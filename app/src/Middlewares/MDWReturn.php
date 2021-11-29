<?php
namespace Middlewares;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response as ResponseMW;

use Components\Retorno;

class MDWReturn 
{
    public function __invoke(Request $request, RequestHandler $handler) : ResponseMW
    {
        $response = $handler->handle($request);
        $contenidoAPI =  $response->getBody();
        $contenidoAPI = json_decode($contenidoAPI);
        $response = new ResponseMW();
        $response->getBody()->write(json_encode($contenidoAPI));
        return $response;
    }
}