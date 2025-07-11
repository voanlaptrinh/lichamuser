@extends('welcome')

@section('content')
      <div class="row">
        <div class="col-lg-8">
            <div class=" mt-3 boder_fix">
                <div class="breadcrumb1">
                    <ul>
                        <li class="breadcrumb1-item"><a href="/">Trang chủ</a></li>
                        <li class="breadcrumb1-item">Xem ngày tốt xấu</li>
                        <li class="breadcrumb1-item">Xem ngày nhập trạch</li>
                    </ul>
                </div>
                <div class="page-head">
                    <h1 class="page-head-title h4 h3-sm h2-md h1-lg ">
                        XEM NGÀY TỐT NHẬP TRẠCH THEO TUỔI GIA CHỦ
                    </h1>
                    <p class="pb-2">Xem ngày nhập trạch giúp ta có thể tạo ra một trường năng lượng tuchs cực với các lợi
                        ích, Khai Báo Với Thần Linh, Kích Hoạt Năng Lượng Tốt, Hóa Giải Khí Xấu, Nền Tảng Gia Đạo Êm Ấm
                    </p>
                </div>
                <div class="card">

                    <div class="card-body">

                        <form action="{{ route('nhap-trach.check') }}" method="POST" class="form_dmy date-picker">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="birthdate" class="form-label fw-bold">Ngày sinh gia chủ <span
                                            class="text-danger">*</span></label>
                                    <input type="text"
                                        class="form-control dateuser @error('birthdate') is-invalid @enderror"
                                        id="birthdate" name="birthdate" placeholder="dd/mm/yyyy"
                                        value="{{ old('birthdate', $inputs['birthdate'] ?? '') }}">
                                    @error('birthdate')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="gioi_tinh" class="form-label fw-bold">Giới tính <span
                                            class="text-danger">*</span></label>
                                    {{-- SỬA VALUE: 'nu' -> 'nữ' để khớp với helper --}}
                                    <select name="gioi_tinh" class="form-select @error('gioi_tinh') is-invalid @enderror">
                                        <option value="">-- Chọn giới tính --</option>
                                        <option value="nam"
                                            {{ old('gioi_tinh', $inputs['gioi_tinh'] ?? '') == 'nam' ? 'selected' : '' }}>
                                            Nam
                                        </option>
                                        <option value="nữ"
                                            {{ old('gioi_tinh', $inputs['gioi_tinh'] ?? '') == 'nữ' ? 'selected' : '' }}>Nữ
                                        </option>
                                    </select>
                                    @error('gioi_tinh')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="huong_nha" class="form-label fw-bold">Hướng nhà dự kiến <span
                                            class="text-danger">*</span></label>
                                    <select name="huong_nha" class="form-select @error('huong_nha') is-invalid @enderror">
                                        <option value="">-- Chọn hướng nhà --</option>
                                        <option value="bac"
                                            {{ old('huong_nha', $inputs['huong_nha'] ?? '') == 'bac' ? 'selected' : '' }}>
                                            Bắc
                                        </option>
                                        <option value="dong_bac"
                                            {{ old('huong_nha', $inputs['huong_nha'] ?? '') == 'dong_bac' ? 'selected' : '' }}>
                                            Đông
                                            Bắc</option>
                                        <option value="dong"
                                            {{ old('huong_nha', $inputs['huong_nha'] ?? '') == 'dong' ? 'selected' : '' }}>
                                            Đông
                                        </option>
                                        <option value="dong_nam"
                                            {{ old('huong_nha', $inputs['huong_nha'] ?? '') == 'dong_nam' ? 'selected' : '' }}>
                                            Đông
                                            Nam</option>
                                        <option value="nam"
                                            {{ old('huong_nha', $inputs['huong_nha'] ?? '') == 'nam' ? 'selected' : '' }}>
                                            Nam
                                        </option>
                                        <option value="tay_nam"
                                            {{ old('huong_nha', $inputs['huong_nha'] ?? '') == 'tay_nam' ? 'selected' : '' }}>
                                            Tây
                                            Nam</option>
                                        <option value="tay"
                                            {{ old('huong_nha', $inputs['huong_nha'] ?? '') == 'tay' ? 'selected' : '' }}>
                                            Tây
                                        </option>
                                        <option value="tay_bac"
                                            {{ old('huong_nha', $inputs['huong_nha'] ?? '') == 'tay_bac' ? 'selected' : '' }}>
                                            Tây
                                            Bắc</option>
                                    </select>
                                    @error('huong_nha')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="date_range" class="form-label fw-bold">Khoảng thời gian cần xem <span
                                            class="text-danger">*</span></label>
                                    <input type="text"
                                        class="form-control wedding_date_range @error('date_range') is-invalid @enderror"
                                        id="date_range" name="date_range" placeholder="dd/mm/yy - dd/mm/yy"
                                        value="{{ old('date_range', $inputs['date_range'] ?? '') }}">
                                    @error('date_range')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="text-end">

                                <button type="submit" class="btn btn-outline-danger">Xem Kết Quả</button>
                            </div>
                        </form>
                        @if (isset($birthdateInfo) && isset($huongNhaAnalysis))
                            <div class="card shadow-sm mb-4">
                                <div class="card-header bg-primary text-white">
                                    <h2 class="h4 mb-0">Tổng quan Phong thủy cho Gia chủ</h2>
                                </div>
                                <div class="card-body ">
                                    <div class="row">

                                        <div class="col-md-6">
                                            <h5 class="text-primary">Thông tin gia chủ</h5>
                                            <ul class="list-unstyled">
                                                <li><strong>Ngày sinh Dương lịch:</strong>
                                                    {{ $birthdateInfo['dob']->format('d/m/Y') }}
                                                </li>
                                                <li><strong>Ngày sinh Âm lịch:</strong>
                                                    {{ $birthdateInfo['lunar_dob_str'] }}</li>
                                                <li><strong>Can Chi năm sinh:</strong>
                                                    {{ $birthdateInfo['can_chi_nam'] }}
                                                </li>
                                                <li><strong>Bản mệnh (Ngũ hành nạp âm):</strong>
                                                    {{ $birthdateInfo['menh']['hanh'] }}
                                                    ({{ $birthdateInfo['menh']['napAm'] }})</li>
                                            </ul>
                                        </div>

                                        <div class="col-md-6">
                                            <h5 class="text-primary">Cung mệnh Bát Trạch</h5>
                                            <ul class="list-unstyled">
                                                <li><strong>Quái số:</strong>
                                                    {{ $birthdateInfo['phong_thuy']['quai_so'] }}
                                                </li>
                                                <li><strong>Cung Mệnh:</strong> <span
                                                        class="fw-bold">{{ $birthdateInfo['phong_thuy']['cung_menh'] }}</span>
                                                </li>
                                                <li><strong>Mệnh Ngũ Hành:</strong>
                                                    {{ $birthdateInfo['phong_thuy']['ngu_hanh'] }}</li>
                                                <li><strong>Nhóm:</strong> <span
                                                        class="fw-bold">{{ $birthdateInfo['phong_thuy']['nhom'] }}</span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Phân tích hướng nhà --}}
                            <div class="card shadow-sm mb-4">
                                <div
                                    class="card-header @if ($huongNhaAnalysis['is_good']) bg-success @else bg-danger @endif text-white">
                                    <h2 class="h4 mb-0">Phân tích Hướng nhà:
                                        {{ $huongNhaAnalysis['direction_name'] }}</h2>
                                </div>
                                <div class="card-body">
                                    <div class="text-center mb-3">
                                        @if ($huongNhaAnalysis['is_good'])
                                            <h3 class="text-success">HƯỚNG HỢP TUỔI</h3>
                                            <button type="button" class="btn btn-outline-success">Cung
                                                {{ $huongNhaAnalysis['quality_name'] }}</button>
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
                                <div class="card-header">
                                    <ul class="nav nav-tabs flex-wrap w-100 text-center customers-nav-tabs" id="yearTab" role="tablist">
                                        @foreach ($resultsByYear as $year => $data)
                                            <li class="nav-item flex-fill border-top" role="presentation">
                                                <button class="nav-link w-100 @if ($loop->first) active @endif"
                                                    id="tab-{{ $year }}-tab" data-bs-toggle="tab"
                                                    data-bs-target="#tab-{{ $year }}" type="button"
                                                    role="tab" aria-controls="tab-{{ $year }}"
                                                    aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                                                    Năm {{ $year }}
                                                </button>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                                <div class="card-body p-0 mt-2">
                                    <div class="tab-content" id="yearTabContent">
                                        @foreach ($resultsByYear as $year => $data)
                                            <div class="tab-pane fade @if ($loop->first) show active @endif"
                                                id="tab-{{ $year }}" role="tabpanel"
                                                aria-labelledby="tab-{{ $year }}-tab">
                                                <div class="row g-2">
                                                    {{-- Các phần thông tin giữ nguyên --}}
                                                </div>
                                                {{-- Các phần thông tin khác giữ nguyên --}}

                                                @if (!empty($data['days']))
                                                    {{-- SỬA Ở ĐÂY: Thêm dropdown sắp xếp --}}
                                                    <div class="d-flex justify-content-between align-items-center mb-3 mt-4">
                                                        <h4 class="mb-0 title-date-chitite-tot">Bảng điểm chi tiết
                                                            các ngày tốt</h4>
                                                        <div class="d-flex align-items-center ">
                                                            <select class="form-select sort-select">
                                                                <option value="date" selected>Theo ngày (Mặc định)</option>
                                                                <option value="score_desc">Cao đến thấp</option>
                                                                <option value="score_asc">Thấp đến cao</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="table-responsive">
                                                        <table
                                                            class="table table-bordered table-hover text-center align-middle tabl-repont">
                                                            <thead class="table-light">
                                                                <tr>
                                                                    <th>Ngày Dương Lịch</th>
                                                                    <th>Ngày Âm Lịch</th>
                                                                    <th>Điểm</th>
                                                                    <th>Đánh giá</th>
                                                                    <th>Giờ tốt (Hoàng Đạo)</th>
                                                                </tr>
                                                            </thead>
                                                            {{-- SỬA Ở ĐÂY: Thêm class cho tbody --}}
                                                            <tbody class="results-tbody">
                                                                @forelse($data['days'] as $day)
                                                                    @php
                                                                        if (!function_exists('getRatingClassBuildHouse')) {
                                                                            function getRatingClassBuildHouse(string $rating): string {
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
                                                                        <td class="fw-bold">
                                                                            {{ $day['day_score']['percentage'] }}%
                                                                        </td>
                                                                        <td><strong>{{ $day['day_score']['rating'] }}</strong>
                                                                        </td>
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
                                                                            <p class="mb-0">Trong khoảng thời gian bạn chọn của năm nay, không tìm thấy ngày nào thực sự tốt.</p>
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
                        @else
                            <h4>
                                Xem Ngày Tốt Nhập Trạch Năm 2024 Theo Tuổi Gia Chủ
                            </h4>
                            <p><b>Khai Mở Vận Hội Mới, An Cư Lạc Nghiệp, Vạn Sự Hanh Thông</b></p>
                            <p>Lễ Nhập trạch không chỉ đơn thuần là việc dọn đến một ngôi nhà mới. Đây là một dấu mốc thiêng
                                liêng, là nghi lễ "khai báo" chính thức với Thổ Công, Thổ Địa và các vị Gia tiên rằng gia
                                đình sẽ bắt đầu cuộc sống tại đây. Đây là thời khắc ngôi nhà thực sự "có sự sống", được nạp
                                năng lượng của gia chủ.</p>
                            <p>Chọn được ngày giờ hoàng đạo để nhập trạch chính là chìa khóa để khởi đầu một chặng đường mới
                                đầy may mắn, tài lộc và bình an.</p>
                            <h4>
                                Lễ Nhập Trạch Quan Trọng Như Thế Nào?
                            </h4>
                            <p>Một khởi đầu đúng đắn sẽ tạo ra một trường năng lượng tích cực, ảnh hưởng sâu sắc đến mọi mặt
                                cuộc sống trong ngôi nhà mới.</p>
                            <table class="table table-bordered table-actent">
                                <tbody>
                                    <tr>
                                        <td>
                                            <b>Khai Báo Với Thần Linh</b>
                                        </td>
                                        <td>
                                            Là lời trình diện trang trọng với Thần linh cai quản khu đất, mong nhận được sự
                                            che chở, phù hộ, giúp gia đình an cư, tránh được sự quấy nhiễu của các yếu tố
                                            tiêu cực.
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <b>Kích Hoạt Năng Lượng Tốt</b>
                                        </td>
                                        <td>
                                            Vào nhà đúng ngày giờ hoàng đạo giúp kích hoạt các luồng sinh khí thịnh vượng,
                                            mang lại may mắn, sức khỏe và tài lộc cho tất cả các thành viên.
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <b>Hóa Giải Khí Xấu</b>
                                        </td>
                                        <td>
                                            Mỗi ngôi nhà mới đều có thể tồn tại các "chướng khí" từ quá trình xây dựng hoặc
                                            từ trước đó. Lễ nhập trạch đúng cách giúp xua đuổi, thanh lọc các nguồn năng
                                            lượng xấu này.
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <b>Nền Tảng Gia Đạo Êm Ấm</b>
                                        </td>
                                        <td>
                                            Một khởi đầu hài hòa, thuận lợi sẽ tạo ra tâm lý an yên, vui vẻ, là nền tảng cho
                                            một gia đình hòa thuận, hạnh phúc và con cháu phát triển thuận lợi.
                                        </td>
                                    </tr>

                                </tbody>
                            </table>
                            <h4>
                                Phương Pháp Luận Giải Của Chúng Tôi
                            </h4>
                            <p>Để tìm ra ngày tốt nhất cho bạn, công cụ của chúng tôi không chỉ dựa vào lịch vạn niên thông
                                thường mà còn phân tích sâu các yếu tố phong thủy phức tạp:</p>
                            <ul style="list-style: circle; margin-left: 2rem;">
                                <li><b>Phân Tích Mệnh Trạch Gia Chủ:</b> Dựa trên ngày tháng năm sinh của bạn để tìm ra ngày
                                    có Ngũ hành, Can Chi tương sinh, tương hợp, tránh tuyệt đối các ngày xung khắc với bản
                                    mệnh.</li>
                                <li><b>Loại Trừ Các Ngày Đại Kỵ:</b> Tự động lọc bỏ các ngày xấu cho việc nhập trạch như Tam
                                    Nương, Nguyệt Kỵ, Thọ Tử, Sát Chủ... đảm bảo sự an toàn tuyệt đối.</li>
                                <li><b>Ưu Tiên Ngày Cát Tinh Hội Tụ:</b> Lựa chọn các ngày có nhiều sao tốt (Cát Tinh) chiếu
                                    mệnh như Thiên Hỷ, Thiên Quý, Sinh Khí, Yếu An... để nhân đôi may mắn.</li>
                                <li><b>Gợi Ý Giờ Hoàng Đạo:</b> Sau khi có ngày tốt, chúng tôi sẽ gợi ý những khung giờ vàng
                                    trong ngày để bạn thực hiện nghi lễ, giúp mọi việc được trọn vẹn viên mãn.</li>
                            </ul>
                        @endif
                    </div>
                </div>
            </div>



        </div>
        <div class="col-lg-4">
            @include('assinbar')
        </div>
    </div>
     <script>
        document.addEventListener('DOMContentLoaded', function() {
            const allTabPanes = document.querySelectorAll('.tab-pane');

            allTabPanes.forEach(tabPane => {
                const sortSelect = tabPane.querySelector('.sort-select');
                const tableBody = tabPane.querySelector('.results-tbody');

                if (!sortSelect || !tableBody) {
                    return;
                }

                const rows = Array.from(tableBody.querySelectorAll('tr'));

                rows.forEach((row, index) => {
                    row.dataset.originalIndex = index;
                });

                function getScore(row) {
                    const scoreCell = row.querySelector('td.fw-bold');
                    if (scoreCell) {
                        return parseInt(scoreCell.textContent, 10);
                    }
                    return 0;
                }

                sortSelect.addEventListener('change', function() {
                    const sortValue = this.value;

                    rows.sort((rowA, rowB) => {
                        if (sortValue === 'score_desc') {
                            return getScore(rowB) - getScore(rowA);
                        } else if (sortValue === 'score_asc') {
                            return getScore(rowA) - getScore(rowB);
                        } else {
                            return rowA.dataset.originalIndex - rowB.dataset.originalIndex;
                        }
                    });

                    rows.forEach(row => {
                        tableBody.appendChild(row);
                    });
                });
            });
        });
    </script>
@endsection
