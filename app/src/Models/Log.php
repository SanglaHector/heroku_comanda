<?php
namespace Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
class Log extends Model
{
    protected $table = 'logs';
    protected $primaryKey = 'id';
    //public $incrementing = false;
    //protected $keyType = 'string';
    //public $timestamps = false;
    //protected $dateFormat = 'U';
    //const CREATED_AT = 'creation_date';
    //const UPDATED_AT = 'last_update';
    //use SoftDeletes;
    //const DELETED_AT = 'deleted_at';
    static function insert($id_usuario,$id_sector,$in_out)
    {
        $model = new Log();
        $model->id_usuario = $id_usuario;
        $model->id_sector = $id_sector;
        $model->in_out = $in_out;
        $retorno = $model->save();
        return $retorno;
    }
    static function get()
    {
        $collection = Log::orderBy('id','DESC')->get();
        return $collection;
    }
    static function getById($id)
    {
        $model = new Log();
        $model = $model->find($id);
        return $model;
    }
    static function getByKey($key,$value)
    {
        $retorno = null;
        $models = Log::where($key,$value)
                            ->limit(1)
                            ->get();
        foreach ($models as $model) {
            $retorno = $model;
        }
        return $retorno;
    }
    static function deleteById($id)
    {
        $model = new Log();
        $model = $model->find($id);
        $model->delete();
        return $model;
    }
    static function updateById($id_usuario,$id_sector,$in_out,$id)
    {
        $model = new Log();
        $model = $model->find($id);
        if(!is_null($model))
        {
            $model->id_usuario = $id_usuario;
            $model->id_sector = $id_sector;
            $model->in_out = $in_out;
            $model->save();
        }else
        {
            return 'Log inexistente';
        }
        return $model;
    }
    static function getLastLog($id_usuario)
    {
        $log = Log::where('id_usuario',$id_usuario)
                    ->orderByDesc('created_at')
                    ->first();
        return $log;
    }
    static function getLastSector($id_sector)
    {
        $log = Log::where('id_sector',$id_sector)
                    ->orderByDesc('created_at')
                    ->first();
        return $log;
    }
    static function getAllWorking($users)
    {
        $logs = new Collection();
        foreach ($$users as $user ) {
            $log = Log::getLastLog($user->id);
            if(!is_null($log) && $log->in_out)
            {
                $losg->push($log);
            }
        }
        return $logs;
    }
}