@extends('welcome')

@section('content')
    <div class="row">
        <div class="col-lg-8">
            <div class=" mt-3 boder_fix">
                <div class="breadcrumb1">
                    <ul>
                        <li class="breadcrumb1-item"><a href="/">Trang chủ</a></li>
                        <li class="breadcrumb1-item">Xem ngày tốt xấu</li>
                        <li class="breadcrumb1-item">Xem ngày tốt ký hợp đồng</li>
                    </ul>
                </div>
                <div class="page-head">
                    <h1 class="page-head-title h4 h3-sm h2-md h1-lg ">
                        XEM NGÀY TỐT KÝ HỢP ĐỒNG
                    </h1>
                    <p class="pb-2">Người xưa có câu: <b>"Bút sa gà chết"</b>. Một khi đã đặt bút ký vào hợp đồng, mọi
                        điều khoản đều được ấn định, mang theo trách nhiệm và ảnh hưởng trực tiếp đến tương lai, tài chính
                        và các mối quan hệ.
                    </p>
                </div>
                {{-- PHẦN FORM GIỮ NGUYÊN --}}
                <div class="card ">
                    <div class="card-body">
                        <form action="{{ route('ky-hop-dong.check') }}" method="POST" class="form_dmy date-picker">
                            @csrf

                            <div class="row">
                                <div class="col-lg-12">
                                    <label for="user_name" class="form-label">Tên <span class="text-danger">*</span></label>
                                    {{-- SỬA Ở ĐÂY: Thêm lại class "dateuser" --}}
                                    <input type="text" class="form-control @error('user_name') is-invalid @enderror"
                                        id="user_name" name="user_name" placeholder="Tên"
                                        value="{{ old('user_name', $inputs['user_name'] ?? '') }}">
                                    @error('user_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
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
                                    <label for="wedding_date_range" class="form-label">Dự kiến thời gian ký kết <span
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
                                                <button
                                                    class="nav-link w-100 @if ($loop->first) active @endif"
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
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <div class="card p-4 ">
                                                            <h4 class="mb-3">Thông tin gia chủ</h4>
                                                            <ul>
                                                                <li>Họ tên: {{ $user_name }}</li>
                                                                <li>Ngày sinh dương lịch:
                                                                    <b>{{ $birthdateInfo['dob']->format('d/m/Y') }}</b>
                                                                </li>
                                                                <li>Ngày sinh âm lịch:
                                                                    <b>{{ $birthdateInfo['lunar_dob_str'] }}</b>
                                                                </li>
                                                                <li>Tuổi: <b>{{ $birthdateInfo['can_chi_nam'] }}</b>, Mệnh:
                                                                    {{ $birthdateInfo['menh']['hanh'] }}
                                                                    ({{ $birthdateInfo['menh']['napAm'] }})
                                                                </li>
                                                                <li>Tuổi âm: <b>{{ $data['year_analysis']['lunar_age'] }}
                                                                        Tuổi</b></li>
                                                                <li>Dự kiến xuất hành: Trong khoảng
                                                                    {{ $date_start_end[0] }} đến {{ $date_start_end[1] }}
                                                                </li>
                                                            </ul>

                                                        </div>
                                                    </div>
                                                    <div class="info-panel">
                                                        <div class="h6"> <b>* Mẹo Nhỏ Để Buổi Ký Kết Viên Mãn:</b></div>

                                                        <ul>
                                                            <li><b>1. Đọc Kỹ Hợp Đồng:</b> Yếu tố quan trọng nhất. Hãy chắc
                                                                chắn bạn đã đọc và hiểu rõ mọi điều khoản trước khi đặt bút.
                                                            </li>
                                                            <li><b>2. Chuẩn Bị Tâm Lý:</b> Giữ một tâm thế tự tin, bình tĩnh
                                                                và tích cực. Năng lượng của bạn cũng là một phần của buổi ký
                                                                kết.</li>
                                                            <li><b>3. Không Gian Ký Kết:</b> Chọn một nơi sạch sẽ, sáng sủa
                                                                và chuyên nghiệp để tiến hành. Tránh những nơi ồn ào, bừa
                                                                bộn.</li>
                                                            <li><b>4. Trang Phục:</b> TMặc trang phục lịch sự, chỉn chu, thể
                                                                hiện sự tôn trọng đối với đối tác và với chính thỏa thuận.
                                                            </li>
                                                        </ul>
                                                        <p class="fst-italic text-center fw-bolder pt-2">Kính chúc Quý vị
                                                            bút sa thành công, hợp tác đại cát, vạn sự hanh thông!</p>

                                                    </div>
                                                    {{-- <p>{!! $data['year_analysis']['description'] !!}</p> --}}
                                                </div>


                                                @if ($data['year_analysis'])
                                                    <div
                                                        class="d-flex justify-content-between align-items-center mb-3 mt-3">
                                                        <h4 class="mb-0 title-date-chitite-tot">Bảng điểm chi tiết các ngày
                                                            tốt</h4>
                                                        <div class="d-flex align-items-center ">
                                                            <select class="form-select" id="sort-select">
                                                                <option value="date" selected>Theo ngày (Mặc định)
                                                                </option>
                                                                <option value="score_desc"> Cao đến thấp</option>
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
                                                            <tbody id="results-tbody">
                                                                {{-- Lọc và chỉ hiển thị những ngày có điểm TỐT hoặc RẤT TỐT --}}

                                                                @forelse($data['days'] as $day)
                                                                    @php
                                                                        if (
                                                                            !function_exists('getRatingClassBuildHouse')
                                                                        ) {
                                                                            function getRatingClassBuildHouse(
                                                                                string $rating,
                                                                            ): string {
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
                                                                        <td class="fw-bold cour">
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
                                                                                chọn của năm nay,
                                                                                không tìm thấy ngày nào thực sự tốt để tiến
                                                                                hành xây dựng.
                                                                            </p>
                                                                            <small>Bạn có thể thử mở rộng khoảng thời gian
                                                                                tìm kiếm.</small>
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
                            <h4>Xem Ngày Tốt Ký Hợp Đồng: Đặt Bút Thành Công, Hợp Tác Đại Cát</h4>
                            <p>Việc chọn một ngày lành tháng tốt để ký kết không chỉ là một nét văn hóa, mà còn là một bước
                                chuẩn bị chiến lược, giúp mọi việc được khởi đầu trong sự thuận lợi, hanh thông và nhận được
                                nguồn năng lượng tích cực nhất.</p>
                            <div class="h5">Tại Sao Ngày Ký Kết Lại Quan Trọng Đến Vậy?</div>
                            <p>Một thời điểm ký kết tốt sẽ là nền tảng cho sự thành công của toàn bộ thỏa thuận.</p>
                            <table class="table table-bordered table-actent">
                                <tbody>
                                    <tr>
                                        <td>
                                            <b>Hợp Tác Suôn Sẻ</b>
                                        </td>
                                        <td>
                                            Ngày tốt giúp hai bên dễ dàng tìm được tiếng nói chung, quá trình hợp tác sau
                                            này diễn ra thuận lợi, ít mâu thuẫn, xây dựng mối quan hệ đối tác bền vững.
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <b>Tối Ưu Hóa Tài Lộc</b>
                                        </td>
                                        <td>
                                            Ký kết vào ngày có sao Tài Lộc chiếu mệnh sẽ giúp thỏa thuận, dự án mang lại lợi
                                            nhuận cao, dòng tiền hanh thông, đạt được các mục tiêu tài chính đề ra.
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <b>Giảm Thiểu Rủi Ro, Thị Phi</b>
                                        </td>
                                        <td>
                                            Tránh được những ngày năng lượng xấu giúp hạn chế các rủi ro tiềm ẩn, tranh chấp
                                            pháp lý, hiểu lầm không đáng có hoặc những kẻ tiểu nhân phá hoại.

                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <b> Thực Thi Hiệu Quả</b>
                                        </td>
                                        <td>
                                            Một khởi đầu thuận lợi tạo ra động lực và tâm thế tốt, giúp việc triển khai các
                                            điều khoản trong hợp đồng sau đó được tiến hành một cách nhanh chóng và hiệu
                                            quả.
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="h5">Phương Pháp Luận Giải Chính Xác Của Chúng Tôi</div>
                            <p>Để đưa ra ngày tốt nhất cho việc ký kết, công cụ của chúng tôi phân tích dựa trên nhiều yếu
                                tố phong thủy chuyên sâu:</p>
                            <ul style="list-style: circle; margin-left: 2rem;">
                                <li><b>Phân Tích Theo Tuổi Người Ký:</b> Dựa vào ngày tháng năm sinh của người đứng tên ký
                                    chính để tìm ra ngày có Can-Chi, Ngũ Hành tương sinh, tránh các ngày xung khắc trực tiếp
                                    với bản mệnh.</li>
                                <li><b>Loại Trừ Các Ngày Đại Kỵ:</b> Tự động lọc bỏ các ngày xấu cho việc ký kết, giao dịch
                                    như <b>Thọ Tử, Sát Chủ, Nguyệt Phá, Bất Tương...</b> những ngày dễ gây đổ vỡ, tranh cãi.
                                </li>
                                <li><b>Ưu Tiên Ngày Có Cát Tinh:</b> Lựa chọn những ngày có nhiều sao tốt chủ về giao dịch,
                                    hợp tác, tiền tài như <b>Thiên Quý, Thiên Tài, Nguyệt Tài, Lộc Mã...</b> để gia tăng sự
                                    may mắn.</li>
                                <li><b>Gợi Ý Giờ Hoàng Đạo:</b> Đề xuất các khung giờ vàng trong ngày để bạn đặt bút ký,
                                    giúp tối ưu hóa nguồn năng lượng tốt đẹp nhất của ngày hôm đó.</li>
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
            const sortSelect = document.getElementById('sort-select');
            const resultsTbody = document.getElementById('results-tbody');


            // Kiểm tra xem các phần tử có tồn tại không để tránh lỗi
            if (!sortSelect || !resultsTbody) {
                return;
            }

            // Lấy tất cả các hàng (tr) từ tbody
            const rows = Array.from(resultsTbody.querySelectorAll('tr'));

            // Lưu lại thứ tự ban đầu (theo ngày) để có thể quay lại
            rows.forEach((row, index) => {
                row.dataset.originalIndex = index;
            });

            // Hàm để lấy điểm số từ một hàng
            function getScore(row) {
                // Tìm ô chứa điểm, lấy text và chuyển sang số nguyên
                const scoreCell = row.querySelector('td.fw-bold');
                if (scoreCell) {
                    // Loại bỏ ký tự '%' và chuyển thành số
                    return parseInt(scoreCell.textContent.replace('%', ''), 10);
                }
                return 0; // Trả về 0 nếu không tìm thấy điểm
            }

            // Lắng nghe sự kiện 'change' trên dropdown
            sortSelect.addEventListener('change', function() {
                const sortValue = this.value; // Lấy giá trị của option được chọn

                // Sắp xếp mảng các hàng dựa trên lựa chọn
                rows.sort((rowA, rowB) => {
                    if (sortValue === 'score_desc') {
                        // Sắp xếp điểm từ cao đến thấp
                        return getScore(rowB) - getScore(rowA);
                    } else if (sortValue === 'score_asc') {
                        // Sắp xếp điểm từ thấp đến cao
                        return getScore(rowA) - getScore(rowB);
                    } else {
                        // Mặc định: Sắp xếp theo ngày (thứ tự ban đầu)
                        return rowA.dataset.originalIndex - rowB.dataset.originalIndex;
                    }
                });

                // Xóa các hàng hiện tại khỏi tbody
                // resultsTbody.innerHTML = ''; // Cách này cũng được nhưng không hiệu quả bằng cách dưới

                // Thêm lại các hàng đã được sắp xếp vào tbody
                // Thao tác này sẽ tự động di chuyển các hàng đến vị trí mới
                rows.forEach(row => {
                    resultsTbody.appendChild(row);
                });
            });
        });
    </script>
@endsection
