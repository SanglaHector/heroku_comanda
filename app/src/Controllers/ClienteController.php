<?php
namespace Controllers;

use Interfaces\IDatabase;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Models\Cliente;

class ClienteController implements IDatabase
{
    function addOne(Request $request, Response $response, $args)
    {
        $body = $request->getParsedBody();
        if( isset($body['email']) &&
            isset($body['clave']))
            {
                $cliente = new Cliente();
                $respuesta = $cliente::insert($body['email'],$body['clave']);
                $response->getBody()->write(json_encode($respuesta));
            }else
            {
                $respuesta = "Faltan cargar datos";
                $response->getBody()->write(json_encode($respuesta));
            }
            return $response;
    }
    function getOne(Request $request, Response $response, $args)
    {
        if(isset($args['id']))
        {
            $cliente = new Cliente();
            $respuesta = $cliente::deleteById($args['id']);
            $response->getBody()->write(json_encode($respuesta));
        }else{
            $respuesta = "Faltan cargar datos";
            $response->getBody()->write(json_encode($respuesta));
        }
        return $response;
    }
    function getAll(Request $request, Response $response, $args)
    {
        $cliente = new Cliente();
        $respuesta = $cliente::get();
        $response->getBody()->write(json_encode($respuesta));
        return $response;
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
    function get(Request $request, Response $response, $args)
    {
    }
    function update(Request $request, Response $response, $args)
    {
        $body = $request->getParsedBody();
        if( isset($body['apellido']) &&
            isset($body['email']) &&
            isset($args['id']))
            {
                $cliente = new Cliente();
                $respuesta = $cliente::updateById($body['email'],$body['clave'],$args['id']);
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
        $cliente = new Cliente();
        $respuesta = $cliente::deleteById($body);
        $response->getBody()->write(json_encode($respuesta));
        return $response;
    }
}