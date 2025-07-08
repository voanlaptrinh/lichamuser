<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chuyển Đổi Ngày Dương Sang Âm</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Các link CSS nếu cần, ví dụ: Bootstrap hoặc custom CSS -->
    <link href="{{ asset('/css/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('/css/airbnb.css') }}"> {{-- hoặc dark, material_red --}}
    <link rel="stylesheet" href="{{ asset('/css/bootstrap-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/styledemo.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/style.css') }}">
    @stack('stylehome')

</head>

<body>

    <div class="site-wrap">
        <header class="fbs__net-navbar navbar navbar-expand-lg">
            <div class="container d-flex align-items-center justify-content-between">

                <!-- Start Logo-->
                <a class="navbar-brand w-auto" href="{{ route('home') }}">
                    <img class="logo img-fluid"
                        src="https://themewagon.github.io/Nova-Bootstrap/assets/images/logo-dark.svg" alt="">
                </a>
                <!-- End Logo-->

                <!-- Start offcanvas-->
                <!-- THAY ĐỔI 1: Thêm class `flex-grow-1` để container này chiếm hết không gian ở giữa -->
                <div class="offcanvas offcanvas-start w-75 flex-grow-1" id="fbs__net-navbars" tabindex="-1"
                    aria-labelledby="fbs__net-navbarsLabel">

                    <div class="offcanvas-header">
                        <div class="offcanvas-header-logo">
                            <a class="logo-link" id="fbs__net-navbarsLabel" href="{{ route('home') }}">
                                <img class="logo img-fluid"
                                    src="https://themewagon.github.io/Nova-Bootstrap/assets/images/logo-dark.svg"
                                    alt="FreeBootstrap.net image placeholder">
                            </a>
                        </div>
                        <button class="btn-close btn-close-black" type="button" data-bs-dismiss="offcanvas"
                            aria-label="Close"></button>
                    </div>

                    <div class="offcanvas-body align-items-lg-center">

                        <!-- THAY ĐỔI 2: Thay `me-auto` bằng `mx-auto` để căn giữa menu -->
                        <ul class="navbar-nav nav mx-auto ps-lg-5 mb-2 mb-lg-0">
                            <li class="nav-item"><a class="nav-link scroll-link active" aria-current="page"
                                    href="#home">Trang chủ</a></li>

                            <li class="nav-item dropdown"><a class="nav-link dropdown-toggle scrot-dropdow"
                                    href="#" data-bs-toggle="dropdown" aria-expanded="false">Đổi ngày <i
                                        class="bi bi-chevron-down"></i></a>
                                <ul class="dropdown-menu">
                                    <li><a class="nav-link scroll-link dropdown-item"
                                            href="{{ route('doi-licham') }}">Đổi dương sang âm
                                        </a></li>
                                    <li><a class="nav-link scroll-link dropdown-item"
                                            href="{{ route('doi-licham') }}">Đổi âm sang dương
                                        </a></li>
                                </ul>
                            </li>
                            <li class="nav-item"><a class="nav-link scroll-link"
                                    href="{{ route('horoscope.index') }}">Cung hoàng đạo</a></li>


                            <li class="nav-item dropdown"><a class="nav-link dropdown-toggle scrot-dropdow"
                                    href="#" data-bs-toggle="dropdown" aria-expanded="false"> Lịch Tháng <i
                                        class="bi bi-chevron-down"></i></a>
                                <ul class="dropdown-menu">
                                    @php($currentYear = date('Y'))
                                    @for ($month = 1; $month <= 12; $month++)
                                        <li><a class="nav-link scroll-link dropdown-item"
                                                href="{{ route('lich.thang', ['nam' => $currentYear, 'thang' => $month]) }}">Tháng
                                                {{ $month }}</a></li>
                                    @endfor


                                </ul>
                            </li>
                            <li class="nav-item dropdown"><a class="nav-link dropdown-toggle scrot-dropdow"
                                    href="#" data-bs-toggle="dropdown" aria-expanded="false">Lịch năm <i
                                        class="bi bi-chevron-down"></i></a>
                                <ul class="dropdown-menu">
                                    @php($currentYearHeader = date('Y'))
                                    @php($startYearHeader = $currentYearHeader - 1)
                                    @php($endYearHeader = $currentYearHeader + 10)

                                    @for ($year = $startYearHeader; $year <= $endYearHeader; $year++)
                                        <li>
                                            <a href="{{ route('lich.nam', ['nam' => $year]) }}"
                                                class="nav-link scroll-link dropdown-item">
                                                Xem lịch năm {{ $year }}
                                            </a>
                                        </li>
                                    @endfor



                                </ul>
                            </li>
                            <li class="nav-item"><a class="nav-link scroll-link"
                                    href="{{ route('van-khan.index') }}">Văn khấn</a></li>
                            <li class="nav-item"><a class="nav-link scroll-link"
                                    href="{{ route('thuoc-lo-ban.index') }}">Thước lỗ ban</a></li>
                        </ul>

                    </div>
                </div>
                <!-- End offcanvas-->

                <div class="ms-auto w-auto">
                    <div class="header-social d-flex align-items-center gap-1">
                        <button class="fbs__net-navbar-toggler justify-content-center align-items-center ms-auto"
                            data-bs-toggle="offcanvas" data-bs-target="#fbs__net-navbars"
                            aria-controls="fbs__net-navbars" aria-label="Toggle navigation" aria-expanded="false">
                            <svg class="fbs__net-icon-menu" xmlns="http://www.w3.org/2000/svg" width="24"
                                height="24" viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <line x1="21" x2="3" y1="6" y2="6"></line>
                                <line x1="15" x2="3" y1="12" y2="12"></line>
                                <line x1="17" x2="3" y1="18" y2="18"></line>
                            </svg>
                            <svg class="fbs__net-icon-close" xmlns="http://www.w3.org/2000/svg" width="24"
                                height="24" viewbox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M18 6 6 18"></path>
                                <path d="m6 6 12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </header>


    

        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    @yield('content')
                </div>

            </div>
        </div>
    </div>

    <!-- JS của Bootstrap (nếu sử dụng Bootstrap) -->
    <script src="{{ asset('/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('/js/flatpickr.js') }}"></script>
    <script src="{{ asset('/js/vn.js') }}"></script>


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
            flatpickr(".datehomecdate", {
                altInput: true,
                altFormat: "d/m/Y", // 👈 Hiển thị cho người dùng (ngày/tháng/năm)
                dateFormat: "Y-m-d", // 👈 Gửi giá trị thực cho server (năm-tháng-ngày)
                defaultDate: "{{ old('cdate', $cdate ?? '') }}"
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

            // const duongInput = document.getElementById('duong_date');
            // const amInput = document.getElementById('am_date');
            // const cdateInput = document.getElementById('cdate'); // Input ẩn để submit
            // const form = document.getElementById('convertForm');
            // // Lấy CSRF token từ form, an toàn hơn
            // // const csrf = form.querySelector('input[name="_token"]').value;

            // // Hàm helper để tránh lặp code
            // async function updateDate(sourceElement, targetElement, apiUrl) {
            //     const dateValue = sourceElement.value;

            //     // Nếu người dùng xóa ngày, thì cũng xóa ngày ở ô còn lại
            //     if (!dateValue) {
            //         targetElement.value = '';
            //         // Nếu xóa ngày dương, xóa luôn cdate
            //         if (sourceElement.id === 'duong_date') {
            //             cdateInput.value = '';
            //         }
            //         return;
            //     }

            //     try {
            //         const response = await fetch(apiUrl, {
            //             method: 'POST',
            //             headers: {
            //                 'Content-Type': 'application/json',
            //                 'Accept': 'application/json',
            //             },
            //             body: JSON.stringify({
            //                 date: dateValue
            //             })
            //         });

            //         if (!response.ok) {
            //             // Xử lý lỗi từ server, ví dụ hiển thị thông báo
            //             console.error('Lỗi server:', await response.text());
            //             return;
            //         }

            //         const data = await response.json();

            //         if (data.date) {
            //             targetElement.value = data.date;

            //             // Luôn cập nhật input ẩn `cdate` với giá trị DƯƠNG LỊCH
            //             if (sourceElement.id === 'duong_date') {
            //                 cdateInput.value = sourceElement.value; // Là chính nó
            //             } else { // Nếu nguồn là ngày âm, thì `cdate` là kết quả chuyển đổi
            //                 cdateInput.value = data.date;
            //             }
            //         } else if (data.error) {
            //             console.error('Lỗi chuyển đổi:', data.error);
            //         }
            //     } catch (error) {
            //         console.error('Lỗi fetch:', error);
            //     }
            // }

            // // Sự kiện đổi ngày dương → âm
            // if (duongInput) {
            //     duongInput.addEventListener('change', () => {
            //         // Sử dụng route() của Laravel để lấy URL an toàn
            //         updateDate(duongInput, amInput, "{{ route('api.to.am') }}");
            //     });
            // }

            // // Sự kiện đổi ngày âm → dương
            // if (amInput) {
            //     amInput.addEventListener('change', () => {
            //         updateDate(amInput, duongInput, "{{ route('api.to.duong') }}");
            //     });
            // }

            // // Kiểm tra trước khi submit form
            // if (form) {
            //     form.addEventListener('submit', (e) => {
            //         // Chỉ cần kiểm tra `cdate` vì nó luôn là ngày dương cuối cùng
            //         if (!cdateInput.value) {
            //             e.preventDefault(); // Ngăn form submit
            //             alert("Vui lòng chọn ngày để xem chi tiết.");
            //         }
            //     });
            // }
        });
    </script>


</body>

</html>
