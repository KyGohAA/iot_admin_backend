<?php 

namespace App\PowerMeterModel;

use Auth;
use Session;
use App\Company;
use App\Setting;
use App\Language;
use PHPExcel;
use PHPExcel_Style_Alignment;
use PHPExcel_Style_Border;
use PHPExcel_Style_Fill;
	/**
	* Report Document Sales Export To Excel
	*/
	class UCRoomUsageReportExcel extends PHPExcel
	{
		public function content($listing,$houses_detail=null,$report_detail=null)
		{
			$this->setActiveSheetIndex(0);
			$this->setting = new Setting();
			$col = 0;
			$row= 5;
			$total_payable_amount = 0;
			//header of the page(listing information)
			$this->getActiveSheet()->mergeCells('A1:'.$this->getActiveSheet()->getHighestColumn().'1');
			$this->getActiveSheet()->getStyle('A1:'.$this->getActiveSheet()->getHighestColumn().'1')->getFont()->setBold(true);
			$this->getActiveSheet()->SetCellValue('A1', $this->setting->convert_encoding(mb_strtoupper($report_detail['report_title'])));

			$this->getActiveSheet()->mergeCells('A2:'.$this->getActiveSheet()->getHighestColumn().'2');
			$this->getActiveSheet()->getStyle('A2:'.$this->getActiveSheet()->getHighestColumn().'2')->getFont()->setBold(true);
			$this->getActiveSheet()->SetCellValue('A2', $this->setting->convert_encoding($report_detail['report_title']));

			$this->getActiveSheet()->getStyle('A3:'.$this->getActiveSheet()->getHighestColumn().'2')->getFont()->setBold(true);
			$this->getActiveSheet()->SetCellValue('A3', "Created Date:".' '.date('Y-m-d H:i:s', strtotime('now')));

			$this->getActiveSheet()->getStyle('A4:'.$this->getActiveSheet()->getHighestColumn().'2')->getFont()->setBold(true);
			$this->getActiveSheet()->SetCellValue('A4', $this->setting->convert_encoding(Language::trans('Date Range').' : '.$report_detail['date_range']));

			$this->getActiveSheet()->getStyle('A5:'.$this->getActiveSheet()->getHighestColumn().'2')->getFont()->setBold(true);
			$this->getActiveSheet()->SetCellValue('A5', $this->setting->convert_encoding(Language::trans('Room No').' : '.$report_detail['room_title']));

			$this->getActiveSheet()->getStyle('A6:'.$this->getActiveSheet()->getHighestColumn().'2')->getFont()->setBold(true);
			$this->getActiveSheet()->SetCellValue('A6', $this->setting->convert_encoding(Language::trans('Currency').' : MYR'));

			$this->getActiveSheet()->getStyle('A7:'.$this->getActiveSheet()->getHighestColumn().'2')->getFont()->setBold(true);

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
						$this->getActiveSheet()->setCellValueByColumnAndRow($col++, $row ,$report_col);
			}
			$row++;
//dd($row);
			$house_row = $row;
			foreach ($houses_detail as $house) {

					$total = 0;
					$col=0;
					//dd($house['house_unit']);
					$row = 11;
					//dd($col.'-'.$row);
					$this->getActiveSheet()->getStyleByColumnAndRow($col, $house_row)->getFont()->setBold(true);
					$this->getActiveSheet()->getStyleByColumnAndRow($col, $house_row)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$this->getActiveSheet()->setCellValueByColumnAndRow($col++, $house_row++, Language::trans('House') . ' ' . ':' . ' ' . $house['house_unit']);


					foreach ($house['house_rooms'] as $room) 
					{
        						$col = 0;
        						$isMeterRegister   = false;
		                $isFirstRoomHeader = true;
		                $rowNo             = 0;
		                $index             = 0;
		                $reading_data = isset($listing[$room['meter']['id']]) ? $listing[$room['meter']['id']] : array();
		                foreach ($reading_data as $index => $row) {

		                		if ($row->meter_register_id == $room['meter']['id']) {
				                        if ($isFirstRoomHeader == true) {
				                   
				                            $this->getActiveSheet()->getStyleByColumnAndRow($col, $house_row)->getFont()->setBold(true);
											$this->getActiveSheet()->getStyleByColumnAndRow($col, $house_row)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
											$this->getActiveSheet()->setCellValueByColumnAndRow($col++, $house_row,  Language::trans('Room') . ' : ' . $room['house_room_name'] );

											$this->getActiveSheet()->getStyleByColumnAndRow($col, $house_row)->getFont()->setBold(true);
											$this->getActiveSheet()->getStyleByColumnAndRow($col, $house_row)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
											$this->getActiveSheet()->setCellValueByColumnAndRow($col++, $house_row++, Language::trans('Meter Id').' : '.$room['meter']['id']);


				                        }
				                        
				                        $payable_amount    = Setting::calculate_utility_fee($row->total_usage);
				                        $isFirstRoomHeader = false;
				                        $isMeterRegister   = true;
				                        $total_payable_amount += $payable_amount;
				                        
				                        // table of the listing
				                        // header of the table
				    
	                        $this->getActiveSheet()->getStyleByColumnAndRow($col, $house_row)->getFont()->setBold(true);
							$this->getActiveSheet()->getStyleByColumnAndRow($col, $house_row)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$this->getActiveSheet()->setCellValueByColumnAndRow($col++, $house_row, ($index + 1) . '.');

							$this->getActiveSheet()->getStyleByColumnAndRow($col, $house_row)->getFont()->setBold(true);
							$this->getActiveSheet()->getStyleByColumnAndRow($col, $house_row)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$this->getActiveSheet()->setCellValueByColumnAndRow($col++, $house_row, $this->setting->convert_encoding($this->setting->getDate($row->current_date)));

							$this->getActiveSheet()->getStyleByColumnAndRow($col, $house_row)->getFont()->setBold(true);
							$this->getActiveSheet()->getStyleByColumnAndRow($col, $house_row)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$this->getActiveSheet()->setCellValueByColumnAndRow($col++, $house_row,  $this->setting->convert_encoding($row->time_started));

							$this->getActiveSheet()->getStyleByColumnAndRow($col, $house_row)->getFont()->setBold(true);
							$this->getActiveSheet()->getStyleByColumnAndRow($col, $house_row)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$this->getActiveSheet()->setCellValueByColumnAndRow($col++, $house_row, $this->setting->convert_encoding($row->time_ended));

							$this->getActiveSheet()->getStyleByColumnAndRow($col, $house_row)->getFont()->setBold(true);
							$this->getActiveSheet()->getStyleByColumnAndRow($col, $house_row)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$this->getActiveSheet()->setCellValueByColumnAndRow($col++, $house_row, $this->setting->convert_encoding($row->last_meter_reading));

							$this->getActiveSheet()->getStyleByColumnAndRow($col, $house_row)->getFont()->setBold(true);
							$this->getActiveSheet()->getStyleByColumnAndRow($col, $house_row)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$this->getActiveSheet()->setCellValueByColumnAndRow($col++, $house_row, $this->setting->convert_encoding($row->current_meter_reading) );

							$this->getActiveSheet()->getStyleByColumnAndRow($col, $house_row)->getFont()->setBold(true);
							$this->getActiveSheet()->getStyleByColumnAndRow($col, $house_row)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$this->getActiveSheet()->setCellValueByColumnAndRow($col++, $house_row++, $this->setting->convert_encoding($row->current_usage));

				


				                        $total += $row->current_usage;
				                        $index++;
				                    }

		                }



		                if ($isMeterRegister == false) {
		                    
		                  /*  $this->SetFont('Courier', 'B', 9);
		                    $this->Cell(20, 3, Language::trans('Room') . " " . $room['house_room_name'], 0, 0, 'L');
		                    $this->Cell(15, 3, Language::trans(Setting::SUNWAY_NO_METER_FOUND_LABEL), 0, 1, 'L');*/
		                    
		                } else {
		                	/*$this->Line(10, $this->GetY(), 270, $this->getY());
		                    $this->Cell(215, 5, $this->setting->convert_encoding(Language::trans('Total')) . ' : ', 0, 0, 'R');
		                    $this->Cell(45, 5, $this->setting->convert_encoding($this->setting->getDouble($total)), 0, 1, 'R');
		                    $this->Ln(2);
		                    $this->Line(235, $this->GetY(), 270, $this->getY());
		                    $this->Ln(1);
		                    $this->Line(235, $this->GetY(), 270, $this->getY());*/
		                }
		                $isMeterRegister = false;
		                //$this->Ln(2);   


					}

			}






			$this->getActiveSheet()->getColumnDimension('A')->setWidth(20);
			$this->getActiveSheet()->getColumnDimension('B')->setWidth(35);
			$this->getActiveSheet()->getColumnDimension('C')->setWidth(20);
			$this->getActiveSheet()->getColumnDimension('D')->setWidth(60);
			$this->getActiveSheet()->getColumnDimension('E')->setWidth(20);
			$this->getActiveSheet()->getColumnDimension('F')->setWidth(20);







		}
	
	}
