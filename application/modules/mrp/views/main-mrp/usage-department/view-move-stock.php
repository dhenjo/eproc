<?php
//print $name; 
//die;

?>

<div class="col-xs-12">
    <div class="box box-solid box-primary">
        <div class="box-header">
            <h3 class="box-title">Move To Stock <?php print $name;?></h3>
            <div class="box-tools pull-right">
                <div class="widget-control pull-left">
                    <span style="display: none; margin-left: 10px;" id="btn-loader-page"><img width="35px" src="<?php print $url?>img/ajax-loader.gif" /></span>
                  </div>
                <?php // if($id_hr_master_organisasi === $this->session->userdata("stock_dept_search_id_hr_master")){?>
                <button class="btn" type="submit" id="btn-move">Save changes</button>
                <?php // } ?>
                <button class="btn btn-primary btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
            </div>
             <div class="widget-control pull-left">
              <span style="display: none; margin-left: 10px;" id="loader-page2"><img width="35px" src="<?php print $url?>img/ajax-loader.gif" /></span>
            </div>
        </div>
        <div class="box-body">
          <table class="table table-striped">
            <tr>
                <th style="width: 25%">Available Barang</th>
                <td><?php  print $jumlah;  ?></td>
            </tr>
            <tr>
              <th>Tanggal</th>
              <td><?php // if($id_hr_master_organisasi === $this->session->userdata("stock_dept_search_id_hr_master")){?>
                  <?php  print $this->form_eksternal->form_input("tgl_move", "", ' id="tgl_move" class="form-control date input-sm" placeholder="Tanggal"');  ?></td>
              <?php // }?>
            
             <tr>
              <th>Jumlah</th>
              <th><?php // if($id_hr_master_organisasi === $this->session->userdata("stock_dept_search_id_hr_master")){?>
                  <?php print $this->form_eksternal->form_input('jumlah_move', "", 'min="0" id="jml_move" class="form-control input-sm" placeholder="Jumlah"');?></th>
              <?php // }?>
             </tr>
            <tr>
              <th>Note</th>
              <th><?php // if($id_hr_master_organisasi === $this->session->userdata("stock_dept_search_id_hr_master")){?>
                  <?php print $this->form_eksternal->form_textarea('note_move', "", 'class="form-control input-sm" id="note_move"')?>
              <?php // }?>
                </th>
            </tr>
          </table>
        </div>
     
    </div>
  </div>