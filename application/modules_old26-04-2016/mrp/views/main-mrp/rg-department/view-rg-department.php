<div class="row">
  <div class="col-xs-12">

    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
            <li class="active"><a href="#list-rg-department" data-toggle="tab"><b>RG Department</b></a></li>
            <li><a href="#history-rg-department" id="history-rg-department2" data-toggle="tab"><b>History RG Department</b></a></li>
            <!--<li class="pull-right"><button class="btn" type="submit" id="btn-mutasi">Save changes</button></li>-->
        </ul>
        <div class="tab-content no-padding">
            <!-- Morris chart - Sales -->
            <div class="chart tab-pane active" id="list-rg-department" style="position: relative; height: 300px;">
                <?php $this->load->view('main-mrp/rg-department/list-rg-department');?>
            </div>
            <div class="chart tab-pane" id="history-rg-department" style="position: relative; height: 300px;">
                <?php $this->load->view('main-mrp/rg-department/history-rg-department');?>
            </div>
          
        </div>
    </div>
  </div>
</div>