<?php
namespace Config; 
use Illuminate\Events\Dispatcher;
use Illuminate\Container\Container;
use Illuminate\Database\Capsule\Manager as Capsule;
class Database{
	public function __construct(){
    $capsule = new Capsule;

  /*$capsule->addConnection([
        'driver'    => 'mysql',
        'host'      => 'localhost',
        'database'  => 'la_comanda',
        'username'  => 'root',
        'password'  => '',
        'charset'   => 'utf8',
        'collation' => 'utf8_unicode_ci',
        'prefix'    => '',
    ]);*/
    $capsule->addConnection([
        'driver'    => 'mysql',
        'host'      => 'remotemysql.com',
        'database'  => 'p0lz4sXyxG',
        'username'  => 'p0lz4sXyxG',
        'password'  => 'R6BULS9Exq',
        'charset'   => 'utf8',
        'collation' => 'utf8_unicode_ci',
        'prefix'    => '',
    ]);
    
    $capsule->setEventDispatcher(new Dispatcher(new Container));
    $capsule->setAsGlobal();
    $capsule->bootEloquent();
    }
}
