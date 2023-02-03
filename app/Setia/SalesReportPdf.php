<?php

namespace App\Setia;

use App\Setting;
use App\Language;

use Codedge\Fpdf\Fpdf\Fpdf as Fpdf;

class SalesReportPdf extends Fpdf
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
		$this->Ln(10);
		$this->Cell(0,5,$this->setting->convert_encoding(mb_strtoupper(Language::trans('Sales Report'))),'B',1,'C');
		$this->Ln(5);

		// table of the listing
		// header of the table
	    $this->SetFont('Courier', 'B', 9);
		$this->Cell(10,5,'#',0,0,'L');
		$this->Cell(25,5,$this->setting->convert_encoding(Language::trans('Doc. No')),0,0,'C');
		$this->Cell(25,5,$this->setting->convert_encoding(Language::trans('Doc. Date')),0,0,'C');
		$this->Cell(80,5,$this->setting->convert_encoding(Language::trans('Customer Name')),0,0,'C');
		$this->Cell(25,5,$this->setting->convert_encoding(Language::trans('Pay. Term')),0,0,'C');
		$this->Cell(25,5,$this->setting->convert_encoding(Language::trans('Due Date')),0,0,'C');
		$this->Cell(25,5,$this->setting->convert_encoding(Language::trans('Status')),0,0,'C');
		$this->Cell(45,5,$this->setting->convert_encoding(Language::trans('Amount')),0,1,'R');
		$this->Line(10, $this->GetY(), 270, $this->getY());
		// content of the table
		$total=0;
		$this->Ln(2);
		foreach ($this->listing as $index => $row) {
			$this->Cell(10,5,($index+1).'.',0,0,'L');
			$this->Cell(25,5,$this->setting->convert_encoding($row->document_no),0,0,'C');
			$this->Cell(25,5,$this->setting->convert_encoding($row->getDate($row->document_date)),0,0,'C');
			$this->Cell(80,5,$this->setting->convert_encoding($row->customer_name),0,0,'C');
			$this->Cell(25,5,$this->setting->convert_encoding($row->payment_term_days ? ($row->payment_term_days.' '.Language::trans('Days')):Language::trans('Cash')),0,0,'C');
			$this->Cell(25,5,$this->setting->convert_encoding($row->getDate($row->due_date)),0,0,'C');
			$this->Cell(25,5,$this->setting->convert_encoding($row->status),0,0,'C');
			$this->Cell(45,5,$this->setting->convert_encoding($row->getDouble($row->total_amount)),0,1,'R');
			$total+=$row->total_amount;
		}
		$this->Ln(2);
		$this->Line(10, $this->GetY(), 270, $this->GetY());
		$this->Ln(2);
		// footer of the table
		$this->Cell(215,5,$this->setting->convert_encoding(Language::trans('Total')).' : ',0,0,'R');
		$this->Cell(45,5,$this->setting->convert_encoding($this->setting->getDouble($total)),0,1,'R');
		$this->Ln(2);
		$this->Line(235, $this->GetY(), 270, $this->getY());
		$this->Ln(1);
		$this->Line(235, $this->GetY(), 270, $this->getY());
    }
}
