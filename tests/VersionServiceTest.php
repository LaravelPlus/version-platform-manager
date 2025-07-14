<?php

namespace LaravelPlus\VersionPlatformManager\Tests;

use LaravelPlus\VersionPlatformManager\Services\VersionService;
use LaravelPlus\VersionPlatformManager\Models\PlatformVersion;
use LaravelPlus\VersionPlatformManager\Models\UserVersion;
use LaravelPlus\VersionPlatformManager\Models\WhatsNew;
use Tests\TestCase;
use App\Models\User;

class VersionServiceTest extends TestCase
{
    protected VersionService $versionService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->versionService = app(VersionService::class);
    }

    /** @test */
    public function it_can_create_platform_version()
    {
        $data = [
            'version' => '1.0.0',
            'title' => 'Test Version',
            'description' => 'Test description',
            'is_active' => true,
        ];

        $version = $this->versionService->createPlatformVersion($data);

        $this->assertInstanceOf(PlatformVersion::class, $version);
        $this->assertEquals('1.0.0', $version->version);
        $this->assertEquals('Test Version', $version->title);
    }

    /** @test */
    public function it_can_create_whats_new_content()
    {
        // Create a platform version first
        $platformVersion = PlatformVersion::create([
            'version' => '1.0.0',
            'title' => 'Test Version',
            'is_active' => true,
        ]);

        $data = [
            'platform_version_id' => $platformVersion->id,
            'title' => 'Test Feature',
            'content' => 'Test content',
            'type' => 'feature',
            'is_active' => true,
        ];

        $whatsNew = $this->versionService->createWhatsNew($data);

        $this->assertInstanceOf(WhatsNew::class, $whatsNew);
        $this->assertEquals('Test Feature', $whatsNew->title);
        $this->assertEquals('feature', $whatsNew->type);
    }

    /** @test */
    public function it_can_validate_version_format()
    {
        $this->assertTrue($this->versionService->isValidVersion('1.0.0'));
        $this->assertTrue($this->versionService->isValidVersion('2.1.5'));
        $this->assertFalse($this->versionService->isValidVersion('1.0'));
        $this->assertFalse($this->versionService->isValidVersion('invalid'));
    }

    /** @test */
    public function it_can_compare_versions()
    {
        $this->assertEquals(-1, $this->versionService->compareVersions('1.0.0', '1.1.0'));
        $this->assertEquals(1, $this->versionService->compareVersions('2.0.0', '1.9.9'));
        $this->assertEquals(0, $this->versionService->compareVersions('1.0.0', '1.0.0'));
    }

    /** @test */
    public function it_can_get_user_version()
    {
        $user = User::factory()->create();
        
        $userVersion = $this->versionService->getUserVersion($user);

        $this->assertInstanceOf(UserVersion::class, $userVersion);
        $this->assertEquals($user->id, $userVersion->user_id);
        $this->assertEquals(config('version-platform-manager.default_user_version', '1.0.0'), $userVersion->version);
    }

    /** @test */
    public function it_can_update_user_version()
    {
        $user = User::factory()->create();
        $newVersion = '2.0.0';

        $this->versionService->updateUserVersion($user, $newVersion);

        $userVersion = $this->versionService->getUserVersion($user);
        $this->assertEquals($newVersion, $userVersion->version);
    }
} 