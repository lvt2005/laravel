<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;

/*
|--------------------------------------------------------------------------
| Trang công khai (không cần đăng nhập)
|--------------------------------------------------------------------------
*/

// Trang chủ - redirect đến trang bảng điều khiển
Route::get('/', function () {
    return redirect()->route('bang-dieu-khien');
})->name('trang-chu');

// Trang bảng điều khiển (dashboard) - Landing page chính
Route::get('/bang-dieu-khien', function () {
    return view('user.dashboard');
})->name('bang-dieu-khien');

// Trang giới thiệu đặt lịch
Route::get('/dat-lich', function () {
    return view('nologin.booking-intro');
})->name('dat-lich');

// Trang biểu mẫu đặt lịch (hỗ trợ cả URL với query param ?doctor=xxx)
Route::get('/dat-lich/bieu-mau', function () {
    return view('nologin.booking-flow');
})->name('dat-lich.bieu-mau');

// Trang tra cứu lịch hẹn
Route::get('/tra-cuu-lich-hen', function () {
    return view('nologin.lookup-appointment');
})->name('tra-cuu-lich-hen');

// Trang tìm kiếm bác sĩ
Route::get('/tim-bac-si', function () {
    return view('nologin.search-doctor');
})->name('tim-bac-si');

// Alias cho trang tìm bác sĩ
Route::get('/bac-si/tim-kiem', function () {
    return redirect()->route('tim-bac-si');
});

// Trang đăng nhập
Route::get('/dang-nhap', function () {
    return view('nologin.login');
})->name('dang-nhap');

// Trang quên mật khẩu
Route::get('/quen-mat-khau', function () {
    return view('nologin.forgot');
})->name('quen-mat-khau');

// Google OAuth callback - xử lý response từ Google
Route::get('/dang-nhap/google-callback', function () {
    return view('nologin.google-callback');
})->name('google-callback');

/*
|--------------------------------------------------------------------------
| Trang người dùng (cần đăng nhập)
|--------------------------------------------------------------------------
*/

// Trang hồ sơ cá nhân (dashboard sau đăng nhập)
Route::get('/ho-so', function () {
    return view('user.profile');
})->name('ho-so');

// Dashboard người dùng
Route::get('/user/dashboard', function () {
    return view('user.dashboard');
})->name('user.dashboard');

/*
|--------------------------------------------------------------------------
| Trang bác sĩ (cần đăng nhập với vai trò doctor)
|--------------------------------------------------------------------------
*/

// Trang hồ sơ bác sĩ
Route::get('/bac-si/ho-so', function () {
    return view('doctor.profile');
})->name('bac-si.ho-so');

// Alias cho trang bác sĩ
Route::get('/bac-si', function () {
    return redirect()->route('bac-si.ho-so');
})->name('bac-si');

/*
|--------------------------------------------------------------------------
| Trang quản trị (cần đăng nhập với vai trò admin)
|--------------------------------------------------------------------------
*/

// Trang quản trị admin
Route::get('/quan-tri', function () {
    return view('admin.profile');
})->name('quan-tri');

// Alias cho admin - hỗ trợ URL cũ
Route::get('/admin', function () {
    return redirect()->route('quan-tri');
})->name('admin');

// Backward compatibility - URL cũ redirect sang URL mới
Route::get('/admin/ho-so', function () {
    return redirect()->route('quan-tri');
});

/*
|--------------------------------------------------------------------------
| Trang chuyên khoa
|--------------------------------------------------------------------------
*/

// Trang danh sách chuyên khoa
Route::get('/chuyen-khoa', [PageController::class, 'allDepartments'])->name('chuyen-khoa');

// Các chuyên khoa chi tiết
Route::get('/chuyen-khoa/{slug}', [PageController::class, 'specialty'])->name('chuyen-khoa.chi-tiet');

/*
|--------------------------------------------------------------------------
| Trang dịch vụ
|--------------------------------------------------------------------------
*/

Route::get('/dich-vu/{slug}', [PageController::class, 'service'])->name('dich-vu.slug');
Route::get('/dich-vu/chi-tiet/{id}', [PageController::class, 'serviceById'])->name('dich-vu.chi-tiet')->where('id', '[0-9]+');

/*
|--------------------------------------------------------------------------
| Trang chi tiết bác sĩ
|--------------------------------------------------------------------------
*/

Route::get('/bac-si/{id}', [PageController::class, 'doctorDetail'])->name('bac-si.chi-tiet')->where('id', '[0-9]+');

/*
|--------------------------------------------------------------------------
| Trang hướng dẫn khách hàng
|--------------------------------------------------------------------------
*/

// Trang liên hệ
Route::get('/lien-he', function () {
    return view('pages.contact');
})->name('lien-he');

// Trang hướng dẫn bảo hiểm y tế
Route::get('/huong-dan/bao-hiem-y-te', function () {
    return view('pages.health-insurance');
})->name('huong-dan.bao-hiem');

// Trang bảng giá tiền giường
Route::get('/huong-dan/bang-gia-tien-giuong', function () {
    return view('pages.hospital-pricing');
})->name('huong-dan.bang-gia');

// Trang quyền và nghĩa vụ người bệnh
Route::get('/huong-dan/quyen-va-nghia-vu', function () {
    return view('pages.rights-obligations');
})->name('huong-dan.quyen-nghia-vu');
