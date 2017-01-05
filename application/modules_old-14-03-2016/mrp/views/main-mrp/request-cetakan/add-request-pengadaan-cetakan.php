
<div class="row">
  <div class="col-xs-12">
    <div class="box box-solid box-success">
        <div class="box-header">
            <h3 class="box-title">Form Permintaan Pengadaan Cetakan</h3>
            <div class="box-tools pull-right">
                <button class="btn btn-success btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
                <!--<a href="<?php print site_url("mrp/add-request-pengadaan-cetakan")?>" class="btn btn-success btn-sm"><i class="fa fa-plus"></i></a>-->
            </div>
            <div class="widget-control pull-left">
              <span style="display: none; margin-left: 10px;" id="loader-page2"><img width="35px" src="<?php print $url?>img/ajax-loader.gif" /></span>
            </div>
        </div>
      
        <div class="box-body">
          <div class="row">
            <div class="box">
              <div class="box-body table-responsive">
                <table class="table table-bordered table-hover" id="tableboxy2">
                  <thead>
                    <tr>
                        <th style="width: 50px">No.</th>
                        <th>Nama Barang</th>
                        <th>Satuan</th>
                        <th style="width: 150px">Jumlah</th>
                        <!--<th>Action</th>-->
                    </tr>
                  </thead>
                  <tbody id="data_list">
                    
                  </tbody>
                  
                  <tfoot>
                  
                    <tr>
                      <td colspan="4">
                          <button class="btn btn-primary" id="btn-save2">Save</button>
                          <img src='<?php print $img; ?>' style='display:none' id='img-cetakan' alt=''>
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
