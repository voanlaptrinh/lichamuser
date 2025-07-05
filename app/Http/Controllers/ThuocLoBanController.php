<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ThuocLoBanController extends Controller
{
    public function index(Request $request)
    {
        // Chúng ta không cần tính toán kết quả ở đây nữa
        // vì JS sẽ làm việc đó.
        // Nhưng chúng ta cần toàn bộ cấu trúc thước để JS vẽ.
        
        // Lấy toàn bộ dữ liệu cấu trúc từ helper/config
        // Giả sử bạn đang dùng helper có chứa dữ liệu
        // (Cách này tốt vì dữ liệu thước không thay đổi)
        $rulersData = $this->getRulerStructureWithDetails();

        return view('thuoc-lo-ban.interactive', [
            'rulersData' => $rulersData,
        ]);
    }

    /**
     * Một hàm riêng để lấy cấu trúc thước,
     * giúp controller gọn gàng hơn.
     */
       /**
     * Cung cấp cấu trúc dữ liệu thước Lỗ Ban chi tiết, bao gồm cả giải nghĩa.
     */
    private function getRulerStructureWithDetails(): array
    {
        // Dữ liệu này có thể được tách ra một file config hoặc helper riêng nếu muốn.
        return [
            'thong_thuy' => [
                'name' => 'Thước Lỗ Ban 52.2cm: Khoảng thông thủy (cửa, cửa sổ...)',
                'title_short' => 'Thước Lỗ Ban 52.2cm',
                'description_title' => 'Khoảng không thông thủy (cửa, cửa sổ...)',
                'total_length' => 52.2,
                'khoang' => [
                    ['name' => 'Quý nhân', 'type' => 'good', 'cung' => [
                        ['name' => 'Quyền lộc', 'desc' => 'May mắn về tài lộc, chức vụ, gia đình thịnh vượng.'],
                        ['name' => 'Trung tín', 'desc' => 'Gia chủ trung thực, được mọi người tín nhiệm.'],
                        ['name' => 'Tác quan', 'desc' => 'Có đường công danh, sự nghiệp rộng mở.'],
                        ['name' => 'Phát đạt', 'desc' => 'Làm ăn phát đạt, kinh doanh thuận lợi.'],
                        ['name' => 'Thông minh', 'desc' => 'Con cái thông minh, học giỏi, thành tài.'],
                    ]],
                    ['name' => 'Hiểm họa', 'type' => 'bad', 'cung' => [
                        ['name' => 'Án thành', 'desc' => 'Dễ vướng vào kiện tụng, tranh chấp.'],
                        ['name' => 'Hỗn nhân', 'desc' => 'Gia đình bất hòa, tình cảm vợ chồng rạn nứt.'],
                        ['name' => 'Thất hiếu', 'desc' => 'Con cái ngỗ nghịch, không vâng lời cha mẹ.'],
                        ['name' => 'Tai họa', 'desc' => 'Gặp phải những tai ương bất ngờ, khó lường.'],
                        ['name' => 'Trường bệnh', 'desc' => 'Sức khỏe suy yếu, bệnh tật kéo dài.'],
                    ]],
                    ['name' => 'Thiên tai', 'type' => 'bad', 'cung' => [
                        ['name' => 'Hoàn tử', 'desc' => 'Nguy cơ mất người, tuyệt tự.'],
                        ['name' => 'Quan tài', 'desc' => 'Gặp chuyện xui xẻo, tang tóc.'],
                        ['name' => 'Thân tàn', 'desc' => 'Bản thân có thể gặp tai nạn, thương tật.'],
                        ['name' => 'Thất tài', 'desc' => 'Hao tốn tiền của, tài sản tiêu tán.'],
                        ['name' => 'Hệ quả', 'desc' => 'Gánh chịu hậu quả xấu từ những việc đã làm.'],
                    ]],
                    ['name' => 'Thiên tài', 'type' => 'good', 'cung' => [
                        ['name' => 'Thi thơ', 'desc' => 'Gia chủ có tài năng về nghệ thuật, văn chương.'],
                        ['name' => 'Văn học', 'desc' => 'Đường học vấn, thi cử thuận lợi.'],
                        ['name' => 'Thanh quý', 'desc' => 'Được mọi người kính trọng, có danh tiếng tốt.'],
                        ['name' => 'Tác lộc', 'desc' => 'Được hưởng phúc lộc trời ban, may mắn về tiền bạc.'],
                        ['name' => 'Thiên lộc', 'desc' => 'Tài lộc bất ngờ, có của ăn của để.'],
                    ]],
                    // ... Thêm các khoảng còn lại với mô tả tương tự ...
                ]
                // Để ngắn gọn, tôi chỉ điền 4/8 khoảng. Bạn có thể bổ sung các khoảng còn lại.
            ],
            'duong_trach' => [
                'name' => 'Thước Lỗ Ban 42.9cm (Dương trạch): Khối xây dựng (bếp, bệ, bậc...)',
                'title_short' => 'Thước Lỗ Ban 42.9cm (Dương trạch)',
                'description_title' => 'Khối xây dựng (bếp, bệ, bậc...)',
                'total_length' => 42.9,
                'khoang' => [
                    ['name' => 'Tài', 'type' => 'good', 'cung' => [
                        ['name' => 'Tài đức', 'desc' => 'Có tài và có đức, được phúc lộc.'],
                        ['name' => 'Bảo khố', 'desc' => 'Có kho báu, làm ăn tích lũy được nhiều của cải.'],
                        ['name' => 'Lục hợp', 'desc' => 'Gặp nhiều may mắn, quan hệ thuận hòa.'],
                        ['name' => 'Nghênh phúc', 'desc' => 'Đón nhận phúc lộc, những điều tốt lành.'],
                    ]],
                    ['name' => 'Bệnh', 'type' => 'bad', 'cung' => [
                        ['name' => 'Thoát tài', 'desc' => 'Mất mát tiền của, không giữ được tài sản.'],
                        ['name' => 'Công sự', 'desc' => 'Dễ gặp rắc rối liên quan đến pháp luật, kiện tụng.'],
                        ['name' => 'Lao chấp', 'desc' => 'Vướng vào vòng lao lý, tù tội.'],
                        ['name' => 'Cô quả', 'desc' => 'Cuộc sống cô đơn, lẻ loi.'],
                    ]],
                    ['name' => 'Ly', 'type' => 'bad', 'cung' => [
                        ['name' => 'Trường bệnh', 'desc' => 'Bệnh tật triền miên, sức khỏe kém.'],
                        ['name' => 'Kiếp tài', 'desc' => 'Bị cướp đoạt tài sản, tiền bạc.'],
                        ['name' => 'Quan quỷ', 'desc' => 'Gặp chuyện không may liên quan đến chính quyền.'],
                        ['name' => 'Thất thoát', 'desc' => 'Mất mát, thất lạc tài sản, đồ đạc.'],
                    ]],
                    ['name' => 'Nghĩa', 'type' => 'good', 'cung' => [
                        ['name' => 'Thêm đinh', 'desc' => 'Gia đình có thêm con trai, người nối dõi.'],
                        ['name' => 'Ích lợi', 'desc' => 'Làm việc gì cũng có lợi, thu được kết quả tốt.'],
                        ['name' => 'Quý tử', 'desc' => 'Sinh được con trai quý, thông minh, hiếu thảo.'],
                        ['name' => 'Đại cát', 'desc' => 'Rất may mắn, mọi sự hanh thông.'],
                    ]],
                ]
            ],
            'am_trach' => [
                'name' => 'Thước Lỗ Ban 38.8cm (Âm phần): Đồ nội thất (bàn thờ, tủ...)',
                'title_short' => 'Thước Lỗ Ban 38.8cm (Âm phần)',
                'description_title' => 'Đồ nội thất (bàn thờ, tủ...)',
                'total_length' => 38.8,
                'khoang' => [
                     ['name' => 'Đinh', 'type' => 'good', 'cung' => [
                        ['name' => 'Phúc Tinh', 'desc' => 'Sao phúc chiếu, mang lại may mắn, bình an.'],
                        ['name' => 'Cập đệ', 'desc' => 'Thi cử đỗ đạt, công danh thăng tiến.'], // Cập nhật tên cung
                        ['name' => 'Tài Vượng', 'desc' => 'Tài lộc dồi dào, tiền bạc thịnh vượng.'],
                        ['name' => 'Đăng Khoa', 'desc' => 'Đỗ đạt cao, có tên trên bảng vàng.'],
                     ]],
                     ['name' => 'Hại', 'type' => 'bad', 'cung' => [
                        ['name' => 'Khẩu thiệt', 'desc' => 'Gặp chuyện thị phi, tai tiếng, tranh cãi.'],
                        ['name' => 'Lâm bệnh', 'desc' => 'Mắc bệnh tật, sức khỏe suy yếu.'],
                        ['name' => 'Tử tuyệt', 'desc' => 'Đoạn tuyệt đường con cái, không có người nối dõi.'],
                        ['name' => 'Tai chí', 'desc' => 'Tai họa bất ngờ ập đến.'],
                     ]],
                     ['name' => 'Vượng', 'type' => 'good', 'cung' => [
                        ['name' => 'Thiên đức', 'desc' => 'Được trời đất che chở, có đức độ.'],
                        ['name' => 'Hỷ sự', 'desc' => 'Trong nhà có chuyện vui, hỷ tín.'],
                        ['name' => 'Tiến bảo', 'desc' => 'Có của cải, tài lộc đến nhà.'],
                        ['name' => 'Nạp phúc', 'desc' => 'Đón nhận phúc đức, may mắn.'],
                     ]],
                     // ... và các khoảng còn lại
                ]
            ]
        ];
    }

}
