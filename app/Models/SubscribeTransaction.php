<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubscribeTransaction extends Model
{
  use HasFactory, SoftDeletes;

  protected $fillable = [
    'user_id',
    'code_swift',
    'total_amount',
    'is_paid',
    'subscription_start_date',
    'proof',
  ];

  public function user()
  {
    return $this->belongsTo(User::class);
  }
}
