<?php
namespace Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Enums\Eestado;
class Mesa extends Model
{
    protected $table = 'mesas';
    protected $primaryKey = 'id';
    use SoftDeletes;
    static function insert($id_empleado,$id_cliente,$id_estado,$numero)
    {
        $mesa = new Mesa();
        $mesa->id_empleado = $id_empleado;
        $mesa->id_cliente = $id_cliente;
        $mesa->id_estado = $id_estado;
        $mesa->numero = $numero;
        $retorno = $mesa->save();
        Operacion::insert('mesa',Operacion::ALTA,$mesa);
        return $retorno;
    }
    static function get()
    {
        $collection = Mesa::orderBy('id','DESC')->get();
        return $collection;
    }
    static function deleteById($id)
    {
        $mesa = new Mesa();
        $mesa = $mesa->find($id);
        Operacion::insert('mesa',Operacion::BAJA,$mesa);
        $mesa->delete();
        return $mesa;
    }
    static function updateById($id_empleado,$id_cliente,$id_estado,$numero,$id)
    {
        $mesa = new Mesa();
        $mesa = $mesa->find($id);
        Operacion::insert('mesa',Operacion::MODIFICACION_ANTES,$mesa);
        if(!is_null($mesa))
        {
            $mesa->id_empleado = $id_empleado;
            $mesa->id_cliente = $id_cliente;
            $mesa->id_estado = $id_estado;
            $mesa->numero = $numero;
            $mesa->save();
            Operacion::insert('mesa',Operacion::MODIFICACION_DSP,$mesa);
        }
        return $mesa;
    }
    static function getByKey($key,$value)
    {
        $retorno = null;
        $mesas = Mesa::where($key,$value)
                            ->limit(1)
                            ->get();
        foreach ($mesas as $mesa) {
            $retorno = $mesa;
        }
        if(is_null($retorno))
        {
            $retorno = null;
        }
        return $retorno;
    }
    static function getAllByKey($key,$value)
    {
        $retorno = array();
        $mesas = Mesa::where($key,$value)
                            ->get();
        foreach ($mesas as $mesa) {
            array_push($retorno,$mesa);
        }
        return $retorno;
    }
    static function getById($id)
    {
        return Mesa::find($id);
    }
    static function getActiveByKey($key,$value)
    {
        $retorno = null;
        $mesa = Mesa::where($key,$value)
                    ->where('id_estado',13)
                    ->limit(1)
                    ->get();
        foreach ($mesa as $m ) {
            $retorno = $m;
        }
        return $retorno;
    }
    static function changeState($model,$state)
    {
        $model = Mesa::find($model->id);
        $model->id_estado = $state;
        $model->save();
    }
    static function getCount($id_empleado)
    {
        $count = 0;
        $mesas =  Mesa::where('id_empleado',$id_empleado)
        ->get();
        foreach ($mesas as $mesa ) {
            if($mesa->id_estado == Eestado::DISPONIBLE ||
               $mesa->id_estado == Eestado::CON_CLIENTE_PAGANDO ||
               $mesa->id_estado == Eestado::CLIENTE_MIRANDO_CARTA)
               $count = $count + 1;
        }
        return $count;
    }
    static function hasMesa($id_empleado)
    {
        $retorno = false;
        $mesas = Mesa::where('id_empleado',$id_empleado)
        ->get();
        foreach ($mesas as $mesa ) {
            if($mesa->id_estado == Eestado::DISPONIBLE ||
               $mesa->id_estado == Eestado::CON_CLIENTE_PAGANDO ||
               $mesa->id_estado == Eestado::CLIENTE_MIRANDO_CARTA)
               $retorno =  true;
        }
        return $retorno;
    }
    static function getNonFree()
    {
        $mesas = Mesa::where('id_empleado','!=',0)
        ->where('id_estado','!=',Eestado::CERRADA)
        ->get();
        return $mesas;
    }
    static function retornarMesaCliente($id_cliente)
    {
        $mesas = Mesa::get();
        $retorno = null;
        foreach ($mesas as $mesa ) {
            if(($mesa->id_estado == Eestado::CLIENTE_ESPERANDO_PEDIDO || Eestado::CON_CLIENTE_PAGANDO) 
            && $mesa->id_cliente == $id_cliente)
            {
                $retorno = $mesa;
            }
        }
        return $retorno;
    }
    static function convertToModelCSV($array)
    {
        $model = new Mesa();
        $model->id = $array['0'];
        $model->id_empleado = $array['1'];
        $model->id_cliente = $array['2'];
        $model->id_estado = $array['3'];
        $model->numero = trim($array['4'], ';');
        return $model;
    }
    static function exist($id)
    {
        $model = Mesa::find($id);
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
            $model->__get('id_empleado'),
            $model->__get('id_cliente'),
            $model->__get('id_estado'),
            $model->__get('numero')
        );
        $string = implode(',',$array);
        $string = $string.';'.PHP_EOL;
        return $string;
    }
    //consultas especificas
    //mesa mas usada
    static function mesasUsadas()
    {
        $models = Mesa::select(Mesa::raw('count(numero), numero'))
        ->where('id_cliente','!=',(-1))
        ->groupBy('id_cliente')
        ->get();
        return $models;
    }
    static function mesaMasFacturo()
    {
        $models = Mesa::join('tickets','mesas.id','=','tickets.id_mesa')
        ->select(Mesa::raw('sum(precio_total),mesas.numero'))
        ->groupBy('numero')
        ->get();
        return $models;
    }
    static function MayorImporte()
    {
        $models = Mesa::join('tickets','mesas.id','=','tickets.id_mesa')
        ->select(Mesa::raw('max(precio_total),mesas.numero'))
        ->get();
        return $models;
    }
} 