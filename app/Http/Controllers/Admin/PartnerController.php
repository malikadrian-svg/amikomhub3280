<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use Illuminate\Http\Request;

class PartnerController extends Controller
{
    /**
     * Menampilkan daftar partner dengan fitur pencarian.
     */
    public function index(Request $request)
    {
        $query = Partner::query();

        if ($request->filled('search')) {
            $query->where('name', 'LIKE', '%' . $request->search . '%');
        }

        $partners = $query->latest()->get();

        return view('admin.partners.index', compact('partners'));
    }

    /**
     * Menampilkan form tambah partner.
     */
    public function create()
    {
        return view('admin.partners.create');
    }

    /**
     * Menyimpan data partner baru ke database.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'logo_url' => 'required|url',
        ]);

        Partner::create($data);

        return redirect()->route('admin.partners.index')->with('success', 'Data Partner berhasil ditambahkan.');
    }

    /**
     * Menampilkan form edit partner.
     */
    public function edit(Partner $partner)
    {
        return view('admin.partners.edit', compact('partner'));
    }

    /**
     * Memperbarui data partner di database.
     */
    public function update(Request $request, Partner $partner)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'logo_url' => 'required|url',
        ]);

        $partner->update($data);

        return redirect()->route('admin.partners.index')->with('success', 'Rincian data partner berhasil diperbarui.');
    }

    /**
     * Menghapus data partner dari database.
     */
    public function destroy(Partner $partner)
    {
        $partner->delete();

        return redirect()->route('admin.partners.index')->with('success', 'Data partner berhasil dihapus secara permanen.');
    }
}
