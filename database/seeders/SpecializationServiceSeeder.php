<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SpecializationServiceSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing data
        DB::table('treatment_service')->delete();
        DB::table('specialization')->delete();

        $specializations = [
            [
                'name' => 'Khoa Khám Bệnh',
                'description' => 'Khoa khám bệnh tổng quát, tiếp nhận và thăm khám bệnh nhân ban đầu',
                'slug' => 'khoa-kham-benh',
                'services' => [
                    ['name' => 'Khám tổng quát', 'price' => 200000, 'duration' => 30],
                    ['name' => 'Khám sức khỏe định kỳ', 'price' => 500000, 'duration' => 60],
                    ['name' => 'Tư vấn sức khỏe', 'price' => 150000, 'duration' => 20],
                ]
            ],
            [
                'name' => 'Phòng Khám Ngoại Thần Kinh',
                'description' => 'Chuyên khám và điều trị các bệnh lý về thần kinh ngoại biên và trung ương',
                'slug' => 'ngoai-than-kinh',
                'services' => [
                    ['name' => 'Khám thần kinh', 'price' => 300000, 'duration' => 40],
                    ['name' => 'Điện não đồ', 'price' => 450000, 'duration' => 45],
                    ['name' => 'Chụp MRI não', 'price' => 2500000, 'duration' => 60],
                    ['name' => 'Điều trị đau đầu mãn tính', 'price' => 350000, 'duration' => 30],
                ]
            ],
            [
                'name' => 'Phòng Khám Chấn thương Chỉnh hình, Cơ-Xương-Khớp',
                'description' => 'Chuyên điều trị các bệnh lý về xương khớp, chấn thương chỉnh hình',
                'slug' => 'chan-thuong-chinh-hinh',
                'services' => [
                    ['name' => 'Khám cơ xương khớp', 'price' => 280000, 'duration' => 35],
                    ['name' => 'Nắn chỉnh xương', 'price' => 500000, 'duration' => 45],
                    ['name' => 'Tiêm nội khớp', 'price' => 800000, 'duration' => 30],
                    ['name' => 'Phẫu thuật nội soi khớp', 'price' => 15000000, 'duration' => 120],
                    ['name' => 'Vật lý trị liệu khớp', 'price' => 250000, 'duration' => 45],
                ]
            ],
            [
                'name' => 'Phòng Ngoại Tổng Hợp',
                'description' => 'Khám và điều trị các bệnh ngoại khoa tổng hợp',
                'slug' => 'ngoai-tong-hop',
                'services' => [
                    ['name' => 'Khám ngoại tổng quát', 'price' => 250000, 'duration' => 30],
                    ['name' => 'Tiểu phẫu', 'price' => 1000000, 'duration' => 60],
                    ['name' => 'Khâu vết thương', 'price' => 300000, 'duration' => 30],
                    ['name' => 'Phẫu thuật u nang', 'price' => 3500000, 'duration' => 90],
                ]
            ],
            [
                'name' => 'Phòng Nội Tổng Hợp',
                'description' => 'Khám và điều trị các bệnh nội khoa tổng hợp',
                'slug' => 'noi-tong-hop',
                'services' => [
                    ['name' => 'Khám nội tổng quát', 'price' => 250000, 'duration' => 30],
                    ['name' => 'Khám tim mạch', 'price' => 350000, 'duration' => 40],
                    ['name' => 'Khám tiêu hóa', 'price' => 300000, 'duration' => 35],
                    ['name' => 'Khám hô hấp', 'price' => 280000, 'duration' => 30],
                    ['name' => 'Điện tâm đồ', 'price' => 150000, 'duration' => 15],
                ]
            ],
            [
                'name' => 'Phòng Khám Tai - Mũi - Họng',
                'description' => 'Chuyên khám và điều trị các bệnh về tai, mũi, họng',
                'slug' => 'tai-mui-hong',
                'services' => [
                    ['name' => 'Khám tai mũi họng', 'price' => 250000, 'duration' => 30],
                    ['name' => 'Nội soi tai mũi họng', 'price' => 400000, 'duration' => 30],
                    ['name' => 'Đo thính lực', 'price' => 200000, 'duration' => 20],
                    ['name' => 'Rửa mũi xoang', 'price' => 150000, 'duration' => 15],
                    ['name' => 'Phẫu thuật VA', 'price' => 5000000, 'duration' => 90],
                ]
            ],
            [
                'name' => 'Khoa Vật Lý Trị Liệu - Phục Hồi Chức Năng',
                'description' => 'Phục hồi chức năng và vật lý trị liệu cho bệnh nhân',
                'slug' => 'vat-ly-tri-lieu',
                'services' => [
                    ['name' => 'Vật lý trị liệu cơ bản', 'price' => 200000, 'duration' => 45],
                    ['name' => 'Điện trị liệu', 'price' => 180000, 'duration' => 30],
                    ['name' => 'Xoa bóp trị liệu', 'price' => 250000, 'duration' => 45],
                    ['name' => 'Phục hồi chức năng sau phẫu thuật', 'price' => 350000, 'duration' => 60],
                    ['name' => 'Châm cứu', 'price' => 200000, 'duration' => 30],
                ]
            ],
            [
                'name' => 'Khoa Gây mê - Hồi Sức',
                'description' => 'Chuyên gây mê và hồi sức cho các ca phẫu thuật',
                'slug' => 'gay-me-hoi-suc',
                'services' => [
                    ['name' => 'Gây mê toàn thân', 'price' => 2000000, 'duration' => 0],
                    ['name' => 'Gây tê tủy sống', 'price' => 1500000, 'duration' => 0],
                    ['name' => 'Gây tê ngoài màng cứng', 'price' => 1800000, 'duration' => 0],
                    ['name' => 'Hồi sức sau phẫu thuật', 'price' => 1000000, 'duration' => 0],
                ]
            ],
            [
                'name' => 'Khoa Hồi Sức Tích Cực - Cấp Cứu',
                'description' => 'Cấp cứu và hồi sức tích cực cho bệnh nhân nặng',
                'slug' => 'hoi-suc-cap-cuu',
                'services' => [
                    ['name' => 'Cấp cứu ban đầu', 'price' => 500000, 'duration' => 0],
                    ['name' => 'Hồi sức tim phổi', 'price' => 2000000, 'duration' => 0],
                    ['name' => 'Theo dõi tích cực 24h', 'price' => 3000000, 'duration' => 0],
                    ['name' => 'Thở máy', 'price' => 1500000, 'duration' => 0],
                ]
            ],
            [
                'name' => 'Khoa Xét Nghiệm',
                'description' => 'Xét nghiệm các mẫu bệnh phẩm phục vụ chẩn đoán',
                'slug' => 'xet-nghiem',
                'services' => [
                    ['name' => 'Xét nghiệm máu tổng quát', 'price' => 150000, 'duration' => 15],
                    ['name' => 'Xét nghiệm sinh hóa máu', 'price' => 300000, 'duration' => 15],
                    ['name' => 'Xét nghiệm nước tiểu', 'price' => 80000, 'duration' => 10],
                    ['name' => 'Xét nghiệm vi sinh', 'price' => 250000, 'duration' => 15],
                    ['name' => 'Xét nghiệm gen', 'price' => 2500000, 'duration' => 30],
                ]
            ],
            [
                'name' => 'Khoa Chẩn đoán Hình ảnh - Thăm Dò Chức Năng',
                'description' => 'Chẩn đoán hình ảnh và thăm dò chức năng các cơ quan',
                'slug' => 'chan-doan-hinh-anh',
                'services' => [
                    ['name' => 'Chụp X-quang', 'price' => 150000, 'duration' => 15],
                    ['name' => 'Siêu âm tổng quát', 'price' => 250000, 'duration' => 20],
                    ['name' => 'Siêu âm tim', 'price' => 400000, 'duration' => 30],
                    ['name' => 'Chụp CT Scanner', 'price' => 1500000, 'duration' => 30],
                    ['name' => 'Chụp MRI', 'price' => 3000000, 'duration' => 45],
                    ['name' => 'Nội soi dạ dày', 'price' => 800000, 'duration' => 30],
                    ['name' => 'Nội soi đại tràng', 'price' => 1200000, 'duration' => 45],
                ]
            ],
            [
                'name' => 'Khoa Dược',
                'description' => 'Cung cấp và quản lý thuốc cho bệnh nhân',
                'slug' => 'khoa-duoc',
                'services' => [
                    ['name' => 'Tư vấn sử dụng thuốc', 'price' => 50000, 'duration' => 15],
                    ['name' => 'Kiểm tra tương tác thuốc', 'price' => 100000, 'duration' => 15],
                    ['name' => 'Pha chế thuốc theo đơn', 'price' => 100000, 'duration' => 30],
                ]
            ],
        ];

        foreach ($specializations as $spec) {
            // Create specialization
            $specId = DB::table('specialization')->insertGetId([
                'name' => $spec['name'],
                'description' => $spec['description'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Create services for this specialization
            foreach ($spec['services'] as $service) {
                DB::table('treatment_service')->insert([
                    'name' => $service['name'],
                    'description' => 'Dịch vụ ' . $service['name'] . ' tại ' . $spec['name'],
                    'price' => $service['price'],
                    'duration_minutes' => $service['duration'],
                    'is_active' => true,
                    'specialization_id' => $specId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        $this->command->info('Đã tạo ' . count($specializations) . ' chuyên khoa và các dịch vụ liên quan!');
    }
}
