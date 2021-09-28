<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-print"></i> Create Quotation  <small>Add, Edit, Update</small>
        <small></small>
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
            <div class="col-xs-4">
                <div class="row">
                    <!-- left column -->
                    <div class="col-md-12">
                      <!-- general form elements -->
                      <div class="box box-primary">
                            <div class="box-header">
                                <h3 class="box-title">Add Quotation</h3>
                            </div>
                            <!-- /.box-header -->
                            <!-- form start -->
                            <?php $this->load->helper("form"); ?>
                            <form role="form" id="addUser" action="<?php echo base_url() ?>accounts/addNewInvoice" method="post" role="form">
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-md-12">                                
                                            <div class="form-group">
                                                <label for="fname">Quotation no.</label>
                                                <input type="text" class="form-control required" value="<?php echo $invNo; ?>" id="invoice_no" name="invoice_no">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">                                
                                            <div class="form-group">
                                                <label for="fname">Quotation date</label>
                                                <input type="text" class="form-control required" value="<?=date("d-m-Y");?>" id="invoice_date" name="invoice_date" readonly>
                                            </div>
                                            
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">                                
                                            <div class="form-group">
                                                <label for="fname">Client Name</label>
                                                <select class="form-control" name="client">
                        							<option></option>
                        							<?php foreach($clients as $c){ ?>
                        							    <option value="<?=$c->client_name?>"><?=$c->client_name?></option>
                        							<?php }?>
                        						</select>
                                            </div>                                    
                                        </div>
                                    </div>
                                    <!--div class="row">
                                        <div class="col-md-12">                                
                                            <div class="form-group">
                                                <label for="fname">Amount due</label>
                                                <input type="text" class="form-control required" value="<?php echo set_value('invoice_no'); ?>" id="invoice_no" name="invoice_no">
                                            </div>
                                            
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">                                
                                            <div class="form-group">
                                                <label for="fname">Discount.</label>
                                                <input type="text" class="form-control required" value="<?php echo set_value('invoice_no'); ?>" id="invoice_no" name="invoice_no">
                                            </div>
                                            
                                        </div>
                                    </div-->
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
            
            <div class="col-md-8">
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
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/common.js" charset="utf-8"></script>
