<?php
namespace Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Sector extends Model
{
    protected $table = 'sectores';
    protected $primaryKey = 'id';
    use SoftDeletes;
    static function insert($nombre,$id_tipo_empleado)
    {
        $model = new Sector();
        $model->nombre = $nombre;
        $model->id_tipo_empleado = $id_tipo_empleado;
        $retorno = $model->save();
        return $retorno;
    }
    static function get()
    {
        $collection = Sector::orderBy('id','DESC')->get();
        return $collection;
    }
    static function getById($id)
    {
        $model = new Sector();
        $model = $model->find($id);
        return $model;
    }
    static function getByKey($key,$value)
    {
        $retorno = null;
        $models = Sector::where($key,$value)
                            ->limit(1)
                            ->get();
        foreach ($models as $model) {
            $retorno = $model;
        }
        return $retorno;
    }
    static function deleteById($id)
    {
        $model = new Sector();
        $model = $model->find($id);
        $model->delete();
        return $model;
    }
    static function updateById($nombre,$id_tipo_empleado,$id)
    {
        $model = new Sector();
        $model = $model->find($id);
        if(!is_null($model))
        {
            $model->nombre = $nombre;
            $model->id_tipo_empleado = $id_tipo_empleado;
            $model->save();
        }else
        {
            return 'Sector inexistente';
        }
        return $model;
    }
    
}