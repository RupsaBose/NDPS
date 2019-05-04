<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Narcotic;
use App\District;
use App\Unit;
use App\Narcotic_unit;
use App\Agency_detail;
use App\Court_detail;
use App\Seizure;
use App\Storage_detail;
use App\Ps_detail;
use App\User;
use Carbon\Carbon;
use DB;
use Auth;


class SpecialCourtController extends Controller
{
    public function index_dashboard(){   
        $district_id =Auth::user()->district_id;   

        $data['total_seizure'] = Seizure::where('district_id',$district_id)
                                            ->count();
        $data['total_disposed'] = Seizure::where([
                                                ['disposal_flag','Y'],
                                                ['district_id',$district_id]
                                            ])
                                            ->count();
        
        $data['total_undisposed'] = $data['total_seizure'] - $data['total_disposed'];

        return view('dashboard_special_court',compact('data'));

    }


    public function monthly_report_status(Request $request){
        $district_id =Auth::user()->district_id;   

        $start_date = date('Y-m-d', strtotime('01-'.$request->input('month')));
        $end_date = date('Y-m-d', strtotime($start_date. ' +30 days'));
        
        // For dataTable :: STARTS
        $columns = array( 
            0 =>'PS ID',
            1=>'Case No',
            2=>'Case Year',
            3=>'More Details',
            4=>'Sl No',
            5=>'Stakeholder Name',
            6 =>'Case_No',
            7 =>'Narcotic Type',
            8 =>'Certification Status',
            9 =>'Disposal Status'
        );

        // Fetching unique Case No. As Multiple Row May Exist For A Single Case No.
        $cases = Seizure::join('ps_details','seizures.ps_id','=','ps_details.ps_id')
                        ->join('agency_details','seizures.stakeholder_id','=','agency_details.agency_id')
                        ->where([
                            ['seizures.created_at','>=',$start_date],
                            ['seizures.created_at','<=',$end_date],
                            ['seizures.district_id',$district_id]
                        ])
                        ->orWhere([
                            ['seizures.updated_at','>=',$start_date],
                            ['seizures.updated_at','<=',$end_date],
                            ['seizures.district_id',$district_id]
                        ])
                        ->select('seizures.ps_id','case_no','case_year','seizures.created_at','ps_name','agency_name')
                        ->distinct()
                        ->get();
        
        $record = array();

        $report['Sl No'] = 0;
        
        foreach($cases as $case){
            //PS ID
            $report['PS ID'] = $case->ps_id;

            //Case No
            $report['Case No'] = $case->case_no;

            //PS ID
            $report['Case Year'] = $case->case_year;

            //More Details
            $report['More Details'] = '<img src="images/details_open.png" style="cursor:pointer" class="more_details" alt="More Details">';

            // Serial Number incrementing for every row
            $report['Sl No'] +=1;

            //If submitted date is within 10 days of present date, a new marker will be shown
            if(((strtotime(date('Y-m-d')) - strtotime($case->created_at)) / (60*60*24) <=10))
                $report['Stakeholder Name'] = "<strong>".$case->agency_name."</strong> <small class='label pull-right bg-blue'>new</small>";
            else
                $report['Stakeholder Name'] = "<strong>".$case->agency_name."</strong>";

            //Case_No
            $report['Case_No'] = $case->ps_name." PS / ".$case->case_no." / ".$case->case_year;

            // Fetching details of respective Case No.   
            $seizure_details = Seizure::join('narcotics','seizures.drug_id','=','narcotics.drug_id')
                                        ->join('units','seizures.seizure_quantity_weighing_unit_id','=','units.unit_id')                                        
                                        ->where([
                                            ['seizures.ps_id',$case->ps_id],
                                            ['case_no',$case->case_no],
                                            ['case_year',$case->case_year]
                                        ])                                        
                                        ->get();
            
            $certification_done_flag = 0;
            $certification_pending_flag = 0;
            $partial_certification_flag = 0;

            $disposal_done_flag = 0;
            $disposal_pending_flag = 0;
            $partial_disposal_flag = 0;

            $report['Narcotic Type'] = "<ul type='square'>";
            foreach($seizure_details as $key => $seizure){
                //Narcotic Type
                $report['Narcotic Type'] .= "<li>".$seizure->drug_name."</li>";

                 //Certification Status
                if($seizure->certification_flag=='Y'){
                    $certification_done_flag = 1;
                }
                else{
                    $certification_pending_flag = 1;
                }

                if($certification_done_flag == 1 && $certification_pending_flag == 1){
                    $partial_certification_flag = 1;
                }

                //Disposal Status
                if($seizure->disposal_flag=='Y'){
                    $disposal_done_flag = 1;
                }
                else{
                    $disposal_pending_flag = 1;
                }

                if($disposal_done_flag == 1 && $disposal_pending_flag == 1){
                    $partial_disposal_flag = 1;
                }
            }
            $report['Narcotic Type'] .= "</ul>";

            //Certification Status                
            if($partial_certification_flag == 1){
                $report['Certification Status'] = 'PARTIALLY CERTFIED';
            }
            else if($certification_done_flag == 1 && $certification_pending_flag == 0){
                $report['Certification Status'] = 'COMPLETED';
            }
            else if($certification_done_flag == 0 && $certification_pending_flag == 1){
                $report['Certification Status'] = 'PENDING';
            }


            //Disposal Status                
            if($partial_disposal_flag == 1){
                $report['Disposal Status'] = 'PARTIALLY DISPOSED';
            }
            else if($disposal_done_flag == 1 && $disposal_pending_flag == 0){
                $report['Disposal Status'] = 'DISPOSED';
            }
            else if($disposal_done_flag == 0 && $disposal_pending_flag == 1){
                $report['Disposal Status'] = 'PENDING';
            }

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

    public function fetch_case_details(Request $request){
        $district_id =Auth::user()->district_id; 
        
        $ps_id = $request->input('ps_id');
        $case_no = $request->input('case_no');
        $case_year = $request->input('case_year');

        $case_details = Seizure::join('ps_details','seizures.ps_id','=','ps_details.ps_id')
                                ->join('agency_details','seizures.stakeholder_id','=','agency_details.agency_id')
                                ->join('narcotics','seizures.drug_id','=','narcotics.drug_id')
                                ->join('units AS u1','seizures.seizure_quantity_weighing_unit_id','=','u1.unit_id')
                                ->leftjoin('units AS u2','seizures.sample_quantity_weighing_unit_id','=','u2.unit_id')
                                ->leftjoin('units AS u3','seizures.disposal_quantity_weighing_unit_id','=','u3.unit_id')
                                ->join('storage_details','seizures.storage_location_id','=','storage_details.storage_id')
                                ->leftjoin('court_details','seizures.certification_court_id','=','court_details.court_id')
                                ->where([
                                    ['seizures.ps_id',$ps_id],
                                    ['case_no',$case_no],
                                    ['case_year',$case_year],
                                    ['seizures.district_id',$district_id]
                                ])
                                ->select('drug_name','quantity_of_drug','u1.unit_name AS seizure_unit','date_of_seizure',
                                'date_of_disposal','disposal_quantity','disposal_flag','u3.unit_name AS disposal_unit',
                                'storage_name','court_name','date_of_certification','certification_flag','quantity_of_sample',
                                'u2.unit_name AS sample_unit','remarks','magistrate_remarks')
                                ->get();
                                
        foreach($case_details as $case){
            $case['date_of_seizure'] = Carbon::parse($case['date_of_seizure'])->format('d-m-Y');
            
            if($case['certification_flag']=='Y'){                    
                $case['date_of_certification'] = Carbon::parse($case['date_of_certification'])->format('d-m-Y');
                $case['certification_flag'] = 'Certification Completed';
            }
            else{
                $case['certification_flag'] = 'PENDING';
                $case['date_of_certification'] = 'NA';
                $case['quantity_of_sample'] = 'NA';
                $case['sample_unit'] = '';
                $case['magistrate_remarks'] = 'NA';
            }
            
            if($case['disposal_flag']=='Y'){                    
                $case['date_of_disposal'] = Carbon::parse($case['date_of_disposal'])->format('d-m-Y');
                $case['disposal_flag'] = 'Disposed';
            }
            else{
                $case['date_of_disposal'] = 'NA';
                $case['disposal_quantity'] = 'NA';
                $case['disposal_unit'] = '';
                $case['disposal_flag'] = 'PENDING';
            }

            if($case['remarks']==null)
                $case['remarks']='Nothing Mentioned';

            
            if($case['magistrate_remarks']==null)
                $case['magistrate_remarks']='Nothing Mentioned';
        }
        
        echo json_encode($case_details);

    }
}
