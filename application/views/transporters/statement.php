<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-truck"></i> Transporters Management <small></small>
        <small></small>
      </h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-xs-12 text-right">
                <div class="form-group">
                    <a class="btn btn-primary" href="<?php echo base_url(); ?>transporter/add" ><i class="fa fa-truck"></i> Add New Transporter</a>
                    
                    <a class="btn btn-primary" href="<?php echo base_url(); ?>reports/truckslist"><i class="fa fa-glass"></i> List of Trucks</a>
                    <!--<a class="btn btn-primary" href="<?php echo base_url(); ?>expence" data-toggle="modal" data-target="#transport_expense_modal"><i class="fa fa-money"></i> Add Transport Expence</a>-->
                    <div id="transport_expense_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                      <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            
                          <div class="row" style="text-align: left;">
                              <div class="col-xs-12">
                                    <form id="transport_expense_form" class="form-bordered" method="post" style="padding: 21px;" action="/transporter/savetransportexpense">
                                        <h2>Transport Expense <small></small></h2>
                    					<div class="row">
                    					    <div class="form-group col-md-3">
                        						<label class="control-label">Date of Transport:</label>
                        						<input type="text" class="form-control input-datepicker" id="date-transport-datepicker" data-date-format="dd-mm-yyyy" name="transport_date" value="">
                        					</div>
                        					<div class="form-group col-md-3">
                        						<label class="control-label">Vehicle  No:</label>
                        						<input type="text" class="form-control" name="vehicle_number" value="">
                        					</div>
                        					<div class="form-group col-md-3">
                        						<label class="control-label">Consignee:</label>
                        						<!--<input type="text" class="form-control" name="consignee" value="">-->
                        						<select class="form-control">
                        						    <?php foreach($consignees as $c){ ?>
                        						        <option value="<?=$c->client_name?>"><?=$c->client_name?></option>
                        						    <?php } ?>
                        						</select>
                        					</div>
                    					    <div class="form-group col-md-3">
                        						<label class="control-label">Container No.:</label>
                        						<input type="text" class="form-control" id="container_no" data-date-format="dd-mm-yyyy" name="container_no" value="">
                        					</div>
                    					</div>
                    					
                    					<div class="row">
                        					<div class="form-group col-md-4">
                        						<label class="control-label">T812 :</label>
                        						<input type="number" class="form-control" name="t812_no" value="">
                        					</div>
                        					
                        						<div class="form-group col-md-4">
                        						<label class="control-label">T810  :</label>
                        						<input type="number" class="form-control" name="t812_no" value="">
                        					</div>
                        					<div class="form-group col-md-4">
                        						<label class="control-label">Clearing charge</label>
                        						<input type="number" class="form-control" name="clearing_charge" value="">
                        					</div>
                    					    <div class="form-group col-md-4">
                        						<label class="control-label">Extra Paid:</label>
                        						<input type="text" class="form-control input-datepicker" id="extra" name="extra_paid" value="" >
                        					</div>
                    					    <!--<div class="form-group col-md-3">-->
                        					<!--	<label class="control-label">Total:</label>-->
                        					<!--	<input type="text" class="form-control input-datepicker" id="total" name="total" value="0" readonly>-->
                        					<!--</div>-->
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

                    <table id="transportation_tbl" class="display">
                        <thead>
                          <tr>
                             <th>No</th>
                             <th>Transport Name</th>
                             <th>Contact person</th>
                             <th>Email</th>
                             <th>Telephone</th>
                             <th>Action</th>
                          </tr>
                         </thead>
                        <tbody>
                           <?php foreach($transporterRecords as $c){ ?>
                                <tr> 
                                    <td class="sorting_1"></td> 
                                    <td><?=$c->transporter_name;?></td> 
                                    <td><?=$c->contact_person;?> </td>
                                    <td><?=$c->email;?></td>
                                    <td><?=$c->mobile_no;?></td>
                                    <td align="left">
                                        <form method="post" action="/transporter/deletetransporter?id=<?=$c->id;?>">
                                            <a href="/transporter/edit?id=<?=$c->id?>">
                                                <button type="button" class="btn btn-info btn-xs" data-toggle="tooltip" title="" data-original-title="Edit Record">
                                                    <i class="fa fa-edit"></i> <span class="hidden-xs hidden-sm hidden-md"></span>
                                                    Edit
                                                </button>
                                            </a>
                                            <a href="/transporter/transporterTrucks?id=<?=$c->id;?>">
                                                <button type="button" class="btn btn-success btn-xs" data-toggle="tooltip" title="" data-original-title="Trucks">
                                                    <i class="fa fa-car"></i><span class="hidden-xs hidden-sm hidden-md"></span>
                                                    Trucks
                                                </button>
                                            </a>
                                            <a href="/transporter/transportexpenses?id=<?=$c->id?>">
                                                <button type="button" class="btn btn-primary btn-xs" data-toggle="tooltip" title="Expenses" data-original-title="Expenses">
                                                    <i class="fa fa-money"></i> <span class="hidden-xs hidden-sm hidden-md"></span>
                                                    Expenses
                                                </button>
                                            </a>
                                            
                                            <a href="/transporter/transportstatement?id=<?=$c->id?>">
                                                <button type="button" class="btn btn-primary btn-xs" data-toggle="tooltip" title="Statement" data-original-title="Statement">
                                                    <i class="fa fa-book"></i> <span class="hidden-xs hidden-sm hidden-md"></span>
                                                    Statement
                                                </button>
                                            </a>
                                            
                                            <button type="submit" name="del" class="btn btn-danger btn-xs" data-toggle="tooltip" title="Delete" data-original-title="Delete Record">
                                                <i class="fa fa-trash"></i><span class="hidden-xs hidden-sm hidden-md"></span>
                                                Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                           <?php } ?>
                       </tbody>
                       
                    </table>
    
                </div>
              </div>
            </div>
            
            <div class="col-xs-12">
                <div class="box">             
                    <iframe width="100%" height="500px" src="/transporter/transportstatementpdf?id=<?=$id?>"></iframe>
                </div>
            </div>
        </div>
    </section>
</div>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/common.js" charset="utf-8"></script>
