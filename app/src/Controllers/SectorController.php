<?php
namespace Controllers;

use Interfaces\IDatabase;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Models\Sector;
use Components\Retorno;

class SectorController implements IDatabase
{
    function addOne(Request $request, Response $response, $args)
    {
        $body = $request->getParsedBody();
        if( isset($body['nombre']) &&
            isset($body['id_tipo_empleado']))
            {
                $model = new Sector();
                $respuesta = $model::insert($body['nombre'],$body['id_tipo_empleado']);
                $respuesta = new Retorno(true,$respuesta,null);
                $response->getBody()->write(json_encode($respuesta));
            }else
            {
                $respuesta = "Faltan cargar datos";
                $respuesta = new Retorno(false,$respuesta,null);
                $response->getBody()->write(json_encode($respuesta));
            }
            return $response;
    }
    function getOne(Request $request, Response $response, $args)
    {
        if(isset($args['id']))
        {
            $model = new Sector();
            $respuesta = $model::deleteById($args['id']);
            $respuesta = new Retorno(true,$respuesta,null);
            $response->getBody()->write(json_encode($respuesta));
        }else{
            $respuesta = "Faltan cargar datos";
            $respuesta = new Retorno(false,$respuesta,null);
            $response->getBody()->write(json_encode($respuesta));
        }
        return $response;
    }
    function getAll(Request $request, Response $response, $args)
    {
        $model = new Sector();
        $respuesta = $model::get();
        $respuesta = new Retorno(true,$respuesta,null);
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
        if( isset($body['nombre']) &&
            isset($body['id_tipo_empleado']) &&
            isset($args['id']))
            {
                $model = new Sector();
                $respuesta = $model::updateById($body['nombre'],$body['id_tipo_empleado'],$args['id']);
                $respuesta = new Retorno(true,$respuesta,null);
                $response->getBody()->write(json_encode($respuesta));
            }else
            {
                $respuesta = "Faltan cargar datos";
                $respuesta = new Retorno(false,$respuesta,null);
                $response->getBody()->write(json_encode($respuesta));
            }
            return $response;
    }
    function delete(Request $request, Response $response, $args)
    {
        $body = $args['id'];
        $model = new Sector();
        $respuesta = $model::deleteById($body);
        $respuesta = new Retorno(true,$respuesta,null);
        $response->getBody()->write(json_encode($respuesta));
        return $response;
    }
}