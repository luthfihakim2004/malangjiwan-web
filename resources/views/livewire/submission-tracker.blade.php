<div>
    {{-- Search form --}}
    <form wire:submit="track" class="flex flex-col sm:flex-row gap-3 mb-8">
        <input type="text" wire:model="code"
               placeholder="ASP-2026-0001"
               class="flex-1 rounded-xl border border-[var(--color-bamboo-dark)] px-4 py-2.5 text-sm font-mono bg-[var(--color-paper)] focus:outline-none focus:ring-2 focus:ring-[var(--color-sawah)] uppercase"
               style="text-transform:uppercase">
        @error('code') <p class="text-xs text-red-500 -mt-2">{{ $message }}</p> @enderror

        <input type="text" wire:model="pin"
               placeholder="PIN (4 digit)"
               maxlength="4"
               inputmode="numeric"
               class="w-full sm:w-36 rounded-xl border border-[var(--color-bamboo-dark)] px-4 py-2.5 text-sm font-mono bg-[var(--color-paper)] focus:outline-none focus:ring-2 focus:ring-[var(--color-sawah)]">
        @error('pin') <p class="text-xs text-red-500 -mt-2">{{ $message }}</p> @enderror

        <button type="submit"
                wire:loading.attr="disabled"
                class="px-6 py-2.5 rounded-full bg-[var(--color-sawah-deep)] text-[var(--color-paper)] text-sm font-semibold hover:bg-[var(--color-sawah)] transition-colors">
            <span wire:loading.remove>Cek Status</span>
            <span wire:loading>Mencari...</span>
        </button>
    </form>

    {{-- Error --}}
    @if ($searched && $errorMsg)
        <div class="p-4 rounded-xl bg-red-50 border border-red-200 text-sm text-red-700">
            {{ $errorMsg }}
        </div>
    @endif

    {{-- Result --}}
    @if ($found && $submission)
        @php
            $statusEnum = $submission->status;
        @endphp
        <div class="rounded-2xl border border-[var(--color-bamboo)] overflow-hidden">

            {{-- Header --}}
            <div class="p-5 bg-[var(--color-paper-dim)] border-b border-[var(--color-bamboo)] flex flex-wrap items-start justify-between gap-3">
                <div>
                    <p class="font-mono text-xs text-[var(--color-ink)]/50 mb-1">{{ $submission->tracking_code }}</p>
                    <h3 class="font-display text-lg font-semibold text-[var(--color-sawah-deep)]">
                        {{ $submission->title }}
                    </h3>
                    <p class="text-sm text-[var(--color-ink)]/60 mt-0.5">
                        {{ $submission->type->label() }}
                        @if ($submission->category)
                            · {{ $submission->category->nama }}
                        @endif
                    </p>
                </div>
                <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $statusEnum->badgeClass() }}">
                    {{ $statusEnum->label() }}
                </span>
            </div>

            {{-- Body --}}
            <div class="p-5 space-y-4">

                <div class="grid sm:grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-[var(--color-ink)]/50 text-xs mb-0.5">Ditujukan kepada</p>
                        <p class="font-medium">{{ $submission->recipientLabel() }}</p>
                    </div>
                    <div>
                        <p class="text-[var(--color-ink)]/50 text-xs mb-0.5">Terakhir diperbarui</p>
                        <p class="font-medium">{{ $submission->updated_at->translatedFormat('d F Y, H:i') }} WIB</p>
                    </div>
                    <div>
                        <p class="text-[var(--color-ink)]/50 text-xs mb-0.5">Tanggal pengiriman</p>
                        <p class="font-medium">{{ $submission->submitted_at?->translatedFormat('d F Y') }}</p>
                    </div>
                    @if ($submission->resolved_at)
                        <div>
                            <p class="text-[var(--color-ink)]/50 text-xs mb-0.5">Tanggal selesai</p>
                            <p class="font-medium">{{ $submission->resolved_at->translatedFormat('d F Y') }}</p>
                        </div>
                    @endif
                </div>

                {{-- Public note from admin --}}
                @if ($submission->public_note)
                    <div class="p-4 rounded-xl bg-[var(--color-bamboo)]/40 border border-[var(--color-bamboo-dark)]">
                        <p class="eyebrow text-[var(--color-sawah-deep)] mb-1">Catatan dari Petugas</p>
                        <p class="text-sm text-[var(--color-ink)]/80">{{ $submission->public_note }}</p>
                    </div>
                @endif

            </div>
        </div>
    @endif
</div>
