<?php
namespace Controllers;

use Interfaces\IDatabase;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Models\Mesa;

class MesaController implements IDatabase
{
    function addOne(Request $request, Response $response, $args)
    {
        $body = $request->getParsedBody();
        if( isset($body['id_empleado']) &&
            isset($body['id_cliente']) &&
            isset($body['id_estado']))
            {
                $mesa = new Mesa();
                $respuesta = $mesa::insert($body['id_empleado'],$body['id_cliente'],
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
        $mesa = new Mesa();
        $respuesta = $mesa::get();
        $response->getBody()->write(json_encode($respuesta));
        return $response;
    }
    function update(Request $request, Response $response, $args)
    {
        $body = $request->getParsedBody();
        if( isset($body['id_empleado']) &&
            isset($body['id_cliente']) &&
            isset($body['id_estado']) &&
            isset($args['id']))
            {
                $mesa = new Mesa();
                $respuesta = $mesa::updateById($body['id_empleado'],$body['id_cliente'],
                $body['id_estado'],$args['id']);
                $response->getBody()->write(json_encode($respuesta));
            }else
            {
                $respuesta ="Faltan cargar datos";
                $response->getBody()->write(json_encode($respuesta));
            }
            return $response;
    }
    function delete(Request $request, Response $response, $args)
    {
        $body = $args['id'];
        $mesa = new Mesa();
        $respuesta = $mesa::deleteById($body);
        $response->getBody()->write(json_encode($respuesta));
        return $response;
    }
}