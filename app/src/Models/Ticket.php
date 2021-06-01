<?php
namespace Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Ticket extends Model
{
    protected $table = 'tickets';
    protected $primaryKey = 'id';
    //public $incrementing = false;
    //protected $keyType = 'string';
    //public $timestamps = false;
    //protected $dateFormat = 'U';
    //const CREATED_AT = 'creation_date';
    //const UPDATED_AT = 'last_update';
    use SoftDeletes;
    //const DELETED_AT = 'deleted_at';
    static function insert($id_mesa,$id_foto,$precio_total)
    {
        $model = new Ticket();
        $model->id_mesa = $id_mesa;
        $model->id_foto = $id_foto;
        $model->precio_total = $precio_total;
        $retorno = $model->save();
        return $retorno;
    }
    static function get()
    {
        $collection = Ticket::orderBy('id','DESC')->get();
        return $collection;
    }
    static function getById($id)
    {
        $model = new Ticket();
        $model = $model->find($id);
        return $model;
    }
    static function getByKey($key,$value)
    {
        $retorno = null;
        $models = Ticket::where($key,$value)
                            ->limit(1)
                            ->get();
        foreach ($models as $model) {
            $retorno = $model;
        }
        return $retorno;
    }
    static function deleteById($id)
    {
        $model = new Ticket();
        $model = $model->find($id);
        $model->delete();
        return $model;
    }
    static function updateById($id_mesa,$id_foto,$precio_total,$id)
    {
        $model = new Ticket();
        $model = $model->find($id);
        if(!is_null($model))
        {
            $model->id_mesa = $id_mesa;
            $model->id_foto = $id_foto;
            $model->precio_total = $precio_total;
            $model->save();
        }else
        {
            return 'Ticket inexistente';
        }
        return $model;
    }
}