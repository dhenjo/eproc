<div class="row">
  <div class="col-xs-12">
    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
            <li class="active"><a href="#mutasi-stock" data-toggle="tab"><b>MUTASI STOCK</b></a></li>
            <li><a href="#stock-in" id="stock-in2" data-toggle="tab"><b>STOCK IN</b></a></li>
            <li class="pull-right"><button class="btn" type="submit" id="btn-mutasi">Save changes</button></li>
        </ul>
        <div class="tab-content no-padding">
            <!-- Morris chart - Sales -->
            <div class="chart tab-pane active" id="mutasi-stock" style="position: relative; height: 300px;">
                <?php $this->load->view('main-mrp/mutasi-stock/view-mutasi');?>
            </div>
            <div class="chart tab-pane" id="stock-in" style="position: relative; height: 300px;">
                <?php $this->load->view('main-mrp/mutasi-stock/view-stock-in');?>
            </div>
        </div>
    </div>
  </div>
</div>