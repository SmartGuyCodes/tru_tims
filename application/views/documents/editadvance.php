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
        <i class="fa fa-money"></i> Client Advance Payments
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
            <div class="col-xs-4">
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
                    
                    <!--Right column-->
                    <div class="col-md-12">
                      <!-- general form elements -->
                      <div class="box box-primary">
                            <div class="box-header">
                                <h3 class="box-title">Client Advance Payment Form</h3>
                            </div>
                            <!-- /.box-header -->
                            <!-- form start -->
                            <?php $this->load->helper("form"); ?>
                            
                              <form style="padding: 5px;" class="form-bordered" method="post" action="/documents/updateadvance?id=<?=$id?>"> 
                                  <div class="row">
                                  	<div class="form-group col-md-12">
                                		<label class="control-label">Receipt No:</label>
                                		<input type="text" class="form-control" readonly="" name="receipt_no" value="<?=$receiptNo?>">
                                	</div>
                                
                                	<div class="form-group col-md-12">
                                		<label class="control-label">Transaction Date:</label>
                                		<input type="text" class="form-control" name="transaction_date" value="<?=date("Y-m-d H:i:s")?>" required="" readonly>
                                	</div>
                                
                                	<div class="form-group col-md-12">
                                		<label class="control-label">File no:</label>
                                		<input type="text" class="form-control" name="file_no" value="<?=$file_no?>" required="" readonly>
                                	</div>
                                  </div>
                                  
                                  <div class="row">
                                
                                	<div class="form-group col-md-12">
                                		<label class="control-label">Paying Client:</label>
                                		<input type="text" class="form-control" required="" name="client" value="<?=$client_id;?>" readonly>
                                	</div>
                                
                                	<div class="form-group col-md-12">
                                		<label class="control-label">Amount:</label>
                                		<input type="text" class="form-control" name="amount" required="">
                                	</div>
                                	
                                	<div class="form-group col-md-12">
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
                                  </div>
                            	  
                            	  <div class="row">
                                	<div class="form-group col-md-12">
                                		<label class="control-label">Transaction Details:</label>
                                		<textarea class="form-control" name="transaction_details"></textarea>
                                	</div>
                                  </div>
                            		
                            	<div class="form-group form-actions" align="center">
                                     <button type="submit" name="btnpay" class="btn btn-sm btn-info"><i class="fa fa-check"></i> 	Receive Payment
                                     </button>
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
                        <h3 class="box-title">List of Advance Payments</h3>
                    </div>                

                    <table id="transporter_tbl" class="display" style="width:100%">
                       <thead>
                          <tr>
                             <th>No</th>
                             <th>DATE</th>
                             <th>AMOUNT</th>
                             <th>NOTE</th>
                             <th>ACTION</th>
                          </tr>
                       </thead>
                       <tbody>
                            <?php foreach($payments as $c){ ?>
                                <tr> 
                                    <td class="sorting_1"></td> 
                                    <td><?=$c->transaction_date;?></td> 
                                    <td><?=number_format($c->amount, 2);?> </td>
                                    <td><?=$c->ref;?> </td>
                                    <td align="center">
                                        <form method="post" action="/documents/deleteadvance?id=<?=$c->id;?>">
                                            <input type="hidden" name="delid" value="<?=$c->id?>">
                                            <!--<a href="/documents/editadvance?id=<?=$c->id;?>">-->
                                            <!--    <button type="button" class="btn btn-info btn-xs" data-toggle="tooltip" title="" data-original-title="Edit Record">-->
                                            <!--        <i class="fa fa-edit"></i> <span class="hidden-xs hidden-sm hidden-md"></span>-->
                                            <!--    </button>-->
                                            <!--</a>-->
                                            <button type="submit" name="del" class="btn btn-danger btn-xs" data-toggle="tooltip" title="" data-original-title="Delete Record"><i class="fa fa-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                           <?php } ?>
                       </tbody>
                       <tfoot>
                        
                       </tfoot>
                    </table>

                </div>
              </div>
            </div>
        </div>
    </section>
</div>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/common.js" charset="utf-8"></script>
