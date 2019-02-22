@extends('layouts.app') @section('content')
<!-- Main content -->
<div class="box box-default">
    <div class="box-header with-border">
        <h3 class="box-title" text-align="center"><strong>Search Previous Reports</strong></h3>
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
        </div>
    </div>
    <!-- /.box-header -->
    <div class="box-body">
        <div class="row">
            <form class="form-inline">
                <div class="col-sm-4">
                    <div class="form-group">
                        <label>
                           <h3> Month & Year: </h3>
                        </label>
                        <input type="text" class="form-control date_only_month month_of_report" style="width:150px; margin-left:30px" name="month_of_report" id="month_of_report" autocomplete="off">
                        <input type = "text" id="agency" style="display:none" value="{{$agency_details['0']['agency_name']}}">
                        <input type = "text" id="jurisdiction" style="display:none" value="{{$agency_details['0']['district_for_report']}}">
                    </div>
                </div>
                <br>
                 <div class="cold-sm-3">
                    <div class="form-group">
                        <button type="button" class="btn btn-success" style="margin-left:30px" id="search">SEARCH</button>
                    </div>
                </div> 
            </form>
        </div>
    </div>
</div>



<div class="box box-default" style="display:none" id="report_display_section">
    <div class="box-header with-border">
        <h3 class="box-title" text-align="center"><strong>Download Previous Report</strong></h3>
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
        </div>
    </div>
    <!-- /.box-header -->
    <div class="box-body">
        <div id="scrollable" style="overflow:auto;">
            <table class="table table-bordered display" style="white-space: nowrap;">
                <thead>
                    <tr>
                        <th rowspan="1"><strong>Sl No.</th>
                        <th rowspan="1"><strong>Nature of Narcotic<br> Drugs / Controlled<br> Substance</strong></th>
                        <th rowspan="1"><strong>Quantity of<br> Seized<br> Contraband</strong></th>
                        <th rowspan="1"><strong>Date of Seizure</strong></th>
                        <th rowspan="1"><strong>Disposal Date</strong></th>
                        <th rowspan="1"><strong>Disposal Quantity</strong></th>
                        <th rowspan="1"><strong>If not disposed,<br> quantity</strong></th>
                        <th rowspan="1"><strong>Place of Storage<br> of seized drugs</strong></th>
                        <th rowspan="1"><strong>Case Details</strong></th>
                        <th rowspan="1"><strong>Applied for <br> Certification At</strong></th>
                        <th rowspan="1"><strong>Date of<br> Certification</strong></th>
                        <th rowspan="1"><strong>Remarks</strong></th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
        
@endsection  


<script src="{{asset('js/jquery/jquery.min.js')}}"></script>

<script>

    $(document).ready(function(){
        var date = $(".date_only_month").datepicker({
			format: "MM-yyyy",
    		viewMode: "months", 
    		minViewMode: "months"
        }); // Date picker initialization For Month of Report

        //$(".select2").select2();

        

        $(document).on("click","#search", function () { 
            
            var month_of_report= $(".date_only_month").val();
            var agency = $("#agency").val();
            var jurisdiction = $("#jurisdiction").val();
         
            $("#report_display_section").show();

            $('.table').DataTable().destroy();
            var table = $(".table").DataTable({ 
                    "processing": true,
                    "serverSide": true,
                    "searching": false,
                    "paging" : false,
                    "ajax": {
                        "url": "stakeholder/previous_report",
                        "dataType": "json",
                        "type": "POST",
                        "data": {
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            month_of_report:month_of_report
                        },
                    },
                    "columns": [   
                      {"data": "Sl No"},         
                      {"data": "Narcotic Nature"},
                      {"data": "Seize Quantity"},
                      {"data": "Seizure Date"},
                      {"data": "Disposal Date"},
                      {"data": "Disposal Quantity"},
                      {"data": "Not Disposed Quantity"},
                      {"data": "Storage Place"},
                      {"data": "Case Details"},
                      {"data": "Where" },
                      {"data": "Certification Date"},
                      {"data": "Remarks"}
                  ],
                  dom: 'Bfrtip',
                    buttons: [         
                        {
                            extend: 'pdfHtml5',
                            orientation: 'landscape',
                            pageSize: 'A3',
                            exportOptions: {
                                columns: ':visible',
                                stripNewlines: false
                            },
                            title: 'Report Regarding Seizure/Disposal of Narcotic Drugs For '+month_of_report,
                            messageTop: 'Court/Agency: '+agency+'                                  District: '+jurisdiction,
                            messageBottom: '',
                            customize: function(doc) {

                                doc.content[0].fontSize=20
                                doc.content[1].margin=[250,0,0,20]
                                doc.content[1].fontSize=14
                            }
                        }
                    ]
                });


         })

    })

</script>