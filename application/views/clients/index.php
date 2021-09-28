<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-users"></i> Clients Management  
        <small>Add, Edit, Update &amp; Delete</small>
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
                    <a class="btn btn-primary" href="<?php echo base_url(); ?>client/add"><i class="fa fa-plus"></i> Register New Client</a> 
                    <a class="btn btn-info" href="<?php echo base_url(); ?>reports/clientslist"><i class="fa fa-glass"></i> List of Clients</a>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
              <div class="box">
                <div class="box-body table-responsive">                   
                    <p>List of Registered Clients</p>
                    <table id="example" class="display" style="width:100%">
                     <thead>
                          <tr>
                             <th>No</th>
                             <th>Name</th>
                             <th>Contact person</th>
                             <th>Email</th>
                             <th>Telephone</th>
                             <th>Action</th>
                          </tr>
                     </thead>
                       <tbody>
                           <?php foreach($clientRecords as $c){ ?>
                                <tr> 
                                    <td class="sorting_1"></td> 
                                    <td><?=$c->client_name;?></td> 
                                    <td><?=$c->contact_person;?> </td>
                                    <td><?=$c->email;?></td>
                                    <td><?=$c->tel_no;?></td>
                                    <td align="left">
                                        <form method="post" action="/client/deleteclient?id=<?=$c->id;?>">
                                            <input type="hidden" name="delid" value="<?=$c->id;?>">
                                            <a href="/client/edit?id=<?=$c->id?>"><button type="button" class="btn btn-info btn-xs" data-toggle="tooltip" title="Edit Record" data-original-title="Edit Record"><i class="fa fa-edit"></i> <span class="hidden-xs hidden-sm hidden-md"></span></button></a>
                                            <a href="/client/statement?id=<?=$c->id;?>">
                                                <button type="button" class="btn btn-primary btn-xs" data-toggle="tooltip" title="Statement" data-original-title="Stetement">
                                                    <i class="fa fa-book"></i> <span class="hidden-xs hidden-sm hidden-md"></span>
                                                    Statement
                                                </button>
                                            </a>
                                            <a href="/client/addCharges?id=<?=$c->id;?>&name=<?=$c->client_name;?>">
                                                <button type="button" class="btn btn-success btn-xs" data-toggle="tooltip" title="Add Charges" data-original-title="Add Charges">
                                                    <i class="fa fa-money"></i> <span class="hidden-xs hidden-sm hidden-md"></span>
                                                    Add Charges
                                                </button>
                                            </a>
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
