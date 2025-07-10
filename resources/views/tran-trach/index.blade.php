@extends('welcome')

@section('content')
    <div class="row">
        <div class="col-lg-8">
            <div class=" mt-3 boder_fix">
                <div class="breadcrumb1">
                    <ul>
                        <li class="breadcrumb1-item"><a href="/">Trang chủ</a></li>
                        <li class="breadcrumb1-item">Xem ngày tốt xấu</li>
                        <li class="breadcrumb1-item">Xem Ngày Tốt Yểm Trấn - Trấn Trạch</li>
                    </ul>
                </div>
                <div class="page-head">
                    <h1 class="page-head-title h4 h3-sm h2-md h1-lg text-uppercase">
                        Xem Ngày Tốt Yểm Trấn - Trấn Trạch
                    </h1>
                    <p class="pb-2">Yểm trấn - Trấn trạch là một nghi lễ phong thủy tối quan trọng, được xem là biện pháp
                        sau cùng và "bất khả kháng" khi một ngôi nhà hay mảnh đất gặp phải các vấn đề nghiêm trọng về năng
                        lượng mà các phương pháp hóa giải thông thường không thể xử lý.
                    </p>
                </div>
                {{-- PHẦN FORM GIỮ NGUYÊN --}}
                <div class="card ">
                    <div class="card-body">
                        <form action="{{ route('tran-trach.check') }}" method="POST" class="form_dmy date-picker">
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
                                    <label for="wedding_date_range" class="form-label">Dự kiến thời gian yểm trấn - trấn
                                        trạch <span class="text-danger">*</span></label>
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
                                                                        Tuổi</b></li>
                                                                <li>Dự kiến xuất hành: Trong khoảng
                                                                    {{ $date_start_end[0] }} đến {{ $date_start_end[1] }}
                                                                </li>
                                                            </ul>

                                                        </div>
                                                    </div>

                                                    {{-- <p>{!! $data['year_analysis']['description'] !!}</p> --}}
                                                </div>


                                                @if ($data['year_analysis'])
                                                    <div
                                                        class="d-flex justify-content-between align-items-center mb-3 mt-3">
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
                            <h4>Xem Ngày Tốt Yểm Trấn - Trấn Trạch: An Gia Hưng Vượng, Hóa Giải Hung Khí</h4>
                            <p>Yểm trấn - Trấn trạch là một nghi lễ phong thủy tối quan trọng, được xem là biện pháp sau
                                cùng và "bất khả kháng" khi một ngôi nhà hay mảnh đất gặp phải các vấn đề nghiêm trọng về
                                năng lượng mà các phương pháp hóa giải thông thường không thể xử lý.</p>
                            <p>Mục đích của nghi lễ này là dùng các pháp khí phong thủy có năng lượng mạnh, đặt tại các vị
                                trí chiến lược vào đúng ngày giờ hoàng đạo để trấn áp các luồng hung khí, tà khí, âm khí,
                                lập lại sự cân bằng và bảo vệ sự bình an cho gia trạch. Đây là một đại sự, tuyệt đối không
                                được thực hiện một cách tùy tiện.</p>
                            <div class="h5">Khi Nào Cần Phải Thực Hiện Nghi Lễ Trấn Trạch?</div>
                            <p>Nghi lễ này chỉ nên được xem xét trong những trường hợp thực sự cần thiết sau:</p>
                            <table class="table table-bordered table-actent">
                                <thead>
                                    <tr>
                                       
                                        <th>Trường hợp</th>
                                        <th>Diễn giải</th>
                                    </tr>

                                </thead>
                                <tbody>
                                    <tr>
                                      
                                        <td>
                                            <b>Đất có Lịch sử Phức tạp</b>
                                        </td>
                                        <td>
                                           Mảnh đất từng là nghĩa địa, bãi tha ma, chiến trường, nơi xảy ra án mạng... có thể còn tồn tại nhiều âm khí, oán khí nặng nề.
                                        </td>
                                    </tr>
                                    <tr>
                                        
                                        <td>
                                            <b>Thế Đất Xấu, Nhiều Sát Khí</b>
                                        </td>
                                        <td>
                                        Đất nằm ở các vị trí "đại hung" như đối diện ngã ba hình cây đao, có góc nhọn của công trình khác chĩa thẳng vào, gần bệnh viện, đền miếu, nhà tang lễ.
                                        </td>
                                    </tr>
                                    <tr>
                                      
                                        <td>
                                            <b>Gia Đạo Bất An Liên Tục</b>
                                        </td>
                                        <td>
                                            Các thành viên trong nhà thường xuyên ốm đau không rõ nguyên nhân, ngủ hay gặp ác mộng, gia đình lục đục, công việc làm ăn thất bát liên tiếp một cách bất thường.
                                        </td>
                                    </tr>
                                    <tr>
                                      
                                        <td>
                                            <b>Cảm Nhận Bất Thường</b>
                                        </td>
                                        <td>
                                          Có cảm giác ớn lạnh, có người đi lại trong nhà hoặc các hiện tượng lạ khác mà không thể giải thích được.
                                        </td>
                                    </tr>

                                </tbody>
                            </table>
                            <div class="h5">Tại Sao Việc Chọn Ngày Giờ Lại Tối Quan Trọng?</div>
                            <p>Đây là một "con dao hai lưỡi". Thực hiện đúng cách sẽ mang lại bình an, nhưng làm sai có thể gây "phản tác dụng", khiến tình hình trở nên tồi tệ hơn.</p>

                            <ul style="list-style: circle; margin-left: 2rem;">
                                <li><b>Làm Đúng Ngày Giờ Tốt:</b>Là thời điểm Dương khí cực thịnh, có sự trợ giúp của các vị Thiện Thần. Việc trấn yểm sẽ phát huy tối đa công năng, trấn áp hiệu quả hung khí và nhận được sự bảo hộ.
                                </li>
                                <li><b>Làm Sai Ngày Giờ Xấu:</b> Là thời điểm Âm khí mạnh, hung thần lộng hành. Việc làm lễ lúc này không những không có tác dụng mà còn có thể chọc giận các vong linh, thế lực vô hình, gây ra những hậu quả khôn lường cho gia chủ.
                                </li>
                               
                            </ul>

                            {{-- Đến đây r --}}
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
                                <p class="mt-0 pt-0">Phúc đức tại tâm, không chỉ tại lễ. Cách "giải hạn" hiệu quả nhất là
                                    <b>tu tâm dưỡng tính,
                                        làm nhiều việc thiện, hiếu kính cha mẹ và sống có trách nhiệm</b>. Nghi lễ cúng sao
                                    là một
                                    điểm tựa tinh thần, giúp chúng ta thêm vững tâm trên con đường đó.</p>
                                <p class="fst-italic fw-bolder text-center pt-2">Kính chúc Quý vị một năm mới vạn sự bình
                                    an, cát tường như ý!</p>

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
