<?php

namespace Tests\Feature;

use App\Models\Organization;
use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class OrganizerRegistrationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        Role::firstOrCreate(['slug' => 'customer'], ['name' => 'Customer']);
        Role::firstOrCreate(['slug' => 'organizer_owner'], ['name' => 'Organizer Owner']);
        $superAdminRole = Role::firstOrCreate(['slug' => 'super_admin'], ['name' => 'Super Admin']);
        
        $permission = \App\Models\Permission::firstOrCreate(
            ['slug' => 'organizers.approve'],
            ['name' => 'organizers.approve', 'group' => 'Admin']
        );
        $superAdminRole->permissions()->syncWithoutDetaching([$permission->id]);
    }

    public function test_user_can_submit_organizer_registration(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $user->roles()->attach(Role::where('slug', 'customer')->first());

        $file = UploadedFile::fake()->image('logo.jpg');

        $this->withoutExceptionHandling();
        $response = $this->actingAs($user)->post(route('organizer.register'), [
            'name' => 'My Awesome Org',
            'email' => 'contact@awesomeorg.com',
            'phone' => '08123456789',
            'website' => 'https://awesomeorg.com',
            'address' => '123 Main Street',
            'description' => 'We organize awesome events and this is a very long description that has at least fifty characters in it.',
            'logo' => $file,
            'ktp_document' => UploadedFile::fake()->image('ktp.jpg')
        ]);

        $response->assertRedirect();
        
        $this->assertDatabaseHas('organizations', [
            'owner_id' => $user->id,
            'name' => 'My Awesome Org',
            'status' => 'pending'
        ]);
        
        // Also check if the logo was saved
        $org = Organization::where('name', 'My Awesome Org')->first();
        Storage::disk('public')->assertExists($org->logo);
    }

    public function test_admin_can_approve_organizer_registration(): void
    {
        $admin = User::factory()->create();
        $admin->roles()->attach(Role::where('slug', 'super_admin')->first());
        
        $user = User::factory()->create();
        
        $org = Organization::factory()->create([
            'owner_id' => $user->id,
            'status' => 'pending'
        ]);

        $this->withoutExceptionHandling();
        $response = $this->actingAs($admin)->patch(route('admin.organizations.approve', $org), [
            'action' => 'approve'
        ]);

        // Ensure no error is thrown (like missing Notification class)
        $response->assertSessionMissing('error');
        $response->assertSessionHas('success');
        $response->assertSessionHasNoErrors();
        $response->assertRedirect();
        
        $this->assertDatabaseHas('organizations', [
            'id' => $org->id,
            'status' => 'approved'
        ]);
        
        // Ensure user gets the organizer role
        $this->assertTrue($user->hasRole('organizer_owner'));
    }
}
