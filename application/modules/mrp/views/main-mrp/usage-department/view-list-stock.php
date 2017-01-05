<?php
if($this->session->userdata("id") == 1 OR $this->nbscache->get_olahan("permission", $this->session->userdata("id_privilege"), "search-stock-department", "edit") !== FALSE){
?>
<div class="row">
  <div class="col-xs-12">
    <div class="box box-solid box-primary">
        <div class="box-header">
            <h3 class="box-title">Pencarian</h3>
            <div class="box-tools pull-right">
                <button class="btn btn-primary btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
            </div>
        </div>
        <div class="box-body">
          <?php print $this->form_eksternal->form_open("", 'role="form"', array())?>
          <div class="row">
            <div class="form-group">
              <div class="col-xs-6">
                <label>Company</label>
                 <?php print $this->form_eksternal->form_dropdown("id_company", $company, 
                    array($this->session->userdata("stock_dept_search_id_company")), 'id="id_company" class="form-control dropdown2 input-sm"');?>
               
              </div>
              <div class="col-xs-6">
               <span id="dt_direktorat">
                <label>Direktorat</label>
               <?php print $this->form_eksternal->form_dropdown("id_hr_master_organisasi[]", "", 
                        array($detail[0]->id_hr_company_department), 'id="id_direktorat" class="form-control dropdown2 input-sm"');?>
                </span>  
              </div>
            </div>
            <div class="form-group">
              <div class="col-xs-6">
                <span id="dt_department">
               <label>Divisi</label>
                <?php print $this->form_eksternal->form_dropdown("id_hr_master_organisasi[]", "", 
                        array($detail[0]->id_hr_company_department), 'id="id_department" class="form-control dropdown2 input-sm"');?>
                   </span>
              </div>
              <div class="col-xs-6">
                   <span id="dt_divisi">
                <label>Department</label>
                <?php print $this->form_eksternal->form_dropdown("id_hr_master_organisasi[]", "", 
                        array($detail[0]->id_hr_company_department), 'id="id_divisi" class="form-control dropdown2 input-sm"');?>
                   </span>
              </div>
            </div>
              <div class="form-group">
              <div class="col-xs-6">
                  <span id="dt_section">
               <label>Section</label>
                  <?php print $this->form_eksternal->form_dropdown("id_hr_master_organisasi[]", "", 
                        array($detail[0]->id_hr_company_department), 'id="id_section" class="form-control dropdown2 input-sm"');?>
                   </span>
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
    </div>
  </div>
</div>
<?php }  ?>
<div class="row">
   <div class="col-xs-12">
    <div class="box box-solid box-success">
        <div class="box-header">
            <h3 class="box-title">List Barang</h3>
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
                        <th>Nama Barang</th>
                        <th>Satuan</th>
                        <th style="width: 30px;">Available<br>Barang</th>
                        <th style="width: 120px;"></th>
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

<div id='collapseOne' class='panel-collapse collapse'>
<div class="row">
  <div class="col-xs-6">
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
                <table class="table table-bordered table-hover" id="tableboxy1">
                  <thead>
                    <tr>
                        <th>Tanggal Dibuat</th>
                        <th>Nama Barang</th>
                        <th>Satuan</th>
                        <th>Stock Masuk</th>
                        <th>Harga Satuan</th>
                        <th>Tanggal Diterima</th>
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
    <div class="col-xs-6">
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
                <table class="table table-bordered table-hover" id="tableboxy2">
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
</div>
   
</div>

<div id='collapseTwo' class='panel-collapse collapse'>
<div class="row">
  <div class="col-xs-12">
    <div class="box box-solid box-success">
        <div class="box-header">
            <h3 class="box-title">Detail Receiving Goods</h3>
            <div class="box-tools pull-right">
                <button class="btn btn-success btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
                <!--<a href="<?php print site_url("mrp/")?>" title="Create Task Order" class="btn btn-success btn-sm"><i class="fa fa-plus"></i></a>-->
            </div>
            <div class="widget-control pull-left">
              <span style="display: none; margin-left: 10px;" id="loader-page"><img width="35px" src="<?php print $url?>img/ajax-loader.gif" /></span>
            </div>
        </div>
        <div class="row">
            <div class="box">
              <div class="box-body table-responsive">
                <table class="table table-bordered table-hover" id="tableboxy3">
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

<div id="script-tambahan">
  
</div>

