<?php
namespace Controllers;

use Interfaces\IDatabase;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Models\Ticket;
use Components\Retorno;
use Components\Archivo;
use Components\InterClass;

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
            $model = new Ticket();
            $respuesta = $model::getById($args['id']);
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
        $model = new Ticket();
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
        if( isset($body['id_mesa']) &&
            isset($body['id_foto']) &&
            isset($body['precio_total']) &&
            isset($args['id']))
            {
                $model = new Ticket();
                $respuesta = $model::updateById($body['id_mesa'],$body['id_foto'],$body['precio_total'],
                $args['id']);
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
        $model = new Ticket();
        $respuesta = $model::deleteById($body);
        $respuesta = new Retorno(true,$respuesta,null);
        $response->getBody()->write(json_encode($respuesta));
        return $response;
    }
    function addPhoto(Request $request, Response $response ,$args)
    {
        //validar ids
        $body = $request->getParsedBody();
        if(isset($body['id_ticket']) &&
           isset($body['id_mesa']) &&
           isset($_FILES['imagen']) )
        {
            $ticket = Ticket::getById($body['id_ticket']);
            if(!is_null($ticket) )
            {
                $mesa = InterClass::returnMesaByTicket($ticket);

                if(!is_null($mesa) && $mesa->id == $body['id_mesa'])
                {
                    //armar nombre
                    $nombreArchivo = Ticket::createPhotoName($body['id_mesa'],$body['id_ticket'],'./imagenesTicket');
                    //guardar imagen
                    Archivo::guardarImagen($_FILES['imagen'],'./imagenesTicket/',$nombreArchivo);
                    //validar si se guardo ok
                    $respuesta = new Retorno(true,"llego ok",null);
                }else
                {
                    $respuesta = new Retorno(false,"Numero de mesa no corresponde con Ticket", null);
                }
            }else
            {
                $respuesta = new Retorno(false,"Numero de ticket incorrecto",null);
            }
        }else
        {
            $respuesta = new Retorno(true,"Por favor, cargar todos los datos", null);
        }
        $response->getBody()->write(json_encode($respuesta));
        return $response;
    }
}