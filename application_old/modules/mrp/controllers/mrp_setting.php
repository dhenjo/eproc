<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mrp_setting extends MX_Controller {
    
  function __construct() {      
    $this->menu = $this->cek();
  }

  function harga_atk(){
    
//    $list = $this->global_models->get("hr_company");
     $list = $this->global_models->get_query("SELECT A.id_mrp_setting_harga_atk,A.update_date,B.name"
    . " FROM mrp_setting_harga_atk AS A"
    . " LEFT JOIN mrp_supplier AS B ON A.id_mrp_supplier = B.id_mrp_supplier"
    );
     
     if($list[0]->id_mrp_setting_harga_atk < 1){
    $menutable = '
      <li><a href="'.site_url("mrp/mrp-setting/add-harga-atk").'"><i class="icon-plus"></i>Add New</a></li>
      ';
     }
     
    $this->template->build('mrp-setting/harga-atk', 
      array(
            'url'         => base_url()."themes/".DEFAULTTHEMES."/",
            'menu'        => "mrp/mrp-setting/harga-atk",
            'data'        => $list,
            'title'       => lang("Setting Harga ATK"),
            'menutable'   => $menutable,
          ));
    $this->template
      ->set_layout('datatables')
      ->build('mrp-setting/harga-atk');
  }
  
    public function add_setting_users_request($id_mrp_setting_users = 0){
//    print "aa"; die;
        
    $dropdown_department = $this->global_models->get_dropdown("hr_department", "id_hr_department", "title", TRUE);     
    
    $dropdown_company = $this->global_models->get_dropdown("hr_company", "id_hr_company", "title", TRUE); 
    
    $dropdown_user = $this->global_models->get_dropdown("m_users", "id_users", "name", TRUE); 
  
    
    if(!$this->input->post(NULL)){
 $data = $this->global_models->get_query("SELECT A.id_users,A.id_mrp_setting_users,A.title,B.name,B.email,D.title AS title_organisasi,E.code AS title_company"
        . " FROM mrp_setting_users AS A"
        . " LEFT JOIN m_users AS B ON A.id_users = B.id_users"
        . " LEFT JOIN hr_pegawai AS C ON A.id_users = C.id_users"
        . " LEFT JOIN hr_master_organisasi AS D ON C.id_hr_master_organisasi = D.id_hr_master_organisasi"
        . " LEFT JOIN hr_company AS E ON C.id_hr_company = E.id_hr_company"
        . " WHERE A.id_mrp_setting_users='{$id_mrp_setting_users}'"
        );
        $css = "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/select2.css' type='text/css' rel='stylesheet'>";
      $foot = "<script type='text/javascript' src='".base_url()."themes/".DEFAULTTHEMES."/js/select2.js'></script>";
      $foot .= "<script>"
//             
              . "$(function() {"
              
                . "$( '#users' ).autocomplete({"
                  . "source: '".site_url("mrp/mrp-setting-ajax/get-users")."',"
                  . "minLength: 1,"
                  . "search  : function(){ $(this).addClass('working');},"
                  . "open    : function(){ $(this).removeClass('working');},"
                  . "select: function( event, ui ) {"
                    . "$('#id_users').val(ui.item.id);"
                  . "}"
                . "});"
            . "});"
              
               . "$(function() {"
            . "$( '#akses_users' ).autocomplete({"
              . "source: '".site_url("mrp/mrp-setting-ajax/get-users")."',"
              . "minLength: 1,"
              . "search  : function(){ $(this).addClass('working');},"
              . "open    : function(){ $(this).removeClass('working');},"
              . "select: function( event, ui ) {"
                . "$('#id_akses_users').val(ui.item.id);"
              . "}"
            . "});"
            . "$(document).on('click', '.delete', function(evt){"
              . "var didelete = $(this).attr('isi');"
              . "$('#'+didelete).remove();"
            . "});"
            . "$(document).on('click', '#add-row', function(evt){"
//              . "alert($('#nomor').val());"
              . "$.post('".site_url("mrp/mrp-setting-ajax/add-row-setting-users")."',{no: $('#nomor').val()},function(data){"
//                . "$('#wadah').insertBefore(data);"
                . "$(data).insertBefore('#wadah');"
                . "var t = ($('#nomor').val() * 1) + 1;"
                . "$('#nomor').val(t);"
              . "});"
            . "});"
          . "});"
              
      ."</script>";
      
      $detail2 = $this->global_models->get_query("SELECT A.id_users,A.id_mrp_setting_users,B.name,B.email,D.title AS title_organisasi,E.code AS title_company"
        . " FROM mrp_setting_users_request AS A"
        . " LEFT JOIN m_users AS B ON A.id_users = B.id_users"
        . " LEFT JOIN hr_pegawai AS C ON A.id_users = C.id_users"
        . " LEFT JOIN hr_master_organisasi AS D ON C.id_hr_master_organisasi = D.id_hr_master_organisasi"
        . " LEFT JOIN hr_company AS E ON C.id_hr_company = E.id_hr_company"
        . " WHERE A.id_mrp_setting_users = '{$id_mrp_setting_users}' ");
//        print "<pre>";
//        print_r($detail2);
//        print "</pre>";
//        die;
      foreach ($detail2 AS $key => $det){
        $hasil .= "<div class='input-group margin' id='akses-users-box2{$key}'>"
            . "<input type='text' class='form-control' value='{$det->name}<{$det->email}><{$det->title_organisasi}><{$det->title_company}>' id='akses-users-2-{$key}' name='akses_users[]'>"
            . "<input type='text' class='form-control' value='{$det->id_users}' id='id_akses-users-2-{$key}' name='id_akses_users[]' style='display: none'>"
            . "<span class='input-group-btn'>"
              . "<a href='javascript:void(0)' class='btn btn-danger btn-flat delete' isi='akses-users-box2{$key}'>"
                . "<i class='fa fa-fw fa-times'></i>"
              . "</a>"
            . "</span> "
          . "</div>";
        $foot .= "<script>"
              . "$(function() {"
                . "$( '#akses-users-2-{$key}' ).autocomplete({"
                  . "source: '".site_url("mrp/mrp-setting-ajax/add-row-setting-users")."',"
                  . "minLength: 1,"
                  . "search  : function(){ $(this).addClass('working');},"
                  . "open    : function(){ $(this).removeClass('working');},"
                  . "select: function( event, ui ) {"
                    . "$('#id_akses-users-2-{$key}').val(ui.item.id);"
                  . "}"
                . "});"
              . "});"
          . "</script>";
      }   
//        print $hasil; die;
      $this->template->build("mrp-setting/add-setting-users-request", 
        array(
              'url'         => base_url()."themes/".DEFAULTTHEMES."/",
              'menu'        => 'mrp/mrp-setting/setting-users-request',
              'title'       => lang("Add Setting Users Request"),
              'detail'        => $data,
              'detail2'      => count($detail2),
              'users'       => $dropdown_user,
              'hasil'       => $hasil,
              'breadcrumb'  => array(
                    "setting_users_request"  => "mrp/mrp-setting/setting-users-request"
                ),
              'css'         => $css,
              'foot'        => $foot
            ));
      $this->template
        ->set_layout('form')
        ->build("mrp-setting/add-setting-users-request");
    }
    else{
      $pst = $this->input->post(NULL);
//      print "<pre>";
//      print_r($pst);
//      print "</pre>";
//      
//      die;
     
        if($pst['id_detail']){
               $kirim = array(
                "title"                       => $pst['title'],
                "id_users"                    => $pst['id_users'],
                "update_by_users"           => $this->session->userdata("id"),
                "update_date"               => date("Y-m-d H:i:s")
            );
           $this->global_models->update("mrp_setting_users", array("id_mrp_setting_users" => $pst['id_detail']),$kirim);
           
           $this->global_models->delete("mrp_setting_users_request", array("id_mrp_setting_users" => $pst['id_detail']));
        foreach ($pst['id_akses_users'] as $value) {
        if($value){
          $kirim = array(
            "id_mrp_setting_users"          => $id_mrp_setting_users,
            "id_users"                      => $value,
            "create_by_users"               => $this->session->userdata("id"),
            "create_date"                   => date("Y-m-d H:i:s"),
          );
          $this->global_models->insert("mrp_setting_users_request", $kirim);
        }
      }
        }
        else{
          $kirim = array(
              "title"                       => $pst['title'],
              "id_users"                    => $pst['id_users'],
              "create_by_users"             => $this->session->userdata("id"),
              "create_date"                 => date("Y-m-d H:i:s")
          );
          $id_mrp_setting_users = $this->global_models->insert("mrp_setting_users", $kirim);
          
      foreach ($pst['id_akses_users'] as $value) {
        if($value){
          $kirim = array(
            "id_mrp_setting_users"          => $id_mrp_setting_users,
            "id_users"                      => $value,
            "create_by_users"               => $this->session->userdata("id"),
            "create_date"                   => date("Y-m-d H:i:s"),
          );
          $this->global_models->insert("mrp_setting_users_request", $kirim);
        }
      }
//      if($this->global_models->insert_batch("mrp_setting_users_request", $kirim)){
//            $this->session->set_flashdata('success', 'Data tersimpan');
//      }
        
     }
        
        if($id_mrp_setting_users){
        $this->session->set_flashdata('success', 'Data tersimpan');
      }
      else{
        $this->session->set_flashdata('notice', 'Data tidak tersimpan');
      }
     redirect("mrp/mrp-setting/setting-users-request");
    }
  }
  
  function setting_users_request() {
      
      $css = "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/datatables/dataTables.bootstrap.css' rel='stylesheet' type='text/css' />"
      . "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/jQueryUI/jquery-ui-1.10.3.custom.min.css' rel='stylesheet' type='text/css' />"
      ;
    
    $foot = ""
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/datatables/jquery.dataTables.js' type='text/javascript'></script>"
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/datatables/dataTables.bootstrap.js' type='text/javascript'></script>"
     ;
	  
    $foot .= "<script>"
        . '$(function() {'
        . 'var table = '
        . '$("#tableboxy").dataTable({'
        . '"order": [[ 0, "desc" ]],'
//          . "'iDisplayLength': 20"
        . '});'
        . "ambil_data(table, 0);"
      . '});'
      
      . 'function ambil_data(table, mulai){'
        . '$.post("'.site_url("mrp/mrp-setting-ajax/get-setting-users-request").'/"+mulai, function(data){'
          . '$("#loader-page").show();'
          . 'var hasil = $.parseJSON(data);'
          . 'if(hasil.status == 2){'
                . 'if(hasil.flag == 2){'
                . '$("#loader-page").hide();'
                . '}'
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
    

//     $pst = $this->input->post(NULL);
//      if($pst){
//
//    }
    
    $this->template->build('mrp-setting/setting-users-request', 
      array(
            'url'           => base_url()."themes/".DEFAULTTHEMES."/",
            'menu'          => "mrp/mrp-setting/setting-users-request",
            'title'         => lang("Setting Users Request"),
            'foot'          => $foot,
            'css'           => $css,
//            'type'          => $type,
//            'jenis'         => $jenis,
//            'brand'         => $brand,
          ));
    $this->template
      ->set_layout('default')
      ->build('mrp-setting/setting-users-request');
  }
  
    public function add_harga_atk($id_harga_atk = 0){
    
//    $dropdown = $this->global_models->get_dropdown("master_department", "id_master_department", "title", TRUE);     
  
    if(!$this->input->post(NULL)){
      $detail =  $list = $this->global_models->get_query("SELECT A.id_mrp_setting_harga_atk,A.id_mrp_supplier,A.update_date,B.name"
    . " FROM mrp_setting_harga_atk AS A"
    . " LEFT JOIN mrp_supplier AS B ON A.id_mrp_supplier = B.id_mrp_supplier"
    );

       
    $foot = ""
     
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/jquery.ui.autocomplete.min.js' type='text/javascript'></script>";
	
    $foot .= "<script>"
                               
      . '$(function() {'
            . "$( '#supplier' ).autocomplete({"
             . "source: '".site_url("mrp/mrp-setting-ajax/get-supplier")."',"
             . "minLength: 1,"
             . "search  : function(){ $(this).addClass('working');},"
             . "open    : function(){ $(this).removeClass('working');},"
             . "select: function( event, ui ) {"
               . "$('#id_supplier').val(ui.item.id);"
             . "}"
           . "});"
       . "});"
            
	  . "</script>"; 
      ;
      
      $this->template->build("mrp-setting/add-harga-atk", 
        array(
              'url'         => base_url()."themes/".DEFAULTTHEMES."/",
              'menu'        => 'mrp/mrp-setting/add-harga-atk',
              'title'       => lang("Master Add Company"),
              'detail'      => $detail,
              'dropdown'    => $dropdown,
              'breadcrumb'  => array(
                    "master_company"  => "mrp/mrp-setting/add-harga-atk"
                ),
//              'css'         => $css,
              'foot'        => $foot
            ));
      $this->template
        ->set_layout('form')
        ->build("mrp-setting/add-harga-atk");
    }
    else{
      $pst = $this->input->post(NULL);
    
      if($pst['id_detail']){
        $kirim = array(
            "id_mrp_supplier"           => $pst['id_supplier'],
            "update_by_users"           => $this->session->userdata("id"),
            "update_date"                 => date("Y-m-d H:i:s")
        );
        
       $mrp_setting_harga =  $this->global_models->update_duplicate("mrp_setting_harga_atk", array("id_mrp_setting_harga_atk" => 1),$kirim);
      
      }
      else{
        $kirim = array(
            "id_mrp_setting_harga_atk"    => 1,
            "id_mrp_supplier"             => $pst['id_supplier'],
            "create_by_users"             => $this->session->userdata("id"),
            "create_date"                 => date("Y-m-d H:i:s"),
            "update_date"                 => date("Y-m-d H:i:s")
        );
        
        $mrp_setting_harga = $this->global_models->update_duplicate("mrp_setting_harga_atk", $kirim,$kirim);
        
      }
      if($mrp_setting_harga){
        $this->session->set_flashdata('success', 'Data tersimpan');
      }
      else{
        $this->session->set_flashdata('notice', 'Data tidak tersimpan');
      }
      redirect("mrp/mrp-setting/harga-atk");
    }
  }
  
    function lock_atk() {
      
//      print "aa"; die;
      $css = "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/datatables/dataTables.bootstrap.css' rel='stylesheet' type='text/css' />"
      . "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/jQueryUI/jquery-ui-1.10.3.custom.min.css' rel='stylesheet' type='text/css' />"
 ;
    
    $foot = ""
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/datatables/jquery.dataTables.js' type='text/javascript'></script>"
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/datatables/dataTables.bootstrap.js' type='text/javascript'></script>"
  ;
	  
    $foot .= "<script>"

      . '$(function() {'
        . 'var table = '
        . '$("#tableboxy").dataTable({'
          . '"order": [[ 0, "desc" ]],'
//          . "'iDisplayLength': 20"
        . '});'
      
        . "ambil_data(table, 0);"
      
      . '});'
      
      . 'function ambil_data(table, mulai){'
        . '$.post("'.site_url("mrp/mrp-setting-ajax/get-lock-atk").'/"+mulai, function(data){'
          . '$("#loader-page").show();'
          . 'var hasil = $.parseJSON(data);'
          . 'if(hasil.status == 2){'
                . 'if(hasil.flag == 2){'
                . '$("#loader-page").hide();'
                . '}'
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
    

//     $pst = $this->input->post(NULL);
//      if($pst){
//
//    }
    
    $this->template->build('mrp-setting/lock-atk', 
      array(
            'url'           => base_url()."themes/".DEFAULTTHEMES."/",
            'menu'          => "mrp/mrp-setting/lock-atk",
            'title'         => lang("Lock ATK"),
            'foot'          => $foot,
            'css'           => $css,
//            'type'          => $type,
//            'jenis'         => $jenis,
//            'brand'         => $brand,
          ));
    $this->template
      ->set_layout('default')
      ->build('mrp-setting/lock-atk');
  }
  
  public function add_lock_atk($id_mrp_setting_lock_atk = 0){

    if(!$this->input->post(NULL)){
        $where = "WHERE A.id_mrp_setting_lock_atk ='{$id_mrp_setting_lock_atk}'";
        $detail = $this->global_models->get_query("SELECT  A.id_mrp_setting_lock_atk,A.id_hr_company,C.title AS nama_company,A.note"
        . ",D.title AS aa,E.title AS bb,F.title AS cc,G.title AS dd,D.level AS a1,E.level AS b1,F.level AS c1,G.level AS d1"
        . ",D.id_hr_master_organisasi AS a2,E.id_hr_master_organisasi AS b2,F.id_hr_master_organisasi AS c2,G.id_hr_master_organisasi AS d2"
        . " FROM mrp_setting_lock_atk AS A"
//        . " LEFT JOIN m_users AS B ON A.id_users = B.id_users"
        . " LEFT JOIN hr_company AS C ON A.id_hr_company = C.id_hr_company"    
        . " LEFT JOIN hr_master_organisasi AS D ON A.id_hr_master_organisasi = D.id_hr_master_organisasi"
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
    $css = "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/datatables/dataTables.bootstrap.css' rel='stylesheet' type='text/css' />"
      . "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/jQueryUI/jquery-ui-1.10.3.custom.min.css' rel='stylesheet' type='text/css' />"
      . "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/select2.css' type='text/css' rel='stylesheet'>";
      $url = base_url("mrp/mrp-setting/add-lock-atk/{$id_mrp_setting_lock_atk}");
//      $url2 = base_url("mrp/add-request-pengadaan-atk/{$id_mrp_request}");
    $foot = ""
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/datatables/jquery.dataTables.js' type='text/javascript'></script>"
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/datatables/dataTables.bootstrap.js' type='text/javascript'></script>"
      . "<script type='text/javascript' src='".base_url()."themes/".DEFAULTTHEMES."/js/select2.js'></script>"
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/jquery.ui.autocomplete.min.js' type='text/javascript'></script>";
	
    $foot .= "<script>"
        
        . "$(function() {"
            
            . "$( '#users' ).autocomplete({"
                  . "source: '".site_url("mrp/mrp-ajax/get-pegawai/{$this->session->userdata("id")}")."',"
                  . "minLength: 1,"
                  . "search  : function(){ $(this).addClass('working');},"
                  . "open    : function(){ $(this).removeClass('working');},"
                  . "select: function( event, ui ) {"
                    . "$('#id_users').val(ui.item.id);"
                  . "}"
                . "});"
            
            . "$('#dt-lock').click(function(){"
        . '$(function() {'
        . 'var table2 = '
        . '$("#tableboxy2").dataTable({'
          . '"order": [[ 0, "asc" ]],'
          . '"bDestroy": true'                
//          . "'iDisplayLength': 100"
//            . "'page': 'all'"
        . '});'
        . "table2.fnClearTable();"
        . 'ambil_data2(table2, 0);'
      
      . '});'      
        . 'function ambil_data2(table, mulai){'
        . '$.post("'.site_url("mrp/mrp-setting-ajax/get-view-lock-atk/{$id_mrp_setting_lock_atk}").'/"+mulai, function(data){'
          . '$("#loader-page").show();'
          . 'var hasil = $.parseJSON(data);'
          . 'if(hasil.status == 2){'
            . 'table.fnAddData(hasil.hasil);'
            . 'ambil_data2(table, hasil.start);'
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
            
            . "$('#btn-task-orders').click(function(){"
                          . "var selects = new Array();"
                          ."$('.dropdown2').each(function(){"
                           . "selects.push($(this).val());"
                          . "});"

            . "var note2   = $('#note_atk').val();"
            . "var id_company   = $('#id_company').val();"    
            . "var oTable = $('#tableboxy').dataTable();"
                          . "var dataArr = [];"
                          . "var rowcollection =  oTable.$('input:checked', {'page': 'all'});"
                          . "rowcollection.each(function(index,elem){"
                          . "dataArr.push($(elem).val());"
                          . " });"
                         
               
//            . "var aData1 = [];"
//            . "var rowcollection1 =  oTable.$('.jumlah', {'page': 'all'});"
//            . "rowcollection1.each(function(index,elem){"
//            . "var value_jumlah = parseInt($(elem).val());"
//            . " var hasil2 = (isNaN(value_jumlah)) ? 0 : value_jumlah;"
//            . "aData1.push( hasil2 );"
//            . "});"
                        ."var dataString2 = 'id_spesifik='+ dataArr +'&id_company='+id_company+'&note=' + note2 +'&id_hr_master_organisasi='+selects;"
                       
                        ."$.ajax({"
                        ."type : 'POST',"
                        ."url : '".site_url("mrp/mrp-setting-ajax/insert-form-lock-atk/{$id_mrp_setting_lock_atk}")."',"
                        ."data: dataString2,"
                        ."dataType : 'html',"
                        ."success: function(data) {"
                            ."window.location ='{$url}'"
                        ."},"
                     ."});"
            . "});"

        . "});"
                                    
      . '$(function() {'
        . 'var table = '
        . '$("#tableboxy").dataTable({'
          . '"order": [[ 0, "asc" ]],'
//          . "'iDisplayLength': 100"
//            . "'page': 'all'"
        . '});'
      
        . 'ambil_data(table, 0);'
      
      . '});'
      
      . 'function ambil_data(table, mulai){'
        . '$.post("'.site_url("mrp/mrp-setting-ajax/get-form-lock-atk/{$id_mrp_setting_lock_atk}").'/"+mulai, function(data){'
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

      $foot .= "<script>"
              ."var id_company = $('#id_company').val();"
              ."company(id_company);"
         . "$('.dropdown2').select2();"
              . "$('.dropdown3').select2();"
              . "$(function() {"
              ."$('#id_company').change(function(){"
                 ." var id=$(this).val();"
                 ."company(id,1);"
                 ."department(id,1);"
                 ."divisi(id,1);"
                 ."section(id,1);"
                ."});"
              
                . "$( '#users' ).autocomplete({"
                  . "source: '".site_url("hr/hr-ajax/get-users")."',"
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
            ."url: '".site_url("hr/hr-ajax/ajax-company-direktorat/{$direktorat}")."',"
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
            ."url: '".site_url("hr/hr-ajax/ajax-company-department/{$department}")."',"
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
            ."url: '".site_url("hr/hr-ajax/ajax-company-divisi/{$divisi}")."',"
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
            ."url: '".site_url("hr/hr-ajax/ajax-company-section/{$section}")."',"
            ."data: dataString2,"
            ."cache: false,"
            ."success: function(html)"
            ."{"
            ."$('#dt_section').html(html);"
            ."}"
            ."});"
      ."}"           
      ."</script>";
//       $list = $this->global_models->get("mrp_request", array("id_mrp_request" => $id_mrp_request)); 
         
       $dropdown_company = $this->global_models->get_dropdown("hr_company", "id_hr_company", "title", TRUE); 
      
//        print $this->db->last_query();
//        die;
      $this->template->build("mrp-setting/view-lock", 
        array(
              'url'         => base_url()."themes/".DEFAULTTHEMES."/",
              'menu'        => 'mrp/mrp-setting/lock-atk',
              'title'       => lang("mrp_add_lock_atk"),
                'detail'        => $detail,
             'company'     => $dropdown_company,
              'breadcrumb'  => array(
                    "mrp_lock_atk"  => "mrp/mrp-setting/lock-atk"
                ),
              'css'         => $css,
              'foot'        => $foot,
              'img'       => base_url()."themes/".DEFAULTTHEMES."/img/ajax-loader.gif"
//              'items'       => $items
            ));
      $this->template
        ->set_layout('form')
        ->build("mrp-setting/view-lock");
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
  
  }
