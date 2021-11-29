<?php

namespace Middlewares;

use Models\Usuario;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response as ResponseMW;
use Components\PDFGenerator;

class MDWConvertToPDF 
{
    public $listado;

    public function __construct($listado)
    {
        $this->listado = $listado;
    }

    public function __invoke(Request $request, RequestHandler $handler) : ResponseMW
    {
        //Invoco al siguiente MD
        $response = $handler->handle($request);
        //obtengo respuesta del MD
        $contenidoAPI = $response->getBody();
        //trato el contenidoAPI 
        $contenidoAPI = json_decode($contenidoAPI);
        //genero nueva respuesta
        $data = $contenidoAPI->data;
        if(is_array($data))
        {
            switch($this->listado)
            {
                case 'Clientes':
                    PDFGenerator::ClienteToPDF($data);
                    break;
                case 'Mesas':
                    PDFGenerator::MesasToPDF($data);
                    break;
                case 'Pedidos':
                    PDFGenerator::PedidosToPDF($data);
                    break;
                case 'Productos':
                    PDFGenerator::ProductosToPDF($data);
                case 'Tickets':
                    PDFGenerator::TicketsToPDF($data);
                    break;
                case 'Usuarios':
                    PDFGenerator::UsuariosToPDF($data);
                    break;
                default: 
                    break;  
            }
        }
        $response = new ResponseMW();
        $response->getBody()->write(json_encode($contenidoAPI));
        return $response;
    }
}