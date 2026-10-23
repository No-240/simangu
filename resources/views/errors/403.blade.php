<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>403 - Akses Ditolak | Sistem Inventory</title>
    <!-- Tabler CSS -->
    <link href="https://cdn.jsdelivr.net/npm/@tabler/core@1.6.1/dist/css/tabler.min.css" rel="stylesheet"/>
</head>
<body class="border-top-wide border-primary d-flex flex-column bg-body-tertiary">
    <div class="page page-center">
        <div class="container-tight py-4">
            <div class="empty">
                <div class="empty-header">403</div>
                <p class="empty-title">Akses Ditolak</p>
                <p class="empty-subtitle text-secondary">
                    Role Anda (<strong class="badge bg-red-lt text-uppercase">{{ Auth::user()->role ?? 'Tamu' }}</strong>) tidak diizinkan membuka halaman ini.
                </p>
                <div class="empty-action">
                    <a href="javascript:history.back()" class="btn btn-primary">
                        Kembali ke Halaman Sebelumnya
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>