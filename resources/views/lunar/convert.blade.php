@extends('welcome')
@section('content')

    <div class=" mt-5">
        <div class="row g-5">


            <div class="col-lg-6">
                <h1>Chuyển Đổi Ngày Dương Sang Âm</h1>

                <!-- Form nhập ngày Dương -->


                <form method="POST" action="{{ url('/doi-lich') }}" id="convertForm">
                    @csrf
                    <div class="form-group">
                        <label for="duong_date">Chọn Ngày Dương</label>
                        <input type="date" id="duong_date" name="duong_date" class="form-control custom-date-input"
                            value="{{ old('duong_date', $cdate ?? \Carbon\Carbon::now()->format('Y-m-d')) }}">

                        <label for="am_date" class="mt-3">Hoặc chọn Ngày Âm</label>
                        <input type="date" id="am_date" name="am_date" class="form-control custom-date-input"
                            value="{{ old('am_date', $amToday ?? '') }}">
                    </div>

                    <input type="hidden" name="cdate" id="cdate" value="{{ old('duong_date', $cdate) }}">

                    <button type="submit" class="btn btn-primary mt-3">Chuyển đổi</button>
                </form>


                @if (isset($al))
                    <h3 class="mt-5">Kết quả chuyển đổi</h3>
                    <p><strong>Ngày Dương: </strong>{{ $dd }}/{{ $mm }}/{{ $yy }}</p>
                    <p><strong>Ngày Âm: </strong>{{ $al[0] }}/{{ $al[1] }}/{{ $al[2] }} (Tháng
                        {{ $al[3] == 1 ? 'Nhuận' : 'Thường' }})</p>
                    <p><strong>Ngày: </strong>{{ $getThongTinCanChiVaIcon['can_chi_ngay'] }} <strong>Tháng: </strong>
                        {{ $getThongTinCanChiVaIcon['can_chi_thang'] }}
                        <strong>Năm: </strong> {{ $getThongTinCanChiVaIcon['can_chi_nam'] }}
                    </p>
                    <p><strong>Ngày trong tuần: </strong>{{ $weekday }}</p>
                    <p>{!! $ngaySuatHanhHTML !!}</p>
                    <p><b>Giờ hoàng đạo</b> {{ $gioHd }}</p>
                @endif
            </div>
            {{-- Dán đoạn này để thay thế cho div class="col-lg-6" của bạn --}}
            <div class="col-lg-6">
                <div class="calendar-container">

                    {{-- SỬA ĐỔI BẮT ĐẦU TỪ ĐÂY --}}
                    <div class="header-calendar">

                        <a href="{{ route('lich.thang', ['nam' => $prevYear, 'thang' => $prevMonth]) }}" class="nav-arrow"
                            title="Tháng trước">
                            <i class="bi bi-chevron-left"></i>
                        </a>

                        {{-- HIỂN THỊ THÁNG/NĂM HIỆN TẠI --}}
                        <span class="header-calendar-title">
                            Tháng {{ $mm }} năm {{ $yy }}
                        </span>

                        {{-- NÚT THÁNG SAU --}}
                        <a href="{{ route('lich.thang', ['nam' => $nextYear, 'thang' => $nextMonth]) }}" class="nav-arrow"
                            title="Tháng sau">
                            <i class="bi bi-chevron-right"></i>
                        </a>

                    </div>
                    {{-- SỬA ĐỔI KẾT THÚC TẠI ĐÂY --}}

                    <div class="body-calendar">
                        <div class="p-2">
                            <div class="day-calendar">
                                <div class="d-flex align-items-center justify-content-between">
                                    <a href="#" id="prev-day-btn" class="nav-arrow" title="Ngày hôm trước"><i
                                            class="bi bi-chevron-left"></i></a>
                                    <div class="day-name">
                                        {{ $dd }}
                                    </div>
                                    <a href="#" id="next-day-btn" class="nav-arrow" title="Ngày hôm sau"><i
                                            class="bi bi-chevron-right"></i></a>
                                </div>

                            </div>
                            <div class="weekday-calendar">
                                <div class="weekday-name">{{ $weekday }}</div>

                                @foreach ($suKienHomNay as $suKien)
                                    <div class="text-center">
                                        <div class="su-kien-info">
                                            <strong>{{ $suKien['ten_su_kien'] ?? $suKien }}</strong>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between align-items-center am-lich-header">
                                <div class="am-lich-info">
                                    <div class="am-lich-item">
                                        <span class="icon">
                                            @if ($getThongTinCanChiVaIcon['icon_nam'])
                                                <img src="{{ $getThongTinCanChiVaIcon['icon_nam'] }}" alt="Chi Icon"
                                                    width="40" height="23">
                                            @else
                                                <p>Không tìm thấy icon.</p>
                                            @endif
                                        </span> Năm {{ $getThongTinCanChiVaIcon['can_chi_nam'] }}
                                    </div>
                                    <div class="am-lich-item">
                                        <span class="icon">
                                            @if ($getThongTinCanChiVaIcon['icon_thang'])
                                                <img src="{{ $getThongTinCanChiVaIcon['icon_thang'] }}" alt="Chi Icon"
                                                    width="40" height="23">
                                            @else
                                                <p>Không tìm thấy icon.</p>
                                            @endif
                                        </span>
                                        Tháng {{ $getThongTinCanChiVaIcon['can_chi_thang'] }}
                                    </div>
                                    <div class="am-lich-item">
                                        <span class="icon">
                                            @if ($getThongTinCanChiVaIcon['icon_ngay'])
                                                <img src="{{ $getThongTinCanChiVaIcon['icon_ngay'] }}" alt="Chi Icon"
                                                    width="40" height="23">
                                            @else
                                                <p>Không tìm thấy icon.</p>
                                            @endif
                                        </span> Ngày {{ $canChi }}
                                    </div>
                                </div>

                                <div class="am-lich-date">
                                    <span class="date-number">{{ $al[0] }}</span>
                                    <div class="date-label">
                                        Âm lịch<br>Tháng {{ $al[1] }} ({{ $al[4] }})
                                    </div>
                                </div>
                            </div>
                            <div class="am-lich-tietkhi">
                                <span>Tiết khí: {!! $tietkhi['icon'] !!} <b
                                        class="text-uppercase">{{ $tietkhi['tiet_khi'] }}</b></span>
                            </div>
                            <div class="pt-3">
                                <div class="gio-lich-info">
                                    <b>Giờ hoàng đạo</b> {{ $gioHd }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-5 mb-5 g-3 d-flex justify-content-center">
            @php
                function renderStars($rating)
                {
                    $stars = '';
                    for ($i = 1; $i <= 5; $i++) {
                        $stars .= $i <= $rating ? '★' : '☆';
                    }
                    return $stars;
                }
            @endphp
            @foreach ($getDetailedGioHoangDao as $itemgio)
                <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
                    <div class="card">
                        <div class="card-body p-3">
                            <div class="row">
                                <div class="col-8">
                                    <div class="numbers">
                                        <p class="text-sm mb-0 text-uppercase font-weight-bold">{{ $itemgio['name'] }}
                                        </p>
                                        <h5 class="font-weight-bolder">
                                            {!! $itemgio['zodiacIcon'] !!}
                                        </h5>
                                        <p class="mb-0 text-center">

                                            {!! $itemgio['canChiMenh'] !!}
                                        </p>
                                    </div>
                                </div>
                                <div class="col-4 text-end">
                                    <div
                                        class="icon icon-shape bg-gradient-primary shadow-primary text-center rounded-circle">
                                        {!! renderStars($itemgio['rating']) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

        </div>
        <div>
            <div class="col-lg-12">

                <div class="card-body p-0">
                    <ul class="nav nav-pills nav-secondary nav-pills-no-bd row g-3" role="tablist"
                        style="border: none !important">
                        <li class="nav-item  pa-right w-50" role="presentation">
                            <a class="nav-link btn active  pt-3 pb-3 text-center text-black fw-bold"
                                id="pills-home-tab-nobd" data-bs-toggle="pill" href="#pills-home-nobd" role="tab"
                                aria-controls="pills-home-nobd" aria-selected="true">Tóm tắt </a>
                        </li>
                        <li class="nav-item tba-ks pa-left  w-50" role="presentation">
                            <a class="nav-link  btn pt-3 pb-3 text-center text-black fw-bold" id="pills-profile-tab-nobd"
                                data-bs-toggle="pill" href="#pills-profile-nobd" role="tab"
                                aria-controls="pills-profile-nobd" aria-selected="false">Chi tiết</a>
                        </li>
                    </ul>

                    <div class="tab-content mb-3" id="pills-without-border-tabContent">
                        <div class="tab-pane fade show active" id="pills-home-nobd" role="tabpanel"
                            aria-labelledby="pills-home-tab-nobd">
                            <p>{{ $getDaySummaryInfo['intro_paragraph'] }}</p>
                            {{-- <h5>Đánh giá ngày {{ round($scoresDate['percentage']) }} Điểm - {{ $scoresDate['rating'] }}</h5> --}}
                            <h5>Đánh giá ngày {{ round($getDaySummaryInfo['score']['percentage']) }} Điểm -
                                {{ $getDaySummaryInfo['score']['rating'] }}</h5>
                            ☼ Các yếu tố tốt xuất hiện trong ngày
                            <p>
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
                            </p>
                            ☼ Các yếu tố xấu xuất hiện trong ngày
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
                                ♥ Việc nên làm
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
                                ♥ Việc không nên làm
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
                        <div class="tab-pane fade " id="pills-profile-nobd" role="tabpanel"
                            aria-labelledby="pills-profile-tab-nobd">
                            <div class="border border-top-0 p-2">
                                <h4> Tổng quan ngay {{ $dd }}/{{ $mm }}/{{ $yy }}</h4>
                                <ul>
                                    <li> Ngày dương lịch: {{ $dd }}/{{ $mm }}/{{ $yy }}
                                    </li>
                                    <li> Ngày âm lịch: {{ $al[0] }}/{{ $al[1] }}/{{ $al[2] }}
                                    </li>
                                    <li>Nạp âm ngũ hành: {{ $getThongTinNgay['nap_am']['napAm'] }}</li>
                                    <li>Tuổi xung: {{ $getThongTinNgay['tuoi_xung'] }}</li>
                                    <li>Giờ hoàng đạo: {{ $getThongTinNgay['gio_hoang_dao'] }}</li>
                                    <li>Giờ hắc đạo: {{ $getThongTinNgay['gio_hac_dao'] }}</li>
                                </ul>
                                <h5>Đánh giá ngày {{ round($getDaySummaryInfo['score']['percentage']) }} / 100 Điểm - Ngày
                                    {{ $getDaySummaryInfo['description'] }}
                                </h5>

                                <h3>PHÂN TÍCH NGŨ HÀNH, SAO, TRỰC, LỤC DIỆU</h3>
                                <h4>1. Xem Can Chi - Khí vận & tuổi hợp/Xung trong ngày</h4>
                                <div>
                                    Nội khí ngày (Can Chi ngày): <br>
                                    - {{ $noiKhiNgay }}
                                </div>
                                <h4>2. Nhị thập bát tú</h4>
                                <p>Ngày {{ $al[0] }}-{{ $al[1] }}-{{ $al[2] }} Âm lịch có xuất
                                    hiện sao: <b>{{ $nhiThapBatTu['name'] }}({{ $nhiThapBatTu['fullName'] }})</b></p>
                                <p>Đây là sao <b>{{ $nhiThapBatTu['nature'] }} </b>-
                                    {{ $nhiThapBatTu['description'] }}</p>
                                <li>
                                    Việc nên làm : {{ $nhiThapBatTu['guidance']['good'] }}
                                    @if (!empty($nhiThapBatTu['guidance']['bad']))
                                        Việc không nên làm : {{ $nhiThapBatTu['guidance']['bad'] }}
                                    @endif
                                </li>
                                <h4>3. Thập nhị trực (12 trực)</h4>
                                <p><b>Trực ngày: </b>Trực <b>{{ $getThongTinTruc['title'] }}</b></p>
                                <p>- Đây là trực {{ $getThongTinTruc['description']['rating'] }} -
                                    {{ $getThongTinTruc['description']['description'] }}</p>
                                <ul>
                                    <li>Việc nên làm: {{ $getThongTinTruc['description']['good'] }}</li>
                                    <li>Việc không nên làm: {{ $getThongTinTruc['description']['bad'] }}</li>
                                </ul>
                                <h4>4. Các sao tốt - xấu theo Ngọc Hạp Thông Thư</h4>
                                <div>
                                    <h6>- Sao tốt</h6>
                                    @if (!empty($getSaoTotXauInfo['sao_tot']))
                                        @foreach ($getSaoTotXauInfo['sao_tot'] as $tenSao => $yNghia)
                                            <li><strong>{{ $tenSao }}:</strong> {{ $yNghia }}</li>
                                        @endforeach
                                    @else
                                        Không có sao tốt trong ngày này
                                    @endif

                                    </ul>
                                    <h6>- Sao xấu</h6>
                                    <ul>
                                        @if (!empty($getSaoTotXauInfo['sao_xau']))
                                            @foreach ($getSaoTotXauInfo['sao_xau'] as $tenSao => $yNghia)
                                                <li><strong>{{ $tenSao }}:</strong> {{ $yNghia }}</li>
                                            @endforeach
                                        @else
                                            Không có sao xấu trong ngày này
                                        @endif

                                    </ul>
                                    <p>{{ $getSaoTotXauInfo['ket_luan'] }}</p>
                                </div>
                                <h4>5. Ngày theo Khổng minh lục diệu</h4>
                                <div>
                                    <p>Ngày này là ngày <b>{{ $khongMinhLucDieu['name'] }}</b>
                                        ({{ $khongMinhLucDieu['rating'] }})</p>
                                    <p>-> {{ $khongMinhLucDieu['description'] }}</p>
                                    <p>{!! $khongMinhLucDieu['poem'] !!}</p>
                                </div>
                                <h3>BÁCH KỴ VÀ CẢNH BÁO ĐẠI KỴ</h3>
                                <div>
                                    <h4>1.Giải thích ý nghĩa theo ngày Bành Tổ Bách Kỵ</h4>
                                    Ngày <b>{{ $canChi }}</b>
                                    <ul>
                                        <li><b>{{ $chiNgay[0] }}</b> {{ $banhToCan }}</li>
                                        <li><b>{{ $chiNgay[1] }}</b> {{ $banhToChi }}</li>
                                    </ul>
                                </div>

                                <h3>NGÀY, GIỜ HƯỚNG XUẤT HÀNH</h3>
                                <div>
                                    <h4>1. Ngày xuất hành</h4>
                                    Đây là ngày <b>{{ $getThongTinXuatHanhVaLyThuanPhong['xuat_hanh_info']['title'] }}
                                        ({{ $getThongTinXuatHanhVaLyThuanPhong['xuat_hanh_info']['rating'] }})</b>:
                                    {{ $getThongTinXuatHanhVaLyThuanPhong['xuat_hanh_info']['description'] }}
                                    <h4>2. Hướng xuất hành</h4>
                                    <h5>Hướng xuất hành tốt:</h5>
                                    <p> ĐÓn Hỷ thần:
                                        {{ $getThongTinXuatHanhVaLyThuanPhong['huong_xuat_hanh']['hyThan']['direction'] }}
                                    </p>
                                    <p> ĐÓn Tài thần:
                                        {{ $getThongTinXuatHanhVaLyThuanPhong['huong_xuat_hanh']['taiThan']['direction'] }}
                                    </p>
                                    @if ($getThongTinXuatHanhVaLyThuanPhong['huong_xuat_hanh']['hacThan']['direction'] != 'Hạc Thần bận việc trên trời')
                                        <p> Hắc thần:
                                            {{ $getThongTinXuatHanhVaLyThuanPhong['huong_xuat_hanh']['hacThan']['direction'] }}
                                        </p>
                                    @endif
                                    <h4>3.Giờ xuất hành Lý Thuần Phong</h4>
                                    <h5>Giờ tốt:</h5>
                                    @foreach ($getThongTinXuatHanhVaLyThuanPhong['ly_thuan_phong']['good'] as $name => $items)
                                        @foreach ($items as $item)
                                            <p> - {{ $item['name'] }} ({{ $item['rating'] }}):
                                                {{ $item['timeRange'][0] }} ({{ $item['chi'][0] }}) và
                                                {{ $item['timeRange'][1] }} ({{ $item['chi'][1] }}) ->
                                                {{ $item['description'] }}
                                            </p>
                                        @endforeach
                                    @endforeach
                                    <h5>Giờ Xấu:</h5>
                                    @foreach ($getThongTinXuatHanhVaLyThuanPhong['ly_thuan_phong']['bad'] as $name => $items)
                                        @foreach ($items as $item)
                                            <p> - {{ $item['name'] }} ({{ $item['rating'] }}):
                                                {{ $item['timeRange'][0] }} ({{ $item['chi'][0] }}) và
                                                {{ $item['timeRange'][1] }} ({{ $item['chi'][1] }}) ->
                                                {{ $item['description'] }}
                                            </p>
                                        @endforeach
                                    @endforeach
                                    {{ $getThongTinXuatHanhVaLyThuanPhong['ly_thuan_phong_description'] }}


                                </div>
                                <h5>KẾT LUẬN CHUNG</h5>
                                <p>{{ $getDaySummaryInfo['intro_paragraph'] }}</p>
                                ☼ Các yếu tố tốt xuất hiện trong ngày
                                <p>
                                    @if ($nhiThapBatTu['nature'] == 'Tốt')
                                        Sao {{ $nhiThapBatTu['name'] }} (Nhị thập bát tú),
                                    @endif
                                    @if ($getThongTinTruc['description']['rating'] == 'Tốt')
                                        Trực {{ $getThongTinTruc['title'] }} (Thập nhị trực),
                                    @endif
                                    @foreach ($getSaoTotXauInfo['sao_tot'] as $tenSao => $yNghia)
                                        @if ($getSaoTotXauInfo['sao_tot'])
                                            Sao: {{ $loop->first ? '' : ', ' }}{{ $tenSao }}
                                        @endif
                                    @endforeach
                                </p>
                                ☼ Các yếu tố xấu xuất hiện trong ngày
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
                                    ♥ Việc nên làm
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
                                    ♥ Việc không nên làm
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

        <table class="calendar-table">
            <thead>
                <tr>
                    <th><span class="title-lich-pc">Thứ hai</span> <span class="title-lich-mobie">Th 2</span></th>
                    <th><span class="title-lich-pc">Thứ ba</span> <span class="title-lich-mobie">Th 3</span></th>
                    <th><span class="title-lich-pc">Thứ tư</span> <span class="title-lich-mobie">Th 4</span></th>
                    <th><span class="title-lich-pc">Thứ năm</span> <span class="title-lich-mobie">Th 5</span></th>
                    <th><span class="title-lich-pc">Thứ sau</span> <span class="title-lich-mobie">Th 6</span></th>
                    <th><span class="title-lich-pc">Thứ bảy</span> <span class="title-lich-mobie">Th 7</span></th>
                    <th><span class="title-lich-pc">Chủ nhật</span> <span class="title-lich-mobie">CN</span></th>

                </tr>
            </thead>
            <tbody>
                {!! $table_html !!}
            </tbody>
        </table>

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
