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
    </div>
  </div>
</div>
<div class="row">
   <div class="col-xs-12">
    <div class="box box-solid box-success">
        <div class="box-header">
            <h3 class="box-title">List Request Pengadaan</h3>
            <div class="box-tools pull-right">
                <div class="widget-control pull-left">
                    <span style="display: none; margin-left: 10px;" id="btn-loader-page"><img width="35px" src="<?php print $url?>img/ajax-loader.gif" /></span>
                  </div>
                <button class="btn" type="submit" id="btn-task-orders">Save Data</button>
                <button class="btn btn-success btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
               
            </div>
            <div class="widget-control pull-left">
              <span style="display: none; margin-left: 10px;" id="loader-page"><img width="35px" src="<?php print $url?>img/ajax-loader.gif" /></span>
            </div>
        </div>
        <div class="box-body">
            <div class="row">
            <div class="form-group">
              <div class="col-xs-6">
                <!--<label>Note ATK</label>-->
                <!--<input type="checkbox" id="checkAll" > Check All-->
    <hr />
                
                <button type='button' class='btn btn-success' data-toggle='modal' data-target='#permintaan-atk1' isi=''>
                      Form Task Orders
              </button>
              </div>
                
            </div>
           
          </div><br>
          <div class="row">
            <div class="box">
              <div class="box-body table-responsive">
                <table class="table table-bordered table-hover" id="tableboxy">
                  <thead>
                    <tr>
                        <th><?php print $this->form_eksternal->form_checkbox('status', $da->id_mrp_request, FALSE,'id="dtcheck"'); ?></th>
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
 <div class="modal fade" id="permintaan-atk1" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Form Task Orders</h4>
            </div>
                <div class="modal-body">
                    <div class="box-body">

                <div class="control-group">
                  <label>Suplier/Title</label>
                  <?php print $this->form_eksternal->form_input('title', $detail[0]->title, 'id="dt_title" class="form-control input-sm" placeholder="Title"');?>
                </div>
                
                <div class="control-group">
                  <label>Note</label>
                  <?php print $this->form_eksternal->form_textarea('note', $detail[0]->note, ' id="dt_note" class="form-control input-sm" id="note_atk"')?>
             
                </div>
              </div>
                </div>
                <div class="modal-footer clearfix">

                    <button type="button" class="btn btn-warning" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>

                </div>
          
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

</div>

<!--<div id='collapseOne' class='panel-collapse collapse'>-->
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
<!--</div>-->
<div id="script-tambahan">
  
</div>
