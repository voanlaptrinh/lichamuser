<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class NhiTrucHelper
{
    //Hàm lấy ra thập nhị trực
    public static function getTruc(int $dd, int $mm, int $yy): string
    {
        try {
            // Lấy ngày âm lịch
            $lunarDate = LunarHelper::convertSolar2Lunar($dd, $mm, $yy);
            // $jd = LunarHelper::jdFromDate($dd, $mm, $yy);

            // // Lấy Chi của ngày (ví dụ: "Ất Mão" → "Mão")
            // $canChi = LunarHelper::canchiNgayByJD($jd);
            // $chi = explode(' ', $canChi)[1] ?? null;
            $jd = LunarHelper::jdFromLunarDate((int)$lunarDate[0], (int)$lunarDate[1], (int)$lunarDate[2], (int)$lunarDate[3]);
            $canChiNgayAm = LunarHelper::canchiNgayByJD($jd);
            $chi = explode(' ', $canChiNgayAm)[1] ?? null;
            if (!$chi) {
                throw new \Exception("Không xác định được Chi từ ngày.");
            }
            $tietkhi = LunarHelper::tietKhiWithIcon($jd);

            // Lấy kiến chi theo tháng âm (không cần tiet khi nữa)
            $kienChi = self::getKienChi($tietkhi['tiet_khi'], (int) $lunarDate[1]);

            $trucList = [
                'Kiến',
                'Trừ',
                'Mãn',
                'Bình',
                'Định',
                'Chấp',
                'Phá',
                'Nguy',
                'Thành',
                'Thu',
                'Khai',
                'Bế'
            ];
            $trucIdx = (self::getChiIndex($chi) - self::getChiIndex($kienChi) + 12) % 12;
            return $trucList[$trucIdx];
        } catch (\Exception $e) {
            Log::error("[TRUC] Error: " . $e->getMessage());
            return "Kiến"; // fallback nếu có lỗi
        }
    }


    public static function getKienChi(string $tietKhi, int $lunarMonth): string
    {

        foreach (DataHelper::$tietKhiToKienChi as $key => $value) {
            if (stripos($tietKhi, $key) !== false) {
                return $value;
            }
        }

        // Fallback theo tháng âm
        $kienChiTheoThang = [
            1 => 'Dần',
            2 => 'Mão',
            3 => 'Thìn',
            4 => 'Tỵ',
            5 => 'Ngọ',
            6 => 'Mùi',
            7 => 'Thân',
            8 => 'Dậu',
            9 => 'Tuất',
            10 => 'Hợi',
            11 => 'Tý',
            12 => 'Sửu',
        ];

        return $kienChiTheoThang[$lunarMonth] ?? 'Dần';
    }

    public static function getChiIndex(string $chi): int
    {
        $chiList = ['Tý', 'Sửu', 'Dần', 'Mão', 'Thìn', 'Tỵ', 'Ngọ', 'Mùi', 'Thân', 'Dậu', 'Tuất', 'Hợi'];
        $normalized = ucfirst(mb_strtolower(trim($chi), 'UTF-8'));
        $index = array_search($normalized, $chiList);

        if ($index === false) {
            Log::error("[getChiIndex] Không tìm thấy chỉ số Chi cho: '$chi' (normalized: '$normalized')");
            return -1;
        }

        return $index;
    }
    public static function getTrucRating(string $trucName, string $purpose): float
    {
        if (
            !isset(DataHelper::$TRUC_RATING_BY_PURPOSE[$purpose]) ||
            !isset(DataHelper::$TRUC_RATING_BY_PURPOSE[$purpose][$trucName])
        ) {
            return DataHelper::$TRUC_RATING_BY_PURPOSE['TOT_XAU_CHUNG'][$trucName] ?? 0.0;
        }

        return DataHelper::$TRUC_RATING_BY_PURPOSE[$purpose][$trucName];
    }
    public static function checkTrucIssues(string $trucName, string $purpose): array
    {
        $issues = [];

        if (
            isset(DataHelper::$EXCLUSION_TRUC[$purpose]) &&
            in_array($trucName, DataHelper::$EXCLUSION_TRUC[$purpose])
        ) {
            $issues[] = [
                'level' => 'exclude',
                'source' => '12Truc',
                'reason' => "Thập nhị trực: Trực $trucName",
                'details' => [
                    'trucName' => $trucName,
                    'displayName' => "Trực $trucName",
                    'purpose' => $purpose
                ]
            ];
        }

        return $issues;
    }
}
