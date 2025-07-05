<?php

namespace App\Helpers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

//Đánh giá tổng quan ngày trong tổng quan ngày trong chi tiết ngày
class FengShuiHelper //cần xác định xem gia chủ thuộc Tây Tứ Mệnh hay Đông Tứ Mệnh trước
{


    /**
     * Hàm chính: Lấy thông tin chi tiết về hướng đặt ban thờ cho gia chủ.
     *
     * @param int $namSinh Năm sinh dương lịch (ví dụ: 2000)
     * @param string $thangSinh Tháng sinh dương lịch
     * @param string $ngaySinh Ngày sinh dương lịch
     * @param string $gioiTinh Giới tính ('nam' hoặc 'nữ')
     * @return array|null Mảng kết quả hoặc null nếu không hợp lệ.
     */
    public static function layHuongBanTho(int $namSinh, int $thangSinh, int $ngaySinh, string $gioiTinh): ?array
    {
        $phongThuyCoBan = self::tinhHuongHopTuoi($namSinh, $gioiTinh);

        if ($phongThuyCoBan === null) {
            return null;
        }

        $cungMenh = $phongThuyCoBan['menh_trach'];
        $huongBanThoTotNhat = self::getBangHuongBanTho()[$cungMenh] ?? [];

        // --- Bổ sung thông tin Âm lịch ---
        // Chuyển đổi ngày sinh Dương sang Âm
        $lunarDob = LunarHelper::convertSolar2Lunar($ngaySinh, $thangSinh, $namSinh);
        $lunarDay = $lunarDob[0];
        $lunarMonth = $lunarDob[1];
        $lunarYear = $lunarDob[2]; // Năm âm lịch

        // Lấy Can Chi của năm sinh Âm lịch
        $canChiNamSinh = KhiVanHelper::canchiNam($lunarYear);

        $ketQua = [
            'basicInfo' => [
                // Thông tin gốc
                'ngaySinhDuongLich' => sprintf('%02d/%02d/%d', $ngaySinh, $thangSinh, $namSinh),
                'gioiTinh' => ucfirst(strtolower($gioiTinh)),
                'menhQuai' => $phongThuyCoBan['menh_trach'] . ' - hành ' . $phongThuyCoBan['ngu_hanh'],
                'thuocNhom' => $phongThuyCoBan['nhom'],

                // --- THÔNG TIN MỚI THÊM ---
                'ngaySinhAmLich' => sprintf('%02d/%02d/%d (%s)', $lunarDay, $lunarMonth, $lunarYear, $canChiNamSinh),
            ],
            'nguyenTacDatBanTho' => [
                'Bàn thờ nên đặt tại vị trí cát (trong nhà) và quay mặt về hướng cát.',
                'Đặc biệt, hướng nhìn ra (mặt bàn thờ) là yếu tố quan trọng nhất.',
            ],
            'huongDatBanThoTotNhat' => $huongBanThoTotNhat,

        ];

        return $ketQua;
    }

    /**
     * Hàm lõi: Tính toán hướng hợp tuổi, Mệnh Trạch, và các thông tin phong thủy khác
     * dựa trên năm sinh và giới tính theo Bát Trạch.
     *
     * @param int $namSinh Năm sinh (ví dụ: 1987, 2005)
     * @param string $gioiTinh Giới tính ('nam' hoặc 'nữ')
     * @return array|null Trả về một mảng chứa thông tin chi tiết hoặc null nếu đầu vào không hợp lệ.
     */
    public static function tinhHuongHopTuoi(int $namSinh, string $gioiTinh): ?array
    {
        // Chuẩn hóa đầu vào
        $gioiTinh = strtolower($gioiTinh);
        if (!in_array($gioiTinh, ['nam', 'nữ']) || $namSinh <= 1900 || $namSinh > 2100) {
            return null;
        }

        // --- Bảng tra cứu Quái số ---
        // Bảng này chứa thông tin gốc của Bát Trạch
        $bangTraCuu = [
            1 => ['menh_trach' => 'Khảm', 'nhom' => 'Đông Tứ Mệnh', 'ngu_hanh' => 'Thủy', 'phuong_vi' => 'Bắc', 'huong_tot' => ['sinh_khi' => 'Đông Nam', 'thien_y' => 'Đông', 'phuoc_duc' => 'Nam', 'phuc_vi' => 'Bắc'], 'huong_xau' => ['tuyet_menh' => 'Tây Nam', 'ngu_quy' => 'Đông Bắc', 'luc_sat' => 'Tây Bắc', 'hoa_hai' => 'Tây']],
            2 => ['menh_trach' => 'Khôn', 'nhom' => 'Tây Tứ Mệnh', 'ngu_hanh' => 'Thổ', 'phuong_vi' => 'Tây Nam', 'huong_tot' => ['sinh_khi' => 'Đông Bắc', 'thien_y' => 'Tây', 'phuoc_duc' => 'Tây Bắc', 'phuc_vi' => 'Tây Nam'], 'huong_xau' => ['tuyet_menh' => 'Bắc', 'ngu_quy' => 'Đông Nam', 'luc_sat' => 'Nam', 'hoa_hai' => 'Đông']],
            3 => ['menh_trach' => 'Chấn', 'nhom' => 'Đông Tứ Mệnh', 'ngu_hanh' => 'Mộc', 'phuong_vi' => 'Đông', 'huong_tot' => ['sinh_khi' => 'Nam', 'thien_y' => 'Bắc', 'phuoc_duc' => 'Đông Nam', 'phuc_vi' => 'Đông'], 'huong_xau' => ['tuyet_menh' => 'Tây', 'ngu_quy' => 'Tây Bắc', 'luc_sat' => 'Đông Bắc', 'hoa_hai' => 'Tây Nam']],
            4 => ['menh_trach' => 'Tốn', 'nhom' => 'Đông Tứ Mệnh', 'ngu_hanh' => 'Mộc', 'phuong_vi' => 'Đông Nam', 'huong_tot' => ['sinh_khi' => 'Bắc', 'thien_y' => 'Nam', 'phuoc_duc' => 'Đông', 'phuc_vi' => 'Đông Nam'], 'huong_xau' => ['tuyet_menh' => 'Đông Bắc', 'ngu_quy' => 'Tây Nam', 'luc_sat' => 'Tây', 'hoa_hai' => 'Tây Bắc']],
            6 => ['menh_trach' => 'Càn', 'nhom' => 'Tây Tứ Mệnh', 'ngu_hanh' => 'Kim', 'phuong_vi' => 'Tây Bắc', 'huong_tot' => ['sinh_khi' => 'Tây', 'thien_y' => 'Đông Bắc', 'phuoc_duc' => 'Tây Nam', 'phuc_vi' => 'Tây Bắc'], 'huong_xau' => ['tuyet_menh' => 'Nam', 'ngu_quy' => 'Đông', 'luc_sat' => 'Bắc', 'hoa_hai' => 'Đông Nam']],
            7 => ['menh_trach' => 'Đoài', 'nhom' => 'Tây Tứ Mệnh', 'ngu_hanh' => 'Kim', 'phuong_vi' => 'Tây', 'huong_tot' => ['sinh_khi' => 'Tây Bắc', 'thien_y' => 'Tây Nam', 'phuoc_duc' => 'Đông Bắc', 'phuc_vi' => 'Tây'], 'huong_xau' => ['tuyet_menh' => 'Đông', 'ngu_quy' => 'Nam', 'luc_sat' => 'Đông Nam', 'hoa_hai' => 'Bắc']],
            8 => ['menh_trach' => 'Cấn', 'nhom' => 'Tây Tứ Mệnh', 'ngu_hanh' => 'Thổ', 'phuong_vi' => 'Đông Bắc', 'huong_tot' => ['sinh_khi' => 'Tây Nam', 'thien_y' => 'Tây Bắc', 'phuoc_duc' => 'Tây', 'phuc_vi' => 'Đông Bắc'], 'huong_xau' => ['tuyet_menh' => 'Đông Nam', 'ngu_quy' => 'Bắc', 'luc_sat' => 'Đông', 'hoa_hai' => 'Nam']],
            9 => ['menh_trach' => 'Ly', 'nhom' => 'Đông Tứ Mệnh', 'ngu_hanh' => 'Hỏa', 'phuong_vi' => 'Nam', 'huong_tot' => ['sinh_khi' => 'Đông', 'thien_y' => 'Đông Nam', 'phuoc_duc' => 'Bắc', 'phuc_vi' => 'Nam'], 'huong_xau' => ['tuyet_menh' => 'Tây Bắc', 'ngu_quy' => 'Tây', 'luc_sat' => 'Tây Nam', 'hoa_hai' => 'Đông Bắc']],
        ];

        // --- Bước 2: Tính toán Quái số ---
        $tongSo = array_sum(str_split((string) $namSinh));
        while ($tongSo >= 10) {
            $tongSo = array_sum(str_split((string) $tongSo));
        }

        $quaiSo = 0;
        if ($gioiTinh == 'nam') {
            $quaiSo = 11 - $tongSo;
        } else { // Nữ
            $quaiSo = 4 + $tongSo;
        }

        // Rút gọn quái số nếu lớn hơn 9
        if ($quaiSo > 9) {
            $quaiSo %= 9;
        }
        if ($quaiSo == 0) $quaiSo = 9;

        // Xử lý trường hợp đặc biệt Quái số = 5
        if ($quaiSo == 5) {
            if ($gioiTinh == 'nam') {
                $quaiSo = 2; // Nam là Khôn
            } else {
                $quaiSo = 8; // Nữ là Cấn
            }
        }

        // --- Bước 3: Tra cứu và trả về kết quả ---
        if (!isset($bangTraCuu[$quaiSo])) {
            return null; // Không tìm thấy quái số hợp lệ
        }

        $ketQua = $bangTraCuu[$quaiSo];
        $ketQua['quai_so'] = $quaiSo;
        $ketQua['cung_menh'] = $ketQua['menh_trach'];

        return $ketQua;
    }

    /**
     * Hàm private: Cung cấp dữ liệu các hướng tốt cho ban thờ.
     * Dữ liệu đã được sắp xếp ưu tiên và gán ý nghĩa phù hợp cho việc thờ cúng.
     *
     * @return array
     */
    private static function getBangHuongBanTho(): array
    {
        return [
            // --- TÂY TỨ MỆNH ---
            'Khôn' => [
                ['huong' => 'Tây Nam (Phục Vị)', 'y_nghia' => 'Được tổ tiên phù trì, vượng khí và tài lộc.', 'uu_tien' => 'Ưu tiên 1'],
                ['huong' => 'Tây (Thiên Y)', 'y_nghia' => 'Công việc thuận lợi, hanh thông, cuộc sống tốt đẹp.', 'uu_tien' => 'Ưu tiên 2'], // Tên đã được tùy chỉnh cho giống ảnh
                ['huong' => 'Tây Bắc (Phước Đức)', 'y_nghia' => 'Sức khỏe dồi dào, tránh ốm đau, gặp nhiều may mắn và quý nhân phù trợ.', 'uu_tien' => 'Ưu tiên 3'], // Tên đã được tùy chỉnh
                ['huong' => 'Đông Bắc (Sinh Khí)', 'y_nghia' => 'Tình cảm vợ chồng, cha mẹ, con cái trở nên khăng khít và tốt đẹp.', 'uu_tien' => 'Ưu tiên 4'], // Tên đã được tùy chỉnh
            ],
            'Càn' => [
                ['huong' => 'Tây Bắc (Phục Vị)', 'y_nghia' => 'Được tổ tiên phù trì, vượng khí và tài lộc.', 'uu_tien' => 'Ưu tiên 1'],
                ['huong' => 'Đông Bắc (Thiên Y)', 'y_nghia' => 'Sức khỏe dồi dào, gặp nhiều may mắn, quý nhân phù trợ.', 'uu_tien' => 'Ưu tiên 2'],
                ['huong' => 'Tây Nam (Phước Đức)', 'y_nghia' => 'Gia đình hòa thuận, các mối quan hệ tốt đẹp.', 'uu_tien' => 'Ưu tiên 3'],
                ['huong' => 'Tây (Sinh Khí)', 'y_nghia' => 'Thu hút tài lộc, công danh sự nghiệp phát triển.', 'uu_tien' => 'Ưu tiên 4'],
            ],
            'Cấn' => [
                ['huong' => 'Đông Bắc (Phục Vị)', 'y_nghia' => 'Được tổ tiên phù trì, vượng khí và tài lộc.', 'uu_tien' => 'Ưu tiên 1'],
                ['huong' => 'Tây (Thiên Y)', 'y_nghia' => 'Sức khỏe dồi dào, gặp nhiều may mắn, quý nhân phù trợ.', 'uu_tien' => 'Ưu tiên 2'],
                ['huong' => 'Tây Bắc (Phước Đức)', 'y_nghia' => 'Gia đình hòa thuận, các mối quan hệ tốt đẹp.', 'uu_tien' => 'Ưu tiên 3'],
                ['huong' => 'Tây Nam (Sinh Khí)', 'y_nghia' => 'Thu hút tài lộc, công danh sự nghiệp phát triển.', 'uu_tien' => 'Ưu tiên 4'],
            ],
            'Đoài' => [
                ['huong' => 'Tây (Phục Vị)', 'y_nghia' => 'Được tổ tiên phù trì, vượng khí và tài lộc.', 'uu_tien' => 'Ưu tiên 1'],
                ['huong' => 'Tây Nam (Thiên Y)', 'y_nghia' => 'Sức khỏe dồi dào, gặp nhiều may mắn, quý nhân phù trợ.', 'uu_tien' => 'Ưu tiên 2'],
                ['huong' => 'Đông Bắc (Phước Đức)', 'y_nghia' => 'Gia đình hòa thuận, các mối quan hệ tốt đẹp.', 'uu_tien' => 'Ưu tiên 3'],
                ['huong' => 'Tây Bắc (Sinh Khí)', 'y_nghia' => 'Thu hút tài lộc, công danh sự nghiệp phát triển.', 'uu_tien' => 'Ưu tiên 4'],
            ],
            // --- ĐÔNG TỨ MỆNH ---
            'Khảm' => [
                ['huong' => 'Bắc (Phục Vị)', 'y_nghia' => 'Được tổ tiên phù trì, vượng khí và tài lộc.', 'uu_tien' => 'Ưu tiên 1'],
                ['huong' => 'Đông (Thiên Y)', 'y_nghia' => 'Sức khỏe dồi dào, gặp nhiều may mắn, quý nhân phù trợ.', 'uu_tien' => 'Ưu tiên 2'],
                ['huong' => 'Nam (Phước Đức)', 'y_nghia' => 'Gia đình hòa thuận, các mối quan hệ tốt đẹp.', 'uu_tien' => 'Ưu tiên 3'],
                ['huong' => 'Đông Nam (Sinh Khí)', 'y_nghia' => 'Thu hút tài lộc, công danh sự nghiệp phát triển.', 'uu_tien' => 'Ưu tiên 4'],
            ],
            'Ly' => [
                ['huong' => 'Nam (Phục Vị)', 'y_nghia' => 'Được tổ tiên phù trì, vượng khí và tài lộc.', 'uu_tien' => 'Ưu tiên 1'],
                ['huong' => 'Đông Nam (Thiên Y)', 'y_nghia' => 'Sức khỏe dồi dào, gặp nhiều may mắn, quý nhân phù trợ.', 'uu_tien' => 'Ưu tiên 2'],
                ['huong' => 'Bắc (Phước Đức)', 'y_nghia' => 'Gia đình hòa thuận, các mối quan hệ tốt đẹp.', 'uu_tien' => 'Ưu tiên 3'],
                ['huong' => 'Đông (Sinh Khí)', 'y_nghia' => 'Thu hút tài lộc, công danh sự nghiệp phát triển.', 'uu_tien' => 'Ưu tiên 4'],
            ],
            'Chấn' => [
                ['huong' => 'Đông (Phục Vị)', 'y_nghia' => 'Được tổ tiên phù trì, vượng khí và tài lộc.', 'uu_tien' => 'Ưu tiên 1'],
                ['huong' => 'Bắc (Thiên Y)', 'y_nghia' => 'Sức khỏe dồi dào, gặp nhiều may mắn, quý nhân phù trợ.', 'uu_tien' => 'Ưu tiên 2'],
                ['huong' => 'Đông Nam (Phước Đức)', 'y_nghia' => 'Gia đình hòa thuận, các mối quan hệ tốt đẹp.', 'uu_tien' => 'Ưu tiên 3'],
                ['huong' => 'Nam (Sinh Khí)', 'y_nghia' => 'Thu hút tài lộc, công danh sự nghiệp phát triển.', 'uu_tien' => 'Ưu tiên 4'],
            ],
            'Tốn' => [
                ['huong' => 'Đông Nam (Phục Vị)', 'y_nghia' => 'Được tổ tiên phù trì, vượng khí và tài lộc.', 'uu_tien' => 'Ưu tiên 1'],
                ['huong' => 'Nam (Thiên Y)', 'y_nghia' => 'Sức khỏe dồi dào, gặp nhiều may mắn, quý nhân phù trợ.', 'uu_tien' => 'Ưu tiên 2'],
                ['huong' => 'Đông (Phước Đức)', 'y_nghia' => 'Gia đình hòa thuận, các mối quan hệ tốt đẹp.', 'uu_tien' => 'Ưu tiên 3'],
                ['huong' => 'Bắc (Sinh Khí)', 'y_nghia' => 'Thu hút tài lộc, công danh sự nghiệp phát triển.', 'uu_tien' => 'Ưu tiên 4'],
            ],
        ];
    }

    /**
     * Lấy thông tin về hướng nhà hợp tuổi với cấu trúc dữ liệu mới.
     *
     * @param int $namSinh Năm sinh dương lịch
     * @param int $thangSinh Tháng sinh dương lịch
     * @param int $ngaySinh Ngày sinh dương lịch
     * @param string $gioiTinh Giới tính ('nam' hoặc 'nữ')
     * @return array|null Trả về mảng kết quả hoặc null nếu không hợp lệ.
     */
    public static function layHuongNha(int $namSinh, int $thangSinh, int $ngaySinh, string $gioiTinh): ?array
    {
        // 1. Lấy thông tin phong thủy Bát Trạch cơ bản
        $phongThuyCoBan = self::tinhHuongHopTuoi($namSinh, $gioiTinh);

        if ($phongThuyCoBan === null) {
            return null;
        }

        // --- Bắt đầu xây dựng cấu trúc dữ liệu mới theo yêu cầu ---

        // 2. Chuẩn bị dữ liệu cho mục "Thông tin cơ bản"
        $lunarDob = LunarHelper::convertSolar2Lunar($ngaySinh, $thangSinh, $namSinh);
        $canChiNamSinh = KhiVanHelper::canchiNam($lunarDob[2]); // Lấy Can Chi của năm Âm lịch

        $basicInfo = [
            'ngaySinhDuongLich' => sprintf('%02d/%02d/%d', $ngaySinh, $thangSinh, $namSinh),
            'ngaySinhAmLich' => sprintf('%02d/%02d/%d (%s)', $lunarDob[0], $lunarDob[1], $lunarDob[2], $canChiNamSinh),
            'gioiTinh' => ucfirst(strtolower($gioiTinh)),
            'menhQuai' => $phongThuyCoBan['menh_trach'] . ' - hành ' . $phongThuyCoBan['ngu_hanh'],
            'thuocNhom' => $phongThuyCoBan['nhom'],
        ];

        // 3. Chuẩn bị dữ liệu cho mục "Nguyên tắc chọn hướng nhà"
        $nguyenTac = [
            'huongCat' => ['Sinh Khí', 'Thiên Y', 'Phước Đức', 'Phục Vị'], // Tên các loại hướng tốt
            'huongHung' => ['Ngũ Quỷ', 'Lục Sát', 'Họa Hại', 'Tuyệt Mạng'] // Tên các loại hướng xấu
        ];

        // 4. Lấy bảng phân tích chi tiết các hướng tốt/xấu (để sử dụng nếu cần)
        $huongTotGoc = $phongThuyCoBan['huong_tot'];
        $huongNhaTotChiTiet = self::getHuongTot($huongTotGoc);
        $huongNhaXauChiTiet = $phongThuyCoBan['huong_xau'];


        // 5. Kết hợp tất cả lại thành kết quả cuối cùng
        return [
            'basicInfo' => $basicInfo,
            'nguyenTac' => $nguyenTac,
            'huongNhaTotChiTiet' => $huongNhaTotChiTiet, // Bảng chi tiết hướng tốt, rất hữu ích để hiển thị thêm
            'huongNhaXauChiTiet' => $huongNhaXauChiTiet, // Bảng chi tiết hướng xấu
        ];
    }
    /**
     * Hàm private: Cung cấp ý nghĩa các hướng tốt cho mục đích xây nhà.
     * Sắp xếp theo thứ tự ưu tiên: Sinh Khí > Thiên Y > Diên Niên > Phục Vị.
     *
     * @param array $huongTotGoc Mảng các hướng tốt từ hàm tinhHuongHopTuoi
     * @return array
     */
    private static function getHuongTot(array $huongTotGoc): array
    {
        $yNghia = [
            'sinh_khi' => 'Vượng nhất, hút tiền tài, sự nghiệp phát triển mạnh',
            'thien_y' => 'Cát lợi về sức khỏe, gặp nhiều may mắn.',
            'phuoc_duc' => 'Gia đạo tốt, hôn nhân bền vững, quan hệ tốt.', // phuoc_duc là Diên Niên
            'phuc_vi' => 'Tốt cho tĩnh tại, nội tâm, phù hợp nơi thờ cúng.'
        ];

        return [
            ['Huong' => $huongTotGoc['sinh_khi'], 'Loai' => 'Sinh Khí', 'Y nghia' => $yNghia['sinh_khi'], 'Uu tien' => 'Ưu tiên 1'],
            ['Huong' => $huongTotGoc['thien_y'], 'Loai' => 'Thiên Y', 'Y nghia' => $yNghia['thien_y'], 'Uu tien' => 'Ưu tiên 2'],
            ['Huong' => $huongTotGoc['phuoc_duc'], 'Loai' => 'Diên Niên', 'Y nghia' => $yNghia['phuoc_duc'], 'Uu tien' => 'Ưu tiên 3'],
            ['Huong' => $huongTotGoc['phuc_vi'], 'Loai' => 'Phục Vị', 'Y nghia' => $yNghia['phuc_vi'], 'Uu tien' => 'Ưu tiên 4'],
        ];
    }


    /**
     * HÀM ĐƯỢC VIẾT LẠI: Lấy thông tin về hướng bếp hợp tuổi theo cách tiếp cận đơn giản hơn.
     * Tập trung vào việc chọn "hướng cát" để bếp quay về.
     *
     * @param int $namSinh Năm sinh dương lịch
     * @param int $thangSinh Tháng sinh dương lịch
     * @param int $ngaySinh Ngày sinh dương lịch
     * @param string $gioiTinh Giới tính ('nam' hoặc 'nữ')
     * @return array|null
     */
    public static function layHuongBep(int $namSinh, int $thangSinh, int $ngaySinh, string $gioiTinh): ?array
    {
        // 1. Tái sử dụng hàm gốc để lấy dữ liệu Bát Trạch
        $phongThuyCoBan = self::tinhHuongHopTuoi($namSinh, $gioiTinh);

        if ($phongThuyCoBan === null) {
            return null;
        }

        // 2. Chuẩn bị dữ liệu cho mục "Thông tin cơ bản"
        $lunarDob = LunarHelper::convertSolar2Lunar($ngaySinh, $thangSinh, $namSinh);
        $canChiNamSinh = KhiVanHelper::canchiNam($lunarDob[2]);

        $basicInfo = [
            'ngaySinhDuongLich' => sprintf('%02d/%02d/%d', $ngaySinh, $thangSinh, $namSinh),
            'ngaySinhAmLich' => sprintf('%02d/%02d/%d (%s)', $lunarDob[0], $lunarDob[1], $lunarDob[2], $canChiNamSinh),
            'gioiTinh' => ucfirst(strtolower($gioiTinh)),
            'menhQuai' => $phongThuyCoBan['menh_trach'] . ' - hành ' . $phongThuyCoBan['ngu_hanh'],
            'thuocNhom' => $phongThuyCoBan['nhom'],
        ];

        // 3. Chuẩn bị dữ liệu cho mục "Nguyên tắc chọn hướng bếp"
        $nguyenTac = [
            'title' => 'Tọa hung - hướng cát',
            'rules' => [
                'Bếp nên tọa ở vị trí hướng xấu (hung) để trấn áp tà khí',
                'Miệng bếp (hướng lửa) nên quay về hướng tốt (cát) để thu nạp khí lành',
                'Nếu không chọn được tọa hung, thì ít nhất phải quay về hướng tốt là đã đạt 80% phong thủy.',
            ],
            'note' => 'Hướng bếp là hướng ngược với người đứng nấu (tức là hướng lưng của người nấu).'
        ];

        // 4. Lấy bảng các hướng tốt nhất để bếp quay về
        // Tái sử dụng hàm getHuongTot đã có, vì logic ưu tiên (Sinh Khí > Thiên Y...) là giống nhau
        $huongBepTotNhat = self::getHuongTot($phongThuyCoBan['huong_tot']);

        // 5. Kết hợp tất cả lại
        return [
            'basicInfo' => $basicInfo,
            'nguyenTac' => $nguyenTac,
            'huongBepTotNhat' => $huongBepTotNhat,
        ];
    }





    /**
     * HÀM MỚI: Lấy thông tin về hướng phòng ngủ và giường ngủ hợp tuổi.
     *
     * @param int $namSinh Năm sinh dương lịch
     * @param string $gioiTinh Giới tính ('nam' hoặc 'nữ')
     * @return array|null
     */
    public static function layHuongPhongNgu(int $namSinh, int $thangSinh, int $ngaySinh, string $gioiTinh): ?array
    {
        // 1. Tái sử dụng hàm gốc để lấy dữ liệu Bát Trạch
        $phongThuyCoBan = self::tinhHuongHopTuoi($namSinh, $gioiTinh);

        if ($phongThuyCoBan === null) {
            return null;
        }
        // 2. Chuẩn bị dữ liệu cho mục "Thông tin cơ bản"
        $lunarDob = LunarHelper::convertSolar2Lunar($ngaySinh, $thangSinh, $namSinh);
        $canChiNamSinh = KhiVanHelper::canchiNam($lunarDob[2]);
        // 2. Lấy danh sách hướng tốt gốc
        $huongTotGoc = $phongThuyCoBan['huong_tot'];

        // 3. Sắp xếp và diễn giải lại các hướng theo mục đích PHÒNG NGỦ
        $huongPhongNguChiTiet = self::getBangHuongPhongNgu($huongTotGoc);

        // 4. Định dạng kết quả trả về
        return [
            'basicInfo' => [
                'ngaySinhDuongLich' => sprintf('%02d/%02d/%d', $ngaySinh, $thangSinh, $namSinh),
                'ngaySinhAmLich' => sprintf('%02d/%02d/%d (%s)', $lunarDob[0], $lunarDob[1], $lunarDob[2], $canChiNamSinh),
                'gioiTinh' => ucfirst(strtolower($gioiTinh)),
                'menhQuai' => $phongThuyCoBan['menh_trach'] . ' - hành ' . $phongThuyCoBan['ngu_hanh'],
                'thuocNhom' => $phongThuyCoBan['nhom'],
            ],
            'huongTotChiTiet' => $huongPhongNguChiTiet,
            'huongXauChiTiet' => $phongThuyCoBan['huong_xau'],
            'nguyenTac' => [
                'Hướng đầu giường (hoặc hướng nhìn ra từ giường) nên quay về các hướng cát: Thiên Y, Phước Đức, Sinh Khí, Phục Vị'
            ]
        ];
    }

    /**
     * Hàm private: Cung cấp ý nghĩa các hướng tốt cho mục đích PHÒNG NGỦ.
     * Ưu tiên hàng đầu là Thiên Y (sức khỏe) và Diên Niên (tình cảm).
     *
     * @param array $huongTotGoc Mảng các hướng tốt từ hàm tinhHuongHopTuoi
     * @return array
     */
    private static function getBangHuongPhongNgu(array $huongTotGoc): array
    {
        $yNghia = [
            'thien_y' => 'Cát lợi về sức khỏe, gặp nhiều may mắn',
            'phuoc_duc' => 'Gia đạo tốt, hôn nhân bền vững, quan hệ tốt', // Diên Niên
            'phuc_vi' => 'Tốt cho tĩnh tại, nội tâm, phù hợp nơi thờ cúng',
            'sinh_khi' => 'Hút tiền tài, sự nghiệp phát triển mạnh'
        ];

        // Sắp xếp lại theo thứ tự ưu tiên cho phòng ngủ
        return [
            ['Huong' => $huongTotGoc['thien_y'], 'Loai' => 'Thiên Y', 'Y_nghia' => $yNghia['thien_y'], 'Uu_tien' => 'Ưu tiên 1'],
            ['Huong' => $huongTotGoc['phuoc_duc'], 'Loai' => 'Diên Niên', 'Y_nghia' => $yNghia['phuoc_duc'], 'Uu_tien' => 'Ưu tiên 2'],
            ['Huong' => $huongTotGoc['phuc_vi'], 'Loai' => 'Phục Vị', 'Y_nghia' => $yNghia['phuc_vi'], 'Uu_tien' => 'Ưu tiên 3'],
            ['Huong' => $huongTotGoc['sinh_khi'], 'Loai' => 'Sinh Khí', 'Y_nghia' => $yNghia['sinh_khi'], 'Uu_tien' => 'Ưu tiên 4'],
        ];
    }






    /**
     * HÀM MỚI: Lấy thông tin về hướng bàn làm việc hợp tuổi.
     *
     * @param int $namSinh Năm sinh dương lịch
     * @param string $gioiTinh Giới tính ('nam' hoặc 'nữ')
     * @return array|null
     */
    public static function layHuongBanLamViec(int $namSinh, int $thangSinh, int $ngaySinh, string $gioiTinh): ?array
    {
        // 1. Tái sử dụng hàm gốc để lấy dữ liệu Bát Trạch
        $phongThuyCoBan = self::tinhHuongHopTuoi($namSinh, $gioiTinh);

        if ($phongThuyCoBan === null) {
            return null;
        }

        // 2. Lấy danh sách hướng tốt gốc
        // $huongTotGoc = $phongThuyCoBan['huong_tot'];

        // // 3. Sắp xếp và diễn giải lại các hướng theo mục đích BÀN LÀM VIỆC
        // $huongBanLamViecChiTiet = self::getBangHuongBanLamViec($huongTotGoc);

        // 2. Chuẩn bị dữ liệu cho mục "Thông tin cơ bản"
        $lunarDob = LunarHelper::convertSolar2Lunar($ngaySinh, $thangSinh, $namSinh);
        $canChiNamSinh = KhiVanHelper::canchiNam($lunarDob[2]);
        // 2. Lấy danh sách hướng tốt gốc
        $huongTotGoc = $phongThuyCoBan['huong_tot'];

        // 3. Sắp xếp và diễn giải lại các hướng theo mục đích PHÒNG NGỦ
        $huongBanLamViecChiTiet = self::getBangHuongBanLamViec($huongTotGoc);

        // 4. Định dạng kết quả trả về
        return [
            'basicInfo' => [
                'ngaySinhDuongLich' => sprintf('%02d/%02d/%d', $ngaySinh, $thangSinh, $namSinh),
                'ngaySinhAmLich' => sprintf('%02d/%02d/%d (%s)', $lunarDob[0], $lunarDob[1], $lunarDob[2], $canChiNamSinh),
                'gioiTinh' => ucfirst(strtolower($gioiTinh)),
                'menhQuai' => $phongThuyCoBan['menh_trach'] . ' - hành ' . $phongThuyCoBan['ngu_hanh'],
                'thuocNhom' => $phongThuyCoBan['nhom'],
            ],
            'huongTotChiTiet' => $huongBanLamViecChiTiet,
            'huongXauChiTiet' => $phongThuyCoBan['huong_xau'],
            'nguyenTac' => [
                '<ul><li>Tọa hung - hướng cát: Quay mặt về hướng tốt (hướng cát) để nạp sinh khí. Lưng ghế tọa hướng xấu (hung)</li><li>Nếu không chọn được tọa hung, thì ít nhất phải quay về hướng tốt là đã đạt 80% phong thủy</li> </ul>'
            ]
        ];
    }

    /**
     * Hàm private: Cung cấp ý nghĩa các hướng tốt cho mục đích đặt BÀN LÀM VIỆC.
     * Ưu tiên hàng đầu là Sinh Khí để thúc đẩy sự nghiệp.
     *
     * @param array $huongTotGoc Mảng các hướng tốt từ hàm tinhHuongHopTuoi
     * @return array
     */
    private static function getBangHuongBanLamViec(array $huongTotGoc): array
    {
        $yNghia = [
            'sinh_khi' => 'Giúp bạn tăng cường năng lượng, thu hút tài lộc, danh tiếng và thăng tiến trong sự nghiệp.',
            'thien_y' => 'Cải thiện sức khỏe để làm việc hiệu quả, được quý nhân (cấp trên, đồng nghiệp) giúp đỡ.',
            'phuoc_duc' => 'Củng cố các mối quan hệ với đồng nghiệp, đối tác, khách hàng, tạo môi trường làm việc hòa thuận.', // phuoc_duc là Diên Niên
            'phuc_vi' => 'Tăng cường sự tập trung, củng cố sức mạnh tinh thần, giúp công việc ổn định, vững chắc.'
        ];

        // Sắp xếp lại theo thứ tự ưu tiên cho công việc
        return [
            ['Huong' => $huongTotGoc['sinh_khi'], 'Loai' => 'Sinh Khí', 'Y_nghia' => $yNghia['sinh_khi'], 'Uu_tien' => 'Ưu tiên 1'],
            ['Huong' => $huongTotGoc['thien_y'], 'Loai' => 'Thiên Y', 'Y_nghia' => $yNghia['thien_y'], 'Uu_tien' => 'Ưu tiên 2'],
            ['Huong' => $huongTotGoc['phuoc_duc'], 'Loai' => 'Diên Niên', 'Y_nghia' => $yNghia['phuoc_duc'], 'Uu_tien' => 'Ưu tiên 3'],
            ['Huong' => $huongTotGoc['phuc_vi'], 'Loai' => 'Phục Vị', 'Y_nghia' => $yNghia['phuc_vi'], 'Uu_tien' => 'Ưu tiên 4'],
        ];
    }
}
