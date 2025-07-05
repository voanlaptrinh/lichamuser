@extends('welcome')
@section('content')
    <div class="container">
        <h1>Lịch Âm Năm {{ $nam }} ({{ $can_chi_nam }})</h1>
        <p>Xem các ngày tốt, xấu trong năm {{ $nam }}.</p>
        {{-- Hiển thị thông tin chi tiết tại đây --}}
    </div>
    @php
        use App\Helpers\LunarHelper;
    @endphp
    <div class="container">
        @for ($i = 1; $i <= 12; $i++)
            <div class="row">
                <div class="col-md-12">
                    <div class="vncal-header">
                        <div class="vncal-header-titles">
                            <a href="#" title="Xem lịch âm tháng {{ $i }} năm {{ $nam }}">
                                <h4>THÁNG {{ $i }} NĂM {{ $nam }}</h4>
                            </a>
                        </div>
                    </div>
                    <table class="calendar-table">
                        <thead>
                            <tr>
                                <th><span class="title-lich-pc">Thứ hai</span> <span class="title-lich-mobie">Th 2</span>
                                </th>
                                <th><span class="title-lich-pc">Thứ ba</span> <span class="title-lich-mobie">Th 3</span>
                                </th>
                                <th><span class="title-lich-pc">Thứ tư</span> <span class="title-lich-mobie">Th 4</span>
                                </th>
                                <th><span class="title-lich-pc">Thứ năm</span> <span class="title-lich-mobie">Th 5</span>
                                </th>
                                <th><span class="title-lich-pc">Thứ sau</span> <span class="title-lich-mobie">Th 6</span>
                                </th>
                                <th><span class="title-lich-pc">Thứ bảy</span> <span class="title-lich-mobie">Th 7</span>
                                </th>
                                <th><span class="title-lich-pc">Chủ nhật</span> <span class="title-lich-mobie">CN</span>
                                </th>

                            </tr>
                        </thead>
                        <tbody>
                            {!! LunarHelper::printTable($i, $nam, false) !!}
                        </tbody>
                    </table>

                </div>
            </div>
            <br>
        @endfor
        <div>
            <h4>Sự kiện ngày dương</h4>
            {!! $sukienduong !!}
        </div>
        <div>
            <h4>sự kiện ngày âm</h4>
            {!! $sukienam !!}
        </div>
    </div>
@endsection
