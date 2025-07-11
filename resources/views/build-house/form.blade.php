@extends('welcome')

@section('content')
    <div class="row">
        <div class="col-lg-8">
            <div class=" mt-3 boder_fix">
                <div class="breadcrumb1">
                    <ul>
                        <li class="breadcrumb1-item"><a href="/">Trang chủ</a></li>
                        <li class="breadcrumb1-item">Xem ngày tốt xấu</li>
                        <li class="breadcrumb1-item">Xem ngày mua nhà</li>
                    </ul>
                </div>
                <div class="page-head">
                    <h1 class="page-head-title h4 h3-sm h2-md h1-lg ">
                        XEM NGÀY TỐT ĐỂ MUA NHÀ - AN GIA LẬP NGHIỆP
                    </h1>
                    <p class="pb-2">Mua nhà là việc trọng đại của cả đời người, không chỉ là nơi che mưa che nắng mà còn
                        là nền tảng cho sự nghiệp và hạnh phúc gia đình. Theo quan niệm của người Á Đông, việc chọn được
                        "ngày lành tháng tốt" để thực hiện giao dịch sẽ mang lại may mắn, tài lộc và sự bình an cho gia chủ.
                    </p>
                </div>
                <div class="card">
                    <div class="card-body ">

                        <form action="{{ route('buy-house.check') }}" method="POST" class="form_dmy date-picker">
                            @csrf

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="birthdate" class="form-label">Ngày sinh <span
                                            class="text-danger">*</span></label>
                                    {{-- SỬA Ở ĐÂY: Thêm lại class "dateuser" --}}
                                    <input type="text"
                                        class="form-control dateuser @error('birthdate') is-invalid @enderror"
                                        id="birthdate" name="birthdate" placeholder="dd/mm/yyyy"
                                        value="{{ old('birthdate', $inputs['birthdate'] ?? '') }}">
                                    @error('birthdate')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>


                                <div class="col-md-6 mb-3">
                                    <label for="wedding_date_range" class="form-label">Dự kiến thời gian mua <span
                                            class="text-danger">*</span></label>
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
                            <div class="text-end">

                                <button type="submit" class="btn btn-outline-danger">Xem Kết Quả</button>
                            </div>
                        </form>
                        @if (isset($resultsByYear))
                            <div class="results-container mt-5">

                                <div class="card-header">
                                    <ul class="nav nav-tabs flex-wrap w-100 text-center customers-nav-tabs" id="yearTab"
                                        role="tablist">
                                        @foreach ($resultsByYear as $year => $data)
                                            <li class="nav-item flex-fill border-top" role="presentation">
                                                <button class="nav-link w-100 @if ($loop->first) active @endif"
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
                                <div class="card-body p-0 mt-3">
                                    <div class="tab-content" id="yearTabContent">
                                        @foreach ($resultsByYear as $year => $data)
                                            <div class="tab-pane fade @if ($loop->first) show active @endif"
                                                id="tab-{{ $year }}" role="tabpanel"
                                                aria-labelledby="tab-{{ $year }}-tab">
                                                <div class="row g-2">
                                                    <div class="col-lg-12">
                                                        <div class="card p-2 ">
                                                            <h4 class="mb-3 text-center">Thông tin gia chủ</h4>
                                                            <ul>
                                                                <li>Ngày sinh dương lịch:
                                                                    <b>{{ $birthdateInfo['dob']->format('d/m/Y') }}</b>
                                                                </li>
                                                                <li>Ngày sinh âm lịch:
                                                                    <b>{{ $birthdateInfo['lunar_dob_str'] }}</b>
                                                                </li>
                                                                <li>Tuổi: <b>{{ $birthdateInfo['can_chi_nam'] }}</b>,
                                                                    Mệnh:
                                                                    {{ $birthdateInfo['menh']['hanh'] }}
                                                                    ({{ $birthdateInfo['menh']['napAm'] }})
                                                                </li>
                                                                <li>Tuổi âm:
                                                                    <b>{{ $data['year_analysis']['lunar_age'] }}</b>
                                                                </li>

                                                            </ul>

                                                        </div>
                                                    </div>

                                                    <div class="col-lg-12">
                                                        <div class="card p-2 ">
                                                            <h5 class="text-center">
                                                                kiểm tra kim lâu - hoang ốc - tam tai
                                                            </h5>
                                                            <p>
                                                                Kiểm tra xem năm {{ $year }}
                                                                {{ $data['canchi'] }}
                                                                gia chủ
                                                                tuổi
                                                                {{ $birthdateInfo['can_chi_nam'] }}
                                                                ({{ $data['year_analysis']['lunar_age'] }} tuổi) có phạm
                                                                phải Kim Lâu,
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


                                                @if (!empty($data['days']))
                                                    <div class="d-flex justify-content-between mt-3">
                                                        <div>
                                                            <h5 class="mb-3 title-date-chitite-tot">Bảng điểm chi tiết
                                                                các ngày tốt</h4>
                                                        </div>

                                                        <div class="d-flex align-items-center">
                                                            {{-- SỬA Ở ĐÂY: Đổi id="sort-select" thành class="sort-select" --}}
                                                            <select class="form-select sort-select">
                                                                <option value="date" selected>Theo ngày (Mặc định)
                                                                </option>
                                                                <option value="score_desc"> Cao đến thấp</option>
                                                                <option value="score_asc">Thấp đến cao</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="table-responsive mt-2">
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
                                                            {{-- SỬA Ở ĐÂY: Đổi id="results-tbody" thành class="results-tbody" --}}
                                                            <tbody class="results-tbody">
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
                                                                @forelse($data['days'] as $day)
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
                                                                            <p class="mb-0">Trong khoảng thời gian bạn
                                                                                chọn của năm
                                                                                nay,
                                                                                không tìm thấy ngày nào thực sự tốt để tiến
                                                                                hành xây
                                                                                dựng.
                                                                            </p>
                                                                            <small>Bạn có thể thử mở rộng khoảng thời gian
                                                                                tìm
                                                                                kiếm.</small>
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
                            {{-- Giữ nguyên nội dung mặc định --}}
                            <h5>
                                Tại Sao Cần Chọn Ngày Đẹp Để Mua Nhà?
                            </h5>
                            <p>Việc khởi đầu suôn sẻ sẽ tạo ra nguồn năng lượng tích cực, ảnh hưởng đến vận khí của cả gia
                                đình trong ngôi nhà mới.</p>
                            <table class="table table-bordered table-actent">
                                <tbody>
                                    <tr>
                                        <td>
                                            <b> An Tâm Vững Chắc</b>
                                        </td>
                                        <td>
                                            Bắt đầu mọi việc vào ngày tốt giúp gia chủ có tâm lý vững vàng, tin tưởng vào
                                            một tương lai tốt đẹp.
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <b> Thu Hút Tài Lộc</b>
                                        </td>
                                        <td>
                                            Ngày hợp mệnh giúp chiêu tài, kích lộc, tạo nền tảng để gia chủ làm ăn phát đạt,
                                            thịnh vượng.
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <b> Gia Đạo Hòa Thuận</b>
                                        </td>
                                        <td>
                                            Năng lượng hòa hợp của ngày lành giúp các thành viên trong gia đình sống vui vẻ,
                                            yêu thương và gắn kết.
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <b> Sự Nghiệp Hanh Thông</b>
                                        </td>
                                        <td>
                                            "An cư" rồi mới "lạc nghiệp". Một khởi đầu tốt đẹp sẽ là bước đệm cho sự nghiệp
                                            thăng tiến.
                                        </td>
                                    </tr>

                                </tbody>
                            </table>
                            <h5>Phương Pháp Luận Giải Của Chúng Tôi</h5>
                            <p>Để đưa ra kết quả chính xác và đáng tin cậy, công cụ của chúng tôi phân tích dựa trên các
                                nguyên tắc cốt lõi của Kinh Dịch và Phong Thủy học:</p>
                            <ul style="list-style: circle; margin-left: 2rem;">
                                <li><b>Dựa trên ngày tháng năm sinh của gia chủ:</b>Tính toán Ngũ hành bản mệnh, Thiên Can,
                                    Địa Chi để tìm ra các ngày tương sinh, tương hợp.</li>
                                <li><b>Loại trừ các ngày Bách Kỵ:</b> Tự động loại bỏ các ngày xấu, đại kỵ cho việc lớn như:
                                    <b>Tam Nương, Nguyệt Kỵ, Sát Chủ, Thọ Tử, Trùng Tang, Trùng Phục...</b>
                                </li>
                                <li><b>Chọn lọc các Sao Tốt (Cát Tinh):</b>Ưu tiên những ngày có nhiều sao tốt chiếu mệnh
                                    như:<b>Thiên Quý, Thiên Đức, Nguyệt Đức, Thiên Hỷ, Sinh Khí...</b></li>
                                <li><b>Đối chiếu theo Thập Nhị Trực:</b>Lựa chọn các ngày có Trực tốt cho việc giao dịch,
                                    mua bán như<b>Trực Mãn, Trực Thành, Trực Khai.</b></li>
                                <li><b>Gợi ý Giờ Hoàng Đạo:</b>Sau khi chọn được ngày tốt, chúng tôi sẽ gợi ý các khung giờ
                                    vàng trong ngày để tiến hành ký kết, đặt cọc.</li>
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
    {{-- SỬA Ở ĐÂY: Toàn bộ khối script được viết lại để hoạt động với nhiều tab --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 1. Tìm tất cả các container của từng tab (mỗi `.tab-pane` là một tab).
            const allTabPanes = document.querySelectorAll('.tab-pane');

            // 2. Lặp qua mỗi tab để thiết lập trình sắp xếp riêng cho nó.
            allTabPanes.forEach(tabPane => {
                // Tìm dropdown và tbody *chỉ bên trong tab hiện tại* bằng class.
                const sortSelect = tabPane.querySelector('.sort-select');
                const tableBody = tabPane.querySelector('.results-tbody');

                // 3. Nếu tab này không có bảng kết quả, bỏ qua để tránh lỗi.
                if (!sortSelect || !tableBody) {
                    return;
                }

                // 4. Lấy tất cả các hàng <tr> của bảng này và chuyển thành mảng.
                const rows = Array.from(tableBody.querySelectorAll('tr'));

                // 5. Lưu lại thứ tự ban đầu để có thể quay về sắp xếp theo ngày.
                rows.forEach((row, index) => {
                    row.dataset.originalIndex = index;
                });

                // 6. Hàm tiện ích để lấy điểm số từ một hàng.
                function getScore(row) {
                    const scoreCell = row.querySelector('td.fw-bold');
                    if (scoreCell) {
                        // Loại bỏ ký tự '%' và chuyển chuỗi thành số nguyên.
                        return parseInt(scoreCell.textContent.replace('%', ''), 10);
                    }
                    return 0; // Trả về 0 nếu không tìm thấy điểm.
                }

                // 7. Gắn sự kiện 'change' vào dropdown của *tab này*.
                sortSelect.addEventListener('change', function() {
                    const sortValue = this.value; // Lấy giá trị đã chọn (vd: 'score_desc').

                    // Sắp xếp mảng `rows` dựa trên lựa chọn.
                    rows.sort((rowA, rowB) => {
                        if (sortValue === 'score_desc') {
                            // Sắp xếp điểm giảm dần (cao đến thấp).
                            return getScore(rowB) - getScore(rowA);
                        } else if (sortValue === 'score_asc') {
                            // Sắp xếp điểm tăng dần (thấp đến cao).
                            return getScore(rowA) - getScore(rowB);
                        } else {
                            // Mặc định ('date'): Sắp xếp theo thứ tự ban đầu.
                            return rowA.dataset.originalIndex - rowB.dataset.originalIndex;
                        }
                    });

                    // 8. Cập nhật lại DOM: Chèn các hàng đã sắp xếp vào lại tbody.
                    // Việc này sẽ tự động di chuyển các phần tử đến vị trí mới.
                    rows.forEach(row => {
                        tableBody.appendChild(row);
                    });
                });
            });
        });
    </script>
@endsection