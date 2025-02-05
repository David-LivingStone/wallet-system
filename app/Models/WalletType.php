<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WalletType extends Model
{
    use HasFactory;

    protected $table = 'wallet_types';
    
    protected $fillable = [
        'name',
        'min_balance',
        'monthly_interest_rate',
    ];
}
