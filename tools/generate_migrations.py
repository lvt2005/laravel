#!/usr/bin/env python3
"""Utility to convert SQL dumps into Laravel DB::statement-based migrations."""
from __future__ import annotations

import argparse
from collections import defaultdict, deque
from datetime import datetime, timedelta
from pathlib import Path
import re
import sys

CREATE_PATTERN = re.compile(r"CREATE TABLE `(?P<name>[^`]+)`.*?\)\s*ENGINE=.*?;", re.S)
REF_PATTERN = re.compile(r"REFERENCES\s+`([^`]+)`", re.I)


def studly(name: str) -> str:
    return "".join(part.capitalize() for part in name.split("_"))


def extract_create_statement(sql_text: str, source: Path) -> tuple[str, str]:
    match = CREATE_PATTERN.search(sql_text)
    if not match:
        raise ValueError(f"Could not locate CREATE TABLE statement in {source}")
    return match.group("name"), match.group(0).strip()


def topo_sort(graph: dict[str, set[str]], in_degree: dict[str, int]) -> list[str]:
    queue = deque(sorted(node for node, degree in in_degree.items() if degree == 0))
    ordered: list[str] = []
    while queue:
        node = queue.popleft()
        ordered.append(node)
        for neighbor in sorted(graph[node]):
            in_degree[neighbor] -= 1
            if in_degree[neighbor] == 0:
                queue.append(neighbor)
    if len(ordered) != len(graph):
        missing = set(graph).difference(ordered)
        raise RuntimeError(f"Cycle detected among tables: {', '.join(sorted(missing))}")
    return ordered


def build_template(statement: str, table: str) -> str:
    return f"""<?php

use Illuminate\\Database\\Migrations\\Migration;
use Illuminate\\Support\\Facades\\DB;
use Illuminate\\Support\\Facades\\Schema;

return new class extends Migration
{{
    public function up(): void
    {{
        DB::statement(<<<'SQL'
{statement}
SQL);
    }}

    public function down(): void
    {{
        Schema::dropIfExists('{table}');
    }}
}};
"""


def main() -> None:
    parser = argparse.ArgumentParser(description="Generate Laravel migrations from SQL files")
    default_dump = Path(__file__).resolve().parents[3] / "Dump20251123"
    default_output = Path(__file__).resolve().parents[1] / "database" / "migrations"
    parser.add_argument("--dump-dir", default=str(default_dump), help="Directory containing .sql dumps")
    parser.add_argument("--output-dir", default=str(default_output), help="Destination migrations directory")
    parser.add_argument("--timestamp-start", default="2025_11_24_000000", help="Starting timestamp for migration filenames")
    args = parser.parse_args()

    dump_dir = Path(args.dump_dir).resolve()
    out_dir = Path(args.output_dir).resolve()
    if not dump_dir.exists():
        sys.exit(f"Dump directory not found: {dump_dir}")
    out_dir.mkdir(parents=True, exist_ok=True)

    sql_files = sorted(dump_dir.glob("*.sql"))
    if not sql_files:
        sys.exit(f"No .sql files found under {dump_dir}")

    tables: dict[str, str] = {}
    dependencies: dict[str, set[str]] = defaultdict(set)
    for sql_file in sql_files:
        sql_text = sql_file.read_text(encoding="utf-8")
        table, statement = extract_create_statement(sql_text, sql_file)
        tables[table] = statement
        for ref in REF_PATTERN.findall(statement):
            if ref != table:
                dependencies[table].add(ref)
        dependencies.setdefault(table, set())

    graph: dict[str, set[str]] = {table: set() for table in tables}
    in_degree: dict[str, int] = {table: 0 for table in tables}

    for table, deps in dependencies.items():
        for dep in deps:
            if dep not in tables:
                continue
            if table not in graph[dep]:
                graph[dep].add(table)
                in_degree[table] += 1

    ordered_tables = topo_sort(graph, in_degree)

    current = datetime.strptime(args.timestamp_start, "%Y_%m_%d_%H%M%S")
    for table in ordered_tables:
        timestamp = current.strftime("%Y_%m_%d_%H%M%S")
        filename = f"{timestamp}_create_{table}_table.php"
        content = build_template(tables[table], table)
        (out_dir / filename).write_text(content, encoding="utf-8")
        current += timedelta(seconds=1)
        print(f"Wrote {filename}")


if __name__ == "__main__":
    main()

