<?php

namespace App\Http\Controllers\Api;

use App\Helpers\LunarHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LunarConvertController extends Controller
{
   public function convertToAm(Request $request)
    {
        $date = $request->input('date');
        try {
            [$yy, $mm, $dd] = explode('-', $date);
            $al = LunarHelper::convertSolar2Lunar((int)$dd, (int)$mm, (int)$yy);
            $amDate = sprintf('%04d-%02d-%02d', $al[2], $al[1], $al[0]);
            return response()->json(['date' => $amDate]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Lỗi chuyển đổi dương -> âm'], 400);
        }
    }

    public function convertToDuong(Request $request)
    {
        $date = $request->input('date');
        try {
            [$yy, $mm, $dd] = explode('-', $date);
            $dl = LunarHelper::convertLunar2Solar((int)$dd, (int)$mm, (int)$yy, 0); // mặc định không nhuận
            $duongDate = sprintf('%04d-%02d-%02d', $dl[2], $dl[1], $dl[0]);
            return response()->json(['date' => $duongDate]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Lỗi chuyển đổi âm -> dương'], 400);
        }
    }
}
