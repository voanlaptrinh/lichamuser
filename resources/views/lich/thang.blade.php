@extends('welcome')
@section('content')
    <div class="container">
        <h2>Lịch âm tháng {{ $mm }} năm {{ $yy }}</h2>
        <h5>Tháng {{ $can_chi_thang }}</h5>
        <p>{{ $desrtipton_thang }}</p>
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

        <div class="row">
            <div class="col-lg-6">
                <h5>Ngày tốt tháng </h5>
                <div class="row g-1">
                    @foreach ($data_totxau['tot'] as $data_tot)
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body text-center">
                                    <a href="{{route('lich.nam.ngay', ['nam' => $data_tot['yy'], 'thang' => $data_tot['mm'], 'ngay' => $data_tot['dd']])}}">
                                    Ngày {{ $data_tot['dd'] }} Tháng {{ $data_tot['mm'] }} Năm {{ $data_tot['yy'] }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

            </div>
            <div class="col-lg-6">
                <h5>Ngày Xấu tháng </h5>
                <div class="row g-1">
                    @foreach ($data_totxau['xau'] as $data_xau)
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body text-center">
                                  <a href="{{route('lich.nam.ngay', ['nam' => $data_xau['yy'], 'thang' => $data_xau['mm'], 'ngay' => $data_xau['dd']])}}">
                                      Ngày {{ $data_xau['dd'] }} Tháng {{ $data_xau['mm'] }} Năm {{ $data_xau['yy'] }}
                                  </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

            </div>
        </div>
        <div class="row g-3">
            <h5 class="pt-3 pb-1">Ngày Xấu tháng </h5>
            @foreach ($le_lichs as $le_lich)
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body text-start">
                            {{ $le_lich['dd'] }}-{{ $le_lich['mm'] }}: {{ $le_lich['name'] }}
                        </div>
                    </div>
                </div>
            @endforeach

        </div>
        <div class="row g-3">
            <h5 class="pt-3 pb-1">Ngày Xấu tháng </h5>
            @foreach ($su_kiens as $su_kien)
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body text-start">
                            {{ $su_kien }}
                        </div>
                    </div>
                </div>
            @endforeach

        </div>
        <h5 class="pt-3 pb-1">Ngày xuất hành âm lịch</h5>
        <div class="row g-2">
            @foreach ($data_al as $ngay)
                <div class="col-lg-12 mb-2">
                    <div class="card">
                        <div class="card-body">

                            <p>{{ $ngay['day'] }}/{{ $ngay['month'] }}:

                                @if (!empty($ngay['xuat_hanh_html']))
                                    {!! $ngay['xuat_hanh_html'] !!}
                                @endif
                            </p>

                        </div>
                    </div>
                </div>
            @endforeach

        </div>


<style>
    .active{
        background: pink
    }
</style>
        <div>
            <h5 class="pt-3 pb-1">Xem lịch âm các tháng khác</h5>
            <div class="row g-2">
                @for ($i = 1; $i <= 12; $i++)
                    <div class="col-lg-6">
                        <a href="{{ route('lich.thang', ['nam' => $yy, 'thang' => $i]) }}" class="">
                            <div class="card {{$mm == $i ? 'active' : ''}}">
                                <div class="card-body text-center">
                                    Lịch âm tháng {{ $i }} năm {{ $yy }}
                                </div>
                            </div>
                        </a>
                    </div>
                @endfor
            </div>

        </div>
    </div>
@endsection
