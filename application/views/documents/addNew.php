<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-file"></i> Receive Documemnt
        <small>Add, Edit, Update &amp; Delete</small>
      </h1>
      <hr>
    </section>
    
    <section class="content">
        <div class="row">
            <div class="col-xs-12 text-left">
                <a class="btn btn-danger" href="/documents/listing"<i class="fa fa-arrow-left"></i> Back</a>
            </div>
        </div>
        <br />
    
        <div class="row">
            <!-- left column -->
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
            <div class="col-md-12">
              <!-- general form elements -->
              <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Key in Documents Received Data Here</h3>
                    </div><!-- /.box-header -->
                    <!-- form start -->
                    <?php $this->load->helper("form"); ?>
                    <form role="form" id="addUser" action="<?php echo base_url() ?>documents/addNewDocument" method="post" role="form">
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-2">  
                                    <div class="form-group">
                                        <label for="container_nr">Container Nr.</label>
                                        <input type="text" class="form-control required" value="" id="container_nr" name="container_nr" required>
                                    </div> 
                                </div>
                                <div class="col-md-2">                                
                                    <div class="form-group">
                                        <label for="fname">Date Received</label>
                                        <input type="text" class="form-control required" value="<?php echo set_value('date_received'); ?>" id="date_received" name="date_received" required>
                                        <input type="hidden" name="file_no" value="<?=$fileNo?>">
                                    </div>                                    
                                </div>
                                <div class="col-md-2">                                
                                    <div class="form-group">
                                        <label for="fname">Bill of Landing</label>
                                        <input type="text" class="form-control required" value="<?php echo set_value('bill_of_landing'); ?>" id="bill_of_landing" name="bill_of_landing">
                                    </div>
                                    
                                </div>
                                <div class="col-md-2">                                
                                    <div class="form-group">
                                        <label for="fname">Vessel/Voyage</label>
                                        <input type="text" class="form-control required" value="<?php echo set_value('vessel'); ?>" id="vessel" name="vessel">
                                    </div>                                    
                                </div>
                                <div class="col-md-2">                                
                                    <div class="form-group">
                                        <label for="fname">Manifest no.</label>
                                        <input type="text" class="form-control required" value="<?php echo set_value('manifest_no'); ?>" id="manifest_no" name="manifest_no">
                                    </div>
                                    
                                </div>
                                <div class="col-md-2">                                
                                    <div class="form-group">
                                        <label for="fname">ETA/ATA</label>
                                        <input type="text" class="form-control required" value="<?php echo set_value('eta_ata'); ?>" id="eta_ata" name="eta_ata">
                                    </div>                                    
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="password">Client</label>
                                        <!--<input type="text" class="form-control required" id="mobile_no" name="mobile_no">-->
                                        <select class="form-control" name="client_id" required>
                                            <option></option>
                                            <?php foreach($clients as $client){ ?>
                                                <option value="<?=$client->client_name;?>"><?=$client->client_name;?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="cpassword">Consignee</label>
                                        <select class="form-control" name="consignee_id" required>
                                            <option></option>
                                            <?php foreach($clients as $con){ ?>
                                                <option value="<?=$con->client_name;?>"><?=$con->client_name;?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">                                
                                    <div class="form-group">
                                        <label for="fname">Shipping Line</label>
                                        <select class="form-control" name="shipping_line" required>
                                            <option></option>
                                            <?php foreach($shippinglines as $ship){?>
                                                <option value="<?=$ship->shipper_name;?>"><?=$ship->shipper_name;?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    
                                </div>
                                
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="t812_nr">T812 Nr.</label>
                                        <input type="text" class="form-control required" value="" id="t812_nr" name="t812_nr">
                                    </div> 
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-2">                                
                                    <div class="form-group">
                                        <label for="fname">Cargo Type</label>
                                        <select class="form-control" name="cargo_type">
                							<option></option>
                							<option>Local Cargo</option>
                							<option>Transit Cargo</option>
                							<option>Motor Vehicle</option>
                						</select>
                                    </div>                                    
                                </div>
                                <div class="col-md-2">                                
                                    <div class="form-group">
                                        <label for="fname">Collection status</label>
                                        <select class="form-control" name="collection_status">
                							<option></option>
                							<option>Collected</option>
                							<option>Not Collected</option>
                						</select>
                                    </div>                                    
                                </div>
                                <div class="col-md-2">                                
                                    <div class="form-group">
                                        <label for="fname">Container size</label>
                                        <select class="form-control" name="container_size">
                							<option></option>
                							<option>20 FT</option>
                							<option>40 FT</option>
                							<option>Unit</option>
                						</select>
                                    </div>                                    
                                </div>
                                <div class="col-md-6">                                
                                    <div class="form-group">
                                        <label for="fname">Consignment</label>
                                        <input type="text" class="form-control required" value="<?php echo set_value('consignement'); ?>" id="consignement" name="consignement">
                                    </div>                                    
                                </div>
                                <div class="col-md-12">                                
                                    <div class="form-group">
                                        <label for="fname">Notes.</label>
                                        <input type="text" class="form-control required" value="<?php echo set_value('notes'); ?>" id="notes" name="notes">
                                    </div>                                    
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-sm-6">                              
                                    <div class="form-group">
                                        <label for="clearing_charges">Clearing Charges</label>
                                        <input type="text" class="form-control required" value="" id="clearing_charges" name="clearing_charges">
                                    </div>  
                                </div>
                                <div class="col-sm-6">                              
                                    <div class="form-group">
                                        <label for="extra_paid">Extra Paid</label>
                                        <input type="text" class="form-control required" value="" id="extra_paid" name="extra_paid">
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
        </div>    
    </section>
    
</div>
<script src="<?php echo base_url(); ?>assets/js/addUser.js" type="text/javascript"></script>