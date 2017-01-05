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
                <label>Supplier</label>
                 <?php print $this->form_eksternal->form_input('supplier_search_name', $this->session->userdata("supplier_search_name"),
                  'class="form-control input-sm" placeholder="Supplier"')?>
              </div>
              <div class="col-xs-6">
                <label>PIC</label>
                <?php print $this->form_eksternal->form_input('supplier_search_pic', $this->session->userdata("supplier_search_pic"),
                  'class="form-control input-sm" placeholder="PIC"')?>
              </div>
            </div>
            <div class="form-group">
              <div class="col-xs-6">
               <label>Phone</label>
                 <?php print $this->form_eksternal->form_input('supplier_search_phone', $this->session->userdata("supplier_search_phone"),
                  'class="form-control input-sm" placeholder="Phone"')?>
              </div>
              <div class="col-xs-6">
                <label>Fax</label>
                <?php print $this->form_eksternal->form_input('supplier_search_fax', $this->session->userdata("supplier_search_fax"),
                  'class="form-control input-sm" placeholder="Fax"')?>
              </div>
            </div>
              <div class="form-group">
              <div class="col-xs-6">
               <label>Email</label>
                 <?php print $this->form_eksternal->form_input('supplier_search_email', $this->session->userdata("supplier_search_email"),
                  'class="form-control input-sm" placeholder="Email"')?>
              </div>
              <div class="col-xs-6">
                <label>Website</label>
                 <?php print $this->form_eksternal->form_input('supplier_search_website', $this->session->userdata("supplier_search_website"),
                  'class="form-control input-sm" placeholder="Website"')?>
              </div>
            </div>
              <div class="form-group">
              <div class="col-xs-12">
               <label>Address</label>
                 <?php print $this->form_eksternal->form_input('supplier_search_address', $this->session->userdata("supplier_search_address"),
                  'class="form-control input-sm" placeholder="Address"')?>
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
            <h3 class="box-title">Supplier List</h3>
            <div class="box-tools pull-right">
                <button class="btn btn-success btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
                <a href="<?php print site_url("mrp/mrp-master/add-supplier")?>" class="btn btn-success btn-sm" title="Add Supplier"><i class="fa fa-plus"></i></a>
                
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
                        <th>Supplier Name</th>
                        <th>PIC</th>
                        <th>Phone</th>
                        <th>Fax</th>
                        <th>Email</th>
                        <th>Website</th>
                        <th>Address</th>
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