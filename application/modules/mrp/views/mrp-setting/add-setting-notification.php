
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
                    array("id_detail" => $detail[0]->id_setting_blast_email_rg))?>
              <div class="box-body">
                <div class="control-group">
                  <label>Supplier</label>
                  <?php 
                  $name = $this->global_models->get_field("mrp_supplier","name",array("id_mrp_supplier" => "{$detail[0]->id_mrp_supplier}"));
//                  if($detail[0]->name){
//                      $nama = $detail[0]->name." <".$detail[0]->email."><".$detail[0]->title_organisasi."><".$detail[0]->title_company.">";
//                  }else{
//                      $nama = "";
//                  }
                
                  print $this->form_eksternal->form_input("supplier",$name, 'id="supplier" class="form-control input-sm" placeholder="Supplier"');
                  print $this->form_eksternal->form_input("id_supplier", $detail[0]->id_mrp_supplier, 'id="id_supplier" style="display: none"');
                  ?>
                </div>
            <div class="control-group">
                  <label>Days</label>
                  <input type="number" name='days' value="<?php print $detail[0]->days; ?>" placeholder="Days" class="form-control input-sm" />
                  
            </div>
                 
              </div>
              <div class="box-footer">
                  <input type="text" class="form-control" value="<?php print $detail2;?>" id="nomor" style="display: none">
                  <button class="btn btn-primary" type="submit">Save changes</button>
                  <a href="<?php print site_url("mrp/mrp-setting/setting-notification-email-rg")?>" class="btn btn-warning"><?php print lang("cancel")?></a>
              </div>
        </div><!-- /.box -->
    </div><!--/.col (left) -->
</div>   <!-- /.row -->
