<?php
//require __DIR__ . '/../vendor/autoload.php';
require __DIR__. './vendor/autoload.php';
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
//Configuracion base de datos
use Config\Database;
//Controladores 
use Controllers\ClienteController;
use Controllers\EmpleadoController;
use Controllers\EncuestaController;
use Controllers\EstadoController;
use Controllers\LogController;
use Controllers\MesaController;
use Controllers\OperacionController;
use Controllers\PedidoController;
use Controllers\ProductoController;
use Controllers\SectorController;
use Controllers\SocioController;
use Controllers\TicketController;
use Controllers\TipoEmpleadoController;
//Enumerados
use Enums\Eestado;
use Slim\Routing\RouteCollectorProxy;

$app = AppFactory::create();
$app->setBasePath("/app");
/*$database = new Database();
//empleados
$app->group('/Empleado', function (RouteCollectorProxy $group)
{
    $group->post('[/]',EmpleadoController::class.':addOne');
 //   $group->get('s[/{id}]',EmpleadoController::class.':getAll'); 
    $group->get('s[/{clave}[/{valor}]]',EmpleadoController::class.':getAll'); 
 //   $group->delete('/delete',EmpleadoController::class.':deleteAll');
    $group->delete('/delete/{id}',EmpleadoController::class.':deleteOne');
    $group->put('/update',EmpleadoController::class.':updateAll');
  //  $group->put('/update/{id}',EmpleadoController::class.':updateOne');
});
//usuarios
/*$app->group('/Usuario', function (RouteCollectorProxy $group)
{
    $group->post('[/]',UsuarioController::class.':addOne');
    $group->get('s[/{id}]',UsuarioController::class.':getAll'); 
    $group->get('s[/{clave}/{valor}]',UsuarioController::class.':get'); 
    $group->get('/delete',UsuarioController::class.':deleteAll');
    $group->get('/delete/{id}',UsuarioController::class.':deleteOne');
    $group->get('/update',UsuarioController::class.':updateAll');
    $group->get('/update/{id}',UsuarioController::class.':updateOne');
});
//clientes
$app->group('/Clientes', function (RouteCollectorProxy $group)
{
    $group->post('[/]',ClienteController::class.':addOne');
    $group->get('s[/{id}]',ClienteController::class.':getAll'); 
    $group->get('s[/{clave}/{valor}]',ClienteController::class.':get'); 
    $group->get('/delete',ClienteController::class.':deleteAll');
    $group->get('/delete/{id}',ClienteController::class.':deleteOne');
    $group->get('/update',ClienteController::class.':updateAll');
    $group->get('/update/{id}',ClienteController::class.':updateOne');
});
//socio
$app->group('/Socio', function (RouteCollectorProxy $group)
{
    $group->post('[/]',SocioController::class.':addOne');
    $group->get('s[/{id}]',SocioController::class.':getAll'); 
    $group->get('s[/{clave}/{valor}]',SocioClienteController::class.':get'); 
    $group->get('/delete',SocioController::class.':deleteAll');
    $group->get('/delete/{id}',SocioController::class.':deleteOne');
    $group->get('/update',SocioController::class.':updateAll');
    $group->get('/update/{id}',SocioController::class.':updateOne');
});
//encuesta
$app->group('/Encuesta', function (RouteCollectorProxy $group)
{
    $group->post('[/]',EncuestaController::class.':addOne');
    $group->get('s[/{id}]',EncuestaController::class.':getAll'); 
    $group->get('s[/{clave}/{valor}]',EncuestaController::class.':get'); 
    $group->get('/delete',EncuestaController::class.':deleteAll');
    $group->get('/delete/{id}',EncuestaController::class.':deleteOne');
    $group->get('/update',EncuestaController::class.':updateAll');
    $group->get('/update/{id}',EncuestaController::class.':updateOne');
});
//estado
$app->group('/Estado', function (RouteCollectorProxy $group)
{
    $group->post('[/]',EstadoController::class.':addOne');
    $group->get('s[/{id}]',EstadoController::class.':getAll'); 
    $group->get('s[/{clave}/{valor}]',EstadoController::class.':get'); 
    $group->get('/delete',EstadoController::class.':deleteAll');
    $group->get('/delete/{id}',EstadoController::class.':deleteOne');
    $group->get('/update',EstadoController::class.':updateAll');
    $group->get('/update/{id}',EstadoController::class.':updateOne');
});
//log
$app->group('/Log', function (RouteCollectorProxy $group)
{
    $group->post('[/]',LogController::class.':addOne');
    $group->get('s[/{id}]',LogController::class.':getAll'); 
    $group->get('s[/{clave}/{valor}]',LogController::class.':get'); 
    $group->get('/delete',LogController::class.':deleteAll');
    $group->get('/delete/{id}',LogController::class.':deleteOne');
    $group->get('/update',LogController::class.':updateAll');
    $group->get('/update/{id}',LogController::class.':updateOne');
});
//mesa
$app->group('/Mesa', function (RouteCollectorProxy $group)
{
    $group->post('[/]',MesaController::class.':addOne');
    $group->get('s[/{id}]',MesaController::class.':getAll'); 
    $group->get('s[/{clave}/{valor}]',MesaController::class.':get'); 
    $group->get('/delete',MesaController::class.':deleteAll');
    $group->get('/delete/{id}',MesaController::class.':deleteOne');
    $group->get('/update',MesaController::class.':updateAll');
    $group->get('/update/{id}',MesaController::class.':updateOne');
});
//operacion
$app->group('/Operacion', function (RouteCollectorProxy $group)
{
    $group->post('[/]',OperacionController::class.':addOne');
    $group->get('s[/{id}]',OperacionController::class.':getAll'); 
    $group->get('s[/{clave}/{valor}]',OperacionController::class.':get'); 
    $group->get('/delete',OperacionController::class.':deleteAll');
    $group->get('/delete/{id}',OperacionController::class.':deleteOne');
    $group->get('/update',OperacionController::class.':updateAll');
    $group->get('/update/{id}',OperacionController::class.':updateOne');
});
//pedido
$app->group('/Pedido', function (RouteCollectorProxy $group)
{
    $group->post('[/]',PedidoController::class.':addOne');
    $group->get('s[/{id}]',PedidoController::class.':getAll'); 
    $group->get('s[/{clave}/{valor}]',PedidoController::class.':get'); 
    $group->get('/delete',PedidoController::class.':deleteAll');
    $group->get('/delete/{id}',PedidoController::class.':deleteOne');
    $group->get('/update',PedidoController::class.':updateAll');
    $group->get('/update/{id}',PedidoController::class.':updateOne');
});
//producto
$app->group('/Producto', function (RouteCollectorProxy $group)
{
    $group->post('[/]',ProductoController::class.':addOne');
    $group->get('s[/{id}]',ProductoController::class.':getAll'); 
    $group->get('s[/{clave}/{valor}]',ProductoController::class.':get'); 
    $group->get('/delete',ProductoController::class.':deleteAll');
    $group->get('/delete/{id}',ProductoController::class.':deleteOne');
    $group->get('/update',ProductoController::class.':updateAll');
    $group->get('/update/{id}',ProductoController::class.':updateOne');
});
//sector
$app->group('/Sector', function (RouteCollectorProxy $group)
{
  $group->post('[/]',SectorController::class.':addOne');
  $group->get('s[/{id}]',SectorController::class.':getAll'); 
  $group->get('s[/{clave}/{valor}]',SectorController::class.':get'); 
  $group->get('/delete',SectorController::class.':deleteAll');
  $group->get('/delete/{id}',SectorController::class.':deleteOne');
  $group->get('/update',SectorController::class.':updateAll');
  $group->get('/update/{id}',SectorController::class.':updateOne');
});
//ticket
$app->group('/Ticket', function (RouteCollectorProxy $group)
{
    $group->post('[/]',TicketController::class.':addOne');
    $group->get('s[/{id}]',TicketController::class.':getAll'); 
    $group->get('s[/{clave}/{valor}]',TicketController::class.':get'); 
    $group->get('/delete',TicketController::class.':deleteAll');
    $group->get('/delete/{id}',TicketController::class.':deleteOne');
    $group->get('/update',TicketController::class.':updateAll');
    $group->get('/update/{id}',TicketController::class.':updateOne');
});
//tipo empleado
$app->group('/TipoEmpleado', function (RouteCollectorProxy $group)
{
    $group->post('[/]',TipoEmpleadoController::class.':addOne');
    $group->get('s[/{id}]',TipoEmpleadoController::class.':getAll'); 
    $group->get('s[/{clave}/{valor}]',TipoEmpleadoController::class.':get'); 
    $group->get('/delete',TipoEmpleadoController::class.':deleteAll');
    $group->get('/delete/{id}',TipoEmpleadoController::class.':deleteOne');
    $group->get('/update',TipoEmpleadoController::class.':updateAll');
    $group->get('/update/{id}',TipoEmpleadoController::class.':updateOne');
});
//pruebas
$app->get('/hello/{name}', function (Request $request, Response $response, $args) {
    $name = $args['name'];
    $response->getBody()->write("Hello, $name");
    return $response;
});
//*/
/*$app->addRoutingMiddleware();
$errorMiddleware = $app->addErrorMiddleware(true, true, true);*/
$app->get('[/]', function (Request $request, Response $response, $args) {
    $response->getBody()->write("Hola mundo, estoy en slim");
    return $response;
});
$app->run();
