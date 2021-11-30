<?php
namespace Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Cliente extends Model 
{
    protected $table = 'clientes';
    protected $primaryKey = 'id';
    use SoftDeletes;
    function __construct()
    {
        
    }
    static function insert($email,$clave,$id_estado)
    {
        $model = new Cliente();
        $model->email = $email;
        $clave = crypt($model->clave,'SHA-256');
        $model->clave = $clave;
        $model->id_estado = $id_estado;
        Operacion::insert('cliente',Operacion::ALTA,$model);//prueba
        $retorno = $model->save();
        return $retorno;
    }
    static function get()
    {
        $collection = Cliente::orderBy('id','DESC')->get();
        return $collection;
    }
    static function getById($id)
    {
        return Cliente::find($id);
    }
    static function getByKey($key,$value)
    {
        $retorno = null;
        $models = Cliente::where($key,$value)
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
        $models = Cliente::where($key,$value)
                            ->get();
        foreach ($models as $model) {
            array_push($retorno,$model);
        }
        return $retorno;
    }
    static function deleteById($id)
    {
        $model = new Cliente();
        $model = $model->find($id);
        Operacion::insert('cliente',Operacion::BAJA,$model);
        $model->delete();
        return $model;
    }
    static function updateById($email,$clave,$estado,$id)
    {
        $model = new Cliente();
        $model = $model->find($id);
        Operacion::insert('cliente',Operacion::MODIFICACION_ANTES,$model);
        if(!is_null($model))
        {
            $model->email = $email;
           // $model->clave = crypt($clave,'SHA-256');
            $model->clave = $clave;
            $model->id_estado = $estado;
            $model->save();
            Operacion::insert('cliente',Operacion::MODIFICACION_DSP,$model);
        }else
        {
            return 'Cliente inexistente';
        }
        return $model;
    }
    static function convertToModelCSV($array)
    {
        $model = new Cliente();
        $model->id = $array['0'];
        $model->email = $array['1'];
        $model->clave = $array['2'];
        $model->id_estado = trim($array['3'], ';');//quito el ultimo ';' que queda en el cantidad
        return $model;
    }
    static function exist($id)
    {
        $model = Cliente::find($id);
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
            $model->__get('email'),
            $model->__get('clave'),
            $model->__get('id_estado')
        );
        $string = implode(',',$array);
        $string = $string.';'.PHP_EOL;
        return $string;
    }
}