<!DOCTYPE html>
<html>
  <head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo $pageTitle; ?></title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <!-- Bootstrap 3.3.4 -->
    <link href="<?php echo base_url(); ?>assets/bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <!--Bootstrap 4.4.1-->
    <!--<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">-->
    <!-- FontAwesome 4.3.0 -->
    <link href="<?php echo base_url(); ?>assets/bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <!-- Ionicons 2.0.0 -->
    <link href="<?php echo base_url(); ?>assets/bower_components/Ionicons/css/ionicons.min.css" rel="stylesheet" type="text/css" />
    <!-- Theme style -->
    <link href="<?php echo base_url(); ?>assets/dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />
    <!-- AdminLTE Skins. Choose a skin from the css/skins folder instead of downloading all of them to reduce the load. -->
    <link href="<?php echo base_url(); ?>assets/dist/css/skins/_all-skins.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <style>
    	.error{
    		color:red;
    		font-weight: normal;
    	}
    </style>
    <script src="<?php echo base_url(); ?>assets/bower_components/jquery/dist/jquery.min.js"></script>
    <script type="text/javascript">
        var baseURL = "<?php echo base_url(); ?>";
    </script>
    
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    <!--<link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css"/>-->
    <link href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/buttons/1.6.1/css/buttons.dataTables.min.css" rel="stylesheet" />

    <style>
        table {
            counter-reset: 1;
        }
        table tr {
            counter-increment: rowNumber;
        }
        table tr td:first-child::before {
            content: counter(rowNumber);
            min-width: 1em;
            margin-right: 0.5em;
        }
        
        /*tbody tr:nth-child(odd) {*/
        /*  background-color: #ff33cc;*/
        /*}*/
        
        /*tbody tr:nth-child(even) {*/
        /*  background-color: #e495e4;*/
        /*}*/
        
        /*tbody tr {*/
        /*  background-image: url(noise.png);*/
        /*}*/
        
        /*table {*/
        /*  background-color: #ff33cc;*/
        /*}*/
    </style>
  </head>
  <body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">
      
      <header class="main-header">
        <!-- Logo -->
        <a href="<?php echo base_url(); ?>" class="logo">
          <!-- mini logo for sidebar mini 50x50 pixels -->
          <span class="logo-mini"><b>CO</b>MS</span>
          <!-- logo for regular state and mobile devices -->
          <span class="logo-lg"><b>Trulance</b>TruTMIS</span>
        </a>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top" role="navigation">
          <!-- Sidebar toggle button-->
          <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
          </a>
          <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <li class="user user-menu">
                    <a href="#">Welcome to Trulance Cargo Management Info System.</a>
                </li>    
              <li class="dropdown tasks-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
                  <i class="fa fa-history"></i>
                </a>
                <ul class="dropdown-menu">
                  <li class="header"> Last Login : <i class="fa fa-clock-o"></i> <?= empty($last_login) ? "First Time Login" : $last_login; ?></li>
                </ul>
              </li>
              <!-- User Account: style can be found in dropdown.less -->
              <li class="dropdown user user-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <img src="<?php echo base_url(); ?>assets/dist/img/avatar.png" class="user-image" alt="User Image"/>
                  <span class="hidden-xs"><?php echo $name; ?></span>
                </a>
                <ul class="dropdown-menu">
                  <!-- User image -->
                  <li class="user-header">
                    
                    <img src="<?php echo base_url(); ?>assets/dist/img/avatar.png" class="img-circle" alt="User Image" />
                    <p>
                      <?php echo $name; ?>
                      <small><?php echo $role_text; ?></small>
                    </p>
                    
                  </li>
                  <!-- Menu Footer-->
                  <li class="user-footer">
                    <div class="pull-left">
                      <a href="<?php echo base_url(); ?>profile" class="btn btn-warning btn-flat"><i class="fa fa-user-circle"></i> Profile</a>
                    </div>
                    <div class="pull-right">
                      <a href="<?php echo base_url(); ?>logout" class="btn btn-default btn-flat"><i class="fa fa-sign-out"></i> Sign out</a>
                    </div>
                  </li>
                </ul>
              </li>
            </ul>
          </div>
        </nav>
      </header>
      <!-- Left side column. contains the logo and sidebar -->
      <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
          <!-- sidebar menu: : style can be found in sidebar.less -->
          <ul class="sidebar-menu" data-widget="tree">
            <li class="header">MAIN NAVIGATION</li>
            
            <!--Dashboard-->
            <li>
              <a href="<?php echo base_url(); ?>dashboard">
                <i class="fa fa-dashboard"></i> <span>Dashboard</span></i>
              </a>
            </li>
            
            <?php if(in_array("shippinglines_list", unserialize($permissions)) || in_array("clients_list", unserialize($permissions))) {?>
            <!--Profiles-->
            <li class="treeview">
              <a href="#">
                <i class="fa fa-circle-o"></i> <span>Profiles</span>
                <span class="pull-right-container">
                  <i class="fa angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                 <?php if (in_array("shippinglines_list", unserialize($permissions))) { ?>
                    <li><a href="<?php echo base_url(); ?>shippinglines/listing"><i class="fa fa-ship"></i> Shipping Lines</a></li>
                 <?php } ?>
                 <?php if (in_array("clients_list", unserialize($permissions))) { ?>    
                    <li><a href="<?php echo base_url(); ?>client/listing"><i class="fa fa-users"></i> Clients</a></li>
                 <?php } ?>
              </ul>
            </li>
            <?php } ?>
            
            <!--Documents manager-->
            <?php if (in_array("documents_manager", unserialize($permissions))) { ?>    
                <li>
                  <a href="<?php echo base_url(); ?>documents/listing" >
                    <i class="fa fa-file-text"></i>
                    <span>Documents Manager</span>
                  </a>
                </li>
            <?php } ?>
            
            <!--li>
              <a href="/bond/listing" >
                <i class="fa fa-certificate"></i>
                <span>Bonds Manager</span>
              </a>
            </li-->
            
            
            <?php if(in_array("transporters_list", unserialize($permissions)) || in_array("interchange", unserialize($permissions))) {?>
                <!--Transport manager-->
                <li class="treeview">
                  <a href="#">
                    <i class="fa fa-truck"></i> <span>Transport Manager</span>
                    <span class="pull-right-container">
                      <i class="fa angle-left pull-right"></i>
                    </span>
                  </a>
                  <ul class="treeview-menu">
                    <?php if (in_array("transporters_list", unserialize($permissions))) { ?> 
                        <li><a href="/transporter/listing"><i class="fa fa-truck"></i> Transporters</a></li>
                    <?php } ?>
                    <?php if (in_array("interchange", unserialize($permissions))) { ?> 
                        <li><a href="/transporter/interchange"><i class="fa fa-exchange"></i> Interchange</a></li>
                    <?php } ?>
                  </ul>
                </li>
            <?php } ?>
            
            <!--li class="treeview">
              <a href="#">
                <i class="fa fa-circle-o"></i> <span>Clearing Manager</span>
                <span class="pull-right-container">
                  <i class="fa angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <li><a href="/clearingforwarding/launchclearing"><i class="fa fa-ship"></i> Launch Clearing</a></li>
                <li><a href="/clearingforwarding/releasecargo"><i class="fa fa-ship"></i> Release Cargo</a></li>
              </ul>
            </li>
            <li class="treeview">
              <a href="#">
                <i class="fa fa-car"></i> <span>Transport Manager</span>
                <span class="pull-right-container">
                  <i class="fa angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <li><a href="/transporter/launchtransport"><i class="fa fa-car"></i> Launch Transport</a></li>
                <li><a href="/transporter/transporterjobs"><i class="fa fa-car"></i> Transporter Jobs</a></li>
                <li><a href="/transporter/interchange"><i class="fa fa-car"></i> Interchange</a></li>
                <li><a href="/transporter/destinations"><i class="fa fa-car"></i> Destinations</a></li>
              </ul>
            </li-->
            
            <?php if (in_array("fees_manager", unserialize($permissions)) || in_array("accounts_manager", unserialize($permissions)) || 
            in_array("quotations_manager", unserialize($permissions)) || in_array("voucher_manager", unserialize($permissions)) || in_array("receipts_manager", unserialize($permissions))) { ?> 
                <!--Accounts Manager-->
                <li class="treeview">
                  <a href="#">
                    <i class="fa fa-address-card"></i> <span>Accounts Manager</span>
                    <span class="pull-right-container">
                      <i class="fa angle-left pull-right"></i>
                    </span>
                  </a>
                  <ul class="treeview-menu">
                    <?php if (in_array("fees_manager", unserialize($permissions))) { ?> 
                        <li><a href="/accounts/fees"><i class="fa fa-money"></i> Fees Manager</a></li>
                    <?php } ?>
                    <?php if (in_array("accounts_manager", unserialize($permissions))) { ?> 
                        <li><a href="/accounts/accountlist"><i class="fa fa-address-book-o"></i> Accounts</a></li> 
                    <?php } ?>
                    <?php if (in_array("quotations_manager", unserialize($permissions))) { ?> 
                        <li><a href="/accounts/invoicelist"><i class="fa fa-print"></i> Quotations</a></li>
                    <?php } ?>
                    <?php if (in_array("voucher_manager", unserialize($permissions))) { ?> 
                        <li><a href="/accounts/makepayments"><i class="fa fa-rotate-right"></i> Make Payments</a></li>
                    <?php } ?>
                    <?php if (in_array("receipts_manager", unserialize($permissions))) { ?> 
                        <li><a href="/accounts/receivepayments"><i class="fa fa-rotate-left"></i> Receive Money</a></li>
                    <?php } ?>
                  </ul>
                </li>
            <?php } ?>
            
            
            <?php if (in_array("reports", unserialize($permissions))) { ?> 
                <!--Reports-->
                <li class="treeview">
                  <a href="#">
                    <i class="fa fa-line-chart"></i> <span>Reports</span>
                    <span class="pull-right-container">
                      <i class="fa angle-left pull-right"></i>
                    </span>
                  </a>
                  <ul class="treeview-menu">
                    <li><a href="/reports/dailyreports" style="color: #ff0000"><i class="fa fa-cog fa-spin"></i> Daily Reports</a></li>
                    <li><a href="/reports/innterchangereport"><i class="fa fa-circle-o"></i> Interchange Report</a></li>
                    <li><a href="/reports/customerjobs"><i class="fa fa-circle-o"></i> Customer Jobs</a></li>
                    <li class="treeview">
                        <a href="">
                            <i class="fa fa-circle-o"></i> <span>List of</span>
                            <span class="pull-right-container">
                                <i class="fa angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="/reports/clientslist"><i class="fa fa-circle-o"></i> Clients </a></li>
                            <li><a href="/reports/transporterslist"><i class="fa fa-circle-o"></i> Transporters</a></li>
                            <li><a href="/reports/truckslist"><i class="fa fa-circle-o"></i> Trucks</a></li>
                            <li><a href="/reports/shippinglineslist"><i class="fa fa-circle-o"></i> Shipping Lines</a></li>
                      </ul>
                        
                    </li>
                    <li><a href="/reports/filesandcontainers"><i class="fa fa-circle-o"></i> Files &amp; Containers</a></li>
                    <!--<li><a href="/reports/innterchangereport"><i class="fa fa-circle-o"></i> Clearing &amp; Forwarding</a></li>-->
                    <li><a href="/reports/tripstatements"><i class="fa fa-circle-o"></i> Trip Statements</a></li>
                    <li><a href="/reports/receiptstatements"><i class="fa fa-circle-o"></i> Receipt Statements</a></li>
                    <li><a href="/reports/voucherstatements"><i class="fa fa-circle-o"></i> Voucher Statements</a></li>
                    <!--<li><a href="/reports/releasedcargo"><i class="fa fa-circle-o"></i> Released Cargo</a></li>-->
                    <!--<li><a href="/reports/cargotransfer"><i class="fa fa-circle-o"></i> Cargo Transfer</a></li>-->
                    <li><a href="/reports/clientstatements"><i class="fa fa-circle-o"></i> Client Statements</a></li>
                    <li><a href="/reports/transporterstatements"><i class="fa fa-circle-o"></i> Transporter Statements</a></li>
                    <!--<li><a href="<?php echo base_url(); ?>reports/bondreports"><i class="fa fa-certificate fa-spin"></i> Bond Reports</a></li>-->
                  </ul>
                </li>
            <?php } ?>
            
            <?php if (in_array("settings", unserialize($permissions))) { ?> 
                <!--Settings-->
                <li class="treeview">
              <a href="#">
                <i class="fa fa-cogs"></i> <span>Settings</span>
                <span class="pull-right-container">
                  <i class="fa angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu"> 
                <!--<li><a href="<?php echo base_url(); ?>login-history"><i class="fa fa-history"></i> Activity Logs</a></li>-->
                <li><a href="<?php echo base_url(); ?>userListing"><i class="fa fa-user-secret"></i> Users</a></li>
                <!--<li><a href="<?php echo base_url(); ?>user/manageroles"><i class="fa fa-code-fork"></i> Manage Roles</a></li>-->
                <!--<li><a href="<?php echo base_url(); ?>user/managepermissions"><i class="fa fa-lock"></i> Manage Permissions</a></li>-->
                <!--li><a href="#"><i class="fa fa-cog fa-spin"></i> General Settings</a></li>
                <li><a href="#"><i class="fa fa-certificate fa-spin"></i> Company Settings</a></li-->
              </ul>
            </li>
            <?php } ?>
            ?>
          </ul>
        </section>
        <!-- /.sidebar -->
      </aside>