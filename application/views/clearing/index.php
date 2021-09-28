<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-file"></i> Clearing Documents 
        <small></small>
      </h1>
    </section>
    <section class="content">
        <div class="row">
            <!--div class="col-xs-12 text-right">
                <div class="form-group">
                    <a class="btn btn-primary" href="<?php echo base_url(); ?>documents/add"><i class="fa fa-file"></i> Receive Document</a>
                      <a class="btn btn-primary" href="<?php echo base_url(); ?>daily_report"><i class="fa fa-glass"></i> Daily Report</a>
                </div>
            </div-->
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
                             <th>File No.</th>
                             <th>Client</th>
                             <th>Consignee</th>
                             <th>Shipping Line</th>
                             <th>Date</th>
                             <th>Status</th>
                             <th>Action</th>
                          </tr>
                       </thead>
                       <tbody>
                           <?php foreach($docs as $c){ ?>
                                <tr> 
                                    <td class="sorting_1"></td> 
                                    <td><?=$c->file_no;?></td> 
                                    <td><?=$c->client_id;?> </td>
                                    <td><?=$c->consignee_id;?></td>
                                    <td><?=$c->shipping_line;?></td>
                                    <td><?=$c->date_received;?></td>
                                    <td>
                                        <?php if($c->do_status == "READY") {?>
                                            <button class="btn btn-success btn-xs btn-block">Cleared</button>
                                        <?php }else {?>
                                            <button class="btn btn-danger btn-xs btn-block">Not Cleared</button>
                                        <?php }?>
                                    </td>
                                    <td align="center">
                                      <form method="post">
                                            <a href="<?php echo base_url(); ?>clearingforwarding/loadfile?id=<?=$c->file_no;?>">
                                                <button type="button" class="btn btn-info btn-xs" data-toggle="tooltip" title="" data-original-title="Load File">
                                                  <i class="fa fa-folder-open"></i>
                                                  <span class="hidden-xs hidden-sm hidden-md"></span>
                                                  Launch
                                                </button>
                                            </a>  
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
