<?php

namespace Middlewares;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response as ResponseMW;

use Components\Token;
use Components\Retorno;
use Components\TratarEnums;
use Enums\Eestado;
use Models\Log;
use Models\Usuario;

class MDWGrabarLog // Cargo la tabla de logs
{
    public function __invoke(Request $request, RequestHandler $handler): ResponseMW
    {
        $response = $handler->handle($request);
        $contenidoAPI = $response->getBody();
        $contenidoAPI = json_decode($contenidoAPI);
        $response = new ResponseMW();
        if (isset($contenidoAPI->ok) && $contenidoAPI->ok) 
        {
            $id = Token::autenticarToken($contenidoAPI->data);
            $usuario = Usuario::getById($id);
            //me fijo que no este loguado
            if (isset($usuario->id)) 
            {
                $log = Log::getLastLog($usuario->id);
                $sector = TratarEnums::returnSector($usuario->tipo_empleado);
                if (!is_null($log)) //si no es nulo es por que hay un log
                {
                    if($log->in_out)//ya esta logueado
                    {
                        $response = new ResponseMW();
                        $retorno = new Retorno(false,'Ya se encuentra logueado',null);
                        $response->getBody()->write(json_encode($retorno));
                    }else
                    {//su ultimo log fue para salir
                        //veo en que sector lo logueo si es cocinero
                        if (is_array($sector)) //cocina o candy bar 3:cocina 4:candy bar
                        {
                            $log = Log::getLastSector(3);
                            if (!is_null($log)) 
                            {//pregunto si el log '3'(cocina) es un in o un out
                                $log->in_out ? $sector = 3 : $sector = 4;
                            } else 
                            {
                                $sector = 3;//no hay ningun log, lo mando a 3(COCINA)
                            }
                        }
                         $log = Log::insert($usuario->id,$sector,1);
                         $usuario = Usuario::chagenState($usuario,Eestado::TRABAJANDO);
                        $response->getBody()->write(json_encode($contenidoAPI));
                    }
                }else
                {//si no hay logs, lo logueo. Pasa cuando es nuevo el empleado
                    if (is_array($sector)) //cocina o candy bar 3:cocina 4:candy bar
                    {
                        $log = Log::getLastSector(3);
                        if (!is_null($log)) 
                        {//pregunto si el log '3'(cocina) es un in o un out
                            $log->in_out ? $sector = 4 : $sector = 3;
                        } else 
                        {
                            $sector = 3;//no hay ningun log, lo mando a 3(COCINA)
                        }
                    }
                    $log = Log::insert($usuario->id,$sector,1);
                    $usuario = Usuario::chagenState($usuario,Eestado::TRABAJANDO);
                    $response->getBody()->write(json_encode($contenidoAPI));
                }
            } else 
            {
                $response = new ResponseMW();
                $retorno = new Retorno(false, 'Error al loguearse', null);
                $response->getBody()->write(json_encode($retorno));
            }
        }
        else
        {
            $response = new ResponseMW();
            $retorno = new Retorno(false, 'Error al loguearse', null);
            $response->getBody()->write(json_encode($retorno));
        }
        return $response;
    }
    
}//revisar todas las validaciones