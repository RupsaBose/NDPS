@extends('layouts.app') 
@section('content')
<!-- Main content -->

<!-- Only PS and NCB can input data -->
@if(Auth::user()->user_type == "ps" || Auth::user()->user_name == "NCB")
	<div class="box box-default">
			<div class="box-header with-border" >
				<h3 class="box-title" id="box-title" text-align="center"><strong>Seizure - Certification And Disposal Details of Narcotic Contrabands:</strong></h3>
				<div class="box-tools pull-right">
					<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
					<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
				</div>
			</div>
			<!-- /.box-header -->
			<div class="box-body">
					<div class="container">	
						<ul  class="nav nav-pills col-sm-offset-3">
							<li class="active" style="border-style:outset" id="li_seizure"><a href="#seizure" data-toggle="tab"><strong style="font-size:large">Seizure Details</strong></a></li>
							<li style="border-style:outset" id="li_certification"><a href="#certification" data-toggle="tab"><strong style="font-size:large">Apply for Certification</strong></a></li>
							<li style="border-style:outset; pointer-events:none;opacity:0.3;" id="li_disposal"><a href="#disposal" data-toggle="tab"><strong style="font-size:large">Disposal Details</strong></a></li>
						</ul>
						
						<br>

						<div class="alert alert-info" id="case_no_string" role="alert" style="display:none; width:90%; background-color:#337ab7">						
						</div>

						<hr>						

						<div class="tab-content clearfix">
							<!-- Seizure Details Form :: STARTS -->
							<div class="tab-pane active" id="seizure">
								<form id="form_seizure">
									<div class="form-group required row">
										<label class="col-sm-2 col-form-label-sm control-label" style="font-size:medium">Case</label>
										<div class="col-sm-3">
											<select class="form-control select2" id="stakeholder" autocomplete="off">
												@if(Auth::user()->user_type=="agency")
													<option value="">Select Agency</option>
												@else
													<option value="">Select PS</option>
												@endif
												@foreach($data['stakeholders'] as $stakeholder)
													<option value="{{$stakeholder->stakeholder_id}}">{{$stakeholder->stakeholder_name}}</option>
												@endforeach
											</select>
										</div>
										<div class="col-sm-2">
											<input class="form-control" type="number" id="case_no" placeholder="Case No." autocomplete="off">
										</div>
										<div class="col-sm-3">
											<select class="form-control select2" id="case_year" autocomplete="off">	
												<option value="">Select Year</option>					
												@for($i=Date('Y');$i>=1980;$i--)
													<option value="{{$i}}">{{$i}}</option>
												@endfor
											</select>
										</div>
									</div>

									<div class="form-group required row" @if(Auth::user()->user_type=="agency" && Auth::user()->user_name=="NCB") style="display:none" @endif>
										<label class="col-sm-2 col-form-label-sm control-label" style="font-size:medium">Case Initiated By</label>
										<div class="col-sm-2">
											@if(Auth::user()->user_type=="ps")
												<select class="form-control" id="case_initiated_by" autocomplete="off">
													<option value="">Select an option</option>												
													<option value="self">Self</option>
													<option value="agency">Any Agency</option>
												</select>
											@elseif(Auth::user()->user_type=="agency" && Auth::user()->user_name=="NCB")
												<select class="form-control" id="case_initiated_by" autocomplete="off">	
													<option value="self">Self</option>
												</select>
											@endif
										</div>

										<div id="div_case_initiated_by" style="display:none">
											<label class="col-sm-2 col-sm-offset-1 col-form-label-sm control-label" style="font-size:medium">Agency Name</label>
											<div class="col-sm-2">
												<select class="form-control" id="agency_name" autocomplete="off">
													<option value="">Select an option</option>
													@foreach($data['agencies'] as $agency)
														<option value="{{$agency->agency_id}}">{{$agency->agency_name}}</option>
													@endforeach
												</select>
											</div>
										</div>

									</div>

									<hr>

									<div class="div_add_more">
										<div class="form-group required row">
											<label class="col-sm-2 col-form-label-sm control-label" style="font-size:medium">Nature of Narcotic</label>
											<div class="col-sm-3">
												<select class="form-control narcotic_type" autocomplete="off">
													<option value="" selected>Select An Option</option>
													@foreach($data['narcotics'] as $narcotic)
														<option value="{{$narcotic->drug_id}}">{{$narcotic->drug_name}}</option>
													@endforeach
													<option value="999">Other</option>
												</select>									
											</div>

											<div class="col-sm-1 div_img_add_more">											
												<img src="{{asset('images/details_open.png')}}" style="cursor:pointer" class="add_more" alt="add_more" id="add_more">
											</div>

											<div class="col-sm-3 div_other_narcotic_type" style="display:none">
													<input class="form-control other_narcotic_name" type="text" placeholder="Narcotic Name" autocomplete="off">
													<input class="form-control flag_other_narcotic" type="number" style="display:none" autocomplete="off"> 	
											</div>

										</div>

										<div class="form-group required row">
											<label class="col-sm-2 col-form-label-sm control-label" style="font-size:medium">Quantity of Seizure</label>
											<div class="col-sm-3">
												<input class="form-control seizure_quantity" type="number" autocomplete="off">										
											</div>

											<label class="col-sm-2 col-sm-offset-1 col-form-label-sm control-label" style="font-size:medium">Weighing Unit</label>
											<div class="col-sm-2">											
												<select class="form-control seizure_weighing_unit" autocomplete="off">
													<option value="" selected>Select An Option</option>											
												</select>
											</div>										
										</div>

										<div class="form-group required row">	
											<label class="col-sm-2 col-form-label-sm control-label" style="font-size:medium">Date of Seizure</label>
											<div class="col-sm-3">											
												<input type="text" class="form-control date seizure_date" placeholder="Choose Date" autocomplete="off">
											</div>
										</div>

									</div>
									

									<div class="form-group required row">	
											<label class="col-sm-2 col-form-label-sm control-label" style="font-size:medium">Place of Storage</label>
											<div class="col-sm-3">
												<select class="form-control select2" id="storage" autocomplete="off">
													<option value="">Select An Option</option>
													@foreach($data['storages'] as $storage)
														<option value="{{$storage->storage_id}}">{{$storage->storage_name}}</option>
													@endforeach
													<option value="999">Other</option>
												</select>
											</div>

											<div class="col-sm-3 col-sm-offset-1 div_other_storage" style="display:none">
												<input class="form-control other_storage_name" type="text" placeholder="Storage Name" autocomplete="off">
												<input class="form-control flag_other_storage" type="number" style="display:none" autocomplete="off"> 	
											</div>
																												
									</div>


									<div class="form-group required row">	
											<label class="col-sm-2 col-form-label-sm" style="font-size:medium">Case Details / Remark</label>
											<div class="col-sm-2">											
												<textarea class="form-control" id="remark" ></textarea>
											</div>
									</div>

									<hr>

									<div class="col-sm-3 col-sm-offset-5">
										<a href="#certification" data-toggle="tab">
											<button type="button" class="btn btn-success btn-lg btnNext">Next</button>
										</a>
										<button type="button" class="btn btn-primary btn-lg reset">Reset</button>
									</div>

								</form>
							</div>
							<!-- Seizure Details Form :: ENDS -->

							<!-- Certification Details Form :: STARTS -->
							<div class="tab-pane" id="certification">
								<form id="form_certification">
									<div class="form-group required row">
										<label class="col-sm-2 col-form-label-sm  control-label" style="font-size:medium">District</label>
										<div class="col-sm-3">
											<select class="form-control select2" id="district">
												<option value="">Select An Option</option>
												@foreach($data['districts'] as $district)
													<option value="{{$district->district_id}}">{{$district->district_name}}</option>
												@endforeach
											</select>
										</div>

										<label class="col-sm-2 col-sm-offset-1 col-form-label-sm  control-label" style="font-size:medium">Designated Magistrate</label>
										<div class="col-sm-3">											
											<select class="form-control select2" id="court">
												<option value="">Select An Option</option>
											</select>
										</div>
									</div>

									<hr>

									<div class="form-group" id="if_certified">
										<!-- Content Will Come Dynamically -->
									</div>

									<div class="col-sm-6 col-sm-offset-4">
										<a href="#seizure" data-toggle="tab">
											<button type="button" class="btn btn-warning btn-lg btnPrevious">Back</button>
										</a>									
										<button type="button" class="btn btn-success btn-lg" id="apply">Apply For Certification</button>
										<a href="#disposal" data-toggle="tab">
											<button type="button" class="btn btn-primary btn-lg btnNext" id="toDisposal" style="display:none">Next</button>
										</a>
										<button type="button" class="btn btn-primary btn-lg reset">Reset</button>
									</div>

								</form>
							</div>
							<!-- Certification Details Form :: ENDS -->

							<!-- Disposal Details Form :: STARTS -->
							<div class="tab-pane" id="disposal">
								<form id="form_disposal">
									<!-- Content Will Come Dynamically -->
								</form>
							</div>
						</div>
					</div>
			</div>
	</div>
@endif

<!-- Stakeholders' Report Submission Status -->
<div class="row">
    <div class="box box-primary" id="div_report">
            <div class="box-header with-border">
              <form class="form-inline">
                  <label class="box-title" style="font-size:25px; margin-left:30%">
                      Report For The Month Of :                  
					  <input type="text" class="form-control month_of_report" style="width:20%; margin-left:3%" name="month_of_report" id="month_of_report" value="{{date('F',strtotime(date('d-m-Y'))).'-'.date('Y',strtotime(date('d-m-Y')))}}" autocomplete="off">
					  @if(Auth::user()->user_type=='ps' || Auth::user()->user_name=='NCB')
						  <button type="button" class="btn btn-danger pull-right" id="add_new_case">Add New Case</button>
					  @endif
                  </label>
              </form>
              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table class="table table-bordered table-responsive display" style="width:100%;">
                <thead>
                  <tr>
                    <th style="display:none">STAKEHOLDER ID </th>
                    <th style="display:none">STAKEHOLDER TYPE </th>
                    <th style="display:none">CASE NO </th>
                    <th style="display:none">CASE YEAR </th>
                    <th></th>
                    <th>Sl No.</th>
					<th>Case No.</th>
					<th>Designated Magistrate</th>                                     
                    <th>Nature of Narcotic</th>
                    <th>Certification Status</th>
                    <th>Disposal Status</th>
                  </tr>
                </thead>
              </table>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
  </div>


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

		$(".date").datepicker({
			endDate:'0',
			format: 'dd-mm-yyyy'
         }); // Date picker initialization For All The Form Elements

		 var date=$(".month_of_report").datepicker({
			format: "MM-yyyy",
			viewMode: "months", 
			minViewMode: "months"
		}); // Date picker initialization For Month of Report
		
		$(".select2").select2(); // select2 dropdown initialization

		$('html, body').animate({
			scrollTop: $("#div_report").offset().top
		}, 1000)

		$('.btnNext').click(function(){
			$('.nav > .active').next('li').find('a').trigger('click');
			$('html, body').animate({
				scrollTop: $("#box-title").offset().top
			}, 1000)
		});

		$('.btnPrevious').click(function(){
			$('.nav > .active').prev('li').find('a').trigger('click');
			$('html, body').animate({
				scrollTop: $("#box-title").offset().top
			}, 1000)
		});	

		$(document).on("click","#add_new_case",function(){
			$('html, body').animate({
				scrollTop: $("#box-title").offset().top
			}, 1000)
		})

		$(document).on("click",".reset",function(){
			location.reload(true);
		})
		

 	/*LOADER*/

		$(document).ajaxStart(function() {
			$("#wait").css("display", "block");
		});
		$(document).ajaxComplete(function() {
			$("#wait").css("display", "none");
		});

    /*LOADER*/


	/*If multiple narcotics are seized in a same case :: STARTS*/
		var count = 0;
		$(document).on("click","#add_more", function(){
			count++;
			$(".div_add_more:first").clone().find('.div_other_narcotic_type').hide().end().insertAfter(".div_add_more:last");
			$(".add_more:last").attr({src:"images/details_close.png",
										class:"remove", 
										alt:"remove",
										id:""});
			$(".seizure_quantity:last").val('');
			$(".date").datepicker({
			endDate:'0',
			format: 'dd-mm-yyyy'
		}); // Date picker re-initialization
			
		})
	/*If multiple narcotics are seized in a same case :: ENDS*/


	/*When Case Initiated By Any Agency :: STARTS*/
	$(document).on("change","#case_initiated_by", function(){	
		var case_initiated_by=$(this).val();

		if(case_initiated_by=="agency")
			$("#div_case_initiated_by").show();
		else if(case_initiated_by=="self")
			$("#div_case_initiated_by").hide();
	});
	/*When Case Initiated By Any Agency :: ENDS*/


	/*If multiple narcotics are entered after first time submission of seizure details :: STARTS*/			
		$(document).on("click","#seizure_add_more", function(){
			$(".div_add_more_seizure:first").clone().find('.div_other_narcotic_type').hide().end().insertAfter(".div_add_more_seizure:last");
			$(".seizure_add_more:last").attr({src:"images/details_close.png",
												class:"remove_add_more_seizure", 
												alt:"remove",
												id:""});
			$(".seizure_quantity:last").val('');
			$(".date").datepicker({
				endDate:'0',
				format: 'dd-mm-yyyy'
			}); // Date picker re-initialization
			
		})
	/*If multiple narcotics are entered after first time submission of seizure details :: ENDS*/

		/*If multiple narcotics are seized in a same case after submission of seizure details once :: STARTS*/
		$(document).on("click","#add_new_seizure", function(){
				var obj;
				var str_narcotic_list = "";

				$.ajax({
					type:"POST",
					url:"entry_form/fetch_narcotics",
					async: false,
					data:{_token: $('meta[name="csrf-token"]').attr('content')},
					success:function(response){
						obj = $.parseJSON(response);							
						$.each(obj,function(key,value){
								str_narcotic_list=str_narcotic_list+'<option value="'+value.drug_id+'">'+value.drug_name+'</option>';
						})							
					}
				})
				
			

			var str_narcotic_details = 
					'<div class="div_add_more_seizure">'+
								'<div class="form-group required row">'+
									'<label class="col-sm-2 col-form-label-sm control-label" style="font-size:medium">Nature of Narcotic</label>'+
									'<div class="col-sm-3">'+
										'<select class="form-control narcotic_type" autocomplete="off">'+
											'<option value="" selected>Select An Option</option>'+str_narcotic_list+												
											'<option value="999">Other</option>'+
										'</select>'+
									'</div>'+

									'<div class="col-sm-1 div_img_add_more">'+
										'<img src="{{asset("images/details_open.png")}}" style="cursor:pointer" class="seizure_add_more" alt="seizure_add_more" id="seizure_add_more">'+
									'</div>'+

									'<div class="col-sm-3 div_other_narcotic_type" style="display:none">'+
											'<input class="form-control other_narcotic_name" type="text" placeholder="Narcotic Name" autocomplete="off">'+
											'<input class="form-control flag_other_narcotic" type="number" style="display:none" autocomplete="off">'+
									'</div>'+

								'</div>'+

								'<div class="form-group required row">'+
									'<label class="col-sm-2 col-form-label-sm control-label" style="font-size:medium">Quantity of Seizure</label>'+
									'<div class="col-sm-3">'+
										'<input class="form-control seizure_quantity" type="number" autocomplete="off">'+
									'</div>'+

									'<label class="col-sm-2 col-sm-offset-1 col-form-label-sm control-label" style="font-size:medium">Weighing Unit</label>'+
									'<div class="col-sm-2">'+
										'<select class="form-control seizure_weighing_unit" autocomplete="off">'+
											'<option value="" selected>Select An Option</option>'+
										'</select>'+
									'</div>'+
								'</div>'+

								'<div class="form-group required row">'+
									'<label class="col-sm-2 col-form-label-sm control-label" style="font-size:medium">Date of Seizure</label>'+
									'<div class="col-sm-3">'+
										'<input type="text" class="form-control date seizure_date" placeholder="Choose Date" autocomplete="off">'+
									'</div>'+

									'<div class="col-sm-1 col-sm-offset-1">'+
										'<button type="button" class="btn btn-success btn-md save">Save</button>'+											
									'</div>'+

								'</div>'+
								
								'<hr>'+

							'</div>';

				$(str_narcotic_details).insertAfter(".div_add_more:last");

				$("#add_new_seizure").hide();
				$("#cancel").show();
				$(".date").datepicker({
					endDate:'0',
					format: 'dd-mm-yyyy'
				}); // Date picker re-initialization
			

		})
	/*If multiple narcotics are seized in a same case after submission of seizure details once :: ENDS*/


		/*Cancel the attempt to insert new seizure details after the submission of seizure details once :: STARTS */
			$(document).on("click","#cancel", function(){
				$(".div_add_more_seizure").remove();
				$("#add_new_seizure").show();
				$("#cancel").hide();
			})
		/*Cancel the attempt to insert new seizure details after the submission of seizure details once :: ENDS */


		/*If multiple narcotics are seized in a same case and want to remove one :: STARTS*/
		$(document).on("click",".remove", function(){
				count --;
				$(this).closest(".div_add_more").remove();
		})
		/*If multiple narcotics are seized in a same case and want to remove one :: ENDS*/

		/*If multiple narcotics are seized in a same case and want to remove one :: STARTS*/
		$(document).on("click",".remove_add_more_seizure", function(){
				$(this).closest(".div_add_more_seizure").remove();
		})
		/*If multiple narcotics are seized in a same case and want to remove one :: ENDS*/


		/* Save New Seizure After Inserting Seizure Details Once :: STARTS*/
		$(document).on("click",".save",function(){
					var stakeholder = $("#stakeholder option:selected").val();
					var case_no = $("#case_no").val();
					var case_year = $("#case_year option:selected").val();							
					var storage = $("#storage option:selected").val();
					var remark = $("#remark").val();
					var district = $("#district option:selected").val();
					var court = $("#court option:selected").val();


					var element = $(this);

					// If div structure changes, following code will not work :: STARTS
					var element_narcotic_type = $(this).parent().parent().prev().prev().find(".narcotic_type");
					var narcotic_type = element_narcotic_type.val();

					var element_other_narcotic_name = $(this).parent().parent().prev().prev().find(".other_narcotic_name");
					var other_narcotic_name = element_other_narcotic_name.val();

					var flag_other_narcotic = $(this).parent().parent().prev().prev().find(".flag_other_narcotic").val();

					var element_seizure_quantity = $(this).parent().parent().prev().find(".seizure_quantity");
					var seizure_quantity = element_seizure_quantity.val();

					var element_seizure_weighing_unit = $(this).parent().parent().prev().find(".seizure_weighing_unit");
					var seizure_weighing_unit = element_seizure_weighing_unit.val();

					var element_seizure_date = $(this).parent().parent().find(".seizure_date");
					var seizure_date = element_seizure_date.val();
					// If div structure changes, following code will not work :: ENDS

					if(narcotic_type==""){
						swal("Invalid Input","Please Select Narcotic Contraband","error");
						return false;
					}
					if(seizure_quantity==""){
						swal("Invalid Input","Please Insert Seizure Quantity","error");
						return false;
					}
					else if(seizure_weighing_unit==""){
						swal("Invalid Input","Please Select Weighing Unit","error");
						return false;
					}
					else if(seizure_date==""){
						swal("Invalid Input","Please Insert Date of Seizure","error");
						return false;
					}
					else{
						swal({
							title: "Are you sure?",
							text: "",
							icon: "warning",
							buttons: true,
							dangerMode: true,
							})
							.then((willDelete) => {
									if (willDelete) {
										$.ajax({
											type: "POST",
											url:"entry_form/add_new_seizure_details", 
											data: {
												_token: $('meta[name="csrf-token"]').attr('content'),
												stakeholder:stakeholder,
												case_no:case_no,
												case_year:case_year,
												narcotic_type:narcotic_type,
												seizure_date:seizure_date,
												seizure_quantity:seizure_quantity,
												seizure_weighing_unit:seizure_weighing_unit,
												other_narcotic_name:other_narcotic_name,
												flag_other_narcotic:flag_other_narcotic,
												storage:storage,
												remark:remark,
												district:district,
												court:court
											},
											success:function(response){
												swal("New Seizure Details Submitted Successfully","","success");
												element_narcotic_type.attr('readonly',true);
												element_seizure_quantity.attr('readonly',true);
												element_seizure_weighing_unit.attr('disabled',true);
												element_seizure_date.attr('readonly',true);
												element_other_narcotic_name.attr('readonly',true);
												element.hide();
												$("#cancel").hide();
											},
											error:function(response){
												console.log(response);
											}
										})
									}
						});
					}
			})
			/* Save New Seizure After Inserting Seizure Details Once :: ENDS*/


		/*Apply For Certification :: STARTS*/
		var narcotic_type = new Array();
		var seizure_quantity = new Array();
		var seizure_weighing_unit = new Array();
		var seizure_date = new Array();
		var flag_other_narcotic = new Array();
		var other_narcotic_name = new Array();	
		
		$(document).on("click","#apply",function(){

				var stakeholder = $("#stakeholder option:selected").val();
				var case_no = $("#case_no").val();
				var case_year = $("#case_year").val();

				var case_initiated_by = $("#case_initiated_by option:selected").val();

				if(case_initiated_by=="self")
					var agency_name = null;
				else if(case_initiated_by=="agency")
					var agency_name = $("#agency_name option:selected").val();

				narcotic_type = [];
				$(".narcotic_type").each(function(){
					narcotic_type.push($(this).val());
				})
				
				seizure_quantity = [];
				$(".seizure_quantity").each(function(){
					seizure_quantity.push($(this).val());
				})

				seizure_weighing_unit = [];
				$(".seizure_weighing_unit").each(function(){
					seizure_weighing_unit.push($(this).val());
				})

				seizure_date = [];
				$(".seizure_date").each(function(){
					seizure_date.push($(this).val());
				})

				flag_other_narcotic = [];
				$(".flag_other_narcotic").each(function(){
					flag_other_narcotic.push($(this).val());
				})

				other_narcotic_name = [];
				$(".other_narcotic_name").each(function(){
					other_narcotic_name.push($(this).val());
				})
			
				
				var flag_other_storage = $(".flag_other_storage").val();
				var other_storage_name = $(".other_storage_name").val();
							
				var storage = $("#storage option:selected").val();
				var remark = $("#remark").val();
				var district = $("#district option:selected").val();
				var court = $("#court option:selected").val();


				if(stakeholder==""){
					swal("Invalid Input","Please Select Stakeholder's Name","error");
					return false;
				}
				else if(case_no==""){
					swal("Invalid Input","Please Insert Case No.","error");
					return false;
				}
				else if(case_year==""){
					swal("Invalid Input","Please Select Case Year.","error");
					return false;
				}
				else if(case_initiated_by==""){
					swal("Invalid Input","Please Insert Who Has Initiated The Case","error");
					return false;
				}
				else if(case_initiated_by=="agency" && agency_name==""){
					swal("Invalid Input","Please Select The Agency Who Has Initiated The Case","error");
					return false;
				}
				else if(storage==""){
					swal("Invalid Input","Please Select Place of Storage of Seizure","error");
					return false;
				}
				else if(district==""){
					swal("Invalid Input","Please Select District","error");
					return false;
				}
				else if(court==""){
					swal("Invalid Input","Please Select NDPS Court","error");
					return false;
				}
				else{
					swal({
					title: "Are you sure?",
					text: "Once You Applied For Certification, Seizure Details Can Not Be Modified Anymore",
					icon: "warning",
					buttons: true,
					dangerMode: true,
					})
					.then((willDelete) => {
							if (willDelete) {
								$.ajax({
									type: "POST",
									url:"entry_form", 
									data: {
										_token: $('meta[name="csrf-token"]').attr('content'),
										stakeholder:stakeholder,
										case_no:case_no,
										case_year:case_year,
										case_initiated_by:case_initiated_by,
										agency_name:agency_name,
										narcotic_type:narcotic_type,
										seizure_date:seizure_date,
										seizure_quantity:seizure_quantity,
										seizure_weighing_unit:seizure_weighing_unit,
										storage:storage,
										remark:remark,
										district:district,
										court:court,
										flag_other_narcotic:flag_other_narcotic,
										other_narcotic_name:other_narcotic_name,
										flag_other_storage:flag_other_storage,
										other_storage_name:other_storage_name
									},
									success:function(response){
										swal("Application For Certification Successfully Submitted","","success");
										setTimeout(function(){
												window.location.reload(true);
										},2000);
									},
									error:function(response){
										swal("Invalid Input","","error");														
									}
								})
							}
					});
				}
				
		})
		/*Apply For Certification :: ENDS*/


		/*Fetch list of court on correspondance to the selected district :: STARTS*/
		$(document).on("change","#district", function(){	

		var district=$(this).val();
		$("#court").children('option:not(:first)').remove();
		
				$.ajax({
					type: "POST",
					url:"entry_form/fetch_court",
					data: {
						_token: $('meta[name="csrf-token"]').attr('content'),
						district: district
					},
					success:function(resonse){                        
						var obj=$.parseJSON(resonse)
						$.each(obj['district_wise_court'],function(index,value){							
							$("#court").append('<option value="'+value.court_id+'">'+value.court_name+'</option>');
						})
					}
				});

		});
		/*Fetch list of court on correspondance to the selected district :: ENDS*/


		/*Fetch list of units on correspondance to the selected sezied narcotic type :: STARTS*/
		$(document).on("change",".narcotic_type", function(){	

			var narcotic=$(this).val();

			// If div structure changes, following code will not work
			if(narcotic=="999"){
				$(this).parent().parent().find('.div_other_narcotic_type').show();
				var flag_other_narcotic = 1;
				$(this).parent().parent().find('.div_other_narcotic_type').find('.flag_other_narcotic').val(1);
			}
			else{
				$(this).parent().parent().find('.div_other_narcotic_type').hide();
				var flag_other_narcotic = 0;
				$(this).parent().parent().find('.div_other_narcotic_type').find('.flag_other_narcotic').val(0);	
			}
			
			var element = $(this).parent().parent().next().find(".seizure_weighing_unit");
			
			element.children('option:not(:first)').remove();

					$.ajax({
						type: "POST",
						url:"entry_form/narcotic_units",
						data: {
							_token: $('meta[name="csrf-token"]').attr('content'),
							narcotic: narcotic,
							flag_other_narcotic
						},
						success:function(resonse){                        
							var obj=$.parseJSON(resonse)
							$.each(obj['units'],function(index,value){							
								element.append('<option value="'+value.unit_id+'">'+value.unit_name+'</option>');											
							})
						}
					});

			});
			/*Fetch list of units on correspondance to the selected narcotic type :: ENDS*/


			/*For Other Storage Functionality :: STARTS*/
			$(document).on("change","#storage", function(){	
					var storage=$(this).val();

					// If div structure changes, following code will not work
					if(storage=="999"){
						$(this).parent().parent().find('.div_other_storage').show();
						var flag_other_storage = 1;
						$(this).parent().parent().find('.div_other_storage').find('.flag_other_storage').val(1);
					}
					else{
						$(this).parent().parent().find('.div_other_storage').hide();
						var flag_other_storage = 0;
						$(this).parent().parent().find('.div_other_storage').find('.flag_other_storage').val(0);	
					}

			});
			/*Fetch list of units on correspondance to the selected narcotic type :: ENDS*/


			/*Fetch list of units on correspondance to the selected sezied narcotic type :: STARTS*/
		$(document).on("change",".narcotic_type_disposal", function(){	

				var narcotic=$(this).val();
				var display = $(this).find(':selected').data('display');
				// If div structure changes, following code will not work
				var element = $(this).parent().parent().next().find(".disposal_weighing_unit");

				element.children('option:not(:first)').remove();

						$.ajax({
							type: "POST",
							url:"entry_form/narcotic_units",
							data: {
								_token: $('meta[name="csrf-token"]').attr('content'),
								narcotic: narcotic,
								display:display
							},
							success:function(resonse){                        
								var obj=$.parseJSON(resonse)
								$.each(obj['units'],function(index,value){							
									element.append('<option value="'+value.unit_id+'" data-unit_degree="'+value.unit_degree+'">'+value.unit_name+'</option>');
								})
							}
						});

		});
/*Fetch list of units on correspondance to the selected narcotic type :: ENDS*/


			/*Fetching case details for a specific case :: STARTS */
			$(document).on("change","#case_year", function(){
					var stakeholder = $("#stakeholder option:selected").val();
					var case_no = $("#case_no").val();
					var case_year = $("#case_year option:selected").val();			

					if(stakeholder!="" && case_no!="" && case_year!=""){
						var case_no_string = "Case No. : "+$("#stakeholder option:selected").text()+" / "+case_no+" / "+case_year;
						$("#case_no_string").html(case_no_string).show();

							$.ajax({
								type:"POST",
								url:"entry_form/fetch_case_details",
								data:{
									_token: $('meta[name="csrf-token"]').attr('content'),
									stakeholder:stakeholder,
									case_no:case_no,
									case_year:case_year
								},
								success:function(response){
									var obj = $.parseJSON(response);
									if(obj['case_details'].length>0){
											$("#stakeholder").attr('disabled',true);
											$("#case_no").attr('readonly',true);
											$("#case_year").attr('disabled',true);

											var str_case_details ="";
											var str_certification_details = "";
											var str_disposal_details = "";
											var all_narcotic_certfication_flag = 0;
											var all_narcotic_disposal_flag = 0;

											$.each(obj['case_details'],function(index,value){
												str_case_details+=
													'<div class="form-group required row">'+
															'<label class="col-sm-2 col-form-label-sm control-label" style="font-size:medium">Nature of Narcotic</label>'+
															'<div class="col-sm-3">'+
																'<input type="text" class="form-control" value="'+value.drug_name+'" disabled>'+																
															'</div>'+

															'<label class="col-sm-2 col-sm-offset-1 col-form-label-sm control-label" style="font-size:medium">Quantity of Seizure</label>'+
															'<div class="col-sm-3">'+
																	'<input class="form-control" type="text" data-seizure_unit_degree="'+value.seizure_unit_degree+'" value="'+value.quantity_of_drug+' '+value.seizure_unit+'" disabled>'+										
															'</div>'+
													'</div>'+
													
													'<div class="form-group required row">'+
														'<label class="col-sm-2 col-form-label-sm control-label" style="font-size:medium">Date of Seizure</label>'+
														'<div class="col-sm-3">'+
															'<input type="text" class="form-control date seizure_date" value="'+value.date_of_seizure+'" disabled>'+
														'</div>';	

													if(index==obj['case_details'].length-1){
														str_case_details+=
															'<div class="col-sm-1 col-sm-offset-1 div_add_new_seizure">'+
																'<button type="button" class="btn btn-info btn-md add_new_seizure" id="add_new_seizure">New Seizure</button>'+
																'<button type="button" class="btn btn-danger btn-md cancel" id="cancel" style="display:none">Cancel</button>'+
															'</div>'+
														
														'</div>'+														
														'<hr>';	
														}	
														else{
															str_case_details+=
																'</div>'+														
															'<hr>';	
														}

													if(value.certification_flag=='Y'){
															str_certification_details+=""+
																		'<div class="form-group required row">'+
																				'<label class="col-sm-2 col-form-label-sm control-label" style="font-size:medium">Nature of Narcotic</label>'+
																				'<div class="col-sm-3">'+
																					'<input type="text" class="form-control" value="'+value.drug_name+'" disabled>'+
																				'</div>'+

																				'<label class="col-sm-2 col-sm-offset-1 col-form-label-sm control-label" style="font-size:medium">Quantity of Sample</label>'+
																				'<div class="col-sm-3">'+
																						'<input class="form-control" type="text" value="'+value.quantity_of_sample+' '+value.sample_unit+'" disabled>'+										
																				'</div>'+																				
																		'</div>'+

																		'<div class="form-group required row">'+
																				'<label class="col-sm-2 col-form-label-sm control-label" style="font-size:medium">Certification Date</label>'+
																				'<div class="col-sm-3">'+
																						'<input type="text" class="form-control date" value="'+value.date_of_certification+'" disabled>'+
																				'</div>'+

																				'<label class="col-sm-2 col-sm-offset-1 col-form-label-sm" style="font-size:medium">Remarks</label>'+
																				'<div class="col-sm-2">'+
																						'<textarea class="form-control" id="magistrate_remarks" disabled>'+value.magistrate_remarks+'</textarea>'+
																				'</div>'+
																		'</div>'+

																		'<div class="form-check form-group required">'+
																				'<input class="form-check-input" type="checkbox" value="verification" id="verification_statement" checked disabled>'+
																				'<label class="form-check-label control-label" for="verification_statement" style="font-size:medium">'+
																					'I hereby declare that the seizure details furnished here are true and correct.'+
																				'</label>'+
																		'</div>'+
																		
																		'<hr>';

																		all_narcotic_certfication_flag = 1;

																		if(value.disposal_flag=='Y'){
																			str_disposal_details+=""+
																					'<div class="form-group required row">'+
																						'<label class="col-sm-2 col-form-label-sm control-label" style="font-size:medium">Nature of Narcotic</label>'+
																						'<div class="col-sm-3">'+
																							'<input type="text" class="form-control" value="'+value.drug_name+'" disabled>'+
																						'</div>'+
																							
																						'<label class="col-sm-2 col-sm-offset-2 col-form-label-sm control-label" style="font-size:medium">Disposal Quantity</label>'+
																							'<div class="col-sm-2">'+
																								'<input class="form-control disposal_quantity" type="text" value="'+value.disposal_quantity+' '+value.disposal_unit+'" disabled>'+
																							'</div>'+
																					'</div>'+	

																					'<div class="form-group required row">'+
																						'<label class="col-sm-2 col-form-label-sm control-label" style="font-size:medium">Date of Disposal</label>'+
																						'<div class="col-sm-2">'+
																							'<input type="text" class="form-control date disposal_date" value="'+value.date_of_disposal+'" disabled>'+
																						'</div>'+
																					'</div>'+

																					'<hr>'

																					all_narcotic_disposal_flag = 1;

																		}
																		else{
																			str_disposal_details+=""+
																				'<div class="form-group required row">'+
																						'<label class="col-sm-2 col-form-label-sm control-label" style="font-size:medium">Narcotic Type</label>'+
																						'<div class="col-sm-3">'+
																								'<select class="form-control select2 narcotic_type_disposal" disabled>'+
																									'<option value="'+value.drug_id+'" data-display="'+value.display+'" selected>'+value.drug_name+'</option>'+
																								'</select>'+
																						'</div>'+
																				'</div>'+

																				'<div class="form-group required row">'+
																						'<label class="col-sm-2 col-form-label-sm control-label" style="font-size:medium">Disposal Quantity</label>'+
																						'<div class="col-sm-3">'+
																							'<input class="form-control disposal_quantity" type="number">'+
																							'<small>Seizure Quantity: '+value.quantity_of_drug+' '+value.seizure_unit+' ; Sample Quantity: '+value.quantity_of_sample+' '+value.sample_unit+'</small>'+
																						'</div>'+

																						'<label class="col-sm-2 col-sm-offset-2 col-form-label-sm control-label" style="font-size:medium">Weighing Unit</label>'+
																						'<div class="col-sm-2">'+
																							'<select class="form-control select2 disposal_weighing_unit">'+
																								'<option value="" selected>Select an option...</option>'+
																							'</select>'+
																						'</div>'+
																				'</div>'+
																				
																				'<div class="form-group required row">'+
																					'<label class="col-sm-2 col-form-label-sm control-label" style="font-size:medium">Date of Disposal</label>'+
																					'<div class="col-sm-3">'+
																						'<input type="text" class="form-control date disposal_date" placeholder="Choose Date" autocomplete="off">'+
																					'</div>'+

																					'<div class="col-sm-3 col-sm-offset-2">'+
																						'<button type="button" class="btn btn-success btn-md dispose">Dispose</button>'+
																					'</div>'+

																				'</div>'+

																				'<hr>';									

																				all_narcotic_disposal_flag = 0;																			

																		}
													}
													else{														
														str_certification_details+=
															'<div class="form-group required row">'+
																	'<label class="col-sm-2 col-form-label-sm control-label" style="font-size:medium">Nature of Narcotic</label>'+
																	'<div class="col-sm-3">'+
																		'<input type="text" class="form-control narcotic_type" value="'+value.drug_name+'" disabled>'+
																	'</div>'+
															'</div>'+

															'<div class="alert alert-danger" style="width:90%" role="alert">Certification Yet To Be Approved By The Judicial Magistrate</div>';															
														
													}
													
											})

											str_disposal_details+=
											'<div class="form-group required row">'+
												'<div class="col-sm-3 col-sm-offset-5">'+
													'<a href="#certification" data-toggle="tab">'+
														'<button type="button" class="btn btn-warning btn-lg btnPrevious">Back</button>'+
													'</a>'+
													'<button type="button" class="btn btn-primary btn-lg reset" style="margin-left:16px">Reset</button>'+
												'</div>'+
											'</div>';

											$(".div_add_more").html(str_case_details);
											$("#if_certified").html(str_certification_details);
											$("#form_disposal").html(str_disposal_details);

											$(".date").datepicker({
												endDate:'0',
												format: 'dd-mm-yyyy'
											}); // Initialization of Date picker For The Disposal Screen
											
											if(obj['case_details']['0'].ps_id!=null && obj['case_details']['0'].agency_id==null){
												$("#case_initiated_by").val('self').attr('disabled',true);												
											}
											else if(obj['case_details']['0'].ps_id!=null && obj['case_details']['0'].agency_id!=null){
												$("#case_initiated_by").val('agency').attr('disabled',true);
												$("#agency_name").val(obj['case_details']['0'].agency_id).attr('disabled',true);
												$("#div_case_initiated_by").show();											
											}
											
											$("#seizure_date").val(obj['case_details']['0'].date_of_seizure).attr('readonly',true);											
											$("#storage").prepend("<option value='"+obj['case_details']['0'].storage_location_id+"' selected>"+obj['case_details']['0'].storage_name+"</option>").attr('disabled',true);
											$("#remark").val(obj['case_details']['0'].remarks).attr('readonly',true);
											$("#district").prepend("<option value='"+obj['case_details']['0'].district_id+"' selected>"+obj['case_details']['0'].district_name+"</option>").attr('disabled',true);
											$("#court").prepend("<option value='"+obj['case_details']['0'].certification_court_id+"' selected>"+obj['case_details']['0'].court_name+"</option>").attr('disabled',true);
											
											$("#apply").hide();	
											$(".narcotic_type_disposal").trigger("change");
											// If one or many of 'n' no. of seized narcotic is/are certified 
											if(all_narcotic_certfication_flag==1){													
														$("#toDisposal").show();
														$("#li_disposal").css("pointer-events","");									
														$("#li_disposal").css("opacity","");	
											}
											// If one or many of 'n' no. of seized narcotic is/are disposed
											if(all_narcotic_disposal_flag==1){
														$("#dispose").hide();
											}
											else{
														$("#dispose").show();
											}
										
									}
								}
							})
					}

			})
			/*Fetching case details for a specific case :: ENDS */

			/* Fetching Case Details On Other Events Too :: STARTS */
				$(document).on("change","#stakeholder", function(){
						$("#case_year").trigger("change");
					
						var stakeholder=$(this).val();
						$("#district").children('option:not(:first)').remove();
						
								$.ajax({
									type: "POST",
									url:"entry_form/fetch_district",
									data: {
										_token: $('meta[name="csrf-token"]').attr('content'),
										stakeholder:stakeholder
									},
									success:function(resonse){                        
										var obj=$.parseJSON(resonse)
										
										$.each(obj['stakeholder_wise_district'],function(index,value){							
											$("#district").append('<option value="'+value.district_id+'">'+value.district_name+'</option>');														
										})

										$("#district").trigger("change");
									}
								});
				})

				$(document).on("keyup","#case_no", function(){
						$("#case_year").trigger("change");
				})
			/* Fetching Case Details On Other Events Too :: ENDS */


			/* Dispose :: STARTS*/
			$(document).on("click",".dispose",function(){
					var stakeholder = $("#stakeholder option:selected").val();
					var case_no = $("#case_no").val();
					var case_year = $("#case_year option:selected").val();

					var element = $(this);

					// If div structure changes, following code will not work :: STARTS
					var narcotic_type = $(this).parent().parent().prev().prev().find(".narcotic_type_disposal").val();

					var element_disposal_quantity = $(this).parent().parent().prev().find(".disposal_quantity");
					var disposal_quantity = element_disposal_quantity.val();

					var element_disposal_weighing_unit = $(this).parent().parent().prev().find(".disposal_weighing_unit");
					var disposal_weighing_unit = element_disposal_weighing_unit.val();

					var disposal_weighing_degree = element_disposal_weighing_unit.data('unit_degree');

					var element_disposal_date = $(this).parent().parent().find(".disposal_date");
					var disposal_date = element_disposal_date.val();
					// If div structure changes, following code will not work :: ENDS

					if(narcotic_type==""){
						swal("Invalid Input","Please Select Narcotic Contraband","error");
						return false;
					}
					if(disposal_quantity==""){
						swal("Invalid Input","Please Insert Disposal Quantity","error");
						return false;
					}
					else if(disposal_weighing_unit==""){
						swal("Invalid Input","Please Select Weighing Unit","error");
						return false;
					}
					else if(disposal_date==""){
						swal("Invalid Input","Please Insert Date of Disposal","error");
						return false;
					}					
					else{
						swal({
							title: "Are you sure?",
							text: "",
							icon: "warning",
							buttons: true,
							dangerMode: true,
							})
							.then((willDelete) => {
								if (willDelete) {
									$.ajax({
										type: "POST",
										url:"entry_form/dispose", 
										data: {
											_token: $('meta[name="csrf-token"]').attr('content'),
											stakeholder:stakeholder,
											case_no:case_no,
											case_year:case_year,
											narcotic_type:narcotic_type,
											disposal_date:disposal_date,
											disposal_quantity:disposal_quantity,
											disposal_weighing_unit:disposal_weighing_unit
										},
										success:function(response){
											swal("Disposal Record Submitted Successfully","","success");
											element_disposal_quantity.attr('readonly',true);
											element_disposal_weighing_unit.attr('disabled',true);
											element_disposal_date.attr('readonly',true);
											element.hide();
										},
										error:function(response){
											console.log(response);
										}
									})
								}
						});
					}
			})
			/* Dispose :: ENDS*/


		var table;
		// This function will take month as an input and fetch corresponding report
		function get_monthly_report(month){

				$('.table').DataTable().destroy();

				table = $(".table").DataTable({
							"processing": true,
							"serverSide": true,
							"searching": false,
							"paging" : false,
							"ordering" : false,
							"ajax": {
							"url": "entry_form/monthly_report_status",
							"type": "POST",
							"data": {
								_token: $('meta[name="csrf-token"]').attr('content'),
								month:month
							}
						},
						"columns": [  
						{"class":"stakeholder_id",
							"data":"Stakeholder ID"},
						{"class":"stakeholder_type",
							"data":"Stakeholder Type"},
						{"class":"case_no",
							"data":"Case No"},
						{"class":"case_year",
							"data":"Case Year"},
						{"data":"More Details"}, 
						{"data": "Sl No"},
						{"data": "Case_No",
						"width":"20%"},
						{"data": "Magistrate"},
						{"data": "Narcotic Type"},
						{"data": "Certification Status"},
						{"data": "Disposal Status"}
					]
				});

				table.column( 0 ).visible( false ); // Hiding the Stakeholder ID column
				table.column( 1 ).visible( false ); // Hiding the Stakeholder Type column
				table.column( 2 ).visible( false ); // Hiding the Case No. column
				table.column( 3 ).visible( false ); // Hiding the Case Year column
				
		}

		var month_of_report = $(".month_of_report").val();    
		get_monthly_report(month_of_report); // on document ready, fetching the last month's report
	
		// Fetching report status according to the user selected month
		date.on('hide',function(e){
				month_of_report = $(".month_of_report").val();
				get_monthly_report(month_of_report);
		});


		// Fetching More Details About a Case
		$(document).on("click",".more_details",function(){  
			var element = $(this);        
			var tr = element.closest('tr');
			var row = table.row(tr);
			var row_data = table.row(tr).data();

			var stakeholder_id = row_data['Stakeholder ID'];  
			var stakeholder_type = row_data['Stakeholder Type']; 
			var case_no = row_data['Case No'];
			var case_year = row_data['Case Year'];
			
			var obj;

		// fetch case details only when the child row is hide
			if(!row.child.isShown()){ 

					$.ajax({
						type:"POST",
						url:"entry_form/fetch_more_details",
						data:{
							_token: $('meta[name="csrf-token"]').attr('content'),
							stakeholder_id:stakeholder_id,
							stakeholder_type:stakeholder_type,
							case_no:case_no,
							case_year:case_year
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
				child_string += '<table cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">'+                                
									'<tr>'+
										'<td><strong>Storage Location:</strong></td>'+
										'<td>'+obj['0'].storage_name+'</td>'+
									'</tr>'+
									'<tr>'+
										'<td><strong>Case Details / Remarks:</strong></td>'+
										'<td>'+obj['0'].remarks+'</td>'+
									'</tr>'+
								'</table>'+

								'<br>'+
								
								'<div style="width:85%; overflow-x:scroll">'+
									'<table class="table table-bordered table-responsive" style="white-space:nowrap;">'+
										'<thead>'+
											'<tr>'+
												'<th>Narcotic Type</th>'+
												'<th>Seizure Quantity</th>'+ 
												'<th>Date of Seizure</th>'+                                       
												'<th>Certification Status</th>'+
												'<th>Date of Certification</th>'+
												'<th>Sample Quantity</th>'+
												'<th>Magistrate Remarks</th>'+
												'<th>Disposal Status</th>'+
												'<th>Date of Disposal</th>'+
												'<th>Disposal Quantity</th>'+
											'</tr>'+
										'</thead>'+
										
										'<tbody>';

				$.each(obj,function(key,value){
					child_string += ""+
								'<tr>'+ 
									'<td>'+
										value.drug_name+
									'</td>'+                               
									'<td>'+
										value.quantity_of_drug+' '+value.seizure_unit+
									'</td>'+
									'<td>'+
										value.date_of_seizure+
									'</td>'+ 
									'<td>'+
										value.certification_flag+
									'</td>'+
									'<td>'+
										value.date_of_certification+
									'</td>'+                                
									'<td>'+
										value.quantity_of_sample+' '+value.sample_unit+
									'</td>'+
									'<td>'+
										value.magistrate_remarks+
									'</td>'+
									'<td>'+
										value.disposal_flag+
									'</td>'+
									'<td>'+
										value.date_of_disposal+
									'</td>'+
									'<td>'+
										value.disposal_quantity+' '+value.disposal_unit+
									'</td>'+
								'</tr>';
				})

				child_string +='</tbody></table></div>';

				row.child(child_string).show();
			}

		})

	});

	
</script>

@endsection
