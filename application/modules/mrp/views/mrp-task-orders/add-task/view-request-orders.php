
<div class="col-xs-6">
    <div class="box box-solid box-success">
        <div class="box-header">
            <h3 class="box-title">Update Task Orders</h3>
            <div class="box-tools pull-right">
                 <?php
                 if($detail[0]->status < 6){
                 ?>
               <button class="btn " type="submit" id="btn-update-to">Save changes</button><span class="pull-right" style="display: none; margin-left: 10px;" id="loader-page-save"><img width="35px" src="<?php print $url?>img/ajax-loader.gif" /></span>
                 <?php } ?> 
               <button class="btn btn-success btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
               
            </div>
            <div class="widget-control pull-left">
              <span style="display: none; margin-left: 10px;" id="loader-page"><img width="35px" src="<?php print $url?>img/ajax-loader.gif" /></span>
            </div>
        </div>
        <div class="box-body">
          <?php print $this->form_eksternal->form_open("", 'role="form"', array())?>
          <div class="row">
            <div class="form-group">
              <div class="col-xs-6">
                <label>Type</label>
                <?php print $this->form_eksternal->form_dropdown('type', $type, 
                  array($this->session->userdata('create_to_search_type')), 'class="form-control dropdown2 input-sm"')?>         
              </div>
                <div class="col-xs-6">
                <label>Perusahaan</label>
                <?php print $this->form_eksternal->form_dropdown('company', $company, 
                  array($this->session->userdata('create_to_search_company')), 'class="form-control dropdown2 input-sm"')?>         
              </div>
            </div>
           
          </div>
          <div class="row">
            <div class="col-xs-3">
              <br />
              <button class="btn btn-primary" type="submit">Search</button>
              <hr />
            </div>
          </div>
          </form> 
        </div>
        <div class="box-body">
          <div class="row">
            <div class="box">
              <div class="box-body table-responsive">
                <table class="table table-bordered table-hover" id="tableboxy">
                  <thead>
                    <tr>
                        <th><?php print $this->form_eksternal->form_checkbox('status', $da->id_mrp_request, FALSE,'id="dtcheck"'); ?></th>
                        <th>Kode<br>Request<br>Orders</th>
                        <th>Pegawai</th>
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
<div class="col-xs-6">
    <div class="box box-solid box-success">
        <div class="box-header">
            <h3 class="box-title">Data Task Orders</h3>
            <div class="box-tools pull-right">
                <?php if($detail[0]->status < 9){?>
                <button class="btn btn-warning btn-sm" id="btn-exchange" title="Exchange"><i class="fa fa-exchange"></i></button>
                <?php } ?>
                <button class="btn btn-success btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
               
            </div>
            <div class="widget-control pull-left">
              <span style="display: none; margin-left: 10px;" id="loader-page2"><img width="35px" src="<?php print $url?>img/ajax-loader.gif" /></span>
            </div>
        </div>
         <div class="box-body">
         
          <div class="row">
            <div class="form-group">
              <div class="col-xs-6">
                <label>No PO</label>
                <?php print $this->form_eksternal->form_dropdown('no_po', $no_po, 
                  array($this->session->userdata("add_to_search_no_po_{$id_mrp_task_orders}")), 'class="form-control dropdown2 no_po input-sm"')?>         
              </div>
               
            </div>
           
          </div>
         
        </div>
        <div class="box-body">
          <div class="row">
            <div class="box">
              <div class="box-body table-responsive">
                <table class="table table-bordered table-hover" id="tableboxy2">
                  <thead>
                    <tr>
                        <th>Kode<br>Request<br>Orders</th>
                        <th>Pegawai</th>
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
                        <th>Jumlah RG</th>
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
<!--<div id='collapseTwo' class='panel-collapse collapse'>
<div class="row">
  <div class="col-xs-12">
    <div class="box box-solid box-success">
        <div class="box-header">
            <h3 class="box-title">Detail Request Pengadaan</h3>
            <div class="box-tools pull-right">
                <button class="btn btn-success btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
                <a href="<?php print site_url("mrp/add-request-pengadaan-atk")?>" class="btn btn-success btn-sm"><i class="fa fa-plus"></i></a>
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
                        <th>Action</th>
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
                        <th>Jumlah RG</th>
                        <th>Action</th>
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
</div>-->
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