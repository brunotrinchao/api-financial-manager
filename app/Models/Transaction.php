<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'id',
        'user_id',
        'category_id',
        'type',
        'method',
        'interval',
        'amount',
        'description',
        'transaction_date',
        'source_type',
        'source_id',
        'installment',
        'frequency',
    'status'];

    public function scopeFilter($query, $filters)
    {
        return $filters->apply($query);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function sourceAccount()
    {
        return $this->belongsTo(Account::class, 'source_id');
    }

    public function sourceCreditCard()
    {
        return $this->belongsTo(CreditCard::class, 'source_id');
    }

}
