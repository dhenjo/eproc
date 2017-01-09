<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mrp_report extends MX_Controller {
    
  function __construct() {      
    $this->menu = $this->cek();
  }
  
      function detail_rekap_data($id_hr_master_organisasi = 0){

        
    $pst = $this->input->post();
      
       if($pst){
           $set = array(
            "report_dept_search_year"                   => $pst['years'],   
            "report_dept_search_start_month"            => $pst['start_month'],
            "report_dept_search_end_month"              => $pst['end_month'],
            "report_dept_search_type"                   => $pst['type'],   
        );
          $this->session->set_userdata($set);
           redirect("mrp/mrp-report/detail-rekap-data/{$id_hr_master_organisasi}");
       }
       
       
//           print_r($pst);
////            $pst = $_REQUEST." bbbr";
////       print_r($pst);
//       die("ss");

       
    $hr_pegawai = $this->global_models->get("hr_master_organisasi", array("parent" => "{$id_hr_master_organisasi}"));
    $no = 0;
    $aa = $id_hr_master_organisasi;
   
    foreach ($hr_pegawai as $ky => $val) {
        if($no > 0){
            $aa .= ",".$val->id_hr_master_organisasi;
            $hr_pegawai2 = $this->global_models->get("hr_master_organisasi", array("parent" => "{$val->id_hr_master_organisasi}"));
            if($hr_pegawai2[0]->id_hr_master_organisasi){
                foreach ($hr_pegawai2 as $ky2 => $val2) {
                    $aa .= ",".$val2->id_hr_master_organisasi;
                 $hr_pegawai3 = $this->global_models->get("hr_master_organisasi", array("parent" => "{$val2->id_hr_master_organisasi}"));
                
                    if($hr_pegawai3[0]->id_hr_master_organisasi){
                        foreach ($hr_pegawai3 as $ky3 => $val3) {
                            $aa .= ",".$val3->id_hr_master_organisasi;
                        }
                    } 
                }
            }
        }else{
            $aa .= ",".$val->id_hr_master_organisasi;
        }
        $no++;        
    }
//    print $aa; die;
       $where = " A.id_hr_master_organisasi IN ({$aa}) AND A.id_hr_company ='{$this->session->userdata('report_dept_search_id_company')}' ";
       
      $data = $this->global_models->get_query("SELECT "
        . " SUM(A.jumlah - (A.pemakaian + A.mutasi)) AS jumlah,A.id_mrp_stock_out,C.name AS title_umum,B.title AS title_spesifik"
        . " FROM mrp_stock_out AS A"
        . " LEFT JOIN mrp_inventory_spesifik AS B ON A.id_mrp_inventory_spesifik = B.id_mrp_inventory_spesifik"
        . " LEFT JOIN mrp_inventory_umum AS C ON B.id_mrp_inventory_umum = C.id_mrp_inventory_umum"
//        . " LEFT JOIN mrp_brand AS D ON B.id_mrp_brand = D.id_mrp_brand"
//        . " LEFT JOIN mrp_satuan AS E ON A.id_mrp_satuan = E.id_mrp_satuan"
        . " WHERE A.status='1' AND {$where}"
        . " GROUP BY A.id_mrp_inventory_spesifik"
        );
//        print "<pre>";
//        print_r($data);
//        print "</pre>";
//        die;
//       print $this->db->last_query();
//       die;
      if($data[0]->jumlah){
          $jml = $data[0]->jumlah;
      }else{
          $jml = 0;
      }
//     print $jml; 
//     die;
//        print_r($data);
//        die;
    $css = "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/datatables/dataTables.bootstrap.css' rel='stylesheet' type='text/css' />"
      . "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/jQueryUI/jquery-ui-1.10.3.custom.min.css' rel='stylesheet' type='text/css' />";
    $url = base_url("mrp/mrp-stock/stock-department-detail/{$type}/{$id_mrp_inventory_spesifik}"); 
    $foot = ""
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/datatables/jquery.dataTables.js' type='text/javascript'></script>"
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/datatables/dataTables.bootstrap.js' type='text/javascript'></script>"
     ;
	
    $foot .= "<script>"
            
            . "$(function(){"
           
//            . "$( '#users' ).autocomplete({"
//                  . "source: '".site_url("mrp/mrp-ajax-stock/get-pegawai/")."',"
//                  . "minLength: 1,"
//                  . "search  : function(){ $(this).addClass('working');},"
//                  . "open    : function(){ $(this).removeClass('working');},"
//                  . "select: function( event, ui ) {"
//                    . "alert('abc');"
//                    . "$('#id_users').val(ui.item.id);"
//                  . "}"
//            . "});"
                          
//            . "$('#detail-pemakaian2').click(function(){"
//                    . '$(function() {'                         
//                    . 'var table = '
//                    . '$("#tableboxy6").dataTable({'
//                    . '"order": [[ 5, "desc" ]],'
//                    . '"bDestroy": true'
//                . '});'
//                    . "table.fnClearTable();"  
//                    . 'ambil_data3(table, 0);'
//                . '});'
            
//                . 'function ambil_data3(table, mulai){'
//                   . '$.post("'.site_url("mrp/mrp-ajax-asset/get-detail-pemakaian/{$type}/{$id_mrp_inventory_spesifik}").'/"+mulai, function(data){'
//                     . '$("#loader-page3").show();'
//                     . 'var hasil = $.parseJSON(data);'
//                     . 'if(hasil.status == 2){'
//                       . 'table.fnAddData(hasil.hasil);'
//                       . 'ambil_data3(table, hasil.start);'
//                     . '}'
//                     . 'else{'
//                       . '$("#loader-page3").hide();'
//                     . '}'
//                   . '});'
//                 . '}'
//            . "});"
             
//            . "$('#btn-mutasi').click(function(){"
////               
//                    . "var jumlah   = $('#dt_jumlah2').val();"
//                    . "var tgl      = $('#tgl_diserahkan6').val();"
//                    . "var note     = $('#note2').val();"
//                    . "id_users     = $('#id_users').val();"
//                    . " var dt_total = '{$jml}';"      
//                    . " var dt_total2   = (isNaN(dt_total)) ? 0 : dt_total;"
//                    . " var dt_jumlah   = (isNaN(jumlah))   ? 0 : jumlah;"
//                    . " var dt_users    = (isNaN(id_users)) ? 0 : id_users;" 
//                        . "if(dt_jumlah == 0 && dt_users == 0){ "
//                          . " alert('Jumlah Tidak Boleh Kosong Dan Users Harus di Isi');"
//                        . " }"
//                            . "else{"
////                            . "alert(id_users);"
//                             . "if(dt_total2 >= dt_jumlah){"
//                                ."var dataString2 = 'jumlah='+ dt_jumlah +'&note='+note+'&tanggal='+tgl+'&total='+ dt_total2 +'&id_users='+dt_users;"
//                                ."$.ajax({"
//                                ."type : 'POST',"
//                                ."url : '".site_url("mrp/mrp-ajax-stock/update-mutasi-stock-department/{$id_mrp_inventory_spesifik}")."',"
//                                ."data: dataString2,"
//                                ."dataType : 'html',"
//                                ."success: function(data) {";
//                               $foot .= "window.location.href ='{$url}';"     
//                                ."},"
//                             ."});"
//                                . "}else{"
//                                    . "alert('Jumlah yang di input Tidak Boleh Melebihi Stock');"
//                                . "}"
//                        . "}"
//            . "});"
                                       
//             . "$('#btn-pemakaian').click(function(){"
//                    . "var jumlah   = $('#dt_jumlah').val();"
//                    . "var tgl      = $('#tgl_pemakaian').val();"
//                    . "var note     = $('#note').val();"
//                    . "var dt_total = '{$jml}';"      
//                    . " var dt_total2 = (isNaN(dt_total)) ? 0 : dt_total;"
//                    . " var dt_jumlah = (isNaN(jumlah)) ? 0 : jumlah;"
//                        . "if(dt_jumlah == 0){"
//                          . "alert('Jumlah Tidak Boleh Kosong');"
//                        . "}else{"
//                             . "if(dt_total2 >= dt_jumlah){"
//                                ."var dataString2 = 'jumlah='+ dt_jumlah +'&note='+note+'&tanggal='+tgl+'&total='+ dt_total2;"
//                                ."$.ajax({"
//                                ."type : 'POST',"
//                                ."url : '".site_url("mrp/mrp-ajax-stock/update-pemakaian-stock/{$id_mrp_inventory_spesifik}")."',"
//                                ."data: dataString2,"
//                                ."dataType : 'html',"
//                                ."success: function(data) {";
//                               $foot .= "window.location.href ='{$url}';"     
//                                ."},"
//                             ."});"
//                                . "}else{"
//                                    . "alert('Jumlah yang di input Tidak Boleh Melebihi Stock');"
//                                . "}"
//                        . "}"
//              . "});"
                                       
//            . "$( '.date' ).datepicker({"
//              . "showOtherMonths: true,"
//              . "dateFormat: 'yy-mm-dd',"  
//              . "selectOtherMonths: true,"  
//              . "selectOtherYears: true,"
//              . "});"
                                       
           . "$( '.date' ).datepicker({"
              . "showOtherMonths: true,"
              . "dateFormat: 'yy-mm-dd',"  
              . "selectOtherMonths: true,"  
              . "selectOtherYears: true,"
              . "});"
                  
            . "});"
//       . "$('#detail-stock2').click(function(){"
        . '$(function() {'                         
        . 'var table = '
        . '$("#tableboxy").dataTable({'
          . '"order": [[ 0, "asc" ]],'
            . '"bDestroy": true'
        . '});'
            . "table.fnClearTable();"  
            . 'ambil_data(table, 0,0,0);'
//      . '});'
            
     . 'function ambil_data(table, mulai,qqty,qharga){'
        . '$.post("'.site_url("mrp/mrp-ajax-report/get-detail-rekap-data/{$id_hr_master_organisasi}").'/"+mulai+"/"+qqty+"/"+qharga, function(data){'
          . '$("#loader-page").show();'
          . 'var hasil = $.parseJSON(data);'
          . 'if(hasil.status == 2){'
            . 'table.fnAddData(hasil.hasil);'
            . 'ambil_data(table, hasil.start,hasil.dtqty,hasil.dtjumlah);'
            . "$('#qty').text(parseFloat(hasil.dtqty, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,').toString());"
            . "$('#jml').text(parseFloat(hasil.dtjumlah, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,').toString());"    
          . '}'
          . 'else{'
            . '$("#loader-page").hide();'
          . '}'
          
        . '});'
      . '}'  
         . "});"
                
      . '$(function() {'
        . "$( '.date_years' ).datepicker({"
        . "changeYear: true,"
        . "showButtonPanel: true,"
        . "dateFormat: 'yy',"
                    . "beforeShow: function (e, t) {"
                    . "$(this).datepicker('hide');"
                    . "$('#ui-datepicker-div').addClass('hide-calendar');"
                    . "$('#ui-datepicker-div').addClass('ui-datepicker-year');"
                    . "$('.ui-datepicker-month').removeClass();"
                    . "$('#ui-datepicker-div').addClass('HideTodayButton');"
                    . "},"
              . "onClose: function(dateText, inst) {"
//              . "var month = $('#ui-datepicker-div .ui-datepicker-month :selected').val();"
              . "var year = $('#ui-datepicker-div .ui-datepicker-year :selected').val();"
              . "$(this).datepicker('setDate', new Date(year, 1));"
              . "}"  
        . "});"     
      . "});"
                
	  . "</script>"; 
        
//    $hr_id_hr_master_organisasi = $this->global_models->get_field("hr_pegawai","id_hr_master_organisasi", array("id_users" => $this->session->userdata("id")));
    
    $month = array(0 => "Pilih", 1 => "Januari", 2 => "Feb", 3 => "Maret", 4 => "April", 5 => "Mei", 6 => "Juni", 7 => "Juli", 8 => "Agustus", 9 => "September",
            10 => "Oktober", 11 => "November", 12 => "Desember");
        
    $title_org =$this->global_models->get_field("hr_master_organisasi","title", array("id_hr_master_organisasi" => "{$id_hr_master_organisasi}"));
    $dropdown_type1 = array (0 => "-All-");
    $dropdown_type2 = $this->global_models->get_dropdown("mrp_type_inventory", "id_mrp_type_inventory", "code", FALSE);
      $dropdown_type = array_merge($dropdown_type1, $dropdown_type2);  
      
//    print "aa";
//    die;
    $this->template->build("report/detail-rekap-data", 
        array(
              'url'                         => base_url()."themes/".DEFAULTTHEMES."/",
              'menu'                        => 'mrp/mrp-report/rekap-data',
              'title'                       => lang("Report Data"),
              'jumlah'                      => $jml,
              'title_organisasi'            => $title_org,
              'month'                       => $month,
              'type'                        => $dropdown_type,
               'name'                       => $data[0]->title_umum." ".$data[0]->title_spesifik,
//              'id_mrp_task_orders'  => $id_mrp_task_orders,
//              'id_mrp_po'           => $id_mrp_po,
//              'dt_status'              => $status,
              'breadcrumb'                  => array(
                    "Report data"  => "mrp/mrp-report/rekap-data"
                ),
              'css'         => $css,
              'foot'        => $foot,
//              'img'       => base_url()."themes/".DEFAULTTHEMES."/img/ajax-loader.gif"
//              'items'       => $items
            ));
      $this->template
        ->set_layout('form')
        ->build("report/detail-rekap-data");
}

        function rekap_bulanan(){
      
    $dropdown_company = $this->global_models->get_dropdown("hr_company", "id_hr_company", "title", TRUE);

       $bulan_now   = date('n');
       $year_now    = date('Y');
       $pst = $this->input->post(NULL);
       if($pst){
//          print_r($pst); 
//          die;
           
        if($pst['export']){
        $this->load->model('mrp/mmrp');
        $this->mmrp->rekap_pembayaran_xls("Rekapan Pembayaran");
        }
        
            if($pst['id_hr_master_organisasi2']){
                $id_hr_master_organisasi = $pst['id_hr_master_organisasi2'];
            }elseif($pst['id_hr_master_organisasi']){
                $id_hr_master_organisasi = end(array_filter($pst['id_hr_master_organisasi']));
            }else{
                $id_hr_master_organisasi = $hr_pegawai[0]->id_hr_master_organisasi;
            }
           
           $set = array(
            "report_bulanan_search_id_company"             => $pst['id_company'],
            "report_bulanan_search_year"                   => $pst['years'],   
            "report_bulanan_search_start_month"            => $pst['start_month'],
            "report_bulanan_search_end_month"              => $pst['end_month'],
            "report_bulanan_search_id_supplier"            => $pst['id_supplier'],
            "report_bulanan_search_id_mrp_po"              => $pst['id_mrp_po'],   
        );
           
          $this->session->set_userdata($set);
       }else{
           $hr_pegawai2 = $this->global_models->get("hr_pegawai", array("id_users" => $this->session->userdata("id")));
    
           if($this->session->userdata("report_bulanan_search_start_month")){
               $start_date = $this->session->userdata("report_bulanan_search_start_month");
           }else{
               $start_date = $bulan_now;
           }
           
           if($this->session->userdata('report_bulanan_search_year')){
               $year_date = $this->session->userdata('report_bulanan_search_year');
           }else{
               $year_date = $year_now;
           }      
           
           if($this->session->userdata("report_bulanan_search_end_month")){
               $end_date = $this->session->userdata("report_bulanan_search_end_month");
           }else{
               $end_date = $bulan_now;
           }
           
           $set = array(
            "report_bulanan_search_id_company"             => $pst['id_company'],
            "report_bulanan_search_year"                   => $year_date,   
            "report_bulanan_search_start_month"            => $start_date,
            "report_bulanan_search_end_month"              => $end_date,
            "report_bulanan_search_id_supplier"            => $pst['id_supplier'],
            "report_bulanan_search_id_mrp_po"              => $pst['id_mrp_po'],   
            
        );
          $this->session->set_userdata($set);
       }
       
    $css = "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/datatables/dataTables.bootstrap.css' rel='stylesheet' type='text/css' />"
      . "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/jQueryUI/jquery-ui-1.10.3.custom.min.css' rel='stylesheet' type='text/css' />"
      . "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/select2.css' type='text/css' rel='stylesheet'>";

    $foot = ""
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/datatables/jquery.dataTables.js' type='text/javascript'></script>"
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/datatables/dataTables.bootstrap.js' type='text/javascript'></script>"
      . "<script type='text/javascript' src='".base_url()."themes/".DEFAULTTHEMES."/js/select2.js'></script>"
     ;
	
   $foot .= "<script>"
            
            . "$(function(){"
           
         . "$( '#supplier' ).autocomplete({"
         . "source: '".site_url("mrp/mrp-ajax-report/get-supplier")."',"
         . "minLength: 1,"
         . "search  : function(){ $(this).addClass('working');},"
         . "open    : function(){ $(this).removeClass('working');},"
         . "select: function( event, ui ) {"
           . "$('#id_supplier').val(ui.item.id);"
           . "var supplier = $('#id_supplier').val();"
           
         . "}"
       . "});"
           
            . "$( '#no-po' ).autocomplete({"
         . "source: '".site_url("mrp/mrp-ajax-report/get-no-po")."',"
         . "minLength: 1,"
         . "search  : function(){ $(this).addClass('working');},"
         . "open    : function(){ $(this).removeClass('working');},"
         . "select: function( event, ui ) {"
           . "$('#id_mrp_po').val(ui.item.id);"
           . "var id_mrp_po = $('#id_mrp_po').val();"
           
         . "}"
       . "});"
           
           . "$( '.date' ).datepicker({"
              . "showOtherMonths: true,"
              . "dateFormat: 'yy-mm-dd',"  
              . "selectOtherMonths: true,"  
              . "selectOtherYears: true,"
              . "});"   
            . "});"
//       . "$('#detail-stock2').click(function(){"
        . '$(function() {'                         
        . 'var table = '
        . '$("#tableboxy").dataTable({'
          . '"order": [[ 0, "asc" ]],'
            . '"bDestroy": true'
        . '});'
            . "table.fnClearTable();"  
            . 'ambil_data(table, 0,0);'
//      . '});'
            
     . 'function ambil_data(table, mulai,jml){'
        . '$.post("'.site_url("mrp/mrp-ajax-report/get-rekap-bulanan").'/"+mulai+"/"+jml, function(data){'
          . '$("#loader-page").show();'
          . 'var hasil = $.parseJSON(data);'
          . 'if(hasil.status == 2){'
            . 'table.fnAddData(hasil.hasil);'
            . 'ambil_data(table, hasil.start,hasil.dtjumlah);'
//            . "$('#qty').text(parseFloat(hasil.dtqty, 10).toFixed(0).replace(/(\d)(?=(\d{3})+\.)/g, '$1,').toString());"
            . "$('#jml').text(parseFloat(hasil.dtjumlah, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,').toString());"    
          . '}'
          . 'else{'
            . '$("#loader-page").hide();'
          . '}'
          
        . '});'
      . '}'  
         . "});"
                
      . '$(function() {'
        . "$( '.date_years' ).datepicker({"
        . "changeYear: true,"
        . "showButtonPanel: true,"
        . "dateFormat: 'yy',"
                    . "beforeShow: function (e, t) {"
                    . "$(this).datepicker('hide');"
                    . "$('#ui-datepicker-div').addClass('hide-calendar');"
                    . "$('#ui-datepicker-div').addClass('ui-datepicker-year');"
                    . "$('.ui-datepicker-month').removeClass();"
                    . "$('#ui-datepicker-div').addClass('HideTodayButton');"
                    . "},"
              . "onClose: function(dateText, inst) {"
//              . "var month = $('#ui-datepicker-div .ui-datepicker-month :selected').val();"
              . "var year = $('#ui-datepicker-div .ui-datepicker-year :selected').val();"
              . "$(this).datepicker('setDate', new Date(year, 1));"
              . "}"  
        . "});"     
      . "});"
                
	  . "</script>"; 
        
            $month = array(0 => "Pilih", 1 => "Januari", 2 => "Feb", 3 => "Maret", 4 => "April", 5 => "Mei", 6 => "Juni", 7 => "Juli", 8 => "Agustus", 9 => "September",
                10 => "Oktober", 11 => "November", 12 => "Desember");
//    print_r($this->session->all_userdata());    
//      die("aa");      
    $this->template->build("report/report-bulanan/main", 
        array(
              'url'                 => base_url()."themes/".DEFAULTTHEMES."/",
              'menu'                => 'mrp/mrp-report/rekap-bulanan',
              'title'               => lang("Report Bulanan"),
//              'department'          => $dropdown_department,
              'company'             => $dropdown_company,
//              'type'                => $dropdown_type,
              'detail'              => $detail,
              'month'               => $month,
              'struktur'            => $aa,
//              'company2'             => $hr_pegawai[0]->id_hr_company,
              'css'         => $css,
              'foot'        => $foot,
            ));
      $this->template
        ->set_layout('form')
        ->build("report/report-bulanan/main");
}

    function detail_report_po($id_mrp_task_orders = 0,$id_mrp_po = 0){

    $css = "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/datatables/dataTables.bootstrap.css' rel='stylesheet' type='text/css' />"
      . "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/jQueryUI/jquery-ui-1.10.3.custom.min.css' rel='stylesheet' type='text/css' />";
    $url = base_url("mrp/mrp-stock/stock-department-detail/{$type}/{$id_mrp_inventory_spesifik}"); 
    $foot = ""
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/datatables/jquery.dataTables.js' type='text/javascript'></script>"
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/datatables/dataTables.bootstrap.js' type='text/javascript'></script>"
     ;
	
    $foot .= "<script>"
            
            . "$(function(){"
           
//            . "$( '#users' ).autocomplete({"
//                  . "source: '".site_url("mrp/mrp-ajax-stock/get-pegawai/")."',"
//                  . "minLength: 1,"
//                  . "search  : function(){ $(this).addClass('working');},"
//                  . "open    : function(){ $(this).removeClass('working');},"
//                  . "select: function( event, ui ) {"
//                    . "alert('abc');"
//                    . "$('#id_users').val(ui.item.id);"
//                  . "}"
//            . "});"
                          
//            . "$('#detail-pemakaian2').click(function(){"
//                    . '$(function() {'                         
//                    . 'var table = '
//                    . '$("#tableboxy6").dataTable({'
//                    . '"order": [[ 5, "desc" ]],'
//                    . '"bDestroy": true'
//                . '});'
//                    . "table.fnClearTable();"  
//                    . 'ambil_data3(table, 0);'
//                . '});'
            
//                . 'function ambil_data3(table, mulai){'
//                   . '$.post("'.site_url("mrp/mrp-ajax-asset/get-detail-pemakaian/{$type}/{$id_mrp_inventory_spesifik}").'/"+mulai, function(data){'
//                     . '$("#loader-page3").show();'
//                     . 'var hasil = $.parseJSON(data);'
//                     . 'if(hasil.status == 2){'
//                       . 'table.fnAddData(hasil.hasil);'
//                       . 'ambil_data3(table, hasil.start);'
//                     . '}'
//                     . 'else{'
//                       . '$("#loader-page3").hide();'
//                     . '}'
//                   . '});'
//                 . '}'
//            . "});"
             
//            . "$('#btn-mutasi').click(function(){"
////               
//                    . "var jumlah   = $('#dt_jumlah2').val();"
//                    . "var tgl      = $('#tgl_diserahkan6').val();"
//                    . "var note     = $('#note2').val();"
//                    . "id_users     = $('#id_users').val();"
//                    . " var dt_total = '{$jml}';"      
//                    . " var dt_total2   = (isNaN(dt_total)) ? 0 : dt_total;"
//                    . " var dt_jumlah   = (isNaN(jumlah))   ? 0 : jumlah;"
//                    . " var dt_users    = (isNaN(id_users)) ? 0 : id_users;" 
//                        . "if(dt_jumlah == 0 && dt_users == 0){ "
//                          . " alert('Jumlah Tidak Boleh Kosong Dan Users Harus di Isi');"
//                        . " }"
//                            . "else{"
////                            . "alert(id_users);"
//                             . "if(dt_total2 >= dt_jumlah){"
//                                ."var dataString2 = 'jumlah='+ dt_jumlah +'&note='+note+'&tanggal='+tgl+'&total='+ dt_total2 +'&id_users='+dt_users;"
//                                ."$.ajax({"
//                                ."type : 'POST',"
//                                ."url : '".site_url("mrp/mrp-ajax-stock/update-mutasi-stock-department/{$id_mrp_inventory_spesifik}")."',"
//                                ."data: dataString2,"
//                                ."dataType : 'html',"
//                                ."success: function(data) {";
//                               $foot .= "window.location.href ='{$url}';"     
//                                ."},"
//                             ."});"
//                                . "}else{"
//                                    . "alert('Jumlah yang di input Tidak Boleh Melebihi Stock');"
//                                . "}"
//                        . "}"
//            . "});"
                                       
//             . "$('#btn-pemakaian').click(function(){"
//                    . "var jumlah   = $('#dt_jumlah').val();"
//                    . "var tgl      = $('#tgl_pemakaian').val();"
//                    . "var note     = $('#note').val();"
//                    . "var dt_total = '{$jml}';"      
//                    . " var dt_total2 = (isNaN(dt_total)) ? 0 : dt_total;"
//                    . " var dt_jumlah = (isNaN(jumlah)) ? 0 : jumlah;"
//                        . "if(dt_jumlah == 0){"
//                          . "alert('Jumlah Tidak Boleh Kosong');"
//                        . "}else{"
//                             . "if(dt_total2 >= dt_jumlah){"
//                                ."var dataString2 = 'jumlah='+ dt_jumlah +'&note='+note+'&tanggal='+tgl+'&total='+ dt_total2;"
//                                ."$.ajax({"
//                                ."type : 'POST',"
//                                ."url : '".site_url("mrp/mrp-ajax-stock/update-pemakaian-stock/{$id_mrp_inventory_spesifik}")."',"
//                                ."data: dataString2,"
//                                ."dataType : 'html',"
//                                ."success: function(data) {";
//                               $foot .= "window.location.href ='{$url}';"     
//                                ."},"
//                             ."});"
//                                . "}else{"
//                                    . "alert('Jumlah yang di input Tidak Boleh Melebihi Stock');"
//                                . "}"
//                        . "}"
//              . "});"
                                       
//            . "$( '.date' ).datepicker({"
//              . "showOtherMonths: true,"
//              . "dateFormat: 'yy-mm-dd',"  
//              . "selectOtherMonths: true,"  
//              . "selectOtherYears: true,"
//              . "});"
                                       
           . "$( '.date' ).datepicker({"
              . "showOtherMonths: true,"
              . "dateFormat: 'yy-mm-dd',"  
              . "selectOtherMonths: true,"  
              . "selectOtherYears: true,"
              . "});"
                  
            . "});"
//       . "$('#detail-stock2').click(function(){"
        . '$(function() {'                         
        . 'var table = '
        . '$("#tableboxy").dataTable({'
          . '"order": [[ 0, "asc" ]],'
            . '"bDestroy": true'
        . '});'
            . "table.fnClearTable();"  
            . 'ambil_data(table, 0,0,0);'
//      . '});'
            
     . 'function ambil_data(table, mulai,qqty,qharga){'
        . '$.post("'.site_url("mrp/mrp-ajax-report/get-detail-report-po/{$id_mrp_po}").'/"+mulai+"/"+qqty+"/"+qharga, function(data){'
          . '$("#loader-page").show();'
          . 'var hasil = $.parseJSON(data);'
         . 'if(hasil.status == 2){'
            . 'table.fnAddData(hasil.hasil);'
            . 'ambil_data(table, hasil.start, hasil.dt_total);'
                 . "$('#dt-discount').text(parseFloat(hasil.dt_discount, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,').toString());"
                . "$('#dt-ppn').text(parseFloat(hasil.dt_ppn, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,').toString());"
           . "$('#dt-sub-total').text(parseFloat(hasil.dt_total, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,').toString());"     
           . "$('#dt-total').text(parseFloat(hasil.dt_all_total, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,').toString());"
          . '}'
          . 'else{'
            . '$("#loader-page").hide();'
          . '}'
          
        . '});'
      . '}'  
         . "});"
                
//      . '$(function() {'
//        . "$( '.date_years' ).datepicker({"
//        . "changeYear: true,"
//        . "showButtonPanel: true,"
//        . "dateFormat: 'yy',"
//                    . "beforeShow: function (e, t) {"
//                    . "$(this).datepicker('hide');"
//                    . "$('#ui-datepicker-div').addClass('hide-calendar');"
//                    . "$('#ui-datepicker-div').addClass('ui-datepicker-year');"
//                    . "$('.ui-datepicker-month').removeClass();"
//                    . "$('#ui-datepicker-div').addClass('HideTodayButton');"
//                    . "},"
//              . "onClose: function(dateText, inst) {"
////              . "var month = $('#ui-datepicker-div .ui-datepicker-month :selected').val();"
//              . "var year = $('#ui-datepicker-div .ui-datepicker-year :selected').val();"
//              . "$(this).datepicker('setDate', new Date(year, 1));"
//              . "}"  
//        . "});"     
//      . "});"
                
	  . "</script>"; 
        
//    $hr_id_hr_master_organisasi = $this->global_models->get_field("hr_pegawai","id_hr_master_organisasi", array("id_users" => $this->session->userdata("id")));
    
   $where = " A.id_mrp_po ='{$id_mrp_po}'";
       
      $data = $this->global_models->get_query("SELECT A.no_po,A.tanggal_po,B.name AS supplier,"
              . " (SELECT D.tanggal_diterima"
              . " FROM mrp_receiving_goods_po AS C"
              . " LEFT JOIN mrp_receiving_goods_department AS D ON C.id_mrp_receiving_goods_po = D.id_mrp_receiving_goods_po"
              . " WHERE C.id_mrp_po ='{$id_mrp_po}'"
              . " GROUP BY C.id_mrp_receiving_goods_po"
              . " ORDER BY D.tanggal_diterima ASC) AS tanggal_diterima"
        . " FROM mrp_po AS A"
        . " LEFT JOIN mrp_supplier AS B ON A.id_mrp_supplier = B.id_mrp_supplier"
        . " WHERE A.status='7' AND {$where}"
//        . " GROUP BY A.id_mrp_inventory_spesifik"
        );
//        print $this->db->last_query();
//        die;
        
//         $surat_jln = $this->global_models->get_query("SELECT B.tanggal_diterima"
//        . " FROM mrp_receiving_goods_po AS A"
//        . " LEFT JOIN mrp_receiving_goods_department AS B ON A.id_mrp_receiving_goods_po = B.id_mrp_receiving_goods_po"
//        . " WHERE A.id_mrp_po ='{$id_mrp_po}'"
//        . " GROUP BY A.id_mrp_receiving_goods_po"
//        . " ORDER BY B.tanggal_diterima ASC"
//        );
//        
//        print $this->db->last_query();
//        die;
        $dt_beban =  $this->global_models->get_query(" SELECT G.title AS master_organisasi "
                . " FROM mrp_po_asset AS A"
                . " LEFT JOIN mrp_task_orders_request_asset AS B ON A.id_mrp_task_orders_request_asset = B.id_mrp_task_orders_request_asset"
                . " LEFT JOIN mrp_task_orders_request AS C ON B.id_mrp_task_orders = C.id_mrp_task_orders"
                . " LEFT JOIN mrp_request AS D ON C.id_mrp_request = D.id_mrp_request"
                . " LEFT JOIN mrp_request_asset AS E ON (D.id_mrp_request = E.id_mrp_request AND E.id_mrp_inventory_spesifik = A.id_mrp_inventory_spesifik)"
                . " LEFT JOIN hr_pegawai AS F ON D.id_hr_pegawai = F.id_hr_pegawai"
                . " LEFT JOIN hr_master_organisasi AS G ON F.id_hr_master_organisasi = G.id_hr_master_organisasi"
                . " WHERE A.id_mrp_po ='{$id_mrp_po}' AND A.id_mrp_task_orders='{$id_mrp_task_orders}'"
                . " GROUP BY D.id_hr_pegawai");
                $z = 0;
//             print   $this->db->last_query();
//             die;   
             $beban = "";
        foreach ($dt_beban as $v) {
            if($z > 0){
                $beban .= ",".$v->master_organisasi;
            }else{
                $beban .= $v->master_organisasi;
            }
            
            $z++;
        }
//        print_r($data);
//        die;
        
        $lama = ((strtotime ($data[0]->tanggal_diterima) - strtotime ($data[0]->tanggal_po))/(60*60*24));
    $this->template->build("report/report-po/detail-report-po", 
        array(
              'url'                         => base_url()."themes/".DEFAULTTHEMES."/",
              'menu'                        => 'mrp/mrp-report/report-po',
              'title'                       => lang("Detail Report PO"),
              'name'                        => $data[0]->title_umum." ".$data[0]->title_spesifik,
              'list'                        => $data,
              'surat_jalan'                 => $data[0]->tanggal_diterima,
              'lama'                        => $lama,
              'beban'                       => $beban,
              'breadcrumb'                  => array(
                    "Report PO"  => "mrp/mrp-report/report-po"
                ),
              'css'         => $css,
              'foot'        => $foot,
//              'img'       => base_url()."themes/".DEFAULTTHEMES."/img/ajax-loader.gif"
//              'items'       => $items
            ));
      $this->template
        ->set_layout('form')
        ->build("report/report-po/detail-report-po");
}

    function rekap_data(){
//        $hr_pegawai = $this->global_models->get("hr_pegawai", array("id_users" => $this->session->userdata("id")));
        $hr_pegawai = $this->global_models->get_query("SELECT A.id_hr_company,B.id_hr_master_organisasi,B.level,B.title"
                . " FROM hr_pegawai AS A"
                . " LEFT JOIN hr_master_organisasi AS B ON A.id_hr_master_organisasi = B.id_hr_master_organisasi"
                . " WHERE id_users ='{$this->session->userdata("id")}'");
    $data_lvl = array ("1" => "Direktorat", "2" => "Divisi", "3" => "Department","4" => "Section");
        $aa = array(0 => "ALL");
        foreach ($hr_pegawai as $ky => $val) {
        if($hr_pegawai[0]->id_hr_master_organisasi){
        $aa[$val->id_hr_master_organisasi] = $val->title."[".$data_lvl[$val->level]."]";
            $hr_pegawai2 = $this->global_models->get("hr_master_organisasi", array("parent" => "{$val->id_hr_master_organisasi}"));
            if($hr_pegawai2[0]->id_hr_master_organisasi){
                foreach ($hr_pegawai2 as $ky2 => $val2) {
                    $aa[$val2->id_hr_master_organisasi] = $val2->title."[".$data_lvl[$val2->level]."]";;
                 $hr_pegawai3 = $this->global_models->get("hr_master_organisasi", array("parent" => "{$val2->id_hr_master_organisasi}"));
                    if($hr_pegawai3[0]->id_hr_master_organisasi){
                        foreach ($hr_pegawai3 as $ky3 => $val3) {
                            $aa[$val3->id_hr_master_organisasi] = $val3->title."[".$data_lvl[$val3->level]."]";;
                            $hr_pegawai4 = $this->global_models->get("hr_master_organisasi", array("parent" => "{$val3->id_hr_master_organisasi}"));
                            if($hr_pegawai4[0]->id_hr_master_organisasi){
                                foreach ($hr_pegawai3 as $ky4 => $val4) {
                                    $aa[$val4->id_hr_master_organisasi] = $val4->title."[".$data_lvl[$val4->level]."]";
                                }
                            }
                        }
                    } 
                }
            }
        }else{
            $aa[$val->id_hr_master_organisasi] = $val->title."[".$data_lvl[$val->level]."]";;
        }
             
    }
    
    $dropdown_department = $this->global_models->get_dropdown("hr_department", "id_hr_department", "title", TRUE);     
    $dropdown_company = $this->global_models->get_dropdown("hr_company", "id_hr_company", "title", TRUE);
    $dropdown_type1 = array (0 => "-All-");
    $dropdown_type2 = $this->global_models->get_dropdown("mrp_type_inventory", "id_mrp_type_inventory", "code", FALSE);
    $dropdown_type = array_merge($dropdown_type1, $dropdown_type2);  
//    print $this->session->userdata('stock_dept_search_id_hr_master');
//    die;
       $bulan_now   = date('n');
       $year_now    = date('Y');
       $pst = $this->input->post(NULL);
       if($pst){
//          print_r($pst); 
//          die;
           
        if($pst['export']){
        $this->load->model('mrp/mmrp');
        $this->mmrp->export_xls("Rekapan");
        }
        
            if($pst['id_hr_master_organisasi2']){
                $id_hr_master_organisasi = $pst['id_hr_master_organisasi2'];
            }elseif($pst['id_hr_master_organisasi']){
                $id_hr_master_organisasi = end(array_filter($pst['id_hr_master_organisasi']));
            }else{
                $id_hr_master_organisasi = $hr_pegawai[0]->id_hr_master_organisasi;
            }
           
           $set = array(
            "report_dept_search_id_company"             => $pst['id_company'],
            "report_dept_search_id_hr_master"           => $id_hr_master_organisasi,
            "report_dept_search_year"                   => $pst['years'],   
            "report_dept_search_start_month"            => $pst['start_month'],
            "report_dept_search_end_month"              => $pst['end_month'],
            "report_dept_search_type"                   => $pst['type'],   
        );
           
          $this->session->set_userdata($set);
       }else{
           $hr_pegawai2 = $this->global_models->get("hr_pegawai", array("id_users" => $this->session->userdata("id")));
    
           if($this->session->userdata("report_dept_search_start_month")){
               $start_date = $this->session->userdata("report_dept_search_start_month");
           }else{
               $start_date = $bulan_now;
           }
           
           if($this->session->userdata('report_dept_search_year')){
               $year_date = $this->session->userdata('report_dept_search_year');
           }else{
               $year_date = $year_now;
           }      
           
           if($this->session->userdata("report_dept_search_end_month")){
               $end_date = $this->session->userdata("report_dept_search_end_month");
           }else{
               $end_date = $bulan_now;
           }
           
           $set = array(
            "report_dept_search_id_company"             => $hr_pegawai2[0]->id_hr_company,
            "report_dept_search_id_hr_master"           => $hr_pegawai2[0]->id_hr_master_organisasi,
            "report_dept_search_year"                   => $year_date,   
            "report_dept_search_start_month"            => $start_date,
            "report_dept_search_end_month"              => $end_date,
            
        );
          $this->session->set_userdata($set);
       }
       
//          die;
//       print $this->session->userdata("stock_dept_search_id_company");
//       die;
       $where = "WHERE D.id_hr_master_organisasi='{$this->session->userdata("report_dept_search_id_hr_master")}'";
        $detail = $this->global_models->get_query("SELECT "
        . "D.title AS aa,E.title AS bb,F.title AS cc,G.title AS dd,D.level AS a1,E.level AS b1,F.level AS c1,G.level AS d1"
        . ",D.id_hr_master_organisasi AS a2,E.id_hr_master_organisasi AS b2,F.id_hr_master_organisasi AS c2,G.id_hr_master_organisasi AS d2"
        . " FROM hr_master_organisasi AS D"
//        . " LEFT JOIN m_users AS B ON A.id_users = B.id_users"
//        . " LEFT JOIN hr_company AS C ON A.id_hr_company = C.id_hr_company"    
//        . " LEFT JOIN hr_master_organisasi AS D ON A.id_hr_master_organisasi = D.id_hr_master_organisasi"
        . " LEFT JOIN hr_master_organisasi AS E ON E.id_hr_master_organisasi = D.parent"
        . " LEFT JOIN hr_master_organisasi AS F ON F.id_hr_master_organisasi = E.parent"
        . " LEFT JOIN hr_master_organisasi AS G ON G.id_hr_master_organisasi = F.parent"         
        . " {$where}");
        
//        print $this->db->last_query();
//        die;
        if($detail[0]->a1 == 4){
            $section        = $detail[0]->a2;
            $divisi         = $detail[0]->b2;
            $department     = $detail[0]->c2;
            $direktorat     = $detail[0]->d2;
        }elseif($detail[0]->a1 == 3){
            $divisi         = $detail[0]->a2;
            $department     = $detail[0]->b2;
            $direktorat     = $detail[0]->c2;
            $section        = 0;
        }elseif($detail[0]->a1 == 2){
            $department     = $detail[0]->a2;
            $direktorat     = $detail[0]->b2;
            $divisi         = 0;
            $section        = 0;
        }elseif($detail[0]->a1 == 1){
            $direktorat         = $detail[0]->a2;
            $department         = 0;
            $divisi             = 0;
            $section            = 0;
        }else{
            $direktorat         = 0;
            $department         = 0;
            $divisi             = 0;
            $section            = 0;
        }
      
//        print_r($this->session->all_userdata()); 
//        die;
//    print $this->session->userdata('stock_dept_search_id_hr_master');
//       die;
    $css = "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/datatables/dataTables.bootstrap.css' rel='stylesheet' type='text/css' />"
      . "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/jQueryUI/jquery-ui-1.10.3.custom.min.css' rel='stylesheet' type='text/css' />"
      . "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/select2.css' type='text/css' rel='stylesheet'>";

    $foot = ""
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/datatables/jquery.dataTables.js' type='text/javascript'></script>"
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/datatables/dataTables.bootstrap.js' type='text/javascript'></script>"
      . "<script type='text/javascript' src='".base_url()."themes/".DEFAULTTHEMES."/js/select2.js'></script>"
     ;
	
    $foot .= "<script>"            
      . '$(function() {'                         
        . 'var table = '
        . '$("#tableboxy").dataTable({'
          . '"order": [[ 0, "asc" ]],'
        . '});'
        . 'ambil_data(table, 0, 0,0);'
      . '});'
            
     . 'function ambil_data(table, mulai,qty,jml){'
        . '$.post("'.site_url("mrp/mrp-ajax-report/get-rekap-data").'/"+mulai+"/"+qty+"/"+jml, function(data){'
          . '$("#loader-page").show();'
          . 'var hasil = $.parseJSON(data);'
          . 'if(hasil.status == 2){'
            . 'table.fnAddData(hasil.hasil);'
            . 'ambil_data(table, hasil.start,hasil.dtqty,hasil.dtjumlah);'
            . "$('#qty').text(parseFloat(hasil.dtqty, 10).toFixed(0).replace(/(\d)(?=(\d{3})+\.)/g, '$1,').toString());"
            . "$('#jml').text(parseFloat(hasil.dtjumlah, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,').toString());"
          . '}'
          . 'else{'
            . '$("#loader-page").hide();'
          . '}'
        . '});'
      . '}'
                
      . "$('#stock-dept2').click(function(){"
        . '$(function() {'                         
        . 'var table = '
        . '$("#tableboxy5").dataTable({'
          . '"order": [[ 0, "desc" ]],'
          . '"bDestroy": true'
        . '});'
        . "table.fnClearTable();"        
        . 'ambil_data2(table, 0);'
      . '});'
            
        
            
     . 'function ambil_data2(table, mulai){'
        . "var type =1;"
//        . "var id_inventory = '{$id_mrp_inventory_spesifik}';"        
        . '$.post("'.site_url("mrp/mrp-ajax-asset/get-asset-department-detail").'/"+type+"/0/"+mulai, function(data){'
          . '$("#loader-page2").show();'
          . 'var hasil = $.parseJSON(data);'
          . 'if(hasil.status == 2){'
            . 'table.fnAddData(hasil.hasil);'
            . 'ambil_data2(table, hasil.start);'
          . '}'
          . 'else{'
            
            . '$("#loader-page2").hide();'
          . '}'
        . '});'
      . '}'
        . "});"
                        
	  . "</script>"; 
    
    $foot .= "<script>"
              ."var id_company = $('#id_company').val();"
              ."company(id_company);"
         . "$('.dropdown2').select2();"
              . "$(function() {"
              ."$('#id_company').change(function(){"
                 ." var id=$(this).val();"
                 ."company(id,1);"
                 ."department(id,1);"
                 ."divisi(id,1);"
                 ."section(id,1);"
                ."});"
              
                . "$( '#users' ).autocomplete({"
                  . "source: '".site_url("mrp/mrp-ajax-stock/get-users")."',"
                  . "minLength: 1,"
                  . "search  : function(){ $(this).addClass('working');},"
                  . "open    : function(){ $(this).removeClass('working');},"
                  . "select: function( event, ui ) {"
                    . "$('#id_users').val(ui.item.id);"
                  . "}"
                . "});"
            . "});"
              
      ."function company(id,flag){"
      ."var dataString2 = 'name='+ id+'&flag='+flag;"
         ."$.ajax"
            ."({"
            ."type: 'POST',"
            ."url: '".site_url("mrp/mrp-ajax-stock/ajax-company-direktorat/{$direktorat}")."',"
            ."data: dataString2,"
            ."cache: false,"
            ."success: function(html)"
            ."{"
            ."$('#dt_direktorat').html(html);"
            ."}"
            ."});"
        ."}"
      
      ."function department(id,flag){"
       ."var dataString2 = 'name='+ id+'&flag='+flag;"
         ."$.ajax"
            ."({"
            ."type: 'POST',"
            ."url: '".site_url("mrp/mrp-ajax-stock/ajax-company-department/{$department}")."',"
            ."data: dataString2,"
            ."cache: false,"
            ."success: function(html)"
            ."{"
            ."$('#dt_department').html(html);"
            ."}"
            ."});"
      ."}"
      
      ."function divisi(id,flag){"
       ."var dataString2 = 'name='+ id+'&flag='+flag;"
         ."$.ajax"
            ."({"
            ."type: 'POST',"
            ."url: '".site_url("mrp/mrp-ajax-stock/ajax-company-divisi/{$divisi}")."',"
            ."data: dataString2,"
            ."cache: false,"
            ."success: function(html)"
            ."{"
            ."$('#dt_divisi').html(html);"
            ."}"
            ."});"
      ."}"
                    
      ."function section(id,flag){"
       ."var dataString2 = 'name='+ id+'&flag='+flag;"
         ."$.ajax"
            ."({"
            ."type: 'POST',"
            ."url: '".site_url("mrp/mrp-ajax-stock/ajax-company-section/{$section}")."',"
            ."data: dataString2,"
            ."cache: false,"
            ."success: function(html)"
            ."{"
            ."$('#dt_section').html(html);"
            ."}"
            ."});"
      ."}"
       
      . '$(function() {'
        . "$( '.date_years' ).datepicker({"
        . "changeYear: true,"
        . "showButtonPanel: true,"
        . "dateFormat: 'yy',"
                    . "beforeShow: function (e, t) {"
                    . "$(this).datepicker('hide');"
                    . "$('#ui-datepicker-div').addClass('hide-calendar');"
                    . "$('#ui-datepicker-div').addClass('ui-datepicker-year');"
                    . "$('.ui-datepicker-month').removeClass();"
                    . "$('#ui-datepicker-div').addClass('HideTodayButton');"
                    . "},"
              . "onClose: function(dateText, inst) {"
//              . "var month = $('#ui-datepicker-div .ui-datepicker-month :selected').val();"
              . "var year = $('#ui-datepicker-div .ui-datepicker-year :selected').val();"
              . "$(this).datepicker('setDate', new Date(year, 1));"
              . "}"  
        . "});"     
      . "});"
        
      ."</script>";
        
            $month = array(0 => "Pilih", 1 => "Januari", 2 => "Feb", 3 => "Maret", 4 => "April", 5 => "Mei", 6 => "Juni", 7 => "Juli", 8 => "Agustus", 9 => "September",
                10 => "Oktober", 11 => "November", 12 => "Desember");
//    print_r($this->session->all_userdata());    
//      die("aa");      
    $this->template->build("report/main", 
        array(
              'url'                 => base_url()."themes/".DEFAULTTHEMES."/",
              'menu'                => 'mrp/mrp-report/rekap-data',
              'title'               => lang("Report Data"),
              'department'          => $dropdown_department,
              'company'             => $dropdown_company,
              'type'                => $dropdown_type,
              'detail'              => $detail,
              'month'               => $month,
              'struktur'            => $aa,
              'company2'             => $hr_pegawai[0]->id_hr_company,
              'css'         => $css,
              'foot'        => $foot,
            ));
      $this->template
        ->set_layout('form')
        ->build("report/main");
}

    function report_po(){
    
    $dropdown_company = $this->global_models->get_dropdown("hr_company", "id_hr_company", "title", TRUE);
   
       $bulan_now   = date('n');
       $year_now    = date('Y');
       $pst = $this->input->post(NULL);
       if($pst){
//          print_r($pst); 
//          die;
           
        if($pst['export']){
        $this->load->model('mrp/mmrp');
        $this->mmrp->export_report_po_xls("Report PO");
        }
        
        if($pst['xls']){
        $this->load->model('mrp/mmrp');
        $this->mmrp->export_report_po_merger_xls("Report PO");
        }
        
	if($pst['supplier']){
               $id_supplier = $pst['id_supplier'];
           }else{
               $id_supplier = 0;
           }
		   
           $set = array(
            "report_po_search_id_company"             => $pst['id_company'],
            "report_po_search_year"                   => $pst['years'],   
            "report_po_search_start_month"            => $pst['start_month'],
            "report_po_search_end_month"              => $pst['end_month'],
            "report_po_search_id_supplier"            => $id_supplier,
            'report_po_search_type'                   => $pst['type']   
        );
           
          $this->session->set_userdata($set);
       }else{
           
           if($this->session->userdata("report_po_search_id_company")){
               $id_company = $this->session->userdata("report_po_search_id_company");
           }else{
               $id_company = 0;
           }

           if($this->session->userdata("report_po_search_type")){
               $id_type = $this->session->userdata("report_po_search_type");
           }else{
               $id_type = 0;
           }
               
           if($this->session->userdata("report_po_search_start_month")){
               $start_date = $this->session->userdata("report_po_search_start_month");
           }else{
               $start_date = $bulan_now;
           }
           
           if($this->session->userdata('report_po_search_year')){
               $year_date = $this->session->userdata('report_po_search_year');
           }else{
               $year_date = $year_now;
           }      
           
           if($this->session->userdata("report_po_search_end_month")){
               $end_date = $this->session->userdata("report_po_search_end_month");
           }else{
               $end_date = $bulan_now;
           }
           
		   if($this->session->userdata("report_po_search_id_supplier")){
               $id_supplier = $this->session->userdata("report_po_search_id_supplier");
           }else{
               $id_supplier = 0;
           }
		   
           $set = array(
            "report_po_search_id_company"             => $id_company,
            "report_po_search_year"                   => $year_date,   
            "report_po_search_start_month"            => $start_date,
            "report_po_search_end_month"              => $end_date,
            "report_po_search_id_supplier"            => $id_supplier,
            "report_po_search_type"                   => $id_type,
        );
          $this->session->set_userdata($set);
       }
       
//          die;
//       print $this->session->userdata("stock_dept_search_id_company");
//       die;
      
      
    $css = "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/datatables/dataTables.bootstrap.css' rel='stylesheet' type='text/css' />"
      . "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/jQueryUI/jquery-ui-1.10.3.custom.min.css' rel='stylesheet' type='text/css' />"
      . "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/select2.css' type='text/css' rel='stylesheet'>";

    $foot = ""
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/datatables/jquery.dataTables.js' type='text/javascript'></script>"
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/datatables/dataTables.bootstrap.js' type='text/javascript'></script>"
      . "<script type='text/javascript' src='".base_url()."themes/".DEFAULTTHEMES."/js/select2.js'></script>"
     ;
	
    $foot .= "<script>"            
      . '$(function() {'                         
        . 'var table = '
        . '$("#tableboxy").dataTable({'
          . '"order": [[ 0, "asc" ]],'
        . '});'
        . 'ambil_data(table, 0);'
      . '});'
	  
           . "$( '#supplier' ).autocomplete({"
         . "source: '".site_url("mrp/mrp-ajax-report/get-supplier")."',"
         . "minLength: 1,"
         . "search  : function(){ $(this).addClass('working');},"
         . "open    : function(){ $(this).removeClass('working');},"
         . "select: function( event, ui ) {"
           . "$('#id_supplier').val(ui.item.id);"
           . "var supplier = $('#id_supplier').val();"
           
         . "}"
       . "});"
	   
     . 'function ambil_data(table, mulai){'
        . '$.post("'.site_url("mrp/mrp-ajax-report/get-report-po").'/"+mulai, function(data){'
          . '$("#loader-page").show();'
          . 'var hasil = $.parseJSON(data);'
          . 'if(hasil.status == 2){'
            . 'table.fnAddData(hasil.hasil);'
            . 'ambil_data(table, hasil.start);'
          . '}'
          . 'else{'
            . '$("#loader-page").hide();'
          . '}'
        . '});'
      . '}'
   
                        
	  . "</script>"; 
    
    $foot .= "<script>"
              ."var id_company = $('#id_company').val();"
             
         . "$('.dropdown2').select2();"
              . "$(function() {"
              ."$('#id_company').change(function(){"
                 ." var id=$(this).val();"
                
                ."});"
              
               
            . "});"
              
      . '$(function() {'
        . "$( '.date_years' ).datepicker({"
        . "changeYear: true,"
        . "showButtonPanel: true,"
        . "dateFormat: 'yy',"
                    . "beforeShow: function (e, t) {"
                    . "$(this).datepicker('hide');"
                    . "$('#ui-datepicker-div').addClass('hide-calendar');"
                    . "$('#ui-datepicker-div').addClass('ui-datepicker-year');"
                    . "$('.ui-datepicker-month').removeClass();"
                    . "$('#ui-datepicker-div').addClass('HideTodayButton');"
                    . "},"
              . "onClose: function(dateText, inst) {"
//              . "var month = $('#ui-datepicker-div .ui-datepicker-month :selected').val();"
              . "var year = $('#ui-datepicker-div .ui-datepicker-year :selected').val();"
              . "$(this).datepicker('setDate', new Date(year, 1));"
              . "}"  
        . "});"     
      . "});"
        
      ."</script>";
        
            $month = array(0 => "Pilih", 1 => "Januari", 2 => "Feb", 3 => "Maret", 4 => "April", 5 => "Mei", 6 => "Juni", 7 => "Juli", 8 => "Agustus", 9 => "September",
                10 => "Oktober", 11 => "November", 12 => "Desember");
            
            $dropdown_type = $this->global_models->get_dropdown("mrp_type_inventory", "id_mrp_type_inventory", "code", TRUE,array("status" => "1"));     
        
    $this->template->build("report/report-po/main-report-po", 
        array(
              'url'                 => base_url()."themes/".DEFAULTTHEMES."/",
              'menu'                => 'mrp/mrp-report/report-po',
              'title'               => lang("Report PO"),
              'department'          => $dropdown_department,
              'company'             => $dropdown_company,
              'type'                => $dropdown_type,
              'detail'              => $detail,
              'month'               => $month,
              'type'                => $dropdown_type,
              'css'         => $css,
              'foot'        => $foot,
            ));
      $this->template
        ->set_layout('form')
        ->build("report/report-po/main-report-po");
}

   function asset_department_detail($type = 0,$id_mrp_inventory_spesifik = 0){
//     $stock = $this->global_models->get("mrp_stock_out", array("id_mrp_stock" => $id_mrp_stock)); 
     
//       if($this->session->userdata("id") == 1){
//           $id_users = 9;
//       }
//      $hr_pegawai = $this->global_models->get("hr_pegawai", array("id_users" => $id_users));
//       
       $pst = $this->input->post();
      
       if($pst){
//         print_r($pst);
//         die("ss");
        if($pst["no_asset"]){
            $kirim = array(
            "no_asset"                    => $pst['no_asset'],
            "status"                      => $pst['status'],    
            "update_by_users"             => $this->session->userdata("id"),
            "update_date"                 => date("Y-m-d H:i:s")
        );

      $update_data = $this->global_models->update("mrp_stock_out", array("id_mrp_stock_out" => $pst['id_mrp_stock_out']),$kirim); 
      
      $gt =$this->global_models->get("mrp_stock_out",array("id_mrp_stock_out" => $pst['id_mrp_stock_out']));
      $dtstatus = array( 1=> "<span class='label bg-green'>Dipakai</span>",
         2 => "<span class='label bg-orange'>Pending Mutasi</span>",
         3 => "<span class='label bg-red'>Mutasi</span>",
         4 => "<span class='label bg-navy'>Reject</span>",
         5 => "<span class='label bg-green'>Update</span>",
         6 => "<span class='label bg-red'>Hilang</span>",
         7 => "<span class='label bg-red'>Rusak</span>",
        );
      if($pst['no_asset']){
          $noasset = "No. Asset:".$pst['no_asset'];
      }else{
          $noasset = "";
      }
      $kirim = array(
            "id_mrp_stock_out"                     => $gt[0]->id_mrp_stock_out,
            "id_hr_pegawai"                        => $gt[0]->id_hr_pegawai,
            "id_hr_master_organisasi"              => $gt[0]->id_hr_master_organisasi,
            "id_hr_company"                        => $gt[0]->id_hr_company,
            "id_mrp_inventory_spesifik"            => $gt[0]->id_mrp_inventory_spesifik,
            "id_mrp_satuan"                        => $gt[0]->id_mrp_satuan,
            "harga"                                => $gt[0]->harga,
            "jumlah"                               => $gt[0]->jumlah,
            "status"                               => 5,
            "note"                                 => "Data di Update <br> ".$noasset."<br>Dengan Status:".$dtstatus[$pst['status']],
            "create_by_users"                      => $this->session->userdata("id"),
            "create_date"                          => date("Y-m-d H:i:s")
            );
            $this->global_models->insert("mrp_stock_out_department", $kirim);
      
    if($update_data){
        $this->session->set_flashdata('success', 'Data berhasil di Update');
        redirect("mrp/mrp-asset/asset-department-detail/{$pst['type']}/{$pst['id_mrp_inventory_spesifik']}");
     }
       }
       
       if($pst["id_users"] && $pst['tanggal'] && $pst['jumlah_mutasi']){
           if($pst['total'] >= $pst['jumlah_mutasi']){
               $ttl = $pst['total'] - $pst['jumlah_mutasi'];
               if($ttl > 0){
                   $stts = 1;
               }else{
                   $stts = 2;
               }
        $kirim = array(
            "status"                      => $stts,
            "mutasi"                      => $pst['jumlah_mutasi'],      
            "update_by_users"             => $this->session->userdata("id"),
            "update_date"                 => date("Y-m-d H:i:s")
            );
            $update_data = $this->global_models->update("mrp_stock_out", array("id_mrp_stock_out" => $pst['id_mrp_stock_out']),$kirim); 
       
            $gt =$this->global_models->get("mrp_stock_out",array("id_mrp_stock_out" => $pst['id_mrp_stock_out']));
            $kirim = array(
            "id_mrp_stock_out"                     => $gt[0]->id_mrp_stock_out,
            "id_hr_pegawai"                        => $gt[0]->id_hr_pegawai,
            "id_hr_master_organisasi"              => $gt[0]->id_hr_master_organisasi,
            "id_hr_company"                        => $gt[0]->id_hr_company,
            "id_mrp_inventory_spesifik"            => $gt[0]->id_mrp_inventory_spesifik,
            "id_mrp_satuan"                        => $gt[0]->id_mrp_satuan,
            "jumlah"                               => $pst['jumlah_mutasi'],  
            "harga"                                => $gt[0]->harga,
            "status"                               => 2,
            "user_penerima"                        => $pst['id_users'],
            "tanggal"                              => $pst['tanggal'],
            "note"                                 => $pst['note'],
            "create_by_users"                      => $this->session->userdata("id"),
            "create_date"                          => date("Y-m-d H:i:s")
            );
            $this->global_models->insert("mrp_stock_out_department", $kirim);
            
            $this->session->set_flashdata('success', 'Data berhasil di Update');
            redirect("mrp/mrp-asset/asset-department-detail/{$pst['type']}/{$pst['id_mrp_inventory_spesifik']}");
           }else{
               $this->session->set_flashdata('notice', 'Jumlah Yang Anda Masukan melebihi Jumlah Asset');
               redirect("mrp/mrp-asset/asset-department-detail/{$pst['type']}/{$pst['id_mrp_inventory_spesifik']}");
           }
           
       }else{
           $this->session->set_flashdata('notice', 'Kolom Tanggal diserahkan,kolom Users dan kolom Jumlah Harus di Isi');
           redirect("mrp/mrp-asset/asset-department-detail/{$pst['type']}/{$pst['id_mrp_inventory_spesifik']}");
       }
//           print_r($pst);
////            $pst = $_REQUEST." bbbr";
////       print_r($pst);
//       die("ss");
       }
       
    $hr_pegawai = $this->global_models->get("hr_master_organisasi", array("parent" => "{$this->session->userdata('stock_dept_search_id_hr_master')}"));
    $no = 0;
    $aa = $this->session->userdata('stock_dept_search_id_hr_master');
    foreach ($hr_pegawai as $ky => $val) {
        if($no > 0){
            $aa .= ",".$val->id_hr_master_organisasi;
            $hr_pegawai2 = $this->global_models->get("hr_master_organisasi", array("parent" => "{$val->id_hr_master_organisasi}"));
            if($hr_pegawai2[0]->id_hr_master_organisasi){
                foreach ($hr_pegawai2 as $ky2 => $val2) {
                    $aa .= ",".$val2->id_hr_master_organisasi;
                 $hr_pegawai3 = $this->global_models->get("hr_master_organisasi", array("parent" => "{$val2->id_hr_master_organisasi}"));
                
                    if($hr_pegawai3[0]->id_hr_master_organisasi){
                        foreach ($hr_pegawai3 as $ky3 => $val3) {
                            $aa .= ",".$val3->id_hr_master_organisasi;
                        }
                    } 
                }
            }
        }else{
            $aa .= ",".$val->id_hr_master_organisasi;
        }
        $no++;        
    }
//    print $aa; die;
       $where = " A.id_mrp_inventory_spesifik='$id_mrp_inventory_spesifik' AND A.id_hr_master_organisasi IN ({$aa}) AND A.id_hr_company ='{$this->session->userdata('stock_dept_search_id_company')}' ";
       
      $data = $this->global_models->get_query("SELECT "
        . " SUM(A.jumlah - (A.pemakaian + A.mutasi)) AS jumlah,A.id_mrp_stock_out,C.name AS title_umum,B.title AS title_spesifik"
        . " FROM mrp_stock_out AS A"
        . " LEFT JOIN mrp_inventory_spesifik AS B ON A.id_mrp_inventory_spesifik = B.id_mrp_inventory_spesifik"
        . " LEFT JOIN mrp_inventory_umum AS C ON B.id_mrp_inventory_umum = C.id_mrp_inventory_umum"
//        . " LEFT JOIN mrp_brand AS D ON B.id_mrp_brand = D.id_mrp_brand"
//        . " LEFT JOIN mrp_satuan AS E ON A.id_mrp_satuan = E.id_mrp_satuan"
        . " WHERE A.status='1' AND {$where}"
        . " GROUP BY A.id_mrp_inventory_spesifik"
        );
//        print "<pre>";
//        print_r($data);
//        print "</pre>";
//        die;
//       print $this->db->last_query();
//       die;
      if($data[0]->jumlah){
          $jml = $data[0]->jumlah;
      }else{
          $jml = 0;
      }
//     print $jml; 
//     die;
//        print_r($data);
//        die;
    $css = "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/datatables/dataTables.bootstrap.css' rel='stylesheet' type='text/css' />"
      . "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/jQueryUI/jquery-ui-1.10.3.custom.min.css' rel='stylesheet' type='text/css' />";
    $url = base_url("mrp/mrp-stock/stock-department-detail/{$type}/{$id_mrp_inventory_spesifik}"); 
    $foot = ""
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/datatables/jquery.dataTables.js' type='text/javascript'></script>"
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/datatables/dataTables.bootstrap.js' type='text/javascript'></script>"
     ;
	
    $foot .= "<script>"
            
            . "$(function(){"
           
            . "$( '#users' ).autocomplete({"
                  . "source: '".site_url("mrp/mrp-ajax-stock/get-pegawai/")."',"
                  . "minLength: 1,"
                  . "search  : function(){ $(this).addClass('working');},"
                  . "open    : function(){ $(this).removeClass('working');},"
                  . "select: function( event, ui ) {"
                    . "alert('abc');"
                    . "$('#id_users').val(ui.item.id);"
                  . "}"
            . "});"
                          
            . "$('#detail-pemakaian2').click(function(){"
                    . '$(function() {'                         
                    . 'var table = '
                    . '$("#tableboxy6").dataTable({'
                    . '"order": [[ 5, "desc" ]],'
                    . '"bDestroy": true'
                . '});'
                    . "table.fnClearTable();"  
                    . 'ambil_data3(table, 0);'
                . '});'
            
                . 'function ambil_data3(table, mulai){'
                   . '$.post("'.site_url("mrp/mrp-ajax-asset/get-detail-pemakaian/{$type}/{$id_mrp_inventory_spesifik}").'/"+mulai, function(data){'
                     . '$("#loader-page3").show();'
                     . 'var hasil = $.parseJSON(data);'
                     . 'if(hasil.status == 2){'
                       . 'table.fnAddData(hasil.hasil);'
                       . 'ambil_data3(table, hasil.start);'
                     . '}'
                     . 'else{'
                       . '$("#loader-page3").hide();'
                     . '}'
                   . '});'
                 . '}'
            . "});"
             
            . "$('#btn-mutasi').click(function(){"
//               
                    . "var jumlah   = $('#dt_jumlah2').val();"
                    . "var tgl      = $('#tgl_diserahkan6').val();"
                    . "var note     = $('#note2').val();"
                    . "id_users     = $('#id_users').val();"
                    . " var dt_total = '{$jml}';"      
                    . " var dt_total2   = (isNaN(dt_total)) ? 0 : dt_total;"
                    . " var dt_jumlah   = (isNaN(jumlah))   ? 0 : jumlah;"
                    . " var dt_users    = (isNaN(id_users)) ? 0 : id_users;" 
                        . "if(dt_jumlah == 0 && dt_users == 0){ "
                          . " alert('Jumlah Tidak Boleh Kosong Dan Users Harus di Isi');"
                        . " }"
                            . "else{"
//                            . "alert(id_users);"
                             . "if(dt_total2 >= dt_jumlah){"
                                ."var dataString2 = 'jumlah='+ dt_jumlah +'&note='+note+'&tanggal='+tgl+'&total='+ dt_total2 +'&id_users='+dt_users;"
                                ."$.ajax({"
                                ."type : 'POST',"
                                ."url : '".site_url("mrp/mrp-ajax-stock/update-mutasi-stock-department/{$id_mrp_inventory_spesifik}")."',"
                                ."data: dataString2,"
                                ."dataType : 'html',"
                                ."success: function(data) {";
                               $foot .= "window.location.href ='{$url}';"     
                                ."},"
                             ."});"
                                . "}else{"
                                    . "alert('Jumlah yang di input Tidak Boleh Melebihi Stock');"
                                . "}"
                        . "}"
            . "});"
                                       
             . "$('#btn-pemakaian').click(function(){"
                    . "var jumlah   = $('#dt_jumlah').val();"
                    . "var tgl      = $('#tgl_pemakaian').val();"
                    . "var note     = $('#note').val();"
                    . "var dt_total = '{$jml}';"      
                    . " var dt_total2 = (isNaN(dt_total)) ? 0 : dt_total;"
                    . " var dt_jumlah = (isNaN(jumlah)) ? 0 : jumlah;"
                        . "if(dt_jumlah == 0){"
                          . "alert('Jumlah Tidak Boleh Kosong');"
                        . "}else{"
                             . "if(dt_total2 >= dt_jumlah){"
                                ."var dataString2 = 'jumlah='+ dt_jumlah +'&note='+note+'&tanggal='+tgl+'&total='+ dt_total2;"
                                ."$.ajax({"
                                ."type : 'POST',"
                                ."url : '".site_url("mrp/mrp-ajax-stock/update-pemakaian-stock/{$id_mrp_inventory_spesifik}")."',"
                                ."data: dataString2,"
                                ."dataType : 'html',"
                                ."success: function(data) {";
                               $foot .= "window.location.href ='{$url}';"     
                                ."},"
                             ."});"
                                . "}else{"
                                    . "alert('Jumlah yang di input Tidak Boleh Melebihi Stock');"
                                . "}"
                        . "}"
              . "});"
                                       
//            . "$( '.date' ).datepicker({"
//              . "showOtherMonths: true,"
//              . "dateFormat: 'yy-mm-dd',"  
//              . "selectOtherMonths: true,"  
//              . "selectOtherYears: true,"
//              . "});"
                                       
           . "$( '.date' ).datepicker({"
              . "showOtherMonths: true,"
              . "dateFormat: 'yy-mm-dd',"  
              . "selectOtherMonths: true,"  
              . "selectOtherYears: true,"
              . "});"
                  
            . "});"
//       . "$('#detail-stock2').click(function(){"
        . '$(function() {'                         
        . 'var table = '
        . '$("#tableboxy5").dataTable({'
          . '"order": [[ 1, "desc" ]],'
            . '"bDestroy": true'
        . '});'
            . "table.fnClearTable();"  
            . 'ambil_data(table, 0);'
//      . '});'
            
     . 'function ambil_data(table, mulai){'
        . '$.post("'.site_url("mrp/mrp-ajax-asset/get-asset-department-detail/{$type}/{$id_mrp_inventory_spesifik}").'/"+mulai, function(data){'
          . '$("#loader-page").show();'
          . 'var hasil = $.parseJSON(data);'
          . 'if(hasil.status == 2){'
            . 'table.fnAddData(hasil.hasil);'
            . 'ambil_data(table, hasil.start);'
          . '}'
          . 'else{'
            . '$("#loader-page").hide();'
          . '}'
        . '});'
      . '}'  
         . "});"
	  . "</script>"; 
    $hr_id_hr_master_organisasi = $this->global_models->get_field("hr_pegawai","id_hr_master_organisasi", array("id_users" => $this->session->userdata("id")));
        
    $this->template->build("asset/main-detail", 
        array(
              'url'                         => base_url()."themes/".DEFAULTTHEMES."/",
              'menu'                        => 'mrp/mrp-asset/asset-department',
              'title'                       => lang("Asset Department"),
              'jumlah'                      => $jml,
              'id_hr_master_organisasi'     => $hr_id_hr_master_organisasi,
               'name'                       => $data[0]->title_umum." ".$data[0]->title_spesifik,
//              'id_mrp_task_orders'  => $id_mrp_task_orders,
//              'id_mrp_po'           => $id_mrp_po,
//              'dt_status'              => $status,
              'breadcrumb'                  => array(
                    "mrp_list_asset_department"  => "mrp/mrp-asset/asset-department"
                ),
              'css'         => $css,
              'foot'        => $foot,
//              'img'       => base_url()."themes/".DEFAULTTHEMES."/img/ajax-loader.gif"
//              'items'       => $items
            ));
      $this->template
        ->set_layout('form')
        ->build("asset/main-detail");
}

  function proses_pending_mutasi($type = 0,$id_mrp_stock_out_department = 0){
    
       $get = $this->global_models->get_query("SELECT A.user_penerima,A.jumlah,A.tanggal,A.id_mrp_stock_out,A.status,"
        . " B.id_hr_master_organisasi,B.id_hr_company,"
        . " C.jumlah AS jumlah_asset,C.mutasi AS mutasi_asset,C.id_mrp_stock_in,C.id_mrp_stock_out,C.id_mrp_stock,C.id_mrp_inventory_spesifik,C.id_mrp_satuan,C.harga,"
        . " D.name AS receiver,G.title AS rcv_organisasi, E.name AS giver,H.title AS gvr_organisasi"
        . " FROM mrp_stock_out_department AS A"
        . " LEFT JOIN hr_pegawai AS B ON A.user_penerima = B.id_hr_pegawai"
        . " LEFT JOIN mrp_stock_out AS C ON A.id_mrp_stock_out = C.id_mrp_stock_out"
        . " LEFT JOIN m_users AS D ON B.id_users = D.id_users"
        . " LEFT JOIN m_users AS E ON A.create_by_users = E.id_users"
        . " LEFT JOIN hr_pegawai AS F ON E.id_users =F.id_users"
        . " LEFT JOIN hr_master_organisasi AS G ON B.id_hr_master_organisasi = G.id_hr_master_organisasi"
        . " LEFT JOIN hr_master_organisasi AS H ON F.id_hr_master_organisasi = H.id_hr_master_organisasi"
        . " WHERE A.id_mrp_stock_out_department = '{$id_mrp_stock_out_department}'"
        );
//        print "<pre>";
//        print_r($get);
//        print "</pre>";
//        die;
      if($type == 1){
          $dt_total = ($get[0]->jumlah_asset - $get[0]->mutasi_asset);
    if($dt_total > 0){
 if($get[0]->id_mrp_stock_out != 0 AND $get[0]->status == 2){

    $jml = ($get[0]->jumlah_asset - $get[0]->jumlah);
    $mts = ($get[0]->mutasi_asset - $get[0]->jumlah);
     $kirim = array(
    "jumlah"                        => $jml,
    "mutasi"                        => $mts,  
    "update_by_users"               => $this->session->userdata("id"),
    "update_date"                   => date("Y-m-d H:i:s")
    );
    $this->global_models->update("mrp_stock_out", array("id_mrp_stock_out" => $get[0]->id_mrp_stock_out),$kirim);

    $this->olah_stock_out_code($kode_out);
    $kirim = array(
          "id_mrp_stock_in"                      => $get[0]->id_mrp_stock_in,
          "id_mrp_stock"                         => $get[0]->id_mrp_stock,                        
          "id_hr_pegawai"                        => $get[0]->user_penerima,
          "id_hr_master_organisasi"              => $get[0]->id_hr_master_organisasi,
          "id_hr_company"                        => $get[0]->id_hr_company,
          "id_mrp_inventory_spesifik"            => $get[0]->id_mrp_inventory_spesifik,
         "id_mrp_satuan"                         => $get[0]->id_mrp_satuan,  
          "jumlah"                               => $get[0]->jumlah, 
          "code"                                 => $kode_out,     
          "harga"                                => $get[0]->harga,
          "status"                               => 1,
          "note"                                 => "Mutasi Asset Ke ".$get[0]->receiver." [".$get[0]->rcv_organisasi."] Dari ".$get[0]->giver." [".$get[0]->gvr_organisasi."]",
          "tanggal"                              => $get[0]->tanggal,
          "create_by_users"                      => $this->session->userdata("id"),
          "create_date"                          => date("Y-m-d H:i:s")
        );
  $this->global_models->insert("mrp_stock_out", $kirim);

  $kirim = array(
  "status"                        => 3,
  "note_mutasi"                   => "Mutasi Asset Ke ".$get[0]->receiver." [".$get[0]->rcv_organisasi."] Dari ".$get[0]->giver." [".$get[0]->gvr_organisasi."]",    
  "update_by_users"               => $this->session->userdata("id"),
  "update_date"                   => date("Y-m-d H:i:s")
);
$this->global_models->update("mrp_stock_out_department", array("id_mrp_stock_out_department" => $id_mrp_stock_out_department),$kirim);

}
    }else{
        $dtstock       = $get[0]->mutasi_asset - $get[0]->jumlah;
        $kirim = array(
        "id_hr_pegawai"                 => $get[0]->user_penerima,
        "id_hr_master_organisasi"       => $get[0]->id_hr_master_organisasi,
        "id_hr_company"                 => $get[0]->id_hr_company,
        "mutasi"                        => $dtstock,    
        "note"                          => "Mutasi Asset Ke ".$get[0]->receiver." [".$get[0]->rcv_organisasi."] Dari ".$get[0]->giver." [".$get[0]->gvr_organisasi."]",   
        "update_by_users"               => $this->session->userdata("id"),
        "status"                        => 1,    
        "update_date"                   => date("Y-m-d H:i:s")
        );
        $this->global_models->update("mrp_stock_out", array("id_mrp_stock_out" => $get[0]->id_mrp_stock_out),$kirim);

        $kirim = array(
        "status"                        => 3,
        "note_mutasi"                   => "Mutasi Asset Ke ".$get[0]->receiver." [".$get[0]->rcv_organisasi."] Dari ".$get[0]->giver." [".$get[0]->gvr_organisasi."]",    
        "update_by_users"               => $this->session->userdata("id"),
        "update_date"                   => date("Y-m-d H:i:s")
    );
        
    $this->global_models->update("mrp_stock_out_department", array("id_mrp_stock_out_department" => $id_mrp_stock_out_department),$kirim);

    }
      
   $this->session->set_flashdata('success', 'Data Diupdate');
//   redirect("mrp/mrp-asset/asset-department");
    }elseif($type == 2){
        $kirim = array(
        "status"                        => 4,
        "update_by_users"               => $this->session->userdata("id"),
        "update_date"                   => date("Y-m-d H:i:s")
    );
    $this->global_models->update("mrp_stock_out_department", array("id_mrp_stock_out_department" => $id_mrp_stock_out_department),$kirim);
     $get_stock_out = $this->global_models->get("mrp_stock_out",array("id_mrp_stock_out" => $get[0]->id_mrp_stock_out));
     $dtstock       = $get[0]->mutasi_asset - $get[0]->jumlah;
    
    $kirim = array(
        "status"                        => 1,
        "mutasi"                        => $dtstock,
        "update_by_users"               => $this->session->userdata("id"),
        "update_date"                   => date("Y-m-d H:i:s")
    );
    $this->global_models->update("mrp_stock_out", array("id_mrp_stock_out" => $get[0]->id_mrp_stock_out),$kirim);
    
    $this->session->set_flashdata('success', 'Data Diupdate');
   
     
    }
    redirect("mrp/mrp-asset/asset-department");
  }
  
}