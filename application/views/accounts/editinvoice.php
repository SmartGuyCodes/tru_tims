<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-print"></i> Edit Quoation <small>Add, View, Edit, Update, Delete</small>
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
                    <!--left column-->
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
                    
                    <!-- right column-->
                    <div class="col-md-12">
                      <!-- general form elements -->
                      <div class="box box-primary">
                            <div class="box-header">
                                <h3 class="box-title">Edit Quotation</h3> <small>Add, View, Edit, Update, Delete</small>
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
                                                <input type="text" class="form-control required" value="<?=$profile[0]->invoice_no; ?>" id="invoice_no" name="invoice_no" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">                                
                                            <div class="form-group">
                                                <label for="fname">Quotation date</label>
                                                <input type="text" class="form-control required" value="<?=$profile[0]->invoice_date; ?>" id="invoice_date" name="invoice_date" readonly>
                                            </div>
                                            
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">                                
                                            <div class="form-group">
                                                <label for="fname">Client Name</label>
                                                <select class="form-control" name="client" readonly>
                        							<option value="<?=$profile[0]->client; ?>"><?=$profile[0]->client;?> </option>
                        							<?php foreach($clients as $c){ ?>
                        							    <!--<option value="<?=$c->client_name?>"><?=$c->client_name?></option>-->
                        							<?php }?>
                        						</select>
                                            </div>                                    
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">                                
                                            <div class="form-group">
                                                <label for="fname">Amount</label>
                                                <input type="text" class="form-control required" value="<?=$profile[0]->amount;?>" id=" 	amount" name="amount" readonly>
                                            </div>
                                            
                                        </div>
                                    </div>
                                    <!--div class="row">
                                        <div class="col-md-12">                                
                                            <div class="form-group">
                                                <label for="fname">Discount.</label>
                                                <input type="text" class="form-control required" value="<?=$profile[0]->discount;?>" id="invoice_no" name="invoice_no">
                                            </div>
                                            
                                        </div>
                                    </div-->
                                </div>
                                <!-- /.box-body -->
                                <div class="box-footer">
                                    <!--<input type="submit" class="btn btn-primary" value="Submit" />-->
                                    <!--<input type="reset" class="btn btn-default" value="Reset" />-->
                                    <a href="/accounts/invoicelist" class="btn btn-danger">Back</a>
                                </div>
                            </form>
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
                    
                    <form class="form-bordered" action="<?php echo base_url() ?>accounts/updateInvoiceItem" method="post" >
    					<div class="form-group col-md-3">
    						<label class="control-label">Charge:</label>
    						<select id="example-select2" name="item" class="form-control" style="width: 100%;" tabindex="-1" aria-hidden="true" name="charge">
                                <option></option>
                                <option value="IDF Fee">IDF Fee</option>
                                <option value="Port Charges">Port Charges</option>
                                <option value="Shipping Line Fees">Shipping Line Fees</option>
                                <option value="Storage">Storage</option>
                                <option value="Taxes">Taxes</option>
                                <option value="Transport">Transport</option>
                                <option value="Miscellaneous">Miscellaneous</option>
                            </select>
                            <input type="hidden" class="form-control" name="invoice_no" value="<?=$profile[0]->invoice_no; ?>">
                            <input type="hidden" name="id" value="<?=$profile[0]->id;?>">
    					</div>
    					<div class="form-group col-md-4">
    						<label class="control-label">Description:</label>
    						<input type="text" class="form-control" name="description">
    					</div>
    					<div class="form-group col-md-3">
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
