<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mrp_request extends MX_Controller {
    
  function __construct() {      
    $this->menu = $this->cek();
  }
  
  function request_pengadaan_cetakan_rutin(){
      
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
         // . '"order": [[ 0, "desc" ]],'
		  . ' "aaSorting": []'
//          . "'iDisplayLength': 20"
        . '});'
      
        . "ambil_data(table, 0,{$this->session->userdata("id")});"
      
      . '});'
                
      . "$(document).on('click', '#id-customer-cancel', function(evt){"
        . "var id = $(this).attr('isi');"
        . "$('#id_request').val(id);"
      . "});"
                
      . 'function ambil_data(table, mulai,id_users){'
        . '$.post("'.site_url("mrp/mrp-ajax-request/get-mrp-request-pengadaan-cetakan-rutin").'/"+mulai+"/"+id_users, function(data){'
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
    
     $link = site_url('mrp/cancel-request/11');
     $before_table = "<div class='modal fade' id='edit-keterangan-cancel' tabindex='-1' role='dialog' aria-hidden='true'>
    <div class='modal-dialog'>
        <div class='modal-content'>
            <div class='modal-header'>
                <button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
                <h4 class='modal-title'>Note Delete</h4>
            </div>
            <form action='{$link}' method='post'>
                <div class='modal-body'>
                    <div class='form-group'>
                        <div class='input-group'>
<!--                            <span class='input-group-addon'>Note Cancel:</span>-->
                            <input name='id_request' class='form-control' id='id_request' style='display: none'>
                            <textarea name='note_delete' placeholder='Note Delete' style='margin: 0px; width: 553px; height: 227px;'></textarea>
                        </div>
                    </div>
                </div>
                <div class='modal-footer clearfix'>

                    <button type='button' class='btn btn-danger' data-dismiss='modal'><i class='fa fa-times'></i> Cancel</button>

                    <button type='submit' class='btn btn-primary pull-left'> Submit</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>";
            
    
    $this->template->build('mrp-request/pengadaan-cetakan-rutin/request-pengadaan-cetakan-rutin', 
      array(
            'url'           => base_url()."themes/".DEFAULTTHEMES."/",
            'menu'          => "mrp/mrp-request/request-pengadaan-cetakan-rutin",
            'title'         => lang("Form Request Pengadaan Cetakan Rutin"),
            'foot'          => $foot,
            'css'           => $css,
            'before_table'  => $before_table,
//            'type'          => $type,
//            'jenis'         => $jenis,
//            'brand'         => $brand,
          ));
    $this->template
      ->set_layout('default')
      ->build('mrp-request/pengadaan-cetakan-rutin/request-pengadaan-cetakan-rutin');
  }
  
  function request_pengadaan_cetakan_invoice(){
      
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
         // . '"order": [[ 0, "desc" ]],'
		  . ' "aaSorting": []'
//          . "'iDisplayLength': 20"
        . '});'
      
        . "ambil_data(table, 0,{$this->session->userdata("id")});"
      
      . '});'
                
      . "$(document).on('click', '#id-customer-cancel', function(evt){"
        . "var id = $(this).attr('isi');"
        . "$('#id_request').val(id);"
      . "});"
                
      . 'function ambil_data(table, mulai,id_users){'
        . '$.post("'.site_url("mrp/mrp-ajax-request/get-mrp-request-pengadaan-cetakan-invoice").'/"+mulai+"/"+id_users, function(data){'
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
    
     $link = site_url('mrp/cancel-request/10');
     $before_table = "<div class='modal fade' id='edit-keterangan-cancel' tabindex='-1' role='dialog' aria-hidden='true'>
    <div class='modal-dialog'>
        <div class='modal-content'>
            <div class='modal-header'>
                <button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
                <h4 class='modal-title'>Note Delete</h4>
            </div>
            <form action='{$link}' method='post'>
                <div class='modal-body'>
                    <div class='form-group'>
                        <div class='input-group'>
<!--                            <span class='input-group-addon'>Note Cancel:</span>-->
                            <input name='id_request' class='form-control' id='id_request' style='display: none'>
                            <textarea name='note_delete' placeholder='Note Delete' style='margin: 0px; width: 553px; height: 227px;'></textarea>
                        </div>
                    </div>
                </div>
                <div class='modal-footer clearfix'>

                    <button type='button' class='btn btn-danger' data-dismiss='modal'><i class='fa fa-times'></i> Cancel</button>

                    <button type='submit' class='btn btn-primary pull-left'> Submit</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>";
            
    
    $this->template->build('mrp-request/pengadaan-cetakan-invoice/request-pengadaan-cetakan-invoice', 
      array(
            'url'           => base_url()."themes/".DEFAULTTHEMES."/",
            'menu'          => "mrp/mrp-request/request-pengadaan-cetakan-invoice",
            'title'         => lang("Form Request Pengadaan Cetakan Invoice"),
            'foot'          => $foot,
            'css'           => $css,
            'before_table'  => $before_table,
//            'type'          => $type,
//            'jenis'         => $jenis,
//            'brand'         => $brand,
          ));
    $this->template
      ->set_layout('default')
      ->build('mrp-request/pengadaan-cetakan-invoice/request-pengadaan-cetakan-invoice');
  }
  
    function request_pengadaan_umum() {
      
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
         // . '"order": [[ 0, "desc" ]],'
		  . ' "aaSorting": []'
//          . "'iDisplayLength': 20"
        . '});'
      
        . "ambil_data(table, 0,{$this->session->userdata("id")});"
      
      . '});'
                
      . "$(document).on('click', '#id-customer-cancel', function(evt){"
        . "var id = $(this).attr('isi');"
        . "$('#id_request').val(id);"
      . "});"
                
      . 'function ambil_data(table, mulai,id_users){'
        . '$.post("'.site_url("mrp/mrp-ajax-request/get-mrp-request-pengadaan-umum").'/"+mulai+"/"+id_users, function(data){'
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
    
     $link = site_url('mrp/cancel-request/9');
     $before_table = "<div class='modal fade' id='edit-keterangan-cancel' tabindex='-1' role='dialog' aria-hidden='true'>
    <div class='modal-dialog'>
        <div class='modal-content'>
            <div class='modal-header'>
                <button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
                <h4 class='modal-title'>Note Delete</h4>
            </div>
            <form action='{$link}' method='post'>
                <div class='modal-body'>
                    <div class='form-group'>
                        <div class='input-group'>
<!--                            <span class='input-group-addon'>Note Cancel:</span>-->
                            <input name='id_request' class='form-control' id='id_request' style='display: none'>
                            <textarea name='note_delete' placeholder='Note Delete' style='margin: 0px; width: 553px; height: 227px;'></textarea>
                        </div>
                    </div>
                </div>
                <div class='modal-footer clearfix'>

                    <button type='button' class='btn btn-danger' data-dismiss='modal'><i class='fa fa-times'></i> Cancel</button>

                    <button type='submit' class='btn btn-primary pull-left'> Submit</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>";
            
    
    $this->template->build('mrp-request/pengadaan-umum/request-pengadaan-umum', 
      array(
            'url'           => base_url()."themes/".DEFAULTTHEMES."/",
            'menu'          => "mrp/mrp-request/request-pengadaan-umum",
            'title'         => lang("Form Request Pengadaan Transportation"),
            'foot'          => $foot,
            'css'           => $css,
            'before_table'  => $before_table,
//            'type'          => $type,
//            'jenis'         => $jenis,
//            'brand'         => $brand,
          ));
    $this->template
      ->set_layout('default')
      ->build('mrp-request/pengadaan-umum/request-pengadaan-umum');
  }
  
      public function add_request_pengadaan_cetakan_invoice($id_mrp_request = 0){
      
    if(!$this->input->post(NULL)){
        
    $css = "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/datatables/dataTables.bootstrap.css' rel='stylesheet' type='text/css' />"
      . "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/jQueryUI/jquery-ui-1.10.3.custom.min.css' rel='stylesheet' type='text/css' />"
      . "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/select2.css' type='text/css' rel='stylesheet'>";
      $url = base_url('mrp/mrp-request/request-pengadaan-cetakan-invoice');
      $url2 = base_url("mrp/mrp-request/add-request-pengadaan-cetakan-invoice/{$id_mrp_request}");
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
                        ."url : '".site_url("mrp/mrp-ajax-request/insert-form-mrp-request-pengadaan/10")."',"
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
                        ."url : '".site_url("mrp/mrp-ajax-request/draft-form-mrp-request-pengadaan/10")."',"
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
            
            . "var aData2 = [];"
            . "var rowcollection2 =  oTable.$('.jumlah_rg', {'page': 'all'});"
            . "rowcollection2.each(function(index,elem){"
            . "var value_jumlah_rg = parseInt($(elem).val());"
            . " var hasil3 = (isNaN(value_jumlah_rg)) ? 0 : value_jumlah_rg;"
            . "aData2.push( hasil3 );"
            . "});"
                        ."var dataString2 = 'id_spesifik='+ aData +'&jumlah='+ aData1 +'&jumlah_rg=' + aData2 +'&note=' + note2+'&id_hr_pegawai='+ id_hr_pegawai +'&id_receiver='+ id_receiver2 +'&id_mrp_request='+ {$id_mrp_request};"
                        ."$.ajax({"
                        ."type : 'POST',"
                        ."url : '".site_url("mrp/mrp-ajax-request/update-form-mrp-request-pengadaan/10")."',"
                        ."data: dataString2,"
                        ."dataType : 'html',"
                        ."success: function(data) {"
                            ."window.location ='{$url2}'"
                        ."},"
                     ."});"
            . "});"
                              
  ."$('#btn-proses').click(function(){"
    
    ."$('#btn-save').hide();"
    ."$('#loader-data').show();"
    ."$('#loader-page').show();"  
    . "var oTable = $('#tableboxy4').dataTable();"
    . "var aData = [];"
    . "var rowcollection =  oTable.$('.jumlah_mutasi', {'page': 'all'});"
    . "rowcollection.each(function(index,elem){"
    . "var value_jumlah = parseInt($(elem).val());"
    . " var hasil = (isNaN(value_jumlah)) ? 0 : value_jumlah;"
    . "aData.push( hasil );"
//            . "alert(aData);"
    . "});"

    . "var oTable = $('#tableboxy').dataTable();"
    . "var aData2 = [];"
    . "var rowcollection2 =  oTable.$('.id_spesifik', {'page': 'all'});"
    . "rowcollection2.each(function(index,elem){"
    . "var value_id_spesifik = parseInt($(elem).val());"
    . " var hasil2 = (isNaN(value_id_spesifik)) ? 0 : value_id_spesifik;"
    . "aData2.push( hasil2 );"
//            . "alert(aData2);"
    . "});"
    
    . "var tgl_diserahkan   = $('#tgl_diserahkan').val();"
    . "var id_users         = $('#id_users').val();"
    . "var note_mutasi      = $('#note_mutasi').val();"
        . "if(tgl_diserahkan ==''){"
            . "alert('Tanggal diserahkan tidak boleh kosong');"
//            ."window.location ='{$url2}'"
        . "}else{"
        ."var dataString2 = 'id_mrp_inventory_spesifik='+ aData2 +'&jumlah=' + aData +'&tgl_diserahkan='+tgl_diserahkan+'&note='+note_mutasi+'&id_users='+id_users;"           
        ."$.ajax({"
        ."type : 'POST',"
        ."url : '".site_url("mrp/mrp-ajax/proses-mutasi-stock-request/{$id_mrp_request}")."',"
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
//           . "alert(aData);"              
    
//        . "alert(dataString2);"
        
                        
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
                                    
            . "var aData2 = [];"
            . "var rowcollection2 =  oTable.$('.jumlah_rg', {'page': 'all'});"
            . "rowcollection2.each(function(index,elem){"
            . "var value_jumlah_rg = parseInt($(elem).val());"
            . " var hasil3 = (isNaN(value_jumlah_rg)) ? 0 : value_jumlah_rg;"
            . "aData2.push( hasil3 );"
            . "});"                        
                ."var dataString2 = 'id_spesifik='+ aData +'&jumlah='+ aData1+ '&jumlah_rg='+ aData2 +'&note=' + note2+'&id_hr_pegawai='+ id_hr_pegawai +'&id_receiver='+ id_receiver2 +'&id_mrp_request='+ {$id_mrp_request};"
                ."$.ajax({"
                ."type : 'POST',"
                ."url : '".site_url("mrp/mrp-ajax-request/update-form-mrp-request-pengadaan/10")."',"
                ."data: dataString2,"
                ."dataType : 'html',"
                ."success: function(data) {"
                    ."window.location ='{$url2}'"
                ."},"
             ."});"
        . "});"
   . "});"
                                     
      . '$(function() {'
    . "$( '.date' ).datepicker({"
    . "showOtherMonths: true,"
    . "dateFormat: 'yy-mm-dd',"  
    . "selectOtherMonths: true,"  
    . "selectOtherYears: true,"
    . "});"
            . "$('#req-mutasi-stock2').click(function(){"
            . 'var table = '
            . '$("#tableboxy4").dataTable({'
            . '"order": [[ 0, "asc" ]],'
            . '"bDestroy": true'                         
            . '});'
            . "table.fnClearTable();"                        
            . 'ambil_data4(table, 0);'
            . '});'
                            
            . "$('#history-req-mutasi-stock2').click(function(){"
            . 'var table = '
            . '$("#history-mutasi").dataTable({'
                . '"order": [[ 1, "desc" ]],'
                . '"bDestroy": true'                    
            . '});'
                . "table.fnClearTable();"                   
                . 'history_mutasi(table, 0);'            
       . "});"      
//                                    
//            . "});"
                                    
            . "$('#dt-cetakan-invoice').click(function(){"
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
        . '$.post("'.site_url("mrp/mrp-ajax-request/get-form-mrp-request-pengadaan-cetakan-invoice/{$id_mrp_request}/{$this->session->userdata("id")}/").'/"+mulai, function(data){'
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
        . '$.post("'.site_url("mrp/mrp-ajax-request/get-list-form-mrp-request-pengadaan-cetakan-invoice/{$id_mrp_request}/{$this->session->userdata("id")}/").'/"+mulai, function(data){'
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
                
    . 'function ambil_data4(table, mulai){'
    . '$.post("'.site_url("mrp/mrp-ajax-stock/ajax-form-mrp-request-pengadaan-2/{$id_mrp_request}").'/"+mulai, function(data){'
      . '$("#loader-page4").show();'
      . 'var hasil = $.parseJSON(data);'
      . 'if(hasil.status == 2){'
            . 'if(hasil.flag == 2){'
            . '$("#loader-page4").hide();'
            . '}'
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
            
    . 'function history_mutasi(table, mulai){'
    . '$.post("'.site_url("mrp/mrp-ajax/get-history-mutasi/{$id_mrp_request}").'/"+mulai, function(data){'
      . '$("#loader-history-mutasi").show();'
      . 'var hasil = $.parseJSON(data);'
      . 'if(hasil.status == 2){'
        . 'table.fnAddData(hasil.hasil);'
        . 'history_mutasi(table, hasil.start);'
      . '}'
      . 'else{'
        . '$("#loader-history-mutasi").hide();'
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
      . "$(document).on('click', '#id-reject', function(evt){"
        . "var id = $(this).attr('isi');"
        . "$('#id_request').val(id);"
      . "});"
	  . "</script>";   

    $link = site_url('mrp/reject-request/10');
     $before_table = "<div class='modal fade' id='edit-keterangan-reject' tabindex='-1' role='dialog' aria-hidden='true'>
    <div class='modal-dialog'>
        <div class='modal-content'>
            <div class='modal-header'>
                <button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
                <h4 class='modal-title'>Note Reject</h4>
            </div>
            <form action='{$link}' method='post'>
                <div class='modal-body'>
                    <div class='form-group'>
                        <div class='input-group'>
<!--                            <span class='input-group-addon'>Note Reject:</span>-->
                            <input name='id_request' class='form-control' id='id_request' style='display: none'>
                            <textarea name='note_reject' placeholder='Note Reject' style='margin: 0px; width: 553px; height: 227px;'></textarea>
                        </div>
                    </div>
                </div>
                <div class='modal-footer clearfix'>

                    <button type='button' class='btn btn-danger' data-dismiss='modal'><i class='fa fa-times'></i> Cancel</button>

                    <button type='submit' class='btn btn-primary pull-left'> Submit</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
    </div>";
//       $list = $this->global_models->get("mrp_request", array("id_mrp_request" => $id_mrp_request)); 
         
       $list = $this->global_models->get_query("SELECT A.id_mrp_request,A.status,A.note,A.create_by_users,A.id_hr_pegawai,A.user_pegawai_receiver,A.tanggal_diserahkan,A.note_mutasi,"
        . "B.id_users,C.name,C.email,D.title AS name_organisasi,E.id_hr_pegawai AS pegawai_receiver,F.name AS name_receiver,F.email AS email_receiver"
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
        
        $totl =$this->global_models->get_query("SELECT COUNT(status_blast) AS total FROM mrp_request AS A"
            . " WHERE A.create_by_users='{$this->session->userdata("id")}' AND A.status_blast = 1 AND A.type_inventory = 10 ");
       
      $this->template->build("mrp-request/pengadaan-cetakan-invoice/request-cetakan-invoice/view-request-cetakan-invoice", 
        array(
              'url'         => base_url()."themes/".DEFAULTTHEMES."/",
              'menu'        => 'mrp/mrp-request/request-pengadaan-cetakan-invoice',
              'title'       => lang("mrp_add_request_pengadaan_cetakan_invoice"),
              'list'        => $list,
              'total'       => $totl,
              'before_table' => $before_table,
              'breadcrumb'  => array(
                    "pengadaan_cetakan_invoice"  => "mrp/mrp-request/request-pengadaan-cetakan-invoice"
                ),
              'css'         => $css,
              'foot'        => $foot,
              'img'       => base_url()."themes/".DEFAULTTHEMES."/img/ajax-loader.gif"
//              'items'       => $items
            ));
      $this->template
        ->set_layout('form')
        ->build("mrp-request/pengadaan-cetakan-invoice/request-cetakan-invoice/view-request-cetakan-invoice");
    }
    else{
      $pst = $this->input->post(NULL);
     
//      if($pst['btn_cancel'] == "Cancel"){
//         
//          $kirim = array(
//            "status"                    => 10,  
//            "update_by_users"           => $this->session->userdata("id"),
//            "update_date"               => date("Y-m-d H:i:s")
//        );
//       $this->global_models->update("mrp_request", array("id_mrp_request" => $pst['id_detail_cancel']),$kirim);
//       $ro_kode = $this->global_models->get_field("mrp_request", "code", array("id_mrp_request" => "{$pst['id_detail_cancel']}"));
//       $this->session->set_flashdata('success', "Request Orders dengan kode {$ro_kode} Berhasil di Cancel");
//       redirect("mrp/request-pengadaan-atk");
//      }
      
      if($pst['btn_closed'] == "closed"){
           $kirim = array(
            "status"                    => 9,  
            "update_by_users"           => $this->session->userdata("id"),
            "update_date"               => date("Y-m-d H:i:s")
        );
       $this->global_models->update("mrp_request", array("id_mrp_request" => $pst['id_detail']),$kirim);
       redirect("mrp/mrp-request/request-pengadaan-cetakan-invoice");
      }
      
      if($pst["btn_approval"] == "approve"){
          $kirim = array(
            "status"                    => 3,
            "user_approval"             => $this->session->userdata("id"),  
            "update_by_users"           => $this->session->userdata("id"),
            "update_date"               => date("Y-m-d H:i:s")
        );
       $this->global_models->update("mrp_request", array("id_mrp_request" => $id_mrp_request),$kirim);
       
       $kirim1 = array(
            "id_mrp_request"                => $id_mrp_request,
            "type"                          => 2,
            "status"                        => 1,
            "create_by_users"               => $this->session->userdata("id"),
            "create_date"                   => date("Y-m-d H:i:s")
        );
       
        $this->global_models->insert("temp_alert_email", $kirim1);
        
       $kirim = array(
            "status"                    => 3,
            "update_by_users"           => $this->session->userdata("id"),
            "update_date"               => date("Y-m-d H:i:s")
        );
       $this->global_models->update("mrp_request_asset", array("id_mrp_request" => $id_mrp_request),$kirim);
     
      }
      
      if($pst['btn_reject'] == "reject"){
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
      
      redirect("mrp/mrp-request/request-pengadaan-cetakan-invoice");
    }
  }
  
    public function add_request_pengadaan_cetakan_rutin($id_mrp_request = 0){
      
    if(!$this->input->post(NULL)){
        
    $css = "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/datatables/dataTables.bootstrap.css' rel='stylesheet' type='text/css' />"
      . "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/jQueryUI/jquery-ui-1.10.3.custom.min.css' rel='stylesheet' type='text/css' />"
      . "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/select2.css' type='text/css' rel='stylesheet'>";
      $url = base_url('mrp/mrp-request/request-pengadaan-cetakan-rutin');
      $url2 = base_url("mrp/mrp-request/add-request-pengadaan-cetakan-rutin/{$id_mrp_request}");
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
                        ."url : '".site_url("mrp/mrp-ajax-request/insert-form-mrp-request-pengadaan/11")."',"
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
                        ."url : '".site_url("mrp/mrp-ajax-request/draft-form-mrp-request-pengadaan/11")."',"
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
            
            . "var aData2 = [];"
            . "var rowcollection2 =  oTable.$('.jumlah_rg', {'page': 'all'});"
            . "rowcollection2.each(function(index,elem){"
            . "var value_jumlah_rg = parseInt($(elem).val());"
            . " var hasil3 = (isNaN(value_jumlah_rg)) ? 0 : value_jumlah_rg;"
            . "aData2.push( hasil3 );"
            . "});"
                        ."var dataString2 = 'id_spesifik='+ aData +'&jumlah='+ aData1 +'&jumlah_rg=' + aData2 +'&note=' + note2+'&id_hr_pegawai='+ id_hr_pegawai +'&id_receiver='+ id_receiver2 +'&id_mrp_request='+ {$id_mrp_request};"
                        ."$.ajax({"
                        ."type : 'POST',"
                        ."url : '".site_url("mrp/mrp-ajax-request/update-form-mrp-request-pengadaan/11")."',"
                        ."data: dataString2,"
                        ."dataType : 'html',"
                        ."success: function(data) {"
                            ."window.location ='{$url2}'"
                        ."},"
                     ."});"
            . "});"
                              
  ."$('#btn-proses').click(function(){"
    
    ."$('#btn-save').hide();"
    ."$('#loader-data').show();"
    ."$('#loader-page').show();"  
    . "var oTable = $('#tableboxy4').dataTable();"
    . "var aData = [];"
    . "var rowcollection =  oTable.$('.jumlah_mutasi', {'page': 'all'});"
    . "rowcollection.each(function(index,elem){"
    . "var value_jumlah = parseInt($(elem).val());"
    . " var hasil = (isNaN(value_jumlah)) ? 0 : value_jumlah;"
    . "aData.push( hasil );"
//            . "alert(aData);"
    . "});"

    . "var oTable = $('#tableboxy').dataTable();"
    . "var aData2 = [];"
    . "var rowcollection2 =  oTable.$('.id_spesifik', {'page': 'all'});"
    . "rowcollection2.each(function(index,elem){"
    . "var value_id_spesifik = parseInt($(elem).val());"
    . " var hasil2 = (isNaN(value_id_spesifik)) ? 0 : value_id_spesifik;"
    . "aData2.push( hasil2 );"
//            . "alert(aData2);"
    . "});"
    
    . "var tgl_diserahkan   = $('#tgl_diserahkan').val();"
    . "var id_users         = $('#id_users').val();"
    . "var note_mutasi      = $('#note_mutasi').val();"
        . "if(tgl_diserahkan ==''){"
            . "alert('Tanggal diserahkan tidak boleh kosong');"
//            ."window.location ='{$url2}'"
        . "}else{"
        ."var dataString2 = 'id_mrp_inventory_spesifik='+ aData2 +'&jumlah=' + aData +'&tgl_diserahkan='+tgl_diserahkan+'&note='+note_mutasi+'&id_users='+id_users;"           
        ."$.ajax({"
        ."type : 'POST',"
        ."url : '".site_url("mrp/mrp-ajax/proses-mutasi-stock-request/{$id_mrp_request}")."',"
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
//           . "alert(aData);"              
    
//        . "alert(dataString2);"
        
                        
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
                                    
            . "var aData2 = [];"
            . "var rowcollection2 =  oTable.$('.jumlah_rg', {'page': 'all'});"
            . "rowcollection2.each(function(index,elem){"
            . "var value_jumlah_rg = parseInt($(elem).val());"
            . " var hasil3 = (isNaN(value_jumlah_rg)) ? 0 : value_jumlah_rg;"
            . "aData2.push( hasil3 );"
            . "});"                        
                ."var dataString2 = 'id_spesifik='+ aData +'&jumlah='+ aData1+ '&jumlah_rg='+ aData2 +'&note=' + note2+'&id_hr_pegawai='+ id_hr_pegawai +'&id_receiver='+ id_receiver2 +'&id_mrp_request='+ {$id_mrp_request};"
                ."$.ajax({"
                ."type : 'POST',"
                ."url : '".site_url("mrp/mrp-ajax-request/update-form-mrp-request-pengadaan/11")."',"
                ."data: dataString2,"
                ."dataType : 'html',"
                ."success: function(data) {"
                    ."window.location ='{$url2}'"
                ."},"
             ."});"
        . "});"
   . "});"
                                     
      . '$(function() {'
    . "$( '.date' ).datepicker({"
    . "showOtherMonths: true,"
    . "dateFormat: 'yy-mm-dd',"  
    . "selectOtherMonths: true,"  
    . "selectOtherYears: true,"
    . "});"
            . "$('#req-mutasi-stock2').click(function(){"
            . 'var table = '
            . '$("#tableboxy4").dataTable({'
            . '"order": [[ 0, "asc" ]],'
            . '"bDestroy": true'                         
            . '});'
            . "table.fnClearTable();"                        
            . 'ambil_data4(table, 0);'
            . '});'
                            
            . "$('#history-req-mutasi-stock2').click(function(){"
            . 'var table = '
            . '$("#history-mutasi").dataTable({'
                . '"order": [[ 1, "desc" ]],'
                . '"bDestroy": true'                    
            . '});'
                . "table.fnClearTable();"                   
                . 'history_mutasi(table, 0);'            
       . "});"      
//                                    
//            . "});"
                                    
            . "$('#dt-cetakan-rutin').click(function(){"
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
        . '$.post("'.site_url("mrp/mrp-ajax-request/get-form-mrp-request-pengadaan-cetakan-rutin/{$id_mrp_request}/{$this->session->userdata("id")}/").'/"+mulai, function(data){'
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
        . '$.post("'.site_url("mrp/mrp-ajax-request/get-list-form-mrp-request-pengadaan-cetakan-rutin/{$id_mrp_request}/{$this->session->userdata("id")}/").'/"+mulai, function(data){'
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
                
    . 'function ambil_data4(table, mulai){'
    . '$.post("'.site_url("mrp/mrp-ajax-stock/ajax-form-mrp-request-pengadaan-2/{$id_mrp_request}").'/"+mulai, function(data){'
      . '$("#loader-page4").show();'
      . 'var hasil = $.parseJSON(data);'
      . 'if(hasil.status == 2){'
            . 'if(hasil.flag == 2){'
            . '$("#loader-page4").hide();'
            . '}'
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
            
    . 'function history_mutasi(table, mulai){'
    . '$.post("'.site_url("mrp/mrp-ajax/get-history-mutasi/{$id_mrp_request}").'/"+mulai, function(data){'
      . '$("#loader-history-mutasi").show();'
      . 'var hasil = $.parseJSON(data);'
      . 'if(hasil.status == 2){'
        . 'table.fnAddData(hasil.hasil);'
        . 'history_mutasi(table, hasil.start);'
      . '}'
      . 'else{'
        . '$("#loader-history-mutasi").hide();'
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
      . "$(document).on('click', '#id-reject', function(evt){"
        . "var id = $(this).attr('isi');"
        . "$('#id_request').val(id);"
      . "});"
	  . "</script>";   

    $link = site_url('mrp/reject-request/11');
     $before_table = "<div class='modal fade' id='edit-keterangan-reject' tabindex='-1' role='dialog' aria-hidden='true'>
    <div class='modal-dialog'>
        <div class='modal-content'>
            <div class='modal-header'>
                <button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
                <h4 class='modal-title'>Note Reject</h4>
            </div>
            <form action='{$link}' method='post'>
                <div class='modal-body'>
                    <div class='form-group'>
                        <div class='input-group'>
<!--                            <span class='input-group-addon'>Note Reject:</span>-->
                            <input name='id_request' class='form-control' id='id_request' style='display: none'>
                            <textarea name='note_reject' placeholder='Note Reject' style='margin: 0px; width: 553px; height: 227px;'></textarea>
                        </div>
                    </div>
                </div>
                <div class='modal-footer clearfix'>

                    <button type='button' class='btn btn-danger' data-dismiss='modal'><i class='fa fa-times'></i> Cancel</button>

                    <button type='submit' class='btn btn-primary pull-left'> Submit</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
    </div>";
//       $list = $this->global_models->get("mrp_request", array("id_mrp_request" => $id_mrp_request)); 
         
       $list = $this->global_models->get_query("SELECT A.id_mrp_request,A.status,A.note,A.create_by_users,A.id_hr_pegawai,A.user_pegawai_receiver,A.tanggal_diserahkan,A.note_mutasi,"
        . "B.id_users,C.name,C.email,D.title AS name_organisasi,E.id_hr_pegawai AS pegawai_receiver,F.name AS name_receiver,F.email AS email_receiver"
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
        
        $totl =$this->global_models->get_query("SELECT COUNT(status_blast) AS total FROM mrp_request AS A"
            . " WHERE A.create_by_users='{$this->session->userdata("id")}' AND A.status_blast = 1 AND A.type_inventory = 11 ");
       
      $this->template->build("mrp-request/pengadaan-cetakan-rutin/request-cetakan-rutin/view-request-cetakan-rutin", 
        array(
              'url'         => base_url()."themes/".DEFAULTTHEMES."/",
              'menu'        => 'mrp/mrp-request/request-pengadaan-cetakan-rutin',
              'title'       => lang("mrp_add_request_pengadaan_cetakan_rutin"),
              'list'        => $list,
              'total'       => $totl,
              'before_table' => $before_table,
              'breadcrumb'  => array(
                    "pengadaan_cetakan_rutin"  => "mrp/mrp-request/request-pengadaan-cetakan-rutin"
                ),
              'css'         => $css,
              'foot'        => $foot,
              'img'       => base_url()."themes/".DEFAULTTHEMES."/img/ajax-loader.gif"
//              'items'       => $items
            ));
      $this->template
        ->set_layout('form')
        ->build("mrp-request/pengadaan-cetakan-rutin/request-cetakan-rutin/view-request-cetakan-rutin");
    }
    else{
      $pst = $this->input->post(NULL);
     
//      if($pst['btn_cancel'] == "Cancel"){
//         
//          $kirim = array(
//            "status"                    => 10,  
//            "update_by_users"           => $this->session->userdata("id"),
//            "update_date"               => date("Y-m-d H:i:s")
//        );
//       $this->global_models->update("mrp_request", array("id_mrp_request" => $pst['id_detail_cancel']),$kirim);
//       $ro_kode = $this->global_models->get_field("mrp_request", "code", array("id_mrp_request" => "{$pst['id_detail_cancel']}"));
//       $this->session->set_flashdata('success', "Request Orders dengan kode {$ro_kode} Berhasil di Cancel");
//       redirect("mrp/request-pengadaan-atk");
//      }
      
      if($pst['btn_closed'] == "closed"){
           $kirim = array(
            "status"                    => 9,  
            "update_by_users"           => $this->session->userdata("id"),
            "update_date"               => date("Y-m-d H:i:s")
        );
       $this->global_models->update("mrp_request", array("id_mrp_request" => $pst['id_detail']),$kirim);
       redirect("mrp/mrp-request/request-pengadaan-cetakan-rutin");
      }
      
      if($pst["btn_approval"] == "approve"){
          $kirim = array(
            "status"                    => 3,
            "user_approval"             => $this->session->userdata("id"),  
            "update_by_users"           => $this->session->userdata("id"),
            "update_date"               => date("Y-m-d H:i:s")
        );
       $this->global_models->update("mrp_request", array("id_mrp_request" => $id_mrp_request),$kirim);
       
       $kirim1 = array(
            "id_mrp_request"                => $id_mrp_request,
            "type"                          => 2,
            "status"                        => 1,
            "create_by_users"               => $this->session->userdata("id"),
            "create_date"                   => date("Y-m-d H:i:s")
        );
       
        $this->global_models->insert("temp_alert_email", $kirim1);
        
       $kirim = array(
            "status"                    => 3,
            "update_by_users"           => $this->session->userdata("id"),
            "update_date"               => date("Y-m-d H:i:s")
        );
       $this->global_models->update("mrp_request_asset", array("id_mrp_request" => $id_mrp_request),$kirim);
     
      }
      
      if($pst['btn_reject'] == "reject"){
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
      
      redirect("mrp/mrp-request/request-pengadaan-cetakan-rutin");
    }
  }
  
     function request_pengadaan_promosi() {
      
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
         // . '"order": [[ 0, "desc" ]],'
		  . ' "aaSorting": []'
//          . "'iDisplayLength': 20"
        . '});'
      
        . "ambil_data(table, 0,{$this->session->userdata("id")});"
      
      . '});'
                
      . "$(document).on('click', '#id-customer-cancel', function(evt){"
        . "var id = $(this).attr('isi');"
        . "$('#id_request').val(id);"
      . "});"
                
      . 'function ambil_data(table, mulai,id_users){'
        . '$.post("'.site_url("mrp/mrp-ajax-request/get-mrp-request-pengadaan-promosi").'/"+mulai+"/"+id_users, function(data){'
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
    
     $link = site_url('mrp/cancel-request/8');
     $before_table = "<div class='modal fade' id='edit-keterangan-cancel' tabindex='-1' role='dialog' aria-hidden='true'>
    <div class='modal-dialog'>
        <div class='modal-content'>
            <div class='modal-header'>
                <button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
                <h4 class='modal-title'>Note Delete</h4>
            </div>
            <form action='{$link}' method='post'>
                <div class='modal-body'>
                    <div class='form-group'>
                        <div class='input-group'>
<!--                            <span class='input-group-addon'>Note Cancel:</span>-->
                            <input name='id_request' class='form-control' id='id_request' style='display: none'>
                            <textarea name='note_delete' placeholder='Note Delete' style='margin: 0px; width: 553px; height: 227px;'></textarea>
                        </div>
                    </div>
                </div>
                <div class='modal-footer clearfix'>

                    <button type='button' class='btn btn-danger' data-dismiss='modal'><i class='fa fa-times'></i> Cancel</button>

                    <button type='submit' class='btn btn-primary pull-left'> Submit</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>";
            
    
    $this->template->build('mrp-request/pengadaan-promosi/request-pengadaan-promosi', 
      array(
            'url'           => base_url()."themes/".DEFAULTTHEMES."/",
            'menu'          => "mrp/mrp-request/request-pengadaan-promosi",
            'title'         => lang("Form Request Pengadaan Promosi"),
            'foot'          => $foot,
            'css'           => $css,
            'before_table'  => $before_table,
//            'type'          => $type,
//            'jenis'         => $jenis,
//            'brand'         => $brand,
          ));
    $this->template
      ->set_layout('default')
      ->build('mrp-request/pengadaan-promosi/request-pengadaan-promosi');
  }
  
    function request_pengadaan_office() {
      
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
         // . '"order": [[ 0, "desc" ]],'
		  . ' "aaSorting": []'
//          . "'iDisplayLength': 20"
        . '});'
      
        . "ambil_data(table, 0,{$this->session->userdata("id")});"
      
      . '});'
                
      . "$(document).on('click', '#id-customer-cancel', function(evt){"
        . "var id = $(this).attr('isi');"
        . "$('#id_request').val(id);"
      . "});"
                
      . 'function ambil_data(table, mulai,id_users){'
        . '$.post("'.site_url("mrp/mrp-ajax-request/get-mrp-request-pengadaan-office").'/"+mulai+"/"+id_users, function(data){'
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
    
     $link = site_url('mrp/cancel-request/7');
     $before_table = "<div class='modal fade' id='edit-keterangan-cancel' tabindex='-1' role='dialog' aria-hidden='true'>
    <div class='modal-dialog'>
        <div class='modal-content'>
            <div class='modal-header'>
                <button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
                <h4 class='modal-title'>Note Delete</h4>
            </div>
            <form action='{$link}' method='post'>
                <div class='modal-body'>
                    <div class='form-group'>
                        <div class='input-group'>
<!--                            <span class='input-group-addon'>Note Cancel:</span>-->
                            <input name='id_request' class='form-control' id='id_request' style='display: none'>
                            <textarea name='note_delete' placeholder='Note Delete' style='margin: 0px; width: 553px; height: 227px;'></textarea>
                        </div>
                    </div>
                </div>
                <div class='modal-footer clearfix'>

                    <button type='button' class='btn btn-danger' data-dismiss='modal'><i class='fa fa-times'></i> Cancel</button>

                    <button type='submit' class='btn btn-primary pull-left'> Submit</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>";
            
    
    $this->template->build('mrp-request/pengadaan-office/request-pengadaan-office', 
      array(
            'url'           => base_url()."themes/".DEFAULTTHEMES."/",
            'menu'          => "mrp/mrp-request/request-pengadaan-office",
            'title'         => lang("Form Request Pengadaan Office"),
            'foot'          => $foot,
            'css'           => $css,
            'before_table'  => $before_table,
//            'type'          => $type,
//            'jenis'         => $jenis,
//            'brand'         => $brand,
          ));
    $this->template
      ->set_layout('default')
      ->build('mrp-request/pengadaan-office/request-pengadaan-office');
  }
  
    function request_pengadaan_service() {
      
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
         // . '"order": [[ 0, "desc" ]],'
		  . ' "aaSorting": []'
//          . "'iDisplayLength': 20"
        . '});'
      
        . "ambil_data(table, 0,{$this->session->userdata("id")});"
      
      . '});'
                
      . "$(document).on('click', '#id-customer-cancel', function(evt){"
        . "var id = $(this).attr('isi');"
        . "$('#id_request').val(id);"
      . "});"
                
      . 'function ambil_data(table, mulai,id_users){'
        . '$.post("'.site_url("mrp/mrp-ajax-request/get-mrp-request-pengadaan-service").'/"+mulai+"/"+id_users, function(data){'
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

     $link = site_url('mrp/cancel-request/5');
     $before_table = "<div class='modal fade' id='edit-keterangan-cancel' tabindex='-1' role='dialog' aria-hidden='true'>
    <div class='modal-dialog'>
        <div class='modal-content'>
            <div class='modal-header'>
                <button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
                <h4 class='modal-title'>Note Delete</h4>
            </div>
            <form action='{$link}' method='post'>
                <div class='modal-body'>
                    <div class='form-group'>
                        <div class='input-group'>
<!--                            <span class='input-group-addon'>Note Cancel:</span>-->
                            <input name='id_request' class='form-control' id='id_request' style='display: none'>
                            <textarea name='note_delete' placeholder='Note Delete' style='margin: 0px; width: 553px; height: 227px;'></textarea>
                        </div>
                    </div>
                </div>
                <div class='modal-footer clearfix'>

                    <button type='button' class='btn btn-danger' data-dismiss='modal'><i class='fa fa-times'></i> Cancel</button>

                    <button type='submit' class='btn btn-primary pull-left'> Submit</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>";
            
    
    $this->template->build('mrp-request/pengadaan-service/request-pengadaan-service', 
      array(
            'url'           => base_url()."themes/".DEFAULTTHEMES."/",
            'menu'          => "mrp/mrp-request/request-pengadaan-service",
            'title'         => lang("Form Request Pengadaan Service"),
            'foot'          => $foot,
            'css'           => $css,
            'before_table'  => $before_table,
//            'type'          => $type,
//            'jenis'         => $jenis,
//            'brand'         => $brand,
          ));
    $this->template
      ->set_layout('default')
      ->build('mrp-request/pengadaan-service/request-pengadaan-service');
  }
  
     function request_pengadaan_technical() {
      
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
         // . '"order": [[ 0, "desc" ]],'
		  . ' "aaSorting": []'
//          . "'iDisplayLength': 20"
        . '});'
      
        . "ambil_data(table, 0,{$this->session->userdata("id")});"
      
      . '});'
                
      . "$(document).on('click', '#id-customer-cancel', function(evt){"
        . "var id = $(this).attr('isi');"
        . "$('#id_request').val(id);"
      . "});"
                
      . 'function ambil_data(table, mulai,id_users){'
        . '$.post("'.site_url("mrp/mrp-ajax-request/get-mrp-request-pengadaan-technical").'/"+mulai+"/"+id_users, function(data){'
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
    
     $link = site_url('mrp/cancel-request/4');
     $before_table = "<div class='modal fade' id='edit-keterangan-cancel' tabindex='-1' role='dialog' aria-hidden='true'>
    <div class='modal-dialog'>
        <div class='modal-content'>
            <div class='modal-header'>
                <button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
                <h4 class='modal-title'>Note Delete</h4>
            </div>
            <form action='{$link}' method='post'>
                <div class='modal-body'>
                    <div class='form-group'>
                        <div class='input-group'>
<!--                            <span class='input-group-addon'>Note Cancel:</span>-->
                            <input name='id_request' class='form-control' id='id_request' style='display: none'>
                            <textarea name='note_delete' placeholder='Note Delete' style='margin: 0px; width: 553px; height: 227px;'></textarea>
                        </div>
                    </div>
                </div>
                <div class='modal-footer clearfix'>

                    <button type='button' class='btn btn-danger' data-dismiss='modal'><i class='fa fa-times'></i> Cancel</button>

                    <button type='submit' class='btn btn-primary pull-left'> Submit</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>";
            
    
    $this->template->build('mrp-request/pengadaan-technical/request-pengadaan-technical', 
      array(
            'url'           => base_url()."themes/".DEFAULTTHEMES."/",
            'menu'          => "mrp/mrp-request/request-pengadaan-technical",
            'title'         => lang("Form Request Pengadaan Technical Supply"),
            'foot'          => $foot,
            'css'           => $css,
            'before_table'  => $before_table,
//            'type'          => $type,
//            'jenis'         => $jenis,
//            'brand'         => $brand,
          ));
    $this->template
      ->set_layout('default')
      ->build('mrp-request/pengadaan-technical/request-pengadaan-technical');
  }
  
    function request_pengadaan_komputer() {
      
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
         // . '"order": [[ 0, "desc" ]],'
		  . ' "aaSorting": []'
//          . "'iDisplayLength': 20"
        . '});'
      
        . "ambil_data(table, 0,{$this->session->userdata("id")});"
      
      . '});'
                
      . "$(document).on('click', '#id-customer-cancel', function(evt){"
        . "var id = $(this).attr('isi');"
        . "$('#id_request').val(id);"
      . "});"
                
      . 'function ambil_data(table, mulai,id_users){'
        . '$.post("'.site_url("mrp/mrp-ajax-request/get-mrp-request-pengadaan-komputer").'/"+mulai+"/"+id_users, function(data){'
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
    
     $link = site_url('mrp/cancel-request/3');
     $before_table = "<div class='modal fade' id='edit-keterangan-cancel' tabindex='-1' role='dialog' aria-hidden='true'>
    <div class='modal-dialog'>
        <div class='modal-content'>
            <div class='modal-header'>
                <button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
                <h4 class='modal-title'>Note Delete</h4>
            </div>
            <form action='{$link}' method='post'>
                <div class='modal-body'>
                    <div class='form-group'>
                        <div class='input-group'>
<!--                            <span class='input-group-addon'>Note Cancel:</span>-->
                            <input name='id_request' class='form-control' id='id_request' style='display: none'>
                            <textarea name='note_delete' placeholder='Note Delete' style='margin: 0px; width: 553px; height: 227px;'></textarea>
                        </div>
                    </div>
                </div>
                <div class='modal-footer clearfix'>

                    <button type='button' class='btn btn-danger' data-dismiss='modal'><i class='fa fa-times'></i> Cancel</button>

                    <button type='submit' class='btn btn-primary pull-left'> Submit</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>";
            
    
    $this->template->build('mrp-request/pengadaan-komputer/request-pengadaan-komputer', 
      array(
            'url'           => base_url()."themes/".DEFAULTTHEMES."/",
            'menu'          => "mrp/mrp-request/request-pengadaan-komputer",
            'title'         => lang("Form Request Pengadaan Komputer Supply"),
            'foot'          => $foot,
            'css'           => $css,
            'before_table'  => $before_table,
//            'type'          => $type,
//            'jenis'         => $jenis,
//            'brand'         => $brand,
          ));
    $this->template
      ->set_layout('default')
      ->build('mrp-request/pengadaan-komputer/request-pengadaan-komputer');
  }
  
      public function add_request_pengadaan_komputer($id_mrp_request = 0){
      
    if(!$this->input->post(NULL)){
        
    $css = "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/datatables/dataTables.bootstrap.css' rel='stylesheet' type='text/css' />"
      . "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/jQueryUI/jquery-ui-1.10.3.custom.min.css' rel='stylesheet' type='text/css' />"
      . "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/select2.css' type='text/css' rel='stylesheet'>";
      $url = base_url('mrp/mrp-request/request-pengadaan-komputer');
      $url2 = base_url("mrp/mrp-request/add-request-pengadaan-komputer/{$id_mrp_request}");
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
//             . "var value_jumlah = parseInt($(elem).val());"
			. " var value_jumlah = parseFloat($(elem).val()).toFixed(2);" 
            . " var hasil2 = (isNaN(value_jumlah)) ? 0 : value_jumlah;"
            . "aData1.push( hasil2 );"
            . "});"
                        ."var dataString2 = 'id_spesifik='+ aData +'&jumlah='+ aData1+'&note=' + note2 +'&id_hr_pegawai='+id_hr_pegawai +'&id_receiver='+ id_receiver2 +'&id_mrp_request='+ {$id_mrp_request};"
                        ."$.ajax({"
                        ."type : 'POST',"
                        ."url : '".site_url("mrp/mrp-ajax-request/insert-form-mrp-request-pengadaan/3")."',"
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
//             . "var value_jumlah = parseInt($(elem).val());"
			. " var value_jumlah = parseFloat($(elem).val()).toFixed(2);" 
            . " var hasil2 = (isNaN(value_jumlah)) ? 0 : value_jumlah;"
            . "aData1.push( hasil2 );"
            . "});"
                        ."var dataString2 = 'id_spesifik='+ aData +'&jumlah='+ aData1+'&note=' + note2+'&id_hr_pegawai='+ id_hr_pegawai +'&id_receiver='+ id_receiver2 +'&id_mrp_request='+ {$id_mrp_request};"
                        ."$.ajax({"
                        ."type : 'POST',"
                        ."url : '".site_url("mrp/mrp-ajax-request/draft-form-mrp-request-pengadaan/3")."',"
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
//             . "var value_jumlah = parseInt($(elem).val());"
			. " var value_jumlah = parseFloat($(elem).val()).toFixed(2);" 
            . " var hasil2 = (isNaN(value_jumlah)) ? 0 : value_jumlah;"
            . "aData1.push( hasil2 );"
            . "});"
            
            . "var aData2 = [];"
            . "var rowcollection2 =  oTable.$('.jumlah_rg', {'page': 'all'});"
            . "rowcollection2.each(function(index,elem){"
            . "var value_jumlah_rg = parseInt($(elem).val());"
            . " var hasil3 = (isNaN(value_jumlah_rg)) ? 0 : value_jumlah_rg;"
            . "aData2.push( hasil3 );"
            . "});"
                        ."var dataString2 = 'id_spesifik='+ aData +'&jumlah='+ aData1 +'&jumlah_rg=' + aData2 +'&note=' + note2+'&id_hr_pegawai='+ id_hr_pegawai +'&id_receiver='+ id_receiver2 +'&id_mrp_request='+ {$id_mrp_request};"
                        ."$.ajax({"
                        ."type : 'POST',"
                        ."url : '".site_url("mrp/mrp-ajax-request/update-form-mrp-request-pengadaan/3")."',"
                        ."data: dataString2,"
                        ."dataType : 'html',"
                        ."success: function(data) {"
                            ."window.location ='{$url2}'"
                        ."},"
                     ."});"
            . "});"
                              
  ."$('#btn-proses').click(function(){"
    
    ."$('#btn-save').hide();"
    ."$('#loader-data').show();"
    ."$('#loader-page').show();"  
    . "var oTable = $('#tableboxy4').dataTable();"
    . "var aData = [];"
    . "var rowcollection =  oTable.$('.jumlah_mutasi', {'page': 'all'});"
    . "rowcollection.each(function(index,elem){"
//     . "var value_jumlah = parseInt($(elem).val());"
	. " var value_jumlah = parseFloat($(elem).val()).toFixed(2);" 
    . " var hasil = (isNaN(value_jumlah)) ? 0 : value_jumlah;"
    . "aData.push( hasil );"
//            . "alert(aData);"
    . "});"

    . "var oTable = $('#tableboxy').dataTable();"
    . "var aData2 = [];"
    . "var rowcollection2 =  oTable.$('.id_spesifik', {'page': 'all'});"
    . "rowcollection2.each(function(index,elem){"
    . "var value_id_spesifik = parseInt($(elem).val());"
    . " var hasil2 = (isNaN(value_id_spesifik)) ? 0 : value_id_spesifik;"
    . "aData2.push( hasil2 );"
//            . "alert(aData2);"
    . "});"
    
    . "var tgl_diserahkan   = $('#tgl_diserahkan').val();"
    . "var id_users         = $('#id_users').val();"
    . "var note_mutasi      = $('#note_mutasi').val();"
        . "if(tgl_diserahkan ==''){"
            . "alert('Tanggal diserahkan tidak boleh kosong');"
//            ."window.location ='{$url2}'"
        . "}else{"
        ."var dataString2 = 'id_mrp_inventory_spesifik='+ aData2 +'&jumlah=' + aData +'&tgl_diserahkan='+tgl_diserahkan+'&note='+note_mutasi+'&id_users='+id_users;"           
        ."$.ajax({"
        ."type : 'POST',"
        ."url : '".site_url("mrp/mrp-ajax/proses-mutasi-stock-request/{$id_mrp_request}")."',"
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
//           . "alert(aData);"              
    
//        . "alert(dataString2);"
        
                        
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
//             . "var value_jumlah = parseInt($(elem).val());"
			. " var value_jumlah = parseFloat($(elem).val()).toFixed(2);" 
            . " var hasil2 = (isNaN(value_jumlah)) ? 0 : value_jumlah;"
            . "aData1.push( hasil2 );"
            . "});"
                                    
            . "var aData2 = [];"
            . "var rowcollection2 =  oTable.$('.jumlah_rg', {'page': 'all'});"
            . "rowcollection2.each(function(index,elem){"
            . "var value_jumlah_rg = parseInt($(elem).val());"
            . " var hasil3 = (isNaN(value_jumlah_rg)) ? 0 : value_jumlah_rg;"
            . "aData2.push( hasil3 );"
            . "});"                        
                ."var dataString2 = 'id_spesifik='+ aData +'&jumlah='+ aData1+ '&jumlah_rg='+ aData2 +'&note=' + note2+'&id_hr_pegawai='+ id_hr_pegawai +'&id_receiver='+ id_receiver2 +'&id_mrp_request='+ {$id_mrp_request};"
                ."$.ajax({"
                ."type : 'POST',"
                ."url : '".site_url("mrp/mrp-ajax-request/update-form-mrp-request-pengadaan/3")."',"
                ."data: dataString2,"
                ."dataType : 'html',"
                ."success: function(data) {"
                    ."window.location ='{$url2}'"
                ."},"
             ."});"
        . "});"
   . "});"
                                     
      . '$(function() {'
    . "$( '.date' ).datepicker({"
    . "showOtherMonths: true,"
    . "dateFormat: 'yy-mm-dd',"  
    . "selectOtherMonths: true,"  
    . "selectOtherYears: true,"
    . "});"
            . "$('#req-mutasi-stock2').click(function(){"
            . 'var table = '
            . '$("#tableboxy4").dataTable({'
            . '"order": [[ 0, "asc" ]],'
            . '"bDestroy": true'                         
            . '});'
            . "table.fnClearTable();"                        
            . 'ambil_data4(table, 0);'
            . '});'
                            
            . "$('#history-req-mutasi-stock2').click(function(){"
            . 'var table = '
            . '$("#history-mutasi").dataTable({'
                . '"order": [[ 1, "desc" ]],'
                . '"bDestroy": true'                    
            . '});'
                . "table.fnClearTable();"                   
                . 'history_mutasi(table, 0);'            
       . "});"      
//                                    
//            . "});"
                                    
            . "$('#dt-komputer').click(function(){"
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
        . '$.post("'.site_url("mrp/mrp-ajax-request/get-form-mrp-request-pengadaan-komputer/{$id_mrp_request}/{$this->session->userdata("id")}/").'/"+mulai, function(data){'
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
        . '$.post("'.site_url("mrp/mrp-ajax-request/get-list-form-mrp-request-pengadaan-komputer/{$id_mrp_request}/{$this->session->userdata("id")}/").'/"+mulai, function(data){'
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
                
    . 'function ambil_data4(table, mulai){'
    . '$.post("'.site_url("mrp/mrp-ajax-stock/ajax-form-mrp-request-pengadaan-2/{$id_mrp_request}").'/"+mulai, function(data){'
      . '$("#loader-page4").show();'
      . 'var hasil = $.parseJSON(data);'
      . 'if(hasil.status == 2){'
            . 'if(hasil.flag == 2){'
            . '$("#loader-page4").hide();'
            . '}'
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
            
    . 'function history_mutasi(table, mulai){'
    . '$.post("'.site_url("mrp/mrp-ajax/get-history-mutasi/{$id_mrp_request}").'/"+mulai, function(data){'
      . '$("#loader-history-mutasi").show();'
      . 'var hasil = $.parseJSON(data);'
      . 'if(hasil.status == 2){'
        . 'table.fnAddData(hasil.hasil);'
        . 'history_mutasi(table, hasil.start);'
      . '}'
      . 'else{'
        . '$("#loader-history-mutasi").hide();'
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
      . "$(document).on('click', '#id-reject', function(evt){"
        . "var id = $(this).attr('isi');"
        . "$('#id_request').val(id);"
      . "});"
	  . "</script>";   

    $link = site_url('mrp/reject-request/3');
     $before_table = "<div class='modal fade' id='edit-keterangan-reject' tabindex='-1' role='dialog' aria-hidden='true'>
    <div class='modal-dialog'>
        <div class='modal-content'>
            <div class='modal-header'>
                <button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
                <h4 class='modal-title'>Note Reject</h4>
            </div>
            <form action='{$link}' method='post'>
                <div class='modal-body'>
                    <div class='form-group'>
                        <div class='input-group'>
<!--                            <span class='input-group-addon'>Note Reject:</span>-->
                            <input name='id_request' class='form-control' id='id_request' style='display: none'>
                            <textarea name='note_reject' placeholder='Note Reject' style='margin: 0px; width: 553px; height: 227px;'></textarea>
                        </div>
                    </div>
                </div>
                <div class='modal-footer clearfix'>

                    <button type='button' class='btn btn-danger' data-dismiss='modal'><i class='fa fa-times'></i> Cancel</button>

                    <button type='submit' class='btn btn-primary pull-left'> Submit</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
    </div>";
//       $list = $this->global_models->get("mrp_request", array("id_mrp_request" => $id_mrp_request)); 
         
       $list = $this->global_models->get_query("SELECT A.id_mrp_request,A.status,A.note,A.create_by_users,A.id_hr_pegawai,A.user_pegawai_receiver,A.tanggal_diserahkan,A.note_mutasi,"
        . "B.id_users,C.name,C.email,D.title AS name_organisasi,E.id_hr_pegawai AS pegawai_receiver,F.name AS name_receiver,F.email AS email_receiver"
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
      $this->template->build("mrp-request/pengadaan-komputer/request-komputer/view-request-komputer", 
        array(
              'url'         => base_url()."themes/".DEFAULTTHEMES."/",
              'menu'        => 'mrp/mrp-request/request-pengadaan-komputer',
              'title'       => lang("mrp_add_request_pengadaan_komputer"),
              'list'        => $list,
              'before_table' => $before_table,
              'breadcrumb'  => array(
                    "mrp_request_pengadaan_komputer"  => "mrp/mrp-request/request-pengadaan-komputer"
                ),
              'css'         => $css,
              'foot'        => $foot,
              'img'       => base_url()."themes/".DEFAULTTHEMES."/img/ajax-loader.gif"
//              'items'       => $items
            ));
      $this->template
        ->set_layout('form')
        ->build("mrp-request/pengadaan-komputer/request-komputer/view-request-komputer");
    }
    else{
      $pst = $this->input->post(NULL);
     
//      if($pst['btn_cancel'] == "Cancel"){
//         
//          $kirim = array(
//            "status"                    => 10,  
//            "update_by_users"           => $this->session->userdata("id"),
//            "update_date"               => date("Y-m-d H:i:s")
//        );
//       $this->global_models->update("mrp_request", array("id_mrp_request" => $pst['id_detail_cancel']),$kirim);
//       $ro_kode = $this->global_models->get_field("mrp_request", "code", array("id_mrp_request" => "{$pst['id_detail_cancel']}"));
//       $this->session->set_flashdata('success', "Request Orders dengan kode {$ro_kode} Berhasil di Cancel");
//       redirect("mrp/request-pengadaan-atk");
//      }
      
      if($pst['btn_closed'] == "closed"){
           $kirim = array(
            "status"                    => 9,  
            "update_by_users"           => $this->session->userdata("id"),
            "update_date"               => date("Y-m-d H:i:s")
        );
       $this->global_models->update("mrp_request", array("id_mrp_request" => $pst['id_detail']),$kirim);
       redirect("mrp/mrp-request/request-pengadaan-komputer");
      }
      
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
      
      if($pst['btn_reject'] == "reject"){
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
      
      redirect("mrp/mrp-request/request-pengadaan-komputer");
    }
  }
  
     public function add_request_pengadaan_promosi($id_mrp_request = 0){
      
    if(!$this->input->post(NULL)){
        
    $css = "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/datatables/dataTables.bootstrap.css' rel='stylesheet' type='text/css' />"
      . "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/jQueryUI/jquery-ui-1.10.3.custom.min.css' rel='stylesheet' type='text/css' />"
      . "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/select2.css' type='text/css' rel='stylesheet'>";
      $url = base_url('mrp/mrp-request/request-pengadaan-promosi');
      $url2 = base_url("mrp/mrp-request/add-request-pengadaan-promosi/{$id_mrp_request}");
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
//             . "var value_jumlah = parseInt($(elem).val());"
				. " var value_jumlah = parseFloat($(elem).val()).toFixed(2);" 
            . " var hasil2 = (isNaN(value_jumlah)) ? 0 : value_jumlah;"
            . "aData1.push( hasil2 );"
            . "});"
                        ."var dataString2 = 'id_spesifik='+ aData +'&jumlah='+ aData1+'&note=' + note2 +'&id_hr_pegawai='+id_hr_pegawai +'&id_receiver='+ id_receiver2 +'&id_mrp_request='+ {$id_mrp_request};"
                        ."$.ajax({"
                        ."type : 'POST',"
                        ."url : '".site_url("mrp/mrp-ajax-request/insert-form-mrp-request-pengadaan/8")."',"
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
//             . "var value_jumlah = parseInt($(elem).val());"
			. " var value_jumlah = parseFloat($(elem).val()).toFixed(2);" 
            . " var hasil2 = (isNaN(value_jumlah)) ? 0 : value_jumlah;"
            . "aData1.push( hasil2 );"
            . "});"
                        ."var dataString2 = 'id_spesifik='+ aData +'&jumlah='+ aData1+'&note=' + note2+'&id_hr_pegawai='+ id_hr_pegawai +'&id_receiver='+ id_receiver2 +'&id_mrp_request='+ {$id_mrp_request};"
                        ."$.ajax({"
                        ."type : 'POST',"
                        ."url : '".site_url("mrp/mrp-ajax-request/draft-form-mrp-request-pengadaan/8")."',"
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
//             . "var value_jumlah = parseInt($(elem).val());"
			. " var value_jumlah = parseFloat($(elem).val()).toFixed(2);" 
            . " var hasil2 = (isNaN(value_jumlah)) ? 0 : value_jumlah;"
            . "aData1.push( hasil2 );"
            . "});"
            
            . "var aData2 = [];"
            . "var rowcollection2 =  oTable.$('.jumlah_rg', {'page': 'all'});"
            . "rowcollection2.each(function(index,elem){"
            . "var value_jumlah_rg = parseInt($(elem).val());"
            . " var hasil3 = (isNaN(value_jumlah_rg)) ? 0 : value_jumlah_rg;"
            . "aData2.push( hasil3 );"
            . "});"
                        ."var dataString2 = 'id_spesifik='+ aData +'&jumlah='+ aData1 +'&jumlah_rg=' + aData2 +'&note=' + note2+'&id_hr_pegawai='+ id_hr_pegawai +'&id_receiver='+ id_receiver2 +'&id_mrp_request='+ {$id_mrp_request};"
                        ."$.ajax({"
                        ."type : 'POST',"
                        ."url : '".site_url("mrp/mrp-ajax-request/update-form-mrp-request-pengadaan/8")."',"
                        ."data: dataString2,"
                        ."dataType : 'html',"
                        ."success: function(data) {"
                            ."window.location ='{$url2}'"
                        ."},"
                     ."});"
            . "});"
                              
  ."$('#btn-proses').click(function(){"
    
    ."$('#btn-save').hide();"
    ."$('#loader-data').show();"
    ."$('#loader-page').show();"  
    . "var oTable = $('#tableboxy4').dataTable();"
    . "var aData = [];"
    . "var rowcollection =  oTable.$('.jumlah_mutasi', {'page': 'all'});"
    . "rowcollection.each(function(index,elem){"
//     . "var value_jumlah = parseInt($(elem).val());"
	. " var value_jumlah = parseFloat($(elem).val()).toFixed(2);" 
    . " var hasil = (isNaN(value_jumlah)) ? 0 : value_jumlah;"
    . "aData.push( hasil );"
//            . "alert(aData);"
    . "});"

    . "var oTable = $('#tableboxy').dataTable();"
    . "var aData2 = [];"
    . "var rowcollection2 =  oTable.$('.id_spesifik', {'page': 'all'});"
    . "rowcollection2.each(function(index,elem){"
    . "var value_id_spesifik = parseInt($(elem).val());"
    . " var hasil2 = (isNaN(value_id_spesifik)) ? 0 : value_id_spesifik;"
    . "aData2.push( hasil2 );"
//            . "alert(aData2);"
    . "});"
    
    . "var tgl_diserahkan   = $('#tgl_diserahkan').val();"
    . "var id_users         = $('#id_users').val();"
    . "var note_mutasi      = $('#note_mutasi').val();"
        . "if(tgl_diserahkan ==''){"
            . "alert('Tanggal diserahkan tidak boleh kosong');"
//            ."window.location ='{$url2}'"
        . "}else{"
        ."var dataString2 = 'id_mrp_inventory_spesifik='+ aData2 +'&jumlah=' + aData +'&tgl_diserahkan='+tgl_diserahkan+'&note='+note_mutasi+'&id_users='+id_users;"           
        ."$.ajax({"
        ."type : 'POST',"
        ."url : '".site_url("mrp/mrp-ajax/proses-mutasi-stock-request/{$id_mrp_request}")."',"
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
//           . "alert(aData);"              
    
//        . "alert(dataString2);"
        
                        
            . "});"                                             
            . "$('#btn-save2').click(function(){"
              . "$('#btn-save2').hide();"
                . "$('#img-5-office').show();"
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
//             . "var value_jumlah = parseInt($(elem).val());"
			. " var value_jumlah = parseFloat($(elem).val()).toFixed(2);" 
            . " var hasil2 = (isNaN(value_jumlah)) ? 0 : value_jumlah;"
            . "aData1.push( hasil2 );"
            . "});"
                                    
            . "var aData2 = [];"
            . "var rowcollection2 =  oTable.$('.jumlah_rg', {'page': 'all'});"
            . "rowcollection2.each(function(index,elem){"
            . "var value_jumlah_rg = parseInt($(elem).val());"
            . " var hasil3 = (isNaN(value_jumlah_rg)) ? 0 : value_jumlah_rg;"
            . "aData2.push( hasil3 );"
            . "});"                        
                ."var dataString2 = 'id_spesifik='+ aData +'&jumlah='+ aData1+ '&jumlah_rg='+ aData2 +'&note=' + note2+'&id_hr_pegawai='+ id_hr_pegawai +'&id_receiver='+ id_receiver2 +'&id_mrp_request='+ {$id_mrp_request};"
                ."$.ajax({"
                ."type : 'POST',"
                ."url : '".site_url("mrp/mrp-ajax-request/update-form-mrp-request-pengadaan/8")."',"
                ."data: dataString2,"
                ."dataType : 'html',"
                ."success: function(data) {"
                    ."window.location ='{$url2}'"
                ."},"
             ."});"
        . "});"
   . "});"
                                     
      . '$(function() {'
    . "$( '.date' ).datepicker({"
    . "showOtherMonths: true,"
    . "dateFormat: 'yy-mm-dd',"  
    . "selectOtherMonths: true,"  
    . "selectOtherYears: true,"
    . "});"
            . "$('#req-mutasi-stock2').click(function(){"
            . 'var table = '
            . '$("#tableboxy4").dataTable({'
            . '"order": [[ 0, "asc" ]],'
            . '"bDestroy": true'                         
            . '});'
            . "table.fnClearTable();"                        
            . 'ambil_data4(table, 0);'
            . '});'
                            
            . "$('#history-req-mutasi-stock2').click(function(){"
            . 'var table = '
            . '$("#history-mutasi").dataTable({'
                . '"order": [[ 1, "desc" ]],'
                . '"bDestroy": true'                    
            . '});'
                . "table.fnClearTable();"                   
                . 'history_mutasi(table, 0);'            
       . "});"      
//                                    
//            . "});"
                                    
            . "$('#dt-office').click(function(){"
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
        . '$.post("'.site_url("mrp/mrp-ajax-request/get-form-mrp-request-pengadaan-promosi/{$id_mrp_request}/{$this->session->userdata("id")}/").'/"+mulai, function(data){'
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
        . '$.post("'.site_url("mrp/mrp-ajax-request/get-list-form-mrp-request-pengadaan-promosi/{$id_mrp_request}/{$this->session->userdata("id")}/").'/"+mulai, function(data){'
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
                
    . 'function ambil_data4(table, mulai){'
    . '$.post("'.site_url("mrp/mrp-ajax-stock/ajax-form-mrp-request-pengadaan-2/{$id_mrp_request}").'/"+mulai, function(data){'
      . '$("#loader-page4").show();'
      . 'var hasil = $.parseJSON(data);'
      . 'if(hasil.status == 2){'
            . 'if(hasil.flag == 2){'
            . '$("#loader-page4").hide();'
            . '}'
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
            
    . 'function history_mutasi(table, mulai){'
    . '$.post("'.site_url("mrp/mrp-ajax/get-history-mutasi/{$id_mrp_request}").'/"+mulai, function(data){'
      . '$("#loader-history-mutasi").show();'
      . 'var hasil = $.parseJSON(data);'
      . 'if(hasil.status == 2){'
        . 'table.fnAddData(hasil.hasil);'
        . 'history_mutasi(table, hasil.start);'
      . '}'
      . 'else{'
        . '$("#loader-history-mutasi").hide();'
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
      . "$(document).on('click', '#id-reject', function(evt){"
        . "var id = $(this).attr('isi');"
        . "$('#id_request').val(id);"
      . "});"
	  . "</script>";   

    $link = site_url('mrp/reject-request/8');
     $before_table = "<div class='modal fade' id='edit-keterangan-reject' tabindex='-1' role='dialog' aria-hidden='true'>
    <div class='modal-dialog'>
        <div class='modal-content'>
            <div class='modal-header'>
                <button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
                <h4 class='modal-title'>Note Reject</h4>
            </div>
            <form action='{$link}' method='post'>
                <div class='modal-body'>
                    <div class='form-group'>
                        <div class='input-group'>
<!--                            <span class='input-group-addon'>Note Reject:</span>-->
                            <input name='id_request' class='form-control' id='id_request' style='display: none'>
                            <textarea name='note_reject' placeholder='Note Reject' style='margin: 0px; width: 553px; height: 227px;'></textarea>
                        </div>
                    </div>
                </div>
                <div class='modal-footer clearfix'>

                    <button type='button' class='btn btn-danger' data-dismiss='modal'><i class='fa fa-times'></i> Cancel</button>

                    <button type='submit' class='btn btn-primary pull-left'> Submit</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
    </div>";
//       $list = $this->global_models->get("mrp_request", array("id_mrp_request" => $id_mrp_request)); 
         
       $list = $this->global_models->get_query("SELECT A.id_mrp_request,A.status,A.note,A.create_by_users,A.id_hr_pegawai,A.user_pegawai_receiver,A.tanggal_diserahkan,A.note_mutasi,"
        . "B.id_users,C.name,C.email,D.title AS name_organisasi,E.id_hr_pegawai AS pegawai_receiver,F.name AS name_receiver,F.email AS email_receiver"
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
      $this->template->build("mrp-request/pengadaan-promosi/request-promosi/view-request-promosi", 
        array(
              'url'         => base_url()."themes/".DEFAULTTHEMES."/",
              'menu'        => 'mrp/mrp-request/request-pengadaan-promosi',
              'title'       => lang("mrp_add_request_pengadaan_promosi"),
              'list'        => $list,
              'before_table' => $before_table,
              'breadcrumb'  => array(
                    "mrp_request_pengadaan_promosi"  => "mrp/mrp-request/request-pengadaan-promosi"
                ),
              'css'         => $css,
              'foot'        => $foot,
              'img'       => base_url()."themes/".DEFAULTTHEMES."/img/ajax-loader.gif"
//              'items'       => $items
            ));
      $this->template
        ->set_layout('form')
        ->build("mrp-request/pengadaan-promosi/request-promosi/view-request-promosi");
    }
    else{
      $pst = $this->input->post(NULL);
     
//      if($pst['btn_cancel'] == "Cancel"){
//         
//          $kirim = array(
//            "status"                    => 10,  
//            "update_by_users"           => $this->session->userdata("id"),
//            "update_date"               => date("Y-m-d H:i:s")
//        );
//       $this->global_models->update("mrp_request", array("id_mrp_request" => $pst['id_detail_cancel']),$kirim);
//       $ro_kode = $this->global_models->get_field("mrp_request", "code", array("id_mrp_request" => "{$pst['id_detail_cancel']}"));
//       $this->session->set_flashdata('success', "Request Orders dengan kode {$ro_kode} Berhasil di Cancel");
//       redirect("mrp/request-pengadaan-atk");
//      }
      
      if($pst['btn_closed'] == "closed"){
           $kirim = array(
            "status"                    => 9,  
            "update_by_users"           => $this->session->userdata("id"),
            "update_date"               => date("Y-m-d H:i:s")
        );
       $this->global_models->update("mrp_request", array("id_mrp_request" => $pst['id_detail']),$kirim);
       redirect("mrp/mrp-request/request-pengadaan-office");
      }
      
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
      
      if($pst['btn_reject'] == "reject"){
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
      
      redirect("mrp/mrp-request/request-pengadaan-office");
    }
  }
  
  public function add_request_pengadaan_umum($id_mrp_request = 0){
      
    if(!$this->input->post(NULL)){
        
    $css = "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/datatables/dataTables.bootstrap.css' rel='stylesheet' type='text/css' />"
      . "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/jQueryUI/jquery-ui-1.10.3.custom.min.css' rel='stylesheet' type='text/css' />"
      . "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/select2.css' type='text/css' rel='stylesheet'>";
      $url = base_url('mrp/mrp-request/request-pengadaan-umum');
      $url2 = base_url("mrp/mrp-request/add-request-pengadaan-umum/{$id_mrp_request}");
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
//             . "var value_jumlah = parseInt($(elem).val());"
			. " var value_jumlah = parseFloat($(elem).val()).toFixed(2);" 
            . " var hasil2 = (isNaN(value_jumlah)) ? 0 : value_jumlah;"
            . "aData1.push( hasil2 );"
            . "});"
                        ."var dataString2 = 'id_spesifik='+ aData +'&jumlah='+ aData1+'&note=' + note2 +'&id_hr_pegawai='+id_hr_pegawai +'&id_receiver='+ id_receiver2 +'&id_mrp_request='+ {$id_mrp_request};"
                        ."$.ajax({"
                        ."type : 'POST',"
                        ."url : '".site_url("mrp/mrp-ajax-request/insert-form-mrp-request-pengadaan/9")."',"
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
//             . "var value_jumlah = parseInt($(elem).val());"
			. " var value_jumlah = parseFloat($(elem).val()).toFixed(2);" 
            . " var hasil2 = (isNaN(value_jumlah)) ? 0 : value_jumlah;"
            . "aData1.push( hasil2 );"
            . "});"
                        ."var dataString2 = 'id_spesifik='+ aData +'&jumlah='+ aData1+'&note=' + note2+'&id_hr_pegawai='+ id_hr_pegawai +'&id_receiver='+ id_receiver2 +'&id_mrp_request='+ {$id_mrp_request};"
                        ."$.ajax({"
                        ."type : 'POST',"
                        ."url : '".site_url("mrp/mrp-ajax-request/draft-form-mrp-request-pengadaan/9")."',"
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
//             . "var value_jumlah = parseInt($(elem).val());"
			. " var value_jumlah = parseFloat($(elem).val()).toFixed(2);" 
            . " var hasil2 = (isNaN(value_jumlah)) ? 0 : value_jumlah;"
            . "aData1.push( hasil2 );"
            . "});"
            
            . "var aData2 = [];"
            . "var rowcollection2 =  oTable.$('.jumlah_rg', {'page': 'all'});"
            . "rowcollection2.each(function(index,elem){"
            . "var value_jumlah_rg = parseInt($(elem).val());"
            . " var hasil3 = (isNaN(value_jumlah_rg)) ? 0 : value_jumlah_rg;"
            . "aData2.push( hasil3 );"
            . "});"
                        ."var dataString2 = 'id_spesifik='+ aData +'&jumlah='+ aData1 +'&jumlah_rg=' + aData2 +'&note=' + note2+'&id_hr_pegawai='+ id_hr_pegawai +'&id_receiver='+ id_receiver2 +'&id_mrp_request='+ {$id_mrp_request};"
                        ."$.ajax({"
                        ."type : 'POST',"
                        ."url : '".site_url("mrp/mrp-ajax-request/update-form-mrp-request-pengadaan/9")."',"
                        ."data: dataString2,"
                        ."dataType : 'html',"
                        ."success: function(data) {"
                            ."window.location ='{$url2}'"
                        ."},"
                     ."});"
            . "});"
                              
  ."$('#btn-proses').click(function(){"
    
    ."$('#btn-save').hide();"
    ."$('#loader-data').show();"
    ."$('#loader-page').show();"  
    . "var oTable = $('#tableboxy4').dataTable();"
    . "var aData = [];"
    . "var rowcollection =  oTable.$('.jumlah_mutasi', {'page': 'all'});"
    . "rowcollection.each(function(index,elem){"
//     . "var value_jumlah = parseInt($(elem).val());"
	. " var value_jumlah = parseFloat($(elem).val()).toFixed(2);" 
    . " var hasil = (isNaN(value_jumlah)) ? 0 : value_jumlah;"
    . "aData.push( hasil );"
//            . "alert(aData);"
    . "});"

    . "var oTable = $('#tableboxy').dataTable();"
    . "var aData2 = [];"
    . "var rowcollection2 =  oTable.$('.id_spesifik', {'page': 'all'});"
    . "rowcollection2.each(function(index,elem){"
    . "var value_id_spesifik = parseInt($(elem).val());"
    . " var hasil2 = (isNaN(value_id_spesifik)) ? 0 : value_id_spesifik;"
    . "aData2.push( hasil2 );"
//            . "alert(aData2);"
    . "});"
    
    . "var tgl_diserahkan   = $('#tgl_diserahkan').val();"
    . "var id_users         = $('#id_users').val();"
    . "var note_mutasi      = $('#note_mutasi').val();"
        . "if(tgl_diserahkan ==''){"
            . "alert('Tanggal diserahkan tidak boleh kosong');"
//            ."window.location ='{$url2}'"
        . "}else{"
        ."var dataString2 = 'id_mrp_inventory_spesifik='+ aData2 +'&jumlah=' + aData +'&tgl_diserahkan='+tgl_diserahkan+'&note='+note_mutasi+'&id_users='+id_users;"           
        ."$.ajax({"
        ."type : 'POST',"
        ."url : '".site_url("mrp/mrp-ajax/proses-mutasi-stock-request/{$id_mrp_request}")."',"
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
//           . "alert(aData);"              
    
//        . "alert(dataString2);"
        
                        
            . "});"                                             
            . "$('#btn-save2').click(function(){"
              . "$('#btn-save2').hide();"
                . "$('#img-5-office').show();"
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
//             . "var value_jumlah = parseInt($(elem).val());"
			. " var value_jumlah = parseFloat($(elem).val()).toFixed(2);" 
            . " var hasil2 = (isNaN(value_jumlah)) ? 0 : value_jumlah;"
            . "aData1.push( hasil2 );"
            . "});"
                                    
            . "var aData2 = [];"
            . "var rowcollection2 =  oTable.$('.jumlah_rg', {'page': 'all'});"
            . "rowcollection2.each(function(index,elem){"
            . "var value_jumlah_rg = parseInt($(elem).val());"
            . " var hasil3 = (isNaN(value_jumlah_rg)) ? 0 : value_jumlah_rg;"
            . "aData2.push( hasil3 );"
            . "});"                        
                ."var dataString2 = 'id_spesifik='+ aData +'&jumlah='+ aData1+ '&jumlah_rg='+ aData2 +'&note=' + note2+'&id_hr_pegawai='+ id_hr_pegawai +'&id_receiver='+ id_receiver2 +'&id_mrp_request='+ {$id_mrp_request};"
                ."$.ajax({"
                ."type : 'POST',"
                ."url : '".site_url("mrp/mrp-ajax-request/update-form-mrp-request-pengadaan/9")."',"
                ."data: dataString2,"
                ."dataType : 'html',"
                ."success: function(data) {"
                    ."window.location ='{$url2}'"
                ."},"
             ."});"
        . "});"
   . "});"
                                     
      . '$(function() {'
    . "$( '.date' ).datepicker({"
    . "showOtherMonths: true,"
    . "dateFormat: 'yy-mm-dd',"  
    . "selectOtherMonths: true,"  
    . "selectOtherYears: true,"
    . "});"
            . "$('#req-mutasi-stock2').click(function(){"
            . 'var table = '
            . '$("#tableboxy4").dataTable({'
            . '"order": [[ 0, "asc" ]],'
            . '"bDestroy": true'                         
            . '});'
            . "table.fnClearTable();"                        
            . 'ambil_data4(table, 0);'
            . '});'
                            
            . "$('#history-req-mutasi-stock2').click(function(){"
            . 'var table = '
            . '$("#history-mutasi").dataTable({'
                . '"order": [[ 1, "desc" ]],'
                . '"bDestroy": true'                    
            . '});'
                . "table.fnClearTable();"                   
                . 'history_mutasi(table, 0);'            
       . "});"      
//                                    
//            . "});"
                                    
            . "$('#dt-umum').click(function(){"
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
        . '$.post("'.site_url("mrp/mrp-ajax-request/get-form-mrp-request-pengadaan-umum/{$id_mrp_request}/{$this->session->userdata("id")}/").'/"+mulai, function(data){'
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
        . '$.post("'.site_url("mrp/mrp-ajax-request/get-list-form-mrp-request-pengadaan-umum/{$id_mrp_request}/{$this->session->userdata("id")}/").'/"+mulai, function(data){'
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
                
    . 'function ambil_data4(table, mulai){'
    . '$.post("'.site_url("mrp/mrp-ajax-stock/ajax-form-mrp-request-pengadaan-2/{$id_mrp_request}").'/"+mulai, function(data){'
      . '$("#loader-page4").show();'
      . 'var hasil = $.parseJSON(data);'
      . 'if(hasil.status == 2){'
            . 'if(hasil.flag == 2){'
            . '$("#loader-page4").hide();'
            . '}'
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
            
    . 'function history_mutasi(table, mulai){'
    . '$.post("'.site_url("mrp/mrp-ajax/get-history-mutasi/{$id_mrp_request}").'/"+mulai, function(data){'
      . '$("#loader-history-mutasi").show();'
      . 'var hasil = $.parseJSON(data);'
      . 'if(hasil.status == 2){'
        . 'table.fnAddData(hasil.hasil);'
        . 'history_mutasi(table, hasil.start);'
      . '}'
      . 'else{'
        . '$("#loader-history-mutasi").hide();'
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
      . "$(document).on('click', '#id-reject', function(evt){"
        . "var id = $(this).attr('isi');"
        . "$('#id_request').val(id);"
      . "});"
	  . "</script>";   

    $link = site_url('mrp/reject-request/9');
     $before_table = "<div class='modal fade' id='edit-keterangan-reject' tabindex='-1' role='dialog' aria-hidden='true'>
    <div class='modal-dialog'>
        <div class='modal-content'>
            <div class='modal-header'>
                <button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
                <h4 class='modal-title'>Note Reject</h4>
            </div>
            <form action='{$link}' method='post'>
                <div class='modal-body'>
                    <div class='form-group'>
                        <div class='input-group'>
<!--                            <span class='input-group-addon'>Note Reject:</span>-->
                            <input name='id_request' class='form-control' id='id_request' style='display: none'>
                            <textarea name='note_reject' placeholder='Note Reject' style='margin: 0px; width: 553px; height: 227px;'></textarea>
                        </div>
                    </div>
                </div>
                <div class='modal-footer clearfix'>

                    <button type='button' class='btn btn-danger' data-dismiss='modal'><i class='fa fa-times'></i> Cancel</button>

                    <button type='submit' class='btn btn-primary pull-left'> Submit</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
    </div>";
//       $list = $this->global_models->get("mrp_request", array("id_mrp_request" => $id_mrp_request)); 
         
       $list = $this->global_models->get_query("SELECT A.id_mrp_request,A.status,A.note,A.create_by_users,A.id_hr_pegawai,A.user_pegawai_receiver,A.tanggal_diserahkan,A.note_mutasi,"
        . "B.id_users,C.name,C.email,D.title AS name_organisasi,E.id_hr_pegawai AS pegawai_receiver,F.name AS name_receiver,F.email AS email_receiver"
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
      $this->template->build("mrp-request/pengadaan-umum/request-umum/view-request-umum", 
        array(
              'url'         => base_url()."themes/".DEFAULTTHEMES."/",
              'menu'        => 'mrp/mrp-request/request-pengadaan-umum',
              'title'       => lang("request_pengadaan_transportation"),
              'list'        => $list,
              'before_table' => $before_table,
              'breadcrumb'  => array(
                    "request_pengadaan_transportation"  => "mrp/mrp-request/request-pengadaan-umum"
                ),
              'css'         => $css,
              'foot'        => $foot,
              'img'       => base_url()."themes/".DEFAULTTHEMES."/img/ajax-loader.gif"
//              'items'       => $items
            ));
      $this->template
        ->set_layout('form')
        ->build("mrp-request/pengadaan-umum/request-umum/view-request-umum");
    }
    else{
      $pst = $this->input->post(NULL);
     
//      if($pst['btn_cancel'] == "Cancel"){
//         
//          $kirim = array(
//            "status"                    => 10,  
//            "update_by_users"           => $this->session->userdata("id"),
//            "update_date"               => date("Y-m-d H:i:s")
//        );
//       $this->global_models->update("mrp_request", array("id_mrp_request" => $pst['id_detail_cancel']),$kirim);
//       $ro_kode = $this->global_models->get_field("mrp_request", "code", array("id_mrp_request" => "{$pst['id_detail_cancel']}"));
//       $this->session->set_flashdata('success', "Request Orders dengan kode {$ro_kode} Berhasil di Cancel");
//       redirect("mrp/request-pengadaan-atk");
//      }
      
      if($pst['btn_closed'] == "closed"){
           $kirim = array(
            "status"                    => 9,  
            "update_by_users"           => $this->session->userdata("id"),
            "update_date"               => date("Y-m-d H:i:s")
        );
       $this->global_models->update("mrp_request", array("id_mrp_request" => $pst['id_detail']),$kirim);
       redirect("mrp/mrp-request/request-pengadaan-umum");
      }
      
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
      
      if($pst['btn_reject'] == "reject"){
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
      
      redirect("mrp/mrp-request/request-pengadaan-umum");
    }
  }
  
    public function add_request_pengadaan_office($id_mrp_request = 0){
      
    if(!$this->input->post(NULL)){
        
    $css = "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/datatables/dataTables.bootstrap.css' rel='stylesheet' type='text/css' />"
      . "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/jQueryUI/jquery-ui-1.10.3.custom.min.css' rel='stylesheet' type='text/css' />"
      . "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/select2.css' type='text/css' rel='stylesheet'>";
      $url = base_url('mrp/mrp-request/request-pengadaan-office');
      $url2 = base_url("mrp/mrp-request/add-request-pengadaan-office/{$id_mrp_request}");
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
//             . "var value_jumlah = parseInt($(elem).val());"
			. " var value_jumlah = parseFloat($(elem).val()).toFixed(2);" 
            . " var hasil2 = (isNaN(value_jumlah)) ? 0 : value_jumlah;"
            . "aData1.push( hasil2 );"
            . "});"
                        ."var dataString2 = 'id_spesifik='+ aData +'&jumlah='+ aData1+'&note=' + note2 +'&id_hr_pegawai='+id_hr_pegawai +'&id_receiver='+ id_receiver2 +'&id_mrp_request='+ {$id_mrp_request};"
                        ."$.ajax({"
                        ."type : 'POST',"
                        ."url : '".site_url("mrp/mrp-ajax-request/insert-form-mrp-request-pengadaan/7")."',"
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
//             . "var value_jumlah = parseInt($(elem).val());"
			. " var value_jumlah = parseFloat($(elem).val()).toFixed(2);" 
            . " var hasil2 = (isNaN(value_jumlah)) ? 0 : value_jumlah;"
            . "aData1.push( hasil2 );"
            . "});"
                        ."var dataString2 = 'id_spesifik='+ aData +'&jumlah='+ aData1+'&note=' + note2+'&id_hr_pegawai='+ id_hr_pegawai +'&id_receiver='+ id_receiver2 +'&id_mrp_request='+ {$id_mrp_request};"
                        ."$.ajax({"
                        ."type : 'POST',"
                        ."url : '".site_url("mrp/mrp-ajax-request/draft-form-mrp-request-pengadaan/7")."',"
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
//             . "var value_jumlah = parseInt($(elem).val());"
			. " var value_jumlah = parseFloat($(elem).val()).toFixed(2);" 
            . " var hasil2 = (isNaN(value_jumlah)) ? 0 : value_jumlah;"
            . "aData1.push( hasil2 );"
            . "});"
            
            . "var aData2 = [];"
            . "var rowcollection2 =  oTable.$('.jumlah_rg', {'page': 'all'});"
            . "rowcollection2.each(function(index,elem){"
            . "var value_jumlah_rg = parseInt($(elem).val());"
            . " var hasil3 = (isNaN(value_jumlah_rg)) ? 0 : value_jumlah_rg;"
            . "aData2.push( hasil3 );"
            . "});"
                        ."var dataString2 = 'id_spesifik='+ aData +'&jumlah='+ aData1 +'&jumlah_rg=' + aData2 +'&note=' + note2+'&id_hr_pegawai='+ id_hr_pegawai +'&id_receiver='+ id_receiver2 +'&id_mrp_request='+ {$id_mrp_request};"
                        ."$.ajax({"
                        ."type : 'POST',"
                        ."url : '".site_url("mrp/mrp-ajax-request/update-form-mrp-request-pengadaan/7")."',"
                        ."data: dataString2,"
                        ."dataType : 'html',"
                        ."success: function(data) {"
                            ."window.location ='{$url2}'"
                        ."},"
                     ."});"
            . "});"
                              
  ."$('#btn-proses').click(function(){"
    
    ."$('#btn-save').hide();"
    ."$('#loader-data').show();"
    ."$('#loader-page').show();"  
    . "var oTable = $('#tableboxy4').dataTable();"
    . "var aData = [];"
    . "var rowcollection =  oTable.$('.jumlah_mutasi', {'page': 'all'});"
    . "rowcollection.each(function(index,elem){"
//     . "var value_jumlah = parseInt($(elem).val());"
	. " var value_jumlah = parseFloat($(elem).val()).toFixed(2);" 
    . " var hasil = (isNaN(value_jumlah)) ? 0 : value_jumlah;"
    . "aData.push( hasil );"
//            . "alert(aData);"
    . "});"

    . "var oTable = $('#tableboxy').dataTable();"
    . "var aData2 = [];"
    . "var rowcollection2 =  oTable.$('.id_spesifik', {'page': 'all'});"
    . "rowcollection2.each(function(index,elem){"
    . "var value_id_spesifik = parseInt($(elem).val());"
    . " var hasil2 = (isNaN(value_id_spesifik)) ? 0 : value_id_spesifik;"
    . "aData2.push( hasil2 );"
//            . "alert(aData2);"
    . "});"
    
    . "var tgl_diserahkan   = $('#tgl_diserahkan').val();"
    . "var id_users         = $('#id_users').val();"
    . "var note_mutasi      = $('#note_mutasi').val();"
        . "if(tgl_diserahkan ==''){"
            . "alert('Tanggal diserahkan tidak boleh kosong');"
//            ."window.location ='{$url2}'"
        . "}else{"
        ."var dataString2 = 'id_mrp_inventory_spesifik='+ aData2 +'&jumlah=' + aData +'&tgl_diserahkan='+tgl_diserahkan+'&note='+note_mutasi+'&id_users='+id_users;"           
        ."$.ajax({"
        ."type : 'POST',"
        ."url : '".site_url("mrp/mrp-ajax/proses-mutasi-stock-request/{$id_mrp_request}")."',"
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
//           . "alert(aData);"              
    
//        . "alert(dataString2);"
        
                        
            . "});"                                             
            . "$('#btn-save2').click(function(){"
              . "$('#btn-save2').hide();"
                . "$('#img-5-office').show();"
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
//             . "var value_jumlah = parseInt($(elem).val());"
			. " var value_jumlah = parseFloat($(elem).val()).toFixed(2);" 
            . " var hasil2 = (isNaN(value_jumlah)) ? 0 : value_jumlah;"
            . "aData1.push( hasil2 );"
            . "});"
                                    
            . "var aData2 = [];"
            . "var rowcollection2 =  oTable.$('.jumlah_rg', {'page': 'all'});"
            . "rowcollection2.each(function(index,elem){"
            . "var value_jumlah_rg = parseInt($(elem).val());"
            . " var hasil3 = (isNaN(value_jumlah_rg)) ? 0 : value_jumlah_rg;"
            . "aData2.push( hasil3 );"
            . "});"                        
                ."var dataString2 = 'id_spesifik='+ aData +'&jumlah='+ aData1+ '&jumlah_rg='+ aData2 +'&note=' + note2+'&id_hr_pegawai='+ id_hr_pegawai +'&id_receiver='+ id_receiver2 +'&id_mrp_request='+ {$id_mrp_request};"
                ."$.ajax({"
                ."type : 'POST',"
                ."url : '".site_url("mrp/mrp-ajax-request/update-form-mrp-request-pengadaan/7")."',"
                ."data: dataString2,"
                ."dataType : 'html',"
                ."success: function(data) {"
                    ."window.location ='{$url2}'"
                ."},"
             ."});"
        . "});"
   . "});"
                                     
      . '$(function() {'
    . "$( '.date' ).datepicker({"
    . "showOtherMonths: true,"
    . "dateFormat: 'yy-mm-dd',"  
    . "selectOtherMonths: true,"  
    . "selectOtherYears: true,"
    . "});"
            . "$('#req-mutasi-stock2').click(function(){"
            . 'var table = '
            . '$("#tableboxy4").dataTable({'
            . '"order": [[ 0, "asc" ]],'
            . '"bDestroy": true'                         
            . '});'
            . "table.fnClearTable();"                        
            . 'ambil_data4(table, 0);'
            . '});'
                            
            . "$('#history-req-mutasi-stock2').click(function(){"
            . 'var table = '
            . '$("#history-mutasi").dataTable({'
                . '"order": [[ 1, "desc" ]],'
                . '"bDestroy": true'                    
            . '});'
                . "table.fnClearTable();"                   
                . 'history_mutasi(table, 0);'            
       . "});"      
//                                    
//            . "});"
                                    
            . "$('#dt-office').click(function(){"
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
        . '$.post("'.site_url("mrp/mrp-ajax-request/get-form-mrp-request-pengadaan-office/{$id_mrp_request}/{$this->session->userdata("id")}/").'/"+mulai, function(data){'
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
        . '$.post("'.site_url("mrp/mrp-ajax-request/get-list-form-mrp-request-pengadaan-office/{$id_mrp_request}/{$this->session->userdata("id")}/").'/"+mulai, function(data){'
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
                
    . 'function ambil_data4(table, mulai){'
    . '$.post("'.site_url("mrp/mrp-ajax-stock/ajax-form-mrp-request-pengadaan-2/{$id_mrp_request}").'/"+mulai, function(data){'
      . '$("#loader-page4").show();'
      . 'var hasil = $.parseJSON(data);'
      . 'if(hasil.status == 2){'
            . 'if(hasil.flag == 2){'
            . '$("#loader-page4").hide();'
            . '}'
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
            
    . 'function history_mutasi(table, mulai){'
    . '$.post("'.site_url("mrp/mrp-ajax/get-history-mutasi/{$id_mrp_request}").'/"+mulai, function(data){'
      . '$("#loader-history-mutasi").show();'
      . 'var hasil = $.parseJSON(data);'
      . 'if(hasil.status == 2){'
        . 'table.fnAddData(hasil.hasil);'
        . 'history_mutasi(table, hasil.start);'
      . '}'
      . 'else{'
        . '$("#loader-history-mutasi").hide();'
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
      . "$(document).on('click', '#id-reject', function(evt){"
        . "var id = $(this).attr('isi');"
        . "$('#id_request').val(id);"
      . "});"
	  . "</script>";   

    $link = site_url('mrp/reject-request/7');
     $before_table = "<div class='modal fade' id='edit-keterangan-reject' tabindex='-1' role='dialog' aria-hidden='true'>
    <div class='modal-dialog'>
        <div class='modal-content'>
            <div class='modal-header'>
                <button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
                <h4 class='modal-title'>Note Reject</h4>
            </div>
            <form action='{$link}' method='post'>
                <div class='modal-body'>
                    <div class='form-group'>
                        <div class='input-group'>
<!--                            <span class='input-group-addon'>Note Reject:</span>-->
                            <input name='id_request' class='form-control' id='id_request' style='display: none'>
                            <textarea name='note_reject' placeholder='Note Reject' style='margin: 0px; width: 553px; height: 227px;'></textarea>
                        </div>
                    </div>
                </div>
                <div class='modal-footer clearfix'>

                    <button type='button' class='btn btn-danger' data-dismiss='modal'><i class='fa fa-times'></i> Cancel</button>

                    <button type='submit' class='btn btn-primary pull-left'> Submit</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
    </div>";
//       $list = $this->global_models->get("mrp_request", array("id_mrp_request" => $id_mrp_request)); 
         
       $list = $this->global_models->get_query("SELECT A.id_mrp_request,A.status,A.note,A.create_by_users,A.id_hr_pegawai,A.user_pegawai_receiver,A.tanggal_diserahkan,A.note_mutasi,"
        . "B.id_users,C.name,C.email,D.title AS name_organisasi,E.id_hr_pegawai AS pegawai_receiver,F.name AS name_receiver,F.email AS email_receiver"
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
      $this->template->build("mrp-request/pengadaan-office/request-office/view-request-office", 
        array(
              'url'         => base_url()."themes/".DEFAULTTHEMES."/",
              'menu'        => 'mrp/mrp-request/request-pengadaan-office',
              'title'       => lang("mrp_add_request_pengadaan_office"),
              'list'        => $list,
              'before_table' => $before_table,
              'breadcrumb'  => array(
                    "mrp_request_pengadaan_office"  => "mrp/mrp-request/request-pengadaan-office"
                ),
              'css'         => $css,
              'foot'        => $foot,
              'img'       => base_url()."themes/".DEFAULTTHEMES."/img/ajax-loader.gif"
//              'items'       => $items
            ));
      $this->template
        ->set_layout('form')
        ->build("mrp-request/pengadaan-office/request-office/view-request-office");
    }
    else{
      $pst = $this->input->post(NULL);
     
//      if($pst['btn_cancel'] == "Cancel"){
//         
//          $kirim = array(
//            "status"                    => 10,  
//            "update_by_users"           => $this->session->userdata("id"),
//            "update_date"               => date("Y-m-d H:i:s")
//        );
//       $this->global_models->update("mrp_request", array("id_mrp_request" => $pst['id_detail_cancel']),$kirim);
//       $ro_kode = $this->global_models->get_field("mrp_request", "code", array("id_mrp_request" => "{$pst['id_detail_cancel']}"));
//       $this->session->set_flashdata('success', "Request Orders dengan kode {$ro_kode} Berhasil di Cancel");
//       redirect("mrp/request-pengadaan-atk");
//      }
      
      if($pst['btn_closed'] == "closed"){
           $kirim = array(
            "status"                    => 9,  
            "update_by_users"           => $this->session->userdata("id"),
            "update_date"               => date("Y-m-d H:i:s")
        );
       $this->global_models->update("mrp_request", array("id_mrp_request" => $pst['id_detail']),$kirim);
       redirect("mrp/mrp-request/request-pengadaan-promosi");
      }
      
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
      
      if($pst['btn_reject'] == "reject"){
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
      
      redirect("mrp/mrp-request/request-pengadaan-promosi");
    }
  }

  public function add_request_pengadaan_technical($id_mrp_request = 0){
      
    if(!$this->input->post(NULL)){
        
    $css = "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/datatables/dataTables.bootstrap.css' rel='stylesheet' type='text/css' />"
      . "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/jQueryUI/jquery-ui-1.10.3.custom.min.css' rel='stylesheet' type='text/css' />"
      . "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/select2.css' type='text/css' rel='stylesheet'>";
      $url = base_url('mrp/mrp-request/request-pengadaan-technical');
      $url2 = base_url("mrp/mrp-request/add-request-pengadaan-technical/{$id_mrp_request}");
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
//             . "var value_jumlah = parseInt($(elem).val());"
			. " var value_jumlah = parseFloat($(elem).val()).toFixed(2);" 
            . " var hasil2 = (isNaN(value_jumlah)) ? 0 : value_jumlah;"
            . "aData1.push( hasil2 );"
            . "});"
                        ."var dataString2 = 'id_spesifik='+ aData +'&jumlah='+ aData1+'&note=' + note2 +'&id_hr_pegawai='+id_hr_pegawai +'&id_receiver='+ id_receiver2 +'&id_mrp_request='+ {$id_mrp_request};"
                        ."$.ajax({"
                        ."type : 'POST',"
                        ."url : '".site_url("mrp/mrp-ajax-request/insert-form-mrp-request-pengadaan/4")."',"
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
//             . "var value_jumlah = parseInt($(elem).val());"
			. " var value_jumlah = parseFloat($(elem).val()).toFixed(2);" 
            . " var hasil2 = (isNaN(value_jumlah)) ? 0 : value_jumlah;"
            . "aData1.push( hasil2 );"
            . "});"
                        ."var dataString2 = 'id_spesifik='+ aData +'&jumlah='+ aData1+'&note=' + note2+'&id_hr_pegawai='+ id_hr_pegawai +'&id_receiver='+ id_receiver2 +'&id_mrp_request='+ {$id_mrp_request};"
                        ."$.ajax({"
                        ."type : 'POST',"
                        ."url : '".site_url("mrp/mrp-ajax-request/draft-form-mrp-request-pengadaan/4")."',"
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
//             . "var value_jumlah = parseInt($(elem).val());"
			. " var value_jumlah = parseFloat($(elem).val()).toFixed(2);" 
            . " var hasil2 = (isNaN(value_jumlah)) ? 0 : value_jumlah;"
            . "aData1.push( hasil2 );"
            . "});"
            
            . "var aData2 = [];"
            . "var rowcollection2 =  oTable.$('.jumlah_rg', {'page': 'all'});"
            . "rowcollection2.each(function(index,elem){"
            . "var value_jumlah_rg = parseInt($(elem).val());"
            . " var hasil3 = (isNaN(value_jumlah_rg)) ? 0 : value_jumlah_rg;"
            . "aData2.push( hasil3 );"
            . "});"
                        ."var dataString2 = 'id_spesifik='+ aData +'&jumlah='+ aData1 +'&jumlah_rg=' + aData2 +'&note=' + note2+'&id_hr_pegawai='+ id_hr_pegawai +'&id_receiver='+ id_receiver2 +'&id_mrp_request='+ {$id_mrp_request};"
                        ."$.ajax({"
                        ."type : 'POST',"
                        ."url : '".site_url("mrp/mrp-ajax-request/update-form-mrp-request-pengadaan/4")."',"
                        ."data: dataString2,"
                        ."dataType : 'html',"
                        ."success: function(data) {"
                            ."window.location ='{$url2}'"
                        ."},"
                     ."});"
            . "});"
                              
  ."$('#btn-proses').click(function(){"
    
    ."$('#btn-save').hide();"
    ."$('#loader-data').show();"
    ."$('#loader-page').show();"  
    . "var oTable = $('#tableboxy4').dataTable();"
    . "var aData = [];"
    . "var rowcollection =  oTable.$('.jumlah_mutasi', {'page': 'all'});"
    . "rowcollection.each(function(index,elem){"
//     . "var value_jumlah = parseInt($(elem).val());"
	. " var value_jumlah = parseFloat($(elem).val()).toFixed(2);" 
    . " var hasil = (isNaN(value_jumlah)) ? 0 : value_jumlah;"
    . "aData.push( hasil );"
//            . "alert(aData);"
    . "});"

    . "var oTable = $('#tableboxy').dataTable();"
    . "var aData2 = [];"
    . "var rowcollection2 =  oTable.$('.id_spesifik', {'page': 'all'});"
    . "rowcollection2.each(function(index,elem){"
    . "var value_id_spesifik = parseInt($(elem).val());"
    . " var hasil2 = (isNaN(value_id_spesifik)) ? 0 : value_id_spesifik;"
    . "aData2.push( hasil2 );"
//            . "alert(aData2);"
    . "});"
    
    . "var tgl_diserahkan   = $('#tgl_diserahkan').val();"
    . "var id_users         = $('#id_users').val();"
    . "var note_mutasi      = $('#note_mutasi').val();"
        . "if(tgl_diserahkan ==''){"
            . "alert('Tanggal diserahkan tidak boleh kosong');"
//            ."window.location ='{$url2}'"
        . "}else{"
        ."var dataString2 = 'id_mrp_inventory_spesifik='+ aData2 +'&jumlah=' + aData +'&tgl_diserahkan='+tgl_diserahkan+'&note='+note_mutasi+'&id_users='+id_users;"           
        ."$.ajax({"
        ."type : 'POST',"
        ."url : '".site_url("mrp/mrp-ajax/proses-mutasi-stock-request/{$id_mrp_request}")."',"
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
//           . "alert(aData);"              
    
//        . "alert(dataString2);"
        
                        
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
//             . "var value_jumlah = parseInt($(elem).val());"
			. " var value_jumlah = parseFloat($(elem).val()).toFixed(2);"             
            . " var hasil2 = (isNaN(value_jumlah)) ? 0 : value_jumlah;"
            . "aData1.push( hasil2 );"
            . "});"
                                    
            . "var aData2 = [];"
            . "var rowcollection2 =  oTable.$('.jumlah_rg', {'page': 'all'});"
            . "rowcollection2.each(function(index,elem){"
            . "var value_jumlah_rg = parseInt($(elem).val());"
            . " var hasil3 = (isNaN(value_jumlah_rg)) ? 0 : value_jumlah_rg;"
            . "aData2.push( hasil3 );"
            . "});"                        
                ."var dataString2 = 'id_spesifik='+ aData +'&jumlah='+ aData1+ '&jumlah_rg='+ aData2 +'&note=' + note2+'&id_hr_pegawai='+ id_hr_pegawai +'&id_receiver='+ id_receiver2 +'&id_mrp_request='+ {$id_mrp_request};"
                ."$.ajax({"
                ."type : 'POST',"
                ."url : '".site_url("mrp/mrp-ajax-request/update-form-mrp-request-pengadaan/4")."',"
                ."data: dataString2,"
                ."dataType : 'html',"
                ."success: function(data) {"
                    ."window.location ='{$url2}'"
                ."},"
             ."});"
        . "});"
   . "});"
                                     
      . '$(function() {'
    . "$( '.date' ).datepicker({"
    . "showOtherMonths: true,"
    . "dateFormat: 'yy-mm-dd',"  
    . "selectOtherMonths: true,"  
    . "selectOtherYears: true,"
    . "});"
            . "$('#req-mutasi-stock2').click(function(){"
            . 'var table = '
            . '$("#tableboxy4").dataTable({'
            . '"order": [[ 0, "asc" ]],'
            . '"bDestroy": true'                         
            . '});'
            . "table.fnClearTable();"                        
            . 'ambil_data4(table, 0);'
            . '});'
                            
            . "$('#history-req-mutasi-stock2').click(function(){"
            . 'var table = '
            . '$("#history-mutasi").dataTable({'
                . '"order": [[ 1, "desc" ]],'
                . '"bDestroy": true'                    
            . '});'
                . "table.fnClearTable();"                   
                . 'history_mutasi(table, 0);'            
       . "});"      
//                                    
//            . "});"
                                    
            . "$('#dt-technical').click(function(){"
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
        . '$.post("'.site_url("mrp/mrp-ajax-request/get-form-mrp-request-pengadaan-technical/{$id_mrp_request}/{$this->session->userdata("id")}/").'/"+mulai, function(data){'
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
        . '$.post("'.site_url("mrp/mrp-ajax-request/get-list-form-mrp-request-pengadaan-technical/{$id_mrp_request}/{$this->session->userdata("id")}/").'/"+mulai, function(data){'
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
                
    . 'function ambil_data4(table, mulai){'
    . '$.post("'.site_url("mrp/mrp-ajax-stock/ajax-form-mrp-request-pengadaan-2/{$id_mrp_request}").'/"+mulai, function(data){'
      . '$("#loader-page4").show();'
      . 'var hasil = $.parseJSON(data);'
      . 'if(hasil.status == 2){'
            . 'if(hasil.flag == 2){'
            . '$("#loader-page4").hide();'
            . '}'
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
            
    . 'function history_mutasi(table, mulai){'
    . '$.post("'.site_url("mrp/mrp-ajax/get-history-mutasi/{$id_mrp_request}").'/"+mulai, function(data){'
      . '$("#loader-history-mutasi").show();'
      . 'var hasil = $.parseJSON(data);'
      . 'if(hasil.status == 2){'
        . 'table.fnAddData(hasil.hasil);'
        . 'history_mutasi(table, hasil.start);'
      . '}'
      . 'else{'
        . '$("#loader-history-mutasi").hide();'
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
      . "$(document).on('click', '#id-reject', function(evt){"
        . "var id = $(this).attr('isi');"
        . "$('#id_request').val(id);"
      . "});"
	  . "</script>";   

    $link = site_url('mrp/reject-request/4');
     $before_table = "<div class='modal fade' id='edit-keterangan-reject' tabindex='-1' role='dialog' aria-hidden='true'>
    <div class='modal-dialog'>
        <div class='modal-content'>
            <div class='modal-header'>
                <button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
                <h4 class='modal-title'>Note Reject</h4>
            </div>
            <form action='{$link}' method='post'>
                <div class='modal-body'>
                    <div class='form-group'>
                        <div class='input-group'>
<!--                            <span class='input-group-addon'>Note Reject:</span>-->
                            <input name='id_request' class='form-control' id='id_request' style='display: none'>
                            <textarea name='note_reject' placeholder='Note Reject' style='margin: 0px; width: 553px; height: 227px;'></textarea>
                        </div>
                    </div>
                </div>
                <div class='modal-footer clearfix'>

                    <button type='button' class='btn btn-danger' data-dismiss='modal'><i class='fa fa-times'></i> Cancel</button>

                    <button type='submit' class='btn btn-primary pull-left'> Submit</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
    </div>";
//       $list = $this->global_models->get("mrp_request", array("id_mrp_request" => $id_mrp_request)); 
         
       $list = $this->global_models->get_query("SELECT A.id_mrp_request,A.status,A.note,A.create_by_users,A.id_hr_pegawai,A.user_pegawai_receiver,A.tanggal_diserahkan,A.note_mutasi,"
        . "B.id_users,C.name,C.email,D.title AS name_organisasi,E.id_hr_pegawai AS pegawai_receiver,F.name AS name_receiver,F.email AS email_receiver"
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
      $this->template->build("mrp-request/pengadaan-technical/request-technical/view-request-technical", 
        array(
              'url'         => base_url()."themes/".DEFAULTTHEMES."/",
              'menu'        => 'mrp/mrp-request/request-pengadaan-technical',
              'title'       => lang("mrp_add_request_pengadaan_technical"),
              'list'        => $list,
              'before_table' => $before_table,
              'breadcrumb'  => array(
                    "mrp_request_pengadaan_technical"  => "mrp/mrp-request/request-pengadaan-technical"
                ),
              'css'         => $css,
              'foot'        => $foot,
              'img'       => base_url()."themes/".DEFAULTTHEMES."/img/ajax-loader.gif"
//              'items'       => $items
            ));
      $this->template
        ->set_layout('form')
        ->build("mrp-request/pengadaan-technical/request-technical/view-request-technical");
    }
    else{
      $pst = $this->input->post(NULL);
     
//      if($pst['btn_cancel'] == "Cancel"){
//         
//          $kirim = array(
//            "status"                    => 10,  
//            "update_by_users"           => $this->session->userdata("id"),
//            "update_date"               => date("Y-m-d H:i:s")
//        );
//       $this->global_models->update("mrp_request", array("id_mrp_request" => $pst['id_detail_cancel']),$kirim);
//       $ro_kode = $this->global_models->get_field("mrp_request", "code", array("id_mrp_request" => "{$pst['id_detail_cancel']}"));
//       $this->session->set_flashdata('success', "Request Orders dengan kode {$ro_kode} Berhasil di Cancel");
//       redirect("mrp/request-pengadaan-atk");
//      }
      
      if($pst['btn_closed'] == "closed"){
           $kirim = array(
            "status"                    => 9,  
            "update_by_users"           => $this->session->userdata("id"),
            "update_date"               => date("Y-m-d H:i:s")
        );
       $this->global_models->update("mrp_request", array("id_mrp_request" => $pst['id_detail']),$kirim);
       redirect("mrp/mrp-request/request-pengadaan-technical");
      }
      
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
      
      if($pst['btn_reject'] == "reject"){
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
      
      redirect("mrp/mrp-request/request-pengadaan-technical");
    }
  }
  
  public function add_request_pengadaan_service($id_mrp_request = 0){
      
    if(!$this->input->post(NULL)){
        
    $css = "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/datatables/dataTables.bootstrap.css' rel='stylesheet' type='text/css' />"
      . "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/jQueryUI/jquery-ui-1.10.3.custom.min.css' rel='stylesheet' type='text/css' />"
      . "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/select2.css' type='text/css' rel='stylesheet'>";
      $url = base_url('mrp/mrp-request/request-pengadaan-service');
      $url2 = base_url("mrp/mrp-request/add-request-pengadaan-service/{$id_mrp_request}");
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
//             . "var value_jumlah = parseInt($(elem).val());"
			. " var value_jumlah = parseFloat($(elem).val()).toFixed(2);" 
            . " var hasil2 = (isNaN(value_jumlah)) ? 0 : value_jumlah;"
            . "aData1.push( hasil2 );"
            . "});"
                        ."var dataString2 = 'id_spesifik='+ aData +'&jumlah='+ aData1+'&note=' + note2 +'&id_hr_pegawai='+id_hr_pegawai +'&id_receiver='+ id_receiver2 +'&id_mrp_request='+ {$id_mrp_request};"
                        ."$.ajax({"
                        ."type : 'POST',"
                        ."url : '".site_url("mrp/mrp-ajax-request/insert-form-mrp-request-pengadaan/5")."',"
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
//             . "var value_jumlah = parseInt($(elem).val());"
			. " var value_jumlah = parseFloat($(elem).val()).toFixed(2);" 
            . " var hasil2 = (isNaN(value_jumlah)) ? 0 : value_jumlah;"
            . "aData1.push( hasil2 );"
            . "});"
                        ."var dataString2 = 'id_spesifik='+ aData +'&jumlah='+ aData1+'&note=' + note2+'&id_hr_pegawai='+ id_hr_pegawai +'&id_receiver='+ id_receiver2 +'&id_mrp_request='+ {$id_mrp_request};"
                        ."$.ajax({"
                        ."type : 'POST',"
                        ."url : '".site_url("mrp/mrp-ajax-request/draft-form-mrp-request-pengadaan/5")."',"
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
//             . "var value_jumlah = parseInt($(elem).val());"
            . " var value_jumlah = parseFloat($(elem).val()).toFixed(2);" 
            . " var hasil2 = (isNaN(value_jumlah)) ? 0 : value_jumlah;"
            . "aData1.push( hasil2 );"
            . "});"
            
            . "var aData2 = [];"
            . "var rowcollection2 =  oTable.$('.jumlah_rg', {'page': 'all'});"
            . "rowcollection2.each(function(index,elem){"
            . "var value_jumlah_rg = parseInt($(elem).val());"
            . " var hasil3 = (isNaN(value_jumlah_rg)) ? 0 : value_jumlah_rg;"
            . "aData2.push( hasil3 );"
            . "});"
                        ."var dataString2 = 'id_spesifik='+ aData +'&jumlah='+ aData1 +'&jumlah_rg=' + aData2 +'&note=' + note2+'&id_hr_pegawai='+ id_hr_pegawai +'&id_receiver='+ id_receiver2 +'&id_mrp_request='+ {$id_mrp_request};"
                        ."$.ajax({"
                        ."type : 'POST',"
                        ."url : '".site_url("mrp/mrp-ajax-request/update-form-mrp-request-pengadaan/5")."',"
                        ."data: dataString2,"
                        ."dataType : 'html',"
                        ."success: function(data) {"
                            ."window.location ='{$url2}'"
                        ."},"
                     ."});"
            . "});"
                              
  ."$('#btn-proses').click(function(){"
    
    ."$('#btn-save').hide();"
    ."$('#loader-data').show();"
    ."$('#loader-page').show();"  
    . "var oTable = $('#tableboxy4').dataTable();"
    . "var aData = [];"
    . "var rowcollection =  oTable.$('.jumlah_mutasi', {'page': 'all'});"
    . "rowcollection.each(function(index,elem){"
//     . "var value_jumlah = parseInt($(elem).val());"
    . " var value_jumlah = parseFloat($(elem).val()).toFixed(2);" 
    . " var hasil = (isNaN(value_jumlah)) ? 0 : value_jumlah;"
    . "aData.push( hasil );"
//            . "alert(aData);"
    . "});"

    . "var oTable = $('#tableboxy').dataTable();"
    . "var aData2 = [];"
    . "var rowcollection2 =  oTable.$('.id_spesifik', {'page': 'all'});"
    . "rowcollection2.each(function(index,elem){"
    . "var value_id_spesifik = parseInt($(elem).val());"
    . " var hasil2 = (isNaN(value_id_spesifik)) ? 0 : value_id_spesifik;"
    . "aData2.push( hasil2 );"
//            . "alert(aData2);"
    . "});"
    
    . "var tgl_diserahkan   = $('#tgl_diserahkan').val();"
    . "var id_users         = $('#id_users').val();"
    . "var note_mutasi      = $('#note_mutasi').val();"
        . "if(tgl_diserahkan ==''){"
            . "alert('Tanggal diserahkan tidak boleh kosong');"
//            ."window.location ='{$url2}'"
        . "}else{"
        ."var dataString2 = 'id_mrp_inventory_spesifik='+ aData2 +'&jumlah=' + aData +'&tgl_diserahkan='+tgl_diserahkan+'&note='+note_mutasi+'&id_users='+id_users;"           
        ."$.ajax({"
        ."type : 'POST',"
        ."url : '".site_url("mrp/mrp-ajax/proses-mutasi-stock-request/{$id_mrp_request}")."',"
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
//           . "alert(aData);"              
    
//        . "alert(dataString2);"
        
                        
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
//             . "var value_jumlah = parseInt($(elem).val());"
			. " var value_jumlah = parseFloat($(elem).val()).toFixed(2);" 
            . " var hasil2 = (isNaN(value_jumlah)) ? 0 : value_jumlah;"
            . "aData1.push( hasil2 );"
            . "});"
                                    
            . "var aData2 = [];"
            . "var rowcollection2 =  oTable.$('.jumlah_rg', {'page': 'all'});"
            . "rowcollection2.each(function(index,elem){"
            . "var value_jumlah_rg = parseInt($(elem).val());"
            . " var hasil3 = (isNaN(value_jumlah_rg)) ? 0 : value_jumlah_rg;"
            . "aData2.push( hasil3 );"
            . "});"                        
                ."var dataString2 = 'id_spesifik='+ aData +'&jumlah='+ aData1+ '&jumlah_rg='+ aData2 +'&note=' + note2+'&id_hr_pegawai='+ id_hr_pegawai +'&id_receiver='+ id_receiver2 +'&id_mrp_request='+ {$id_mrp_request};"
                ."$.ajax({"
                ."type : 'POST',"
                ."url : '".site_url("mrp/mrp-ajax-request/update-form-mrp-request-pengadaan/5")."',"
                ."data: dataString2,"
                ."dataType : 'html',"
                ."success: function(data) {"
                    ."window.location ='{$url2}'"
                ."},"
             ."});"
        . "});"
   . "});"
                                     
      . '$(function() {'
    . "$( '.date' ).datepicker({"
    . "showOtherMonths: true,"
    . "dateFormat: 'yy-mm-dd',"  
    . "selectOtherMonths: true,"  
    . "selectOtherYears: true,"
    . "});"
            . "$('#req-mutasi-stock2').click(function(){"
            . 'var table = '
            . '$("#tableboxy4").dataTable({'
            . '"order": [[ 0, "asc" ]],'
            . '"bDestroy": true'                         
            . '});'
            . "table.fnClearTable();"                        
            . 'ambil_data4(table, 0);'
            . '});'
                            
            . "$('#history-req-mutasi-stock2').click(function(){"
            . 'var table = '
            . '$("#history-mutasi").dataTable({'
                . '"order": [[ 1, "desc" ]],'
                . '"bDestroy": true'                    
            . '});'
                . "table.fnClearTable();"                   
                . 'history_mutasi(table, 0);'            
       . "});"      
//                                    
//            . "});"
                                    
            . "$('#dt-service').click(function(){"
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
        . '$.post("'.site_url("mrp/mrp-ajax-request/get-form-mrp-request-pengadaan-service/{$id_mrp_request}/{$this->session->userdata("id")}/").'/"+mulai, function(data){'
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
        . '$.post("'.site_url("mrp/mrp-ajax-request/get-list-form-mrp-request-pengadaan-service/{$id_mrp_request}/{$this->session->userdata("id")}/").'/"+mulai, function(data){'
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
                
    . 'function ambil_data4(table, mulai){'
    . '$.post("'.site_url("mrp/mrp-ajax-stock/ajax-form-mrp-request-pengadaan-2/{$id_mrp_request}").'/"+mulai, function(data){'
      . '$("#loader-page4").show();'
      . 'var hasil = $.parseJSON(data);'
      . 'if(hasil.status == 2){'
            . 'if(hasil.flag == 2){'
            . '$("#loader-page4").hide();'
            . '}'
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
            
    . 'function history_mutasi(table, mulai){'
    . '$.post("'.site_url("mrp/mrp-ajax/get-history-mutasi/{$id_mrp_request}").'/"+mulai, function(data){'
      . '$("#loader-history-mutasi").show();'
      . 'var hasil = $.parseJSON(data);'
      . 'if(hasil.status == 2){'
        . 'table.fnAddData(hasil.hasil);'
        . 'history_mutasi(table, hasil.start);'
      . '}'
      . 'else{'
        . '$("#loader-history-mutasi").hide();'
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
      . "$(document).on('click', '#id-reject', function(evt){"
        . "var id = $(this).attr('isi');"
        . "$('#id_request').val(id);"
      . "});"
	  . "</script>";   

    $link = site_url('mrp/reject-request/5');
     $before_table = "<div class='modal fade' id='edit-keterangan-reject' tabindex='-1' role='dialog' aria-hidden='true'>
    <div class='modal-dialog'>
        <div class='modal-content'>
            <div class='modal-header'>
                <button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
                <h4 class='modal-title'>Note Reject</h4>
            </div>
            <form action='{$link}' method='post'>
                <div class='modal-body'>
                    <div class='form-group'>
                        <div class='input-group'>
<!--                            <span class='input-group-addon'>Note Reject:</span>-->
                            <input name='id_request' class='form-control' id='id_request' style='display: none'>
                            <textarea name='note_reject' placeholder='Note Reject' style='margin: 0px; width: 553px; height: 227px;'></textarea>
                        </div>
                    </div>
                </div>
                <div class='modal-footer clearfix'>

                    <button type='button' class='btn btn-danger' data-dismiss='modal'><i class='fa fa-times'></i> Cancel</button>

                    <button type='submit' class='btn btn-primary pull-left'> Submit</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
    </div>";
//       $list = $this->global_models->get("mrp_request", array("id_mrp_request" => $id_mrp_request)); 
         
       $list = $this->global_models->get_query("SELECT A.id_mrp_request,A.status,A.note,A.create_by_users,A.id_hr_pegawai,A.user_pegawai_receiver,A.tanggal_diserahkan,A.note_mutasi,"
        . "B.id_users,C.name,C.email,D.title AS name_organisasi,E.id_hr_pegawai AS pegawai_receiver,F.name AS name_receiver,F.email AS email_receiver"
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
      $this->template->build("mrp-request/pengadaan-service/request-service/view-request-service", 
        array(
              'url'         => base_url()."themes/".DEFAULTTHEMES."/",
              'menu'        => 'mrp/mrp-request/request-pengadaan-service',
              'title'       => lang("mrp_add_request_pengadaan_service"),
              'list'        => $list,
              'before_table' => $before_table,
              'breadcrumb'  => array(
                    "mrp_request_pengadaan_service"  => "mrp/mrp-request/request-pengadaan-service"
                ),
              'css'         => $css,
              'foot'        => $foot,
              'img'       => base_url()."themes/".DEFAULTTHEMES."/img/ajax-loader.gif"
//              'items'       => $items
            ));
      $this->template
        ->set_layout('form')
        ->build("mrp-request/pengadaan-service/request-service/view-request-service");
    }
    else{
      $pst = $this->input->post(NULL);
     
//      if($pst['btn_cancel'] == "Cancel"){
//         
//          $kirim = array(
//            "status"                    => 10,  
//            "update_by_users"           => $this->session->userdata("id"),
//            "update_date"               => date("Y-m-d H:i:s")
//        );
//       $this->global_models->update("mrp_request", array("id_mrp_request" => $pst['id_detail_cancel']),$kirim);
//       $ro_kode = $this->global_models->get_field("mrp_request", "code", array("id_mrp_request" => "{$pst['id_detail_cancel']}"));
//       $this->session->set_flashdata('success', "Request Orders dengan kode {$ro_kode} Berhasil di Cancel");
//       redirect("mrp/request-pengadaan-atk");
//      }
      
      if($pst['btn_closed'] == "closed"){
           $kirim = array(
            "status"                    => 9,  
            "update_by_users"           => $this->session->userdata("id"),
            "update_date"               => date("Y-m-d H:i:s")
        );
       $this->global_models->update("mrp_request", array("id_mrp_request" => $pst['id_detail']),$kirim);
       redirect("mrp/mrp-request/request-pengadaan-service");
      }
      
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
      
      if($pst['btn_reject'] == "reject"){
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
      
      redirect("mrp/mrp-request/request-pengadaan-service");
    }
  }
  
}