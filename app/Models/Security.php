<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Security extends Model
{
    use HasFactory;

    public function securityPrice(): HasOne{
        return $this->hasOne(SecurityPrice::class);
    }
}
