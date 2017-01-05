
<style>
 .MonthDatePicker .ui-datepicker-year
{
    display:none;   
}
.HideTodayButton .ui-datepicker-buttonpane .ui-datepicker-current
{
    visibility:hidden;
}

.hide-calendar .ui-datepicker-calendar
{
	display:none!important;
	visibility:hidden!important
}
    
</style>    
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
                    array($this->session->userdata("report_po_search_id_company")), 'id="id_company" class="form-control dropdown2 input-sm"');?>
               
            </div>
            <div class="col-xs-6">
               <label>Years</label>
        <?php print $this->form_eksternal->form_input('years', $this->session->userdata("report_po_search_year"), 'id="tgl_lahir" class="form-control input-sm date_years" placeholder="Years"');?>
              </div>   
            </div>
              
               <div class="form-group">
              <div class="col-xs-6">
               <label>Start Month</label>
                  <?php print $this->form_eksternal->form_dropdown("start_month", $month, 
                        $this->session->userdata("report_po_search_start_month"), ' class="form-control dropdown2 input-sm"');?>
                  </div>
               <div class="col-xs-6">

               <label>End Month</label>
        <?php print $this->form_eksternal->form_dropdown("end_month", $month, 
                        $this->session->userdata("report_po_search_end_month"), ' class="form-control dropdown2 input-sm"');?>
              </div>
               
            </div>
             <div class="form-group">
              <div class="col-xs-6">
               <label>Vendor</label>
                   <?php 
                  $nama_supplier = $this->global_models->get_field("mrp_supplier","name",array("id_mrp_supplier" => "{$this->session->userdata("report_po_search_id_supplier")}"));
                print $this->form_eksternal->form_input("supplier", $nama_supplier, 'id="supplier" class="form-control input-sm" placeholder="Supplier"');
                  print $this->form_eksternal->form_input("id_supplier", $this->session->userdata("report_po_search_id_supplier"), 'id="id_supplier" style="display: none"');
                  
                  ?> </div>
                 <div class="col-xs-6">

               <label>Type</label>
        <?php print $this->form_eksternal->form_dropdown("type", $type, 
                        $this->session->userdata("report_po_search_type"), ' class="form-control dropdown2 input-sm"');?>
              </div>
               
            </div>
          </div>
           <div class="row">
            <div class="col-xs-4">
              <br />
              <button class="btn btn-primary" type="submit">Search</button>
              <hr />
            </div>
            <div class="col-xs-4">
              <br />
              <input type='submit' name='export' value='Export Excel' class='btn btn-success' type='submit'></input>
              <hr />
            </div>
              <div class="col-xs-4">
              <br />
              <input type='submit' name='xls' value='Excel' class='btn btn-warning' type='submit'></input>
              <hr />
            </div>
          </div>          </form> 
        </div>
    </div>
  </div>
</div>

<div class="row">
   <div class="col-xs-12">
    <div class="box box-solid box-success">
        <div class="box-header">
            <h3 class="box-title">Report PO</h3>
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
                        <th style="width: 20px;">No</th>
                        <th style="width: 20px;">Tanggal</th>
                        <th>No Po</th>
                        <th style="width: 30px;">Qty</th>
                        <th style="width: 30px;">Total Harga</th>
                        <th style="width: 110px;">Vendor</th>
                        <th style="width: 120px;">Beban</th>
                        <th style="width: 120px;">Surat Jalan</th>
                        <th style="width: 25px;">Lama</th>
                         <th style="width: 25px;"></th>
                    </tr>
                  </thead>
                  <tbody id="data_list">
                  </tbody>
<!--                  <tfoot>
                    <tr>
                      <td colspan="3">
                         
                      </td>
                      <td id="qty"></td>
                      <td id="jml"></td>
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
</div>

<div id="script-tambahan">
  
</div>

