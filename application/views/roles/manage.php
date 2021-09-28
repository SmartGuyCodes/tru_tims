<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-fork"></i> Roles <small>manage user roles</small>
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
                    <a class="btn btn-primary" href="<?php echo base_url(); ?>shippinglines/add"><i class="fa fa-plus"></i> Add New</a>
                       <a class="btn btn-primary" href="<?php echo base_url(); ?>reports/shippinglineslist"><i class="fa fa-ship"></i> List of Shipping Lines</a>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
              <div class="box">
                <div class="box-body table-responsive">                   

                    <table id="example" class="display" style="width:100%">
                       <thead>
                          <tr>
                             <th>No.</th>
                             <th>Shipper Name</th>
                             <th>Contact person</th>
                             <th>Telephone no.</th>
                             <th>Action</th>
                          </tr>
                       </thead>
                       <tbody>
                           <?php foreach($shippingLines as $c){ ?>
                                <tr> 
                                    <td class="sorting_1"></td> 
                                    <td><?=$c->shipper_name;?></td> 
                                    <td><?=$c->contact_person;?> </td>
                                    <td><?=$c->telephone_no;?></td>
                                    <td align="center">
                                        <form method="post" action="/shippinglines/delete?id=<?=$c->id;?>">
                                            <a href="/shippinglines/edit?id=<?=$c->id;?>"><button type="button" class="btn btn-info btn-xs" data-toggle="tooltip" title="Edit Record" data-original-title="Edit Record"><i class="fa fa-edit"></i> <span class="hidden-xs hidden-sm hidden-md"></span></button></a>
                                            <button type="submit" name="del" class="btn btn-danger btn-xs" data-toggle="tooltip" title="Delete Record" data-original-title="Delete Record"><i class="fa fa-trash"></i></button>
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
