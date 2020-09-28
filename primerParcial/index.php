<?php
const USUARIOJSON = './archivos/usuario.json';
const PRECIOJSON = './archivos/precio.json';
require_once __DIR__.'./clases/manejadorArchivo.php';
require_once __DIR__.'./clases/usuario.php';
require __DIR__.'/vendor/autoload.php';

function ObtenerToken()
{
    try 
    {
        $headers = getallheaders();
        return $headers['token'];
    }
    catch (\Throwable $th) 
    {
        echo 'Excepcion:'. $th->getMessage();
    }
    
}


$pathInfo = $_SERVER['PATH_INFO'];
$metodo = $_SERVER['REQUEST_METHOD'];

echo '<br>';
switch ($pathInfo) 
{
    case '/registro':
        
        switch ($metodo) 
        {

            case 'POST':
                
                $mail = $_POST['email'] ?? '';
                $clave = $_POST['password'] ?? '';
                $tipoUsuario = $_POST['tipo'] ?? '';
                $usuarioJson = Usuario::LeerUsuarioJSON();
                $nuevoUsuario = new Usuario($mail,$clave,$tipoUsuario);
                if(Usuario::ValidarMailUnico($nuevoUsuario))
                {
                    array_push($usuarioJson,$nuevoUsuario);
                    if(ManejadorArchivo::GuardarJSON(USUARIOJSON,$usuarioJson))
                    {
                        echo '<br>Usuario guardado<br>';
                    }
                }
                else
                {
                    echo '<br>Usuario repetido<br>';
                }
                
                break;
            
            default:
                echo 'Metodo no valido';
                break;
        }
        break;
    case '/login':
        switch($metodo)
        {
            case 'POST':
                Usuario::LoginUsuario($metodo);
                break;
        }
        
    case '/precio':
        switch($metodo)
        {
            case 'POST':
                if(Usuario::PermitirPermisoAdmin(ObtenerToken()))
            {
                /*if(ManejadorArchivo::GuardarJSON(PRECIOJSON))
                {

                }*/
        }
    }
        
    
    default:
        echo 'Ruta invalidad';
        break;
}