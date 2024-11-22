<?php

namespace Database\Seeders;

use App\Models\Bank;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        Bank::create(['name' => 'Bank of America', 'logo' => 'https://example.com/logos/boa.jpg']);
        Bank::create(['name' => 'Chase Bank', 'logo' => 'https://example.com/logos/chase.jpg']);
    }
}
