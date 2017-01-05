<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mrp extends MX_Controller {
    
  function __construct() {      
    $this->menu = $this->cek();
  }
  
  function cancel_request($type = 0){
      
      $pst = $this->input->post(NULL); 
//      print_r($pst);
//      die;
      $note_delete = $pst['note_delete'];
      if($note_delete){
          $now = date("Y-m-d H:i:s");
      $note_b =$this->global_models->get_field("mrp_request","note_warning",array("id_mrp_request" => "{$pst['id_request']}"));
      
      $kirim = array(
            "status"                    => 10,  
            "update_by_users"           => $this->session->userdata("id"),
            "note_warning"              => "(note delete {$now})".$note_delete."<br>".$note_b,
            "update_date"               => date("Y-m-d H:i:s")
        );
       $this->global_models->update("mrp_request", array("id_mrp_request" => $pst['id_request']),$kirim);
       $ro_kode =$this->global_models->get_field("mrp_request","code",array("id_mrp_request" => "{$pst['id_request']}"));
       $this->session->set_flashdata('success', "Request Orders dengan kode {$ro_kode} Berhasil di Delete");
       }else{
           $this->session->set_flashdata('notice', 'Note Harus di Isi');
       }
       
       if($type == 1){
            redirect("mrp/request-pengadaan-atk");
        }elseif($type == 2){
            redirect("mrp/request-pengadaan-cetakan");
        }elseif($type == 3){
             redirect("mrp/mrp-request/request-pengadaan-komputer");
        }elseif($type == 4){
             redirect("mrp/mrp-request/request-pengadaan-technical");
        }elseif($type == 5){
             redirect("mrp/mrp-request/request-pengadaan-service");
        }elseif($type == 7){
             redirect("mrp/mrp-request/request-pengadaan-office");
        }elseif($type == 8){
             redirect("mrp/mrp-request/request-pengadaan-promosi");
        }elseif($type == 9){
             redirect("mrp/mrp-request/request-pengadaan-umum");
        }elseif($type == 10){
            redirect("mrp/mrp-request/request-pengadaan-cetakan-invoice");
        }elseif($type == 11){
            redirect("mrp/mrp-request/request-pengadaan-cetakan-rutin");
        }
      
       
  }
   function reject_request($type = 0){
      
      $pst = $this->input->post(NULL); 
//      print_r($pst);
//      die;
      $note_reject = $pst['note_reject'];
      if($note_reject){
          
      $now = date("Y-m-d H:i:s");
      $note_b =$this->global_models->get_field("mrp_request","note_warning",array("id_mrp_request" => "{$pst['id_request']}"));
      
      $kirim = array(
            "status"                    => 11,  
            "update_by_users"           => $this->session->userdata("id"),
            "note_warning"              => "(note reject {$now})".$note_reject."<br>".$note_b,
            "update_date"               => date("Y-m-d H:i:s")
        );
       $this->global_models->update("mrp_request", array("id_mrp_request" => $pst['id_request']),$kirim);
       $ro_kode =$this->global_models->get_field("mrp_request","code",array("id_mrp_request" => "{$pst['id_request']}"));
       $this->session->set_flashdata('success', "Request Orders dengan kode {$ro_kode} telah di reject");
    }else{
          $this->session->set_flashdata('notice', 'Note Harus di Isi');
      }
       if($type == 1){
           redirect("mrp/request-pengadaan-atk");
       }elseif($type == 2){
           redirect("mrp/request-pengadaan-cetakan");
       }elseif($type == 3){
           redirect("mrp/mrp-request/request-pengadaan-komputer");
       }elseif($type == 4){
           redirect("mrp/mrp-request/request-pengadaan-technical");
       }elseif($type == 5){
           redirect("mrp/mrp-request/request-pengadaan-service");
       }elseif($type == 7){
            redirect("mrp/mrp-request/request-pengadaan-office");
       }elseif($type == 8){
            redirect("mrp/mrp-request/request-pengadaan-promosi");
       }elseif($type == 9){
            redirect("mrp/mrp-request/request-pengadaan-umum");
       }elseif($type == 10){
           redirect("mrp/mrp-request/request-pengadaan-cetakan-invoice");
       }elseif($type == 11){
           redirect("mrp/mrp-request/request-pengadaan-cetakan-rutin");
       }
       
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
    
     $link = site_url('mrp/cancel-request/1');
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
            
    
    $this->template->build('main-mrp/request-pengadaan-atk', 
      array(
            'url'           => base_url()."themes/".DEFAULTTHEMES."/",
            'menu'          => "mrp/request-pengadaan-atk",
            'title'         => lang("Form Request Pengadaan ATK"),
            'foot'          => $foot,
            'css'           => $css,
            'before_table'  => $before_table,
//            'type'          => $type,
//            'jenis'         => $jenis,
//            'brand'         => $brand,
          ));
    $this->template
      ->set_layout('default')
      ->build('main-mrp/request-pengadaan-atk');
  }
  
  function request_pengadaan_cetakan(){
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
		 . ' "aaSorting": []'
        //  . '"order": [[ 0, "desc" ]],'
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
                
      . "$(document).on('click', '#id-customer-cancel', function(evt){"
        . "var id = $(this).attr('isi');"
        . "$('#id_request').val(id);"
      . "});"
	  . "</script>";   
    
        $link = site_url('mrp/cancel-request/2');
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
            'before_table'  => $before_table,
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
                                   
            . "$('#after-btn-save').click(function(){"
                . "$('#btn-save').hide();"
            . "$('#img-5').show();"
            . "var note2   = $('#note_cetakan').val();"
            . "var id_hr_pegawai2   = $('#id_users').val();"
            . "var id_receiver2     = $('#id_users_penerima').val();"                             
            . " var id_hr_pegawai = (isNaN(id_hr_pegawai2)) ? 0 : id_hr_pegawai2;"                                 
            
                                    
                        ."var dataString2 =  'note=' + note2 +'&id_receiver='+ id_receiver2 +'&id_hr_pegawai='+ id_hr_pegawai + '&id_mrp_request='+ {$id_mrp_request};"
                        ."$.ajax({"
                        ."type : 'POST',"
                        ."url : '".site_url("mrp/mrp-ajax/update-mrp-request-pengadaan/1")."',"
                        ."data: dataString2,"
                        ."dataType : 'html',"
                        ."success: function(data) {"
                            ."window.location ='{$url2}'"
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
                        ."url : '".site_url("mrp/mrp-ajax/update-form-mrp-request-pengadaan/2")."',"
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
      //list ATK
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

    $link = site_url('mrp/reject-request/1');
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
         
       $list = $this->global_models->get_query("SELECT A.id_mrp_request,A.code,A.status,A.note,A.create_by_users,A.id_hr_pegawai,A.user_pegawai_receiver,A.tanggal_diserahkan,A.note_mutasi,"
        . "B.id_users,C.name,C.email,D.title AS name_organisasi,E.id_hr_pegawai AS pegawai_receiver,F.name AS name_receiver,F.email AS email_receiver"
        . ",A.create_by_users"
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
    $created_by_users = $this->global_models->get_field("m_users", "name", array("id_users" => $list[0]->create_by_users));
    
    $totl =$this->global_models->get_query("SELECT COUNT(status_blast) AS total FROM mrp_request AS A"
            . " WHERE A.create_by_users='{$this->session->userdata("id")}' AND A.status_blast = 1 AND A.type_inventory = 2 ");
       
    
//        print $this->db->last_query();
//        die;
      $this->template->build("main-mrp/request-atk/view-request-atk", 
        array(
              'url'         => base_url()."themes/".DEFAULTTHEMES."/",
              'menu'        => 'mrp/request-pengadaan-atk',
              'title'       => lang("mrp_add_request_pengadaan_atk"),
              'list'        => $list,
              'before_table' => $before_table,
              'total'       => $totl,
              'breadcrumb'  => array(
                    "mrp_request_pengadaan_atk"  => "mrp/request-pengadaan-atk"
                ),
              'css'         => $css,
              'created_by_users'  => $created_by_users,
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
            "status_blast"              => 2,
            "update_by_users"           => $this->session->userdata("id"),
            "update_date"               => date("Y-m-d H:i:s")
        );
       $this->global_models->update("mrp_request", array("id_mrp_request" => $pst['id_detail']),$kirim);
       redirect("mrp/request-pengadaan-atk");
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
       
       $kirim1 = array(
            "id_mrp_request"                => $id_mrp_request,
            "type"                          => 2,
            "status"                        => 1,
            "create_by_users"               => $this->session->userdata("id"),
            "create_date"                   => date("Y-m-d H:i:s")
        );
       
        $this->global_models->insert("temp_alert_email", $kirim1);
     
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
//            . "alert('11');"
//            ."window.location ='{$url}'"
                . "$('#btn-task-orders').hide();"
                . "$('#img-5').show();"
            . "var note2   = $('#note_cetakan').val();"
            . "var id_hr_pegawai2   = $('#id_users').val();"
            . "var id_receiver2     = $('#id_users_penerima').val();"                 
            . " var id_hr_pegawai   = (isNaN(id_hr_pegawai2)) ? 0 : id_hr_pegawai2;"              
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
                        ."var dataString2 = 'id_spesifik='+ aData +'&jumlah='+ aData1+'&'+'&note=' + note2 +'&id_receiver='+ id_receiver2 +'&id_hr_pegawai='+id_hr_pegawai +'&id_mrp_request='+ {$id_mrp_request};"
//                        ."alert(dataString2);"
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
            . "var id_receiver2     = $('#id_users_penerima').val();"                             
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
                        ."var dataString2 = 'id_spesifik='+ aData +'&jumlah='+ aData1+'&note='+ note2 +'&id_receiver='+ id_receiver2 +'&id_hr_pegawai='+ id_hr_pegawai+'&id_mrp_request='+ {$id_mrp_request};"
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
            . "var id_hr_pegawai2   = $('#id_users').val();"
            . "var id_receiver2     = $('#id_users_penerima').val();"                             
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
                                    
                        ."var dataString2 = 'id_spesifik='+ aData +'&jumlah='+ aData1 + '&jumlah_rg=' + aData2 +'&note=' + note2 +'&id_receiver='+ id_receiver2 +'&id_hr_pegawai='+ id_hr_pegawai + '&id_mrp_request='+ {$id_mrp_request};"
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
            . "var id_hr_pegawai2   = $('#id_users').val();"
            . "var id_receiver2     = $('#id_users_penerima').val();"                             
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
                                    
                        ."var dataString2 = 'id_spesifik='+ aData +'&jumlah='+ aData1 + '&jumlah_rg='+ aData2 +'&id_receiver='+ id_receiver2 +'&id_hr_pegawai='+ id_hr_pegawai +'&note=' + note2+'&id_mrp_request='+ {$id_mrp_request};"
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
    
   ."});"                     
        . "});"
      . '$(function() {'
    . "$( '.date' ).datepicker({"
    . "showOtherMonths: true,"
    . "dateFormat: 'yy-mm-dd',"  
    . "selectOtherMonths: true,"  
    . "selectOtherYears: true,"
    . "});"                            
        . "$('#dt-cetakan').click(function(){"
        . 'var table = '
            . '$("#tableboxy2").dataTable({'
              . '"order": [[ 0, "asc" ]],'
              . '"bDestroy": true'
            . '});'
            . "table.fnClearTable();"
            . 'ambil_data2(table, 0);'
      
        . "});"
                                    
        . "$('#req-mutasi-stock2').click(function(){"
        . 'var table = '
            . '$("#tableboxy4").dataTable({'
              . '"order": [[ 0, "asc" ]],'
              . '"bDestroy": true'
            . '});'
            . "table.fnClearTable();"
            . 'ambil_data4(table, 0);'
      
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
        
//      $list = $this->global_models->get_query("SELECT A.id_mrp_request,A.status,A.note,A.create_by_users,A.id_hr_pegawai,"
//        . "B.id_users,C.name,C.email,D.title AS name_organisasi"
//        . " FROM mrp_request AS A"
//        . " LEFT JOIN hr_pegawai AS B ON A.id_hr_pegawai = B.id_hr_pegawai"
//        . " LEFT JOIN m_users AS C ON B.id_users = C.id_users"
//        . " LEFT JOIN hr_master_organisasi AS D ON B.id_hr_master_organisasi = D.id_hr_master_organisasi"
//        . " WHERE A.id_mrp_request='{$id_mrp_request}'"
//        );
        
    $link = site_url('mrp/reject-request/2');
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
            
         $list = $this->global_models->get_query("SELECT A.id_mrp_request,A.code,A.status,A.note,A.create_by_users,A.id_hr_pegawai,A.user_pegawai_receiver,A.tanggal_diserahkan,A.note_mutasi,"
        . "B.id_users,C.name,C.email,D.title AS name_organisasi,E.id_hr_pegawai AS pegawai_receiver,F.name AS name_receiver,F.email AS email_receiver"
        . ",A.create_by_users"
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
    $created_by_users = $this->global_models->get_field("m_users", "name", array("id_users" => $list[0]->create_by_users));        
//       print_r($list);
//       die;
        
    $totl =$this->global_models->get_query("SELECT COUNT(status_blast) AS total FROM mrp_request AS A"
            . " WHERE A.create_by_users='{$this->session->userdata("id")}' AND A.status_blast = 1 AND A.type_inventory = 1 ");
       
           
      $this->template->build("main-mrp/request-cetakan/view-request-cetakan", 
        array(
              'url'         => base_url()."themes/".DEFAULTTHEMES."/",
              'menu'        => 'mrp/request-pengadaan-cetakan',
              'title'       => lang("mrp_add_request_pengadaan_cetakan"),
              'id_mrp_request'  => $id_mrp_request,
              'list'        => $list,
              'total'       => $totl,
              'breadcrumb'  => array(
                    "mrp_request_pengadaan_cetakan"  => "mrp/request-pengadaan-cetakan"
                ),
              'css'         => $css,
              'created_by_users'  => $created_by_users,
              'before_table' => $before_table,
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
     
      if($pst['btn_closed'] == "closed"){
           $kirim = array(
            "status"                    => 9,  
            "update_by_users"           => $this->session->userdata("id"),
            "update_date"               => date("Y-m-d H:i:s")
        );
       $this->global_models->update("mrp_request", array("id_mrp_request" => $pst['id_detail']),$kirim);
       redirect("mrp/request-pengadaan-cetakan");
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
      redirect("mrp/request-pengadaan-cetakan");
    }
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
              'menu'                => 'mrp/mrp-po/list-po',
              'title'               => lang("payment_request"),
              'id_po'           => $id_mrp_po,
              'list'            => $list,
//              'id_mrp_task_orders'  => $id_mrp_task_orders,
//              'id_mrp_po'           => $id_mrp_po,
//              'dt_status'           => $status,
              'breadcrumb'  => array(
                    "mrp_list_po"  => "mrp/mrp-po/list-po"
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
      redirect("mrp/mrp-po/list-po");
    }
   
  }
  
  function preview($id_mrp_task_orders = 0,$id_mrp_po = 0){
   
      $detail = $this->global_models->get_query("SELECT A.id_mrp_po,A.ppn,A.discount,A.frm,A.no_po,A.user_approval,A.note,A.tanggal_dikirim,A.tanggal_po"
      . ",B.office,B.title AS company,B.address AS address_company"
      . ",C.name AS nama_supplier,C.pic,C.phone,C.address AS address_supplier"
      . ",D.name AS nama_user,E.signature,A.flag_desimal"
      . " FROM mrp_po AS A"
      . " LEFT JOIN hr_company AS B ON A.id_hr_company = B.id_hr_company"
      . " LEFT JOIN mrp_supplier AS C ON A.id_mrp_supplier = C.id_mrp_supplier"
      . " LEFT JOIN m_users AS D ON A.user_approval = D.id_users"
      . " LEFT JOIN hr_pegawai AS E ON A.user_approval = E.id_users"  
      . " WHERE A.id_mrp_po='{$id_mrp_po}'");
      
//      print_r($detail);
//      DIE;
      $where = "WHERE A.status >= 4 AND A.id_mrp_task_orders = '{$id_mrp_task_orders}' AND A.id_mrp_po = '{$id_mrp_po}'  ";
      $list = $data = $this->global_models->get_query("SELECT A.id_mrp_task_orders_request_asset,A.jumlah,A.catatan,A.note,C.name AS nama_barang,E.title AS satuan"
            . ",B.title AS title_spesifik,F.harga,D.title AS name_brand,A.id_mrp_task_orders_request_asset,E.group_satuan,A.harga AS harga_task_order_request"
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
       'note'         => nl2br(htmlspecialchars($detail[0]->note))
       
    );
    $this->load->view('main-mrp/print-preview', $data);
  
  }
  
    function preview2($id_mrp_request = 0,$id_mrp_task_orders = 0){
      
        if($this->session->userdata("add_to_search_no_po_{$id_mrp_task_orders}")){
            $id_po = " AND E.id_mrp_po ={$this->session->userdata("add_to_search_no_po_{$id_mrp_task_orders}")}";
            $id_mrp_po = " AND I.id_mrp_po ={$this->session->userdata("add_to_search_no_po_{$id_mrp_task_orders}")}";
            }else{
                $id_mrp_po = "";
            $id_po = "";
        }
      $detail = $this->global_models->get_query(
      "SELECT A.id_mrp_request,H.user_approval,E.id_mrp_po,E.no_po,E.flag_desimal"
      . ",F.office,F.title AS company,F.address AS address_company"
      . ",G.name AS nama_supplier,G.id_mrp_supplier,G.pic,G.phone,G.address AS address_supplier"
      . ",H.code AS code_request,H.type_inventory,I.name AS nama_user,J.signature,L.name AS users,M.title AS organisasi"
      . ",O.name AS pegawai_receiver,P.title AS organisasi_receiver,E.tanggal_po,H.note,Q.iso,M.title AS struktural"
      . " FROM mrp_request_asset AS A"
      . " LEFT JOIN mrp_task_orders_request_asset AS C ON A.id_mrp_inventory_spesifik = C.id_mrp_inventory_spesifik"
      . " LEFT JOIN mrp_po_asset AS D ON C.id_mrp_task_orders_request_asset = D.id_mrp_task_orders_request_asset"
      . " LEFT JOIN mrp_po AS E ON D.id_mrp_po = E.id_mrp_po"
      . " LEFT JOIN hr_company AS F ON E.id_hr_company = F.id_hr_company"
      . " LEFT JOIN mrp_supplier AS G ON D.id_mrp_supplier = G.id_mrp_supplier"
      . " LEFT JOIN mrp_request AS H ON A.id_mrp_request = H.id_mrp_request"
      . " LEFT JOIN m_users AS I ON H.user_approval = I.id_users"
      . " LEFT JOIN hr_pegawai AS J ON H.user_approval = J.id_users"
      . " LEFT JOIN hr_pegawai AS K ON H.id_hr_pegawai = K.id_hr_pegawai"
      . " LEFT JOIN m_users AS L ON K.id_users = L.id_users"
      . " LEFT JOIN hr_master_organisasi AS M ON K.id_hr_master_organisasi = M.id_hr_master_organisasi"
      . " LEFT JOIN hr_pegawai AS N ON H.user_pegawai_receiver = N.id_hr_pegawai"
      . " LEFT JOIN m_users AS O ON N.id_users = O.id_users"
      . " LEFT JOIN hr_master_organisasi AS P ON N.id_hr_master_organisasi = P.id_hr_master_organisasi"
      . " LEFT JOIN mrp_type_inventory AS Q ON H.type_inventory = Q.id_mrp_type_inventory"        
      . " WHERE C.id_mrp_task_orders='{$id_mrp_task_orders}' AND A.id_mrp_request = '{$id_mrp_request}' {$id_po}"
      . " GROUP BY A.id_mrp_request_asset");
            
//      print "<pre>";
//      print $this->db->last_query();
//      print_r($detail);
//      print "</pre>";
//      die;
      
     $where = "WHERE A.status >= 1 $id_mrp_po AND A.id_mrp_task_orders = '{$id_mrp_task_orders}' AND H.id_mrp_request = '{$id_mrp_request}'  ";
      $list = $data = $this->global_models->get_query("SELECT A.id_mrp_task_orders_request_asset,H.jumlah,A.note,C.name AS nama_barang,E.title AS satuan"
            . ",B.title AS title_spesifik,I.harga,D.title AS name_brand,A.id_mrp_task_orders_request_asset,E.group_satuan,I.harga AS harga_task_order_request"
              . ",E.nilai,I.id_mrp_po"
        . " FROM mrp_task_orders_request_asset AS A"
        . " LEFT JOIN mrp_inventory_spesifik AS B ON A.id_mrp_inventory_spesifik = B.id_mrp_inventory_spesifik"
        . " LEFT JOIN mrp_inventory_umum AS C ON B.id_mrp_inventory_umum = C.id_mrp_inventory_umum"
        . " LEFT JOIN mrp_brand AS D ON B.id_mrp_brand = D.id_mrp_brand"
        . " LEFT JOIN mrp_satuan AS E ON A.id_mrp_satuan = E.id_mrp_satuan"
        . " LEFT JOIN mrp_supplier_inventory AS F ON B.id_mrp_inventory_spesifik = (F.id_mrp_inventory_spesifik AND F.id_mrp_supplier = '{$detail[0]->id_mrp_supplier}')"
        . " LEFT JOIN mrp_supplier G ON F.id_mrp_supplier = G.id_mrp_supplier "
        . " LEFT JOIN mrp_po_asset AS I ON A.id_mrp_task_orders_request_asset = I.id_mrp_task_orders_request_asset"
        . " LEFT JOIN mrp_request_asset AS H ON A.id_mrp_inventory_spesifik = H.id_mrp_inventory_spesifik"
        . " {$where}"
        . " GROUP BY H.id_mrp_request_asset"
        . " ORDER BY CONCAT(C.name,B.title) ASC"
        );
      
//        print $this->db->last_query();
//        die;
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
  
   function po_pdf($id_mrp_task_orders = 0,$id_mrp_po = 0){
   
      $detail = $this->global_models->get_query("SELECT A.id_mrp_po,A.frm,A.no_po,A.user_approval,A.note,A.tanggal_dikirim"
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
                . "<div style='font-size: 12px; margin-top: 10px'>".$detail[0]->frm."<br>Kepada Yth,<br></span><span style='font-size: 12px;'>{$supplier}<br><br>"
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
//           . '"order": [[ 0, "desc" ]],'
			. ' "aaSorting": []'  
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
  
    function list_rg_khusus($id_mrp_po){

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
//           . '"order": [[ 0, "desc" ]],'
		. ' "aaSorting": []'
        . '});'
      
        . 'ambil_data(table, 0);'
      
      . '});'
      
      . 'function ambil_data(table, mulai){'
        . '$.post("'.site_url("mrp/mrp-ajax/get-mrp-list-rg-khusus").'/"+mulai, function(data){'
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
  
    $this->template->build('main-mrp/rg-khusus/list-rg', 
      array(
            'url'           => base_url()."themes/".DEFAULTTHEMES."/",
            'menu'          => "mrp/list-rg-khusus",
            'title'         => lang("RG Khusus"),
            'foot'          => $foot,
            'css'           => $css,
          ));
    $this->template
      ->set_layout('default')
      ->build('main-mrp/rg-khusus/list-rg');
  }
  
    function list_rg_department($id_mrp_po = 0,$id_mrp_task_orders = 0){

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
        . '$.post("'.site_url("mrp/mrp-ajax/get-mrp-list-rg-department/{$id_mrp_po}/{$id_mrp_task_orders}").'/"+mulai, function(data){'
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
            'menu'          => "mrp/list-rg-dept",
            'title'         => lang("Receiving Goods"),
            'foot'          => $foot,
            'css'           => $css,
            'breadcrumb'  => array(
                    "RG Department"  => "mrp/list-rg-dept"
                ),
          ));
    $this->template
      ->set_layout('default')
      ->build('main-mrp/list-rg-depatment');
  }
  
     function list_rg_dept(){

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
        . '$.post("'.site_url("mrp/mrp-ajax/get-mrp-list-rg-dept").'/"+mulai, function(data){'
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
  
    $this->template->build('main-mrp/list-rg-dept', 
      array(
            'url'           => base_url()."themes/".DEFAULTTHEMES."/",
            'menu'          => "mrp/list-rg",
            'title'         => lang("Receiving Goods"),
            'foot'          => $foot,
            'css'           => $css,
          ));
    $this->template
      ->set_layout('default')
      ->build('main-mrp/list-rg-dept');
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
//        $jml_stock_out = $this->global_models->get_field("mrp_stock_out","SUM(jumlah)", array("id_mrp_stock" => 1, "id_mrp_stock_in" => 1));
//        print $this->db->last_query();
//        die;
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
              . "$(document).on('click', '#id-rg-cancel', function(evt){"
                . "var id = $(this).attr('isi');"
                . "$('#kode_mrp_rg').val(id);"
              . "});"
              . "$(document).on('click', '#btn-delete', function(evt){"
                . "var dkode =$('#kode_mrp_rg').val();"
               
                . "var note =$('#note_delete').val();"
                . "$('.btn-rg-hide').hide();"
            
                ."var dataString2 = 'note='+ note+'&kode='+dkode;"
                
                ."$.ajax({"
                ."type : 'GET',"
                ."url : '".site_url("mrp/mrp-ajax/cancel-rg/")."',"
                ."data: dataString2,"
                ."dataType : 'html',"
                ."success: function(data) {"
//                    . 'var hasil = $.parseJSON(data);'
//                    . "if(hasil.progress == 100){"
//                        ."$('#dta-bar-history').hide();"
//                        . "$('#bar_proses_history').width(hasil.progress+'%');"
//                        . "$('#no_proses_history').text(hasil.progress);"
//                        . "$('#show-bars-history').text(hasil.progress);"
//                        ."window.location ='{$url2}'"
//                    . "}else{"
//                        . "proses_data({$id_mrp_receiving_goods_po},hasil.id_rg, hasil.number);"
//                        . "$('#bar_proses_history').width(hasil.progress+'%');"
//                        . "$('#no_proses_history').text(hasil.progress);"
//                        . "$('#show-bars-history').text(hasil.progress);"        
//                    . "}"
                ."},"
             ."});"
              . "});"  
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
            ."$('#dta-bar').show();"
            ."$('#loader-page2').show();"  
            . "var oTable = $('#tableboxy').dataTable();"
            . "var aData = [];"
            . "var rowcollection =  oTable.$('.rg', {'page': 'all'});"
            . "rowcollection.each(function(index,elem){"
//             . "var value_rg = parseInt($(elem).val());"
            . " var value_rg = parseFloat($(elem).val()).toFixed(2);"
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
                    . "if(hasil.progress == 100){"
                        ."$('#dta-bar').hide();"
                        . "$('#bar_proses').width(hasil.progress+'%');"
                        . "$('#no_proses').text(hasil.progress);"
                        . "$('#show-bars').text(hasil.progress);"
                        ."window.location ='{$url2}'"
                    . "}else{"
                        . "proses_data({$id_mrp_receiving_goods_po},hasil.id_rg, hasil.number);"
                        . "$('#bar_proses').width(hasil.progress+'%');"
                        . "$('#no_proses').text(hasil.progress);"
                        . "$('#show-bars').text(hasil.progress);"        
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
     //tab RG
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
                                         
    . "function proses_data(id_mrp_receiving_goods_po,id_rg,number){"
        . '$.post("'.site_url("mrp/mrp-ajax/set-rg/").'/"+id_mrp_receiving_goods_po+"/"+id_rg+"/"+number, function(data){'
          . 'var hasil = $.parseJSON(data);'
          . 'if(hasil.progress == 100){'
             ."$('#dta-bar').hide();"
             . "$('#bar_proses').width(hasil.progress+'%');"
             . "$('#no_proses').text(hasil.progress);"
             . "$('#show-bars').text(hasil.progress);"
            ."window.location ='{$url2}'"
             . '}'
          . 'else{'
             . 'proses_data(hasil.id_mrp_receiving_goods_po,hasil.id_rg, hasil.number);'
             . "$('#bar_proses').width(hasil.progress+'%');"
             . "$('#no_proses').text(hasil.progress);"
             . "$('#show-bars').text(hasil.progress);"       
//            . '$("#loader-page").hide();'
//            . 'load_tooltip();'
//            . 'load_tooltip_harga();'
//            . "$('#script-tambahan').html(hasil.dt_total);"
          . '}'
        . '});'
    . "}"
               
      //tab History RG
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
                
      . "function coba_data(id_mrp_receiving_goods_po_asset,id_mrp_inventory_spesifik){"
                    . 'var table = '
                    . '$("#rg-department").dataTable({'
                    . '"order": [[ 0, "asc" ]],'
                    . '"bDestroy": true'
                    . '});'
                    . "table.fnClearTable();"
                    . 'ambil_data5(table, 0,id_mrp_receiving_goods_po_asset,id_mrp_inventory_spesifik);'
            . "}"
                //detail rg department
                . 'function ambil_data5(table, mulai,id_mrp_receiving_goods_po_asset,id_mrp_inventory_spesifik){'
                . '$.post("'.site_url("mrp/mrp-ajax/get-rg-detail-rg-department").'/"+id_mrp_receiving_goods_po_asset+"/"+id_mrp_inventory_spesifik+"/"+mulai, function(data){'
                  . '$("#load-rg-department").show();'
                  . 'var hasil = $.parseJSON(data);'
                  . 'if(hasil.status == 2){'
                    . 'table.fnAddData(hasil.hasil);'
                    . 'ambil_data5(table, hasil.start,id_mrp_receiving_goods_po_asset,id_mrp_inventory_spesifik);'
                  . '}'
                  . 'else{'
                    . '$("#load-rg-department").hide();'
                  . '}'
                . '});'
              . '}'
                . 'function closedWin() {'
                . 'confirm("close ?");'
                . 'return false; /* which will not allow to close the window */'
                . '}'
                
      . "</script>";
        
//     $link = site_url('mrp/cancel-request/1');
     $before_table = "<div class='modal fade' id='edit-keterangan-cancel' tabindex='-1' role='dialog' aria-hidden='true'>
    <div class='modal-dialog'>
        <div class='modal-content'>
            <div class='modal-header'>
                <button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
                <h4 class='modal-title'>Note Delete</h4>
            </div>
            <form action='#' method='post'>
                <div class='modal-body'>
                    <div class='form-group'>
                        <div class='input-group'>
<!--                            <span class='input-group-addon'>Note Cancel:</span>-->
                            <input name='kode_mrp_rg' class='form-control' id='kode_mrp_rg' style='display:none'>
                            <textarea name='note_delete' id='note_delete' placeholder='Note Delete' style='margin: 0px; width: 553px; height: 227px;'></textarea>
                        </div>
                    </div>
                </div>
                <div class='modal-footer clearfix'>

                    <button type='button' class='btn btn-danger' data-dismiss='modal'><i class='fa fa-times'></i> Cancel</button>

                    <button type='button' id='btn-delete' data-dismiss='modal' class='btn btn-primary pull-left'> Proses</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>";


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
              'before_table'       => $before_table,
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
   
      function rg_khusus($id_mrp_receiving_goods_po) {
//        $jml_stock_out = $this->global_models->get_field("mrp_stock_out","SUM(jumlah)", array("id_mrp_stock" => 1, "id_mrp_stock_in" => 1));
//        print $this->db->last_query();
//        die;
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
      $url = base_url('mrp/list-rg-khusus');
      $url2 = base_url("mrp/rg-khusus/{$id_mrp_receiving_goods_po}");
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
//             . "var value_rg = parseInt($(elem).val());"
			. " var value_rg = parseFloat($(elem).val()).toFixed(2);"
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
                ."url : '".site_url("mrp/mrp-ajax/update-rg-khusus/{$id_mrp_receiving_goods_po}")."',"
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
        . '$.post("'.site_url("mrp/mrp-ajax/get-rg-khusus/{$id_mrp_receiving_goods_po}").'/"+mulai, function(data){'
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
        . '$.post("'.site_url("mrp/mrp-ajax/get-mrp-detail-rg-khusus/{$id_mrp_receiving_goods_po}").'/"+mulai, function(data){'
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
              'menu'                => 'mrp/list-rg-khusus',
              'title'               => lang("RG Khusus"),
              'list'                => $list,
              'id_mrp_task_orders'  => $id_mrp_task_orders,
              'id_mrp_po'           => $id_mrp_po,
              'dt_status'              => $status,
              'breadcrumb'  => array(
                    "mrp_list_rg_khusus"  => "mrp/list-rg-khusus"
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
       $dt_po = $this->global_models->get("mrp_receiving_goods_po",array("id_mrp_receiving_goods_po" => "{$id_mrp_receiving_goods_po}"));
    $css = "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/datatables/dataTables.bootstrap.css' rel='stylesheet' type='text/css' />"
      . "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/jQueryUI/jquery-ui-1.10.3.custom.min.css' rel='stylesheet' type='text/css' />";
      $url = base_url("mrp/list-rg-department/{$dt_po[0]->id_mrp_po}/{$dt_po[0]->id_mrp_task_orders}");
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
            ."$('#dta-bar').show();"
            ."$('#btn-cancel').hide();"
            ."$('#loader-page2').show();" 
            . "var oTable = $('#tableboxy').dataTable();"
            . "var aData = [];"
            . "var rowcollection =  oTable.$('.rg', {'page': 'all'});"
            . "rowcollection.each(function(index,elem){"
//             . "var value_rg = parseInt($(elem).val());"
			. " var value_rg = parseFloat($(elem).val()).toFixed(2);"
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
            
//            . "alert(aData3);"
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
                ."url : '".site_url("mrp/mrp-ajax/update-rg-department/{$id_mrp_receiving_goods_po}/{$id_mrp_request}")."',"
                ."data: dataString2,"
                ."dataType : 'html',"
                ."success: function(data) {"
                    . 'var hasil = $.parseJSON(data);'
                    . "if(hasil.progress == 100){"
                        ."$('#dta-bar').hide();"
                        . "$('#bar_proses').width(hasil.progress+'%');"
                        . "$('#no_proses').text(hasil.progress);"
                        . "$('#show-bars').text(hasil.progress);"
                        ."window.location ='{$url2}'"
                    . "}else{"
                        . "proses_data({$id_mrp_receiving_goods_po},{$id_mrp_request},hasil.id_rg,hasil.number);"
                        . "$('#bar_proses').width(hasil.progress+'%');"
                        . "$('#no_proses').text(hasil.progress);"
                        . "$('#show-bars').text(hasil.progress);"   
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
        . 'ambil_data(table, 0);'
      . '});'
                                
      . "function proses_data(id_mrp_receiving_goods_po,id_request,id_rg,number){"
        . '$.post("'.site_url("mrp/mrp-ajax/set-rg-department/").'/"+id_mrp_receiving_goods_po+"/"+id_request+"/"+id_rg+"/"+number, function(data){'
          . 'var hasil = $.parseJSON(data);'
          . 'if(hasil.progress == 100){'
             ."$('#dta-bar').hide();"
             . "$('#bar_proses').width(hasil.progress+'%');"
             . "$('#no_proses').text(hasil.progress);"
             . "$('#show-bars').text(hasil.progress);"
            ."window.location ='{$url2}'"
             . '}'
          . 'else{'
             . 'proses_data(hasil.id_mrp_receiving_goods_po,hasil.id_mrp_request,hasil.id_rg,hasil.number);'
             . "$('#bar_proses').width(hasil.progress+'%');"
             . "$('#no_proses').text(hasil.progress);"
             . "$('#show-bars').text(hasil.progress);"       
//            . '$("#loader-page").hide();'
//            . 'load_tooltip();'
//            . 'load_tooltip_harga();'
//            . "$('#script-tambahan').html(hasil.dt_total);"
          . '}'
        . '});'
    . "}"
                    
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
        . '$.post("'.site_url("mrp/mrp-ajax/get-mrp-detail-rg-department/{$id_mrp_receiving_goods_po}/{$id_mrp_request}").'/"+mulai, function(data){'
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
    
    $request = $this->global_models->get_query("SELECT C.name,D.title,A.code,A.id_mrp_request,A.type_inventory FROM mrp_request AS A"
            . " LEFT JOIN hr_pegawai AS B ON A.id_hr_pegawai = B.id_hr_pegawai"
            . " LEFT JOIN m_users AS C ON B.id_users = C.id_users"
            . " LEFT JOIN hr_master_organisasi AS D ON B.id_hr_master_organisasi = D.id_hr_master_organisasi"
            . " WHERE id_mrp_request='{$id_mrp_request}'");  
            
    $this->template->build("main-mrp/rg-department/view-rg-department", 
        array(
              'url'                 => base_url()."themes/".DEFAULTTHEMES."/",
              'menu'                => 'mrp/list-rg-dept',
              'title'               => lang("Receiving Goods"),
              'list'                => $list,
              'id_mrp_task_orders'  => $dt_po[0]->id_mrp_task_orders,
              'id_mrp_po'           => $dt_po[0]->id_mrp_po,
              'dt_status'              => $status,
              "request"				=> $request,
              'breadcrumb'  => array(
                    "mrp_list_rg_department"  => "mrp/list-rg-department/{$dt_po[0]->id_mrp_po}/{$dt_po[0]->id_mrp_task_orders}"
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
//             . "var value_rg = parseInt($(elem).val());"
			. " var value_rg = parseFloat($(elem).val()).toFixed(2);"
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


  
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */