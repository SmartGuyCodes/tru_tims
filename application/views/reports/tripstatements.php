<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-circle-o"></i> Trip Statements
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
                        <h3 class="box-title">Trip Statements</h3>
                    </div>    
                           
                    <div class="form-group">
                        <div class="col-md-12">
                            <form class="form-bordered" method="post" action="/reports/filtertripstatements">
                                <div class="row">
                                    <div class="input-daterange col-md-6" data-date-format="yyyy-mm-dd">
            							
            							<div class="form-group">
                                            <div class="col-xs-12 date">
                                                <div class="input-group input-append date" id="datePicker">
                                                    <label>From</label>
                                                    <input type="text" class="form-control" name="start_date" id="start_date"/>
                                                    <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
            						
            						<div class="input-daterange col-md-6" data-date-format="yyyy-mm-dd">	
            							<div class="form-group">
                                            <div class="col-xs-12 date">
                                                <div class="input-group input-append date" id="datePicker">
                                                    <label>To</label>
                                                    <input type="text" class="form-control" name="end_date" id="end_date"/>
                                                    <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                                                </div>
                                            </div>
                                        </div>
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
                    <iframe width="100%" height="500px" src="/reports/tripstatementspdf"></iframe>    

                </div>
              </div>
            </div>
        </div>
    </section>
</div>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/common.js" charset="utf-8"></script>
