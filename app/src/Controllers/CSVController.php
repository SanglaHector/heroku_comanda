<?php
namespace Controllers;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Models\Cliente;
use Models\Mesa;
use Models\Pedido;
use Models\Producto;
use Models\Ticket;
use Models\Usuario;

use Components\Retorno;
use Components\Archivo;
class CSVController{

    static function cargarArchivoCSV(Request $request, Response $response, $args)
    {
        $body = $request->getParsedBody();
        $models = array();
        if(isset($_FILES['archivo']) && 
           isset($body['tabla']))
        {
            $file = $_FILES['archivo'];    
            $models = CSVController::load($file,$body['tabla']);
            if(is_array($models) && count($models) > 0)
            {
                $respuesta = new Retorno(true,'Se han cargado los registros del archivo ', null);
            }else
            {
                $respuesta = new Retorno(false,'No se ha podido cargar el archivo ', null);
            }
        }else
        {
            $respuesta = new Retorno(false,"Faltan datos por cargar", null);
        }
        $response->getBody()->write(json_encode($respuesta));
        return $response;
    }

    static function load($file,$nameModel)
    {
        $extencion = explode('.',$file['name'])[1];
        $models = array();
        if($extencion == 'CSV')
        {
            $nameFile = Archivo::saveArchivo($file,'./Archivos/Input/');
            if($nameFile != false)
            {
                $models = array();
                $models = Archivo::loadArchivo($nameFile,true,'csv');
                foreach ($models as $model ) {
                    switch($nameModel)
                    {
                        case 'cliente':
                            $clientes = array();
                            $cliente = Cliente::convertToModelCSV($model);
                            array_push($clientes,$cliente);
                        foreach ($clientes as $cliente ) {
                            if(!Cliente::exist($cliente->id))
                            {
                                Cliente::insert($cliente->email,
                                                $cliente->clave,
                                                $cliente->id_estado);
                            }
                        }
                        $models = $clientes;
                            break;
                        case 'mesa':
                            $mesas = array();
                            $mesa = Mesa::convertToModelCSV($model);
                            array_push($mesas,$mesa);
                            foreach ($mesas as $mesa ) {
                                if(!Mesa::exist($mesa->id))
                                {
                                    Mesa::insert($mesa->id_empleado,
                                                $mesa->id_cliente,
                                                $mesa->id_estado,
                                                $mesa->numero);
                                }
                            }
                        $models = $mesas;
                            break;
                        case 'pedido':
                            $pedidos = array();
                            $pedido = Pedido::convertToModelCSV($model);
                            array_push($pedidos,$pedido);
                            foreach ($pedidos as $pedido ) {
                                if(!Pedido::exist($pedido->id))
                                {
                                    Pedido::insert($pedido->id_ticket,
                                                   $pedido->id_producto,
                                                    $pedido->cantidad,
                                                    $pedido->id_estado,
                                                    $pedido->hora_estimada);
                                }
                            }
                        $models = $pedidos;
                            break;
                            case 'producto':
                            $productos = array();
                            $producto = Producto::convertToModelCSV($model);
                            array_push($productos,$producto);
                            foreach ($productos as $cliente ) {
                                if(!Producto::exist($producto->id))
                                {
                                    Producto::insert($producto->id_sector,
                                                    $producto->nombre,
                                                    $producto->stock,
                                                    $producto->precio,
                                                    $producto->tiempo_preparacion);
                                }
                            }
                        $models = $productos;
                            break;
                        case 'ticket':
                            $tickets = array();
                            $ticket = Ticket::convertToModelCSV($model);
                            array_push($tickets,$ticket);
                            foreach ($tickets as $ticket ) {
                                if(!Ticket::exist($ticket->id))
                                {
                                    Ticket::insert($ticket->id_mesa,
                                                   $ticket->id_foto,
                                                    $ticket->precio_total);
                                }
                            }
                            $models = $tickets;
                            break;
                        case 'usuario':
                            $usuarios = array();
                            $usuario = Usuario::convertToModelCSV($model);
                            array_push($usuarios,$usuario);
                            foreach ($usuarios as $usuario ) {
                                if(!Usuario::exist($usuario->id))
                                {
                                    Usuario::insert($usuario->tipo_empleado,
                                                    $usuario->id_sector,
                                                    $usuario->id_estado,
                                                    $usuario->nombre,
                                                    $usuario->apellido,
                                                    $usuario->email,
                                                    $usuario->clave,
                                                    $usuario->DNI);
                                }
                            }
                            $models = $usuarios;
                            break;
                        default:
                            break;
                    }
                }
            }
        return $models;
        }
    }
    static function generarCSV(Request $request, Response $response, $args)
    {
        if(isset($args['tabla']))
        {   
            $archivo = CSVController::unload($args['tabla']);
            if($archivo != "")
            {
                $respuesta = new Retorno(true,'Se ha descargado el archivo ', null);
            }else
            {
                $respuesta = new Retorno(false,'No se ha podido descargar el archivo ', null);
            }
        }else
        {
            $respuesta = new Retorno(false,"Faltan datos por cargar", null);
        }
        $response->getBody()->write(json_encode($respuesta));
        return $response;
    }
    static function unload($tabla)
    {
        $path = "";
        $cabecera = "";
        $models = array();
        if (!file_exists('./archivos/output/')) {
            mkdir('./archivos/output/', 0777, true);
        }
        switch($tabla)
        {
            case 'cliente':
                $cabecera = "id,email,clave,id_estado;";
                $path = './archivos/output/clientes.csv';
                $modelsClie = Cliente::get();
                foreach ($modelsClie as $model ) {
                    $cliente = Cliente::toCSV($model);
                    array_push($models,$cliente);
                }
                break;
            case 'mesa':
                $modelsMesa = Mesa::get();
                $cabecera = "id,id_empleado,id_cliente,id_estado,numero;";
                $path = './archivos/output/mesas.csv';
                foreach ($modelsMesa as $model ) {
                    $mesa = Mesa::toCSV($model);
                    array_push($models,$mesa);
                }
                break;
            case 'pedido':
                $modelsPed = Pedido::get();
                $cabecera = "id,id_ticket,id_producto,cantidad,id_estado,hora_estimada,hora_final;";
                $path = './archivos/output/pedidos.csv';
                foreach ($modelsPed as $model ) {
                    $pedido = Pedido::toCSV($model);
                    array_push($models,$pedido);
                }
                break;
            case 'producto':
                $modelsProd = Producto::get();
                $cabecera = "id,id_sector,nombre,stock,tiempo_preparacion;";
                $path = './archivos/output/productos.csv';
                foreach ($modelsProd as $model ) {
                    $producto = Producto::toCSV($model);
                    array_push($models,$producto);
                }
                break;
            case 'ticket';
                $modelsTic = Ticket::get();
                $cabecera = "id,id_mesa,id_foto,precio_total;";
                $path = './archivos/output/tickets.csv';
                foreach ($modelsTic as $model ) {
                    $ticket = Ticket::toCSV($model);
                    array_push($models,$ticket);
                }
                break;
            case 'usuario':
                $path = './archivos/output/usuarios.csv';
                $cabecera = "id,tipo_empleado,id_sector,id_estado,nombre,apellido,email,DNI;";
                $modelsUsu = Usuario::get();
                foreach ($modelsUsu as $model ) {
                    $usuario = Usuario::toCSV($model);
                    array_push($models,$usuario);
                }
                break;
            default:
                break;
        }
        if(file_exists($path))
        {
            unlink($path);
        }
        Archivo::Guardar($path,$cabecera.PHP_EOL,'csv');
        foreach ($models as $model ) {
            Archivo::Guardar($path,$model,'csv');
        }
        return $path;
    }
}