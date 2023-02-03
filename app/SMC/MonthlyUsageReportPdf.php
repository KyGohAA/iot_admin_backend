<?php

namespace App\SMC;

use App\Setting;
use App\Language;


use Codedge\Fpdf\Fpdf\Fpdf as Fpdf;

class MonthlyUsageReportPdf extends Fpdf
{
    public function content()
    {	//dd($report_detail->report_title);
    	 // header of the listing -----------------------------------------------------------------------------
    	$report_detail = $this->report_detail;
        $this->SetFont('Courier', 'B', 12);
        $this->Cell(0, 8, $this->setting->convert_encoding(mb_strtoupper($report_detail['report_title'])), 0, 1, 'C');
        $this->setX(90);
        $this->SetFont('Courier', 'B', 10);
        $this->MultiCell(100, 4, $this->setting->convert_encoding($this->header_title->get_address()), 0, 'C');
        $this->Ln(2);
        $this->Line(10, $this->GetY(), 270, $this->GetY());
        $this->SetFont('Courier', '', 8);
        $this->Ln(2);
        $this->Cell(135, 3, $this->setting->convert_encoding(Language::trans('Date Range') . ' : ' . $this->date_range), 0, 0, 'L');
        $this->Cell(135, 3, $this->setting->convert_encoding(Language::trans('Room No') . ' : ' . $this->room_title), 0, 1, 'L');
        $this->Cell(135, 3, $this->setting->convert_encoding(Language::trans('Currency') . ' : RM'), 0, 1, 'L');
        $this->Ln(3);

        $this->SetFont('Courier', 'B', 20);
        $this->Cell(0, 7, $this->setting->convert_encoding(mb_strtoupper(Language::trans('Room Usages Report'))), 'B', 1, 'C');
        $this->Ln(3);
        // ---------------------------------------------------------------------------------------------------
    	
    	// table of the listing
		// header of the table
	    $this->SetFont('Courier', 'B', 9);
		$this->Cell(10,5,'#',0,0,'L');
		$this->Cell(15,5,$this->setting->convert_encoding(Language::trans('Month')),0,0,'C');
		$this->Cell(40,5,$this->setting->convert_encoding(Language::trans('Total Hours')),0,0,'C');
		$this->Cell(40,5,$this->setting->convert_encoding(Language::trans('Avg. kW')),0,0,'C');
		$this->Cell(35,5,$this->setting->convert_encoding(Language::trans('Max. kW')),0,0,'C');
		$this->Cell(35,5,$this->setting->convert_encoding(Language::trans('Min. kW')),0,0,'C');
		$this->Cell(35,5,$this->setting->convert_encoding(Language::trans('Total kWh')),0,0,'C');
	    $this->Cell(30,5,$this->setting->convert_encoding(Language::trans('Total Charges (RM)')),0,1,'C');

	    $project_kwh_total_usage = 0;
    	$total_payable_amount = 0;
		foreach($this->houses_detail as $house){

			$this->Ln(3);
			$this->SetFont('Courier', 'B', 9);
			$this->Cell(10,5,'---------------------------------------------------------------------------------------------------------------------------------------',0,1,'L');
			$this->Cell(10,5,"|  ".Language::trans('House').' '.':'.' '.$house['house_unit'],0,1,'L');
			$this->Cell(10,5,'---------------------------------------------------------------------------------------------------------------------------------------',0,1,'L');

			foreach($house['house_rooms'] as $room){

				$is_meter_register = false;
				$is_first_room_header = true;
				$rowNo =0 ;
				$room_subtotal = 0;
				$room_kwh_subtotal_usage = 0;

				foreach($this->listing as $row){

					if(!isset($room['meter']['id'])){

							$this->SetFont('Courier', 'B', 9);									
							$this->Cell(20,3,Language::trans('Room')." ".$room['house_room_name'],0,0,'L');
							$this->Cell(15,3,Language::trans(Setting::SUNWAY_NO_METER_FOUND_LABEL),0,1,'L');
							$rowNo++;	
							break;	

					}elseif($row->meter_register_id == $room['meter']['id']){
						if($is_first_room_header == true){
							$this->SetFont('Courier', 'B', 9);

							$this->Ln(1);	
							$this->Line(10, $this->GetY(), 270, $this->GetY());									
							$this->Cell(20,5,Language::trans('Room').' : '.$room['house_room_name'],0,1,'L');
							$this->Line(10, $this->GetY(), 270, $this->GetY());	
							//$this->Cell(15,5,Language::trans('Meter Status').' : '.$this->listing[$rowNo]->meter_register_id,0,1,'L');

							if($this->is_show_tenant)
							{
								if(count($room['house_room_members']) > 0){
									
									$this->SetFont('Courier', 'B', 9);									
									
									$this->Cell(10,4,'#',0,0,'L');
									$this->Cell(60,4,$this->setting->convert_encoding(Language::trans('Name')),0,0,'C');
									$this->Cell(50,4,$this->setting->convert_encoding(Language::trans('Move In Date')),0,0,'C');
									$this->Cell(50,4,$this->setting->convert_encoding(Language::trans('Move Out Date')),0,1,'C');
									$this->Line(10, $this->GetY(), 270, $this->GetY());
									$this->Ln(1);
									$t_counter =1 ;
									foreach ($room['house_room_members'] as $member) {
										//dd($member);
										$this->Cell(10,3,$t_counter,0,0,'L');
										$this->Cell(60,3,$member['house_member_name'],0,0,'L');
										$this->Cell(50,3,$member['house_room_member_start_date'],0,0,'C');
										$this->Cell(50,3, ( $member['house_room_member_end_date'] == '0000-00-00 00:00:00' ? '-' : $member['house_room_member_end_date'] ),0,1,'C');
										$t_counter++;
									}

									$this->Ln(3);
									$this->SetFont('Courier', 'B', 9);									
									
									$this->Cell(10,4,'#',0,0,'L');
									$this->Cell(15,4,$this->setting->convert_encoding(Language::trans('Month')),0,0,'C');
									$this->Cell(40,4,$this->setting->convert_encoding(Language::trans('Total Hours')),0,0,'C');
									$this->Cell(40,4,$this->setting->convert_encoding(Language::trans('Avg. kW')),0,0,'C');
									$this->Cell(35,4,$this->setting->convert_encoding(Language::trans('Max. kW')),0,0,'C');
									$this->Cell(35,4,$this->setting->convert_encoding(Language::trans('Min. kW')),0,0,'C');
									$this->Cell(35,4,$this->setting->convert_encoding(Language::trans('Total kWh')),0,0,'C');
								    $this->Cell(30,4,$this->setting->convert_encoding(Language::trans('Total Charges (RM)')),0,1,'C');
								    $this->Line(10, $this->GetY(), 270, $this->GetY());
								    $this->Ln(1);
									

								}else{
									$this->SetFont('Courier', 'B', 9);									
									$this->Cell(20,5,Language::trans('No tenanted'),0,1,'L');
								}
							}

						}

							$payable_amount = Setting::calculate_utility_fee($row->total_usage);
							$is_first_room_header = false;			
							$is_meter_register = true;
							//$total_payable_amount += $payable_amount;
							$room_subtotal += $payable_amount;
							$room_kwh_subtotal_usage += $row->total_usage; 
					
							// table of the listing
							// header of the table
						    $this->SetFont('Courier', 'B', 8);
						    $this->Cell(10,3,$rowNo,0,0,'L');
							$this->Cell(15,3,date('m-Y', strtotime($row->current_date)),0,0,'L');
							$this->Cell(40,3,$row->total_hours,0,0,'C');
							$this->Cell(35,3,number_format($row->average_usage,9),0,0,'R');
							$this->Cell(35,3,number_format($row->max_usage,9),0,0,'R');
							$this->Cell(35,3,number_format($row->min_usage,9),0,0,'R');
							$this->Cell(30,3,number_format($row->total_usage,9),0,0,'R');
							//$this->Cell(30,3,json_encode($room['house_room_members']),0,0,'R');
							$this->Cell(30,3,number_format($payable_amount,2),0,1,'R');	

							

							
					}

					

					$rowNo++;	
				}

			
				
					/*$this->Ln(2);
					$this->Line(10, $this->GetY(), 270, $this->GetY());
					$this->Ln(2);*/

					if(isset($room['meter']['id'])){
						$this->Ln(2);
						$this->Cell(170,3,Language::trans('Subtotal'),0,0,'R');
						$this->Cell(30,3,number_format($room_kwh_subtotal_usage,9),0,0,'R');
						$this->Cell(30,3,number_format($room_subtotal,2),0,1,'R');	
						$project_kwh_total_usage += $room_kwh_subtotal_usage;
						$total_payable_amount += $room_subtotal;
						$this->Ln(2);
						$this->Line(155, $this->GetY(), 270, $this->getY());
						$this->Ln(1);
						$this->Line(155, $this->GetY(), 270, $this->getY());

					}

					/*$this->Ln(2);
					$this->Line(170, $this->GetY(), 270, $this->getY());
					$this->Ln(1);
					$this->Line(170, $this->GetY(), 270, $this->getY());*/

				/*if($is_meter_register == false){

					$this->SetFont('Courier', 'B', 9);									
					$this->Cell(20,3,Language::trans('Room')." ".$room['house_room_name'],0,0,'L');
					$this->Cell(15,3,Language::trans(Setting::SUNWAY_NO_METER_FOUND_LABEL),0,1,'L');

				}	*/					 
					$is_meter_register = false;		
					$this->Ln(2);
			}


		}
		$this->SetFont('Courier', 'B', 8);
		$this->Cell(170,3,Language::trans('Total'),0,0,'R');
		$this->Cell(30,3,number_format($project_kwh_total_usage,9),0,0,'R');
		$this->Cell(30,3,number_format($total_payable_amount,2),0,1,'R');	
		$this->Ln(2);
		$this->Line(170, $this->GetY(), 270, $this->getY());
		$this->Ln(1);
		$this->Line(170, $this->GetY(), 270, $this->getY());

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
