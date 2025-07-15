<?php

namespace LaravelPlus\VersionPlatformManager\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use LaravelPlus\VersionPlatformManager\Models\PlatformVersion;
use LaravelPlus\VersionPlatformManager\Models\UserVersion;
use LaravelPlus\VersionPlatformManager\Models\WhatsNew;
use App\Models\User;

class PublicWhatsNewControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_guest_redirected_to_login()
    {
        $response = $this->get('/whats-new');
        
        $response->assertRedirect('/login');
    }

    public function test_authenticated_user_can_access_whats_new_page()
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)->get('/whats-new');
        
        $response->assertStatus(200);
        $response->assertViewIs('version-platform-manager::whats-new.page');
    }

    public function test_mark_as_read_requires_authentication()
    {
        $response = $this->post('/whats-new/mark-read', [
            'version_id' => 1
        ]);
        
        $response->assertRedirect('/login');
    }

    public function test_authenticated_user_can_mark_version_as_read()
    {
        $user = User::factory()->create();
        $version = PlatformVersion::factory()->create([
            'version' => '2.0.0',
            'is_active' => true
        ]);
        
        // Create user version record
        UserVersion::create([
            'user_id' => $user->id,
            'version' => '1.0.0'
        ]);

        $response = $this->actingAs($user)->post('/whats-new/mark-read', [
            'version_id' => $version->id
        ]);
        
        $response->assertRedirect();
        $response->assertSessionHas('success', 'Marked as read successfully.');
        
        // Verify the user's last seen version was updated
        $userVersion = UserVersion::where('user_id', $user->id)->first();
        $this->assertEquals('2.0.0', $userVersion->last_seen_version);
    }

    public function test_mark_as_read_with_invalid_version_id()
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)->post('/whats-new/mark-read', [
            'version_id' => 999 // Non-existent version
        ]);
        
        $response->assertSessionHasErrors('version_id');
    }
} 