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
        <i class="fa fa-money"></i> Transporter Expenses
        <small>Add, & Delete</small>
      </h1>
      <hr>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-xs-12 text-left">
                <!--/transporter/transportexpenses?id=10-->
                <a class="btn btn-danger" href="/transporter/transportexpenses?id=<?=$transporter_id;?>"<i class="fa fa-arrow-left"></i> Back</a>
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
                                <h3 class="box-title">Transporter Fee Entry Form</h3>
                            </div>
                            <!-- /.box-header -->
                            <!-- form start -->
                            <?php $this->load->helper("form"); ?>
                            <form role="form" id="addUser" action="<?php echo base_url() ?>transporter/addtransporterextrafee" method="post" role="form">
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-md-12">                                
                                            <div class="form-group">
                                                <label for="fname">Transporter</label>
                                                <input class="form-control"  type="text"  value="<?=$transporter_name;?>" name="transporter_name" readonly/>
                                                <input type="hidden"  value="<?=$transporter_id;?>" name="transporter_id" />
                                                <input type="hidden" name="old_balance" value="<?=$balance;?>"/>
                                            </div>
                                            
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">                                
                                            <div class="form-group">
                                                <label for="fname">Container no: </label>
                                                <input name="container_no" class="form-control" type="text" value="<?=$container_no?>" readonly />
                                                <input name="truck_no" type="hidden" value="<?=$truck_no?>" />
                                            </div>                                    
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">                                
                                            <div class="form-group">
                                                <label for="fname">Type of Fee</label>
                                                <select class="form-control" name="fee_type" required>
                        							<option></option>
                        							
                        							<?php foreach($transportation_expense_fee_types as $fee){ ?>
                        							    <option value="<?=$fee->title?>"><?=$fee->title?></option>
                        							<?php } ?>
                        						</select>
                                            </div>                                    
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">                                
                                            <div class="form-group">
                                                <label for="fname">AMOUNT: </label>
                                                <input class="form-control"  type="number"  value="" name="fee_amount" required/>
                                            </div>                                    
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">                                
                                            <div class="form-group">
                                                <label for="fname">NOTE:</label>
                                                <textarea class="form-control" rows="5" id="fee_note" name="fee_note"></textarea>
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
                </div>
            </div>
            
            <div class="col-xs-8">
              <div class="box">
                <div class="box-body table-responsive padding">   
                    <div class="box-header">
                        <h3 class="box-title">List of Fees</h3>
                    </div>                

                    <table id="transporter_tbl" class="display" style="width:100%">
                       <thead>
                          <tr>
                             <th>No</th>
                             <th>TRANSPORTER</th>
                             <th>FEE TYPE</th>
                             <th>AMOUNT</th>
                             <th>NOTE</th>
                             <th>ACTION</th>
                          </tr>
                       </thead>
                       <tbody>
                            <!--
                               [id] => 1
                                [title] => Tansport
                                [type] => Transport
                            -->
                            <?php foreach($transporterextrafee as $c){ ?>
                                <tr> 
                                    <td class="sorting_1"></td> 
                                    <td><?=$c->transporter_name;?></td> 
                                    <td><?=$c->fee_type;?> </td>
                                    <td><?=formatMoney($c->fee_amount, 1);?> </td>
                                    <td><?=$c->fee_note;?> </td>
                                    <td align="center">
                                        <form method="post" action="/transporter/deleteextrafee?id=<?=$c->id?>">
                                            <input type="hidden" name="delid" value="<?=$c->id?>">
                                            <!--<a href="#"><button type="button" class="btn btn-info btn-xs" data-toggle="tooltip" title="" data-original-title="Edit Record"><i class="fa fa-edit"></i> <span class="hidden-xs hidden-sm hidden-md"></span></button></a>-->
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
