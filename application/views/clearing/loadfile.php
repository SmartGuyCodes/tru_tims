<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-certificate"></i> Clearing & Forwarding Module
        <small></small>
      </h1>
      <br/>
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
                    <form class="form-horizontal form-bordered" method="post" action="<?php echo base_url(); ?>clearingforwarding/clearingfees?file_no=<?=$file_no?>">
                        <div class="row">
                            <div class="col-sm-12">
                                <!-- Example Form Block -->
                                <div class="block full">
                    				  	<div class="col-sm-5">
                    						<!-- Example Form Block -->
                    						<div class="block">
                    								<div class="form-group col-md-12">
                    									<label class="control-label">File No:</label>
                    									<input type="text" class="form-control" name="fileno" value="<?=$file_no;?>">
                    								</div>
                    								
                    								<div class="form-group">
                    								    
                                                          <!--div class="form-check">
                                                            <input type="checkbox" class="form-check-input" id="exampleCheck1">
                                                            <label class="form-check-label" for="exampleCheck1">Check me out</label>
                                                          </div-->
                    									<div class="checkbox">
                    										<label style="margin-left: 15px;">
                    										    <input class="form-check-input" type="checkbox" name="fees[]" value="IDF_fee">IDF Fee
                    										</label>
                    									</div>
                    									<div class="checkbox">
                    										<label style="margin-left: 15px;"><input type="checkbox" name="fees[]" value="misc_fee">Miscellaneous</label>
                    									</div>
                    									<div class="checkbox">
                    										<label style="margin-left: 15px;"><input type="checkbox" name="fees[]" value="port_charges">Port Charges</label>
                    									</div>
                    									<div class="checkbox">
                    										<label style="margin-left: 15px;"><input type="checkbox" name="fees[]" value="shipping_line_fees">Shipping Line Fees</label>
                    									</div>
                    									<div class="checkbox">
                    										<label style="margin-left: 15px;"><input type="checkbox" name="fees[]" value="storage_fees">storage</label>
                    									</div>
                    									<div class="checkbox">
                    										<label style="margin-left: 15px;"><input type="checkbox" name="fees[]" value="tax">Taxes</label>
                    									</div>
                    									<div class="checkbox">
                    										<label style="margin-left: 15px;"><input type="checkbox" name="fees[]" value="transport_fee">transport</label>
                    									</div>
                    								</div>
                    								<div class="form-group form-actions">
                    									 <button style="margin-left: 15px;" type="submit" name="btnpostcharges" class="btn btn-sm btn-info"><i class="fa fa-check"></i> Confirm</button>
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