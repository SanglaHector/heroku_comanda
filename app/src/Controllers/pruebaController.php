<?php
namespace Controllers;

use Components\InterClass;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Components\Retorno;

class PruebaController 
{
    function probarAutenticador(Request $request, Response $response, $args)
    {
        $usuario = InterClass::retornarUsuarioPorToken();
        $respuesta =  new Retorno(true,$usuario,null);
        $response->getBody()->write(json_encode($respuesta));
        return $response;
    }
}