@extends('welcome')
@section('content')
    <div class="">
        <h1 class="p-title">Xem ngày tốt xấu tháng {{ $mm }} năm {{ $yy }}</h1>

        {{-- <h5>Tháng {{ $can_chi_thang }}</h5> --}}
        <div class="h5">
            {{ $desrtipton_thang['name'] }} <br>

            {{-- {{$desrtipton_thang[]}} --}}
        </div>
        <p style="font-size: 18px">
            - {{ $desrtipton_thang['weather'] }} <br>
            - {{ $desrtipton_thang['symbolism'] }}
        </p>
        <ol class="breadcrumb">
            <li itemprop="itemListElement"><a itemprop="item" href="/"> <span itemprop="name">Lịch âm</span> </a>
                <meta itemprop="position" content="1">
            </li>
            <li itemprop="itemListElement"><a itemprop="item" class="text-danger"> <span itemprop="name">Năm
                        {{ $yy }}</span> </a>
                <meta itemprop="position" content="2">
            </li>
            <li itemprop="itemListElement"><a itemprop="item" class="text-danger"> <span itemprop="name">Tháng
                        {{ $mm }}</span> </a>
                <meta itemprop="position" content="3">
            </li>
        </ol>
        <div class="hd-day pb-3"><span class="dh-gr"> <span class="dh-tot">●</span> <span>Ngày Hoàng Đạo</span> </span>
            <span class="dh-gr"> <span class="dh-xau">●</span> <span>Ngày Hắc Đạo</span> </span>
        </div>
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

        <div>
            <h2 class="p-title">Ngày tốt trong tháng {{ $mm }} năm {{ $yy }}</h2>
            <div class="row g-2 d-flex justify-content-center">
                @foreach ($data_totxau['tot'] as $data_tot)
                    <div class="col-lg-6">
                        <div class="card">
                            <a href="{{ route('lich.nam.ngay', ['nam' => $data_tot['yy'], 'thang' => $data_tot['mm'], 'ngay' => $data_tot['dd']]) }}"
                                class="ngay-item-lich-nam-thang">
                                <div class="card-body text-center">

                                    Ngày {{ $data_tot['dd'] }} Tháng {{ $data_tot['mm'] }} Năm {{ $data_tot['yy'] }}

                                </div>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <div>
            <h2 class="p-title">Ngày xấu trong tháng {{ $mm }} năm {{ $yy }}</h2>
            <div class="row g-2 d-flex justify-content-center">
                @foreach ($data_totxau['xau'] as $data_xau)
                    <div class="col-lg-6">

                        <div class="card">
                            <a href="{{ route('lich.nam.ngay', ['nam' => $data_xau['yy'], 'thang' => $data_xau['mm'], 'ngay' => $data_xau['dd']]) }}"
                                class="ngay-item-lich-nam-thang">
                                <div class="card-body text-center">

                                    Ngày {{ $data_xau['dd'] }} Tháng {{ $data_xau['mm'] }} Năm {{ $data_xau['yy'] }}

                                </div>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="row g-2 pt-2">
            <h2 class="p-title">Ngày lễ trong tháng {{ $mm }} năm {{ $yy }}</h2>
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
          <style>
            .active {
                background: pink
            }
        </style>
        <div>
          <h2 class="p-title">Lịch âm các tháng năm {{ $yy }}</h2>
            <div class="row g-2">
                @for ($i = 1; $i <= 12; $i++)
                    <div class="col-lg-6">
                        <a href="{{ route('lich.thang', ['nam' => $yy, 'thang' => $i]) }}" class="">
                            <div class="card {{ $mm == $i ? 'active' : '' }}">
                                <div class="card-body text-center">
                                    Lịch âm tháng {{ $i }} năm {{ $yy }}
                                </div>
                            </div>
                        </a>
                    </div>
                @endfor
            </div>

        </div>
        <h2 class="p-title">Ngày xuất hành âm lịch tốt trong tháng {{ $mm }} năm {{ $yy }}</h2>

        <div class="row g-1">
            @foreach ($data_al as $ngay)
                <div class="col-lg-12">
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



    </div>
@endsection
