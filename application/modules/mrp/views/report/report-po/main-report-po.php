<div class="row">
  <div class="col-xs-12">

    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
            <li class="active"><a href="#list-report" data-toggle="tab"><b>LIST REPORT PO</b></a></li>
<!--            <li><a href="#asset-dept" id="stock-dept2" data-toggle="tab"><b>ASSET DEPT</b></a></li>
            <li><a href="#pending-mutasi" id="pending-mutasi2" data-toggle="tab"><b>PENDING MUTASI</b></a></li>-->
            <!--<li class="pull-right"><button class="btn" type="submit" id="btn-mutasi">Save changes</button></li>-->
        </ul>
        <div class="tab-content no-padding">
            <!-- Morris chart - Sales -->
            <div class="chart tab-pane active" id="list-report" style="position: relative; height: 300px;">
                <?php $this->load->view('report/report-po/view-list-report-po');?>
            </div>
<!--            <div class="chart tab-pane" id="asset-dept" style="position: relative; height: 300px;">
                <?php // $this->load->view('report/view-asset-dept-detail');?>
            </div>
            <div class="chart tab-pane" id="pending-mutasi" style="position: relative; height: 300px;">
                <?php // $this->load->view('report/pending-mutasi');?>
            </div>-->
        </div>
    </div>
  </div>
</div>