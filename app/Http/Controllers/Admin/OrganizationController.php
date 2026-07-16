<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\OrganizationDocument;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class OrganizationController extends Controller
{
    /**
     * Show all organizations (approved, pending, suspended).
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Organization::class);

        $query = Organization::with('owner');

        if ($request->filled('search')) {
            $query->where('name', 'LIKE', '%' . $request->search . '%')
                  ->orWhere('email', 'LIKE', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Show pending organizations first, then by latest
        $organizations = $query->orderByRaw("FIELD(status, 'pending', 'approved', 'suspended')")
                               ->latest()
                               ->paginate(15);

        return view('admin.organizations.index', compact('organizations'));
    }

    /**
     * Approve a pending organization.
     */
    public function approve(Organization $organization)
    {
        $this->authorize('update', $organization);

        try {
            DB::beginTransaction();

            $organization->update([
                'status'      => 'approved',
                'approved_at' => now(),
                'approved_by' => auth()->id(),
            ]);

            // Assign the organizer_owner role to the owner if they don't have it
            $ownerRole = Role::where('slug', 'organizer_owner')->first();
            $owner = $organization->owner;

            if ($ownerRole && $owner && !$owner->roles->contains($ownerRole->id)) {
                $owner->roles()->attach($ownerRole->id);
            }

            // Send Notification to Owner
            if ($owner) {
                $owner->notify(new \App\Notifications\OrganizationApprovedNotification($organization));
            }

            DB::commit();

            return back()->with('success', "Organisasi {$organization->name} berhasil disetujui.");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Suspend/Reject an organization.
     */
    public function suspend(Organization $organization)
    {
        $this->authorize('delete', $organization);

        $organization->update([
            'status' => 'suspended'
        ]);

        return back()->with('success', "Organisasi {$organization->name} telah dibekukan.");
    }

    /**
     * Download an organization's verification document securely.
     */
    public function downloadDocument(OrganizationDocument $document)
    {
        $this->authorize('viewAny', Organization::class);

        if (!Storage::exists($document->file_path)) {
            return back()->with('error', 'File dokumen tidak ditemukan di server.');
        }

        return Storage::download($document->file_path, $document->original_name);
    }
}
