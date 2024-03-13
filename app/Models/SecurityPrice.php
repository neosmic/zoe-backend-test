<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class SecurityPrice extends Model
{
  use HasFactory;

  protected $fillable = [
    'last_price',
    'as_of_date',
    'security_id',
  ];

  public function security(): HasOne
  {
    return $this->hasOne(Security::class);
  }
}
