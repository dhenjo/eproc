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
              <div class="col-xs-12">
               <label>Inventory Umum</label>
                 <?php print $this->form_eksternal->form_input('inventory_spesifik_search_nama', $this->session->userdata("inventory_spesifik_search_nama"),
                  'class="form-control input-sm" placeholder="Inventory Umum"')?>
              </div>
            </div>
            <div class="form-group">
              <div class="col-xs-4">
                <label>Jenis</label>
                 <?php print $this->form_eksternal->form_dropdown('inventory_spesifik_search_jenis', $jenis, 
                  array($this->session->userdata('inventory_spesifik_search_jenis')), 'class="form-control dropdown2 input-sm"')?>
              </div>
             <div class="col-xs-4">
               <label>Brand</label>
                  <?php print $this->form_eksternal->form_input('inventory_spesifik_search_brand', $this->session->userdata("inventory_spesifik_search_brand"),
                  'class="form-control input-sm" placeholder="Brand"')?>
              </div>
              <div class="col-xs-4">
                <label>Satuan</label>
                <?php print $this->form_eksternal->form_input('inventory_spesifik_search_satuan', $this->session->userdata("inventory_spesifik_search_satuan"),
                  'class="form-control input-sm" placeholder="Satuan"')?>
              </div>
            </div>
              <div class="form-group">
              
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
            <h3 class="box-title">Inventory Spesifik List</h3>
            <div class="box-tools pull-right">
                <button class="btn btn-success btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
                <a href="<?php print site_url("mrp/mrp-master/add-inventory-spesifik")?>" class="btn btn-success btn-sm"><i class="fa fa-plus"></i></a>
                <!--<a href="<?php print site_url("mrp/mrp-master/list-upload-inventory-spesifik")?>" class="btn btn-success btn-sm"><i class="fa fa-cloud-upload"></i></a>-->
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
                        <th>Jenis</th>
                        <th>Inventory Umum</th>
                        <th>Title</th>
                        <th>Brand</th>
                        <th>Satuan</th>
                        <th>Note</th>
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
<!--<div id='collapseOne' class='panel-collapse collapse'>-->
<div>
<div class="row">
  <div class="col-xs-12">
    <div class="box box-solid box-success">
        <div class="box-header">
            <h3 class="box-title">Supplier</h3>
            <div class="box-tools pull-right">
                <button class="btn btn-success btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
                <!--<a href="<?php print site_url("mrp/add-request-pengadaan-atk")?>" class="btn btn-success btn-sm"><i class="fa fa-plus"></i></a>-->
            </div>
            <div class="widget-control pull-left">
              <span style="display: none; margin-left: 10px;" id="loader-page2"><img width="35px" src="<?php print $url?>img/ajax-loader.gif" /></span>
            </div>
        </div>
      
       
        <div class="box-body">
          
          <div class="row">
            <div class="box">
              <div class="box-body table-responsive">
                   <table class="table table-bordered table-hover" id="tableboxy2">
                  <thead>
                    <tr>
                        <th>Inventory</th>
                        <th>Supplier</th>
                        <th>Harga</th>
                        <!--<th>Action</th>-->
                    </tr>
                  </thead>
                  <tbody id="data_list">
                     
                  </tbody>
                </table>
               
                 
              </div>
            </div>
          </div>
             
        </div>
    </div>
  </div>
</div>
</div>
<div id="script-tambahan">
  
</div>