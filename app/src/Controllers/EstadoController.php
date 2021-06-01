<?php
namespace Controllers;

use Interfaces\IDatabase;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Models\Estado;

class EstadoController implements IDatabase
{
    function addOne(Request $request, Response $response, $args)
    {
        $body = $request->getParsedBody();
        if( isset($body['estado']))
            {
                $model = new Estado();
                $respuesta = $model::insert($body['estado']);
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
            $model = new Estado();
            $respuesta = $model::deleteById($args['id']);
            $response->getBody()->write(json_encode($respuesta));
        }else{
            $respuesta = "Faltan cargar datos";
            $response->getBody()->write(json_encode($respuesta));
        }
        return $response;
    }
    function getAll(Request $request, Response $response, $args)
    {
        $model = new Estado();
        $respuesta = $model::get();
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
        if( isset($body['estado']) &&
            isset($args['id']))
            {
                $model = new Estado();
                $respuesta = $model::updateById($body['estado'],$args['id']);
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
        $model = new Estado();
        $respuesta = $model::deleteById($body);
        $response->getBody()->write(json_encode($respuesta));
        return $response;
    }
}