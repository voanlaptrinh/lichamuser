<?php

namespace App\Http\Controllers;

use App\Helpers\CatHungHelper;
use App\Helpers\DataHelper;
use App\Helpers\FunctionHelper;
use App\Helpers\GioHoangDaoHelper;
use App\Helpers\GoodBadDayHelper;
use App\Helpers\HuongXuatHanhHelper;
use App\Helpers\KhiVanHelper;
use App\Helpers\LichKhongMinhHelper;
use App\Helpers\LunarHelper;
use App\Helpers\LyThuanPhongHelper;
use App\Helpers\NhiThapBatTuHelper;
use App\Helpers\NhiTrucHelper;
use App\Helpers\SaoTotXauHelper;
use App\Helpers\TimeConstantHelper;
use App\Helpers\XemNgayTotXauHelper;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Jetfuel\SolarLunar\Solar;
use Jetfuel\SolarLunar\Lunar;
use Jetfuel\SolarLunar\SolarLunar;

class LunarController extends Controller
{
    // public function index(Request $request)
    // {
    //     // Mặc định là ngày hiện tại nếu không nhập
    //     $dd = date('d');
    //     $mm = date('m');
    //     $yy = date('Y');

    //     $cdate = $request->input('cdate');  // Lấy ngày từ form (định dạng Y-m-d từ input[type="date"])
    //     $birthdate = $request->input('birthdate');  // Lấy ngày từ form (định dạng Y-m-d từ input[type="date"])

    //     if (FunctionHelper::validateDate($cdate, 'Y-m-d')) {
    //         // Nếu hợp lệ, phân tách thành dd, mm, yy
    //         $cdate_info = explode('-', $cdate);
    //         list($yy, $mm, $dd) = $cdate_info;
    //     }

    //     // Chuyển từ Dương lịch sang Âm lịch
    //     $al = LunarHelper::convertSolar2Lunar((int)$dd, (int)$mm, (int)$yy);

    //     // Tính Can Chi của ngày
    //     $jd = LunarHelper::jdFromDate((int)$dd, (int)$mm, (int)$yy);

    //     $canChi = LunarHelper::canchiNgayByJD($jd);
    //     $chi_ngay = explode(' ', $canChi);
    //     $chiNgay = $chi_ngay;

    //     $chi_ngay = @$chi_ngay[1];
    //     $gioHd = LunarHelper::gioHDTrongNgayTXT($chi_ngay); // Tính giờ hoàng đạo trong ngày
    //     $thu = LunarHelper::sw_get_weekday($cdate); // Tính thứ trong tháng

    //     $ngaySuatHanh = LichKhongMinhHelper::numToNgay((int)$al[1], (int)$al[0]); // Tính ngày xuất hành
    //     $ngaySuatHanhHTML = LichKhongMinhHelper::ngayToHTML($ngaySuatHanh); // HTML cho ngày xuất hành

    //     $tietkhi = LunarHelper::tietKhiWithIcon($jd);
    //     list($table_html, $data_totxau) = LunarHelper::printTable($mm, $yy, true, true);



    //     //Lấy sao tốt xấu theo ngọc Hạp thông thư
    //     $getSaoTotXauInfo = FunctionHelper::getSaoTotXauInfo($dd, $mm, $yy);
    //     //end

    //     //can chi tháng năm
    //     $getThongTinCanChiVaIcon = FunctionHelper::getThongTinCanChiVaIcon((int)$dd, (int)$mm, (int)$yy);
    //     //end

    //     //Tổng quan ngày
    //     $getThongTinNgay = FunctionHelper::getThongTinNgay((int)$dd, (int)$mm, (int)$yy);
    //     //end


    //     //Nội khí ngày
    //     $noiKhiNgay =  KhiVanHelper::getDetailedNoiKhiExplanation((int)$dd, (int)$mm, (int)$yy);
    //     //end

    //     //Nhị thập bát tú
    //     $nhiThapBatTu = FunctionHelper::nhiThapBatTu((int)$yy, (int)$mm, (int)$dd);
    //     //end nhị thập bát tú

    //     //THập Nhị Trực 
    //     $getThongTinTruc = FunctionHelper::getThongTinTruc($dd, $mm, $yy);
    //     //end tHập nhị trực

    //     //Khổng minh lục diệu
    //     $khongMinhLucDieu = LichKhongMinhHelper::getKhongMinhLucDieuDayInfo($dd, $mm, $yy);
    //     //end khổng minh lục diệu

    //     //giải thích ngày theo Bành Tổ Bách Kỵ
    //     $banhToCan = DataHelper::$banhToCanTaboos[$chiNgay[0]];
    //     $banhToChi = DataHelper::$banhToChiTaboos[$chiNgay[1]];
    //     //end

    //     //Hướng xuất hành và giờ xuất hành lý thuần phong
    //     $getThongTinXuatHanhVaLyThuanPhong = FunctionHelper::getThongTinXuatHanhVaLyThuanPhong($dd, $mm, $yy);
    //     //------End------//

    //     //------Giờ hoàng đạo-------//
    //     $getDetailedGioHoangDao = GioHoangDaoHelper::getDetailedGioHoangDao((int)$dd, (int)$mm, (int)$yy);
    //     //end giờ hoàng đạo

    //     $amToday = sprintf('%04d-%02d-%02d', $al[2], $al[1], $al[0]);

    //     $getDaySummaryInfo = FunctionHelper::getDaySummaryInfo((int)$dd, (int)$mm, (int)$yy, $birthdate);


    //     return view('lunar.convert', [
    //         'cdate' => $cdate,
    //         'dd' => $dd,
    //         'mm' => $mm,
    //         'yy' => $yy,
    //         'al' => $al,
    //         'canChi' => $canChi,
    //         'weekday' => $thu, // Thứ trong tháng
    //         'ngaySuatHanh' => $ngaySuatHanh, // Ngày xuất hành
    //         'ngaySuatHanhHTML' => $ngaySuatHanhHTML, // HTML cho ngày xuất hành
    //         'gioHd' => $gioHd, // Giờ hoàng đạo
    //         'tietkhi' => $tietkhi, // Tiết khí
    //         'table_html' => $table_html, // HTML cho bảng lịch
    //         'data_totxau' => $data_totxau, // Dữ liệu tốt xấu
    //         'noiKhiNgay' => $noiKhiNgay,
    //         'banhToCan' => $banhToCan, //giải thích ngày theo Bành Tổ Bách Kỵ
    //         'banhToChi' => $banhToChi, //giải thích ngày theo Bành Tổ Bách Kỵ
    //         'chiNgay' => $chiNgay,
    //         'amToday' => $amToday,

    //         'khongMinhLucDieu' => $khongMinhLucDieu, // lịch khổng minh lục diệu
    //         'getDetailedGioHoangDao' => $getDetailedGioHoangDao,
    //         'getThongTinXuatHanhVaLyThuanPhong' => $getThongTinXuatHanhVaLyThuanPhong,//Hướng xuất hành và giờ xuất hành lý thuần phong
    //         'getThongTinTruc' => $getThongTinTruc,
    //         'getThongTinCanChiVaIcon' => $getThongTinCanChiVaIcon, //Lấy thông tin ngày và icon
    //         'nhiThapBatTu' => $nhiThapBatTu, //THoong tin nhị thập bát tú
    //         'getSaoTotXauInfo' => $getSaoTotXauInfo, //Ngọc hạp thông thư
    //         'getThongTinNgay' => $getThongTinNgay, //Tổng quan ngày
    //         'getDaySummaryInfo' => $getDaySummaryInfo

    //     ]);
    // }

    // Hàm kiểm tra ngày hợp lệ theo định dạng


















    /**
     * Trang chủ, hiển thị thông tin cho ngày hiện tại hoặc ngày được chọn từ form.
     */
    public function index(Request $request)
    {
        // Mặc định là ngày hiện tại nếu không có input
        $yy = date('Y');
        $mm = date('m');
        $dd = date('d');

        $cdate = $request->input('cdate'); // Lấy ngày từ form (định dạng Y-m-d)

        // Nếu người dùng chọn ngày từ form, cập nhật lại ngày tháng năm
        if (FunctionHelper::validateDate($cdate, 'Y-m-d')) {
            list($yy, $mm, $dd) = explode('-', $cdate);
        }

        // Lấy ngày sinh (nếu có) để tính toán bổ sung
        $birthdate = $request->input('birthdate');

        // Gọi phương thức xử lý chung và trả về view
        return $this->processAndRenderLunarData($dd, $mm, $yy, $birthdate);
    }

    /**
     * Hiển thị thông tin cho một ngày cụ thể từ URL.
     * Route: /am-lich/nam/{yy}/thang/{mm}/ngay/{dd}
     */
    public function ngay(Request $request, $yy, $mm, $dd)
    {
        // --- VALIDATION ---
        // Kiểm tra xem ngày, tháng, năm từ URL có hợp lệ không
        if (!checkdate((int)$mm, (int)$dd, (int)$yy)) {
            // Nếu không hợp lệ, trả về lỗi 404 Not Found
            abort(404, 'Ngày bạn yêu cầu không tồn tại.');
        }

        // Lấy ngày sinh (nếu có) từ query string, ví dụ: /am-lich/...?birthdate=...
        $birthdate = $request->input('birthdate');

        // Gọi phương thức xử lý chung và trả về view
        return $this->processAndRenderLunarData($dd, $mm, $yy, $birthdate);
    }

    /**
     * Phương thức private để xử lý logic tính toán và render view.
     * Phương thức này được gọi bởi cả index() và ngay().
     *
     * @param string|int $dd Ngày
     * @param string|int $mm Tháng
     * @param string|int $yy Năm
     * @param string|null $birthdate Ngày sinh (tùy chọn)
     * @return \Illuminate\View\View
     */
    private function processAndRenderLunarData($dd, $mm, $yy, $birthdate = null)
    {
        // Ép kiểu để đảm bảo là số nguyên
        $dd = (int)$dd;
        $mm = (int)$mm;
        $yy = (int)$yy;

        // Tạo chuỗi ngày Y-m-d từ các tham số để truyền cho view
        $cdate = sprintf('%04d-%02d-%02d', $yy, $mm, $dd);

        // --- Bắt đầu toàn bộ logic tính toán (giữ nguyên từ hàm index cũ) ---

        // Chuyển từ Dương lịch sang Âm lịch
        $al = LunarHelper::convertSolar2Lunar($dd, $mm, $yy);

        // Tính Can Chi của ngày
        $jd = LunarHelper::jdFromDate($dd, $mm, $yy);
        $canChi = LunarHelper::canchiNgayByJD($jd);
        $chiNgay = explode(' ', $canChi);

        $chi_ngay = @$chiNgay[1];
        $gioHd = LunarHelper::gioHDTrongNgayTXT($chi_ngay);
        $thu = LunarHelper::sw_get_weekday($cdate);

        $ngaySuatHanh = LichKhongMinhHelper::numToNgay($al[1], $al[0]);
        $ngaySuatHanhHTML = LichKhongMinhHelper::ngayToHTML($ngaySuatHanh);

        $tietkhi = LunarHelper::tietKhiWithIcon($jd);
        list($table_html, $data_totxau) = LunarHelper::printTable($mm, $yy, true, true);

        // =========================================================
        // ===  LOGIC LỌC VÀ LẤY SỰ KIỆN CHO NGÀY ĐANG XEM  ===
        // =========================================================

        // Khởi tạo một mảng rỗng để chứa các sự kiện CỦA RIÊNG ngày này
        $suKienHomNay = [];

        // 1. Xử lý sự kiện DƯƠNG LỊCH
        // Lấy tất cả sự kiện DƯƠNG LỊCH trong tháng từ Helper
        $suKienTrongThangDuong = LunarHelper::getVietnamEvent($mm, $yy);

        // Bây giờ, kiểm tra xem ngày DƯƠNG LỊCH hiện tại ($dd) có tồn tại
        // như một key trong danh sách sự kiện của tháng không.
        if (isset($suKienTrongThangDuong[$dd])) {
            // Nếu có, thêm sự kiện của ngày đó vào mảng kết quả
            $suKienHomNay[] = $suKienTrongThangDuong[$dd];
        }

        // 2. Xử lý sự kiện ÂM LỊCH (tương tự)
        // Lấy tất cả sự kiện ÂM LỊCH trong tháng từ Helper
        // Giả sử $al[0] là ngày âm, $al[1] là tháng âm
        $suKienTrongThangAm = LunarHelper::getVietnamLunarEvent2($al[1], $al[2]);
        // dd($suKienTrongThangAm);
        // Kiểm tra xem ngày ÂM LỊCH hiện tại ($al[0]) có trong danh sách không
        if (isset($suKienTrongThangAm[$al[0]])) {
            // Nếu có, cũng thêm vào mảng kết quả
            $suKienHomNay[] = $suKienTrongThangAm[$al[0]];
        }

        // =========================================================
        // ===  KẾT THÚC LOGIC LỌC SỰ KIỆN  ===
        // =========================================================

        // Lấy sao tốt xấu theo ngọc Hạp thông thư
        $getSaoTotXauInfo = FunctionHelper::getSaoTotXauInfo($dd, $mm, $yy);

        // Can chi tháng năm
        $getThongTinCanChiVaIcon = FunctionHelper::getThongTinCanChiVaIcon($dd, $mm, $yy);

        // Tổng quan ngày
        $getThongTinNgay = FunctionHelper::getThongTinNgay($dd, $mm, $yy);

        // Nội khí ngày
        $noiKhiNgay =  KhiVanHelper::getDetailedNoiKhiExplanation($dd, $mm, $yy);

        // Nhị thập bát tú
        $nhiThapBatTu = FunctionHelper::nhiThapBatTu($yy, $mm, $dd);

        // THập Nhị Trực 
        $getThongTinTruc = FunctionHelper::getThongTinTruc($dd, $mm, $yy);

        // Khổng minh lục diệu
        $khongMinhLucDieu = LichKhongMinhHelper::getKhongMinhLucDieuDayInfo($dd, $mm, $yy);

        // Giải thích ngày theo Bành Tổ Bách Kỵ
        $banhToCan = DataHelper::$banhToCanTaboos[$chiNgay[0]];
        $banhToChi = DataHelper::$banhToChiTaboos[$chiNgay[1]];

        // Hướng xuất hành và giờ xuất hành lý thuần phong
        $getThongTinXuatHanhVaLyThuanPhong = FunctionHelper::getThongTinXuatHanhVaLyThuanPhong($dd, $mm, $yy);

        // Giờ hoàng đạo
        $getDetailedGioHoangDao = GioHoangDaoHelper::getDetailedGioHoangDao($dd, $mm, $yy);

        $amToday = sprintf('%04d-%02d-%02d', $al[2], $al[1], $al[0]);
        // $amToday = sprintf('%02d-%02d%04d-', $al[2], $al[1], $al[0]);

        // Tính toán thông tin tổng hợp (có thể phụ thuộc vào ngày sinh)
        $getDaySummaryInfo = FunctionHelper::getDaySummaryInfo($dd, $mm, $yy, $birthdate);

        // --- Kết thúc logic tính toán ---



        $currentDate = Carbon::create($yy, $mm, 1);
        // Tính toán tháng trước
        $prevDate = $currentDate->copy()->subMonth();
        $prevYear = $prevDate->year;
        $prevMonth = $prevDate->month;

        // Tính toán tháng sau
        $nextDate = $currentDate->copy()->addMonth();
        $nextYear = $nextDate->year;
        $nextMonth = $nextDate->month;


        // Trả về view với đầy đủ dữ liệu
        return view('lunar.convert', [
            'cdate' => $cdate, // Ngày đang xem, định dạng Y-m-d
            'dd' => sprintf('%02d', $dd), // Thêm số 0 ở đầu nếu cần
            'mm' => sprintf('%02d', $mm),
            
            // Các biến mới cho việc điều hướng
            'prevYear' => $prevYear,
            'prevMonth' => $prevMonth,
            'nextYear' => $nextYear,
            'nextMonth' => $nextMonth,

            'yy' => $yy,
            'al' => $al,
            'canChi' => $canChi,
            'weekday' => $thu,
            'ngaySuatHanh' => $ngaySuatHanh,
            'ngaySuatHanhHTML' => $ngaySuatHanhHTML,
            'gioHd' => $gioHd,
            'tietkhi' => $tietkhi,
            'table_html' => $table_html,
            'data_totxau' => $data_totxau,
            'noiKhiNgay' => $noiKhiNgay,
            'banhToCan' => $banhToCan,
            'banhToChi' => $banhToChi,
            'chiNgay' => $chiNgay,
            'amToday' => $amToday,
            'khongMinhLucDieu' => $khongMinhLucDieu,
            'getDetailedGioHoangDao' => $getDetailedGioHoangDao,
            'getThongTinXuatHanhVaLyThuanPhong' => $getThongTinXuatHanhVaLyThuanPhong,
            'getThongTinTruc' => $getThongTinTruc,
            'getThongTinCanChiVaIcon' => $getThongTinCanChiVaIcon,
            'nhiThapBatTu' => $nhiThapBatTu,
            'getSaoTotXauInfo' => $getSaoTotXauInfo,
            'getThongTinNgay' => $getThongTinNgay,
            'getDaySummaryInfo' => $getDaySummaryInfo,
            // Thêm birthdate để hiển thị lại trên form nếu có
            'birthdate' => $birthdate,
            'suKienHomNay' => $suKienHomNay,
        ]);
    }
}
