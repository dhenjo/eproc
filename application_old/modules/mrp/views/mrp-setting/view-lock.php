<?php
//print $this->nbscache->get_olahan("permission", $this->session->userdata("id_privilege"), "edit-form-ro", "edit");
//die;
?>
<div class="row">
  <div class="col-xs-12">
 <?php if($detail[0]->id_mrp_setting_lock_atk > 0){?>
    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
            <li class="active"><a href="#edit-atk" data-toggle="tab"><b>Lock ATK</b></a></li>
            <li><a href="#view-lock-atk" id="dt-lock" data-toggle="tab"><b>View Lock ATK</b></a></li>
        </ul>
        <div class="tab-content no-padding">
            <!-- Morris chart - Sales -->
            <div class="chart tab-pane active" id="edit-atk" style="position: relative; height: 300px;">
                <?php $this->load->view('mrp-setting/add-lock-atk');?>
            </div>
            <div class="chart tab-pane" id="view-lock-atk" style="position: relative; height: 300px;">
                <?php $this->load->view('mrp-setting/view-lock-atk');?>
            </div>
        </div>
    </div>
 <?php }else{ ?>
      <?php $this->load->view('mrp-setting/add-lock-atk');?>
 <?php } ?>
  </div>
</div>
<div id="script-tambahan">
  
</div>
<div id="tambah-data">
</div>    

