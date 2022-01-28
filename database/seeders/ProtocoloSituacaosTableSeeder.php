<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProtocoloSituacaosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('protocolo_situacaos')->insert([
            'id' => 1,
            'descricao' => 'Aberto',
        ]);
        DB::table('protocolo_situacaos')->insert([
            'id' => 2,
            'descricao' => 'Em Tramitação',
        ]);
        DB::table('protocolo_situacaos')->insert([
            'id' => 3,
            'descricao' => 'Concluido',
        ]);
        DB::table('protocolo_situacaos')->insert([
            'id' => 4,
            'descricao' => 'Cancelado',
        ]);
    }
}
