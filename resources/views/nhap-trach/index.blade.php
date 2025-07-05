@extends('welcome')

@section('content')
    <div class="container mt-4 mb-5">
        {{-- PHẦN FORM NHẬP LIỆU --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-light">
                <h1 class="h3 mb-0">Xem ngày tốt Nhập trạch theo tuổi</h1>
            </div>
            <div class="card-body">
                {{-- Đổi action sang route của bạn, ví dụ 'nhap-trach.check' --}}
                <form action="{{ route('nhap-trach.check') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="birthdate" class="form-label fw-bold">Ngày sinh gia chủ</label>
                            <input type="text" class="form-control dateuser @error('birthdate') is-invalid @enderror"
                                id="birthdate" name="birthdate" placeholder="dd/mm/yyyy"
                                value="{{ old('birthdate', $inputs['birthdate'] ?? '') }}">
                            @error('birthdate')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="gioi_tinh" class="form-label fw-bold">Giới tính</label>
                            {{-- SỬA VALUE: 'nu' -> 'nữ' để khớp với helper --}}
                            <select name="gioi_tinh" class="form-select @error('gioi_tinh') is-invalid @enderror">
                                <option value="">-- Chọn giới tính --</option>
                                <option value="nam"
                                    {{ old('gioi_tinh', $inputs['gioi_tinh'] ?? '') == 'nam' ? 'selected' : '' }}>Nam
                                </option>
                                <option value="nữ"
                                    {{ old('gioi_tinh', $inputs['gioi_tinh'] ?? '') == 'nữ' ? 'selected' : '' }}>Nữ</option>
                            </select>
                            @error('gioi_tinh')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="huong_nha" class="form-label fw-bold">Hướng nhà dự kiến</label>
                            <select name="huong_nha" class="form-select @error('huong_nha') is-invalid @enderror">
                                <option value="">-- Chọn hướng nhà --</option>
                                <option value="bac"
                                    {{ old('huong_nha', $inputs['huong_nha'] ?? '') == 'bac' ? 'selected' : '' }}>Bắc
                                </option>
                                <option value="dong_bac"
                                    {{ old('huong_nha', $inputs['huong_nha'] ?? '') == 'dong_bac' ? 'selected' : '' }}>Đông
                                    Bắc</option>
                                <option value="dong"
                                    {{ old('huong_nha', $inputs['huong_nha'] ?? '') == 'dong' ? 'selected' : '' }}>Đông
                                </option>
                                <option value="dong_nam"
                                    {{ old('huong_nha', $inputs['huong_nha'] ?? '') == 'dong_nam' ? 'selected' : '' }}>Đông
                                    Nam</option>
                                <option value="nam"
                                    {{ old('huong_nha', $inputs['huong_nha'] ?? '') == 'nam' ? 'selected' : '' }}>Nam
                                </option>
                                <option value="tay_nam"
                                    {{ old('huong_nha', $inputs['huong_nha'] ?? '') == 'tay_nam' ? 'selected' : '' }}>Tây
                                    Nam</option>
                                <option value="tay"
                                    {{ old('huong_nha', $inputs['huong_nha'] ?? '') == 'tay' ? 'selected' : '' }}>Tây
                                </option>
                                <option value="tay_bac"
                                    {{ old('huong_nha', $inputs['huong_nha'] ?? '') == 'tay_bac' ? 'selected' : '' }}>Tây
                                    Bắc</option>
                            </select>
                            @error('huong_nha')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="date_range" class="form-label fw-bold">Khoảng thời gian cần xem</label>
                            <input type="text"
                                class="form-control wedding_date_range @error('date_range') is-invalid @enderror"
                                id="date_range" name="date_range" placeholder="dd/mm/yy - dd/mm/yy"
                                value="{{ old('date_range', $inputs['date_range'] ?? '') }}">
                            @error('date_range')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary btn-lg">Xem Kết Quả</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- PHẦN HIỂN THỊ KẾT QUẢ --}}
        @if (isset($birthdateInfo) && isset($huongNhaAnalysis))
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h2 class="h4 mb-0">Tổng quan Phong thủy cho Gia chủ</h2>
                </div>
                <div class="card-body">
                    <div class="row">
                        {{-- Thông tin cơ bản --}}
                        <div class="col-md-6">
                            <h5 class="text-primary">Thông tin gia chủ</h5>
                            <ul class="list-unstyled">
                                <li><strong>Ngày sinh Dương lịch:</strong> {{ $birthdateInfo['dob']->format('d/m/Y') }}
                                </li>
                                <li><strong>Ngày sinh Âm lịch:</strong> {{ $birthdateInfo['lunar_dob_str'] }}</li>
                                <li><strong>Can Chi năm sinh:</strong> {{ $birthdateInfo['can_chi_nam'] }}</li>
                                <li><strong>Bản mệnh (Ngũ hành nạp âm):</strong> {{ $birthdateInfo['menh']['hanh'] }}
                                    ({{ $birthdateInfo['menh']['napAm'] }})</li>
                            </ul>
                        </div>
                        {{-- Thông tin Bát Trạch --}}
                        <div class="col-md-6">
                            <h5 class="text-primary">Cung mệnh Bát Trạch</h5>
                            <ul class="list-unstyled">
                                <li><strong>Quái số:</strong> {{ $birthdateInfo['phong_thuy']['quai_so'] }}</li>
                                <li><strong>Cung Mệnh:</strong> <span
                                        class="fw-bold">{{ $birthdateInfo['phong_thuy']['cung_menh'] }}</span></li>
                                <li><strong>Mệnh Ngũ Hành:</strong> {{ $birthdateInfo['phong_thuy']['ngu_hanh'] }}</li>
                                <li><strong>Nhóm:</strong> <span
                                        class="fw-bold">{{ $birthdateInfo['phong_thuy']['nhom'] }}</span></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Phân tích hướng nhà --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header @if ($huongNhaAnalysis['is_good']) bg-success @else bg-danger @endif text-white">
                    <h2 class="h4 mb-0">Phân tích Hướng nhà: {{ $huongNhaAnalysis['direction_name'] }}</h2>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        @if ($huongNhaAnalysis['is_good'])
                            <h3 class="text-success">HƯỚNG HỢP TUỔI</h3>
                            <span class="badge bg-success-subtle text-success-emphasis fs-5">Cung
                                {{ $huongNhaAnalysis['quality_name'] }}</span>
                        @else
                            <h3 class="text-danger">HƯỚNG KHÔNG HỢP TUỔI</h3>
                            <span class="badge bg-danger-subtle text-danger-emphasis fs-5">Cung
                                {{ $huongNhaAnalysis['quality_name'] }}</span>
                        @endif
                    </div>
                    <p class="fs-5 text-center">{{ $huongNhaAnalysis['description'] }}</p>

                    <hr>

                    <div class="row mt-4">
                        <div class="col-md-6">
                            <h5 class="text-success">Các hướng Tốt khác</h5>
                            <ul class="list-group">
                                @foreach ($birthdateInfo['phong_thuy']['huong_tot'] as $key => $direction)
                                    <li
                                        class="list-group-item d-flex justify-content-between align-items-center @if ($direction == $huongNhaAnalysis['direction_name']) list-group-item-success @endif">
                                        <strong>{{ str_replace('_', ' ', ucwords($key, '_')) }}</strong>
                                        <span>{{ $direction }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h5 class="text-danger">Các hướng Xấu cần tránh</h5>
                            <ul class="list-group">
                                @foreach ($birthdateInfo['phong_thuy']['huong_xau'] as $key => $direction)
                                    <li
                                        class="list-group-item d-flex justify-content-between align-items-center @if ($direction == $huongNhaAnalysis['direction_name']) list-group-item-danger @endif">
                                        <strong>{{ str_replace('_', ' ', ucwords($key, '_')) }}</strong>
                                        <span>{{ $direction }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="results-container mt-5">
                {{-- Phân tích hướng nhà và các kết luận --}}

                <div class="card-header">
                    <ul class="nav nav-tabs card-header-tabs" id="yearTab" role="tablist">
                        @foreach ($resultsByYear as $year => $data)
                            <li class="nav-item" role="presentation">
                                <button class="nav-link @if ($loop->first) active @endif"
                                    id="tab-{{ $year }}-tab" data-bs-toggle="tab"
                                    data-bs-target="#tab-{{ $year }}" type="button" role="tab"
                                    aria-controls="tab-{{ $year }}"
                                    aria-selected="{{ $loop->first ? 'true' : 'false' }}">@d
                                    Năm {{ $year }}
                                </button>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="yearTabContent">
                        @foreach ($resultsByYear as $year => $data)
                    
                            <div class="tab-pane fade @if ($loop->first) show active @endif"
                                id="tab-{{ $year }}" role="tabpanel"
                                aria-labelledby="tab-{{ $year }}-tab">
                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="card p-4 ">
                                            <h4 class="mb-3">Thông tin gia chủ</h4>
                                            <ul>
                                                <li>Ngày sinh dương lịch:
                                                    <b>{{ $birthdateInfo['dob']->format('d/m/Y') }}</b>
                                                </li>
                                                <li>Ngày sinh âm lịch: <b>{{ $birthdateInfo['lunar_dob_str'] }}</b></li>
                                                <li>Giới tính: <b>{{ $inputs['gioi_tinh'] }}</b></li>
                                                <li>Tuổi: <b>{{ $birthdateInfo['can_chi_nam'] }}</b>, Mệnh:
                                                    {{ $birthdateInfo['menh']['hanh'] }}
                                                    ({{ $birthdateInfo['menh']['napAm'] }})
                                                </li>
                                                <li>Tuổi âm: <b>{{ $data['year_analysis']['lunar_age'] }}</b></li>

                                            </ul>

                                        </div>
                                    </div>
                                    {{-- @dd($data) --}}
                                    <div class="col-lg-8">
                                        <div class="card p-4 ">
                                            <h5 class="text-center">
                                                kiểm tra kim lâu - hoang ốc - tam tai
                                            </h5>
                                            <p>
                                                Kiểm tra xem năm {{ $year }} {{ $data['canchi'] }} gia chủ tuổi
                                                {{ $birthdateInfo['can_chi_nam'] }}
                                                ({{ $data['year_analysis']['lunar_age'] }} tuổi) có phạm phải Kim Lâu,
                                                Hoang Ốc, Tam Tai không?
                                            </p>
                                            <ul>
                                                <li>
                                                    {{ $data['year_analysis']['details']['kimLau']['message'] }}
                                                </li>
                                                <li>
                                                    {{ $data['year_analysis']['details']['hoangOc']['message'] }}
                                                </li>
                                                <li>
                                                    {{ $data['year_analysis']['details']['tamTai']['message'] }}
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    {{-- @dd($data) --}}
                                    <p>{!! $data['year_analysis']['description'] !!}</p>
                                </div>
                                @if (isset($huongNhaAnalysis))
                                    <div class="mt-3">
                                        <h5 class="text-primary">Kết luận</h5>

                                        <p class="mb-0">{!! $huongNhaAnalysis['conclusion'] !!}</p>
                                    </div>
                                @endif

                                @if ($data['year_analysis'])
                                    <h4 class="mt-4 mb-3">Bảng điểm chi tiết các ngày tốt</h4>

                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover text-center align-middle">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Ngày Dương Lịch</th>
                                                    <th>Ngày Âm Lịch</th>
                                                    <th>Điểm</th>
                                                    <th>Đánh giá</th>
                                                    <th>Giờ tốt (Hoàng Đạo)</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                {{-- Lọc và chỉ hiển thị những ngày có điểm TỐT hoặc RẤT TỐT --}}
                                                @php
                                                    $goodDays = array_filter($data['days'], function ($day) {
                                                        $rating = $day['day_score']['rating'];
                                                        return $rating === 'Tốt' || $rating === 'Rất tốt';
                                                    });
                                                @endphp

                                                @forelse($data['days'] as $day)
                                                    @php
                                                        if (!function_exists('getRatingClassBuildHouse')) {
                                                            function getRatingClassBuildHouse(string $rating): string
                                                            {
                                                                return match ($rating) {
                                                                    'Rất tốt' => 'table-success',
                                                                    'Tốt' => 'table-info',
                                                                    'Trung bình' => 'table-warning',
                                                                    default => 'table-danger',
                                                                };
                                                            }
                                                        }
                                                    @endphp
                                                    <tr
                                                        class="{{ getRatingClassBuildHouse($day['day_score']['rating']) }}">
                                                        <td>
                                                            <strong>{{ $day['date']->format('d/m/Y') }}</strong>
                                                            <br>
                                                            <small>{{ $day['weekday_name'] }}</small>
                                                        </td>
                                                        <td>{{ $day['full_lunar_date_str'] }}</td>
                                                        <td class="fw-bold fs-5">{{ $day['day_score']['percentage'] }}%
                                                        </td>
                                                        <td><strong>{{ $day['day_score']['rating'] }}</strong></td>
                                                        <td>
                                                            @if (!empty($day['good_hours']))
                                                                {{ implode('; ', $day['good_hours']) }}
                                                            @else
                                                                <span class="text-muted">Không có</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="5" class="text-center p-4">
                                                            <p class="mb-0">Trong khoảng thời gian bạn chọn của năm nay,
                                                                không tìm thấy ngày nào thực sự tốt để tiến hành xây dựng.
                                                            </p>
                                                            <small>Bạn có thể thử mở rộng khoảng thời gian tìm kiếm.</small>
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                @endif

                            </div>
                        @endforeach
                    </div>
                </div>





            </div>
        @endif
    </div>

@endsection
