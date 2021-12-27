<?php
require('fpdf/fpdf.php');

include_once('database/connectdb.php');

$id = $_GET['id'];

$select = $inventory->prepare("select * from invoice where id  = $id");
$select->execute();

$row = $select->fetch(PDO::FETCH_OBJ);


$logo = "storage/logo/1.png";
// create pdf object
$pdf = new FPDF('P', 'mm', array(80,200));

// add new page

$pdf->AddPage();

$pdf->SetFont('Arial', 'B', 16);

if($logo){
    $pdf->Image($logo,27,10,30);
}
$pdf->Ln(15);
// $pdf->Cell(70);
$pdf->SetFont('Arial', 'B', 13);
$pdf->Cell(45,15,'::INVOICE::',0,0,'R');

$pdf->Ln();
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(0,5,'Address: Shreeji Niwas.',0,0,'');

$pdf->Ln();
$pdf->Cell(0,5,'Phone: 8238151241',0,0,'');
$pdf->Ln();
$pdf->Cell(0,5,'E-mail: chhatbarmitesh123@gmail.com',0,1,'');
$pdf->Cell(5,5,'GST NO:  234772DFSA230D',0,1,'');

$pdf->Line(7,38,72,38);
$pdf->Line(7,60,72,60);

$pdf->Ln(1); // next cell after 10 height


$pdf->SetFont('Courier', 'BI', 8);

$pdf->Cell(20,4,'Bill To:',0,0,'');
$pdf->Cell(30,4,$row->client_name,0,1,'');

$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(20,5,'Invoice Id: ',0,0,'');
$pdf->Cell(30,5,$row->id,0,1,'');

$pdf->Cell(20,5,'Order Date: ',0,0,'');
$pdf->Cell(30,5,date('d-M-Y',strtotime($row->order_date)),0,1,'');
$pdf->Ln();
$pdf->SetFont('Arial', 'B', 6);
// $pdf->SetFillColor(220,220,220);
$pdf->Cell(34,5,'PRODUCT',1,0,'C');
$pdf->Cell(11,5,'QTY',1,0,'C');
$pdf->Cell(8,5,'PRICE',1,0,'C');
$pdf->Cell(12,5,'TOTAL',1,0,'C');


$select = $inventory->prepare("select * from invoice_details where invoice_id = $id");
$select->execute();
$pdf->SetX(7);
while($item = $select->fetch(PDO::FETCH_OBJ)){
    $pdf->SetFont('Arial', '', 6);
    $pdf->Ln();
    $pdf->Cell(34,5,$item->product_name,1,0);
    $pdf->Cell(11,5,$item->quantity,1,0,'C');
    $pdf->Cell(8,5,$item->price,1,0,'C');
    $pdf->Cell(12,5,$item->price * $item->quantity,1,0,'C');
} 
// //////////////////

//  Product Table

////////////////////
$pdf->Ln();
$pdf->SetX(7);
$pdf->SetFont('Courier', 'B', 8);
$pdf->Cell(20,5,'',0,0,'');
$pdf->Cell(25,5,'SUBTOTAL',1,0,'C');
$pdf->Cell(23,5,$row->subtotal,1,0,'C');
$pdf->Ln();
$pdf->SetX(7);
$pdf->SetFont('Courier', 'B', 8);
$pdf->Cell(20,5,'',0,0,'');
$pdf->Cell(25,5,'IGST',1,0,'C');
$pdf->Cell(23,5,$row->gst,1,0,'C');
$pdf->Ln();
$pdf->SetX(7);
$pdf->SetFont('Courier', 'B', 8);
$pdf->Cell(20,5,'',0,0,'');
$pdf->Cell(25,5,'DISCOUNT',1,0,'C');
$pdf->Cell(23,5,$row->discount,1,0,'C');
$pdf->Ln();
$pdf->SetX(7);
$pdf->SetFont('Courier', 'B', 8);
$pdf->Cell(20,5,'',0,0,'');
$pdf->Cell(25,5,'TOTAL',1,0,'C');
$pdf->Cell(23,5,$row->total. ' RS',1,0,'C');
$pdf->Ln();
$pdf->SetX(7);
$pdf->SetFont('Courier', 'B', 8);
$pdf->Cell(20,5,'',0,0,'');
$pdf->Cell(25,5,'PAID',1,0,'C');
$pdf->Cell(23,5,$row->paid,1,0,'C');
$pdf->Ln();
$pdf->SetX(7);
$pdf->SetFont('Courier', 'B', 8);
$pdf->Cell(20,5,'',0,0,'');
$pdf->Cell(25,5,'DUE',1,0,'C');
$pdf->Cell(23,5,$row->due,1,0,'C');

// output the result.

$pdf->output();

?>