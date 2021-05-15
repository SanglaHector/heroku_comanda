<?php
namespace Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Usuario extends Model
{
    protected $table = 'usuarios';
    protected $primaryKey = 'id';
    //public $incrementing = false;
    //protected $keyType = 'string';
    //public $timestamps = false;
    //protected $dateFormat = 'U';
    //const CREATED_AT = 'creation_date';
    //const UPDATED_AT = 'last_update';
    use SoftDeletes;
    //const DELETED_AT = 'deleted_at';
    static function retornarUltimoId()
    {
        $id = 0;
        $collection = Usuario::orderBy('id','DESC')->limit(1)->get();
        foreach ($collection as $attribute ) {
            $id = $attribute->id;
        }
       return $id;
    }
    
}