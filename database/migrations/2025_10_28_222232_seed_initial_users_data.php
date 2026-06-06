<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Inserta usuarios iniciales
        DB::table('users')->insert([
            [
                'name' => 'gina',
                'email' => 'gina@gmail.com',
                'email_verified_at' => now(),
                'password' => bcrypt('12345gin&%$'),
                'carnet' => '13676364',
                'telefono' => '69846061',
                'apaterno' => 'Bayon',
                'amaterno' => 'Medina',
                'role' => 'profe',
            ],
            [
                'name' => 'yun',
                'email' => 'yunaice2018@gmail.com',
                'email_verified_at' => now(),
                'password' => bcrypt('12345yun&%$'),
                'carnet' => '13676364',
                'telefono' => '69846061',
                'apaterno' => 'Bayon',
                'amaterno' => 'Medina',
                'role' => 'profe',
            ],
            [
                'name' => 'Ginaluz',
                'email' => 'ginabayon2017@gmail.com',
                'email_verified_at' => now(),
                'password' => bcrypt('12345gin&%$'),
                'carnet' => '13676364',
                'telefono' => '69846061',
                'apaterno' => 'Bayon',
                'amaterno' => 'Medina',
                'role' => 'admin',
            ],
            [
                'name' => 'Eliana',
                'email' => 'gbayonm@fcpn.edu.bo',
                'email_verified_at' => now(),
                'password' => bcrypt('12345gin&%$'),
                'carnet' => '1212313213',
                'telefono' => '69846061',
                'apaterno' => 'calle',
                'amaterno' => 'Chauca',
                'role' => 'profe',
            ],

        ]);
    }

    public function down(): void
    {
        // Elimina los datos insertados si se revierte la migración
        DB::table('users')->whereIn('email', [
            'gina@gmail.com',
            'yunaice2018@gmail.com',
            'ginabayon2017@gmail.com',
            'gbayonm@fcpn.edu.bo',
        ])->delete();
    }
};
