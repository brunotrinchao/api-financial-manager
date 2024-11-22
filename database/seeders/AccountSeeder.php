<?php

namespace Database\Seeders;

use App\Models\Account;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        Account::create(['user_id' => 1, 'bank_id' => 1, 'name' => 'Main Account', 'balance' => 1000.00]);
        Account::create(['user_id' => 2, 'bank_id' => 2, 'name' => 'Savings Account', 'balance' => 5000.00]);
    }
}
