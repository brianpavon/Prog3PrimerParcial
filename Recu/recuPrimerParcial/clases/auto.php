<?php

require_once __DIR__ . './manejadorArchivo.php';

class Auto extends ManejadorArchivo
{
    public $_patente;
    public $_fecha_ingreso;
    public $_tipoEstadia;
    public $_mailUsuario;
    public $_fecha_egreso;

    public function __construct($patente,$fechaIngreso,$tipoEstadia,$mailUsuario)
    {
        $this->_patente = $patente;
        $this->_fecha_ingreso = $fechaIngreso;
        $this->_tipoEstadia = $tipoEstadia;
        $this->_mailUsuario = $mailUsuario;
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
        return $this->_patente . '*' . $this->_fecha_ingreso . '*' . $this->_tipoEstadia.'*'.$this->_mailUsuario.'*'.$this->_fecha_egreso;
    }

    public static function MostrarAuto($auto)
    {
        echo 'Patente: '.$auto->_patente.' Fecha de ingreso: '.$auto->_fecha_ingreso.' Fecha Egreso: '.$auto->_fecha_egreso.PHP_EOL;
    }
    

    public static function LeerAutoJSON()
    {
        $autos = parent::LeerJSON(AUTOJSON);
        $listaAutos = array();

        foreach ($autos as $auto) 
        {
            $autoNuevo = new Auto($auto->_patente, $auto->_fecha_ingreso, $auto->_tipoEstadia,$auto->_mailUsuario);
            array_push($listaAutos, $autoNuevo);
        }

        return $listaAutos;
    }

    public static function IngresarAutos($mail)
    {
        
        $fecha = date('D:H:i');        
        $patente = $_POST['patente']??'';
        $tipoEstadia = $_POST['tipo']??'';
        $mailUsuario = $mail;
        $nuevoAuto = new Auto($patente,$fecha,$tipoEstadia,$mailUsuario);
        $listaAutos = Auto::LeerAutoJSON();
        array_push($listaAutos,$nuevoAuto);
        if(parent::GuardarJSON(AUTOJSON,$listaAutos))
        {
            echo 'Auto creado de manera exitosa';
        }
        else
        {
            echo 'No se creo ningun auto';
        }
    }

   public static function RetiroAutos($patente)
   {
       $arrayAutos = self::LeerAutoJSON();
       //var_dump($arrayAutos);
       foreach ($arrayAutos as $auto) 
       {
           //$auto = json_decode($itemArray);
           if($auto->_patente == $patente)
           {
               $auto->_fecha_egreso = date('D:H:i');
               //var_dump($auto->__toString());
               Auto::MostrarAuto($auto);
               if($auto->_tipoEstadia == 'hora')
               {
                $horas = self::CalcularHoras($auto);
                //var_dump($horas);
                Precio::CalcularPrecio($auto->_tipoEstadia,$horas);
               }                             
               break;
           }
       }
   }

   public static function CalcularHoras($auto)
   {
       $horaIngreso = explode(':',$auto->_fecha_ingreso);
       $horaSalida = explode(':',$auto->_fecha_egreso);
       
       $minutosEntrada = (int)$horaIngreso[1]*60 + (int)$horaIngreso[2];
       $minutosSalida = (int)$horaSalida[1]*60 + (int)$horaSalida[2];
       
       if($minutosEntrada < $minutosSalida)
       {
            $totalMinutos = $minutosSalida - $minutosEntrada;
       }
       else if($minutosEntrada > $minutosSalida)
       {
           $totalMinutos = $minutosEntrada - $minutosSalida;
       }

       if($totalMinutos <= 59)
       {
           $totalHoras = 1;
       }
       else if($totalMinutos > 59)
       {
           $totalHoras = (int)round($totalMinutos/60,PHP_ROUND_HALF_DOWN);
       }
       
       //$totalHoras = (int)$horaSalida[2] - (int)$horaIngreso[2];
       //var_dump($totalHoras);
       return $totalHoras;
   }
}

/*function calcular_tiempo_trasnc($hora1,$hora2)
{
    $separar[1]=explode(':',$hora1);
    $separar[2]=explode(':',$hora2);

    $total_minutos_trasncurridos[1] = ($separar[1][0]*60)+$separar[1][1];
    $total_minutos_trasncurridos[2] = ($separar[2][0]*60)+$separar[2][1];
    $total_minutos_trasncurridos = $total_minutos_trasncurridos[1]-$total_minutos_trasncurridos[2];

    if($total_minutos_trasncurridos<=59) 
    return($total_minutos_trasncurridos.' Minutos');

    elseif($total_minutos_trasncurridos>59)
    {
        $HORA_TRANSCURRIDA = round($total_minutos_trasncurridos/60);
        if($HORA_TRANSCURRIDA<=9) 
        $HORA_TRANSCURRIDA='0'.$HORA_TRANSCURRIDA;
        $MINUITOS_TRANSCURRIDOS = $total_minutos_trasncurridos%60;
        if($MINUITOS_TRANSCURRIDOS<=9) 
        $MINUITOS_TRANSCURRIDOS='0'.$MINUITOS_TRANSCURRIDOS;

        return ($HORA_TRANSCURRIDA.':'.$MINUITOS_TRANSCURRIDOS.' Horas');

    } 
}*/
