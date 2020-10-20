<?php

require_once './clases/manejadorArchivo.php';
require_once './clases/servicio.php';
require_once './clases/vehiculo.php';

class Turno extends ManejadorArchivo
{
    public $patente;
    public $fecha;    
    public $marca;
    public $modelo;
    public $precio;
    public $tipoServicio;

    public function __construct($patente,$fecha,$marca,$modelo,$precio,$tipoServicio)
    {
        $this->patente = $patente;
        $this->fecha = $fecha;
        $this->marca = $marca;
        $this->modelo = $modelo;
        $this->precio = $precio;
        $this->tipoServicio = $tipoServicio;
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
        return $this->patente .'*'.$this->fecha.'*'. $this->marca .'*'. $this->modelo.'*'.$this->precio.'*'.$this->tipoServicio;
    }

    public static function LeerTurnoJSON()
    {
        $turnosLeidos = parent::LeerJSON(TURNOJSON);
        $listaTurnos = array();

        foreach ($turnosLeidos as $turno) 
        {
            $turnoNuevo = new Turno($turno->patente,$turno->fecha,$turno->marca,$turno->modelo,$turno->precio,$turno->tipoServicio);
            array_push($listaTurnos, $turnoNuevo);
        }

        return $listaTurnos;
    }

    public static function AltaTurno()
    {
        $creo = false;
        $patente = $_POST['patente'] ?? '';
        $fecha = $_POST['fecha'] ?? '';
        $idServicio = $_POST['idServicio'] ?? '';
        $vehiculo = Vehiculo::ObtenerVehiculo($patente);
        $servicio = Servicio::ObtenerServicio($idServicio);
        $turnoJson = self::LeerTurnoJSON();
        $nuevoTurno = new Turno($patente,$fecha,$vehiculo->marca,$vehiculo->modelo,$vehiculo->precio,$servicio->tipo);
        $arrayNuevo = $turnoJson;
        array_push($arrayNuevo, $nuevoTurno);
        //var_dump($servicioJson);
        if (ManejadorArchivo::GuardarJSON(TURNOJSON, $arrayNuevo)) 
        {
            $creo = true;
            echo '<br>Turno creado<br>';
        }
        
        return $creo;

    }    

}
