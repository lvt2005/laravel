<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    // Dữ liệu các chuyên khoa
    private $specialties = [
        'ngoai-than-kinh' => [
            'title' => 'Ngoại Thần Kinh - Cột Sống',
            'subtitle' => 'Chuyên điều trị các bệnh về thần kinh và cột sống',
            'icon' => 'fas fa-brain',
            'description' => 'Khoa Ngoại Thần Kinh - Cột Sống chuyên khám và điều trị các bệnh lý về hệ thần kinh trung ương, ngoại biên và cột sống. Với đội ngũ bác sĩ giàu kinh nghiệm và trang thiết bị hiện đại, chúng tôi cam kết mang đến dịch vụ chăm sóc sức khỏe tốt nhất cho bệnh nhân.',
            'features' => [
                'Phẫu thuật u não, u tủy sống',
                'Điều trị thoát vị đĩa đệm',
                'Phẫu thuật cột sống ít xâm lấn',
                'Điều trị đau dây thần kinh',
                'Phẫu thuật chấn thương sọ não',
            ]
        ],
        'chan-thuong-chinh-hinh' => [
            'title' => 'Chấn Thương Chỉnh Hình',
            'subtitle' => 'Điều trị cơ xương khớp và chấn thương',
            'icon' => 'fas fa-bone',
            'description' => 'Khoa Chấn Thương Chỉnh Hình chuyên khám và điều trị các bệnh lý về cơ xương khớp, chấn thương thể thao, và các dị tật bẩm sinh về xương khớp. Áp dụng các kỹ thuật phẫu thuật tiên tiến nhất hiện nay.',
            'features' => [
                'Phẫu thuật thay khớp háng, khớp gối',
                'Điều trị gãy xương',
                'Nội soi khớp',
                'Phẫu thuật chấn thương thể thao',
                'Điều trị thoái hóa khớp',
            ]
        ],
        'ngoai-tong-hop' => [
            'title' => 'Ngoại Tổng Hợp',
            'subtitle' => 'Phẫu thuật và điều trị ngoại khoa tổng quát',
            'icon' => 'fas fa-procedures',
            'description' => 'Khoa Ngoại Tổng Hợp thực hiện các ca phẫu thuật và điều trị ngoại khoa đa dạng, từ các bệnh lý đường tiêu hóa, gan mật đến các khối u và bệnh lý tuyến giáp.',
            'features' => [
                'Phẫu thuật ổ bụng',
                'Phẫu thuật gan mật',
                'Cắt ruột thừa nội soi',
                'Phẫu thuật thoát vị',
                'Điều trị sỏi mật',
            ]
        ],
        'noi-tong-hop' => [
            'title' => 'Nội Tổng Hợp',
            'subtitle' => 'Khám và điều trị các bệnh nội khoa',
            'icon' => 'fas fa-heartbeat',
            'description' => 'Khoa Nội Tổng Hợp chuyên khám, tư vấn và điều trị các bệnh lý nội khoa như tim mạch, hô hấp, tiêu hóa, thận, nội tiết và các bệnh lý khác.',
            'features' => [
                'Khám và điều trị bệnh tim mạch',
                'Điều trị bệnh hô hấp',
                'Khám tiêu hóa',
                'Điều trị đái tháo đường',
                'Khám sức khỏe tổng quát',
            ]
        ],
        'tai-mui-hong' => [
            'title' => 'Tai - Mũi - Họng',
            'subtitle' => 'Chuyên khám và điều trị bệnh tai mũi họng',
            'icon' => 'fas fa-head-side-mask',
            'description' => 'Khoa Tai Mũi Họng cung cấp dịch vụ khám và điều trị toàn diện các bệnh lý về tai, mũi, họng cho mọi lứa tuổi với đội ngũ bác sĩ chuyên khoa giàu kinh nghiệm.',
            'features' => [
                'Nội soi tai mũi họng',
                'Phẫu thuật amidan, VA',
                'Điều trị viêm xoang',
                'Phẫu thuật chỉnh hình mũi',
                'Điều trị ù tai, điếc',
            ]
        ],
        'vat-ly-tri-lieu' => [
            'title' => 'Vật Lý Trị Liệu - Phục Hồi Chức Năng',
            'subtitle' => 'Phục hồi chức năng và vật lý trị liệu',
            'icon' => 'fas fa-walking',
            'description' => 'Khoa Vật Lý Trị Liệu - Phục Hồi Chức Năng giúp bệnh nhân phục hồi sau chấn thương, phẫu thuật hoặc các bệnh lý mãn tính thông qua các phương pháp vật lý trị liệu tiên tiến.',
            'features' => [
                'Phục hồi sau phẫu thuật',
                'Điều trị đau cơ xương khớp',
                'Vật lý trị liệu cho trẻ em',
                'Phục hồi sau đột quỵ',
                'Điện trị liệu, siêu âm trị liệu',
            ]
        ],
        'gay-me' => [
            'title' => 'Gây Mê - Hồi Sức',
            'subtitle' => 'Dịch vụ gây mê và hồi sức sau phẫu thuật',
            'icon' => 'fas fa-syringe',
            'description' => 'Khoa Gây Mê - Hồi Sức đảm bảo an toàn cho bệnh nhân trong suốt quá trình phẫu thuật và theo dõi hồi sức sau mổ với đội ngũ bác sĩ gây mê hồi sức chuyên nghiệp.',
            'features' => [
                'Gây mê toàn thân',
                'Gây tê vùng',
                'Hồi sức sau phẫu thuật',
                'Kiểm soát đau',
                'Theo dõi bệnh nhân 24/7',
            ]
        ],
        'hoi-suc-tich-cuc' => [
            'title' => 'Hồi Sức Tích Cực - Cấp Cứu',
            'subtitle' => 'Cấp cứu và hồi sức tích cực 24/7',
            'icon' => 'fas fa-ambulance',
            'description' => 'Khoa Hồi Sức Tích Cực - Cấp Cứu hoạt động 24/7, sẵn sàng tiếp nhận và xử lý các trường hợp cấp cứu với đội ngũ y bác sĩ chuyên nghiệp và trang thiết bị hiện đại.',
            'features' => [
                'Cấp cứu 24/7',
                'Hồi sức tim phổi',
                'Điều trị sốc',
                'Theo dõi bệnh nhân nặng',
                'Hỗ trợ hô hấp',
            ]
        ],
        'xet-nghiem' => [
            'title' => 'Xét Nghiệm',
            'subtitle' => 'Dịch vụ xét nghiệm đa dạng và chính xác',
            'icon' => 'fas fa-vials',
            'description' => 'Khoa Xét Nghiệm cung cấp đầy đủ các loại xét nghiệm từ cơ bản đến chuyên sâu với kết quả nhanh chóng và chính xác, hỗ trợ chẩn đoán và điều trị bệnh.',
            'features' => [
                'Xét nghiệm máu',
                'Xét nghiệm nước tiểu',
                'Xét nghiệm sinh hóa',
                'Xét nghiệm vi sinh',
                'Xét nghiệm gen',
            ]
        ],
        'chan-doan-hinh-anh' => [
            'title' => 'Chẩn Đoán Hình Ảnh',
            'subtitle' => 'X-quang, CT, MRI và siêu âm',
            'icon' => 'fas fa-x-ray',
            'description' => 'Khoa Chẩn Đoán Hình Ảnh được trang bị các thiết bị chẩn đoán hình ảnh hiện đại như X-quang kỹ thuật số, CT scanner, MRI, siêu âm đa dạng để hỗ trợ chẩn đoán chính xác.',
            'features' => [
                'Chụp X-quang kỹ thuật số',
                'CT Scanner đa lát cắt',
                'MRI 1.5T - 3T',
                'Siêu âm 4D',
                'Chụp mạch máu DSA',
            ]
        ],
        'duoc' => [
            'title' => 'Khoa Dược',
            'subtitle' => 'Cung cấp thuốc và tư vấn dược phẩm',
            'icon' => 'fas fa-pills',
            'description' => 'Khoa Dược cung cấp thuốc đảm bảo chất lượng, nguồn gốc rõ ràng cùng dịch vụ tư vấn sử dụng thuốc an toàn và hiệu quả cho bệnh nhân.',
            'features' => [
                'Cung cấp thuốc chất lượng',
                'Tư vấn sử dụng thuốc',
                'Kiểm soát tương tác thuốc',
                'Pha chế thuốc theo đơn',
                'Tư vấn dinh dưỡng lâm sàng',
            ]
        ],
    ];

    // Dữ liệu các dịch vụ
    private $services = [
        'tam-soat-ung-thu' => [
            'title' => 'Tầm Soát Ung Thư',
            'subtitle' => 'Phát hiện sớm để điều trị kịp thời',
            'icon' => 'fas fa-ribbon',
            'description' => 'Chương trình tầm soát ung thư toàn diện giúp phát hiện sớm các dấu hiệu ung thư, từ đó có phương pháp điều trị kịp thời và hiệu quả.',
            'features' => [
                'Tầm soát ung thư vú',
                'Tầm soát ung thư cổ tử cung',
                'Tầm soát ung thư đại trực tràng',
                'Tầm soát ung thư gan',
                'Tầm soát ung thư phổi',
                'Xét nghiệm marker ung thư',
            ]
        ],
        'noi-soi' => [
            'title' => 'Dịch Vụ Nội Soi',
            'subtitle' => 'Nội soi chẩn đoán và điều trị',
            'icon' => 'fas fa-search',
            'description' => 'Dịch vụ nội soi hiện đại với công nghệ HD, NBI giúp chẩn đoán chính xác và điều trị hiệu quả các bệnh lý đường tiêu hóa.',
            'features' => [
                'Nội soi dạ dày',
                'Nội soi đại tràng',
                'Nội soi mật tụy',
                'Nội soi can thiệp',
                'Nội soi không đau',
            ]
        ],
        'kham-suc-khoe-dinh-ky' => [
            'title' => 'Khám Sức Khỏe Định Kỳ',
            'subtitle' => 'Chủ động bảo vệ sức khỏe của bạn',
            'icon' => 'fas fa-clipboard-check',
            'description' => 'Gói khám sức khỏe định kỳ toàn diện giúp bạn theo dõi tình trạng sức khỏe, phát hiện sớm các bệnh lý tiềm ẩn và có kế hoạch chăm sóc sức khỏe phù hợp.',
            'features' => [
                'Khám tổng quát với bác sĩ',
                'Xét nghiệm máu toàn diện',
                'Chụp X-quang ngực',
                'Điện tâm đồ',
                'Siêu âm bụng tổng quát',
                'Đo mật độ xương',
            ]
        ],
    ];

    // Trang danh sách chuyên khoa
    public function allDepartments()
    {
        return view('pages.all-departments');
    }

    // Trang chi tiết chuyên khoa
    public function specialty($slug)
    {
        // Thử tìm theo ID trước
        if (is_numeric($slug)) {
            $specialization = \App\Models\Specialization::withCount(['doctors' => function($q) {
                $q->where('doctor_status', 'ACTIVE');
            }])->with(['services', 'doctors' => function($q) {
                $q->where('doctor_status', 'ACTIVE')
                  ->with(['user', 'clinic'])
                  ->orderByDesc('rating_avg')
                  ->limit(12);
            }])->find($slug);
            
            if ($specialization) {
                return view('pages.specialty.detail', [
                    'specialization' => $specialization,
                    'doctors' => $specialization->doctors
                ]);
            }
        }
        
        // Nếu không tìm thấy theo ID, thử tìm theo slug cũ
        if (!isset($this->specialties[$slug])) {
            abort(404);
        }

        $data = $this->specialties[$slug];
        return view('pages.specialty.template', $data);
    }

    // Trang chi tiết dịch vụ
    public function service($slug)
    {
        // Thử tìm theo ID trước
        if (is_numeric($slug)) {
            $service = \App\Models\TreatmentService::with('specialization')
                ->where('is_active', true)
                ->find($slug);
            
            if ($service) {
                // Lấy danh sách bác sĩ thuộc chuyên khoa này
                $doctors = [];
                if ($service->specialization_id) {
                    $doctors = \App\Models\Doctor::with(['user', 'specialization'])
                        ->where('specialization_id', $service->specialization_id)
                        ->where('doctor_status', 'ACTIVE')
                        ->whereHas('user', function($q) {
                            $q->where('status', 'ACTIVE');
                        })
                        ->orderByDesc('rating_avg')
                        ->limit(6)
                        ->get();
                }
                
                return view('pages.service.detail', [
                    'service' => $service,
                    'doctors' => $doctors
                ]);
            }
        }
        
        // Nếu không tìm thấy theo ID, thử tìm theo slug cũ
        if (!isset($this->services[$slug])) {
            abort(404);
        }

        $data = $this->services[$slug];
        return view('pages.service.template', $data);
    }

    // Trang chi tiết dịch vụ theo ID
    public function serviceById($id)
    {
        $service = \App\Models\TreatmentService::with('specialization')
            ->where('is_active', true)
            ->find($id);
        
        if (!$service) {
            abort(404, 'Không tìm thấy dịch vụ');
        }
        
        // Lấy danh sách bác sĩ thuộc chuyên khoa này
        $doctors = [];
        if ($service->specialization_id) {
            $doctors = \App\Models\Doctor::with(['user', 'specialization', 'clinic'])
                ->where('specialization_id', $service->specialization_id)
                ->where('doctor_status', 'ACTIVE')
                ->whereHas('user', function($q) {
                    $q->where('status', 'ACTIVE');
                })
                ->orderByDesc('rating_avg')
                ->limit(6)
                ->get();
        }
        
        return view('pages.service.detail', [
            'service' => $service,
            'doctors' => $doctors
        ]);
    }

    // Trang chi tiết bác sĩ
    public function doctorDetail($id)
    {
        // Lấy thông tin bác sĩ từ database
        $doctor = \App\Models\Doctor::with(['user', 'specialization', 'clinic'])
            ->where('id', $id)
            ->first();
        
        if (!$doctor) {
            abort(404, 'Không tìm thấy bác sĩ');
        }
        
        // Lấy giới tính
        $genderMap = ['MALE' => 'Nam', 'FEMALE' => 'Nữ', 'OTHER' => 'Khác'];
        $gender = isset($doctor->user->gender) ? ($genderMap[strtoupper($doctor->user->gender)] ?? '') : '';
        
        // Chuẩn bị dữ liệu để hiển thị
        $doctorData = [
            'id' => $doctor->id,
            'name' => $doctor->user->full_name ?? 'Chưa cập nhật',
            'degree' => $doctor->degree ?? '',
            'specialty' => $doctor->specialization->name ?? 'Đa khoa',
            'specialization_id' => $doctor->specialization_id,
            'avatar' => $doctor->user->avatar_url ?? '/frontend/img/default-doctor.png',
            'rating' => number_format($doctor->rating_avg ?? 0, 1),
            'reviews' => $doctor->rating_count ?? 0,
            'experience' => $doctor->experience ?? 0,
            'bio' => $doctor->description ?? '',
            'clinic' => $doctor->clinic->name ?? 'Bệnh viện Nam Sài Gòn',
            'address' => $doctor->clinic->address ?? '70 Đ. Tô Ký, Tân Chánh Hiệp, Quận 12, TP.HCM',
            'consultation_fee' => $doctor->consultation_fee ?? 200000,
            'phone' => $doctor->user->phone ?? '',
            'email' => $doctor->user->email ?? '',
            'gender' => $gender,
            'dob' => $doctor->user->dob ?? '',
        ];

        return view('pages.doctor-detail', ['doctor' => $doctorData]);
    }
}
