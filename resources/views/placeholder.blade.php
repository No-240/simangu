@extends('layouts.app')

@section('title', $judul)

@section('content')
<div class="section-header">
    <h1>{{ $judul }}</h1>
</div>

<div class="card">
    <div class="card-body text-center py-5">
        <i class="fas fa-tools fa-3x text-muted mb-3"></i>
        <h5>Segera hadir</h5>
        <p class="text-muted mb-0">{{ $deskripsi }}</p>
    </div>
</div>
@endsection