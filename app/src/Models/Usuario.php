<?php
namespace Models;

use Enums\Eestado;
use Components\Token;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Usuario extends Model
{
    protected $table = 'usuarios';
    protected $primaryKey = 'id';
    use SoftDeletes;
    static function insert($tipo_empleado,$id_sector,$id_estado,$nombre,$apellido,$email,$clave,$DNI)
    {
        $usuario = new Usuario();
        $usuario->tipo_empleado = $tipo_empleado;
        $usuario->id_sector = $id_sector;
        $usuario->id_estado = $id_estado;
        $usuario->nombre = $nombre;
        $usuario->apellido = $apellido;
        $usuario->email = $email;
        $usuario->clave = crypt($clave,'SHA-256');
        $usuario->DNI = $DNI;
        $retorno = $usuario->save();
        Operacion::insert('usuario',Operacion::ALTA,$usuario);//prueba
        return $retorno;
    }
    static function get()
    {
        $collection = Usuario::orderBy('id','DESC')->get();
        return $collection;
    }
    static function getById($id)
    {
        $usuario = new Usuario();
        $usuario = $usuario->find($id);
        return $usuario;
    }
    static function getByKey($key,$value)
    {
        $retorno = null;
        $usuarios = Usuario::where($key,$value)
                            ->limit(1)
                            ->get();
        foreach ($usuarios as $usuario) {
            $retorno = $usuario;
        }
        return $retorno;
    }
    static function getAllByKey($key,$value)
    {
        $retorno = array();
        $models = Usuario::where($key,$value)
                            ->get();
        foreach ($models as $model) {
            array_push($retorno,$model);
        }
        return $retorno;
    }
    static function deleteById($id)
    {
        $usuario = new Usuario();
        $usuario = $usuario->find($id);
        Operacion::insert('usuario',Operacion::BAJA,$usuario);//prueba
        $usuario->delete();
        return $usuario;
    }
    static function updateById($tipo_empleado,$id_sector,$id_estado,$nombre,$apellido,$email,$clave,$DNI,$id)
    {
        $usuario = new Usuario();
        $usuario = $usuario->find($id);
        Operacion::insert('usuario',Operacion::MODIFICACION_ANTES,$usuario);
        if(!is_null($usuario))
        {
            $usuario->tipo_empleado = $tipo_empleado;
            $usuario->id_sector = $id_sector;
            $usuario->id_estado = $id_estado;
            $usuario->nombre = $nombre;
            $usuario->apellido = $apellido;
            $usuario->email = $email; 
            $usuario->clave = $clave;
            $usuario->DNI = $DNI; 
            $usuario->save();
            Operacion::insert('usuario',Operacion::MODIFICACION_DSP,$usuario);
        }else
        {
            return 'Usuario inexistente';
        }
        return $usuario;
    }
    static function getFreeEmployee($tipo_empleado,$id_sector = null)
    {
        if($id_sector == null)
        {
            $usuarios = Usuario::where('tipo_empleado',$tipo_empleado)
                                ->where('id_estado',21)
                                ->limit(1)
                                ->get();
            foreach ($usuarios as $usuario) {
                $retorno = $usuario;
            }
            return $retorno;
        }
    }
    static function chagenState($model,$state)
    {
        $model = Usuario::find($model->id);
        $model->id_estado = $state;
        $model->save();
    }
    static function getWorking($tipo_empleado = null)
    {
        if($tipo_empleado == null)
        {
            $usuarios = Usuario::where('id_estado',Eestado::TRABAJANDO)
            ->orderBy('id')
            ->get();
        }else
        {
            $usuarios = Usuario::where('id_estado',Eestado::TRABAJANDO)
            ->where('tipo_empleado',$tipo_empleado)
            ->orderBy('id')
            ->get();
        }
        return $usuarios;
    }
    
    static function returnUsuarioByToken($token)
    {
        $header = Token::getHeader('token');
        $stdClass = Token::autenticarToken($header);
        return Usuario::returnByKeyDB($stdClass->id,'id',0);
    }
    static function convertToModelCSV($array)
    {
        $model = new Usuario();
        $model->id = $array['0'];
        $model->tipo_empleado = $array['1'];
        $model->id_sector = $array['2'];
        $model->id_estado = $array['3'];
        $model->nombre = $array['4'];
        $model->apellido = $array['5'];
        $model->email = $array['6'];
        $model->clave = $array['7'];
        $model->DNI = trim($array['8'], ';');
        return $model;
    }
    static function exist($id)
    {
        $model = Usuario::find($id);
        if(is_null($model))
        {
            return false;
        }else
        {
            return true;
        }
    }
    static function toCSV($model)
    {
        $array = array(
            $model->__get('id'),
            $model->__get('tipo_empleado'),
            $model->__get('id_sector'),
            $model->__get('id_estado'),
            $model->__get('nombre'),
            $model->__get('apellido'),
            $model->__get('email'),
            $model->__get('DNI')
        );
        $string = implode(',',$array);
        $string = $string.';'.PHP_EOL;
        return $string;
    }
}