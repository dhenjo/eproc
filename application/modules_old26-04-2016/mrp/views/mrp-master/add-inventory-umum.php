<style>
    .working{
        background:url('<?php print $url?>img/ajax-loader.gif') no-repeat right center;
        background-size: 20px;
    }
</style>
<div class="row">
    <!-- left column -->
    <div class="col-md-12">
        <!-- general form elements -->
        <div class="box box-primary">
            <div class="box-header">
                <!--<h3 class="box-title">Quick Example</h3>-->
            </div><!-- /.box-header -->
            <!-- form start -->
            <?php 
            
//            $akses = "normal";
//            $kunci = TRUE;
//            if($detail[0]->status > 1){
//              $akses = "view";
//              $kunci = FALSE;
//            }
            
//            if($akses != "view"){
              print $this->form_eksternal->form_open("", 'role="form"', array("id_detail" => $detail[0]->id_mrp_inventory_umum));
//            }
            ?>
              <div class="box-body">

                <div class="control-group">
                  <label>Type</label>
                  <?php print $this->form_eksternal->form_dropdown('type', $type, 
                  array($detail[0]->id_mrp_type_inventory), 'class="form-control dropdown2 input-sm"')?>
              </div>
                
                <div class="control-group">
                  <label>Nama</label>
                  <?php print $this->form_eksternal->form_input('name', $detail[0]->name, 'class="form-control input-sm" placeholder="Nama"');?>
                </div>
                  
                <div class="control-group">
                  <label>Kode</label>
                  <?php print $this->form_eksternal->form_input('code', $detail[0]->code, 'class="form-control input-sm" placeholder="Kode"');?>
                </div>
              
                <div class="control-group">
                  <label>Note</label>
                  <?php print $this->form_eksternal->form_textarea('note', $detail[0]->note, 'class="form-control input-sm" id="Note"')?>
             
                </div>
              </div>
              <div class="box-footer">
                 
                  <button class="btn btn-primary" type="submit">Save changes</button>
                  <a href="<?php print site_url("mrp/mrp-master/inventory-umum")?>" class="btn btn-warning"><?php print lang("cancel")?></a>
              </div>
        </div><!-- /.box -->
    </div><!--/.col (left) -->
</div>   <!-- /.row -->
