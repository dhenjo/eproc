<div class="row">
  <div class="col-xs-12">

    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
            <li class="active"><a href="#list-stock" data-toggle="tab"><b>LIST USAGE</b></a></li>
            <li><a href="#stock-dept" id="stock-dept2" data-toggle="tab"><b>USAGE DEPT</b></a></li>
            <li><a href="#pending-mutasi" id="pending-mutasi2" data-toggle="tab"><b>PENDING MUTASI</b></a></li>
            <!--<li class="pull-right"><button class="btn" type="submit" id="btn-mutasi">Save changes</button></li>-->
        </ul>
        <div class="tab-content no-padding">
            <!-- Morris chart - Sales -->
            <div class="chart tab-pane active" id="list-stock" style="position: relative; height: 300px;">
                <?php $this->load->view('main-mrp/usage-department/view-list-stock');?>
            </div>
            <div class="chart tab-pane" id="stock-dept" style="position: relative; height: 300px;">
                <?php $this->load->view('main-mrp/usage-department/view-stock-dept-detail');?>
            </div>
            <div class="chart tab-pane" id="pending-mutasi" style="position: relative; height: 300px;">
                <?php $this->load->view('main-mrp/usage-department/pending-mutasi');?>
            </div>
        </div>
    </div>
  </div>
</div>