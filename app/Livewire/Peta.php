<?php

namespace App\Livewire;

use App\Models\Tag;
use App\Models\Umkm;
use App\Models\Wisata;
use Livewire\Component;

class Peta extends Component
{
    public bool $showUmkm   = true;
    public bool $showWisata = true;
    public string $tag      = '';

    public function render()
    {
        $wisataMarkers = [];
        $umkmMarkers   = [];

        if ($this->showWisata) {
            $wisataMarkers = Wisata::published()
                ->whereNotNull('latitude')
                ->whereNotNull('longitude')
                ->when($this->tag, fn ($q) =>
                    $q->whereHas('tags', fn ($t) => $t->where('slug', $this->tag))
                )
                ->with('tags')
                ->get()
                ->map(fn ($w) => [
                    'lat'      => (float) $w->latitude,
                    'lng'      => (float) $w->longitude,
                    'nama'     => $w->nama,
                    'type'     => 'wisata',
                    'kategori' => $w->tags->pluck('nama')->join(', '),
                    'url'      => route('wisata.show', $w->slug),
                ])->toArray();
        }

        if ($this->showUmkm) {
            $umkmMarkers = Umkm::published()
                ->whereNotNull('latitude')
                ->whereNotNull('longitude')
                ->when($this->tag, fn ($q) =>
                    $q->whereHas('tags', fn ($t) => $t->where('slug', $this->tag))
                )
                ->with('tags')
                ->get()
                ->map(fn ($u) => [
                    'lat'      => (float) $u->latitude,
                    'lng'      => (float) $u->longitude,
                    'nama'     => $u->nama,
                    'type'     => 'umkm',
                    'kategori' => $u->tags->pluck('nama')->join(', '),
                    'url'      => route('umkm.show', $u->slug),
                ])->toArray();
        }

        $tags = Tag::where(function ($q) {
                $q->whereHas('wisatas', fn ($w) => $w->published()->whereNotNull('latitude'))
                  ->orWhereHas('umkms', fn ($u) => $u->published()->whereNotNull('latitude'));
            })
            ->orderBy('nama')
            ->get();

        $markers = array_merge($wisataMarkers, $umkmMarkers);

        return view('livewire.peta', [
            'markers' => $markers,
            'tags'    => $tags,
        ]);
    }

    public function updated(): void
    {
        $this->dispatch('peta-updated');
    }
}
