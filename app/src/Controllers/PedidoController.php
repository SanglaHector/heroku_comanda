<?php
namespace Controllers;

use Interfaces\IDatabase;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Models\Pedido;
use Components\Retorno;
use Components\InterClass;
use Components\StateHandler;
use Components\PedidoHandler;
use Enums\Eestado;
use Exception;

class PedidoController implements IDatabase
{

    function addOne(Request $request, Response $response, $args)
    {
        $body = $request->getParsedBody();
        if( isset($body['id_producto']) &&
            isset($body['cantidad']))
            {
                $ticket = InterClass::retornarTicket();//nuevo o actual
                if(is_null($ticket))
                {
                    $respuesta = new Retorno(false,"Debe reservar una mesa antes de hacer el pedido", null);
                }else
                {
                    //ver stock
                    $producto = InterClass::retornarProducto($body['id_producto']);
                    if(is_null($producto))
                    {
                        $respuesta = new Retorno(false,"Producto inexistente", null);
                    }else
                    {
                        if($producto->stock >= $body['cantidad'])
                        {
                            //alta pedido 
                            //update stock producto / update precio ticket / update stado mesa
                            $pedido = PedidoHandler::altaPedido($body['cantidad'],$producto,$ticket);
                            //$ticket->precio_total = ($producto->precio * $body['cantidad']) + $ticket->precio_total;
                            $respuesta = new Retorno(true,$pedido,null);
                        }else
                        {
                            $respuesta = new Retorno(false,"No hay stock disponible para este producto",null);
                        }
                    }
                }
            }else
            {
                $respuesta = new Retorno(false,"Faltan cargar datos",null);
            }
            $response->getBody()->write(json_encode($respuesta));
            return $response;
    }

    function addSeveral(Request $request, Response $response, $args)
    {
        $body = $request->getParsedBody();
        if( isset($body['data']) )
            {
                $pedidos = json_decode($body['data']);
                $ticket = InterClass::retornarTicket();//nuevo o actual
                if(is_null($ticket))
                {
                    $respuesta = new Retorno(false,"Debe reservar una mesa antes de hacer el pedido", null);
                }else
                {
                    foreach ($pedidos as $pedido ) {
                        if(isset($pedido->id_producto) && isset($pedido->cantidad))
                        {
                            $producto = InterClass::retornarProducto($pedido->id_producto);
                            if(is_null($producto))
                            {
                                $respuesta = new Retorno(false,"Producto inexistente", null);
                            }else
                            {
                                if($producto->stock >= $pedido->cantidad)
                                {
                                    $pedido = PedidoHandler::altaPedido($pedido->cantidad,$producto,$ticket);
                                    //$ticket->precio_total = ($producto->precio * $body['cantidad']) + $ticket->precio_total;
                                }else
                                {
                                    $respuesta = new Retorno(false,"No hay stock disponible para este producto",null);
                                }
                            }
                        }else
                        {
                            $respuesta = new Retorno(false,'Los datos son erroneos',null);
                        }
                    }
                    $respuesta = new Retorno(true,"Pedidos dados de alta con existo, tiket: ".$ticket->id,null);
                }
            }else
            {
                $respuesta = new Retorno(false,"Faltan cargar datos",null);
            }
            $response->getBody()->write(json_encode($respuesta));
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
        try{
            $pedidos = PedidoHandler::filtrarPedidos();
            $respuesta = new Retorno(true,$pedidos,null);
        }catch(Exception $e)
        {
            $respuesta = new Retorno(false,"Ha ocurrido un error inesperado",$e->getMessage());
        }
        $response->getBody()->write(json_encode($respuesta));
        return $response;
    }
    function getReady(Request $request, Response $response, $args)
    {
        $estado = Eestado::LISTO_PARA_SERVIR;
        $pedidos = PedidoHandler::filtrarPedidos($estado);
        $respuesta = new Retorno(true,$pedidos,null);
        $response->getBody()->write(json_encode($respuesta));
        return $response;
    }
    function update(Request $request, Response $response, $args)
    {
        $body = $request->getParsedBody();
        if( isset($body['id_ticket']) &&
            isset($body['id_producto']) &&
            isset($body['cantidad']) &&
            isset($body['id_estado']) &&
            isset($args['id']) &&
            isset($body['hora_estimada']))
            {
                $pedido = new Pedido();
                $respuesta = $pedido::updateById($body['id_ticket'],$body['id_producto'],$body['cantidad'],
                $body['id_estado'],$args['id'],$body['hora_estimada'], null);
                $respuesta = new Retorno(true,$respuesta,null);
                $response->getBody()->write(json_encode($respuesta));
            }else
            {
                $respuesta = "Faltan cargar datos";
                $respuesta = new Retorno(false,$respuesta,null);
                $response->getBody()->write(json_encode($respuesta));
            }
            return $response;
    }
    function delete(Request $request, Response $response, $args)
    {
        $body = $args['id'];
        $pedido = new Pedido();
        $respuesta = $pedido::deleteById($body);
        $respuesta = new Retorno(true,$respuesta,null);
        $response->getBody()->write(json_encode($respuesta));
        return $response;
    }
    function estadoSiguiente(Request $request, Response $response, $args)
    {
        $body = $request->getParsedBody();
        $tiempo = "";
        if(isset($body['tiempo']))
        {
            $tiempo = $body['tiempo'];
        }
        if(isset($args['id']))
        {
            $id_pedido = $args['id'];
            if(StateHandler::cambiarEstadoPedido($id_pedido,$tiempo))
            {
                $retorno = new Retorno(true,"Se ha tomado el pedido correctamente",null);
            }else
            {
                $retorno = new Retorno(false,"Error al tomar pedido",null);
            }
        }else
        {
            $retorno = new Retorno(false,"Por favor ingrese un pedido", null);
        }
        $response->getBody()->write(json_encode($retorno));
        return $response;
    }
    function getTime(Request $request,Response $response,$args)
    {
        $tiempoMayor = '00:00:00';
        if(isset($args['id']))
        {
            $id_ticket = $args['id'];
            $pedidos = InterClass::returnPedidosByTicket($id_ticket);
            $tiempos = array();
            foreach ($pedidos as $pedido ) {
                if($pedido->id_estado == Eestado::EN_PREPARACION)
                {
                    $tiempoPedido = Pedido::calcularDifHoras($pedido);
                }else{
                    $producto = InterClass::retornarProducto($pedido->id_producto);
                    if(!is_null($producto))
                    {
                        $tiempoPedido = $producto->tiempo_preparacion;
                    }else
                    {
                        $tiempoPedido = '00:00:00';
                    }
                }
                array_push($tiempos,$tiempoPedido);
            }
            foreach ($tiempos as $tiempo ) {
                if($tiempo > $tiempoMayor)
                {
                    $tiempoMayor = $tiempo;
                }
            }
        } 
        $response->getBody()->write(json_encode($tiempoMayor));
        return $response;
    }
    function servir(Request $request,Response $response,$args)
    {
        if(isset($args['id']))
        {
            $id_pedido = $args['id'];
            $estado = Eestado::LISTO_PARA_SERVIR;
            $pedidos = PedidoHandler::filtrarPedidos($estado);
            $ok = false;
            foreach ($pedidos as $pedido ) {
                if($pedido->id == $id_pedido)
                {
                    $ok = true;
                }
            }
            if( $ok && StateHandler::cambiarEstadoPedido($id_pedido) )
            {
                $mesa = InterClass::returnMesaByPedido($id_pedido);
                if(!is_null($mesa) && StateHandler::cambiarEstadoMesa($mesa->id))
                {
                    $retorno = new Retorno(true,"Se ha avanzado con el pedido correctamente",null);
                }else
                {
                    $retorno = new Retorno(false,"Error al cambiar estado Mesa", null);
                }
            }else
            {
                $retorno = new Retorno(false,"Pedido no esta listo para servir",null);
            }
            
        }else
        {
            $retorno = new Retorno(false,"Por favor ingrese un pedido", null);
        }
        $response->getBody()->write(json_encode($retorno));
        return $response;
    }

    function pay (Request $request, Response $response, $args)
    {
        if(isset($args['id']))
        {
            $pedido = Pedido::getById($args['id']);
            if(!is_null($pedido))
            {
                if($pedido->id_estado == Eestado::SERVIDO)
                {
                    $estadoSiguiente = Eestado::getNext($pedido->id_estado);
                    if($estadoSiguiente !=  false)
                    {
                        $update = Pedido::updateById($pedido->id_ticket,
                        $pedido->id_producto,
                        $pedido->cantidad,
                        $estadoSiguiente,
                        $pedido->id,
                        $pedido->hora_estimada,
                        $pedido->hora_final);
                        $retorno = new Retorno(true,"El pedido se ha pagado correctamente",null);            
                        
                    }else
                    {
                        $retorno = new Retorno(false,"Pedido seleccionado incorrecto",null);            
                    }
                }else
                {
                    $retorno = new Retorno(false,"Pedido seleccionado incorrecto",null);            
                }
            }
            else
            {
                $retorno = new Retorno(false,"Pedido seleccionado incorrecto",null);            
            }
        }else
        {
            $retorno = new Retorno(false,"Por favor ingrese un id",null);
        }
        $response->getBody()->write(json_encode($retorno));
        return $response;;
    }
}