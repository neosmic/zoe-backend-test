<?php
namespace App\Services;

use App\Models\SecurityPrice;
use App\Models\SecurityType;
use Illuminate\Contracts\Queue\ShouldQueue;

class SecurityPricesSync {
    public function handle(SecurityType $securityType){

        $data = RequestSecurityPrice::data();
        foreach ($data as $datum){
            $security = $securityType->securityBySymbol($datum['symbol']);

            $securityPrice = $security->securityPrice;
            $savingData = [
                'last_price' => $datum['price'],
                'as_of_date' => $datum['last_price_datetime']
            ];
            if ($securityPrice) {
                $securityPrice->update($savingData);
            }else{
                $savingData['security_id'] = $security->id;
                SecurityPrice::create($savingData);
            }
        }
    }
}
