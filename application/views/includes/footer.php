

    <footer class="main-footer">
        <div class="pull-right hidden-xs">
          <b>Trulance Cargo Management Info System </b>  | Version 1.0.0
        </div>
        <strong>Copyright &copy; <?=date('Y')?> <a href="<?php echo base_url(); ?>">Trulance Communications</a>.</strong> All rights reserved.
    </footer>
    
    <script src="<?php echo base_url(); ?>assets/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/dist/js/adminlte.min.js" type="text/javascript"></script>
     <script src="<?php echo base_url(); ?>assets/dist/js/pages/dashboard.js" type="text/javascript"></script> 
    <!--<script src="<?php echo base_url(); ?>assets/js/jquery.validate.js" type="text/javascript"></script>-->
    <!--<script src="<?php echo base_url(); ?>assets/js/validation.js" type="text/javascript"></script>-->
    <script type="text/javascript">
        var windowURL = window.location.href;
        pageURL = windowURL.substring(0, windowURL.lastIndexOf('/'));
        var x= $('a[href="'+pageURL+'"]');
            x.addClass('active');
            x.parent().addClass('active');
        var y= $('a[href="'+windowURL+'"]');
            y.addClass('active');
            y.parent().addClass('active');
    </script>
    
    <!--<script type="text/javascript" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>-->
    <script type="text/javascript" src="
    https://code.jquery.com/jquery-3.3.1.js"></script>
    <script type="text/javascript" src="
    https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="
    https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" src="
    https://cdn.datatables.net/buttons/1.6.1/js/buttons.flash.min.js"></script>
    <script type="text/javascript" src="
    https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script type="text/javascript" src="
    https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script type="text/javascript" src="
    https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script type="text/javascript" src="
    https://cdn.datatables.net/buttons/1.6.1/js/buttons.html5.min.js"></script>
    <script type="text/javascript" src="
    https://cdn.datatables.net/buttons/1.6.1/js/buttons.print.min.js"></script>

    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    
    <script type="text/javascript">
        $(document).ready(function() {
            $('#transporter_tbl, #transporters_tbl, #example, #transportation_tbl, #transporter_expense_tbl').DataTable();
            
            $( "#reg_date, #date_received, #eta_ata, #date-transport-datepicker, #entry_date, #start_date, #end_date, #interchange_entry_date").datepicker();
            
            $('#interchange_date').datepicker();
        } );
    </script> 
    
    <!--Start of Tawk.to Script-->
    <script type="text/javascript">
        var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
        (function(){
            var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
            s1.async=true;
            s1.src='https://embed.tawk.to/5cebd2eba667a0210d59a08c/default';
            s1.charset='UTF-8';
            s1.setAttribute('crossorigin','*');
            s0.parentNode.insertBefore(s1,s0);
        })();
    </script>
    <!--End of Tawk.to Script-->
  </body>
</html>