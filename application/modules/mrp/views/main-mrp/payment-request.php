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
                    array("id_detail" => $id_po))?>
              <div class="box-body">
                   <div class="control-group">
                  <label>Tanggal Pembayaran</label>
                  <?php print $this->form_eksternal->form_input("tgl_payment", $list[0]->tanggal_payment, ' id="tgl_payment" class="form-control date_pymnt input-sm" placeholder="Tanggal Payment"'); ?>
                </div>
                
                <div class="control-group">
                  <label>Note Pembayaran</label>
                 <?php print $this->form_eksternal->form_textarea('note_pembayaran', $list[0]->note_payment, ' id="note_po" class="form-control input-sm"'); ?>
                </div>
            <?php
            $status_pembayaran = array("" => "-Pilih-",1 => "Lunas", 2 => "Belum Lunas");
//            print $list[0]->status_payment;
//            die('aa');
            ?>
                <div class="control-group" class="col-xs-6">
                  <label>Status</label>
                <?php print $this->form_eksternal->form_dropdown("status_pembayaran", $status_pembayaran, 
                    $list[0]->status_payment, 'id="status_pembayaran" class="form-control dropdown2 input-sm"');?>
                </div>

              </div>
              <div class="box-footer">
                  <button class="btn btn-primary" type="submit">Save changes</button>
                  <a href="<?php print site_url("mrp/mrp-po/list-po")?>" class="btn btn-warning"><?php print lang("cancel")?></a>
              </div>
        </div><!-- /.box -->
    </div>
   

