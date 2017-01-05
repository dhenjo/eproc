<div class="col-xs-12">
    <div class="box box-solid box-primary">
        <div class="box-header">
            <h3 class="box-title">Mutasi Stock</h3>
            <div class="box-tools pull-right">
                <div class="widget-control pull-left">
                    <span style="display: none; margin-left: 10px;" id="btn-loader-page"><img width="35px" src="<?php print $url?>img/ajax-loader.gif" /></span>
                  </div>
                <button class="btn btn-primary btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
            </div>
             <div class="widget-control pull-left">
              <span style="display: none; margin-left: 10px;" id="loader-page2"><img width="35px" src="<?php print $url?>img/ajax-loader.gif" /></span>
            </div>
        </div>
        <div class="box-body">
          <table class="table table-striped">
           <tr>
              <th >Stock Available</th>
              <td><?php  print $detail[0]->stock_akhir;  ?></td>
            </tr>
            <tr>
              <th >Tanggal Diserahkan</th>
              <td><?php  print $this->form_eksternal->form_input("tgl_diserahkan", $tgl, ' id="tgl_diserahkan" class="form-control date input-sm" placeholder="Tanggal Diserahkan"');  ?></td>
            </tr>
           
            <tr>
              <th>Users</th>
              <td><?php  print $this->form_eksternal->form_input("users", $name, 'id="users" class="form-control input-sm" placeholder="Users"');
                  print $this->form_eksternal->form_input("id_users", $list[0]->id_hr_pegawai, 'id="id_users" style="display: none"'); ?></td>
            </tr>
           
             <tr>
              <th>Jumlah</th>
              <th><?php print $this->form_eksternal->form_input('jumlah', $detail[0]->jumlah, 'min="0" id="dt_jumlah" class="form-control input-sm" placeholder="Jumlah"');?></th>
            </tr>
          
          </table>
        </div>
     
    </div>
  </div>