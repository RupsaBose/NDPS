@extends('layouts.app') @section('content')
<style>
    .select2-container--default .select2-selection--multiple .select2-selection__choice{
        background-color:#111;
    }
</style>
<!-- Main content -->
<div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">Add New Narcotic</h3>
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
            </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <div class="row">                
                <div class="col-md-3 form-group required">
                    <label class="control-label">Narcotic's Name</label>
                    <input type="text" class="form-control" name="narcotic_name" id="narcotic_name">
                </div>
                <div class="col-md-3 form-group required">
                    <label class="control-label">Narcotic's Unit</label>
                    <select class="form-control select2 js-example-basic-multiple narcotic_unit" style="width:150px" name="narcotic_unit" id="narcotic_unit"  multiple="multiple">
                        <option value="">Select an option</option>
                        @foreach($data as $unit)
                            <option value="{{$unit['unit_id']}}">{{$unit['unit_name']}}</option>
                        @endforeach
                    </select>
                </div>
                
                 <div class="col-md-2">
                    <div class="form-group">
                        <label>&nbsp;</label>
                        <button type="button" class="form-control btn-success btn btn-primary " id="add_narcotics">Add New Narcotic
                    </div>
                </div>
                <!-- /.col -->  
    
            </div>
            <!-- /.row -->
        </div>
</div>

<div class="box box-default" id="show_all_data">
    <div class="box-header with-border">
        <h3 class="box-title">All Narcotics' Details</h3>
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
        </div>
    </div>
    <!-- /.box-header -->
    <div class="box-body">
            <table class="table table-striped table-bordered" id="show_narcotics_data">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>NARCOTIC'S NAME</th>
                            <th>NARCOTIC'S UNIT</th>
                            <th>ACTION</th>
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
   
   <!--loader starts-->

@endsection
<script src="{{asset('js/jquery/jquery.min.js')}}"></script>

<script>

        $(document).ready(function(){            
             $('.select2').select2({
                placeholder: "Select Weighing Unit",
            });

            /*LOADER*/
            $(document).ajaxStart(function() {
                    $("#wait").css("display", "block");
                });
                $(document).ajaxComplete(function() {
                    $("#wait").css("display", "none");
                });

            /*LOADER*/

            //Datatable Code For Showing Data :: START

                var table = $("#show_narcotics_data").dataTable({  
                            "processing": true,
                            "serverSide": true,
                            "ajax":{
                                    "url": "show_all_narcotics",
                                    "dataType": "json",
                                    "type": "POST",
                                    "data":{ _token: $('meta[name="csrf-token"]').attr('content')},                                    
                                },
                            "columns": [                
                                {"class": "id",
                                  "data": "ID" },
                                {"class": "narcotic data",
                                 "data": "NARCOTIC" },
                                {"data": "UNIT" },
                                {"class": "delete",
                                "data": "ACTION" }
                            ]
                        }); 
                        
                                       
            // DataTable initialization with Server-Processing ::END

            // Double Click To Enable Content editable
            $(document).on("click",".data", function(){
                        $(this).attr('contenteditable',true);
            })

            //Narcotic master maintenance 
             $(document).on("click","#add_narcotics",function (){
                var narcotic = $("#narcotic_name").val().toLowerCase().replace(/\b[a-z]/g, function(letter) {
                    return letter.toUpperCase();
                });
                
                var narcotic_unit=$("#narcotic_unit").val();
                 
                            
                 $.ajax({
                        type:"POST",
                        url:"master_maintenance/narcotic",
                        data:{_token: $('meta[name="csrf-token"]').attr('content'), 
                                 narcotic_name:narcotic,
                                 narcotic_unit:narcotic_unit
                             },
                             success:function(response){
                               $("#narcotic_name").val('');
                               $("#narcotic_unit").val('');
                               swal("Added Successfully","A new narcotic has been added","success");
                               setTimeout(function(){
                                    window.location.reload(true);
                                },2000);  
                            }
                        

                        });
                });

         /* To prevent updation when no changes to the data is made*/

        var prev_narcotic;
        var prev_unit;
        $(document).on("focusin",".data", function(){
            prev_narcotic = $(this).closest("tr").find(".narcotic").text();
            prev_unit = $(this).closest("tr").find(".unit").val();
        })

         /* Data Updation Code Starts*/

        $(document).on("focusout",".data", function(){
            var id = $(this).closest("tr").find(".id").text();
            var narcotic = $(this).closest("tr").find(".narcotic").text();
            var unit = $(this).closest("tr").find(".unit").val();
            
            if(narcotic == prev_narcotic && unit == prev_unit)
                return false;


            $.ajax({
                type:"POST",
                url:"master_maintenance_narcotic/update",                
                data:{_token: $('meta[name="csrf-token"]').attr('content'), 
                        id:id, 
                        narcotic:narcotic,
                        unit:unit,
                        prev_unit:prev_unit
                    },
                success:function(response){   
                               
                    swal("Narcotic's Details Updated","","success");
                    table.api().ajax.reload();
                },
                error:function(response) {  
                    console.log(response)
                      if(response.responseJSON.errors.hasOwnProperty('unit'))
                         swal("Cannot updated Narcotic", ""+response.responseJSON.errors.unit['0'], "error");
                                                         
                      if(response.responseJSON.errors.hasOwnProperty('narcotic'))
                          swal("Cannot updated Narcotic", ""+response.responseJSON.errors.narcotic['0'], "error");         
                }
             })
        })

        // /* Data Updation Cods Ends */

         /* Data Deletion Codes Starts */

        $(document).on("click",".delete", function(){

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
                            var unit = $(this).closest("tr").find(".unit").val();

                            $.ajax({
                                type:"POST",
                                url:"master_maintenance_narcotic/delete",
                                data:{
                                    _token: $('meta[name="csrf-token"]').attr('content'), 
                                    id:id,
                                    unit:unit
                                },
                                success:function(response){
                                    if(response==1){
                                        swal("Data Deleted Successfully","","success");  
                                        table.api().ajax.reload();                
                                    }
                                },
                                error:function(response){
                                                                    
                                    var id = element.closest("tr").find(".id").text();
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
                                                    url:"master_maintenance_narcotic/seizure_narcotic_delete",
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

                               })
                        }
                        else 
                        {
                            swal("Deletion Cancelled","","error");
                        }
                    })
            });

        /* Data Deletion Codes Ends */

    });

</script>

    </body>

    </html>