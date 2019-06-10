<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use App\Agency_detail;
use App\Court_detail;
use App\District;
use App\User;
use Carbon\Carbon;
use App\Seizure;
use App\Narcotic;
use App\Narcotic_unit;
use App\Unit;
use App\Ps_detail;
use App\Storage_detail;
use DB;


class MasterMaintenanceController extends Controller
{

    //District::Start

        //Fetching districts
        public function index_district(Request $request)
                {
                    $data= array();

                    $data['districts'] = District::select('district_id','district_name')->orderBy('district_name')->get();
                    

                    return view('district_view',compact('data'));
                }


    //District::End

    //stakeholder::Start
        
        //Add stakeholder
        public function store_stakeholder(Request $request){

            $this->validate ( $request, [ 
                'stakeholder_name' => 'required|max:255|unique:agency_details,agency_name',
                'district' => 'required|max:255'         
            ] ); 

            $stakeholder = strtoupper($request->input('stakeholder_name'));
            $district = strtoupper($request->input('district')); 

            Agency_detail::insert([
                'agency_name'=>$stakeholder,
                'district_for_report'=>$district,
                'created_at'=>Carbon::today(),
                'updated_at'=>Carbon::today()
                ]);

            return 1;
        }

        // Data Table Code for stakeholders
        public function get_all_stakeholders_data(Request $request){
            $columns = array( 
                0 =>'ID', 
                1 =>'STAKEHOLDER',
                2 =>'DISTRICT',
                3=>'ACTION'
            );

            $totalData = Agency_detail::count();

            $totalFiltered = $totalData; 

            $limit = $request->input('length');
            $start = $request->input('start');
            $order = $columns[$request->input('order.0.column')];
            $dir = $request->input('order.0.dir');

            if(empty($request->input('search.value'))){
                $stakeholder = Agency_detail::offset($start)
                                ->limit($limit)
                                ->orderBy('agency_name',$dir)
                                ->get();
                $totalFiltered = Agency_detail::count();
            }
            else{
                $search = strtoupper($request->input('search.value'));
                $stakeholder = Agency_detail::where('agency_id','like',"%{$search}%")
                                    ->orWhere('agency_name','like',"%{$search}%")
                                    ->orWhere('district_for_report','like',"%{$search}%")
                                    ->offset($start)
                                    ->limit($limit)
                                    ->orderBy('agency_name',$dir)
                                    ->get();
                $totalFiltered = Agency_detail::where('agency_id','like',"%{$search}%")
                                        ->orWhere('agency_name','like',"%{$search}%")
                                        ->orWhere('district_for_report','like',"%{$search}%")
                                        ->count();
                }

                $data = array();

                if($stakeholder){
                    foreach($stakeholder as $stakeholder){
                        $nestedData['ID'] = $stakeholder->agency_id;
                        $nestedData['STAKEHOLDER'] = $stakeholder->agency_name;
                        $nestedData['DISTRICT'] = $stakeholder->district_for_report;
                        $nestedData['ACTION'] = "<i class='fa fa-trash' aria-hidden='true'></i>";
        
                        $data[] = $nestedData;
                    }
                        $json_data = array(
                            "draw" => intval($request->input('draw')),
                            "recordsTotal" => intval($totalData),
                            "recordsFiltered" =>intval($totalFiltered),
                            "data" => $data
                        );
                
                        echo json_encode($json_data);
                    }
        
                }

                /*update stakeholder*/
                public function update_stakeholder(Request $request){
                    $this->validate ( $request, [ 
                        'id' => 'required',
                        'stakeholder' => 'required|max:255',
                        'district' => 'required|max:255'          
                    ] ); 

                    
                    $id = $request->input('id');
                    $stakeholder = strtoupper($request->input('stakeholder'));
                    $district = $request->input('district');

                    $data = [
                        'agency_name'=>$stakeholder,
                        'updated_at'=>Carbon::today(),
                        'district_for_report'=>$district

                    ];

                    Agency_detail::where('agency_id',$id)->update($data);
                    
                    return 1;
                
                }

                //deleting stakeholder
                public function destroy_stakeholder(Request $request)
                {
                    $id = $request->input('id');
                    Agency_detail::where('agency_id',$id)->delete();
                    return 1;
                }

                public function destroy_seizure_stakeholder_record(Request $request)
                {
                    $id = $request->input('id');
                    Seizure::where('agency_id',$id)->delete();
                    User::where('agency_id',$id)->delete();
                    Agency_detail::where('agency_id',$id)->delete();
                    return 1;

                }
            //Stakeholder::End

            //Court::Start
          
                //court master maintenance view
                public function index_court(Request $request)
                {
                    $data= array();

                    $data['districts'] = District::select('district_id','district_name')->orderBy('district_name')->get();
                    

                    return view('court_view',compact('data'));
                }

                //showing exisiting courts
                public function get_all_court_details(Request $request){

                    $columns = array( 
                        0 =>'COURT ID', 
                        1 =>'COURT NAME',
                        2 =>'DISTRICT NAME',
                        3=>'ACTION'
                    );

                    $totalData = Court_detail::count();

                    $totalFiltered = $totalData; 

                    $limit = $request->input('length');
                    $start = $request->input('start');
                    $order = $columns[$request->input('order.0.column')];
                    $dir = $request->input('order.0.dir');


                    if(empty($request->input('search.value'))){

                        $court = Court_detail::
                                        join('districts','court_details.district_id','=','districts.district_id')                               
                                        ->offset($start)
                                        ->limit($limit)
                                        ->orderBy('court_name',$dir)
                                        ->get();

                        $totalFiltered = Court_detail::count();
                    }
                    else{
                        $search = strtoupper($request->input('search.value'));
                        $court = Court_detail::
                                        join('districts','court_details.district_id','=','districts.district_id')                               
                                        ->where('court_id','like',"%{$search}%")
                                        ->orWhere('court_name','like',"%{$search}%")
                                        ->orWhere('district_name','like',"%{$search}%")
                                        ->offset($start)
                                        ->limit($limit)
                                        ->orderBy('court_name',$dir)
                                        ->get();
                            
                        $totalFiltered = Court_detail::
                                        join('districts','court_details.district_id','=','districts.district_id')                   
                                        ->where('court_id','like',"%{$search}%")
                                        ->orWhere('court_name','like',"%{$search}%")
                                        ->orWhere('district_name','like',"%{$search}%")
                                        ->count();


                        }

                    $data = Array();

                    if($court){
                        foreach($court as $court){
                            $nestedData['COURT ID'] = $court->court_id;
                            $nestedData['COURT NAME'] = $court->court_name;
                            $nestedData['DISTRICT NAME'] = $court->district_name;
                            $nestedData['ACTION'] = "<i class='fa fa-trash' aria-hidden='true'></i>";
            
                            $data[] = $nestedData;
                        }
                            $json_data = array(
                                "draw" => intval($request->input('draw')),
                                "recordsTotal" => intval($totalData),
                                "recordsFiltered" =>intval($totalFiltered),
                                "data" => $data
                            );
                    
                            echo json_encode($json_data);
                        }
            
                            }

                /*adding new court*/
                public function store_court(Request $request){

                    $this->validate ( $request, [ 
                        
                        'court_name' => 'required|max:255|unique:court_details,court_name',
                        'district_name' => 'required|integer|max:1000'

                    ] ); 

                $court_name=strtoupper($request->input('court_name'));
                $district_name=strtoupper($request->input('district_name'));

                Court_detail::insert([
                    'court_name'=>$court_name,
                    'district_id'=>$district_name,
                    'created_at'=>Carbon::today(),
                    'updated_at'=>Carbon::today()
                    ]);
                return 1;

            }
        
            /*Update court */
            public function update_court(Request $request){
                $this->validate ( $request, [ 
                    'id' => 'required',
                    'court_name' => 'required|max:255'         
                ] ); 

                
                $id = $request->input('id');
                $court_name= strtoupper($request->input('court_name'));

                $data = [
                    'court_name'=>$court_name,
                    'updated_at'=>Carbon::today()
                    ];         
                 Court_detail::where('court_id',$id)->update($data);
                
                 return 1;
            }


            //deleting court details
            public function destroy_court(Request $request)
            {
                $id = $request->input('id');
                Court_detail::where('court_id',$id)->delete();
                return 1;
            }

            public function destroy_seizure_court_record(Request $request){
                $id = $request->input('id');
                Seizure::where('certification_court_id',$id)->delete();
                User::where('court_id',$id)->delete();
                Court_detail::where('court_id',$id)->delete();
                return 1;
            }
            
        //Court:End
            
        //Narcotic:Start

            // Data Table Code for Narcotics
            public function index_narcotic()
            {
                $data=Unit::get();
                return view('narcotic_view',compact('data'));

            }

            public function get_all_narcotics_data(Request $request)
            {
                $columns = array( 
                    0 =>'ID', 
                    1 =>'NARCOTIC',
                    2=>'UNIT',
                    3=>'ACTION'
                );
        
                $totalData = Narcotic::count();
        
                $totalFiltered = $totalData; 
        
                $limit = $request->input('length');
                $start = $request->input('start');
                $order = $columns[$request->input('order.0.column')];
                $dir = $request->input('order.0.dir');
        
                if(empty($request->input('search.value'))){
                    $narcotic = Narcotic_unit::join('units','units.unit_id','=','narcotic_units.unit_id')                               
                                                ->join('narcotics','narcotics.drug_id','=','narcotic_units.narcotic_id')                               
                                                ->offset($start)
                                                ->limit($limit)
                                                ->orderBy('drug_name',$dir)
                                                ->get();
                    $totalFiltered = Narcotic_unit::join('units','units.unit_id','=','narcotic_units.unit_id')                               
                                                    ->join('narcotics','narcotics.drug_id','=','narcotic_units.narcotic_id')                               
                                                    ->count();
                }
                else
                {
                    $search = strtoupper($request->input('search.value'));
                    $narcotic = Narcotic_unit::join('units','units.unit_id','=','narcotic_units.unit_id')                               
                                            ->join('narcotics','narcotics.drug_id','=','narcotic_units.narcotic_id')                               
                                            ->where('unit_name','like',"%{$search}%")
                                            ->orWhere('drug_name','like',"%{$search}%")                                    
                                            ->offset($start)
                                            ->limit($limit)
                                            ->orderBy('drug_name',$dir)
                                            ->get();
                    $totalFiltered = Narcotic_unit::join('units','units.unit_id','=','narcotic_units.unit_id')                               
                                            ->join('narcotics','narcotics.drug_id','=','narcotic_units.narcotic_id')                               
                                            ->where('unit_name','like',"%{$search}%")
                                            ->orWhere('drug_name','like',"%{$search}%")                                            
                                            ->count();
                }
        
                $data = array();
        
                if($narcotic)
                {
                    foreach($narcotic as $narcotic)
                    {
                        $nestedData['ID'] = $narcotic->drug_id;
                        
                        $nestedData['NARCOTIC'] = $narcotic->drug_name;

                        $unit = Unit::get();
                        
                        $option = "";
                        $option = $option."<select class='form-control unit data' style='width:150px'>";
                        foreach($unit as $data1){
                            $option = $option."<option value='".$data1['unit_id']."'";
                            
                            if($data1['unit_id']==$narcotic->unit_id)
                                $option = $option." selected>".$data1['unit_name']."</option>";
                            else
                                $option = $option.">".$data1['unit_name']."</option>";
                        }

                        
                        $option = $option."</select>";
                        
                        $nestedData['UNIT'] = $option;
                        $nestedData['ACTION'] = "<i class='fa fa-trash' aria-hidden='true'></i>";
        
                        $data[] = $nestedData;
                    }
                        $json_data = array(
                            "draw" => intval($request->input('draw')),
                            "recordsTotal" => intval($totalData),
                            "recordsFiltered" =>intval($totalFiltered),
                            "data" => $data
                        );
                
                        echo json_encode($json_data);
                }
            
            }


            //Add Narcotics
            public function store_narcotic(Request $request){
                $this->validate ( $request, [ 
                    'narcotic_name' => 'required|max:255|unique:narcotics,drug_name',
                    'narcotic_unit' => 'required|max:255'         
                ] ); 

                $narcotic = ucwords($request->input('narcotic_name'));
                $unit = $request->input('narcotic_unit');
                
               
                
                Narcotic::insert([
                    'drug_name' => $narcotic,
                    'display' => 'Y',
                    'created_at'=>Carbon::today(),
                    'updated_at'=>Carbon::today()
                ]);

                $narcotic_id = Narcotic::max('drug_id');

                for($i=0;$i<sizeof($unit);$i++){
                    Narcotic_unit::insert([
                        'narcotic_id'=>$narcotic_id,
                        'unit_id'=>$unit[$i],
                        'created_at'=>Carbon::today(),
                        'updated_at'=>Carbon::today()
                    ]);
                }
        
                return 1;



                }

            //update Narcotics
            public function update_narcotics(Request $request){
                $this->validate ( $request, [ 
                    'id' => 'required',
                    'narcotic' => 'required|max:255',
                    'unit' => 'required|max:255'          
                ] ); 

                    
                    $id = $request->input('id');
                    $narcotic = ucwords($request->input('narcotic'));
                    $unit = $request->input('unit');
                    $prev_unit = $request->input('prev_unit');

                    $data = [
                        'drug_name'=>$narcotic,
                        'updated_at'=>Carbon::today(),
                        'display' =>'Y'

                    ];

                    Narcotic::where('drug_id',$id)->update($data);
                    
                    return 1;
                
                }

                //Delete Narcotics
                public function destroy_narcotic(Request $request){
                        $id = $request->input('id');
                        $unit_id= $request->input('unit');
                        $narotic_count=Narcotic_unit::where('narcotic_id',$id)->count();
                        if($narotic_count==1)
                        {
                            Narcotic_unit::where('narcotic_id',$id)->delete();
                            Narcotic::where('drug_id',$id)->delete();
                        }
                        else{
                            Narcotic_unit::where('narcotic_id',$id)
                                            ->where('unit_id',$unit_id)
                                            ->delete();
                        }
                        return 1;
                }

                
                public function destroy_seizure_narcotic_record(Request $request)
                {
                    $id = $request->input('id');
                    $unit_id= $request->input('unit');
                    $narotic_count=Narcotic_unit::where('narcotic_id',$id)->count();
                    Seizure::where('drug_id',$id)
                            ->where('unit_id',$unit_id)
                            ->delete();
                    if($narotic_count==1)
                    {
                        Narcotic_unit::where('narcotic_id',$id)->delete();
                        Narcotic::where('drug_id',$id)->delete();
                    }
                    else
                    {
                        Narcotic_unit::where('narcotic_id',$id)
                                            ->where('unit_id',$unit_id)
                                            ->delete();
                    }
                    return 1;
                }
            
        //Narcotic:ends

        //Unit:start

            // Data Table Code for Unit
            public function get_all_units(Request $request)
            {
                $columns = array( 
                    0 =>'ID', 
                    1 =>'UNIT NAME',
                    2=>'UNIT DEGREE',
                    3=>'ACTION'
                );
                $totalData =Unit::count();
        
                $totalFiltered = $totalData; 
        
                $limit = $request->input('length');
                $start = $request->input('start');
                $order = $columns[$request->input('order.0.column')];
                $dir = $request->input('order.0.dir');
        
                if(empty($request->input('search.value'))){
                    $unit = Unit::offset($start)
                                    ->limit($limit)
                                    ->orderBy('unit_id',$dir)
                                    ->get();
                    $totalFiltered = Unit::count();
                }
                else
                {
                    $search = strtoupper($request->input('search.value'));
                    $unit = Unit::where('unit_id','like',"%{$search}%")
                                        ->orWhere('unit_name','like',"%{$search}%")                                    
                                        ->offset($start)
                                        ->limit($limit)
                                        ->orderBy('unit_name',$dir)
                                        ->get();
                    $totalFiltered = Unit::where('unit_id','like',"%{$search}%")
                                            ->orWhere('unit_name','like',"%{$search}%")                                           
                                            ->count();
                }
        
                $data = array();
        
                if($unit)
                {
                    foreach($unit as $unit)
                    {
                        $nestedData['ID'] = $unit->unit_id;
                        $nestedData['UNIT NAME'] = $unit->unit_name;
                        $nestedData['UNIT DEGREE'] = $unit->unit_degree;
                        $nestedData['ACTION'] = "<i class='fa fa-trash' aria-hidden='true'></i>";
        
                        $data[] = $nestedData;
                    }
                        $json_data = array(
                            "draw" => intval($request->input('draw')),
                            "recordsTotal" => intval($totalData),
                            "recordsFiltered" =>intval($totalFiltered),
                            "data" => $data
                        );
                
                        echo json_encode($json_data);
                }
            
            
            }

            //Add Unit
            public function store_unit(Request $request){
                $this->validate ( $request, [ 
                    'narcotic_unit' => 'required|max:255',
                    'unit_degree' => 'required|integer'   
                ] ); 
                $narcotic_unit = strtoupper($request->input('narcotic_unit')); 
                $unit_degree = $request->input('unit_degree'); 
                Unit::insert([
                    'unit_name'=>$narcotic_unit,
                    'unit_degree' =>$unit_degree,
                    'created_at'=>Carbon::today(),
                    'updated_at'=>Carbon::today()
                    ]);
        
            return 1;
            }

            //update Unit
            public function update_unit(Request $request){
                $this->validate ( $request, [ 
                    'id' => 'required',
                    'narcotic_unit' => 'required|max:255'          
                ] ); 

                
                $id = $request->input('id');
                $unit = $request->input('unit');

                $data = [
                    'unit_name'=>$unit,
                    'updated_at'=>Carbon::today()

                ];

                Unit::where('unit_id',$id)->update($data);
                
                return 1;
            
            }

             //Delete unit
             public function destroy_unit(Request $request){
                $id = $request->input('id');
                Unit::where('unit_id',$id)->delete();
                return 1;
              }

              public function destroy_seizure_unit_record(Request $request)
              {
                  $id = $request->input('id');
                  Seizure::where('seizure_quantity_weighing_unit_id',$id)->delete();
                  Narcotic_unit::where('unit_id',$id)->delete();
                  Unit::where('unit_id',$id)->delete();
                  return 1;
              }



        //Unit:end

        //Police Staion:Start

            public function index_ps(Request $request)
            {
                $data= array();

                $data['districts'] = District::select('district_id','district_name')->orderBy('district_name')->get();
                

                return view('ps_view',compact('data'));
            }


            // Data Table Code for PS
                public function get_all_ps(Request $request)
                {
                    $columns = array( 
                        0 =>'ID', 
                        1 =>'POLICE STATION NAME',
                        3 =>'DISTRICT',
                        2=>'ACTION'
                    );
                    $totalData =Ps_detail::count();
            
                    $totalFiltered = $totalData; 
            
                    $limit = $request->input('length');
                    $start = $request->input('start');
                    $order = $columns[$request->input('order.0.column')];
                    $dir = $request->input('order.0.dir');
            
                    if(empty($request->input('search.value'))){
                        $ps = Ps_detail::join('districts','ps_details.district_id','=','districts.district_id')
                                        ->offset($start)
                                        ->limit($limit)
                                        ->orderBy('ps_id',$dir)
                                        ->get();
                        $totalFiltered = Ps_detail::join('districts','ps_details.district_id','=','districts.district_id')
                                          ->count();
                    }
                    else
                    {
                        $search = strtoupper($request->input('search.value'));
                        $ps = Ps_detail::join('districts','ps_details.district_id','=','districts.district_id')
                                        ->where('ps_name','like',"%{$search}%")
                                        ->offset($start)
                                        ->limit($limit)
                                        ->orderBy('ps_name',$dir)
                                        ->get();
                        $totalFiltered = Ps_detail::join('districts','ps_details.district_id','=','districts.district_id')
                                                    ->where('ps_name','like',"%{$search}%")                                      
                                                    ->count();
                    }
            
                    $data = array();
            
                    if($ps)
                    {
                        foreach($ps as $ps)
                        {
                            $nestedData['ID'] = $ps->ps_id;
                            $nestedData['POLICE STATION NAME'] = $ps->ps_name;
                            $nestedData['DISTRICT'] = $ps->district_name;
                            $nestedData['ACTION'] = "<i class='fa fa-trash' aria-hidden='true'></i>";
            
                            $data[] = $nestedData;
                        }
                            $json_data = array(
                                "draw" => intval($request->input('draw')),
                                "recordsTotal" => intval($totalData),
                                "recordsFiltered" =>intval($totalFiltered),
                                "data" => $data
                            );
                    
                            echo json_encode($json_data);
                    }
                
                
                }

                //Adding new PS
                public function store_ps(Request $request){

                    $this->validate ( $request, [                     
                        'ps_name' => 'required|string|max:255|unique:ps_details,ps_name'                    

                    ] ); //'district_name' => 'required|integer|max:255'
                    $ps_name=strtoupper($request->input('ps_name'));
                    $district_name=strtoupper($request->input('district_name'));

                    Ps_detail::insert([
                        'ps_name'=>$ps_name,
                        'district_id'=>$district_name,
                        'created_at'=>Carbon::today(),
                        'updated_at'=>Carbon::today()
                        ]);
                    return 1;
                }

                //Update PS
                public function update_ps(Request $request){
                    $this->validate ( $request, [ 
                        'id' => 'required',
                        'ps_name' => 'required|max:255',      
                    ] ); 

                        
                        $id = $request->input('id');
                        $ps_name = ucwords($request->input('ps_name'));
                    
                        $data = [
                            'ps_name'=>$ps_name,
                            'updated_at'=>Carbon::today()
                            ];

                        Ps_detail::where('ps_id',$id)->update($data);
                        
                        return 1;
                    
                    }

                //Delete Police Station
                    public function destroy_police_station(Request $request){
                        $id = $request->input('id');
                        Ps_detail::where('ps_id',$id)->delete();
                        return 1;
                    }

                    public function destroy_seizure_police_record(Request $request){
                        $id = $request->input('id');
                        Seizure::where('ps_id',$id)->delete();
                        Ps_detail::where('ps_id',$id)->delete();
                        return 1;

                    }

        //Police Staion:End


        //Storage :start

            // Data Table Code for STORAGE
            public function get_all_storage(Request $request)
            {
                $columns = array( 
                    0 =>'ID', 
                    1 =>'STORAGE NAME',
                    2=>'ACTION'
                );
                $totalData =Storage_detail::count();
        
                $totalFiltered = $totalData; 
        
                $limit = $request->input('length');
                $start = $request->input('start');
                $order = $columns[$request->input('order.0.column')];
                $dir = $request->input('order.0.dir');
        
                if(empty($request->input('search.value'))){
                    $storages = Storage_detail::offset($start)
                                    ->limit($limit)
                                    ->orderBy('storage_id',$dir)
                                    ->get();
                    $totalFiltered = Storage_detail::count();
                }
                else
                {
                    $search = strtoupper($request->input('search.value'));
                    $storages = Storage_detail::where('storage_name','like',"%{$search}%")
                                        ->orWhere('storage_id','like',"%{$search}%")
                                        ->offset($start)
                                        ->limit($limit)
                                        ->orderBy('storage_id',$dir)
                                        ->get();
                    $totalFiltered = Storage_detail::where('storage_name','like',"%{$search}%")
                                            ->orWhere('storage_id','like',"%{$search}%")                                           
                                            ->count();
                }
        
                $data = array();
        
                if($storages)
                {
                    foreach($storages as $storage)
                    {
                        $nestedData['ID'] = $storage->storage_id;
                        $nestedData['STORAGE NAME'] = $storage->storage_name;
                        $nestedData['ACTION'] = "<i class='fa fa-trash' aria-hidden='true'></i>";
        
                        $data[] = $nestedData;
                    }
                        $json_data = array(
                            "draw" => intval($request->input('draw')),
                            "recordsTotal" => intval($totalData),
                            "recordsFiltered" =>intval($totalFiltered),
                            "data" => $data
                        );
                
                        echo json_encode($json_data);
                }
            
            
            }

             //Adding new STORAGE
             public function store_storage(Request $request){

                $this->validate ( $request, [                     
                    'storage_name' => 'required|max:255|unique:storage_details,storage_name'                    

                ] );
                $storage_name=strtoupper($request->input('storage_name'));
              
                Storage_detail::insert([
                    'storage_name'=>$storage_name,
                    'display'=>'Y',
                    'created_at'=>Carbon::today(),
                    'updated_at'=>Carbon::today()
                    ]);
                return 1;
            }

             //Update STORAGE
             public function update_storage(Request $request){
                $this->validate ( $request, [ 
                    'id' => 'required',
                    'storage_name' => 'required|max:255',      
                ] ); 

                    
                    $id = $request->input('id');
                    $storage_name = ucwords($request->input('storage_name'));
                
                    $data = [
                        'storage_name'=>$storage_name,
                        'updated_at'=>Carbon::today()
                        ];

                    Storage_detail::where('storage_id',$id)->update($data);
                    
                    return 1;
                
                }

              //Delete storage
             public function destroy_storage(Request $request){
                $id = $request->input('id');
                Storage_detail::where('storage_id',$id)->delete();
                return 1;
              }

              public function destroy_seizure_storage_record(Request $request)
              {
                  $id = $request->input('id');
                  Seizure::where('storage_location_id',$id)->delete();
                  Storage_detail::where('storage_id',$id)->delete();
                  return 1;
              }

        //Storage:End

        // New User Creation
        
        public function index_user_creation(){

            $data['agency_details'] = Agency_detail::select('agency_id','agency_name')
                                    ->distinct()
                                    ->orderBy('agency_name')
                                    ->get();

            $data['ps_details'] = Ps_detail::select('ps_id','ps_name')
                                    ->distinct()
                                    ->orderBy('ps_name')
                                    ->get();

            $data['court_details'] = Court_detail::select('court_id','court_name')
                                    ->distinct()
                                    ->orderBy('court_name')
                                    ->get();

            $data['district_details'] = district::select('district_id','district_name')
                                        ->distinct()
                                        ->orderBy('district_name')
                                        ->get();

            return view('create_new_user', compact('data'));
        }
            

        public function create_new_user(Request $request){

            $this->validate ( $request, [ 
                'user_id' => 'required|unique:users,user_id|max:30',
                'user_name' => 'required|max:255|unique:users,user_name',
                'password' => 'required|confirmed|max:255',
                'user_type' => 'required|max:30',
                'stakeholder_name' => 'nullable|integer|unique:users,agency_id',
                'court_name' => 'nullable|integer|unique:users,court_id',
                'district_name' => 'nullable|integer|unique:users,district_id',
                'ps_name' => 'nullable|integer|unique:users,ps_id',
                'email_id' => 'required|email|max:100|unique:users,email',
                'contact_no' => 'nullable|integer|unique:users,contact_no'         
            ] ); 


            $user_id = $request->input('user_id');
            $user_name = $request->input('user_name');
            $password = Hash::make($request->input('password'));
            $user_type = $request->input('user_type');
            $agency_name = $request->input('agency_name');
            $court_name = $request->input('court_name');
            $district_name = $request->input('district_name');
            $ps_name = $request->input('ps_name');
            $email = $request->input('email_id');
            $phno = $request->input('contact_no');
            $created_at = Carbon::today();
            $updated_at = Carbon::today();

            User::insert([
                    'user_id' => $user_id,
                    'user_name' => $user_name,
                    'password' => $password,
                    'agency_id' => $agency_name,
                    'ps_id' => $ps_name,
                    'court_id' => $court_name,
                    'district_id' => $district_name,
                    'email' => $email,
                    'contact_no' => $phno,
                    'user_type' => $user_type,
                    'created_at' => $created_at,
                    'updated_at' => $updated_at
            ]);

            return 1;
        }

        //Update Password:Start

        public function update_password(Request $request){

            $this->validate($request,
                    ['new_password' => 'required|confirmed|alpha_dash|min:6|max:15|different:current_password',
                    'current_password' =>'required'
                    ]);
            
            $uid=$request->input('uid');  
            $new_password = Hash::make($request->input('new_password'));
            $cur_password =  $request->input('current_password');
            $data['user'] = User::where('user_id',$uid)
                            ->get();

            if(Hash::check($cur_password, $data['user'][0]['password']))
            {
                User::where('user_id',$uid)
                ->update(['password'=>$new_password, 'updated_at'=>Carbon::today()]);
            
                return 1;
            }

            else
             return 0;

           
            
           
        }

        //Update Password:End
 }

        


