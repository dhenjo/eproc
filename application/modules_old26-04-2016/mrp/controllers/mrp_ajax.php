<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mrp_ajax extends MX_Controller {
  function __construct() {
    $this->menu = $this->cek();
  }
  
  function get_mrp_master_supplier($start = 0){
      
      $where = "WHERE 1=1";
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
      $hasil[] = array(
        $da->name,
        $da->pic,
        $da->phone,
        $da->fax,
        $da->email, 
        $da->website,  
        $da->address,  
        "<div class='btn-group'>"
          . "<a href='".site_url("mrp/mrp-master/add-supplier/{$da->id_mrp_supplier}")."' type='button' class='btn btn-info btn-flat' style='width: 40px' title='Edit Supplier'><i class='fa fa-edit'></i></a>"
           . "<a href='".site_url("mrp/mrp-master/supplier-inventory/{$da->id_mrp_supplier}")."' type='button' class='btn btn-info btn-flat' style='width: 40px' title='Product Supplier'><i class='fa fa-list-alt'></i></a>"       
        . "</div>"
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
      
      $where = "WHERE 1=1 AND A.id_mrp_supplier = '{$id_mrp_supplier}'";
    
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
            $jenis2 = " [Jenis Barang:".$jenis[$da->jenis]."]";
        }else{
            $jenis2 = "";
        }
        
        if($da->typ){
            $type2 = " [Type:".$da->type."]";
        }else{
            $type2 ="";
        }
        
        if($da->brand){
           $brand = " [Brand:".$da->brand."]";
        }else{
            $brand = "";
        }
        
        if($da->satuan){
           $satuan = " [Satuan:".$da->satuan."]";
        }else{
            $satuan = " [Satuan:".$da->satuan."]";
        }
        
        $tgl = date("d F Y", strtotime($da->tanggal));
      $hasil[] = array(
        $da->inventory_umum.$title_spesifik2.$jenis2.$type2.$brand.$satuan,
        number_format($da->harga),
        $tgl, 
        $status[$da->status],   
        "<div class='btn-group'>"
          . "<a href='".site_url("mrp/mrp-master/add-supplier-inventory/{$da->id_mrp_supplier}/{$da->id_mrp_supplier_inventory}")."' type='button' class='btn btn-info btn-flat' style='width: 40px' title='Edit Product'><i class='fa fa-edit'></i></a>"
          . "<a href='".site_url("mrp/mrp-master/history-supplier-inventory/{$da->id_mrp_supplier}/{$da->id_mrp_supplier_inventory}")."' type='button' class='btn btn-info btn-flat' style='width: 40px' title='History Product'><i class='fa fa-columns'></i></a>"       
        . "</div>"
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
      
      
   $data = $this->global_models->get_query("SELECT  A.create_date,A.id_mrp_supplier_inventory,A.id_mrp_supplier,A.harga,A.tanggal,A.status"
        . ",D.name AS inventory_umum,F.code AS type,E.title AS brand,G.title AS satuan,C.jenis"
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
         
        $tgl = date("d F Y H:i:s", strtotime($da->create_date));
      $hasil[] = array(
        $tgl,
        $da->inventory_umum." [Jenis Barang:".$jenis[$da->jenis]."] [Type:".$da->type."] [Brand:".$da->brand."] [Satuan:".$da->satuan."]",
        number_format($da->harga),
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
  
  function get_satuan_po($group_satuan){
    if (empty($_GET['term'])) exit ;
    $q = strtolower($_GET["term"]);
    if (get_magic_quotes_gpc()) $q = stripslashes($q);
    $items = $this->global_models->get_query("
      SELECT *
      FROM mrp_satuan
      WHERE 
      LOWER(title) LIKE '%{$q}%' AND (group_satuan = '{$group_satuan}' AND status = 1)
      LIMIT 0,10
      ");
      
      
//    $where = " AND LOWER(A.title) LIKE '%{$q}%' AND A.status=1";
//      
//    $items = $this->global_models->get_query("SELECT  A.*"
//        . " FROM mrp_satuan AS A"
//        . " WHERE id_mrp_satuan in(SELECT MAX(id_mrp_satuan)
//                FROM mrp_satuan
//               GROUP BY group_satuan
//             ) {$where}"
//        . " LIMIT 0,10 ");

    if(count($items) > 0){
      foreach($items as $tms){
//          if($tms->note){
//              $note = " [".$tms->note."]";
//          }else{
//              $note = "";
//          }
        $result[] = array(
            "id"    => $tms->id_mrp_satuan,
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
  
  function get_mrp_master_inventory_spesifik($start = 0){
      
      $where = "WHERE 1=1";
     
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
      $acd = $this->global_models->get("hr_master_organisasi", array("parent" => "{$dta_id}"));

        $no = 0;
        $aa = "";
        foreach ($acd as $val) {
            if($no > 0){
                $aa .= ",".$val->id_hr_master_organisasi;
            }else{
                $aa .= $val->id_hr_master_organisasi;
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
                $qry .= "AND ((A.id_hr_master_organisasi='$dta_id' OR A.id_hr_master_organisasi IN ($aa))OR (A.id_create_by_organisasi='$dta_id' OR A.id_create_by_organisasi IN ($aa)) ";
            }elseif($this->nbscache->get_olahan("permission", $this->session->userdata("id_privilege"), "proses_ro", "edit") !== FALSE){
                $qry .= "AND ((A.id_hr_master_organisasi='$dta_id' OR A.status >='3') OR (A.id_create_by_organisasi='$dta_id' OR A.status >='3')) ";
            }else{
                $qry .= "AND (A.id_hr_master_organisasi='$dta_id' OR A.id_create_by_organisasi='$dta_id')";
            }
        }
            
//        }
        
    $where = "WHERE A.type_inventory = 2 {$qry}";
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
        . " ORDER BY A.id_mrp_request ASC"
        . " LIMIT {$start}, 10"
        );
//    print  $this->db->last_query(); die;
//    $data_array = json_decode($data);
//    $this->debug($data, true);
    
//    $jenis = array(1 => "Habis Pakai", 2 => "Asset");
    $status = array( 1=> "<span class='label bg-orange'>Create</span>", 2 => "<span class='label bg-orange'>Draft</span>",
        3 => "<span class='label bg-green'>Approved</span>",4 => "<span class='label bg-green'>Task Order</span>",
        5 => "<span class='label bg-green'>Proses PO</span>", 6 => "<span class='label bg-green'>Sent PO</span>",
        7 => "<span class='label bg-green'>Partial</span>",8 => "<span class='label bg-green'>RG</span>",9 => "<span class='label bg-green'>Closed Request Orders</span>");
//    $status = array(1 => "Create", 2 => "Approve");
//    $type_inventory = array(1 => "Pengadaan Cetakan", 2 => "Pengadaan ATK");
   $no = 0;
   $hide = 0;
    foreach ($data AS $ky => $da){
            $dt_name = "";
        if($da->status >= 3){
            $dt_name = "<br>Approved:".$da->name;
        }
        
       if($da->status == 1){
           if($da->create_by_users == $id_users OR $id_users == 1){
               $hasil[$ky] = array(
                date('d-M-Y H:i:s', strtotime($da->create_date))."<br>Kode Request:<b>".$da->ro_code."</b>",  
                $da->nama_pegawai."<br>".$da->nip,
                $da->perusahaan."<br>Department:".$da->department,
                $da->note,
                $status[$da->status].$dt_name,
                "<a href='".site_url("mrp/add-task-orders/{$da->id_mrp_task_orders}")."'>{$da->code}</a>",
                $da->create_by,        
                "<div class='btn-group'>"
                  . "<a href='".site_url("mrp/add-request-pengadaan-atk/{$da->id_mrp_request}")."' type='button' class='btn btn-info btn-flat' style='width: 40px'><i class='fa fa-edit'></i></a>"
                . "</div>"
              );
                $hide++;  
           }
            
       }else{
           $hasil[$ky] = array(
                date('d-M-Y H:i:s', strtotime($da->create_date))."<br>Kode Request:<b>".$da->ro_code."</b>",  
                $da->nama_pegawai."<br>".$da->nip,
                $da->perusahaan."<br>Department:".$da->department,
                $da->note,
                $status[$da->status].$dt_name,
                "<a href='".site_url("mrp/add-task-orders/{$da->id_mrp_task_orders}")."'>{$da->code}</a>",
                $da->create_by,
                "<div class='btn-group'>"
                  . "<a href='".site_url("mrp/add-request-pengadaan-atk/{$da->id_mrp_request}")."' type='button' class='btn btn-info btn-flat' style='width: 40px'><i class='fa fa-edit'></i></a>"
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
      $acd = $this->global_models->get("hr_master_organisasi", array("parent" => "{$dta_id}"));

        $no = 0;
        $aa = "";
        foreach ($acd as $val) {
            if($no > 0){
                $aa .= ",".$val->id_hr_master_organisasi;
            }else{
                $aa .= $val->id_hr_master_organisasi;
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
                $qry .= "AND (A.id_hr_master_organisasi='$dta_id' OR A.id_hr_master_organisasi IN ($aa)) ";
            }elseif($this->nbscache->get_olahan("permission", $this->session->userdata("id_privilege"), "proses_ro", "edit") !== FALSE){
                $qry .= "AND (A.id_hr_master_organisasi='$dta_id' OR A.status >='3') ";
            }else{
                $qry .= "AND A.id_hr_master_organisasi='$dta_id'";
            }
        }
        
      $where = "WHERE A.type_inventory = 1 {$qry}";
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
        . " ORDER BY A.id_mrp_request ASC"
        . " LIMIT {$start}, 10"
        );
//      print  $this->db->last_query(); die;
//    $data_array = json_decode($data);
//    $this->debug($data, true);
    
//    $jenis = array(1 => "Habis Pakai", 2 => "Asset");
   $status = array( 1=> "<span class='label bg-orange'>Create</span>", 2 => "<span class='label bg-orange'>Draft</span>",
        3 => "<span class='label bg-green'>Approved</span>",4 => "<span class='label bg-green'>Task Order</span>",
        5 => "<span class='label bg-green'>Proses PO</span>", 6 => "<span class='label bg-green'>Sent PO</span>",
        7 => "<span class='label bg-green'>Partial</span>",8 => "<span class='label bg-green'>RG</span>",9 => "<span class='label bg-green'>Closed Request Orders</span>");
//    $status = array(1 => "Create", 2 => "Approve");
    
    
    $type_inventory = array(1 => "Pengadaan Cetakan", 2 => "Pengadaan ATK");
        $no = 0;
       $hide = 0;
       
    foreach ($data AS $ky => $da){
            $dt_name = "";
        if($da->status >= 3){
            $dt_name = "<br>Approved:".$da->name;
        }
        
        if($da->status == 1){
             if($da->create_by_users == $id_users OR $id_users == 1){
                  $hasil[$ky] = array(
       date('d-M-Y H:i:s', strtotime($da->create_date))."<br>Kode Request:<b>".$da->ro_code."</b>", 
        $da->nama_pegawai."<br>".$da->nip,
        $da->perusahaan."<br>Department:".$da->department,
        "[".$type_inventory[$da->type_inventory]."]<br>".$da->note,
        $status[$da->status].$dt_name,
        "<a href='".site_url("mrp/add-task-orders/{$da->id_mrp_task_orders}")."'>{$da->code}</a>",  
        "<div class='btn-group'>"
          . "<a href='".site_url("mrp/add-request-pengadaan-cetakan/{$da->id_mrp_request}")."' type='button' class='btn btn-info btn-flat' style='width: 40px'><i class='fa fa-edit'></i></a>"
        . "</div>"
      );
          $hide++;
             }
           
        }else{
             $hasil[$ky] = array(
       date('d-M-Y H:i:s', strtotime($da->create_date))."<br>Kode Request:<b>".$da->ro_code."</b>", 
        $da->nama_pegawai."<br>".$da->nip,
        $da->perusahaan."<br>Department:".$da->department,
        "[".$type_inventory[$da->type_inventory]."]<br>".$da->note,
        $status[$da->status].$dt_name,
        "<a href='".site_url("mrp/add-task-orders/{$da->id_mrp_task_orders}")."'>{$da->code}</a>",  
        "<div class='btn-group'>"
          . "<a href='".site_url("mrp/add-request-pengadaan-cetakan/{$da->id_mrp_request}")."' type='button' class='btn btn-info btn-flat' style='width: 40px'><i class='fa fa-edit'></i></a>"
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
      
      if($this->session->userdata("id") == 1 OR $this->nbscache->get_olahan("permission", $this->session->userdata("id_privilege"), "edit-form-ro", "edit") !== FALSE){
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
        . "A.id_mrp_inventory_spesifik,A.title AS title_spesifik,E.jumlah,G.harga,I.id_mrp_setting_lock_atk"
        . " ,E.id_mrp_request_asset AS id_spesifik"
        . " FROM mrp_inventory_spesifik AS A"
        . " LEFT JOIN mrp_inventory_umum AS B ON A.id_mrp_inventory_umum = B.id_mrp_inventory_umum"
        . " LEFT JOIN mrp_brand AS C ON A.id_mrp_brand = C.id_mrp_brand"
        . " LEFT JOIN mrp_satuan AS D ON A.id_mrp_satuan = D.id_mrp_satuan"
        . " LEFT JOIN mrp_request_asset AS E ON (A.id_mrp_inventory_spesifik = E.id_mrp_inventory_spesifik AND E.id_mrp_request = '{$id_mrp_request}')"
        . " LEFT JOIN mrp_request AS F ON E.id_mrp_request = F.id_mrp_request"
        . " LEFT JOIN mrp_supplier_inventory AS G ON (A.id_mrp_inventory_spesifik = G.id_mrp_inventory_spesifik AND G.id_mrp_supplier = '{$id_mrp_supplier}')"
        . " LEFT JOIN mrp_setting_lock_atk_asset AS H ON A.id_mrp_inventory_spesifik = H.id_mrp_inventory_spesifik"
        . " LEFT JOIN mrp_setting_lock_atk AS I ON (H.id_mrp_setting_lock_atk = I.id_mrp_setting_lock_atk AND I.id_hr_company = '{$ls[0]->id_hr_company}' AND I.id_hr_master_organisasi ='{$ls[0]->id_hr_master_organisasi}')"        
        . " {$where} "
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
        if($da->harga){
            $harga = number_format($da->harga);
        }else{
            $harga = "";
        }
        
        $data_id = "id=dt_id_mrp_inventory_spesifik_".$da->id_mrp_inventory_spesifik;
        $data_jumlah = "id=jumlah".$da->id_mrp_inventory_spesifik;
        if($da->id_mrp_setting_lock_atk){
            $dt_jumlah = $this->form_eksternal->form_input("jumlah[]", $jumlah, $disabled.$data_jumlah.'  style="width:100px;display:none" class="form-control jumlah input-sm" placeholder="Jumlah"');
        }else{
            $dt_jumlah = $this->form_eksternal->form_input("jumlah[]", $jumlah, $disabled.$data_jumlah.'  style="width:100px" class="form-control jumlah input-sm" placeholder="Jumlah"');
        }
        
        if($da->id_spesifik){
           $btn_del =  "<div class='btn-group'>"
        . "<a href='javascript:void(0)' onclick='delete_request_pengadaan({$da->id_spesifik})' id='del_{$da->id_spesifik}' class='btn btn-danger btn-flat' style='width: 40px'><i class='fa fa-trash-o'></i></a>"
        ."<span style='display: none; margin-left: 10px;' id='img-page-{$da->id_spesifik}'><img width='35px' src='{$url}' /></span>"
        . "</div>";
        }
        
      $hasil[] = array(
          $no =$no + 1,
        $da->inventory_umum." ".$da->title_spesifik,
        $da->brand,
        $da->satuan,
        $harga,  
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
      
      if($this->session->userdata("id") == 1 OR $this->nbscache->get_olahan("permission", $this->session->userdata("id_privilege"), "edit-form-ro", "edit") !== FALSE){
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
        . " {$where} "
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
        if($da->id_mrp_setting_lock_atk){
            $dt_jumlah = $this->form_eksternal->form_input("jumlah[]", $jumlah, $disabled.$data_jumlah.'  style="width:100px;display:none" class="form-control jumlah input-sm" placeholder="Jumlah"');
        }else{
            $dt_jumlah = $this->form_eksternal->form_input("jumlah[]", $jumlah, $disabled.$data_jumlah.'  style="width:100px" class="form-control jumlah input-sm" placeholder="Jumlah"');
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
        }elseif($type == 2){
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
        }elseif($type == 2){
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
  
    function ajax_form_grouping_mrp_request_pengadaan($type,$id_mrp_task_orders,$create_by_users,$start = 0){
     
//      $status = $this->global_models->get_field("mrp_request", "status", 
//                    array("id_mrp_request" => "{$id_mrp_request}"));
//                    
//      $dt_user = $this->global_models->get_field("mrp_request", "create_by_users", 
//                    array("id_mrp_request" => "{$id_mrp_request}"));              
      
      $where = "WHERE G.id_mrp_task_orders = {$id_mrp_task_orders} AND F.create_by_users ={$create_by_users} AND A.status = 1 AND B.id_mrp_type_inventory = {$type} AND jumlah IS NOT NULL";
      
      
      $data = $this->global_models->get_query("SELECT B.name AS inventory_umum,C.title AS brand,D.title AS satuan,"
        . "A.id_mrp_inventory_spesifik,A.title AS title_spesifik,SUM(E.jumlah) AS jumlah,F.id_mrp_request,F.status AS status_request,E.rg"
        . " FROM mrp_inventory_spesifik AS A"
        . " LEFT JOIN mrp_inventory_umum AS B ON A.id_mrp_inventory_umum = B.id_mrp_inventory_umum"
        . " LEFT JOIN mrp_brand AS C ON A.id_mrp_brand = C.id_mrp_brand"
        . " LEFT JOIN mrp_satuan AS D ON A.id_mrp_satuan = D.id_mrp_satuan"
        . " LEFT JOIN mrp_request_asset AS E ON (A.id_mrp_inventory_spesifik = E.id_mrp_inventory_spesifik)"
        . " LEFT JOIN mrp_request AS F ON E.id_mrp_request = F.id_mrp_request"
        . " LEFT JOIN mrp_task_orders_request AS G ON F.id_mrp_request = G.id_mrp_request"
        . " {$where} "
        . " GROUP BY F.create_by_users,A.id_mrp_inventory_spesifik"
//        . " ORDER BY A.id_mrp_inventory_spesifik ASC"
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
        }elseif($type == 2){
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
      if($status == 1 AND  $this->session->userdata("id") != $dt_user){
          $where .= " AND jumlah IS NOT NULL";
          $disabled = "disabled ";  
      }elseif($status > 1){
          $where .= " AND jumlah IS NOT NULL";
          $disabled = "disabled ";  
      }
      
       if($this->session->userdata("id") == 1 OR ($status == 2 AND $this->nbscache->get_olahan("permission", $this->session->userdata("id_privilege"), "approval-ro", "edit") !== FALSE)){
       $disabled ="";
      }
      
      if($this->session->userdata("id") == 1 OR $this->nbscache->get_olahan("permission", $this->session->userdata("id_privilege"), "edit-form-ro", "edit") !== FALSE){
       $disabled ="";
      }
      

      
      $data = $this->global_models->get_query("SELECT B.name AS inventory_umum,D.title AS satuan,A.title AS title_spesifik,"
        . "A.id_mrp_inventory_spesifik,E.jumlah,E.id_mrp_request_asset AS id_spesifik"
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
        $data_id = "id=dt_id_mrp_inventory_spesifik_".$da->id_mrp_inventory_spesifik;
        $data_jumlah = "id=jumlah".$da->id_mrp_inventory_spesifik;
        $no =$no + 1;
        $btn_del = "";
        if($da->id_spesifik){
           $btn_del =  "<div class='btn-group'>"
        . "<a href='javascript:void(0)' onclick='delete_request_pengadaan({$da->id_spesifik})' id='del_{$da->id_spesifik}' class='btn btn-danger btn-flat' style='width: 40px'><i class='fa fa-trash-o'></i></a>"
        ."<span style='display: none; margin-left: 10px;' id='img-page-{$da->id_spesifik}'><img width='35px' src='{$url}' /></span>"
        . "</div>";
        }
        
      $hasil[] = array(
          $no,
        $da->inventory_umum." ".$da->title_spesifik,
        $da->satuan,
        $this->form_eksternal->form_input("id_mrp_inventory_spesifik[]", $da->id_mrp_inventory_spesifik, $data_id.' style="width:100px;display:none" class="form-control id_spesifik input-sm" placeholder=""').
        $this->form_eksternal->form_input("jumlah[]", $jumlah, $disabled.$data_jumlah.' style="width:100px" class="form-control jumlah input-sm" placeholder="Jumlah"')
        ,$btn_del
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
      
      if($this->session->userdata("id") == 1 OR $this->nbscache->get_olahan("permission", $this->session->userdata("id_privilege"), "edit-form-ro", "edit") !== FALSE){
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
        $no =$no + 1;
      $hasil[] = array(
          $no,
        $da->inventory_umum." ".$da->title_spesifik,
        $da->satuan,
        $this->form_eksternal->form_input("id_mrp_inventory_spesifik[]", $da->id_mrp_inventory_spesifik, $data_id.' style="width:100px;display:none" class="form-control id_spesifik input-sm" placeholder=""').
        $this->form_eksternal->form_input("jumlah[]", $jumlah, $disabled.$data_jumlah.' style="width:100px" class="form-control jumlah input-sm" placeholder="Jumlah"')
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
   $id_spesifik = $_POST['id_spesifik'];
     $jumlah =  $_POST['jumlah'];
     $note =  $_POST['note'];
      $id_pegawai =  $_POST['id_hr_pegawai'];
    $id_mrp_request =  $_POST['id_mrp_request'];
    $id_receiver = $_POST['id_receiver'];
     
    $arr_id = explode(",",$id_spesifik);
    $arr_jumlah = explode(",",$jumlah);
    $id_hr_pegawai = $this->global_models->get_field("hr_pegawai", "id_hr_pegawai", 
                    array("id_users" => $this->session->userdata("id")));
//    $aa = array($id_spesifik,$jumlah,$note,$id_mrp_request);
//      print_r($aa); die;    
      
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
            "status"                      => 2,
            "type_inventory"              => $type_inventory,
            "create_by_users"             => $this->session->userdata("id"),
            "create_date"                 => date("Y-m-d H:i:s")
        );
        $id_mrp_request = $this->global_models->insert("mrp_request", $kirim);
        
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
   $id_spesifik = $_POST['id_spesifik'];
     $jumlah =  $_POST['jumlah'];
     $note =  $_POST['note'];
     $id_pegawai =  $_POST['id_hr_pegawai'];
    $id_mrp_request =  $_POST['id_mrp_request'];
    $id_receiver = $_POST['id_receiver'];
     
    $arr_id = explode(",",$id_spesifik);
    $arr_jumlah = explode(",",$jumlah);
    $arr_id_mrp_request = explode(",",$id_mrp_request);
    $id_hr_pegawai = $this->global_models->get_field("hr_pegawai", "id_hr_pegawai", 
                    array("id_users" => $this->session->userdata("id")));
//    $aa = array($id_spesifik,$jumlah,$note,$id_mrp_request);
//      print_r($aa); die;    
      
   
    if($id_mrp_request > 0){
        
       $id_hr_pegawai = $this->global_models->get_field("mrp_request", "id_hr_pegawai", 
                    array("id_mrp_request" => $id_mrp_request));
       
        if($id_pegawai != 0){
        $dt_hr_pegawai = $id_pegawai;
        }else{
        $dt_hr_pegawai = $id_hr_pegawai;
        }
        
        $kirim = array(
           "id_hr_pegawai"                => $dt_hr_pegawai,
            "user_pegawai_receiver"                => $id_receiver,
            "note"                        => $note,
            "update_by_users"               => $this->session->userdata("id"),
            "update_date"                   => date("Y-m-d H:i:s")
        );
        $this->global_models->update("mrp_request", array("id_mrp_request" => $id_mrp_request),$kirim);
       
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
        $dt_hr_pegawai = $id_hr_pegawai;
        }
        
        $this->olah_request_order_code($kode);
        $kirim = array(
           "id_hr_pegawai"                => $dt_hr_pegawai,
            "user_pegawai_receiver"       => $id_receiver,
            "note"                        => $note,
            "code"                        => $kode,
            "status"                      => 1,
            "type_inventory"              => $type_inventory,
            "create_by_users"             => $this->session->userdata("id"),
            "create_date"                 => date("Y-m-d H:i:s")
        );
        $id_mrp_request = $this->global_models->insert("mrp_request", $kirim);
        
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
   $id_spesifik = $_POST['id_spesifik'];
    $jumlah =  $_POST['jumlah'];
    $note =  $_POST['note'];
    $id_pegawai =  $_POST['id_hr_pegawai'];
    $id_mrp_request =  $_POST['id_mrp_request'];
    $id_receiver = $_POST['id_receiver'];
     
    $arr_id = explode(",",$id_spesifik);
    $arr_jumlah = explode(",",$jumlah);
    $id_hr_pegawai = $this->global_models->get_field("hr_pegawai", "id_hr_pegawai", 
                    array("id_users" => $this->session->userdata("id")));
    
    $status = $this->global_models->get_field("mrp_request", "status", 
                    array("id_mrp_request" => $id_mrp_request));
    
//    $aa = array($id_spesifik,$jumlah,$note,$id_mrp_request);
//      print_r($aa); die;    
      
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
        }else{
            $kirim = array(
            "note"                        => $note,
            "status"                       => $status,
            "update_by_users"               => $this->session->userdata("id"),
            "update_date"                   => date("Y-m-d H:i:s")
            );
            $this->global_models->update("mrp_request", array("id_mrp_request" => $id_mrp_request),$kirim);
        }
       
        $this->global_models->delete("mrp_request_asset", array("id_mrp_request" => $id_mrp_request));
        foreach ($arr_jumlah as $key => $val2) {
            if($val2 > 0){
                    $kirim = array(
                    "id_mrp_request"                => $id_mrp_request,
                    "id_mrp_inventory_spesifik"     => $arr_id[$key],
                    "jumlah"                        => $val2,   
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
  
  function get_mrp_task_orders($start = 0){
      
//      $where = "WHERE A.kategori_inventory = 2";
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
      
    $data = $this->global_models->get_query("SELECT A.id_mrp_task_orders,A.title,A.note,A.status,A.code,A.create_date "
//            . ",E.code AS department,F.title AS perusahaan"
        . " FROM mrp_task_orders AS A"
//        . " LEFT JOIN hr_pegawai AS B ON A.id_hr_pegawai = B.id_hr_pegawai"
//        . " LEFT JOIN m_users AS C ON B.id_users = C.id_users"
//        . " LEFT JOIN hr_company_department AS D ON B.id_hr_company_department = D.id_hr_company_department"    
//        . " LEFT JOIN hr_department AS E ON D.id_hr_department = E.id_hr_department"
//        . " LEFT JOIN hr_company AS F ON B.id_hr_company = F.id_hr_company"    
//        . " LEFT JOIN mrp_brand AS C ON A.id_mrp_brand = C.id_mrp_brand"
//        . " LEFT JOIN mrp_qty AS D ON A.id_mrp_qty = D.id_mrp_qty"    
//        . " {$where}"
        . " ORDER BY A.id_mrp_task_orders ASC"
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
    $status = array( 1=> "<span class='label bg-orange'>Create</span>", 2 => "<span class='label bg-green'>Proses PO</span>",
        3 => "<span class='label bg-green'>Approved PO</span>", 4 =>"<span class='label bg-green'>Sent PO</span>",9 =>"<span class='label bg-green'>Closed Task Orders</span>"
        );
    
    if($da->tanggal_dikirim != "0000-00-00" AND $da->tanggal_dikirim != ""){
            $tgl = date("d M Y", strtotime($da->tanggal_dikirim));
        }else{
            $tgl = "";
        }
//    $status = array(1 => "Create", 2 => "Approve");
    foreach ($data AS $da){
      $hasil[] = array(
        $da->code,
        date("d M Y H:i:s", strtotime($da->create_date)),  
        $da->title,
        nl2br($da->note),
        $status[$da->status], 
        "<div class='btn-group'>"
          . "<a href='".site_url("mrp/add-task-orders/{$da->id_mrp_task_orders}")."' type='button' class='btn btn-info btn-flat' title='Edit Task Order' style='width: 40px'><i class='fa fa-edit'></i></a>"
          . "<a href='".site_url("mrp/po/{$da->id_mrp_task_orders}")."' type='button' class='btn btn-info btn-flat' title='Purchase Order' style='width: 40px'><i class='fa fa-shopping-cart'></i></a>"
        . "</div>"
      );
    }
    $return['hasil'] = $hasil;
//    $this->debug($return, true);
    print json_encode($return);
    die;
  }
  
  function get_task_mrp_request_pengadaan($dttype =0,$start = 0){
      
    $where = "WHERE A.status = 3";   
      
    $data = $this->global_models->get_query("SELECT A.id_mrp_request,C.name AS nama_pegawai,B.nip,A.note,A.status,A.type_inventory,A.code AS ro_kode "
            . ",D.code AS department,F.title AS perusahaan,G.name AS nama_approved"
        . " FROM mrp_request AS A"
        . " LEFT JOIN hr_pegawai AS B ON A.id_hr_pegawai = B.id_hr_pegawai"
        . " LEFT JOIN m_users AS C ON B.id_users = C.id_users"
        . " LEFT JOIN hr_master_organisasi AS D ON B.id_hr_master_organisasi = D.id_hr_master_organisasi"    
//        . " LEFT JOIN hr_department AS E ON D.id_hr_department = E.id_hr_department"
        . " LEFT JOIN hr_company AS F ON B.id_hr_company = F.id_hr_company"
        . " LEFT JOIN m_users AS G ON A.user_approval = G.id_users"
//        . " LEFT JOIN mrp_brand AS C ON A.id_mrp_brand = C.id_mrp_brand"
//        . " LEFT JOIN mrp_qty AS D ON A.id_mrp_qty = D.id_mrp_qty"    
        . " {$where}"
        . " ORDER BY A.id_mrp_request ASC"
        . " LIMIT {$start}, 5");
        
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
    $status = array( 1=> "<span class='label bg-orange'>Create</span>",
        2 => "<span class='label bg-orange'>Draft</span>",
        3 => "<span class='label bg-green'>Approved</span>",
        4 => "<span class='label bg-green'>Task Orders</span>");
    
//    $status = array(1 => "Create", 2 => "Approve");
    foreach ($data AS $da){
        
        if($da->type_inventory == 1){
            $dt_link = "cetakan";
        }else{
            $dt_link = "atk";
        }
        $dt_approved = "";
        if($da->nama_approved){
            $dt_approved = "[Approved By ".$da->nama_approved."]";
        }
        
        
      $type = array(1 => "Cetakan", 2 => "ATK");
      if($dttype == 1){
          $hasil[] = array(
        $this->form_eksternal->form_checkbox('status', $da->id_mrp_request, FALSE,'class="dt_id"'),
//        "<a href='".site_url("mrp/add-request-pengadaan-{$dt_link}/{$da->id_mrp_request}")."'>".$da->ro_kode."</a>",  
        "<a data-toggle='collapse' data-parent='#accordion'  href='#collapseOne' onclick='coba_data({$da->type_inventory},{$da->id_mrp_request});'>".$da->ro_kode."</a>",
        $type[$da->type_inventory],  
        $da->nama_pegawai."<br>".$da->nip."<br>".$dt_approved,
        $da->perusahaan."<br>Department:".$da->department,
        $da->note,
        $status[$da->status]
        . "<script>"
            . "function coba_data(typ_inventory,id_mrp_req){"
                . "if(typ_inventory == 1){"
                    . '$("#Atableboxy2").hide();'
                    . '$("#Atableboxy1").show();'
                    . 'var table = '
                    . '$("#tableboxy-req1").dataTable({'
                    . '"order": [[ 0, "asc" ]],'
                    . '"bDestroy": true'
                    . '});'
                    . "table.fnClearTable();"
                    . 'ambil_data5(table, 0,id_mrp_req,typ_inventory);'
                . "}else{"
                    . '$("#Atableboxy1").hide();'
                    . '$("#Atableboxy2").show();'
                    . 'var table = '
                    . '$("#tableboxy-req2").dataTable({'
                    . '"order": [[ 0, "asc" ]],'
                    . '"bDestroy": true'
                    . '});'
                    . "table.fnClearTable();"
                    . 'ambil_data5(table, 0,id_mrp_req,typ_inventory);'
                . "}"
                
            . "}"
        
                . 'function ambil_data5(table, mulai,id_mrp_req,type){'
                . '$.post("'.site_url("mrp/mrp-ajax/ajax-form-mrp-request-pengadaan").'/"+type+"/"+id_mrp_req+"/"+mulai, function(data){'
                  . '$("#loader-page").show();'
                  . 'var hasil = $.parseJSON(data);'
                  . 'if(hasil.status == 2){'
                    . 'table.fnAddData(hasil.hasil);'
                    . 'ambil_data5(table, hasil.start,id_mrp_req,type);'
                  . '}'
                  . 'else{'
                    . '$("#loader-page").hide();'
                  . '}'
                . '});'
              . '}'
        . "</script>"
      );
      }elseif($dttype == 2){
        $note = "";
        if($da->note){
          $note = "<br>Note:".$da->note;
        }
        $hasil[] = array(
        $this->form_eksternal->form_checkbox('status', $da->id_mrp_request, FALSE,'class="dt_id"'),
//        "<a href='".site_url("mrp/add-request-pengadaan-{$dt_link}/{$da->id_mrp_request}")."'>".$da->ro_kode."</a>",  
        "<a data-toggle='collapse' data-parent='#accordion'  href='#collapseOne' onclick='coba_data({$da->type_inventory},{$da->id_mrp_request});'>".$da->ro_kode."<br>Type:".$type[$da->type_inventory].$note."</a>",
        "Pegawai:".$da->nama_pegawai."<br>Perusahaan:".$da->perusahaan."<br>Department:".$da->department."<br>".$dt_approved,
        $status[$da->status]
        . "<script>"
            . "function coba_data(typ_inventory,id_mrp_req){"
                . "if(typ_inventory == 1){"
                    . '$("#Atableboxy2").hide();'
                    . '$("#Atableboxy1").show();'
                    . 'var table = '
                    . '$("#tableboxy-req1").dataTable({'
                    . '"order": [[ 0, "asc" ]],'
                    . '"bDestroy": true'
                    . '});'
                    . "table.fnClearTable();"
                    . 'ambil_data5(table, 0,id_mrp_req,typ_inventory);'
                . "}else{"
                    . '$("#Atableboxy1").hide();'
                    . '$("#Atableboxy2").show();'
                    . 'var table = '
                    . '$("#tableboxy-req2").dataTable({'
                    . '"order": [[ 0, "asc" ]],'
                    . '"bDestroy": true'
                    . '});'
                    . "table.fnClearTable();"
                    . 'ambil_data5(table, 0,id_mrp_req,typ_inventory);'
                . "}"
                
            . "}"
        
                . 'function ambil_data5(table, mulai,id_mrp_req,type){'
                . '$.post("'.site_url("mrp/mrp-ajax/ajax-form-mrp-request-pengadaan").'/"+type+"/"+id_mrp_req+"/"+mulai, function(data){'
                  . '$("#loader-page").show();'
                  . 'var hasil = $.parseJSON(data);'
                  . 'if(hasil.status == 2){'
                    . 'table.fnAddData(hasil.hasil);'
                    . 'ambil_data5(table, hasil.start,id_mrp_req,type);'
                  . '}'
                  . 'else{'
                    . '$("#loader-page").hide();'
                  . '}'
                . '});'
              . '}'
        . "</script>"
      );
      }
      
    }
    $return['hasil'] = $hasil;
//    $this->debug($return, true);
    print json_encode($return);
    die;
  }
  
    function insert_task_orders(){
    $id_mrp_request = $_POST['id_mrp_request'];
    $title =  $_POST['title'];
    $note =  $_POST['note'];
    $id_mrp_task_orders =  $_POST['id_mrp_task_orders'];
     
    $arr_id = explode(",",$id_mrp_request);
//    print $title."<br>";
//   print_r($arr_id);
//   die;("aa");
    
    if($id_mrp_task_orders > 0){
        
        $cek = $this->global_models->get_field("mrp_task_orders", "code", array("id_mrp_task_orders" => $id_mrp_task_orders));
        
        if($cek){
            $kode = $cek;
        }else{
            $this->olah_task_order_code($kode);
        }
        
        $kirim = array(
            "title"                 => $title,
            "code"                  => $kode,
            "note"                  => $note,
            "update_by_users"       => $this->session->userdata("id"),
            "update_date"           => date("Y-m-d H:i:s")
        );
        $id_mrp_task_orders2 = $this->global_models->update("mrp_task_orders", array("id_mrp_task_orders" => $id_mrp_task_orders),$kirim);
       
        if($id_mrp_task_orders2 > 0){
            foreach ($arr_id as $key => $val) {
            if($val > 0){
                $kirim = array(
                "id_mrp_task_orders"            => $id_mrp_task_orders,
                "id_mrp_request"                => $arr_id[$key],
                "status"                        => 3,
                "create_by_users"               => $this->session->userdata("id"),
                "create_date"                   => date("Y-m-d H:i:s")
            );
            $task_orders_request[$key] = $this->global_models->insert("mrp_task_orders_request", $kirim);
            
             $kirim3 = array(
                "status"                        => 4,
                "update_by_users"               => $this->session->userdata("id"),
                "update_date"                   => date("Y-m-d H:i:s")
                );
           
             $mrp_request[$key] = $this->global_models->update("mrp_request", array("id_mrp_request" => $arr_id[$key]),$kirim3);
            
             $mrp_request_asset[$key] = $this->global_models->get("mrp_request_asset", array("id_mrp_request" => $arr_id[$key]));
             foreach ($mrp_request_asset[$key] as $ky => $value) {
               $dt_jumlah = $this->global_models->get_field("mrp_task_orders_request_asset", "jumlah",array("id_mrp_task_orders" => "{$id_mrp_task_orders}","id_mrp_inventory_spesifik" => "{$value->id_mrp_inventory_spesifik}"));
               
//               $dt_jumlah_po = $this->global_models->get_field("mrp_po_asset", "jumlah",array("id_mrp_task_orders" => "{$id_mrp_task_orders}","id_mrp_inventory_spesifik" => "{$value->id_mrp_inventory_spesifik}"));
               
               
               if($dt_jumlah > 0){
                   $jml =  $dt_jumlah + $value->jumlah;
                   $kirim3 = array(
                    "jumlah"                        =>  $jml, 
                    "update_by_users"               => $this->session->userdata("id"),
                    "update_date"                   => date("Y-m-d H:i:s")
                    );
           
                    $this->global_models->update("mrp_task_orders_request_asset", array("id_mrp_task_orders" => "{$id_mrp_task_orders}","id_mrp_inventory_spesifik" => "{$value->id_mrp_inventory_spesifik}"),$kirim3);
                    
                    $this->global_models->update("mrp_po_asset", array("id_mrp_task_orders" => "{$id_mrp_task_orders}","id_mrp_inventory_spesifik" => "{$value->id_mrp_inventory_spesifik}"),$kirim3);

               }else{
                   $id_satuan =$this->global_models->get_field("mrp_inventory_spesifik", "id_mrp_satuan", array("id_mrp_inventory_spesifik" => $value->id_mrp_inventory_spesifik));
     
                   $kirim = array(
                    "id_mrp_task_orders"            => $id_mrp_task_orders,
                    "id_mrp_inventory_spesifik"     => $value->id_mrp_inventory_spesifik,
                    "jumlah"                        => $value->jumlah,
                    "id_mrp_satuan"                 => $id_satuan,
                    "status"                        => 1,
                    "create_by_users"               => $this->session->userdata("id"),
                    "create_date"                   => date("Y-m-d H:i:s")
                    );
                    $id_asset = $this->global_models->insert("mrp_task_orders_request_asset", $kirim);
                    
                    $kirim = array(
                    "id_mrp_task_orders"                => $id_mrp_task_orders,
                    "id_mrp_inventory_spesifik"         => $value->id_mrp_inventory_spesifik,
                    "id_mrp_task_orders_request_asset"  => $id_asset,
                    "jumlah"                            => $value->jumlah,
                    "id_mrp_satuan"                     => $id_satuan,
                    "status"                            => 1,
                    "create_by_users"                   => $this->session->userdata("id"),
                    "create_date"                       => date("Y-m-d H:i:s")
                    );
                     $this->global_models->insert("mrp_po_asset", $kirim);
               }
                 
             }
            }
        }
        }
        
    }else{
        $this->olah_task_order_code($kode);
        $kirim = array(
            "title"                => $title,
            "code"                  => $kode,
            "note"                 => $note,
            "status"                      => 1,
            "create_by_users"             => $this->session->userdata("id"),
            "create_date"                 => date("Y-m-d H:i:s")
        );
        $id_mrp_task_orders2 = $this->global_models->insert("mrp_task_orders", $kirim);
        
         foreach ($arr_id as $key => $val) {
            if($val > 0){
                $kirim = array(
                "id_mrp_task_orders"            => $id_mrp_task_orders2,
                "id_mrp_request"                => $arr_id[$key],
                "status"                        => 1,
                "create_by_users"               => $this->session->userdata("id"),
                "create_date"                   => date("Y-m-d H:i:s")
            );
            $task_orders_request[$key] = $this->global_models->insert("mrp_task_orders_request", $kirim);
            
             $kirim3 = array(
                "status"                        => 4,
                "update_by_users"               => $this->session->userdata("id"),
                "update_date"                   => date("Y-m-d H:i:s")
                );
           
             $mrp_request[$key] = $this->global_models->update("mrp_request", array("id_mrp_request" => $arr_id[$key]),$kirim3);
            
             $mrp_request_asset[$key] = $this->global_models->get("mrp_request_asset", array("id_mrp_request" => $arr_id[$key]));
             foreach ($mrp_request_asset[$key] as $ky => $value) {
               $dt_jumlah = $this->global_models->get_field("mrp_task_orders_request_asset", "jumlah",array("id_mrp_task_orders" => "{$id_mrp_task_orders2}","id_mrp_inventory_spesifik" => "{$value->id_mrp_inventory_spesifik}"));
               if($dt_jumlah > 0){
                   $jml =  $dt_jumlah + $value->jumlah;
                   $kirim_request_asset = array(
                    "jumlah"                        =>  $jml, 
                    "update_by_users"               => $this->session->userdata("id"),
                    "update_date"                   => date("Y-m-d H:i:s")
                    );
           
                    $this->global_models->update("mrp_task_orders_request_asset", array("id_mrp_task_orders" => "{$id_mrp_task_orders2}","id_mrp_inventory_spesifik" => "{$value->id_mrp_inventory_spesifik}"),$kirim_request_asset);
            
                     
                    $this->global_models->update("mrp_po_asset", array("id_mrp_task_orders" => "{$id_mrp_task_orders2}","id_mrp_inventory_spesifik" => "{$value->id_mrp_inventory_spesifik}"),$kirim_request_asset);

               }else{
                    $id_satuan =$this->global_models->get_field("mrp_inventory_spesifik", "id_mrp_satuan", array("id_mrp_inventory_spesifik" => $value->id_mrp_inventory_spesifik));
     
                   $kirim = array(
                    "id_mrp_task_orders"            => $id_mrp_task_orders2,
                    "id_mrp_inventory_spesifik"     => $value->id_mrp_inventory_spesifik,
                    "jumlah"                        => $value->jumlah, 
                    "id_mrp_satuan"                 => $id_satuan,   
                    "status"                        => 1,
                    "create_by_users"               => $this->session->userdata("id"),
                    "create_date"                   => date("Y-m-d H:i:s")
                    );
                    $id_asset = $this->global_models->insert("mrp_task_orders_request_asset", $kirim);
                    
                     $kirim = array(
                    "id_mrp_task_orders"                => $id_mrp_task_orders2,
                    "id_mrp_inventory_spesifik"         => $value->id_mrp_inventory_spesifik,
                    "id_mrp_task_orders_request_asset"  => $id_asset,   
                    "jumlah"                            => $value->jumlah,
                    "id_mrp_satuan"                     => $id_satuan,
                    "status"                            => 1,
                    "create_by_users"                   => $this->session->userdata("id"),
                    "create_date"                       => date("Y-m-d H:i:s")
                    );
                    $this->global_models->insert("mrp_po_asset", $kirim);
               }
                 
             }
            }
        }
    }
    
    if($id_mrp_task_orders2 > 0){
        $this->session->set_flashdata('success', 'Data tersimpan');
    }
//     $array =array($cc,$kk,$note);
//     print_r($array);
     die;
  }
 
    function get_task_orders_request($id_mrp_task_orders = 0,$start = 0){
      
      $where = "WHERE A.status >= 4 AND G.id_mrp_task_orders = '$id_mrp_task_orders'";   
      
    $data = $this->global_models->get_query("SELECT A.code AS req_code,A.id_mrp_request,G.id_mrp_task_orders,C.name AS nama_pegawai,B.nip,A.note,A.status,A.type_inventory "
        . ",D.code AS department,F.title AS perusahaan"
        . " FROM mrp_request AS A"
        . " LEFT JOIN hr_pegawai AS B ON A.id_hr_pegawai = B.id_hr_pegawai"
        . " LEFT JOIN m_users AS C ON B.id_users = C.id_users"
        . " LEFT JOIN hr_master_organisasi AS D ON B.id_hr_master_organisasi = D.id_hr_master_organisasi"    
//        . " LEFT JOIN hr_department AS E ON D.id_hr_department = E.id_hr_department"
        . " LEFT JOIN hr_company AS F ON B.id_hr_company = F.id_hr_company"
        . " LEFT JOIN mrp_task_orders_request AS G ON A.id_mrp_request = G.id_mrp_request"    
//        . " LEFT JOIN mrp_brand AS C ON A.id_mrp_brand = C.id_mrp_brand"
//        . " LEFT JOIN mrp_qty AS D ON A.id_mrp_qty = D.id_mrp_qty"    
        . " {$where}"
        . " ORDER BY A.id_mrp_request ASC"
        . " LIMIT {$start}, 10");
//       print   $this->db->last_query();
//       die;
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
    $status = array( 1=> "<span class='label bg-orange'>Create</span>", 
        2 => "<span class='label bg-orange'>Draft</span>",
        3 => "<span class='label bg-green'>Approved</span>",
        4 => "<span class='label bg-green'>Task Orders</span>",
        5 => "<span class='label bg-green'>Proses PO</span>",
        6 => "<span class='label bg-green'>Sent PO</span>",
        7 => "<span class='label bg-green'>Partial</span>",
        8 => "<span class='label bg-green'>RG</span>",
        9 => "<span class='label bg-green'>Closed Request Orders</span>");
        $url = base_url()."themes/".DEFAULTTHEMES."/img/ajax-loader.gif";
//    $status = array(1 => "Create", 2 => "Approve");
    foreach ($data AS $da){
        
        if($da->type_inventory == 1){
            $dt_link = "cetakan";
        }else{
            $dt_link = "atk";
        }
        
        $type = array(1 => "Cetakan", 2 => "ATK");
        
        if($da->status <= 4){
           $btn = "<a href='javascript:void(0)' onclick='delete_task_order_request({$da->id_mrp_request})' id='del_{$da->id_mrp_request}' class='btn btn-danger btn-flat' style='width: 40px'><i class='fa fa-trash-o'></i></a>"
            ."<span style='display: none; margin-left: 10px;' id='img-page-{$da->id_mrp_request}'><img width='35px' src='{$url}' /></span>";
        }
        $lnk = site_url("mrp/preview2/{$da->id_mrp_request}/{$da->id_mrp_task_orders}");
        $on = 'window.open(this.href,"_blank"); return false;';
        $btn_2 = "";
        if($da->status > 4){
        $btn_2 = "<a href='{$lnk}' onclick='{$on}' class='btn btn-primary btn-flat' ><i class='fa fa-print'></i></a>";
        }
        
       $note = "";
        if($da->note){
            $note = "<br>Note:".$da->note;
        }
      $hasil[] = array(
        "<a data-toggle='collapse' data-parent='#accordion'  href='#collapseTwo' onclick='coba_data2({$da->type_inventory},{$da->id_mrp_request});'>".$da->req_code."<br>Type:".$type[$da->type_inventory].$note."</a>"
                . "<script>"
            . "function coba_data2(typ_inventory,id_mrp_req){"
                . "if(typ_inventory == 1){"
                    . '$("#Atableboxy-data2").hide();'
                    . '$("#Atableboxy-data1").show();'
                    . 'var table = '
                    . '$("#tableboxy-req-data-1").dataTable({'
                    . '"order": [[ 0, "asc" ]],'
                    . '"bDestroy": true'
                    . '});'
                    . "table.fnClearTable();"
                    . 'ambil_data9(table, 0,id_mrp_req,typ_inventory);'
                . "}else{"
                    . '$("#Atableboxy-data1").hide();'
                    . '$("#Atableboxy-data2").show();'
                    . 'var table = '
                    . '$("#tableboxy-req-data-2").dataTable({'
                    . '"order": [[ 0, "asc" ]],'
                    . '"bDestroy": true'
                    . '});'
                    . "table.fnClearTable();"
                    . 'ambil_data9(table, 0,id_mrp_req,typ_inventory);'
                . "}"
                
            . "}"
        
                . 'function ambil_data9(table, mulai,id_mrp_req,type){'
                . '$.post("'.site_url("mrp/mrp-ajax/ajax-form-mrp-request-pengadaan2").'/"+type+"/"+id_mrp_req+"/"+mulai, function(data){'
                  . '$("#loader-page").show();'
                  . 'var hasil = $.parseJSON(data);'
                  . 'if(hasil.status == 2){'
                    . 'table.fnAddData(hasil.hasil);'
                    . 'ambil_data9(table, hasil.start,id_mrp_req,type);'
                  . '}'
                  . 'else{'
                    . '$("#loader-page").hide();'
                  . '}'
                . '});'
              . '}'
        . "</script>",
        "<a href='".site_url("mrp/add-request-pengadaan-{$dt_link}/{$da->id_mrp_request}")."'>"."Pegawai:".$da->nama_pegawai."<br>Perusahaan:".$da->perusahaan."<br>Department:".$da->department."</a>",
        $status[$da->status], 
        "<div class='btn-group'>"
        .$btn
        .$btn_2        
        . "</div>"
      );
    }
    $return['hasil'] = $hasil;
//    $this->debug($return, true);
    print json_encode($return);
    die;
  }
  
  function get_grouping_orders_request($id_mrp_task_orders = 0,$start = 0){
      
      $where = "WHERE A.status >= 4 AND G.id_mrp_task_orders = '$id_mrp_task_orders'";   
      
    $data = $this->global_models->get_query("SELECT A.code AS req_code,A.id_mrp_request,G.id_mrp_task_orders,B.name AS nama_pegawai,A.note,A.status,A.type_inventory "
        . ",D.code AS department,D.level,F.title AS perusahaan,A.create_by_users"
        . " FROM mrp_request AS A"
        . " LEFT JOIN m_users AS B ON A.create_by_users = B.id_users"
        . " LEFT JOIN hr_pegawai AS C ON B.id_users = C.id_users"    
        . " LEFT JOIN hr_master_organisasi AS D ON C.id_hr_master_organisasi = D.id_hr_master_organisasi"    
        . " LEFT JOIN hr_company AS F ON C.id_hr_company = F.id_hr_company"
        . " LEFT JOIN mrp_task_orders_request AS G ON A.id_mrp_request = G.id_mrp_request"    
//        . " LEFT JOIN mrp_brand AS C ON A.id_mrp_brand = C.id_mrp_brand"
//        . " LEFT JOIN mrp_qty AS D ON A.id_mrp_qty = D.id_mrp_qty"    
        . " {$where}"
        . " GROUP BY A.create_by_users ASC"
        . " LIMIT {$start}, 10");
//       print   $this->db->last_query();
//       die;
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
    $status = array( 1=> "<span class='label bg-orange'>Create</span>", 
        2 => "<span class='label bg-orange'>Draft</span>",
        3 => "<span class='label bg-green'>Approved</span>",
        4 => "<span class='label bg-green'>Task Orders</span>",
        5 => "<span class='label bg-green'>Proses PO</span>",
        6 => "<span class='label bg-green'>Sent PO</span>",
        7 => "<span class='label bg-green'>Partial</span>",
        8 => "<span class='label bg-green'>RG</span>",
        9 => "<span class='label bg-green'>Closed Request Orders</span>");
        $url = base_url()."themes/".DEFAULTTHEMES."/img/ajax-loader.gif";
//    $status = array(1 => "Create", 2 => "Approve");
        
      $organisasi =  array("1" => "Direktorat","2" => "Divisi","3" => "Department","4" => "Section");
    foreach ($data AS $da){
        
        if($da->type_inventory == 1){
            $dt_link = "cetakan";
        }else{
            $dt_link = "atk";
        }
        
        $type = array(1 => "Cetakan", 2 => "ATK");
        
        $lnk = site_url("mrp/preview-grouping/{$da->create_by_users}/{$da->id_mrp_task_orders}");
        $on = 'window.open(this.href,"_blank"); return false;';
        $btn_2 = "";
       // if($da->status > 4){
        $btn_2 = "<a href='{$lnk}' onclick='{$on}' class='btn btn-primary btn-flat' ><i class='fa fa-print'></i></a>";
       // }
        
       $note = "";
        if($da->note){
            $note = "<br>Note:".$da->note;
        }
      $hasil[] = array(
       "<a data-toggle='collapse' data-parent='#accordion'  href='#collapseThree' onclick='coba_data4({$da->type_inventory},{$da->id_mrp_task_orders},{$da->create_by_users});'>".$da->nama_pegawai."</a>"
          . "<script>"
            . "function coba_data4(typ_inventory,id_mrp_task_orders,create_by_users){"
                    . 'var table = '
                    . '$("#tableboxy-grouping-req-data").dataTable({'
                    . '"order": [[ 0, "asc" ]],'
                    . '"bDestroy": true'
                    . '});'
                    . "table.fnClearTable();"
                    . 'ambil_data10(table, 0,id_mrp_task_orders,typ_inventory,create_by_users);'
            . "}"
        
                . 'function ambil_data10(table, mulai,id_mrp_task_orders,type,create_by_users){'
                . '$.post("'.site_url("mrp/mrp-ajax/ajax-form-grouping-mrp-request-pengadaan").'/"+type+"/"+id_mrp_task_orders+"/"+create_by_users+"/"+mulai, function(data){'
                  . '$("#loader-page10").show();'
                  . 'var hasil = $.parseJSON(data);'
                  . 'if(hasil.status == 2){'
                    . 'table.fnAddData(hasil.hasil);'
                    . 'ambil_data10(table, hasil.start,id_mrp_task_orders,type,create_by_users);'
                  . '}'
                  . 'else{'
                    . '$("#loader-page10").hide();'
                  . '}'
                . '});'
              . '}'
        . "</script>",
       $da->perusahaan."<br>{$organisasi[$da->level]} :".$da->department,  
       $status[$da->status], 
        "<div class='btn-group'>"
        .$btn_2        
        . "</div>"
      );
    }
    $return['hasil'] = $hasil;
//    $this->debug($return, true);
    print json_encode($return);
    die;
  }
  
  function get_task_orders_request_asset($id_mrp_task_orders = 0,$start = 0){
      
      $where = "WHERE A.id_mrp_task_orders = '$id_mrp_task_orders'";
//       if($status == 1){
//          $where .= " AND A.status < 3 ";
//      }else{
//          $where .= " AND A.status > 2 ";
//      }
    $data = $this->global_models->get_query("SELECT A.jumlah AS jml_to,F.jumlah AS jml_po,D.code AS brand,C.name AS nama_barang"
        . ",B.title AS title_spesifik,E.title AS satuan,D.title AS brand"
        . " FROM mrp_task_orders_request_asset AS A"
        . " LEFT JOIN mrp_inventory_spesifik AS B ON A.id_mrp_inventory_spesifik = B.id_mrp_inventory_spesifik"
        . " LEFT JOIN mrp_inventory_umum AS C ON B.id_mrp_inventory_umum = C.id_mrp_inventory_umum"
        . " LEFT JOIN mrp_brand AS D ON B.id_mrp_brand = D.id_mrp_brand"
        . " LEFT JOIN mrp_satuan AS E ON B.id_mrp_satuan = E.id_mrp_satuan"
        . " LEFT JOIN mrp_po_asset AS F ON (A.id_mrp_task_orders_request_asset= F.id_mrp_task_orders_request_asset AND F.status > 2 AND F.id_mrp_supplier IS NOT NULL) "    
//        . " LEFT JOIN mrp_brand AS C ON A.id_mrp_brand = C.id_mrp_brand"
//        . " LEFT JOIN mrp_qty AS D ON A.id_mrp_qty = D.id_mrp_qty"    
        . " {$where}"
        . " ORDER BY A.id_mrp_task_orders ASC"
        . " LIMIT {$start}, 10");
//       print   $this->db->last_query();
//       die;
//    $data_array = json_decode($data);
//    $this->debug($data, true);
//     
    if(count($data) > 0){
      $return['status'] = 2;
      $return['start']  = $start + 10;
    }
    else{
      $return['status'] = 3;
    }
//    $jenis = array(1 => "Habis Pakai", 2 => "Asset");
//    $status = array( 1=> "<span class='label bg-orange'>Create</span>", 2 => "<span class='label bg-green'>Approve</span>",3 => "<span class='label bg-green'>Task Orders</span>");
//    $status = array(1 => "Create", 2 => "Approve");
    
    foreach ($data AS $da){
        if($da->jml_po){
           $jumlah_po =  $da->jml_po;
        }else{
            $jumlah_po = 0;
        }
        
        $brn = "";
        if($da->brand){
            $brn = "<br>Brand:".$da->brand;
        }
      $hasil[] = array(
        $da->nama_barang." ".$da->title_spesifik.$brn,
        $da->brand,
        $da->satuan,
        "<center>".$da->jml_to."</center>",
        "<center>".$jumlah_po."</center>"
      );
      
    }
    $return['hasil'] = $hasil;
//    $this->debug($return, true);
    print json_encode($return);
    die;
  }
  
   function delete_task_orders_request($id_mrp_task_orders = 0){
    $id_mrp_request = $_POST['id_mrp_request'];
    
            $kirim3 = array(
                "status"                        => 3,
                "update_by_users"               => $this->session->userdata("id"),
                "update_date"                   => date("Y-m-d H:i:s")
                );
        $this->global_models->update("mrp_request", array("id_mrp_request" => $id_mrp_request),$kirim3);
            
         $mrp_request_asset = $this->global_models->get("mrp_request_asset", array("id_mrp_request" => $id_mrp_request));
             foreach ($mrp_request_asset as $ky => $value) {
               $dt_jumlah = $this->global_models->get_field("mrp_task_orders_request_asset", "jumlah",array("id_mrp_task_orders" => "{$id_mrp_task_orders}","id_mrp_inventory_spesifik" => "{$value->id_mrp_inventory_spesifik}"));
               if($dt_jumlah > 0){
                   $jml =  $dt_jumlah - $value->jumlah;
                   $kirim3 = array(
                    "jumlah"                        =>  $jml, 
                    "update_by_users"               => $this->session->userdata("id"),
                    "update_date"                   => date("Y-m-d H:i:s")
                    );
           
                    $this->global_models->update("mrp_task_orders_request_asset", array("id_mrp_task_orders" => "{$id_mrp_task_orders}","id_mrp_inventory_spesifik" => "{$value->id_mrp_inventory_spesifik}"),$kirim3);
                    
                   $this->global_models->delete("mrp_task_orders_request_asset", array("id_mrp_task_orders" => $id_mrp_task_orders,"id_mrp_inventory_spesifik" => "{$value->id_mrp_inventory_spesifik}", "jumlah" => 0));
                  
                    $this->global_models->update("mrp_po_asset", array("id_mrp_task_orders" => "{$id_mrp_task_orders}","id_mrp_inventory_spesifik" => "{$value->id_mrp_inventory_spesifik}"),$kirim3);
                    
                   $this->global_models->delete("mrp_po_asset", array("id_mrp_task_orders" => $id_mrp_task_orders,"id_mrp_inventory_spesifik" => "{$value->id_mrp_inventory_spesifik}", "jumlah" => 0)); 
               }
                }
                $this->global_models->delete("mrp_task_orders_request", array("id_mrp_request" => $id_mrp_request,"id_mrp_task_orders" => $id_mrp_task_orders));
        
                $this->session->set_flashdata('success', 'Data Berhasil di Hapus');
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
  
   function get_po_request_asset($id_mrp_task_orders = 0,$start = 0,$id_mrp_supplier = 0){
//      $id_mrp_supplier = 1;
       $url = base_url()."themes/".DEFAULTTHEMES."/img/ajax-loader.gif";
      $where = "WHERE A.status = 1 AND A.id_mrp_task_orders = '$id_mrp_task_orders'";

    $data = $this->global_models->get_query("SELECT A.id_mrp_task_orders_request_asset,A.id_mrp_satuan,A.jumlah,A.note,A.id_mrp_task_orders_request_asset,A.harga AS harga_task_order_request,"
        . "B.title AS title_spesifik,C.name AS nama_barang,"
        . "D.code AS brand,"
        . "E.title AS satuan,E.group_satuan,E.nilai,"
        . "F.harga"
        . " FROM mrp_po_asset AS A"
        . " LEFT JOIN mrp_inventory_spesifik AS B ON A.id_mrp_inventory_spesifik = B.id_mrp_inventory_spesifik"
        . " LEFT JOIN mrp_inventory_umum AS C ON B.id_mrp_inventory_umum = C.id_mrp_inventory_umum"
        . " LEFT JOIN mrp_brand AS D ON B.id_mrp_brand = D.id_mrp_brand"
        . " LEFT JOIN mrp_satuan AS E ON A.id_mrp_satuan = E.id_mrp_satuan"
        . " LEFT JOIN mrp_supplier_inventory AS F ON (B.id_mrp_inventory_spesifik = F.id_mrp_inventory_spesifik) AND F.id_mrp_supplier = '{$id_mrp_supplier}'"
        . " {$where}"
        . " ORDER BY A.id_mrp_task_orders ASC"
        . " LIMIT {$start}, 1");
//       $aa = $this->db->last_query();
//        print_r($aa); die;
    if(count($data) > 0){
      $return['status'] = 2;
      $return['start']  = $start + 1;
    }
    else{
      $return['status'] = 1;
    }

    if($id_mrp_supplier > 0){
        $dt_mrp_supplier = "";
        
    }else{
        $dt_mrp_supplier = " disabled ";
        
    }
   
    foreach ($data AS $ky => $da){
        if($da->title_spesifik){
            $title_inventory = " ".$da->title_spesifik;
        }else{
            $title_inventory = "";
        }
        
        if($da->brand){
            $brand2 = " [Merk:".$da->brand."]";
        }else{
            $brand2 = "";
        }
        
                
        $dt_id_satuan = "id_satuan_{$da->id_mrp_task_orders_request_asset}";
        $dt_satuan = "satuan_{$da->id_mrp_task_orders_request_asset}";
       $dt_id_suppl = $this->global_models->get_field("mrp_task_orders_request_asset", "id_mrp_supplier",array("id_mrp_supplier" => "{$id_mrp_supplier}","id_mrp_task_orders_request_asset" => $da->id_mrp_task_orders_request_asset));
        if($id_mrp_supplier > 0){
            if($dt_id_suppl){
                $dta_hrg = $da->harga_task_order_request;
            }else{
                $dta_hrg = $da->harga;
            }
            
            $kirim[$ky] = array(
            "harga"                     => $dta_hrg,
            "update_by_users"           => $this->session->userdata("id"),
            "update_date"               => date("Y-m-d H:i:s")
            );
            $this->global_models->update("mrp_po_asset", array("id_mrp_task_orders_request_asset" => $da->id_mrp_task_orders_request_asset,"status" => "1"),$kirim[$ky]);
          
            $this->global_models->update("mrp_task_orders_request_asset", array("id_mrp_task_orders_request_asset" => $da->id_mrp_task_orders_request_asset,"status" => "1"),$kirim[$ky]);
        }

        $id_satuan = "id='id_satuan_{$da->id_mrp_task_orders_request_asset}'";
        $satuan = "id='satuan_{$da->id_mrp_task_orders_request_asset}'";
        $note = "id='note_{$da->id_mrp_task_orders_request_asset}'";
        $dt_jumlah = "id='id_jumlah_{$da->id_mrp_task_orders_request_asset}'";
        $dt_harga = "id='id_harga_{$da->id_mrp_task_orders_request_asset}'";
        $dt_total = "id='id_total_{$da->id_mrp_task_orders_request_asset}'";
        $nilai = "id='nilai_{$da->id_mrp_task_orders_request_asset}'";
        $total = (($da->nilai *$da->jumlah) * $dta_hrg);
        

        $hasil[] = array(
        $da->nama_barang.$title_inventory.$brand2.$this->form_eksternal->form_input("id_mrp_task_orders_request_asset[]", $da->id_mrp_task_orders_request_asset, ' class="form-control mrp_task_orders_request_asset input-sm" style="display: none"'),
        $this->form_eksternal->form_input("satuan[]", $da->satuan, $satuan.$dt_mrp_supplier.' class="form-control input-sm" placeholder="Satuan"').
        $this->form_eksternal->form_input("nilai[]", $da->nilai, $nilai.' style="display: none"').
        $this->form_eksternal->form_input("id_satuan[]", $da->id_mrp_satuan, $id_satuan.' style="display: none"')
            ."<script>"
//            . "alert({$dt_id_suppl});"
//            . "alert({$da->harga_task_order_request});"
//            . "alert({$da->harga});"
//            . "alert({$dta_hrg});"
           . '$(function() {'
            . "$('#del_{$da->id_mrp_task_orders_request_asset}').click(function(){"
                . "var table = $('#tableboxy2').DataTable();"
                . "$('#tableboxy2 tbody').on( 'click', 'tr', function () {"
                . "if ( $(this).hasClass('selected') ) {"
                    . "$(this).removeClass('selected');"
                . "}"
                . "else {"
                . "table.$('tr.selected').removeClass('selected');"
                    . "$(this).addClass('selected');"
                . "}"
                    . "$('#del_{$da->id_mrp_task_orders_request_asset}').click(function(){"
                        . "table.row('.selected').remove().draw( false ); "
                    . "});"
             . "});"
            . "});"
          
//                  . "alert('cvfg');"
//                        . "table.row('.selected').remove().draw( false );"
//                  . "});"
                . "$( '#{$dt_satuan}' ).autocomplete({"
                     . "source: '".site_url("mrp/mrp-ajax/get-satuan-po/{$da->group_satuan}")."',"
                     . "minLength: 1,"
                     . "search  : function(){ $(this).addClass('working');},"
                     . "open    : function(){ $(this).removeClass('working');},"
                     . "select: function( event, ui ) {"
                     . "$('#id_satuan_{$da->id_mrp_task_orders_request_asset}').val(ui.item.id);"
                            . "var dt_satuan = $('#id_satuan_{$da->id_mrp_task_orders_request_asset}').val();"
//                            . "alert(dt_satuan);"
                            . "var dt_jumlah = $('#id_jumlah_{$da->id_mrp_task_orders_request_asset}').val();"
                            ."var dataString2 = 'dsatuan='+ dt_satuan + '&jumlah=' + dt_jumlah;"
                            ."$.ajax({"
                            ."type : 'POST',"
                            ."url : '".site_url("mrp/mrp-ajax/change-satuan/{$da->id_mrp_task_orders_request_asset}")."',"
                            ."data: dataString2,"
                            ."dataType : 'html',"
                            ."success: function(data) {"
                                    . 'var hasil = $.parseJSON(data);'
                                    . "$('#id_jumlah_{$da->id_mrp_task_orders_request_asset}').val(hasil.jumlah);"
                                    . "$('#id_total_{$da->id_mrp_task_orders_request_asset}').val(parseFloat(hasil.total, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,').toString());"
                                    . "$('#nilai_{$da->id_mrp_task_orders_request_asset}').val(hasil.nilai);" 
//                                     . "alert(hasil.total);"
//                                    . "$('#script-tambahan').html(data);"
                            ."},"
                         ."});"       
                     . "}"
                   . "});"
                  . '});'
                             
                   . "$('#id_jumlah_{$da->id_mrp_task_orders_request_asset}').keyup(function(){"
                   . "var jml = $('#id_jumlah_{$da->id_mrp_task_orders_request_asset}').val().toString().replace(/\$|\,/g,'') * 1;"
                   . "var hrg = $('#id_harga_{$da->id_mrp_task_orders_request_asset}').val().toString().replace(/\$|\,/g,'') * 1;"
                   . "var dt_nilai = $('#nilai_{$da->id_mrp_task_orders_request_asset}').val();"
                   . "hrg = hrg ? hrg : 0;"
                   . "jml = jml ? jml : 0;"
                   . "penjumlahan_{$da->id_mrp_task_orders_request_asset}(jml,hrg,dt_nilai);"
                    ."var dataString2 = 'jumlah='+ jml +'&harga='+ hrg;"
                        ."$.ajax({"
                        ."type : 'POST',"
                        ."url : '".site_url("mrp/mrp-ajax/update-po-task-orders-request/{$da->id_mrp_task_orders_request_asset}")."',"
                        ."data: dataString2,"
                        ."dataType : 'html',"
                        ."success: function(data) {"
//                                . "alert('aa');"
//                        . "$('#script-tambahan').html(data);"

                        ."},"
                     ."});"    
                   . "});"
                           
                   . "$('#id_harga_{$da->id_mrp_task_orders_request_asset}').keyup(function(){"
                   . "var jml = $('#id_jumlah_{$da->id_mrp_task_orders_request_asset}').val().toString().replace(/\$|\,/g,'') * 1;"
                   . "var hrg = $('#id_harga_{$da->id_mrp_task_orders_request_asset}').val().toString().replace(/\$|\,/g,'') * 1;"
                   . "var dt_nilai = $('#nilai_{$da->id_mrp_task_orders_request_asset}').val();"
                   . "hrg = hrg ? hrg : 0;"
                   . "jml = jml ? jml : 0;"
                   . "penjumlahan_{$da->id_mrp_task_orders_request_asset}(jml,hrg,dt_nilai);"
                   ."var dataString2 = 'jumlah='+ jml +'&harga='+ hrg;"
                        ."$.ajax({"
                        ."type : 'POST',"
                        ."url : '".site_url("mrp/mrp-ajax/update-po-task-orders-request/{$da->id_mrp_task_orders_request_asset}")."',"
                        ."data: dataString2,"
                        ."dataType : 'html',"
                        ."success: function(data) {"
//                                . "alert('aa');"
//                        . "$('#script-tambahan').html(data);"
                        ."},"
                     ."});"    
                   . "});"
                   
                   . "$('#note_{$da->id_mrp_task_orders_request_asset}').keyup(function(){"
                   . "var dt_note = $('#note_{$da->id_mrp_task_orders_request_asset}').val();"
                   . "var dataString2 = 'note='+ dt_note;"
                   ."$.ajax({"
                        ."type : 'POST',"
                        ."url : '".site_url("mrp/mrp-ajax/update-keterangan-po-task-orders-request/{$da->id_mrp_task_orders_request_asset}")."',"
                        ."data: dataString2,"
                        ."dataType : 'html',"
                        ."success: function(data) {"
                        ."},"
                     ."});"            
                   . "});"
                       
                
                . "function penjumlahan_{$da->id_mrp_task_orders_request_asset}(jumlah,harga,nilai){"
                     . "var total = ((nilai*jumlah) * harga);"
                     . "$('#id_total_{$da->id_mrp_task_orders_request_asset}').val(parseFloat(total, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,').toString());"
                . "}"
            . "</script>"
            ,
        $this->form_eksternal->form_input("jumlah[]", $da->jumlah, $dt_jumlah.$dt_mrp_supplier.' id="satuan" class="form-control input-sm" placeholder="Satuan"'),
        $this->form_eksternal->form_input("harga[]", number_format($dta_hrg), $dt_harga.$dt_mrp_supplier.' onkeyup="FormatCurrency(this)"  style="width:160px" class="form-control jumlah input-sm" placeholder="Harga"'),
        $this->form_eksternal->form_input("total[]", number_format($total), $data_id.$dt_total.' disabled style="width:230px;" class="form-control id_spesifik input-sm" placeholder=""')
        ,$this->form_eksternal->form_textarea('note[]', $da->note, $note.' style="height: 50px;" class="form-control input-sm"')
        ,"<div class='btn-group'>"
          . "<a href='javascript:void(0)' onclick='delete_po_task_order_request({$da->id_mrp_task_orders_request_asset})' id='del_{$da->id_mrp_task_orders_request_asset}' class='btn btn-danger btn-flat' style='width: 40px'>x</a>"
          . "<span style='display: none; margin-left: 10px;' id='img-page-{$da->id_mrp_task_orders_request_asset}'><img width='35px' src='{$url}' /></span>"
          . "</div>"
//          ."<script>"
//                  
//          . "</script>"
      );
           
          if($id_mrp_supplier > 0){
            $kirim = array(
            "id_mrp_supplier"           => $id_mrp_supplier,
            "update_by_users"           => $this->session->userdata("id"),
            "update_date"               => date("Y-m-d H:i:s")
            );
            $this->global_models->update("mrp_po_asset", array("id_mrp_task_orders_request_asset" => $da->id_mrp_task_orders_request_asset,"status" => "1"),$kirim);
           
            $this->global_models->update("mrp_task_orders_request_asset", array("id_mrp_task_orders_request_asset" => $da->id_mrp_task_orders_request_asset,"status" => "1"),$kirim);
          }
    }
    
   
    $return['hasil'] = $hasil;
    $return['dt_total'] = $dt_total;
//    $this->debug($return, true);
    print json_encode($return);
    die;
  }
  
  function get_update_po_request_asset($id_mrp_task_orders = 0,$id_mrp_po = 0,$start = 0,$id_mrp_supplier = 0){
//      $id_mrp_supplier = 1;
       $url = base_url()."themes/".DEFAULTTHEMES."/img/ajax-loader.gif";
      $where = "WHERE (A.status = 3 OR A.status = 8) AND A.id_mrp_task_orders = '$id_mrp_task_orders' AND A.id_mrp_po = '$id_mrp_po' ";

    $data = $this->global_models->get_query("SELECT A.id_mrp_task_orders_request_asset,A.jumlah,A.note,A.id_mrp_task_orders_request_asset,A.harga AS harga_task_order_request,"
        . "B.title AS title_spesifik,C.name AS nama_barang,"
        . "D.code AS brand,"
        . "E.title AS satuan,E.group_satuan,E.nilai,"
        . "F.harga"
        . " FROM mrp_po_asset AS A"
        . " LEFT JOIN mrp_inventory_spesifik AS B ON A.id_mrp_inventory_spesifik = B.id_mrp_inventory_spesifik"
        . " LEFT JOIN mrp_inventory_umum AS C ON B.id_mrp_inventory_umum = C.id_mrp_inventory_umum"
        . " LEFT JOIN mrp_brand AS D ON B.id_mrp_brand = D.id_mrp_brand"
        . " LEFT JOIN mrp_satuan AS E ON A.id_mrp_satuan = E.id_mrp_satuan"
        . " LEFT JOIN mrp_supplier_inventory AS F ON (B.id_mrp_inventory_spesifik = F.id_mrp_inventory_spesifik) AND F.id_mrp_supplier = '{$id_mrp_supplier}'"
        . " {$where}"
        . " ORDER BY A.id_mrp_task_orders ASC"
        . " LIMIT {$start}, 1");

    if(count($data) > 0){
      $return['status'] = 2;
      $return['start']  = $start + 1;
    }
    else{
      $return['status'] = 1;
    }

    if($id_mrp_supplier > 0){
        $dt_mrp_supplier = "";
        
    }else{
        $dt_mrp_supplier = " disabled "; 
    }
    foreach ($data AS $ky => $da){
       
        $dt_id_satuan = "id_satuan_{$da->id_mrp_task_orders_request_asset}";
        $dt_satuan = "satuan_{$da->id_mrp_task_orders_request_asset}";
       $dt_id_suppl = $this->global_models->get_field("mrp_task_orders_request_asset", "id_mrp_supplier",array("id_mrp_supplier" => "{$id_mrp_supplier}","id_mrp_task_orders_request_asset" => $da->id_mrp_task_orders_request_asset));
        if($id_mrp_supplier > 0){
            if($dt_id_suppl > 0){
                $dta_hrg = $da->harga_task_order_request;
                
            }else{
                $dta_hrg = $da->harga;
            }
             $kirim[$ky] = array(
            "harga"                     => $dta_hrg,
            "update_by_users"           => $this->session->userdata("id"),
            "update_date"               => date("Y-m-d H:i:s")
        );
        $this->global_models->update("mrp_task_orders_request_asset", array("id_mrp_task_orders_request_asset" => $da->id_mrp_task_orders_request_asset),$kirim[$ky]);
         
        }
//        die;
//        if($dta_hrg){
          //  $harga = $dta_hrg;
//        }else{
//            $harga = 0;
//        }
        
        $id_satuan = "id='id_satuan_{$da->id_mrp_task_orders_request_asset}'";
        $satuan = "id='satuan_{$da->id_mrp_task_orders_request_asset}'";
        $note = "id='note_{$da->id_mrp_task_orders_request_asset}'";
        $dt_jumlah = "id='id_jumlah_{$da->id_mrp_task_orders_request_asset}'";
        $dt_harga = "id='id_harga_{$da->id_mrp_task_orders_request_asset}'";
        $dt_total = "id='id_total_{$da->id_mrp_task_orders_request_asset}'";
        $nilai = "id='nilai_{$da->id_mrp_task_orders_request_asset}'";
        $total = (($da->nilai *$da->jumlah) * $dta_hrg);
        
//        $dt_total += $total;
        if($da->title_spesifik){
            $title_spesifik = " ".$da->title_spesifik;
        }else{
            $title_spesifik = "";
        }
        
        if($da->brand){
            $brand = " [".$da->brand."]";
        }else{
            $brand = "";
        }
        
        $hasil[] = array(
        $da->nama_barang.$title_spesifik.$brand.$this->form_eksternal->form_input("id_mrp_task_orders_request_asset[]", $da->id_mrp_task_orders_request_asset, ' class="form-control mrp_task_orders_request_asset input-sm" style="display: none"'),
        $this->form_eksternal->form_input("satuan[]", $da->satuan, $satuan.$dt_mrp_supplier.' class="form-control input-sm" placeholder="Satuan"').
        $this->form_eksternal->form_input("nilai[]", $da->nilai, $nilai.' style="display: none"').
        $this->form_eksternal->form_input("id_satuan[]", $da->id_mrp_satuan, $id_satuan.' style="display: none"')
            ."<script>"
                . '$(function() {'
//                . "var table = $('#tableboxy').DataTable();"
//            . "$('#tableboxy tbody').on( 'click', 'tr', function () {"
//                . "if ( $(this).hasClass('selected') ) {"
//                . "$(this).removeClass('selected');"
//            . "}"
//            . "else {"
//                . "table.$('tr.selected').removeClass('selected');"
//                . "$(this).addClass('selected');"
//                . "}"
//                . "table.row('.selected').remove().draw( false ); "
//             . "});"
            
            . "$('#del2_{$da->id_mrp_task_orders_request_asset}').click(function(){"
                . "var table = $('#tableboxy').DataTable();"
                . "$('#tableboxy tbody').on( 'click', 'tr', function () {"
                . "if ( $(this).hasClass('selected') ) {"
                    . "$(this).removeClass('selected');"
                . "}"
                . "else {"
                . "table.$('tr.selected').removeClass('selected');"
                    . "$(this).addClass('selected');"
                . "}"
                    . "$('#del2_{$da->id_mrp_task_orders_request_asset}').click(function(){"
                        . "table.row('.selected').remove().draw( false ); "
                    . "});"
             . "});"
            . "});"
            
                . "$( '#{$dt_satuan}' ).autocomplete({"
                     . "source: '".site_url("mrp/mrp-ajax/get-satuan-po/{$da->group_satuan}")."',"
                     . "minLength: 1,"
                     . "search  : function(){ $(this).addClass('working');},"
                     . "open    : function(){ $(this).removeClass('working');},"
                     . "select: function( event, ui ) {"
                     . "$('#id_satuan_{$da->id_mrp_task_orders_request_asset}').val(ui.item.id);"
                            . "var dt_satuan = $('#id_satuan_{$da->id_mrp_task_orders_request_asset}').val();"
//                            . "alert(dt_satuan);"
                            . "var dt_jumlah = $('#id_jumlah_{$da->id_mrp_task_orders_request_asset}').val();"
                            ."var dataString2 = 'dsatuan='+ dt_satuan + '&jumlah=' + dt_jumlah;"
                            ."$.ajax({"
                            ."type : 'POST',"
                            ."url : '".site_url("mrp/mrp-ajax/change-satuan/{$da->id_mrp_task_orders_request_asset}")."',"
                            ."data: dataString2,"
                            ."dataType : 'html',"
                            ."success: function(data) {"
                                    . 'var hasil = $.parseJSON(data);'
                                    . "$('#id_jumlah_{$da->id_mrp_task_orders_request_asset}').val(hasil.jumlah);"
                                    . "$('#id_total_{$da->id_mrp_task_orders_request_asset}').val(parseFloat(hasil.total, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,').toString());"
                                    . "$('#nilai_{$da->id_mrp_task_orders_request_asset}').val(hasil.nilai);" 
//                                     . "alert(hasil.total);"
//                                    . "$('#script-tambahan').html(data);"
                            ."},"
                         ."});"       
                     . "}"
                   . "});"
                  . '});'
                             
                   . "$('#id_jumlah_{$da->id_mrp_task_orders_request_asset}').keyup(function(){"
                   . "var jml = $('#id_jumlah_{$da->id_mrp_task_orders_request_asset}').val().toString().replace(/\$|\,/g,'') * 1;"
                   . "var hrg = $('#id_harga_{$da->id_mrp_task_orders_request_asset}').val().toString().replace(/\$|\,/g,'') * 1;"
                   . "var dt_nilai = $('#nilai_{$da->id_mrp_task_orders_request_asset}').val();" 
                   . "hrg = hrg ? hrg : 0;"
                   . "jml = jml ? jml : 0;"
                   . "penjumlahan_{$da->id_mrp_task_orders_request_asset}(jml,hrg,dt_nilai);"
                    ."var dataString2 = 'jumlah='+ jml +'&harga='+ hrg;"
                        ."$.ajax({"
                        ."type : 'POST',"
                        ."url : '".site_url("mrp/mrp-ajax/update-po-task-orders-request/{$da->id_mrp_task_orders_request_asset}")."',"
                        ."data: dataString2,"
                        ."dataType : 'html',"
                        ."success: function(data) {"
//                                . "alert('aa');"
//                        . "$('#script-tambahan').html(data);"

                        ."},"
                     ."});"    
                   . "});"
                           
                   . "$('#id_harga_{$da->id_mrp_task_orders_request_asset}').keyup(function(){"
                   . "var jml = $('#id_jumlah_{$da->id_mrp_task_orders_request_asset}').val().toString().replace(/\$|\,/g,'') * 1;"
                   . "var hrg = $('#id_harga_{$da->id_mrp_task_orders_request_asset}').val().toString().replace(/\$|\,/g,'') * 1;"
                   . "var dt_nilai = $('#nilai_{$da->id_mrp_task_orders_request_asset}').val();" 
                   . "hrg = hrg ? hrg : 0;"
                   . "jml = jml ? jml : 0;"
                   . "penjumlahan_{$da->id_mrp_task_orders_request_asset}(jml,hrg,dt_nilai);"
                   ."var dataString2 = 'jumlah='+ jml +'&harga='+ hrg;"
                        ."$.ajax({"
                        ."type : 'POST',"
                        ."url : '".site_url("mrp/mrp-ajax/update-po-task-orders-request/{$da->id_mrp_task_orders_request_asset}")."',"
                        ."data: dataString2,"
                        ."dataType : 'html',"
                        ."success: function(data) {"
//                                . "alert('aa');"
//                        . "$('#script-tambahan').html(data);"

                        ."},"
                     ."});"    
                   . "});"
                                
                   . "$('#note_{$da->id_mrp_task_orders_request_asset}').keyup(function(){"
                   . "var dt_note = $('#note_{$da->id_mrp_task_orders_request_asset}').val();"
                   . "var dataString2 = 'note='+ dt_note;"
                   ."$.ajax({"
                        ."type : 'POST',"
                        ."url : '".site_url("mrp/mrp-ajax/update-keterangan-po-task-orders-request/{$da->id_mrp_task_orders_request_asset}")."',"
                        ."data: dataString2,"
                        ."dataType : 'html',"
                        ."success: function(data) {"
//                                . "alert('aa');"
                        ."},"
                     ."});"            
                   . "});"       
                
//                . "$('#dt_total').text({$dt_total});"
                
                . "function penjumlahan_{$da->id_mrp_task_orders_request_asset}(jumlah,harga,nilai){"
                     . "var total = ((nilai * jumlah) * harga);"
                     . "$('#id_total_{$da->id_mrp_task_orders_request_asset}').val(parseFloat(total, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,').toString());"
                . "}"
            . "</script>"
            ,
        $this->form_eksternal->form_input("jumlah[]", $da->jumlah, $dt_jumlah.$dt_mrp_supplier.' id="satuan" class="form-control input-sm" placeholder="Satuan"'),
        $this->form_eksternal->form_input("harga[]", number_format($dta_hrg), $dt_harga.$dt_mrp_supplier.' onkeyup="FormatCurrency(this)"  style="width:160px" class="form-control jumlah input-sm" placeholder="Harga"'),
        $this->form_eksternal->form_input("total[]", number_format($total), $data_id.$dt_total.' disabled style="width:230px;" class="form-control id_spesifik input-sm" placeholder=""')
        ,$this->form_eksternal->form_textarea('note[]', $da->note, $note.'style="height: 50px;" class="form-control input-sm"')
        ,"<div class='btn-group'>"
          . "<a href='javascript:void(0)' onclick='delete_po_task_order_request2({$da->id_mrp_task_orders_request_asset})' id='del2_{$da->id_mrp_task_orders_request_asset}' class='btn btn-danger btn-flat' style='width: 40px'>x</a>"
          . "<span style='display: none; margin-left: 10px;' id='img-page2-{$da->id_mrp_task_orders_request_asset}'><img width='35px' src='{$url}' /></span>"
          . "</div>"  
      );
          
          if($id_mrp_supplier > 0){
            $kirim = array(
            "id_mrp_supplier"           => $id_mrp_supplier,
            "update_by_users"           => $this->session->userdata("id"),
            "update_date"               => date("Y-m-d H:i:s")
            );
            $this->global_models->update("mrp_task_orders_request_asset", array("id_mrp_task_orders_request_asset" => $da->id_mrp_task_orders_request_asset),$kirim);
      
           }
    }
    
   
    $return['hasil'] = $hasil;
    $return['dt_total'] = $dt_total;
//    $this->debug($return, true);
    print json_encode($return);
    die;
  }
  
  function get_po_inventory_spesifik(){
    if (empty($_GET['term'])) exit ;
    $q = strtolower($_GET["term"]);
    if (get_magic_quotes_gpc()) $q = stripslashes($q);
    $items = $this->global_models->get_query("
      SELECT A.id_mrp_inventory_spesifik,A.jenis,B.name,C.title AS brand,E.code AS type,D.title AS satuan
      ,D.id_mrp_satuan
      FROM mrp_inventory_spesifik AS A
      LEFT JOIN mrp_inventory_umum AS B ON A.id_mrp_inventory_umum = B.id_mrp_inventory_umum
      LEFT JOIN mrp_brand AS C ON A.id_mrp_brand = C.id_mrp_brand
      LEFT JOIN mrp_satuan AS D ON A.id_mrp_satuan = D.id_mrp_satuan
      LEFT JOIN mrp_type_inventory AS E ON B.id_mrp_type_inventory = E.id_mrp_type_inventory
      WHERE 
      A.status ='1' AND LOWER(B.name) LIKE '%{$q}%'
      LIMIT 0,10
      ");
      
      $jenis = array("1" => "Habis Pakai", "2" => "Asset");
      
    if(count($items) > 0){
      foreach($items as $tms){
         
          
        $result[] = array(
            "id"            => $tms->id_mrp_inventory_spesifik,
            "id_satuan"     => $tms->id_mrp_satuan,
            "label"         => $tms->name." <Jenis Barang:".$jenis[$tms->jenis].">"." <Type:".$tms->type."> <Brand:".$tms->brand."> <Satuan:".$tms->satuan.">",
            "value"         => $tms->name." <Jenis Barang:".$jenis[$tms->jenis].">"." <Type:".$tms->type."> <Brand:".$tms->brand."> <Satuan:".$tms->satuan.">",
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

function update_po_task_orders_request($id_mrp_task_orders_request_asset = 0){
    $jumlah = $_POST['jumlah'];
    $harga = $_POST['harga'];

    $kirim3 = array(
        "jumlah"                        => $jumlah,
        "harga"                         => $harga,
        "update_by_users"               => $this->session->userdata("id"),
        "update_date"                   => date("Y-m-d H:i:s")
        );
    $this->global_models->update("mrp_po_asset", array("id_mrp_task_orders_request_asset" => $id_mrp_task_orders_request_asset),$kirim3);

//    $this->session->set_flashdata('success', 'Data Berhasil di Hapus');
    die;

}

function update_keterangan_po_task_orders_request($id_mrp_task_orders_request_asset = 0){
    $note = $_POST['note'];
    
    $kirim3 = array(
        "note"                          => $note,
        "update_by_users"               => $this->session->userdata("id"),
        "update_date"                   => date("Y-m-d H:i:s")
        );
    $this->global_models->update("mrp_po_asset", array("id_mrp_task_orders_request_asset" => $id_mrp_task_orders_request_asset),$kirim3);

    die;
}

function delete_po_task_orders_request(){
    $id_mrp_task_orders_request_asset = $_POST['id_mrp_task_orders_request_asset'];
    
            $kirim3 = array(
                "status"                        => 2,
                "update_by_users"               => $this->session->userdata("id"),
                "update_date"                   => date("Y-m-d H:i:s")
                );
        $this->global_models->update("mrp_po_asset", array("id_mrp_task_orders_request_asset" => $id_mrp_task_orders_request_asset),$kirim3);
            
        die;
           
   }
   
   function delete_po_task_orders_request2(){
    $id_mrp_task_orders_request_asset = $_POST['id_mrp_task_orders_request_asset'];
    
            $kirim3 = array(
                "status"                        => 1,
                "update_by_users"               => $this->session->userdata("id"),
                "update_date"                   => date("Y-m-d H:i:s")
                );
        $this->global_models->update("mrp_po_asset", array("id_mrp_task_orders_request_asset" => $id_mrp_task_orders_request_asset),$kirim3);
            
        die;
           
   }
   
   function delete_request_pengadaan(){
       $id_mrp_request_asset = $_POST['id_mrp_request_asset'];
    $this->global_models->delete("mrp_request_asset", array("id_mrp_request_asset" => $id_mrp_request_asset));
    die;
  }
   
function insert_mrp_po(){
  $id_mrp_task_orders_request_asset = $_POST['id_mrp_task_orders_request_asset'];
  $status =  $_POST['status'];
  $id_supplier =  $_POST['id_supplier'];
  $id_company =  $_POST['id_company'];
 
  if($id_mrp_task_orders_request_asset){
    $arr_id = explode(",",$id_mrp_task_orders_request_asset);
    $dt_status = array("4" => "Pengajuan PO", "3" => "Draft");
    
    $id_mrp_task_orders = $this->global_models->get_field("mrp_task_orders_request_asset","id_mrp_task_orders", array("id_mrp_task_orders_request_asset" => $arr_id[0]));
    
//    print $id_mrp_task_orders."<br>";
//    print $status;
//    die;
    if($status == 4){
        $kirim = array(
        "status"                        => 2,
        "update_by_users"               => $this->session->userdata("id"),
        "update_date"                   => date("Y-m-d H:i:s")
    );
    
    $this->global_models->update("mrp_task_orders", array("id_mrp_task_orders" => $id_mrp_task_orders),$kirim);
   
    $mrp_task_orders_request = $this->global_models->get("mrp_task_orders_request", array("id_mrp_task_orders" => $id_mrp_task_orders));
    
        foreach ($mrp_task_orders_request as $vl) {
             $kirim = array(
                "status"                        => 5,
                "update_by_users"               => $this->session->userdata("id"),
                "update_date"                   => date("Y-m-d H:i:s")
            );
    
        $this->global_models->update("mrp_request", array("id_mrp_request" => $vl->id_mrp_request),$kirim);
   
        }
    
    }
    
    $this->olah_purchase_order_code($kode);
    $kirim = array(
        "id_mrp_supplier"               => $id_supplier,
        "id_hr_company"                 => $id_company,
        "code"                          => $kode,
        "status"                        => $status,
        "create_by_users"               => $this->session->userdata("id"),
        "create_date"                   => date("Y-m-d H:i:s")
    );
   $id_mrp_po = $this->global_models->insert("mrp_po", $kirim);
            
    foreach ($arr_id as $ky => $val) {
    $kirim = array(
        "status"                        => $status,
        "id_mrp_po"                     => $id_mrp_po,
        "update_by_users"               => $this->session->userdata("id"),
        "update_date"                   => date("Y-m-d H:i:s")
    );
    
    $this->global_models->update("mrp_po_asset", array("id_mrp_task_orders_request_asset" => $val),$kirim);
    }
    $this->session->set_flashdata('success', 'Data Berhasi di Proses ke '.$dt_status[$status]);
  }else{
       $this->session->set_flashdata('notice', 'Data tidak tersimpan');
  }
 
 die;

}

function update_detail_po($id_mrp_task_orders = 0,$id_mrp_po = 0){
 
  $status =  $_POST['status'];
  $tanggal =  $_POST['tanggal'];
  $note =  $_POST['note'];
  $tanggal_po = $_POST['tanggal_po'];
  
  if($tanggal_po == "0000-00-00" OR $tanggal_po == ""){
      $tgl_po = date("Y-m-d");
    }else{
      $tgl_po = $tanggal_po;
  }
  
  if($status){
      $kirim2 = array(
        "status"                        => $status,
        "update_by_users"               => $this->session->userdata("id"),
        "update_date"                   => date("Y-m-d H:i:s")
    );
    
    $this->global_models->update("mrp_po", array("id_mrp_po" => $id_mrp_po),$kirim2);
   
    $kirim = array(
        "status"                        => $status,
        "update_by_users"               => $this->session->userdata("id"),
        "update_date"                   => date("Y-m-d H:i:s")
    );
    
    $this->global_models->update("mrp_task_orders_request_asset", array("id_mrp_task_orders" => $id_mrp_task_orders, "id_mrp_po" => $id_mrp_po),$kirim);
 
    $this->session->set_flashdata('success', 'Data Berhasi di Proses ke '.$dt_status[$status]);
  }elseif($tanggal !="" OR $note != "" OR $tgl_po != ""){
      $kirim2 = array(
        "tanggal_dikirim"               => $tanggal,
        "tanggal_po"                    => $tgl_po, 
        "note"                          => $note,  
        "update_by_users"               => $this->session->userdata("id"),
        "update_date"                   => date("Y-m-d H:i:s")
    );
    
    $this->global_models->update("mrp_po", array("id_mrp_po" => $id_mrp_po),$kirim2);
  }
    

 die;
}

function update_mrp_po($id_mrp_po = 0){
  $id_mrp_task_orders_request_asset = $_POST['id_mrp_task_orders_request_asset'];
  $status =  $_POST['status'];
  $id_supplier  =  $_POST['id_supplier'];
  $id_company   =  $_POST['id_company'];
  
  if($id_company > 0){
      $arr_id = explode(",",$id_mrp_task_orders_request_asset);
 $dt_status = array("5" => "Approve PO","4" => "Pengajuan PO", "3" => "Draft");

    $kirim2 = array(
        "id_mrp_supplier"               => $id_supplier,
        "id_hr_company"                 => $id_company,
        "status"                        => $status,
//        "no_po"                         => $no_po,
        "update_by_users"               => $this->session->userdata("id"),
        "update_date"                   => date("Y-m-d H:i:s")
    );
    $this->global_models->update("mrp_po", array("id_mrp_po" => $id_mrp_po),$kirim2);
       
 foreach ($arr_id as $ky => $val) {
    $kirim = array(
        "status"                         => $status,
        "id_mrp_supplier"               => $id_supplier,
        "id_mrp_po"                      => $id_mrp_po,
        "update_by_users"               => $this->session->userdata("id"),
        "update_date"                   => date("Y-m-d H:i:s")
    );
    
    $this->global_models->update("mrp_po_asset", array("id_mrp_task_orders_request_asset" => $val),$kirim);
 }
 $this->session->set_flashdata('success', 'Data Berhasi di Proses ke '.$dt_status[$status]);
  }else{
  $this->session->set_flashdata('notice', 'Data tidak tersimpan');
  }
 
 die;

}

function update_rg($id_mrp_receiving_goods_po = 0){
  $pst = $_REQUEST;
  $id_mrp_receiving_goods_po_asset = $pst['id_mrp_receiving_goods_po_asset'];
  $rg =  $pst['rg'];
 
  $tgl_diterima =  $pst['tgl_diterima'];
  $note =  $pst['note'];
  
//  if($id_company > 0){
      $arr_id = explode(",",$id_mrp_receiving_goods_po_asset);
      $arr_rg = explode(",",$rg);
      
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
   $flag_rg  = 0;
 foreach ($arr_id as $ky => $val) {
    $dt_rg = $this->global_models->get_field("mrp_receiving_goods_po_asset", "rg",array("id_mrp_receiving_goods_po_asset" => "{$val}"));
    $dt_jml = $this->global_models->get_field("mrp_receiving_goods_po_asset", "jumlah",array("id_mrp_receiving_goods_po_asset" => "{$val}"));
     
    //cek field rg, 
     if($dt_rg){
         $dt_rg = $dt_rg;
     }else{
         $dt_rg = 0;
     }
     
     $fix = $arr_rg[$ky] + $dt_rg;
     
     if($dt_jml > $fix){
         $fix_rg = $fix;
         $flag_rg = $flag_rg + 1;
     }else{
         $fix_rg = $dt_jml;
     }
     
   $get_task_order_asset[$ky] = $this->global_models->get("mrp_receiving_goods_po_asset",array("id_mrp_receiving_goods_po_asset" => "{$val}"));
   
   $jmlh_now = $dt_jml - $fix;
   if($jmlh_now >= 0){
       
     if($arr_rg[$ky] > 0){
           $kirim = array(
        "rg"                            => $fix_rg,
        "update_by_users"               => $this->session->userdata("id"),
        "update_date"                   => date("Y-m-d H:i:s")
        );
        $this->global_models->update("mrp_receiving_goods_po_asset", array("id_mrp_receiving_goods_po_asset" => $val),$kirim);

        $dt_mrp_stock = $this->global_models->get("mrp_stock",array("id_mrp_inventory_spesifik" => "{$get_task_order_asset[$ky][0]->id_mrp_inventory_spesifik}"));
        $kirim = array(
            "id_mrp_receiving_goods"            => $id_rg,   
            "id_mrp_stock"                      => $dt_mrp_stock[0]->id_mrp_stock,
            "id_mrp_satuan"                     => $get_task_order_asset[$ky][0]->id_mrp_satuan,
            "jumlah"                            => $arr_rg[$ky],
            "id_mrp_inventory_spesifik"         => $get_task_order_asset[$ky][0]->id_mrp_inventory_spesifik,
            "harga"                             => $get_task_order_asset[$ky][0]->harga,
            "status"                            => 1,
            "create_by_users"                   => $this->session->userdata("id"),
            "create_date"                       => date("Y-m-d H:i:s")
          );
        $dt_id_stock[$ky] = $this->global_models->insert("mrp_receiving_goods_detail", $kirim);
    
        
    if($dt_mrp_stock[0]->id_mrp_inventory_spesifik > 0){
        
        $dtstock_in             = $dt_mrp_stock[0]->stock_in + $arr_rg[$ky];
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
        "id_mrp_receiving_goods_detail"         => $dt_id_stock[$ky],
        "id_mrp_inventory_spesifik"             => $dt_mrp_stock[0]->id_mrp_inventory_spesifik,
        "id_mrp_satuan"                         => $dt_mrp_stock[0]->id_mrp_satuan,    
        "id_mrp_supplier"                       => $get_task_order_asset[$ky][0]->id_mrp_supplier,
        "jumlah"                                => $arr_rg[$ky],  
        "tanggal_diterima"                      => $tgl_diterima, 
        "harga"                                 => $get_task_order_asset[$ky][0]->harga,
        "status"                                => 1,
        "create_by_users"                       => $this->session->userdata("id"),
        "create_date"                           => date("Y-m-d H:i:s")
      );
     $id_mrp_stock_in = $this->global_models->insert("mrp_stock_in", $kirim);
     
    }else{
      $kirim = array( 
        "id_mrp_inventory_spesifik"         => $get_task_order_asset[$ky][0]->id_mrp_inventory_spesifik,
        "id_mrp_satuan"                     => $get_task_order_asset[$ky][0]->id_mrp_satuan,   
        "stock_in"                          => $arr_rg[$ky],
        "status"                            => 1,
        "create_by_users"                   => $this->session->userdata("id"),
        "create_date"                       => date("Y-m-d H:i:s")
      );
     $id_stock[$ky] = $this->global_models->insert("mrp_stock", $kirim);
     
     $this->olah_stock_in_code($kode);
     $kirim = array(
        "id_mrp_stock"                          => $id_stock[$ky],
        "kode"                                  => $kode,  
        "id_mrp_receiving_goods_detail"         => $dt_id_stock[$ky],
        "id_mrp_inventory_spesifik"             => $get_task_order_asset[$ky][0]->id_mrp_inventory_spesifik,
        "id_mrp_satuan"                         => $get_task_order_asset[$ky][0]->id_mrp_satuan,    
        "id_mrp_supplier"                       => $get_task_order_asset[$ky][0]->id_mrp_supplier,
        "jumlah"                                => $arr_rg[$ky], 
        "tanggal_diterima"                      => $tgl_diterima, 
        "harga"                                 => $get_task_order_asset[$ky][0]->harga,
        "status"                                => 1,
        "create_by_users"                       => $this->session->userdata("id"),
        "create_date"                           => date("Y-m-d H:i:s")
      );
     $id_mrp_stock_in = $this->global_models->insert("mrp_stock_in", $kirim);
     
    }
    $this->load->model('mrp/mmrp');
    $this->mmrp->mutasi_rg($id_mrp_stock_in,$get_task_order_asset[$ky][0]->id_mrp_receiving_goods_po,$get_task_order_asset[$ky][0]->id_mrp_inventory_spesifik,0,$id_rg_department,$tgl_diterima,$note);
    
     }
        
     if($flag_rg > 0){
//            print $flag_rg."test1<br>";
                $krm2 = array(
                "status"                            => 7,
                "update_by_users"                   => $this->session->userdata("id"),
                "update_date"                       => date("Y-m-d H:i:s")
            );
            $this->global_models->update("mrp_receiving_goods_po", array("id_mrp_receiving_goods_po" => $id_mrp_receiving_goods_po),$krm2);
            
        }else{
//            print $flag_rg."test2<br>";
               $krm2 = array(
                "status"                            => 8,
                "update_by_users"                   => $this->session->userdata("id"),
                "update_date"                       => date("Y-m-d H:i:s")
            );
            $this->global_models->update("mrp_receiving_goods_po", array("id_mrp_receiving_goods_po" => $id_mrp_receiving_goods_po),$krm2);
             
        }
    
    $this->session->set_flashdata('success', 'Data Berhasil di Proses');
   }else{
    $this->session->set_flashdata('notice', 'Data tidak tersimpan');
   }
   
     }
     $return['status'] = 1;
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

function update_rg_department($id_mrp_receiving_goods_po = 0){
    $pst = $_REQUEST;
  $id_mrp_receiving_goods_po_asset = $pst['id_mrp_receiving_goods_po_asset'];
  $rg =  $pst['rg'];
  $id_mrp_request = $pst['id_mrp_request'];
  $tgl_diterima =   $pst['tgl_diterima'];
  $note =  $pst['note'];
  
//  if($id_company > 0){
      $arr_id = explode(",",$id_mrp_receiving_goods_po_asset);
      $arr_rg = explode(",",$rg);
      $arr_id_mrp_request = explode(",",$id_mrp_request);
      
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
   $id_rg_department = $this->global_models->insert("mrp_receiving_goods_department", $kirim);
   $flag_rg  = 0;
 foreach ($arr_id as $ky => $val) {
     $dt_rg = $this->global_models->get_field("mrp_receiving_goods_po_asset", "rg",array("id_mrp_receiving_goods_po_asset" => "{$val}"));
    $dt_jml = $this->global_models->get_field("mrp_receiving_goods_po_asset", "jumlah",array("id_mrp_receiving_goods_po_asset" => "{$val}"));
     
    //cek field rg, 
     if($dt_rg){
         $dt_rg = $dt_rg;
     }else{
         $dt_rg = 0;
     }
     
     $fix = $arr_rg[$ky] + $dt_rg;
     
     if($dt_jml > $fix){
         $fix_rg = $fix;
         $flag_rg = $flag_rg + 1;
     }else{
         $fix_rg = $dt_jml;
     }
     
   $get_task_order_asset[$ky] = $this->global_models->get("mrp_receiving_goods_po_asset",array("id_mrp_receiving_goods_po_asset" => "{$val}"));
   
   $jmlh_now = $dt_jml - $fix;
   if($jmlh_now >= 0){
       
     if($arr_rg[$ky] > 0){
         
           $kirim = array(
        "rg"                            => $fix_rg,
        "update_by_users"               => $this->session->userdata("id"),
        "update_date"                   => date("Y-m-d H:i:s")
        );
        $this->global_models->update("mrp_receiving_goods_po_asset", array("id_mrp_receiving_goods_po_asset" => $val),$kirim);
        
        $dt_mrp_stock = $this->global_models->get("mrp_stock",array("id_mrp_inventory_spesifik" => "{$get_task_order_asset[$ky][0]->id_mrp_inventory_spesifik}"));
        
        $kirim = array(
            "id_mrp_receiving_goods"            => $id_rg,   
            "id_mrp_stock"                      => $dt_mrp_stock[0]->id_mrp_stock,
            "id_mrp_satuan"                     => $get_task_order_asset[$ky][0]->id_mrp_satuan,
            "jumlah"                            => $arr_rg[$ky],
            "id_mrp_inventory_spesifik"         => $get_task_order_asset[$ky][0]->id_mrp_inventory_spesifik,
            "harga"                             => $get_task_order_asset[$ky][0]->harga,
            "status"                            => 1,
            "create_by_users"                   => $this->session->userdata("id"),
            "create_date"                       => date("Y-m-d H:i:s")
          );
        $dt_id_stock[$ky] = $this->global_models->insert("mrp_receiving_goods_detail", $kirim);
    
    
    if($dt_mrp_stock[0]->id_mrp_inventory_spesifik > 0){
        
        $dtstock_in             = $dt_mrp_stock[0]->stock_in + $arr_rg[$ky];
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
        "id_mrp_receiving_goods_detail"         => $dt_id_stock[$ky],
        "id_mrp_inventory_spesifik"             => $dt_mrp_stock[0]->id_mrp_inventory_spesifik,
        "id_mrp_satuan"                         => $dt_mrp_stock[0]->id_mrp_satuan,    
        "id_mrp_supplier"                       => $get_task_order_asset[$ky][0]->id_mrp_supplier,
        "jumlah"                                => $arr_rg[$ky],  
        "tanggal_diterima"                      => $tgl_diterima, 
        "harga"                                 => $get_task_order_asset[$ky][0]->harga,
        "status"                                => 1,
        "create_by_users"                       => $this->session->userdata("id"),
        "create_date"                           => date("Y-m-d H:i:s")
      );
     $id_mrp_stock_in = $this->global_models->insert("mrp_stock_in", $kirim);
     
    }else{
      $kirim = array( 
        "id_mrp_inventory_spesifik"         => $get_task_order_asset[$ky][0]->id_mrp_inventory_spesifik,
        "id_mrp_satuan"                     => $get_task_order_asset[$ky][0]->id_mrp_satuan,   
        "stock_in"                          => $arr_rg[$ky],
        "status"                            => 1,
        "create_by_users"                   => $this->session->userdata("id"),
        "create_date"                       => date("Y-m-d H:i:s")
      );
     $id_stock[$ky] = $this->global_models->insert("mrp_stock", $kirim);
     
     $this->olah_stock_in_code($kode);
     $kirim = array(
        "id_mrp_stock"                          => $id_stock[$ky],
        "kode"                                  => $kode,  
        "id_mrp_receiving_goods_detail"         => $dt_id_stock[$ky],
        "id_mrp_inventory_spesifik"             => $get_task_order_asset[$ky][0]->id_mrp_inventory_spesifik,
        "id_mrp_satuan"                         => $get_task_order_asset[$ky][0]->id_mrp_satuan,    
        "id_mrp_supplier"                       => $get_task_order_asset[$ky][0]->id_mrp_supplier,
        "jumlah"                                => $arr_rg[$ky], 
        "tanggal_diterima"                      => $tgl_diterima, 
        "harga"                                 => $get_task_order_asset[$ky][0]->harga,
        "status"                                => 1,
        "create_by_users"                       => $this->session->userdata("id"),
        "create_date"                           => date("Y-m-d H:i:s")
      );
     $id_mrp_stock_in = $this->global_models->insert("mrp_stock_in", $kirim);
     
    }
    $this->load->model('mrp/mmrp');
    $this->mmrp->mutasi_rg($id_mrp_stock_in,$get_task_order_asset[$ky][0]->id_mrp_receiving_goods_po,$get_task_order_asset[$ky][0]->id_mrp_inventory_spesifik,$arr_id_mrp_request[$ky],$id_rg_department,$tgl_diterima,$note);
    
     }
      
    $this->session->set_flashdata('success', 'Data Berhasil di Proses');
   }else{
    $this->session->set_flashdata('notice', 'Data tidak tersimpan');
   }
   
     }
     
        if($flag_rg > 0){
//            print $flag_rg."test1<br>";
                $krm2 = array(
                "status"                            => 7,
                "update_by_users"                   => $this->session->userdata("id"),
                "update_date"                       => date("Y-m-d H:i:s")
            );
            $this->global_models->update("mrp_receiving_goods_po", array("id_mrp_receiving_goods_po" => $id_mrp_receiving_goods_po),$krm2);

        }else{
//            print $flag_rg."test2<br>";
               $krm2 = array(
                "status"                            => 8,
                "update_by_users"                   => $this->session->userdata("id"),
                "update_date"                       => date("Y-m-d H:i:s")
            );
            $this->global_models->update("mrp_receiving_goods_po", array("id_mrp_receiving_goods_po" => $id_mrp_receiving_goods_po),$krm2);
        }
//     print $flag_rg."a<br>";
//     print $flag_rg2."B<br>";
//     die;
     $return['status'] = 1;
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

function approve_detail_po($id_mrp_task_orders = 0,$id_mrp_po = 0){
//  $id_mrp_task_orders_request_asset = $_POST['id_mrp_task_orders_request_asset'];
  $status =  $_POST['status'];
//  $id_supplier =  $_POST['id_supplier'];
  $id_company =  $_POST['id_company'];
  
 
   if($status == 5){
       
       $kirim3 = array(
        "status"                        => 3,
        "update_by_users"               => $this->session->userdata("id"),
        "update_date"                   => date("Y-m-d H:i:s")
    );
    
      $this->global_models->update("mrp_task_orders", array("id_mrp_task_orders" => $id_mrp_task_orders),$kirim3);
   
       $tahun = date("Y");
       $code = $this->global_models->get_field("hr_company", "code",array("id_hr_company" => "{$id_company}"));
  
       $nomor = $this->global_models->get_field("no_po", "nomor",array("tahun" => "{$tahun}","code_company" => $code));
       if($nomor > 0){
            $number = $nomor;
       }else{
           $number = 1;
           $kirim = array(
            "nomor"                     => $number,
            "tahun"                     => $tahun,
            "status"                    => 1,
            "code_company"              => $code,  
            "create_by_users"           => $this->session->userdata("id"),
            "create_date"               => date("Y-m-d H:i:s")
        );
        $id_supplier = $this->global_models->insert("no_po", $kirim);
          
       }
       $no_po = $code."/PO/".$number."/".date("m")."/".date("Y");
   
    $kirim2 = array(
        "status"                        => $status,
        "no_po"                         => $no_po,
        "user_approval"                 => $this->session->userdata("id"),
        "update_by_users"               => $this->session->userdata("id"),
        "update_date"                   => date("Y-m-d H:i:s")
    );
    $this->global_models->update("mrp_po", array("id_mrp_po" => $id_mrp_po),$kirim2);
       
    $kirim3 = array(
        "nomor"                         => ($number+1),
        "update_by_users"               => $this->session->userdata("id"),
        "update_date"                   => date("Y-m-d H:i:s")
    );
    $this->global_models->update("no_po", array("tahun" => "{$tahun}","code_company" => $code),$kirim3);
    
   }elseif($status == 6){
       
    $kirim = array(
        "status"                        => 4,
        "update_by_users"               => $this->session->userdata("id"),
        "update_date"                   => date("Y-m-d H:i:s")
    );
    
    $this->global_models->update("mrp_task_orders", array("id_mrp_task_orders" => $id_mrp_task_orders),$kirim);
   
    $mrp_task_orders_request = $this->global_models->get("mrp_task_orders_request", array("id_mrp_task_orders" => $id_mrp_task_orders));
    
        foreach ($mrp_task_orders_request as $vl) {
             $kirim = array(
                "status"                        => 6,
                "update_by_users"               => $this->session->userdata("id"),
                "update_date"                   => date("Y-m-d H:i:s")
            );
    
        $this->global_models->update("mrp_request", array("id_mrp_request" => $vl->id_mrp_request),$kirim);
   
    }
        
       //status 6 => SEND PO
       $kirim2 = array(
        "status"                        => $status,
        "update_by_users"               => $this->session->userdata("id"),
        "update_date"                   => date("Y-m-d H:i:s")
    );
    $this->global_models->update("mrp_po", array("id_mrp_po" => $id_mrp_po),$kirim2);
    
    //Create RG
    
    $this->olah_receiving_goods_code($kode);
     $kirim = array(
            "id_mrp_po"                 => $id_mrp_po,
            "code"                      => $kode,
            "id_mrp_task_orders"        => $id_mrp_task_orders, 
            "status"                    => $status,
            "create_by_users"           => $this->session->userdata("id"),
            "create_date"               => date("Y-m-d H:i:s")
        );
        $id_rg_po = $this->global_models->insert("mrp_receiving_goods_po", $kirim);
    
        $dt_po = $this->global_models->get("mrp_po_asset",array("id_mrp_po" => "{$id_mrp_po}"));
        
        foreach ($dt_po as $ky => $val) {
                $kirim = array(
                "id_mrp_receiving_goods_po"         => $id_rg_po,
                "id_mrp_po_asset"                   => $val->id_mrp_po_asset,
                "id_mrp_inventory_spesifik"         => $val->id_mrp_inventory_spesifik,
                "id_mrp_satuan"                     => $val->id_mrp_satuan,
                "jumlah"                            => $val->jumlah,
                "harga"                             => $val->harga,
                "id_mrp_supplier"                   => $val->id_mrp_supplier,    
                "status"                            => $status,
                "create_by_users"                   => $this->session->userdata("id"),
                "create_date"                       => date("Y-m-d H:i:s")
            );
            $this->global_models->insert("mrp_receiving_goods_po_asset", $kirim);
        }
        
   }elseif($status == 8){
       $kirim2 = array(
        "status"                        => $status,
        "update_by_users"               => $this->session->userdata("id"),
        "update_date"                   => date("Y-m-d H:i:s")
    );
    $this->global_models->update("mrp_po", array("id_mrp_po" => $id_mrp_po),$kirim2);
   }
   
    $kirim = array(
        "status"                         => $status,
        "update_by_users"               => $this->session->userdata("id"),
        "update_date"                   => date("Y-m-d H:i:s")
    );
    
    $this->global_models->update("mrp_po_asset", array("id_mrp_task_orders" => $id_mrp_task_orders,"id_mrp_po" => $id_mrp_po),$kirim);

 $this->session->set_flashdata('success', 'Data Berhasi di Proses '.$dt_status[$status]);
  
 die;

}

function closed_detail_po($id_mrp_task_orders = 0,$id_mrp_po = 0){
//  $id_mrp_task_orders_request_asset = $_POST['id_mrp_task_orders_request_asset'];
  $status =  $_POST['status'];
//  $id_supplier =  $_POST['id_supplier'];
 
   if($status == 7){
    $kirim2 = array(
        "status"                        => $status,
        "update_by_users"               => $this->session->userdata("id"),
        "update_date"                   => date("Y-m-d H:i:s")
    );
    $this->global_models->update("mrp_po", array("id_mrp_po" => $id_mrp_po),$kirim2);      
   }
   
    $kirim = array(
        "status"                         => $status,
        "update_by_users"               => $this->session->userdata("id"),
        "update_date"                   => date("Y-m-d H:i:s")
    );
    
    $this->global_models->update("mrp_task_orders_request_asset", array("id_mrp_task_orders" => $id_mrp_task_orders,"id_mrp_po" => $id_mrp_po),$kirim);

 $this->session->set_flashdata('success', 'Data Berhasi di Proses');
 die;

}

function closed_task_orders(){
 $id_mrp_task_orders =$_POST['id_mrp_task_orders'];
  
    $kirim2 = array(
        "status"                        => 9,
        "update_by_users"               => $this->session->userdata("id"),
        "update_date"                   => date("Y-m-d H:i:s")
    );
    $this->global_models->update("mrp_task_orders", array("id_mrp_task_orders" => $id_mrp_task_orders),$kirim2);      

   $get = $this->global_models->get("mrp_task_orders_request", array("id_mrp_task_orders" => $id_mrp_task_orders));
         
   foreach ($get as $val) {
       $kirim = array(
        "status"                        => 9,
        "update_by_users"               => $this->session->userdata("id"),
        "update_date"                   => date("Y-m-d H:i:s")
    );
    $this->global_models->update("mrp_request", array("id_mrp_request" => $val->id_mrp_request),$kirim);      

   }
   
 $this->session->set_flashdata('success', 'Data Berhasi di Proses');
 die;

}

function get_mrp_list_po($start = 0,$id_users = 0){
      
    $data = $this->global_models->get_query("SELECT A.id_mrp_po,A.no_po,A.status,A.tanggal_dikirim,A.code,A.create_date"
        . ",B.id_mrp_task_orders,C.code AS kode_task,A.create_by_users"
        . ",D.name"
        . " FROM mrp_po AS A"
        . " LEFT JOIN mrp_po_asset AS B ON A.id_mrp_po = B.id_mrp_po"
        . " LEFT JOIN mrp_task_orders AS C ON B.id_mrp_task_orders = C.id_mrp_task_orders"
        . " LEFT JOIN mrp_supplier AS D ON A.id_mrp_supplier = D.id_mrp_supplier"   
//        . " {$where}"
        . " GROUP BY A.id_mrp_po "
        . " ORDER BY A.id_mrp_po ASC"
        . " LIMIT {$start}, 10");
        
//    $data_array = json_decode($data);
//    $this->debug($data, true);
  
//    $jenis = array(1 => "Habis Pakai", 2 => "Asset");
    $status = array( 3=> "<span class='label bg-orange'>Draft PO</span>", 4 => "<span class='label bg-green'>Proses Pengajuan PO</span>",
      5 => "<span class='label bg-green'>Approved PO</span>", 6 =>"<span class='label bg-green'>Sent PO</span>"
        , 7 =>"<span class='label bg-green'>Closed PO</span>", 8 =>"<span class='label bg-red'>Revisi PO</span>");
    
//    $status = array(1 => "Create", 2 => "Approve");
    $hide = 0;
    foreach ($data AS $ky => $da){
     $cd_po = date("d M Y H:i:s", strtotime($da->create_date));
    if($da->status <= "3"){
        if($da->create_by_users == $id_users){
            if($da->status == 3 OR $da->status == 8){
        $btn_update ="<a href='".site_url("mrp/update-po/{$da->id_mrp_task_orders}/{$da->id_mrp_po}")."' type='button' class='btn btn-info btn-flat' title='Edit Purchase Order' style='width: 40px'><i class='fa fa-edit'></i></a>";
         }else{
             $btn_update = "";
         }
        
         if($da->status >= 6 AND $da->status <= 7){
            $btn_rg = "<a href='".site_url("mrp/rg/{$da->id_mrp_po}")."' type='button' class='btn btn-info btn-flat' title='Receiving Goods' style='width: 40px'><i class='fa fa-file-text-o'></i></a>";
            $btn_status_payment = "<a href='".site_url("mrp/status-payment/{$da->id_mrp_po}")."' type='button' class='btn btn-warning btn-flat' title='Status Payment' style='width: 40px'><i class='fa fa-indent'></i></a>";
        }else{
            $btn_status_payment = "";
            $btn_rg = "";
        }
        
        $btn_del = "";
        if($da->status < 4){
            $btn_del = "<a href='".site_url("mrp/delete-list-po/{$da->id_mrp_po}")."' type='button' class='btn btn-danger btn-flat' title='Delete Purchase Order' style='width: 40px'><i class='fa fa-trash-o'></i></a>";
        }
        
        if($da->tanggal_dikirim != "0000-00-00" AND $da->tanggal_dikirim != ""){
            $tgl = date("d M Y", strtotime($da->tanggal_dikirim));
        }else{
            $tgl = "";
        }
        
            $hasil[$ky] = array(
            $da->no_po,
            $cd_po."<br>".$da->code,    
            "<a href='".site_url("mrp/add-task-orders/{$da->id_mrp_task_orders}")."'  title='Task Order' style='width: 40px'>{$da->kode_task}</a>",
            $da->name,
            $tgl,
            $status[$da->status], 
            "<div class='btn-group'>"
              . $btn_update
              . "<a href='".site_url("mrp/detail-po/{$da->id_mrp_task_orders}/{$da->id_mrp_po}")."' type='button' class='btn btn-info btn-flat' title='List Purchase Order' style='width: 40px'><i class='fa fa-list-alt'></i></a>"
              . $btn_del
              . $btn_status_payment      
            . "</div>"
          );
        }
          
          $hide++;
     }else{
                if($da->status == 3 OR $da->status == 8){
        $btn_update ="<a href='".site_url("mrp/update-po/{$da->id_mrp_task_orders}/{$da->id_mrp_po}")."' type='button' class='btn btn-info btn-flat' title='Edit Purchase Order' style='width: 40px'><i class='fa fa-edit'></i></a>";
         }else{
             $btn_update = "";
             
         }
        
          if($da->status >= 6 AND $da->status <= 7){
            $btn_rg = "<a href='".site_url("mrp/rg/{$da->id_mrp_po}")."' type='button' class='btn btn-info btn-flat' title='Receiving Goods' style='width: 40px'><i class='fa fa-file-text-o'></i></a>";
            $btn_status_payment = "<a href='".site_url("mrp/status-payment/{$da->id_mrp_po}")."' type='button' class='btn btn-warning btn-flat' title='Status Payment' style='width: 40px'><i class='fa fa-indent'></i></a>";
        }else{
            $btn_status_payment = "";
            $btn_rg = "";
        }
        
        if($da->tanggal_dikirim != "0000-00-00" AND $da->tanggal_dikirim != ""){
            $tgl = date("d M Y", strtotime($da->tanggal_dikirim));
        }else{
            $tgl = "";
        }
        
            $hasil[$ky] = array(
            $da->no_po,
            $cd_po."<br>".$da->code,    
            "<a href='".site_url("mrp/add-task-orders/{$da->id_mrp_task_orders}")."'  title='Task Order' style='width: 40px'>{$da->kode_task}</a>",
            $da->name,
            $tgl,
            $status[$da->status], 
            "<div class='btn-group'>"
              . $btn_update
              . "<a href='".site_url("mrp/detail-po/{$da->id_mrp_task_orders}/{$da->id_mrp_po}")."' type='button' class='btn btn-info btn-flat' title='List Purchase Order' style='width: 40px'><i class='fa fa-list-alt'></i></a>"
    //          . $btn_rg
              . $btn_status_payment        
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
  
  function get_view_add_to_list_po($id_mrp_task_orders = 0,$start = 0,$id_users = 0){
      
    $data = $this->global_models->get_query("SELECT A.id_mrp_po,A.no_po,A.status,A.tanggal_dikirim,A.code"
        . ",B.id_mrp_task_orders,C.code AS kode_task,A.create_by_users"
        . ",D.name"
        . " FROM mrp_po AS A"
        . " LEFT JOIN mrp_po_asset AS B ON A.id_mrp_po = B.id_mrp_po"
        . " LEFT JOIN mrp_task_orders AS C ON B.id_mrp_task_orders = C.id_mrp_task_orders"
        . " LEFT JOIN mrp_supplier AS D ON A.id_mrp_supplier = D.id_mrp_supplier"   
        . " WHERE C.id_mrp_task_orders='{$id_mrp_task_orders}'"
        . " GROUP BY A.id_mrp_po "
        . " ORDER BY A.id_mrp_po ASC"
        . " LIMIT {$start}, 10");
        
//    $data_array = json_decode($data);
//    $this->debug($data, true);
  
//    $jenis = array(1 => "Habis Pakai", 2 => "Asset");
    $status = array( 3=> "<span class='label bg-orange'>Draft PO</span>", 4 => "<span class='label bg-green'>Proses Pengajuan PO</span>",
      5 => "<span class='label bg-green'>Approved PO</span>", 6 =>"<span class='label bg-green'>Send PO</span>"
        , 7 =>"<span class='label bg-green'>Closed PO</span>", 8 =>"<span class='label bg-red'>Revisi PO</span>");
    
//    $status = array(1 => "Create", 2 => "Approve");
    $hide = 0;
    foreach ($data AS $ky => $da){
        
    if($da->status <= "3"){
        if($da->create_by_users == $id_users){
            if($da->status == 3 OR $da->status == 8){
        $btn_update ="<a href='".site_url("mrp/update-po/{$da->id_mrp_task_orders}/{$da->id_mrp_po}")."' type='button' class='btn btn-info btn-flat' title='Edit Purchase Order' style='width: 40px'><i class='fa fa-edit'></i></a>";
         }else{
             $btn_update = "";
         }
        
         if($da->status >= 6 AND $da->status <= 7){
            $btn_rg = "<a href='".site_url("mrp/rg/{$da->id_mrp_po}")."' type='button' class='btn btn-info btn-flat' title='Receiving Goods' style='width: 40px'><i class='fa fa-file-text-o'></i></a>";
        }else{
            $btn_rg = "";
        }
        
        if($da->tanggal_dikirim != "0000-00-00" AND $da->tanggal_dikirim != ""){
            $tgl = date("d M Y", strtotime($da->tanggal_dikirim));
        }else{
            $tgl = "";
        }
        
            $hasil[$ky] = array(
            $da->no_po,
              "<a data-toggle='collapse' data-parent='#accordion'  href='#collapsePO' onclick='coba_data10({$da->id_mrp_task_orders},{$da->id_mrp_po});'>".$da->code."</a>"
                . "<script>"
                 . "function coba_data10(id_mrp_task_orders,id_mrp_po){"
                    . 'var table = '
                    . '$("#tableboxy12").dataTable({'
                    . '"order": [[ 0, "asc" ]],'
                    . '"bDestroy": true'
                    . '});'
                    . "table.fnClearTable();"
                    . 'ambil_data12(id_mrp_task_orders,id_mrp_po,table, 0);'
            . "}"
      
               . 'function ambil_data12(id_mrp_task_orders,id_mrp_po,table, mulai,total){'
        . '$.post("'.site_url("mrp/mrp-ajax/get-mrp-detail-po-list").'/"+id_mrp_task_orders+"/"+id_mrp_po+"/"+mulai+"/"+total, function(data){'
          . '$("#loader-page12").show();'
          . 'var hasil = $.parseJSON(data);'
          . 'if(hasil.status == 2){'
            . 'table.fnAddData(hasil.hasil);'
            . 'ambil_data12(id_mrp_task_orders,id_mrp_po,table, hasil.start, hasil.dt_total);'
           . "$('#dt-total').text(parseFloat(hasil.dt_total, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,').toString());"
          . '}'
          . 'else{'
            . '$("#loader-page12").hide();'
//            . 'load_tooltip();'
//            . 'load_tooltip_harga();'
//            . "$('#script-tambahan').html(hasil.dt_total);"
          . '}'
        . '});'
      . '}'
        . "</script>",    
            $da->kode_task,
            $da->name,
            $tgl,
            $status[$da->status], 
            "<div class='btn-group'>"
              . "<a href='".site_url("mrp/detail-po/{$da->id_mrp_task_orders}/{$da->id_mrp_po}")."' type='button' class='btn btn-info btn-flat' title='Detail Purchase Order' style='width: 40px'><i class='fa fa-list-alt'></i></a>"
    //          . $btn_rg
            . "</div>"
          );
        }
          
          $hide++;
     }else{
                if($da->status == 3 OR $da->status == 8){
        $btn_update ="<a href='".site_url("mrp/update-po/{$da->id_mrp_task_orders}/{$da->id_mrp_po}")."' type='button' class='btn btn-info btn-flat' title='Edit Purchase Order' style='width: 40px'><i class='fa fa-edit'></i></a>";
         }else{
             $btn_update = "";
         }
        
         if($da->status >= 6 AND $da->status <= 7){
            $btn_rg = "<a href='".site_url("mrp/rg/{$da->id_mrp_po}")."' type='button' class='btn btn-info btn-flat' title='Receiving Goods' style='width: 40px'><i class='fa fa-file-text-o'></i></a>";
        }else{
            $btn_rg = "";
        }
        
        if($da->tanggal_dikirim != "0000-00-00" AND $da->tanggal_dikirim != ""){
            $tgl = date("d M Y", strtotime($da->tanggal_dikirim));
        }else{
            $tgl = "";
        }
        
            $hasil[$ky] = array(
            $da->no_po,
            "<a data-toggle='collapse' data-parent='#accordion'  href='#collapsePO' onclick='coba_data10({$da->id_mrp_task_orders},{$da->id_mrp_po});'>".$da->code."</a>"
                . "<script>"
                 . "function coba_data10(id_mrp_task_orders,id_mrp_po){"
                    . 'var table = '
                    . '$("#tableboxy12").dataTable({'
                    . '"order": [[ 0, "asc" ]],'
                    . '"bDestroy": true'
                    . '});'
                    . "table.fnClearTable();"
                    . 'ambil_data12(id_mrp_task_orders,id_mrp_po,table, 0);'
            . "}"
      
               . 'function ambil_data12(id_mrp_task_orders,id_mrp_po,table, mulai,total){'
        . '$.post("'.site_url("mrp/mrp-ajax/get-mrp-detail-po-list").'/"+id_mrp_task_orders+"/"+id_mrp_po+"/"+mulai+"/"+total, function(data){'
          . '$("#loader-page12").show();'
          . 'var hasil = $.parseJSON(data);'
          . 'if(hasil.status == 2){'
            . 'table.fnAddData(hasil.hasil);'
            . 'ambil_data12(id_mrp_task_orders,id_mrp_po,table, hasil.start, hasil.dt_total);'
           . "$('#dt-total').text(parseFloat(hasil.dt_total, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,').toString());"
          . '}'
          . 'else{'
            . '$("#loader-page12").hide();'
//            . 'load_tooltip();'
//            . 'load_tooltip_harga();'
//            . "$('#script-tambahan').html(hasil.dt_total);"
          . '}'
        . '});'
      . '}'
        . "</script>",    
            $da->kode_task,
            $da->name,
            $tgl,
            $status[$da->status], 
            "<div class='btn-group'>"
              . $btn_update
              . "<a href='".site_url("mrp/detail-po/{$da->id_mrp_task_orders}/{$da->id_mrp_po}")."' type='button' class='btn btn-info btn-flat' title='Detail Purchase Order' style='width: 40px'><i class='fa fa-list-alt'></i></a>"
    //          . $btn_rg
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
  
  function get_mrp_list_rg($start = 0){
      
      $where = " WHERE A.status >= 6 AND A.status <= 8";
    $data = $this->global_models->get_query("SELECT A.id_mrp_receiving_goods_po,A.status,A.create_date,A.code AS code_rg"
        . ",B.code AS code_po,B.id_mrp_po,C.id_mrp_task_orders"
        . " FROM mrp_receiving_goods_po AS A"
        . " LEFT JOIN mrp_po AS B ON A.id_mrp_po = B.id_mrp_po"
        . " LEFT JOIN mrp_po_asset AS C ON B.id_mrp_po = C.id_mrp_po"    
        . " {$where}"
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
            $btn_rg = "<a href='".site_url("mrp/rg/{$da->id_mrp_receiving_goods_po}")."' type='button' class='btn btn-info btn-flat' title='Receiving Goods' style='width: 40px'><i class='fa fa-plus'></i></a>";
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
        "<a href='".site_url("mrp/detail-po/{$da->id_mrp_task_orders}/{$da->id_mrp_po}")."'  title='Purchase Order' style='width: 40px'>{$da->code_po}</a>",
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
  
    function get_mrp_list_rg_department($start = 0){
//      $id_hr_pegawai =$this->global_models->get_field("hr_pegawai","id_hr_pegawai",array("id_users" =>$this->session->userdata("id")));
       
      $dta_id = $this->global_models->get_field("hr_pegawai", "id_hr_master_organisasi", array("id_users" => $this->session->userdata("id")));
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
        $where = " WHERE (A.status >=6) AND H.title IS NOT NULL ";
      
//    $data = $this->global_models->get_query("SELECT A.id_mrp_receiving_goods_po,A.status,A.create_date,A.code AS code_rg"
//        . ",B.code AS code_po,B.id_mrp_po,C.id_mrp_task_orders"
//        . " FROM mrp_receiving_goods_po AS A"
//        . " LEFT JOIN mrp_po AS B ON A.id_mrp_po = B.id_mrp_po"
//        . " LEFT JOIN mrp_po_asset AS C ON B.id_mrp_po = C.id_mrp_po"    
//        . " {$where}"
//        . " GROUP BY C.id_mrp_po"
//        . " ORDER BY A.id_mrp_po ASC"
//        . " LIMIT {$start}, 10");
      
        $data = $this->global_models->get_query("SELECT F.code,F.id_mrp_request,F.type_inventory,A.id_mrp_receiving_goods_po,A.status,A.create_date,A.code AS code_rg"
        . ",B.no_po AS code_po,B.id_mrp_po,C.id_mrp_task_orders,F.status AS status_request"
        . ",I.name AS users,H.title AS hr_organisasi"
        . " FROM mrp_receiving_goods_po AS A"
        . " LEFT JOIN mrp_po AS B ON A.id_mrp_po = B.id_mrp_po"
        . " LEFT JOIN mrp_po_asset AS C ON B.id_mrp_po = C.id_mrp_po"
        . " LEFT JOIN mrp_task_orders AS D ON C.id_mrp_task_orders = D.id_mrp_task_orders"
        . " LEFT JOIN mrp_task_orders_request AS E ON D.id_mrp_task_orders = E.id_mrp_task_orders"
        . " LEFT JOIN mrp_request AS F ON E.id_mrp_request = F.id_mrp_request"
        . " LEFT JOIN hr_pegawai AS G ON F.id_hr_pegawai = G.id_hr_pegawai"
        . " LEFT JOIN hr_master_organisasi AS H ON (G.id_hr_master_organisasi = H.id_hr_master_organisasi AND G.id_hr_master_organisasi ='{$dta_id}')"
        . " LEFT JOIN m_users AS I ON G.id_users = I.id_users"    
        . " {$where}"
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
        "<a data-toggle='collapse' data-parent='#accordion'  href='#collapseOne' onclick='coba_data({$da->id_mrp_receiving_goods_po},{$da->id_mrp_request});'>".$da->code_rg."</a>"
         . "<script>"
            . "function coba_data(id,id_mrp_request){"
                . 'var table = '
                . '$("#tableboxy1").dataTable({'
                  . '"order": [[ 1, "asc" ]],'
                . '"bDestroy": true'
                . '});'
                . "table.fnClearTable();"
                . 'ambil_data5(table, 0,id,id_mrp_request);'
            . "}"
        
        . 'function ambil_data5(table, mulai,id,id_mrp_request){'
        
        . '$.post("'.site_url("mrp/mrp-ajax/get-rg-department-history").'/"+id+"/"+id_mrp_request+"/"+mulai, function(data){'
          . '$("#loader-page1").show();'
          . 'var hasil = $.parseJSON(data);'
          . 'if(hasil.status == 2){'
                . "table.fnClearTable();"
            . 'table.fnAddData(hasil.hasil);'
                
            . 'ambil_data5(table, hasil.start,id,id_mrp_request);'
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
  
  function get_mrp_detail_rg($id_mrp_receiving_goods_po = 0,$start = 0){
      
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
  
  function get_mrp_detail_rg_department($id_mrp_receiving_goods_po = 0,$start = 0){
      
      $lst = $this->global_models->get_query("SELECT A.id_hr_master_organisasi"
        . " FROM hr_pegawai AS A"
        . " LEFT JOIN m_users AS B ON A.id_users = B.id_users"
        . " LEFT JOIN hr_master_organisasi AS C ON A.id_hr_master_organisasi = C.id_hr_master_organisasi"
        . " WHERE A.id_users ='{$this->session->userdata("id")}'"
        );
//      $type = 1 => rg_keseluruhan
//      $type = 2 => rg_department
//      $where = " WHERE A.status =6";
        $where = " WHERE A.id_mrp_receiving_goods_po ='{$id_mrp_receiving_goods_po}' AND D.id_hr_master_organisasi = '{$lst[0]->id_hr_master_organisasi}'";
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
        
//        $this->db->last_query();
//        die('tes');
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
  
  function get_mrp_detail_po_list($id_mrp_task_orders = 0,$id_mrp_po = 0,$start = 0, $dt_total = 0){
//      $id_mrp_supplier = 1;
       $url = base_url()."themes/".DEFAULTTHEMES."/img/ajax-loader.gif";
      $where = "WHERE A.status >= 3 AND A.id_mrp_task_orders = '$id_mrp_task_orders' AND A.id_mrp_po = '$id_mrp_po'  ";

    $data = $this->global_models->get_query("SELECT A.id_mrp_task_orders_request_asset,A.jumlah,A.note,C.name AS nama_barang,E.title AS satuan"
        . ",B.title AS title_spesifik,F.harga,A.id_mrp_task_orders_request_asset,E.group_satuan,A.harga AS harga_task_order_request"
        . ",E.nilai,D.title AS brand"
        . " FROM mrp_po_asset AS A"
        . " LEFT JOIN mrp_inventory_spesifik AS B ON A.id_mrp_inventory_spesifik = B.id_mrp_inventory_spesifik"
        . " LEFT JOIN mrp_inventory_umum AS C ON B.id_mrp_inventory_umum = C.id_mrp_inventory_umum"
        . " LEFT JOIN mrp_brand AS D ON B.id_mrp_brand = D.id_mrp_brand"
        . " LEFT JOIN mrp_satuan AS E ON A.id_mrp_satuan = E.id_mrp_satuan"
        . " LEFT JOIN mrp_supplier_inventory AS F ON B.id_mrp_inventory_spesifik = (F.id_mrp_inventory_spesifik AND F.id_mrp_supplier = '{$id_mrp_supplier}')"
        . " LEFT JOIN mrp_supplier G ON F.id_mrp_supplier = G.id_mrp_supplier "
        . " {$where}"
        . " ORDER BY A.id_mrp_task_orders ASC"
        . " LIMIT {$start}, 1");
//        print $acs = $this->db->last_query();
//        die;
    if(count($data) > 0){
      $return['status'] = 2;
      $return['start']  = $start + 1;
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

        $total = (($da->jumlah * $da->nilai) * $da->harga_task_order_request);
        
        $brn = "";
        if($da->brand){
            $brn = "<br>Brand:".$da->brand;
        }
        $hasil[] = array(
        $da->nama_barang." ".$da->title_spesifik.$brn,    
        $da->satuan,
        $da->jumlah,
        number_format($da->harga_task_order_request),
        number_format($total),
        $da->note,    
      );
           $dt_total = $dt_total + $total;
         
    }
    
    $return['hasil'] = $hasil;
    $return['dt_total'] = $dt_total;
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
//        
//    $where = "WHERE A.status >= 3 AND A.id_mrp_receiving_goods_po = '{$id_mrp_receiving_goods_po}' AND I.id_mrp_request IS NOT NULL  ";
    $where2 = "WHERE E.status >= 3";
      
    $data2 = $this->global_models->get_query("SELECT B.name AS nama_barang,C.title AS brand,D.title AS satuan,"
        . "A.id_mrp_inventory_spesifik,A.title AS title_spesifik,E.jumlah,E.rg,F.id_mrp_request,F.status AS status_request"
        . " FROM mrp_inventory_spesifik AS A"
        . " LEFT JOIN mrp_inventory_umum AS B ON A.id_mrp_inventory_umum = B.id_mrp_inventory_umum"
        . " LEFT JOIN mrp_brand AS C ON A.id_mrp_brand = C.id_mrp_brand"
        . " LEFT JOIN mrp_satuan AS D ON A.id_mrp_satuan = D.id_mrp_satuan"
        . " LEFT JOIN mrp_request_asset AS E ON (A.id_mrp_inventory_spesifik = E.id_mrp_inventory_spesifik AND E.id_mrp_request = '{$id_mrp_request}')"
        . " LEFT JOIN mrp_request AS F ON E.id_mrp_request = F.id_mrp_request"
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
                    $da->satuan,
                    $da->jumlah,
                    $da->note.
                    $this->form_eksternal->form_input("id_mrp_task_orders_request_asset[]", $val->id_mrp_receiving_goods_po_asset, ' class="form-control id_mrp_receiving_goods_po_asset input-sm" style="display: none"')
                    .$this->form_eksternal->form_input("id_mrp_request[]", $da->id_mrp_request, ' class="form-control id_mrp_request input-sm" style="display: none"')    ,
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
  
  function get_rg($id_mrp_receiving_goods_po = 0,$start = 0){
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
  
    function get_rg_department_history($id_mrp_receiving_goods_po = 0,$id_mrp_request = 0,$start = 0){
        
//        $id_hr_pegawai =$this->global_models->get_field("hr_pegawai","id_hr_pegawai",array("id_users" =>$this->session->userdata("id")));
//          if($this->session->userdata("id") == 1){
//          $id_usr = 9;
//      }else{
//          $id_usr = $this->session->userdata("id");
//      }
//      $this->global_models->get("hr_pegawai",array());
      $where = "WHERE A.status >= 3 AND A.id_mrp_receiving_goods_po = '{$id_mrp_receiving_goods_po}' AND I.id_mrp_request IS NOT NULL  ";

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

    $data = $this->global_models->get_query("SELECT L.id_users,I.id_mrp_request,A.id_mrp_receiving_goods_po_asset,A.jumlah AS jml_po,"
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
      $where = "WHERE A.id_mrp_receiving_goods_department = '$id_mrp_receiving_goods_department' AND A.id_hr_master_organisasi = '$id_hr_master_organisasi' ";

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
  
  function change_satuan($id_mrp_task_orders_request_asset = 0){
  
  $jumlah =  $_POST['jumlah'];
  $id_mrp_satuan =  $_POST['dsatuan'];

  $dt_sort_new = $this->global_models->get_field("mrp_satuan", "sort",array("id_mrp_satuan" => "{$id_mrp_satuan}"));
  $dt_nilai_new = $this->global_models->get_field("mrp_satuan", "nilai",array("id_mrp_satuan" => "{$id_mrp_satuan}"));
  $dta = $this->global_models->get("mrp_po_asset", array("id_mrp_task_orders_request_asset" => $id_mrp_task_orders_request_asset));
  $dt_sort_old = $this->global_models->get_field("mrp_satuan", "sort",array("id_mrp_satuan" => "{$dta[0]->id_mrp_satuan}"));
  $dt_nilai_old = $this->global_models->get_field("mrp_satuan", "nilai",array("id_mrp_satuan" => "{$dta[0]->id_mrp_satuan}"));
  
  if($dt_sort_new > $dt_sort_old){
        $v_jumlah = ($dt_nilai_old/$dt_nilai_new) * $jumlah;
        $total = (($v_jumlah * $dt_nilai_new)* $dta[0]->harga);
  }elseif($dt_sort_new < $dt_sort_old){
           $v_jumlah = ceil(($jumlah/$dt_nilai_new)* $dt_nilai_old);
//        $v_jumlah = ceil($jumlah/$dt_nilai_new);
        $total = (($v_jumlah * $dt_nilai_new) * $dta[0]->harga);
  }elseif($dt_sort_new == $dt_sort_old){
        $v_jumlah = $jumlah;
        $total = (($v_jumlah)*$dta[0]->harga);
  }
   
  if($id_mrp_satuan > 0){
      $kirim = array(
        "jumlah"                        => $v_jumlah,
        "id_mrp_satuan"                 => $id_mrp_satuan,
        "update_by_users"               => $this->session->userdata("id"),
        "update_date"                   => date("Y-m-d H:i:s")
    );
    $this->global_models->update("mrp_po_asset", array("id_mrp_task_orders_request_asset" => $id_mrp_task_orders_request_asset),$kirim);
  }
    
//    print $this->db->last_query(); die;
    $data2= array("jumlah" => $v_jumlah,
                  "total"   => $total,
                  "nilai"   => $dt_nilai_new);
    
    print json_encode($data2);
    die;

}

function get_users_penerima($status = 0){
    
        $where = "WHERE id_users='{$this->session->userdata("id")}'";
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
      . " WHERE A.id_users > 1 AND A.status=1 {$qry} AND (LOWER(A.name) LIKE '%{$q}%' OR LOWER(C.title) LIKE '%{$q}%' OR LOWER(A.email) LIKE '%{$q}%')"
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
    
        $where = "WHERE id_users='{$this->session->userdata("id")}'";
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
      . " WHERE A.id_users > 1 AND A.status=1 {$qry} AND (LOWER(A.name) LIKE '%{$q}%' OR LOWER(C.title) LIKE '%{$q}%' OR LOWER(A.email) LIKE '%{$q}%')"
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
  
  private function olah_task_order_code(&$kode){
    $this->load->helper('string');
    $kode_random = random_string('alnum', 6);
    $st_upper = strtoupper($kode_random);
    $kode = "TO".$st_upper;
    $cek = $this->global_models->get_field("mrp_task_orders", "id_mrp_task_orders", array("code" => $kode));
    if($cek > 0){
      $this->olah_task_order_code($kode);
    }
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
  
  
  private function olah_purchase_order_code(&$kode){
    $this->load->helper('string');
    $kode_random = random_string('alnum', 6);
    $st_upper = strtoupper($kode_random);
    $kode = "PO".$st_upper;
    $cek = $this->global_models->get_field("mrp_po", "id_mrp_po", array("code" => $kode));
    if($cek > 0){
      $this->olah_purchase_order_code($kode);
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