<?php
if($list[0]->title_spesifik){
    $title_spesifik = " ".$list[0]->title_spesifik;
}else{
    $title_spesifik = "";
}
?>
<div class="row">
   <div class="col-xs-12">
    <div class="box box-solid box-primary">
        <div class="box-header">
            <h3 class="box-title">Detail Stock <?php print $list[0]->nama_barang.$title_spesifik; ?></h3>
            <div class="box-tools pull-right">
                <button class="btn btn-primary btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
            </div>
            <div class="widget-control pull-left">
              <span style="display: none; margin-left: 10px;" id="loader-page"><img width="35px" src="<?php print $url?>img/ajax-loader.gif" /></span>
            </div>
        </div>
        <div class="box-body">
          <div class="row">
            <div class="box">
              <div class="box-body table-responsive">
                <table class="table table-bordered table-hover" id="tableboxy">
                  <thead>
                    <tr>
                        <th>tanggal</th>
                        <th>Kode PO</th>
                        <th>Harga</th>
                        <th style="width: 30px;">Jumlah</th>
                        <!--<th style="width: 120px;"></th>-->
                    </tr>
                  </thead>
                  <tbody id="data_list">
                  </tbody>
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

