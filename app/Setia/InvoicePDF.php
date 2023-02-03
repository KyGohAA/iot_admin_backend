<?php

namespace App\Setia;

use App\Setting;
use App\Language;
use App\Company;
use Codedge\Fpdf\Fpdf\Fpdf as Fpdf;

class InvoicePDF extends Fpdf
{

    public function getHeaderFromCompanyModel(){

        $model = new Company();
        $model->self_profile();

        //wip
        $model = Company::find(2);
        $this->company = $model;
        $header_data = [
                    'status_code'   =>  1,
                    'address'    =>  $model->get_address(),
                    'company' => $model,
                    ];

        return $header_data;
        

    }

    public function content($model)
    {
        // header of the listing
        $this->getHeaderFromCompanyModel();
        $this->Ln(5);
        $this->SetFont('Courier', '', 12);
        $this->Cell(0,3,$this->setting->convert_encoding(mb_strtoupper($this->header_data['company']['name'])),0,1,'C');
        $this->Cell(0,5,$this->setting->convert_encoding(mb_strtoupper($this->header_data['address'])),0,1,'C');
        $this->setX(90);

        $this->SetFont('Courier', '', 10);
        $this->Ln(1);
        $this->Cell(135,4,$this->setting->convert_encoding('Tel No.:').$this->header_data['company']['tel'],0,0,'C');
        $this->Cell(60,4,$this->setting->convert_encoding('Fax No.:').$this->header_data['company']['fax'],0,1,'L');
        $this->Ln(8);
        $this->SetFont('Courier', 'B', 10);
        $this->Cell(30,3,$this->setting->convert_encoding(Language::trans('Invoice')),0,1,'L');
        $this->Ln(4);
        $this->SetFont('Courier', '', 8);
        /*$this->Cell(30,5,$this->setting->convert_encoding(Language::trans('Our Ref.')),0,0,'L');
        $this->Cell(133,3,': '.$model['document_no'],0,1,'L');*/
        $this->Cell(30,3,$this->setting->convert_encoding(Language::trans('Customer Name')),0,0,'L');
        $this->Cell(90,3,': '.(isset($this->document->customer) ? $this->document->customer->name : ''),0,0,'L');
        $this->Cell(30,3,$this->setting->convert_encoding(Language::trans('Membership No.')),0,0,'L');
        $this->Cell(133,3,': '.$model['customer_name'],0,1,'L');
        $this->Cell(30,3,$this->setting->convert_encoding(Language::trans('Received from')),0,0,'L');
        $this->Cell(90,3,': '.(isset($this->document->customer) ? $this->document->customer->name : ''),0,0,'L');
        $this->Cell(30,3,$this->setting->convert_encoding(Language::trans('Reference No.')),0,0,'L');
        $this->Cell(133,3,': '.(isset($this->document) ? $this->document->reference_no : ''),0,1,'L');
        $this->Cell(30,3,$this->setting->convert_encoding(Language::trans('Payment mode.')),0,0,'L');
        $this->Cell(90,3,': '.(isset($this->document) ? $this->document->payment_method : ''),0,0,'L');
        $this->Cell(30,3,$this->setting->convert_encoding(Language::trans('Receipt No.')),0,0,'L');
        $this->Cell(135,3,': '.(isset($this->document) ? $this->document->document_no : ''),0,1,'L');
        $this->Cell(30,3,$this->setting->convert_encoding(Language::trans('Sum Of Ringgit')),0,0,'L');
        $this->Cell(90,3,': '.$model['customer_name'],0,0,'L');
        $this->Cell(30,3,$this->setting->convert_encoding(Language::trans('Receipt Date.')),0,0,'L');
        $this->Cell(135,3,': '.$model['document_date'],0,1,'L');
        $this->Ln(5);


        // table of the listing
        // header of the table
        $this->SetFont('Courier', 'B', 9);
        $this->Cell(10,5,'#',0,0,'L');
        $this->Cell(140,5,$this->setting->convert_encoding(Language::trans('Description')),0,0,'L');
        $this->Cell(25,5,$this->setting->convert_encoding(Language::trans('Amount (RM)')),0,0,'C');
        $this->Line(5, $this->GetY(), 210, $this->getY());
        // content of the table
        $total=0;
        $index=0;
        $this->Ln(2);

        foreach ($model->items as $row) {
            $this->Cell(10,5,($index+1).'.',0,0,'L');
            $this->Cell(25,5,$this->setting->convert_encoding($row->document_no),0,0,'C');
            $this->Cell(25,5,$this->setting->convert_encoding($row->getDate($row->document_date)),0,1,'C');     
            $total+=$row->total_amount;
        }
        $this->Line(5, $this->GetY(), 210, $this->GetY());
        $this->Ln(2);
        $this->Cell(125,5,$this->setting->convert_encoding(Language::trans('Total')).' : ',0,0,'R');
        $this->Cell(45,5,$this->setting->convert_encoding($this->setting->getDouble($model['payment_amount'])),0,1,'R');
        $this->Ln(2);


        $this->Ln(3);
        $this->Line(5, $this->GetY(), 210, $this->GetY());
        $this->SetFont('Courier', '', 8);
        $this->Cell(215,5,$this->setting->convert_encoding(Language::trans('This invoice is valid subject to the cheque being honoured. Thanks you for your payment.')).' : ',0,0,'L');
        $this->Ln(2);
        // footer of the table
    
        $this->Line(235, $this->GetY(), 270, $this->getY());
        $this->Ln(1);
        $this->Line(235, $this->GetY(), 270, $this->getY());
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
