@extends('layouts.app')

@section('title', $judul)
@section('subtitle', $deskripsi)

@section('content')
<div class="flex flex-col items-center justify-center rounded-xl border border-dashed border-line bg-surface px-6 py-20 text-center">
    <span class="mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-brand/15 text-brand">
        <i class="fa-solid fa-screwdriver-wrench text-2xl"></i>
    </span>
    <h2 class="text-lg font-semibold text-slate-100">Segera hadir</h2>
    <p class="mt-2 max-w-md text-sm text-muted">{{ $deskripsi }}</p>
</div>
@endsection