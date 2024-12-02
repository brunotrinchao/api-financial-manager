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
        Bank::create(['name' => 'Banco do Brasil', 'logo' => 'https://example.com/logos/banco-do-brasil.jpg']);
        Bank::create(['name' => 'Caixa Econômica Federal', 'logo' => 'https://example.com/logos/caixa-economica.jpg']);
        Bank::create(['name' => 'Bradesco', 'logo' => 'https://example.com/logos/bradesco.jpg']);
        Bank::create(['name' => 'Itaú', 'logo' => 'https://example.com/logos/itau.jpg']);
        Bank::create(['name' => 'Santander', 'logo' => 'https://example.com/logos/santander.jpg']);
        Bank::create(['name' => 'BTG Pactual', 'logo' => 'https://example.com/logos/btg-pactual.jpg']);
        Bank::create(['name' => 'Banco Inter', 'logo' => 'https://example.com/logos/banco-inter.jpg']);
        Bank::create(['name' => 'Nubank', 'logo' => 'https://example.com/logos/nubank.jpg']);
        Bank::create(['name' => 'PagBank', 'logo' => 'https://example.com/logos/pagbank.jpg']);
        Bank::create(['name' => 'Mercado Pago', 'logo' => 'https://example.com/logos/mercado-pago.jpg']);

    }
}
