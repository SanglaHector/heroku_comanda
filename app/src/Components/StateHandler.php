<?php
namespace Components;
use Models\Pedido;
use Models\Producto;
use Models\Mesa;
use Enums\Eestado;
use Enums\EtipoUsuario;
class StateHandler
{
    static function cambiarEstadoPedido($id_pedido,$tiempo = null)
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
                $estadoSiguiente = Eestado::getNext($pedido->id_estado);
                if($estadoSiguiente !=  false)
                {
                    switch($estadoSiguiente)
                    {
                        case Eestado::EN_PREPARACION:
                            if(is_null($tiempo) ||
                            $tiempo == "")
                            {  
                                return false;
                            }else
                            {
                                if(Validaciones::validarHora($tiempo))
                                {
                                    Pedido::updateById($pedido->id_ticket,
                                    $pedido->id_producto,
                                    $pedido->cantidad,
                                    $estadoSiguiente,
                                    $pedido->id,
                                    $tiempo,
                                    $pedido->hora_final);
                                    $retorno = true;        
                                }else{
                                    return false;
                                }
                            }
                            break;
                        case Eestado::LISTO_PARA_SERVIR:
                            //calcular tiempo final
                            $hora_final = Pedido::calcularDifHoras($pedido);
                            Pedido::updateById($pedido->id_ticket,
                                    $pedido->id_producto,
                                    $pedido->cantidad,
                                    $estadoSiguiente,
                                    $pedido->id,
                                    $pedido->hora_estimada,
                                    $hora_final);
                            $retorno = true;        
                            break;
                        case Eestado::SERVIDO:
                            if($usuario->tipo_empleado == 4 || 
                               $usuario->tipo_empleado == 5)
                               {
                                Pedido::updateById($pedido->id_ticket,
                                $pedido->id_producto,
                                $pedido->cantidad,
                                $estadoSiguiente,
                                $pedido->id,
                                $pedido->hora_estimada,
                                $pedido->hora_final);
                                $retorno = true;       
                               }
                               break;
                        default:
                            Pedido::updateById($pedido->id_ticket,
                            $pedido->id_producto,
                            $pedido->cantidad,
                            $estadoSiguiente,
                            $pedido->id,
                            $pedido->hora_estimada,
                            $pedido->hora_final);
                            $retorno = true;
                            break;
                    }
                }
              }
            }   
        }
        return $retorno;
    }
    static function cambiarEstadoMesa($id_mesa)
    {
        $retorno = false;
        $mesa = Mesa::getById($id_mesa);
        if(!is_null($mesa))
        {
            $estadoSiguiente = Eestado::getNext($mesa->id_estado);
            if($estadoSiguiente !=  false)
            {
                Mesa::updateById($mesa->id_empleado,
                $mesa->id_cliente,
                $estadoSiguiente,
                $mesa->numero,
                $mesa->id);
                $retorno = true;
            }
        
        }
        return $retorno;
    }
    static function forzarEstadoPedido($id_pedido)
    {
        $pedido = Pedido::getById($id_pedido);
        if(!is_null($pedido))
        {
            Pedido::updateById($pedido->id_ticket,
                            $pedido->id_producto,
                            $pedido->cantidad,
                            Eestado::SERVIDO,
                            $pedido->id,
                            $pedido->hora_estimada,
                            $pedido->hora_final);
        }
    }
    static function forzarEstadoMesa($id_mesa)
    {
        $mesa = Mesa::getById($id_mesa);
        if(!is_null($mesa))
        {
            Mesa::updateById($mesa->id_empleado,
            $mesa->id_cliente,
            Eestado::CERRADA,
            $mesa->numero,
            $mesa->id);
        }
    }
}