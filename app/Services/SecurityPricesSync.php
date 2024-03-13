<?php
namespace App\Services;

use App\Mail\SecurityPricesUpdated;
use App\Models\Security;
use App\Models\SecurityPrice;
use App\Models\SecurityType;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SecurityPricesSync implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    protected SecurityType $securityType;

    public function __construct($securityType)
    {
        $this->securityType = $securityType;
    }

    public function handle(Security $security)
    {
        $data = RequestSecurityPrice::data($this->securityType->slug);

        foreach ($data as $datum) {

            $symbol = $datum['symbol'];
            $security = $security->securityBySymbol($symbol)->first();
            if (!$security) {
                continue;
            }
            $savingData = [
                'last_price' => $datum['price'],
                'as_of_date' => $datum['last_price_datetime']
            ];
            $securityPrice = $security->securityPrice;
            if ($securityPrice) {
                $securityPrice->update($savingData);
            } else {
                $securityPrice = SecurityPrice::create([
                    ...$savingData,
                    'security_id' => $security->id
                ]);
            }
            if ($securityPrice->wasChanged(['last_price'])) {
                Mail::to('example@example.com')->send(new SecurityPricesUpdated($security));
            }
        }
    }
}
