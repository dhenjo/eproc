<div class="col-xs-12">
    <div class="box box-solid box-success">
        <div class="box-header">
            <h3 class="box-title">List Purchase Orders</h3>
            <div class="box-tools pull-right">
                <button class="btn btn-success btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
                <a href="<?php print site_url("mrp/mrp-po/po/{$id_mrp_task_orders}"); ?>" type='button' class='btn bg-black btn-flat' title='Create Purchase Orders' style='width: 40px'><i class='fa fa-shopping-cart'></i></a>
                <!--<a href="<?php print site_url("mrp/add-task-orders")?>" title="Create Task Order" class="btn btn-success btn-sm"><i class="fa fa-plus"></i></a>-->
            </div>
            <div class="widget-control pull-left">
              <span style="display: none; margin-left: 10px;" id="loader-page5"><img width="35px" src="<?php print $url?>img/ajax-loader.gif" /></span>
            </div>
        </div>
        <div class="box-body">
          <div class="row">
            <div class="box">
              <div class="box-body table-responsive">
                <table class="table table-bordered table-hover" id="tableboxy5">
                  <thead>
                    <tr>
                        <th style="width: 15px">No. PO</th>
                        <th>Kode<br>PO</th>
                        <th>Kode<br>Task<br>Orders</th>
                        <th>Supplier</th>
                        <th>Tanggal Dikirim</th>
                        <th>Status</th>
                        <th>Action</th>
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
 <div id='collapsePO' class='panel-collapse collapse'>
<div class="row">
  <div class="col-xs-12">
    <div class="box box-solid box-primary">
        <div class="box-header">
            <h3 class="box-title">List Purchase Orders</h3>
            <div class="box-tools pull-right">
                <button class="btn btn-primary btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
            </div>
            <div class="widget-control pull-left">
              <span style="display: none; margin-left: 10px;" id="loader-page12"><img width="35px" src="<?php print $url?>img/ajax-loader.gif" /></span>
            </div>
        </div>
        <div class="box-body">
          <div class="row">
            <div class="box">
              <div class="box-body table-responsive">
                <table class="table table-bordered table-hover" id="tableboxy12">
                  <thead>
                    <tr>
                        <th>Nama Barang</th>
                        <th>Satuan</th>
                        <th style="width: 30px;">Jumlah</th>
                        <th>Harga</th>
                        <th>Total</th>
                        <th>Keterangan</th>
                    </tr>
                  </thead>
                  <tbody id="data_list">
                    
                  </tbody>
                  <tfoot>
                         <tr>
                      <td colspan="3">
<!--                        <button type='button' class='btn btn-warning btn-flat' id="btn-proses">Proses Pengajuan PO</button>
                        <button type='button' class='btn btn-info btn-flat' id="btn-save">Save</button>-->
                        <!--<button type='button' class='btn btn-danger btn-flat'>Delete</button>-->
                      </td>
                      <td><b>Total</b></td>
                      <td><span id="dt-total"></span></td>
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
</div>