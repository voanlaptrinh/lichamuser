@extends('welcome')

@section('content')
    <div class="row">
        <div class="col-lg-8">
            <div class=" mt-3 boder_fix">
                <div class="breadcrumb1">
                    <ul>
                        <li class="breadcrumb1-item"><a href="/">Trang chủ</a></li>
                        <li class="breadcrumb1-item">Xem ngày tốt xấu</li>
                        <li class="breadcrumb1-item">Xem ngày tốt lập ban thờ</li>
                    </ul>
                </div>
                <div class="page-head">
                    <h1 class="page-head-title h4 h3-sm h2-md h1-lg text-uppercase">
                        Xem Ngày Tốt Lập Bàn Thờ
                    </h1>
                    <p class="pb-2">Lập bàn thờ là nghi lễ thiêng liêng và trọng đại nhất khi bắt đầu cuộc sống tại một
                        nơi ở mới hoặc khởi sự kinh doanh. Đây không phải là việc sắp đặt đồ đạc, mà là nghi thức <b>"khai
                            môn lập vị"</b>, chính thức thỉnh mời các vị Thần linh, Gia tiên về ngự, chứng giám và phù hộ độ
                        trì.
                    </p>
                </div>
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('lap-ban-tho.check') }}" method="POST" class="form_dmy date-picker">
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
                                                                        Tuổi</b>
                                                                </li>
                                                                <li>Dự kiến xuất hành: Trong khoảng
                                                                    {{ $date_start_end[0] }} đến
                                                                    {{ $date_start_end[1] }} </li>
                                                            </ul>

                                                        </div>
                                                    </div>
                                                    <div class="info-panel">
                                                        <div class="h6"> <b>* Quy Trình Việc Lập Bàn Thờ (Tham Khảo)</b>
                                                        </div>

                                                        <ul>
                                                            <li><b>1. Chọn Bàn Thờ và Vị Trí:</b> Lựa chọn kích thước, chất
                                                                liệu bàn thờ phù hợp. Vị trí đặt phải trang nghiêm, sạch sẽ,
                                                                có điểm tựa vững chắc.
                                                            </li>
                                                            <li><b>2. Chuẩn Bị Đồ Thờ Cúng:</b> Sắm sửa đầy đủ các vật phẩm
                                                                cần thiết như bát hương, bài vị, di ảnh, chén nước, lọ
                                                                hoa...</li>
                                                            <li><b>3. Tẩy Uế (Khai Quang):</b> Tất cả đồ thờ mới mua về phải
                                                                được lau sạch bằng rượu gừng hoặc nước thơm ngũ vị để thanh
                                                                tẩy, loại bỏ uế khí.</li>
                                                            <li><b>4. Bốc Bát Hương</b> Chuẩn bị cốt cho bát hương (tro nếp,
                                                                thất bảo...) và thực hiện nghi lễ bốc bát hương một cách cẩn
                                                                thận.</li>
                                                            <li><b>5. Chuẩn Bị Lễ Vật:</b> Sắm một mâm cúng đầy đủ, tươm tất
                                                                để dâng lên trong ngày an vị.</li>
                                                        </ul>
                                                        <p class="fst-italic fw-bolder text-center pt-2">Kính chúc Quý gia
                                                            chủ vạn sự hanh thông, an vị bàn thờ viên mãn, gia đạo hưng
                                                            thịnh, tài lộc dồi dào!</p>

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
                            <h4>Xem Ngày Tốt Lập Bàn Thờ Thần Tài, Gia Tiên: Rước Tài Lộc, Giữ Gìn Gia Đạo</h4>
                            <p>Một bàn thờ được an vị đúng ngày, đúng giờ, đúng phương vị sẽ là <b>cầu nối tâm linh vững
                                    chắc</b>,
                                là nền tảng để thu hút phúc khí, giữ gìn gia đạo và khai mở con đường tài lộc.
                            </p>
                            <div class="h5">Tại Sao Lập Bàn Thờ Là Việc Trọng Đại Bậc Nhất?</div>
                            <p>Việc thiết lập "trụ sở tâm linh" của gia đình một cách tùy tiện có thể dẫn đến những hệ lụy
                                không mong muốn. Ngược lại, một khởi đầu đúng đắn sẽ mang lại vô vàn lợi ích.</p>
                            <table class="table table-bordered table-actent">
                                <tbody>
                                    <tr>
                                        <td>
                                            <b>Khai Môn Lập Vị</b>
                                        </td>
                                        <td>
                                           Là lời mời chính thức và trang trọng nhất để các vị Thần linh, Gia tiên về ngự. Một lời mời đúng lễ nghi sẽ nhận được sự chứng giám và ủng hộ.
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <b>	Nền Tảng Phúc Khí</b>
                                        </td>
                                        <td>
                                           Bàn thờ là nơi "tụ khí" của cả ngôi nhà. Lập bàn thờ vào ngày giờ tốt sẽ giúp tụ được vượng khí, sinh khí, làm nền tảng cho sự may mắn và thịnh vượng lâu dài.
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <b>Chiêu Tài Kích Lộc</b>
                                        </td>
                                        <td>
                                           Đặc biệt với bàn thờ Thần Tài - Thổ Địa, việc an vị đúng cách sẽ giúp khai mở cung Tài lộc, giúp việc kinh doanh, buôn bán hanh thông, tiền vào như nước.
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <b>	Gia Đạo Bình An</b>
                                        </td>
                                        <td>
                                           Bàn thờ là nơi con cháu hướng về, thể hiện lòng thành kính. Khi bàn thờ được an vị, gia đạo sẽ có quy củ, con cháu được che chở, gia đình hòa thuận, bình an.

                                        </td>
                                    </tr>

                                </tbody>
                            </table>
                            <div class="h5">Phương Pháp Luận Giải Theo Chuyên Gia</div>
                            <p>Lập bàn thờ là việc phức tạp, đòi hỏi sự kết hợp của nhiều yếu tố phong thủy chứ không chỉ đơn thuần là chọn ngày đẹp:</p>
                            <ul style="list-style: circle; margin-left: 2rem;">
                                <li><b>Phân Tích Tuổi Gia Chủ:</b> Phân tích Can-Chi, Ngũ Hành của người chủ gia đình (thường
                                    là nam giới) để tìm ngày tương sinh, tránh ngày xung khắc.
                                </li>
                                <li><b>Xét Hướng Đặt Bàn Thờ: Đây là yếu tố tối quan trọng:</b> Hướng bàn thờ phải "tọa cát hướng cát" (đặt ở phương vị tốt, nhìn về hướng tốt) theo tuổi của gia chủ. Công cụ của chúng tôi sẽ giúp bạn xác định điều này.
                                </li>
                                <li><b>Phân Biệt Loại Bàn Thờ:</b> Cách xem ngày và chọn hướng cho bàn thờ Gia tiên và bàn thờ Thần Tài - Thổ Địa có những nguyên tắc khác nhau. Chúng tôi sẽ phân tích dựa trên lựa chọn của bạn.</li>
                                <li><b>Loại Trừ Ngày Giờ Xấu:</b> Tự động lọc bỏ các ngày giờ đại kỵ, có sao xấu chiếu mệnh, đảm bảo sự an toàn và trang nghiêm tuyệt đối cho nghi lễ.
                                </li>
                            </ul>
                             <div class="info-panel">
                                                        <div class="h6"> <b>* Quy Trình Việc Lập Bàn Thờ (Tham Khảo)</b>
                                                        </div>

                                                        <ul>
                                                            <li><b>1. Chọn Bàn Thờ và Vị Trí:</b> Lựa chọn kích thước, chất
                                                                liệu bàn thờ phù hợp. Vị trí đặt phải trang nghiêm, sạch sẽ,
                                                                có điểm tựa vững chắc.
                                                            </li>
                                                            <li><b>2. Chuẩn Bị Đồ Thờ Cúng:</b> Sắm sửa đầy đủ các vật phẩm
                                                                cần thiết như bát hương, bài vị, di ảnh, chén nước, lọ
                                                                hoa...</li>
                                                            <li><b>3. Tẩy Uế (Khai Quang):</b> Tất cả đồ thờ mới mua về phải
                                                                được lau sạch bằng rượu gừng hoặc nước thơm ngũ vị để thanh
                                                                tẩy, loại bỏ uế khí.</li>
                                                            <li><b>4. Bốc Bát Hương</b> Chuẩn bị cốt cho bát hương (tro nếp,
                                                                thất bảo...) và thực hiện nghi lễ bốc bát hương một cách cẩn
                                                                thận.</li>
                                                            <li><b>5. Chuẩn Bị Lễ Vật:</b> Sắm một mâm cúng đầy đủ, tươm tất
                                                                để dâng lên trong ngày an vị.</li>
                                                        </ul>
                                                        <p class="fst-italic fw-bolder text-center pt-2">Kính chúc Quý gia
                                                            chủ vạn sự hanh thông, an vị bàn thờ viên mãn, gia đạo hưng
                                                            thịnh, tài lộc dồi dào!</p>

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
