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
            <div class="col-sm-4">
                <select class="form-control select2" id="report">
                    <option value="">Select an option</option>
                    <option value="district_court_report">District & Court Wise Report</option>
                    <option value="stakeholder_report">PS Wise Report</option>
                    <option value="malkhana_report">Storage Wise Report</option>
                    <option value="narcotic_district_report">Narcotic & District Wise Report</option>
                    <option value="narcotic_malkhana_report">Narcotic & Malkhana Wise Report</option>
                </select>
            </div>            
        </div>

        <br>

        <div class="form-group row">
            <label class="col-sm-2 col-form-label-sm control-label" style="font-size:medium">Date Range</label>
            <div class="col-sm-3">
                <input type="text" class="form-control date_range" id="date_range" placeholder="Date Range" autocomplete="off">
            </div>

            <div class="col-sm-4 col-sm-offset-1" style="margin-top:-1.5%">
                <button type="button" class="btn btn-success btn-sm" style="margin-top:15px" id="generate_report">GENERATE REPORT</button>
                <button type="button" class="btn btn-danger btn-sm" style="margin-top:15px" id="reset">RESET</button>
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

<iframe id="txtArea1" style="display:none"></iframe>


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
            <h3 class="box-title">PS Wise Report</h3>
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


<!-- Narcotic - District Wise Search Result -->
<div class="row" id="narcotic_district_search_result" style="display:none">
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Narcotic & District Wise Report</h3>
            <button class="btnExport"> Download Report </button>

            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
            </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <div style="width:100%; overflow-x:scroll">
                <table class="table table-bordered table-responsive display" id="narcotic_district_report" style="white-space:nowrap;">
                    <thead id="thead_narcotic_district_report">                    
                    </thead>
                    <tbody id="tbody_narcotic_district_report">
                    </tbody>
                </table>
            </div>
        </div>
        <!-- /.box-body -->
    </div>
    <!-- /.box -->
</div>    



<!-- Narcotic - Malkhana Wise Search Result -->
<div class="row" id="narcotic_malkhana_search_result" style="display:none">
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Narcotic & Malkhana Wise Report</h3>
            <button class="btnExport"> Download Report </button>
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
            </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <table class="table table-bordered table-responsive display" id="narcotic_malkhana_report" style="white-space:nowrap;">
                <thead id="thead_narcotic_malkhana_report">                    
                </thead>
                <tbody id="tbody_narcotic_malkhana_report">
                </tbody>
            </table>
        </div>
        <!-- /.box-body -->
    </div>
    <!-- /.box -->
</div>

    
<!--Closing that has been openned in the header.blade.php -->
</section>
<!-- /.content -->
</div>
<!-- /.content-wrapper -->


<script src="{{asset('js/jquery/jquery.min.js')}}"></script>

    <script>
        $(document).ready(function() {

            $(".select2").select2();

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
            var from_date_report;
            var to_date_report;
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
                    
                    from_date_report = start.format('DD-MM-YYYY');
                    to_date_report = end.format('DD-MM-YYYY');
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
                var report_type = $("#report option:selected").val();

                var date_range = $("#date_range").val();

                if(report_type==""){
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
                    $("#narcotic_malkhana_search_result").hide();
                    $("#narcotic_district_search_result").hide();


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
                    $("#narcotic_malkhana_search_result").hide();
                    $("#narcotic_district_search_result").hide();
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
                    $("#narcotic_malkhana_search_result").hide();
                    $("#narcotic_district_search_result").hide();

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
                else if(report_type=="narcotic_district_report"){
                    $("#district_court_search_result").hide();
                    $("#storage_search_result").hide();
                    $("#stakeholder_search_result").hide();
                    $("#narcotic_malkhana_search_result").hide();
                    $("#narcotic_district_search_result").show();

                    $.ajax({
                        url:"disposed_undisposed_tally/narcotic_district_wise_report",
                        type:"post",
                        dataType:"json",
                        data:{
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            from_date:from_date,
                            to_date:to_date
                        },
                        success:function(response){
                            var tbody = "";
                            var i;
                            for(i=0;i<response[0].data.length;i++){                                
                                var j;
                                tbody+="<tr><td>"+response[0].data[i].district_name+"</td>";
                                for(j=0;j<response.length;j++){
                                    tbody+="<td>";

                                    if(response[j].data[i].disposal_quantity!=0)
                                        tbody+= response[j].data[i].disposal_quantity+" "+response[j].data[i].unit_name;
                                    else
                                        tbody+="NIL";

                                    tbody+="</td><td>";
                                        
                                    if(response[j].data[i].undisposed_quantity!=0)
                                        tbody+=response[j].data[i].undisposed_quantity+" "+response[j].data[i].unit_name;
                                    else
                                        tbody+="NIL";
                                
                                    tbody+="</td>";
                                }
                                tbody+="</tr>";
                            }

                            var thead = "<tr><th rowspan='2'>District Name</th>";

                            for(i=0;i<response.length;i++){
                                thead+="<th colspan='2'>"+
                                            response[i].narcotic_name+
                                        "</th>";
                            }

                            thead+="</tr><tr>";

                            for(i=0;i<response.length;i++){
                                thead+="<th>Disp</th><th>Un-disp</th>";
                            }
                            thead+="</tr>";

                            $("#thead_narcotic_district_report").html(thead); 
                            $("#tbody_narcotic_district_report").html(tbody);
                            $('#narcotic_district_report').DataTable().destroy();
                            $('#narcotic_district_report').DataTable();    
                        }
                    })
                }
                else if(report_type=="narcotic_malkhana_report"){
                    $("#district_court_search_result").hide();
                    $("#storage_search_result").hide();
                    $("#stakeholder_search_result").hide();
                    $("#narcotic_malkhana_search_result").show();
                    $("#narcotic_district_search_result").hide();

                    $.ajax({
                        url:"disposed_undisposed_tally/narcotic_malkhana_wise_report",
                        type:"post",
                        dataType:"json",
                        data:{
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            from_date:from_date,
                            to_date:to_date
                        },
                        success:function(response){                            
                            var tbody = "";
                            var i;
                            for(i=0;i<response[0].data.length;i++){                                
                                var j;
                                tbody+="<tr><td>"+response[0].data[i].storage_name+"</td>";
                                for(j=0;j<response.length;j++){
                                    tbody+="<td>";

                                    if(response[j].data[i].disposal_quantity!=0)
                                        tbody+= response[j].data[i].disposal_quantity+" "+response[j].data[i].unit_name;
                                    else
                                        tbody+="NIL";

                                    tbody+="</td><td>";
                                        
                                    if(response[j].data[i].undisposed_quantity!=0)
                                        tbody+=response[j].data[i].undisposed_quantity+" "+response[j].data[i].unit_name;
                                    else
                                        tbody+="NIL";
                                
                                    tbody+="</td>";
                                }
                                tbody+="</tr>";
                            }

                            var thead = "<tr><th rowspan='2'>Malkhana Name</th>";
                            for(i=0;i<response.length;i++){
                                thead+="<th colspan='2'>"+
                                            response[i].narcotic_name+
                                        "</th>";
                            }
                            
                            thead+="</tr><tr>";

                            for(i=0;i<response.length;i++){
                                thead+="<th>Disp</th><th>Un-disp</th>";
                            }
                            thead+="</tr>";  
                            
                            $("#thead_narcotic_malkhana_report").html(thead); 
                            $("#tbody_narcotic_malkhana_report").html(tbody);
                            $('#narcotic_malkhana_report').DataTable().destroy();
                            $('#narcotic_malkhana_report').DataTable();
                        }
                    })
                }                
                
            });
            // Report Code :: ENDS

            // Exporting Report In Excel 
            $(document).on("click",".btnExport", function()
            {
                var tab_text="<table border='2px'><tr bgcolor='#87AFC6'>";
                var textRange; var j=0;

                var report_type = $("#report option:selected").val();
                if(report_type=="narcotic_district_report")
                    tab = document.getElementById('narcotic_district_report'); // id of table
                else if(report_type=="narcotic_malkhana_report")
                    tab = document.getElementById('narcotic_malkhana_report'); // id of table

                for(j = 0 ; j < tab.rows.length ; j++) 
                {     
                    tab_text=tab_text+tab.rows[j].innerHTML+"</tr>";
                }

                tab_text=tab_text+"</table>";
                tab_text= tab_text.replace(/<A[^>]*>|<\/A>/g, "");//remove if u want links in your table
                tab_text= tab_text.replace(/<img[^>]*>/gi,""); // remove if u want images in your table
                tab_text= tab_text.replace(/<input[^>]*>|<\/input>/gi, ""); // reomves input params

                var ua = window.navigator.userAgent;
                var msie = ua.indexOf("MSIE "); 

                if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./))      // If Internet Explorer
                {
                    txtArea1.document.open("txt/html","replace");
                    txtArea1.document.write(tab_text);
                    txtArea1.document.close();
                    txtArea1.focus(); 

                    if(report_type=="narcotic_district_report")
                        sa=txtArea1.document.execCommand("SaveAs",true,"narcotic_report_ddms.xlsx");
                    else if(report_type=="narcotic_malkhana_report")
                        sa=txtArea1.document.execCommand("SaveAs",true,"narcotic_malkhana_ddms.xlsx");
                    
                }  
                else                 
                    sa = window.open('data:application/vnd.ms-excel,' + encodeURIComponent(tab_text));  

                return (sa);
            });


            // Fetching More Details
            $(document).on("click",".more_details",function(){  
                var report_type = $("#report option:selected").val();

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

            // Reset The Page
            $(document).on("click","#reset",function(){
                location.reload(true);
            })
            
        })
    </script>

    
@endsection
