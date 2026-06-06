<?php

namespace App\Http\Controllers;

use App\Models\Pagina;
use Illuminate\Http\Request;

class WelcomController extends Controller
{
    public function index(Request $request)
    {

        $paginas = Pagina::paginate();

        return view('welcome', compact('paginas'));
    }
}
