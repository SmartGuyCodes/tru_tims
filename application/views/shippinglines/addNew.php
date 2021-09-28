<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-ship"></i> Shipping Line Management <small>Add, Edit, Delete</small>
        <small></small>
      </h1>
      <hr>
    </section>
    
    <section class="content">
    
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
              <!-- general form elements -->
              <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Fill in the Shipping Line details here</h3>
                    </div><!-- /.box-header -->
                    <!-- form start -->
                    <?php $this->load->helper("form"); ?>
                    <form role="form" id="addUser" action="<?php echo base_url() ?>shippinglines/addNewshipper" method="post" role="form">
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-4">                                
                                    <div class="form-group">
                                        <label for="fname">Shipping  line Name</label>
                                        <input type="text" class="form-control required" value="<?php echo set_value('shipper_name'); ?>" id="shipper_name" name="shipper_name" maxlength="128">
                                    </div>
                                    
                                </div>
                                <div class="col-md-4">                                
                                    <div class="form-group">
                                        <label for="fname">Contact person</label>
                                        <input type="text" class="form-control required" value="<?php echo set_value('contact_person'); ?>" id="contact_person" name="contact_person" maxlength="128">
                                    </div>                                    
                                </div>
                                <div class="col-md-4">                                
                                    <div class="form-group">
                                        <label for="fname">Email</label>
                                        <input type="email" class="form-control required" value="<?php echo set_value('email'); ?>" id="email" name="email" maxlength="128">
                                    </div>                                    
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="password">Telephone no</label>
                                        <input type="text" class="form-control required" id="telephone_no" name="telephone_no">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="cpassword">Country</label>
                                        <input type="text" class="form-control required" id="country" name="country">
                                    </div>
                                </div>
                                <div class="col-md-4">                                
                                    <div class="form-group">
                                        <label for="fname">Address</label>
                                        <input type="text" class="form-control required" value="<?php echo set_value('address'); ?>" id="address" name="address" maxlength="128">
                                    </div>                                    
                                </div>
                            </div>
                        </div><!-- /.box-body -->
                        <div class="box-footer">
                            <input type="submit" class="btn btn-primary" value="Submit" />
                            <input type="reset" class="btn btn-default" value="Reset" />
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-md-12">
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