@extends('welcome')

@section('content')
    <div class="row">
        <div class="col-lg-8">
            <div class=" mt-3 boder_fix">
                <div class="breadcrumb1">
                    <ul>
                        <li class="breadcrumb1-item"><a href="/">Trang chủ</a></li>
                        <li class="breadcrumb1-item">Xem ngày tốt xấu</li>
                        <li class="breadcrumb1-item">Xem tuổi cưới hỏi</li>
                    </ul>
                </div>
                <div class="page-head">
                    <h1 class="page-head-title h4 h3-sm h2-md h1-lg ">
                        XEM NGÀY TỐT CƯỚI HỎI HỢP TUỔI VỢ CHỒNG
                    </h1>
                    <p class="pb-2"> Dựng vợ gả chồng là việc hệ trọng của cả đời người...
                        Vui lòng nhập đầy đủ ngày sinh...</p>
                </div>
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('astrology.check') }}" method="POST" class="form_dmy date-picker">
                            @csrf

                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="groom_dob" class="form-label">Ngày sinh Chú rể <span class="text-danger">*</span></label>
                                    {{-- SỬA Ở ĐÂY: Thêm lại class "dateuser" --}}
                                    <input type="text"
                                        class="form-control dateuser @error('groom_dob') is-invalid @enderror"
                                        id="groom_dob" name="groom_dob" placeholder="dd/mm/yyyy"
                                        value="{{ old('groom_dob', $inputs['groom_dob'] ?? '') }}">
                                    @error('groom_dob')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="bride_dob" class="form-label">Ngày sinh Cô dâu <span class="text-danger">*</span></label>
                                    {{-- SỬA Ở ĐÂY: Thêm lại class "dateuser" --}}
                                    <input type="text"
                                        class="form-control dateuser @error('bride_dob') is-invalid @enderror"
                                        id="bride_dob" name="bride_dob" placeholder="dd/mm/yyyy"
                                        value="{{ old('bride_dob', $inputs['bride_dob'] ?? '') }}">
                                    @error('bride_dob')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label for="wedding_date_range" class="form-label">Khoảng ngày dự định cưới <span class="text-danger">*</span></label>
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
                            <div class="text-end">

                                <button type="submit" class="btn btn-outline-danger">Xem Kết Quả</button>
                            </div>
                        </form>
                        <div class="pt-4">
                            @if (isset($resultsByYear))
                                <div class="card-header">
                                    <ul class="nav nav-tabs flex-wrap w-100 text-center customers-nav-tabs" id="yearTab" role="tablist">
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
                                <div class="card-body">
                                    <div class="tab-content" id="yearTabContent">
                                        @foreach ($resultsByYear as $year => $data)
                                            <div class="tab-pane fade @if ($loop->first) show active @endif"
                                                id="tab-{{ $year }}" role="tabpanel"
                                                aria-labelledby="tab-{{ $year }}-tab">
                                                
                                                {{-- Phân tích --}}
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        {{-- @dd($data) --}}
                                                        <div
                                                            class="alert {{ $data['groom_analysis']['is_bad_year'] ? 'alert-warning' : 'alert-success' }}">
                                                            <h5 class="alert-heading">Chú Rể</h5>
                                                            <p>
                                                                Kiểm tra xem năm {{ $year }} {{ $data['canchi'] }}
                                                                Chú
                                                                rể tuổi
                                                                {{ $groomInfo['can_chi_nam'] }} (Nam
                                                                {{ $data['groom_analysis']['lunar_age'] }} tuổi) có phạm
                                                                phải
                                                                Kim
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
                                                            <p>Mệnh: {{ $groomInfo['menh']['hanh'] }}
                                                                ({{ $groomInfo['menh']['napAm'] }})
                                                            </p>
                                                            <p>Kết luận {!! $data['groom_analysis']['description'] !!}</p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div
                                                            class="alert {{ $data['bride_analysis']['is_bad_year'] ? 'alert-warning' : 'alert-success' }}">
                                                            <h5 class="alert-heading">Cô Dâu</h5>
                                                            <p>
                                                                Kiểm tra xem năm {{ $year }} {{ $data['canchi'] }}
                                                                Cô
                                                                Dâu tuổi
                                                                {{ $brideInfo['can_chi_nam'] }} (Nữ
                                                                {{ $data['bride_analysis']['lunar_age'] }} tuổi) có phạm
                                                                phải
                                                                Kim
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
                                                            <p>Mệnh: {{ $brideInfo['menh']['hanh'] }}
                                                                ({{ $brideInfo['menh']['napAm'] }})</p>
                                                            <p>Kết luận {!! $data['bride_analysis']['description'] !!}</p>
                                                        </div>
                                                    </div>
                                                </div>



                                                {{-- Bảng kết quả chi tiết các ngày --}}
                                                @if (empty($data['days']))
                                                    <div class="alert alert-info">Không có ngày nào trong khoảng bạn chọn
                                                        thuộc
                                                        năm này.
                                                    </div>
                                                @else
                                                    <div class="d-flex justify-content-between">
                                                        <div>
                                                            <h5 class="mb-3">Bảng điểm chi tiết các ngày</h4>
                                                        </div>

                                                        <!-- START: Thêm Dropdown sắp xếp -->
                                                        <div class="row mb-3">
                                                            <div class="col-md-12">
                                                               
                                                                <select class="form-select" id="dateSortSelect">
                                                                    <option value="date_asc" selected>Thứ tự ngày (Mặc định)
                                                                    </option>
                                                                    <option value="total_desc">Tổng điểm (Cao đến Thấp)
                                                                    </option>
                                                                    <option value="total_asc">Tổng điểm (Thấp đến Cao)
                                                                    </option>
                                                                    <option value="groom_desc">Điểm Chú Rể (Cao đến Thấp)
                                                                    </option>
                                                                    <option value="groom_asc">Điểm Chú Rể (Thấp đến Cao)
                                                                    </option>
                                                                    <option value="bride_desc">Điểm Cô Dâu (Cao đến Thấp)
                                                                    </option>
                                                                    <option value="bride_asc">Điểm Cô Dâu (Thấp đến Cao)
                                                                    </option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- END: Thêm Dropdown sắp xếp -->


                                                    <div class="table-responsive">
                                                        <table
                                                            class="table table-bordered table-hover text-center align-middle">
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
                                                    
                                                                    <tr data-date="{{ $day['date']->format('Y-m-d') }}"
                                                                        data-groom-score="{{ $day['groom_score']['percentage'] }}"
                                                                        data-bride-score="{{ $day['bride_score']['percentage'] }}"
                                                                        data-total-score="{{ ($day['groom_score']['percentage'] + $day['bride_score']['percentage']) / 2 }}">
                                                                        {{-- END: Thêm các data-attribute --}}
                                                                        <td>
                                                                            <strong>{{ $day['date']->format('d/m/Y') }}</strong><br>
                                                                            <small>Thứ {{ $day['weekday_name'] }}</small>
                                                                        </td>
                                                                        <td>
                                                                            {{ $day['lunar_date_str'] }} <br>
                                                                            {{ $day['full_lunar_date_str'] }}
                                                                            @if (!empty($day['good_hours']))
                                                                                <div class="mt-2">
                                                                                    <span class="text-danger"><i
                                                                                            class="far fa-clock"></i>
                                                                                        <strong>Gợi ý giờ
                                                                                            tốt:</strong></span><br>
                                                                                    <span>{{ implode('; ', $day['good_hours']) }}.</span>
                                                                                </div>
                                                                            @endif
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
                                                                                {{ $day['bride_score']['percentage'] }}
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @else
                                <h4>
                                    Vì Sao Việc Chọn Ngày Cưới Hỏi Lại Quan Trọng Đến Vậy?
                                </h4>
                                <p class="pt-3 pb-3">Trong dòng chảy văn hóa phương Đông, hôn nhân không chỉ là sự gắn kết
                                    của
                                    hai cá nhân mà còn là sự giao hòa của hai dòng họ, một sự kiện trọng đại được đánh dấu
                                    bằng
                                    một cột mốc thời gian thiêng liêng. Việc cẩn trọng lựa chọn ngày lành tháng tốt không
                                    phải
                                    là một thủ tục rườm rà hay mê tín, mà là một ứng dụng tinh hoa của các học thuyết vũ trụ
                                    quan cổ xưa, với mục đích tối thượng là đạt đến sự hòa hợp tuyệt đối: <b>"Thiên Thời -
                                        Địa
                                        Lợi
                                        - Nhân Hòa"</b>.</p>
                                <div>
                                    <h5>
                                        <b>1. Khởi Tạo Dòng Năng Lượng Tốt Lành (Thiên Thời)</b>
                                    </h5>
                                    <p>Vũ trụ vận hành theo những quy luật năng lượng vô hình nhưng mạnh mẽ. Mỗi ngày, mỗi
                                        giờ
                                        đều có một "trường khí" riêng, được tạo nên bởi sự tương tác của Âm Dương, sự vận
                                        động
                                        của Ngũ Hành (Kim, Mộc, Thủy, Hỏa, Thổ) và vị trí của các vì tinh tú.</p>
                                    <p>- <b>Một "ngày tốt"</b> là thời điểm mà các dòng năng lượng trong vũ trụ ở trạng thái
                                        cân
                                        bằng, tương sinh và hài hòa nhất. Cử hành hôn lễ vào một ngày như vậy cũng giống như
                                        hạ
                                        thủy một con thuyền vào ngày biển lặng, gió thuận. Nó tạo ra một lực đẩy ban đầu vô
                                        cùng
                                        mạnh mẽ, giúp cho "con thuyền hôn nhân" khởi hành một cách suôn sẻ, nhẹ nhàng và
                                        bình
                                        an.</p>
                                    <p>- <b>Ngược lại, một "ngày xấu"</b> (ngày hắc đạo, ngày có các sao xấu chiếu mệnh,
                                        ngày
                                        năng lượng xung khắc) giống như một ngày bão tố. Việc bắt đầu một hành trình mới
                                        trong
                                        điều kiện khắc nghiệt như vậy sẽ tiềm ẩn nhiều rủi ro, thử thách và gian truân hơn.
                                    </p>
                                </div>
                                <div>
                                    <h5>
                                        <b>2. Hóa Giải Xung Khắc, Kích Hoạt Tương Hợp (Nhân Hòa)</b>
                                    </h5>
                                    <p>Mỗi người chúng ta khi sinh ra đều mang một "bản đồ mệnh" riêng, được quy định bởi
                                        Can
                                        (Thiên Can) và Chi (Địa Chi) của ngày tháng năm sinh. "Bản đồ" này quyết định tính
                                        cách,
                                        vận mệnh và sự tương hợp với người khác.</p>
                                    <p>- <b>Hóa giải điểm yếu:</b> Không có cặp đôi nào là hoàn hảo tuyệt đối. Giữa hai bản
                                        mệnh
                                        luôn tồn tại những điểm tương sinh tốt đẹp và cả những điểm xung khắc tiềm ẩn. Một
                                        ngày
                                        cưới được lựa chọn cẩn thận sẽ đóng vai trò như một <b>chất xúc tác kỳ diệu</b>.
                                        Năng
                                        lượng của ngày tốt đó sẽ giúp "trung hòa" và làm suy yếu các yếu tố xung khắc, đồng
                                        thời
                                        khuếch đại, nuôi dưỡng các yếu tố tương hợp. Nó giống như một người hòa giải khéo
                                        léo,
                                        giúp hai cá tính khác biệt tìm được tiếng nói chung và dễ dàng dung hòa hơn trong
                                        cuộc
                                        sống.</p>
                                    <p>- <b>Chọn "chất keo" kết dính:</b> Ngày cưới tốt chính là chất keo năng lượng, giúp
                                        gắn
                                        kết hai bản mệnh lại với nhau một cách bền chặt, tạo ra một thể thống nhất vững mạnh
                                        hơn
                                        để cùng nhau đối mặt với những thăng trầm của cuộc đời.</p>
                                </div>
                                <div>
                                    <h5>
                                        <b>3. Tạo Sự An Tâm và Gắn Kết Gia Đình (Địa Lợi)</b>
                                    </h5>
                                    <p>Hôn nhân là việc của cả hai gia đình. Sự tôn trọng và tuân thủ các nghi lễ truyền
                                        thống, đặc biệt là việc chọn ngày, thể hiện sự nghiêm túc và trân trọng của đôi trẻ
                                        đối với cuộc hôn nhân và với các bậc trưởng bối.</p>
                                    <p>- <b>Sự chúc phúc trọn vẹn:</b> Khi ngày cưới được lựa chọn dựa trên sự phân tích kỹ
                                        lưỡng, nó mang lại một niềm tin và sự an tâm to lớn cho cha mẹ, ông bà hai bên. Đây
                                        không chỉ là một thủ tục, mà là một lời khẳng định rằng con cháu mình đang bắt đầu
                                        cuộc sống mới với sự chuẩn bị chu đáo nhất, được trời đất và tổ tiên chứng giám, phù
                                        hộ.</p>
                                    <p>- <b>Tránh những lời dị nghị:</b> Việc này cũng giúp tránh được những lời bàn ra tán
                                        vào hoặc sự lo lắng không đáng có từ người thân nếu lỡ chọn phải một ngày không đẹp,
                                        tạo ra một không khí vui vẻ, đoàn kết và trọn vẹn trong ngày đại hỷ.</p>
                                </div>
                            @endif
                        </div>
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
            // Lấy các element cần thiết
            const sortSelect = document.getElementById('dateSortSelect');
            const tableBody = document.querySelector('.table tbody');

            // Chuyển tất cả các dòng <tr> thành một mảng các đối tượng để dễ xử lý.
            // Việc này chỉ cần làm một lần khi trang tải xong.
            const rows = Array.from(tableBody.querySelectorAll('tr'));

            // Thêm sự kiện 'change' cho dropdown
            sortSelect.addEventListener('change', function() {
                // Lấy giá trị của lựa chọn (ví dụ: 'total_desc')
                const sortBy = this.value;

                // Gọi hàm sắp xếp
                sortRows(sortBy);
            });

            /**
             * Hàm chính để sắp xếp các dòng trong bảng
             * @param {string} sortBy - Tiêu chí sắp xếp
             */
            function sortRows(sortBy) {
                rows.sort((rowA, rowB) => {
                    // Lấy dữ liệu từ các data-attribute
                    const dateA = rowA.dataset.date;
                    const dateB = rowB.dataset.date;

                    const totalA = parseFloat(rowA.dataset.totalScore);
                    const totalB = parseFloat(rowB.dataset.totalScore);

                    const groomA = parseFloat(rowA.dataset.groomScore);
                    const groomB = parseFloat(rowB.dataset.groomScore);

                    const brideA = parseFloat(rowA.dataset.brideScore);
                    const brideB = parseFloat(rowB.dataset.brideScore);

                    // Dùng switch-case để xử lý các trường hợp sắp xếp
                    switch (sortBy) {
                        case 'total_desc':
                            return totalB - totalA; // Sắp xếp giảm dần
                        case 'total_asc':
                            return totalA - totalB; // Sắp xếp tăng dần
                        case 'groom_desc':
                            return groomB - groomA;
                        case 'groom_asc':
                            return groomA - groomB;
                        case 'bride_desc':
                            return brideB - brideA;
                        case 'bride_asc':
                            return brideA - brideB;
                        case 'date_asc':
                        default:
                            // So sánh chuỗi ngày tháng ( định dạng Y-m-d so sánh được)
                            return dateA.localeCompare(dateB);
                    }
                });

                // Sau khi đã sắp xếp mảng `rows`, cập nhật lại DOM
                // Xóa tất cả các dòng hiện tại trong tbody
                tableBody.innerHTML = '';

                // Thêm lại các dòng đã được sắp xếp vào tbody
                rows.forEach(row => {
                    tableBody.appendChild(row);
                });
            }
        });
    </script>
@endsection
