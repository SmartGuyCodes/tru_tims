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
        <i class="fa fa-rotate-right"></i> Payment List <small>Add, Edit, Update, Delete</small>
        <small></small>
      </h1>
      <hr>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-xs-12 text-right">
                <div class="form-group">
                    <a class="btn btn-primary" data-toggle="modal" data-target="#pay_non_transporter_modal"><i class="fa fa-money"></i> Receive Payment from Client</a> 
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
                              <form class="form-bordered" method="post" action="/accounts/receivepayment">
                              	<div class="form-group col-md-4">
                            		<label class="control-label">Receipt No:</label>
                            		<input type="text" class="form-control" readonly="" name="receipt_no" value="<?=$receiptNo;?>">
                            	</div>
                            
                            	<div class="form-group col-md-4">
                            		<label class="control-label">Transaction Date:</label>
                            		<input type="text" class="form-control" name="transaction_date" value="<?=date("d-m-Y")?>">
                            	</div>
                            
                            	<div class="form-group col-md-4">
                            		<label class="control-label">Amount:</label>
                            		<input type="text" class="form-control" name="amount">
                            	</div>
                            
                            	<div class="form-group col-md-4">
                            		<label class="control-label">Payment Mode:</label>
                            		<select class="form-control" name="payment_mode">
                            			<option></option>
                            			<option value="CASH">CASH</option>
                            			<option value="TRANSFER">TRANSFER</option>
                            			<option value="CHEQUE">CHEQUE</option>
                            			<option value="DEPOSIT SLIP">DEPOSIT SLIP</option>
                            			<option value="MOBILE MONEY TRANSFER">MOBILE MONEY TRANSFER</option>
                            		</select>
                            	</div>
                            
                            	<div class="form-group col-md-8">
                            		<label class="control-label">Client:</label>
                            		<input type="hidden" class="form-control" name="user_type" value="c">
                            		<select class="form-control" name="client">
                            		    <?php foreach($clients as $c){ ?>
                            		        <option value="<?=$c->client_name?>"><?=$c->client_name;?></option>
                            		    <?php } ?>
                            		</select>
                            	</div>
                            	
                            	<div class="form-group col-md-12">
                            		<label class="control-label">Transaction Details:</label>
                            		<textarea class="form-control" name="transaction_details"></textarea>
                            	</div>
                            		
                            	<div class="form-group form-actions" align="center">
                                     <button type="submit" name="btnpay" class="btn btn-sm btn-info"><i class="fa fa-check"></i> 	Receive Payment
                                     </button>
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
            <div class="col-xs-12">
                
              <div class="box">
                <div class="box-body table-responsive">                
                    <h4 style="text-align: center;">Payments</h4>
                    <table id="example" class="display" style="width:100%">
                     <thead>
                          <tr>
                              <th>#</th>
                             <th>Receipt No.</th>
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
                                    <td><?=$c->receipt_no;?></td> 
                                    <td><?=$c->transaction_date;?> </td>
                                    <td><?=$c->client;?></td>
                                    <td><?=formatMoney($c->amount, TRUE);?></td>
                                    <td><?=$c->payment_mode;?></td>
                                    <td align="left">
                                        <form method="post" action="/accounts/deletepaymentreceipt?id=<?=$c->id?>">
                                            <input type="hidden" name="delid" value="<?=$c->id;?>">
                                            <a href="/accounts/previewreceipt?id=<?=$c->id?>"><button type="button" class="btn btn-info btn-xs" data-toggle="tooltip" title="Edit Record" data-original-title="Edit Record"><i class="fa fa-eye"></i> <span class="hidden-xs hidden-sm hidden-md"></span></button></a>
                                            <!--<a href="/client/edit?id=<?=$c->id?>"><button type="button" class="btn btn-info btn-xs" data-toggle="tooltip" title="Edit Record" data-original-title="Edit Record"><i class="fa fa-edit"></i> <span class="hidden-xs hidden-sm hidden-md"></span></button></a>-->
                                           
                                            <button type="submit" name="del" class="btn btn-danger btn-xs" data-toggle="tooltip" title="" data-original-title="Delete Record"><i class="fa fa-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                           <?php } ?>
                       </tbody>
                    
                    </table>
                    <hr/>
                    <hr/>
                    <iframe width="100%" height="500px" src="/accounts/letarisitipdf?id=<?=$id;?>"></iframe>
                </div>
              </div>
            </div>
        </div>
    </section>
</div>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/common.js" charset="utf-8"></script>
