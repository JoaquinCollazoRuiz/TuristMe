<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \Firebase\JWT\JWT;
use App\Usuario;//Para poder acceder al modelo de User

class LoginController extends Controller
{
    public function login(Request $req)
    {
        if (!isset($_POST['email']) or !isset($_POST['contrasena'])) 
        {
            return $this->error(400, 'Campos incompletos');
        }
        //En caso de estar los campos completos me comprueba si coincide con la base de datos y en caso de ser correcto
        //me permite iniciar sesión devolviendome el token
        $email = $_POST['email'];
        $contrasena = $_POST['contrasena'];
        if ($this->checkExist($email,$contrasena))
        {
            $usuarioSave = Usuario::where('email', $email)->first();
            $usuarioData = array(
                'id' => $usuarioSave->id,
                'nombre' => $usuarioSave->nombre,
                'email' => $usuarioSave->email,
                'contrasena' => $usuarioSave->contrasena,
                'id_rol' => $usuarioSave->id_rol,
            );
            $token = JWT::encode($usuarioData, $this->key);
            // return $this->success('Usuario Logeado', $token);
            return response($token);
        }
        else
        {
            return $this->error(400, 'Datos incorrectos');
        }
    }
    public function checkExist($email,$contrasena)
    {   
        $usuarioSave = Usuario::where('email', $email)->first();
        
        // if(!is_null($usuarioSave) && $usuarioSave->contrasena == $contrasena && $usuarioSave->id_rol == 0)
        // {
        //     return true;
        // }
        if(!is_null($usuarioSave) && $usuarioSave->contrasena == $contrasena && $usuarioSave->id_rol == 0)
        {
            return true;
        }
        return false;
    }
}