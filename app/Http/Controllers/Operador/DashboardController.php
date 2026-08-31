<?php

namespace App\Http\Controllers\Operador;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $cargo = auth()->user()->perfil;
        return view('operador.dashboard', compact('cargo'));
    }
}
