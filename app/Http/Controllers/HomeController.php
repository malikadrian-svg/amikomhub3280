<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Menampilkan halaman utama (welcome).
     */
    public function index()
    {
        return view('welcome');
    }
}
