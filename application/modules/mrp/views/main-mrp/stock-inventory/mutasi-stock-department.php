<?php 
//print_r($list);
//die;
?>
<div class="row">
  <div class="col-xs-12">
    <div class="box box-solid box-success">
        <div class="box-header">
            <h3 class="box-title">Mutasi Stock</h3>
            <div class="box-tools pull-right">
                <button class="btn btn-success btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
                <!--<a href="<?php print site_url("mrp/add-request-pengadaan-atk")?>" class="btn btn-success btn-sm"><i class="fa fa-plus"></i></a>-->
            </div>
            <div class="widget-control pull-left">
              <span style="display: none; margin-left: 10px;" id="loader-page"><img width="35px" src="<?php print $url?>img/ajax-loader.gif" /></span>
            </div>
        </div>
     
<div class="box-body">
    <table class="table table-striped">
    <tr>
      <th >Tanggal Diserahkan</th>
      <td><?php  print $this->form_eksternal->form_input("tgl_diserahkan", $list[0]->tanggal_diserahkan, ' id="tgl_diserahkan" class="form-control date input-sm" placeholder="Tanggal Diserahkan"');  ?></td>
    </tr>
    <tr>
      <th>Users</th>
      <td><?php  print $this->form_eksternal->form_input("users", $name, 'id="users" readonly class="form-control input-sm" placeholder="Users"');
          print $this->form_eksternal->form_input("id_users", $list[0]->id_hr_pegawai, 'id="id_users" style="display: none"'); ?></td>
    </tr>
    <tr>
        <th>Note</th>
        <td> <?php print $this->form_eksternal->form_textarea('note', $list[0]->note_mutasi, 'style="margin: 0px; width: 553px; height: 227px;" class="form-control input-sm" id="note_mutasi"')?>
        </td>
    </tr>    
  </table>

    <br><br>        
<div class="row">
  <div class="box">
    <div class="box-body table-responsive">
      <table class="table table-bordered table-hover" id="tableboxy">
    <thead>
      <tr>
          <th style="width: 50px">No.</th>
          <th>Nama Barang</th>
          <th>Jumlah Stock</th>
          <th>Jumlah Request</th>
          <th>Jumlah Dikirim</th>
          <th></th>
      </tr>
    </thead>
    <tbody id="data_list">
    </tbody>
<tfoot>
  <tr>
    <td colspan="6">
        <button class="btn btn-primary" id="btn-save">Proses</button>
        <span style="display: none; margin-left: 10px;" id="loader-data"><img width="35px" src="<?php print $url?>img/ajax-loader.gif" /></span>
    </td>
  </tr>
</tfoot>

      </table>
    </div>
  </div>
</div> 
 </div>
    </div>
  </div>
</div>
<div id="script-tambahan">
  
</div>
<div id="tambah-data">
</div>    
