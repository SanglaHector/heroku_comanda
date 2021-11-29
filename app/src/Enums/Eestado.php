<?php

namespace Enums;

class Eestado 
{
    const PENDIENTE = 1;
    const EN_PREPARACION = 2;
    const LISTO_PARA_SERVIR = 3;
    const SERVIDO = 4;
    const CANCELADO = 5;
    const DISPONIBLE = 10;
    const CLIENTE_MIRANDO_CARTA = 11;
    const CLIENTE_ESPERANDO_PEDIDO = 12;
    const CON_CLIENTE_COMIENDO = 13;
    const CON_CLIENTE_PAGANDO= 14;
    const CERRADA = 15;
    const TRABAJANDO = 21;
    const FUERA = 22;
    const SUSPENDIDO = 23;
    const VACACIONES = 24;
    const SIN_MESA = 30;
    const CON_MESA = 31;


    public static function esPedido($numero)
    {
        switch ($numero) {
            case Eestado::PENDIENTE:
                return true;
            case Eestado::EN_PREPARACION:
                return true;
            case Eestado::LISTO_PARA_SERVIR:
                return true;
            case Eestado::SERVIDO:
                return true;
            case Eestado::CANCELADO:
                return false;
            default:
                return false;
        }
    }
    public static function esMesa($numero)
    {
        switch($numero)
        {
            case Eestado::DISPONIBLE;
                return true;
            case Eestado::CLIENTE_ESPERANDO_PEDIDO:
                return true;
            case Eestado::CON_CLIENTE_PAGANDO:
                return true;
            case Eestado::CERRADA:
                return true;
            case Eestado::CLIENTE_MIRANDO_CARTA:
                true;
            default:
                return false;
        }
    }
    public static function getNext($id_estado)
    {
        $estado = Eestado::GetDescription($id_estado);
        $estado = Eestado::getVal($estado);
        if($estado == 0)
        {
            return 0;
        }else
        {
            $estado = $estado + 1;
            if(Eestado::exist($estado))
            {
                return $estado;
            }else
            {
                return 0;
            }
        }

    }
    public static function GetDescription($numero)
    {
        switch ($numero) {
            case Eestado::PENDIENTE:
                return "PENDIENTE";
            case Eestado::EN_PREPARACION:
                return "EN PREPARACION";
            case Eestado::LISTO_PARA_SERVIR:
                return "LISTO PARA SERVIR";
            case Eestado::DISPONIBLE:
                return "DISPONIBLE";
            case Eestado::CLIENTE_ESPERANDO_PEDIDO:
                return "CLIENTE ESPERANDO PEDIDO";
            case Eestado::CON_CLIENTE_PAGANDO:
                return "CON CLIENTE PAGANDO";
            case Eestado::CERRADA:
                return "CERRADA";
            case Eestado::CLIENTE_MIRANDO_CARTA:
                return "CLIENTE MIRANDO CARTA";
            case Eestado::CON_CLIENTE_COMIENDO:
                return 'CON CLIENTE COMIENDO';
            case Eestado::TRABAJANDO:
                return "TRABAJANDO";
            case Eestado::FUERA:
                return "FUERA";
            case Eestado::SUSPENDIDO:
                return "SUSPENDIDO";
            case Eestado::VACACIONES:
                return "VACACIONES";
            case Eestado::CON_MESA;
                return "CON MESA";
            case Eestado::SIN_MESA:
                return "SIN MESA";
            case Eestado::SERVIDO:
                return "SERVIDO";
            case Eestado::CANCELADO:
                return "CANCELADO";
            default:
                return "";
        }
    }

    public static function getVal($string)
    {
        switch ($string) {
            case "PENDIENTE":
                return Eestado::PENDIENTE;
            case "EN PREPARACION":
                return Eestado::EN_PREPARACION;
            case "LISTO PARA SERVIR":
                return Eestado::LISTO_PARA_SERVIR;
            case "DISPONIBLE":
                return Eestado::DISPONIBLE;
            case "CLIENTE ESPERANDO PEDIDO":
                return Eestado::CLIENTE_ESPERANDO_PEDIDO;
            case "CON CLIENTE PAGANDO":
                return Eestado::CON_CLIENTE_PAGANDO;
            case "CON CLIENTE COMIENDO":
                return Eestado::CON_CLIENTE_COMIENDO;
            case "CERRADA":
                return Eestado::CERRADA;
            case "CLIENTE MIRANDO CARTA":
                return Eestado::CLIENTE_MIRANDO_CARTA;
            case "TRABAJANDO":
                return Eestado::TRABAJANDO;
            case "FUERA":
                return Eestado::FUERA;
            case "SUSPENDIDO":
                return Eestado::SUSPENDIDO;
            case "VACACIONES":
                return Eestado::VACACIONES;
            case "CON MESA":
                return Eestado::CON_MESA;
            case "SIN MESA":
                return Eestado::SIN_MESA;
            case "CANCELADO":
                return Eestado::CANCELADO;
            default:
                return 0;
        }
    }
    private static function exist($id_estado)
    {
        $retorno = false;
        switch($id_estado)
        {
            case 1:
            case 2:
            case 3:
            case 4:
            case 5:
            case 10:
            case 11:
            case 12:
            case 13:
            case 14:
            case 15:
            case 21:
            case 22:
            case 23:
            case 24:
            case 30:
            case 31:
                $retorno = true;
                break;
            default:
                $retorno = false;
                break;
        }
        return $retorno;
    }
}
