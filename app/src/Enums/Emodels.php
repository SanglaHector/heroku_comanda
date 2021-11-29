<?php

namespace Enums;

class Emodels 
{
    const EMPLEADO = 1;
    const CLIENTE = 2;
    const MESA = 3;
    const PEDIDO = 4;
    const PRODUCTO= 5;
    const ENCUESTA = 6;
    const ESTADO = 7;
    const LOG = 8;
    const SECTOR = 9;
    const TICKET = 10;
    const OPERACION = 11;

    public static function GetDescription($numero)
    {
        switch ($numero) {
            case Emodels::EMPLEADO:
                return "EMPLEADO";
            case Emodels::CLIENTE:
                return "CLIENTE";
            case Emodels::MESA:
                return "MESA";
            case Emodels::PEDIDO:
                return "PEDIDO";
            case Emodels::PRODUCTO:
                return "PRODUCTO";
            case Emodels::ENCUESTA:
                return "ENCUESTA";
            case Emodels::ESTADO;
                return "ESTADO";
            case Emodels::LOG;
                return "LOG";
            case Emodels::SECTOR:
                return "SECTOR";
            case Emodels::TICKET:
                return "TICKET";
            case Emodels::OPERACION:
                return "OPERACION";
        }
    }

    public static function getVal($string)
    {
        switch ($string) {
            case 'EMPLEADO':
                return Emodels::EMPLEADO;
            case 'CLIENTE':
                return Emodels::CLIENTE;
            case 'MESA':
                return Emodels::MESA;
            case 'PEDIDO':
                return Emodels::PEDIDO;
            case 'PRODUCTO':
                return Emodels::PRODUCTO;
            case 'ENCUESTA':
                return Emodels::ENCUESTA;
            case 'ESTADO';
                return Emodels::ESTADO;
            case 'LOG';
                return Emodels::LOG;
            case 'SECTOR':
                return Emodels::SECTOR;
            case 'TICKET':
                return Emodels::TICKET;
            case 'OPERACION':
                return Emodels::OPERACION;
            default:
                return "";
        }
    }
}