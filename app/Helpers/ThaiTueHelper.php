<?php


namespace App\Helpers;

class ThaiTueHelper
{
    public static function evaluateThaiTueByPurpose(string $chiDay, string $chiBirth, string $purpose): array
    {
        $isThaiTue = self::isThaiTueConflict($chiDay, $chiBirth);
        $issues = [];

        if (!$isThaiTue) {
            return ['issues' => $issues];
        }

        $severityByPurpose = [
            'TOT_XAU_CHUNG' => [
                'level' => 'warn',
                'reason' => 'Phạm Thái Tuế - Nên thận trọng',
            ],
            'KHAI_TRUONG' => [
                'level' => 'warn',
                'reason' => 'Phạm Thái Tuế - Thận trọng khi khai trương',
            ],
            'CUOI_HOI' => [
                'level' => 'warn',
                'reason' => 'Phạm Thái Tuế - Thận trọng khi cưới hỏi',
            ],
            'MUA_NHA_DAT' => [
                'level' => 'warn',
                'reason' => 'Phạm Thái Tuế - Thận trọng khi mua nhà đất',
            ],
            'DONG_THO' => [
                'level' => 'exclude',
                'reason' => 'Phạm Thái Tuế - Kỵ động thổ',
            ],
            'NHAP_TRACH' => [
                'level' => 'warn',
                'reason' => 'Phạm Thái Tuế - Thận trọng khi nhập trạch',
            ],
            'XUAT_HANH' => [
                'level' => 'warn',
                'reason' => 'Phạm Thái Tuế - Thận trọng khi xuất hành',
            ],
            'KY_HOP_DONG' => [
                'level' => 'warn',
                'reason' => 'Phạm Thái Tuế - Thận trọng khi ký hợp đồng',
            ],
            'DINH_HON' => [
                'level' => 'warn',
                'reason' => 'Phạm Thái Tuế - Thận trọng khi đính hôn',
            ],
            'SANG_CAT' => [
                'level' => 'exclude',
                'reason' => 'Phạm Thái Tuế - Kỵ sang cát',
            ],
            'CHUYEN_BAN_THO' => [
                'level' => 'warn',
                'reason' => 'Phạm Thái Tuế - Thận trọng khi chuyển bàn thờ',
            ],
            'LAP_BAN_THO' => [
                'level' => 'warn',
                'reason' => 'Phạm Thái Tuế - Thận trọng khi lập bàn thờ',
            ],
            'CUNG_SAO_GIAI_HAN' => [
                'level' => 'none',
            ],
            'YEM_TRAN' => [
                'level' => 'exclude',
                'reason' => 'Phạm Thái Tuế - Kỵ yểm trần',
            ],
            'CUU_MANG' => [
                'level' => 'none',
            ],
            'MUA_XE' => [
                'level' => 'warn',
                'reason' => 'Phạm Thái Tuế - Thận trọng khi mua xe',
            ],
            'THI_CU' => [
                'level' => 'warn',
                'reason' => 'Phạm Thái Tuế - Thận trọng khi thi cử',
            ],
            'NHAN_CONG' => [
                'level' => 'warn',
                'reason' => 'Phạm Thái Tuế - Thận trọng khi nhận công',
            ],
            'DANG_KY_GIAY_TO' => [
                'level' => 'none',
            ],
        ];

        $rule = $severityByPurpose[$purpose] ?? $severityByPurpose['TOT_XAU_CHUNG'];
        $level = $rule['level'];

        if (in_array($level, ['exclude', 'warn'])) {
            $issues[] = [
                'level' => $level,
                'source' => 'ThaiTue',
                'reason' => $rule['reason'] . ' (' . $chiDay . ')',
                'details' => [
                    'chiDay' => $chiDay,
                    'chiBirth' => $chiBirth,
                ],
            ];
        }

        return ['issues' => $issues];
    }
    public static function isThaiTueConflict(string $chiDay, string $chiBirth): bool
    {
        return $chiDay === $chiBirth;
    }
}
