<?php

namespace App\Helpers;

class TimeConstantHelper
{
    
    public static function getNapAmFromCanChi($canChi)
    {
        $napAmData = DataHelper::$napAmTable[$canChi] ?? ['napAm' => 'Bích Thượng Thổ', 'hanh' => 'Thổ'];

        $parts = explode(' ', $canChi);

        if (count($parts) != 2) {
            return [
                'canChi' => $canChi,
                'canHanh' => 'Kim',
                'chiHanh' => 'Thủy',
                'napAm' => $napAmData['napAm'],
                'napAmHanh' => $napAmData['hanh'],
            ];
        }

        [$can, $chi] = $parts;

        return [
            'canChi' => $canChi,
            'canHanh' => DataHelper::$canToHanh[$can] ?? 'Kim',
            'chiHanh' => DataHelper::$chiToHanh[$chi] ?? 'Thủy',
            'napAm' => $napAmData['napAm'],
            'napAmHanh' => $napAmData['hanh'],
        ];
    }
    public static function getConflictAge($canChi)
    {
        $conflictAges = DataHelper::$listAgeConflict[$canChi] ?? '';
        return $conflictAges;
    }
    public static function getGioHoangDaoByChi($chi)
    {
        $chi = strtolower($chi);
        foreach (DataHelper::$hoangDaoByChi as $key => $gioList) {
            $keys = explode('|', $key);
            if (in_array($chi, $keys)) {
                return $gioList;
            }
        }
        return [];
    }

    public static function getGioHacDaoByChi($chi)
    {
        $chi = strtolower($chi);
        foreach (DataHelper::$hacDaoByChi as $key => $gioList) {
            $keys = explode('|', $key);
            if (in_array($chi, $keys)) {
                return $gioList;
            }
        }
        return [];
    }
    public static function getGioHDTrongNgayTXT($chi, $isHoangDao = true, $isMini = false)
    {
        $gioList = $isHoangDao ? self::getGioHoangDaoByChi($chi) : self::getGioHacDaoByChi($chi);

        return implode(', ', array_map(function ($gio) use ($isMini) {
            return $isMini
                ? (DataHelper::$khungGioMini[$gio] ?? '')
                : (DataHelper::$khungGio[$gio] ?? '');
        }, $gioList));
    }
}
