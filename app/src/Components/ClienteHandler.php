<?php
namespace Components;
use Models\Cliente;
use Enums\Eestado;

class ClienteHandler{
    static function darMesa($id_cliente)
    {
        $cliente = Cliente::getById($id_cliente); 
        $cliente = Cliente::updateById($cliente->email,$cliente->clave,Eestado::CON_MESA,$cliente->id);
    }
}
