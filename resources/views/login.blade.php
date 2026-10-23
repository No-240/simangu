<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Masuk - SIMANGU</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:wght@500;700;800&family=Public+Sans:wght@400;500;600&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/@tabler/core@1.6.1/dist/css/tabler.min.css" rel="stylesheet"/>
  <link href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@3.19.0/dist/tabler-icons.min.css" rel="stylesheet"/>
  <script type="module" src="https://unpkg.com/@lottiefiles/dotlottie-wc@0.7.1/dist/dotlottie-wc.js"></script>
  <style>
    :root { --ink:#12303a; --teal:#0f7b8a; --teal-dark:#0b5e6a; --tint:#eaf3f5; --muted:#5b6f77; --line:#cfd9dc; --amber:#c98a0b; }
    body { font-family:'Public Sans',system-ui,sans-serif; color:var(--ink);
           background:linear-gradient(160deg,#f3f8f9 0%,#e7eff2 100%); min-height:100vh; }
    h1, .brand { font-family:'Bricolage Grotesque','Public Sans',sans-serif; letter-spacing:-0.02em; }
    :focus-visible { outline:3px solid var(--amber); outline-offset:2px; }

    .shell { min-height:100vh; display:flex; align-items:center; justify-content:center; padding:1.5rem; }
    .card-login { width:100%; max-width:1000px; background:#fff; border-radius:18px;
                  box-shadow:0 20px 50px rgba(18,48,58,.12); overflow:hidden;
                  display:grid; grid-template-columns:1fr; }
    @media (min-width:992px) { .card-login { grid-template-columns:1fr 1fr; min-height:560px; } }

    .left { display:none; background:var(--tint); padding:2rem; flex-direction:column; }
    @media (min-width:992px) { .left { display:flex; } }
    .brand { font-weight:800; font-size:1.35rem; color:var(--ink); text-decoration:none; }
    .anim { flex:1; display:flex; align-items:center; justify-content:center; }
    dotlottie-wc { width:min(100%,380px); height:380px; }

    .right { padding:2.5rem 2rem; display:flex; align-items:center; }
    @media (min-width:992px) { .right { padding:3rem; } }
    .form-wrap { width:100%; max-width:22rem; margin:0 auto; }
    .brand-m { display:inline-block; margin-bottom:1.5rem; }
    @media (min-width:992px) { .brand-m { display:none; } }
    h1 { font-weight:800; font-size:1.8rem; margin-bottom:.25rem; }
    .sub { color:var(--muted); margin-bottom:1.6rem; }

    .form-label { font-weight:600; font-size:.92rem; margin-bottom:.35rem; }
    .form-control { border:1.5px solid var(--line); border-radius:8px; padding:.7rem .9rem; }
    .form-control:focus { border-color:var(--teal); box-shadow:0 0 0 .2rem rgba(15,123,138,.18); }

    .roles { display:grid; grid-template-columns:1fr 1fr; gap:.5rem; }
    .roles input { position:absolute; opacity:0; }
    .roles label { border:1.5px solid var(--line); border-radius:8px; padding:.65rem .5rem; text-align:center;
                   font-weight:600; font-size:.92rem; cursor:pointer; }
    .roles input:checked + label { border-color:var(--teal); background:var(--tint); color:var(--teal-dark); }
    .roles input:focus-visible + label { outline:3px solid var(--amber); outline-offset:2px; }

    .pw { position:relative; }
    .pw .form-control { padding-right:4.5rem; }
    .pw button { position:absolute; right:.4rem; top:50%; transform:translateY(-50%); border:0; background:none;
                 color:var(--teal-dark); font-size:.85rem; font-weight:600; padding:.4rem .6rem; }

    .btn-simangu { background:var(--teal); color:#fff; border:0; padding:.8rem; font-weight:600; border-radius:8px; width:100%; }
    .btn-simangu:hover { background:var(--teal-dark); color:#fff; }
    .hint { color:var(--muted); font-size:.9rem; }
    a { color:var(--teal-dark); }
  </style>
</head>

<body>
  <div class="shell">
    <div class="card-login">

      <aside class="left">
        <a href="{{ url('/') }}" class="brand">SIMANGU</a>
        <div class="anim">
          <dotlottie-wc
            src="https://lottie.host/1295e6d9-a428-46d8-870f-8f4047202afe/gAau07BeLf.lottie"
            autoplay loop></dotlottie-wc>
        </div>
      </aside>

      <main class="right">
        <div class="form-wrap">
          <a href="{{ url('/') }}" class="brand brand-m">SIMANGU</a>
          <h1>Selamat datang</h1>
          <p class="sub">Masuk ke sistem gudang CV Jaya Giry.</p>

          @if ($errors->any())
            <div class="alert alert-danger py-2" role="alert">{{ $errors->first() }}</div>
          @endif

          <form method="POST" action="{{ route('login') }}" novalidate>
              @csrf

              <div class="mb-3">
                  <label for="email" class="form-label">Email</label>
                  <input id="email" type="email" name="email" value="{{ old('email') }}"
                        class="form-control @error('email') is-invalid @enderror"
                        placeholder="Masukkan email" required autofocus autocomplete="username">
                  @error('email')
                      <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
              </div>

              <div class="mb-3">
                  <label for="password" class="form-label">Kata sandi</label>
                  <div class="input-group">
                      <input id="password" type="password" name="password"
                            class="form-control @error('password') is-invalid @enderror"
                            placeholder="Masukkan kata sandi" required autocomplete="current-password">
                      <button type="button" class="btn btn-outline-secondary" id="toggle-password"
                              aria-label="Tampilkan kata sandi" aria-pressed="false">
                          <i class="ti ti-eye" id="toggle-icon"></i>
                      </button>
                  </div>
                  @error('password')
                      <div class="invalid-feedback d-block">{{ $message }}</div>
                  @enderror
              </div>

              <button type="submit" class="btn btn-primary w-100">Masuk</button>
          </form>

          <p class="hint mt-4 mb-1">Lupa kata sandi atau belum punya akun? Hubungi admin gudang.</p>
          <p class="hint mb-0"><a href="{{ url('/') }}">Kembali ke beranda</a></p>
        </div>
      </main>

    </div>
  </div>

  <script>
      document.addEventListener('DOMContentLoaded', function () {
          var input = document.getElementById('password');
          var btn   = document.getElementById('toggle-password');
          var icon  = document.getElementById('toggle-icon');

          if (input && btn && icon) {
              btn.addEventListener('click', function () {
                  var tampil = input.type === 'password';
                  input.type = tampil ? 'text' : 'password';
                  icon.className = tampil ? 'ti ti-eye-off' : 'ti ti-eye';
                  btn.setAttribute('aria-pressed', tampil ? 'true' : 'false');
                  btn.setAttribute('aria-label', tampil ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi');
              });
          }

          var form = document.querySelector('form');
          if (form) {
              form.addEventListener('submit', function () {
                  var b = this.querySelector('button[type=submit]');
                  b.disabled = true;
                  b.textContent = 'Memproses...';
              });
          }
      });
  </script>

</body>
</html>
