@extends('welcome')

@section('content')
    <div class="container mt-4 mb-5">
        {{-- PHẦN FORM GIỮ NGUYÊN --}}
        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <h1 class="h3 mb-0">Xem Tuổi Cưới Hỏi & Chọn Ngày Tốt</h1>
            </div>
            <div class="card-body">
                <form action="{{ route('astrology.check') }}" method="POST">
                    @csrf
                    {{-- @if ($errors->any())
                        <div class="alert alert-danger">
                            <strong>Có lỗi xảy ra! Vui lòng kiểm tra lại:</strong>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif --}}
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="groom_dob" class="form-label">Ngày sinh Chú rể</label>
                            {{-- SỬA Ở ĐÂY: Thêm lại class "dateuser" --}}
                            <input type="text" class="form-control dateuser @error('groom_dob') is-invalid @enderror"
                                id="groom_dob" name="groom_dob" placeholder="dd/mm/yyyy"
                                value="{{ old('groom_dob', $inputs['groom_dob'] ?? '') }}">
                            @error('groom_dob')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="bride_dob" class="form-label">Ngày sinh Cô dâu</label>
                            {{-- SỬA Ở ĐÂY: Thêm lại class "dateuser" --}}
                            <input type="text" class="form-control dateuser @error('bride_dob') is-invalid @enderror"
                                id="bride_dob" name="bride_dob" placeholder="dd/mm/yyyy"
                                value="{{ old('bride_dob', $inputs['bride_dob'] ?? '') }}">
                            @error('bride_dob')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="wedding_date_range" class="form-label">Khoảng ngày dự định cưới</label>
                            {{-- SỬA Ở ĐÂY: Thêm lại class "wedding_date_range" --}}
                            <input type="text"
                                class="form-control wedding_date_range @error('wedding_date_range') is-invalid @enderror"
                                id="wedding_date_range" name="wedding_date_range" placeholder="dd/mm/yy - dd/mm/yy"
                                value="{{ old('wedding_date_range', $inputs['wedding_date_range'] ?? '') }}">
                            @error('wedding_date_range')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Xem Kết Quả</button>
                </form>
            </div>
        </div>

        {{-- PHẦN KẾT QUẢ MỚI --}}
        @if (isset($resultsByYear))
            {{-- Giao diện Tab --}}
            <div class="card mt-4">
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
                                <h4 class="mb-3">Tổng quan năm {{ $year }}</h4>
                                {{-- Phân tích --}}
                                <div class="row">
                                    <div class="col-md-6">
                                        {{-- @dd($data) --}}
                                        <div
                                            class="alert {{ $data['groom_analysis']['is_bad_year'] ? 'alert-warning' : 'alert-success' }}">
                                            <h5 class="alert-heading">Chú Rể</h5>
                                            <p>
                                                Kiểm tra xem năm {{ $year }} {{ $data['canchi'] }} Chú rể tuổi
                                                {{ $groomInfo['can_chi_nam'] }} (Nam
                                                {{ $data['groom_analysis']['lunar_age'] }} tuổi) có phạm phải Kim
                                                Lâu, Hoang Ốc, Tam Tai không?
                                            </p>
                                            <ul>
                                                <li>
                                                    {{ $data['groom_analysis']['kim_lau']['message'] }}
                                                </li>
                                                <li>
                                                    {{ $data['groom_analysis']['hoang_oc']['message'] }}
                                                </li>
                                                <li>
                                                    {{ $data['groom_analysis']['tam_tai']['message'] }}
                                                </li>
                                            </ul>
                                            <p>Mệnh: {{$groomInfo['menh']['hanh']}} ({{$groomInfo['menh']['napAm']}})</p>
                                            <p>Kết luận {!! $data['groom_analysis']['description'] !!}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div
                                            class="alert {{ $data['bride_analysis']['is_bad_year'] ? 'alert-warning' : 'alert-success' }}">
                                            <h5 class="alert-heading">Cô Dâu</h5>
                                            <p>
                                                Kiểm tra xem năm {{ $year }} {{ $data['canchi'] }} Cô Dâu tuổi
                                                {{ $brideInfo['can_chi_nam'] }} (Nữ
                                                {{ $data['bride_analysis']['lunar_age'] }} tuổi) có phạm phải Kim
                                                Lâu, Hoang Ốc, Tam Tai không?
                                            </p>
                                            <ul>
                                                <li>
                                                    {{ $data['bride_analysis']['kim_lau']['message'] }}
                                                </li>
                                                <li>
                                                    {{ $data['bride_analysis']['hoang_oc']['message'] }}
                                                </li>
                                                <li>
                                                    {{ $data['bride_analysis']['tam_tai']['message'] }}
                                                </li>
                                            </ul>
                                             <p>Mệnh: {{$brideInfo['menh']['hanh']}} ({{$brideInfo['menh']['napAm']}})</p>
                                            <p>Kết luận {!! $data['bride_analysis']['description'] !!}</p>
                                        </div>
                                    </div>
                                </div>



                                {{-- Bảng kết quả chi tiết các ngày --}}
                                @if (empty($data['days']))
                                    <div class="alert alert-info">Không có ngày nào trong khoảng bạn chọn thuộc năm này.
                                    </div>
                                @else
                                    <h4 class="mb-3">Bảng điểm chi tiết các ngày</h4>
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover text-center align-middle">
                                            <thead class="table-light">
                                                <tr>
                                                    <th rowspan="2" class="align-middle">Ngày Dương</th>
                                                    <th rowspan="2" class="align-middle">Ngày Âm</th>
                                                    <th colspan="1">Điểm Chú Rể</th>
                                                    <th colspan="1">Điểm Cô Dâu</th>
                                                </tr>
                                                <tr>
                                                    <th>Điểm (%)</th>

                                                    <th>Điểm (%)</th>

                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($data['days'] as $day)
                                                    <tr>
                                                        <td>
                                                            <strong>{{ $day['date']->format('d/m/Y') }}</strong><br>
                                                            <small>Thứ {{ $day['weekday_name'] }}</small>
                                                        </td>
                                                        <td>{{ $day['lunar_date_str'] }} <br>
                                                            {{ $day['full_lunar_date_str'] }}
                                                            <p>
                                                                @if (!empty($day['good_hours']))
                                                                    <div class="mt-2">
                                                                        <span class="text-danger"><i
                                                                                class="far fa-clock"></i> <strong>Gợi ý giờ
                                                                                tốt:</strong></span><br>
                                                                        {{-- Nối các giờ lại với nhau bằng dấu chấm phẩy --}}
                                                                        <span>{{ implode('; ', $day['good_hours']) }}.</span>
                                                                    </div>
                                                                @endif

                                                            </p>
                                                        </td>
                                                        {{-- Chú rể --}}
                                                        <td>
                                                            <div class="fw-bold fs-5">
                                                                {{ $day['groom_score']['percentage'] }}



                                                            </div>
                                                        </td>

                                                        {{-- Cô dâu --}}
                                                        <td>
                                                            <div class="fw-bold fs-5">
                                                                {{ $day['bride_score']['percentage'] }}</div>
                                                        </td>

                                                    </tr>
                                                @endforeach
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
