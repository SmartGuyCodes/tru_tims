<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-truck"></i> Edit Transporter Expense
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
                        <h3 class="box-title">Update Transport Expense Details</h3>
                    </div><!-- /.box-header -->
                    <!-- form start -->
                    <?php $this->load->helper("form"); ?>
                    <form role="form" id="addUser" action="<?php echo base_url() ?>transporter/editTransportExpense?id=<?=$profile[0]->id;?>" method="post" role="form">
                        <div class="box-body">
        					<div class="row">
        					    <div class="form-group col-md-3">
            						<label class="control-label">Date of Transport:</label>
            						<input type="text" class="form-control input-datepicker hasDatepicker" id="date-transport-datepicker" data-date-format="dd-mm-yyyy" name="transport_date" value="<?=$profile[0]->transport_date?>" readonly>
            						<input value="<?=$transporter_id;?>" type="hidden" name="transporter_id">
            						<input value="<?=$curr_balance?>" name="curr_balance" type="hidden"/>
            						<input value="<?=$file_no?>" name="file_no" type="hidden"/>
            					</div>
            					<div class="form-group col-md-3">
            						<label class="control-label">Vehicle  No:</label>
            						<input type="text" class="form-control" name="vehicle_number" value="<?=$profile[0]->vehicle_number?>" readonly>
            					</div>
            					<div class="form-group col-md-3">
            						<label class="control-label">Consignee:</label>
            						<input type="text" class="form-control" name="consignee" value="<?=$profile[0]->consignee?>" readonly>
            					</div>
        					    <div class="form-group col-md-3">
            						<label class="control-label">Container No.:</label>
            						<input type="text" class="form-control" id="container_no" data-date-format="dd-mm-yyyy" name="container_no" value="<?=$profile[0]->container_no?>" readonly>
            					</div>
        					</div>
        					
        					<div class="row">
        					    <div class="form-group col-md-3">
            						<label class="control-label">T810  No:</label>
            						<input type="number" class="form-control input-datepicker" id="t810_no" name="t810_no" value="<?=$profile[0]->t810_no?>">
            					</div>
            					<div class="form-group col-md-3">
            						<label class="control-label">T812  No:</label>
            						<input type="number" class="form-control" name="t812_no" value="<?=$profile[0]->t812_no?>">
            					</div>
            					<div class="form-group col-md-3">
            						<label class="control-label">Transport Charge</label>
            						<input type="number" class="form-control" name="clearing_charge" value="<?=$profile[0]->clearing_charge?>">
            					</div>
        					    <div class="form-group col-md-3">
            						<label class="control-label">Other Expences:</label>
            						<input type="number" class="form-control input-datepicker" id="extra" name="extra_paid" value="<?=$profile[0]->extra_paid?>">
            					</div>
        					</div>
        					
        					<div class="row">
            					<div class="form-group col-md-6">
            						<label class="control-label">Advance</label>
            						<input type="number" class="form-control" name="advance" value="">
            						<input type="hidden" name="last_advance" value="<?=$profile[0]->advance?>"/>
            					</div>
        					    <div class="form-group col-md-6">
            						<label class="control-label">Balance:</label>
            						<input type="number" class="form-control input-datepicker" id="extra" name="balance" value="<?=$profile[0]->balance?>" readonly>
            					</div>
        					</div>
                        </div><!-- /.box-body -->
                        <div class="box-footer">
                            <div class="form-group form-actions" align="center">
                                <a class="btn btn-sm btn-danger" href="/transporter/listing"><i class="fa fa-arrow-left"></i> Back</a>
                                <button type="submit" name="btninterchange" class="btn btn-sm btn-info"><i class="fa fa-check"></i> Save Record</button>
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