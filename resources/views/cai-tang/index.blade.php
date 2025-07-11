@extends('welcome')

@section('content')
    <div class="row">
        <div class="col-lg-8">
            <div class=" mt-3 boder_fix">
                <div class="breadcrumb1">
                    <ul>
                        <li class="breadcrumb1-item"><a href="/">Trang chủ</a></li>
                        <li class="breadcrumb1-item">Xem ngày tốt xấu</li>
                        <li class="breadcrumb1-item">Xem ngày tốt để sang cát</li>
                    </ul>
                </div>
                <div class="page-head">
                    <h1 class="page-head-title h4 h3-sm h2-md h1-lg text-uppercase">
                        Xem Ngày Tốt Cải Táng (Sang Cát)
                    </h1>
                    <p class="pb-2">Cải táng, hay còn gọi là sang cát, bốc mộ, là một việc đại sự trong đời sống tâm linh
                        của người Việt. Đây không chỉ là việc di dời phần mộ, mà là một nghi lễ thiêng liêng, thể hiện tấm
                        lòng hiếu kính của con cháu đối với tổ tiên, với mong muốn tìm cho người đã khuất một "ngôi nhà mới"
                        tốt đẹp hơn, khô ráo, có sinh khí, để các Ngài được an giấc ngàn thu.
                    </p>
                </div>
                {{-- PHẦN FORM GIỮ NGUYÊN --}}
                <div class="card ">
                    <div class="card-body">
                        <form action="{{ route('cai-tang.check') }}" method="POST" class="form_dmy date-picker">
                            @csrf

                            <div class="row">
                                <div class="h5 text-center">Thông tin trưởng nam (hoặc người chủ sự):</div>
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
                                    <label for="wedding_date_range" class="form-label">Dự kiến cải táng <span
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
                                <div class="text-center h5">
                                    Thông tin người mất
                                </div>
                                <div class="col-md-6">
                                    <label for="birth_mat" class="form-label">Năm sinh âm lịch</label>
                                    {{-- SỬA Ở ĐÂY --}}
                                    <select name="birth_mat" id="birth_mat" class="form-select">
                                        @php
                                            // Lấy giá trị đã chọn, ưu tiên old() sau đó mới đến $inputs
                                            $selectedBirthYear = old('birth_mat', $inputs['birth_mat'] ?? null);
                                        @endphp
                                        @for ($year = date('Y'); $year >= 1800; $year--)
                                            <option value="{{ $year }}"
                                                {{ $selectedBirthYear == $year ? 'selected' : '' }}>
                                                {{ $year }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label for="nam_mat" class="form-label">Năm Mất âm lịch</label>
                                    {{-- SỬA Ở ĐÂY --}}
                                    <select name="nam_mat" id="nam_mat" class="form-select">
                                        @php
                                            // Lấy giá trị đã chọn, ưu tiên old() sau đó mới đến $inputs
                                            $selectedDeathYear = old('nam_mat', $inputs['nam_mat'] ?? null);
                                        @endphp
                                        @for ($year = date('Y'); $year >= 1800; $year--)
                                            <option value="{{ $year }}"
                                                {{ $selectedDeathYear == $year ? 'selected' : '' }}>
                                                {{ $year }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            <div class="text-end">

                                <button type="submit" class="btn btn-outline-danger">Xem Kết Quả</button>
                            </div>
                        </form>



                        {{-- PHẦN HIỂN THỊ KẾT QUẢ --}}
                        @if (isset($resultsByYear))
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
                            <div class="card-body p-2">
                                <div class="tab-content" id="yearTabContent">
                                    @foreach ($resultsByYear as $year => $data)
                                        <div class="tab-pane fade @if ($loop->first) show active @endif"
                                            id="tab-{{ $year }}" role="tabpanel"
                                            aria-labelledby="tab-{{ $year }}-tab">
                                            <div class="row">
                                                <div class="card shadow-sm mb-4" style="border: 2px dotted #0d6efd;">
                                                    <div class="card-header bg-white border-0 text-center">
                                                        <div class="h6 mb-0 text-uppercase" style="color: #0d6efd;">Thông
                                                            tin
                                                            cơ bản
                                                            về người
                                                            xem</div>
                                                    </div>
                                                    <div class="card-body">
                                                        {{-- Thông tin người đứng lễ --}}
                                                        <div class="fw-bold">Thông tin người đứng lễ</div>
                                                        <ul class="list-unstyled ps-3">

                                                            <li class="mb-2">
                                                                <span class="text-warning me-2">◆</span>
                                                                <strong>Ngày sinh dương lịch:</strong>
                                                                {{ $hostInfo['dob_str'] }}
                                                            </li>
                                                            <li class="mb-2">
                                                                <span class="text-warning me-2">◆</span>
                                                                <strong>Ngày sinh âm lịch:</strong>
                                                                {{ $hostInfo['lunar_dob_str'] }}
                                                                ({{ \App\Helpers\KhiVanHelper::canchiNam($hostInfo['dob_obj']->year) }})
                                                            </li>

                                                            <li class="mb-2">
                                                                <span class="text-warning me-2">◆</span>
                                                                <strong>Tuổi âm:</strong>
                                                                {{ $data['host_analysis']['lunar_age'] }}
                                                                tuổi
                                                            </li>
                                                        </ul>

                                                        {{-- Thông tin người mất --}}
                                                        <div class="fw-bold mt-4">Thông tin về người mất</div>
                                                        <ul class="list-unstyled ps-3">
                                                            <li class="mb-2">
                                                                <span class="text-warning me-2">◆</span>
                                                                <strong>Năm sinh âm lịch:</strong>
                                                                {{ $deceasedInfo['birth_year_lunar'] }}
                                                                ({{ $deceasedInfo['birth_can_chi'] }})
                                                            </li>
                                                            <li class="mb-2">
                                                                <span class="text-warning me-2">◆</span>
                                                                <strong>Năm mất âm lịch:</strong>
                                                                {{ $deceasedInfo['death_year_lunar'] }}
                                                                ({{ $deceasedInfo['death_can_chi'] }})
                                                            </li>
                                                            <li class="mb-2">
                                                                <span class="text-warning me-2">◆</span>
                                                                <strong>Tuổi:</strong> {{ $deceasedInfo['birth_can_chi'] }}
                                                            </li>
                                                        </ul>

                                                        {{-- Thời gian dự kiến --}}

                                                    </div>

                                                </div>
                                            </div>
                                            <div class="row" style="border: 2px dotted #0d6efd;">
                                                <div class="col-lg-6">
                                                    <div class="p-3">
                                                        <h6>Xem tuổi người đứng lễ:</h6>
                                                        @php $deceasedResult = $data['deceased_analysis']; @endphp
                                                        <div>
                                                            Kiểm tra người đừng lễ sinh năm
                                                            {{ $hostInfo['dob_obj']->year }}
                                                            ({{ \App\Helpers\KhiVanHelper::canchiNam($hostInfo['dob_obj']->year) }})
                                                            có gặp hạn Kim Lâu, Tam Tai, Hoang Ốc hoặc xung với năm
                                                            {{ $deceasedResult['check_year_can_chi'] }} không?
                                                        </div>
                                                        <ul>

                                                            <li>
                                                                @if ($data['host_analysis']['kimLau']['is_bad'])
                                                                    {{ $data['host_analysis']['kimLau']['message'] }}
                                                                @else
                                                                    Không phạm kim lâu
                                                                @endif
                                                            </li>
                                                            <li>

                                                                @if ($data['host_analysis']['hoangOc']['is_bad'])
                                                                    Phạm Hoang Ốc
                                                                    {{ $data['host_analysis']['hoangOc']['message'] }}
                                                                @else
                                                                    Không phạm hoang ốc
                                                                @endif

                                                            </li>
                                                            <li>
                                                                @if ($data['host_analysis']['tamTai']['is_bad'])
                                                                    {{ $data['host_analysis']['tamTai']['message'] }}
                                                                @else
                                                                    Không phạm tam tai
                                                                @endif

                                                            </li>
                                                            <li>

                                                                @if ($data['host_analysis']['thaiTue']['is_pham'])
                                                                    Năm {{ $deceasedResult['check_year_can_chi'] }}
                                                                    ({{ $deceasedResult['check_year'] }}) xung với tuổi
                                                                    {{ \App\Helpers\KhiVanHelper::canchiNam($hostInfo['dob_obj']->year) }}
                                                                    ({{ $hostInfo['dob_obj']->year }})
                                                                @else
                                                                    Năm {{ $deceasedResult['check_year_can_chi'] }}
                                                                    ({{ $deceasedResult['check_year'] }}) Không xung với
                                                                    tuổi
                                                                    {{ \App\Helpers\KhiVanHelper::canchiNam($hostInfo['dob_obj']->year) }}
                                                                    ({{ $hostInfo['dob_obj']->year }})
                                                                @endif

                                                            </li>
                                                        </ul>
                                                        <p class="mt-1"><strong>Kết luận:</strong></p>
                                                        <div
                                                            class="alert @if ($data['host_analysis']['is_bad_year']) alert-danger @else alert-success @endif">

                                                            {!! $data['host_analysis']['description'] !!}
                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="p-3">
                                                        <h6>Xem tuổi người mất:</h6>
                                                        <p>Kiểm tra năm <strong>{{ $deceasedResult['check_year_can_chi'] }}
                                                                ({{ $deceasedResult['check_year'] }})
                                                            </strong> có phạm Thái Tuế, Tuế Phá (lục xung) với
                                                            tuổi người mất hay không?</p>
                                                        <strong>Người mất sinh năm
                                                            {{ $deceasedResult['deceased_birth_year'] }}
                                                            ({{ $deceasedResult['deceased_can_chi'] }}):</strong>
                                                        <ul class="list-unstyled ps-3">
                                                            <li>→ Năm {{ $deceasedResult['check_year_can_chi'] }}
                                                                @if ($deceasedResult['is_thai_tue'])
                                                                    <strong class="text-danger">Phạm Thái Tuế</strong>
                                                                @else
                                                                    <strong class="text-success">Không phạm Thái
                                                                        Tuế</strong>
                                                                @endif
                                                            </li>
                                                            <li>→ @if ($deceasedResult['is_tue_pha'])
                                                                    <strong class="text-danger">Phạm Tuế Phá (xung Thái
                                                                        Tuế)</strong>
                                                                @else
                                                                    <strong class="text-success">Không phạm Tuế
                                                                        Phá</strong>
                                                                @endif
                                                            </li>
                                                        </ul>
                                                        <p class="mt-3"><strong>Kết luận:</strong></p>
                                                        <div
                                                            class="alert @if ($deceasedResult['is_bad']) alert-danger @else alert-success @endif">
                                                            {!! $deceasedResult['conclusion'] !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="info-panel">
                                                <div class="h6"> <b>* Những Điều Tối Quan Trọng Cần Lưu Ý</b></div>

                                                <ul>
                                                    <li><b>1. Người có thai không nên tham gia:</b> Phụ nữ có thai nên tránh
                                                        đến gần khu vực làm lễ để tránh ảnh hưởng không tốt đến thai nhi.
                                                    </li>
                                                    <li><b>2. Chuẩn bị lễ vật và văn khấn:</b> Cần chuẩn bị mâm cúng ở cả
                                                        khu mộ cũ và mộ mới, cùng với bài văn khấn xin phép Thổ Thần, Thổ
                                                        Địa.</li>
                                                    <li><b>3. Dấu hiệu tốt khi bốc mộ:</b> Khi mở nắp quan tài, nếu thấy
                                                        xương cốt còn nguyên vẹn, có màu vàng sáng, khô ráo thì đó là dấu
                                                        hiệu của đất tốt, báo hiệu phúc lộc. </li>
                                                    <li><b>4. "Nhà mới" phải tốt hơn nhà cũ:</b> Vị trí đất để sang cát phải
                                                        được chọn lựa kỹ càng, là nơi cao ráo, sạch sẽ, có hướng tốt.</li>
                                                </ul>
                                                <p class="fst-italic fw-bolder text-center pt-2">Kính chúc gia quyến vạn sự
                                                    chu toàn, thực hiện nghi lễ viên mãn, mang lại phúc đức cho gia tộc!</p>

                                            </div>
                                            <div class="d-flex justify-content-between align-items-center mb-3 mt-3">
                                                <h4 class="mb-0 title-date-chitite-tot">Bảng điểm chi tiết các ngày
                                                    tốt</h4>
                                                <div class="d-flex align-items-center ">
                                                    <select class="form-select sort-select" id="">
                                                        <option value="date" selected>Theo ngày (Mặc định)
                                                        </option>
                                                        <option value="score_desc"> Cao đến thấp</option>
                                                        <option value="score_asc">Thấp đến cao</option>
                                                    </select>
                                                </div>
                                            </div>
                                            @if ($data['days'])
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


                                                            @forelse($data['days'] as $day)
                                                                @php
                                                                    if (!function_exists('getRatingClassBuildHouse')) {
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
                                                                        <p class="mb-0">Trong khoảng thời gian bạn chọn
                                                                            của năm
                                                                            nay,
                                                                            không tìm thấy ngày nào thực sự tốt để tiến hành
                                                                            xây
                                                                            dựng.
                                                                        </p>
                                                                        <small>Bạn có thể thử mở rộng khoảng thời gian tìm
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
                        @else
                            <h4>Xem Ngày Tốt Cải Táng (Sang Cát): An Lành Cho Tiên Tổ, Phúc Lộc Cho Con Cháu</h4>
                            <p>Việc này ảnh hưởng vô cùng sâu sắc đến sự an yên của người đã khuất và cả hậu vận, phúc khí
                                của toàn bộ gia tộc. Vì vậy, chọn lựa ngày giờ phải được tiến hành một cách cẩn trọng tột
                                bậc.</p>
                            <div class="h5">Tại Sao Phải Cẩn Trọng Tột Bậc Khi Chọn Ngày Cải Táng?</div>
                            <p>Sai một ly, đi một dặm. Việc làm kinh động đến phần mộ của tổ tiên vào sai thời điểm có thể
                                dẫn đến những hệ lụy khôn lường.</p>
                            <table class="table table-bordered table-actent">
                                <tbody>
                                    <tr>
                                        <td>
                                            <b>Giúp Tiên Tổ An Yên</b>
                                        </td>
                                        <td>
                                            Mục đích cao cả nhất là giúp người đã khuất có một nơi an nghỉ tốt hơn, không bị
                                            ẩm ướt, không bị xâm phạm, từ đó linh hồn được siêu thoát, an lạc.
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <b>Khai Mở Vận Mạch Mới</b>
                                        </td>
                                        <td>
                                            Khi mồ mả tổ tiên được đặt vào nơi có long mạch tốt, vào đúng ngày giờ vượng
                                            khí, sẽ phù hộ độ trì cho con cháu làm ăn phát đạt, sức khỏe dồi dào, gia đạo
                                            thịnh vượng.
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <b>Tránh Phạm Phải Đại Kỵ</b>
                                        </td>
                                        <td>
                                            Cải táng sai ngày có thể phạm vào các ngày đại hung như <b>Trùng Tang, Trùng
                                                Phục, Sát Chủ</b>, làm kinh động đến vong linh, gây ảnh hưởng xấu đến sức
                                            khỏe, tài vận của con cháu.
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <b>Thể Hiện Lòng Hiếu Kính</b>
                                        </td>
                                        <td>
                                            Sự chuẩn bị chu đáo, thực hiện nghi lễ đúng cách thể hiện trọn vẹn tấm lòng
                                            thành kính, biết ơn của con cháu đối với nguồn cội, tổ tiên.
                                        </td>
                                    </tr>

                                </tbody>
                            </table>
                            <div class="h5">Phương Pháp Luận Giải Theo Chuyên Gia</div>
                            <p>Xem ngày cải táng là một trong những việc phức tạp nhất trong phong thủy, đòi hỏi phải xem
                                xét nhiều yếu tố cùng lúc:</p>
                            <ul style="list-style: circle; margin-left: 2rem;">
                                <li><b>Dựa Vào Tuổi Người Mất và Trưởng Nam:</b> Phải đối chiếu Can Chi, Ngũ hành của người
                                    đã mất và người trưởng nam (hoặc người đứng đầu lo việc) để tránh ngày xung khắc.</li>
                                <li><b>Thời Điểm Trong Năm:</b> Việc cải táng thường được thực hiện vào cuối Thu hoặc mùa
                                    Đông, khi thời tiết khô ráo, vạn vật ngừng sinh trưởng, ít ảnh hưởng đến long mạch nhất.
                                </li>
                                <li><b>Loại Trừ Tuyệt Đối Ngày Xấu:</b> Sàng lọc và loại bỏ hoàn toàn các ngày đại kỵ, các
                                    sao xấu chủ về tang ma, mồ mả.</li>
                                <li><b>Chọn Giờ Hoàng Đạo:</b> Việc cải táng thường diễn ra vào ban đêm hoặc sáng sớm, khi
                                    âm khí hội tụ. Chọn đúng giờ để thực hiện các nghi thức quan trọng là điều bắt buộc.
                                </li>
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
            // 1. Tìm tất cả các container của từng tab (mỗi `.tab-pane` là một tab).
            const allTabPanes = document.querySelectorAll('.tab-pane');

            // 2. Lặp qua mỗi tab để thiết lập trình sắp xếp riêng cho nó.
            allTabPanes.forEach(tabPane => {
                // Tìm dropdown và tbody *chỉ bên trong tab hiện tại* bằng class.
                const sortSelect = tabPane.querySelector('.sort-select');
                const tableBody = tabPane.querySelector('.results-tbody');

                // 3. Nếu tab này không có bảng kết quả, bỏ qua để tránh lỗi.
                if (!sortSelect || !tableBody) {
                    return; // Chuyển sang tab tiếp theo.
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
                        // parseInt sẽ tự động bỏ qua các ký tự không phải số ở cuối chuỗi.
                        return parseInt(scoreCell.textContent, 10);
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
                    rows.forEach(row => {
                        tableBody.appendChild(row);
                    });
                });
            });
        });
    </script>
@endsection
