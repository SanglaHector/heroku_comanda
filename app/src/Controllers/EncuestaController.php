<?php
namespace Controllers;

use Interfaces\IDatabase;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Models\Encuesta;

class EncuestaController implements IDatabase
{
    function addOne(Request $request, Response $response, $args)
    {
        $body = $request->getParsedBody();
        if( isset($body['id_cliente']) &&
            isset($body['mesa']) &&
            isset($body['restaurante']) &&
            isset($body['mozo']) &&
            isset($body['cocinero']) &&
            isset($body['id_ticket']) &&
            isset($body['descripcion']))
            {
                $model = new Encuesta();
                $respuesta = $model::insert($body['id_cliente'],$body['mesa'],$body['restaurante'],
                $body['mozo'],$body['cocinero'],$body['id_ticket'],$body['descripcion']);
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
            $model = new Encuesta();
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
        $model = new Encuesta();
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
        if( isset($body['id_cliente']) &&
            isset($body['mesa']) &&
            isset($body['restaurante']) &&
            isset($body['mozo']) &&
            isset($body['cocinero']) &&
            isset($body['id_ticket']) &&
            isset($body['descripcion']) &&
            isset($args['id']))
            {
                $model = new Encuesta();
                $respuesta = $model::updateById($body['id_cliente'],$body['mesa'],$body['restaurante'],
                $body['mozo'],$body['cocinero'],$body['id_ticket'],$body['descripcion'],$args['id']);
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
        $model = new Encuesta();
        $respuesta = $model::deleteById($body);
        $response->getBody()->write(json_encode($respuesta));
        return $response;
    }
}