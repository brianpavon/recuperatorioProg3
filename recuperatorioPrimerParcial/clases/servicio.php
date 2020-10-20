<?php
require_once __DIR__.'./manejadorArchivo.php';

class Servicio extends ManejadorArchivo
{
    public $id;
    public $tipo;
    public $precio;
    public $demora;

    public function __construct($id,$tipo,$precio,$demora)
    {
        $this->id = $id;
        $this->tipo=$tipo;
        $this->precio = $precio;
        $this->demora = $demora;    
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
        return $this->id . '*' . $this->tipo . '*' . $this->precio.'*'.$this->demora;
    }

    public static function AltaServicio()
    {
        $creo = false;
        $id = $_POST['id'] ?? 0;
        $tipo = $_POST['tipo'] ?? '';
        $precio = $_POST['precio'] ?? 0;
        $demora = $_POST['demora'] ?? '';
        $servicioJson = Servicio::LeerServicioJSON();
        $nuevoServicio = new Servicio($id, $tipo, $precio, $demora);
        $arrayNuevo = $servicioJson;
        array_push($arrayNuevo, $nuevoServicio);
        //var_dump($servicioJson);
        if (ManejadorArchivo::GuardarJSON(SERVICIOJSON, $arrayNuevo)) 
        {
            $creo = true;
            echo '<br>Service creado<br>';
        }
        
        return $creo;

    }    

    //LEE ARCHIVO JSON Y DEVUELVE LA LISTA DE SERVICIOS
    public static function LeerServicioJSON()
    {
        $serviciosLeidos = parent::LeerJSON(SERVICIOJSON);
        $listaServicios = array();

        foreach ($serviciosLeidos as $servicio) 
        {
            $servicioNuevo = new Servicio($servicio->id, $servicio->tipo, $servicio->precio, $servicio->demora);
            array_push($listaServicios, $servicioNuevo);
        }

        return $listaServicios;
    }

    public static function BuscarServicio($service)
    {
        $enLista = false;
        $listaServicios = self::LeerServicioJSON();
        
        foreach ($listaServicios as $item) 
        {
            if($item->tipo == $service)
            {
                $enLista = true;
                break;
            }
        }
        if($enLista == true)
        {
            foreach ($listaServicios as $item) 
            {
                var_dump('Nro. de servicio '.$item->id.' tipo '.$item->tipo.PHP_EOL);
            }
        }
        else
        {
            foreach ($listaServicios as $item) 
            {
                var_dump($item->__toString());
            }
        }
    }

    public static function ObtenerServicio($idServicio)
    {
        $listaServicios = self::LeerServicioJSON();
       
        $enLista = false;
        foreach ($listaServicios as $item) 
        {
            if($item->id == $idServicio)
            {
                $servicioNuevo = $item;
                $enLista = true;
                break;
            }            
        }
        if($enLista == true)
        {
            return $servicioNuevo;
        }
        else
        {
            echo 'Cargue primero servicios o verifique el ID';
        }
        
    }
}