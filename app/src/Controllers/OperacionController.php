<?php
namespace Controllers;

use Interfaces\IDatabase;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Models\Operacion;
use Components\Retorno;
class OperacionController //implements IDatabase
{
    /*function addOne(Request $request, Response $response, $args)
    {
        $body = $request->getParsedBody();
        if( isset($body['entidad']) &&
            isset($body['id_entidad']) &&
            isset($body['id_usuario']) &&
            isset($body['id_estado']))
            {
                $model = new Operacion();
                $respuesta = $model::insert($body['entidad'],$body['id_entidad'],$body['id_usuario'],
                $body['id_usu_modif'],$body['id_estado']);
                $respuesta = new Retorno(true,$respuesta,null);
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
        $model = new Operacion();
        $respuesta = $model::get();
        $respuesta = new Retorno(true,$respuesta,null);
        $response->getBody()->write(json_encode($respuesta));
        return $response;
    }
    function update(Request $request, Response $response, $args)
    {
        $body = $request->getParsedBody();
        if( isset($body['entidad']) &&
            isset($body['id_entidad']) &&
            isset($body['id_usuario']) &&
            isset($body['id_estado']))
            {
                $model = new Operacion();
                $respuesta = $model::updateById($body['entidad'],$body['id_entidad'],$body['id_usuario'],
                $body['id_usu_modif'],$body['id_estado'],$args['id']);
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
        $model = new Operacion();
        $respuesta = $model::deleteById($body);
        $respuesta = new Retorno(true,$respuesta,null);
        $response->getBody()->write(json_encode($respuesta));
        return $response;
    }*/
}