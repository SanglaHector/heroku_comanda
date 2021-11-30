<?php
namespace Controllers;

use Interfaces\IDatabase;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Models\Usuario;
use Components\Token;
use Components\Retorno;
use Components\InterClass;
use Components\LogHandler;
use Enums\Eestado;
use stdClass;
use Exception;

class UsuarioController implements IDatabase
{ 
    function singIn(Request $request, Response $response, $args)
    {
        $body = $request->getParsedBody();
        if( isset($body['email']) && isset($body['clave']))
        {
            $usuario = new Usuario();
            $usuario = $usuario->getByKey('email',$body['email']);
            $clave = crypt($body['clave'],'SHA-256');
            if( !is_null($usuario) && 
                $usuario->email == $body['email'] 
                && $usuario->clave == $clave)
            {
                //tocar aca para ver que voy a guardar en el token
                $respuesta = Token::retornoToken($usuario->id,$usuario->tipo_empleado);
                $respuesta = new Retorno(true,$respuesta,null);
            }else{
                $respuesta = "Datos incorrectos";
                $respuesta = new Retorno(false,$respuesta,null);
            }
        }else{
            $respuesta = "Por favor, ingrese email y clave";
        }
        $response->getBody()->write(json_encode($respuesta));
        return $response;
    }
    function singOut(Request $request, Response $response, $args)
    {
        try
        {
            $model = InterClass::retornarUsuarioPorToken();
            if(!is_null($model)){
                if($model->id_estado == Eestado::TRABAJANDO)
                {
                    LogHandler::desloguearEmpleado($model);
                    $respuesta = new Retorno(true,"Que descances.",null);
                }else
                {
                    $respuesta = new Retorno(true,"Usted no se encuentra trabajando.",null);
                }
            }else{
                $respuesta = new Retorno(false,"Usted no se encuentra registrado.",null);
            }
        }catch(Exception $e)
        {
            $respuesta = new Retorno(false,"Ha ocurrido un error inesperado",$e->getMessage());
        }
        $response->getBody()->write(json_encode($respuesta));
        return $response;
    }
    function addOne(Request $request, Response $response, $args)
    {
        $body = $request->getParsedBody();
        
        $usuario = new Usuario();
        $respuesta = $usuario::insert($body['tipo_empleado'],$body['id_sector'],$body['id_estado'],$body['nombre'],
        $body['apellido'],$body['email'],$body['clave'],$body['DNI']);
        $respuesta = new Retorno(true,$respuesta,null);
        $response->getBody()->write(json_encode($respuesta));
        
        return $response;
    }
    function getOne(Request $request, Response $response, $args)
    {
        if(isset($args['id']))
        {
            $usuario = new Usuario();
            $respuesta = $usuario::deleteById($args['id']);
            $respuesta = new Retorno(true,$respuesta,null);
        }else{
            $respuesta = "Faltan cargar datos";
            $respuesta = new Retorno(false,$respuesta,null);
        }
        $response->getBody()->write(json_encode($respuesta));
        return $response;
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
        $usuario = new Usuario();
        $respuesta = $usuario::get();
        $respuesta = new Retorno(true,$respuesta,null);
        $response->getBody()->write(json_encode($respuesta));
        return $response;
    }
    function update(Request $request, Response $response, $args)
    {
        $body = $request->getParsedBody();
        if( isset($body['tipo_empleado']) &&
            isset($body['id_sector']) &&
            isset($body['id_estado']) &&
            isset($body['nombre']) &&
            isset($body['apellido']) &&
            isset($body['email']) &&
            isset($body['clave']) &&
            isset($body['DNI']) && 
            isset($args['id']))
            {
                $usuario = new Usuario();
                $respuesta = $usuario::updateById($body['tipo_empleado'],$body['id_sector'],$body['id_estado'],$body['nombre'],
                $body['apellido'],$body['email'],$body['clave'],$body['DNI'],$args['id']);
                $respuesta = new Retorno(true,$respuesta,null);
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
        $usuario = new Usuario();
        $respuesta = $usuario::deleteById($body);
        $respuesta = new Retorno(true,$respuesta,null);
        $response->getBody()->write(json_encode($respuesta));
        return $response;
    }
}