<?php 

namespace App\PowerMeterModel;

use App\Auth;
use App\Session;
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
	class UCMonthlyUsageReportExcel extends PHPExcel
	{
		public function content($listing ,$houses_detail)
		{
			$this->setActiveSheetIndex(0);
			$total = 0;
			$col = 0;
			$row= 5;

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
			$total_payable_amount = 0;

			$report_cols = array('Month','Total Hours','Avg. kW','Max. kW','Min. kW','Total kWh','Total Charges (RM)');

			foreach($report_cols as $report_col){
						$this->getActiveSheet()->getStyleByColumnAndRow($col, $row)->getFont()->setBold(true);
						$this->getActiveSheet()->getStyleByColumnAndRow($col, $row)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$this->getActiveSheet()->setCellValueByColumnAndRow($col++, $row, $report_col);
			}
		
			foreach($houses_detail as $house){
				$col = 0;
				$row++;
				$this->getActiveSheet()->getStyleByColumnAndRow($col, $row)->getFont()->setBold(true);
				$this->getActiveSheet()->getStyleByColumnAndRow($col, $row)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
				$this->getActiveSheet()->setCellValueByColumnAndRow($col++, $row++, Language::trans('House').' : '.$house['house_unit']);
				
				if(isset($house['house_rooms'])){
					
					foreach($house['house_rooms'] as $room){

						$isMeterRegister = false;
						$isFirstRoomHeader = true;
						$rowNo =0 ;
						$room_subtotal = 0;

						foreach($listing as $item){
							$col = 0;
							if(!isset($room['meter']['id'])){

										$col = 0;
										$this->getActiveSheet()->getStyleByColumnAndRow($col, $row)->getFont()->setBold(true);
										$this->getActiveSheet()->getStyleByColumnAndRow($col, $row)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
										$this->getActiveSheet()->setCellValueByColumnAndRow($col++, $row, Language::trans('Room').' : '.$room['house_room_name']);

										$this->getActiveSheet()->getStyleByColumnAndRow($col, $row)->getFont()->setBold(true);
										$this->getActiveSheet()->getStyleByColumnAndRow($col, $row)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
										$this->getActiveSheet()->setCellValueByColumnAndRow($col++, $row, Language::trans(Setting::SUNWAY_NO_METER_FOUND_LABEL));
										break;	

							}elseif($item->meter_register_id == $room['meter']['id']){
									if($isFirstRoomHeader == true){
	
										$this->getActiveSheet()->getStyleByColumnAndRow($col, $row)->getFont()->setBold(true);
										$this->getActiveSheet()->getStyleByColumnAndRow($col, $row)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
										$this->getActiveSheet()->setCellValueByColumnAndRow($col++, $row, Language::trans('Room').' : '.$room['house_room_name']);

										$this->getActiveSheet()->getStyleByColumnAndRow($col, $row)->getFont()->setBold(true);
										$this->getActiveSheet()->getStyleByColumnAndRow($col, $row)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
										$this->getActiveSheet()->setCellValueByColumnAndRow($col++, $row++, Language::trans('Meter Status').' : '.$listing[$rowNo]->meter_register_id);
										
									}
									
										$col = 0;
										$payable_amount = Setting::calculate_utility_fee($item->total_usage);
										$this->getActiveSheet()->getStyleByColumnAndRow($col, $row)->getFont()->setBold(true);
										$this->getActiveSheet()->getStyleByColumnAndRow($col, $row)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
										$this->getActiveSheet()->setCellValueByColumnAndRow($col++, $row, $item->current_date);

										$this->getActiveSheet()->getStyleByColumnAndRow($col, $row)->getFont()->setBold(true);
										$this->getActiveSheet()->getStyleByColumnAndRow($col, $row)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
										$this->getActiveSheet()->setCellValueByColumnAndRow($col++, $row, $item->total_hours);

										$this->getActiveSheet()->getStyleByColumnAndRow($col, $row)->getFont()->setBold(true);
										$this->getActiveSheet()->getStyleByColumnAndRow($col, $row)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
										$this->getActiveSheet()->setCellValueByColumnAndRow($col++, $row, $item->average_usage);

										$this->getActiveSheet()->getStyleByColumnAndRow($col, $row)->getFont()->setBold(true);
										$this->getActiveSheet()->getStyleByColumnAndRow($col, $row)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
										$this->getActiveSheet()->setCellValueByColumnAndRow($col++, $row, $item->max_usage);

										$this->getActiveSheet()->getStyleByColumnAndRow($col, $row)->getFont()->setBold(true);
										$this->getActiveSheet()->getStyleByColumnAndRow($col, $row)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
										$this->getActiveSheet()->setCellValueByColumnAndRow($col++, $row, $item->min_usage);

										$this->getActiveSheet()->getStyleByColumnAndRow($col, $row)->getFont()->setBold(true);
										$this->getActiveSheet()->getStyleByColumnAndRow($col, $row)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
										$this->getActiveSheet()->setCellValueByColumnAndRow($col++, $row, $item->total_usage);
										
										$this->getActiveSheet()->getStyleByColumnAndRow($col, $row)->getFont()->setBold(true);
										$this->getActiveSheet()->getStyleByColumnAndRow($col, $row)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
										$this->getActiveSheet()->setCellValueByColumnAndRow($col++, $row++, number_format($payable_amount,2));
							
										$payable_amount = Setting::calculate_utility_fee($item->total_usage);
										$isFirstRoomHeader = false;			
										$isMeterRegister = true;
										$total += $item->total_usage; 
										$total_payable_amount += $payable_amount;
									
	
										
							}

						}
/*								if($isMeterRegister == false){
									$col = 0;
									$this->getActiveSheet()->getStyleByColumnAndRow($col, $row)->getFont()->setBold(true);
									$this->getActiveSheet()->getStyleByColumnAndRow($col, $row)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$this->getActiveSheet()->setCellValueByColumnAndRow($col++, $row, Language::trans('Room').' : '.$room['house_room_name']);

									$this->getActiveSheet()->getStyleByColumnAndRow($col, $row)->getFont()->setBold(true);
									$this->getActiveSheet()->getStyleByColumnAndRow($col, $row)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$this->getActiveSheet()->setCellValueByColumnAndRow($col++, $row, Language::trans(Setting::SUNWAY_NO_METER_FOUND_LABEL));
								}
				 */
								$isMeterRegister = false;
								$row++;
						}
						
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
