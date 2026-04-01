<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WorkflowSmokeTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_access_public_listing_pages(): void
    {
        $this->get(route('listings.index'))->assertOk();
    }

    public function test_guest_is_redirected_from_authenticated_routes(): void
    {
        $this->get(route('listings.create'))->assertRedirect(route('login'));
        $this->get(route('messages.index'))->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_access_user_workflow_pages(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertOk();

        $this->actingAs($user)
            ->get(route('listings.create'))
            ->assertOk();

        $this->actingAs($user)
            ->get(route('listings.my'))
            ->assertOk();
    }

    public function test_non_admin_user_cannot_access_admin_dashboard(): void
    {
        $user = User::factory()->create(['is_admin' => false]);

        $this->actingAs($user)
            ->get(route('admin.dashboard'))
            ->assertForbidden();
    }

    public function test_admin_user_can_access_admin_dashboard(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertOk();
    }
}
