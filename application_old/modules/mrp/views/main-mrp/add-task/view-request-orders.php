<div class="col-xs-12">
    <div class="box box-solid box-success">
        <div class="box-header">
            <h3 class="box-title">List Request Pengadaan</h3>
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
                        <th></th>
                        <th>Kode<br>Request<br>Orders</th>
                        <th>Type</th>
                        <th>Pegawai</th>
                        <th>Perusahaan<br>Department</th>
                        <th>Note</th>
                        <th>Status</th>
                    </tr>
                  </thead>
                  <tbody id="data_list">
                    
                  </tbody>
<!--                  <tfoot>
                    <tr>
                      <td colspan="3">
                        <button type='button' class='btn btn-warning btn-flat'>Set Draft</button>
                        <button type='button' class='btn btn-danger btn-flat'>Delete</button>
                      </td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      
                    </tr>
                  </tfoot>-->
                </table>
              </div>
            </div>
          </div> 
        </div>
    </div>
  </div>
 <div id='collapseOne' class='panel-collapse collapse'>
<div class="row">
  <div class="col-xs-12">
    <div class="box box-solid box-success">
        <div class="box-header">
            <h3 class="box-title">Detail Request Pengadaan</h3>
            <div class="box-tools pull-right">
                <button class="btn btn-success btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
                <!--<a href="<?php print site_url("mrp/add-request-pengadaan-atk")?>" class="btn btn-success btn-sm"><i class="fa fa-plus"></i></a>-->
            </div>
            <div class="widget-control pull-left">
              <span style="display: none; margin-left: 10px;" id="loader-page"><img width="35px" src="<?php print $url?>img/ajax-loader.gif" /></span>
            </div>
        </div>
      
       
        <div class="box-body">
          
          <div class="row" id="Atableboxy1" style="display:none">
            <div class="box">
              <div class="box-body table-responsive">
                   <table class="table table-bordered table-hover" id="tableboxy-req1">
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
                </table>
               
                 
              </div>
            </div>
          </div>
            
          <div class="row" id="Atableboxy2" style="display:none">
            <div class="box">
              <div class="box-body table-responsive">
              <table class="table table-bordered table-hover" id="tableboxy-req2">
                  <thead>
                    <tr>
                        <th style="width: 50px">No.</th>
                        <th>Nama Barang</th>
                        <th>Merk</th>
                        <th>Satuan</th>
                        <th style="width: 150px">Jumlah</th>
                        <!--<th>Action</th>-->
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
</div>