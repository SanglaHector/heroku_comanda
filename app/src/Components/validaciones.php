<?php
namespace Components;
use Models\Sector;
use Models\TipoUsuario;
use Models\Usuario;
class Validaciones {
    static function validarString($array)
    {
        if(is_array($array))
        {
            foreach ($array as $variable ){
                if(!is_string($variable))
                {
                    return false;
                }
            }
           return Validaciones::convertirUpperCase($array);
        }else
        {
            return false;
        }
    }
    static function convertirUpperCase($array)
    {
        if(is_array($array))
        {
            $retorno = array();
            $elemento = "";
            foreach ($array as $variable ) {
                $elemento = strtoupper($variable);
                array_push($retorno,$elemento);
            }
            return $retorno;
        }else
        {
            return false;
        }
    }
    static function validarNumerico($array)
    {
        if(is_array($array))
        {
            $retorno = array();
            $elemento = "";
            foreach ($array as $variable ) {
                if(is_numeric($variable))
                {
                    $elemento = $variable;
                    array_push($retorno,$elemento);
                }else{
                    return false;
                }
            }
            return $retorno;
        }
    }
    static function validarSector($sector)
    {
        $sectores = Sector::get();
        if(is_string($sector))
        {
            $sectores = Sector::where('nombre',strtoupper($sector))
            ->first();
            if(!is_null($sectores))
            {
                return $sectores->id;
            }
        }
        if(is_numeric($sector))
        {
            $sectores = Sector::where('id',$sector)
            ->first();
            if(!is_null($sectores))
            {
                return $sectores->id;
            }
        }
        return false;
    }
    static function validarNuevoUsuario($email)
    {
        //$tipos = TipoUsuario::all();
        $usuarios = Usuario::where('email',$email)->get();
        foreach ($usuarios as $usuario ) {
            if($usuario->email == $email)
            {
                return false;
            }
        }
        return true;
    }
    static function validarHora($date, $format = 'H:i:s')
    {
        $array = explode(":",$date);
        if(isset($array[0]) &&
           isset($array[1]) &&
           isset($array[2]))
           {
               if(Validaciones::validarNumerico($array))
               {
                   return true;
               }else
               {
                   return false;
               }
           }else
           {
               return false;
           }
    }
    static function formatearHora($strDate)
    {
        $array = explode(":",$strDate);
        $array[0] = intval($array[0]);
        $array[0] = intval($array[1]);
        $array[0] = intval($array[2]);
        return implode(':',$array);
    }
    static function validarFecha($fecha,$format = 'Y-m-d')
    {
        $arr = explode('-',$fecha);
        if(isset($arr[0])
         && isset($arr[1])
         && isset($arr[2]))
        {
            if(Validaciones::validarNumerico($arr))
            {
                return true;
            }
        }
        return false;
    }
}