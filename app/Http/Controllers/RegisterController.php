<?php
namespace App\Http\Controllers;
use \Firebase\JWT\JWT;
use Illuminate\Http\Request;
use App\Usuario;

class RegisterController extends Controller
{
    public function register (Request $request)
    {
        if (!isset($_POST['nombre']) or !isset($_POST['email']) or !isset($_POST['contrasena'])) 
        {
            return $this->error(400, 'No puede haber campos vacios');
        }
        $usuario = $_POST['nombre'];
        $email = $_POST['email'];
        $contrasena = $_POST['contrasena'];
        // if($this->checkPassword($password))
        // {
        //     return $this->error(415,'La contraseña tiene que ser superior a 8 carecteres');
        // }
        // if($this->checkEmail($email))
        // {
        //     return $this->error(415,'El email no es valido');
        // }
        if($this->checkUserExist($email))
        {
            return $this->error(415,'El usuario ya existe');
        }
     
        if (!empty($usuario) && !empty($email) && !empty($contrasena))
        {
            $usuarios = new Usuario();
            $usuarios->nombre = $usuario;
            $usuarios->contrasena = $contrasena;
            $usuarios->email = $email;
            $usuarios->save();
            return $this->success('Usuario registrado',"");                 
        }
        else
        {
            return $this->error(400,'No puede haber campos vacios');
        }    
    }
    // public function checkPassword($password)
    // {
    //     if(strlen($password) = 8)
    //     {
    //         return true;
    //     }
    //     return false;
    // }
    // public function checkEmail($email)
    // {
    //     if(!filter_var($email, FILTER_VALIDATE_EMAIL))
    //     {
    //         return true;
    //     }
    //     return false;
    // }
    public function checkUserExist($email)
    {
        $usuarioData = Usuario::where('email',$email)->first();
        if(!is_null($usuarioData))
        {
            return true;
        }
        return false;
    }
}