<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        Category::create(['name' => 'Sem categoria', 'slug' => 'SEM_CATEGORIA']);
        Category::create(['name' => 'Alimentação', 'slug' => 'ALIMENTACAO']);
        Category::create(['name' => 'Transporte', 'slug' => 'TRANSPORTE']);
        Category::create(['name' => 'Moradia', 'slug' => 'MORADIA']);
        Category::create(['name' => 'Educação', 'slug' => 'EDUCACAO']);
        Category::create(['name' => 'Lazer', 'slug' => 'LAZER']);
        Category::create(['name' => 'Saúde', 'slug' => 'SAUDE']);
        Category::create(['name' => 'Vestuário', 'slug' => 'VESTUARIO']);
        Category::create(['name' => 'Despesas Domésticas', 'slug' => 'DESPESAS_DOMESTICAS']);
        Category::create(['name' => 'Investimentos', 'slug' => 'INVESTIMENTOS']);
        Category::create(['name' => 'Doações', 'slug' => 'DOACOES']);
        Category::create(['name' => 'Dívidas', 'slug' => 'DIVIDAS']);
        Category::create(['name' => 'Assinaturas e Serviços', 'slug' => 'ASSINATURAS_SERVICOS']);
        Category::create(['name' => 'Dívidas - Bruno', 'slug' => 'DIVIDAS_BRUNO']);
        Category::create(['name' => 'Dívidas - Taty', 'slug' => 'DIVIDAS_TATY']);
        Category::create(['name' => 'Dívidas - Loja', 'slug' => 'DIVIDAS_LOJA']);
        Category::create(['name' => 'Outros', 'slug' => 'OUTROS']);

    }
}
