<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Log;

class LyThuanPhongHelper
{
    public static function getLyThuanPhongHours(int $dd, int $mm, int $yy): array
    {
        try {
            $lunarDate = LunarHelper::convertSolar2Lunar((int)$dd, (int)$mm, (int)$yy);
            $lunarDay = $lunarDate[0];
            $lunarMonth = $lunarDate[1];

            $result = [];

            foreach (DataHelper::$lyThuanPhongHours as $hourKey => $hourInfo) {
                $khacIndex = DataHelper::$khacDinhDi[$hourKey] ?? null;

                if (is_null($khacIndex)) {
                    Log::warning("Lý Thuần Phong: Không tìm thấy khắc định đi cho giờ \"$hourKey\"");
                    continue;
                }

                $calculationResult = ($lunarDay + $lunarMonth + $khacIndex) - 2;
                $remainder = $calculationResult % 6;

                $hourTypeKey = DataHelper::$lyThuanPhongHourTypes[$remainder] ?? null;
                if (is_null($hourTypeKey)) {
                    Log::warning("Lý Thuần Phong: Không tìm thấy loại giờ cho số dư $remainder");
                    continue;
                }

                $typeData = DataHelper::$lyThuanPhongData[$hourTypeKey] ?? null;
                if (is_null($typeData)) {
                    Log::warning("Lý Thuần Phong: Không tìm thấy dữ liệu chi tiết cho loại giờ \"$hourTypeKey\"");
                    continue;
                }

                $result[] = array_merge($hourInfo, [
                    'name' => $typeData['name'],
                    'rating' => $typeData['rating'],
                    'description' => $typeData['description'],
                    'advice' => $typeData['advice'],
                    'color' => $typeData['color'],
                    'colorValue' => self::getColorFromName($typeData['color']), // nếu bạn có hàm này
                    'icon' => $typeData['icon'],
                ]);
            }

            return $result;
        } catch (\Throwable $e) {
            Log::error("Lỗi khi tính giờ Lý Thuần Phong: " . $e->getMessage());
            return [];
        }
    }
    protected static function getColorFromName(string $colorName): string
    {
        return match ($colorName) {
            'green' => '#388E3C',        // Colors.green.shade700
            'lightGreen' => '#689F38',   // Colors.lightGreen.shade600
            'red' => '#D32F2F',          // Colors.red.shade700
            'orange' => '#F57C00',       // Colors.orange.shade700
            'amber' => '#FFA000',        // Colors.amber.shade700
            default => '#9E9E9E',        // Colors.grey
        };
    }
    // --- Hàm MỚI: Nhóm giờ theo đánh giá VÀ theo tên ---
    /// Nhóm các giờ theo đánh giá (good/bad/neutral) VÀ theo tên loại giờ (Đại An, Tốc Hỷ,...)
    public static function groupAndCombineHours(int $dd, int $mm, int $yy): array
    {
        $allHours = self::getLyThuanPhongHours((int)$dd, (int)$mm, (int)$yy);

        $result = [
            'good' => [],
            'neutral' => [],
            'bad' => [],
        ];

        $groupedByRating = [
            'good' => [],
            'neutral' => [],
            'bad' => [],
        ];

        // Phân loại theo đánh giá
        foreach ($allHours as $hour) {
            $rating = $hour['rating'] ?? '';
            if (in_array($rating, ['Rất tốt', 'Tốt', 'Tốt vừa'])) {
                $groupedByRating['good'][] = $hour;
            } elseif (in_array($rating, ['Xấu', 'Rất xấu', 'Xấu vừa'])) {
                $groupedByRating['bad'][] = $hour;
            } else {
                $groupedByRating['neutral'][] = $hour;
            }
        }

        // Hàm nhóm theo 'name'
        $groupByName = function (array $items): array {
            $grouped = [];
            foreach ($items as $item) {
                $key = $item['name'] ?? 'unknown';
                $grouped[$key][] = $item;
            }
            return $grouped;
        };

        // Hàm gộp các mục có nội dung giống nhau
        $mergeSimilarItems = function (array $items): array {
            $merged = [];

            foreach ($items as $item) {
                // Tạo key so sánh bằng cách loại bỏ chi, timeRange, pair
                $compareKey = md5(json_encode(array_diff_key($item, array_flip(['chi', 'timeRange', 'pair']))));

                if (!isset($merged[$compareKey])) {
                    $merged[$compareKey] = array_diff_key($item, array_flip(['chi', 'timeRange', 'pair']));
                    $merged[$compareKey]['chi'] = [$item['chi']];
                    $merged[$compareKey]['timeRange'] = [$item['timeRange']];
                    $merged[$compareKey]['pair'] = [$item['pair']];
                } else {
                    $merged[$compareKey]['chi'][] = $item['chi'];
                    $merged[$compareKey]['timeRange'][] = $item['timeRange'];
                    $merged[$compareKey]['pair'][] = $item['pair'];
                }
            }

            return array_values($merged);
        };

        // Sắp xếp thứ tự ưu tiên nhóm
        foreach ($groupedByRating as $ratingKey => $hoursInRating) {
            $groupedByName = $groupByName($hoursInRating);

            if ($ratingKey === 'good') {
                $order = ['Đại An', 'Tốc Hỷ', 'Tiểu Cát'];
            } elseif ($ratingKey === 'bad') {
                $order = ['Không Vong', 'Xích Khẩu', 'Lưu Niên'];
            } else {
                // Với neutral, vẫn gộp nội dung giống nhau
                foreach ($groupedByName as $name => $items) {
                    $groupedByName[$name] = $mergeSimilarItems($items);
                }
                $result[$ratingKey] = $groupedByName;
                continue;
            }

            // Gộp nội dung giống nhau trước khi sắp xếp
            foreach ($groupedByName as $name => $items) {
                $groupedByName[$name] = $mergeSimilarItems($items);
            }

            // Sắp xếp theo $order
            uksort($groupedByName, function ($a, $b) use ($order) {
                $indexA = array_search($a, $order);
                $indexB = array_search($b, $order);
                return ($indexA !== false ? $indexA : 99) <=> ($indexB !== false ? $indexB : 99);
            });

            $result[$ratingKey] = $groupedByName;
        }

        return $result;
    }


    public static function getTravelConclusion($dayDailyRating, $hyThanDirection, $taiThanDirection, $hacThanDirection, $groupedAndCombinedHours): string
    {
        try {
            // 1. Đánh giá ngày
            $dayRatingConclusion = "ngày {$dayDailyRating} để xuất hành";

            // 2. Hướng tốt và xấu
            $directionConclusion = '';
            $goodDirs = [];

            if (!empty($hyThanDirection) && $hyThanDirection !== 'Không xác định') {
                $goodDirs[] = $hyThanDirection;
            }
            if (!empty($taiThanDirection) && $taiThanDirection !== 'Không xác định' && $taiThanDirection !== $hyThanDirection) {
                $goodDirs[] = $taiThanDirection;
            }

            if (!empty($goodDirs)) {
                $directionConclusion .= ', nếu xuất hành nên chọn hướng ' . implode(' hoặc ', $goodDirs);
            }

            if (!empty($hacThanDirection) && $hacThanDirection !== 'Không xác định') {
                if ($hacThanDirection === 'Hạc Thần bận việc trên trời') {
                    $directionConclusion .= ', không cần tránh hướng Hạc Thần';
                } else {
                    $directionConclusion .= ', tránh hướng ' . $hacThanDirection . ' (hướng xấu gặp Hạc thần)';
                }
            }
            $directionConclusion .= '.';

            // 3. Giờ tốt ban ngày
            $timeConclusion = ' Nên chọn các khung giờ ban ngày tốt như';
            $bestDaytimeHours = [];


            $goodHoursMap = $groupedAndCombinedHours['good'] ?? [];

            $filterDaytime = function ($hourList) {
                $result = [];

                foreach ($hourList as $hourItem) {
                    $ranges = $hourItem['timeRange'] ?? [];

                    // Lọc riêng các khung giờ ban ngày trong timeRange
                    $dayRanges = array_filter($ranges, function ($range) {
                        [$start, $end] = explode('-', $range);
                        $startHour = (int) explode(':', $start)[0];
                        $endHour = (int) explode(':', $end)[0];

                        // Ban ngày là 5h–19h
                        // Cần xét start và end để lọc chính xác
                        return ($startHour >= 5 && $startHour < 19) || ($endHour > 5 && $endHour <= 19);
                    });

                    if (!empty($dayRanges)) {
                        // Thêm vào kết quả mới với chỉ khung giờ ban ngày
                        $newHourItem = $hourItem;
                        $newHourItem['timeRange'] = array_values($dayRanges);
                        $result[] = $newHourItem;
                    }
                }

                return $result;
            };

            $daytimeDaiAn = $filterDaytime($goodHoursMap['Đại An'] ?? []);
            if (!empty($daytimeDaiAn)) {
                $bestDaytimeHours[] = 'Đại An: ' . self::_formatLtpTimeForConclusion($daytimeDaiAn);
            }

            $daytimeTocHy = $filterDaytime($goodHoursMap['Tốc Hỷ'] ?? []);
            if (!empty($daytimeTocHy)) {
                $bestDaytimeHours[] = 'Tốc Hỷ: ' . self::_formatLtpTimeForConclusion($daytimeTocHy);
            }

            $daytimeTieuCat = $filterDaytime($goodHoursMap['Tiểu Cát'] ?? []);
            if (!empty($daytimeTieuCat)) {
                $bestDaytimeHours[] = 'Tiểu Cát: ' . self::_formatLtpTimeForConclusion($daytimeTieuCat);
            }

            if (!empty($bestDaytimeHours)) {
                $timeConclusion .= ' ' . implode(' hoặc ', array_slice($bestDaytimeHours, 0, 2)) . ' để xuất hành.';
            } else {
                $timeConclusion = ' Hôm nay không có khung giờ Hoàng đạo nào đặc biệt tốt vào ban ngày, nên cân nhắc kỹ nếu cần xuất hành.';
            }

            // 4. Kết luận tổng
            return "👉 Đây là $dayRatingConclusion{$directionConclusion}{$timeConclusion}";
        } catch (\Throwable $e) {
            \Log::error("Lỗi tạo kết luận Xuất hành: " . $e->getMessage());
            return "👉 Không thể tạo kết luận xuất hành.";
        }
    }

    public static function _formatLtpTimeForConclusion(array $hourList): string
    {
        if (empty($hourList)) return '';

        try {
            $results = [];

            foreach ($hourList as $hourItem) {
                $ranges = $hourItem['timeRange'] ?? [];

                foreach ($ranges as $range) {
                    $formattedTime = self::_formatHourRangeOnly($range);
                    $chi = self::_getChiFromHourRange($range);

                    $results[] = "{$formattedTime} ({$chi})";
                }
            }

            return implode(' và ', $results);
        } catch (\Throwable $e) {
            return 'Lỗi giờ';
        }
    }



    public static function _formatHourRangeOnly(string $standardRange): string
    {
        try {
            $parts = explode('-', $standardRange);
            if (count($parts) === 2) {
                $startHour = (int) explode(':', $parts[0])[0];
                $endHour = (int) explode(':', $parts[1])[0];

                $endHourDisplay = ($endHour === 0 || $endHour === 1) ? 1 : $endHour;
                if ($startHour === 21) $endHourDisplay = 23;

                return "{$startHour}h–{$endHourDisplay}h";
            }
            return $standardRange;
        } catch (\Throwable $e) {
            \Log::warning("Lỗi format giờ LTP đơn giản: '$standardRange' - {$e->getMessage()}");
            return '(Lỗi giờ)';
        }
    }
    public static function _getChiFromHourRange(string $range): string
    {
        $hourToChi = [
            'tý',   // 23h–1h
            'sửu',  // 1h–3h
            'dần',  // 3h–5h
            'mão',  // 5h–7h
            'thìn', // 7h–9h
            'tỵ',   // 9h–11h
            'ngọ',  // 11h–13h
            'mùi',  // 13h–15h
            'thân', // 15h–17h
            'dậu',  // 17h–19h
            'tuất', // 19h–21h
            'hợi',  // 21h–23h
        ];

        try {
            $parts = explode('-', $range);
            if (count($parts) === 2) {
                $startHour = (int) explode(':', $parts[0])[0];
                $index = (int) floor(($startHour + 1) % 24 / 2); // dịch về 0–11
                return ucfirst($hourToChi[$index] ?? 'N/A');
            }
        } catch (\Throwable $e) {
            \Log::warning("Lỗi xác định Chi từ giờ '$range': {$e->getMessage()}");
        }

        return 'N/A';
    }
}
