<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CreditCard extends Model
{
    protected $table = 'credit_cards';
    protected $fillable = ['user_id', 'issuer_id', 'name', 'limit', 'available_limit'];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function issuer()
    {
        return $this->belongsTo(Issuer::class);
    }
}
