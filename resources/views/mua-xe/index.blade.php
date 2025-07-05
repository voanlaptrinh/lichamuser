@extends('welcome')

@section('content')
    <div class="container mt-4 mb-5">
        {{-- PHẦN FORM GIỮ NGUYÊN --}}
        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <h1 class="h3 mb-0">Xem ngày mua xe - nhận xe mới</h1>
            </div>
            <div class="card-body">
                <form action="{{ route('mua-xe.check') }}" method="POST">
                    @csrf

                    <div class="row">

                        <div class="col-md-6 mb-3">
                            <label for="birthdate" class="form-label">Ngày sinh</label>
                            {{-- SỬA Ở ĐÂY: Thêm lại class "dateuser" --}}
                            <input type="text" class="form-control dateuser @error('birthdate') is-invalid @enderror"
                                id="birthdate" name="birthdate" placeholder="dd/mm/yyyy"
                                value="{{ old('birthdate', $inputs['birthdate'] ?? '') }}">
                            @error('birthdate')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>


                        <div class="col-md-6 mb-3">
                            <label for="wedding_date_range" class="form-label">dự kiến thời gian Mua xe - 
nhận xe mới</label>
                            {{-- SỬA Ở ĐÂY: Thêm lại class "wedding_date_range" --}}
                            <input type="text"
                                class="form-control wedding_date_range @error('date_range') is-invalid @enderror"
                                id="date_range" name="date_range" placeholder="dd/mm/yy - dd/mm/yy"
                                value="{{ old('date_range', $inputs['date_range'] ?? '') }}">
                            @error('date_range')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Xem Kết Quả</button>
                </form>
            </div>
        </div>

        {{-- Giả sử bạn có biến $resultsByYear sau khi form được submit --}}
        @if (isset($resultsByYear))
            <div class="results-container mt-5">

                <div class="card-header">
                    <ul class="nav nav-tabs card-header-tabs" id="yearTab" role="tablist">
                        @foreach ($resultsByYear as $year => $data)
                            <li class="nav-item" role="presentation">
                                <button class="nav-link @if ($loop->first) active @endif"
                                    id="tab-{{ $year }}-tab" data-bs-toggle="tab"
                                    data-bs-target="#tab-{{ $year }}" type="button" role="tab"
                                    aria-controls="tab-{{ $year }}"
                                    aria-selected="{{ $loop->first ? 'true' : 'false' }}">
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
                                    <div class="col-lg-12">
                                        <div class="card p-4 ">
                                            <h4 class="mb-3">Thông tin gia chủ</h4>
                                            <ul>

                                                <li>Ngày sinh dương lịch:
                                                    <b>{{ $birthdateInfo['dob']->format('d/m/Y') }}</b>
                                                </li>
                                                <li>Ngày sinh âm lịch: <b>{{ $birthdateInfo['lunar_dob_str'] }}</b></li>
                                                <li>Tuổi: <b>{{ $birthdateInfo['can_chi_nam'] }}</b>, Mệnh:
                                                    {{ $birthdateInfo['menh']['hanh'] }}
                                                    ({{ $birthdateInfo['menh']['napAm'] }})
                                                </li>
                                                <li>Tuổi âm: <b>{{ $data['year_analysis']['lunar_age'] }} Tuổi</b></li>
                                                <li>Dự kiến thời gian mua xe - nhận xe mới: Trong khoảng
                                                    {{ $date_start_end[0] }} đến {{ $date_start_end[1] }} </li>
                                            </ul>

                                        </div>
                                    </div>

                                    {{-- <p>{!! $data['year_analysis']['description'] !!}</p> --}}
                                </div>


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
