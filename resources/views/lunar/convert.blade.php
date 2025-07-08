@extends('welcome')
@section('content')

    <div class="row">
        <div class="col-lg-8">

            <div class=" mt-3 boder_fix">
                <div class="breadcrumb1">
                    <ul>
                        <li class="breadcrumb1-item"><a href="/">Trang chủ</a></li>
                        <li class="breadcrumb1-item">Đổi ngày âm dương</li>
                    </ul>
                </div>
                <div class="page-head">
                    <h1 class="page-head-title h4 h3-sm h2-md h1-lg ">
                        Xem ngày tốt xấu hôm nay {{ $dd }}/{{ $mm }}/{{ $yy }} - Lịch âm
                        {{ $al[0] }}/{{ $al[1] }}/{{ $al[2] }}, Lịch Vạn Niên {{ $yy }}
                    </h1>
                </div>
                <hr>
                {{-- CSS để tạo giao diện tùy chỉnh cho input date (giữ nguyên, không đổi) --}}
                <style>
                    .date-picker-wrapper {
                        position: relative;
                        display: flex;
                        align-items: center;
                        cursor: pointer;
                        /* Thêm con trỏ chuột để người dùng biết có thể click */
                    }

                    .date-picker-wrapper .custom-date-display {
                        background-color: #fff;
                        pointer-events: none;
                    }

                    .date-picker-wrapper .native-date-input {
                        position: absolute;
                        left: 0;
                        top: 0;
                        width: 100%;
                        height: 100%;
                        opacity: 0;
                        /* Không cần cursor ở đây nữa vì wrapper đã có */
                        border: none;
                    }

                    .date-picker-wrapper::after {
                        content: '📅';
                        position: absolute;
                        right: 12px;
                        top: 50%;
                        transform: translateY(-50%);
                        pointer-events: none;
                    }
                </style>

                {{-- Form chuyển đổi ngày --}}
                <form method="POST" action="{{ route('doi-lich') }}" id="convertForm" class="pb-3">
                    @csrf

                    <input type="hidden" name="cdate" id="cdate"
                        value="{{ old('cdate', $cdate ?? \Carbon\Carbon::now()->format('Y-m-d')) }}">

                    <div class="row g-2">
                        <!-- Cột Ngày Dương Lịch -->
                        <div class="col-lg-5">
                            <div class="form-group">
                                {{-- THAY ĐỔI: "for" trỏ vào input date gốc để tăng độ nhạy khi click label --}}
                                <div for="duong_date_native" class=" txt-native">Chọn Ngày Dương <span class="text-danger">*</span></div>
                                {{-- THAY ĐỔI: Thêm ID cho div wrapper để bắt sự kiện click --}}
                                <div class="date-picker-wrapper" id="duong_date_wrapper">
                                    <input type="text" id="duong_date_display" class="form-control custom-date-display"
                                        readonly>
                                    <input type="date" id="duong_date_native" class="native-date-input">
                                </div>
                            </div>
                        </div>

                        <!-- Cột Ngày Âm Lịch -->
                        <div class="col-lg-5">
                            <div class="form-group">
                                {{-- THAY ĐỔI: "for" trỏ vào input date gốc --}}
                                <div for="am_date_native" class="  txt-native">Hoặc chọn Ngày Âm <span class="text-danger">*</span></div>
                                {{-- THAY ĐỔI: Thêm ID cho div wrapper --}}
                                <div class="date-picker-wrapper" id="am_date_wrapper">
                                    <input type="text" id="am_date_display" class="form-control custom-date-display"
                                        readonly>
                                    <input type="date" id="am_date_native" class="native-date-input">
                                </div>
                            </div>
                        </div>

                        <!-- Nút Submit -->
                        <div class="col-lg-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-outline-danger w-100">Chuyển đổi</button>
                        </div>
                    </div>
                </form>

                {{-- Toàn bộ JavaScript xử lý logic đã được nâng cấp --}}
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        // --- Lấy các element cần thiết từ DOM ---
                        const form = document.getElementById('convertForm');
                        const cdateInput = document.getElementById('cdate');

                        // NÂNG CẤP: Lấy cả các div wrapper
                        const duongDateWrapper = document.getElementById('duong_date_wrapper');
                        const duongDateNative = document.getElementById('duong_date_native');
                        const duongDateDisplay = document.getElementById('duong_date_display');

                        const amDateWrapper = document.getElementById('am_date_wrapper');
                        const amDateNative = document.getElementById('am_date_native');
                        const amDateDisplay = document.getElementById('am_date_display');

                        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                        // --- Các hàm tiện ích (giữ nguyên logic của bạn) ---
                        function formatDateToDisplay(isoDate) {
                            if (!isoDate || isoDate.split('-').length !== 3) return '';
                            const [year, month, day] = isoDate.split('-');
                            return `${day}/${month}/${year}`;
                        }

                        async function convertDate(dateValue, apiUrl, paramName) {
                            if (!dateValue) return null;
                            try {
                                const bodyPayload = {
                                    [paramName]: dateValue
                                };
                                const response = await fetch(apiUrl, {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'Accept': 'application/json',
                                        'X-CSRF-TOKEN': csrfToken
                                    },
                                    body: JSON.stringify(bodyPayload)
                                });
                                if (!response.ok) {
                                    console.error('Lỗi từ server:', await response.text());
                                    return null;
                                }
                                const data = await response.json();
                                return data.date || null;
                            } catch (error) {
                                console.error('Lỗi khi gọi API:', error);
                                return null;
                            }
                        }

                        // --- Khởi tạo giá trị ban đầu (giữ nguyên) ---
                        function initializeForm() {
                            const initialSolarDate = cdateInput.value;
                            if (initialSolarDate) {
                                duongDateNative.value = initialSolarDate;
                                duongDateDisplay.value = formatDateToDisplay(initialSolarDate);
                                convertDate(initialSolarDate, "{{ route('api.to.am') }}", 'duong_date').then(lunarDate => {
                                    if (lunarDate) {
                                        amDateNative.value = lunarDate;
                                        amDateDisplay.value = formatDateToDisplay(lunarDate);
                                    }
                                });
                            }
                        }
                        initializeForm();

                        // ==========================================================
                        // ===== NÂNG CẤP TĂNG ĐỘ NHẠY KHI CLICK - BẮT ĐẦU =====
                        // ==========================================================

                        // Khi click vào vùng wrapper của ngày Dương, chủ động mở lịch
                        duongDateWrapper.addEventListener('click', function() {
                            try {
                                duongDateNative.showPicker();
                            } catch (error) {
                                // Fallback cho các trình duyệt cũ không hỗ trợ showPicker()
                                console.error("Trình duyệt không hỗ trợ showPicker().", error);
                            }
                        });

                        // Khi click vào vùng wrapper của ngày Âm, chủ động mở lịch
                        amDateWrapper.addEventListener('click', function() {
                            try {
                                amDateNative.showPicker();
                            } catch (error) {
                                console.error("Trình duyệt không hỗ trợ showPicker().", error);
                            }
                        });

                        // ==========================================================
                        // ===== NÂNG CẤP TĂNG ĐỘ NHẠY KHI CLICK - KẾT THÚC =====
                        // ==========================================================

                        // --- Gắn sự kiện `change` (giữ nguyên logic xử lý dữ liệu của bạn) ---

                        // 1. Sự kiện khi người dùng đã CHỌN xong một NGÀY DƯƠNG
                        duongDateNative.addEventListener('change', async function() {
                            const selectedSolarDate = this.value;
                            cdateInput.value = selectedSolarDate;
                            duongDateDisplay.value = formatDateToDisplay(selectedSolarDate);
                            const newLunarDate = await convertDate(selectedSolarDate, "{{ route('api.to.am') }}",
                                'duong_date');
                            amDateNative.value = newLunarDate;
                            amDateDisplay.value = formatDateToDisplay(newLunarDate);
                        });

                        // 2. Sự kiện khi người dùng đã CHỌN xong một NGÀY ÂM
                        amDateNative.addEventListener('change', async function() {
                            const selectedLunarDate = this.value;
                            amDateDisplay.value = formatDateToDisplay(selectedLunarDate);
                            const newSolarDate = await convertDate(selectedLunarDate,
                                "{{ route('api.to.duong') }}", 'am_date');
                            if (newSolarDate) {
                                cdateInput.value = newSolarDate;
                                duongDateNative.value = newSolarDate;
                                duongDateDisplay.value = formatDateToDisplay(newSolarDate);
                            }
                        });

                        // 3. Kiểm tra trước khi submit form (giữ nguyên)
                        form.addEventListener('submit', (e) => {
                            if (!cdateInput.value) {
                                e.preventDefault();
                                alert("Vui lòng chọn một ngày để xem chi tiết.");
                            }
                        });
                    });
                </script>

                <div class="row">

                    <div class="col-lg-12 col-md-12">


                        <div class="calendar-boxs">
                            <div class="boxs_slider" id="boxlichngay">
                                <div class="calendar-slider">
                                    <div class="calendar-header">
                                        <div class="w-header">
                                            <a href="{{ route('lich.thang', ['nam' => $prevYear, 'thang' => $prevMonth]) }}"
                                                class="nav-arrow ic-view-calendar" title="Tháng trước"><i
                                                    class="bi bi-caret-left"></i>
                                            </a>
                                            <a class="ddmmyy" title="Tháng {{ $mm }} năm {{ $yy }}">
                                                Tháng
                                                {{ $mm }} năm {{ $yy }}</a>
                                            <a href="{{ route('lich.thang', ['nam' => $nextYear, 'thang' => $nextMonth]) }}"
                                                class="nav-arrow ic-next-calendar" title="Tháng sau">
                                                <i class="bi bi-caret-right"></i>

                                            </a>

                                        </div>
                                    </div>
                                    <div class="lichngayparent">
                                        <div class="lichngay">
                                            <div class="calendar-box1 d-flex justify-content-between">
                                                <div class="d-flex align-items-center">
                                                    <a href="#" id="prev-day-btn" class="nav-arrow"
                                                        title="Ngày hôm trước"><i class="bi bi-chevron-left"></i></a>
                                                </div>
                                                <div>
                                                    <a title="Xem ngày 07/07/2025"
                                                        class="rows-ngay">{{ $dd }}</a>
                                                    <div class="rows-thu">{{ $weekday }}</div>
                                                    <div class="row-event holiday active" id="lntholiday0">
                                                        @foreach ($suKienHomNay as $suKien)
                                                            <i class="ic icon_star ic-event"></i>
                                                            <span>{{ $suKien['ten_su_kien'] ?? $suKien }}</span>
                                                        @endforeach
                                                    </div>

                                                </div>
                                                <div class="d-flex align-items-center">
                                                    <a href="#" id="next-day-btn" class="nav-arrow"
                                                        title="Ngày hôm sau"><i class="bi bi-chevron-right"></i></a>
                                                </div>

                                            </div>
                                            <div class="calendar-box2">
                                                <div class="calendar-col2 text-right">
                                                    <div class="calendar-info1">
                                                        @if ($getThongTinCanChiVaIcon['icon_ngay'])
                                                            <img src="{{ $getThongTinCanChiVaIcon['icon_ngay'] }}"
                                                                alt="Chi Icon" class="img-12congiap">
                                                        @else
                                                            <p>Không tìm thấy icon.</p>
                                                        @endif
                                                        <span class="ngay-am">{{ $al[0] }}</span>
                                                        {{-- <img class="img-12congiap"
                                                    src="/content/images/con-giap/Suu.png"> <span class="ngay-am">13</span> --}}
                                                    </div>
                                                    <p><strong>Ngày Hắc đạo</strong></p>
                                                    <p><strong>Năm {{ $getThongTinCanChiVaIcon['can_chi_nam'] }}</strong>
                                                    </p>
                                                    <p>Tháng {{ $getThongTinCanChiVaIcon['can_chi_thang'] }}</p>
                                                    <p>Ngày {{ $canChi }}</p>
                                                    <p>Tiết khí: {{ $tietkhi['tiet_khi'] }}</p>
                                                </div>
                                                <div class="calendar-col2">
                                                    <div class="calendar-info2">THÁNG {{ $al[1] }}
                                                        ({{ $al[4] }})
                                                    </div>
                                                    <p><strong>Giờ Hoàng Đạo:</strong></p>
                                                    @foreach ($gioHdData as $gioHdItems)
                                                        <p>{{ $gioHdItems }}</p>
                                                    @endforeach

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="calendar-bottom center d-flex justify-content-between">
                                        <a class="calendar-link left">Hôm qua</a>
                                        <a>Hôm
                                            nay</a>
                                        <a class="calendar-link-right">Ngày
                                            mai</a>
                                    </div>
                                </div>
                            </div>
                        </div>



                    </div>


                </div>

                <div class="mt-4">
                    <div class="col-lg-12">

                        <div class="card-body p-0">
                            <ul class="nav nav-tabs flex-wrap w-100 text-center customers-nav-tabs" role="tablist">
                                <li class="nav-item flex-fill border-top" role="presentation">
                                    <a class="nav-link active " id="pills-home-tab-nobd" data-bs-toggle="pill"
                                        href="#pills-home-nobd" role="tab" aria-controls="pills-home-nobd"
                                        aria-selected="true">Tóm tắt </a>
                                </li>
                                <li class="nav-item flex-fill border-top" role="presentation">
                                    <a class="nav-link" id="pills-profile-tab-nobd" data-bs-toggle="pill"
                                        href="#pills-profile-nobd" role="tab" aria-controls="pills-profile-nobd"
                                        aria-selected="false">Chi
                                        tiết</a>
                                </li>
                            </ul>

                            <div class="tab-content mb-3" id="pills-without-border-tabContent">
                                <div class="tab-pane fade show active" id="pills-home-nobd" role="tabpanel"
                                    aria-labelledby="pills-home-tab-nobd">
                                    <div class="border border-top-0 p-2">
                                        <p>{!! $getDaySummaryInfo['intro_paragraph'] !!}</p>

                                        <div class="h5">Đánh giá ngày
                                            {{ round($getDaySummaryInfo['score']['percentage']) }} Điểm -
                                            {{ $getDaySummaryInfo['score']['rating'] }}</div>

                                        <p>
                                            - Các yếu tố tốt xuất hiện trong ngày:
                                            @if (
                                                $nhiThapBatTu['nature'] == 'Tốt' ||
                                                    $getThongTinTruc['description']['rating'] == 'Tốt' ||
                                                    $getSaoTotXauInfo['sao_tot']
                                            )
                                                @if ($nhiThapBatTu['nature'] == 'Tốt')
                                                    Sao {{ $nhiThapBatTu['name'] }} (Nhị thập bát tú)
                                                @endif
                                                @if ($getThongTinTruc['description']['rating'] == 'Tốt')
                                                    Trực {{ $getThongTinTruc['title'] }} (Thập nhị trực),
                                                @endif
                                                @if ($getSaoTotXauInfo['sao_tot'])
                                                    Sao:
                                                @endif

                                                @foreach ($getSaoTotXauInfo['sao_tot'] as $tenSao => $yNghia)
                                                    @if (!empty($getSaoTotXauInfo['sao_tot']))
                                                        {{ $loop->first ? '' : ', ' }}{{ $tenSao }}
                                                    @endif
                                                @endforeach
                                            @else
                                                Không có Yếu tố nào
                                            @endif
                                        </p>

                                        <p>
                                            - Các yếu tố xấu xuất hiện trong ngày:
                                            @if ($nhiThapBatTu['nature'] == 'Xấu')
                                                Sao {{ $nhiThapBatTu['name'] }} (Nhị thập bát tú),
                                            @endif

                                            @if ($getThongTinTruc['description']['rating'] == 'Xấu')
                                                Trực {{ $getThongTinTruc['title'] }} (Thập nhị trực),
                                            @endif
                                            Sao: @foreach ($getSaoTotXauInfo['sao_xau'] as $tenSao => $yNghia)
                                                {{ $loop->first ? '' : ', ' }}{{ $tenSao }}
                                            @endforeach
                                        </p>
                                        <div>
                                            <b>* Việc nên làm</b>
                                            <div>
                                                <ul>
                                                    @if (!empty($nhiThapBatTu['guidance']['good']))
                                                        <li>{{ $nhiThapBatTu['guidance']['good'] }} (Nhị thập bát tú -
                                                            {{ $nhiThapBatTu['name'] }})</li>
                                                    @endif
                                                    @if (!empty($getThongTinTruc['description']['good']))
                                                        <li>
                                                            {{ $getThongTinTruc['description']['good'] }} (Thập nhị trực -
                                                            {{ $getThongTinTruc['title'] }})
                                                        </li>
                                                    @endif
                                                </ul>
                                            </div>
                                            <b>* Việc không nên làm</b>
                                            <div>
                                                <ul>
                                                    @if (!empty($nhiThapBatTu['guidance']['bad']))
                                                        <li>{{ $nhiThapBatTu['guidance']['bad'] }} (Nhị thập bát tú - sao
                                                            {{ $nhiThapBatTu['name'] }})</li>
                                                    @endif
                                                    @if (!empty($getThongTinTruc['description']['bad']))
                                                        <li>
                                                            {{ $getThongTinTruc['description']['bad'] }} (Thập nhị trực -
                                                            {{ $getThongTinTruc['title'] }})
                                                        </li>
                                                    @endif
                                                </ul>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="tab-pane fade " id="pills-profile-nobd" role="tabpanel"
                                    aria-labelledby="pills-profile-tab-nobd">
                                    <div class="border border-top-0 p-2">
                                        <h4> Tổng quan ngay {{ $dd }}/{{ $mm }}/{{ $yy }}
                                        </h4>
                                        <ul>
                                            <li> Ngày dương lịch:
                                                {{ $dd }}/{{ $mm }}/{{ $yy }}
                                            </li>
                                            <li> Ngày âm lịch:
                                                {{ $al[0] }}/{{ $al[1] }}/{{ $al[2] }}
                                            </li>
                                            <li>Nạp âm ngũ hành: {{ $getThongTinNgay['nap_am']['napAm'] }}</li>
                                            <li>Tuổi xung: {{ $getThongTinNgay['tuoi_xung'] }}</li>
                                            <li>Giờ hoàng đạo: {{ $getThongTinNgay['gio_hoang_dao'] }}</li>
                                            <li>Giờ hắc đạo: {{ $getThongTinNgay['gio_hac_dao'] }}</li>
                                        </ul>
                                        <h5>Đánh giá ngày {{ round($getDaySummaryInfo['score']['percentage']) }} / 100 Điểm
                                            - Ngày
                                            {{ $getDaySummaryInfo['description'] }}
                                        </h5>


                                        <div class="vncal-header">
                                            <div class="vncal-header-titles">
                                                <div class="h6 text-dark">PHÂN TÍCH NGŨ HÀNH, SAO, TRỰC, LỤC DIỆU</div>
                                            </div>
                                        </div>
                                        <table class="table table-bordered table-actent">
                                            <tbody>
                                                <tr>
                                                    <td><b> Vận khí và tuổi hợp/xung trong ngày</b></td>
                                                    <td>
                                                        {{ $noiKhiNgay }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><b>Nhị thập bát tú</b></td>
                                                    <td>
                                                        <p>- Ngày
                                                            {{ $al[0] }}-{{ $al[1] }}-{{ $al[2] }}
                                                            Âm lịch có
                                                            xuất
                                                            hiện sao:
                                                            <b>{{ $nhiThapBatTu['name'] }}({{ $nhiThapBatTu['fullName'] }})</b>
                                                        </p>
                                                        <p>- Đây là sao <b>{{ $nhiThapBatTu['nature'] }} </b>-
                                                            {{ $nhiThapBatTu['description'] }}</p>
                                                        @if (!empty($nhiThapBatTu['guidance']['good']))
                                                            <p>- <b class="text-success">Việc nên làm</b> :
                                                                {{ $nhiThapBatTu['guidance']['good'] }}</p>
                                                        @endif
                                                        @if (!empty($nhiThapBatTu['guidance']['bad']))
                                                            <p>
                                                                - <b class="text-danger">Việc không nên làm</b> :
                                                                {{ $nhiThapBatTu['guidance']['bad'] }}
                                                            </p>
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><b>Thập nhị trực (12 trực)</b></td>
                                                    <td>
                                                        <p><b>Trực ngày: </b>Trực <b>{{ $getThongTinTruc['title'] }}</b>
                                                        </p>
                                                        <p>- Đây là trực {{ $getThongTinTruc['description']['rating'] }} -
                                                            {{ $getThongTinTruc['description']['description'] }}</p>
                                                        @if (!empty($getThongTinTruc['description']['good']))
                                                            <p>- <b class="text-success">Việc nên làm</b> :
                                                                {{ $getThongTinTruc['description']['good'] }}</p>
                                                        @endif
                                                        @if (!empty($getThongTinTruc['description']['bad']))
                                                            <p>
                                                                - <b class="text-danger">Việc không nên làm</b> :
                                                                {{ $getThongTinTruc['description']['bad'] }}
                                                            </p>
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><b>Ngọc hạp thông thư</b></td>
                                                    <td>
                                                        <div class="h6 text-decoration-underline">Sao tốt:</div>
                                                        @if (!empty($getSaoTotXauInfo['sao_tot']))
                                                            @foreach ($getSaoTotXauInfo['sao_tot'] as $tenSao => $yNghia)
                                                                <p>
                                                                    - <strong>{{ $tenSao }}:</strong>
                                                                    {{ $yNghia }}
                                                                </p>
                                                            @endforeach
                                                        @else
                                                            Không có sao tốt trong ngày này
                                                        @endif
                                                        <div class="h6 text-decoration-underline">Sao xấu:</div>
                                                        @if (!empty($getSaoTotXauInfo['sao_xau']))
                                                            @foreach ($getSaoTotXauInfo['sao_xau'] as $tenSao => $yNghia)
                                                                <p>
                                                                    - <strong>{{ $tenSao }}:</strong>
                                                                    {{ $yNghia }}
                                                                </p>
                                                            @endforeach
                                                        @else
                                                            Không có sao xấu trong ngày này
                                                        @endif

                                                        <p class="pt-2">{{ $getSaoTotXauInfo['ket_luan'] }}</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><b> Khổng minh lục diệu</b></td>
                                                    <td>
                                                        <p>Ngày này là ngày <b>{{ $khongMinhLucDieu['name'] }}</b>
                                                            ({{ $khongMinhLucDieu['rating'] }})</p>
                                                        <p>-> {{ $khongMinhLucDieu['description'] }}</p>
                                                        <p>{!! $khongMinhLucDieu['poem'] !!}</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><b> Bành Tổ Bách Kỵ</b></td>
                                                    <td>
                                                        Ngày <b>{{ $canChi }}</b>
                                                        <ul>
                                                            <li><b>{{ $chiNgay[0] }}</b> {{ $banhToCan }}</li>
                                                            <li><b>{{ $chiNgay[1] }}</b> {{ $banhToChi }}</li>
                                                        </ul>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <b>Ngày xuất hành</b>
                                                    </td>
                                                    <td>
                                                        <b>{{ $getThongTinXuatHanhVaLyThuanPhong['xuat_hanh_info']['title'] }}
                                                            ({{ $getThongTinXuatHanhVaLyThuanPhong['xuat_hanh_info']['rating'] }})</b>:
                                                        {{ $getThongTinXuatHanhVaLyThuanPhong['xuat_hanh_info']['description'] }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><b>Hướng xuất hành</b></td>
                                                    <td>
                                                        <h5>Hướng xuất hành tốt:</h5>
                                                        <p> Đón Hỷ thần:
                                                            {{ $getThongTinXuatHanhVaLyThuanPhong['huong_xuat_hanh']['hyThan']['direction'] }}
                                                        </p>
                                                        <p> Đón Tài thần:
                                                            {{ $getThongTinXuatHanhVaLyThuanPhong['huong_xuat_hanh']['taiThan']['direction'] }}
                                                        </p>
                                                        @if ($getThongTinXuatHanhVaLyThuanPhong['huong_xuat_hanh']['hacThan']['direction'] != 'Hạc Thần bận việc trên trời')
                                                            <p> Hắc thần:
                                                                {{ $getThongTinXuatHanhVaLyThuanPhong['huong_xuat_hanh']['hacThan']['direction'] }}
                                                            </p>
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><b>Giờ xuất hành Lý Thuần Phong</b></td>
                                                    <td>
                                                        <h5>Giờ tốt:</h5>
                                                        @foreach ($getThongTinXuatHanhVaLyThuanPhong['ly_thuan_phong']['good'] as $name => $items)
                                                            @foreach ($items as $item)
                                                                <p> - <b>{{ $item['name'] }} ({{ $item['rating'] }})</b>:
                                                                    {{ $item['timeRange'][0] }} ({{ $item['chi'][0] }})
                                                                    và
                                                                    {{ $item['timeRange'][1] }} ({{ $item['chi'][1] }})
                                                                    ->
                                                                    {{ $item['description'] }}
                                                                </p>
                                                            @endforeach
                                                        @endforeach
                                                        <h5>Giờ Xấu:</h5>
                                                        @foreach ($getThongTinXuatHanhVaLyThuanPhong['ly_thuan_phong']['bad'] as $name => $items)
                                                            @foreach ($items as $item)
                                                                <p> - <b>{{ $item['name'] }}
                                                                        ({{ $item['rating'] }})
                                                                    </b>:
                                                                    {{ $item['timeRange'][0] }} ({{ $item['chi'][0] }})
                                                                    và
                                                                    {{ $item['timeRange'][1] }} ({{ $item['chi'][1] }})
                                                                    ->
                                                                    {{ $item['description'] }}
                                                                </p>
                                                            @endforeach
                                                        @endforeach
                                                        {{ $getThongTinXuatHanhVaLyThuanPhong['ly_thuan_phong_description'] }}
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>





                                        <h5>KẾT LUẬN CHUNG</h5>
                                        <p>{{ $getDaySummaryInfo['intro_paragraph'] }}</p>
                                        <b>
                                            ☼ Các yếu tố tốt xuất hiện trong ngày
                                        </b>
                                        <p>
                                            @php
                                                $parts = [];

                                                if ($nhiThapBatTu['nature'] == 'Tốt') {
                                                    $parts[] = "Sao {$nhiThapBatTu['name']} (Nhị thập bát tú)";
                                                }

                                                if ($getThongTinTruc['description']['rating'] == 'Tốt') {
                                                    $parts[] = "Trực {$getThongTinTruc['title']} (Thập nhị trực)";
                                                }

                                                $saoTotList = array_keys($getSaoTotXauInfo['sao_tot'] ?? []);
                                                if (count($saoTotList)) {
                                                    $parts[] = 'Các sao tốt: ' . implode(', ', $saoTotList);
                                                }
                                            @endphp

                                            {{ implode(', ', $parts) }}
                                        </p>

                                        <b>☼ Các yếu tố xấu xuất hiện trong ngày</b>
                                        <p>
                                            @if ($nhiThapBatTu['nature'] == 'Xấu')
                                                Sao {{ $nhiThapBatTu['name'] }} (Nhị thập bát tú),
                                            @endif

                                            @if ($getThongTinTruc['description']['rating'] == 'Xấu')
                                                Trực {{ $getThongTinTruc['title'] }} (Thập nhị trực),
                                            @endif
                                            Sao: @foreach ($getSaoTotXauInfo['sao_xau'] as $tenSao => $yNghia)
                                                {{ $loop->first ? '' : ', ' }}{{ $tenSao }}
                                            @endforeach
                                        </p>
                                        <div>
                                            <b class="text-success">
                                                - Việc nên làm
                                            </b>
                                            <div>
                                                <ul>
                                                    @if (!empty($nhiThapBatTu['guidance']['good']))
                                                        <li>{{ $nhiThapBatTu['guidance']['good'] }} (Nhị thập bát tú -
                                                            {{ $nhiThapBatTu['name'] }})</li>
                                                    @endif
                                                    @if (!empty($getThongTinTruc['description']['good']))
                                                        <li>
                                                            {{ $getThongTinTruc['description']['good'] }} (Thập nhị trực -
                                                            {{ $getThongTinTruc['title'] }})
                                                        </li>
                                                    @endif
                                                </ul>
                                            </div>
                                            <b class="text-danger">
                                                - Việc không nên làm
                                            </b>
                                            <div>
                                                <ul>
                                                    @if (!empty($nhiThapBatTu['guidance']['bad']))
                                                        <li>{{ $nhiThapBatTu['guidance']['bad'] }} (Nhị thập bát tú - sao
                                                            {{ $nhiThapBatTu['name'] }})</li>
                                                    @endif
                                                    @if (!empty($getThongTinTruc['description']['bad']))
                                                        <li>
                                                            {{ $getThongTinTruc['description']['bad'] }} (Thập nhị trực -
                                                            {{ $getThongTinTruc['title'] }})
                                                        </li>
                                                    @endif
                                                </ul>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>



            </div>
        </div>
        <div class="col-lg-4">
            @include('assinbar')
        </div>
    </div>

 


    @push('scripts')
        <script>
            document.addEventListener("DOMContentLoaded", () => {
                // Lấy ngày tháng năm hiện tại từ Blade
                const currentYear = {{ $yy }};
                const currentMonth = {{ $mm }}; // Tháng từ PHP (1-12)
                const currentDay = {{ $dd }};

                // Tạo đối tượng Date trong JavaScript
                // Lưu ý: Tháng trong JS là 0-11, nên phải trừ đi 1
                const currentDate = new Date(currentYear, currentMonth - 1, currentDay);

                // Lấy các element nút bấm
                const prevBtn = document.getElementById('prev-day-btn');
                const nextBtn = document.getElementById('next-day-btn');

                // --- Xử lý nút "Ngày trước" ---
                // Chỉ thực hiện nếu nút tồn tại
                if (prevBtn) {
                    // Tạo một bản sao của ngày hiện tại để tính toán
                    const prevDate = new Date(currentDate);
                    // Đặt ngày thành ngày hôm trước, JS sẽ tự xử lý việc chuyển tháng/năm
                    prevDate.setDate(currentDate.getDate() - 1);

                    // Lấy các thành phần của ngày mới (không thêm số 0)
                    const prevYear = prevDate.getFullYear();
                    const prevMonth = prevDate.getMonth() + 1; // +1 để quay lại định dạng 1-12
                    const prevDay = prevDate.getDate();

                    // Gán URL mới cho nút
                    prevBtn.href = `/am-lich/nam/${prevYear}/thang/${prevMonth}/ngay/${prevDay}`;
                }

                // --- Xử lý nút "Ngày sau" ---
                // Chỉ thực hiện nếu nút tồn tại
                if (nextBtn) {
                    // Tạo một bản sao của ngày hiện tại để tính toán
                    const nextDate = new Date(currentDate);
                    // Đặt ngày thành ngày hôm sau
                    nextDate.setDate(currentDate.getDate() + 1);

                    // Lấy các thành phần của ngày mới (không thêm số 0)
                    const nextYear = nextDate.getFullYear();
                    const nextMonth = nextDate.getMonth() + 1; // +1 để quay lại định dạng 1-12
                    const nextDay = nextDate.getDate();

                    // Gán URL mới cho nút
                    nextBtn.href = `/am-lich/nam/${nextYear}/thang/${nextMonth}/ngay/${nextDay}`;
                }
            });
        </script>
    @endpush

@endsection
