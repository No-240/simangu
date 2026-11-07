<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk - SIMANGU</title>

    @vite(['resources/css/app.css'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script type="module" src="https://unpkg.com/@lottiefiles/dotlottie-wc@0.7.1/dist/dotlottie-wc.js"></script>
</head>
<body class="min-h-screen bg-app font-sans text-slate-100 antialiased">

<div class="flex min-h-screen items-center justify-center p-6">
    <div class="grid w-full max-w-4xl overflow-hidden rounded-2xl border border-line bg-surface shadow-2xl lg:grid-cols-2">

        {{-- Kiri: animasi --}}
        <aside class="hidden flex-col justify-between bg-surface-2 p-10 lg:flex">
            <span class="text-xl font-bold tracking-tight text-brand">SIMANGU</span>

            <div class="flex flex-1 items-center justify-center">
                <dotlottie-wc
                    src="https://lottie.host/1295e6d9-a428-46d8-870f-8f4047202afe/gAau07BeLf.lottie"
                    autoplay loop
                    class="block h-80 w-full max-w-sm"></dotlottie-wc>
            </div>

            <p class="text-sm text-muted">Sistem pencatatan gudang CV Jaya Giry.</p>
        </aside>

        {{-- Kanan: form --}}
        <main class="flex items-center p-8 sm:p-12">
            <div class="mx-auto w-full max-w-sm">

                <a href="{{ url('/') }}" class="mb-6 block text-xl font-bold tracking-tight text-brand lg:hidden">SIMANGU</a>

                <h1 class="text-2xl font-bold text-slate-100">Selamat datang</h1>
                <p class="mb-6 mt-1 text-sm text-muted">Masuk ke sistem gudang CV Jaya Giry.</p>

                @if ($errors->any())
                    <div class="mb-5 rounded-lg border border-red-500/30 bg-red-500/10 px-4 py-3 text-sm text-red-300" role="alert">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" novalidate class="space-y-5">
                    @csrf

                    <div>
                        <label for="email" class="mb-1.5 block text-sm font-medium text-slate-300">Email</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}"
                               placeholder="Masukkan email" required autofocus autocomplete="username"
                               class="w-full rounded-lg border bg-surface-2 px-3.5 py-2.5 text-sm text-slate-100 placeholder:text-muted focus:border-brand focus:outline-none focus:ring-2 focus:ring-brand/20
                                      {{ $errors->has('email') ? 'border-red-500' : 'border-line' }}">
                        @error('email')
                            <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="mb-1.5 block text-sm font-medium text-slate-300">Kata sandi</label>
                        <div class="relative">
                            <input id="password" type="password" name="password"
                                   placeholder="Masukkan kata sandi" required autocomplete="current-password"
                                   class="w-full rounded-lg border bg-surface-2 py-2.5 pl-3.5 pr-12 text-sm text-slate-100 placeholder:text-muted focus:border-brand focus:outline-none focus:ring-2 focus:ring-brand/20
                                          {{ $errors->has('password') ? 'border-red-500' : 'border-line' }}">
                            <button type="button" id="toggle-password"
                                    aria-label="Tampilkan kata sandi" aria-pressed="false"
                                    class="absolute inset-y-0 right-0 flex items-center px-3.5 text-muted hover:text-brand">
                                <i id="toggle-icon" class="fa-solid fa-eye"></i>
                            </button>
                        </div>
                        @error('password')
                            <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" id="btn-masuk"
                            class="w-full rounded-lg bg-brand py-3 text-sm font-semibold text-app hover:bg-brand-dark hover:text-white disabled:opacity-60">
                        Masuk
                    </button>
                </form>

                <p class="mt-6 text-sm text-muted">Lupa kata sandi atau belum punya akun? Hubungi admin gudang.</p>
                <p class="mt-2 text-sm">
                    <a href="{{ url('/') }}" class="text-brand hover:underline">Kembali ke beranda</a>
                </p>
            </div>
        </main>

    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var input = document.getElementById('password');
        var btn   = document.getElementById('toggle-password');
        var icon  = document.getElementById('toggle-icon');

        btn.addEventListener('click', function () {
            var tampil = input.type === 'password';
            input.type = tampil ? 'text' : 'password';
            icon.className = tampil ? 'fa-solid fa-eye-slash' : 'fa-solid fa-eye';
            btn.setAttribute('aria-pressed', tampil ? 'true' : 'false');
            btn.setAttribute('aria-label', tampil ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi');
        });

        document.querySelector('form').addEventListener('submit', function () {
            var b = document.getElementById('btn-masuk');
            b.disabled = true;
            b.textContent = 'Memproses...';
        });
    });
</script>

</body>
</html>