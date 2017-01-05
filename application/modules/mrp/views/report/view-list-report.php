<?php
if($this->session->userdata("id") == 1 OR $this->nbscache->get_olahan("permission", $this->session->userdata("id_privilege"), "search-report", "edit") !== FALSE){
// print "<pre>";
//        print_r($this->session->all_userdata());
//        print "</pre>";
    ?>
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
                    array($this->session->userdata("report_dept_search_id_company")), 'id="id_company" class="form-control dropdown2 input-sm"');?>
               
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
              <div class="col-xs-4">
                  <span id="dt_department">
               <label>Department</label>
                <?php print $this->form_eksternal->form_dropdown("id_hr_master_organisasi[]", "", 
                        array($detail[0]->id_hr_company_department), 'id="id_department" class="form-control dropdown2 input-sm"');?>
                   </span>
              </div>
              <div class="col-xs-4">
                   <span id="dt_divisi">
                <label>Divisi</label>
                <?php print $this->form_eksternal->form_dropdown("id_hr_master_organisasi[]", "", 
                        array($detail[0]->id_hr_company_department), 'id="id_divisi" class="form-control dropdown2 input-sm"');?>
                   </span>
              </div>
                <div class="col-xs-4">
                  <span id="dt_section">
               <label>Section</label>
                  <?php print $this->form_eksternal->form_dropdown("id_hr_master_organisasi[]", "", 
                        array($detail[0]->id_hr_company_department), 'id="id_section" class="form-control dropdown2 input-sm"');?>
                   </span>
              </div>
            </div>
              <div class="form-group">
               <div class="col-xs-6">

               <label>Type</label>
        <?php print $this->form_eksternal->form_dropdown("type", $type, 
                        $this->session->userdata("report_dept_search_type"), ' class="form-control dropdown2 input-sm"');?>
              </div>    
               <div class="col-xs-6">

               <label>Years</label>
        <?php print $this->form_eksternal->form_input('years', $this->session->userdata("report_dept_search_year"), 'id="tgl_lahir" class="form-control input-sm date_years" placeholder="Years"');?>
              </div>   
            </div>
              
               <div class="form-group">
              <div class="col-xs-6">
               <label>Start Month</label>
                  <?php print $this->form_eksternal->form_dropdown("start_month", $month, 
                        $this->session->userdata("report_dept_search_start_month"), ' class="form-control dropdown2 input-sm"');?>
                  </div>
               <div class="col-xs-6">

               <label>End Month</label>
        <?php print $this->form_eksternal->form_dropdown("end_month", $month, 
                        $this->session->userdata("report_dept_search_end_month"), ' class="form-control dropdown2 input-sm"');?>
              </div>
               
            </div>
            
          </div>
          <div class="row">
            <div class="col-xs-7">
              <br />
              <button class="btn btn-primary" type="submit">Search</button>
              <hr />
            </div>
            <div class="col-xs-5">
              <br />
              <input type='submit' name='export' value='Export Excel' class='btn btn-success' type='submit'></input>
              <hr />
            </div>
          </div>
          </form> 
        </div>
    </div>
  </div>
</div>
<?php }
if($this->session->userdata("id") == 1 OR $this->nbscache->get_olahan("permission", $this->session->userdata("id_privilege"), "search-report-users", "edit") !== FALSE){
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
                <div class="col-xs-6" style="display:none">
                <label>Company</label>
                 <?php print $this->form_eksternal->form_dropdown("id_company", $company, 
                    array($company2), 'id="id_company" class="form-control dropdown2 input-sm"');?>
               
              </div>
              <div class="col-xs-12">
                <label>Struktur</label>
                 <?php print $this->form_eksternal->form_dropdown("id_hr_master_organisasi2", $struktur, 
                    array($this->session->userdata("report_dept_search_id_hr_master")), ' class="form-control dropdown2 input-sm"');?>
               
              </div>
            </div>
          
              <div class="form-group">
               <div class="col-xs-6">

               <label>Type</label>
        <?php print $this->form_eksternal->form_dropdown("type", $type, 
                        $this->session->userdata("report_dept_search_type"), ' class="form-control dropdown2 input-sm"');?>
              </div>    
               <div class="col-xs-6">

               <label>Years</label>
        <?php print $this->form_eksternal->form_input('years', $this->session->userdata("report_dept_search_year"), 'id="tgl_lahir" class="form-control input-sm date_years" placeholder="Years"');?>
              </div>   
            </div>
              
               <div class="form-group">
              <div class="col-xs-6">
               <label>Start Month</label>
                  <?php print $this->form_eksternal->form_dropdown("start_month", $month, 
                        $this->session->userdata("report_dept_search_start_month"), ' class="form-control dropdown2 input-sm"');?>
                  </div>
               <div class="col-xs-6">

               <label>End Month</label>
        <?php print $this->form_eksternal->form_dropdown("end_month", $month, 
                        $this->session->userdata("report_dept_search_end_month"), ' class="form-control dropdown2 input-sm"');?>
              </div>
               
            </div>
            
          </div>
          <div class="row">
            <div class="col-xs-7">
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
<?php } ?>
<div class="row">
   <div class="col-xs-12">
    <div class="box box-solid box-success">
        <div class="box-header">
            <h3 class="box-title">Rekap</h3>
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
                        <th>Nama</th>
                        <th>DEPT/CABANG</th>
                        <th style="width: 30px;">Qty</th>
                        <th style="width: 30px;">Jumlah</th>
                        <th style="width: 120px;"></th>
                    </tr>
                  </thead>
                  <tbody id="data_list">
                  </tbody>
                  <tfoot>
                    <tr>
                      <td colspan="3">
                         
                      </td>
                      <td id="qty"></td>
                      <td id="jml"></td>
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

