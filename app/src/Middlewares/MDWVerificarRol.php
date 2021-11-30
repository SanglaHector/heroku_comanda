<?php
namespace Middlewares;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response as ResponseMW;

use Controllers\UsuarioController;
use Components\Token;
use Components\Retorno;
use Enums\EtipoUsuario;
use Exception;

class MDWVerificarRol // verifico el rol: empleado(mozo, bartender, etc), socio o cliente
{
    public $roleArray;//ingreso todos los roles que pueden tener acceso

    public function __construct($roleArray)
    {
        $this->roleArray = $roleArray;
    }

    public function __invoke(Request $request, RequestHandler $handler) : ResponseMW
    {
        //$header = Token::getHeader('token');
        $header = Token::getAutentication($request);
        $rol =  Token::getRole($header);
        if(!is_null($rol) && in_array($rol,$this->roleArray))
        {
            $response = $handler->handle($request);
            $contenidoAPI = $response->getBody();
            $contenidoAPI = json_decode($contenidoAPI);
            $response = new ResponseMW();
            $response->getBody()->write(json_encode($contenidoAPI));

            return $response;
        }
        $response = new ResponseMW(); 
        $retorno = new Retorno(false,'No posee privilegios para hacer esta consulta.',null);
        $response->getBody()->write(json_encode($retorno));
        return $response;
    }
}