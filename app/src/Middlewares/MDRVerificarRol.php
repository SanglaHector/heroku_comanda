<?php
namespace Middlewares;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response as ResponseMW;

use Controllers\UsuarioController;
use Components\Token;
use Enums\EtipoUsuario;

class MDRVerificarRol // verifico el rol: empleado(mozo, bartender, etc), socio o cliente
{
    public $roleArray;//ingreso todos los roles que pueden tener acceso

    public function __construct($roleArray)
    {
        $this->roleArray = $roleArray;
    }

    public function __invoke(Request $request, RequestHandler $handler) : ResponseMW
    {
        $header = Token::getHeader('token');
        $rol =  Token::getRole($header);
        if(!is_null($rol) && in_array($rol,$this->roleArray))
        {
            $response = $handler->handle($request);
            $contenidoAPI = (string) $response->getBody();
            $response = new ResponseMW();
            $response->getBody()->write($contenidoAPI);

            return $response;
        }
        $response = new ResponseMW();
        $response->getBody()->write(json_encode('No posee privilegios para hacer esta consulta'));
        return $response;
    }
}