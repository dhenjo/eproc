<?php
//print $this->nbscache->get_olahan("permission", $this->session->userdata("id_privilege"), "edit-form-ro", "edit");
//die;
?>
<div class="row">
  <div class="col-xs-12">
    <div class="box box-solid box-success">
        <div class="box-header">
            <h3 class="box-title">Form Lock ATK</h3>
            <div class="box-tools pull-right">
                <button class="btn " id="btn-task-orders">Save Data</button>
                <button class="btn btn-success btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
                <!--<a href="<?php print site_url("mrp/add-request-pengadaan-atk")?>" class="btn btn-success btn-sm"><i class="fa fa-plus"></i></a>-->
            </div>
            <div class="widget-control pull-left">
              <span style="display: none; margin-left: 10px;" id="loader-page"><img width="35px" src="<?php print $url?>img/ajax-loader.gif" /></span>
            </div>
        </div>
      
<!--        <div class="modal fade" id="permintaan-atk1" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                 
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Note ATK</h4>
            </div>
                <div class="modal-body">
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-addon">Note Cancel:</span>
                       
                            <?php print $this->form_eksternal->form_textarea('note', $list[0]->note, 'style="margin: 0px; width: 553px; height: 227px;" class="form-control input-sm" id="note_atk"')?>
             
                            <textarea name="note_cancel" placeholder="Note Cancel" style="margin: 0px; width: 553px; height: 227px;"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer clearfix">

                    <button type="button" class="btn btn-warning" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>

                </div>
          
        </div> /.modal-content 
    </div> /.modal-dialog 
</div>-->
        <div class="box-body">
            <div class="row">
            <div class="form-group">
              <div class="col-xs-12">
                <!--<label>Note ATK</label>-->
                
<!--                <button type='button' class='btn btn-success' data-toggle='modal' data-target='#permintaan-atk1' isi=''>
                      Note ATK
              </button>-->
              </div>
                 <div class="col-xs-12">
                  <label>Company</label>
                  <?php print $this->form_eksternal->form_dropdown("id_company", $company, 
                    array($detail[0]->id_hr_company), 'id="id_company" class="form-control dropdown3 input-sm"');?>
                </div>
                <span id="dt_direktorat" class="col-xs-12">
                    <div class="col-xs-12">
                      <label>Direktorat</label>
                     <?php print $this->form_eksternal->form_dropdown("id_hr_master_organisasi[]", "", 
                        array($detail[0]->id_hr_company_department), 'id="id_direktorat" class="form-control dropdown2 input-sm"');?>
                    </div>
                </span>
                 <span id="dt_department" class="col-xs-12">
                     <div class="col-xs-12">
                      <label>Department</label>
                     <?php print $this->form_eksternal->form_dropdown("id_hr_master_organisasi[]", "", 
                        array($detail[0]->id_hr_company_department), 'id="id_department" class="form-control dropdown2 input-sm"');?>
                    </div>
                </span>
                 <span id="dt_divisi" class="col-xs-12">
                     <div class="col-xs-12">
                      <label>Divisi</label>
                     <?php print $this->form_eksternal->form_dropdown("id_hr_master_organisasi[]", "", 
                        array($detail[0]->id_hr_company_department), 'id="id_divisi" class="form-control dropdown2 input-sm"');?>
                    </div>
                </span>
                <span id="dt_section" class="col-xs-12">
                    <div class="col-xs-12">
                      <label>Section</label>
                     <?php print $this->form_eksternal->form_dropdown("id_hr_master_organisasi[]", "", 
                        array($detail[0]->id_hr_company_department), 'id="id_section" class="form-control dropdown2 input-sm"');?>
                    </div>
                </span>
                <div class="col-xs-12">
                      <label>Note</label>
                     <?php print $this->form_eksternal->form_textarea('note', $detail[0]->note, 'style="margin: 0px;" class="form-control input-sm" id="note_atk"');?>
                    </div>
            </div>
                <br><br>
         
          </div>
            <br><br>
            
          <div class="row">
            <div class="box">
              <div class="box-body table-responsive">
                <table class="table table-bordered table-hover" id="tableboxy">
                  <thead>
                    <tr>
                        <th style="width: 50px">No.</th>
                        <th>Nama Barang</th>
                        <th>Merk</th>
                        <th>Satuan</th>
                        <th style="width: 150px"></th>
                        <!--<th>Action</th>-->
                    </tr>
                  </thead>
                  <tbody id="data_list">
                    
                  </tbody>
                  
                  <tfoot>
                   
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
