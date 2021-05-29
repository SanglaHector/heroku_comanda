<?php
namespace Middlewares;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response as ResponseMW;

use Models\Usuario;

use Components\Token;

class MDWVerificarToken
{
    public function __invoke(Request $request, RequestHandler $handler) : ResponseMW
    {//verifico si existe
        $header = Token::getHeader('token');//aca me traigo el header que ingresa el cliente por peticion
        if(!is_null($header))
        {
            $usuario = Token::autenticarToken($header);//retorna todo el usuario 
            if(isset($usuario->id))
            {
                if(!is_null(Usuario::getById($usuario->id)))
                {
                    //Invoco al siguiente MD
                    $response = $handler->handle($request);
                    //obtengo respuesta del MD
                    $contenidoAPI = (string) $response->getBody();
                    //genero nueva respuesta
                    $response = new ResponseMW();
                    //ejecuto acciones despues de invocar al siguiente MD
                    $response->getBody()->write($contenidoAPI);
                    return $response;
                }
            }
        }
        $response = new ResponseMW();
        $response->getBody()->write(json_encode('No posee privilegios para hacer esta consulta'));
        return $response;
    }
}
