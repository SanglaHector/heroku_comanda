<?php
namespace Controllers;

use Components\InterClass;
use Interfaces\IDatabase;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Models\Cliente;
use Components\Token;
use Components\Retorno;
use Enums\EtipoUsuario;
use Models\Mesa;
use Models\Ticket;

class ConsultaController
{
    //empleados
    function logsPorEmpleado()
    {

    }
    
    //pedidos
    function productoMasVendido()
    {

    }
    function productoMenosVendido()
    {

    }
    function pedidosFueraDeTiempo()
    {

    }
    function pedidosCancelados()
    {

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
       // $models = Mesa::mesaMasFacturo();
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
        $respuesta = new Retorno(true,"La mesa que mas facturo es la ".$numeroMesa." con un monto de $".$monto,null);
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
    //entre fechas
    //encuestas
}