<?php
namespace Controllers;

use Components\LogHandler;
use Components\MesasHandler;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Components\Retorno;
class LocalController{
    function close(Request $request, Response $response, $args)
    {
        MesasHandler::cerrarMesas();
        LogHandler::desloguear();
        $respuesta = new Retorno(false,"Local cerrado",null);
        $response->getBody()->write(json_encode($respuesta));
        return $response;
    }

}