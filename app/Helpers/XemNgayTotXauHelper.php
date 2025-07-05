<?php

namespace App\Helpers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class XemNgayTotXauHelper
{
  
    /**
     * Lấy danh sách sao theo ngày
     *
     * @param array $catTinhData Dữ liệu sao
     * @param int $thangAm Tháng âm
     * @param string $ngayCan Ngày thiên
     * @param string $ngayChi Ngày địa chi
     * @return array Danh sách sao
     */
    public static function laySaoTotTheoNgay(int $thangAm, string $ngayCan, string $ngayChi): array
    {
        $danhSachSao = [];
    
        // Kiểm tra xem tháng âm có tồn tại trong dữ liệu không
        if (!isset(DataHelper::$catTinhData[$thangAm])) {
            return $danhSachSao; // Trả về mảng rỗng nếu tháng không tồn tại
        }
    
        $thongTinThang = DataHelper::$catTinhData[$thangAm];
        // Lấy sao theo can
        if (isset($thongTinThang['can'][$ngayCan]) && is_array($thongTinThang['can'][$ngayCan])) {
            $danhSachSao = array_merge($danhSachSao, $thongTinThang['can'][$ngayCan]);
        }
    
        // Lấy sao theo chi
        if (isset($thongTinThang['chi'][$ngayChi]) && is_array($thongTinThang['chi'][$ngayChi])) {
            $danhSachSao = array_merge($danhSachSao, $thongTinThang['chi'][$ngayChi]);
        }
    
        // Lấy sao theo can_chi nếu có
        $keyCanChi = $ngayCan . '_' . $ngayChi;
        if (isset($thongTinThang['can_chi'][$keyCanChi]) && is_array($thongTinThang['can_chi'][$keyCanChi])) {
            $danhSachSao = array_merge($danhSachSao, $thongTinThang['can_chi'][$keyCanChi]);
        }
    
        // Loại bỏ trùng lặp và sắp xếp
        $danhSachSao = array_unique($danhSachSao);
        sort($danhSachSao);
    
        // Gắn mô tả vào sao
        $danhSachSaoCoMoTa = [];
        foreach ($danhSachSao as $sao) {
            $moTa = DataHelper::$catTinhDescription[$sao] ?? 'Đang cập nhật';
            $danhSachSaoCoMoTa[$sao] = $moTa;
        }
    
        return $danhSachSaoCoMoTa;
    }
    public static function laySaoXauTheoNgay(int $thangAm, string $ngayCan, string $ngayChi): array
    {
        $danhSachSao = [];
    
        // Kiểm tra xem tháng âm có tồn tại trong dữ liệu không
        if (!isset(DataHelper::$hungSatData[$thangAm])) {
            return $danhSachSao; // Trả về mảng rỗng nếu tháng không tồn tại
        }
    
        $thongTinThang = DataHelper::$hungSatData[$thangAm];
    
        // Lấy sao theo can
        if (isset($thongTinThang['can'][$ngayCan]) && is_array($thongTinThang['can'][$ngayCan])) {
            $danhSachSao = array_merge($danhSachSao, $thongTinThang['can'][$ngayCan]);
        }
    
        // Lấy sao theo chi
        if (isset($thongTinThang['chi'][$ngayChi]) && is_array($thongTinThang['chi'][$ngayChi])) {
            $danhSachSao = array_merge($danhSachSao, $thongTinThang['chi'][$ngayChi]);
        }
    
        // Lấy sao theo can_chi nếu có
        $keyCanChi = $ngayCan . '_' . $ngayChi;
        if (isset($thongTinThang['can_chi'][$keyCanChi]) && is_array($thongTinThang['can_chi'][$keyCanChi])) {
            $danhSachSao = array_merge($danhSachSao, $thongTinThang['can_chi'][$keyCanChi]);
        }
    
        // Loại bỏ trùng lặp và sắp xếp
        $danhSachSao = array_unique($danhSachSao);
        sort($danhSachSao);
    
        // Gắn mô tả vào sao
        $danhSachSaoCoMoTa = [];
        foreach ($danhSachSao as $sao) {
            $moTa = DataHelper::$hungSatDescription[$sao] ?? 'Đang cập nhật';
            $danhSachSaoCoMoTa[$sao] = $moTa;
        }
    
        return $danhSachSaoCoMoTa;
    }
 public static function getCatTinh(Carbon $date): array
{

         $carbonDate = Carbon::instance($date);
        $lunarDate = LunarHelper::convertSolar2Lunar($carbonDate->day, $carbonDate->month, $carbonDate->year); // Trả về đối tượng có ->month
          $jdNgayAm = LunarHelper::jdFromLunarDate((int)$lunarDate[0], (int)$lunarDate[1], (int)$lunarDate[2], (int)$lunarDate[3]);
        $canChiDay = LunarHelper::canchiNgayByJD($jdNgayAm);    // Ví dụ: "Giáp Tý"
        $parts = explode(' ', $canChiDay);
        if (count($parts) !== 2) {
            throw new \Exception('Invalid Can-Chi format');
        }

        $can = $parts[0];
        $chi = $parts[1];
        $month = (int)$lunarDate[1] ?? null;
        $result = [];

        // Kiểm tra sao theo Can
        if (
            isset(DataHelper::$catTinhData[$month]['can'])
            && is_array(DataHelper::$catTinhData[$month]['can'])
        ) {
            foreach (DataHelper::$catTinhData[$month]['can'] as $matchCan => $stars) {
                $matchCanList = array_map('trim', explode(',', $matchCan));
                if (in_array($can, $matchCanList)) {
                    foreach ($stars as $star) {
                        if (isset(DataHelper::$catTinhDescription[$star])) {
                            $result[] = [
                                'name' => $star,
                                'description' => DataHelper::$catTinhDescription[$star],
                                'icon' => DataHelper::$starEmojis[$star] ?? '✨',
                            ];
                        }
                    }
                }
            }
        }

        // Kiểm tra sao theo Chi
        if (
            isset(DataHelper::$catTinhData[$month]['chi'])
            && is_array(DataHelper::$catTinhData[$month]['chi'])
        ) {
            foreach (DataHelper::$catTinhData[$month]['chi'] as $matchChi => $stars) {
                $matchChiList = array_map(function ($item) {
                    return explode('_', trim($item))[0];
                }, explode(',', $matchChi));

                if (in_array($chi, $matchChiList)) {
                    foreach ($stars as $star) {
                        if (!self::starExists($result, $star)
                            && isset(DataHelper::$catTinhDescription[$star])) {
                            $result[] = [
                                'name' => $star,
                                'description' => DataHelper::$catTinhDescription[$star],
                                'icon' => DataHelper::$starEmojis[$star] ?? '✨',
                            ];
                        }
                    }
                }
            }
        }

        // Kiểm tra sao theo Can Chi kết hợp
        if (
            isset(DataHelper::$catTinhData[$month]['can_chi'])
            && is_array(DataHelper::$catTinhData[$month]['can_chi'])
        ) {
            foreach (DataHelper::$catTinhData[$month]['can_chi'] as $matchCanChi => $stars) {
                $canChiParts = explode(' ', $matchCanChi);
                if (count($canChiParts) == 2 && $canChiParts[0] === $can && $canChiParts[1] === $chi) {
                    foreach ($stars as $star) {
                        if (!self::starExists($result, $star)
                            && isset(DataHelper::$catTinhDescription[$star])) {
                            $result[] = [
                                'name' => $star,
                                'description' => DataHelper::$catTinhDescription[$star],
                                'icon' => DataHelper::$starEmojis[$star] ?? '✨',
                            ];
                        }
                    }
                }
            }
        }

        if (empty($result)) {
            Log::debug("No Cat Tinh stars found for date: {$date}, month: {$month}, can: {$can}, chi: {$chi}");
        }

        return $result;
   
}

private static function starExists(array $list, string $star): bool
{
    foreach ($list as $item) {
        if ($item['name'] === $star) {
            return true;
        }
    }
    return false;
}

    public static function getNgocHapHungSat(Carbon $date): array
    {
        try {
        $carbonDate = Carbon::instance($date);
        $lunarDate = LunarHelper::convertSolar2Lunar($carbonDate->day, $carbonDate->month, $carbonDate->year); // Trả về đối tượng có ->month
          $jdNgayAm = LunarHelper::jdFromLunarDate((int)$lunarDate[0], (int)$lunarDate[1], (int)$lunarDate[2], (int)$lunarDate[3]);
        $canChiDay = LunarHelper::canchiNgayByJD($jdNgayAm);    // Ví dụ: "Giáp Tý"
        $parts = explode(' ', $canChiDay);

        if (count($parts) !== 2) {
            throw new \Exception('Invalid Can-Chi format');
        }

        $can = $parts[0];
        $chi = $parts[1];
       $month = (int)$lunarDate[1] ?? null;
        $result = [];

        Log::debug("Processing Hung Sat - Date: {$date}, Can: {$can}, Chi: {$chi}, Month: {$month}");

        // Kiểm tra sao theo Can
        if (
            isset(DataHelper::$hungSatData[$month]['can']) &&
            is_array(DataHelper::$hungSatData[$month]['can'])
        ) {
            foreach (DataHelper::$hungSatData[$month]['can'] as $matchCan => $stars) {
                $matchCanList = array_map('trim', explode(',', $matchCan));
                if (in_array($can, $matchCanList)) {
                    foreach ($stars as $star) {
                        if (isset(DataHelper::$hungSatDescription[$star])) {
                            $result[] = [
                                'name' => $star,
                                'description' => DataHelper::$hungSatDescription[$star],
                                'icon' => DataHelper::$starEmojis[$star] ?? '⚠️',
                            ];
                        }
                    }
                }
            }
        }

        // Kiểm tra sao theo Chi
        if (
            isset(DataHelper::$hungSatData[$month]['chi']) &&
            is_array(DataHelper::$hungSatData[$month]['chi'])
        ) {
            foreach (DataHelper::$hungSatData[$month]['chi'] as $matchChi => $stars) {
                $matchChiList = array_map(function ($item) {
                    return explode('_', trim($item))[0];
                }, explode(',', $matchChi));

                if (in_array($chi, $matchChiList)) {
                    foreach ($stars as $star) {
                        if (!self::starExists($result, $star)
                            && isset(DataHelper::$hungSatDescription[$star])) {
                            $result[] = [
                                'name' => $star,
                                'description' => DataHelper::$hungSatDescription[$star],
                                'icon' => DataHelper::$starEmojis[$star] ?? '⚠️',
                            ];
                        }
                    }
                }
            }
        }

        // Kiểm tra sao theo Can Chi kết hợp
        if (
            isset(DataHelper::$hungSatData[$month]['can_chi']) &&
            is_array(DataHelper::$hungSatData[$month]['can_chi'])
        ) {
            foreach (DataHelper::$hungSatData[$month]['can_chi'] as $matchCanChi => $stars) {
                $canChiParts = explode(' ', $matchCanChi);
                if (count($canChiParts) == 2 && $canChiParts[0] === $can && $canChiParts[1] === $chi) {
                    foreach ($stars as $star) {
                        if (!self::starExists($result, $star)
                            && isset(DataHelper::$hungSatDescription[$star])) {
                            $result[] = [
                                'name' => $star,
                                'description' => DataHelper::$hungSatDescription[$star],
                                'icon' => DataHelper::$starEmojis[$star] ?? '⚠️',
                            ];
                        }
                    }
                }
            }
        }

        if (empty($result)) {
            Log::debug("No Hung Sat stars found for date: {$date}, month: {$month}, can: {$can}, chi: {$chi}");
        }

        return $result;
    } catch (\Throwable $e) {
        Log::error('Error getting hung sat: ' . $e->getMessage());
        return [];
    }
    }
    
}
