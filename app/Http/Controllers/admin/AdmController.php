<?php


namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Logo;
use App\Http\Controllers\Controller;

class AdmController extends Controller
{

    public function dashboard(){
        $usuarios = User::count(); 
        // $productos = Product::count();
        // $novedades = Novedad::count(); 
      //  $logo = Logo::first(); 



        return view('admin.dashboard', compact('usuarios')); 
    }
    public function login()
    {
        // Lógica del método dashboard
        return view('admin.dashboard');
    }
}
