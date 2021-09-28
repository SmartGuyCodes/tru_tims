<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-paperclip"></i> Attach Bond 
        <small>Attach &amp; Update</small>
      </h1>
      <hr>
    </section>
    <section class="content">
        <!--div class="row">
            <div class="col-xs-12 text-right">
                <div class="form-group">
                    <a class="btn btn-primary" href="<?php echo base_url(); ?>transporter/add"><i class="fa fa-plus"></i> Add New</a>
                </div>
            </div>
        </div-->
        
        <div class="row">
            <div class="col-xs-12">
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
                                <h3 class="box-title">Attach Bond</h3>
                            </div>
                            <!-- /.box-header -->
                            <!-- form start 
                            // Alafu the second form would be to select the container no. And attach bond using value of the container so itakuwa na 
                            //date, container, consignment , from, to, bond value.
                            -->
                            <?php $this->load->helper("form"); ?>
                            <form role="form" id="addUser" action="<?php echo base_url() ?>bond/addattachment" method="post" role="form">
                                <div class="box-body">
                                    <div class="row">
                                        <div class="form-group col-md-6">
                    						<label class="control-label">Container No.</label>
                    						<input type="text" class="form-control" id="" name="container_nr" value="" required>
                    					</div>
                    					
                    					<div class="form-group col-md-6">
                    						<label class="control-label">Consignment:</label>
                    						<input type="text" class="form-control" id="consignment" name="consignment" value="" required>
                    					</div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="form-group col-md-4">
                    						<label class="control-label">Date</label>
                    						<input type="text" class="form-control input-datepicker" id="interchange_entry_date" data-date-format="dd-mm-yyyy" name="bond_date" value="" required>
                    						<!--<input type="hidden" class="form-control" id="" name="container_nr" value="<?=$container_no;?>" >-->
                    						<!--<input type="hidden" class="form-control" id="consignment" name="consignment" value="<?=$consignment;?>">-->
                    					</div>
                                        
                                        <div class="form-group col-md-4">
                    						<label class="control-label">Bond:</label>
                    						<select class="form-control" name="bond_name">
                    						    <option></option>
                    						    <?php foreach($bonds as $b){ ?>
                    						        <option value="<?=$b->bond_name;?>"><?=$b->bond_name;?></option>
                    						    <?php }?>
                    						</select>
                    					</div>
                                        
                                        <div class="form-group col-md-4">
                    						<label class="control-label">Bond Charges:</label>
                    						<input type="number" class="form-control" id="charges " name="charges" value="0" required>
                    					</div>
                                    </div>
                                    
                                    <div class="row">
                    					<div class="form-group col-md-6">
                    						<label class="control-label">From:</label>
                    						<input type="text" class="form-control" id="from " name="from" value="" required>
                    					</div>
                    					
                    					<div class="form-group col-md-6">
                    						<label class="control-label">To:</label>
                    						<input type="text" class="form-control" id="to " name="to" value="" required>
                    					</div>
                                    </div>
                                   
                                </div>
                                <!-- /.box-body -->
                                <div class="box-footer">
                                    <a class="btn btn-danger" href="/bond/listing"><i class="fa fa-arrow-left"></i> Back</a>
                                    <button type="submit" class="btn btn-primary"><i class="fa fa-paperclip"></i> Attach</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/common.js" charset="utf-8"></script>
