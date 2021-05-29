<?php
namespace Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Pedido extends Model
{
    protected $table = 'pedidos';
    protected $primaryKey = 'id';
    //public $incrementing = false;
    //protected $keyType = 'string';
    //public $timestamps = false;
    //protected $dateFormat = 'U';
    //const CREATED_AT = 'creation_date';
    //const UPDATED_AT = 'last_update';
    use SoftDeletes;
    //const DELETED_AT = 'deleted_at';
    static function insert($id_ticket,$id_producto,$cantidad,$id_estado)
    {
        $pedido = new Pedido();
        $pedido->id_ticket = $id_ticket;
        $pedido->id_producto = $id_producto;
        $pedido->cantidad = $cantidad;
        $pedido->id_estado = $id_estado;
        $pedido->hora_estimada = '01:00:00';
        $pedido->hora_final = null;
        $retorno = $pedido->save();
        return $retorno;
    }
    static function get()
    {
        $collection = Pedido::orderBy('id','DESC')->get();
        return $collection;
    }
    static function deleteById($id)
    {
        $pedido = new Pedido();
        $pedido = $pedido->find($id);
        $pedido->delete();
        return $pedido;
    }
    static function updateById($id_ticket,$id_producto,$cantidad,$id_estado,$id)
    {
        $pedido = new Pedido();
        $pedido = $pedido->find($id);
        if(!is_null($pedido))
        {
            $pedido = new Pedido();
            $pedido->id_ticket = $id_ticket;
            $pedido->id_producto = $id_producto;
            $pedido->cantidad = $cantidad;
            $pedido->id_estado = $id_estado;
            $pedido->hora_estimada = '01:00:00';
            $pedido->hora_final = null;
        $retorno = $pedido->save();
        }else
        {
            $retorno = "Pedido inexistente";
        }
        return $retorno;
    }
}