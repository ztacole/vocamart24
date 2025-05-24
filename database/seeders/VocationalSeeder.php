<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VocationalSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('vocationals')->insert([
            ['name' => 'Rekayasa Perangkat Lunak'],
            ['name' => 'Tata Boga'],
            ['name' => 'Tata Busana'],
            ['name' => 'Perhotelan'],
            ['name' => 'Usaha Layanan Wisata'],
        ]);
    }
}

