<?php

namespace App\Http\Controllers\Agronomo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $cargo = auth()->user()->perfil;
        return view('agronomo.dashboard', compact('cargo'));
    }
}
