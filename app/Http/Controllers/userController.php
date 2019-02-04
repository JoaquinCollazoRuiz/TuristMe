<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Usuario;
use \Firebase\JWT\JWT;

class userController extends Controller
{
    public function index()
    {
        if ($this->checkLogin()) 
        { 
            $usuarioData = $this->getUsuarioData();
            $usuariosCSave = $this->allUsuariosOneUsuario($usuarioData->id);

            if (count($usuariosCSave) < 1)
            {
                return $this->success('No hay usuarios todavia');
            }

            return $this->success('Lista usuarios creados: ', $usuariosCSave);
        }
        else
        {
            return $this->error(400, "Acceso denegado");
        }    
    }
    
    public function store(Request $request)
    {
        if ($this->checkLogin()) 
        { 

            if (!$request->filled("usuarioNombre") or !$request->filled("email") or !$request->filled("contrasena"))
            {
                return $this-> error(400, "Campos vacios");
            }

            $usuarioData = $this->getUsuarioData();
            $this->isUsedUsuarioNombre($request->usuarioNombre,$usuarioData->id);
            $usuario = new Usuario();
            $usuario->nombre = $request->usuarioNombre;
            $usuario->email = $request->email;
            $usuario->contrasena = $request->contrasena;
            $usuario->id_rol = $usuarioData->id;
            $usuario->save();
            return $this->success('usuario creado', $request->usuarioNombre);
        }
        else
        {
            return $this->error(400, "Acceso denegado");
        }    
    }

    public function show($usuarioNombre)
    {
        if ($this->checkLogin()) 
        {
            if(is_null($usuarioNombre))
            {
                return $this->error(400, "El nombre del usuario tiene que estar rellenado");
            }
            $usuarioData = $this->getUsuarioData();
            $usuarioSave = $this->oneUsuarioOfUsuario($usuarioData->id,$usuarioNombre);
            return $this->success('La categoria selecionada', $usuarioSave);
        }
        else
        {
            return $this->error(400, "Acceso denegado");
        } 
    }   

    public function update(Request $request, $id)
    {
        if ($this->checkLoginAdmin()) 
        { 
            $infoToken = $this->getUsuarioData();

            if(is_null($newName))
            {
                $newName = $infoToken->nombre;
            }
            if(is_null($newEmail))
            {
                $newEmail = $infoToken->email;
            }
            if(is_null($newContrasena))
            {
                $newContrasena = $infoToken->contrasena;
            }
            $usuarioSave = Usuario::where('id',$id)->first();
            $usuarioSave->nombre = $request->nombre;
            $usuarioSave->email = $request->email;
            $usuarioSave->contrasena = $request->contrasena;

            $usuarioSave->save();
            return $this->success('Usuario modificada', $usuarioSave);
        }
        else
        {
            return $this->error(400, "Acceso denegado");
        }
    }
   
    public function destroy($usuario)
    {
        if ($this->checkLogin()) 
        { 
            $usuarioNombre = $usuario;
            $usuarioSave = Usuario::where('nombre',$usuarioNombre)->first();
            $usuarioSave->delete();
            return $this->success('eliminado usuario', "");
        }
        else
        {
            return $this->error(400, "Acceso denegado");
        }       
    }
    
    private function isUsedUsuarioNombre($usuarioNombre,$id_rol)
    {
        $usuariosCSave = $this->allUsuariosOneUsuario($id_rol);
        foreach ($usuariosCSave as $Usuario => $UsuarioSave) 
        {
            if($UsuarioSave->nombre == $usuarioNombre)
            {
                exit($this->error(400,'El nombre del usuario ya existe'));
            }  
        }
    }

    private function allUsuariosOneUsuario($id)
    {
        return Usuario::where('id_rol', $id)->get();
    }
    private function oneusuarioOfUsuario($id,$usuarionombre)
    {
        $usuariosCSave = $this->allUsuariosOneUsuario($id);
        foreach ($usuariosCSave as $usuariosC => $categorie)
        {
            if($usuarionombre == $categorie->nombre)
            {
                return $categorie;
            }
        }
        exit($this->error(400,'Este usuario no existe, introduce una que ya exista'));
    }
}
