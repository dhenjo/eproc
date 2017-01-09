<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mrp_ajax_to extends MX_Controller {
    
  function __construct() {      
    $this->menu = $this->cek();
  }
  
  function get_exchange_task_orders($id_mrp_task_orders = 0){
     $to_request= $this->global_models->get("mrp_task_orders_request",array("id_mrp_task_orders" => "{$id_mrp_task_orders}"));
     
     $id_po =$this->global_models->get_query("SELECT B.id_mrp_po "
         . "FROM mrp_task_orders_request_asset AS A"
         . " LEFT JOIN mrp_po_asset AS B ON A.id_mrp_task_orders_request_asset = B.id_mrp_task_orders_request_asset"
         . " WHERE A.id_mrp_task_orders='{$id_mrp_task_orders}' "
         . " GROUP BY B.id_mrp_po");
         
        if(is_array($id_po)){
            foreach ($id_po as $p) {
               $this->load->model('mrp/mmrp');
               $this->mmrp->cancel_po($p->id_mrp_po);
           }
        } 
        
     $kirim = array(
            "jumlah"                    => 0,  
            "update_by_users"           => $this->session->userdata("id"),
            "update_date"               => date("Y-m-d H:i:s")
        );
       $this->global_models->update("mrp_task_orders_request_asset", array("id_mrp_task_orders" => "{$id_mrp_task_orders}"),$kirim);
       $jumlah[] = 0;
//       print $this->db->last_query();
//            die;
     foreach ($to_request as $key => $v) {
//            $request = $this->global_models->get("mrp_request_asset",array("id_mrp_request" => "{$v->id_mrp_request}"));
        $request = $this->global_models->get_query("SELECT A.jumlah,A.id_mrp_inventory_spesifik,B.id_mrp_satuan "
        . " FROM mrp_request_asset AS A"
        . " LEFT JOIN mrp_inventory_spesifik AS B ON A.id_mrp_inventory_spesifik = B.id_mrp_inventory_spesifik"
        . " WHERE A.id_mrp_request = '{$v->id_mrp_request}'"
//        . " GROUP BY C.id_mrp_po"
//        . " ORDER BY A.id_mrp_po ASC"
        );
            foreach ($request as $r){
              $jumlah[$r->id_mrp_inventory_spesifik] = $jumlah[$r->id_mrp_inventory_spesifik] + $r->jumlah;
              
              $id_mrp_spesifik = $this->global_models->get_field("mrp_task_orders_request_asset","id_mrp_inventory_spesifik",array("id_mrp_task_orders" => "{$id_mrp_task_orders}","id_mrp_inventory_spesifik" => "{$r->id_mrp_inventory_spesifik}"));
           if($id_mrp_spesifik > 0){
               $kirim = array(
                "jumlah"                    => $jumlah[$r->id_mrp_inventory_spesifik],  
                "update_by_users"           => $this->session->userdata("id"),
                "update_date"               => date("Y-m-d H:i:s")
                );
                $this->global_models->update("mrp_task_orders_request_asset", array("id_mrp_task_orders" => "{$id_mrp_task_orders}","id_mrp_inventory_spesifik" => "{$r->id_mrp_inventory_spesifik}"),$kirim);
           }else{
               $kirim = array(
                    "id_mrp_task_orders"            => $id_mrp_task_orders,
                    "id_mrp_inventory_spesifik"     => $r->id_mrp_inventory_spesifik,
                    "jumlah"                        => $jumlah[$r->id_mrp_inventory_spesifik],
                    "id_mrp_satuan"                 => $r->id_mrp_satuan,
                    "status"                        => 1,
                    "create_by_users"               => $this->session->userdata("id"),
                    "create_date"                   => date("Y-m-d H:i:s")
                    );
            $this->global_models->insert("mrp_task_orders_request_asset", $kirim);
           }
//       print $this->db->last_query();
//       die;
          }
       }
       $this->global_models->delete("mrp_task_orders_request_asset", array("id_mrp_task_orders" => "{$id_mrp_task_orders}","jumlah" => "0"));
     
       $this->session->set_flashdata('success', 'Perubahan data');
       }
  
  function get_task_mrp_request_pengadaan($dttype =0,$start = 0){
      
    $where = "WHERE A.status = 3";   
    
    if($this->session->userdata('create_to_search_type')){
        $where .= " AND A.type_inventory = {$this->session->userdata('create_to_search_type')}";
    }
    
    if($this->session->userdata('create_to_search_company')){
        $where .= " AND F.id_hr_company = {$this->session->userdata('create_to_search_company')}";
    }
      
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
        . " ORDER BY A.id_mrp_request DESC"
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
  
    $type = $this->global_models->get_dropdown("mrp_type_inventory", "id_mrp_type_inventory", "code", TRUE);
     
    foreach ($data AS $da){
        
//        if($da->type_inventory == 1){
//            $dt_link = "cetakan";
//        }else{
//            $dt_link = "atk";
//        }
        $dt_approved = "";
        if($da->nama_approved){
            $dt_approved = "[Approved By ".$da->nama_approved."]";
        }
        
        
//      $type = array(1 => "Cetakan", 2 => "ATK");
      if($dttype == 1){
          $hasil[] = array(
        $this->form_eksternal->form_checkbox('status', $da->id_mrp_request, FALSE,'class="dt_id"'),
//        "<a href='".site_url("mrp/add-request-pengadaan-{$dt_link}/{$da->id_mrp_request}")."'>".$da->ro_kode."</a>",  
        "<a data-toggle='collapse' data-parent='#accordion'  href='#collapseOne' onclick='coba_data({$da->type_inventory},{$da->id_mrp_request});'>".$da->ro_kode."</a>",
        $type[$da->type_inventory],  
        $da->nama_pegawai."<br>".$dt_approved,
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
      }elseif($dttype == 2 OR $dttype == 3 OR $dttype == 4 OR $dttype == 5 OR ($dttype >=7 OR $dttype <=9)){
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
       . " WHERE A.status !=12"
        . " ORDER BY A.id_mrp_task_orders DESC"
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
        3 => "<span class='label bg-green'>Approved PO</span>", 4 =>"<span class='label bg-green'>Sent PO</span>",
        9 =>"<span class='label bg-green'>Closed Task Orders</span>", 12 => "<span class='label bg-red'>Cancel Task Orders</span>"
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
          . "<a href='".site_url("mrp/mrp-task-orders/add-task-orders/{$da->id_mrp_task_orders}")."' type='button' class='btn btn-info btn-flat' title='Edit Task Order' style='width: 40px'><i class='fa fa-edit'></i></a>"
          . "<a href='".site_url("mrp/mrp-po/po/{$da->id_mrp_task_orders}")."' type='button' class='btn btn-info btn-flat' title='Purchase Order' style='width: 40px'><i class='fa fa-shopping-cart'></i></a>"
        . "</div>"
      );
    }
    $return['hasil'] = $hasil;
//    $this->debug($return, true);
    print json_encode($return);
    die;
  }
  
  function cancel_task_orders(){
 $id_mrp_task_orders =$_POST['id_mrp_task_orders'];
  
 $id_po =$this->global_models->get_query("SELECT B.id_mrp_po "
         . "FROM mrp_task_orders_request_asset AS A"
         . " LEFT JOIN mrp_po_asset AS B ON A.id_mrp_task_orders_request_asset = B.id_mrp_task_orders_request_asset"
         . " WHERE A.id_mrp_task_orders='{$id_mrp_task_orders}' "
         . " GROUP BY B.id_mrp_po");
         
 if(is_array($id_po)){
     foreach ($id_po as $p) {
        $this->load->model('mrp/mmrp');
        $this->mmrp->cancel_po($p->id_mrp_po);
    }
 }     
      
    $kirim2 = array(
        "status"                        => 12,
        "update_by_users"               => $this->session->userdata("id"),
        "update_date"                   => date("Y-m-d H:i:s")
    );
    $this->global_models->update("mrp_task_orders", array("id_mrp_task_orders" => "{$id_mrp_task_orders}"),$kirim2);      

   $get = $this->global_models->get("mrp_task_orders_request", array("id_mrp_task_orders" => "{$id_mrp_task_orders}"));
         
   foreach ($get as $val) {
       $kirim = array(
        "status"                        => 3,
        "update_by_users"               => $this->session->userdata("id"),
        "update_date"                   => date("Y-m-d H:i:s")
    );
    $this->global_models->update("mrp_request", array("id_mrp_request" => "{$val->id_mrp_request}"),$kirim);      

   }
   $this->global_models->delete("mrp_task_orders_request_asset", array("id_mrp_task_orders" => "{$id_mrp_task_orders}"));
                  
   
   
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
                    
//                    $this->global_models->update("mrp_po_asset", array("id_mrp_task_orders" => "{$id_mrp_task_orders}","id_mrp_inventory_spesifik" => "{$value->id_mrp_inventory_spesifik}"),$kirim3);

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
                    
//                    $kirim = array(
//                    "id_mrp_task_orders"                => $id_mrp_task_orders,
//                    "id_mrp_inventory_spesifik"         => $value->id_mrp_inventory_spesifik,
//                    "id_mrp_task_orders_request_asset"  => $id_asset,
//                    "jumlah"                            => $value->jumlah,
//                    "id_mrp_satuan"                     => $id_satuan,
//                    "status"                            => 1,
//                    "create_by_users"                   => $this->session->userdata("id"),
//                    "create_date"                       => date("Y-m-d H:i:s")
//                    );
//                     $this->global_models->insert("mrp_po_asset", $kirim);
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
            
                     
//                    $this->global_models->update("mrp_po_asset", array("id_mrp_task_orders" => "{$id_mrp_task_orders2}","id_mrp_inventory_spesifik" => "{$value->id_mrp_inventory_spesifik}"),$kirim_request_asset);

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
                    
//                     $kirim = array(
//                    "id_mrp_task_orders"                => $id_mrp_task_orders2,
//                    "id_mrp_inventory_spesifik"         => $value->id_mrp_inventory_spesifik,
//                    "id_mrp_task_orders_request_asset"  => $id_asset,   
//                    "jumlah"                            => $value->jumlah,
//                    "id_mrp_satuan"                     => $id_satuan,
//                    "status"                            => 1,
//                    "create_by_users"                   => $this->session->userdata("id"),
//                    "create_date"                       => date("Y-m-d H:i:s")
//                    );
//                    $this->global_models->insert("mrp_po_asset", $kirim);
               }
                 
             }
            }
        }
    }
    
    if($id_mrp_task_orders2 > 0){
        $return['id'] = $id_mrp_task_orders2;
//    $this->debug($return, true);
    print json_encode($return);
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
        
        $type = $this->global_models->get_dropdown("mrp_type_inventory", "id_mrp_type_inventory", "code", TRUE);
    
    foreach ($data AS $da){
        
         if($da->type_inventory == 1){
            $dt_link = "mrp/add-request-pengadaan-cetakan/{$da->id_mrp_request}";
        }elseif($da->type_inventory == 2){
            $dt_link = "mrp/add-request-pengadaan-atk/{$da->id_mrp_request}";
        }elseif($da->type_inventory == 3){
            $dt_link = "mrp/mrp-request/add-request-pengadaan-komputer/{$da->id_mrp_request}";
        }elseif($da->type_inventory == 4){
            $dt_link = "mrp/mrp-request/add-request-pengadaan-technical/{$da->id_mrp_request}";
        }elseif($da->type_inventory == 5){
            $dt_link = "mrp/mrp-request/add-request-pengadaan-service/{$da->id_mrp_request}";
        }elseif($da->type_inventory == 7){
            $dt_link = "mrp/mrp-request/add-request-pengadaan-office/{$da->id_mrp_request}";
        }elseif($da->type_inventory == 8){
            $dt_link = "mrp/mrp-request/add-request-pengadaan-promosi/{$da->id_mrp_request}";
        }elseif($da->type_inventory == 9){
            $dt_link = "mrp/mrp-request/add-request-pengadaan-umum/{$da->id_mrp_request}";
        }elseif($da->type_inventory == 10){
            $dt_link = "mrp/mrp-request/add-request-pengadaan-cetakan-invoice/{$da->id_mrp_request}";
            
        }
        
//        $type = array(1 => "Cetakan", 2 => "ATK");
        
//        if($da->status <= 8){
           $btn = "<a href='javascript:void(0)' onclick='delete_task_order_request({$da->id_mrp_request})' id='del_{$da->id_mrp_request}' class='btn btn-danger btn-flat' style='width: 40px'><i class='fa fa-trash-o'></i></a>"
            ."<span style='display: none; margin-left: 10px;' id='img-page-{$da->id_mrp_request}'><img width='35px' src='{$url}' /></span>";
//        }
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
        "<a href='".site_url($dt_link)."'>"."Pegawai:".$da->nama_pegawai."<br>Perusahaan:".$da->perusahaan."<br>Department:".$da->department."</a>",
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
  
function get_task_orders_request_asset($id_mrp_task_orders = 0,$start = 0){
      
      $where = "WHERE A.id_mrp_task_orders = '$id_mrp_task_orders'";
//       if($status == 1){
//          $where .= " AND A.status < 3 ";
//      }else{
//          $where .= " AND A.status > 2 ";
//      }
      
    $data = $this->global_models->get_query("SELECT A.id_mrp_inventory_spesifik,A.id_mrp_task_orders,A.jumlah AS jml_to,(Select SUM(H.jumlah) FROM mrp_po_asset AS H where H.id_mrp_inventory_spesifik  = A.id_mrp_inventory_spesifik AND H.status !=12 AND H.id_mrp_task_orders ='{$id_mrp_task_orders}' GROUP BY H.id_mrp_inventory_spesifik) AS jml_po,D.code AS brand,C.name AS nama_barang"
        . ",B.title AS title_spesifik,E.title AS satuan,D.title AS brand"
        . " FROM mrp_task_orders_request_asset AS A"
        . " LEFT JOIN mrp_inventory_spesifik AS B ON A.id_mrp_inventory_spesifik = B.id_mrp_inventory_spesifik"
        . " LEFT JOIN mrp_inventory_umum AS C ON B.id_mrp_inventory_umum = C.id_mrp_inventory_umum"
        . " LEFT JOIN mrp_brand AS D ON B.id_mrp_brand = D.id_mrp_brand"
        . " LEFT JOIN mrp_satuan AS E ON B.id_mrp_satuan = E.id_mrp_satuan"
        . " LEFT JOIN mrp_po_asset AS F ON (A.id_mrp_task_orders_request_asset= F.id_mrp_task_orders_request_asset AND (F.status > 2 AND F.status <=11) AND F.id_mrp_supplier IS NOT NULL) "    
//        . " LEFT JOIN mrp_brand AS C ON A.id_mrp_brand = C.id_mrp_brand"
//        . " LEFT JOIN mrp_qty AS D ON A.id_mrp_qty = D.id_mrp_qty"    
        . " {$where}" 
        . " GROUP BY A.id_mrp_task_orders,A.id_mrp_inventory_spesifik"
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
        $satuan = "";
        if($da->satuan){
            $satuan = "<br>Satuan:".$da->satuan;
        }
        
      $hasil[] = array(
        "<a data-toggle='collapse' data-parent='#accordion'  href='#collapse_task_orders' onclick='data_users_request({$da->id_mrp_inventory_spesifik},{$da->id_mrp_task_orders});'>".$da->nama_barang." ".$da->title_spesifik.$brn.$satuan."</a>"
          . "<script>"
            . "function data_users_request(id_mrp_inventory_spesifik,id_mrp_task_orders){"
                    . 'var table = '
                    . '$("#users-request-task-orders").dataTable({'
                    . '"order": [[ 0, "asc" ]],'
                    . '"bDestroy": true'
                    . '});'
                    . "table.fnClearTable();"
                    . 'ambil_data_users_request(table, 0,id_mrp_inventory_spesifik,id_mrp_task_orders);'
            . "}"
        
                . 'function ambil_data_users_request(table, mulai,id_mrp_inventory_spesifik,id_mrp_task_orders){'
                . '$.post("'.site_url("mrp/mrp-ajax-to/ajax-form-to-users-request").'/"+id_mrp_inventory_spesifik+"/"+id_mrp_task_orders+"/"+mulai, function(data){'
                  . '$("#loader-page10").show();'
                  . 'var hasil = $.parseJSON(data);'
                  . 'if(hasil.status == 2){'
                    . 'table.fnAddData(hasil.hasil);'
                    . 'ambil_data_users_request(table, hasil.start,id_mrp_inventory_spesifik,id_mrp_task_orders);'
                  . '}'
                  . 'else{'
                    . '$("#loader-page10").hide();'
                  . '}'
                . '});'
              . '}'
        . "</script>",
        "<center>".$da->jml_to."</center>",
        "<center>".$jumlah_po."</center>"
      );
      
    }
    $return['hasil'] = $hasil;
//    $this->debug($return, true);
    print json_encode($return);
    die;
  }
  
function proses_mutasi_stock($id_mrp_task_orders = 0){
  $pst = $_POST;
  $id_mrp_inventory_spesifik = $pst['id_mrp_inventory_spesifik'];
  $jml_rg           =  $pst['jumlah'];
  
  $tgl_diserahkan   = $pst['tgl_diserahkan'];
  $note             =  $pst['note'];
 
//  $this->global_modes->get_query("Select"
//   . "FROM mrp_task_orders_request AS A "
//   . "LEFT JOIN ");
  if($tgl_diserahkan != "" AND $tgl_diserahkan != "0000-00-00"){
  $this->load->model('mrp/mmrp');
  $this->mmrp->proses_mutasi_stock_to($id_mrp_task_orders,$id_mrp_inventory_spesifik,$jml_rg,$tgl_diserahkan,$note);
   $return['status'] = 2;
   print json_encode($return);
   die;
  
  }else{
  $return['status'] = 1;
  print json_encode($return);
  die;
  }
}
  
  function get_task_orders_mutasi_asset($id_mrp_task_orders,$start = 0){

//   $status = $this->global_models->get_field("mrp_request", "status", 
//                 array("id_mrp_request" => "{$id_mrp_request}"));
//
//   $dt_user = $this->global_models->get_field("mrp_request", "create_by_users", 
//                 array("id_mrp_request" => "{$id_mrp_request}"));              

//   $where = "WHERE E.jumlah IS NOT NULL";
    $where = "WHERE A.id_mrp_task_orders = '$id_mrp_task_orders'";
   $data = $this->global_models->get_query("SELECT A.jumlah,F.jumlah AS jml_po,C.name AS inventory_umum"
        . ",B.title AS title_spesifik,E.title AS satuan,D.title AS brand"
        . ",G.stock_akhir"
        . " FROM mrp_task_orders_request_asset AS A"
        . " LEFT JOIN mrp_inventory_spesifik AS B ON A.id_mrp_inventory_spesifik = B.id_mrp_inventory_spesifik"
        . " LEFT JOIN mrp_inventory_umum AS C ON B.id_mrp_inventory_umum = C.id_mrp_inventory_umum"
        . " LEFT JOIN mrp_brand AS D ON B.id_mrp_brand = D.id_mrp_brand"
        . " LEFT JOIN mrp_satuan AS E ON B.id_mrp_satuan = E.id_mrp_satuan"
        . " LEFT JOIN mrp_po_asset AS F ON (A.id_mrp_task_orders_request_asset= F.id_mrp_task_orders_request_asset AND F.status ='11' AND F.id_mrp_supplier IS NOT NULL) "
        . " LEFT JOIN mrp_stock AS G ON A.id_mrp_inventory_spesifik = G.id_mrp_inventory_spesifik" 
//      . " LEFT JOIN mrp_brand AS C ON A.id_mrp_brand = C.id_mrp_brand"
//      . " LEFT JOIN mrp_qty AS D ON A.id_mrp_qty = D.id_mrp_qty"    
        . " {$where}"
        . " ORDER BY A.id_mrp_task_orders ASC"
        . " LIMIT {$start}, 10");
        
//     print  $this->db->last_query();
//     die;
//   $data = $this->global_models->get_query("SELECT B.name AS inventory_umum,C.title AS brand,D.title AS satuan,"
//     . " A.id_mrp_inventory_spesifik,A.title AS title_spesifik,E.jumlah,E.rg,F.id_mrp_request,F.status AS status_request,F.type_inventory AS type,"
//     . " G.stock_akhir"
//     . " FROM mrp_task_orders_request_asset AS A"
//     . " LEFT JOIN mrp_inventory_umum AS B ON A.id_mrp_inventory_umum = B.id_mrp_inventory_umum"
//     . " LEFT JOIN mrp_brand AS C ON A.id_mrp_brand = C.id_mrp_brand"
//     . " LEFT JOIN mrp_satuan AS D ON A.id_mrp_satuan = D.id_mrp_satuan"
//     . " LEFT JOIN mrp_po_asset AS E ON (A.id_mrp_inventory_spesifik = E.id_mrp_inventory_spesifik AND E.id_mrp_request = '{$id_mrp_request}')"
//     . " LEFT JOIN mrp_request AS F ON E.id_mrp_request = F.id_mrp_request"
//     . " LEFT JOIN mrp_stock AS G ON A.id_mrp_inventory_spesifik = G.id_mrp_inventory_spesifik"
////     . " {$where} "
//     . " ORDER BY A.id_mrp_inventory_spesifik ASC"
//     . " LIMIT {$start}, 10");

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

//    $url = base_url('mrp/create-task-orders');
 $no = $start;
    foreach ($data AS $da){

//        $dta_jumlah = $jumlah;

        $brand = "";     
        if($da->brand){
            $brand = "<br>Merk:".$da->brand;
        }
        $satuan = "";
        if($da->satuan){
           $satuan = "<br>Satuan:".$da->satuan."<br>"; 
        }
        $stock = 0;
        if($da->stock_akhir){
            $stock = $da->stock_akhir;
        }
        $dt_rg = 0;
        if($da->rg){
            $dt_rg = $da->rg;
        }
        
       $kekurangan = $da->jumlah - $da->rg;
        $data_id = "id=dt_id_mrp_inventory_spesifik_".$da->id_mrp_inventory_spesifik;
        $data_jumlah = "id=jumlah_mutasi".$da->id_mrp_inventory_spesifik;
        if($kekurangan == 0){
            $dt_jumlah = $this->form_eksternal->form_input("jumlah_mutasi[]", 0, $data_jumlah.'  style="width:100px;display:none" class="form-control jumlah_mutasi input-sm" placeholder="Jumlah"');
        }else{
            if($da->stock_akhir != 0){
                $dt_jumlah = $this->form_eksternal->form_input("jumlah_mutasi[]", $kekurangan, $data_jumlah.'  style="width:100px" class="form-control jumlah_mutasi input-sm" placeholder="Jumlah"');
            }else{
                $dt_jumlah = $this->form_eksternal->form_input("jumlah_mutasi[]", 0, $data_jumlah.'  style="width:100px;display:none" class="form-control jumlah_mutasi input-sm" placeholder="Jumlah"');
            } 
         }
       
       $hasil[] = array(
        $no =$no + 1,
         $da->inventory_umum." ".$da->title_spesifik.$brand.$satuan,
         $stock,
         $da->jumlah,
         $dt_rg,
         $this->form_eksternal->form_input("id_mrp_inventory_spesifik[]", $da->id_mrp_inventory_spesifik, $data_id.' style="width:100px;display:none" class="form-control id_spesifik input-sm" placeholder=""').  
         $dt_jumlah,
        ); 
//     }        
             
    }

 $return['hasil'] = $hasil;
//    $this->debug($return, true);
 print json_encode($return);
 die;
}

//    function get_task_orders_mutasi_asset($id_mrp_task_orders = 0,$start = 0){
//      
//      $where = "WHERE A.id_mrp_task_orders = '$id_mrp_task_orders'";
////       if($status == 1){
////          $where .= " AND A.status < 3 ";
////      }else{
////          $where .= " AND A.status > 2 ";
////      }
//      
////       $data = $this->global_models->get_query("SELECT B.name AS inventory_umum,C.title AS brand,D.title AS satuan,"
////     . " A.id_mrp_inventory_spesifik,A.title AS title_spesifik,E.jumlah,E.rg,F.id_mrp_request,F.status AS status_request,F.type_inventory AS type,"
////     . " G.stock_akhir"
////     . " FROM mrp_inventory_spesifik AS A"
////     . " LEFT JOIN mrp_inventory_umum AS B ON A.id_mrp_inventory_umum = B.id_mrp_inventory_umum"
////     . " LEFT JOIN mrp_brand AS C ON A.id_mrp_brand = C.id_mrp_brand"
////     . " LEFT JOIN mrp_satuan AS D ON A.id_mrp_satuan = D.id_mrp_satuan"
////     . " LEFT JOIN mrp_request_asset AS E ON (A.id_mrp_inventory_spesifik = E.id_mrp_inventory_spesifik AND E.id_mrp_request = '{$id_mrp_request}')"
////     . " LEFT JOIN mrp_request AS F ON E.id_mrp_request = F.id_mrp_request"
////     . " LEFT JOIN mrp_stock AS G ON A.id_mrp_inventory_spesifik = G.id_mrp_inventory_spesifik"
////     . " {$where} "
////     . " ORDER BY A.id_mrp_inventory_spesifik ASC"
////     . " LIMIT {$start}, 10");
//     
//    $data = $this->global_models->get_query("SELECT A.jumlah AS jml_to,F.jumlah AS jml_po,C.name AS nama_barang"
//        . ",B.title AS title_spesifik,E.title AS satuan,D.title AS brand"
//        . " FROM mrp_task_orders_request_asset AS A"
//        . " LEFT JOIN mrp_inventory_spesifik AS B ON A.id_mrp_inventory_spesifik = B.id_mrp_inventory_spesifik"
//        . " LEFT JOIN mrp_inventory_umum AS C ON B.id_mrp_inventory_umum = C.id_mrp_inventory_umum"
//        . " LEFT JOIN mrp_brand AS D ON B.id_mrp_brand = D.id_mrp_brand"
//        . " LEFT JOIN mrp_satuan AS E ON B.id_mrp_satuan = E.id_mrp_satuan"
//        . " LEFT JOIN mrp_po_asset AS F ON (A.id_mrp_task_orders_request_asset= F.id_mrp_task_orders_request_asset AND F.status > 2 AND F.id_mrp_supplier IS NOT NULL) "
////        . " LEFT JOIN mrp_join AS G ON "    
////        . " LEFT JOIN mrp_brand AS C ON A.id_mrp_brand = C.id_mrp_brand"
////        . " LEFT JOIN mrp_qty AS D ON A.id_mrp_qty = D.id_mrp_qty"    
//        . " {$where}"
//        . " ORDER BY A.id_mrp_task_orders ASC"
//        . " LIMIT {$start}, 10");
////       print   $this->db->last_query();
////       die;
////    $data_array = json_decode($data);
////    $this->debug($data, true);
////     
//    if(count($data) > 0){
//      $return['status'] = 2;
//      $return['start']  = $start + 10;
//    }
//    else{
//      $return['status'] = 3;
//    }
////    $jenis = array(1 => "Habis Pakai", 2 => "Asset");
////    $status = array( 1=> "<span class='label bg-orange'>Create</span>", 2 => "<span class='label bg-green'>Approve</span>",3 => "<span class='label bg-green'>Task Orders</span>");
////    $status = array(1 => "Create", 2 => "Approve");
//    
//    foreach ($data AS $da){
//        if($da->jml_po){
//           $jumlah_po =  $da->jml_po;
//        }else{
//            $jumlah_po = 0;
//        }
//        
//        $brn = "";
//        if($da->brand){
//            $brn = "<br>Brand:".$da->brand;
//        }
//      $hasil[] = array(
//        $da->nama_barang." ".$da->title_spesifik.$brn,
//        $da->brand,
//        $da->satuan,
//        "<center>".$da->jml_to."</center>",
//        "<center>".$jumlah_po."</center>"
//      );
//      
//    }
//    $return['hasil'] = $hasil;
////    $this->debug($return, true);
//    print json_encode($return);
//    die;
//  }
  
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
        
        $lnk = site_url("mrp/mrp-task-orders/preview-grouping/{$da->create_by_users}/{$da->id_mrp_task_orders}");
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
                . '$.post("'.site_url("mrp/mrp-ajax-to/ajax-form-grouping-mrp-request-pengadaan").'/"+type+"/"+id_mrp_task_orders+"/"+create_by_users+"/"+mulai, function(data){'
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
    
    $url = base_url('mrp/mrp-task-orders/create-task-orders');
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
            
            if($da->rg){
                $jumlah_rg = $da->rg;
            }else{
                $jumlah_rg = 0;
            }
//            if($da->status_request == 3){
//                $dta_jumlah = $this->form_eksternal->form_input("jumlah[]", $jumlah, $data_jumlah.' style="width:100px" class="form-control jumlah input-sm" placeholder="Jumlah"');
//            }
            
          $no =$no + 1;
          $hasil[] = array(
            $no,
            $da->inventory_umum." ".$da->title_spesifik,
            $da->brand,  
            $da->satuan,
            $this->form_eksternal->form_input("id_mrp_inventory_spesifik[]", $da->id_mrp_inventory_spesifik, $data_id.' style="width:100px;display:none" class="form-control id_spesifik input-sm" placeholder=""').
            $jumlah,
            $jumlah_rg  
            );
        }
        }elseif($type == 2 OR $type == 3 OR $type == 4 OR $type == 5){
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
  
    function ajax_form_to_users_request($id_mrp_inventory_spesifik,$id_mrp_task_orders,$start = 0){
     
//      $status = $this->global_models->get_field("mrp_request", "status", 
//                    array("id_mrp_request" => "{$id_mrp_request}"));
//                    
//      $dt_user = $this->global_models->get_field("mrp_request", "create_by_users", 
//                    array("id_mrp_request" => "{$id_mrp_request}"));              
      
      $where = " WHERE A.id_mrp_task_orders = {$id_mrp_task_orders} AND C.id_mrp_inventory_spesifik ={$id_mrp_inventory_spesifik}";
      
      $data = $this->global_models->get_query("SELECT B.id_mrp_request,B.code AS code_ro,B.type_inventory,E.name AS users,G.name AS create_users,F.code AS organisasi,C.jumlah"
        . " FROM mrp_task_orders_request AS A"
        . " LEFT JOIN mrp_request AS B ON A.id_mrp_request = B.id_mrp_request"
        . " LEFT JOIN mrp_request_asset AS C ON B.id_mrp_request = C.id_mrp_request"
        . " LEFT JOIN hr_pegawai AS D ON B.id_hr_pegawai = D.id_hr_pegawai"
        . " LEFT JOIN m_users AS E ON D.id_users = E.id_users"
        . " LEFT JOIN m_users AS G ON B.create_by_users = G.id_users"
        . " LEFT JOIN hr_master_organisasi AS F ON D.id_hr_master_organisasi = F.id_hr_master_organisasi"
        . "{$where}"
        . " LIMIT {$start}, 10");
      
      
//      $data = $this->global_models->get_query("SELECT B.name AS inventory_umum,C.title AS brand,D.title AS satuan,"
//        . "A.id_mrp_inventory_spesifik,A.title AS title_spesifik,SUM(E.jumlah) AS jumlah,F.id_mrp_request,F.status AS status_request,E.rg"
//        . " FROM mrp_inventory_spesifik AS A"
//        . " LEFT JOIN mrp_inventory_umum AS B ON A.id_mrp_inventory_umum = B.id_mrp_inventory_umum"
//        . " LEFT JOIN mrp_brand AS C ON A.id_mrp_brand = C.id_mrp_brand"
//        . " LEFT JOIN mrp_satuan AS D ON A.id_mrp_satuan = D.id_mrp_satuan"
//        . " LEFT JOIN mrp_request_asset AS E ON (A.id_mrp_inventory_spesifik = E.id_mrp_inventory_spesifik)"
//        . " LEFT JOIN mrp_request AS F ON E.id_mrp_request = F.id_mrp_request"
//        . " LEFT JOIN mrp_task_orders_request AS G ON F.id_mrp_request = G.id_mrp_request"
//        . " {$where} "
//        . " GROUP BY F.create_by_users,A.id_mrp_inventory_spesifik"
////        . " ORDER BY A.id_mrp_inventory_spesifik ASC"
//        . " LIMIT {$start}, 10");
        
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
    
    $url = base_url('mrp/mrp-task-orders/create-task-orders');
    $no = $start;
 
            foreach ($data AS $da){
                
               
            if($da->jumlah){
                $jumlah = $da->jumlah;
            }else{
                $jumlah = 0;
            }
             if($da->type_inventory == 1){
            $dt_link = "mrp/add-request-pengadaan-cetakan/{$da->id_mrp_request}";
        }elseif($da->type_inventory == 2){
            $dt_link = "mrp/add-request-pengadaan-atk/{$da->id_mrp_request}";
        }elseif($da->type_inventory == 3){
            $dt_link = "mrp/mrp-request/add-request-pengadaan-komputer/{$da->id_mrp_request}";
        }elseif($da->type_inventory == 4){
            $dt_link = "mrp/mrp-request/add-request-pengadaan-technical/{$da->id_mrp_request}";
        }elseif($da->type_inventory == 5){
            $dt_link = "mrp/mrp-request/add-request-pengadaan-service/{$da->id_mrp_request}";
        }elseif($da->type_inventory == 7){
            $dt_link = "mrp/mrp-request/add-request-pengadaan-office/{$da->id_mrp_request}";
        }elseif($da->type_inventory == 8){
            $dt_link = "mrp/mrp-request/add-request-pengadaan-promosi/{$da->id_mrp_request}";
        }elseif($da->type_inventory == 9){
            $dt_link = "mrp/mrp-request/add-request-pengadaan-umum/{$da->id_mrp_request}";
        }elseif($da->type_inventory == 10){
            $dt_link = "mrp/mrp-request/add-request-pengadaan-cetakan-invoice/{$da->id_mrp_request}";
            
        }
           $link = "<a href='".site_url($dt_link)."' target='_blank'>{$da->code_ro}</a>";

          $hasil[] = array(
           
            $da->users,
            $jumlah,
            $da->create_users,
             $link 
            );
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
        , 7 =>"<span class='label bg-green'>Closed PO</span>", 8 =>"<span class='label bg-red'>Revisi PO</span>"
        , 12 => "<span class='label bg-red'>CANCEL PO</span>");
    
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
        
            $hasil[] = array(
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
                    . 'ambil_data12(id_mrp_task_orders,id_mrp_po,table, 0,0);'
            . "}"
      
               . 'function ambil_data12(id_mrp_task_orders,id_mrp_po,table, mulai,total){'
        . '$.post("'.site_url("mrp/mrp-ajax-to/get-mrp-detail-po-list").'/"+id_mrp_task_orders+"/"+id_mrp_po+"/"+mulai+"/"+total, function(data){'
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
              . "<a href='".site_url("mrp/mrp-po/detail-po/{$da->id_mrp_task_orders}/{$da->id_mrp_po}")."' type='button' class='btn btn-info btn-flat' title='Detail Purchase Order' style='width: 40px'><i class='fa fa-list-alt'></i></a>"
    //          . $btn_rg
            . "</div>"
          );
        }
          
          $hide++;
     }else{
                if($da->status == 3 OR $da->status == 8){
        $btn_update ="<a href='".site_url("mrp/mrp-po/update-po/{$da->id_mrp_task_orders}/{$da->id_mrp_po}")."' type='button' class='btn btn-info btn-flat' title='Edit Purchase Order' style='width: 40px'><i class='fa fa-edit'></i></a>";
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
        
            $hasil[] = array(
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
                    . 'ambil_data12(id_mrp_task_orders,id_mrp_po,table, 0,0);'
            . "}"
      
               . 'function ambil_data12(id_mrp_task_orders,id_mrp_po,table, mulai,total){'
        . '$.post("'.site_url("mrp/mrp-ajax-to/get-mrp-detail-po-list").'/"+id_mrp_task_orders+"/"+id_mrp_po+"/"+mulai+"/"+total, function(data){'
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
              . "<a href='".site_url("mrp/mrp-po/detail-po/{$da->id_mrp_task_orders}/{$da->id_mrp_po}")."' type='button' class='btn btn-info btn-flat' title='Detail Purchase Order' style='width: 40px'><i class='fa fa-list-alt'></i></a>"
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
  
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */