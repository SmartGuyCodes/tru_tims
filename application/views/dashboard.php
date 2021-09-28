<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <i class="fa fa-tachometer" aria-hidden="true"></i> Dashboard
            <small>Control panel</small>
        </h1>
        <div class="row">
            <div col-lg-12>
                <h4 class="content-header">
                    Welcome to Trulance Cargo Management Info System.
                </h4>
            </div>
        </div>
    </section>
    
    <section class="content">
        
        <div class="row">
            <?php if (in_array("clients_list", ($permissions))) { ?>    
            <!--Clients-->
            <div class="col-lg-3 col-xs-6">
                  <!-- small box -->
                  <div class="small-box bg-aqua">
                    <div class="inner">
                      <h3><?=count($clientRecords)?></h3>
                      <p>Clients</p>
                    </div>
                    <div class="icon">
                      <i class="fa fa-users"></i>
                    </div>
                    <a href="/client/listing" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                  </div>
                </div><!-- ./col -->
            <?php } ?>
          
            <?php if (in_array("shippinglines_list", ($permissions))) { ?>
            <!--shippingLines-->
            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-aqua">
                <div class="inner">
                  <h3><?=count($shippingLines)?></h3>
                  <p>Shipping Lines</p>
                </div>
                <div class="icon">
                  <i class="fa fa-ship"></i>
                </div>
                <a href="/shippinglines/listing" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div><!-- ./col -->
            <?php } ?>
            
            <?php if (in_array("transporters_list", ($permissions))) { ?> 
            <!--//transporters-->
            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-aqua">
                <div class="inner">
                  <h3><?=count($transporters)?></h3>
                  <p>Transporters</p>
                </div>
                <div class="icon">
                  <i class="fa fa-car"></i>
                </div>
                <a href="/transporter/listing" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div><!-- ./col -->
            <?php } ?>
            
            <?php if (in_array("documents_manager", ($permissions))) { ?>   
            <!--//documents-->
            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-aqua">
                <div class="inner">
                  <h3><?=count($documents)?></h3>
                  <p>Registered Files</p>
                </div>
                <div class="icon">
                  <i class="fa fa-file-text"></i>
                </div>
                <a href="/documents/listing" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div><!-- ./col -->
            <?php } ?>
            
            <?php if (in_array("quotations_manager", ($permissions))) { ?> 
            <!--//invoices-->
            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-aqua">
                <div class="inner">
                  <h3><?=count($invoices)?></h3>
                  <p>Quotations</p>
                </div>
                <div class="icon">
                  <i class="fa fa-print"></i>
                </div>
                <a href="/accounts/invoicelist" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div><!-- ./col -->
            <?php } ?>
            
            <?php if (in_array("voucher_manager", ($permissions))) { ?> 
            <!--//Vouchers-->
            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-aqua">
                <div class="inner">
                  <h3><?=count($vouchers)?></h3>
                  <p>Vouchers</p>
                </div>
                <div class="icon">
                  <i class="fa fa-rotate-right"></i>
                </div>
                <a href="/accounts/makepayments" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div><!-- ./col -->
            <?php } ?>
            
            <?php if (in_array("receipts_manager", ($permissions))) { ?> 
            <!--//Receipts-->
            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-aqua">
                <div class="inner">
                  <h3><?=count($receipts)?></h3>
                  <p>Receipts</p>
                </div>
                <div class="icon">
                  <i class="fa fa-rotate-left"></i>
                </div>
                <a href="/accounts/receivepayments" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div><!-- ./col -->
            <?php } ?>
            
            <?php if (in_array("transporters_list", ($permissions))) { ?> 
            <!--//Trucks-->
            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-aqua">
                <div class="inner">
                  <h3><?=count($trucks)?></h3>
                  <p>Trucks</p>
                </div>
                <div class="icon">
                  <i class="fa fa-truck"></i>
                </div>
                <a href="/reports/truckslist" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div><!-- ./col -->
            <?php } ?>
            
            <?php if (in_array("settings", ($permissions))) { ?>
            <!--//System users-->
            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-aqua">
                <div class="inner">
                  <h3><?=count($users)?></h3>
                  <p>System users</p>
                </div>
                <div class="icon">
                  <i class="fa fa-user-secret"></i>
                </div>
                <a href="/userListing" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div><!-- ./col -->
            <?php } ?>
            
            <?php if (in_array("settings", ($permissions))) { ?>
            <!--//System Activity Logs
            <div class="col-lg-3 col-xs-6">
              <div class="small-box bg-aqua">
                <div class="inner">
                  <h3><?=$logins;?></h3>
                  <p>Activity Logs</p>
                </div>
                <div class="icon">
                  <i class="fa fa-history"></i>
                </div>
                <a href="/login-history" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div>
            ./col -->
            <?php } ?>
          
          </div>
          
    </section>
</div>