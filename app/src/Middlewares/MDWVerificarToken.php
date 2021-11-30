<?php
namespace Middlewares;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response as ResponseMW;

use Models\Usuario;
use Models\Cliente;;

use Components\Token;
use Components\Retorno;
use Exception;

class MDWVerificarToken
{
    public function __invoke(Request $request, RequestHandler $handler) : ResponseMW
    {//verifico si existe
    //    $header = Token::getHeader('token');//aca me traigo el header que ingresa el cliente por peticion
        try
        {
            $header = Token::getAutentication($request);
            if(!is_null($header))
            {
                $id = Token::autenticarToken($header);//retorna todo el usuario 
                $tipo = Token::returnTipoToken($header);
                if($tipo == 6) //cliente
                {
                    if(!is_null(Cliente::getById($id)))
                    {
                        //Invoco al siguiente MD
                        $response = $handler->handle($request);
                        //obtengo respuesta del MD
                        $contenidoAPI =  $response->getBody();
                        $contenidoAPI = json_decode($contenidoAPI);
                        //genero nueva respuesta
                        $response = new ResponseMW();
                        //ejecuto acciones despues de invocar al siguiente MD
                        $response->getBody()->write(json_encode($contenidoAPI));
                        return $response;
                    }
                }else //usuario
                {
                    if(isset($id))
                    {
                        if(!is_null(Usuario::getById($id)))
                        {
                            //Invoco al siguiente MD
                            $response = $handler->handle($request);
                            //obtengo respuesta del MD
                            $contenidoAPI =  $response->getBody();
                            $contenidoAPI = json_decode($contenidoAPI);
                            //genero nueva respuesta
                            $response = new ResponseMW();
                            //ejecuto acciones despues de invocar al siguiente MD
                            $response->getBody()->write(json_encode($contenidoAPI));
                            return $response;
                        }
                    }
    
                }
            }
            $response = new ResponseMW();
            $retorno = new Retorno(false,'El token ingresado es incorrecto o se ha vencido.',null);
        }catch(Exception $e)
        {
            //Invoco al siguiente MD
            //$response = $handler->handle($request);
            //obtengo respuesta del MD
            //$contenidoAPI =  $response->getBody();
            //$contenidoAPI = json_decode($contenidoAPI);
            //genero nueva respuesta
            $response = new ResponseMW();
            //ejecuto acciones despues de invocar al siguiente MD
            $retorno = new Retorno(false,"Ha ocurrido un error inesperado",$e->getMessage());
        }
        $response->getBody()->write(json_encode($retorno));
        return $response;
    }
}
