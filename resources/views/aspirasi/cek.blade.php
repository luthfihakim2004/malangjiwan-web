@extends('layouts.app')

@section('title', 'Cek Status Aspirasi - Malangjiwan')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-14">

    <nav class="mb-8 text-sm text-[var(--color-ink)]/50 flex items-center gap-2">
        <a href="{{ route('aspirasi.index') }}" class="hover:text-[var(--color-bata)]">Aspirasi</a>
        <span>/</span>
        <span class="text-[var(--color-ink)]/80">Cek Status</span>
    </nav>

    <div class="mb-8">
        <p class="eyebrow mb-1">Pelacakan</p>
        <h1 class="font-display text-3xl font-semibold text-[var(--color-sawah-deep)]">
            Cek Status Aspirasi
        </h1>
        <p class="mt-2 text-sm text-[var(--color-ink)]/60">
            Masukkan kode pelacakan dan PIN 4 digit yang Anda terima saat pengiriman.
        </p>
    </div>

    <livewire:submission-tracker />

    <p class="mt-8 text-sm text-[var(--color-ink)]/50">
        Belum punya kode?
        <a href="{{ route('aspirasi.index') }}" class="text-[var(--color-sawah)] hover:text-[var(--color-bata)]">
            Kirim aspirasi baru
        </a>
    </p>

</div>
@endsection
