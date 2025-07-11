@extends('welcome')

@section('content')
    <div class="row">
        <div class="col-lg-8">
            <div class=" mt-3 boder_fix">
                <div class="breadcrumb1">
                    <ul>
                        <li class="breadcrumb1-item"><a href="/">Trang chủ</a></li>
                        <li class="breadcrumb1-item">Xem ngày tốt xấu</li>
                        <li class="breadcrumb1-item">Xem ngày tốt TRẤN TRẠCH & YỂM TRẤN</li>
                    </ul>
                </div>
                <div class="page-head">
                    <h1 class="page-head-title h4 h3-sm h2-md h1-lg text-uppercase">
                        Xem Ngày Tốt TRẤN TRẠCH & YỂM TRẤN
                    </h1>
                    <p class="pb-2">Từ ngàn xưa, ông cha ta đã luôn coi trọng việc xây dựng và gìn giữ một ngôi nhà bình
                        an, thịnh vượng. "An cư lạc nghiệp" không chỉ là mong ước về một nơi ở ổn định, mà còn là khát vọng
                        về một không gian sống hài hòa, nơi con người được che chở và phát triển. Trong dòng chảy văn hóa
                        phương Đông, Trấn Trạch và Yểm Trấn chính là những pháp môn quan trọng để hiện thực hóa ước mong đó.
                    </p>
                </div>
                <div class="card">
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
                                                            <select class="form-select sort-select" id="">
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
                                                                        <td class="fw-bold">
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
                            <h4>TRẤN TRẠCH & YỂM TRẤN: BÍ QUYẾT AN GIA, HÓA GIẢI SÁT KHÍ, HƯNG VƯỢNG TÀI LỘC</h4>
                            <p>Từ ngàn xưa, ông cha ta đã luôn coi trọng việc xây dựng và gìn giữ một ngôi nhà bình an,
                                thịnh vượng. "An cư lạc nghiệp" không chỉ là mong ước về một nơi ở ổn định, mà còn là khát
                                vọng về một không gian sống hài hòa, nơi con người được che chở và phát triển. Trong dòng
                                chảy văn hóa phương Đông, Trấn Trạch và Yểm Trấn chính là những pháp môn quan trọng để hiện
                                thực hóa ước mong đó.
                                <br> Vậy Trấn Trạch - Yểm Trấn thực chất là gì? Khi nào chúng ta cần đến chúng? Và làm thế
                                nào để thực hiện đúng cách mà không gây phản tác dụng? Hãy cùng tìm hiểu chi tiết.
                            </p>
                            <div class="h5">1. Phân Biệt Rõ Ràng: Trấn Trạch và Yểm Trấn</div>
                            <p>Mặc dù thường được nhắc đến cùng nhau, Trấn Trạch và Yểm Trấn có mục đích và phương pháp khác
                                biệt.</p>
                            <div class="h5">
                                A. Trấn Trạch là gì?
                            </div>
                            <ul style="list-style: circle; margin-left: 2rem;">
                                <li><b>Định nghĩa:</b> "Trấn" là giữ cho ổn định, "Trạch" là nhà ở. Trấn Trạch là các phương
                                    pháp nhằm ổn định năng lượng (địa khí) của một ngôi nhà, giúp cân bằng âm dương, tăng
                                    cường sinh khí và ngăn chặn các luồng năng lượng xấu từ bên ngoài xâm nhập.
                                </li>
                                <li><b>Mục đích:</b>
                                    <ul style="list-style: circle; margin-left: 2rem;">
                                        <li><b>An Gia</b>: Giúp các thành viên trong gia đình cảm thấy yên ổn, khỏe mạnh,
                                            tinh thần thư thái.</li>
                                        <li><b>Tăng Vượng Khí</b>: Thu hút và tích tụ năng lượng tốt, giúp gia chủ làm ăn
                                            phát đạt, tài lộc hanh thông.</li>
                                        <li><b>Bảo Vệ</b>: Tạo ra một "lá chắn năng lượng" vô hình, bảo vệ ngôi nhà khỏi
                                            những tác động tiêu cực từ môi trường xung quanh.</li>
                                    </ul>
                                </li>
                                <li><b>Bản chất</b>: Trấn trạch mang tính chất <b>phòng thủ, bảo vệ và nuôi dưỡng.</b> Giống
                                    như việc chúng ta tăng cường hệ miễn dịch cho cơ thể để chống lại bệnh tật.</li>

                            </ul>
                            <div class="h5">
                                B. Yểm Trấn là gì?
                            </div>
                            <ul style="list-style: circle; margin-left: 2rem;">
                                <li><b>Định nghĩa:</b> "Yểm" là đè xuống, trấn áp, "Trấn" là áp chế. <b>Yểm Trấn</b> là
                                    những nghi
                                    lễ, phương pháp có tính can thiệp mạnh mẽ hơn, dùng để trấn áp, khống chế hoặc xua đuổi
                                    những nguồn năng lượng cực xấu, tà khí, âm khí nặng nề tại một khu đất hoặc ngôi nhà.
                                </li>
                                <li><b>Mục đích:</b>
                                    <ul style="list-style: circle; margin-left: 2rem;">
                                        <li><b>Trừ Tà:</b> Xua đuổi vong linh, âm khí, tà ma quấy nhiễu.</li>
                                        <li><b>Hóa Giải Sát Khí Nặng:</b> Vô hiệu hóa những luồng năng lượng cực đoan (ví dụ
                                            như mảnh đất từng là bãi tha ma, chiến trường, hoặc bị yểm bùa từ trước).
                                        </li>
                                        <li><b>Khóa Long Mạch Xấu:</b> Ngăn chặn những ảnh hưởng tiêu cực từ các long mạch
                                            bị tổn thương.</li>
                                    </ul>
                                </li>
                                <li><b>Bản chất:</b> Yểm trấn mang tính chất <b>tấn công, trấn áp và hóa giải triệt để</b>.
                                    Giống như việc bác sĩ phải dùng đến phẫu thuật hoặc thuốc đặc trị để loại bỏ một khối u
                                    ác tính.</li>

                            </ul>
                            <div class="h5">
                                2. Khi Nào Cần Thực Hiện Trấn Trạch - Yểm Trấn?
                            </div>
                            <p>Không phải ngôi nhà nào cũng cần thực hiện các nghi lễ này. Dưới đây là những trường hợp phổ
                                biến nên cân nhắc:</p>
                            <ul>
                                <li>1. <b>Khi Xây Dựng Nhà Mới (Lễ Động Thổ):</b> Thực hiện trấn trạch để xin phép Thổ Địa,
                                    Thần Linh cai quản khu đất, cầu mong quá trình xây dựng thuận lợi và đặt nền móng năng
                                    lượng tốt cho ngôi nhà tương lai.</li>
                                <li>2. <b>Khi Dọn Vào Nhà Mới (Lễ Nhập Trạch):</b> Trấn trạch giúp khai báo với các vị thần
                                    linh, xua đi những chướng khí còn sót lại từ quá trình thi công và chính thức thiết lập
                                    một trường năng lượng an lành cho gia chủ.
                                </li>
                                <li>3. <b>Đất Có Lịch Sử Xấu:</b> Mảnh đất từng là nghĩa địa, bãi rác, bệnh viện, nơi xảy ra
                                    án mạng hoặc chiến trận thường có âm khí rất nặng. Trường hợp này cần thực hiện cả trấn
                                    trạch và yểm trấn một cách cẩn trọng..</li>
                                <li>4. <b>Nhà Gặp Sát Khí:</b>
                                    <ul style="list-style: circle; margin-left: 2rem;">
                                        <li>Nhà có đường đâm thẳng vào cửa chính (thương sát).</li>
                                        <li>Nhà đối diện với góc nhọn của tòa nhà khác, cột điện, tháp nước (tiêm giác sát).
                                        </li>
                                        <li>Nhà gần nơi có nhiều âm khí như đền, chùa, miếu, nhà tang lễ.</li>
                                    </ul>

                                </li>
                                <li>5. <b>Gia Đạo Bất An, Làm Ăn Sa Sút:</b> Khi gia đình liên tục gặp chuyện không may, sức
                                    khỏe suy giảm, làm ăn thất bại không rõ nguyên nhân, có thể địa khí của ngôi nhà đã bị
                                    suy yếu hoặc tác động xấu.</li>
                                <li>6. <b>Cảm Giác Bất An Trong Nhà:</b> Các thành viên luôn cảm thấy lạnh lẽo, bất an, hay
                                    mơ thấy ác mộng, nghe thấy tiếng động lạ. Đây có thể là dấu hiệu của sự tồn tại của các
                                    năng lượng tiêu cực.</li>

                            </ul>
                            <div class="h5">
                                3. Các Vật Phẩm Trấn Trạch Phổ Biến và Ý Nghĩa
                            </div>
                            <p>Việc trấn trạch thường sử dụng các vật phẩm phong thủy (pháp khí) đã được khai quang, trì
                                chú.</p>
                            <ul tyle="list-style: circle; margin-left: 2rem;">
                                <li><b>Gương Bát Quái:</b>
                                    <ul style="list-style: circle; margin-left: 2rem;">
                                        <li><b>Công dụng:</b> Là pháp khí phổ biến nhất, dùng để phản xạ, hóa giải và phân
                                            tán sát khí.</li>
                                        <li><b>Phân loại:</b> Gương lồi (phân tán), gương lõm (tích tụ), gương phẳng (phản
                                            xạ)
                                        </li>
                                        <li><b>Vị trí:</b> Thường treo phía trên cửa chính, hướng ra ngoài. <b>Tuyệt đối
                                                không treo đối diện nhà người khác.</b></li>
                                    </ul>
                                </li>
                                <li><b>Hồ Lô:</b>
                                    <ul style="list-style: circle; margin-left: 2rem;">
                                        <li><b>Công dụng:</b> Hút và hóa giải bệnh tật, tà khí. Biểu tượng của sức khỏe và
                                            trường thọ.</li>
                                        <li><b>Vị trí:</b> Treo ở đầu giường, cửa sổ phòng người bệnh, hoặc ở các vị trí có
                                            sát khí.
                                        </li>
                                    </ul>
                                </li>
                                <li><b>Tượng Quan Công, Đạt Ma Sư Tổ, Hộ Pháp:</b>
                                    <ul style="list-style: circle; margin-left: 2rem;">
                                        <li><b>Công dụng:</b> Những vị thần có uy lực mạnh mẽ, giúp trấn áp tà ma, ngăn chặn
                                            kẻ tiểu nhân và bảo vệ gia chủ.</li>
                                        <li><b>Vị trí:</b> Đặt ở phòng khách, hướng ra cửa chính.
                                        </li>
                                    </ul>
                                </li>
                                <li><b>Tỳ Hưu, Sư Tử, Kỳ Lân:</b>
                                    <ul style="list-style: circle; margin-left: 2rem;">
                                        <li><b>Công dụng:</b> Là những linh vật canh giữ, hóa giải sát khí, chiêu tài lộc
                                            (đặc biệt là Tỳ Hưu) và mang lại điềm lành.</li>
                                        <li><b>Vị trí:</b> Thường đặt theo cặp ở hai bên cửa chính hoặc trên bàn làm việc.
                                        </li>
                                    </ul>
                                </li>
                                <li><b>Đá Thạch Anh và các loại đá phong thủy:</b>
                                    <ul style="list-style: circle; margin-left: 2rem;">
                                        <li><b>Công dụng:</b> Đá tự nhiên mang năng lượng dương mạnh mẽ, giúp khử từ trường
                                            xấu, tăng cường sinh khí và ổn định cảm xúc.</li>
                                        <li><b>Vị trí:</b> Đặt các trụ đá hoặc quả cầu đá ở phòng khách, góc nhà, hoặc những
                                            nơi cảm thấy năng lượng tù đọng.
                                        </li>
                                    </ul>
                                </li>
                                <li><b>Bùa Trấn Trạch:</b>
                                    <ul style="list-style: circle; margin-left: 2rem;">
                                        <li><b>Công dụng:</b> Là những đạo phù được các pháp sư, thầy phong thủy cao tay vẽ
                                            và trì chú. Chúng có năng lực trấn yểm rất mạnh, thường dùng cho các trường hợp
                                            đặc biệt.</li>
                                        <li><b>Vị trí:</b> Dán ở cửa chính, bốn góc nhà, hoặc chôn dưới đất theo chỉ định
                                            của thầy.
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                            <h5>
                                4. Lưu Ý Cực Kỳ Quan Trọng: Trấn Trạch Là "Con Dao Hai Lưỡi"
                            </h5>
                            <p>Đây là phần quan trọng nhất. Nếu thực hiện sai cách, trấn trạch và yểm trấn không những không
                                hiệu quả mà còn có thể gây ra những hậu quả nghiêm trọng ("phản tác dụng").</p>
                            <ul>
                                <li><b>1.Không Tự Ý Thực Hiện:</b> Đây không phải là một mẹo vặt "DIY". Đặc biệt là Yểm
                                    Trấn,
                                    đòi hỏi kiến thức sâu rộng, kinh nghiệm và năng lực tâm linh. <b>Hãy tìm đến các chuyên
                                        gia, thầy phong thủy, pháp sư có uy tín, đức độ và tâm sáng.</b></li>

                                <li><b>2.Phải "Khai Quang Điểm Nhãn":</b> Vật phẩm phong thủy chỉ là vật vô tri nếu chưa
                                    được khai quang, trì chú để kích hoạt linh tính.</b></li>

                                <li><b>3.Đúng Người, Đúng Thời Điểm, Đúng Vị Trí:</b> Cùng một vật phẩm nhưng đặt sai vị
                                    trí, sai thời gian hoặc không hợp với mệnh của gia chủ đều có thể gây hại.</li>
                                <li><b>4.Cái Gốc Là "Tâm":</b> Phong thủy là công cụ hỗ trợ. Nền tảng của một ngôi nhà an
                                    yên chính là phúc đức và tâm tính của những người sống trong đó. "Tâm an thì trạch an".
                                    Dù có trấn yểm tốt đến đâu mà gia đình bất hòa, làm việc thất đức thì cũng không thể bền
                                    vững.</li>

                            </ul>
                            <div class="h5">
                                <b>Kết luận:</b>
                            </div>
                            <p>Trấn Trạch và Yểm Trấn là một phần sâu sắc và quan trọng của văn hóa tâm linh phương Đông,
                                thể hiện mong muốn kiến tạo một không gian sống hài hòa giữa Con người và Thiên - Địa. Chúng
                                không phải là mê tín dị đoan, mà là những phương pháp điều chỉnh năng lượng dựa trên sự quan
                                sát và đúc kết qua hàng ngàn năm.</p>
                            <p>Để có một ngôi nhà thực sự bình an và thịnh vượng, hãy kết hợp việc áp dụng phong thủy một
                                cách đúng đắn với việc tu dưỡng đạo đức, sống lương thiện và xây dựng một gia đình hòa
                                thuận. Đó mới là nền tảng vững chắc nhất cho mọi sự tốt lành.</p>
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
