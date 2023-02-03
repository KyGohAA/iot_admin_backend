<?php

namespace App\SMC;

use App\Setting;
use App\Language;

use Codedge\Fpdf\Fpdf\Fpdf as Fpdf;

class InvoiceReportPdf extends Fpdf
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
	    $this->SetFont('Courier', '', 10);
		$this->Ln(2);
		$this->Cell(135,5,$this->setting->convert_encoding(Language::trans('Date Range').' : '.$this->date_range),0,0,'L');
		$this->Cell(135,5,$this->setting->convert_encoding(Language::trans('Room No').' : '.$this->date_range),0,1,'L');
		$this->Cell(135,5,$this->setting->convert_encoding(Language::trans('Currency').' : MYR'),0,1,'L');
		$this->Ln(3);
		$this->Cell(0,5,$this->setting->convert_encoding(mb_strtoupper(Language::trans('Invoices Report'))),'B',1,'C');
		$this->Ln(5);

		// table of the listing
		// header of the table
	    $this->SetFont('Courier', 'B', 9);
		$this->Cell(10,5,'#',0,0,'L');
		$this->Cell(45,5,$this->setting->convert_encoding(Language::trans('Document No')),0,0,'C');
		$this->Cell(30,5,$this->setting->convert_encoding(Language::trans('Room No')),0,0,'C');
		$this->Cell(50,5,$this->setting->convert_encoding(Language::trans('Last Meter Reading')),0,0,'C');
		$this->Cell(50,5,$this->setting->convert_encoding(Language::trans('Current Meter Reading')),0,0,'C');
		$this->Cell(30,5,$this->setting->convert_encoding(Language::trans('Payment Status')),0,0,'C');
		$this->Cell(45,5,$this->setting->convert_encoding(Language::trans('Amount')),0,1,'C');
		$this->Line(10, $this->GetY(), 270, $this->getY());
		// content of the table
		$total=0;
		$this->Ln(2);
		foreach ($this->listing as $index => $row) {
			$this->Cell(10,5,($index+1).'.',0,0,'L');
			$this->Cell(45,5,$this->setting->convert_encoding($row->document_no),0,0,'C');
			$this->Cell(30,5,$this->setting->convert_encoding($this->setting->convert_room_no($this->model->leaf_room_id, $this->rooms)),0,0,'C');
			$this->Cell(50,5,$this->setting->convert_encoding($row->last_meter_reading),0,0,'C');
			$this->Cell(50,5,$this->setting->convert_encoding($row->current_meter_reading),0,0,'C');
			$this->Cell(30,5,$this->setting->convert_encoding($row->is_paid ? Language::trans('Paid'):Language::trans('Outstanding')),0,0,'C');
			$this->Cell(45,5,$this->setting->convert_encoding($row->total_amount),0,1,'C');
			$total+=$row->total_amount;
		}
		$this->Ln(2);
		$this->Line(10, $this->GetY(), 270, $this->GetY());
		$this->Ln(2);
		// footer of the table
		$this->Cell(215,5,$this->setting->convert_encoding(Language::trans('Total')).' : ',0,0,'R');
		$this->Cell(45,5,$this->setting->convert_encoding($this->setting->getDouble($total)),0,1,'C');
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
