<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-truck"></i> Transporter Management <small>Add, Edit, Update, Delete</small>
        <small></small>
      </h1>
    </section>
    
    <section class="content">
    
        <div class="row">
            <!-- left column -->
            <div class="col-md-8">
              <!-- general form elements -->
              <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Edit Transporter Details</h3>
                    </div><!-- /.box-header -->
                    <!-- form start -->
                    <?php $this->load->helper("form"); ?>
                    <form role="form" id="addUser" action="<?php echo base_url() ?>transporter/updateTransporterProfile?id=<?=$profile->id?>" method="post" role="form">
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-6">                                
                                    <div class="form-group">
                                        <label for="fname">Transporter Name</label>
                                        <input type="text" class="form-control required" value="<?=$profile->transporter_name?>" id="transporter_name" name="transporter_name" maxlength="128">
                                    </div>
                                    
                                </div>
                                <div class="col-md-6">                                
                                    <div class="form-group">
                                        <label for="fname">Contact person</label>
                                        <input type="text" class="form-control required" value="<?=$profile->contact_person?>" id="contact_person" name="contact_person" maxlength="128">
                                    </div>                                    
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="password">Mobile no</label>
                                        <input type="text" class="form-control required" value="<?=$profile->mobile_no?>" id="mobile_no" name="mobile_no">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="cpassword">Email</label>
                                        <input type="text" class="form-control required" value="<?=$profile->email?>" id="email" name="email">
                                    </div>
                                </div>
                                <div class="col-md-4">                                
                                    <div class="form-group">
                                        <label for="fname">Physical Address</label>
                                        <input type="text" class="form-control required" value="<?=$profile->physical_address?>" id="physical_address" name="physical_address">
                                    </div>
                                    
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-12">                                
                                    <div class="form-group">
                                        <label for="fname">NOTES</label>
                                        <textarea  class="form-control required"id="notes" name="notes">
                                            <?=$profile->notes?>
                                        </textarea>
                                    </div>                                    
                                </div>
                            </div>
                        </div><!-- /.box-body -->
                        <div class="box-footer">
                            <input type="submit" class="btn btn-primary" value="Submit" />
                            <!--<input type="reset" class="btn btn-default" value="Reset" />-->
                            <a class="btn btn-danger" href="/transporter/listing">Back</a>
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