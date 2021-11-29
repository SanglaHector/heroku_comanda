<?php
namespace Enums;

class EtipoUsuario 
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
    public static function validarSector($usuario,$producto)
    {
        $retorno = false;
        switch($producto->id_sector)
        {
            case 1: //barra de tragos
                if($usuario->tipo_empleado == EtipoUsuario::BARTENDER)
                {
                    $retorno = true;
                }
                break;
            case 2: // Cerveceria
                if($usuario->tipo_empleado == EtipoUsuario::CERVECERO)
                {
                    $retorno = true;
                }
                break;
            case 3://Cocina
                if($usuario->tipo_empleado == EtipoUsuario::COCINERO)
                {
                    $retorno = true;
                }
                break;
            case 4://Candy Bar
                if($usuario->tipo_empleado == EtipoUsuario::COCINERO)
                {
                    $retorno = true;
                }
                break;
            default:
                break;
        }
        if($usuario->tipo_empleado == EtipoUsuario::MOZO ||
            $usuario->tipo_empleado == EtipoUsuario::SOCIO)
        {
            $retorno = true;
        }
        return $retorno;
    }
}