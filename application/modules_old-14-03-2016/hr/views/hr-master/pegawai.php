<div class="row">
  <div class="col-xs-12">
    <div class="box box-solid box-primary">
        <div class="box-header">
            <h3 class="box-title">Pencarian</h3>
            <div class="box-tools pull-right">
                <button class="btn btn-primary btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
            </div>
        </div>
        <div class="box-body">
          <?php print $this->form_eksternal->form_open("", 'role="form"', array())?>
          <div class="row">
            <div class="form-group">
              <div class="col-xs-6">
                <label>Users</label>
                <?php print $this->form_eksternal->form_dropdown('pegawai_search_user', $users, 
                  array($this->session->userdata("pegawai_search_user")), 'class="form-control dropdown2 input-sm"')?>
              </div>
              <div class="col-xs-6">
                <label>NIP</label>
                <?php print $this->form_eksternal->form_input('pegawai_search_nip', $this->session->userdata("pegawai_search_nip"),
                  'class="form-control input-sm" placeholder="NIP"')?>
              </div>
            </div>
            <div class="form-group">
              <div class="col-xs-6">
                <label>Company</label>
                <?php print $this->form_eksternal->form_dropdown('pegawai_search_company', $company, 
                  array($this->session->userdata("pegawai_search_company")), 'class="form-control dropdown2 input-sm"')?>
              </div>
              <div class="col-xs-6">
                <label>Department</label>
                <?php print $this->form_eksternal->form_dropdown('pegawai_search_department', $department, array($this->session->userdata("pegawai_search_department")),'class="form-control dropdown2 input-sm"')?>
              </div>
            </div>
            
          </div>
          <div class="row">
            <div class="col-xs-3">
              <br />
              <button class="btn btn-primary" type="submit">Search</button>
              <hr />
            </div>
          </div>
          </form> 
        </div>
    </div>
  </div>
</div>
<div class="row">
  <div class="col-xs-12">
    <div class="box box-solid box-success">
        <div class="box-header">
            <h3 class="box-title">Pegawai List</h3>
            <div class="box-tools pull-right">
                <button class="btn btn-success btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
                <a href="<?php print site_url("hr/hr-master/add-pegawai")?>" class="btn btn-success btn-sm"><i class="fa fa-plus"></i></a>
            </div>
            <div class="widget-control pull-left">
              <span style="display: none; margin-left: 10px;" id="loader-page"><img width="35px" src="<?php print $url?>img/ajax-loader.gif" /></span>
            </div>
        </div>
        <div class="box-body">
          <div class="row">
            <div class="box">
              <div class="box-body table-responsive">
                <table class="table table-bordered table-hover" id="tableboxy">
                  <thead>
                    <tr>
                        <th>Users</th>
                        <th>Company</th>
                        <th>Organisasi</th>
                        <th>NIP</th>
                        <th>Action</th>
                    </tr>
                  </thead>
                  <tbody id="data_list">
                    
                  </tbody>
<!--                  <tfoot>
                    <tr>
                      <td colspan="3">
                        <button type='button' class='btn btn-warning btn-flat'>Set Draft</button>
                        <button type='button' class='btn btn-danger btn-flat'>Delete</button>
                      </td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      
                    </tr>
                  </tfoot>-->
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