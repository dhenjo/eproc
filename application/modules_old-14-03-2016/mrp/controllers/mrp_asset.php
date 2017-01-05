<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mrp_asset extends MX_Controller {
    
  function __construct() {      
    $this->menu = $this->cek();
  }
  
    function asset_department(){
    
//   if(!$this->input->post(NULL)){ 
    $dropdown_department = $this->global_models->get_dropdown("hr_department", "id_hr_department", "title", TRUE);     
    $dropdown_company = $this->global_models->get_dropdown("hr_company", "id_hr_company", "title", TRUE); 
        
//    print $this->session->userdata('stock_dept_search_id_hr_master');
//    die;
          
       $pst = $this->input->post(NULL);
       if($pst){
//          print_r($pst); 
//          die;
           $id_hr_master_organisasi = end(array_filter($pst['id_hr_master_organisasi']));

           $set = array(
            "asset_dept_search_id_company"          => $pst['id_company'],
            "asset_dept_search_id_hr_master"        => $id_hr_master_organisasi,
            
        );
          $this->session->set_userdata($set);
       }else{
           $hr_pegawai = $this->global_models->get("hr_pegawai", array("id_users" => $this->session->userdata("id")));
    
           $set = array(
            "asset_dept_search_id_company"          => $hr_pegawai[0]->id_hr_company,
            "asset_dept_search_id_hr_master"        => $hr_pegawai[0]->id_hr_master_organisasi,  
        );
          $this->session->set_userdata($set);
       }
//       print $this->session->userdata("stock_dept_search_id_company");
//       die;
       $where = "WHERE D.id_hr_master_organisasi='{$this->session->userdata("asset_dept_search_id_hr_master")}'";
        $detail = $this->global_models->get_query("SELECT "
        . "D.title AS aa,E.title AS bb,F.title AS cc,G.title AS dd,D.level AS a1,E.level AS b1,F.level AS c1,G.level AS d1"
        . ",D.id_hr_master_organisasi AS a2,E.id_hr_master_organisasi AS b2,F.id_hr_master_organisasi AS c2,G.id_hr_master_organisasi AS d2"
        . " FROM hr_master_organisasi AS D"
//        . " LEFT JOIN m_users AS B ON A.id_users = B.id_users"
//        . " LEFT JOIN hr_company AS C ON A.id_hr_company = C.id_hr_company"    
//        . " LEFT JOIN hr_master_organisasi AS D ON A.id_hr_master_organisasi = D.id_hr_master_organisasi"
        . " LEFT JOIN hr_master_organisasi AS E ON E.id_hr_master_organisasi = D.parent"
        . " LEFT JOIN hr_master_organisasi AS F ON F.id_hr_master_organisasi = E.parent"
        . " LEFT JOIN hr_master_organisasi AS G ON G.id_hr_master_organisasi = F.parent"         
        . " {$where}");
        
        if($detail[0]->a1 == 4){
            $section        = $detail[0]->a2;
            $divisi         = $detail[0]->b2;
            $department     = $detail[0]->c2;
            $direktorat     = $detail[0]->d2;
        }elseif($detail[0]->a1 == 3){
            $divisi         = $detail[0]->a2;
            $department     = $detail[0]->b2;
            $direktorat     = $detail[0]->c2;
            $section        = 0;
        }elseif($detail[0]->a1 == 2){
            $department     = $detail[0]->a2;
            $direktorat     = $detail[0]->b2;
            $divisi         = 0;
            $section        = 0;
        }elseif($detail[0]->a1 == 1){
            $direktorat         = $detail[0]->a2;
            $department         = 0;
            $divisi             = 0;
            $section            = 0;
        }else{
            $direktorat         = 0;
            $department         = 0;
            $divisi             = 0;
            $section            = 0;
        }
      
       
//    print $this->session->userdata('stock_dept_search_id_hr_master');
//       die;
    $css = "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/datatables/dataTables.bootstrap.css' rel='stylesheet' type='text/css' />"
      . "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/jQueryUI/jquery-ui-1.10.3.custom.min.css' rel='stylesheet' type='text/css' />"
      . "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/select2.css' type='text/css' rel='stylesheet'>";

    $foot = ""
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/datatables/jquery.dataTables.js' type='text/javascript'></script>"
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/datatables/dataTables.bootstrap.js' type='text/javascript'></script>"
      . "<script type='text/javascript' src='".base_url()."themes/".DEFAULTTHEMES."/js/select2.js'></script>"
     ;
	
    $foot .= "<script>"            
      . '$(function() {'                         
        . 'var table = '
        . '$("#tableboxy").dataTable({'
          . '"order": [[ 0, "asc" ]],'
        . '});'
        . 'ambil_data(table, 0, 0);'
      . '});'
            
         . "$('#pending-mutasi2').click(function(){"
                    . '$(function() {'                         
                    . 'var table = '
                    . '$("#tableboxy7").dataTable({'
                    . '"order": [[ 0, "asc" ]],'
                    . '"bDestroy": true'
                . '});'
                    . "table.fnClearTable();"  
                    . 'ambil_data7(table, 0);'
        . '});'
            
         . 'function ambil_data7(table, mulai){'
        . "var type =1;"
//        . "var id_inventory = '{$id_mrp_inventory_spesifik}';"        
        . '$.post("'.site_url("mrp/mrp-ajax-asset/get-pending-mutasi").'/"+mulai, function(data){'
          . '$("#loader-page2").show();'
          . 'var hasil = $.parseJSON(data);'
          . 'if(hasil.status == 2){'
            . 'table.fnAddData(hasil.hasil);'
            . 'ambil_data7(table, hasil.start);'
          . '}'
          . 'else{'
            . '$("#loader-page2").hide();'
          . '}'
        . '});'
      . '}'
        . "});"
            
     . 'function ambil_data(table, mulai){'
        . '$.post("'.site_url("mrp/mrp-ajax-asset/get-asset-department").'/"+mulai, function(data){'
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
                
      . "$('#stock-dept2').click(function(){"
        . '$(function() {'                         
        . 'var table = '
        . '$("#tableboxy5").dataTable({'
          . '"order": [[ 0, "desc" ]],'
          . '"bDestroy": true'
        . '});'
        . "table.fnClearTable();"        
        . 'ambil_data2(table, 0);'
      . '});'
            
        
            
     . 'function ambil_data2(table, mulai){'
        . "var type =1;"
//        . "var id_inventory = '{$id_mrp_inventory_spesifik}';"        
        . '$.post("'.site_url("mrp/mrp-ajax-asset/get-asset-department-detail").'/"+type+"/0/"+mulai, function(data){'
          . '$("#loader-page2").show();'
          . 'var hasil = $.parseJSON(data);'
          . 'if(hasil.status == 2){'
            . 'table.fnAddData(hasil.hasil);'
            . 'ambil_data2(table, hasil.start);'
          . '}'
          . 'else{'
            . '$("#loader-page2").hide();'
          . '}'
        . '});'
      . '}'
        . "});"
                        
	  . "</script>"; 
    
    $foot .= "<script>"
              ."var id_company = $('#id_company').val();"
              ."company(id_company);"
         . "$('.dropdown2').select2();"
              . "$(function() {"
              ."$('#id_company').change(function(){"
                 ." var id=$(this).val();"
                 ."company(id,1);"
                 ."department(id,1);"
                 ."divisi(id,1);"
                 ."section(id,1);"
                ."});"
              
                . "$( '#users' ).autocomplete({"
                  . "source: '".site_url("mrp/mrp-ajax-stock/get-users")."',"
                  . "minLength: 1,"
                  . "search  : function(){ $(this).addClass('working');},"
                  . "open    : function(){ $(this).removeClass('working');},"
                  . "select: function( event, ui ) {"
                    . "$('#id_users').val(ui.item.id);"
                  . "}"
                . "});"
            . "});"
              
      ."function company(id,flag){"
      ."var dataString2 = 'name='+ id+'&flag='+flag;"
         ."$.ajax"
            ."({"
            ."type: 'POST',"
            ."url: '".site_url("mrp/mrp-ajax-stock/ajax-company-direktorat/{$direktorat}")."',"
            ."data: dataString2,"
            ."cache: false,"
            ."success: function(html)"
            ."{"
            ."$('#dt_direktorat').html(html);"
            ."}"
            ."});"
        ."}"
      
      ."function department(id,flag){"
       ."var dataString2 = 'name='+ id+'&flag='+flag;"
         ."$.ajax"
            ."({"
            ."type: 'POST',"
            ."url: '".site_url("mrp/mrp-ajax-stock/ajax-company-department/{$department}")."',"
            ."data: dataString2,"
            ."cache: false,"
            ."success: function(html)"
            ."{"
            ."$('#dt_department').html(html);"
            ."}"
            ."});"
      ."}"
      
      ."function divisi(id,flag){"
       ."var dataString2 = 'name='+ id+'&flag='+flag;"
         ."$.ajax"
            ."({"
            ."type: 'POST',"
            ."url: '".site_url("mrp/mrp-ajax-stock/ajax-company-divisi/{$divisi}")."',"
            ."data: dataString2,"
            ."cache: false,"
            ."success: function(html)"
            ."{"
            ."$('#dt_divisi').html(html);"
            ."}"
            ."});"
      ."}"
                    
      ."function section(id,flag){"
       ."var dataString2 = 'name='+ id+'&flag='+flag;"
         ."$.ajax"
            ."({"
            ."type: 'POST',"
            ."url: '".site_url("mrp/mrp-ajax-stock/ajax-company-section/{$section}")."',"
            ."data: dataString2,"
            ."cache: false,"
            ."success: function(html)"
            ."{"
            ."$('#dt_section').html(html);"
            ."}"
            ."});"
      ."}"           
      ."</script>";
        
    $this->template->build("asset/main", 
        array(
              'url'                 => base_url()."themes/".DEFAULTTHEMES."/",
              'menu'                => 'mrp/mrp-asset/asset-department',
              'title'               => lang("Asset Department"),
              'department'  => $dropdown_department,
              'company'     => $dropdown_company,
              'detail'      => $detail,
//              'id_mrp_po'           => $id_mrp_po,
//              'dt_status'              => $status,
//              'breadcrumb'  => array(
//                    "mrp_list_stock_department"  => "mrp/stock-department"
//                ),
              'css'         => $css,
              'foot'        => $foot,
//              'img'       => base_url()."themes/".DEFAULTTHEMES."/img/ajax-loader.gif"
//              'items'       => $items
            ));
      $this->template
        ->set_layout('form')
        ->build("asset/main");
//   }else{
       
       
//   }
}

   function asset_department_detail($type = 0,$id_mrp_inventory_spesifik = 0){
//     $stock = $this->global_models->get("mrp_stock_out", array("id_mrp_stock" => $id_mrp_stock)); 
     
//       if($this->session->userdata("id") == 1){
//           $id_users = 9;
//       }
//      $hr_pegawai = $this->global_models->get("hr_pegawai", array("id_users" => $id_users));
//       
       $pst = $this->input->post();
      
       if($pst){
//         print_r($pst);
//         die("ss");
        if($pst["no_asset"]){
            $kirim = array(
            "no_asset"                    => $pst['no_asset'],
            "status"                      => $pst['status'],    
            "update_by_users"             => $this->session->userdata("id"),
            "update_date"                 => date("Y-m-d H:i:s")
        );

      $update_data = $this->global_models->update("mrp_stock_out", array("id_mrp_stock_out" => $pst['id_mrp_stock_out']),$kirim); 
      
      $gt =$this->global_models->get("mrp_stock_out",array("id_mrp_stock_out" => $pst['id_mrp_stock_out']));
      $dtstatus = array( 1=> "<span class='label bg-green'>Dipakai</span>",
         2 => "<span class='label bg-orange'>Pending Mutasi</span>",
         3 => "<span class='label bg-red'>Mutasi</span>",
         4 => "<span class='label bg-navy'>Reject</span>",
         5 => "<span class='label bg-green'>Update</span>",
         6 => "<span class='label bg-red'>Hilang</span>",
         7 => "<span class='label bg-red'>Rusak</span>",
        );
      if($pst['no_asset']){
          $noasset = "No. Asset:".$pst['no_asset'];
      }else{
          $noasset = "";
      }
      $kirim = array(
            "id_mrp_stock_out"                     => $gt[0]->id_mrp_stock_out,
            "id_hr_pegawai"                        => $gt[0]->id_hr_pegawai,
            "id_hr_master_organisasi"              => $gt[0]->id_hr_master_organisasi,
            "id_hr_company"                        => $gt[0]->id_hr_company,
            "id_mrp_inventory_spesifik"            => $gt[0]->id_mrp_inventory_spesifik,
            "id_mrp_satuan"                        => $gt[0]->id_mrp_satuan,
            "harga"                                => $gt[0]->harga,
            "jumlah"                               => $gt[0]->jumlah,
            "status"                               => 5,
            "note"                                 => "Data di Update <br> ".$noasset."<br>Dengan Status:".$dtstatus[$pst['status']],
            "create_by_users"                      => $this->session->userdata("id"),
            "create_date"                          => date("Y-m-d H:i:s")
            );
            $this->global_models->insert("mrp_stock_out_department", $kirim);
      
    if($update_data){
        $this->session->set_flashdata('success', 'Data berhasil di Update');
        redirect("mrp/mrp-asset/asset-department-detail/{$pst['type']}/{$pst['id_mrp_inventory_spesifik']}");
     }
       }
       
       if($pst["id_users"] && $pst['tanggal'] && $pst['jumlah_mutasi']){
           if($pst['total'] >= $pst['jumlah_mutasi']){
               $ttl = $pst['total'] - $pst['jumlah_mutasi'];
               if($ttl > 0){
                   $stts = 1;
               }else{
                   $stts = 2;
               }
        $kirim = array(
            "status"                      => $stts,
            "mutasi"                     => $pst['jumlah_mutasi'],      
            "update_by_users"             => $this->session->userdata("id"),
            "update_date"                 => date("Y-m-d H:i:s")
            );
            $update_data = $this->global_models->update("mrp_stock_out", array("id_mrp_stock_out" => $pst['id_mrp_stock_out']),$kirim); 
       
            $gt =$this->global_models->get("mrp_stock_out",array("id_mrp_stock_out" => $pst['id_mrp_stock_out']));
            $kirim = array(
            "id_mrp_stock_out"                     => $gt[0]->id_mrp_stock_out,
            "id_hr_pegawai"                        => $gt[0]->id_hr_pegawai,
            "id_hr_master_organisasi"              => $gt[0]->id_hr_master_organisasi,
            "id_hr_company"                        => $gt[0]->id_hr_company,
            "id_mrp_inventory_spesifik"            => $gt[0]->id_mrp_inventory_spesifik,
            "id_mrp_satuan"                        => $gt[0]->id_mrp_satuan,
            "jumlah"                               => $pst['jumlah_mutasi'],  
            "harga"                                => $gt[0]->harga,
            "status"                               => 2,
            "user_penerima"                        => $pst['id_users'],
            "tanggal"                              => $pst['tanggal'],
            "note"                                 => $pst['note'],
            "create_by_users"                      => $this->session->userdata("id"),
            "create_date"                          => date("Y-m-d H:i:s")
            );
            $this->global_models->insert("mrp_stock_out_department", $kirim);
            
            $this->session->set_flashdata('success', 'Data berhasil di Update');
            redirect("mrp/mrp-asset/asset-department-detail/{$pst['type']}/{$pst['id_mrp_inventory_spesifik']}");
           }else{
               $this->session->set_flashdata('notice', 'Jumlah Yang Anda Masukan melebihi Jumlah Asset');
               redirect("mrp/mrp-asset/asset-department-detail/{$pst['type']}/{$pst['id_mrp_inventory_spesifik']}");
           }
           
       }else{
           $this->session->set_flashdata('notice', 'Kolom Tanggal diserahkan,kolom Users dan kolom Jumlah Harus di Isi');
           redirect("mrp/mrp-asset/asset-department-detail/{$pst['type']}/{$pst['id_mrp_inventory_spesifik']}");
       }
//           print_r($pst);
////            $pst = $_REQUEST." bbbr";
////       print_r($pst);
//       die("ss");
       }
       
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
//    print $aa; die;
       $where = " A.id_mrp_inventory_spesifik='$id_mrp_inventory_spesifik' AND A.id_hr_master_organisasi IN ({$aa}) AND A.id_hr_company ='{$this->session->userdata('stock_dept_search_id_company')}' ";
       
      $data = $this->global_models->get_query("SELECT "
        . " SUM(A.jumlah - (A.pemakaian + A.mutasi)) AS jumlah,A.id_mrp_stock_out,C.name AS title_umum,B.title AS title_spesifik"
        . " FROM mrp_stock_out AS A"
        . " LEFT JOIN mrp_inventory_spesifik AS B ON A.id_mrp_inventory_spesifik = B.id_mrp_inventory_spesifik"
        . " LEFT JOIN mrp_inventory_umum AS C ON B.id_mrp_inventory_umum = C.id_mrp_inventory_umum"
//        . " LEFT JOIN mrp_brand AS D ON B.id_mrp_brand = D.id_mrp_brand"
//        . " LEFT JOIN mrp_satuan AS E ON A.id_mrp_satuan = E.id_mrp_satuan"
        . " WHERE A.status='1' AND {$where}"
        . " GROUP BY A.id_mrp_inventory_spesifik"
        );
//        print "<pre>";
//        print_r($data);
//        print "</pre>";
//        die;
//       print $this->db->last_query();
//       die;
      if($data[0]->jumlah){
          $jml = $data[0]->jumlah;
      }else{
          $jml = 0;
      }
//     print $jml; 
//     die;
//        print_r($data);
//        die;
    $css = "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/datatables/dataTables.bootstrap.css' rel='stylesheet' type='text/css' />"
      . "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/jQueryUI/jquery-ui-1.10.3.custom.min.css' rel='stylesheet' type='text/css' />";
    $url = base_url("mrp/mrp-stock/stock-department-detail/{$type}/{$id_mrp_inventory_spesifik}"); 
    $foot = ""
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/datatables/jquery.dataTables.js' type='text/javascript'></script>"
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/datatables/dataTables.bootstrap.js' type='text/javascript'></script>"
     ;
	
    $foot .= "<script>"
            
            . "$(function(){"
           
            . "$( '#users' ).autocomplete({"
                  . "source: '".site_url("mrp/mrp-ajax-stock/get-pegawai/")."',"
                  . "minLength: 1,"
                  . "search  : function(){ $(this).addClass('working');},"
                  . "open    : function(){ $(this).removeClass('working');},"
                  . "select: function( event, ui ) {"
                    . "alert('abc');"
                    . "$('#id_users').val(ui.item.id);"
                  . "}"
            . "});"
                          
            . "$('#detail-pemakaian2').click(function(){"
                    . '$(function() {'                         
                    . 'var table = '
                    . '$("#tableboxy6").dataTable({'
                    . '"order": [[ 5, "desc" ]],'
                    . '"bDestroy": true'
                . '});'
                    . "table.fnClearTable();"  
                    . 'ambil_data3(table, 0);'
                . '});'
            
                . 'function ambil_data3(table, mulai){'
                   . '$.post("'.site_url("mrp/mrp-ajax-asset/get-detail-pemakaian/{$type}/{$id_mrp_inventory_spesifik}").'/"+mulai, function(data){'
                     . '$("#loader-page3").show();'
                     . 'var hasil = $.parseJSON(data);'
                     . 'if(hasil.status == 2){'
                       . 'table.fnAddData(hasil.hasil);'
                       . 'ambil_data3(table, hasil.start);'
                     . '}'
                     . 'else{'
                       . '$("#loader-page3").hide();'
                     . '}'
                   . '});'
                 . '}'
            . "});"
             
            . "$('#btn-mutasi').click(function(){"
//               
                    . "var jumlah   = $('#dt_jumlah2').val();"
                    . "var tgl      = $('#tgl_diserahkan6').val();"
                    . "var note     = $('#note2').val();"
                    . "id_users     = $('#id_users').val();"
                    . " var dt_total = '{$jml}';"      
                    . " var dt_total2   = (isNaN(dt_total)) ? 0 : dt_total;"
                    . " var dt_jumlah   = (isNaN(jumlah))   ? 0 : jumlah;"
                    . " var dt_users    = (isNaN(id_users)) ? 0 : id_users;" 
                        . "if(dt_jumlah == 0 && dt_users == 0){ "
                          . " alert('Jumlah Tidak Boleh Kosong Dan Users Harus di Isi');"
                        . " }"
                            . "else{"
//                            . "alert(id_users);"
                             . "if(dt_total2 >= dt_jumlah){"
                                ."var dataString2 = 'jumlah='+ dt_jumlah +'&note='+note+'&tanggal='+tgl+'&total='+ dt_total2 +'&id_users='+dt_users;"
                                ."$.ajax({"
                                ."type : 'POST',"
                                ."url : '".site_url("mrp/mrp-ajax-stock/update-mutasi-stock-department/{$id_mrp_inventory_spesifik}")."',"
                                ."data: dataString2,"
                                ."dataType : 'html',"
                                ."success: function(data) {";
                               $foot .= "window.location.href ='{$url}';"     
                                ."},"
                             ."});"
                                . "}else{"
                                    . "alert('Jumlah yang di input Tidak Boleh Melebihi Stock');"
                                . "}"
                        . "}"
            . "});"
                                       
             . "$('#btn-pemakaian').click(function(){"
                    . "var jumlah   = $('#dt_jumlah').val();"
                    . "var tgl      = $('#tgl_pemakaian').val();"
                    . "var note     = $('#note').val();"
                    . "var dt_total = '{$jml}';"      
                    . " var dt_total2 = (isNaN(dt_total)) ? 0 : dt_total;"
                    . " var dt_jumlah = (isNaN(jumlah)) ? 0 : jumlah;"
                        . "if(dt_jumlah == 0){"
                          . "alert('Jumlah Tidak Boleh Kosong');"
                        . "}else{"
                             . "if(dt_total2 >= dt_jumlah){"
                                ."var dataString2 = 'jumlah='+ dt_jumlah +'&note='+note+'&tanggal='+tgl+'&total='+ dt_total2;"
                                ."$.ajax({"
                                ."type : 'POST',"
                                ."url : '".site_url("mrp/mrp-ajax-stock/update-pemakaian-stock/{$id_mrp_inventory_spesifik}")."',"
                                ."data: dataString2,"
                                ."dataType : 'html',"
                                ."success: function(data) {";
                               $foot .= "window.location.href ='{$url}';"     
                                ."},"
                             ."});"
                                . "}else{"
                                    . "alert('Jumlah yang di input Tidak Boleh Melebihi Stock');"
                                . "}"
                        . "}"
              . "});"
                                       
//            . "$( '.date' ).datepicker({"
//              . "showOtherMonths: true,"
//              . "dateFormat: 'yy-mm-dd',"  
//              . "selectOtherMonths: true,"  
//              . "selectOtherYears: true,"
//              . "});"
                                       
           . "$( '.date' ).datepicker({"
              . "showOtherMonths: true,"
              . "dateFormat: 'yy-mm-dd',"  
              . "selectOtherMonths: true,"  
              . "selectOtherYears: true,"
              . "});"
                  
            . "});"
//       . "$('#detail-stock2').click(function(){"
        . '$(function() {'                         
        . 'var table = '
        . '$("#tableboxy5").dataTable({'
          . '"order": [[ 1, "desc" ]],'
            . '"bDestroy": true'
        . '});'
            . "table.fnClearTable();"  
            . 'ambil_data(table, 0);'
//      . '});'
            
     . 'function ambil_data(table, mulai){'
        . '$.post("'.site_url("mrp/mrp-ajax-asset/get-asset-department-detail/{$type}/{$id_mrp_inventory_spesifik}").'/"+mulai, function(data){'
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
         . "});"
	  . "</script>"; 
    $hr_id_hr_master_organisasi = $this->global_models->get_field("hr_pegawai","id_hr_master_organisasi", array("id_users" => $this->session->userdata("id")));
        
    $this->template->build("asset/main-detail", 
        array(
              'url'                         => base_url()."themes/".DEFAULTTHEMES."/",
              'menu'                        => 'mrp/mrp-asset/asset-department',
              'title'                       => lang("Asset Department"),
              'jumlah'                      => $jml,
              'id_hr_master_organisasi'     => $hr_id_hr_master_organisasi,
               'name'                       => $data[0]->title_umum." ".$data[0]->title_spesifik,
//              'id_mrp_task_orders'  => $id_mrp_task_orders,
//              'id_mrp_po'           => $id_mrp_po,
//              'dt_status'              => $status,
              'breadcrumb'                  => array(
                    "mrp_list_asset_department"  => "mrp/mrp-asset/asset-department"
                ),
              'css'         => $css,
              'foot'        => $foot,
//              'img'       => base_url()."themes/".DEFAULTTHEMES."/img/ajax-loader.gif"
//              'items'       => $items
            ));
      $this->template
        ->set_layout('form')
        ->build("asset/main-detail");
}

  function proses_pending_mutasi($type = 0,$id_mrp_stock_out_department = 0){
    
       $get = $this->global_models->get_query("SELECT A.user_penerima,A.jumlah,A.tanggal,A.id_mrp_stock_out,A.status,"
        . " B.id_hr_master_organisasi,B.id_hr_company,"
        . " C.jumlah AS jumlah_asset,C.mutasi AS mutasi_asset,C.id_mrp_stock_in,C.id_mrp_stock_out,C.id_mrp_stock,C.id_mrp_inventory_spesifik,C.id_mrp_satuan,C.harga,"
        . " D.name AS receiver,G.title AS rcv_organisasi, E.name AS giver,H.title AS gvr_organisasi"
        . " FROM mrp_stock_out_department AS A"
        . " LEFT JOIN hr_pegawai AS B ON A.user_penerima = B.id_hr_pegawai"
        . " LEFT JOIN mrp_stock_out AS C ON A.id_mrp_stock_out = C.id_mrp_stock_out"
        . " LEFT JOIN m_users AS D ON B.id_users = D.id_users"
        . " LEFT JOIN m_users AS E ON A.create_by_users = E.id_users"
        . " LEFT JOIN hr_pegawai AS F ON E.id_users =F.id_users"
        . " LEFT JOIN hr_master_organisasi AS G ON B.id_hr_master_organisasi = G.id_hr_master_organisasi"
        . " LEFT JOIN hr_master_organisasi AS H ON F.id_hr_master_organisasi = H.id_hr_master_organisasi"
        . " WHERE A.id_mrp_stock_out_department = '{$id_mrp_stock_out_department}'"
        );
//        print "<pre>";
//        print_r($get);
//        print "</pre>";
//        die;
      if($type == 1){
          $dt_total = ($get[0]->jumlah_asset - $get[0]->mutasi_asset);
    if($dt_total > 0){
 if($get[0]->id_mrp_stock_out != 0 AND $get[0]->status == 2){

    $jml = ($get[0]->jumlah_asset - $get[0]->jumlah);
    $mts = ($get[0]->mutasi_asset - $get[0]->jumlah);
     $kirim = array(
    "jumlah"                        => $jml,
    "mutasi"                        => $mts,  
    "update_by_users"               => $this->session->userdata("id"),
    "update_date"                   => date("Y-m-d H:i:s")
    );
    $this->global_models->update("mrp_stock_out", array("id_mrp_stock_out" => $get[0]->id_mrp_stock_out),$kirim);

    $this->olah_stock_out_code($kode_out);
    $kirim = array(
          "id_mrp_stock_in"                      => $get[0]->id_mrp_stock_in,
          "id_mrp_stock"                         => $get[0]->id_mrp_stock,                        
          "id_hr_pegawai"                        => $get[0]->user_penerima,
          "id_hr_master_organisasi"              => $get[0]->id_hr_master_organisasi,
          "id_hr_company"                        => $get[0]->id_hr_company,
          "id_mrp_inventory_spesifik"            => $get[0]->id_mrp_inventory_spesifik,
         "id_mrp_satuan"                         => $get[0]->id_mrp_satuan,  
          "jumlah"                               => $get[0]->jumlah, 
          "code"                                 => $kode_out,     
          "harga"                                => $get[0]->harga,
          "status"                               => 1,
          "note"                                 => "Mutasi Asset Ke ".$get[0]->receiver." [".$get[0]->rcv_organisasi."] Dari ".$get[0]->giver." [".$get[0]->gvr_organisasi."]",
          "tanggal"                              => $get[0]->tanggal,
          "create_by_users"                      => $this->session->userdata("id"),
          "create_date"                          => date("Y-m-d H:i:s")
        );
  $this->global_models->insert("mrp_stock_out", $kirim);

  $kirim = array(
  "status"                        => 3,
  "note_mutasi"                   => "Mutasi Asset Ke ".$get[0]->receiver." [".$get[0]->rcv_organisasi."] Dari ".$get[0]->giver." [".$get[0]->gvr_organisasi."]",    
  "update_by_users"               => $this->session->userdata("id"),
  "update_date"                   => date("Y-m-d H:i:s")
);
$this->global_models->update("mrp_stock_out_department", array("id_mrp_stock_out_department" => $id_mrp_stock_out_department),$kirim);

}
    }else{
        $dtstock       = $get[0]->mutasi_asset - $get[0]->jumlah;
        $kirim = array(
        "id_hr_pegawai"                 => $get[0]->user_penerima,
        "id_hr_master_organisasi"       => $get[0]->id_hr_master_organisasi,
        "id_hr_company"                 => $get[0]->id_hr_company,
        "mutasi"                        => $dtstock,    
        "note"                          => "Mutasi Asset Ke ".$get[0]->receiver." [".$get[0]->rcv_organisasi."] Dari ".$get[0]->giver." [".$get[0]->gvr_organisasi."]",   
        "update_by_users"               => $this->session->userdata("id"),
        "status"                        => 1,    
        "update_date"                   => date("Y-m-d H:i:s")
        );
        $this->global_models->update("mrp_stock_out", array("id_mrp_stock_out" => $get[0]->id_mrp_stock_out),$kirim);

        $kirim = array(
        "status"                        => 3,
        "note_mutasi"                   => "Mutasi Asset Ke ".$get[0]->receiver." [".$get[0]->rcv_organisasi."] Dari ".$get[0]->giver." [".$get[0]->gvr_organisasi."]",    
        "update_by_users"               => $this->session->userdata("id"),
        "update_date"                   => date("Y-m-d H:i:s")
    );
        
    $this->global_models->update("mrp_stock_out_department", array("id_mrp_stock_out_department" => $id_mrp_stock_out_department),$kirim);

    }
      
   $this->session->set_flashdata('success', 'Data Diupdate');
//   redirect("mrp/mrp-asset/asset-department");
    }elseif($type == 2){
        $kirim = array(
        "status"                        => 4,
        "update_by_users"               => $this->session->userdata("id"),
        "update_date"                   => date("Y-m-d H:i:s")
    );
    $this->global_models->update("mrp_stock_out_department", array("id_mrp_stock_out_department" => $id_mrp_stock_out_department),$kirim);
     $get_stock_out = $this->global_models->get("mrp_stock_out",array("id_mrp_stock_out" => $get[0]->id_mrp_stock_out));
     $dtstock       = $get[0]->mutasi_asset - $get[0]->jumlah;
    
    $kirim = array(
        "status"                        => 1,
        "mutasi"                        => $dtstock,
        "update_by_users"               => $this->session->userdata("id"),
        "update_date"                   => date("Y-m-d H:i:s")
    );
    $this->global_models->update("mrp_stock_out", array("id_mrp_stock_out" => $get[0]->id_mrp_stock_out),$kirim);
    
    $this->session->set_flashdata('success', 'Data Diupdate');
   
     
    }
    redirect("mrp/mrp-asset/asset-department");
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
  
}