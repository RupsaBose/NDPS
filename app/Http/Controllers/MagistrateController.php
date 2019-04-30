<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Narcotic;
use App\District;
use App\Unit;
use App\Agency_detail;
use App\Court_detail;
use App\Seizure;
use App\Storage_detail;
use App\Ps_detail;
use App\User;
use Carbon\Carbon;
use DB;
use Auth;



class MagistrateController extends Controller
{
    /* For Judicial Magistrate */

    // Show index page for Magistrate's end
    public function show_magistrate_index(Request $request){

        $data = array();
        
        $data['ps'] = Ps_detail::select('ps_id','ps_name')->get();
        
        return view('magistrate_entry_form', compact('data'));
    }


    //Fetch case details of a specific case no.
    public function fetch_case_details(Request $request){
        $court_id =Auth::user()->court_id;
        $ps = $request->input('ps');
        $case_no = $request->input('case_no');
        $case_year = $request->input('case_year');

        $data['case_details'] = Seizure::join('ps_details','seizures.ps_id','=','ps_details.ps_id')
                        ->join('narcotics','seizures.drug_id','=','narcotics.drug_id')
                        ->join('units','seizure_quantity_weighing_unit_id','=','units.unit_id')
                        ->join('storage_details','seizures.storage_location_id','=','storage_details.storage_id')
                        ->join('districts','seizures.district_id','=','districts.district_id')
                        ->join('court_details','seizures.certification_court_id','=','court_details.court_id')
                        ->where([['seizures.ps_id',$ps],['seizures.case_no',$case_no],['seizures.case_year',$case_year],['certification_court_id',$court_id]])
                        ->limit(1)
                        ->get();
        foreach($data['case_details'] as $case_details){
            $case_details->date_of_seizure = Carbon::parse($case_details->date_of_seizure)->format('d-m-Y');
            if($case_details->certification_flag=='Y')
                $case_details->date_of_certification = Carbon::parse($case_details->date_of_certification)->format('d-m-Y');
            
        }

        echo json_encode($data);
    }

    // Do certification
    public function certify(Request $request){
        $this->validate ( $request, [ 
            'ps' => 'required|integer',
            'case_no' => 'required|integer',
            'case_year' => 'required|integer',
            'sample_quantity' => 'required|numeric',
            'sample_weighing_unit' => 'required|integer',
            'certification_date' => 'required|date',
            'magistrate_remarks' => 'nullable|max:255'
        ] ); 

        $ps = $request->input('ps'); 
        $case_no = $request->input('case_no'); 
        $case_year = $request->input('case_year');

        $sample_quantity = $request->input('sample_quantity'); 
        $sample_weighing_unit = $request->input('sample_weighing_unit');         
        $certification_date = Carbon::parse($request->input('certification_date'))->format('Y-m-d');
        $magistrate_remarks = $request->input('magistrate_remarks');

        $data = [
            'certification_flag'=>'Y',
            'quantity_of_sample'=>$sample_quantity,
            'sample_quantity_weighing_unit_id'=>$sample_weighing_unit,
            'date_of_certification'=>$certification_date,
            'magistrate_remarks'=>$magistrate_remarks,
            'updated_at'=>Carbon::today()
        ];

        Seizure::where([['ps_id',$ps],['case_no',$case_no],['case_year',$case_year]])->update($data);
        
        return 1;
        
    }

    public function monthly_report_status(Request $request){
        $court_id =Auth::user()->court_id;
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

        $seizure_details = Seizure::join('ps_details','seizures.ps_id','=','ps_details.ps_id')
                                    ->join('agency_details','seizures.stakeholder_id','=','agency_details.agency_id')
                                    ->join('narcotics','seizures.drug_id','=','narcotics.drug_id')
                                    ->join('units','seizures.seizure_quantity_weighing_unit_id','=','units.unit_id')
                                    ->join('storage_details','seizures.storage_location_id','=','storage_details.storage_id')
                                    ->where([
                                        ['date_of_seizure','>=',$start_date],
                                        ['date_of_seizure','<=',$end_date],
                                        ['certification_court_id',$court_id]
                                    ])
                                    ->orWhere([
                                        ['date_of_certification','>=',$start_date],
                                        ['date_of_certification','<=',$end_date],
                                        ['certification_court_id',$court_id]
                                    ])
                                    ->orWhere([
                                        ['date_of_disposal','>=',$start_date],
                                        ['date_of_disposal','<=',$end_date],
                                        ['certification_court_id',$court_id]
                                    ])
                                    ->get();

        $record = array();

        $report['Sl No'] = 0;

        foreach($seizure_details as $data){
            //PS ID
            $report['PS ID'] = $data->ps_id;

            //Case No
            $report['Case No'] = $data->case_no;

            //PS ID
            $report['Case Year'] = $data->case_year;

            //More Details
            $report['More Details'] = '<img src="images/details_open.png" style="cursor:pointer" class="more_details" alt="More Details">';

            // Serial Number incrementing for every row
            $report['Sl No'] +=1;

            //If submitted date is within 10 days of present date, a new marker will be shown
            if(((strtotime(date('Y-m-d')) - strtotime($data->created_at)) / (60*60*24) <=10))
                $report['Stakeholder Name'] = "<strong>".$data->agency_name."</strong> <small class='label pull-right bg-blue'>new</small>";
            else
                $report['Stakeholder Name'] = "<strong>".$data->agency_name."</strong>";

            //Case_No
            $report['Case_No'] = $data->ps_name." PS / ".$data->case_no." / ".$data->case_year;

            //Narcotic Type
            $report['Narcotic Type'] = $data->drug_name;

            //Certification Status
            if($data->certification_flag=='Y')
                $report['Certification Status'] = 'DONE';
            else if ($data->certification_flag=='N')
                $report['Certification Status'] = 'PENDING';


            //Disposal Status
            if($data->disposal_flag=='Y')
                $report['Disposal Status'] = 'DONE';
            else if ($data->disposal_flag=='N')
                $report['Disposal Status'] = 'NOT DISPOSED';

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

    public function fetch_case_details_for_report(Request $request){
        $court_id =Auth::user()->court_id;
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
                                    ['certification_court_id',$court_id]
                                ])
                                ->select('quantity_of_drug','u1.unit_name AS seizure_unit','date_of_seizure',
                                'date_of_disposal','disposal_quantity','disposal_flag','u3.unit_name AS disposal_unit',
                                'storage_name','court_name','date_of_certification','certification_flag','quantity_of_sample',
                                'u2.unit_name AS sample_unit','remarks','magistrate_remarks')
                                ->get();
                                
        foreach($case_details as $case){
            $case['date_of_seizure'] = Carbon::parse($case['date_of_seizure'])->format('d-m-Y');
            
            if($case['certification_flag']=='Y'){                    
                $case['date_of_certification'] = Carbon::parse($case['date_of_certification'])->format('d-m-Y');
            }
            else{
                $case['date_of_certification'] = 'NA';
                $case['quantity_of_sample'] = 'NA';
                $case['sample_unit'] = '';
                $case['magistrate_remarks'] = 'NA';
            }
            
            if($case['disposal_flag']=='Y'){                    
                $case['date_of_disposal'] = Carbon::parse($case['date_of_disposal'])->format('d-m-Y');
            }
            else{
                $case['date_of_disposal'] = 'NA';
                $case['disposal_quantity'] = 'NA';
                $case['disposal_unit'] = '';
            }

            if($case['remarks']==null)
                $case['remarks']='Not Mentioned';

            
            if($case['magistrate_remarks']==null)
                $case['magistrate_remarks']='Not Mentioned';
        }
        
        echo json_encode($case_details);

    }
    
}
