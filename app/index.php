<?php
require __DIR__ . '/../vendor/autoload.php';
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;

//Configuracion base de datos
use Config\Database;
//Controladores 
use Controllers\ClienteController;
use Controllers\EncuestaController;
use Controllers\EstadoController;
use Controllers\LogController;
use Controllers\MesaController;
use Controllers\PedidoController;
use Controllers\ProductoController;
use Controllers\SectorController;
use Controllers\TicketController;
use Controllers\UsuarioController;
//Enumerados
use Enums\Emodels;
use Enums\EtipoUsuario;
//Middlewares
use Middlewares\MDRVerificarRol;
use Middlewares\MDWVerificarToken;
use Middlewares\MDWRequestParams;
$database = new Database();
$app = AppFactory::create();
//$app->setBasePath('/herokucomanda/app');//localhost
//$app->setBasePath('/app/app');//heroku -- no poner setBasePath porque no funciona

$app->addBodyParsingMiddleware();
$app->addRoutingMiddleware();

// Entrar/Salir/Registrarse
$app->group('/Sing', function (RouteCollectorProxy $group) {
    $group->post('In/empleados', UsuarioController::class . ':singIn');//listo
    $group->post('In/clientes', ClienteController::class . ':singIn');//sin probar
    
    $group->post('Up/empleados', UsuarioController::class . ':singUp');//sin probar
    $group->post('Up/clientes', ClienteController::class . ':singUp');//sin probar
    
    $group->post('Out/empleados', UsuarioController::class . ':singOut');//sin uso - para logs
    $group->post('Out/clientes', ClienteController::class . ':singOut');//sin uso - para logs
});

//usuario 
$app->group('/Empleado', function (RouteCollectorProxy $group) {
    $group->get('s[/{clave}[/{valor}]]', UsuarioController::class . ':getAll');
    
    $group->post('', UsuarioController::class . ':addOne')
    ->add(new MDWRequestParams(Emodels::EMPLEADO));

    $group->delete('/delete/{id}', UsuarioController::class . ':delete');
    $group->post('/{id}', UsuarioController::class . ':update');

})->add(new MDRVerificarRol([EtipoUsuario::SOCIO]))
->add(new MDWVerificarToken());

//producto
$app->group('/Producto', function (RouteCollectorProxy $group) {
    $group->get('s[/{clave}[/{valor}]]', ProductoController::class . ':getAll');
    $group->post('[/]', ProductoController::class . ':addOne');
    $group->delete('/delete/{id}', ProductoController::class . ':delete');
    $group->post('/{id}', ProductoController::class . ':update');
})->add(new MDWVerificarToken());

//mesa
$app->group('/Mesa', function (RouteCollectorProxy $group) {
    $group->get('s[/{clave}[/{valor}]]', MesaController::class . ':getAll');
    $group->post('[/]', MesaController::class . ':addOne');
    $group->delete('/delete/{id}', MesaController::class . ':delete');
    $group->post('/{id}', MesaController::class . ':update');
})->add(new MDWVerificarToken());
//pedido
$app->group('/Pedido', function (RouteCollectorProxy $group) {
    $group->get('s[/{clave}[/{valor}]]', PedidoController::class . ':getAll');
    $group->post('[/]', PedidoController::class . ':addOne');
    $group->delete('/delete/{id}', PedidoController::class . ':delete');
    $group->post('/{id}', PedidoController::class . ':update');
})->add(new MDWVerificarToken());
//clientes
$app->group('/Cliente', function (RouteCollectorProxy $group) {
    $group->get('s[/{clave}[/{valor}]]', ClienteController::class . ':getAll');
    $group->post('[/]', ClienteController::class . ':addOne');
    $group->delete('/delete/{id}', ClienteController::class . ':delete');
    $group->post('/{id}', ClienteController::class . ':update');
});


///////////*******************SIN PROBAR ////////////////////////////////////////////////////////////*/
//encuesta
$app->group('/Encuesta', function (RouteCollectorProxy $group) {
    $group->get('s[/{clave}[/{valor}]]', EncuestaController::class . ':getAll');
    $group->post('[/]', EncuestaController::class . ':addOne');
    $group->delete('/delete/{id}', EncuestaController::class . ':delete');
    $group->post('/{id}', EncuestaController::class . ':update');
});
//estado
$app->group('/Estado', function (RouteCollectorProxy $group) {
    $group->get('s[/{clave}[/{valor}]]', EstadoController::class . ':getAll');
    $group->post('[/]', EstadoController::class . ':addOne');
    $group->delete('/delete/{id}', EstadoController::class . ':delete');
    $group->post('/{id}', EstadoController::class . ':update');
});
//log
$app->group('/Log', function (RouteCollectorProxy $group) {
    $group->get('s[/{clave}[/{valor}]]', LogController::class . ':getAll');
    $group->post('[/]', LogController::class . ':addOne');
    $group->delete('/delete/{id}', LogController::class . ':delete');
    $group->post('/{id}', LogController::class . ':update');
});
//sector
$app->group('/Sector', function (RouteCollectorProxy $group) {
    $group->get('s[/{clave}[/{valor}]]', SectorController::class . ':getAll');
    $group->post('[/]', SectorController::class . ':addOne');
    $group->delete('/delete/{id}', SectorController::class . ':delete');
    $group->post('/{id}', SectorController::class . ':update');
});
//ticket
$app->group('/Ticket', function (RouteCollectorProxy $group) {
    $group->get('s[/{clave}[/{valor}]]', TicketController::class . ':getAll');
    $group->post('[/]', TicketController::class . ':addOne');
    $group->delete('/delete/{id}', TicketController::class . ':delete');
    $group->post('/{id}', TicketController::class . ':update');
});
$errorMiddleware = $app->addErrorMiddleware(true, true, true);
$app->run();

