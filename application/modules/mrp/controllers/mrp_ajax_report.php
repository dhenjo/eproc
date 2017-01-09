<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mrp_ajax_report extends MX_Controller {
  function __construct() {
    $this->menu = $this->cek();
  }
  
    function get_supplier(){
       
       $data = $this->global_models->get_query("SELECT B.id_mrp_supplier"
        . " FROM mrp_task_orders_request_asset AS A"
        . " LEFT JOIN mrp_supplier_inventory AS B ON A.id_mrp_inventory_spesifik = B.id_mrp_inventory_spesifik"
        . " WHERE B.status =1"
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
  
  function get_no_po(){
       
       
    if (empty($_GET['term'])) exit ;
    $q = strtolower($_GET["term"]);
    if (get_magic_quotes_gpc()) $q = stripslashes($q);
    $items = $this->global_models->get_query("
      SELECT *
      FROM mrp_po
      WHERE 
      LOWER(no_po) LIKE '%{$q}%' AND status IN (6,7)
      LIMIT 0,10
      ");
    if(count($items) > 0){
      foreach($items as $tms){
        $result[] = array(
            "id"    => $tms->id_mrp_po,
            "label" => $tms->no_po,
            "value" => $tms->no_po,
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
  
  function get_rekap_data($start = 0,$qty = 0,$jumlah = 0){
//      $id_mrp_supplier = 1;
//       $url = base_url()."themes/".DEFAULTTHEMES."/img/ajax-loader.gif";
//      $where = "WHERE A.status >= 3 AND A.id_mrp_po = '$id_mrp_po'  ";
    $hr_pegawai = $this->global_models->get("hr_master_organisasi", array("parent" => "{$this->session->userdata('report_dept_search_id_hr_master')}"));

    $no = 0;
    $aa = $this->session->userdata('report_dept_search_id_hr_master');
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
//    A.id_hr_master_organisasi IN ($aa)
    $where = "WHERE 1=1 AND B.status =1";
    
    if($aa){
       $hr_master_organisasi = "AND B.id_hr_master_organisasi IN ($aa)"; 
    }else{
        $hr_master_organisasi = "";
    }
    
    if($this->session->userdata('report_dept_search_id_company')){
        $where .= " $hr_master_organisasi AND B.id_hr_company ='{$this->session->userdata('report_dept_search_id_company')}' ";  
    }
    
    if($this->session->userdata('report_dept_search_year') AND $this->session->userdata('report_dept_search_start_month')){
        $where .= " AND tanggal >= '{$this->session->userdata('report_dept_search_year')}-{$this->session->userdata('report_dept_search_start_month')}-1 + INTERVAL 1 MONTH'";
    }
    
    if($this->session->userdata('report_dept_search_year') AND $this->session->userdata('report_dept_search_end_month')){
        $where .= " AND tanggal <= '{$this->session->userdata('report_dept_search_year')}-{$this->session->userdata('report_dept_search_end_month')}-31'";
    }
    
    
//    if($this->session->userdata("id") == 1){
//           $id_users = 9;
//    }
//       
//       $hr_pegawai = $this->global_models->get("hr_pegawai", array("id_users" => $id_users));
    $type = "";
    if($this->session->userdata("report_dept_search_type")){
         $type = " AND D.id_mrp_type_inventory ='{$this->session->userdata("report_dept_search_type")}'";
    }
    
    $data = $this->global_models->get_query("SELECT A.title,A.level,A.id_hr_master_organisasi,B.id_hr_master_organisasi,SUM(jumlah) AS qty,SUM(jumlah* harga) AS price"
        . " FROM hr_master_organisasi AS A"
        . " LEFT JOIN mrp_report AS B ON A.id_hr_master_organisasi = B.id_hr_master_organisasi"
        . " LEFT JOIN mrp_inventory_spesifik AS C ON B.id_mrp_inventory_spesifik = C.id_mrp_inventory_spesifik"
        . " LEFT JOIN mrp_inventory_umum AS D ON C.id_mrp_inventory_umum = D.id_mrp_inventory_umum"    
        . " {$where}{$type}"
        . " GROUP BY A.id_hr_master_organisasi"
        . " ORDER BY A.level ASC,A.title ASC"
        . " LIMIT {$start}, 10");
//        print $acs = $this->db->last_query();
//        die;
    if(count($data) > 0){
      $return['status'] = 2;
      $angka = $start;
      $return['start']  = $start + 10;
    }
    else{
      $return['status'] = 1;
    }

//   $angka = 0;
   
    foreach ($data AS $da){
        $qty = $qty + $da->qty;
        $jumlah = $jumlah + $da->price;

        $angka = ($angka+1);
        $lvl = array(1 => "Direktorat",2 => "Divisi",3 => "Department",4 => "Section");
        $hasil[] = array(
        $angka,   
        $da->title,            
        $lvl[$da->level],
        $da->qty,    
        number_format($da->price),
        "<div class='btn-group'>"
        . "<a href='".site_url("mrp/mrp-report/detail-rekap-data/{$da->id_hr_master_organisasi}")."' type='button' class='btn btn-info btn-flat' title='Detail Rekap' style='width: 40px'><i class='fa fa-th-list'></i></a>"
//        . "<a href='".site_url("mrp/po/")."' type='button' class='btn btn-info btn-flat' title='Purchase Order' style='width: 40px'><i class='fa fa-exchange'></i></a>"
      . "</div>"   
      );
    }
    
    $return['dtqty'] = $qty;
    $return['dtjumlah'] = $jumlah;
    $return['hasil'] = $hasil;
//    $this->debug($return, true);
    print json_encode($return);
    die;
  }
  
 function get_rekap_bulanan($start = 0,$jumlah = 0){

    $where = "1=1 ";
    
    if($this->session->userdata("report_bulanan_search_id_company")){
         $where .= " AND B.id_hr_company ='{$this->session->userdata("report_bulanan_search_id_company")}'";
     }
     
     if($this->session->userdata('report_bulanan_search_year') AND $this->session->userdata('report_bulanan_search_start_month')){
        $where .= " AND D.tanggal_diterima >= '{$this->session->userdata('report_bulanan_search_year')}-{$this->session->userdata('report_bulanan_search_start_month')}-1 + INTERVAL 1 MONTH'";
    }
    
    if($this->session->userdata('report_bulanan_search_year') AND $this->session->userdata('report_bulanan_search_end_month')){
        $where .= " AND D.tanggal_diterima <= '{$this->session->userdata('report_bulanan_search_year')}-{$this->session->userdata('report_bulanan_search_end_month')}-31'";
    }
    
//    $type = "";
    if($this->session->userdata("report_bulanan_search_id_supplier")){
        $supplier = " AND D.id_mrp_supplier ='{$this->session->userdata("report_bulanan_search_id_supplier")}'";
    }
    $id_mrp_po = "";
    if($this->session->userdata("report_bulanan_search_id_mrp_po")){
        $id_mrp_po = " AND J.id_mrp_po ='{$this->session->userdata("report_bulanan_search_id_mrp_po")}'";
    }
    
    $data = $this->global_models->get_query("SELECT B.id_mrp_inventory_spesifik,F.name,E.title,A.title,A.level,A.id_hr_master_organisasi,B.harga,B.id_hr_master_organisasi,SUM(B.jumlah) AS qty,SUM(B.jumlah* B.harga) AS price"
        . " ,MONTH(B.tanggal) AS bulan"
        . " FROM hr_master_organisasi AS A"
        . " LEFT JOIN mrp_report AS B ON A.id_hr_master_organisasi = B.id_hr_master_organisasi"
        . " LEFT JOIN mrp_stock_out AS C ON B.id_mrp_stock_out = C.id_mrp_stock_out"
        . " LEFT JOIN mrp_stock_in AS D ON C.id_mrp_stock_in = D.id_mrp_stock_in"
        . " LEFT JOIN mrp_inventory_spesifik AS E ON B.id_mrp_inventory_spesifik = E.id_mrp_inventory_spesifik"
        . " LEFT JOIN mrp_inventory_umum AS F ON E.id_mrp_inventory_umum = F.id_mrp_inventory_umum"
        . " LEFT JOIN mrp_receiving_goods_detail AS G ON D.id_mrp_receiving_goods_detail = G.id_mrp_receiving_goods_detail"
        . " LEFT JOIN mrp_receiving_goods AS H ON G.id_mrp_receiving_goods = H.id_mrp_receiving_goods"
        . " LEFT JOIN mrp_receiving_goods_po AS I ON H.id_mrp_receiving_goods_po = I.id_mrp_receiving_goods_po"
        . " LEFT JOIN mrp_po AS J ON I.id_mrp_po = J.id_mrp_po"
        . " WHERE B.status < 15 AND {$where} $supplier $id_mrp_po"
        . " GROUP BY A.id_hr_master_organisasi"
//        . " GROUP BY A.id_hr_master_organisasi,YEAR(B.tanggal), MONTH(B.tanggal)"
//        . " ORDER BY concat(D.name,C.title,B.harga) ASC"
//        . " LEFT JOIN mrp_satuan AS E ON A.id_mrp_satuan = E.id_mrp_satuan"
//        . " LEFT JOIN mrp_task_orders_request_asset AS F ON A.id_mrp_po = F.id_mrp_po"
//        . " {$where}"
//        . " GROUP BY A.id_mrp_inventory_spesifik"
        . " LIMIT {$start}, 10");
//        print "<pre>";
////        print_r($data);
//        print $acs = $this->db->last_query();
//        print "</pre>";
//        die;
        
    if(count($data) > 0){
      $return['status'] = 2;
      $angka = $start;
      $return['start']  = $start + 10;
    }
    else{
      $return['status'] = 1;
    }
    
//   $angka = 0;
    foreach ($data AS $key => $da){
        $qty = $qty + $da->qty;
        
        $dt_qt[$key] = $this->global_models->get_query("SELECT SUM(B.jumlah* B.harga) AS price"
        . " ,MONTH(B.tanggal) AS bulan"
        . " FROM mrp_report AS B"
        . " LEFT JOIN mrp_stock_out AS C ON B.id_mrp_stock_out = C.id_mrp_stock_out"
        . " LEFT JOIN mrp_stock_in AS D ON C.id_mrp_stock_in = D.id_mrp_stock_in"
        . " LEFT JOIN mrp_receiving_goods_detail AS G ON D.id_mrp_receiving_goods_detail = G.id_mrp_receiving_goods_detail"
        . " LEFT JOIN mrp_receiving_goods AS H ON G.id_mrp_receiving_goods = H.id_mrp_receiving_goods"
        . " LEFT JOIN mrp_receiving_goods_po AS I ON H.id_mrp_receiving_goods_po = I.id_mrp_receiving_goods_po"
        . " LEFT JOIN mrp_po AS J ON I.id_mrp_po = J.id_mrp_po"        
        . " WHERE B.status < 15 AND B.id_hr_master_organisasi = {$da->id_hr_master_organisasi} AND {$where} $supplier $id_mrp_po"
        . " GROUP BY B.id_hr_master_organisasi,YEAR(B.tanggal), MONTH(B.tanggal)"
         );

        foreach($dt_qt[$key] as $ky => $cek) {
            $ccss[$key][$cek->bulan] = number_format($cek->price);
            $jml_hrg[$key] += ($cek->price);
            $jumlah = $jumlah + $cek->price;
        }
        
        for ($a = $this->session->userdata("report_bulanan_search_start_month"); $a <= $this->session->userdata("report_bulanan_search_end_month"); $a++) {
        

                $cc[$key][$a] = 0;

        }

        $kcst = (array_replace($cc[$key],$ccss[$key]));
        
        $angka = ($angka+1);
        $lvl = array(1 => "Direktorat",2 => "Divisi",3 => "Department",4 => "Section");
        $hasil2 = array(
        $angka,    
        $da->title." [".$lvl[$da->level]."]",

      );


       $apaaja =  number_format($jml_hrg[$key]);
        $aks = array($apaaja);


        $hasil[] = array_merge($hasil2,$kcst,$aks);

    }

    $return['dtjumlah'] = $jumlah;
    $return['hasil'] = $hasil;
//    $this->debug($return, true);
    print json_encode($return);
    die;
  }
  
 function get_report_po($start = 0){
//      $id_mrp_supplier = 1;
//       $url = base_url()."themes/".DEFAULTTHEMES."/img/ajax-loader.gif";
//      $where = "WHERE A.status >= 3 AND A.id_mrp_po = '$id_mrp_po'  ";
       
   
    $where = "WHERE 1=1 AND A.status =7";
    
//    if($aa){
//       $hr_master_organisasi = "AND B.id_hr_master_organisasi IN ($aa)"; 
//    }else{
//        $hr_master_organisasi = "";
//    }
//    

    if($this->session->userdata('report_po_search_type')){
        $where .= " AND F.id_mrp_type_inventory ='{$this->session->userdata('report_po_search_type')}' ";  
    }

    if($this->session->userdata('report_po_search_id_company')){
        $where .= " $hr_master_organisasi AND A.id_hr_company ='{$this->session->userdata('report_po_search_id_company')}' ";  
    }
	
	if($this->session->userdata('report_po_search_id_supplier')){
        $where .= " AND A.id_mrp_supplier ='{$this->session->userdata('report_po_search_id_supplier')}' ";  
    }
    
    if($this->session->userdata('report_po_search_year') AND $this->session->userdata('report_po_search_start_month')){
        $where .= " AND (A.tanggal_po >= '{$this->session->userdata('report_po_search_year')}-{$this->session->userdata('report_po_search_start_month')}-1 + INTERVAL 1 MONTH' OR "
        . " A.tanggal_po >= '{$this->session->userdata('report_po_search_year')}-{$this->session->userdata('report_po_search_start_month')}-1 + INTERVAL 1 MONTH') ";
    }
    
    if($this->session->userdata('report_po_search_year') AND $this->session->userdata('report_po_search_end_month')){
        $where .= " AND (A.tanggal_po <= '{$this->session->userdata('report_po_search_year')}-{$this->session->userdata('report_po_search_end_month')}-31' OR "
        . " A.tanggal_po <= '{$this->session->userdata('report_po_search_year')}-{$this->session->userdata('report_po_search_end_month')}-31') ";
    }
    
    
//    if($this->session->userdata("id") == 1){
//           $id_users = 9;
//    }
//       
//       $hr_pegawai = $this->global_models->get("hr_pegawai", array("id_users" => $id_users));
//    $type = "";
//    if($this->session->userdata("report_dept_search_type")){
//         $type = " AND D.id_mrp_type_inventory ='{$this->session->userdata("report_dept_search_type")}'";
//    }
    
//    $data = $this->global_models->get_query("SELECT A.title,A.level,A.id_hr_master_organisasi,B.id_hr_master_organisasi,SUM(jumlah) AS qty,SUM(jumlah* harga) AS price"
//        . " FROM hr_master_organisasi AS A"
//        . " LEFT JOIN mrp_report AS B ON A.id_hr_master_organisasi = B.id_hr_master_organisasi"
//        . " LEFT JOIN mrp_inventory_spesifik AS C ON B.id_mrp_inventory_spesifik = C.id_mrp_inventory_spesifik"
//        . " LEFT JOIN mrp_inventory_umum AS D ON C.id_mrp_inventory_umum = D.id_mrp_inventory_umum"    
//        . " {$where}{$type}"
//        . " GROUP BY A.id_hr_master_organisasi"
//        . " ORDER BY A.level ASC,A.title ASC"
//        . " LIMIT {$start}, 10");
//        $data = $this->global_models->get_query("SELECT A.id_mrp_po,A.id_mrp_supplier,A.tanggal_po,no_po,SUM(B.jumlah) AS qty,SUM(B.jumlah* B.harga) AS price"
//                . " ,B.id_mrp_task_orders,D.tanggal_diterima"
//        . " FROM mrp_po AS A"
//        . " LEFT JOIN mrp_po_asset AS B ON A.id_mrp_po = B.id_mrp_po"
//        . " LEFT JOIN mrp_receiving_goods_po AS C ON A.id_mrp_po = C.id_mrp_po"
//        . " LEFT JOIN mrp_receiving_goods_department AS D ON C.id_mrp_receiving_goods_po = D.id_mrp_receiving_goods_po"
//        . " {$where}"
//        . " GROUP BY A.id_mrp_po,C.id_mrp_receiving_goods_po"
//        . " ORDER BY D.tanggal_diterima ASC"
//        . " LIMIT {$start}, 10");

    $data = $this->global_models->get_query("SELECT A.id_mrp_po,A.id_mrp_supplier,A.tanggal_po,no_po,A.ppn,A.discount,"
            . "(SELECT SUM(L.jumlah) FROM mrp_po AS K"
            . " LEFT JOIN mrp_po_asset AS L ON K.id_mrp_po = L.id_mrp_po "
            . " WHERE K.id_mrp_po=A.id_mrp_po "
            . " GROUP BY K.id_mrp_po) AS qty "
            . ",(SELECT SUM(N.harga * N.jumlah) FROM mrp_po AS M "
            . " LEFT JOIN mrp_po_asset AS N ON M.id_mrp_po = N.id_mrp_po "
            . " WHERE M.id_mrp_po=A.id_mrp_po "
            . " GROUP BY M.id_mrp_po) AS price"
                . " ,B.id_mrp_task_orders,MIN(D.tanggal_diterima) AS tanggal_diterima"
        . " FROM mrp_po AS A"
        . " LEFT JOIN mrp_po_asset AS B ON A.id_mrp_po = B.id_mrp_po"
        . " LEFT JOIN mrp_inventory_spesifik AS E ON B.id_mrp_inventory_spesifik = E.id_mrp_inventory_spesifik"
        . " LEFT JOIN mrp_inventory_umum AS F ON E.id_mrp_inventory_umum = F.id_mrp_inventory_umum"
        . " LEFT JOIN mrp_receiving_goods_po AS C ON A.id_mrp_po = C.id_mrp_po"
        . " LEFT JOIN mrp_receiving_goods_department AS D ON C.id_mrp_receiving_goods_po = D.id_mrp_receiving_goods_po"
        . " {$where} AND D.status=1"
        . " GROUP BY A.id_mrp_po"
        . " ORDER BY D.tanggal_diterima ASC"
        . " LIMIT {$start}, 7");
        
//        print $acs = $this->db->last_query();
//        die;
    if(count($data) > 0){
      $return['status'] = 2;
      $angka = $start;
      $return['start']  = $start + 7;
    }
    else{
      $return['status'] = 1;
    }

//        $angka = 0;
   
    foreach ($data AS $da){
       $dt_beban =  $this->global_models->get_query(" SELECT G.title AS master_organisasi "
                . " FROM mrp_po_asset AS A"
                . " LEFT JOIN mrp_task_orders_request_asset AS B ON A.id_mrp_task_orders_request_asset = B.id_mrp_task_orders_request_asset"
                . " LEFT JOIN mrp_task_orders_request AS C ON B.id_mrp_task_orders = C.id_mrp_task_orders"
                . " LEFT JOIN mrp_request AS D ON C.id_mrp_request = D.id_mrp_request"
                . " LEFT JOIN mrp_request_asset AS E ON (C.id_mrp_request = E.id_mrp_request AND A.id_mrp_inventory_spesifik = E.id_mrp_inventory_spesifik) "
                . " LEFT JOIN hr_pegawai AS F ON D.id_hr_pegawai = F.id_hr_pegawai"
                . " LEFT JOIN hr_master_organisasi AS G ON F.id_hr_master_organisasi = G.id_hr_master_organisasi"
                . " WHERE A.status < 8 AND A.id_mrp_po ='{$da->id_mrp_po}' AND E.id_mrp_request IS NOT NULL AND A.id_mrp_task_orders='{$da->id_mrp_task_orders}'"
                . " GROUP BY F.id_hr_master_organisasi");//    print $this->db->last_query();
//    die;            
        $z = 0;
        $beban = "";
        foreach ($dt_beban as $v) {
            if($z > 0){
                $beban .= ",".$v->master_organisasi;
            }else{
                $beban .= $v->master_organisasi;
            }
            
            $z++;
        }
        
//        $surat_jln = $this->global_models->get_query("SELECT A.id_mrp_po,B.tanggal_diterima"
//        . " FROM mrp_receiving_goods_po AS A"
//        . " LEFT JOIN mrp_receiving_goods_department AS B ON A.id_mrp_receiving_goods_po = B.id_mrp_receiving_goods_po"
//        . " WHERE A.id_mrp_po ='{$da->id_mrp_po}'"
//        . " ORDER BY B.tanggal_diterima ASC");
//        print $this->db->last_query();       
//        die;
        $qty    = $qty + $da->qty;
        $jumlah = $jumlah + $da->price;
//       $lama = ((abs(strtotime ($da->tanggal_po) - strtotime ($surat_jln[0]->tanggal_diterima)))/(60*60*24));
        $lama = ((strtotime ($da->tanggal_diterima) - strtotime ($da->tanggal_po))/(60*60*24));
        $angka = ($angka+1);
        $tgl_po = "";
        if($da->tanggal_po != "0000-00-00" AND $da->tanggal_po != ""){
        $tgl_po = date("d/m/y", strtotime($da->tanggal_po));

        }
        
            $tgl_surat_jln = "";
            if($da->tanggal_diterima != "0000-00-00" AND $da->tanggal_diterima != ""){
            $tgl_surat_jln = date("d/m/y", strtotime($da->tanggal_diterima));
          
            }
            
            if($da->discount){
            $discount = $da->discount;
        }else{
            $discount = 0;
        }    
        
        if($da->ppn == 1){
            $ppn = ((10/100)* ($da->price - $discount));
        }else{
            $ppn = 0;
        }
        
        $all_total = $da->price - ($discount + $ppn);
        
        $hasil[] = array(
        $angka,
//            $da->tanggal_po,
        $tgl_po,
//        date_format($da->tanggal_po,"d/m/y"),            
        $da->no_po,
        $da->qty,
        number_format($all_total),
        $this->global_models->get_field("mrp_supplier","name",array("id_mrp_supplier" => "{$da->id_mrp_supplier}")),    
        $beban,
        $tgl_surat_jln,
            $lama,
        "<div class='btn-group'>"
        . "<a href='".site_url("mrp/mrp-report/detail-report-po/{$da->id_mrp_task_orders}/{$da->id_mrp_po}")."' type='button' class='btn btn-info btn-flat' title='Detail Rekap' style='width: 40px'><i class='fa fa-th-list'></i></a>"
//        . "<a href='".site_url("mrp/po/")."' type='button' class='btn btn-info btn-flat' title='Purchase Order' style='width: 40px'><i class='fa fa-exchange'></i></a>"
      . "</div>"   
      );
    }
    
    $return['dtqty'] = $qty;
    $return['dtjumlah'] = $jumlah;
    $return['hasil'] = $hasil;
//    $this->debug($return, true);
    print json_encode($return);
    die;
  }
  
   function get_detail_rekap_data($id_hr_master_organisasi = 0,$start = 0,$total_qty = 0, $total_harga = 0){
//      $id_mrp_supplier = 1;
//       $url = base_url()."themes/".DEFAULTTHEMES."/img/ajax-loader.gif";
//      $where = "WHERE A.status >= 3 AND A.id_mrp_po = '$id_mrp_po'  ";
       
    $hr_pegawai = $this->global_models->get("hr_master_organisasi", array("parent" => "{$id_hr_master_organisasi}"));
    $no = 0;
    $aa = $id_hr_master_organisasi;
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
//    if($this->session->userdata('asset_dept_search_id_company')){
//        $where = "WHERE B.jenis = 2 AND A.id_hr_master_organisasi IN ($aa) AND A.id_hr_company ='{$this->session->userdata('asset_dept_search_id_company')}' ";  
//    }
    
//    if($this->session->userdata("id") == 1){
//           $id_users = 9;
//    }
//       
//       $hr_pegawai = $this->global_models->get("hr_pegawai", array("id_users" => $id_users));

   
    
    $where = " B.id_hr_company ='{$this->session->userdata("report_dept_search_id_company")}' AND B.id_hr_master_organisasi ='{$id_hr_master_organisasi}'";
    
     if($this->session->userdata('report_dept_search_year') AND $this->session->userdata('report_dept_search_start_month')){
        $where .= " AND tanggal >= '{$this->session->userdata('report_dept_search_year')}-{$this->session->userdata('report_dept_search_start_month')}-1 + INTERVAL 1 MONTH'";
    }
    
    if($this->session->userdata('report_dept_search_year') AND $this->session->userdata('report_dept_search_end_month')){
        $where .= " AND tanggal <= '{$this->session->userdata('report_dept_search_year')}-{$this->session->userdata('report_dept_search_end_month')}-31'";
    }
    
    $type = "";
    if($this->session->userdata("report_dept_search_type")){
        $type = " AND D.id_mrp_type_inventory ='{$this->session->userdata("report_dept_search_type")}'";
    }
    
//    $dropdown_type1 = array (0 => "-All-");
    $dropdown_type2 = $this->global_models->get_dropdown("mrp_type_inventory", "id_mrp_type_inventory", "code", FALSE);
//    $dropdown_type = array_merge($dropdown_type1, $dropdown_type2);  
      
    
    $data = $this->global_models->get_query("SELECT B.id_mrp_inventory_spesifik,A.title,A.level,A.id_hr_master_organisasi,B.harga,B.id_hr_master_organisasi,SUM(jumlah) AS qty,SUM(jumlah* harga) AS price"
        . " ,D.id_mrp_type_inventory,D.name AS umum,C.title AS spesifik,F.title AS satuan,E.title AS brand,MONTH(B.tanggal) AS bulan"
        . " FROM hr_master_organisasi AS A"
        . " LEFT JOIN mrp_report AS B ON A.id_hr_master_organisasi = B.id_hr_master_organisasi"
        . " LEFT JOIN mrp_inventory_spesifik AS C ON B.id_mrp_inventory_spesifik = C.id_mrp_inventory_spesifik"
        . " LEFT JOIN mrp_inventory_umum AS D ON C.id_mrp_inventory_umum = D.id_mrp_inventory_umum"
        . " LEFT JOIN mrp_brand AS E ON C.id_mrp_brand = E.id_mrp_brand"
        . " LEFT JOIN mrp_satuan AS F ON C.id_mrp_satuan = F.id_mrp_satuan"    
        . " WHERE {$where}{$type}"
        . " GROUP BY B.id_mrp_inventory_spesifik,B.harga"
        . " ORDER BY concat(D.name,C.title,B.harga) ASC"
//        . " LEFT JOIN mrp_satuan AS E ON A.id_mrp_satuan = E.id_mrp_satuan"
//        . " LEFT JOIN mrp_task_orders_request_asset AS F ON A.id_mrp_po = F.id_mrp_po"
//        . " {$where}"
//        . " GROUP BY A.id_mrp_inventory_spesifik"
        . " LIMIT {$start}, 10");
//        print "<pre>";
//        print_r($data);
//        print "</pre>";
//        print $acs = $this->db->last_query();
//        die;
    if(count($data) > 0){
      $return['status'] = 2;
      $angka = $start;
      $return['start']  = $start + 10;
    }
    else{
      $return['status'] = 1;
    }
    $jumlah = 0;
//   $angka = 0;
    foreach ($data AS $key => $da){
        $qty = $qty + $da->qty;
        $jumlah = $jumlah + $da->price;
        if($da->brand){
            $brand = "<br>Jenis/Merk: ".$da->brand;
        }
        
        if($da->satuan){
            $stn = "<br>Satuan: ".$da->satuan;
        }
        if($da->harga){
            $hrg = "<br>Harga: ".number_format($da->harga);
        }
        
        if($da->id_mrp_type_inventory){
           $type2 = "<br>Type:".$dropdown_type2[$da->id_mrp_type_inventory]; 
        }
//        $cc = 0;
        
        $dt_qt[$key] = $this->global_models->get_query("SELECT SUM(jumlah) AS qty"
        . " ,MONTH(B.tanggal) AS bulan"
        . " FROM mrp_report AS B"
        . " WHERE B.id_mrp_inventory_spesifik ='{$da->id_mrp_inventory_spesifik}' AND {$where}"
        . "GROUP BY B.id_hr_master_organisasi,YEAR(B.tanggal), MONTH(B.tanggal)" 
        );
//        print "<pre>";
//        print_r($dt_qt);
//        print "</pre>";
//        print $acs = $this->db->last_query();
//        die;
        $price2[$key] = 0;
        foreach($dt_qt[$key] as $ky => $cek) {
            $ccss[$key][$cek->bulan] = $cek->qty;
            $ttl_qty[$key] += ($cek->qty);
            $ttl_hrg[$key] += ($da->harga * $cek->qty);
            $price2[$key] = $price2[$key] + ($da->harga * $cek->qty);
        }
//        foreach($dt_qt[$key] as $cek) {
//            $ccss[$key][$cek->bulan] = $cek->qty;
//        }
        
//        $jml_qty += $da->qty;
//        $jml_hrg += ($da->harga * $da->qty);
//        $ttl_qty = array();
//        $ttl_hrg = array();
        for ($a = $this->session->userdata("report_dept_search_start_month"); $a <= $this->session->userdata("report_dept_search_end_month"); $a++) {
        
//            foreach ($dt_qt as $cek) {
                $cc[$key][$a] = 0;
//                 foreach($dt_qt as $cek) {
//                if($cek->bulan == $a){
//                $ttl_qty[$a] = $ttl_qty[$a] + $da->qty;
//                $ttl_hrg[$a] += ($da->harga * $da->qty);
//                
//                }
//                 }
                
            
        }
        
         $kcst = (array_replace($cc[$key],$ccss[$key]));
//        $kcst = (array_replace($cc[$key],$ccss[$key]));
        
        $angka = ($angka+1);
        $lvl = array(1 => "Direktorat",2 => "Department",3 => "Section",4 => "Divisi");
        $hasil2 = array(
        $angka,    
        $da->umum." ".$da->spesifik.$type2.$brand.$stn.$hrg
//        explode(",",$cc[$key]),
      );
        $ll = implode(';', $ttl_qty);

       $apaaja =  number_format($ttl_hrg[$key]);
        $aks = array($ttl_qty[$key]);
        $ttl_hrg = array($apaaja);

//        $ss = array_splice($hasil2, 0,2); 
//        $ss1 = array_splice($hasil2, 2);
        $hasil[] = array_merge($hasil2,$kcst,$aks,$ttl_hrg);
       $total_qty = $total_qty + $ttl_qty[$key];
       $total_harga = $total_harga + $price2[$key];
    }
//    print "<pre>";
//    print_r($hasil);
//    print "</pre>";
//    die;
    
    $return['dtqty'] = $total_qty;
    $return['dtjumlah'] = $total_harga;
    $return['hasil'] = $hasil;
//    $this->debug($return, true);
    print json_encode($return);
    die;
  }
  
    function get_detail_report_po($id_mrp_po = 0,$start = 0, $dt_total = 0){
//      $id_mrp_supplier = 1;
      $url = base_url()."themes/".DEFAULTTHEMES."/img/ajax-loader.gif";
      $where = "WHERE H.status = 7 AND A.id_mrp_po = '$id_mrp_po'  ";
	  $po = $this->global_models->get("mrp_po",array("id_mrp_po" => "{$id_mrp_po}"));
    $data = $this->global_models->get_query("SELECT A.id_mrp_task_orders_request_asset,A.jumlah,A.catatan,A.note,C.name AS nama_barang,E.title AS satuan"
        . ",B.title AS title_spesifik,F.harga,A.id_mrp_task_orders_request_asset,E.group_satuan,A.harga AS harga_task_order_request"
        . ",E.nilai,D.title AS brand"
        . " FROM mrp_po_asset AS A"
        . " LEFT JOIN mrp_inventory_spesifik AS B ON A.id_mrp_inventory_spesifik = B.id_mrp_inventory_spesifik"
        . " LEFT JOIN mrp_inventory_umum AS C ON B.id_mrp_inventory_umum = C.id_mrp_inventory_umum"
        . " LEFT JOIN mrp_brand AS D ON B.id_mrp_brand = D.id_mrp_brand"
        . " LEFT JOIN mrp_satuan AS E ON A.id_mrp_satuan = E.id_mrp_satuan"
        . " LEFT JOIN mrp_supplier_inventory AS F ON B.id_mrp_inventory_spesifik = F.id_mrp_inventory_spesifik"
        . " LEFT JOIN mrp_supplier AS G ON F.id_mrp_supplier = G.id_mrp_supplier "
        . " LEFT JOIN mrp_po AS H ON A.id_mrp_po = H.id_mrp_po"
        . " {$where}"
        . " GROUP BY A.id_mrp_inventory_spesifik"
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
        $da->catatan,    
        $da->note,    
      );
           $dt_total = $dt_total + $total;
         
    }
    
     if($po[0]->discount){
        $discount = $po[0]->discount;
    }else{
        $discount = 0;
    }
    
    if($po[0]->ppn == 1){
        $ppn = ((10/100)* ($dt_total - $discount));
    }else{
        $ppn = 0;
    }
    
    $dt_all_total = $dt_total - ($discount + $ppn);
    $return['hasil'] = $hasil;
    $return['dt_discount'] = $discount;
    $return['dt_ppn'] = $ppn;
    $return['dt_total'] = $dt_total;
    $return['dt_all_total'] = $dt_all_total;//    $this->debug($return, true);
    print json_encode($return);
    die;
  }
  
  function get_asset_department_detail($type = 0,$id_mrp_inventory_spesifik = 0,$start = 0){
//      $id_mrp_supplier = 1;
//       $url = base_url()."themes/".DEFAULTTHEMES."/img/ajax-loader.gif";
//      $where = "WHERE A.status >= 3 AND A.id_mrp_po = '$id_mrp_po'  ";
         
    $hr_pegawai = $this->global_models->get("hr_master_organisasi", array("parent" => "{$this->session->userdata('asset_dept_search_id_hr_master')}"));
    $no = 0;
    $aa = $this->session->userdata('asset_dept_search_id_hr_master');
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
    
    if($aa){
        $aa = $aa;
    }else{
        $aa = 0;
    }
//    A.id_hr_master_organisasi IN ($aa)
    if($this->session->userdata('asset_dept_search_id_company')){
        $where = "WHERE B.jenis = 2 AND A.id_hr_master_organisasi IN ($aa) AND A.id_hr_company ='{$this->session->userdata('asset_dept_search_id_company')}' ";  
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

            $data = $this->global_models->get_query("SELECT A.id_mrp_stock_out,A.no_asset,A.code,C.name AS nama_barang,E.title AS satuan,"
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
    
    $btn_update2 = "<button type='button' class='btn btn-info tour-edit'  data-toggle='modal' data-target='#compose-modal{$da->id_mrp_stock_out}'>Update</button>";
    $btn_mutasi = " <button type='button' class='btn btn-warning tour-edit'  data-toggle='modal' data-target='#compose-modal-mutasi{$da->id_mrp_stock_out}'>Mutasi</button>";
    if($da->jumlah > $da->mutasi){
        $btn_update = $btn_update2;
        if($da->status < 6){
            $btn_update .= $btn_mutasi;
        }
    }else{
        $btn_update = "";
    }
    $dw = array("1" => "Dipakai",
                "6" => "Hilang",
                "7" => "Rusak");
    
    $dropdown2 = $this->form_eksternal->form_dropdown('status', $dw, 
              array($da->status), 'id="id_direktorat" class=" form-control dropdown2 input-sm"');
    $ads = $this->form_eksternal->form_input("no_asset", $da->no_asset, 'id="no_asset"  class="form-control input-sm" placeholder="No asset"');
    $dt_type = $this->form_eksternal->form_input("type", $type, 'id="no_asset" style="display:none"  class="form-control input-sm" placeholder="type" ');
    $dt_id_mrp_stock_out = $this->form_eksternal->form_input("id_mrp_stock_out", $da->id_mrp_stock_out, 'id="id_mrp_stock_out" style="display:none"  class="form-control input-sm" ');
    $dtid_mrp_inventory_spesifik = $this->form_eksternal->form_input("id_mrp_inventory_spesifik", $id_mrp_inventory_spesifik, 'id="id_mrp_inventory_spesifik" style="display:none"  class="form-control input-sm" ');
    $dtdate = "id=date".$da->id_mrp_stock_out;
    $tgl_diserahkan = $this->form_eksternal->form_input("tanggal", "", " {$dtdate} class='form-control input-sm' placeholder='Tanggal Diserahkan'");
    $usr = "id=users".$da->id_mrp_stock_out;
    $idusr = "id=id_users".$da->id_mrp_stock_out;
    $dt_user = $this->form_eksternal->form_input("users", "", "{$usr} class='form-control input-sm' placeholder='Users' ");
    $dt_idusr = $this->form_eksternal->form_input("id_users", "", " {$idusr} style='display: none'");
    $dt_note    = $this->form_eksternal->form_textarea('note', "", 'class="form-control input-sm" id="note2"'); 
    $jml_mutasi = $this->form_eksternal->form_input('jumlah_mutasi', "", 'min="0" id="dt_jumlah2" class="form-control input-sm" placeholder="Jumlah"');
    $totl = $this->form_eksternal->form_input('total', $da->jumlah, 'min="0" id="total" class="form-control input-sm" style="display:none" ');
    
    $status = array( 1=> "<span class='label bg-green'>Dipakai</span>",
         2 => "<span class='label bg-orange'>Pending Mutasi</span>",
         3 => "<span class='label bg-red'>Mutasi</span>",
         4 => "<span class='label bg-navy'>Reject</span>",
         5 => "<span class='label bg-green'>Update</span>",
         6 => "<span class='label bg-red'>Hilang</span>",
         7 => "<span class='label bg-red'>Rusak</span>",
        );
       $url = site_url("mrp/mrp-asset/asset_department_detail/{$type}/{$id_mrp_inventory_spesifik}");
       $brng = $da->nama_barang.$title_spesifik.$brn."<br>Satuan:".$da->satuan."<br>Harga Satuan:".number_format($da->harga).$dtnote."<br>Asset:".$status[$da->status];
       $pegawai = $da->nama_users."<br>Department:".$da->department."<br>Company:".$da->code_company;
        $hasil[] = array(
        $da->no_asset,    
        $tgl."<br>Code:".$da->code,
        $brng,
        $da->jumlah,
        $da->mutasi,    
        $pegawai,
        $tgl_terima,
        $btn_update."<div class='modal fade' id='compose-modal{$da->id_mrp_stock_out}' tabindex='-1' role='dialog' aria-hidden='true'>
    <div class='modal-dialog'>
        <div class='modal-content'>
            <div class='modal-header'>
                <button type='button' class='close' data-dismiss='modal' aria-hidden='true'></button>
                <h4 class='modal-title'>Update No. Asset</h4>
            </div>
            <form action='{$url}' method='post'>
                $dt_type $dtid_mrp_inventory_spesifik $dt_id_mrp_stock_out
                <div class='modal-body'>
                    <div class='col-md-12'>
                      <div class='control-group'>
                      <label>No Asset</label>
                      {$ads}
                      </div>
                      </div>
                      <div class='col-md-12'>
                      <div class='control-group'>
                      <label>Status</label><br>
                      {$dropdown2}
                      </div>
                      </div>
                      <br><br><br><br><br><br>
                   
                </div>
                <div class='modal-footer clearfix'>

                    <button type='button' class='btn btn-danger' data-dismiss='modal'><i class='fa fa-times'></i> Cancel</button>

                    <button type='submit' class='btn btn-primary pull-left'> Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>".
    "<div class='modal fade' id='compose-modal-mutasi{$da->id_mrp_stock_out}' tabindex='-1' role='dialog' aria-hidden='true'>
    <div class='modal-dialog'>
        <div class='modal-content'>
            <div class='modal-header'>
                <button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
                <h4 class='modal-title'>Mutasi Asset</h4>
            </div>
            <form class='eventInsForm{$da->id_mrp_stock_out}' action='{$url}' method='post'>
                $dt_type$dtid_mrp_inventory_spesifik$dt_id_mrp_stock_out$dt_idusr$totl
                <div class='modal-body'>
                <table class='table table-striped'>
                    <tr>
                        <td style='width: 25%'>Asset Available</td>
                        <td>{$da->jumlah}</td>
                    </tr>
                   <tr>
                        <td style='width: 25%'>Tanggal Diserahkan</td>
                        <td>{$tgl_diserahkan}</td>
                    </tr>
                    <tr>
                        <td style='width: 25%'>Users</td>
                        <td>$dt_user</td>
                    </tr>  
                    <tr>
                        <td style='width: 25%'>Jumlah</td>
                        <td>{$jml_mutasi}</td>
                    </tr>
                     
                    <tr>
                        <td style='width: 25%'>Note</td>
                        <td>{$dt_note}</td>
                    </tr> 
                      
                  </table>
                </div>
                <div class='modal-footer clearfix'>

                    <button type='button' class='btn btn-danger' data-dismiss='modal'><i class='fa fa-times'></i> Cancel</button>

                    <button type='submit' class='btn btn-primary pull-left'> Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>".
    "<script>"
       . "$( '#date{$da->id_mrp_stock_out}' ).datepicker({"
              . "showOtherMonths: true,"
              . "dateFormat: 'yy-mm-dd',"  
              . "selectOtherMonths: true,"  
              . "selectOtherYears: true,"
              . "});"                          
      . "$( '#users{$da->id_mrp_stock_out}' ).autocomplete({"
            . "source: '".site_url("mrp/mrp-ajax-asset/get-pegawai/")."',"
            . "minLength: 1,"
            . "search  : function(){ $(this).addClass('working');},"
            . "open    : function(){ $(this).removeClass('working');},"
            . "select: function( event, ui ) {"
            . "$('#id_users{$da->id_mrp_stock_out}').val(ui.item.id);"
            . "}"
        . "});"
      ."$( '#users{$da->id_mrp_stock_out}' ).autocomplete( 'option', 'appendTo', '.eventInsForm{$da->id_mrp_stock_out}' )"    
      . "</script>"                            
      );
         
    }
    
    $return['hasil'] = $hasil;
//    $this->debug($return, true);
    print json_encode($return);
    die;
  }
  
}