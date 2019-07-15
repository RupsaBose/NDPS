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



class MagistrateController extends Controller
{
    /* For Judicial Magistrate */

    // Show index page for Magistrate's end
    public function show_magistrate_index(Request $request){

        $data = array();
        
        $data['ps'] = Ps_detail::select('ps_id','ps_name')
                                ->orderBy('ps_name')
                                ->get();
        $data['agencies'] = Agency_detail::select('agency_id','agency_name')
                                            ->orderBy('agency_name')
                                            ->get();
        
        return view('magistrate_entry_form', compact('data'));
    }


    //Fetch case details of a specific case no.
    public function fetch_case_details(Request $request){
        $court_id =Auth::user()->court_id;
        $stakeholder = $request->input('stakeholder');
        $stakeholder_type = $request->input('stakeholder_type');
        $case_no = $request->input('case_no');
        $case_year = $request->input('case_year');

        if($stakeholder_type == 'ps'){
            $data['case_details'] = Seizure::join('ps_details','seizures.ps_id','=','ps_details.ps_id')
                            ->join('narcotics','seizures.drug_id','=','narcotics.drug_id')
                            ->join('units AS u1','seizures.seizure_quantity_weighing_unit_id','=','u1.unit_id')
                            ->leftjoin('units AS u2','seizures.sample_quantity_weighing_unit_id','=','u2.unit_id')                        
                            ->join('storage_details','seizures.storage_location_id','=','storage_details.storage_id')
                            ->join('court_details','seizures.certification_court_id','=','court_details.court_id')
                            ->join('districts','seizures.district_id','=','districts.district_id')
                            ->where([['seizures.ps_id',$stakeholder],['seizures.case_no',$case_no],['seizures.case_year',$case_year]])                        
                            ->select('drug_name','narcotics.display','narcotics.drug_id','quantity_of_drug','seizure_quantity_weighing_unit_id',
                                    'u1.unit_name AS seizure_unit','date_of_seizure','storage_name',
                                    'court_name','districts.district_id','district_name','date_of_certification',
                                    'certification_flag','quantity_of_sample','u2.unit_name AS sample_unit',
                                    'remarks','magistrate_remarks')
                            ->get();
        }
        else if($stakeholder_type='agency'){
            $data['case_details'] = Seizure::join('agency_details','seizures.agency_id','=','agency_details.agency_id')
                            ->join('narcotics','seizures.drug_id','=','narcotics.drug_id')
                            ->join('units AS u1','seizures.seizure_quantity_weighing_unit_id','=','u1.unit_id')
                            ->leftjoin('units AS u2','seizures.sample_quantity_weighing_unit_id','=','u2.unit_id')                        
                            ->join('storage_details','seizures.storage_location_id','=','storage_details.storage_id')
                            ->join('court_details','seizures.certification_court_id','=','court_details.court_id')
                            ->join('districts','seizures.district_id','=','districts.district_id')
                            ->where([['seizures.agency_id',$stakeholder],['seizures.case_no',$case_no],['seizures.case_year',$case_year]])                        
                            ->select('drug_name','narcotics.display','narcotics.drug_id','quantity_of_drug','seizure_quantity_weighing_unit_id',
                                    'u1.unit_name AS seizure_unit','date_of_seizure','storage_name',
                                    'court_name','districts.district_id','district_name','date_of_certification',
                                    'certification_flag','quantity_of_sample','u2.unit_name AS sample_unit',
                                    'remarks','magistrate_remarks')
                            ->get();
        }

        foreach($data['case_details'] as $case_details){
            $case_details->date_of_seizure = Carbon::parse($case_details->date_of_seizure)->format('d-m-Y');
            if($case_details->certification_flag=='Y')
                $case_details->date_of_certification = Carbon::parse($case_details->date_of_certification)->format('d-m-Y');           
            if($case_details->magistrate_remarks==null)
                $case_details->magistrate_remarks = "";
        }

        echo json_encode($data);
    }


    // Narcotic wise unit fetching
    public function narcotic_units(Request $request){

        $narcotic = $request->input('narcotic'); 
        $display = $request->input('display'); 

        if($display=='Y'){
            $data['units']=Narcotic_unit::join('units',"narcotic_units.unit_id","=","units.unit_id")
                                    ->select('units.unit_id','unit_name')
                                    ->where('narcotic_id','=', $narcotic )
                                    ->get();
        }
        else if($display=='N'){
            $data['units']= Unit::get();
        }

        echo json_encode($data);

    }

    // Do certification
    public function certify(Request $request){
        $this->validate ( $request, [ 
            'stakeholder' => 'required|integer',
            'case_no' => 'required|integer',
            'case_year' => 'required|integer',
            'narcotic_type' => 'required|integer',
            'sample_quantity' => 'required|numeric',
            'sample_weighing_unit' => 'required|integer',
            'certification_date' => 'required|date',
            'magistrate_remarks' => 'nullable|max:255'
        ] ); 

        $stakeholder = $request->input('stakeholder'); 
        $stakeholder_type = $request->input('stakeholder_type'); 
        $case_no = $request->input('case_no'); 
        $case_year = $request->input('case_year');
        $narcotic_type = $request->input('narcotic_type');

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

        if($stakeholder_type=='ps'){
            Seizure::where([
                ['ps_id',$stakeholder],
                ['case_no',$case_no],
                ['case_year',$case_year],
                ['drug_id',$narcotic_type]
            ])->update($data);
        }
        else if($stakeholder_type=='agency'){
            Seizure::where([
                ['agency_id',$stakeholder],
                ['case_no',$case_no],
                ['case_year',$case_year],
                ['drug_id',$narcotic_type]
            ])->update($data);
        }
        
        return 1;
        
    }

    public function monthly_report_status(Request $request){
        $court_id =Auth::user()->court_id;
        $start_date = date('Y-m-d', strtotime('01-'.$request->input('month')));
        $end_date = date('Y-m-d', strtotime($start_date. ' +30 days'));
        
        // For dataTable :: STARTS
        $columns = array( 
            0 =>'Stakeholder ID',
            1 =>'Stakeholder Type',
            2=>'Case No',
            3=>'Case Year',
            4=>'More Details',
            5=>'Sl No',
            6=>'Stakeholder Name',
            7 =>'Case_No',
            8 =>'Narcotic Type',
            9 =>'Certification Status',
            10 =>'Disposal Status'
        );

        // Fetching unique Case No. As Multiple Row May Exist For A Single Case No.
        $cases = Seizure::leftjoin('ps_details','seizures.ps_id','=','ps_details.ps_id')
                        ->leftjoin('agency_details','seizures.agency_id','=','agency_details.agency_id')
                        ->where([
                            ['seizures.created_at','>=',$start_date],
                            ['seizures.created_at','<=',$end_date],
                            ['certification_court_id',$court_id]
                        ])
                        ->orWhere([
                            ['seizures.updated_at','>=',$start_date],
                            ['seizures.updated_at','<=',$end_date],
                            ['certification_court_id',$court_id]
                        ])
                        ->select('seizures.ps_id','seizures.agency_id','case_no','case_year','seizures.created_at','ps_name','agency_name')
                        ->distinct()
                        ->get();
        
        $record = array();

        $report['Sl No'] = 0;
        
        foreach($cases as $case){
            //Stakeholder ID and Stakeholder Type
            if($case->ps_id!=null){
                $report['Stakeholder ID'] = $case->ps_id;
                $report['Stakeholder Type'] = "ps";
            }
            else{
                $report['Stakeholder ID'] = $case->agency_id;
                $report['Stakeholder Type'] = "agency";
            }

            //Case No
            $report['Case No'] = $case->case_no;

            //Case Year
            $report['Case Year'] = $case->case_year;

            //More Details
            $report['More Details'] = '<img src="images/details_open.png" style="cursor:pointer" class="more_details" alt="More Details">';

            // Serial Number incrementing for every row
            $report['Sl No'] +=1;

            //If Case Initiated By Any Agency Other Than NCB
            if($case->ps_id!=null && $case->agency_id!=null){
                //If submitted date is within 10 days of present date, a new marker will be shown
                if(((strtotime(date('Y-m-d')) - strtotime($case->created_at)) / (60*60*24) <=10))
                    $report['Stakeholder Name'] = "<strong>".$case->ps_name."</strong><br>(Case Initiated By: ".$case->agency_name.")<small class='label pull-right bg-blue'>new</small>";
                else
                    $report['Stakeholder Name'] = "<strong>".$case->ps_name."</strong><br>(Case Initiated By: ".$case->agency_name.")";
            }
            //If Case Initiated By Any PS
            else if($case->ps_id!=null && $case->agency_id==null){
                //If submitted date is within 10 days of present date, a new marker will be shown
                if(((strtotime(date('Y-m-d')) - strtotime($case->created_at)) / (60*60*24) <=10))
                    $report['Stakeholder Name'] = "<strong>".$case->ps_name."</strong><small class='label pull-right bg-blue'>new</small>";
                else
                    $report['Stakeholder Name'] = "<strong>".$case->ps_name."</strong>";
            }
            //If Case Initiated By NCB
            else if($case->ps_id==null){
                //If submitted date is within 10 days of present date, a new marker will be shown
                if(((strtotime(date('Y-m-d')) - strtotime($case->created_at)) / (60*60*24) <=10))
                    $report['Stakeholder Name'] = "<strong>".$case->agency_name."</strong> <small class='label pull-right bg-blue'>new</small>";
                else
                    $report['Stakeholder Name'] = "<strong>".$case->agency_name."</strong>";
            }

            //Case_No
            if($case->ps_id!=null){
                $report['Case_No'] = $case->ps_name." / ".$case->case_no." / ".$case->case_year;
            }
            else{
                $report['Case_No'] = $case->agency_name." / ".$case->case_no." / ".$case->case_year;
            }

            // Fetching details of respective Case No.  
            if($case->ps_id!=null){ 
                $seizure_details = Seizure::join('narcotics','seizures.drug_id','=','narcotics.drug_id')
                                            ->join('units','seizures.seizure_quantity_weighing_unit_id','=','units.unit_id')                                        
                                            ->where([
                                                ['seizures.ps_id',$case->ps_id],
                                                ['case_no',$case->case_no],
                                                ['case_year',$case->case_year]
                                            ])                                        
                                            ->get();
            }
            else{
                $seizure_details = Seizure::join('narcotics','seizures.drug_id','=','narcotics.drug_id')
                                            ->join('units','seizures.seizure_quantity_weighing_unit_id','=','units.unit_id')                                        
                                            ->where([
                                                ['seizures.agency_id',$case->agency_id],
                                                ['case_no',$case->case_no],
                                                ['case_year',$case->case_year]
                                            ])                                        
                                            ->get();
            }
            
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

    public function fetch_case_details_for_report(Request $request){
        $court_id =Auth::user()->court_id;
        $stakeholder_id = $request->input('stakeholder_id');
        $stakeholder_type = $request->input('stakeholder_type');
        $case_no = $request->input('case_no');
        $case_year = $request->input('case_year');

        if($stakeholder_type=='ps'){
            $case_details = Seizure::join('ps_details','seizures.ps_id','=','ps_details.ps_id')
                                    ->join('narcotics','seizures.drug_id','=','narcotics.drug_id')
                                    ->join('units AS u1','seizures.seizure_quantity_weighing_unit_id','=','u1.unit_id')
                                    ->leftjoin('units AS u2','seizures.sample_quantity_weighing_unit_id','=','u2.unit_id')
                                    ->leftjoin('units AS u3','seizures.disposal_quantity_weighing_unit_id','=','u3.unit_id')
                                    ->join('storage_details','seizures.storage_location_id','=','storage_details.storage_id')
                                    ->join('court_details','seizures.certification_court_id','=','court_details.court_id')
                                    ->where([
                                        ['seizures.ps_id',$stakeholder_id],
                                        ['case_no',$case_no],
                                        ['case_year',$case_year],
                                        ['certification_court_id',$court_id]
                                    ])
                                    ->select('drug_name','quantity_of_drug','u1.unit_name AS seizure_unit','date_of_seizure',
                                    'date_of_disposal','disposal_quantity','disposal_flag','u3.unit_name AS disposal_unit',
                                    'storage_name','court_name','date_of_certification','certification_flag','quantity_of_sample',
                                    'u2.unit_name AS sample_unit','remarks','magistrate_remarks')
                                    ->get();
        }
        else if($stakeholder_type=='agency'){
            $case_details = Seizure::join('agency_details','seizures.agency_id','=','agency_details.agency_id')
                                    ->join('narcotics','seizures.drug_id','=','narcotics.drug_id')
                                    ->join('units AS u1','seizures.seizure_quantity_weighing_unit_id','=','u1.unit_id')
                                    ->leftjoin('units AS u2','seizures.sample_quantity_weighing_unit_id','=','u2.unit_id')
                                    ->leftjoin('units AS u3','seizures.disposal_quantity_weighing_unit_id','=','u3.unit_id')
                                    ->join('storage_details','seizures.storage_location_id','=','storage_details.storage_id')
                                    ->join('court_details','seizures.certification_court_id','=','court_details.court_id')
                                    ->where([
                                        ['seizures.agency_id',$stakeholder_id],
                                        ['case_no',$case_no],
                                        ['case_year',$case_year],
                                        ['certification_court_id',$court_id]
                                    ])
                                    ->select('drug_name','quantity_of_drug','u1.unit_name AS seizure_unit','date_of_seizure',
                                    'date_of_disposal','disposal_quantity','disposal_flag','u3.unit_name AS disposal_unit',
                                    'storage_name','court_name','date_of_certification','certification_flag','quantity_of_sample',
                                    'u2.unit_name AS sample_unit','remarks','magistrate_remarks')
                                    ->get();
        }
                                
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
