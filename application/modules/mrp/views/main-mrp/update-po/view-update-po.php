<div class="row">
  <div class="col-xs-12">

    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
            <li class="active"><a href="#list-po" data-toggle="tab"><b>List PO</b></a></li>
            <li><a href="#update-po" id="update-po2" data-toggle="tab"><b>Update PO</b></a></li>
            <!--<li class="pull-right"><button class="btn" type="submit" id="btn-mutasi">Save changes</button></li>-->
        </ul>
        <div class="tab-content no-padding">
            <!-- Morris chart - Sales -->
            <div class="chart tab-pane active" id="list-po" style="position: relative; height: 300px;">
                <?php $this->load->view('main-mrp/update-po/list');?>
            </div>
            <div class="chart tab-pane" id="update-po" style="position: relative; height: 300px;">
                <?php $this->load->view('main-mrp/update-po/update');?>
            </div>
          
        </div>
    </div>
  </div>
</div>