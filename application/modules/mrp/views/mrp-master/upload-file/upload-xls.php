
<div class="row">
  <div class="col-xs-12">
    <div class="box box-solid box-primary">
        <div class="box-header">
            <h3 class="box-title"><?php print $this->global_models->get_field("mrp_supplier", "name", 
                    array("id_mrp_supplier" => $id_mrp_supplier)); ?></h3>
            <div class="box-tools pull-right">
                <button class="btn btn-primary btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
            </div>
        </div>
         
        <div class="box-body">
         <?php print $this->form_eksternal->form_open_multipart("", 'role="form"', 
                    array("id_detail" => $id_mrp_supplier))?>
          <div class="row">
              <div class="form-group">
              <div class="col-xs-12">
               <label>Template</label>
               <a href="<?php print base_url("files/antavaya/template/template_supplier_inventory_file.xls")?>" class="btn btn-success"><?php print lang("Download")?></a>
                
              </div>
            </div>
            <div class="form-group">
              <div class="col-xs-12">
               <label>File</label>
                  <?php print $this->form_eksternal->form_upload('file', "", "class='form-control input-sm'"); ?>
              </div>
            </div>
              <div class="form-group">
              
            </div>
          </div>
          <div class="row">
            <div class="col-xs-3">
              <br />
               <button class="btn btn-primary" type="submit">Save changes</button>
                  <a href="<?php print site_url("inventory/product-tour")?>" class="btn btn-warning"><?php print lang("cancel")?></a>
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
            <h3 class="box-title">List Files Upload</h3>
            <div class="box-tools pull-right">
                <button class="btn btn-success btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
               
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
                        <th>Files</th>
                        <th>Created Date</th>
                        <th>Status</th>
                        <th>Action</th>
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