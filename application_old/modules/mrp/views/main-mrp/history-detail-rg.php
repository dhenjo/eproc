<div class="row">
   <div class="col-xs-12">
    <div class="box box-solid box-primary">
        <div class="box-header">
            <h3 class="box-title">History Detail Receiving Goods</h3>
            <div class="box-tools pull-right">
                <button class="btn btn-primary btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
            </div>
            <div class="widget-control pull-left">
              <span style="display: none; margin-left: 10px;" id="loader-page"><img width="35px" src="<?php print $url?>img/ajax-loader.gif" /></span>
            </div>
        </div>
        <div class="box-body">
           
            <div class="row">
                 <div class="col-md-12">
            <div class="box-body">
          <table class="table table-striped">
             <tr>
                 <th style="width: 22%">Kode PO</th>
              <td><?php print "<a href='".site_url("mrp/detail-po/{$id_mrp_task_orders}/{$list[0]->id_mrp_po}")."'>{$code_po}</a>"; ?></td>
            </tr>

            <?php
            $tgl = "";
            if($detail[0]->tanggal_dikirim != "0000-00-00" AND $list[0]->tanggal_diterima != ""){
            $tgl = date("d M Y", strtotime($list[0]->tanggal_diterima));
          }
            ?>
            <tr>
              <th>Tanggal Diterima</th>
              <td><?php print $tgl; ?></td>
            </tr>
            <tr>
              <th>Note</th>
              <td><?php print nl2br($list[0]->note); ?></td>
            </tr>
            
          </table>
        </div>
          </div>
                </div>
          <div class="row">
            <div class="box">
              <div class="box-body table-responsive">
                <table class="table table-bordered table-hover" id="tableboxy">
                  <thead>
                    <tr>
                        <th>Nama Barang</th>
                        <th>Satuan</th>
                        <th>Jumlah Yang Diterima</th>
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

<div id='collapseOne' class='panel-collapse collapse'><div class='row'>

</div> 
