<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-list"></i> Transporter Statements
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
            
            <div class="col-xs-12">
              <div class="box">
                <div class="box-body table-responsive padding">   
                    <div class="box-header">
                        <h3 class="box-title">Transporter Statements</h3>
                    </div>         
                    <div class="form-group">
                        <div class="col-md-12">
                            <form class="form-bordered" method="post" action="/reports/filtertransporterstatements">
                                <div class="row">
                                    
                                    <div class="col-md-4">
                                        <label>Transporters</label>
                                        <select id="example-select2" name="tid" class="form-control select-select2 select2-hidden-accessible col-md-4" style="width: 100%;" data-placeholder="Choose Consignee" tabindex="-1" aria-hidden="true">
        						            <option></option>
        						            <?php foreach ($transporters as $c){ ?>
        						                <option value="<?=$c->transporter_name;?>"><?=$c->transporter_name;?></option>
        						            <?php } ?>
        							    </select>
        							</div>
                                    
                                    <div class="row">
                                        <div class="col-sm-12">
            							    <span class="input-group-addon">
            							        <button type="submit" name="btn btn-primary" class="btn btn-xs btn-info"><i class="fa fa-check"></i> Filter</button>
            							    </span>
                                        </div>
                                    </div>
                                </div>
    						</form>
                        </div>
                    </div>
                    <hr>
                    
                    <iframe width="100%" height="500px" src="/reports/transporterstatementspdf"></iframe>

                </div>
              </div>
            </div>
        </div>
    </section>
</div>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/common.js" charset="utf-8"></script>
