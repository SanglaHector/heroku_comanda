<?php
namespace Components;
use Enums\EtipoUsuario;
class TratarEnums{
    public function __construct()
    {
        
    }
    public static function returnSector($tipoEmpleado)
    {
        switch($tipoEmpleado){
            case EtipoUsuario::BARTENDER:
                $sector = 1;
                break;
            case EtipoUsuario::CERVECERO:
                $sector = 2;
                break;
            case EtipoUsuario::COCINERO:
                $sector = array(3,4);
                break;
            default:
                $sector = null;
        }
        return $sector;
    }
}