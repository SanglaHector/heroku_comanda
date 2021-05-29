<?php
namespace Controllers;

use Interfaces\IDatabase;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Models\Pedido;

class PedidoController implements IDatabase
{
    function addOne(Request $request, Response $response, $args)
    {
        $body = $request->getParsedBody();
        if( isset($body['id_ticket']) &&
            isset($body['id_producto']) &&
            isset($body['cantidad']) &&
            isset($body['id_estado']))
            {
                $pedido = new Pedido();
                $respuesta = $pedido::insert($body['id_ticket'],$body['id_producto'],$body['cantidad'],
                $body['id_estado']);
                $response->getBody()->write(json_encode($respuesta));
            }else
            {
                $response = "Faltan cargar datos";
            }
            return $response;
    }
    function getOne(Request $request, Response $response, $args)
    {
    }
    function get(Request $request, Response $response, $args)
    {
    }
    function deleteOne(Request $request, Response $response, $args)
    {
    }
    function deleteAll(Request $request, Response $response, $args)
    {
    }
    function updateOne(Request $request, Response $response, $args)
    {
    }
    function updateAll(Request $request, Response $response, $args)
    {
    }
    function getAll(Request $request, Response $response, $args)
    {
        $body = $request->getParsedBody();
        $pedido = new Pedido();
        $respuesta = $pedido::get();
        $response->getBody()->write(json_encode($respuesta));
        return $response;
    }
    function update(Request $request, Response $response, $args)
    {
        $body = $request->getParsedBody();
        if( isset($body['id_ticket']) &&
            isset($body['id_producto']) &&
            isset($body['cantidad']) &&
            isset($body['id_estado']) &&
            isset($args['id']))
            {
                $pedido = new Pedido();
                $respuesta = $pedido::updateById($body['id_ticket'],$body['id_producto'],$body['cantidad'],
                $body['id_estado'],$args['id']);
                $response->getBody()->write(json_encode($respuesta));
            }else
            {
                $respuesta = "Faltan cargar datos";
                $response->getBody()->write(json_encode($respuesta));
            }
            return $response;
    }
    function delete(Request $request, Response $response, $args)
    {
        $body = $args['id'];
        $pedido = new Pedido();
        $respuesta = $pedido::deleteById($body);
        $response->getBody()->write(json_encode($respuesta));
        return $response;
    }
}