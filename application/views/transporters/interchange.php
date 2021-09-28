<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-exchange"></i> Interchange 
        <small>Add, Edit, Update &amp; Manage</small>
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
                                <h3 class="box-title">Interchange Form</h3>
                            </div>
                            <!-- /.box-header -->
                            <!-- form start -->
                            <?php $this->load->helper("form"); ?>
                            <form role="form" id="addUser" action="<?php echo base_url() ?>transporter/addNewInterchange" method="post" role="form">
                                <div class="box-body">
                                    <div class="row">
                                        <div class="form-group col-md-2">
                    						<label class="control-label">Entry Date:</label>
                    						<input type="text" class="form-control input-datepicker" id="interchange_entry_date" data-date-format="dd-mm-yyyy" name="entry_date" value="">
                    						<!--<input type="text" class="form-control" id="interchange_entry_date " name="entry_date " value="">-->
                    					</div>
                    					
                    					<div class="form-group col-md-4">
                    						<label class="control-label">Drivers Name:</label>
                    						<input type="text" class="form-control" id="driver " name="driver" value="N/A">
                    					</div>
                                        
                                        <div class="form-group col-md-2">
                    						<label class="control-label">Charges:</label>
                    						<input type="text" class="form-control" id="charges " name="charges" value="0">
                    					</div>
                    					
                    					<div class="form-group col-md-4">
                    						<label class="control-label">Deposit paid:</label>
                    						<select class="form-control" name="deposit" required>
                    						    <option></option>
                    						    <option value="PAID">PAID</option>
                    						    <option value="NOT PAID">NOT PAID</option>
                    						</select>
                    					</div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="form-group col-md-4">
                    						<label class="control-label">Container No:</label>
                    						<input type="text" class="form-control" name="container_no" value="N/A">
                    					</div>
                    					
                                        <div class="form-group col-md-4">
                    						<label class="control-label">Interchange Date:</label>
                    						<input type="text" class="form-control input-datepicker" id="interchange_date" data-date-format="dd-mm-yyyy" name="interchange_date" value="">
                    					</div>
                    					
                    					<div class="form-group col-md-4">
                    						<label class="control-label">Depot:</label>
                    						<select class="form-control" name="depot">
                    							<option></option>
                    							<option value="HAKIKA TRANSPORT SERVICES LTD">HAKIKA TRANSPORT SERVICES LTD</option>
                    							<option value="DODWEL INWARD INTERCHANGE">DODWEL INWARD INTERCHANGE</option>
                    							<option value="FORTUNE CONTAINER DEPOT">FORTUNE CONTAINER DEPOT</option>
                    							<option value="LOGISTICS SOLUTIONS LTD">LOGISTICS SOLUTIONS LTD</option>
                    							<option value="MVITA CONTAINER DEPOT">MVITA CONTAINER DEPOT</option>
                    							<option value="OTHERS">OTHERS</option>
                    						</select>
                    					</div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="form-group col-md-4">
                    						<label class="control-label">Container Size:</label>
                    						<select class="form-control" name="container_size">
                    							<option></option>
                    							<option value="20ft">20 FT</option>
                    							<option value="40ft">40 FT</option>
                    						</select>
                    					</div>
                    					
                    					<div class="form-group col-md-4">
                    						<label class="control-label">Truck No:</label>
                    						<input type="text" class="form-control" name="truck_no" value="">
                    					    <div></div>
                    					</div>
                    					
                    					<div class="form-group col-md-4">
                    						<label class="control-label">Shipping Line:</label>
                    						<select class="form-control" name="agent_shipping_line">
                    						    <option></option>
                    						    <?php foreach($shippinglines as $s){ ?>
                    							    <option value="<?=$s->shipper_name?>"><?=$s->shipper_name?></option>
                    						    <?php }?>
                    						</select>
                    					</div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="form-group col-md-4">
                    						<label class="control-label">Client name:</label>
                    						<select name="client_name" class="form-control" required>
                    						    <option></option>
                    						    <?php foreach($clients as $c){ ?>
                    						        <option value="<?=$c->client_name;?>"><?=$c->client_name;?></option>
                    						    <?php } ?>
                    						</select>
                    					</div>
                    					
                                        <div class="form-group col-md-4">
                    						<label class="control-label">Consignee name:</label>
                    						<select name="client_name" class="form-control" required>
                    						    <option></option>
                    						    <?php foreach($consignees as $c){ ?>
                    						        <option value="<?=$c->client_name;?>"><?=$c->client_name;?></option>
                    						    <?php } ?>
                    						</select>
                    					</div>
                    					
                    					<div class="form-group col-md-4">
                    						<label class="control-label">Transporter name:</label>
                    						<select name="client_name" class="form-control" required>
                    						    <option></option>
                    						    <?php foreach($transporters as $t){ ?>
                    						        <option value="<?=$t->transporter_name;?>"><?=$t->transporter_name;?></option>
                    						    <?php } ?>
                    						</select>
                    					</div>
                                    </div>
                                   
                                </div>
                                <!-- /.box-body -->
                                <div class="box-footer">
                                    <input type="submit" class="btn btn-primary" value="Submit" />
                                    <input type="reset" class="btn btn-default" value="Reset" />
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
