<div class="row">
<div class="col-xs-12">
    <div class="box box-solid box-success">
        <div class="box-header">
            <h3 class="box-title">List Request Pengadaan</h3>
            <div class="box-tools pull-right">
                <button class="btn" type="submit" id="btn-closed-to">Closed Task Orders</button>
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
<?php
if($id_mrp_task_orders){
?>
  <div class="col-xs-12">
    <div class="box box-solid box-primary">
        <div class="box-header">
            <h3 class="box-title">List Task Orders</h3>
            <div class="box-tools pull-right">
                <button class="btn" type="submit" id="btn-task-orders">Save changes</button>
                <div class="widget-control pull-left">
                    <span style="display: none; margin-left: 10px;" id="btn-loader-page"><img width="35px" src="<?php print $url?>img/ajax-loader.gif" /></span>
                  </div>
                <button class="btn btn-primary btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
            </div>
             <div class="widget-control pull-left">
              <span style="display: none; margin-left: 10px;" id="loader-page2"><img width="35px" src="<?php print $url?>img/ajax-loader.gif" /></span>
            </div>
        </div>
        <div class="box-body">
          <table class="table table-striped">
            <tr>
              <th>Kode</th>
              <td><?php print $detail[0]->code; ?></td>
            </tr>
            <tr>
              <th>Status</th>
              <td><?php print $dt_status[$detail[0]->status]; ?></td>
            </tr>
             <tr>
              <th>Title</th>
              <th><?php print $this->form_eksternal->form_input('title', $detail[0]->title, 'id="dt_title" class="form-control input-sm" placeholder="Title"');?></th>
            </tr>
            <tr>
              <th>Note</th>
              <td><?php print $this->form_eksternal->form_textarea('note', $detail[0]->note, ' id="dt_note" class="form-control input-sm" id="note_atk"')?></td>
            </tr>
           
          </table>
        </div>
        <?php // $id_dt =$this->global_models->get_field("mrp_task_orders_request", "SUM(id_mrp_task_orders_request)", array("id_mrp_task_orders" => $id_mrp_task_orders));
//        if($id_dt > 0){
        ?>
        <div class="box-body">
          <div class="row">
            <div class="box">
              <div class="box-body table-responsive">
                <table class="table table-bordered table-hover" id="tableboxy2">
                  <thead>
                    <tr>
                        <th>Kode<br>Request<br>Orders</th>
                        <th>Type</th>
                        <th>Pegawai</th>
                        <th>Perusahaan<br>Department</th>
                        <th>Note</th>
                        <th>Status</th>
                        <th></th>
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
        <?php // } ?>
    </div>
  </div>
<div id='collapseTwo' class='panel-collapse collapse'>
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
          
          <div class="row" id="Atableboxy-data1" style="display:none">
            <div class="box">
              <div class="box-body table-responsive">
                   <table class="table table-bordered table-hover" id="tableboxy-req-data-1">
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
            
          <div class="row" id="Atableboxy-data2" style="display:none">
            <div class="box">
              <div class="box-body table-responsive">
              <table class="table table-bordered table-hover" id="tableboxy-req-data-2">
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
     <div class="col-xs-6">
        <?php // $id_dt =$this->global_models->get_field("mrp_task_orders_request", "SUM(id_mrp_task_orders_request)", array("id_mrp_task_orders" => $id_mrp_task_orders));
//        if($id_dt > 0){
        ?>
    <div class="box box-solid box-primary">
        <div class="box-header">
            <h3 class="box-title">List Task Request Orders</h3>
            <div class="box-tools pull-right">
                <button class="btn btn-primary btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
            </div>
             <div class="widget-control pull-left">
              <span style="display: none; margin-left: 10px;" id="loader-page3"><img width="35px" src="<?php print $url?>img/ajax-loader.gif" /></span>
            </div>
        </div>
        <div class="box-body">
          <div class="row">
            <div class="box">
              <div class="box-body table-responsive">
                <table class="table table-bordered table-hover" id="tableboxy3">
                  <thead>
                    <tr>
                        <th>Nama Barang</th>
                        <th>Merk</th>
                        <th>Satuan</th>
                        <th>Jumlah</th>
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
         <?php // } ?>
  </div>
    
     <div class="col-xs-6">
      
    <div class="box box-solid box-primary">
        <div class="box-header">
            <h3 class="box-title">List Purchase Orders</h3>
            <div class="box-tools pull-right">
                <button class="btn btn-primary btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
            </div>
             <div class="widget-control pull-left">
              <span style="display: none; margin-left: 10px;" id="loader-page4"><img width="35px" src="<?php print $url?>img/ajax-loader.gif" /></span>
            </div>
        </div>
        <div class="box-body">
          <div class="row">
            <div class="box">
              <div class="box-body table-responsive">
                <table class="table table-bordered table-hover" id="tableboxy4">
                  <thead>
                    <tr>
                        <th>Nama Barang</th>
                        <th>Merk</th>
                        <th>Satuan</th>
                        <th>Jumlah</th>
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
<?php } ?>
</div>
<div id="script-tambahan">
  
</div>

