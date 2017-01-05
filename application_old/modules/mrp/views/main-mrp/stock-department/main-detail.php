<div class="row">
  <div class="col-xs-12">

    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
            <li class="active"><a href="#pemakaian" data-toggle="tab"><b>PEMAKAIAN</b></a></li>
            <li><a href="#detail-stock" id="detail-stock2" data-toggle="tab"><b>DETAIL STOCK</b></a></li>
            <li><a href="#detail-pemakaian" id="detail-pemakaian2" data-toggle="tab"><b>DETAIL PEMAKAIAN</b></a></li>
            <!--<li class="pull-right"><button class="btn" type="submit" id="btn-mutasi">Save changes</button></li>-->
        </ul>
        <div class="tab-content no-padding">
            <!-- Morris chart - Sales -->
            <div class="chart tab-pane active" id="pemakaian" style="position: relative; height: 300px;">
                <?php $this->load->view('main-mrp/stock-department/view-create-pemakaian');?>
            </div>
            <div class="chart tab-pane" id="detail-stock" style="position: relative; height: 300px;">
                <?php $this->load->view('main-mrp/stock-department/view-stock-dept-detail');?>
            </div>
            <div class="chart tab-pane" id="detail-pemakaian" style="position: relative; height: 300px;">
                <?php $this->load->view('main-mrp/stock-department/view-detail-pemakaian');?>
            </div>
        </div>
    </div>
  </div>
</div>