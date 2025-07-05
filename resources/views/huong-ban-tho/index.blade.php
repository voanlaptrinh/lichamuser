@extends('welcome')

@section('content')
    <div class="container mt-4 mb-5">
        {{-- PHẦN FORM GIỮ NGUYÊN --}}
        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <h1 class="h3 mb-0">Xem hướng ban thờ</h1>
            </div>
            <div class="card-body">
                <form action="{{ route('huong-ban-tho.check') }}" method="POST">
                    @csrf

                    <div class="row">

                        <div class="col-md-6 mb-3">
                            <label for="birthdate" class="form-label fw-bold">Ngày sinh gia chủ (Dương lịch)</label>
                            {{-- Sử dụng biến $birthDate được truyền từ controller để điền vào value --}}
                            <input type="text" class="form-control dateuser @error('birthdate') is-invalid @enderror"
                                id="birthdate" name="birthdate" placeholder="dd/mm/yyyy"
                                value="{{ $birthDate ?? old('birthdate') }}" required>
                            @error('birthdate')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="gioi_tinh" class="form-label fw-bold">Giới tính</label>
                            <select name="gioi_tinh" class="form-select @error('gioi_tinh') is-invalid @enderror" required>
                                <option value="">-- Chọn giới tính --</option>
                                {{-- Sử dụng biến $gender để xác định option nào được 'selected' --}}
                                <option value="nam" {{ isset($gender) && $gender == 'nam' ? 'selected' : '' }}>Nam
                                </option>
                                <option value="nữ" {{ isset($gender) && $gender == 'nữ' ? 'selected' : '' }}>Nữ
                                </option>
                            </select>
                            @error('gioi_tinh')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">Xem Kết Quả</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Giả sử bạn có biến $resultsByYear sau khi form được submit --}}
        @if (isset($results))
            {{-- @dd($results) --}}
            <div class="row g-3 mt-4">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <ul>
                                <li>Ngày sinh dương lịch: {{ $results['basicInfo']['ngaySinhDuongLich'] }}</li>
                                <li>Ngày sinh âm lịch: {{ $results['basicInfo']['ngaySinhAmLich'] }}</li>
                                <li>Giới tính: {{ $results['basicInfo']['gioiTinh'] }}</li>
                                <li>Mệnh quái: {{ $results['basicInfo']['menhQuai'] }}</li>
                                <li>Thuộc nhóm: {{ $results['basicInfo']['thuocNhom'] }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="text-center">Nguyên tắc đặt ban thờ</h5>
                            <ul>
                                <li>{{ $results['nguyenTacDatBanTho'][0] }}</li>
                                <li>{{ $results['nguyenTacDatBanTho'][1] }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="text-center">Hướng đặt ban thờ tốt nhất cho {{ $results['basicInfo']['gioiTinh'] }}
                                {{ $nam_sinh }}</h5>

                            <table class="table table-primary table-bordered">
                                <thead>
                                    <tr>
                                        <th scope="col">Hướng</th>
                                        <th scope="col">Ý nghĩa</th>
                                        <th scope="col">Ưu tiên</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($results['huongDatBanThoTotNhat'] as $item)
                                        <tr>
                                            <td>{{ $item['huong'] }}</td>
                                            <td>{{ $item['y_nghia'] }}</td>
                                            <td>{{ $item['uu_tien'] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="text-center">
                                Những điều cần tránh khi đặt ban thờ
                            </h5>
                            <div>
                                <h6>
                                    1. Bàn thờ đặt đối diện cửa ra vào hoặc cửa sổ lớn
                                </h6>
                                <ul>
                                    <li>
                                        Làm khí bị xung, tán tài, mất linh khí, dễ động.
                                    </li>
                                    <li>
                                        Gây mất sự trang nghiêm, dễ bị quấy nhiễu.
                                    </li>
                                </ul>
                            </div>
                            <div>
                                <h6>
                                    2. Tựa lưng bàn thờ vào khoảng trống
                                </h6>
                                <ul>
                                    <li>
                                        Tượng trưng cho không có điểm tựa, tổ tiên không được “an vị”.
                                    </li>
                                    <li>
                                        Phải tựa vào tường vững chắc, không rung lắc.
                                    </li>
                                </ul>
                            </div>
                            <div>
                                <h6>

                                    3. Đặt bàn thờ dưới xà ngang, gầm cầu thang, nhà vệ sinh
                                </h6>
                                <ul>
                                    <li>
                                        Gây áp lực sát khí, mất tôn nghiêm.
                                    </li>
                                    <li>
                                        Dễ sinh bệnh, bất an về tinh thần.
                                    </li>
                                </ul>
                            </div>
                            <div>
                                <h6>

                                    4. Đặt bàn thờ cạnh bếp, nhà tắm, hoặc nơi ô uế
                                </h6>
                                <ul>
                                    <li>
                                        Hỏa khí, thủy khí và tạp khí phá hủy trường năng lượng tâm linh.
                                    </li>
                                    <li>
                                        Phạm đại kỵ, tổ tiên không ứng.
                                    </li>
                                </ul>

                            </div>
                            <div>
                                <h6>
                                    5. Bài trí bàn thờ lộn xộn, bừa bộn
                                </h6>
                                <ul>
                                    <li>
                                        Đồ thờ, ảnh thờ đặt sai thứ tự, hoa héo, hương tàn.
                                    </li>
                                    <li>
                                        Làm mất phúc khí, giảm sự linh ứng.
                                    </li>
                                </ul>
                            </div>
                            <div>
                                <h6>
                                    6. Bàn thờ quá cao hoặc quá thấp
                                </h6>
                                <ul>
                                    <li>
                                        Cao quá → khó chăm sóc, cách xa con cháu.
                                    </li>
                                    <li>
                                        Thấp quá → bất kính, không hợp nguyên lý “thiên cao, địa thấp”.
                                    </li>
                                </ul>
                            </div>
                            <div>
                                <h6>
                                    7. Dưới bàn thờ làm tủ, nhà kho, hoặc để đồ linh tinh
                                </h6>
                                <ul>
                                    <li>
                                        Làm mất đi sự thanh tịnh, không khí trang nghiêm.
                                    </li>
                                    <li>
                                        Cao quá → khó chăm sóc, cách xa con cháu.
                                    </li>
                                    <li>
                                        Thấp quá → bất kính, không hợp nguyên lý “thiên cao, địa thấp”.
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

    </div>
@endsection
