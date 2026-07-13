<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Models\Post;
use App\Models\Umkm;
use App\Models\Wisata;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(){
        return view('home',[
            'profil'  => Profile::current(),
            'wisatas'   => Wisata::published()
                        ->where('featured', true)
                        ->with(['media', 'tags'])
                        ->latest()
                        ->take(3)
                        ->get(),
            'umkms'   => Umkm::published()
                        ->where('featured', true)
                        ->with(['media', 'tags'])
                        ->latest()
                        ->take(3)
                        ->get(),
            'posts'   => Post::published()
                        ->with(['media', 'tags'])
                        ->latest('published_at')
                        ->take(3)
                        ->get(),
        ]);
    }
}
