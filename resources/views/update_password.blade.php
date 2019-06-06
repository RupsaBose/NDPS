@extends('layouts.app') @section('content')
<!-- Main content -->
<div class="box box-default">
   <div class="box-header with-border">
      <h3 class="box-title">Update Password</h3>
      <div class="box-tools pull-right">
         <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
         <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
      </div>
   </div>
   <!-- /.box-header -->
   <div class="box-body">
      <div class="row">
         <div class="col-md-3 col-md-offset-4">
            <div class="form-group">
               <label> User Id</label>
               <input type="text" class="form-control" name="user_id" id="user_id" value={{Auth::user()->user_id}} disabled>
            </div>
         </div>
         <div class="col-md-3 col-md-offset-4">
            <div class="form-group">
               <label> Current Password</label>
               <input type="password" class="form-control" name="cur_password" id="cur_password" >
            </div>
         </div>
         <!-- /.col -->
         <div class="col-md-3 col-md-offset-4">
            <div class="form-group">
               <label>New Password</label>
               <input type="password" class="form-control" name="password" id="password" >
            </div>
         </div>
         <!-- /.col -->
         <div class="col-md-3 col-md-offset-4">
            <div class="form-group">
               <label>Confirm Password</label>
               <input type="password" class="form-control" name="re_password" id="re_password" >
            </div>
         </div>
         <div class="col-md-3 col-md-offset-4">
            <div class="form-group">
               <label>&nbsp</label>                            
               <button type="button" class="form-control btn btn-primary" name= "change_password" id="change_password">Change Password
            </div>
         </div>
         <!-- /.col -->
      </div>
      <hr>
   </div>
   <br> <br>
</div>
<!-- /.box-body -->

<div style="display:none;">
        <form id="logout-form" action="{{ route('logout') }}" method="POST">
        @csrf
        <input type="submit" id="submit">
    </form>
</div>
@endsection

<script src="{{asset('js/jquery/jquery.min.js')}}"></script>
<script>

    $(document).ready(function()
    {
        $(document).on("click","#change_password",function(){
                var uid= $("#user_id").val();
                var password =$("#password").val();
                var cur_password=$("#cur_password").val();
                var re_password=$("#re_password").val();

                $.ajax({

                    url:"update_password",
                    type: "POST",
                    data:{ _token: $('meta[name="csrf-token"]').attr('content'),
                            uid:uid,
                            new_password:password,
                            current_password:cur_password,
                            new_password_confirmation: re_password
                         },
                    success:function(response)
                    {                      

                        if(response==1){
                            $("#password").val('');
                            $("#cur_password").val('');
                            $("#re_password").val('');
                            swal({
                                title:"Password Updated Successfully",
                                text:"Login with the new password",
                                icon:"success"
                            }).then(function(){
                                $("#submit").trigger("click");
                            });
                        }
                        else if(response==0)
                        {
                            swal("Invalid Input","Incorrect Current Password","error");
                        }                     
                    },

                     error:function(response){
                         console.log(response);
                        if(response.responseJSON.errors.hasOwnProperty('new_password'))
                                   swal("Password Can Not be Updated", ""+response.responseJSON.errors.new_password['0'], "error");
                        if(response.responseJSON.errors.hasOwnProperty('current_password'))
                                   swal("Password Can Not be Updated", ""+response.responseJSON.errors.current_password['0'], "error");        
                        }
                })
        })

    });



</script>