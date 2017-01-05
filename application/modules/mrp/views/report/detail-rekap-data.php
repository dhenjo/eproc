
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
            <h3 class="box-title">Rekap</h3>
            <div class="box-tools pull-right">
                <button class="btn btn-success btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
            </div>
            <div class="widget-control pull-left">
              <span style="display: none; margin-left: 10px;" id="loader-page"><img width="35px" src="<?php print $url?>img/ajax-loader.gif" /></span>
            </div>
        </div>
        <?php
        $jml_data = ($this->session->userdata("report_dept_search_end_month") - $this->session->userdata("report_dept_search_start_month")) + 1;
        
        if($jml_data > 0){
            $jml_data = $jml_data;
        }else{
            $jml_data = 0;
        }
        ?>
        <div class="box-body">
          <div class="row">
            <div class="box">
              <div class="box-body table-responsive">
                <table class="table table-bordered table-hover" id="tableboxy">
                  <thead>
<!--                    <tr>
                        <th style="width: 20px;">No</th>
                        <th>Nama Barang</th>
                        <th>Jenis/Merk</th>
                        <th style="width: 30px;">Qty</th>
                        <th style="width: 30px;">Harga</th>
                        <th style="width: 120px;"></th>
                    </tr>-->
                     <tr>
                        <th  style="width: 10px;" rowspan="2">No</th>
                        <th rowspan="2" style="width: 30%;">Nama Barang</th>
                        <th colspan="<?php print $jml_data; ?>"><center><?php print $title_organisasi; ?></center></th>
                        <th colspan="2"><center>Jumlah</center></th>
                    </tr>
                    <tr>
                     <?php
                     $mth = array(1 => "Jan", 2 => "Feb", 3 => "Mar", 4 => "Apr", 5 => "Mei", 6 => "Jun", 7 => "Jul", 8 => "Agu", 9 => "Sep",
            10 => "Okt", 11 => "Nov", 12 => "Des");
        
                     for ($a=$this->session->userdata("report_dept_search_start_month"); $a <= $this->session->userdata("report_dept_search_end_month"); $a++) {
                     
                     ?>   
                      <td style="width: 5px;"><center><?php print $mth[$a]?></center></td>
                     <?php } ?>
                      <td>Unit</td>
                      <td>Rupiah</td>
                    </tr>
                  </thead>
                  <tbody id="data_list">
                  </tbody>
                  <tfoot>
                    <tr>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <?php $dtno = $jml_data; 
                      for ($a=$this->session->userdata("report_dept_search_start_month"); $a <= $this->session->userdata("report_dept_search_end_month"); $a++) {
                     
                      ?>
                      
                        <td colspan="" id="bulan-<?php print $a; ?>"></td>
                      <?php } ?>
                      <td style="width: 5px;" id="qty"></td>
                      <td style="width: 5px;" id="jml"></td>
                      
                     
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