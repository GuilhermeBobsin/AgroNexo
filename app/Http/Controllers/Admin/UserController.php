<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $usuarios = User::orderBy('name')->paginate(12);

        return view('admin.usuarios.index', compact('usuarios'));
    }
}
