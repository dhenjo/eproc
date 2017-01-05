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
              print $this->form_eksternal->form_open("", 'role="form"', array("id_detail" => $detail[0]->id_mrp_inventory_spesifik));
//            }
            ?>
              <div class="box-body">
                  
                <div class="control-group">
                  <label>Jenis</label>
                 <?php print $this->form_eksternal->form_dropdown('jenis', array(1 => "Habis Pakai", 2 => "Asset"), 
                  array($detail[0]->jenis), 'class="form-control dropdown2 input-sm"')?>
              </div>
                
                <div class="control-group">
                  <label>Inventory Umum</label>
                  <?php 
          $type_inventory = $this->global_models->get_query("SELECT  B.code AS type_inventory"
        . " FROM mrp_inventory_umum AS A"
        . " LEFT JOIN mrp_type_inventory AS B ON A.id_mrp_type_inventory = B.id_mrp_type_inventory"    
        . " WHERE id_mrp_inventory_umum = '{$detail[0]->id_mrp_inventory_umum}'");
                  
        if($type_inventory[0]->type_inventory != ""){
            $dt_type =" [".$type_inventory[0]->type_inventory."]";
        }
                  print $this->form_eksternal->form_input("inventory_umum", $this->global_models->get_field("mrp_inventory_umum", "name", 
                    array("id_mrp_inventory_umum" => $detail[0]->id_mrp_inventory_umum)).$dt_type, 'id="inventory_umum" class="form-control input-sm" placeholder="Inventory Umum"');
                  print $this->form_eksternal->form_input("id_mrp_inventory_umum", $detail[0]->id_mrp_inventory_umum, 'id="id_mrp_inventory_umum" style="display: none"');
                  ?>
                </div>  
                  
                <div class="control-group">
                  <label>Title</label>
                  <?php print $this->form_eksternal->form_input('title', $detail[0]->title, 'class="form-control input-sm" placeholder="Title"');?>
                </div>
                 
                 <div class="control-group">
                  <label>Brand</label>
                  <?php 
                  print $this->form_eksternal->form_input("brand", $this->global_models->get_field("mrp_brand", "title", 
                    array("id_mrp_brand" => $detail[0]->id_mrp_brand)), 'id="brand" class="form-control input-sm" placeholder="Brand"');
                  print $this->form_eksternal->form_input("id_brand", $detail[0]->id_mrp_brand, 'id="id_brand" style="display: none"');
                  ?>
                </div>  
                  
                <div class="control-group">
                  <label>Satuan</label>
                  <?php 
                  print $this->form_eksternal->form_input("satuan", $this->global_models->get_field("mrp_satuan", "title", 
                    array("id_mrp_satuan" => $detail[0]->id_mrp_satuan)), 'id="satuan" class="form-control input-sm" placeholder="Satuan"');
                  print $this->form_eksternal->form_input("id_satuan", $detail[0]->id_mrp_satuan, 'id="id_satuan" style="display: none"');
                  ?>
                </div>  
                
                <div class="control-group">
                  <label>Note</label>
                  <?php print $this->form_eksternal->form_textarea('note', $detail[0]->note, 'class="form-control input-sm" id="Note"')?>
             
                </div>
              </div>
              <div class="box-footer">
                 
                  <button class="btn btn-primary" type="submit">Save changes</button>
                  <a href="<?php print site_url("mrp/mrp-master/inventory-spesifik")?>" class="btn btn-warning"><?php print lang("cancel")?></a>
              </div>
        </div><!-- /.box -->
    </div><!--/.col (left) -->
</div>   <!-- /.row -->
