<?php

namespace App\Helpers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

//Đánh giá tổng quan ngày trong tổng quan ngày trong chi tiết ngày
class DayScoreHelper
{
    public function loadDayScoreData(array $input): array
    {
        try {
            $date = new \DateTime($input['selectedDate']);
            $birthDateToUse = $input['hasLuckyElementsData'] ? $input['fakeBirthDate'] : null;

            $generalPurposeKey = 'TOT_XAU_CHUNG';
            $currentPurposeKey = $input['purposeToKey'][$input['selectedPurpose']] ?? $generalPurposeKey;

            // Step 1: Tính điểm cho mục tiêu chung
            $summaryGeneralDayScoreData = self::calculateDayScoreForPurpose(
                $date,
                $birthDateToUse,
                $generalPurposeKey
            );

            // Step 2: Tính điểm cho mục tiêu hiện tại
            if ($currentPurposeKey === $generalPurposeKey) {
                $dayScoreData = $summaryGeneralDayScoreData;
            } else {
                $dayScoreData = self::calculateDayScoreForPurpose(
                    $date,
                    $birthDateToUse,
                    $currentPurposeKey
                );
            }

            // Step 3: Tính điểm cho tất cả các mục đích
            $purposeScores = [];
            foreach ($input['supportedPurposes'] as $purposeDisplay) {
                $key = $input['purposeToKey'][$purposeDisplay] ?? 'TOT_XAU_CHUNG';
                $purposeResult = self::calculateDayScoreForPurpose($date, $birthDateToUse, $key);

                $issues = $purposeResult['issues'] ?? [];
                $hasExclusion = collect($issues)->contains(fn($issue) => $issue['level'] === 'exclude');

                $purposeScores[] = [
                    'purpose' => $purposeDisplay,
                    'details' => $purposeResult,
                    'hasExclusion' => $hasExclusion,
                ];
            }

            // Step 4: Sắp xếp theo exclusion và điểm số
            usort($purposeScores, function ($a, $b) {
                if ($a['hasExclusion'] && !$b['hasExclusion']) return 1;
                if (!$a['hasExclusion'] && $b['hasExclusion']) return -1;
                $aPoints = $a['details']['percentage'] ?? 0.0;
                $bPoints = $b['details']['percentage'] ?? 0.0;
                return $bPoints <=> $aPoints;
            });

            return [
                'dayScoreData' => $dayScoreData,
                'summaryGeneralDayScoreData' => $summaryGeneralDayScoreData,
                'purposeScores' => $purposeScores,
                'isDayScoreLoaded' => true,
            ];
        } catch (\Throwable $e) {
            Log::error('Lỗi khi tính điểm ngày tốt xấu', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return [
                'dayScoreData' => [],
                'summaryGeneralDayScoreData' => [],
                'purposeScores' => [],
                'isDayScoreLoaded' => false,
            ];
        }
    }
    public static function calculateDayScoreForPurpose(\DateTime $date, ?\DateTime $birthDate, string $purpose): array
    {
        // Gọi hàm chính xử lý logic chấm điểm
        return GoodBadDayHelper::calculateDayScore($date, $birthDate, $purpose);
    }
}
