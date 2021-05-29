<?php
namespace Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Producto extends Model
{
    protected $table = 'productos';
    protected $primaryKey = 'id';
    //public $incrementing = false;
    //protected $keyType = 'string';
    //public $timestamps = false;
    //protected $dateFormat = 'U';
    //const CREATED_AT = 'creation_date';
    //const UPDATED_AT = 'last_update';
    use SoftDeletes;
    //const DELETED_AT = 'deleted_at';
    static function insert($id_sector,$nombre,$stock,$precio,$tiempo_preparacion)
    {
        $producto = new Producto();
        $producto->id_sector = $id_sector;
        $producto->nombre = $nombre;
        $producto->stock = $stock;
        $producto->precio = $precio;
        $producto->tiempo_preparacion = $tiempo_preparacion;
        $retorno = $producto->save();
        return $retorno;
    }
    static function get()
    {
        $collection = Producto::orderBy('id','DESC')->get();
        return $collection;
    }
    static function deleteById($id)
    {
        $producto = new Producto();
        $producto = $producto->find($id);
        $producto->delete();
        return $producto;
    }
    static function updateById($id_sector,$nombre,$stock,$precio,$tiempo_preparacion,$id)
    {
        $producto = new Producto();
        $producto = $producto->find($id);
        if(!is_null($producto))
        {
            $producto = new Producto();
            $producto->id_sector = $id_sector;
            $producto->nombre = $nombre;
            $producto->stock = $stock;
            $producto->precio = $precio;
            $producto->tiempo_preparacion = $tiempo_preparacion;
            $retorno = $producto->save();
        }else{
            $retorno = "Producto inexistente";
        }
        return $retorno;
    }
}