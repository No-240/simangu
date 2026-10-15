{{-- resources/views/partials/loading.blade.php
     Muncul hanya sekali setelah login berhasil (flash 'baru_login' dari LoginController). --}}
@if (session('baru_login'))
<div id="page-loader" role="status" aria-live="polite">
    <div class="pl-box">
        <div class="pl-brand">SIMANGU</div>
        <dotlottie-wc src="https://lottie.host/7743ec81-d508-4f44-ab64-c56520f9b771/NOD4baBhkY.lottie" autoplay loop style="width: 180px; height: 180px; display: block; margin: 0 auto 0.5rem"></dotlottie-wc>
        <p class="pl-text">Menyiapkan dashboard...</p>
    </div>
</div>

<script type="module" src="https://unpkg.com/@lottiefiles/dotlottie-wc@0.7.1/dist/dotlottie-wc.js"></script>

<style>
    #page-loader {
        position: fixed;
        inset: 0;
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--ink, #12301f);
        color: #fff;
        transition: opacity .35s ease, visibility .35s ease;
    }
    #page-loader.selesai { opacity: 0; visibility: hidden; }
    .pl-box { text-align: center; }
    .pl-brand {
        font-weight: 800;
        font-size: 1.6rem;
        letter-spacing: -0.02em;
        margin-bottom: 1.5rem;
    }
    .pl-spinner {
        width: 44px;
        height: 44px;
        margin: 0 auto 1rem;
        border: 4px solid rgba(255, 255, 255, .2);
        border-top-color: var(--teal-light, #9be0b4);
        border-radius: 50%;
        animation: pl-putar .8s linear infinite;
    }
    .pl-text { margin: 0; font-size: .95rem; color: #b8d4c3; }
    @keyframes pl-putar { to { transform: rotate(360deg); } }
    @media (prefers-reduced-motion: reduce) {
        .pl-spinner { animation-duration: 2.5s; }
        #page-loader { transition: none; }
    }
</style>

<script>
    (function () {
        var loader = document.getElementById('page-loader');
        if (!loader) return;

        var mulai = Date.now();
        var MIN_TAMPIL = 1800;   // ms, supaya tidak berkedip
        var MAKS_TUNGGU = 8000; // ms, pengaman agar tidak menggantung
        var sudah = false;

        function sembunyikan() {
            if (sudah) return;
            sudah = true;
            var sisa = Math.max(0, MIN_TAMPIL - (Date.now() - mulai));
            setTimeout(function () {
                loader.classList.add('selesai');
                setTimeout(function () { loader.remove(); }, 400);
            }, sisa);
        }

        // 'load' menunggu CSS, gambar, dan font selesai dimuat
        if (document.readyState === 'complete') {
            sembunyikan();
        } else {
            window.addEventListener('load', sembunyikan);
        }
        setTimeout(sembunyikan, MAKS_TUNGGU);
    })();
</script>
@endif