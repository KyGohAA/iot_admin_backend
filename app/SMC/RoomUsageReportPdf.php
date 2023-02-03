<?php

namespace App\SMC;

use App\Setting;
use App\Language;

use Codedge\Fpdf\Fpdf\Fpdf as Fpdf;

class RoomUsageReportPdf extends Fpdf
{


    public function content($leaf_room_id=null)
    {
        $total_payable_amount = 0;
        // header of the listing -----------------------------------------------------------------------------
        $this->SetFont('Courier', 'B', 12);
        $this->Cell(0, 8, $this->setting->convert_encoding(mb_strtoupper($this->header_title->name)), 0, 1, 'C');
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
        $this->Cell(10, 5, '#', 0, 0, 'L');
        $this->Cell(35, 5, $this->setting->convert_encoding(Language::trans('Date')), 0, 0, 'C');
        $this->Cell(30, 5, $this->setting->convert_encoding(Language::trans('From Time')), 0, 0, 'C');
        $this->Cell(30, 5, $this->setting->convert_encoding(Language::trans('To Time')), 0, 0, 'C');
        $this->Cell(55, 5, $this->setting->convert_encoding(Language::trans('Last Meter Reading')), 0, 0, 'C');
        $this->Cell(55, 5, $this->setting->convert_encoding(Language::trans('Current Meter Reading')), 0, 0, 'C');
        $this->Cell(45, 5, $this->setting->convert_encoding(Language::trans('Current Usage')), 0, 1, 'R');
        $this->Line(10, $this->GetY(), 270, $this->getY());
        // content of the table
        $listing = $this->listing;
        $total = 0;
        $this->Ln(2);
        $houses = isset($this->houses_detail[0]) ? $this->houses_detail[0] : $this->houses_detail;
        foreach ($houses as $house) {
 
            $total = 0;
            $this->SetFont('Courier', 'B', 9);
            $this->Cell(10,5,'---------------------------------------------------------------------------------------------------------------------------------------',0,1,'L');
            $this->Cell(10,5,"|  ".Language::trans('House').' '.':'.' '.$house['house_unit'],0,1,'L');
            $this->Cell(10,5,'---------------------------------------------------------------------------------------------------------------------------------------',0,1,'L');
            
            foreach ($house['house_rooms'] as $room) {

                if($leaf_room_id !=0){
                    if($room['id_house_room'] != $leaf_room_id)
                    {
                        continue;
                    }
                }

                $isMeterRegister   = false;
                $isFirstRoomHeader = true;
                $rowNo             = 0;
                $index             = 0;

                $reading_data = isset($listing[$room['meter']['id']]) ? $listing[$room['meter']['id']] : array();
                foreach ($reading_data as $row) {
                    
                    if ($row->meter_register_id == $room['meter']['id']) {
                        if ($isFirstRoomHeader == true) {
                            $this->SetFont('Courier', 'B', 9);
                            $this->Cell(20, 5, Language::trans('Room') . ' : ' . $room['house_room_name'], 0, 0, 'L');
                            $this->Cell(15,5,Language::trans('Meter Id').' : '.$room['meter']['id'],0,1,'L');
                            $this->Line(10, $this->GetY(), 270, $this->getY());
                        }
                        
                        $payable_amount    = Setting::calculate_utility_fee($row->total_usage);
                        $isFirstRoomHeader = false;
                        $isMeterRegister   = true;
                        $total_payable_amount += $payable_amount;
                        
                        // table of the listing
                        // header of the table
                        $this->SetFont('Courier', 'B', 8);
                        $this->Cell(10, 5, ($index + 1) . '.', 0, 0, 'L');
                        $this->Cell(35, 5, $this->setting->convert_encoding($this->setting->getDate($row->current_date)), 0, 0, 'C');
                        $this->Cell(30, 5, $this->setting->convert_encoding($row->time_started), 0, 0, 'C');
                        $this->Cell(30, 5, $this->setting->convert_encoding($row->time_ended), 0, 0, 'C');
                        $this->Cell(55, 5, $this->setting->convert_encoding($row->last_meter_reading), 0, 0, 'C');
                        $this->Cell(55, 5, $this->setting->convert_encoding($row->current_meter_reading), 0, 0, 'C');
                        $this->Cell(45, 5, $this->setting->convert_encoding($row->current_usage), 0, 1, 'R');
                        $total += $row->current_usage;
                        $index++;
                    }
                    
                }
                
                if ($isMeterRegister == false) {
                    
                    $this->SetFont('Courier', 'B', 9);
                    $this->Cell(20, 3, Language::trans('Room') . " " . $room['house_room_name'], 0, 0, 'L');
                    $this->Cell(15, 3, Language::trans(Setting::SUNWAY_NO_METER_FOUND_LABEL.' ( '.$this->date_range.' ) '), 0, 1, 'L');
                    
                } else {
                	$this->Line(10, $this->GetY(), 270, $this->getY());
                    $this->Cell(215, 5, $this->setting->convert_encoding(Language::trans('Total')) . ' : ', 0, 0, 'R');
                    $this->Cell(45, 5, $this->setting->convert_encoding($this->setting->getDouble($total)), 0, 1, 'R');
                    $this->Ln(2);
                    $this->Line(235, $this->GetY(), 270, $this->getY());
                    $this->Ln(1);
                    $this->Line(235, $this->GetY(), 270, $this->getY());
                }
                $isMeterRegister = false;
                $this->Ln(2);   
            }
        }//end foreach

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