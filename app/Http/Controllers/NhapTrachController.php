<?php

namespace App\Http\Controllers;

use App\Helpers\FengShuiHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

// Giả sử các helper của bạn được đặt tên như thế này
use App\Helpers\AstrologyHelper;
use App\Helpers\GoodBadDayHelper;
use App\Helpers\KhiVanHelper;
use App\Helpers\LunarHelper;
use App\Helpers\DataHelper;

class NhapTrachController extends Controller
{
    /**
     * Hiển thị form nhập liệu.
     */
    public function showForm()
    {
        return view('nhap-trach.index');
    }

    /**
     * Xử lý dữ liệu, phân tích năm, phân tích ngày và trả kết quả.
     */
    public function checkDays(Request $request)
    {
        // 1. Xử lý Input và Validation
        $input = $request->all();
        $originalInputs = $input;

        $dateRange = $request->input('date_range');
        $dates = $dateRange ? explode(' đến ', $dateRange) : [null, null];
        if (count($dates) === 1) $dates[1] = $dates[0];

        $request->merge([
            'start_date' => $dates[0] ?? null,
            'end_date' => $dates[1] ?? null,
        ]);

        if (!empty($input['birthdate']) && \DateTime::createFromFormat('d/m/Y', $input['birthdate'])) {
            $input['birthdate_formatted'] = Carbon::createFromFormat('d/m/Y', $input['birthdate'])->format('Y-m-d');
        } else {
             $input['birthdate_formatted'] = null;
        }

        $request->merge(['birthdate' => $input['birthdate_formatted']]);


        $validator = Validator::make($request->all(), [
            'birthdate' => 'required|date',
            'gioi_tinh' => 'required|in:nam,nữ', // Sửa 'nu' thành 'nữ' để khớp với helper
            'huong_nha' => 'required|string|in:bac,dong_bac,dong,dong_nam,nam,tay_nam,tay,tay_bac',
            'date_range' => 'required',
            'start_date' => 'required|date_format:d/m/Y',
            'end_date' => 'required|date_format:d/m/Y|after_or_equal:start_date',
        ], [
            'birthdate.required' => 'Vui lòng nhập ngày sinh của gia chủ.',
            'gioi_tinh.required' => 'Vui lòng chọn giới tính.',
            'huong_nha.required' => 'Vui lòng chọn hướng nhà.',
            'date_range.required' => 'Vui lòng chọn khoảng ngày dự định.',
            'start_date.*' => 'Định dạng ngày bắt đầu không hợp lệ.',
            'end_date.*' => 'Định dạng ngày kết thúc không hợp lệ hoặc trước ngày bắt đầu.',
        ]);


        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($originalInputs);
        }

        $validated = $validator->validated();

        $birthdate = Carbon::parse($validated['birthdate']);
        $startDate = Carbon::createFromFormat('d/m/Y', $validated['start_date'])->startOfDay();
        $endDate = Carbon::createFromFormat('d/m/Y', $validated['end_date'])->endOfDay();
        $period = CarbonPeriod::create($startDate, $endDate);

        // 2. Lấy thông tin cơ bản VÀ PHONG THỦY của gia chủ
        $birthdateInfo = $this->getPersonBasicInfo($birthdate, $validated['gioi_tinh']);
        
        // 2.1 Phân tích hướng nhà đã chọn
        $huongNhaAnalysis = null;
        if (isset($birthdateInfo['phong_thuy'])) {
           $huongNhaAnalysis = $this->analyzeHouseDirection(
    $validated['huong_nha'],
    $birthdateInfo['phong_thuy'],
    $birthdate, // Thêm biến $birthdate
    $validated['gioi_tinh'] // Thêm biến $gioi_tinh
);
        }

        // 3. Phân tích các năm
        $uniqueYears = [];
        foreach ($period as $date) {
            $uniqueYears[$date->year] = true;
        }
        $uniqueYears = array_keys($uniqueYears);

        $resultsByYear = [];
        foreach ($uniqueYears as $year) {
            $yearAnalysis = $this->calculateYearAnalysis($birthdate, $year);
            $canChiNam = KhiVanHelper::canchiNam((int)$year);
            $resultsByYear[$year] = [
                'year_analysis' => $yearAnalysis,
                'canchi' => $canChiNam,
                'days' => [], // Mảng để lưu kết quả chi tiết của từng ngày
            ];
        }

        // 4. Lặp qua từng ngày để tính điểm chi tiết
        $purpose = 'NHAP_TRACH';

        foreach ($period as $date) {
            $year = $date->year;
            $dayScoreDetails = GoodBadDayHelper::calculateDayScore($date, $birthdate->year, $purpose);
            $jd = LunarHelper::jdFromDate($date->day, $date->month, $date->year);
            $dayCanChi = LunarHelper::canchiNgayByJD($jd);
            $dayChi = explode(' ', $dayCanChi)[1];
            $goodHours = LunarHelper::getGoodHours($dayChi, 'day');
            $lunarParts = LunarHelper::convertSolar2Lunar($date->day, $date->month, $date->year);
            $fullLunarDateStr = sprintf('Ngày %02d/%02d %s', $lunarParts[0], $lunarParts[1], $dayCanChi);

            $resultsByYear[$year]['days'][] = [
                'date' => $date->copy(),
                'weekday_name' => $date->isoFormat('dddd'),
                'full_lunar_date_str' => $fullLunarDateStr,
                'good_hours' => $goodHours,
                'day_score' => $dayScoreDetails,
            ];
        }

        // 5. Trả kết quả về cho view
        return view('nhap-trach.index', [
            'inputs' => $originalInputs,
            'birthdateInfo' => $birthdateInfo,
            'huongNhaAnalysis' => $huongNhaAnalysis, // TRUYỀN KẾT QUẢ PHÂN TÍCH HƯỚNG NHÀ
            'resultsByYear' => $resultsByYear,
        ]);
    }

    /**
     * Hàm trợ giúp: Phân tích các hạn lớn trong một năm cho gia chủ.
     */
    private function calculateYearAnalysis(Carbon $dob, int $yearToCheck): array
    {
        // (Giữ nguyên code của bạn)
        $birthYear = $dob->year;
        $lunarAge = AstrologyHelper::getLunarAge($birthYear, $yearToCheck);

        $kimLau = AstrologyHelper::checkKimLau($lunarAge);
        $hoangOc = AstrologyHelper::checkHoangOc($lunarAge);
        $tamTai = AstrologyHelper::checkTamTai($birthYear, $yearToCheck);

        $badFactors = [];
        if ($kimLau['is_bad']) $badFactors[] = 'Kim Lâu';
        if ($hoangOc['is_bad']) $badFactors[] = 'Hoang Ốc';
        if ($tamTai['is_bad']) $badFactors[] = 'Tam Tai';

        $isBadYear = count($badFactors) > 0;
        $message = $isBadYear
            ? "Năm {$yearToCheck}, gia chủ phạm phải: <strong>" . implode(', ', $badFactors) . "</strong>  - đại kỵ phong thủy khi làm việc trọng đại như động thổ, xây dựng. Vì vậy, không nên khởi công trong năm nay.
Nếu buộc phải thực hiện, gia chủ nên mượn tuổi hợp để hóa giải vận xấu."
            : "Năm {$yearToCheck}, gia chủ không phạm Kim Lâu, Hoang Ốc hay Tam Tai – đây là tín hiệu rất tốt trong phong thủy. Bạn hoàn toàn có thể an tâm tiến hành các công việc trọng đại liên quan đến nhà cửa như mua nhà/đất, xây dựng, hoặc chuyển về nhà mới trong năm nay.
Thời điểm cát lợi, vận khí hanh thông – rất thích hợp để an cư, lập nghiệp.";

        return [
            'is_bad_year' => $isBadYear,
            'lunar_age' => $lunarAge,
            'description' => $message,
            'details' => compact('kimLau', 'hoangOc', 'tamTai'),
        ];
    }


    /**
     * Hàm trợ giúp: Lấy thông tin cơ bản và PHONG THỦY của một người.
     */
    private function getPersonBasicInfo(Carbon $dob, string $gender): array
    {
        $birthYear = $dob->year;
        $canChiNam = KhiVanHelper::canchiNam((int)$birthYear);
        $menh = DataHelper::$napAmTable[$canChiNam];
        $lunarDob = LunarHelper::convertSolar2Lunar($dob->day, $dob->month, $dob->year);

        // *** LOGIC MỚI: TÍNH TOÁN PHONG THỦY ***
        // Sử dụng helper `tinhHuongHopTuoi()` đã tạo trước đó.
        $phongThuyInfo = FengShuiHelper::tinhHuongHopTuoi($birthYear, $gender);

        return [
            'dob' => $dob,
            'gender' => $gender,
            'lunar_dob_str' => sprintf('%02d/%02d/%d', $lunarDob[0], $lunarDob[1], $lunarDob[2]),
            'can_chi_nam' => $canChiNam,
            'menh' => $menh,
            'phong_thuy' => $phongThuyInfo, // Thêm thông tin phong thủy vào đây
        ];
    }

     
       /**
     * HÀM HOÀN CHỈNH: Phân tích hướng nhà, lấy Tên Cung và Mô Tả từ DataHelper.
     */
    private function analyzeHouseDirection(string $houseDirectionKey, array $phongThuyInfo, Carbon $dob, string $gender): array
    {
        // 1. Ánh xạ và chuẩn bị dữ liệu
        $directionMap = [
            'bac' => 'Bắc', 'dong_bac' => 'Đông Bắc', 'dong' => 'Đông', 'dong_nam' => 'Đông Nam',
            'nam' => 'Nam', 'tay_nam' => 'Tây Nam', 'tay' => 'Tây', 'tay_bac' => 'Tây Bắc',
        ];
        $houseDirectionName = $directionMap[$houseDirectionKey] ?? '';
        $genderName = ($gender === 'nam') ? 'Nam mệnh' : 'Nữ mệnh';
        
        $result = [
            'direction_name' => $houseDirectionName,
            'is_good' => false,
            'quality_key' => '',
            'quality_name' => 'Không xác định',
            'description' => 'Không tìm thấy thông tin phù hợp.',
            'conclusion' => '',
        ];

        // 3. Tìm hướng nhà trong các cung Tốt
        foreach ($phongThuyInfo['huong_tot'] as $key => $direction) {
            if ($direction === $houseDirectionName) {
                $result['is_good'] = true;
                $result['quality_key'] = $key;
                
                // LẤY TÊN CUNG CÓ DẤU TỪ DATAHELPER
                $result['quality_name'] = DataHelper::$cungBatTrachNames[$key] ?? str_replace('_', ' ', ucwords($key, '_'));
                
                // Lấy mô tả từ DataHelper
                $result['description'] = DataHelper::$cungBatTrachDescriptions[$key] ?? 'Hướng tốt.';
                
                $result['conclusion'] = sprintf(
                    'Hướng nhà <strong>%s</strong> thuộc nhóm <strong>%s</strong>, hoàn toàn hợp tuổi với gia chủ sinh ngày %s (<strong>%s</strong>). Đây là hướng cát (cung %s), hỗ trợ tốt cho tài lộc, sự nghiệp và gia đạo.<br>👉 Gia chủ có thể yên tâm nhập trạch và an cư lâu dài.',
                    $houseDirectionName, $phongThuyInfo['nhom'], $dob->format('d/m/Y'),
                    $genderName, "<strong>" . $result['quality_name'] . "</strong>"
                );
                return $result;
            }
        }
        
        // 4. Nếu không thấy, tìm trong các hướng xấu
        foreach ($phongThuyInfo['huong_xau'] as $key => $direction) {
            if ($direction === $houseDirectionName) {
                $result['is_good'] = false;
                $result['quality_key'] = $key;

                // LẤY TÊN CUNG CÓ DẤU TỪ DATAHELPER
                $result['quality_name'] = DataHelper::$cungBatTrachNames[$key] ?? str_replace('_', ' ', ucwords($key, '_'));
                
                // Lấy mô tả từ DataHelper
                $result['description'] = DataHelper::$cungBatTrachDescriptions[$key] ?? 'Hướng xấu.';

                $result['conclusion'] = sprintf(
                    'Hướng nhà <strong>%s</strong> không thuộc nhóm hướng hợp với tuổi của gia chủ (<strong>%s</strong>). Đây là hướng không hợp mệnh (phạm phải cung %s), có thể ảnh hưởng đến tài lộc và sức khỏe nếu không được hóa giải đúng cách.<br>👉 Nên xem xét các biện pháp hóa giải phong thủy để chuyển hung thành cát.',
                    $houseDirectionName, $phongThuyInfo['nhom'], "<strong>" . $result['quality_name'] . "</strong>"
                );
                return $result;
            }
        }

        return $result;
    }
}