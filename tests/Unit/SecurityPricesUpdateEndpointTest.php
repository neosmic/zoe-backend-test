<?php

namespace Tests\Feature\Controllers;

use App\Models\Security;
use App\Models\SecurityPrice;
use App\Models\SecurityType;
use App\Services\RequestSecurityPrice;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class SecurityPricesUpdateEndpointTest extends TestCase
{
    use RefreshDatabase;

    public function test_update_or_create()
    {
        Queue::fake();
        $slugTest = 'mutual_funds';
        $dataFromApi = RequestSecurityPrice::data($slugTest)[0];
        $securityType = SecurityType::factory()->create(['slug' => $slugTest]);
        $security = Security::factory()
            ->create([
                'security_type_id' => $securityType->id,
                'symbol' => $dataFromApi['symbol']
            ]);
        SecurityPrice::factory()->create(['security_id' => $security->id]);

        $response = $this->post(url('/api/securities/prices?security_type=mutual_funds'));
        $response->assertStatus(200);
        $response->assertExactJson(['msg' => 'ok']);

        Queue::assertPushed(\App\Services\SecurityPricesSync::class);
    }

    public function test_update_or_create_invalid_security_type()
    {
        Queue::fake();

        $response = $this->postJson('/api/securities/prices?security_type=invalid_type');
        $response->assertStatus(404);
        $response->assertExactJson(['msg' => 'No Securities to update or create of the type invalid_type']);

        Queue::assertNotPushed(\App\Services\SecurityPricesSync::class);
    }
}
