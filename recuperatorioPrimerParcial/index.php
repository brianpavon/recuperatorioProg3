<?php
const RUTAARCHIVO = './archivos/';
const USUARIOJSON = './archivos/usuario.json';
const TURNOJSON = './archivos/turno.json';
const VEHICULOTXT = './archivos/vehiculo.txt';
const SERVICIOJSON = './archivos/servicio.json';

require_once __DIR__.'./clases/usuario.php';
require_once __DIR__.'./clases/vehiculo.php';
require_once __DIR__.'./clases/manejadorArchivo.php';
require_once __DIR__.'./clases/servicio.php';
require_once __DIR__.'./clases/turno.php';
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
//echo 'hola';
$pathInfo = $_SERVER['PATH_INFO'];
$metodo = $_SERVER['REQUEST_METHOD'];

switch ($metodo) 
{
    case 'POST':
        switch ($pathInfo) 
        {
            case '/registro':
                if(Usuario::CrearUsuario())
                {
                    echo 'Registro exitoso';
                }
                else
                {
                    echo 'Verifique los datos';
                }                
                break;

            case '/login':
                if(Usuario::LoginUsuario())
                {
                    echo 'Usuario verificado';
                }
                else
                {
                    echo 'Verifique los datos';
                }                    
                break;
            case '/vehiculo':
                if(Usuario::PermitirPermisoUser(ObtenerToken()) || Usuario::PermitirPermisoAdmin(ObtenerToken()))
                {
                    Vehiculo::IngresarVehiculos();
                }   
                else
                {
                    echo 'Usuario Invalido';
                }      
                break;
            case '/servicio':
                if(Usuario::PermitirPermisoAdmin(ObtenerToken()) || Usuario::PermitirPermisoAdmin(ObtenerToken()))
                {
                   Servicio::AltaServicio();
                   
                }
                else
                {
                    echo 'Usuario invalido';
                }
                break;
                case '/stats':
                if(Usuario::PermitirPermisoAdmin(ObtenerToken()))
                {
                    
                    Servicio::BuscarServicio($_POST['tipoServicio']);
                }
                else
                {
                    echo 'Usuario invalido';
                }
                break;
                case '/turno':
                    if(Usuario::PermitirPermisoAdmin(ObtenerToken()) || Usuario::PermitirPermisoAdmin(ObtenerToken()))
                    {
                        //echo 'hola';
                        Turno::AltaTurno();
                    }
                    else
                    {
                        echo 'Usuario invalido';
                    }
                    break;
            default:
                echo 'Ruta Invalida';
                break;
        }
        
        break;
    case 'GET':
        
        $datosUrl = explode('/',$pathInfo);
        //var_dump($datosUrl[1]);
        if($datosUrl[1] == 'marca' || $datosUrl[1] == 'modelo' || $datosUrl[1] == 'patente')
        {
            
            $datos = $datosUrl[2]; 
            $tipoDatos = $datosUrl[1];
            
            if(Usuario::PermitirPermisoUser(ObtenerToken()) || Usuario::PermitirPermisoAdmin(ObtenerToken()))
            {
                //var_dump($patente);
                Vehiculo::MostrarBusqueda(strtoupper($datos),$tipoDatos);
            }
        }
        
        else if($datosUrl[1] == 'stats')
        {
            //var_dump($datosUrl[2]);
            if(Usuario::PermitirPermisoAdmin(ObtenerToken()))
            {
                //var_dump($datosUrl[2]);
                Servicio::BuscarServicio($datosUrl[2]);
            }
            else
            {
                echo 'Usuario invalido';
            }
        }

        break;
    
    default:
        echo 'Metodo invalido';
        break;
}