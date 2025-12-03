<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== SEEDING TREATMENT SERVICES ===\n\n";

$services = [
    [
        'name' => 'Khám tổng quát',
        'description' => 'Khám sức khỏe tổng quát với bác sĩ chuyên khoa',
        'price' => '300000',
        'duration_minutes' => 30,
        'is_active' => true,
    ],
    [
        'name' => 'Khám chuyên khoa Nội',
        'description' => 'Khám và tư vấn các bệnh lý nội khoa',
        'price' => '350000',
        'duration_minutes' => 30,
        'is_active' => true,
    ],
    [
        'name' => 'Khám chuyên khoa Ngoại',
        'description' => 'Khám và tư vấn các bệnh lý ngoại khoa',
        'price' => '400000',
        'duration_minutes' => 30,
        'is_active' => true,
    ],
    [
        'name' => 'Khám Tai - Mũi - Họng',
        'description' => 'Khám và điều trị các bệnh lý tai mũi họng',
        'price' => '350000',
        'duration_minutes' => 30,
        'is_active' => true,
    ],
    [
        'name' => 'Khám Thần kinh - Cột sống',
        'description' => 'Khám và tư vấn các bệnh lý thần kinh, cột sống',
        'price' => '500000',
        'duration_minutes' => 45,
        'is_active' => true,
    ],
    [
        'name' => 'Khám Chấn thương Chỉnh hình',
        'description' => 'Khám và điều trị các chấn thương cơ xương khớp',
        'price' => '450000',
        'duration_minutes' => 45,
        'is_active' => true,
    ],
    [
        'name' => 'Nội soi dạ dày',
        'description' => 'Nội soi chẩn đoán và điều trị bệnh lý dạ dày',
        'price' => '1500000',
        'duration_minutes' => 60,
        'is_active' => true,
    ],
    [
        'name' => 'Nội soi đại tràng',
        'description' => 'Nội soi chẩn đoán và điều trị bệnh lý đại tràng',
        'price' => '2000000',
        'duration_minutes' => 90,
        'is_active' => true,
    ],
    [
        'name' => 'Siêu âm ổ bụng',
        'description' => 'Siêu âm chẩn đoán các cơ quan vùng bụng',
        'price' => '350000',
        'duration_minutes' => 30,
        'is_active' => true,
    ],
    [
        'name' => 'Chụp X-quang',
        'description' => 'Chụp X-quang các bộ phận cơ thể',
        'price' => '200000',
        'duration_minutes' => 15,
        'is_active' => true,
    ],
    [
        'name' => 'Chụp CT Scanner',
        'description' => 'Chụp cắt lớp vi tính CT đa dạng các vùng',
        'price' => '2500000',
        'duration_minutes' => 45,
        'is_active' => true,
    ],
    [
        'name' => 'Chụp MRI',
        'description' => 'Chụp cộng hưởng từ MRI',
        'price' => '3500000',
        'duration_minutes' => 60,
        'is_active' => true,
    ],
    [
        'name' => 'Xét nghiệm máu tổng quát',
        'description' => 'Xét nghiệm công thức máu, sinh hóa máu cơ bản',
        'price' => '500000',
        'duration_minutes' => 30,
        'is_active' => true,
    ],
    [
        'name' => 'Gói tầm soát ung thư',
        'description' => 'Gói xét nghiệm marker ung thư và khám tổng quát',
        'price' => '5000000',
        'duration_minutes' => 120,
        'is_active' => true,
    ],
    [
        'name' => 'Khám sức khỏe định kỳ',
        'description' => 'Gói khám sức khỏe định kỳ toàn diện',
        'price' => '2500000',
        'duration_minutes' => 120,
        'is_active' => true,
    ],
    [
        'name' => 'Vật lý trị liệu',
        'description' => 'Điều trị phục hồi chức năng bằng vật lý trị liệu',
        'price' => '300000',
        'duration_minutes' => 45,
        'is_active' => true,
    ],
];

$inserted = 0;
foreach ($services as $service) {
    $exists = DB::table('treatment_service')
        ->where('name', $service['name'])
        ->exists();
    
    if (!$exists) {
        $service['created_at'] = now();
        $service['updated_at'] = now();
        DB::table('treatment_service')->insert($service);
        echo "✅ Added: {$service['name']}\n";
        $inserted++;
    } else {
        echo "⏭️  Skipped (exists): {$service['name']}\n";
    }
}

echo "\n=== DONE: {$inserted} services added ===\n";
