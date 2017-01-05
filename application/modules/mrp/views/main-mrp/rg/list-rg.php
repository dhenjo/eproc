<div class="row">
   <div class="col-xs-12">
    <div class="box box-solid box-primary">
        <div class="box-header">
            <h3 class="box-title">Receiving Goods</h3>
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
              <div class="control-group">
               <label>Tanggal Diterima</label>    
              <?php print $this->form_eksternal->form_input("tgl_dikirim", $tgl, ' id="tgl_diterima" class="form-control date input-sm" placeholder="Tanggal Diterima"'); ?>
             </div>
             <div class="control-group">
               <label>Note</label>
                            <?php print $this->form_eksternal->form_textarea('note', $detail_note, ' class="form-control input-sm" id="note"')?>
             </div>
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
                        <th style="width: 30px;">Jumlah</th>
                        <th>Keterangan</th>
                        <th>Jumlah<br>Yang Diterima</th>
                        <th>Jumlah<br>Yang Kurang</th>
                        <th style="width: 120px;"></th>
                    </tr>
                  </thead>
                  <tbody id="data_list">
                  </tbody>
                  <tfoot>
                    <tr>
                      <td colspan="3">
                          <span style="display: none; margin-left: 10px;" id="loader-page2"><img width="35px" src="<?php print $url?>img/ajax-loader.gif" /></span>
                        <button type='button' class='btn btn-info btn-flat' id="btn-save">Save</button>
                        <!--<button type='button' class='btn btn-danger btn-flat'>Delete</button>-->
                      </td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                    </tr>
                  </tfoot>
                </table>
            <div class="box-body" style="display: none;" id="dta-bar">
                <img width="35px" src="<?php print $url?>img/ajax-loader.gif" /> Wait Proses<div style="padding-left:15%;margin-top: -3%" id="no_proses">1</div>%
                <div class="progress progress-striped active">
                    <div id="bar_proses" class="progress-bar progress-bar-primary" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: 1%; background-color: #1a6ea5">
                        Progress <span id="show-bars">1</span>%
                    </div>
                </div>
        </div>
              </div>
            </div>
          </div> 
        </div>
    </div>
       
  </div>
</div>
<div id="script-tambahan">
  
</div>
<div class="row">
  <div class="col-xs-12">
    <div class="box box-solid box-success">
        <div class="box-header">
            <h3 class="box-title">RG Department</h3>
            <div class="box-tools pull-right">
                <button class="btn btn-success btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
                <!--<a href="<?php print site_url("mrp/add-request-pengadaan-atk")?>" class="btn btn-success btn-sm"><i class="fa fa-plus"></i></a>-->
            </div>
            <div class="widget-control pull-left">
              <span style="display: none; margin-left: 10px;" id="load-rg-department"><img width="35px" src="<?php print $url?>img/ajax-loader.gif" /></span>
            </div>
        </div>
      
       
        <div class="box-body">
            <div class="box">
              <div class="box-body table-responsive">
              <table class="table table-bordered table-hover" id="rg-department">
                  <thead>
                    <tr>
                        <th>Users Request</th>
                        <th>Nama Barang</th>
                        <th>Satuan</th>
                        <th>Jumlah</th>
                        <th>Jumlah <br>Yang Diterima</th>
                        <th>Jumlah <br>Yang Kurang</th>
                        <th>Created<br>By Users</th>
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

