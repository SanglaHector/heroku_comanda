<?php
namespace Models;

use Components\Validaciones;
use DateTime;
use Enums\Eestado;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Date;

class Pedido extends Model
{
    protected $table = 'pedidos';
    protected $primaryKey = 'id';
    const UPDATED_AT = 'update_at';
    use SoftDeletes;
    static function insert($id_ticket,$id_producto,$cantidad,$id_estado,$hora_estimada = '01:00:00')
    {
        $pedido = new Pedido();
        $pedido->id_ticket = $id_ticket;
        $pedido->id_producto = $id_producto;
        $pedido->cantidad = $cantidad;
        $pedido->id_estado = $id_estado;
        $pedido->hora_estimada = $hora_estimada;
        $pedido->hora_final = null;
        $pedido->save();
        Operacion::insert('pedido',Operacion::ALTA,$pedido);
        return $pedido;
    }
    static function get()
    {
        $collection = Pedido::orderBy('id','DESC')->get();
        return $collection;
    }
    static function getById($id)
    {
        $pedido = Pedido::find($id);
        return $pedido;
    }
    static function getByKey($key,$value)
    {
        $retorno = null;
        $models = Pedido::where($key,$value)
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
        $models = Pedido::where($key,$value)
                            ->get();
        foreach ($models as $model) {
            array_push($retorno,$model);
        }
        return $retorno;
    }
    static function deleteById($id)
    {
        $pedido = new Pedido();
        $pedido = $pedido->find($id);
        Operacion::insert('pedido',Operacion::BAJA,$pedido);
        $pedido->delete();
        return $pedido;
    }
    static function updateById($id_ticket,$id_producto,$cantidad,$id_estado,$id,$hora_estimada,$hora_final)
    {
        $pedido = Pedido::find($id); 
        Operacion::insert('pedido',Operacion::MODIFICACION_ANTES,$pedido);
        if(!is_null($pedido))
        {
            $pedido->id_ticket = $id_ticket;
            $pedido->id_producto = $id_producto;
            $pedido->cantidad = $cantidad;
            $pedido->id_estado = $id_estado;
            $pedido->hora_estimada = $hora_estimada;
            $pedido->hora_final = $hora_final;
            $pedido->save();
            Operacion::insert('pedido',Operacion::MODIFICACION_DSP,$pedido);
        }else
        {
            $pedido = "Pedido inexistente";
        }
        return $pedido;
    }
    static function calcularDifHoras($model)
    {
        //formateo timestamps
        $timestamps = date('Y-m-d H:i:s');
        $update_at =  date('Y-m-d H:i:s',$model->update_at->timestamp);
        //calculo 
        $minutos = (strtotime($update_at)-strtotime($timestamps))/60;
        $minutos = abs($minutos); 
        $minutos = floor($minutos);
        $horas = floor(abs($minutos / 60));
        $minutosFinal = $minutos - ($horas*60);
        //formateo
        $horas = strval($horas);
        $minutosFinal = strval($minutosFinal);
        if(strlen($horas) == 1)
        {
            $horas = "0".$horas;
        }
        if(strlen($minutosFinal) == 1)
        {
            $minutosFinal = "0".$minutosFinal;
        }
        $retorno = $horas.":".$minutosFinal.":00";
        return $retorno;
    }
    static function convertToModelCSV($array)
    {
        $model = new Pedido();
        $date = Validaciones::formatearHora(trim($array['5'], ';'));
        $model->id = $array['0'];
        $model->id_ticket = $array['1'];
        $model->id_producto = $array['2'];
        $model->cantidad = $array['3'];
        $model->id_estado = $array['4'];
        $model->hora_estimada = $date;
        return $model;
    }
    static function exist($id)
    {
        $model = Pedido::find($id);
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
            $model->__get('id_ticket'),
            $model->__get('id_producto'),
            $model->__get('cantidad'),
            $model->__get('id_estado'),
            $model->__get('hora_estimada'),
            $model->__get('hora_final')
        );
        $string = implode(',',$array);
        $string = $string.';'.PHP_EOL;
        return $string;
    }
    static function masVendido()
    {
        $models = Pedido::join('productos','pedidos.id_producto','=','productos.id')
        ->select(Pedido::raw('sum(pedidos.cantidad),productos.nombre'))
        ->groupBy('pedidos.id_producto')
        ->get();
        return $models;
    }
    static function fueraDeTiempo()
    {
        $models = Pedido::join('productos','pedidos.id_producto','=','productos.id')
        ->select(Pedido::raw('hora_estimada,hora_final,productos.nombre'))
        ->whereRaw('hora_estimada < hora_final' )
        ->get();
        return $models;
    }
    static function cancelados()
    {
        return Pedido::getAllByKey('id_estado',Eestado::CANCELADO);
    }
}