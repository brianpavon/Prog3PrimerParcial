<?php
require_once __DIR__ . './manejadorArchivo.php';
require_once '/xampp/htdocs/parcial/vendor/autoload.php';
//const USUARIOJSON = './archivos/usuario.json';
use \Firebase\JWT\JWT;

class Usuario extends ManejadorArchivo
{
    public $_email;
    public $_clave;
    public $_tipoUsuario;


    public function __construct($mail, $clave, $tipoUsuario)
    {
        $this->_email = $mail;
        $this->_clave = $clave;
        if ($tipoUsuario == 'admin' || $tipoUsuario == 'user') {
            $this->_tipoUsuario = $tipoUsuario;
        } else {
            $this->_tipoUsuario = 'user';
        }
    }

    public function __get($name)
    {
        echo $this->$name;
    }

    public function __set($name, $value)
    {

        $this->$name = $value;
    }

    public function __toString()
    {
        return $this->_email . '*' . $this->_clave . '*' . $this->_tipoUsuario;
    }

    //LEE ARCHIVO TXT Y DEVUELVE LA LISTA DE USUARIOS
    /*public static function LeerTxt()
    {
        $usuariosLeidos = parent ::Leer(USUARIOTXT);
        $listaUsuarios = array();
        if(count($usuariosLeidos)>0)
        {
            foreach ($usuariosLeidos as $key => $value) 
            {
                if(count($value)>0)
                {
                    $usuarioNuevo = new Usuario($value[0],$value[1]);
                    array_push($listaUsuarios,$usuarioNuevo);
                }
            }
        }
        return $listaUsuarios;
    }*/

    public static function CrearUsuario()
    {
        $mail = $_POST['email'] ?? '';
        $clave = $_POST['password'] ?? '';
        $tipoUsuario = $_POST['tipo'] ?? '';
        $usuarioJson = Usuario::LeerUsuarioJSON();
        $nuevoUsuario = new Usuario($mail, $clave, $tipoUsuario);
        if (Usuario::ValidarMailUnico($nuevoUsuario)) 
        {
            array_push($usuarioJson, $nuevoUsuario);
            if (ManejadorArchivo::GuardarJSON(USUARIOJSON, $usuarioJson)) 
            {
                echo '<br>Usuario guardado<br>';
            }
        } else {
            echo '<br>Usuario repetido<br>';
        }
    }

    //LEE ARCHIVO JSON Y DEVUELVE LA LISTA DE PROFESORES
    public static function LeerUsuarioJSON()
    {
        $usuariosLeidos = parent::LeerJSON(USUARIOJSON);
        $listaUsuarios = array();

        foreach ($usuariosLeidos as $usuario) {
            $userNuevo = new Usuario($usuario->_email, $usuario->_clave, $usuario->_tipoUsuario);
            array_push($listaUsuarios, $userNuevo);
        }

        return $listaUsuarios;
    }

    //VERIFICO LEGAJO UNICO
    public static function ValidarMailUnico($usuario)
    {
        $repetido = true;
        $arrayDeUsuarios = Usuario::LeerUsuarioJSON();

        foreach ($arrayDeUsuarios as $item) {
            if ($usuario->_email == $item->_email) {
                $repetido = false;
            }
        }

        return $repetido;
    }

    //VERIFICAR PERMISOS
    public static function PermitirPermisoAdmin($token)
    {
        $retorno = false;
        try {
            $payload = JWT::decode($token, "primerparcial", array('HS256'));
            //var_dump($payload);
            foreach ($payload as $value) {
                if ($value == 'admin') {

                    $retorno = true;
                }
            }
        } catch (\Throwable $th) {
            echo 'Excepcion:' . $th->getMessage();
        }
        return $retorno;
    }

    public static function PermitirPermisoUser($token)
    {
        $retorno = false;
        try {
            $payload = JWT::decode($token, "primerparcial", array('HS256'));
            //var_dump($payload);
            foreach ($payload as $value) {
                if ($value == 'user') {

                    $retorno = true;
                }
            }
        } catch (\Throwable $th) {
            echo 'Excepcion:' . $th->getMessage();
        }
        return $retorno;
    }

    public static function ObtenerMailToken($token)
    {
        //$retorno = false;
        try {
            $payload = JWT::decode($token, "primerparcial", array('HS256'));
            //var_dump($payload);
            foreach ($payload as $key => $value) 
            {
                if ($key == 'mail') 
                {

                    return $value;
                }
            }
        } catch (\Throwable $th) {
            echo 'Excepcion:' . $th->getMessage();
        }
        //return $retorno;
    }

    //VERIFICA QUE EL USUARIO ESTE CARGADO
    public static function LoginUsuario($metodo)
    {

        if ($metodo == 'POST') {
            $mail = $_POST['email'] ?? '';
            $clave = $_POST['password'] ?? '';

            $loginValido = Usuario::VerificarUsuarioRegistrado($mail, $clave);

            if ($loginValido != false) {
                echo $loginValido;
            } else {
                echo 'Clave o mail invalidos';
            }
        } else {
            echo 'Metodo o ruta invalida';
        }
    }

    //
    public static function VerificarUsuarioRegistrado($mail, $clave)
    {
        $listarUsuarios = Usuario::LeerUsuarioJSON();
        $payload = array();
        $encodeCorrecto = false;
        //var_dump($listarUsuarios);
        if (count($listarUsuarios) > 0) {
            foreach ($listarUsuarios as $usuario) {
                if ($usuario->_email == $mail && $usuario->_clave == $clave) {
                    $payload = array(
                        "mail" => $mail,
                        "clave" => $clave,
                        "tipo" => $usuario->_tipoUsuario
                    );
                    $encodeCorrecto = JWT::encode($payload, 'primerparcial');
                    break;
                }
            }
        } else {
            echo 'Cargue usuarios primero';
        }
        //var_dump($encodeCorrecto);
        return $encodeCorrecto;
    }
}
