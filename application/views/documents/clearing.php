<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-sign-out"></i> Manage Received Documents
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
              <!-- general form elements -->
              <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Document Details</h3>
                    </div><!-- /.box-header -->
                    <!-- form start -->
                    <?php $this->load->helper("form"); ?>
                    <form role="form" id="addUser" action="<?php echo base_url() ?>documents/updateClearance/<?=$documentInfo[0]->id?>" method="post" role="form"> 
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-2">                                
                                    <div class="form-group">
                                        <label for="fname">File no.</label>
                                        <input type="text" class="form-control required" value="<?=$documentInfo[0]->file_no?>" id="file_no" name="file_no" >
                                        <input type="hidden" value="<?=$documentInfo[0]->id?>" name="id"/>
                                    </div>
                                    
                                </div>
                                <div class="col-md-2">                                
                                    <div class="form-group">
                                        <label for="fname">Date Received</label>
                                        <input type="text" class="form-control required" value="<?=$documentInfo[0]->date_received?>" id="date_received" name="date_received" >
                                    </div>                                    
                                </div>
                                <div class="col-md-2">                                
                                    <div class="form-group">
                                        <label for="fname">Bill of Landing</label>
                                        <input type="text" class="form-control required" value="<?=$documentInfo[0]->bill_of_landing?>" id="bill_of_landing" name="bill_of_landing" >
                                    </div>
                                    
                                </div>
                                <div class="col-md-2">                                
                                    <div class="form-group">
                                        <label for="fname">Vessel/Voyage No</label>
                                        <input type="text" class="form-control required" value="<?=$documentInfo[0]->vessel?>" id="vessel" name="vessel" >
                                    </div>                                    
                                </div>
                                <div class="col-md-2">                                
                                    <div class="form-group">
                                        <label for="fname">Manifest no.</label>
                                        <input type="text" class="form-control required" value="<?=$documentInfo[0]->manifest_no?>" id="manifest_no" name="manifest_no" >
                                    </div>
                                    
                                </div>
                                <div class="col-md-2">                                
                                    <div class="form-group">
                                        <label for="fname">ETA/ATA</label>
                                        <input type="text" class="form-control required" value="<?=$documentInfo[0]->eta_ata?>" id="eta_ata" name="eta_ata" >
                                    </div>                                    
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="password">Client</label>
                                        <input type="text" class="form-control required" id="client_id" name="client_id" value="<?=$documentInfo[0]->client_id?>" >
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="cpassword">Consignee</label>
                                        <input type="text" class="form-control required" id="consignee_id" name="consignee_id" value="<?=$documentInfo[0]->consignee_id?>" >
                                    </div>
                                </div>
                                <div class="col-md-3">                                
                                    <div class="form-group">
                                        <label for="fname">Shipping Line</label>
                                        <input type="text" class="form-control required" id="shipping_line" name="shipping_line" value="<?=$documentInfo[0]->shipping_line?>" >
                                    </div>
                                    
                                </div>
                                
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="t812_nr">T812 N0.</label>
                                        <input type="text" class="form-control required" value="<?=$documentInfo[0]->t812_nr?>" id="t812_nr" name="t812_nr">
                                    </div> 
                                </div>
                                  <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="t810_nr">T810 N0.</label>
                                        <input type="text" class="form-control required" value="<?=$documentInfo[0]->t810_nr?>" id="t810_nr" name="t810_nr">
                                    </div> 
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-2">                                
                                    <div class="form-group">
                                        <label for="fname">Cargo Type</label>
                                        <input type="text" class="form-control required" id="cargo_type" name="cargo_type" value="<?=$documentInfo[0]->cargo_type?>" >
                                    </div>                                    
                                </div>
                                <div class="col-md-2">                                
                                    <div class="form-group">
                                        <label for="fname">Collection status</label>
                                        <input type="text" class="form-control required" id="collection_status" name="collection_status" value="<?=$documentInfo[0]->collection_status?>" >
                                    </div>                                    
                                </div>
                                <div class="col-md-2">                                
                                    <div class="form-group">
                                        <label for="fname">Container size</label>
                                        <input type="text" class="form-control required" id="container_size" name="container_size" value="<?=$documentInfo[0]->container_size?>" >
                                    </div>                                    
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="container_nr">Container Nr.</label>
                                        <input type="text" class="form-control required" value="<?=$documentInfo[0]->container_nr; ?>" id="container_nr" name="container_nr">
                                    </div> 
                                </div>
                                <div class="col-md-2">                                
                                    <div class="form-group">
                                        <label for="fname">Consignment</label>
                                        <input type="text" class="form-control required" value="<?=$documentInfo[0]->consignement?>" id="consignement" name="consignement" >
                                    </div>                                    
                                </div>
                                <div class="col-md-2">                                
                                    <div class="form-group">
                                        <label for="fname">CFS NAME.</label>
                                        <input type="text" class="form-control required" value="<?=$documentInfo[0]->idf_no?>" id="idf_no" name="idf_no" >
                                    </div>                                    
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-2">                                
                                    <div class="form-group">
                                        <label for="fname">Charges</label>
                                        <select class="form-control" name="charges">
                							<option><?=$documentInfo[0]->charges?></option>
                							<option>PAID</option>
                							<option>NOT PAID</option>
                						</select>
                                    </div>                                    
                                </div>
                                <div class="col-md-2">                                
                                    <div class="form-group">
                                        <label for="fname">D.O. Status</label>
                                        <select class="form-control" name="do_status">
                							<option><?=$documentInfo[0]->do_status?></option>
                							<option>READY</option>
                							<option>NOT READY</option>
                						</select>
                                    </div>
                                </div>
                                <div class="col-md-2">                                
                                    <div class="form-group">
                                        <label for="fname">Down</label>
                                        <input type="text" class="form-control required" value="<?=$documentInfo[0]->down?>" id="down" name="down">
                                    </div>                                    
                                </div>
                                <div class="col-md-3">                                
                                    <div class="form-group">
                                        <label for="fname">Car registration</label>
                                        <input type="text" class="form-control required" value="<?=$documentInfo[0]->car_reg?>" id="car_reg" name="car_reg">
                                    </div>                                    
                                </div>
                                <div class="col-md-3">                                
                                    <div class="form-group">
                                        <label for="fname">Gate Out</label>
                                        <input type="text" class="form-control required" value="<?=$documentInfo[0]->gate_out?>" id="gate_out" name="gate_out">
                                    </div>
                                    
                                </div>
                                
                                
                                
                                <div class="col-md-12">                                
                                    <div class="form-group">
                                        <label for="fname">Notes</label>
                                        <input type="text" class="form-control required" value="<?=$documentInfo[0]->notes?>" id="gate_out" name="notes">
                                    </div>
                                    
                                </div>
                            
                            
                            </div>
                            
                            <div class="row">
                                <div class="col-sm-2">                              
                                    <div class="form-group">
                                        <label for="clearing_charges">Clearing Charges</label>
                                        <input type="text" class="form-control required" value="<?=($documentInfo[0]->clearing_charges)?>" id="clearing_charges" name="clearing_charges">
                                    </div>  
                                </div>
                                <div class="col-sm-2">                              
                                    <div class="form-group">
                                        <label for="extra_paid">Extra Paid</label>
                                        <input type="text" class="form-control required" value="<?=$documentInfo[0]->extra_paid?>" id="extra_paid" name="extra_paid">
                                    </div>  
                                </div>
                                <div class="col-sm-2">                              
                                    <div class="form-group">
                                        <label for="extra_paid">Total</label>
                                        <input type="text" class="form-control required" value="<?=$documentInfo[0]->clearing_charges + $documentInfo[0]->extra_paid?>" id="total" name="total" readonly>
                                    </div>  
                                </div>
                                <div class="col-sm-3">                              
                                    <div class="form-group">
                                        <label for="extra_paid">Balance</label>
                                        <input type="text" class="form-control required" value="<?=($documentInfo[0]->balance)?>" id="balance" name="balance" readonly>
                                    </div>  
                                </div>
                                <div class="col-sm-3">                              
                                    <div class="form-group">
                                        <label for="clearing_charges">Amount Paid</label>
                                        <p style="color: #ff0000;"><?=number_format($documentInfo[0]->amount_paid, 2);?></p>
                                        <!--<input type="text" class="form-control required" value="" id="amount_paid" name="amount_paid" >-->
                                        <input type="hidden" class="form-control required" value="<?=($documentInfo[0]->amount_paid)?>" id="last_amount_paid" name="last_amount_paid">
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