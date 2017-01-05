<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mrp_ajax_report extends MX_Controller {
  function __construct() {
    $this->menu = $this->cek();
  }
  
 function get_rekap_data($start = 0,$qty = 0,$jumlah = 0){
//      $id_mrp_supplier = 1;
//       $url = base_url()."themes/".DEFAULTTHEMES."/img/ajax-loader.gif";
//      $where = "WHERE A.status >= 3 AND A.id_mrp_po = '$id_mrp_po'  ";
       
    $hr_pegawai = $this->global_models->get("hr_master_organisasi", array("parent" => "{$this->session->userdata('report_dept_search_id_hr_master')}"));
    $no = 0;
    $aa = $this->session->userdata('report_dept_search_id_hr_master');
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
    $where = "WHERE 1=1";
    if($this->session->userdata('report_dept_search_id_company')){
        $where .= " AND B.id_hr_master_organisasi IN ($aa) AND B.id_hr_company ='{$this->session->userdata('report_dept_search_id_company')}' ";  
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
    }else{
       
    }
    
    $data = $this->global_models->get_query("SELECT A.title,A.level,A.id_hr_master_organisasi,B.id_hr_master_organisasi,SUM(jumlah) AS qty,SUM(jumlah* harga) AS price"
        . " FROM hr_master_organisasi AS A"
        . " LEFT JOIN mrp_report AS B ON A.id_hr_master_organisasi = B.id_hr_master_organisasi"
        . " LEFT JOIN mrp_inventory_spesifik AS C ON B.id_mrp_inventory_spesifik = C.id_mrp_inventory_spesifik"
        . " LEFT JOIN mrp_inventory_umum AS D ON C.id_mrp_inventory_umum = D.id_mrp_inventory_umum"    
        . " {$where}{$type}"
//        . " WHERE A.id_hr_master_organisasi IS NOT NULL"
        . " GROUP BY A.id_hr_master_organisasi"
        . " ORDER BY A.level ASC,A.title ASC"
//        . " LEFT JOIN mrp_satuan AS E ON A.id_mrp_satuan = E.id_mrp_satuan"
//        . " LEFT JOIN mrp_task_orders_request_asset AS F ON A.id_mrp_po = F.id_mrp_po"
//        . " {$where}"
//        . " GROUP BY A.id_mrp_inventory_spesifik"
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
//       if($da->title_spesifik){
//           $title_spesifik = " ".$da->title_spesifik."<br>";
//       }else{
//           $title_spesifik = "";
//       }
//       
//       if($da->brand){
//           $brn = "<br>Brand: ".$da->brand;
//       }else{
//           $brn = "";
//       }
//       $brng = $da->title.$title_spesifik.$brn;
        $angka = ($angka+1);
        $lvl = array(1 => "Direktorat",2 => "Department",3 => "Section",4 => "Divisi");
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
        
        $dt_qt = $this->global_models->get_query("SELECT jumlah AS qty"
        . " ,MONTH(B.tanggal) AS bulan"
        . " FROM mrp_report AS B"
        . " WHERE B.id_mrp_inventory_spesifik ='{$da->id_mrp_inventory_spesifik}' AND {$where}"
         );
//        print "<pre>";
//        print_r($dt_qt);
//        print "</pre>";
//        print $acs = $this->db->last_query();
//        die;
        
        foreach($dt_qt as $cek) {
            $ccss[$cek->bulan] = $cek->qty;
        }
        
        $jml_qty += $da->qty;
        $jml_hrg += ($da->harga * $da->qty);
        $ttl_qty = array();
        $ttl_hrg = array();
        for ($a = $this->session->userdata("report_dept_search_start_month"); $a <= $this->session->userdata("report_dept_search_end_month"); $a++) {
        
//            foreach ($dt_qt as $cek) {
                $cc[$a] = 0;
                if($cek->bulan == $a){
                $ttl_qty[$a] += $da->qty;
                $ttl_hrg[$a] += ($da->harga * $da->qty);
                $t_harga[$a] = number_format($ttl_hrg[$a]);
                }
            
        } 
//        print_r($ccss);
//        print_r($cc);
        $kcst = (array_replace($cc,$ccss));
        
        $angka = ($angka+1);
        $lvl = array(1 => "Direktorat",2 => "Department",3 => "Section",4 => "Divisi");
        $hasil2 = array(
        $angka,    
        $da->umum." ".$da->spesifik.$type2.$brand.$stn.$hrg
//        explode(",",$cc[$key]),
      );
        $aks =array($ttl_qty,number_format($ttl_hrg));
//        $ss = array_splice($hasil2, 0,2); 
//        $ss1 = array_splice($hasil2, 2);
        $hasil[] = array_merge($hasil2,$kcst,$ttl_qty,$t_harga);
    }
//    print "<pre>";
//    print_r($hasil);
//    print "</pre>";
//    die;
    
    $return['dtqty'] = $total_qty + $jml_qty;
    $return['dtjumlah'] = $total_harga + $jml_hrg;
    $return['hasil'] = $hasil;
//    $this->debug($return, true);
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