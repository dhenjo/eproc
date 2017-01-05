<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mrp_ajax extends MX_Controller {
  function __construct() {
    $this->menu = $this->cek();
  }
  
  function get_mrp_master_list_files_upload_xls($id_mrp_supplier = 0,$start = 0){
      
      $where = "WHERE 1=1 AND A.status <=2 AND id_mrp_supplier ='{$id_mrp_supplier}'";
     
    $data = $this->global_models->get_query("SELECT  A.id_file_xls,A.file,A.create_by_users,A.create_date,A.status"
        . " FROM file_xls AS A"
        . " {$where}"
        . " ORDER BY A.id_file_xls DESC"
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
    
    $status = array( 1=> "<span class='label bg-orange'>Belum Di Proses</span>", 2 => "<span class='label bg-green'>Sudah Di Proses</span>");
    
    
    foreach ($data AS $da){
        if($da->status > 1){
            $btn = "<a href='".site_url("mrp/mrp-master/delete-proses-file/{$da->id_file_xls}")."' type='button' class='btn btn-danger btn-sm' style='width: 40px' title='Delete File'><i class='fa fa-trash-o'></i></a>";
        }else{
           $btn = "<a href='".site_url("mrp/mrp-master/proses-file/{$da->id_file_xls}")."' type='button' class='btn btn-info btn-sm' style='width: 40px' title='Proses File'><i class='fa fa-check'></i></a>" 
           . "<a href='".site_url("mrp/mrp-master/delete-proses-file/{$da->id_file_xls}")."' type='button' class='btn btn-danger btn-sm' style='width: 40px' title='Delete File'><i class='fa fa-trash-o'></i></a>"; 
        }
        $files = base_url("files/antavaya/upload_xls/{$da->file}");
        $name = $this->global_models->get_field("m_users","name",array("id_users" => $da->create_by_users));
         $tgl = date("d F Y H:i:s", strtotime($da->create_date));
      $hasil[] = array(
        "<a href='{$files}' >{$da->file}</a>",
        $tgl."<br>".$name,
        $status[$da->status],  
        "<div class='btn-group'>"
          . $btn
        . "</div>"
      );
    }
    $return['hasil'] = $hasil;
//    $this->debug($return, true);
    print json_encode($return);
    die;
  }
  
  function get_mrp_master_supplier($start = 0){
      
      $where = "WHERE 1=1 AND A.status < 2";
     if($this->session->userdata('supplier_search_name')){
        $where .= " AND A.name like '%{$this->session->userdata('supplier_search_name')}%'";
      }
      
      if($this->session->userdata('supplier_search_pic')){
        $where .= " AND A.pic like '%{$this->session->userdata('supplier_search_pic')}%'";
      }
      
       if($this->session->userdata('supplier_search_phone')){
        $where .= " AND A.phone like '%{$this->session->userdata('supplier_search_phone')}%'";
      }
      
      if($this->session->userdata('supplier_search_fax')){
        $where .= " AND A.fax like '%{$this->session->userdata('supplier_search_fax')}%'";
      }
      
      if($this->session->userdata('supplier_search_email')){
        $where .= " AND A.email like '%{$this->session->userdata('supplier_search_email')}%'";
      }
      
      if($this->session->userdata('supplier_search_website')){
        $where .= " AND A.website like '%{$this->session->userdata('supplier_search_website')}%'";
      }
      
      if($this->session->userdata('supplier_search_address')){
        $where .= " AND A.address like '%{$this->session->userdata('supplier_search_address')}%'";
      }
      
    $data = $this->global_models->get_query("SELECT  A.id_mrp_supplier,A.name,A.pic,"
        . "A.phone,A.fax,A.email,A.website,A.address"
        . " FROM mrp_supplier AS A"
        . " {$where}"
        . " ORDER BY A.id_mrp_supplier ASC"
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
       
         $btn2 = "<div class='btn-group'>"
               . "<button type='button' class='btn btn-info dropdown-toggle' data-toggle='dropdown'>"
                . "<span class='caret'></span>"
                . "<span class='sr-only'>Toggle Dropdown</span>"
                . "</button>"
                . "<ul class='dropdown-menu' role='menu'>"
                . "<li><a href='".site_url("mrp/mrp-master/add-supplier/{$da->id_mrp_supplier}")."'>Edit Supplier</a></li>"
                . "<li><a href='".site_url("mrp/mrp-master/supplier-inventory/{$da->id_mrp_supplier}")."' type='button'>Product Supplier</a></li>"
                . "<li class='divider'></li>"
                . "<li><a href='".site_url("mrp/mrp-master/delete-supplier/{$da->id_mrp_supplier}")."'>Delete</li>"       
                . "</ul>"
                 . "</div>";
                
      $hasil[] = array(
        $da->name,
        $da->pic,
        $da->phone,
        $da->fax,
        $da->email, 
        $da->website,  
        $da->address,  
        $btn2
      );
    }
    $return['hasil'] = $hasil;
//    $this->debug($return, true);
    print json_encode($return);
    die;
  }
  
    function ajax_master_inventory_spesifik_supplier($id_mrp_spesifik = 0,$start = 0){
      
      $where = " WHERE A.id_mrp_inventory_spesifik ='{$id_mrp_spesifik}'";
     $data = $this->global_models->get_query("SELECT D.name AS name_supplier,A.harga"
        . " ,C.name AS umum,B.title AS spesifik"
        . " FROM mrp_supplier_inventory AS A"
        . " LEFT JOIN mrp_inventory_spesifik AS B ON A.id_mrp_inventory_spesifik = B.id_mrp_inventory_spesifik"
        . " LEFT JOIN mrp_inventory_umum AS C ON B.id_mrp_inventory_umum = C.id_mrp_inventory_umum"
        . " LEFT JOIN mrp_supplier AS D ON A.id_mrp_supplier = D.id_mrp_supplier" 
        . " {$where}"
        . " ORDER BY A.id_mrp_supplier_inventory DESC"
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
//    $status = array(1 => "Create", 2 => "Approve");
    foreach ($data AS $da){
        $name = $da->umum." ".$da->spesifik;
        $hasil[] = array(
        $name,
        $da->name_supplier,
        number_format($da->harga)    
      );
    }
    $return['hasil'] = $hasil;
//    $this->debug($return, true);
    print json_encode($return);
    die;
  }
  
  function get_mrp_master_brand($start = 0){
      
      $where = "WHERE 1=1";
     if($this->session->userdata('brand_search_title')){
        $where .= " AND A.title like '%{$this->session->userdata('brand_search_title')}%'";
      }
      
      if($this->session->userdata('brand_search_code')){
        $where .= " AND A.code like '%{$this->session->userdata('brand_search_code')}%'";
      }
      
     
    $data = $this->global_models->get_query("SELECT  A.id_mrp_brand,A.title,A.code,A.status"
        . " FROM mrp_brand AS A"
        . " {$where}"
        . " ORDER BY A.id_mrp_brand ASC"
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
     $status = array( 1=> "<span class='label bg-orange'>Active</span>", 2 => "<span class='label bg-maroon'>Non Active</span>");
    
//    $status = array(1 => "Active", 2 => "Non Active");
    foreach ($data AS $da){
      $hasil[] = array(
        $da->title,
        $da->code,
        $status[$da->status],
        "<div class='btn-group'>"
          . "<a href='".site_url("mrp/mrp-master/add-brand/{$da->id_mrp_brand}")."' type='button' class='btn btn-info btn-flat' style='width: 40px'><i class='fa fa-edit'></i></a>"
        . "</div>"
      );
    }
    $return['hasil'] = $hasil;
//    $this->debug($return, true);
    print json_encode($return);
    die;
  }
  
  function get_mrp_master_satuan($group = 0,$sort = 0,$start = 0){
      
      $where = "WHERE 1=1 AND A.status='1'";
     if($this->session->userdata('satuan_search_title')){
        $where .= " AND A.title like '%{$this->session->userdata('satuan_search_title')}%'";
      }
      
      if($this->session->userdata('satuan_search_nilai')){
        $where .= " AND A.nilai like '%{$this->session->userdata('satuan_search_nilai')}%'";
      }
      
      if($this->session->userdata('satuan_search_type')){
        $where .= " AND A.type = '{$this->session->userdata('satuan_search_type')}'";
      }
      
      if($group > 0 AND $sort > 0){
          $where .= " AND A.group_satuan = '{$group}' AND A.sort > '$sort'";
      }else{
          $where .= " AND A.sort = '1'";
      }
     
    $data = $this->global_models->get_query("SELECT  A.*"
        . " FROM mrp_satuan AS A"
        . " {$where}"
        . " GROUP BY A.group_satuan"
        . " LIMIT {$start}, 10");
        
    if(count($data) > 0){
      $return['status'] = 2;
      $return['start']  = $start + 10;
    }
    else{
      $return['status'] = 3;
    }
//     $status = array( 1=> "<span class='label bg-orange'>Active</span>", 2 => "<span class='label bg-maroon'>Non Active</span>");
    $type = array(1 => "Bukan Terkecil", 2 => "Terkecil");
//    $status = array(1 => "Active", 2 => "Non Active");
    $parent = $this->global_models->get_field("mrp_satuan", "title", 
                    array("group_Satuan" => $group,"sort" => $sort));
    if($parent){
        $parent = $parent;
    }else{
        $parent = "";
    }
    foreach ($data AS $da){
      $hasil[] = array(
        "<a href='".site_url("mrp/mrp-master/satuan/{$da->group_satuan}/{$da->sort}/{$da->id_mrp_satuan}")."'>{$da->title}</a>",
        $type[$da->type],
        $da->nilai,
        $parent,
        $da->note,     
        "<div class='btn-group'>"
          . "<a href='".site_url("mrp/mrp-master/add-satuan/{$da->group_satuan}/{$sort}/{$da->id_mrp_satuan}")."' type='button' class='btn btn-info btn-flat' style='width: 40px'><i class='fa fa-edit'></i></a>"
          . "<a href='".site_url("mrp/mrp-master/satuan/{$da->group_satuan}/{$da->sort}/{$da->id_mrp_satuan}")."' type='button' class='btn btn-info btn-flat' style='width: 40px'><i class='fa fa-plus'></i></a>"        
          . "<a href='".site_url("mrp/mrp-master/delete-satuan/{$da->group_satuan}/{$da->sort}/{$da->id_mrp_satuan}")."' type='button' class='btn btn-warning btn-flat' style='width: 40px'><i class='fa fa-trash-o'></i></a>"
          . "</div>"
      );
    }
    $return['hasil'] = $hasil;
//    $this->debug($return, true);
    print json_encode($return);
    die;
  }
  
  function get_mrp_master_supplier_inventory($id_mrp_supplier = 0,$start = 0){
      
      $where = "WHERE 1=1 AND A.status <= 2 AND A.id_mrp_supplier = '{$id_mrp_supplier}'";
    
      if($this->session->userdata('supplier_inventory_search_harga')){
        $where .= " AND A.harga like '%{$this->session->userdata('supplier_inventory_search_harga')}%'";
      }
      
       if($this->session->userdata('supplier_inventory_search_nama')){
           $q = strtolower($this->session->userdata('supplier_inventory_search_nama'));
          $dt = $this->global_models->get_query("SELECT id_mrp_inventory_spesifik FROM mrp_inventory_spesifik AS A"
          . " LEFT JOIN mrp_inventory_umum AS B ON A.id_mrp_inventory_umum = B.id_mrp_inventory_umum"
          . " WHERE LOWER(CONCAT(B.name,' ',A.title)) like '%{$q}%'");

                foreach ($dt as $ky => $value2) {
                    $daaa .= " ".$value2->id_mrp_inventory_spesifik;

                 }
               $hilang3 = trim($daaa); 
              $dts = str_replace(" ",",", $hilang3);
              if($dts){
                  $where .= " AND C.id_mrp_inventory_spesifik in ({$dts})";
              }else{
                  $where .= " AND C.id_mrp_inventory_spesifik in (0)";
              }
        
      }
      
    $data = $this->global_models->get_query("SELECT  A.id_mrp_supplier_inventory,A.id_mrp_supplier,A.harga,A.tanggal,A.status"
        . ",D.name AS inventory_umum,F.code AS type,E.title AS brand,G.title AS satuan,C.jenis,C.title AS title_spesifik"
        . " FROM mrp_supplier_inventory AS A"
//        . " LEFT JOIN mrp_supplier AS B ON A.id_mrp_supplier = B.id_mrp_supplier"
        . " LEFT JOIN mrp_inventory_spesifik AS C ON A.id_mrp_inventory_spesifik = C.id_mrp_inventory_spesifik"
        . " LEFT JOIN mrp_inventory_umum AS D ON C.id_mrp_inventory_umum = D.id_mrp_inventory_umum"    
        . " LEFT JOIN mrp_brand AS E ON C.id_mrp_brand = E.id_mrp_brand"
        . " LEFT JOIN mrp_type_inventory AS F ON D.id_mrp_type_inventory = F.id_mrp_type_inventory"
        . " LEFT JOIN mrp_satuan AS G ON C.id_mrp_satuan = G.id_mrp_satuan"    
        . " {$where}"
        . " ORDER BY A.id_mrp_supplier_inventory ASC"
        . " LIMIT {$start}, 10");
        
//   print $last = $this->db->last_query(); die;
//    $this->debug($data, true);
    if(count($data) > 0){
      $return['status'] = 2;
      $return['start']  = $start + 10;
    }
    else{
      $return['status'] = 3;
    }
    $status = array( 1=> "<span class='label bg-orange'>Active</span>", 2 => "<span class='label bg-maroon'>Non Active</span>");

     $jenis = array("1" => "Habis Pakai", "2" => "Asset");
    foreach ($data AS $key => $da){
        
        if($da->title_spesifik){
            $title_spesifik2 = " ".$da->title_spesifik;
        }else{
            $title_spesifik2 = "";
        }
        
        if($da->jenis){
            $jenis2 = "<br>Jenis Barang:".$jenis[$da->jenis];
        }else{
            $jenis2 = "";
        }
        
        if($da->brand){
           $brand = "<br>Brand:".$da->brand;
        }else{
            $brand = "";
        }
        
        if($da->satuan){
           $satuan = "<br>Satuan:".$da->satuan;
        }else{
            $satuan = "";
        }
        
        $tgl = date("d F Y", strtotime($da->tanggal));
        
        $btn2 = "<div class='btn-group'>"
                . "<button type='button' class='btn btn-info dropdown-toggle' data-toggle='dropdown' title='Click Here'>"
                . "<span class='caret'></span>"
                . "</button>"
                . "<ul class='dropdown-menu'>"
                . "<li><a href='".site_url("mrp/mrp-master/add-supplier-inventory/{$da->id_mrp_supplier}/{$da->id_mrp_supplier_inventory}")."'>Edit Product</a></li>"
                . "<li><a href='".site_url("mrp/mrp-master/history-supplier-inventory/{$da->id_mrp_supplier}/{$da->id_mrp_supplier_inventory}")."'>History Product</li>"
                . "<li class='divider'></li>"
                . "<li><a href='".site_url("mrp/mrp-master/delete-supplier-inventory/{$da->id_mrp_supplier}/{$da->id_mrp_supplier_inventory}")."'>Delete</li>"       
                . "</ul>"
                 . "</div>";
        
             
      $hasil[] = array(
        $da->inventory_umum,
        $title_spesifik2.$jenis2.$brand.$satuan,  
          $da->type,
        number_format($da->harga),
        $tgl, 
        $status[$da->status],   
        $btn2
      );
    }
    $return['hasil'] = $hasil;
//    $this->debug($return, true);
    print json_encode($return);
    die;
  }
  
  function get_mrp_master_history_supplier_inventory($id_mrp_supplier = 0,$id_mrp_supplier_inventory = 0,$start = 0){
      
      $where = "WHERE 1=1 AND A.id_mrp_supplier = '{$id_mrp_supplier}' AND id_mrp_supplier_inventory ='{$id_mrp_supplier_inventory}'";
     if($this->session->userdata('history_supplier_inventory_search_kategori_barang')){
        $where .= " AND A.kategori_inventory = '{$this->session->userdata('history_supplier_inventory_search_kategori_barang')}'";
      }
      
      if($this->session->userdata('history_supplier_inventory_search_harga')){
        $where .= " AND A.harga like '%{$this->session->userdata('history_supplier_inventory_search_harga')}%'";
      }
      
       if($this->session->userdata('history_supplier_inventory_search_nama')){
          $dt = $this->global_models->get_query("SELECT id_mrp_inventory FROM mrp_inventory"
          . " WHERE name like '%{$this->session->userdata('history_supplier_inventory_search_nama')}%'");

                foreach ($dt as $ky => $value2) {
                    $daaa .= " ".$value2->id_mrp_inventory;

                 }
               $hilang3 = trim($daaa); 
              $dts = str_replace(" ",",", $hilang3);
              if($dts){
                  $where .= " AND C.id_mrp_inventory in ({$dts})";
              }else{
                  $where .= " AND C.id_mrp_inventory in (0)";
              }
        
      }
      
      
   $data = $this->global_models->get_query("SELECT  A.create_date,A.id_mrp_supplier_inventory,A.id_mrp_supplier,A.harga,A.tanggal,A.status,A.note"
        . ",C.title AS spesifik,D.name AS inventory_umum,F.code AS type,E.title AS brand,G.title AS satuan,C.jenis"
        . " FROM log_mrp_supplier_inventory AS A"
//        . " LEFT JOIN mrp_supplier AS B ON A.id_mrp_supplier = B.id_mrp_supplier"
        . " LEFT JOIN mrp_inventory_spesifik AS C ON A.id_mrp_inventory_spesifik = C.id_mrp_inventory_spesifik"
        . " LEFT JOIN mrp_inventory_umum AS D ON C.id_mrp_inventory_umum = D.id_mrp_inventory_umum"    
        . " LEFT JOIN mrp_brand AS E ON C.id_mrp_brand = E.id_mrp_brand"
        . " LEFT JOIN mrp_type_inventory AS F ON D.id_mrp_type_inventory = F.id_mrp_type_inventory"
        . " LEFT JOIN mrp_satuan AS G ON C.id_mrp_satuan = G.id_mrp_satuan"    
        . " {$where}"
        . " ORDER BY A.id_mrp_supplier_inventory DESC"
        . " LIMIT {$start}, 10");
        
//   print $last = $this->db->last_query(); die;
//    $this->debug($data, true);
    if(count($data) > 0){
      $return['status'] = 2;
      $return['start']  = $start + 10;
    }
    else{
      $return['status'] = 3;
    }
    $status = array( 1=> "<span class='label bg-orange'>Active</span>", 2 => "<span class='label bg-maroon'>Non Active</span>");
     
    $kategori_inventory = array(1 => "Umum", 2 => "Spesifik");
     $jenis = array("1" => "Habis Pakai", "2" => "Asset");
    foreach ($data AS $key => $da){
         
        $tgl_create = date("d F Y H:i:s", strtotime($da->create_date));
        $tgl = date("d F Y", strtotime($da->tanggal));
      $hasil[] = array(
        $tgl_create,
        $tgl, 
        $da->inventory_umum." ".$da->spesifik." [Jenis Barang:".$jenis[$da->jenis]."] [Type:".$da->type."] [Brand:".$da->brand."] [Satuan:".$da->satuan."]",
        $da->harga,
         $da->note, 
        $status[$da->status]
      );
    }
    $return['hasil'] = $hasil;
//    $this->debug($return, true);
    print json_encode($return);
    die;
  }
  
  function get_mrp_master_inventory_umum($start = 0){
      
      $where = "WHERE 1=1 ";
     if($this->session->userdata('inventory_umum_search_type')){
        $where .= " AND A.id_mrp_type_inventory = '{$this->session->userdata('inventory_umum_search_type')}'";
      }
      
       if($this->session->userdata('inventory_umum_search_nama')){
        $where .= " AND A.name like '%{$this->session->userdata('inventory_umum_search_nama')}%'";
      }
      
      if($this->session->userdata('inventory_umum_search_code')){
        $where .= " AND A.code like '%{$this->session->userdata('inventory_umum_search_code')}%'";
      }
      
      
    $data = $this->global_models->get_query("SELECT A.name,A.code,A.id_mrp_inventory_umum,A.note,B.title AS type"
        . " FROM mrp_inventory_umum AS A"
        . " LEFT JOIN mrp_type_inventory AS B ON A.id_mrp_type_inventory = B.id_mrp_type_inventory"
        . " {$where}"
        . " ORDER BY A.id_mrp_inventory_umum ASC"
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
      $hasil[] = array(
        $da->type,
        $da->name,
        $da->code, 
        $da->note,   
        "<div class='btn-group'>"
          . "<a href='".site_url("mrp/mrp-master/add-inventory-umum/{$da->id_mrp_inventory_umum}")."' type='button' class='btn btn-info btn-flat' style='width: 40px'><i class='fa fa-edit'></i></a>"
        . "</div>"
      );
    }
    $return['hasil'] = $hasil;
//    $this->debug($return, true);
    print json_encode($return);
    die;
  }
  
  function brand(){
    if (empty($_GET['term'])) exit ;
    $q = strtolower($_GET["term"]);
    if (get_magic_quotes_gpc()) $q = stripslashes($q);
    $items = $this->global_models->get_query("
      SELECT *
      FROM mrp_brand
      WHERE 
      LOWER(title) LIKE '%{$q}%'
      LIMIT 0,10
      ");
    if(count($items) > 0){
      foreach($items as $tms){
        $result[] = array(
            "id"    => $tms->id_mrp_brand,
            "label" => $tms->title,
            "value" => $tms->title,
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
  
  function inventory_umum(){
    if (empty($_GET['term'])) exit ;
    $q = strtolower($_GET["term"]);
    if (get_magic_quotes_gpc()) $q = stripslashes($q);
    $items = $this->global_models->get_query("
      SELECT A.id_mrp_inventory_umum,A.name,B.code
      FROM mrp_inventory_umum AS A
      LEFT JOIN mrp_type_inventory AS B ON A.id_mrp_type_inventory = B.id_mrp_type_inventory
      WHERE 
      A.status ='1' AND LOWER(A.name) LIKE '%{$q}%'
      LIMIT 0,10
      ");
    if(count($items) > 0){
      foreach($items as $tms){
        $result[] = array(
            "id"    => $tms->id_mrp_inventory_umum,
            "label" => $tms->name." <".$tms->code.">",
            "value" => $tms->name." <".$tms->code.">",
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
  
  function satuan(){
    if (empty($_GET['term'])) exit ;
    $q = strtolower($_GET["term"]);
    if (get_magic_quotes_gpc()) $q = stripslashes($q);
//    $items = $this->global_models->get_query("
//      SELECT *
//      FROM mrp_satuan
//      WHERE 
//      LOWER(title) LIKE '%{$q}%'
//      LIMIT 0,10
//      ");
      
      
    $where = " AND LOWER(A.title) LIKE '%{$q}%' AND A.status=1";
      
    $items = $this->global_models->get_query("SELECT  A.*"
        . " FROM mrp_satuan AS A"
        . " WHERE id_mrp_satuan in(SELECT MAX(id_mrp_satuan)
                FROM mrp_satuan
               GROUP BY group_satuan
             ) {$where}"
        . " LIMIT 0,10 ");
//      print  $this->db->last_query(); die;
    if(count($items) > 0){
      foreach($items as $tms){
          if($tms->note){
              $note = " [".$tms->note."]";
          }else{
              $note = "";
          }
        $result[] = array(
            "id"    => $tms->id_mrp_satuan,
            "label" => $tms->title.$note,
            "value" => $tms->title.$note,
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
  
  function proses_mutasi_stock_request($id_mrp_request = 0){
  $pst = $_POST;
  $id_mrp_inventory_spesifik = $pst['id_mrp_inventory_spesifik'];
  $jml_rg           =  $pst['jumlah'];
  
  $tgl_diserahkan   = $pst['tgl_diserahkan'];
  $id_hr_pegawai    =  $pst['id_users'];
  $note             =  $pst['note'];
 
  if($tgl_diserahkan != "" AND $tgl_diserahkan != "0000-00-00"){
  $this->load->model('mrp/mmrp');
  $this->mmrp->proses_mutasi_stock($id_mrp_request,$id_mrp_inventory_spesifik,$jml_rg,$id_hr_pegawai,$tgl_diserahkan,$note);
   $return['status'] = 2;
   print json_encode($return);
   die;
  
  }else{
  $return['status'] = 1;
  print json_encode($return);
  die;
  }
 
  
}
  
  function get_mrp_master_inventory_spesifik($start = 0){
      
      $where = "WHERE 1=1 AND A.status < 2";
     
      if($this->session->userdata('inventory_spesifik_search_nama')){
//        $where .= " AND A.jenis = '{$this->session->userdata('inventory_spesifik_search_nama')}'";
         $saerch_name =  strtolower($this->session->userdata('inventory_spesifik_search_nama'));
        $dt = $this->global_models->get_query("SELECT A.id_mrp_inventory_spesifik "
                . "FROM mrp_inventory_spesifik AS A"
                . " LEFT JOIN mrp_inventory_umum AS B ON A.id_mrp_inventory_umum = B.id_mrp_inventory_umum"
                . " WHERE B.status = '1' AND LOWER(CONCAT(B.name,' ',A.title)) like '%{$saerch_name}%' ");


                foreach ($dt as $ky => $value2) {
                    $daaa .= " ".$value2->id_mrp_inventory_spesifik;

                 }
               $hilang3 = trim($daaa); 
              $dts = str_replace(" ",",", $hilang3);
              if($dts){
                  $where .= " AND A.id_mrp_inventory_spesifik in ({$dts})";
              }else{
                  $where .= " AND A.id_mrp_inventory_spesifik in (0)";
              }
        }
      
       if($this->session->userdata('inventory_spesifik_search_jenis')){
        $where .= " AND A.jenis = '{$this->session->userdata('inventory_spesifik_search_jenis')}'";
      }
      
      if($this->session->userdata('inventory_spesifik_search_brand')){
//        $where .= " AND A.code like '%{$this->session->userdata('inventory_spesifik_search_brand')}%'";
      
         $dt = $this->global_models->get_query("SELECT id_mrp_brand FROM mrp_brand"
          . " WHERE status = '1' AND title like '%{$this->session->userdata('inventory_spesifik_search_brand')}%' ");

                foreach ($dt as $ky => $value2) {
                    $daaa .= " ".$value2->id_mrp_brand;

                 }
               $hilang3 = trim($daaa); 
              $dts = str_replace(" ",",", $hilang3);
              if($dts){
                  $where .= " AND C.id_mrp_brand in ({$dts})";
              }else{
                  $where .= " AND C.id_mrp_brand in (0)";
              }
        }
      
       if($this->session->userdata('inventory_spesifik_search_satuan')){
//        $where .= " AND A.id_mrp_brand = '{$this->session->userdata('inventory_spesifik_search_satuan')}'";
      
        $dt = $this->global_models->get_query("SELECT id_mrp_satuan FROM mrp_satuan"
          . " WHERE title like '%{$this->session->userdata('inventory_spesifik_search_satuan')}%' ");

                foreach ($dt as $ky => $value2) {
                    $daaa .= " ".$value2->id_mrp_satuan;

                 }
               $hilang3 = trim($daaa); 
              $dts = str_replace(" ",",", $hilang3);
              if($dts){
                  $where .= " AND D.id_mrp_satuan in ({$dts})";
              }else{
                  $where .= " AND D.id_mrp_satuan in (0)";
              }
        }     
      
    $data = $this->global_models->get_query("SELECT  E.code AS type_inventory,B.name AS inventory_umum,C.title AS brand,D.title AS satuan,A.jenis,A.title AS title_spesifik,"
        . "A.id_mrp_inventory_spesifik,A.note"
        . " FROM mrp_inventory_spesifik AS A"
        . " LEFT JOIN mrp_inventory_umum AS B ON A.id_mrp_inventory_umum = B.id_mrp_inventory_umum"
        . " LEFT JOIN mrp_brand AS C ON A.id_mrp_brand = C.id_mrp_brand"
        . " LEFT JOIN mrp_satuan AS D ON A.id_mrp_satuan = D.id_mrp_satuan"
        . " LEFT JOIN mrp_type_inventory AS E ON B.id_mrp_type_inventory = E.id_mrp_type_inventory"    
        . " {$where}"
        . " ORDER BY A.id_mrp_inventory_spesifik ASC"
        . " LIMIT {$start}, 7");
        
//    $data_array = json_decode($data);
//    $this->debug($data, true);
    if(count($data) > 0){
      $return['status'] = 2;
      $return['start']  = $start + 7;
    }
    else{
      $return['status'] = 3;
    }
    $jenis = array(1 => "Habis Pakai", 2 => "Asset");
    
    foreach ($data AS $da){
      $hasil[] = array(
        $jenis[$da->jenis],
        "<a data-toggle='collapse' data-parent='#accordion'  href='#collapseOne' onclick='coba_data({$da->id_mrp_inventory_spesifik});'>".$da->inventory_umum." [".$da->type_inventory."]"."</a>",
        $da->title_spesifik,  
        $da->brand, 
        $da->satuan,  
        $da->note,  
        "<div class='btn-group'>"
          . "<a href='".site_url("mrp/mrp-master/add-inventory-spesifik/{$da->id_mrp_inventory_spesifik}")."' type='button' class='btn btn-info btn-flat' style='width: 40px'><i class='fa fa-edit'></i></a>"
          . "<a href='".site_url("mrp/mrp-master/delete-inventory-spesifik/{$da->id_mrp_inventory_spesifik}")."' type='button' class='btn btn-danger btn-flat' style='width: 40px'><i class='fa fa-trash-o'></i></a>"
        . "</div>"
        . "<script>"
            . "function coba_data(id_inventory){"
                    . 'var table = '
                    . '$("#tableboxy2").dataTable({'
                    . '"order": [[ 0, "asc" ]],'
                    . '"bDestroy": true'
                    . '});'
                    . "table.fnClearTable();"
                    . 'ambil_data2(table, 0,id_inventory);'
              
            . "}"
        
                . 'function ambil_data2(table, mulai,id_inventory){'
                . '$.post("'.site_url("mrp/mrp-ajax/ajax-master-inventory-spesifik-supplier").'/"+id_inventory+"/"+mulai, function(data){'
                  . '$("#loader-page2").show();'
                  . 'var hasil = $.parseJSON(data);'
                  . 'if(hasil.status == 2){'
                    . 'table.fnAddData(hasil.hasil);'
                    . 'ambil_data2(table, hasil.start,id_inventory);'
                  . '}'
                  . 'else{'
                    . '$("#loader-page2").hide();'
                  . '}'
                . '});'
              . '}'
        . "</script>"          
      );
    }
    $return['hasil'] = $hasil;
//    $this->debug($return, true);
    print json_encode($return);
    die;
  }
  
  function get_inventory_spesifik(){
    if (empty($_GET['term'])) exit ;
    $q = strtolower($_GET["term"]);
    if (get_magic_quotes_gpc()) $q = stripslashes($q);
    $items = $this->global_models->get_query("
      SELECT A.id_mrp_inventory_spesifik,A.jenis,A.title AS title_spesifik,B.name,C.title AS brand,E.code AS type,D.title AS satuan
      FROM mrp_inventory_spesifik AS A
      LEFT JOIN mrp_inventory_umum AS B ON A.id_mrp_inventory_umum = B.id_mrp_inventory_umum
      LEFT JOIN mrp_brand AS C ON A.id_mrp_brand = C.id_mrp_brand
      LEFT JOIN mrp_satuan AS D ON A.id_mrp_satuan = D.id_mrp_satuan
      LEFT JOIN mrp_type_inventory AS E ON B.id_mrp_type_inventory = E.id_mrp_type_inventory
      WHERE 
      A.status ='1' AND LOWER(CONCAT(B.name,' ',A.title)) LIKE '%{$q}%'
      ORDER BY CONCAT(B.name,A.title)  ASC
      LIMIT 0,10
      ");

      $jenis = array("1" => "Habis Pakai", "2" => "Asset");
      
    if(count($items) > 0){
      foreach($items as $tms){
          
          if($tms->title_spesifik){
              $title_spesifik = " ".$tms->title_spesifik;
          }else{
              $title_spesifik = "";
          }
          if($tms->jenis){
              $jenis2 = " <Jenis Barang:".$jenis[$tms->jenis].">";
          }else{
              $jenis2 = "";
          }
          
          if($tms->type){
              $type =" <Type:".$tms->type.">";
          }else{
              $type = "";
          }
          
          if($tms->brand){
              $brand = " <Brand:".$tms->brand.">";
          }else{
              $brand = "";
          }
          
          if($tms->satuan){
              $satuan = " <Satuan:".$tms->satuan.">";
          }else{
              $satuan = "";
          }
              
        $result[] = array(
            "id"    => $tms->id_mrp_inventory_spesifik,
            "label" => $tms->name.$title_spesifik.$jenis2.$type.$brand.$satuan,
            "value" => $tms->name.$title_spesifik.$jenis2.$type.$brand.$satuan,
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
  
  function get_mrp_request_pengadaan_atk($start = 0,$id_users = 0){
      
      $dta_id = $this->global_models->get_field("hr_pegawai", "id_hr_master_organisasi", array("id_users" => $id_users));
      $hr_pegawai = $this->global_models->get("hr_master_organisasi", array("parent" => "{$dta_id}"));

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
        $no = 0;
        $aa = $dta_id;
         foreach ($hr_pegawai as $ky => $val) {
        if($hr_pegawai[0]->id_hr_master_organisasi){
            $aa .= ",".$val->id_hr_master_organisasi;
            $hr_pegawai2 = $this->global_models->get("hr_master_organisasi", array("parent" => "{$val->id_hr_master_organisasi}"));
            if($hr_pegawai2[0]->id_hr_master_organisasi){
                foreach ($hr_pegawai2 as $ky2 => $val2) {
                    $aa .= ",".$val2->id_hr_master_organisasi;
                 $hr_pegawai3 = $this->global_models->get("hr_master_organisasi", array("parent" => "{$val2->id_hr_master_organisasi}"));
                
                    if($hr_pegawai3[0]->id_hr_master_organisasi){
                        foreach ($hr_pegawai3 as $ky3 => $val3) {
                            $aa .= ",".$val3->id_hr_master_organisasi;
                            $hr_pegawai4 = $this->global_models->get("hr_master_organisasi", array("parent" => "{$val3->id_hr_master_organisasi}"));
                            if($hr_pegawai4[0]->id_hr_master_organisasi){
                                foreach ($hr_pegawai3 as $ky4 => $val4) {
                                    $aa .= ",".$val4->id_hr_master_organisasi;
                                }
                            }
                        }
                    } 
                }
            }
        }else{
            $aa .= ",".$val->id_hr_master_organisasi;
        }
        $no++;        
    }
    
        $qry = ""; 
//        if($status == 1){
//            $qry .= "A.create_by_users ='{$id_users}'";
//        }elseif($status == 2){
        if($id_users == 1){
            $qry = "";
        }else{
            if($aa){
                $qry .= "AND ((A.id_hr_master_organisasi='$dta_id' OR A.id_hr_master_organisasi IN ($aa)) OR (A.id_create_by_organisasi='$dta_id' OR A.id_create_by_organisasi IN ($aa))) ";
            }elseif($this->nbscache->get_olahan("permission", $this->session->userdata("id_privilege"), "proses_ro", "edit") !== FALSE){
                $qry .= "AND ((A.id_hr_master_organisasi='$dta_id' OR A.status >='3') OR (A.id_create_by_organisasi='$dta_id' OR A.status >='3')) ";
            }else{
                $qry .= "AND (A.id_hr_master_organisasi='$dta_id' OR A.id_create_by_organisasi='$dta_id')";
            }
        }
            
//        }
    
    if($this->nbscache->get_olahan("permission", $this->session->userdata("id_privilege"), "show-request", "edit") !== FALSE){    
    $where = "WHERE A.type_inventory = 2 AND (A.status >= 1 AND A.status < 10)";
    }elseif($this->session->userdata("id") == 1){
      $where = "WHERE A.type_inventory = 2";   
    }else{
    $where = "WHERE A.type_inventory = 2 {$qry} AND ((A.status >= 1 AND A.status < 10) OR A.status =11) ";   
    }
    
//    $data = $this->global_models->get_query("SELECT A.id_mrp_request,A.note,A.status,A.type_inventory,A.create_date,A.code AS ro_code,A.create_by_users,I.name AS create_by"
//        . ",B.id_hr_pegawai,B.id_hr_master_organisasi,J.id_hr_master_organisasi AS id_create_by_organisasi,C.name AS nama_pegawai,B.nip "
//        . ",D.code AS department,F.title AS perusahaan,H.code,H.id_mrp_task_orders,K.name"
//        . " FROM mrp_request AS A"
//        . " LEFT JOIN hr_pegawai AS B ON A.id_hr_pegawai = B.id_hr_pegawai"
//        . " LEFT JOIN m_users AS C ON B.id_users = C.id_users"
//        . " LEFT JOIN hr_master_organisasi AS D ON B.id_hr_master_organisasi = D.id_hr_master_organisasi" 
//        . " LEFT JOIN hr_company AS F ON B.id_hr_company = F.id_hr_company"
//        . " LEFT JOIN mrp_task_orders_request AS G ON A.id_mrp_request = G.id_mrp_request"
//        . " LEFT JOIN mrp_task_orders AS H ON G.id_mrp_task_orders = H.id_mrp_task_orders"
//        . " LEFT JOIN m_users AS I ON I.id_users = A.create_by_users"
//        . " LEFT JOIN hr_pegawai AS J ON J.id_users = A.create_by_users"
//        . " LEFT JOIN m_users AS K ON A.user_approval = K.id_users"   
//////      . " LEFT JOIN mrp_brand AS C ON A.id_mrp_brand = C.id_mrp_brand"
//////      . " LEFT JOIN mrp_qty AS D ON A.id_mrp_qty = D.id_mrp_qty"    
//        . " {$where}"
//        . " ORDER BY A.id_mrp_request ASC"
//        . " LIMIT {$start}, 10"
//        );
     $data = $this->global_models->get_query("SELECT *"
        . " FROM v_mrp_request AS A"
        . " {$where}"
        . " GROUP BY A.id_mrp_request"
        . " ORDER BY A.create_date DESC"
        . " LIMIT {$start}, 10"
        );
        
        
//    print  $this->db->last_query(); die;
//    $data_array = json_decode($data);
//    $this->debug($data, true);
    
//    $jenis = array(1 => "Habis Pakai", 2 => "Asset");
    $status = array( 1=> "<span class='label bg-orange'>Draft</span>", 2 => "<span class='label bg-blue'>Pengajuan</span>",
        3 => "<span class='label bg-green'>Approved</span>",4 => "<span class='label bg-green'>Task Order</span>",
        5 => "<span class='label bg-green'>Proses PO</span>", 6 => "<span class='label bg-green'>Sent PO</span>",
        7 => "<span class='label bg-green'>Partial</span>",8 => "<span class='label bg-green'>RG</span>",
        9 => "<span class='label bg-green'>Closed RO</span>", 10 => "<span class='label bg-red'>CANCEL RO</span>",
        11=> "<span class='label bg-black'>Reject RO</span>");
//    $status = array(1 => "Create", 2 => "Approve");
//    $type_inventory = array(1 => "Pengadaan Cetakan", 2 => "Pengadaan ATK");
   $no = 0;
   $hide = 0;
    foreach ($data AS $ky => $da){
            $dt_name = "";
        if($da->name){
            $dt_name = "<br>Approved:".$da->name;
        }
        $receiver = "";
        if($da->id_pegawai_receiver){
            $receiver = "<br><b>Penerima Barang</b>:".$da->name_receiver."<".$da->nama_department_receiver.">";
        }
        $note_warning = $this->global_models->get_field("mrp_request","note_warning",array("id_mrp_request" => $da->id_mrp_request));
       if($da->status >= 9 AND $da->status <= 10){
           $btn_del = "";
       }else{
            if($id_users == 1 OR $this->nbscache->get_olahan("permission", $this->session->userdata("id_privilege"), "btn-delete", "edit") !== FALSE){
                    $btn_del = "<a href='javascript:void(0)' data-toggle='modal' type='button' class='btn btn-danger btn-sm' title='Delete' data-target='#edit-keterangan-cancel' isi='{$da->id_mrp_request}' id='id-customer-cancel' ><i class='fa fa-trash-o'></i></a>"; 
             }elseif($da->create_by_users == $id_users AND ($da->status <= 2 OR $da->status == 11)){
                 $btn_del = "<a href='javascript:void(0)' data-toggle='modal' type='button' class='btn btn-danger btn-sm' title='Delete' data-target='#edit-keterangan-cancel' isi='{$da->id_mrp_request}' id='id-customer-cancel' ><i class='fa fa-trash-o'></i></a>"; 
             }else{
                 $btn_del = "";
             }
        }
       if($da->status == 1 OR $da->status == 11){
           if($da->create_by_users == $id_users OR $id_users == 1){
               $hasil[] = array(
                date('d-M-Y H:i:s', strtotime($da->create_date))."<br>Kode Request:<b>".$da->ro_code."</b>",  
                $da->nama_pegawai."<br>".$da->nip,
                $da->perusahaan."<br>Department:".$da->department,
                $da->note."<br>".$note_warning,
                $status[$da->status].$dt_name,
                "<a href='".site_url("mrp/mrp-task-orders/add-task-orders/{$da->id_mrp_task_orders}")."'>{$da->code}</a>",
                $da->create_by.$receiver,        
                "<div class='btn-group'>"
                  . "<a href='".site_url("mrp/add-request-pengadaan-atk/{$da->id_mrp_request}")."' title='Edit Request Orders' type='button' class='btn btn-info btn-sm'><i class='fa fa-edit'></i></a>"
                  . $btn_del
                . "</div>"
              );
                $hide++;  
           }
            
       }else{
           $hasil[] = array(
                date('d-M-Y H:i:s', strtotime($da->create_date))."<br>Kode Request:<b>".$da->ro_code."</b>",  
                $da->nama_pegawai."<br>".$da->nip,
                $da->perusahaan."<br>Department:".$da->department,
                $da->note."<br>".$note_warning,
                $status[$da->status].$dt_name,
                "<a href='".site_url("mrp/mrp-task-orders/add-task-orders/{$da->id_mrp_task_orders}")."'>{$da->code}</a>",
                $da->create_by.$receiver,
                "<div class='btn-group'>"
                  . "<a href='".site_url("mrp/add-request-pengadaan-atk/{$da->id_mrp_request}")."' title='Edit Request Orders' type='button' class='btn btn-info btn-sm'><i class='fa fa-edit'></i></a>"
                . $btn_del
                . "</div>"
              );
                  $no++;
        }    
            }
       
   if(count($data) > 0){
       if($hide > 0){
           $return['flag'] = 1;
       }else{
           $return['flag'] = 2;
       }
//       if($no > 0){
           $return['status'] = 2;
           $return['start']  = $start + 10; 
//       }
    }
    else{
      $return['status'] = 3;
    }     
    $return['hasil'] = $hasil;
//    $this->debug($return, true);
    print json_encode($return);
    die;
  }
  
    function get_mrp_request_pengadaan_cetakan($start = 0,$id_users = 0){
      
      $dta_id = $this->global_models->get_field("hr_pegawai", "id_hr_master_organisasi", array("id_users" => $id_users));
      $hr_pegawai = $this->global_models->get("hr_master_organisasi", array("parent" => "{$dta_id}"));

        $no = 0;
        $aa = $dta_id;
         foreach ($hr_pegawai as $ky => $val) {
        if($hr_pegawai[0]->id_hr_master_organisasi){
            $aa .= ",".$val->id_hr_master_organisasi;
            $hr_pegawai2 = $this->global_models->get("hr_master_organisasi", array("parent" => "{$val->id_hr_master_organisasi}"));
            if($hr_pegawai2[0]->id_hr_master_organisasi){
                foreach ($hr_pegawai2 as $ky2 => $val2) {
                    $aa .= ",".$val2->id_hr_master_organisasi;
                 $hr_pegawai3 = $this->global_models->get("hr_master_organisasi", array("parent" => "{$val2->id_hr_master_organisasi}"));
                
                    if($hr_pegawai3[0]->id_hr_master_organisasi){
                        foreach ($hr_pegawai3 as $ky3 => $val3) {
                            $aa .= ",".$val3->id_hr_master_organisasi;
                            $hr_pegawai4 = $this->global_models->get("hr_master_organisasi", array("parent" => "{$val3->id_hr_master_organisasi}"));
                            if($hr_pegawai4[0]->id_hr_master_organisasi){
                                foreach ($hr_pegawai3 as $ky4 => $val4) {
                                    $aa .= ",".$val4->id_hr_master_organisasi;
                                }
                            }
                        }
                    } 
                }
            }
        }else{
            $aa .= ",".$val->id_hr_master_organisasi;
        }
        $no++;        
    }
    $qry = ""; 
//        if($status == 1){
//            $qry .= "A.create_by_users ='{$id_users}'";
//        }elseif($status == 2){
        if($id_users == 1){
            $qry = "";
        }else{
            if($aa){
                $qry .= "AND ((A.id_hr_master_organisasi='$dta_id' OR A.id_hr_master_organisasi IN ($aa)) OR (A.id_create_by_organisasi='$dta_id' OR A.id_create_by_organisasi IN ($aa))) ";
            }elseif($this->nbscache->get_olahan("permission", $this->session->userdata("id_privilege"), "proses_ro", "edit") !== FALSE){
                $qry .= "AND (A.id_hr_master_organisasi='$dta_id' OR A.status >='3') ";
            }else{
                $qry .= "AND A.id_hr_master_organisasi='$dta_id'";
            }
        }
        
   if($this->nbscache->get_olahan("permission", $this->session->userdata("id_privilege"), "show-request", "edit") !== FALSE){    
    $where = "WHERE A.type_inventory = 1 AND (A.status >= 1 AND A.status < 10)";
    }elseif($this->session->userdata("id") == 1){
      $where = "WHERE A.type_inventory = 1 ";   
    }else{
    $where = "WHERE A.type_inventory = 1 {$qry} AND ((A.status >= 1 AND A.status < 10) OR A.status =11) ";   
    }
    
//      $where = "WHERE A.type_inventory = 1 {$qry}";
//     if($this->session->userdata('inventory_spesifik_search_type')){
//        $where .= " AND A.id_mrp_type_inventory = '{$this->session->userdata('inventory_spesifik_search_type')}'";
//      }
//      
//      if($this->session->userdata('inventory_spesifik_search_jenis')){
//        $where .= " AND A.jenis = '{$this->session->userdata('inventory_spesifik_search_jenis')}'";
//      }
//      
//       if($this->session->userdata('inventory_spesifik_search_nama')){
//        $where .= " AND A.name like '%{$this->session->userdata('inventory_spesifik_search_nama')}%'";
//      }
//      
//      if($this->session->userdata('inventory_spesifik_search_code')){
//        $where .= " AND A.code like '%{$this->session->userdata('inventory_spesifik_search_code')}%'";
//      }
//      
//       if($this->session->userdata('inventory_spesifik_search_brand')){
//        $where .= " AND A.id_mrp_brand = '{$this->session->userdata('inventory_spesifik_search_brand')}'";
//      }     
      
//    $data = $this->global_models->get_query("SELECT A.id_mrp_request,C.name AS nama_pegawai,B.nip,A.note,A.status,A.type_inventory,A.create_date,A.code AS ro_code "
//        . ",E.code AS department,F.title AS perusahaan,H.code,H.id_mrp_task_orders,I.name AS nama_approval"
//        . " FROM mrp_request AS A"
//        . " LEFT JOIN hr_pegawai AS B ON A.id_hr_pegawai = B.id_hr_pegawai"
//        . " LEFT JOIN m_users AS C ON B.id_users = C.id_users"
//        . " LEFT JOIN hr_company_department AS D ON B.id_hr_company_department = D.id_hr_company_department"    
//        . " LEFT JOIN hr_department AS E ON D.id_hr_department = E.id_hr_department"
//        . " LEFT JOIN hr_company AS F ON B.id_hr_company = F.id_hr_company"
//        . " LEFT JOIN mrp_task_orders_request AS G ON A.id_mrp_request = G.id_mrp_request"
//        . " LEFT JOIN mrp_task_orders AS H ON G.id_mrp_task_orders = H.id_mrp_task_orders"
//        . " LEFT JOIN m_users AS I ON A.user_approval = I.id_users"     
////        . " LEFT JOIN mrp_brand AS C ON A.id_mrp_brand = C.id_mrp_brand"
////        . " LEFT JOIN mrp_qty AS D ON A.id_mrp_qty = D.id_mrp_qty"    
//        . " {$where}"
//        . " ORDER BY A.id_mrp_request ASC"
//        . " LIMIT {$start}, 10");
//        
//         $data = $this->global_models->get_query("SELECT *"
       $data = $this->global_models->get_query("SELECT *"
        . " FROM v_mrp_request AS A"
        . " {$where}"
        . " GROUP BY A.id_mrp_request"
        . " ORDER BY A.create_date DESC"
        . " LIMIT {$start}, 10"
        );
//      print  $this->db->last_query(); die;
//    $data_array = json_decode($data);
//    $this->debug($data, true);
    
//    $jenis = array(1 => "Habis Pakai", 2 => "Asset");
   $status = array( 1=> "<span class='label bg-orange'>Draft</span>", 2 => "<span class='label bg-blue'>Pengajuan</span>",
        3 => "<span class='label bg-green'>Approved</span>",4 => "<span class='label bg-green'>Task Order</span>",
        5 => "<span class='label bg-green'>Proses PO</span>", 6 => "<span class='label bg-green'>Sent PO</span>",
        7 => "<span class='label bg-green'>Partial</span>",8 => "<span class='label bg-green'>RG</span>",
        9 => "<span class='label bg-green'>Closed Request Orders</span>",
       10 => "<span class='label bg-red'>CANCEL RO</span>",
       11=> "<span class='label bg-black'>Reject RO</span>");
//    $status = array(1 => "Create", 2 => "Approve");
    
    
    $type_inventory = array(1 => "Pengadaan Cetakan", 2 => "Pengadaan ATK");
        $no = 0;
       $hide = 0;
       
    foreach ($data AS $ky => $da){
            $dt_name = "";
            $note_warning = $this->global_models->get_field("mrp_request","note_warning",array("id_mrp_request" => $da->id_mrp_request));
        if($da->status >= 3){
            if($da->name){
                $dt_name = "<br>Approved:".$da->name;
            }
            
        }
        
        if($da->status >= 9 AND $da->status <= 10){
           $btn_del = "";
       }else{
           if($id_users == 1 OR $this->nbscache->get_olahan("permission", $this->session->userdata("id_privilege"), "btn-delete", "edit") !== FALSE){
                    $btn_del = "<a href='javascript:void(0)' data-toggle='modal' type='button' class='btn btn-danger btn-sm' title='Delete' data-target='#edit-keterangan-cancel' isi='{$da->id_mrp_request}' id='id-customer-cancel' ><i class='fa fa-trash-o'></i></a>"; 
             }elseif($da->create_by_users == $id_users AND ($da->status <= 2 OR $da->status == 11)){
                 $btn_del = "<a href='javascript:void(0)' data-toggle='modal' type='button' class='btn btn-danger btn-sm' title='Delete' data-target='#edit-keterangan-cancel' isi='{$da->id_mrp_request}' id='id-customer-cancel' ><i class='fa fa-trash-o'></i></a>"; 
             }else{
                 $btn_del = "";
             }
           }
        
        if($da->status == 1 OR $da->status == 11){
             if($da->create_by_users == $id_users OR $id_users == 1){
                  $hasil[] = array(
       date('d-M-Y H:i:s', strtotime($da->create_date))."<br>Kode Request:<b>".$da->ro_code."</b>", 
        $da->nama_pegawai."<br>".$da->nip,
        $da->perusahaan."<br>Department:".$da->department,
        "[".$type_inventory[$da->type_inventory]."]<br>".$da->note."<br>".$note_warning,
        $status[$da->status].$dt_name,
        "<a href='".site_url("mrp/mrp-task-orders/add-task-orders/{$da->id_mrp_task_orders}")."'>{$da->code}</a>",
        $da->create_by,        
        "<div class='btn-group'>"
          . "<a href='".site_url("mrp/add-request-pengadaan-cetakan/{$da->id_mrp_request}")."' type='button' class='btn btn-info btn-sm' style='width: 40px'><i class='fa fa-edit'></i></a>"
          . $btn_del        
        . "</div>"
      );
          $hide++;
             }
           
        }else{
             $hasil[] = array(
       date('d-M-Y H:i:s', strtotime($da->create_date))."<br>Kode Request:<b>".$da->ro_code."</b>", 
        $da->nama_pegawai."<br>".$da->nip,
        $da->perusahaan."<br>Department:".$da->department,
        "[".$type_inventory[$da->type_inventory]."]<br>".$da->note."<br>".$note_warning,
        $status[$da->status].$dt_name,
        "<a href='".site_url("mrp/mrp-task-orders/add-task-orders/{$da->id_mrp_task_orders}")."'>{$da->code}</a>",
        $da->create_by,        
        "<div class='btn-group'>"
          . "<a href='".site_url("mrp/add-request-pengadaan-cetakan/{$da->id_mrp_request}")."' type='button' class='btn btn-info btn-sm' style='width: 40px'><i class='fa fa-edit'></i></a>"
          . $btn_del 
        . "</div>"
      );
        }
      
    }
    
   if(count($data) > 0){
       if($hide > 0){
           $return['flag'] = 1;
       }else{
           $return['flag'] = 2;
       }
//       if($no > 0){
           $return['status'] = 2;
           $return['start']  = $start + 10; 
//       }
    }
    else{
      $return['status'] = 3;
    }     
    $return['hasil'] = $hasil;
//    $this->debug($return, true);
    print json_encode($return);
    die;
  }
  
  function get_form_mrp_request_pengadaan($id_mrp_request = 0,$id_users = 0,$start = 0){
      
      $status = $this->global_models->get_field("mrp_request", "status", 
                    array("id_mrp_request" => "{$id_mrp_request}"));
                    
      $dt_user = $this->global_models->get_field("mrp_request", "create_by_users", 
                    array("id_mrp_request" => "{$id_mrp_request}"));              
                    
      $ls = $this->global_models->get_query("SELECT B.id_hr_company,B.id_hr_master_organisasi"
        . " FROM m_users AS A"
        . " LEFT JOIN hr_pegawai AS B ON A.id_users = B.id_users"
        . " WHERE A.id_users = '{$id_users}'");
//       print $this->db->last_query();
//       die;
      $where = "WHERE A.status = 1 AND B.id_mrp_type_inventory = 2";
      $disabled ="";
      
      if($id_mrp_request > 0){
          $where .= " AND jumlah IS NOT NULL";
      }
      
      if($status == 1 AND  $this->session->userdata("id") != $dt_user){
//          $where .= " AND jumlah IS NOT NULL";
          $disabled = "disabled ";  
      }elseif($status > 1){
//          $where .= " AND jumlah IS NOT NULL";
          $disabled = "disabled ";  
      }
//      
   
       if($this->session->userdata("id") == 1 OR ($status == 2 AND $this->nbscache->get_olahan("permission", $this->session->userdata("id_privilege"), "approval-ro", "edit") !== FALSE)){
       $disabled ="";
      }
      
      if($this->session->userdata("id") == 1 OR $status == 11 OR $this->nbscache->get_olahan("permission", $this->session->userdata("id_privilege"), "edit-form-ro", "edit") !== FALSE){
       $disabled ="";
      }
//      if($id_mrp_request > 0){
//          $join = " "
//                  . " ";
//          $field = ",E.jumlah";
//      }else{
//          $where . " AND 1=1 ";
//          $join = "";
//          $field = "";
//      }
      $id_mrp_supplier = $this->global_models->get_field("mrp_setting_harga_atk","id_mrp_supplier",array("id_mrp_setting_harga_atk" => '1'));
      $data = $this->global_models->get_query("SELECT B.name AS inventory_umum,C.title AS brand,D.title AS satuan,"
        . "A.id_mrp_inventory_spesifik,A.title AS title_spesifik,E.jumlah,E.rg,G.harga,I.id_mrp_setting_lock_atk,F.create_by_users"
        . " ,E.rg,E.id_mrp_request_asset AS id_spesifik,F.status"
        . " FROM mrp_inventory_spesifik AS A"
        . " LEFT JOIN mrp_inventory_umum AS B ON A.id_mrp_inventory_umum = B.id_mrp_inventory_umum"
        . " LEFT JOIN mrp_brand AS C ON A.id_mrp_brand = C.id_mrp_brand"
        . " LEFT JOIN mrp_satuan AS D ON A.id_mrp_satuan = D.id_mrp_satuan"
        . " LEFT JOIN mrp_request_asset AS E ON (A.id_mrp_inventory_spesifik = E.id_mrp_inventory_spesifik AND E.id_mrp_request = '{$id_mrp_request}')"
        . " LEFT JOIN mrp_request AS F ON E.id_mrp_request = F.id_mrp_request"
        . " LEFT JOIN mrp_supplier_inventory AS G ON (A.id_mrp_inventory_spesifik = G.id_mrp_inventory_spesifik AND G.id_mrp_supplier = '{$id_mrp_supplier}')"
        . " LEFT JOIN mrp_setting_lock_atk_asset AS H ON A.id_mrp_inventory_spesifik = H.id_mrp_inventory_spesifik"
        . " LEFT JOIN mrp_setting_lock_atk AS I ON (H.id_mrp_setting_lock_atk = I.id_mrp_setting_lock_atk AND I.id_hr_company = '{$ls[0]->id_hr_company}' AND I.id_hr_master_organisasi ='{$ls[0]->id_hr_master_organisasi}')"        
        . " {$where} AND G.harga > 0"
        . " GROUP BY A.id_mrp_inventory_spesifik"
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
    $url = base_url()."themes/".DEFAULTTHEMES."/img/ajax-loader.gif";
    foreach ($data AS $da){
        if($da->jumlah){
            $jumlah = $da->jumlah;
        }else{
            $jumlah = 0;
        }
        
        if($da->rg){
            $rg = $da->rg;
        }else{
            $rg = 0;
        }
        
        if($da->harga){
            $harga = number_format($da->harga);
        }else{
            $harga = "";
        }
        
        $data_id = "id=dt_id_mrp_inventory_spesifik_".$da->id_mrp_inventory_spesifik;
        $data_jumlah = "id=jumlah".$da->id_mrp_inventory_spesifik;
        $data_jumlah_rg = "id=jumlah_rg".$da->id_mrp_inventory_spesifik;
        if($da->id_mrp_setting_lock_atk){
            $dt_jumlah = $this->form_eksternal->form_input("jumlah[]", $jumlah, $data_jumlah.'  style="width:100px;display:none" class="form-control jumlah input-sm" placeholder="Jumlah"').
            $this->form_eksternal->form_input("jumlah_rg[]", $rg, $data_jumlah_rg.'  style="width:100px;display:none" class="form-control jumlah_rg input-sm" placeholder="rg"');
        }else{
         
             $dt_jumlah = $this->form_eksternal->form_input("jumlah[]", $jumlah, $disabled.$data_jumlah.'  style="width:100px" class="form-control jumlah input-sm" placeholder="Jumlah"').
            $this->form_eksternal->form_input("jumlah_rg[]", $rg, $data_jumlah_rg.'  style="width:100px;display:none" class="form-control jumlah_rg input-sm" placeholder="rg"');
          
            
        }
        
        if($da->id_spesifik){
            if($id_users ==1 OR $da->create_by_users == $id_users OR $this->nbscache->get_olahan("permission", $this->session->userdata("id_privilege"), "edit-form-ro", "edit") !== FALSE OR $this->nbscache->get_olahan("permission", $this->session->userdata("id_privilege"), "approval-ro", "edit") !== FALSE){
        $btn_del =  "<div class='btn-group'>"
        . "<a href='javascript:void(0)' onclick='delete_request_pengadaan({$da->id_spesifik})' id='del_{$da->id_spesifik}' class='btn btn-danger btn-flat' style='width: 40px'><i class='fa fa-trash-o'></i></a>"
        ."<span style='display: none; margin-left: 10px;' id='img-page-{$da->id_spesifik}'><img width='35px' src='{$url}' /></span>"
        . "</div>";
            }
        }
        
        if($da->status < 6 OR $da->status == 11){
            $dt_jumlah = $dt_jumlah;
            $btn_del = $btn_del;
        }else{
            $dt_jumlah = $jumlah;
            $btn_del = "";
        }
        
      $hasil[] = array(
          $no =$no + 1,
        $da->inventory_umum." ".$da->title_spesifik,
        $da->brand,
        $da->satuan,
        $harga,  
       $this->form_eksternal->form_input("id_mrp_inventory_spesifik[]", $da->id_mrp_inventory_spesifik, $data_id.' style="width:100px;display:none" class="form-control id_spesifik input-sm" placeholder=""').
       $rg, 
       $dt_jumlah, 
       $btn_del
      );
    }
    $return['hasil'] = $hasil;
//    $this->debug($return, true);
    print json_encode($return);
    die;
  }
  
    function get_list_form_mrp_request_pengadaan($id_mrp_request = 0,$id_users = 0,$start = 0){
      
      $status = $this->global_models->get_field("mrp_request", "status", 
                    array("id_mrp_request" => "{$id_mrp_request}"));
                    
      $dt_user = $this->global_models->get_field("mrp_request", "create_by_users", 
                    array("id_mrp_request" => "{$id_mrp_request}"));              
                    
      $ls = $this->global_models->get_query("SELECT B.id_hr_company,B.id_hr_master_organisasi"
        . " FROM m_users AS A"
        . " LEFT JOIN hr_pegawai AS B ON A.id_users = B.id_users"
        . " WHERE A.id_users = '{$id_users}'");
//       print $this->db->last_query();
//       die;
      $where = "WHERE A.status = 1 AND B.id_mrp_type_inventory = 2";
      $disabled ="";
      if($status == 1 AND  $this->session->userdata("id") != $dt_user){
//          $where .= " AND jumlah IS NOT NULL";
          $disabled = "disabled ";  
      }elseif($status > 1){
//          $where .= " AND jumlah IS NOT NULL";
          $disabled = "disabled ";  
      }
//      
   
       if($this->session->userdata("id") == 1 OR ($status == 2 AND $this->nbscache->get_olahan("permission", $this->session->userdata("id_privilege"), "approval-ro", "edit") !== FALSE)){
       $disabled ="";
      }
      
      if($this->session->userdata("id") == 1 OR $status == 11 OR $this->nbscache->get_olahan("permission", $this->session->userdata("id_privilege"), "edit-form-ro", "edit") !== FALSE){
       $disabled ="";
      }

      $id_mrp_supplier = $this->global_models->get_field("mrp_setting_harga_atk","id_mrp_supplier",array("id_mrp_setting_harga_atk" => '1'));
      $data = $this->global_models->get_query("SELECT B.name AS inventory_umum,C.title AS brand,D.title AS satuan,"
        . "A.id_mrp_inventory_spesifik,A.title AS title_spesifik,E.jumlah,G.harga,I.id_mrp_setting_lock_atk"
        . " FROM mrp_inventory_spesifik AS A"
        . " LEFT JOIN mrp_inventory_umum AS B ON A.id_mrp_inventory_umum = B.id_mrp_inventory_umum"
        . " LEFT JOIN mrp_brand AS C ON A.id_mrp_brand = C.id_mrp_brand"
        . " LEFT JOIN mrp_satuan AS D ON A.id_mrp_satuan = D.id_mrp_satuan"
        . " LEFT JOIN mrp_request_asset AS E ON (A.id_mrp_inventory_spesifik = E.id_mrp_inventory_spesifik AND E.id_mrp_request = '{$id_mrp_request}')"
        . " LEFT JOIN mrp_request AS F ON E.id_mrp_request = F.id_mrp_request"
        . " LEFT JOIN mrp_supplier_inventory AS G ON (A.id_mrp_inventory_spesifik = G.id_mrp_inventory_spesifik AND G.id_mrp_supplier = '{$id_mrp_supplier}')"
        . " LEFT JOIN mrp_setting_lock_atk_asset AS H ON A.id_mrp_inventory_spesifik = H.id_mrp_inventory_spesifik"
        . " LEFT JOIN mrp_setting_lock_atk AS I ON (H.id_mrp_setting_lock_atk = I.id_mrp_setting_lock_atk AND I.id_hr_company = '{$ls[0]->id_hr_company}' AND I.id_hr_master_organisasi ='{$ls[0]->id_hr_master_organisasi}')"        
        . " {$where} AND G.harga > 0"
        . " GROUP BY A.id_mrp_inventory_spesifik"
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
        if($da->jumlah){
            $jumlah = $da->jumlah;
        }else{
            $jumlah = 0;
        }
        if($da->harga){
            $harga = number_format($da->harga);
        }else{
            $harga = "";
        }
        
        $data_id = "id=dt_id_mrp_inventory_spesifik_".$da->id_mrp_inventory_spesifik;
        $data_jumlah = "id=jumlah".$da->id_mrp_inventory_spesifik;
        $data_jumlah_rg = "id=jumlah_rg".$da->id_mrp_inventory_spesifik;
//        if($da->id_mrp_setting_lock_atk){
//            $dt_jumlah = $this->form_eksternal->form_input("jumlah[]", $jumlah, $disabled.$data_jumlah.'  style="width:100px;display:none" class="form-control jumlah input-sm" placeholder="Jumlah"');
//        }else{
//            $dt_jumlah = $this->form_eksternal->form_input("jumlah[]", $jumlah, $disabled.$data_jumlah.'  style="width:100px" class="form-control jumlah input-sm" placeholder="Jumlah"');
//        }
        
        if($da->id_mrp_setting_lock_atk){
            $dt_jumlah = $this->form_eksternal->form_input("jumlah[]", $jumlah, $data_jumlah.'  style="width:100px;display:none" class="form-control jumlah input-sm" placeholder="Jumlah"').
            $this->form_eksternal->form_input("jumlah_rg[]", $rg, $data_jumlah_rg.'  style="width:100px;display:none" class="form-control jumlah_rg input-sm" placeholder="rg"');
        }else{
            
            $dt_jumlah = $this->form_eksternal->form_input("jumlah[]", $jumlah, $disabled.$data_jumlah.'  style="width:100px" class="form-control jumlah input-sm" placeholder="Jumlah"').
            $this->form_eksternal->form_input("jumlah_rg[]", $rg, $data_jumlah_rg.'  style="width:100px;display:none" class="form-control jumlah_rg input-sm" placeholder="rg"');
        }
        
      $hasil[] = array(
          $no =$no + 1,
        $da->inventory_umum." ".$da->title_spesifik,
        $da->brand,
        $da->satuan,
        $harga,  
        $this->form_eksternal->form_input("id_mrp_inventory_spesifik[]", $da->id_mrp_inventory_spesifik, $data_id.' style="width:100px;display:none" class="form-control id_spesifik input-sm" placeholder=""').
        $dt_jumlah, 
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
  
   function ajax_form_mrp_request_pengadaan($type,$id_mrp_request,$start = 0){
     
      $status = $this->global_models->get_field("mrp_request", "status", 
                    array("id_mrp_request" => "{$id_mrp_request}"));
                    
      $dt_user = $this->global_models->get_field("mrp_request", "create_by_users", 
                    array("id_mrp_request" => "{$id_mrp_request}"));              
      
      $where = "WHERE A.status = 1 AND B.id_mrp_type_inventory = {$type} AND jumlah IS NOT NULL";
      
      
      $data = $this->global_models->get_query("SELECT B.name AS inventory_umum,C.title AS brand,D.title AS satuan,"
        . "A.id_mrp_inventory_spesifik,A.title AS title_spesifik,E.jumlah,F.id_mrp_request,F.status AS status_request"
        . " FROM mrp_inventory_spesifik AS A"
        . " LEFT JOIN mrp_inventory_umum AS B ON A.id_mrp_inventory_umum = B.id_mrp_inventory_umum"
        . " LEFT JOIN mrp_brand AS C ON A.id_mrp_brand = C.id_mrp_brand"
        . " LEFT JOIN mrp_satuan AS D ON A.id_mrp_satuan = D.id_mrp_satuan"
        . " LEFT JOIN mrp_request_asset AS E ON (A.id_mrp_inventory_spesifik = E.id_mrp_inventory_spesifik AND E.id_mrp_request = '{$id_mrp_request}')"
        . " LEFT JOIN mrp_request AS F ON E.id_mrp_request = F.id_mrp_request"
        . " {$where} "
        . " ORDER BY A.id_mrp_inventory_spesifik ASC"
        . " LIMIT {$start}, 10");
        
//    print $this->db->last_query(); die;
//    $data_array = json_decode($data);
//    $this->debug($data, true);
    if(count($data) > 0){
      $return['status'] = 2;
      $return['start']  = $start + 10;
    }
    else{
      $return['status'] = 3;
    }
    
    $url = base_url('mrp/create-task-orders');
    $no = $start;
    if($type == 1){
            foreach ($data AS $da){
                
                $data_id = "id=dt_id_mrp_inventory_spesifik_".$da->id_mrp_inventory_spesifik;
                $data_jumlah = "id=jumlah".$da->id_mrp_inventory_spesifik;
                
            if($da->jumlah){
                $jumlah = $da->jumlah;
            }else{
                $jumlah = 0;
            }
            if($da->status_request == 3){
                $dta_jumlah = $this->form_eksternal->form_input("jumlah[]", $jumlah, $data_jumlah.' style="width:100px" class="form-control jumlah input-sm" placeholder="Jumlah"');
            }
          $no =$no + 1;
          $hasil[] = array(
              $no,
            $da->inventory_umum." ".$da->title_spesifik,
            $da->satuan,
            $this->form_eksternal->form_input("id_mrp_inventory_spesifik[]", $da->id_mrp_inventory_spesifik, $data_id.' style="width:100px;display:none" class="form-control id_spesifik input-sm" placeholder=""').
            $dta_jumlah
            .   "<script>"
                . "$('#jumlah{$da->id_mrp_inventory_spesifik}').keyup(function(){"
                   . "var jml = $('#jumlah{$da->id_mrp_inventory_spesifik}').val() * 1;"
                   . "jml = jml ? jml : 0;"
                    ."var dataString2 = 'jumlah='+ jml+ '&id_mrp_request='+ {$da->id_mrp_request};"
                        ."$.ajax({"
                        ."type : 'POST',"
                        ."url : '".site_url("mrp/mrp-ajax/ajax-update-request-pengadaan/{$da->id_mrp_inventory_spesifik}")."',"
                        ."data: dataString2,"
                        ."dataType : 'html',"
                        ."success: function(data) {"
                        . 'var hasil = $.parseJSON(data);'
//                                . "alert(hasil.hasil);"
//                        . "$('#script-tambahan').html(hasil.hasil);"

                        ."},"
                     ."});"    
                . "});"
            .   "</script>"
            );
        }
        }elseif(($type >= 2  OR $type <= 5) OR ($type >= 7 OR $type <= 9)){
            foreach ($data AS $da){
            if($da->jumlah){
                $jumlah = $da->jumlah;
            }else{
                $jumlah = 0;
            }
            
            
            $data_id = "id=dt_id_mrp_inventory_spesifik_".$da->id_mrp_inventory_spesifik;
            $data_jumlah = "id=jumlah".$da->id_mrp_inventory_spesifik;
              
            if($da->status_request == 3){
                $dta_jumlah = $this->form_eksternal->form_input("jumlah[]", $jumlah, $data_jumlah.' style="width:100px" class="form-control jumlah input-sm" placeholder="Jumlah"');
            }
            
          $hasil[] = array(
              $no =$no + 1,
            $da->inventory_umum." ".$da->title_spesifik,
            $da->brand,
            $da->satuan,
            $this->form_eksternal->form_input("id_mrp_inventory_spesifik[]", $da->id_mrp_inventory_spesifik, $data_id.' style="width:100px;display:none" class="form-control id_spesifik input-sm" placeholder=""').
            $dta_jumlah
            .   "<script>"
                 . "$('#jumlah{$da->id_mrp_inventory_spesifik}').keyup(function(){"
                    . "var jml = $('#jumlah{$da->id_mrp_inventory_spesifik}').val() * 1;"
                    . "jml = jml ? jml : 0;"
                     ."var dataString2 = 'jumlah='+ jml+ '&id_mrp_request='+ {$da->id_mrp_request};"
                         ."$.ajax({"
                         ."type : 'POST',"
                         ."url : '".site_url("mrp/mrp-ajax/ajax-update-request-pengadaan/{$da->id_mrp_inventory_spesifik}")."',"
                         ."data: dataString2,"
                         ."dataType : 'html',"
                         ."success: function(data) {"
                         . 'var hasil = $.parseJSON(data);'
//                                 . "alert(hasil.hasil);"
 //                        . "$('#script-tambahan').html(hasil.hasil);"

                         ."},"
                      ."});"    
                 . "});"
             .   "</script>"
              );
            }
        }
    $return['hasil'] = $hasil;
//    $this->debug($return, true);
    print json_encode($return);
    die;
  }
  
      function ajax_form_mrp_request_pengadaan2($type,$id_mrp_request,$start = 0){
     
      $status = $this->global_models->get_field("mrp_request", "status", 
                    array("id_mrp_request" => "{$id_mrp_request}"));
                    
      $dt_user = $this->global_models->get_field("mrp_request", "create_by_users", 
                    array("id_mrp_request" => "{$id_mrp_request}"));              
      
      $where = "WHERE A.status = 1 AND B.id_mrp_type_inventory = {$type} AND jumlah IS NOT NULL";
      
      
      $data = $this->global_models->get_query("SELECT B.name AS inventory_umum,C.title AS brand,D.title AS satuan,"
        . "A.id_mrp_inventory_spesifik,A.title AS title_spesifik,E.jumlah,F.id_mrp_request,F.status AS status_request,E.rg"
        . " FROM mrp_inventory_spesifik AS A"
        . " LEFT JOIN mrp_inventory_umum AS B ON A.id_mrp_inventory_umum = B.id_mrp_inventory_umum"
        . " LEFT JOIN mrp_brand AS C ON A.id_mrp_brand = C.id_mrp_brand"
        . " LEFT JOIN mrp_satuan AS D ON A.id_mrp_satuan = D.id_mrp_satuan"
        . " LEFT JOIN mrp_request_asset AS E ON (A.id_mrp_inventory_spesifik = E.id_mrp_inventory_spesifik AND E.id_mrp_request = '{$id_mrp_request}')"
        . " LEFT JOIN mrp_request AS F ON E.id_mrp_request = F.id_mrp_request"
        . " {$where} "
        . " ORDER BY A.id_mrp_inventory_spesifik ASC"
        . " LIMIT {$start}, 10");
        
//    print $this->db->last_query(); die;
//    $data_array = json_decode($data);
//    $this->debug($data, true);
    if(count($data) > 0){
      $return['status'] = 2;
      $return['start']  = $start + 10;
    }
    else{
      $return['status'] = 3;
    }
    
    $url = base_url('mrp/create-task-orders');
    $no = $start;
    if($type == 1){
            foreach ($data AS $da){
                
                $data_id = "id=dt_id_mrp_inventory_spesifik_".$da->id_mrp_inventory_spesifik;
                $data_jumlah = "id=jumlah".$da->id_mrp_inventory_spesifik;
                
            if($da->jumlah){
                $jumlah = $da->jumlah;
            }else{
                $jumlah = 0;
            }
//            if($da->status_request == 3){
//                $dta_jumlah = $this->form_eksternal->form_input("jumlah[]", $jumlah, $data_jumlah.' style="width:100px" class="form-control jumlah input-sm" placeholder="Jumlah"');
//            }
          $no =$no + 1;
          $hasil[] = array(
              $no,
            $da->inventory_umum." ".$da->title_spesifik,
            $da->satuan,
            $this->form_eksternal->form_input("id_mrp_inventory_spesifik[]", $da->id_mrp_inventory_spesifik, $data_id.' style="width:100px;display:none" class="form-control id_spesifik input-sm" placeholder=""').
            $jumlah
            );
        }
        }elseif(($type >= 2 OR $type <= 5) OR ($type >= 7 OR $type <= 9)){
            foreach ($data AS $da){
            if($da->jumlah){
                $jumlah = $da->jumlah;
            }else{
                $jumlah = 0;
            }
            
            if($da->rg){
                $rg = $da->rg;
            }else{
                $rg = 0;
            }
            
            
            $data_id = "id=dt_id_mrp_inventory_spesifik_".$da->id_mrp_inventory_spesifik;
            $data_jumlah = "id=jumlah".$da->id_mrp_inventory_spesifik;
              
            if($da->status_request == 3){
                $dta_jumlah = $this->form_eksternal->form_input("jumlah[]", $jumlah, $data_jumlah.' style="width:100px" class="form-control jumlah input-sm" placeholder="Jumlah"');
            }
            
          $hasil[] = array(
              $no =$no + 1,
            $da->inventory_umum." ".$da->title_spesifik,
            $da->brand,
            $da->satuan,
            $this->form_eksternal->form_input("id_mrp_inventory_spesifik[]", $da->id_mrp_inventory_spesifik, $data_id.' style="width:100px;display:none" class="form-control id_spesifik input-sm" placeholder=""').
            $jumlah,
            $rg  
              );
            }
        }
    $return['hasil'] = $hasil;
//    $this->debug($return, true);
    print json_encode($return);
    die;
  }
  
  function ajax_update_request_pengadaan($id_mrp_inventory_spesifik = 0) {
     $id_mrp_request = $_POST["id_mrp_request"];
     $jumlah = $_POST["jumlah"];
       $kirim = array(
            "jumlah"                       => $jumlah,
            "update_by_users"               => $this->session->userdata("id"),
            "update_date"                   => date("Y-m-d H:i:s")
        );
       $dta = $this->global_models->update("mrp_request_asset", array("id_mrp_request" => $id_mrp_request,"id_mrp_inventory_spesifik" => $id_mrp_inventory_spesifik),$kirim);
    
       if($dta > 0){
           $hasil = "Data Berhasil di Update";
       }else{
           $hasil = "data Gagal di update";
       }
       
    $return['hasil'] = $hasil;
    print json_encode($return);
    die;
        
  }
  
  function get_form_mrp_request_pengadaan_cetakan($id_mrp_request = 0,$id_users = 0,$start = 0){
       $status = $this->global_models->get_field("mrp_request", "status", 
                    array("id_mrp_request" => "{$id_mrp_request}"));
                    
      $dt_user = $this->global_models->get_field("mrp_request", "create_by_users", 
                    array("id_mrp_request" => "{$id_mrp_request}"));  
                    
      $ls = $this->global_models->get_query("SELECT B.id_hr_company,B.id_hr_master_organisasi"
        . " FROM m_users AS A"
        . " LEFT JOIN hr_pegawai AS B ON A.id_users = B.id_users"
        . " WHERE A.id_users = '{$id_users}'");
      
       
      $disabled ="";
      $where = "WHERE A.status = 1 AND B.id_mrp_type_inventory = '1'";
       if($id_mrp_request > 0){
          $where .= " AND jumlah IS NOT NULL";
      }
      if($status == 1 AND  $this->session->userdata("id") != $dt_user){
//          $where .= " AND jumlah IS NOT NULL";
          $disabled = "disabled ";  
      }elseif($status > 1){
//          $where .= " AND jumlah IS NOT NULL";
          $disabled = "disabled ";  
      }
      
       if($this->session->userdata("id") == 1 OR ($status == 2 AND $this->nbscache->get_olahan("permission", $this->session->userdata("id_privilege"), "approval-ro", "edit") !== FALSE)){
       $disabled ="";
      }
      
      if($this->session->userdata("id") == 1 OR $status == 11 OR  $this->nbscache->get_olahan("permission", $this->session->userdata("id_privilege"), "edit-form-ro", "edit") !== FALSE){
       $disabled ="";
      }
      
//      $data = $this->global_models->get_query("SELECT B.name AS inventory_umum,C.title AS brand,D.title AS satuan,"
//        . "A.id_mrp_inventory_spesifik,A.title AS title_spesifik,E.jumlah,E.rg,G.harga,I.id_mrp_setting_lock_atk"
//        . " ,E.rg,E.id_mrp_request_asset AS id_spesifik,F.status"
//        . " FROM mrp_inventory_spesifik AS A"
//        . " LEFT JOIN mrp_inventory_umum AS B ON A.id_mrp_inventory_umum = B.id_mrp_inventory_umum"
//        . " LEFT JOIN mrp_brand AS C ON A.id_mrp_brand = C.id_mrp_brand"
//        . " LEFT JOIN mrp_satuan AS D ON A.id_mrp_satuan = D.id_mrp_satuan"
//        . " LEFT JOIN mrp_request_asset AS E ON (A.id_mrp_inventory_spesifik = E.id_mrp_inventory_spesifik AND E.id_mrp_request = '{$id_mrp_request}')"
//        . " LEFT JOIN mrp_request AS F ON E.id_mrp_request = F.id_mrp_request"
//        . " LEFT JOIN mrp_supplier_inventory AS G ON (A.id_mrp_inventory_spesifik = G.id_mrp_inventory_spesifik AND G.id_mrp_supplier = '{$id_mrp_supplier}')"
//        . " LEFT JOIN mrp_setting_lock_atk_asset AS H ON A.id_mrp_inventory_spesifik = H.id_mrp_inventory_spesifik"
//        . " LEFT JOIN mrp_setting_lock_atk AS I ON (H.id_mrp_setting_lock_atk = I.id_mrp_setting_lock_atk AND I.id_hr_company = '{$ls[0]->id_hr_company}' AND I.id_hr_master_organisasi ='{$ls[0]->id_hr_master_organisasi}')"        
//        . " {$where} "
//        . " GROUP BY A.id_mrp_inventory_spesifik"
//        . " ORDER BY A.id_mrp_inventory_spesifik ASC"
//        . " LIMIT {$start}, 10");
        
      $data = $this->global_models->get_query("SELECT B.name AS inventory_umum,D.title AS satuan,A.title AS title_spesifik,"
        . "A.id_mrp_inventory_spesifik,E.jumlah,E.rg,E.id_mrp_request_asset AS id_spesifik,F.create_by_users,F.status"
        . " FROM mrp_inventory_spesifik AS A"
        . " LEFT JOIN mrp_inventory_umum AS B ON A.id_mrp_inventory_umum = B.id_mrp_inventory_umum"
//        . " LEFT JOIN mrp_brand AS C ON A.id_mrp_brand = C.id_mrp_brand"
        . " LEFT JOIN mrp_satuan AS D ON A.id_mrp_satuan = D.id_mrp_satuan"
        . " LEFT JOIN mrp_request_asset AS E ON (A.id_mrp_inventory_spesifik = E.id_mrp_inventory_spesifik AND E.id_mrp_request = '{$id_mrp_request}')"
        . " LEFT JOIN mrp_request AS F ON E.id_mrp_request = F.id_mrp_request"
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
    $no     = $start;
    $url    = base_url()."themes/".DEFAULTTHEMES."/img/ajax-loader.gif";
    foreach ($data AS $da){
        if($da->jumlah){
            $jumlah = $da->jumlah;
        }else{
            $jumlah = 0;
        }
        
        if($da->rg){
            $rg = $da->rg;
        }else{
            $rg = 0;
        }
        
        $data_id = "id=dt_id_mrp_inventory_spesifik_".$da->id_mrp_inventory_spesifik;
        $data_jumlah = "id=jumlah".$da->id_mrp_inventory_spesifik;
        $data_jumlah_rg = "id=jumlah_rg".$da->id_mrp_inventory_spesifik;
        $no =$no + 1;
        $dt_jumlah = $this->form_eksternal->form_input("jumlah[]", $jumlah, $disabled.$data_jumlah.' style="width:100px" class="form-control jumlah input-sm" placeholder="Jumlah"').
        $this->form_eksternal->form_input("jumlah_rg[]", $rg, $data_jumlah_rg.'  style="width:100px;display:none" class="form-control jumlah_rg input-sm" placeholder="rg"');
       
        $btn_del = "";
        if($da->id_spesifik){
             if($id_users ==1 OR $da->create_by_users == $id_users OR $this->nbscache->get_olahan("permission", $this->session->userdata("id_privilege"), "edit-form-ro", "edit") !== FALSE OR $this->nbscache->get_olahan("permission", $this->session->userdata("id_privilege"), "approval-ro", "edit") !== FALSE){
           $btn_del =  "<div class='btn-group'>"
        . "<a href='javascript:void(0)' onclick='delete_request_pengadaan({$da->id_spesifik})' id='del_{$da->id_spesifik}' class='btn btn-danger btn-flat' style='width: 40px'><i class='fa fa-trash-o'></i></a>"
        ."<span style='display: none; margin-left: 10px;' id='img-page-{$da->id_spesifik}'><img width='35px' src='{$url}' /></span>"
        . "</div>";
         }
        
        }
        
        if($da->status < 6  OR $da->status == 12){
            $dt_jumlah = $dt_jumlah;
            $btn_del = $btn_del;
        }else{
            $dt_jumlah = $jumlah;
            $btn_del = "";
        }
        
      $hasil[] = array(
          $no,
        $da->inventory_umum." ".$da->title_spesifik,
        $da->satuan,
        $this->form_eksternal->form_input("id_mrp_inventory_spesifik[]", $da->id_mrp_inventory_spesifik, $data_id.' style="width:100px;display:none" class="form-control id_spesifik input-sm" placeholder=""').
        $dt_jumlah,
        $btn_del
      );
    }
    $return['hasil'] = $hasil;
//    $this->debug($return, true);
    print json_encode($return);
    die;
  }
  
   function get_list_form_mrp_request_pengadaan_cetakan($id_mrp_request = 0,$id_users = 0,$start = 0){
       $status = $this->global_models->get_field("mrp_request", "status", 
                    array("id_mrp_request" => "{$id_mrp_request}"));
                    
      $dt_user = $this->global_models->get_field("mrp_request", "create_by_users", 
                    array("id_mrp_request" => "{$id_mrp_request}"));  
                    
      $ls = $this->global_models->get_query("SELECT B.id_hr_company,B.id_hr_master_organisasi"
        . " FROM m_users AS A"
        . " LEFT JOIN hr_pegawai AS B ON A.id_users = B.id_users"
        . " WHERE A.id_users = '{$id_users}'");
      
      $disabled ="";
      $where = "WHERE A.status = 1 AND B.id_mrp_type_inventory = '1'";
      if($status == 1 AND  $this->session->userdata("id") != $dt_user){
//          $where .= " AND jumlah IS NOT NULL";
          $disabled = "disabled ";  
      }elseif($status > 1){
//          $where .= " AND jumlah IS NOT NULL";
          $disabled = "disabled ";  
      }
      
       if($this->session->userdata("id") == 1 OR ($status == 2 AND $this->nbscache->get_olahan("permission", $this->session->userdata("id_privilege"), "approval-ro", "edit") !== FALSE)){
       $disabled ="";
      }
      
      if($this->session->userdata("id") == 1 OR $status == 11 OR $this->nbscache->get_olahan("permission", $this->session->userdata("id_privilege"), "edit-form-ro", "edit") !== FALSE){
       $disabled ="";
      }
      

      
      $data = $this->global_models->get_query("SELECT B.name AS inventory_umum,D.title AS satuan,A.title AS title_spesifik,"
        . "A.id_mrp_inventory_spesifik,E.jumlah"
        . " FROM mrp_inventory_spesifik AS A"
        . " LEFT JOIN mrp_inventory_umum AS B ON A.id_mrp_inventory_umum = B.id_mrp_inventory_umum"
//        . " LEFT JOIN mrp_brand AS C ON A.id_mrp_brand = C.id_mrp_brand"
        . " LEFT JOIN mrp_satuan AS D ON A.id_mrp_satuan = D.id_mrp_satuan"
        . " LEFT JOIN mrp_request_asset AS E ON (A.id_mrp_inventory_spesifik = E.id_mrp_inventory_spesifik AND E.id_mrp_request = '{$id_mrp_request}')"
        . " LEFT JOIN mrp_request AS F ON E.id_mrp_request = F.id_mrp_request"
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
        if($da->jumlah){
            $jumlah = $da->jumlah;
        }else{
            $jumlah = 0;
        }
        $data_id = "id=dt_id_mrp_inventory_spesifik_".$da->id_mrp_inventory_spesifik;
        $data_jumlah = "id=jumlah".$da->id_mrp_inventory_spesifik;
        $data_jumlah_rg = "id=jumlah_rg".$da->id_mrp_inventory_spesifik;
        $no =$no + 1;
        
        $dt_jumlah = $this->form_eksternal->form_input("jumlah[]", $jumlah, $disabled.$data_jumlah.'  style="width:100px" class="form-control jumlah input-sm" placeholder="Jumlah"').
        $this->form_eksternal->form_input("jumlah_rg[]", $rg, $data_jumlah_rg.'  style="width:100px;display:none" class="form-control jumlah_rg input-sm" placeholder="rg"');
      $hasil[] = array(
          $no,
        $da->inventory_umum." ".$da->title_spesifik,
        $da->satuan,
        $this->form_eksternal->form_input("id_mrp_inventory_spesifik[]", $da->id_mrp_inventory_spesifik, $data_id.' style="width:100px;display:none" class="form-control id_spesifik input-sm" placeholder=""').
        $dt_jumlah
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
  
   function draft_form_mrp_request_pengadaan($type_inventory = 0){
     // $type_inventory 1 = Cetakan, 2 = ATK;
       
     $pst = $_POST;
     $id_spesifik = $pst['id_spesifik'];
     $jumlah =  $pst['jumlah'];
     $note =  $pst['note'];
     $id_pegawai =  $pst['id_hr_pegawai'];
     $id_mrp_request =  $pst['id_mrp_request'];
     $id_receiving = $pst['id_receiver'];
     
    $arr_id = explode(",",$id_spesifik);
    $arr_jumlah = explode(",",$jumlah);
    $id_hr_pegawai = $this->global_models->get_field("hr_pegawai", "id_hr_pegawai", 
                    array("id_users" => $this->session->userdata("id")));
//    $aa = array($id_spesifik,$jumlah,$note,$id_mrp_request);
//      print_r($aa); die;    
      
      $received =$this->global_models->get_field("hr_pegawai", "id_hr_pegawai",
           array("id_users" => "{$this->session->userdata("id")}"));
    if($id_receiving > 0){
        $id_receiver = $id_receiving;
    }else{
        $id_receiver = $received;
    }  
    
    if($id_mrp_request > 0){
        
         $id_hr_pegawai = $this->global_models->get_field("mrp_request", "id_hr_pegawai", 
                    array("id_mrp_request" => $id_mrp_request));
       
        if($id_pegawai != 0){
        $dt_hr_pegawai = $id_pegawai;
        }else{
        $dt_hr_pegawai = $id_hr_pegawai;
        }
        
        $kirim = array(
            "id_hr_pegawai"               => $dt_hr_pegawai,
            "user_pegawai_receiver"       => $id_receiver,
            "note"                        => $note,
            "status"                      => 2,
            "update_by_users"             => $this->session->userdata("id"),
            "update_date"                 => date("Y-m-d H:i:s")
        );
        $this->global_models->update("mrp_request", array("id_mrp_request" => $id_mrp_request),$kirim);
       
       if($type_inventory == 1 OR $type_inventory == 2){
            $kirim1 = array(
            "id_mrp_request"                => $id_mrp_request,
            "type"                          => 1,
            "status"                        => 1,
            "create_by_users"               => $this->session->userdata("id"),
            "create_date"                   => date("Y-m-d H:i:s")
        );
        $this->global_models->insert("temp_alert_email", $kirim1);
       
        }
        
        $this->global_models->delete("mrp_request_asset", array("id_mrp_request" => $id_mrp_request));
        foreach ($arr_jumlah as $key => $val2) {
            if($val2 > 0){
                    $kirim = array(
                    "id_mrp_request"                => $id_mrp_request,
                    "id_mrp_inventory_spesifik"     => $arr_id[$key],
                    "jumlah"                        => $val2,   
                    "status"                        => 2,
                    "create_by_users"               => $this->session->userdata("id"),
                    "create_date"                   => date("Y-m-d H:i:s")
                );
                $this->global_models->insert("mrp_request_asset", $kirim);
            }
        }
    }else{
        if($id_pegawai != 0){
        $dt_hr_pegawai = $id_pegawai;
        }else{
        $dt_hr_pegawai = $id_hr_pegawai;
        }
        $this->olah_request_order_code($kode);
        $kirim = array(
           "id_hr_pegawai"                => $dt_hr_pegawai,
            "note"                        => $note,
            "code"                        => $kode,
            "user_pegawai_receiver"       => $id_receiver,
            "status"                      => 2,
            "status_blast"                => 3,
            "type_inventory"              => $type_inventory,
            "create_by_users"             => $this->session->userdata("id"),
            "create_date"                 => date("Y-m-d H:i:s")
        );
        $id_mrp_request = $this->global_models->insert("mrp_request", $kirim);
        
        if($type_inventory == 1 OR $type_inventory == 2){
            $kirim1 = array(
            "id_mrp_request"                => $id_mrp_request,
            "type"                          => 1,
            "status"                        => 1,
            "create_by_users"               => $this->session->userdata("id"),
            "create_date"                   => date("Y-m-d H:i:s")
        );
        $this->global_models->insert("temp_alert_email", $kirim1);
       
        }
        
         foreach ($arr_jumlah as $key => $val) {
            if($val > 0){
                $kirim = array(
                "id_mrp_request"                => $id_mrp_request,
                "id_mrp_inventory_spesifik"     => $arr_id[$key],
                "jumlah"                        => $val,   
                "status"                        => 2,
                "create_by_users"               => $this->session->userdata("id"),
                "create_date"                   => date("Y-m-d H:i:s")
            );
            $this->global_models->insert("mrp_request_asset", $kirim);
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
//    print json_encode($return);
    die;
  }
  
    function insert_form_mrp_request_pengadaan($type_inventory = 0){
     // $type_inventory 1 = Cetakan, 2 = ATK;
      $pst = $_REQUEST;
   $id_spesifik = $pst['id_spesifik'];
     $jumlah =  $pst['jumlah'];
     $note =  $pst['note'];
     $id_pegawai =  $pst['id_hr_pegawai'];
    $id_mrp_request =  $pst['id_mrp_request'];
    $id_receiving = $pst['id_receiver'];
     
    $arr_id = explode(",",$id_spesifik);
    $arr_jumlah = explode(",",$jumlah);
    $arr_id_mrp_request = explode(",",$id_mrp_request);
//    $id_hr_pegawai = $this->global_models->get_field("hr_pegawai", "id_hr_pegawai", 
//                    array("id_users" => $this->session->userdata("id")));
//    $aa = array($id_spesifik,$jumlah,$note,$id_mrp_request);
//      print_r($aa); die;    
//      
   $received =$this->global_models->get_field("hr_pegawai", "id_hr_pegawai",
           array("id_users" => "{$this->session->userdata("id")}"));
    if($id_receiving > 0){
        $id_receiver = $id_receiving;
    }else{
        $id_receiver = $received;
    }
    
    if($id_mrp_request > 0){
        
       $get = $this->global_models->get("mrp_request", 
                    array("id_mrp_request" => $id_mrp_request));
       
       $id_receiver =$this->global_models->get_field("hr_pegawai", "id_hr_pegawai",
           array("id_users" => "{$get[0]->create_by_users}"));
           
        if($id_pegawai != 0){
        $dt_hr_pegawai = $id_pegawai;
        }else{
        $dt_hr_pegawai = $get[0]->id_hr_pegawai;
        }
        
        $kirim = array(
           "id_hr_pegawai"                => $dt_hr_pegawai,
            "user_pegawai_receiver"                => $id_receiver,
            "note"                        => $note,
            "update_by_users"               => $this->session->userdata("id"),
            "update_date"                   => date("Y-m-d H:i:s")
        );
        $this->global_models->update("mrp_request", array("id_mrp_request" => $id_mrp_request),$kirim);
//       print $this->db->last_query()."bb";
//       die;
        $this->global_models->delete("mrp_request_asset", array("id_mrp_request" => $id_mrp_request));
        foreach ($arr_jumlah as $key => $val2) {
            if($val2 > 0){
                    $kirim = array(
                    "id_mrp_request"                => $id_mrp_request,
                    "id_mrp_inventory_spesifik"     => $arr_id[$key],
                    "jumlah"                        => $val2,   
                    "status"                        => 1,
                    "create_by_users"               => $this->session->userdata("id"),
                    "create_date"                   => date("Y-m-d H:i:s")
                );
                $this->global_models->insert("mrp_request_asset", $kirim);
            }
        }
    }else{
        
        if($id_pegawai != 0){
        $dt_hr_pegawai = $id_pegawai;
        }else{
        $dt_hr_pegawai = $received;
        }
        
        $this->olah_request_order_code($kode);
        $kirim = array(
           "id_hr_pegawai"                => $dt_hr_pegawai,
            "user_pegawai_receiver"       => $id_receiver,
            "note"                        => $note,
            "code"                        => $kode,
            "status"                      => 1,
            "status_blast"                => 3,
            "type_inventory"              => $type_inventory,
            "create_by_users"             => $this->session->userdata("id"),
            "create_date"                 => date("Y-m-d H:i:s")
        );
        $id_mrp_request = $this->global_models->insert("mrp_request", $kirim);
//        print $this->db->last_query()."bb";
//       die;
        $this->global_models->delete("mrp_request_asset", array("id_mrp_request" => "{$id_mrp_request}"));
         foreach ($arr_jumlah as $key => $val) {
            if($val > 0){
                $kirim = array(
                "id_mrp_request"                => $id_mrp_request,
                "id_mrp_inventory_spesifik"     => $arr_id[$key],
                "jumlah"                        => $val,   
                "status"                        => 1,
                "create_by_users"               => $this->session->userdata("id"),
                "create_date"                   => date("Y-m-d H:i:s")
            );
            $this->global_models->insert("mrp_request_asset", $kirim);
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
//    print json_encode($return);
    die;
  }
  
    function update_form_mrp_request_pengadaan($type_inventory = 0){
     // $type_inventory 1 = Cetakan, 2 = ATK;
        $pst = $_POST;
    $id_spesifik = $pst['id_spesifik'];
    $jumlah =  $pst['jumlah'];
    $jumlah_rg =  $pst['jumlah_rg'];
    $note =  $pst['note'];
    $id_pegawai =  $pst['id_hr_pegawai'];
    $id_mrp_request =  $pst['id_mrp_request'];
    $id_receiving = $pst['id_receiver'];
     
    $arr_id = explode(",",$id_spesifik);
    $arr_jumlah = explode(",",$jumlah);
    $arr_jumlah_rg = explode(",",$jumlah_rg);
    $id_hr_pegawai = $this->global_models->get_field("hr_pegawai", "id_hr_pegawai", 
                    array("id_users" => $this->session->userdata("id")));
    
    $status = $this->global_models->get_field("mrp_request", "status", 
                    array("id_mrp_request" => $id_mrp_request));
    
//    $aa = array($id_spesifik,$jumlah,$note,$id_mrp_request);
//      print_r($aa); die;    
      
      $received =$this->global_models->get_field("hr_pegawai", "id_hr_pegawai",
           array("id_users" => "{$this->session->userdata("id")}"));
    if($id_receiving > 0){
        $id_receiver = $id_receiving;
    }else{
        $id_receiver = $received;
    }  
    
    if($id_mrp_request > 0){
        if($id_pegawai !=0){
             $kirim = array(
            "id_hr_pegawai"              => $id_pegawai,
            "user_pegawai_receiver"              => $id_receiver,     
            "note"                        => $note,
            "status"                       => $status,
            "update_by_users"               => $this->session->userdata("id"),
            "update_date"                   => date("Y-m-d H:i:s")
            );
            $this->global_models->update("mrp_request", array("id_mrp_request" => $id_mrp_request),$kirim);
//            print $this->db->last_query();
//            die('cc');
        }else{
            $kirim = array(
            "note"                        => $note,
            "user_pegawai_receiver"        => $id_receiver,         
            "status"                       => $status,
            "update_by_users"               => $this->session->userdata("id"),
            "update_date"                   => date("Y-m-d H:i:s")
            );
            $this->global_models->update("mrp_request", array("id_mrp_request" => $id_mrp_request),$kirim);
        
//            print $this->db->last_query();
//            die('kk');
        }
       
        $this->global_models->delete("mrp_request_asset", array("id_mrp_request" => $id_mrp_request));
        foreach ($arr_jumlah as $key => $val2) {
            if($val2 > 0){
                    $kirim = array(
                    "id_mrp_request"                => $id_mrp_request,
                    "id_mrp_inventory_spesifik"     => $arr_id[$key],
                    "jumlah"                        => $val2,
                    "rg"                            => $arr_jumlah_rg[$key],    
                    "status"                        => $status,
                    "create_by_users"               => $this->session->userdata("id"),
                    "create_date"                   => date("Y-m-d H:i:s")
                );
                $this->global_models->insert("mrp_request_asset", $kirim);
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
//    print json_encode($return);
    die;
  }
   
   function get_supplier($id_mrp_task_orders){
       
       $data = $this->global_models->get_query("SELECT B.id_mrp_supplier"
        . " FROM mrp_task_orders_request_asset AS A"
        . " LEFT JOIN mrp_supplier_inventory AS B ON A.id_mrp_inventory_spesifik = B.id_mrp_inventory_spesifik"
        . " WHERE A.id_mrp_task_orders = '{$id_mrp_task_orders}' AND B.status =1"
        . " GROUP BY B.id_mrp_supplier"
        . " ORDER BY A.id_mrp_task_orders ASC"
        );
        
        foreach ($data as $key => $val) {
            if($val->id_mrp_supplier){
                $val2 .= $val->id_mrp_supplier." ";
            }
        }
        
        $val3 = rtrim($val2);
        $dts = str_replace(" ",",", $val3);
       
    if (empty($_GET['term'])) exit ;
    $q = strtolower($_GET["term"]);
    if (get_magic_quotes_gpc()) $q = stripslashes($q);
    $items = $this->global_models->get_query("
      SELECT *
      FROM mrp_supplier
      WHERE 
      LOWER(name) LIKE '%{$q}%' AND id_mrp_supplier IN ({$dts})
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
  
  function get_company(){
      
    if (empty($_GET['term'])) exit ;
    $q = strtolower($_GET["term"]);
    if (get_magic_quotes_gpc()) $q = stripslashes($q);
    $items = $this->global_models->get_query("
      SELECT *
      FROM hr_company
      WHERE 
      LOWER(title) LIKE '%{$q}%'
      LIMIT 0,10
      ");
    if(count($items) > 0){
      foreach($items as $tms){
        $result[] = array(
            "id"    => $tms->id_hr_company,
            "label" => $tms->title,
            "value" => $tms->title,
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
   
   function delete_request_pengadaan(){
       $id_mrp_request_asset = $_POST['id_mrp_request_asset'];
    $this->global_models->delete("mrp_request_asset", array("id_mrp_request_asset" => $id_mrp_request_asset));
    die;
  }

function update_rg($id_mrp_receiving_goods_po = 0){
//     $persen = ceil($ke/7 * 100);
  $pst = $_REQUEST;
  $id_mrp_receiving_goods_po_asset = $pst['id_mrp_receiving_goods_po_asset'];
  $rg =  $pst['rg'];
 
  $tgl_diterima =  $pst['tgl_diterima'];
  $note =  $pst['note'];
  $no_rg = 0;
  
//  if($id_company > 0){
      $arr_id = explode(",",$id_mrp_receiving_goods_po_asset);
      $arr_rg = explode(",",$rg);
//       $this->debug($pst, true);
     $total = count($arr_id);
     $progress = ceil(1/$total * 100);
//      print "<br>";
//      print_r($arr_rg);
//      print "<br>";
//      print $arr_rg[$no_rg];
//      die;
//  $dt_status = array("5" => "Approve PO","4" => "Pengajuan PO", "3" => "Draft");
//    $data = array(1 =>$id_mrp_receiving_goods_po_asset,2 =>$rg,3 =>$tgl_diterima, 4=>$note, 5 => $id_mrp_receiving_goods_po);
//    $pst = $_REQUEST;
//    print_r($data);
//   
//    $this->debug($pst, true);
//    die;
      if($tgl_diterima){
     $this->olah_receiving_goods_code($kode_rg);
       $kirim = array(
        "id_mrp_receiving_goods_po"          => $id_mrp_receiving_goods_po,   
        "status"                             => 1,
        "tanggal_diterima"                   => $tgl_diterima,
        "code"                               => $kode_rg,  
        "note"                               => $note, 
        "create_by_users"                    => $this->session->userdata("id"),
        "create_date"                        => date("Y-m-d H:i:s")
      );
   $id_rg = $this->global_models->insert("mrp_receiving_goods", $kirim);
   $id_rg_department = $this->global_models->insert("mrp_receiving_goods_department", $kirim);
   
   $krm = array(
        "id_mrp_receiving_goods_po_asset"    => $id_mrp_receiving_goods_po_asset,   
        "jumlah_rg"                          => $rg,
        "tgl_diterima"                       => $tgl_diterima,
        "note"                               => $note,
       "id_mrp_receiving_goods"              => $id_rg,
       "id_mrp_receiving_goods_department"   => $id_rg_department,
        "create_by_users"                    => $this->session->userdata("id"),
        "create_date"                        => date("Y-m-d H:i:s")
      );
   $id_log_rg = $this->global_models->insert("log_rg", $krm);
   
   $flag_rg  = 0;
// foreach ($arr_id[$no_rg] as $ky => $val) {
   if($arr_id[$no_rg]){
    $dt_rg = $this->global_models->get_field("mrp_receiving_goods_po_asset", "rg",array("id_mrp_receiving_goods_po_asset" => "{$arr_id[$no_rg]}"));
    $dt_jml = $this->global_models->get_field("mrp_receiving_goods_po_asset", "jumlah",array("id_mrp_receiving_goods_po_asset" => "{$arr_id[$no_rg]}"));
     
    //cek field rg, 
     if($dt_rg){
         $dt_rg = $dt_rg;
     }else{
         $dt_rg = 0;
     }
     
     //Total RG
     $fix = $arr_rg[$no_rg] + $dt_rg;
     
     if($dt_jml > $fix){
         $fix_rg = $fix;
         $flag_rg = $flag_rg + 1;
     }else{
         $fix_rg = $dt_jml;
     }
     
   $get_task_order_asset[$no_rg] = $this->global_models->get("mrp_receiving_goods_po_asset",array("id_mrp_receiving_goods_po_asset" => "{$arr_id[$no_rg]}"));
   
   $jmlh_now = $dt_jml - $fix;
   if($jmlh_now >= 0){
       
     if($arr_rg[$no_rg] > 0){
           $kirim = array(
        "rg"                            => $fix_rg,
        "update_by_users"               => $this->session->userdata("id"),
        "update_date"                   => date("Y-m-d H:i:s")
        );
        $this->global_models->update("mrp_receiving_goods_po_asset", array("id_mrp_receiving_goods_po_asset" => $arr_id[$no_rg]),$kirim);

        $dt_mrp_stock = $this->global_models->get("mrp_stock",array("id_mrp_inventory_spesifik" => "{$get_task_order_asset[$no_rg][0]->id_mrp_inventory_spesifik}"));
        $kirim = array(
            "id_mrp_receiving_goods"            => $id_rg,   
            "id_mrp_stock"                      => $dt_mrp_stock[0]->id_mrp_stock,
            "id_mrp_satuan"                     => $get_task_order_asset[$no_rg][0]->id_mrp_satuan,
            "jumlah"                            => $arr_rg[$no_rg],
            "id_mrp_inventory_spesifik"         => $get_task_order_asset[$no_rg][0]->id_mrp_inventory_spesifik,
            "harga"                             => $get_task_order_asset[$no_rg][0]->harga,
            "status"                            => 1,
            "create_by_users"                   => $this->session->userdata("id"),
            "create_date"                       => date("Y-m-d H:i:s")
          );
        $dt_id_stock[$no_rg] = $this->global_models->insert("mrp_receiving_goods_detail", $kirim);
    
        
    if($dt_mrp_stock[0]->id_mrp_inventory_spesifik > 0){
        
        $dtstock_in             = $dt_mrp_stock[0]->stock_in + $arr_rg[$no_rg];
        $dtstock_out            = $dt_mrp_stock[0]->stock_out;
        $dtstock_akhir = $dtstock_in - $dtstock_out;
                
         $kirim = array(
        "stock_in"                      => $dtstock_in,
            
        "update_by_users"               => $this->session->userdata("id"),
        "update_date"                   => date("Y-m-d H:i:s")
    );
    $this->global_models->update("mrp_stock", array("id_mrp_inventory_spesifik" => $dt_mrp_stock[0]->id_mrp_inventory_spesifik),$kirim);
    $this->olah_stock_in_code($kode);
    $kirim = array(
        "id_mrp_stock"                          => $dt_mrp_stock[0]->id_mrp_stock,
        "kode"                                  => $kode,                            
        "id_mrp_receiving_goods_detail"         => $dt_id_stock[$no_rg],
        "id_mrp_inventory_spesifik"             => $dt_mrp_stock[0]->id_mrp_inventory_spesifik,
        "id_mrp_satuan"                         => $dt_mrp_stock[0]->id_mrp_satuan,    
        "id_mrp_supplier"                       => $get_task_order_asset[$no_rg][0]->id_mrp_supplier,
        "jumlah"                                => $arr_rg[$no_rg],  
        "tanggal_diterima"                      => $tgl_diterima, 
        "harga"                                 => $get_task_order_asset[$no_rg][0]->harga,
        "status"                                => 1,
        "create_by_users"                       => $this->session->userdata("id"),
        "create_date"                           => date("Y-m-d H:i:s")
      );
     $id_mrp_stock_in = $this->global_models->insert("mrp_stock_in", $kirim);
     
    }else{
      $kirim = array( 
        "id_mrp_inventory_spesifik"         => $get_task_order_asset[$ky][0]->id_mrp_inventory_spesifik,
        "id_mrp_satuan"                     => $get_task_order_asset[$ky][0]->id_mrp_satuan,   
        "stock_in"                          => $arr_rg[$no_rg],
        "status"                            => 1,
        "create_by_users"                   => $this->session->userdata("id"),
        "create_date"                       => date("Y-m-d H:i:s")
      );
     $id_stock[$ky] = $this->global_models->insert("mrp_stock", $kirim);
     
     $this->olah_stock_in_code($kode);
     $kirim = array(
        "id_mrp_stock"                          => $id_stock[$ky],
        "kode"                                  => $kode,  
        "id_mrp_receiving_goods_detail"         => $dt_id_stock[$no_rg],
        "id_mrp_inventory_spesifik"             => $get_task_order_asset[$no_rg][0]->id_mrp_inventory_spesifik,
        "id_mrp_satuan"                         => $get_task_order_asset[$no_rg][0]->id_mrp_satuan,    
        "id_mrp_supplier"                       => $get_task_order_asset[$no_rg][0]->id_mrp_supplier,
        "jumlah"                                => $arr_rg[$no_rg], 
        "tanggal_diterima"                      => $tgl_diterima, 
        "harga"                                 => $get_task_order_asset[$no_rg][0]->harga,
        "status"                                => 1,
        "create_by_users"                       => $this->session->userdata("id"),
        "create_date"                           => date("Y-m-d H:i:s")
      );
     $id_mrp_stock_in = $this->global_models->insert("mrp_stock_in", $kirim);
     
    }
    $this->load->model('mrp/mmrp');
    $this->mmrp->mutasi_rg($id_mrp_stock_in,$get_task_order_asset[$no_rg][0]->id_mrp_receiving_goods_po,$get_task_order_asset[$no_rg][0]->id_mrp_inventory_spesifik,0,$id_rg_department,$tgl_diterima,$note);
   
    $dtrg = $this->global_models->get_query("SELECT A.id_mrp_po,SUM(B.rg) AS rg,SUM(B.jumlah) AS jumlah FROM mrp_receiving_goods_po AS A "
                . " LEFT JOIN mrp_receiving_goods_po_asset AS B ON A.id_mrp_receiving_goods_po = B.id_mrp_receiving_goods_po"
                . " WHERE A.id_mrp_receiving_goods_po = '{$get_task_order_asset[$no_rg][0]->id_mrp_receiving_goods_po}' "
                . " GROUP BY A.id_mrp_receiving_goods_po ");
    $hsl_rg = $dtrg[0]->jumlah - $dtrg[0]->rg;
    
    $status = 7;
    if($hsl_rg == 0){
            $status = 8;
        $kirim2 = array(
        "status"                        => 7,
        "update_by_users"               => $v->create_by_users,
        "update_date"                   => date("Y-m-d H:i:s")
    );
    $this->global_models->update("mrp_po", array("id_mrp_po" => $dtrg[0]->id_mrp_po),$kirim2);  
    }
    
    $krm2 = array(
        "status"                            => $status,
        "update_by_users"                   => $this->session->userdata("id"),
        "update_date"                       => date("Y-m-d H:i:s")
    );
     $this->global_models->update("mrp_receiving_goods_po", array("id_mrp_receiving_goods_po" => $get_task_order_asset[$no_rg][0]->id_mrp_receiving_goods_po),$krm2);
      
    }
          
    $this->session->set_flashdata('success', 'Data Berhasil di Proses');
   }else{
    $this->session->set_flashdata('notice', 'Data tidak tersimpan');
   }
   
   }
//     }
     $next_no = $no_rg + 1;
     $return = array(
      "status"      => 1,
      "id_rg"        => $id_log_rg,
      "number"      => $next_no,   
      "progress"      => $progress,
    );
      }else{
          $this->session->set_flashdata('notice', 'Data tidak tersimpan');
        $return['status'] = 2;
        
      }

        print json_encode($return);
        die;
}

 function set_rg($id_mrp_receiving_goods_po,$id_log,$number){
     
     $get = $this->global_models->get("log_rg",array("id_log_rg" => "{$id_log}"));
     
     $id_mrp_receiving_goods_po_asset = $pst['id_mrp_receiving_goods_po_asset'];
  
    $no_rg = $number;
  
//  if($id_company > 0){
      $arr_id = explode(",",$get[0]->id_mrp_receiving_goods_po_asset);
      $arr_rg = explode(",",$get[0]->jumlah_rg);
     $total = count($arr_id);
     $totl = $number+1;
     $progress = ceil($totl/$total * 100);
//      print "<br>";
//      print_r($arr_rg);
//      print "<br>";
//      print $arr_rg[$no_rg];
//      die;

      if($get[0]->tgl_diterima){
     
   
   $flag_rg  = 0;
// foreach ($arr_id[$no_rg] as $ky => $val) {
   if($arr_id[$number]){
    $dt_rg = $this->global_models->get_field("mrp_receiving_goods_po_asset", "rg",array("id_mrp_receiving_goods_po_asset" => "{$arr_id[$number]}"));
    $dt_jml = $this->global_models->get_field("mrp_receiving_goods_po_asset", "jumlah",array("id_mrp_receiving_goods_po_asset" => "{$arr_id[$number]}"));
     
    //cek field rg, 
     if($dt_rg){
         $dt_rg = $dt_rg;
     }else{
         $dt_rg = 0;
     }
     
     //Total RG
     $fix = $arr_rg[$number] + $dt_rg;
     
     if($dt_jml > $fix){
         $fix_rg = $fix;
         $flag_rg = $flag_rg + 1;
     }else{
         $fix_rg = $dt_jml;
     }
     
   $get_task_order_asset = $this->global_models->get("mrp_receiving_goods_po_asset",array("id_mrp_receiving_goods_po_asset" => "{$arr_id[$number]}"));
   
   $jmlh_now = $dt_jml - $fix;
   if($jmlh_now >= 0){
       
     if($arr_rg[$number] > 0){
           $kirim = array(
        "rg"                            => $fix_rg,
        "update_by_users"               => $this->session->userdata("id"),
        "update_date"                   => date("Y-m-d H:i:s")
        );
        $this->global_models->update("mrp_receiving_goods_po_asset", array("id_mrp_receiving_goods_po_asset" => $arr_id[$number]),$kirim);

        $dt_mrp_stock = $this->global_models->get("mrp_stock",array("id_mrp_inventory_spesifik" => "{$get_task_order_asset[0]->id_mrp_inventory_spesifik}"));
        $kirim = array(
            "id_mrp_receiving_goods"            => $get[0]->id_mrp_receiving_goods,   
            "id_mrp_stock"                      => $dt_mrp_stock[0]->id_mrp_stock,
            "id_mrp_satuan"                     => $get_task_order_asset[0]->id_mrp_satuan,
            "jumlah"                            => $arr_rg[$number],
            "id_mrp_inventory_spesifik"         => $get_task_order_asset[0]->id_mrp_inventory_spesifik,
            "harga"                             => $get_task_order_asset[0]->harga,
            "status"                            => 1,
            "create_by_users"                   => $this->session->userdata("id"),
            "create_date"                       => date("Y-m-d H:i:s")
          );
        $dt_id_stock = $this->global_models->insert("mrp_receiving_goods_detail", $kirim);
    
        
    if($dt_mrp_stock[0]->id_mrp_inventory_spesifik > 0){
        
        $dtstock_in             = $dt_mrp_stock[0]->stock_in + $arr_rg[$number];
        $dtstock_out            = $dt_mrp_stock[0]->stock_out;
        $dtstock_akhir = $dtstock_in - $dtstock_out;
                
        $kirim = array(
        "stock_in"                      => $dtstock_in,
            
        "update_by_users"               => $this->session->userdata("id"),
        "update_date"                   => date("Y-m-d H:i:s")
    );
    $this->global_models->update("mrp_stock", array("id_mrp_inventory_spesifik" => $dt_mrp_stock[0]->id_mrp_inventory_spesifik),$kirim);
    $this->olah_stock_in_code($kode);
    $kirim = array(
        "id_mrp_stock"                          => $dt_mrp_stock[0]->id_mrp_stock,
        "kode"                                  => $kode,                            
        "id_mrp_receiving_goods_detail"         => $dt_id_stock,
        "id_mrp_inventory_spesifik"             => $dt_mrp_stock[0]->id_mrp_inventory_spesifik,
        "id_mrp_satuan"                         => $dt_mrp_stock[0]->id_mrp_satuan,    
        "id_mrp_supplier"                       => $get_task_order_asset[0]->id_mrp_supplier,
        "jumlah"                                => $arr_rg[$number],  
        "tanggal_diterima"                      => $get[0]->tgl_diterima, 
        "harga"                                 => $get_task_order_asset[0]->harga,
        "status"                                => 1,
        "create_by_users"                       => $this->session->userdata("id"),
        "create_date"                           => date("Y-m-d H:i:s")
      );
     $id_mrp_stock_in = $this->global_models->insert("mrp_stock_in", $kirim);
     
    }else{
      $kirim = array( 
        "id_mrp_inventory_spesifik"         => $get_task_order_asset[0]->id_mrp_inventory_spesifik,
        "id_mrp_satuan"                     => $get_task_order_asset[0]->id_mrp_satuan,   
        "stock_in"                          => $arr_rg[$number],
        "status"                            => 1,
        "create_by_users"                   => $this->session->userdata("id"),
        "create_date"                       => date("Y-m-d H:i:s")
      );
     $id_stock = $this->global_models->insert("mrp_stock", $kirim);
     
     $this->olah_stock_in_code($kode);
     $kirim = array(
        "id_mrp_stock"                          => $id_stock,
        "kode"                                  => $kode,  
        "id_mrp_receiving_goods_detail"         => $dt_id_stock,
        "id_mrp_inventory_spesifik"             => $get_task_order_asset[0]->id_mrp_inventory_spesifik,
        "id_mrp_satuan"                         => $get_task_order_asset[0]->id_mrp_satuan,    
        "id_mrp_supplier"                       => $get_task_order_asset[0]->id_mrp_supplier,
        "jumlah"                                => $arr_rg[$number], 
        "tanggal_diterima"                      => $get[0]->tgl_diterima,
        "harga"                                 => $get_task_order_asset[0]->harga,
        "status"                                => 1,
        "create_by_users"                       => $this->session->userdata("id"),
        "create_date"                           => date("Y-m-d H:i:s")
      );
     $id_mrp_stock_in = $this->global_models->insert("mrp_stock_in", $kirim);
     
    }
    $this->load->model('mrp/mmrp');
    $this->mmrp->mutasi_rg($id_mrp_stock_in,$get_task_order_asset[0]->id_mrp_receiving_goods_po,$get_task_order_asset[0]->id_mrp_inventory_spesifik,0,$get[0]->id_mrp_receiving_goods_department,$get[0]->tgl_diterima,$get[0]->note);
   
    $dtrg = $this->global_models->get_query("SELECT A.id_mrp_po,SUM(B.rg) AS rg,SUM(B.jumlah) AS jumlah FROM mrp_receiving_goods_po AS A "
                . " LEFT JOIN mrp_receiving_goods_po_asset AS B ON A.id_mrp_receiving_goods_po = B.id_mrp_receiving_goods_po"
                . " WHERE A.id_mrp_receiving_goods_po = '{$get_task_order_asset[0]->id_mrp_receiving_goods_po}' "
                . " GROUP BY A.id_mrp_receiving_goods_po ");
    $hsl_rg = $dtrg[0]->jumlah - $dtrg[0]->rg;
    
    $status = 7;
    if($hsl_rg == 0){
      $status = 8;
        $kirim2 = array(
        "status"                        => 7,
        "update_by_users"               => $v->create_by_users,
        "update_date"                   => date("Y-m-d H:i:s")
    );
    $this->global_models->update("mrp_po", array("id_mrp_po" => $dtrg[0]->id_mrp_po),$kirim2); 
    
    $kirim3 = array(
        "reminder"                      => 2,
        "update_by_users"               => $v->create_by_users,
        "update_date"                   => date("Y-m-d H:i:s")
    );
    $this->global_models->update("blast_email_po", array("id_mrp_receiving_goods_po" => $get_task_order_asset[0]->id_mrp_receiving_goods_po),$kirim3); 
    }
    
    $krm2 = array(
        "status"                            => $status,
        "update_by_users"                   => $this->session->userdata("id"),
        "update_date"                       => date("Y-m-d H:i:s")
    );
     $this->global_models->update("mrp_receiving_goods_po", array("id_mrp_receiving_goods_po" => $get_task_order_asset[0]->id_mrp_receiving_goods_po),$krm2);
           
    }
        
    
    
    $this->session->set_flashdata('success', 'Data Berhasil di Proses');
   }else{
    $this->session->set_flashdata('notice', 'Data tidak tersimpan');
   }
   
   }
//     }
     $next_no = $number + 1;
     $return = array(
      "status"      => 1,
      "id_rg"        => $id_log,
      "number"      => $next_no,   
      "progress"      => $progress,
      "id_mrp_receiving_goods_po" => $id_mrp_receiving_goods_po   
    );
      }else{
          $this->session->set_flashdata('notice', 'Data tidak tersimpan');
        $return['status'] = 2;
        
      }

        print json_encode($return);
        die;
 }

function update_rg_khusus($id_mrp_receiving_goods_po = 0){
  $pst = $_POST;
  $id_mrp_receiving_goods_po_asset = $pst['id_mrp_receiving_goods_po_asset'];
  $rg =  $pst['rg'];
 
  $tgl_diterima =  $pst['tgl_diterima'];
  $note =  $pst['note'];
  
      
   
    

    if($tgl_diterima){
       $this->olah_receiving_goods_code($kode_rg);
       $kirim = array(
        "id_mrp_receiving_goods_po"          => $id_mrp_receiving_goods_po,   
        "status"                             => 1,
        "tanggal_diterima"                   => $tgl_diterima,
        "code"                               => $kode_rg,  
        "note"                               => $note, 
        "create_by_users"                    => $this->session->userdata("id"),
        "create_date"                        => date("Y-m-d H:i:s")
      );
   $id_mrp_receiving_goods = $this->global_models->insert("mrp_receiving_goods", $kirim);
   $id_rg_department = $this->global_models->insert("mrp_receiving_goods_department", $kirim);
   
    $this->load->model('mrp/mmrp');
   $this->mmrp->rg_all($id_mrp_receiving_goods_po,$id_mrp_receiving_goods,$tgl_diterima,$rg,$id_mrp_receiving_goods_po_asset);
    
        $return['status']   = 1;
      }else{
        $this->session->set_flashdata('notice', 'Data tidak tersimpan');
        $return['status']   = 2;
      }
 
        print json_encode($return);
        die;
}

function update_rg_department($id_mrp_receiving_goods_po = 0,$id_mrp_request = 0){
    $pst = $_REQUEST;
  $id_mrp_receiving_goods_po_asset = $pst['id_mrp_receiving_goods_po_asset'];
  $rg =  $pst['rg'];
  $tgl_diterima =   $pst['tgl_diterima'];
  $note =  $pst['note'];
  $no_rg = 0;
  
//  if($id_company > 0){
      $arr_id = explode(",",$id_mrp_receiving_goods_po_asset);
      $arr_rg = explode(",",$rg);
      
      $total = count($arr_id);
     $progress = ceil(1/$total * 100);
//  $dt_status = array("5" => "Approve PO","4" => "Pengajuan PO", "3" => "Draft");

      if($tgl_diterima){
    $this->olah_receiving_goods_code($kode_rg);
       $kirim = array(
        "id_mrp_receiving_goods_po"          => $id_mrp_receiving_goods_po,   
        "status"                             => 1,
        "tanggal_diterima"                   => $tgl_diterima,
        "code"                               => $kode_rg,  
        "note"                               => $note, 
        "create_by_users"                    => $this->session->userdata("id"),
        "create_date"                        => date("Y-m-d H:i:s")
      );
   $id_rg = $this->global_models->insert("mrp_receiving_goods", $kirim);
   $kirim = array(
        "id_mrp_receiving_goods_po"          => $id_mrp_receiving_goods_po,   
        "status"                             => 1,
        "tanggal_diterima"                   => $tgl_diterima,
        "code"                               => $kode_rg,
        "id_mrp_request"                     => $id_mrp_request,
        "note"                               => $note, 
        "create_by_users"                    => $this->session->userdata("id"),
        "create_date"                        => date("Y-m-d H:i:s")
      );
   $id_rg_department = $this->global_models->insert("mrp_receiving_goods_department", $kirim);
   
   $krm = array(
        "id_mrp_receiving_goods_po_asset"    => $id_mrp_receiving_goods_po_asset,   
        "jumlah_rg"                          => $rg,
        "tgl_diterima"                       => $tgl_diterima,
        "note"                               => $note,
       "id_mrp_receiving_goods"              => $id_rg,
       "id_mrp_receiving_goods_department"   => $id_rg_department,
        "create_by_users"                    => $this->session->userdata("id"),
        "create_date"                        => date("Y-m-d H:i:s")
      );
   $id_log_rg = $this->global_models->insert("log_rg", $krm);
   
   $flag_rg  = 0;
// foreach ($arr_id[$no_rg] as $ky => $val) {
   if($arr_id[$no_rg]){
     $dt_id_inventory_spesifik = $this->global_models->get_field("mrp_receiving_goods_po_asset", "id_mrp_inventory_spesifik",array("id_mrp_receiving_goods_po_asset" => "{$arr_id[$no_rg]}"));
     $dt_rg = $this->global_models->get_field("mrp_receiving_goods_po_asset", "rg",array("id_mrp_receiving_goods_po_asset" => "{$arr_id[$no_rg]}"));
    $dt_jml = $this->global_models->get_field("mrp_receiving_goods_po_asset", "jumlah",array("id_mrp_receiving_goods_po_asset" => "{$arr_id[$no_rg]}"));
     $dt_request = $this->global_models->get("mrp_request_asset",array("id_mrp_request" => "{$id_mrp_request}","id_mrp_inventory_spesifik" => "{$dt_id_inventory_spesifik}"));
    
    //cek field rg, 
     if($dt_rg){
         $dt_rg = $dt_rg;
     }else{
         $dt_rg = 0;
     }
     $dt_kekurangan = $dt_request[0]->jumlah - $dt_request[0]->rg;
     
     $total_kekurangan = $dt_kekurangan - $arr_rg[$no_rg];
     $fix = $arr_rg[$no_rg] + $dt_rg;
     
     if($dt_jml > $fix){
         $fix_rg = $fix;
         $flag_rg = $flag_rg + 1;
     }else{
         $fix_rg = $dt_jml;
     }
     
   $get_task_order_asset[$no_rg] = $this->global_models->get("mrp_receiving_goods_po_asset",array("id_mrp_receiving_goods_po_asset" => "{$arr_id[$no_rg]}"));
   
   $jmlh_now = $dt_jml - $fix;
    if($total_kekurangan >= 0){
//   if($jmlh_now >= 0){
       
    if($arr_rg[$no_rg] > 0){
         
           $kirim = array(
        "rg"                            => $fix_rg,
        "update_by_users"               => $this->session->userdata("id"),
        "update_date"                   => date("Y-m-d H:i:s")
        );
        $this->global_models->update("mrp_receiving_goods_po_asset", array("id_mrp_receiving_goods_po_asset" => $arr_id[$no_rg]),$kirim);
        
        $dt_mrp_stock = $this->global_models->get("mrp_stock",array("id_mrp_inventory_spesifik" => "{$get_task_order_asset[$no_rg][0]->id_mrp_inventory_spesifik}"));
        
        $kirim = array(
            "id_mrp_receiving_goods"            => $id_rg,   
            "id_mrp_stock"                      => $dt_mrp_stock[0]->id_mrp_stock,
            "id_mrp_satuan"                     => $get_task_order_asset[$no_rg][0]->id_mrp_satuan,
            "jumlah"                            => $arr_rg[$no_rg],
            "id_mrp_inventory_spesifik"         => $get_task_order_asset[$no_rg][0]->id_mrp_inventory_spesifik,
            "harga"                             => $get_task_order_asset[$no_rg][0]->harga,
            "status"                            => 1,
            "create_by_users"                   => $this->session->userdata("id"),
            "create_date"                       => date("Y-m-d H:i:s")
          );
        $dt_id_stock[$no_rg] = $this->global_models->insert("mrp_receiving_goods_detail", $kirim);
    
    
    if($dt_mrp_stock[0]->id_mrp_inventory_spesifik > 0){
        
        $dtstock_in             = $dt_mrp_stock[0]->stock_in + $arr_rg[$no_rg];
        $dtstock_out            = $dt_mrp_stock[0]->stock_out;
        $dtstock_akhir          = $dtstock_in - $dtstock_out;
                
         $kirim = array(
        "stock_in"                      => $dtstock_in,
        "update_by_users"               => $this->session->userdata("id"),
        "update_date"                   => date("Y-m-d H:i:s")
    );
    $this->global_models->update("mrp_stock", array("id_mrp_inventory_spesifik" => $dt_mrp_stock[0]->id_mrp_inventory_spesifik),$kirim);
    $this->olah_stock_in_code($kode);
    $kirim = array(
        "id_mrp_stock"                          => $dt_mrp_stock[0]->id_mrp_stock,
        "kode"                                  => $kode,                            
        "id_mrp_receiving_goods_detail"         => $dt_id_stock[$no_rg],
        "id_mrp_inventory_spesifik"             => $dt_mrp_stock[0]->id_mrp_inventory_spesifik,
        "id_mrp_satuan"                         => $dt_mrp_stock[0]->id_mrp_satuan,    
        "id_mrp_supplier"                       => $get_task_order_asset[$no_rg][0]->id_mrp_supplier,
        "jumlah"                                => $arr_rg[$no_rg],  
        "tanggal_diterima"                      => $tgl_diterima, 
        "harga"                                 => $get_task_order_asset[$no_rg][0]->harga,
        "status"                                => 1,
        "create_by_users"                       => $this->session->userdata("id"),
        "create_date"                           => date("Y-m-d H:i:s")
      );
     $id_mrp_stock_in = $this->global_models->insert("mrp_stock_in", $kirim);
     
    }else{
      $kirim = array( 
        "id_mrp_inventory_spesifik"         => $get_task_order_asset[$no_rg][0]->id_mrp_inventory_spesifik,
        "id_mrp_satuan"                     => $get_task_order_asset[$no_rg][0]->id_mrp_satuan,   
        "stock_in"                          => $arr_rg[$no_rg],
        "status"                            => 1,
        "create_by_users"                   => $this->session->userdata("id"),
        "create_date"                       => date("Y-m-d H:i:s")
      );
     $id_stock[$no_rg] = $this->global_models->insert("mrp_stock", $kirim);
     
     $this->olah_stock_in_code($kode);
     $kirim = array(
        "id_mrp_stock"                          => $id_stock[$no_rg],
        "kode"                                  => $kode,  
        "id_mrp_receiving_goods_detail"         => $dt_id_stock[$no_rg],
        "id_mrp_inventory_spesifik"             => $get_task_order_asset[$no_rg][0]->id_mrp_inventory_spesifik,
        "id_mrp_satuan"                         => $get_task_order_asset[$no_rg][0]->id_mrp_satuan,    
        "id_mrp_supplier"                       => $get_task_order_asset[$no_rg][0]->id_mrp_supplier,
        "jumlah"                                => $arr_rg[$no_rg], 
        "tanggal_diterima"                      => $tgl_diterima, 
        "harga"                                 => $get_task_order_asset[$no_rg][0]->harga,
        "status"                                => 1,
        "create_by_users"                       => $this->session->userdata("id"),
        "create_date"                           => date("Y-m-d H:i:s")
      );
     $id_mrp_stock_in = $this->global_models->insert("mrp_stock_in", $kirim);
     
    }
    $this->load->model('mrp/mmrp');
    $this->mmrp->mutasi_rg($id_mrp_stock_in,$get_task_order_asset[$no_rg][0]->id_mrp_receiving_goods_po,$get_task_order_asset[$no_rg][0]->id_mrp_inventory_spesifik,$id_mrp_request,$id_rg_department,$tgl_diterima,$note);
    
    }
      
   
    $this->session->set_flashdata('success', 'Data Berhasil di Proses');
    }else{
    $this->session->set_flashdata('notice', 'Data tidak tersimpan');
   }
   
     }
     
        $dtrg = $this->global_models->get_query("SELECT A.id_mrp_po,SUM(B.rg) AS rg,SUM(B.jumlah) AS jumlah FROM mrp_receiving_goods_po AS A "
                . " LEFT JOIN mrp_receiving_goods_po_asset AS B ON A.id_mrp_receiving_goods_po = B.id_mrp_receiving_goods_po"
                . " WHERE A.id_mrp_receiving_goods_po = '{$get_task_order_asset[$no_rg][0]->id_mrp_receiving_goods_po}' "
                . " GROUP BY A.id_mrp_receiving_goods_po ");
    $hsl_rg = $dtrg[0]->jumlah - $dtrg[0]->rg;
    
    $status = 7;
    if($hsl_rg == 0){
            $status = 8;
        $kirim2 = array(
        "status"                        => 7,
        "update_by_users"               => $v->create_by_users,
        "update_date"                   => date("Y-m-d H:i:s")
    );
    $this->global_models->update("mrp_po", array("id_mrp_po" => $dtrg[0]->id_mrp_po),$kirim2);  
    }
    
    $krm2 = array(
        "status"                            => $status,
        "update_by_users"                   => $this->session->userdata("id"),
        "update_date"                       => date("Y-m-d H:i:s")
    );
     $this->global_models->update("mrp_receiving_goods_po", array("id_mrp_receiving_goods_po" => $get_task_order_asset[$no_rg][0]->id_mrp_receiving_goods_po),$krm2);
      
//     print $flag_rg."a<br>";
//     print $flag_rg2."B<br>";
//     die;
     $next_no = $no_rg + 1;
     $return = array(
      "status"          => 1,
      "id_rg"           => $id_log_rg,
      "number"          => $next_no,   
      "progress"        => $progress,
    );
     
      }else{
          $this->session->set_flashdata('notice', 'Data tidak tersimpan');
        $return['status'] = 2;
        
      }
    
     
//  }else{
//  $this->session->set_flashdata('notice', 'Data tidak tersimpan');
//  }
 
        print json_encode($return);
        die;

}

function set_rg_department($id_mrp_receiving_goods_po = 0,$id_mrp_request = 0,$id_log,$number){
   $get = $this->global_models->get("log_rg",array("id_log_rg" => "{$id_log}"));
     
    
    $no_rg = $number;
  
//  if($id_company > 0){
      $arr_id = explode(",",$get[0]->id_mrp_receiving_goods_po_asset);
      $arr_rg = explode(",",$get[0]->jumlah_rg);
     $total = count($arr_id);
     $totl = $number+1;
     $progress = ceil($totl/$total * 100);
//  $dt_status = array("5" => "Approve PO","4" => "Pengajuan PO", "3" => "Draft");

      if($get[0]->tgl_diterima){
    
   $flag_rg  = 0;
// foreach ($arr_id[$no_rg] as $ky => $val) {
   if($arr_id[$no_rg]){
     $dt_id_inventory_spesifik = $this->global_models->get_field("mrp_receiving_goods_po_asset", "id_mrp_inventory_spesifik",array("id_mrp_receiving_goods_po_asset" => "{$arr_id[$no_rg]}"));
     $dt_rg = $this->global_models->get_field("mrp_receiving_goods_po_asset", "rg",array("id_mrp_receiving_goods_po_asset" => "{$arr_id[$no_rg]}"));
    $dt_jml = $this->global_models->get_field("mrp_receiving_goods_po_asset", "jumlah",array("id_mrp_receiving_goods_po_asset" => "{$arr_id[$no_rg]}"));
     $dt_request = $this->global_models->get("mrp_request_asset",array("id_mrp_request" => "{$id_mrp_request}","id_mrp_inventory_spesifik" => "{$dt_id_inventory_spesifik}"));
    
    //cek field rg, 
     if($dt_rg){
         $dt_rg = $dt_rg;
     }else{
         $dt_rg = 0;
     }
     $dt_kekurangan = $dt_request[0]->jumlah - $dt_request[0]->rg;
     
     $total_kekurangan = $dt_kekurangan - $arr_rg[$no_rg];
     $fix = $arr_rg[$no_rg] + $dt_rg;
     
     if($dt_jml > $fix){
         $fix_rg = $fix;
         $flag_rg = $flag_rg + 1;
     }else{
         $fix_rg = $dt_jml;
     }
     
   $get_task_order_asset[$no_rg] = $this->global_models->get("mrp_receiving_goods_po_asset",array("id_mrp_receiving_goods_po_asset" => "{$arr_id[$no_rg]}"));
   
   $jmlh_now = $dt_jml - $fix;
    if($total_kekurangan >= 0){
//   if($jmlh_now >= 0){
       
    if($arr_rg[$no_rg] > 0){
         
           $kirim = array(
        "rg"                            => $fix_rg,
        "update_by_users"               => $this->session->userdata("id"),
        "update_date"                   => date("Y-m-d H:i:s")
        );
        $this->global_models->update("mrp_receiving_goods_po_asset", array("id_mrp_receiving_goods_po_asset" => $arr_id[$no_rg]),$kirim);
        
        $dt_mrp_stock = $this->global_models->get("mrp_stock",array("id_mrp_inventory_spesifik" => "{$get_task_order_asset[$no_rg][0]->id_mrp_inventory_spesifik}"));
        
        $kirim = array(
            "id_mrp_receiving_goods"            => $get[0]->id_mrp_receiving_goods,   
            "id_mrp_stock"                      => $dt_mrp_stock[0]->id_mrp_stock,
            "id_mrp_satuan"                     => $get_task_order_asset[$no_rg][0]->id_mrp_satuan,
            "jumlah"                            => $arr_rg[$no_rg],
            "id_mrp_inventory_spesifik"         => $get_task_order_asset[$no_rg][0]->id_mrp_inventory_spesifik,
            "harga"                             => $get_task_order_asset[$no_rg][0]->harga,
            "status"                            => 1,
            "create_by_users"                   => $this->session->userdata("id"),
            "create_date"                       => date("Y-m-d H:i:s")
          );
        $dt_id_stock[$no_rg] = $this->global_models->insert("mrp_receiving_goods_detail", $kirim);
    
    
    if($dt_mrp_stock[0]->id_mrp_inventory_spesifik > 0){
        
        $dtstock_in             = $dt_mrp_stock[0]->stock_in + $arr_rg[$no_rg];
        $dtstock_out            = $dt_mrp_stock[0]->stock_out;
        $dtstock_akhir          = $dtstock_in - $dtstock_out;
                
         $kirim = array(
        "stock_in"                      => $dtstock_in,
        "update_by_users"               => $this->session->userdata("id"),
        "update_date"                   => date("Y-m-d H:i:s")
    );
    $this->global_models->update("mrp_stock", array("id_mrp_inventory_spesifik" => $dt_mrp_stock[0]->id_mrp_inventory_spesifik),$kirim);
    $this->olah_stock_in_code($kode);
    $kirim = array(
        "id_mrp_stock"                          => $dt_mrp_stock[0]->id_mrp_stock,
        "kode"                                  => $kode,                            
        "id_mrp_receiving_goods_detail"         => $dt_id_stock[$no_rg],
        "id_mrp_inventory_spesifik"             => $dt_mrp_stock[0]->id_mrp_inventory_spesifik,
        "id_mrp_satuan"                         => $dt_mrp_stock[0]->id_mrp_satuan,    
        "id_mrp_supplier"                       => $get_task_order_asset[$no_rg][0]->id_mrp_supplier,
        "jumlah"                                => $arr_rg[$no_rg],  
        "tanggal_diterima"                      => $get[0]->tgl_diterima, 
        "harga"                                 => $get_task_order_asset[$no_rg][0]->harga,
        "status"                                => 1,
        "create_by_users"                       => $this->session->userdata("id"),
        "create_date"                           => date("Y-m-d H:i:s")
      );
     $id_mrp_stock_in = $this->global_models->insert("mrp_stock_in", $kirim);
     
    }else{
      $kirim = array( 
        "id_mrp_inventory_spesifik"         => $get_task_order_asset[$no_rg][0]->id_mrp_inventory_spesifik,
        "id_mrp_satuan"                     => $get_task_order_asset[$no_rg][0]->id_mrp_satuan,   
        "stock_in"                          => $arr_rg[$no_rg],
        "status"                            => 1,
        "create_by_users"                   => $this->session->userdata("id"),
        "create_date"                       => date("Y-m-d H:i:s")
      );
     $id_stock[$no_rg] = $this->global_models->insert("mrp_stock", $kirim);
     
     $this->olah_stock_in_code($kode);
     $kirim = array(
        "id_mrp_stock"                          => $id_stock[$no_rg],
        "kode"                                  => $kode,  
        "id_mrp_receiving_goods_detail"         => $dt_id_stock[$no_rg],
        "id_mrp_inventory_spesifik"             => $get_task_order_asset[$no_rg][0]->id_mrp_inventory_spesifik,
        "id_mrp_satuan"                         => $get_task_order_asset[$no_rg][0]->id_mrp_satuan,    
        "id_mrp_supplier"                       => $get_task_order_asset[$no_rg][0]->id_mrp_supplier,
        "jumlah"                                => $arr_rg[$no_rg], 
        "tanggal_diterima"                      => $get[0]->tgl_diterima,
        "harga"                                 => $get_task_order_asset[$no_rg][0]->harga,
        "status"                                => 1,
        "create_by_users"                       => $this->session->userdata("id"),
        "create_date"                           => date("Y-m-d H:i:s")
      );
     $id_mrp_stock_in = $this->global_models->insert("mrp_stock_in", $kirim);
     
    }
    $this->load->model('mrp/mmrp');
    $this->mmrp->mutasi_rg($id_mrp_stock_in,$get_task_order_asset[$no_rg][0]->id_mrp_receiving_goods_po,$get_task_order_asset[$no_rg][0]->id_mrp_inventory_spesifik,$id_mrp_request,$get[0]->id_mrp_receiving_goods_department,$get[0]->tgl_diterima,$get[0]->note);
    
    }
      
   
    $this->session->set_flashdata('success', 'Data Berhasil di Proses');
    }else{
    $this->session->set_flashdata('notice', 'Data tidak tersimpan');
   }
   
     }
     
        $dtrg = $this->global_models->get_query("SELECT A.id_mrp_po,SUM(B.rg) AS rg,SUM(B.jumlah) AS jumlah FROM mrp_receiving_goods_po AS A "
                . " LEFT JOIN mrp_receiving_goods_po_asset AS B ON A.id_mrp_receiving_goods_po = B.id_mrp_receiving_goods_po"
                . " WHERE A.id_mrp_receiving_goods_po = '{$get_task_order_asset[$no_rg][0]->id_mrp_receiving_goods_po}' "
                . " GROUP BY A.id_mrp_receiving_goods_po ");
    $hsl_rg = $dtrg[0]->jumlah - $dtrg[0]->rg;
    
    $status = 7;
    if($hsl_rg == 0){
            $status = 8;
        $kirim2 = array(
        "status"                        => 7,
        "update_by_users"               => $v->create_by_users,
        "update_date"                   => date("Y-m-d H:i:s")
    );
    $this->global_models->update("mrp_po", array("id_mrp_po" => $dtrg[0]->id_mrp_po),$kirim2);
    
    $kirim3 = array(
        "reminder"                      => 2,
        "update_by_users"               => $v->create_by_users,
        "update_date"                   => date("Y-m-d H:i:s")
    );
    $this->global_models->update("blast_email_po", array("id_mrp_receiving_goods_po" => $get_task_order_asset[0]->id_mrp_receiving_goods_po),$kirim3);
    
    }
    
    $krm2 = array(
        "status"                            => $status,
        "update_by_users"                   => $this->session->userdata("id"),
        "update_date"                       => date("Y-m-d H:i:s")
    );
     $this->global_models->update("mrp_receiving_goods_po", array("id_mrp_receiving_goods_po" => $get_task_order_asset[$no_rg][0]->id_mrp_receiving_goods_po),$krm2);
      
//     print $flag_rg."a<br>";
//     print $flag_rg2."B<br>";
//     die;
      $next_no = $number + 1;
     $return = array(
      "status"      => 1,
      "id_rg"        => $id_log,
      "number"      => $next_no,   
      "progress"      => $progress,
      "id_mrp_receiving_goods_po" => $id_mrp_receiving_goods_po,
      "id_mrp_request"          => $id_mrp_request   
    );
     
      }else{
          $this->session->set_flashdata('notice', 'Data tidak tersimpan');
        $return['status'] = 2;
        
      }
    
     
//  }else{
//  $this->session->set_flashdata('notice', 'Data tidak tersimpan');
//  }
 
        print json_encode($return);
        die;

}
  
  function get_mrp_list_rg($start = 0){
      
     $where = " WHERE A.status >= 6 AND A.status <= 8";
    $data = $this->global_models->get_query("SELECT B.id_mrp_supplier,B.tanggal_po,B.no_po,A.id_mrp_receiving_goods_po,A.status,A.create_date,A.code AS code_rg"
        . ",B.code AS code_po,B.id_mrp_po,C.id_mrp_task_orders,(SELECT D.tanggal_diterima FROM mrp_receiving_goods AS D "
        . " LEFT JOIN mrp_receiving_goods_po AS E ON D.id_mrp_receiving_goods_po = E.id_mrp_receiving_goods_po "
            . " WHERE E.status >= 6 AND E.status <= 8 AND (D.status != 9 AND E.id_mrp_receiving_goods_po=A.id_mrp_receiving_goods_po) "
            . " GROUP BY E.id_mrp_receiving_goods_po"
            . " ) AS tgl_terima "
        . " FROM mrp_receiving_goods_po AS A"
        . " LEFT JOIN mrp_po AS B ON A.id_mrp_po = B.id_mrp_po"
        . " LEFT JOIN mrp_po_asset AS C ON B.id_mrp_po = C.id_mrp_po"    
        . " {$where}"
        . " GROUP BY C.id_mrp_po"
        . " ORDER BY A.id_mrp_po DESC"
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
//    $jenis = array(1 => "Habis Pakai", 2 => "Asset");
    $status = array( 3=> "<span class='label bg-orange'>Draft PO</span>", 4 => "<span class='label bg-green'>Proses Pengajuan PO</span>",
      5 => "<span class='label bg-green'>Approved PO</span>", 
        6 =>"<span class='label bg-green'>Create</span>",
        7 =>"<span class='label bg-green'>Partial</span>",
        8 =>"<span class='label bg-green'>RG</span>",);
    
//    $status = array(1 => "Create", 2 => "Approve");
    foreach ($data AS $da){
        $supplier = $this->global_models->get_field("mrp_supplier","name",array("id_mrp_supplier" =>"{$da->id_mrp_supplier}"));
        
        if($da->tanggal_po == "0000-00-00" AND $da->tanggal_po == ""){
            $tgl_po = "";
        }else{
           $tgl_po = date("d M Y", strtotime($da->tanggal_po));
        }
        
        if($da->tgl_terima == "0000-00-00" AND $da->tgl_terima == ""){
            $tgl_terima = "";
        }else{
           $tgl_terima = date("d M Y", strtotime($da->tgl_terima));
        }
        
         if($da->status >= 6){
            $btn_rg = "<a href='".site_url("mrp/rg/{$da->id_mrp_receiving_goods_po}")."' type='button' class='btn btn-info btn-flat' title='Receiving Goods' style='width: 40px'><i class='fa fa-plus'></i></a>";
        }else{
            $btn_rg = "";
        }
        
//        if($da->tanggal_dikirim != "0000-00-00" AND $da->tanggal_dikirim != ""){
//            $tgl = date("d M Y", strtotime($da->tanggal_dikirim));
//        }else{
//            $tgl = "";
//        }

		if($tgl_terima =="01 Jan 1970"){
            $tgl_terima = "";
        }else{
            $tgl_terima = $tgl_terima;
        }
        
        $hasil[] = array(
        $tgl_terima,
        "<a data-toggle='collapse' data-parent='#accordion'  href='#collapseOne' onclick='coba_data({$da->id_mrp_receiving_goods_po});'>".$da->code_rg."</a>"
         . "<script>"
            . "function coba_data(id){"
                . 'var table = '
                . '$("#tableboxy1").dataTable({'
                  . '"order": [[ 1, "asc" ]],'
                . '"bDestroy": true'
                . '});'
                . "table.fnClearTable();"
                . 'ambil_data5(table, 0,id);'
            . "}"
        
        . 'function ambil_data5(table, mulai,id){'
        . '$.post("'.site_url("mrp/mrp-ajax/get-rg-history").'/"+id+"/"+mulai, function(data){'
          . '$("#loader-page").show();'
          . 'var hasil = $.parseJSON(data);'
          . 'if(hasil.status == 2){'
            . 'table.fnAddData(hasil.hasil);'
            . 'ambil_data5(table, hasil.start,id);'
          . '}'
          . 'else{'
            . '$("#loader-page").hide();'
          . '}'
        . '});'
      . '}'
            
            . "</script>",    
        "<a href='".site_url("mrp/mrp-po/detail-po/{$da->id_mrp_task_orders}/{$da->id_mrp_po}")."'  title='Purchase Order' style='width: 40px'>{$da->code_po}<br>{$da->no_po}</a>",
        $supplier."<br>".$tgl_po,
        $status[$da->status],
        "<div class='btn-group'>"
          . $btn_rg
//          . "<a href='".site_url("mrp/detail-rg/{$da->id_mrp_receiving_goods_po}")."' type='button' class='btn btn-info btn-flat' title='List Receiving Goods' style='width: 40px'><i class='fa fa-list-alt'></i></a>"     
        . "</div>"
      );
    }
    $return['hasil'] = $hasil;
//    $this->debug($return, true);
    print json_encode($return);
    die;
  }
  
  function get_mrp_list_rg_dept($start = 0){
      
      $dta_id = $this->global_models->get_field("hr_pegawai", "id_hr_pegawai", array("id_users" => $this->session->userdata("id")));

    $where = " WHERE A.status >= 6 AND A.status <= 8 AND B.status =6";
    $data = $this->global_models->get_query("SELECT A.id_mrp_receiving_goods_po,A.status,A.create_date,A.code AS code_rg"
        . ",B.code AS code_po,B.no_po,B.id_mrp_po,C.id_mrp_task_orders"
        . " FROM mrp_receiving_goods_po AS A"
        . " LEFT JOIN mrp_po AS B ON A.id_mrp_po = B.id_mrp_po"
        . " LEFT JOIN mrp_po_asset AS C ON B.id_mrp_po = C.id_mrp_po"
        . " LEFT JOIN mrp_task_orders_request_asset AS D ON C.id_mrp_task_orders_request_asset = D.id_mrp_task_orders_request_asset"
        . " LEFT JOIN mrp_task_orders_request AS E ON D.id_mrp_task_orders = E.id_mrp_task_orders"
        . " LEFT JOIN mrp_request AS F ON E.id_mrp_request = F.id_mrp_request"    
        . " {$where} AND (F.id_hr_pegawai ='{$dta_id}' OR F.create_by_users='{$this->session->userdata("id")}' OR F.user_pegawai_receiver='{$dta_id}')"
        . " GROUP BY C.id_mrp_po"
        . " ORDER BY A.id_mrp_po ASC"
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
//    $jenis = array(1 => "Habis Pakai", 2 => "Asset");
    $status = array( 3=> "<span class='label bg-orange'>Draft PO</span>", 4 => "<span class='label bg-green'>Proses Pengajuan PO</span>",
      5 => "<span class='label bg-green'>Approved PO</span>", 
        6 =>"<span class='label bg-green'>Create</span>",
        7 =>"<span class='label bg-green'>Partial</span>",
        8 =>"<span class='label bg-green'>RG</span>",);
    
//    $status = array(1 => "Create", 2 => "Approve");
    foreach ($data AS $da){
        
         if($da->status >= 6){
            $btn_rg = "<a href='".site_url("mrp/list-rg-department/{$da->id_mrp_po}/{$da->id_mrp_task_orders}")."' type='button' class='btn btn-info btn-flat' title='Receiving Goods' style='width: 40px'><i class='fa fa-plus'></i></a>";
        }else{
            $btn_rg = "";
        }
        
//        if($da->tanggal_dikirim != "0000-00-00" AND $da->tanggal_dikirim != ""){
//            $tgl = date("d M Y", strtotime($da->tanggal_dikirim));
//        }else{
//            $tgl = "";
//        }
        $po = $da->code_po."<br>".$da->no_po;
        $hasil[] = array(
        date("d M Y H:i:s", strtotime($da->create_date)),
        "<a data-toggle='collapse' data-parent='#accordion'  href='#collapseOne' onclick='coba_data({$da->id_mrp_receiving_goods_po});'>".$da->code_rg."</a>"
         . "<script>"
            . "function coba_data(id){"
                . 'var table = '
                . '$("#tableboxy1").dataTable({'
                  . '"order": [[ 1, "asc" ]],'
                . '"bDestroy": true'
                . '});'
                . "table.fnClearTable();"
                . 'ambil_data5(table, 0,id);'
            . "}"
        
        . 'function ambil_data5(table, mulai,id){'
        . '$.post("'.site_url("mrp/mrp-ajax/get-rg-history").'/"+id+"/"+mulai, function(data){'
          . '$("#loader-page").show();'
          . 'var hasil = $.parseJSON(data);'
          . 'if(hasil.status == 2){'
            . 'table.fnAddData(hasil.hasil);'
            . 'ambil_data5(table, hasil.start,id);'
          . '}'
          . 'else{'
            . '$("#loader-page").hide();'
          . '}'
        . '});'
      . '}'
            
            . "</script>",    
        "<a href='".site_url("mrp/mrp-po/detail-po/{$da->id_mrp_task_orders}/{$da->id_mrp_po}")."'  title='Purchase Order' style='width: 40px'>{$po}</a>",
        $status[$da->status],
        "<div class='btn-group'>"
          . $btn_rg
//          . "<a href='".site_url("mrp/detail-rg/{$da->id_mrp_receiving_goods_po}")."' type='button' class='btn btn-info btn-flat' title='List Receiving Goods' style='width: 40px'><i class='fa fa-list-alt'></i></a>"     
        . "</div>"
      );
    }
    $return['hasil'] = $hasil;
//    $this->debug($return, true);
    print json_encode($return);
    die;
  }
  
    function get_mrp_list_rg_khusus($start = 0){
      
      $where = " WHERE A.status >= 6 AND A.status <= 8";
    $data = $this->global_models->get_query("SELECT A.id_mrp_receiving_goods_po,A.status,A.create_date,A.code AS code_rg"
        . ",B.code AS code_po,B.id_mrp_po,C.id_mrp_task_orders"
        . " FROM mrp_receiving_goods_po AS A"
        . " LEFT JOIN mrp_po AS B ON A.id_mrp_po = B.id_mrp_po"
        . " LEFT JOIN mrp_po_asset AS C ON B.id_mrp_po = C.id_mrp_po"    
        . " {$where}"
        . " GROUP BY C.id_mrp_po"
        . " ORDER BY A.id_mrp_po DESC"
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
//    $jenis = array(1 => "Habis Pakai", 2 => "Asset");
    $status = array( 3=> "<span class='label bg-orange'>Draft PO</span>", 4 => "<span class='label bg-green'>Proses Pengajuan PO</span>",
      5 => "<span class='label bg-green'>Approved PO</span>", 
        6 =>"<span class='label bg-green'>Create</span>",
        7 =>"<span class='label bg-green'>Partial</span>",
        8 =>"<span class='label bg-green'>RG</span>",);
    
//    $status = array(1 => "Create", 2 => "Approve");
    foreach ($data AS $da){
        
         if($da->status >= 6){
            $btn_rg = "<a href='".site_url("mrp/rg-khusus/{$da->id_mrp_receiving_goods_po}")."' type='button' class='btn btn-info btn-flat' title='Receiving Goods' style='width: 40px'><i class='fa fa-plus'></i></a>";
        }else{
            $btn_rg = "";
        }
        
//        if($da->tanggal_dikirim != "0000-00-00" AND $da->tanggal_dikirim != ""){
//            $tgl = date("d M Y", strtotime($da->tanggal_dikirim));
//        }else{
//            $tgl = "";
//        }
        
        $hasil[] = array(
        date("d M Y H:i:s", strtotime($da->create_date)),
        "<a data-toggle='collapse' data-parent='#accordion'  href='#collapseOne' onclick='coba_data({$da->id_mrp_receiving_goods_po});'>".$da->code_rg."</a>"
         . "<script>"
            . "function coba_data(id){"
                . 'var table = '
                . '$("#tableboxy1").dataTable({'
                  . '"order": [[ 1, "asc" ]],'
                . '"bDestroy": true'
                . '});'
                . "table.fnClearTable();"
                . 'ambil_data5(table, 0,id);'
            . "}"
        
        . 'function ambil_data5(table, mulai,id){'
        . '$.post("'.site_url("mrp/mrp-ajax/get-rg-history").'/"+id+"/"+mulai, function(data){'
          . '$("#loader-page").show();'
          . 'var hasil = $.parseJSON(data);'
          . 'if(hasil.status == 2){'
            . 'table.fnAddData(hasil.hasil);'
            . 'ambil_data5(table, hasil.start,id);'
          . '}'
          . 'else{'
            . '$("#loader-page").hide();'
          . '}'
        . '});'
      . '}'
            
            . "</script>",    
        "<a href='".site_url("mrp/mrp-po/detail-po/{$da->id_mrp_task_orders}/{$da->id_mrp_po}")."'  title='Purchase Order' style='width: 40px'>{$da->code_po}</a>",
        $status[$da->status],
        "<div class='btn-group'>"
          . $btn_rg
//          . "<a href='".site_url("mrp/detail-rg/{$da->id_mrp_receiving_goods_po}")."' type='button' class='btn btn-info btn-flat' title='List Receiving Goods' style='width: 40px'><i class='fa fa-list-alt'></i></a>"     
        . "</div>"
      );
    }
    $return['hasil'] = $hasil;
//    $this->debug($return, true);
    print json_encode($return);
    die;
  }
  
    function get_mrp_list_rg_department($id_mrp_po = 0,$id_mrp_task_orders = 0,$start = 0){
//      $id_hr_pegawai =$this->global_models->get_field("hr_pegawai","id_hr_pegawai",array("id_users" =>$this->session->userdata("id")));
       
      $dta_id = $this->global_models->get_field("hr_pegawai", "id_hr_pegawai", array("id_users" => $this->session->userdata("id")));
//      $acd = $this->global_models->get("hr_master_organisasi", array("parent" => "{$dta_id}"));
//
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
//        $qry = ""; 
//        if($status == 1){
//            $qry .= "A.create_by_users ='{$id_users}'";
//        }elseif($status == 2){\
        
//        if($this->session->userdata("id") == 1){
//            $qry = "";
//        }else{
//            if($aa){
//                $qry .= " AND G.id_hr_master_organisasi IN ($aa) H.title IS NOT NULL ";
//            }else{
//                $qry .= " AND H.title IS NOT NULL ";
//            }
//        }
      $no = 0;
      $aa = "";
      $po = $this->global_models->get("mrp_po_asset",array('id_mrp_po' =>"{$id_mrp_po}"));
      foreach ($po as $p) {
          if($no > 0){
            $aa .= ",".$p->id_mrp_inventory_spesifik;
          }else{
            $aa .= $p->id_mrp_inventory_spesifik;
          }
        $no++;
      }
      if($aa){
          $inventory_spec = "AND B.id_mrp_inventory_spesifik IN({$aa})";
      }
      $dt_where = "WHERE A.id_mrp_task_orders='{$id_mrp_task_orders}' $inventory_spec";
      $data_query = $this->global_models->get_query("SELECT A.id_mrp_request"
//        . ",B.code AS code_po,B.id_mrp_po,C.id_mrp_task_orders"
        . " FROM mrp_task_orders_request AS A"
        . " LEFT JOIN mrp_request_asset AS B ON A.id_mrp_request = B.id_mrp_request"
//        . " LEFT JOIN mrp_po_asset AS C ON B.id_mrp_po = C.id_mrp_po"    
        . " {$dt_where}"
//        . " GROUP BY C.id_mrp_po"
//        . " ORDER BY A.id_mrp_po ASC"
        );
        $no2 = 0;
        $bb = "";
        foreach ($data_query as $dq) {
            if($no2 > 0){
            $bb .= ",".$dq->id_mrp_request;
          }else{
            $bb .= $dq->id_mrp_request;
          }
        $no2++;
        }
        
        $where = " WHERE (A.status >=6) AND H.title IS NOT NULL AND K.id_mrp_request IN({$bb}) AND (F.id_hr_pegawai ='{$dta_id}' OR F.create_by_users={$this->session->userdata("id")} OR F.user_pegawai_receiver ='{$dta_id}')";
      
//    $data = $this->global_models->get_query("SELECT A.id_mrp_receiving_goods_po,A.status,A.create_date,A.code AS code_rg"
//        . ",B.code AS code_po,B.id_mrp_po,C.id_mrp_task_orders"
//        . " FROM mrp_receiving_goods_po AS A"
//        . " LEFT JOIN mrp_po AS B ON A.id_mrp_po = B.id_mrp_po"
//        . " LEFT JOIN mrp_po_asset AS C ON B.id_mrp_po = C.id_mrp_po"    
//        . " {$where}"
//        . " GROUP BY C.id_mrp_po"
//        . " ORDER BY A.id_mrp_po ASC"
//        . " LIMIT {$start}, 10");
     
        
        $data = $this->global_models->get_query(
//        "SELECT *"
       "SELECT F.code,F.id_mrp_request,F.type_inventory,A.id_mrp_receiving_goods_po,A.status,A.create_date,A.code AS code_rg"
        . ",B.no_po AS code_po,C.id_mrp_po,C.id_mrp_task_orders,F.status AS status_request"
        . ",F.id_hr_pegawai,F.create_by_users,I.name AS users,H.title AS hr_organisasi"
        . " FROM mrp_receiving_goods_po AS A"
        . " LEFT JOIN mrp_po AS B ON A.id_mrp_po = B.id_mrp_po"
        . " LEFT JOIN mrp_po_asset AS C ON B.id_mrp_po = C.id_mrp_po"
//        . " LEFT JOIN mrp_task_orders_request_asset AS D ON C.id_mrp_task_orders_request_asset = D.id_mrp_task_orders_request_asset"
//        . " LEFT JOIN mrp_task_orders_request AS E ON D.id_mrp_task_orders = E.id_mrp_task_orders"
        . " LEFT JOIN mrp_request_asset AS K ON C.id_mrp_inventory_spesifik = K.id_mrp_inventory_spesifik"
         . " LEFT JOIN mrp_request AS F ON K.id_mrp_request = F.id_mrp_request"        
        . " LEFT JOIN hr_pegawai AS G ON F.id_hr_pegawai = G.id_hr_pegawai"
        . " LEFT JOIN hr_master_organisasi AS H ON G.id_hr_master_organisasi = H.id_hr_master_organisasi"
        . " LEFT JOIN m_users AS I ON G.id_users = I.id_users"
        . " LEFT JOIN m_users AS J ON F.create_by_users = J.id_users"
//        . " LEFT JOIN hr_pegawai AS K ON J.id_users = K.id_users"
//        . " LEFT JOIN hr_master_organisasi AS L ON (K.id_hr_master_organisasi = L.id_hr_master_organisasi AND K.id_hr_master_organisasi='{$dta_id}')"    
//        . " {$where} AND A.id_mrp_po='{$id_mrp_po}' AND (F.create_by_users='{$this->session->userdata("id")}' OR G.id_hr_master_organisasi ='{$dta_id}')"
        . " {$where} AND A.id_mrp_po='{$id_mrp_po}'"
        . " GROUP BY F.id_mrp_request"
        . " ORDER BY A.id_mrp_po ASC"
        . " LIMIT {$start}, 5");
     
//        print $this->db->last_query();
//        die;
//    $data_array = json_decode($data);
//    $this->debug($data, true);
    if(count($data) > 0){
      $return['status'] = 2;
      $return['start']  = $start + 5;
    }
    else{
      $return['status'] = 3;
    }
//    $jenis = array(1 => "Habis Pakai", 2 => "Asset");
    $status = array( 3=> "<span class='label bg-orange'>Draft PO</span>", 4 => "<span class='label bg-green'>Proses Pengajuan PO</span>",
      5 => "<span class='label bg-green'>Partial</span>", 6 =>"<span class='label bg-green'>Create</span>",
      7 =>"<span class='label bg-green'>Partial</span>",8 =>"<span class='label bg-green'>RG</span>",9 => "<span class='label bg-green'>Closed RG</span>");
    
    
//    $status = array(1 => "Create", 2 => "Approve");
    foreach ($data AS $da){
        
         if($da->status >= 6){
            $btn_rg = "<a href='".site_url("mrp/rg-department/{$da->id_mrp_receiving_goods_po}/{$da->id_mrp_request}")."' type='button' class='btn btn-info btn-flat' title='Receiving Goods' style='width: 40px'><i class='fa fa-plus'></i></a>";
        }else{
            $btn_rg = "";
        }
        
        if($da->status > 8){
            $fix_status = $da->status;
        }else{
            $fix_status = $da->status_request;
        }
        
//        if($da->tanggal_dikirim != "0000-00-00" AND $da->tanggal_dikirim != ""){
//            $tgl = date("d M Y", strtotime($da->tanggal_dikirim));
//        }else{
//            $tgl = "";
//        }
        if($da->type_inventory == 1){
            $link = site_url("mrp/add-request-pengadaan-cetakan/{$da->id_mrp_request}");
            $clik = 'window.open(this.href,"_blank"); return false;';
            $url_link = "<a href='{$link}' onclick='{$clik}' >{$da->code}</a>";
        }elseif($da->type_inventory == 2){
            $link = site_url("mrp/add-request-pengadaan-atk/{$da->id_mrp_request}");
            $clik = 'window.open(this.href,"_blank"); return false;';
            $url_link = "<a href='{$link}' onclick='{$clik}' >{$da->code}</a>";
        }else{
            $url_link = $da->code;
        }
        
        $hasil[] = array(
        date("d M Y H:i:s", strtotime($da->create_date)),
        "<a data-toggle='collapse' data-parent='#accordion'  href='#collapseOne' onclick='coba_data({$da->id_mrp_receiving_goods_po},{$da->id_mrp_request},{$da->id_mrp_po});'>".$da->code_rg."</a>"
         . "<script>"
            . "function coba_data(id,id_mrp_request,id_mrp_po){"
                . 'var table = '
                . '$("#tableboxy1").dataTable({'
                  . '"order": [[ 1, "asc" ]],'
                . '"bDestroy": true'
                . '});'
                . "table.fnClearTable();"
                . 'ambil_data5(table, 0,id,id_mrp_request,id_mrp_po);'
            . "}"
        
        . 'function ambil_data5(table, mulai,id,id_mrp_request,id_mrp_po){'
        
        . '$.post("'.site_url("mrp/mrp-ajax/get-rg-department-history").'/"+id+"/"+id_mrp_request+"/"+id_mrp_po+"/"+mulai, function(data){'
          . '$("#loader-page1").show();'
          . 'var hasil = $.parseJSON(data);'
          . 'if(hasil.status == 2){'
                . "table.fnClearTable();"
            . 'table.fnAddData(hasil.hasil);'
                
            . 'ambil_data5(table, hasil.start,id,id_mrp_request,id_mrp_po);'
          . '}'
          . 'else{'
            . '$("#loader-page1").hide();'
          . '}'
        . '});'
      . '}'
            
            . "</script>",    
        $da->code_po,
        $url_link,      
        $da->users." [".$da->hr_organisasi."]",
        $status[$fix_status],
        "<div class='btn-group'>"
          . $btn_rg
//          . "<a href='".site_url("mrp/detail-rg-department/{$da->id_mrp_receiving_goods_po}")."' type='button' class='btn btn-info btn-flat' title='List Receiving Goods' style='width: 40px'><i class='fa fa-list-alt'></i></a>"     
        . "</div>"
      );
    }
    $return['hasil'] = $hasil;
//    $this->debug($return, true);
    print json_encode($return);
    die;
  }
  
  function cancel_rg(){
      $pst = $_REQUEST;
      print_r($pst);
      die;
    $kode = $pst['kode'];
    $note = $pst['note'];
 
    
  }
  
    function get_mrp_detail_rg($id_mrp_receiving_goods_po = 0,$start = 0){
      
//      $type = 1 => rg_keseluruhan
//      $type = 2 => rg_department
//      $where = " WHERE A.status =6";
        $where = " WHERE A.id_mrp_receiving_goods_po ='{$id_mrp_receiving_goods_po}'";
        $data = $this->global_models->get_query("SELECT A.code,"
        . " B.code AS code_rg,B.note,B.tanggal_diterima,B.id_mrp_receiving_goods,B.create_date,B.status,"
        . " C.name"
        . " FROM mrp_receiving_goods_po AS A"
        . " LEFT JOIN mrp_receiving_goods AS B ON A.id_mrp_receiving_goods_po = B.id_mrp_receiving_goods_po"
        . " LEFT JOIN m_users AS C ON B.create_by_users = C.id_users"
        . " {$where}"
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
        
        if($da->tanggal_diterima){
            $tgl = date("d M Y", strtotime($da->tanggal_diterima));
        }else{
            $tgl = "";
        }
        
        if($tgl == "01 Jan 1970"){
            $tgl = "";
        }
        
        if($da->create_date){
            $tanggal_dibuat = date("d M Y H:i:s", strtotime($da->create_date));
        }else{
            $tanggal_dibuat = "";
        }
        
        if($da->status < 9){
             $btn_cancel = "<a href='javascript:void(0)' data-toggle='modal' type='button' class='btn btn-rg-hide btn-danger btn-sm' title='Delete' data-target='#edit-keterangan-cancel' isi='{$da->code_rg}' id='id-rg-cancel' ><i class='fa fa-trash-o'></i></a>"; 
            
        }elseif($da->status == 9){
            $btn_cancel = "<span class='label bg-red'>Cancel</span>";
        }
        
        $hasil[] = array(
        "<a data-toggle='collapse' data-parent='#accordion'  href='#collapseOne' onclick='coba_data({$da->id_mrp_receiving_goods});'>".$da->code_rg."</a>",
        $tanggal_dibuat,
        $tgl,
        nl2br($da->note)
            . "<script>"
            . "function coba_data(id){"
                . 'var table = '
                . '$("#tableboxy3").dataTable({'
                  . '"order": [[ 1, "asc" ]],'
                . '"bDestroy": true'
                . '});'
                . "table.fnClearTable();"
                . 'ambil_data3(table, 0,id);'
            . "}"
        
        . 'function ambil_data3(table, mulai,id){'
        . '$.post("'.site_url("mrp/mrp-ajax/list-history-detail-rg").'/"+id+"/"+mulai, function(data){'
          . '$("#loader-page3").show();'
          . 'var hasil = $.parseJSON(data);'
          . 'if(hasil.status == 2){'
            . 'table.fnAddData(hasil.hasil);'
            . 'ambil_data3(table, hasil.start,id);'
          . '}'
          . 'else{'
            . '$("#loader-page3").hide();'
          . '}'
        . '});'
      . '}'
            
            . "</script>",
      $da->name,
       $btn_cancel         
      );
    }
    $return['hasil'] = $hasil;
//    $this->debug($return, true);
    print json_encode($return);
    die;
  }
  
    function get_history_mutasi($id_mrp_request = 0,$start = 0){
      
        $where = " WHERE A.id_mrp_request ='{$id_mrp_request}'";
        $data = $this->global_models->get_query("SELECT A.id_history_request_mutasi,A.id_mrp_request,A.kode,A.tanggal_diserahkan,A.note,"
        . "C.name AS user_request,"
        . "D.name AS create_by_users"
        . " FROM history_request_mutasi AS A"
        . " LEFT JOIN hr_pegawai AS B ON A.user_request = B.id_hr_pegawai"
        . " LEFT JOIN m_users AS C ON B.id_users = C.id_users"
        . " LEFT JOIN m_users AS D ON A.create_by_users = D.id_users"
        . " {$where}"
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
        
        
        if($da->tanggal_diserahkan){
            $tanggal_diserahkan = date("d M Y", strtotime($da->tanggal_diserahkan));
        }else{
            $tanggal_diserahkan = "";
        }
        
        
        $hasil[] = array(
        "<a data-toggle='collapse' data-parent='#accordion'  href='#collapseOne' onclick='coba_data({$da->id_history_request_mutasi});'>".$da->kode."</a>",
        $tanggal_diserahkan,
        $da->user_request,
        nl2br($da->note)
            . "<script>"
            . "function coba_data(id){"
                . 'var table = '
                . '$("#detail-history").dataTable({'
                  . '"order": [[ 1, "asc" ]],'
                . '"bDestroy": true'
                . '});'
                . "table.fnClearTable();"
                . 'history_mutasi_detail(table, 0,id);'
            . "}"
        
        . 'function history_mutasi_detail(table, mulai,id){'
        . '$.post("'.site_url("mrp/mrp-ajax/get-history-mutasi-detail").'/"+id+"/"+mulai, function(data){'
          . '$("#loader-page3").show();'
          . 'var hasil = $.parseJSON(data);'
          . 'if(hasil.status == 2){'
            . 'table.fnAddData(hasil.hasil);'
            . 'history_mutasi_detail(table, hasil.start,id);'
          . '}'
          . 'else{'
            . '$("#loader-page3").hide();'
          . '}'
        . '});'
      . '}'
            
            . "</script>",
      $da->create_by_users          
      );
    }
    $return['hasil'] = $hasil;
//    $this->debug($return, true);
    print json_encode($return);
    die;
  }
  
 function get_history_mutasi_detail($id_history_request_mutasi = 0,$start = 0){
//      $id_mrp_supplier = 1;
//       $url = base_url()."themes/".DEFAULTTHEMES."/img/ajax-loader.gif";
    $where = "WHERE A.id_history_request_mutasi = '$id_history_request_mutasi'";
    $data = $this->global_models->get_query("SELECT A.jumlah AS jumlah_diterima,A.note,C.name AS nama_barang,E.title AS satuan"
        . ",B.title AS title_spesifik, G.name,D.title AS brand"
        . " FROM history_request_mutasi_detail AS A"
        . " LEFT JOIN mrp_inventory_spesifik AS B ON A.id_mrp_inventory_spesifik = B.id_mrp_inventory_spesifik"
        . " LEFT JOIN mrp_inventory_umum AS C ON B.id_mrp_inventory_umum = C.id_mrp_inventory_umum"
        . " LEFT JOIN mrp_brand AS D ON B.id_mrp_brand = D.id_mrp_brand"
        . " LEFT JOIN mrp_satuan AS E ON A.id_mrp_satuan = E.id_mrp_satuan"
        . " LEFT JOIN m_users AS G ON A.create_by_users = G.id_users"
        . " {$where}"
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
        if($da->title_spesifik){
            $title_spesifik = " ".$da->title_spesifik;
        }else{
            $title_spesifik ="";
        }
        
        $brn = "";
    if($da->brand){
        $brn = "<br>Brand:".$da->brand;
    }
        $hasil[] = array(
        $da->nama_barang.$title_spesifik.$brn,    
        $da->satuan,
        $da->jumlah_diterima,
        $da->name    
        
      );  
    }
    
    $return['hasil'] = $hasil;
//    $this->debug($return, true);
    print json_encode($return);
    die;
  }
  
  function get_mrp_detail_rg_khusus($id_mrp_receiving_goods_po = 0,$start = 0){
      
//      $type = 1 => rg_keseluruhan
//      $type = 2 => rg_department
//      $where = " WHERE A.status =6";
        $where = " WHERE A.id_mrp_receiving_goods_po ='{$id_mrp_receiving_goods_po}'";
        $data = $this->global_models->get_query("SELECT A.code,"
        . " B.code AS code_rg,B.note,B.tanggal_diterima,B.id_mrp_receiving_goods,B.create_date,"
        . " C.name"
        . " FROM mrp_receiving_goods_po AS A"
        . " LEFT JOIN mrp_receiving_goods AS B ON A.id_mrp_receiving_goods_po = B.id_mrp_receiving_goods_po"
        . " LEFT JOIN m_users AS C ON B.create_by_users = C.id_users"
        . " {$where}"
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
        
        if($da->tanggal_diterima){
            $tgl = date("d M Y", strtotime($da->tanggal_diterima));
        }else{
            $tgl = "";
        }
        
        if($tgl == "01 Jan 1970"){
            $tgl = "";
        }
        
        if($da->create_date){
            $tanggal_dibuat = date("d M Y H:i:s", strtotime($da->create_date));
        }else{
            $tanggal_dibuat = "";
        }
        
        
        $tanggal_dibuat =
        
        $hasil[] = array(
        "<a data-toggle='collapse' data-parent='#accordion'  href='#collapseOne' onclick='coba_data({$da->id_mrp_receiving_goods});'>".$da->code_rg."</a>",
        $tanggal_dibuat,
        $tgl,
        nl2br($da->note)
            . "<script>"
            . "function coba_data(id){"
                . 'var table = '
                . '$("#tableboxy3").dataTable({'
                  . '"order": [[ 1, "asc" ]],'
                . '"bDestroy": true'
                . '});'
                . "table.fnClearTable();"
                . 'ambil_data3(table, 0,id);'
            . "}"
        
        . 'function ambil_data3(table, mulai,id){'
        . '$.post("'.site_url("mrp/mrp-ajax/list-history-detail-rg").'/"+id+"/"+mulai, function(data){'
          . '$("#loader-page3").show();'
          . 'var hasil = $.parseJSON(data);'
          . 'if(hasil.status == 2){'
            . 'table.fnAddData(hasil.hasil);'
            . 'ambil_data3(table, hasil.start,id);'
          . '}'
          . 'else{'
            . '$("#loader-page3").hide();'
          . '}'
        . '});'
      . '}'
            
            . "</script>",
      $da->name          
      );
    }
    $return['hasil'] = $hasil;
//    $this->debug($return, true);
    print json_encode($return);
    die;
  }
  
  function get_mrp_detail_rg_department($id_mrp_receiving_goods_po = 0,$id_mrp_request = 0,$start = 0){
      
      $lst = $this->global_models->get_query("SELECT A.id_hr_master_organisasi"
        . " FROM hr_pegawai AS A"
        . " LEFT JOIN m_users AS B ON A.id_users = B.id_users"
        . " LEFT JOIN hr_master_organisasi AS C ON A.id_hr_master_organisasi = C.id_hr_master_organisasi"
        . " WHERE A.id_users ='{$this->session->userdata("id")}'"
        );
//      $type = 1 => rg_keseluruhan
//      $type = 2 => rg_department
//      $where = " WHERE A.status =6";
        $where = " WHERE A.id_mrp_receiving_goods_po ='{$id_mrp_receiving_goods_po}' AND B.id_mrp_request='{$id_mrp_request}'";
        $data = $this->global_models->get_query("SELECT A.code,"
        . " B.code AS code_rg,B.note,B.tanggal_diterima,B.id_mrp_receiving_goods_department,B.create_date,"
        . " C.name"
        . " FROM mrp_receiving_goods_po AS A"
        . " LEFT JOIN mrp_receiving_goods_department AS B ON A.id_mrp_receiving_goods_po = B.id_mrp_receiving_goods_po"
        . " LEFT JOIN m_users AS C ON B.create_by_users = C.id_users"
        . " LEFT JOIN mrp_receiving_goods_detail_department AS D ON B.id_mrp_receiving_goods_department = D.id_mrp_receiving_goods_department"     
        . " {$where}" 
        . " GROUP BY B.id_mrp_receiving_goods_department"
        . " LIMIT {$start}, 10");
        
//        print $this->db->last_query();
//        die();
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
        
        if($da->tanggal_diterima){
            $tgl = date("d M Y", strtotime($da->tanggal_diterima));
        }else{
            $tgl = "";
        }
        
        if($tgl == "01 Jan 1970"){
            $tgl = "";
        }
        
        if($da->create_date){
            $tanggal_dibuat = date("d M Y H:i:s", strtotime($da->create_date));
        }else{
            $tanggal_dibuat = "";
        }
        
        
        $tanggal_dibuat =
        
        $hasil[] = array(
        "<a data-toggle='collapse' data-parent='#accordion'  href='#collapseOne' onclick='coba_data({$da->id_mrp_receiving_goods_department});'>".$da->code_rg."</a>",
        $tanggal_dibuat,
        $tgl,
        nl2br($da->note)
            . "<script>"
            . "function coba_data(id){"
                . 'var table = '
                . '$("#tableboxy3").dataTable({'
                  . '"order": [[ 1, "asc" ]],'
                . '"bDestroy": true'
                . '});'
                . "table.fnClearTable();"
                . 'ambil_data3(table, 0,id);'
            . "}"
        
        . 'function ambil_data3(table, mulai,id){'
        . '$.post("'.site_url("mrp/mrp-ajax/list-history-detail-rg-department/{$lst[0]->id_hr_master_organisasi}").'/"+id+"/"+mulai, function(data){'
          . '$("#loader-page3").show();'
          . 'var hasil = $.parseJSON(data);'
          . 'if(hasil.status == 2){'
            . 'table.fnAddData(hasil.hasil);'
            . 'ambil_data3(table, hasil.start,id);'
          . '}'
          . 'else{'
            . '$("#loader-page3").hide();'
          . '}'
        . '});'
      . '}'
        . "</script>",
      $da->name          
      );
    }
    $return['hasil'] = $hasil;
//    $this->debug($return, true);
    print json_encode($return);
    die;
  }
  
  function get_rg_department($id_mrp_receiving_goods_po = 0,$id_mrp_request  = 0,$start = 0){

      
//      if($this->session->userdata("id") == 1){
//          $id_usr = 9;
//      }else{
//          $id_usr = $this->session->userdata("id");
//      }
//      $this->global_models->get("hr_pegawai",array());
//      $where = "WHERE A.status >= 3 AND A.id_mrp_receiving_goods_po = '{$id_mrp_receiving_goods_po}' AND I.id_mrp_request='{$id_mrp_request}'  ";
       
     $where = "WHERE A.status >= 3 AND A.id_mrp_receiving_goods_po = '$id_mrp_receiving_goods_po'  ";

    $data = $this->global_models->get_query("SELECT A.id_mrp_receiving_goods_po_asset,A.rg,A.jumlah,A.note,"
        . "B.id_mrp_inventory_spesifik,B.title AS title_spesifik,C.name AS nama_barang,E.title AS satuan"
        . " FROM mrp_receiving_goods_po_asset AS A"
        . " LEFT JOIN mrp_inventory_spesifik AS B ON A.id_mrp_inventory_spesifik = B.id_mrp_inventory_spesifik"
        . " LEFT JOIN mrp_inventory_umum AS C ON B.id_mrp_inventory_umum = C.id_mrp_inventory_umum"
        . " LEFT JOIN mrp_brand AS D ON B.id_mrp_brand = D.id_mrp_brand"
        . " LEFT JOIN mrp_satuan AS E ON A.id_mrp_satuan = E.id_mrp_satuan"
        . " {$where}"
//        . " ORDER BY A.id_mrp_task_orders ASC"
//        . " LIMIT {$start}, 10"
        );
//        print $this->db->last_query();
//        die;
//        
//    $where = "WHERE A.status >= 3 AND A.id_mrp_receiving_goods_po = '{$id_mrp_receiving_goods_po}' AND I.id_mrp_request IS NOT NULL  ";
    
     $id_hr_pegawai = $this->global_models->get_field("hr_pegawai","id_hr_pegawai",array("id_users" => $this->session->userdata("id")));   
    
     $where2 = "WHERE E.status >= 3 AND (F.id_hr_pegawai ='{$id_hr_pegawai}' OR F.create_by_users = '{$this->session->userdata("id")}' OR F.user_pegawai_receiver='{$id_hr_pegawai}')";
     
    $data2 = $this->global_models->get_query("SELECT H.name AS name_request,B.name AS nama_barang,C.title AS brand,D.title AS satuan,"
        . "A.id_mrp_inventory_spesifik,A.title AS title_spesifik,E.jumlah,E.rg,F.id_mrp_request,F.status AS status_request"
        . " FROM mrp_inventory_spesifik AS A"
        . " LEFT JOIN mrp_inventory_umum AS B ON A.id_mrp_inventory_umum = B.id_mrp_inventory_umum"
        . " LEFT JOIN mrp_brand AS C ON A.id_mrp_brand = C.id_mrp_brand"
        . " LEFT JOIN mrp_satuan AS D ON A.id_mrp_satuan = D.id_mrp_satuan"
        . " LEFT JOIN mrp_request_asset AS E ON (A.id_mrp_inventory_spesifik = E.id_mrp_inventory_spesifik AND E.id_mrp_request = '{$id_mrp_request}')"
        . " LEFT JOIN mrp_request AS F ON E.id_mrp_request = F.id_mrp_request"
        . " LEFT JOIN hr_pegawai AS G ON F.id_hr_pegawai = G.id_hr_pegawai"
        . " LEFT JOIN m_users AS H ON G.id_users = H.id_users"
        . " {$where2} "
        . " ORDER BY A.id_mrp_inventory_spesifik ASC"
        . " LIMIT {$start}, 7");
        
//        print $this->db->last_query();
//        die;
        
    if(count($data2) > 0){
      $return['status'] = 2;
      $return['start']  = $start + 7;
    }
    else{
      $return['status'] = 1;
    }

    foreach ($data as $val) {
            foreach ($data2 AS $da){
                
             if($val->id_mrp_inventory_spesifik == $da->id_mrp_inventory_spesifik){
                if($da->rg){
                    $rg2    = $da->jumlah - $da->rg;
                    $dt_rg  = $da->rg;
                }else{
                    $rg2    = $da->jumlah;
                    $dt_rg  = 0;
                }

                if($da->title_spesifik){
                    $title_spesifik = " ".$da->title_spesifik;
                }else{
                    $title_spesifik = "";
                }
                $total = $da->jumlah * $da->harga_task_order_request;
                $rg = "id='rg_{$val->id_mrp_receiving_goods_po_asset}'";
                    $dtnote = $this->form_eksternal->form_input("rg[]", $rg2, $rg.'class="form-control rg input-sm" placeholder="Receiving Goods" style="display: none"');
                  if($da->jumlah != $da->rg){
                   $dtnote = $this->form_eksternal->form_input("rg[]", $rg2, $rg.'class="form-control rg input-sm" placeholder="Receiving Goods"').

                "<script>"
                    . '$(function() {'
                       . "$('#rg_{$val->id_mrp_receiving_goods_po_asset}').keyup(function(){"
    //                   . "alert('aa');"
                        . "var jml = $('#rg_{$val->id_mrp_receiving_goods_po_asset}').val()*1;" 
                        . "jml = jml ? jml : 0;"
                        . "var receive = {$dt_rg};"
                        . "var dt_jml = jml + receive; "
    //                            . "alert(dt_jml);" 
                        . "var hasil = {$da->jumlah} - dt_jml;"
                        . "if({$da->jumlah} > dt_jml){"
                            . "$('#jml_{$val->id_mrp_receiving_goods_po_asset}').text(jml);"
                        . "}else{"
                             . "$('#jml_{$val->id_mrp_receiving_goods_po_asset}').text({$da->jumlah});"       
                        . "}"
                        . "if(hasil > 0){"
                            . "$('#jml_min_{$val->id_mrp_receiving_goods_po_asset}').text(hasil);"
                        . "}else{"
                            . "$('#jml_min_{$val->id_mrp_receiving_goods_po_asset}').text(0);"
                        . "}"

                       . "});"

                   . "});"  
                    . "</script>";      

                    } 
                    
                    $brn = "";
                    if($da->brand){
                        $brn = "<br>Brand:".$da->brand;
                    }
        
                    $hasil[] = array(
                    $da->nama_barang.$title_spesifik.$brn,
                    $da->name_request,    
                    $da->satuan,
                    $da->jumlah,
                    $da->note.
                    $this->form_eksternal->form_input("id_mrp_task_orders_request_asset[]", $val->id_mrp_receiving_goods_po_asset, ' class="form-control id_mrp_receiving_goods_po_asset input-sm" style="display: none"'),
                    "<span style='font-weight:Bold;' id='jml_{$val->id_mrp_receiving_goods_po_asset}'>{$dt_rg}</span>"
                    ,
                    "<span style='font-weight:Bold;' id='jml_min_{$val->id_mrp_receiving_goods_po_asset}'>{$rg2}</span>",
                     $dtnote       
                    );
                }
           
        }
    }
    
    
    $return['hasil'] = $hasil;
//    $this->debug($return, true);
    print json_encode($return);
    die;
  }
  
    function get_rg_khusus($id_mrp_receiving_goods_po = 0,$start = 0){
//      $id_mrp_supplier = 1;
//       $url = base_url()."themes/".DEFAULTTHEMES."/img/ajax-loader.gif";
      $where = "WHERE A.status >= 3 AND A.id_mrp_receiving_goods_po = '$id_mrp_receiving_goods_po'  ";

    $data = $this->global_models->get_query("SELECT A.id_mrp_receiving_goods_po_asset,A.rg,A.jumlah,A.note,"
        . "B.title AS title_spesifik,C.name AS nama_barang,E.title AS satuan,D.title AS brand"
        . " FROM mrp_receiving_goods_po_asset AS A"
        . " LEFT JOIN mrp_inventory_spesifik AS B ON A.id_mrp_inventory_spesifik = B.id_mrp_inventory_spesifik"
        . " LEFT JOIN mrp_inventory_umum AS C ON B.id_mrp_inventory_umum = C.id_mrp_inventory_umum"
        . " LEFT JOIN mrp_brand AS D ON B.id_mrp_brand = D.id_mrp_brand"
        . " LEFT JOIN mrp_satuan AS E ON A.id_mrp_satuan = E.id_mrp_satuan"
        . " {$where}"
//        . " ORDER BY A.id_mrp_task_orders ASC"
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

    if($id_mrp_supplier > 0){
        $dt_mrp_supplier = "";
        
    }else{
        $dt_mrp_supplier = " disabled "; 
    }
    foreach ($data AS $da){
        if($da->rg){
            $rg2 = $da->jumlah - $da->rg;
            $dt_rg = $da->rg;
        }else{
            $rg2 = $da->jumlah;
            $dt_rg = 0;
        }
        
        if($da->title_spesifik){
            $title_spesifik = " ".$da->title_spesifik;
        }else{
            $title_spesifik = "";
        }
         $total = $da->jumlah * $da->harga_task_order_request;
        $rg = "id='rg_{$da->id_mrp_receiving_goods_po_asset}'";
            $dtnote = $this->form_eksternal->form_input("rg[]", $rg2, $rg.'class="form-control rg input-sm" placeholder="Receiving Goods" style="display: none"');
          if($da->jumlah != $da->rg){
           $dtnote = $this->form_eksternal->form_input("rg[]", $rg2, $rg.'class="form-control rg input-sm" placeholder="Receiving Goods"').
        
         "<script>"
                . '$(function() {'
                   . "$('#rg_{$da->id_mrp_receiving_goods_po_asset}').keyup(function(){"
//                   . "alert('aa');"
                    . "var jml = $('#rg_{$da->id_mrp_receiving_goods_po_asset}').val()*1;" 
                    . "jml = jml ? jml : 0;"
                    . "var receive = {$dt_rg};"
                    . "var dt_jml = jml + receive; "
                    . "var jml_rcv = jml + receive;"
//                            . "alert(dt_jml);" 
                    . "var hasil = {$da->jumlah} - dt_jml;"
                    . "if({$da->jumlah} > dt_jml){"
                        . "$('#jml_{$da->id_mrp_receiving_goods_po_asset}').text(jml_rcv);"
                    . "}else{"
                        . "$('#jml_{$da->id_mrp_receiving_goods_po_asset}').text({$da->jumlah});"
                        . "$('#rg_{$da->id_mrp_receiving_goods_po_asset}').val({$rg2});"       
                    . "}"
                    . "if(hasil > 0){"
                        . "$('#jml_min_{$da->id_mrp_receiving_goods_po_asset}').text(hasil);"
                    . "}else{"
                        . "$('#jml_min_{$da->id_mrp_receiving_goods_po_asset}').text(0);"
                    . "}"
                    
//                    . "alert(hasil);"
//                    . "if({$da->jumlah})"
//                   . "penjumlahan_{$da->id_mrp_task_orders_request_asset}(jml,hrg,dt_nilai);"
//                   ."var dataString2 = 'jumlah='+ jml;"
//                        ."$.ajax({"
//                        ."type : 'POST',"
//                        ."url : '".site_url("mrp/mrp-ajax/update-po-task-orders-request/{$da->id_mrp_task_orders_request_asset}")."',"
//                        ."data: dataString2,"
//                        ."dataType : 'html',"
//                        ."success: function(data) {"
//                                
//                        ."},"
//                     ."});"    
                   . "});"
                                 
               . "});"       

                
            . "</script>";      
    
        } 
        $brn = "";
        if($da->brand){
            $brn = "<br>Brand:".$da->brand;
        }
        $hasil[] = array(
        $da->nama_barang.$title_spesifik.$brn,    
        $da->satuan,
        $da->jumlah,
        $da->note.
        $this->form_eksternal->form_input("id_mrp_task_orders_request_asset[]", $da->id_mrp_receiving_goods_po_asset, ' class="form-control id_mrp_receiving_goods_po_asset input-sm" style="display: none"'),
        "<span style='font-weight:Bold;' id='jml_{$da->id_mrp_receiving_goods_po_asset}'>{$dt_rg}</span>"
        ,
        "<span style='font-weight:Bold;' id='jml_min_{$da->id_mrp_receiving_goods_po_asset}'>{$rg2}</span>",
         $dtnote       
        );
    }
    
    $return['hasil'] = $hasil;
//    $this->debug($return, true);
    print json_encode($return);
    die;
  }
  
  function get_rg_detail_rg_department($id_mrp_receiving_goods_po_asset= 0,$id_mrp_inventory_spesifik = 0,$start = 0){
      
    $where = "WHERE  A.id_mrp_receiving_goods_po_asset = '$id_mrp_receiving_goods_po_asset' AND D.id_mrp_inventory_spesifik = '$id_mrp_inventory_spesifik'";

    $data = $this->global_models->get_query("SELECT D.jumlah,D.rg,(D.jumlah-D.rg)AS kekurangan,"
        . "(SELECT CONCAT(G.name,'|',H.name) FROM mrp_request AS E "
        . " LEFT JOIN hr_pegawai AS F ON E.id_hr_pegawai = F.id_hr_pegawai"
        . " LEFT JOIN m_users AS G ON F.id_users = G.id_users"
        . " LEFT JOIN m_users AS H ON E.create_by_users = H.id_users"
        . " WHERE E.id_mrp_request = D.id_mrp_request) AS name,"
        . " (SELECT CONCAT(G.name,'|',F.title,'|',H.title) FROM mrp_inventory_spesifik AS F "
        . " LEFT JOIN mrp_inventory_umum AS G ON F.id_mrp_inventory_umum = G.id_mrp_inventory_umum"
        . " LEFT JOIN mrp_satuan AS H ON F.id_mrp_Satuan = H.id_mrp_satuan"
        . " WHERE F.id_mrp_inventory_spesifik = D.id_mrp_inventory_spesifik) AS barang"
        . " FROM mrp_receiving_goods_po_asset AS A"
        . " LEFT JOIN mrp_receiving_goods_po AS B ON A.id_mrp_receiving_goods_po = B.id_mrp_receiving_goods_po"
        . " LEFT JOIN mrp_task_orders_request AS C ON B.id_mrp_task_orders = C.id_mrp_task_orders"
        . " LEFT JOIN mrp_request_asset AS D ON C.id_mrp_request = D.id_mrp_request"
        . " {$where}"
        . " GROUP BY A.id_mrp_receiving_goods_po_asset,D.id_mrp_request"
        . " LIMIT {$start}, 10"
        );
        
        if(count($data) > 0){
        $return['status'] = 2;
        $return['start']  = $start + 10;
        }
        else{
        $return['status'] = 1;
        }
        
        foreach ($data AS $da){
            $name = explode("|",$da->name);
            $barang = explode("|",$da->barang);
        $hasil[] = array(
            $name[0],
            $barang[0]." ".$barang[1],
            $barang[2],       
            $da->jumlah,
            $da->rg,
            $da->kekurangan,
            $name[1]
        );
        }
        $return['hasil'] = $hasil;
//    $this->debug($return, true);
    print json_encode($return);
//       print $this->db->last_query();
       die;
  }
  
  function get_rg($id_mrp_receiving_goods_po = 0,$start = 0){
//      $id_mrp_supplier = 1;
//       $url = base_url()."themes/".DEFAULTTHEMES."/img/ajax-loader.gif";
      $where = "WHERE A.status >= 3 AND A.id_mrp_receiving_goods_po = '$id_mrp_receiving_goods_po'  ";

    $data = $this->global_models->get_query("SELECT A.id_mrp_receiving_goods_po_asset,A.id_mrp_inventory_spesifik,A.rg,A.jumlah,A.note,"
        . "B.title AS title_spesifik,C.name AS nama_barang,E.title AS satuan,D.title AS brand"
        . " FROM mrp_receiving_goods_po_asset AS A"
        . " LEFT JOIN mrp_inventory_spesifik AS B ON A.id_mrp_inventory_spesifik = B.id_mrp_inventory_spesifik"
        . " LEFT JOIN mrp_inventory_umum AS C ON B.id_mrp_inventory_umum = C.id_mrp_inventory_umum"
        . " LEFT JOIN mrp_brand AS D ON B.id_mrp_brand = D.id_mrp_brand"
        . " LEFT JOIN mrp_satuan AS E ON A.id_mrp_satuan = E.id_mrp_satuan"
        . " {$where}"
//        . " ORDER BY A.id_mrp_task_orders ASC"
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

    if($id_mrp_supplier > 0){
        $dt_mrp_supplier = "";
        
    }else{
        $dt_mrp_supplier = " disabled "; 
    }
    foreach ($data AS $da){
        if($da->rg){
            $rg2 = $da->jumlah - $da->rg;
            $dt_rg = $da->rg;
        }else{
            $rg2 = $da->jumlah;
            $dt_rg = 0;
        }
        
        if($da->title_spesifik){
            $title_spesifik = " ".$da->title_spesifik;
        }else{
            $title_spesifik = "";
        }
         $total = $da->jumlah * $da->harga_task_order_request;
        $rg = "id='rg_{$da->id_mrp_receiving_goods_po_asset}'";
            $dtnote = $this->form_eksternal->form_input("rg[]", $rg2, $rg.'class="form-control rg input-sm" placeholder="Receiving Goods" style="display: none"');
          if($da->jumlah != $da->rg){
           $dtnote = $this->form_eksternal->form_input("rg[]", $rg2, $rg.'class="form-control rg input-sm" placeholder="Receiving Goods"').
        
         "<script>"
                . '$(function() {'
                   . "$('#rg_{$da->id_mrp_receiving_goods_po_asset}').keyup(function(){"
//                   . "alert('aa');"
                    . "var jml = $('#rg_{$da->id_mrp_receiving_goods_po_asset}').val()*1;" 
                    . "jml = jml ? jml : 0;"
                    . "var receive = {$dt_rg};"
                    . "var dt_jml = jml + receive; "
                    . "var jml_rcv = jml + receive;"
//                            . "alert(dt_jml);" 
                    . "var hasil = {$da->jumlah} - dt_jml;"
                    . "if({$da->jumlah} > dt_jml){"
                        . "$('#jml_{$da->id_mrp_receiving_goods_po_asset}').text(jml_rcv);"
                    . "}else{"
                        . "$('#jml_{$da->id_mrp_receiving_goods_po_asset}').text({$da->jumlah});"
                        . "$('#rg_{$da->id_mrp_receiving_goods_po_asset}').val({$rg2});"       
                    . "}"
                    . "if(hasil > 0){"
                        . "$('#jml_min_{$da->id_mrp_receiving_goods_po_asset}').text(hasil);"
                    . "}else{"
                        . "$('#jml_min_{$da->id_mrp_receiving_goods_po_asset}').text(0);"
                    . "}"
                    
//                    . "alert(hasil);"
//                    . "if({$da->jumlah})"
//                   . "penjumlahan_{$da->id_mrp_task_orders_request_asset}(jml,hrg,dt_nilai);"
//                   ."var dataString2 = 'jumlah='+ jml;"
//                        ."$.ajax({"
//                        ."type : 'POST',"
//                        ."url : '".site_url("mrp/mrp-ajax/update-po-task-orders-request/{$da->id_mrp_task_orders_request_asset}")."',"
//                        ."data: dataString2,"
//                        ."dataType : 'html',"
//                        ."success: function(data) {"
//                                
//                        ."},"
//                     ."});"    
                   . "});"
                                 
               . "});"       

                
            . "</script>";      
    
        } 
        $brn = "";
        if($da->brand){
            $brn = "<br>Brand:".$da->brand;
        }
        $hasil[] = array(
          "<a data-toggle='collapse' data-parent='#accordion'  href='#collapseOne' onclick='coba_data({$da->id_mrp_receiving_goods_po_asset},{$da->id_mrp_inventory_spesifik});'>".$da->nama_barang.$title_spesifik.$brn."</a>",   
        $da->satuan,
        $da->jumlah,
        $da->note.
        $this->form_eksternal->form_input("id_mrp_task_orders_request_asset[]", $da->id_mrp_receiving_goods_po_asset, ' class="form-control id_mrp_receiving_goods_po_asset input-sm" style="display: none"'),
        "<span style='font-weight:Bold;' id='jml_{$da->id_mrp_receiving_goods_po_asset}'>{$dt_rg}</span>"
        ,
        "<span style='font-weight:Bold;' id='jml_min_{$da->id_mrp_receiving_goods_po_asset}'>{$rg2}</span>",
         $dtnote       
        );
    }
    
    $return['hasil'] = $hasil;
//    $this->debug($return, true);
    print json_encode($return);
    die;
  }
  
  function get_rg_history($id_mrp_receiving_goods_po = 0,$start = 0){
//      $id_mrp_supplier = 1;
//       $url = base_url()."themes/".DEFAULTTHEMES."/img/ajax-loader.gif";
      $where = "WHERE A.status >= 3 AND A.id_mrp_receiving_goods_po = '$id_mrp_receiving_goods_po'  ";

    $data = $this->global_models->get_query("SELECT A.id_mrp_receiving_goods_po_asset,A.rg,A.jumlah,A.note,"
        . "B.title AS title_spesifik,C.name AS nama_barang,E.title AS satuan,D.title AS brand"
        . " FROM mrp_receiving_goods_po_asset AS A"
        . " LEFT JOIN mrp_inventory_spesifik AS B ON A.id_mrp_inventory_spesifik = B.id_mrp_inventory_spesifik"
        . " LEFT JOIN mrp_inventory_umum AS C ON B.id_mrp_inventory_umum = C.id_mrp_inventory_umum"
        . " LEFT JOIN mrp_brand AS D ON B.id_mrp_brand = D.id_mrp_brand"
        . " LEFT JOIN mrp_satuan AS E ON A.id_mrp_satuan = E.id_mrp_satuan"
        . " {$where}"
//        . " ORDER BY A.id_mrp_task_orders ASC"
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
        if($da->rg){
            $rg2 = $da->jumlah - $da->rg;
            $dt_rg = $da->rg;
        }else{
            $rg2 = $da->jumlah;
            $dt_rg = 0;
        }
        
        if($da->title_spesifik){
            $title_spesifik = " ".$da->title_spesifik;
        }else{
            $title_spesifik = "";
        }
       
        $brn = "";
        if($da->brand){
            $brn = "<br>Brand:".$da->brand;
        }
        
        $total = $da->jumlah * $da->harga_task_order_request;
        $rg = "id='rg_{$da->id_mrp_receiving_goods_po_asset}'";
        $hasil[] = array(
        $da->nama_barang.$title_spesifik.$brn,    
        $da->satuan,
        $da->jumlah,
        $da->note.
        $this->form_eksternal->form_input("id_mrp_task_orders_request_asset[]", $da->id_mrp_receiving_goods_po_asset, ' class="form-control id_mrp_receiving_goods_po_asset input-sm" style="display: none"'),
        "<span style='font-weight:Bold;' id='jml_{$da->id_mrp_receiving_goods_po_asset}'>{$dt_rg}</span>"
        ,
        "<span style='font-weight:Bold;' id='jml_min_{$da->id_mrp_receiving_goods_po_asset}'>{$rg2}</span>"
        );
    }
    
    $return['hasil'] = $hasil;
//    $this->debug($return, true);
    print json_encode($return);
    die;
  }
  
    function get_rg_department_history($id_mrp_receiving_goods_po = 0,$id_mrp_request = 0,$id_mrp_po = 0,$start = 0){
        
//        $id_hr_pegawai =$this->global_models->get_field("hr_pegawai","id_hr_pegawai",array("id_users" =>$this->session->userdata("id")));
//          if($this->session->userdata("id") == 1){
//          $id_usr = 9;
//      }else{
//          $id_usr = $this->session->userdata("id");
//      }
//      $this->global_models->get("hr_pegawai",array());
        $inventory_po = $this->global_models->get("mrp_po_asset",array("id_mrp_po"  => "{$id_mrp_po}"));
        $no=1;
        $ips = "4";
        foreach ($inventory_po as $ip) {
            if($no > 0){
                $ips .= ",".$ip->id_mrp_inventory_spesifik;
            }else{
                $ips .= $ip->id_mrp_inventory_spesifik;
            }
        }
        $que ="";
        if($ips){
            $que = " AND B.id_mrp_inventory_spesifik IN({$ips})";
        }
      $where = "WHERE A.status >= 3 AND A.id_mrp_receiving_goods_po = '{$id_mrp_receiving_goods_po}' AND I.id_mrp_request IS NOT NULL $que ";

//    $data = $this->global_models->get_query("SELECT L.id_users,I.id_mrp_request,A.id_mrp_receiving_goods_po_asset,A.rg,A.jumlah AS jml_po,"
//        . "A.note,B.title AS title_spesifik,C.name AS nama_barang,E.title AS satuan, J.jumlah"
//        . " FROM mrp_receiving_goods_po_asset AS A"
//        . " LEFT JOIN mrp_inventory_spesifik AS B ON A.id_mrp_inventory_spesifik = B.id_mrp_inventory_spesifik"
//        . " LEFT JOIN mrp_inventory_umum AS C ON B.id_mrp_inventory_umum = C.id_mrp_inventory_umum"
//        . " LEFT JOIN mrp_brand AS D ON B.id_mrp_brand = D.id_mrp_brand"
//        . " LEFT JOIN mrp_satuan AS E ON A.id_mrp_satuan = E.id_mrp_satuan"
//        . " LEFT JOIN mrp_receiving_goods_po AS F ON A.id_mrp_receiving_goods_po = F.id_mrp_receiving_goods_po"
//        . " LEFT JOIN mrp_task_orders AS G ON F.id_mrp_task_orders = G.id_mrp_task_orders"
//        . " LEFT JOIN mrp_task_orders_request AS H ON G.id_mrp_task_orders = H.id_mrp_task_orders"
//        . " LEFT JOIN mrp_request AS I ON H.id_mrp_request = I.id_mrp_request"
//        . " LEFT JOIN mrp_request_asset AS J ON I.id_mrp_request = J.id_mrp_request"
//        . " LEFT JOIN hr_pegawai AS K ON I.id_hr_pegawai = K.id_hr_pegawai"
//        . " LEFT JOIN m_users AS L ON K.id_users = L.id_users"
//        . " {$where}"
//        . " GROUP BY id_mrp_receiving_goods_po_asset "
////        . " ORDER BY A.id_mrp_task_orders ASC"
//        . " LIMIT {$start}, 10");
        
//      $id_mrp_supplier = 1;
//       $url = base_url()."themes/".DEFAULTTHEMES."/img/ajax-loader.gif";
//      $where = "WHERE A.status >= 3 AND A.id_mrp_receiving_goods_po = '$id_mrp_receiving_goods_po'  ";

    $data = $this->global_models->get_query("SELECT L.id_users,L.name AS users_request,I.id_mrp_request,A.id_mrp_receiving_goods_po_asset,A.jumlah AS jml_po,"
        . "A.note,B.title AS title_spesifik,C.name AS nama_barang,E.title AS satuan,D.title AS brand,"
        . " G.id_mrp_task_orders,J.jumlah,J.rg"
        . " FROM mrp_receiving_goods_po_asset AS A"
        . " LEFT JOIN mrp_receiving_goods_po AS F ON A.id_mrp_receiving_goods_po = F.id_mrp_receiving_goods_po"
        . " LEFT JOIN mrp_task_orders AS G ON F.id_mrp_task_orders = G.id_mrp_task_orders"
        . " LEFT JOIN mrp_task_orders_request AS H ON G.id_mrp_task_orders = H.id_mrp_task_orders"
        . " LEFT JOIN mrp_request AS I ON (H.id_mrp_request = I.id_mrp_request AND I.id_mrp_request='{$id_mrp_request}')"
        . " LEFT JOIN mrp_request_asset AS J ON H.id_mrp_request = J.id_mrp_request"
        . " LEFT JOIN hr_pegawai AS K ON I.id_hr_pegawai = K.id_hr_pegawai"
        . " LEFT JOIN m_users AS L ON K.id_users = L.id_users"
        . " LEFT JOIN mrp_inventory_spesifik AS B ON J.id_mrp_inventory_spesifik = B.id_mrp_inventory_spesifik"
        . " LEFT JOIN mrp_inventory_umum AS C ON B.id_mrp_inventory_umum = C.id_mrp_inventory_umum"
        . " LEFT JOIN mrp_brand AS D ON B.id_mrp_brand = D.id_mrp_brand"
        . " LEFT JOIN mrp_satuan AS E ON A.id_mrp_satuan = E.id_mrp_satuan"    
        . " {$where}"
        . " GROUP BY J.id_mrp_inventory_spesifik "
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
        if($da->rg){
            $rg2 = $da->jumlah - $da->rg;
            $dt_rg = $da->rg;
        }else{
            $rg2 = $da->jumlah;
            $dt_rg = 0;
        }
        
        if($da->title_spesifik){
            $title_spesifik = " ".$da->title_spesifik;
        }else{
            $title_spesifik = "";
        }
       
        $brn = "";
        if($da->brand){
            $brn = "<br>Brand:".$da->brand;
        }
        
        $total = $da->jumlah * $da->harga_task_order_request;
        $rg = "id='rg_{$da->id_mrp_receiving_goods_po_asset}'";
        $hasil[] = array(
        $da->nama_barang.$title_spesifik.$brn,
        $da->users_request,    
        $da->satuan,
        $da->jumlah,
        $da->note.
        $this->form_eksternal->form_input("id_mrp_task_orders_request_asset[]", $da->id_mrp_receiving_goods_po_asset, ' class="form-control id_mrp_receiving_goods_po_asset input-sm" style="display: none"'),
        "<span style='font-weight:Bold;' id='jml_{$da->id_mrp_receiving_goods_po_asset}'>{$dt_rg}</span>"
        ,
        "<span style='font-weight:Bold;' id='jml_min_{$da->id_mrp_receiving_goods_po_asset}'>{$rg2}</span>"
        );
    }
    
    $return['hasil'] = $hasil;
//    $this->debug($return, true);
    print json_encode($return);
    die;
  }
  
    function list_history_detail_rg($id_mrp_receiving_goods = 0,$start = 0){
//      $id_mrp_supplier = 1;
//       $url = base_url()."themes/".DEFAULTTHEMES."/img/ajax-loader.gif";
      $where = "WHERE A.id_mrp_receiving_goods = '$id_mrp_receiving_goods'";

    $data = $this->global_models->get_query("SELECT A.jumlah AS jumlah_diterima,A.note,C.name AS nama_barang,E.title AS satuan"
        . ",B.title AS title_spesifik, G.name,D.title AS brand"
        . " FROM mrp_receiving_goods_detail AS A"
        . " LEFT JOIN mrp_inventory_spesifik AS B ON A.id_mrp_inventory_spesifik = B.id_mrp_inventory_spesifik"
        . " LEFT JOIN mrp_inventory_umum AS C ON B.id_mrp_inventory_umum = C.id_mrp_inventory_umum"
        . " LEFT JOIN mrp_brand AS D ON B.id_mrp_brand = D.id_mrp_brand"
        . " LEFT JOIN mrp_satuan AS E ON A.id_mrp_satuan = E.id_mrp_satuan"
        . " LEFT JOIN mrp_receiving_goods AS F ON A.id_mrp_receiving_goods = F.id_mrp_receiving_goods"
        . " LEFT JOIN m_users AS G ON A.create_by_users = G.id_users"
        . " {$where}"
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
        if($da->title_spesifik){
            $title_spesifik = " ".$da->title_spesifik;
        }else{
            $title_spesifik ="";
        }
        
        $brn = "";
    if($da->brand){
        $brn = "<br>Brand:".$da->brand;
    }
        $hasil[] = array(
        $da->nama_barang.$title_spesifik.$brn,    
        $da->satuan,
        $da->jumlah_diterima,
        $da->name    
        
      );  
    }
    
    $return['hasil'] = $hasil;
//    $this->debug($return, true);
    print json_encode($return);
    die;
  }
  
    function list_history_detail_rg_department($id_hr_master_organisasi = 0,$id_mrp_receiving_goods_department = 0,$start = 0){
//      $id_mrp_supplier = 1;
//       $url = base_url()."themes/".DEFAULTTHEMES."/img/ajax-loader.gif";
      $where = "WHERE A.id_mrp_receiving_goods_department = '$id_mrp_receiving_goods_department'";

    $data = $this->global_models->get_query("SELECT A.jumlah AS jumlah_diterima,A.note,C.name AS nama_barang,E.title AS satuan"
            . ",B.title AS title_spesifik, G.name,D.title AS brand"
        . " FROM mrp_receiving_goods_detail_department AS A"
        . " LEFT JOIN mrp_inventory_spesifik AS B ON A.id_mrp_inventory_spesifik = B.id_mrp_inventory_spesifik"
        . " LEFT JOIN mrp_inventory_umum AS C ON B.id_mrp_inventory_umum = C.id_mrp_inventory_umum"
        . " LEFT JOIN mrp_brand AS D ON B.id_mrp_brand = D.id_mrp_brand"
        . " LEFT JOIN mrp_satuan AS E ON A.id_mrp_satuan = E.id_mrp_satuan"
        . " LEFT JOIN mrp_receiving_goods_department AS F ON A.id_mrp_receiving_goods_department = F.id_mrp_receiving_goods_department"
        . " LEFT JOIN m_users AS G ON A.create_by_users = G.id_users"
       
        . " {$where}"
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
        if($da->title_spesifik){
            $title_spesifik = " ".$da->title_spesifik;
        }else{
            $title_spesifik ="";
        }
        
        $brn = "";
        if($da->brand){
            $brn = "<br>Brand:".$da->brand;
        }
        
        $hasil[] = array(
        $da->nama_barang.$title_spesifik.$brn,    
        $da->satuan,
        $da->jumlah_diterima,
        $da->name    
        
      );  
    }
    
    $return['hasil'] = $hasil;
//    $this->debug($return, true);
    print json_encode($return);
    die;
  }

function get_users_penerima($status = 0){
    
        $where = "WHERE A.status =1 AND id_users='{$this->session->userdata("id")}'";
        $detail = $this->global_models->get_query("SELECT "
        . "B.id_hr_master_organisasi AS B1"
        . ",C.id_hr_master_organisasi AS C1"
        . ",D.id_hr_master_organisasi AS D1"
        . ",E.id_hr_master_organisasi AS E1"
        . " FROM hr_pegawai AS A"
        . " LEFT JOIN hr_master_organisasi AS B ON A.id_hr_master_organisasi = B.id_hr_master_organisasi"
        . " LEFT JOIN hr_master_organisasi AS C ON B.id_hr_master_organisasi = C.parent"
        . " LEFT JOIN hr_master_organisasi AS D ON C.id_hr_master_organisasi = D.parent"
        . " LEFT JOIN hr_master_organisasi AS E ON D.id_hr_master_organisasi = E.parent"         
        . " {$where}");
      
foreach ($detail as $val) {
    if($val->B1){
       $dt .= $val->B1." ";
    }
    if($val->C1){
        $dt .= $val->C1." ";
    }
    
    if($val->D1){
        $dt .= $val->D1." ";
    }
    
    if($val->E1){
        $dt .= $val->E1." ";
    } 
}
        $dt =rtrim($dt," ");
        $dt1 = str_replace(" ",",",$dt);
       $dt1 = explode (",",$dt1);
         $asd =array_unique($dt1);

        $no = 0;
        $k = "";
        foreach ($asd as $vl) {
            if($no > 0){
                 $k .= ",".$vl;
            }else{
                $k .= $vl;
            }
            $no++;
        }
        if($k){
            $k = $k;
        }else{
            $k = 0;
        }
        
     $where2 = "WHERE A.id_users='{$this->session->userdata("id")}'";
        $detail2 = $this->global_models->get_query("SELECT B.id_users"
        . " FROM mrp_setting_users AS A"
        . " LEFT JOIN mrp_setting_users_request AS B ON A.id_mrp_setting_users = B.id_mrp_setting_users"     
        . " {$where2}");    
        
    foreach ($detail2 as $val2) {
    if($val2->id_users){
       $dt2 .= $val2->id_users." ";
    } 
}
        $dt2 =rtrim($dt2," ");
        $dt12 = str_replace(" ",",",$dt2);
       $dt12 = explode (",",$dt12);
         $asd2 =array_unique($dt12);

        $no2 = 0;
        $k2 = "";
        foreach ($asd2 as $vl2) {
            if($no2 > 0){
                 $k2 .= ",".$vl2;
            }else{
                $k2 .= $vl2;
            }
            $no2++;
        }
        
        if($k2){
            $k2 = $k2;
        }else{
            $k2 = 0;
        }
        
       if($status == "1" OR $this->session->userdata("id") == 1){
          $qry ="";
       }else{
           $qry = " AND (B.id_hr_master_organisasi IN ($k) OR A.id_users IN ($k2))"; 
           
         
       }
//       print $qry;
////        print $qry;
////       print $this->db->last_query();
//       die;
    if (empty($_GET['term'])) exit ;
    $q = strtolower($_GET["term"]);
    if (get_magic_quotes_gpc()) $q = stripslashes($q);
    $items = $this->global_models->get_query("SELECT A.id_users,A.name,A.email,B.id_hr_pegawai,C.title AS name_organisasi"
      . " FROM m_users AS A"
      . " LEFT JOIN hr_pegawai AS B ON A.id_users = B.id_users"
      . " LEFT JOIN hr_master_organisasi AS C ON B.id_hr_master_organisasi = C.id_hr_master_organisasi"
      . " WHERE C.title IS NOT NULL AND A.status=1 AND B.status=1 {$qry} AND (LOWER(A.name) LIKE '%{$q}%' OR LOWER(C.title) LIKE '%{$q}%' OR LOWER(A.email) LIKE '%{$q}%')"
      . " LIMIT 0,10"
      );
      
//      print $this->db->last_query();
//      die;
     
    if(count($items) > 0){
      foreach($items as $tms){
        $result[] = array(
            "id"    => $tms->id_hr_pegawai,
            "label" => $tms->name." <".$tms->name_organisasi."><".$tms->email.">",
            "value" => $tms->name." <".$tms->name_organisasi."><".$tms->email.">",
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
  
function get_pegawai($status = 0){
    
        $where = "WHERE A.status=1 AND id_users='{$this->session->userdata("id")}'";
        $detail = $this->global_models->get_query("SELECT "
        . "B.id_hr_master_organisasi AS B1"
        . ",C.id_hr_master_organisasi AS C1"
        . ",D.id_hr_master_organisasi AS D1"
        . ",E.id_hr_master_organisasi AS E1"
        . " FROM hr_pegawai AS A"
        . " LEFT JOIN hr_master_organisasi AS B ON A.id_hr_master_organisasi = B.id_hr_master_organisasi"
        . " LEFT JOIN hr_master_organisasi AS C ON B.id_hr_master_organisasi = C.parent"
        . " LEFT JOIN hr_master_organisasi AS D ON C.id_hr_master_organisasi = D.parent"
        . " LEFT JOIN hr_master_organisasi AS E ON D.id_hr_master_organisasi = E.parent"         
        . " {$where}");
      
foreach ($detail as $val) {
    if($val->B1){
       $dt .= $val->B1." ";
    }
    if($val->C1){
        $dt .= $val->C1." ";
    }
    
    if($val->D1){
        $dt .= $val->D1." ";
    }
    
    if($val->E1){
        $dt .= $val->E1." ";
    } 
}
        $dt =rtrim($dt," ");
        $dt1 = str_replace(" ",",",$dt);
       $dt1 = explode (",",$dt1);
         $asd =array_unique($dt1);

        $no = 0;
        $k = "";
        foreach ($asd as $vl) {
            if($no > 0){
                 $k .= ",".$vl;
            }else{
                $k .= $vl;
            }
            $no++;
        }
        if($k){
            $k = $k;
        }else{
            $k = 0;
        }
        
     $where2 = "WHERE A.id_users='{$this->session->userdata("id")}'";
        $detail2 = $this->global_models->get_query("SELECT B.id_users"
        . " FROM mrp_setting_users AS A"
        . " LEFT JOIN mrp_setting_users_request AS B ON A.id_mrp_setting_users = B.id_mrp_setting_users"     
        . " {$where2}");    
        
    foreach ($detail2 as $val2) {
    if($val2->id_users){
       $dt2 .= $val2->id_users." ";
    } 
}
        $dt2 =rtrim($dt2," ");
        $dt12 = str_replace(" ",",",$dt2);
       $dt12 = explode (",",$dt12);
         $asd2 =array_unique($dt12);

        $no2 = 0;
        $k2 = "";
        foreach ($asd2 as $vl2) {
            if($no2 > 0){
                 $k2 .= ",".$vl2;
            }else{
                $k2 .= $vl2;
            }
            $no2++;
        }
        
        if($k2){
            $k2 = $k2;
        }else{
            $k2 = 0;
        }
        
       if($status == "1" OR $this->session->userdata("id") == 1){
          $qry ="";
       }else{
           $qry = " AND (B.id_hr_master_organisasi IN ($k) OR A.id_users IN ($k2))"; 
           
         
       }
//       print $qry;
////        print $qry;
////       print $this->db->last_query();
//       die;
    if (empty($_GET['term'])) exit ;
    $q = strtolower($_GET["term"]);
    if (get_magic_quotes_gpc()) $q = stripslashes($q);
    $items = $this->global_models->get_query("SELECT A.id_users,A.name,A.email,B.id_hr_pegawai,C.title AS name_organisasi"
      . " FROM m_users AS A"
      . " LEFT JOIN hr_pegawai AS B ON A.id_users = B.id_users"
      . " LEFT JOIN hr_master_organisasi AS C ON B.id_hr_master_organisasi = C.id_hr_master_organisasi"
      . " WHERE C.title IS NOT NULL AND A.status=1 AND B.status=1 {$qry} AND (LOWER(A.name) LIKE '%{$q}%' OR LOWER(C.title) LIKE '%{$q}%' OR LOWER(A.email) LIKE '%{$q}%')"
      . " LIMIT 0,10"
      );
      
//      print $this->db->last_query();
//      die;
     
    if(count($items) > 0){
      foreach($items as $tms){
        $result[] = array(
            "id"    => $tms->id_hr_pegawai,
            "label" => $tms->name." <".$tms->name_organisasi."><".$tms->email.">",
            "value" => $tms->name." <".$tms->name_organisasi."><".$tms->email.">",
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
  
  private function olah_request_order_code(&$kode){
    $this->load->helper('string');
    $kode_random = random_string('alnum', 6);
    $st_upper = strtoupper($kode_random);
    $kode = "RO".$st_upper;
    $cek = $this->global_models->get_field("mrp_request", "id_mrp_request", array("code" => $kode));
    if($cek > 0){
      $this->olah_request_order_code($kode);
    }
  }
  
  private function olah_receiving_goods_code(&$kode){
    $this->load->helper('string');
    $kode_random = random_string('alnum', 6);
    $st_upper = strtoupper($kode_random);
    $kode = "RG".$st_upper;
    $cek = $this->global_models->get_field("mrp_receiving_goods", "id_mrp_receiving_goods", array("code" => $kode));
    if($cek > 0){
      $this->olah_receiving_goods_code($kode);
    }
  }
  
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */