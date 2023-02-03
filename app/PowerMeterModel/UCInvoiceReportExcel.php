+6<?php 

namespace App\PowerMeterModel;

use Auth;
use Session;
use App\Company;
use App\Setting;
use PHPExcel;
use PHPExcel_Style_Alignment;
use PHPExcel_Style_Border;
use PHPExcel_Style_Fill;
	/**
	* Report Document Sales Export To Excel
	*/
	class UCInvoiceReportExcel extends PHPExcel
	{
		public function content($listing=null)
		{
			$this->setActiveSheetIndex(0);
			
			$col = 0;
			$row= 5;

			//header of the page(listing information)
			$this->getActiveSheet()->mergeCells('A1:'.$this->getActiveSheet()->getHighestColumn().'1');
			$this->getActiveSheet()->getStyle('A1:'.$this->getActiveSheet()->getHighestColumn().'1')->getFont()->setBold(true);
			$this->getActiveSheet()->SetCellValue('A1', $this->setting->convert_encoding(mb_strtoupper($this->header_title->name)));

			$this->getActiveSheet()->mergeCells('A2:'.$this->getActiveSheet()->getHighestColumn().'2');
			$this->getActiveSheet()->getStyle('A2:'.$this->getActiveSheet()->getHighestColumn().'2')->getFont()->setBold(true);
			$this->getActiveSheet()->SetCellValue('A2', $this->setting->convert_encoding($this->header_title->get_address()));

			$this->getActiveSheet()->getStyle('A3:'.$this->getActiveSheet()->getHighestColumn().'2')->getFont()->setBold(true);
			$this->getActiveSheet()->SetCellValue('A3', "Created Date:".' '.date('Y-m-d H:i:s', strtotime('now')));

			$this->getActiveSheet()->getStyle('A4:'.$this->getActiveSheet()->getHighestColumn().'2')->getFont()->setBold(true);
			$this->getActiveSheet()->SetCellValue('A4', $this->setting->convert_encoding(Language::trans('Date Range').' : '.$this->date_range));

			$this->getActiveSheet()->getStyle('A5:'.$this->getActiveSheet()->getHighestColumn().'2')->getFont()->setBold(true);
			$this->getActiveSheet()->SetCellValue('A5', $this->setting->convert_encoding(Language::trans('Room No').' : '.$this->date_range));

			$this->getActiveSheet()->getStyle('A6:'.$this->getActiveSheet()->getHighestColumn().'2')->getFont()->setBold(true);
			$this->getActiveSheet()->SetCellValue('A6', $this->setting->convert_encoding(Language::trans('Currency').' : MYR'));

			$this->getActiveSheet()->getStyle('A7:'.$this->getActiveSheet()->getHighestColumn().'2')->getFont()->setBold(true);
			$this->getActiveSheet()->SetCellValue('A7', $this->setting->convert_encoding(mb_strtoupper(Language::trans('Invoices Report'))));
			$this->getActiveSheet()->getColumnDimension('A')->setWidth(20);
			$this->getActiveSheet()->getColumnDimension('B')->setWidth(35);
			$this->getActiveSheet()->getColumnDimension('C')->setWidth(20);

			$this->getActiveSheet()->getColumnDimension('D')->setWidth(60);
			$this->getActiveSheet()->getColumnDimension('E')->setWidth(20);
			$this->getActiveSheet()->getColumnDimension('F')->setWidth(20);


			$col = 0;
			$row = 10;
			$total=0;
			$report_cols = array('Date','From Time','To Time','Last Meter Reading','Current Meter Reading','Current Usage');

			foreach($report_cols as $report_col){
						$this->getActiveSheet()->getStyleByColumnAndRow($col, $row)->getFont()->setBold(true);
						$this->getActiveSheet()->getStyleByColumnAndRow($col, $row)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$this->getActiveSheet()->setCellValueByColumnAndRow($col++, $row, $report_col);
			}

			foreach ($this->listing as $index => $row) {

					$col = 0;
					$this->getActiveSheet()->getStyleByColumnAndRow($col, $row)->getFont()->setBold(true);
					$this->getActiveSheet()->getStyleByColumnAndRow($col, $row)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$this->getActiveSheet()->setCellValueByColumnAndRow($col++, $row, $index+1);
					$this->getActiveSheet()->getStyleByColumnAndRow($col, $row)->getFont()->setBold(true);
					$this->getActiveSheet()->getStyleByColumnAndRow($col, $row)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$this->getActiveSheet()->setCellValueByColumnAndRow($col++, $row, $row->document_no)
					$this->getActiveSheet()->getStyleByColumnAndRow($col, $row)->getFont()->setBold(true);
					$this->getActiveSheet()->getStyleByColumnAndRow($col, $row)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$this->getActiveSheet()->setCellValueByColumnAndRow($col++, $row, $row->this->setting->convert_room_no($this->model->leaf_room_id, $this->rooms));
					$this->getActiveSheet()->getStyleByColumnAndRow($col, $row)->getFont()->setBold(true);
					$this->getActiveSheet()->getStyleByColumnAndRow($col, $row)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$this->getActiveSheet()->setCellValueByColumnAndRow($col++, $row, $row->last_meter_reading);
					$this->getActiveSheet()->getStyleByColumnAndRow($col, $row)->getFont()->setBold(true);
					$this->getActiveSheet()->getStyleByColumnAndRow($col, $row)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$this->getActiveSheet()->setCellValueByColumnAndRow($col++, $row, $row->current_meter_reading);
					$this->getActiveSheet()->getStyleByColumnAndRow($col, $row)->getFont()->setBold(true);
					$this->getActiveSheet()->getStyleByColumnAndRow($col, $row)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$this->getActiveSheet()->setCellValueByColumnAndRow($col++, $row, $row->is_paid ? Language::trans('Paid'):Language::trans('Outstanding'));
					$this->getActiveSheet()->getStyleByColumnAndRow($col, $row)->getFont()->setBold(true);
					$this->getActiveSheet()->getStyleByColumnAndRow($col, $row)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$this->getActiveSheet()->setCellValueByColumnAndRow($col++, $row++, $row->total_amount);
					$total+=$row->total_amount;		
			}

			$this->getActiveSheet()->getStyleByColumnAndRow($col, $row)->getFont()->setBold(true);
			$this->getActiveSheet()->getStyleByColumnAndRow($col, $row)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			$this->getActiveSheet()->setCellValueByColumnAndRow($col++, $row, $row->is_paid ? Language::trans('Paid'):Language::trans('Outstanding'));
			$this->getActiveSheet()->getStyleByColumnAndRow($col, $row)->getFont()->setBold(true);
			$this->getActiveSheet()->getStyleByColumnAndRow($col, $row)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			$this->getActiveSheet()->setCellValueByColumnAndRow($col++, $row++, $this->setting->convert_encoding(Language::trans('Total')).' : '.$this->setting->getDouble($total));
			
			$this->getActiveSheet()->getColumnDimension('A')->setWidth(20);
			$this->getActiveSheet()->getColumnDimension('B')->setWidth(35);
			$this->getActiveSheet()->getColumnDimension('C')->setWidth(20);
			$this->getActiveSheet()->getColumnDimension('D')->setWidth(60);
			$this->getActiveSheet()->getColumnDimension('E')->setWidth(20);
			$this->getActiveSheet()->getColumnDimension('F')->setWidth(20);
		}
	
	}
