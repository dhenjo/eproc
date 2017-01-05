<!--<div class="row">
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
               <label>Inventory Spesifik</label>
                 <?php print $this->form_eksternal->form_input('history_supplier_inventory_search_nama', $this->session->userdata("history_supplier_inventory_search_nama"),
                  'class="form-control input-sm" placeholder="Inventory Spesifik"')?>
              </div>
              <div class="col-xs-6">
                <label>Harga</label>
                <?php print $this->form_eksternal->form_input('history_supplier_inventory_search_harga', $this->session->userdata("history_supplier_inventory_search_harga"), ' class="form-control input-sm" placeholder="Harga"');?>
               
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
</div>-->
<div class="row">
  <div class="col-xs-12">
    <div class="box box-solid box-success">
        <div class="box-header">
            <h3 class="box-title">History Supplier Inventory <?php print $this->global_models->get_field("mrp_supplier", "name", 
                    array("id_mrp_supplier" => $id_mrp_supplier)); ?> List</h3>
            <div class="box-tools pull-right">
                <button class="btn btn-success btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
                <!--<a href="<?php print site_url("mrp/mrp-master/add-supplier-inventory/{$id_mrp_supplier}")?>" class="btn btn-success btn-sm"><i class="fa fa-plus"></i></a>-->
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
                        <th>Tanggal Update</th>
                        <th>Tanggal</th>
                        <th>Inventory Spesifik</th>
                        <th>Harga</th>
                        <th>Note</th>
                        <th>Status</th>
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