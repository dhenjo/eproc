<div class="col-xs-12">
    <div class="box box-solid box-primary">
        <div class="box-header">
            <h3 class="box-title">Pemakaian Asset <?php print $name; ?></h3>
            <div class="box-tools pull-right">
                <div class="widget-control pull-left">
                    <span style="display: none; margin-left: 10px;" id="btn-loader-page"><img width="35px" src="<?php print $url?>img/ajax-loader.gif" /></span>
                  </div>
                <?php if($id_hr_master_organisasi === $this->session->userdata("stock_dept_search_id_hr_master")){?>
               <button class="btn" type="submit" id="btn-pemakaian">Save changes</button>
                <?php }?>
               <td><button type='button' class='btn btn-info tour-edit'  data-toggle='modal' data-target='#compose-modal'>Detail</button>
               <td><button type='button' class='btn btn-info tour-edit'  data-toggle='modal' data-target='#compose-modal'>Detail2</button> 
               <button class="btn btn-primary btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
            </div>
             <div class="widget-control pull-left">
              <span style="display: none; margin-left: 10px;" id="loader-page2"><img width="35px" src="<?php print $url?>img/ajax-loader.gif" /></span>
            </div>
        </div>
        <div class="box-body">
          <table class="table table-striped">
           <tr>
              <th >Stock Available</th>
              <td style="width: 80%;"><?php  print $jumlah;  ?></td>
            </tr>
            <tr>
              <th >Tanggal Pemakaian</th>
              <td> <?php if($id_hr_master_organisasi === $this->session->userdata("stock_dept_search_id_hr_master")){?>
                  <?php  print $this->form_eksternal->form_input("tanggal", $tgl, ' id="tgl_pemakaian" class="form-control date input-sm" placeholder="Tanggal Pemakaian"');  ?>
              <?php } ?>
              </td>
            </tr>
             <tr>
              <th>Jumlah</th>
              <th> <?php if($id_hr_master_organisasi === $this->session->userdata("stock_dept_search_id_hr_master")){?>
                  <?php print $this->form_eksternal->form_input('jumlah', $detail[0]->jumlah, 'min="0" id="dt_jumlah" class="form-control input-sm" placeholder="Jumlah"');?></th>
              <?php }?>
             </tr>
            <tr>
              <th>Note</th>
              <th> <?php if($id_hr_master_organisasi === $this->session->userdata("stock_dept_search_id_hr_master")){?>
                  <?php print $this->form_eksternal->form_textarea('note', $detail[0]->note, 'class="form-control input-sm" id="note"')?>
              <?php } ?>
              </th>
            </tr>
          </table>
        </div>
     
    </div>
  </div>
<div class="modal fade" id="compose-modal2" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Customer Detail</h4>
            </div>
            <form action="<?php print site_url("store/cancel-tour-per-pax")?>" method="post">
                <div class="modal-body">
                  
                    <div class="col-md-12">
                      <div class="control-group">
                      <label>Name</label>
                      <?php print $this->form_eksternal->form_input('name', "", 'id="name"  class="form-control input-sm" placeholder="Name"');?>
                      </div>
                      </div>
                    
                    <div class="col-md-4">
                     <div class="control-group">
                      <label>No Telp</label>
                      <?php print $this->form_eksternal->form_input('telp',"", 'id="telp" class="form-control input-sm"  id="ano_telp_pemesan" placeholder="No Telp"');?>
                      </div>
                      </div>
                     <div class="col-md-4">
                      <div class="control-group">
                      <label>Place Of Birth</label>
                       <?php print $this->form_eksternal->form_input('place_birth', "", 'id="tmpt_tgl_lahir"  class="form-control input-sm" placeholder="Place Of Birth"');?>
                      </div>
                       </div>
                   
                    <div class="col-md-4">
                      <div class="control-group">
                      <label>Date Of Birth</label>
                      <?php print $this->form_eksternal->form_input('date', "", 'id="tgl_lahir" class="form-control input-sm adult_date" placeholder="Date Of Birth"');?>
                       </div>
                     </div>
                    
                    <div class="col-md-6">
                      <div class="control-group">
                      <label>No Passport</label>
                      <?php print $this->form_eksternal->form_input('passport', "", 'id="passport" class="form-control input-sm" placeholder="No Passport"');?>
                     </div>
                    </div>
                    <div class="col-md-6">
                    <div class="control-group">
                      <label>Place Of Issued</label>
                      <?php print $this->form_eksternal->form_input('place_issued', "", 'id="place_issued" class="form-control input-sm" placeholder="Place Of Issue"');?>
                     </div>
                      </div>
                   
                    <div class="col-md-6">
                    <div class="control-group">
                      <label>Date Of Issued</label>
                      <?php print $this->form_eksternal->form_input('date_issued', "", 'id="date_issued" class="form-control input-sm " placeholder="Date Of Issued"');?>
                      </div>
                      </div>
                   
                      <div class="col-md-6">
                    <div class="control-group">
                      <label>Date Of Expired</label>
                      <?php print $this->form_eksternal->form_input('date_expired', "", 'id="date_expired" class="form-control input-sm " placeholder="Date Of Expired"');?>
                    
                    </div><br>
                        </div>
                   <div class="col-md-12">
                      <div class="control-group">
                      <label>Type</label>
                      <?php print $this->form_eksternal->form_input('type', "", 'id="type" class="form-control  input-sm" placeholder="Type"');?>
                      </div><br>
                      </div>
                   
                </div>
                <div class="modal-footer clearfix">

                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</button>

                    <button type="submit" class="btn btn-primary pull-left"> Submit</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<div class="modal fade" id="compose-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Customer Detail</h4>
            </div>
            <form action="<?php print site_url("store/cancel-tour-per-pax")?>" method="post">
                <div class="modal-body">
                  
                    <div class="col-md-12">
                      <div class="control-group">
                      <label>Name</label>
                      <?php print $this->form_eksternal->form_input('name', "", 'id="name"  class="form-control input-sm" placeholder="Name"');?>
                      </div>
                      </div>
                    
                    <div class="col-md-4">
                     <div class="control-group">
                      <label>No Telp</label>
                      <?php print $this->form_eksternal->form_input('telp',"", 'id="telp" class="form-control input-sm"  id="ano_telp_pemesan" placeholder="No Telp"');?>
                      </div>
                      </div>
                     <div class="col-md-4">
                      <div class="control-group">
                      <label>Place Of Birth</label>
                       <?php print $this->form_eksternal->form_input('place_birth', "", 'id="tmpt_tgl_lahir"  class="form-control input-sm" placeholder="Place Of Birth"');?>
                      </div>
                       </div>
                   
                    <div class="col-md-4">
                      <div class="control-group">
                      <label>Date Of Birth</label>
                      <?php print $this->form_eksternal->form_input('date', "", 'id="tgl_lahir" class="form-control input-sm adult_date" placeholder="Date Of Birth"');?>
                       </div>
                     </div>
                    
                    <div class="col-md-6">
                      <div class="control-group">
                      <label>No Passport</label>
                      <?php print $this->form_eksternal->form_input('passport', "", 'id="passport" class="form-control input-sm" placeholder="No Passport"');?>
                     </div>
                    </div>
                    <div class="col-md-6">
                    <div class="control-group">
                      <label>Place Of Issued</label>
                      <?php print $this->form_eksternal->form_input('place_issued', "", 'id="place_issued" class="form-control input-sm" placeholder="Place Of Issue"');?>
                     </div>
                      </div>
                   
                    <div class="col-md-6">
                    <div class="control-group">
                      <label>Date Of Issued</label>
                      <?php print $this->form_eksternal->form_input('date_issued', "", 'id="date_issued" class="form-control input-sm " placeholder="Date Of Issued"');?>
                      </div>
                      </div>
                   
                      <div class="col-md-6">
                    <div class="control-group">
                      <label>Date Of Expired</label>
                      <?php print $this->form_eksternal->form_input('date_expired', "", 'id="date_expired" class="form-control input-sm " placeholder="Date Of Expired"');?>
                    
                    </div><br>
                        </div>
                   <div class="col-md-12">
                      <div class="control-group">
                      <label>Type</label>
                      <?php print $this->form_eksternal->form_input('type', "", 'id="type" class="form-control  input-sm" placeholder="Type"');?>
                      </div><br>
                      </div>
                   
                </div>
                <div class="modal-footer clearfix">

                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</button>

                    <button type="submit" class="btn btn-primary pull-left"> Submit</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>