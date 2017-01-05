<?php
//print $this->nbscache->get_olahan("permission", $this->session->userdata("id_privilege"), "edit-form-ro", "edit");
//die;

?>
<div class="row">
  <div class="col-xs-12">

    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
             <li class="active"><a href="#view-atk" data-toggle="tab"><b>List ATK</b></a></li>
            <?php
            
            if($list[0]->id_mrp_request){
            ?>
            <li><a href="#atk" id="dt-atk" data-toggle="tab"><b>ATK</b></a></li>
            <?php } ?>
           
        </ul>
        <div class="tab-content no-padding">
             <div class="chart tab-pane active" id="view-atk" style="position: relative; height: 300px;">
                <?php $this->load->view("main-mrp/request-atk/view-request-pengadaan-atk"); ?>
            </div>
           <?php 
         
            if($list[0]->id_mrp_request){
          
           ?>
           <div class="chart tab-pane " id="atk" style="position: relative; height: 300px;">
                 <?php $this->load->view('main-mrp/request-atk/add-request-pengadaan-atk');?>
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

