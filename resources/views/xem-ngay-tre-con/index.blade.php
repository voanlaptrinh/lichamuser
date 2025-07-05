@extends('welcome')

@section('content')

    <h1 class="text-center text-primary mb-4">Xem giờ sinh con có phạm không?</h1>

    {{-- FORM NHẬP LIỆU --}}
    <div class="card bg-light border mb-5">
        <div class="card-body">
            <form action="{{ url('/xem-ngay-sinh') }}" method="GET">
                <div class="row g-3 align-items-end">
                    <div class="col-md-6 col-lg-3">
                        <label for="ngay_sinh" class="form-label fw-bold">Ngày sinh của trẻ (DL)</label>
                        <input type="date" class="form-control" id="ngay_sinh" name="ngay_sinh"
                            value="{{ $inputs['ngay_sinh'] ?? date('Y-m-d') }}" required>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <label for="gio_sinh" class="form-label fw-bold">Giờ sinh</label>
                        <select name="gio_sinh" id="gio_sinh" class="form-select" required>
                            @foreach ($gio_sinh_options as $chi => $khoang_gio)
                                <option value="{{ $chi }}"
                                    {{ ($inputs['gio_sinh'] ?? 'Ty') == $chi ? 'selected' : '' }}>
                                    {{ $chi }} ({{ $khoang_gio }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <label for="gioi_tinh" class="form-label fw-bold">Giới tính</label>
                        <select name="gioi_tinh" id="gioi_tinh" class="form-select" required>
                            <option value="Nam" {{ ($inputs['gioi_tinh'] ?? 'Nam') == 'Nam' ? 'selected' : '' }}>Nam
                            </option>
                            <option value="Nữ" {{ ($inputs['gioi_tinh'] ?? '') == 'Nữ' ? 'selected' : '' }}>Nữ</option>
                        </select>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <label for="nam_sinh_bo" class="form-label fw-bold">Năm sinh của Bố</label>
                        <select name="nam_sinh_bo" id="nam_sinh_bo" class="form-select">
                            <option value="">-- Tùy chọn --</option>
                            @foreach ($years as $year)
                                <option value="{{ $year }}"
                                    {{ ($inputs['nam_sinh_bo'] ?? '') == $year ? 'selected' : '' }}>{{ $year }}
                                </option>
                            @endforeach
                        </select>
                        <small class="form-text text-muted">Để xem giờ Quan sát.</small>
                    </div>
                </div>
                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-primary btn-lg px-5">Xem kết quả phân tích</button>
                </div>
            </form>
        </div>
    </div>
    <div>
        Các loại giờ xấu cho con trẻ phổ biến như sau:

        <ul>
            <li>- Sinh phạm giờ Kim xà thiết tỏa: dễ chết yểu. Đây được xem là tối độc trong các loại phạm mà trẻ con hay
                gặp.</li>
            <li>- Sinh phạm giờ Quan sát: tính khí ngang ngược, dễ mắc vòng tù tội.</li>
            <li>
                - Sinh phạm giờ Diêm Vương: thần kinh không ổn định, hay giật mình, trợn trừng
            </li>
            <li>
                - Sinh phạm giờ Dạ đề: hay khóc đêm
            </li>
            <li>

                - Sinh phạm giờ Tướng quân: hay mắc bệnh sài đen (co giật, khóc hoài không nín)
            </li>
        </ul>


    </div>
    {{-- KHU VỰC HIỂN THỊ KẾT QUẢ --}}
    @if (isset($results))
        <div class="p-3 bg-white border rounded">
            <h2 class="text-center" style="font-size: 1.75rem; font-weight: bold; margin-bottom: 1.5rem;">Kết quả phân tích
            </h2>

            <p>Trẻ sinh ngày: <strong>{{ $results['duong_lich'] }}</strong> dương lịch</p>
            <p>{{ $results['am_lich_full'] }}</p>
            <p>{{ $results['thuoc_mua'] }}</p>
            <p>{{ $results['gio_sinh'] }}</p>

            <hr class="my-4">

            <p>Theo kết quả phân tích từ các phương pháp dân gian còn lưu truyền được thì trẻ:</p>
            <ul class="list-unstyled ps-3">
                @foreach ($results['phan_tich_pham'] as $pham)
                    <li class="mb-2">
                        @if ($pham['pham'])
                            <span class="text-danger">❌ {{ $pham['ket_luan'] }}</span>
                        @else
                            <span class="text-success">✔️ {{ $pham['ket_luan'] }}</span>
                        @endif
                    </li>
                @endforeach
            </ul>

            {{-- Hiển thị phần hóa giải nếu có giờ phạm --}}
            @foreach ($results['phan_tich_pham'] as $key => $pham)
                @if ($pham['pham'])
                    <div class="mt-4 p-3 border-top">
                        <h5 class="text-uppercase text-danger">CÁCH HÓA GIẢI PHẠM GIỜ
                            {{ str_replace('_', ' ', strtoupper($key)) }}</h5>
                        <p>
                            <em>(Nội dung dưới đây mang tính tham khảo, được tổng hợp từ dân gian.)</em>
                        </p>
                        <p>
                            {{-- Bạn có thể thêm nội dung hóa giải tĩnh hoặc từ database ở đây --}}
                            Ví dụ: Cách 1: Lấy x xác ve sầu (nam 7 cái, nữ 9 cái), bỏ miệng và chân, sao giòn sắc uống...
                        </p>
                    </div>
                @endif
            @endforeach
        </div>
    @endif


@endsection
