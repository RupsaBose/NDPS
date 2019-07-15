@extends('layouts.app') @section('content')
<!-- Main content -->
<div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">Add New Court</h3>
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
            </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <div class="row">                
                <div class="col-md-3 form-group required">
                    <label class="control-label">Court Name</label>
                        <input type="text" class="form-control court_name" name="court_name" id="court_name">
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
                        <button type="button" class="form-control btn-success btn btn-primary" name="add_new_court" id="add_new_court">Add New Court
                    </div>
                </div>
                <!-- /.col -->  
    
            </div>
            <!-- /.row -->
        </div>
</div>

<div class="box box-default" id="show_all_data">
    <div class="box-header with-border">
        <h3 class="box-title"> Courts' Details</h3>
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
        </div>
    </div>
    <!-- /.box-header -->
    <div class="box-body">
            <table class="table table-striped table-bordered" id="show_courts_details">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>COURT'S NAME</th>
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

        //Datatable Code For Showing Data :: START
        var table = $("#show_courts_details").dataTable({  
                            "processing": true,
                            "serverSide": true,
                            "pageLength": "10",
                            "ajax":{
                                    "url": "show_courts_details",
                                    "dataType": "json",
                                    "type": "POST",
                                    "data":{ _token: $('meta[name="csrf-token"]').attr('content')}
                                },
                            "columns": [                
                                {"class":"court_id data",
                                "data": "COURT ID" },
                                {"class":"court_name data",
                                 "data": "COURT NAME" },
                                {"class":"district_name data",
                                "data": "DISTRICT NAME" },
                                {"class":"delete"
                                 ,"data": "ACTION" }
                            ]
                        }); 
           

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

         /*Addition of Court_Details starts*/
            
                $(document).on("click", "#add_new_court",function(){

                    var court_name= $('#court_name').val().toUpperCase();
                    var district=$('#district_name option:selected').val();
                    
                    $.ajax({

                        type:"POST",
                        url:"master_maintenance/court_details",
                        data:{
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            court_name:court_name,
                            district_name:district

                            },
                            success:function(response)
                            {
                                $("#court_name").val('');
                                swal("Court Added Successfully","","success");
                                table.api().ajax.reload();
                            },
                            error:function(response) {  

                                if(response.responseJSON.errors.hasOwnProperty('district_name'))
                                    swal("Cannot create new Court", ""+response.responseJSON.errors.district_name['0'], "error");
                                                            
                                if(response.responseJSON.errors.hasOwnProperty('court_name'))
                                    swal("Cannot create new Court", ""+response.responseJSON.errors.court_name['0'], "error");
                                }
                        });
            });

        /*Addition in Court_Details ends*/

         // DataTable initialization with Server-Processing ::END

            // Double Click To Enable Content editable
            $(document).on("click",".court_name", function(){
                $(this).attr('contenteditable',true);
              })


        /* Start To prevent updation when no changes to the data is made*/

            var prev_court;
            $(document).on("focusin",".court_name", function(){
                prev_court = $(this).closest("tr").find(".court_name").text();
            })

        /*End to prevent updation when no changes to the data is made */


        /* Data Updation Code Starts*/
        $(document).on("focusout",".court_name", function(){
            var id = $(this).closest("tr").find(".court_id").text();
            var court_name = $(this).closest("tr").find(".court_name").text();
           
            
            if(court_name == prev_court)
                return false;


            $.ajax({
                type:"POST",
                url:"master_maintenance_court/update",                
                data:{_token: $('meta[name="csrf-token"]').attr('content'), 
                        id:id, 
                        court_name:court_name
                     },
                success:function(response){ 
                    swal("Court's Details Updated","","success");
                    table.api().ajax.reload();
                },
                error:function(response) {                           
                    //   if(response.responseJSON.errors.hasOwnProperty('court_name'))
                          swal("Cannot updated Court","", "error");
                          
                }

            })
        })

        // /* Data Updation Cods Ends */



     /* Data Deletion Codes Starts */

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
                        var id = $(this).closest("tr").find(".court_id").text();
                        var tr = $(this).closest("tr");

                        $.ajax({
                            type:"POST",
                            url:"master_maintenance_court_details/delete",
                            data:{
                                _token: $('meta[name="csrf-token"]').attr('content'), 
                                id:id
                            },
                            success:function(response){
                                if(response==1){
                                    swal("Court Deleted Successfully","","success");  
                                    table.api().ajax.reload();                
                                }
                            },
                            error:function(response){
                                
                                var id = element.closest("tr").find(".court_id").text();
                                    swal({
                                        title: "Are You Sure?",
                                        text: "Once deleted,all details of SEIZURE and USERS associated with this COURT will be deleted ",
                                        icon: "warning",
                                        buttons: true,
                                        dangerMode: true,
                                        })
                                        .then((willDelete) => {
                                        if(willDelete) {
                                         
                                            var tr =element.closest("tr");

                                            $.ajax({
                                                type:"POST",
                                                url:"master_maintenance_court/seizure_court_delete",
                                                data:{
                                                    _token: $('meta[name="csrf-token"]').attr('content'), 
                                                    id:id
                                                },
                                                success:function(response){
                                                    if(response==1){
                                                        swal("Court Deleted Successfully","Court and its associated entry has been deleted","success");  
                                                        table.api().ajax.reload();                
                                                    }
                                                }
                                            });
                                        }
                                        
                                    })
                                }
                            }); 
                        }
                    else 
                    {
                        swal("Deletion Cancelled","","error");
                    }
            });
        
            /* Data Deletion Codes Ends */
        });
      
});
</script>


@endsection
