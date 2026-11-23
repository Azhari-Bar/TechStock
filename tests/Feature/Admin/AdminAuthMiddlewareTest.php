<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class AdminAuthMiddlewareTest extends TestCase
{
    use DatabaseTransactions;

    public function test_all_admin_routes_require_auth(): void
    {
        $routes = [
            route('admin.dashboard'),
            route('admin.categories.index'),
            route('admin.brands.index'),
            route('admin.products.index'),
        ];

        foreach ($routes as $route) {
            $this->get($route)->assertRedirect(route('login'));
        }
    }

    public function test_admin_routes_require_admin_role(): void
    {
        $user = User::factory()->create(['role' => 'staff', 'is_active' => true]);
        $routes = [
            route('admin.dashboard'),
            route('admin.categories.index'),
            route('admin.brands.index'),
            route('admin.products.index'),
        ];

        foreach ($routes as $route) {
            $this->actingAs($user)->get($route)->assertForbidden();
        }
    }
}
