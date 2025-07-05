<?php

namespace App\Helpers;

class SaoTotXauHelper
{
    public static function getStarDisplayName(string $starName): string
    {
        return  DataHelper::$starDisplayNames[$starName] ?? $starName;
    }
    public static function getNgocHapConclusion(array $ngocHapCatTinh, array $ngocHapHungSat): string
    {
        try {
            if (empty($ngocHapCatTinh) && empty($ngocHapHungSat)) {
                return "👉 Không có thông tin sao tốt/xấu đặc biệt theo Ngọc Hạp cho ngày này.";
            }

            $catScoreRaw = 0.0;
            $topGoodStars = [];
            if (!empty($ngocHapCatTinh)) {
                $tempGoodStars = [];

                foreach ($ngocHapCatTinh as $name => $description) {

                    if (!empty($name)) {
                        $score = CatHungHelper::getStarRatingForPurpose($name, 'TOT_XAU_CHUNG', true);
                        $catScoreRaw += $score;
                        $tempGoodStars[] = ['name' => $name, 'score' => $score];
                    }
                }

                // Sắp xếp điểm từ cao đến thấp
                usort($tempGoodStars, fn($a, $b) => $b['score'] <=> $a['score']);

                // Lấy top 2 sao tốt
                $topGoodStars = array_map(
                    fn($s) => ['name' => $s['name']],
                    array_slice($tempGoodStars, 0, 2)
                );
            }


            $hungScoreRaw = 0.0;
            $topBadStars = [];

            if (!empty($ngocHapHungSat)) {
                $tempBadStars = [];

                foreach ($ngocHapHungSat as $name => $description) {
                    if (!empty($name)) {
                        $score = CatHungHelper::getStarRatingForPurpose($name, 'TOT_XAU_CHUNG', false);
                        $hungScoreRaw += $score;
                        $tempBadStars[] = ['name' => $name, 'score' => $score];
                    }
                }

                // Sắp xếp điểm từ thấp đến cao (xấu nhất trước)
                usort($tempBadStars, fn($a, $b) => $a['score'] <=> $b['score']);

                // Lấy top 2 sao xấu nhất
                $topBadStars = array_map(
                    fn($s) => ['name' => $s['name']],
                    array_slice($tempBadStars, 0, 2)
                );
            }


            $totalScore = $catScoreRaw + $hungScoreRaw;

            $conclusion = "👉 Sao tốt liệu có hóa giải được sao xấu không? ";
            if ($totalScore < 0) {
                $badNames = implode(', ', array_column($topBadStars, 'name'));
                if (empty($badNames) && !empty($ngocHapHungSat)) {
                    $badNames = implode(', ', array_map(
                        fn($s) => $s['name'] ?? '',
                        array_slice($ngocHapHungSat, 0, 2)
                    ));
                }

                $conclusion .= "Ngày này có một số sao tốt, nhưng lại có sự xuất hiện của các hung tinh mạnh mẽ như "
                    . ($badNames ?: "một số sao xấu")
                    . " gây ảnh hưởng tiêu cực đến vận khí của ngày. Mặc dù sao tốt xuất hiện không ít, nhưng về tổng thể chúng vẫn không đủ để hóa giải hoàn toàn các sao xấu. Do đó, khi tiến hành các công việc quan trọng, bạn cần đặc biệt lưu ý và tìm cách hóa giải các yếu tố xấu trước khi thực hiện các quyết định trọng đại.";
            } else {
                $goodNames = implode(', ', array_column($topGoodStars, 'name'));
                if (empty($goodNames) && !empty($ngocHapCatTinh)) {
                    $goodNames = implode(', ', array_map(
                        fn($s) => $s['name'] ?? '',
                        array_slice($ngocHapCatTinh, 0, 2)
                    ));
                }

                $conclusion .= "Dù ngày này có một số sao xấu, nhưng lại xuất hiện những sao tốt như "
                    . ($goodNames ?: "các sao tốt khác")
                    . " giúp hỗ trợ rất tốt cho công việc trong ngày. Tổng thể, các sao tốt xuất hiện đủ mạnh mẽ để hóa giải ảnh hưởng tiêu cực từ sao xấu. Tuy nhiên, khi tiến hành các công việc lớn, bạn vẫn cần chú ý đến các yếu tố khác như ngày kỵ, tuổi xung, hay trực xấu. Nếu có, hãy tìm cách hóa giải trước khi thực hiện các công việc quan trọng để đạt được kết quả tốt nhất.";
            }

            return $conclusion;
        } catch (\Throwable $e) {
            \Log::error('Lỗi tạo kết luận Ngọc Hạp', ['error' => $e]);
            return "👉 Không thể tạo kết luận về Ngọc Hạp.";
        }
    }
}
