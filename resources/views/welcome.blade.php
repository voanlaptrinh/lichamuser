<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chuyển Đổi Ngày Dương Sang Âm</title>
    <!-- Các link CSS nếu cần, ví dụ: Bootstrap hoặc custom CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/airbnb.css"> {{-- hoặc dark, material_red --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('/css/styledemo.css') }}">
</head>

<body>
    <div class="container">
        <header class="d-flex justify-content-center py-3" style=" background: papayawhip">
            <ul class="nav nav-pills">
                <li class="nav-item"><a href="{{ route('home') }}" class="nav-link active" aria-current="page">Trang
                        chủ</a></li>
                <li class="nav-item"><a href="{{ route('horoscope.index') }}" class="nav-link text-dark" aria-current="page">Cung
                        hoàng đạo</a></li>
                <li>
                    <div class="dropdown">
                        <button class="btn dropdown-toggle" type="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            Lịch Tháng
                        </button>
                        <ul class="dropdown-menu">
                            @php($currentYear = date('Y'))
                            @for ($month = 1; $month <= 12; $month++)
                                <li>
                                    <a class="dropdown-item"
                                        href="{{ route('lich.thang', ['nam' => $currentYear, 'thang' => $month]) }}">Tháng
                                        {{ $month }}</a>
                                </li>
                            @endfor

                        </ul>
                    </div>

                </li>
                <li>
                    <div class="dropdown">
                        <button class="btn dropdown-toggle" type="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            Lịch năm
                        </button>
                        <ul class="dropdown-menu">
                            @php($currentYearHeader = date('Y'))
                            @php($startYearHeader = $currentYearHeader - 1)
                            @php($endYearHeader = $currentYearHeader + 10)

                            @for ($year = $startYearHeader; $year <= $endYearHeader; $year++)
                                <li>
                                    <a href="{{ route('lich.nam', ['nam' => $year]) }}">
                                        Xem lịch năm {{ $year }}
                                    </a>
                                </li>
                            @endfor
                        </ul>
                    </div>

                </li>
                <li class="nav-item"><a href="{{ route('van-khan.index') }}" class="nav-link text-dark" aria-current="page">Văn
                        khấn</a></li>
                <li class="nav-item"><a href="{{ route('thuoc-lo-ban.index') }}" class="nav-link text-dark" aria-current="page">Thước lỗ ban</a></li>
            </ul>
        </header>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-lg-9">
                @yield('content')
            </div>
            <div class="col-lg-3">
                <ul class="">

                    <li class="nav-item"><a href="{{ route('astrology.form') }}" class="nav-link">Xem tuổi cưới hỏi</a>
                    </li>
                    <li class="nav-item"><a href="{{ route('buy-house.form') }}" class="nav-link">Xem ngày mua nhà</a>
                    </li>
                    <li class="nav-item"><a href="{{ route('breaking.form') }}" class="nav-link">Xem ngày động thổ</a>
                    </li>
                    <li class="nav-item"><a href="{{ route('nhap-trach.form') }}" class="nav-link">Xem ngày nhập
                            trạch</a>
                    </li>
                    <li class="nav-item"><a href="{{ route('xuat-hanh.form') }}" class="nav-link">Xem ngày xuất
                            hành</a>
                    </li>
                    <li class="nav-item"><a href="{{ route('khai-truong.form') }}" class="nav-link">Xem ngày khai
                            trương</a>
                    </li>
                    <li class="nav-item"><a href="{{ route('ky-hop-dong.form') }}" class="nav-link">Xem ngày hý hợp
                            đồng</a>
                    </li>
                    <li class="nav-item"><a href="{{ route('cai-tang.form') }}" class="nav-link">Xem ngày cải táng</a>
                    </li>
                    <li class="nav-item"><a href="{{ route('ban-tho.form') }}" class="nav-link">Xem ngày Đổi ban
                            thờ</a>
                    </li>
                    <li class="nav-item"><a href="{{ route('lap-ban-tho.form') }}" class="nav-link">Xem ngày Lập ban
                            thờ</a> </li>
                    <li class="nav-item"><a href="{{ route('giai-han.form') }}" class="nav-link">Xem Ngày Cúng sao -
                            giải
                            hạn</a> </li>
                    <li class="nav-item"><a href="{{ route('tran-trach.form') }}" class="nav-link">Xem Ngày yểm trấn -
                            trấn
                            trạch</a> </li>
                    <li class="nav-item"><a href="{{ route('phong-sinh.form') }}" class="nav-link">Xem Ngày Cầu an -
                            làm
                            phúc - phóng sinh</a> </li>
                    <li class="nav-item"><a href="{{ route('mua-xe.form') }}" class="nav-link">Xem ngày mua xe - nhận
                            xe
                            mới</a> </li>
                    <li class="nav-item"><a href="{{ route('du-lich.form') }}" class="nav-link">Xem ngày xuất hành - du
                            lịch - công tác</a> </li>
                    <li class="nav-item"><a href="{{ route('thi-cu.form') }}" class="nav-link">Xem ngày thi cử phỏng
                            vấn</a> </li>
                    <li class="nav-item"><a href="{{ route('cong-viec-moi.form') }}" class="nav-link">Xem Ngày Nhận
                            công
                            việc mới</a> </li>
                    <li class="nav-item"><a href="{{ route('giay-to.form') }}" class="nav-link">Xem ngày làm giấy tờ
                            -
                            cccd, hộ chiếu </a> </li>
                    <li class="nav-item"><a href="{{ route('huong-ban-tho.form') }}" class="nav-link">Xem Hướng ban
                            thờ
                        </a> </li>
                    <li class="nav-item"><a href="{{ route('huong-nha.form') }}" class="nav-link">Xem Hướng nhà hợp
                            tuổi</a> </li>
                    <li class="nav-item"><a href="{{ route('huong-bep.form') }}" class="nav-link">Xem Hướng bếp hợp
                            tuổi</a> </li>
                    <li class="nav-item"><a href="{{ route('huong-phong-ngu.form') }}" class="nav-link">Xem Hướng
                            phòng
                            ngủ
                            hợp tuổi</a> </li>
                    <li class="nav-item"><a href="{{ route('huong-ban-lam-viec.form') }}" class="nav-link">Xem Hướng
                            Bàn
                            làm việc</a> </li>
                    <li class="nav-item"><a href="{{ route('xem-ngay-cua-con.index') }}" class="nav-link">Xem giờ sinh của con</a> </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- JS của Bootstrap (nếu sử dụng Bootstrap) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/vn.js"></script>


    @stack('scripts')

    <script>
        document.addEventListener("DOMContentLoaded", () => {







            const maxDate = new Date(new Date().getFullYear(), 11, 31); // 31/12 năm nay
            function rangeShortcutPlugin() {
                // Danh sách các nút chọn nhanh
                const shortcuts = [{
                        label: "7 ngày tới",
                        days: 7
                    },
                    {
                        label: "15 ngày tới",
                        days: 15
                    },
                    {
                        label: "30 ngày tới",
                        days: 30
                    },
                ];

                return function(fp) {
                    return {
                        onReady: function() {
                            // Dùng class của Bootstrap để tạo layout
                            const container = document.createElement("div");
                            container.className =
                                "d-flex justify-content-center flex-wrap gap-2 p-2 border-top";

                            shortcuts.forEach(shortcut => {
                                const btn = document.createElement("button");
                                btn.className = "btn btn-sm btn-outline-primary";
                                btn.type = "button";
                                btn.textContent = shortcut.label;

                                btn.addEventListener("click", () => {
                                    // Tính toán ngày bắt đầu và kết thúc một cách an toàn
                                    const startDate = new Date();
                                    const endDate = new Date();
                                    endDate.setDate(startDate.getDate() + shortcut.days -
                                        1);

                                    // SỬA ĐỔI QUAN TRỌNG Ở ĐÂY
                                    // Đặt ngày và thêm `false` để không kích hoạt sự kiện onChange
                                    fp.setDate([startDate, endDate], false);

                                    // Cập nhật lại giá trị hiển thị trên input và đóng calendar
                                    fp.altInput.value = fp.formatDate(startDate, "d/m/Y") +
                                        " - " + fp.formatDate(endDate, "d/m/Y");
                                    fp.close();
                                });

                                container.appendChild(btn);
                            });

                            fp.calendarContainer.appendChild(container);
                        }
                    };
                };
            }
            // Flatpickr cho ngày sinh chú rể
            flatpickr("input[name='groom_dob']", {
                dateFormat: "d/m/Y",
                maxDate: maxDate,
                locale: "vn",

            });

            // Flatpickr cho ngày sinh cô dâu
            flatpickr("input[name='bride_dob']", {
                dateFormat: "d/m/Y",
                maxDate: maxDate,
                locale: "vn",

            });
            flatpickr(".dateuser", {
                dateFormat: "d/m/Y",
                maxDate: maxDate,
                locale: "vn",

            });
            flatpickr(".datehome", {
                altFormat: "d/m/Y", // Định dạng cho người dùng xem
                dateFormat: "d/m/Y", // ĐỊNH DẠNG GỬI LÊN SERVER -> Đây là cái quyết định
                maxDate: maxDate,
                locale: "vn",

            });

            const overallMinDate = new Date(new Date().getFullYear() - 10, 0, 1);
            const overallMaxDate = new Date(new Date().getFullYear() + 10, 11, 31);

            flatpickr('.wedding_date_range', {
                mode: "range",
                dateFormat: "d/m/Y",
                locale: "vn",
                minDate: overallMinDate,
                maxDate: overallMaxDate,

                onChange: function(selectedDates, dateStr, instance) {
                    // Khi người dùng mới chỉ chọn 1 ngày (ngày bắt đầu)
                    if (selectedDates.length === 1) {
                        const startDate = selectedDates[0];

                        // Tạo một bản sao của ngày bắt đầu để tính toán mà không ảnh hưởng đến ngày gốc
                        const newMaxDate = new Date(startDate);

                        // Đặt ngày thành ngày cuối cùng của tháng thứ 12 kể từ ngày bắt đầu.
                        // Mẹo: Ngày 0 của tháng (N+1) chính là ngày cuối cùng của tháng N.
                        // Ví dụ: Ngày 0 của tháng 4 là ngày 31 của tháng 3.
                        // Do đó, chúng ta sẽ đi tới tháng thứ 13 (so với tháng bắt đầu) và lấy ngày 0.
                        newMaxDate.setMonth(startDate.getMonth() + 13, 0);

                        // --- KẾT THÚC LOGIC SỬA ĐỔI ---

                        // Cập nhật lại giới hạn cho calendar
                        instance.set('minDate', startDate);
                        instance.set('maxDate', newMaxDate);
                    }

                    // Khi người dùng xóa lựa chọn, reset lại giới hạn ban đầu
                    if (selectedDates.length === 0) {
                        instance.set('minDate', overallMinDate);
                        instance.set('maxDate', overallMaxDate);
                    }
                },

                onOpen: function(selectedDates, dateStr, instance) {
                    // Khi mở lại calendar mà đã có 1 khoảng được chọn, reset lại để người dùng chọn lại từ đầu
                    if (selectedDates.length === 2) {
                        instance.set('minDate', overallMinDate);
                        instance.set('maxDate', overallMaxDate);
                    }
                },

                plugins: [
                    rangeShortcutPlugin() // Plugin của bạn vẫn hoạt động tốt
                ]
            });

            const duongInput = document.getElementById('duong_date');
            const amInput = document.getElementById('am_date');
            const cdateInput = document.getElementById('cdate'); // Input ẩn để submit
            const form = document.getElementById('convertForm');
            // Lấy CSRF token từ form, an toàn hơn
            // const csrf = form.querySelector('input[name="_token"]').value;

            // Hàm helper để tránh lặp code
            async function updateDate(sourceElement, targetElement, apiUrl) {
                const dateValue = sourceElement.value;

                // Nếu người dùng xóa ngày, thì cũng xóa ngày ở ô còn lại
                if (!dateValue) {
                    targetElement.value = '';
                    // Nếu xóa ngày dương, xóa luôn cdate
                    if (sourceElement.id === 'duong_date') {
                        cdateInput.value = '';
                    }
                    return;
                }

                try {
                    const response = await fetch(apiUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({
                            date: dateValue
                        })
                    });

                    if (!response.ok) {
                        // Xử lý lỗi từ server, ví dụ hiển thị thông báo
                        console.error('Lỗi server:', await response.text());
                        return;
                    }

                    const data = await response.json();

                    if (data.date) {
                        targetElement.value = data.date;

                        // Luôn cập nhật input ẩn `cdate` với giá trị DƯƠNG LỊCH
                        if (sourceElement.id === 'duong_date') {
                            cdateInput.value = sourceElement.value; // Là chính nó
                        } else { // Nếu nguồn là ngày âm, thì `cdate` là kết quả chuyển đổi
                            cdateInput.value = data.date;
                        }
                    } else if (data.error) {
                        console.error('Lỗi chuyển đổi:', data.error);
                    }
                } catch (error) {
                    console.error('Lỗi fetch:', error);
                }
            }

            // Sự kiện đổi ngày dương → âm
            if (duongInput) {
                duongInput.addEventListener('change', () => {
                    // Sử dụng route() của Laravel để lấy URL an toàn
                    updateDate(duongInput, amInput, "{{ route('api.to.am') }}");
                });
            }

            // Sự kiện đổi ngày âm → dương
            if (amInput) {
                amInput.addEventListener('change', () => {
                    updateDate(amInput, duongInput, "{{ route('api.to.duong') }}");
                });
            }

            // Kiểm tra trước khi submit form
            if (form) {
                form.addEventListener('submit', (e) => {
                    // Chỉ cần kiểm tra `cdate` vì nó luôn là ngày dương cuối cùng
                    if (!cdateInput.value) {
                        e.preventDefault(); // Ngăn form submit
                        alert("Vui lòng chọn ngày để xem chi tiết.");
                    }
                });
            }
        });
    </script>


</body>

</html>
