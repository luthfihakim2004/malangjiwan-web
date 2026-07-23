<?php

use App\Http\Controllers\GalleryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\UmkmController;
use App\Http\Controllers\WisataController;
use App\Livewire\Peta;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/profil-desa', [ProfileController::class, 'index'])->name('profil');

Route::get('/peta', Peta::class)->name('peta');

Route::get('/wisata', [WisataController::class, 'index'])->name('wisata.index');
Route::get('/wisata/{wisata:slug}', [WisataController::class, 'show'])->name('wisata.show');

Route::get('/umkm', [UmkmController::class, 'index'])->name('umkm.index');
Route::get('/umkm/{umkm:slug}', [UmkmController::class, 'show'])->name('umkm.show');

Route::get('/post', [PostController::class, 'index'])->name('post.index');
Route::get('/post/{post:slug}', [PostController::class, 'show'])->name('post.show');

Route::get('/galeri', [GalleryController::class, 'index'])->name('galeri.index');

Route::get('/sitemap.xml', SitemapController::class)->name('sitemap');
