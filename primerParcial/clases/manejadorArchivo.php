<?php

class ManejadorArchivo
{
    //GUARDA EN UN ARCHIVO TXT
    public function Guardar($nombreArchivo, $dato)
    {   
        $guardado = false;
        if($nombreArchivo != " " && $dato != null)
        {
            $archivo = fopen($nombreArchivo, "a+");
            fwrite($archivo,$dato.PHP_EOL);
            $guardado = fclose($archivo);
        }
        else
        {
            echo 'Ruta o dato incorrecto';
        }
        return $guardado;                   
        
    }

    //LEE UN ARCHIVO TXT
    public function Leer($nombreArchivo)
    {
        $listaDeAlgo = array();

        if($nombreArchivo != " " && file_exists($nombreArchivo))
        {
            $archivo = fopen($nombreArchivo, "r");
            while(!feof($archivo))
            {
                $linea = fgets($archivo);
                $datos = explode('*',$linea);
                if(count($datos)>1)
                {
                    array_push($listaDeAlgo,$datos);
                }
            }
            fclose($archivo);
        }
        else
        {
            echo "<br>aca Se necesita un archivo para leer<br>";
        }
        return $listaDeAlgo;
    }

    //Lee un archivo JSON y devuelve un array con lo que leyo
    public static function LeerJSON($nombreArchivo)
    {
        $arrayJSON = array();
        if($nombreArchivo != '' && file_exists($nombreArchivo))
        {   
            $archivo = fopen($nombreArchivo,'r');
            
            if(filesize($nombreArchivo) > 0)
            {
                $lectura = fread($archivo,filesize($nombreArchivo));
            }
            else
            {
                $lectura = '{}';
            }
            $arrayJSON = json_decode($lectura);
            fclose($archivo);
            
        }
        /*else
        {
            echo '<br>aca El nombre del archivo no puede estar vacio<br>';
        }*/
        return $arrayJSON;
    }

    //GUARDA EN FORMATO JSON
    public static function GuardarJSON($nombreArchivo,$arrayDeAlgo)
    {
        $guardado = false;
        if($nombreArchivo != '')
        {
            if(file_exists($nombreArchivo))
            {
                $listaDeArray = ManejadorArchivo::LeerJSON($nombreArchivo);
                foreach ($arrayDeAlgo as $value) 
                {
                    array_push($listaDeArray,$value);
                }
                $archivo = fopen($nombreArchivo,'w');
                fwrite($archivo,json_encode($listaDeArray));
                $guardado = fclose($archivo);
            }
            else
            {
                $archivo = fopen($nombreArchivo,'w');
                fwrite($archivo,json_encode($arrayDeAlgo));
                $guardado = fclose($archivo);
                
            }
        }        
        else
        {
            echo 'Ingrese un nombre de archivo valido';
        }        
        return $guardado;
    }

    //DESERIALIZA UN ARCHIVO Y DEVUELVE UN ARRAY
    public static function Deserializar($nombreArchivo)
    {
        $arrayDeObjeto = array();
        if($nombreArchivo != '' && file_exists($nombreArchivo))
        {
            $archivo = fopen($nombreArchivo,'r');
            if(filesize($nombreArchivo) > 0)
            {
                $lectura = fread($archivo,filesize($nombreArchivo));
            }
            else
            {
                $lectura = '{}';
            }
            fclose($archivo);
            $arrayDeObjeto = unserialize($lectura);
        }
        else
        {
            echo 'Ingrese un nombre valido';
        }
        return $arrayDeObjeto;
    }

    //SERIALIZA UN ARRAY EN UN ARCHIVO TXT
    public function Serializar($nombreArchivo,$arrayDeAlgo)
    {
        $serializo = false;
        if($nombreArchivo != '')
        {
            if(file_exists($nombreArchivo))
            {
                $arrayDeArray = ManejadorArchivo::Deserializar($nombreArchivo);
                foreach ($arrayDeAlgo as $value) 
                {
                    array_push($arrayDeArray,$value);
                }
                $archivo = fopen($nombreArchivo,'w');
                fwrite($archivo,serialize($arrayDeArray));
                $serializo = fclose($archivo);
            }
            else
            {
                $archivo = fopen($nombreArchivo,'w');
                fwrite($archivo,serialize($arrayDeAlgo));
                $serializo = fclose($archivo);
            }
            
        }
        else
        {
            echo 'Ingrese un nombre de archivo valido';
        }
        return $serializo;
    }
  


} 