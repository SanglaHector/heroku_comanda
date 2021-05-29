<?php
namespace Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Mesa extends Model
{
    protected $table = 'mesas';
    protected $primaryKey = 'id';
    //public $incrementing = false;
    //protected $keyType = 'string';
    //public $timestamps = false;
    //protected $dateFormat = 'U';
    //const CREATED_AT = 'creation_date';
    //const UPDATED_AT = 'last_update';
    //const DELETED_AT = 'deleted_at';
    use SoftDeletes;
    static function insert($id_empleado,$id_cliente,$id_estado)
    {
        $mesa = new Mesa();
        $mesa->id_empleado = $id_empleado;
        $mesa->id_cliente = $id_cliente;
        $mesa->id_estado = $id_estado;
        $retorno = $mesa->save();
        return $retorno;
    }
    static function get()
    {
        $collection = Mesa::orderBy('id','DESC')->get();
        return $collection;
    }
    static function deleteById($id)
    {
        $mesa = new Mesa();
        $mesa = $mesa->find($id);
        $mesa->delete();
        return $mesa;
    }
    static function updateById($id_empleado,$id_cliente,$id_estado,$id)
    {
        $mesa = new Mesa();
        $mesa = $mesa->find($id);
        if(!is_null($mesa))
        {
            $mesa->id_empleado = $id_empleado;
            $mesa->id_cliente = $id_cliente;
            $mesa->id_estado = $id_estado;
            $retorno = $mesa->save();
        }else
        {
            $retorno = "Mesa inexistente";
        }
        return $retorno;
    }
}