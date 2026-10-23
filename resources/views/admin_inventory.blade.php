<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Inventory - SIMANGU</title>
    <link href="https://cdn.jsdelivr.net/npm/@tabler/core@1.6.1/dist/css/tabler.min.css" rel="stylesheet"/>
</head>
<body>
<div class="page">
    <div class="page-wrapper">
        <div class="container-xl py-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="page-title">Admin Inventory</h2>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="btn btn-outline-danger">Keluar</button>
                </form>
            </div>

            <div class="card mb-4">
                <div class="card-body">
                    <div class="text-muted">Total nilai aset</div>
                    <div class="h2 mb-0">Rp {{ number_format($totalAset, 0, ',', '.') }}</div>
                </div>
            </div>

            <div class="card">
                <div class="table-responsive">
                    <table class="table table-vcenter card-table">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Nama</th>
                                <th>Kategori</th>
                                <th>Satuan</th>
                                <th class="text-end">Stok</th>
                                <th class="text-end">Stok Minimum</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($barang as $b)
                                <tr>
                                    <td>{{ $b->kode }}</td>
                                    <td>{{ $b->nama }}</td>
                                    <td>{{ $b->kategori }}</td>
                                    <td>{{ $b->satuan }}</td>
                                    <td class="text-end">{{ $b->stok }}</td>
                                    <td class="text-end">{{ $b->stok_minimum }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="text-center text-muted">Belum ada data barang.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>