@extends('layouts.app') @section('content')
<!-- Main content -->
<div class="box box-default">
    <div class="box-header with-border">
        <h3 class="box-title">Disposed Undisposed Tally</h3>
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
        </div>
    </div>
    <!-- /.box-header -->
    <div class="box-body">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label-sm control-label" style="font-size:medium">Type of Report:</label>
            <div class="form-check form-check-inline"> 
                <label class="form-check-label" for="district_court_report" style="font-size:medium">District & Court Wise Report</label>
                <input class="form-check-input" type="radio" name="report" value="district_court_report">
       
                <label class="form-check-label" for="stakeholder_report" style="font-size:medium; margin-left:2%">Stakeholder Wise Report</label>
                <input class="form-check-input col-sm-offset-1" type="radio" name="report" value="stakeholder_report">
         
                <label class="form-check-label" for="malkhana_report" style="font-size:medium; margin-left:2%">Storage Wise Report</label>
                <input class="form-check-input" type="radio" name="report" value="malkhana_report">
            </div>
        </div>

        <br>

        <div class="form-group row">
            <label class="col-sm-2 col-form-label-sm control-label" style="font-size:medium">Date Range</label>
            <div class="col-sm-3">
                <input type="text" class="form-control date_range" id="date_range" placeholder="Date Range" autocomplete="off">
            </div>

            <div class="col-sm-4 col-sm-offset-1" style="margin-top:-1.5%">
                <button type="button" class="button btn-success btn-sm" style="margin-top:15px" id="generate_report">GENERATE REPORT</button>
                <button type="button" class="button btn-danger btn-sm" style="margin-top:15px" id="reset">RESET</button>
            </div>
        </div>   

    </div>
    <!-- /.box-body -->

</div>
 <!-- /.box-->

<!--loader starts-->
<div class="col-md-offset-5 col-md-3" id="wait" style="display:none;">
    <img src='images/loader.gif'width="25%" height="10%" />
      <br>Loading..
</div>

<!--loader starts-->


<!-- District - Court Wise Search Result -->
<div class="row" id="district_court_search_result" style="display:none">
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">District & Court Wise Report</h3>
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
            </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <table class="table table-bordered table-responsive display" id="district_court_report" style="white-space:nowrap;">
                <thead>
                    <tr>
                    <th style="display:none">DISTRICT ID </th>
                    <th></th>
                    <th>Sl No.</th>
                    <th>District Name</th>
                    <th>Narcotic Type</th>                                    
                    <th>Disposed Quantity</th>
                    <th>Undisposed Quantity</th>
                    </tr>
                </thead>
            </table>
        </div>
        <!-- /.box-body -->
    </div>
    <!-- /.box -->
</div>


<!-- Stakeholder Wise Search Result -->
<div class="row" id="stakeholder_search_result" style="display:none">
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Stakeholder Wise Report</h3>
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
            </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <table class="table table-bordered table-responsive display" id="stakeholder_report" style="white-space:nowrap;">
                <thead>
                    <tr>
                    <th style="display:none">STAKEHOLDER ID </th>
                    <th>Sl No.</th>
                    <th>Stakeholder Name</th>
                    <th>Narcotic Type</th>                                    
                    <th>Disposed Quantity</th>
                    <th>Undisposed Quantity</th>
                    </tr>
                </thead>
            </table>
        </div>
        <!-- /.box-body -->
    </div>
    <!-- /.box -->
</div>


<!-- District - Court Wise Search Result -->
<div class="row" id="storage_search_result" style="display:none">
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Storage Wise Report</h3>
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
            </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <table class="table table-bordered table-responsive display" id="storage_report" style="white-space:nowrap;">
                <thead>
                    <tr>
                    <th style="display:none">STORAGE ID </th>
                    <th></th>
                    <th>Sl No.</th>
                    <th>Storage Name</th>
                    <th>Narcotic Type</th>                                    
                    <th>Disposed Quantity</th>
                    <th>Undisposed Quantity</th>
                    </tr>
                </thead>
            </table>
        </div>
        <!-- /.box-body -->
    </div>
    <!-- /.box -->
</div>
    
    
    
@endsection


<script src="{{asset('js/jquery/jquery.min.js')}}"></script>

    <script>
        $(document).ready(function() {

            /*LOADER*/
            $(document).ajaxStart(function() {
                $("#wait").css("display", "block");
            });
            
            $(document).ajaxComplete(function() {
                $("#wait").css("display", "none");
            });
            /*LOADER*/
            
            // For Date Range Picker :: STARTS            
            var from_date;
            var to_date;
            $("#date_range").daterangepicker({
                    opens: 'left',
                    autoUpdateInput: false,
                    endDate:moment(),
                    maxDate:moment(),
                    locale: {
                        format: 'DD/MM/YYYY',
                        cancelLabel: 'Clear'
                    }
                }, function(start, end, label) {
                    from_date = start.format('YYYY-MM-DD');
                    to_date = end.format('YYYY-MM-DD');
            });

            $("#date_range").on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
            });

            $("#date_range").on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
            });   


            // Report Code :: STARTS
            var table;
            $(document).on("click","#generate_report", function(){                
                // Getting input values
                var report_type_selected = $('input[name=report]').is(':checked');
                var report_type = $('input[name=report]:checked').val();

                var date_range = $("#date_range").val();

                if(!report_type_selected){
                    swal("Invalid Input","Please Select A Type of Report","error");
                    return false;
                }
                else if(date_range==""){
                    swal("Invalid Input","Please Select A Date Range","error");
                    return false;
                }
                else if(from_date=="" || to_date ==""){
                    swal("Invalid Input","Please Select A Date Range","error");
                    return false;
                }
                else if(report_type=="district_court_report"){
                    $('#district_court_report').DataTable().destroy();
                    $("#district_court_search_result").show();
                    $("#storage_search_result").hide();
                    $("#stakeholder_search_result").hide();

                    table = $("#district_court_report").DataTable({ 
                        "processing": true,
                        "serverSide": true,
                        "searching": true,
                        "paging" : true,
                        "ajax": {
                        "url": "disposed_undisposed_tally/district_court_report",
                        "type": "POST",
                        "data": {
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            from_date:from_date,
                            to_date:to_date
                        }
                        },
                        "columns": [  
                            {"class":"district_id",
                             "data":"DISTRICT ID"},
                            {"data": "More Details"}, 
                            {"data": "Sl No"},         
                            {"data": "District Name"},
                            {"data": "Narcotic Type"},
                            {"data": "Disposed Quantity"},
                            {"data": "Undisposed Quantity"}
                        ]
                    });

                    table.column( 0 ).visible( false ); // Hiding the district id column
                }
                else if(report_type=="stakeholder_report"){
                    $('#stakeholder_report').DataTable().destroy();
                    $("#district_court_search_result").hide();
                    $("#storage_search_result").hide();
                    $("#stakeholder_search_result").show();

                    table = $("#stakeholder_report").DataTable({ 
                        "processing": true,
                        "serverSide": true,
                        "searching": true,
                        "paging" : true,
                        "ajax": {
                        "url": "disposed_undisposed_tally/stakeholder_report",
                        "type": "POST",
                        "data": {
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            from_date:from_date,
                            to_date:to_date
                        }
                        },
                        "columns": [  
                            {"class":"stakeholder_id",
                             "data":"STAKEHOLDER ID"},
                            {"data": "Sl No"},         
                            {"data": "Stakeholder Name"},
                            {"data": "Narcotic Type"},
                            {"data": "Disposed Quantity"},
                            {"data": "Undisposed Quantity"}
                        ]
                    });

                    table.column( 0 ).visible( false ); // Hiding the stakeholder id column
                }
                else if(report_type=="malkhana_report"){
                    $('#storage_report').DataTable().destroy();
                    $("#storage_search_result").show();
                    $("#district_court_search_result").hide();
                    $("#stakeholder_search_result").hide();

                    table = $("#storage_report").DataTable({ 
                        "processing": true,
                        "serverSide": true,
                        "searching": true,
                        "paging" : true,
                        "ajax": {
                        "url": "disposed_undisposed_tally/storage_report",
                        "type": "POST",
                        "data": {
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            from_date:from_date,
                            to_date:to_date
                        }
                        },
                        "columns": [  
                            {"class":"storage_id",
                             "data":"STORAGE ID"},
                            {"data": "More Details"}, 
                            {"data": "Sl No"},         
                            {"data": "Storage Name"},
                            {"data": "Narcotic Type"},
                            {"data": "Disposed Quantity"},
                            {"data": "Undisposed Quantity"}
                        ]
                    });

                    table.column( 0 ).visible( false ); // Hiding the storage id column
                }
                
            });
            // Report Code :: ENDS


            // Fetching More Details
            $(document).on("click",".more_details",function(){  
                var report_type = $('input[name=report]:checked').val();

                // Fetching More Detailed Report About Any District :: STARTS
                if(report_type=="district_court_report"){
                    var element = $(this);        
                    var tr = element.closest('tr');
                    var row = table.row(tr);
                    var row_data = table.row(tr).data();

                    var district_id = row_data['DISTRICT ID'];

                    var obj;

                    // fetch case details only when the child row is hide
                    if(!row.child.isShown()){ 

                        $.ajax({
                            type:"POST",
                            url:"disposed_undisposed_tally/fetch_more_details_district_court_report",
                            data:{
                                _token: $('meta[name="csrf-token"]').attr('content'),
                                district_id:district_id,
                                from_date:from_date,
                                to_date:to_date
                            },
                            success:function(response){
                                obj = $.parseJSON(response); 
                            },
                            error:function(response){
                                console.log(response);
                            },
                            async: false
                        }) 
                    }

                    if(row.child.isShown() ) {
                        element.attr("src","images/details_open.png");
                        row.child.hide();
                    }
                    else {
                        element.attr("src","images/details_close.png");

                        var child_string ="";            
                        child_string += '<table class="table table-bordered table-responsive">'+
                                        '<thead>'+
                                            '<tr>'+
                                                '<th>Sl No.</th>'+
                                                '<th style="display:none">Court ID</th>'+
                                                '<th>NDPS Court</th>'+
                                                '<th>Narcotic Type</th>'+                                        
                                                '<th>Disposed Quantity</th>'+
                                                '<th>Undisposed Quantity</th>'+
                                            '</tr>'+
                                        '</thead>'+
                                        
                                    '<tbody>';

                        $.each(obj,function(key,value){
                        child_string += ""+
                            '<tr class="info">'+ 
                                '<td>'+
                                    value.sl_no+
                                '</td>'+
                                '<td class="court_id" style="display:none">'+
                                    value.court_id+
                                '</td>'+
                                '<td>'+
                                    value.court_name+
                                '</td>'+
                                '<td>'+
                                    value.narcotic_type+
                                '</td>'+                               
                                '<td>'+
                                    value.disposed_quantity+
                                '</td>'+
                                '<td>'+
                                    value.undisposed_quantity+
                                '</td>'+
                            '</tr>';
                        })

                        child_string +='</tbody></table>';

                        row.child(child_string).show();
                    }
                }
                // Fetching More Detailed Report About Any District :: ENDS
                
                // Fetching More Detailed Report About Any Storage :: STARTS
                else if(report_type=="malkhana_report"){
                    var element = $(this);        
                    var tr = element.closest('tr');
                    var row = table.row(tr);
                    var row_data = table.row(tr).data();

                    var storage_id = row_data['STORAGE ID'];

                    var obj;

                    // fetch case details only when the child row is hide
                    if(!row.child.isShown()){ 

                        $.ajax({
                            type:"POST",
                            url:"disposed_undisposed_tally/fetch_more_details_storage_report",
                            data:{
                                _token: $('meta[name="csrf-token"]').attr('content'),
                                storage_id:storage_id,
                                from_date:from_date,
                                to_date:to_date
                            },
                            success:function(response){
                                obj = $.parseJSON(response); 
                            },
                            error:function(response){
                                console.log(response);
                            },
                            async: false
                        }) 
                    }

                    if(row.child.isShown() ) {
                        element.attr("src","images/details_open.png");
                        row.child.hide();
                    }
                    else {
                        element.attr("src","images/details_close.png");

                        var child_string ="";            
                        child_string += '<table class="table table-bordered table-responsive">'+
                                        '<thead>'+
                                            '<tr>'+
                                                '<th>Sl No.</th>'+
                                                '<th style="display:none">Stakeholder ID</th>'+
                                                '<th>Stakeholder</th>'+
                                                '<th>Narcotic Type</th>'+                                        
                                                '<th>Disposed Quantity</th>'+
                                                '<th>Undisposed Quantity</th>'+
                                            '</tr>'+
                                        '</thead>'+
                                        
                                    '<tbody>';

                        $.each(obj,function(key,value){
                        child_string += ""+
                            '<tr class="info">'+ 
                                '<td>'+
                                    value.sl_no+
                                '</td>'+
                                '<td class="court_id" style="display:none">'+
                                    value.stakeholder_id+
                                '</td>'+
                                '<td>'+
                                    value.stakeholder_name+
                                '</td>'+
                                '<td>'+
                                    value.narcotic_type+
                                '</td>'+                               
                                '<td>'+
                                    value.disposed_quantity+
                                '</td>'+
                                '<td>'+
                                    value.undisposed_quantity+
                                '</td>'+
                            '</tr>';
                        })

                        child_string +='</tbody></table>';

                        row.child(child_string).show();
                    }
                }
                // Fetching More Detailed Report About Any Storage :: ENDS
            })
            
        })
    </script>