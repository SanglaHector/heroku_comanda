<?php
namespace Models;

use Components\InterClass;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use stdClass;

class Operacion extends Model 
{
    protected $table = 'operaciones';
    protected $primaryKey = 'id';
    const ALTA = 'A';
    const BAJA= 'B';
    const MODIFICACION_ANTES = 'MA';
    const MODIFICACION_DSP = 'MD';
    static function insert($entidad,$accion,$data)
    {
        $modelo = new Operacion();
        $modelo->entidad = $entidad;
        $modelo->accion = $accion;
        $modelo->data = Operacion::returnDataByModel($entidad,$data);
        $usuario = InterClass::retornarUsuarioPorToken();
        $modelo->id_usu_alta = $usuario->id;
        if(isset($usuario->tipo_empleado))
        {
            $modelo->tipo_usu_alta  = "USUARIO";
        }else{
            $modelo->tipo_usu_alta  = "CLIENTE";
        }
        $retorno = $modelo->save();
        return $retorno;
    }
    static function get()
    {
        $collection = Operacion::orderBy('id','DESC')->get();
        return $collection;
    }
    static function getById($id)
    {
        $modelo = new Operacion();
        $modelo = $modelo->find($id);
        return $modelo;
    }
    static function getByKey($key,$value)
    {
        $retorno = null;
        $modelos = Operacion::where($key,$value)
                            ->limit(1)
                            ->get();
        foreach ($modelos as $modelo) {
            $retorno = $modelo;
        }
        return $retorno;
    }
    static function deleteById($id)
    {
        $modelo = new Operacion();
        $modelo = $modelo->find($id);
        $modelo->delete();
        return $modelo;
    }
    static function updateById($entidad,$id_entidad,$id_usuario,$id_usu_modif,$id_estado,$id)
    {
        $modelo = new Operacion();
        $modelo = $modelo->find($id);
        if(!is_null($modelo))
        {
            $modelo->entidad = $entidad;
            $modelo->id_entidad = $id_entidad;
            $modelo->id_usuario = $id_usuario;
            $modelo->id_usu_modif = $id_usu_modif;
            $modelo->id_estado_actual = $id_estado;
            $usuario = InterClass::retornarUsuarioPorToken();
            $modelo->id_usu_alta = $usuario->id;
            if(is_a($usuario,'Usuario'))
            {
                $modelo->tipo_usu_alta  = "USUARIO";
            }else{
                $modelo->tipo_usu_alta  = "CLIENTE";
            }
            $modelo->save();
        }else
        {
            return 'Operacion inexistente';
        }
        return $modelo;
    }
    static function returnDataByModel($model,$dataIn)
    {
        $dataOut = "";
        switch($model)
        {
            case 'usuario':
                $dataOut = Operacion::dataUsuario($dataIn);
                break;
            case 'cliente':
                $dataOut = Operacion::dataCliente($dataIn);
                break;
            case 'mesa':
                $dataOut = Operacion::dataMesa($dataIn);
                break;
            case 'pedido':
                $dataOut = Operacion::dataPedido($dataIn);
                break;
            case 'producto':
                $dataOut = Operacion::dataProducto($dataIn);
                break;
            case 'ticket':
                $dataOut = Operacion::dataTicket($dataIn);
                break;
            default:
                break;
        }
        return $dataOut;
    }
    static function dataUsuario($dataIn)
    {
        $dataOut = "";
        $standar = new stdClass();
        $standar->id = $dataIn->id;
        $standar->tipo_empleado = $dataIn->tipo_empleado;
        $standar->id_sector = $dataIn->id_sector;
        $standar->id_estado = $dataIn->id_estado;
        $standar->nombre = $dataIn->nombre;
        $standar->apellido = $dataIn->apellido;
        $standar->email = $dataIn->email;
        $standar->clave = $dataIn->clave;
        $dataOut = json_encode($standar);
        return $dataOut;
    }
    static function dataCliente($dataIn)
    {
        $dataOut = "";
        $standar = new stdClass();
        if(is_null($dataIn->id))
        {
            $standar->id = 0;
        }else
        {
            $standar->id = $dataIn->id;
        }
        $standar->email = $dataIn->email;
        $dataOut = json_encode($standar);
        return $dataOut;
    }
    static function dataMesa($dataIn)
    {
        $dataOut = "";
        $standar = new stdClass();
        $standar->id = $dataIn->id;
        $standar->id_empleado = $dataIn->id_empleado;
        $standar->id_estado = $dataIn->id_estado;
        $standar->id_numero = $dataIn->numero;
        $dataOut = json_encode($standar);
        return $dataOut;
    }
    static function dataPedido($dataIn)
    {
        $dataOut = "";
        $standar = new stdClass();
        $standar->id = $dataIn->id;
        $standar->id_ticket = $dataIn->id_ticket;
        $standar->id_producto = $dataIn->id_producto;
        $standar->cantidad = $dataIn->cantidad;
        $standar->id_estado = $dataIn->id_estado;
        $standar->hora_estimada = $dataIn->hora_estimada;
        $standar->hora_final = $dataIn->hora_final;
        $dataOut = json_encode($standar);
        return $dataOut;
    }
    static function dataProducto($dataIn)
    {
        $dataOut = "";
        $standar = new stdClass();
        $standar->id = $dataIn->id;
        $standar->id_sector = $dataIn->id_sector;
        $standar->nombre = $dataIn->nombre;
        $standar->stock = $dataIn->stock;
        $standar->precio = $dataIn->precio;
        $standar->tiempo_preparacion = $dataIn->tiempo_preparacion;
        $dataOut = json_encode($standar);
        return $dataOut;
    }
    static function dataTicket($dataIn)
    {
        $dataOut = "";
        $standar = new stdClass();
        $standar->id = $dataIn->id;
        $standar->id_mesa = $dataIn->id_mesa;
        $standar->id_foto = $dataIn->id_foto;
        $standar->precio_final = $dataIn->precio_final;
        $dataOut = json_encode($standar);
        return $dataOut;
    }
    static function cantidadOperaciones($sector)
    {
        $models = Operacion::join('usuarios','operaciones.id_usu_alta','=','usuarios.id')
        ->where('entidad','=', "pedido")
        ->where('tipo_usu_alta','=', "USUARIO")
        ->where('accion', '=', 'MA')
        ->where('usuarios.id_sector','=',$sector)
        ->count();
        return $models;
    }
    static function cantidadOperacionesPorUsu($sector)
    {
        $models = Operacion::join('usuarios','operaciones.id_usu_alta','=','usuarios.id')
        ->select(Operacion::raw('count(*),usuarios.apellido'))
        ->where('entidad','=', "pedido")
        ->where('tipo_usu_alta','=', "USUARIO")
        ->where('accion', '=', 'MA')
        ->where('usuarios.id_sector','=',$sector)
        ->groupBy('usuarios.apellido')
        ->get();
        return $models;
    }
    static function operacionesPorUsuario()
    {
        $models = Operacion::join('usuarios','operaciones.id_usu_alta','=','usuarios.id')
        ->select(Operacion::raw('count(*),usuarios.apellido'))
        ->where('entidad','=', "pedido")
        ->where('tipo_usu_alta','=', "USUARIO")
        ->where('accion', '=', 'MA')
        ->groupBy('usuarios.apellido')
        ->get();
        return $models;
    }
}