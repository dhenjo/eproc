<div class="row">
  <div class="col-xs-12">

    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
            <li class="active"><a href="#alert-email-users" data-toggle="tab"><b>List Alert Email To Users</b></a></li>
            <li><a href="#alert-email-procurement" id="alert-email-procurement2" data-toggle="tab"><b>List Alert Email To Procurement</b></a></li>
           
            <!--<li class="pull-right"><button class="btn" type="submit" id="btn-mutasi">Save changes</button></li>-->
        </ul>
        <div class="tab-content no-padding">
            <!-- Morris chart - Sales -->
            <div class="chart tab-pane active" id="alert-email-users" style="position: relative; height: 300px;">
                <?php $this->load->view('mrp-setting/alert-email-users');?>
            </div>
            <div class="chart tab-pane" id="alert-email-procurement" style="position: relative; height: 300px;">
                <?php $this->load->view('mrp-setting/alert-email-procurement');?>
            </div>
           
        </div>
    </div>
  </div>
</div>