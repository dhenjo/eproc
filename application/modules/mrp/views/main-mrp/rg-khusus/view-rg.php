<div class="row">
  <div class="col-xs-12">

    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
            <li class="active"><a href="#list-rg" data-toggle="tab"><b>RG</b></a></li>
            <li><a href="#history-rg" id="history-rg2" data-toggle="tab"><b>History RG</b></a></li>
            <!--<li class="pull-right"><button class="btn" type="submit" id="btn-mutasi">Save changes</button></li>-->
        </ul>
        <div class="tab-content no-padding">
            <!-- Morris chart - Sales -->
            <div class="chart tab-pane active" id="list-rg" style="position: relative; height: 300px;">
                <?php $this->load->view('main-mrp/rg-khusus/rg');?>
            </div>
            <div class="chart tab-pane" id="history-rg" style="position: relative; height: 300px;">
                <?php $this->load->view('main-mrp/rg-khusus/history-rg');?>
            </div>
          
        </div>
    </div>
  </div>
</div>