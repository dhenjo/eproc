<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mrp_master extends MX_Controller {
    
  function __construct() {      
    $this->menu = $this->cek();
  }
 
  function delete_proses_file($id_file_xls = 0){
      $kirim = array(
                "status"                => 3,
                "update_date"           => date("Y-m-d H:i:s"),
                "update_by_users"       => $this->session->userdata('id')
          );
    $this->global_models->update("file_xls", array("id_file_xls" => "{$id_file_xls}"), $kirim);
    $name_files = $this->global_models->get_field("file_xls","file",array("id_file_xls" => "{$id_file_xls}"));
    if($name_files){
    $files = "files/antavaya/upload_xls/".$name_files;
    unlink($files);
    }
   $this->session->set_flashdata('success', 'Data Sudah Di Delete');
   $id_mrp_supplier = $this->global_models->get_field("file_xls","id_mrp_supplier",array("id_file_xls" => "{$id_file_xls}"));
    redirect("mrp/mrp-master/upload-file-xls/{$id_mrp_supplier}");
  }
  function supplier(){
    
   $css = "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/datatables/dataTables.bootstrap.css' rel='stylesheet' type='text/css' />"
      . "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/jQueryUI/jquery-ui-1.10.3.custom.min.css' rel='stylesheet' type='text/css' />";
    
    $foot = ""
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/jquery-ui-1.10.3.min.js' type='text/javascript'></script>"
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/datatables/jquery.dataTables.js' type='text/javascript'></script>"
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/datatables/dataTables.bootstrap.js' type='text/javascript'></script>";
	  
    $foot .= "<script>"
      . '$(function() {'
        . 'var table = '
        . '$("#tableboxy").dataTable({'
          . '"order": [[ 1, "asc" ]],'
//          . "'iDisplayLength': 20"
        . '});'
      
        . 'ambil_data(table, 0);'
      
      . '});'
      
      . 'function ambil_data(table, mulai){'
        . '$.post("'.site_url("mrp/mrp-ajax/get-mrp-master-supplier").'/"+mulai, function(data){'
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
    
     $pst = $this->input->post(NULL);
      if($pst){
    
        $newdata = array(
            'supplier_search_name'                   => $pst['supplier_search_name'],
            'supplier_search_pic'                    => $pst['supplier_search_pic'],
            'supplier_search_phone'                  => $pst['supplier_search_phone'],
            'supplier_search_fax'                    => $pst['supplier_search_fax'],
            'supplier_search_email'                  => $pst['supplier_search_email'],
            'supplier_search_website'                  => $pst['supplier_search_website'],
            'supplier_search_address'                => $pst['supplier_search_address'],
          );
          $this->session->set_userdata($newdata);
    }
    
    
    $this->template->build('mrp-master/supplier', 
      array(
            'url'         => base_url()."themes/".DEFAULTTHEMES."/",
            'menu'        => "mrp/mrp-master/supplier",
            'title'       => lang("Supplier"),
            'foot'          => $foot,
            'css'           => $css
          ));
    $this->template
      ->set_layout('default')
      ->build('mrp-master/supplier');
  }
  
  function add_supplier($id_mrp_supplier){
    if(!$this->input->post(NULL)){
      $detail = $this->global_models->get("mrp_supplier", array("id_mrp_supplier" => $id_mrp_supplier));
      
      $this->template->build("mrp-master/add-supplier", 
        array(
              'url'         => base_url()."themes/".DEFAULTTHEMES."/",
              'menu'        => 'mrp/mrp-master/supplier',
              'title'       => lang("Add Supplier"),
              'detail'      => $detail,
              'breadcrumb'  => array(
                    "Supplier"  => "mrp/mrp-master/supplier"
                ),
            ));
      $this->template
        ->set_layout('form')
        ->build("mrp-master/add-supplier");
    }
    else{
      $pst = $this->input->post(NULL);
      
      if($pst['id_detail']){
        $kirim = array(
            "name"                      => $pst['name'],
            "phone"                     => $pst['phone'],
            "fax"                       => $pst['fax'],
            "email"                     => $pst['email'],
            "website"                   => $pst['website'],
            "address"                   => $pst['address'],
            "pic"                       => $pst['pic'],
            "update_by_users"           => $this->session->userdata("id"),
            "update_date"               => date("Y-m-d H:i:s")
        );
        $id_supplier = $this->global_models->update("mrp_supplier", array("id_mrp_supplier" => $pst['id_detail']),$kirim);
      }
      else{
        $kirim = array(
            "name"                      => $pst['name'],
            "phone"                     => $pst['phone'],
            "fax"                       => $pst['fax'],
            "email"                     => $pst['email'],
            "website"                   => $pst['website'],
            "address"                   => $pst['address'],
            "pic"                       => $pst['pic'],
            "status"                    => 1,
            "create_by_users"           => $this->session->userdata("id"),
            "create_date"               => date("Y-m-d H:i:s")
        );
        
        $id_supplier = $this->global_models->insert("mrp_supplier", $kirim);
      }
      if($id_supplier){
        $this->session->set_flashdata('success', 'Data tersimpan');
      }
      else{
        $this->session->set_flashdata('notice', 'Data tidak tersimpan');
      }
      redirect("mrp/mrp-master/supplier");
    }
  }
  
  function brand(){
    
   $css = "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/datatables/dataTables.bootstrap.css' rel='stylesheet' type='text/css' />"
      . "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/jQueryUI/jquery-ui-1.10.3.custom.min.css' rel='stylesheet' type='text/css' />";
    
    $foot = ""
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/jquery-ui-1.10.3.min.js' type='text/javascript'></script>"
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/datatables/jquery.dataTables.js' type='text/javascript'></script>"
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/datatables/dataTables.bootstrap.js' type='text/javascript'></script>";
	  
    $foot .= "<script>"
      . '$(function() {'
        . 'var table = '
        . '$("#tableboxy").dataTable({'
          . '"order": [[ 1, "asc" ]],'
//          . "'iDisplayLength': 20"
        . '});'
      
        . 'ambil_data(table, 0);'
      
      . '});'
      
      . 'function ambil_data(table, mulai){'
        . '$.post("'.site_url("mrp/mrp-ajax/get-mrp-master-brand").'/"+mulai, function(data){'
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
    
     $pst = $this->input->post(NULL);
      if($pst){
    
        $newdata = array(
            'brand_search_title'                   => $pst['brand_search_title'],
            'brand_search_code'                    => $pst['brand_search_code'],
            
          );
          $this->session->set_userdata($newdata);
    }
    
    
    $this->template->build('mrp-master/brand', 
      array(
            'url'         => base_url()."themes/".DEFAULTTHEMES."/",
            'menu'        => "mrp/mrp-master/brand",
            'title'       => lang("Brand"),
            'foot'          => $foot,
            'css'           => $css
          ));
    $this->template
      ->set_layout('default')
      ->build('mrp-master/brand');
  }
  
  function add_brand($id_mrp_brand){
    if(!$this->input->post(NULL)){
      $detail = $this->global_models->get("mrp_brand", array("id_mrp_brand" => $id_mrp_brand));
      
      $this->template->build("mrp-master/add-brand", 
        array(
              'url'         => base_url()."themes/".DEFAULTTHEMES."/",
              'menu'        => 'mrp/mrp-master/brand',
              'title'       => lang("Add Brand"),
              'detail'      => $detail,
              'breadcrumb'  => array(
                    "Brand"  => "mrp/mrp-master/brand"
                ),
            ));
      $this->template
        ->set_layout('form')
        ->build("mrp-master/add-brand");
    }
    else{
      $pst = $this->input->post(NULL);
      
      if($pst['status'] == 1){
          $status = 1;
      }else{
          $status = 2;
      }
      if($pst['id_detail']){
        $kirim = array(
            "title"                    => $pst['title'],
            "code"                     => $pst['code'],
            "status"                   => $status,
            "update_by_users"           => $this->session->userdata("id"),
            "update_date"               => date("Y-m-d H:i:s")
        );
        $id_supplier = $this->global_models->update("mrp_brand", array("id_mrp_brand" => $pst['id_detail']),$kirim);
      }
      else{
        $kirim = array(
            "title"                    => $pst['title'],
            "code"                     => $pst['code'],
            "status"                   => $status,
            "create_by_users"           => $this->session->userdata("id"),
            "create_date"               => date("Y-m-d H:i:s")
        );
        
        $id_supplier = $this->global_models->insert("mrp_brand", $kirim);
      }
      if($id_supplier){
        $this->session->set_flashdata('success', 'Data tersimpan');
      }
      else{
        $this->session->set_flashdata('notice', 'Data tidak tersimpan');
      }
      redirect("mrp/mrp-master/brand");
    }
  }
  
  function satuan($group = 0,$sort = 0){
    
   $css = "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/datatables/dataTables.bootstrap.css' rel='stylesheet' type='text/css' />"
      . "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/jQueryUI/jquery-ui-1.10.3.custom.min.css' rel='stylesheet' type='text/css' />";
    
    $foot = ""
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/jquery-ui-1.10.3.min.js' type='text/javascript'></script>"
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/datatables/jquery.dataTables.js' type='text/javascript'></script>"
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/datatables/dataTables.bootstrap.js' type='text/javascript'></script>";
	  
    $foot .= "<script>"
      . '$(function() {'
        . 'var table = '
        . '$("#tableboxy").dataTable({'
          . '"order": [[ 1, "asc" ]],'
//          . "'iDisplayLength': 20"
        . '});'
      
        . 'ambil_data(table, 0);'
      
      . '});'
      
      . 'function ambil_data(table, mulai){'
        . '$.post("'.site_url("mrp/mrp-ajax/get-mrp-master-satuan/{$group}/{$sort}").'/"+mulai, function(data){'
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
    $type = array(0 => "- Pilih -",1 => "Bukan Terkecil", 2 => "Terkecil");
//    $list = $this->global_models->get_query("SELECT B.*, A.title AS ayah
//      FROM mrp_satuan AS A
//      RIGHT JOIN mrp_satuan AS B ON A.id_mrp_satuan = B.parent
//    WHERE B.parent = '{$parent}'
//      GROUP BY B.id_mrp_satuan");
      
     $pst = $this->input->post(NULL);
      if($pst){
    
        $newdata = array(
            'satuan_search_title'                   => $pst['satuan_search_title'],
            'satuan_search_type'                    => $pst['satuan_search_type'],
            'satuan_search_nilai'                    => $pst['satuan_search_nilai'],
            
          );
          $this->session->set_userdata($newdata);
    }
    
    
    $this->template->build('mrp-master/satuan', 
      array(
            'url'         => base_url()."themes/".DEFAULTTHEMES."/",
            'menu'        => "mrp/mrp-master/satuan",
            'title'       => lang("Satuan"),
            'foot'          => $foot,
            'css'           => $css,
            'group'         => $group,
            'sort'          => $sort,
//            'detail'        => $list,
            'type'          => $type
          ));
    $this->template
      ->set_layout('default')
      ->build('mrp-master/satuan');
  }
  
  function add_satuan($group = 0,$sort = 0,$id_mrp_satuan = 0){
    if(!$this->input->post(NULL)){
      $detail = $this->global_models->get("mrp_satuan", array("id_mrp_satuan" => $id_mrp_satuan));
//      $kate = $this->global_models->get_dropdown("mrp_satuan", "id_mrp_satuan", "title", TRUE);
      $type = array(1 => "Bukan Terkecil", 2 => "Terkecil");
      $this->template->build("mrp-master/add-satuan", 
        array(
              'url'         => base_url()."themes/".DEFAULTTHEMES."/",
              'menu'        => 'mrp/mrp-master/satuan',
              'title'       => lang("Add Satuan"),
              'detail'      => $detail,
              'kate'        => $kate,
              'parent'      => $parent,
              'type'        => $type,
              'breadcrumb'  => array(
                    "Satuan"  => "mrp/mrp-master/satuan"
                ),
            ));
      $this->template
        ->set_layout('form')
        ->build("mrp-master/add-satuan");
    }
    else{
      $pst = $this->input->post(NULL);
      
       if($this->input->post("id_detail", TRUE)){
        $kirim = array(
                "title"                 => $pst['title'],
                "type"                  => $pst['type'],
                "nilai"                 => $pst['nilai'],
                "note"                  => $pst['note'],
                "update_date"           => date("Y-m-d H:i:s"),
                "update_by_users"       => $this->session->userdata('id')
          );
        $id_satuan = $this->global_models->update("mrp_satuan", array("id_mrp_satuan" => $this->input->post("id_detail", TRUE)), $kirim);
      }
      else{
       $dt_group = $this->global_models->get_field("mrp_satuan", "max(group_satuan)");
        
          if($group == ""){
              $group2 = $dt_group + 1;
          }else{
              $group2 = $group;
          }
          $dt_sort = $this->global_models->get_field("mrp_satuan", "max(sort)",array("group_satuan" => "{$group2}","status" => 1));
          $sort2 = $dt_sort + 1;
        
        if($pst['title']){
          $kirim = array(
              "title"                 => $pst['title'],
              "type"                  => $pst['type'],
              "nilai"                 => $pst['nilai'],
              "note"                  => $pst['note'],
              "group_satuan"          => $group2,
              "sort"                  => $sort2,
              "status"                => 1,
              "create_by_users"       => $this->session->userdata('id'),
              "create_date"           => date("Y-m-d H:i:s"),
          );
          
            if($sort >= $dt_sort){
                $id_satuan = $this->global_models->insert("mrp_satuan", $kirim);
           }else{
               $this->session->set_flashdata('notice', 'Data tidak tersimpan');
               if($group > 0 AND $sort > 0){
                    redirect("mrp/mrp-master/satuan/{$group}/{$sort}");
                }else{
                    redirect("mrp/mrp-master/satuan");
                }
           }
        }
      }
      if($id_satuan){
        $this->session->set_flashdata('success', 'Data tersimpan');
      }
      else{
        $this->session->set_flashdata('notice', 'Data tidak tersimpan');
      }
      if($group > 0 AND $sort > 0){
          redirect("mrp/mrp-master/satuan/{$group}/{$sort}");
      }else{
          redirect("mrp/mrp-master/satuan");
      }
      
    }
  }
  
   public function delete_satuan($group,$sort,$id){
       
        $kirim = array(
                "status"                => 2,
                "update_date"           => date("Y-m-d H:i:s"),
                "update_by_users"       => $this->session->userdata('id')
          );
      
    if($this->global_models->update("mrp_satuan", array("id_mrp_satuan" => $id), $kirim)){
      $this->session->set_flashdata('success', 'Data terhapus');
    }
    else{
      $this->session->set_flashdata('notice', 'Data tidak terhapus');
    }
     if($parent > 0){
          redirect("mrp/mrp-master/satuan/{$parent}");
      }else{
          redirect("mrp/mrp-master/satuan");
      }
      
  }
  
  function delete_supplier($id_mrp_supplier = 0){
      
      $kirim = array(
            "status"                    => 3,
            "update_by_users"           => $this->session->userdata("id"),
            "update_date"               => date("Y-m-d H:i:s")
        );
        $this->global_models->update("mrp_supplier", array("id_mrp_supplier" => "{$id_mrp_supplier}"),$kirim);
        $this->global_models->update("mrp_supplier_inventory", array("id_mrp_supplier" => "{$id_mrp_supplier}"),$kirim);
        $this->session->set_flashdata('success', 'Data terhapus');
        redirect("mrp/mrp-master/supplier/{$id_mrp_supplier}");
        
    }
    
  function delete_supplier_inventory($id_mrp_supplier = 0, $id_mrp_supplier_inventory = 0){
      $kirim = array(
            "status"                    => 3,
            "update_by_users"           => $this->session->userdata("id"),
            "update_date"               => date("Y-m-d H:i:s")
        );
        $id_type_inventory = $this->global_models->update("mrp_supplier_inventory", array("id_mrp_supplier_inventory" => "{$id_mrp_supplier_inventory}"),$kirim);
        $this->session->set_flashdata('success', 'Data terhapus');
        redirect("mrp/mrp-master/supplier-inventory/{$id_mrp_supplier}/{$id_mrp_supplier_inventory}");
        
    }
  
  function supplier_inventory($id_mrp_supplier) {
     
   $css = "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/datatables/dataTables.bootstrap.css' rel='stylesheet' type='text/css' />"
      . "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/jQueryUI/jquery-ui-1.10.3.custom.min.css' rel='stylesheet' type='text/css' />";
    
    $foot = ""
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/jquery-ui-1.10.3.min.js' type='text/javascript'></script>"
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/datatables/jquery.dataTables.js' type='text/javascript'></script>"
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/datatables/dataTables.bootstrap.js' type='text/javascript'></script>";
	  
    $foot .= "<script>"
      . '$(function() {'
        . 'var table = '
        . '$("#tableboxy").dataTable({'
          . '"order": [[ 1, "asc" ]],'
//          . "'iDisplayLength': 20"
        . '});'
      
        . "ambil_data(table, 0);"
      
      . '});'
      
      . 'function ambil_data(table, mulai){'
        . '$.post("'.site_url("mrp/mrp-ajax/get-mrp-master-supplier-inventory/".$id_mrp_supplier."/").'/"+mulai, function(data){'
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
    
     $pst = $this->input->post(NULL);
      if($pst){
  
        $newdata = array(
            'supplier_inventory_search_harga'                    => $pst['supplier_inventory_search_harga'],
            'supplier_inventory_search_nama'                  => $pst['supplier_inventory_search_nama'],
          
          );
          $this->session->set_userdata($newdata);
          
           if($pst['export']){
               $nama_supplier = $this->global_models->get_field("mrp_supplier", "name", 
                    array("id_mrp_supplier" => $id_mrp_supplier));
            $this->load->model('mrp/mmrp_master');
            $this->mmrp_master->export_xls("Supplier-Inventory-{$nama_supplier}",$id_mrp_supplier);
        }
    }
    
    $this->template->build('mrp-master/supplier-inventory', 
      array(
            'url'         => base_url()."themes/".DEFAULTTHEMES."/",
            'menu'        => "mrp/mrp-master/supplier",
            'title'       => lang("Supplier Inventory ".$this->global_models->get_field("mrp_supplier", "name", 
                    array("id_mrp_supplier" => $id_mrp_supplier))),
            'breadcrumb'    => array(
            "Supplier"  => "mrp/mrp-master/supplier/"
            ),
            'foot'          => $foot,
            'css'           => $css,
            'id_mrp_supplier' => $id_mrp_supplier,
            
          ));
    $this->template
      ->set_layout('default')
      ->build('mrp-master/supplier-inventory');
  }
  
    function proses_file($id_file_xls){
      
    $detail = $this->global_models->get("file_xls", array("id_file_xls" => $id_file_xls));
    $this->load->library('excel_reader');
    
    if($detail[0]->id_file_xls > 0){

    $file = "./files/antavaya/upload_xls/".$detail[0]->file;

    $this->excel_reader->read($file);
    $worksheet = $this->excel_reader->sheets[0];
    $numRows = $worksheet['numRows']; // ex: 14
    $numCols = $worksheet['numCols']; // ex: 4
    $cells = $worksheet['cells']; // the 1
    
//          print "<pre>";
//      print_r($worksheet); 
//      print "</pre>"; die;
//      $data = @file_get_contents($file);
//      $Data = explode(PHP_EOL, $data);
//     $this->debug($Data, true); die;
    
    for($i=4; $i <= $numRows; $i++){
    $name_umum        = trim(strtolower($cells[$i][1]));
    $name_spesifik    = trim(strtolower($cells[$i][2]));
    $type             = trim(strtolower($cells[$i][3]));
    $jenis            = trim(strtolower($cells[$i][4]));
    $brand            = trim(strtolower($cells[$i][5]));
    $satuan           = trim(strtolower($cells[$i][6]));
    $harga            = trim($cells[$i][7]);
    if($name_umum){  
    $id_mrp_satuan = $this->global_models->get_field("mrp_satuan","id_mrp_satuan",array("LOWER(title)" =>$satuan,"status" => 1));
          
    if($id_mrp_satuan){
        $id_mrp_satuan = $id_mrp_satuan;
    }else{
        $dt_group = $this->global_models->get_field("mrp_satuan", "max(group_satuan)");
        $group2 = $dt_group + 1;

        $kirim = array(
        "title"                 => trim($cells[$i][6]),
        "type"                  => 2,
        "nilai"                 => 1,
        "note"                  => "Satuan Terkecil",
        "group_satuan"          => $group2,
        "sort"                  => 1,
        "status"                => 1,
        "create_by_users"       => $this->session->userdata('id'),
        "create_date"           => date("Y-m-d H:i:s"),
       );
    $id_mrp_satuan = $this->global_models->insert("mrp_satuan", $kirim);
    }
    
    $id_mrp_brand = $this->global_models->get_field("mrp_brand","id_mrp_brand",array("LOWER(title)" =>$brand,"status" => 1));
    
    if($id_mrp_brand){
        $id_mrp_brand = $id_mrp_brand;
    }else{
        $kirim = array(
            "title"                    => trim($cells[$i][5]),
            "code"                     => trim($cells[$i][5]),
            "status"                   => 1,
            "create_by_users"           => $this->session->userdata("id"),
            "create_date"               => date("Y-m-d H:i:s")
        );
    $id_mrp_brand = $this->global_models->insert("mrp_brand", $kirim);
    }
    
    $arr_jenis = array("habis pakai" => "1", "habispakai" => "1", "asset" => "2", "aset" => "2");
    $dt_jenis = $arr_jenis[$jenis];
    
    if($dt_jenis){
        $dt_jenis = $dt_jenis;
    }else{
        $dt_jenis = 1;
    }
    
    $id_mrp_type_inventory = $this->global_models->get_field("mrp_type_inventory","id_mrp_type_inventory",array("LOWER(code)" =>$type,"status" => 1));
     
    if($id_mrp_type_inventory){
        $id_mrp_type_inventory = $id_mrp_type_inventory;
    }else{
        $id_mrp_type_inventory = 6;
    }
    
    $id_mrp_inventory_umum = $this->global_models->get_field("mrp_inventory_umum","id_mrp_inventory_umum",array("LOWER(name)" =>$name_umum,"id_mrp_type_inventory" =>$id_mrp_type_inventory,"status" => 1));
    if($id_mrp_inventory_umum){
        $id_mrp_inventory_umum = $id_mrp_inventory_umum;
    }else{
        $kirim = array(
            "id_mrp_type_inventory"         => $id_mrp_type_inventory,
            "name"                          => trim($cells[$i][1]),
            "code"                          => trim($cells[$i][1]),
            "status"                        => 1,
            "create_by_users"               => $this->session->userdata("id"),
            "create_date"                   => date("Y-m-d H:i:s")
        );
        $id_mrp_inventory_umum = $this->global_models->insert("mrp_inventory_umum", $kirim);
    }
    
    $id_mrp_inventory_spesifik = $this->global_models->get_field("mrp_inventory_spesifik","id_mrp_inventory_spesifik",
    array("LOWER(title)" =>$name_spesifik,"id_mrp_inventory_umum" =>$id_mrp_inventory_umum, 
        "jenis" => $dt_jenis,"id_mrp_satuan" =>$id_mrp_satuan,"id_mrp_brand" =>$id_mrp_brand,"status" => 1));
    
    if($id_mrp_inventory_spesifik){
        $id_mrp_inventory_spesifik = $id_mrp_inventory_spesifik; 
    }else{
        $kirim = array(
           "jenis"                              => $dt_jenis,
            "id_mrp_inventory_umum"             => $id_mrp_inventory_umum,
            "title"                             => trim($cells[$i][2]),
            "id_mrp_satuan"                     => $id_mrp_satuan,
            "id_mrp_brand"                      => $id_mrp_brand,
            "status"                            => 1,
            "create_by_users"                   => $this->session->userdata("id"),
            "create_date"                       => date("Y-m-d H:i:s")
        );
        $id_mrp_inventory_spesifik = $this->global_models->insert("mrp_inventory_spesifik", $kirim);
    }
    
     $mrp_supplier_inventory = $this->global_models->get("mrp_supplier_inventory",array("id_mrp_inventory_spesifik" => $id_mrp_inventory_spesifik,"id_mrp_supplier" =>$detail[0]->id_mrp_supplier,"status" => 1));
    
     if($mrp_supplier_inventory[0]->id_mrp_supplier_inventory){
         if($mrp_supplier_inventory[0]->harga != $harga OR $mrp_supplier_inventory[0]->id_mrp_inventory_spesifik != $id_mrp_inventory_spesifik){
             $kirim = array(
                "id_mrp_supplier_inventory"         => $mrp_supplier_inventory[0]->id_mrp_supplier_inventory,   
                "id_mrp_supplier"                   => $mrp_supplier_inventory[0]->id_mrp_supplier,
                "id_mrp_inventory_spesifik"         => $mrp_supplier_inventory[0]->id_mrp_inventory_spesifik,
                "harga"                             => $mrp_supplier_inventory[0]->harga,
                "tanggal"                           => $mrp_supplier_inventory[0]->tanggal,
                "status"                            => $mrp_supplier_inventory[0]->status,
                "create_by_users"                   => $this->session->userdata("id"),
                "create_date"                       => date("Y-m-d H:i:s")
             );
            $this->global_models->insert("log_mrp_supplier_inventory", $kirim);
            
            $kirim = array(
            "id_mrp_supplier"               => $detail[0]->id_mrp_supplier,
            "id_mrp_inventory_spesifik"     => $id_mrp_inventory_spesifik,
            "harga"                         => $harga,
            "tanggal"                       => date("Y-m-d H:i:s"),
            "status"                        => 1,
            "update_by_users"               => $this->session->userdata("id"),
            "update_date"                   => date("Y-m-d H:i:s")
        );
        $id_supplier_inventory = $this->global_models->update("mrp_supplier_inventory", array("id_mrp_supplier_inventory" => $mrp_supplier_inventory[0]->id_mrp_supplier_inventory),$kirim);
     
         } 
     }else{
         $kirim = array(
            "id_mrp_supplier"               => $detail[0]->id_mrp_supplier,
            "id_mrp_inventory_spesifik"     => $id_mrp_inventory_spesifik,
            "harga"                         => $harga,
            "tanggal"                       => date("Y-m-d H:i:s"),
            "status"                        => 1,
            "create_by_users"               => $this->session->userdata("id"),
            "create_date"               => date("Y-m-d H:i:s")
              );
        $id_supplier_inventory = $this->global_models->insert("mrp_supplier_inventory", $kirim);
     }
    }
        }
    
    $kirim = array(
            "status"         => 2,
            "update_by_users" => $this->session->userdata("id"),
        );
        $id_file = $this->global_models->update("file_xls", array("id_file_xls" => $detail[0]->id_file_xls),$kirim);
        
     if($id_file){
        $this->session->set_flashdata('success', 'Data tersimpan');
      }
      else{
        $this->session->set_flashdata('notice', 'Data tidak tersimpan');
      }
       redirect("mrp/mrp-master/supplier-inventory/{$detail[0]->id_mrp_supplier}");

    }
    
  }
  
    function upload_file_xls($id_mrp_supplier){
//        print "a";
//        die;
   // print_r($_FILES); die;
    if(!$this->input->post(NULL)){
        
        $css = "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/datatables/dataTables.bootstrap.css' rel='stylesheet' type='text/css' />"
      . "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/jQueryUI/jquery-ui-1.10.3.custom.min.css' rel='stylesheet' type='text/css' />";
    
    $foot = ""
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/jquery-ui-1.10.3.min.js' type='text/javascript'></script>"
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/datatables/jquery.dataTables.js' type='text/javascript'></script>"
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/datatables/dataTables.bootstrap.js' type='text/javascript'></script>";
	  
           $foot .= "<script>"
      . '$(function() {'
        . 'var table = '
        . '$("#tableboxy").dataTable({'
//          . '"order": [[ 1, "asc" ]],'
//          . "'iDisplayLength': 20"
         . ' "aaSorting": []'
        . '});'
      
        . "ambil_data(table, 0,{$id_mrp_supplier});"
      
      . '});'
      
      . 'function ambil_data(table, mulai,id_mrp_supplier){'
        . '$.post("'.site_url("mrp/mrp-ajax/get-mrp-master-list-files-upload-xls").'/"+id_mrp_supplier+"/"+mulai, function(data){'
          . '$("#loader-page").show();'
          . 'var hasil = $.parseJSON(data);'
          . 'if(hasil.status == 2){'
            . 'table.fnAddData(hasil.hasil);'
            . 'ambil_data(table, hasil.start,id_mrp_supplier);'
          . '}'
          . 'else{'
            . '$("#loader-page").hide();'
          . '}'
        . '});'
      . '}'
      
	  . "</script>"; 
           
      $this->template->build('mrp-master/upload-file/upload-xls.php', 
      array(
            'url'               => base_url()."themes/".DEFAULTTHEMES."/",
            'menu'              => "mrp/mrp-master/supplier",
            'id_mrp_supplier'   => $id_mrp_supplier,
            'title'         => lang("Upload File Supplier Inventory "),
            'breadcrumb'    => array(
            "Supplier Inventory"  => "mrp/mrp-master/supplier-inventory/".$id_mrp_supplier
            ),
           'css'         => $css,
           'foot'        => $foot
          ));
    $this->template
      ->set_layout('form')
      ->build('mrp-master/upload-file/upload-xls.php');
    }else{
      $pst = $this->input->post(NULL);
//      print "<pre>";
//      print_r($pst); 
//      print "<pre>";
//      die;
      
      $config['upload_path'] = './files/antavaya/upload_xls/';
      $config['allowed_types'] = '*';

      $this->load->library('upload', $config);
      
      if($_FILES['file']['name']){
        if (  $this->upload->do_upload('file')){
          $data = array('upload_data' => $this->upload->data());
        }
        else{
          print $this->upload->display_errors();
          print "<br /> <a href='".site_url("mrp/mrp-master/supplier-inventory/{$id_mrp_supplier}")."'>Back</a>";
          die;
        }
      }
       $kirim = array(
                "status"                                => 1,
                "id_mrp_supplier"                       => $pst["id_detail"],
                "create_by_users"                       => $this->session->userdata("id"),
                "create_date"                           => date("Y-m-d H:i:s")
                );
//      $this->debug($data_thumb, true);
      if($data['upload_data']['file_name']){
          $kirim['file'] = $data['upload_data']['file_name'];
        }
       
        $id_csv_file = $this->global_models->insert("file_xls", $kirim);
        
      if($id_csv_file){
        $this->session->set_flashdata('success', 'Data tersimpan');
      }
      else{
        $this->session->set_flashdata('notice', 'Data tidak tersimpan');
      }
      redirect("mrp/mrp-master/upload-file-xls/{$id_mrp_supplier}");
    }
  }
  
  function add_supplier_inventory($id_mrp_supplier = 0,$id_mrp_supplier_inventory = 0) {
//      $detail = $this->global_models->get_query("mrp_supplier_inventory", array("id_mrp_supplier" => $id_mrp_supplier,"id_mrp_supplier_inventory" => $id_mrp_supplier_inventory));
        
       $detail = $this->global_models->get_query("SELECT A.id_mrp_inventory_spesifik,B.name "
        . ",C.harga,C.tanggal,C.status,C.id_mrp_supplier_inventory"
        . ",E.code AS type,D.title AS brand,F.title AS satuan,A.jenis,C.note"
        . " FROM mrp_inventory_spesifik AS A"
        . " LEFT JOIN mrp_inventory_umum AS B ON A.id_mrp_inventory_umum = B.id_mrp_inventory_umum"
        . " LEFT JOIN mrp_supplier_inventory AS C ON A.id_mrp_inventory_spesifik = C.id_mrp_inventory_spesifik"        
        . " LEFT JOIN mrp_brand AS D ON A.id_mrp_brand = D.id_mrp_brand"
        . " LEFT JOIN mrp_type_inventory AS E ON B.id_mrp_type_inventory = E.id_mrp_type_inventory"
        . " LEFT JOIN mrp_satuan AS F ON A.id_mrp_satuan = F.id_mrp_satuan"    
        . " WHERE C.id_mrp_supplier_inventory = '{$id_mrp_supplier_inventory}'");
        
//        print_r($detail); die;
//      print $detail[0]->id_mrp_inventory; die;
//      $css = "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/select2.css' type='text/css' rel='stylesheet'>"
        $css = "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/jQueryUI/jquery-ui-1.10.3.custom.min.css' rel='stylesheet' type='text/css' />";
    
    $foot = "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/datepicker/datepicker3.css' rel='stylesheet' type='text/css' />"
//       . "<script type='text/javascript' src='".base_url()."themes/".DEFAULTTHEMES."/js/select2.js'></script>"
       . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/jquery.ui.autocomplete.min.js' type='text/javascript'></script>";
	  
    $foot .= "<script>"
            . "$(document).ready(function () {"
                . "$( '#start_date' ).datepicker({"
                . "showOtherMonths: true,"
                . "dateFormat: 'yy-mm-dd',"
                . "selectOtherMonths: true,"
                . "selectOtherYears: true"
                . "});";
            if($detail[0]->id_mrp_inventory){
                $foot .= " var ket_invent =$('#kate_inventory').val();"
            . " kategori_inventory_edit(ket_invent,{$detail[0]->id_mrp_inventory});";
            } 
            $foot .= "});"
            . "$(function() {"
            
            ."$('#harga').keyup(function(){"
                 ." var id=$(this).val();"
//                 . "alert(id);"
//                 ."numberformat(id);"
                 ." $('#harga').val('$' + parseFloat(total, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,').toString());"
                ."});"

                    . "$( '#inventory_spesifik' ).autocomplete({"
                  . "source: '".site_url("mrp/mrp-ajax/get-inventory-spesifik")."',"
                  . "minLength: 1,"
                  . "search  : function(){ $(this).addClass('working');},"
                  . "open    : function(){ $(this).removeClass('working');},"
                  . "select: function( event, ui ) {"
                    . "$('#id_mrp_inventory_spesifik').val(ui.item.id);"
                  . "}"
                . "});"
            . "});"
            
         ."function numberformat(x) {
            return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            }"
            
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
   num = Math.floor(num*100+0.50000000001);
   cents = num0;
   num = Math.floor(num/100).toString();
   for (var i = 0; i < Math.floor((num.length-(1+i))/3); i++)
   num = num.substring(0,num.length-(4*i+3))+'.'+
   num.substring(num.length-(4*i+3));
   return (((sign)?'':'-') + num);
}
</script>";
    
      if(!$this->input->post(NULL)){
      
//      $type = $this->global_models->get_dropdown("mrp_type_inventory", "id_mrp_type_inventory", "code", TRUE, array("status" => "1")); 
      
//      $data = $this->global_models->get_query("SELECT  A.id_mrp_supplier_inventory,A.id_mrp_supplier,A.kategori_inventory,A.harga,A.tanggal,A.status"
//        . ""
//        . " FROM mrp_supplier_inventory AS A"
//        . " WHERE A.id_mrp_supplier_inventory ='{$id_mrp_supplier_inventory}' AND A.id_mrp_supplier='{$id_mrp_supplier}'"
//        );
        
     $jenis = array("1" => "Habis Pakai", "2" => "Asset");
      $this->template->build("mrp-master/add-supplier-inventory", 
        array(
              'url'         => base_url()."themes/".DEFAULTTHEMES."/",
              'menu'        => 'mrp/mrp-master/supplier',
              'title'       => lang("Add Supplier Inventory"),
              'detail'      => $detail,
              'type'        => $type,
              'breadcrumb'  => array(
                    "Supplier Inventory"  => "mrp/mrp-master/supplier-inventory/{$id_mrp_supplier}"
                ),
                'foot'      => $foot,
                'css'       => $css,
                'id_mrp_supplier'   => $id_mrp_supplier,
                'id_mrp_supplier_inventory' => $id_mrp_supplier_inventory,            
                'jenis' => $jenis,  
//                'detail'            => $data,           
            ));
      $this->template
        ->set_layout('form')
        ->build("mrp-master/add-supplier-inventory");
    }
    else{
      $pst = $this->input->post(NULL);
       
      if($pst['status'] == 1){
          $status = 1;
      }else{
          $status = 2;
      }
      if($pst['id_detail']){
          
          
          
          $dt_update = $this->global_models->get("mrp_supplier_inventory", array("id_mrp_supplier_inventory" => $pst['id_detail']));
          if($dt_update[0]->harga != str_replace(",","",$pst['harga']) OR $dt_update[0]->id_mrp_inventory_spesifik != $pst['id_mrp_inventory_spesifik'] OR $dt_update[0]->status != $status){
              $harga = "harga Lama:".number_format($dt_update[0]->harga)."<br>Harga Baru:".$pst['harga'];
               $kirim = array(
                "id_mrp_supplier_inventory"         => $dt_update[0]->id_mrp_supplier_inventory,   
                "id_mrp_supplier"                   => $dt_update[0]->id_mrp_supplier,
                "id_mrp_inventory_spesifik"         => $dt_update[0]->id_mrp_inventory_spesifik,
                "harga"                             => $harga,
                "tanggal"                           => $dt_update[0]->tanggal,
                "status"                            => $dt_update[0]->status,
                 "note"                             => $pst['note'], 
                "create_by_users"                   => $this->session->userdata("id"),
                "create_date"                       => date("Y-m-d H:i:s")
             );
        
            $this->global_models->insert("log_mrp_supplier_inventory", $kirim);
            }
         
        $kirim = array(
            "id_mrp_supplier"               => $pst['id_mrp_supplier'],
            "id_mrp_inventory_spesifik"     => $pst['id_mrp_inventory_spesifik'],
            "harga"                         => str_replace(",","",$pst['harga']),
            "tanggal"                       => $pst['tanggal'],
            "status"                        => $status,
            "note"                          => $pst['note'],
            "update_by_users"               => $this->session->userdata("id"),
            "update_date"                   => date("Y-m-d H:i:s")
        );
        $id_supplier_inventory = $this->global_models->update("mrp_supplier_inventory", array("id_mrp_supplier_inventory" => $pst['id_detail']),$kirim);
      }
      else{
       $id_mrp_supplier_inventory = $this->global_models->get_field("mrp_supplier_inventory","id_mrp_supplier_inventory", array("id_mrp_inventory_spesifik" => $pst['id_mrp_inventory_spesifik'],"id_mrp_supplier" =>$pst['id_mrp_supplier'])); 
        
           if($pst['id_mrp_inventory_spesifik'] > 0){
               if($id_mrp_supplier_inventory < 1){
                    $kirim = array(
                    "id_mrp_supplier"               => $pst['id_mrp_supplier'],
                    "id_mrp_inventory_spesifik"      => $pst['id_mrp_inventory_spesifik'],
                    "harga"                         => str_replace(",","",$pst['harga']),
                    "tanggal"                       => $pst['tanggal'],
                    "status"                        => $status,
                    "note"                          => $pst['note'],
                    "create_by_users"           => $this->session->userdata("id"),
                    "create_date"               => date("Y-m-d H:i:s")
                    );
                    $id_supplier_inventory = $this->global_models->insert("mrp_supplier_inventory", $kirim);
               }else{
                   $this->session->set_flashdata('notice', 'Data tidak tersimpan, Inventory Spesifik sudah ada sebelumnya');
                   redirect("mrp/mrp-master/supplier-inventory/{$pst['id_mrp_supplier']}");
               }
           }else{
               $this->session->set_flashdata('notice', 'Data tidak tersimpan, Inventory Spesifik tidak boleh kosong');
               redirect("mrp/mrp-master/supplier-inventory/{$pst['id_mrp_supplier']}");
           }
        
      }
      if($id_supplier_inventory){
        $this->session->set_flashdata('success', 'Data tersimpan');
      }
      else{
        $this->session->set_flashdata('notice', 'Data tidak tersimpan');
      }
      redirect("mrp/mrp-master/supplier-inventory/{$pst['id_mrp_supplier']}");
    }
  }
  
   function type_inventory(){
    
    $list = $this->global_models->get("mrp_type_inventory");
    
    $menutable = '
      <li><a href="'.site_url("mrp/mrp-master/add-type-inventory").'"><i class="icon-plus"></i> Add New</a></li>
      ';
    $this->template->build('mrp/mrp-master/type-inventory', 
      array(
            'url'         => base_url()."themes/".DEFAULTTHEMES."/",
            'menu'        => "mrp/mrp-master/type-inventory",
            'data'        => $list,
            'title'       => lang("Master Type Inventory"),
            'menutable'   => $menutable,
          ));
    $this->template
      ->set_layout('datatables')
      ->build('mrp/mrp-master/type-inventory');
  }
  
  function history_supplier_inventory($id_mrp_supplier,$id_supplier_inventory) {
      
   $css = "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/datatables/dataTables.bootstrap.css' rel='stylesheet' type='text/css' />"
      . "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/jQueryUI/jquery-ui-1.10.3.custom.min.css' rel='stylesheet' type='text/css' />";
    
    $foot = ""
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/jquery-ui-1.10.3.min.js' type='text/javascript'></script>"
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/datatables/jquery.dataTables.js' type='text/javascript'></script>"
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/datatables/dataTables.bootstrap.js' type='text/javascript'></script>";
	  
    $foot .= "<script>"
      . '$(function() {'
        . 'var table = '
        . '$("#tableboxy").dataTable({'
          . '"order": [[ 1, "asc" ]],'
//          . "'iDisplayLength': 20"
        . '});'
      
        . "ambil_data(table, 0);"
      
      . '});'
      
      . 'function ambil_data(table, mulai){'
        . '$.post("'.site_url("mrp/mrp-ajax/get-mrp-master-history-supplier-inventory/".$id_mrp_supplier."/{$id_supplier_inventory}").'/"+mulai, function(data){'
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
    
     $pst = $this->input->post(NULL);
      if($pst){
    
        $newdata = array(
            'history_supplier_inventory_search_kategori_barang'     => $pst['history_supplier_inventory_search_kategori_barang'],
            'history_supplier_inventory_search_harga'               => $pst['history_supplier_inventory_search_harga'],
            'history_supplier_inventory_search_nama'                => $pst['history_supplier_inventory_search_nama'],
          
          );
          $this->session->set_userdata($newdata);
    }
    
    $kategori_invetory = array(0 => "- Pilih -",1 => "Umum", 2 => "Spesifik");
    $this->template->build('mrp-master/history-supplier-inventory', 
      array(
            'url'         => base_url()."themes/".DEFAULTTHEMES."/",
            'menu'        => "mrp/mrp-master/supplier",
            'title'       => lang("History Supplier Inventory ".$this->global_models->get_field("mrp_supplier", "name", 
                    array("id_mrp_supplier" => $id_mrp_supplier))),
            'foot'          => $foot,
            'css'           => $css,
            'id_mrp_supplier' => $id_mrp_supplier,
            'kategori_inventory' => $kategori_invetory,
            'breadcrumb'  => array(
                    "Supplier Inventory"  => "mrp/mrp-master/supplier-inventory/{$id_mrp_supplier}"
                ),
          ));
    $this->template
      ->set_layout('default')
      ->build('mrp-master/history-supplier-inventory');
  }
  
 function add_type_inventory($id_mrp_type_inventory){
    if(!$this->input->post(NULL)){
      $detail = $this->global_models->get("mrp_type_inventory", array("id_mrp_type_inventory" => $id_mrp_type_inventory));
      
      $this->template->build("mrp-master/add-type-inventory", 
        array(
              'url'         => base_url()."themes/".DEFAULTTHEMES."/",
              'menu'        => 'mrp/mrp-master/type-inventory',
              'title'       => lang("Add Type Inventory"),
              'detail'      => $detail,
              'breadcrumb'  => array(
                    "Type Inventory"  => "mrp/mrp-master/type-inventory"
                ),
            ));
      $this->template
        ->set_layout('form')
        ->build("mrp-master/add-type-inventory");
    }
    else{
      $pst = $this->input->post(NULL);
      
      if($pst['status'] == 1){
          $status = 1;
      }else{
          $status = 2;
      }
      if($pst['id_detail']){
        $kirim = array(
            "title"                     => $pst['title'],
            "code"                      => $pst['code'],
            "iso"                       => $pst['iso'],
            "status"                    => $status,
            "update_by_users"           => $this->session->userdata("id"),
            "update_date"               => date("Y-m-d H:i:s")
        );
        $id_type_inventory = $this->global_models->update("mrp_type_inventory", array("id_mrp_type_inventory" => $pst['id_detail']),$kirim);
      }
      else{
        $kirim = array(
           "title"                      => $pst['title'],
            "code"                      => $pst['code'],
            "iso"                       => $pst['iso'],
            "status"                    => $status,
            "create_by_users"           => $this->session->userdata("id"),
            "create_date"               => date("Y-m-d H:i:s")
        );
        
        $id_type_inventory = $this->global_models->insert("mrp_type_inventory", $kirim);
      }
      if($id_type_inventory){
        $this->session->set_flashdata('success', 'Data tersimpan');
      }
      else{
        $this->session->set_flashdata('notice', 'Data tidak tersimpan');
      }
      redirect("mrp/mrp-master/type-inventory");
    }
  }
  
  function inventory_umum() {
      
   
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
          . '"order": [[ 1, "asc" ]],'
//          . "'iDisplayLength': 20"
        . '});'
      
        . 'ambil_data(table, 0);'
      
      . '});'
      
      . 'function ambil_data(table, mulai){'
        . '$.post("'.site_url("mrp/mrp-ajax/get-mrp-master-inventory-umum").'/"+mulai, function(data){'
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
    
    $type = $this->global_models->get_dropdown("mrp_type_inventory", "id_mrp_type_inventory", "code", TRUE, array("status" => "1")); 
    $jenis = array(0 => "- Pilih -",1 => "Habis Pakai", 2 => "Asset");
     $pst = $this->input->post(NULL);
      if($pst){
          
        $newdata = array(
            'inventory_umum_search_type'                   => $pst['inventory_umum_search_type'],
            'inventory_umum_search_jenis'                    => $pst['inventory_umum_search_jenis'],
            'inventory_umum_search_nama'                  => $pst['inventory_umum_search_nama'],
            'inventory_umum_search_code'                    => $pst['inventory_umum_search_code'],
           
          );
          $this->session->set_userdata($newdata);
    }
    
    
    $this->template->build('mrp-master/inventory-umum', 
      array(
            'url'         => base_url()."themes/".DEFAULTTHEMES."/",
            'menu'        => "mrp/mrp-master/inventory-umum",
            'title'       => lang("Inventory Umum"),
            'foot'          => $foot,
            'css'           => $css,
            'type'          => $type,
            'jenis'         => $jenis,
             
          ));
    $this->template
      ->set_layout('default')
      ->build('mrp-master/inventory-umum');
  }
  
  function add_inventory_umum($id_mrp_inventory_umum) {
  
      $css = "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/select2.css' type='text/css' rel='stylesheet'>"
        . "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/jQueryUI/jquery-ui-1.10.3.custom.min.css' rel='stylesheet' type='text/css' />";
    
    $foot = ""
       . "<script type='text/javascript' src='".base_url()."themes/".DEFAULTTHEMES."/js/select2.js'></script>"
       . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/jquery.ui.autocomplete.min.js' type='text/javascript'></script>";
	  
    $foot .= "<script>"
          . "$('.dropdown2').select2();"
            . "$(function() {"
            . "$( '#qty' ).autocomplete({"
                  . "source: '".site_url("mrp/mrp-ajax/qty")."',"
                  . "minLength: 1,"
                  . "search  : function(){ $(this).addClass('working');},"
                  . "open    : function(){ $(this).removeClass('working');},"
                  . "select: function( event, ui ) {"
                    . "$('#id_qty').val(ui.item.id);"
                  . "}"
                . "});"
            . "});"
	  . "</script>";   
    
        if(!$this->input->post(NULL)){
      $detail = $this->global_models->get("mrp_inventory_umum", array("id_mrp_inventory_umum" => $id_mrp_inventory_umum));
      $type = $this->global_models->get_dropdown("mrp_type_inventory", "id_mrp_type_inventory", "code", TRUE, array("status" => "1")); 
  
      $this->template->build("mrp-master/add-inventory-umum", 
        array(
              'url'         => base_url()."themes/".DEFAULTTHEMES."/",
              'menu'        => 'mrp/mrp-master/inventory-umum',
              'title'       => lang("Add Inventory Umum"),
              'detail'      => $detail,
              'type'        => $type,  
              'breadcrumb'  => array(
                    "Inventory Umum"  => "mrp/mrp-master/inventory-umum"
                ),
                'foot'      => $foot,
                'css'       => $css,
            ));
      $this->template
        ->set_layout('form')
        ->build("mrp-master/add-inventory-umum");
    }
    else{
      $pst = $this->input->post(NULL);
      
      if($pst['id_detail']){
        $kirim = array(
            "id_mrp_type_inventory"         => $pst['type'],
            "name"                          => $pst['name'],
            "code"                          => $pst['code'],
            "note"                          => $pst['note'],
            "update_by_users"               => $this->session->userdata("id"),
            "update_date"                   => date("Y-m-d H:i:s")
        );
        $id_inventory_umum = $this->global_models->update("mrp_inventory_umum", array("id_mrp_inventory_umum" => $pst['id_detail']),$kirim);
      }
      else{
        $kirim = array(
            "id_mrp_type_inventory"         => $pst['type'],
            "name"                          => $pst['name'],
            "code"                          => $pst['code'],
            "note"                          => $pst['note'],
            "status"                        => 1,
            "create_by_users"               => $this->session->userdata("id"),
            "create_date"                   => date("Y-m-d H:i:s")
        );
        
        $id_inventory_umum = $this->global_models->insert("mrp_inventory_umum", $kirim);
      }
      if($id_inventory_umum){
        $this->session->set_flashdata('success', 'Data tersimpan');
      }
      else{
        $this->session->set_flashdata('notice', 'Data tidak tersimpan');
      }
      redirect("mrp/mrp-master/inventory-umum");
    }
  }
  
   function inventory_spesifik() {
      
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
          . '"order": [[ 1, "asc" ]],'
//          . "'iDisplayLength': 20"
        . '});'
      
        . 'ambil_data(table, 0);'
      
      . '});'
      
      . 'function ambil_data(table, mulai){'
        . '$.post("'.site_url("mrp/mrp-ajax/get-mrp-master-inventory-spesifik").'/"+mulai, function(data){'
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
    $brand = $this->global_models->get_dropdown("mrp_brand", "id_mrp_brand", "title", TRUE, array("status" => "1")); 
    $jenis = array(0 => "- Pilih -",1 => "Habis Pakai", 2 => "Asset");
     $pst = $this->input->post(NULL);
      if($pst){
//          print "<pre>";
//          print_r($pst);
//          print "</pre>";
//          die;
        $newdata = array(
            'inventory_spesifik_search_nama'                        => $pst['inventory_spesifik_search_nama'],
            'inventory_spesifik_search_jenis'                       => $pst['inventory_spesifik_search_jenis'],
            'inventory_spesifik_search_brand'                        => $pst['inventory_spesifik_search_brand'],
            'inventory_spesifik_search_satuan'                        => $pst['inventory_spesifik_search_satuan'],
            
          );
          $this->session->set_userdata($newdata);
    }
//    print $this->session->userdata('inventory_spesifik_search_brand'); die;
    
    $this->template->build('mrp-master/inventory-spesifik', 
      array(
            'url'           => base_url()."themes/".DEFAULTTHEMES."/",
            'menu'          => "mrp/mrp-master/inventory-spesifik",
            'title'         => lang("Inventory Spesifik"),
            'foot'          => $foot,
            'css'           => $css,
            'type'          => $type,
            'jenis'         => $jenis,
            'brand'         => $brand,
          ));
    $this->template
      ->set_layout('default')
      ->build('mrp-master/inventory-spesifik');
  }
  
  function delete_inventory_spesifik($id_mrp_inventory_spesifik = 0){
      $kirim = array(
            "status"                    => 3,
            "update_by_users"           => $this->session->userdata("id"),
            "update_date"               => date("Y-m-d H:i:s")
        );
        $id_type_inventory = $this->global_models->update("mrp_inventory_spesifik", array("id_mrp_inventory_spesifik" => "{$id_mrp_inventory_spesifik}"),$kirim);
        $this->session->set_flashdata('success', 'Data terhapus');
        redirect("mrp/mrp-master/inventory-spesifik");
        
  }
  function add_inventory_spesifik($id_mrp_inventory_spesifik) {
      
       $css = "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/select2.css' type='text/css' rel='stylesheet'>"
        . "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/jQueryUI/jquery-ui-1.10.3.custom.min.css' rel='stylesheet' type='text/css' />";
    
    $foot = ""
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
            
            . "$( '#satuan' ).autocomplete({"
                  . "source: '".site_url("mrp/mrp-ajax/satuan")."',"
                  . "minLength: 1,"
                  . "search  : function(){ $(this).addClass('working');},"
                  . "open    : function(){ $(this).removeClass('working');},"
                  . "select: function( event, ui ) {"
                    . "$('#id_satuan').val(ui.item.id);"
                  . "}"
                . "});"
            
            . "$( '#inventory_umum' ).autocomplete({"
                  . "source: '".site_url("mrp/mrp-ajax/inventory-umum")."',"
                  . "minLength: 1,"
                  . "search  : function(){ $(this).addClass('working');},"
                  . "open    : function(){ $(this).removeClass('working');},"
                  . "select: function( event, ui ) {"
                    . "$('#id_mrp_inventory_umum').val(ui.item.id);"
                  . "}"
                . "});"
            . "});"
	  . "</script>";   
    
        if(!$this->input->post(NULL)){
      $detail = $this->global_models->get("mrp_inventory_spesifik", array("id_mrp_inventory_spesifik" => $id_mrp_inventory_spesifik,"status" => "1"));
//      $type = $this->global_models->get_dropdown("mrp_type_inventory", "id_mrp_type_inventory", "code", TRUE, array("status" => "1")); 
  
      $this->template->build("mrp-master/add-inventory-spesifik", 
        array(
              'url'         => base_url()."themes/".DEFAULTTHEMES."/",
              'menu'        => 'mrp/mrp-master/inventory-spesifik',
              'title'       => lang("Add Inventory Spesifik"),
              'detail'      => $detail,
              'type'        => $type,
              'breadcrumb'  => array(
                    "Inventory Spesifik"  => "mrp/mrp-master/inventory-spesifik"
                ),
                'foot'      => $foot,
                'css'       => $css,
            ));
      $this->template
        ->set_layout('form')
        ->build("mrp-master/add-inventory-spesifik");
    }
    else{
      $pst = $this->input->post(NULL);
//      print "<pre>";
//      print_r($pst);
//      print "</pre>";
//      die;
      if($pst['id_brand'] > 0){
          $dt_brand = strtolower($pst['brand']);
          $items = $this->global_models->get_query("
            SELECT id_mrp_brand
            FROM mrp_brand
            WHERE 
            LOWER(title) = '{$dt_brand}'
            ");
            if($items[0]->id_mrp_brand){
                $brand = $items[0]->id_mrp_brand;
            }else{
                 $kirim = array(
                    "title"                     => $pst['brand'],
                    "code"                      => $pst['brand'],
                    "status"                    => 1,
                    "create_by_users"           => $this->session->userdata("id"),
                    "create_date"               => date("Y-m-d H:i:s")
                );
                 $brand = $this->global_models->insert("mrp_brand", $kirim);
            }
      }else{
          if($pst['brand'] != ""){
           $dt_brand = strtolower($pst['brand']);
           $items = $this->global_models->get_query("
            SELECT id_mrp_brand
            FROM mrp_brand
            WHERE 
            LOWER(title) = '{$dt_brand}'
            ");
            if($items[0]->id_mrp_brand){
                $brand = $items[0]->id_mrp_brand;
            }else{
                 $kirim = array(
                    "title"                     => $pst['brand'],
                    "code"                      => $pst['brand'],
                    "status"                    => 1,
                    "create_by_users"           => $this->session->userdata("id"),
                    "create_date"               => date("Y-m-d H:i:s")
                );
                 $brand = $this->global_models->insert("mrp_brand", $kirim);
            }
          }
          
      }
      
      if($pst['id_detail']){
        $kirim = array(
            "jenis"                             => $pst['jenis'],
            "id_mrp_inventory_umum"             => $pst['id_mrp_inventory_umum'],
            "title"                             => $pst['title'],
            "id_mrp_satuan"                     => $pst['id_satuan'],
            "id_mrp_brand"                      => $brand,
            "note"                              => $pst['note'],
            "update_by_users"                   => $this->session->userdata("id"),
            "update_date"                       => date("Y-m-d H:i:s")
        );
        $id_inventory_spesifik = $this->global_models->update("mrp_inventory_spesifik", array("id_mrp_inventory_spesifik" => $pst['id_detail']),$kirim);
      }
      else{
        $kirim = array(
           "jenis"                              => $pst['jenis'],
            "id_mrp_inventory_umum"             => $pst['id_mrp_inventory_umum'],
            "title"                             => $pst['title'],
            "id_mrp_satuan"                     => $pst['id_satuan'],
            "id_mrp_brand"                      => $brand,
            "note"                              => $pst['note'],
            "status"                            => 1,
            "create_by_users"                   => $this->session->userdata("id"),
            "create_date"                       => date("Y-m-d H:i:s")
        );
        
        $id_inventory_spesifik = $this->global_models->insert("mrp_inventory_spesifik", $kirim);
      }
      if($id_inventory_spesifik){
        $this->session->set_flashdata('success', 'Data tersimpan');
      }
      else{
        $this->session->set_flashdata('notice', 'Data tidak tersimpan');
      }
      redirect("mrp/mrp-master/inventory-spesifik");
    }
  }
  
//  function list_upload_inventory_spesifik() {
//   $list = $this->global_models->get("import_mrp_inventory", array("kategori_inventory" => 2));
//      $menutable = '
//      <li><a href="'.site_url("mrp/mrp-master/upload-inventory-spesifik").'"><i class="icon-plus"></i> Upload File</a></li>
//      ';
//    $this->template->build('mrp-master/list-upload-inventory-spesifik', 
//      array(
//            'url'         => base_url()."themes/".DEFAULTTHEMES."/",
//            'menu'        => "mrp/mrp-master/inventory-spesifik",
//            'data'        => $list,
//            'title'       => lang("List Import Inventory Spesifik"),
//             'breadcrumb'    => array(
//            "Inventory Spesifik"  => "mrp/mrp-master/inventory-spesifik"
//            ),
//           'menutable'      => $menutable,
//          ));
//    $this->template
//      ->set_layout('datatables')
//      ->build('mrp-master/list-upload-inventory-spesifik');
//  }
  
//  function upload_inventory_spesifik() {
//   $list = $this->global_models->get("import_mrp_inventory", array("kategori_inventory" => 2));
//      $menutable = '
//      <li><a href="'.site_url("mrp/mrp-master/upload-inventory-spesifik").'"><i class="icon-plus"></i> Upload File</a></li>
//      ';
//    $this->template->build('mrp-master/upload-inventory-spesifik', 
//      array(
//            'url'         => base_url()."themes/".DEFAULTTHEMES."/",
//            'menu'        => "mrp/mrp-master/inventory-spesifik",
//            'data'        => $list,
//            'title'       => lang("List Import Inventory Spesifik"),
//             'breadcrumb'    => array(
//            "Inventory Spesifik"  => "mrp/mrp-master/inventory-spesifik"
//            ),
//           'menutable'      => $menutable,
//          ));
//    $this->template
//      ->set_layout('datatables')
//      ->build('mrp-master/upload-inventory-spesifik');
//  }
  
  function upload_inventory_spesifik(){
   // print_r($_FILES); die;
    if(!$this->input->post(NULL)){
      $this->template->build('mrp-master/upload-inventory-spesifik', 
      array(
            'url'         => base_url()."themes/".DEFAULTTHEMES."/",
            'menu'        => "mrp/mrp-master/inventory-spesifik",
//            'data'        => $list,
            'title'       => lang("List Import Inventory Spesifik"),
             'breadcrumb'    => array(
            "Inventory Spesifik"  => "mrp/mrp-master/list-upload-inventory-spesifik"
            ),
          ));
    $this->template
      ->set_layout('datatables')
      ->build('mrp-master/upload-inventory-spesifik');
    }else{
      $pst = $this->input->post(NULL);
//      print "<pre>";
//      print_r($pst); 
//      print "<pre>";
//      die;
      $config['upload_path'] = './files/antavaya/inventory/spesifik/';
      $config['allowed_types'] = 'xls|xlsx';

      $this->load->library('upload', $config);
      
      if($_FILES['file']['name']){
        if (  $this->upload->do_upload('file')){
          $data = array('upload_data' => $this->upload->data());
        }
        else{
          print $this->upload->display_errors();
          print "<br /> <a href='".site_url("mrp/mrp-master/list-upload-inventory-spesifik/")."'>Back</a>";
          die;
        }
      }
       $kirim = array(
                "status"                                => 1,
                "kategori_inventory"                    => 2,
                "create_by_users"                       => $this->session->userdata("id"),
                "create_date"                           => date("Y-m-d H:i:s")
                );
//      $this->debug($data_thumb, true);
      if($data['upload_data']['file_name']){
          $kirim['file'] = $data['upload_data']['file_name'];
        }
       
        $id_csv_file = $this->global_models->insert("import_mrp_inventory", $kirim);
        
      if($id_csv_file){
        $this->session->set_flashdata('success', 'Data tersimpan');
      }
      else{
        $this->session->set_flashdata('notice', 'Data tidak tersimpan');
      }
      redirect("mrp/mrp-master/list-upload-inventory-spesifik");
    }
  }
  
//function proses_import($id_import_mrp_inventory,$katagori_inventory){
//      
//if($katagori_inventory == 2){
//      $detail = $this->global_models->get("import_mrp_inventory", array("id_import_mrp_inventory" => $id_import_mrp_inventory));
//   
//      $this->load->library('excel_reader');
//   
//    if($detail[0]->id_import_mrp_inventory > 0){
//   
//        $file = "./files/antavaya/inventory/spesifik/".$detail[0]->file;
//
//      $this->excel_reader->read($file);
//    $worksheet = $this->excel_reader->sheets[0];
//    $numRows = $worksheet['numRows']; // ex: 14
//    $numCols = $worksheet['numCols']; // ex: 4
//    $cells = $worksheet['cells']; // the 1
//
////    print "<pre>";
////      print_r($worksheet); 
////      print "</pre>"; die;
////      $data = @file_get_contents($file);
////      $Data = explode(PHP_EOL, $data);
////     $this->debug($Data, true); die;
//        for($i=4; $i <= $numRows; $i++){
//          
//            $dt_type = strtolower($cells[$i][1]);
//           $items_type = $this->global_models->get_query("
//            SELECT id_mrp_type_inventory
//            FROM mrp_type_inventory
//            WHERE 
//            LOWER(code) = '{$dt_type}'
//            ");
//            
//            if($items_type[0]->id_mrp_type_inventory){
//                $type = $items_type[0]->id_mrp_type_inventory;
//            }else{
//                 $kirim = array(
//                    "title"                     => $cells[$i][1],
//                    "code"                      => $cells[$i][1],
//                    "status"                    => 1,
//                    "create_by_users"           => $this->session->userdata("id"),
//                    "create_date"               => date("Y-m-d H:i:s")
//                );
//                 $type = $this->global_models->insert("mrp_type_inventory", $kirim);
//            }
//            $dt_jenis = strtolower($cells[$i][2]);
//            $jenis = array("habis pakai" => 1, "asset" => 2);
//            
//             $dt_brand = strtolower($cells[$i][4]);
//           $items_brand = $this->global_models->get_query("
//            SELECT id_mrp_brand
//            FROM mrp_brand
//            WHERE 
//            LOWER(title) = '{$dt_brand}'
//            ");
//            
//            if($items_brand[0]->id_mrp_brand){
//                $brand = $items_brand[0]->id_mrp_brand;
//            }else{
//                 $kirim = array(
//                    "title"                     => $cells[$i][4],
//                    "code"                      => $cells[$i][4],
//                    "status"                    => 1,
//                    "create_by_users"           => $this->session->userdata("id"),
//                    "create_date"               => date("Y-m-d H:i:s")
//                );
//                 $brand = $this->global_models->insert("mrp_brand", $kirim);
//            }
//            
//             $dt_qty = strtolower($cells[$i][5]);
//           $items_qty = $this->global_models->get_query("
//            SELECT id_mrp_qty
//            FROM mrp_qty
//            WHERE 
//            LOWER(title) = '{$dt_qty}'
//            ");
//            
//            if($items_qty[0]->id_mrp_qty){
//                $qty = $items_qty[0]->id_mrp_qty;
//            }else{
//                 $kirim = array(
//                    "title"                     => $cells[$i][5],
//                    "code"                      => $cells[$i][5],
//                    "status"                    => 1,
//                    "create_by_users"           => $this->session->userdata("id"),
//                    "create_date"               => date("Y-m-d H:i:s")
//                );
//                 $qty = $this->global_models->insert("mrp_qty", $kirim);
//            }
//            
//            $kirim = array(
//               "id_mrp_type_inventory"         => $type,
//               "jenis"                         => $jenis[$dt_jenis],
//               "name"                          => $cells[$i][3],
//               "code"                          => $cells[$i][3],
//               "id_mrp_brand"                  => $brand,
//               "id_mrp_qty"                    => $qty,
//               "kategori_inventory"            => 2,
//               "status"                        => 1,
//               "create_by_users"               => $this->session->userdata("id"),
//               "create_date"                   => date("Y-m-d H:i:s")
//           );
//
//           $id_inventory = $this->global_models->insert("mrp_inventory", $kirim);
//        
//          
//        }
//        $kirim = array(
//            "status"         => 2,
//            "update_by_users" => $this->session->userdata("id"),
//            "update_date"     => date("Y-m-d H:i:s")
//        );
//        $id_import_product_2 = $this->global_models->update("import_mrp_inventory", array("id_import_mrp_inventory" => $id_import_mrp_inventory),$kirim);
//        
//     if($id_import_product_2){
//        $this->session->set_flashdata('success', 'Data tersimpan');
//      }
//      else{
//        $this->session->set_flashdata('notice', 'Data tidak tersimpan');
//      }
//       redirect("mrp/mrp-master/list-upload-inventory-spesifik/");
//    
//    }
//}  
//    
//    
//  }
 
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */