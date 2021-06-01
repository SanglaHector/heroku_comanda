<?php
namespace Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Encuesta extends Model
{
    protected $table = 'encuestas';
    protected $primaryKey = 'id';
    //public $incrementing = false;
    //protected $keyType = 'string';
    //public $timestamps = false;
    //protected $dateFormat = 'U';
    //const CREATED_AT = 'creation_date';
    //const UPDATED_AT = 'last_update';
    use SoftDeletes;
    //const DELETED_AT = 'deleted_at';
    static function insert($id_cliente,$mesa,$restaurante,$mozo,$cocinero,$id_ticket,$descripcion)
    {
        $model = new Encuesta();
        $model->id_cliente = $id_cliente;
        $model->mesa = $mesa;
        $model->restaurante =$restaurante;
        $model->mozo = $mozo;
        $model->cocinero = $cocinero;
        $model->id_ticket = $id_ticket;
        $model->descripcion = $descripcion;
        $retorno = $model->save();
        return $retorno;
    }
    static function get()
    {
        $collection = Encuesta::orderBy('id','DESC')->get();
        return $collection;
    }
    static function getById($id)
    {
        $model = new Encuesta();
        $model = $model->find($id);
        return $model;
    }
    static function getByKey($key,$value)
    {
        $retorno = null;
        $models = Encuesta::where($key,$value)
                            ->limit(1)
                            ->get();
        foreach ($models as $model) {
            $retorno = $model;
        }
        return $retorno;
    }
    static function deleteById($id)
    {
        $model = new Encuesta();
        $model = $model->find($id);
        $model->delete();
        return $model;
    }
    static function updateById($id_cliente,$mesa,$restaurante,$mozo,$cocinero,$id_ticket,$descripcion,$id)
    {
        $model = new Encuesta();
        $model = $model->find($id);
        if(!is_null($model))
        {
            $model->id_cliente = $id_cliente;
            $model->mesa = $mesa;
            $model->restaurante =$restaurante;
            $model->mozo = $mozo;
            $model->cocinero = $cocinero;
            $model->id_ticket = $id_ticket;
            $model->descripcion = $descripcion;
            $model->save();
        }else
        {
            return 'Encuesta inexistente';
        }
        return $model;
    }
}