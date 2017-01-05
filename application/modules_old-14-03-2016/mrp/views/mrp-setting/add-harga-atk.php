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
                    array("id_detail" => $detail[0]->id_mrp_setting_harga_atk))?>
              <div class="box-body">

              <div class="control-group">
               <label>Supplier</label>
               <?php 
                print $this->form_eksternal->form_input("supplier", $detail[0]->name, 'id="supplier" class="form-control input-sm" placeholder="Supplier"');
                  print $this->form_eksternal->form_input("id_supplier", $detail[0]->id_mrp_supplier, 'id="id_supplier" style="display: none"');
                  
                  ?>
             </div>
                
              </div>
              <div class="box-footer">
                  <button class="btn btn-primary" type="submit">Save changes</button>
                  <a href="<?php print site_url("mrp/mrp-setting/harga-atk")?>" class="btn btn-warning"><?php print lang("cancel")?></a>
              </div>
        </div><!-- /.box -->
    </div><!--/.col (left) -->
</div>   <!-- /.row -->
