@php
    $path = $getRecord()?->attachment;
    $url  = $path ? asset('storage/' . $path) : null;
    $ext  = $path ? strtolower(pathinfo($path, PATHINFO_EXTENSION)) : null;
@endphp

@if ($url)
    <div class="mt-1">
        @if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp']))
            <a href="{{ $url }}" target="_blank">
                <img src="{{ $url }}" alt="Lampiran" class="max-h-64 rounded-lg border object-contain">
            </a>
        @elseif ($ext === 'pdf')
            <a href="{{ $url }}" target="_blank"
               class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-red-50 border border-red-200 text-red-700 text-sm font-medium hover:bg-red-100 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                </svg>
                Buka PDF
            </a>
        @elseif ($ext === 'mp4')
            <video controls class="max-h-64 rounded-lg border w-full">
                <source src="{{ $url }}" type="video/mp4">
                Browser Anda tidak mendukung video.
            </video>
        @else
            <a href="{{ $url }}" target="_blank"
               class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-gray-50 border text-gray-700 text-sm font-medium hover:bg-gray-100 transition-colors">
                Unduh Lampiran
            </a>
        @endif
    </div>
@else
    <p class="text-sm text-gray-400 italic">Tidak ada lampiran.</p>
@endif
