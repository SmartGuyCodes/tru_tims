<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-ship"></i> Containers Management 
        <small>Add, Edit, Update &amp; Delete </small>
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
                      <!-- general form elements -->
                      <div class="box box-primary">
                            <div class="box-header">
                                <h3 class="box-title">Cargo Loading Form</h3>
                            </div>
                            <!-- /.box-header -->
                            <!-- form start -->
                            <?php $this->load->helper("form"); ?>
                            <form role="form" id="addUser" action="<?php echo base_url() ?>documents/addContainer" method="post" role="form">
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-md-12">                                
                                            <div class="form-group">
                                                <label for="fname">File No:</label>
                                                <input type="text" class="form-control required" value="<?php echo $documentInfo[0]->file_no; ?>" id="file_no" name="file_no" readonly>
                                                <input type="hidden" value="<?=$documentInfo[0]->id?>" name="id"/>
                                            </div>
                                            
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">                                
                                            <div class="form-group">
                                                <label for="fname">Container/(unit)Chasis No:</label>
                                                <input type="text" class="form-control required" value="<?php echo set_value('driver_name'); ?>" id="container_chasis_no" name="container_chasis_no">
                                            </div>                                    
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">                                
                                            <div class="form-group">
                                                <label for="fname">Description:</label>
                                                <textarea class="form-control required"  value="<?php echo set_value('driver_email'); ?>" id="description" name="description">
                                                    
                                                </textarea>
                                            </div>                                    
                                        </div>
                                    </div>
                                </div>
                                <!-- /.box-body -->
                                <div class="box-footer">
                                    <input type="submit" class="btn btn-primary" value="Submit" />
                                    <input type="reset" class="btn btn-default" value="Reset" />
                                </div>
                            </form>
                        </div>
                    </div>
                    
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
                </div>
            </div>
            
            <div class="col-xs-8">
              <div class="box">
                <div class="box-body table-responsive padding">   
                    <div class="box-header">
                        <h3 class="box-title">List of Cargo Loaded on Container</h3>
                    </div>                

                    <table id="transporter_tbl" class="display" style="width:100%">
                       <thead>
                          <tr>
                             <th>No</th>
                             <th>Container/Chasis No.</th>
                             <th>Description</th>
                             <th>Action</th>
                          </tr>
                       </thead>
                       <tbody>
                           <?php foreach($containers as $c){ ?>
                                <tr> 
                                    <td class="sorting_1"></td> 
                                    <td><?=$c->container_chasis_no;?></td> 
                                    <td><?=$c->description;?> </td>
                                    <td align="center">
                                        <form method="post">
                                            <input type="hidden" name="delid" value="<?=$c->id;?>">
                                            <a href="#"><button type="button" class="btn btn-info btn-xs" data-toggle="tooltip" title="" data-original-title="Edit Record"><i class="fa fa-edit"></i> <span class="hidden-xs hidden-sm hidden-md"></span></button></a>
                                            <button type="submit" name="del" class="btn btn-danger btn-xs" data-toggle="tooltip" title="" data-original-title="Delete Record"><i class="fa fa-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                           <?php } ?>
                       </tbody>
                       <tfoot>
                          <tr>
                           
                          </tr>
                       </tfoot>
                    </table>

                </div>
              </div>
            </div>
        </div>
    </section>
</div>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/common.js" charset="utf-8"></script>
