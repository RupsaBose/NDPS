@extends('layouts.app') @section('content')
<!-- Main content -->
<div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">Add New Stakeholder</h3>
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
            </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <div class="row">                
                <div class="col-md-3 form-group required">
                    <label class="control-label">Stakeholder's Name</label>
                    <input type="text" class="form-control" name="stakeholder_name" id="stakeholder">
                </div>
                <div class="col-md-3 form-group required">
                    <label class="control-label">District</label>
                    <input type="text" class="form-control" name="district" id="district">
                </div>
                
                 <div class="col-md-2">
                    <div class="form-group">
                        <label>&nbsp;</label>
                        <button type="button" class="form-control btn-success btn btn-primary " id="add">Add New Stakeholder
                    </div>
                </div>
                <!-- /.col -->  
    
            </div>
            <!-- /.row -->
        </div>
</div>

<div class="box box-default" id="show_all_data">
    <div class="box-header with-border">
        <h3 class="box-title">All Stakeholders' Details</h3>
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
        </div>
    </div>
    <!-- /.box-header -->
    <div class="box-body">
            <table class="table table-striped table-bordered" id="show_stakeholder_data">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>STAKEHOLDER'S NAME</th>
                            <th>JURISDICTION</th>
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
   
   <!--loader starts-->

@endsection
<script src="{{asset('js/jquery/jquery.min.js')}}"></script>

<script>

        $(document).ready(function(){
            


            /*LOADER*/

                $(document).ajaxStart(function() {
                    $("#wait").css("display", "block");
                });
                $(document).ajaxComplete(function() {
                    $("#wait").css("display", "none");
                });

            /*LOADER*/

            //Datatable Code For Showing Data :: START
                var table = $("#show_stakeholder_data").dataTable({  
                            "processing": true,
                            "serverSide": true,
                            "ajax":{
                                    "url": "show_all_stakeholders",
                                    "dataType": "json",
                                    "type": "POST",
                                    "data":{ _token: $('meta[name="csrf-token"]').attr('content')}
                                },
                            "columns": [                
                                {  "class": "id",
                                    "data": "ID" },
                                {"class": "stakeholder",
                                    "data": "STAKEHOLDER" },
                                {"class": "district",
                                    "data": "DISTRICT" },
                                {"data": "ACTION" }
                            ]
                        }); 
            // DataTable initialization with Server-Processing ::END

            // Double Click To Enable Content editable
            $(document).on("click",".data", function(){
                        $(this).attr('contenteditable',true);
                    })

            /*Stakeholder master maintenance */

             $(document).on("click","#add",function (){
                
                            

                 var stakeholder= $("#stakeholder").val().toUpperCase();
                 var district= $("#district").val().toUpperCase();

                            
                 $.ajax({
                        type:"POST",
                        url:"master_maintenance/stakeholder",
                        data:{_token: $('meta[name="csrf-token"]').attr('content'), 
                                 stakeholder_name:stakeholder,
                                district:district,
                            },
                             success:function(response){
                               $("#stakeholder").val('');
                               $("#district").val('');
                               swal("Added Successfully","A new Stackholder has been added","success");
                                    
                            },
                            error:function(response) {  
                               if(response.responseJSON.errors.hasOwnProperty('district'))
                                   swal("Cannot create new Stakeholder", ""+response.responseJSON.errors.district['0'], "error");
                                                         
                               if(response.responseJSON.errors.hasOwnProperty('stakeholder_name'))
                                    swal("Cannot create new Stakeholder", ""+response.responseJSON.errors.stakeholder_name['0'], "error");
                                    
                              }


                        });
                });

            // To prevent updation when no changes to the data is made
            var stakeholder_name;
            var prev_data_pubadd;
            $(document).on("focusin",".data", function(){
            prev_data_pubname = $(this).closest("tr").find(".name").text();
            prev_data_pubadd = $(this).closest("tr").find(".address").text();
        })

         /* Data Updation Code Starts*/
         $(document).on("focusout",".data", function(){
            var id = $(this).closest("tr").find(".id").text();
            var stakeholder = $(this).closest("tr").find(".name").text();
            var jurisdiction = $(this).closest("tr").find(".address").text();
            
            if(pub_name == prev_data_pubname && pub_address == prev_data_pubadd)
                return false;

            $.ajax({
                type:"POST",
                url:"publisher_master_maintainance/update",                
                data:{_token: $('meta[name="csrf-token"]').attr('content'), 
                        id:id, 
                        publisher_name:pub_name,
                        publisher_address:pub_address
                    },
                success:function(response){                    
                    swal("Publisher's Details Updated","","success");
                    table.api().ajax.reload();
                },
                error:function(response) {                   
                if(response.responseJSON.errors.hasOwnProperty('publisher_name'))
                    swal("Cannot create new Publisher", ""+response.responseJSON.errors.publisher_name['0'], "error");
                if(response.responseJSON.errors.hasOwnProperty('publisher_address'))
                    swal("Cannot create new Publisher", ""+response.responseJSON.errors.publisher_address['0'], "error");
                table.api().ajax.reload();
               }
            })
        })

        // /* Data Updation Cods Ends */

        });
</script>

    </body>

    </html>