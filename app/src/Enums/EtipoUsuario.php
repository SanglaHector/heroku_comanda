<?php
namespace Enums;

use MyCLabs\Enum\Enum;

class EtipoUsuario extends Enum
{
    const BARTENDER = 1;
    const CERVECERO = 2;
    const COCINERO = 3;
    const MOZO = 4;
    const SOCIO = 5;
    const CLIENTE = 6;

    public static function esEmpleado($numero)
    {
        switch ($numero) {
            case EtipoUsuario::BARTENDER:
                return true;
            case EtipoUsuario::CERVECERO:
                return true;
            case EtipoUsuario::COCINERO:
                return true;
            case EtipoUsuario::MOZO:
                return true;
            default:
                return false;
            }
    }
    public static function esTipo($numero)
    {
        switch ($numero) {
            case EtipoUsuario::BARTENDER:
                return true;
            case EtipoUsuario::CERVECERO:
                return true;
            case EtipoUsuario::COCINERO:
                return true;
            case EtipoUsuario::MOZO:
                return true;
            case EtipoUsuario::SOCIO:
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
            case EtipoUsuario::BARTENDER:
                return "BARTENDER";
            case EtipoUsuario::CERVECERO:
                return "CERVECERO";
            case EtipoUsuario::COCINERO:
                return "COCINERO";
            case EtipoUsuario::MOZO:
                return "MOZO";
            case EtipoUsuario::SOCIO:
                return "SOCIO";
            case EtipoUsuario::CLIENTE:
                return "CLIENTE";
            default:
                return 0;
        }
    }

    public static function getVal($string)
    {
        switch ($string) {
            case "BARTENDER":
                return EtipoUsuario::BARTENDER;
            case "CERVECERO":
                return EtipoUsuario::CERVECERO;
            case "COCINERO":
                return EtipoUsuario::COCINERO;
            case "MOZO":
                return EtipoUsuario::MOZO;
            case "SOCIO":
                return EtipoUsuario::SOCIO;
            case "CLIENTE":
                return EtipoUsuario::CLIENTE;
            default:
                return "";
        }
    }
}

/*
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
*/