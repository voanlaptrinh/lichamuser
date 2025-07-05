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

class NhanCongViecMoiController extends Controller
{
    /**
     * Hiển thị form xem ngày nhận công việc mới.
     */
    public function showForm()
    {
        // Không cần truyền dateRanges nữa
        return view('cong-viec-moi.index');
    }

    /**
     * Xử lý dữ liệu, phân tích năm, phân tích ngày và trả kết quả.
     */
    public function checkDays(Request $request)
    {
        // 1. Xử lý Input và Validation (Giữ nguyên code của bạn)
        $input = $request->all();
        $originalInputs = $input;

        $dateRange = $request->input('date_range');
        $dates = $dateRange ? explode(' đến ', $dateRange) : [null, null];
        if (count($dates) === 1) $dates[1] = $dates[0];

        $request->merge([
            'start_date' => $dates[0] ?? null,
            'end_date' => $dates[1] ?? null,
            'birthdate_formatted' => $input['birthdate'] ?? null,
        ]);

        if (!empty($input['birthdate']) && Carbon::hasFormat($input['birthdate'], 'd/m/Y')) {
            $input['birthdate'] = Carbon::createFromFormat('d/m/Y', $input['birthdate'])->format('Y-m-d');
        }
        $request->merge($input);

        $validator = Validator::make($request->all(), [

            'birthdate' => 'required|date',
            'date_range' => 'required',
            'start_date' => 'required|date_format:d/m/Y',
            'end_date' => 'required|date_format:d/m/Y|after_or_equal:start_date',
        ], [
            'birthdate.required' => 'Vui lòng nhập ngày sinh của gia chủ.',
            'date_range.required' => 'Vui lòng chọn khoảng ngày dự định.',
            'start_date.*' => 'Định dạng ngày bắt đầu không hợp lệ.',
            'end_date.*' => 'Định dạng ngày kết thúc không hợp lệ hoặc trước ngày bắt đầu.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();

        $birthdate = Carbon::parse($validated['birthdate']);
        $startDate = Carbon::createFromFormat('d/m/Y', $validated['start_date'])->startOfDay();
        $endDate = Carbon::createFromFormat('d/m/Y', $validated['end_date'])->endOfDay();
        $period = CarbonPeriod::create($startDate, $endDate);

        // 2. Lấy thông tin cơ bản của gia chủ và phân tích các năm
        $birthdateInfo = $this->getPersonBasicInfo($birthdate);
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

        // ---------------------------------------------------------------------
        // ---- PHẦN LOGIC MỚI: LẶP QUA TỪNG NGÀY ĐỂ TÍNH ĐIỂM CHI TIẾT ----
        // ---------------------------------------------------------------------

        // a. Xác định mục đích (purpose) cho việc xem ngày làm nhà
        $purpose = 'NHAN_CONG'; // Hoặc 'LAM_NHA', tùy theo bạn định nghĩa trong DataHelper

        foreach ($period as $date) {
            $year = $date->year;


            // b. Tính toán điểm số của ngày dựa trên tuổi gia chủ
            $dayScoreDetails = GoodBadDayHelper::calculateDayScore($date, $birthdate->year, $purpose);

            // c. Lấy thông tin Can Chi của ngày
            $jd = LunarHelper::jdFromDate($date->day, $date->month, $date->year);
            $dayCanChi = LunarHelper::canchiNgayByJD($jd);

            // d. Lấy Giờ Hoàng Đạo (chỉ giờ ban ngày)
            $dayChi = explode(' ', $dayCanChi)[1];
            $goodHours = LunarHelper::getGoodHours($dayChi, 'day'); // 'day' để chỉ lấy giờ ban ngày

            // e. Tạo chuỗi ngày Âm lịch đầy đủ để hiển thị
            $lunarParts = LunarHelper::convertSolar2Lunar($date->day, $date->month, $date->year);
            $fullLunarDateStr = sprintf('Ngày %02d/%02d %s', $lunarParts[0], $lunarParts[1], $dayCanChi);

            // f. Thêm tất cả kết quả vào mảng `days` của năm tương ứng
            $resultsByYear[$year]['days'][] = [
                'date' => $date->copy(),
                'weekday_name' => $date->isoFormat('dddd'),
                'full_lunar_date_str' => $fullLunarDateStr,
                'good_hours' => $goodHours,
                'day_score' => $dayScoreDetails, // Toàn bộ object điểm số và chi tiết
            ];
        }

        // 4. Trả kết quả về cho view
        return view('cong-viec-moi.index', [
            'date_start_end' => $dates,
            'inputs' => $originalInputs,
            'birthdateInfo' => $birthdateInfo,
            'resultsByYear' => $resultsByYear,
        ]);
    }
    /**
     * Hàm trợ giúp: Phân tích các hạn lớn trong một năm cho gia chủ.
     */
    private function calculateYearAnalysis(Carbon $dob, int $yearToCheck): array
    {
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


        return [
            'is_bad_year' => $isBadYear,
            'lunar_age' => $lunarAge,
            'details' => compact('kimLau', 'hoangOc', 'tamTai'),
        ];
    }


    /**
     * Hàm trợ giúp: Lấy thông tin cơ bản của một người.
     * Cần thêm hàm này vào vì bạn có gọi $this->getPersonBasicInfo($birthdate).
     */
    private function getPersonBasicInfo(Carbon $dob): array
    {
        $birthYear = $dob->year;
        $canChiNam = KhiVanHelper::canchiNam((int)$birthYear);
        $menh = DataHelper::$napAmTable[$canChiNam]; // Giả sử bạn có DataHelper
        $lunarDob = LunarHelper::convertSolar2Lunar($dob->day, $dob->month, $dob->year);

        return [
            'dob' => $dob,
            'lunar_dob_str' => sprintf('%02d/%02d/%d', $lunarDob[0], $lunarDob[1], $lunarDob[2]),
            'can_chi_nam' => $canChiNam,
            'menh' => $menh,
        ];
    }
}
