<?php
namespace Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Usuario extends Model
{
    protected $table = 'usuarios';
    protected $primaryKey = 'id';
    //public $incrementing = false;
    //protected $keyType = 'string';
    //public $timestamps = false;
    //protected $dateFormat = 'U';
    //const CREATED_AT = 'creation_date';
    //const UPDATED_AT = 'last_update';
    use SoftDeletes;
    //const DELETED_AT = 'deleted_at';
    /*static function retornarUltimoId()
    {
        $id = 0;
        $collection = Usuario::orderBy('id','DESC')->limit(1)->get();
        foreach ($collection as $attribute ) {
            $id = $attribute->id;
        } 
       return $id;
    }*/
    static function insert($tipo_empleado,$id_sector,$nombre,$apellido,$email,$clave,$DNI)
    {
        $usuario = new Usuario();
        $usuario->tipo_empleado = $tipo_empleado;
        $usuario->id_sector = $id_sector;
        $usuario->nombre = $nombre;
        $usuario->apellido = $apellido;
        $usuario->email = $email;
        $usuario->clave = $clave;
        $usuario->DNI = $DNI;
        $retorno = $usuario->save();
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
    static function deleteById($id)
    {
        $usuario = new Usuario();
        $usuario = $usuario->find($id);
        $usuario->delete();
        return $usuario;
    }
    static function updateById($tipo_empleado,$id_sector,$nombre,$apellido,$email,$clave,$DNI,$id)
    {
        $usuario = new Usuario();
        $usuario = $usuario->find($id);
        if(!is_null($usuario))
        {
            $usuario->tipo_empleado = $tipo_empleado;
            $usuario->id_sector = $id_sector;
            $usuario->nombre = $nombre;
            $usuario->apellido = $apellido;
            $usuario->email = $email; 
            $usuario->clave = $clave; 
            $usuario->DNI = $DNI; 
            $usuario->save();
        }else
        {
            return 'Usuario inexistente';
        }
        return $usuario;
    }
}