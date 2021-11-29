<?php
namespace Models;
use Components\Validaciones;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Producto extends Model
{
    protected $table = 'productos';
    protected $primaryKey = 'id';
    use SoftDeletes;
    static function insert($id_sector,$nombre,$stock,$precio,$tiempo_preparacion)
    {
        $producto = new Producto();
        $producto->id_sector = $id_sector;
        $producto->nombre = $nombre;
        $producto->stock = $stock;
        $producto->precio = $precio;
        $producto->tiempo_preparacion = $tiempo_preparacion;
        $retorno = $producto->save();
        Operacion::insert('producto',Operacion::ALTA,$producto);
        return $retorno;
    }
    static function get()
    {
        $collection = Producto::orderBy('id','DESC')->get();
        return $collection;
    }
    static function getById($id)
    {
        $model = Producto::find($id);
        return $model;
    }
    static function deleteById($id)
    {
        $producto = new Producto();
        $producto = $producto->find($id);
        Operacion::insert('producto',Operacion::BAJA,$producto);
        $producto->delete();
        return $producto;
    }
    static function updateById($id_sector,$nombre,$stock,$precio,$tiempo_preparacion,$id)
    {
        $producto = new Producto();
        $producto = $producto->find($id);
        Operacion::insert('producto',Operacion::MODIFICACION_ANTES,$producto);
        if(!is_null($producto))
        {
            $producto->id_sector = $id_sector;
            $producto->nombre = $nombre;
            $producto->stock = $stock;
            $producto->precio = $precio;
            $producto->tiempo_preparacion = $tiempo_preparacion;
            $producto->save();
            Operacion::insert('producto',Operacion::MODIFICACION_DSP,$producto);
        }else{
            $producto = "Producto inexistente";
        }
        return $producto;
    }
    static function convertToModelCSV($array)
    {
        $model = new Producto();
        $date = Validaciones::formatearHora(trim($array['5'], ';'));
        $model->id = $array['0'];
        $model->id_sector = $array['1'];
        $model->nombre = $array['2'];
        $model->stock = $array['3'];
        $model->precio = $array['4'];
        $model->tiempo_preparacion = $date;
        return $model;
    }
    static function exist($id)
    {
        $model = Producto::find($id);
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
            $model->__get('id_sector'),
            $model->__get('nombre'),
            $model->__get('stock'),
            $model->__get('tiempo_preparacion')
        );
        $string = implode(',',$array);
        $string = $string.';'.PHP_EOL;
        return $string;
    }
}