<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mrp extends MX_Controller {
    
  function __construct() {      
    $this->menu = $this->cek();
  }
  
  function request_pengadaan_atk() {
      
//      print "aa"; die;
      $css = "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/datatables/dataTables.bootstrap.css' rel='stylesheet' type='text/css' />"
      . "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/jQueryUI/jquery-ui-1.10.3.custom.min.css' rel='stylesheet' type='text/css' />"
//      . "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/select2.css' type='text/css' rel='stylesheet'>"
              ;
    
    $foot = ""
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/datatables/jquery.dataTables.js' type='text/javascript'></script>"
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/datatables/dataTables.bootstrap.js' type='text/javascript'></script>"
//      . "<script type='text/javascript' src='".base_url()."themes/".DEFAULTTHEMES."/js/select2.js'></script>"
//      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/jquery.ui.autocomplete.min.js' type='text/javascript'></script>"
            ;
	  
    $foot .= "<script>"
//          . "$('.dropdown2').select2();"
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
          . '"order": [[ 0, "desc" ]],'
//          . "'iDisplayLength': 20"
        . '});'
      
        . "ambil_data(table, 0,{$this->session->userdata("id")});"
      
      . '});'
      
      . 'function ambil_data(table, mulai,id_users){'
        . '$.post("'.site_url("mrp/mrp-ajax/get-mrp-request-pengadaan-atk").'/"+mulai+"/"+id_users, function(data){'
          . '$("#loader-page").show();'
          . 'var hasil = $.parseJSON(data);'
          . 'if(hasil.status == 2){'
                . 'if(hasil.flag == 2){'
                . '$("#loader-page").hide();'
                . '}'
            . 'table.fnAddData(hasil.hasil);'
            . 'ambil_data(table, hasil.start,id_users);'
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
    
    $this->template->build('main-mrp/request-pengadaan-atk', 
      array(
            'url'           => base_url()."themes/".DEFAULTTHEMES."/",
            'menu'          => "mrp/request-pengadaan-atk",
            'title'         => lang("Form Request Pengadaan ATK"),
            'foot'          => $foot,
            'css'           => $css,
//            'type'          => $type,
//            'jenis'         => $jenis,
//            'brand'         => $brand,
          ));
    $this->template
      ->set_layout('default')
      ->build('main-mrp/request-pengadaan-atk');
  }
  
  function request_pengadaan_cetakan() {
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
          . "$('.dropdown2').select2();"
        . "$(function() {"
            . "$( '#brand' ).autocomplete({"
              . "source: '".site_url("mrp/mrp-ajax/brand")."',"
              . "minLength: 1,"
              . "search  : function(){ $(this).addClass('working');},"
              . "open    : function(){ $(this).removeClass('working');},"
              . "select: function( event, ui ) {"
                . "$('#id_brand').val(ui.item.id);"
              . "}"
            . "});"
        . "});"
      . '$(function() {'
        . 'var table = '
        . '$("#tableboxy").dataTable({'
          . '"order": [[ 0, "desc" ]],'
//          . "'iDisplayLength': 20"
        . '});'
      
        . "ambil_data(table, 0,{$this->session->userdata("id")});"
      
      . '});'
      
      . 'function ambil_data(table, mulai,id_users){'
        . '$.post("'.site_url("mrp/mrp-ajax/get-mrp-request-pengadaan-cetakan").'/"+mulai+"/"+id_users, function(data){'
          . '$("#loader-page").show();'
          . 'var hasil = $.parseJSON(data);'
          . 'if(hasil.status == 2){'
                . 'if(hasil.flag == 2){'
                . '$("#loader-page").hide();'
                . '}'
            . 'table.fnAddData(hasil.hasil);'
            . 'ambil_data(table, hasil.start,id_users);'
          . '}'
          . 'else{'
            . '$("#loader-page").hide();'

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
    
    $this->template->build('main-mrp/request-pengadaan-cetakan', 
      array(
            'url'           => base_url()."themes/".DEFAULTTHEMES."/",
            'menu'          => "mrp/request-pengadaan-cetakan",
            'title'         => lang("Form Request Pengadaan Cetakan"),
            'foot'          => $foot,
            'css'           => $css,
//            'type'          => $type,
//            'jenis'         => $jenis,
//            'brand'         => $brand,
          ));
    $this->template
      ->set_layout('default')
      ->build('main-mrp/request-pengadaan-cetakan');
  }
  
  public function add_request_pengadaan_atk($id_mrp_request = 0){

    if(!$this->input->post(NULL)){
        
    $css = "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/datatables/dataTables.bootstrap.css' rel='stylesheet' type='text/css' />"
      . "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/jQueryUI/jquery-ui-1.10.3.custom.min.css' rel='stylesheet' type='text/css' />"
      . "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/select2.css' type='text/css' rel='stylesheet'>";
      $url = base_url('mrp/request-pengadaan-atk');
      $url2 = base_url("mrp/add-request-pengadaan-atk/{$id_mrp_request}");
    $foot = ""
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/datatables/jquery.dataTables.js' type='text/javascript'></script>"
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/datatables/dataTables.bootstrap.js' type='text/javascript'></script>"
      . "<script type='text/javascript' src='".base_url()."themes/".DEFAULTTHEMES."/js/select2.js'></script>"
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/jquery.ui.autocomplete.min.js' type='text/javascript'></script>";
	
    if($this->nbscache->get_olahan("permission", $this->session->userdata("id_privilege"), "search-user-ro", "edit") !== FALSE){
        $dt_status = 1;
    }else{
        $dt_status = 2;
    }
    $foot .= "<script>"
        
        . "$(function() {"
            . "$( '#users' ).autocomplete({"

                  . "source: '".site_url("mrp/mrp-ajax/get-pegawai/{$dt_status}")."',"
                  . "minLength: 1,"
                  . "search  : function(){ $(this).addClass('working');},"
                  . "open    : function(){ $(this).removeClass('working');},"
                  . "select: function( event, ui ) {"
                    . "$('#id_users').val(ui.item.id);"
                  . "}"
                . "});"
            . "$( '#users_penerima' ).autocomplete({"

                  . "source: '".site_url("mrp/mrp-ajax/get-users-penerima/{$dt_status}")."',"
                  . "minLength: 1,"
                  . "search  : function(){ $(this).addClass('working');},"
                  . "open    : function(){ $(this).removeClass('working');},"
                  . "select: function( event, ui ) {"
                    . "$('#id_users_penerima').val(ui.item.id);"
                  . "}"
                . "});"              
            
            . "$('#btn-task-orders').click(function(){"
            . "$('#btn-task-orders').hide();"
            . "$('#img-5').show();"
            . "var note2   = $('#note_atk').val();"
            . "var id_hr_pegawai2   = $('#id_users').val();"
            . "var id_receiver2   = $('#id_users_penerima').val();"                 
            . " var id_hr_pegawai = (isNaN(id_hr_pegawai2)) ? 0 : id_hr_pegawai2;"
                                  
            . "var oTable = $('#tableboxy').dataTable();"
            . "var aData = [];"
            . "var rowcollection =  oTable.$('.id_spesifik', {'page': 'all'});"
            . "rowcollection.each(function(index,elem){"
            . "var value_id_spesifik = parseInt($(elem).val());"
            . " var hasil = (isNaN(value_id_spesifik)) ? 0 : value_id_spesifik;"
            . "aData.push( hasil );"
            . "});"
            
            . "var aData1 = [];"
            . "var rowcollection1 =  oTable.$('.jumlah', {'page': 'all'});"
            . "rowcollection1.each(function(index,elem){"
            . "var value_jumlah = parseInt($(elem).val());"
            . " var hasil2 = (isNaN(value_jumlah)) ? 0 : value_jumlah;"
            . "aData1.push( hasil2 );"
            . "});"
                        ."var dataString2 = 'id_spesifik='+ aData +'&jumlah='+ aData1+'&note=' + note2 +'&id_hr_pegawai='+id_hr_pegawai +'&id_receiver='+ id_receiver2 +'&id_mrp_request='+ {$id_mrp_request};"
                       
                        ."$.ajax({"
                        ."type : 'POST',"
                        ."url : '".site_url("mrp/mrp-ajax/insert-form-mrp-request-pengadaan/2")."',"
                        ."data: dataString2,"
                        ."dataType : 'html',"
                        ."success: function(data) {"
                            ."window.location ='{$url}'"
                        ."},"
                     ."});"
            . "});"
            
            . "$('#btn-draft').click(function(){"
                . "$('#btn-draft').hide();"
                . "$('#img-5').show();"
            . "var note2   = $('#note_atk').val();"
            . "var id_hr_pegawai2   = $('#id_users').val();"
            . "var id_receiver2   = $('#id_users_penerima').val();"                          
            . " var id_hr_pegawai = (isNaN(id_hr_pegawai2)) ? 0 : id_hr_pegawai2;"
                                          
            . "var oTable = $('#tableboxy').dataTable();"
            . "var aData = [];"
            . "var rowcollection =  oTable.$('.id_spesifik', {'page': 'all'});"
            . "rowcollection.each(function(index,elem){"
            . "var value_id_spesifik = parseInt($(elem).val());"
            . " var hasil = (isNaN(value_id_spesifik)) ? 0 : value_id_spesifik;"
            . "aData.push( hasil );"
            . "});"
            
            . "var aData1 = [];"
            . "var rowcollection1 =  oTable.$('.jumlah', {'page': 'all'});"
            . "rowcollection1.each(function(index,elem){"
            . "var value_jumlah = parseInt($(elem).val());"
            . " var hasil2 = (isNaN(value_jumlah)) ? 0 : value_jumlah;"
            . "aData1.push( hasil2 );"
            . "});"
                        ."var dataString2 = 'id_spesifik='+ aData +'&jumlah='+ aData1+'&note=' + note2+'&id_hr_pegawai='+ id_hr_pegawai +'&id_receiver='+ id_receiver2 +'&id_mrp_request='+ {$id_mrp_request};"
                        ."$.ajax({"
                        ."type : 'POST',"
                        ."url : '".site_url("mrp/mrp-ajax/draft-form-mrp-request-pengadaan/2")."',"
                        ."data: dataString2,"
                        ."dataType : 'html',"
                        ."success: function(data) {"
                            ."window.location ='{$url}'"
                        ."},"
                     ."});"
            . "});"
            
            . "$('#btn-save').click(function(){"
                . "$('#btn-save').hide();"
                . "$('#img-5').show();"
            . "var note2   = $('#note_atk').val();"
            . "var id_hr_pegawai2   = $('#id_users').val();"
            . "var id_receiver2   = $('#id_users_penerima').val();"                          
            . " var id_hr_pegawai = (isNaN(id_hr_pegawai2)) ? 0 : id_hr_pegawai2;"
                                                           
            . "var oTable = $('#tableboxy').dataTable();"
            . "var aData = [];"
            . "var rowcollection =  oTable.$('.id_spesifik', {'page': 'all'});"
            . "rowcollection.each(function(index,elem){"
            . "var value_id_spesifik = parseInt($(elem).val());"
            . " var hasil = (isNaN(value_id_spesifik)) ? 0 : value_id_spesifik;"
            . "aData.push( hasil );"
            . "});"
            
            . "var aData1 = [];"
            . "var rowcollection1 =  oTable.$('.jumlah', {'page': 'all'});"
            . "rowcollection1.each(function(index,elem){"
            . "var value_jumlah = parseInt($(elem).val());"
            . " var hasil2 = (isNaN(value_jumlah)) ? 0 : value_jumlah;"
            . "aData1.push( hasil2 );"
            . "});"
                        ."var dataString2 = 'id_spesifik='+ aData +'&jumlah='+ aData1+'&note=' + note2+'&id_hr_pegawai='+ id_hr_pegawai +'&id_receiver='+ id_receiver2 +'&id_mrp_request='+ {$id_mrp_request};"
                        ."$.ajax({"
                        ."type : 'POST',"
                        ."url : '".site_url("mrp/mrp-ajax/update-form-mrp-request-pengadaan/2")."',"
                        ."data: dataString2,"
                        ."dataType : 'html',"
                        ."success: function(data) {"
                            ."window.location ='{$url2}'"
                        ."},"
                     ."});"
            . "});"
                                    
            . "$('#btn-save2').click(function(){"
              . "$('#btn-save2').hide();"
                . "$('#img-5').show();"
            . "var note2   = $('#note_atk').val();"
            . "var id_hr_pegawai2   = $('#id_users').val();"
            . "var id_receiver2   = $('#id_users_penerima').val();"                        
            . " var id_hr_pegawai = (isNaN(id_hr_pegawai2)) ? 0 : id_hr_pegawai2;"
                                                           
            . "var oTable = $('#tableboxy2').dataTable();"
            . "var aData = [];"
            . "var rowcollection =  oTable.$('.id_spesifik', {'page': 'all'});"
            . "rowcollection.each(function(index,elem){"
            . "var value_id_spesifik = parseInt($(elem).val());"
            . " var hasil = (isNaN(value_id_spesifik)) ? 0 : value_id_spesifik;"
            . "aData.push( hasil );"
            . "});"
            
            . "var aData1 = [];"
            . "var rowcollection1 =  oTable.$('.jumlah', {'page': 'all'});"
            . "rowcollection1.each(function(index,elem){"
            . "var value_jumlah = parseInt($(elem).val());"
            . " var hasil2 = (isNaN(value_jumlah)) ? 0 : value_jumlah;"
            . "aData1.push( hasil2 );"
            . "});"
                        ."var dataString2 = 'id_spesifik='+ aData +'&jumlah='+ aData1+'&note=' + note2+'&id_hr_pegawai='+ id_hr_pegawai +'&id_receiver='+ id_receiver2 +'&id_mrp_request='+ {$id_mrp_request};"
                        ."$.ajax({"
                        ."type : 'POST',"
                        ."url : '".site_url("mrp/mrp-ajax/update-form-mrp-request-pengadaan/2")."',"
                        ."data: dataString2,"
                        ."dataType : 'html',"
                        ."success: function(data) {"
                            ."window.location ='{$url2}'"
                        ."},"
                     ."});"
            . "});"

        . "});"
                                    
       
                                    
      . '$(function() {'
         
                                    
            . "$('#dt-atk').click(function(){"
                . 'var table = '
                    . '$("#tableboxy2").dataTable({'
                      . '"order": [[ 0, "asc" ]],'
                      . '"bDestroy": true'              
            //          . "'iDisplayLength': 100"
            //            . "'page': 'all'"
                    . '});'
                    . "table.fnClearTable();"
                    . 'ambil_data2(table, 0);'
      
            . "});"
        . 'var table = '
        . '$("#tableboxy").dataTable({'
          . '"order": [[ 0, "asc" ]],'
//          . "'iDisplayLength': 100"
//            . "'page': 'all'"
        . '});'
      
        . 'ambil_data(table, 0);'
      
      . '});'
      
      . 'function ambil_data(table, mulai){'
        . '$.post("'.site_url("mrp/mrp-ajax/get-form-mrp-request-pengadaan/{$id_mrp_request}/{$this->session->userdata("id")}/").'/"+mulai, function(data){'
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
      . 'function ambil_data2(table, mulai){'
        . '$.post("'.site_url("mrp/mrp-ajax/get-list-form-mrp-request-pengadaan/{$id_mrp_request}/{$this->session->userdata("id")}/").'/"+mulai, function(data){'
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
      
      . "function delete_request_pengadaan(id){"
         . "$('#del_'+ id).hide();"
         . "$('#img-page-'+ id).show();"
            ."var dataString2 = 'id_mrp_request_asset='+ id;"
               ."$.ajax({"
               ."type : 'POST',"
               ."url : '".site_url("mrp/mrp-ajax/delete-request-pengadaan/")."',"
               ."data: dataString2,"
               ."dataType : 'html',"
               ."success: function(data) {"
                       . 'var table = '
                       . '$("#tableboxy").dataTable({'
                       . '"order": [[ 0, "asc" ]],'
                       . '"bDestroy": true'
                       . '});'
                       . "table.fnClearTable();"
                        . 'ambil_data(table, 0);'

               ."},"
            ."});"
      . "}"
      
	  . "</script>";   

//       $list = $this->global_models->get("mrp_request", array("id_mrp_request" => $id_mrp_request)); 
         
       $list = $this->global_models->get_query("SELECT A.id_mrp_request,A.status,A.note,A.create_by_users,A.id_hr_pegawai,A.user_pegawai_receiver,"
        . "B.id_users,C.name,C.email,D.title AS name_organisasi,E.id_users AS pegawai_receiver,F.name AS name_receiver,F.email AS email_receiver"
        . " FROM mrp_request AS A"
        . " LEFT JOIN hr_pegawai AS B ON A.id_hr_pegawai = B.id_hr_pegawai"
        . " LEFT JOIN m_users AS C ON B.id_users = C.id_users"
        . " LEFT JOIN hr_master_organisasi AS D ON B.id_hr_master_organisasi = D.id_hr_master_organisasi"
        . " LEFT JOIN hr_pegawai AS E ON A.user_pegawai_receiver = E.id_hr_pegawai"
        . " LEFT JOIN m_users AS F ON E.id_users = F.id_users"
        . " WHERE A.id_mrp_request='{$id_mrp_request}'"
        );
//        print $this->db->last_query();
//        die;
      $this->template->build("main-mrp/request-atk/view-request-atk", 
        array(
              'url'         => base_url()."themes/".DEFAULTTHEMES."/",
              'menu'        => 'mrp/request-pengadaan-atk',
              'title'       => lang("mrp_add_request_pengadaan_atk"),
              'list'        => $list,
              'breadcrumb'  => array(
                    "mrp_request_pengadaan_atk"  => "mrp/request-pengadaan-atk"
                ),
              'css'         => $css,
              'foot'        => $foot,
              'img'       => base_url()."themes/".DEFAULTTHEMES."/img/ajax-loader.gif"
//              'items'       => $items
            ));
      $this->template
        ->set_layout('form')
        ->build("main-mrp/request-atk/view-request-atk");
    }
    else{
      $pst = $this->input->post(NULL);
     
      if($pst["btn_approval"] == "approve"){
          $kirim = array(
            "status"                    => 3,
            "user_approval"             => $this->session->userdata("id"),  
            "update_by_users"           => $this->session->userdata("id"),
            "update_date"               => date("Y-m-d H:i:s")
        );
       $this->global_models->update("mrp_request", array("id_mrp_request" => $id_mrp_request),$kirim);
       
       $kirim = array(
            "status"                    => 3,
            "update_by_users"           => $this->session->userdata("id"),
            "update_date"               => date("Y-m-d H:i:s")
        );
       $this->global_models->update("mrp_request_asset", array("id_mrp_request" => $id_mrp_request),$kirim);
     
      }
      redirect("mrp/request-pengadaan-atk");
    }
  }
  
  public function add_request_pengadaan_cetakan($id_mrp_request = 0){
      
     if(!$this->input->post(NULL)){
        
    $css = "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/datatables/dataTables.bootstrap.css' rel='stylesheet' type='text/css' />"
      . "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/jQueryUI/jquery-ui-1.10.3.custom.min.css' rel='stylesheet' type='text/css' />"
      . "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/select2.css' type='text/css' rel='stylesheet'>";
      $url = base_url('mrp/request-pengadaan-cetakan');
      $url2 = base_url("mrp/add-request-pengadaan-cetakan/{$id_mrp_request}");
    $foot = ""
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/datatables/jquery.dataTables.js' type='text/javascript'></script>"
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/datatables/dataTables.bootstrap.js' type='text/javascript'></script>"
      . "<script type='text/javascript' src='".base_url()."themes/".DEFAULTTHEMES."/js/select2.js'></script>"
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/jquery.ui.autocomplete.min.js' type='text/javascript'></script>";
	
     if($this->nbscache->get_olahan("permission", $this->session->userdata("id_privilege"), "search-user-ro", "edit") !== FALSE){
        $dt_status = 1;
    }else{
        $dt_status = 2;
    }
    
    $foot .= "<script>"
        
        . "$(function() {"
            
            . "$( '#users' ).autocomplete({"
                  . "source: '".site_url("mrp/mrp-ajax/get-pegawai/{$dt_status}")."',"
                  . "minLength: 1,"
                  . "search  : function(){ $(this).addClass('working');},"
                  . "open    : function(){ $(this).removeClass('working');},"
                  . "select: function( event, ui ) {"
                    . "$('#id_users').val(ui.item.id);"
                  . "}"
                . "});"
                          
            . "$('#btn-task-orders').click(function(){"
//            . "alert('11');"
//            ."window.location ='{$url}'"
                . "$('#btn-task-orders').hide();"
                . "$('#img-5').show();"
            . "var note2   = $('#note_cetakan').val();"
             . "var id_hr_pegawai2   = $('#id_users').val();"
            . " var id_hr_pegawai = (isNaN(id_hr_pegawai2)) ? 0 : id_hr_pegawai2;"              
//            . "alert('cc');"
            . "var oTable = $('#tableboxy').dataTable();"
            . "var aData = [];"
            . "var rowcollection =  oTable.$('.id_spesifik', {'page': 'all'});"
            . "rowcollection.each(function(index,elem){"
            . "var value_id_spesifik = parseInt($(elem).val());"
            . " var hasil = (isNaN(value_id_spesifik)) ? 0 : value_id_spesifik;"
            . "aData.push( hasil );"
            . "});"
            
            . "var aData1 = [];"
            . "var rowcollection1 =  oTable.$('.jumlah', {'page': 'all'});"
            . "rowcollection1.each(function(index,elem){"
            . "var value_jumlah = parseInt($(elem).val());"
            . " var hasil2 = (isNaN(value_jumlah)) ? 0 : value_jumlah;"
            . "aData1.push( hasil2 );"
            . "});"
                        ."var dataString2 = 'id_spesifik='+ aData +'&jumlah='+ aData1+'&note=' + note2 +'&id_hr_pegawai='+id_hr_pegawai +'&id_mrp_request='+ {$id_mrp_request};"
                        ."$.ajax({"
                        ."type : 'POST',"
                        ."url : '".site_url("mrp/mrp-ajax/insert-form-mrp-request-pengadaan/1")."',"
                        ."data: dataString2,"
                        ."dataType : 'html',"
                        ."success: function(data) {"
//                          . "alert('aa');"
                            ."window.location ='{$url}'"
                        ."},"
                     ."});"
            . "});"
                                    
            . "$('#btn-draft').click(function(){"
            . "$('#btn-draft').hide();"
            . "$('#img-5').show();"
            . "var note2   = $('#note_cetakan').val();"
            . "var id_hr_pegawai2   = $('#id_users').val();"
            . " var id_hr_pegawai = (isNaN(id_hr_pegawai2)) ? 0 : id_hr_pegawai2;"                        
            . "var oTable = $('#tableboxy').dataTable();"
            . "var aData = [];"
            . "var rowcollection =  oTable.$('.id_spesifik', {'page': 'all'});"
            . "rowcollection.each(function(index,elem){"
            . "var value_id_spesifik = parseInt($(elem).val());"
            . " var hasil = (isNaN(value_id_spesifik)) ? 0 : value_id_spesifik;"
            . "aData.push( hasil );"
            . "});"
            
            . "var aData1 = [];"
            . "var rowcollection1 =  oTable.$('.jumlah', {'page': 'all'});"
            . "rowcollection1.each(function(index,elem){"
            . "var value_jumlah = parseInt($(elem).val());"
            . " var hasil2 = (isNaN(value_jumlah)) ? 0 : value_jumlah;"
            . "aData1.push( hasil2 );"
            . "});"
                        ."var dataString2 = 'id_spesifik='+ aData +'&jumlah='+ aData1+'&note=' + note2+'&id_hr_pegawai='+ id_hr_pegawai+'&id_mrp_request='+ {$id_mrp_request};"
                        ."$.ajax({"
                        ."type : 'POST',"
                        ."url : '".site_url("mrp/mrp-ajax/draft-form-mrp-request-pengadaan/1")."',"
                        ."data: dataString2,"
                        ."dataType : 'html',"
                        ."success: function(data) {"
                            ."window.location ='{$url}'"
                        ."},"
                     ."});"
            . "});"
                                    
            . "$('#btn-save').click(function(){"
                . "$('#btn-save').hide();"
                . "$('#img-5').show();"
            . "var note2   = $('#note_cetakan').val();"
            . "var oTable = $('#tableboxy').dataTable();"
            . "var aData = [];"
            . "var rowcollection =  oTable.$('.id_spesifik', {'page': 'all'});"
            . "rowcollection.each(function(index,elem){"
            . "var value_id_spesifik = parseInt($(elem).val());"
            . " var hasil = (isNaN(value_id_spesifik)) ? 0 : value_id_spesifik;"
            . "aData.push( hasil );"
            . "});"
            
            . "var aData1 = [];"
            . "var rowcollection1 =  oTable.$('.jumlah', {'page': 'all'});"
            . "rowcollection1.each(function(index,elem){"
            . "var value_jumlah = parseInt($(elem).val());"
            . " var hasil2 = (isNaN(value_jumlah)) ? 0 : value_jumlah;"
            . "aData1.push( hasil2 );"
            . "});"
                        ."var dataString2 = 'id_spesifik='+ aData +'&jumlah='+ aData1+'&note=' + note2+'&id_mrp_request='+ {$id_mrp_request};"
                        ."$.ajax({"
                        ."type : 'POST',"
                        ."url : '".site_url("mrp/mrp-ajax/update-form-mrp-request-pengadaan/1")."',"
                        ."data: dataString2,"
                        ."dataType : 'html',"
                        ."success: function(data) {"
                            ."window.location ='{$url2}'"
                        ."},"
                     ."});"
            . "});"
            
            . "$('#btn-save2').click(function(){"
                . "$('#btn-save2').hide();"
                . "$('#img-cetakan').show();"
            . "var note2   = $('#note_cetakan').val();"
            . "var oTable = $('#tableboxy2').dataTable();"
            . "var aData = [];"
            . "var rowcollection =  oTable.$('.id_spesifik', {'page': 'all'});"
            . "rowcollection.each(function(index,elem){"
            . "var value_id_spesifik = parseInt($(elem).val());"
            . " var hasil = (isNaN(value_id_spesifik)) ? 0 : value_id_spesifik;"
            . "aData.push( hasil );"
            . "});"
            
            . "var aData1 = [];"
            . "var rowcollection1 =  oTable.$('.jumlah', {'page': 'all'});"
            . "rowcollection1.each(function(index,elem){"
            . "var value_jumlah = parseInt($(elem).val());"
            . " var hasil2 = (isNaN(value_jumlah)) ? 0 : value_jumlah;"
            . "aData1.push( hasil2 );"
            . "});"
                        ."var dataString2 = 'id_spesifik='+ aData +'&jumlah='+ aData1+'&note=' + note2+'&id_mrp_request='+ {$id_mrp_request};"
                        ."$.ajax({"
                        ."type : 'POST',"
                        ."url : '".site_url("mrp/mrp-ajax/update-form-mrp-request-pengadaan/1")."',"
                        ."data: dataString2,"
                        ."dataType : 'html',"
                        ."success: function(data) {"
                            ."window.location ='{$url2}'"
                        ."},"
                     ."});"
            . "});"
                                    
                                    
        . "});"
      . '$(function() {'
        . "$('#dt-cetakan').click(function(){"
        . 'var table = '
            . '$("#tableboxy2").dataTable({'
              . '"order": [[ 0, "asc" ]],'
              . '"bDestroy": true'
            . '});'
            . "table.fnClearTable();"
            . 'ambil_data2(table, 0);'
      
        . "});"
        . 'var table = '
        . '$("#tableboxy").dataTable({'
          . '"order": [[ 0, "asc" ]],'
//          . "'iDisplayLength': 100"
//            . "'page': 'all'"
        . '});'
        . 'ambil_data(table, 0);'
      . '});'
      
      . 'function ambil_data(table, mulai){'
        . '$.post("'.site_url("mrp/mrp-ajax/get-form-mrp-request-pengadaan-cetakan/{$id_mrp_request}/{$this->session->userdata("id")}/").'/"+mulai, function(data){'
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
      
      . 'function ambil_data2(table, mulai){'
        . '$.post("'.site_url("mrp/mrp-ajax/get-list-form-mrp-request-pengadaan-cetakan/{$id_mrp_request}/{$this->session->userdata("id")}/").'/"+mulai, function(data){'
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
      
      . "function delete_request_pengadaan(id){"
         . "$('#del_'+ id).hide();"
         . "$('#img-page-'+ id).show();"
            ."var dataString2 = 'id_mrp_request_asset='+ id;"
               ."$.ajax({"
               ."type : 'POST',"
               ."url : '".site_url("mrp/mrp-ajax/delete-request-pengadaan/")."',"
               ."data: dataString2,"
               ."dataType : 'html',"
               ."success: function(data) {"
                       . 'var table = '
                       . '$("#tableboxy").dataTable({'
                       . '"order": [[ 0, "asc" ]],'
                       . '"bDestroy": true'
                       . '});'
                       . "table.fnClearTable();"
                        . 'ambil_data(table, 0);'

               ."},"
            ."});"
      . "}"
                
	  . "</script>";   
        
      $list = $this->global_models->get_query("SELECT A.id_mrp_request,A.status,A.note,A.create_by_users,A.id_hr_pegawai,"
        . "B.id_users,C.name,C.email,D.title AS name_organisasi"
        . " FROM mrp_request AS A"
        . " LEFT JOIN hr_pegawai AS B ON A.id_hr_pegawai = B.id_hr_pegawai"
        . " LEFT JOIN m_users AS C ON B.id_users = C.id_users"
        . " LEFT JOIN hr_master_organisasi AS D ON B.id_hr_master_organisasi = D.id_hr_master_organisasi"
        . " WHERE A.id_mrp_request='{$id_mrp_request}'"
        );
        
           
      $this->template->build("main-mrp/request-cetakan/view-request-cetakan", 
        array(
              'url'         => base_url()."themes/".DEFAULTTHEMES."/",
              'menu'        => 'mrp/request-pengadaan-cetakan',
              'title'       => lang("mrp_add_request_pengadaan_cetakan"),
              'id_mrp_request'  => $id_mrp_request,
              'list'        => $list,
              'breadcrumb'  => array(
                    "mrp_request_pengadaan_cetakan"  => "mrp/request-pengadaan-cetakan"
                ),
              'css'         => $css,
              'foot'        => $foot,
              'img'       => base_url()."themes/".DEFAULTTHEMES."/img/ajax-loader.gif"
//              'items'       => $items
            ));
      $this->template
        ->set_layout('form')
        ->build("main-mrp/request-cetakan/view-request-cetakan");
    }
    else{
      $pst = $this->input->post(NULL);
     
      if($pst["btn_approval"] == "approve"){
          $kirim = array(
            "status"                    => 3,
            "user_approval"             => $this->session->userdata("id"),    
            "update_by_users"           => $this->session->userdata("id"),
            "update_date"               => date("Y-m-d H:i:s")
        );
        $this->global_models->update("mrp_request", array("id_mrp_request" => $id_mrp_request),$kirim);
        
        $kirim = array(
            "status"                    => 3,
            "update_by_users"           => $this->session->userdata("id"),
            "update_date"               => date("Y-m-d H:i:s")
        );
       $this->global_models->update("mrp_request_asset", array("id_mrp_request" => $id_mrp_request),$kirim);
     
      }
      redirect("mrp/request-pengadaan-cetakan");
    }
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
    $url = base_url('mrp/task-orders');
    $url2 = base_url('mrp/add-task-orders/'.$id_mrp_task_orders); 
    $foot .= "<script>"
        . "$(function() {"
            . "$('#dtcheck').click(function(){"
                . "$(':checkbox.dt_id').prop('checked', this.checked); "
                . "});"
            
            . "$('#btn-closed-to').click(function(){"
              ."var dataString2 = 'id_mrp_task_orders='+ {$id_mrp_task_orders};"
                        ."$.ajax({"
                            ."type : 'POST',"
                            ."url : '".site_url("mrp/mrp-ajax/closed-task-orders")."',"
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
                . "var note2   = $('#dt_note').val();"
                . "var title2   = $('#dt_title').val();"
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
                        ."url : '".site_url("mrp/mrp-ajax/insert-task-orders")."',"
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
                . "var note2   = $('#dt_note').val();"
                . "var title2   = $('#dt_title').val();"
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
                        ."url : '".site_url("mrp/mrp-ajax/insert-task-orders")."',"
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
      
      
       . 'function ambil_data(table, mulai){'
        . '$.post("'.site_url("mrp/mrp-ajax/get-task-mrp-request-pengadaan/2").'/"+mulai, function(data){'
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
                                    
      . 'function ambil_data2(table, mulai){'
        . '$.post("'.site_url("mrp/mrp-ajax/get-task-orders-request/{$id_mrp_task_orders}").'/"+mulai, function(data){'
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
        . '$.post("'.site_url("mrp/mrp-ajax/get-task-orders-request-asset/{$id_mrp_task_orders}").'/"+mulai, function(data){'
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
        . '$.post("'.site_url("mrp/mrp-ajax/get-grouping-orders-request/{$id_mrp_task_orders}").'/"+mulai, function(data){'
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
                
      . 'function ambil_data_po(table, mulai,id_users){'
       . '$.post("'.site_url("mrp/mrp-ajax/get-view-add-to-list-po/{$id_mrp_task_orders}").'/"+mulai+"/"+id_users, function(data){'
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
                        ."url : '".site_url("mrp/mrp-ajax/delete-task-orders-request/{$id_mrp_task_orders}")."',"
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
     
	  . "</script>";   
       $mrp_task_orders = $this->global_models->get("mrp_task_orders", array("id_mrp_task_orders" => $id_mrp_task_orders)); 
      
       $status = array( 1=> "<span class='label bg-orange'>Create</span>",2 => "<span class='label bg-green'>Proses PO</span>",
           3 => "<span class='label bg-green'>Approved PO</span>", 4 => "<span class='label bg-green'>Sent PO</span>", 9 =>"<span class='label bg-green'>Closed Task Orders</span>");
    
       $this->template->build("main-mrp/add-task/view-add-task-orders", 
        array(
              'url'         => base_url()."themes/".DEFAULTTHEMES."/",
              'menu'        => 'mrp/task-orders',
              'title'       => lang("mrp_add_task_orders"),
              'id_mrp_task_orders'  => $id_mrp_task_orders,
              'breadcrumb'  => array(
                    "mrp_request_pengadaan"  => "mrp/task-orders"
                ),
              'css'         => $css,
              'foot'        => $foot,
              'dt_status'   => $status,
              'detail'      => $mrp_task_orders,
              'img'       => base_url()."themes/".DEFAULTTHEMES."/img/ajax-loader.gif"
//              'items'       => $items
            ));
      $this->template
        ->set_layout('form')
        ->build("main-mrp/add-task/view-add-task-orders");
    } 
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
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/jquery.ui.autocomplete.min.js' type='text/javascript'></script>"
      ;
    $url = base_url('mrp/task-orders');
    $url2 = base_url('mrp/add-task-orders/'.$id_mrp_task_orders); 
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
                . "var note2   = $('#dt_note').val();"
                . "var title2   = $('#dt_title').val();"
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
                        ."url : '".site_url("mrp/mrp-ajax/insert-task-orders")."',"
                        ."data: dataString2,"
                        ."dataType : 'html',"
                        ."success: function(data) {"
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
                            $foot .= "window.location.href ='{$url}'";
                        }
                        $foot .=  "},"
                     ."});"
            . "});"
        . "});"
      . '$(function() {'
        . 'var table = '
        . '$("#tableboxy").dataTable({'
          . '"order": [[ 0, "asc" ]],'
        . '});'
        . 'ambil_data(table, 0);'
      . '});'
           
       . 'function ambil_data(table, mulai){'
        . '$.post("'.site_url("mrp/mrp-ajax/get-task-mrp-request-pengadaan/1").'/"+mulai, function(data){'
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
      $this->template->build("main-mrp/create-task-orders", 
        array(
              'url'         => base_url()."themes/".DEFAULTTHEMES."/",
              'menu'        => 'mrp/task-orders',
              'title'       => lang("create_task_orders"),
              'id_mrp_task_orders'  => $id_mrp_task_orders,
              'breadcrumb'  => array(
                    "mrp_task_orders"  => "mrp/task-orders"
                ),
              'css'         => $css,
              'foot'        => $foot,
              'detail'      => $mrp_task_orders,
              'img'       => base_url()."themes/".DEFAULTTHEMES."/img/ajax-loader.gif"
//              'items'       => $items
            ));
      $this->template
        ->set_layout('form')
        ->build("main-mrp/create-task-orders");
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
          . '"order": [[ 1, "desc" ]],'
//          . "'iDisplayLength': 20"
        . '});'
      
        . 'ambil_data(table, 0);'
      
      . '});'
      
      . 'function ambil_data(table, mulai){'
        . '$.post("'.site_url("mrp/mrp-ajax/get-mrp-task-orders").'/"+mulai, function(data){'
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
    
    $this->template->build('main-mrp/task-orders', 
      array(
            'url'           => base_url()."themes/".DEFAULTTHEMES."/",
            'menu'          => "mrp/task-orders",
            'title'         => lang("Task Orders"),
            'foot'          => $foot,
            'css'           => $css,
//            'type'          => $type,
//            'jenis'         => $jenis,
//            'brand'         => $brand,
          ));
    $this->template
      ->set_layout('default')
      ->build('main-mrp/task-orders');
  }
  
   public function po($id_mrp_task_orders = 0){

    if(!$this->input->post(NULL)){
        
    $kirim3 = array(
            "status"                        => 1,
            "update_by_users"               => $this->session->userdata("id"),
            "update_date"                   => date("Y-m-d H:i:s")
            );
    $this->global_models->update("mrp_po_asset", array("id_mrp_task_orders" => $id_mrp_task_orders, "status" => "2"),$kirim3);

    $css = "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/datatables/dataTables.bootstrap.css' rel='stylesheet' type='text/css' />"
      . "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/jQueryUI/jquery-ui-1.10.3.custom.min.css' rel='stylesheet' type='text/css' />";
      $url = base_url('mrp/list-po');
    $foot = ""
            
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/datatables/jquery.dataTables.js' type='text/javascript'></script>"
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/datatables/dataTables.bootstrap.js' type='text/javascript'></script>"
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/jquery.ui.autocomplete.min.js' type='text/javascript'></script>";
	
    $foot .= "<script>"
                               
    . '$(function() {'
            
    . "$( '#supplier' ).autocomplete({"
         . "source: '".site_url("mrp/mrp-ajax/get-supplier/{$id_mrp_task_orders}")."',"
         . "minLength: 1,"
         . "search  : function(){ $(this).addClass('working');},"
         . "open    : function(){ $(this).removeClass('working');},"
         . "select: function( event, ui ) {"
           . "$('#id_supplier').val(ui.item.id);"
           . "var supplier = $('#id_supplier').val();"
            . 'var table = '
            . '$("#tableboxy2").dataTable({'
            . '"order": [[ 0, "asc" ]],'
            . '"bDestroy": true'
            . '});'
            . "table.fnClearTable();"
            . 'ambil_data(table, 0,supplier);'
         . "}"
       . "});"
            
       . "$( '#company' ).autocomplete({"
         . "source: '".site_url("mrp/mrp-ajax/get-company")."',"
         . "minLength: 1,"
         . "search  : function(){ $(this).addClass('working');},"
         . "open    : function(){ $(this).removeClass('working');},"
         . "select: function( event, ui ) {"
           . "$('#id_company').val(ui.item.id);"
         . "}"
       . "});"     
            
//        . "$( '#barang' ).autocomplete({"
//         . "source: '".site_url("mrp/mrp-ajax/get-po-inventory-spesifik")."',"
//         . "minLength: 1,"
//         . "search  : function(){ $(this).addClass('working');},"
//         . "open    : function(){ $(this).removeClass('working');},"
//         . "select: function( event, ui ) {"
//           . "$('#id_barang').val(ui.item.id);"
//            . "$('#id_satuan').val(ui.item.id_satuan);"
////            . "alert($('#id_barang').val());"
//         . "}"
//       . "});"
            
       . "$('#btn-proses').click(function(){"
            . "var oTable = $('#tableboxy2').dataTable();"
            . "var aData = [];"
            . "var rowcollection =  oTable.$('.mrp_task_orders_request_asset', {'page': 'all'});"
            . "rowcollection.each(function(index,elem){"
            . "var value_id_mrp_task_orders_request_asset = parseInt($(elem).val());"
            . " var hasil = (isNaN(value_id_mrp_task_orders_request_asset)) ? 0 : value_id_mrp_task_orders_request_asset;"
            . "aData.push( hasil );"
            . "});"
            . "var supplier = $('#id_supplier').val();"
            . "var company = $('#id_company').val();"
            ."var dataString2 = 'id_mrp_task_orders_request_asset='+ aData +'&status=4'+'&id_supplier='+ supplier +'&id_company='+ company;"
                ."$.ajax({"
                ."type : 'POST',"
                ."url : '".site_url("mrp/mrp-ajax/insert-mrp-po")."',"
                ."data: dataString2,"
                ."dataType : 'html',"
                ."success: function(data) {"
//                 . "alert(data);"
                    ."window.location ='{$url}'"
                ."},"
             ."});"
       . "});"
                            
       . "$('#btn-draft').click(function(){"
            . "var oTable = $('#tableboxy2').dataTable();"
            . "var aData = [];"
            . "var rowcollection =  oTable.$('.mrp_task_orders_request_asset', {'page': 'all'});"
            . "rowcollection.each(function(index,elem){"
            . "var value_id_mrp_task_orders_request_asset = parseInt($(elem).val());"
            . " var hasil = (isNaN(value_id_mrp_task_orders_request_asset)) ? 0 : value_id_mrp_task_orders_request_asset;"
            . "aData.push( hasil );"
            . "});"
            . "var supplier = $('#id_supplier').val();"
            . "var company = $('#id_company').val();"                
            ."var dataString2 = 'id_mrp_task_orders_request_asset='+ aData +'&status=3'+'&id_supplier='+ supplier +'&id_company='+ company;"
                ."$.ajax({"
                ."type : 'POST',"
                ."url : '".site_url("mrp/mrp-ajax/insert-mrp-po")."',"
                ."data: dataString2,"
                ."dataType : 'html',"
                ."success: function(data) {"
                    ."window.location ='{$url}'"
                ."},"
             ."});"
       . "});"            
            
        . "var supplier = $('#id_supplier').val();"  
        . 'var table = '
        . '$("#tableboxy2").dataTable({'
          . '"order": [[ 0, "asc" ]],'
        . '});'
        . 'ambil_data(table, 0,supplier);'
      . '});'
            
      . "function delete_po_task_order_request(id){"
        . "var table = $('#example').DataTable();"
        . "table.row('.selected').remove().draw( false );"
        
//         . "$('#del_'+ id).hide();"
//         . "$('#img-page-'+ id).show();"
//            ."var dataString2 = 'id_mrp_task_orders_request_asset='+ id;"
//               ."$.ajax({"
//               ."type : 'POST',"
//               ."url : '".site_url("mrp/mrp-ajax/delete-po-task-orders-request")."',"
//               ."data: dataString2,"
//               ."dataType : 'html',"
//               ."success: function(data) {"
//                    . "var id_mrp_supplier = $('#id_supplier').val();"
//                       . 'var table = '
//                       . '$("#tableboxy").dataTable({'
//                       . '"order": [[ 0, "asc" ]],'
//                       . '"bDestroy": true'
//                       . '});'
//                       . "table.fnClearTable();"
//                       . 'ambil_data(table, 0,id_mrp_supplier);'
//
//               ."},"
//            ."});"
      . "}"
     . 'function ambil_data(table, mulai,id_mrp_supplier){'
        . "if(typeof id_mrp_supplier == 'undefined'){"
            . "mrp_supplier = 0;"
            . "}else{"
            . "mrp_supplier = id_mrp_supplier;"
         . "}"
       
        . '$.post("'.site_url("mrp/mrp-ajax/get-po-request-asset/{$id_mrp_task_orders}").'/"+mulai+"/"+mrp_supplier, function(data){'
          . '$("#loader-page").show();'
          . 'var hasil = $.parseJSON(data);'
          . 'if(hasil.status == 2){'
            . 'table.fnAddData(hasil.hasil);'
            . 'ambil_data(table, hasil.start,id_mrp_supplier);'
           
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
        
        $foot .= "<script>
function FormatCurrency(objNum)
{
   var num = objNum.value
   var ent, dec;
   if (num != '' && num != objNum.oldvalue)
   {
     num = MoneyToNumber(num);
     if (isNaN(num))
     {
       objNum.value = (objNum.oldvalue)?objNum.oldvalue:'';
     } else {
       var ev = (navigator.appName.indexOf('Netscape') != -1)?Event:event;
       if (ev.keyCode == 190 || !isNaN(num.split('.')[1]))
       {
        // alert(num.split('.')[1]);
         objNum.value = AddCommas(num.split('.')[0])+'.'+num.split('.')[1];
       }
       else
       {
         objNum.value = AddCommas(num.split('.')[0]);
       }
       objNum.oldvalue = objNum.value;
     }
   }
}
function MoneyToNumber(num)
{
   return (num.replace(/,/g, ''));
}

function AddCommas(num)
{
   numArr=new String(num).split('').reverse();
   for (i=3;i<numArr.length;i+=3)
   {
     numArr[i]+=',';
   }
   return numArr.reverse().join('');
} 
        
function formatCurrency(num) {
   num = num.toString().replace(/\$|\,/g,'');
   if(isNaN(num))
   num = '0';
   sign = (num == (num = Math.abs(num)));
   num = Math.floor(num*10000+0.50000000001);
   cents = num0;
   num = Math.floor(num/100).toString();
   for (var i = 0; i < Math.floor((num.length-(1+i))/3); i++)
   num = num.substring(0,num.length-(4*i+3))+'.'+
   num.substring(num.length-(4*i+3));
   return (((sign)?'':'-') + num);
}
</script>";
        
     $id_mrp_supplier =$this->global_models->get_field("mrp_task_orders_request_asset", "id_mrp_supplier", array("id_mrp_task_orders" => $id_mrp_task_orders));
     $nama_supplier =$this->global_models->get_field("mrp_supplier", "name", array("id_mrp_supplier" => $id_mrp_supplier));
     
     
     $data2 = $this->global_models->get_query("SELECT D.id_hr_company,D.title "
        . " FROM mrp_task_orders_request AS A"
        . " LEFT JOIN mrp_request AS B ON A.id_mrp_request = B.id_mrp_request"
        . " LEFT JOIN hr_pegawai AS C ON B.id_hr_pegawai = C.id_hr_pegawai"
        . " LEFT JOIN hr_company AS D ON C.id_hr_company = D.id_hr_company"
        . " WHERE A.id_mrp_task_orders = '{$id_mrp_task_orders}'"
        . " GROUP BY C.id_hr_company"
        );
        
    $where = "WHERE A.status = 1 AND A.id_mrp_task_orders = '$id_mrp_task_orders'";
    $jml = $this->global_models->get_query("SELECT count(A.id_mrp_task_orders_request_asset) AS id"
        . " FROM mrp_po_asset AS A"
        . " {$where}"
        );
        
//     print "<pre>";
//     print_r($jml);
//     print "</pre>";
//     die;
        
//     $id_hr_company =$this->global_models->get_field("mrp_task_orders_request_asset", "id_hr_company", array("id_mrp_task_orders" => $id_mrp_task_orders));
//     $nama_perusahaan =$this->global_models->get_field("hr_company", "title", array("id_hr_company" => $id_hr_company));
    $this->template->build("main-mrp/add-po", 
        array(
              'url'         => base_url()."themes/".DEFAULTTHEMES."/",
              'menu'        => 'mrp/task-orders',
              'title'       => lang("mrp_add_po"),
              'id_mrp_supplier'  => $id_mrp_supplier,
              'nama_supplier'   => $nama_supplier,
              'nama_perusahaan' => $data2[0]->title,
              'id_perusahaan'   => $data2[0]->id_hr_company,
              'total'           => $jml[0]->id,
              'breadcrumb'  => array(
                    "mrp_task-orders"  => "mrp/task-orders"
                ),
              'css'         => $css,
              'foot'        => $foot,
              'img'       => base_url()."themes/".DEFAULTTHEMES."/img/ajax-loader.gif"
//              'items'       => $items
            ));
      $this->template
        ->set_layout('form')
        ->build("main-mrp/add-po");
    }
   
  }
  
   public function update_po($id_mrp_task_orders = 0,$id_mrp_po = 0){
  $status = $this->global_models->get_field("mrp_po", "status",array("id_mrp_po" => "{$id_mrp_po}"));
  
    if($status != 3 AND $status != 8){
        redirect("mrp/detail-po/{$id_mrp_task_orders}/{$id_mrp_po}");
    }
    
    if(!$this->input->post(NULL)){
        
    $kirim3 = array(
            "status"                        => 1,
            "update_by_users"               => $this->session->userdata("id"),
            "update_date"                   => date("Y-m-d H:i:s")
            );
    $this->global_models->update("mrp_po_asset", array("id_mrp_task_orders" => $id_mrp_task_orders, "status" => "2"),$kirim3);

        
    $css = "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/datatables/dataTables.bootstrap.css' rel='stylesheet' type='text/css' />"
      . "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/jQueryUI/jquery-ui-1.10.3.custom.min.css' rel='stylesheet' type='text/css' />";
      $url = base_url('mrp/list-po');
      $url2 = base_url("mrp/update-po/{$id_mrp_task_orders}/{$id_mrp_po}");
    $foot = ""
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/datatables/jquery.dataTables.js' type='text/javascript'></script>"
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/datatables/dataTables.bootstrap.js' type='text/javascript'></script>"
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/jquery.ui.autocomplete.min.js' type='text/javascript'></script>";
	
    $foot .= "<script>"
                               
      . '$(function() {'
            
    . "$( '#supplier' ).autocomplete({"
         . "source: '".site_url("mrp/mrp-ajax/get-supplier/{$id_mrp_task_orders}")."',"
         . "minLength: 1,"
         . "search  : function(){ $(this).addClass('working');},"
         . "open    : function(){ $(this).removeClass('working');},"
         . "select: function( event, ui ) {"
           . "$('#id_supplier').val(ui.item.id);"
           . "var supplier = $('#id_supplier').val();"
            . 'var table = '
            . '$("#tableboxy").dataTable({'
            . '"order": [[ 0, "asc" ]],'
            . '"bDestroy": true'
            . '});'
            . "table.fnClearTable();"
            . 'ambil_data(table, 0,supplier);'
         . "}"
       . "});"
            
         . "$( '#company' ).autocomplete({"
         . "source: '".site_url("mrp/mrp-ajax/get-company")."',"
         . "minLength: 1,"
         . "search  : function(){ $(this).addClass('working');},"
         . "open    : function(){ $(this).removeClass('working');},"
         . "select: function( event, ui ) {"
           . "$('#id_company').val(ui.item.id);"
//            . "alert($('#id_company').val());"
         . "}"
       . "});"
       . "$('#update-po2').click(function(){"
            . "var supplier = $('#id_supplier').val();"
            . 'var table = '
            . '$("#tableboxy2").dataTable({'
            . '"order": [[ 0, "asc" ]],'
            . '"bDestroy": true'
            . '});'
            . "table.fnClearTable();"
            . 'ambil_data2(table, 0,supplier);'
       . "});"
            
        . "$( '#barang' ).autocomplete({"
         . "source: '".site_url("mrp/mrp-ajax/get-po-inventory-spesifik")."',"
         . "minLength: 1,"
         . "search  : function(){ $(this).addClass('working');},"
         . "open    : function(){ $(this).removeClass('working');},"
         . "select: function( event, ui ) {"
           . "$('#id_barang').val(ui.item.id);"
            . "$('#id_satuan').val(ui.item.id_satuan);"
//            . "alert($('#id_barang').val());"
         . "}"
       . "});"
            
       . "$('#btn-proses').click(function(){"
            . "var oTable = $('#tableboxy').dataTable();"
            . "var aData = [];"
            . "var rowcollection =  oTable.$('.mrp_task_orders_request_asset', {'page': 'all'});"
            . "rowcollection.each(function(index,elem){"
            . "var value_id_mrp_task_orders_request_asset = parseInt($(elem).val());"
            . " var hasil = (isNaN(value_id_mrp_task_orders_request_asset)) ? 0 : value_id_mrp_task_orders_request_asset;"
            . "aData.push( hasil );"
            . "});"
            . "var company = $('#id_company').val();"
            . "var supplier = $('#id_supplier').val();"
            ."var dataString2 = 'id_mrp_task_orders_request_asset='+ aData +'&status=4'+'&id_supplier='+ supplier + '&id_company=' + company ;"
                ."$.ajax({"
                ."type : 'POST',"
                ."url : '".site_url("mrp/mrp-ajax/update-mrp-po/{$id_mrp_po}")."',"
                ."data: dataString2,"
                ."dataType : 'html',"
                ."success: function(data) {"
                    ."window.location ='{$url}'"
                ."},"
             ."});"
       . "});"
                            
       . "$('#save-data-update').click(function(){"
             . "var oTable = $('#tableboxy2').dataTable();"
            . "var aData = [];"
            . "var rowcollection =  oTable.$('.mrp_task_orders_request_asset', {'page': 'all'});"
            . "rowcollection.each(function(index,elem){"
            . "var value_id_mrp_task_orders_request_asset = parseInt($(elem).val());"
            . " var hasil = (isNaN(value_id_mrp_task_orders_request_asset)) ? 0 : value_id_mrp_task_orders_request_asset;"
            . "aData.push( hasil );"
            . "});"
            . "var company = $('#id_company').val();"
//                . "alert(company);"
            . "var supplier = $('#id_supplier').val();"
            ."var dataString2 = 'id_mrp_task_orders_request_asset='+ aData +'&status=3'+'&id_supplier='+ supplier +'&id_company=' + company ;"
                ."$.ajax({"
                ."type : 'POST',"
                ."url : '".site_url("mrp/mrp-ajax/update-mrp-po/{$id_mrp_po}")."',"
                ."data: dataString2,"
                ."dataType : 'html',"
                ."success: function(data) {"
                    ."window.location ='{$url2}'"
                ."},"
             ."});"
       . "});"                                        
            
       . "$('#btn-save').click(function(){"
            . "var oTable = $('#tableboxy').dataTable();"
            . "var aData = [];"
            . "var rowcollection =  oTable.$('.mrp_task_orders_request_asset', {'page': 'all'});"
            . "rowcollection.each(function(index,elem){"
            . "var value_id_mrp_task_orders_request_asset = parseInt($(elem).val());"
            . " var hasil = (isNaN(value_id_mrp_task_orders_request_asset)) ? 0 : value_id_mrp_task_orders_request_asset;"
            . "aData.push( hasil );"
            . "});"
            . "var company = $('#id_company').val();"
//                . "alert(company);"
            . "var supplier = $('#id_supplier').val();"
            ."var dataString2 = 'id_mrp_task_orders_request_asset='+ aData +'&status=3'+'&id_supplier='+ supplier +'&id_company=' + company ;"
                ."$.ajax({"
                ."type : 'POST',"
                ."url : '".site_url("mrp/mrp-ajax/update-mrp-po/{$id_mrp_po}")."',"
                ."data: dataString2,"
                ."dataType : 'html',"
                ."success: function(data) {"
                    ."window.location ='{$url}'"
                ."},"
             ."});"
       . "});"
            
            
        . "var supplier = $('#id_supplier').val();"  
        . 'var table = '
        . '$("#tableboxy").dataTable({'
          . '"order": [[ 0, "asc" ]],'
        . '});'
        . 'ambil_data(table, 0,supplier);'
      . '});'
            
      . "function delete_po_task_order_request(id){"
//         . "$('#del_'+ id).hide();"
//         . "$('#img-page-'+ id).show();"
            ."var dataString2 = 'id_mrp_task_orders_request_asset='+ id;"
               ."$.ajax({"
               ."type : 'POST',"
               ."url : '".site_url("mrp/mrp-ajax/delete-po-task-orders-request/")."',"
               ."data: dataString2,"
               ."dataType : 'html',"
               ."success: function(data) {"
//                    . "var id_mrp_supplier = $('#id_supplier').val();"
//                       . 'var table = '
//                       . '$("#tableboxy2").dataTable({'
//                       . '"order": [[ 0, "asc" ]],'
//                       . '"bDestroy": true'
//                       . '});'
//                       . "table.fnClearTable();"
//                       . 'ambil_data2(table, 0,id_mrp_supplier);'
//                            
////                       . 'var table = '
//                       . '$("#tableboxy").dataTable().fnReloadAjax();'
//                       . "table.fnClearTable();"
//                       . 'ambil_data(table, 0,id_mrp_supplier);'
                                 
               ."},"
            ."});"
      . "}"
                            
      . "function delete_po_task_order_request2(id){"
//         . "$('#del2_'+ id).hide();"
//         . "$('#img-page2-'+ id).show();"
            ."var dataString2 = 'id_mrp_task_orders_request_asset='+ id;"
               ."$.ajax({"
               ."type : 'POST',"
               ."url : '".site_url("mrp/mrp-ajax/delete-po-task-orders-request2/")."',"
               ."data: dataString2,"
               ."dataType : 'html',"
               ."success: function(data) {"
//                    . "var id_mrp_supplier = $('#id_supplier').val();"
//                       . 'var table = '
//                       . '$("#tableboxy").dataTable({'
//                       . '"order": [[ 0, "asc" ]],'
//                       . '"bDestroy": true'
//                       . '});'
//                       . "table.fnClearTable();"
//                       . 'ambil_data(table, 0,id_mrp_supplier);'
//                            
//                       . 'var table = '
//                       . '$("#tableboxy2").dataTable({'
//                       . '"bDestroy": true,'
//                       . '"bJQueryUI": true,'    
//                       . '});'
//                       . "table.fnClearTable();"
//                       . 'ambil_data2(table, 0,id_mrp_supplier);'
               ."},"
            ."});"
      . "}"                      
                            
     . 'function ambil_data(table, mulai,id_mrp_supplier){'
        . "if(typeof id_mrp_supplier == 'undefined'){"
            . "mrp_supplier = 0;"
            . "}else{"
            . "mrp_supplier = id_mrp_supplier;"
         . "}"
       
        . '$.post("'.site_url("mrp/mrp-ajax/get-update-po-request-asset/{$id_mrp_task_orders}/{$id_mrp_po}").'/"+mulai+"/"+mrp_supplier, function(data){'
          . '$("#loader-page").show();'
          . 'var hasil = $.parseJSON(data);'
          . 'if(hasil.status == 2){'
            . 'table.fnAddData(hasil.hasil);'
            . 'ambil_data(table, hasil.start,mrp_supplier);'
           
          . '}'
          . 'else{'
            . '$("#loader-page").hide();'
//            . 'load_tooltip();'
//            . 'load_tooltip_harga();'
//            . "$('#script-tambahan').html(data);"
          . '}'
        . '});'
      . '}'
                
      . 'function ambil_data2(table, mulai,id_mrp_supplier){'
        . "if(typeof id_mrp_supplier == 'undefined'){"
            . "mrp_supplier = 0;"
            . "}else{"
            . "mrp_supplier = id_mrp_supplier;"
         . "}"
       
        . '$.post("'.site_url("mrp/mrp-ajax/get-po-request-asset/{$id_mrp_task_orders}").'/"+mulai+"/"+mrp_supplier, function(data){'
          . '$("#loader-page2").show();'
          . 'var hasil = $.parseJSON(data);'
          . 'if(hasil.status == 2){'
            . 'table.fnAddData(hasil.hasil);'
            . 'ambil_data2(table, hasil.start,id_mrp_supplier);'
          . '}'
          . 'else{'
            . '$("#loader-page2").hide();'
//            . 'load_tooltip();'
//            . 'load_tooltip_harga();'
//            . "$('#script-tambahan').html(data);"
          . '}'
        . '});'
      . '}'     
	  . "</script>"; 
        
        $foot .= "<script>
function FormatCurrency(objNum)
{
   var num = objNum.value
   var ent, dec;
   if (num != '' && num != objNum.oldvalue)
   {
     num = MoneyToNumber(num);
     if (isNaN(num))
     {
       objNum.value = (objNum.oldvalue)?objNum.oldvalue:'';
     } else {
       var ev = (navigator.appName.indexOf('Netscape') != -1)?Event:event;
       if (ev.keyCode == 190 || !isNaN(num.split('.')[1]))
       {
        // alert(num.split('.')[1]);
         objNum.value = AddCommas(num.split('.')[0])+'.'+num.split('.')[1];
       }
       else
       {
         objNum.value = AddCommas(num.split('.')[0]);
       }
       objNum.oldvalue = objNum.value;
     }
   }
}
function MoneyToNumber(num)
{
   return (num.replace(/,/g, ''));
}

function AddCommas(num)
{
   numArr=new String(num).split('').reverse();
   for (i=3;i<numArr.length;i+=3)
   {
     numArr[i]+=',';
   }
   return numArr.reverse().join('');
} 
        
function formatCurrency(num) {
   num = num.toString().replace(/\$|\,/g,'');
   if(isNaN(num))
   num = '0';
   sign = (num == (num = Math.abs(num)));
   num = Math.floor(num*10000+0.50000000001);
   cents = num0;
   num = Math.floor(num/100).toString();
   for (var i = 0; i < Math.floor((num.length-(1+i))/3); i++)
   num = num.substring(0,num.length-(4*i+3))+'.'+
   num.substring(num.length-(4*i+3));
   return (((sign)?'':'-') + num);
}
</script>";
        
    $id_mrp_supplier =$this->global_models->get_field("mrp_task_orders_request_asset", "id_mrp_supplier", array("id_mrp_task_orders" => $id_mrp_task_orders));
    $nama_supplier =$this->global_models->get_field("mrp_supplier", "name", array("id_mrp_supplier" => $id_mrp_supplier));
//     $id_mrp_supplier =$this->global_models->get_field("mrp_po", "id_mrp_po", array("id_mrp_task_orders" => $id_mrp_task_orders));
    
     $data = $this->global_models->get_query("SELECT A.id_mrp_po,B.id_mrp_supplier,C.name AS nama_supplier,A.id_hr_company,D.title AS nama_perusahaan"
        . ",A.create_by_users"
        . " FROM mrp_po AS A"
        . " LEFT JOIN mrp_po_asset AS B ON A.id_mrp_po = B.id_mrp_po"
        . " LEFT JOIN mrp_supplier AS C ON B.id_mrp_supplier = C.id_mrp_supplier"
        . " LEFT JOIN hr_company AS D ON A.id_hr_company = D.id_hr_company"   
        . " WHERE B.id_mrp_task_orders ='{$id_mrp_task_orders}' AND A.id_mrp_po = '{$id_mrp_po}'"
        . " GROUP BY A.id_mrp_po "
        . " ORDER BY A.id_mrp_po ASC "
        );
        
        $where = "WHERE A.status = 1 AND A.id_mrp_task_orders = '$id_mrp_task_orders'";
        $jml = $this->global_models->get_query("SELECT count(A.id_mrp_task_orders_request_asset) AS id"
        . " FROM mrp_po_asset AS A"
        . " {$where}"
        );
        
//        print_r($data); die;
     $this->template->build("main-mrp/update-po/view-update-po", 
        array(
              'url'         => base_url()."themes/".DEFAULTTHEMES."/",
              'menu'        => 'mrp/list-po',
              'title'       => lang("mrp_add_po"),
              'id_mrp_supplier'     => $data[0]->id_mrp_supplier,
              'nama_supplier'       => $data[0]->nama_supplier,
              'id_perusahaan'       => $data[0]->id_hr_company,
              'nama_perusahaan'     => $data[0]->nama_perusahaan,
              'id_users'            => $data[0]->create_by_users,
              'total'               => $jml[0]->id,
              'breadcrumb'  => array(
                    "list-po"  => 'mrp/list-po'
                ),
              'css'         => $css,
              'foot'        => $foot,
              'img'       => base_url()."themes/".DEFAULTTHEMES."/img/ajax-loader.gif"
//              'items'       => $items
            ));
      $this->template
        ->set_layout('form')
        ->build("main-mrp/update-po/view-update-po");
    }
   
  }
  
  function list_po() {

    $css = "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/datatables/dataTables.bootstrap.css' rel='stylesheet' type='text/css' />"
      . "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/jQueryUI/jquery-ui-1.10.3.custom.min.css' rel='stylesheet' type='text/css' />";
    
    $foot = ""
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/datatables/jquery.dataTables.js' type='text/javascript'></script>"
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/datatables/dataTables.bootstrap.js' type='text/javascript'></script>"
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/jquery.ui.autocomplete.min.js' type='text/javascript'></script>";
	  
    $foot .= "<script>"
//          . "$('.dropdown2').select2();"
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
          . '"order": [[ 1, "desc" ]],'
//          . "'iDisplayLength': 20"
        . '});'
      
        . "ambil_data(table, 0,{$this->session->userdata("id")});"
      
      . '});'
      
      . 'function ambil_data(table, mulai,id_users){'
        . '$.post("'.site_url("mrp/mrp-ajax/get-mrp-list-po").'/"+mulai+"/"+id_users, function(data){'
          . '$("#loader-page").show();'
          . 'var hasil = $.parseJSON(data);'
          . 'if(hasil.status == 2){'
            . 'if(hasil.flag === 2){'
                . '$("#loader-page").hide();'
                . '}'
            . 'table.fnAddData(hasil.hasil);'
            . 'ambil_data(table, hasil.start,id_users);'
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

//     $pst = $this->input->post(NULL);
//      if($pst){
//
////        $newdata = array(
////            'inventory_spesifik_search_type'                        => $pst['inventory_spesifik_search_type'],
////            'inventory_spesifik_search_jenis'                       => $pst['inventory_spesifik_search_jenis'],
////            'inventory_spesifik_search_nama'                        => $pst['inventory_spesifik_search_nama'],
////            'inventory_spesifik_search_code'                        => $pst['inventory_spesifik_search_code'],
////            'inventory_spesifik_search_brand'                       => $pst['inventory_spesifik_search_brand'],
////            
////          );
////          $this->session->set_userdata($newdata);
//    }
    
    $this->template->build('main-mrp/list-po', 
      array(
            'url'           => base_url()."themes/".DEFAULTTHEMES."/",
            'menu'          => "mrp/list-po",
            'title'         => lang("Purchase Order"),
            'foot'          => $foot,
            'css'           => $css,
          ));
    $this->template
      ->set_layout('default')
      ->build('main-mrp/list-po');
  }
  
   public function status_payment($id_mrp_po = 0){
   
    if(!$this->input->post(NULL)){
        
    $list = $this->global_models->get_query("SELECT A.tanggal_payment,A.note_payment,A.status_payment"
        . " FROM mrp_po AS A"
        . " WHERE A.id_mrp_po = '{$id_mrp_po}' "
       );
        
    $css = "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/datatables/dataTables.bootstrap.css' rel='stylesheet' type='text/css' />"
      . "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/jQueryUI/jquery-ui-1.10.3.custom.min.css' rel='stylesheet' type='text/css' />"
      ;
    $foot = ""
//      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/datatables/jquery.dataTables.js' type='text/javascript'></script>"
//      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/datatables/dataTables.bootstrap.js' type='text/javascript'></script>"
     ;
	
    $foot .= "<script>"
                               
      . '$(function() {'
            . "$( '.date' ).datepicker({"
              . "showOtherMonths: true,"
              . "dateFormat: 'yy-mm-dd',"  
              . "selectOtherMonths: true,"  
              . "selectOtherYears: true,"
                . "});"
            . "});"
      . "</script>"; 
       
    $this->template->build("main-mrp/payment-request", 
        array(
              'url'                 => base_url()."themes/".DEFAULTTHEMES."/",
              'menu'                => 'mrp/list-po',
              'title'               => lang("payment_request"),
              'id_po'           => $id_mrp_po,
              'list'            => $list,
//              'id_mrp_task_orders'  => $id_mrp_task_orders,
//              'id_mrp_po'           => $id_mrp_po,
//              'dt_status'           => $status,
              'breadcrumb'  => array(
                    "mrp_list_po"  => "mrp/list-po"
                ),
              'css'         => $css,
              'foot'        => $foot,
            ));
      $this->template
        ->set_layout('form')
        ->build("main-mrp/payment-request");
    }else{
    $pst = $this->input->post(NULL);
//      print_r($pst); die;
      if($pst['id_detail']){
       $kirim = array(
            "tanggal_payment"               => $pst['tgl_payment'],
            "note_payment"                  => $pst['note_pembayaran'],
            "status_payment"                => $pst['status_pembayaran'],
            "update_by_users"               => $this->session->userdata("id"),
            "update_date"                   => date("Y-m-d H:i:s")
        );
        
        $id_mrp_po = $this->global_models->update("mrp_po", array("id_mrp_po" => $pst['id_detail']),$kirim);
      
      }
      else{
        $kirim = array(
            "tanggal_payment"               => $pst['tanggal_payment'],
            "note_payment"                  => $pst['note_payment'],
            "status_payment"                => $pst['status_payment'],
            "create_by_users"               => $this->session->userdata("id"),
            "create_date"                   => date("Y-m-d H:i:s")
        );
        
        $id_mrp_po = $this->global_models->insert("mrp_po", $kirim);
        
      }
      if($id_mrp_po){
        $this->session->set_flashdata('success', 'Data tersimpan');
      }
      else{
        $this->session->set_flashdata('notice', 'Data tidak tersimpan');
      }
      redirect("mrp/list-po");
    }
   
  }
  
   public function detail_po($id_mrp_task_orders = 0,$id_mrp_po = 0){
   
    if(!$this->input->post(NULL)){
        
    $list = $this->global_models->get_query("SELECT A.id_mrp_po,A.no_po,A.code,A.status,A.note,A.tanggal_dikirim,A.status,A.tanggal_payment,A.note_payment,A.status_payment,A.tanggal_po"
        . ",B.name,B.pic,B.phone,B.fax,B.address,B.id_mrp_supplier,A.user_approval"
        . ",C.title AS nama_perusahaan,C.office,C.address AS alamat_perusahaan,C.id_hr_company"
        . " FROM mrp_po AS A"
        . " LEFT JOIN mrp_supplier AS B ON A.id_mrp_supplier = B.id_mrp_supplier"
        . " LEFT JOIN hr_company AS C ON A.id_hr_company = C.id_hr_company"    
        . " WHERE A.id_mrp_po = '{$id_mrp_po}' "
//        . " GROUP BY A.id_mrp_po "
       );
        
    $css = "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/datatables/dataTables.bootstrap.css' rel='stylesheet' type='text/css' />"
      . "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/jQueryUI/jquery-ui-1.10.3.custom.min.css' rel='stylesheet' type='text/css' />";
      $url = base_url('mrp/list-po');
    $foot = ""
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/datatables/jquery.dataTables.js' type='text/javascript'></script>"
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/datatables/dataTables.bootstrap.js' type='text/javascript'></script>"
     ;
	
    $foot .= "<script>"
                               
      . '$(function() {'
            . "$( '.date' ).datepicker({"
              . "showOtherMonths: true,"
              . "dateFormat: 'yy-mm-dd',"  
              . "selectOtherMonths: true,"  
              . "selectOtherYears: true,"
              . "onSelect: function(){"
              . "var tgl_kirim = $('#tgl_dikirim').val();"
            . "var tgl_po = $('#tgl_po').val();"
            . "var dt_note = $('#note_po').val();"
            ."var dataString2 = 'tanggal='+tgl_kirim +'&note=' + dt_note+ '&tanggal_po='+ tgl_po;"
                ."$.ajax({"
                ."type : 'POST',"
                ."url : '".site_url("mrp/mrp-ajax/update-detail-po/{$id_mrp_task_orders}/{$id_mrp_po}")."',"
                ."data: dataString2,"
                ."dataType : 'html',"
                ."success: function(data) {"
                ."},"
            ."});"
              . "}"
              . "});"
                           
       . "$('#note_po').keyup(function(){"
            . "var tgl_kirim = $('#tgl_dikirim').val();"
            . "var tgl_po = $('#tgl_po').val();"           
            . "var dt_note = $('#note_po').val();"
            ."var dataString2 = 'tanggal='+tgl_kirim +'&note=' + dt_note+ '&tanggal_po='+ tgl_po;"
                ."$.ajax({"
                ."type : 'POST',"
                ."url : '".site_url("mrp/mrp-ajax/update-detail-po/{$id_mrp_task_orders}/{$id_mrp_po}")."',"
                ."data: dataString2,"
                ."dataType : 'html',"
                ."success: function(data) {"
                ."},"
            ."});"
       . "});"
            
       . "$('#btn-approval').click(function(){"
            ."var dataString2 = 'status=5' +'&id_company={$list[0]->id_hr_company}';"
                ."$.ajax({"
                ."type : 'POST',"
                ."url : '".site_url("mrp/mrp-ajax/approve-detail-po/{$id_mrp_task_orders}/{$id_mrp_po}")."',"
                ."data: dataString2,"
                ."dataType : 'html',"
                ."success: function(data) {"
                    ."window.location ='{$url}'"
                ."},"
             ."});"
       . "});"
                            
       . "$('#btn-send').click(function(){"
            ."var dataString2 = 'status=6';"
                ."$.ajax({"
                ."type : 'POST',"
                ."url : '".site_url("mrp/mrp-ajax/approve-detail-po/{$id_mrp_task_orders}/{$id_mrp_po}")."',"
                ."data: dataString2,"
                ."dataType : 'html',"
                ."success: function(data) {"
                    ."window.location ='{$url}'"
                ."},"
             ."});"
       . "});"
       
       . "$('#btn-revisi').click(function(){"
            ."var dataString2 = 'status=8';"
                ."$.ajax({"
                ."type : 'POST',"
                ."url : '".site_url("mrp/mrp-ajax/approve-detail-po/{$id_mrp_task_orders}/{$id_mrp_po}")."',"
                ."data: dataString2,"
                ."dataType : 'html',"
                ."success: function(data) {"
                    ."window.location ='{$url}'"
                ."},"
             ."});"
       . "});"
       
        . "$('#btn-closed-po').click(function(){"
            ."var dataString2 = 'status=7';"
                ."$.ajax({"
                ."type : 'POST',"
                ."url : '".site_url("mrp/mrp-ajax/closed-detail-po/{$id_mrp_task_orders}/{$id_mrp_po}")."',"
                ."data: dataString2,"
                ."dataType : 'html',"
                ."success: function(data) {"
                    ."window.location ='{$url}'"
                ."},"
             ."});"
       . "});"
            
        . 'var table = '
        . '$("#tableboxy").dataTable({'
          . '"order": [[ 0, "asc" ]],'
        . '});'
        . 'ambil_data(table, 0, 0);'
      . '});'
            
     . 'function ambil_data(table, mulai,total){'
        . '$.post("'.site_url("mrp/mrp-ajax/get-mrp-detail-po-list/{$id_mrp_task_orders}/{$id_mrp_po}").'/"+mulai+"/"+total, function(data){'
          . '$("#loader-page").show();'
          . 'var hasil = $.parseJSON(data);'
          . 'if(hasil.status == 2){'
            . 'table.fnAddData(hasil.hasil);'
            . 'ambil_data(table, hasil.start, hasil.dt_total);'
           . "$('#dt-total').text(parseFloat(hasil.dt_total, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,').toString());"
          . '}'
          . 'else{'
            . '$("#loader-page").hide();'
//            . 'load_tooltip();'
//            . 'load_tooltip_harga();'
//            . "$('#script-tambahan').html(hasil.dt_total);"
          . '}'
        . '});'
      . '}'
	  . "</script>"; 
        
//    $id_mrp_supplier =$this->global_models->get_field("mrp_task_orders_request_asset", "id_mrp_supplier", array("id_mrp_task_orders" => $id_mrp_task_orders));
//    $nama_supplier =$this->global_models->get_field("mrp_supplier", "name", array("id_mrp_supplier" => $id_mrp_supplier));
//     $id_mrp_task_orders = 0,$id_mrp_po 
         $status = array( 3=> "<span class='label bg-orange'>Draft PO</span>", 4 => "<span class='label bg-green'>Proses Pengajuan PO</span>",
      5 => "<span class='label bg-green'>Approved PO</span>", 6 =>"<span class='label bg-green'>Sent PO</span>"
             , 7 =>"<span class='label bg-green'>Closed PO</span>", 8 =>"<span class='label bg-red'>Revisi PO</span>");
    
    $this->template->build("main-mrp/detail-po/view-detail", 
        array(
              'url'                 => base_url()."themes/".DEFAULTTHEMES."/",
              'menu'                => 'mrp/list-po',
              'title'               => lang("mrp_detail_po"),
              'list'                => $list,
              'id_mrp_task_orders'  => $id_mrp_task_orders,
              'id_mrp_po'           => $id_mrp_po,
              'dt_status'           => $status,
              'breadcrumb'  => array(
                    "mrp_list_po"  => "mrp/list-po"
                ),
              'css'         => $css,
              'foot'        => $foot,
//              'img'       => base_url()."themes/".DEFAULTTHEMES."/img/ajax-loader.gif"
//              'items'       => $items
            ));
      $this->template
        ->set_layout('form')
        ->build("main-mrp/detail-po/view-detail");
    }
   
  }
  
  function preview($id_mrp_task_orders = 0,$id_mrp_po = 0){
   
      $detail = $this->global_models->get_query("SELECT A.id_mrp_po,A.no_po,A.user_approval,A.note,A.tanggal_dikirim,A.tanggal_po"
      . ",B.office,B.title AS company,B.address AS address_company"
      . ",C.name AS nama_supplier,C.pic,C.phone,C.address AS address_supplier"
      . ",D.name AS nama_user,E.signature"
      . " FROM mrp_po AS A"
      . " LEFT JOIN hr_company AS B ON A.id_hr_company = B.id_hr_company"
      . " LEFT JOIN mrp_supplier AS C ON A.id_mrp_supplier = C.id_mrp_supplier"
      . " LEFT JOIN m_users AS D ON A.user_approval = D.id_users"
      . " LEFT JOIN hr_pegawai AS E ON A.user_approval = E.id_users"  
      . " WHERE A.id_mrp_po='{$id_mrp_po}'");
      
//      print_r($detail);
//      DIE;
      $where = "WHERE A.status >= 4 AND A.id_mrp_task_orders = '{$id_mrp_task_orders}' AND A.id_mrp_po = '{$id_mrp_po}'  ";
      $list = $data = $this->global_models->get_query("SELECT A.id_mrp_task_orders_request_asset,A.jumlah,A.note,C.name AS nama_barang,E.title AS satuan"
            . ",B.title AS title_spesifik,F.harga,A.id_mrp_task_orders_request_asset,E.group_satuan,A.harga AS harga_task_order_request"
              . ",E.nilai"
        . " FROM mrp_po_asset AS A"
        . " LEFT JOIN mrp_inventory_spesifik AS B ON A.id_mrp_inventory_spesifik = B.id_mrp_inventory_spesifik"
        . " LEFT JOIN mrp_inventory_umum AS C ON B.id_mrp_inventory_umum = C.id_mrp_inventory_umum"
        . " LEFT JOIN mrp_brand AS D ON B.id_mrp_brand = D.id_mrp_brand"
        . " LEFT JOIN mrp_satuan AS E ON A.id_mrp_satuan = E.id_mrp_satuan"
        . " LEFT JOIN mrp_supplier_inventory AS F ON B.id_mrp_inventory_spesifik = (F.id_mrp_inventory_spesifik AND F.id_mrp_supplier = '{$id_mrp_supplier}')"
        . " LEFT JOIN mrp_supplier G ON F.id_mrp_supplier = G.id_mrp_supplier "
        . " {$where}"
        . " ORDER BY C.name ASC");
      
     $data = array(
      'detail'     => $detail,
       'supplier'      => $supplier,
//      'kedua'     => $detail_array->tour->days." Hari / {$detail_array->tour->night} Malam - {$detail_array->tour->airlines}",
      'no'          =>  $detail[0]->code,
       'list'       =>  $list,
       'nama_user'  =>  $nama_user,
       'signature'  =>  $detail[0]->signature,
       'tanggal_dikirim' => $detail[0]->tanggal_dikirim,
       'note'         => $detail[0]->note
       
    );
    $this->load->view('main-mrp/print-preview', $data);
  
  }
  
    function preview2($id_mrp_request = 0,$id_mrp_task_orders = 0){
   
      $detail = $this->global_models->get_query(
      "SELECT H.user_approval,E.id_mrp_po,E.no_po"
      . ",F.office,F.title AS company,F.address AS address_company"
      . ",G.name AS nama_supplier,G.pic,G.phone,G.address AS address_supplier"
      . ",I.name AS nama_user,J.signature,L.name AS users,M.title AS organisasi"
      . ",O.name AS pegawai_receiver,P.title AS organisasi_receiver,E.tanggal_po"
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
      . " LEFT JOIN hr_pegawai AS N ON H.user_pegawai_receiver = N.id_hr_pegawai"
      . " LEFT JOIN m_users AS O ON N.id_users = O.id_users"
      . " LEFT JOIN hr_master_organisasi AS P ON N.id_hr_master_organisasi = P.id_hr_master_organisasi"        
      . " WHERE C.id_mrp_task_orders='{$id_mrp_task_orders}' AND A.id_mrp_request = '{$id_mrp_request}'"
      . " GROUP BY A.id_mrp_request_asset");
      
//      print "<pre>";
//      print $this->db->last_query();
//      print_r($detail);
//      print "</pre>";
//      die;
      
      $where = "WHERE A.status >= 1 AND A.id_mrp_task_orders = '{$id_mrp_task_orders}' AND H.id_mrp_request = '{$id_mrp_request}'  ";
      $list = $data = $this->global_models->get_query("SELECT A.id_mrp_task_orders_request_asset,H.jumlah,A.note,C.name AS nama_barang,E.title AS satuan"
            . ",B.title AS title_spesifik,A.harga,A.id_mrp_task_orders_request_asset,E.group_satuan,A.harga AS harga_task_order_request"
              . ",E.nilai"
        . " FROM mrp_task_orders_request_asset AS A"
        . " LEFT JOIN mrp_inventory_spesifik AS B ON A.id_mrp_inventory_spesifik = B.id_mrp_inventory_spesifik"
        . " LEFT JOIN mrp_inventory_umum AS C ON B.id_mrp_inventory_umum = C.id_mrp_inventory_umum"
        . " LEFT JOIN mrp_brand AS D ON B.id_mrp_brand = D.id_mrp_brand"
        . " LEFT JOIN mrp_satuan AS E ON A.id_mrp_satuan = E.id_mrp_satuan"
        . " LEFT JOIN mrp_supplier_inventory AS F ON B.id_mrp_inventory_spesifik = (F.id_mrp_inventory_spesifik AND F.id_mrp_supplier = '{$id_mrp_supplier}')"
        . " LEFT JOIN mrp_supplier G ON F.id_mrp_supplier = G.id_mrp_supplier "
        . " LEFT JOIN mrp_request_asset AS H ON A.id_mrp_inventory_spesifik = H.id_mrp_inventory_spesifik"
        . " {$where}"
        . " GROUP BY H.id_mrp_request_asset"
        . " ORDER BY CONCAT(C.name,B.title) ASC"
        );
      
        
//       print "<pre>";
//      print_r($list);
//      print "</pre>";
//      die;
     $data = array(
      'detail'     => $detail,
       'supplier'      => $supplier,
//      'kedua'     => $detail_array->tour->days." Hari / {$detail_array->tour->night} Malam - {$detail_array->tour->airlines}",
      'no'          =>  $detail[0]->code,
       'list'       =>  $list,
       'nama_user'  =>  $nama_user,
       'signature'  =>  $detail[0]->signature,
       'tanggal_dikirim' => $detail[0]->tanggal_dikirim,
       'note'         => $detail[0]->note
       
    );
    $this->load->view('main-mrp/print-preview2', $data);
  
  }
  
      function preview_grouping($create_by_users = 0,$id_mrp_task_orders = 0){
   
      $detail = $this->global_models->get_query(
      "SELECT H.user_approval,E.id_mrp_po,E.no_po,E.tanggal_po"
      . ",F.office,F.title AS company,F.address AS address_company"
      . ",G.name AS nama_supplier,G.pic,G.phone,G.address AS address_supplier"
      . ",I.name AS nama_user,J.signature,L.name AS users,M.title AS organisasi"
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
      . " LEFT JOIN m_users AS N ON A.create_by_users = N.id_users"
      . " LEFT JOIN hr_pegawai AS O ON N.id_users = O.id_users"       
      . " LEFT JOIN hr_master_organisasi AS P ON O.id_hr_master_organisasi = P.id_hr_master_organisasi"
                    
      . " WHERE C.id_mrp_task_orders='{$id_mrp_task_orders}' AND A.create_by_users = '{$create_by_users}'"
      . " GROUP BY A.id_mrp_request_asset");
      
//      print "<pre>";
//      print $this->db->last_query();
//      print_r($detail);
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
      
       $where = "WHERE G.id_mrp_task_orders = {$id_mrp_task_orders} AND F.create_by_users ={$create_by_users} AND A.status >= 1 AND jumlah IS NOT NULL";
      
      
      $list = $this->global_models->get_query("SELECT B.name AS nama_barang,C.title AS brand,D.title AS satuan,"
        . "A.id_mrp_inventory_spesifik,A.title AS title_spesifik,SUM(E.jumlah) AS jumlah,F.id_mrp_request,F.status AS status_request,"
        . " G.id_mrp_task_orders,D.nilai"
        . " FROM mrp_inventory_spesifik AS A"
        . " LEFT JOIN mrp_inventory_umum AS B ON A.id_mrp_inventory_umum = B.id_mrp_inventory_umum"
        . " LEFT JOIN mrp_brand AS C ON A.id_mrp_brand = C.id_mrp_brand"
        . " LEFT JOIN mrp_satuan AS D ON A.id_mrp_satuan = D.id_mrp_satuan"
        . " LEFT JOIN mrp_request_asset AS E ON A.id_mrp_inventory_spesifik = E.id_mrp_inventory_spesifik"
        . " LEFT JOIN mrp_request AS F ON E.id_mrp_request = F.id_mrp_request"
        . " LEFT JOIN mrp_task_orders_request AS G ON F.id_mrp_request = G.id_mrp_request"
        . " {$where} "
        . " GROUP BY F.create_by_users,A.id_mrp_inventory_spesifik"
         );
       
//        print $this->db->last_query();
//       print "<pre>";
//      print_r($list);
//      print "</pre>";
//      die;
     $data = array(
      'detail'     => $detail,
       'supplier'      => $supplier,
//      'kedua'     => $detail_array->tour->days." Hari / {$detail_array->tour->night} Malam - {$detail_array->tour->airlines}",
      'no'          =>  $detail[0]->code,
       'list'       =>  $list,
       'nama_user'  =>  $nama_user,
       'signature'  =>  $detail[0]->signature,
       'tanggal_dikirim' => $detail[0]->tanggal_dikirim,
       'note'         => $detail[0]->note
       
    );
    $this->load->view('main-mrp/print-grouping', $data);
  
  }
  
   function po_pdf($id_mrp_task_orders = 0,$id_mrp_po = 0){
   
   
      $detail = $this->global_models->get_query("SELECT A.id_mrp_po,A.no_po,A.user_approval,A.note,A.tanggal_dikirim"
      . ",B.office,B.title AS company,B.address AS address_company"
      . ",C.name AS nama_supplier,C.pic,C.phone,C.address AS address_supplier"
      . ",D.name AS nama_user,E.signature"
      . " FROM mrp_po AS A"
      . " LEFT JOIN hr_company AS B ON A.id_hr_company = B.id_hr_company"
      . " LEFT JOIN mrp_supplier AS C ON A.id_mrp_supplier = C.id_mrp_supplier"
      . " LEFT JOIN m_users AS D ON A.user_approval = D.id_users"
      . " LEFT JOIN hr_pegawai AS E ON A.user_approval = E.id_users"  
      . " WHERE A.id_mrp_po='{$id_mrp_po}'");
      
//      print $detail[0]->no_po;
//      die();
     
      $where = "WHERE A.status >= 4 AND A.id_mrp_task_orders = '{$id_mrp_task_orders}' AND A.id_mrp_po = '{$id_mrp_po}'  ";
      $list = $data = $this->global_models->get_query("SELECT A.id_mrp_task_orders_request_asset,A.jumlah,A.note,C.name AS nama_barang,E.title AS satuan"
            . ",B.title AS title_spesifik,F.harga,A.id_mrp_task_orders_request_asset,E.group_satuan,A.harga AS harga_task_order_request"
              . ",E.nilai"
        . " FROM mrp_po_asset AS A"
        . " LEFT JOIN mrp_inventory_spesifik AS B ON A.id_mrp_inventory_spesifik = B.id_mrp_inventory_spesifik"
        . " LEFT JOIN mrp_inventory_umum AS C ON B.id_mrp_inventory_umum = C.id_mrp_inventory_umum"
        . " LEFT JOIN mrp_brand AS D ON B.id_mrp_brand = D.id_mrp_brand"
        . " LEFT JOIN mrp_satuan AS E ON A.id_mrp_satuan = E.id_mrp_satuan"
        . " LEFT JOIN mrp_supplier_inventory AS F ON B.id_mrp_inventory_spesifik = (F.id_mrp_inventory_spesifik AND F.id_mrp_supplier = '{$id_mrp_supplier}')"
        . " LEFT JOIN mrp_supplier G ON F.id_mrp_supplier = G.id_mrp_supplier "
        . " {$where}"
        . " ORDER BY C.name ASC");
        $office = $detail[0]->office.' - '.$detail[0]->company.'<br>'.$detail[0]->address_company;
        $supplier = $detail[0]->nama_supplier.'<br>'.$detail[0]->pic.'/'.$detail[0]->phone.'<br>'.$detail[0]->address_supplier;
    if($detail[0]->tanggal_dikirim != '0000-00-00' AND $detail[0]->tanggal_dikirim != ''){
  $tgl = 'pada tanggal '.date('d M Y', strtotime($detail[0]->tanggal_dikirim));
    }
	 
    $html = "<style>
    @media print {
    @page { margin: 0; }
    body { margin: 1.6cm; }
    }
    body{
/*    font-size: smaller;*/
    }
    table{
    font-size: smaller;
    }
</style>";
    
    $html .=  "<table width='100%' style='padding-bottom: 3%;border: 2'>"
          . "<tr>"
            . "<td>"
                . "<div style='font-size: 12px; margin-top: 30px'>{$office}<br><br>"
                . "</div>"
            . "</td>"
            . "<td>"
//                . "<div style='font-size: 12px; margin-top: 30px'>{$office}<br><br>"
//                . "</div>"
            . "</td>"            
            . "<td>"
                . "<div style='font-size: 12px; margin-top: 30px'>Kepada Yth,<br></span><span style='font-size: 12px;'>{$supplier}<br><br>"
                . "</div>"
            . "</td>"            
          . "</tr>"
        . "</table>"
        .  '<table style="padding-bottom: 25%;width:100%">'
          . "<tr>"
                . '<td colspan="3">Dengan Hormat,
  <br>Mohon dapat dikirimkan kepada kami '.$tgl.' barang-barang sbb :'
                . "</td>"
          . "</tr>"
        . "</table><br>"
        . '<table cellpadding="1" cellspacing="1" border="1">'
          . "<tr>"
            . '<th style="border: 0.5px solid black;width: 30px; text-align:center;">No</th>'
            . '<th style="border: 0.5px solid black;width: 45%;">Jenis Barang</th>'
            . '<th style="border: 0.5px solid black;width: 40px;text-align:center;">Jumlah<br>Barang</th>'
            . '<th style="border: 0.5px solid black;width: 40px;text-align:center;">Satuan</th>'
            . '<th style="border: 0.5px solid black;width: 80px;text-align:center;">Harga<br>Satuan</th>'
            . '<th style="border: 0.5px solid black;text-align:center;">Total<br>Harga</th>'
            . '<th style="border: 0.5px solid black;">Keterangan</th>'
          . "</tr>";
           $no =0;
           foreach($list AS $py){
              if($py->title_spesifik){
                    $title_spesifik = ' '.$py->title_spesifik;
              }else{
                    $title_spesifik = '';
              }
              $no = $no + 1;
              $total = number_format(($py->jumlah * $py->nilai ) * $py->harga_task_order_request);
              $total2 += (($py->jumlah * $py->nilai) * $py->harga_task_order_request);
              $ttl_3 = number_format($total2);
              $note2 = nl2br($py->note);
              $hrg = number_format($py->harga_task_order_request);
              $nama_barang = $py->nama_barang.$title_spesifik;
              $html .= "<tr>"
                    . '<td style="border: 0.5px solid black;text-align: center;">'.$no.'</td>'
                    . '<td style="border: 0.5px solid black;">'.$nama_barang.'</td>'
                    . '<td style="border: 0.5px solid black;"><center>'.$py->jumlah.'</center></td>'
                    . '<td style="border: 0.5px solid black;"><center>'.$py->satuan.'</center></td>'
                    . '<td style="border: 0.5px solid black;"><center>'.$hrg.'</center></td>'
                    . '<td style="border: 0.5px solid black;"><center>Rp '.$total.'</center></td>'
                    . '<td style="border: 0.5px solid black;">'.$note2.'</td>'
                    . "</tr>";
            
           }
          
        $html .= "<tr>"
                . '<td colspan="5" style="border: 0.5px solid black;"><center><b>Total</b></center></td>'
                . '<td style="border: 0.5px solid black; "><center><b>Rp '.$ttl_3.'</b></center></td>'
                . '<td style="border: 0.5px solid black;"></td>'
              . "</tr>"
        . "</table><br><br>";
         $tgl2 = date('d F Y');
        if($detail[0]->signature){
             $link = base_url().'files/antavaya/signature/'.$detail[0]->signature;
            $sgn = "src=$link";
           $sgn2 = "<img style='width: 120px;height: 80px;' $sgn >";
        }else{
            $sgn2 = '';
        }
		
        $html .=  '<table>'
                . "<tr>"
                    . '<td style="width:25%">Demikian dan terima kasih.<br><br>Jakarta, '.$tgl2.'<br><br><span style="padding-left: 40px">Hormat Kami</span></td>'
                    . '<td style="width:55%">Keterangan :</td>'    
                    . '<td><br><br><br><br><span style="padding-left: 20px">Diterima oleh</span></td>'
                . "</tr>"
                . "<tr>"
                    . "<td>"
                        . '<span style="padding-left: 15px">'.$sgn2.'</span>'
                    . "</td>"
                    . "<td>- Tagihan harus disertai dengan ORDER pembelian asli.<br>- Tanda Terima KWITANSI dilaksanakan setiap hari SELASA
dan JUM'AT.<br>- Pembayaran dilakukan setiap hari SELASA dan JUM'AT.<br>- Barang yang dikirim dan tidak sesuai permintaan akan
ditolak max. 7 hari setelah tanggal pengiriman.<br></td>"
                    . "<td></td>"
                    . "<td></td>"            
                . "</tr>"
                . "<tr>"
                    . '<td><span style="padding-left: 40px">( '.$detail[0]->nama_user.' )</span></td>'
                    . "<td>&nbsp;</td>"
                        
                    . '<td><span style="padding-left: 10px">(...............................)</span></td>'
                . "</tr>"
                . "<tr>"
                    . '<td style="width:25%">&nbsp;</td>'
                    . '<td style="width:55%"><div style="margin-top: 20%"><b>'.$note.'</b></div></td>'
                    . '<td style="width:20%">&nbsp;</td>'
                . "</tr>"
              . "</table>";  
        
                   
//     print $html; 
//     die;  

$this->load->library('Pdf');

    // create new PDF document
$pdf = new Pdf(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information

$ts = "ORDER PEMBELIAN";
$ts2 = $detail[0]->no_po;
//$pdf->Header($title);
$pdf->setHtmlHeader($ts,$ts2);
// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);


$pdf->SetFont('helvetica', '', 12);


$pdf->AddPage();

//    $this->load->view('main-mrp/print-preview2', $data);
    
// set some text to print
//$txt = <<<EOD
//TCPDF Example 003
//
//Custom page header and footer are defined by extending the TCPDF class and overriding the Header() and Footer() methods.
//EOD;

// print a block of text using Write()
//$pdf->Write(0, $this->load->view('main-mrp/print-preview2', $data), '', 0, 'C', true, 0, false, false, 0);
     $pdf->writeHTML($html, true, false, true, false, '');

// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output("PO_".$detail[0]->no_po, 'I');

         
//       print "<pre>";
//      print_r($list);
//      print "</pre>";
//      die;
     $data = array(
      'detail'     => $detail,
       'supplier'      => $supplier,
//      'kedua'     => $detail_array->tour->days." Hari / {$detail_array->tour->night} Malam - {$detail_array->tour->airlines}",
      'no'          =>  $detail[0]->code,
       'list'       =>  $list,
       'nama_user'  =>  $nama_user,
       'signature'  =>  $detail[0]->signature,
       'tanggal_dikirim' => $detail[0]->tanggal_dikirim,
       'note'         => $detail[0]->note
       
    );
     
  
  }
  
  function list_rg($id_mrp_po){

//       $data = $this->global_models->get_query("SELECT B.id_hr_pegawai,C.jumlah,D.id_hr_master_organisasi,D.id_hr_company"
//        . " FROM mrp_task_orders_request AS A"
//        . " INNER JOIN mrp_request AS B ON A.id_mrp_request = B.id_mrp_request"
//        . " INNER JOIN mrp_request_asset AS C ON B.id_mrp_request = C.id_mrp_request"
//        . " INNER JOIN hr_pegawai AS D ON B.id_hr_pegawai = D.id_hr_pegawai"
//        . " WHERE A.id_mrp_task_orders='5' AND C.id_mrp_inventory_spesifik='1'"
//        );  
//       print $this->db->last_query();
//        print_r($data); die;
      $css = "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/datatables/dataTables.bootstrap.css' rel='stylesheet' type='text/css' />"
      . "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/jQueryUI/jquery-ui-1.10.3.custom.min.css' rel='stylesheet' type='text/css' />";
    
    $foot = ""
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/datatables/jquery.dataTables.js' type='text/javascript'></script>"
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/datatables/dataTables.bootstrap.js' type='text/javascript'></script>"
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/jquery.ui.autocomplete.min.js' type='text/javascript'></script>";
	  
    $foot .= "<script>"

      . '$(function() {'
        . 'var table = '
        . '$("#tableboxy").dataTable({'
          . '"order": [[ 0, "desc" ]],'
        . '});'
      
        . 'ambil_data(table, 0);'
      
      . '});'
      
      . 'function ambil_data(table, mulai){'
        . '$.post("'.site_url("mrp/mrp-ajax/get-mrp-list-rg").'/"+mulai, function(data){'
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
  
    $this->template->build('main-mrp/list-rg', 
      array(
            'url'           => base_url()."themes/".DEFAULTTHEMES."/",
            'menu'          => "mrp/list-rg",
            'title'         => lang("Receiving Goods"),
            'foot'          => $foot,
            'css'           => $css,
          ));
    $this->template
      ->set_layout('default')
      ->build('main-mrp/list-rg');
  }
  
    function list_rg_department($id_mrp_po){

//       $data = $this->global_models->get_query("SELECT B.id_hr_pegawai,C.jumlah,D.id_hr_master_organisasi,D.id_hr_company"
//        . " FROM mrp_task_orders_request AS A"
//        . " INNER JOIN mrp_request AS B ON A.id_mrp_request = B.id_mrp_request"
//        . " INNER JOIN mrp_request_asset AS C ON B.id_mrp_request = C.id_mrp_request"
//        . " INNER JOIN hr_pegawai AS D ON B.id_hr_pegawai = D.id_hr_pegawai"
////        . " WHERE A.id_mrp_task_orders='5' AND C.id_mrp_inventory_spesifik='1'"
//        );  
//       print $this->db->last_query();
//        print_r($data); die;
        
      $css = "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/datatables/dataTables.bootstrap.css' rel='stylesheet' type='text/css' />"
      . "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/jQueryUI/jquery-ui-1.10.3.custom.min.css' rel='stylesheet' type='text/css' />";
    
    $foot = ""
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/datatables/jquery.dataTables.js' type='text/javascript'></script>"
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/datatables/dataTables.bootstrap.js' type='text/javascript'></script>"
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/jquery.ui.autocomplete.min.js' type='text/javascript'></script>";
	  
    $foot .= "<script>"

      . '$(function() {'
        . 'var table = '
        . '$("#tableboxy").dataTable({'
          . '"order": [[ 0, "desc" ]],'
        . '});'
      
        . 'ambil_data(table, 0);'
      
      . '});'
      
      . 'function ambil_data(table, mulai){'
        . '$.post("'.site_url("mrp/mrp-ajax/get-mrp-list-rg-department").'/"+mulai, function(data){'
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
  
    $this->template->build('main-mrp/list-rg-depatment', 
      array(
            'url'           => base_url()."themes/".DEFAULTTHEMES."/",
            'menu'          => "mrp/list-rg-department",
            'title'         => lang("Receiving Goods"),
            'foot'          => $foot,
            'css'           => $css,
          ));
    $this->template
      ->set_layout('default')
      ->build('main-mrp/list-rg-depatment');
  }
  
    function detail_rg($id_mrp_receiving_goods_po){

      $css = "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/datatables/dataTables.bootstrap.css' rel='stylesheet' type='text/css' />"
      . "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/jQueryUI/jquery-ui-1.10.3.custom.min.css' rel='stylesheet' type='text/css' />";
    
    $foot = ""
      . "<script src='".base_url()."themes/lte/js/plugins/tooltipster-master/js/jquery.tooltipster.min.js' type='text/javascript'></script>"
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/datatables/jquery.dataTables.js' type='text/javascript'></script>"
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/datatables/dataTables.bootstrap.js' type='text/javascript'></script>"
//      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/jquery.ui.autocomplete.min.js' type='text/javascript'></script>"
            ;
	  
    $foot .= "<script>"

      . '$(function() {'
        . 'var table = '
        . '$("#tableboxy").dataTable({'
          . '"order": [[ 1, "desc" ]],'
        . '});'
      
        . 'ambil_data(table, 0);'
        
      . '});'
      
      . 'function ambil_data(table, mulai){'
        . '$.post("'.site_url("mrp/mrp-ajax/get-mrp-detail-rg/{$id_mrp_receiving_goods_po}").'/"+mulai, function(data){'
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
  
    $this->template->build('main-mrp/detail-rg', 
      array(
            'url'           => base_url()."themes/".DEFAULTTHEMES."/",
            'menu'          => "mrp/list-rg",
            'title'         => lang("Receiving Goods"),
            'foot'          => $foot,
            'css'           => $css,
            'breadcrumb'  => array(
                    "mrp_list_rg"  => "mrp/list-rg"
                ),
          ));
    $this->template
      ->set_layout('default')
      ->build('main-mrp/detail-rg');
  }
  
  function detail_rg_department($id_mrp_receiving_goods_po){

      $css = "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/datatables/dataTables.bootstrap.css' rel='stylesheet' type='text/css' />"
      . "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/jQueryUI/jquery-ui-1.10.3.custom.min.css' rel='stylesheet' type='text/css' />";
    
    $foot = ""
      . "<script src='".base_url()."themes/lte/js/plugins/tooltipster-master/js/jquery.tooltipster.min.js' type='text/javascript'></script>"
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/datatables/jquery.dataTables.js' type='text/javascript'></script>"
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/datatables/dataTables.bootstrap.js' type='text/javascript'></script>"
//      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/jquery.ui.autocomplete.min.js' type='text/javascript'></script>"
            ;
	  
    $foot .= "<script>"

      . '$(function() {'
        . 'var table = '
        . '$("#tableboxy").dataTable({'
          . '"order": [[ 1, "desc" ]],'
        . '});'
      
        . 'ambil_data(table, 0);'
        
      . '});'
      
      . 'function ambil_data(table, mulai){'
        . '$.post("'.site_url("mrp/mrp-ajax/get-mrp-detail-rg/{$id_mrp_receiving_goods_po}").'/"+mulai, function(data){'
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
  
    $this->template->build('main-mrp/detail-rg', 
      array(
            'url'           => base_url()."themes/".DEFAULTTHEMES."/",
            'menu'          => "mrp/list-rg-department",
            'title'         => lang("Receiving Goods"),
            'foot'          => $foot,
            'css'           => $css,
            'breadcrumb'  => array(
                    "mrp_list_rg"  => "mrp/list-rg-department"
                ),
          ));
    $this->template
      ->set_layout('default')
      ->build('main-mrp/detail-rg');
  }
    function rg($id_mrp_receiving_goods_po) {
        
        $set = array(
            "jml_rg_dpt"          => 0
        );
        $this->session->set_userdata($set);
        
       if(!$this->input->post(NULL)){
        
//    $list = $this->global_models->get_query("SELECT A.id_mrp_po,A.no_po,A.status,A.note,A.tanggal_dikirim,A.status"
//        . ",B.name,B.pic,B.phone,B.fax,B.address,B.id_mrp_supplier"
//        . ",C.title AS nama_perusahaan,C.office,C.address AS alamat_perusahaan,C.id_hr_company"
//        . " FROM mrp_po AS A"
//        . " LEFT JOIN mrp_supplier AS B ON A.id_mrp_supplier = B.id_mrp_supplier"
//        . " LEFT JOIN hr_company AS C ON A.id_hr_company = C.id_hr_company"    
//        . " WHERE A.id_mrp_po = '{$id_mrp_po}' "
////        . " GROUP BY A.id_mrp_po "
//       );
        
    $css = "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/datatables/dataTables.bootstrap.css' rel='stylesheet' type='text/css' />"
      . "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/jQueryUI/jquery-ui-1.10.3.custom.min.css' rel='stylesheet' type='text/css' />";
      $url = base_url('mrp/list-rg');
      $url2 = base_url("mrp/rg/{$id_mrp_receiving_goods_po}");
    $foot = ""
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/datatables/jquery.dataTables.js' type='text/javascript'></script>"
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/datatables/dataTables.bootstrap.js' type='text/javascript'></script>"
     ;
	
    $foot .= "<script>"
                               
      . '$(function() {'
              . "$( '.date' ).datepicker({"
              . "showOtherMonths: true,"
              . "dateFormat: 'yy-mm-dd',"  
              . "selectOtherMonths: true,"  
              . "selectOtherYears: true,"
              . "});"
            
//            . "$('#note_po').keyup(function(){"
//            . "var tgl_kirim = $('#tgl_dikirim').val();"
//            . "var dt_note = $('#note_po').val();"
//            ."var dataString2 = 'tanggal='+tgl_kirim +'&note=' + dt_note;"
//                ."$.ajax({"
//                ."type : 'POST',"
//                ."url : '".site_url("mrp/mrp-ajax/update-detail-po/{$id_mrp_po}")."',"
//                ."data: dataString2,"
//                ."dataType : 'html',"
//                ."success: function(data) {"
//                ."},"
//            ."});"
//       . "});"
       
       . "$('#btn-save').click(function(){"
            ."$('#btn-save').hide();"
            ."$('#loader-page').show();"
            ."$('#loader-page2').show();"  
            . "var oTable = $('#tableboxy').dataTable();"
            . "var aData = [];"
            . "var rowcollection =  oTable.$('.rg', {'page': 'all'});"
            . "rowcollection.each(function(index,elem){"
            . "var value_rg = parseInt($(elem).val());"
            . " var hasil = (isNaN(value_rg)) ? 0 : value_rg;"
            . "aData.push( hasil );"
            . "});"
                        
            . "var oTable = $('#tableboxy').dataTable();"
            . "var aData2 = [];"
            . "var rowcollection2 =  oTable.$('.id_mrp_receiving_goods_po_asset', {'page': 'all'});"
            . "rowcollection2.each(function(index,elem){"
            . "var value_id_mrp_receiving_goods_po_asset = parseInt($(elem).val());"
            . " var hasil2 = (isNaN(value_id_mrp_receiving_goods_po_asset)) ? 0 : value_id_mrp_receiving_goods_po_asset;"
            . "aData2.push( hasil2 );"
            . "});"
//            . "alert(aData);"
//            . "alert(aData2);"
           
            . "var tgl_diterima = $('#tgl_diterima').val();"
                . "if(tgl_diterima ==''){"
                    . "alert('Tanggal diterima tidak boleh kosong');"
                    ."window.location ='{$url2}'"
                . "}"
            . "var dt_note = $('#note').val();"
//           . "alert(aData);"
            
                                  
            ."var dataString2 = 'id_mrp_receiving_goods_po_asset='+ aData2 +'&rg=' + aData +'&tgl_diterima='+tgl_diterima+'&note='+dt_note;"
                ."$.ajax({"
                ."type : 'POST',"
                ."url : '".site_url("mrp/mrp-ajax/update-rg/{$id_mrp_receiving_goods_po}")."',"
                ."data: dataString2,"
                ."dataType : 'html',"
                ."success: function(data) {"
                       
                    . 'var hasil = $.parseJSON(data);'
                    . "if(hasil.status == 2){"
                        ."window.location ='{$url2}'"
                    . "}else{"
                        ."window.location ='{$url}'"
                    . "}"
                ."},"
             ."});"
       . "});"
                                
       . "$('#history-rg2').click(function(){"
            . 'var table = '
            . '$("#tableboxy2").dataTable({'
                . '"order": [[ 1, "desc" ]],'
                . '"bDestroy": true'                    
            . '});'
                . "table.fnClearTable();"                   
                . 'ambil_data2(table, 0);'
                          
       . "});"                         
                            
        . 'var table = '
        . '$("#tableboxy").dataTable({'
          . '"order": [[ 0, "asc" ]],'
        . '});'
        . 'ambil_data(table, 0, 0);'
      . '});'
            
     . 'function ambil_data(table, mulai){'
        . '$.post("'.site_url("mrp/mrp-ajax/get-rg/{$id_mrp_receiving_goods_po}").'/"+mulai, function(data){'
          . '$("#loader-page").show();'
          . 'var hasil = $.parseJSON(data);'
          . 'if(hasil.status == 2){'
            . 'table.fnAddData(hasil.hasil);'
            . 'ambil_data(table, hasil.start);'
//           . "$('#dt-total').text(parseFloat(hasil.dt_total, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,').toString());"
          . '}'
          . 'else{'
            . '$("#loader-page").hide();'
//            . 'load_tooltip();'
//            . 'load_tooltip_harga();'
//            . "$('#script-tambahan').html(hasil.dt_total);"
          . '}'
        . '});'
      . '}'
      
      . 'function ambil_data2(table, mulai){'
        . '$.post("'.site_url("mrp/mrp-ajax/get-mrp-detail-rg/{$id_mrp_receiving_goods_po}").'/"+mulai, function(data){'
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
                
      . "</script>"; 
        
//    $id_mrp_supplier =$this->global_models->get_field("mrp_task_orders_request_asset", "id_mrp_supplier", array("id_mrp_task_orders" => $id_mrp_task_orders));
//    $nama_supplier =$this->global_models->get_field("mrp_supplier", "name", array("id_mrp_supplier" => $id_mrp_supplier));
//     $id_mrp_task_orders = 0,$id_mrp_po 
         $status = array( 3=> "<span class='label bg-orange'>Draft PO</span>", 4 => "<span class='label bg-green'>Proses Pengajuan PO</span>",
      5 => "<span class='label bg-green'>Approved PO</span>", 6 =>"<span class='label bg-green'>Send PO</span>");
    
    $this->template->build("main-mrp/rg/view-rg", 
        array(
              'url'                 => base_url()."themes/".DEFAULTTHEMES."/",
              'menu'                => 'mrp/list-po',
              'title'               => lang("Receiving Goods"),
              'list'                => $list,
              'id_mrp_task_orders'  => $id_mrp_task_orders,
              'id_mrp_po'           => $id_mrp_po,
              'dt_status'              => $status,
              'breadcrumb'  => array(
                    "mrp_list_rg"  => "mrp/list-rg"
                ),
              'css'         => $css,
              'foot'        => $foot,
//              'img'       => base_url()."themes/".DEFAULTTHEMES."/img/ajax-loader.gif"
//              'items'       => $items
            ));
      $this->template
        ->set_layout('form')
        ->build("main-mrp/rg/view-rg");
    }
  }
  
  function rg_department($id_mrp_receiving_goods_po,$id_mrp_request) {
      
        $set = array(
            "jml_rg_dpt"          => 0
        );
        $this->session->set_userdata($set);
        
       if(!$this->input->post(NULL)){
        
//    $list = $this->global_models->get_query("SELECT A.id_mrp_po,A.no_po,A.status,A.note,A.tanggal_dikirim,A.status"
//        . ",B.name,B.pic,B.phone,B.fax,B.address,B.id_mrp_supplier"
//        . ",C.title AS nama_perusahaan,C.office,C.address AS alamat_perusahaan,C.id_hr_company"
//        . " FROM mrp_po AS A"
//        . " LEFT JOIN mrp_supplier AS B ON A.id_mrp_supplier = B.id_mrp_supplier"
//        . " LEFT JOIN hr_company AS C ON A.id_hr_company = C.id_hr_company"    
//        . " WHERE A.id_mrp_po = '{$id_mrp_po}' "
////        . " GROUP BY A.id_mrp_po "
//       );
        
    $css = "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/datatables/dataTables.bootstrap.css' rel='stylesheet' type='text/css' />"
      . "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/jQueryUI/jquery-ui-1.10.3.custom.min.css' rel='stylesheet' type='text/css' />";
      $url = base_url('mrp/list-rg-department');
      $url2 = base_url("mrp/rg-department/{$id_mrp_receiving_goods_po}/{$id_mrp_request}");
    $foot = ""
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/datatables/jquery.dataTables.js' type='text/javascript'></script>"
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/datatables/dataTables.bootstrap.js' type='text/javascript'></script>"
     ;
	
    $foot .= "<script>"
                               
      . '$(function() {'
              . "$( '.date' ).datepicker({"
              . "showOtherMonths: true,"
              . "dateFormat: 'yy-mm-dd',"  
              . "selectOtherMonths: true,"  
              . "selectOtherYears: true,"
              . "});"
            
       . "$('#btn-save').click(function(){"
            ."$('#btn-save').hide();"
            ."$('#loader-page').show();"
            ."$('#loader-page2').show();" 
            . "var oTable = $('#tableboxy').dataTable();"
            . "var aData = [];"
            . "var rowcollection =  oTable.$('.rg', {'page': 'all'});"
            . "rowcollection.each(function(index,elem){"
            . "var value_rg = parseInt($(elem).val());"
            . " var hasil = (isNaN(value_rg)) ? 0 : value_rg;"
            . "aData.push( hasil );"
            . "});"
                        
            . "var oTable = $('#tableboxy').dataTable();"
            . "var aData2 = [];"
            . "var rowcollection2 =  oTable.$('.id_mrp_receiving_goods_po_asset', {'page': 'all'});"
            . "rowcollection2.each(function(index,elem){"
            . "var value_id_mrp_receiving_goods_po_asset = parseInt($(elem).val());"
            . " var hasil2 = (isNaN(value_id_mrp_receiving_goods_po_asset)) ? 0 : value_id_mrp_receiving_goods_po_asset;"
            . "aData2.push( hasil2 );"
            . "});"
            
            . "var oTable = $('#tableboxy').dataTable();"
            . "var aData3 = [];"
            . "var rowcollection3 =  oTable.$('.id_mrp_request', {'page': 'all'});"
            . "rowcollection3.each(function(index,elem){"
            . "var value_id_mrp_request = parseInt($(elem).val());"
            . " var hasil3 = (isNaN(value_id_mrp_request)) ? 0 : value_id_mrp_request;"
            . "aData3.push( hasil3 );"
            . "});"
//            . "alert(aData3);"
//            . "alert(aData2);"
           
            . "var tgl_diterima = $('#tgl_diterima').val();"
                . "if(tgl_diterima ==''){"
                    . "alert('Tanggal diterima tidak boleh kosong');"
                    ."window.location ='{$url2}'"
                . "}"
            . "var dt_note = $('#note').val();"
//           . "alert(aData);"
            
                                  
            ."var dataString2 = 'id_mrp_receiving_goods_po_asset='+ aData2 +'&rg=' + aData +'&id_mrp_request='+ aData3 +'&tgl_diterima='+tgl_diterima+'&note='+dt_note;"
                ."$.ajax({"
                ."type : 'POST',"
                ."url : '".site_url("mrp/mrp-ajax/update-rg-department/{$id_mrp_receiving_goods_po}")."',"
                ."data: dataString2,"
                ."dataType : 'html',"
                ."success: function(data) {"
                    . 'var hasil = $.parseJSON(data);'
                    . "if(hasil.status == 2){"
                        ."window.location ='{$url2}'"
                    . "}else{"
                        ."window.location ='{$url}'"
                    . "}"
                ."},"
             ."});"
       . "});"
       . "$('#history-rg-department2').click(function(){"
            . 'var table = '
            . '$("#tableboxy2").dataTable({'
                . '"order": [[ 1, "desc" ]],'
                . '"bDestroy": true'                    
            . '});'
                . "table.fnClearTable();"                   
                . 'ambil_data2(table, 0);'
       . "});"                         
                            
        . 'var table = '
        . '$("#tableboxy").dataTable({'
          . '"order": [[ 0, "asc" ]],'
        . '});'
        . 'ambil_data(table, 0, 0);'
      . '});'
            
     . 'function ambil_data(table, mulai){'
        . '$.post("'.site_url("mrp/mrp-ajax/get-rg-department/{$id_mrp_receiving_goods_po}/{$id_mrp_request}").'/"+mulai, function(data){'
          . '$("#loader-page").show();'
          . 'var hasil = $.parseJSON(data);'
          . 'if(hasil.status == 2){'
            . 'table.fnAddData(hasil.hasil);'
            . 'ambil_data(table, hasil.start);'
//           . "$('#dt-total').text(parseFloat(hasil.dt_total, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,').toString());"
          . '}'
          . 'else{'
            . '$("#loader-page").hide();'
//            . 'load_tooltip();'
//            . 'load_tooltip_harga();'
//            . "$('#script-tambahan').html(hasil.dt_total);"
          . '}'
        . '});'
      . '}'
      . 'function ambil_data2(table, mulai){'
        . '$.post("'.site_url("mrp/mrp-ajax/get-mrp-detail-rg-department/{$id_mrp_receiving_goods_po}").'/"+mulai, function(data){'
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
	  . "</script>"; 
        
//    $id_mrp_supplier =$this->global_models->get_field("mrp_task_orders_request_asset", "id_mrp_supplier", array("id_mrp_task_orders" => $id_mrp_task_orders));
//    $nama_supplier =$this->global_models->get_field("mrp_supplier", "name", array("id_mrp_supplier" => $id_mrp_supplier));
//     $id_mrp_task_orders = 0,$id_mrp_po 
         $status = array( 3=> "<span class='label bg-orange'>Draft PO</span>", 4 => "<span class='label bg-green'>Proses Pengajuan PO</span>",
      5 => "<span class='label bg-green'>Approved PO</span>", 6 =>"<span class='label bg-green'>Send PO</span>");
    
    $this->template->build("main-mrp/rg-department/view-rg-department", 
        array(
              'url'                 => base_url()."themes/".DEFAULTTHEMES."/",
              'menu'                => 'mrp/list-rg-department',
              'title'               => lang("Receiving Goods"),
              'list'                => $list,
              'id_mrp_task_orders'  => $id_mrp_task_orders,
              'id_mrp_po'           => $id_mrp_po,
              'dt_status'              => $status,
              'breadcrumb'  => array(
                    "mrp_list_rg_department"  => "mrp/list-rg-department"
                ),
              'css'         => $css,
              'foot'        => $foot,
//              'img'       => base_url()."themes/".DEFAULTTHEMES."/img/ajax-loader.gif"
//              'items'       => $items
            ));
      $this->template
        ->set_layout('form')
        ->build("main-mrp/rg-department/view-rg-department");
    }
  }
  
  function history_detail_rg($id_mrp_po,$id_mrp_receiving_goods) {
       if(!$this->input->post(NULL)){
        
    $css = "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/datatables/dataTables.bootstrap.css' rel='stylesheet' type='text/css' />"
      . "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/jQueryUI/jquery-ui-1.10.3.custom.min.css' rel='stylesheet' type='text/css' />";
    $url = base_url('mrp/list-rg');
    $foot = ""
      . "<script src='".base_url()."themes/lte/js/plugins/tooltipster-master/js/jquery.tooltipster.min.js' type='text/javascript'></script>"
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/datatables/jquery.dataTables.js' type='text/javascript'></script>"
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/datatables/dataTables.bootstrap.js' type='text/javascript'></script>"
 
//     . "<script src='http://ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js'></script>"
      ;
	
    $foot .= "<script>"
                               
      . '$(function() {'
//       ."$('#aa').click(function(){"
//            . "alert('aa');"
//            . "});"

            
       . "$('#btn-save').click(function(){"
                  
            . "var oTable = $('#tableboxy').dataTable();"
            . "var aData = [];"
            . "var rowcollection =  oTable.$('.rg', {'page': 'all'});"
            . "rowcollection.each(function(index,elem){"
            . "var value_rg = parseInt($(elem).val());"
            . " var hasil = (isNaN(value_rg)) ? 0 : value_rg;"
            . "aData.push( hasil );"
            . "});"
                        
            . "var oTable = $('#tableboxy').dataTable();"
            . "var aData2 = [];"
            . "var rowcollection2 =  oTable.$('.id_mrp_task_orders_request_asset', {'page': 'all'});"
            . "rowcollection2.each(function(index,elem){"
            . "var value_id_mrp_task_orders_request_asset = parseInt($(elem).val());"
            . " var hasil2 = (isNaN(value_id_mrp_task_orders_request_asset)) ? 0 : value_id_mrp_task_orders_request_asset;"
            . "aData2.push( hasil2 );"
            . "});"
           
            . "var tgl_diterima = $('#tgl_diterima').val();"
            . "var dt_note = $('#note').val();"
                                  
            ."var dataString2 = 'id_mrp_task_orders_request_asset='+ aData2 +'&rg=' + aData +'&tgl_diterima='+tgl_diterima+'&note='+dt_note;"
                ."$.ajax({"
                ."type : 'POST',"
                ."url : '".site_url("mrp/mrp-ajax/update-rg/{$id_mrp_po}")."',"
                ."data: dataString2,"
                ."dataType : 'html',"
                ."success: function(data) {"
                    ."window.location ='{$url}'"
                ."},"
             ."});"
       . "});"
                            
        . 'var table = '
        . '$("#tableboxy").dataTable({'
          . '"order": [[ 0, "asc" ]],'
        . '});'
        . 'ambil_data(table, 0, 0);'
        
        . 'var table = '
        . '$("#tableboxy1").dataTable({'
          . '"order": [[ 0, "asc" ]],'
        . '});'
        . 'ambil_data(table, 0, 0);'
      . '});'
            
     . 'function ambil_data(table, mulai){'
        . '$.post("'.site_url("mrp/mrp-ajax/list-history-detail-rg/{$id_mrp_receiving_goods}").'/"+mulai, function(data){'
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
        
    $code =$this->global_models->get_field("mrp_po", "code", array("id_mrp_po" => $id_mrp_po));
    
    $mrp_receiving_goods = $this->global_models->get("mrp_receiving_goods", array("id_mrp_receiving_goods" => $id_mrp_receiving_goods));
//    $nama_supplier =$this->global_models->get_field("mrp_supplier", "name", array("id_mrp_supplier" => $id_mrp_supplier));
//     $id_mrp_task_orders = 0,$id_mrp_po 
        
    $this->template->build("main-mrp/history-detail-rg", 
        array(
              'url'                 => base_url()."themes/".DEFAULTTHEMES."/",
              'menu'                => 'mrp/list-rg',
              'title'               => lang("History Detail Receiving Goods"),
              'list'                => $mrp_receiving_goods,
              'id_mrp_task_orders'  => $id_mrp_task_orders,
              'code_po'           => $code,
              'dt_status'              => $status,
              'breadcrumb'  => array(
                    "Receiving Goods"  => "mrp/list-rg"
                ),
              'css'         => $css,
              'foot'        => $foot,
//              'img'       => base_url()."themes/".DEFAULTTHEMES."/img/ajax-loader.gif"
//              'items'       => $items
            ));
      $this->template
        ->set_layout('form')
        ->build("main-mrp/history-detail-rg");
    }
  }

  function stock_detail($id_mrp_inventory_spesifik){
     
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
        . "ambil_data(table, 0);"
      . '});'
            
     . 'function ambil_data(table, mulai){'
        . '$.post("'.site_url("mrp/mrp-ajax/get-stock-detail/{$id_mrp_inventory_spesifik}").'/"+mulai, function(data){'
          . '$("#loader-page").show();'
          . 'var hasil = $.parseJSON(data);'
          . 'if(hasil.status == 2){'
            . 'table.fnAddData(hasil.hasil);'
            . "ambil_data(table, hasil.start);"
          . '}'
          . 'else{'
            . '$("#loader-page").hide();'
          . '}'
        . '});'
      . '}'
	  . "</script>"; 
        
    $data = $this->global_models->get_query("SELECT B.name AS nama_barang,A.title AS title_spesifik"
        . " FROM mrp_inventory_spesifik AS A"
        . " LEFT JOIN mrp_inventory_umum AS B ON A.id_mrp_inventory_umum = B.id_mrp_inventory_umum"
        . " WHERE A.id_mrp_inventory_spesifik ='{$id_mrp_inventory_spesifik}'"
        );
//        print ($data[0]->nama_barang); die;
    $this->template->build("main-mrp/stock-detail", 
        array(
              'url'                 => base_url()."themes/".DEFAULTTHEMES."/",
              'menu'                => 'mrp/stock',
              'title'               => lang("Detail Stock"),
              'list'                => $data,
//              'id_mrp_task_orders'  => $id_mrp_task_orders,
//              'id_mrp_po'           => $id_mrp_po,
//              'dt_status'              => $status,
              'breadcrumb'  => array(
                    "Stock"  => "mrp/stock"
                ),
              'css'         => $css,
              'foot'        => $foot,
//              'img'       => base_url()."themes/".DEFAULTTHEMES."/img/ajax-loader.gif"
//              'items'       => $items
            ));
      $this->template
        ->set_layout('form')
        ->build("main-mrp/stock-detail");
}

function delete_list_po($id){
       
    $this->global_models->delete("mrp_po", array("id_mrp_po" => $id));
        
    $this->session->set_flashdata('success', 'Data Berhasil di Hapus');
     redirect("mrp/list-po");
    die;
  }
  
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */