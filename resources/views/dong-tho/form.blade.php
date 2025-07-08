@extends('welcome')

@section('content')
    <div class="row">
        <div class="col-lg-8">
            <div class=" mt-3 boder_fix">
                <div class="breadcrumb1">
                    <ul>
                        <li class="breadcrumb1-item"><a href="/">Trang chủ</a></li>
                        <li class="breadcrumb1-item">Xem ngày tốt xấu</li>
                        <li class="breadcrumb1-item">Xem ngày động thổ</li>
                    </ul>
                </div>
                <div class="page-head">
                    <h1 class="page-head-title h4 h3-sm h2-md h1-lg ">
                        XEM NGÀY TỐT ĐỘNG THỔ NHÀ
                    </h1>
                    <p class="pb-2">Động thổ là việc quan trọng của việc xây nhà, không chỉ là là phong tục mà còn là một
                        hành động mang ý nghĩa tâm linh sâu sắc đây là việc xin pháp các vị Thần Linh cai quản mảnh đất đó
                        mong được che chở phù hộ cho thuận buồm xuôi gió
                    </p>
                </div>
                {{-- PHẦN FORM GIỮ NGUYÊN --}}
                <div class="card ">
                    <div class="card-body">
                        <form action="{{ route('breaking.check') }}" method="POST" class="form_dmy date-picker">
                            @csrf

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="birthdate" class="form-label">Ngày sinh <span class="text-danger">*</span></label>
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
                                    <label for="wedding_date_range" class="form-label">Dự kiến thời động thổ <span class="text-danger">*</span></label>
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
                                <div class="mt-2">
                                    <div class="tab-content" id="yearTabContent">
                                        @foreach ($resultsByYear as $year => $data)
                                            <div class="tab-pane fade @if ($loop->first) show active @endif"
                                                id="tab-{{ $year }}" role="tabpanel"
                                                aria-labelledby="tab-{{ $year }}-tab">
                                                <div class="row g-2">
                                                    <div class="col-lg-12">
                                                        <div class="card p-4 ">
                                                            <h4 class="mb-3">Thông tin gia chủ</h4>
                                                            <ul>
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
                                                                <li>Tuổi âm:
                                                                    <b>{{ $data['year_analysis']['lunar_age'] }}</b>
                                                                </li>

                                                            </ul>

                                                        </div>
                                                    </div>

                                                    <div class="col-lg-12">
                                                        <div class="card p-3 ">
                                                            <h5 class="text-center">
                                                                Kiểm tra kim lâu - hoang ốc - tam tai
                                                            </h5>
                                                            <p>
                                                                Kiểm tra xem năm {{ $year }} {{ $data['canchi'] }}
                                                                gia chủ
                                                                tuổi
                                                                {{ $birthdateInfo['can_chi_nam'] }}
                                                                ({{ $data['year_analysis']['lunar_age'] }} tuổi) có phạm
                                                                phải Kim
                                                                Lâu,
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
                                                    <p><b>Kết luận: </b></p>
                                                    <p>=> {!! $data['year_analysis']['description'] !!}</p>
                                                </div>


                                                @if ($data['year_analysis'])
                                                    <div class="d-flex justify-content-between align-items-center mb-3">
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

                                                                @php
                                                                    $goodDays = array_filter($data['days'], function (
                                                                        $day,
                                                                    ) {
                                                                        $rating = $day['day_score']['rating'];
                                                                        return $rating === 'Tốt' ||
                                                                            $rating === 'Rất tốt';
                                                                    });
                                                                @endphp

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
                                                                            {{ $day['day_score']['percentage'] }} %
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
                                                                                chọn của
                                                                                năm nay,
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
                            <div>
                                <h2>
                                    Tại Sao Chọn Ngày Động Thổ Là Bước Đi Tối Quan Trọng?
                                </h2>
                                <p><b>Khởi đầu đúng đắn quyết định vận mệnh của cả công trình và gia chủ</b></p>
                                <p>Trước khi đặt viên gạch đầu tiên, người xưa luôn cử hành Lễ Động Thổ. Đây không phải là
                                    một thủ tục mê tín, mà là một hành động mang ý nghĩa tâm linh sâu sắc: là lời "trình
                                    báo" và xin phép các vị Thần linh cai quản mảnh đất, cầu mong sự che chở và phù hộ để
                                    mọi việc được "thuận buồm xuôi gió".</p>
                                Việc chọn đúng ngày giờ hoàng đạo chính là cách chúng ta giao tiếp với thế giới tự nhiên và
                                tâm linh, để nhận được sự ủng hộ tốt nhấ
                                <h5 class="pt-2">
                                    4 Lý Do Cốt Lõi Bạn Phải Xem Ngày Động Thổ Cẩn Thận
                                </h5>
                                <div>
                                    <p> <b>1. Tôn Trọng "Long Mạch - Thổ Thần" - Nền Tảng Của Sự An Lành</b></p>
                                    <p>Mỗi mảnh đất đều có Thần Thổ Địa và Long Mạch cai quản. Việc động thổ giống như "can
                                        thiệp" vào nơi ở của các Ngài. Chọn ngày tốt chính là thể hiện sự thành kính, xin
                                        phép một cách lễ độ, tránh kinh động đến các vị thần, từ đó nhận được sự phù trợ
                                        thay vì quấy phá. Một khởi đầu được chấp thuận sẽ giúp quá trình thi công diễn ra
                                        trong bình an.</p>
                                    <p> <b>2. "Thiên Thời - Địa Lợi" - Tránh Rủi Ro, Tối Ưu Tiến Độ</b></p>
                                    <p>Phong thủy tin rằng vũ trụ vận hành theo quy luật tuần hoàn của năng lượng. Có những
                                        ngày năng lượng xung khắc, dễ gây ra tai nạn, sự cố, thời tiết bất lợi, trục trặc
                                        máy móc. Ngược lại, ngày tốt là ngày có năng lượng hài hòa, "Thiên Thời, Địa Lợi",
                                        giúp mọi việc diễn ra suôn sẻ, an toàn và thường hoàn thành đúng hoặc sớm hơn dự
                                        kiến.</p>
                                    <p> <b>3. Kích Hoạt "Vượng Khí" - Nền Tảng Cho Tài Lộc Tương Lai</b></p>
                                    <p>Ngôi nhà không chỉ là nơi để ở, mà còn là một trường năng lượng ảnh hưởng trực tiếp
                                        đến vận mệnh của gia chủ. Động thổ vào ngày giờ hoàng đạo, hợp với mệnh của gia chủ,
                                        giống như việc cắm một chiếc "ăng-ten" thu sóng tốt vào đúng thời điểm vượng khí
                                        nhất. Nguồn năng lượng tích cực này sẽ được niêm phong vào nền móng, tạo đà cho tài
                                        lộc, sức khỏe và sự nghiệp của gia đình phát triển sau này.</p>
                                    <p> <b>4. Hóa Giải "Hung Sát" - Biến Dữ Thành Lành</b></p>
                                    <p>Không phải mảnh đất nào cũng hoàn hảo. Một số khu đất có thể tiềm ẩn các luồng "hung
                                        khí" hoặc nằm ở phương vị xấu của năm (phạm Thái Tuế, Tam Sát...). Việc chọn đúng
                                        ngày tốt với các sao Cát tinh mạnh mẽ có thể đóng vai trò như một lá bùa hộ mệnh,
                                        giúp trấn áp, hóa giải các yếu tố bất lợi này, mang lại sự bình yên lâu dài cho gia
                                        trạch.</p>
                                    <hr>
                                    <h5>Chúng Tôi Giúp Bạn Tìm Ra Ngày Tốt Nhất Như Thế Nào</h5>
                                    <p>Công cụ của chúng tôi không chỉ chọn ngày đẹp chung chung. Hệ thống sẽ phân tích đa
                                        chiều dựa trên các nguyên tắc Phong thủy chính thống:</p>
                                    <ul style="list-style: circle; margin-left: 2rem;">
                                        <li><b>Đối chiếu tuổi và mệnh của bạn</b> để tìm ngày tương sinh.</li>
                                        <li><b>Loại trừ toàn bộ ngày đại kỵ</b> (Sát Chủ, Thọ Tử, Hoang Vu...).</li>
                                        <li><b>Xem xét hướng nhà</b> để tránh các hướng phạm của năm.</li>
                                        <li><b>Ưu tiên ngày có nhiều sao tốt (Cát Tinh)</b> hội tụ.</li>
                                    </ul>
                                </div>

                            </div>
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
