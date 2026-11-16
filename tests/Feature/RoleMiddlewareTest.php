<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class RoleMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Route::middleware(['web', 'auth', 'role:client'])->get('/_test/client-only', fn () => 'client page');
        Route::middleware(['web', 'auth', 'role:client,admin'])->get('/_test/client-or-admin', fn () => 'shared page');
    }

    public function test_client_can_open_a_client_page(): void
    {
        $this->actingAs(User::factory()->client()->create())
            ->get('/_test/client-only')
            ->assertOk()
            ->assertSee('client page');
    }

    public function test_runner_and_admin_are_forbidden_from_a_client_page(): void
    {
        $this->actingAs(User::factory()->runner()->create())
            ->get('/_test/client-only')
            ->assertForbidden();

        $this->actingAs(User::factory()->admin()->create())
            ->get('/_test/client-only')
            ->assertForbidden();
    }

    public function test_a_route_can_allow_several_roles(): void
    {
        $this->actingAs(User::factory()->admin()->create())
            ->get('/_test/client-or-admin')
            ->assertOk();

        $this->actingAs(User::factory()->runner()->create())
            ->get('/_test/client-or-admin')
            ->assertForbidden();
    }

    public function test_guest_is_sent_to_the_login_page(): void
    {
        $this->get('/_test/client-only')->assertRedirect(route('login'));
    }
}
