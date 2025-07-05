@extends('welcome')

@section('content')
    <div class="container mt-4 mb-5">
        {{-- FORM NHẬP LIỆU --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-light">
                <h1 class="h3 mb-0">Xem Hướng Phòng Ngủ Hợp Tuổi</h1>
            </div>
            <div class="card-body">
                <p>Phòng ngủ là nơi nghỉ ngơi, phục hồi năng lượng sau một ngày làm việc. Chọn hướng phòng và hướng giường
                    ngủ hợp tuổi sẽ giúp bạn có giấc ngủ sâu, sức khỏe tốt và tinh thần minh mẫn.</p>
                {{-- Form này đã đúng, chỉ cần đảm bảo route name là chính xác --}}
                <form action="{{ route('huong-phong-ngu.check') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="birthdate" class="form-label fw-bold">Ngày sinh (Dương lịch)</label>
                            <input type="text" class="form-control dateuser @error('birthdate') is-invalid @enderror"
                                id="birthdate" name="birthdate" placeholder="dd/mm/yyyy"
                                value="{{ $birthdate ?? old('birthdate') }}" required>
                            @error('birthdate')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="gioi_tinh" class="form-label fw-bold">Giới tính</label>
                            <select name="gioi_tinh" class="form-select @error('gioi_tinh') is-invalid @enderror" required>
                                <option value="">-- Chọn giới tính --</option>
                                <option value="nam" {{ isset($gioi_tinh) && $gioi_tinh == 'nam' ? 'selected' : '' }}>
                                    Nam</option>
                                <option value="nữ" {{ isset($gioi_tinh) && $gioi_tinh == 'nữ' ? 'selected' : '' }}>Nữ
                                </option>
                            </select>
                            @error('gioi_tinh')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">Xem Kết Quả</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- PHẦN HIỂN THỊ KẾT QUẢ --}}
        @if (isset($results))
            <div class="results-container">
                <h2 class="h4 mb-3">Kết quả xem hướng phòng ngủ cho {{ $results['basicInfo']['gioiTinh'] }} sinh ngày
                    {{ $results['basicInfo']['ngaySinhDuongLich'] }}</h2>

                {{-- 1. THÔNG TIN CƠ BẢN --}}
                <div class="card mb-3">
                    <div class="card-header"><strong>THÔNG TIN CƠ BẢN CỦA GIA CHỦ</strong></div>
                    <div class="card-body">
                        <ul class="list-unstyled">
                            <li><strong>Ngày sinh dương lịch:</strong> {{ $results['basicInfo']['ngaySinhDuongLich'] }}</li>
                            <li><strong>Ngày sinh âm lịch:</strong> {{ $results['basicInfo']['ngaySinhAmLich'] }}</li>
                            <li><strong>Giới tính:</strong> {{ $results['basicInfo']['gioiTinh'] }}</li>
                            <li><strong>Mệnh quái:</strong> {{ $results['basicInfo']['menhQuai'] }}</li>
                            <li><strong>Thuộc nhóm:</strong> <strong
                                    class="text-danger">{{ $results['basicInfo']['thuocNhom'] }}</strong></li>
                        </ul>
                    </div>
                </div>
                <div class="card mb-3">
                    <div class="card-header"><strong>NGUYÊN TẮC BỐ TRÍ PHÒNG NGỦ</strong></div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            {{-- Dùng $results['nguyenTac'] như trong Helper --}}
                            @foreach ($results['nguyenTac'] as $rule)
                                <li class="list-group-item"><i
                                        class="fas fa-check text-primary me-2"></i>{{ $rule }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <div class="card mb-3">
                    <div class="card-header bg-success text-white"><strong>CÁC HƯỚNG TỐT CHO PHÒNG NGỦ VÀ GIƯỜNG
                            NGỦ</strong></div>
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0 text-center align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Hướng</th>
                                    <th>Ý nghĩa</th>
                                    <th>Mức độ tốt</th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($results['huongTotChiTiet'] as $huong)
                                    <tr>
                                        <td class="fw-bold fs-5">{{ $huong['Huong'] }} ({{ $huong['Loai'] }})</td>
                                        <td class="text-start p-3">{{ $huong['Y_nghia'] }}</td>
                                        <td><span class="badge bg-success fs-6">{{ $huong['Uu_tien'] }}</span></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <h6>NHỮNG ĐIỀU CẦN TRÁNH KHI KÊ GIƯỜNG NGỦ</h6>
                <p>Việc kê giường ngủ không đúng cách có thể ảnh hưởng xấu đến sức khỏe, tinh thần, hôn nhân và tài vận.
                    Dưới đây là những điều cấm kỵ cần tránh:</p>
                <div>
                    <h6>1. Đầu giường không có điểm tựa</h6>
                    <ul>
                        <li>Không nên để đầu giường trống không hoặc tựa vào cửa sổ, vách kính, rèm.</li>
                        <li>👉 Gây mất cảm giác an toàn, dễ mất ngủ, mơ xấu.</li>
                    </ul>
                </div>
                <div>
                    <h6>2. Đầu giường hướng vào nhà vệ sinh</h6>
                    <ul>
                        <li>Nhà vệ sinh có uế khí, thủy khí → gây đau đầu, khó ngủ, bệnh lâu ngày không rõ nguyên nhân</li>
                    </ul>
                </div>
                <div>
                    <h6>3. Đầu giường hướng vào bếp hoặc sau bếp</h6>
                    <ul>
                        <li>Hỏa khí mạnh gây nóng nảy, mệt mỏi, xung đột, đặc biệt là với trẻ nhỏ, phụ nữ mang thai.</li>
                    </ul>
                </div>
                <div>
                    <h6>4. Giường nằm dưới xà ngang, dầm trần</h6>
                    <ul>
                        <li>Gây áp lực tâm lý, “trực sát” đè lên cơ thể, dẫn đến đau nhức, bệnh mãn tính.</li>
                    </ul>
                </div>
                <div>
                    <h6>5. Giường đối diện gương soi</h6>
                    <ul>
                        <li>Gương phản xạ năng lượng → gây giật mình, mất ngủ, dễ gặp ác mộng, ly tán.</li>
                    </ul>
                </div>
                <div>
                    <h6>6. Đặt giường sát cửa ra vào hoặc đối diện cửa phòng</h6>
                    <ul>
                        <li>Khí vào phòng xung thẳng vào người nằm → bất an, bệnh tật.</li>
                    </ul>
                </div>
                <div>
                    <h6>7. Kê giường dưới cầu thang, góc khuất, hoặc nơi có vật chắn trên đầu</h6>
                    <ul>
                        <li>Phong khí bế tắc, người ngủ ở đó dễ bị ức chế, lo lắng, căng thẳng.</li>
                    </ul>
                </div>
                <div>
                    <h6>8. Kê giường giữa phòng, không có điểm tựa</h6>
                    <ul>
                        <li>Giống như “con thuyền trôi nổi”, thiếu ổn định, dễ mất phương hướng trong cuộc sống</li>
                    </ul>
                </div>
                <div>
                    <h6>Ghi nhớ nguyên tắc VÀNG khi kê giường ngủ:</h6>
                    <p>Đầu giường phải vững – không xung – không uế – không động – không phản chiếu.</p>
                </div>



            </div>
        @endif
    </div>
@endsection
