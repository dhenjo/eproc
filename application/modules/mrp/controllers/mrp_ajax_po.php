<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mrp_ajax_po extends MX_Controller {
  function __construct() {
    $this->menu = $this->cek();
  }
  
  function insert_mrp_po($id_mrp_po = 0,$id_mrp_task_orders = 0){
  $pst = $_POST;
  $id_mrp_task_orders_request_asset = $pst['id_mrp_task_orders_request_asset'];
  $status       = $pst['status'];
  $id_supplier  = $pst['id_supplier'];
  $id_company   = $pst['id_company'];
  $jumlah_po    = $pst['jumlah_po'];
  $harga_po     = $pst['harga_po'];
  $note_po      = $pst['note_po'];
  $id_satuan_po = $pst['id_satuan_po'];
  $id_mrp_inventory_spesifik = $pst['id_mrp_inventory_spesifik'];
  
  if($id_mrp_task_orders_request_asset){
    $arr_id = explode(",",$id_mrp_task_orders_request_asset);
    
    $arr_jumlah_po  = explode(",",$jumlah_po);
    $arr_harga_po   = explode(",",$harga_po);
    $arr_note_po    = explode(",",$note_po);
    $arr_id_satuan_po    = explode(",",$id_satuan_po);
    $arr_id_mrp_inventory_spesifik    = explode(",",$id_mrp_inventory_spesifik);
    
//    $dt_status = array("4" => "Pengajuan PO", "3" => "Draft");
    
    $kirim2 = array(
        "id_mrp_supplier"               => $id_supplier,
        "id_hr_company"                 => $id_company,
        "status"                        => $status,
        "update_by_users"               => $this->session->userdata("id"),
        "update_date"                   => date("Y-m-d H:i:s")
    );
    $this->global_models->update("mrp_po", array("id_mrp_po" => $id_mrp_po),$kirim2);
       
            
    foreach ($arr_id as $ky => $val) {
//    $kirim = array(
//        "status"                        => $status,
//        "id_mrp_po"                     => $id_mrp_po,
//        "update_by_users"               => $this->session->userdata("id"),
//        "update_date"                   => date("Y-m-d H:i:s")
//    );
//    
//    $this->global_models->update("mrp_po_asset", array("id_mrp_task_orders_request_asset" => $val),$kirim);
    
    $kirim = array(
        "id_mrp_task_orders"                => $id_mrp_task_orders,
        "id_mrp_inventory_spesifik"         => $arr_id_mrp_inventory_spesifik[$ky],
        "id_mrp_task_orders_request_asset"  => $val,
        "id_mrp_supplier"                   => $id_supplier,
        "id_mrp_po"                         => $id_mrp_po, 
        "jumlah"                            => $arr_jumlah_po[$ky],
        "id_mrp_satuan"                     => $arr_id_satuan_po[$ky],
        "note"                              => $arr_note_po[$ky],
        "harga"                             => $arr_harga_po[$ky],
        "status"                            => $status,
        "create_by_users"                   => $this->session->userdata("id"),
        "create_date"                       => date("Y-m-d H:i:s")
        );
    $this->global_models->insert("mrp_po_asset", $kirim);
        
    }
                
    $this->session->set_flashdata('success', 'Data Berhasi di Proses');
  }else{
       $this->session->set_flashdata('notice', 'Data tidak tersimpan');
  }
 
 die;
}

  function insert_po(){
  $pst = $_POST;
  $id_mrp_task_orders_request_asset = $pst['id_mrp_task_orders_request_asset'];
  $status       = $pst['status'];
  $id_supplier  = $pst['id_supplier'];
  $id_company   = $pst['id_company'];
  $jumlah_po    = $pst['jumlah_po'];
  $harga_po     = $pst['harga_po'];
  $note_po      = $pst['note_po'];
  $catatan      = $pst['catatan'];
  $id_satuan_po = $pst['id_satuan_po'];
  $ppn          = $pst['ppn'];
  $desimal      = $pst['desimal'];
  $disc         = $pst['discount'];
  $id_mrp_inventory_spesifik = $pst['id_mrp_inventory_spesifik'];
  
  if($id_mrp_task_orders_request_asset){
    $arr_id = explode(",",$id_mrp_task_orders_request_asset);
    
    $arr_jumlah_po       = explode(",",$jumlah_po);
    $arr_harga_po        = explode(",",$harga_po);
    $arr_note_po         = explode(",",$note_po);
    $arr_catatan         = explode(",", $catatan);
    $arr_id_satuan_po    = explode(",",$id_satuan_po);
    $arr_id_mrp_inventory_spesifik    = explode(",",$id_mrp_inventory_spesifik);
    
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
        "discount"                      => str_replace(",","",$disc),
        "flag_desimal"                  => $desimal,
        "ppn"                           => $ppn,
        "create_by_users"               => $this->session->userdata("id"),
        "create_date"                   => date("Y-m-d H:i:s")
    );
   $id_mrp_po = $this->global_models->insert("mrp_po", $kirim);
            
    foreach ($arr_id as $ky => $val) {
//    $kirim = array(
//        "status"                        => $status,
//        "id_mrp_po"                     => $id_mrp_po,
//        "update_by_users"               => $this->session->userdata("id"),
//        "update_date"                   => date("Y-m-d H:i:s")
//    );
//    
//    $this->global_models->update("mrp_po_asset", array("id_mrp_task_orders_request_asset" => $val),$kirim);
    
    $kirim = array(
        "id_mrp_task_orders"                => $id_mrp_task_orders,
        "id_mrp_inventory_spesifik"         => $arr_id_mrp_inventory_spesifik[$ky],
        "id_mrp_task_orders_request_asset"  => $val,
        "id_mrp_supplier"                   => $id_supplier,
        "id_mrp_po"                         => $id_mrp_po, 
        "jumlah"                            => $arr_jumlah_po[$ky],
        "id_mrp_satuan"                     => $arr_id_satuan_po[$ky],
        "note"                              => $arr_note_po[$ky],
        "catatan"                           => $arr_catatan[$ky],
        "harga"                             => $arr_harga_po[$ky],
        "status"                            => $status,
        "create_by_users"                   => $this->session->userdata("id"),
        "create_date"                       => date("Y-m-d H:i:s")
        );
    $this->global_models->insert("mrp_po_asset", $kirim);
        
    }
                
    $this->session->set_flashdata('success', 'Data Berhasi di Proses ke '.$dt_status[$status]);
  }else{
       $this->session->set_flashdata('notice', 'Data tidak tersimpan');
  }
 
 die;
}

   function get_po_request_asset($id_mrp_task_orders = 0,$start = 0,$id_mrp_supplier = 0,$harga_total2 = 0,$desimal = 0){
//      $id_mrp_supplier = 1;
       $url = base_url()."themes/".DEFAULTTHEMES."/img/ajax-loader.gif";
      $where = "WHERE  A.id_mrp_task_orders = '$id_mrp_task_orders' ";

    $data = $this->global_models->get_query("SELECT A.id_mrp_task_orders_request_asset,A.id_mrp_satuan,A.jumlah,A.note,A.id_mrp_task_orders_request_asset,A.harga AS harga_task_order_request,"
        . "B.title AS title_spesifik,C.name AS nama_barang,A.id_mrp_inventory_spesifik,"
        . "D.code AS brand,"
        . "E.title AS satuan,E.group_satuan,E.nilai,G.catatan,"
        . "F.harga,(Select SUM(H.jumlah) FROM mrp_po_asset AS H where H.id_mrp_inventory_spesifik  = A.id_mrp_inventory_spesifik AND H.status !=12 AND H.id_mrp_task_orders ='{$id_mrp_task_orders}' GROUP BY H.id_mrp_inventory_spesifik) AS jumlah_po"
        . " FROM mrp_task_orders_request_asset AS A"
        . " LEFT JOIN mrp_inventory_spesifik AS B ON A.id_mrp_inventory_spesifik = B.id_mrp_inventory_spesifik"
        . " LEFT JOIN mrp_inventory_umum AS C ON B.id_mrp_inventory_umum = C.id_mrp_inventory_umum"
        . " LEFT JOIN mrp_brand AS D ON B.id_mrp_brand = D.id_mrp_brand"
        . " LEFT JOIN mrp_satuan AS E ON A.id_mrp_satuan = E.id_mrp_satuan"
        . " LEFT JOIN mrp_supplier_inventory AS F ON (B.id_mrp_inventory_spesifik = F.id_mrp_inventory_spesifik) AND F.id_mrp_supplier = '{$id_mrp_supplier}'"
        . " LEFT JOIN mrp_po_asset AS G ON A.id_mrp_task_orders = G.id_mrp_task_orders "
        . " {$where}"
        . " GROUP BY A.id_mrp_task_orders,A.id_mrp_inventory_spesifik"
        . " ORDER BY A.id_mrp_task_orders ASC"
        . " LIMIT {$start}, 1");
//       print $this->db->last_query();
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
   $ank = 0;
   
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
                if($da->harga){
                    $dta_hrg = $da->harga;
                }else{
                    $dta_hrg = $da->harga_task_order_request;
                }
                
            }else{
                $dta_hrg = $da->harga;
            }
            
            $kirim[$ky] = array(
            "harga"                     => $dta_hrg,
            "update_by_users"           => $this->session->userdata("id"),
            "update_date"               => date("Y-m-d H:i:s")
            );
//            $this->global_models->update("mrp_po_asset", array("id_mrp_task_orders_request_asset" => $da->id_mrp_task_orders_request_asset,"status" => "1"),$kirim[$ky]);
          
            $this->global_models->update("mrp_task_orders_request_asset", array("id_mrp_task_orders_request_asset" => $da->id_mrp_task_orders_request_asset,"status" => "1"),$kirim[$ky]);
        }
        
         $total_2 = (($da->jumlah * $da->nilai) * $dta_hrg);
         
        $id_satuan = "id='id_satuan_{$da->id_mrp_task_orders_request_asset}'";
        $id_mrp_inventory_spesifik = "id='id_mrp_inventory_spesifik_{$da->id_mrp_task_orders_request_asset}'";
        $satuan = "id='satuan_{$da->id_mrp_task_orders_request_asset}'";
        $note = "id='note_{$da->id_mrp_task_orders_request_asset}'";
        $catatan = "id='catatan_{$da->id_mrp_task_orders_request_asset}'";
        $dt_jumlah = "id='id_jumlah_{$da->id_mrp_task_orders_request_asset}'";
        $dt_harga = "id='id_harga_{$da->id_mrp_task_orders_request_asset}'";
        $dt_total = "id='id_total_{$da->id_mrp_task_orders_request_asset}'";
        $nilai = "id='nilai_{$da->id_mrp_task_orders_request_asset}'";
        $total = (($da->nilai *$da->jumlah) * $dta_hrg);
        
       $total_jml_po = $da->jumlah - $da->jumlah_po; 
       
        if($total_jml_po > 0){
            if($desimal == 1){
        $ank_jumlah = number_format($total_jml_po,2,".","");
//        $ank_harga = number_format($da->harga_task_order_request,2,".",",");
        }else{
        $ank_jumlah = number_format($total_jml_po,0,".","");
//        $ank_harga = number_format($da->harga_task_order_request,0,".",",");
        }
             $hasil[] = array(
           
        $da->nama_barang.$title_inventory.$brand2.$this->form_eksternal->form_input("id_mrp_task_orders_request_asset[]", $da->id_mrp_task_orders_request_asset, ' class="form-control mrp_task_orders_request_asset input-sm" style="display: none"'),
        $this->form_eksternal->form_input("satuan[]", $da->satuan, $satuan.$dt_mrp_supplier.' class="form-control  input-sm" placeholder="Satuan"').
        $this->form_eksternal->form_input("nilai[]", $da->nilai, $nilai.' class="form-control value_po input-sm" style="display: none"').
        $this->form_eksternal->form_input("id_satuan[]", $da->id_mrp_satuan, $id_satuan.' class="form-control id_satuan_po  input-sm" style="display: none"').
        $this->form_eksternal->form_input("id_mrp_inventory_spesifik[]", $da->id_mrp_inventory_spesifik, $id_mrp_inventory_spesifik.' class="form-control id_mrp_inventory_spesifik  input-sm" style="display: none"')         
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
                     . "source: '".site_url("mrp/mrp-ajax-po/get-satuan-po/{$da->group_satuan}")."',"
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
                            ."url : '".site_url("mrp/mrp-ajax-po/change-satuan/{$da->id_mrp_task_orders_request_asset}")."',"
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
//                    ."var dataString2 = 'jumlah='+ jml +'&harga='+ hrg;"
//                        ."$.ajax({"
//                        ."type : 'POST',"
//                        ."url : '".site_url("mrp/mrp-ajax-po/update-po-task-orders-request/{$da->id_mrp_task_orders_request_asset}")."',"
//                        ."data: dataString2,"
//                        ."dataType : 'html',"
//                        ."success: function(data) {"
////                                . "alert('aa');"
////                        . "$('#script-tambahan').html(data);"
//
//                        ."},"
//                     ."});"    
                   . "});"
                           
                   . "$('#id_harga_{$da->id_mrp_task_orders_request_asset}').keyup(function(){"
                   . "var jml = $('#id_jumlah_{$da->id_mrp_task_orders_request_asset}').val().toString().replace(/\$|\,/g,'') * 1;"
                   . "var hrg = $('#id_harga_{$da->id_mrp_task_orders_request_asset}').val().toString().replace(/\$|\,/g,'') * 1;"
                   . "var dt_nilai = $('#nilai_{$da->id_mrp_task_orders_request_asset}').val();"
                   . "hrg = hrg ? hrg : 0;"
                   . "jml = jml ? jml : 0;"
                   . "penjumlahan_{$da->id_mrp_task_orders_request_asset}(jml,hrg,dt_nilai);"
//                   ."var dataString2 = 'jumlah='+ jml +'&harga='+ hrg;"
//                        ."$.ajax({"
//                        ."type : 'POST',"
//                        ."url : '".site_url("mrp/mrp-ajax-po/update-po-task-orders-request/{$da->id_mrp_task_orders_request_asset}")."',"
//                        ."data: dataString2,"
//                        ."dataType : 'html',"
//                        ."success: function(data) {"
////                                . "alert('aa');"
////                        . "$('#script-tambahan').html(data);"
//                        ."},"
//                     ."});"    
                   . "});"
                   
//                   . "$('#note_{$da->id_mrp_task_orders_request_asset}').keyup(function(){"
//                   . "var dt_note = $('#note_{$da->id_mrp_task_orders_request_asset}').val();"
//                   . "var dataString2 = 'note='+ dt_note;"
//                   ."$.ajax({"
//                        ."type : 'POST',"
//                        ."url : '".site_url("mrp/mrp-ajax-po/update-keterangan-po-task-orders-request/{$da->id_mrp_task_orders_request_asset}")."',"
//                        ."data: dataString2,"
//                        ."dataType : 'html',"
//                        ."success: function(data) {"
//                        ."},"
//                     ."});"            
//                   . "});"
                       
                . "function penjumlahan_{$da->id_mrp_task_orders_request_asset}(jumlah,harga,nilai){"
                     . "var total = ((nilai*jumlah) * harga);"
                     . "$('#id_total_{$da->id_mrp_task_orders_request_asset}').val(parseFloat(total, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,').toString());"
                . "}"
            . "</script>"
            ,
        $this->form_eksternal->form_input("jumlah[]", $ank_jumlah, $dt_jumlah.$dt_mrp_supplier.' id="satuan" value_po class="form-control jumlah_po input-sm" placeholder="Satuan"'),
        $this->form_eksternal->form_input("harga[]", number_format($dta_hrg), $dt_harga.$dt_mrp_supplier.' onkeyup="FormatCurrency(this)"  style="width:120px" class="form-control harga_po input-sm" placeholder="Harga"'),
        $this->form_eksternal->form_input("total[]", number_format($total), $data_id.$dt_total.' disabled style="width:140px;" class="form-control id_spesifik input-sm" placeholder=""')
        ,$this->form_eksternal->form_textarea('catatan[]', $da->catatan, $catatan.' style="height: 50px;" class="form-control catatan_po input-sm"')
        ,$this->form_eksternal->form_textarea('note[]', $da->note, $note.' style="height: 50px;" class="form-control note_po input-sm"')
        ,"<div class='btn-group'>"
          . "<a href='javascript:void(0)' onclick='delete_po_task_order_request({$da->id_mrp_task_orders_request_asset})' id='del_{$da->id_mrp_task_orders_request_asset}' class='btn btn-danger btn-flat' style='width: 40px'>x</a>"
          . "<span style='display: none; margin-left: 10px;' id='img-page-{$da->id_mrp_task_orders_request_asset}'><img width='35px' src='{$url}' /></span>"
          . "</div>"
//          ."<script>"
//                  
//          . "</script>"
                  
      );
      $harga_total2 = $harga_total2 + $total_2;    
       $ank = $ank +1;   
    }else{
        $harga_total2 = $harga_total2;  
        $hasil[] = array("","","","","","","","");
    }
       
          if($id_mrp_supplier > 0){
            $kirim = array(
            "id_mrp_supplier"           => $id_mrp_supplier,
            "update_by_users"           => $this->session->userdata("id"),
            "update_date"               => date("Y-m-d H:i:s")
            );
//            $this->global_models->update("mrp_po_asset", array("id_mrp_task_orders_request_asset" => $da->id_mrp_task_orders_request_asset,"status" => "1"),$kirim);
           
            $this->global_models->update("mrp_task_orders_request_asset", array("id_mrp_task_orders_request_asset" => $da->id_mrp_task_orders_request_asset,"status" => "1"),$kirim);
          }
    }
   $return['angka'] = $ank;
    $return['hasil'] = $hasil;
    $return['dt_total'] = $harga_total2;
//    $this->debug($return, true);
    print json_encode($return);
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
  
 function change_satuan($id_mrp_po_asset = 0){
  
  $jumlah =  $_POST['jumlah'];
  $id_mrp_satuan =  $_POST['dsatuan'];

  $dt_sort_new = $this->global_models->get_field("mrp_satuan", "sort",array("id_mrp_satuan" => "{$id_mrp_satuan}"));
  $dt_nilai_new = $this->global_models->get_field("mrp_satuan", "nilai",array("id_mrp_satuan" => "{$id_mrp_satuan}"));
  $dta = $this->global_models->get("mrp_po_asset", array("id_mrp_task_orders_request_asset" => $id_mrp_po_asset));
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
    $this->global_models->update("mrp_po_asset", array("id_mrp_po_asset" => $id_mrp_po_asset),$kirim);
  }
    
//    print $this->db->last_query(); die;
    $data2= array("jumlah" => $v_jumlah,
                  "total"   => $total,
                  "nilai"   => $dt_nilai_new);
    
    print json_encode($data2);
    die;

}

function update_po_task_orders_request($id_mrp_po_asset = 0){
    $jumlah = $_POST['jumlah'];
    $harga = $_POST['harga'];

    $kirim3 = array(
        "jumlah"                        => $jumlah,
        "harga"                         => $harga,
        "update_by_users"               => $this->session->userdata("id"),
        "update_date"                   => date("Y-m-d H:i:s")
        );
    $this->global_models->update("mrp_po_asset", array("id_mrp_po_asset" => $id_mrp_po_asset),$kirim3);

//    $this->session->set_flashdata('success', 'Data Berhasil di Hapus');
    die;

}

function update_keterangan_po_task_orders_request($id_mrp_po_asset = 0){
    $note = $_POST['note'];
    $catatan = $_POST['catatan'];
    if($note){
        $kirim3 = array(
        "note"                          => $note,
        "update_by_users"               => $this->session->userdata("id"),
        "update_date"                   => date("Y-m-d H:i:s")
        );
    $this->global_models->update("mrp_po_asset", array("id_mrp_po_asset" => $id_mrp_po_asset),$kirim3);
    }
    
    if($catatan){
         $kirim3 = array(
        "catatan"                       => $catatan,
        "update_by_users"               => $this->session->userdata("id"),
        "update_date"                   => date("Y-m-d H:i:s")
        );
    $this->global_models->update("mrp_po_asset", array("id_mrp_po_asset" => $id_mrp_po_asset),$kirim3);
    }
    die;
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

    function get_mrp_list_po($start = 0,$id_users = 0){
      
//      if($this->session->userdata("id") == 1){
//          $where = "";
//      }else{
          $where = "WHERE A.status < 11";
//      }
    $data = $this->global_models->get_query("SELECT A.id_mrp_po,A.no_po,A.status,A.tanggal_dikirim,A.code,A.create_date"
        . ",B.id_mrp_task_orders,C.code AS kode_task,A.create_by_users"
        . ",D.name,A.tanggal_po,(SELECT concat(L.tanggal_diterima,',',K.id_mrp_receiving_goods_po) FROM mrp_receiving_goods_po AS K"
            . " LEFT JOIN mrp_receiving_goods AS L ON K.id_mrp_receiving_goods_po = L.id_mrp_receiving_goods_po"
            . " WHERE K.id_mrp_po = A.id_mrp_po "
            . " GROUP BY K.id_mrp_po"
            . " ORDER BY L.tanggal_diterima ASC LIMIT 0,1) AS date_receive"
        . " FROM mrp_po AS A"
        . " LEFT JOIN mrp_po_asset AS B ON A.id_mrp_po = B.id_mrp_po"
        . " LEFT JOIN mrp_task_orders AS C ON B.id_mrp_task_orders = C.id_mrp_task_orders"
        . " LEFT JOIN mrp_supplier AS D ON A.id_mrp_supplier = D.id_mrp_supplier"   
        . " {$where}"
        . " GROUP BY A.id_mrp_po "
        . " ORDER BY A.id_mrp_po DESC"
        . " LIMIT {$start}, 10");
        
//    $data_array = json_decode($data);
//    $this->debug($data, true);
  
//    $jenis = array(1 => "Habis Pakai", 2 => "Asset");
    $status = array( 3=> "<span class='label bg-orange'>Draft PO</span>", 4 => "<span class='label bg-maroon'>Proses Pengajuan PO</span>",
      5 => "<span class='label bg-green'>Approved PO</span>", 6 =>"<span class='label bg-blue'>Sent PO</span>"
        , 7 =>"<span class='label bg-green'>Closed PO</span>", 8 =>"<span class='label bg-red'>Revisi PO</span>"
        , 12 => "<span class='label bg-red'>CANCEL PO</span>");
    
//    $status = array(1 => "Create", 2 => "Approve");
    $hide = 0;
    foreach ($data AS $ky => $da){
     $cd_po = date("d M Y", strtotime($da->tanggal_po));
    if($da->status <= "3"){
        if($da->create_by_users == $id_users){
            if($da->status == 3 OR $da->status == 8){
        $btn_update ="<a href='".site_url("mrp/mrp-po/update-po/{$da->id_mrp_task_orders}/{$da->id_mrp_po}")."' type='button' class='btn btn-info btn-flat' title='Edit Purchase Order' style='width: 40px'><i class='fa fa-edit'></i></a>";
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
            $btn_del = "<a href='".site_url("mrp/mrp-po/delete-list-po/{$da->id_mrp_po}")."' type='button' class='btn btn-danger btn-flat' title='Delete Purchase Order' style='width: 40px'><i class='fa fa-trash-o'></i></a>";
        }
        
        $date_receive= explode(",",$da->date_receive);
         
        if($date_receive[0] != "0000-00-00" AND $date_receive[0] != ""){
            $tgl = date("d M Y", strtotime($date_receive[0]));
        }else{
            $tgl = "";
        }
        
            $hasil[] = array(
            $da->no_po,
            $cd_po."<br>".$da->code,    
            "<a href='".site_url("mrp/mrp-task-orders/add-task-orders/{$da->id_mrp_task_orders}")."'  title='Task Order' style='width: 40px'>{$da->kode_task}</a>",
            $da->name,
            "<a href='".site_url("mrp/rg/{$date_receive[1]}")."'  title='RG' style='width: 40px'>{$tgl}</a>",
            $status[$da->status], 
            "<div class='btn-group'>"
              . $btn_update
              . "<a href='".site_url("mrp/mrp-po/detail-po/{$da->id_mrp_task_orders}/{$da->id_mrp_po}")."' type='button' class='btn btn-info btn-flat' title='List Purchase Order' style='width: 40px'><i class='fa fa-list-alt'></i></a>"
              . $btn_del
              . $btn_status_payment      
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
//            $btn_status_payment = "<a href='".site_url("mrp/status-payment/{$da->id_mrp_po}")."' type='button' class='btn btn-warning btn-flat' title='Status Payment' style='width: 40px'><i class='fa fa-indent'></i></a>";
        }else{
//            $btn_status_payment = "";
            $btn_rg = "";
        }
        
       $date_receive= explode(",",$da->date_receive);
         
        if($date_receive[0] != "0000-00-00" AND $date_receive[0] != ""){
            $tgl = date("d M Y", strtotime($date_receive[0]));
        }else{
            $tgl = "";
        }
        
            $hasil[] = array(
            $da->no_po,
            $cd_po."<br>".$da->code,    
            "<a href='".site_url("mrp/mrp-task-orders/add-task-orders/{$da->id_mrp_task_orders}")."'  title='Task Orders' style='width: 40px'>{$da->kode_task}</a>",
            $da->name,
            "<a href='".site_url("mrp/rg/{$date_receive[1]}")."'  title='RG' style='width: 40px'>{$tgl}</a>",
            $status[$da->status], 
            "<div class='btn-group'>"
              . $btn_update
              . "<a href='".site_url("mrp/mrp-po/detail-po/{$da->id_mrp_task_orders}/{$da->id_mrp_po}")."' type='button' class='btn btn-info btn-flat' title='List Purchase Order' style='width: 40px'><i class='fa fa-list-alt'></i></a>"
    //          . $btn_rg
//              . $btn_status_payment        
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
  
  function get_mrp_list_po_umum($start = 0,$id_users = 0){
      
//      if($this->session->userdata("id") == 1){
//          $where = "";
//      }else{
          $where = "WHERE A.status < 11";
//      }
    $data = $this->global_models->get_query("SELECT A.id_mrp_po,A.no_po,A.status,A.tanggal_dikirim,A.code,A.create_date"
        . ",B.id_mrp_task_orders,C.code AS kode_task,A.create_by_users"
        . ",D.name,A.tanggal_po,(SELECT concat(L.tanggal_diterima,',',K.id_mrp_receiving_goods_po) FROM mrp_receiving_goods_po AS K"
            . " LEFT JOIN mrp_receiving_goods AS L ON K.id_mrp_receiving_goods_po = L.id_mrp_receiving_goods_po"
            . " WHERE K.id_mrp_po = A.id_mrp_po "
            . " GROUP BY K.id_mrp_po"
            . " ORDER BY L.tanggal_diterima ASC LIMIT 0,1) AS date_receive"
        . " FROM mrp_po AS A"
        . " LEFT JOIN mrp_po_asset AS B ON A.id_mrp_po = B.id_mrp_po"
        . " LEFT JOIN mrp_task_orders AS C ON B.id_mrp_task_orders = C.id_mrp_task_orders"
        . " LEFT JOIN mrp_supplier AS D ON A.id_mrp_supplier = D.id_mrp_supplier"   
        . " {$where}"
        . " GROUP BY A.id_mrp_po "
        . " ORDER BY A.id_mrp_po DESC"
        . " LIMIT {$start}, 10");
        
//    $data_array = json_decode($data);
//    $this->debug($data, true);
  
//    $jenis = array(1 => "Habis Pakai", 2 => "Asset");
    $status = array( 3=> "<span class='label bg-orange'>Draft PO</span>", 4 => "<span class='label bg-maroon'>Proses Pengajuan PO</span>",
      5 => "<span class='label bg-green'>Approved PO</span>", 6 =>"<span class='label bg-blue'>Sent PO</span>"
        , 7 =>"<span class='label bg-green'>Closed PO</span>", 8 =>"<span class='label bg-red'>Revisi PO</span>"
        , 12 => "<span class='label bg-red'>CANCEL PO</span>");
    
//    $status = array(1 => "Create", 2 => "Approve");
    $hide = 0;
    foreach ($data AS $ky => $da){
     $cd_po = date("d M Y", strtotime($da->tanggal_po));
    $date_receive= explode(",",$da->date_receive);
         
        if($date_receive[0] != "0000-00-00" AND $date_receive[0] != ""){
            $tgl = date("d M Y", strtotime($date_receive[0]));
        }else{
            $tgl = "";
        }
        
            $hasil[] = array(
            $da->no_po,
            $cd_po."<br>".$da->code,    
            $da->kode_task,
            $da->name,
            $tgl,
            $status[$da->status], 
            "<div class='btn-group'>"
              . "<a href='".site_url("mrp/mrp-po/detail-po-umum/{$da->id_mrp_task_orders}/{$da->id_mrp_po}")."' type='button' class='btn btn-info btn-flat' title='List Purchase Order' style='width: 40px'><i class='fa fa-list-alt'></i></a>"
            . "</div>"
          );
         
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
  
  function update_mrp_po($id_mrp_po = 0){
  
  $pst = $_POST;
  $id_mrp_po_asset = $pst['id_mrp_po_asset'];
  $status       = $pst['status'];
  $id_supplier  = $pst['id_supplier'];
  $id_company   = $pst['id_company'];
  $ppn          = $pst['ppn'];
  $disc         = $pst['discount'];
  $desimal      = $pst['desimal'];
  
//  $jumlah_po    = $pst['jumlah_po'];
//  $harga_po     = $pst['harga_po'];
//  $note_po      = $pst['note_po'];
//  $id_satuan_po = $pst['id_satuan_po'];
//  $id_mrp_inventory_spesifik = $pst['id_mrp_inventory_spesifik'];
//  
  if($id_company > 0){
      
    $arr_id = explode(",",$id_mrp_po_asset);
      
//    $arr_jumlah_po      = explode(",",$jumlah_po);
//    $arr_harga_po       = explode(",",$harga_po);
//    $arr_note_po        = explode(",",$note_po);
//    $arr_id_satuan_po   = explode(",",$id_satuan_po);
//    $arr_id_mrp_inventory_spesifik    = explode(",",$id_mrp_inventory_spesifik);
    
 $dt_status = array("5" => "Approve PO","4" => "Pengajuan PO", "3" => "Draft");

    $kirim2 = array(
        "id_mrp_supplier"               => $id_supplier,
        "id_hr_company"                 => $id_company,
        "status"                        => $status,
        "discount"                      => str_replace(",","",$disc),
        "ppn"                           => $ppn,
        "flag_desimal"                  => $desimal,
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
    
    $this->global_models->update("mrp_po_asset", array("id_mrp_po_asset" => $val),$kirim);
 }
 $this->session->set_flashdata('success', 'Data Berhasi di Proses ke '.$dt_status[$status]);
  }else{
  $this->session->set_flashdata('notice', 'Data tidak tersimpan');
  }
 
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
    $id_mrp_po_asset = $_POST['id_mrp_po_asset'];
    
       $this->global_models->delete("mrp_po_asset", array("id_mrp_po_asset" => $id_mrp_po_asset));
//            $kirim3 = array(
//                "status"                        => 1,
//                "update_by_users"               => $this->session->userdata("id"),
//                "update_date"                   => date("Y-m-d H:i:s")
//                );
//        $this->global_models->update("mrp_po_asset", array("id_mrp_task_orders_request_asset" => $id_mrp_task_orders_request_asset),$kirim3);
        die;
           
   }
   
function get_update_po_request_asset($id_mrp_task_orders = 0,$id_mrp_po = 0,$start = 0,$id_mrp_supplier = 0,$harga_total2 = 0,$desimal = 0){
//      $id_mrp_supplier = 1;
       $url = base_url()."themes/".DEFAULTTHEMES."/img/ajax-loader.gif";
      $where = "WHERE (A.status = 3 OR A.status = 8) AND A.id_mrp_task_orders = '$id_mrp_task_orders' AND A.id_mrp_po = '$id_mrp_po' ";

    $data = $this->global_models->get_query("SELECT A.id_mrp_po_asset,A.catatan,A.id_mrp_task_orders_request_asset,A.jumlah,A.note,A.id_mrp_task_orders_request_asset,A.harga AS harga_task_order_request,"
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
        . " GROUP BY A.id_mrp_inventory_spesifik"
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
       
        if($desimal == 1){
            $ank_jml[$ky] = number_format($da->jumlah,2, ".", ",");
        }else{
            $ank_jml[$ky] = number_format($da->jumlah,0, ".", ",");
        }
        $dt_id_satuan = "id_satuan_{$da->id_mrp_po_asset}";
        $dt_satuan = "satuan_{$da->id_mrp_po_asset}";
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
        
        $total_2 = (($da->jumlah * $da->nilai) * $dta_hrg);
        
        $id_satuan = "id='id_satuan_{$da->id_mrp_po_asset}'";
        $satuan = "id='satuan_{$da->id_mrp_po_asset}'";
        $note = "id='note_{$da->id_mrp_po_asset}'";
        $catatan = "id='catatan_{$da->id_mrp_po_asset}'";
        $dt_jumlah = "id='id_jumlah_{$da->id_mrp_po_asset}'";
        $dt_harga = "id='id_harga_{$da->id_mrp_po_asset}'";
        $dt_total = "id='id_total_{$da->id_mrp_po_asset}'";
        $nilai = "id='nilai_{$da->id_mrp_po_asset}'";
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
        $da->nama_barang.$title_spesifik.$brand.$this->form_eksternal->form_input("id_mrp_po_asset[]", $da->id_mrp_po_asset, ' class="form-control mrp_po_asset input-sm" style="display: none"'),
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
            
            . "$('#del2_{$da->id_mrp_po_asset}').click(function(){"
               
                . "var table = $('#tableboxy').DataTable();"
                . "$('#tableboxy tbody').on( 'click', 'tr', function () {"
                . "if ( $(this).hasClass('selected') ) {"
                    . "$(this).removeClass('selected');"
                . "}"
                . "else {"
                . "table.$('tr.selected').removeClass('selected');"
                    . "$(this).addClass('selected');"
                . "}"
                    . "$('#del2_{$da->id_mrp_po_asset}').click(function(){"
                        . "table.row('.selected').remove().draw( false ); "
//                            . " var tot_now =$('#dt-total').text();"
//                            . " var dt_t = $('#id_total_{$da->id_mrp_po_asset}').val();"
//                            . " var hsl = tot_now - dt_t;"
//                            . "$('#dt-total').text(hsl);"
                    . "});"
             . "});"
            . "});"
            
                . "$( '#{$dt_satuan}' ).autocomplete({"
                     . "source: '".site_url("mrp/mrp-ajax-po/get-satuan-po/{$da->group_satuan}")."',"
                     . "minLength: 1,"
                     . "search  : function(){ $(this).addClass('working');},"
                     . "open    : function(){ $(this).removeClass('working');},"
                     . "select: function( event, ui ) {"
                     . "$('#id_satuan_{$da->id_mrp_po_asset}').val(ui.item.id);"
                            . "var dt_satuan = $('#id_satuan_{$da->id_mrp_po_asset}').val();"
//                            . "alert(dt_satuan);"
                            . "var dt_jumlah = $('#id_jumlah_{$da->id_mrp_po_asset}').val();"
                            ."var dataString2 = 'dsatuan='+ dt_satuan + '&jumlah=' + dt_jumlah;"
                            ."$.ajax({"
                            ."type : 'POST',"
                            ."url : '".site_url("mrp/mrp-ajax-po/change-satuan/{$da->id_mrp_po_asset}")."',"
                            ."data: dataString2,"
                            ."dataType : 'html',"
                            ."success: function(data) {"
                                    . 'var hasil = $.parseJSON(data);'
                                    . "$('#id_jumlah_{$da->id_mrp_po_asset}').val(hasil.jumlah);"
                                    . "$('#id_total_{$da->id_mrp_po_asset}').val(parseFloat(hasil.total, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,').toString());"
                                    . "$('#nilai_{$da->id_mrp_po_asset}').val(hasil.nilai);" 
//                                     . "alert(hasil.total);"
//                                    . "$('#script-tambahan').html(data);"
                            ."},"
                         ."});"       
                     . "}"
                   . "});"
                  . '});'
                             
                   . "$('#id_jumlah_{$da->id_mrp_po_asset}').keyup(function(){"
                   . "var jml = $('#id_jumlah_{$da->id_mrp_po_asset}').val().toString().replace(/\$|\,/g,'') * 1;"
                   . "var hrg = $('#id_harga_{$da->id_mrp_po_asset}').val().toString().replace(/\$|\,/g,'') * 1;"
                   . "var dt_nilai = $('#nilai_{$da->id_mrp_po_asset}').val();" 
                   . "hrg = hrg ? hrg : 0;"
                   . "jml = jml ? jml : 0;"
                   . "penjumlahan_{$da->id_mrp_po_asset}(jml,hrg,dt_nilai);"
                    ."var dataString2 = 'jumlah='+ jml +'&harga='+ hrg;"
                        ."$.ajax({"
                        ."type : 'POST',"
                        ."url : '".site_url("mrp/mrp-ajax-po/update-po-task-orders-request/{$da->id_mrp_po_asset}")."',"
                        ."data: dataString2,"
                        ."dataType : 'html',"
                        ."success: function(data) {"
//                                . "alert('aa');"
//                        . "$('#script-tambahan').html(data);"

                        ."},"
                     ."});"    
                   . "});"
                           
                   . "$('#id_harga_{$da->id_mrp_po_asset}').keyup(function(){"
                   . "var jml = $('#id_jumlah_{$da->id_mrp_po_asset}').val().toString().replace(/\$|\,/g,'') * 1;"
                   . "var hrg = $('#id_harga_{$da->id_mrp_po_asset}').val().toString().replace(/\$|\,/g,'') * 1;"
                   . "var dt_nilai = $('#nilai_{$da->id_mrp_po_asset}').val();" 
                   . "hrg = hrg ? hrg : 0;"
                   . "jml = jml ? jml : 0;"
                   . "penjumlahan_{$da->id_mrp_po_asset}(jml,hrg,dt_nilai);"
                   ."var dataString2 = 'jumlah='+ jml +'&harga='+ hrg;"
                        ."$.ajax({"
                        ."type : 'POST',"
                        ."url : '".site_url("mrp/mrp-ajax-po/update-po-task-orders-request/{$da->id_mrp_po_asset}")."',"
                        ."data: dataString2,"
                        ."dataType : 'html',"
                        ."success: function(data) {"
//                                . "alert('aa');"
//                        . "$('#script-tambahan').html(data);"

                        ."},"
                     ."});"    
                   . "});"
                                
                   . "$('#note_{$da->id_mrp_po_asset}').keyup(function(){"
                   . "var dt_note = encodeURIComponent($('#note_{$da->id_mrp_po_asset}').val());"
                   . "var dataString2 = 'note='+ dt_note;"
                   ."$.ajax({"
                        ."type : 'POST',"
                        ."url : '".site_url("mrp/mrp-ajax-po/update-keterangan-po-task-orders-request/{$da->id_mrp_po_asset}")."',"
                        ."data: dataString2,"
                        ."dataType : 'html',"
                        ."success: function(data) {"
//                                . "alert('aa');"
                        ."},"
                     ."});"            
                   . "});"
                   
                   . "$('#catatan_{$da->id_mrp_po_asset}').keyup(function(){"
                   . "var dt_catatan = encodeURIComponent($('#catatan_{$da->id_mrp_po_asset}').val());"
                   . "var dataString2 = 'catatan='+ dt_catatan;"
                   ."$.ajax({"
                        ."type : 'POST',"
                        ."url : '".site_url("mrp/mrp-ajax-po/update-keterangan-po-task-orders-request/{$da->id_mrp_po_asset}")."',"
                        ."data: dataString2,"
                        ."dataType : 'html',"
                        ."success: function(data) {"
//                                . "alert('aa');"
                        ."},"
                     ."});"            
                   . "});"                  
                
//                . "$('#dt_total').text({$dt_total});"
                
                . "function penjumlahan_{$da->id_mrp_po_asset}(jumlah,harga,nilai){"
                     . "var total = ((nilai * jumlah) * harga);"
                     . "$('#id_total_{$da->id_mrp_po_asset}').val(parseFloat(total, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,').toString());"
                . "}"
            . "</script>"
            ,
        $this->form_eksternal->form_input("jumlah[]", $ank_jml[$ky], $dt_jumlah.$dt_mrp_supplier.' id="satuan" class="form-control input-sm" placeholder="Satuan"'),
        $this->form_eksternal->form_input("harga[]", number_format($dta_hrg), $dt_harga.$dt_mrp_supplier.' onkeyup="FormatCurrency(this)"  style="width:120px" class="form-control jumlah input-sm" placeholder="Harga"'),
        $this->form_eksternal->form_input("total[]", number_format($total), $data_id.$dt_total.' disabled style="width:140px;" class="form-control id_spesifik input-sm" placeholder=""')
        ,$this->form_eksternal->form_textarea('catatan[]', $da->catatan, $catatan.'style="height: 50px;" class="form-control input-sm"')
        ,$this->form_eksternal->form_textarea('note[]', $da->note, $note.'style="height: 50px;" class="form-control input-sm"')
        ,"<div class='btn-group'>"
          . "<a href='javascript:void(0)' onclick='delete_po_task_order_request2({$da->id_mrp_po_asset})' id='del2_{$da->id_mrp_po_asset}' class='btn btn-danger btn-flat' style='width: 40px'>x</a>"
          . "<span style='display: none; margin-left: 10px;' id='img-page2-{$da->id_mrp_po_asset}'><img width='35px' src='{$url}' /></span>"
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
        $harga_total2 = $harga_total2 + $total_2;      
    }
    
   
    $return['hasil'] = $hasil;
    $return['dt_total'] = $harga_total2;
//    $this->debug($return, true);
    print json_encode($return);
    die;
  }
  
  function excel_po($id_mrp_task_orders = 0,$id_mrp_po = 0){
      
     
      
  }
  
function update_detail_po($id_mrp_task_orders = 0,$id_mrp_po = 0){
  $pst = $_POST;
  $status =  $pst['status'];
  $tanggal =  $pst['tanggal'];
  $note =  $pst['note'];
  $tanggal_po = $pst['tanggal_po'];
  
  $frm = $pst['frm'];
  $no_po = "";
  if($frm){
       $kirim2 = array(
        "frm"                        => $frm,
        "update_by_users"            => $this->session->userdata("id"),
        "update_date"                => date("Y-m-d H:i:s")
    );
    
    $this->global_models->update("mrp_po", array("id_mrp_po" => $id_mrp_po),$kirim2);
  }
  
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
      
      $po = $this->global_models->get("mrp_po",array("id_mrp_po" =>"{$id_mrp_po}"));    
      $code = $this->global_models->get_field("hr_company","code",array("id_hr_company" =>"{$po[0]->id_hr_company}"));    
      if($po[0]->no_po){
          if($tgl_po){
            $arr_no_po = explode("/",$po[0]->no_po);
            $tahun =substr($tgl_po,0,4);
            $bulan =substr($tgl_po,5,-3);
            $no_po = $code."/PO/".$arr_no_po[2]."/".$bulan."/".$tahun;
            }else{
                $no_po = $po[0]->no_po;
            }
      
      }
      
      if($note == "undefined"){
          $nt_note = "";
      }else{
          $nt_note = $note;
      }
      
      $kirim2 = array(
        "tanggal_dikirim"               => $tanggal,
        "tanggal_po"                    => $tgl_po, 
        "note"                          => $nt_note,
        "no_po"                         => $no_po,
        "update_by_users"               => $this->session->userdata("id"),
        "update_date"                   => date("Y-m-d H:i:s")
    );
    
    $this->global_models->update("mrp_po", array("id_mrp_po" => $id_mrp_po),$kirim2);
  }
    
    $return['no_po'] = $no_po;
//    $this->debug($return, true);
    print json_encode($return);
 die;
}

function approve_detail_po($id_mrp_task_orders = 0,$id_mrp_po = 0){
//  $id_mrp_task_orders_request_asset = $_POST['id_mrp_task_orders_request_asset'];
  $pst = $_POST;
  $status =  $pst['status'];
//  $id_supplier =  $_POST['id_supplier'];
  $id_company =  $pst['id_company'];
  $tanggal = $pst["tanggal"];
  $frm =$pst['frm'];
 
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
       
       if($tanggal){
           $tahun =substr($tanggal,0,4);
           $bulan =substr($tanggal,-5,-3);
       }else{
           $tahun = date("Y");
           $bulan = date("m");
       }
       
       $no_po = $code."/PO/".$number."/".$bulan."/".$tahun;
   
    $kirim2 = array(
        "status"                        => $status,
        "no_po"                         => $no_po,
        "frm"                           => $frm,
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
    
        $array_id_mrp_request = "";
        $no_id = 0;
        foreach ($mrp_task_orders_request as $vl) {
            
            if($no_id >0){
                $array_id_mrp_request .= ",".$vl->id_mrp_request;
            }else{
                $array_id_mrp_request .= $vl->id_mrp_request;
            }
            
//             $kirim = array(
//                "status"                        => 6,
//                "status_blast"                  => 1,
//                "update_by_users"               => $this->session->userdata("id"),
//                "update_date"                   => date("Y-m-d H:i:s")
//            );
//    
//        $this->global_models->update("mrp_request", array("id_mrp_request" => $vl->id_mrp_request),$kirim);
    $no_id++;
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
            "array_id_mrp_request"      => $array_id_mrp_request,
            "status"                    => $status,
            "create_by_users"           => $this->session->userdata("id"),
            "create_date"               => date("Y-m-d H:i:s")
        );
        $id_rg_po = $this->global_models->insert("mrp_receiving_goods_po", $kirim);
        
      if($pst['blast_eml'] == 1){ 
        $kirim = array(
            "id_mrp_receiving_goods_po"         => $id_rg_po,
            "status"                            => 1,
            "reminder"                         => 1,
            "create_by_users"                   => $this->session->userdata("id"),
            "create_date"                       => date("Y-m-d H:i:s")
        );
       $this->global_models->insert("blast_email_po", $kirim);
     
       $id_mrp_po2 = $this->global_models->get_query("SELECT A.id_mrp_po FROM mrp_po AS A"
               . " LEFT JOIN setting_blast_email_rg AS B ON A.id_mrp_supplier = B.id_mrp_supplier"
               . " WHERE B.status=1 AND A.id_mrp_po='{$id_mrp_po}'");
               
       if($id_mrp_po2[0]->id_mrp_po > 0){
           $this->db->query("UPDATE mrp_request SET status_blast=1,status=6,update_by_users='{$this->session->userdata("id")}' WHERE id_mrp_request IN({$array_id_mrp_request}) ");
       }else{
           $this->db->query("UPDATE mrp_request SET status=6,update_by_users='{$this->session->userdata("id")}' WHERE id_mrp_request IN({$array_id_mrp_request}) ");
       }
       
       }else{
        $this->db->query("UPDATE mrp_request SET status=6,update_by_users='{$this->session->userdata("id")}' WHERE id_mrp_request IN({$array_id_mrp_request}) ");   
       }
       
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
        "frm"                          => $frm, 
        "user_checker"                  => "NULL",    
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
   
//    $kirim = array(
//        "status"                         => $status,
//        "update_by_users"               => $this->session->userdata("id"),
//        "update_date"                   => date("Y-m-d H:i:s")
//    );
//    
//    $this->global_models->update("mrp_task_orders_request_asset", array("id_mrp_task_orders" => $id_mrp_task_orders,"id_mrp_po" => $id_mrp_po),$kirim);

 $this->session->set_flashdata('success', 'Data Berhasi di Proses');
 die;

}

  function get_mrp_detail_po_list($id_mrp_task_orders = 0,$id_mrp_po = 0,$status = 0,$start = 0, $dt_total = 0){
//      $id_mrp_supplier = 1;
       $url = base_url()."themes/".DEFAULTTHEMES."/img/ajax-loader.gif";
      $where = "WHERE A.status >= 3 AND A.id_mrp_task_orders = '$id_mrp_task_orders' AND A.id_mrp_po = '$id_mrp_po'  ";

    $data = $this->global_models->get_query("SELECT A.id_mrp_po_asset,A.id_mrp_task_orders_request_asset,A.jumlah,A.catatan,A.note,C.name AS nama_barang,E.title AS satuan"
        . ",B.title AS title_spesifik,F.harga,E.group_satuan,A.harga AS harga_task_order_request,B.id_mrp_inventory_spesifik"
        . ",E.nilai,D.title AS brand,A.status,A.id_mrp_task_orders,A.id_mrp_po,A.status,H.flag_desimal"
        . " FROM mrp_po_asset AS A"
        . " LEFT JOIN mrp_inventory_spesifik AS B ON A.id_mrp_inventory_spesifik = B.id_mrp_inventory_spesifik"
        . " LEFT JOIN mrp_inventory_umum AS C ON B.id_mrp_inventory_umum = C.id_mrp_inventory_umum"
        . " LEFT JOIN mrp_brand AS D ON B.id_mrp_brand = D.id_mrp_brand"
        . " LEFT JOIN mrp_satuan AS E ON A.id_mrp_satuan = E.id_mrp_satuan"
        . " LEFT JOIN mrp_supplier_inventory AS F ON B.id_mrp_inventory_spesifik = (F.id_mrp_inventory_spesifik AND F.id_mrp_supplier = '{$id_mrp_supplier}')"
        . " LEFT JOIN mrp_supplier G ON F.id_mrp_supplier = G.id_mrp_supplier "
        . " LEFT JOIN mrp_po AS H ON A.id_mrp_po = H.id_mrp_po"
        . " {$where}"
        . " GROUP BY B.id_mrp_inventory_spesifik"
        . " ORDER BY A.id_mrp_task_orders ASC"
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

        $total = (($da->jumlah * $da->nilai) * $da->harga_task_order_request);
        $catatan = "id='catatan_{$da->id_mrp_po_asset}'";
        $brn = "";
        if($da->brand){
            $brn = "<br>Brand:".$da->brand;
        }
//        $da->catatan;
        if($status <= 6){
        $catatan = $this->form_eksternal->form_textarea('catatan[]', $da->catatan, $catatan.'style="height: 50px;" class="form-control input-sm"');
        }else{
            $catatan = $da->catatan;
        }
        
        if($da->flag_desimal == 1){
        $ank_jumlah = number_format($da->jumlah,2,".",",");
        $ank_harga = number_format($da->harga_task_order_request,2,".",",");
        }else{
        $ank_jumlah = number_format($da->jumlah,0,".",",");
        $ank_harga = number_format($da->harga_task_order_request,0,".",",");
        }
        $hasil[] = array(
        $da->nama_barang." ".$da->title_spesifik.$brn,    
        $da->satuan,
        $ank_jumlah,
        $ank_harga,    
        number_format($total),
        $catatan
         ."<script>"
                . '$(function() {'
                   . "$('#catatan_{$da->id_mrp_po_asset}').keyup(function(){"
                   . "var dt_catatan = encodeURIComponent($('#catatan_{$da->id_mrp_po_asset}').val());"
                   . "var dataString2 = 'catatan='+ dt_catatan;"
                   ."$.ajax({"
                        ."type : 'POST',"
                        ."url : '".site_url("mrp/mrp-ajax-po/update-keterangan-po-task-orders-request/{$da->id_mrp_po_asset}")."',"
                        ."data: dataString2,"
                        ."dataType : 'html',"
                        ."success: function(data) {"
//                                . "alert('aa');"
                        ."},"
                     ."});"            
                   . "});"                  
             . "});"    
            . "</script>",    
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
  
  function get_mrp_detail_po_list_umum($id_mrp_task_orders = 0,$id_mrp_po = 0,$status = 0,$start = 0, $dt_total = 0){
//      $id_mrp_supplier = 1;
       $url = base_url()."themes/".DEFAULTTHEMES."/img/ajax-loader.gif";
      $where = "WHERE A.status >= 3 AND A.id_mrp_task_orders = '$id_mrp_task_orders' AND A.id_mrp_po = '$id_mrp_po'  ";

    $data = $this->global_models->get_query("SELECT A.id_mrp_po_asset,A.id_mrp_task_orders_request_asset,A.jumlah,A.catatan,A.note,C.name AS nama_barang,E.title AS satuan"
        . ",B.title AS title_spesifik,F.harga,E.group_satuan,A.harga AS harga_task_order_request,B.id_mrp_inventory_spesifik"
        . ",E.nilai,D.title AS brand,A.status,A.id_mrp_task_orders,A.id_mrp_po,A.status,H.flag_desimal"
        . " FROM mrp_po_asset AS A"
        . " LEFT JOIN mrp_inventory_spesifik AS B ON A.id_mrp_inventory_spesifik = B.id_mrp_inventory_spesifik"
        . " LEFT JOIN mrp_inventory_umum AS C ON B.id_mrp_inventory_umum = C.id_mrp_inventory_umum"
        . " LEFT JOIN mrp_brand AS D ON B.id_mrp_brand = D.id_mrp_brand"
        . " LEFT JOIN mrp_satuan AS E ON A.id_mrp_satuan = E.id_mrp_satuan"
        . " LEFT JOIN mrp_supplier_inventory AS F ON B.id_mrp_inventory_spesifik = (F.id_mrp_inventory_spesifik AND F.id_mrp_supplier = '{$id_mrp_supplier}')"
        . " LEFT JOIN mrp_supplier G ON F.id_mrp_supplier = G.id_mrp_supplier "
        . " LEFT JOIN mrp_po AS H ON A.id_mrp_po = H.id_mrp_po"
        . " {$where}"
        . " GROUP BY B.id_mrp_inventory_spesifik"
        . " ORDER BY A.id_mrp_task_orders ASC"
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

        $total = (($da->jumlah * $da->nilai) * $da->harga_task_order_request);
       
        $brn = "";
        if($da->brand){
            $brn = "<br>Brand:".$da->brand;
        }
        
            $catatan = $da->catatan;
     
        
        if($da->flag_desimal == 1){
        $ank_jumlah = number_format($da->jumlah,2,".",",");
        $ank_harga = number_format($da->harga_task_order_request,2,".",",");
        }else{
        $ank_jumlah = number_format($da->jumlah,0,".",",");
        $ank_harga = number_format($da->harga_task_order_request,0,".",",");
        }
        $hasil[] = array(
        $da->nama_barang." ".$da->title_spesifik.$brn,    
        $da->satuan,
        $ank_jumlah,
        $ank_harga,    
        number_format($total),
        $catatan,    
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