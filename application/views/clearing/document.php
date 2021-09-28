<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-file"></i> Clearing & Forwarding
        <small></small>
      </h1>
      <br />
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
                <div class="block full">
                <!-- Example Form Title -->
                <div class="block-title">
                    <h4 style="text-alignt: center;">File Info.</h4>
                </div>
                <!-- END Example Form Title -->

                <!-- Example Form Content -->
                    <div class="form-group col-md-2">
						<label class="control-label">File Number:</label>
						<input type="text" readonly="" class="form-control" name="fileno" value="<?=$file_no?>">
					</div>
					<div class="form-group col-md-2">
						<label class="control-label">Date of Entry:</label>
						<input type="text" class="form-control input-datepicker" id="example-datepicker3" data-date-format="dd-mm-yyyy" name="datereceived" value="2020-01-30">
					</div>
					<div class="form-group col-md-4">
						<label class="control-label">Client:</label>
						<input type="text" class="form-control" name="client" value="NAASH AFRICA LOGISTICS LTD">
						<input type="hidden" class="form-control" name="clientid" value="CC05318">
					</div>
					<div class="form-group col-md-4">
						<label class="control-label">Consignee:</label>
						<input type="text" class="form-control" name="consignee" value="hasmad">
					</div>
					<div class="form-group col-md-4">
						<label class="control-label">Consignment:</label>
						<input type="text" class="form-control" name="consignment" value="232323">
					</div>
					<div class="form-group col-md-2">
						<label class="control-label">Containers:</label>
						<input style="text-align: center" readonly="" type="text" class="form-control" name="noofcontainers" value="0">
					</div>
					<div class="form-group col-md-3">
						<label class="control-label">Container Size:</label>
						<input type="text" class="form-control" name="containersize" value="20 FT">
					</div>
					
					<div class="form-group col-md-3">
						<label class="control-label">Bill of Landing:</label>
						<input type="text" class="form-control" name="billoflanding" value="sdsdsdss">
					</div>
					
					<div class="form-group col-md-4">
						<label class="control-label">Shipping Line:</label>
						<input type="text" class="form-control" name="shippingline" value="maask">
					</div>
					<div class="form-group col-md-2">
						<label class="control-label">Date of Loading:</label>
						<input type="text" class="form-control input-datepicker" id="example-datepicker3" data-date-format="yyyy-mm-dd" name="dateofloading" value="">
					</div>
					<div class="form-group col-md-3">
						<label class="control-label">IDF No:</label>
						<input type="text" class="form-control" name="idfno" value="1233231">
					</div>
					<div class="form-group col-md-3">
						<label class="control-label">Collection Status:</label>
						<select class="form-control" name="collectionstatus">
							<option>Collected</option>
							<option>Collected</option>
							<option>Not Collected</option>
						</select>
					</div>
					<div class="form-group col-md-3">
						<label class="control-label">Date of Lodging:</label>
						<input type="text" class="form-control input-datepicker" id="example-datepicker3" data-date-format="yyyy-mm-dd" name="dateoflodging" value="">
					</div>
					<div class="form-group col-md-3">
						<label class="control-label">Date of Collection:</label>
						<input type="text" class="form-control input-datepicker" id="example-datepicker3" data-date-format="yyyy-mm-dd" name="dateofcollection" value="">
					</div>
					
					
					<div class="form-group col-md-6">
						<label class="control-label">Amount Agreed:</label>
						<input type="number" class="form-control" id="am" oninput="myCalc()" name="amount" value="">
					</div>
					
					     
                    <div class="form-group form-actions" align="center">
                        <div class="form-group form-actions">
                            <div class="col-xs-12" align="center">
                                <button type="submit" name="btncnf" class="btn btn-sm btn-info"><i class="fa fa-check"></i> Save Record</button>
                            </div>
                        </div>
                    </div>
                <!-- END Example Form Content -->
            </div>
            </div>
        </div>
    </section>
</div>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/common.js" charset="utf-8"></script>
