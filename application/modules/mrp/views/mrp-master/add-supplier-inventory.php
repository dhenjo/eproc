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
//            print $detail[0]->id_mrp_supplier_inventory; die;
              print $this->form_eksternal->form_open("", 'role="form"', array("id_detail" => $id_mrp_supplier_inventory,"id_mrp_supplier" => $id_mrp_supplier));

            ?>
            <?php
            if($detail[0]->name)
            $inventory_spesifik = $detail[0]->name." [Jenis Barang:".$jenis[$detail[0]->jenis]."] [Type:".$detail[0]->type."] [Brand:".$detail[0]->brand."] [Satuan:".$detail[0]->satuan."]";
            ?>
              <div class="box-body">

               <span id="tambah-department">
                  <div class="control-group">
                  <label>Inventory Spesifik</label>
                  <?php 
                  print $this->form_eksternal->form_input("inventory_spesifik", $inventory_spesifik, 'id="inventory_spesifik" class="form-control input-sm" placeholder="Inventory Spesifik"');
                  print $this->form_eksternal->form_input("id_mrp_inventory_spesifik", $detail[0]->id_mrp_inventory_spesifik, 'id="id_mrp_inventory_spesifik" style="display: none"');
                  ?>
                </div>  
                </span>
                  <?php
                  if($detail[0]->harga){
                      $harga =number_format($detail[0]->harga);
                  }
                  ?>
                <div class="control-group">
                  <label>Harga</label>
                  <?php print $this->form_eksternal->form_input('harga', $harga, 'onkeyup="FormatCurrency(this)" class="form-control input-sm" placeholder="Harga"');?>
                </div>
                <?php
                if($detail[0]->tanggal){
                    $tanggal = $detail[0]->tanggal;
                }else{
                     $tanggal = date("Y-m-d");
                }
                ?>
                <div class="control-group">
                  <label>Tanggal</label>
                  <?php print $this->form_eksternal->form_input('tanggal', $tanggal, 'id="start_date" class="form-control input-sm" placeholder="Tanggal"');?>
                </div>
                
                 <div class="control-group">
                  <label>Note</label>
                  <?php print $this->form_eksternal->form_textarea('note', $detail[0]->note, 'class="form-control input-sm" id="editor2"');?>
                </div>
                
                   <div class="control-group">
                    <h4><b>Status</b></h4>
                    <div class="input-group">
                        <div class="checkbox">
                            <label>
                                <?php
                                if($detail[0]->status == 1)
                                  print $this->form_eksternal->form_checkbox('status', 1, TRUE);
                                else
                                  print $this->form_eksternal->form_checkbox('status', 1, FALSE);
                                ?>
                                Active
                            </label>
                        </div>
                        </div>
                </div>
                
              </div>
              <div class="box-footer">
                 
                  <button class="btn btn-primary" type="submit">Save changes</button>
                  
                  <a href="<?php print site_url("mrp/mrp-master/supplier-inventory/{$id_mrp_supplier}")?>" class="btn btn-warning"><?php print lang("cancel")?></a>
              </div>
        </div><!-- /.box -->
    </div><!--/.col (left) -->
</div>   <!-- /.row -->
