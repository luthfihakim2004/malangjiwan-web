<div>
@if ($submitted)
    {{-- ── Confirmation ── --}}
    <div class="rounded-2xl border border-[var(--color-bamboo)] bg-[var(--color-paper-dim)] p-8 text-center">
        <div class="w-14 h-14 rounded-full bg-green-100 flex items-center justify-center mx-auto mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
            </svg>
        </div>

        <h3 class="font-display text-2xl font-semibold text-[var(--color-sawah-deep)] mb-2">
            Aspirasi Berhasil Dikirim
        </h3>
        <p class="text-[var(--color-ink)]/60 text-sm mb-8 max-w-sm mx-auto">
            Simpan kode dan PIN berikut untuk memantau status aspirasi Anda.
            <strong class="text-[var(--color-bata)]">PIN tidak dapat ditampilkan ulang.</strong>
        </p>

        <div class="inline-flex flex-col sm:flex-row gap-4 p-5 rounded-xl bg-[var(--color-paper)] border border-[var(--color-bamboo-dark)] mb-6">
            <div class="text-center">
                <p class="text-xs text-[var(--color-ink)]/50 mb-1">Kode Pelacakan</p>
                <p class="font-mono text-2xl font-bold text-[var(--color-sawah-deep)] tracking-wider">
                    {{ $trackingCode }}
                </p>
            </div>
            <div class="hidden sm:block w-px bg-[var(--color-bamboo-dark)]"></div>
            <div class="text-center">
                <p class="text-xs text-[var(--color-ink)]/50 mb-1">PIN</p>
                <p class="font-mono text-2xl font-bold text-[var(--color-bata)] tracking-widest">
                    {{ $trackingPin }}
                </p>
            </div>
        </div>

        <a href="{{ route('aspirasi.cek') }}"
           class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-[var(--color-sawah)] text-[var(--color-paper)] text-sm font-semibold hover:bg-[var(--color-sawah-deep)] transition-colors">
            Cek Status Aspirasi
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
            </svg>
        </a>
    </div>

@else
    {{-- ── Form ── --}}
    <form
        x-data="submissionForm()"
        @submit.prevent="submit"
        class="space-y-6"
    >

        {{-- Recipient info (embedded context) --}}
        @if ($recipientName)
            <div class="flex items-center gap-3 p-3 rounded-xl bg-[var(--color-bamboo)]/40 text-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0 text-[var(--color-sawah)]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0zM19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                </svg>
                <span class="text-[var(--color-ink)]/70">Ditujukan kepada: <strong class="text-[var(--color-ink)]">{{ $recipientName }}</strong></span>
            </div>
        @endif

        {{-- Type selection --}}
        <div>
            <label class="block text-sm font-medium text-[var(--color-ink)] mb-2">
                Jenis Aspirasi <span class="text-[var(--color-bata)]">*</span>
            </label>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                @foreach (\App\Enums\SubmissionType::cases() as $t)
                    <label class="relative cursor-pointer">
                        <input type="radio" wire:model="type" value="{{ $t->value }}" class="peer sr-only">
                        <div class="p-3 rounded-xl border-2 text-center transition-all
                                    border-[var(--color-bamboo)] peer-checked:border-[var(--color-sawah-deep)]
                                    peer-checked:bg-[var(--color-sawah-deep)] peer-checked:text-[var(--color-paper)]
                                    hover:border-[var(--color-sawah-light)]">
                            <p class="font-medium text-sm">{{ $t->label() }}</p>
                        </div>
                    </label>
                @endforeach
            </div>
            @error('type') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
        </div>

        {{-- Standalone: recipient selection --}}
        @if (! $recipientType)
            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-[var(--color-ink)] mb-1">Tujukan ke</label>
                    <select wire:model.live="recipientChoice"
                            class="w-full rounded-xl border border-[var(--color-bamboo-dark)] px-3 py-2.5 text-sm bg-[var(--color-paper)] focus:outline-none focus:ring-2 focus:ring-[var(--color-sawah)]">
                        <option value="pemerintah">Pemerintah Desa</option>
                        <option value="wisata">Destinasi Wisata</option>
                        <option value="umkm">UMKM</option>
                    </select>
                </div>

                @if ($recipientChoice === 'wisata' && $wisataOptions->isNotEmpty())
                    <div>
                        <label class="block text-sm font-medium text-[var(--color-ink)] mb-1">Pilih Wisata</label>
                        <select wire:model="recipientId"
                                class="w-full rounded-xl border border-[var(--color-bamboo-dark)] px-3 py-2.5 text-sm bg-[var(--color-paper)] focus:outline-none focus:ring-2 focus:ring-[var(--color-sawah)]">
                            <option value="">-- Pilih --</option>
                            @foreach ($wisataOptions as $id => $nama)
                                <option value="{{ $id }}">{{ $nama }}</option>
                            @endforeach
                        </select>
                    </div>
                @elseif ($recipientChoice === 'umkm' && $umkmOptions->isNotEmpty())
                    <div>
                        <label class="block text-sm font-medium text-[var(--color-ink)] mb-1">Pilih UMKM</label>
                        <select wire:model="recipientId"
                                class="w-full rounded-xl border border-[var(--color-bamboo-dark)] px-3 py-2.5 text-sm bg-[var(--color-paper)] focus:outline-none focus:ring-2 focus:ring-[var(--color-sawah)]">
                            <option value="">-- Pilih --</option>
                            @foreach ($umkmOptions as $id => $nama)
                                <option value="{{ $id }}">{{ $nama }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif
            </div>
        @endif

        {{-- Title --}}
        <div>
            <label class="block text-sm font-medium text-[var(--color-ink)] mb-1">
                Judul <span class="text-[var(--color-bata)]">*</span>
            </label>
            <input type="text" wire:model="title" placeholder="Ringkasan singkat aspirasi Anda"
                   class="w-full rounded-xl border border-[var(--color-bamboo-dark)] px-3 py-2.5 text-sm bg-[var(--color-paper)] focus:outline-none focus:ring-2 focus:ring-[var(--color-sawah)]">
            @error('title') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
        </div>

        {{-- Description --}}
        <div>
            <label class="block text-sm font-medium text-[var(--color-ink)] mb-1">
                Deskripsi <span class="text-[var(--color-bata)]">*</span>
            </label>
            <textarea wire:model="description" rows="5"
                      placeholder="Jelaskan aspirasi Anda secara detail..."
                      class="w-full rounded-xl border border-[var(--color-bamboo-dark)] px-3 py-2.5 text-sm bg-[var(--color-paper)] focus:outline-none focus:ring-2 focus:ring-[var(--color-sawah)] resize-none"></textarea>
            @error('description') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
        </div>

        {{-- Category + Incident date --}}
        <div class="grid sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-[var(--color-ink)] mb-1">Kategori</label>
                <select wire:model="categoryId"
                        class="w-full rounded-xl border border-[var(--color-bamboo-dark)] px-3 py-2.5 text-sm bg-[var(--color-paper)] focus:outline-none focus:ring-2 focus:ring-[var(--color-sawah)]">
                    <option value="">-- Pilih Kategori --</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-[var(--color-ink)] mb-1">Tanggal Kejadian</label>
                <input type="date" wire:model="incidentDate"
                       max="{{ date('Y-m-d') }}"
                       class="w-full rounded-xl border border-[var(--color-bamboo-dark)] px-3 py-2.5 text-sm bg-[var(--color-paper)] focus:outline-none focus:ring-2 focus:ring-[var(--color-sawah)]">
                @error('incidentDate') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>
        </div>

        {{-- Location description --}}
        <div>
            <label class="block text-sm font-medium text-[var(--color-ink)] mb-1">Keterangan Lokasi</label>
            <input type="text" wire:model="locationDescription"
                   placeholder="e.g. Depan gerbang masuk, dekat parkiran..."
                   class="w-full rounded-xl border border-[var(--color-bamboo-dark)] px-3 py-2.5 text-sm bg-[var(--color-paper)] focus:outline-none focus:ring-2 focus:ring-[var(--color-sawah)]">
        </div>

        {{-- Attachment --}}
        <div>
            <label class="block text-sm font-medium text-[var(--color-ink)] mb-1">
                Lampiran <span class="text-[var(--color-ink)]/40 font-normal">(foto/PDF/video, maks. 5MB)</span>
            </label>
            <input type="file" wire:model="attachment"
                   accept=".jpg,.jpeg,.png,.pdf,.mp4"
                   class="w-full text-sm text-[var(--color-ink)]/70 file:mr-3 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-[var(--color-bamboo)] file:text-[var(--color-sawah-deep)] hover:file:bg-[var(--color-bamboo-dark)] cursor-pointer">
            <div wire:loading wire:target="attachment" class="mt-1 text-xs text-[var(--color-ink)]/50">Mengunggah...</div>
            @error('attachment') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
        </div>

        {{-- Identity mode --}}
        <div class="border-t border-[var(--color-bamboo)] pt-6">
            <label class="block text-sm font-medium text-[var(--color-ink)] mb-3">Identitas Pelapor</label>
            <div class="grid sm:grid-cols-2 gap-2 mb-4">
                <label class="relative cursor-pointer">
                    <input type="radio" wire:model.live="identityMode" value="anonymous" class="peer sr-only">
                    <div class="p-3 rounded-xl border-2 transition-all text-sm
                                border-[var(--color-bamboo)] peer-checked:border-[var(--color-sawah-deep)]
                                peer-checked:bg-[var(--color-sawah-deep)] peer-checked:text-[var(--color-paper)]">
                        <p class="font-medium">Anonim</p>
                        <p class="text-xs opacity-70 mt-0.5">Identitas tidak dicantumkan</p>
                    </div>
                </label>
                <label class="relative cursor-pointer">
                    <input type="radio" wire:model.live="identityMode" value="identified" class="peer sr-only">
                    <div class="p-3 rounded-xl border-2 transition-all text-sm
                                border-[var(--color-bamboo)] peer-checked:border-[var(--color-sawah-deep)]
                                peer-checked:bg-[var(--color-sawah-deep)] peer-checked:text-[var(--color-paper)]">
                        <p class="font-medium">Teridentifikasi</p>
                        <p class="text-xs opacity-70 mt-0.5">Nama dicantumkan</p>
                    </div>
                </label>
            </div>

            @if ($identityMode === 'identified')
                <div class="grid sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-[var(--color-ink)] mb-1">
                            Nama <span class="text-[var(--color-bata)]">*</span>
                        </label>
                        <input type="text" wire:model="reporterName" placeholder="Nama lengkap"
                               class="w-full rounded-xl border border-[var(--color-bamboo-dark)] px-3 py-2 text-sm bg-[var(--color-paper)] focus:outline-none focus:ring-2 focus:ring-[var(--color-sawah)]">
                        @error('reporterName') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-[var(--color-ink)] mb-1">Telepon</label>
                        <input type="tel" wire:model="reporterPhone" placeholder="08..."
                               class="w-full rounded-xl border border-[var(--color-bamboo-dark)] px-3 py-2 text-sm bg-[var(--color-paper)] focus:outline-none focus:ring-2 focus:ring-[var(--color-sawah)]">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-[var(--color-ink)] mb-1">Email</label>
                        <input type="email" wire:model="reporterEmail" placeholder="email@domain.com"
                               class="w-full rounded-xl border border-[var(--color-bamboo-dark)] px-3 py-2 text-sm bg-[var(--color-paper)] focus:outline-none focus:ring-2 focus:ring-[var(--color-sawah)]">
                        @error('reporterEmail') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>
            @endif

            {{-- Always show phone for anonymous too so they can be contacted if needed --}}
            @if ($identityMode === 'anonymous')
                <div class="grid sm:grid-cols-2 gap-4 mt-4">
                    <div>
                        <label class="block text-xs font-medium text-[var(--color-ink)] mb-1">
                            Telepon <span class="text-[var(--color-ink)]/40 font-normal">(opsional)</span>
                        </label>
                        <input type="tel" wire:model="reporterPhone" placeholder="08..."
                               class="w-full rounded-xl border border-[var(--color-bamboo-dark)] px-3 py-2 text-sm bg-[var(--color-paper)] focus:outline-none focus:ring-2 focus:ring-[var(--color-sawah)]">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-[var(--color-ink)] mb-1">
                            Email <span class="text-[var(--color-ink)]/40 font-normal">(opsional)</span>
                        </label>
                        <input type="email" wire:model="reporterEmail" placeholder="email@domain.com"
                               class="w-full rounded-xl border border-[var(--color-bamboo-dark)] px-3 py-2 text-sm bg-[var(--color-paper)] focus:outline-none focus:ring-2 focus:ring-[var(--color-sawah)]">
                        @error('reporterEmail') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>
            @endif
        </div>

        @error('captcha')
            <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
        @enderror

        {{-- Submit --}}
        <div class="pt-2">
            <button type="submit"
                    wire:loading.attr="disabled" wire:target="submitWithToken"
                    class="w-full sm:w-auto px-8 py-3 rounded-full bg-[var(--color-sawah-deep)] text-[var(--color-paper)] font-semibold hover:bg-[var(--color-sawah)] transition-colors disabled:opacity-60">
                <span wire:loading.remove wire:target="submitWithToken">Kirim Aspirasi</span>
                <span wire:loading wire:target="submitWithToken">Mengirim...</span>
            </button>
            <p class="mt-2 text-xs text-[var(--color-ink)]/40">
                Data yang Anda kirimkan akan diproses oleh petugas desa.
            </p>
        </div>

    </form>
@endif
</div>
