<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Blast_mail extends MX_Controller {
    
  function __construct() {      
    //$this->menu = $this->cek();
    
//    $this->debug($this->menu, true);
  }
  
  public function index(){
     
      $this->load->library('email');
      $this->email->initialize($this->global_models->email_conf());
//      print "a";
     $data = $this->global_models->get_query("SELECT A.id_mrp_po,A.id_mrp_task_orders,A.id_mrp_receiving_goods_po,B.id_mrp_receiving_goods_po,C.tanggal_po,"
      . "C.create_date,A.code AS code_rg,CURDATE() AS tanggal_sekarang,ADDDATE(C.tanggal_po, INTERVAL D.days DAY) as required_date"
      . ",D.id_mrp_supplier,days,blast_email "
      . " FROM mrp_receiving_goods_po  AS A "
      . " LEFT JOIN mrp_receiving_goods AS B ON A.id_mrp_receiving_goods_po = B.id_mrp_receiving_goods_po "
      . " LEFT JOIN mrp_po AS C ON A.id_mrp_po = C.id_mrp_po "
      . " LEFT JOIN setting_blast_email_rg AS D ON C.id_mrp_supplier = D.id_mrp_supplier "
      . " WHERE B.id_mrp_receiving_goods_po IS NULL "
//      . "AND C.tanggal_po <= CURDATE() "
      . "AND C.status!=7 "
      . " AND (D.id_mrp_supplier IS NOT NULL AND D.status = 1)"
    );
//    print_r($data);
     
     
//      . "AND C.tanggal_po <= CURDATE() "
//      . "AND C.status!=7 "
//      . " AND (D.id_mrp_supplier IS NOT NULL AND D.status = 1)"
    
//    print $this->db->last_query();
//    die;
     $pesan = "";
    
    foreach ($data as $key => $val) {
      if($val->required_date == date("Y-m-d")){
      $data2 = $this->global_models->get_query("SELECT D.id_mrp_request,D.create_by_users,G.email,H.email AS email2 "
      . " FROM mrp_po  AS A "
      . " LEFT JOIN mrp_po_asset AS B ON A.id_mrp_po = B.id_mrp_po "
      . " LEFT JOIN mrp_task_orders_request AS C ON B.id_mrp_task_orders = C.id_mrp_task_orders "
      . " LEFT JOIN mrp_request AS D ON C.id_mrp_request = D.id_mrp_request "
      . " LEFT JOIN mrp_request_asset AS E ON (D.id_mrp_request = E.id_mrp_request AND B.id_mrp_inventory_spesifik = E.id_mrp_inventory_spesifik)"
      . " LEFT JOIN hr_pegawai AS F ON D.id_hr_pegawai = F.id_hr_pegawai"
      . " LEFT JOIN m_users AS G ON F.id_users = G.id_users"
      . " LEFT JOIN m_users AS H ON D.create_by_users = H.id_users"
      . " WHERE A.id_mrp_po ='{$val->id_mrp_po}' AND B.id_mrp_task_orders = '{$val->id_mrp_task_orders}' "
      . " GROUP BY D.id_mrp_request");
        $email = array();
        $email2 = array();
        foreach ($data2 as $v) {
            $email[] = $v->email;
            $email2[] = $v->email2;
        }
        $gabung_array   = array_merge($email, $email2);
        $krm_email      = array_unique($gabung_array);
        $data_email     = implode(",", $krm_email);
//        print_r($gabung_array);
//        die;
//      print $this->db->last_query();
//    die;
        $this->email->from('no-reply@antavaya.com', 'Administrator');
        $this->email->to("hendri.prasetyo@antavaya.com");
//        if($val->blast_email == 1){
//            $this->email->to($data_email);
//            $this->email->cc('procurement@antavaya.com');
//        }elseif($val->blast_email == 2){
//            $this->email->to('procurement@antavaya.com');
//        }
        
        $this->email->subject('Diharuskan input Receiving Goods(RG) ke System');
        $this->email->message("Apakah barang pesanan anda sudah diterima ?.
<br>Jika pesanan sudah diterima segera menginput Receiving Goods (RG) di system dengan kode RG <b>{$val->code_rg}</b>.
<br>Abaikan pesan ini jika sudah dilakukan.<br>

Terima kasih");
        if($this->email->send() === TRUE){
          $pesan .= "New Password has been send to your mail";
        }else{
            $pesan .= "gagal";
        }
      }  
    }
   
    print $pesan."<br>";
//    print "<pre>";
//    print_r($data);
//    print "</pre>";
//    print $this->db->last_query();
    die("Sent Email");
  }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */