<?php

namespace Enums;

use MyCLabs\Enum\Enum;

class EtipoUsuario extends Enum
{
    const SOCIO = 1;
    const EMPLEADO = 2;
    const CLIENTE = 3;

    public static function esTipo($numero)
    {
        switch ($numero) {
            case EtipoUsuario::SOCIO:
                return true;
            case EtipoUsuario::EMPLEADO:
                return true;
            case EtipoUsuario::CLIENTE:
                return true;
            default:
                return false;
        }
    }
    public static function GetDescription($numero)
    {
        switch ($numero) {
            case EtipoUsuario::SOCIO:
                return "SOCIO";
            case EtipoUsuario::EMPLEADO:
                return "EMPLEADO";
            case EtipoUsuario::CLIENTE:
                return "CLIENTE";
        }
    }

    public static function getVal($string)
    {
        switch ($string) {
            case "SOCIO":
                return EtipoUsuario::SOCIO;
            case "EMPLEADO":
                return EtipoUsuario::EMPLEADO;
            case "CLIENTE":
                return EtipoUsuario::CLIENTE;
            default:
                return "";
        }
    }
}
