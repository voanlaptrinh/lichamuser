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
    Xem ngày tốt xấu hôm nay {{ $dd }}/{{ $mm }}/{{ $yy }} - Lịch âm {{ $al[0] }}/{{ $al[1] }}/{{ $al[2] }}, Lịch Vạn Niên {{ $yy }}
</h1>
                </div>
                <hr>
                <form method="POST" action="{{ route('doi-lich') }}" id="convertForm" class="pb-3">
                        @csrf
                        <input type="hidden" name="cdate" id="cdate" value="{{ old('duong_date', $cdate) }}">

                        <div class="row g-2">
                            <!-- Ngày dương -->
                            <div class="col-lg-5">
                                <div class="form-group">
                                    <label for="duong_date"  class="form-label">Chọn Ngày Dương</label>
                                    <input type="date" id="duong_date" name="duong_date"
                                        class="form-control custom-date-input"
                                        value="{{ old('duong_date', $cdate ?? \Carbon\Carbon::now()->format('Y-m-d')) }}">
                                </div>
                            </div>

                            <!-- Ngày âm -->
                            <div class="col-lg-5">
                                <div class="form-group">
                                    <label for="am_date"  class="form-label">Hoặc chọn Ngày Âm</label>
                                    <input type="date" id="am_date" name="am_date"
                                        class="form-control custom-date-input"
                                        value="{{ old('am_date', $amToday ?? '') }}">
                                </div>
                            </div>

                            <!-- Nút submit -->
                            <div class="col-lg-2 d-flex align-items-end">
                                <button type="submit" class="btn btn-outline-danger w-100 ">Chuyển đổi</button>
                            </div>
                        </div>
                    </form>

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
               
                <div>
                    <div class="col-lg-12">

                        <div class="card-body p-0">
                            <ul class="nav nav-pills nav-secondary nav-pills-no-bd row g-3" role="tablist"
                                style="border: none !important">
                                <li class="nav-item  pa-right w-50" role="presentation">
                                    <a class="nav-link btn active  pt-3 pb-3 text-center text-black fw-bold"
                                        id="pills-home-tab-nobd" data-bs-toggle="pill" href="#pills-home-nobd"
                                        role="tab" aria-controls="pills-home-nobd" aria-selected="true">Tóm tắt </a>
                                </li>
                                <li class="nav-item tba-ks pa-left  w-50" role="presentation">
                                    <a class="nav-link  btn pt-3 pb-3 text-center text-black fw-bold"
                                        id="pills-profile-tab-nobd" data-bs-toggle="pill" href="#pills-profile-nobd"
                                        role="tab" aria-controls="pills-profile-nobd" aria-selected="false">Chi
                                        tiết</a>
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

                                        <h3>PHÂN TÍCH NGŨ HÀNH, SAO, TRỰC, LỤC DIỆU</h3>
                                        <h4>1. Xem Can Chi - Khí vận & tuổi hợp/Xung trong ngày</h4>
                                        <div>
                                            Nội khí ngày (Can Chi ngày): <br>
                                            - {{ $noiKhiNgay }}
                                        </div>
                                        <h4>2. Nhị thập bát tú</h4>
                                        <p>Ngày {{ $al[0] }}-{{ $al[1] }}-{{ $al[2] }} Âm lịch có
                                            xuất
                                            hiện sao: <b>{{ $nhiThapBatTu['name'] }}({{ $nhiThapBatTu['fullName'] }})</b>
                                        </p>
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
                                            Đây là ngày
                                            <b>{{ $getThongTinXuatHanhVaLyThuanPhong['xuat_hanh_info']['title'] }}
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



            </div>
        </div>
        <div class="col-lg-4">
            <div class="widget bg-wg2" style="background: #f1f1f1;">
                <div class="widget-title fixtitlewidget">
                    <h2 class="widget-title-cat fixtitlewidget">Xem ngày tốt xấu cho việc</h2>
                </div>
                <div class="widget-container">
                    <div class="accordion-menu">
                        <div class="accordion_item1">
                            <h3 class="accordion_title font14"><a href="{{ route('astrology.form') }}"
                                    class="nav-link">Xem tuổi cưới hỏi</a></h3>
                        </div>
                        <div class="accordion_item1">
                            <h3 class="accordion_title font14"><a href="{{ route('buy-house.form') }}"
                                    class="nav-link">Xem ngày mua nhà</a></h3>
                        </div>
                        <div class="accordion_item1">
                            <h3 class="accordion_title font14"><a href="{{ route('breaking.form') }}"
                                    class="nav-link">Xem ngày động thổ</a></h3>
                        </div>
                        <div class="accordion_item1">
                            <h3 class="accordion_title font14"><a href="{{ route('nhap-trach.form') }}"
                                    class="nav-link">Xem ngày nhập
                                    trạch</a></h3>
                        </div>
                        <div class="accordion_item1">
                            <h3 class="accordion_title font14"><a href="{{ route('xuat-hanh.form') }}"
                                    class="nav-link">Xem ngày xuất
                                    hành</a></h3>
                        </div>
                        <div class="accordion_item1">
                            <h3 class="accordion_title font14"><a href="{{ route('khai-truong.form') }}"
                                    class="nav-link">Xem ngày khai
                                    trương</a></h3>
                        </div>
                        <div class="accordion_item1">
                            <h3 class="accordion_title font14"><a href="{{ route('ky-hop-dong.form') }}"
                                    class="nav-link">Xem ngày hý hợp
                                    đồng</a></h3>
                        </div>
                        <div class="accordion_item1">
                            <h3 class="accordion_title font14"><a href="{{ route('cai-tang.form') }}"
                                    class="nav-link">Xem ngày cải táng</a></h3>
                        </div>
                        <div class="accordion_item1">
                            <h3 class="accordion_title font14"><a href="{{ route('ban-tho.form') }}"
                                    class="nav-link">Xem ngày Đổi ban
                                    thờ</a></h3>
                        </div>
                        <div class="accordion_item1">
                            <h3 class="accordion_title font14"><a href="{{ route('lap-ban-tho.form') }}"
                                    class="nav-link">Xem ngày Lập ban
                                    thờ</a></h3>
                        </div>
                        <div class="accordion_item1">
                            <h3 class="accordion_title font14"><a href="{{ route('giai-han.form') }}"
                                    class="nav-link">Xem Ngày Cúng sao -
                                    giải
                                    hạn</a></h3>
                        </div>
                        <div class="accordion_item1">
                            <h3 class="accordion_title font14"><a href="{{ route('tran-trach.form') }}"
                                    class="nav-link">Xem Ngày yểm trấn -
                                    trấn
                                    trạch</a></h3>
                        </div>
                        <div class="accordion_item1">
                            <h3 class="accordion_title font14"><a href="{{ route('phong-sinh.form') }}"
                                    class="nav-link">Xem Ngày Cầu an -
                                    làm
                                    phúc - phóng sinh</a></h3>
                        </div>
                        <div class="accordion_item1">
                            <h3 class="accordion_title font14"><a href="{{ route('mua-xe.form') }}"
                                    class="nav-link">Xem ngày mua xe - nhận
                                    xe
                                    mới</a></h3>
                        </div>
                        <div class="accordion_item1">
                            <h3 class="accordion_title font14"><a href="{{ route('du-lich.form') }}"
                                    class="nav-link">Xem ngày xuất hành - du
                                    lịch - công tác</a></h3>
                        </div>
                        <div class="accordion_item1">
                            <h3 class="accordion_title font14"><a href="{{ route('thi-cu.form') }}"
                                    class="nav-link">Xem ngày thi cử phỏng
                                    vấn</a></h3>
                        </div>
                        <div class="accordion_item1">
                            <h3 class="accordion_title font14"><a href="{{ route('cong-viec-moi.form') }}"
                                    class="nav-link">Xem Ngày Nhận
                                    công
                                    việc mới</a></h3>
                        </div>
                        <div class="accordion_item1">
                            <h3 class="accordion_title font14"><a href="{{ route('giay-to.form') }}"
                                    class="nav-link">Xem ngày làm giấy tờ
                                    -
                                    cccd, hộ chiếu </a></h3>
                        </div>
                        <div class="accordion_item1">
                            <h3 class="accordion_title font14"><a href="{{ route('huong-ban-tho.form') }}"
                                    class="nav-link">Xem Hướng ban
                                    thờ
                                </a></h3>
                        </div>
                        <div class="accordion_item1">
                            <h3 class="accordion_title font14"><a href="{{ route('huong-nha.form') }}"
                                    class="nav-link">Xem Hướng nhà hợp
                                    tuổi</a></h3>
                        </div>
                        <div class="accordion_item1">
                            <h3 class="accordion_title font14"><a href="{{ route('huong-bep.form') }}"
                                    class="nav-link">Xem Hướng bếp hợp
                                    tuổi</a></h3>
                        </div>
                        <div class="accordion_item1">
                            <h3 class="accordion_title font14"><a href="{{ route('huong-phong-ngu.form') }}"
                                    class="nav-link">Xem Hướng
                                    phòng
                                    ngủ
                                    hợp tuổi</a></h3>
                        </div>
                        <div class="accordion_item1">
                            <h3 class="accordion_title font14"><a href="{{ route('huong-ban-lam-viec.form') }}"
                                    class="nav-link">Xem Hướng
                                    Bàn
                                    làm việc</a></h3>
                        </div>
                        <div class="accordion_item1">
                            <h3 class="accordion_title font14"><a href="{{ route('xem-ngay-cua-con.index') }}"
                                    class="nav-link">Xem giờ sinh của con</a></h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .widget-title {

            position: relative;
            margin-bottom: 0;

        }

        .accordion_title {
            float: left;
            font-weight: normal;
        }

        .accordion_item1 {
            padding-top: 6px;
            padding-bottom: 0px;
            position: relative;
            width: 100%;
            float: left;
            border-bottom: solid 0.5px #e0e0e0;
        }

        .breadcrumb1 {
            float: left;
            width: 100%;
            margin-bottom: 10px;
            border-bottom: solid 0.5px #e0e0e0;
            padding-bottom: 10px;
        }

        .breadcrumb1-item {
            display: inline;
            font-size: 13px;
            color: #6f6f6f;
        }

        .breadcrumb1-item+:before {
            padding: 0 5px;
            color: #6f6f6f;
            content: "/\00a0";
        }

        .font14 {
            font-size: 14px;
        }

        .bg-wg2 {
            background: red
        }
    </style>



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
