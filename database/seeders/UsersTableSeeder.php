<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Administrador',
            'email' => 'adm@mail.com',
            'active' => 'Y',
            'password' => Hash::make('123456'),
            'setor_id' => 1,
        ]);

        DB::table('users')->insert([
            'name' => 'Gerente',
            'email' => 'gerente@mail.com',
            'active' => 'Y',
            'password' => Hash::make('123456'),
            'setor_id' => 1,
        ]);

        DB::table('users')->insert([
            'name' => 'Operador',
            'email' => 'operador@mail.com',
            'active' => 'Y',
            'password' => Hash::make('123456'),
            'setor_id' => 1,
        ]);

        DB::table('users')->insert([
            'name' => 'Leitor',
            'email' => 'leitor@mail.com',
            'active' => 'Y',
            'password' => Hash::make('123456'),
            'setor_id' => 1,
        ]);

        /*  usuarios para testes*/
        /*
            maria@mail.com
            abadia@mail.com
            joana@mail.com
            tulio@mail.com
            erica@mail.com
            neuza@mail.com
            diana@mail.com
            karina@mail.com
            antonio@mail.com

        */
        DB::table('users')->insert([
            'name' => 'Maria da Silva',
            'email' => 'maria@mail.com',
            'active' => 'Y',
            'password' => Hash::make('123456'),
            'setor_id' => 2,
        ]);
        DB::table('users')->insert([
            'name' => 'Abadia Almeida dos Santos',
            'email' => 'abadia@mail.com',
            'active' => 'Y',
            'password' => Hash::make('123456'),
            'setor_id' => 2,
        ]);
        DB::table('users')->insert([
            'name' => 'Joana Horta Lousã',
            'email' => 'joana@mail.com',
            'active' => 'Y',
            'password' => Hash::make('123456'),
            'setor_id' => 3,
        ]);
        DB::table('users')->insert([
            'name' => 'Túlio Leiria César',
            'email' => 'tulio@mail.com',
            'active' => 'Y',
            'password' => Hash::make('123456'),
            'setor_id' => 3,
        ]);
        DB::table('users')->insert([
            'name' => 'Erica Travassos Luz',
            'email' => 'erica@mail.com',
            'active' => 'Y',
            'password' => Hash::make('123456'),
            'setor_id' => 1,
        ]);
        DB::table('users')->insert([
            'name' => 'Neuza Souto Maior Bouças',
            'email' => 'neuza@mail.com',
            'active' => 'Y',
            'password' => Hash::make('123456'),
            'setor_id' => 1,
        ]);
        DB::table('users')->insert([
            'name' => 'Diana Castanho Grilo',
            'email' => 'diana@mail.com',
            'active' => 'Y',
            'password' => Hash::make('123456'),
            'setor_id' => 1,
        ]);
        DB::table('users')->insert([
            'name' => 'Karina Valcácer Ulhoa',
            'email' => 'karina@mail.com',
            'active' => 'Y',
            'password' => Hash::make('123456'),
            'setor_id' => 4,
        ]);
        DB::table('users')->insert([
            'name' => 'Antonio Portugal Quina',
            'email' => 'antonio@mail.com',
            'active' => 'Y',
            'password' => Hash::make('123456'),
            'setor_id' => 6,
        ]);
        DB::table('users')->insert([
            'name' => 'Manuel Caldas Veleda',
            'email' => 'manuel@mail.com',
            'active' => 'Y',
            'password' => Hash::make('123456'),
            'setor_id' => 6,
        ]);

    }
}
