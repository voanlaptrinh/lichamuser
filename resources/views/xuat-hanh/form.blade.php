@extends('welcome')

@section('content')
    <div class="row">
        <div class="col-lg-8">
            <div class=" mt-3 boder_fix">
                <div class="breadcrumb1">
                    <ul>
                        <li class="breadcrumb1-item"><a href="/">Trang chủ</a></li>
                        <li class="breadcrumb1-item">Xem ngày tốt xấu</li>
                        <li class="breadcrumb1-item">Xem ngày xuất hành</li>
                    </ul>
                </div>
                <div class="page-head">
                    <h1 class="page-head-title h4 h3-sm h2-md h1-lg text-uppercase">
                        Xem Ngày Tốt Xuất Hành
                    </h1>
                    <p class="pb-2">Theo quan niệm của người xưa, "Đầu xuôi thì đuôi lọt". Một chuyến đi được khởi hành
                        vào ngày lành tháng tốt, đúng hướng, đúng giờ sẽ mang lại sự bình an, may mắn và giúp mọi mục tiêu
                        của chuyến đi được hoàn thành viên mãn.
                    </p>
                </div>
                <div class="card">

                    <div class="card-body ">
                        <form action="{{ route('xuat-hanh.check') }}" method="POST" class="form_dmy date-picker">
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
                                    <label for="wedding_date_range" class="form-label">Dự kiến thời gian khởi hành <span
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


                        {{-- Giả sử bạn có biến $resultsByYear sau khi form được submit --}}
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
                                <div class="card-body p-0">

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
                                                                <li>Dự kiến xuất hành: Trong khoảng
                                                                    {{ $date_start_end[0] }} đến {{ $date_start_end[1] }}
                                                                </li>
                                                            </ul>

                                                        </div>
                                                    </div>
                                                    <p><b>Kết luận:</b></p>
                                                    <p>{!! $data['year_analysis']['description'] !!}</p>
                                                    <p> <b> Lời khuyên:</b></p>
                                                    <p> Sau khi có ngày giờ và hướng tốt, hãy cố gắng bước ra khỏi nhà và đi
                                                        về hướng đó trước tiên (dù chỉ một đoạn ngắn) rồi sau đó mới đi theo
                                                        lộ trình thực tế. Điều này mang ý nghĩa "nghênh đón" sự may mắn.</p>
                                                    <p class="fst-italic fw-bolder text-center">Kính chúc quý bạn thượng lộ
                                                        bình an, mã đáo thành công!</p>
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
                                                                @php
                                                                    $goodDays = array_filter($data['days'], function (
                                                                        $day,
                                                                    ) {
                                                                        $rating = $day['day_score']['rating'];
                                                                        return $rating === 'Tốt' ||
                                                                            $rating === 'Rất tốt' ||
                                                                            $rating === 'Khá';
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
                                                @else
                                                    <h3>Xem Ngày Tốt Xuất Hành: Thượng Lộ Bình An, Vạn Sự Hanh Thông</h3>
                                                    <p>Dù là đi công tác, du lịch, du học hay bắt đầu một hành trình quan
                                                        trọng, việc chọn ngày xuất hành cẩn thận chính là bước chuẩn bị đầu
                                                        tiên để cầu mong một khởi đầu thuận lợi.</p>
                                                    <div class="h5">Tại Sao Cần Chọn Ngày Giờ Xuất Hành?</div>
                                                    <p>Việc lựa chọn thời điểm khởi hành không chỉ là một nét văn hóa, mà
                                                        còn dựa trên các nguyên tắc của vũ trụ học nhằm mang lại lợi ích
                                                        thực tế.</p>
                                                    <table class="table table-bordered table-actent">
                                                        <tbody>
                                                            <tr>
                                                                <td>
                                                                    <b>Thượng Lộ Bình An</b>
                                                                </td>
                                                                <td>
                                                                    Đây là mong cầu quan trọng nhất. Ngày tốt giúp tránh
                                                                    được những rủi ro, va chạm bất ngờ, sự cố về phương
                                                                    tiện, giúp chuyến đi được thông suốt, an toàn.
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    <b>Công Việc Thuận Lợi</b>
                                                                </td>
                                                                <td>
                                                                    Xuất hành vào ngày hợp mệnh giúp việc đàm phán, ký kết
                                                                    hợp đồng, gặp gỡ đối tác dễ dàng thành công, đạt được
                                                                    kết quả như ý.
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    <b>Thu Hút May Mắn</b>
                                                                </td>
                                                                <td>
                                                                    Khởi hành đúng lúc, đúng hướng giúp bạn đón nhận được
                                                                    nguồn năng lượng tích cực, gặp được quý nhân phù trợ,
                                                                    mọi việc hanh thông.
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    <b>Tinh Thần An Yên, Vui Vẻ</b>
                                                                </td>
                                                                <td>
                                                                    Khi đã chuẩn bị kỹ lưỡng, tâm lý của bạn sẽ thoải mái,
                                                                    tự tin, giúp tận hưởng chuyến đi một cách trọn vẹn và xử
                                                                    lý mọi tình huống tốt hơn.

                                                                </td>
                                                            </tr>

                                                        </tbody>
                                                    </table>
                                                    <div class="h5">Yếu Tố Đặc Biệt: Hướng Xuất Hành (Hỷ Thần & Tài
                                                        Thần)</div>
                                                    <p>Khác với các việc khác, xem ngày xuất hành có một yếu tố cực kỳ quan
                                                        trọng là <b>hướng khởi hành</b>. Vào mỗi ngày, các vị thần mang lại
                                                        may mắn sẽ ngự ở các phương khác nhau:</p>
                                                    <ul style="list-style: circle; margin-left: 2rem;">
                                                        <li><b>Hỷ Thần (Thần Vui Vẻ):</b> Xuất hành theo hướng này sẽ mang
                                                            lại niềm vui, sự may mắn trong tình cảm, gặp gỡ thuận lợi, hỷ
                                                            sự.</li>
                                                        <li><b>Tài Thần (Thần Tài Lộc):</b> Xuất hành theo hướng này sẽ tốt
                                                            cho việc cầu tài, buôn bán, giao dịch tiền bạc, ký kết kinh tế.
                                                        </li>
                                                    </ul>
                                                    <p>Công cụ của chúng tôi sẽ chỉ rõ hướng Hỷ Thần và Tài Thần trong ngày
                                                        tốt để bạn có thể lựa chọn lộ trình phù hợp nhất với mục đích của
                                                        mình.
                                                    </p>
                                                    <div class="h5">Phương Pháp Luận Giải Chính Xác Của Chúng Tôi</div>
                                                    <p>Chúng tôi phân tích ngày giờ dựa trên sự kết hợp của nhiều học thuyết
                                                        uy tín:</p>
                                                    <ul style="list-style: circle; margin-left: 2rem;">
                                                        <li><b>Phân tích Can-Chi, Ngũ Hành</b> của ngày và của tuổi bạn để
                                                            tìm sự tương hợp.</li>
                                                        <li><b>Loại bỏ các ngày xấu</b> có sao chủ về tai ương, bất lợi cho
                                                            việc di chuyển.</li>
                                                        <li><b>Tính toán hướng Hỷ Thần, Tài Thần</b> dựa trên lịch Can Chi
                                                            của từng ngày.</li>
                                                        <li><b>Gợi ý các Giờ Hoàng Đạo</b> tốt nhất trong ngày để bạn bắt
                                                            đầu chuyến đi.</li>
                                                    </ul>
                                                @endif

                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                            </div>
                        @else
                            <h3>Xem Ngày Tốt Xuất Hành: Thượng Lộ Bình An, Vạn Sự Hanh Thông</h3>
                            <p>Dù là đi công tác, du lịch, du học hay bắt đầu một hành trình quan
                                trọng, việc chọn ngày xuất hành cẩn thận chính là bước chuẩn bị đầu
                                tiên để cầu mong một khởi đầu thuận lợi.</p>
                            <div class="h5">Tại Sao Cần Chọn Ngày Giờ Xuất Hành?</div>
                            <p>Việc lựa chọn thời điểm khởi hành không chỉ là một nét văn hóa, mà
                                còn dựa trên các nguyên tắc của vũ trụ học nhằm mang lại lợi ích
                                thực tế.</p>
                            <table class="table table-bordered table-actent">
                                <tbody>
                                    <tr>
                                        <td>
                                            <b>Thượng Lộ Bình An</b>
                                        </td>
                                        <td>
                                            Đây là mong cầu quan trọng nhất. Ngày tốt giúp tránh
                                            được những rủi ro, va chạm bất ngờ, sự cố về phương
                                            tiện, giúp chuyến đi được thông suốt, an toàn.
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <b>Công Việc Thuận Lợi</b>
                                        </td>
                                        <td>
                                            Xuất hành vào ngày hợp mệnh giúp việc đàm phán, ký kết
                                            hợp đồng, gặp gỡ đối tác dễ dàng thành công, đạt được
                                            kết quả như ý.
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <b>Thu Hút May Mắn</b>
                                        </td>
                                        <td>
                                            Khởi hành đúng lúc, đúng hướng giúp bạn đón nhận được
                                            nguồn năng lượng tích cực, gặp được quý nhân phù trợ,
                                            mọi việc hanh thông.
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <b>Tinh Thần An Yên, Vui Vẻ</b>
                                        </td>
                                        <td>
                                            Khi đã chuẩn bị kỹ lưỡng, tâm lý của bạn sẽ thoải mái,
                                            tự tin, giúp tận hưởng chuyến đi một cách trọn vẹn và xử
                                            lý mọi tình huống tốt hơn.

                                        </td>
                                    </tr>

                                </tbody>
                            </table>
                            <div class="h5">Yếu Tố Đặc Biệt: Hướng Xuất Hành (Hỷ Thần & Tài
                                Thần)</div>
                            <p>Khác với các việc khác, xem ngày xuất hành có một yếu tố cực kỳ quan
                                trọng là <b>hướng khởi hành</b>. Vào mỗi ngày, các vị thần mang lại
                                may mắn sẽ ngự ở các phương khác nhau:</p>
                            <ul style="list-style: circle; margin-left: 2rem;">
                                <li><b>Hỷ Thần (Thần Vui Vẻ):</b> Xuất hành theo hướng này sẽ mang
                                    lại niềm vui, sự may mắn trong tình cảm, gặp gỡ thuận lợi, hỷ
                                    sự.</li>
                                <li><b>Tài Thần (Thần Tài Lộc):</b> Xuất hành theo hướng này sẽ tốt
                                    cho việc cầu tài, buôn bán, giao dịch tiền bạc, ký kết kinh tế.
                                </li>
                            </ul>
                            <p>Công cụ của chúng tôi sẽ chỉ rõ hướng Hỷ Thần và Tài Thần trong ngày
                                tốt để bạn có thể lựa chọn lộ trình phù hợp nhất với mục đích của
                                mình.
                            </p>
                            <div class="h5">Phương Pháp Luận Giải Chính Xác Của Chúng Tôi</div>
                            <p>Chúng tôi phân tích ngày giờ dựa trên sự kết hợp của nhiều học thuyết
                                uy tín:</p>
                            <ul style="list-style: circle; margin-left: 2rem;">
                                <li><b>Phân tích Can-Chi, Ngũ Hành</b> của ngày và của tuổi bạn để
                                    tìm sự tương hợp.</li>
                                <li><b>Loại bỏ các ngày xấu</b> có sao chủ về tai ương, bất lợi cho
                                    việc di chuyển.</li>
                                <li><b>Tính toán hướng Hỷ Thần, Tài Thần</b> dựa trên lịch Can Chi
                                    của từng ngày.</li>
                                <li><b>Gợi ý các Giờ Hoàng Đạo</b> tốt nhất trong ngày để bạn bắt
                                    đầu chuyến đi.</li>
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
