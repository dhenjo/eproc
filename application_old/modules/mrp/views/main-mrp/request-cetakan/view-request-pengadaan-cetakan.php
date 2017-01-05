
<div class="row">
  <div class="col-xs-12">
    <div class="box box-solid box-success">
        <div class="box-header">
            <h3 class="box-title">Form Permintaan Pengadaan Cetakan</h3>
            <div class="box-tools pull-right">
                <button class="btn btn-success btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
                <!--<a href="<?php print site_url("mrp/add-request-pengadaan-cetakan")?>" class="btn btn-success btn-sm"><i class="fa fa-plus"></i></a>-->
            </div>
            <div class="widget-control pull-left">
              <span style="display: none; margin-left: 10px;" id="loader-page"><img width="35px" src="<?php print $url?>img/ajax-loader.gif" /></span>
            </div>
        </div>
      
        <div class="modal fade" id="permintaan-atk1" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Note Cetakan</h4>
            </div>
                <div class="modal-body">
                    <div class="form-group">
                        <div class="input-group">
<!--                            <span class="input-group-addon">Note Cancel:</span>-->
                           <?php $detail_note = $this->global_models->get_field("mrp_request", "note", 
                    array("id_mrp_request" => $id_mrp_request)); ?> 
                            <?php print $this->form_eksternal->form_textarea('note', $detail_note, 'style="margin: 0px; width: 553px; height: 227px;" class="form-control input-sm" id="note_cetakan"')?>
             
                            <!--<textarea name="note_cancel" placeholder="Note Cancel" style="margin: 0px; width: 553px; height: 227px;"></textarea>-->
                        </div>
                    </div>
                </div>
                <div class="modal-footer clearfix">

                    <button type="button" class="btn btn-warning" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>

                </div>
          
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
        <div class="box-body">
            <div class="row">
            <div class="form-group">
              <div class="col-xs-6">
                <!--<label>Note ATK</label>-->
                
                <button type='button' class='btn btn-success' data-toggle='modal' data-target='#permintaan-atk1' isi=''>
                      Note Cetakan
              </button>
              </div><br><br>
              <?php
           
            if($list[0]->id_mrp_request > 0){
                
               if($list[0]->create_by_users == $this->session->userdata("id")){
                
                    if($this->nbscache->get_olahan("permission", $this->session->userdata("id_privilege"), "user_ro", "edit") !== FALSE OR $this->session->userdata("id") == 1){
            ?>  
           <div class="control-group">
               <div class="col-xs-6">
                  <label>Users Request</label>
                  <?php 
                  if($list[0]->name){
                      $name = $list[0]->name." <".$list[0]->name_organisasi."><".$list[0]->email.">";
                  }else{
                      $name = "";
                  }
                  print $this->form_eksternal->form_input("users", $name, 'id="users" class="form-control input-sm" placeholder="Users Request"');
                  print $this->form_eksternal->form_input("id_users", $list[0]->id_hr_pegawai, 'id="id_users" style="display: none"');
                  ?>
                  </div>
                </div>
            <?php    }    
                }
            }else{
                if($this->nbscache->get_olahan("permission", $this->session->userdata("id_privilege"), "user_ro", "edit") !== FALSE OR $this->session->userdata("id") == 1){
            ?>
             <div class="control-group">
               <div class="col-xs-6">
                  <label>Users Request</label>
                  <?php 
                  if($list[0]->name){
                      $name = $list[0]->name." <".$list[0]->email.">";
                  }else{
                      $name = "";
                  }
                  print $this->form_eksternal->form_input("users", $name, 'id="users" class="form-control input-sm" placeholder="Users Request"');
                  print $this->form_eksternal->form_input("id_users", $list[0]->id_hr_pegawai, 'id="id_users" style="display: none"');
                  ?>
                  </div>
                </div>
            <?php }
            } ?>
            </div>
           
          </div><br><br>
          <div class="row">
            <div class="box">
              <div class="box-body table-responsive">
                <table class="table table-bordered table-hover" id="tableboxy">
                  <thead>
                    <tr>
                        <th style="width: 50px">No.</th>
                        <th>Nama Barang</th>
                        <th>Satuan</th>
                        <th style="width: 150px">Jumlah</th>
                        <th></th>
                    </tr>
                  </thead>
                  <tbody id="data_list">
                    
                  </tbody>
                  
                  <tfoot>
                  
                    <tr>
                      <td colspan="2">
                      <?php if($list[0]->status < 2){?>    
                          <button class="btn btn-primary" id="btn-task-orders">Save</button>&nbsp;
                          <button class="btn btn-warning" id="btn-draft">Draft</button>
                      <?php }elseif($this->session->userdata("id") == 1 OR $this->nbscache->get_olahan("permission", $this->session->userdata("id_privilege"), "approval-ro", "edit") !== FALSE){ ?>
                                <?php  if($list[0]->status < 3){?>
                          <button class="btn btn-primary" id="btn-save">Save</button>
                            <?php } ?>  
                      <?php }
                          if($this->session->userdata("id") == 1 OR $this->nbscache->get_olahan("permission", $this->session->userdata("id_privilege"), "approval-ro", "edit") !== FALSE){ ?>    
                            <?php if($list[0]->status > 2){?>
                          <button class="btn btn-primary" id="btn-save">Save</button>
                                <?php }?>
                            <?php }?>
                          <img src='<?php print $img; ?>' style='display:none' id='img-5' alt=''>
                      </td>
                      <td colspan="2">
                       <?php if($this->session->userdata("id") == 1 OR $this->nbscache->get_olahan("permission", $this->session->userdata("id_privilege"), "approval-ro", "edit") !== FALSE){
                                if($list[0]->status == 2){
                              ?>
                          <?php print $this->form_eksternal->form_open("", 'role="form"', array())?>
                           <button class="btn btn-info" value="approve" name="btn_approval" type="submit" >Approve</button>
                            </form>
                            <?php }?>
                        <?php } ?>
                      </td>
                    </tr>
                  
                  </tfoot>
                 
                </table>
              </div>
            </div>
          </div> 
        </div>
    </div>
  </div>
</div>
<div id="script-tambahan">
  
</div>
<div id="tambah-data">
</div>    
