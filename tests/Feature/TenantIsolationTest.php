<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\Organization;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TenantIsolationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create necessary roles and permissions
        $role = Role::firstOrCreate(['slug' => 'organizer_owner'], ['name' => 'Organizer Owner']);
        Role::firstOrCreate(['slug' => 'customer'], ['name' => 'Customer']);

        $permissions = [
            'events.view',
            'events.create',
            'events.edit',
            'events.delete',
        ];
        foreach ($permissions as $perm) {
            $permission = \App\Models\Permission::firstOrCreate(
                ['slug' => $perm],
                ['name' => $perm, 'group' => 'Events']
            );
            $role->permissions()->syncWithoutDetaching([$permission->id]);
        }
    }

    public function test_organizer_cannot_edit_another_organizers_event(): void
    {
        // 1. Setup Organizer A
        $userA = User::factory()->create();
        $userA->roles()->attach(Role::where('slug', 'organizer_owner')->first());
        $orgA = Organization::factory()->create(['owner_id' => $userA->id, 'status' => 'approved']);
        $eventA = Event::factory()->create(['organization_id' => $orgA->id]);

        // 2. Setup Organizer B
        $userB = User::factory()->create();
        $userB->roles()->attach(Role::where('slug', 'organizer_owner')->first());
        $orgB = Organization::factory()->create(['owner_id' => $userB->id, 'status' => 'approved']);
        $eventB = Event::factory()->create(['organization_id' => $orgB->id]);

        // 3. Act & Assert: Organizer A tries to edit Event B
        $response = $this->actingAs($userA)
                         ->withSession(['active_organization_id' => $orgA->id])
                         ->get(route('organizer.events.edit', ['organization' => $orgA->slug, 'event' => $eventB->id]));

        // Since the Tenant scope or controller policy should block it, we expect a 403 or 404
        $response->assertStatus(403);
    }
    
    public function test_organizer_cannot_view_another_organizers_event_in_dashboard(): void
    {
        $userA = User::factory()->create();
        $userA->roles()->attach(Role::where('slug', 'organizer_owner')->first());
        $orgA = Organization::factory()->create(['owner_id' => $userA->id, 'status' => 'approved']);
        $eventA = Event::factory()->create(['organization_id' => $orgA->id, 'title' => 'Event A Title']);

        $userB = User::factory()->create();
        $orgB = Organization::factory()->create(['owner_id' => $userB->id, 'status' => 'approved']);
        $eventB = Event::factory()->create(['organization_id' => $orgB->id, 'title' => 'Event B Title']);

        $response = $this->actingAs($userA)
                         ->withSession(['active_organization_id' => $orgA->id])
                         ->get(route('organizer.events.index', ['organization' => $orgA->slug]));

        $response->assertStatus(200);
        $response->assertSee('Event A Title');
        $response->assertDontSee('Event B Title');
    }
}
