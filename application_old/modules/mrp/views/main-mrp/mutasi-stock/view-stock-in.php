<div class="row">
  <div class="col-xs-12">
    <div class="box box-solid box-success">
        <div class="box-header">
            <h3 class="box-title">STOCK IN</h3>
            <div class="box-tools pull-right">
                <button class="btn btn-success btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
                
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
                        <th>Tanggal Dibuat</th>
                        <th>Kode<br>Stock IN</th>
                        <th>Nama Barang</th>
                        <th>Satuan</th>
                        <th>Stock Masuk</th>
                        <th>Stock Keluar</th>
                        <th>Harga Satuan</th>
                        <th>Tanggal Diterima</th>
                        <th>Status</th>
                        <th>Kode RG</th>
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
    <div id='collapseTwo' class='panel-collapse collapse'>
    <div class="col-xs-12" id="dt-stock-out" style="display:none">
    <div class="box box-solid box-success">
        <div class="box-header">
            <h3 class="box-title">STOCK OUT</h3>
            <div class="box-tools pull-right">
                <button class="btn btn-success btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
                
            </div>
            <div class="widget-control pull-left">
              <span style="display: none; margin-left: 10px;" id="loader-page"><img width="35px" src="<?php print $url?>img/ajax-loader.gif" /></span>
            </div>
        </div>
      
       
        <div class="box-body">
          <div class="row">
           <div class="box">
              <div class="box-body table-responsive">
                <table class="table table-bordered table-hover" id="tableboxy3">
                  <thead>
                    <tr>
                        <th>Tanggal Dibuat</th>
                        <th>Nama Barang</th>
                        <th>Stock</th>
                        <th>Users</th>
                        <th>Tanggal Diterima</th>
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
        
<div class="col-xs-12" id="dt-rg" style="display:none">
    <div class="box box-solid box-success">
        <div class="box-header">
            <h3 class="box-title">Detail Receiving Goods</h3>
            <div class="box-tools pull-right">
                <button class="btn btn-success btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
            </div>
            <div class="widget-control pull-left">
              <span style="display: none; margin-left: 10px;" id="loader-page"><img width="35px" src="<?php print $url?>img/ajax-loader.gif" /></span>
            </div>
        </div>
        <div class="row">
            <div class="box">
              <div class="box-body table-responsive">
                <table class="table table-bordered table-hover" id="tableboxy4">
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