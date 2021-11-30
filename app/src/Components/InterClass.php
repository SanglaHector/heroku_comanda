<?php
namespace Components;

use Enums\Eestado;
use Models\Usuario;
use Models\Cliente;
use Models\Mesa;
use Enums\EtipoUsuario;
use Models\Producto;
use Models\Ticket;
use Models\Pedido;
use Models\Sector;

class InterClass
{
    //mozos
    static function retornarMozoLibre()
    {
        $id_mozo = InterClass::mozoSinMesa();
        if($id_mozo == 0 )
        {
            $id_mozo = InterClass::mozoConMenosTrabajo();
        }
        return $id_mozo;
    }
    static public function mozoConMenosTrabajo()//probar
    {
        $mesas = Mesa::getNonFree();//mesas donde actualmente hay un mozo trabajando
        $min = 1000;
        $auxId = 0; 
        foreach ($mesas as $mesa ) {
            $cantidad = Mesa::getCount($mesa->id_empleado);
            if($cantidad < $min)
            {
                $min = $cantidad;
                $auxId = $mesa->id_empleado;
            }   
        }
        return $auxId;
    }
    static public function mozoSinMesa()
    {
        $mozos = Usuario::getWorking(EtipoUsuario::MOZO);
        $idLibre = 0;
        foreach ($mozos as $mozo ) {
            if(!Mesa::hasMesa($mozo->id))
            {
                $idLibre = $mozo->id;
            }
        }
        return $idLibre;
    }
    static function darMesa($id_cliente)
    {
        $cliente = Cliente::getById($id_cliente); 
        $cliente = Cliente::updateById($cliente->email,$cliente->clave,Eestado::CON_MESA,$cliente->id);
    }
    //usuarios
    static function retornarUsuarioPorToken()
    {
        $retorno = null;
      //  $header = Token::getHeader('token');//aca me traigo el header que ingresa el cliente por peticion
        $header = getenv("TOKEN");//prueba
        $id = Token::autenticarToken($header);
        $tipo = Token::returnTipoToken($header);
        if($tipo == EtipoUsuario::CLIENTE)
        {
            $retorno = InterClass::retornarClienteById($id);
        }else
        {
            $retorno = InterClass::retornarUsuarioById($id);
        }
        return $retorno;
    }
    static function retornarUsuarioById($id)
    {
        return Usuario::getById($id);
    }
    //productos
    static function retornarProducto($id_producto)
    {
        $producto = Producto::getById($id_producto);
        if(!isset($producto->id))
        {
            $producto = null;
        }
        return $producto;
    }
    //mesas
    static public function returnMesasByMozo($id_mozo)
    {
        $mesas = Mesa::getNonFree();
        $retorno = array();
        foreach ($mesas as $mesa ) {
            if($mesa->id_empleado == $id_mozo)
            {
                array_push($retorno,$mesa);
            }
        }
        return $retorno;
    }
    static function returnMesaByPedido($id_pedido)
    {
        $retorno = null;
        $pedido = Pedido::getById($id_pedido);
        if(!is_null($pedido))
        {
            $ticket = Ticket::getById($pedido->id_ticket);
            if(!is_null($ticket))
            {
                return Mesa::getById($ticket->id_mesa);
            }
        }
        return $retorno;
    }
    static function returnMesaByTicket($ticket)
    {
        return Mesa::getById($ticket->id_mesa);
    }
    static function returnMesasByUsuario($id_usuario)
    {
        return Mesa::getAllByKey('id_usuario',$id_usuario);
    }
    static function returnMesaByCliente($id_cliente)
    {
        return Mesa::getActiveByKey('id_cliente',$id_cliente);
    }
    //pedidos
    static function returnPedidosByTicket($id_ticket)
    {
        return Pedido::getAllByKey('id_ticket',$id_ticket);   
    }
    static function returnPedidosBySector($id_sector,$pedidos)
    {
        $retorno = array();   
        foreach ($pedidos as $pedido ) {
            $producto = Producto::getById($pedido->id_producto);
            if($producto->id_sector == $id_sector)
            {
                array_push($retorno,$pedido);
            }
        }
        return $retorno;
    }
    //clientes
    static function retornarClienteById($id_cliente)
    {
        return Cliente::getById($id_cliente);
    }
    static function retornarClienteByTicket($id_ticket)
    {
        $ticket = Ticket::getById($id_ticket);
        $mesa = Mesa::getById($ticket->id_mesa);
        $cliente = Cliente::getById($mesa->id_cliente);
        return $cliente;
    }
    //tickets
    static function returnTicktByMesa($id_mesa)
    {
        return Ticket::getByKey('id_mesa',$id_mesa);
    }
    static function retornarTicket()
    {
        $cliente = InterClass::retornarUsuarioPorToken();
        $mesa = Mesa::retornarMesaCliente($cliente->id);
        if(!is_null($mesa))//si trajo una mesa 
        {
            //busco el ticket
            $ticket = Ticket::getByKey('id_mesa',$mesa->id);//no le pongo estado al ticket porque la mes ya lo tiene.
            if(!isset($ticket->id))//Si noexiste ticket activo para la mesa creo uno.
            {
                $ticket = Ticket::insert($mesa->id,null,0);
                $ticket = Ticket::getLast();
            }
        }else//si no trae mesa hay un error grave, ya que antes de pedir deberia reservar mesa
        {
            $ticket = null;
        }
        return $ticket;
    }
    static function retornarSector($id_sector)
    {
        return Sector::getById($id_sector);
    }
}
