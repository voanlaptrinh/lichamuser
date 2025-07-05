@extends('welcome')

@section('content')
    <div class="container mt-4 mb-5">
        {{-- FORM NHẬP LIỆU --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-light">
                <h1 class="h3 mb-0">Xem Hướng Bếp Hợp Tuổi</h1>
            </div>
            <div class="card-body">
                <p>Trong phong thủy, bếp đóng vai trò quan trọng trong việc trấn áp hung khí và mang lại sức khỏe, tài lộc.
                    Hãy xem hướng đặt bếp hợp với tuổi của bạn.</p>
                <form action="{{ route('huong-bep.check') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="birthdate" class="form-label fw-bold">Ngày sinh (Dương lịch)</label>
                            <input type="text" class="form-control dateuser @error('birthdate') is-invalid @enderror"
                                id="birthdate" name="birthdate" placeholder="dd/mm/yyyy"
                                value="{{ $birthdate ?? old('birthdate') }}" required>
                            @error('birthdate')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="gioi_tinh" class="form-label fw-bold">Giới tính</label>
                            <select name="gioi_tinh" class="form-select @error('gioi_tinh') is-invalid @enderror" required>
                                <option value="">-- Chọn giới tính --</option>
                                <option value="nam" {{ isset($gioi_tinh) && $gioi_tinh == 'nam' ? 'selected' : '' }}>
                                    Nam</option>
                                <option value="nữ" {{ isset($gioi_tinh) && $gioi_tinh == 'nữ' ? 'selected' : '' }}>Nữ
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

        {{-- PHẦN HIỂN THỊ KẾT QUẢ --}}
        @if (isset($results))
            <div class="results-container">
                <div class="card">
                    <div class="card-body">
                        {{-- 1. THÔNG TIN CƠ BẢN --}}
                        <h3 class="h5">THÔNG TIN CƠ BẢN</h3>
                        <ul class="list-unstyled">
                            <li>Năm sinh dương lịch: {{ $results['basicInfo']['ngaySinhDuongLich'] }}</li>
                            <li>Năm sinh âm lịch: {{ $results['basicInfo']['ngaySinhAmLich'] }}</li>
                            <li>Giới tính: {{ $results['basicInfo']['gioiTinh'] }}</li>
                            <li>Mệnh quái: {{ $results['basicInfo']['menhQuai'] }}</li>
                            <li>Thuộc nhóm: {{ $results['basicInfo']['thuocNhom'] }}</li>
                        </ul>

                        <hr>

                        {{-- 2. NGUYÊN TẮC CHỌN HƯỚNG BẾP --}}
                        <h3 class="h5">NGUYÊN TẮC CHỌN HƯỚNG BẾP</h3>
                        <p class="mb-1">Nguyên tắc chọn: <strong>{{ $results['nguyenTac']['title'] }}</strong></p>
                        <ul>
                            @foreach ($results['nguyenTac']['rules'] as $rule)
                                <li>{{ $rule }}</li>
                            @endforeach
                        </ul>
                        <p><em>Lưu ý: {{ $results['nguyenTac']['note'] }}</em></p>

                        <hr>

                        {{-- 3. HƯỚNG BẾP TỐT NHẤT --}}
                        <h3 class="h5">HƯỚNG BẾP TỐT CHO {{ strtoupper($results['basicInfo']['gioiTinh']) }}
                            {{ explode('/', $results['basicInfo']['ngaySinhDuongLich'])[2] }}</h3>
                        <div class="table-responsive">
                            <table class="table table-bordered text-center align-middle">
                                <thead class="table-success">
                                    <tr>
                                        <th>Hướng</th>
                                        <th>Ý nghĩa</th>
                                        <th>Ưu tiên</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($results['huongBepTotNhat'] as $huong)
                                        <tr>
                                            <td class="fw-bold">{{ $huong['Huong'] }} ({{ $huong['Loai'] }})</td>
                                            <td>{{ $huong['Y nghia'] }}</td>
                                            <td>{{ $huong['Uu tien'] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>


                        <hr>
                        <h5>
                            NHỮNG ĐIỀU CẦN TRÁNH KHI ĐẶT BẾP
                        </h5>
                        <p>Phong thủy coi bếp là nơi “tàng hỏa” – giữ lửa, giữ tài lộc, nên vị trí và hướng đặt bếp cực kỳ
                            quan trọng. Dưới đây là các điều cấm kỵ tuyệt đối cần tránh:</p>
                        <div>
                            <h6> 1. Đặt bếp đối diện cửa chính</h6>
                            <ul>
                                <li>Gọi là “Khai môn kiến táo” → mất tài lộc, gia đạo bất an.</li>
                                <li>Người trong nhà dễ nóng nảy, tiêu tán của cải.</li>
                            </ul>
                        </div>
                        <div>
                            <h6>2. Đặt bếp đối diện nhà vệ sinh </h6>
                            <ul>
                                <li> Uế khí từ WC xung thẳng vào bếp → thực phẩm bị ảnh hưởng, dễ sinh bệnh.</li>
                                <li>Gây xung khí giữa Thủy (WC) – Hỏa (bếp) → bất ổn, khẩu thiệt.</li>
                            </ul>
                        </div>
                        <div>
                            <h6>3. Đặt bếp gần hoặc trên giếng nước, bồn rửa, máy giặt</h6>
                            <ul>
                                <li>Thủy khắc Hỏa → bếp dễ tắt lửa, hao tài, tổn khí.</li>
                                <li> Gọi là “Thủy Hỏa tương xung” → vợ chồng bất hòa.</li>
                            </ul>
                        </div>
                        <div>
                            <h6>4. Đặt bếp ở giữa nhà (“Trung cung”)</h6>
                            <ul>
                                <li> Trung tâm nhà cần tĩnh, an ổn → đặt bếp vào giữa gây động, ảnh hưởng toàn cục. </li>
                                <li> Phạm vào “hỏa thiêu trung cung” → nhà dễ ly tán, bệnh tật kéo dài. </li>
                            </ul>
                        </div>
                        <div>
                            <h6>5. Bếp không có chỗ tựa (lưng bếp trống)</h6>
                            <ul>
                                <li>Giống như “bếp trôi nổi” → không vững tài khí, dễ hao hụt.</li>
                                <li>Bếp nên tựa vào tường vững chắc, kín đáo.</li>
                            </ul>
                        </div>
                        <div>
                            <h6>6. Miệng bếp nhìn thẳng ra cửa hoặc đường đi</h6>
                            <ul>
                                <li>Bếp bị “khí xung” → đun nấu không yên, mất tập trung, tán khí.</li>
                            </ul>
                        </div>
                        <div>
                            <h6>7. Đặt bếp dưới xà ngang</h6>
                            <ul>
                                <li>Gây “áp đỉnh sát” → ảnh hưởng tới người phụ nữ trong nhà: đau đầu, suy nhược.</li>
                            </ul>
                        </div>
                        <div>
                            <h6>8. Đặt bếp trên hoặc dưới phòng ngủ</h6>
                            <ul>
                                <li>Gây “Hỏa thiêu nhân đinh” → bệnh tật kéo dài, xung đột trong nhà.</li>
                            </ul>
                        </div>
                        <div>
                            <h6>Nguyên tắc vàng khi đặt bếp:</h6>
                            <p>Tọa hung – hướng cát, tránh xung – tránh thủy – tránh động – tránh uế.</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
