<?php

namespace App\Helpers;

use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\Log;

class FunctionHelper
{
    public static  function nhiThapBatTu($yy, $mm, $dd)
    {
        $date = Carbon::create((int)$yy, (int)$mm, (int)$dd);
        $saoInfo = NhiThapBatTuHelper::getNhiThapBatTu($date);
        $guidance = DataHelper::$guidance[$saoInfo['name']] ?? [
            'good' => 'Không rõ',
            'bad' => 'Không rõ'
        ];
        $fullName = DataHelper::$nhiThapBatTuFullNames[$saoInfo['name']] ?? 'Không rõ';
        return [
            'name' => $saoInfo['name'],
            'element' => $saoInfo['element'] ?? 'Không rõ',
            'nature' => $saoInfo['nature'] ?? 'Không rõ',
            'description' => $saoInfo['description'] ?? 'Không có mô tả.',
            'fullName' => $fullName,
            'guidance' => $guidance,
        ];
    }

    public static function getSaoTotXauInfo(int $dd, int $mm, int $yy): array
    {
        $al = LunarHelper::convertSolar2Lunar((int)$dd, (int)$mm, (int)$yy);
        $jdNgayAm = LunarHelper::jdFromLunarDate((int)$al[0], (int)$al[1], (int)$al[2], (int)$al[3]);
        $canChiNgayAm = LunarHelper::canchiNgayByJD($jdNgayAm);

        $chi_ngay_am = explode(' ', $canChiNgayAm);
        $ngayThienCan = $chi_ngay_am[0] ?? '';
        $ngayDiaChi = $chi_ngay_am[1] ?? '';

        $saoTot = XemNgayTotXauHelper::laySaoTotTheoNgay((int)$al[1], $ngayThienCan, $ngayDiaChi);
        $saoXau = XemNgayTotXauHelper::laySaoXauTheoNgay((int)$al[1], $ngayThienCan, $ngayDiaChi);
        $ketLuanSao = SaoTotXauHelper::getNgocHapConclusion($saoTot, $saoXau);

        return [
            'thien_can' => $ngayThienCan,
            'dia_chi' => $ngayDiaChi,
            'sao_tot' => $saoTot,
            'sao_xau' => $saoXau,
            'ket_luan' => $ketLuanSao,
        ];
    }

    public static function getDaySummaryInfo(int $dd, int $mm, int $yy, $birthdate): array
    {
        $isPersonalized = !empty($birthdate);
    

        // Chuyển ngày dương sang Carbon
        $date = DateTime::createFromFormat('Y-m-d', "$yy-$mm-$dd");
        $date = Carbon::instance($date);

        // Tính điểm
        $scoresDate = GoodBadDayHelper::calculateDayScore($date, $birthdate, '');
        $desScoresDate = GoodBadDayHelper::mapRatingToLevelDescription($scoresDate['rating']);
        $formattedDate = $date->format('j-n-Y'); // Ví dụ: 17-6-2025
        $percentageRounded = round($scoresDate['percentage']);

        // Lấy đoạn giới thiệu tổng quan
        $getIntroParagraph = GoodBadDayHelper::getIntroParagraph($formattedDate, $percentageRounded, $isPersonalized);

        return [
            'date' => $date,
            'formatted_date' => $formattedDate,
            'score' => $scoresDate,
            'percentage_rounded' => $percentageRounded,
            'description' => $desScoresDate,
            'intro_paragraph' => $getIntroParagraph,
        ];
    }


    public static function getThongTinNgay(int $dd, int $mm, int $yy): array
    {
        $al = LunarHelper::convertSolar2Lunar((int)$dd, (int)$mm, (int)$yy);
        $jdNgayAm = LunarHelper::jdFromLunarDate((int)$al[0], (int)$al[1], (int)$al[2], (int)$al[3]);
        $canChiNgayAm = LunarHelper::canchiNgayByJD($jdNgayAm);

        $chi_ngay_am = explode(' ', $canChiNgayAm);
        $ngayThienCan = $chi_ngay_am[0] ?? '';
        $ngayDiaChi = $chi_ngay_am[1] ?? '';
        return [
            'nap_am' => TimeConstantHelper::getNapAmFromCanChi($canChiNgayAm),
            'tuoi_xung' => TimeConstantHelper::getConflictAge($canChiNgayAm),
            'gio_hoang_dao' => TimeConstantHelper::getGioHDTrongNgayTXT($ngayDiaChi, true, true),
            'gio_hac_dao' => TimeConstantHelper::getGioHDTrongNgayTXT($ngayDiaChi, false, true),
        ];
    }
    public static function getThongTinCanChiVaIcon($dd, $mm, $yy)
    {
        $al = LunarHelper::convertSolar2Lunar((int)$dd, (int)$mm, (int)$yy);
        $jday = LunarHelper::jdFromDate((int)$dd, (int)$mm, (int)$yy);
        $dayCanChi = LunarHelper::canchiNgayByJD($jday);
        $chi_ngay = explode(' ', $dayCanChi)[1];
        $can_chi_nam = KhiVanHelper::canchiNam($al[2]);
        $can_chi_thang = KhiVanHelper::canchiThang($al[2], $al[1]);
        $chi_thang = KhiVanHelper::extractChi(KhiVanHelper::canchiThang($al[2], $al[1]));
        $chi_nam   = KhiVanHelper::extractChi(KhiVanHelper::canchiNam($al[2]));
        $icon_ngay  = KhiVanHelper::getChiSvg($chi_ngay);
        $icon_thang = KhiVanHelper::getChiSvg($chi_thang);
        $icon_nam   = KhiVanHelper::getChiSvg($chi_nam);
        return [
            'can_chi_nam' => $can_chi_nam,
            'can_chi_thang' => $can_chi_thang,
            'can_chi_ngay' => $dayCanChi,
            'chi_ngay' => $chi_ngay,
            'chi_thang' => $chi_thang,
            'chi_nam' => $chi_nam,
            'icon_ngay' => $icon_ngay,
            'icon_thang' => $icon_thang,
            'icon_nam' => $icon_nam,
        ];
    }
    public static function validateDate($date, $format = 'Y-m-d')
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) === $date;
    }
    public static function getThongTinTruc(int $day, int $month, int $year): array
    {
        $titleTruc = NhiTrucHelper::getTruc($day, $month, $year);
        $descriptionTruc = DataHelper::$trucInfo[$titleTruc] ?? 'Không có thông tin trực.';

        return [
            'title' => $titleTruc,
            'description' => $descriptionTruc,
        ];
    }
    public static function getThongTinXuatHanhVaLyThuanPhong(int $dd, int $mm, int $yy): array
{
    // Hướng xuất hành theo ngày âm
     $al = LunarHelper::convertSolar2Lunar((int)$dd, (int)$mm, (int)$yy);
    $groupMonth = HuongXuatHanhHelper::getMonthGroup((int)$al[1]);
    $xuatHanhDay = HuongXuatHanhHelper::calculateXuatHanhDay($groupMonth, (int)$al[0]);
    $infoXuatHanh = HuongXuatHanhHelper::getXuatHanhInfo($xuatHanhDay);

    // Hướng xuất hành theo dương lịch
    $huongXuatHanh = HuongXuatHanhHelper::getHuongXuatHanh($dd, $mm, $yy);

    // Lý Thuần Phong
    $lyThuanPhong = LyThuanPhongHelper::groupAndCombineHours($dd, $mm, $yy);
    $lyThuanPhongDescription = LyThuanPhongHelper::getTravelConclusion(
        $infoXuatHanh['rating'] ?? null,
        $huongXuatHanh['hyThan']['direction'] ?? '',
        $huongXuatHanh['taiThan']['direction'] ?? '',
        $huongXuatHanh['hacThan']['direction'] ?? '',
        $lyThuanPhong
    );

    return [
        'xuat_hanh_info' => $infoXuatHanh,
        'huong_xuat_hanh' => $huongXuatHanh,
        'ly_thuan_phong' => $lyThuanPhong,
        'ly_thuan_phong_description' => $lyThuanPhongDescription,
    ];
}

}
