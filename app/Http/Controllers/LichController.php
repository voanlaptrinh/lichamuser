<?php

namespace App\Http\Controllers;

use App\Helpers\HuongXuatHanhHelper;
use App\Helpers\KhiVanHelper;
use App\Helpers\LichKhongMinhHelper;
use App\Helpers\LoadConfigHelper;
use App\Helpers\LunarHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class LichController extends Controller
{
 
    public function nam($nam)
    {
        if (!$nam || $nam < 1800 || $nam > 2300) {
            abort(404);
        }

        // --- BẮT ĐẦU PHẦN CODE MỚI ---

        // 1. Mảng dữ liệu mô tả 12 con giáp
        $moTaConGiap = [
            'Tý'    => 'Đứng đầu chu kỳ 12 con giáp, Tý là biểu tượng cho sự thông minh, lanh lợi và khả năng sinh tồn mạnh mẽ. Nó đại diện cho sự khởi đầu, sự tích lũy của cải và khả năng tìm thấy cơ hội trong những hoàn cảnh khó khăn nhất.',
            'Sửu'   => 'Là hình ảnh của sức mạnh bền bỉ và sự cần cù, chăm chỉ. Con trâu gắn liền với nền văn minh lúa nước, tượng trưng cho sự ổn định, tính kiên định và thành quả gặt hái được từ lao động chân chính.',
            'Dần'   => 'Mang trong mình khí phách của "chúa sơn lâm", Dần là biểu tượng của quyền uy, lòng dũng cảm và sức mạnh tiên phong. Nó đại diện cho nhà lãnh đạo bẩm sinh, không ngại đối đầu thử thách để bảo vệ lý tưởng của mình.',
            'Mão'   => 'Là hiện thân của sự tinh tế, khôn khéo và uyển chuyển. Vẻ ngoài mềm mại của Mão ẩn chứa một trí tuệ sắc sảo, sự cẩn trọng và khả năng ngoại giao tài tình, luôn biết cách biến nguy thành an một cách nhẹ nhàng.',
            'Thìn'  => 'Con rồng trong huyền thoại của người phương Đông là tính Dương của vũ trụ, biểu tượng tối cao cho quyền lực, sự may mắn và thịnh vượng. Nó tượng trưng cho tham vọng lớn lao, sức mạnh thần thánh và sự biến hóa phi thường.',
            'Tỵ'    => 'Biểu trưng cho sự bí ẩn, trí tuệ sâu sắc và sức quyến rũ tiềm tàng. Con rắn còn đại diện cho sự tái sinh và lột xác, khả năng vượt qua nghịch cảnh để vươn lên một tầm cao mới, mạnh mẽ và khôn ngoan hơn.',
            'Ngọ'   => 'Là biểu tượng của sự tự do, lòng nhiệt huyết và tinh thần phiêu lưu không mệt mỏi. Con ngựa tượng trưng cho tốc độ, sức mạnh và khát vọng không ngừng chinh phục những chân trời mới, vượt qua mọi giới hạn.',
            'Mùi'   => 'Mang trong mình sự ôn hòa, lòng nhân ái và một tâm hồn nghệ sĩ. Con dê là biểu tượng của hòa bình, sự hòa hợp trong các mối quan hệ và một cuộc sống bình yên, an lạc, luôn hướng về tình cảm gia đình và cộng đồng.',
            'Thân'  => 'Đại diện cho trí thông minh bậc thầy, sự nhanh nhạy và óc khôi hài tinh nghịch. Con khỉ là biểu tượng của khả năng ứng biến linh hoạt, sự sáng tạo và tài giải quyết vấn đề một cách độc đáo, khéo léo.',
            'Dậu'   => 'Tiếng gáy vang vọng mỗi sớm mai của nó là biểu tượng cho sự chính xác, cần mẫn và một khởi đầu mới đầy năng lượng. Con gà còn tượng trưng cho lòng tự tôn, sự kiêu hãnh và tinh thần trách nhiệm trong công việc.',
            'Tuất'  => 'Là hiện thân của lòng trung thành tuyệt đối, sự chính trực và tinh thần bảo vệ. Con chó đại diện cho tình bạn vô điều kiện, sự tin cậy và công lý, là người bạn đồng hành đáng tin cậy nhất trên mọi nẻo đường.',
            'Hợi'   => 'Là biểu tượng của sự sung túc, lòng nhân hậu và cuộc sống an nhàn, phú quý. Con lợn mang ý nghĩa của sự may mắn, thịnh vượng và một tâm hồn chân thành, luôn tận hưởng niềm vui của cuộc sống một cách trọn vẹn.',
        ];

        // Lấy Can Chi của năm (ví dụ: "Giáp Thìn")
        $can_chi_nam = LunarHelper::canchiNam($nam);

        // Tách để lấy tên con giáp (ví dụ: "Thìn")
        $tenConGiap = explode(' ', $can_chi_nam)[1] ?? '';

        // Lấy mô tả tương ứng
        $moTa = $moTaConGiap[$tenConGiap] ?? 'Không có thông tin mô tả cho năm này.';

        // 2. Tính ngày bắt đầu và kết thúc của năm Âm lịch
        // Giả định rằng helper của bạn có hàm chuyển đổi từ Âm lịch sang Dương lịch.
        // Cú pháp có thể là: LunarHelper::convertLunar2Solar(ngày, tháng, năm âm lịch, múi giờ)
        // Chúng ta cần tìm ngày Mùng 1 Tháng 1 Âm lịch của năm `$can_chi_nam` và năm sau đó.

        // Tìm ngày bắt đầu (Mùng 1 Tết của năm $nam)
        // Giả sử helper của bạn có một hàm tiện ích để lấy ngày Tết
        // Nếu không, bạn sẽ cần logic phức tạp hơn để tìm ra năm âm lịch tương ứng.
        // Ở đây, tôi giả định có hàm getLunarNewYearDate($solarYear) trả về đối tượng Carbon
        $tetStartDate = $this->getLunarNewYearDate($nam);

        // Tìm ngày kết thúc (là ngày 30 Tết của năm $nam, tức là 1 ngày trước Mùng 1 Tết năm sau)
        $nextTetStartDate = $this->getLunarNewYearDate($nam + 1);
        $tetEndDate = $nextTetStartDate->copy()->subDay();
        
        // Định dạng ngày tháng để hiển thị ra view (có cả Thứ)
        Carbon::setLocale('vi'); // Thiết lập ngôn ngữ Tiếng Việt cho Carbon
        $startDateFormatted = $tetStartDate->translatedFormat('l, \n\g\à\y d/m/Y'); // Ví dụ: "Thứ Bảy, ngày 10/02/2024"
        $endDateFormatted = $tetEndDate->translatedFormat('l, \n\g\à\y d/m/Y');   // Ví dụ: "Thứ Sáu, ngày 28/01/2025"

        // --- KẾT THÚC PHẦN CODE MỚI ---


        // Dữ liệu cũ của bạn
        $metaTitle = "Năm $can_chi_nam $nam - Bắt đầu từ $startDateFormatted";
        $metaDescription = "Thông tin chi tiết năm $nam ($can_chi_nam). Năm âm lịch bắt đầu từ ngày $startDateFormatted và kết thúc vào ngày $endDateFormatted. $moTa";
        $metaKeywords = "lịch âm $nam,lich am $nam,lich van nien $nam,âm lịch $nam,am lich $nam,lich am " . mb_strtolower($can_chi_nam);
        $ogImg = url("/image/nam/$nam");
        $sukienduong = LunarHelper::printAllDuongLichEvents($nam);
        $sukienam = LunarHelper::printAllAmLichEvents();

        return view('lich.nam', compact(
            'nam',
            'can_chi_nam',
            'metaTitle',
            'metaDescription',
            'metaKeywords',
            'ogImg',
            'sukienduong',
            'sukienam',
            'moTa', // <-- Biến mới: Mô tả con giáp
            'startDateFormatted', // <-- Biến mới: Ngày bắt đầu đã định dạng
            'endDateFormatted'  // <-- Biến mới: Ngày kết thúc đã định dạng
        ));
    }

    /**
     * Hàm trợ giúp để tìm ngày dương lịch của mùng 1 Tết trong một năm dương lịch.
     * Bạn nên chuyển hàm này vào LunarHelper của mình.
     *
     * @param int $solarYear
     * @return Carbon
     */
    private function getLunarNewYearDate(int $solarYear): Carbon
    {
        // Chuyển ngày 01/01 của năm dương lịch sang âm lịch để biết ta đang ở năm âm lịch nào
        $lunarInfo = LunarHelper::convertSolar2Lunar(1, 1, $solarYear, 7);
        $lunarYear = $lunarInfo[2];

        // Chuyển ngày mùng 1 tháng 1 của năm âm lịch đó sang dương lịch
        $solarDate = LunarHelper::convertLunar2Solar(1, 1, $lunarYear, 0, 7);
        $solarDateCarbon = Carbon::create($solarDate[2], $solarDate[1], $solarDate[0]);

        // Nếu ngày Tết của năm âm lịch đó lại rơi vào năm dương lịch trước đó,
        // có nghĩa là Tết của năm dương lịch hiện tại phải là của năm âm lịch tiếp theo.
        if ($solarDateCarbon->year < $solarYear) {
            $solarDate = LunarHelper::convertLunar2Solar(1, 1, $lunarYear + 1, 0, 7);
            $solarDateCarbon = Carbon::create($solarDate[2], $solarDate[1], $solarDate[0]);
        }
        
        return $solarDateCarbon;
    }


    public function thang($nam, $thang)
    {

        if (!$nam || $nam < 1800 || $nam > 2300 || !$thang || $thang > 12) {
            abort(404);
        }
        $desrtipton_thang = LoadConfigHelper::$mheaders[$thang];
        // Dữ liệu lịch âm (giả định bạn đã viết lại hàm tương đương printTable)
        [$table_html, $data_totxau, $data_al] = LunarHelper::printTable($thang, $nam, true, true, true);

        $can_chi_nam = LunarHelper::canchiNam($data_al[0]['year']);
        $can_chi_thang = LunarHelper::canchiThang($data_al[0]['year'], $data_al[0]['month']);

        $le_lichs = array_filter(LoadConfigHelper::$ledl, function ($le) use ($thang) {
            return $le['mm'] == $thang;
        });
        $su_kiens = LoadConfigHelper::$sukien[$thang];

        // Sau khi lấy $data_al từ LunarHelper
        foreach ($data_al as &$ngay) {
            // Bước 1: Xác định nhóm tháng (group 1, 2, 3)
            $thang_am = $ngay['month'];

            $groupMonth = HuongXuatHanhHelper::getMonthGroup((int)$thang_am);
            // Bước 2: Tính loại ngày xuất hành
            $ten_ngay = HuongXuatHanhHelper::calculateXuatHanhDay($groupMonth, $ngay['day']);

            // Bước 3: Lấy mô tả HTML của ngày đó
            $mo_ta_ngay = LichKhongMinhHelper::ngayToHTML($ten_ngay);

            // Thêm vào mỗi ngày
            $ngay['xuat_hanh_ten'] = $ten_ngay;
            $ngay['xuat_hanh_html'] = $mo_ta_ngay;
        }

        return view('lich.thang', [

            'yy' => $nam,
            'mm' => $thang,
            'can_chi_nam' => $can_chi_nam,
            'can_chi_thang' => $can_chi_thang,
            'table_html' => $table_html,
            'data_totxau' => $data_totxau,
            'data_al' => $data_al,
            'desrtipton_thang' => $desrtipton_thang,
            'le_lichs' => $le_lichs, //Ngày lễ dương lịch
            'su_kiens' => $su_kiens, //Sự kiện tháng 

        ]);
    }













    // public function ngay($nam, $thang, $ngay)
    // {
    //     if (!$nam || $nam < 1800 || $nam > 2300 || !$thang || $thang > 12 || !$ngay || $ngay > 31) {
    //         abort(404);
    //     }

    //     $canonical = url()->to(route('lich.ngay', compact('nam', 'thang', 'ngay')));

    //     $al = LunarHelper::convertSolar2Lunar($ngay, $thang, $nam);
    //     $jd = LunarHelper::jdFromDate($ngay, $thang, $nam);

    //     $can_chi_ngay = LunarHelper::canchiNgayByJD($jd);
    //     $can_chi_thang = LunarHelper::canchiThang($al[2], $al[1]);
    //     $can_chi_nam = LunarHelper::canchiNam($al[2]);

    //     $chi_ngay = explode(' ', $can_chi_ngay)[1] ?? null;
    //     $gioHd = LunarHelper::gioHDTrongNgayTXT($chi_ngay);

    //     $data_ngay = LunarHelper::getContentById($ngay . $thang . $nam) ?? LunarHelper::getContentByDMY($ngay, $thang, $nam);

    //     $data = [
    //         'yy' => $nam,
    //         'mm' => $thang,
    //         'dd' => $ngay,
    //         'al' => $al,
    //         'jd' => $jd,
    //         'can_chi_nam' => $can_chi_nam,
    //         'can_chi_thang' => $can_chi_thang,
    //         'can_chi_ngay' => $can_chi_ngay,
    //         'giohd' => $gioHd,
    //         'ngay_content' => $data_ngay['Content'] ?? '',
    //     ];



    //     return view('lich.ngay', compact(
    //         'data',
    //         'canonical'
    //     ));
    // }
}
