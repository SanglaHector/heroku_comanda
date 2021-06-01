<?php
namespace Controllers;

use Interfaces\IDatabase;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Models\Ticket;

class TicketController implements IDatabase
{
    function addOne(Request $request, Response $response, $args)
    {
        $body = $request->getParsedBody();
        if( isset($body['id_mesa']) &&
            isset($body['id_foto']) &&
            isset($body['precio_total']))
            {
                $model = new Ticket();
                $respuesta = $model::insert($body['id_mesa'],$body['id_foto'],$body['precio_total']);
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
            $model = new Ticket();
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
        $model = new Ticket();
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
        if( isset($body['id_mesa']) &&
            isset($body['id_foto']) &&
            isset($body['precio_total']) &&
            isset($args['id']))
            {
                $model = new Ticket();
                $respuesta = $model::updateById($body['id_mesa'],$body['id_foto'],$body['precio_total'],
                $args['id']);
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
        $model = new Ticket();
        $respuesta = $model::deleteById($body);
        $response->getBody()->write(json_encode($respuesta));
        return $response;
    }
}