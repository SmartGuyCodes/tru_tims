<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-certificate"></i> Clearing & Forwarding
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
                        <h3 class="box-title">Clearing Info.</h3>
                    </div><!-- /.box-header -->
                    <!-- form start -->
                    <?php $this->load->helper("form"); ?>
                    <form class="form-horizontal form-bordered" method="post">
    <div class="row">
        <div class="col-sm-12">
            <!-- Example Form Block -->
            <div class="block full">
                <!-- Example Form Title -->
                <div class="block-title">
                    <h2>Clearing Info.</h2>
                </div>
				  	<div class="col-sm-5">
						<!-- Example Form Block -->
						<div class="block">
								<div class="form-group col-md-12">
									<label class="control-label">File No:</label>
									<input type="text" class="form-control" name="fileno" value="FL0140120">
								</div>
								
								<div class="form-group">
									<div class="checkbox">
													  <label><input type="checkbox" name="fees[]" value="F0091318">IDF Fee</label>
													</div><div class="checkbox">
													  <label><input type="checkbox" name="fees[]" value="F0161318">Miscellaneous</label>
													</div><div class="checkbox">
													  <label><input type="checkbox" name="fees[]" value="F0141318">Port Charges</label>
													</div><div class="checkbox">
													  <label><input type="checkbox" name="fees[]" value="F0101318">Shipping Line Fees</label>
													</div><div class="checkbox">
													  <label><input type="checkbox" name="fees[]" value="F0311018">storage</label>
													</div><div class="checkbox">
													  <label><input type="checkbox" name="fees[]" value="F0131318">Taxes</label>
													</div><div class="checkbox">
													  <label><input type="checkbox" name="fees[]" value="F0321018">transport</label>
													</div>								</div>
								<div class="form-group form-actions">
									 <button type="submit" name="btnpostcharges" class="btn btn-sm btn-info"><i class="fa fa-check"></i> Confirm</button>
								</div>
							<!-- END Example Form Content -->
						</div>
						<!-- END Example Form Block -->
					</div>
                    <div class="form-group form-actions" align="center">
                       
                    </div>
						
                <!-- END Example Form Content -->
            </div>
            <!-- END Example Form Block -->
        </div>
        
        
        
        
        
        
    </div></form>
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