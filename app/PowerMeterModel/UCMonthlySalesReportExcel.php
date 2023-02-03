<?php 

namespace App\PowerMeterModel;

use Auth;
use Session;
use App\Company;
use App\Setting;
use App\LeafAPI;
use App\Language as language;
use PHPExcel;
use PHPExcel_Style_Alignment;
use PHPExcel_Style_Border;
use PHPExcel_Style_Fill;
	/**
	* Report Document Sales Export To Excel
	*/
	class UCMonthlySalesReportExcel extends PHPExcel
	{
		public function content($listing/*=null*/)
		{
			$this->setActiveSheetIndex(0);
			$this->setting = new Setting();
			$col = 0;
			$row= 5;
			$report_col_no = 0;
			//header of the page(listing information)
			$this->getActiveSheet()->mergeCells('A1:'.$this->getActiveSheet()->getHighestColumn().'1');
			$this->getActiveSheet()->getStyle('A1:'.$this->getActiveSheet()->getHighestColumn().'1')->getFont()->setBold(true);

			$this->getActiveSheet()->mergeCells('A2:'.$this->getActiveSheet()->getHighestColumn().'2');
			$this->getActiveSheet()->getStyle('A2:'.$this->getActiveSheet()->getHighestColumn().'2')->getFont()->setBold(true);
		
			$this->getActiveSheet()->getStyle('A3:'.$this->getActiveSheet()->getHighestColumn().'2')->getFont()->setBold(true);
			$this->getActiveSheet()->SetCellValue('A3', "Created Date:".' '.date('Y-m-d H:i:s', strtotime('now')));
			$this->getActiveSheet()->getColumnDimension('A')->setWidth(20);
			$this->getActiveSheet()->getColumnDimension('B')->setWidth(35);
			$this->getActiveSheet()->getColumnDimension('C')->setWidth(20);

			$this->getActiveSheet()->getColumnDimension('D')->setWidth(60);
			$this->getActiveSheet()->getColumnDimension('E')->setWidth(20);
			$this->getActiveSheet()->getColumnDimension('F')->setWidth(20);

			$col = 0;
			$row = 10;
			$total = 0;
			$report_cols = array('No.','Room No','Document No','Refernce No.','Description','Document Date','Payment Status','Amount');
	
			foreach($report_cols as $report_col){

				if($report_col == 'Amount'){
					$report_col_no = $col;
				}

				$this->getActiveSheet()->getStyleByColumnAndRow($col, $row)->getFont()->setBold(true);
				$this->getActiveSheet()->getStyleByColumnAndRow($col, $row)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
				$this->getActiveSheet()->setCellValueByColumnAndRow($col++, $row, $report_col);


			}
			$row = 11;
			//$index=  1;
			foreach ($listing as $index => $item) {

					$col = 0;

					$this->getActiveSheet()->getStyleByColumnAndRow($col, $row)->getFont()->setBold(true);
					$this->getActiveSheet()->getStyleByColumnAndRow($col, $row)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$this->getActiveSheet()->setCellValueByColumnAndRow($col++, $row, $index+1);

					$this->getActiveSheet()->getStyleByColumnAndRow($col, $row)->getFont()->setBold(true);
					$this->getActiveSheet()->getStyleByColumnAndRow($col, $row)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$this->getActiveSheet()->setCellValueByColumnAndRow($col++, $row, LeafAPI::get_room_name_by_leaf_room_id($item->leaf_room_id));
				
					$this->getActiveSheet()->getStyleByColumnAndRow($col, $row)->getFont()->setBold(true);
					$this->getActiveSheet()->getStyleByColumnAndRow($col, $row)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$this->getActiveSheet()->setCellValueByColumnAndRow($col++, $row, $item->document_no);

					$this->getActiveSheet()->getStyleByColumnAndRow($col, $row)->getFont()->setBold(true);
					$this->getActiveSheet()->getStyleByColumnAndRow($col, $row)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$this->getActiveSheet()->setCellValueByColumnAndRow($col++, $row, $item->payment_gateway_reference_no);

					$this->getActiveSheet()->getStyleByColumnAndRow($col, $row)->getFont()->setBold(true);
					$this->getActiveSheet()->getStyleByColumnAndRow($col, $row)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$this->getActiveSheet()->setCellValueByColumnAndRow($col++, $row, $item->remark);

					$this->getActiveSheet()->getStyleByColumnAndRow($col, $row)->getFont()->setBold(true);
					$this->getActiveSheet()->getStyleByColumnAndRow($col, $row)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$this->getActiveSheet()->setCellValueByColumnAndRow($col++, $row, $item->document_date);
					$status_text =  $item->status == 1 ? 'Success' : 'Fail';
					$this->getActiveSheet()->getStyleByColumnAndRow($col, $row)->getFont()->setBold(true);
					$this->getActiveSheet()->getStyleByColumnAndRow($col, $row)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$this->getActiveSheet()->setCellValueByColumnAndRow($col++, $row, $status_text);

					$this->getActiveSheet()->getStyleByColumnAndRow($col, $row)->getFont()->setBold(true);
					$this->getActiveSheet()->getStyleByColumnAndRow($col, $row)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$this->getActiveSheet()->setCellValueByColumnAndRow($col++, $row, $item->getDouble($item->total_amount));
					$row++;
					$total+=$item->total_amount;		
			}

			$this->getActiveSheet()->getStyleByColumnAndRow($col, $row)->getFont()->setBold(true);
			$this->getActiveSheet()->getStyleByColumnAndRow($col, $row)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			$col -= 2;
			$this->getActiveSheet()->setCellValueByColumnAndRow($col++, $row, $this->setting->convert_encoding(language::trans('Total')));
			$this->getActiveSheet()->setCellValueByColumnAndRow($col, $row++, $this->setting->convert_encoding($this->setting->getDouble($total)));


			$this->getActiveSheet()->getColumnDimension('A')->setWidth(20);
			$this->getActiveSheet()->getColumnDimension('B')->setWidth(35);
			$this->getActiveSheet()->getColumnDimension('C')->setWidth(20);
			$this->getActiveSheet()->getColumnDimension('D')->setWidth(60);
			$this->getActiveSheet()->getColumnDimension('E')->setWidth(20);
			$this->getActiveSheet()->getColumnDimension('F')->setWidth(20);







		}
	
	}
