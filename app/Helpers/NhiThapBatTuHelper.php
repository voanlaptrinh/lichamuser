<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class NhiThapBatTuHelper
{
    /**
     * Dữ liệu cơ sở để tính toán.
     * NGÀY: 09/02/2005
     * SAO: Cơ (Tương ứng với chỉ số 6 trong mảng 0-based)
     */
    private const BASE_DATE_STRING = '2005-02-09';
    private const BASE_STAR_INDEX = 6;
    /**
     * Phương thức chính để lấy thông tin sao Nhị Thập Bát Tú.
     * Hàm này được thiết kế để luôn cho kết quả đúng và có thể dự đoán.
     *
     * @param Carbon|string $inputDate  Ngày cần tính, có thể là đối tượng Carbon hoặc chuỗi (ví dụ: '2024-05-22').
     * @return array                    Mảng chứa thông tin của sao, hoặc mảng lỗi nếu có vấn đề.
     */
    public static function getNhiThapBatTu(Carbon|string $inputDate): array
    {
        try {
            // Bước 1: Chuẩn hóa đầu vào
            // Luôn đảm bảo chúng ta làm việc với một đối tượng Carbon hợp lệ.
            // Điều này giúp hàm linh hoạt hơn, chấp nhận cả chuỗi và đối tượng.
            $targetDate = Carbon::parse($inputDate);

            // Bước 2: Chuẩn hóa múi giờ và thời gian
            // Để đảm bảo tính toán nhất quán, mọi ngày đều được đưa về múi giờ Việt Nam
            // và đặt thời gian về đầu ngày (00:00:00).
            $timeZone = 'Asia/Ho_Chi_Minh';
            $normalizedTargetDate = $targetDate->setTimezone($timeZone)->startOfDay();
            $baseDate = Carbon::createFromFormat( 'Y-m-d', self::BASE_DATE_STRING, $timeZone)->startOfDay();

            // Bước 3: Tính toán chênh lệch số ngày
            // Công thức: (Ngày Cần Tính) - (Ngày Cơ Sở)
            $daysDifference = $baseDate->diffInDays($normalizedTargetDate, false);

            // Tính chỉ số sao với modulo dương
            $starIndex = (self::BASE_STAR_INDEX + $daysDifference) % 28;
            if ($starIndex < 0) {
                $starIndex += 28;
            }


            // Bước 5: Lấy thông tin sao từ mảng dữ liệu và trả về
            // Kiểm tra xem chỉ số có tồn tại trong mảng dữ liệu không.
            if (isset(DataHelper::$nhiThapBatTu[$starIndex])) {
                return DataHelper::$nhiThapBatTu[$starIndex];
            }

            // Nếu vì một lý do nào đó mà chỉ số không hợp lệ, ghi log và ném lỗi.
            throw new \Exception("Chỉ số sao không hợp lệ được tính toán: {$starIndex}");
        } catch (\Throwable $e) {
            // Bắt tất cả các loại lỗi có thể xảy ra (ngày không hợp lệ, tính toán lỗi,...)
            Log::error("Lỗi khi tính Nhị Thập Bát Tú: " . $e->getMessage());

            // Trả về một mảng lỗi rõ ràng để phía client có thể xử lý.
            return [
                'name' => 'Lỗi',
                'description' => 'Không thể tính toán sao cho ngày được cung cấp.',
                'error' => $e->getMessage(),
            ];
        }
    }




    // protected static function limitCacheSize(int $max = 500): void
    // {
    //     if (count(self::$cache) > $max) {
    //         self::$cache = array_slice(self::$cache, -$max, null, true);
    //     }
    // }
    /**
     * Lấy điểm đánh giá (rating) của một sao cho một mục đích cụ thể.
     *
     * @param string $starName Tên của sao (ví dụ: 'Giác', 'Cang').
     * @param string $purpose  Mục đích cần đánh giá (ví dụ: 'CUOI_HOI').
     * @return float           Điểm đánh giá, mặc định là 0.0 nếu không tìm thấy.
     */
    public static function getStarRating(string $starName, string $purpose): float
    {
        // Sử dụng toán tử ?? (null coalescing) để viết code gọn gàng và an toàn.

        // 1. Thử lấy điểm từ mục đích cụ thể ($purpose).
        // Nếu $purpose không tồn tại hoặc $starName không có trong $purpose, kết quả sẽ là `null`.
        $rating = DataHelper::$STAR_RATING_BY_PURPOSE[$purpose][$starName] ?? null;
        
        // 2. Nếu $rating vẫn là `null`, có nghĩa là không tìm thấy ở bước 1.
        //    Lúc này, ta thử lấy điểm từ mục đích dự phòng 'TOT_XAU_CHUNG'.
        //    Nếu vẫn không tìm thấy, giá trị mặc định cuối cùng sẽ là 0.0.
        return $rating ?? DataHelper::$STAR_RATING_BY_PURPOSE['TOT_XAU_CHUNG'][$starName] ?? 0.0;
    }



    /**
     * Lấy tên sao theo ngày
     *
     * @param \DateTime|string|Carbon $date
     * @return string
     */
    public static function getSao($date): string
    {
        return self::getNhiThapBatTu($date)['name'] ?? 'Nguy';
    }

    /**
     * Lấy thông tin sao theo ngày
     *
     * @param \DateTime|string|Carbon $date
     * @return array
     */
    public static function getSaoInfo($date): array
    {
        return self::getNhiThapBatTu($date);
    }
    public static function checkStarIssues(string $starName, string $purpose): array
    {
        $issues = [];
        if (
            isset(DataHelper::$EXCLUSION_STARS[$purpose]) &&
            in_array($starName, DataHelper::$EXCLUSION_STARS[$purpose])
        ) {
            $issues[] = [
                'level' => 'exclude',
                'source' => '28Tu',
                'reason' => "Sao 28 Tú: $starName kỵ với mục đích này.",
                'details' => [
                    'starName' => $starName,
                    'purpose' => $purpose,
                ]
            ];
        }

        return $issues;
    }
}
