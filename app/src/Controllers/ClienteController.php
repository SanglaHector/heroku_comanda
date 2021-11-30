<?php
namespace Controllers;

use Components\InterClass;
use Interfaces\IDatabase;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Models\Cliente;
use Components\Token;
use Components\Retorno;
use Components\TicketHandler;
use Components\Archivo;
use Components\LoadCSV;
use Enums\Eestado;
use Enums\EtipoUsuario;
class ClienteController implements IDatabase
{
    function singIn(Request $request, Response $response, $args)
    {
        $body = $request->getParsedBody();
        if( isset($body['email']) && isset($body['clave']))
        {
            $model = new Cliente();
            $model = $model->getByKey('email',$body['email']);
            $clave = crypt($body['clave'],'SHA-256');
            if( !is_null($model) && 
                $model->email == $body['email'] 
                && $model->clave == $clave)
            {
                $tipoUsuario = EtipoUsuario::CLIENTE;
                $respuesta = new Retorno(true,Token::retornoToken($model->id,$tipoUsuario),null);
            }else{
                $respuesta = new Retorno(false,"Datos incorrectos",null);
            }
        }else{
            $respuesta = new Retorno(false,"Por favor, ingrese email y clave",null);
        }
        $response->getBody()->write(json_encode($respuesta));
        return $response;
    }

    function addOne(Request $request, Response $response, $args)
    {
        $body = $request->getParsedBody();
        if( isset($body['email']) &&
            isset($body['clave']))
            {
                $cliente = new Cliente();
                $respuesta = $cliente::insert($body['email'],$body['clave'],Eestado::SIN_MESA);
                $respuesta = new Retorno(true,$respuesta,null);
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
            $cliente = new Cliente();
            $respuesta = $cliente::deleteById($args['id']);
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
        $cliente = new Cliente();
        $respuesta = $cliente::get();
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
        if( isset($body['apellido']) &&
            isset($body['email']) &&
            isset($args['id']) &&
            isset($body['estado']))
            {
                $cliente = new Cliente();
                $respuesta = $cliente::updateById($body['email'],$body['clave'],$body['estado'],$args['id']);
                $respuesta = new Retorno(true,$respuesta,null);
            }else
            {
                $respuesta = "Faltan cargar datos";
                $respuesta = new Retorno(true,$respuesta,null);
            }
            $response->getBody()->write(json_encode($respuesta));
            return $response;
    }
    function delete(Request $request, Response $response, $args)
    {
        $body = $args['id'];
        $cliente = new Cliente();
        $respuesta = $cliente::deleteById($body);
        $respuesta = new Retorno(true,$respuesta,null);
        $response->getBody()->write(json_encode($respuesta));
        return $response;
    }
    function pedirCuenta(Request $request, Response $response, $args)
    {
        $cliente = InterClass::retornarUsuarioPorToken();
        $cliente = Cliente::getById($cliente->id);
        $precioFinal = TicketHandler::cerrarTicket($cliente,Eestado::SERVIDO);
        if(!is_null($precioFinal))
        {
            $response->getBody()->write(json_encode('El total de su pedido es de: $'.$precioFinal));
        }else
        {
            $response->getBody()->write(json_encode("Ha ocurrido un error en su ticket"));
        }
        return $response;
    }
}