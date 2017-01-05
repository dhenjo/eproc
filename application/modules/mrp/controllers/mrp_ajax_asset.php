<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mrp_ajax_asset extends MX_Controller {
  function __construct() {
    $this->menu = $this->cek();
  }
  
     function get_asset_department($start = 0){
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
//    A.id_hr_master_organisasi IN ($aa)
    $where = "WHERE A.status < 12 AND B.jenis = 2";
    if($this->session->userdata('asset_dept_search_id_company')){
        $where .= " AND A.id_hr_company ='{$this->session->userdata('asset_dept_search_id_company')}' ";  
    }
    
    if($aa){
        $where .= " AND A.id_hr_master_organisasi IN ('{$aa}')";
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
 . "<a href='".site_url("mrp/mrp-asset/asset-department-detail/2/{$da->id_mrp_inventory_spesifik}")."' type='button' class='btn btn-info btn-flat' title='List Asset' style='width: 40px'><i class='fa fa-th-list'></i></a>"
//        . "<a href='".site_url("mrp/po/")."' type='button' class='btn btn-info btn-flat' title='Purchase Order' style='width: 40px'><i class='fa fa-exchange'></i></a>"
      . "</div>"   
      );
         
    }
    
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
    $where = " WHERE A.status < 12 AND B.jenis = 2 ";
    if($this->session->userdata('asset_dept_search_id_company')){
        $where .= " AND A.id_hr_company ='{$this->session->userdata('asset_dept_search_id_company')}' ";  
    }
    
    if($aa){
        $where .= " AND A.id_hr_master_organisasi IN ($aa)";
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
  
    function get_pending_mutasi($start = 0){
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
//    A.id_hr_master_organisasi IN ($aa)
    if($this->session->userdata('asset_dept_search_id_company')){
        $where = "WHERE B.jenis=2 AND A.status = 2 AND  (A.id_hr_master_organisasi IN ($aa) OR J.id_hr_master_organisasi IN ($aa)) AND A.id_hr_company ='{$this->session->userdata('asset_dept_search_id_company')}' ";  
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
    . "<a href='".site_url("mrp/mrp-asset/proses-pending-mutasi/1/{$da->id_mrp_stock_out_department}")."' type='button' class='btn btn-info btn-flat' title='Approved' style='width: 40px'><i class='fa fa-check'></i></a>"
    . "</div>";
    $btn_reject = "<a href='".site_url("mrp/mrp-asset/proses-pending-mutasi/2/{$da->id_mrp_stock_out_department}")."' type='button' class='btn btn-danger btn-flat' title='Reject' style='width: 40px'><i class='fa fa-times'></i></a>";        
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
  
 function get_pegawai(){
    
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
  
    function get_detail_pemakaian($type = 0,$id_mrp_inventory_spesifik = 0,$start = 0){
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
//    A.id_hr_master_organisasi IN ($aa)
    if($this->session->userdata('asset_dept_search_id_company')){
        $where = "WHERE  (A.id_hr_master_organisasi IN ($aa) OR J.id_hr_master_organisasi IN ($aa)) AND A.id_hr_company ='{$this->session->userdata('asset_dept_search_id_company')}' ";  
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
             . " {$where} AND I.status < 12"
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
    
    if($da->user_penerima AND $da->status == 2){
        $received ="<br>Receiver:".$da->received_users;
    }
    
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
         4 => "<span class='label bg-navy'>Reject</span>",
         5 => "<span class='label bg-green'>Update</span>"
         );
    $name_users = $this->global_models->get_field("m_users","name", array("id_users" => $da->id_users));
  
    if($da->code){
        $code_sot = "<br>Code: ".$da->code;
    }else{
        $code_sot = "";
    }
    
    if($da->jumlah){
        $jml = $da->jumlah;
    }else{
        $jml = 0;
    }
    
    $cd = date("d M Y H:i:s", strtotime($da->create_date))."<br>";
       $brng = $da->nama_barang.$title_spesifik.$brn."<br>Satuan:".$da->satuan.$received;
       $pegawai = $da->nama_users."<br>Department:".$da->department."<br>Company:".$da->code_company;
        $hasil[] = array(
        $tgl,
        $brng,
        $jml,
        $status[$da->status],    
        $da->note,
        $cd."Created By:".$name_users.$code_sot
      );
         
    }
    
    $return['hasil'] = $hasil;
//    $this->debug($return, true);
    print json_encode($return);
    die;
  }
  
}