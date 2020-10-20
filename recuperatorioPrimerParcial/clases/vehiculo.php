<?php
require_once __DIR__ . './manejadorArchivo.php';

class Vehiculo extends ManejadorArchivo
{
    public $patente;
    public $marca;
    public $modelo;
    public $precio;

    public function __construct($patente,$marca,$modelo,$precio)
    {
        $this->patente = $patente;
        $this->marca = $marca;
        $this->modelo = $modelo;
        $this->precio = $precio;
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
        return $this->patente . '*' . $this->marca . '*' . $this->modelo.'*'.$this->precio;
    }

    public static function MostrarAuto($vehiculo)
    {
        echo 'Patente: '. $vehiculo->patente .' Marca: '. $vehiculo->marca .' Modelo: '. $vehiculo->modelo .' Precio: '. $vehiculo->precio .PHP_EOL;
    }

    public static function LeerVehiculoTxt()
    {
        $vehiculosLeidos = parent ::Leer(VEHICULOTXT);
        $listavehiculos = array();
        if(count($vehiculosLeidos)>0)
        {
            foreach ($vehiculosLeidos as $key => $value) 
            {
                if(count($value)>0)
                {
                    $vehiculoNuevo = new Vehiculo($value[0],$value[1],$value[2],$value[3]);
                    array_push($listavehiculos,$vehiculoNuevo);
                }
            }
        }
        return $listavehiculos;
    }

    public static function IngresarVehiculos()
    {
                        
        $patente = $_POST['patente']??'';
        $marca = $_POST['marca']??'';
        $modelo = $_POST['modelo']??'';
        $precio = $_POST['precio']?? 0;
        
        $nuevoVehiculo = new Vehiculo(strtoupper($patente),strtoupper($marca),strtoupper($modelo),$precio);
        $listaVehiculos = Vehiculo::LeerVehiculoTxt();
        if(!self::VerificarPatente($nuevoVehiculo))
        {
            array_push($listaVehiculos,$nuevoVehiculo);
            if(parent::Guardar(VEHICULOTXT,$nuevoVehiculo))
            {
                echo 'Auto creado de manera exitosa<br>';
                //var_dump($listaVehiculos);
            }
            else
            {
                echo 'No se creo ningun auto';
            }
        }
        else
        {
            echo 'Vehiculo repetido';
        }
        
    }

    private static function VerificarPatente($vehiculo)
    {
        $repetido = false;
        $listaVehiculos = Vehiculo::LeerVehiculoTxt();
        foreach ($listaVehiculos as $item) 
        {
            if($item->patente == $vehiculo->patente)
            {
                $repetido = true;
            }
        }
        return $repetido;
    }

    public static function ObtenerVehiculo($patente)
    {
        $listaVehiculos = self::LeerVehiculoTxt();
        
        foreach ($listaVehiculos as $item) 
        {
            if($item->patente == $patente)
            {
                $vehiculoNuevo = $item;
                break;
            }
        }
        return $vehiculoNuevo;
    }

    public static function MostrarBusqueda($dato,$tipoDato)
    {
        
        $listaVehiculos = self::LeerVehiculoTxt();
        //var_dump($listaVehiculos);
        foreach ($listaVehiculos as $item) 
        {
            //echo'hola';
            //var_dump($tipoDato);
            switch ($tipoDato) 
            {
                
                case 'patente':
                    if($item->patente == $dato)
                    {
                        $item::MostrarAuto($item);                     
                    }
                    break;
                case 'marca':
                    if($item->marca == $dato)
                    {
                        $item::MostrarAuto($item);
                    }
                    break;
                case 'modelo':
                    if($item->modelo == $dato)
                    {
                        $item::MostrarAuto($item);
                    }
                                    
                    break;
                default:
                    echo 'No existe '.$dato;
                    break;
            }
            /*if($item->patente == $dato || $item->marca == $dato || $item->modelo == $dato)
            {
                //$item->__toString();
                $item::MostrarAuto($item);
                //self::MostrarAuto($item);
                //break;
            }*/
            /*else
            {
                echo 'No existe '.$dato;
                break;
            }*/
        }
        
    }
}