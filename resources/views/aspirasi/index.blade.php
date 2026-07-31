@extends('layouts.app')
@section('title', 'Aspirasi & Pengaduan — Malangjiwan')
@section('meta_description', 'Sampaikan aspirasi, pengaduan, kritik, dan saran Anda kepada Pemerintah Desa Malangjiwan.')

@push('scripts')
<script src="https://www.google.com/recaptcha/api.js?render={{ config('services.recaptcha.site_key') }}"></script>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('submissionForm', () => ({
        async submit() {
            try {
                const token = await grecaptcha.execute(
                    '{{ config('services.recaptcha.site_key') }}',
                    { action: 'submission' }
                );

                await this.$wire.submitWithToken(token);
            } catch (e) {
                console.error('reCAPTCHA error:', e);
            }
        }
    }));
});
</script>
@endpush

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-14">

    <div class="mb-10">
        <p class="eyebrow mb-1">Layanan Publik</p>
        <h1 class="font-display text-3xl sm:text-4xl font-semibold text-[var(--color-sawah-deep)]">
            Aspirasi & Pengaduan
        </h1>
        <p class="mt-2 text-[var(--color-ink)]/60 max-w-lg">
            Sampaikan aspirasi, pengaduan, kritik, atau saran Anda.
            Setiap pengiriman akan mendapatkan kode pelacakan untuk memantau perkembangan.
        </p>
    </div>

    {{-- Tab switcher --}}
    <div x-data="{ tab: 'kirim' }" class="space-y-6">

        <div class="flex gap-2 border-b border-[var(--color-bamboo)]">
            <button @click="tab = 'kirim'"
                    :class="tab === 'kirim'
                        ? 'border-b-2 border-[var(--color-sawah-deep)] text-[var(--color-sawah-deep)] font-semibold'
                        : 'text-[var(--color-ink)]/50 hover:text-[var(--color-ink)]'"
                    class="pb-3 px-1 text-sm transition-colors">
                Kirim Aspirasi
            </button>
            <button @click="tab = 'cek'"
                    :class="tab === 'cek'
                        ? 'border-b-2 border-[var(--color-sawah-deep)] text-[var(--color-sawah-deep)] font-semibold'
                        : 'text-[var(--color-ink)]/50 hover:text-[var(--color-ink)]'"
                    class="pb-3 px-1 text-sm transition-colors">
                Cek Status
            </button>
        </div>

        <div x-show="tab === 'kirim'">
            <livewire:submission-form />
        </div>

        <div x-show="tab === 'cek'">
            <p class="text-sm text-[var(--color-ink)]/60 mb-6">
                Masukkan kode pelacakan dan PIN yang Anda terima saat pengiriman.
            </p>
            <livewire:submission-tracker />
        </div>

    </div>
</div>
@endsection
