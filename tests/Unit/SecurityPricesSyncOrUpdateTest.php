<?php

use App\Services\SecurityPricesSync;
use App\Models\SecurityPrice;
use App\Models\SecurityType;
use App\Models\Security;
use App\Services\RequestSecurityPrice;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class SecurityPricesSyncOrUpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_update_prices()
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
        $syncService = new SecurityPricesSync($securityType);
        $syncService->handle($security);

        $this->assertDatabaseHas('security_prices', [
            'security_id' => $security->id,
            'last_price' => $dataFromApi['price'],
        ]);

        Queue::assertNotPushed(SecurityPricesSync::class);
    }

    public function test_create_prices_when_not_exists()
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

        $this->assertDatabaseMissing('security_prices', [
            'security_id' => $security->id,
        ]);

        $syncService = new SecurityPricesSync($securityType);
        $syncService->handle($security);

        $this->assertDatabaseHas(
            'security_prices',
            [
                'security_id' => $security->id,
                'last_price' => $dataFromApi['price'],
            ]
        );

        Queue::assertNotPushed(SecurityPricesSync::class);
    }
}
