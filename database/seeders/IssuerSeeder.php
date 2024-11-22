<?php

namespace Database\Seeders;

use App\Models\Issuer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class IssuerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        Issuer::create(['name' => 'Visa', 'logo' => 'https://example.com/logos/visa.jpg']);
        Issuer::create(['name' => 'MasterCard', 'logo' => 'https://example.com/logos/mastercard.jpg']);
    }
}
