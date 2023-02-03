<?php

namespace App\SMC;

use App\Setting;
use App\Language;
use App\LeafAPI;

use Codedge\Fpdf\Fpdf\Fpdf as Fpdf;

class MonthlySaleReportPdf extends Fpdf
{
    public function content()
    {
		// header of the listing
	    $this->SetFont('Courier', 'B', 12);
		$this->Cell(0,8,$this->setting->convert_encoding(mb_strtoupper($this->header_title->name)),0,1,'C');
		$this->setX(90);
	    $this->SetFont('Courier', 'B', 10);
		$this->MultiCell(100,4,$this->setting->convert_encoding($this->header_title->get_address()),0,'C');
		$this->Ln(2);
		$this->Line(10, $this->GetY(), 270, $this->GetY());
	    $this->SetFont('Courier', '', 8);
		$this->Ln(2);
		$this->Cell(135,5,$this->setting->convert_encoding(Language::trans('Date Range').' : '.$this->date_range),0,0,'L');
		$this->Ln(3);
		$this->SetFont('Courier', 'B', 20);
		$this->Cell(0,7,$this->setting->convert_encoding(mb_strtoupper(Language::trans('Monthly Sales Report'))),'B',1,'C');
		$this->Ln(5);

		// table of the listing
		// header of the table
	    $this->SetFont('Courier', 'B', 9);
		$this->Cell(10,5,'#',0,0,'L');
	
		$this->Cell(25,5,$this->setting->convert_encoding(Language::trans('Room No')),0,0,'C');
		$this->Cell(35,5,$this->setting->convert_encoding(Language::trans('Document No')),0,0,'C');
		$this->Cell(35,5,$this->setting->convert_encoding(Language::trans('Reference No')),0,0,'C');
		$this->Cell(55,5,$this->setting->convert_encoding(Language::trans('Description')),0,0,'C');
		$this->Cell(45,5,$this->setting->convert_encoding(Language::trans('Document Date')),0,0,'C');
		$this->Cell(20,5,$this->setting->convert_encoding(Language::trans('Payment Status')),0,0,'C');
		$this->Cell(30,5,$this->setting->convert_encoding(Language::trans('Amount')),0,1,'R');
		$this->Line(10, $this->GetY(), 270, $this->getY());
		// content of the table
		$total=0;
		$this->Ln(2);
		$this->SetFont('Courier', 'B', 7);
		foreach ($this->listing as $index => $row) {
			$x = $this->GetX();
			$y = $this->GetY();
			$status_text =  $row->status == 1 ? 'Success' : 'Fail';
			$this->Cell(10,5,($index+1).'.',0,0,'L');
			$this->Cell(30, 5, $this->setting->convert_encoding(LeafAPI::get_room_name_by_leaf_room_id($row->leaf_room_id)), 0, 'R');
			$this->Cell(35, 5, $this->setting->convert_encoding($row->document_no), 0, 'R');
			$this->Cell(30, 5, $this->setting->convert_encoding($row->payment_gateway_reference_no), 0, 'R');
			$this->MultiCell(80, 5, $this->setting->convert_encoding($row->remark), 0, 'L', FALSE);
			$this->SetXY($x + 175, $y);
			$this->Cell(40, 5, $this->setting->convert_encoding($row->document_date), 0, 'R');
			$this->Cell(20, 5, $this->setting->convert_encoding($status_text), 0, 'R');
			$this->Cell(15, 5, $this->setting->convert_encoding($row->getDouble($row->total_amount)),0,1,'R');
			$this->Ln(2);
			$total+=$row->total_amount;
		}
		$this->Ln(2);
		$this->Line(10, $this->GetY(), 270, $this->GetY());
		$this->Ln(2);
		// footer of the table
		$this->Cell(225,5,$this->setting->convert_encoding(Language::trans('Total')).' : ',0,0,'R');
		$this->Cell(25,5,$this->setting->convert_encoding($this->setting->getDouble($total)),0,1,'R');
		$this->Ln(2);
		$this->Line(235, $this->GetY(), 270, $this->getY());
		$this->Ln(1);
		$this->Line(235, $this->GetY(), 270, $this->getY());
		$this->is_finished = true;
    }

    public function Footer()
    {
        if($this->is_finished){
            $this->SetY(-15);

            //draw line
            $this->Ln(2);
            $this->Line('5', $this->getY(), '275', $this->getY());
            $this->Ln(2);
            $this->setX(165);

        }
    }
}


