<?php
namespace Middlewares;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response as ResponseMW;

use Models\Usuario;

use Enums\Emodels;

class MDWRequestParams// Aca se valida que exista todo lo que ingresa por request y que sea valido. 
{
    public $model;//ingreso todos los roles que pueden tener acceso

    public function __construct($model)
    {
        $this->model = $model;
    }
    public function __invoke(Request $request, RequestHandler $handler) : ResponseMW
    {
        $isVal = false;
        switch($this->model)
        {
            case Emodels::EMPLEADO:
                $isVal = MDWRequestParams::datosBasicosEmpleado($request);
                break;
            case Emodels::CLIENTE:
                $isVal = MDWRequestParams::datosBasicosCliente($request);
                break;
            case Emodels::MESA:
                $isVal = MDWRequestParams::datosBasicosMesa($request);
                break;
            case Emodels::PRODUCTO:
                $isVal = MDWRequestParams::datosBasicosProducto($request);
                break;
            case Emodels::PEDIDO:
                $isVal = MDWRequestParams::datosBasicosPedido($request);
            default:
                $isVal = false;
                break;
        }
        if($isVal)
        {
            $response = $handler->handle($request);
            $contenidoAPI = (string) $response->getBody();
            $response = new ResponseMW();
            $response->getBody()->write($contenidoAPI);
        }else
        {
            $response = new ResponseMW();
            $response->getBody()->write(json_encode('Error de request. Falta datos'));
        }
        return $response;
    }
    static private function datosBasicosEmpleado(Request $request)
    {
        $retorno = false;
        $body = $request->getParsedBody();
        if( isset($body['tipo_empleado']) &&
        isset($body['id_sector']) &&
        isset($body['nombre']) &&
        isset($body['apellido']) &&
        isset($body['email']) &&
        isset($body['clave']) &&
        isset($body['DNI']))
        {
            $retorno = true;
        }
        return $retorno;
    }
    static private function datosBasicosCliente(Request $request)
    {
        $retorno = false;
        $body = $request->getParsedBody();
        if( isset($body['email']) &&
        isset($body['clave']))
        {
            $retorno = true;
        }
        return $retorno;
    }
    static private function datosBasicosMesa(Request $request)
    {
        $retorno = false;
        $body = $request->getParsedBody();
        if( isset($body['id_empleado']) &&
        isset($body['id_cliente']) &&
        isset($body['id_estado']))
        {
            $retorno = true;
        }
        return $retorno;
    }
    static private function datosBasicosProducto(Request $request)
    {
        $retorno = false;
        $body = $request->getParsedBody();
        if( isset($body['id_sector']) &&
        isset($body['nombre']) &&
        isset($body['stock']) &&
        isset($body['precio']) &&
        isset($body['tiempo_preparacion']))
        {
            $retorno = true;
        }
        return $retorno;
    }
    static private function datosBasicosPedido(Request $request)
    {
        $retorno = false;
        $body = $request->getParsedBody();
        if( isset($body['id_ticket']) &&
        isset($body['id_producto']) &&
        isset($body['cantidad']) &&
        isset($body['id_estado']))
        {
            $retorno = true;
        }
        return $retorno;
    }
}
