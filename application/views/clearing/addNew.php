<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-file"></i> Received Documemnts  Management 
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
                        <h3 class="box-title">Key in Documents Received Data Here</h3>
                    </div><!-- /.box-header -->
                    <!-- form start -->
                    <?php $this->load->helper("form"); ?>
                    <form role="form" id="addUser" action="<?php echo base_url() ?>documents/addNewDocument" method="post" role="form">
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-2">                                
                                    <div class="form-group">
                                        <label for="fname">CONT NO..</label>
                                        <input type="text" class="form-control required" value="<?php echo $fileNo ?>" id="file_no" name="file_no" readonly>
                                    </div>
                                    
                                </div>
                                <div class="col-md-2">                                
                                    <div class="form-group">
                                        <label for="fname">Date Received</label>
                                        <input type="text" class="form-control required" value="<?php echo set_value('date_received'); ?>" id="date_received" name="date_received">
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
                                        <label for="fname">Vessel/Voy</label>
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
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="password">Client</label>
                                        <!--<input type="text" class="form-control required" id="mobile_no" name="mobile_no">-->
                                        <select class="form-control" name="client_id">
                                            <option>--Select Client--</option>
                                            <?php foreach($clients as $client){ if($client->ac_type == 'client'){?>
                                                <option value="<?=$client->client_name;?>"><?=$client->client_name;?></option>
                                            <?php }} ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="cpassword">Consignee</label>
                                        <select class="form-control" name="consignee_id">
                                            <option>--Select Consignee--</option>
                                            <?php foreach($clients as $con){ if($con->ac_type == 'consignee'){?>
                                                <option value="<?=$con->client_name;?>"><?=$con->client_name;?></option>
                                            <?php }} ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">                                
                                    <div class="form-group">
                                        <label for="fname">Shipping Line</label>
                                        <select class="form-control" name="shipping_line">
                                            <option>--Select Shipping Line--</option>
                                            <?php foreach($shippinglines as $ship){?>
                                                <option value="<?=$ship->shipper_name;?>"><?=$ship->shipper_name;?></option>
                                            <?php } ?>
                                        </select>
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
                                <div class="col-md-4">                                
                                    <div class="form-group">
                                        <label for="fname">Consignment</label>
                                        <input type="text" class="form-control required" value="<?php echo set_value('consignement'); ?>" id="consignement" name="consignement">
                                    </div>                                    
                                </div>
                                <div class="col-md-12">                                
                                    <div class="form-group">
                                        <label for="fname">Notes.</label>
                                        <input type="text" class="form-control required" value="<?php echo set_value('notes'); ?>" id="idf_no" name="notes">
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