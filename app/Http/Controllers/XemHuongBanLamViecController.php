<?php

namespace App\Http\Controllers;

use App\Helpers\FengShuiHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;

class XemHuongBanLamViecController extends Controller
{
    /**
     * Hiển thị form xem hướng bàn làm việc.
     */
    public function showForm()
    {
        return view('ban-lam-viec.index');
    }

    /**
     * Xử lý request, tính toán và trả về kết quả.
     */
    public function check(Request $request)
    {
        // 1. Validation
        $validator = Validator::make($request->all(), [
            'birthdate' => 'required|date_format:d/m/Y',
            'gioi_tinh' => 'required|in:nam,nữ',
        ], [
            'birthdate.required' => 'Vui lòng nhập ngày sinh của bạn.',
            'birthdate.date_format' => 'Định dạng ngày sinh phải là dd/mm/yyyy.',
            'gioi_tinh.required' => 'Vui lòng chọn giới tính.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();

        // 2. Xử lý dữ liệu đầu vào
        $birthdateInput = $validated['birthdate'];
        $birthdateObject = Carbon::createFromFormat('d/m/Y', $birthdateInput);
        $gioiTinh = $validated['gioi_tinh'];

        // 3. Gọi Helper để lấy kết quả
        $results = FengShuiHelper::layHuongBanLamViec(
            $birthdateObject->year,
            $birthdateObject->month,
            $birthdateObject->day,
            $gioiTinh
        );

        // 4. Trả về view với đầy đủ dữ liệu
        return view('ban-lam-viec.index', [
            'results' => $results,
            'birthdate' => $birthdateInput,
            'gioi_tinh' => $gioiTinh,
        ]);
    }
}
