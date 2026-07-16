<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PlatformSetting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * Display the settings form.
     */
    public function index()
    {
        $settings = [
            'default_commission_rate' => PlatformSetting::get('default_commission_rate', 5.00),
            'platform_fee'            => PlatformSetting::get('platform_fee', 5000),
        ];

        return view('admin.settings.index', compact('settings'));
    }

    /**
     * Update the platform settings.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'default_commission_rate' => 'required|numeric|min:0|max:100',
            'platform_fee'            => 'required|integer|min:0',
        ]);

        PlatformSetting::set('default_commission_rate', $validated['default_commission_rate']);
        PlatformSetting::set('platform_fee', $validated['platform_fee']);

        return redirect()->route('admin.settings.index')
            ->with('success', 'Pengaturan platform berhasil diperbarui.');
    }
}
