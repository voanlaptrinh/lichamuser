<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str; // Sử dụng để chuyển đổi Markdown sang HTML
class VanKhanController extends Controller
{
    // Hằng số chứa URL của API để dễ dàng quản lý
    private const API_URL = 'https://cloudrun.xemlicham.com/van-khans';

    /**
     * Hiển thị danh sách tất cả các bài văn khấn.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $response = Http::get(self::API_URL);

        $vanKhans = [];
        if ($response->successful() && isset($response->json()['success']) && $response->json()['success']) {
            // Lấy mảng 'vanKhans' từ 'responseObject'
            $vanKhans = $response->json()['responseObject']['vanKhans'] ?? [];
        }

        // Truyền dữ liệu ra view
        return view('van-khan.index', ['vanKhans' => $vanKhans]);
    }

    /**
     * Hiển thị chi tiết một bài văn khấn.
     *
     * @param  string  $id
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function show($id)
    {
        $response = Http::get(self::API_URL);

        if (!$response->successful() || !isset($response->json()['success']) || !$response->json()['success']) {
            // Nếu gọi API thất bại, quay về trang danh sách với thông báo lỗi
            return redirect()->route('van-khan.index')->with('error', 'Không thể tải dữ liệu chi tiết.');
        }

        $allVanKhans = $response->json()['responseObject']['vanKhans'] ?? [];

        // Tìm bài văn khấn có id tương ứng trong danh sách đã lấy về
        // Sử dụng collection của Laravel để tìm kiếm dễ dàng hơn
        $vanKhan = collect($allVanKhans)->firstWhere('id', $id);

        // Nếu không tìm thấy, trả về trang 404 Not Found
        if (!$vanKhan) {
            abort(404);
        }
        
        // Để hiển thị nội dung Markdown đẹp hơn, ta chuyển đổi nó thành HTML
        // Laravel 9+ có sẵn helper Str::markdown()
        // Lưu ý: Phải dùng {!! !!} trong Blade để render HTML
        $vanKhan['offerings_html'] = Str::markdown($vanKhan['offerings'] ?? '');
        $vanKhan['instructions_html'] = Str::markdown($vanKhan['instructions'] ?? '');
        
        foreach ($vanKhan['templates'] as $key => $template) {
             $vanKhan['templates'][$key]['content_html'] = Str::markdown($template['content'] ?? '');
        }

        return view('van-khan.show', ['vanKhan' => $vanKhan]);
    }
}
