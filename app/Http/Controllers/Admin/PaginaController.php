<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pagina;
use App\Models\User;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class PaginaController extends Controller
{
    public function index(Request $request)
    {
        $role = auth()->user()->rol?->nombre;
        $pagina = Pagina::select('*')->orderBy('id', 'ASC');
        $limit = (isset($request->limit)) ? $request->limit : 10;

        if (isset($request->search)) {
            $pagina = $pagina->where('id', 'like', '%'.$request->search.'%')
                ->orWhere('nombre', 'like', '%'.$request->search.'%');
        }
        $pagina = $pagina->paginate($limit)->appends($request->all());

        return view('pagina.index', compact('pagina', 'role'));
    }

    public function create()
    {
        $role = auth()->user()->rol?->nombre;
        $pagina = Pagina::all();
        $destinatarios = User::all();

        return view('pagina.create', compact('pagina', 'role', 'destinatarios'));
    }

    public function store(Request $request)
    {
        $rules = [
            'nombre' => 'required|min:3',
            'descripcion' => 'required|min:10',
            'imagen' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];

        $messages = [
            'nombre.required' => 'El nombre para la publicación es obligatorio.',
            'nombre.min' => 'El nombre debe tener al menos 3 caracteres.',
            'descripcion.required' => 'La descripción para la publicación es obligatoria.',
            'descripcion.min' => 'La descripción debe tener al menos 10 caracteres.',
            'imagen.required' => 'La imagen es obligatoria.',
            'imagen.image' => 'El archivo debe ser una imagen.',
            'imagen.mimes' => 'La imagen debe ser de tipo: jpeg, png, jpg, gif, svg.',
            'imagen.max' => 'La imagen no debe pesar más de 2MB.',
        ];

        $this->validate($request, $rules, $messages);

        $input = $request->except(['imagen', 'send_email', '_token', '_method']);

        // Guardar imagen usando Laravel Storage (disco privado seguro)
        if ($image = $request->file('imagen')) {
            // Verificar MIME real del archivo (no la extension declarada por el cliente)
            $mimeReal = $image->getMimeType();
            $mimesPermitidos = ['image/jpeg', 'image/png', 'image/gif', 'image/svg+xml', 'image/webp'];

            if (! in_array($mimeReal, $mimesPermitidos)) {
                return redirect()->back()
                    ->withErrors(['imagen' => 'El archivo no es una imagen válida (MIME: '.$mimeReal.').'])
                    ->withInput();
            }

            // Generar nombre seguro con extension derivada del MIME (no del cliente)
            $extension = match ($mimeReal) {
                'image/jpeg' => 'jpg',
                'image/png' => 'png',
                'image/gif' => 'gif',
                'image/webp' => 'webp',
                default => 'png',
            };
            $nombreSeguro = date('YmdHis').'_'.bin2hex(random_bytes(4)).'.'.$extension;

            // Almacenar en public/imagen (carpeta pública de imágenes de comunicados)
            $image->move(public_path('imagen'), $nombreSeguro);
            $input['imagen'] = $nombreSeguro;
        }

        $pagina = Pagina::create($input);

        // Enviar correos a todos los usuarios si está marcado
        if ($request->send_email == '1') {
            $destinatarios = User::all();
            $emailCount = 0;

            foreach ($destinatarios as $destinatario) {
                try {
                    Mail::send([], [], function ($message) use ($destinatario, $pagina) {
                        $message->to($destinatario->email)
                            ->subject('Nueva Publicacion: '.$pagina->nombre)
                            ->setBody("
                                <html>
                                <head>
                                    <style>
                                        body { font-family: Arial, sans-serif; }
                                        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                                        .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 20px; text-align: center; }
                                        .content { padding: 20px; background: #f8f9fa; }
                                        .footer { text-align: center; padding: 10px; font-size: 12px; color: #6c757d; }
                                        img { max-width: 100%; height: auto; border-radius: 10px; margin: 15px 0; }
                                    </style>
                                </head>
                                <body>
                                    <div class='container'>
                                        <div class='header'>
                                            <h2>📢 Nueva Publicación</h2>
                                        </div>
                                        <div class='content'>
                                            <h3>{$pagina->nombre}</h3>
                                            <p>{$pagina->descripcion}</p>
                                            ".($pagina->imagen ? "<img src='".url('imagen/'.$pagina->imagen)."' alt='Imagen'>" : '')."
                                        </div>
                                        <div class='footer'>
                                            <p>© ".date('Y').' Sistema de Gestión. Todos los derechos reservados.</p>
                                        </div>
                                    </div>
                                </body>
                                </html>
                            ', 'text/html');
                    });
                    $emailCount++;
                } catch (\Exception $e) {
                    Log::warning('PaginaController: fallo al enviar correo', [
                        'destinatario' => $destinatario->email,
                        'error' => $e->getMessage(),
                    ]);
                }
            }

            if ($emailCount > 0) {
                // ✅ Usar Session::flash como en UserController
                Session::flash('toastr_success', '✅ Publicación creada. Se enviaron '.$emailCount.' notificaciones por correo.');
            } else {
                Session::flash('toastr_warning', 'Publicacion creada, pero no se pudo enviar correos.');
            }
        } else {
            // ✅ Usar Session::flash como en UserController
            Session::flash('toastr_success', '✅ Publicación creada correctamente.');
        }

        return redirect('/paginawebs');
    }

    public function show($id)
    {
        $role = auth()->user()->rol?->nombre;
        try {
            $enid = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            abort(404);
        }
        $paginas = Pagina::findOrFail($enid);

        return view('pagina.show', compact('paginas', 'role'));
    }

    public function edit($id)
    {
        $role = auth()->user()->rol?->nombre;
        try {
            $enid = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            abort(404);
        }
        $pagina = Pagina::findOrFail($enid);

        return view('pagina.edit', compact('pagina', 'role'));
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'nombre' => 'required|min:3',
            'descripcion' => 'required|min:10',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];

        $messages = [
            'nombre.required' => 'El nombre para la publicación es obligatorio.',
            'nombre.min' => 'El nombre debe tener al menos 3 caracteres.',
            'descripcion.required' => 'La descripción para la publicación es obligatoria.',
            'descripcion.min' => 'La descripción debe tener al menos 10 caracteres.',
            'imagen.image' => 'El archivo debe ser una imagen.',
            'imagen.mimes' => 'La imagen debe ser de tipo: jpeg, png, jpg, gif, svg.',
            'imagen.max' => 'La imagen no debe pesar más de 2MB.',
        ];

        $this->validate($request, $rules, $messages);

        $pagina = Pagina::findOrFail($id);
        $input = $request->except(['imagen', 'send_email', '_token', '_method']);

        // Actualizar imagen si se sube una nueva
        if ($image = $request->file('imagen')) {
            $mimeReal = $image->getMimeType();
            $mimesPermitidos = ['image/jpeg', 'image/png', 'image/gif', 'image/svg+xml', 'image/webp'];

            if (! in_array($mimeReal, $mimesPermitidos)) {
                return redirect()->back()
                    ->withErrors(['imagen' => 'El archivo no es una imagen válida (MIME: '.$mimeReal.').'])
                    ->withInput();
            }

            // Eliminar imagen anterior
            if ($pagina->imagen && file_exists(public_path('imagen/'.$pagina->imagen))) {
                unlink(public_path('imagen/'.$pagina->imagen));
            }

            $extension = match ($mimeReal) {
                'image/jpeg' => 'jpg',
                'image/png' => 'png',
                'image/gif' => 'gif',
                'image/webp' => 'webp',
                default => 'png',
            };
            $nombreSeguro = date('YmdHis').'_'.bin2hex(random_bytes(4)).'.'.$extension;
            $image->move(public_path('imagen'), $nombreSeguro);
            $input['imagen'] = $nombreSeguro;
        }

        $pagina->update($input);

        // Enviar correos a todos los usuarios sobre la actualización
        if ($request->send_email == '1') {
            $destinatarios = User::all();
            $emailCount = 0;

            foreach ($destinatarios as $destinatario) {
                try {
                    Mail::send([], [], function ($message) use ($destinatario, $pagina) {
                        $message->to($destinatario->email)
                            ->subject('Publicacion Actualizada: '.$pagina->nombre)
                            ->setBody("
                                <html>
                                <head>
                                    <style>
                                        body { font-family: Arial, sans-serif; }
                                        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                                        .header { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; padding: 20px; text-align: center; }
                                        .content { padding: 20px; background: #f8f9fa; }
                                        .footer { text-align: center; padding: 10px; font-size: 12px; color: #6c757d; }
                                        img { max-width: 100%; height: auto; border-radius: 10px; margin: 15px 0; }
                                    </style>
                                </head>
                                <body>
                                    <div class='container'>
                                        <div class='header'>
                                            <h2>✏️ Publicación Actualizada</h2>
                                        </div>
                                        <div class='content'>
                                            <h3>{$pagina->nombre}</h3>
                                            <p>{$pagina->descripcion}</p>
                                            ".($pagina->imagen ? "<img src='".url('imagen/'.$pagina->imagen)."' alt='Imagen'>" : '').'
                                            <p><strong>Fecha de actualización:</strong> '.date('d/m/Y H:i:s')."</p>
                                        </div>
                                        <div class='footer'>
                                            <p>© ".date('Y').' Sistema de Gestión. Todos los derechos reservados.</p>
                                        </div>
                                    </div>
                                </body>
                                </html>
                            ', 'text/html');
                    });
                    $emailCount++;
                } catch (\Exception $e) {
                    Log::warning('PaginaController: fallo al enviar correo', [
                        'destinatario' => $destinatario->email,
                        'error' => $e->getMessage(),
                    ]);
                }
            }

            if ($emailCount > 0) {
                // ✅ Usar Session::flash como en UserController
                Session::flash('toastr_success', '✅ Publicación actualizada. Se enviaron '.$emailCount.' notificaciones por correo.');
            } else {
                Session::flash('toastr_warning', 'Publicacion actualizada, pero no se pudo enviar correos.');
            }
        } else {
            // ✅ Usar Session::flash como en UserController
            Session::flash('toastr_success', '✅ Publicación actualizada correctamente.');
        }

        return redirect('/paginawebs');
    }

    public function destroy($id)
    {
        $pagina = Pagina::findOrFail($id);

        // Eliminar imagen asociada
        if ($pagina->imagen && file_exists(public_path('imagen/'.$pagina->imagen))) {
            unlink(public_path('imagen/'.$pagina->imagen));
        }

        $pagina->delete();

        // ✅ Usar Session::flash como en UserController
        Session::flash('toastr_info', '🗑️ Publicación eliminada correctamente.');

        return redirect('/paginawebs');
    }
}
