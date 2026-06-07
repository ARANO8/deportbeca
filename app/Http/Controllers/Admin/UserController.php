<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rol;
use App\Models\User;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::select('*')->orderBy('id', 'ASC');
        $limit = (isset($request->limit)) ? $request->limit : 10;

        if (isset($request->search)) {
            $users = $users->where('id', 'like', '%'.$request->search.'%')
                ->orWhere('name', 'like', '%'.$request->search.'%')
                ->orWhere('email', 'like', '%'.$request->search.'%')
                ->orWhere('status', 'like', '%'.$request->search.'%')
                ->orWhere('telefono', 'like', '%'.$request->search.'%');
        }
        $users = $users->paginate($limit)->appends($request->all());

        return view('user.index', compact('users'));
    }

    public function create()
    {
        $roles = Rol::where('status', 'active')->get();

        return view('user.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users,email',
            'telefono' => 'required',
            'apaterno' => 'required',
            'amaterno' => 'required',
            'password' => 'required|min:8|regex:/[^\w]/',
            'rol_id' => 'required|exists:roles,id',
        ];

        $messages = [
            'name.required' => 'El nombre del usuario es obligatorio',
            'name.min' => 'El nombre del usuario debe tener mas de 3 caracteres',
            'email.required' => 'El correo electronico del usuario es obligatorio',
            'email.email' => 'Ingrese una dirrecion de correo electronico valido',
            'email.unique' => 'Este correo electronico ya está registrado',
            'telefono.required' => 'El número de telefono es obligatorio',
            'apaterno.required' => 'El apellido paterno del usuario es oligatorio',
            'amaterno.required' => 'El apellido materno del usuario es obligatorio',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres',
            'password.regex' => 'La contraseña debe contener al menos un carácter especial',
            'rol_id.required' => 'Debe seleccionar un rol para el usuario',
            'rol_id.exists' => 'El rol seleccionado no es válido',
        ];

        $this->validate($request, $rules, $messages);

        $user = User::create(
            $request->only('name', 'email', 'telefono', 'apaterno', 'amaterno', 'rol_id')
            + [
                'status' => 'activo',
                'password' => bcrypt($request->input('password')),
            ]
        );

        // Enviar correo de bienvenida (sin incluir la contraseña en texto plano)
        try {
            $to = $user->email;
            $subject = 'Registro de Usuario - DeportBeca';
            $message = "
                <h3>Bienvenido(a) {$user->name}</h3>
                <p>Su cuenta ha sido registrada correctamente en el sistema DeportBeca.</p>
                <p><strong>Usuario:</strong> {$user->email}</p>
                <p>Inicie sesion con la contraseña asignada por el administrador.
                   Si necesita cambiarla, use la opcion de perfil dentro del sistema.</p>
                <br>
                <p>Atentamente,<br>El equipo de administracion</p>
            ";

            Mail::send([], [], function ($mail) use ($to, $subject, $message) {
                $mail->to($to)
                    ->subject($subject)
                    ->setBody($message, 'text/html');
            });

            Session::flash('toastr_success', ' Usuario creado correctamente. Se enviaron las credenciales al correo.');

        } catch (\Exception $e) {
            Session::flash('toastr_warning', ' Usuario creado pero hubo un problema al enviar el correo.');
        }

        return redirect('/users');
    }

    public function show($id)
    {
        try {
            $enid = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            abort(404);
        }
        $users = User::with('rol')->findOrFail($enid);

        return view('user.show', compact('users'));
    }

    public function edit($id)
    {
        try {
            $enid = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            abort(404);
        }
        $user = User::findOrFail($enid);
        $roles = Rol::where('status', 'active')->get();

        return view('user.edit', compact('user', 'roles'));
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users,email,'.$id,
            'telefono' => 'required',
            'apaterno' => 'required',
            'amaterno' => 'required',
            'password' => 'nullable|min:8|regex:/[^\w]/',
            'rol_id' => 'required|exists:roles,id',
        ];

        $messages = [
            'name.required' => 'El nombre del usuario es obligatorio',
            'name.min' => 'El nombre del usuario debe tener mas de 3 caracteres',
            'email.required' => 'El correo electronico del usuario es obligatorio',
            'email.email' => 'Ingrese una dirrecion de correo electronico valido',
            'email.unique' => 'Este correo electronico ya está registrado',
            'telefono.required' => 'El número de telefono es obligatorio',
            'apaterno.required' => 'El apellido paterno del usuario es oligatorio',
            'amaterno.required' => 'El apellido materno del usuario es obligatorio',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres',
            'password.regex' => 'La contraseña debe contener al menos un carácter especial',
            'rol_id.required' => 'Debe seleccionar un rol para el usuario',
            'rol_id.exists' => 'El rol seleccionado no es válido',
        ];

        $this->validate($request, $rules, $messages);

        $user = User::findOrFail($id);
        $data = $request->only('name', 'email', 'telefono', 'apaterno', 'amaterno', 'status', 'rol_id');

        $password = $request->input('password');
        if ($password) {
            $data['password'] = bcrypt($password);
        }

        $user->fill($data);
        $user->save();

        Session::flash('toastr_success', ' Usuario actualizado correctamente.');

        return redirect('/users');
    }

    public function inactivo(User $user)
    {
        $user->status = 'inactivo';
        $user->save();

        Session::flash('toastr_info', ' Usuario inactivado correctamente.');

        return redirect('/users');
    }

    public function activo(User $user)
    {
        $user->status = 'activo';
        $user->save();
        Session::flash('toastr_success', ' Usuario activado correctamente.');

        return redirect('/users');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        Session::flash('toastr_info', '🗑️ Usuario eliminado correctamente.');

        return redirect()->route('users.index');
    }
}
