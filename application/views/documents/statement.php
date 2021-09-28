<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-file"></i> Clearing Documents Management
        <small>Add, Edit, Update &amp; Delete</small>
      </h1>
    </section>
    <section class="content">
        
        <div class="row">
            
            <!--left column-->
            <div class="col-xs-4">
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
            
            <div class="col-xs-8 text-right">
                <div class="form-group">
                    <a class="btn btn-primary" href="<?php echo base_url(); ?>documents/add"><i class="fa fa-file"></i> Receive Document</a>
                      <a class="btn btn-warning" href="<?php echo base_url(); ?>reports/dailyreports"><i class="fa fa-cog fa-spin"></i> View Daily Report</a>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-xs-12">
              <div class="box">
                <div class="box-body table-responsive">                   
                    <!--# 	File No. 	Client 	Consignee 	Shipping Line 	Date Received 	Action-->
                    <table id="transporter_tbl" class="display" style="width:100%">
                       <thead>
                          <tr>
                             <th>No</th>
                             <th>Container No.</th>
                             <th>Client</th>
                             <th>Consignee</th>
                          
                             <th>Date Received</th>
                             <th>Action</th>
                          </tr>
                       </thead>
                       <tbody>
                           <?php foreach($docs as $c){ ?>
                                <tr> 
                                    <td class="sorting_1"></td> 
                                    <td><?=$c->container_nr;?></td> 
                                    <td><?=$c->client_id;?> </td>
                                    <td><?=$c->consignee_id;?></td>
                                    
                                    <td><?=$c->date_received;?></td>
                                    <td align="left">
                                        <form method="post"  action="/documents/deletedoc?id=<?=$c->id?>">
                                            <input type="hidden" name="delid" value="<?=$c->id;?>">
                                            <p>
                                                <!--<a href="/documents/containers?id=<?=$c->id;?>"><button type="button" class="btn btn-success btn-xs" data-toggle="tooltip" data-placement="top" title="Containers/Motor Vehicle" data-original-title="Containers/Motor Vehicle"><i class="fa fa-ship"></i><span class="hidden-xs hidden-sm hidden-md"></span></button></a>-->
                                                <a href="/documents/clearing?id=<?=$c->id;?>"><button type="button" class="btn btn-primary btn-xs" data-toggle="tooltip" data-placement="top" title="update daily report" data-original-title="update daily report"><i class="fa fa-sign-out"></i><span class="hidden-xs hidden-sm hidden-md"></span></button></a>
                                                <a href="/documents/clearing?id=<?=$c->id;?>">
                                                    <button type="button" class="btn btn-info btn-xs" data-toggle="tooltip" data-placement="top" title="Edit Record" data-original-title="Edit Record"><i class="fa fa-edit"></i> <span class="hidden-xs hidden-sm hidden-md"></span> Edit</button>
                                                </a>
                                                <button type="submit" name="del" class="btn btn-danger btn-xs" data-toggle="tooltip" data-placement="top" title="Delete Record" data-original-title="Delete Record"><i class="fa fa-trash"></i> Delete</button>
                                           
                                                <a href="/documents/statement?file_no=<?=$c->file_no;?>">
                                                    <button type="button" class="btn btn-primary btn-xs" data-toggle="tooltip" title="Statement" data-original-title="Statement">
                                                        <i class="fa fa-book"></i> <span class="hidden-xs hidden-sm hidden-md"></span>
                                                        Statement
                                                    </button>
                                                </a>
                                            
                                               <a class="btn btn-success btn-xs" href="/documents/receiveadvance?id=<?=$c->id;?>"><i class="fa fa-money"></i> Receive Deposit</a>
                                            </p>
                                        </form>
                                    </td>
                                   
                                </tr>
                           <?php } ?>
                       </tbody>
                      
                    </table>

                </div>
              </div>
            </div>
            <hr>
            
            <div class="col-xs-12">
                <div class="box">             
                    <iframe width="100%" height="500px" src="/documents/statementpdf?file_no=<?=$file_no;?>&container_no=<?=$container_nr?>"></iframe>
                </div>
            </div>
        </div>
    </section>
</div>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/common.js" charset="utf-8"></script>
