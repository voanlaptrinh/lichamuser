<?php

namespace App\Http\Controllers;

use App\Helpers\FengShuiHelper;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class XemHuongNhaController extends Controller
{
    /**
     * Hiển thị form xem hướng nhà.
     */
    public function showForm()
    {
        return view('huong-nha.index');
    }

    /**
     * Xử lý request, tính toán và trả về kết quả.
     */
    public function check(Request $request)
    {
        // 1. Validation
        $validator = Validator::make($request->all(), [
            'nam_sinh' => 'required',
            'gioi_tinh' => 'required|in:nam,nữ',
        ], [
            'nam_sinh.required' => 'Vui lòng nhập năm sinh.',
            'gioi_tinh.required' => 'Vui lòng chọn giới tính.',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();
        // $namSinh = (int)$validated['nam_sinh'];
         $birthDateInput = $validated['nam_sinh']; // Giữ lại chuỗi 'd/m/Y'
        $birthDateObject = Carbon::createFromFormat('d/m/Y', $birthDateInput);
        $gioiTinh = $validated['gioi_tinh'];

        // 2. Gọi Helper để lấy kết quả
     $results = FengShuiHelper::layHuongNha(
            $birthDateObject->year,
            $birthDateObject->month,
            $birthDateObject->day,
            $gioiTinh
        );

        // 3. Trả về view với đầy đủ dữ liệu
        return view('huong-nha.index', [
            'results' => $results,
            'nam_sinh' => $birthDateObject, // Truyền lại năm sinh đã nhập
            'gioi_tinh' => $gioiTinh, // Truyền lại giới tính đã chọn
            'inputdate' => $birthDateInput,
        ]);
    }
}