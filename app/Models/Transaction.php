<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory;

    protected $table = 'transactions';
    
    protected $fillable = [
        'sender_wallet_id',
        'receiver_wallet_id',
        'amount',
    ];

    public function senderWallet()
    {
        return $this->belongsTo(UserWallet::class, 'sender_wallet_id');
    }

    public function receiverWallet()
    {
        return $this->belongsTo(UserWallet::class, 'receiver_wallet_id');
    }
}
