<?php


namespace App\Helpers;

class NguHanhRelationHelper
{
    

    public static function isSinh(string $hanh1, string $hanh2): bool
    {
        if (!in_array($hanh1, DataHelper::$NGU_HANH) || !in_array($hanh2, DataHelper::$NGU_HANH)) {
            return false;
        }
        return DataHelper::$SINH_RELATIONS[$hanh1] === $hanh2;
    }

    public static function isKhac(string $hanh1, string $hanh2): bool
    {
        if (!in_array($hanh1, DataHelper::$NGU_HANH) || !in_array($hanh2, DataHelper::$NGU_HANH)) {
            return false;
        }
        return DataHelper::$KHAC_RELATIONS[$hanh1] === $hanh2;
    }
}
