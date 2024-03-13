<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SecurityType extends Model
{
    use HasFactory;
    protected $fillable = [
        "slug",
        "name",
    ];

    public function securities(): HasMany
    {
        return $this->hasMany(Security::class);
    }
    public function securityBySymbol($symbol): ?Security
    {
        return $this->securities->where('symbol', $symbol)->get();
    }
}
