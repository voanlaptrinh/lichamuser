<?php

namespace App\Http\Controllers;

use App\Helpers\XemNgayTreConHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class XemNgayTreConController extends Controller
{
    public function index(Request $request)
    {
        // Lấy dữ liệu tĩnh từ Helper
        $lichData = XemNgayTreConHelper::getLichData();
        $gio_sinh_options = $lichData['gio_chi'];
        
        // Tạo một mảng các năm để người dùng chọn
        $years = range(Carbon::now()->year - 70, Carbon::now()->year + 5);
        
        $results = null;

        // Chỉ xử lý khi người dùng đã gửi form
        if ($request->filled('ngay_sinh')) {
            // Validate dữ liệu đầu vào
            $request->validate([
                'ngay_sinh' => 'required|date_format:Y-m-d',
                'gio_sinh' => 'required|in:' . implode(',', array_keys($gio_sinh_options)),
                'gioi_tinh' => 'required|in:Nam,Nữ',
                'nam_sinh_bo' => 'nullable|integer', // Không bắt buộc nhưng phải là số
            ]);

            // Chuyển đổi chuỗi ngày thành đối tượng Carbon
            $date = Carbon::createFromFormat('Y-m-d', $request->input('ngay_sinh'));

            // Gọi hàm phân tích chính trong Helper
            $results = XemNgayTreConHelper::phanTichNgayGioSinh(
                $date->day,
                $date->month,
                $date->year,
                $request->input('gio_sinh'),
                $request->input('gioi_tinh'),
                $request->input('nam_sinh_bo') ? (int)$request->input('nam_sinh_bo') : null
            );
        }

        // Trả về view cùng với tất cả dữ liệu cần thiết
        return view('xem-ngay-tre-con.index', [
            'gio_sinh_options' => $gio_sinh_options,
            'years' => $years,
            'results' => $results,
            'inputs' => $request->all(),
        ]);
    }
}
