<div class="row">
    <div class="col-xs-12">
    <div class="box box-solid box-primary">
        <div class="box-header">
            <h3 class="box-title">Update</h3>
            <div class="box-tools pull-right">
                <button class="btn btn-primary btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
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
                        <th>Nama Barang</th>
                        <th>Satuan</th>
                        <th style="width: 30px;">Jumlah</th>
                        <th>Harga</th>
                        <th>Total</th>
                        <th>Keterangan</th>
                        <th></th>
                    </tr>
                  </thead>
                  <tbody id="data_list">
                    
                  </tbody>
                  <tfoot>
<!--                    <tr>
                      <td colspan="4">
                        <button type='button' class='btn btn-warning btn-flat'>Set Draft</button>
                        <button type='button' class='btn btn-danger btn-flat'>Delete</button>
                      </td>
                      <td><b>Total</b></td>
                      <td><span id="dt-total"></span></td>
                      <td>&nbsp;</td>
                    </tr>-->
                     <tr>
                      <td colspan="4">
                      <?php
                    
                      if($total > 0){?>
                        <button type='button' class='btn btn-warning btn-flat' id="save-data-update">Save</button>
                        <!--<button type='button' class='btn btn-danger btn-flat'>Delete</button>-->
                      <?php } ?>
                      </td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
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

