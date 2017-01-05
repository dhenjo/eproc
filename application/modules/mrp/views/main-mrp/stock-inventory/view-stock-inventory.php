   <div class="row">
  <div class="col-xs-12">

    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
            <li class="active"><a href="#mutasi-stock" data-toggle="tab"><b>MUTASI STOCK</b></a></li>
            <li><a href="#history-mutasi" id="history-mutasi2" data-toggle="tab"><b>HISTORY MUTASI STOCK</b></a></li>
            <!--<li><a href="#pending-mutasi" id="pending-mutasi2" data-toggle="tab"><b>PENDING MUTASI</b></a></li>-->
            <!--<li class="pull-right"><button class="btn" type="submit" id="btn-mutasi">Save changes</button></li>-->
        </ul>
        <div class="tab-content no-padding">
            <!-- Morris chart - Sales -->
            <div class="chart tab-pane active" id="mutasi-stock" style="position: relative; height: 300px;">
                <?php $this->load->view('main-mrp/stock-inventory/mutasi-stock-department');?>
            </div>
            <div class="chart tab-pane" id="history-mutasi" style="position: relative; height: 300px;">
                <?php $this->load->view('main-mrp/stock-inventory/history-mutasi'); ?>
            </div>
<!--            <div class="chart tab-pane" id="pending-mutasi" style="position: relative; height: 300px;">
                <?php // $this->load->view('report/pending-mutasi');?>
            </div>-->
        </div>
    </div>
  </div>
</div>

