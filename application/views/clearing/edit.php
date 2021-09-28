<!--
    Array
(
    [0] => stdClass Object
        (
            [id] => 3
            [file_no] => FL0486157
            [date_received] => 01/26/2020
            [bill_of_landing] => dasxzx
            [vessel] => 45gtt
            [manifest_no] => 982983
            [eta_ata] => 01/31/2020
            [client_id] => Test Client
            [consignee_id] => Test Client 3
            [shipping_line] => Shipper 2
            [cargo_type] => Transit Cargo
            [collection_status] => Collected
            [container_size] => 40 FT
            [consignement] => esdlksdk
            [idf_no] => 898sdiu
            
            [charges] => NOT PAID
            [do_status] => NOT READY
            [down] => CCF
            [car_reg] => KAC897R
            [gate_out] => 32782933
            [created_at] => 2020-01-31 01:44:22
            [updated_at] => 0000-00-00 00:00:00
        )

)
-->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-sign-out"></i> Update File Entry 
        <small>Add / Edit File</small>
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
                        <h3 class="box-title">Add File</h3>
                    </div><!-- /.box-header -->
                    <!-- form start -->
                    <?php $this->load->helper("form"); ?>
                    <form role="form" id="addUser" action="<?php echo base_url() ?>documents/updateClearance/<?=$documentInfo[0]->id?>" method="post" role="form"> 
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-2">                                
                                    <div class="form-group">
                                        <label for="fname">File no.</label>
                                        <input type="text" class="form-control required" value="<?=$documentInfo[0]->file_no?>" id="file_no" name="file_no" readonly>
                                        <input type="hidden" value="<?=$documentInfo[0]->id?>" name="id"/>
                                    </div>
                                    
                                </div>
                                <div class="col-md-2">                                
                                    <div class="form-group">
                                        <label for="fname">Date Received</label>
                                        <input type="text" class="form-control required" value="<?=$documentInfo[0]->date_received?>" id="date_received" name="date_received" readonly>
                                    </div>                                    
                                </div>
                                <div class="col-md-2">                                
                                    <div class="form-group">
                                        <label for="fname">Bill of Landing</label>
                                        <input type="text" class="form-control required" value="<?=$documentInfo[0]->bill_of_landing?>" id="bill_of_landing" name="bill_of_landing" readonly>
                                    </div>
                                    
                                </div>
                                <div class="col-md-2">                                
                                    <div class="form-group">
                                        <label for="fname">Vessel/Voy</label>
                                        <input type="text" class="form-control required" value="<?=$documentInfo[0]->vessel?>" id="vessel" name="vessel" readonly>
                                    </div>                                    
                                </div>
                                <div class="col-md-2">                                
                                    <div class="form-group">
                                        <label for="fname">Manifest no.</label>
                                        <input type="text" class="form-control required" value="<?=$documentInfo[0]->manifest_no?>" id="manifest_no" name="manifest_no" readonly>
                                    </div>
                                    
                                </div>
                                <div class="col-md-2">                                
                                    <div class="form-group">
                                        <label for="fname">ETA/ATA</label>
                                        <input type="text" class="form-control required" value="<?=$documentInfo[0]->eta_ata?>" id="eta_ata" name="eta_ata" readonly>
                                    </div>                                    
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="password">Client</label>
                                        <input type="text" class="form-control required" id="client_id" name="client_id" value="<?=$documentInfo[0]->client_id?>" readonly>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="cpassword">Consignee</label>
                                        <input type="text" class="form-control required" id="consignee_id" name="consignee_id" value="<?=$documentInfo[0]->consignee_id?>" readonly>
                                    </div>
                                </div>
                                <div class="col-md-4">                                
                                    <div class="form-group">
                                        <label for="fname">Shipping Line</label>
                                        <input type="text" class="form-control required" id="shipping_line" name="shipping_line" value="<?=$documentInfo[0]->shipping_line?>" readonly>
                                    </div>
                                    
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-2">                                
                                    <div class="form-group">
                                        <label for="fname">Cargo Type</label>
                                        <input type="text" class="form-control required" id="cargo_type" name="cargo_type" value="<?=$documentInfo[0]->cargo_type?>" readonly>
                                    </div>                                    
                                </div>
                                <div class="col-md-2">                                
                                    <div class="form-group">
                                        <label for="fname">Collection status</label>
                                        <input type="text" class="form-control required" id="collection_status" name="collection_status" value="<?=$documentInfo[0]->collection_status?>" readonly>
                                    </div>                                    
                                </div>
                                <div class="col-md-2">                                
                                    <div class="form-group">
                                        <label for="fname">Container size</label>
                                        <input type="text" class="form-control required" id="container_size" name="container_size" value="<?=$documentInfo[0]->container_size?>" readonly>
                                    </div>                                    
                                </div>
                                <div class="col-md-4">                                
                                    <div class="form-group">
                                        <label for="fname">Consignment</label>
                                        <input type="text" class="form-control required" value="<?=$documentInfo[0]->consignement?>" id="consignement" name="consignement" readonly>
                                    </div>                                    
                                </div>
                                <div class="col-md-2">                                
                                    <div class="form-group">
                                        <label for="fname">IDF no.</label>
                                        <input type="text" class="form-control required" value="<?=$documentInfo[0]->idf_no?>" id="idf_no" name="idf_no" readonly>
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