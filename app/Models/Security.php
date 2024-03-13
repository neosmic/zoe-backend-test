<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Security extends Model
{
    use HasFactory;
    protected $fillable = [
        'symbol',
        'security_type_id'
    ];

    public function securityPrice(): HasOne
    {
        return $this->hasOne(SecurityPrice::class);
    }

    public function securityType(): BelongsTo
    {
        return $this->belongsTo(SecurityType::class);
    }
    public function securityBySymbol($symbol)
    {
     $securities = $this->where('symbol', '=', $symbol)->get();
     return $securities;
    }
}
