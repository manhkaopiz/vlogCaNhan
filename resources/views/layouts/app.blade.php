<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'My Blog')</title>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="/template/admin/plugins/fontawesome-free/css/all.min.css">

    <!-- Google Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="/template/admin/css/app.css">

    <!-- Theme style -->
    <link rel="stylesheet" href="/template/admin/dist/css/adminlte.min.css">

    <!-- Weather Script -->
    <script>
        $(document).ready(function() {
            const apiKey = 'f98b352b2d23b0f4e8b4f5f332bd6f05'; // Thay thế bằng API key của bạn
            const cities = ['Ho Chi Minh', 'Hanoi', 'Da Nang', 'Can Tho', 'Hung Yen'];
            const apiUrl = 'https://api.openweathermap.org/data/2.5/weather?q=';
            let currentIndex = 0;

            function getWeatherIcon(description) {
                console.log('Weather description:', description); // Kiểm tra giá trị mô tả thời tiết
                switch (description.toLowerCase()) {
                    case 'trời quang đãng': return '☀️'; // Nắng
                    case 'mây thưa': return '🌤️'; // Ít mây
                    case 'bầu trời quang đãng': return '🌤️'; // Ít mây
                    case 'mây rải rác': return '⛅'; // Mây rải rác
                    case 'mây đen u ám': return '⛅'; // Mây rải rác
                    case 'mây dày': return '☁️'; // Mây đứt quãng
                    case 'mưa rào': return '🌦️'; // Mưa rào
                    case 'mưa vừa': return '🌧️'; // Mưa
                    case 'mưa cường độ nặng': return '🌧️'; // Mưa
                    case 'dông bão': return '⛈️'; // Bão
                    case 'tuyết': return '❄️'; // Tuyết
                    case 'sương mù': return '🌫️'; // Sương mù
                    case 'mưa bụi': return '🌦️'; // Mưa bụi
                    case 'mây cụm': return '🌫️'; // Khói
                    default: return '❓'; // Khác
                }
            }

            function displayWeather(city) {
                $.get(`${apiUrl}${city}&units=metric&appid=${apiKey}&lang=vi`, function(data) {
                    const icon = getWeatherIcon(data.weather[0].description);
                    const weatherHtml = `
                        <div class="weather-city">
                            <div class="weather-info">
                                <h4>${data.name}, ${data.sys.country}</h4>
                                <div class="weather-icon">${icon}</div>
                                <p>Nhiệt độ: ${data.main.temp}°C</p>
<!--                                <p>Thời tiết: ${data.weather[0].description}</p>-->
                            </div>
                        </div>
                    `;
                    $('#weather').html(weatherHtml);
                }).fail(function() {
                    $('#weather').html(`<p>Không thể lấy dữ liệu thời tiết cho ${city}</p>`);
                });
            }

            function rotateCities() {
                displayWeather(cities[currentIndex]);
                currentIndex = (currentIndex + 1) % cities.length;
            }

            rotateCities(); // Hiển thị thành phố đầu tiên ngay khi trang tải
            setInterval(rotateCities, 5000); // Thay đổi thành phố sau mỗi 5 giây
        });
    </script>

    <style>
        #weather {
            margin-top: 10px;
            padding-right: 10px;

            background-color: #f7f7f7;
            border-radius: 8px;
            text-align: center;
        }
        .weather-city {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: row;
            margin-bottom: 20px;
        }
        .weather-info {
            display: flex;
            align-items: center;
            flex-direction: row;
        }
        .weather-info h4 {
            margin-right: 15px;
        }
        .weather-icon {
            font-size: 2rem;
            margin-right: 15px;
        }
        .weather-info p {
            margin: 0;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="{{ route('posts.index') }}">My Blog</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div id="weather">
        <h3>Dự Báo Thời Tiết</h3>
        <!-- Thông tin thời tiết cho từng thành phố sẽ được thêm vào đây -->
    </div>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
            @if (Auth::guard('customer')->check())
                <h3>Hello, {{ $profile->name ?: $customer->name }}</h3>
                <a class="nav-link" href="{{ route('profile.show') }}">
                    <img src="{{ asset('storage/' . ($profile->avatar ?: 'avatars/default-avatar.png')) }}" alt="Avatar" class="avatar-img">
                </a>
                <a class="nav-link" href="{{ route('logout') }}"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    Logout
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            @else
                <a class="nav-link" href="{{ route('login') }}">Login</a>
                <a class="nav-link" href="{{ route('register') }}">Register</a>
            @endif
        </ul>
    </div>
</nav>

<div class="container mt-5">

    @yield('content')
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
