<?php

declare(strict_types=1);

namespace LaravelPlus\VersionPlatformManager\Tests;

use Illuminate\Foundation\Auth\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use LaravelPlus\VersionPlatformManager\Providers\VersionPlatformManagerServiceProvider;
use Orchestra\Testbench\TestCase;

final class AdminGateTest extends TestCase
{
    use RefreshDatabase;

    protected function getPackageProviders($app): array
    {
        return [VersionPlatformManagerServiceProvider::class];
    }

    protected function defineDatabaseMigrations(): void
    {
        // package migration alters `users`, so it must exist first
        \Illuminate\Support\Facades\Schema::create('users', function ($t): void {
            $t->id();
            $t->string('name')->nullable();
            $t->timestamps();
        });
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }

    protected function defineRoutes($router): void
    {
        \Illuminate\Support\Facades\Route::get('/login', fn () => 'login')->name('login');
    }

    protected function defineEnvironment($app): void
    {
        $app['config']->set('database.default', 'testing');
        $app['config']->set('app.key', 'base64:'.base64_encode(str_repeat('a', 32)));
    }

    public function test_admin_routes_are_404_for_authenticated_users_without_the_gate(): void
    {
        $this->actingAs((new User)->forceFill(['id' => 1]))
            ->get('/admin/version-manager/users')
            ->assertNotFound();
    }

    public function test_admin_routes_open_when_host_defines_the_gate(): void
    {
        Gate::define('viewVersionManager', fn () => true);

        // next-version also proves the route is no longer shadowed by versions/{version}
        $this->actingAs((new User)->forceFill(['id' => 1]))
            ->getJson('/admin/version-manager/versions/next-version')
            ->assertSuccessful();
    }

    public function test_whats_new_page_redirects_guests_instead_of_500(): void
    {
        $this->get('/whats-new')->assertRedirect();
    }
}
