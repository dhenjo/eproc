<div class="row">
    <!-- left column -->
    <div class="col-md-12">
        <!-- general form elements -->
        <div class="box box-primary">
            <div class="box-header">
                <!--<h3 class="box-title">Quick Example</h3>-->
            </div><!-- /.box-header -->
            <!-- form start -->
            <?php print $this->form_eksternal->form_open_multipart("", 'role="form"', 
                    array("id_detail" => ""))?>
              <div class="box-body">
                <div class="control-group">
                  <label>template</label><br>
                  <a href="<?php print base_url("files/antavaya/template/inventory_spesifik.xls")?>" ><?php print lang("File Template")?></a>
<!--                   $file = "./files/antavaya/inventory/spesifik/".$detail[0]->file;-->
                </div>
                  <br>
                <div class="control-group">
                  <label>File</label>
                  <?php print $this->form_eksternal->form_upload('file', $detail[0]->file, "class='form-control input-sm'");
                   ?>
                </div>
                  <br>
                 
              <div class="box-footer">
                  <button class="btn btn-primary" type="submit">Save changes</button>
                  <a href="<?php print site_url("master/supplier/supplier-product/{$id_master_supplier}")?>" class="btn btn-warning"><?php print lang("cancel")?></a>
              </div>
        </div>
		
    </div><!--/.col (left) -->
</div>   <!-- /.row -->