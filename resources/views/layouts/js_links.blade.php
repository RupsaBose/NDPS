<!-- jQuery 3 -->
    <script src="{{asset('js/AdminLTE-2.4.5/bower_components/jquery/dist/jquery.min.js')}}"></script>
    <!-- Bootstrap 3.3.7 -->
    <script src="{{asset('js/AdminLTE-2.4.5/bower_components/bootstrap/dist/js/bootstrap.min.js')}}"></script>
    <!-- SlimScroll -->
    <script src="{{asset('js/AdminLTE-2.4.5/bower_components/jquery-slimscroll/jquery.slimscroll.min.js')}}"></script>
    <!-- FastClick -->
    <script src="{{asset('js/AdminLTE-2.4.5/bower_components/fastclick/lib/fastclick.js')}}"></script>
    <!-- AdminLTE App -->
    <script src="{{asset('js/AdminLTE-2.4.5/dist/js/adminlte.min.js')}}"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="{{asset('js/AdminLTE-2.4.5/dist/js/demo.js')}}"></script>
    <!-- Sweet Alert-->
    <script src="{{asset('js/Sweet Alert/sweetalert.min.js')}}"></script>
    <!-- Data Table -->
    <script src="{{asset('js/dataTables.min.js')}}"></script>
    <script src="{{asset('js/dataTables.responsive.min.js')}}"></script>
    <!-- Select-2 -->
    <script src="{{asset('js/select2.min.js')}}"></script>
    <!-- Date Picker -->    
    <script src="{{asset('js/bootstrap-datepicker.js')}}"></script>
    <!-- moment min -->    
    <script src="{{asset('js/moment.min.js')}}"></script>
    <!-- Date Range Picker -->    
    <script src="{{asset('js/daterangepicker.min.js')}}"></script>
    <!-- For Data Table Buttons STARTS -->
    <script src="{{asset('js/dataTables.buttons.min.js')}}"></script>        
    <script src="{{asset('js/buttons.flash.min.js')}}"></script>
    <script src="{{asset('js/jszip.min.js')}}"></script>
    <script src="{{asset('js/pdfmake.min.js')}}"></script>
    <script src="{{asset('js/vfs_fonts.js')}}"></script>
    <script src="{{asset('js/buttons.html5.min.js')}}"></script>
    <script src="{{asset('js/buttons.print.min.js')}}"></script>
    <script src="{{asset('js/buttons.colVis.min.js')}}"></script>
    <!-- For Data Table Buttons ENDS -->

    
    <script>
        // Session Timeout For Inactive Window STARTS
        $(document).ready(function(){
            var idle_time = 600000; // for 10 minutes
            var idleSecondsCounter=1;

            document.onclick = function() {
                idleSecondsCounter = 0;
            };

            document.onmousemove = function() {
                idleSecondsCounter = 0;
            };

            document.onkeypress = function() {
                idleSecondsCounter = 0;
            };

            setInterval(function(){
                if(idleSecondsCounter==1){
                    $("#submit").trigger("click");
                }
                else{
                    idleSecondsCounter = 1;
                }
            },idle_time)

        });
        // Session Timeout For Inactive Window ENDS

        
    </script>
    