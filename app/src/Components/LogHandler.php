<?php
namespace Components;
use Models\Log;
use Enums\Eestado;
use Models\Cliente;
use Models\Usuario;

class LogHandler{
    static function desloguear()
    {
        $clientes = Cliente::getAllByKey('id_estado',Eestado::CON_MESA);
        $empleados = Usuario::getAllByKey('id_estado',Eestado::TRABAJANDO);
        foreach ($clientes as $cliente ) {
            Cliente::updateById($cliente->email,$cliente->clave,Eestado::SIN_MESA,$cliente->id);
        }
        foreach ($empleados as $empleado ) {
            Usuario::updateById($empleado->tipo_empleado,
                                $empleado->id_sector,
                                Eestado::FUERA,
                                $empleado->nombre,
                                $empleado->apellido,
                                $empleado->email,
                                $empleado->clave,
                                $empleado->DNI,
                                $empleado->id);
            $log = Log::getLastLog($empleado->id);
            if($log->in_out)
            {
                Log::insert($empleado->id,$empleado->id_sector,0);//hacer lo mismo para clientes
            }
        }
    }
}
