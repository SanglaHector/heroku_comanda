<?php
namespace Components;

use Enums\Eestado;
use Enums\EtipoUsuario;
use Components\InterClass;
use Fpdf\Fpdf;

class PDFGenerator
{
    static function UsuariosToPDF($models)
    {
        $pdf = new Fpdf();
        $pdf->AddPage();
        $pdf->SetFont('Arial','B',12);
        //cabecera
        $pdf->Cell(10,10,'Id',1);
        $pdf->Cell(30,10,'Tipo',1);
        $pdf->Cell(40,10,'Estado',1);
        $pdf->Cell(80,10,'Mail',1);
        $pdf->Cell(40,10,'Apellido',1,1);
        //filas
        foreach ($models as $model ) {
            //busco tipo
            $tipo = EtipoUsuario::GetDescription($model->tipo_empleado);
            //busco estado
            $estado = Eestado::GetDescription($model->id_estado);
            $pdf->Cell(10,10, $model->id);
            $pdf->Cell(30,10, $tipo);
            $pdf->Cell(40,10, $estado);
            $pdf->Cell(80,10, $model->email);
            $pdf->Cell(40,10, $model->apellido,0,1);
        }
        $pdf->Output();
    }
    //sin probar
    static function ClienteToPDF($models)
    {
        $pdf = new Fpdf();
        $pdf->AddPage();
        $pdf->SetFont('Arial','B',12);
        //cabecera
        $pdf->Cell(10,10,'Id',1);
        $pdf->Cell(80,10,'Mail',1);
        $pdf->Cell(40,10,'Estado',1,1);
        //filas
        foreach ($models as $model ) {
            //busco estado
            $estado = Eestado::GetDescription($model->id_estado);
            $pdf->Cell(10,10, $model->id);
            $pdf->Cell(80,10, $model->email);
            $pdf->Cell(40,10, $estado,0,1);
        }
        $pdf->Output();
    }
    static function MesasToPDF($models)
    {
        $pdf = new Fpdf();
        $pdf->AddPage();
        $pdf->SetFont('Arial','B',12);
        //cabecera
        $pdf->Cell(10,10,'Id',1);
        $pdf->Cell(12,10,'Mesa',1);
        $pdf->Cell(80,10,'Mozo',1);
        $pdf->Cell(65,10,'Cliente',1);
        $pdf->Cell(40,10,'Estado',1,1);
        //filas
        foreach ($models as $model ) {
            //busco estado
            $estado = Eestado::GetDescription($model->id_estado);
            $mozo = InterClass::retornarUsuarioById($model->id_empleado);
            $cliente = InterClass::retornarClienteById($model->id_cliente);
            if(is_null($cliente))
            {
                $nombre = "Sin cliente";
            }else
            {
                $nombre = $cliente->email;
            }
            $pdf->Cell(10,10, $model->id);
            $pdf->Cell(12,10, $model->numero);
            $pdf->Cell(80,10, $mozo->apellido);
            $pdf->Cell(65,10, $nombre);
            $pdf->Cell(40,10, $estado,0,1);
        }
        $pdf->Output();
    }
    static function PedidosToPDF($models)
    {
        $pdf = new Fpdf();
        $pdf->AddPage();
        $pdf->SetFont('Arial','B',12);
        //cabecera
        $pdf->Cell(8,10,'Id',1);
        $pdf->Cell(65,10,'Producto',1);
        $pdf->Cell(15,10,'Cant',1);
        $pdf->Cell(55,10,'Estado',1);
        $pdf->Cell(30,10,'Tiempo Est.',1);
        $pdf->Cell(30,10,'Tiempo Final',1,1);
        //filas
        foreach ($models as $model ) {
            //busco estado
            $estado = Eestado::GetDescription($model->id_estado);
            $producto = InterClass::retornarProducto($model->id_producto);
            if(is_null($model->hora_final))
            {
                $hora_final = '00:00:00';
            }else
            {
                $hora_final = $model->hora_final;
            }
            $pdf->Cell(8,10, $model->id);
            $pdf->Cell(65,10, $producto->nombre);
            $pdf->Cell(15,10, $model->cantidad);
            $pdf->Cell(55,10, $estado);
            $pdf->Cell(30,10, $model->hora_estimada);
            $pdf->Cell(30,10, $hora_final,0,1);
        }
        $pdf->Output();
    }
    static function ProductosToPDF($models)
    {
        $pdf = new Fpdf();
        $pdf->AddPage();
        $pdf->SetFont('Arial','B',12);
        $pdf->SetFillColor(0,0,300);
       // $pdf->Image('logo.jpg',10,10,-300);
        //cabecera
        $pdf->Cell(10,10,'Id',1,0,'C',1);
        $pdf->Cell(80,10,'Sector',1,0,'C',1);
        $pdf->Cell(60,10,'Nombre',1,0,'C',1);
        $pdf->Cell(25,10,'Stock',1,0,'C',1);
        $pdf->Cell(30,10,'Precio',1,1,'C',1);
        //filas
        foreach ($models as $model ) {
            //busco sector
            $sector = InterClass::retornarSector($model->id_sector);
            $precio = '$'.$model->precio;
            $pdf->Cell(10,10, $model->id,0,0,'C');
            $pdf->Cell(80,10, $sector->nombre,0,0,'C');
            $pdf->Cell(60,10, $model->nombre,0,0,'C');
            $pdf->Cell(25,10, $model->stock,0,0,'C');
            $pdf->Cell(30,10, $precio,0,1,'L');
        }
        //$pdf->Output('D','productos.PDF');
        $pdf->Output();
    }
    static function TicketsToPDF($models)
    {
        $pdf = new Fpdf();
        $pdf->AddPage();
        $pdf->SetFont('Arial','B',12);
        //cabecera
        $pdf->Cell(10,10,'Id',1,0,'C');
        $pdf->Cell(15,10,'Mesa',1,0,'C');
        $pdf->Cell(20,10,'Id Mesa',1,0,'C');
        $pdf->Cell(80,10,'Precio total',1,1,'C');
        //filas
        foreach ($models as $model ) {
            $mesa = InterClass::returnMesaByTicket($model);
            if(is_null($model->precio_total))
            {
                $precioTotal = '$0,00';
            }else
            {
                $precioTotal = '$'.$model->precio_total;
            }
            $pdf->Cell(10,10, $model->id,0,0,'C');
            $pdf->Cell(15,10, $mesa->numero,0,0,'C');
            $pdf->Cell(20,10, $model->id_mesa,0,0,'C');
            $pdf->Cell(80,10, $precioTotal,0,1,'R');
        }
        $pdf->Output();
    }
    /*
    static function EncuestasToPDF($models)
    {

    }
    */
}