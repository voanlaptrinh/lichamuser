@extends('welcome')

@section('content')

    <div class="card shadow-sm">
        <div class="card-header bg-dark text-white">
            <h1 class="h3 mb-0">{{ $vanKhan['title'] }}</h1>
        </div>
        <div class="card-body">
            @if ($vanKhan['description'])
                <p class="card-text text-muted">{{ $vanKhan['description'] }}</p>
            @endif

            @if (!empty($vanKhan['categories']))
                <p>
                    <strong>Chủ đề:</strong>
                    @foreach ($vanKhan['categories'] as $category)
                        <span class="badge bg-info">{{ $category }}</span>
                    @endforeach
                </p>
            @endif
            <hr>

            {{-- PHẦN CẬP NHẬT: HIỂN THỊ DẠNG SELECT NẾU CÓ NHIỀU MẪU --}}
            @if (isset($vanKhan['templates']) && count($vanKhan['templates']) > 0)

                {{-- Nếu có nhiều hơn 1 mẫu, hiển thị dropdown --}}
                @if (count($vanKhan['templates']) > 1)
                    <div class="mb-3">
                        <label for="template-selector" class="form-label fw-bold">Chọn mẫu văn khấn:</label>
                        <select class="form-select" id="template-selector">
                            @foreach ($vanKhan['templates'] as $template)
                                <option value="{{ $loop->index }}" {{ $loop->first ? 'selected' : '' }}>
                                    {{ $template['title'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif

                {{-- Vùng hiển thị nội dung của mẫu được chọn --}}
                <div id="template-content" class="content-markdown mb-4">
                    {{-- Tải nội dung của mẫu đầu tiên làm mặc định --}}
                    {!! $vanKhan['templates'][0]['content_html'] !!}
                </div>

            @endif
            {{-- KẾT THÚC PHẦN CẬP NHẬT --}}


            <!-- Hiển thị Lễ vật -->
            @if ($vanKhan['offerings'])
                <div class="card border-success mt-4">
                    <div class="card-header bg-success text-white"><strong>🎁 Lễ Vật Cần Chuẩn Bị</strong></div>
                    <div class="card-body content-markdown">
                        {!! $vanKhan['offerings_html'] !!}
                    </div>
                </div>
            @endif

            <!-- Hiển thị Hướng dẫn -->
            @if ($vanKhan['instructions'])
                <div class="card border-primary mt-4">
                    <div class="card-header bg-primary text-white"><strong>🧭 Hướng Dẫn Thực Hiện</strong></div>
                    <div class="card-body content-markdown">
                        {!! $vanKhan['instructions_html'] !!}
                    </div>
                </div>
            @endif
        </div>
        <div class="card-footer text-muted">
            Cập nhật lần cuối: {{ \Carbon\Carbon::parse($vanKhan['updatedAt'])->format('d/m/Y H:i') }}
        </div>
    </div>
    @if (isset($vanKhan['templates']) && count($vanKhan['templates']) > 1)
        <script>
            const templatesData = @json($vanKhan['templates']);

            // Lấy các element từ DOM
            const templateSelector = document.getElementById('template-selector');
            const templateContent = document.getElementById('template-content');

            // Lắng nghe sự kiện 'change' trên dropdown
            templateSelector.addEventListener('change', function() {
                // 'this.value' sẽ là index của mẫu được chọn (vd: 0, 1, 2)
                const selectedIndex = this.value;

                // Lấy nội dung HTML tương ứng từ mảng dữ liệu
                const newContent = templatesData[selectedIndex].content_html;

                // Cập nhật nội dung cho div hiển thị
                templateContent.innerHTML = newContent;
            });
        </script>
    @endif
@endsection
