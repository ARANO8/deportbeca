<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Obtener el id del rol Administrador (debe existir — corre después de RolesPermisosSeeder)
        $rolAdminId = DB::table('roles')->where('nombre', 'Administrador')->value('id');

        $adminEmail = env('ADMIN_EMAIL', 'admin@deportbeca.edu.bo');
        $adminPass = env('ADMIN_PASSWORD', 'Admin2024$Dep');

        DB::table('users')->updateOrInsert(
            ['email' => $adminEmail],
            [
                'name' => 'Administrador',
                'email' => $adminEmail,
                'email_verified_at' => now(),
                'password' => Hash::make($adminPass),
                'apaterno' => 'Becas',
                'amaterno' => 'Deportes',
                'carnet' => '00000000',
                'telefono' => '0000000',
                'rol_id' => $rolAdminId,
                'status' => 'activo',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        $this->command->info("Usuario admin creado: {$adminEmail} / {$adminPass}");
        $this->command->warn('Cambia la contrasena en el primer inicio de sesion.');
    }
}
