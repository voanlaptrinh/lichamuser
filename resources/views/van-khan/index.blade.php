@extends('welcome')

@section('content')
    <div class="p-4 p-md-5 mb-4 text-white rounded bg-dark">
        <div class="col-md-8 px-0">
            <h1 class="display-4 fst-italic">Tuyển tập Văn khấn Cổ truyền Việt Nam</h1>
            <p class="lead my-3">Tổng hợp các bài văn khấn cho mọi dịp lễ, cúng trong năm, giúp bạn thực hiện các nghi lễ một cách trang trọng và đúng đắn.</p>
        </div>
    </div>

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="row">
        <div class="col-md-12">
            <h3 class="pb-4 mb-4 fst-italic border-bottom">
                Tất cả bài văn khấn
            </h3>
            
            <ul class="list-group">
                @forelse ($vanKhans as $item)
                    <a href="{{ route('van-khan.show', ['id' => $item['id']]) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-start">
                        <div class="ms-2 me-auto">
                            <div class="fw-bold">{{ $item['title'] }}</div>
                            {{ $item['description'] }}
                            @if (!empty($item['categories']))
                                <br>
                                <small class="text-muted">Chủ đề: {{ implode(', ', $item['categories']) }}</small>
                            @endif
                        </div>
                        <span class="badge bg-primary rounded-pill">Xem chi tiết</span>
                    </a>
                @empty
                    <li class="list-group-item">
                        <p class="text-center text-muted">Không tìm thấy dữ liệu hoặc không thể kết nối tới API.</p>
                    </li>
                @endforelse
            </ul>
        </div>
    </div>
@endsection
