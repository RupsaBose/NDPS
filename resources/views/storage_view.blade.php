@extends('layouts.app') @section('content')
<!-- Main content -->
<div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">Add New Storage</h3>
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
            </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <div class="row">                
                <div class="col-md-3 form-group required">
                    <label class="control-label">Storage Name</label>
                        <input type="text" class="form-control storage_name" name="storage_name" id="storage_name">
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>&nbsp;</label>
                        <button type="button" class="form-control btn-success btn btn-primary" name="add_new_storage" id="add_new_storage">Add New Storage
                    </div>
                </div>
                <!-- /.col -->  
    
            </div>
            <!-- /.row -->
        </div>
</div>

<div class="box box-default" id="show_all_data">
    <div class="box-header with-border">
        <h3 class="box-title"> All Storage Details</h3>
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
        </div>
    </div>
    <!-- /.box-header -->
    <div class="box-body">
            <table class="table table-striped table-bordered" id="show_storage_details">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>STORAGE NAME</th>
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
<!--loader ends-->

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

                var table = $("#show_storage_details").dataTable({  
                            "processing": true,
                            "serverSide": true,
                            "ajax":{
                                    "url": "show_all_storage",
                                    "dataType": "json",
                                    "type": "POST",
                                    "data":{ _token: $('meta[name="csrf-token"]').attr('content')},                                    
                                },
                            "columns": [                
                                {"class": "id",
                                  "data": "ID" },
                                {"class": "storage_name data",
                                 "data": "STORAGE NAME" },
                                {"class": "delete",
                                "data": "ACTION" }
                            ]
                        }); 
                        
     // DataTable initialization with Server-Processing ::END

            // Double Click To Enable Content editable
            $(document).on("click",".data", function(){
                 $(this).attr('contenteditable',true);
            })
     //Addition of Storage_Details starts
        
            $(document).on("click", "#add_new_storage",function(){

                var storage_name = $("#storage_name").val().toLowerCase().replace(/\b[a-z]/g, function(letter) {
                    return letter.toUpperCase();
                });
                               
                $.ajax({

                    type:"POST",
                    url:"master_maintenance/storage",
                    data:{
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        storage_name:storage_name

                },
                success:function(response)
                {
                    $("#storage_name").val('');
                    swal("Storage Added Successfully","","success");
                    table.api().ajax.reload();
                },
                error:function(response) { 
                    if(response.responseJSON.errors.hasOwnProperty('storage_name'))
                        swal("Cannot Add New STORAGE", ""+response.responseJSON.errors.storage_name['0'], "error");
                }                
            });
        });

        //Addition in Storage_Details ends

     //To prevent updation when no changes to the data is made

        var prev_storage_name;
        $(document).on("focusin",".data", function(){
            prev_storage_name = $(this).closest("tr").find(".storage_name").text();
        })

        //Data Updation Code Starts
        $(document).on("focusout",".data", function(){
            var id = $(this).closest("tr").find(".id").text();
            var storage_name = $(this).closest("tr").find(".storage_name").text();
                        
            if(storage_name == prev_storage_name)
                    return false;


            $.ajax({
                type:"POST",
                url:"master_maintenance_storage/storage_update",                
                data:{_token: $('meta[name="csrf-token"]').attr('content'), 
                        id:id, 
                        storage_name:storage_name
                    },
                success:function(response){   
                            
                    swal("Storage Details Updated","","success");
                    table.api().ajax.reload();
                },
                error:function(response) { 
                    if(response.responseJSON.errors.hasOwnProperty('storage_name'))
                        swal("Cannot updated Storage", ""+response.responseJSON.errors.storage_name['0'], "error");
                }
            })
        })

        // Data Updation Codes Ends 

       // Data Deletion Codes Starts */

                $(document).on("click",".delete", function(){
                var element=$(this);
                var id;
                swal({
                    title: "Are You Sure?",
                    text: "Once Deleted, you will not be able to recover the data",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                    })
                    .then((willDelete) => {
                    if(willDelete){
                             id = $(this).closest("tr").find(".id").text();
                           
                            $.ajax({
                                type:"POST",
                                url:"master_maintenance_storage/storage_delete",
                                data:{
                                    _token: $('meta[name="csrf-token"]').attr('content'), 
                                    id:id
                                },
                                success:function(response){
                                    if(response==1){
                                        swal("Storage Deleted Successfully","","success");  
                                        table.api().ajax.reload();                
                                    }
                                },
                                error:function(response){

                                     swal({
                                        title: "Are You Sure?",
                                        text: "Once deleted,all details of SEIZURE and USERS associated with this STORAGE will be deleted ",
                                        icon: "warning",
                                        buttons: true,
                                        dangerMode: true,
                                        })
                                        .then((willDelete) => {
                                        if(willDelete) {
                                            $.ajax({
                                                type:"POST",
                                                url:"master_maintenance_storage/seizure_storage_delete",
                                                data:
                                                {
                                                    _token: $('meta[name="csrf-token"]').attr('content'),
                                                    id:id
                                                },
                                                success:function(response)
                                                {
                                                    if(response==1)
                                                    {
                                                        swal("Storage Deleted Successfully","Storage and its associated entry has been deleted","success");  
                                                        table.api().ajax.reload();                
                                                    }
                                                }
                                         })
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

                // Data Deletion Codes Ends 

        
 });
</script>


@endsection
