<div class="row">
  <div class="col-xs-12">

    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
            <li class="active"><a href="#list-po" data-toggle="tab"><b>List PO</b></a></li>
            <li><a href="#perusahaan" id="history-rg-department2" data-toggle="tab"><b>Perusahaan</b></a></li>
            <li><a href="#supplier" id="history-rg-department2" data-toggle="tab"><b>Supplier</b></a></li>
            <!--<li class="pull-right"><button class="btn" type="submit" id="btn-mutasi">Save changes</button></li>-->
        </ul>
        <div class="tab-content no-padding">
            <!-- Morris chart - Sales -->
            <div class="chart tab-pane active" id="list-po" style="position: relative; height: 300px;">
                <?php $this->load->view('main-mrp/detail-po-umum/list-po');?>
            </div>
             <div class="chart tab-pane" id="perusahaan" style="position: relative; height: 300px;">
                <?php $this->load->view('main-mrp/detail-po-umum/perusahaan');?>
            </div>
           <div class="chart tab-pane" id="supplier" style="position: relative; height: 300px;">
                <?php $this->load->view('main-mrp/detail-po-umum/supplier');?>
            </div>
        </div>
    </div>
  </div>
</div>