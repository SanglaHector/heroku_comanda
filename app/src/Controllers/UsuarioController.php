<?php
namespace Controllers;

use Interfaces\IDatabase;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Models\Usuario;
use Components\Token;
class UsuarioController implements IDatabase
{ 
    function singIn(Request $request, Response $response, $args)
    {
        $body = $request->getParsedBody();
        if( isset($body['email']) && isset($body['clave']))
        {
            $usuario = new Usuario();
            $usuario = $usuario->getByKey('email',$body['email']);
            
            if( !is_null($usuario) && 
                $usuario->email == $body['email'] 
                && $usuario->clave == $body['clave'])
            {
                $respuesta = Token::retornoToken($usuario,$usuario->tipo_empleado);
            }else{
                $respuesta = "Datos incorrectos";
            }
        }else{
            $respuesta = "Por favor, ingrese email y clave";
        }
        $response->getBody()->write(json_encode($respuesta));
        return $response;
    }
    function addOne(Request $request, Response $response, $args)
    {
        $body = $request->getParsedBody();
        
        $usuario = new Usuario();
        $respuesta = $usuario::insert($body['tipo_empleado'],$body['id_sector'],$body['nombre'],
        $body['apellido'],$body['email'],$body['clave'],$body['DNI']);
        $response->getBody()->write(json_encode($respuesta));
        
        return $response;
    }
    
    function getOne(Request $request, Response $response, $args)
    {
        if(isset($args['id']))
        {
            $usuario = new Usuario();
            $respuesta = $usuario::deleteById($args['id']);
            $response->getBody()->write(json_encode($respuesta));
        }else{
            $respuesta = "Faltan cargar datos";
            $response->getBody()->write(json_encode($respuesta));
        }
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
        $response->getBody()->write(json_encode($respuesta));
        return $response;
    }
    function update(Request $request, Response $response, $args)
    {
        $body = $request->getParsedBody();
        if( isset($body['tipo_empleado']) &&
            isset($body['id_sector']) &&
            isset($body['nombre']) &&
            isset($body['apellido']) &&
            isset($body['email']) &&
            isset($body['clave']) &&
            isset($body['DNI']) && 
            isset($args['id']))
            {
                $usuario = new Usuario();
                $respuesta = $usuario::updateById($body['tipo_empleado'],$body['id_sector'],$body['nombre'],
                $body['apellido'],$body['email'],$body['clave'],$body['DNI'],$args['id']);
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
        $usuario = new Usuario();
        $respuesta = $usuario::deleteById($body);
        $response->getBody()->write(json_encode($respuesta));
        return $response;
    }
}