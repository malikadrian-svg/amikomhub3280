<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * Menampilkan halaman detail event.
     */
    public function show(\App\Models\Event $event)
    {
        // Mengambil daftar kategori untuk keperluan menu footer
        $categories = \App\Models\Category::all();

        // Me-render view dengan membawa data kategori dan data spesifik acara tersebut
        return view('event-detail', compact('categories', 'event'));
    }

    /**
     * Menampilkan halaman checkout event.
     */
    public function checkout($id)
    {
        return view('checkout');
    }
}
