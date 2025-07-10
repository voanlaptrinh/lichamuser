@extends('welcome')

@section('content')
    <div class="row">
        <div class="col-lg-8">
            <div class=" mt-3 boder_fix">
                <div class="breadcrumb1">
                    <ul>
                        <li class="breadcrumb1-item"><a href="/">Trang chủ</a></li>
                        <li class="breadcrumb1-item">Xem ngày tốt xấu</li>
                        <li class="breadcrumb1-item">Xem ngày cúng sao - giải hạn</li>
                    </ul>
                </div>
                <div class="page-head">
                    <h1 class="page-head-title h4 h3-sm h2-md h1-lg text-uppercase">
                        Xem Sao Hạn & Hướng Dẫn Cúng Giải
                    </h1>
                    <p class="pb-2">Hiểu rõ vận mệnh, thành tâm cầu nguyện, hóa giải hung tai, nghênh đón cát tường
                    </p>
                </div>
                {{-- PHẦN FORM GIỮ NGUYÊN --}}
                <div class="card ">
                    <div class="card-body">
                        <form action="{{ route('giai-han.check') }}" method="POST" class="form_dmy date-picker">
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
                                    <label for="wedding_date_range" class="form-label">Dự kiến thời gian <span
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
                                                                <li>Tuổi âm: <b>{{ $data['year_analysis']['lunar_age'] }}
                                                                        Tuổi</b>
                                                                </li>
                                                                <li>Dự kiến cúng sao giải hạn: Trong khoảng
                                                                    {{ $date_start_end[0] }} đến {{ $date_start_end[1] }}
                                                                </li>
                                                            </ul>

                                                        </div>
                                                    </div>

                                                    {{-- <p>{!! $data['year_analysis']['description'] !!}</p> --}}
                                                </div>


                                                @if ($data['year_analysis'])
                                                   <div class="d-flex justify-content-between align-items-center mb-3 mt-3">
                                                        <h4 class="mb-0 title-date-chitite-tot">Bảng điểm chi tiết các ngày
                                                            tốt</h4>
                                                        <div class="d-flex align-items-center ">
                                                            <select class="form-select sort-select" id="sort-select">
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
                                                            <tbody class="results-tbody">
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
                            <h4>Xem Sao Hạn & Hướng Dẫn Cúng Giải</h4>
                            <p>Theo lệ cổ truyền, mỗi người khi bước sang năm mới đều có một ngôi sao trong hệ thống Cửu
                                Diệu chiếu mệnh và một vận hạn tương ứng. Việc tìm hiểu sao hạn của mình không phải để lo
                                sợ, mà là để "biết mình biết ta", chủ động hơn trong cuộc sống, từ đó có những phương pháp
                                phòng tránh và hóa giải phù hợp.</p>
                            <p>Lễ cúng sao giải hạn đầu năm là một nghi thức tâm linh quan trọng, thể hiện lòng thành kính
                                với các đấng bề trên, cầu mong một năm mới bình an, vạn sự hanh thông.</p>
                            <div class="h5">Hiểu Về Sao Chiếu Mệnh & Vận Hạn Của Bạn</div>
                            <p>Hệ thống Cửu Diệu gồm 9 ngôi sao, mỗi sao mang một năng lượng và ý nghĩa riêng, ảnh hưởng đến
                                các phương diện như tài lộc, sức khỏe, công danh và gia đạo.</p>
                            <table class="table table-bordered table-actent">
                                <thead>
                                    <tr>
                                        <th>Loại Sao</th>
                                        <th>Các Sao Chiếu Mệnh</th>
                                        <th>Ý Nghĩa Chính</th>
                                    </tr>
                                   
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <b><i class="bi bi-star-fill text-warning" ></i> Sao Tốt (Cát Tinh)</b>
                                        </td>
                                        <td>
                                            <b>Thái Dương, Thái Âm, Mộc Đức</b>
                                        </td>
                                        <td>
                                            Chọn ngày giờ cẩn thận là hành động đầu tiên thể hiện sự tôn trọng, nghiêm túc
                                            và lòng hiếu kính của gia chủ đối với các đấng bề trên.
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <b> <i class="bi bi-star-fill text-warning" ></i> Sao Trung Bình</b>
                                        </td>
                                        <td>
                                            <b>Vân Hớn, Thổ Tú, Thủy Diệu</b>
                                        </td>
                                        <td>
                                            Có cả tốt và xấu đan xen. Công việc làm ăn ở mức trung bình, cần cẩn trọng lời
                                            ăn tiếng nói và các vấn đề sức khỏe nhỏ.
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <b><i class="bi bi-star-fill text-warning" ></i> Sao Xấu (Hung Tinh)</b>
                                        </td>
                                        <td>
                                            <b>La Hầu, Kế Đô, Thái Bạch</b>
                                        </td>
                                        <td>
                                            Chủ về hao tốn tiền của, thị phi, tai tiếng, dễ gặp chuyện buồn phiền, cần đặc
                                            biệt chú ý sức khỏe và làm lễ giải hạn cẩn thận.
                                        </td>
                                    </tr>

                                </tbody>
                            </table>
                            <p>Bên cạnh sao chiếu mệnh, mỗi người còn gặp một trong tám niên hạn (Bát Hạn) như Huỳnh Tuyền,
                                Tam Kheo... Công cụ của chúng tôi sẽ giúp bạn xác định chính xác cả sao và hạn của mình.</p>
                            <div class="h5">Ý Nghĩa Thực Sự Của Việc Cúng Lễ</div>

                            <ul style="list-style: circle; margin-left: 2rem;">
                                <li><b>Nếu gặp Sao Tốt:</b> Lễ cúng là một lời <b>tạ ơn</b>, cầu mong các vị thần tiếp tục
                                    ban phước lành, giúp may mắn được bền vững, gia tăng phúc lộc.
                                </li>
                                <li><b>Nếu gặp Sao Xấu:</b> Lễ cúng là một lời <b>cầu xin</b>, thể hiện lòng thành tâm sám
                                    hối, mong các Ngài giảm nhẹ tai ương, che chở để tai qua nạn khỏi.
                                </li>
                                <li><b>Về mặt tinh thần:</b> Nghi lễ giúp chúng ta an tâm hơn, có thêm niềm tin và động lực
                                    để đối mặt với những thử thách trong năm, đồng thời tự nhắc nhở bản thân phải sống cẩn
                                    trọng và thiện lương hơn.</li>

                            </ul>
                            <div class="info-panel">
                                <div class="h6"> <b>* Chúng Tôi Giúp Bạn Chuẩn Bị Lễ Nghi Chu Toàn</b>
                                </div>
                                <p>Bạn không cần phải là một chuyên gia phong thủy. Chỉ cần cung cấp thông tin, công cụ của
                                    chúng tôi sẽ tự động tính toán và cung cấp cho bạn một bản hướng dẫn chi tiết và đầy đủ
                                    nhất, bao gồm:</p>
                                <ul>
                                    <li><b>1. Tên chính xác:</b> của Sao và Hạn chiếu mệnh bạn năm nay.
                                    </li>
                                    <li><b>2. Ngày giờ cúng lễ:</b> tốt nhất trong tháng (Âm lịch).</li>
                                    <li><b>3. Hướng cúng:</b> chuẩn xác để đặt bàn lễ.</li>
                                    <li><b>4. Số lượng nến và màu sắc:</b> tương ứng với từng sao.</li>
                                    <li><b>5. Mẫu bài vị:</b> và màu giấy cần dùng.</li>
                                    <li><b>6. Văn khấn:</b> đầy đủ, trang trọng.</li>
                                </ul>
                                <div class="h6 pt-2 fw-bolder text-decoration-underline">Lời Khuyên Quan Trọng Nhất: </div>
                                <p class="mt-0 pt-0">Phúc đức tại tâm, không chỉ tại lễ. Cách "giải hạn" hiệu quả nhất là <b>tu tâm dưỡng tính,
                                    làm nhiều việc thiện, hiếu kính cha mẹ và sống có trách nhiệm</b>. Nghi lễ cúng sao là một
                                    điểm tựa tinh thần, giúp chúng ta thêm vững tâm trên con đường đó.</p>
                                <p class="fst-italic fw-bolder text-center pt-2">Kính chúc Quý vị một năm mới vạn sự bình an, cát tường như ý!</p>

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
        // 1. Tìm TẤT CẢ các dropdown sắp xếp
        const allSortSelects = document.querySelectorAll('.sort-select');

        // 2. Lặp qua từng dropdown để gắn sự kiện riêng cho nó
        allSortSelects.forEach(selectElement => {

            // 3. Tìm tab-pane cha và tbody tương ứng của CHÍNH dropdown này
            const tabPane = selectElement.closest('.tab-pane');
            if (!tabPane) return; // Bỏ qua nếu không tìm thấy tab-pane

            const resultsTbody = tabPane.querySelector('.results-tbody');
            if (!resultsTbody) return; // Bỏ qua nếu không tìm thấy tbody

            // Lấy tất cả các hàng (tr) từ tbody của CHÍNH tab này
            const rows = Array.from(resultsTbody.querySelectorAll('tr'));

            // Lưu lại thứ tự ban đầu (theo ngày) để có thể quay lại
            rows.forEach((row, index) => {
                row.dataset.originalIndex = index;
            });

            // Hàm để lấy điểm số từ một hàng (giữ nguyên)
            function getScore(row) {
                const scoreCell = row.querySelector('td.cour'); // Sử dụng class `.cour` bạn đã đặt
                if (scoreCell) {
                    return parseInt(scoreCell.textContent.replace('%', ''), 10);
                }
                return 0;
            }

            // 4. Gắn sự kiện 'change' cho CHÍNH dropdown này
            selectElement.addEventListener('change', function() {
                const sortValue = this.value;

                // Sắp xếp mảng các hàng của CHÍNH tab này
                rows.sort((rowA, rowB) => {
                    if (sortValue === 'score_desc') {
                        return getScore(rowB) - getScore(rowA);
                    } else if (sortValue === 'score_asc') {
                        return getScore(rowA) - getScore(rowB);
                    } else {
                        return rowA.dataset.originalIndex - rowB.dataset.originalIndex;
                    }
                });

                // Thêm lại các hàng đã được sắp xếp vào tbody của CHÍNH tab này
                rows.forEach(row => {
                    resultsTbody.appendChild(row);
                });
            });
        });
    });
</script>
 
@endsection
