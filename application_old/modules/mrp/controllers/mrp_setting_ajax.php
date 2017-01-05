<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mrp_setting_ajax extends MX_Controller {
  function __construct() {
    $this->menu = $this->cek();
  }
  
   function get_supplier(){
       
    if (empty($_GET['term'])) exit ;
    $q = strtolower($_GET["term"]);
    if (get_magic_quotes_gpc()) $q = stripslashes($q);
    $items = $this->global_models->get_query("
      SELECT *
      FROM mrp_supplier
      WHERE 
      LOWER(name) LIKE '%{$q}%'
      LIMIT 0,10
      ");
    if(count($items) > 0){
      foreach($items as $tms){
        $result[] = array(
            "id"    => $tms->id_mrp_supplier,
            "label" => $tms->name,
            "value" => $tms->name,
        );
      }
    }
    else{
      $result[] = array(
          "id"    => 0,
          "label" => "No Found",
          "value" => "No Found",
      );
    }
    echo json_encode($result);
    die;
  }
  
  function get_setting_users_request($start = 0){
 
//    $where = "WHERE {$qry}";

     $data = $this->global_models->get_query("SELECT A.id_mrp_setting_users,A.title,B.name"
        . " FROM mrp_setting_users AS A"
        . " LEFT JOIN m_users AS B ON A.id_users = B.id_users"
//        . " LEFT JOIN hr_master_organisasi AS C ON A.id_hr_master_organisasi = C.id_hr_master_organisasi"
//        . " {$where}"
//        . " ORDER BY A.id_mrp_setting_lock_atk ASC"
        . " LIMIT {$start}, 10"
        );
//    print  $this->db->last_query(); die;
//    $data_array = json_decode($data);
//    $this->debug($data, true);
    

    foreach ($data AS $ky => $da){
       $hasil[$ky] = array(
                $da->title,
                $da->name,
                "<div class='btn-group'>"
                . "<a href='".site_url("mrp/mrp-setting/add-setting-users-request/{$da->id_mrp_setting_users}")."' type='button' title='Edit Setting Users Request' class='btn btn-info btn-flat' style='width: 40px'><i class='fa fa-edit'></i></a>"      
                . "</div>"
              );
            }
       
   if(count($data) > 0){
           $return['status'] = 2;
           $return['start']  = $start + 10; 
    }
    else{
      $return['status'] = 3;
    }     
    $return['hasil'] = $hasil;
//    $this->debug($return, true);
    print json_encode($return);
    die;
  }
  
    function add_row_setting_users(){
    $nomor = $this->input->post("no") + 1;
    print "<div class='input-group margin' id='akses-users-box{$nomor}'>"
      . "<input type='text' class='form-control' id='akses_users{$nomor}' name='akses_users[]'>"
      . "<input type='text' class='form-control' id='id_akses_users{$nomor}' name='id_akses_users[]' style='display: none'>"
      . "<span class='input-group-btn'>"
        . "<a href='javascript:void(0)' class='btn btn-danger btn-flat delete' isi='akses-users-box{$nomor}'>"
          . "<i class='fa fa-fw fa-times'></i>"
        . "</a>"
      . "</span> "
    . "</div>"
    . "<script>"
        . "$(function() {"
          . "$( '#akses_users{$nomor}' ).autocomplete({"
            . "source: '".site_url("mrp/mrp-setting-ajax/get-users")."',"
            . "minLength: 1,"
            . "search  : function(){ $(this).addClass('working');},"
            . "open    : function(){ $(this).removeClass('working');},"
            . "select: function( event, ui ) {"
              . "$('#id_akses_users{$nomor}').val(ui.item.id);"
            . "}"
          . "});"
        . "});"
    . "</script>";
    die;
  }
  
  function get_users(){
    if (empty($_GET['term'])) exit ;
    $q = strtolower($_GET["term"]);
    if (get_magic_quotes_gpc()) $q = stripslashes($q);
    $items = $this->global_models->get_query("
      SELECT A.id_users,A.name,A.email,C.title AS title_organisasi,D.code AS title_company
      FROM m_users AS A
      LEFT JOIN hr_pegawai AS B ON A.id_users = B.id_users
      LEFT JOIN hr_master_organisasi AS C ON B.id_hr_master_organisasi = C.id_hr_master_organisasi
      LEFT JOIN hr_company AS D ON B.id_hr_company = D.id_hr_company
      WHERE 
      A.id_users > 1 AND (LOWER(A.name) LIKE '%{$q}%' OR LOWER(A.email) LIKE '%{$q}%')
      LIMIT 0,10
      ");
    if(count($items) > 0){
      foreach($items as $tms){
          if($tms->title_organisasi){
              $result[] = array(
            "id"    => $tms->id_users,
            "label" => $tms->name." <".$tms->email."><".$tms->title_organisasi."><".$tms->title_company.">",
            "value" => $tms->name." <".$tms->email."><".$tms->title_organisasi."><".$tms->title_company.">",
        );
          }
      }
    }
    else{
      $result[] = array(
          "id"    => 0,
          "label" => "No Found",
          "value" => "No Found",
      );
    }
    echo json_encode($result);
    die;
  }
  
    function get_lock_atk($start = 0){
      
     
//        $no = 0;
//        $aa = "";
//        foreach ($acd as $val) {
//            if($no > 0){
//                $aa .= ",".$val->id_hr_master_organisasi;
//            }else{
//                $aa .= $val->id_hr_master_organisasi;
//            }
//            $no++;
//        }
        $qry = ""; 
//        if($status == 1){
//            $qry .= "A.create_by_users ='{$id_users}'";
//        }elseif($status == 2){
//        if($id_users == 1){
//            $qry = "";
//        }else{
//            if($aa){
//                $qry .= "AND ((A.id_hr_master_organisasi='$dta_id' OR A.id_hr_master_organisasi IN ($aa))OR (A.id_create_by_organisasi='$dta_id' OR A.id_create_by_organisasi IN ($aa)) ";
//            }elseif($this->nbscache->get_olahan("permission", $this->session->userdata("id_privilege"), "proses_ro", "edit") !== FALSE){
//                $qry .= "AND ((A.id_hr_master_organisasi='$dta_id' OR A.status >='3') OR (A.id_create_by_organisasi='$dta_id' OR A.status >='3')) ";
//            }else{
//                $qry .= "AND (A.id_hr_master_organisasi='$dta_id' OR A.id_create_by_organisasi='$dta_id')";
//            }
//        }
            
//        }
        
    $where = "WHERE {$qry}";

     $data = $this->global_models->get_query("SELECT A.id_mrp_setting_lock_atk,A.create_date,A.note,B.title AS perusahaan,C.title AS department"
        . " FROM mrp_setting_lock_atk AS A"
        . " LEFT JOIN hr_company AS B ON A.id_hr_company = B.id_hr_company"
        . " LEFT JOIN hr_master_organisasi AS C ON A.id_hr_master_organisasi = C.id_hr_master_organisasi"
//        . " {$where}"
        . " ORDER BY A.id_mrp_setting_lock_atk ASC"
        . " LIMIT {$start}, 10"
        );
//    print  $this->db->last_query(); die;
//    $data_array = json_decode($data);
//    $this->debug($data, true);
    

    foreach ($data AS $ky => $da){
       $hasil[$ky] = array(
                date('d-M-Y H:i:s', strtotime($da->create_date)),
                $da->perusahaan."<br>Department:".$da->department,
                $da->note,
                "<div class='btn-group'>"
                  . "<a href='".site_url("mrp/mrp-setting/add-lock-atk/{$da->id_mrp_setting_lock_atk}")."' type='button' title='Edit Lock ATK' class='btn btn-info btn-flat' style='width: 40px'><i class='fa fa-edit'></i></a>"      
                . "</div>"
              );
                
            }
       
   if(count($data) > 0){
           $return['status'] = 2;
           $return['start']  = $start + 10; 
    }
    else{
      $return['status'] = 3;
    }     
    $return['hasil'] = $hasil;
//    $this->debug($return, true);
    print json_encode($return);
    die;
  }
  
   function get_form_lock_atk($id_mrp_setting_lock_atk = 0,$start = 0){
      
      $status = $this->global_models->get_field("mrp_request", "status", 
                    array("id_mrp_request" => "{$id_mrp_request}"));
                    
      $dt_user = $this->global_models->get_field("mrp_request", "create_by_users", 
                    array("id_mrp_request" => "{$id_mrp_request}"));              
      
      $where = "WHERE A.status = 1 AND B.id_mrp_type_inventory = 2";
    
//      if($status == 1 AND  $this->session->userdata("id") != $dt_user){
//          $where .= " AND jumlah IS NOT NULL";
//          $disabled = "disabled ";  
//      }elseif($status > 1){
//          $where .= " AND jumlah IS NOT NULL";
//          $disabled = "disabled ";  
//      }
////      
//   
//       if($this->session->userdata("id") == 1 OR ($status == 2 AND $this->nbscache->get_olahan("permission", $this->session->userdata("id_privilege"), "approval-ro", "edit") !== FALSE)){
//       $disabled ="";
//      }
//      
//      if($this->session->userdata("id") == 1 OR $this->nbscache->get_olahan("permission", $this->session->userdata("id_privilege"), "edit-form-ro", "edit") !== FALSE){
//       $disabled ="";
//      }
      
      $data = $this->global_models->get_query("SELECT B.name AS inventory_umum,C.title AS brand,D.title AS satuan,"
        . "A.id_mrp_inventory_spesifik,A.title AS title_spesifik,E.id_mrp_inventory_spesifik AS id"
        . " FROM mrp_inventory_spesifik AS A"
        . " LEFT JOIN mrp_inventory_umum AS B ON A.id_mrp_inventory_umum = B.id_mrp_inventory_umum"
        . " LEFT JOIN mrp_brand AS C ON A.id_mrp_brand = C.id_mrp_brand"
        . " LEFT JOIN mrp_satuan AS D ON A.id_mrp_satuan = D.id_mrp_satuan"
        . " LEFT JOIN mrp_setting_lock_atk_asset AS E ON (A.id_mrp_inventory_spesifik = E.id_mrp_inventory_spesifik AND E.id_mrp_setting_lock_atk = '{$id_mrp_setting_lock_atk}')"
        . " LEFT JOIN mrp_setting_lock_atk AS F ON E.id_mrp_setting_lock_atk = F.id_mrp_setting_lock_atk"
        . " {$where} "
        . " ORDER BY A.id_mrp_inventory_spesifik ASC"
        . " LIMIT {$start}, 10");
        
//       print $this->db->last_query(); die;
//    $data_array = json_decode($data);
//    $this->debug($data, true);
        
    
    if(count($data) > 0){
      $return['status'] = 2;
      $return['start']  = $start + 10;
    }
    else{
      $return['status'] = 3;
    }
    
//    $jenis = array(1 => "Habis Pakai", 2 => "Asset");
    $no = $start;
    foreach ($data AS $da){
       
        $id = " id=".$da->id_mrp_inventory_spesifik;
        if($da->id == $da->id_mrp_inventory_spesifik){
            $check = " checked";
        }else{
            $check ="";
        }
        $data_id = "id=dt_id_mrp_inventory_spesifik_".$da->id_mrp_inventory_spesifik;
        $data_jumlah = "id=jumlah".$da->id_mrp_inventory_spesifik;
      $hasil[] = array(
          $no =$no + 1,
        $da->inventory_umum." ".$da->title_spesifik,
        $da->brand,
        $da->satuan, 
        $this->form_eksternal->form_input("id_mrp_inventory_spesifik[]", $da->id_mrp_inventory_spesifik, $data_id.' style="width:100px;display:none" class="form-control id_spesifik2 input-sm" placeholder=""').
        $this->form_eksternal->form_checkbox('status', $da->id_mrp_inventory_spesifik, FALSE,$id.$check.' class="form-control"'), 
//        "<div class='btn-group'>"
//          . "<a href='".site_url("mrp/mrp-master/add-inventory-spesifik/{$da->id_mrp_request}")."' type='button' class='btn btn-info btn-flat' style='width: 40px'><i class='fa fa-edit'></i></a>"
//        . "</div>"
      );
    }
    $return['hasil'] = $hasil;
//    $this->debug($return, true);
    print json_encode($return);
    die;
  }
  
     function get_view_lock_atk($id_mrp_setting_lock_atk = 0,$start = 0){
      
      $where = "WHERE A.status = 1 AND B.id_mrp_type_inventory = 2 AND E.id_mrp_setting_lock_atk='{$id_mrp_setting_lock_atk}'";
    
//      if($status == 1 AND  $this->session->userdata("id") != $dt_user){
//          $where .= " AND jumlah IS NOT NULL";
//          $disabled = "disabled ";  
//      }elseif($status > 1){
//          $where .= " AND jumlah IS NOT NULL";
//          $disabled = "disabled ";  
//      }
////      
//   
//       if($this->session->userdata("id") == 1 OR ($status == 2 AND $this->nbscache->get_olahan("permission", $this->session->userdata("id_privilege"), "approval-ro", "edit") !== FALSE)){
//       $disabled ="";
//      }
//      
//      if($this->session->userdata("id") == 1 OR $this->nbscache->get_olahan("permission", $this->session->userdata("id_privilege"), "edit-form-ro", "edit") !== FALSE){
//       $disabled ="";
//      }
      
      $data = $this->global_models->get_query("SELECT B.name AS inventory_umum,C.title AS brand,D.title AS satuan,"
        . "A.id_mrp_inventory_spesifik,A.title AS title_spesifik,E.id_mrp_inventory_spesifik AS id"
        . " FROM mrp_inventory_spesifik AS A"
        . " LEFT JOIN mrp_inventory_umum AS B ON A.id_mrp_inventory_umum = B.id_mrp_inventory_umum"
        . " LEFT JOIN mrp_brand AS C ON A.id_mrp_brand = C.id_mrp_brand"
        . " LEFT JOIN mrp_satuan AS D ON A.id_mrp_satuan = D.id_mrp_satuan"
        . " LEFT JOIN mrp_setting_lock_atk_asset AS E ON A.id_mrp_inventory_spesifik = E.id_mrp_inventory_spesifik"
        . " LEFT JOIN mrp_setting_lock_atk AS F ON E.id_mrp_setting_lock_atk = F.id_mrp_setting_lock_atk"
        . " {$where} "
        . " ORDER BY A.id_mrp_inventory_spesifik ASC"
        . " LIMIT {$start}, 10");
        
//       print $this->db->last_query(); die;
//    $data_array = json_decode($data);
//    $this->debug($data, true);
        
    
    if(count($data) > 0){
      $return['status'] = 2;
      $return['start']  = $start + 10;
    }
    else{
      $return['status'] = 3;
    }
    
//    $jenis = array(1 => "Habis Pakai", 2 => "Asset");
    $no = $start;
    foreach ($data AS $da){
       
        $id = " id=".$da->id_mrp_inventory_spesifik;
        if($da->id == $da->id_mrp_inventory_spesifik){
            $check = " checked";
        }else{
            $check ="";
        }
        $data_id = "id=dt_id_mrp_inventory_spesifik_".$da->id_mrp_inventory_spesifik;
        $data_jumlah = "id=jumlah".$da->id_mrp_inventory_spesifik;
      $hasil[] = array(
          $no =$no + 1,
        $da->inventory_umum." ".$da->title_spesifik,
        $da->brand,
        $da->satuan
      );
    }
    $return['hasil'] = $hasil;
//    $this->debug($return, true);
    print json_encode($return);
    die;
  }
  
    function ajax_company_direktorat($id_hr_company_department = 0){
     $pst = $this->input->post(NULL);
     $pos = $_POST['name'];
     $flag =    $_POST['flag'];
      $users_1 = $this->global_models->get_query("
      SELECT  B.id_hr_master_organisasi,B.title
      FROM hr_company_direktorat AS A
      LEFT JOIN hr_master_organisasi AS B ON A.id_hr_master_organisasi = B.id_hr_master_organisasi
      WHERE A.id_hr_company = '{$pos}'
      ");
      $html = "";
      $aa[0] ="- Pilih -";
    
       foreach ($users_1 as $key => $value) {
          
        $aa[$value->id_hr_master_organisasi] = $value->title;
       }
       
    $html = "<div class='col-xs-12'>"
          . "<label>Direktorat</label>";
        $html .= $this->form_eksternal->form_dropdown('id_hr_master_organisasi[]', $aa, 
              array($id_hr_company_department), 'id="id_direktorat" class=" form-control dropdown2 input-sm"');
    $html .= "</div>";
   
     $html .= "<script>";
     if($flag != 1){
         $html .="department({$id_hr_company_department});";
     }
          
          $html .="$('.dropdown2').select2();"
             ."$('#id_direktorat').change(function(){"
                 ." var id=$(this).val();"
//                 ."department(id);"
                 ."divisi(0,1);"
                ."section(0,1);"
         ."var dataString2 = 'name='+id+'&flag=1';"
         ."$.ajax"
            ."({"
            ."type: 'POST',"
            ."url: '".site_url("hr/hr-ajax/ajax-company-department/0")."',"
            ."data: dataString2,"
            ."cache: false,"
            ."success: function(html)"
            ."{"
            ."$('#dt_department').html(html);"
            ."}"
            ."});"
                ."});"
            . "</script>";
    print $html;
    die;
  }
  
   function ajax_company_department($id_hr_company_department = 0){
      $pst = $this->input->post(NULL);
     $pos = $_POST['name'];
      $flag =    $_POST['flag'];
      $users_1 = $this->global_models->get_query("
      SELECT  A.id_hr_master_organisasi,A.title
      FROM hr_master_organisasi AS A
      WHERE A.parent = '{$pos}' AND level=2
      ");
//      print $csd =$this->db->last_query();
//      die;
      $html = "";
      $aa[0] ="- Pilih -";
    
       foreach ($users_1 as $key => $value) {
          
        $aa[$value->id_hr_master_organisasi] = $value->title;
       }
       
    $html = "<div class='col-xs-12'>"
          . "<label>Department</label>";
        $html .= $this->form_eksternal->form_dropdown('id_hr_master_organisasi[]', $aa, 
              array($id_hr_company_department), 'id="id_department" class=" form-control dropdown2 input-sm"');
    $html .= "</div>";
   
     $html .= "<script>";
     if($flag != 1){
          $html .=  "divisi({$id_hr_company_department});";
     }    
          $html .=  "$('.dropdown2').select2();"
        ."$('#id_department').change(function(){"
            ." var id=$(this).val();"
//              . "alert(id);"
//            ."divisi(id);"
            ."var dataString2 = 'name='+ id+'&flag=1';"
            ."$.ajax"
               ."({"
               ."type: 'POST',"
               ."url: '".site_url("hr/hr-ajax/ajax-company-divisi/0")."',"
               ."data: dataString2,"
               ."cache: false,"
               ."success: function(html)"
               ."{"
               ."$('#dt_divisi').html(html);"
               ."}"
               ."});"
            ."section(0);" 
           ."});"
            . "</script>";
    print $html;
    die;
  }
  
    function ajax_company_divisi($id_hr_company_department = 0){
      $pst = $this->input->post(NULL);
     $pos = $_POST['name'];
      $flag =    $_POST['flag'];
//     print $pos;
//     print_r($pst); die;
//     $id_mrp_inventory = $_POST['id_mrp_company'];
     
//    print $pos.$id_mrp_inventory; die;
//     $alamat = $this->global_models->get_field("master_company", "address", array("id_master_company" => "{$pos}"));

      $users_1 = $this->global_models->get_query("
       SELECT  A.id_hr_master_organisasi,A.title
      FROM hr_master_organisasi AS A
      WHERE A.parent = '{$pos}' AND level=3
      ");
      $html = "";
      $aa[0] ="- Pilih -";
    
       foreach ($users_1 as $key => $value) {
          
        $aa[$value->id_hr_master_organisasi] = $value->title;
       }
       
    $html = "<div class='col-xs-12'>"
          . "<label>Divisi</label>";
        $html .= $this->form_eksternal->form_dropdown('id_hr_master_organisasi[]', $aa, 
              array($id_hr_company_department), 'id="id_divisi" class=" form-control dropdown2 input-sm"');
    $html .= "</div>";
   
     $html .= "<script>";
     if($flag != 1){
             $html .= "section({$id_hr_company_department});";
     }
             $html .= "$('.dropdown2').select2();"
             ."$('#id_divisi').change(function(){"
            ." var id=$(this).val();"
//              . "alert(id);"
//            ."section(id);"
         ."var dataString2 = 'name='+ id+'&flag=1';"
         ."$.ajax"
            ."({"
            ."type: 'POST',"
            ."url: '".site_url("hr/hr-ajax/ajax-company-section/0")."',"
            ."data: dataString2,"
            ."cache: false,"
            ."success: function(html)"
            ."{"
            ."$('#dt_section').html(html);"
            ."}"
            ."});"
                     
           ."});"
            . "</script>";
    print $html;
    die;
  }
  
   function ajax_company_section($id_hr_company_department = 0){
      $pst = $this->input->post(NULL);
     $pos = $_POST['name'];
      $flag =    $_POST['flag'];
//     print $pos;
//     print_r($pst); die;
//     $id_mrp_inventory = $_POST['id_mrp_company'];
     
//    print $pos.$id_mrp_inventory; die;
//     $alamat = $this->global_models->get_field("master_company", "address", array("id_master_company" => "{$pos}"));

      $users_1 = $this->global_models->get_query("
       SELECT  A.id_hr_master_organisasi,A.title
      FROM hr_master_organisasi AS A
      WHERE A.parent = '{$pos}' AND level=4
      ");
      $html = "";
      $aa[0] ="- Pilih -";
    
       foreach ($users_1 as $key => $value) {
        $aa[$value->id_hr_master_organisasi] = $value->title;
       }
       if($flag == 1){
           $id_hr_company_department = 0;
       }else{
           $id_hr_company_department = $id_hr_company_department;
       }
    $html = "<div class='col-xs-12'>"
          . "<label>Section</label>";
        $html .= $this->form_eksternal->form_dropdown('id_hr_master_organisasi[]', $aa, 
              array($id_hr_company_department), 'id="id_section" class=" form-control dropdown2 input-sm"');
    $html .= "</div>";
   
     $html .= "<script>"
          . "$('.dropdown2').select2();"
            . "</script>";
    print $html;
    die;
  }
  
     function ajax_divisi($divisi){
      $pst = $this->input->post(NULL);
     $pos = $_POST['id_department'];

     $data = $this->global_models->get_query("SELECT  A.id_hr_master_organisasi,A.title"
        . " FROM hr_master_organisasi AS A"
        . " WHERE A.level=3 AND A.parent='{$pos}'"
        . " ORDER BY A.title ASC");
        
    
      $html = "";
//      $aa[0] ="- Pilih -";
    
       foreach ($data as $key => $value) {
        $aa[$value->id_hr_master_organisasi] = $value->title;
       }
     
//       $departm = $this->global_models->get_field("hr_master_organisasi", "parent", array("id_hr_master_organisasi" => "{$section}"));

    $html = "<div class='col-xs-12'>"
          . "<label>Divisi</label>";
        $html .= $this->form_eksternal->form_dropdown('divisi', $aa, 
              $divisi, 'id="id_divisi" class=" form-control dropdown2 input-sm"');
    $html .= "</div>";
   
     $html .= "<script>"
             ."$('#id_department').change(function(){"
                 ." var id=$(this).val();"
                 ."divisi(id);"
                ."});"
          . "$('.dropdown2').select2();"
            . "</script>";
    print $html;
    die;
  }
  
    function insert_form_lock_atk($id_mrp_setting_lock_atk = 0){
     $id_mrp_request = 0;
     
        $id_spesifik = $_POST['id_spesifik'];
        $id_company =  $_POST['id_company'];
        $note =  $_POST['note'];
        $id_hr_master_organisasi =  $_POST['id_hr_master_organisasi'];
   
     
    $arr_id = explode(",",$id_spesifik);
    $id_master = explode(",",$id_hr_master_organisasi);

    $id_hr_master_organisasi = end(array_filter($id_master));
    
    if($id_mrp_setting_lock_atk > 0){
        
        $kirim = array(
            "id_hr_company"                 => $id_company,
            "id_hr_master_organisasi"       => $id_hr_master_organisasi,
            "note"                          => $note,
            "update_by_users"               => $this->session->userdata("id"),
            "update_date"                   => date("Y-m-d H:i:s")
        );
        $this->global_models->update("mrp_setting_lock_atk", array("id_mrp_setting_lock_atk" => $id_mrp_setting_lock_atk),$kirim);
       
        $this->global_models->delete("mrp_setting_lock_atk_asset", array("id_mrp_setting_lock_atk" => $id_mrp_setting_lock_atk));
        foreach ($arr_id as $key => $val2) {
            if($val2 > 0){
                    $kirim = array(
                    "id_mrp_setting_lock_atk"       => $id_mrp_setting_lock_atk,
                    "id_mrp_inventory_spesifik"     => $val2,
                    "create_by_users"               => $this->session->userdata("id"),
                    "create_date"                   => date("Y-m-d H:i:s")
                );
                $this->global_models->insert("mrp_setting_lock_atk_asset", $kirim);
            }
        }
    }else{
        
       
        $kirim = array(
            "id_hr_company"                     => $id_company,
            "id_hr_master_organisasi"           => $id_hr_master_organisasi,
            "note"                              => $note,
            "create_by_users"                   => $this->session->userdata("id"),
            "create_date"                       => date("Y-m-d H:i:s")
        );
        $id_mrp_setting_lock_atk = $this->global_models->insert("mrp_setting_lock_atk", $kirim);
        
         foreach ($arr_id as $key => $val) {
            if($val > 0){
                $kirim = array(
                "id_mrp_setting_lock_atk"       => $id_mrp_setting_lock_atk,
                "id_mrp_inventory_spesifik"     => $val,
                "create_by_users"               => $this->session->userdata("id"),
                "create_date"                   => date("Y-m-d H:i:s")
            );
            $this->global_models->insert("mrp_setting_lock_atk_asset", $kirim);
            }
        }
    }
     
   
//    if($id_mrp_request > 0){
        $this->session->set_flashdata('success', 'Data tersimpan');
        
//    }
//     $array =array($cc,$kk,$note);
//     print_r($array);
//         $return['hasil'] = 1;
//    $this->debug($return, true);
        $return['id_mrp_setting_lock_atk'] = $id_mrp_setting_lock_atk;
    print json_encode($return);
    die;
  }
  
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */