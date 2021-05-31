<?php
require __DIR__ . './vendor/autoload.php';

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
use Enums\Eestado;
use Enums\EtipoUsuario;
//Middlewares
use Middlewares\MDRVerificarRol;
use Middlewares\MDWVerificarToken;

$database = new Database();
$app = AppFactory::create();
//$app->setBasePath('/herokucomanda/app');//localhost
$app->setBasePath('/app/app');//heroku
//prueba
$app->get('[/]', function (Request $request, Response $response, $args) {
    $response->getBody()->write("Hola mundo, estoy en heroku");
    return $response;
});

// Entrar/Salir/Registrarse
$app->group('/Sing', function (RouteCollectorProxy $group) {
    $group->post('In/empleados', UsuarioController::class . ':singIn');//listo
    $group->post('In/clientes', ClienteController::class . ':singIn');

    $group->post('Up/empleados', UsuarioController::class . ':singUp');
    $group->post('Up/clientes', ClienteController::class . ':singUp');

    $group->post('Out/empleados', UsuarioController::class . ':singOut');//sin uso
    $group->post('Out/clientes', ClienteController::class . ':singOut');//sin uso
});

//usuario 
$app->group('/Usuario', function (RouteCollectorProxy $group) {
    $group->get('s[/{clave}[/{valor}]]', UsuarioController::class . ':getAll');
    $group->post('', UsuarioController::class . ':addOne');
    $group->delete('/delete/{id}', UsuarioController::class . ':delete');
    $group->post('/{id}', UsuarioController::class . ':update');

})  ->add(new MDRVerificarRol([EtipoUsuario::SOCIO]))
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
$app->group('/Clientes', function (RouteCollectorProxy $group) {
    $group->post('[/]', ClienteController::class . ':addOne');
    $group->get('s[/{id}]', ClienteController::class . ':getAll');
});
//encuesta
$app->group('/Encuesta', function (RouteCollectorProxy $group) {
    $group->post('[/]', EncuestaController::class . ':addOne');
    $group->get('s[/{id}]', EncuestaController::class . ':getAll');
});
//estado
$app->group('/Estado', function (RouteCollectorProxy $group) {
    $group->post('[/]', EstadoController::class . ':addOne');
    $group->get('s[/{id}]', EstadoController::class . ':getAll');
});
//log
$app->group('/Log', function (RouteCollectorProxy $group) {
    $group->post('[/]', LogController::class . ':addOne');
    $group->get('s[/{id}]', LogController::class . ':getAll');
});
//sector
$app->group('/Sector', function (RouteCollectorProxy $group) {
    $group->post('[/]', SectorController::class . ':addOne');
    $group->get('s[/{id}]', SectorController::class . ':getAll');
});
//ticket
$app->group('/Ticket', function (RouteCollectorProxy $group) {
    $group->post('[/]', TicketController::class . ':addOne');
    $group->get('s[/{id}]', TicketController::class . ':getAll');
});

$app->addRoutingMiddleware();
$errorMiddleware = $app->addErrorMiddleware(true, true, true);
$app->run();
