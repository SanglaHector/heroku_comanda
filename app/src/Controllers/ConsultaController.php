<?php
namespace Controllers;

use Components\InterClass;
use Interfaces\IDatabase;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Models\Cliente;
use Components\Token;
use Components\Retorno;
use Components\Validaciones;
use Enums\EtipoUsuario;
use Models\Mesa;
use Models\Ticket;
use Models\Pedido;
use Models\Log;
use Models\Operacion;
use stdClass;

class ConsultaController
{
    //empleados
    function ingresos(Request $request, Response $response, $args)
    {
        $models = array();
        $logs = Log::ingresos();
        foreach ($logs as $log ) {
            $stdClass = new stdClass();
            $stdClass->usuario = $log['apellido'];
            $stdClass->entrada = $log['created_at'];
            array_push($models,$stdClass);
        }
        $respuesta = new Retorno(true,$models,null);
        $response->getBody()->write(json_encode($respuesta));
        return $response;
    }
    function cantidadOperaciones(Request $request, Response $response, $args)
    {
        $sector = 0;
        if(isset($args['sector'])){
            switch($args['sector'])
            {
                case 'COCINA':
                    $sector = 3;
                    break;
                case 'BAR':
                    $sector = 1;
                    break;
                case 'CANDY_BAR':
                    $sector = 4;
                    break;
                case 'CERVECERIA':
                    $sector = 2;
                    break;
                default:
                    $respuesta = new Retorno(false,"Por favor cargue un sector: COCINA,BAR,CANDY_BAR O CERVECERIA.",null);
                    $response->getBody()->write(json_encode($respuesta));
                    return $response;
            }
            $models = Operacion::cantidadOperaciones($sector);
            $respuesta = new Retorno(true,"La cantidad de operaciones es de: ".$models,null);
        }else
        {
            $respuesta = new Retorno(false,"Por favor cargue un sector: COCINA,BAR,CANDY_BAR O CERVECERIA.",null);
        }
        $response->getBody()->write(json_encode($respuesta));
        return $response;
    }
    function cantidadOperacionesPorUsuario(Request $request, Response $response, $args)
    {
        $sector = 0;
        $retorno = array();
        if(isset($args['sector'])){
            switch($args['sector'])
            {
                case 'COCINA':
                    $sector = 3;
                    break;
                case 'BAR':
                    $sector = 1;
                    break;
                case 'CANDY_BAR':
                    $sector = 4;
                    break;
                case 'CERVECERIA':
                    $sector = 2;
                    break;
                default:
                    $respuesta = new Retorno(false,"Por favor cargue un sector: COCINA,BAR,CANDY_BAR O CERVECERIA.",null);
                    $response->getBody()->write(json_encode($respuesta));
                    return $response;
            }
            $models = Operacion::cantidadOperacionesPorUsu($sector);
            foreach ($models as $model ) {
                $stdClass = new stdClass();
                $stdClass->cantidad = $model['count(*)'];
                $stdClass->apellido = $model['apellido'];
                array_push($retorno,$stdClass);
            }
            $respuesta = new Retorno(true,$retorno,null);
        }else
        {
            $respuesta = new Retorno(false,"Por favor cargue un sector: COCINA,BAR,CANDY_BAR O CERVECERIA.",null);
        }
        $response->getBody()->write(json_encode($respuesta));
        return $response;
    }
    function operacionesPorUsuario(Request $request, Response $response, $args)
    {
        $retorno = array();
        $models = Operacion::operacionesPorUsuario();
        foreach ($models as $model ) {
            $stdClass = new stdClass();
            $stdClass->cantidad = $model['count(*)'];
            $stdClass->apellido = $model['apellido'];
            array_push($retorno,$stdClass);
        }
        $respuesta = new Retorno(true,$retorno,null);
        $response->getBody()->write(json_encode($respuesta));
        return $response;
    }
    //pedidos
    function productoMasVendido(Request $request, Response $response, $args)
    {
        $producto = "";
        $cantidad = 0;
        $models = Pedido::masVendido();
        foreach ($models as $model ) {
            if($cantidad <  $model['sum(pedidos.cantidad)'])
            {
                $cantidad = $model['sum(pedidos.cantidad)'];
                $producto = $model['nombre'];
            }
        }
        $respuesta = new Retorno(true,"El producto mas vendido es ".$producto." con una cantidad de ".$cantidad,null);
        $response->getBody()->write(json_encode($respuesta));
        return $response;
    }
    function pedidosFueraDeTiempo(Request $request, Response $response, $args)
    {
        $models = array();
        $pedidos = Pedido::fueraDeTiempo();
        foreach ($pedidos as $pedido ) {
            $stdClass = new stdClass();
            $stdClass->producto = $pedido['nombre'];
            $stdClass->hora_estimada = $pedido['hora_estimada'];
            $stdClass->hora_final = $pedido['hora_final'];
            array_push($models,$stdClass);
        }
        $respuesta = new Retorno(true,$models,null);
        $response->getBody()->write(json_encode($respuesta));
        return $response;
    }
    function pedidosCancelados(Request $request, Response $response, $args)
    {
        $models = Pedido::cancelados();
        $respuesta = new Retorno(true,$models,null);
        $response->getBody()->write(json_encode($respuesta));
        return $response;
    }
    //mesas
    function mesaMasUsada(Request $request, Response $response, $args)
    {
        $numeroMesa = 0;
        $cantidad = 0;
        $mesas = Mesa::mesasUsadas();
        foreach ($mesas as $mesa ) {
            if($cantidad <  $mesa['count(numero)'])
            {
                $cantidad = $mesa['count(numero)'];
                $numeroMesa = $mesa['numero'];
            }
        }
        $respuesta = new Retorno(true,"La mesa mas usada es la ".$numeroMesa.' con una cantidad de '.$cantidad. ' clientes en total.',null);
        $response->getBody()->write(json_encode($respuesta));
        return $response;
    }
    function mesaMenosUsada(Request $request, Response $response, $args)
    {
        $numeroMesa = 0;
        $cantidad = 99999;
        $mesas = Mesa::mesasUsadas();
        foreach ($mesas as $mesa ) {
            if($cantidad >  $mesa['count(numero)'])
            {
                $cantidad = $mesa['count(numero)'];
                $numeroMesa = $mesa['numero'];
            }
        }
        $respuesta = new Retorno(true,"La mesa menos usada es la ".$numeroMesa.' con una cantidad de '.$cantidad. ' clientes en total.',null);
        $response->getBody()->write(json_encode($respuesta));
        return $response;
    }
    function mesaMasFacturo(Request $request, Response $response, $args)
    {
        $numeroMesa = 0;
        $monto = 0;
        $models = Ticket::FacturaPorMesa();
        foreach ($models as $mesa ) {
            if($monto <  $mesa['SUM(tickets.precio_total)'])
            {
                $monto = $mesa['SUM(tickets.precio_total)'];
                $numeroMesa = $mesa['numero'];
            }
        }
        $respuesta = new Retorno(true,"La mesa que mas facturo es la ".$numeroMesa." con un monto de $".$monto,null);
        $response->getBody()->write(json_encode($respuesta));
        return $response;
    }
    function mesaMenosFacturo(Request $request, Response $response, $args)
    {
        $numeroMesa = 0;
        $monto = 999999999999;
        $models = Ticket::FacturaPorMesa();
        foreach ($models as $mesa ) {
            if($monto <  $mesa['SUM(tickets.precio_total)'])
            {
                $monto = $mesa['SUM(tickets.precio_total)'];
                $numeroMesa = $mesa['numero'];
            }
        }
        $respuesta = new Retorno(true,"La mesa que menos facturo es la ".$numeroMesa." con un monto de $".$monto,null);
        $response->getBody()->write(json_encode($respuesta));
        return $response;
    }
    function mesaMayorImporte(Request $request, Response $response, $args)
    {
        $numeroMesa = 0;
        $monto = 0;
        $models = Mesa::MayorImporte();
        foreach ($models as $mesa ) {
            if($monto <  $mesa['max(precio_total)'])
            {
                $monto = $mesa['max(precio_total)'];
                $numeroMesa = $mesa['numero'];
            }
        }
        $respuesta = new Retorno(true,"La mesa con mayor importe es la ".$numeroMesa." con un monto de $".$monto,null);
        $response->getBody()->write(json_encode($respuesta));
        return $response;
    }
    function masFacturoEntreFechas(Request $request, Response $response, $args)
    {
        $numeroMesa = 0;
        $monto = 0;
        $models = array();
        if(isset($args['fDesde']) &&
           isset($args['fHasta']))
           {
               $fechaDesde = $args['fDesde'];
               $fechaHasta = $args['fHasta'];
               if(Validaciones::validarFecha($fechaHasta) &&
                 Validaciones::validarFecha($fechaDesde))
                 {
                     $models = Mesa::masFacturoEntreFechas($fechaDesde,$fechaHasta);
                     foreach ($models as $mesa ) {
                         if($monto <  $mesa['sum(precio_total)'])
                         {
                             $monto = $mesa['sum(precio_total)'];
                             $numeroMesa = $mesa['numero'];
                         }
                     }
                 }
           }
        $respuesta = new Retorno(true,"La mesa con mayor importe es la ".$numeroMesa." con un monto de $".$monto,null);
        $response->getBody()->write(json_encode($respuesta));
        return $response;
    }
    //encuestas --  comentarios
}