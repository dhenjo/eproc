<?php
//print $detail[0]->status;
//die;
if($id_mrp_task_orders){
?>
  <div class="col-xs-12">
    <div class="box box-solid box-primary">
        <div class="box-header">
            <h3 class="box-title">List Task Orders</h3>
            <div class="box-tools pull-right">
                <div class="widget-control pull-left">
                    <span style="display: none; margin-left: 10px;" id="btn-loader-page"><img width="35px" src="<?php print $url?>img/ajax-loader.gif" /></span>
                  </div>
               <?php if($detail[0]->status < 9 ){ ?>
                <button class="btn btn-danger btn-flat" id="btn-cancel-to" type="submit">Cancel TO</button>
               <?php } ?>
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
              <th>Suplier/Title</th>
              <th><?php print $this->form_eksternal->form_input('title', $detail[0]->title, 'id="dt_title" class="form-control input-sm" placeholder="Title"');?></th>
            </tr>
            <tr>
              <th>Note</th>
              <td><?php print $this->form_eksternal->form_textarea('note', $detail[0]->note, ' id="dt_note" class="form-control input-sm" id="note_atk"')?></td>
            </tr>
            <?php if($detail[0]->status <= 8){?>
            <tr>
              <th></th>
              <td><button class="btn btn-primary" type="submit" id="btn-task-orders">Save changes</button>
               <?php if($detail[0]->status == 4){ ?>
              <div style="padding-left:40%;margin-top: -4% "><button class="btn btn-warning" type="submit" id="btn-closed-to">Closed Task Orders</button></div></td>
               <?php } ?>
            </tr>
            <?php } ?>
          </table>
        </div>
        <?php // $id_dt =$this->global_models->get_field("mrp_task_orders_request", "SUM(id_mrp_task_orders_request)", array("id_mrp_task_orders" => $id_mrp_task_orders));
//        if($id_dt > 0){
        ?>
 
        <?php // } ?>
    </div>
  </div>

     <div class="col-xs-6">
        <?php // $id_dt =$this->global_models->get_field("mrp_task_orders_request", "SUM(id_mrp_task_orders_request)", array("id_mrp_task_orders" => $id_mrp_task_orders));
//        if($id_dt > 0){
        ?>
    <div class="box box-solid box-primary">
        <div class="box-header">
            <!--<h3 class="box-title">Task Orders</h3>-->
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
                        <th style="width: 70px;">Total RO</th>
                        <th style="width: 70px;">Total PO</th>
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
    
<?php } ?>

<!--<div id='collapse_task_orders' class='panel-collapse collapse'>-->
<div class="row">
  <div class="col-xs-6">
    <div class="box box-solid box-success">
        <div class="box-header">
            <h3 class="box-title">Users Request</h3>
            <div class="box-tools pull-right">
                <button class="btn btn-success btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
                <!--<a href="<?php print site_url("mrp/add-request-pengadaan-atk")?>" class="btn btn-success btn-sm"><i class="fa fa-plus"></i></a>-->
            </div>
            <div class="widget-control pull-left">
              <span style="display: none; margin-left: 10px;" id="users-request-task-orders10"><img width="35px" src="<?php print $url?>img/ajax-loader.gif" /></span>
            </div>
        </div>
      
       
        <div class="box-body">
            <div class="box">
              <div class="box-body table-responsive">
              <table class="table table-bordered table-hover" id="users-request-task-orders">
                  <thead>
                    <tr>
                        <th>Users</th>
                        <th>Jumlah</th>
                        <th>Created<br>By Users</th>
                        <th>Code<br>RO</th>
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
<!--</div>-->