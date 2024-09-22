<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Informasi Stok Darah & Donasi Darah</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #e53935;
            --secondary-color: #ff6b6b;
            --accent-color: #ff9e80;
            --light-color: #ffffff;
            --dark-color: #37474f;
        }

        body {
            font-family: 'Poppins', sans-serif;
            color: var(--dark-color);
            background-color: var(--light-color);
        }

        .navbar {
            background-color: var(--light-color);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 0.5rem 0;
            height: 70px;
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            height: 100%;
        }

        .navbar-brand .logo {
            max-height: 50px;
            width: auto;
            margin-right: 10px;
        }

        .navbar-brand .navbar-text {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-color);
        }

        .navbar-toggler {
            border: none;
            background-color: transparent;
        }

        .navbar-toggler-icon {
            background-image: url('data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"%3E%3Cpath fill="none" stroke="%23000000" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"/%3E%3C/svg%3E');
        }

        .nav-link {
            color: var(--primary-color) !important;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        .nav-link:hover {
            color: var(--accent-color) !important;
        }

        .btn-login {
            background-color: var(--secondary-color);
            color: var(--light-color);
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-login:hover {
            background-color: var(--primary-color);
            color: var(--light-color);
        }

        .hero-section {
            background-color: var(--primary-color);
            color: white;
            padding: 100px 0;
            clip-path: polygon(0 0, 100% 0, 100% 85%, 0 100%);
        }

        .section-title {
            position: relative;
            display: inline-block;
            margin-bottom: 30px;
            color: var(--primary-color);
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 0;
            width: 50px;
            height: 3px;
            background-color: var(--accent-color);
            transition: width 0.3s ease;
        }

        .section-title:hover::after {
            width: 100%;
        }

        .blood-stock-card {
            border: none;
            border-radius: 15px;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            background-color: white;
        }

        .blood-stock-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(178, 34, 34, 0.1);
        }

        .stock-icon {
            font-size: 2.5rem;
            color: var(--primary-color);
            transition: transform 0.3s ease;
        }

        .blood-stock-card:hover .stock-icon {
            transform: scale(1.1);
        }

        .feature-icon {
            font-size: 3rem;
            color: var(--accent-color);
            transition: transform 0.3s ease;
        }

        .feature-card:hover .feature-icon {
            transform: scale(1.1) rotate(5deg);
        }

        .cta-section {
            background-color: var(--secondary-color);
            color: white;
            padding: 80px 0;
            clip-path: polygon(0 15%, 100% 0, 100% 85%, 0 100%);
        }

        .btn-custom {
            background-color: var(--accent-color);
            border: none;
            color: white;
            padding: 10px 30px;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-custom:hover {
            background-color: var(--primary-color);
            color: white;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
        }

        .footer {
            background-color: var(--dark-color);
            color: white;
            padding: 50px 0;
        }

        .social-icon {
            color: var(--light-color);
            font-size: 1.5rem;
            margin: 0 10px;
            transition: color 0.3s ease, transform 0.3s ease;
        }

        .social-icon:hover {
            color: var(--accent-color);
            transform: scale(1.2);
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }

            100% {
                transform: scale(1);
            }
        }

        .pulse-animation {
            animation: pulse 2s infinite;
        }

        #map {
            height: 400px;
            width: 100%;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#">
                <img src="<?= base_url('assets/images/logo-only.png') ?>" alt="Logo" class="logo">
                <span class="navbar-text">SiDarah</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item"><a class="nav-link" href="#home">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link" href="#stock">Stok Darah</a></li>
                    <li class="nav-item"><a class="nav-link" href="#about">Tentang</a></li>
                    <li class="nav-item"><a class="nav-link" href="#features">Fitur</a></li>
                    <li class="nav-item"><a class="nav-link" href="#request-blood">Pengajuan Stok</a></li>
                    <li class="nav-item ms-3"><a class="btn btn-login" href="<?= base_url('login') ?>">Login</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <section id="home" class="hero-section">
        <div class="container text-center">
            <h1 class="display-4 fw-bold mb-4">Selamatkan Nyawa dengan Donasi Darah</h1>
            <p class="lead mb-5">Sistem informasi terpadu untuk stok darah dan donasi darah</p>
            <a href="<?= base_url('donor-darah') ?>" class="btn btn-custom btn-lg">Mulai Donasi</a>
        </div>
    </section>

    <section id="stock" class="py-5">
        <div class="container">
            <h2 class="text-center section-title">Stok Darah Terkini</h2>
            <div class="row mt-5">
                <div class="col-md-3 mb-4">
                    <div class="card blood-stock-card text-center">
                        <div class="card-body">
                            <i class="fas fa-tint stock-icon mb-3"></i>
                            <h3 class="card-title">A</h3>
                            <p class="card-text display-6 fw-bold">120</p>
                            <p class="text-muted">Kantong</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="card blood-stock-card text-center">
                        <div class="card-body">
                            <i class="fas fa-tint stock-icon mb-3"></i>
                            <h3 class="card-title">B</h3>
                            <p class="card-text display-6 fw-bold">85</p>
                            <p class="text-muted">Kantong</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="card blood-stock-card text-center">
                        <div class="card-body">
                            <i class="fas fa-tint stock-icon mb-3"></i>
                            <h3 class="card-title">AB</h3>
                            <p class="card-text display-6 fw-bold">30</p>
                            <p class="text-muted">Kantong</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="card blood-stock-card text-center">
                        <div class="card-body">
                            <i class="fas fa-tint stock-icon mb-3"></i>
                            <h3 class="card-title">O</h3>
                            <p class="card-text display-6 fw-bold">150</p>
                            <p class="text-muted">Kantong</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-5">
                <div class="col-lg-8 mx-auto">
                    <h3 class="text-center mb-4">Grafik Donasi Darah Bulanan</h3>
                    <canvas id="bloodDonationChart"></canvas>
                </div>
            </div>
        </div>
    </section>

    <section id="about" class="py-5 bg-white">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <h2 class="section-title">Tentang Kami</h2>
                    <p>SiDarah adalah platform inovatif yang menghubungkan pendonor darah dengan mereka yang membutuhkan. Kami berkomitmen untuk meningkatkan aksesibilitas dan efisiensi dalam proses donasi darah.</p>
                    <p>Dengan menggunakan teknologi terkini, kami menyediakan informasi real-time tentang stok darah dan memfasilitasi proses donasi yang mudah dan cepat.</p>
                    <a href="#" class="btn btn-custom mt-3">Pelajari Lebih Lanjut</a>
                </div>
                <div class="col-lg-6">
                    <img src="<?= base_url('assets/images/content-2.jpg') ?>" alt="Tentang Kami" class="img-fluid rounded-3 shadow">
                </div>
            </div>
        </div>
    </section>

    <section id="features" class="py-5">
        <div class="container">
            <h2 class="text-center section-title">Fitur Utama</h2>
            <div class="row mt-5">
                <div class="col-md-4 mb-4">
                    <div class="card feature-card h-100 border-0 shadow-sm">
                        <div class="card-body text-center">
                            <i class="fas fa-search feature-icon mb-4"></i>
                            <h4 class="card-title">Cek Stok Darah</h4>
                            <p class="card-text">Lihat ketersediaan stok darah secara real-time di berbagai lokasi.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card feature-card h-100 border-0 shadow-sm">
                        <div class="card-body text-center">
                            <i class="fas fa-calendar-alt feature-icon mb-4"></i>
                            <h4 class="card-title">Jadwalkan Donasi</h4>
                            <p class="card-text">Atur jadwal donasi darah Anda dengan mudah melalui platform kami.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card feature-card h-100 border-0 shadow-sm">
                        <div class="card-body text-center">
                            <i class="fas fa-map-marker-alt feature-icon mb-4"></i>
                            <h4 class="card-title">Lokasi Donor</h4>
                            <p class="card-text">Temukan lokasi donor darah terdekat dengan mudah.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="cta-section">
        <div class="container text-center">
            <h2 class="mb-4">Siap untuk Menyelamatkan Nyawa?</h2>
            <p class="lead mb-4">Bergabunglah dengan komunitas donor darah kami dan jadilah pahlawan bagi mereka yang membutuhkan.</p>
            <a href="<?= base_url('donor-darah') ?>" class="btn btn-custom btn-lg">Daftar Sekarang</a>
        </div>
    </section>

    <section id="hospital-map" class="py-5 bg-white">
        <div class="container">
            <h2 class="text-center section-title">Lokasi Rumah Sakit</h2>
            <div class="row mt-5">
                <div class="col-md-12">
                    <div id="map"></div>
                </div>
            </div>
        </div>
    </section>

    <section id="request-blood" class="py-5 bg-white">
        <div class="container">
            <h2 class="text-center section-title">Pengajuan Stok Darah</h2>
            <div class="row mt-5">
                <div class="col-md-6 mb-4 mb-md-0">
                    <form id="bloodRequestForm">
                        <div class="mb-3">
                            <input type="text" class="form-control" placeholder="Nama Lengkap" required>
                        </div>
                        <div class="mb-3">
                            <input type="email" class="form-control" placeholder="Email" required>
                        </div>
                        <div class="mb-3">
                            <input type="tel" class="form-control" placeholder="Nomor Telepon" required>
                        </div>
                        <div class="mb-3">
                            <select class="form-select" required>
                                <option value="">Pilih Golongan Darah</option>
                                <option value="A">A</option>
                                <option value="B">B</option>
                                <option value="AB">AB</option>
                                <option value="O">O</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <input type="number" class="form-control" placeholder="Jumlah Kantong Darah" required>
                        </div>
                        <div class="mb-3">
                            <textarea class="form-control" rows="5" placeholder="Alasan Pengajuan" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-custom">Ajukan Permintaan</button>
                    </form>
                </div>
                <div class="col-md-6">
                    <img src="<?= base_url('assets/images/content-1.jpg') ?>" alt="Pengajuan Stok Darah" class="img-fluid rounded-3 shadow">
                </div>
            </div>
        </div>
    </section>

    <footer class="footer">
        <div class="container text-center">
            <p>&copy; 2024 SiDarah. Hak Cipta Dilindungi.</p>
            <div class="mt-3">
                <a href="#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                <a href="#" class="social-icon"><i class="fab fa-twitter"></i></a>
                <a href="#" class="social-icon"><i class="fab fa-instagram"></i></a>
                <a href="#" class="social-icon"><i class="fab fa-linkedin-in"></i></a>
            </div>
        </div>
    </footer>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&callback=initMap" async defer></script>
    <script>
        // Chart.js configuration
        var ctx = document.getElementById('bloodDonationChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [{
                    label: 'Jumlah Donasi',
                    data: [65, 59, 80, 81, 56, 55, 40, 45, 50, 55, 60, 70],
                    fill: false,
                    borderColor: 'rgb(229, 57, 53)',
                    backgroundColor: 'rgba(229, 57, 53, 0.1)',
                    borderWidth: 3,
                    tension: 0.1,
                    pointBackgroundColor: 'rgb(255, 107, 107)',
                    pointBorderColor: 'rgb(55, 71, 79)',
                    pointRadius: 5,
                    pointHoverRadius: 7,
                    borderDash: [5, 5],
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(255, 158, 128, 0.2)',
                        },
                        ticks: {
                            color: 'rgb(55, 71, 79)',
                        }
                    },
                    x: {
                        grid: {
                            color: 'rgba(255, 158, 128, 0.2)',
                        },
                        ticks: {
                            color: 'rgb(55, 71, 79)',
                        }
                    }
                },
                plugins: {
                    legend: {
                        labels: {
                            color: 'rgb(55, 71, 79)',
                        }
                    }
                }
            }
        });

        // Google Maps configuration
        function initMap() {
            var rsHerminaMakassar = {
                lat: -5.1679,
                lng: 119.4609
            }; // Coordinates for RS Hermina Makassar
            var map = new google.maps.Map(document.getElementById('map'), {
                zoom: 15,
                center: rsHerminaMakassar
            });

            var marker = new google.maps.Marker({
                position: rsHerminaMakassar,
                map: map,
                title: 'RS Hermina Makassar'
            });

            var infoWindow = new google.maps.InfoWindow({
                content: 'RS Hermina Makassar<br>Jl. Toddopuli Raya Timur No.7, Borong, Kec. Manggala, Kota Makassar, Sulawesi Selatan 90231'
            });

            marker.addListener('click', function() {
                infoWindow.open(map, marker);
            });
        }

        // Form submission handler
        document.getElementById('bloodRequestForm').addEventListener('submit', function(e) {
            e.preventDefault();
            alert('Permintaan stok darah telah dikirim. Kami akan menghubungi Anda segera.');
            this.reset();
        });

        // Scroll animation
        window.addEventListener('scroll', function() {
            var elements = document.querySelectorAll('.blood-stock-card, .feature-card, .btn-custom');
            elements.forEach(function(element) {
                if (isElementInViewport(element)) {
                    element.classList.add('pulse-animation');
                } else {
                    element.classList.remove('pulse-animation');
                }
            });
        });

        function isElementInViewport(el) {
            var rect = el.getBoundingClientRect();
            return (
                rect.top >= 0 &&
                rect.left >= 0 &&
                rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
                rect.right <= (window.innerWidth || document.documentElement.clientWidth)
            );
        }
    </script>
</body>

</html>