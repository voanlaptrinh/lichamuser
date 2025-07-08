@extends('welcome')

@section('content')
    <div class="row">
        <div class="col-lg-8">
            <div class=" mt-3 boder_fix">
                <div class="breadcrumb1">
                    <ul>
                        <li class="breadcrumb1-item"><a href="/">Trang chủ</a></li>
                        <li class="breadcrumb1-item">Xem ngày tốt xấu</li>
                        <li class="breadcrumb1-item">Xem ngày khai trương</li>
                    </ul>
                </div>
                <div class="page-head">
                    <h1 class="page-head-title h4 h3-sm h2-md h1-lg text-uppercase">
                        Xem Ngày Tốt Khai Trương
                    </h1>
                    <p class="pb-2">Ngày khai trương không chỉ là ngày đầu tiên mở cửa kinh doanh. Theo quan niệm phong
                        thủy, đây là thời khắc thiêng liêng để "khai môn" đón luồng sinh khí đầu tiên, là lời trình diện với
                        Thần Tài, Thổ Địa, cầu mong sự phù hộ để công việc làm ăn được thuận lợi, phát đạt.
                    </p>
                </div>
                <div class="card">

                    <div class="card-body">
                        <form action="{{ route('khai-truong.check') }}" method="POST" class="form_dmy date-picker">
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
                                                        <div class="h6"> <b>*  Cho Ngày Khai Trương:</b></div>
                                                        <p>Để ngày trọng đại được diễn ra suôn sẻ, bạn nên chuẩn bị trước:
                                                        </p>
                                                        <ul>
                                                            <li><b>1. Chuẩn Bị Lễ Vật:</b> Sắm sửa mâm cúng Thần Tài - Thổ Địa đầy đủ (hoa quả, tam sên, vàng mã, trầu cau...).
                                                            </li>
                                                            <li><b>2.Chuẩn Bị Văn Khấn:</b> In sẵn bài văn khấn trang trọng, thể hiện lòng thành kính.</li>
                                                            <li><b>3. Chọn Người "Mở Hàng":</b>  Nhờ một người hợp tuổi, tính tình vui vẻ, xởi lởi đến mua hàng đầu tiên để lấy "vía" may mắ.</li>
                                                            <li><b>4. Tạo Không Khí Sôi Động:</b> Mở nhạc vui tươi, có thể tổ chức múa lân, các chương trình khuyến mãi để thu hút dương khí và sự chú ý.</li>
                                                        </ul>
                                                        <p class="fst-italicfw-bolder">Kính chúc Quý Doanh nghiệp khai trương đại cát đại lợi, mã đáo thành công, tiền vào như nước!</p>

                                                    </div>
                                                    {{-- <p>{!! $data['year_analysis']['description'] !!}</p> --}}
                                                </div>


                                                @if ($data['year_analysis'])
                                                    <h4 class="mt-4 mb-3">Bảng điểm chi tiết các ngày tốt</h4>

                                                    <div class="table-responsive">
                                                        <table
                                                            class="table table-bordered table-hover text-center align-middle">
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
                                                                        <td class="fw-bold fs-5">
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
                            <h4>Xem Ngày Đẹp Khai Trương: Khai Trương Hồng Phát, Tiền Vào Như Nước</h4>
                            <p>Một khởi đầu được chọn lựa kỹ càng vào ngày giờ hoàng đạo sẽ là nền tảng vững chắc, quyết
                                định đến vận khí và sự thành bại của cả chặng đường kinh doanh phía trước.</p>
                            <div class="h5">Tại Sao Ngày Khai Trương Là Ngày Tối Thượng?</div>
                            <p>"Đầu xuôi đuôi lọt" - một ngày khai trương tốt sẽ mang lại những lợi ích vô giá cho doanh
                                nghiệp của bạn.</p>
                            <table class="table table-bordered table-actent">
                                <tbody>
                                    <tr>
                                        <td>
                                            <b> Khai Mở Tài Vận</b>
                                        </td>
                                        <td>
                                            Ngày giờ tốt giúp kích hoạt cung Tài Lộc, thu hút dòng tiền, giúp việc buôn bán,
                                            giao dịch diễn ra hanh thông, mang lại lợi nhuận cao.
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <b>Thu Hút Khách Hàng</b>
                                        </td>
                                        <td>
                                            Tạo ra một trường năng lượng tích cực, hấp dẫn, thu hút được nhiều khách hàng
                                            "mở hàng" có "vía tốt", tạo ấn tượng ban đầu mạnh mẽ và lan tỏa danh tiếng.
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <b> Kinh Doanh Thuận Lợi</b>
                                        </td>
                                        <td>
                                            Tránh được những ngày năng lượng xấu, giảm thiểu rủi ro, trục trặc, tranh chấp
                                            trong kinh doanh. Mọi việc vận hành trơn tru, ổn định và phát triển bền vững.
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <b>Tạo Dựng Uy Tín</b>
                                        </td>
                                        <td>
                                            Một buổi lễ khai trương thành công, đông khách sẽ tạo dựng niềm tin và hình ảnh
                                            chuyên nghiệp trong mắt đối tác và khách hàng ngay từ những ngày đầu.

                                        </td>
                                    </tr>

                                </tbody>
                            </table>
                            <div class="h5">YPhương Pháp Luận Giải Chuyên Sâu Của Chúng Tôi</div>
                            <p>Để tìm ra ngày khai trương hoàn hảo, công cụ của chúng tôi không chỉ xem lịch thông thường mà
                                còn phân tích dựa trên các yếu tố phức tạp, dành riêng cho kinh doanh:</p>
                            <ul style="list-style: circle; margin-left: 2rem;">
                                <li><b>Dựa trên Mệnh Trạch Gia Chủ:</b> Phân tích Can Chi, Ngũ hành của người đứng đầu để
                                    tìm ra ngày tương sinh, tương trợ tốt nhất cho vận mệnh của người chủ.</li>
                                <li><b>Xét Theo Ngành Nghề Kinh Doanh: Đây là yếu tố đặc biệt quan trọng!</b> Mỗi ngành nghề
                                    thuộc một hành khác nhau trong Ngũ Hành (ví dụ: Nhà hàng thuộc Hỏa, Kinh doanh vàng bạc
                                    thuộc Kim). Chúng tôi sẽ ưu tiên những ngày có hành tương sinh với ngành nghề của bạn.
                                </li>
                                <li><b>Loại Bỏ Tuyệt Đối Ngày Đại Kỵ:</b> Tự động lọc bỏ các ngày xấu như Tam Nương, Nguyệt
                                    Kỵ, và đặc biệt là các ngày <b>Bất Tương, Thụ Tử, Phế Nhật</b> - những ngày tối kỵ cho
                                    việc khởi sự kinh doanh.</li>
                                <li><b>Ưu Tiên Ngày Cát Tinh Về Tài Lộc:</b> Lựa chọn những ngày có nhiều sao tốt chủ về
                                    tiền tài, danh vọng như <b>Thiên Tài, Thiên Quý, Lộc Mã, Thiên Mã...</b> </li>
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

@endsection
