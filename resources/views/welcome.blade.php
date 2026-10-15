<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CV Jayagiry - Inventory System</title>

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- FontAwesome (Ikon) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Custom CSS Laravel Asset -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="icon" href="{{ asset('assets/images/favicon.png') }}" type="image/png">
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar">
        <div class="nav-container">
            <div class="nav-brand">
                <img src="{{ asset('assets/images/logo.png') }}" alt="Logo CV Jayagiry" class="logo">
                <div class="brand-text">
                    <h1>CV JAYAGIRY</h1>
                    <span>SIMANGU</span>
                </div>
            </div>
            <a href="{{ route('login') }}" class="btn-primary" style="text-decoration:none">
                <i class="fa-solid fa-right-to-bracket"></i> Masuk Admin / Staff Gudang
            </a>
        </div>
    </nav>

    <!-- Hero Section -->
    <header class="hero">
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <span class="hero-label">INTERNAL INVENTORY MANAGEMENT SYSTEM</span>
            <h2 class="hero-title">Pencatatan Stok Gudang <br> CV Jayagiry</h2>
            <p class="hero-subtitle">Catat barang masuk dan keluar, lalu lihat sisa stok setiap barang dalam satu sistem yang sederhana dan mudah dipakai.</p>

            <div class="hero-actions">
                <a href="#steps" class="btn-primary" style="text-decoration:none">
                  <i class="fa-solid fa-circle-question"></i> How To Use It
                </a>
                <a href="#features" class="btn-secondary">Jelajahi Fitur</a>
            </div>

            <div class="hero-highlights">
                <span><i class="fa-solid fa-check-circle"></i> Monitoring Stok</span>
                <span><i class="fa-solid fa-check-circle"></i> Pencatatan Barang Masuk &amp; Keluar</span>
                <span><i class="fa-solid fa-check-circle"></i> Riwayat Transaksi</span>
            </div>
        </div>
    </header>

    <!-- Features Section -->
    <section id="features" class="features">
        <div class="section-header">
            <h2>Kelola Gudang Lebih Mudah dan Rapi</h2>
            <p>Fitur yang dibutuhkan sehari-hari di gudang, tanpa menu yang tidak terpakai.</p>
        </div>

        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fa-solid fa-box"></i>
                </div>
                <h3>Monitoring Stok</h3>
                <p>Lihat daftar semua barang beserta sisa stoknya, dan dapatkan tanda untuk barang yang hampir habis.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fa-solid fa-arrow-right-arrow-left"></i>
                </div>
                <h3>Arus Barang</h3>
                <p>Catat setiap barang masuk dan keluar. Stok bertambah atau berkurang otomatis sesuai transaksi.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                </div>
                <h3>Riwayat Transaksi</h3>
                <p>Telusuri kapan suatu barang masuk atau keluar, berapa jumlahnya, dan siapa yang mencatat.</p>
            </div>
        </div>
    </section>

    <!-- Steps Section -->
    <section id="steps" class="steps">
        <div class="section-header">
            <h2>Mulai Kelola Inventory dengan Mudah</h2>
        </div>
        <div class="steps-container">
            <div class="step-item">
                <div class="step-number">1</div>
                <h4>Login ke Sistem</h4>
                <p>Pengguna masuk menggunakan akun yang telah diberikan oleh administrator.</p>
            </div>
            <div class="step-item">
                <div class="step-number">2</div>
                <h4>Catat Barang Masuk dan Keluar</h4>
                <p>Staff gudang mencatat transaksi, sedangkan admin juga dapat mengelola data barang.</p>
            </div>
            <div class="step-item">
                <div class="step-number">3</div>
                <h4>Pantau Stok</h4>
                <p>Pengguna melihat sisa stok dan riwayat transaksi di dashboard dan tabel barang.</p>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-container">
            <div class="footer-brand">
                <h3>CV Jayagiry</h3>
                <p>Inventory System &mdash; Informasi Pengelolaan Gudang Internal.</p>
            </div>
            <div class="footer-contact">
                <p><i class="fa-solid fa-location-dot"></i> Jl. Siliwangi, Padaasih, Kec. Cisaat, Kabupaten Sukabumi, Jawa Barat 43156.</p>
                <p><i class="fa-solid fa-phone"></i> <a href="tel:+628111114109">0811-1114-109</a></p>
            </div>
        </div>
        <div class="footer-bottom">
            &copy; 2026 CV Jayagiry. Internal System.
        </div>
    </footer>

</body>
</html>