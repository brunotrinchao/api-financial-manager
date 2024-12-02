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
        Issuer::create(['name' => 'American Express', 'logo' => 'https://example.com/logos/amex.jpg']);
        Issuer::create(['name' => 'Elo', 'logo' => 'https://example.com/logos/elo.jpg']);
        Issuer::create(['name' => 'Hipercard', 'logo' => 'https://example.com/logos/hipercard.jpg']);
        Issuer::create(['name' => 'Diners Club', 'logo' => 'https://example.com/logos/dinersclub.jpg']);
        Issuer::create(['name' => 'Discover', 'logo' => 'https://example.com/logos/discover.jpg']);
        Issuer::create(['name' => 'JCB', 'logo' => 'https://example.com/logos/jcb.jpg']);
        Issuer::create(['name' => 'Aura', 'logo' => 'https://example.com/logos/aura.jpg']);
        Issuer::create(['name' => 'UnionPay', 'logo' => 'https://example.com/logos/unionpay.jpg']);
        Issuer::create(['name' => 'Cabal', 'logo' => 'https://example.com/logos/cabal.jpg']);
        Issuer::create(['name' => 'Banescard', 'logo' => 'https://example.com/logos/banescard.jpg']);

    }
}
