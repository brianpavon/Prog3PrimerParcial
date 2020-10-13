<?php

require_once __DIR__ . './manejadorArchivo.php';
//require_once __DIR__ . './clases/usuario.php';

class Precio extends ManejadorArchivo
{
    public $_precioHora;
    public $_precioEstadia;
    public $_precioMensual;

    public function __construct($precioHora, $precioEstadia, $precioMensual)
    {
        $this->_precioHora = $precioHora;
        $this->_precioEstadia = $precioEstadia;
        $this->_precioMensual = $precioMensual;
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
        return $this->_precioHora . '*' . $this->_precioEstadia . '*' . $this->_precioMensual;
    }

    public static function LeerPrecioJSON()
    {
        $precios = parent::LeerJSON(PRECIOJSON);
        $listaPrecios = array();

        foreach ($precios as $precio) {
            $precioNuevo = new Precio($precio->_precioHora, $precio->_precioEstadia, $precio->_precioMensual);
            array_push($listaPrecios, $precioNuevo);
        }

        return $listaPrecios;
    }

    public static function CargarPrecios()
    {

        $precioHora = $_POST['precio_hora'] ?? '';
        $precioEstadia = $_POST['precio_estadia'] ?? '';
        $precioMensual = $_POST['precio_mensual'] ?? '';
        $listaPrecios = Precio::LeerPrecioJSON();
        $nuevoPrecio = new Precio($precioHora, $precioEstadia, $precioMensual);
        array_push($listaPrecios, $nuevoPrecio);
        if (ManejadorArchivo::GuardarJSON(PRECIOJSON, $listaPrecios)) 
        {
            echo 'Guardado exitoso';
        } 
        else 
        {
            echo 'No se pudo guardar';
        }
    }

    //Calcular precio
    public static function CalcularPrecio($tipo,$cantidad)
    {
        $listaPrecios = self::LeerPrecioJSON();
        $totalTarifa = 0;
        foreach ($listaPrecios as $precioLista) 
        {
            switch($tipo)
            {
                case 'hora':
                    $totalTarifa = $cantidad * $precioLista->_precioHora;
                    break;

                case 'estadia':
                    $totalTarifa = $cantidad * $precioLista->_precioEstadia;
                    break;

                case 'mensual':
                    $totalTarifa = $cantidad * $precioLista->_precioMensual;
                    break;
            }
        }
        echo 'Importe a abonar '. $totalTarifa;
    }
}
