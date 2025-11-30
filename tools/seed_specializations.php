<?php
require __DIR__.'/../vendor/autoload.php';
$app = require __DIR__.'/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Specialization;
use App\Models\TreatmentService;

$specializations = [
    [
        'name' => 'Nội khoa',
        'description' => 'Khám và điều trị các bệnh nội khoa, bệnh mãn tính',
        'services' => [
            ['name' => 'Khám nội tổng quát', 'price' => 200000, 'duration' => 30],
            ['name' => 'Khám tim mạch', 'price' => 300000, 'duration' => 45],
            ['name' => 'Khám hô hấp', 'price' => 250000, 'duration' => 30],
            ['name' => 'Khám tiêu hóa', 'price' => 250000, 'duration' => 30],
            ['name' => 'Khám thận - tiết niệu', 'price' => 280000, 'duration' => 30],
        ]
    ],
    [
        'name' => 'Ngoại khoa',
        'description' => 'Khám và phẫu thuật các bệnh lý ngoại khoa',
        'services' => [
            ['name' => 'Khám ngoại tổng quát', 'price' => 200000, 'duration' => 30],
            ['name' => 'Tiểu phẫu', 'price' => 500000, 'duration' => 60],
            ['name' => 'Phẫu thuật nội soi', 'price' => 5000000, 'duration' => 120],
            ['name' => 'Thay băng vết thương', 'price' => 100000, 'duration' => 15],
        ]
    ],
    [
        'name' => 'Sản phụ khoa',
        'description' => 'Khám và điều trị các bệnh phụ khoa, thai sản',
        'services' => [
            ['name' => 'Khám phụ khoa', 'price' => 250000, 'duration' => 30],
            ['name' => 'Khám thai định kỳ', 'price' => 300000, 'duration' => 45],
            ['name' => 'Siêu âm thai', 'price' => 350000, 'duration' => 30],
            ['name' => 'Xét nghiệm sàng lọc trước sinh', 'price' => 800000, 'duration' => 30],
        ]
    ],
    [
        'name' => 'Răng Hàm Mặt',
        'description' => 'Khám và điều trị các bệnh răng miệng',
        'services' => [
            ['name' => 'Khám răng tổng quát', 'price' => 150000, 'duration' => 20],
            ['name' => 'Nhổ răng', 'price' => 300000, 'duration' => 30],
            ['name' => 'Trám răng', 'price' => 250000, 'duration' => 30],
            ['name' => 'Cạo vôi răng', 'price' => 200000, 'duration' => 30],
            ['name' => 'Tẩy trắng răng', 'price' => 1500000, 'duration' => 60],
        ]
    ],
    [
        'name' => 'Y học Cổ truyền',
        'description' => 'Khám và điều trị bằng phương pháp y học cổ truyền',
        'services' => [
            ['name' => 'Khám đông y', 'price' => 200000, 'duration' => 30],
            ['name' => 'Châm cứu', 'price' => 150000, 'duration' => 45],
            ['name' => 'Xoa bóp bấm huyệt', 'price' => 200000, 'duration' => 45],
            ['name' => 'Giác hơi', 'price' => 100000, 'duration' => 30],
        ]
    ],
    [
        'name' => 'Da liễu',
        'description' => 'Khám và điều trị các bệnh về da',
        'services' => [
            ['name' => 'Khám da liễu', 'price' => 200000, 'duration' => 20],
            ['name' => 'Điều trị mụn', 'price' => 350000, 'duration' => 30],
            ['name' => 'Laser trị nám', 'price' => 1000000, 'duration' => 45],
            ['name' => 'Điều trị viêm da', 'price' => 300000, 'duration' => 30],
        ]
    ],
    [
        'name' => 'Tâm thần',
        'description' => 'Khám và điều trị các bệnh tâm thần, rối loạn tâm lý',
        'services' => [
            ['name' => 'Khám tâm thần', 'price' => 300000, 'duration' => 45],
            ['name' => 'Tư vấn tâm lý', 'price' => 400000, 'duration' => 60],
            ['name' => 'Trị liệu tâm lý', 'price' => 500000, 'duration' => 60],
        ]
    ],
    [
        'name' => 'Tai Mũi Họng',
        'description' => 'Khám và điều trị các bệnh tai mũi họng',
        'services' => [
            ['name' => 'Khám tai mũi họng', 'price' => 200000, 'duration' => 20],
            ['name' => 'Nội soi tai mũi họng', 'price' => 350000, 'duration' => 30],
            ['name' => 'Đo thính lực', 'price' => 200000, 'duration' => 30],
            ['name' => 'Rửa mũi xoang', 'price' => 150000, 'duration' => 20],
        ]
    ],
    [
        'name' => 'Nhãn khoa',
        'description' => 'Khám và điều trị các bệnh về mắt',
        'services' => [
            ['name' => 'Khám mắt tổng quát', 'price' => 200000, 'duration' => 30],
            ['name' => 'Đo khúc xạ', 'price' => 150000, 'duration' => 20],
            ['name' => 'Soi đáy mắt', 'price' => 200000, 'duration' => 20],
            ['name' => 'Phẫu thuật đục thủy tinh thể', 'price' => 8000000, 'duration' => 60],
        ]
    ],
    [
        'name' => 'Phục hồi chức năng',
        'description' => 'Vật lý trị liệu và phục hồi chức năng',
        'services' => [
            ['name' => 'Khám phục hồi chức năng', 'price' => 200000, 'duration' => 30],
            ['name' => 'Vật lý trị liệu', 'price' => 250000, 'duration' => 45],
            ['name' => 'Điện trị liệu', 'price' => 150000, 'duration' => 30],
            ['name' => 'Tập vận động trị liệu', 'price' => 200000, 'duration' => 45],
        ]
    ],
    [
        'name' => 'Y học dự phòng/Y tế công cộng',
        'description' => 'Tư vấn và dịch vụ y tế dự phòng',
        'services' => [
            ['name' => 'Tư vấn sức khỏe', 'price' => 150000, 'duration' => 30],
            ['name' => 'Tiêm vắc xin', 'price' => 100000, 'duration' => 15],
            ['name' => 'Khám sức khỏe định kỳ', 'price' => 500000, 'duration' => 60],
            ['name' => 'Tầm soát ung thư', 'price' => 2000000, 'duration' => 90],
        ]
    ],
    [
        'name' => 'Cận lâm sàng',
        'description' => 'Xét nghiệm và chẩn đoán hình ảnh',
        'services' => [
            ['name' => 'Xét nghiệm máu tổng quát', 'price' => 200000, 'duration' => 15],
            ['name' => 'Xét nghiệm nước tiểu', 'price' => 100000, 'duration' => 15],
            ['name' => 'Chụp X-quang', 'price' => 150000, 'duration' => 15],
            ['name' => 'Siêu âm ổ bụng', 'price' => 300000, 'duration' => 20],
            ['name' => 'Chụp CT Scanner', 'price' => 1500000, 'duration' => 30],
            ['name' => 'Chụp MRI', 'price' => 3000000, 'duration' => 45],
        ]
    ],
    [
        'name' => 'Dược',
        'description' => 'Tư vấn dược và cấp phát thuốc',
        'services' => [
            ['name' => 'Tư vấn sử dụng thuốc', 'price' => 50000, 'duration' => 15],
            ['name' => 'Pha chế thuốc theo đơn', 'price' => 100000, 'duration' => 30],
        ]
    ],
    [
        'name' => 'Điều dưỡng',
        'description' => 'Dịch vụ chăm sóc điều dưỡng',
        'services' => [
            ['name' => 'Tiêm truyền', 'price' => 100000, 'duration' => 30],
            ['name' => 'Thay băng', 'price' => 80000, 'duration' => 15],
            ['name' => 'Chăm sóc vết thương', 'price' => 150000, 'duration' => 30],
            ['name' => 'Hút đàm', 'price' => 100000, 'duration' => 15],
        ]
    ],
];

echo "=== SEEDING SPECIALIZATIONS & SERVICES ===\n\n";

// Xóa dữ liệu cũ
TreatmentService::query()->delete();
Specialization::query()->delete();

foreach ($specializations as $specData) {
    $spec = Specialization::create([
        'name' => $specData['name'],
        'description' => $specData['description'],
    ]);
    echo "✅ Chuyên khoa: {$spec->name}\n";
    
    foreach ($specData['services'] as $svcData) {
        TreatmentService::create([
            'specialization_id' => $spec->id,
            'name' => $svcData['name'],
            'price' => $svcData['price'],
            'duration_minutes' => $svcData['duration'],
            'is_active' => true,
            'description' => "Dịch vụ {$svcData['name']} thuộc chuyên khoa {$spec->name}",
        ]);
        echo "   - {$svcData['name']}\n";
    }
    echo "\n";
}

echo "=== DONE ===\n";
