<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Menampilkan halaman kelola kategori.
     */
    public function index()
    {
        return view('admin.categories.index');
    }
}
