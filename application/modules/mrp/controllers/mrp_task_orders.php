<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mrp_task_orders extends MX_Controller {
    
  function __construct() {      
    $this->menu = $this->cek();
  }
 
  public function create_task_orders($id_mrp_task_orders = 0){

    if(!$this->input->post(NULL)){
        
    $css = "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/datatables/dataTables.bootstrap.css' rel='stylesheet' type='text/css' />"
      . "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/jQueryUI/jquery-ui-1.10.3.custom.min.css' rel='stylesheet' type='text/css' />"
      . "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/select2.css' type='text/css' rel='stylesheet'>";
    
    $foot = ""
       . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/jquery1.10.2.min.js' type='text/javascript'></script>"      
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/datatables/jquery.dataTables.js' type='text/javascript'></script>"
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/datatables/dataTables.bootstrap.js' type='text/javascript'></script>"
      . "<script type='text/javascript' src='".base_url()."themes/".DEFAULTTHEMES."/js/select2.js'></script>"
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/jquery.ui.autocomplete.min.js' type='text/javascript'></script>";
	  
    $url = base_url('mrp/mrp-task-orders/task-orders');
    $url2 = base_url('mrp/mrp-task-orders/task-orders/'.$id_mrp_task_orders); 
    $foot .= "<script>"
         
        . "$(function() {"
            . "$('#dtcheck').click(function(){"
                . "$(':checkbox.dt_id').prop('checked', this.checked); "
                . "});"
//            . "var atLeastOneIsChecked = $('#dtcheck:checkbox:checked').length > 0;"
//           
//            . "alert(atLeastOneIsChecked);"
               
            . "$('#btn-task-orders').click(function(){"
            
                . "$('#btn-task-orders').hide();"
                . "$('#btn-loader-page').show();"
                . "var note2   = encodeURIComponent($('#dt_note').val());"
                . "var title2   = encodeURIComponent($('#dt_title').val());"
////            . "alert('cc');"
                . "var oTable = $('#tableboxy').dataTable();"
                . "var aData = [];"
                . "var rowcollection =  oTable.$('input[type=checkbox]:checked', {'page': 'all'});"
                . "rowcollection.each(function(index,elem){"
//            . "alert(index);"
//                . "var aa = $(elem).prop('checked', true);"
                . "var value_id_spesifik = parseInt($(elem).val());"
                . " var hasil = (isNaN(value_id_spesifik)) ? 0 : value_id_spesifik;"
                . "aData.push( hasil );"
            . "});"
//            . "alert(aData);"
                        ."var dataString2 = 'id_mrp_request='+ aData +'&title='+ title2+'&note=' + note2+'&id_mrp_task_orders='+ {$id_mrp_task_orders};"
                        ."$.ajax({"
                        ."type : 'POST',"
                        ."url : '".site_url("mrp/mrp-ajax-to/insert-task-orders")."',"
                        ."data: dataString2,"
                        ."dataType : 'html',"
                        ."success: function(data) {"
                            . 'var hasil = $.parseJSON(data);'    
                            . "$('#btn-loader-page').hide();"
                            . "$('#btn-task-orders').show();"    
                            . "$('#script-tambahan').html(data);";
                        if($id_mrp_task_orders){
                            $foot .= 'var table = '
                                . '$("#tableboxy3").dataTable({'
                                . '"order": [[ 0, "asc" ]],'
                                . '"bDestroy": true'
                                . '});'
                                . "table.fnClearTable();"
                                . 'ambil_data3(table, 0);'
                                    
                                 . 'var table = '
                                . '$("#tableboxy").dataTable({'
                                . '"order": [[ 0, "asc" ]],'
                                . '"bDestroy": true'
                                . '});'
                                . "table.fnClearTable();"
                                . 'ambil_data(table, 0);'
                                    
                                . 'var table = '
                                . '$("#tableboxy2").dataTable({'
                                . '"order": [[ 0, "asc" ]],'
                                . '"bDestroy": true'
                                . '});'
                                . "table.fnClearTable();"
                                . 'ambil_data2(table, 0);';
                        }else{
                            $foot .= "window.location.href ='{$url2}'";
                        }
                        $foot .=  "},"
                     ."});"
            . "});"
        . "});"
      . '$(function() {'
        . 'var table = '
        . '$("#tableboxy").dataTable({'
        . ' "aaSorting": []'
//          . '"order": [[ 0, "asc" ]],'
        . '});'
        . 'ambil_data(table, 0);'
      . '});'
           
       . 'function ambil_data(table, mulai){'
        . '$.post("'.site_url("mrp/mrp-ajax-to/get-task-mrp-request-pengadaan/1").'/"+mulai, function(data){'
          . '$("#loader-page").show();'
          . 'var hasil = $.parseJSON(data);'
          . 'if(hasil.status == 2){'
            . 'table.fnAddData(hasil.hasil);'
            . 'ambil_data(table, hasil.start);'
          . '}'
          . 'else{'
            . '$("#loader-page").hide();'
//            . 'load_tooltip();'
//            . 'load_tooltip_harga();'
//            . "$('#script-tambahan').html(data);"
          . '}'
        . '});'
      . '}'
	. "</script>";   
      $mrp_task_orders = $this->global_models->get("mrp_task_orders", array("id_mrp_task_orders" => $id_mrp_task_orders)); 
      $type1 = array("0" => "-All-");
      $type2 = $this->global_models->get_dropdown("mrp_type_inventory", "id_mrp_type_inventory", "code", FALSE, array("status" => "1")); 
      $type = $type1 + $type2;
      
      $company1 = array("0" => "-All-");
      $company12 = $this->global_models->get_dropdown("hr_company", "id_hr_company", "title", FALSE); 
      $company = array_merge($company1, $company12);
      
      $this->template->build("mrp-task-orders/create-task-orders", 
        array(
              'url'         => base_url()."themes/".DEFAULTTHEMES."/",
              'menu'        => 'mrp/mrp-task-orders/create-task-orders',
              'title'       => lang("create_task_orders"),
              'id_mrp_task_orders'  => $id_mrp_task_orders,
              'breadcrumb'  => array(
                    "mrp_task_orders"  => "mrp/mrp-task-orders/create-task-orders"
                ),
              'css'         => $css,
              'foot'        => $foot,
              'type'        => $type,
              'company'     => $company,
              'detail'      => $mrp_task_orders,
              'img'       => base_url()."themes/".DEFAULTTHEMES."/img/ajax-loader.gif"
//              'items'       => $items
            ));
      $this->template
        ->set_layout('form')
        ->build("mrp-task-orders/create-task-orders");
    }else{
        $pst = $this->input->post();
      
       if($pst){
           $set = array(
            "create_to_search_type"                   => $pst['type'],
            "create_to_search_company"                => $pst['company']    
        );
          $this->session->set_userdata($set);
          redirect("mrp/mrp-task-orders/create-task-orders");
       }else{
            $set = array(
            "create_to_search_type"                   => "",
            "create_to_search_company"                => ""    
                
        );
          $this->session->set_userdata($set);
         redirect("mrp/mrp-task-orders/create-task-orders");
       }   
    }
    
  }
  
    function task_orders() {
//      print "aa"; die;
      $css = "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/datatables/dataTables.bootstrap.css' rel='stylesheet' type='text/css' />"
      . "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/jQueryUI/jquery-ui-1.10.3.custom.min.css' rel='stylesheet' type='text/css' />"
      . "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/select2.css' type='text/css' rel='stylesheet'>";
    
    $foot = ""
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/datatables/jquery.dataTables.js' type='text/javascript'></script>"
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/datatables/dataTables.bootstrap.js' type='text/javascript'></script>"
      . "<script type='text/javascript' src='".base_url()."themes/".DEFAULTTHEMES."/js/select2.js'></script>"
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/jquery.ui.autocomplete.min.js' type='text/javascript'></script>";
	  
    $foot .= "<script>"
//        . "$('.dropdown2').select2();"
//        . "$(function() {"
//            . "$( '#brand' ).autocomplete({"
//              . "source: '".site_url("mrp/mrp-ajax/brand")."',"
//              . "minLength: 1,"
//              . "search  : function(){ $(this).addClass('working');},"
//              . "open    : function(){ $(this).removeClass('working');},"
//              . "select: function( event, ui ) {"
//                . "$('#id_brand').val(ui.item.id);"
//              . "}"
//            . "});"
//        . "});"
      . '$(function() {'
        . 'var table = '
        . '$("#tableboxy").dataTable({'
          . ' "aaSorting": []'    
//          . '"order": [[ 1, "desc" ]],'
//          . "'iDisplayLength': 20"
        . '});'
      
        . 'ambil_data(table, 0);'
      
      . '});'
      
      . 'function ambil_data(table, mulai){'
        . '$.post("'.site_url("mrp/mrp-ajax-to/get-mrp-task-orders").'/"+mulai, function(data){'
          . '$("#loader-page").show();'
          . 'var hasil = $.parseJSON(data);'
          . 'if(hasil.status == 2){'
            . 'table.fnAddData(hasil.hasil);'
            . 'ambil_data(table, hasil.start);'
          . '}'
          . 'else{'
            . '$("#loader-page").hide();'
//            . 'load_tooltip();'
//            . 'load_tooltip_harga();'
//            . "$('#script-tambahan').html(data);"
          . '}'
        . '});'
      . '}'
	  . "</script>";   
    
//    $type = $this->global_models->get_dropdown("mrp_type_inventory", "id_mrp_type_inventory", "code", TRUE, array("status" => "1")); 
//    $brand = $this->global_models->get_dropdown("mrp_brand", "id_mrp_brand", "title", TRUE, array("status" => "1")); 
//    $jenis = array(0 => "- Pilih -",1 => "Habis Pakai", 2 => "Asset");
     $pst = $this->input->post(NULL);
      if($pst){

//        $newdata = array(
//            'inventory_spesifik_search_type'                        => $pst['inventory_spesifik_search_type'],
//            'inventory_spesifik_search_jenis'                       => $pst['inventory_spesifik_search_jenis'],
//            'inventory_spesifik_search_nama'                        => $pst['inventory_spesifik_search_nama'],
//            'inventory_spesifik_search_code'                        => $pst['inventory_spesifik_search_code'],
//            'inventory_spesifik_search_brand'                       => $pst['inventory_spesifik_search_brand'],
//            
//          );
//          $this->session->set_userdata($newdata);
    }
    
    $this->template->build('mrp-task-orders/task-orders', 
      array(
            'url'           => base_url()."themes/".DEFAULTTHEMES."/",
            'menu'          => "mrp/mrp-task-orders/task-orders",
            'title'         => lang("Task Orders"),
            'foot'          => $foot,
            'css'           => $css,
//            'type'          => $type,
//            'jenis'         => $jenis,
//            'brand'         => $brand,
          ));
    $this->template
      ->set_layout('default')
      ->build('mrp-task-orders/task-orders');
  }
  
   public function add_task_orders($id_mrp_task_orders = 0){
      
      
    if(!$this->input->post(NULL)){

        
         $css = "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/datatables/dataTables.bootstrap.css' rel='stylesheet' type='text/css' />"
      . "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/jQueryUI/jquery-ui-1.10.3.custom.min.css' rel='stylesheet' type='text/css' />"
      . "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/select2.css' type='text/css' rel='stylesheet'>";
    
    $foot = ""
             . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/jquery1.10.2.min.js' type='text/javascript'></script>"
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/datatables/jquery.dataTables.js' type='text/javascript'></script>"
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/datatables/dataTables.bootstrap.js' type='text/javascript'></script>"
      . "<script type='text/javascript' src='".base_url()."themes/".DEFAULTTHEMES."/js/select2.js'></script>"
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/jquery.ui.autocomplete.min.js' type='text/javascript'></script>";
	  
    
//    $css = "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/datatables/dataTables.bootstrap.css' rel='stylesheet' type='text/css' />"
//      . "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/jQueryUI/jquery-ui-1.10.3.custom.min.css' rel='stylesheet' type='text/css' />"
////      . "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/select2.css' type='text/css' rel='stylesheet'>"
//      ;
//    
//    $foot = ""
//      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/jquery1.10.2.min.js' type='text/javascript'></script>"      
//      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/datatables/jquery.dataTables.js' type='text/javascript'></script>"
//      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/datatables/dataTables.bootstrap.js' type='text/javascript'></script>"
////      . "<script type='text/javascript' src='".base_url()."themes/".DEFAULTTHEMES."/js/select2.js'></script>"
//      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/jquery.ui.autocomplete.min.js' type='text/javascript'></script>";
    
    $url = base_url('mrp/mrp-task-orders/task-orders');
    $url2 = base_url('mrp/mrp-task-orders/add-task-orders/'.$id_mrp_task_orders); 
    $foot .= "<script>"
        . "$(function() {"
            . "$('#btn-exchange').click(function(){"
            . "var txt;"
            . "var r = confirm('Apakah Anda Yakin Update Perubahan, Apabila di Task Orders ini sudah ada PO, automatis PO yang ada akan di Cancel');"
            . "if (r == true) {"
            . '$.get("'.site_url("mrp/mrp-ajax-to/get-exchange-task-orders/{$id_mrp_task_orders}").'", function(data){'
//          . 'var hasil = $.parseJSON(data);'
            ."window.location.href ='{$url2}'"
          . '});'
            . "} else {"
            . "txt = 'You pressed Cancel!';"
            . "}"
            . "});"
            
            . "$('#dtcheck').click(function(){"
                . "$(':checkbox.dt_id').prop('checked', this.checked); "
                . "});"
            . "$('#btn-closed-to').click(function(){"
              ."var dataString2 = 'id_mrp_task_orders='+ {$id_mrp_task_orders};"
                        ."$.ajax({"
                            ."type : 'POST',"
                            ."url : '".site_url("mrp/mrp-ajax-to/closed-task-orders")."',"
                            ."data: dataString2,"
                            ."dataType : 'html',"
                            ."success: function(data){"
                            ."window.location.href ='{$url2}'"
                            . "}"
                      ."});"
            . "});"
            
            . "$('#btn-cancel-to').click(function(){"
                ."var dataString2 = 'id_mrp_task_orders='+ {$id_mrp_task_orders};"
                        ."$.ajax({"
                            ."type : 'POST',"
                            ."url : '".site_url("mrp/mrp-ajax-to/cancel-task-orders")."',"
                            ."data: dataString2,"
                            ."dataType : 'html',"
                            ."success: function(data){"
                            ."window.location.href ='{$url2}'"
                            . "}"
                      ."});"                    
            . "});"
                                    
            . "$('#btn-update-to').click(function(){"
                 . "$('#btn-update-to').hide();"
                . "$('#loader-page-save').show();"
                . "var note2   = encodeURIComponent($('#dt_note').val());"
                . "var title2   = encodeURIComponent($('#dt_title').val());"
////            . "alert('cc');"
                . "var oTable = $('#tableboxy').dataTable();"
                . "var aData = [];"
                . "var rowcollection =  oTable.$('input[type=checkbox]:checked', {'page': 'all'});"
                . "rowcollection.each(function(index,elem){"
//            . "alert(index);"
//            . "var aa = $(elem).prop('checked', true);"
                . "var value_id_spesifik = parseInt($(elem).val());"
                . " var hasil = (isNaN(value_id_spesifik)) ? 0 : value_id_spesifik;"
                . "aData.push( hasil );"
            . "});"
//            . "alert(aData);"
                        ."var dataString2 = 'id_mrp_request='+ aData +'&title='+ title2+'&note=' + note2+'&id_mrp_task_orders='+ {$id_mrp_task_orders};"
                        ."$.ajax({"
                        ."type : 'POST',"
                        ."url : '".site_url("mrp/mrp-ajax-to/insert-task-orders")."',"
                        ."data: dataString2,"
                        ."dataType : 'html',"
                        ."success: function(data) {"
                          . "$('#dtcheck').prop('checked', false); "
                            . "$('#loader-page-save').hide();"
                            . "$('#btn-update-to').show();"    
                            . "$('#script-tambahan').html(data);";
                        if($id_mrp_task_orders){
                            $foot .= 'var table = '
                                . '$("#tableboxy3").dataTable({'
                                . '"order": [[ 0, "asc" ]],'
                                . '"bDestroy": true'
                                . '});'
                                . "table.fnClearTable();"
                                . 'ambil_data3(table, 0);'
                                    
                                 . 'var table = '
                                . '$("#tableboxy").dataTable({'
                                . '"order": [[ 0, "asc" ]],'
                                . '"bDestroy": true'
                                . '});'
                                . "table.fnClearTable();"
                                . 'ambil_data(table, 0);'
                                    
                                . 'var table = '
                                . '$("#tableboxy2").dataTable({'
                                . '"order": [[ 0, "asc" ]],'
                                . '"bDestroy": true'
                                . '});'
                                . "table.fnClearTable();"
                                . 'ambil_data2(table, 0);';
//                            $foot .= "window.location.href ='{$url2}'";
                        }else{
//                            $foot .= "window.location.href ='{$url2}'";
                        }
                        $foot .=  "},"
                     ."});"
            . "});"
                                    
            . "$('#btn-task-orders').click(function(){"
            
                . "$('#btn-task-orders').hide();"
                . "$('#loader-page-save').show();"
                . "var note2   = encodeURIComponent($('#dt_note').val());"
                . "var title2   = encodeURIComponent($('#dt_title').val());"
////            . "alert('cc');"
                . "var oTable = $('#tableboxy').dataTable();"
                . "var aData = [];"
                . "var rowcollection =  oTable.$('input[type=checkbox]:checked', {'page': 'all'});"
                . "rowcollection.each(function(index,elem){"
//            . "alert(index);"
//            . "var aa = $(elem).prop('checked', true);"
                . "var value_id_spesifik = parseInt($(elem).val());"
                . " var hasil = (isNaN(value_id_spesifik)) ? 0 : value_id_spesifik;"
                . "aData.push( hasil );"
            . "});"
//            . "alert(aData);"
                        ."var dataString2 = 'id_mrp_request='+ aData +'&title='+ title2+'&note=' + note2+'&id_mrp_task_orders='+ {$id_mrp_task_orders};"
                        ."$.ajax({"
                        ."type : 'POST',"
                        ."url : '".site_url("mrp/mrp-ajax-to/insert-task-orders")."',"
                        ."data: dataString2,"
                        ."dataType : 'html',"
                        ."success: function(data) {"
                            . "$('#loader-page-save').hide();"
                            . "$('#btn-task-orders').show();"    
                            . "$('#script-tambahan').html(data);";
                        if($id_mrp_task_orders){
                            $foot .= 'var table = '
                                . '$("#tableboxy3").dataTable({'
                                . '"order": [[ 0, "asc" ]],'
                                . '"bDestroy": true'
                                . '});'
                                . "table.fnClearTable();"
                                . 'ambil_data3(table, 0);'
                                    
                                 . 'var table = '
                                . '$("#tableboxy").dataTable({'
                                . '"order": [[ 0, "asc" ]],'
                                . '"bDestroy": true'
                                . '});'
                                . "table.fnClearTable();"
                                . 'ambil_data(table, 0);'
                                    
                                . 'var table = '
                                . '$("#tableboxy2").dataTable({'
                                . '"order": [[ 0, "asc" ]],'
                                . '"bDestroy": true'
                                . '});'
                                . "table.fnClearTable();"
                                . 'ambil_data2(table, 0);';
                            $foot .= "window.location.href ='{$url2}'";
                        }else{
                            $foot .= "window.location.href ='{$url2}'";
                        }
                        $foot .=  "},"
                     ."});"
            . "});"
        . "});"
                                
    . "$('#btn-proses-mutasi').click(function(){"     
    ."$('#btn-proses-mutasi').hide();"
    ."$('#loader-data').show();"
    ."$('#loader-page').show();"  
    . "var oTable = $('#tableboxy-mutasi').dataTable();"
    . "var aData = [];"
    . "var rowcollection =  oTable.$('.jumlah_mutasi', {'page': 'all'});"
    . "rowcollection.each(function(index,elem){"
    . "var value_jumlah = parseInt($(elem).val());"
    . " var hasil = (isNaN(value_jumlah)) ? 0 : value_jumlah;"
    . "aData.push( hasil );"
//            . "alert(aData);"
    . "});"

    . "var aData2 = [];"
    . "var rowcollection2 =  oTable.$('.id_spesifik', {'page': 'all'});"
    . "rowcollection2.each(function(index,elem){"
    . "var value_id_spesifik = parseInt($(elem).val());"
    . " var hasil2 = (isNaN(value_id_spesifik)) ? 0 : value_id_spesifik;"
    . "aData2.push( hasil2 );"
//            . "alert(aData2);"
    . "});"
    
    . "var tgl_diserahkan   = $('#tgl_diserahkan').val();"
    . "var note_mutasi      = $('#note_mutasi').val();"
        . "if(tgl_diserahkan ==''){"
            . "alert('Tanggal diserahkan tidak boleh kosong');"
//            ."window.location ='{$url2}'"
        . "}else{"
        ."var dataString2 = 'id_mrp_inventory_spesifik='+ aData2 +'&jumlah=' + aData +'&tgl_diserahkan='+tgl_diserahkan+'&note='+note_mutasi;"           
        ."$.ajax({"
        ."type : 'POST',"
        ."url : '".site_url("mrp/mrp-ajax-to/proses-mutasi-stock/{$id_mrp_task_orders}")."',"
        ."data: dataString2,"
        ."dataType : 'html',"
        ."success: function(data) {"
            . 'var hasil = $.parseJSON(data);'
//                . 'alert(hasil);'
            . "if(hasil.status == 2){"
                ."window.location ='{$url2}'"
            . "}else{"
                ."window.location ='{$url}'"
            . "}"
        ."},"
     ."});"
        . "}"             
    . "});"                        
      . "$('#request-orders2').click(function(){"
            . '$(function() {'
        . 'var table = '
        . '$("#tableboxy").dataTable({'
          . '"order": [[ 0, "asc" ]],'
           . '"bDestroy": true'                      
        . '});'
        . "table.fnClearTable();"                        
        . 'ambil_data(table, 0);'
      . '});'
      
      . 'var table = '
        . '$("#tableboxy2").dataTable({'
          . '"order": [[ 1, "asc" ]],'
          . '"bDestroy": true'                        
        . '});'
        . "table.fnClearTable();"                        
        . 'ambil_data2(table, 0);'       
        . "});"
                                
        . "$('#mutasi-to2').click(function(){"
        . 'var table = '
            . '$("#tableboxy-mutasi").dataTable({'
              . '"order": [[ 0, "asc" ]],'
              . '"bDestroy": true'
            . '});'
            . "table.fnClearTable();"
            . 'data_mutasi(table, 0);'
      
        . "});"
                                
      . '$(function() {'
        . 'var table = '
        . '$("#tableboxy3").dataTable({'
          . '"order": [[ 0, "asc" ]],'
        . '});'
        . 'ambil_data3(table, 0);'
      . '});'
      
      . '$(function() {'
        . 'var table = '
        . '$("#tableboxy4").dataTable({'
          . '"order": [[ 0, "asc" ]],'
        . '});'
        . 'ambil_data4(table, 0);'
      . '});'  
                                
      . "$('#purchase-orders2').click(function(){"
           . '$(function() {'
                . 'var table = '
                . '$("#tableboxy5").dataTable({'
                . '"order": [[ 0, "asc" ]],'
                . '"bDestroy": true'                  
                . '});'
                . "table.fnClearTable();"                
                . "ambil_data_po(table, 0,{$this->session->userdata("id")});"
            . '});'
        . "});"
      //tab task orders
       . 'function ambil_data(table, mulai){'
        . '$.post("'.site_url("mrp/mrp-ajax-to/get-task-mrp-request-pengadaan/2").'/"+mulai, function(data){'
          . '$("#loader-page").show();'
          . 'var hasil = $.parseJSON(data);'
          . 'if(hasil.status == 2){'
            . 'table.fnAddData(hasil.hasil);'
            . 'ambil_data(table, hasil.start);'
          . '}'
          . 'else{'
            . '$("#loader-page").hide();'
//            . 'load_tooltip();'
//            . 'load_tooltip_harga();'
//            . "$('#script-tambahan').html(data);"
          . '}'
        . '});'
      . '}'
        //tab RO                            
      . 'function ambil_data2(table, mulai){'
        . '$.post("'.site_url("mrp/mrp-ajax-to/get-task-orders-request/{$id_mrp_task_orders}").'/"+mulai, function(data){'
          . '$("#loader-page2").show();'
          . 'var hasil = $.parseJSON(data);'
          . 'if(hasil.status == 2){'
            . 'table.fnAddData(hasil.hasil);'
            . 'ambil_data2(table, hasil.start);'
          . '}'
          . 'else{'
            . '$("#loader-page2").hide();'
//            . 'load_tooltip();'
//            . 'load_tooltip_harga();'
//            . "$('#script-tambahan').html(data);"
          . '}'
        . '});'
      . '}'
                
      . 'function ambil_data3(table, mulai){'
        . '$.post("'.site_url("mrp/mrp-ajax-to/get-task-orders-request-asset/{$id_mrp_task_orders}").'/"+mulai, function(data){'
          . '$("#loader-page3").show();'
          . 'var hasil = $.parseJSON(data);'
          . 'if(hasil.status == 2){'
            . 'table.fnAddData(hasil.hasil);'
            . 'ambil_data3(table, hasil.start);'
          . '}'
          . 'else{'
            . '$("#loader-page3").hide();'
//            . 'load_tooltip();'
//            . 'load_tooltip_harga();'
//            . "$('#script-tambahan').html(data);"
          . '}'
        . '});'
      . '}'
                
      . 'function ambil_data4(table, mulai){'
        . '$.post("'.site_url("mrp/mrp-ajax-to/get-grouping-orders-request/{$id_mrp_task_orders}").'/"+mulai, function(data){'
          . '$("#loader-page4").show();'
          . 'var hasil = $.parseJSON(data);'
          . 'if(hasil.status == 2){'
            . 'table.fnAddData(hasil.hasil);'
            . 'ambil_data4(table, hasil.start);'
          . '}'
          . 'else{'
            . '$("#loader-page4").hide();'
//            . 'load_tooltip();'
//            . 'load_tooltip_harga();'
//            . "$('#script-tambahan').html(data);"
          . '}'
        . '});'
      . '}'
                
    . 'function data_mutasi(table, mulai){'
    . '$.post("'.site_url("mrp/mrp-ajax-to/get-task-orders-mutasi-asset/{$id_mrp_task_orders}").'/"+mulai, function(data){'
      . '$("#loader-mutasi").show();'
      . 'var hasil = $.parseJSON(data);'
      . 'if(hasil.status == 2){'
            . 'if(hasil.flag == 2){'
            . '$("#loader-mutasi").hide();'
            . '}'
        . 'table.fnAddData(hasil.hasil);'
        . 'data_mutasi(table, hasil.start);'
      . '}'
      . 'else{'
        . '$("#loader-mutasi").hide();'
//            . 'load_tooltip();'
//            . 'load_tooltip_harga();'
//            . "$('#script-tambahan').html(data);"
      . '}'
    . '});'
    . '}'           
      . 'function ambil_data_po(table, mulai,id_users){'
       . '$.post("'.site_url("mrp/mrp-ajax-to/get-view-add-to-list-po/{$id_mrp_task_orders}").'/"+mulai+"/"+id_users, function(data){'
          . '$("#loader-page5").show();'
          . 'var hasil = $.parseJSON(data);'
          . 'if(hasil.status == 2){'
            . 'table.fnAddData(hasil.hasil);'
            . 'ambil_data_po(table, hasil.start,id_users);'
          . '}'
          . 'else{'
            . '$("#loader-page5").hide();'
//            . 'load_tooltip();'
//            . 'load_tooltip_harga();'
//            . "$('#script-tambahan').html(data);"
          . '}'
        . '});'
      . '}'
//                  $da->id_mrp_request                  
      . "function delete_task_order_request(id){"
         . '$("#del_"+ id).hide();'
         . '$("#img-page-"+ id).show();'       
        ."var dataString2 = 'id_mrp_request='+ id;"
            ."$.ajax({"
            ."type : 'POST',"
            ."url : '".site_url("mrp/mrp-ajax-to/delete-task-orders-request/{$id_mrp_task_orders}")."',"
            ."data: dataString2,"
            ."dataType : 'html',"
            ."success: function(data) {"

                     . 'var table = '
                    . '$("#tableboxy2").dataTable({'
                    . '"order": [[ 0, "asc" ]],'
                    . '"bDestroy": true'
                    . '});'
                    . "table.fnClearTable();"
                    . 'ambil_data2(table, 0);'

                    . 'var table = '
                    . '$("#tableboxy3").dataTable({'
                    . '"order": [[ 0, "asc" ]],'
                    . '"bDestroy": true'
                    . '});'
                    . "table.fnClearTable();"
                    . 'ambil_data3(table, 0);'

                    . 'var table = '
                    . '$("#tableboxy").dataTable({'
                    . '"order": [[ 0, "asc" ]],'
                    . '"bDestroy": true'
                    . '});'
                    . "table.fnClearTable();"
                    . 'ambil_data(table, 0);'

//                            . "$('#script-tambahan').html(data);"
//                            ."window.location.href ='{$url2}'"
            ."},"
         ."});"
      . '}'
      . "$('.no_po').on('change', function() {"
//                ."alert( this.value );" // or $(this).val()
            ."var dataString2 = 'no_po='+ this.value;"
            ."$.ajax({"
            ."type : 'POST',"
            ."url : '".site_url("mrp/mrp-task-orders/session-no-po/{$id_mrp_task_orders}")."',"
            ."data: dataString2,"
            ."dataType : 'html',"
            ."success: function(data) {"
               . 'var hasil = $.parseJSON(data);'
//                    . "alert({$this->session->userdata('add_to_search_no_po')});"
            ."},"
         ."});"
               ."});"
        . "$(function() {"
          
                    . "});"
     
	  . "</script>";   
       $mrp_task_orders = $this->global_models->get("mrp_task_orders", array("id_mrp_task_orders" => $id_mrp_task_orders)); 
      
       $status = array( 1=> "<span class='label bg-orange'>Create</span>",2 => "<span class='label bg-green'>Proses PO</span>",
           3 => "<span class='label bg-green'>Approved PO</span>", 4 => "<span class='label bg-green'>Sent PO</span>", 
           9 =>"<span class='label bg-green'>Closed Task Orders</span>", 12 =>"<span class='label bg-red'>Cancel Task Orders</span>");
    
       $type1 = array("0" => "-All-");
      $type2 = $this->global_models->get_dropdown("mrp_type_inventory", "id_mrp_type_inventory", "code", FALSE, array("status" => "1")); 
       $type = $type1 + $type2;
//      $type = array_merge();
//      $no_po = $this->global_models->get_dropdown("mrp_po", "id_mrp_po", "no_po", TRUE); 
      
       $data_po = $this->global_models->get_query("SELECT A.id_mrp_po,A.no_po"
        . " FROM mrp_po AS A"
        . " LEFT JOIN mrp_po_asset AS B ON A.id_mrp_po = B.id_mrp_po"
        . " LEFT JOIN mrp_task_orders AS C ON B.id_mrp_task_orders = C.id_mrp_task_orders"
        . " LEFT JOIN mrp_supplier AS D ON A.id_mrp_supplier = D.id_mrp_supplier"   
        . " WHERE C.id_mrp_task_orders='{$id_mrp_task_orders}' AND A.no_po IS NOT NULL"
        . " GROUP BY A.id_mrp_po "
        . " ORDER BY A.id_mrp_po ASC");
        
//        print_r($data_po);
        $no_po1 = array("0" => "-All-");
        $no_po2 = array();
        if(is_array($data_po)){
            foreach ($data_po as $key => $value) {
            $no_po2[$value->id_mrp_po] = $value->no_po;
            }
        }
      
     
      $no_po = $no_po1 + $no_po2;
//         print "a";
//        die;
      $company1 = array("0" => "-All-");
      $company12 = $this->global_models->get_dropdown("hr_company", "id_hr_company", "title", FALSE); 
      $company = array_merge($company1, $company12);
      
      
       $this->template->build("mrp-task-orders/add-task/view-add-task-orders", 
        array(
              'url'         => base_url()."themes/".DEFAULTTHEMES."/",
              'menu'        => 'mrp/mrp-task-orders/task-orders',
              'title'       => lang("mrp_add_task_orders"),
              'id_mrp_task_orders'  => $id_mrp_task_orders,
              'breadcrumb'  => array(
                    "mrp_task_orders"  => "mrp/mrp-task-orders/task-orders"
                ),
              'css'         => $css,
              'foot'        => $foot,
              'dt_status'   => $status,
                'type'      =>$type,
            'company'              => $company,
            'detail'      => $mrp_task_orders,
            'no_po'         => $no_po,
              'img'       => base_url()."themes/".DEFAULTTHEMES."/img/ajax-loader.gif"
//              'items'       => $items
            ));
      $this->template
        ->set_layout('form')
        ->build("mrp-task-orders/add-task/view-add-task-orders");
    }else{
        $pst = $this->input->post();
      
       if($pst){
           $set = array(
            "create_to_search_type"                   => $pst['type'],
            "create_to_search_company"                => $pst['company']    
        );
          $this->session->set_userdata($set);
          redirect("mrp/mrp-task-orders/add-task-orders/{$id_mrp_task_orders}");
       }else{
            $set = array(
            "create_to_search_type"                   => "",
            "create_to_search_company"                => ""    
                
        );
          $this->session->set_userdata($set);
         redirect("mrp/mrp-task-orders/add-task-orders/{$id_mrp_task_orders}");
       }   
    } 
  }
  
  function session_no_po($id_mrp_task_orders = 0){
      $pst = $this->input->post();
      
       if($pst){
           $set = array(
            "add_to_search_no_po_{$id_mrp_task_orders}"                   => $pst['no_po'], 
        );
          $this->session->set_userdata($set);
          
       }else{
            $set = array(
            "add_to_search_no_po_{$id_mrp_task_orders}"                   => 0,   
                
        );
          $this->session->set_userdata($set);
       }
       
       print json_encode($set);
       die;
  }
  
      function preview_grouping($create_by_users = 0,$id_mrp_task_orders = 0){
   
        if($this->session->userdata("add_to_search_no_po_{$id_mrp_task_orders}")){
            $id_po = " AND E.id_mrp_po ={$this->session->userdata("add_to_search_no_po_{$id_mrp_task_orders}")}";
            $id_mrp_po = " AND I.id_mrp_po ={$this->session->userdata("add_to_search_no_po_{$id_mrp_task_orders}")}";
            }else{
                $id_mrp_po = "";
            $id_po = "";
        }
        
      $detail = $this->global_models->get_query(
      "SELECT A.id_mrp_request,H.user_approval,E.id_mrp_po,E.no_po,E.tanggal_po"
      . ",F.office,F.title AS company,F.address AS address_company"        
      . ",G.name AS nama_supplier,G.id_mrp_supplier,I.id_users AS id_user_approval,G.pic,G.phone,G.address AS address_supplier"
      . ",I.name AS nama_user_aproval,J.signature,L.name AS users,M.title AS organisasi"
      . ",N.name AS pegawai_receiver,P.title AS organisasi_receiver"
      . " FROM mrp_request_asset AS A"
      . " LEFT JOIN mrp_task_orders_request_asset AS C ON A.id_mrp_inventory_spesifik = C.id_mrp_inventory_spesifik"
      . " LEFT JOIN mrp_po_asset AS D ON C.id_mrp_task_orders_request_asset = D.id_mrp_task_orders_request_asset"
      . " LEFT JOIN mrp_po AS E ON D.id_mrp_po = E.id_mrp_po"
      . " LEFT JOIN hr_company AS F ON E.id_hr_company = F.id_hr_company"
      . " LEFT JOIN mrp_supplier AS G ON C.id_mrp_supplier = G.id_mrp_supplier"
      . " LEFT JOIN mrp_request AS H ON A.id_mrp_request = H.id_mrp_request"
      . " LEFT JOIN m_users AS I ON H.user_approval = I.id_users"
      . " LEFT JOIN hr_pegawai AS J ON H.user_approval = J.id_users"
      . " LEFT JOIN hr_pegawai AS K ON H.id_hr_pegawai = K.id_hr_pegawai"
      . " LEFT JOIN m_users AS L ON K.id_users = L.id_users"
      . " LEFT JOIN hr_master_organisasi AS M ON K.id_hr_master_organisasi = M.id_hr_master_organisasi"
      . " LEFT JOIN m_users AS N ON H.create_by_users = N.id_users"
      . " LEFT JOIN hr_pegawai AS O ON N.id_users = O.id_users"       
      . " LEFT JOIN hr_master_organisasi AS P ON O.id_hr_master_organisasi = P.id_hr_master_organisasi"
                    
      . " WHERE C.id_mrp_task_orders='{$id_mrp_task_orders}' AND H.create_by_users = '{$create_by_users}' {$id_po}"
      . " GROUP BY E.id_mrp_po"
              );
      
//      print "<pre>";
//      print $this->db->last_query();
////      print_r($detail);
//      print "</pre>";
//      die;
      
//      $where = "WHERE A.status >= 1 AND A.id_mrp_task_orders = '{$id_mrp_task_orders}' AND I.create_by_users = '{$create_by_users}'  ";
//      $list = $data = $this->global_models->get_query("SELECT A.id_mrp_task_orders_request_asset,SUM(A.jumlah)AS jumlah,A.note,C.name AS nama_barang,E.title AS satuan"
//            . ",B.title AS title_spesifik,A.harga,A.id_mrp_task_orders_request_asset,E.group_satuan,A.harga AS harga_task_order_request"
//              . ",E.nilai"
//        . " FROM mrp_task_orders_request_asset AS A"
//        . " LEFT JOIN mrp_inventory_spesifik AS B ON A.id_mrp_inventory_spesifik = B.id_mrp_inventory_spesifik"
//        . " LEFT JOIN mrp_inventory_umum AS C ON B.id_mrp_inventory_umum = C.id_mrp_inventory_umum"
//        . " LEFT JOIN mrp_brand AS D ON B.id_mrp_brand = D.id_mrp_brand"
//        . " LEFT JOIN mrp_satuan AS E ON A.id_mrp_satuan = E.id_mrp_satuan"
//        . " LEFT JOIN mrp_supplier_inventory AS F ON B.id_mrp_inventory_spesifik = F.id_mrp_inventory_spesifik"
//        . " LEFT JOIN mrp_supplier G ON F.id_mrp_supplier = G.id_mrp_supplier "
//        . " LEFT JOIN mrp_task_orders_request AS H ON (A.id_mrp_task_orders = H.id_mrp_task_orders AND A.id_mrp_task_orders='{$id_mrp_task_orders}')"
//        . " LEFT JOIN mrp_request AS I ON H.id_mrp_request = I.id_mrp_request"
//        . " {$where}"
//        . " GROUP BY A.id_mrp_inventory_spesifik,I.create_by_users"
//        . " ORDER BY CONCAT(C.name,B.title) ASC"
//        );
        
       $where = "WHERE G.id_mrp_task_orders = {$id_mrp_task_orders} $id_mrp_po AND F.create_by_users ={$create_by_users} AND A.status >= 1 AND E.jumlah IS NOT NULL";
      
      
   $list = $this->global_models->get_query("SELECT B.name AS nama_barang,C.title AS brand,D.title AS satuan,"
        . "A.id_mrp_inventory_spesifik,A.title AS title_spesifik,"
//        . " SUM(E.jumlah) AS jumlah,"
        . " (Select SUM(Y.jumlah) "
        . " FROM mrp_request AS X "
        . " LEFT JOIN mrp_request_asset AS Y ON X.id_mrp_request = Y.id_mrp_request"
        . " LEFT JOIN mrp_task_orders_request AS Z ON X.id_mrp_request = Z.id_mrp_request"
        . " WHERE X.create_by_users  = '{$create_by_users}' AND Z.id_mrp_task_orders = {$id_mrp_task_orders} AND Y.id_mrp_inventory_spesifik=A.id_mrp_inventory_spesifik  AND X.status !=12 GROUP BY Y.id_mrp_inventory_spesifik,X.create_by_users) AS jumlah "
        . " ,F.id_mrp_request,F.status AS status_request,"
        . " G.id_mrp_task_orders,D.nilai,I.harga"
        . " FROM mrp_inventory_spesifik AS A"
        . " LEFT JOIN mrp_inventory_umum AS B ON A.id_mrp_inventory_umum = B.id_mrp_inventory_umum"
        . " LEFT JOIN mrp_brand AS C ON A.id_mrp_brand = C.id_mrp_brand"
        . " LEFT JOIN mrp_satuan AS D ON A.id_mrp_satuan = D.id_mrp_satuan"
        . " LEFT JOIN mrp_request_asset AS E ON A.id_mrp_inventory_spesifik = E.id_mrp_inventory_spesifik "
        . " LEFT JOIN mrp_request AS F ON E.id_mrp_request = F.id_mrp_request"
        . " LEFT JOIN mrp_task_orders_request AS G ON F.id_mrp_request = G.id_mrp_request"
        . " LEFT JOIN mrp_task_orders_request_asset AS H ON G.id_mrp_task_orders = H.id_mrp_task_orders"
        . " LEFT JOIN mrp_po_asset AS I ON E.id_mrp_inventory_spesifik = I.id_mrp_inventory_spesifik"
        . " {$where} "
        . " GROUP BY F.create_by_users,A.id_mrp_inventory_spesifik"
         );
//       (F.id_mrp_inventory_spesifik AND F.id_mrp_supplier = '{$detail[0]->id_mrp_supplier}')"
//        print $this->db->last_query();
//        die;
        
//       print "<pre>";
//      print_r($detail);
//      print "</pre>";
//      die;
     $data = array(
      'detail'     => $detail,
       'supplier'      => $supplier,
//      'kedua'     => $detail_array->tour->days." Hari / {$detail_array->tour->night} Malam - {$detail_array->tour->airlines}",
      'no'          =>  $detail[0]->code,
       'list'       =>  $list,
       'nama_user'  =>  $nama_user,
	'create_users'	=> $create_by_users,
       'signature'  =>  $detail[0]->signature,
       'tanggal_dikirim' => $detail[0]->tanggal_dikirim,
       'note'         => $detail[0]->note
       
    );
    $this->load->view('main-mrp/print-grouping', $data);
  
  }
  
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */