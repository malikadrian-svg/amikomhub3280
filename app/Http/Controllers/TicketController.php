<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TicketController extends Controller
{
    /**
     * Menampilkan halaman e-ticket.
     */
    public function show($id)
    {
        return view('ticket');
    }
}
