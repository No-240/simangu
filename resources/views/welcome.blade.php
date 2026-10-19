<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CV Jayagiry - Inventory System</title>

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- FontAwesome (Ikon) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" href="{{ asset('assets/images/favicon.png') }}" type="image/png">

    <!-- Tailwind CSS (via CDN untuk implementasi langsung) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'ink': '#12301f',
                        'ink-soft': '#1b4a30',
                        'teal-main': '#1f8a4c',
                        'teal-dark': '#166a3a',
                        'teal-light': '#9be0b4',
                        'tint': '#e8f4ec',
                        'tint-steps': '#eaf1eb',
                        'paper': '#f7faf8',
                        'muted': '#5a6f62',
                        'line': '#cfdcd3',
                        'text-soft': '#b8d4c3',
                        'step-num': '#9abfa1',
                    },
                    fontFamily: {
                        sans: ['Inter', 'system-ui', 'sans-serif'],
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-paper text-ink font-sans leading-relaxed">

    <!-- Navbar -->
    <nav class="sticky top-0 z-50 bg-white border-b border-line">
        <div class="max-w-6xl mx-auto px-5 py-3 flex items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <img src="{{ asset('assets/images/logo.png') }}" alt="Logo CV Jayagiry" class="h-10 w-auto">
                <div>
                    <h1 class="text-[1.15rem] font-bold leading-tight">CV JAYAGIRY</h1>
                    <span class="text-xs text-muted block">SIMANGU</span>
                </div>
            </div>
            <a href="{{ route('login') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-teal-main text-white rounded-lg font-semibold text-sm transition-colors hover:bg-teal-dark border-2 border-transparent">
                <i class="fa-solid fa-right-to-bracket"></i> Login
            </a>
        </div>
    </nav>

    <!-- Hero Section (2 Kolom: Kiri Teks, Kanan Gambar) -->
    <header class="relative overflow-hidden bg-gradient-to-br from-ink to-ink-soft text-white">
        <!-- Overlay Pattern -->
        <div class="absolute inset-0 pointer-events-none bg-[repeating-linear-gradient(135deg,rgba(255,255,255,0.04)_0_2px,transparent_2px_28px)]"></div>
        
        <div class="relative max-w-7xl mx-auto px-5 pt-20 pb-24 grid grid-cols-1 lg:grid-cols-[1.2fr_1fr] items-center gap-12">
            
            <!-- Kolom Kiri: Teks -->
            <div class="text-center lg:text-left">
                <span class="inline-block text-xs font-semibold tracking-widest text-teal-light mb-4">INTERNAL INVENTORY MANAGEMENT SYSTEM</span>
                <h2 class="text-4xl md:text-5xl lg:text-[3.4rem] font-bold leading-[1.12] tracking-tight max-w-[18ch] mx-auto lg:mx-0">
                    Pencatatan Stok Gudang <br> CV Jayagiry
                </h2>
                <p class="mt-5 text-[1.05rem] text-text-soft max-w-[52ch] mx-auto lg:mx-0">
                    Solusi digital terpadu untuk manajemen inventaris. Kendalikan arus barang dan pantau ketersediaan stok melalui sistem yang efisien dan user-friendly.
                </p>

                <div class="flex flex-wrap justify-center lg:justify-start gap-3 mt-8">
                    <a href="#steps" class="inline-flex items-center gap-2 px-6 py-3 bg-teal-main text-white rounded-lg font-semibold text-[0.95rem] transition-colors hover:bg-teal-dark">
                        <i class="fa-solid fa-circle-question"></i> Cara Penggunaan
                    </a>
                    <a href="#features" class="inline-flex items-center gap-2 px-6 py-3 bg-transparent text-white border border-white/55 rounded-lg font-semibold text-[0.95rem] transition-colors hover:bg-white/10">
                        Jelajahi Fitur
                    </a>
                </div>

                <div class="flex flex-wrap justify-center lg:justify-start gap-x-6 gap-y-2 mt-9 text-sm text-text-soft">
                    <span><i class="fa-solid fa-check-circle text-teal-light mr-1.5"></i> Monitoring Stok</span>
                    <span><i class="fa-solid fa-check-circle text-teal-light mr-1.5"></i> Pencatatan Barang Masuk &amp; Keluar</span>
                    <span><i class="fa-solid fa-check-circle text-teal-light mr-1.5"></i> Riwayat Transaksi</span>
                </div>
            </div>

            <!-- Kolom Kanan: Gambar -->
            <div class="flex justify-center items-center row-start-1 lg:row-auto">
                <img src="{{ asset('img/dashboard.png') }}" alt="Ilustrasi Dasbor Gudang" class="w-full max-w-[500px] h-auto block drop-shadow-[0_20px_30px_rgba(0,0,0,0.15)]">
            </div>

        </div>
    </header>

    <!-- Features Section -->
    <section id="features" class="py-20 bg-paper">
        <div class="max-w-6xl mx-auto px-5 mb-12 flex flex-col items-center text-center">
            <h2 class="text-2xl md:text-[2.1rem] font-bold tracking-tight mb-2 text-teal-dark">Kelola Gudang Lebih Mudah dan Rapi</h2>
            <p class="text-muted max-w-[56ch]">Optimalkan operasional gudang dengan fitur tepat guna tanpa kerumitan.</p>
        </div>

        <div class="max-w-6xl mx-auto px-5 grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Card 1 -->
            <div class="bg-white rounded-[12px] p-10 shadow-[0_4px_15px_rgba(0,0,0,0.05)] transition-all duration-300 hover:-translate-y-1 hover:shadow-[0_10px_25px_rgba(0,0,0,0.08)] flex flex-col items-start">
                <div class="bg-transparent text-teal-dark text-[3rem] mb-6 block">
                    <i class="fa-solid fa-box"></i>
                </div>
                <h3 class="text-[1.15rem] font-bold mb-2.5 text-ink">Monitoring Stok</h3>
                <p class="text-muted text-[0.95rem]">Lihat daftar semua barang beserta sisa stoknya, dan dapatkan tanda untuk barang yang hampir habis.</p>
            </div>
            <!-- Card 2 -->
            <div class="bg-white rounded-[12px] p-10 shadow-[0_4px_15px_rgba(0,0,0,0.05)] transition-all duration-300 hover:-translate-y-1 hover:shadow-[0_10px_25px_rgba(0,0,0,0.08)] flex flex-col items-start">
                <div class="bg-transparent text-teal-dark text-[3rem] mb-6 block">
                    <i class="fa-solid fa-arrow-right-arrow-left"></i>
                </div>
                <h3 class="text-[1.15rem] font-bold mb-2.5 text-ink">Arus Barang</h3>
                <p class="text-muted text-[0.95rem]">Catat setiap barang masuk dan keluar. Stok bertambah atau berkurang otomatis sesuai transaksi.</p>
            </div>
            <!-- Card 3 -->
            <div class="bg-white rounded-[12px] p-10 shadow-[0_4px_15px_rgba(0,0,0,0.05)] transition-all duration-300 hover:-translate-y-1 hover:shadow-[0_10px_25px_rgba(0,0,0,0.08)] flex flex-col items-start">
                <div class="bg-transparent text-teal-dark text-[3rem] mb-6 block">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                </div>
                <h3 class="text-[1.15rem] font-bold mb-2.5 text-ink">Riwayat Transaksi</h3>
                <p class="text-muted text-[0.95rem]">Telusuri kapan suatu barang masuk atau keluar, berapa jumlahnya, dan siapa yang mencatat.</p>
            </div>
        </div>
    </section>

    <!-- Steps Section -->
    <section id="steps" class="py-24 bg-tint-steps scroll-mt-16">
        <div class="max-w-6xl mx-auto px-5 mb-14 flex flex-col items-center text-center">
            <h2 class="text-3xl md:text-[2.1rem] font-bold text-teal-dark mb-3">Mulai Kelola Inventory dengan Mudah</h2>
            <p class="text-[1.1rem] text-[#435b4a]">Tiga langkah praktis untuk mengoptimalkan operasional gudang Anda.</p>
        </div>
        
        <div class="max-w-6xl mx-auto px-5 grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Step 1 -->
            <div class="bg-white p-10 rounded-2xl shadow-[0_8px_24px_rgba(0,0,0,0.04)] flex flex-col">
                <div class="flex justify-between items-center mb-6 w-full">
                    <div class="text-[3rem] font-extrabold text-step-num leading-[0.8] tracking-[-2px]">01</div>
                    <div class="text-[2.2rem] text-teal-main"><i class="fa-solid fa-user-lock"></i></div>
                </div>
                <h4 class="text-[1.25rem] font-bold text-ink mb-3">Login ke Sistem</h4>
                <p class="text-[#4a6354] text-[0.95rem] leading-relaxed">Pengguna masuk menggunakan akun yang telah diberikan oleh administrator.</p>
            </div>
            <!-- Step 2 -->
            <div class="bg-white p-10 rounded-2xl shadow-[0_8px_24px_rgba(0,0,0,0.04)] flex flex-col">
                <div class="flex justify-between items-center mb-6 w-full">
                    <div class="text-[3rem] font-extrabold text-step-num leading-[0.8] tracking-[-2px]">02</div>
                    <div class="text-[2.2rem] text-teal-main"><i class="fa-solid fa-box-open"></i></div>
                </div>
                <h4 class="text-[1.25rem] font-bold text-ink mb-3">Catat Barang Masuk & Keluar</h4>
                <p class="text-[#4a6354] text-[0.95rem] leading-relaxed">Staff gudang mencatat transaksi, sedangkan admin juga dapat mengelola data barang.</p>
            </div>
            <!-- Step 3 -->
            <div class="bg-white p-10 rounded-2xl shadow-[0_8px_24px_rgba(0,0,0,0.04)] flex flex-col">
                <div class="flex justify-between items-center mb-6 w-full">
                    <div class="text-[3rem] font-extrabold text-step-num leading-[0.8] tracking-[-2px]">03</div>
                    <div class="text-[2.2rem] text-teal-main"><i class="fa-solid fa-chart-line"></i></div>
                </div>
                <h4 class="text-[1.25rem] font-bold text-ink mb-3">Pantau Stok</h4>
                <p class="text-[#4a6354] text-[0.95rem] leading-relaxed">Pengguna melihat sisa stok dan riwayat transaksi di dashboard dan tabel barang.</p>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-ink text-white pt-14">
        <div class="max-w-6xl mx-auto px-5 pb-10 grid grid-cols-1 md:grid-cols-[1.5fr_1fr_1fr] gap-10 items-start">
            
            <!-- Kolom 1: Brand, Logo & Maps -->
            <div>
                <div class="flex items-center gap-3 mb-4">
                    <img src="{{ asset('assets/images/logo.png') }}" alt="Logo CV Jayagiry" class="w-10 h-auto">
                    <h3 class="text-[1.4rem] font-bold text-white m-0">CV Jayagiry</h3>
                </div>
                <p class="text-white/85 leading-relaxed mb-5">Jl. Siliwangi, Padaasih, Kec. Cisaat, Kab. Sukabumi, Jawa Barat 43156.</p>
                
                <!-- Google Maps Embed -->
                <div class="w-full h-40 rounded-lg overflow-hidden border border-white/20 opacity-90 hover:opacity-100 transition-opacity">
                    <iframe 
                        src="https://maps.google.com/maps?q=Jl.+Siliwangi,+Padaasih,+Kec.+Cisaat,+Kab.+Sukabumi&t=&z=15&ie=UTF8&iwloc=&output=embed" 
                        width="100%" 
                        height="100%" 
                        style="border:0;" 
                        allowfullscreen="" 
                        loading="lazy" 
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
            </div>
            
            <!-- Kolom 2: Kontak -->
            <div>
                <h4 class="text-[1.1rem] font-semibold text-white mb-5">Hubungi Kami</h4>

                <!-- Telepon -->
                <p class="flex items-start gap-2.5 mb-3 text-white/85">
                    <i class="fa-solid fa-phone mt-1.5 text-teal-light"></i>
                    <a href="tel:+628111114109" class="hover:text-white transition-colors">0811-1114-109</a>
                </p>

                <!-- Instagram -->
                <p class="flex items-start gap-2.5 mb-3 text-white/85">
                    <i class="fa-brands fa-instagram mt-1.5 text-teal-light"></i>
                    <a href="https://www.instagram.com/cv.jayagiry/" target="_blank" rel="noopener noreferrer" class="hover:text-white transition-colors">@cv.jayagiry</a>
                </p>

                <!-- Facebook -->
                <p class="flex items-start gap-2.5 mb-3 text-white/85">
                    <i class="fa-brands fa-facebook mt-1.5 text-teal-light"></i>
                    <a href="https://www.facebook.com/people/CV-JAYAGIRY/100075862337857/?sk=about" target="_blank" rel="noopener noreferrer" class="hover:text-white transition-colors">CV.JAYAGIRY</a>
                </p>
            </div>

            <!-- Kolom 3: Tautan Cepat & Bantuan -->
            <div>
                <h4 class="text-[1.1rem] font-semibold text-white mb-5">Navigasi & Bantuan</h4>
                <ul class="space-y-3">
                    <li><a href="#features" class="text-white/85 hover:text-white transition-colors">Jelajahi Fitur Sistem</a></li>
                    <li><a href="#steps" class="text-white/85 hover:text-white transition-colors">Panduan Penggunaan</a></li>
                    <li><a href="tel:+628111114109" class="text-white/85 hover:text-white transition-colors">Hubungi IT Support</a></li>
                </ul>
            </div>
        </div>

        <!-- Tombol WhatsApp Mengambang -->
        <a href="https://wa.me/628987361447?text=Halo%20Admin%2C%20saya%20ingin%20bertanya"
        target="_blank" rel="noopener noreferrer" aria-label="Hubungi admin via WhatsApp"
        class="fixed bottom-6 right-6 z-50 flex items-center justify-center w-14 h-14 rounded-full bg-[#25D366] text-white text-3xl shadow-lg hover:bg-[#1ebe5b] hover:scale-110 transition-all duration-300">
            <i class="fa-brands fa-whatsapp"></i>
        </a>
        
        <!-- Garis Pemisah -->
        <hr class="border-t border-white/15 max-w-6xl mx-auto w-[calc(100%-2.5rem)]">
        
        <!-- Copyright & Versi -->
        <div class="max-w-6xl mx-auto px-5 py-6 text-center text-[0.9rem] text-white/70">
            <span>&copy; 2026 CV Jayagiry. Internal System.</span>
        </div>
    </footer>

</body>
</html>