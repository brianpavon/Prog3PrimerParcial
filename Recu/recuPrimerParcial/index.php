<?php
const USUARIOJSON = './archivos/usuario.json';
const PRECIOJSON = './archivos/precio.json';
const AUTOJSON = './archivos/auto.json';

require_once __DIR__.'./clases/manejadorArchivo.php';
require_once __DIR__.'./clases/usuario.php';
require_once __DIR__.'./clases/precio.php';
require_once __DIR__.'./clases/auto.php';

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
switch($metodo)
{
    case 'POST':
        switch ($pathInfo) 
        {
            case '/registro':  
                Usuario::CrearUsuario();
                break;
                
            case '/login':
                Usuario::LoginUsuario($metodo);
                break;
               
            case '/precio':     
                if (Usuario::PermitirPermisoAdmin(ObtenerToken())) 
                {
                    Precio::CargarPrecios();                    
                }
                else
                {
                    echo 'Permiso invalido';
                }
                break;
            case '/ingreso':
                if(Usuario::PermitirPermisoUser(ObtenerToken()))
                {
                    $mail = Usuario::ObtenerMailToken(ObtenerToken());
                    //var_dump($mail);
                    Auto::IngresarAutos($mail);
                }
                else
                {
                    echo 'Permiso invalido';
                }
            default:
                echo 'Ruta Inválida';
                break;
        }
        
    case 'GET':
        //$ruta = $pathInfo;
        $datosUrl = explode('/',$pathInfo);
        /*$ruta;
        //var_dump($datosUrl[1]);
        if(count($datosUrl)>1)
        {
            //var_dump($datosUrl[1]);
            switch ($datosUrl[1]) 
            {
                case 'retiro':
                    $ruta = $datosUrl[2];
                    break;
                case 'importe':
                    $ruta = $datosUrl[2];
                    break;
                
                default:
                    # code...
                    break;
            }
        }
        else
        {
            $ruta = $pathInfo;
        }
        return $ruta;*/
        //return $ruta;
        if($datosUrl[1] == 'retiro')
        {
            $patente = $datosUrl[2];
            Auto::RetiroAutos($patente);

        }
        
        /*switch ($ruta) 
        {
            case 'value':
                # code...
                break;
            
            default:
                # code...
                break;
        }*/
        
        break;
    default:
        echo 'Metodo invalido';
        break;

}

/*function ObtenerDatosUrl()
{
    $datosUrl = explode('/',$pathInfo);
    $ruta;
    //var_dump($datosUrl[1]);
    if(count($datosUrl)>1)
    {
        //var_dump($datosUrl[1]);
        switch ($datosUrl[1]) 
        {
            case 'retiro':
                $ruta = $datosUrl[2];
                break;
            case 'importe':
                $ruta = $datosUrl[2];
                break;
                
            default:
                    # code...
                break;
            }
        }
}*/

/*switch ($pathInfo) 
{
    case '/registro':
        
        switch ($metodo) 
        {

            case 'POST':
                Usuario::CrearUsuario();
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
        break;
        
    case '/precio':
        switch($metodo)
        {
            case 'POST':
                
                if (Usuario::PermitirPermisoAdmin(ObtenerToken())) 
                {
                    Precio::CargarPrecios();                    
                }
        }       break;
        break;
    case '/ingreso':
        switch($metodo)
        {
            case 'POST':
                if(Usuario::PermitirPermisoUser(ObtenerToken()))
                {
                    $mail = Usuario::ObtenerMailToken(ObtenerToken());
                    //var_dump($mail);
                    Auto::IngresarAutos($mail);
                }
                break;
        }
        
        break;
    case '/retiro/aaa123':
        switch ($metodo) 
        {
            case 'GET':
                $patente = explode('/',$pathInfo);
                if(Usuario::PermitirPermisoUser(ObtenerToken()))
                {
                    

                }
                break;
            
            default:
                echo 'Metodo invalido';
                break;
        }
        break;
}*/