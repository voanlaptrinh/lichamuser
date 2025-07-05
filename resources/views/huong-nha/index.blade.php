@extends('welcome')

@section('content')
    <div class="container mt-4 mb-5">
        {{-- FORM NHẬP LIỆU --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-light">
                <h1 class="h3 mb-0">Xem Hướng Nhà Hợp Tuổi</h1>
            </div>
            <div class="card-body">
                <p>Chọn hướng nhà hợp tuổi là một yếu tố quan trọng trong phong thủy, giúp gia chủ thu hút vượng khí, tài
                    lộc và may mắn.</p>
                <form action="{{ route('huong-nha.check') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nam_sinh" class="form-label fw-bold">Ngày sinh gia chủ (Dương lịch)</label>
                            {{-- Input đã được đổi thành nhập ngày sinh đầy đủ --}}
                            <input type="text" class="form-control dateuser @error('nam_sinh') is-invalid @enderror"
                                id="nam_sinh" name="nam_sinh" placeholder="dd/mm/yyyy"
                                value="{{ $inputdate ?? old('nam_sinh') }}" required>
                            @error('nam_sinh')
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
                <h2 class="h4 mb-3">Kết quả xem hướng nhà cho gia chủ sinh ngày
                    {{ $results['basicInfo']['ngaySinhDuongLich'] }}</h2>

                {{-- 1. THÔNG TIN CƠ BẢN (THEO MẪU HÌNH ẢNH) --}}
                <div class="card mb-4 border-primary">
                    <div class="card-header bg-primary text-white text-center"><strong>THÔNG TIN CƠ BẢN</strong></div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-calendar-day me-2 text-primary"></i>Ngày sinh dương lịch:</span>
                                <span class="fw-bold">{{ $results['basicInfo']['ngaySinhDuongLich'] }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-moon me-2 text-primary"></i>Ngày sinh âm lịch:</span>
                                <span class="fw-bold">{{ $results['basicInfo']['ngaySinhAmLich'] }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-venus-mars me-2 text-primary"></i>Giới tính:</span>
                                <span class="fw-bold">{{ $results['basicInfo']['gioiTinh'] }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-star-of-life me-2 text-primary"></i>Mệnh quái:</span>
                                <span class="fw-bold">{{ $results['basicInfo']['menhQuai'] }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-users me-2 text-primary"></i>Thuộc nhóm:</span>
                                <span class="fw-bold text-danger">{{ $results['basicInfo']['thuocNhom'] }}</span>
                            </li>
                        </ul>
                    </div>
                </div>

                {{-- 2. NGUYÊN TẮC CHỌN HƯỚNG (THEO MẪU HÌNH ẢNH) --}}
                <div class="card mb-4">
                    <div class="card-header text-center"><strong>NGUYÊN TẮC CHỌN HƯỚNG NHÀ</strong></div>
                    <div class="card-body">
                        <p><i class="fas fa-check-circle text-success me-2"></i><strong class="text-success">Chọn hướng
                                cát:</strong> {{ implode(', ', $results['nguyenTac']['huongCat']) }}</p>
                        <p><i class="fas fa-times-circle text-danger me-2"></i><strong class="text-danger">Tránh hướng
                                hung:</strong> {{ implode(', ', $results['nguyenTac']['huongHung']) }}</p>
                    </div>
                </div>

                {{-- 3. BẢNG CHI TIẾT HƯỚNG TỐT (GIỮ LẠI VÌ RẤT HỮU ÍCH) --}}
                <div class="card mb-3">
                    <div class="card-header bg-success text-white"><strong>PHÂN TÍCH CHI TIẾT CÁC HƯỚNG TỐT</strong></div>
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0 text-center align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Hướng</th>
                                    <th>Loại Cát Tinh</th>
                                    <th>Ý nghĩa</th>
                                    <th>Mức độ tốt</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($results['huongNhaTotChiTiet'] as $huong)
                                    <tr>
                                        <td class="fw-bold fs-5">{{ $huong['Huong'] }}</td>
                                        <td>{{ $huong['Loai'] }}</td>
                                        <td class="text-start p-3">{{ $huong['Y nghia'] }}</td>
                                        <td><span class="badge bg-success fs-6">{{ $huong['Uu tien'] }}</span></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>


            </div>
        @endif
    </div>
@endsection
