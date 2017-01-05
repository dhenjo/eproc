<?php
//print $this->nbscache->get_olahan("permission", $this->session->userdata("id_privilege"), "edit-form-ro", "edit");
//die;

?>
<div class="row">
  <div class="col-xs-12">

    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
             <li class="active"><a href="#view-cetakan" data-toggle="tab"><b>List Cetakan</b></a></li>
            <?php
            
            if($list[0]->id_mrp_request){
            ?>
            <li><a href="#cetakan" id="dt-cetakan" data-toggle="tab"><b>Cetakan</b></a></li>
            <?php } ?>
           
        </ul>
        <div class="tab-content no-padding">
             <div class="chart tab-pane active" id="view-cetakan" style="position: relative; height: 300px;">
                <?php $this->load->view("main-mrp/request-cetakan/view-request-pengadaan-cetakan"); ?>
            </div>
           <?php 
         
            if($list[0]->id_mrp_request){
          
           ?>
           <div class="chart tab-pane " id="cetakan" style="position: relative; height: 300px;">
                 <?php $this->load->view('main-mrp/request-cetakan/add-request-pengadaan-cetakan');?>
            </div>
            <?php } ?>
        </div>
    </div>

  </div>
</div>
<div id="script-tambahan">
  
</div>
<div id="tambah-data">
</div>    

