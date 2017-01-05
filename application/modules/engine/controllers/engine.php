<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Engine extends MX_Controller {
    
  function __construct() {      
    //$this->menu = $this->cek();
    
//    $this->debug($this->menu, true);
  }
  
  function cancel_rg_new(){
      $pst = $_REQUEST;
      print_r($pst);
      die;
    $kode = $pst['kode'];
    $note = "Note Cancel:".$pst['note'];
    $this->db->trans_begin();
    
   $get_mrp_rg = $this->global_models->get_query("SELECT A.code,A.id_mrp_receiving_goods,B.id_mrp_receiving_goods_department,A.id_mrp_receiving_goods_po"
            . " FROM mrp_receiving_goods AS A"
            . " LEFT JOIN mrp_receiving_goods_department AS B ON A.code = B.code"
            . " WHERE A.code='{$kode}'");
            
    if($get_mrp_rg[0]->note){
        $dt_note = $get_mrp_rg[0]->note."<br>".$note;
    }else{
        $dt_note = $note;
    }
    
    $this->global_models->update("mrp_receiving_goods",array("code" => "{$get_mrp_rg[0]->code}"),array("status" => 2,"note" =>"{$dt_note}"));
    $this->global_models->update("mrp_receiving_goods_department",array("code" => "{$get_mrp_rg[0]->code}"),array("status" => 2));
    
//    $get_rg_detail = $this->global_models->get("mrp_receiving_goods_detail",array("id_mrp_receiving_goods" => "{$get_mrp_rg[0]->id_mrp_receiving_goods}"));
    
    $get_rg_detail =$this->global_models->get_query("SELECT A.id_mrp_inventory_spesifik,A.id_mrp_receiving_goods_detail,B.id_mrp_stock_in,C.id_mrp_stock_out"
            . " FROM mrp_receiving_goods_detail AS A"
            . " LEFT JOIN mrp_stock_in AS B ON A.id_mrp_receiving_goods_detail = B.id_mrp_receiving_goods_detail"
            . " LEFT JOIN mrp_stock_out AS C ON B.id_mrp_stock_in = C.id_mrp_stock_in"
            . " WHERE A.id_mrp_receiving_goods='{$get_mrp_rg[0]->id_mrp_receiving_goods}' ");
            
    $this->global_models->update("mrp_receiving_goods_detail",array("id_mrp_receiving_goods_detail" => "{$get_rg_detail[0]->id_mrp_receiving_goods_detail}"),array("status" => 2));
    $total = $this->global_models->get_field("mrp_receiving_goods_po_asset","rg",array("id_mrp_receiving_goods_po" => "{$get_mrp_rg[0]->id_mrp_receiving_goods_po}","id_mrp_inventory_spesifik" => "{$get_rg_detail[0]->id_mrp_inventory_spesifik}"));
    $rg =$total -$get_rg_detail[0]->jumlah;
    $this->global_models->update("mrp_receiving_goods_po_asset",array("id_mrp_receiving_goods_po" =>"{$get_mrp_rg[0]->id_mrp_receiving_goods_po}","id_mrp_inventory_spesifik" => "{$get_rg_detail[0]->id_mrp_inventory_spesifik}"),array("rg" => "{$rg}"));
    
    $this->global_models->update("mrp_receiving_goods_detail_department",array("id_mrp_receiving_goods_department" =>"{$get_mrp_rg[0]->id_mrp_receiving_goods_department}","id_mrp_inventory_spesifik" => "{$get_rg_detail[0]->id_mrp_inventory_spesifik}"),array("status" => "2"));
   
    $this->global_models->update("mrp_stock_in",array("id_mrp_stock_in" =>"{$get_rg_detail[0]->id_mrp_stock_in}"),array("status" => 15));
    $this->global_models->update("mrp_stock_out",array("id_mrp_stock_out" =>"{$get_rg_detail[0]->id_mrp_stock_out}"),array("status" => 15));
    $this->global_models->update("mrp_report",array("id_mrp_stock_out" =>"{$get_rg_detail[0]->id_mrp_stock_out}"),array("status" => 15));
    
    $id = $this->global_models->get_field("mrp_receiving_goods_po", "id_mrp_task_orders",array("id_mrp_receiving_goods_po" => "{$get_mrp_rg[0]->id_mrp_receiving_goods_po}"));
    
    $data2 = $this->global_models->get_query("SELECT B.id_hr_pegawai,A.id_mrp_request,"
        . "C.jumlah,C.rg,C.id_mrp_request_asset,D.id_hr_master_organisasi,D.id_hr_company"
        . " FROM mrp_task_orders_request AS A"
        . " INNER JOIN mrp_request AS B ON A.id_mrp_request = B.id_mrp_request"
        . " INNER JOIN mrp_request_asset AS C ON B.id_mrp_request = C.id_mrp_request"
        . " INNER JOIN hr_pegawai AS D ON B.id_hr_pegawai = D.id_hr_pegawai"
        . " WHERE A.id_mrp_task_orders='{$id}' AND C.id_mrp_inventory_spesifik='{$get_rg_detail[0]->id_mrp_inventory_spesifik}' "
        );  
     foreach ($data2 as $val) {
//         $val->rg
        $ttal = $this->global_models->get_field("mrp_report","jumlah",array("id_hr_pegawai" => $val->id_hr_pegawai,"id_mrp_inventory_spesifik" => "{$val->id_mrp_inventory_spesifik}"));
            $rg_dept = $val->rg - $ttal;
            $krm2 = array(
            "rg"                                => $rg_dept,
                
            "update_by_users"                   => $this->session->userdata("id"),
            "update_date"                       => date("Y-m-d H:i:s")
        );
            
        $this->global_models->update("mrp_request_asset", array("id_mrp_request_asset" => $val->id_mrp_request_asset),$krm2);
        
        $this->global_models->update("mrp_request", array("id_mrp_request" => $val->id_mrp_request), array("status" => 6));   
    }
    
        
    if ($this->db->trans_status() === FALSE)
     {
        $this->db->trans_rollback();
     }
    else
     {
        $this->db->trans_commit();
     }
    
    }
  
  function test(){
       
  $table = '<div class="table">
    
    <div class="row header blue">
      <div class="cell">
        Nama Barang
      </div>
      <div class="cell">
        Satuan
      </div>
      <div class="cell">
        Jumlah
      </div>
    </div>
    
    <div class="row">
      <div class="cell">
        ninjalug
      </div>
      <div class="cell">
        misterninja@hotmail.com
      </div>
      <div class="cell">
        ************
      </div>
    </div>
    
  </div>';
      die;
  }
  
  function cancel_rg() {
//      $rg_department = $this->global_models->get("mrp_receiving_goods_department");
        while (1) {
          $this->load->model('mrp/mmrp_rg');
          print $data = $this->mmrp_rg->engine_cancel_rg();
//          print "go";
//          die;
          sleep(5);
        }
      
  }
  
  function proses_rg(){
//      print "a";
//      die;
      set_time_limit(0);
      $no= $no1 = 0;
       while (1) {
          $this->load->model('mrp/mmrp_rg');
          print $data = $this->mmrp_rg->engine_proses_rg();
          print $no+1;
         sleep(5);
         print $no1 + 2; 
        }
  }
  
  function proses_rg_department(){
       while (1) {
          $this->load->model('mrp/mmrp_rg');
          print $data = $this->mmrp_rg->engine_proses_rg_department();
//          die;
         sleep(5);
        }
  }
  
  function alert_mail(){
      
            $css = '<style>body {
  font-family: "Helvetica Neue", Helvetica, Arial;
  font-size: 14px;
  line-height: 20px;
  font-weight: 400;
  color: #3b3b3b;
  -webkit-font-smoothing: antialiased;
  font-smoothing: antialiased;
  #background: #2b2b2b;
}

.wrapper {
  margin: 0 auto;
  padding: 40px;
  max-width: 800px;
}

.table {
  margin: 0 0 40px 0;
  width: 548px;
  box-shadow: 1px 1px 3px rgba(0, 0, 0, 0.2);
  display: table;
}
@media screen and (max-width: 580px) {
  .table {
    display: block;
  }
}

.row {
  display: table-row;
  background: #f6f6f6;
}
.row:nth-of-type(odd) {
  background: #e9e9e9;
}
.row.header {
  font-weight: 900;
  color: #ffffff;
  background: #ea6153;
}
.row.green {
  background: #27ae60;
}
.row.blue {
  background: #2980b9;
}
@media screen and (max-width: 580px) {
  .row {
    padding: 8px 0;
    display: block;
  }
}

.cell {
  padding: 6px 12px;
  display: table-cell;
}
@media screen and (max-width: 580px) {
  .cell {
    padding: 2px 12px;
    display: block;
  }
}</style>';
            
      $this->load->library('email');
      $this->email->initialize($this->global_models->email_conf());
//    while (1) {  
     date_default_timezone_set('Asia/Jakarta');
     $dt = $this->global_models->get("temp_alert_email", array("status" => 1));
     $type_array = $this->global_models->get_dropdown("mrp_type_inventory", "id_mrp_type_inventory", "code", FALSE, array("status" => "1")); 
//     print_r($type_array);
//     print $type_array[1];
//     die;
//      $type_array = array(1 => "Cetakan",2 => "ATK", 10 => "Cetakan INvoice");
     foreach ($dt as $k => $v) {
         
         $data = $this->global_models->get_query("SELECT D.name AS inventory_umum,E.title AS brand,F.title AS satuan,"
        . " C.title AS title_spesifik,B.jumlah"
        . " FROM mrp_request AS A"
        . " LEFT JOIN mrp_request_asset AS B ON A.id_mrp_request = B.id_mrp_request"
        . " LEFT JOIN mrp_inventory_spesifik AS C ON B.id_mrp_inventory_spesifik = C.id_mrp_inventory_spesifik"
        . " LEFT JOIN mrp_inventory_umum AS D ON C.id_mrp_inventory_umum = D.id_mrp_inventory_umum"
        . " LEFT JOIN mrp_brand AS E ON C.id_mrp_brand = E.id_mrp_brand"
        . " LEFT JOIN mrp_satuan AS F ON C.id_mrp_satuan = F.id_mrp_satuan"       
        . " WHERE A.id_mrp_request='{$v->id_mrp_request}'"         
        );
        
        $table = '<div class="table">
            <div class="row header blue">
            <div style="width:2%;" class="cell">
            No
          </div>
            <div class="cell">
            Nama Barang
          </div>
        <div style="width:2%;" class="cell">
          Jumlah Request
        </div>
    </div>';
        $no[$k] = 0;
        foreach ($data as $ky => $vl) {
            
            if($vl->brand){
                $brand = "<br>Merk:".$vl->brand;
            }else{
                $brand = "";
            }
            
            if($vl->satuan){
                $satuan = "<br>Satuan:".$vl->satuan;
            }else{
                $satuan = "";
            }
            $no[$k] = $no[$k] + 1;
            $name = $vl->inventory_umum." ".$vl->title_spesifik.$brand.$satuan;
    $table .= '<div class="row">'
            . '<div class="cell" >'
            . "{$no[$k]}"
            . "</div>"
            . '<div class="cell">'
            . "{$name}"
            . "</div>"
            . '<div class="cell">'
                . "{$vl->jumlah}"
            . '</div>'
            . '</div>';
        }
        $table .= '</div>';
//        print $css.$table;
//        die;
     if($v->type == 1){
        $dt_user = $this->global_models->get_query("SELECT A.type_inventory,A.code,B.id_users AS id_users_request,A.create_by_users,C.name AS users_request,D.name AS created_users"
                 . " FROM mrp_request AS A "
                 . " LEFT JOIN hr_pegawai AS B ON A.id_hr_pegawai = B.id_hr_pegawai"
                 . " LEFT JOIN m_users AS C ON B.id_users = C.id_users"
                 . " LEFT JOIN m_users AS D ON A.create_by_users = D.id_users"
                 . " LEFT JOIN hr_master_organisasi AS E ON B.id_hr_master_organisasi = E.id_hr_master_organisasi"
                 . " WHERE A.id_mrp_request='{$v->id_mrp_request}'"
                 );
//        print_r($data);
//        die;
        
        if($dt_user[0]->id_users_request == $dt_user[0]->create_by_users){
            $usr = "";
        }else{
            $usr = "Sesuai Permintaan Dari user {$dt_user[0]->users_request}";
        }
        
         $isi = "Dear Users Approval<br><br>"
               . "Users {$dt_user[0]->created_users} telah melakukan pengajuan {$type_array[$dt_user[0]->type_inventory]} $usr dengan kode Request <b>{$dt_user[0]->code}</b>.<br>"
               . "Supaya permintaan barang tersebut dapat diteruskan ke Bagian Procurement, Silahkan di Approve sesuai dengan kode Request tersebut. <br>"
               . "Dibawah ini list barang - barang yang di ajukan <br><br>";
        
        
         $to_users = $this->global_models->get_query("SELECT C.email"
                . " FROM alert_email_users AS A"
                . " LEFT JOIN alert_email_approval AS B ON A.id_alert_email_struktural = B.id_alert_email_struktural"
                . " LEFT JOIN m_users AS C ON B.id_users = C.id_users"
                . " WHERE A.id_users='{$dt_user[0]->id_users_request}'");

                $ank[$k] = 0;
                $email = "";
            foreach ($to_users as $value) {
                if($ank[$k] > 0){
                    $email .= ",".$value->email;
                }else{
                    $email .=$value->email;
                }
                $ank[$k] = $ank[$k] + 1;
            }  
            
       if($email){
        $this->email->from('no-reply@antavaya.com', 'System Eprocurement');
        $this->email->to($email);
        $this->email->bcc("hendri.prasetyo@antavaya.com");
        $this->email->subject("pengajuan Barang {$type_array[$dt_user[0]->type_inventory]} Dengan kode Request {$dt_user[0]->code}");
        $this->email->message($isi.$css.$table);
        if($this->email->send() === TRUE){
          $pesan .= "New Password has been send to your mail";
        }else{
            $pesan .= "gagal";
        }
        
        
        $kirim = array(
            "note"                        => "berhasil terkirim Ke Approval",
            "status"                      => 2,
            "update_by_users"             => $v->create_by_users,
            "update_date"                 => date("Y-m-d H:i:s")
        );
        $this->global_models->update("temp_alert_email", array("id_temp_alert_email" => $v->id_temp_alert_email),$kirim);
        
        }else{
            $kirim = array(
            "note"                        => "Gagal kirim, Email Approval tidak ada",
            "status"                      => 3,
            "update_by_users"             => $v->create_by_users,
            "update_date"                 => date("Y-m-d H:i:s")
        );
        $this->global_models->update("temp_alert_email", array("id_temp_alert_email" => $v->id_temp_alert_email),$kirim);
        
        }
}else{
    $dt_users = $this->global_models->get_query("SELECT A.type_inventory,A.code,B.id_users AS id_users_request,A.create_by_users,C.name AS users_request,D.name AS created_users"
                . " ,E.level,E.title AS struktur,G.name AS users_aproval,F.id_mrp_type_inventory,F.title AS type_inventory,H.title AS company"
                . " FROM mrp_request AS A "
                . " LEFT JOIN hr_pegawai AS B ON A.id_hr_pegawai = B.id_hr_pegawai"
                . " LEFT JOIN m_users AS C ON B.id_users = C.id_users"
                . " LEFT JOIN m_users AS D ON A.create_by_users = D.id_users"
                . " LEFT JOIN hr_master_organisasi AS E ON B.id_hr_master_organisasi = E.id_hr_master_organisasi"
                . " LEFT JOIN mrp_type_inventory AS F ON A.type_inventory = F.id_mrp_type_inventory"
                . " LEFT JOIN m_users AS G ON A.user_approval = G.id_users"
                . " LEFT JOIN hr_company AS H ON B.id_hr_company = H.id_hr_company"
                . " WHERE A.id_mrp_request='{$v->id_mrp_request}'"
                );
           
            $user_email = $this->global_models->get_query("SELECT C.email"
                    . " FROM alert_email_procurement AS A"
                    . " LEFT JOIN alert_email_procurement_users AS B ON A.id_alert_email_procurement = B.id_alert_email_procurement"
                    . " LEFT JOIN m_users AS C ON B.id_users = C.id_users"
                    . " WHERE A.id_mrp_type_inventory='{$dt_users[0]->id_mrp_type_inventory}'");  
//            print $this->db->last_query();   
//            die;
             $nmr[$k] = 0;
             $email = "";
             foreach ($user_email as $vl) {
                 if($nmr[$k] == 0){
                     $email .= $vl->email;
                 }else{
                     $email .= ",".$vl->email;
                 }
                 $nmr[$k] = $nmr[$k] + 1;
             }
                    
            $lvl = array(1 => "Direktorat",
                         2 => "Divisi",
                         3 => "Department",
                         4 => "Section");    
    $isi = "Dear Procurement,<br><br>"
            . "User {$dt_users[0]->users_aproval} Telah melakukan approval untuk permintaan barang {$dt_users[0]->type_inventory} dengan kode Request: <b>{$dt_users[0]->code}</b>. <br>"
            . " Users Request : {$dt_users[0]->users_request}<br>"
            . " {$lvl[$dt_users[0]->level]}: {$dt_users[0]->struktur}<br>"
            . " Perusahaan: {$dt_users[0]->company}<br>"
            . " Dibawah ini list barang - barang yang di request<br><br>";
//     print $email;
//     die;
if($email){
 $this->email->from('no-reply@antavaya.com', 'System Eprocurement');
 $this->email->to($email);
 $this->email->bcc("hendri.prasetyo@antavaya.com");
 $this->email->subject("Request Barang {$type_array[$dt_users[0]->id_mrp_type_inventory]} Untuk {$dt_users[0]->struktur} Dengan kode Request {$dt_users[0]->code}");
 $this->email->message($isi.$css.$table);
 if($this->email->send() === TRUE){
   $pesan .= "Message has been send to your mail";

 }else{
     $pesan .= "gagal";

 }

 $kirim = array(
     "note"                        => "berhasil terkirim Ke Procurement",
     "status"                      => 2,
     "update_by_users"             => $v->create_by_users,
     "update_date"                 => date("Y-m-d H:i:s")
 );
 $this->global_models->update("temp_alert_email", array("id_temp_alert_email" => $v->id_temp_alert_email),$kirim);

}else{
    
    $kirim = array(
     "note"                        => "Gagal kirim, Email Procurement tidak ada",
     "status"                      => 3,
     "update_by_users"             => $v->create_by_users,
     "update_date"                 => date("Y-m-d H:i:s")
 );
 $this->global_models->update("temp_alert_email", array("id_temp_alert_email" => $v->id_temp_alert_email),$kirim);

}     
        
        
            }    
        
    sleep(3);  
//        die;
     }
     sleep(5);
//   }  
     
  }
  
function notification_blast_po() {
      
       $this->load->library('email');
      $this->email->initialize($this->global_models->email_conf());
     
$css = '<style>body {
  font-family: "Helvetica Neue", Helvetica, Arial;
  font-size: 14px;
  line-height: 20px;
  font-weight: 400;
  color: #3b3b3b;
  -webkit-font-smoothing: antialiased;
  font-smoothing: antialiased;
  #background: #2b2b2b;
}

.wrapper {
  margin: 0 auto;
  padding: 40px;
  max-width: 800px;
}

.table {
  margin: 0 0 40px 0;
  width: 548px;
  box-shadow: 1px 1px 3px rgba(0, 0, 0, 0.2);
  display: table;
}
@media screen and (max-width: 580px) {
  .table {
    display: block;
  }
}

.row {
  display: table-row;
  background: #f6f6f6;
}
.row:nth-of-type(odd) {
  background: #e9e9e9;
}
.row.header {
  font-weight: 900;
  color: #ffffff;
  background: #ea6153;
}
.row.green {
  background: #27ae60;
}
.row.blue {
  background: #2980b9;
}
@media screen and (max-width: 580px) {
  .row {
    padding: 8px 0;
    display: block;
  }
}

.cell {
  padding: 6px 12px;
  display: table-cell;
}
@media screen and (max-width: 580px) {
  .cell {
    padding: 2px 12px;
    display: block;
  }
}</style>';
          
      $get = $this->global_models->get("blast_email_po",array("status <=" => 2));
     
      foreach ($get as $k => $v) {
      
        $get_rgpo = $this->global_models->get_query("SELECT A.array_id_mrp_request,A.code,B.no_po FROM mrp_receiving_goods_po AS A "
        . " LEFT JOIN mrp_po AS B ON A.id_mrp_po = B.id_mrp_po "
        . " WHERE A.id_mrp_receiving_goods_po='{$v->id_mrp_receiving_goods_po}' AND A.status IN(12,6,7)");
//      print $this->db->last_query();
//      print "<pre>";
//      print_r($get);
//      print "</pre>";
//      die;
//        foreach ($get_rgpo as $ky => $val) {
    if($get_rgpo[0]->array_id_mrp_request){     
        $get_req = explode(",",$get_rgpo[0]->array_id_mrp_request);
        $ss = 0;
        foreach ($get_req as $key => $value) {
        $this->global_models->update("mrp_request",array("id_mrp_request" => "{$value}"),array("status_blast" => 1));
        $data[$key] = $this->global_models->get_query("SELECT A.code AS kode_req,A.id_mrp_request,B.code,"
                . " (SELECT CONCAT(D.email,',',D.name) FROM hr_pegawai AS C "
                . " LEFT JOIN m_users AS D ON C.id_users = D.id_users WHERE C.id_hr_pegawai=A.id_hr_pegawai) AS m_request,"
                . " (SELECT CONCAT(F.email,',',F.name) FROM hr_pegawai AS E "
                . " LEFT JOIN m_users AS F ON E.id_users = F.id_users WHERE E.id_hr_pegawai=A.user_pegawai_receiver) AS m_receiver,"
                . " (SELECT G.email FROM m_users AS G WHERE G.id_users = A.create_by_users) AS create_usr " 
        . " FROM mrp_request AS A"
        . " LEFT JOIN mrp_type_inventory AS B ON A.type_inventory = B.id_mrp_type_inventory"
        . " WHERE A.id_mrp_request ='{$value}'");
        
//        print $this->db->last_query();
//        die;
        
        $users_request = explode(",",$data[$key][0]->m_request);
        $users_receiver = explode(",",$data[$key][0]->m_receiver);
        
//        die;
        $data2 = $this->global_models->get_query("SELECT D.name AS inventory_umum,E.title AS brand,F.title AS satuan,"
        . " C.title AS title_spesifik,B.jumlah"
        . " FROM mrp_request AS A"
        . " LEFT JOIN mrp_request_asset AS B ON A.id_mrp_request = B.id_mrp_request"
        . " LEFT JOIN mrp_inventory_spesifik AS C ON B.id_mrp_inventory_spesifik = C.id_mrp_inventory_spesifik"
        . " LEFT JOIN mrp_inventory_umum AS D ON C.id_mrp_inventory_umum = D.id_mrp_inventory_umum"
        . " LEFT JOIN mrp_brand AS E ON C.id_mrp_brand = E.id_mrp_brand"
        . " LEFT JOIN mrp_satuan AS F ON C.id_mrp_satuan = F.id_mrp_satuan"       
        . " WHERE A.id_mrp_request='{$value}' "         
        );
       
        $table = '<div class="table">
            <div class="row header blue">
            <div style="width:2%;" class="cell">
            No
          </div>
            <div class="cell">
            Nama Barang
          </div>
        <div style="width:2%;" class="cell">
          Jumlah Request
        </div>
        </div>';
        $no[$key] = 0;
        foreach ($data2 as $y => $vl) {
            
            if($vl->brand){
                $brand = "<br>Merk:".$vl->brand;
            }else{
                $brand = "";
            }
            
            if($vl->satuan){
                $satuan = "<br>Satuan:".$vl->satuan;
            }else{
                $satuan = "";
            }
            $no[$key] = $no[$key] + 1;
            $name = $vl->inventory_umum." ".$vl->title_spesifik.$brand.$satuan;
    $table .= '<div class="row">'
            . '<div class="cell" >'
            . "{$no[$key]}"
            . "</div>"
            . '<div class="cell">'
            . "{$name}"
            . "</div>"
            . '<div class="cell">'
                . "{$vl->jumlah}"
            . '</div>'
            . '</div>';
        }
        $table .= '</div>';
           
        $this->email->from('no-reply@antavaya.com', 'Administrator E-procurement');
        if($data[$key][0]->create_usr == $users_receiver[0]){
            $this->email->to("{$data[$key][0]->create_usr}");
            $this->email->bcc("hendri.prasetyo@antavaya.com");
        }else{
            $this->email->to("{$data[$key][0]->create_usr}");
            $this->email->cc("{$users_receiver[0]}");
            $this->email->bcc("hendri.prasetyo@antavaya.com");
        }

       $pesan = " Dear Users, <br><br>"
               . " Request {$data[$key][0]->code} sudah diproses oleh procurement<br>"
               . " Users Request: {$users_request[1]}<br>"
               . " Users yang akan menerima barang dari supplier: {$users_receiver[1]}<br>"
                ." No PO  : {$get_rgpo[0]->no_po} <br>"
                ." Code RG: {$get_rgpo[0]->code} <br>"
                . "Berikut Request {$data[$key][0]->code} yang diproses:<br><br>";
//              print $pesan.$css.$table;
//              die;
//           $kcs .= $pesan.$css.$table;   
    $this->email->subject("Notifikasi Proses PO dengan kode Request {$data[$key][0]->kode_req}");
    $this->email->message($pesan.$css.$table);
    
        if($this->email->send() === TRUE){
//            $ss++;
          $this->global_models->update("blast_email_po",array("id_blast_email_po" => "{$v->id_blast_email_po}"),array("status" => 3));
          $pesan2 = "New Password has been send to your mail";
        }else{
          $this->global_models->update("blast_email_po",array("id_blast_email_po" => "{$v->id_blast_email_po}"),array("status" => 2));
          $pesan2 = "gagal";
        }
    
}
  }

    }
//    echo $this->email->print_debugger();
    
//      print $kcs;
//    print $ss;
    print $pesan2;
    die;
  }
  public function blast_rg(){
       $this->load->library('email');
      $this->email->initialize($this->global_models->email_conf());
      $css = '<style>body {
  font-family: "Helvetica Neue", Helvetica, Arial;
  font-size: 14px;
  line-height: 20px;
  font-weight: 400;
  color: #3b3b3b;
  -webkit-font-smoothing: antialiased;
  font-smoothing: antialiased;
  #background: #2b2b2b;
}

.wrapper {
  margin: 0 auto;
  padding: 40px;
  max-width: 800px;
}

.table {
  margin: 0 0 40px 0;
  width: 548px;
  box-shadow: 1px 1px 3px rgba(0, 0, 0, 0.2);
  display: table;
}
@media screen and (max-width: 580px) {
  .table {
    display: block;
  }
}

.row {
  display: table-row;
  background: #f6f6f6;
}
.row:nth-of-type(odd) {
  background: #e9e9e9;
}
.row.header {
  font-weight: 900;
  color: #ffffff;
  background: #ea6153;
}
.row.green {
  background: #27ae60;
}
.row.blue {
  background: #2980b9;
}
@media screen and (max-width: 580px) {
  .row {
    padding: 8px 0;
    display: block;
  }
}

.cell {
  padding: 6px 12px;
  display: table-cell;
}
@media screen and (max-width: 580px) {
  .cell {
    padding: 2px 12px;
    display: block;
  }
}</style>';
     $get = $this->global_models->get_query("SELECT B.array_id_mrp_request,B.code AS code_rg,C.no_po,D.days,DATE_ADD(C.tanggal_po,INTERVAL D.days DAY) AS tgl "
              . " FROM blast_email_po AS A"
              . " LEFT JOIN mrp_receiving_goods_po AS B ON A.id_mrp_receiving_goods_po = B.id_mrp_receiving_goods_po"
              . " LEFT JOIN mrp_po AS C ON B.id_mrp_po = C.id_mrp_po"
              . " LEFT JOIN setting_blast_email_rg AS D ON C.id_mrp_supplier = D.id_mrp_supplier"
              . " WHERE A.reminder = 1 AND D.status=1"
              . " GROUP BY A.id_mrp_receiving_goods_po");
//     print $this->db->last_query();
//     die;
//    $aa = $get[0]->array_id_mrp_request;
//    $id_request = explode(",", $aa);
//    foreach ($id_request as $k => $v) {
//        $this->global_models->get("mrp_request",array("id_mrp_request" => "{$v}","blast_rg"));
//    }
    foreach ($get as $k => $v) {
    $start = $v->tgl;
    $now = date("Y-m-d");
//     die;
    $day = 86400;
    $sTime = strtotime($start); // Start as time  
    $eTime = strtotime($now); // End as time  
    $numDays = round(($eTime - $sTime) / $day);
        if($numDays >= 0){
            $id_request = explode(",", $v->array_id_mrp_request);
            foreach ($id_request as $ky => $vl) {
            $gt = $this->global_models->get("mrp_request",array("id_mrp_request" => "{$vl}","status_blast" => "1","status <=" => "7"));
           
                if($gt[0]->id_mrp_request){
                   $dt = $this->global_models->get_query("SELECT A.jumlah,A.rg,(A.jumlah - A.rg)AS sisa,C.name AS inventory_umum,D.title AS brand,E.title AS satuan,B.title AS title_spesifik "
                            . " FROM mrp_request_asset AS A"
                            . " LEFT JOIN mrp_inventory_spesifik AS B ON A.id_mrp_inventory_spesifik = B.id_mrp_inventory_spesifik"
                            . " LEFT JOIN mrp_inventory_umum AS C ON C.id_mrp_inventory_umum = B.id_mrp_inventory_umum"
                            . " LEFT JOIN mrp_brand AS D ON B.id_mrp_brand = D.id_mrp_brand"
                            . " LEFT JOIN mrp_satuan AS E ON B.id_mrp_satuan = E.id_mrp_satuan" 
                            . " WHERE A.id_mrp_request='{$gt[0]->id_mrp_request}'");
//                print $this->db->last_query();
//                die;
            $table = '<div class="table">
                <div class="row header blue">
                <div style="width:2%;" class="cell">
                No
            </div>
                <div class="cell">
                Nama Barang
                </div>
            <div style="width:2%;" class="cell">
                Jumlah Request
            </div>
            <div style="width:2%;" class="cell">
                Jumlah Yang Diterima
            </div>
            <div style="width:2%;" class="cell">
                Jumlah Yang Belum diterima
            </div>
            </div>';
        $no[$ky] = 0;
        foreach ($dt as $key => $val) {
         if($val->sisa > 0){
            if($val->brand){
                $brand = "<br>Merk:".$val->brand;
            }else{
                $brand = "";
            }
            
            if($val->satuan){
                $satuan = "<br>Satuan:".$val->satuan;
            }else{
                $satuan = "";
            }
            $no[$ky] = $no[$ky] + 1;
            $name = $val->inventory_umum." ".$val->title_spesifik.$brand.$satuan;
    $table .= '<div class="row">'
            . '<div class="cell" >'
            . "{$no[$ky]}"
            . "</div>"
            . '<div class="cell">'
            . "{$name}"
            . "</div>"
            . '<div class="cell">'
                . "{$val->jumlah}"
            . '</div>'
            . '<div class="cell">'
                . "{$val->rg}"
            . '</div>'
            . '<div class="cell">'
                . "{$val->sisa}"
            . '</div>'  
            . '</div>';
         }
        }
        $table .= '</div>';  
            $data = $this->global_models->get_query("SELECT A.code AS kode_req,A.id_mrp_request,B.code,"
                . " (SELECT CONCAT(D.email,',',D.name) FROM hr_pegawai AS C "
                . " LEFT JOIN m_users AS D ON C.id_users = D.id_users WHERE C.id_hr_pegawai=A.id_hr_pegawai) AS m_request,"
                . " (SELECT CONCAT(F.email,',',F.name) FROM hr_pegawai AS E "
                . " LEFT JOIN m_users AS F ON E.id_users = F.id_users WHERE E.id_hr_pegawai=A.user_pegawai_receiver) AS m_receiver,"
                . " (SELECT G.email FROM m_users AS G WHERE G.id_users = A.create_by_users) AS create_usr " 
        . " FROM mrp_request AS A"
        . " LEFT JOIN mrp_type_inventory AS B ON A.type_inventory = B.id_mrp_type_inventory"
        . " WHERE A.id_mrp_request ='{$gt[0]->id_mrp_request}'");
        
        $users_request = explode(",",$data[0]->m_request);
        $users_receiver = explode(",",$data[0]->m_receiver);
//        print $this->db->last_query();
//        die;
//        print_r($data);
//        die;
        
       $this->email->from('no-reply@antavaya.com', 'Administrator E-procurement');
        if($data[0]->create_usr == $users_receiver[0]){
            $this->email->to("{$data[0]->create_usr}");
            $this->email->bcc("hendri.prasetyo@antavaya.com");
//            $this->email->to("hendri.prasetyo@antavaya.com");
        }else{
            $this->email->to("{$data[0]->create_usr}");
//            $this->email->to("dhenjo@gmail.com");
//            $this->email->to("hendri.prasetyo@antavaya.com");
            $this->email->cc("{$users_receiver[0]}");
            $this->email->bcc("hendri.prasetyo@antavaya.com");
        }

       $pesan = " Dear Users, <br><br>"
               . " Request {$data[0]->code} sudah diproses oleh Procurement<br>"
               . " Users Request: <b>{$users_request[1]}</b><br>"
               . " Users yang akan menerima barang dari supplier: <b>{$users_receiver[1]}</b><br>"
               . " Kode Request Orders:<b> {$data[0]->kode_req} </b><br>"
                ." No PO  :<b>{$v->no_po} </b><br>"
                ." Kode RG:<b> {$v->code_rg} </b> <br>"
               . " Apabila <b>{$users_receiver[1]}</b> sudah menerima barang dari supplier, <b>{$users_receiver[1]}</b> wajib mengisi RG di system, Apabila tidak melakukan RG di system, <br>"
               . " maka untuk Request {$data[0]->code} selanjutnya tidak dapat dilakukan karena system akan Blok permintaan berikutnya. <br>"
               . " System akan membuka kembali setelah Users telah mengisi RG di system sesuai dengan yang di Request <br>"
                . "Berikut data RG {$data[0]->code} yang harus di input:<br><br>";
        
//       print $pesan.$css.$table;
//       die;
    $this->email->subject("Notifikasi RG pada kode request {$data[0]->kode_req}");
    $this->email->message($pesan.$css.$table);
    
        if($this->email->send() === TRUE){
//          $this->global_models->update("blast_email_po",array("id_blast_email_po" => "{$v->id_blast_email_po}"),array("status" => 3));
          $pesan2 = "New Password has been send to your mail";
        }else{
//          $this->global_models->update("blast_email_po",array("id_blast_email_po" => "{$v->id_blast_email_po}"),array("status" => 2));
          $pesan2 = "gagal";
        }
       
    }
           
            }
        }
    }
        if($pesan2){
            print $pesan2;
        }else{
            print "data kosong";
        }
       
        die;
    
  }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */