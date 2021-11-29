<?php
namespace Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Estado extends Model
{
    protected $table = 'estados';
    protected $primaryKey = 'id';
    use SoftDeletes;
    static function insert($estado,$entidad)
    {
        $model = new Estado();
        $model->estado = $estado;
        $model->id_entidad = $entidad;
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
    static function updateById($estado,$entidad,$id)
    {
        $model = new Estado();
        $model = $model->find($id);
        if(!is_null($model))
        {
            $model->estado = $estado;
            $model->id_entidad = $entidad;
            $model->save();
        }else
        {
            return 'Estado inexistente';
        }
        return $model;
    }
}