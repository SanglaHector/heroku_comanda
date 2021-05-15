<?php

namespace Enums;

use MyCLabs\Enum\Enum;

class Eestado extends Enum
{
    const PENDIENTE = 1;
    const EN_PREPARACION = 2;
    const LISTO_PARA_SERVIR = 3;
    const CLIENTE_ESPERANDO_PEDIDO = 11;
    const CON_CLIENTE_PAGANDO= 12;
    const CERRADA = 13;

    public static function esPedido($numero)
    {
        switch ($numero) {
            case Eestado::PENDIENTE:
                return true;
            case Eestado::EN_PREPARACION:
                return true;
            case Eestado::LISTO_PARA_SERVIR:
                return true;
            default:
                return false;
        }
    }
    public static function esMesa($numero)
    {
        switch($numero)
        {
            case Eestado::CLIENTE_ESPERANDO_PEDIDO:
                return true;
            case Eestado::CON_CLIENTE_PAGANDO:
                return true;
            case Eestado::CERRADA:
                return true;
            default:
                return false;
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
            case Eestado::CLIENTE_ESPERANDO_PEDIDO:
                return "CLIENTE ESPERANDO PEDIDO";
            case Eestado::CON_CLIENTE_PAGANDO:
                return "CON CLIENTE PAGANDO";
            case Eestado::CERRADA:
                return "CERRADA";
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
            case "CLIENTE ESPERANDO PEDIDO":
                return Eestado::CLIENTE_ESPERANDO_PEDIDO;
            case "CON CLIENTE PAGANDO":
                return Eestado::CON_CLIENTE_PAGANDO;
            case "CERRADA":
                return Eestado::CERRADA;
            default:
                return "";
        }
    }
}
