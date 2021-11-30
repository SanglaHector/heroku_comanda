<?php
namespace Components;

use Models\Ticket;
use Components\StateHandler;
use Components\InterClass;
use Enums\Eestado;

class TicketHandler
{
    static function cerrarTicket($cliente,$id_estado)
    {
        $mesa = InterClass::returnMesaByCliente($cliente->id);
        $precioTotal = 0;
        
        if(!is_null($mesa))
        {
            $ticket = InterClass::returnTicktByMesa($mesa->id);
            if(!is_null($ticket))
            {
                $pedidos = InterClass::returnPedidosByTicket($ticket->id);
                foreach ($pedidos as $pedido ) {
                    $producto = interClass::retornarProducto($pedido->id_producto);
                    $precioTotal = ($pedido->cantidad * $producto->precio) + $precioTotal;
                    if($pedido->id_estado != Eestado::SERVIDO &&
                       $pedido->id_estado != Eestado::CANCELADO)
                    {
                        StateHandler::forzarEstadoPedido($pedido->id,$id_estado);
                    }
                }
                StateHandler::cambiarEstadoMesa($mesa->id,Eestado::CON_CLIENTE_PAGANDO);
                Ticket::updateById(
                    $mesa->id,
                    Ticket::createPhotoName($mesa->id,$ticket->id),
                    $precioTotal,
                    $ticket->id
                );
                return $precioTotal;
            }else
            {
                return null;
            }
        }else
        {
            return null;
        }
    }
}
