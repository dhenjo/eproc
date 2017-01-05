<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Hr_ajax extends MX_Controller {
  function __construct() {
    $this->menu = $this->cek();
  }
  
  function get_hr_master_pegawai($start = 0){
      
      $where = "WHERE 1=1";
     if($this->session->userdata('pegawai_search_user')){
        $where .= " AND A.id_users = '{$this->session->userdata('pegawai_search_user')}'";
      }
      
      if($this->session->userdata('pegawai_search_nip')){
        $where .= " AND A.nip LIKE '%{$this->session->userdata('pegawai_search_nip')}%'";
      }
      
       if($this->session->userdata('pegawai_search_company')){
        $where .= " AND A.id_hr_company = '{$this->session->userdata('pegawai_search_company')}'";
      }
      
      if($this->session->userdata('pegawai_search_department')){
        $where .= " AND A.id_hr_department = '{$this->session->userdata('pegawai_search_department')}'";
      }
      
    $data = $this->global_models->get_query("SELECT  A.nip,A.id_hr_pegawai,B.name,C.title AS nama_company,E.title AS nama_department"
        . " FROM hr_pegawai AS A"
        . " LEFT JOIN m_users AS B ON A.id_users = B.id_users"
        . " LEFT JOIN hr_company AS C ON A.id_hr_company = C.id_hr_company"
        . " LEFT JOIN hr_master_organisasi AS E ON A.id_hr_master_organisasi = E.id_hr_master_organisasi"
        . " {$where}"
//        . " GROUP BY A.id_hr_pegawai"
        . " ORDER BY A.id_hr_pegawai ASC"
        . " LIMIT {$start}, 10");
//     print $cc =$this->db->last_query();  
//     die;
//    $data_array = json_decode($data);
//    $this->debug($data, true);
    if(count($data) > 0){
      $return['status'] = 2;
      $return['start']  = $start + 10;
    }
    else{
      $return['status'] = 3;
    }
    
    
    foreach ($data AS $da){
         if($this->session->userdata("id") == 1){
         $sign = "<a href='".site_url("hr/hr-master/pegawai-signature/{$da->id_hr_pegawai}")."' type='button' title='signature' class='btn btn-warning btn-flat' style='width: 40px'><i class='fa fa-key'></i></a>";
         }
      $hasil[] = array(
        $da->name,
        $da->nama_company,
        $da->nama_department,
        $da->nip,
        "<div class='btn-group'>"
        . "<a href='".site_url("hr/hr-master/add-pegawai/{$da->id_hr_pegawai}")."' type='button' title='Update Data Pegawai' class='btn btn-info btn-flat' style='width: 40px'><i class='fa fa-edit'></i></a>"
        . $sign
        . "</div>"
      );
    }
    $return['hasil'] = $hasil;
//    $this->debug($return, true);
    print json_encode($return);
    die;
  }
  
  function get_hr_master_direktorat($start = 0){
      
    $data = $this->global_models->get_query("SELECT  id_hr_master_organisasi,title,code"
        . " FROM hr_master_organisasi AS A"
        . " WHERE level = 1"
        . " ORDER BY A.id_hr_master_organisasi ASC"
        . " LIMIT {$start}, 10");
        
//    $data_array = json_decode($data);
//    $this->debug($data, true);
    if(count($data) > 0){
      $return['status'] = 2;
      $return['start']  = $start + 10;
    }
    else{
      $return['status'] = 3;
    }
    
    
    foreach ($data AS $da){
        if($this->session->userdata("id") == 1){
        $btn_del = "<a href='".site_url("hr/hr-master/delete-master-organisasi/{$da->id_hr_master_organisasi}/direktorat")."' type='button' class='btn btn-danger btn-flat' style='width: 40px'><i class='fa fa-trash-o'></i></a>";
        }
      $hasil[] = array(
        $da->title,
        $da->code,
        "<div class='btn-group'>"
          . "<a href='".site_url("hr/hr-master/add-direktorat/{$da->id_hr_master_organisasi}")."' type='button' class='btn btn-info btn-flat' style='width: 40px'><i class='fa fa-edit'></i></a>"
          . $btn_del        
        . "</div>"
      );
    }
    $return['hasil'] = $hasil;
//    $this->debug($return, true);
    print json_encode($return);
    die;
  }
  
    function get_hr_master_section($start = 0){
      
   $data = $this->global_models->get_query("SELECT  A.id_hr_master_organisasi,A.title,A.code,B.title AS divisi,C.title AS department,D.title AS direktorat"
        . " FROM hr_master_organisasi AS A"
        . " LEFT JOIN hr_master_organisasi AS B ON A.parent = B.id_hr_master_organisasi"
        . " LEFT JOIN hr_master_organisasi AS C ON B.parent = C.id_hr_master_organisasi"
        . " LEFT JOIN hr_master_organisasi AS D ON C.parent = D.id_hr_master_organisasi"  
        . " WHERE A.level=4"
        . " ORDER BY A.id_hr_master_organisasi ASC"
        . " LIMIT {$start}, 10");
        
//    $data_array = json_decode($data);
//    $this->debug($data, true);
    if(count($data) > 0){
      $return['status'] = 2;
      $return['start']  = $start + 10;
    }
    else{
      $return['status'] = 3;
    }
    
    
    foreach ($data AS $da){
        if($this->session->userdata("id") == 1){
        $btn_del = "<a href='".site_url("hr/hr-master/delete-master-organisasi/{$da->id_hr_master_organisasi}/section")."' type='button' class='btn btn-danger btn-flat' style='width: 40px'><i class='fa fa-trash-o'></i></a>";
    }
      $hasil[] = array(
        $da->title,
        $da->code,
        $da->direktorat,
        $da->department,
        $da->divisi,  
        "<div class='btn-group'>"
          . "<a href='".site_url("hr/hr-master/add-section/{$da->id_hr_master_organisasi}")."' type='button' class='btn btn-info btn-flat' style='width: 40px'><i class='fa fa-edit'></i></a>"
          . $btn_del
          . "</div>"
      );
    }
    $return['hasil'] = $hasil;
//    $this->debug($return, true);
    print json_encode($return);
    die;
  }
  
   function get_hr_master_mo_department($start = 0){
      
    $data = $this->global_models->get_query("SELECT  A.id_hr_master_organisasi,A.title,A.code,B.title AS direktorat"
        . " FROM hr_master_organisasi AS A"
        . " LEFT JOIN hr_master_organisasi AS B ON A.parent = B.id_hr_master_organisasi"
//        . " LEFT JOIN hr_master_organisasi AS C ON B.parent = C.id_hr_master_organisasi"
        . " WHERE A.level=2"
        . " ORDER BY A.id_hr_master_organisasi ASC"
        . " LIMIT {$start}, 10");
        
//    $data_array = json_decode($data);
//    $this->debug($data, true);
    if(count($data) > 0){
      $return['status'] = 2;
      $return['start']  = $start + 10;
    }
    else{
      $return['status'] = 3;
    }
    
    
    foreach ($data AS $da){
         if($this->session->userdata("id") == 1){
        $btn_del = "<a href='".site_url("hr/hr-master/delete-master-organisasi/{$da->id_hr_master_organisasi}/mo-department")."' type='button' class='btn btn-danger btn-flat' style='width: 40px'><i class='fa fa-trash-o'></i></a>";
    }
      $hasil[] = array(
        $da->title,
        $da->code,
        $da->direktorat,
        "<div class='btn-group'>"
          . "<a href='".site_url("hr/hr-master/add-mo-department/{$da->id_hr_master_organisasi}")."' type='button' class='btn btn-info btn-flat' style='width: 40px'><i class='fa fa-edit'></i></a>"
          . $btn_del
        . "</div>"
      );
    }
    $return['hasil'] = $hasil;
//    $this->debug($return, true);
    print json_encode($return);
    die;
  }
  
  function get_hr_master_divisi($start = 0){
      
    $data = $this->global_models->get_query("SELECT  A.id_hr_master_organisasi,A.title,A.code,B.title AS department,C.title AS direktorat"
        . " FROM hr_master_organisasi AS A"
        . " LEFT JOIN hr_master_organisasi AS B ON A.parent = B.id_hr_master_organisasi"
        . " LEFT JOIN hr_master_organisasi AS C ON B.parent = C.id_hr_master_organisasi"   
        . " WHERE A.level=3"
        . " ORDER BY A.id_hr_master_organisasi ASC"
        . " LIMIT {$start}, 10");
        
//    $data_array = json_decode($data);
//    $this->debug($data, true);
    if(count($data) > 0){
      $return['status'] = 2;
      $return['start']  = $start + 10;
    }
    else{
      $return['status'] = 3;
    }
    
    foreach ($data AS $da){
         if($this->session->userdata("id") == 1){
        $btn_del = "<a href='".site_url("hr/hr-master/delete-master-organisasi/{$da->id_hr_master_organisasi}/divisi")."' type='button' class='btn btn-danger btn-flat' style='width: 40px'><i class='fa fa-trash-o'></i></a>";
        }
      $hasil[] = array(
        $da->title,
        $da->code,
        $da->direktorat,
        $da->department,  
        "<div class='btn-group'>"
          . "<a href='".site_url("hr/hr-master/add-divisi/{$da->id_hr_master_organisasi}")."' type='button' class='btn btn-info btn-flat' style='width: 40px'><i class='fa fa-edit'></i></a>"
          . $btn_del
          . "</div>"
      );
    }
    $return['hasil'] = $hasil;
//    $this->debug($return, true);
    print json_encode($return);
    die;
  }
  
   function add_row_direktorat(){
    $nomor = $this->input->post("no") + 1;
    print "<div class='input-group margin' id='direktorat-box{$nomor}'>"
      . "<input type='text' class='form-control' id='direktorat{$nomor}' name='direktorat[]'>"
      . "<input type='text' class='form-control' id='id_direktorat{$nomor}' name='id_direktorat[]' style='display: none'>"
      . "<span class='input-group-btn'>"
        . "<a href='javascript:void(0)' class='btn btn-danger btn-flat delete' isi='direktorat-box{$nomor}'>"
          . "<i class='fa fa-fw fa-times'></i>"
        . "</a>"
      . "</span> "
    . "</div>"
    . "<script>"
        . "$(function() {"
          . "$( '#direktorat{$nomor}' ).autocomplete({"
            . "source: '".site_url("hr/hr-ajax/get-hr-master-company-direktorat")."',"
            . "minLength: 1,"
            . "search  : function(){ $(this).addClass('working');},"
            . "open    : function(){ $(this).removeClass('working');},"
            . "select: function( event, ui ) {"
              . "$('#id_direktorat{$nomor}').val(ui.item.id);"
            . "}"
          . "});"
        . "});"
    . "</script>";
    die;
  }
  
  function get_hr_master_company_direktorat(){
    if (empty($_GET['term'])) exit ;
    $q = strtolower($_GET["term"]);
    if (get_magic_quotes_gpc()) $q = stripslashes($q);
    $items = $this->global_models->get_query("
      SELECT *
      FROM hr_master_organisasi
      WHERE 
      level=1 AND (LOWER(title) LIKE '%{$q}%' OR LOWER(code) LIKE '%{$q}%')
      LIMIT 0,10
      ");
    if(count($items) > 0){
      foreach($items as $tms){
        $result[] = array(
            "id"    => $tms->id_hr_master_organisasi,
            "label" => $tms->title." <".$tms->code.">",
            "value" => $tms->title." <".$tms->code.">",
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
  
  function get_users(){
    if (empty($_GET['term'])) exit ;
    $q = strtolower($_GET["term"]);
    if (get_magic_quotes_gpc()) $q = stripslashes($q);
    $items = $this->global_models->get_query("
      SELECT *
      FROM m_users
      WHERE 
      id_users > 1 AND (LOWER(name) LIKE '%{$q}%' OR LOWER(email) LIKE '%{$q}%')
      LIMIT 0,10
      ");
    if(count($items) > 0){
      foreach($items as $tms){
        $result[] = array(
            "id"    => $tms->id_users,
            "label" => $tms->name." <".$tms->email.">",
            "value" => $tms->name." <".$tms->email.">",
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
       
    $html = "<div class='control-group'>"
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
       
    $html = "<div class='control-group'>"
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
       
    $html = "<div class='control-group'>"
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
    $html = "<div class='control-group'>"
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

    $html = "<div class='control-group'>"
          . "<label>Divisi</label>";
        $html .= $this->form_eksternal->form_dropdown('divisi', $aa, 
              $divisi, 'id="id_divisi" class=" form-control dropdown2 input-sm"');
    $html .= "</div>";
   
     $html .= "<script>"
             ."$('#id_department').change(function(){"
                 ." var id=$(this).val();"
//                 . "alert(id);"
                 ."divisi(id);"
                ."});"
          . "$('.dropdown2').select2();"
//             ." var id_dept=$('#id_section').val();"
////              . "alert(id_dept);"
//             . "department(id_dept);"
//               ."function department(id){"
//      ."var dataString2 = 'id_department='+ id;"
//            
//         ."$.ajax"
//            ."({"
//            ."type: 'POST',"
//            ."url: '".site_url("hr/hr-ajax/ajax-department/{$departm}")."',"
//            ."data: dataString2,"
//            ."cache: false,"
//            ."success: function(html)"
//            ."{"
//            ."$('#dt_department').html(html);"
//            ."}"
//            ."});"
//        ."}"
            . "</script>";
    print $html;
    die;
  }
  
   function ajax_department($department){
      $pst = $this->input->post(NULL);
     $pos = $_POST['id_direktorat'];

     $data = $this->global_models->get_query("SELECT  A.id_hr_master_organisasi,A.title"
        . " FROM hr_master_organisasi AS A"
        . " WHERE A.level=2 AND A.parent='{$pos}'"
        . " ORDER BY A.title ASC");
        
    
      $html = "";
//      $aa[0] ="- Pilih -";
    
       foreach ($data as $key => $value) {
        $aa[$value->id_hr_master_organisasi] = $value->title;
       }
       
    $html = "<div class='control-group'>"
          . "<label>Department</label>";
        $html .= $this->form_eksternal->form_dropdown('department', $aa, 
              $department, 'id="id_department" class=" form-control dropdown2 input-sm"');
    $html .= "</div>";
   
     $html .= "<script>"
          . "$('.dropdown2').select2();"
            . "</script>";
    print $html;
    die;
  }
  
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */