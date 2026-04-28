<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * Menampilkan halaman detail event.
     */
    public function show($id)
    {
        return view('event-detail');
    }

    /**
     * Menampilkan halaman checkout event.
     */
    public function checkout($id)
    {
        return view('checkout');
    }
}
