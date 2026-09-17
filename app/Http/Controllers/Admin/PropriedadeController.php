<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PropriedadeController extends Controller
{
    public function index()
    {
        return view('admin.propriedades.index');
    }

    public function create(){
        return view('admin.propriedades.create');
    }

}
