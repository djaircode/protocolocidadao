<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;

class SetoresTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('setors')->insert([
            'codigo' => '0001',
            'descricao' => 'Recursos Humanos',
            'contato' => '9898',
        ]);

        DB::table('setors')->insert([
            'codigo' => '0002',
            'descricao' => 'TI',
            'contato' => '1243, 1244, 1245',
        ]);

        DB::table('setors')->insert([
            'codigo' => '0003',
            'descricao' => 'Comunicação',
            'contato' => '1827',
        ]);

        DB::table('setors')->insert([
            'codigo' => '0004',
            'descricao' => 'Pagamentos',
            'contato' => '8978, 7171 (algum texto)',
        ]);

        DB::table('setors')->insert([
            'codigo' => '0005',
            'descricao' => 'Transporte',
            'contato' => '2321',
        ]);

        DB::table('setors')->insert([
            'codigo' => '0006',
            'descricao' => 'Compras',
            'contato' => '7898',
        ]);
    }
}
