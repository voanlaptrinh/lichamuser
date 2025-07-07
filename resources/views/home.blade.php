@extends('welcome')
@section('content')

    <div class="row">
        <div class="col-lg-8">
            <div class=" mt-3">
                <div class="row g-4">

                    <div class="col-lg-7 col-md-7">


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
                                                    <a title="Xem ngày 07/07/2025" class="rows-ngay">{{ $dd }}</a>
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
                    <div class="col-lg-5 col-md-5">
                        <div class="box-item-li-tot-xau">
                            <div class="widget-title2">
                                <div class="widget-title-cat2">Xem ngày tốt xấu phổ biến</div>
                            </div>

                            <ul>

                                <li class="nav-item"><a href="{{ route('astrology.form') }}"
                                        class="nav-link item-tot-xau-li">1. Xem tuổi
                                        cưới
                                        hỏi</a>
                                </li>
                                <li class="nav-item"><a href="{{ route('buy-house.form') }}"
                                        class="nav-link item-tot-xau-li">2. Xem ngày
                                        mua
                                        nhà</a>
                                </li>
                                <li class="nav-item"><a href="{{ route('breaking.form') }}"
                                        class="nav-link item-tot-xau-li">3. Xem ngày
                                        động
                                        thổ</a>
                                </li>

                                <li class="nav-item"><a href="{{ route('xuat-hanh.form') }}"
                                        class="nav-link item-tot-xau-li">4. Xem ngày
                                        xuất
                                        hành</a>
                                </li>
                                <li class="nav-item"><a href="{{ route('khai-truong.form') }}"
                                        class="nav-link item-tot-xau-li">5. Xem
                                        ngày
                                        khai
                                        trương</a>
                                </li>

                                <li class="nav-item"><a href="{{ route('ban-tho.form') }}"
                                        class="nav-link item-tot-xau-li">6. Xem ngày
                                        Đổi
                                        ban
                                        thờ</a>
                                </li>

                                <li class="nav-item"><a href="{{ route('tran-trach.form') }}"
                                        class="nav-link item-tot-xau-li">7. Xem Ngày
                                        yểm
                                        trấn -
                                        trấn
                                        trạch</a> </li>


                                <li class="nav-item"><a href="{{ route('huong-ban-tho.form') }}"
                                        class="nav-link item-tot-xau-li">8. Xem
                                        Hướng
                                        ban
                                        thờ
                                    </a> </li>
                                <li class="nav-item"><a href="{{ route('huong-nha.form') }}"
                                        class="nav-link item-tot-xau-li">9. Xem Hướng
                                        nhà
                                        hợp
                                        tuổi</a> </li>
                                <li class="nav-item"><a href="{{ route('huong-bep.form') }}"
                                        class="nav-link item-tot-xau-li">10. Xem Hướng
                                        bếp
                                        hợp
                                        tuổi</a> </li>

                                <li class="nav-item"><a href="{{ route('huong-ban-lam-viec.form') }}"
                                        class="nav-link item-tot-xau-li">11. Xem
                                        Hướng
                                        Bàn
                                        làm việc</a> </li>
                                <li class="nav-item"><a href="{{ route('xem-ngay-cua-con.index') }}"
                                        class="nav-link item-tot-xau-li">12. Xem
                                        giờ sinh của con</a> </li>
                            </ul>

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
                                                    <td><b>1. Vận khí và tuổi hợp/xung trong ngày</b></td>
                                                    <td>
                                                        {{ $noiKhiNgay }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><b>2. Nhị thập bát tú</b></td>
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
                                                    <td><b>3. Thập nhị trực (12 trực)</b></td>
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
                                                    <td><b>4. Ngọc hạp thông thư</b></td>
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
                                                    <td><b>5. Khổng minh lục diệu</b></td>
                                                    <td>
                                                        <p>Ngày này là ngày <b>{{ $khongMinhLucDieu['name'] }}</b>
                                                            ({{ $khongMinhLucDieu['rating'] }})</p>
                                                        <p>-> {{ $khongMinhLucDieu['description'] }}</p>
                                                        <p>{!! $khongMinhLucDieu['poem'] !!}</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><b>6. Bành Tổ Bách Kỵ</b></td>
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

                <div>
                    <div class="h6">
                        <b> Giới thiệu nguồn gốc lịch Việt Nam:</b>
                    </div>
                    <p style="line-height: 30px;">- Lịch Việt Nam là kết tinh giữa tri thức thiên văn phương Đông và kinh nghiệm sống của người Việt qua
                        hàng nghìn năm. Từ xa xưa, cha ông ta đã biết quan sát mặt trời, mặt trăng và các hiện tượng thiên
                        nhiên để xác định thời gian, mùa vụ, từ đó hình thành hệ thống canh tác, lễ nghi và tín ngưỡng phù
                        hợp với chu kỳ tự nhiên.
                        <br>
                       - Lịch truyền thống của Việt Nam là <b>lịch âm dương</b> (âm dương lịch), kết hợp giữa chu kỳ mặt
                        trăng (âm lịch) và mặt trời (dương lịch). Mỗi tháng âm lịch bắt đầu vào ngày sóc (mùng 1), khi trăng
                        non xuất hiện, nhưng vẫn được điều chỉnh theo vị trí mặt trời để đảm bảo sự ăn khớp với bốn mùa
                        trong năm. Chính vì thế, lịch âm dương vừa phản ánh đời sống nông nghiệp, vừa giữ vai trò quan trọng
                        trong phong tục, tín ngưỡng như chọn ngày cưới hỏi, xây nhà, khai trương...
                        <br>
                       - Trong lịch sử, Việt Nam từng sử dụng các lịch pháp khác nhau như: <b>Hiệp Kỷ lịch, Thái Dương lịch,
                            Hiệp Thống lịch</b>, chịu ảnh hưởng từ các triều đại Trung Hoa nhưng có sự điều chỉnh phù hợp
                        với khí hậu và tập quán bản địa. Đến thời nhà Nguyễn, lịch pháp được chuẩn hóa và ban hành thống
                        nhất trên toàn quốc.
                        <br>
                       - Ngày nay, dù dương lịch được dùng phổ biến trong đời sống hiện đại, nhưng lịch truyền thống (âm
                        lịch) vẫn giữ vai trò quan trọng trong văn hóa người Việt. Các dịp Tết Nguyên Đán, rằm, mùng một,
                        hay việc xem ngày lành tháng tốt… đều gắn liền với âm lịch và thể hiện sự kết nối giữa con người với
                        thiên nhiên và tổ tiên.
                    </p>
                </div>

            </div>
        </div>
        <div class="col-lg-4">
            <div class=" d-none d-lg-block">
                <div class="widget-title">
                    <h3 class="widget-title-cat">Xem lịch âm dương</h3>
                </div>
                <div class="col-md-12 mb-2">
                    <form method="POST" action="{{ route('doi-lich-home') }}" id="convertForm">
                        @csrf

                        <div class="row g-2 align-items-center">
                            <!-- Cột nhập ngày -->
                            <div class="col-md-8">
                                <input type="text"
                                    class="form-control datehomecdate @error('cdate') is-invalid @enderror" id="cdate"
                                    name="cdate" placeholder="Chọn ngày..." value="{{ old('cdate', $cdate ?? '') }}">

                                @error('cdate')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Cột nút bấm -->
                            <div class="col-md-4">
                                <button class="btn btn-primary w-100" type="submit">Xem ngày</button>
                            </div>
                        </div>
                    </form>

                </div>
                <table class="calendar-table">
                    <thead>
                        <tr>
                            <th>Th 2</th>
                            <th>Th 3</th>
                            <th>Th 4</th>
                            <th>Th 5</th>
                            <th>Th 6</th>
                            <th>Th 7</th>
                            <th>CN</th>

                        </tr>
                    </thead>
                    <tbody>
                        {!! $table_html !!}
                    </tbody>
                </table>
            </div>


            <div class="row pt-3 g-3 d-flex justify-content-center">
                @php
                    function renderStars($rating)
                    {
                        $stars = '';
                        for ($i = 1; $i <= 5; $i++) {
                            $stars .=
                                $i <= $rating
                                    ? '<i class="bi bi-star-fill text-warning"></i>'
                                    : '<i class="bi bi-star text-warning"></i>';
                        }
                        return $stars;
                    }
                @endphp
                <div class="widget-title">
                    <h3 class="widget-title-cat">Giờ Hoàng Đạo</h3>
                </div>
                @foreach ($getDetailedGioHoangDao as $itemgio)
                    <div class="col-lg-6 col-xl-6 col-sm-6 col-6">
                        <div class="card">
                            <div class="card-body p-3">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="numbers">
                                            <div class="d-flex justify-content-between">
                                                <p class="text-sm mb-0 text-uppercase font-weight-bold">
                                                    {{ $itemgio['name'] }}
                                                </p>
                                                <div class="font-weight-bolder">
                                                    <img src="{{ asset('icons/' . $itemgio['zodiacIcon']) }}"
                                                        alt="" width="45" height="">
                                                </div>
                                            </div>
                                            <div
                                                class="icon icon-shape bg-gradient-primary shadow-primary text-center rounded-circle">
                                                {!! renderStars($itemgio['rating']) !!}
                                            </div>
                                            <p class="mb-0 text-center">

                                                {!! $itemgio['canChiMenh'] !!}
                                            </p>
                                        </div>
                                    </div>
                                    {{-- <div class="col-4 text-end">
                                            <div
                                                class="icon icon-shape bg-gradient-primary shadow-primary text-center rounded-circle">
                                                {!! renderStars($itemgio['rating']) !!}
                                            </div>
                                        </div> --}}
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>
        </div>
    </div>

    @push('stylehome')
        <style>
            .can_chi_text {
                display: none;
            }

            .duong {
                font-size: 14px;
            }

            .am {

                font-size: 12px;
            }

            .nhuan-khong {
                display: none
            }

            .calendar-table td {
                padding: 18px
            }

            .calendar-table th {

                font-size: 13px;
                width: 100%;
                background: #f9ebeb;
                color: #272626;
            }

            .calendar-table td:hover {
                background: #f9ebeb;
            }
        </style>
    @endpush



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
