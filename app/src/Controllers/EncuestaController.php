<?php
namespace Controllers;

use Components\InterClass;
use Interfaces\IDatabase;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Models\Encuesta;
use Components\Retorno;
use Components\Validaciones;

class EncuestaController implements IDatabase
{
    function addOne(Request $request, Response $response, $args)
    {
        $body = $request->getParsedBody();
        if( isset($body['id_ticket']) &&
            isset($body['mozo']) &&
            isset($body['comida']) &&
            isset($body['restaurante']) &&
            isset($body['descripcion']))
            {
                $model = new Encuesta();
                $ticket = Encuesta::getByKey('id_ticket',$body['id_ticket']);
                if(is_null($ticket)){
                    $ticket = InterClass::retornarTicket($body['id_ticket']);
                    $cliente = InterClass::retornarUsuarioPorToken();
                    if(!is_null($ticket))
                    {
                        $mesa = InterClass::returnMesaByTicket($ticket);
                        $mozo = InterClass::retornarUsuarioById($mesa->id_empleado);
                        if(is_null($mesa) || is_null($mozo))
                        {
                            $respuesta = new Retorno(true,"Inconsistencia de datos",null);
                        }else{
                            if($mesa->id_cliente != $cliente->id)
                            {
                                $respuesta = new Retorno(true,"El ticket ingresado no corresponde a este cliente.",null);
                            }else
                            {
                                if(is_numeric($body['mozo'])
                                && is_numeric($body['comida'])
                                && is_numeric($body['restaurante'])
                                && intval($body['mozo']) < 6 
                                && intval($body['comida']) < 6 
                                && intval($body['restaurante']) < 6)
                                {
                                    $respuesta = $model::insert($cliente->id,
                                                                $mesa->id,
                                                                $mozo->id,
                                                                $ticket->id,
                                                                $mesa->numero,
                                                                $body['mozo'],
                                                                $body['comida'],
                                                                $body['restaurante'],
                                                                $body['descripcion']);
                                    $respuesta = new Retorno(true,$respuesta,null);
                                }else
                                {
                                    $respuesta = new Retorno(true,"Debe ingresar valores numericos entre 0 y 5 para evaluar.",null);
                                }
                            }
                        }
                    }else{
                        $respuesta = new Retorno(true,"Numero de ticket incorrecto.",null);
                    }
                }else{
                    $respuesta = new Retorno(true,"Ya se ha evaluado una encuesta para este ticket.",null);
                }
            }else
            {
                $respuesta = "Faltan cargar datos";
                $respuesta = new Retorno(false,$respuesta,null);
            }
            $response->getBody()->write(json_encode($respuesta));
            return $response;
    }
    function getOne(Request $request, Response $response, $args)
    {
        if(isset($args['id']))
        {
            $model = new Encuesta();
            $respuesta = $model::deleteById($args['id']);
            $respuesta = new Retorno(true,$respuesta,null);
        }else{
            $respuesta = "Faltan cargar datos";
            $respuesta = new Retorno(false,$respuesta,null);
        }
        $response->getBody()->write(json_encode($respuesta));
        return $response;
    }
    function getAll(Request $request, Response $response, $args)
    {
        $model = new Encuesta();
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
        if( isset($body['id_cliente']) &&
            isset($body['mesa']) &&
            isset($body['restaurante']) &&
            isset($body['mozo']) &&
            isset($body['cocinero']) &&
            isset($body['id_ticket']) &&
            isset($body['descripcion']) &&
            isset($args['id']))
            {
                $respuesta = new Retorno(false,"Esta consulta no es posible",null);
            }else
            {
                $respuesta = "Faltan cargar datos";
                $respuesta = new Retorno(false,$respuesta,null);
            }
            $response->getBody()->write(json_encode($respuesta));
            return $response;
    }
    function delete(Request $request, Response $response, $args)
    {
        $body = $args['id'];
        $model = new Encuesta();
        $respuesta = $model::deleteById($body);
        $respuesta = new Retorno(true,$respuesta,null);
        $response->getBody()->write(json_encode($respuesta));
        return $response;
    }
}