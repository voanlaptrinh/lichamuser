<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http; // Sử dụng HTTP Client của Laravel
use Illuminate\Support\Facades\Log;   
use League\CommonMark\CommonMarkConverter;// Dùng để ghi log nếu có lỗi

class HoroscopeController extends Controller
{
    // Mảng dữ liệu cố định cho 12 cung hoàng đạo
    private function getZodiacsData()
    {
        return [
            'aries' => ['name' => 'Bạch Dương', 'icon' => 'https://cdn-icons-png.flaticon.com/512/3429/3429869.png'],
            'taurus' => ['name' => 'Kim Ngưu', 'icon' => 'https://cdn-icons-png.flaticon.com/512/3429/3429934.png'],
            'gemini' => ['name' => 'Song Tử', 'icon' => 'https://cdn-icons-png.flaticon.com/512/3429/3429881.png'],
            'cancer' => ['name' => 'Cự Giải', 'icon' => 'https://cdn-icons-png.flaticon.com/512/3429/3429872.png'],
            'leo' => ['name' => 'Sư Tử', 'icon' => 'https://cdn-icons-png.flaticon.com/512/3429/3429889.png'],
            'virgo' => ['name' => 'Xử Nữ', 'icon' => 'https://cdn-icons-png.flaticon.com/512/3429/3429944.png'],
            'libra' => ['name' => 'Thiên Bình', 'icon' => 'https://cdn-icons-png.flaticon.com/512/3429/3429891.png'],
            'scorpio' => ['name' => 'Bọ Cạp', 'icon' => 'https://cdn-icons-png.flaticon.com/512/3429/3429916.png'],
            'sagittarius' => ['name' => 'Nhân Mã', 'icon' => 'https://cdn-icons-png.flaticon.com/512/3429/3429913.png'],
            'capricorn' => ['name' => 'Ma Kết', 'icon' => 'https://cdn-icons-png.flaticon.com/512/3429/3429875.png'],
            'aquarius' => ['name' => 'Bảo Bình', 'icon' => 'https://cdn-icons-png.flaticon.com/512/3429/3429864.png'],
            'pisces' => ['name' => 'Song Ngư', 'icon' => 'https://cdn-icons-png.flaticon.com/512/3429/3429906.png']
        ];
    }

    /**
     * Hiển thị trang danh sách 12 cung
     */
    public function index()
    {
        $zodiacs = $this->getZodiacsData();
        return view('horoscope.index', ['zodiacs' => $zodiacs]);
    }

    /**
     * Hiển thị trang chi tiết của một cung
     */
    public function show($sign)
    {
        $zodiacs = $this->getZodiacsData();
        if (!array_key_exists($sign, $zodiacs)) {
            abort(404); // Nếu sign không hợp lệ, báo lỗi 404
        }
        $zodiac = ['sign' => $sign] + $zodiacs[$sign];
        return view('horoscope.show', ['zodiac' => $zodiac]);
    }

    /**
     * Lấy dữ liệu từ API bên ngoài và trả về dạng JSON
     */
 public function fetchData($sign, $type)
    {
        $validSigns = array_keys($this->getZodiacsData());
        $validTypes = ['yesterday', 'today', 'tomorrow', 'weekly', 'monthly', 'yearly'];

        if (!in_array($sign, $validSigns) || !in_array($type, $validTypes)) {
            return response()->json(['error' => 'Cung hoặc loại không hợp lệ'], 400);
        }
        
        $apiUrl = "https://cloudrun.xemlicham.com/horoscopes/{$sign}?type={$type}";

        try {
            $response = Http::timeout(10)->get($apiUrl);

            if ($response->successful()) {
                $data = $response->json();

                // --- PHẦN CẬP NHẬT CHÍNH ---

                // 1. Kiểm tra xem có dữ liệu hợp lệ không, bằng cách truy cập sâu vào cấu trúc
                if (isset($data['responseObject']) && isset($data['responseObject']['translatedContent'])) {
                    
                    // 2. Lấy chuỗi Markdown trực tiếp
                    $markdownString = $data['responseObject']['translatedContent'];

                    // 3. Khởi tạo converter
                    $converter = new CommonMarkConverter([
                        'html_input' => 'strip',
                        'allow_unsafe_links' => false,
                    ]);

                    // 4. Chuyển đổi Markdown sang HTML
                    $htmlContent = $converter->convert($markdownString)->getContent();

                    // 5. Trả về JSON chứa HTML
                    return response()->json(['html' => $htmlContent]);
                } else {
                    // Nếu cấu trúc JSON không như mong đợi hoặc không có nội dung
                    // Lấy message lỗi từ API nếu có
                    $errorMessage = $data['message'] ?? 'Không có dữ liệu cho mục này.';
                    return response()->json(['html' => "<p style='text-align:center;'>{$errorMessage}</p>"]);
                }
                // --- KẾT THÚC PHẦN CẬP NHẬT ---

            } else {
                // Xử lý khi request đến API thất bại (lỗi 4xx, 5xx)
                $errorData = $response->json();
                $errorMessage = $errorData['message'] ?? 'Không thể kết nối đến dịch vụ cung hoàng đạo.';
                Log::error("API call failed for {$sign}/{$type}: " . $response->status() . " - " . $errorMessage);
                return response()->json(['error' => $errorMessage], $response->status());
            }
        } catch (\Exception $e) {
            // Xử lý các lỗi kết nối, timeout...
            Log::error("API exception for {$sign}/{$type}: " . $e->getMessage());
            return response()->json(['error' => 'Đã có lỗi xảy ra. Vui lòng thử lại sau.'], 500);
        }
    }
}
