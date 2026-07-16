<?php

namespace Tests\Feature;

use App\Models\Organization;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RBACTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        Role::firstOrCreate(['slug' => 'customer'], ['name' => 'Customer']);
        Role::firstOrCreate(['slug' => 'super_admin'], ['name' => 'Super Admin']);
        Role::firstOrCreate(['slug' => 'organizer_owner'], ['name' => 'Organizer Owner']);
    }

    public function test_customer_cannot_access_admin_dashboard(): void
    {
        $user = User::factory()->create();
        $user->roles()->attach(Role::where('slug', 'customer')->first());

        $response = $this->actingAs($user)->get(route('admin.dashboard'));
        
        // Ensure redirected to home or forbidden
        $response->assertStatus(302);
        $response->assertRedirect('/');
    }

    public function test_super_admin_can_access_admin_dashboard(): void
    {
        $user = User::factory()->create();
        $user->roles()->attach(Role::where('slug', 'super_admin')->first());

        $response = $this->actingAs($user)->get(route('admin.dashboard'));
        
        $response->assertStatus(200);
    }
    
    public function test_customer_cannot_access_organizer_dashboard(): void
    {
        $user = User::factory()->create();
        $user->roles()->attach(Role::where('slug', 'customer')->first());
        
        // Mock an organization slug
        $response = $this->actingAs($user)->get(route('organizer.dashboard', ['organization' => 'fake-org']));
        
        // EnsureOrganization middleware checks for valid org first, then we need to ensure they can't access anyway.
        // If the org doesn't exist, it might 404. Let's create an org.
        $org = Organization::factory()->create(['status' => 'approved']);
        
        $response = $this->actingAs($user)
                         ->withSession(['active_organization_id' => $org->id])
                         ->get(route('organizer.dashboard', ['organization' => $org->slug]));
        
        // Should be forbidden because they don't own it or don't have the role
        $response->assertStatus(403);
    }

    public function test_super_admin_with_permission_can_access_event_approvals(): void
    {
        $user = User::factory()->create();
        $adminRole = Role::where('slug', 'super_admin')->first();
        $user->roles()->attach($adminRole);

        // Create the permission and assign it to the role
        $permission = \App\Models\Permission::firstOrCreate(
            ['slug' => 'events.approve'],
            ['name' => 'Approve Events', 'group' => 'events']
        );
        $adminRole->permissions()->syncWithoutDetaching([$permission->id]);

        $response = $this->actingAs($user)->get(route('admin.event-approvals.index'));
        
        $response->assertStatus(200);
    }

    public function test_super_admin_without_permission_cannot_access_event_approvals(): void
    {
        $user = User::factory()->create();
        $adminRole = Role::where('slug', 'super_admin')->first();
        $user->roles()->attach($adminRole);

        // Explicitly remove permission from the role if it exists
        $permission = \App\Models\Permission::where('slug', 'events.approve')->first();
        if ($permission) {
            $adminRole->permissions()->detach($permission->id);
        }

        $response = $this->actingAs($user)->get(route('admin.event-approvals.index'));
        
        $response->assertStatus(403);
    }

    public function test_customer_cannot_access_event_approvals(): void
    {
        $user = User::factory()->create();
        $user->roles()->attach(Role::where('slug', 'customer')->first());

        $response = $this->actingAs($user)->get(route('admin.event-approvals.index'));
        
        // The IsAdmin middleware will redirect customer to '/'
        $response->assertStatus(302);
        $response->assertRedirect('/');
    }
}
