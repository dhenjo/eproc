<div class="row">
  <div class="col-xs-12">

    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
            <li class="active"><a href="#list-stock" data-toggle="tab"><b>LIST STOCK</b></a></li>
            <li><a href="#list-request" id="list-request2" data-toggle="tab"><b>LIST REQUEST</b></a></li>
            <!--<li><a href="#pending-mutasi" id="pending-mutasi2" data-toggle="tab"><b>PENDING MUTASI</b></a></li>-->
            <!--<li class="pull-right"><button class="btn" type="submit" id="btn-mutasi">Save changes</button></li>-->
        </ul>
        <div class="tab-content no-padding">
            <!-- Morris chart - Sales -->
            <div class="chart tab-pane active" id="list-stock" style="position: relative; height: 300px;">
                <?php $this->load->view('main-mrp/stock-inventory/stock');?>
            </div>
            <div class="chart tab-pane" id="list-request" style="position: relative; height: 300px;">
                <?php $this->load->view('main-mrp/stock-inventory/list-request'); ?>
            </div>
<!--            <div class="chart tab-pane" id="pending-mutasi" style="position: relative; height: 300px;">
                <?php // $this->load->view('report/pending-mutasi');?>
            </div>-->
        </div>
    </div>
  </div>
</div>