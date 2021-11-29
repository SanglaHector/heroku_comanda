<?php
namespace Controllers;

use Interfaces\IDatabase;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Models\Mesa;
use Components\Retorno;
use Components\InterClass;
use Components\StateHandler;
use Components\ClienteHandler;
use Enums\Eestado;
use Enums\EMesas;
use stdClass;

class MesaController implements IDatabase
{
    function addOne(Request $request, Response $response, $args)
    {
        $body = $request->getParsedBody();
        if( isset($body['id_empleado']) &&
            isset($body['id_cliente']) &&
            isset($body['id_estado']) &&
            isset($body['numero']))
            {
                $mesa = new Mesa();
                $respuesta = $mesa::insert($body['id_empleado'],$body['id_cliente'],
                $body['id_estado'],$body['numero']);
                $respuesta = new Retorno(true,$respuesta,null);
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
        $mesa = new Mesa();
        $respuesta = $mesa::get();
        $respuesta = new Retorno(true,$respuesta,null);
        $response->getBody()->write(json_encode($respuesta));
        return $response;
    }
    function update(Request $request, Response $response, $args)
    {
        $body = $request->getParsedBody();
        if( isset($body['id_empleado']) &&
            isset($body['id_cliente']) &&
            isset($body['id_estado']) &&
            isset($args['id']))
            {
                $mesa = new Mesa();
                $respuesta = $mesa::updateById($body['id_empleado'],$body['id_cliente'],
                $body['id_estado'],$body['numero'],$args['id']);
                $respuesta = new Retorno(true,$respuesta,null);
                $response->getBody()->write(json_encode($respuesta));
            }else
            {
                $respuesta ="Faltan cargar datos";
                $respuesta = new Retorno(false,$respuesta,null);
                $response->getBody()->write(json_encode($respuesta));
            }
            return $response;
    }
    function delete(Request $request, Response $response, $args)
    {
        $body = $args['id'];
        $mesa = new Mesa();
        $respuesta = $mesa::deleteById($body);
        $respuesta = new Retorno(true,$respuesta,null);
        $response->getBody()->write(json_encode($respuesta));
        return $response;
    }
    function reservar(Request $request, Response $response, $args)
    {
        $mesa = new Mesa();
        //me fijo que mesa esta libre
        $mesaLibre = $mesa::getByKey('id_estado',10);//las mesas con estado 10 deberian tener un mozo asignado ya
        //validar que haya mesa libre

        if(!is_null($mesaLibre))//
        {
            $cliente = InterClass::retornarUsuarioPorToken();// te decodifica el tocken
            //pero yo quiero ver los datos de ahora
            $cliente = InterClass::retornarClienteById($cliente->id);
            if( $cliente->id_estado == Eestado::SIN_MESA)
            {
                $mozo = InterClass::retornarUsuarioById($mesaLibre->id_empleado);
                
                Mesa::updateById($mesaLibre->id_empleado,$cliente->id,Eestado::CLIENTE_MIRANDO_CARTA,$mesaLibre->numero,$mesaLibre->id);
                //me tengo que traer el nombre del mozo que la atiende
                ClienteHandler::darMesa($cliente->id);
                $standar = new stdClass();
                $standar->numeroDeMesa = $mesaLibre->numero;
                $standar->nombreMozo = $mozo->nombre;
                $standar->apellidoMozo = $mozo->apellido;
                //crear stdobjetc con el numero de la mesa y nombre del mozo
                $respuesta = new Retorno(true,$standar,null);
            }
            else
            {
                $respuesta = new Retorno(false,"Usted ya tiene una mesa asignada",null);
            }
        }else
        {
            $respuesta = new Retorno(false,'No hay mesas libres',null);
        }
        $response->getBody()->write(json_encode($respuesta));
        return $response;
    }
    function openAll(Request $request, Response $response, $args)
    {
        $id_mozo = InterClass::retornarMozoLibre();
        if($id_mozo != 0)
        {
            foreach (EMesas::MESAS as $numero) {
                Mesa::insert($id_mozo,null,Eestado::DISPONIBLE,$numero);
                $id_mozo = InterClass::retornarMozoLibre();
            }
            $respuesta = new Retorno(true,"Se han habilitado todas las mesas", null);
        }else{
            $respuesta = new Retorno(false,"No hay mozos trabajando en este momento.",null);
        }
        $response->getBody()->write(json_encode($respuesta));
        return $response;
    }
    function cerrar(Request $request, Response $response , $args)
    {
        if(isset($args['id']))
        {
            $mesa = Mesa::getById($args['id']);
            if(!is_null($mesa))
            {
                if($mesa->id_estado == Eestado::CON_CLIENTE_PAGANDO)
                {
                    StateHandler::cambiarEstadoMesa($mesa->id);
                    //terminar pedidos 
                    $respuesta = new Retorno(true,"La mesa se ha cerrado existosamente", null);
                }else
                {
                    $respuesta = new Retorno(false,"La mesa aun no esta disponible para cerrase.", null);
                }
            }else
            {
                $respuesta = new Retorno(false, "Mesa inexistente", null);
            }
        }else
        {
            $respuesta = new Retorno(false,"Por favor, ingrese el id de la mesa", null);
        }
        $response->getBody()->write(json_encode($respuesta));
        return $response;
    }
}