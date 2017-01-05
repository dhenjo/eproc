<?php
class Mmrp extends CI_Model {

    function __construct()
    {
        parent::__construct();
//        $this->load->database();
//        $this->load->library('PHPExcel');
    }
    
    function mutasi_rg($id_mrp_stock_in = 0,$id_mrp_receiving_goods_po = 0,$id_mrp_inventory_spesifik = 0,$id_mrp_request = 0,$id_rg_department = 0,$tgl_diterima = "",$note = ""){
            
    $id = $this->global_models->get_field("mrp_receiving_goods_po", "id_mrp_task_orders",array("id_mrp_receiving_goods_po" => "{$id_mrp_receiving_goods_po}"));
     
    if($id_mrp_request > 0){
        $id_request = " AND B.id_mrp_request='$id_mrp_request'";
    }else{
       $id_request = "";
    }
    
    $data2 = $this->global_models->get_query("SELECT B.id_hr_pegawai,A.id_mrp_request,"
        . "C.jumlah,C.rg,C.id_mrp_request_asset,D.id_hr_master_organisasi,D.id_hr_company"
        . " FROM mrp_task_orders_request AS A"
        . " INNER JOIN mrp_request AS B ON A.id_mrp_request = B.id_mrp_request"
        . " INNER JOIN mrp_request_asset AS C ON B.id_mrp_request = C.id_mrp_request"
        . " INNER JOIN hr_pegawai AS D ON B.id_hr_pegawai = D.id_hr_pegawai"
        . " WHERE A.id_mrp_task_orders='{$id}' AND C.id_mrp_inventory_spesifik='$id_mrp_inventory_spesifik' {$id_request}"
        );  
       
    $data = $this->global_models->get_query("SELECT *"
        . " FROM mrp_stock_in AS A"
        . " WHERE A.id_mrp_stock_in={$id_mrp_stock_in} AND status=1");  
       
        $stock_akhir = $this->global_models->get_field("mrp_stock","stock_out", array("id_mrp_stock" => $data[0]->id_mrp_stock));
        $stock_awal = $this->global_models->get_field("mrp_stock","stock_in", array("id_mrp_stock" => $data[0]->id_mrp_stock));
    
//    if($stock_akhir >= $dt_jumlah){
        
        
        $lt_stock_out = $stock_akhir + $data[0]->jumlah;
        $hsl_stock = $stock_awal - $lt_stock_out;

            $krm = array(
            "stock_out"                         => $lt_stock_out,
            "stock_akhir"                       => $hsl_stock,
            "update_by_users"                   => $this->session->userdata("id"),
            "update_date"                       => date("Y-m-d H:i:s")
        );
        $this->global_models->update("mrp_stock", array("id_mrp_stock" => $data[0]->id_mrp_stock),$krm);
       
        $jumlah_stock = $data[0]->jumlah;
        $fix_rg4 = 0;
        foreach ($data2 as $ky => $val) {
        $req_user[$ky] = $val->jumlah - $val->rg;
        $this->olah_stock_out_code($kode);
            
        if($jumlah_stock >= $req_user[$ky]){
            if($req_user[$ky] > 0){
                $kirim = array(
                "id_mrp_stock_in"                      => $id_mrp_stock_in,
                "id_mrp_stock"                         => $data[0]->id_mrp_stock,                            
                "id_hr_pegawai"                        => $val->id_hr_pegawai,
                "id_hr_master_organisasi"              => $val->id_hr_master_organisasi,
                "id_hr_company"                        => $val->id_hr_company,
                "id_mrp_inventory_spesifik"            => $id_mrp_inventory_spesifik,
                "id_mrp_satuan"                        => $data[0]->id_mrp_satuan,
                "jumlah"                               => $req_user[$ky],
                "code"                                 => $kode,     
                "harga"                                => $data[0]->harga,
                "status"                               => 1,
                "tanggal"                              => $data[0]->tanggal_diterima,
                "create_by_users"                      => $this->session->userdata("id"),
                "create_date"                          => date("Y-m-d H:i:s")
              );
            $dtid_out = $this->global_models->insert("mrp_stock_out", $kirim);
             
            $krm = array(
            "id_mrp_stock_out"                  => $dtid_out,
            "id_hr_pegawai"                     => $val->id_hr_pegawai,
            "id_hr_master_organisasi"           => $val->id_hr_master_organisasi,
            "id_hr_company"                     => $val->id_hr_company,
            "id_mrp_inventory_spesifik"         => $id_mrp_inventory_spesifik,
            "id_mrp_satuan"                     => $data[0]->id_mrp_satuan,
            "jumlah"                            => $req_user[$ky],
            "harga"                             => $data[0]->harga,
            "status"                            => 1,
            "tanggal"                           => $data[0]->tanggal_diterima,
            "create_by_users"                   => $this->session->userdata("id"),
            "create_date"                       => date("Y-m-d H:i:s")
            );
            $this->global_models->insert("mrp_report", $krm);
      
           $kirim = array(
            "id_mrp_receiving_goods_department"            => $id_rg_department,
            "id_mrp_stock"                         => $data[0]->id_mrp_stock,    
            "id_mrp_satuan"                     => $data[0]->id_mrp_satuan,
            "jumlah"                            => $req_user[$ky],
            "id_mrp_inventory_spesifik"         => $id_mrp_inventory_spesifik,
            "harga"                             => $data[0]->harga,
            "status"                            => 1,
            "id_hr_master_organisasi"              => $val->id_hr_master_organisasi,   
            "create_by_users"                   => $this->session->userdata("id"),
            "create_date"                       => date("Y-m-d H:i:s")
          );
            $this->global_models->insert("mrp_receiving_goods_detail_department", $kirim);
    
        
              $dt_rg[$ky] = $val->rg + $req_user[$ky];
                $krm = array(
                  "rg"                                => $dt_rg[$ky],
                  "update_by_users"                   => $this->session->userdata("id"),
                  "update_date"                       => date("Y-m-d H:i:s")
              );
              $this->global_models->update("mrp_request_asset", array("id_mrp_request_asset" => $val->id_mrp_request_asset),$krm);

            }
           $jumlah_stock = ($jumlah_stock - $req_user[$ky]);
        }elseif($jumlah_stock > 0){
            
            $jml = $jumlah_stock;
            $this->olah_stock_out_code($kode);
            $kirim2 = array(
            "id_mrp_stock_in"                      => $id_mrp_stock_in,
            "id_mrp_stock"                         => $data[0]->id_mrp_stock,                            
            "id_hr_pegawai"                        => $val->id_hr_pegawai,
            "code"                                 => $kode,  
            "id_hr_master_organisasi"              => $val->id_hr_master_organisasi,
            "id_hr_company"                        => $val->id_hr_company,
            "id_mrp_inventory_spesifik"            => $id_mrp_inventory_spesifik,
            "id_mrp_satuan"                        => $data[0]->id_mrp_satuan,
            "jumlah"                               => $jml,  
            "harga"                                => $data[0]->harga,
            "status"                               => 1,
            "tanggal"                              => $data[0]->tanggal_diterima,
            "create_by_users"                      => $this->session->userdata("id"),
            "create_date"                          => date("Y-m-d H:i:s")
          );
        $dtid_out = $this->global_models->insert("mrp_stock_out", $kirim2);
        
        $krm = array(
            "id_mrp_stock_out"          => $dtid_out,
            "id_hr_pegawai"             => $val->id_hr_pegawai,
            "id_hr_master_organisasi"   => $val->id_hr_master_organisasi,
            "id_hr_company"             => $val->id_hr_company,
            "id_mrp_inventory_spesifik" => $id_mrp_inventory_spesifik,
            "id_mrp_satuan"             => $data[0]->id_mrp_satuan,
            "jumlah"                    => $jml,
            "harga"                     => $data[0]->harga,
            "status"                    => 1,
            "tanggal"                   => $data[0]->tanggal_diterima,
            "create_by_users"           => $this->session->userdata("id"),
            "create_date"               => date("Y-m-d H:i:s")
        );
        $this->global_models->insert("mrp_report", $krm);
        
             
         $kirim = array(
            "id_mrp_receiving_goods_department"     => $id_rg_department,
            "id_mrp_satuan"                         => $data[0]->id_mrp_satuan,
            "jumlah"                                => $jml,
            "id_mrp_inventory_spesifik"             => $id_mrp_inventory_spesifik,
            "harga"                                 => $data[0]->harga,
            "status"                                => 1,
            "id_hr_master_organisasi"               => $val->id_hr_master_organisasi,  
            "create_by_users"                       => $this->session->userdata("id"),
            "create_date"                           => date("Y-m-d H:i:s")
          );
        $this->global_models->insert("mrp_receiving_goods_detail_department", $kirim);
        
          $fix_rg4 = $this->session->userdata("jml_rg_dpt") + 1;
           $set = array(
            "jml_rg_dpt"          => $fix_rg4
            );
         $this->session->set_userdata($set);
        if($req_user[$ky] > 0){
            $dt_rg[$ky] = $val->rg + $jml;
          $krm2 = array(
            "rg"                                => $dt_rg[$ky],
            "update_by_users"                   => $this->session->userdata("id"),
            "update_date"                       => date("Y-m-d H:i:s")
        );
        $this->global_models->update("mrp_request_asset", array("id_mrp_request_asset" => $val->id_mrp_request_asset),$krm2);
       
        }
         $jumlah_stock =($jumlah_stock - $jml);
        }
        
        if($id_request == ""){
            if($this->session->userdata("jml_rg_dpt") > 0){
              
                $krm2 = array(
                "status"                            => 7,
                "update_by_users"                   => $this->session->userdata("id"),
                "update_date"                       => date("Y-m-d H:i:s")
             );
             $this->global_models->update("mrp_request", array("id_mrp_request" => $val->id_mrp_request),$krm2);
             }else{
               $krm2 = array(
                "status"                            => 8,
                "update_by_users"                   => $this->session->userdata("id"),
                "update_date"                       => date("Y-m-d H:i:s")
             );
             $this->global_models->update("mrp_request", array("id_mrp_request" => $val->id_mrp_request),$krm2);

            }
        } 
        
       }
         $kirim2 = array(
            "status"                        => 2,
            "jumlah_out"                    => $data[0]->jumlah,
            "update_by_users"               => $this->session->userdata("id"),
            "update_date"                   => date("Y-m-d H:i:s")
        );
        $this->global_models->update("mrp_stock_in", array("id_mrp_stock_in" => $id_mrp_stock_in),$kirim2);
        
        $kirim2 = array(
            "status"                        => 2,
            "jumlah_out"                    => $data[0]->jumlah,
            "update_by_users"               => $this->session->userdata("id"),
            "update_date"                   => date("Y-m-d H:i:s")
        );
        $this->global_models->update("mrp_stock_in", array("id_mrp_stock_in" => $id_mrp_stock_in),$kirim2);
        
        if($id_mrp_request > 0){
           
//            print $this->session->userdata("jml_rg_dpt")."ces<br>";
            if($this->session->userdata("jml_rg_dpt") > 0){
              
                $krm2 = array(
                "status"                            => 7,
                "update_by_users"                   => $this->session->userdata("id"),
                "update_date"                       => date("Y-m-d H:i:s")
            );
            $this->global_models->update("mrp_request", array("id_mrp_request" => $id_mrp_request),$krm2);
            }else{
               $krm2 = array(
                "status"                            => 8,
                "update_by_users"                   => $this->session->userdata("id"),
                "update_date"                       => date("Y-m-d H:i:s")
            );
            $this->global_models->update("mrp_request", array("id_mrp_request" => $id_mrp_request),$krm2);

         }
        }
//    }else{
//       return false;
//    die;
//    }
    
    }
    
   private function olah_stock_out_code(&$kode){
    $this->load->helper('string');
    $kode_random = random_string('alnum', 6);
    $st_upper = strtoupper($kode_random);
    $kode = "SOT".$st_upper;
    $cek = $this->global_models->get_field("mrp_stock_out", "id_mrp_stock_out", array("code" => $kode));
    if($cek > 0){
      $this->olah_stock_out_code($kode);
    }
  }
    
    function test($id_product_tour_book){
      $payment = $this->global_models->get_query("SELECT *"
        . " FROM product_tour_book_payment"
        . " WHERE id_product_tour_book = '{$id_product_tour_book}'"
        . " AND status = 7"
        . " AND tampil IS NULL");
      foreach($payment AS $py){
        if($py->pos == 1)
          $pos = 2;
        else
          $pos = 1;
        $kirim[] = array(
          "id_product_tour_book"        => $id_product_tour_book,
          "id_currency"                 => 2,
          "id_users"                    => $payment[0]->id_users,
          "nominal"                     => $py->nominal,
          "tanggal"                     => date("Y-m-d H:i:s"),
          "pos"                         => $pos,
          "status"                      => $py->status,
          "tampil"                      => 2,
          "payment"                     => $py->payment,
          "status_payment"              => $py->status_payment,
          "note"                        => "Rev {$py->note}",
          "create_by_users"             => $this->session->userdata("id"),
          "create_date"                 => date("Y-m-d H:i:s"),
        );
        $this->global_models->update("product_tour_book_payment", array("id_product_tour_book_payment" => $py->id_product_tour_book_payment), array("tampil" => 2));
      }
      if($kirim)
        $this->global_models->insert_batch("product_tour_book_payment", $kirim);
      
      $tanggungan = $this->global_models->get_query("SELECT SUM(CASE WHEN pos = 1 THEN nominal ELSE 0 END) AS debit"
        . " ,SUM(CASE WHEN pos = 2 THEN nominal ELSE 0 END) AS kredit"
        . " FROM product_tour_book_payment"
        . " WHERE id_product_tour_book = '{$id_product_tour_book}'"
        . " AND (status = 0 OR status = 6)"
        . " AND tampil IS NULL");
      $sisa = $tanggungan[0]->debit - $tanggungan[0]->kredit;
      $ppn = 1/100 * $sisa;
      
      $this->global_models->insert("product_tour_book_payment", array(
        "id_product_tour_book"        => $id_product_tour_book,
        "id_currency"                 => 2,
        "nominal"                     => $ppn,
        "tanggal"                     => date("Y-m-d H:i:s"),
        "pos"                         => 1,
        "status"                      => 7,
        "note"                        => "PPN 1% ".  number_format($sisa),
        "create_by_users"             => $this->session->userdata("id"),
        "create_date"                 => date("Y-m-d H:i:s"),
      ));
      
      return true;
    }
}
?>
