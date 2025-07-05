@extends('welcome')

@section('content')
    <div class="container mt-4 mb-5">
        {{-- PHẦN FORM GIỮ NGUYÊN --}}
        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <h1 class="h3 mb-0">Xem ngày sang cát - cải táng - động mộ</h1>
            </div>
            <div class="card-body">
                <form action="{{ route('cai-tang.check') }}" method="POST">
                    @csrf

                    <div class="row">
                        <h4 class="text-center">THông tin người đứng lễ</h4>
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
                            <label for="wedding_date_range" class="form-label">Dự kiến cải táng</label>
                            {{-- SỬA Ở ĐÂY: Thêm lại class "wedding_date_range" --}}
                            <input type="text"
                                class="form-control wedding_date_range @error('date_range') is-invalid @enderror"
                                id="date_range" name="date_range" placeholder="dd/mm/yy - dd/mm/yy"
                                value="{{ old('date_range', $inputs['date_range'] ?? '') }}">
                            @error('date_range')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <h4 class="text-center">
                            Thông tin người mất
                        </h4>
                        <div class="col-md-6">
                            <label for="birth_mat" class="form-label">Năm sinh âm lịch</label>
                            {{-- SỬA Ở ĐÂY --}}
                            <select name="birth_mat" id="birth_mat" class="form-select">
                                @php
                                    // Lấy giá trị đã chọn, ưu tiên old() sau đó mới đến $inputs
                                    $selectedBirthYear = old('birth_mat', $inputs['birth_mat'] ?? null);
                                @endphp
                                @for ($year = date('Y'); $year >= 1800; $year--)
                                    <option value="{{ $year }}" {{ $selectedBirthYear == $year ? 'selected' : '' }}>
                                        {{ $year }}
                                    </option>
                                @endfor
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="nam_mat" class="form-label">Năm Mất âm lịch</label>
                            {{-- SỬA Ở ĐÂY --}}
                            <select name="nam_mat" id="nam_mat" class="form-select">
                                @php
                                    // Lấy giá trị đã chọn, ưu tiên old() sau đó mới đến $inputs
                                    $selectedDeathYear = old('nam_mat', $inputs['nam_mat'] ?? null);
                                @endphp
                                @for ($year = date('Y'); $year >= 1800; $year--)
                                    <option value="{{ $year }}" {{ $selectedDeathYear == $year ? 'selected' : '' }}>
                                        {{ $year }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    <div class="text-end mt-4">
                        <button type="submit" class="btn btn-primary">Xem Kết Quả</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- PHẦN HIỂN THỊ KẾT QUẢ --}}
        @if (isset($resultsByYear))
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
                            id="tab-{{ $year }}" role="tabpanel" aria-labelledby="tab-{{ $year }}-tab">
                            <div class="row">
                                <div class="card shadow-sm mb-4" style="border: 2px dotted #0d6efd;">
                                    <div class="card-header bg-white border-0 text-center">
                                        <h2 class="h4 mb-0 text-uppercase" style="color: #0d6efd;">Thông tin cơ bản về người
                                            xem</h2>
                                    </div>
                                    <div class="card-body">
                                        {{-- Thông tin người đứng lễ --}}
                                        <h5 class="fw-bold">Thông tin người đứng lễ</h5>
                                        <ul class="list-unstyled ps-3">

                                            <li class="mb-2">
                                                <span class="text-warning me-2">◆</span>
                                                <strong>Ngày sinh dương lịch:</strong> {{ $hostInfo['dob_str'] }}
                                            </li>
                                            <li class="mb-2">
                                                <span class="text-warning me-2">◆</span>
                                                <strong>Ngày sinh âm lịch:</strong> {{ $hostInfo['lunar_dob_str'] }}
                                                ({{ \App\Helpers\KhiVanHelper::canchiNam($hostInfo['dob_obj']->year) }})
                                            </li>

                                            <li class="mb-2">
                                                <span class="text-warning me-2">◆</span>
                                                <strong>Tuổi âm:</strong> {{ $data['host_analysis']['lunar_age'] }} tuổi
                                            </li>
                                        </ul>

                                        {{-- Thông tin người mất --}}
                                        <h5 class="fw-bold mt-4">Thông tin về người mất</h5>
                                        <ul class="list-unstyled ps-3">
                                            <li class="mb-2">
                                                <span class="text-warning me-2">◆</span>
                                                <strong>Năm sinh âm lịch:</strong> {{ $deceasedInfo['birth_year_lunar'] }}
                                                ({{ $deceasedInfo['birth_can_chi'] }})
                                            </li>
                                            <li class="mb-2">
                                                <span class="text-warning me-2">◆</span>
                                                <strong>Năm mất âm lịch:</strong> {{ $deceasedInfo['death_year_lunar'] }}
                                                ({{ $deceasedInfo['death_can_chi'] }})
                                            </li>
                                            <li class="mb-2">
                                                <span class="text-warning me-2">◆</span>
                                                <strong>Tuổi:</strong> {{ $deceasedInfo['birth_can_chi'] }}
                                            </li>
                                        </ul>

                                        {{-- Thời gian dự kiến --}}

                                    </div>

                                </div>
                            </div>
                            <div class="row" style="border: 2px dotted #0d6efd;">
                                <div class="col-lg-6">
                                    <div class="p-3">
                                        <h5>Xem tuổi người đứng lễ</h5>
                                        @php $deceasedResult = $data['deceased_analysis']; @endphp
                                        <div>
                                            Kiểm tra người đừng lễ sinh năm {{ $hostInfo['dob_obj']->year }}
                                            ({{ \App\Helpers\KhiVanHelper::canchiNam($hostInfo['dob_obj']->year) }})
                                            có gặp hạn Kim Lâu, Tam Tai, Hoang Ốc hoặc xung với năm
                                            {{ $deceasedResult['check_year_can_chi'] }} không?
                                        </div>
                                        <ul>

                                            <li>
                                                @if ($data['host_analysis']['kimLau']['is_bad'])
                                                    {{ $data['host_analysis']['kimLau']['message'] }}
                                                @else
                                                    Không phạm kim lâu
                                                @endif
                                            </li>
                                            <li>

                                                @if ($data['host_analysis']['hoangOc']['is_bad'])
                                                    Phạm Hoang Ốc {{ $data['host_analysis']['hoangOc']['message'] }}
                                                @else
                                                    Không phạm hoang ốc
                                                @endif

                                            </li>
                                            <li>
                                                @if ($data['host_analysis']['tamTai']['is_bad'])
                                                    {{ $data['host_analysis']['tamTai']['message'] }}
                                                @else
                                                    Không phạm tam tai
                                                @endif

                                            </li>
                                            <li>

                                                @if ($data['host_analysis']['thaiTue']['is_pham'])
                                                    Năm {{ $deceasedResult['check_year_can_chi'] }}
                                                    ({{ $deceasedResult['check_year'] }}) xung với tuổi
                                                    {{ \App\Helpers\KhiVanHelper::canchiNam($hostInfo['dob_obj']->year) }}
                                                    ({{ $hostInfo['dob_obj']->year }})
                                                @else
                                                    Năm {{ $deceasedResult['check_year_can_chi'] }}
                                                    ({{ $deceasedResult['check_year'] }}) Không xung với tuổi
                                                    {{ \App\Helpers\KhiVanHelper::canchiNam($hostInfo['dob_obj']->year) }}
                                                    ({{ $hostInfo['dob_obj']->year }})
                                                @endif

                                            </li>
                                        </ul>
                                        <h5>Kết luận</h5>
                                        <div>
                                            {!! $data['host_analysis']['description'] !!}
                                        </div>
                                    </div>

                                </div>
                                <div class="col-lg-6">
                                    <div class="p-3">
                                        <h5>Xem tuổi người mất</h5>
                                        <p>Kiểm tra năm <strong>{{ $deceasedResult['check_year_can_chi'] }}
                                                ({{ $deceasedResult['check_year'] }})
                                            </strong> có phạm Thái Tuế, Tuế Phá (lục xung) với
                                            tuổi người mất hay không?</p>
                                        <strong>Người mất sinh năm {{ $deceasedResult['deceased_birth_year'] }}
                                            ({{ $deceasedResult['deceased_can_chi'] }}):</strong>
                                        <ul class="list-unstyled ps-3">
                                            <li>→ Năm {{ $deceasedResult['check_year_can_chi'] }}
                                                @if ($deceasedResult['is_thai_tue'])
                                                    <strong class="text-danger">Phạm Thái Tuế</strong>
                                                @else
                                                    <strong class="text-success">Không phạm Thái Tuế</strong>
                                                @endif
                                            </li>
                                            <li>→ @if ($deceasedResult['is_tue_pha'])
                                                    <strong class="text-danger">Phạm Tuế Phá (xung Thái Tuế)</strong>
                                                @else
                                                    <strong class="text-success">Không phạm Tuế Phá</strong>
                                                @endif
                                            </li>
                                        </ul>
                                        <p class="mt-3"><strong>Kết luận:</strong></p>
                                        <div
                                            class="alert @if ($deceasedResult['is_bad']) alert-danger @else alert-success @endif">
                                            {!! $deceasedResult['conclusion'] !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <h4 class="mt-4 mb-3">Bảng điểm chi tiết các ngày tốt</h4>
                            @if ($data['days'])
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
                                                <tr class="{{ getRatingClassBuildHouse($day['day_score']['rating']) }}">
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
        @endif
    </div>

@endsection
