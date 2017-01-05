<?php
//print "<pre>";
//print
//print_r($list);
//print "</pre>";
//die;
?>
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
                    array("id_detail" => $detail[0]->id_hr_pegawai))?>
              <div class="box-body">
                   <div class="control-group">
                  <label>Users</label>
                  <?php 
                  $users = $detail[0]->name." <".$detail[0]->email.">";
                  print $this->form_eksternal->form_input("users", $users, 'id="users" class="form-control input-sm" placeholder="users"');
                  print $this->form_eksternal->form_input("id_users", $detail[0]->id_users, 'id="id_users" style="display: none"');
                  ?>
                </div>
                
                <div class="control-group">
                  <label>Company</label>
                  <?php print $this->form_eksternal->form_dropdown("id_company", $company, 
                    array($detail[0]->id_hr_company), 'id="id_company" class="form-control dropdown2 input-sm"');?>
                </div>
                <span id="dt_direktorat">
                    <div class="control-group">
                      <label>Direktorat</label>
                     <?php print $this->form_eksternal->form_dropdown("id_hr_master_organisasi[]", "", 
                        array($detail[0]->id_hr_company_department), 'id="id_direktorat" class="form-control dropdown2 input-sm"');?>
                    </div>
                </span>
                <span id="dt_department">
                    <div class="control-group">
                      <label>Department</label>
                     <?php print $this->form_eksternal->form_dropdown("id_hr_master_organisasi[]", "", 
                        array($detail[0]->id_hr_company_department), 'id="id_department" class="form-control dropdown2 input-sm"');?>
                    </div>
                </span> 
                  
                <span id="dt_divisi">
                    <div class="control-group">
                      <label>Divisi</label>
                     <?php print $this->form_eksternal->form_dropdown("id_hr_master_organisasi[]", "", 
                        array($detail[0]->id_hr_company_department), 'id="id_divisi" class="form-control dropdown2 input-sm"');?>
                    </div>
                </span> 
                  
                <span id="dt_section">
                    <div class="control-group">
                      <label>Section</label>
                     <?php print $this->form_eksternal->form_dropdown("id_hr_master_organisasi[]", "", 
                        array($detail[0]->id_hr_company_department), 'id="id_section" class="form-control dropdown2 input-sm"');?>
                    </div>
                </span>  
                
                <div class="control-group">
                  <label>NIP</label>
                  <?php print $this->form_eksternal->form_input('nip', $detail[0]->nip, 'class="form-control input-sm" placeholder="NIP"');?>
                </div>
                
              </div>
              <div class="box-footer">
                  <button class="btn btn-primary" type="submit">Save changes</button>
                  <a href="<?php print site_url("hr/hr-master/pegawai")?>" class="btn btn-warning"><?php print lang("cancel")?></a>
              </div>
        </div><!-- /.box -->
    </div><!--/.col (left) -->
</div>   <!-- /.row -->
