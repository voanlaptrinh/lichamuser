@extends('welcome')
@section('content')
    <div class="">
        <h1 class="p-title">Xem lịch âm năm {{ $nam }} {{ $can_chi_nam }}</h1>
        <div class="date-range">
            <p>
                Năm Âm lịch <strong>{{ $can_chi_nam }} {{ $nam }}</strong> bắt đầu từ:
                <strong style="color: #28a745;">{{ $startDateFormatted }}</strong> (Dương lịch).
            </p>
            <p>
                Và kết thúc vào:
                <strong style="color: #dc3545;">{{ $endDateFormatted }}</strong> (Dương lịch).
            </p>
            <p style="font-size: 18px">
                {{ $moTa }}
            </p>
        </div>
        <div class="l-y-btop">
            @php
                use App\Helpers\LunarHelper;
            @endphp
            @php($currentYearHeader2 = $nam)
            @php($startYearHeader2 = $currentYearHeader2 - 1)
            @php($endYearHeader2 = $currentYearHeader2 + 10)
            <ul class="tag-sidebar">
                @for ($year = $startYearHeader2; $year <= $endYearHeader2; $year++)
                    <li>
                        <a href="{{ route('lich.nam', ['nam' => $year]) }}">
                            #Lịch âm {{ $year }}
                        </a>
                    </li>
                @endfor

            </ul>
            <div style="clear:both"></div>
        </div>
        <ol class="breadcrumb" itemscope="">
            <li><a itemprop="item" href="/"> <span itemprop="name">Lịch âm</span> </a>
                <meta itemprop="position" content="1">
            </li>
            <li><a itemprop="item" href=""> <span itemprop="name">Năm {{ $nam }}</span> </a>
                <meta itemprop="position" content="2">
            </li>
        </ol>
        <h2 class="p-title">Chi tiết lịch âm năm {{ $nam }} theo tháng </h2>
        <div class="hd-day"><span class="dh-gr"> <span class="dh-tot">●</span> <span>Ngày Hoàng Đạo</span> </span> <span
                class="dh-gr"> <span class="dh-xau">●</span> <span>Ngày Hắc Đạo</span> </span></div>

        <div class="">
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
                                    <th><span class="title-lich-pc">Thứ hai</span> <span class="title-lich-mobie">Th
                                            2</span>
                                    </th>
                                    <th><span class="title-lich-pc">Thứ ba</span> <span class="title-lich-mobie">Th 3</span>
                                    </th>
                                    <th><span class="title-lich-pc">Thứ tư</span> <span class="title-lich-mobie">Th 4</span>
                                    </th>
                                    <th><span class="title-lich-pc">Thứ năm</span> <span class="title-lich-mobie">Th
                                            5</span>
                                    </th>
                                    <th><span class="title-lich-pc">Thứ sau</span> <span class="title-lich-mobie">Th
                                            6</span>
                                    </th>
                                    <th><span class="title-lich-pc">Thứ bảy</span> <span class="title-lich-mobie">Th
                                            7</span>
                                    </th>
                                    <th><span class="title-lich-pc">Chủ nhật</span> <span class="title-lich-mobie">CN</span>
                                    </th>

                                </tr>
                            </thead>
                            <tbody>
                                {!! LunarHelper::printTable($i, $nam, true) !!}
                            </tbody>
                        </table>

                    </div>
                </div>
                <br>
            @endfor
            <div>
               <h2 class="p-title">Ngày lễ dương lịch trong năm {{$nam}}</h2>
                {!! $sukienduong !!}
            </div>
            <div>
                 <h2 class="p-title">Ngày lễ âm lịch trong năm {{$nam}}</h2>
                {!! $sukienam !!}
            </div>
        </div>
    </div>
@endsection
