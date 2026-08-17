<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class HealthCheckTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_returns_ok_status_when_database_and_cache_are_healthy(): void
    {
        $response = $this->getJson('/api/health');

        $response->assertOk()
            ->assertJsonStructure(['status', 'driver', 'checks' => ['database', 'cache']])
            ->assertJsonPath('status', 'ok')
            ->assertJsonPath('driver', 'sqlite')
            ->assertJsonPath('checks.database', true)
            ->assertJsonPath('checks.cache', true);
    }
}
