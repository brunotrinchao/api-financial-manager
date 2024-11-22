<?php

namespace Database\Seeders;

use App\Models\CreditCard;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CreditCardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CreditCard::create([
            'user_id' => 1,
            'issuer_id' => 1,
            'name' => 'Primary Visa',
            'limit' => 5000.00,
            'available_limit' => 4000.00,
        ]);

        CreditCard::create([
            'user_id' => 2,
            'issuer_id' => 2,
            'name' => 'Backup MasterCard',
            'limit' => 3000.00,
            'available_limit' => 2000.00,
        ]);
    }
}
