
<div class="row">
    <!-- left column -->
    <div class="col-md-12">
        <!-- general form elements -->
        <div class="box box-primary">
            <div class="box-header">
                <!--<h3 class="box-title">Quick Example</h3>-->
            </div><!-- /.box-header -->
            <!-- form start -->
            <?php print $this->form_eksternal->form_open("", 'role="form"', 
                    array("id_detail" => $detail[0]->id_mrp_setting_users))?>
              <div class="box-body">
                <div class="control-group">
                  <label>Title</label>
                  <?php 
                  print $this->form_eksternal->form_input("title",$detail[0]->title, ' class="form-control input-sm" placeholder="Title"');
                  ?>
                </div>
                  
                <div class="control-group">
                  <label>Users</label>
                  <?php 
                  if($detail[0]->name){
                      $nama = $detail[0]->name." <".$detail[0]->email."><".$detail[0]->title_organisasi."><".$detail[0]->title_company.">";
                  }else{
                      $nama = "";
                  }
                  
                  print $this->form_eksternal->form_input("users",$nama, 'id="users" class="form-control input-sm" placeholder="users"');
                  print $this->form_eksternal->form_input("id_users", $detail[0]->id_users, 'id="id_users" style="display: none"');
                  ?>
                </div>
                
                 <div class="control-group">
                  <label>Akses Ke Users</label>
                  <?php print $hasil;?>
                  <div class="input-group margin" id="akses-users-box">
                      <input type="text" class="form-control" id="akses_users" name="akses_users[]">
                      <input type="text" class="form-control" id="id_akses_users" name="id_akses_users[]" style="display: none">
                      <span class="input-group-btn">
                          <a href="javascript:void(0)" class="btn btn-danger btn-flat delete" isi="akses-users-box" >
                            <i class="fa fa-fw fa-times"></i>
                          </a>
                      </span>
                  </div>
                  <div id="wadah"></div>
                </div>
                  
                <div class="control-group">
                  <br />
                  <a href="javascript:void(0)" id="add-row" class="btn btn-success btn-sm"><i class="fa fa-fw fa-plus"></i></a>
                </div>
 
              </div>
              <div class="box-footer">
                  <input type="text" class="form-control" value="<?php print $detail2;?>" id="nomor" style="display: none">
                  <button class="btn btn-primary" type="submit">Save changes</button>
                  <a href="<?php print site_url("mrp/mrp-setting/setting-users-request")?>" class="btn btn-warning"><?php print lang("cancel")?></a>
              </div>
        </div><!-- /.box -->
    </div><!--/.col (left) -->
</div>   <!-- /.row -->
