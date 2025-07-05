@extends('welcome')

@section('content')
    <h1>Khám Phá 12 Cung Hoàng Đạo</h1>
    <div class="row g-3">
        @foreach ($zodiacs as $sign => $details)
            <div class="col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('horoscope.show', ['sign' => $sign]) }}" class="zodiac-card">
                            <img src="{{ $details['icon'] }}" alt="{{ $details['name'] }}" class="img-fluid">
                            <div class="name text-center">{{ $details['name'] }}</div>
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
