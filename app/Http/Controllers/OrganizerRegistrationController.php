<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrganizerRegistrationRequest;
use App\Models\Organization;
use App\Models\Role;
use App\Models\PlatformSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class OrganizerRegistrationController extends Controller
{
    /**
     * Show the registration form.
     */
    public function create()
    {
        // If the user already owns an organization, redirect them to dashboard
        if (Auth::user()->organizations()->wherePivot('role', 'owner')->exists()) {
            return redirect()->route('home')->with('info', 'Anda sudah memiliki organisasi.');
        }

        return view('organizer.register');
    }

    /**
     * Handle the registration submission.
     */
    public function store(StoreOrganizerRegistrationRequest $request)
    {
        $user = Auth::user();

        // Check if admin approval is required
        $requireApproval = PlatformSetting::get('require_organizer_approval', true);
        $status = $requireApproval ? 'pending' : 'approved';
        $approvedAt = $requireApproval ? null : now();
        $approvedBy = $requireApproval ? null : $user->id; // Auto-approved by self if no approval needed

        try {
            DB::beginTransaction();

            // 1. Create the Organization
            $organization = Organization::create([
                'owner_id'    => $user->id,
                'name'        => $request->name,
                'slug'        => Str::slug($request->name),
                'description' => $request->description,
                'email'       => $request->email,
                'phone'       => $request->phone,
                'website'     => $request->website,
                'address'     => $request->address,
                'status'      => $status,
                'approved_at' => $approvedAt,
                'approved_by' => $approvedBy,
            ]);

            // 2. Attach the user as the owner in the pivot table
            $organization->members()->attach($user->id, [
                'role' => 'owner'
            ]);

            // 3. If auto-approved, assign the RBAC role immediately
            if ($status === 'approved') {
                $ownerRole = Role::where('slug', 'organizer_owner')->first();
                if ($ownerRole && !$user->roles->contains($ownerRole->id)) {
                    $user->roles()->attach($ownerRole->id);
                }
            }

            // 4. Handle file uploads (KTP & Legal)
            $this->uploadDocument($request, $organization, 'ktp_document', 'KTP');
            
            if ($request->hasFile('legal_document')) {
                $this->uploadDocument($request, $organization, 'legal_document', 'Legalitas');
            }

            DB::commit();

            if ($status === 'pending') {
                return redirect()->route('home')->with('success', 'Pendaftaran berhasil dikirim. Tim kami akan meninjau pengajuan Anda dalam 1-2 hari kerja.');
            } else {
                return redirect()->route('organizer.dashboard', $organization->slug)
                    ->with('success', 'Organisasi berhasil dibuat!');
            }

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
        }
    }

    /**
     * Helper to upload and record a document.
     */
    private function uploadDocument(Request $request, Organization $organization, string $fieldName, string $typeLabel)
    {
        $file = $request->file($fieldName);
        
        // Store in a secure location (not public)
        $path = $file->store("organizations/{$organization->id}/documents");

        $organization->documents()->create([
            'type'          => $typeLabel,
            'file_path'     => $path,
            'original_name' => $file->getClientOriginalName(),
            'file_size'     => $file->getSize(),
        ]);
    }
}
