<?php
namespace Controllers;

use Interfaces\IDatabase;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Models\Producto;

class ProductoController implements IDatabase
{
    function addOne(Request $request, Response $response, $args)
    {
        $body = $request->getParsedBody();
        if( isset($body['id_sector']) &&
            isset($body['nombre']) &&
            isset($body['stock']) &&
            isset($body['precio']) &&
            isset($body['tiempo_preparacion']))
            {
                $producto = new Producto();
                $respuesta = $producto::insert($body['id_sector'],$body['nombre'],
                $body['stock'],$body['precio'],$body['tiempo_preparacion']);
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
        //usuario
        $tipo = 2;//'empleado'
        $producto = new Producto();
        $respuesta = $producto::get();
        $response->getBody()->write(json_encode($respuesta));
        return $response;
    }
    function update(Request $request, Response $response, $args)
    {
        $body = $request->getParsedBody();
        if( isset($body['id_sector']) &&
            isset($body['nombre']) &&
            isset($body['stock']) &&
            isset($body['precio']) &&
            isset($body['tiempo_preparacion']) && 
            isset($args['id']))
            {
                $producto = new Producto();
                $respuesta = $producto::updateById($body['id_sector'],$body['nombre'],
                $body['stock'],$body['precio'],$body['tiempo_preparacion'],$args['id']);
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
        $producto = new Producto();
        $respuesta = $producto::deleteById($body);
        $response->getBody()->write(json_encode($respuesta));
        return $response;
    }
}