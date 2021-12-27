<?php
require('fpdf/fpdf.php');

include_once('database/connectdb.php');

$id = $_GET['id'];

$select = $inventory->prepare("select * from invoice where id  = $id");
$select->execute();

$row = $select->fetch(PDO::FETCH_OBJ);


$logo = "storage/logo/1.png";
$invoice_id = 12;
// create pdf object
$pdf = new FPDF('P', 'mm', 'A4');

// add new page

$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);

if($logo){
    $pdf->Image($logo,20,10,30);
}

$pdf->Cell(70);
$pdf->SetFont('Arial', 'B', 13);
$pdf->Cell(110,20,'INVOICE',0,0,'R');

$pdf->Ln();
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(10);
$pdf->Cell(100,5,'Address: Shreeji Niwas Jasani park main road.',0,0,'');
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(70,5,'Invoice: '.$row->id,0,0,'R');
$pdf->Ln();
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(10);
$pdf->Cell(100,5,'Phone Number: 8238151241',0,0,'');
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(70,5,'Date: '.date('d-M-Y',strtotime($row->order_date)),0,0,'R');
$pdf->Ln();
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(10);
$pdf->Cell(100,5,'E-mail Address: chhatbarmitesh123@gmail.com',0,1,'');
$pdf->Cell(10);
$pdf->Cell(100,5,'GST NO:  '.$row->gst_no,0,1,'');

//Line(x1,y1,x2,y2) 
$pdf->Line(18,55,190,55);
$pdf->Line(18,56,190,56);

$pdf->Ln(10); // next cell after 10 height


$pdf->SetFont('Courier', 'BI', 14);
$pdf->Cell(10);
$pdf->Cell(25,10,'Bill To:',0,0,'');
$pdf->Cell(50,10,$row->client_name,0,1,'');

$pdf->Ln();
$pdf->Cell(10);
$pdf->SetFont('Arial', 'I', 10);
$pdf->SetFillColor(220,220,220);
$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(170,8,' Order Details',1,0,'L',true);
$pdf->Ln();
$pdf->Cell(10);
$y = $pdf->GetY();
$pdf->MultiCell(85,10, ' Orer Id '.$row->id."\n".' Payment Type: '.$row->payment_type,'LRB',1,0,'C',true);
$x = $pdf->GetX();
$pdf->SetXY($x+95,$y);
$pdf->MultiCell(85,10,' Payment Status: '.$row->payment_status."\n".' Address: '.$row->client_address,'LRB',1,0,'C',true);

$pdf->Cell(50,15,'',0,1,'');
$pdf->Cell(10);
$pdf->Cell(170,8,' Item of Invoice',1,0,'L',true);
$pdf->Ln();

$pdf->SetFont('Arial', 'B', 10);
// $pdf->SetFillColor(220,220,220);
$pdf->Cell(10);
$pdf->Cell(80,8,'PRODUCT',1,0,'C',false);
$pdf->Cell(20,8,'QTY',1,0,'C',false);
$pdf->Cell(30,8,'PRICE',1,0,'C',false);
$pdf->Cell(40,8,'TOTAL',1,0,'C',false);

$select = $inventory->prepare("select * from invoice_details where invoice_id = $id");
$select->execute();

while($item = $select->fetch(PDO::FETCH_OBJ)){
    $pdf->SetFont('Arial', '', 10);
    $pdf->Ln();
    $pdf->Cell(10);
    $pdf->Cell(80,8,$item->product_name,1,0);
    $pdf->Cell(20,8,$item->quantity,1,0,'C');
    $pdf->Cell(30,8,$item->price,1,0,'C');
    $pdf->Cell(40,8,$item->price * $item->quantity,1,0,'C');
} 




$pdf->SetFillColor(250,250,210);
$pdf->SetTextColor(0, 0, 0);
$pdf->Ln();
$pdf->Cell(10);
$pdf->SetFont('Arial', 'B', 10);
$pdf->SetFillColor(240,248,255);
$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(100,8,'Bank Details: HDFC BANK',1,0,'C',true);
$pdf->SetFont('Arial', '', 10);
$pdf->SetFillColor(250,250,210);
$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(30,8,'SubTotal',1,0,'C',true);
$pdf->Cell(40,8,$row->subtotal,1,0,'C',true);


$pdf->Ln();
$pdf->Cell(10);
$pdf->SetFont('Arial', 'B', 10);
$pdf->SetFillColor(240,248,255);
$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(100,8,'IFSC CODE: HDFC0001637        A/c NO: 50200021106573 
',1,0,'C',true);
$pdf->SetFont('Arial', '', 10);
$pdf->SetFillColor(250,250,210);
$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(30,8,'IGST (18%)',1,0,'C',true);
$pdf->Cell(40,8,$row->gst,1,0,'C',true);


$pdf->Ln();
$pdf->Cell(10);
$pdf->SetFont('Arial', 'B', 8.5);
$pdf->SetFillColor(240,248,255);
$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(100,8,' 1) Interest will be charged 2% if payment is not made within days.
',1,0,'L',true);
$pdf->SetFont('Arial', '', 10);
$pdf->SetFillColor(250,250,210);
$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(30,8,'Discount',1,0,'C',true);
$pdf->Cell(40,8,$row->discount,1,0,'C',true);

$pdf->SetFillColor(238,232,170);
$pdf->SetTextColor(0, 0, 0);
// $pdf->SetFont('Arial', 'B', 12);
$pdf->Ln();
$pdf->Cell(10);
$pdf->SetFont('Arial', 'B', 8.5);
$pdf->SetFillColor(240,248,255);
$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(100,8,' 2) Goods once sold will not be taken back.',1,0,'L',true);
$pdf->SetFont('Arial', 'B', 10);
$pdf->SetFillColor(238,232,170);
$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(30,8,'Grand Total',1,0,'C',true);
$pdf->Cell(40,8,$row->total.' RS ',1,0,'C',true);

$pdf->SetFillColor(250,250,210);
$pdf->SetTextColor(0, 0, 0);
$pdf->Ln();
$pdf->Cell(10);
$pdf->SetFont('Arial', 'B', 8.5);
$pdf->SetFillColor(240,248,255);
$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(100,8,' 3) All subject to RAJKOT jurisdiction.',1,0,'L',true);
$pdf->SetFont('Arial', '', 10);
$pdf->SetFillColor(250,250,210);
$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(30,8,'Paid',1,0,'C',true);
$pdf->Cell(40,8,$row->paid,1,0,'C',true);

$pdf->Ln();
$pdf->Cell(10);
$pdf->SetFont('Arial', 'B', 8);
$pdf->SetFillColor(240,248,255);
$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(100,8,' 4) Please provide your GSTIN No. after written we are not responsible.',1,0,'L',true);
$pdf->SetFont('Arial', '', 10);
$pdf->SetFillColor(250,250,210);
$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(30,8,'Due',1,0,'C',true);
$pdf->Cell(40,8,$row->due,1,0,'C',true);


$pdf->Ln();
$pdf->Cell(10);
$pdf->SetFont('Arial', 'B', 10);
$pdf->SetFillColor(240,248,255);
$pdf->SetTextColor(0, 0, 0);
$y = $pdf->GetY();
$pdf->MultiCell(85,10, ' Customers Approval '."\n".' Name / Signature: ','LRB',1,0,'C',true);
$x = $pdf->GetX();
$pdf->SetXY($x+95,$y);
$pdf->MultiCell(85,10,' For CM Company '."\n".' Authorized Signatory:','LRB',1,0,'C',true);


$pdf->SetFont('Arial', '', 7);
$pdf->Cell(10);
$pdf->Cell(170,8,'AS PER RAJKOT JURIDICTION',0,0,'C');



// output the result.

$pdf->output();

?>