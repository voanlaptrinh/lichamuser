<?php

namespace App\Helpers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class CatHungHelper
{

    // ... các hàm phụ như getStarRatingForPurpose, isStarExcludedForPurpose ...

     // ... các hàm phụ khác như getStarRatingForPurpose, isStarExcludedForPurpose, getStarDisplayName ...

    /**
     * Đánh giá Cát Hung dựa trên các sao tốt/xấu trong ngày cho một mục đích cụ thể.
     * Logic đã được điều chỉnh để khớp hoàn toàn với phiên bản Dart đã được xác thực.
     *
     * @param Carbon $date Ngày cần đánh giá.
     * @param string $purpose Mục đích xem ngày (ví dụ: 'CUOI_HOI').
     * @return array
     */
    public static function evaluateCatHung(Carbon $date, string $purpose): array
    {
        // Lấy danh sách sao và khởi tạo, đảm bảo luôn là mảng
        $catTinhList = XemNgayTotXauHelper::getCatTinh($date) ?? [];
        $hungSatList = XemNgayTotXauHelper::getNgocHapHungSat($date) ?? [];
         $issues = [];

        // 1. Tính điểm thô (logic này của bạn đã đúng)
        $catScoreRaw = 0.0;
        foreach ($catTinhList as $star) {
            if (is_array($star) && isset($star['name'])) {
                $catScoreRaw += self::getStarRatingForPurpose($star['name'], $purpose, true);
            }
        }

        $hungScoreRaw = 0.0;
        foreach ($hungSatList as $star) {
            if (is_array($star) && isset($star['name'])) {
                $starName = $star['name'];
                if (self::isStarExcludedForPurpose($starName, $purpose)) {
                    $issues[] = [
                        'level' => 'exclude', 'source' => 'CatHung',
                        'reason' => 'Sao xấu: ' . self::getStarDisplayName($starName),
                        'details' => ['starName' => $starName, 'displayName' => self::getStarDisplayName($starName)]
                    ];
                }
                $hungScoreRaw += self::getStarRatingForPurpose($starName, $purpose, false);
            }
        }

        // Tổng điểm thô (x)
        $x = $catScoreRaw + $hungScoreRaw;

    
        // 2. Lấy giá trị x_min, x_max một cách an toàn
        $effectivePurpose = array_key_exists($purpose, DataHelper::$purposeMinMaxScores)
            ? $purpose
            : 'TOT_XAU_CHUNG';
        
        // SỬA LỖI Ở ĐÂY: Lấy dữ liệu một cách an toàn, có giá trị mặc định
        $minMaxData = DataHelper::$purposeMinMaxScores[$effectivePurpose] ?? null;

        // Nếu $minMaxData là null, sử dụng giá trị mặc định giống hệt Dart
        $x_min = $minMaxData['min'] ?? -20.0;
        $x_max = $minMaxData['max'] ?? 20.0;
        $abs_x_min = abs($x_min);
        
        // Log warning nếu không tìm thấy, giống hệt Dart
        if ($minMaxData === null) {
             Log::warning("CatHungService: Không tìm thấy min/max scores cho purpose '$purpose', dùng mặc định của '$effectivePurpose'.");
        }

        // 3. Áp dụng công thức tính phần trăm (logic này của bạn đã đúng)
        $percentage = 50.0;
        if ($x <= 0) {
            $percentage = ($abs_x_min > 0)
                ? (($x + $abs_x_min) / $abs_x_min) * 50.0
                : 50.0;
        } else {
            $percentage = ($x_max > 0)
                ? ($x / $x_max) * 50.0 + 50.0
                : 100.0;
        }
        
        // 4. Giới hạn phần trăm trong khoảng [0, 100] (clamp)
        $percentage = max(0.0, min(100.0, $percentage));

        // 5. Quy đổi ngược từ phần trăm sang thang điểm [-2, 2] (logic này của bạn đã đúng)
        $score = ($percentage / 25.0) - 2.0;
        $score = max(-2.0, min(2.0, $score)); // Đảm bảo không có sai số

        // 6. Trả về kết quả (logic này của bạn đã đúng)
        return [
            'score' => $score,
            'issues' => $issues,
            'details' => [
                'catScoreRaw' => $catScoreRaw,
                'hungScoreRaw' => $hungScoreRaw,
                'totalScoreRaw' => $x,
                'percentage' => round($percentage, 2), // Làm tròn cho đẹp
                'x_min_used' => $x_min,
                'x_max_used' => $x_max,
                'catStarsCount' => count($catTinhList),
                'hungStarsCount' => count($hungSatList),
                'catStars' => $catTinhList,
                'hungStars' => $hungSatList,
            ]
        ];
    }
  




    public static function isStarExcludedForPurpose($starName, $purpose)
    {
        return isset(DataHelper::$exclusionStars[$purpose]) &&
            in_array($starName, DataHelper::$exclusionStars[$purpose]);
    }

    public static function getStarRatingForPurpose($starName, $purpose, $isCatTinh)
    {
        $ratings = DataHelper::$starRatings;

        // Nếu có rating đặc biệt cho mục đích và sao này
        if (isset($ratings[$purpose][$starName])) {
            return $ratings[$purpose][$starName];
        }

        // Nếu không có, thử lấy từ TOT_XAU_CHUNG
        if (isset($ratings['TOT_XAU_CHUNG'][$starName])) {
            return $ratings['TOT_XAU_CHUNG'][$starName];
        }

        // Nếu không có trong bảng, trả về giá trị mặc định
        return $isCatTinh ? 0.5 : -0.5;
    }

    public static function getStarDisplayName($starName)
    {
        return DataHelper::$starDisplayNames[$starName] ?? $starName;
    }
}
