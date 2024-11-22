<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Issuer extends Model
{
    protected $table = "issuers";
    protected $fillable = ['name', 'logo'];

    public function creditCards()
    {
        return $this->hasMany(CreditCard::class);
    }
}
