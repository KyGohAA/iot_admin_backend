<?php

namespace App\Setia;

use App\Setting;
use App\Language;

use Codedge\Fpdf\Fpdf\Fpdf as Fpdf;

class ARInvoicePdf extends Fpdf
{
    function Header()
    {
        $this->SetFont('arial','B',14);
        $this->Cell(0,6, $this->setting->convert_encoding($this->company['name']),0,1,'C');
        // $this->SetFont('arial','',9);
        // $this->Cell(0,4, $this->setting->convert_encoding('KC 0030485-V'),0,1,'C');
        $this->SetFont('arial','',10);
        $this->SetX(60);
        $this->MultiCell(100,4, $this->setting->convert_encoding($this->company->get_address()),0,'C');
        $this->Cell(0,4, 'Website : '.$this->company->website,0,1,'C');
        $this->Cell(0,4, 'Tel: '.$this->company->tel.', Fax: '.$this->company->mobile,0,1,'C');

        // document title
        $this->Ln(5);
        $this->SetFont('arial','U',12);
        $this->Cell(0,5, $this->setting->convert_encoding('Tax Invoice'),0,1,'C');

        // document information like document no, date and etc
        $this->setXY(145,42);
        $this->SetFont('arial','',10);
        $this->Cell(25,5, $this->setting->convert_encoding('Inv No : '),0,0,'R');
        $this->Cell(25,5, $this->setting->convert_encoding($this->document->document_no),0,1,'L');
        $this->setX(145);
        $this->Cell(25,5, $this->setting->convert_encoding('Date : '),0,0,'R');
        $this->Cell(25,5, date('d-m-Y',strtotime($this->document->document_date)),0,1,'L');
        $this->setX(145);
        $this->Cell(25,5, $this->setting->convert_encoding('Terms : '),0,0,'R');
        $this->Cell(25,5, $this->setting->convert_encoding($this->document->payment_term_days ? $this->document->payment_term_days.' Days':'Cash'),0,1,'L');
        $this->setX(145);
        $this->Cell(25,5, $this->setting->convert_encoding('Cust. PO : '),0,0,'R');
        $this->Cell(25,5, $this->setting->convert_encoding($this->document->po_no),0,1,'L');
        $this->setX(145);
        $this->Cell(25,5, $this->setting->convert_encoding('Currency : '),0,0,'R');
        $this->Cell(25,5, $this->setting->convert_encoding($this->document->currency_code),0,1,'L');

        // customer information
        $this->setY(42);
        $this->SetFont('arial','',10);
        $this->Cell(30,5, $this->setting->convert_encoding('Cust. Name : '),0,0,'R');
        $this->Cell(75,5, $this->setting->convert_encoding($this->document->customer->name),0,1,'L');
        $this->Cell(30,5, $this->setting->convert_encoding('Address : '),0,0,'R');
        $this->MultiCell(75,5, $this->setting->convert_encoding($this->document->get_billing_address()),0,'L');
        $this->Cell(30,5, $this->setting->convert_encoding('Tel : '),0,0,'R');
        $this->Cell(75,5, $this->setting->convert_encoding($this->document->phone_no),0,1,'L');
        $this->Cell(30,5, $this->setting->convert_encoding('Sales Person : '),0,0,'R');
        $this->Cell(75,5, $this->setting->convert_encoding($this->document->sales_person),0,1,'L');

        $this->SetFont('arial','',10);
        $this->Ln(3);
        $this->line(5,$this->getY(),205,$this->getY());
        $this->Ln(2);
        $this->Cell(5,5, $this->setting->convert_encoding('#'),0,0,'L');
        $this->Cell(75,5, $this->setting->convert_encoding('Description'),0,0,'L');
        $this->Cell(20,5, $this->setting->convert_encoding('Qty'),0,0,'C');
        $this->Cell(25,5, $this->setting->convert_encoding('UOM'),0,0,'C');
        $this->Cell(25,5, $this->setting->convert_encoding('U.Price'),0,0,'C');
        $this->Cell(20,5, $this->setting->convert_encoding('Tax'),0,0,'C');
        $this->Cell(25,5, $this->setting->convert_encoding('Total'),0,1,'R');
        $this->Ln(2);
        $this->line(5,$this->getY(),205,$this->getY());
    }

    function Footer()
    {
    	$setting = new Setting();
        $this->SetY(-70);
        $this->line(5,$this->getY(),205,$this->getY());

        // total amount
        $this->Ln(1);
        $this->SetX(170);
        $this->SetFont('arial','',10);
        $this->Cell(10,4,'Sub Total : ',0,0,'R');
        $this->Cell(25,4, $this->document->getDouble($this->document->amount),0,1,'R');
        $this->SetX(170);
        $this->SetFont('arial','',10);
        $this->Cell(10,4,'GST @ 6.00% : ',0,0,'R');
        $this->Cell(25,4, $this->document->getDouble($this->document->gst_amount),0,1,'R');
        $this->SetX(170);
        $this->SetFont('arial','',10);
        $this->Cell(10,4,'Grand Total : ',0,0,'R');
        $this->Cell(25,4, $this->document->getDouble($this->document->total_amount),0,1,'R');

        // document terms & rules
        $this->SetY(-69);
        $this->SetFont('arial','',9);
        $this->Cell(150,4, $this->setting->convert_encoding('*Goods sold are not returnable.'),0,1,'L');
        $this->MultiCell(150,4, $this->setting->convert_encoding('*We Reserve The Right To Charge Interest Of 2% Per Month On Overdue Accounts.'),0,'L');

        $this->SetY(-45);
        $this->Cell(30,4,'Prepared By',0,0,'L');
        $this->SetY(-24);
        $this->Cell(30,4,'',0,0,'L');
        $this->SetY(-20);
        $this->line(5,$this->getY(),50,$this->getY());
        $this->Ln(1);
        $this->Cell(30,4,$this->company['name'],0,0,'L');

        $this->SetXY(175, -45);
        $this->Cell(30,4,'Received By',0,0,'R');
        $this->SetY(-20);
        $this->line(155,$this->getY(),205,$this->getY());
        $this->Ln(1);
        $this->SetX(175);
        $this->Cell(30,4,'CHOP AND SIGN',0,0,'R');
    }
}
