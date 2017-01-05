<div class="row">    
<div class="col-xs-12">
    <div class="box box-solid box-success">
        <div class="box-header">
            <h3 class="box-title">History Stock <?php print $name; ?></h3>
            <div class="box-tools pull-right">
                <button class="btn btn-success btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
                
            </div>
            <div class="widget-control pull-left">
              <span style="display: none; margin-left: 10px;" id="loader-page3"><img width="35px" src="<?php print $url?>img/ajax-loader.gif" /></span>
            </div>
        </div>
      
        <div class="box-body">
          <div class="row">
           <div class="box">
              <div class="box-body table-responsive">
                <table class="table table-bordered table-hover" id="tableboxy6">
                  <thead>
                    <tr>
                        <th style="width: 95px;">Tanggal</th>
                        <th>Nama Barang</th>
                        <th style="width: 10%">Jumlah</th>
                        <th style="width: 10%">Status</th>
                        <th>Note</th>
                        <th>Created<br>Date</th>
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