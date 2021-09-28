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
        <i class="fa fa-truck"></i> Transporters Trips Management <small></small>
        <small></small>
      </h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-2">
                <a class="btn btn-danger" href="/transporter/listing">Back</a>
            </div>
            
            <div class="col-md-6">
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
            
            <div class="col-md-4 text-right">
                <div class="form-group">
                    <a class="btn btn-primary" href="<?php echo base_url(); ?>expence" data-toggle="modal" data-target="#transport_expense_modal"><i class="fa fa-bus"></i> New Transport Trip</a>
                    <div id="transport_expense_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                          <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                
                              <div class="row" style="text-align: left;">
                                  <div class="col-xs-12">
                                        <form id="transport_expense_form" class="form-bordered" method="post" style="padding: 21px;" action="/transporter/savetransportexpense">
                                            <h2>Trip Management <small></small></h2>
                        					<div class="row">
                        					    <div class="form-group col-md-3">
                            						<label class="control-label">Container No.:</label>
                            						<input type="text" class="form-control" name="container_nr" value="" required>
                            						<!--select class="form-control" name="container_nr" required>
                            						    <option></option>
                            						    <?php foreach($files as $f){ ?>
                            						        <option value="<?=$f->container_nr;?>"><?=$f->container_nr;?></option>
                            						    <?php } ?>
                            						</select-->
                            					
                            					</div>
                        					    <div class="form-group col-md-3">
                            						<label class="control-label">Date of Transport:</label>
                            						<input type="text" class="form-control input-datepicker" id="date-transport-datepicker" data-date-format="dd-mm-yyyy" name="transport_date" value="" required>
                            						<input value="<?=$id?>" type="hidden" name="transporter_id"/>
                            					</div>
                            					<div class="form-group col-md-3">
                            						<label class="control-label">Vehicle  No:</label>
                            						<!--<input type="text" class="form-control" name="vehicle_number" value="">-->
                            						<select class="form-control" name="vehicle_number" required>
                            						    <?php foreach($trucks as $t){ ?>
                            						        <option  value="<?=$t->truck_no?>"><?=$t->truck_no?></option>
                            						    <?php } ?>
                            						</select>
                            					</div>
                            					<div class="form-group col-md-3">
                            						<label class="control-label">Consignee:</label>
                            						<!--<input type="text" class="form-control" name="consignee" value="">-->
                            						<select class="form-control" name="consignee" required>
                            						    <?php foreach($consignees as $c){ ?>
                            						        <option value="<?=$c->client_name?>"><?=$c->client_name?></option>
                            						    <?php } ?>
                            						</select>
                            					</div>
                        					    <!--<div class="form-group col-md-3">-->
                            					<!--	<label class="control-label">Container Number:</label>-->
                            					<!--	<input type="text" class="form-control" id="container_no" data-date-format="dd-mm-yyyy" name="container_no" value="">-->
                            					<!--</div>-->
                        					</div>
                        					
                        					<div class="row">
                        					    <div class="form-group col-md-3">
                            						<label class="control-label">T810 No:</label>
                            						<input type="text" class="form-control input-datepicker" id="t810_no" name="t810_no" value="" required>
                            					</div>
                            					<div class="form-group col-md-3">
                            						<label class="control-label">T812 No:</label>
                            						<input type="text" class="form-control" name="t812_no" value="" required>
                            					</div>
                            					<div class="form-group col-md-3">
                            						<label class="control-label">Transport Charge</label>
                            						<input type="number" class="form-control" name="clearing_charge" value="" required>
                            					</div>
                        					    <div class="form-group col-md-3">
                            						<label class="control-label">Other Charge:</label>
                            						<input type="number" class="form-control input-datepicker" id="extra" name="extra_paid" value="" required>
                            					</div>
                        					</div>
                        
                                            <div class="form-group form-actions" align="center">
                                                <button type="submit" name="btninterchange" class="btn btn-sm btn-info"><i class="fa fa-check"></i> Save Record</button>
                                            </div>
                        				</form>
                                  </div>
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

                    <!--transporter_expense_tbl-->
                    <table id="transporter_expense_tbl" class="display" style="width:100%"> 
                        <thead>
                          <tr>
                              <th>No</th>
                             <th>Transport Date</th>
                             <th>Truck RegNo.</th>
                             <th>Consignee</th>
                             <th>Container No.</th>
                             <th>Agreed Amount </th>
                             <th>Other expences</th>
                             <th>Total Amount</th>
                             <th>Action</th>
                          </tr>
                         </thead>
                        <tbody>
                           <?php foreach($transporterExpensesRecords as $c){ ?>
                                <tr> 
                                    <td class="sorting_1"></td> 
                                    <td><?=$c->transport_date;?></td>
                                    <td><?=$c->vehicle_number;?></td> 
                                    <td><?=$c->consignee;?> </td>
                                    <td><?=$c->container_no;?></td>
                                  
                                    <td><?=formatMoney($c->clearing_charge, true);?></td>
                                    <td><?=formatMoney($c->extra_paid, true);?></td>
                                    <td><?=formatMoney($c->total, true);?></td>
                                    <td align="left">
                                        <form method="post" action="/transporter/deletetransporterexpense?id=<?=$c->id?>">
                                            <input type="hidden" name="transporter_id" value="<?=$transporter_id;?>">
                                            
                                            <p>
                                                <a href="/transporter/transporterextrafee?container_no=<?=$c->container_no;?>&truck_no=<?=$c->vehicle_number;?>&tid=<?=$id?>">
                                                    <button type="button" class="btn btn-primary btn-xs" data-toggle="tooltip" title="" data-original-title="Add Record">
                                                        <i class="fa fa-plus"></i> <span class="hidden-xs hidden-sm hidden-md"></span> 
                                                        Add Expense
                                                    </button>
                                                </a>
                                            </p>
                                            
                                            <p>
                                                <a href="/transporter/edittransporterexpense?id=<?=$c->id;?>">
                                                    <button type="button" class="btn btn-info btn-xs" data-toggle="tooltip" title="" data-original-title="Edit Record">
                                                        <i class="fa fa-edit"></i> <span class="hidden-xs hidden-sm hidden-md"></span> 
                                                        Edit Expense
                                                    </button>
                                                </a>
                                            </p>
                                            
                                            <button type="submit" name="del" class="btn btn-danger btn-xs" data-toggle="tooltip" title="" data-original-title="Delete Record"><i class="fa fa-trash"></i> Delete Expense</button>
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
