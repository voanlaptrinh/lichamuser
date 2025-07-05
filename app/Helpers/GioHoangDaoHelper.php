<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\FacadesLog;

class GioHoangDaoHelper
{
    public static function getDetailedGioHoangDao($dd, $mm, $yy): array
    {
        $al = LunarHelper::convertSolar2Lunar((int)$dd, (int)$mm, (int)$yy);
        $jdNgayAm = LunarHelper::jdFromLunarDate((int)$al[0], (int)$al[1], (int)$al[2], (int)$al[3]);
        $dayCanChi = LunarHelper::canchiNgayByJD($jdNgayAm);
        $results = [];
        [$dayCan, $dayChi] = explode(' ', $dayCanChi);
        $dayNapAmData = TimeConstantHelper::getNapAmFromCanChi($dayCanChi);
        // $dayNapAmData = getHanhOfDay($date); // ['napAmHanh' => 'Kim']
        $dayHanh = $dayNapAmData['napAmHanh'] ?? 'Không rõ';
        $formatDaychi = strtolower($dayChi);
        $luckyHourNames = LunarHelper::gioHoangDaoByNgay($formatDaychi); // ['tý', 'ngọ', ...]
        foreach ($luckyHourNames as $gioName) {
            try {

                $hourNameCapitalized = ucfirst($gioName);
                $standardRange = DataHelper::$khungGio[$gioName] ?? '(??-??:??)';
                $formattedTimeRange = self::formatHourRangeForDisplay($standardRange);
                $startHour = self::getStartHourFromRange($standardRange);

                $canChiGio = self::getCanChiGioString($startHour, $dayCan); // "Bính Tý"
                [$hourCan, $hourChi] = explode(' ', $canChiGio);

                $hourNapAmData = DataHelper::$napAmTable[$canChiGio] ?? ['hanh' => 'Không rõ'];
                $menhGio = $hourNapAmData['hanh'];
                $canChiMenh = "$canChiGio</br>Mệnh $menhGio";
                $typeAndRating = self::determineHourTypeAndRating($hourCan, $hourChi, $menhGio, $dayCan, $dayChi, $dayHanh);

                $zodiacIcon = DataHelper::$chiIcons[$hourChi] ?? '❓';
                $zodiacColors = DataHelper::$chiColors[$hourChi] ?? DataHelper::$chiColors['default'];
                $zodiacBackgroundColor = $zodiacColors['background'];
                $zodiacBorderColor = $zodiacColors['border'];

                $results[] = [
                    'name' => $hourNameCapitalized,
                    'timeRange' => $formattedTimeRange,
                    'canChiMenh' => $canChiMenh,
                    'type' => $typeAndRating['type'],
                    'rating' => $typeAndRating['rating'],
                    'zodiacSign' => $hourChi,
                    'zodiacIcon' => $zodiacIcon,
                    'zodiacBackgroundColor' => $zodiacBackgroundColor,
                    'zodiacBorderColor' => $zodiacBorderColor,
                ];
            } catch (\Throwable $e) {
                Log::error("Lỗi xử lý giờ hoàng đạo '$gioName'" . $e->getMessage());
            }
        }

        return $results;
    }
    public static function formatHourRangeForDisplay(string $standardRange): string
    {
        try {
            // 1. Trích xuất phần thời gian trong dấu ngoặc
            if (!preg_match('/\((\d{1,2}:\d{2}-\d{1,2}:\d{2})\)/', $standardRange, $matches)) {
                // Nếu không khớp, bỏ ngoặc và trả lại chuỗi
                return preg_replace('/[()]/', '', $standardRange);
            }

            $timePart = $matches[1]; // "23:00-0:59"
            $parts = explode('-', $timePart); // ["23:00", "0:59"]
            if (count($parts) !== 2) return "(Lỗi giờ)";

            $startHour = (int) explode(':', $parts[0])[0];
            $endHourRaw = (int) explode(':', $parts[1])[0];

            // 2. Xác định giờ kết thúc hiển thị theo quy ước
            switch ($startHour) {
                case 23:
                    $endHourDisplay = 1;
                    break;
                case 1:
                    $endHourDisplay = 3;
                    break;
                case 3:
                    $endHourDisplay = 5;
                    break;
                case 5:
                    $endHourDisplay = 7;
                    break;
                case 7:
                    $endHourDisplay = 9;
                    break;
                case 9:
                    $endHourDisplay = 11;
                    break;
                case 11:
                    $endHourDisplay = 13;
                    break;
                case 13:
                    $endHourDisplay = 15;
                    break;
                case 15:
                    $endHourDisplay = 17;
                    break;
                case 17:
                    $endHourDisplay = 19;
                    break;
                case 19:
                    $endHourDisplay = 21;
                    break;
                case 21:
                    $endHourDisplay = 23;
                    break;
                default:
                    $endHourDisplay = ($startHour + 2) % 24; // Dự phòng
            }

            return "({$startHour}h-{$endHourDisplay}h)";
        } catch (\Throwable $e) {
            Log::error("Lỗi định dạng khung giờ: '{$standardRange}'", [
                'error' => $e->getMessage()
            ]);
            return "(Lỗi)";
        }
    }
    public static function getStartHourFromRange(string $standardRange): int
    {
        try {
            // 1. Trích xuất phần thời gian trong dấu ngoặc đơn
            if (!preg_match('/\((\d{1,2}):\d{2}-\d{1,2}:\d{2}\)/', $standardRange, $matches)) {
                Log::warning("Không thể trích xuất giờ bắt đầu từ: '{$standardRange}'");
                return 0; // Mặc định
            }

            // 2. Lấy phần giờ bắt đầu
            $startHour = (int) $matches[1];
            return $startHour;
        } catch (\Throwable $e) {
            Log::error("Lỗi lấy giờ bắt đầu từ: '{$standardRange}'", [
                'error' => $e->getMessage()
            ]);
            return 0; // Mặc định khi lỗi
        }
    }
    public static function getCanChiGioString(int $startHour, string $dayCan): string
    {
        $hangChi = ['Tý', 'Sửu', 'Dần', 'Mão', 'Thìn', 'Tỵ', 'Ngọ', 'Mùi', 'Thân', 'Dậu', 'Tuất', 'Hợi'];
        $hangCan = ['Giáp', 'Ất', 'Bính', 'Đinh', 'Mậu', 'Kỷ', 'Canh', 'Tân', 'Nhâm', 'Quý'];

        // 1. Tính chỉ số Chi giờ
        $hourIndex = intdiv($startHour + 1, 2) % 12;
        if ($hourIndex < 0 || $hourIndex >= count($hangChi)) {
            throw new \Exception("Lỗi Chi Giờ (Hour Index: $hourIndex)");
        }
        $currentChi = $hangChi[$hourIndex];

        // 2. Tìm chỉ số Can ngày
        $dayCanIndex = array_search($dayCan, $hangCan);
        if ($dayCanIndex === false) {
            throw new \Exception("Can Ngày không hợp lệ: $dayCan");
        }

        // 3. Tính chỉ số Can giờ theo quy luật ngũ hành
        switch ($dayCanIndex % 5) {
            case 0:
                $startCanIndex = 0;
                break; // Giáp, Kỷ
            case 1:
                $startCanIndex = 2;
                break; // Ất, Canh
            case 2:
                $startCanIndex = 4;
                break; // Bính, Tân
            case 3:
                $startCanIndex = 6;
                break; // Đinh, Nhâm
            case 4:
                $startCanIndex = 8;
                break; // Mậu, Quý
            default:
                throw new \Exception("Lỗi không xác định khi tính Can Giờ");
        }

        $currentCanIndex = ($startCanIndex + $hourIndex) % 10;
        if ($currentCanIndex < 0 || $currentCanIndex >= count($hangCan)) {
            throw new \Exception("Lỗi Can Giờ (Can Index: $currentCanIndex)");
        }

        $currentCan = $hangCan[$currentCanIndex];

        return "$currentCan $currentChi";
    }
    public static function determineHourTypeAndRating($hourCan, $hourChi, $hourHanh, $dayCan, $dayChi, $dayHanh): array
    {
        $type = 'Tốt';
        $rating = 4;

        // Ưu tiên Lục Hợp / Tam Hợp
        if (
            self::isLucHop($hourChi, $dayChi) ||
            self::isTamHop($hourChi, $dayChi)
        ) {
            return ['type' => 'Tốt', 'rating' => 5];
        }

        // Quan hệ Xung / Hại / Phá / Hình
        if (self::isLucXung($hourChi, $dayChi)) {
            $type = 'Kỳ';
            $rating = 1;
        } elseif (self::isTuongHai($hourChi, $dayChi)) {
            $type = 'Kỳ';
            $rating = 2;
        } elseif (
            self::isTuongPha($hourChi, $dayChi) ||
            self::isTuongHinh($hourChi, $dayChi)
        ) {
            $type = 'Trung bình';
            $rating = 2;
        } elseif (
            self::isTuHinh($hourChi) &&
            $hourChi === $dayChi
        ) {
            $type = 'Trung bình';
            $rating = 2;
        }

        // Ngũ Hành Nạp Âm
        if ($type !== 'Kỳ') {
            if (self::isSinh($dayHanh, $hourHanh)) {
                if ($rating < 5) $rating++;
                if ($rating >= 4) $type = 'Tốt';
            } elseif (self::isSinh($hourHanh, $dayHanh)) {
                if ($rating < 5) $rating++;
                if ($rating >= 4) $type = 'Tốt';
            } elseif (self::isKhac($dayHanh, $hourHanh)) {
                if ($rating > 1) $rating--;
                if ($rating <= 2) $type = 'Trung bình';
            } elseif (self::isKhac($hourHanh, $dayHanh)) {
                if ($rating > 1) $rating -= 2;
                $rating = max(1, $rating);
                if ($rating <= 1) {
                    $type = 'Kỳ';
                } elseif ($rating <= 2) {
                    $type = 'Trung bình';
                }
            } elseif ($hourHanh === $dayHanh) {
                if ($rating < 5 && $type === 'Tốt') $rating++;
            }
        }

        // Đảm bảo rating trong khoảng 1-5
        $rating = min(max($rating, 1), 5);

        // Không override nếu đã là Kỵ
        if ($type !== 'Kỳ') {
            if ($rating >= 4) {
                $type = 'Tốt';
            } elseif ($rating >= 2) {
                $type = 'Trung bình';
            } else {
                $type = 'Kỳ';
            }
        }

        return [
            'type' => $type,
            'rating' => $rating
        ];
    }

    // Kiểm tra Lục Hợp
    public static function isLucHop(string $chi1, string $chi2): bool
    {
        return self::isValidChi($chi1) && self::isValidChi($chi2) && DataHelper::$LUC_HOP[$chi1] === $chi2;
    }

    // Kiểm tra Tam Hợp
    public static function isTamHop(string $chi1, string $chi2): bool
    {
        return self::isValidChi($chi1) && self::isValidChi($chi2)
            && isset(DataHelper::$TAM_HOP_GROUPS[$chi1])
            && in_array($chi2, DataHelper::$TAM_HOP_GROUPS[$chi1], true);
    }

    // Hàm phụ kiểm tra giá trị hợp lệ
    public static function isValidChi(string $chi): bool
    {
        $chiList = ['tý', 'sửu', 'dần', 'mão', 'thìn', 'tỵ', 'ngọ', 'mùi', 'thân', 'dậu', 'tuất', 'hợi'];
        return in_array($chi, $chiList, true);
    }
    // Kiểm tra Lục Xung
    public static function isLucXung(string $chi1, string $chi2): bool
    {
        return self::isValidChi($chi1) && self::isValidChi($chi2)
            && DataHelper::$LUC_XUNG[$chi1] === $chi2;
    }

    // Kiểm tra Tương Hại
    public static function isTuongHai(string $chi1, string $chi2): bool
    {
        return self::isValidChi($chi1) && self::isValidChi($chi2)
            && DataHelper::$TUONG_HAI[$chi1] === $chi2;
    }
    // Kiểm tra Tương Phá
    public static function isTuongPha(string $chi1, string $chi2): bool
    {
        return self::isValidChi($chi1) && self::isValidChi($chi2)
            && DataHelper::$TUONG_PHA[$chi1] === $chi2;
    }

    // Kiểm tra Tương Hình
    public static function isTuongHinh(string $chi1, string $chi2): bool
    {
        if (!self::isValidChi($chi1) || !self::isValidChi($chi2) || $chi1 === $chi2) {
            return false;
        }

        // Hình vô lễ
        if (isset(DataHelper::$HINH_VO_LE_PAIR[$chi1]) && DataHelper::$HINH_VO_LE_PAIR[$chi1] === $chi2) {
            return true;
        }

        // Hình ỷ thế
        if (
            in_array($chi1, DataHelper::$HINH_Y_THE_TRIPLE, true) &&
            in_array($chi2, DataHelper::$HINH_Y_THE_TRIPLE, true)
        ) {
            return true;
        }

        // Hình vô ân
        if (
            in_array($chi1, DataHelper::$HINH_VO_AN_TRIPLE, true) &&
            in_array($chi2, DataHelper::$HINH_VO_AN_TRIPLE, true)
        ) {
            return true;
        }

        return false;
    }

    // (Optional) Tự hình: chi tự hình chính nó
    public static function isTuHinh(string $chi): bool
    {
        return in_array($chi, ['tý', 'dậu', 'mão', 'ngọ'], true); // ví dụ các chi có khả năng tự hình
    }
    // Kiểm tra Tương Sinh: hanh1 sinh hanh2?
    public static function isSinh(string $hanh1, string $hanh2): bool
    {
        return in_array($hanh1, DataHelper::$NGU_HANH) &&
            in_array($hanh2, DataHelper::$NGU_HANH) &&
            DataHelper::$SINH_RELATIONS[$hanh1] === $hanh2;
    }

    // Kiểm tra Tương Khắc: hanh1 khắc hanh2?
    public static function isKhac(string $hanh1, string $hanh2): bool
    {
        return in_array($hanh1, DataHelper::$NGU_HANH) &&
            in_array($hanh2, DataHelper::$NGU_HANH) &&
            DataHelper::$KHAC_RELATIONS[$hanh1] === $hanh2;
    }
}
