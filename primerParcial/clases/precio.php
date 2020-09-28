<?php

require_once __DIR__.'./manejadorArchivo.php';

class Precio extends ManejadorArchivo
{
    public $_precioHora;
    public $_precioEstadia;
    public $_precioMensual;

    public function __construct($precioHora,$precioEstadia,$precioMensual)
    {
        $this->_precioHora = $precioHora;
        $this->_precioEstadia = $precioEstadia;
        $this->_precioMensual = $precioMensual;
    }

    public function __get($name)
    {
        echo $this->$name;
    }

    public function __set($name,$value)
    {
        
        $this->$name = $value;

    }

    public function __toString()
    {
        return $this->_precioHora . '*' . $this->_precioEstadia.'*'.$this->_precioMensual;
    }

    public static function LeerUsuarioJSON()
    {       
        $precios = parent::LeerJSON(PRECIOJSON); 
        $listaPrecios = array();
        
        foreach ($precios as $precio) 
        {
            $precioNuevo = new Usuario($precio->_precioHora,$precio->_precioEstadia,$precio->_precioMensual);
            array_push($listaPrecios,$precioNuevo);
        }
        
        return $listaPrecios;
    }  
}