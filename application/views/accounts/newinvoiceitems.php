<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-print"></i> Create Quotation : Add Quotation Items <small>Add, View, Edit, Update, Delete</small>
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
                            <form role="form" id="addUser" action="<?php echo base_url() ?>accounts/addNewInvoiceItems" method="post" role="form">
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-md-12">                                
                                            <div class="form-group">
                                                <label for="fname">Quotation No.</label>
                                                <input type="text" class="form-control required" value="<?php echo $invoice_no; ?>" id="invoice_no" name="invoice_no" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">                                
                                            <div class="form-group">
                                                <label for="fname">Quotation Date</label>
                                                <input type="text" class="form-control required" value="<?=$invoiceInfo[0]->invoice_date;?>" id="invoice_date" name="invoice_date" >
                                            </div>
                                            
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">                                
                                            <div class="form-group">
                                                <label for="fname">Client Name</label>
                                                <!--select class="form-control" name="client">
                        							<option></option>
                        							<?php foreach($clients as $c){ ?>
                        							    <option value="<?=$c->client_name?>"><?=$c->client_name?></option>
                        							<?php }?>
                        						</select-->
                                                <input type="text" class="form-control required" value="<?=$invoiceInfo[0]->client;?>" id="client" name="client" readonly>
                                            </div>                                    
                                        </div>
                                       
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">                                
                                            <div class="form-group">
                                                <label for="fname">Amount</label>
                                                <input type="text" class="form-control required" value="<?=$invoiceInfo[0]->amount;?>" id="invoice_no" name="invoice_no" readonly>
                                            </div>
                                           
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">                                
                                            <div class="form-group">
                                                <label for="fname">Discount.</label>
                                                <input type="text" class="form-control required" value="<?=$invoiceInfo[0]->discount;?>" id="invoice_no" name="invoice_no" readonly>
                                            </div>
                                            
                                        </div>
                                    </div>
                                </div>
                                <!-- /.box-body -->
                                <div class="box-footer">
                                    <a href="/accounts/invoicelist" class="btn btn-primary"><i class="fa fa-arrow-left"></i> BACK</a>
                                    <!--input type="submit" class="btn btn-primary" value="UPDATE" />
                                    <input type="reset" class="btn btn-default" value="Reset" /-->
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
            </div>
            
            <div class="col-xs-8">
                
              <div class="box">
                <div class="box-body table-responsive padding">   
                    <div class="box-header">
                        <h3 class="box-title">Add Items</h3>
                    </div>        
                    
                    <form class="form-bordered" action="<?php echo base_url() ?>accounts/addInvoiceItem" method="post" >
    					<div class="form-group col-md-5">
    						<label class="control-label">ChargeS:</label>
    						<select id="example-select2" name="item" class="form-control" style="width: 100%;" tabindex="-1" aria-hidden="true" name="charge">
                                <option></option>
                                <option value="Transport">Dry Container Transport upto 30 tonnes Weigh bridge</option>
                                 <option value="Transport">Reefer Container - Transport upto 30 tonnes Weigh bridge</option>
                                  <option value="Transport1">Dry Container Excess Tonnage </option>
                                    <option value="Transport1">Reefer- Container Excess Tonnage </option>
                                <option value="clearing20ft">Dry Containers -Clearing  Charges 20FT</option>
                                <option value="clearing40ft">Dry Containers -Clearing  Charges 40FT</option>
                                <option value="reefer20ft">Reefer Containers -Clearing  Charges 20FT</option>
                                <option value="reefer40ft">Reefer Containers -Clearing  Charges 40FT</option>
                                <option value="Port Charges">Storage  Charges </option>
                                   <option value="Port Charges">Port Charges</option>
                                <option value="Shipping Line Fees">Shipping Line Fees</option>
                                <option value="Storage">Storage</option>
                                <option value="dumarage">Dumarage Charges</option>
                                <option value="damages charges">Damages charges</option>
                                <option value="Miscellaneous">Miscellaneous</option>
                                     <option value="extra days">Extra storage days</option>
                        
                                <option value="Miscellaneous">Miscellaneous</option>
                            </select>
                            <input type="hidden" class="form-control" name="invoice_no" value="<?=$invoice_no?>">
    					</div>
    					<div class="form-group col-md-3">
    						<label class="control-label">Description:</label>
    						<input type="text" class="form-control" name="description">
    					</div>
    					<div class="form-group col-md-2">
    						<label class="control-label">Amount:</label>
    						<input type="text" class="form-control" name="amount">
    					</div>
    					<div class="form-group col-md-2">
    						<label class="control-label">&nbsp;</label>
    						<button type="submit" name="btnitem" class="btn btn-sm btn-info form-control"><i class="fa fa-check"></i> Submit</button>
    					</div>
                    </form>
                    <br />
                    
                    <table id="transporter_tbl" class="display" style="width:100%">
                       <thead>
                          <tr>
                             <th>#</th>
                             <th>Item</th>
                             <th>Details</th>
                             <th>Amount</th>
                             <th>Action</th>
                          </tr>
                       </thead>
                       <tbody>
                           <!--
                            [invoice_no] => INV025670
                            [charge] => Taxes
                            [description] => kjskjskjsjk
                            [amount] => 10090
                           -->
                            <?php foreach($items as $c){ ?>
                                <tr> 
                                    <td class="sorting_1"></td> 
                                    <td><?=$c->charge;?></td> 
                                    <td><?=$c->description;?> </td>
                                    <td><?=$c->amount;?> </td>
                                    <td align="center">
                                        <form method="post" action="/accounts/deleteinvoiceitem">
                                            <input type="hidden" name="delid" value="<?=$c->id?>">
                                            <button type="submit" name="del" class="btn btn-danger btn-xs" data-toggle="tooltip" title="" data-original-title="Delete Record"><i class="fa fa-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                           <?php } ?>
                       </tbody>
                    </table>

                </div>
              </div>
            </div>
        </div>
    </section>
</div>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/common.js" charset="utf-8"></script>