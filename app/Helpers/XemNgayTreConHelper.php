<?php

namespace App\Helpers;

// Bạn có thể không cần Carbon nếu LunarHelper đã xử lý hết
// use Carbon\Carbon; 
// use Illuminate\Support\Facades\Log;

class XemNgayTreConHelper
{
    /**
     * Cung cấp dữ liệu tĩnh về Can, Chi, Ngũ Hành và các giờ phạm
     */
    public static function getLichData(): array
    {
        return [
            'can' => ['Canh', 'Tân', 'Nhâm', 'Quý', 'Giáp', 'Ất', 'Bính', 'Đinh', 'Mậu', 'Kỷ'],
            'chi' => ['Thân', 'Dậu', 'Tuất', 'Hợi', 'Tý', 'Sửu', 'Dần', 'Mão', 'Thìn', 'Tỵ', 'Ngọ', 'Mùi'],
            'can_hanh' => [ 'Giáp' => 'Mộc', 'Ất' => 'Mộc', 'Bính' => 'Hỏa', 'Đinh' => 'Hỏa', 'Mậu' => 'Thổ', 'Kỷ' => 'Thổ', 'Canh' => 'Kim', 'Tân' => 'Kim', 'Nhâm' => 'Thủy', 'Quý' => 'Thủy', ],
            'gio_chi' => [ 'Tý' => '23:00 - 00:59', 'Sửu' => '01:00 - 02:59', 'Dần' => '03:00 - 04:59', 'Mão' => '05:00 - 06:59', 'Thìn' => '07:00 - 08:59', 'Tỵ' => '09:00 - 10:59', 'Ngọ' => '11:00 - 12:59', 'Mùi' => '13:00 - 14:59', 'Thân' => '15:00 - 16:59', 'Dậu' => '17:00 - 18:59', 'Tuất' => '19:00 - 20:59', 'Hợi' => '21:00 - 22:59', ],
            'mua' => [ 1 => 'Xuân', 2 => 'Xuân', 3 => 'Xuân', 4 => 'Hạ', 5 => 'Hạ', 6 => 'Hạ', 7 => 'Thu', 8 => 'Thu', 9 => 'Thu', 10 => 'Đông', 11 => 'Đông', 12 => 'Đông' ],

            // Dữ liệu cho giờ phạm
            'kim_xa' => [ 'Thìn' => 'Tỵ', 'Tỵ' => 'Thìn', 'Tuất' => 'Hợi', 'Hợi' => 'Tuất', 'Sửu' => 'Sửu', 'Mùi' => 'Mùi', ],
            'da_de' => ['Tý', 'Ngọ', 'Mão', 'Dậu'],
            'quan_sat' => [ 'Giáp' => 'Sửu', 'Ất' => 'Thìn', 'Bính' => 'Mùi', 'Đinh' => 'Tuất', 'Mậu' => 'Sửu', 'Kỷ' => 'Thìn', 'Canh' => 'Mùi', 'Tân' => 'Tuất', 'Nhâm' => 'Sửu', 'Quý' => 'Thìn', ],
            'diem_vuong' => [ 1 => 'Sửu', 2 => 'Tuất', 3 => 'Thìn', 4 => 'Mão', 5 => 'Tý', 6 => 'Hợi', 7 => 'Dậu', 8 => 'Mùi', 9 => 'Ngọ', 10 => 'Tỵ', 11 => 'Dần', 12 => 'Thân', ],
            'tuong_quan' => [ 1 => 'Dậu', 2 => 'Ngọ', 3 => 'Mão', 4 => 'Tý', 5 => 'Hợi', 6 => 'Thân', 7 => 'Tỵ', 8 => 'Dần', 9 => 'Sửu', 10 => 'Tuất', 11 => 'Mùi', 12 => 'Thìn', ]
        ];
    }
    
    /**
     * Hàm chính để phân tích toàn bộ thông tin
     * @param int $day
     * @param int $month
     * @param int $year
     * @param string $chiGio
     * @param string $gioiTinh
     * @param int|null $namSinhBo
     * @return array
     */
    public static function phanTichNgayGioSinh(int $day, int $month, int $year, string $chiGio, string $gioiTinh, ?int $namSinhBo = null): array
    {
        $data = self::getLichData();
        $jd = LunarHelper::jdFromDate($day, $month, $year);
        $lunar = LunarHelper::convertSolar2Lunar($day, $month, $year);

        $lunarDay = $lunar[0];
        $lunarMonth = $lunar[1];
        $lunarYear = $lunar[2];
        $isLeapMonth = $lunar[3];

        // Lấy Can Chi ngày
        $canNgay = $data['can'][($jd + 9) % 10];
        $chiNgay = $data['chi'][($jd + 1) % 12];
        $chiNamSinh = $data['chi'][$lunarYear % 12];
        
        $canNam = $data['can'][$lunarYear % 10];
        $canThangKey = (($lunarYear - 1900) * 12 + $lunarMonth + 13) % 10;
        $canThang = $data['can'][$canThangKey];
        $chiThang = $data['chi'][($lunarMonth + 1) % 12];

        // Bắt đầu phân tích
        $analysis = [];
        
        // 1. Kiểm tra Kim Xà Thiết Tỏa
        $phamKimXa = (isset($data['kim_xa'][$chiNamSinh]) && $data['kim_xa'][$chiNamSinh] === $chiGio);
        $analysis['kim_xa'] = ['pham' => $phamKimXa, 'ket_luan' => ($phamKimXa ? 'Phạm' : 'Không phạm') . ' giờ Kim xà thiết tỏa'];
        
        // 2. Kiểm tra Dạ Đề
        $phamDaDe = in_array($chiGio, $data['da_de']);
        $analysis['da_de'] = ['pham' => $phamDaDe, 'ket_luan' => ($phamDaDe ? 'Phạm' : 'Không phạm') . ' giờ Dạ đề'];

        // 3. Kiểm tra Quan Sát
        $phamQuanSat = false;
        if ($namSinhBo) {
            $canBo = $data['can'][($namSinhBo - 4) % 10];
            $phamQuanSat = (isset($data['quan_sat'][$canBo]) && $data['quan_sat'][$canBo] === $chiGio);
        }
        $analysis['quan_sat'] = ['pham' => $phamQuanSat, 'ket_luan' => ($phamQuanSat ? 'Phạm' : 'Không phạm') . ' giờ Quan sát'];

        // 4. Kiểm tra Diêm Vương
        $phamDiemVuong = (isset($data['diem_vuong'][$lunarMonth]) && $data['diem_vuong'][$lunarMonth] === $chiGio);
        $analysis['diem_vuong'] = ['pham' => $phamDiemVuong, 'ket_luan' => ($phamDiemVuong ? 'Phạm' : 'Không phạm') . ' giờ Diêm Vương'];

        // 5. Kiểm tra Tướng Quân
        $phamTuongQuan = (isset($data['tuong_quan'][$lunarMonth]) && $data['tuong_quan'][$lunarMonth] === $chiGio);
        $analysis['tuong_quan'] = ['pham' => $phamTuongQuan, 'ket_luan' => ($phamTuongQuan ? 'Phạm' : 'Không phạm') . ' giờ Tướng quân'];

        // Tổng hợp kết quả để trả về
        return [
            'duong_lich' => "{$day}/{$month}/{$year}",
            'am_lich_full' => "Tức: {$lunarDay}/{$lunarMonth}/{$lunarYear}" . ($isLeapMonth ? " (Nhuận)" : "") . " Âm lịch, ngày {$canNgay} {$chiNgay}, tháng {$canThang} {$chiThang}, năm {$canNam} {$chiNamSinh}",
            'thuoc_mua' => "Thuộc mùa: Mùa " . ($data['mua'][$lunarMonth] ?? 'Không xác định'),
            'gio_sinh' => "Giờ sinh: {$chiGio}; Giới tính: {$gioiTinh}",
            'phan_tich_pham' => $analysis
        ];
    }
}