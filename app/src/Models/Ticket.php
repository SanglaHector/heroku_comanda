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
        Operacion::insert('ticket',Operacion::ALTA,$model);
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
    static function getLast()
    {
        $model = Ticket::get()
        ->first();
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
    static function getAllByKey($key,$value)
    {
        $retorno = array();
        $models = Ticket::where($key,$value)
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
        Operacion::insert('ticket',Operacion::BAJA,$model);
        $model->delete();
        return $model;
    }
    static function updateById($id_mesa,$id_foto,$precio_total,$id)
    {
        $model = new Ticket();
        $model = $model->find($id);
        Operacion::insert('ticket',Operacion::MODIFICACION_ANTES,$model);
        if(!is_null($model))
        {
            $model->id_mesa = $id_mesa;
            $model->id_foto = $id_foto;
            $model->precio_total = $precio_total;
            $model->save();
            Operacion::insert('ticket',Operacion::MODIFICACION_DSP,$model);
        }else
        {
            return 'Ticket inexistente';
        }
        return $model;
    }
    static function createPhotoName($mesa,$ticket)
    {
        return  $mesa . '_' . $ticket;
    }
    static function returnPhotoName($name)
    {
        $array = explode('/',$name);
        $array = explode('_',$array[2]);
        $array = explode('.',$array[0]);
        return $array[0];        
    }
    static function convertToModelCSV($array)
    {
        $model = new Ticket();
        $model->id = $array['0'];
        $model->id_mesa = $array['1'];
        $model->id_foto = $array['2'];
        $model->precio_total = trim($array['3'], ';');
        return $model;
    }
    static function exist($id)
    {
        $model = Ticket::find($id);
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
            $model->__get('id_mesa'),
            $model->__get('id_foto'),
            $model->__get('precio_total')
        );
        $string = implode(',',$array);
        $string = $string.';'.PHP_EOL;
        return $string;
    }
    static function FacturaPorMesa()//no funciona
    {
        $models = Ticket::
        join('mesas','tickets.id_mesa','=','mesas.id')
        ->select(Ticket::raw('SUM(tickets.precio_total),mesas.numero'))
        ->groupBy('numero')
        ->get();
        return $models;
    }
}