<?php
    function formatMoney($number, $fractional=false) {
        if ($fractional) {
            $number = sprintf('%.2f', $number);
        }
        while (true) {
            $replaced = preg_replace('/(-?\d+)(\d\d\d)/', '$1,$2', $number);
            if ($replaced != $number) {
                $number = $replaced;
            } else {
                break;
            }
        }
        return $number;
    } 
?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-rotate-right"></i> Payment List  <small>Add, Edit, Update &amp; Delete</small>
        <small></small>
      </h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-xs-12 text-right">
                <div class="form-group">
                    <a class="btn btn-primary" data-toggle="modal" data-target="#pay_non_transporter_modal"><i class="fa fa-plus"></i> Pay Non-Transporters</a> 
                    <!-- Modal -->
                    <div class="modal fade" id="pay_non_transporter_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                      <div class="modal-dialog" role="document">
                        <div class="modal-content" style="text-align: left">
                          <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Payment Form</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <div class="modal-body">
                              <form class="form-bordered" method="post" action="/accounts/nontranspayment">
                    		  	<div class="form-group col-md-4">
                    				<label class="control-label">Voucher:</label>
                    				<input type="text" class="form-control" readonly="" name="voucher_no" value="<?=$voucherNo?>">
                    			</div>
                    			<div class="form-group col-md-4">
                    				<label class="control-label">Transaction Date:</label>
                    				<input type="text" class="form-control" name="payment_date" value="<?=date("d-m-Y")?>" required>
                    			</div>
                    			<div class="form-group col-md-4">
                    				<label class="control-label">Payment Mode:</label>
                    				<select class="form-control" name="payment_mode" required>
                    					<option></option>
                    					<option value="CASH">CASH</option>
                    					<option value="CHEQUE">CHEQUE</option>
                    					<option value="DEPOSIT SLIP">DEPOSIT SLIP</option>
                    					<option value="EFT">EFT</option>
                    					<option value="MOBILE MONEY TRANSFER">MOBILE MONEY TRANSFER</option>
                    					<option value="TT/WIRE/TRANSFER">TT/WIRE/TRANSFER</option>
                    				</select>
                    			</div>
                    			<div class="form-group col-md-6">
            						<label class="control-label">Payee's Name:</label>
            						<input type="text" class="form-control" name="payee_name" value="" required>
            						<!--<select class="form-control" name="payee_name">-->
            						<!--    <?php foreach($clients as $s){ ?>-->
            						<!--        <option value="<?=$s->client_name;?>"><?=$s->client_name;?></option>-->
            						<!--    <?php } ?>-->
            						<!--</select>-->
            					</div>
                    			<div class="form-group col-md-6">
                    				<label class="control-label">Amount:</label>
                    				<input type="text" class="form-control" name="amount" required>
                    				<!--<input type="hidden" class="form-control" name="tp" value="NO">-->
                    			</div>
                    			
                    			<div class="form-group col-md-12">
            						<label class="control-label">Reference:</label>
            						<textarea class="form-control" rows="4" name="ref"></textarea>
            					</div>
                    			 
                          		<div class="form-group form-actions" align="center">
                                    <button type="submit" name="btnpay" class="btn btn-sm btn-info"><i class="fa fa-check"></i> Make Payment</button>
                                </div>
                            </form>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <!--<button type="button" class="btn btn-primary">Save changes</button>-->
                          </div>
                        </div>
                      </div>
                    </div>
                    
                    <a class="btn btn-success" data-toggle="modal" data-target="#pay_transporter_modal"><i class="fa fa-money"></i> Pay Transporter</a>
                    <!-- Modal -->
                    <div class="modal fade" id="pay_transporter_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                      <div class="modal-dialog" role="document">
                        <div class="modal-content" style="text-align: left">
                          <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Transporter Payment Form</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <div class="modal-body">
                            <form class="form-bordered" method="post" action="/accounts/nontranspayment">
                    		  	<div class="form-group col-md-3">
                    				<label class="control-label">Voucher:</label>
                    				<input type="text" class="form-control" readonly="" name="voucher_no" value="<?=$voucherNo?>">
                    			</div>
                    			<div class="form-group col-md-3">
                    				<label class="control-label">Transaction Date:</label>
                    				<input type="text" class="form-control" name="payment_date" value="<?=date("d-m-Y")?>" required>
                    				<input type="hidden" name="transporter_id" value="<?=$transporter_id?>"/>
                    			</div>
                    			<!--files-->
                    			<div class="form-group col-md-3">
                    				<label class="control-label" >Container No.</label>
                    				<select class="form-control" name="container_no" required>
                    					<option></option>
                    					<?php foreach($files as $f){ ?>
                    					<option value="<?=$f->container_nr?>"><?=$f->container_nr?></option>
                    					<?php } ?>
                    				</select>
                    			</div>
                    			<div class="form-group col-md-3">
                    				<label class="control-label">Payment Mode:</label>
                    				<select class="form-control" name="payment_mode" required>
                    					<option></option>
                    					<option value="CASH">CASH</option>
                    					<option value="CHEQUE">CHEQUE</option>
                    					<option value="DEPOSIT SLIP">DEPOSIT SLIP</option>
                    					<option value="EFT">EFT</option>
                    					<option value="MOBILE MONEY TRANSFER">MOBILE MONEY TRANSFER</option>
                    					<option value="TT/WIRE/TRANSFER">TT/WIRE/TRANSFER</option>
                    				</select>
                    			</div>
                    			<div class="form-group col-md-6">
            						<label class="control-label">Payee's Name:</label>
            						<input type="hidden" class="form-control" name="user_type" value="t">
            						<select class="form-control" name="payee_name" required>
            						    <?php foreach($transporters as $s){ ?>
            						        <option value="<?=$s->transporter_name;?>"><?=$s->transporter_name;?></option>
            						    <?php } ?>
            						</select>
            					</div>
                    			<div class="form-group col-md-6">
                    				<label class="control-label">Amount:</label>
                    				<input type="text" class="form-control" name="amount" required>
                    				<!--<input type="hidden" class="form-control" name="tp" value="NO">-->
                    			</div>
                    			
                    			<div class="form-group col-md-12">
            						<label class="control-label">Reference:</label>
            						<textarea class="form-control" rows="4" name="ref"></textarea>
            					</div>
                    			 
                          		<div class="form-group form-actions" align="center">
                                    <button type="submit" name="btnpay" class="btn btn-sm btn-info"><i class="fa fa-check"></i> Make Payment</button>
                                </div>
                            </form>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <!--<button type="button" class="btn btn-primary">Save changes</button>-->
                          </div>
                        </div>
                      </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
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
            <div class="col-xs-12">
              <div class="box">
                <div class="box-body table-responsive">                   
                    <h4 style="text-align: center;">Payment List</h4>
                    <table id="example" class="display" style="width:100%">
                     <thead>
                          <tr>
                              <th>#</th>
                             <th>Voucher No.</th>
                             <th>Date</th>
                             <th>Payee</th>
                             <th>Amount</th>
                             <th>Payment Mode</th>
                             <th>Action</th>
                          </tr>
                     </thead>
                       <tbody>
                           <?php foreach($payments as $c){ ?>
                                <tr> 
                                    <td class="sorting_1"></td> 
                                    <td><?=$c->voucher_no;?></td> 
                                    <td><?=$c->payment_date;?> </td>
                                    <td><?=$c->transporter;?></td>
                                    <td><?=formatMoney($c->amount, true);?></td>
                                    <td><?=$c->payment_mode;?></td>
                                    <td align="left">
                                        <form method="post" action="/accounts/deletepaymentvoucher?id=<?=$c->id?>">
                                            <input type="hidden" name="delid" value="<?=$c->id;?>">
                                            <a href="/accounts/previewvoucher?id=<?=$c->id?>"><button type="button" class="btn btn-info btn-xs" data-toggle="tooltip" title="View Record" data-original-title="View Record"><i class="fa fa-eye"></i> <span class="hidden-xs hidden-sm hidden-md"></span></button></a>
                                            <!--<a href="/client/edit?id=<?=$c->id?>"><button type="button" class="btn btn-info btn-xs" data-toggle="tooltip" title="Edit Record" data-original-title="Edit Record"><i class="fa fa-edit"></i> <span class="hidden-xs hidden-sm hidden-md"></span></button></a>-->
                                           
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
