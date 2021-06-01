<?php
namespace Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Cliente extends Model 
{
    protected $table = 'clientes';
    protected $primaryKey = 'id';
    //public $incrementing = false;
    //protected $keyType = 'string';
    //public $timestamps = false;
    //protected $dateFormat = 'U';
    //const CREATED_AT = 'creation_date';
    //const UPDATED_AT = 'last_update';
    use SoftDeletes;
    //const DELETED_AT = 'deleted_at'; 
    static function insert($email,$clave)
    {
        $model = new Cliente();
        $model->email = $email;
        $model->clave = $clave;
        $retorno = $model->save();
        return $retorno;
    }
    static function get()
    {
        $collection = Cliente::orderBy('id','DESC')->get();
        return $collection;
    }
    static function getById($id)
    {
        $model = new Cliente();
        $model = $model->find($id);
        return $model;
    }
    static function getByKey($key,$value)
    {
        $retorno = null;
        $models = Cliente::where($key,$value)
                            ->limit(1)
                            ->get();
        foreach ($models as $model) {
            $retorno = $model;
        }
        return $retorno;
    }
    static function deleteById($id)
    {
        $model = new Cliente();
        $model = $model->find($id);
        $model->delete();
        return $model;
    }
    static function updateById($email,$clave,$id)
    {
        $model = new Cliente();
        $model = $model->find($id);
        if(!is_null($model))
        {
            $model->email = $email;
            $model->clave = $clave;
            $model->save();
        }else
        {
            return 'Cliente inexistente';
        }
        return $model;
    }
}