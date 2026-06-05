<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserSenttingsController extends Controller
{
    public function NewPassword()
    {
        return view('perfil.editar_perfil');
    }

    public function changePassword(Request $request)
    {
        $user = Auth::user();
        $userPassword = $user->password;

        if ($request->password_actual != '') {
            $NuewPass = $request->password;
            $confirPass = $request->confirm_password;
            $name = $request->name;

            if (Hash::check($request->password_actual, $userPassword)) {
                if ($NuewPass == $confirPass) {
                    if (strlen($NuewPass) >= 8 && preg_match('/[^\w]/', $NuewPass)) {
                        $user->password = Hash::make($NuewPass);
                        DB::table('users')
                            ->where('id', $user->id)
                            ->update(['password' => $user->password, 'name' => $name]);

                        return redirect()->back()->with('updateClave', 'La clave fue cambiada correctamente.');
                    } else {
                        return redirect()->back()->with('clavemenor', 'Recuerde la clave debe ser mayor a 8 digitos y contener al menos un caracter especial.');
                    }
                } else {
                    return redirect()->back()->with('claveIncorrecta', 'Por favor verifique las claves no coinciden.');
                }
            } else {
                return back()->withErrors(['password_actual' => 'La Clave no Coinciden']);
            }
        } else {
            $name = $request->name;
            DB::table('users')
                ->where('id', $user->id)
                ->update(['name' => $name]);

            return redirect()->back()->with('name', 'El nombre fue cambiado correctamente.');
        }
    }

    public function editarPerfil()
    {
        $user = Auth::user();

        return view('perfil.editar_perfil', compact('user'));
    }

    public function actualizarPerfil(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|min:3|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'name.min' => 'El nombre debe tener al menos 3 caracteres.',
            'email.required' => 'El correo es obligatorio.',
            'email.email' => 'Ingrese un correo valido.',
            'email.unique' => 'Este correo ya esta en uso por otro usuario.',
            'foto.image' => 'El archivo debe ser una imagen.',
            'foto.mimes' => 'Solo se permiten imagenes JPG, PNG o WEBP.',
            'foto.max' => 'La imagen no debe superar 2MB.',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'telefono' => $request->telefono,
            'apaterno' => $request->apaterno,
            'amaterno' => $request->amaterno,
        ];

        if ($request->hasFile('foto')) {
            $image = $request->file('foto');
            $mimeReal = $image->getMimeType();
            $mimesPermitidos = ['image/jpeg', 'image/png', 'image/webp'];

            if (! in_array($mimeReal, $mimesPermitidos)) {
                return redirect()->back()
                    ->withErrors(['foto' => 'Tipo de imagen no permitido.'])
                    ->withInput();
            }

            // Eliminar foto anterior si existe
            if ($user->foto && Storage::disk('public')->exists('perfiles/'.$user->foto)) {
                Storage::disk('public')->delete('perfiles/'.$user->foto);
            }

            $extension = match ($mimeReal) {
                'image/jpeg' => 'jpg',
                'image/png' => 'png',
                'image/webp' => 'webp',
                default => 'jpg',
            };
            $nombreFoto = 'perfil_'.$user->id.'_'.time().'.'.$extension;
            $image->storeAs('perfiles', $nombreFoto, 'public');
            $data['foto'] = $nombreFoto;
        }

        User::where('id', $user->id)->update($data);

        return redirect()->back()->with('updatePerfil', 'Perfil actualizado correctamente.');
    }
}
