<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-car"></i> Transporter Trucks  <small>Add, Edit, View &amp; Delete</small>
        <small></small>
      </h1>
      <hr>
    </section>
    <section class="content">
        <!--div class="row">
            <div class="col-xs-12 text-right">
                <div class="form-group">
                    <a class="btn btn-primary" href="<?php echo base_url(); ?>transporter/add"><i class="fa fa-plus"></i> Add New</a>
                </div>
            </div>
        </div-->
        
        <div class="row">
            <div class="col-xs-4">
                <div class="row">
                    <!-- left column -->
                    <div class="col-md-12">
                      <!-- general form elements -->
                      <div class="box box-primary">
                            <div class="box-header">
                                <h3 class="box-title">Add Truck to Transporter</h3>
                            </div>
                            <!-- /.box-header -->
                            <!-- form start -->
                            <?php $this->load->helper("form"); ?>
                            <form role="form" id="addUser" action="<?php echo base_url() ?>transporter/addNewTruck" method="post" role="form">
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-md-12">                                
                                            <div class="form-group">
                                                <label for="fname">Transporter name</label>
                                                <input type="text" class="form-control required" value="<?=$transporter_name;?>" id="transporter_name" name="transporter_name">
                                                <input type="hidden" value="<?=$transporter;?>" name="transporter_id"/>
                                            </div>
                                            
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">                                
                                            <div class="form-group">
                                                <label for="fname">Truck no.</label>
                                                <input type="text" class="form-control required" value="<?php echo set_value('truck_no'); ?>" id="truck_no" name="truck_no">
                                                <input type="hidden" value="<?=$transporter;?>" name="transporter_id"/>
                                            </div>
                                            
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">                                
                                            <div class="form-group">
                                                <label for="fname">Driver name</label>
                                                <input type="text" class="form-control required" value="<?php echo set_value('driver_name'); ?>" id="driver_name" name="driver_name">
                                            </div>                                    
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">                                
                                            <div class="form-group">
                                                <label for="fname">Driver mobile no.</label>
                                                <input type="text" class="form-control required" value="<?php echo set_value('driver_mobile_no'); ?>" id="driver_mobile_no" name="driver_mobile_no">
                                            </div>                                    
                                        </div>
                                    </div>
                                   <p></p>
                                </div>
                                <!-- /.box-body -->
                                <div class="box-footer">
                                    <input type="submit" class="btn btn-primary" value="Submit" />
                                    <a class="btn btn-danger" href="/transporter/listing">Back</a>
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
                        <h3 class="box-title">List of Trucks</h3>
                    </div>                

                    <table id="transporter_tbl" class="display" style="width:100%">
                       <thead>
                          <tr>
                             <th>No</th>
                             <th>Truck No.</th>
                             <th>Driver Name</th>
                             <th>Telephone</th>
                             <th>Action</th>
                          </tr>
                       </thead>
                       <tbody>
                            <?php foreach($trucks as $c){ ?>
                                <tr> 
                                    <td class="sorting_1"></td> 
                                    <td><?=$c->truck_no;?></td> 
                                    <td><?=$c->driver_name;?> </td>
                                    <td><?=$c->driver_mobile_no;?></td>
                                    <td align="center">
                                        <form method="post" action="/transporter/deleteTruck?truck_id=<?=$c->id;?>&transporter_id=<?=$transporter?>">
                                            <input type="hidden" name="delid" value="/transporter/deleteTruck?id=<?=$c->id;?>">
                                            <a href="/transporter/editTruck?truck_id=<?=$c->id;?>&transporter_id=<?=$transporter?>"><button type="button" class="btn btn-info btn-xs" data-toggle="tooltip" title="" data-original-title="Edit Record"><i class="fa fa-edit"></i> <span class="hidden-xs hidden-sm hidden-md"></span></button></a>
                                            <button type="submit" name="del" class="btn btn-danger btn-xs" data-toggle="tooltip" title="" data-original-title="Delete Record"><i class="fa fa-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                           <?php } ?>
                       </tbody>
                       <tfoot>
                          <tr>
                             <th>No</th>
                             <th>Truck No.</th>
                             <th>Driver Name</th>
                             <th>Telephone</th>
                             <th>Action</th>
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
