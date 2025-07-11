@extends('welcome')

@section('content')
    <div class="row">
        <div class="col-lg-8">
            <div class=" mt-3 boder_fix">
                <div class="breadcrumb1">
                    <ul>
                        <li class="breadcrumb1-item"><a href="/">Trang chủ</a></li>
                        <li class="breadcrumb1-item">Xem ngày tốt xấu</li>
                        <li class="breadcrumb1-item">Xem ngày tốt để đổi ban thờ</li>
                    </ul>
                </div>
                <div class="page-head">
                    <h1 class="page-head-title h4 h3-sm h2-md h1-lg text-uppercase">
                        Xem Ngày Tốt Thay Đổi Bàn Thờ
                    </h1>
                    <p class="pb-2">Bàn thờ không chỉ là một món đồ nội thất, mà là <b>trung tâm tâm linh</b> của cả gia
                        đình. Đây là nơi linh thiêng kết nối giữa cõi trần và thế giới vô hình, là nơi con cháu thể hiện
                        lòng thành kính với Gia tiên, Thần linh.
                    </p>
                </div>
                <div class="card ">
                    <div class="card-body">
                        <form action="{{ route('ban-tho.check') }}" method="POST" class="form_dmy date-picker">
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
                                    <label for="wedding_date_range" class="form-label">Dự kiến thời gian thực hiện: <span
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

                                <button type="submit" class="btn btn-outline-danger">XEM NGÀY TỐT AN VỊ BÀN THỜ</button>
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
                                <div class="card-body  p-2">

                                    <div class="tab-content" id="yearTabContent">
                                        @foreach ($resultsByYear as $year => $data)
                                            <div class="tab-pane fade @if ($loop->first) show active @endif"
                                                id="tab-{{ $year }}" role="tabpanel"
                                                aria-labelledby="tab-{{ $year }}-tab">
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <div class="card p-4 ">
                                                            <div class="h6 mb-0 text-uppercase" class="mb-3">Thông tin gia
                                                                chủ</div>
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
                                                                    {{ $date_start_end[0] }}
                                                                    đến {{ $date_start_end[1] }} </li>
                                                            </ul>

                                                        </div>
                                                    </div>
                                                    <div class="info-panel">
                                                        <div class="h6"> <b>* Quy Trình An Vị Bàn Thờ (Tham Khảo)</b>
                                                        </div>

                                                        <ul>
                                                            <li><b>1.Làm Lễ Xin Phép:</b> Trước ngày thực hiện, gia chủ phải
                                                                thắp hương, chuẩn bị lễ vật đơn giản và đọc văn khấn để xin
                                                                phép được di dời/thay mới bàn thờ.
                                                            </li>
                                                            <li><b>2. Chuẩn Bị Bàn Thờ Mới:</b> Lau dọn bàn thờ mới bằng
                                                                rượu gừng hoặc nước thơm ngũ vị để tẩy uế, thanh lọc.</li>
                                                            <li><b>3. Hạ Lễ:</b> Vào ngày giờ tốt, sau khi khấn vái, tiến
                                                                hành hạ các đồ thờ cúng xuống. Bát hương phải được đặt ở nơi
                                                                sạch sẽ, trang trọng, dùng vải đỏ che phủ.</li>
                                                            <li><b>4. An Vị:</b> Đặt bàn thờ vào vị trí mới, sau đó lần lượt
                                                                an vị lại các đồ thờ cúng, bát hương đặt sau cùng.</li>
                                                            <li><b>5. Thắp Hương:</b> Sau khi an vị xong, thắp hương và làm
                                                                lễ trình báo đã hoàn tất. Nên thắp hương liên tục trong
                                                                những ngày tiếp theo để bàn thờ "tụ khí".</li>
                                                        </ul>
                                                        <p class="fst-italic fw-bolder text-center pt-2">Kính chúc Quý gia
                                                            chủ thực hiện nghi lễ viên mãn, gia đạo bình an, phúc lộc đủ
                                                            đầy!</p>

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
                                                                                chọn
                                                                                của năm nay,
                                                                                không tìm thấy ngày nào thực sự tốt để tiến
                                                                                hành
                                                                                xây dựng.
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
                            <h4>Xem Ngày Tốt Thay Đổi Bàn Thờ: An Vị Gia Tiên, Gia Tăng Phúc Lộc</h4>
                            <p>Do đó, bất kỳ sự thay đổi nào, dù là di dời hay thay mới bàn thờ, đều là một việc đại sự,
                                phải được thực hiện vào ngày giờ tốt và đúng nghi lễ để tránh "kinh động" đến các Ngài và
                                đảm bảo sự liền mạch của phúc khí gia tộc.</p>
                            <div class="h5">Tại Sao Phải Tuyệt Đối Cẩn Trọng Khi Thay Đổi Bàn Thờ?</div>
                            <p>Việc tùy tiện thay đổi bàn thờ có thể bị coi là "phạm thượng", gây ảnh hưởng không tốt đến sự
                                an yên của cả gia đình.</p>
                            <table class="table table-bordered table-actent">
                                <tbody>
                                    <tr>
                                        <td>
                                            <b>Thể Hiện Lòng Thành Kính</b>
                                        </td>
                                        <td>
                                            Chọn ngày giờ cẩn thận là hành động đầu tiên thể hiện sự tôn trọng, nghiêm túc
                                            và lòng hiếu kính của gia chủ đối với các đấng bề trên.
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <b> Đảm Bảo Sự "An Vị"</b>
                                        </td>
                                        <td>
                                            Giúp các vị Thần linh, Gia tiên "an tọa" tại vị trí mới một cách trang nghiêm,
                                            không bị gián đoạn, từ đó tiếp tục phù hộ độ trì cho con cháu.
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <b> Đón Nhận Sinh Khí Mới</b>
                                        </td>
                                        <td>
                                            Khi chuyển đến một vị trí tốt hơn vào ngày giờ hoàng đạo, bàn thờ sẽ đón nhận
                                            được nguồn năng lượng mới, vượng khí mới, giúp gia tăng tài lộc, may mắn.
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <b>Tránh Phạm Phải Đại Kỵ</b>
                                        </td>
                                        <td>
                                            Thực hiện sai ngày, sai cách có thể làm kinh động đến long mạch, gây xáo trộn
                                            trong gia đạo, ảnh hưởng đến sức khỏe và công việc làm ăn của các thành viên.
                                        </td>
                                    </tr>

                                </tbody>
                            </table>
                            <div class="h5">Phương Pháp Luận Giải Theo Chuyên Gia</div>
                            <p>Xem ngày an vị bàn thờ đòi hỏi sự phân tích kỹ lưỡng, kết hợp nhiều yếu tố:</p>
                            <ul style="list-style: circle; margin-left: 2rem;">
                                <li><b>Dựa vào Tuổi Gia Chủ:</b> Phân tích Can-Chi, Ngũ Hành của người chủ gia đình (thường
                                    là nam giới) để tìm ngày tương sinh, tránh ngày xung khắc.
                                </li>
                                <li><b>Xét Hướng Bàn Thờ:</b> Hướng đặt bàn thờ mới là yếu tố cực kỳ quan trọng. Công cụ sẽ
                                    đối chiếu hướng này với các sao tốt, sao xấu để đảm bảo vị trí mới là "tọa cát hướng
                                    cát".
                                </li>
                                <li><b>Loại Trừ Ngày Xấu:</b> Tự động lọc bỏ các ngày đại kỵ như Tam Nương, Nguyệt Kỵ, Sát
                                    Chủ và đặc biệt là những ngày có sao xấu chiếu đến phương vị của bàn thờ.</li>
                                <li><b>Chọn Giờ Hoàng Đạo:</b> Gợi ý các khung giờ vàng trong ngày để thực hiện các nghi lễ
                                    quan trọng nhất như hạ bát hương cũ và an vị bát hương mới.
                                </li>
                            </ul>
                            <div class="info-panel">
                                <div class="h6"> <b>* Quy Trình An Vị Bàn Thờ (Tham Khảo)</b>
                                </div>

                                <ul>
                                    <li><b>1.Làm Lễ Xin Phép:</b> Trước ngày thực hiện, gia chủ phải
                                        thắp hương, chuẩn bị lễ vật đơn giản và đọc văn khấn để xin
                                        phép được di dời/thay mới bàn thờ.
                                    </li>
                                    <li><b>2. Chuẩn Bị Bàn Thờ Mới:</b> Lau dọn bàn thờ mới bằng
                                        rượu gừng hoặc nước thơm ngũ vị để tẩy uế, thanh lọc.</li>
                                    <li><b>3. Hạ Lễ:</b> Vào ngày giờ tốt, sau khi khấn vái, tiến
                                        hành hạ các đồ thờ cúng xuống. Bát hương phải được đặt ở nơi
                                        sạch sẽ, trang trọng, dùng vải đỏ che phủ.</li>
                                    <li><b>4. An Vị:</b> Đặt bàn thờ vào vị trí mới, sau đó lần lượt
                                        an vị lại các đồ thờ cúng, bát hương đặt sau cùng.</li>
                                    <li><b>5. Thắp Hương:</b> Sau khi an vị xong, thắp hương và làm
                                        lễ trình báo đã hoàn tất. Nên thắp hương liên tục trong
                                        những ngày tiếp theo để bàn thờ "tụ khí".</li>
                                </ul>
                                <p class="fst-italic fw-bolder text-center pt-2">Kính chúc Quý gia
                                    chủ thực hiện nghi lễ viên mãn, gia đạo bình an, phúc lộc đủ
                                    đầy!</p>

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
