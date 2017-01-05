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
              <span style="display: none; margin-left: 10px;" id="loader-page4"><img width="35px" src="<?php print $url?>img/ajax-loader.gif" /></span>
            </div>
        </div>
     
<div class="box-body">
    <table class="table table-striped">
    <tr>
      <th >Tanggal Diserahkan</th>
      <td><?php  print $this->form_eksternal->form_input("tgl_diserahkan", $list[0]->tanggal_diserahkan, ' id="tgl_diserahkan" class="form-control date input-sm" placeholder="Tanggal Diserahkan"');  ?></td>
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
      <table class="table table-bordered table-hover" id="tableboxy4">
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
      <?php 
      
      if($list[0]->status != 10){?>
    <td colspan="6">
        
        <button class="btn btn-primary" id="btn-proses">Proses</button>
        <span style="display: none; margin-left: 10px;" id="loader-data4"><img width="35px" src="<?php print $url?>img/ajax-loader.gif" /></span>
    </td>
      <?php } s?>
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
