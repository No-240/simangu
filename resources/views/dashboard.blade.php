<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dashboard - SIMANGU</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  @include('partials.loading')
  <div class="container py-5">
    <h1 class="h3">Dashboard sementara</h1>
    <p>Login berhasil sebagai <strong>{{ auth()->user()->name }}</strong>
       (peran: {{ auth()->user()->role === 'admin' ? 'Admin / Owner' : 'Staff Gudang' }}).</p>
    <form method="POST" action="{{ route('logout') }}">
      @csrf
      <button class="btn btn-outline-dark">Keluar</button>
    </form>
  </div>
</body>
</html>
