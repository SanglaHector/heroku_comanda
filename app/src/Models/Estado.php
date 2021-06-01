<?php
namespace Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Estado extends Model
{
    protected $table = 'estados';
    protected $primaryKey = 'id';
    //public $incrementing = false;
    //protected $keyType = 'string';
    //public $timestamps = false;
    //protected $dateFormat = 'U';
    //const CREATED_AT = 'creation_date';
    //const UPDATED_AT = 'last_update';
    use SoftDeletes;
    //const DELETED_AT = 'deleted_at';
    static function insert($estado)
    {
        $model = new Estado();
        $model->estado = $estado;
        $retorno = $model->save();
        return $retorno;
    }
    static function get()
    {
        $collection = Estado::orderBy('id','DESC')->get();
        return $collection;
    }
    static function getById($id)
    {
        $model = new Estado();
        $model = $model->find($id);
        return $model;
    }
    static function getByKey($key,$value)
    {
        $retorno = null;
        $models = Estado::where($key,$value)
                            ->limit(1)
                            ->get();
        foreach ($models as $model) {
            $retorno = $model;
        }
        return $retorno;
    }
    static function deleteById($id)
    {
        $model = new Estado();
        $model = $model->find($id);
        $model->delete();
        return $model;
    }
    static function updateById($estado,$id)
    {
        $model = new Estado();
        $model = $model->find($id);
        if(!is_null($model))
        {
            $model->estado = $estado;
            $model->save();
        }else
        {
            return 'Estado inexistente';
        }
        return $model;
    }
}