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
        <i class="fa fa-certificate"></i> Registered Bonds  <small>Register, Attach &amp; Release</small>
        <small></small>
      </h1>
    </section>
    <section class="content">
        <div class="row">
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
            <div class="col-xs-4 text-right">
                <div class="form-group">
                    <a class="btn btn-success" data-toggle="modal" data-target="#bond_register_modal"><i class="fa fa-plus"></i> Register New Bond</a> 
                    <!-- Modal -->
                    <div class="modal fade" id="bond_register_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                      <div class="modal-dialog" role="document">
                        <div class="modal-content" style="text-align: left">
                          <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">New Bond Form</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <div class="modal-body">
                              <form class="form-bordered" method="post" action="/bond/addNewBond">
                    		  	<div class="row">
                    		  	    <div class="form-group col-md-6">
                        				<label class="control-label">Bond Name</label>
                        				<input type="text" class="form-control" name="bond_name" value="" required>
                        			</div>
                        			
                        			<div class="form-group col-md-6">
                        				<label class="control-label">Bond Value</label>
                        				<input type="text" class="form-control" name="bond_value" value="0" required>
                        			</div>
                    		  	</div>
                    			
                    			<div class="row">
                              		<div class="form-group form-actions" align="center">
                                        <button type="submit" name="btnpay" class="btn btn-sm btn-info"><i class="fa fa-check"></i> Register Bond</button>
                                    </div>
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
                    <!---->
                    <a class="btn btn-warning" href="<?php echo base_url(); ?>reports/bondreports"><i class="fa fa-cog fa-spin"></i> View Bond Report</a>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                <div class="box-body table-responsive">                   
                    <h4 style="text-align: center;">Registered Bonds</h4>
                    <table id="example" class="display" style="width:100%">
                     <thead>
                          <tr>
                              <th>#</th>
                             <th>Bond Name</th>
                             <th align="">Bond Value</th>
                             <th>Date Added</th>
                             <th align="left">Action</th>
                          </tr>
                     </thead>
                       <tbody>
                           <?php foreach($bonds as $b){ ?>
                                <tr> 
                                    <td class="sorting_1"></td> 
                                    <td><?=$b->bond_name;?></td> 
                                    <td align=""><?=formatMoney($b->bond_value, true);?></td>
                                    <td><?=$b->created_at;?></td> 
                                    <td align="left">
                                        <form method="post" action="/bond/deletebond?ref=<?=$b->bond_ref;?>">
                                            <input type="hidden" name="delid" value="<?=$b->bond_ref;?>">
                                            <!--<a href="/bond/statement?ref=<?=$b->bond_ref?>"><button type="button" class="btn btn-info btn-xs" data-toggle="tooltip" title="View Record" data-original-title="View Record"><i class="fa fa-eye"></i> <span class="hidden-xs hidden-sm hidden-md"></span></button></a>-->
                                            <!--<a href="/bond/editBond?ref=<?=$b->bond_ref?>"><button type="button" class="btn btn-info btn-xs" data-toggle="tooltip" title="Edit Record" data-original-title="Edit Record"><i class="fa fa-edit"></i> <span class="hidden-xs hidden-sm hidden-md"></span> Edit</button></a>-->
                                            <!--/bond/releasebond?container_nr=<?=$c->container_nr;?>-->
                                            <!--<a href="/bond/releasedbonds?bond_ref=<?=$b->bond_ref;?>">-->
                                            <!--    <button type="button" class="btn btn-success btn-xs" data-toggle="tooltip" data-placement="top" title="Release Bond" data-original-title="Released Bonds"><i class="fa fa-chain-broken"></i> <span class="hidden-xs hidden-sm hidden-md"></span> Released Bonds</button>-->
                                            <!--</a>-->
                                            <!--/documents/attachbond?container_nr=<?=$c->container_nr;?>-->
                                            <!--<a href="/bond/attachedbonds?bond_ref=<?=$b->bond_ref;?>">-->
                                            <!--    <button type="button" class="btn btn-warning btn-xs" data-toggle="tooltip" data-placement="top" title="Attach Bond" data-original-title="Attached Bonds"><i class="fa fa-paperclip"></i> <span class="hidden-xs hidden-sm hidden-md"></span> Attached Bonds</button>-->
                                            <!--</a>-->
                                            
                                            
                                            <a href="/bond/attachbond?bond_ref=<?=$b->bond_ref;?>">
                                                <button type="button" class="btn btn btn-warning btn-xs" data-toggle="tooltip" data-placement="top" title="Attached Bond" data-original-title="Attached Bonds"><i class="fa fa-paperclip"></i> <span class="hidden-xs hidden-sm hidden-md"></span> Attach Bond</button>
                                            </a>
                                            <a href="/bond/releasebond?bond_ref=<?=$b->bond_ref;?>">
                                                <button type="button" class="btn btn btn-primary btn-xs" data-toggle="tooltip" data-placement="top" title="Release Bond" data-original-title="Release Bond"><i class="fa fa-chain-broken"></i> <span class="hidden-xs hidden-sm hidden-md"></span> Release Bond</button>
                                            </a>
                                            <button type="submit" name="del" class="btn btn-danger btn-xs" data-toggle="tooltip" title="" data-original-title="Delete Record"><i class="fa fa-trash"></i> Delete</button>
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
