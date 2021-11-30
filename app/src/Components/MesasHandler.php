<?php
namespace Components;
use Models\Mesa;
use Enums\Eestado;

class MesasHandler{
    static function cerrarMesas()
    {
        $mesas = Mesa::getNonFree();
        $tickets = array();
        foreach ($mesas as $mesa ) {
            $ticket = InterClass::returnTicktByMesa($mesa->id);
            //mesa sin ticket - es decir, se abrio y nunca se usó.
            Mesa::updateById($mesa->id_empleado,
                             $mesa->id_cliente,
                             Eestado::CERRADA,
                             $mesa->numero,
                             $mesa->id);
            if(!is_null($ticket))
            {
                array_push($tickets,$ticket);
            }
        }
        foreach ($tickets as $ticket ) {
            if(is_null($ticket->precio_total) ||
                $ticket->precio_total == 0)
                {
                    $cliente = InterClass::retornarClienteByTicket($ticket->id);
                    TicketHandler::cerrarTicket($cliente,Eestado::SERVIDO);
                }
        }
        
    }
}
