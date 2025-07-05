<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Log;

class LichKhongMinhHelper
{
    
    static function numToNgay($thang_num, $ngay_num)
    {
        $ngay = intval($ngay_num); $thang = intval($thang_num);

        if( in_array($thang, array(1,4,7,10)) )
        {
            if(in_array($ngay, array(6, 12, 18, 24, 30)))    return 'Hảo Thương';
            if(in_array($ngay, array(5, 11, 17, 23, 29)))    return 'Đạo Tặc';
            if(in_array($ngay, array(4, 10, 16, 22, 28)))    return 'Thuần Dương';
            if(in_array($ngay, array(1, 7, 13, 19, 25)))    return 'Đường Phong';
            if(in_array($ngay, array(2, 8, 14, 20, 26)))    return 'Kim Thổ';
            if(in_array($ngay, array(3, 9, 15, 21, 27)))    return 'Kim Dương';
        }
        elseif( in_array($thang, array(2,5,8,11)) ){    
            if(in_array($ngay, array(1, 9, 17, 25)))    return 'Thiên Đạo';
            if(in_array($ngay, array(8, 16, 24, 30)))    return 'Thiên Thương';
            if(in_array($ngay, array(7, 15, 23)))    return 'Thiên Hầu';
            if(in_array($ngay, array(6, 14, 22)))    return 'Thiên Dương';
            if(in_array($ngay, array(2, 10, 18, 26)))    return 'Thiên Môn';
            if(in_array($ngay, array(3, 11, 19, 27)))    return 'Thiên Đường';
            if(in_array($ngay, haystack: array(4, 12, 20, 28)))    return 'Thiên Tài';
            if(in_array($ngay, array(5, 13, 21, 29)))    return 'Thiên Tặc';

        }
        elseif( in_array($thang, array(3,6,9,12)) ){
            if(in_array($ngay, array(2, 10, 18, 26)))    return 'Bạch Hổ Đầu';
            if(in_array($ngay, array(3, 11, 19, 27)))    return 'Bạch Hổ Kiếp';
            if(in_array($ngay, array(4, 12, 20, 28)))    return 'Bạch Hổ Túc';
            if(in_array($ngay, array(5, 13, 21, 29)))    return 'Huyền Vũ';
            if(in_array($ngay, array(1, 9, 17)))    return 'Chu Tước';
            if(in_array($ngay, array(8, 16, 24, 30)))    return 'Thanh Long Túc';
            if(in_array($ngay, array(7, 15, 25, 23)))    return 'Thanh Long Kiếp';
            if(in_array($ngay, array(6, 14, 22)))    return 'Thanh Long Đầu';

        }
        return '';
    }

    static function ngayToHTML($ngay){
        $data_ngay = array(
            'Hảo Thương' => 'Ngày <b>Hảo Thương</b>: xuất hành thuận lợi, gặp qúy nhân phù trợ, làm mọi việc vừa lòng, như ý muốn, áo phẩm vinh quy.',
            'Đạo Tặc' => 'Ngày <b>Đạo Tặc</b>: rất xấu, xuất hành bị hại, mất của',
            'Thuần Dương' => 'Ngày <b>Thuần Dương</b>: xuất hành tốt, lúc về cũng tốt, nhiều thuận lợi, được người tốt giúp đỡ, cầu tài được như ý muốn, tranh luận thường thắng lợi',
            'Đường Phong' => 'Ngày <b>Đường Phong</b>: rất tốt, xuất hành thuận lợi, cầu tài được như ý muốn, gặp quý nhân phù trợ',
            'Kim Thổ' => 'Ngày <b>Kim Thổ</b>: ra đi nhỡ tàu, nhỡ xe, cầu tài không được, trên đường đi mất của, bất lợi ',
            'Kim Dương' => 'Ngày <b>Kim Dương</b>: xuất hành tốt, có quý nhân phù trợ, tài lộc thông suốt, thưa kiện có nhiều lý phải',
            'Thiên Đạo' => 'Ngày <b>Thiên Đạo</b>: xuất hành cầu tài nên tránh, dù được cũng rất tốn kém, thất lý mà thua',
            'Thiên Thương' => 'Ngày <b>Thiên Thương</b>: xuất hành để gặp cấp trên thì tuyệt vời, cầu tài thì được tài, mọi việc đều thuận lợi',
            'Thiên Hầu' => 'Ngày <b>Thiên Hầu</b>: xuất hành dầu ít hay nhiều cũng cãi cọ, phải tránh xẩy ra tai nạn chảy máu, máu sẽ khó cầm',
            'Thiên Dương' => 'Ngày <b>Thiên Dương</b>: xuất hành tốt, cầu tài được tài, hỏi vợ được vợ mọi việc đều như ý muốn',
            'Thiên Môn' => 'Ngày <b>Thiên Môn</b>: xuất hành làm mọi việc đều vừa ý, cầu được ước thấy mọi việc đều thành đạt',
            'Thiên Đường' => 'Ngày <b>Thiên Đường</b>: xuất hành tốt, quý nhân phù trợ, buôn bán may mắn, mọi việc đều như ý',
            'Thiên Tài' => 'Ngày <b>Thiên Tài</b>: nên xuất hành, cầu tài thắng lợi, được người tốt giúp đỡ, mọi việc đều thuận',
            'Thiên Tặc' => 'Ngày <b>Thiên Tặc</b>: xuất hành xấu, cầu tài không được, đi đường dễ mất cắp, mọi việc đều rất xấu',
            'Bạch Hổ Đầu' => 'Ngày <b>Bạch Hổ Đầu</b>: xuất hành, cầu tài đều được, đi đâu đều thông đạt cả',
            'Bạch Hổ Kiếp' => 'Ngày <b>Bạch Hổ Kiếp</b>: xuất hành, cầu tài được như ý muốn, đi hướng Nam và Bắc rất thuận lợi',
            'Bạch Hổ Túc' => 'Ngày <b>Bạch Hổ Túc</b>: cấm đi xa, làm việc gì cũng không thành công, rất xấu trong mọi việc',
            'Huyền Vũ' => 'Ngày <b>Huyền Vũ</b>: xuất hành thường gặp cãi cọ, gặp việc xấu, không nên đi',
            'Chu Tước' => 'Ngày <b>Chu Tước</b>: xuất hành, cầu tài đều xấu, hay mất của, kiện cáo thua vì đuối lý',
            'Thanh Long Túc' => 'Ngày <b>Thanh Long Túc</b>: đi xa không nên, xuất hành xấu, tài lộc không có, kiện cáo cũng đuối lý',
            'Thanh Long Kiếp' => 'Ngày <b>Thanh Long Kiếp</b>: xuất hành 4 phương, 8 hướng đều tốt, trăm sự được như ý',
            'Thanh Long Đầu' => 'Ngày <b>Thanh Long Đầu</b>: xuất hành nên đi vào sáng sớm, cầu tài thắng lợi. mọi việc như ý',
        );
        return @$data_ngay[$ngay];
    }
     public static function getKhongMinhLucDieuDayInfo(int $dd, int $mm, int $yy): array
    {
        try {
            // Convert to âm lịch (giả định bạn đã có LunarHelper)
            $lunar = LunarHelper::convertSolar2Lunar($dd, $mm, $yy);
            $lunarDay = $lunar[0];
            $lunarMonth = $lunar[1];

            $order = DataHelper::$lucDieuOrder;
            $startMap = DataHelper::$lucDieuDayStart;
            $detailsMap = DataHelper::$lucDieuDayDetails;

            $startName = $startMap[$lunarMonth] ?? null;

            if (!$startName) {
                throw new \Exception("Không tìm thấy ngày bắt đầu Lục Diệu cho tháng $lunarMonth");
            }

            $startIndex = array_search($startName, $order);
            if ($startIndex === false) {
                throw new \Exception("Tên ngày bắt đầu \"$startName\" không hợp lệ trong chu kỳ Lục Diệu.");
            }

            $dayIndex = ($startIndex + $lunarDay - 1) % 6;
            $dayName = $order[$dayIndex];

            $details = $detailsMap[$dayName] ?? [
                'rating' => 'Không xác định',
                'description' => 'Lỗi tra cứu chi tiết.',
                'poem' => '',
                'icon' => '❓',
            ];

            return array_merge(['name' => $dayName], $details);
        } catch (\Throwable $e) {
            Log::error('Lỗi khi tính Khổng Minh Lục Diệu: ' . $e->getMessage());
            return [
                'name' => 'Lỗi',
                'rating' => 'Không xác định',
                'description' => 'Không thể tính toán Lục Diệu cho ngày này.',
                'poem' => '',
                'icon' => '❓',
            ];
        }
    }
}