<?php

namespace App\Http\Controllers;

use App\Helpers\FengShuiHelper;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class XemHuongBepController extends Controller
{
    public function showForm()
    {
        return view('huong-bep.index');
    }

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
        $results = FengShuiHelper::layHuongBep(
            $birthdateObject->year,
            $birthdateObject->month,
            $birthdateObject->day,
            $gioiTinh
        );

        // 4. Trả về view với đầy đủ dữ liệu
        return view('huong-bep.index', [
            'results' => $results,
            'birthdate' => $birthdateInput,
            'gioi_tinh' => $gioiTinh,
        ]);
    }
}