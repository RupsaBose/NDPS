<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use App\Narcotic;
use App\District;
use App\Unit;
use App\Agency_detail;
use App\Court_detail;
use App\Seizure;
use App\User;
use Carbon\Carbon;
use DB;
use Auth;


class MonthlyReportController extends Controller
{

    public function monthly_report_status(Request $request){
        $month_of_report = date('Y-m-d', strtotime('01-'.$request->input('month')));
        
        // For dataTable :: STARTS
        $columns = array( 
            0 =>'Sl No',
            1 =>'Stakeholder Name',
            2 =>'Submission Status',
            5 =>'Action'
        );

        $sql = "select distinct agency_details.agency_name, seizures.submit_flag, seizures.created_at
                from agency_details left join (select * from seizures where month_of_report = '".$month_of_report."') as seizures 
                on agency_details.agency_id = seizures.agency_id order by agency_details.agency_name";

        $status = DB::select($sql);

        $record = array();

        $report['Sl No'] = 0;

        foreach($status as $data){
            // Serial Number incrementing for every row
            $report['Sl No'] +=1;

            //If submitted date is within 10 days of present date, a new marker will be shown
            if(strtotime(date('Y-m-d')) - strtotime($data->created_at) <=10)
                $report['Stakeholder Name'] = "<strong>".$data->agency_name."</strong> <small class='label pull-right bg-blue'>new</small>";
            else
                $report['Stakeholder Name'] = "<strong>".$data->agency_name."</strong>";

            // Initializing with 'NA' value
            $report['Action'] = 'NA';

            if($data->submit_flag=='S'){
                // Green Light
                $report['Submission Status'] = "<span style='height: 25px;width: 25px;
                                                background-color: green;border-radius: 50%;
                                                display: inline-block;' title='Report Submitted'></span> <br> Submitted On ". Carbon::parse($data->created_at)->format('d-m-Y');
                
                // As submission completed, view report and unlock submission button appears
                $report['Action'] = "<i class='fas fa-envelope-open-text' title='View Report'></i> <br> 
                                    <i class='fas fa-lock-open' title='Unlock Submission'></i>";
            }
            else if ($data->submit_flag=='N')
                // Yellow Light
                $report['Submission Status'] = "<span style='height: 25px;width: 25px;
                                                background-color: yellow;border-radius: 50%;
                                                display: inline-block;'  title='Drafted But Not Submitted'></span>";
            else
                // Red Light
                $report['Submission Status'] = "<span style='height: 25px;width: 25px;
                                                background-color: red;border-radius: 50%;
                                                display: inline-block;'  title='Yet To Start Working'></span>";


            $record[] = $report;
        }

        $json_data = array(
            "draw" => intval($request->input('draw')),
            "recordsTotal" => intval(sizeof($record)),
            "recordsFiltered" =>intval(sizeof($record)),
            "data" => $record
        );
        
        echo json_encode($json_data);


    }
    
    public function show_monthly_report(Request $request){
        $month_of_report = date('Y-m-d', strtotime('01-'.$request->input('month_of_report')));
        $stakeholder = $request->input('stakeholder');

        
        // For dataTable :: STARTS
        $columns = array( 
            0 =>'Narcotic Nature',
            1 =>'Seize Quantity',
            2 =>'Seizure Date',
            3 =>'Disposal Date',
            4 =>'Disposal Quantity',
            5 =>'Not Disposed Quantity',
            6 =>'Storage Place',
            7 =>'Case Details',
            8 =>'Where',
            9 =>'Certification Date',
            10 => 'Remarks',
            11 => 'Sl No'
        );

        $sql="select s.*,u1.unit_name seizure_unit, s.disposal_quantity, u2.unit_name disposal_unit,
        s.undisposed_quantity,u3.unit_name undisposed_unit_name, court_details.*, districts.*
        from seizures s inner join units u1 on cast(s.unit_name as int)=u1.unit_id 
        left join units u2 on cast(s.unit_of_disposal_quantity as int)=u2.unit_id 
        left join units u3 on cast(s.undisposed_unit as int)=u3.unit_id
        left join court_details on s.certification_court_id = court_details.court_id
        left join districts on s.district_id = districts.district_id
        where s.submit_flag='S' and s.agency_id= ".$stakeholder." and 
        s.month_of_report = '".$month_of_report."' order by seizure_id";

        $data['seizures']=DB::select($sql);

        $record = array();

        $report['Sl No'] = 0;
        foreach($data['seizures'] as $seizures){
            $report['Sl No'] = $report['Sl No'] + 1;
            $report['Narcotic Nature'] = $seizures->drug_name;
            $report['Seize Quantity'] = $seizures->quantity_of_drug. " ".$seizures->seizure_unit;
            $report['Seizure Date'] = Carbon::parse($seizures->date_of_seizure)->format('d-m-Y');
            $report['Disposal Date'] = Carbon::parse($seizures->date_of_disposal)->format('d-m-Y');
            $report['Disposal Quantity'] = $seizures->disposal_quantity. " ".$seizures->disposal_unit;
            $report['Not Disposed Quantity'] = $seizures->undisposed_quantity. " ".$seizures->undisposed_unit_name;
            $report['Storage Place'] = $seizures->storage_location;
            $report['Case Details'] = $seizures->case_details;
            $report['Where'] = $seizures->court_name;
            $report['Certification Date'] = Carbon::parse($seizures->date_of_certification)->format('d-m-Y');
            $report['Remarks'] = $seizures->remarks;

            $record[] = $report;

        }

        $json_data = array(
            "draw" => intval($request->input('draw')),
            "recordsTotal" => intval(sizeof($record)),
            "recordsFiltered" =>intval(sizeof($record)),
            "data" => $record
        );
        
        echo json_encode($json_data);

    }

    //for stakeholder's own monthwise previous report

    public function index_previous_report(){
        
        $agency_details = Agency_detail::where('agency_id',Auth::user()->stakeholder_id)
                                            ->get(); 

        return view('previous_report',compact('agency_details'));      
    }

    public function show_previous_report(Request $request){
         
        $month_of_report = date('Y-m-d', strtotime('01-'.$request->input('month_of_report')));
        $stakeholder = Auth::user()->stakeholder_id;

        
        // For dataTable :: STARTS
        $columns = array( 
            0 =>'Narcotic Nature',
            1 =>'Seize Quantity',
            2 =>'Seizure Date',
            3 =>'Disposal Date',
            4 =>'Disposal Quantity',
            5 =>'Not Disposed Quantity',
            6 =>'Storage Place',
            7 =>'Case Details',
            8 =>'Where',
            9 =>'Certification Date',
            10 => 'Remarks',
            11 => 'Sl No'
        );

        $sql="select s.*,u1.unit_name seizure_unit, s.disposal_quantity, u2.unit_name disposal_unit,
        s.undisposed_quantity,u3.unit_name undisposed_unit_name, court_details.*, districts.*
        from seizures s left join units u1 on cast(s.unit_name as int)=u1.unit_id 
        left join units u2 on cast(s.unit_of_disposal_quantity as int)=u2.unit_id 
        left join units u3 on cast(s.undisposed_unit as int)=u3.unit_id
        left join court_details on s.certification_court_id = court_details.court_id
        left join districts on s.district_id = districts.district_id
        where s.submit_flag='S' and s.agency_id= ".$stakeholder." and 
        s.month_of_report = '".$month_of_report."' order by seizure_id";

        $data['seizures']=DB::select($sql);

        $record = array();

        $report['Sl No'] = 0;
        foreach($data['seizures'] as $seizures){
            $report['Sl No'] = $report['Sl No'] + 1;
            $report['Narcotic Nature'] = $seizures->drug_name;
            $report['Seize Quantity'] = $seizures->quantity_of_drug. " ".$seizures->seizure_unit;
            
            if(empty($seizures->date_of_seizure))
                $report['Seizure Date'] ='';
            else
                $report['Seizure Date'] = Carbon::parse($seizures->date_of_seizure)->format('d-m-Y');
            
            if(empty($seizures->date_of_disposal))
                $report['Disposal Date'] ='';
            else
                $report['Disposal Date'] = Carbon::parse($seizures->date_of_disposal)->format('d-m-Y');

            $report['Disposal Quantity'] = $seizures->disposal_quantity. " ".$seizures->disposal_unit;
            $report['Not Disposed Quantity'] = $seizures->undisposed_quantity. " ".$seizures->undisposed_unit_name;
            $report['Storage Place'] = $seizures->storage_location;
            $report['Case Details'] = $seizures->case_details;
            $report['Where'] = $seizures->court_name;

            if(empty($seizures->date_of_certification))
                $report['Certification Date'] = '';
            else
                $report['Certification Date'] = Carbon::parse($seizures->date_of_certification)->format('d-m-Y');
            
            
            $report['Remarks'] = $seizures->remarks;

            $record[] = $report;

        }

        $json_data = array(
            "draw" => intval($request->input('draw')),
            "recordsTotal" => intval(sizeof($record)),
            "recordsFiltered" =>intval(sizeof($record)),
            "data" => $record
        );
        
        echo json_encode($json_data);

    }



}
