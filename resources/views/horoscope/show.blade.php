{{-- Giả sử layout của bạn là 'welcome.blade.php' --}}
@extends('welcome') 

@section('content')

    <h2 id="detail-title">Chi tiết Cung {{ $zodiac['name'] }}</h2>

    <div id="tabs" class="tabs">
        <button class="tab-button tab-horoscope" data-type="yesterday">Hôm qua</button>
        <button class="tab-button tab-horoscope active" data-type="today">Hôm nay</button>
        <button class="tab-button tab-horoscope" data-type="tomorrow">Ngày mai</button>
        <button class="tab-button tab-horoscope" data-type="weekly">Tuần này</button>
        <button class="tab-button tab-horoscope" data-type="monthly">Tháng này</button>
        <button class="tab-button tab-horoscope" data-type="yearly">Năm này</button>
    </div>

    <div id="horoscope-content" class="horoscope-content">
        {{-- Loader sẽ được thay thế bằng nội dung từ JavaScript --}}
        <div class="loader"></div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const tabsContainer = document.getElementById('tabs');
    const horoscopeContent = document.getElementById('horoscope-content');
    const currentSign = '{{ $zodiac['sign'] }}';

    // Hàm này rất gọn gàng vì tất cả logic xử lý đã ở trên server
    async function fetchHoroscope(type) {
        horoscopeContent.innerHTML = '<div class="loader"></div>';
        const apiUrl = `{{ url('/api/horoscope-data') }}/${currentSign}/${type}`;

        try {
            const response = await fetch(apiUrl);
            const data = await response.json();

            // Nếu request không thành công, hiển thị lỗi
            if (!response.ok) {
                // 'data.error' được trả về từ controller nếu có lỗi
                throw new Error(data.error || `Lỗi máy chủ: ${response.status}`);
            }

            // Dữ liệu trả về là {'html': '...'}
            // Chỉ cần lấy và hiển thị, không cần xử lý gì thêm
            if (data.html) {
                horoscopeContent.innerHTML = data.html;
            } else {
                throw new Error('Định dạng dữ liệu trả về không hợp lệ.');
            }

        } catch (error) {
            console.error('Không thể lấy dữ liệu:', error);
            horoscopeContent.innerHTML = `<p style="color: red; text-align: center;">${error.message}</p>`;
        }
    }

    // Phần xử lý click vào tab
    tabsContainer.addEventListener('click', (event) => {
        const selectedTab = event.target.closest('.tab-horoscope');
        if (!selectedTab) return;
        
        const type = selectedTab.dataset.type;

        tabsContainer.querySelector('.active')?.classList.remove('active');
        selectedTab.classList.add('active');

        fetchHoroscope(type);
    });

    // Tự động tải dữ liệu cho tab đang active khi trang vừa tải xong
    const initialActiveTab = tabsContainer.querySelector('.tab-horoscope.active');
    if (initialActiveTab) {
        fetchHoroscope(initialActiveTab.dataset.type);
    }
});
</script>
@endpush