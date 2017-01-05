<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Hr_master extends MX_Controller {
    
  function __construct() {      
    $this->menu = $this->cek();
  }
  
  function delete_master_organisasi($id,$redirect){
    $this->global_models->delete("hr_master_organisasi", array("id_hr_master_organisasi" => $id));
    $this->session->set_flashdata('success', 'Perubahan data');
    redirect("hr/hr-master/".$redirect);
  }
  
  function company(){
    
    $list = $this->global_models->get("hr_company");
    
    $menutable = '
      <li><a href="'.site_url("hr/hr-master/add-company").'"><i class="icon-plus"></i>Add New</a></li>
      ';
    $this->template->build('hr-master/company', 
      array(
            'url'         => base_url()."themes/".DEFAULTTHEMES."/",
            'menu'        => "hr/hr-master/company",
            'data'        => $list,
            'title'       => lang("Master Company"),
            'menutable'   => $menutable,
          ));
    $this->template
      ->set_layout('datatables')
      ->build('hr-master/company');
  }
  
    function direktorat(){
    
    $css = "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/datatables/dataTables.bootstrap.css' rel='stylesheet' type='text/css' />"
      . "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/jQueryUI/jquery-ui-1.10.3.custom.min.css' rel='stylesheet' type='text/css' />"
//      . "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/select2.css' type='text/css' rel='stylesheet'>"
            ;
    
    $foot = ""
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/jquery-ui-1.10.3.min.js' type='text/javascript'></script>"
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/datatables/jquery.dataTables.js' type='text/javascript'></script>"
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/datatables/dataTables.bootstrap.js' type='text/javascript'></script>"
//      . "<script type='text/javascript' src='".base_url()."themes/".DEFAULTTHEMES."/js/select2.js'></script>"
            ;
	  
    $foot .= "<script>"
//      . "$('.dropdown2').select2();"
      . '$(function() {'
        . 'var table = '
        . '$("#tableboxy").dataTable({'
          . '"order": [[ 1, "asc" ]],'
//          . "'iDisplayLength': 20"
        . '});'
      
        . 'ambil_data(table, 0);'
      
      . '});'
      
      . 'function ambil_data(table, mulai){'
        . '$.post("'.site_url("hr/hr-ajax/get-hr-master-direktorat").'/"+mulai, function(data){'
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
       
    
    $this->template->build('hr-master/direktorat', 
      array(
            'url'         => base_url()."themes/".DEFAULTTHEMES."/",
            'menu'        => "hr/hr-master/direktorat",
            'data'        => $list,
            'title'       => lang("hr_master_direktorat"),
            'css'         => $css,
            'foot'        => $foot,
//            'department'  => $dropdown_department,
//            'company'     => $dropdown_company,
//            'users'       => $dropdown_user,
          ));
    $this->template
      ->set_layout('default')
      ->build('hr-master/direktorat');
  }
  
     function section(){
    
    $css = "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/datatables/dataTables.bootstrap.css' rel='stylesheet' type='text/css' />"
      . "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/jQueryUI/jquery-ui-1.10.3.custom.min.css' rel='stylesheet' type='text/css' />"
//      . "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/select2.css' type='text/css' rel='stylesheet'>"
            ;
    
    $foot = ""
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/jquery-ui-1.10.3.min.js' type='text/javascript'></script>"
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/datatables/jquery.dataTables.js' type='text/javascript'></script>"
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/datatables/dataTables.bootstrap.js' type='text/javascript'></script>"
//      . "<script type='text/javascript' src='".base_url()."themes/".DEFAULTTHEMES."/js/select2.js'></script>"
            ;
	  
    $foot .= "<script>"
//      . "$('.dropdown2').select2();"
      . '$(function() {'
        . 'var table = '
        . '$("#tableboxy").dataTable({'
          . '"order": [[ 1, "asc" ]],'
//          . "'iDisplayLength': 20"
        . '});'
      
        . 'ambil_data(table, 0);'
      
      . '});'
      
      . 'function ambil_data(table, mulai){'
        . '$.post("'.site_url("hr/hr-ajax/get-hr-master-section").'/"+mulai, function(data){'
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
      
    $this->template->build('hr-master/section', 
      array(
            'url'         => base_url()."themes/".DEFAULTTHEMES."/",
            'menu'        => "hr/hr-master/section",
            'data'        => $list,
            'title'       => lang("hr_master_section"),
            'css'         => $css,
            'foot'        => $foot,
            'parent'        => $parent,
//            'company'     => $dropdown_company,
//            'users'       => $dropdown_user,
          ));
    $this->template
      ->set_layout('default')
      ->build('hr-master/section');
  }
  
   function mo_department(){
    
    $css = "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/datatables/dataTables.bootstrap.css' rel='stylesheet' type='text/css' />"
      . "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/jQueryUI/jquery-ui-1.10.3.custom.min.css' rel='stylesheet' type='text/css' />"
//      . "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/select2.css' type='text/css' rel='stylesheet'>"
            ;
    
    $foot = ""
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/jquery-ui-1.10.3.min.js' type='text/javascript'></script>"
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/datatables/jquery.dataTables.js' type='text/javascript'></script>"
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/datatables/dataTables.bootstrap.js' type='text/javascript'></script>"
//      . "<script type='text/javascript' src='".base_url()."themes/".DEFAULTTHEMES."/js/select2.js'></script>"
            ;
	  
    $foot .= "<script>"
//      . "$('.dropdown2').select2();"
      . '$(function() {'
        . 'var table = '
        . '$("#tableboxy").dataTable({'
          . '"order": [[ 1, "asc" ]],'
//          . "'iDisplayLength': 20"
        . '});'
      
        . 'ambil_data(table, 0);'
      
      . '});'
      
      . 'function ambil_data(table, mulai){'
        . '$.post("'.site_url("hr/hr-ajax/get-hr-master-mo-department").'/"+mulai, function(data){'
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
      
    $this->template->build('hr-master/mo-department', 
      array(
            'url'         => base_url()."themes/".DEFAULTTHEMES."/",
            'menu'        => "hr/hr-master/mo-department",
            'data'        => $list,
            'title'       => lang("hr_master_department"),
            'css'         => $css,
            'foot'        => $foot,
            'parent'        => $parent,
//            'company'     => $dropdown_company,
//            'users'       => $dropdown_user,
          ));
    $this->template
      ->set_layout('default')
      ->build('hr-master/mo-department');
  }
  
  function divisi(){
    
    $css = "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/datatables/dataTables.bootstrap.css' rel='stylesheet' type='text/css' />"
      . "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/jQueryUI/jquery-ui-1.10.3.custom.min.css' rel='stylesheet' type='text/css' />"
//      . "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/select2.css' type='text/css' rel='stylesheet'>"
            ;
    
    $foot = ""
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/jquery-ui-1.10.3.min.js' type='text/javascript'></script>"
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/datatables/jquery.dataTables.js' type='text/javascript'></script>"
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/datatables/dataTables.bootstrap.js' type='text/javascript'></script>"
//      . "<script type='text/javascript' src='".base_url()."themes/".DEFAULTTHEMES."/js/select2.js'></script>"
            ;
	  
    $foot .= "<script>"
//      . "$('.dropdown2').select2();"
      . '$(function() {'
        . 'var table = '
        . '$("#tableboxy").dataTable({'
          . '"order": [[ 1, "asc" ]],'
//          . "'iDisplayLength': 20"
        . '});'
      
        . 'ambil_data(table, 0);'
      
      . '});'
      
      . 'function ambil_data(table, mulai){'
        . '$.post("'.site_url("hr/hr-ajax/get-hr-master-divisi").'/"+mulai, function(data){'
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
      
    $this->template->build('hr-master/divisi', 
      array(
            'url'         => base_url()."themes/".DEFAULTTHEMES."/",
            'menu'        => "hr/hr-master/mo-department",
            'data'        => $list,
            'title'       => lang("hr_master_department"),
            'css'         => $css,
            'foot'        => $foot,
            'parent'        => $parent,
//            'company'     => $dropdown_company,
//            'users'       => $dropdown_user,
          ));
    $this->template
      ->set_layout('default')
      ->build('hr-master/divisi');
  }
  
   public function add_mo_department($id_hr_master_organisasi = 0){
    
    if(!$this->input->post(NULL)){
//      $detail = $this->global_models->get("hr_pegawai", array("id_hr_pegawai" => $id_hr_pegawai));
      $detail = $this->global_models->get_query("SELECT  A.id_hr_master_organisasi,A.title,A.code,B.id_hr_master_organisasi AS direktorat"
        . " FROM hr_master_organisasi AS A"
        . " LEFT JOIN hr_master_organisasi AS B ON A.parent = B.id_hr_master_organisasi"
        . " WHERE A.level=2 AND A.id_hr_master_organisasi={$id_hr_master_organisasi}"
        . " ORDER BY A.id_hr_master_organisasi ASC");
        
        $css = "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/select2.css' type='text/css' rel='stylesheet'>";
      $foot = "<script type='text/javascript' src='".base_url()."themes/".DEFAULTTHEMES."/js/select2.js'></script>";
      $foot .= "<script>"
//              ."var id_direktorat = $('#id_direktorat').val();"
//              ."direktorat(id_direktorat);"
            . "$('.dropdown2').select2();"
//              . "$(function() {"
//              ."$('#id_direktorat').change(function(){"
//                 ." var id=$(this).val();"
//                 ."direktorat(id);"
//                ."});"         
//            
//            . "});"
              
//      ."function direktorat(id){"
//      ."var dataString2 = 'id_direktorat='+ id;"
//            
//         ."$.ajax"
//            ."({"
//            ."type: 'POST',"
//            ."url: '".site_url("hr/hr-ajax/ajax-section/{$detail[0]->section}")."',"
//            ."data: dataString2,"
//            ."cache: false,"
//            ."success: function(html)"
//            ."{"
//            ."$('#dt_section').html(html);"
//            ."}"
//            ."});"
//        ."}"
      ."</script>";
       
    $dropdown_direktorat = $this->global_models->get_dropdown("hr_master_organisasi", "id_hr_master_organisasi", "title", TRUE,array("level" => "1"));     
//  print $abc = $this->db->last_query();
//    die;
//    $dropdown_section = $this->global_models->get_dropdown("hr_master_organisasi", "id_hr_master_organisasi", "title", TRUE,array("level" => "2")); 

      $this->template->build("hr-master/add-mo-department", 
        array(
              'url'         => base_url()."themes/".DEFAULTTHEMES."/",
              'menu'        => 'hr/hr-master/mo-department',
              'title'       => lang("Master Add Department"),
              'detail'      => $detail,
              'list'        => $list,
              'direktorat'  => $dropdown_direktorat,
//              'section'     => $dropdown_section,
              'breadcrumb'  => array(
                    "master_department"  => "hr/hr-master/mo-department"
                ),
              'css'         => $css,
              'foot'        => $foot
            ));
      $this->template
        ->set_layout('form')
        ->build("hr-master/add-mo-department");
    }
    else{
      $pst = $this->input->post(NULL);
//      print_r($pst); die;
      if($pst['id_detail']){
       $kirim = array(
            "title"                         => $pst['title'],
            "code"                          => $pst['code'],
            "parent"                        => $pst['direktorat'],
            "update_by_users"               => $this->session->userdata("id"),
            "update_date"                   => date("Y-m-d H:i:s")
        );
        
        $id_hr_direktorat = $this->global_models->update("hr_master_organisasi", array("id_hr_master_organisasi" => $pst['id_detail']),$kirim);
      
      }
      else{
        $kirim = array(
            "title"                         => $pst['title'],
            "code"                          => $pst['code'],
            "level"                         => 2,
            "parent"                        => $pst['direktorat'],
            "create_by_users"               => $this->session->userdata("id"),
            "create_date"                   => date("Y-m-d H:i:s")
        );
        
        $id_hr_direktorat = $this->global_models->insert("hr_master_organisasi", $kirim);
        
      }
      if($id_hr_direktorat){
        $this->session->set_flashdata('success', 'Data tersimpan');
      }
      else{
        $this->session->set_flashdata('notice', 'Data tidak tersimpan');
      }
      redirect("hr/hr-master/mo-department");
    }
  }
  
  public function add_divisi($id_hr_master_organisasi){
    
    if(!$this->input->post(NULL)){

        $detail = $this->global_models->get_query("SELECT  A.id_hr_master_organisasi,A.title,A.code,B.id_hr_master_organisasi AS department"
        . ",C.id_hr_master_organisasi AS direktorat"
        . " FROM hr_master_organisasi AS A"
        . " LEFT JOIN hr_master_organisasi AS B ON A.parent = B.id_hr_master_organisasi"
        . " LEFT JOIN hr_master_organisasi AS C ON B.parent = C.id_hr_master_organisasi"    
        . " WHERE A.level=3 AND A.id_hr_master_organisasi='{$id_hr_master_organisasi}'"
        . " ORDER BY A.id_hr_master_organisasi ASC");
      
        $css = "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/select2.css' type='text/css' rel='stylesheet'>";
      $foot = "<script type='text/javascript' src='".base_url()."themes/".DEFAULTTHEMES."/js/select2.js'></script>";
      $foot .= "<script>"
              ."var id_direktorat = $('#id_direktorat').val();"
//              ." var department=$('#id_department').val();"
              ."department(id_direktorat);"
//              ."section(id_dept);"
            . "$('.dropdown2').select2();"
              . "$(function() {"
              ."$('#id_direktorat').change(function(){"
                 ." var id=$(this).val();"
//                 . "alert(id);"
                 ."department(id);"
                 
//                 ."department(id_dept);"
                ."});"
              
            . "});"
              
            
              
      ."function department(id){"
      ."var dataString2 = 'id_direktorat='+ id;"
            
         ."$.ajax"
            ."({"
            ."type: 'POST',"
            ."url: '".site_url("hr/hr-ajax/ajax-department/{$detail[0]->department}")."',"
            ."data: dataString2,"
            ."cache: false,"
            ."success: function(html)"
            ."{"
            ."$('#dt_department').html(html);"
            ."}"
            ."});"
        ."}"
     
      ."</script>";
       
    $dropdown_direktorat = $this->global_models->get_dropdown("hr_master_organisasi", "id_hr_master_organisasi", "title", TRUE,array("level" => "1"));     
//  print $abc = $this->db->last_query();
//    die;
//    $dropdown_department = $this->global_models->get_dropdown("hr_master_organisasi", "id_hr_master_organisasi", "title", TRUE,array("level" => "2")); 

    
      $this->template->build("hr-master/add-divisi", 
        array(
              'url'         => base_url()."themes/".DEFAULTTHEMES."/",
              'menu'        => 'hr/hr-master/divisi',
              'title'       => lang("Master Add Divisi"),
              'detail'      => $detail,
              'list'        => $list,
              'direktorat'  => $dropdown_direktorat,
//              'department'     => $dropdown_department,
             
              'breadcrumb'  => array(
                    "master_divisi"  => "hr/hr-master/divisi"
                ),
              'css'         => $css,
              'foot'        => $foot
            ));
      $this->template
        ->set_layout('form')
        ->build("hr-master/add-divisi");
    }
    else{
      $pst = $this->input->post(NULL);
//      print_r($pst); die;
      if($pst['id_detail']){
       $kirim = array(
            "title"                         => $pst['title'],
            "code"                          => $pst['code'],
            "parent"                        => $pst['department'],
            "update_by_users"               => $this->session->userdata("id"),
            "update_date"                   => date("Y-m-d H:i:s")
        );
        
        $id_divisi = $this->global_models->update("hr_master_organisasi", array("id_hr_master_organisasi" => $pst['id_detail']),$kirim);
      
      
      }
      else{
        $kirim = array(
            "title"                         => $pst['title'],
            "code"                          => $pst['code'],
            "level"                         => 3,
            "parent"                        => $pst['department'],
            "create_by_users"               => $this->session->userdata("id"),
            "create_date"                   => date("Y-m-d H:i:s")
        );
        
        $id_divisi = $this->global_models->insert("hr_master_organisasi", $kirim);
        
      }
      if($id_divisi){
        $this->session->set_flashdata('success', 'Data tersimpan');
      }
      else{
        $this->session->set_flashdata('notice', 'Data tidak tersimpan');
      }
      redirect("hr/hr-master/divisi");
    }
  }
  
     public function add_section($id_hr_master_organisasi = 0){
    
    if(!$this->input->post(NULL)){
         $detail = $this->global_models->get_query("SELECT  A.id_hr_master_organisasi,A.title,A.code,B.id_hr_master_organisasi AS divisi"
        . ",C.id_hr_master_organisasi AS department,D.id_hr_master_organisasi AS direktorat"
        . " FROM hr_master_organisasi AS A"
        . " LEFT JOIN hr_master_organisasi AS B ON A.parent = B.id_hr_master_organisasi"
        . " LEFT JOIN hr_master_organisasi AS C ON B.parent = C.id_hr_master_organisasi"
        . " LEFT JOIN hr_master_organisasi AS D ON C.parent = D.id_hr_master_organisasi"      
        . " WHERE A.level=4 AND A.id_hr_master_organisasi={$id_hr_master_organisasi}"
        . " ORDER BY A.id_hr_master_organisasi ASC");
//         print_r($detail); die;
      $css = "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/select2.css' type='text/css' rel='stylesheet'>";
      $foot = "<script type='text/javascript' src='".base_url()."themes/".DEFAULTTHEMES."/js/select2.js'></script>";
      $foot .= "<script>"
              ."var id_direktorat = $('#id_direktorat').val();"
              ."department(id_direktorat);"

            . "$('.dropdown2').select2();"
              . "$(function() {"
              ."$('#id_direktorat').change(function(){"
                 ." var id=$(this).val();"
                 ."department(id);"
                ."});"
              
              ."$('#id_department').change(function(){"
                 ." var id=$(this).val();"
                 . "alert(id);"
                 ."divisi(id);"
                ."});"
              
            . "});"
              
      ."function department(id){"
             
      ."var dataString2 = 'id_direktorat='+ id;"
         ."$.ajax"
            ."({"
            ."type: 'POST',"
            ."url: '".site_url("hr/hr-ajax/ajax-department/{$detail[0]->department}")."',"
            ."data: dataString2,"
            ."cache: false,"
            ."success: function(html)"
            ."{"
            ."$('#dt_department').html(html);"
            ." var id_department=$('#id_department').val();"
//             . "alert(id_department);"        
            . "divisi(id_department);"
            ."}"
            ."});"
        ."}"
      ."function divisi(id){"
      ."var dataString2 = 'id_department='+ id;"
         ."$.ajax"
            ."({"
            ."type: 'POST',"
            ."url: '".site_url("hr/hr-ajax/ajax-divisi/{$detail[0]->divisi}")."',"
            ."data: dataString2,"
            ."cache: false,"
            ."success: function(html)"
            ."{"
            ."$('#dt_divisi').html(html);"
            ."}"
            ."});"
        ."}"
     
      ."</script>";
     $parent = $this->global_models->get_dropdown("hr_master_organisasi", "id_hr_master_organisasi", "title", TRUE,array("level" => "1")); 
    
      $this->template->build("hr-master/add-section", 
        array(
              'url'         => base_url()."themes/".DEFAULTTHEMES."/",
              'menu'        => 'hr/hr-master/section',
              'title'       => lang("Master Add Section"),
              'detail'      => $detail,
              'parent'        => $parent,
              'breadcrumb'  => array(
                    "master_section"  => "hr/hr-master/section"
                ),
              'css'         => $css,
              'foot'        => $foot
            ));
      $this->template
        ->set_layout('form')
        ->build("hr-master/add-section");
    }
    else{
      $pst = $this->input->post(NULL);
      if($pst['id_detail']){
        $kirim = array(
            "title"                         => $pst['title'],
            "code"                          => $pst['code'],
            "parent"                        => $pst['divisi'],
            "update_by_users"               => $this->session->userdata("id"),
            "update_date"                   => date("Y-m-d H:i:s")
        );
        
        $id_section = $this->global_models->update("hr_master_organisasi", array("id_hr_master_organisasi" => $pst['id_detail']),$kirim);
      
      }
      else{
        $kirim = array(
            "title"                         => $pst['title'],
            "code"                          => $pst['code'],
            "level"                         => 4,
            "parent"                        => $pst['divisi'],
            "create_by_users"               => $this->session->userdata("id"),
            "create_date"                   => date("Y-m-d H:i:s")
        );
        
        $id_section = $this->global_models->insert("hr_master_organisasi", $kirim);
        
      }
      if($id_section){
        $this->session->set_flashdata('success', 'Data tersimpan');
      }
      else{
        $this->session->set_flashdata('notice', 'Data tidak tersimpan');
      }
      redirect("hr/hr-master/section");
    }
  }
  
   public function add_direktorat($id_hr_master_organisasi = 0){
    
//    $dropdown_department = $this->global_models->get_dropdown("hr_department", "id_hr_department", "title", TRUE);     
//    
//    $dropdown_company = $this->global_models->get_dropdown("hr_company", "id_hr_company", "title", TRUE); 
//    
//    $dropdown_user = $this->global_models->get_dropdown("m_users", "id_users", "name", TRUE); 
  
    if(!$this->input->post(NULL)){
      $detail = $this->global_models->get("hr_master_organisasi", array("id_hr_master_organisasi" => $id_hr_master_organisasi,"level" => 1));

            
      $this->template->build("hr-master/add-direktorat", 
        array(
              'url'         => base_url()."themes/".DEFAULTTHEMES."/",
              'menu'        => 'hr/hr-master/direktorat',
              'title'       => lang("Master Add Direktorat"),
              'detail'      => $detail,
//              'list'        => $list,
//              'department'  => $dropdown_department,
//              'company'     => $dropdown_company,
//              'users'       => $dropdown_user,
              'breadcrumb'  => array(
                    "master_direktorat"  => "hr/hr-master/direktorat"
                ),
              'css'         => $css,
              'foot'        => $foot
            ));
      $this->template
        ->set_layout('form')
        ->build("hr-master/add-direktorat");
    }
    else{
      $pst = $this->input->post(NULL);
//      print_r($pst); die;
      if($pst['id_detail']){
        $kirim = array(
            "title"                         => $pst['title'],
            "code"                          => $pst['code'],
            "update_by_users"               => $this->session->userdata("id"),
            "update_date"                   => date("Y-m-d H:i:s")
        );
        
        $id_hr_direktorat = $this->global_models->update("hr_master_organisasi", array("id_hr_master_organisasi" => $pst['id_detail']),$kirim);
      
      }
      else{
        $kirim = array(
            "title"                         => $pst['title'],
            "code"                          => $pst['code'],
            "level"                         => 1,
            "create_by_users"               => $this->session->userdata("id"),
            "create_date"                   => date("Y-m-d H:i:s")
        );
        
        $id_hr_direktorat = $this->global_models->insert("hr_master_organisasi", $kirim);
        
      }
      if($id_hr_direktorat){
        $this->session->set_flashdata('success', 'Data tersimpan');
      }
      else{
        $this->session->set_flashdata('notice', 'Data tidak tersimpan');
      }
      redirect("hr/hr-master/direktorat");
    }
  }
  
  public function add_company($id_hr_company = 0){
    
//    $dropdown = $this->global_models->get_dropdown("master_department", "id_master_department", "title", TRUE);     
  
    if(!$this->input->post(NULL)){
      $detail = $this->global_models->get("hr_company", array("id_hr_company" => $id_hr_company));

      
      $this->template->build("hr-master/add-company", 
        array(
              'url'         => base_url()."themes/".DEFAULTTHEMES."/",
              'menu'        => 'hr/hr-master/company',
              'title'       => lang("Master Add Company"),
              'detail'      => $detail,
              'list'        => $list,
              'dropdown'    => $dropdown,
              'breadcrumb'  => array(
                    "master_company"  => "hr/hr-master/company"
                ),
              'css'         => $css,
              'foot'        => $foot
            ));
      $this->template
        ->set_layout('form')
        ->build("hr-master/add-company");
    }
    else{
      $pst = $this->input->post(NULL);
      
      if($pst['id_detail']){
        $kirim = array(
          "title"                        => $pst['title'],
            "telp"                        => $pst['telp'],
            "fax"                         => $pst['fax'],
            "office"                      => $pst['office'],
            "address"                     => $pst['address'],
            "code"                        => $pst['code'],
            "npwp"                        => $pst['npwp'],
            "update_by_users"           => $this->session->userdata("id"),
            "update_date"                 => date("Y-m-d H:i:s")
        );
        
        $id_master_company = $this->global_models->update("hr_company", array("id_hr_company" => $pst['id_detail']),$kirim);
      
      }
      else{
        $kirim = array(
           "title"                        => $pst['title'],
            "telp"                        => $pst['telp'],
            "fax"                         => $pst['fax'],
            "office"                      => $pst['office'],
            "address"                     => $pst['address'],
            "code"                        => $pst['code'],
            "npwp"                        => $pst['npwp'],
            "status"                      => 1,
            "create_by_users"             => $this->session->userdata("id"),
            "create_date"                 => date("Y-m-d H:i:s")
        );
        
        $id_hr_company = $this->global_models->insert("hr_company", $kirim);
        
      }
      if($id_hr_company){
        $this->session->set_flashdata('success', 'Data tersimpan');
      }
      else{
        $this->session->set_flashdata('notice', 'Data tidak tersimpan');
      }
      redirect("hr/hr-master/company");
    }
  }
  
    public function company_direktorat($id_hr_company){
//       print "aa"; die;
    if(!$this->input->post(NULL)){
      $css = "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/jQueryUI/jquery-ui-1.10.3.custom.min.css' rel='stylesheet' type='text/css' />";
      $foot = "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/jquery.ui.autocomplete.min.js' type='text/javascript'></script>"
        . "<script>"
          . "$(function() {"
            . "$( '#direktorat' ).autocomplete({"
              . "source: '".site_url("hr/hr-ajax/get-hr-master-company-direktorat")."',"
              . "minLength: 1,"
              . "search  : function(){ $(this).addClass('working');},"
              . "open    : function(){ $(this).removeClass('working');},"
              . "select: function( event, ui ) {"
                . "$('#id_direktorat').val(ui.item.id);"
              . "}"
            . "});"
            . "$(document).on('click', '.delete', function(evt){"
              . "var didelete = $(this).attr('isi');"
              . "$('#'+didelete).remove();"
            . "});"
            . "$(document).on('click', '#add-row', function(evt){"
              . "$.post('".site_url("hr/hr-ajax/add-row-direktorat")."',{no: $('#nomor').val()},function(data){"
//                . "$('#wadah').insertBefore(data);"
                . "$(data).insertBefore('#wadah');"
                . "var t = ($('#nomor').val() * 1) + 1;"
                . "$('#nomor').val(t);"
              . "});"
            . "});"
          . "});"
        . "</script>";
      $detail = $this->global_models->get_query("SELECT A.*, B.title,B.code"
        . " FROM hr_company_direktorat AS A"
        . " LEFT JOIN hr_master_organisasi AS B ON A.id_hr_master_organisasi = B.id_hr_master_organisasi"
        . " WHERE A.id_hr_company = '{$id_hr_company}' AND level='1' ");
      foreach ($detail AS $key => $det){
        $hasil .= "<div class='input-group margin' id='direktorat-box{$key}'>"
            . "<input type='text' class='form-control' value='{$det->title} <{$det->code}>' id='direktorat{$key}' name='direktorat[]'>"
            . "<input type='text' class='form-control' value='{$det->id_hr_department}' id='id_direktorat{$key}' name='id_direktorat[]' style='display: none'>"
            . "<span class='input-group-btn'>"
              . "<a href='javascript:void(0)' class='btn btn-danger btn-flat delete' isi='department-box{$key}'>"
                . "<i class='fa fa-fw fa-times'></i>"
              . "</a>"
            . "</span> "
          . "</div>";
        $foot .= "<script>"
              . "$(function() {"
                . "$( '#direktorat{$key}' ).autocomplete({"
                  . "source: '".site_url("hr/hr-ajax/get-hr-master-company-direktorat")."',"
                  . "minLength: 1,"
                  . "search  : function(){ $(this).addClass('working');},"
                  . "open    : function(){ $(this).removeClass('working');},"
                  . "select: function( event, ui ) {"
                    . "$('#id_direktorat{$key}').val(ui.item.id);"
                  . "}"
                . "});"
              . "});"
          . "</script>";
      }
      $this->template->build("hr-master/company-direktorat", 
        array(
              'url'         => base_url()."themes/".DEFAULTTHEMES."/",
              'menu'        => 'hr/hr-master/company',
              'title'       => lang("List Company Direktorat"),
              'detail'      => count($detail),
              'hasil'       => $hasil,
              'breadcrumb'  => array(
                    "Company"  => "hr/hr-master/company"
                ),
              'css'         => $css,
              'foot'        => $foot
            ));
      $this->template
        ->set_layout('form')
        ->build("hr-master/company-direktorat");
    }
    else{
      $pst = $this->input->post(NULL);
//        print_r($pst); die;
      if($pst['direktorat'][0] !=""){
          $this->global_models->delete("hr_company_direktorat", array("id_hr_company" => $id_hr_company));
      foreach ($pst['id_direktorat'] as $value) {
        if($value){
          $kirim[] = array(
            "id_hr_company"                 => $id_hr_company,
            "id_hr_master_organisasi"       => $value,
            "create_by_users"               => $this->session->userdata("id"),
            "create_date"                   => date("Y-m-d H:i:s"),
          );
        }
      }
      if($this->global_models->insert_batch("hr_company_direktorat", $kirim)){
        $this->session->set_flashdata('success', 'Data tersimpan');
      }
      else{
        $this->session->set_flashdata('notice', 'Data tidak tersimpan');
      }
      }else{
          $this->session->set_flashdata('notice', 'Data tidak tersimpan');
      }
      
      redirect("hr/hr-master/company");
    }
  }
  
//   public function company_department($id_hr_company){
////       print "aa"; die;
//    if(!$this->input->post(NULL)){
//      $css = "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/jQueryUI/jquery-ui-1.10.3.custom.min.css' rel='stylesheet' type='text/css' />";
//      $foot = "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/jquery.ui.autocomplete.min.js' type='text/javascript'></script>"
//        . "<script>"
//          . "$(function() {"
//            . "$( '#department' ).autocomplete({"
//              . "source: '".site_url("hr/hr-ajax/get-hr-master-company-deparment")."',"
//              . "minLength: 1,"
//              . "search  : function(){ $(this).addClass('working');},"
//              . "open    : function(){ $(this).removeClass('working');},"
//              . "select: function( event, ui ) {"
//                . "$('#id_department').val(ui.item.id);"
//              . "}"
//            . "});"
//            . "$(document).on('click', '.delete', function(evt){"
//              . "var didelete = $(this).attr('isi');"
//              . "$('#'+didelete).remove();"
//            . "});"
//            . "$(document).on('click', '#add-row', function(evt){"
//              . "$.post('".site_url("hr/hr-ajax/add-row-department")."',{no: $('#nomor').val()},function(data){"
////                . "$('#wadah').insertBefore(data);"
//                . "$(data).insertBefore('#wadah');"
//                . "var t = ($('#nomor').val() * 1) + 1;"
//                . "$('#nomor').val(t);"
//              . "});"
//            . "});"
//          . "});"
//        . "</script>";
//      $detail = $this->global_models->get_query("SELECT A.*, B.title,B.code"
//        . " FROM hr_company_department AS A"
//        . " LEFT JOIN hr_department AS B ON A.id_hr_department = B.id_hr_department"
//        . " WHERE A.id_hr_company = '{$id_hr_company}'");
//      foreach ($detail AS $key => $det){
//        $hasil .= "<div class='input-group margin' id='department-box{$key}'>"
//            . "<input type='text' class='form-control' value='{$det->title} <{$det->code}>' id='department{$key}' name='department[]'>"
//            . "<input type='text' class='form-control' value='{$det->id_hr_department}' id='id_department{$key}' name='id_department[]' style='display: none'>"
//            . "<span class='input-group-btn'>"
//              . "<a href='javascript:void(0)' class='btn btn-danger btn-flat delete' isi='department-box{$key}'>"
//                . "<i class='fa fa-fw fa-times'></i>"
//              . "</a>"
//            . "</span> "
//          . "</div>";
//        $foot .= "<script>"
//              . "$(function() {"
//                . "$( '#department{$key}' ).autocomplete({"
//                  . "source: '".site_url("hr/hr-ajax/get-hr-master-company-deparment")."',"
//                  . "minLength: 1,"
//                  . "search  : function(){ $(this).addClass('working');},"
//                  . "open    : function(){ $(this).removeClass('working');},"
//                  . "select: function( event, ui ) {"
//                    . "$('#id_department{$key}').val(ui.item.id);"
//                  . "}"
//                . "});"
//              . "});"
//          . "</script>";
//      }
//      $this->template->build("hr-master/company-department", 
//        array(
//              'url'         => base_url()."themes/".DEFAULTTHEMES."/",
//              'menu'        => 'hr/hr-master/company',
//              'title'       => lang("List Company Department"),
//              'detail'      => count($detail),
//              'hasil'       => $hasil,
//              'breadcrumb'  => array(
//                    "Company"  => "hr/hr-master/company"
//                ),
//              'css'         => $css,
//              'foot'        => $foot
//            ));
//      $this->template
//        ->set_layout('form')
//        ->build("hr-master/company-department");
//    }
//    else{
//      $pst = $this->input->post(NULL);
////        print_r($pst); die;
//      if($pst['department'][0] !=""){
//          $this->global_models->delete("hr_company_department", array("id_hr_company" => $id_hr_company));
//      foreach ($pst['id_department'] as $value) {
//        if($value){
//          $kirim[] = array(
//            "id_hr_company"            => $id_hr_company,
//            "id_hr_department"            => $value,
//            "create_by_users"     => $this->session->userdata("id"),
//            "create_date"         => date("Y-m-d H:i:s"),
//          );
//        }
//      }
//      if($this->global_models->insert_batch("hr_company_department", $kirim)){
//        $this->session->set_flashdata('success', 'Data tersimpan');
//      }
//      else{
//        $this->session->set_flashdata('notice', 'Data tidak tersimpan');
//      }
//      }else{
//          $this->session->set_flashdata('notice', 'Data tidak tersimpan');
//      }
//      
//      redirect("hr/hr-master/company");
//    }
//  }

//  function department(){
//    $list = $this->global_models->get("hr_department");
//    
//    $menutable = '
//      <li><a href="'.site_url("hr/hr-master/add-department").'"><i class="icon-plus"></i> Add New</a></li>
//      ';
//    $this->template->build('hr-master/department', 
//      array(
//            'url'         => base_url()."themes/".DEFAULTTHEMES."/",
//            'menu'        => "hr/hr-master/department",
//            'data'        => $list,
//            'title'       => lang("hr_master_department"),
//            'menutable'   => $menutable,
//          ));
//    $this->template
//      ->set_layout('datatables')
//      ->build('hr-master/department');
//  }
  
//  public function add_department($id_hr_department = 0){
//    if(!$this->input->post(NULL)){
//      $detail = $this->global_models->get("hr_department", array("id_hr_department" => $id_hr_department));
//      
//      $this->template->build("hr-master/add-department", 
//        array(
//              'url'         => base_url()."themes/".DEFAULTTHEMES."/",
//              'menu'        => 'hr/hr-master/department',
//              'title'       => lang("hr_master_add_department"),
//              'detail'      => $detail,
//              'breadcrumb'  => array(
//                    "hr_master_department"  => "hr/hr-master/department"
//                ),
//            ));
//      $this->template
//        ->set_layout('form')
//        ->build("hr-master/add-department");
//    }
//    else{
//      $pst = $this->input->post(NULL);
//      
//      if($pst['id_detail']){
//        $kirim = array(
//            "title"                     => $pst['title'],
//            "code"                      => $pst['code'],
//            "update_by_users"           => $this->session->userdata("id"),
//            "update_date"               => date("Y-m-d H:i:s")
//        );
//        
//        $id_hr_department = $this->global_models->update("hr_department", array("id_hr_department" => $pst['id_detail']),$kirim);
//      }
//      else{
//        $kirim = array(
//            "title"                     => $pst['title'],
//            "code"                      => $pst['code'],
//            "status"                      => 1,
//            "create_by_users" => $this->session->userdata("id"),
//            "create_date"     => date("Y-m-d")
//        );
//        
//        $id_hr_department = $this->global_models->insert("hr_department", $kirim);
//      }
//      if($id_hr_department){
//        $this->session->set_flashdata('success', 'Data tersimpan');
//      }
//      else{
//        $this->session->set_flashdata('notice', 'Data tidak tersimpan');
//      }
//      redirect("hr/hr-master/department");
//    }
//  }
  
  function pegawai(){
    $dropdown_department = $this->global_models->get_dropdown("hr_department", "id_hr_department", "title", TRUE);     
    
    $dropdown_company = $this->global_models->get_dropdown("hr_company", "id_hr_company", "title", TRUE); 
    
    $dropdown_user = $this->global_models->get_dropdown("m_users", "id_users", "name", TRUE, array("id_users >" => "1")); 
  
    $pst = $this->input->post();
    if($pst){
//      $this->debug($pst, true);
      $set = array(
        "pegawai_search_user"               => $pst['pegawai_search_user'],
        "pegawai_search_nip"                => $pst['pegawai_search_nip'],
        "pegawai_search_company"            => $pst['pegawai_search_company'],
        "pegawai_search_department"         => $pst['pegawai_search_department'],
      );
      $this->session->set_userdata($set);
    }
    
    $css = "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/datatables/dataTables.bootstrap.css' rel='stylesheet' type='text/css' />"
      . "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/jQueryUI/jquery-ui-1.10.3.custom.min.css' rel='stylesheet' type='text/css' />"
      . "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/select2.css' type='text/css' rel='stylesheet'>";
    
    $foot = ""
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/jquery-ui-1.10.3.min.js' type='text/javascript'></script>"
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/datatables/jquery.dataTables.js' type='text/javascript'></script>"
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/datatables/dataTables.bootstrap.js' type='text/javascript'></script>"
      . "<script type='text/javascript' src='".base_url()."themes/".DEFAULTTHEMES."/js/select2.js'></script>";
	  
    $foot .= "<script>"
      . "$('.dropdown2').select2();"
      . '$(function() {'
        . 'var table = '
        . '$("#tableboxy").dataTable({'
          . '"order": [[ 0, "asc" ]],'
//          . "'iDisplayLength': 20"
        . '});'
      
        . 'ambil_data(table, 0);'
      
      . '});'
      
      . 'function ambil_data(table, mulai){'
        . '$.post("'.site_url("hr/hr-ajax/get-hr-master-pegawai").'/"+mulai, function(data){'
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
       
    
    $this->template->build('hr-master/pegawai', 
      array(
            'url'         => base_url()."themes/".DEFAULTTHEMES."/",
            'menu'        => "hr/hr-master/pegawai",
            'data'        => $list,
            'title'       => lang("hr_master_pegawai"),
            'css'         => $css,
            'foot'        => $foot,
            'department'  => $dropdown_department,
            'company'     => $dropdown_company,
            'users'       => $dropdown_user,
          ));
    $this->template
      ->set_layout('default')
      ->build('hr-master/pegawai');
  }
  
  public function add_pegawai($id_hr_pegawai = 0){
    
    $dropdown_department = $this->global_models->get_dropdown("hr_department", "id_hr_department", "title", TRUE);     
    
    $dropdown_company = $this->global_models->get_dropdown("hr_company", "id_hr_company", "title", TRUE); 
    
    $dropdown_user = $this->global_models->get_dropdown("m_users", "id_users", "name", TRUE); 
  
    if(!$this->input->post(NULL)){
//      $detail = $this->global_models->get("hr_pegawai", array("id_hr_pegawai" => $id_hr_pegawai));
        $where = "WHERE id_hr_pegawai='{$id_hr_pegawai}'";
        $detail = $this->global_models->get_query("SELECT  A.nip,A.id_hr_pegawai,A.id_users,A.id_hr_company,B.name,C.title AS nama_company"
        . ",D.title AS aa,E.title AS bb,F.title AS cc,G.title AS dd,D.level AS a1,E.level AS b1,F.level AS c1,G.level AS d1"
        . ",D.id_hr_master_organisasi AS a2,E.id_hr_master_organisasi AS b2,F.id_hr_master_organisasi AS c2,G.id_hr_master_organisasi AS d2"
        . " FROM hr_pegawai AS A"
        . " LEFT JOIN m_users AS B ON A.id_users = B.id_users"
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
      
//        print "1 =".$direktorat." 2 =".$department." 3=".$divisi." 4".$section;
//        die;
//        print_r($detail); die; //$this->db->last_query();
        $css = "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/select2.css' type='text/css' rel='stylesheet'>";
      $foot = "<script type='text/javascript' src='".base_url()."themes/".DEFAULTTHEMES."/js/select2.js'></script>";
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
        
      $this->template->build("hr-master/add-pegawai", 
        array(
              'url'         => base_url()."themes/".DEFAULTTHEMES."/",
              'menu'        => 'hr/hr-master/pegawai',
              'title'       => lang("Master Add Pegawai"),
              'detail'      => $detail,
              'list'        => $list,
              'department'  => $dropdown_department,
              'company'     => $dropdown_company,
              'users'       => $dropdown_user,
              'breadcrumb'  => array(
                    "master_pegawai"  => "hr/hr-master/pegawai"
                ),
              'css'         => $css,
              'foot'        => $foot
            ));
      $this->template
        ->set_layout('form')
        ->build("hr-master/add-pegawai");
    }
    else{
      $pst = $this->input->post(NULL);
    
      $id_hr_master_organisasi = end(array_filter($pst['id_hr_master_organisasi']));
     
//     if($dt_id_users > 0){
//         $this->session->set_flashdata('notice', 'Nama Users Sudah Ada');
//          redirect("hr/hr-master/pegawai");
//     }else{
      
    if($pst['id_detail']){
       $dt_id_users = $this->global_models->get_field("hr_pegawai","id_users", array("id_users" => "{$pst['id_users']}","id_hr_pegawai" =>"{$pst['id_detail']}"));
       if($dt_id_users > 0){
          $kirim = array(
              "id_users"                    => $pst['id_users'],
              "id_hr_company"               => $pst['id_company'],
              "id_hr_master_organisasi"     => $id_hr_master_organisasi,
              "nip"                         => $pst['nip'],
              "update_by_users"             => $this->session->userdata("id"),
              "update_date"                 => date("Y-m-d H:i:s")
          );

        $id_hr_pegawai = $this->global_models->update("hr_pegawai", array("id_hr_pegawai" => $pst['id_detail']),$kirim); 
       }else{
           $dt_id_users2 = $this->global_models->get_field("hr_pegawai","id_users", array("id_users" => $pst['id_users']));
           if($dt_id_users2 > 0){
               $this->session->set_flashdata('notice', 'Nama Users Sudah Ada');
                redirect("hr/hr-master/pegawai");
           }else{
                $kirim = array(
              "id_users"                    => $pst['id_users'],
              "id_hr_company"               => $pst['id_company'],
              "id_hr_master_organisasi"     => $id_hr_master_organisasi,
              "nip"                         => $pst['nip'],
              "update_by_users"             => $this->session->userdata("id"),
              "update_date"                 => date("Y-m-d H:i:s")
          );

        $id_hr_pegawai = $this->global_models->update("hr_pegawai", array("id_hr_pegawai" => $pst['id_detail']),$kirim); 
           }
           
       }  
       

        }
        else{
    $dt_id_users = $this->global_models->get_field("hr_pegawai","id_users", array("id_users" => $pst['id_users']));
    if($dt_id_users > 0){
         $this->session->set_flashdata('notice', 'Nama Users Sudah Ada');
          redirect("hr/hr-master/pegawai");
        }else{
          $kirim = array(
              "id_users"                    => $pst['id_users'],
              "id_hr_company"               => $pst['id_company'],
              "id_hr_master_organisasi"     => $id_hr_master_organisasi,
              "nip"                         => $pst['nip'],
              "status"                      => 1,
              "create_by_users"             => $this->session->userdata("id"),
              "create_date"                 => date("Y-m-d H:i:s")
          );

          $id_hr_pegawai = $this->global_models->insert("hr_pegawai", $kirim);
        }
     }
        
        if($id_hr_pegawai){
        $this->session->set_flashdata('success', 'Data tersimpan');
      }
      else{
        $this->session->set_flashdata('notice', 'Data tidak tersimpan');
      }
  

      
     redirect("hr/hr-master/pegawai");
    }
  }
  
    function pegawai_signature($id_hr_pegawai =0){
 
   // print_r($_FILES); die;
    
    $signature = $this->global_models->get_field("hr_pegawai", "signature", array("id_hr_pegawai" => $id_hr_pegawai));
        
    
    if(!$this->input->post(NULL)){
      $this->template->build('hr-master/upload-signature', 
      array(
            'url'               => base_url()."themes/".DEFAULTTHEMES."/",
            'menu'              => "hr/hr-master/pegawai",
            'id_hr_pegawai'     => $id_hr_pegawai,
            'signature'         =>  $signature,
            'title'             => lang("Upload File Signature"),
            'breadcrumb'        => array(
            "Pegawai"  => "hr/hr-master/pegawai"
            ),
//           'css'         => $css,
//           'foot'        => $foot
          ));
    $this->template
      ->set_layout('form')
      ->build('hr-master/upload-signature');
    }else{
      $pst = $this->input->post(NULL);
//      print "<pre>";
//      print_r($pst); 
//      print "<pre>";
//      die;
      $config['upload_path'] = './files/antavaya/signature/';
      $config['allowed_types'] = 'gif|jpg|jpeg|png';

      $this->load->library('upload', $config);
      
      if($_FILES['file']['name']){
        if (  $this->upload->do_upload('file')){
          $data = array('upload_data' => $this->upload->data());
        }
        else{
          print $this->upload->display_errors();
          print "<br /> <a href='".site_url("user/")."'>Back</a>";
          die;
        }
      }
       $kirim = array(
                "create_by_users"                       => $this->session->userdata("id"),
                "create_date"                           => date("Y-m-d H:i:s")
                );

      if($data['upload_data']['file_name']){
          $kirim['signature'] = $data['upload_data']['file_name'];
        }
       
        $id_csv_file = $this->global_models->update("hr_pegawai",array("id_hr_pegawai" => $pst['id_detail']), $kirim);
        
      if($id_csv_file){
        $this->session->set_flashdata('success', 'Data tersimpan');
      }
      else{
        $this->session->set_flashdata('notice', 'Data tidak tersimpan');
      }
      redirect("hr/hr-master/pegawai");
    }
  }
  
  //  function delete($id_internal_monitoring){
//    $this->global_models->delete("internal_monitoring", array("id_internal_monitoring" => $id_internal_monitoring));
//    $this->session->set_flashdata('success', 'Data tersimpan');
//    redirect("monitoring");
//  }
  
 
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */