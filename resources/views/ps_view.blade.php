@extends('layouts.app') @section('content')
<!-- Main content -->
<div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">Add New Police Station</h3>
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
            </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <div class="row">                
                <div class="col-md-3 form-group required">
                    <label class="control-label">Police Station's Name</label>
                        <input type="text" class="form-control ps_name" name="ps_name" id="ps_name">
                </div>
                <div class="col-md-4 form-group required">
                    <label class="control-label district_name">District</label><br>
                        <select class="select2"  name="district_name" id="district_name">
                             <option value="">Select District</option>
                             @foreach($data['districts']  as $data1)
                             	<option value="{{$data1['district_id']}}">{{$data1['district_name']}} </option>
							 @endforeach
                         </select>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>&nbsp;</label>
                        <button type="button" class="form-control btn-success btn btn-primary" name="add_new_ps" id="add_new_ps">Add New PS
                    </div>
                </div>
                <!-- /.col -->  
    
            </div>
            <!-- /.row -->
        </div>
</div>

<div class="box box-default" id="show_all_data">
    <div class="box-header with-border">
        <h3 class="box-title"> Police Stations' Details</h3>
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
        </div>
    </div>
    <!-- /.box-header -->
    <div class="box-body">
            <table class="table table-striped table-bordered" id="show_ps_details">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>POLICE STATION'S NAME</th>
                            <th>DISTRICT</th>
                            <th>Action</th>
                        </tr>
                    </thead>                    
            </table>
    </div>
</div>

<hr>
         
<br> <br>

<!--loader starts-->

<div class="col-md-offset-5 col-md-3" id="wait" style="display:none;">
    <img src='images/loader.gif'width="25%" height="10%" />
      <br>Loading..
</div>
   
<!--Closing that has been openned in the header.blade.php -->
</section>
<!-- /.content -->
</div>
<!-- /.content-wrapper -->


<script src="{{asset('js/jquery/jquery.min.js')}}"></script>

<script>

    $(document).ready(function(){

        //select2 initialization code
         $(".select2").select2(); 

         /*LOADER*/

            $(document).ajaxStart(function() {
                $("#wait").css("display", "block");
            });
            $(document).ajaxComplete(function() {
                $("#wait").css("display", "none");
            });

         /*LOADER*/

         //Datatable Code For Showing Data :: START

                var table = $("#show_ps_details").dataTable({  
                            "processing": true,
                            "serverSide": true,
                            "ajax":{
                                    "url": "show_all_ps",
                                    "dataType": "json",
                                    "type": "POST",
                                    "data":{ _token: $('meta[name="csrf-token"]').attr('content')},                                    
                                    },
                            "columns": [                
                                {"class": "id",
                                  "data": "ID" },
                                {"class": "ps_name data",
                                 "data": "POLICE STATION NAME" },
                                 {"class": "district data",
                                 "data": "DISTRICT" },
                                {"class": "delete",
                                "data": "ACTION" }
                            ]
                        }); 
                        
                                       
            // DataTable initialization with Server-Processing ::END

            // Double Click To Enable Content editable
            $(document).on("click",".data", function(){
                $(this).attr('contenteditable',true);
            })


             //Addition of Ps_Details starts
        
            $(document).on("click", "#add_new_ps",function(){
                var ps_name = $("#ps_name").val().toLowerCase().replace(/\b[a-z]/g, function(letter) {
                return letter.toUpperCase();
            });
                var district_name=$('#district_name option:selected').val();
                
                $.ajax({

                    type:"POST",
                    url:"master_maintenance/police_station",
                    data:{
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        ps_name:ps_name,
                        district_name:district_name

                },
                success:function(response)
                {
                    $("#ps_name").val('');
                    swal("PS Added Successfully","","success");
                    table.api().ajax.reload();
                },
                error:function(response) { 
                    if(response.responseJSON.errors.hasOwnProperty('ps_name'))
                        swal("Cannot Add New PS", ""+response.responseJSON.errors.ps_name['0'], "error");
                    }                
                });
            });

        //Addition in PS_Details ends

        //To prevent updation when no changes to the data is made

        var prev_pc_name;
        $(document).on("focusin",".data", function(){
        prev_ps_name = $(this).closest("tr").find(".ps_name").text();
        })

        //Data Updation Code Starts
        $(document).on("focusout",".data", function(){
            var id = $(this).closest("tr").find(".id").text();
            var ps_name = $(this).closest("tr").find(".ps_name").text();
                        
            if(ps_name == prev_ps_name)
                    return false;


            $.ajax({
                type:"POST",
                url:"master_maintenance_ps/ps_update",                
                data:{_token: $('meta[name="csrf-token"]').attr('content'), 
                        id:id, 
                        ps_name:ps_name
                    },
                success:function(response){ 
                    swal("Police Station's Details Updated","","success");
                    table.api().ajax.reload();
                },
                error:function(response) { 
                    if(response.responseJSON.errors.hasOwnProperty('ps_name'))
                        swal("Cannot updated Police Station", ""+response.responseJSON.errors.ps_name['0'], "error");
                }
                })
        })

        // Data Updation Codes Ends 

        // Data Deletion Codes Starts */

                $(document).on("click",".delete", function(){
                    var element=$(this);
                swal({
                    title: "Are You Sure?",
                    text: "Once submitted, you will not be able to change the record",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                    })
                    .then((willDelete) => {
                        if(willDelete) {
                            var id = $(this).closest("tr").find(".id").text();
                            var tr = $(this).closest("tr");

                            $.ajax({
                                type:"POST",
                                url:"master_maintenance_ps/ps_delete",
                                data:{
                                    _token: $('meta[name="csrf-token"]').attr('content'), 
                                    id:id
                                },
                                success:function(response){
                                    if(response==1){
                                        swal("Police Station Deleted Successfully","","success");  
                                        table.api().ajax.reload();                
                                    }
                                },
                                error:function(response){
                                    var id = element.closest("tr").find(".id").text();
                                    swal({
                                        title: "Are You Sure?",
                                        text: "Once deleted,all seizure details associated with this PS will be deleted ",
                                        icon: "warning",
                                        buttons: true,
                                        dangerMode: true,
                                        })
                                        .then((willDelete) => {
                                        if(willDelete) {
                                         
                                            var tr =element.closest("tr");

                                            $.ajax({
                                                type:"POST",
                                                url:"master_maintenance_ps/seizure_ps_delete",
                                                data:{
                                                    _token: $('meta[name="csrf-token"]').attr('content'), 
                                                    id:id
                                                },
                                                success:function(response){
                                                    if(response==1){
                                                        swal("Police Station Deleted Successfully  ","Police Staion and its associated entry has been deleted","success");  
                                                        table.api().ajax.reload();                
                                                    }
                                                }
                                            });
                                        }
                                        
                                    })
                                }
                            })
                        }
                        
                        else 
                        {
                            swal("Deletion Cancelled","","error");
                        }
                })

        // Data Deletion Codes Ends 
    });

});
</script>

@endsection