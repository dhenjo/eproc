<?php
//if($this->session->userdata("id") == 1 OR $this->nbscache->get_olahan("permission", $this->session->userdata("id_privilege"), "search-report", "edit") !== FALSE){
//// print "<pre>";
////        print_r($this->session->all_userdata());
////        print "</pre>";
////        die;
//    ?>

<!--<style>
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
    
</style>    -->
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
              <div class="col-xs-4">
                <label>Company</label>
                 <?php print $this->form_eksternal->form_dropdown("id_company", $company, 
                    array($this->session->userdata("report_bulanan_search_id_company")), 'id="id_company" class="form-control dropdown2 input-sm"');?>
               
              </div>
             <div class="col-xs-4">
               <label>Suplier</label>
                   <?php 
                  $nama_supplier = $this->global_models->get_field("mrp_supplier","name",array("id_mrp_supplier" => "{$this->session->userdata("report_bulanan_search_id_supplier")}"));
                print $this->form_eksternal->form_input("supplier", $nama_supplier, 'id="supplier" class="form-control input-sm" placeholder="Supplier"');
                  print $this->form_eksternal->form_input("id_supplier", $this->session->userdata("report_bulanan_search_id_supplier"), 'id="id_supplier" style="display: none"');
                  
                  ?> </div>
                <div class="col-xs-4">
               <label>No. PO</label>
                   <?php 
                  $nama_supplier = $this->global_models->get_field("mrp_po","no_po",array("id_mrp_po" => "{$this->session->userdata("report_bulanan_search_id_mrp_po")}"));
                print $this->form_eksternal->form_input("no_po", $nama_supplier, 'id="no-po" class="form-control input-sm" placeholder="No PO"');
                  print $this->form_eksternal->form_input("id_mrp_po", $this->session->userdata("report_bulanan_search_id_mrp_po"), 'id="id_mrp_po" style="display: none"');
                  
                  ?> </div>
            </div>
           
            <div class="form-group">
              <div class="col-xs-4">
               <label>Years</label>
        <?php print $this->form_eksternal->form_input('years', $this->session->userdata("report_bulanan_search_year"), 'id="tgl_lahir" class="form-control input-sm date_years" placeholder="Years"');?>
              </div>  
              <div class="col-xs-4">
               <label>Start Month</label>
                  <?php print $this->form_eksternal->form_dropdown("start_month", $month, 
                        $this->session->userdata("report_bulanan_search_start_month"), ' class="form-control dropdown2 input-sm"');?>
                  </div>
               <div class="col-xs-4">

               <label>End Month</label>
        <?php print $this->form_eksternal->form_dropdown("end_month", $month, 
                        $this->session->userdata("report_bulanan_search_end_month"), ' class="form-control dropdown2 input-sm"');?>
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
<?php // }
//
//?>

<div class="row">
   <div class="col-xs-12">
    <div class="box box-solid box-success">
        <div class="box-header">
            <h3 class="box-title">Rekap Bulanan</h3>
            <div class="box-tools pull-right">
                <button class="btn btn-success btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
            </div>
            <div class="widget-control pull-left">
              <span style="display: none; margin-left: 10px;" id="loader-page"><img width="35px" src="<?php print $url?>img/ajax-loader.gif" /></span>
            </div>
        </div>
        <?php
        $jml_data = ($this->session->userdata("report_bulanan_search_end_month") - $this->session->userdata("report_bulanan_search_start_month")) + 1;
        
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
                        <th  style="width: 3%;" rowspan="2">No</th>
                        <th rowspan="2" style="width: 30%;">Struktural</th>
                        <th colspan="<?php print $jml_data; ?>"><center>Bulan</center></th>
                <th rowspan="2" style="width:15%"><center>Total</center></th>
                    </tr>
                    <tr>
                     <?php
                     $mth = array(1 => "Jan", 2 => "Feb", 3 => "Mar", 4 => "Apr", 5 => "Mei", 6 => "Jun", 7 => "Jul", 8 => "Agu", 9 => "Sep",
            10 => "Okt", 11 => "Nov", 12 => "Des");
        
                     for ($a=$this->session->userdata("report_bulanan_search_start_month"); $a <= $this->session->userdata("report_bulanan_search_end_month"); $a++) {
                     
                     ?>   
                      <td style="width: 5px;"><center><?php print $mth[$a]?></center></td>
                     <?php } ?>
<!--                      <td>Unit</td>
                      <td>Rupiah</td>-->
                    </tr>
                  </thead>
                  <tbody id="data_list">
                  </tbody>
                  <tfoot>
                    <tr>
                      <td>&nbsp;</td>
                      <td><center><b>TOTAL</b></center></td>
                      <?php $dtno = $jml_data; 
                      for ($a=$this->session->userdata("report_bulanan_search_start_month"); $a <= $this->session->userdata("report_bulanan_search_end_month"); $a++) {
                     
                      ?>
                      
                        <td colspan="" id="bulan-<?php print $a; ?>"></td>
                      <?php } ?>
<!--                      <td style="width: 5px;" id="qty"></td>
                      <td style="width: 5px;" id="jml"></td>-->
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