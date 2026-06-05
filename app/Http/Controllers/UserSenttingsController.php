<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSenttingsController extends Controller
{
    public function NewPassword()
    {
        return view('perfil.editar_perfil');
    }

    public function changePassword(Request $request)
    {
        $user = Auth::user();
        $userId = $user->id;
        $userEmail = $user->email;
        $userPassword = $user->password;

        if ($request->password_actual != '') {
            $NuewPass = $request->password;
            $confirPass = $request->confirm_password;
            $name = $request->name;

            // Verify if the current password matches the user's password in session
            if (Hash::check($request->password_actual, $userPassword)) {
                // Validate that both passwords are equal
                if ($NuewPass == $confirPass) {
                    // Validate that the password is at least 8 characters long and contains at least one special character
                    if (strlen($NuewPass) >= 8 && preg_match('/[^\w]/', $NuewPass)) {
                        $user->password = Hash::make($NuewPass);
                        DB::table('users')
                            ->where('id', $user->id)
                            ->update(['password' => $user->password, 'name' => $name]);

                        return redirect()->back()->with('updateClave', 'La clave fue cambiada correctamente.');
                    } else {
                        return redirect()->back()->with('clavemenor', 'Recuerde la clave debe ser mayor a 8 digitos y contener al menos un carácter especial.');
                    }
                } else {
                    return redirect()->back()->with('claveIncorrecta', 'Por favor verifique las claves no coinciden.');
                }
            } else {
                return back()->withErrors(['password_actual' => 'La Clave no Coinciden']);
            }
        } else {
            $name = $request->name;
            $sqlBDUpdateName = DB::table('users')
                ->where('id', $user->id)
                ->update(['name' => $name]);

            return redirect()->back()->with('name', 'El nombre fue cambiado correctamente.');
        }
    }
}
