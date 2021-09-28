<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-print"></i> Quotation  <small></small>
        <small></small>
      </h1>
      <hr>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-xs-12 text-right">
                <div class="form-group">
                    <!--<a class="btn btn-primary" href="<?php echo base_url(); ?>accounts/newInvoice"><i class="fa fa-plus"></i> Create New Quotation</a> -->
                    <!--<a class="btn btn-primary" href="<?php echo base_url(); ?>list_clients_report"><i class="fa fa-glass"></i> List of Clients</a>-->
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                
              <div class="box">
                <div class="box-body table-responsive">                   
                    <p>List of Quotations</p>
                    <table id="example" class="display" style="width:100%">
                     <thead>
                          <tr>
                             <th>No</th> <th>Quotation No.</th> <th>Client</th> <th>Amount</th> <th>Status</th> <th>Quotation Date</th> <th>Action</th>
                          </tr>
                     </thead>
                        <tbody>
                           <?php foreach($invoicerecords as $c){ ?>
                                <tr> 
                                    <td class="sorting_1"></td> 
                                    <td><?=$c->invoice_no;?></td> 
                                    <td><?=$c->client;?> </td>
                                    <td><?=$c->amount;?></td>
                                    <td>
                                        <?php if($c->status == 0){ ?>
                                            OPEN
                                        <?php }else{ ?>
                                            CLOSED
                                        <?php } ?>
                                    </td>
                                    <td><?=$c->created_at;?></td>
                                    <td align="left">
                                        <form method="post" action="/accounts/deleteinvoice?id=<?=$c->id;?>">
                                            <input type="hidden" name="delid" value="<?=$c->id;?>">
                                            <a href="/accounts/edit?id=<?=$c->id?>"><button type="button" class="btn btn-info btn-xs" data-toggle="tooltip" title="View Record" data-original-title="Edit Record"><i class="fa fa-edit"></i> <span class="hidden-xs hidden-sm hidden-md"></span></button></a>
                                            <a href="/accounts/preview?id=<?=$c->id?>"><button type="button" class="btn btn-info btn-xs" data-toggle="tooltip" title="View Record" data-original-title="View Record"><i class="fa fa-eye"></i> <span class="hidden-xs hidden-sm hidden-md"></span></button></a>
                                            <!--<button type="button" class="btn btn-info btn-xs" data-toggle="tooltip" title="Preview" data-original-title="Preview"><i class="fa fa-eye"></i><span class="hidden-xs hidden-sm hidden-md"></span></button>-->
                                            <button type="submit" name="del" class="btn btn-danger btn-xs" data-toggle="tooltip" title="" data-original-title="Delete Record"><i class="fa fa-trash"></i></button>
                                        </form>
                                       
                                    </td>
                                </tr>
                           <?php } ?>
                       </tbody>
                    </table>
                    <hr/>
                    <iframe width="100%" height="500px" src="/accounts/invoicepdfgen/<?=$id;?>"></iframe>
                </div>
              </div>
            </div>
        </div>
    </section>
</div>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/common.js" charset="utf-8"></script>
