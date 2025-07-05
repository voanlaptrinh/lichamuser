<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\AstrologyHelper;
use App\Helpers\DataHelper;
use App\Helpers\GoodBadDayHelper;
use App\Helpers\KhiVanHelper;
use App\Helpers\LunarHelper;
use Carbon\CarbonPeriod;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;

class CaiTangController extends Controller
{


    /**
     * Hiển thị form xem ngày cải táng.
     */
    public function showForm()
    {
        return view('cai-tang.index'); // Sử dụng 1 view duy nhất cho cả form và kết quả
    }
    /**
     * Xử lý dữ liệu, phân tích và trả kết quả.
     */
    public function checkDays(Request $request)
    {
        // 1. Xử lý Input và Validation (ĐỒNG BỘ VỚI TÊN INPUT TỪ VIEW)
        $originalInputs = $request->all();
        $dateRange = $request->input('date_range');
        $dates = $dateRange ? explode(' đến ', $dateRange) : [null, null];
        if (count($dates) === 1) $dates[1] = $dates[0];

        $request->merge([
            'start_date' => $dates[0] ?? null,
            'end_date' => $dates[1] ?? null,
        ]);

        $validator = Validator::make($request->all(), [
            'birthdate' => 'required|date_format:d/m/Y',
            'birth_mat' => 'required|integer|min:1800',
            'nam_mat'   => 'required|integer|min:1800|gt:birth_mat',
            'date_range' => 'required',
            'start_date' => 'required|date_format:d/m/Y',
            'end_date' => 'required|date_format:d/m/Y|after_or_equal:start_date',
        ], [
            'birthdate.required' => 'Vui lòng nhập ngày sinh của người đứng lễ.',
            'birthdate.date_format' => 'Định dạng ngày sinh không đúng (dd/mm/yyyy).',
            'birth_mat.required' => 'Vui lòng chọn năm sinh âm lịch của người mất.',
            'nam_mat.required' => 'Vui lòng chọn năm mất của người mất.',
            'nam_mat.gt' => 'Năm mất phải lớn hơn năm sinh.',
            'date_range.required' => 'Vui lòng chọn khoảng ngày dự định.',
            'start_date.*' => 'Định dạng ngày bắt đầu không hợp lệ.',
            'end_date.*' => 'Định dạng ngày kết thúc không hợp lệ hoặc trước ngày bắt đầu.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();

        // 2. Xử lý và chuẩn bị dữ liệu
        $hostDob = Carbon::createFromFormat('d/m/Y', $validated['birthdate']);
        $deceasedBirthYear = (int)$validated['birth_mat'];
        $startDate = Carbon::createFromFormat('d/m/Y', $validated['start_date'])->startOfDay();
        $endDate = Carbon::createFromFormat('d/m/Y', $validated['end_date'])->endOfDay();
        $period = CarbonPeriod::create($startDate, $endDate);

        $hostInfo = $this->getPersonBasicInfo($hostDob);

        // Lấy thông tin cho người đã mất
        $deceasedBirthYear = (int)$validated['birth_mat'];
        $deceasedDeathYear = (int)$validated['nam_mat'];

        $deceasedInfo = [
            'birth_year_lunar' => $deceasedBirthYear,
            'death_year_lunar' => $deceasedDeathYear,
            'birth_can_chi' => KhiVanHelper::canchiNam($deceasedBirthYear),
            'death_can_chi' => KhiVanHelper::canchiNam($deceasedDeathYear), // Thêm Can chi năm mất
        ];

        // 3. Phân tích các năm trong khoảng thời gian đã chọn
        $uniqueYears = collect($period)->map(fn($date) => $date->year)->unique()->values();
        $resultsByYear = [];

        foreach ($uniqueYears as $year) {
            $hostYearAnalysis = $this->calculateHostAnalysis($hostDob, $year);
            $deceasedYearAnalysis = AstrologyHelper::analyzeYearForDeceased($deceasedBirthYear, $year);

            $resultsByYear[$year] = [
                'host_analysis' => $hostYearAnalysis,
                'deceased_analysis' => $deceasedYearAnalysis,
                'days' => [],
            ];
        }

        // 4. Lặp qua từng ngày để chấm điểm chi tiết
        $purpose = 'CAI_TANG';
        foreach ($period as $date) {
            $year = $date->year;

            $dayScoreDetails = GoodBadDayHelper::calculateDayScore(
                $date,
                $hostDob->year,
                $purpose,
            );

            $jd = LunarHelper::jdFromDate($date->day, $date->month, $date->year);
            $dayCanChi = LunarHelper::canchiNgayByJD($jd);
            $dayChi = explode(' ', $dayCanChi)[1];
            $goodHours = LunarHelper::getGoodHours($dayChi, 'all');
            $lunarParts = LunarHelper::convertSolar2Lunar($date->day, $date->month, $date->year);
            $fullLunarDateStr = sprintf('Ngày %s (tức %02d/%02d/%d âm lịch)', $dayCanChi, $lunarParts[0], $lunarParts[1], $lunarParts[2]);

            $resultsByYear[$year]['days'][] = [

                'date' => $date->copy(),
                'weekday_name' => $date->isoFormat('dddd'),
                'full_lunar_date_str' => $fullLunarDateStr,
                'good_hours' => $goodHours,
                'day_score' => $dayScoreDetails,
            ];
        }

        // 5. Trả kết quả về cho view
        return view('cai-tang.index', [ // Đổi lại tên view cho khớp
            'inputs' => $originalInputs,
            'hostInfo' => $hostInfo,
            'deceasedInfo' => $deceasedInfo,
            'resultsByYear' => $resultsByYear,
        ]);
    }

    /**
     * Hàm trợ giúp: Phân tích hạn của người đứng lễ.
     */
    private function calculateHostAnalysis(Carbon $dob, int $yearToCheck): array
    {
        $birthYear = $dob->year;
        $lunarAge = AstrologyHelper::getLunarAge($birthYear, $yearToCheck);
        $kimLau = AstrologyHelper::checkKimLau($lunarAge);
        $hoangOc = AstrologyHelper::checkHoangOc($lunarAge);
        $tamTai = AstrologyHelper::checkTamTai($birthYear, $yearToCheck);
        $thaiTue = AstrologyHelper::checkThaiTue($birthYear, $yearToCheck);
        $badFactors = [];
        if ($kimLau['is_bad']) $badFactors[] = 'Kim Lâu';
        if ($hoangOc['is_bad']) $badFactors[] = 'Hoang Ốc';
        if ($tamTai['is_bad']) $badFactors[] = 'Tam Tai';
        if ($thaiTue['is_pham']) {
            foreach ($thaiTue['details'] as $pham) {
                $badFactors[] = $pham['type']; // Ví dụ: 'Xung Thái Tuế', 'Trực Thái Tuế'
            }
        }
        $isBadYear = count($badFactors) > 0;
        $message = $isBadYear
            ? "Năm {$yearToCheck}, người đứng lễ phạm: <strong>" . implode(', ', $badFactors) . "</strong>. Nên đây không phải là năm thích hợp để đứng lễ. Nếu vẫn quyết định tiến hành công việc thì hãy nhờ người khác hợp tuổi để đứng lễ hoặc chọn năm khác để tiến hành."
            : "Năm {$yearToCheck}, người đứng lễ không phạm: Kim Lâu, Hoang Ốc, Tam Tai năm không xung với tuổi. Nên đây là năm thích hợp để có thể tiến hành các công việc trọng đại về âm phần.";

        return [
            'kimLau' => $kimLau,
            'hoangOc' => $hoangOc,
            'tamTai' => $tamTai,
            'thaiTue' => $thaiTue,
            'is_bad_year' => $isBadYear,
            'lunar_age' => $lunarAge,
            'description' => $message,
        ];
    }

    /**
     * Hàm trợ giúp: Lấy thông tin cơ bản của một người.
     */
    private function getPersonBasicInfo(Carbon $dob): array
    {
        $birthYear = $dob->year;
        $canChiNam = KhiVanHelper::canchiNam((int)$birthYear);
        $menh = DataHelper::$napAmTable[$canChiNam] ?? 'Không rõ';
        $lunarDob = LunarHelper::convertSolar2Lunar($dob->day, $dob->month, $dob->year);

        // Tính tuổi âm cho năm hiện tại
        $currentLunarAge = AstrologyHelper::getLunarAge($birthYear, date('Y'));

        return [
            'dob_obj' => $dob, // Giữ lại object Carbon để dùng
            'dob_str' => $dob->format('d/m/Y'),
            'lunar_dob_str' => sprintf('%02d/%02d/%d', $lunarDob[0], $lunarDob[1], $lunarDob[2]),
            'can_chi_nam' => $canChiNam,
            'menh' => $menh,
            'lunar_age_now' => $currentLunarAge, // Thêm tuổi âm hiện tại
        ];
    }
}
