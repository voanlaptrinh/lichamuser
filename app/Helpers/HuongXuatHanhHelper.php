<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Log;

class HuongXuatHanhHelper
{
    public static function getMonthGroup($month)
    {
        if (in_array($month, [1, 4, 7, 10])) return 1;
        if (in_array($month, [2, 5, 8, 11])) return 2;
        if (in_array($month, [3, 6, 9, 12])) return 3;
        return 0;
    }

    public static function calculateXuatHanhDay(int $monthGroup, int $day): string
    {
        // Chuẩn hóa ngày trong trường hợp tháng thiếu (29 ngày)
        if ($day > 29) {
            $day = $day % 29;
        }
        if ($day == 0) {
            $day = 29; // Trường hợp chia hết
        }

        // Tính số dư khi chia cho 8 (chu kỳ 8 ngày)
        $dayMod8 = ($day - 1) % 8 + 1;

        switch ($monthGroup) {
            case 1: // Tháng 1, 4, 7, 10
                switch ($day % 6) {
                    case 0:
                        return 'Hảo Thương'; // 6, 12, 18, 24, 30
                    case 5:
                        return 'Đạo Tặc'; // 5, 11, 17, 23, 29
                    case 4:
                        return 'Thuần Dương'; // 4, 10, 16, 22, 28
                    case 1:
                        return 'Đường Phong'; // 1, 7, 13, 19, 25
                    case 2:
                        return 'Kim Thổ'; // 2, 8, 14, 20, 26
                    case 3:
                        return 'Kim Dương'; // 3, 9, 15, 21, 27
                    default:
                        return '';
                }

            case 2: // Tháng 2, 5, 8, 11
                switch ($day % 8) {
                    case 1:
                        return 'Thiên Đạo';
                    case 0:
                        return 'Thiên Thương';
                    case 7:
                        return 'Thiên Hầu';
                    case 6:
                        return 'Thiên Dương';
                    case 2:
                        return 'Thiên Môn';
                    case 3:
                        return 'Thiên Đường';
                    case 4:
                        return 'Thiên Tài';
                    case 5:
                        return 'Thiên Tặc';
                    default:
                        return '';
                }

            case 3: // Tháng 3, 6, 9, 12
                switch ($day % 8) {
                    case 2:
                        return 'Bạch Hổ Đầu';
                    case 3:
                        return 'Bạch Hổ Kiếp';
                    case 4:
                        return 'Bạch Hổ Túc';
                    case 5:
                        return 'Huyền Vũ';
                    case 1:
                        return 'Chu Tước';
                    case 0:
                        return 'Thanh Long Túc';
                    case 7:
                        return 'Thanh Long Kiếp';
                    case 6:
                        return 'Thanh Long Đầu';
                    default:
                        return '';
                }

            default:
                return '';
        }
    }
    public static function getXuatHanhInfo(string $title): array
    {
        $rating = DataHelper::$xuatHanhRating[$title] ?? 'Không rõ';
        $description = DataHelper::$xuatHanhDescription[$title] ?? 'Không có mô tả cho xuất hành này.';

        return [
            'title' => $title,
            'rating' => $rating,
            'description' => $description,
        ];
    }


     public static function getHuongXuatHanh(int $dd, int $mm, int $yy)
    {
         $al = LunarHelper::convertSolar2Lunar((int)$dd, (int)$mm, (int)$yy);
          $jd = LunarHelper::jdFromDate((int)$dd, (int)$mm, (int)$yy);

        $canChiDay = LunarHelper::canchiNgayByJD($jd);
        // $canChiDay = LunarHelper::getCanChiNgay($date); // cần hàm này
        $thienCan = explode(' ', $canChiDay)[0];
        $hyThan = DataHelper::$hyThanDirections[$thienCan] ?? 'Không xác định';
        $taiThan = DataHelper::$taiThanDirections[$thienCan] ?? 'Không xác định';
        $hacThan = in_array($canChiDay, DataHelper::$hacThanBusyDays)
            ? 'Hạc Thần bận việc trên trời'
            : (DataHelper::$hacThanDirections[$canChiDay] ?? 'Không xác định');

        return [
            'canChiDay' => $canChiDay,
            'hyThan' => [
                'direction' => $hyThan,
                'icon' => DataHelper::$directionIcons[$hyThan] ?? '➡️',
                'description' => DataHelper::$huongDirectionDescriptions[$hyThan] ?? '',
            ],
            'taiThan' => [
                'direction' => $taiThan,
                'icon' => DataHelper::$directionIcons[$taiThan] ?? '➡️',
                'description' => DataHelper::$huongDirectionDescriptions[$taiThan] ?? '',
            ],
            'hacThan' => [
                'direction' => $hacThan,
                'icon' => $hacThan == 'Hạc Thần bận việc trên trời' ? '🌤️' : (DataHelper::$directionIcons[$hacThan] ?? '➡️'),
                'description' => $hacThan == 'Hạc Thần bận việc trên trời'
                    ? 'Ngày này Hạc Thần bận việc trên trời, không cần lo ngại về hướng xuất hành xấu.'
                    : (DataHelper::$huongDirectionDescriptions[$hacThan] ?? ''),
            ],
            'godDescriptions' => DataHelper::$xuatHanhGodDescriptions,
            'godIcons' => DataHelper::$xuatHanhGodIcons,
        ];
    }
}
