<?php

namespace App\Http\Controllers;

use App\Models\SecurityType;
use App\Services\SecurityPricesSync;
use Illuminate\Http\Request;

class SecurityPricesController extends Controller
{
    public function updateOrCreate(Request $request, SecurityType $securityType)
    {
        $security_type = $request->query('security_type');

        $typeCollection = $securityType->where('slug', '=', $security_type)->get();
        if ($typeCollection->isNotEmpty()) {
            $type = $typeCollection[0];
            SecurityPricesSync::dispatch($type);
            return response(json_encode(['msg' => 'ok']), 200);
        }
        return response(['msg' => 'No Securities to update or create of the type ' . $security_type], 404);

    }

}
