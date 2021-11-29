<?php
require __DIR__ . '/../vendor/autoload.php';
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;
use Fpdf\Fpdf;
//Configuracion base de datos
use Config\Database;
//Controladores 
use Controllers\ClienteController;
use Controllers\ConsultaController;
use Controllers\CSVController;
use Controllers\EncuestaController;
use Controllers\EstadoController;
use Controllers\LocalController;
use Controllers\LogController;
use Controllers\MesaController;
use Controllers\PedidoController;
use Controllers\ProductoController;
use Controllers\PruebaController;
use Controllers\SectorController;
use Controllers\TicketController;
use Controllers\UsuarioController;
//Enumerados
use Enums\Emodels;
use Enums\EtipoUsuario;
//Middlewares
use Middlewares\MDWVerificarRol;
use Middlewares\MDWGrabarLog; 
use Middlewares\MDWVerificarToken;
use Middlewares\MDWRequestParams;
use Middlewares\MDWConvertToPDF;
$database = new Database();
$app = AppFactory::create();
//$app->setBasePath('/herokucomanda/app');//localhost
$app->addBodyParsingMiddleware();
$app->addRoutingMiddleware();

// Entrar/Salir/Registrarse
$app->group('/Sing', function (RouteCollectorProxy $group) {
    $group->post('In/empleados', UsuarioController::class . ':singIn')//listo
    ->add(new MDWGrabarLog());
    $group->post('In/clientes', ClienteController::class . ':singIn');
    
    $group->post('Up/empleados', UsuarioController::class . ':singUp');//sin probar
    $group->post('Up/clientes', ClienteController::class . ':singUp');//sin probar
    
    $group->post('Out/empleados', UsuarioController::class . ':singOut');//sin uso - para logs
    $group->post('Out/clientes', ClienteController::class . ':singOut');//sin uso - para logs
});
//local
$app->group('/Local', function (RouteCollectorProxy $group) {
    $group->put('/cerrar', LocalController::class . ':close');
})->add(new MDWVerificarRol([EtipoUsuario::SOCIO]))
->add(new MDWVerificarToken());

//usuario 
$app->group('/Empleado', function (RouteCollectorProxy $group) {
    $group->get('s[/{clave}[/{valor}]]', UsuarioController::class . ':getAll')
    ->add(new MDWConvertToPDF('Usuarios'));
    
    $group->post('', UsuarioController::class . ':addOne')
    ->add(new MDWRequestParams(Emodels::EMPLEADO));

    $group->delete('/delete/{id}', UsuarioController::class . ':delete');

    $group->post('/{id}', UsuarioController::class . ':update');

})->add(new MDWVerificarRol([EtipoUsuario::SOCIO]))
->add(new MDWVerificarToken());
//producto
$app->group('/Producto', function (RouteCollectorProxy $group) {
    $group->get('s[/{clave}[/{valor}]]', ProductoController::class . ':getAll')
    ->add(new MDWConvertToPDF('Productos'));
    $group->post('[/]', ProductoController::class . ':addOne');
    $group->delete('/delete/{id}', ProductoController::class . ':delete');
    $group->post('/{id}', ProductoController::class . ':update');
})->add(new MDWVerificarToken());

//mesa
$app->group('/Mesa', function (RouteCollectorProxy $group) {
    $group->get('s[/{clave}[/{valor}]]', MesaController::class . ':getAll')
    ->add(new MDWConvertToPDF('Mesas'));
    $group->post('[/]', MesaController::class . ':addOne');
    $group->delete('/delete/{id}', MesaController::class . ':delete');
    $group->post('/{id}', MesaController::class . ':update');
    $group->put('/reservar',MesaController::class . ':reservar')
    ->add(new MDWVerificarRol([EtipoUsuario::CLIENTE]));
    $group->post('/Cerrar/{id}', MesaController::class . ':cerrar')
    ->add(new MDWVerificarRol([EtipoUsuario::MOZO,EtipoUsuario::SOCIO]));

    $group->get('/abrirTodas',MesaController::class . ':openAll')
    ->add(new MDWVerificarRol([EtipoUsuario::SOCIO]));
})->add(new MDWVerificarToken());

//pedido
$app->group('/Pedido', function (RouteCollectorProxy $group) {

    $group->get('s', PedidoController::class . ':getAll')
    ->add(new MDWConvertToPDF('Pedidos'));

    $group->get('/Listos',PedidoController::class . ':getReady')
    ->add(new MDWVerificarRol([EtipoUsuario::MOZO,EtipoUsuario::SOCIO]))
    ->add(new MDWConvertToPDF('Pedidos'));

    $group->post('[/]', PedidoController::class . ':addOne')
    ->add(new MDWVerificarRol([EtipoUsuario::CLIENTE,EtipoUsuario::SOCIO]));

    $group->post('/altaPedidos',PedidoController::class . ':addSeveral')
    ->add(new MDWVerificarRol([EtipoUsuario::CLIENTE]));

    $group->get('/{id}', PedidoController::class . ':getTime')
    ->add(new MDWVerificarRol([EtipoUsuario::CLIENTE,EtipoUsuario::SOCIO,EtipoUsuario::MOZO]));

    $group->post('/cambiarEstado/{id}',PedidoController::class . ':estadoSiguiente')
    ->add(new MDWVerificarRol([EtipoUsuario::BARTENDER,EtipoUsuario::CERVECERO,
    EtipoUsuario::COCINERO]));
    
    $group->post('/servir/{id}',PedidoController::class . ':servir')
    ->add(new MDWVerificarRol([EtipoUsuario::MOZO,EtipoUsuario::SOCIO]));

    $group->put('/pagar/{id}',PedidoController::class . ':pay')
    ->add(new MDWVerificarRol([EtipoUsuario::CLIENTE]));

    $group->delete('/delete/{id}', PedidoController::class . ':delete');

})->add(new MDWVerificarToken());
//clientes
$app->group('/Cliente', function (RouteCollectorProxy $group) {
    $group->get('s', ClienteController::class . ':getAll')
    ->add(new MDWConvertToPDF('Clientes'));
    $group->get('/PedirCuenta', ClienteController::class . ':pedirCuenta');
    $group->post('[/]', ClienteController::class . ':addOne');
    $group->delete('/delete/{id}', ClienteController::class . ':delete');
    $group->post('/{id}', ClienteController::class . ':update'); 
})->add(new MDWVerificarToken());

//carga
$app->group('/CSV', function (RouteCollectorProxy $group) {
    $group->post('/carga', CSVController::class . ':cargarArchivoCSV'); 
    $group->get('/descarga/{tabla}',CSVController::class . ':generarCSV');
});
//consultas
$app->group('/Consulta', function (RouteCollectorProxy $group) {
    $group->get('/mesaMasUsada', ConsultaController::class . ':mesaMasUsada'); 
    $group->get('/mesaMasFacturo', ConsultaController::class . ':mesaMasFacturo'); 
    $group->get('/mesaMayorImporte', ConsultaController::class . ':mesaMayorImporte'); 
});
//********** 2021 */
//❏ 1- Una moza toma el pedido de una:
//❏ Una milanesa a caballo
//❏ Dos hamburguesas de garbanzo
//❏ Una corona
//❏ Un Daikiri
//❏ 2- El mozo saca una foto de la mesa y lo relaciona con el pedido.
//❏ 3- Cada empleado responsable de cada producto del pedido , debe:
//❏ Listar todos los productos pendientes de este tipo de empleado.
//❏ Debe cambiar el estado a “en preparación” y agregarle el tiempo de preparación.
//❏ 4- El cliente ingresa el código de la mesa junto con el número de pedido y ve el tiempo de
//demora de su pedido.
//❏ 5- Alguno de los socios pide el listado de pedidos y el tiempo de demora de ese pedido.
//❏ 6- Cada empleado responsable de cada producto del pedido, debe:
//❏ Listar todos los productos pendientes de este tipo de empleado
//❏ Debe cambiar el estado a “listo para servir” .
//❏ 7- La moza se fija los pedidos que están listos para servir , cambia el estado de la mesa,
//❏ 8- Alguno de los socios pide el listado de las mesas y sus estados .
//❏ 9- La moza cobra la cuenta.
//❏ 10- Alguno de los socios cierra la mesa.
//❏ 11- El cliente ingresa el código de mesa y el del pedido junto con los datos de la encuesta.
//❏ 12- Alguno de los socios pide los mejores comentarios
//❏ 13- Alguno de los socios pide la mesa más usada.
///////////*******************SIN PROBAR ////////////////////////////////////////////////////////////*/
//encuesta
$app->group('/Encuesta', function (RouteCollectorProxy $group) {
    $group->post('[/]', EncuestaController::class . ':addOne');
    $group->get('s[/{clave}[/{valor}]]', EncuestaController::class . ':getAll');
});
//estado
/*$app->group('/Estado', function (RouteCollectorProxy $group) {
    $group->get('s[/{clave}[/{valor}]]', EstadoController::class . ':getAll');
    $group->post('[/]', EstadoController::class . ':addOne');
    $group->delete('/delete/{id}', EstadoController::class . ':delete');
    $group->post('/{id}', EstadoController::class . ':update');
});*/
//log
$app->group('/Log', function (RouteCollectorProxy $group) {
    $group->get('s[/{clave}[/{valor}]]', LogController::class . ':getAll');
    $group->post('[/]', LogController::class . ':addOne');
    $group->delete('/delete/{id}', LogController::class . ':delete');
    $group->post('/{id}', LogController::class . ':update');
});
//sector
/*
$app->group('/Sector', function (RouteCollectorProxy $group) {
    $group->get('s[/{clave}[/{valor}]]', SectorController::class . ':getAll');
    $group->post('[/]', SectorController::class . ':addOne');
    $group->delete('/delete/{id}', SectorController::class . ':delete');
    $group->post('/{id}', SectorController::class . ':update');
});*/
//ticket
$app->group('/Ticket', function (RouteCollectorProxy $group) {
    $group->get('s[/{clave}[/{valor}]]', TicketController::class . ':getAll')
    ->add(new MDWConvertToPDF('Tickets'));
    $group->post('[/]', TicketController::class . ':addOne');
    $group->delete('/delete/{id}', TicketController::class . ':delete');
    $group->post('/{id}', TicketController::class . ':update');
})->add(new MDWVerificarToken());
//imagen
$app->group('/Photo',function(RouteCollectorProxy $group) {
    $group->post('/ticket[/]',TicketController::class . ':addPhoto')
    ->add(new MDWVerificarRol([EtipoUsuario::MOZO]));
})->add(new MDWVerificarToken());

$errorMiddleware = $app->addErrorMiddleware(true, true, true);
//$returnJson = $app->add(new MDWReturn());
$app->run();

