<?php
namespace Components;

use Models\Pedido;
use Models\Ticket;
use Models\Mesa;
use Models\Producto;
use Components\StateHandler;
use Components\InterClass;
use Enums\Eestado;
use Enums\EtipoUsuario;

class PedidoHandler{

    static function altaPedido($cantidad,$producto,$ticket)
    {
        $nuevoStock = $producto->stock - $cantidad;
        $nuevoPrecio = $ticket->precio_total + ($cantidad * $producto->precio);
        $mesa = Mesa::getByKey('id',$ticket->id_mesa);

        Producto::updateById(
            $producto->id_sector,
            $producto->nombre,
            $nuevoStock,
            $producto->precio,
            $producto->tiempo_preparacion,
            $producto->id);

        Ticket::updateById(
            $ticket->id_mesa,
            null,
            $nuevoPrecio,
            $ticket->id);
        
        Mesa::updateById(
            $mesa->id_empleado,
            $mesa->id_cliente,
            Eestado::CLIENTE_ESPERANDO_PEDIDO,
            $mesa->numero,
            $mesa->id);

        $pedido = Pedido::insert(
            $ticket->id,
            $producto->id,
            $cantidad,
            Eestado::PENDIENTE,
            $producto->tiempo_preparacion);
        return $pedido;                     
    }
    
    static function filtrarPedidos($estado = Eestado::PENDIENTE)
    {
        $pedidos = Pedido::get();
        $usuario = InterClass::retornarUsuarioPorToken();
        $respuesta = array();
        if(isset($usuario->tipo_empleado))//usuario
        {
            if(!is_null($usuario->id_sector))//bartender, cocinero, cervecero
            {
                $pedidos = InterClass::returnPedidosBySector($usuario->id_sector,$pedidos);  
                foreach ($pedidos as $pedido ) {
                    array_push($respuesta,$pedido);
                }
            }else//mozo 
            {
                if($usuario->tipo_empleado == EtipoUsuario::MOZO)
                {
                    //traer pendientes
                    //traer mesas de mozo
                    $mesas = InterClass::returnMesasByMozo($usuario->id);
                    //traer tickets de mesas
                    $tickets = array();    
                    foreach ($mesas as $mesa ) {//hay un ticket por mesa
                        $ticket = InterClass::returnTicktByMesa($mesa->id);
                        if(!is_null($ticket))
                        {
                            array_push($tickets,$ticket);
                        }
                    }
                    //traer pedidos pendientes de tickets
                    foreach ($pedidos as $pedido ) {
                        foreach ($tickets as $ticket) {//dentro hay algunos nulos
                            if($pedido->id_ticket == $ticket->id && $pedido->id_estado == $estado)
                            array_push($respuesta,$pedido);
                        }
                    }
                }else//socio
                {
                    foreach ($pedidos as $pedido) {
                        if($pedido->id_estado == $estado)
                        {
                            array_push($respuesta,$pedido);
                        }
                    }
                    //traer todos activos
                }
            }
        }else{//cliente
            $ticket = InterClass::retornarTicket();
            foreach ($pedidos as $pedido ){
                if($pedido->id_ticket == $ticket->id)
                {
                    array_push($respuesta,$pedido);
                }
            }
        }
        return $respuesta;   
    }
    static function armarPedido($id_pedido)
    {
        $retorno = false;
        $pedido = Pedido::getById($id_pedido);
        if(!is_null($pedido))
        {
            $usuario = InterClass::retornarUsuarioPorToken();
            $producto = Producto::getById($pedido->id_producto);
            if(!is_null($producto) &&
            !is_null($usuario))
            {
              if(EtipoUsuario::validarSector($usuario,$producto))
              {
                $retorno = StateHandler::cambiarEstadoPedido($pedido);
              }
            }   
        }
        return $retorno;
    }
    static function cancelarPedido($id_pedido)
    {
        
    }
}