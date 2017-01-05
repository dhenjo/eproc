<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mrp_ajax_stock extends MX_Controller {
  function __construct(){
    $this->menu = $this->cek();
  }
  
    function get_stock($start = 0){
//      $id_mrp_supplier = 1;
//       $url = base_url()."themes/".DEFAULTTHEMES."/img/ajax-loader.gif";
//      $where = "WHERE A.status >= 3 AND A.id_mrp_po = '$id_mrp_po'  ";
       $where = "WHERE A.status = 1 ";
       
    $data = $this->global_models->get_query("SELECT C.name AS nama_barang,E.title AS satuan,D.title AS brand,"
        . " A.stock_akhir,A.id_mrp_inventory_spesifik,B.title AS title_spesifik,A.id_mrp_stock"
        . " FROM mrp_stock AS A"
        . " LEFT JOIN mrp_inventory_spesifik AS B ON A.id_mrp_inventory_spesifik = B.id_mrp_inventory_spesifik"
        . " LEFT JOIN mrp_inventory_umum AS C ON B.id_mrp_inventory_umum = C.id_mrp_inventory_umum"
        . " LEFT JOIN mrp_brand AS D ON B.id_mrp_brand = D.id_mrp_brand"
        . " LEFT JOIN mrp_satuan AS E ON A.id_mrp_satuan = E.id_mrp_satuan"
//        . " LEFT JOIN mrp_task_orders_request_asset AS F ON A.id_mrp_po = F.id_mrp_po"
        . " {$where}"
        . " GROUP BY A.id_mrp_inventory_spesifik"
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
           $title_spesifik = "";
       }
       
       if($da->brand){
           $brn = "<br>Brand: ".$da->brand;
       }else{
           $brn = "";
       }
       
       $brng = $da->nama_barang.$title_spesifik.$brn;
        $hasil[] = array(
         "<a data-toggle='collapse' data-parent='#accordion'  href='#collapseOne' onclick='coba_data({$da->id_mrp_stock});'>".$brng."</a>"
         . "<script>"
            . "function coba_data(id){"
                . 'var table = '
                . '$("#tableboxy1").dataTable({'
                  . '"order": [[ 0, "desc" ]],'
                . '"bDestroy": true'
                . '});'
                . "table.fnClearTable();"
                . 'ambil_data5(table, 0,id);'
                 
                 . 'var table = '
                . '$("#tableboxy2").dataTable({'
                  . '"order": [[ 0, "desc" ]],'
                . '"bDestroy": true'
                . '});'
                . "table.fnClearTable();"
                . 'ambil_data6(table, 0,id);'
            . "}"
        
        . 'function ambil_data5(table, mulai,id){'
        . '$.post("'.site_url("mrp/mrp-ajax-stock/ajax-history-stock-in").'/"+id+"/"+mulai, function(data){'
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
                 
      . 'function ambil_data6(table, mulai,id){'
        . '$.post("'.site_url("mrp/mrp-ajax-stock/ajax-history-stock-out").'/"+id+"/"+mulai, function(data){'
          . '$("#loader-page").show();'
          . 'var hasil = $.parseJSON(data);'
          . 'if(hasil.status == 2){'
            . 'table.fnAddData(hasil.hasil);'
            . 'ambil_data6(table, hasil.start,id);'
          . '}'
          . 'else{'
            . '$("#loader-page").hide();'
          . '}'
        . '});'
      . '}'
            
            . "</script>",            
        $da->satuan,
        $da->stock_akhir,
        "<div class='btn-group'>"
 . "<a href='".site_url("mrp/mrp-stock/mutasi-stock/{$da->id_mrp_stock}")."' type='button' class='btn btn-info btn-flat' title='Mutasi Stock' style='width: 40px'><i class='fa fa-table'></i></a>"
//        . "<a href='".site_url("mrp/po/")."' type='button' class='btn btn-info btn-flat' title='Purchase Order' style='width: 40px'><i class='fa fa-exchange'></i></a>"
      . "</div>"   
      );
         
    }
    
    $return['hasil'] = $hasil;
//    $this->debug($return, true);
    print json_encode($return);
    die;
  }
  
  function ajax_history_stock_in($id_mrp_stock = 0,$start = 0){
   $where = "WHERE A.id_mrp_stock= '{$id_mrp_stock}'";
       
    $data = $this->global_models->get_query("SELECT C.name AS nama_barang,E.title AS satuan,"
        . " A.jumlah,A.harga,A.id_mrp_inventory_spesifik,B.title AS title_spesifik,A.id_mrp_stock,A.create_date,"
        . " A.tanggal_diterima,A.kode,G.code AS code_rg,F.id_mrp_receiving_goods,D.title AS brand"
        . " FROM mrp_stock_in AS A"
        . " LEFT JOIN mrp_inventory_spesifik AS B ON A.id_mrp_inventory_spesifik = B.id_mrp_inventory_spesifik"
        . " LEFT JOIN mrp_inventory_umum AS C ON B.id_mrp_inventory_umum = C.id_mrp_inventory_umum"
        . " LEFT JOIN mrp_brand AS D ON B.id_mrp_brand = D.id_mrp_brand"
        . " LEFT JOIN mrp_satuan AS E ON A.id_mrp_satuan = E.id_mrp_satuan"
        . " LEFT JOIN mrp_receiving_goods_detail AS F ON A.id_mrp_receiving_goods_detail = F.id_mrp_receiving_goods_detail"
        . " LEFT JOIN mrp_receiving_goods AS G ON F.id_mrp_receiving_goods = G.id_mrp_receiving_goods"    
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
           $title_spesifik = "";
       }
       
        if($da->brand){
           $brn = "<br>Brand: ".$da->brand;
       }else{
           $brn = "";
       }
      
       $tgl ="";
    if($da->create_date !="" AND $da->create_date != "0000-00-00"){
        $tgl = date("d M Y H:i:s", strtotime($da->create_date));
    }
    $tgl_trma = "";
    if($da->tanggal_diterima !="" AND $da->tanggal_diterima != "0000-00-00"){
        $tgl_trma = date("d M Y", strtotime($da->tanggal_diterima));
    }
    
       $brng = $da->nama_barang.$title_spesifik.$brn."<br>Satuan".$da->satuan."<br>Stock Masuk:".$da->jumlah."<br>Harga:".number_format($da->harga);
        $hasil[] = array(
        $tgl,
        $brng,
        $da->kode,
        $tgl_trma,
         "<a data-toggle='collapse' data-parent='#accordion'  href='#collapseTwo' onclick='coba_data1({$da->id_mrp_receiving_goods},{$da->id_mrp_inventory_spesifik});'>".$da->code_rg."</a>"
        . "<script>"
            . "function coba_data1(id,id_spesifik){"
                . 'var table = '
                . '$("#tableboxy3").dataTable({'
                  . '"order": [[ 1, "asc" ]],'
                . '"bDestroy": true'
                . '});'
                . "table.fnClearTable();"
                . 'ambil_data7(table, 0,id,id_spesifik);'
            . "}"
        
        . 'function ambil_data7(table, mulai,id,id_spesifik){'
        . '$.post("'.site_url("mrp/mrp-ajax-stock/list-history-detail-rg-stock").'/"+id+"/"+id_spesifik+"/"+mulai, function(data){'
          . '$("#loader-page").show();'
          . 'var hasil = $.parseJSON(data);'
          . 'if(hasil.status == 2){'
            . 'table.fnAddData(hasil.hasil);'
            . 'ambil_data7(table, hasil.start,id,id_spesifik);'
          . '}'
          . 'else{'
            . '$("#loader-page").hide();'
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
  
  function ajax_stock_mutasi_pegawai($id_users = 0){
    
        $where = "WHERE id_users='{$id_users}'";
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
        
       if($this->session->userdata("id") == 1 OR $this->nbscache->get_olahan("permission", $this->session->userdata("id_privilege"), "search-user-ro", "edit") !== FALSE){
           $qry = "";
       }else{
           $qry = " AND B.id_hr_master_organisasi IN ($k)";
       }
        
    if (empty($_GET['term'])) exit ;
    $q = strtolower($_GET["term"]);
    if (get_magic_quotes_gpc()) $q = stripslashes($q);
    $items = $this->global_models->get_query("SELECT A.id_users,A.name,A.email,B.id_hr_pegawai,C.title AS department,D.code"
      . " FROM m_users AS A"
      . " LEFT JOIN hr_pegawai AS B ON A.id_users = B.id_users"
      . " LEFT JOIN hr_master_organisasi AS C ON B.id_hr_master_organisasi = C.id_hr_master_organisasi"
      . " LEFT JOIN hr_company AS D ON B.id_hr_company = D.id_hr_company"
      . " WHERE A.id_users > 1 AND (LOWER(A.name) LIKE '%{$q}%' OR LOWER(C.title) LIKE '%{$q}%' OR LOWER(D.code) LIKE '%{$q}%' OR LOWER(A.email) LIKE '%{$q}%' $qry)"
      . " LIMIT 0,10"
      );
    if(count($items) > 0){
      foreach($items as $tms){
            if($tms->code){
                   $kode = "<".$tms->code.">";
               }else{
                   $kode = "";
               }
               
               if($tms->department){
                   $dept = "<".$tms->department.">";
                   $result[] = array(
                    "id"    => $tms->id_hr_pegawai,
                    "label" => $tms->name." <".$tms->email.">".$dept.$kode,
                    "value" => $tms->name." <".$tms->email.">".$dept.$kode,
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
  
  function update_mutasi_stock($id_mrp_stock = 0){
    $id_pegawai = $_POST['id_pegawai'];
    $dt_jumlah = $jumlah =  $_POST['jumlah'];
    $tanggal    =  $_POST['tanggal'];
    
    $hr_pegawai = $this->global_models->get("hr_pegawai", array("id_hr_pegawai" => $id_pegawai)); 
    $stock_akhir = $this->global_models->get_field("mrp_stock","stock_akhir", array("id_mrp_stock" => $id_mrp_stock));
    
    $data = $this->global_models->get_query("SELECT *"
        . " FROM mrp_stock_in AS A"
        . " WHERE A.id_mrp_stock={$id_mrp_stock} AND status=1"
        . " ORDER BY A.tanggal_diterima ASC");  
       
    if($stock_akhir >= $dt_jumlah){
        $total = $stock_akhir - $dt_jumlah;
        $lt_stock_out = $stock_akhir + $dt_jumlah;
        if($stock_akhir > 0){
            $krm = array(
            "stock_out"                         => $lt_stock_out,
            "stock_akhir"                       => $total,
            "update_by_users"                   => $this->session->userdata("id"),
            "update_date"                       => date("Y-m-d H:i:s")
        );
        $this->global_models->update("mrp_stock", array("id_mrp_stock" => $id_mrp_stock),$krm);
        }
        

        foreach ($data as $ky => $val) {
        $dt_hasil = $val->jumlah - $val->jumlah_out;
        if($dt_hasil <= $jumlah){
           $jml = $val->jumlah_out + $dt_hasil;
           
         $kirim2 = array(
            "status"                        => 2,
            "jumlah_out"                    => $jml,
            "update_by_users"               => $this->session->userdata("id"),
            "update_date"                   => date("Y-m-d H:i:s")
        );
        $this->global_models->update("mrp_stock_in", array("id_mrp_stock_in" => $val->id_mrp_stock_in),$kirim2);

        $kirim = array(
            "id_mrp_stock_in"                      => $val->id_mrp_stock_in,
            "id_mrp_stock"                         => $id_mrp_stock,                            
            "id_hr_pegawai"                        => $id_pegawai,
            "id_hr_master_organisasi"              => $hr_pegawai[0]->id_hr_master_organisasi,
            "id_hr_company"                        => $hr_pegawai[0]->id_hr_company,
            "id_mrp_inventory_spesifik"            => $val->id_mrp_inventory_spesifik,
            "id_mrp_satuan"                        => $val->id_mrp_satuan,
            "jumlah"                               => $dt_hasil,  
            "harga"                                => $val->harga,
            "status"                               => 1,
            "tanggal"                              => $tanggal,
            "create_by_users"                      => $this->session->userdata("id"),
            "create_date"                          => date("Y-m-d H:i:s")
          );
         $this->global_models->insert("mrp_stock_out", $kirim);

              $jumlah = ($jumlah - $dt_hasil);

          }elseif($jumlah > 0){
              $jml = $val->jumlah_out + $jumlah;
              $kirim2 = array(
                "status"                        => 1,
                "jumlah_out"                    => $jml,
                "update_by_users"               => $this->session->userdata("id"),
                "update_date"                   => date("Y-m-d H:i:s")
            );
            $this->global_models->update("mrp_stock_in", array("id_mrp_stock_in" => $val->id_mrp_stock_in),$kirim2);

        $kirim = array(
            "id_mrp_stock_in"                      => $val->id_mrp_stock_in,
            "id_mrp_stock"                         => $id_mrp_stock,                            
            "id_hr_pegawai"                        => $id_pegawai,
            "id_hr_master_organisasi"              => $hr_pegawai[0]->id_hr_master_organisasi,
            "id_hr_company"                        => $hr_pegawai[0]->id_hr_company,
            "id_mrp_inventory_spesifik"            => $val->id_mrp_inventory_spesifik,
            "id_mrp_satuan"                        => $val->id_mrp_satuan,
            "jumlah"                               => $jumlah,  
            "harga"                                => $val->harga,
            "status"                               => 1,
            "tanggal"                              => $tanggal,
            "create_by_users"                      => $this->session->userdata("id"),
            "create_date"                          => date("Y-m-d H:i:s")
          );

        $this->global_models->insert("mrp_stock_out", $kirim);
        
        $this->session->set_flashdata('success', 'Data Tersimpan');
        die;
          }
        }
    }else{
       $this->session->set_flashdata('notice', 'Data tidak tersimpan');
    die;
    }

}

  function update_pemakaian_stock($id_mrp_inventory_spesifik = 0){
    $pst = $_POST;
    $jumlah     = $pst['jumlah'];
    $note       = $pst['note'];
    $tanggal    =  $pst['tanggal'];
    $total      =  $pst['total'];
    
    $hr_pegawai = $this->global_models->get("hr_pegawai", array("id_users" => $this->session->userdata("id"))); 
//    $data       = $this->global_models->get("mrp_stock_out", array("id_mrp_inventory_spesifik" => $id_mrp_inventory_spesifik)); 
//    $stock_akhir = $this->global_models->get_field("mrp_stock","stock_akhir", array("id_mrp_stock" => $id_mrp_stock));
//    
  
    $data = $this->global_models->get_query("SELECT *"
        . " FROM mrp_stock_out AS A"
        . " WHERE A.id_mrp_inventory_spesifik='{$id_mrp_inventory_spesifik}' AND id_hr_master_organisasi='{$hr_pegawai[0]->id_hr_master_organisasi}' AND  status=1"
        . " ORDER BY A.tanggal ASC");  
      
//    print "<pre>";
//    print_r($data);
//    print "</pre>";
//    die;
    if($total >= $jumlah){
      
        foreach ($data as $ky => $val) {
        $dt_hasil = $val->jumlah - ($val->pemakaian + $val->mutasi);
       
        if($dt_hasil <= $jumlah){
//        print "tes";
//        die('1');
           $jml = $val->pemakaian + $dt_hasil;
           
         $kirim2 = array(
            "status"                        => 2,
            "pemakaian"                     => $jml,
            "update_by_users"               => $this->session->userdata("id"),
            "update_date"                   => date("Y-m-d H:i:s")
        );
        $this->global_models->update("mrp_stock_out", array("id_mrp_stock_out" => $val->id_mrp_stock_out),$kirim2);
//        print $this->db->last_query()."1<br>";
        $kirim = array(
            "id_mrp_stock_out"                     => $val->id_mrp_stock_out,
            "id_hr_pegawai"                        => $hr_pegawai[0]->id_hr_pegawai,
            "id_hr_master_organisasi"              => $hr_pegawai[0]->id_hr_master_organisasi,
            "id_hr_company"                        => $hr_pegawai[0]->id_hr_company,
            "id_mrp_inventory_spesifik"            => $val->id_mrp_inventory_spesifik,
            "id_mrp_satuan"                        => $val->id_mrp_satuan,
            "jumlah"                               => $jumlah,  
            "harga"                                => $val->harga,
            "status"                               => 1,
            "tanggal"                              => $tanggal,
            "note"                                 => $note,
            "create_by_users"                      => $this->session->userdata("id"),
            "create_date"                          => date("Y-m-d H:i:s")
          );
         $this->global_models->insert("mrp_stock_out_department", $kirim);
//         print $this->db->last_query()."2<br>";
              $jumlah = ($jumlah - $dt_hasil);

          }elseif($jumlah > 0){
//               print "tes";
//        die('2');
              $jml = $val->pemakaian + $jumlah;
              $kirim2 = array(
                "status"                        => 1,
                "pemakaian"                    => $jml,
                "update_by_users"               => $this->session->userdata("id"),
                "update_date"                   => date("Y-m-d H:i:s")
            );
            $this->global_models->update("mrp_stock_out", array("id_mrp_stock_out" => $val->id_mrp_stock_out),$kirim2);
//        print $this->db->last_query()."3<br>";
          $kirim = array(
             "id_mrp_stock_out"                     => $val->id_mrp_stock_out,
            "id_hr_pegawai"                        => $hr_pegawai[0]->id_hr_pegawai,
            "id_hr_master_organisasi"              => $hr_pegawai[0]->id_hr_master_organisasi,
            "id_hr_company"                        => $hr_pegawai[0]->id_hr_company,
            "id_mrp_inventory_spesifik"            => $val->id_mrp_inventory_spesifik,
            "id_mrp_satuan"                        => $val->id_mrp_satuan,
            "jumlah"                               => $jml,  
            "harga"                                => $val->harga,
            "status"                               => 1,
            "tanggal"                              => $tanggal,
            "note"                                 => $note,
            "create_by_users"                      => $this->session->userdata("id"),
            "create_date"                          => date("Y-m-d H:i:s")
          );

        $this->global_models->insert("mrp_stock_out_department", $kirim);
//        print $this->db->last_query()."4<br>";
        
        $this->session->set_flashdata('success', 'Data Tersimpan');
        die;
          }
        }
    }else{
       $this->session->set_flashdata('notice', 'Data tidak tersimpan');
    die;
    }

}

  function update_mutasi_stock_department($id_mrp_inventory_spesifik = 0){
    $pst = $_POST;
    $jumlah     = $pst['jumlah'];
    $note       = $pst['note'];
    $tanggal    =  $pst['tanggal'];
    $total    =  $pst['total'];
    $id_users    =  $pst['id_users'];
    
    $hr_pegawai = $this->global_models->get("hr_pegawai", array("id_users" => "{$this->session->userdata('id')}")); 
//    $data       = $this->global_models->get("mrp_stock_out", array("id_mrp_inventory_spesifik" => $id_mrp_inventory_spesifik)); 
//    $stock_akhir = $this->global_models->get_field("mrp_stock","stock_akhir", array("id_mrp_stock" => $id_mrp_stock));
//    
    $hr_pegawai_rt = $this->global_models->get("hr_master_organisasi", array("parent" => "{$hr_pegawai[0]->id_hr_master_organisasi}"));
    $no = 0;
    $aa = $hr_pegawai[0]->id_hr_master_organisasi;
    foreach ($hr_pegawai_rt as $ky => $val) {
        if($no > 0){
            $aa .= ",".$val->id_hr_master_organisasi;
            $hr_pegawai2 = $this->global_models->get("hr_master_organisasi", array("parent" => "{$val->id_hr_master_organisasi}"));
            if($hr_pegawai2[0]->id_hr_master_organisasi){
                foreach ($hr_pegawai2 as $ky2 => $val2) {
                    $aa .= ",".$val2->id_hr_master_organisasi;
                 $hr_pegawai3 = $this->global_models->get("hr_master_organisasi", array("parent" => "{$val2->id_hr_master_organisasi}"));
                
                    if($hr_pegawai3[0]->id_hr_master_organisasi){
                        foreach ($hr_pegawai3 as $ky3 => $val3) {
                            $aa .= ",".$val3->id_hr_master_organisasi;
                        }
                    } 
                }
            }
        }else{
            $aa .= ",".$val->id_hr_master_organisasi;
        }
        $no++;        
    }
    
    $data = $this->global_models->get_query("SELECT *"
        . " FROM mrp_stock_out AS A"
        . " WHERE A.id_mrp_inventory_spesifik='{$id_mrp_inventory_spesifik}' AND A.id_hr_master_organisasi IN ({$aa}) AND status=1"
        . " ORDER BY A.tanggal ASC");  
     
//        print $this->db->last_query();
//        print "<pre>";
//        print_r($data);
//        print "</pre>";
//        die;
        
    if($total >= $jumlah){

        foreach ($data as $ky => $val) {
        $dt_hasil = $val->jumlah - ($val->pemakaian + $val->mutasi);
        if($dt_hasil <= $jumlah){
           $jml = $val->mutasi + $dt_hasil;
           
         $kirim2 = array(
            "status"                        => 2,
            "mutasi"                        => $jml,
            "update_by_users"               => $this->session->userdata("id"),
            "update_date"                   => date("Y-m-d H:i:s")
        );
        $this->global_models->update("mrp_stock_out", array("id_mrp_stock_out" => $val->id_mrp_stock_out),$kirim2);

        $kirim = array(
            "id_mrp_stock_out"                     => $val->id_mrp_stock_out,
            "id_hr_pegawai"                        => $hr_pegawai[0]->id_hr_pegawai,
            "id_hr_master_organisasi"              => $hr_pegawai[0]->id_hr_master_organisasi,
            "id_hr_company"                        => $hr_pegawai[0]->id_hr_company,
            "id_mrp_inventory_spesifik"            => $val->id_mrp_inventory_spesifik,
            "id_mrp_satuan"                        => $val->id_mrp_satuan,
            "jumlah"                               => $jml,  
            "harga"                                => $val->harga,
            "user_penerima"                        => $id_users,
            "status"                               => 2,
            "tanggal"                              => $tanggal,
            "note"                                 => $note,
            "create_by_users"                      => $this->session->userdata("id"),
            "create_date"                          => date("Y-m-d H:i:s")
          );
        $this->global_models->insert("mrp_stock_out_department", $kirim);

            $jumlah = ($jumlah - $dt_hasil);

          }elseif($jumlah > 0){
              $jml = $val->mutasi + $jumlah;
              $kirim2 = array(
                "status"                        => 1,
                "mutasi"                        => $jml,
                "update_by_users"               => $this->session->userdata("id"),
                "update_date"                   => date("Y-m-d H:i:s")
            );
            $this->global_models->update("mrp_stock_out", array("id_mrp_stock_out" => $val->id_mrp_stock_out),$kirim2);

          $kirim = array(
             "id_mrp_stock_out"                    => $val->id_mrp_stock_out,
            "id_hr_pegawai"                        => $hr_pegawai[0]->id_hr_pegawai,
            "id_hr_master_organisasi"              => $hr_pegawai[0]->id_hr_master_organisasi,
            "id_hr_company"                        => $hr_pegawai[0]->id_hr_company,
            "id_mrp_inventory_spesifik"            => $val->id_mrp_inventory_spesifik,
            "id_mrp_satuan"                        => $val->id_mrp_satuan,
            "jumlah"                               => $jml,
            "user_penerima"                        => $id_users,  
            "harga"                                => $val->harga,
            "status"                               => 2,
            "tanggal"                              => $tanggal,
            "note"                                 => $note,
            "create_by_users"                      => $this->session->userdata("id"),
            "create_date"                          => date("Y-m-d H:i:s")
          );

        $this->global_models->insert("mrp_stock_out_department", $kirim);
        
        $this->session->set_flashdata('success', 'Data Tersimpan');
        die;
          }
        }
    }else{
       $this->session->set_flashdata('notice', 'Data tidak tersimpan');
    die;
    }

}

  function list_history_detail_rg_stock($id_mrp_receiving_goods = 0,$id_mrp_inventory_spesifik = 0,$start = 0){
//      $id_mrp_supplier = 1;
//       $url = base_url()."themes/".DEFAULTTHEMES."/img/ajax-loader.gif";
      $where = "WHERE A.id_mrp_receiving_goods = '$id_mrp_receiving_goods' AND A.id_mrp_inventory_spesifik = '$id_mrp_inventory_spesifik'  ";

    $data = $this->global_models->get_query("SELECT A.jumlah AS jumlah_diterima,A.note,C.name AS nama_barang,E.title AS satuan"
            . ",B.title AS title_spesifik,G.name,D.title AS brand"
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
  
 function ajax_stock_in($id_mrp_stock = 0,$start = 0){
   $where = "WHERE A.id_mrp_stock= '{$id_mrp_stock}'";
       
    $data = $this->global_models->get_query("SELECT C.name AS nama_barang,E.title AS satuan,"
        . " A.id_mrp_stock_in,A.status,A.kode,A.jumlah,A.jumlah_out,A.harga,A.id_mrp_inventory_spesifik,B.title AS title_spesifik,A.id_mrp_stock,A.create_date,"
        . " A.tanggal_diterima,G.code AS code_rg,F.id_mrp_receiving_goods,D.title AS brand"
        . " FROM mrp_stock_in AS A"
        . " LEFT JOIN mrp_inventory_spesifik AS B ON A.id_mrp_inventory_spesifik = B.id_mrp_inventory_spesifik"
        . " LEFT JOIN mrp_inventory_umum AS C ON B.id_mrp_inventory_umum = C.id_mrp_inventory_umum"
        . " LEFT JOIN mrp_brand AS D ON B.id_mrp_brand = D.id_mrp_brand"
        . " LEFT JOIN mrp_satuan AS E ON A.id_mrp_satuan = E.id_mrp_satuan"
        . " LEFT JOIN mrp_receiving_goods_detail AS F ON A.id_mrp_receiving_goods_detail = F.id_mrp_receiving_goods_detail"
        . " LEFT JOIN mrp_receiving_goods AS G ON F.id_mrp_receiving_goods = G.id_mrp_receiving_goods"    
        . " {$where}"
        . " LIMIT {$start}, 4");
//        print $acs = $this->db->last_query();
//        die;
    if(count($data) > 0){
      $return['status'] = 2;
      $return['start']  = $start + 4;
    }
    else{
      $return['status'] = 1;
    }
    
    
    foreach ($data AS $da){
       if($da->title_spesifik){
           $title_spesifik = " ".$da->title_spesifik;
       }else{
           $title_spesifik = "";
       }
      
       $tgl ="";
    if($da->create_date !="" AND $da->create_date != "0000-00-00"){
        $tgl = date("d M Y H:i:s", strtotime($da->create_date));
    }
    $tgl_trma = "";
    if($da->tanggal_diterima !="" AND $da->tanggal_diterima != "0000-00-00"){
        $tgl_trma = date("d M Y", strtotime($da->tanggal_diterima));
    }
    $brn = "";
    if($da->brand){
        $brn = "<br>Brand:".$da->brand;
    }
     $status = array( 1=> "<span class='label bg-green'>STOCK</span>", 2 => "<span class='label bg-red'>HABIS</span>");
    
       $brng = $da->nama_barang.$title_spesifik.$brn;
        $hasil[] = array(
        $tgl,
        "<a data-toggle='collapse' data-parent='#accordion'  href='#collapseTwo' onclick='coba_data({$da->id_mrp_stock_in});'>".$da->kode."</a>"
         . "<script>"
            . "function coba_data(id){"
                . '$("#dt-stock-out").show();'
                . '$("#dt-rg").hide();'
                 . 'var table = '
                . '$("#tableboxy3").dataTable({'
                  . '"order": [[ 0, "desc" ]],'
                . '"bDestroy": true'
                . '});'
                . "table.fnClearTable();"
                . 'ambil_data6(table, 0,id);'
            . "}"
             
      . 'function ambil_data6(table, mulai,id){'
        . '$.post("'.site_url("mrp/mrp-ajax-stock/ajax-stock-out").'/"+id+"/"+mulai, function(data){'
          . '$("#loader-page").show();'
          . 'var hasil = $.parseJSON(data);'
          . 'if(hasil.status == 2){'
            . 'table.fnAddData(hasil.hasil);'
            . 'ambil_data6(table, hasil.start,id);'
          . '}'
          . 'else{'
            . '$("#loader-page").hide();'
          . '}'
        . '});'
      . '}'
            
            . "</script>",    
        $brng,   
        $da->satuan,
        $da->jumlah,
        $da->jumlah_out,    
        number_format($da->harga),
        $tgl_trma,
        $status[$da->status],    
          "<a data-toggle='collapse' data-parent='#accordion'  href='#collapseTwo' onclick='coba_data2({$da->id_mrp_receiving_goods},{$da->id_mrp_inventory_spesifik});'>".$da->code_rg."</a>"
         . "<script>"
            . "function coba_data2(id,id_spesifik){"
               . '$("#dt-rg").show();'
               . '$("#dt-stock-out").hide();'
                 . 'var table = '
                . '$("#tableboxy4").dataTable({'
                  . '"order": [[ 0, "desc" ]],'
                . '"bDestroy": true'
                . '});'
                . "table.fnClearTable();"
                . 'ambil_data7(table, 0,id,id_spesifik);'
            . "}"
             
      . 'function ambil_data7(table, mulai,id,id_spesifik){'
        . '$.post("'.site_url("mrp/mrp-ajax-stock/list-history-detail-rg-stock").'/"+id+"/"+id_spesifik+"/"+mulai, function(data){'
          . '$("#loader-page").show();'
          . 'var hasil = $.parseJSON(data);'
          . 'if(hasil.status == 2){'
            . 'table.fnAddData(hasil.hasil);'
            . 'ambil_data7(table, hasil.start,id,id_spesifik);'
          . '}'
          . 'else{'
            . '$("#loader-page").hide();'
          . '}'
        . '});'
      . '}'
            . "</script>",  
      );
         
    }
    
    $return['hasil'] = $hasil;
//    $this->debug($return, true);
    print json_encode($return);
    die;
  }
  
    function ajax_stock_out($id_mrp_stock_in = 0,$start = 0){
    $where = "WHERE A.id_mrp_stock_in= '{$id_mrp_stock_in}'";
       
    $data = $this->global_models->get_query("SELECT C.name AS nama_barang,E.title AS satuan,"
        . " A.jumlah,A.harga,A.id_mrp_inventory_spesifik,B.title AS title_spesifik,A.id_mrp_stock,A.create_date,A.tanggal,"
        . " I.name AS nama_users,G.title AS department, H.code AS code_company,D.title AS brand,"
        . " J.kode AS code_stock_in"
        . " FROM mrp_stock_out AS A"
        . " LEFT JOIN mrp_inventory_spesifik AS B ON A.id_mrp_inventory_spesifik = B.id_mrp_inventory_spesifik"
        . " LEFT JOIN mrp_inventory_umum AS C ON B.id_mrp_inventory_umum = C.id_mrp_inventory_umum"
        . " LEFT JOIN mrp_brand AS D ON B.id_mrp_brand = D.id_mrp_brand"
        . " LEFT JOIN mrp_satuan AS E ON A.id_mrp_satuan = E.id_mrp_satuan"
        . " LEFT JOIN hr_pegawai AS F ON A.id_hr_pegawai = F.id_hr_pegawai"
        . " LEFT JOIN hr_master_organisasi AS G ON A.id_hr_master_organisasi = G.id_hr_master_organisasi"
        . " LEFT JOIN hr_company AS H ON A.id_hr_company = H.id_hr_company"
        . " LEFT JOIN m_users AS I ON F.id_users = I.id_users"
        . " LEFT JOIN mrp_stock_in AS J ON A.id_mrp_stock_in = J.id_mrp_stock_in"
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
           $title_spesifik = "";
       }
      
       $tgl ="";
    if($da->create_date !="" AND $da->create_date != "0000-00-00"){
        $tgl = date("d M Y H:i:s", strtotime($da->create_date));
    }
    $tgl_kluar = "";
    if($da->tanggal !="" AND $da->tanggal != "0000-00-00"){
        $tgl_terima = date("d M Y", strtotime($da->tanggal));
    }
    
    $brn = "";
    if($da->brand){
     $brn = "<br>Brand:".$da->brand;   
    }
    
       $brng = $da->nama_barang.$title_spesifik.$brn."<br>Satuan:".$da->satuan."<br>Harga:".number_format($da->harga)."<br>Stock:".$da->jumlah;
       $pegawai = $da->nama_users."<br>Department:".$da->department."<br>Company:".$da->code_company;
        $hasil[] = array(
        $tgl,
        $brng,
        $da->code_stock_in,
        $pegawai,
        $tgl_terima    
      );
         
    }
    
    $return['hasil'] = $hasil;
//    $this->debug($return, true);
    print json_encode($return);
    die;
}

  function ajax_history_stock_out($id_mrp_stock = 0,$start = 0){
    $where = "WHERE A.id_mrp_stock= '{$id_mrp_stock}'";
       
    $data = $this->global_models->get_query("SELECT C.name AS nama_barang,E.title AS satuan,"
        . " A.jumlah,A.harga,A.id_mrp_inventory_spesifik,B.title AS title_spesifik,A.id_mrp_stock,A.create_date,A.tanggal,"
        . " I.name AS nama_users,G.title AS department, H.code AS code_company,D.title AS brand,"
        . " J.kode AS code_stock_in"
        . " FROM mrp_stock_out AS A"
        . " LEFT JOIN mrp_inventory_spesifik AS B ON A.id_mrp_inventory_spesifik = B.id_mrp_inventory_spesifik"
        . " LEFT JOIN mrp_inventory_umum AS C ON B.id_mrp_inventory_umum = C.id_mrp_inventory_umum"
        . " LEFT JOIN mrp_brand AS D ON B.id_mrp_brand = D.id_mrp_brand"
        . " LEFT JOIN mrp_satuan AS E ON A.id_mrp_satuan = E.id_mrp_satuan"
        . " LEFT JOIN hr_pegawai AS F ON A.id_hr_pegawai = F.id_hr_pegawai"
        . " LEFT JOIN hr_master_organisasi AS G ON A.id_hr_master_organisasi = G.id_hr_master_organisasi"
        . " LEFT JOIN hr_company AS H ON A.id_hr_company = H.id_hr_company"
        . " LEFT JOIN m_users AS I ON F.id_users = I.id_users"
        . " LEFT JOIN mrp_stock_in AS J ON A.id_mrp_stock_in = J.id_mrp_stock_in"
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
           $title_spesifik = "";
       }
      
       $tgl ="";
    if($da->create_date !="" AND $da->create_date != "0000-00-00"){
        $tgl = date("d M Y H:i:s", strtotime($da->create_date));
    }
    $tgl_kluar = "";
    if($da->tanggal !="" AND $da->tanggal != "0000-00-00"){
        $tgl_terima = date("d M Y", strtotime($da->tanggal));
    }
    
    if($da->brand){
           $brn = "<br>Brand:".$da->brand;
       }else{
           $brn = "";
       }
    
       $brng = $da->nama_barang.$title_spesifik.$brn."<br>Satuan:".$da->satuan."<br>Harga:".number_format($da->harga)."<br>Stock:".$da->jumlah;
       $pegawai = $da->nama_users."<br>Department:".$da->department."<br>Company:".$da->code_company;
        $hasil[] = array(
        $tgl,
        $brng,
        $da->code_stock_in,
        $pegawai,
        $tgl_terima    
      );
         
    }
    
    $return['hasil'] = $hasil;
//    $this->debug($return, true);
    print json_encode($return);
    die;
}

   function get_stock_department($start = 0){
//      $id_mrp_supplier = 1;
//       $url = base_url()."themes/".DEFAULTTHEMES."/img/ajax-loader.gif";
//      $where = "WHERE A.status >= 3 AND A.id_mrp_po = '$id_mrp_po'  ";
       
    $hr_pegawai = $this->global_models->get("hr_master_organisasi", array("parent" => "{$this->session->userdata('stock_dept_search_id_hr_master')}"));
    $no = 0;
    $aa = $this->session->userdata('stock_dept_search_id_hr_master');
    foreach ($hr_pegawai as $ky => $val) {
        if($no > 0){
            $aa .= ",".$val->id_hr_master_organisasi;
            $hr_pegawai2 = $this->global_models->get("hr_master_organisasi", array("parent" => "{$val->id_hr_master_organisasi}"));
            if($hr_pegawai2[0]->id_hr_master_organisasi){
                foreach ($hr_pegawai2 as $ky2 => $val2) {
                    $aa .= ",".$val2->id_hr_master_organisasi;
                 $hr_pegawai3 = $this->global_models->get("hr_master_organisasi", array("parent" => "{$val2->id_hr_master_organisasi}"));
                
                    if($hr_pegawai3[0]->id_hr_master_organisasi){
                        foreach ($hr_pegawai3 as $ky3 => $val3) {
                            $aa .= ",".$val3->id_hr_master_organisasi;
                        }
                    } 
                }
            }
        }else{
            $aa .= ",".$val->id_hr_master_organisasi;
        }
        $no++;        
    }
//    A.id_hr_master_organisasi IN ($aa)
    if($this->session->userdata('stock_dept_search_id_company')){
        $where = "WHERE B.jenis= 1 AND A.id_hr_master_organisasi IN ($aa) AND A.id_hr_company ='{$this->session->userdata('stock_dept_search_id_company')}' ";  
    }
    
//    if($this->session->userdata("id") == 1){
//           $id_users = 9;
//    }
//       
//       $hr_pegawai = $this->global_models->get("hr_pegawai", array("id_users" => $id_users));

       
    $data = $this->global_models->get_query("SELECT C.name AS nama_barang,E.title AS satuan,D.title AS brand,"
        . " SUM(A.jumlah - (A.pemakaian + A.mutasi)) AS jumlah,A.id_mrp_inventory_spesifik,B.title AS title_spesifik,A.id_mrp_stock"
        . " FROM mrp_stock_out AS A"
        . " LEFT JOIN mrp_inventory_spesifik AS B ON A.id_mrp_inventory_spesifik = B.id_mrp_inventory_spesifik"
        . " LEFT JOIN mrp_inventory_umum AS C ON B.id_mrp_inventory_umum = C.id_mrp_inventory_umum"
        . " LEFT JOIN mrp_brand AS D ON B.id_mrp_brand = D.id_mrp_brand"
        . " LEFT JOIN mrp_satuan AS E ON A.id_mrp_satuan = E.id_mrp_satuan"
//        . " LEFT JOIN mrp_task_orders_request_asset AS F ON A.id_mrp_po = F.id_mrp_po"
        . " {$where}"
        . " GROUP BY A.id_mrp_inventory_spesifik"
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
           $title_spesifik = " ".$da->title_spesifik."<br>";
       }else{
           $title_spesifik = "";
       }
       
       if($da->brand){
           $brn = "<br>Brand: ".$da->brand;
       }else{
           $brn = "";
       }
       $brng = $da->nama_barang.$title_spesifik.$brn;
        $hasil[] = array(
        $brng,            
        $da->satuan,
        $da->jumlah,
        "<div class='btn-group'>"
 . "<a href='".site_url("mrp/mrp-stock/stock-department-detail/2/{$da->id_mrp_inventory_spesifik}")."' type='button' class='btn btn-info btn-flat' title='List Stock' style='width: 40px'><i class='fa fa-th-list'></i></a>"
//        . "<a href='".site_url("mrp/po/")."' type='button' class='btn btn-info btn-flat' title='Purchase Order' style='width: 40px'><i class='fa fa-exchange'></i></a>"
      . "</div>"   
      );
         
    }
    
    $return['hasil'] = $hasil;
//    $this->debug($return, true);
    print json_encode($return);
    die;
  }
  
    function get_stock_department_detail($type = 0,$id_mrp_inventory_spesifik = 0,$start = 0){
//      $id_mrp_supplier = 1;
//       $url = base_url()."themes/".DEFAULTTHEMES."/img/ajax-loader.gif";
//      $where = "WHERE A.status >= 3 AND A.id_mrp_po = '$id_mrp_po'  ";
         
    $hr_pegawai = $this->global_models->get("hr_master_organisasi", array("parent" => "{$this->session->userdata('stock_dept_search_id_hr_master')}"));
    $no = 0;
    $aa = $this->session->userdata('stock_dept_search_id_hr_master');
    foreach ($hr_pegawai as $ky => $val) {
        if($no > 0){
            $aa .= ",".$val->id_hr_master_organisasi;
            $hr_pegawai2 = $this->global_models->get("hr_master_organisasi", array("parent" => "{$val->id_hr_master_organisasi}"));
            if($hr_pegawai2[0]->id_hr_master_organisasi){
                foreach ($hr_pegawai2 as $ky2 => $val2) {
                    $aa .= ",".$val2->id_hr_master_organisasi;
                 $hr_pegawai3 = $this->global_models->get("hr_master_organisasi", array("parent" => "{$val2->id_hr_master_organisasi}"));
                
                    if($hr_pegawai3[0]->id_hr_master_organisasi){
                        foreach ($hr_pegawai3 as $ky3 => $val3) {
                            $aa .= ",".$val3->id_hr_master_organisasi;
                        }
                    } 
                }
            }
        }else{
            $aa .= ",".$val->id_hr_master_organisasi;
        }
        $no++;        
    }
//    A.id_hr_master_organisasi IN ($aa)
    if($this->session->userdata('stock_dept_search_id_company')){
        $where = "WHERE B.jenis = 1 AND A.id_hr_master_organisasi IN ($aa) AND A.id_hr_company ='{$this->session->userdata('stock_dept_search_id_company')}' ";  
    }
    
//       if($this->session->userdata("id") == 1){
//           $id_users = 9;
//       }
//      $hr_pegawai = $this->global_models->get("hr_pegawai", array("id_users" => $id_users));
//       
//       $where = "WHERE A.id_hr_master_organisasi ='{$hr_pegawai[0]->id_hr_master_organisasi}' AND A.id_hr_company ='{$hr_pegawai[0]->id_hr_company}' ";
//       
       if($id_mrp_inventory_spesifik > 0){
           $where .= " AND A.id_mrp_inventory_spesifik ='{$id_mrp_inventory_spesifik}'";
       }

            $data = $this->global_models->get_query("SELECT A.code,C.name AS nama_barang,E.title AS satuan,"
             . " A.jumlah,A.harga,A.id_mrp_inventory_spesifik,B.title AS title_spesifik,"
             . " A.id_mrp_stock,A.create_date,A.tanggal,A.status,A.pemakaian,A.mutasi,A.note,"
             . " I.name AS nama_users,G.title AS department, H.code AS code_company,D.title AS brand,A.note"
             . " FROM mrp_stock_out AS A"
             . " LEFT JOIN mrp_inventory_spesifik AS B ON A.id_mrp_inventory_spesifik = B.id_mrp_inventory_spesifik"
             . " LEFT JOIN mrp_inventory_umum AS C ON B.id_mrp_inventory_umum = C.id_mrp_inventory_umum"
             . " LEFT JOIN mrp_brand AS D ON B.id_mrp_brand = D.id_mrp_brand"
             . " LEFT JOIN mrp_satuan AS E ON A.id_mrp_satuan = E.id_mrp_satuan"
             . " LEFT JOIN hr_pegawai AS F ON A.id_hr_pegawai = F.id_hr_pegawai"
             . " LEFT JOIN hr_master_organisasi AS G ON A.id_hr_master_organisasi = G.id_hr_master_organisasi"
             . " LEFT JOIN hr_company AS H ON A.id_hr_company = H.id_hr_company"
             . " LEFT JOIN m_users AS I ON F.id_users = I.id_users"
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
           $title_spesifik = "";
       }
      
       if($da->brand){
           $brn = "<br>Brand: ".$da->brand;
       }else{
           $brn = "";
       }
       $tgl ="";
    if($da->create_date !="" AND $da->create_date != "0000-00-00"){
        $tgl = date("d M Y H:i:s", strtotime($da->create_date));
    }
    $tgl_kluar = "";
    if($da->tanggal !="" AND $da->tanggal != "0000-00-00"){
        $tgl_terima = date("d M Y", strtotime($da->tanggal));
    }
    
    $dtnote = "";
    if($da->note){
        $dtnote = "<br>Note:".$da->note;
    }
    
    $status = array( 1=> "<span class='label bg-green'>Stock</span>", 
         2 => "<span class='label bg-red'>Habis</span>");
    
       $brng = $da->nama_barang.$title_spesifik.$brn."<br>Satuan:".$da->satuan."<br>Harga Satuan:".number_format($da->harga)."<br>Status:".$status[$da->status].$dtnote;
       $pegawai = $da->nama_users."<br>Department:".$da->department."<br>Company:".$da->code_company;
        $hasil[] = array(
        $tgl."<br>Code:".$da->code,
        $brng,
        $da->jumlah,
        $da->pemakaian,
        $da->mutasi,    
        $pegawai,
        $tgl_terima    
      );
         
    }
    
    $return['hasil'] = $hasil;
//    $this->debug($return, true);
    print json_encode($return);
    die;
  }
  
     function get_detail_pemakaian($type = 0,$id_mrp_inventory_spesifik = 0,$start = 0){
//      $id_mrp_supplier = 1;
//       $url = base_url()."themes/".DEFAULTTHEMES."/img/ajax-loader.gif";
//      $where = "WHERE A.status >= 3 AND A.id_mrp_po = '$id_mrp_po'  ";
         
    $hr_pegawai = $this->global_models->get("hr_master_organisasi", array("parent" => "{$this->session->userdata('stock_dept_search_id_hr_master')}"));
    $no = 0;
    $aa = $this->session->userdata('stock_dept_search_id_hr_master');
    foreach ($hr_pegawai as $ky => $val) {
        if($no > 0){
            $aa .= ",".$val->id_hr_master_organisasi;
            $hr_pegawai2 = $this->global_models->get("hr_master_organisasi", array("parent" => "{$val->id_hr_master_organisasi}"));
            if($hr_pegawai2[0]->id_hr_master_organisasi){
                foreach ($hr_pegawai2 as $ky2 => $val2) {
                    $aa .= ",".$val2->id_hr_master_organisasi;
                 $hr_pegawai3 = $this->global_models->get("hr_master_organisasi", array("parent" => "{$val2->id_hr_master_organisasi}"));
                
                    if($hr_pegawai3[0]->id_hr_master_organisasi){
                        foreach ($hr_pegawai3 as $ky3 => $val3) {
                            $aa .= ",".$val3->id_hr_master_organisasi;
                        }
                    } 
                }
            }
        }else{
            $aa .= ",".$val->id_hr_master_organisasi;
        }
        $no++;        
    }
//    A.id_hr_master_organisasi IN ($aa)
    if($this->session->userdata('stock_dept_search_id_company')){
        $where = "WHERE  (A.id_hr_master_organisasi IN ($aa) OR J.id_hr_master_organisasi IN ($aa)) AND A.id_hr_company ='{$this->session->userdata('stock_dept_search_id_company')}' ";  
    }
    
//       if($this->session->userdata("id") == 1){
//           $id_users = 9;
//       }
//      $hr_pegawai = $this->global_models->get("hr_pegawai", array("id_users" => $id_users));
//       
//       $where = "WHERE A.id_hr_master_organisasi ='{$hr_pegawai[0]->id_hr_master_organisasi}' AND A.id_hr_company ='{$hr_pegawai[0]->id_hr_company}' ";
//       
       if($id_mrp_inventory_spesifik > 0){
           $where .= " AND A.id_mrp_inventory_spesifik ='{$id_mrp_inventory_spesifik}'";
       }
//    $data = $this->global_models->get_query("SELECT C.name AS nama_barang,E.title AS satuan,"
//        . " A.jumlah,A.id_mrp_inventory_spesifik,B.title AS title_spesifik,A.id_mrp_stock"
//        . " FROM mrp_stock_out AS A"
//        . " LEFT JOIN mrp_inventory_spesifik AS B ON A.id_mrp_inventory_spesifik = B.id_mrp_inventory_spesifik"
//        . " LEFT JOIN mrp_inventory_umum AS C ON B.id_mrp_inventory_umum = C.id_mrp_inventory_umum"
//        . " LEFT JOIN mrp_brand AS D ON B.id_mrp_brand = D.id_mrp_brand"
//        . " LEFT JOIN mrp_satuan AS E ON A.id_mrp_satuan = E.id_mrp_satuan"
////        . " LEFT JOIN mrp_task_orders_request_asset AS F ON A.id_mrp_po = F.id_mrp_po"
//        . " {$where}"
//        . " LIMIT {$start}, 10");

            $data = $this->global_models->get_query("SELECT C.name AS nama_barang,E.title AS satuan,"
             . " A.jumlah,A.harga,A.id_mrp_inventory_spesifik,B.title AS title_spesifik,A.create_date,A.create_by_users AS id_users,"
             . " A.create_date,A.tanggal,A.status,A.note,A.user_penerima,A.note,D.title AS brand,"
             . " G.title AS department, H.code AS code_company,I.code,K.name AS received_users"
             . " FROM mrp_stock_out_department AS A"
             . " LEFT JOIN mrp_inventory_spesifik AS B ON A.id_mrp_inventory_spesifik = B.id_mrp_inventory_spesifik"
             . " LEFT JOIN mrp_inventory_umum AS C ON B.id_mrp_inventory_umum = C.id_mrp_inventory_umum"
             . " LEFT JOIN mrp_brand AS D ON B.id_mrp_brand = D.id_mrp_brand"
             . " LEFT JOIN mrp_satuan AS E ON A.id_mrp_satuan = E.id_mrp_satuan"
             . " LEFT JOIN hr_pegawai AS F ON A.id_hr_pegawai = F.id_hr_pegawai"
             . " LEFT JOIN hr_master_organisasi AS G ON A.id_hr_master_organisasi = G.id_hr_master_organisasi"
             . " LEFT JOIN hr_company AS H ON A.id_hr_company = H.id_hr_company"
             . " LEFT JOIN mrp_stock_out AS I ON A.id_mrp_stock_out = I.id_mrp_stock_out"
             . " LEFT JOIN hr_pegawai AS J ON A.user_penerima = J.id_hr_pegawai"
             . " LEFT JOIN m_users AS K ON J.id_users = K.id_users"
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
           $title_spesifik = "";
       }
      
    if($da->tanggal !="" AND $da->tanggal != "0000-00-00"){
        $tgl = date("d M Y", strtotime($da->tanggal));
    }
    $received = "";
    if($da->user_penerima AND $da->status == 3){
        $received = "<br>Received By: ".$da->received_users;
    }
    
    if($da->user_penerima AND $da->status == 4){
        $received = "<br>Rejected By: ".$da->received_users;
    }
    
     if($da->brand){
           $brn = "<br>Brand: ".$da->brand;
       }else{
           $brn = "";
       }
       
    $dtnote = "";
    if($da->note){
        $dtnote = "<br>Note: ".$da->note;
    }
    
     $status = array( 1=> "<span class='label bg-green'>Dipakai</span>", 
         2 => "<span class='label bg-yellow'>Pending Mutasi</span>",
         3 => "<span class='label bg-red'>Mutasi</span>",
         4 => "<span class='label bg-navy'>Reject</span>"
         );
    $name_users = $this->global_models->get_field("m_users","name", array("id_users" => $da->id_users));
  
    if($da->code){
        $code_sot = "<br>Code: ".$da->code;
    }else{
        $code_sot = "";
    }
    $cd = date("d M Y H:i:s", strtotime($da->create_date))."<br>";
       $brng = $da->nama_barang.$title_spesifik.$brn."<br>Satuan:".$da->satuan."<br>Harga:".number_format($da->harga).$received.$dtnote;
       $pegawai = $da->nama_users."<br>Department:".$da->department."<br>Company:".$da->code_company;
        $hasil[] = array(
        $tgl,
        $brng,
        $da->jumlah,
        $status[$da->status],    
        $da->note,
        $cd.$name_users.$code_sot
      );
         
    }
    
    $return['hasil'] = $hasil;
//    $this->debug($return, true);
    print json_encode($return);
    die;
  }
  
  function get_pending_mutasi($start = 0){
//      $id_mrp_supplier = 1;
//       $url = base_url()."themes/".DEFAULTTHEMES."/img/ajax-loader.gif";
//      $where = "WHERE A.status >= 3 AND A.id_mrp_po = '$id_mrp_po'  ";
         
       $hr_pegawai = $this->global_models->get("hr_master_organisasi", array("parent" => "{$this->session->userdata('stock_dept_search_id_hr_master')}"));
    $no = 0;
    $aa = $this->session->userdata('stock_dept_search_id_hr_master');
    foreach ($hr_pegawai as $ky => $val) {
        if($no > 0){
            $aa .= ",".$val->id_hr_master_organisasi;
            $hr_pegawai2 = $this->global_models->get("hr_master_organisasi", array("parent" => "{$val->id_hr_master_organisasi}"));
            if($hr_pegawai2[0]->id_hr_master_organisasi){
                foreach ($hr_pegawai2 as $ky2 => $val2) {
                    $aa .= ",".$val2->id_hr_master_organisasi;
                 $hr_pegawai3 = $this->global_models->get("hr_master_organisasi", array("parent" => "{$val2->id_hr_master_organisasi}"));
                
                    if($hr_pegawai3[0]->id_hr_master_organisasi){
                        foreach ($hr_pegawai3 as $ky3 => $val3) {
                            $aa .= ",".$val3->id_hr_master_organisasi;
                        }
                    } 
                }
            }
        }else{
            $aa .= ",".$val->id_hr_master_organisasi;
        }
        $no++;        
    }
//    A.id_hr_master_organisasi IN ($aa)
    if($this->session->userdata('stock_dept_search_id_company')){
        $where = "WHERE B.jenis=1 AND A.status = 2 AND  (A.id_hr_master_organisasi IN ($aa) OR J.id_hr_master_organisasi IN ($aa)) AND A.id_hr_company ='{$this->session->userdata('stock_dept_search_id_company')}' ";  
    }
    

//       if($id_mrp_inventory_spesifik > 0){
//           $where .= " AND A.id_mrp_inventory_spesifik ='{$id_mrp_inventory_spesifik}'";
//       }

            $data = $this->global_models->get_query("SELECT C.name AS nama_barang,E.title AS satuan,"
             . " A.jumlah,A.harga,A.id_mrp_inventory_spesifik,B.title AS title_spesifik,A.create_by_users AS id_users,"
             . " A.create_date,A.tanggal,A.status,A.note,D.title AS brand,"
             . " G.title AS department, H.code AS code_company,I.code,A.id_mrp_stock_out_department,"
             . " K.name AS users_penerima,K.id_users AS received_users"
             . " FROM mrp_stock_out_department AS A"
             . " LEFT JOIN mrp_inventory_spesifik AS B ON A.id_mrp_inventory_spesifik = B.id_mrp_inventory_spesifik"
             . " LEFT JOIN mrp_inventory_umum AS C ON B.id_mrp_inventory_umum = C.id_mrp_inventory_umum"
             . " LEFT JOIN mrp_brand AS D ON B.id_mrp_brand = D.id_mrp_brand"
             . " LEFT JOIN mrp_satuan AS E ON A.id_mrp_satuan = E.id_mrp_satuan"
             . " LEFT JOIN hr_pegawai AS F ON A.id_hr_pegawai = F.id_hr_pegawai"
             . " LEFT JOIN hr_master_organisasi AS G ON A.id_hr_master_organisasi = G.id_hr_master_organisasi"
             . " LEFT JOIN hr_company AS H ON A.id_hr_company = H.id_hr_company"
             . " LEFT JOIN mrp_stock_out AS I ON A.id_mrp_stock_out = I.id_mrp_stock_out"
             . " LEFT JOIN hr_pegawai AS J ON A.user_penerima = J.id_hr_pegawai"
             . " LEFT JOIN m_users AS K ON J.id_users = K.id_users"
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
           $title_spesifik = "";
       }
      
    if($da->tanggal !="" AND $da->tanggal != "0000-00-00"){
        $tgl = date("d M Y", strtotime($da->tanggal));
    }
    
    if($da->brand){
        $brn = "Brand: ".$da->brand."<br>";
    }else{
        $brn = "";
    }
    
     $status = array( 1=> "<span class='label bg-green'>Dipakai</span>", 
         2 => "<span class='label bg-yellow'>Pending Mutasi</span>",
         3 => "<span class='label bg-red'>Mutasi</span>",
         4 => "<span class='label bg-navy'>Reject</span>"
         );
    
    $name_users = $this->global_models->get_field("m_users","name", array("id_users" => $da->id_users));
    $btn_received = "";
    $btn_reject = "";
    if($this->session->userdata('id') == $da->received_users){
    $btn_received =  "<div class='btn-group'>"
    . "<a href='".site_url("mrp/mrp-stock/proses-pending-mutasi/1/{$da->id_mrp_stock_out_department}")."' type='button' class='btn btn-info btn-flat' title='Approved' style='width: 40px'><i class='fa fa-check'></i></a>"
    . "</div>";
    $btn_reject = "<a href='".site_url("mrp/mrp-stock/proses-pending-mutasi/2/{$da->id_mrp_stock_out_department}")."' type='button' class='btn btn-danger btn-flat' title='Reject' style='width: 40px'><i class='fa fa-times'></i></a>";        
    }   
    $brng = $da->nama_barang.$title_spesifik."<br>".$brn."Satuan:".$da->satuan."<br>Harga:".number_format($da->harga);
       $pegawai = $da->nama_users."<br>Department:".$da->department."<br>Company:".$da->code_company;
        $hasil[] = array(
        $tgl,
        $brng,
        $da->jumlah,
        $status[$da->status],    
        $da->note,
        $da->users_penerima,    
        $name_users,
        $btn_received.$btn_reject    
      );
         
    }
    
    $return['hasil'] = $hasil;
//    $this->debug($return, true);
    print json_encode($return);
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
      AND B.id_hr_master_organisasi IS NOT NULL
      WHERE A.id_hr_company = '{$pos}'
      ");
//      print $this->db->last_query();
//      die;
      $html = "";
      $aa[0] ="- Pilih -";
    
       foreach ($users_1 as $key => $value) {
          if($value->id_hr_master_organisasi){
              $aa[$value->id_hr_master_organisasi] = $value->title;
          }
        
       }
//       print_r($aa); die;
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
            ."url: '".site_url("mrp/mrp-ajax-stock/ajax-company-department/0")."',"
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
          . "<label>Divisi</label>";
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
               ."url: '".site_url("mrp/mrp-ajax-stock/ajax-company-divisi/0")."',"
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
          . "<label>Department</label>";
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
            ."url: '".site_url("mrp/mrp-ajax-stock/ajax-company-section/0")."',"
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
  
  function get_pegawai(){
    
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
      . " WHERE C.title IS NOT NULL AND A.id_users > 1 AND A.status=1 AND (LOWER(A.name) LIKE '%{$q}%' OR LOWER(C.title) LIKE '%{$q}%' OR LOWER(A.email) LIKE '%{$q}%')"
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
  
  
  
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */