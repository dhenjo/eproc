<?php
//print $this->nbscache->get_olahan("permission", $this->session->userdata("id_privilege"), "edit-form-ro", "edit");
//die;

?>
<div class="row">
  <div class="col-xs-12">

    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
             <li class="active"><a href="#view-service" data-toggle="tab"><b>List Service</b></a></li>
            <?php
            if($list[0]->status < 6 OR $list[0]->status == 11 ){
            if($list[0]->id_mrp_request){
            ?>
            <li><a href="#data-service" id="dt-service" data-toggle="tab"><b>Service</b></a></li>
            <?php } }
            if($this->session->userdata("id") == 1 OR $this->nbscache->get_olahan("permission", $this->session->userdata("id_privilege"), "request-mutasi-stock", "edit") !== FALSE){         
             if($this->session->userdata("id") == 1 OR $list[0]->status >= 3){
            ?>
            <?php if($list[0]->status <= 9){?>
            <li><a href="#req-mutasi-stock" id="req-mutasi-stock2" data-toggle="tab"><b>MUTASI STOCK</b></a></li>
            <li><a href="#history-req-mutasi-stock" id="history-req-mutasi-stock2" data-toggle="tab"><b>HISTORY MUTASI STOCK</b></a></li>
         <?php }
             }
            }
         ?> 
         <?php
         if($list[0]->status >= 3 AND $list[0]->status != 9){
         if($this->session->userdata("id") == 1 OR $this->nbscache->get_olahan("permission", $this->session->userdata("id_privilege"), "button-closed-request-orders", "edit") !== FALSE){  
         ?>   
             <?php print $this->form_eksternal->form_open("", 'role="form"', 
                    array("id_detail" => $list[0]->id_mrp_request))?>
            <?php if($list[0]->status <= 8){ ?>
            <div style="padding-left: 82%"><button class="btn btn-info" value="closed" name="btn_closed" type="submit" >Closed Request</button></div>
              </form>
            <?php } ?>
         <?php } } ?>     
        </ul>
       
        <div class="tab-content no-padding">
             <div class="chart tab-pane active" id="view-service" style="position: relative; height: 300px;">
                <?php $this->load->view("mrp-request/pengadaan-service/request-service/view-request-pengadaan-service"); ?>
            </div>
           <?php 
           if($list[0]->status < 6 OR $list[0]->status == 11){
           if($list[0]->id_mrp_request){
           ?>
           <div class="chart tab-pane " id="data-service" style="position: relative; height: 300px;">
                 <?php $this->load->view('mrp-request/pengadaan-service/request-service/add-request-pengadaan-service');?>
            </div>
           <?php } }
         if($this->session->userdata("id") == 1 OR $this->nbscache->get_olahan("permission", $this->session->userdata("id_privilege"), "request-mutasi-stock", "edit") !== FALSE){         
            if($this->session->userdata("id") == 1 OR $list[0]->status >= 3){
          ?>
            <?php if($list[0]->status <= 9){?>
            <div class="chart tab-pane " id="req-mutasi-stock" style="position: relative; height: 300px;">
                 <?php $this->load->view('mrp-request/pengadaan-service/request-service/mutasi-stock-department');?>
            </div>
            <div class="chart tab-pane " id="history-req-mutasi-stock" style="position: relative; height: 300px;">
                 <?php $this->load->view('mrp-request/pengadaan-service/request-service/history-mutasi');?>
            </div>
         <?php }
            }
         }
         ?>
        </div>
    </div>

  </div>
</div>
<div id="script-tambahan">
  
</div>
<div id="tambah-data">
</div>    

