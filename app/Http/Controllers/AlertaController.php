<?php

namespace App\Http\Controllers;

use App\Models\Alerta;

class AlertaController extends Controller
{
    public function index()
    {
        $alertas = auth()->user()
            ->alertas()
            ->paginate(20);

        return view('alertas.index', compact('alertas'));
    }

    public function marcarLeida(Alerta $alerta)
    {
        if ($alerta->user_id !== auth()->id()) {
            abort(403);
        }

        $alerta->update(['leida' => true]);

        if (request()->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back();
    }

    public function marcarTodasLeidas()
    {
        auth()->user()->alertas()->update(['leida' => true]);

        return redirect()->back()->with('toastr_success', 'Todas las alertas marcadas como leidas.');
    }

    public function destroy(Alerta $alerta)
    {
        if ($alerta->user_id !== auth()->id()) {
            abort(403);
        }

        $alerta->delete();

        return redirect()->back()->with('toastr_success', 'Alerta eliminada.');
    }
}
