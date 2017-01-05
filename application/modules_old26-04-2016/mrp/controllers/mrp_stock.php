<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mrp_stock extends MX_Controller {
    
  function __construct() {      
    $this->menu = $this->cek();
  }
  
    function stock_inventory(){
     
    $css = "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/datatables/dataTables.bootstrap.css' rel='stylesheet' type='text/css' />"
      . "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/jQueryUI/jquery-ui-1.10.3.custom.min.css' rel='stylesheet' type='text/css' />";

    $foot = ""
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/datatables/jquery.dataTables.js' type='text/javascript'></script>"
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/datatables/dataTables.bootstrap.js' type='text/javascript'></script>"
     ;
	
    $foot .= "<script>"
                               
      . '$(function() {'                         
        . 'var table = '
        . '$("#tableboxy").dataTable({'
          . '"order": [[ 0, "asc" ]],'
        . '});'
        . 'ambil_data(table, 0, 0);'
      . '});'
            
     . 'function ambil_data(table, mulai){'
        . '$.post("'.site_url("mrp/mrp-ajax-stock/get-stock/").'/"+mulai, function(data){'
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
        
    $this->template->build("main-mrp/stock", 
        array(
              'url'                 => base_url()."themes/".DEFAULTTHEMES."/",
              'menu'                => 'mrp/mrp-stock/stock-inventory',
              'title'               => lang("Stock"),
//              'list'                => $list,
//              'id_mrp_task_orders'  => $id_mrp_task_orders,
//              'id_mrp_po'           => $id_mrp_po,
//              'dt_status'              => $status,
//              'breadcrumb'  => array(
//                    "mrp_list_po"  => "mrp/list-po"
//                ),
              'css'         => $css,
              'foot'        => $foot,
//              'img'       => base_url()."themes/".DEFAULTTHEMES."/img/ajax-loader.gif"
//              'items'       => $items
            ));
      $this->template
        ->set_layout('form')
        ->build("main-mrp/stock");
}

  function stock_department(){
    
//   if(!$this->input->post(NULL)){ 
    $dropdown_department = $this->global_models->get_dropdown("hr_department", "id_hr_department", "title", TRUE);     
    $dropdown_company = $this->global_models->get_dropdown("hr_company", "id_hr_company", "title", TRUE); 
        
//    print $this->session->userdata('stock_dept_search_id_hr_master');
//    die;
          
       $pst = $this->input->post(NULL);
       if($pst){
//          print_r($pst); 
//          die;
           $id_hr_master_organisasi = end(array_filter($pst['id_hr_master_organisasi']));

           $set = array(
            "stock_dept_search_id_company"          => $pst['id_company'],
            "stock_dept_search_id_hr_master"        => $id_hr_master_organisasi,
            
        );
          $this->session->set_userdata($set);
       }else{
           $hr_pegawai = $this->global_models->get("hr_pegawai", array("id_users" => $this->session->userdata("id")));
    
           $set = array(
            "stock_dept_search_id_company"          => $hr_pegawai[0]->id_hr_company,
            "stock_dept_search_id_hr_master"        => $hr_pegawai[0]->id_hr_master_organisasi,  
        );
          $this->session->set_userdata($set);
       }
//       print $this->session->userdata("stock_dept_search_id_company");
//       die;
       $where = "WHERE D.id_hr_master_organisasi='{$this->session->userdata("stock_dept_search_id_hr_master")}'";
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
        . 'ambil_data(table, 0, 0);'
      . '});'
            
         . "$('#pending-mutasi2').click(function(){"
                    . '$(function() {'                         
                    . 'var table = '
                    . '$("#tableboxy7").dataTable({'
                    . '"order": [[ 0, "asc" ]],'
                    . '"bDestroy": true'
                . '});'
                    . "table.fnClearTable();"  
                    . 'ambil_data7(table, 0);'
        . '});'
            
         . 'function ambil_data7(table, mulai){'
//        . "var type =1;"
//        . "var id_inventory = '{$id_mrp_inventory_spesifik}';"        
        . '$.post("'.site_url("mrp/mrp-ajax-stock/get-pending-mutasi").'/"+mulai, function(data){'
          . '$("#loader-page2").show();'
          . 'var hasil = $.parseJSON(data);'
          . 'if(hasil.status == 2){'
            . 'table.fnAddData(hasil.hasil);'
            . 'ambil_data7(table, hasil.start);'
          . '}'
          . 'else{'
            . '$("#loader-page2").hide();'
          . '}'
        . '});'
      . '}'
        . "});"
            
     . 'function ambil_data(table, mulai){'
        . '$.post("'.site_url("mrp/mrp-ajax-stock/get-stock-department").'/"+mulai, function(data){'
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
        . '$.post("'.site_url("mrp/mrp-ajax-stock/get-stock-department-detail").'/"+type+"/0/"+mulai, function(data){'
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
      ."</script>";
        
    $this->template->build("main-mrp/stock-department/main", 
        array(
              'url'             => base_url()."themes/".DEFAULTTHEMES."/",
              'menu'            => 'mrp/mrp-stock/stock-department',
              'title'           => lang("Stock Department"),
              'department'      => $dropdown_department,
              'company'         => $dropdown_company,
              'detail'          => $detail,
//              'id_mrp_po'           => $id_mrp_po,
//              'dt_status'              => $status,
//              'breadcrumb'  => array(
//                    "mrp_list_stock_department"  => "mrp/stock-department"
//                ),
              'css'         => $css,
              'foot'        => $foot,
//              'img'       => base_url()."themes/".DEFAULTTHEMES."/img/ajax-loader.gif"
//              'items'       => $items
            ));
      $this->template
        ->set_layout('form')
        ->build("main-mrp/stock-department/main");
//   }else{
       
       
//   }
}

  public function mutasi_stock($id_mrp_stock = 0){

              
//    $data = $this->global_models->get_query("SELECT *"
//        . " FROM mrp_stock_in AS A"
//        . " WHERE A.id_mrp_stock=1"
//        . " ORDER BY A.tanggal_diterima ASC");
//    $jumlah = 10;
//      foreach ($data as $ky => $val) {
//                print "<br> jumlah dasar: ".$jumlah;
//              if($val->jumlah <= $jumlah){
//                  print "<br>".$jumlah."-".$val->jumlah." ";
//                  $jumlah = ($jumlah - $val->jumlah);
//                  print $jumlah;
//              }elseif($jumlah > 0){
//                  print "<br>".$jumlah = ($jumlah - $val->jumlah);
//                  print "<br>selesai";
//                 die;
//              }
//        }
//     print "<pre>";
//    print_r($data);
//    print "</pre>";
//    die;
          
    if(!$this->input->post(NULL)){
        
    $stock = $this->global_models->get("mrp_stock", array("id_mrp_stock" => $id_mrp_stock)); 
         
    $css = "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/datatables/dataTables.bootstrap.css' rel='stylesheet' type='text/css' />"
      . "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/jQueryUI/jquery-ui-1.10.3.custom.min.css' rel='stylesheet' type='text/css' />"
//      . "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/select2.css' type='text/css' rel='stylesheet'>"
            ;
    
    $foot = ""
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/datatables/jquery.dataTables.js' type='text/javascript'></script>"
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/datatables/dataTables.bootstrap.js' type='text/javascript'></script>"
//      . "<script type='text/javascript' src='".base_url()."themes/".DEFAULTTHEMES."/js/select2.js'></script>"
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/jquery.ui.autocomplete.min.js' type='text/javascript'></script>";
   $url = base_url('mrp/mrp-stock/mutasi-stock/'.$id_mrp_stock); 
    $foot .= "<script>"
         
        . "$(function(){"
             . "$( '#tgl_diserahkan' ).datepicker({"
              . "showOtherMonths: true,"
              . "dateFormat: 'yy-mm-dd',"  
              . "selectOtherMonths: true,"  
              . "selectOtherYears: true,"
              . "});"
            . "$( '#users' ).autocomplete({"
                  . "source: '".site_url("mrp/mrp-ajax-stock/ajax-stock-mutasi-pegawai/{$this->session->userdata("id")}")."',"
                  . "minLength: 1,"
                  . "search  : function(){ $(this).addClass('working');},"
                  . "open    : function(){ $(this).removeClass('working');},"
                  . "select: function( event, ui ) {"
                    . "$('#id_users').val(ui.item.id);"
                  . "}"
                . "});"
                          
            . "$('#btn-mutasi').click(function(){"
                    . "var jumlah = $('#dt_jumlah').val();"
                    . "var tgl    = $('#tgl_diserahkan').val();"
                    . "var id_hr_pegawai = $('#id_users').val();"      
                    . " var dt_jumlah = (isNaN(jumlah)) ? 0 : jumlah;"
                        . "if(dt_jumlah == 0){"
                          . "alert('Jumlah Tidak Boleh Kosong');"
                        . "}else{"
                             . "if({$stock[0]->stock_akhir} >= dt_jumlah){"
//                             . "alert(id_hr_pegawai);"
                                
                                ."var dataString2 = 'jumlah='+ dt_jumlah +'&id_pegawai='+id_hr_pegawai+'&tanggal='+tgl;"
                                ."$.ajax({"
                                ."type : 'POST',"
                                ."url : '".site_url("mrp/mrp-ajax-stock/update-mutasi-stock/{$id_mrp_stock}")."',"
                                ."data: dataString2,"
                                ."dataType : 'html',"
                                ."success: function(data) {";
                               $foot .= "window.location.href ='{$url}'"     
                                ."},"
                             ."});"
                                . "}else{"
                                    . "alert('Jumlah yang di input Tidak Boleh Melebihi Stock');"
                                . "}"
                        . "}"

               
            . "});"
                          
            . "$('#stock-in2').click(function(){"
              . '$(function() {'
                . 'var table = '
                . '$("#tableboxy").dataTable({'
                  . '"order": [[ 7, "desc" ]],'
                   . '"bDestroy": true'                      
                . '});'
                . "table.fnClearTable();"                        
                . "ambil_data_stock_in(table, 0,{$id_mrp_stock});"
               . '});'
                                
//              . "});"
            

            . 'function ambil_data_stock_in(table, mulai,id){'
                . '$.post("'.site_url("mrp/mrp-ajax-stock/ajax-stock-in").'/"+id+"/"+mulai, function(data){'
                  . '$("#loader-page").show();'
                  . 'var hasil = $.parseJSON(data);'
                  . 'if(hasil.status == 2){'
                    . 'table.fnAddData(hasil.hasil);'
                    . 'ambil_data_stock_in(table, hasil.start,id);'
                  . '}'
                  . 'else{'
                    . '$("#loader-page").hide();'
        //            . 'load_tooltip();'
        //            . 'load_tooltip_harga();'
        //            . "$('#script-tambahan').html(data);"
                  . '}'
                . '});'
              . '}'
             . "});"
        . "});"
                        
       
                     
	  . "</script>";   
      
//      
//       $status = array( 1=> "<span class='label bg-orange'>Create</span>", 4 => "<span class='label bg-green'>Closed Task Orders</span>");
//    
       $this->template->build("main-mrp/mutasi-stock/list-mutasi", 
        array(
              'url'         => base_url()."themes/".DEFAULTTHEMES."/",
              'menu'        => 'mrp/mrp-stock/stock-inventory',
              'title'       => lang("mrp_mutasi_stock"),
//              'id_mrp_task_orders'  => $id_mrp_task_orders,
              'breadcrumb'  => array(
                    "mrp_stock_inventory"  => "mrp/mrp-stock/stock-inventory"
                ),
              'css'         => $css,
              'foot'        => $foot,
              'dt_status'   => $status,
              'detail'      => $stock,
              'img'       => base_url()."themes/".DEFAULTTHEMES."/img/ajax-loader.gif"
//              'items'       => $items
            ));
      $this->template
        ->set_layout('form')
        ->build("main-mrp/mutasi-stock/list-mutasi");
    }
    
  }
  
   function stock_department_detail($type = 0,$id_mrp_inventory_spesifik = 0){
//     $stock = $this->global_models->get("mrp_stock_out", array("id_mrp_stock" => $id_mrp_stock)); 
     
//       if($this->session->userdata("id") == 1){
//           $id_users = 9;
//       }
//      $hr_pegawai = $this->global_models->get("hr_pegawai", array("id_users" => $id_users));
//       
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
                   . '$.post("'.site_url("mrp/mrp-ajax-stock/get-detail-pemakaian/{$type}/{$id_mrp_inventory_spesifik}").'/"+mulai, function(data){'
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
                                       
            . "$( '.date' ).datepicker({"
              . "showOtherMonths: true,"
              . "dateFormat: 'yy-mm-dd',"  
              . "selectOtherMonths: true,"  
              . "selectOtherYears: true,"
              . "});"
                                       
           . "$( '.date' ).datepicker({"
              . "showOtherMonths: true,"
              . "dateFormat: 'yy-mm-dd',"  
              . "selectOtherMonths: true,"  
              . "selectOtherYears: true,"
              . "});"
                  
            . "});"
       . "$('#detail-stock2').click(function(){"
            
        . '$(function() {'                         
        . 'var table = '
        . '$("#tableboxy5").dataTable({'
            . '"order": [[ 0, "asc" ]],'
            . '"bDestroy": true'
        . '});'
           . "table.fnClearTable();"  
        . 'ambil_data(table, 0);'
      . '});'
            
     . 'function ambil_data(table, mulai){'
        . '$.post("'.site_url("mrp/mrp-ajax-stock/get-stock-department-detail/{$type}/{$id_mrp_inventory_spesifik}").'/"+mulai, function(data){'
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
        
    $this->template->build("main-mrp/stock-department/main-detail", 
        array(
              'url'                         => base_url()."themes/".DEFAULTTHEMES."/",
              'menu'                        => 'mrp/mrp-stock/stock-department',
              'title'                       => lang("Stock Department"),
              'jumlah'                      => $jml,
              'id_hr_master_organisasi'     => $hr_id_hr_master_organisasi,
               'name'                       => $data[0]->title_umum." ".$data[0]->title_spesifik,
//              'id_mrp_task_orders'  => $id_mrp_task_orders,
//              'id_mrp_po'           => $id_mrp_po,
//              'dt_status'              => $status,
              'breadcrumb'                  => array(
                    "mrp_list_stock_department"  => "mrp/mrp-stock/stock-department"
                ),
              'css'         => $css,
              'foot'        => $foot,
//              'img'       => base_url()."themes/".DEFAULTTHEMES."/img/ajax-loader.gif"
//              'items'       => $items
            ));
      $this->template
        ->set_layout('form')
        ->build("main-mrp/stock-department/main-detail");
}

    function get_stock_detail($id_mrp_inventory_spesifik = 0,$start = 0){
//      $id_mrp_supplier = 1;
//       $url = base_url()."themes/".DEFAULTTHEMES."/img/ajax-loader.gif";
//      $where = "WHERE A.status >= 3 AND A.id_mrp_po = '$id_mrp_po'  ";
       $where = "WHERE A.status = 1 AND A.id_mrp_inventory_spesifik = '$id_mrp_inventory_spesifik' ";
       
    $data = $this->global_models->get_query("SELECT A.create_date,A.harga,A.jumlah,A.id_mrp_po,"
            . "B.code,C.id_mrp_task_orders"
        . " FROM mrp_stock AS A"
        . " LEFT JOIN mrp_po AS B ON A.id_mrp_po = B.id_mrp_po"
        . " LEFT JOIN mrp_task_orders_request_asset AS C ON A.id_mrp_task_orders_request_asset = C.id_mrp_task_orders_request_asset "
//        . " LEFT JOIN mrp_inventory_spesifik AS B ON A.id_mrp_inventory_spesifik = B.id_mrp_inventory_spesifik"
//        . " LEFT JOIN mrp_inventory_umum AS C ON B.id_mrp_inventory_umum = C.id_mrp_inventory_umum"
//        . " LEFT JOIN mrp_brand AS D ON B.id_mrp_brand = D.id_mrp_brand"
//        . " LEFT JOIN mrp_satuan AS E ON A.id_mrp_satuan = E.id_mrp_satuan"
        . " {$where}"
        . " GROUP BY C.id_mrp_po"
        . " LIMIT {$start}, 10");
//        print $acs = $this->db->last_query();
//        die;
    if(count($data) > 0){
      $return['status'] = 2;
      $return['start']  = $start + 10;
    }
    else{
      $return['status'] = 1;
    }
   
    foreach ($data AS $da){
       
        $hasil[] = array(
        date("d F Y", strtotime($da->create_date)),
        "<a href='".site_url("mrp/detail-po/{$da->id_mrp_task_orders}/$da->id_mrp_po")."'>{$da->code}</a>",    
        number_format($da->harga),
        $da->jumlah,
//        "<div class='btn-group'>"
//        . "<a href='".site_url("mrp/stock-detail/{$da->id_mrp_inventory_spesifik}")."' type='button' class='btn btn-info btn-flat' title='Edit Task Order' style='width: 40px'><i class='fa fa-table'></i></a>"
//        . "<a href='".site_url("mrp/po/{$da->id_mrp_task_orders}")."' type='button' class='btn btn-info btn-flat' title='Purchase Order' style='width: 40px'><i class='fa fa-shopping-cart'></i></a>"
//      . "</div>"   
      );
         
    }
    
    $return['hasil'] = $hasil;
//    $this->debug($return, true);
    print json_encode($return);
    die;
  }
  
  function proses_pending_mutasi($type = 0,$id_mrp_stock_out_department = 0){
    
       $get = $this->global_models->get_query("SELECT A.user_penerima,A.jumlah,A.tanggal,A.id_mrp_stock_out,A.status,"
        . " B.id_hr_master_organisasi,B.id_hr_company"
        . " FROM mrp_stock_out_department AS A"
        . " LEFT JOIN hr_pegawai AS B ON A.user_penerima = B.id_hr_pegawai"
        . " WHERE A.id_mrp_stock_out_department = '{$id_mrp_stock_out_department}'"
        );
//        print "<pre>";
//        print_r($get);
//        print "</pre>";
//        die;
      if($type == 1){

      if($get[0]->id_mrp_stock_out != 0 AND $get[0]->status == 2){
          $get_stock_out = $this->global_models->get("mrp_stock_out",array("id_mrp_stock_out" => $get[0]->id_mrp_stock_out));
          $get_stock_in = $this->global_models->get("mrp_stock_in",array("id_mrp_stock_in" => $get_stock_out[0]->id_mrp_stock_in));
          $get_stock = $this->global_models->get("mrp_stock",array("id_mrp_inventory_spesifik" => $get_stock_out[0]->id_mrp_inventory_spesifik));
           $dtstock_in             = $get_stock[0]->stock_in + $get[0]->jumlah;
           $dtstock_out            = $get_stock[0]->stock_out + $get[0]->jumlah;
              
         $kirim = array(
        "stock_in"                      => $dtstock_in,
        "stock_out"                     => $dtstock_out,  
        "update_by_users"               => $this->session->userdata("id"),
        "update_date"                   => date("Y-m-d H:i:s")
    );
    $this->global_models->update("mrp_stock", array("id_mrp_inventory_spesifik" => $get_stock_out[0]->id_mrp_inventory_spesifik),$kirim);
    
    $this->olah_stock_in_code($kode_in);
    $kirim = array(
        "id_mrp_stock"                          => $get_stock[0]->id_mrp_stock,
        "kode"                                  => $kode_in,                            
        "id_mrp_receiving_goods_detail"         => $get_stock_in[0]->id_mrp_receiving_goods_detail,
        "id_mrp_inventory_spesifik"             => $get_stock_in[0]->id_mrp_inventory_spesifik,
        "id_mrp_satuan"                         => $get_stock_in[0]->id_mrp_satuan,    
        "id_mrp_supplier"                       => $get_stock_in[0]->id_mrp_supplier,
        "jumlah"                                => $get[0]->jumlah,
        "jumlah_out"                            => $get[0]->jumlah,
        "tanggal_diterima"                      => $get[0]->tanggal, 
        "harga"                                 => $get_stock_in[0]->harga,
        "status"                                => 2,
        "note"                                  => "Mutasi dari stock department dengan kode stock sebelumnya:".$get_stock_out[0]->code,
        "create_by_users"                       => $this->session->userdata("id"),
        "create_date"                           => date("Y-m-d H:i:s")
      );
     $id_mrp_stock_in = $this->global_models->insert("mrp_stock_in", $kirim);
          $this->olah_stock_out_code($kode_out);
          $kirim = array(
                "id_mrp_stock_in"                      => $id_mrp_stock_in,
                "id_mrp_stock"                         => $get_stock[0]->id_mrp_stock,                        
                "id_hr_pegawai"                        => $get[0]->user_penerima,
                "id_hr_master_organisasi"              => $get[0]->id_hr_master_organisasi,
                "id_hr_company"                        => $get[0]->id_hr_company,
                "id_mrp_inventory_spesifik"            => $get_stock_in[0]->id_mrp_inventory_spesifik,
               "id_mrp_satuan"                         => $get_stock_in[0]->id_mrp_satuan,  
                "jumlah"                               => $get[0]->jumlah, 
                "code"                                 => $kode_out,     
                "harga"                                => $get_stock_in[0]->harga,
                "status"                               => 1,
                "note"                                 => "Mutasi dari stock department dengan kode stock sebelumnya:".$get_stock_out[0]->code,
                "tanggal"                              => $get[0]->tanggal,
                "create_by_users"                      => $this->session->userdata("id"),
                "create_date"                          => date("Y-m-d H:i:s")
              );
        $this->global_models->insert("mrp_stock_out", $kirim);
        
        $kirim = array(
        "status"                        => 3,
        "update_by_users"               => $this->session->userdata("id"),
        "update_date"                   => date("Y-m-d H:i:s")
    );
    $this->global_models->update("mrp_stock_out_department", array("id_mrp_stock_out_department" => $id_mrp_stock_out_department),$kirim);
    
      }
   $this->session->set_flashdata('success', 'Data Diupdate');
    }elseif($type == 2){
        $kirim = array(
        "status"                        => 4,
        "update_by_users"               => $this->session->userdata("id"),
        "update_date"                   => date("Y-m-d H:i:s")
    );
    $this->global_models->update("mrp_stock_out_department", array("id_mrp_stock_out_department" => $id_mrp_stock_out_department),$kirim);
     $get_stock_out = $this->global_models->get("mrp_stock_out",array("id_mrp_stock_out" => $get[0]->id_mrp_stock_out));
         $dtstock = $get_stock_out[0]->mutasi - $get[0]->jumlah;
    $kirim = array(
        "status"                        => 1,
        "mutasi"                        => $dtstock,
        "update_by_users"               => $this->session->userdata("id"),
        "update_date"                   => date("Y-m-d H:i:s")
    );
    $this->global_models->update("mrp_stock_out", array("id_mrp_stock_out" => $get[0]->id_mrp_stock_out),$kirim);
     
    $this->session->set_flashdata('success', 'Data Diupdate');
   
     
    }
    redirect("mrp/mrp-stock/stock-department");
  }
  
  private function olah_stock_in_code(&$kode){
    $this->load->helper('string');
    $kode_random = random_string('alnum', 6);
    $st_upper = strtoupper($kode_random);
    $kode = "SIN".$st_upper;
    $cek = $this->global_models->get_field("mrp_stock_in", "id_mrp_stock_in", array("kode" => $kode));
    if($cek > 0){
      $this->olah_stock_in_code($kode);
    }
  }
  
  private function olah_stock_out_code(&$kode){
    $this->load->helper('string');
    $kode_random = random_string('alnum', 6);
    $st_upper = strtoupper($kode_random);
    $kode = "SOT".$st_upper;
    $cek = $this->global_models->get_field("mrp_stock_out", "id_mrp_stock_out", array("code" => $kode));
    if($cek > 0){
      $this->olah_stock_out_code($kode);
    }
  }
 
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */