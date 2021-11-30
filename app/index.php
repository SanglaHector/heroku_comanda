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
use Controllers\LocalController;
use Controllers\LogController;
use Controllers\MesaController;
use Controllers\PedidoController;
use Controllers\ProductoController;
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
    
    $group->put('Out/empleados', UsuarioController::class . ':singOut')
    ->add(new MDWVerificarRol([EtipoUsuario::MOZO,EtipoUsuario::CERVECERO,EtipoUsuario::COCINERO,EtipoUsuario::SOCIO,EtipoUsuario::BARTENDER]))
    ->add(new MDWVerificarToken());
    $group->put('Out/clientes', ClienteController::class . ':singOut')
    ->add(new MDWVerificarRol([EtipoUsuario::CLIENTE]))
    ->add(new MDWVerificarToken());
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
    //mesas
    $group->get('/mesaMasUsada', ConsultaController::class . ':mesaMasUsada'); 
    $group->get('/mesaMasFacturo', ConsultaController::class . ':mesaMasFacturo'); 
    $group->get('/mesaMayorImporte', ConsultaController::class . ':mesaMayorImporte'); 
    $group->get('/mesaMayorImporte/{fDesde}/{fHasta}', ConsultaController::class . ':masFacturoEntreFechas'); 
    //pedidos
    $group->get('/productoMasVendido', ConsultaController::class . ':productoMasVendido'); 
    $group->get('/fueraDeTiempo', ConsultaController::class . ':pedidosFueraDeTiempo'); 
    $group->get('/cancelados', ConsultaController::class . ':pedidosCancelados'); 
    //empleados
    $group->get('/puntoA', ConsultaController::class . ':ingresos'); 
    $group->get('/puntoB/{sector}', ConsultaController::class . ':cantidadOperaciones'); 
    $group->get('/puntoC/{sector}', ConsultaController::class . ':cantidadOperacionesPorUsuario'); 
    $group->get('/puntoD', ConsultaController::class . ':operacionesPorUsuario'); 
})->add(new MDWVerificarToken());;
//encuesta
$app->group('/Encuesta', function (RouteCollectorProxy $group) {
    $group->post('[/]', EncuestaController::class . ':addOne');
    $group->get('s[/{clave}[/{valor}]]', EncuestaController::class . ':getAll');
});

//log
$app->group('/Log', function (RouteCollectorProxy $group) {
    $group->get('s[/{clave}[/{valor}]]', LogController::class . ':getAll');
    $group->post('[/]', LogController::class . ':addOne');
    $group->delete('/delete/{id}', LogController::class . ':delete');
    $group->post('/{id}', LogController::class . ':update');
});

//ticket
$app->group('/Ticket', function (RouteCollectorProxy $group) {
    $group->get('s[/{clave}[/{valor}]]', TicketController::class . ':getAll')
    ->add(new MDWConvertToPDF('Tickets'));
    $group->post('[/]', TicketController::class . ':addOne');
    $group->delete('/delete/{id}', TicketController::class . ':delete');
    $group->delete('/cancelar/{id}', TicketController::class . ':cancelar');
    $group->post('/{id}', TicketController::class . ':update');
})->add(new MDWVerificarToken());
//imagen
$app->group('/Photo',function(RouteCollectorProxy $group) {
    $group->post('/ticket[/]',TicketController::class . ':addPhoto')
    ->add(new MDWVerificarRol([EtipoUsuario::MOZO]));
})->add(new MDWVerificarToken());

$errorMiddleware = $app->addErrorMiddleware(true, true, true);
$app->run();

