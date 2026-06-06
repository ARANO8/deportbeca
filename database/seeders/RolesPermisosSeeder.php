<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesPermisosSeeder extends Seeder
{
    /**
     * Los módulos del sistema sobre los que se controla acceso.
     * Deben coincidir exactamente con los definidos en PrivilegioController.
     */
    private const MODULOS = [
        'usuarios',
        'carreras',
        'disciplinas',
        'lugares',
        'preinscripciones',
        'fixture',
        'calificaciones',
        'eventos',
        'roles',
        'privilegios',
    ];

    /**
     * Definición de roles con sus permisos por módulo.
     * Claves: ver | crear | editar | eliminar
     */
    private const ROLES = [
        [
            'nombre' => 'Administrador',
            'descripcion' => 'Acceso total al sistema — todos los módulos y acciones',
            'status' => 'active',
            'es_super_admin' => true,
            // admin tiene todos los permisos en todos los módulos
            'permisos_modulos' => 'todos',
        ],
        [
            'nombre' => 'Secretario',
            'descripcion' => 'Gestión de pre-inscripciones y archivador. Solo lectura en el resto.',
            'status' => 'active',
            'es_super_admin' => false,
            'permisos_modulos' => [
                'usuarios' => ['ver' => false, 'crear' => false, 'editar' => false, 'eliminar' => false],
                'carreras' => ['ver' => true,  'crear' => false, 'editar' => false, 'eliminar' => false],
                'disciplinas' => ['ver' => true,  'crear' => false, 'editar' => false, 'eliminar' => false],
                'lugares' => ['ver' => true,  'crear' => false, 'editar' => false, 'eliminar' => false],
                'preinscripciones' => ['ver' => true,  'crear' => false, 'editar' => true,  'eliminar' => false],
                'fixture' => ['ver' => true,  'crear' => false, 'editar' => false, 'eliminar' => false],
                'calificaciones' => ['ver' => true,  'crear' => false, 'editar' => false, 'eliminar' => false],
                'eventos' => ['ver' => true,  'crear' => false, 'editar' => false, 'eliminar' => false],
                'roles' => ['ver' => false, 'crear' => false, 'editar' => false, 'eliminar' => false],
                'privilegios' => ['ver' => false, 'crear' => false, 'editar' => false, 'eliminar' => false],
            ],
        ],
        [
            'nombre' => 'Instructor',
            'descripcion' => 'Gestión de fixture y calificaciones. Lectura de preinscripciones.',
            'status' => 'active',
            'es_super_admin' => false,
            'permisos_modulos' => [
                'usuarios' => ['ver' => false, 'crear' => false, 'editar' => false, 'eliminar' => false],
                'carreras' => ['ver' => true,  'crear' => false, 'editar' => false, 'eliminar' => false],
                'disciplinas' => ['ver' => true,  'crear' => false, 'editar' => false, 'eliminar' => false],
                'lugares' => ['ver' => true,  'crear' => false, 'editar' => false, 'eliminar' => false],
                'preinscripciones' => ['ver' => true,  'crear' => false, 'editar' => false, 'eliminar' => false],
                'fixture' => ['ver' => true,  'crear' => true,  'editar' => true,  'eliminar' => false],
                'calificaciones' => ['ver' => true,  'crear' => false, 'editar' => true,  'eliminar' => false],
                'eventos' => ['ver' => true,  'crear' => false, 'editar' => false, 'eliminar' => false],
                'roles' => ['ver' => false, 'crear' => false, 'editar' => false, 'eliminar' => false],
                'privilegios' => ['ver' => false, 'crear' => false, 'editar' => false, 'eliminar' => false],
            ],
        ],
    ];

    public function run(): void
    {
        foreach (self::ROLES as $rolData) {
            $permisosModulos = $rolData['permisos_modulos'];
            unset($rolData['permisos_modulos']);

            // Crear o actualizar el rol
            DB::table('roles')->updateOrInsert(
                ['nombre' => $rolData['nombre']],
                array_merge($rolData, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );

            $rolId = DB::table('roles')->where('nombre', $rolData['nombre'])->value('id');

            if ($permisosModulos === 'todos') {
                // Administrador: todos los permisos en todos los módulos
                foreach (self::MODULOS as $modulo) {
                    DB::table('rol_modulo_permiso')->updateOrInsert(
                        ['rol_id' => $rolId, 'modulo' => $modulo],
                        [
                            'rol_id' => $rolId,
                            'modulo' => $modulo,
                            'ver' => true,
                            'crear' => true,
                            'editar' => true,
                            'eliminar' => true,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]
                    );
                }
            } else {
                // Otros roles: permisos específicos por módulo
                foreach ($permisosModulos as $modulo => $permisos) {
                    DB::table('rol_modulo_permiso')->updateOrInsert(
                        ['rol_id' => $rolId, 'modulo' => $modulo],
                        array_merge($permisos, [
                            'rol_id' => $rolId,
                            'modulo' => $modulo,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ])
                    );
                }
            }
        }

        $this->command->info('Roles y permisos de módulo insertados correctamente.');
    }
}
