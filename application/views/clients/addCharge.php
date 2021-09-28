<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-users"></i> Client Management 
        <small>Add, Edit, Update &amp; Delete</small>
      </h1>
    </section>
    
    <section class="content">
    
        <div class="row">
            <!-- left column -->
            <div class="col-md-8">
              <!-- general form elements -->
              <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Fill in New Client Charges</h3>
                    </div><!-- /.box-header -->
                    <!-- form start -->
                    <?php $this->load->helper("form"); ?>
                    <form role="form" id="addUser" action="<?php echo base_url() ?>client/updateClientCharges?client_id=<?=$client_id?>" method="post" role="form">
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-12">                                
                                    <div class="form-group">
                                        <label for="container_no">Container no.</label>
                                        <input type="text" class="form-control required" id="container_no" name="container_no" required>
                                    </div>
                                  
                                </div>
                                <div class="col-md-12">                                
                                    <div class="form-group">
                                        <label for="clearing_charge">Clearing Charge</label>
                                        <input type="number" class="form-control required" id="clearing_charge" name="clearing_charge" value="0" required>
                                    </div>                                    
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="extra_charges">Extra Charges</label>
                                        <input type="number" class="form-control required" id="extra_charges" name="extra_charges" value="0" required>
                                    </div>
                                </div>
                            </div>
                        </div><!-- /.box-body -->
                        <div class="box-footer">
                            <a href="/client/listing" class="btn btn-danger" >
                                <i class="fa fa-arrow-left"></i> Back
                            </a>
                            <input type="submit" class="btn btn-primary" value="Submit" />
                            <!--<input type="reset" class="btn btn-default" value="Back" />-->
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-md-4">
                <?php
                    $this->load->helper('form');
                    $error = $this->session->flashdata('error');
                    if($error)
                    {
                ?>
                <div class="alert alert-danger alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <?php echo $this->session->flashdata('error'); ?>                    
                </div>
                <?php } ?>
                <?php  
                    $success = $this->session->flashdata('success');
                    if($success)
                    {
                ?>
                <div class="alert alert-success alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <?php echo $this->session->flashdata('success'); ?>
                </div>
                <?php } ?>
                
                <div class="row">
                    <div class="col-md-12">
                        <?php echo validation_errors('<div class="alert alert-danger alert-dismissable">', ' <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>'); ?>
                    </div>
                </div>
            </div>
        </div>    
    </section>
    
</div>
<script src="<?php echo base_url(); ?>assets/js/addUser.js" type="text/javascript"></script>