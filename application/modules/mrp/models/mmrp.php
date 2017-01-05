<?php
class Mmrp extends CI_Model {

    function __construct()
    {
        parent::__construct();
//        $this->load->database();
        $this->load->library('PHPExcel');
    }
    
    function cancel_po($id_mrp_po = 0) {
          
          $kirim = array(
            "status"                        => 12,
            "update_by_users"               => $this->session->userdata("id"),
            "update_date"                   => date("Y-m-d H:i:s")
        );
        
        $this->global_models->update("mrp_po", array("id_mrp_po" => "{$id_mrp_po}"),$kirim);
        $this->global_models->update("mrp_receiving_goods_po", array("id_mrp_po" => "{$id_mrp_po}"),$kirim);
        $this->global_models->update("mrp_po_asset", array("id_mrp_po" => "{$id_mrp_po}"),$kirim);
        
        $rg_po = $this->global_models->get_field("mrp_receiving_goods_po","id_mrp_receiving_goods_po",array("id_mrp_po" => "{$id_mrp_po}")); 
        
        $this->global_models->update("mrp_receiving_goods", array("id_mrp_receiving_goods_po" => "{$rg_po}"),$kirim);
        $this->global_models->update("mrp_receiving_goods_department", array("id_mrp_receiving_goods_po" => "{$rg_po}"),$kirim);
        
        $mrp_rg_detail = $this->global_models->get_field("mrp_receiving_goods","id_mrp_receiving_goods",array("id_mrp_receiving_goods_po" => "{$rg_po}"));
        
        $id_mrp_receiving_goods_department = $this->global_models->get_field("mrp_receiving_goods_department","id_mrp_receiving_goods_department", array("id_mrp_receiving_goods_po" => "{$rg_po}"));
        $this->global_models->update("mrp_receiving_goods_detail_department", array("id_mrp_receiving_goods_department" => "{$id_mrp_receiving_goods_department}"),$kirim);
            
        $rg_detail =$this->global_models->get("mrp_receiving_goods_detail",array("id_mrp_receiving_goods" => "{$mrp_rg_detail}"));
        
        foreach ($rg_detail as $ky => $rg_d) {
            
            $id_mrp_stock_in[$ky] = $this->global_models->get_field("mrp_stock_in","id_mrp_stock_in", array("id_mrp_receiving_goods_detail" => "{$rg_d->id_mrp_receiving_goods_detail}"));
            $id_mrp_stock_out[$ky] = $this->global_models->get_field("mrp_stock_out","id_mrp_stock_out", array("id_mrp_stock_in" => "{$id_mrp_stock_in[$ky]}"));
            
            $this->global_models->update("mrp_report", array("id_mrp_stock_out" => "{$id_mrp_stock_out[$ky]}"),$kirim);
            $this->global_models->update("mrp_stock_out", array("id_mrp_stock_in" => "{$id_mrp_stock_in[$ky]}"),$kirim);
            $this->global_models->update("mrp_receiving_goods_detail", array("id_mrp_receiving_goods_detail" => "{$rg_d->id_mrp_receiving_goods_detail}"),$kirim);
            $this->global_models->update("mrp_stock_in", array("id_mrp_receiving_goods_detail" => "{$rg_d->id_mrp_receiving_goods_detail}"),$kirim);
            
        }
        
        $po = $this->global_models->get("mrp_po_asset",array("id_mrp_po" => "{$id_mrp_po}"));
        
        foreach ($po as $val) {
            $this->global_models->update("mrp_receiving_goods_po_asset", array("id_mrp_po_asset" => "{$val->id_mrp_po_asset}"),$kirim);
        }
        $id_task_orders =$this->global_models->get_field("mrp_task_orders_request_asset","id_mrp_task_orders",array("id_mrp_task_orders_request_asset" =>"{$po[0]->id_mrp_task_orders_request_asset}"));
         $kirim2 = array(
            "status"                        => 1,
            "update_by_users"               => $this->session->userdata("id"),
            "update_date"                   => date("Y-m-d H:i:s")
        );
        
        $this->global_models->update("mrp_task_orders", array("id_mrp_task_orders" => "{$id_task_orders}"),$kirim2);
        $mrp_task_orders_request = $this->global_models->get("mrp_task_orders_request",array("id_mrp_task_orders" => "{$id_task_orders}"));
        foreach ($mrp_task_orders_request as $r) {
            $kirim3 = array(
            "status"                        => 4,
            "status_blast"                  => 3,    
            "update_by_users"               => $this->session->userdata("id"),
            "update_date"                   => date("Y-m-d H:i:s")
        );
           $this->global_models->update("mrp_request", array("id_mrp_request" => "{$r->id_mrp_request}"),$kirim3); 
        
        $kirim3 = array(
            "rg"                            => 0,
            "update_by_users"               => $this->session->userdata("id"),
            "update_date"                   => date("Y-m-d H:i:s")
        );
           $this->global_models->update("mrp_request_asset", array("id_mrp_request" => "{$r->id_mrp_request}"),$kirim3); 
              
        }
    }
    
function rg_all($id_mrp_receiving_goods_po = 0,$id_mrp_receiving_goods = 0,$tgl_diterima= "0000-00-00",$data_rg = 0,$id_mrp_receiving_goods_po_asset = 0){
    
    $arr_id = explode(",",$id_mrp_receiving_goods_po_asset);
    $arr_rg = explode(",",$data_rg);
      
 $flag_rg  = 0;
 foreach ($arr_id as $ky => $val) {
   
    $where = "WHERE id_mrp_receiving_goods_po_asset = '{$val}' ";
    $data = $this->global_models->get_query("SELECT A.rg,A.jumlah"
       . " FROM mrp_receiving_goods_po_asset AS A"   
       . " {$where}"
    );
        
//    $dt_rg = $this->global_models->get_field("mrp_receiving_goods_po_asset", "rg",array("id_mrp_receiving_goods_po_asset" => "{$val}"));
//    $dt_jml = $this->global_models->get_field("mrp_receiving_goods_po_asset", "jumlah",array("id_mrp_receiving_goods_po_asset" => "{$val}"));
     
    $dt_rg  = $data[0]->rg;
    $dt_jml = $data[0]->jumlah;
    //cek field rg, 
     if($dt_rg){
         $dt_rg = $dt_rg;
     }else{
         $dt_rg = 0;
     }
     
     //Total RG
     $fix = $arr_rg[$ky] + $dt_rg;
     
     if($dt_jml > $fix){
         $fix_rg = $fix;
         $flag_rg = $flag_rg + 1;
     }else{
         $fix_rg = $dt_jml;
     }
     
   $get_task_order_asset[$ky] = $this->global_models->get("mrp_receiving_goods_po_asset",array("id_mrp_receiving_goods_po_asset" => "{$val}"));
   
   $jmlh_now = $dt_jml - $fix;
   if($jmlh_now >= 0){
       
     if($arr_rg[$ky] > 0){
           $kirim = array(
        "rg"                            => $fix_rg,
        "update_by_users"               => $this->session->userdata("id"),
        "update_date"                   => date("Y-m-d H:i:s")
        );
        $this->global_models->update("mrp_receiving_goods_po_asset", array("id_mrp_receiving_goods_po_asset" => $val),$kirim);

        $dt_mrp_stock = $this->global_models->get("mrp_stock",array("id_mrp_inventory_spesifik" => "{$get_task_order_asset[$ky][0]->id_mrp_inventory_spesifik}"));
        $kirim = array(
            "id_mrp_receiving_goods"            => $id_mrp_receiving_goods,   
            "id_mrp_stock"                      => $dt_mrp_stock[0]->id_mrp_stock,
            "id_mrp_satuan"                     => $get_task_order_asset[$ky][0]->id_mrp_satuan,
            "jumlah"                            => $arr_rg[$ky],
            "id_mrp_inventory_spesifik"         => $get_task_order_asset[$ky][0]->id_mrp_inventory_spesifik,
            "harga"                             => $get_task_order_asset[$ky][0]->harga,
            "status"                            => 1,
            "create_by_users"                   => $this->session->userdata("id"),
            "create_date"                       => date("Y-m-d H:i:s")
          );
        $dt_id_stock[$ky] = $this->global_models->insert("mrp_receiving_goods_detail", $kirim);
    
        
    if($dt_mrp_stock[0]->id_mrp_inventory_spesifik > 0){
        
        $dtstock_in             = $dt_mrp_stock[0]->stock_in + $arr_rg[$ky];
        $dtstock_out            = $dt_mrp_stock[0]->stock_out;
        $dtstock_akhir = $dtstock_in - $dtstock_out;
                
         $kirim = array(
        "stock_in"                      => $dtstock_in,
        "stock_out"                     => $dtstock_out,
        "stock_akhir"                   => $dtstock_akhir,    
        "update_by_users"               => $this->session->userdata("id"),
        "update_date"                   => date("Y-m-d H:i:s")
    );
    $this->global_models->update("mrp_stock", array("id_mrp_inventory_spesifik" => $dt_mrp_stock[0]->id_mrp_inventory_spesifik),$kirim);
    $this->olah_stock_in_code($kode);
    $kirim = array(
        "id_mrp_stock"                          => $dt_mrp_stock[0]->id_mrp_stock,
        "kode"                                  => $kode,                            
        "id_mrp_receiving_goods_detail"         => $dt_id_stock[$ky],
        "id_mrp_inventory_spesifik"             => $dt_mrp_stock[0]->id_mrp_inventory_spesifik,
        "id_mrp_satuan"                         => $dt_mrp_stock[0]->id_mrp_satuan,    
        "id_mrp_supplier"                       => $get_task_order_asset[$ky][0]->id_mrp_supplier,
        "jumlah"                                => $arr_rg[$ky],
        "jumlah_out"                            => 0,
        "tanggal_diterima"                      => $tgl_diterima, 
        "harga"                                 => $get_task_order_asset[$ky][0]->harga,
        "status"                                => 1,
        "create_by_users"                       => $this->session->userdata("id"),
        "create_date"                           => date("Y-m-d H:i:s")
      );
     $id_mrp_stock_in = $this->global_models->insert("mrp_stock_in", $kirim);
     
    }else{
        
      $kirim = array( 
        "id_mrp_inventory_spesifik"         => $get_task_order_asset[$ky][0]->id_mrp_inventory_spesifik,
        "id_mrp_satuan"                     => $get_task_order_asset[$ky][0]->id_mrp_satuan,   
        "stock_in"                          => $arr_rg[$ky],
        "stock_out"                         => 0,
        "stock_akhir"                       => $arr_rg[$ky],  
        "status"                            => 1,
        "create_by_users"                   => $this->session->userdata("id"),
        "create_date"                       => date("Y-m-d H:i:s")
      );
     $id_stock[$ky] = $this->global_models->insert("mrp_stock", $kirim);
     
     $this->olah_stock_in_code($kode);
     $kirim = array(
        "id_mrp_stock"                          => $id_stock[$ky],
        "kode"                                  => $kode,  
        "id_mrp_receiving_goods_detail"         => $dt_id_stock[$ky],
        "id_mrp_inventory_spesifik"             => $get_task_order_asset[$ky][0]->id_mrp_inventory_spesifik,
        "id_mrp_satuan"                         => $get_task_order_asset[$ky][0]->id_mrp_satuan,    
        "id_mrp_supplier"                       => $get_task_order_asset[$ky][0]->id_mrp_supplier,
        "jumlah"                                => $arr_rg[$ky],
        "jumlah_out"                            => 0,
        "tanggal_diterima"                      => $tgl_diterima, 
        "harga"                                 => $get_task_order_asset[$ky][0]->harga,
        "status"                                => 1,
        "create_by_users"                       => $this->session->userdata("id"),
        "create_date"                           => date("Y-m-d H:i:s")
      );
     $id_mrp_stock_in = $this->global_models->insert("mrp_stock_in", $kirim);
     
    }
    
     }
        
     if($flag_rg > 0){
//            print $flag_rg."test1<br>";
                $krm2 = array(
                "status"                            => 7,
                "update_by_users"                   => $this->session->userdata("id"),
                "update_date"                       => date("Y-m-d H:i:s")
            );
            $this->global_models->update("mrp_receiving_goods_po", array("id_mrp_receiving_goods_po" => $id_mrp_receiving_goods_po),$krm2);
            
        }else{
//            print $flag_rg."test2<br>";
               $krm2 = array(
                "status"                            => 8,
                "update_by_users"                   => $this->session->userdata("id"),
                "update_date"                       => date("Y-m-d H:i:s")
            );
            $this->global_models->update("mrp_receiving_goods_po", array("id_mrp_receiving_goods_po" => $id_mrp_receiving_goods_po),$krm2);
             
        }
    
    $this->session->set_flashdata('success', 'Data Berhasil di Proses');
   }else{
    $this->session->set_flashdata('notice', 'Data tidak tersimpan');
   }

     }
     
}

 function data_rekap_pembayaran(){
//       $where = " B.id_hr_company ='{$this->session->userdata("report_bulanan_search_id_company")}' ";
     $where = "B.status != 15 ";
    
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
        . " WHERE {$where} $supplier $id_mrp_po"
        . " GROUP BY A.id_hr_master_organisasi"
        );
        
        return $data;
  }

function rekap_pembayaran_xls($filename){
//        print "aa"; die;
//    print_r($where_information); die;
    $month = array(0 => "Pilih", 1 => "Januari", 2 => "Feb", 3 => "Maret", 4 => "April", 5 => "Mei", 6 => "Juni", 7 => "Juli", 8 => "Agustus", 9 => "September",
            10 => "Oktober", 11 => "November", 12 => "Desember");
    $start_month = $month[$this->session->userdata('report_bulanan_search_start_month')];
    $end_month =    $month[$this->session->userdata('report_bulanan_search_end_month')];
     $periode = "Periode {$start_month} - {$end_month} {$this->session->userdata('report_bulanan_search_year')}";
    
    $dropdown_company = $this->global_models->get_dropdown("hr_company", "id_hr_company", "title", TRUE);
     $company = $dropdown_company[$this->session->userdata('report_bulanan_search_id_company')];  
    
    
     $dropdown_supplier = $this->global_models->get_dropdown("mrp_supplier", "id_mrp_supplier", "name", TRUE);
     $supplier = $dropdown_supplier[$this->session->userdata('report_bulanan_search_id_supplier')]; 
     $dt_supplier = "Supplier: ".$supplier;
     $objPHPExcel = $this->phpexcel;
      $objPHPExcel->getProperties()->setCreator("AntaVaya")
							 ->setLastModifiedBy("AntaVaya")
							 ->setTitle("Rekapan Pembayaran")
							 ->setSubject("Data Rekapan Pembayaran")
							 ->setDescription("Rekapan Pembayaran")
							 ->setKeywords("Rekapan Pembayaran")
							 ->setCategory("Rekapan Pembayaran");

      $objPHPExcel->setActiveSheetIndex(0);
      
//      $objPHPExcel->getActiveSheet()->mergeCells('A1:D1');
      $objPHPExcel->getActiveSheet()->setCellValue('B1', $company);
      $objPHPExcel->getActiveSheet()->getStyle('A1:D1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
      $objPHPExcel->getActiveSheet()->getStyle('A1:D1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
      $objPHPExcel->getActiveSheet()->getStyle('A1:D1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
//       print_r($dropdown_supplier);
//     print $supplier;
//     die;
      $objPHPExcel->getActiveSheet()->setCellValue('B2', $periode);      
      $objPHPExcel->getActiveSheet()->getStyle('A2:D2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
      $objPHPExcel->getActiveSheet()->getStyle('A2:D2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
      $objPHPExcel->getActiveSheet()->getStyle('A2:D2')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
      
      $objPHPExcel->getActiveSheet()->setCellValue('B3', $dt_supplier);      
      $objPHPExcel->getActiveSheet()->getStyle('A3:D3')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
      $objPHPExcel->getActiveSheet()->getStyle('A3:D3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
      $objPHPExcel->getActiveSheet()->getStyle('A3:D3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
      
      $objPHPExcel->getActiveSheet()->mergeCells('A4:A5');
      $objPHPExcel->getActiveSheet()->setCellValue('A4', 'NO');
      $objPHPExcel->getActiveSheet()->getStyle("A4:A5")->applyFromArray(
          array(
            'alignment' => array(
              'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
              'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            ),
            'borders' => array(
              'left'     => array(
                'style' => PHPExcel_Style_Border::BORDER_DOUBLE
              ),
              'right'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
              'top'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
              'bottom'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
            ),
          )
      );
      
      $objPHPExcel->getActiveSheet()->mergeCells('B4:B5');
      $objPHPExcel->getActiveSheet()->setCellValue('B4', 'Struktural');
      $objPHPExcel->getActiveSheet()->getStyle("B4:B5")->applyFromArray(
          array(
            'alignment' => array(
              'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
              'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            ),
            'borders' => array(
              'left'     => array(
                'style' => PHPExcel_Style_Border::BORDER_DOUBLE
              ),
              'right'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
              'top'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
              'bottom'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
            ),
          )
      );
     
      $huruf = array(1 => "C", 2 => "D", 3 => "E", 4 => "F", 5 => "G", 6 => "H", 7 => "I", 8 => "J", 9 => "K",
            10 => "L", 11 => "M", 12 => "N", 13 => "0", 14 => "P");
      $j = 0;
      $mth = array(1 => "Jan", 2 => "Feb", 3 => "Mar", 4 => "Apr", 5 => "Mei", 6 => "Jun", 7 => "Jul", 8 => "Agu", 9 => "Sep",
            10 => "Okt", 11 => "Nov", 12 => "Des");
      
      for ($a=$this->session->userdata("report_bulanan_search_start_month"); $a <= $this->session->userdata("report_bulanan_search_end_month"); $a++) {
       $j++;
      $jj = $huruf[$j]."5";
 
      $objPHPExcel->getActiveSheet()->setCellValue("{$jj}", $mth[$a]);
      $objPHPExcel->getActiveSheet()->getStyle("{$jj}")->applyFromArray(
          array(
            'alignment' => array(
              'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
              'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            ),
            'borders' => array(
              'left'     => array(
                'style' => PHPExcel_Style_Border::BORDER_DOUBLE
              ),
              'right'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
              'top'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
              'bottom'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
            ),
          )
      );
      
      }
      $FF = "C4:".$huruf[$j]."4";
      $objPHPExcel->getActiveSheet()->mergeCells($FF);
      $objPHPExcel->getActiveSheet()->setCellValue("C4", "Bulan");
      $objPHPExcel->getActiveSheet()->getStyle($FF)->applyFromArray(
          array(
            'alignment' => array(
              'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
              'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            ),
            'borders' => array(
              'left'     => array(
                'style' => PHPExcel_Style_Border::BORDER_DOUBLE
              ),
              'right'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
              'top'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
              'bottom'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
            ),
          )
      );
      
      $GG = $j+1;
      $HH = $j+2;
      $II = $huruf[$GG]."4:".$huruf[$GG]."5";
      $a_II = $huruf[$GG]."4";
       $objPHPExcel->getActiveSheet()->mergeCells($II);
      $objPHPExcel->getActiveSheet()->setCellValue($a_II, "Jumlah");
      $objPHPExcel->getActiveSheet()->getStyle($II)->applyFromArray(
          array(
            'alignment' => array(
              'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
              'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            ),
            'borders' => array(
              'left'     => array(
                'style' => PHPExcel_Style_Border::BORDER_DOUBLE
              ),
              'right'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
              'top'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
              'bottom'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
            ),
          )
      );
      
       $detail_rekap = $this->data_rekap_pembayaran();
//       $asd = $this->db->last_query();
          $nom = 0;
           $lvl = array(1 => "Direktorat",2 => "Divisi",3 => "Department",4 => "Section");
          
          foreach ($detail_rekap as $key => $da) {
            $nom = $nom + 1;
        
              $umum =  $da->title." [".$lvl[$da->level]."]";
              $objPHPExcel->getActiveSheet()->setCellValue('A'.(6+$key),$nom);
               $objPHPExcel->getActiveSheet()->getStyle('A'.(6+$key))->applyFromArray(
            array(
                'alignment' => array(
                  'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP,
                ),
                'borders' => array(
                  'top'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                  ),
                  'right'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                  ),
                  'left'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_DOUBLE
                  ),
                  'bottom'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                  ),  
                ),
              )
          );
              $objPHPExcel->getActiveSheet()->setCellValue('B'.(6+$key),$umum);
              $objPHPExcel->getActiveSheet()->getStyle('B'.(6+$key))->applyFromArray(
            array(
                'alignment' => array(
                  'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP,
                ),
                'borders' => array(
                  'top'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                  ),
                  'right'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                  ),
                  'left'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_DOUBLE
                  ),
                  'bottom'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                  ),  
                ),
              )
          );
              
              
//     $objPHPExcel->getActiveSheet()->getStyle('E'.(6+$key))->getNumberFormat()->setFormatCode('#,##0');
//              $objPHPExcel->getActiveSheet()->setCellValue('E'.(6+$key),$da->harga);
     
      $where = "B.status != 15 ";
    
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
       $dt_qt[$key] = $this->global_models->get_query("SELECT SUM(B.jumlah* B.harga) AS price"
        . " ,MONTH(B.tanggal) AS bulan"
        . " FROM mrp_report AS B"
        . " LEFT JOIN mrp_stock_out AS C ON B.id_mrp_stock_out = C.id_mrp_stock_out"
        . " LEFT JOIN mrp_stock_in AS D ON C.id_mrp_stock_in = D.id_mrp_stock_in"
        . " LEFT JOIN mrp_receiving_goods_detail AS G ON D.id_mrp_receiving_goods_detail = G.id_mrp_receiving_goods_detail"
        . " LEFT JOIN mrp_receiving_goods AS H ON G.id_mrp_receiving_goods = H.id_mrp_receiving_goods"
        . " LEFT JOIN mrp_receiving_goods_po AS I ON H.id_mrp_receiving_goods_po = I.id_mrp_receiving_goods_po"
        . " LEFT JOIN mrp_po AS J ON I.id_mrp_po = J.id_mrp_po"               
        . " WHERE B.id_hr_master_organisasi = {$da->id_hr_master_organisasi} AND {$where} $supplier $id_mrp_po"
        . " GROUP BY B.id_hr_master_organisasi,YEAR(B.tanggal), MONTH(B.tanggal)"
         );
        
        
//        $ww = $this->db->last_query();
         $huruf = array(1 => "C", 2 => "D", 3 => "E", 4 => "F", 5 => "G", 6 => "H", 7 => "I", 8 => "J", 9 => "K",
            10 => "L", 11 => "M", 12 => "N", 13 => "0", 14 => "P");
           
         foreach($dt_qt[$key] as $ky => $cek) {
            $ccss[$key][$cek->bulan] = $cek->price;
            $jml_hrg[$key] += ($cek->price);
        }
             
            $zz = 0;
        for ($a = $this->session->userdata("report_bulanan_search_start_month"); $a <= $this->session->userdata("report_bulanan_search_end_month"); $a++) {
         $cc[$key][$a] = 0;
         $zz++;
         $kx2 = $huruf[$zz].(7+$key);
         foreach($dt_qt[$key] as $ky => $cek2) {
             if($cek2->bulan == $a){
//             $ttl_qty = $ttl_qty + $cek->qty;
             $ccss2[$a] =  $ccss2[$a] + $cek2->price;
             
             }
            
           
        }
          $objPHPExcel->getActiveSheet()->setCellValue($kx2,$ccss2[$a]);
          $objPHPExcel->getActiveSheet()->getStyle($kx2)->getNumberFormat()->setFormatCode('#,##0');
        }
        $kcst[$ky][$key] = array();      
        $kcst[$ky][$key] = (array_replace($cc[$key],$ccss[$key]));   
        $gr1[$key] = 0;
        $l = 0;
        foreach ($kcst[$ky][$key] as $ks => $vm) {
             $l++;
             $kxl = $huruf[$l].(6+$key);
             $kxl2 = $huruf[$l].(7+$key);
             
             if($vm){
                  $gr[$ky][$key][$ks] = $vm;
//                  $gr1[$key] = $gr1[$key] + $vm;
                 
             }else{
                 $gr[$ky][$key][$ks] = 0;
//                 $gr1[$key] = $gr1[$key] + 0;
             }
            
            $objPHPExcel->getActiveSheet()->setCellValue($kxl,$gr[$ky][$key][$ks]);
            $objPHPExcel->getActiveSheet()->getStyle($kxl)->getNumberFormat()->setFormatCode('#,##0');
            $objPHPExcel->getActiveSheet()->getStyle($kxl)->applyFromArray(
            array(
                'alignment' => array(
                  'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP,
                ),
                'borders' => array(
                  'top'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                  ),
                  'right'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                  ),
                  'left'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_DOUBLE
                  ),
                  'bottom'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                  ),  
                ),
              )
          );
            
//            $objPHPExcel->getActiveSheet()->setCellValue($kxl2,$gr1);
//            $objPHPExcel->getActiveSheet()->getStyle($kxl2)->getNumberFormat()->setFormatCode('#,##0');
//            $objPHPExcel->getActiveSheet()->getStyle($kxl2)->applyFromArray(
//            array(
//                'alignment' => array(
//                  'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP,
//                ),
//                'borders' => array(
//                  'top'     => array(
//                    'style' => PHPExcel_Style_Border::BORDER_THIN
//                  ),
//                  'right'     => array(
//                    'style' => PHPExcel_Style_Border::BORDER_THIN
//                  ),
//                  'left'     => array(
//                    'style' => PHPExcel_Style_Border::BORDER_DOUBLE
//                  ),
//                  'bottom'     => array(
//                    'style' => PHPExcel_Style_Border::BORDER_THIN
//                  ),  
//                ),
//              )
//          );
            
        }
        
            $jml_akhir = $jml_akhir + $jml_hrg[$key];
            
            $GG = $l+1;
            $a_GG = $huruf[$GG].(6+$key);
            $objPHPExcel->getActiveSheet()->setCellValue($a_GG,$jml_hrg[$key]);
            $objPHPExcel->getActiveSheet()->getStyle($a_GG)->getNumberFormat()->setFormatCode('#,##0');
            $objPHPExcel->getActiveSheet()->getStyle($a_GG)->applyFromArray(
            array(
                'alignment' => array(
                  'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP,
                ),
                'borders' => array(
                  'top'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                  ),
                  'right'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                  ),
                  'left'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_DOUBLE
                  ),
                  'bottom'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                  ),  
                ),
              )
          );
            
            $GG = $l+1;
            $a_GG = $huruf[$GG].(7+$key);
            $objPHPExcel->getActiveSheet()->setCellValue($a_GG,$jml_akhir);
            $objPHPExcel->getActiveSheet()->getStyle($a_GG)->getNumberFormat()->setFormatCode('#,##0');
            $objPHPExcel->getActiveSheet()->getStyle($a_GG)->applyFromArray(
            array(
                'alignment' => array(
                  'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP,
                ),
                'borders' => array(
                  'top'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                  ),
                  'right'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                  ),
                  'left'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_DOUBLE
                  ),
                  'bottom'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                  ),  
                ),
              )
          );

          }
 
      $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(4);
      $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(70);
      $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
      $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
      $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
      $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
      
      
      
      $objPHPExcel->setActiveSheetIndex(0);
      header('Content-Type: application/vnd.ms-excel');
      header('Content-Disposition: attachment;filename="'.$filename."-".date("Y-m-d").'.xls"');
      header('Cache-Control: max-age=0');
      $objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel5');
//$objWriter->save(str_replace('.php', '.xls', __FILE__));
      $objWriter->save('php://output');die;
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
            "id_mrp_receiving_goods_department"     => $id_rg_department,
            "id_mrp_stock"                          => $data[0]->id_mrp_stock,    
            "id_mrp_satuan"                         => $data[0]->id_mrp_satuan,
            "jumlah"                                => $req_user[$ky],
            "id_mrp_inventory_spesifik"             => $id_mrp_inventory_spesifik,
            "harga"                                 => $data[0]->harga,
            "status"                                => 1,
            "id_hr_master_organisasi"               => $val->id_hr_master_organisasi,   
            "create_by_users"                       => $this->session->userdata("id"),
            "create_date"                           => date("Y-m-d H:i:s")
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
                "status_blast"                      => 1,    
                "update_by_users"                   => $this->session->userdata("id"),
                "update_date"                       => date("Y-m-d H:i:s")
             );
             $this->global_models->update("mrp_request", array("id_mrp_request" => $val->id_mrp_request),$krm2);
             }else{
               $krm2 = array(
                "status"                            => 9,
                "status_blast"                      => 2,   
                "update_by_users"                   => $this->session->userdata("id"),
                "update_date"                       => date("Y-m-d H:i:s")
             );
             $this->global_models->update("mrp_request", array("id_mrp_request" => $val->id_mrp_request),$krm2);

            }
        } 
        
       } // end looping
       $jml_stock_out = $this->global_models->get_field("mrp_stock_out","SUM(jumlah)", array("id_mrp_stock" => $data[0]->id_mrp_stock, "id_mrp_stock_in" => $id_mrp_stock_in));
         $kirim2 = array(
            "status"                        => 2,
            "jumlah_out"                    => $jml_stock_out,
            "update_by_users"               => $this->session->userdata("id"),
            "update_date"                   => date("Y-m-d H:i:s")
        );
        $this->global_models->update("mrp_stock_in", array("id_mrp_stock_in" => $id_mrp_stock_in),$kirim2);
        
        $lt_stock_out = $stock_akhir + $jml_stock_out;
        $hsl_stock = $stock_awal - $lt_stock_out;

            $krm = array(
            "stock_out"                         => $lt_stock_out,
            "stock_akhir"                       => $hsl_stock,
            "update_by_users"                   => $this->session->userdata("id"),
            "update_date"                       => date("Y-m-d H:i:s")
        );
        $this->global_models->update("mrp_stock", array("id_mrp_stock" => $data[0]->id_mrp_stock),$krm);
       
        
        if($id_mrp_request > 0){
           $dtrg = $this->global_models->get_query("SELECT SUM(B.rg) AS rg,SUM(B.jumlah) AS jumlah FROM mrp_request AS A "
                . " LEFT JOIN mrp_request_asset AS B ON A.id_mrp_request = B.id_mrp_request"
                . " WHERE A.id_mrp_request = '{$id_mrp_request}' "
                . " GROUP BY A.id_mrp_request ");
            $hsl_rg = $dtrg[0]->jumlah - $dtrg[0]->rg;
    
//            print $this->session->userdata("jml_rg_dpt")."ces<br>";
            if($hsl_rg > 0){
              
                $krm2 = array(
                "status"                            => 7,
                "status_blast"                      => 1,    
                "update_by_users"                   => $this->session->userdata("id"),
                "update_date"                       => date("Y-m-d H:i:s")
            );
            $this->global_models->update("mrp_request", array("id_mrp_request" => $id_mrp_request),$krm2);
            }else{
               $krm2 = array(
                "status"                            => 9,
                "status_blast"                      => 2,   
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
  
    function proses_mutasi_stock_to($id_mrp_task_orders = 0,$id_mrp_inventory_spesifik = 0,$jml_rg = 0,$tgl_diserahkan ="0000-00-00",$note = "",$status_history=""){
 // $status_history : 1 => dari Menu Request, 2 => Dari menu Mutasi => 3 => dari Menu Task Orders
  $arr_id_mrp_inventory_spesifik = explode(",",$id_mrp_inventory_spesifik);
  $arr_jml_rg = explode(",",$jml_rg);
  
  $data_awal = $this->global_models->get_query("SELECT A.id_mrp_request,B.id_hr_pegawai"
    . " FROM mrp_task_orders_request AS A"
    . " LEFT JOIN mrp_request AS B ON A.id_mrp_request = B.id_mrp_request"
    . " WHERE A.id_mrp_task_orders='{$id_mrp_task_orders}'");
  
    $this->olah_history_task_mutasi_code($kode_to_mutasi);
  $kirim = array(
        "id_mrp_request"                        => $id_mrp_request,
        "kode"                                  => $kode_to_mutasi,
        "status"                                => 1,                            
        "tanggal_diserahkan"                    => $tgl_diserahkan,
        "id_pegawai"                            => $this->session->userdata("id"),
        "note"                                  => $note,
        "create_by_users"                       => $this->session->userdata("id"),
        "create_date"                           => date("Y-m-d H:i:s")
      );
    $id_history_to_mutasi = $this->global_models->insert("history_to_mutasi", $kirim);
    
 
//  $hr_pegawai = $this->global_models->get("hr_pegawai", array("id_hr_pegawai" => "{$id_hr_pegawai}"));
  
  $flag= 0;
  foreach ($arr_id_mrp_inventory_spesifik as $ky => $val) {
     $mrp_stock = $this->global_models->get("mrp_stock",array("id_mrp_inventory_spesifik" => $val));
     
     $kirim = array(
        "id_history_to_mutasi"             => $id_history_to_mutasi,
        "id_mrp_inventory_spesifik"             => $val,
        "id_mrp_satuan"                         => $mrp_stock[0]->id_mrp_satuan,                            
        "jumlah"                                => $arr_jml_rg[$ky],
        "status"                                => $status_history,
        "create_by_users"                       => $this->session->userdata("id"),
        "create_date"                           => date("Y-m-d H:i:s")
      );
    $this->global_models->insert("history_request_mutasi_detail", $kirim);
     
     if($mrp_stock[0]->stock_akhir >= $arr_jml_rg[$ky]){
         $dt_stock_akhir = $mrp_stock[0]->stock_akhir - $arr_jml_rg[$ky];
         $dt_stock_out = $mrp_stock[0]->stock_out + $arr_jml_rg[$ky];
         
          $krm = array(
            "stock_out"                         => $dt_stock_out,
            "stock_akhir"                       => $dt_stock_akhir,
            "update_by_users"                   => $this->session->userdata("id"),
            "update_date"                       => date("Y-m-d H:i:s")
        );
        $this->global_models->update("mrp_stock", array("id_mrp_stock" => $mrp_stock[0]->id_mrp_stock),$krm);
        
        foreach ($data_awal as $k => $v){
            $mrp_request[$ky] = $this->global_models->get("mrp_request_asset", array("id_mrp_request" => "{$v->id_mrp_request}","id_mrp_inventory_spesifik" =>$val));
        }
        
        $dt_rg = $arr_jml_rg[$ky] + $mrp_request[$ky][0]->rg;
        
        if($dt_rg == $mrp_request[$ky][0]->jumlah){
            $flag = $flag;
        }else{
            $flag = $flag + 1;
        }
        
        $krm = array(
          "rg"                                => $dt_rg,
          "update_by_users"                   => $this->session->userdata("id"),
          "update_date"                       => date("Y-m-d H:i:s")
      );
      $this->global_models->update("mrp_request_asset", array("id_mrp_request" => "{$id_mrp_request}","id_mrp_inventory_spesifik" => "{$val}"),$krm);
      
     $data = $this->global_models->get_query("SELECT *"
        . " FROM mrp_stock_in AS A"
        . " WHERE A.id_mrp_stock='{$mrp_stock[0]->id_mrp_stock}' AND status='1'"
        . " ORDER BY A.tanggal_diterima ASC");  
        
     $jumlah_out = $arr_jml_rg[$ky]; 
        
     foreach ($data as $key => $vl) {
        $dt_hasil = $vl->jumlah - $vl->jumlah_out;
        
//        if($dt_hasil <= $arr_jml_rg[$ky]){
           
        
        $jml_rg = $mrp_request[$ky][0]->jumlah - $mrp_request[$ky][0]->rg;
    if($dt_hasil <= $jumlah_out){
      
        $total = $vl->jumlah_out + $dt_hasil;
        
        
        $kirim2 = array(
        "status"                        => 2,
        "jumlah_out"                    => $total,
        "update_by_users"               => $this->session->userdata("id"),
        "update_date"                   => date("Y-m-d H:i:s")
    );
    $this->global_models->update("mrp_stock_in", array("id_mrp_stock_in" => $vl->id_mrp_stock_in),$kirim2);
    
    $this->olah_stock_out_code($kode_out);
    $kirim = array(
        "id_mrp_stock_in"                      => $vl->id_mrp_stock_in,
        "code"                                 => $kode_out,
        "id_mrp_stock"                         => $mrp_stock[0]->id_mrp_stock,                            
        "id_hr_pegawai"                        => $hr_pegawai[0]->id_hr_pegawai,
        "id_hr_master_organisasi"              => $hr_pegawai[0]->id_hr_master_organisasi,
        "id_hr_company"                        => $hr_pegawai[0]->id_hr_company,
        "id_mrp_inventory_spesifik"            => $mrp_stock[0]->id_mrp_inventory_spesifik,
        "id_mrp_satuan"                        => $mrp_stock[0]->id_mrp_satuan,
        "jumlah"                               => $dt_hasil,  
        "harga"                                => $vl->harga,
        "status"                               => 1,
        "tanggal"                              => $tgl_diserahkan,
        "create_by_users"                      => $this->session->userdata("id"),
        "create_date"                          => date("Y-m-d H:i:s")
      );
    $dtid_out = $this->global_models->insert("mrp_stock_out", $kirim);
    
    $krm = array(
        "id_mrp_stock_out"                  => $dtid_out,
        "id_hr_pegawai"                     => $hr_pegawai[0]->id_hr_pegawai,
        "id_hr_master_organisasi"           => $hr_pegawai[0]->id_hr_master_organisasi,
        "id_hr_company"                     => $hr_pegawai[0]->id_hr_company,
        "id_mrp_inventory_spesifik"         => $mrp_stock[0]->id_mrp_inventory_spesifik,
        "id_mrp_satuan"                     => $mrp_stock[0]->id_mrp_satuan,
        "jumlah"                            => $dt_hasil,
        "harga"                             => $vl->harga,
        "status"                            => 1,
        "tanggal"                           => $tgl_diserahkan,
        "create_by_users"                   => $this->session->userdata("id"),
        "create_date"                       => date("Y-m-d H:i:s")
    );
    $this->global_models->insert("mrp_report", $krm);
    
    $jumlah_out = $jumlah_out - $dt_hasil;
    
    $this->session->set_flashdata('success', 'Data Tersimpan');
    }elseif($jumlah_out > 0){
        $jml = $jumlah_out;
        $total = $vl->jumlah_out + $jml;
        if($mrp_request[$ky][0]->jumlah >= $total){
            $dt = $total;
        }else{
            $dt = $mrp_request[$ky][0]->jumlah;
        }
        $kirim2 = array(
        "status"                        => 1,
        "jumlah_out"                    => $dt,
        "update_by_users"               => $this->session->userdata("id"),
        "update_date"                   => date("Y-m-d H:i:s")
    );
    $this->global_models->update("mrp_stock_in", array("id_mrp_stock_in" => $vl->id_mrp_stock_in),$kirim2);
    
    $this->olah_stock_out_code($kode_out);
    $kirim = array(
        "id_mrp_stock_in"                      => $vl->id_mrp_stock_in,
        "code"                                 => $kode_out,
        "id_mrp_stock"                         => $mrp_stock[0]->id_mrp_stock,                            
        "id_hr_pegawai"                        => $hr_pegawai[0]->id_hr_pegawai,
        "id_hr_master_organisasi"              => $hr_pegawai[0]->id_hr_master_organisasi,
        "id_hr_company"                        => $hr_pegawai[0]->id_hr_company,
        "id_mrp_inventory_spesifik"            => $mrp_stock[0]->id_mrp_inventory_spesifik,
        "id_mrp_satuan"                        => $mrp_stock[0]->id_mrp_satuan,
        "jumlah"                               => $jml,  
        "harga"                                => $vl->harga,
        "status"                               => 1,
        "tanggal"                              => $tgl_diserahkan,
        "create_by_users"                      => $this->session->userdata("id"),
        "create_date"                          => date("Y-m-d H:i:s")
      );
        $dtid_out = $this->global_models->insert("mrp_stock_out", $kirim);
    
    $krm = array(
        "id_mrp_stock_out"                  => $dtid_out,
        "id_hr_pegawai"                     => $hr_pegawai[0]->id_hr_pegawai,
        "id_hr_master_organisasi"           => $hr_pegawai[0]->id_hr_master_organisasi,
        "id_hr_company"                     => $hr_pegawai[0]->id_hr_company,
        "id_mrp_inventory_spesifik"         => $mrp_stock[0]->id_mrp_inventory_spesifik,
        "id_mrp_satuan"                     => $mrp_stock[0]->id_mrp_satuan,
        "jumlah"                            => $jml,
        "harga"                             => $vl->harga,
        "status"                            => 1,
        "tanggal"                           => $tgl_diserahkan,
        "create_by_users"                   => $this->session->userdata("id"),
        "create_date"                       => date("Y-m-d H:i:s")
    );
    $this->global_models->insert("mrp_report", $krm);
    
      $jumlah_out = $jumlah_out - $dt;
    }
      }    
    }else{
       $this->session->set_flashdata('notice', 'Data tidak tersimpan');
    }
  }
  
    if($flag > 0){
        $flag_req = 1;
    }else{
        $flag_req = 2;
    }
    
    $krm2 = array(
          "flag_request"                      => $flag_req,
          "tanggal_diserahkan"                => $tgl_diserahkan,
          "note_mutasi"                       => $note,
          "update_by_users"                   => $this->session->userdata("id"),
          "update_date"                       => date("Y-m-d H:i:s")
      );
  $this->global_models->update("mrp_request", array("id_mrp_request" => "{$id_mrp_request}"),$krm2);
  
    }
    
function proses_mutasi_stock($id_mrp_request = 0,$id_mrp_inventory_spesifik = 0,$jml_rg = 0,$id_hr_pegawai = 0,$tgl_diserahkan ="0000-00-00",$note = "",$status_history=""){
 // $status_history : 1 => dari Menu Request, 2 => Dari menu Mutasi => 3 => dari Menu Task Orders
  $arr_id_mrp_inventory_spesifik = explode(",",$id_mrp_inventory_spesifik);
  $arr_jml_rg = explode(",",$jml_rg);
  
  $hr_pegawai = $this->global_models->get("hr_pegawai", array("id_hr_pegawai" => "{$id_hr_pegawai}"));
  
  $this->olah_history_request_mutasi_code($kode_request_mutasi);
  $kirim = array(
        "id_mrp_request"                        => $id_mrp_request,
        "kode"                                  => $kode_request_mutasi,
        "status"                                => $status_history,                            
        "tanggal_diserahkan"                    => $tgl_diserahkan,
        "user_request"                          => $id_hr_pegawai,
        "note"                                  => $note,
        "create_by_users"                       => $this->session->userdata("id"),
        "create_date"                           => date("Y-m-d H:i:s")
      );
    $id_history_request_mutasi = $this->global_models->insert("history_request_mutasi", $kirim);
  
  $flag= 0;
  foreach ($arr_id_mrp_inventory_spesifik as $ky => $val) {
     $mrp_stock = $this->global_models->get("mrp_stock",array("id_mrp_inventory_spesifik" => $val));
     
     $kirim = array(
        "id_history_request_mutasi"             => $id_history_request_mutasi,
        "id_mrp_inventory_spesifik"             => $val,
        "id_mrp_satuan"                         => $mrp_stock[0]->id_mrp_satuan,                            
        "jumlah"                                => $arr_jml_rg[$ky],
        "status"                                => $status_history,
        "create_by_users"                       => $this->session->userdata("id"),
        "create_date"                           => date("Y-m-d H:i:s")
      );
    $this->global_models->insert("history_request_mutasi_detail", $kirim);
     
     if($mrp_stock[0]->stock_akhir >= $arr_jml_rg[$ky]){
         $dt_stock_akhir = $mrp_stock[0]->stock_akhir - $arr_jml_rg[$ky];
         $dt_stock_out = $mrp_stock[0]->stock_out + $arr_jml_rg[$ky];
         
          $krm = array(
            "stock_out"                         => $dt_stock_out,
            "stock_akhir"                       => $dt_stock_akhir,
            "update_by_users"                   => $this->session->userdata("id"),
            "update_date"                       => date("Y-m-d H:i:s")
        );
        $this->global_models->update("mrp_stock", array("id_mrp_stock" => $mrp_stock[0]->id_mrp_stock),$krm);
        $mrp_request[$ky] = $this->global_models->get("mrp_request_asset", array("id_mrp_request" => "{$id_mrp_request}","id_mrp_inventory_spesifik" =>$val));
        $dt_rg = $arr_jml_rg[$ky] + $mrp_request[$ky][0]->rg;
        
        if($dt_rg == $mrp_request[$ky][0]->jumlah){
            $flag = $flag;
        }else{
            $flag = $flag + 1;
        }
        
        $krm = array(
          "rg"                                => $dt_rg,
          "update_by_users"                   => $this->session->userdata("id"),
          "update_date"                       => date("Y-m-d H:i:s")
      );
      $this->global_models->update("mrp_request_asset", array("id_mrp_request" => "{$id_mrp_request}","id_mrp_inventory_spesifik" => "{$val}"),$krm);
      
     $data = $this->global_models->get_query("SELECT *"
        . " FROM mrp_stock_in AS A"
        . " WHERE A.id_mrp_stock='{$mrp_stock[0]->id_mrp_stock}' AND status='1'"
        . " ORDER BY A.tanggal_diterima ASC");  
        
     $jumlah_out = $arr_jml_rg[$ky]; 
        
     foreach ($data as $key => $vl) {
        $dt_hasil = $vl->jumlah - $vl->jumlah_out;
        
//        if($dt_hasil <= $arr_jml_rg[$ky]){
           
        
        $jml_rg = $mrp_request[$ky][0]->jumlah - $mrp_request[$ky][0]->rg;
    if($dt_hasil <= $jumlah_out){
      
        $total = $vl->jumlah_out + $dt_hasil;
        
        
        $kirim2 = array(
        "status"                        => 2,
        "jumlah_out"                    => $total,
        "update_by_users"               => $this->session->userdata("id"),
        "update_date"                   => date("Y-m-d H:i:s")
    );
    $this->global_models->update("mrp_stock_in", array("id_mrp_stock_in" => $vl->id_mrp_stock_in),$kirim2);
    
    $this->olah_stock_out_code($kode_out);
    $kirim = array(
        "id_mrp_stock_in"                      => $vl->id_mrp_stock_in,
        "code"                                 => $kode_out,
        "id_mrp_stock"                         => $mrp_stock[0]->id_mrp_stock,                            
        "id_hr_pegawai"                        => $hr_pegawai[0]->id_hr_pegawai,
        "id_hr_master_organisasi"              => $hr_pegawai[0]->id_hr_master_organisasi,
        "id_hr_company"                        => $hr_pegawai[0]->id_hr_company,
        "id_mrp_inventory_spesifik"            => $mrp_stock[0]->id_mrp_inventory_spesifik,
        "id_mrp_satuan"                        => $mrp_stock[0]->id_mrp_satuan,
        "jumlah"                               => $dt_hasil,  
        "harga"                                => $vl->harga,
        "status"                               => 1,
        "tanggal"                              => $tgl_diserahkan,
        "create_by_users"                      => $this->session->userdata("id"),
        "create_date"                          => date("Y-m-d H:i:s")
      );
    $dtid_out = $this->global_models->insert("mrp_stock_out", $kirim);
    
    $krm = array(
        "id_mrp_stock_out"                  => $dtid_out,
        "id_hr_pegawai"                     => $hr_pegawai[0]->id_hr_pegawai,
        "id_hr_master_organisasi"           => $hr_pegawai[0]->id_hr_master_organisasi,
        "id_hr_company"                     => $hr_pegawai[0]->id_hr_company,
        "id_mrp_inventory_spesifik"         => $mrp_stock[0]->id_mrp_inventory_spesifik,
        "id_mrp_satuan"                     => $mrp_stock[0]->id_mrp_satuan,
        "jumlah"                            => $dt_hasil,
        "harga"                             => $vl->harga,
        "status"                            => 1,
        "tanggal"                           => $tgl_diserahkan,
        "create_by_users"                   => $this->session->userdata("id"),
        "create_date"                       => date("Y-m-d H:i:s")
    );
    $this->global_models->insert("mrp_report", $krm);
    
    $jumlah_out = $jumlah_out - $dt_hasil;
    
    $this->session->set_flashdata('success', 'Data Tersimpan');
    }elseif($jumlah_out > 0){
        $jml = $jumlah_out;
        $total = $vl->jumlah_out + $jml;
        if($mrp_request[$ky][0]->jumlah >= $total){
            $dt = $total;
        }else{
            $dt = $mrp_request[$ky][0]->jumlah;
        }
        $kirim2 = array(
        "status"                        => 1,
        "jumlah_out"                    => $dt,
        "update_by_users"               => $this->session->userdata("id"),
        "update_date"                   => date("Y-m-d H:i:s")
    );
    $this->global_models->update("mrp_stock_in", array("id_mrp_stock_in" => $vl->id_mrp_stock_in),$kirim2);
    
    $this->olah_stock_out_code($kode_out);
    $kirim = array(
        "id_mrp_stock_in"                      => $vl->id_mrp_stock_in,
        "code"                                 => $kode_out,
        "id_mrp_stock"                         => $mrp_stock[0]->id_mrp_stock,                            
        "id_hr_pegawai"                        => $hr_pegawai[0]->id_hr_pegawai,
        "id_hr_master_organisasi"              => $hr_pegawai[0]->id_hr_master_organisasi,
        "id_hr_company"                        => $hr_pegawai[0]->id_hr_company,
        "id_mrp_inventory_spesifik"            => $mrp_stock[0]->id_mrp_inventory_spesifik,
        "id_mrp_satuan"                        => $mrp_stock[0]->id_mrp_satuan,
        "jumlah"                               => $jml,  
        "harga"                                => $vl->harga,
        "status"                               => 1,
        "tanggal"                              => $tgl_diserahkan,
        "create_by_users"                      => $this->session->userdata("id"),
        "create_date"                          => date("Y-m-d H:i:s")
      );
        $dtid_out = $this->global_models->insert("mrp_stock_out", $kirim);
    
    $krm = array(
        "id_mrp_stock_out"                  => $dtid_out,
        "id_hr_pegawai"                     => $hr_pegawai[0]->id_hr_pegawai,
        "id_hr_master_organisasi"           => $hr_pegawai[0]->id_hr_master_organisasi,
        "id_hr_company"                     => $hr_pegawai[0]->id_hr_company,
        "id_mrp_inventory_spesifik"         => $mrp_stock[0]->id_mrp_inventory_spesifik,
        "id_mrp_satuan"                     => $mrp_stock[0]->id_mrp_satuan,
        "jumlah"                            => $jml,
        "harga"                             => $vl->harga,
        "status"                            => 1,
        "tanggal"                           => $tgl_diserahkan,
        "create_by_users"                   => $this->session->userdata("id"),
        "create_date"                       => date("Y-m-d H:i:s")
    );
    $this->global_models->insert("mrp_report", $krm);
    
      $jumlah_out = $jumlah_out - $dt;
    }
      }    
    }else{
       $this->session->set_flashdata('notice', 'Data tidak tersimpan');
    }
  }
  
    if($flag > 0){
        $flag_req = 1;
    }else{
        $flag_req = 2;
    }
    
    $krm2 = array(
          "flag_request"                      => $flag_req,
          "tanggal_diserahkan"                => $tgl_diserahkan,
          "note_mutasi"                       => $note,
          "update_by_users"                   => $this->session->userdata("id"),
          "update_date"                       => date("Y-m-d H:i:s")
      );
  $this->global_models->update("mrp_request", array("id_mrp_request" => "{$id_mrp_request}"),$krm2);
  
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
    
 private function olah_stock_in_code(&$kode){
    $this->load->helper('string');
    $kode_random = random_string('alnum', 6);
    $st_upper = strtoupper($kode_random);
    $kode = "SIN".$st_upper;
    $cek = $this->global_models->get_field("mrp_stock_in", "id_mrp_stock_in", array("kode" => $kode));
    if($cek > 0){
      $this->olah_stock_in_code($kode);
    }
  }
  
  private function olah_history_request_mutasi_code(&$kode){
    $this->load->helper('string');
    $kode_random = random_string('alnum', 6);
    $st_upper = strtoupper($kode_random);
    $kode = "HRM".$st_upper;
    $cek = $this->global_models->get_field("history_request_mutasi", "id_history_request_mutasi", array("kode" => $kode));
    if($cek > 0){
      $this->olah_history_request_mutasi_code($kode);
    }
  }
  
    function detil_data_rekap($id_hr_master_organisasi) {
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
    
    $data = $this->global_models->get_query("SELECT B.id_mrp_inventory_spesifik,A.title,A.level,A.id_hr_master_organisasi,B.harga,B.id_hr_master_organisasi,SUM(jumlah) AS qty,SUM(jumlah* harga) AS price"
        . " ,D.id_mrp_type_inventory,D.name AS umum,C.title AS spesifik,F.title AS satuan,E.title AS brand,MONTH(B.tanggal) AS bulan"
        . " FROM hr_master_organisasi AS A"
        . " LEFT JOIN mrp_report AS B ON A.id_hr_master_organisasi = B.id_hr_master_organisasi"
        . " LEFT JOIN mrp_inventory_spesifik AS C ON B.id_mrp_inventory_spesifik = C.id_mrp_inventory_spesifik"
        . " LEFT JOIN mrp_inventory_umum AS D ON C.id_mrp_inventory_umum = D.id_mrp_inventory_umum"
        . " LEFT JOIN mrp_brand AS E ON C.id_mrp_brand = E.id_mrp_brand"
        . " LEFT JOIN mrp_satuan AS F ON C.id_mrp_satuan = F.id_mrp_satuan"    
        . " WHERE {$where}{$type} AND B.status=1"
        . " GROUP BY B.id_mrp_inventory_spesifik,B.harga"
        . " ORDER BY concat(D.name,C.title,B.harga) ASC"
        );  
        
         return $data;
  }
  
  function detil_data_report_po($id_mrp_po) {
   $where = "WHERE H.status = 7 AND A.id_mrp_po = '{$id_mrp_po}' ";

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
        );
        
         return $data;
  }
  
   function data_rekap(){
    
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
    
    $where = "WHERE 1=1 AND B.status=1";
    
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
        );
      
      return $data;
  }
  
  function data_report_po(){
    
     $where = "WHERE 1=1 AND A.status =7";
    
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
        
       $data = $this->global_models->get_query("SELECT A.id_mrp_po,A.id_mrp_supplier,A.tanggal_po,no_po,A.ppn,A.discount,"
        . "(SELECT SUM(L.jumlah) FROM mrp_po AS K"
        . " LEFT JOIN mrp_po_asset AS L ON K.id_mrp_po = L.id_mrp_po "
        . " WHERE K.id_mrp_po=A.id_mrp_po "
        . " GROUP BY K.id_mrp_po) AS qty "
        . ",(SELECT SUM(N.harga * N.jumlah) FROM mrp_po AS M "
        . " LEFT JOIN mrp_po_asset AS N ON M.id_mrp_po = N.id_mrp_po "
        . " WHERE M.id_mrp_po=A.id_mrp_po "
        . " GROUP BY M.id_mrp_po) AS price"
        . " ,B.id_mrp_task_orders,D.tanggal_diterima"
        . " FROM mrp_po AS A"
        . " LEFT JOIN mrp_po_asset AS B ON A.id_mrp_po = B.id_mrp_po"
        . " LEFT JOIN mrp_inventory_spesifik AS E ON B.id_mrp_inventory_spesifik = E.id_mrp_inventory_spesifik"
        . " LEFT JOIN mrp_inventory_umum AS F ON E.id_mrp_inventory_umum = F.id_mrp_inventory_umum"       
        . " LEFT JOIN mrp_receiving_goods_po AS C ON A.id_mrp_po = C.id_mrp_po"
        . " LEFT JOIN mrp_receiving_goods_department AS D ON C.id_mrp_receiving_goods_po = D.id_mrp_receiving_goods_po"
        . " {$where}"
        . " GROUP BY A.id_mrp_po"
        . " ORDER BY D.tanggal_diterima ASC"
        );
      
      return $data;
  }

  function export_xls($filename){
    $month = array(0 => "Pilih", 1 => "Januari", 2 => "Feb", 3 => "Maret", 4 => "April", 5 => "Mei", 6 => "Juni", 7 => "Juli", 8 => "Agustus", 9 => "September",
            10 => "Oktober", 11 => "November", 12 => "Desember");
    $start_month = $month[$this->session->userdata('report_dept_search_start_month')];
    $end_month =    $month[$this->session->userdata('report_dept_search_end_month')];
     $periode = "Periode {$start_month} - {$end_month} {$this->session->userdata('report_dept_search_year')}";
    $dropdown_type1 = array (0 => "ATK & Cetakan");
    $dropdown_type2 = $this->global_models->get_dropdown("mrp_type_inventory", "id_mrp_type_inventory", "code", FALSE);
    $dropdown_type = array_merge($dropdown_type1, $dropdown_type2);  
    $type = $dropdown_type[$this->session->userdata("report_dept_search_type")];
     $dropdown_company = $this->global_models->get_dropdown("hr_company", "id_hr_company", "title", TRUE);
     $company = $dropdown_company[$this->session->userdata('report_dept_search_id_company')];  
     $merger_comp_type= $type." ".$company;
//        print "aa"; die;
//    print_r($where_information); die;
      $objPHPExcel = $this->phpexcel;
      $objPHPExcel->getProperties()->setCreator("AntaVaya")
							 ->setLastModifiedBy("AntaVaya")
							 ->setTitle("Data Supplier Inventory")
							 ->setSubject("Data Supplier Inventory")
							 ->setDescription("Supplier Inventory")
							 ->setKeywords("Supplier Inventory")
							 ->setCategory("Supplier Inventory");

      $objPHPExcel->setActiveSheetIndex(0);
      $objPHPExcel->getActiveSheet()->mergeCells('A1:D1');
      $objPHPExcel->getActiveSheet()->setCellValue('B2', 'Rekapan Antavaya');
      $objPHPExcel->getActiveSheet()->getStyle('A1:D2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
      $objPHPExcel->getActiveSheet()->getStyle('A1:D2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
      $objPHPExcel->getActiveSheet()->getStyle('A1:D2')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
      
      $objPHPExcel->getActiveSheet()->setCellValue('B3', $merger_comp_type);      
      $objPHPExcel->getActiveSheet()->getStyle('A3:D3')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
      $objPHPExcel->getActiveSheet()->getStyle('A3:D3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
      $objPHPExcel->getActiveSheet()->getStyle('A3:D3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
      
      $objPHPExcel->getActiveSheet()->setCellValue('B4', $periode);
      $objPHPExcel->getActiveSheet()->getStyle('A4:D4')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
      $objPHPExcel->getActiveSheet()->getStyle('A4:D4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
      $objPHPExcel->getActiveSheet()->getStyle('A4:D4')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
//      $objPHPExcel->getActiveSheet()->getStyle('A1:V2')->getFill()->getStartColor()->setARGB('FF808080');
//      $objPHPExcel->getActiveSheet()->getStyle('A1:D3')->applyFromArray(
//          array(
//            'font'    => array(
//              'bold'      => true,
//               'size'  => 14,
//              'name'  => 'Verdana'
//              
//            ),
//            'fill' => array(
//              'type'       => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
//                'rotation'   => 90,
//              'startcolor' => array(
//                'argb' => 'FFA0A0A0'
//              ),
//              'endcolor'   => array(
//                'argb' => 'FFFFFFFF'
//              )
//            )
//          )
//      );
      $objPHPExcel->getActiveSheet()->setCellValue('A5', 'NO');
      $objPHPExcel->getActiveSheet()->getStyle("A5")->applyFromArray(
          array(
            'alignment' => array(
              'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
              'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            ),
            'borders' => array(
              'left'     => array(
                'style' => PHPExcel_Style_Border::BORDER_DOUBLE
              ),
              'right'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
              'top'     => array(
                'style' => PHPExcel_Style_Border::BORDER_DOUBLE
              ),
              'bottom'     => array(
                'style' => PHPExcel_Style_Border::BORDER_DOUBLE
              ),
            ),
          )
      );
      $objPHPExcel->getActiveSheet()->setCellValue('B5', 'Dept/Cabang');
      $objPHPExcel->getActiveSheet()->getStyle("B5")->applyFromArray(
          array(
            'alignment' => array(
              'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
              'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            ),
            'borders' => array(
              'left'     => array(
                'style' => PHPExcel_Style_Border::BORDER_DOUBLE
              ),
              'right'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
              'top'     => array(
                'style' => PHPExcel_Style_Border::BORDER_DOUBLE
              ),
              'bottom'     => array(
                'style' => PHPExcel_Style_Border::BORDER_DOUBLE
              ),
            ),
          )
      );
      $objPHPExcel->getActiveSheet()->setCellValue('C5', 'Qty');
      $objPHPExcel->getActiveSheet()->getStyle("C5")->applyFromArray(
          array(
            'alignment' => array(
              'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
              'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            ),
            'borders' => array(
              'left'     => array(
                'style' => PHPExcel_Style_Border::BORDER_DOUBLE
              ),
              'right'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
              'top'     => array(
                'style' => PHPExcel_Style_Border::BORDER_DOUBLE
              ),
              'bottom'     => array(
                'style' => PHPExcel_Style_Border::BORDER_DOUBLE
              ),
            ),
          )
      );
      $objPHPExcel->getActiveSheet()->setCellValue('D5', 'Jumlah');
      $objPHPExcel->getActiveSheet()->getStyle("D5")->applyFromArray(
          array(
            'alignment' => array(
              'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
              'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            ),
            'borders' => array(
              'left'     => array(
                'style' => PHPExcel_Style_Border::BORDER_DOUBLE
              ),
              'right'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
              'top'     => array(
                'style' => PHPExcel_Style_Border::BORDER_DOUBLE
              ),
              'bottom'     => array(
                'style' => PHPExcel_Style_Border::BORDER_DOUBLE
              ),
            ),
          )
      );
      
      $objPHPExcel->getActiveSheet()->getStyle('A5:D5')->applyFromArray(
          array(
            'font'    => array(
              'bold'      => true
            ),
            'alignment' => array(
              'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
            ),
            'borders' => array(
              'top'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
              'bottom'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
              'left'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
              'right'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
            ),
            'fill' => array(
              'type'       => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
                'rotation'   => 90,
              'startcolor' => array(
                'argb' => 'FFA0A0A0'
              ),
              'endcolor'   => array(
                'argb' => 'FFFFFFFF'
              )
            )
          )
      );
      
      $data = $this->data_rekap();
        $lvl = array(1 => "Direktorat",2 => "Divisi",3 => "Department",4 => "Section");
        $no = $qty = $jumlah = 0;
        $angka = 6;
        
        foreach ($data as $key => $da) {
//          foreach($value['information'] AS $ky => $info){
            $no = $no + 1;
           
        $qty = $qty + $da->qty;
        $jumlah = $jumlah + $da->price;
        
            $objPHPExcel->getActiveSheet()->setCellValue('A'.(6+$key),$no);
            $objPHPExcel->getActiveSheet()->getStyle("A".(6+$key))->applyFromArray(
            array(
                'alignment' => array(
                  'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP,
                ),
                'borders' => array(
                  'top'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                  ),
                  'right'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                  ),
                  'left'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_DOUBLE
                  ),
                ),
              )
          );
            $objPHPExcel->getActiveSheet()->setCellValue('B'.(6+$key),$da->title."<".$lvl[$da->level].">");
             $objPHPExcel->getActiveSheet()->getStyle("B".(6+$key))->applyFromArray(
            array(
                'alignment' => array(
                  'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP,
                ),
                'borders' => array(
                  'top'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                  ),
                  'right'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                  ),
                  'left'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_DOUBLE
                  ),
                ),
              )
          );
            $objPHPExcel->getActiveSheet()->setCellValue('C'.(6+$key),$da->qty);
             $objPHPExcel->getActiveSheet()->getStyle("C".(6+$key))->applyFromArray(
            array(
                'alignment' => array(
                  'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP,
                ),
                'borders' => array(
                  'top'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                  ),
                  'right'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                  ),
                  'left'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_DOUBLE
                  ),
                ),
              )
          );
            $objPHPExcel->getActiveSheet()->setCellValue('D'.(6+$key),$da->price);
            $objPHPExcel->getActiveSheet()->getStyle('D'.(6+$key))->getNumberFormat()->setFormatCode('#,##0');
             $objPHPExcel->getActiveSheet()->getStyle("D".(6+$key))->applyFromArray(
            array(
                'alignment' => array(
                  'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP,
                ),
                'borders' => array(
                  'top'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                  ),
                  'right'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                  ),
                  'left'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_DOUBLE
                  ),
                ),
              )
          );
        
            $angka++;
        }
        
         $objPHPExcel->getActiveSheet()->getStyle("A".($angka))->applyFromArray(
            array(
                'alignment' => array(
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                ),
                'borders' => array(
                  'top'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                  ),
                  'right'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                  ),
                  'left'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_DOUBLE
                  ),
                    'bottom'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                  ),  
                ),
              )
          );
         
      $objPHPExcel->getActiveSheet()->setCellValue('B'.($angka),"Jumlah");
      $objPHPExcel->getActiveSheet()->getStyle("B".($angka))->applyFromArray(
            array(
                'alignment' => array(
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                ),
                'borders' => array(
                  'top'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                  ),
                  'right'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                  ),
                  'left'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_DOUBLE
                  ),
                    'bottom'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                  ),  
                ),
              )
          );
      $objPHPExcel->getActiveSheet()->setCellValue('C'.($angka), $qty);
      $objPHPExcel->getActiveSheet()->getStyle("C".($angka))->applyFromArray(
            array(
                'alignment' => array(
                  'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP,
                ),
                'borders' => array(
                  'top'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                  ),
                  'right'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                  ),
                  'left'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_DOUBLE
                  ),
                    'bottom'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                  ),  
                ),
              )
          );
      $objPHPExcel->getActiveSheet()->setCellValue('D'.($angka), $jumlah);
      $objPHPExcel->getActiveSheet()->getStyle('D'.($angka))->getNumberFormat()->setFormatCode('#,##0');
      $objPHPExcel->getActiveSheet()->getStyle("D".($angka))->applyFromArray(
            array(
                'alignment' => array(
                  'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP,
                ),
                'borders' => array(
                  'top'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                  ),
                  'right'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                  ),
                  'left'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_DOUBLE
                  ),
                  'bottom'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                  ),  
                ),
              )
          );
      $bod = "A".$angka.":D".$angka;
      $objPHPExcel->getActiveSheet()->getStyle($bod)->applyFromArray(
          array(
            'font'    => array(
              'bold'      => true
            ),
            'alignment' => array(
              'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
            ),
            'borders' => array(
              'top'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
              'bottom'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
              'left'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
              'right'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
            ),
            'fill' => array(
              'type'       => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
                'rotation'   => 90,
              'startcolor' => array(
                'argb' => 'FFA0A0A0'
              ),
              'endcolor'   => array(
                'argb' => 'FFFFFFFF'
              )
            )
          )
      );
      
      $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
      $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
      $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
      $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
       $objPHPExcel->getActiveSheet()->freezePane('A5');
      $objPHPExcel->getActiveSheet()->setTitle('Rekapan');
      
       $data2 = $this->data_rekap();
      $zz = 1;
      foreach ($data2 as $ky => $val) {
          
      $objPHPExcel->createSheet();
      $title_org =$this->global_models->get_field("hr_master_organisasi","code", array("id_hr_master_organisasi" => "{$val->id_hr_master_organisasi}"));
       
      $objPHPExcel->setActiveSheetIndex($zz);
      
      $objPHPExcel->getActiveSheet()->mergeCells('A1:D1');
      $objPHPExcel->getActiveSheet()->setCellValue('B2', $merger_comp_type);
      $objPHPExcel->getActiveSheet()->getStyle('A1:D2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
      $objPHPExcel->getActiveSheet()->getStyle('A1:D2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
      $objPHPExcel->getActiveSheet()->getStyle('A1:D2')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
      
      $objPHPExcel->getActiveSheet()->setCellValue('B3', $periode);      
      $objPHPExcel->getActiveSheet()->getStyle('A3:D3')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
      $objPHPExcel->getActiveSheet()->getStyle('A3:D3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
      $objPHPExcel->getActiveSheet()->getStyle('A3:D3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
      
      $objPHPExcel->getActiveSheet()->mergeCells('A4:A5');
      $objPHPExcel->getActiveSheet()->setCellValue('A4', 'NO');
      $objPHPExcel->getActiveSheet()->getStyle("A4:A5")->applyFromArray(
          array(
            'alignment' => array(
              'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
              'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            ),
            'borders' => array(
              'left'     => array(
                'style' => PHPExcel_Style_Border::BORDER_DOUBLE
              ),
              'right'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
              'top'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
              'bottom'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
            ),
          )
      );
      
      $objPHPExcel->getActiveSheet()->mergeCells('B4:B5');
      $objPHPExcel->getActiveSheet()->setCellValue('B4', 'Nama Barang');
      $objPHPExcel->getActiveSheet()->getStyle("B4:B5")->applyFromArray(
          array(
            'alignment' => array(
              'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
              'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            ),
            'borders' => array(
              'left'     => array(
                'style' => PHPExcel_Style_Border::BORDER_DOUBLE
              ),
              'right'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
              'top'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
              'bottom'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
            ),
          )
      );
      
       $objPHPExcel->getActiveSheet()->mergeCells('C4:C5');
      $objPHPExcel->getActiveSheet()->setCellValue('C4', 'Jenis/Merk');
      $objPHPExcel->getActiveSheet()->getStyle("C4:C5")->applyFromArray(
          array(
            'alignment' => array(
              'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
              'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            ),
            'borders' => array(
              'left'     => array(
                'style' => PHPExcel_Style_Border::BORDER_DOUBLE
              ),
              'right'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
              'top'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
              'bottom'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
            ),
          )
      );
      
      $objPHPExcel->getActiveSheet()->mergeCells('D4:D5');
      $objPHPExcel->getActiveSheet()->setCellValue('D4', 'QTY');
      $objPHPExcel->getActiveSheet()->getStyle("D4:D5")->applyFromArray(
          array(
            'alignment' => array(
              'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
              'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            ),
            'borders' => array(
              'left'     => array(
                'style' => PHPExcel_Style_Border::BORDER_DOUBLE
              ),
              'right'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
              'top'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
              'bottom'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
            ),
          )
      );
      
      $objPHPExcel->getActiveSheet()->mergeCells('E4:E5');
      $objPHPExcel->getActiveSheet()->setCellValue('E4', 'Harga');
      $objPHPExcel->getActiveSheet()->getStyle("E4:E5")->applyFromArray(
          array(
            'alignment' => array(
              'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
              'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            ),
            'borders' => array(
              'left'     => array(
                'style' => PHPExcel_Style_Border::BORDER_DOUBLE
              ),
              'right'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
              'top'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
              'bottom'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
            ),
          )
      );
      
      $huruf = array(1 => "F", 2 => "G", 3 => "H", 4 => "I", 5 => "J", 6 => "K", 7 => "L", 8 => "M", 9 => "N",
            10 => "O", 11 => "P", 12 => "Q", 13 => "R", 14 => "S");
      $j = 0;
      $mth = array(1 => "Jan", 2 => "Feb", 3 => "Mar", 4 => "Apr", 5 => "Mei", 6 => "Jun", 7 => "Jul", 8 => "Agu", 9 => "Sep",
            10 => "Okt", 11 => "Nov", 12 => "Des");
      
      for ($a=$this->session->userdata("report_dept_search_start_month"); $a <= $this->session->userdata("report_dept_search_end_month"); $a++) {
       $j++;
      $jj = $huruf[$j]."5";
 
      $objPHPExcel->getActiveSheet()->setCellValue("{$jj}", $mth[$a]);
      $objPHPExcel->getActiveSheet()->getStyle("{$jj}")->applyFromArray(
          array(
            'alignment' => array(
              'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
              'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            ),
            'borders' => array(
              'left'     => array(
                'style' => PHPExcel_Style_Border::BORDER_DOUBLE
              ),
              'right'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
              'top'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
              'bottom'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
            ),
          )
      );
      
      }
      $FF = "F4:".$huruf[$j]."4";
      $objPHPExcel->getActiveSheet()->mergeCells($FF);
      $objPHPExcel->getActiveSheet()->setCellValue("F4", $title_org);
      $objPHPExcel->getActiveSheet()->getStyle($FF)->applyFromArray(
          array(
            'alignment' => array(
              'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
              'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            ),
            'borders' => array(
              'left'     => array(
                'style' => PHPExcel_Style_Border::BORDER_DOUBLE
              ),
              'right'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
              'top'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
              'bottom'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
            ),
          )
      );
      
      $GG = $j+1;
      $HH = $j+2;
      $II = $huruf[$GG]."4:".$huruf[$HH]."4";
      $a_II = $huruf[$GG]."4";
       $objPHPExcel->getActiveSheet()->mergeCells($II);
      $objPHPExcel->getActiveSheet()->setCellValue($a_II, "Jumlah");
      $objPHPExcel->getActiveSheet()->getStyle($II)->applyFromArray(
          array(
            'alignment' => array(
              'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
              'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            ),
            'borders' => array(
              'left'     => array(
                'style' => PHPExcel_Style_Border::BORDER_DOUBLE
              ),
              'right'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
              'top'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
              'bottom'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
            ),
          )
      );
      
      $KK = $huruf[$GG]."5";
      $objPHPExcel->getActiveSheet()->setCellValue($KK, "Unit");
      $objPHPExcel->getActiveSheet()->getStyle($KK)->applyFromArray(
          array(
            'alignment' => array(
              'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
              'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            ),
            'borders' => array(
              'left'     => array(
                'style' => PHPExcel_Style_Border::BORDER_DOUBLE
              ),
              'right'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
              'top'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
              'bottom'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
            ),
          )
      );
       
     $LL = $huruf[$HH]."5";
      $objPHPExcel->getActiveSheet()->setCellValue($LL, "Rupiah");
      $objPHPExcel->getActiveSheet()->getStyle($LL)->applyFromArray(
          array(
            'alignment' => array(
              'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
              'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            ),
            'borders' => array(
              'left'     => array(
                'style' => PHPExcel_Style_Border::BORDER_DOUBLE
              ),
              'right'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
              'top'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
              'bottom'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
            ),
          )
      );
      
      $kal = "A4:".$huruf[$HH]."5";
      $objPHPExcel->getActiveSheet()->getStyle($kal)->applyFromArray(
          array(
            'font'    => array(
              'bold'      => true
            ),
            'alignment' => array(
              'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
            ),
            'borders' => array(
              'top'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
              'bottom'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
              'left'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
              'right'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
            ),
            'fill' => array(
              'type'       => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
                'rotation'   => 90,
              'startcolor' => array(
                'argb' => 'FFA0A0A0'
              ),
              'endcolor'   => array(
                'argb' => 'FFFFFFFF'
              )
            )
          )
      );
      
       $detail_rekap = $this->detil_data_rekap($val->id_hr_master_organisasi);
//       $asd = $this->db->last_query();
          $nom = 0;
          $dropdown_type2 = $this->global_models->get_dropdown("mrp_type_inventory", "id_mrp_type_inventory", "code", FALSE);
          foreach ($detail_rekap as $key => $da) {
              $nom = $nom + 1;
         //         if($da->id_mrp_type_inventory){
//            $type2 = " <Type:".$dropdown_type2[$da->id_mrp_type_inventory].">"; 
//         }
//              $umum =  $da->umum." ".$da->spesifik.$type2;
              $umum =  $da->umum." ".$da->spesifik;
              $objPHPExcel->getActiveSheet()->setCellValue('A'.(6+$key),$nom);
               $objPHPExcel->getActiveSheet()->getStyle('A'.(6+$key))->applyFromArray(
            array(
                'alignment' => array(
                  'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP,
                ),
                'borders' => array(
                  'top'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                  ),
                  'right'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                  ),
                  'left'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_DOUBLE
                  ),
                  'bottom'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                  ),  
                ),
              )
          );
              $objPHPExcel->getActiveSheet()->setCellValue('B'.(6+$key),$umum);
              $objPHPExcel->getActiveSheet()->getStyle('B'.(6+$key))->applyFromArray(
            array(
                'alignment' => array(
                  'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP,
                ),
                'borders' => array(
                  'top'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                  ),
                  'right'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                  ),
                  'left'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_DOUBLE
                  ),
                  'bottom'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                  ),  
                ),
              )
          );
              $objPHPExcel->getActiveSheet()->setCellValue('C'.(6+$key),$da->brand);
              $objPHPExcel->getActiveSheet()->getStyle('C'.(6+$key))->applyFromArray(
            array(
                'alignment' => array(
                  'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP,
                ),
                'borders' => array(
                  'top'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                  ),
                  'right'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                  ),
                  'left'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_DOUBLE
                  ),
                  'bottom'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                  ),  
                ),
              )
          );
              $objPHPExcel->getActiveSheet()->setCellValue('D'.(6+$key),$da->satuan);
              $objPHPExcel->getActiveSheet()->getStyle('D'.(6+$key))->applyFromArray(
            array(
                'alignment' => array(
                  'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP,
                ),
                'borders' => array(
                  'top'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                  ),
                  'right'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                  ),
                  'left'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_DOUBLE
                  ),
                  'bottom'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                  ),  
                ),
              )
          );
              $objPHPExcel->getActiveSheet()->setCellValue('E'.(6+$key),$da->harga);
              $objPHPExcel->getActiveSheet()->getStyle('E'.(6+$key))->applyFromArray(
            array(
                'alignment' => array(
                  'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP,
                ),
                'borders' => array(
                  'top'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                  ),
                  'right'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                  ),
                  'left'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_DOUBLE
                  ),
                  'bottom'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                  ),  
                ),
              )
          );
              $objPHPExcel->getActiveSheet()->getStyle('E'.(6+$key))->getNumberFormat()->setFormatCode('#,##0');
//              $objPHPExcel->getActiveSheet()->setCellValue('E'.(6+$key),$da->harga);
     
    $where = " B.id_hr_company ='{$this->session->userdata("report_dept_search_id_company")}' AND B.id_hr_master_organisasi ='{$val->id_hr_master_organisasi}'";
     if($this->session->userdata('report_dept_search_year') AND $this->session->userdata('report_dept_search_start_month')){
        $where .= " AND tanggal >= '{$this->session->userdata('report_dept_search_year')}-{$this->session->userdata('report_dept_search_start_month')}-1 + INTERVAL 1 MONTH'";
    }
    
    if($this->session->userdata('report_dept_search_year') AND $this->session->userdata('report_dept_search_end_month')){
        $where .= " AND tanggal <= '{$this->session->userdata('report_dept_search_year')}-{$this->session->userdata('report_dept_search_end_month')}-31'";
    }
       $dt_qt[$ky] = $this->global_models->get_query("SELECT SUM(jumlah) AS qty"
        . " ,MONTH(B.tanggal) AS bulan"
        . " FROM mrp_report AS B"
        . " WHERE B.id_mrp_inventory_spesifik ='{$da->id_mrp_inventory_spesifik}' AND {$where}"
        . " GROUP BY B.id_hr_master_organisasi,YEAR(B.tanggal), MONTH(B.tanggal)"  
        );
//        $ww = $this->db->last_query();
        $huruf = array(1 => "F", 2 => "G", 3 => "H", 4 => "I", 5 => "J", 6 => "K", 7 => "L", 8 => "M", 9 => "N",
            10 => "O", 11 => "P", 12 => "Q", 13 => "R", 14 => "S");
           
         foreach($dt_qt[$ky] as $cek) {
              $ccss[$ky][$key][$cek->bulan] = $cek->qty;
         }
            
                $ttl_qty = 0;
                $ttl_hrg = 0;
                $t_harga = 0;
               
                   $x = 0;
                
        for ($a = $this->session->userdata("report_dept_search_start_month"); $a <= $this->session->userdata("report_dept_search_end_month"); $a++) {
         $cc[$ky][$key][$a] = 0;
         $x++;
         $kx = $huruf[$x].(6+$key);
         $kx2[$zz] = $huruf[$x].(7+$key);
//         $total_cs[$a] = "";
         foreach($dt_qt[$ky] as $fkg => $cek) {

             if($cek->bulan == $a){
             $ttl_qty = $ttl_qty + $cek->qty;
             $total_cs[$a][$zz] = $total_cs[$a][$zz] + $cek->qty;
             
             }
         }
          $objPHPExcel->getActiveSheet()->setCellValue($kx2[$zz],$total_cs[$a][$zz]);
    }
        $kcst[$ky][$key] = array();      
        $kcst[$ky][$key] = (array_replace($cc[$ky][$key],$ccss[$ky][$key]));     
         $l = 0;
        foreach ($kcst[$ky][$key] as $ks => $vm) {
             $l++;
             $kxl = $huruf[$l].(6+$key);
             $kxl2 = $huruf[$l].(7+$key);
             if($vm){
                  $gr[$ky][$key][$ks] = $vm;
                  $gr2[$key] = $gr2[$key] + $vm;
             }else{
                 $gr[$ky][$key][$ks] = "";
             }
            
            $objPHPExcel->getActiveSheet()->setCellValue($kxl,$gr[$ky][$key][$ks]);
            $objPHPExcel->getActiveSheet()->getStyle($kxl)->applyFromArray(
            array(
                'alignment' => array(
                  'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP,
                ),
                'borders' => array(
                  'top'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                  ),
                  'right'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                  ),
                  'left'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_DOUBLE
                  ),
                  'bottom'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                  ),  
                ),
              )
          );
            
        }
            
            $GG = $x+1;
            $a_GG = $huruf[$GG].(6+$key);
            $objPHPExcel->getActiveSheet()->setCellValue($a_GG,$ttl_qty);
            $objPHPExcel->getActiveSheet()->getStyle($a_GG)->applyFromArray(
            array(
                'alignment' => array(
                  'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP,
                ),
                'borders' => array(
                  'top'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                  ),
                  'right'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                  ),
                  'left'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_DOUBLE
                  ),
                  'bottom'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                  ),  
                ),
              )
          );
            $HH = $x+2;
            $a_HH = $huruf[$HH].(6+$key);
            $t_harga = $ttl_qty * $da->harga;
            $objPHPExcel->getActiveSheet()->setCellValue($a_HH,$t_harga);
            $objPHPExcel->getActiveSheet()->getStyle($a_HH)->applyFromArray(
            array(
                'alignment' => array(
                  'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP,
                ),
                'borders' => array(
                  'top'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                  ),
                  'right'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                  ),
                  'left'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_DOUBLE
                  ),
                  'bottom'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                  ),  
                ),
              )
          );
             $objPHPExcel->getActiveSheet()->getStyle($a_HH)->getNumberFormat()->setFormatCode('#,##0');
            
            $jml_qty[$zz]        = $jml_qty[$zz]    + $ttl_qty;
            $jml_total2[$zz]     = $jml_total2[$zz] + $t_harga;
          }
          $key2 = $key + 7;
          $HH   = $x+1;
          $a_HH = $huruf[$HH].($key2);
          
        $objPHPExcel->getActiveSheet()->setCellValue($a_HH, $jml_qty[$zz]);
         $objPHPExcel->getActiveSheet()->getStyle($a_HH)->applyFromArray(
            array(
                'alignment' => array(
                  'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP,
                ),
                'borders' => array(
                  'top'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                  ),
                  'right'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                  ),
                  'left'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_DOUBLE
                  ),
                  'bottom'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                  ),  
                ),
              )
          );
      
          
          $II   = $x+2;
          $b_HH = $huruf[$II].($key2);
          
      $objPHPExcel->getActiveSheet()->setCellValue($b_HH, $jml_total2[$zz]);
       $objPHPExcel->getActiveSheet()->getStyle($b_HH)->applyFromArray(
            array(
                'alignment' => array(
                  'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP,
                ),
                'borders' => array(
                  'top'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                  ),
                  'right'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                  ),
                  'left'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_DOUBLE
                  ),
                  'bottom'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                  ),  
                ),
              )
          );
       $objPHPExcel->getActiveSheet()->getStyle($b_HH)->getNumberFormat()->setFormatCode('#,##0');
      
      $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(4);
      $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(70);
      $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
      $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
      $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
      $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
      
      $objPHPExcel->getActiveSheet()->setTitle($title_org);
      
         $zz++;   
      }
      
      
      
      $objPHPExcel->setActiveSheetIndex(0);
      header('Content-Type: application/vnd.ms-excel');
      header('Content-Disposition: attachment;filename="'.$filename."-".date("Y-m-d").'.xls"');
      header('Cache-Control: max-age=0');
      $objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel5');
//$objWriter->save(str_replace('.php', '.xls', __FILE__));
      $objWriter->save('php://output');die;
}
  
function export_report_po_xls($filename){
    $month = array(0 => "Pilih", 1 => "Januari", 2 => "Feb", 3 => "Maret", 4 => "April", 5 => "Mei", 6 => "Juni", 7 => "Juli", 8 => "Agustus", 9 => "September",
            10 => "Oktober", 11 => "November", 12 => "Desember");
    $start_month = $month[$this->session->userdata('report_po_search_start_month')];
    $end_month =    $month[$this->session->userdata('report_po_search_end_month')];
     $periode = "Periode {$start_month} - {$end_month} {$this->session->userdata('report_po_search_year')}";
//    $dropdown_type1 = array (0 => "ATK & Cetakan");
//    $dropdown_type2 = $this->global_models->get_dropdown("mrp_type_inventory", "id_mrp_type_inventory", "code", FALSE);
//    $dropdown_type = array_merge($dropdown_type1, $dropdown_type2);  
//    $type = $dropdown_type[$this->session->userdata("report_dept_search_type")];
//     $dropdown_company = $this->global_models->get_dropdown("hr_company", "id_hr_company", "title", TRUE);
//     $company = $dropdown_company[$this->session->userdata('report_po_search_id_company')];  
//     $merger_comp_type= $type." ".$company;
//        print "aa"; die;
//    print_r($where_information); die;
      $objPHPExcel = $this->phpexcel;
      $objPHPExcel->getProperties()->setCreator("AntaVaya")
							 ->setLastModifiedBy("AntaVaya")
							 ->setTitle("Data REPORT PO")
							 ->setSubject("Data REPORT PO")
							 ->setDescription("REPORT PO")
							 ->setKeywords("REPORT PO")
							 ->setCategory("REPORT PO");

      $objPHPExcel->setActiveSheetIndex(0);
      $objPHPExcel->getActiveSheet()->mergeCells('A1:I1');
      $objPHPExcel->getActiveSheet()->setCellValue('B2', 'REPORT PO Antavaya');
      $objPHPExcel->getActiveSheet()->getStyle('A1:I2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
      $objPHPExcel->getActiveSheet()->getStyle('A1:I2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
      $objPHPExcel->getActiveSheet()->getStyle('A1:I2')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
      
//      $objPHPExcel->getActiveSheet()->setCellValue('B3', $merger_comp_type);      
//      $objPHPExcel->getActiveSheet()->getStyle('A3:D3')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
//      $objPHPExcel->getActiveSheet()->getStyle('A3:D3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
//      $objPHPExcel->getActiveSheet()->getStyle('A3:D3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
      
      $objPHPExcel->getActiveSheet()->setCellValue('B4', $periode);
      $objPHPExcel->getActiveSheet()->getStyle('A4:I4')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
      $objPHPExcel->getActiveSheet()->getStyle('A4:I4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
      $objPHPExcel->getActiveSheet()->getStyle('A4:I4')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
//      $objPHPExcel->getActiveSheet()->getStyle('A1:V2')->getFill()->getStartColor()->setARGB('FF808080');
//      $objPHPExcel->getActiveSheet()->getStyle('A1:D3')->applyFromArray(
//          array(
//            'font'    => array(
//              'bold'      => true,
//               'size'  => 14,
//              'name'  => 'Verdana'
//              
//            ),
//            'fill' => array(
//              'type'       => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
//                'rotation'   => 90,
//              'startcolor' => array(
//                'argb' => 'FFA0A0A0'
//              ),
//              'endcolor'   => array(
//                'argb' => 'FFFFFFFF'
//              )
//            )
//          )
//      );
      $objPHPExcel->getActiveSheet()->setCellValue('A5', 'NO');
      $objPHPExcel->getActiveSheet()->getStyle("A5")->applyFromArray(
          array(
            'alignment' => array(
              'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
              'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            ),
            'borders' => array(
              'left'     => array(
                'style' => PHPExcel_Style_Border::BORDER_DOUBLE
              ),
              'right'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
              'top'     => array(
                'style' => PHPExcel_Style_Border::BORDER_DOUBLE
              ),
              'bottom'     => array(
                'style' => PHPExcel_Style_Border::BORDER_DOUBLE
              ),
            ),
          )
      );
      $objPHPExcel->getActiveSheet()->setCellValue('B5', 'Tanggal');
      $objPHPExcel->getActiveSheet()->getStyle("B5")->applyFromArray(
          array(
            'alignment' => array(
              'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
              'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            ),
            'borders' => array(
              'left'     => array(
                'style' => PHPExcel_Style_Border::BORDER_DOUBLE
              ),
              'right'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
              'top'     => array(
                'style' => PHPExcel_Style_Border::BORDER_DOUBLE
              ),
              'bottom'     => array(
                'style' => PHPExcel_Style_Border::BORDER_DOUBLE
              ),
            ),
          )
      );
      $objPHPExcel->getActiveSheet()->setCellValue('C5', 'No PO');
      $objPHPExcel->getActiveSheet()->getStyle("C5")->applyFromArray(
          array(
            'alignment' => array(
              'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
              'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            ),
            'borders' => array(
              'left'     => array(
                'style' => PHPExcel_Style_Border::BORDER_DOUBLE
              ),
              'right'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
              'top'     => array(
                'style' => PHPExcel_Style_Border::BORDER_DOUBLE
              ),
              'bottom'     => array(
                'style' => PHPExcel_Style_Border::BORDER_DOUBLE
              ),
            ),
          )
      );
      $objPHPExcel->getActiveSheet()->setCellValue('D5', 'QTY');
      $objPHPExcel->getActiveSheet()->getStyle("D5")->applyFromArray(
          array(
            'alignment' => array(
              'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
              'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            ),
            'borders' => array(
              'left'     => array(
                'style' => PHPExcel_Style_Border::BORDER_DOUBLE
              ),
              'right'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
              'top'     => array(
                'style' => PHPExcel_Style_Border::BORDER_DOUBLE
              ),
              'bottom'     => array(
                'style' => PHPExcel_Style_Border::BORDER_DOUBLE
              ),
            ),
          )
      );
      
      $objPHPExcel->getActiveSheet()->setCellValue('E5', 'Total Harga');
      $objPHPExcel->getActiveSheet()->getStyle("E5")->applyFromArray(
          array(
            'alignment' => array(
              'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
              'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            ),
            'borders' => array(
              'left'     => array(
                'style' => PHPExcel_Style_Border::BORDER_DOUBLE
              ),
              'right'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
              'top'     => array(
                'style' => PHPExcel_Style_Border::BORDER_DOUBLE
              ),
              'bottom'     => array(
                'style' => PHPExcel_Style_Border::BORDER_DOUBLE
              ),
            ),
          )
      );
      
      $objPHPExcel->getActiveSheet()->setCellValue('F5', 'Vendor');
      $objPHPExcel->getActiveSheet()->getStyle("F5")->applyFromArray(
          array(
            'alignment' => array(
              'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
              'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            ),
            'borders' => array(
              'left'     => array(
                'style' => PHPExcel_Style_Border::BORDER_DOUBLE
              ),
              'right'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
              'top'     => array(
                'style' => PHPExcel_Style_Border::BORDER_DOUBLE
              ),
              'bottom'     => array(
                'style' => PHPExcel_Style_Border::BORDER_DOUBLE
              ),
            ),
          )
      );
      
      $objPHPExcel->getActiveSheet()->setCellValue('G5', 'Beban');
      $objPHPExcel->getActiveSheet()->getStyle("G5")->applyFromArray(
          array(
            'alignment' => array(
              'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
              'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            ),
            'borders' => array(
              'left'     => array(
                'style' => PHPExcel_Style_Border::BORDER_DOUBLE
              ),
              'right'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
              'top'     => array(
                'style' => PHPExcel_Style_Border::BORDER_DOUBLE
              ),
              'bottom'     => array(
                'style' => PHPExcel_Style_Border::BORDER_DOUBLE
              ),
            ),
          )
      );
      
      $objPHPExcel->getActiveSheet()->setCellValue('H5', 'Surat Jalan');
      $objPHPExcel->getActiveSheet()->getStyle("H5")->applyFromArray(
          array(
            'alignment' => array(
              'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
              'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            ),
            'borders' => array(
              'left'     => array(
                'style' => PHPExcel_Style_Border::BORDER_DOUBLE
              ),
              'right'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
              'top'     => array(
                'style' => PHPExcel_Style_Border::BORDER_DOUBLE
              ),
              'bottom'     => array(
                'style' => PHPExcel_Style_Border::BORDER_DOUBLE
              ),
            ),
          )
      );
      
      $objPHPExcel->getActiveSheet()->setCellValue('I5', 'Lama');
      $objPHPExcel->getActiveSheet()->getStyle("I5")->applyFromArray(
          array(
            'alignment' => array(
              'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
              'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            ),
            'borders' => array(
              'left'     => array(
                'style' => PHPExcel_Style_Border::BORDER_DOUBLE
              ),
              'right'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
              'top'     => array(
                'style' => PHPExcel_Style_Border::BORDER_DOUBLE
              ),
              'bottom'     => array(
                'style' => PHPExcel_Style_Border::BORDER_DOUBLE
              ),
            ),
          )
      );
      
      $data = $this->data_report_po();
//        $lvl = array(1 => "Direktorat",2 => "Divisi",3 => "Department",4 => "Section");
        $no = $qty = $jumlah = 0;
        $angka = 6;
        
        foreach ($data as $key => $da) {
//          foreach($value['information'] AS $ky => $info){
            $no = $no + 1;
           
       $dt_beban =  $this->global_models->get_query(" SELECT G.title AS master_organisasi "
                . " FROM mrp_po_asset AS A"
                . " LEFT JOIN mrp_task_orders_request_asset AS B ON A.id_mrp_task_orders_request_asset = B.id_mrp_task_orders_request_asset"
                . " LEFT JOIN mrp_task_orders_request AS C ON B.id_mrp_task_orders = C.id_mrp_task_orders"
                . " LEFT JOIN mrp_request AS D ON C.id_mrp_request = D.id_mrp_request"
                . " LEFT JOIN mrp_request_asset AS E ON (D.id_mrp_request = E.id_mrp_request AND E.id_mrp_inventory_spesifik = A.id_mrp_inventory_spesifik)"
                . " LEFT JOIN hr_pegawai AS F ON D.id_hr_pegawai = F.id_hr_pegawai"
                . " LEFT JOIN hr_master_organisasi AS G ON F.id_hr_master_organisasi = G.id_hr_master_organisasi"
                . " WHERE A.id_mrp_po ='{$da->id_mrp_po}' AND A.id_mrp_task_orders='{$da->id_mrp_task_orders}'"
                . " GROUP BY D.id_hr_pegawai");
        
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
        
        $tgl_po = "";
        if($da->tanggal_po != "0000-00-00" AND $da->tanggal_po != ""){
        $tgl_po = date("d/m/y", strtotime($da->tanggal_po));

        }
        
        $tgl_surat_jln = "";
        if($da->tanggal_diterima != "0000-00-00" AND $da->tanggal_diterima != ""){
        $tgl_surat_jln = date("d/m/y", strtotime($da->tanggal_diterima));

        }
        
        $lama = ((strtotime ($da->tanggal_diterima) - strtotime ($da->tanggal_po))/(60*60*24));
        $nm_supplier = $this->global_models->get_field("mrp_supplier","name",array("id_mrp_supplier" => "{$da->id_mrp_supplier}"));
        
            $objPHPExcel->getActiveSheet()->setCellValue('A'.(6+$key),$no);
            $objPHPExcel->getActiveSheet()->getStyle("A".(6+$key))->applyFromArray(
            array(
                'alignment' => array(
                  'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP,
                ),
                'borders' => array(
                  'top'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                  ),
                  'right'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                  ),
                  'left'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_DOUBLE
                  ),
                ),
              )
          );
            $objPHPExcel->getActiveSheet()->setCellValue('B'.(6+$key),$tgl_po);
             $objPHPExcel->getActiveSheet()->getStyle("B".(6+$key))->applyFromArray(
            array(
                'alignment' => array(
                  'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP,
                ),
                'borders' => array(
                  'top'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                  ),
                  'right'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                  ),
                  'left'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_DOUBLE
                  ),
                ),
              )
          );
            $objPHPExcel->getActiveSheet()->setCellValue('C'.(6+$key),$da->no_po);
             $objPHPExcel->getActiveSheet()->getStyle("C".(6+$key))->applyFromArray(
            array(
                'alignment' => array(
                  'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP,
                ),
                'borders' => array(
                  'top'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                  ),
                  'right'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                  ),
                  'left'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_DOUBLE
                  ),
                ),
              )
          );
             
        $objPHPExcel->getActiveSheet()->setCellValue('D'.(6+$key),$da->qty);
        $objPHPExcel->getActiveSheet()->getStyle("D".(6+$key))->applyFromArray(
            array(
                'alignment' => array(
                  'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP,
                ),
                'borders' => array(
                  'top'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                  ),
                  'right'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                  ),
                  'left'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_DOUBLE
                  ),
                ),
              )
          );     
             
            $objPHPExcel->getActiveSheet()->setCellValue('E'.(6+$key),$da->price);
            $objPHPExcel->getActiveSheet()->getStyle('E'.(6+$key))->getNumberFormat()->setFormatCode('#,##0');
             $objPHPExcel->getActiveSheet()->getStyle("E".(6+$key))->applyFromArray(
            array(
                'alignment' => array(
                  'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP,
                ),
                'borders' => array(
                  'top'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                  ),
                  'right'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                  ),
                  'left'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_DOUBLE
                  ),
                ),
              )
          );
        
        $objPHPExcel->getActiveSheet()->setCellValue('F'.(6+$key),$nm_supplier);
        $objPHPExcel->getActiveSheet()->getStyle("F".(6+$key))->applyFromArray(
            array(
                'alignment' => array(
                  'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP,
                ),
                'borders' => array(
                  'top'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                  ),
                  'right'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                  ),
                  'left'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_DOUBLE
                  ),
                ),
              )
          );  
        
        $objPHPExcel->getActiveSheet()->setCellValue('G'.(6+$key),$beban);
        $objPHPExcel->getActiveSheet()->getStyle("G".(6+$key))->applyFromArray(
            array(
                'alignment' => array(
                  'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP,
                ),
                'borders' => array(
                  'top'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                  ),
                  'right'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                  ),
                  'left'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_DOUBLE
                  ),
                ),
              )
          );  
        
        $objPHPExcel->getActiveSheet()->setCellValue('H'.(6+$key),$tgl_surat_jln);
        $objPHPExcel->getActiveSheet()->getStyle("H".(6+$key))->applyFromArray(
            array(
                'alignment' => array(
                  'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP,
                ),
                'borders' => array(
                  'top'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                  ),
                  'right'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                  ),
                  'left'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_DOUBLE
                  ),
                ),
              )
          );  
        
        $objPHPExcel->getActiveSheet()->setCellValue('I'.(6+$key),$lama);
        $objPHPExcel->getActiveSheet()->getStyle("I".(6+$key))->applyFromArray(
            array(
                'alignment' => array(
                  'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP,
                ),
                'borders' => array(
                  'top'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                  ),
                  'right'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                  ),
                  'left'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_DOUBLE
                  ),
                ),
              )
          );     
        
            $angka++;
        }
        
        
      $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
      $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
      $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
      $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
      $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
      $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
      $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
      $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
      $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
      $objPHPExcel->getActiveSheet()->freezePane('A5');
      $objPHPExcel->getActiveSheet()->setTitle('Report PO');
      
      $data2 = $this->data_report_po();
      $zz = 1;
      foreach ($data2 as $ky => $val) {
          
      $objPHPExcel->createSheet();
      $title_org = $this->global_models->get_field("mrp_po","no_po", array("id_mrp_po" => "{$val->id_mrp_po}"));
       
     $title_org2 = str_replace("/","-",$title_org);
    
     $where = " A.id_mrp_po ='{$val->id_mrp_po}'";
       
      $data = $this->global_models->get_query("SELECT A.no_po,A.tanggal_po,B.name AS supplier,"
              . " (SELECT D.tanggal_diterima"
              . " FROM mrp_receiving_goods_po AS C"
              . " LEFT JOIN mrp_receiving_goods_department AS D ON C.id_mrp_receiving_goods_po = D.id_mrp_receiving_goods_po"
              . " WHERE C.id_mrp_po ='{$val->id_mrp_po}'"
             // . " GROUP BY C.id_mrp_receiving_goods_po"
             . " GROUP BY C.id_mrp_po"
              . " ORDER BY D.tanggal_diterima ASC) AS tanggal_diterima"
        . " FROM mrp_po AS A"
        . " LEFT JOIN mrp_supplier AS B ON A.id_mrp_supplier = B.id_mrp_supplier"
        . " WHERE A.status='7' AND {$where}"
//        . " GROUP BY A.id_mrp_inventory_spesifik"
        );
        
        $dt_beban =  $this->global_models->get_query(" SELECT G.title AS master_organisasi "
                . " FROM mrp_po_asset AS A"
                . " LEFT JOIN mrp_task_orders_request_asset AS B ON A.id_mrp_task_orders_request_asset = B.id_mrp_task_orders_request_asset"
                . " LEFT JOIN mrp_task_orders_request AS C ON B.id_mrp_task_orders = C.id_mrp_task_orders"
                . " LEFT JOIN mrp_request AS D ON C.id_mrp_request = D.id_mrp_request"
                . " LEFT JOIN mrp_request_asset AS E ON (D.id_mrp_request = E.id_mrp_request AND E.id_mrp_inventory_spesifik = A.id_mrp_inventory_spesifik)"
                . " LEFT JOIN hr_pegawai AS F ON D.id_hr_pegawai = F.id_hr_pegawai"
                . " LEFT JOIN hr_master_organisasi AS G ON F.id_hr_master_organisasi = G.id_hr_master_organisasi"
                . " WHERE A.id_mrp_po ='{$val->id_mrp_po}' AND A.id_mrp_task_orders='{$val->id_mrp_task_orders}'"
                . " GROUP BY D.id_hr_pegawai");
                $cx = 0;
                $beban = "";
        foreach ($dt_beban as $v) {
            if($cx > 0){
                $beban .= ",".$v->master_organisasi;
            }else{
                $beban .= $v->master_organisasi;
            }
            
            $cx++;
        }
        
        $tgl_surat_jln = "";
        if($data[0]->tanggal_diterima != "0000-00-00" AND $data[0]->tanggal_diterima != ""){
        $tgl_surat_jln = date("d/m/y", strtotime($data[0]->tanggal_diterima));

        }
            
        $tgl_po = "";
        if($data[0]->tanggal_po != "0000-00-00" AND $data[0]->tanggal_po != ""){
        $tgl_po = date("d/m/y", strtotime($data[0]->tanggal_po));

        }
        
        $lama = ((strtotime ($data[0]->tanggal_diterima) - strtotime ($data[0]->tanggal_po))/(60*60*24));
      $objPHPExcel->setActiveSheetIndex($zz);
      
//      $objPHPExcel->getActiveSheet()->mergeCells('A1:D1');
      $objPHPExcel->getActiveSheet()->setCellValue('B1', "No. PO :".$data[0]->no_po);
      $objPHPExcel->getActiveSheet()->getStyle('A1:H2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
      $objPHPExcel->getActiveSheet()->getStyle('A1:H2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
      $objPHPExcel->getActiveSheet()->getStyle('A1:H2')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
//      
      $objPHPExcel->getActiveSheet()->setCellValue('B2', "Tanggal PO :".$tgl_po);      
      $objPHPExcel->getActiveSheet()->getStyle('A2:H2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
      $objPHPExcel->getActiveSheet()->getStyle('A2:H2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
      $objPHPExcel->getActiveSheet()->getStyle('A2:H2')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
      
      $objPHPExcel->getActiveSheet()->setCellValue('B3', "Surat Jalan :".$tgl_surat_jln);      
      $objPHPExcel->getActiveSheet()->getStyle('A3:H3')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
      $objPHPExcel->getActiveSheet()->getStyle('A3:H3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
      $objPHPExcel->getActiveSheet()->getStyle('A3:H3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
      
      $objPHPExcel->getActiveSheet()->setCellValue('B4', "Lama :".$lama);      
      $objPHPExcel->getActiveSheet()->getStyle('A4:H4')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
      $objPHPExcel->getActiveSheet()->getStyle('A4:H4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
      $objPHPExcel->getActiveSheet()->getStyle('A4:H4')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
      
      $objPHPExcel->getActiveSheet()->setCellValue('B5', "Supplier :".$data[0]->supplier);      
      $objPHPExcel->getActiveSheet()->getStyle('A5:H5')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
      $objPHPExcel->getActiveSheet()->getStyle('A5:H5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
      $objPHPExcel->getActiveSheet()->getStyle('A5:H5')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
      
      $objPHPExcel->getActiveSheet()->setCellValue('B6', "Beban :".$beban);      
      $objPHPExcel->getActiveSheet()->getStyle('A6:H6')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
      $objPHPExcel->getActiveSheet()->getStyle('A6:H6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
      $objPHPExcel->getActiveSheet()->getStyle('A6:H6')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
      
      $objPHPExcel->getActiveSheet()->mergeCells('A7:A8');
      $objPHPExcel->getActiveSheet()->setCellValue('A7', 'NO');
      $objPHPExcel->getActiveSheet()->getStyle("A7:A8")->applyFromArray(
          array(
            'alignment' => array(
              'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
              'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            ),
            'borders' => array(
              'left'     => array(
                'style' => PHPExcel_Style_Border::BORDER_DOUBLE
              ),
              'right'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
              'top'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
              'bottom'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
            ),
          )
      );
      
      $objPHPExcel->getActiveSheet()->mergeCells('B7:B8');
      $objPHPExcel->getActiveSheet()->setCellValue('B7', 'Nama Barang');
      $objPHPExcel->getActiveSheet()->getStyle("B7:B8")->applyFromArray(
          array(
            'alignment' => array(
              'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
              'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            ),
            'borders' => array(
              'left'     => array(
                'style' => PHPExcel_Style_Border::BORDER_DOUBLE
              ),
              'right'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
              'top'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
              'bottom'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
            ),
          )
      );
      
       $objPHPExcel->getActiveSheet()->mergeCells('C7:C8');
      $objPHPExcel->getActiveSheet()->setCellValue('C7', 'Satuan');
      $objPHPExcel->getActiveSheet()->getStyle("C7:C8")->applyFromArray(
          array(
            'alignment' => array(
              'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
              'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            ),
            'borders' => array(
              'left'     => array(
                'style' => PHPExcel_Style_Border::BORDER_DOUBLE
              ),
              'right'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
              'top'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
              'bottom'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
            ),
          )
      );
      
      $objPHPExcel->getActiveSheet()->mergeCells('D7:D8');
      $objPHPExcel->getActiveSheet()->setCellValue('D7', 'Jumlah');
      $objPHPExcel->getActiveSheet()->getStyle("D7:D8")->applyFromArray(
          array(
            'alignment' => array(
              'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
              'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            ),
            'borders' => array(
              'left'     => array(
                'style' => PHPExcel_Style_Border::BORDER_DOUBLE
              ),
              'right'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
              'top'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
              'bottom'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
            ),
          )
      );
      
      $objPHPExcel->getActiveSheet()->mergeCells('E7:E8');
      $objPHPExcel->getActiveSheet()->setCellValue('E7', 'Harga');
      $objPHPExcel->getActiveSheet()->getStyle("E7:E8")->applyFromArray(
          array(
            'alignment' => array(
              'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
              'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            ),
            'borders' => array(
              'left'     => array(
                'style' => PHPExcel_Style_Border::BORDER_DOUBLE
              ),
              'right'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
              'top'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
              'bottom'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
            ),
          )
      );
      
      $objPHPExcel->getActiveSheet()->mergeCells('F7:F8');
      $objPHPExcel->getActiveSheet()->setCellValue('F7', 'Total');
      $objPHPExcel->getActiveSheet()->getStyle("F7:F8")->applyFromArray(
          array(
            'alignment' => array(
              'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
              'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            ),
            'borders' => array(
              'left'     => array(
                'style' => PHPExcel_Style_Border::BORDER_DOUBLE
              ),
              'right'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
              'top'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
              'bottom'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
            ),
          )
      );
      
      $objPHPExcel->getActiveSheet()->mergeCells('G7:G8');
      $objPHPExcel->getActiveSheet()->setCellValue('G7', 'Note');
      $objPHPExcel->getActiveSheet()->getStyle("G7:G8")->applyFromArray(
          array(
            'alignment' => array(
              'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
              'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            ),
            'borders' => array(
              'left'     => array(
                'style' => PHPExcel_Style_Border::BORDER_DOUBLE
              ),
              'right'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
              'top'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
              'bottom'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
            ),
          )
      );
      
      $objPHPExcel->getActiveSheet()->mergeCells('H7:H8');
      $objPHPExcel->getActiveSheet()->setCellValue('H7', 'Keterangan');
      $objPHPExcel->getActiveSheet()->getStyle("H7:H8")->applyFromArray(
          array(
            'alignment' => array(
              'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
              'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            ),
            'borders' => array(
              'left'     => array(
                'style' => PHPExcel_Style_Border::BORDER_DOUBLE
              ),
              'right'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
              'top'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
              'bottom'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
            ),
          )
      );
      
       $detail_rekap = $this->detil_data_report_po($val->id_mrp_po);
      
          $nom = 0;
//          $dropdown_type2 = $this->global_models->get_dropdown("mrp_type_inventory", "id_mrp_type_inventory", "code", FALSE);
          foreach ($detail_rekap as $key => $da) {
         $nom = $nom + 1;
          
         $total = (($da->jumlah * $da->nilai) * $da->harga_task_order_request);
        
        $brn = "";
        if($da->brand){
            $brn = "\n Brand:".$da->brand;
        }
         
              $umum =  $da->nama_barang." ".$da->title_spesifik.$brn;
              $objPHPExcel->getActiveSheet()->setCellValue('A'.(9+$key),$nom);
               $objPHPExcel->getActiveSheet()->getStyle('A'.(9+$key))->applyFromArray(
            array(
                'alignment' => array(
                  'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP,
                ),
                'borders' => array(
                  'top'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                  ),
                  'right'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                  ),
                  'left'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_DOUBLE
                  ),
                  'bottom'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                  ),  
                ),
              )
          );
              
              $objPHPExcel->getActiveSheet()->setCellValue('B'.(9+$key),$umum);
              $objPHPExcel->getActiveSheet()->getStyle('B'.(9+$key))->applyFromArray(
            array(
                'alignment' => array(
                  'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP,
                ),
                'borders' => array(
                  'top'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                  ),
                  'right'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                  ),
                  'left'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_DOUBLE
                  ),
                  'bottom'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                  ),  
                ),
              )
          );
              $objPHPExcel->getActiveSheet()->setCellValue('C'.(9+$key),$da->satuan);
              $objPHPExcel->getActiveSheet()->getStyle('C'.(9+$key))->applyFromArray(
            array(
                'alignment' => array(
                  'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP,
                ),
                'borders' => array(
                  'top'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                  ),
                  'right'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                  ),
                  'left'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_DOUBLE
                  ),
                  'bottom'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                  ),  
                ),
              )
          );
              $objPHPExcel->getActiveSheet()->setCellValue('D'.(9+$key),$da->jumlah);
              $objPHPExcel->getActiveSheet()->getStyle('D'.(9+$key))->getNumberFormat()->setFormatCode('#,##0');
              $objPHPExcel->getActiveSheet()->getStyle('D'.(9+$key))->applyFromArray(
            array(
                'alignment' => array(
                  'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP,
                ),
                'borders' => array(
                  'top'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                  ),
                  'right'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                  ),
                  'left'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_DOUBLE
                  ),
                  'bottom'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                  ),  
                ),
              )
          );
              $objPHPExcel->getActiveSheet()->setCellValue('E'.(9+$key),$da->harga_task_order_request);
              $objPHPExcel->getActiveSheet()->getStyle('E'.(9+$key))->getNumberFormat()->setFormatCode('#,##0');
              $objPHPExcel->getActiveSheet()->getStyle('E'.(9+$key))->applyFromArray(
            array(
                'alignment' => array(
                  'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP,
                ),
                'borders' => array(
                  'top'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                  ),
                  'right'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                  ),
                  'left'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_DOUBLE
                  ),
                  'bottom'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                  ),  
                ),
              )
          );
         
        $objPHPExcel->getActiveSheet()->setCellValue('F'.(9+$key),$total);
        $objPHPExcel->getActiveSheet()->getStyle('F'.(9+$key))->getNumberFormat()->setFormatCode('#,##0');
              $objPHPExcel->getActiveSheet()->getStyle('F'.(9+$key))->applyFromArray(
            array(
                'alignment' => array(
                  'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP,
                ),
                'borders' => array(
                  'top'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                  ),
                  'right'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                  ),
                  'left'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_DOUBLE
                  ),
                  'bottom'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                  ),  
                ),
              )
          );   
       $objPHPExcel->getActiveSheet()->setCellValue('G'.(9+$key),$da->catatan);
       $objPHPExcel->getActiveSheet()->getStyle('G'.(9+$key))->applyFromArray(
            array(
                'alignment' => array(
                  'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP,
                ),
                'borders' => array(
                  'top'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                  ),
                  'right'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                  ),
                  'left'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_DOUBLE
                  ),
                  'bottom'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                  ),  
                ),
              )
          );
       $objPHPExcel->getActiveSheet()->setCellValue('H'.(9+$key),$da->note);
       $objPHPExcel->getActiveSheet()->getStyle('H'.(9+$key))->applyFromArray(
            array(
                'alignment' => array(
                  'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP,
                ),
                'borders' => array(
                  'top'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                  ),
                  'right'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                  ),
                  'left'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_DOUBLE
                  ),
                  'bottom'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                  ),  
                ),
              )
          );     
          $jml_total2[$val->id_mrp_po] = $jml_total2[$val->id_mrp_po] + $total;
//       $objPHPExcel->getActiveSheet()->getStyle('E'.(6+$key))->getNumberFormat()->setFormatCode('#,##0');
//       $objPHPExcel->getActiveSheet()->setCellValue('E'.(6+$key),$da->harga);
     
        
        }
        
        $objPHPExcel->getActiveSheet()->setCellValue('E'.(10+$key), "Total");
       $objPHPExcel->getActiveSheet()->getStyle('E'.(10+$key))->applyFromArray(
            array(
                'alignment' => array(
                  'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP,
                ),
                'borders' => array(
                  'top'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                  ),
                  'right'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                  ),
                  'left'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_DOUBLE
                  ),
                  'bottom'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                  ),  
                ),
              )
          );
       
        $objPHPExcel->getActiveSheet()->setCellValue('F'.(10+$key), $jml_total2[$val->id_mrp_po]);
       $objPHPExcel->getActiveSheet()->getStyle('F'.(10+$key))->applyFromArray(
            array(
                'alignment' => array(
                  'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP,
                ),
                'borders' => array(
                  'top'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                  ),
                  'right'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                  ),
                  'left'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_DOUBLE
                  ),
                  'bottom'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                  ),  
                ),
              )
          );
       $objPHPExcel->getActiveSheet()->getStyle('F'.(10+$key))->getNumberFormat()->setFormatCode('#,##0');
       
      $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(4);
      $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(70);
      $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(10);
      $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
      $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
      $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
      $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(40);
      $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(70);;
      $objPHPExcel->getActiveSheet()->setTitle($title_org2);
       $zz++;   
      }
      
      
      
      $objPHPExcel->setActiveSheetIndex(0);
      header('Content-Type: application/vnd.ms-excel');
      header('Content-Disposition: attachment;filename="'.$filename."-".date("Y-m-d").'.xls"');
      header('Cache-Control: max-age=0');
      $objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel5');
//$objWriter->save(str_replace('.php', '.xls', __FILE__));
      $objWriter->save('php://output');die;
}

function export_report_po_merger_xls($filename){
    $month = array(0 => "Pilih", 1 => "Januari", 2 => "Feb", 3 => "Maret", 4 => "April", 5 => "Mei", 6 => "Juni", 7 => "Juli", 8 => "Agustus", 9 => "September",
            10 => "Oktober", 11 => "November", 12 => "Desember");
    $start_month = $month[$this->session->userdata('report_po_search_start_month')];
    $end_month =    $month[$this->session->userdata('report_po_search_end_month')];
     $periode = "Periode {$start_month} - {$end_month} {$this->session->userdata('report_po_search_year')}";
//    $dropdown_type1 = array (0 => "ATK & Cetakan");
//    $dropdown_type2 = $this->global_models->get_dropdown("mrp_type_inventory", "id_mrp_type_inventory", "code", FALSE);
//    $dropdown_type = array_merge($dropdown_type1, $dropdown_type2);  
//    $type = $dropdown_type[$this->session->userdata("report_dept_search_type")];
//     $dropdown_company = $this->global_models->get_dropdown("hr_company", "id_hr_company", "title", TRUE);
//     $company = $dropdown_company[$this->session->userdata('report_po_search_id_company')];  
//     $merger_comp_type= $type." ".$company;
//        print "aa"; die;
//    print_r($where_information); die;
      $objPHPExcel = $this->phpexcel;
      $objPHPExcel->getProperties()->setCreator("AntaVaya")
							 ->setLastModifiedBy("AntaVaya")
							 ->setTitle("Data REPORT PO")
							 ->setSubject("Data REPORT PO")
							 ->setDescription("REPORT PO")
							 ->setKeywords("REPORT PO")
							 ->setCategory("REPORT PO");

      $objPHPExcel->setActiveSheetIndex(0);
      $objPHPExcel->getActiveSheet()->mergeCells('A1:I1');
      $objPHPExcel->getActiveSheet()->setCellValue('B2', 'REPORT PO Antavaya');
      $objPHPExcel->getActiveSheet()->getStyle('A1:I2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
      $objPHPExcel->getActiveSheet()->getStyle('A1:I2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
      $objPHPExcel->getActiveSheet()->getStyle('A1:I2')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
      
//      $objPHPExcel->getActiveSheet()->setCellValue('B3', $merger_comp_type);      
//      $objPHPExcel->getActiveSheet()->getStyle('A3:D3')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
//      $objPHPExcel->getActiveSheet()->getStyle('A3:D3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
//      $objPHPExcel->getActiveSheet()->getStyle('A3:D3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
      
      $objPHPExcel->getActiveSheet()->setCellValue('B4', $periode);
      $objPHPExcel->getActiveSheet()->getStyle('A4:I4')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
      $objPHPExcel->getActiveSheet()->getStyle('A4:I4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
      $objPHPExcel->getActiveSheet()->getStyle('A4:I4')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
//      $objPHPExcel->getActiveSheet()->getStyle('A1:V2')->getFill()->getStartColor()->setARGB('FF808080');
//      $objPHPExcel->getActiveSheet()->getStyle('A1:D3')->applyFromArray(
//          array(
//            'font'    => array(
//              'bold'      => true,
//               'size'  => 14,
//              'name'  => 'Verdana'
//              
//            ),
//            'fill' => array(
//              'type'       => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
//                'rotation'   => 90,
//              'startcolor' => array(
//                'argb' => 'FFA0A0A0'
//              ),
//              'endcolor'   => array(
//                'argb' => 'FFFFFFFF'
//              )
//            )
//          )
//      );
      $objPHPExcel->getActiveSheet()->setCellValue('A5', 'NO');
      $objPHPExcel->getActiveSheet()->getStyle("A5")->applyFromArray(
          array(
            'alignment' => array(
              'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
              'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            ),
            'borders' => array(
              'left'     => array(
                'style' => PHPExcel_Style_Border::BORDER_DOUBLE
              ),
              'right'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
              'top'     => array(
                'style' => PHPExcel_Style_Border::BORDER_DOUBLE
              ),
              'bottom'     => array(
                'style' => PHPExcel_Style_Border::BORDER_DOUBLE
              ),
            ),
          )
      );
      $objPHPExcel->getActiveSheet()->setCellValue('B5', 'Tanggal');
      $objPHPExcel->getActiveSheet()->getStyle("B5")->applyFromArray(
          array(
            'alignment' => array(
              'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
              'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            ),
            'borders' => array(
              'left'     => array(
                'style' => PHPExcel_Style_Border::BORDER_DOUBLE
              ),
              'right'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
              'top'     => array(
                'style' => PHPExcel_Style_Border::BORDER_DOUBLE
              ),
              'bottom'     => array(
                'style' => PHPExcel_Style_Border::BORDER_DOUBLE
              ),
            ),
          )
      );
      
      $objPHPExcel->getActiveSheet()->setCellValue('C5', 'Keterangan');
      $objPHPExcel->getActiveSheet()->getStyle("C5")->applyFromArray(
          array(
            'alignment' => array(
              'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
              'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            ),
            'borders' => array(
              'left'     => array(
                'style' => PHPExcel_Style_Border::BORDER_DOUBLE
              ),
              'right'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
              'top'     => array(
                'style' => PHPExcel_Style_Border::BORDER_DOUBLE
              ),
              'bottom'     => array(
                'style' => PHPExcel_Style_Border::BORDER_DOUBLE
              ),
            ),
          )
      );
      
      $objPHPExcel->getActiveSheet()->setCellValue('D5', 'No PO');
      $objPHPExcel->getActiveSheet()->getStyle("D5")->applyFromArray(
          array(
            'alignment' => array(
              'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
              'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            ),
            'borders' => array(
              'left'     => array(
                'style' => PHPExcel_Style_Border::BORDER_DOUBLE
              ),
              'right'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
              'top'     => array(
                'style' => PHPExcel_Style_Border::BORDER_DOUBLE
              ),
              'bottom'     => array(
                'style' => PHPExcel_Style_Border::BORDER_DOUBLE
              ),
            ),
          )
      );
      $objPHPExcel->getActiveSheet()->setCellValue('E5', 'QTY');
      $objPHPExcel->getActiveSheet()->getStyle("E5")->applyFromArray(
          array(
            'alignment' => array(
              'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
              'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            ),
            'borders' => array(
              'left'     => array(
                'style' => PHPExcel_Style_Border::BORDER_DOUBLE
              ),
              'right'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
              'top'     => array(
                'style' => PHPExcel_Style_Border::BORDER_DOUBLE
              ),
              'bottom'     => array(
                'style' => PHPExcel_Style_Border::BORDER_DOUBLE
              ),
            ),
          )
      );
      
      $objPHPExcel->getActiveSheet()->setCellValue('F5', 'SUB TOTAL');
      $objPHPExcel->getActiveSheet()->getStyle("F5")->applyFromArray(
          array(
            'alignment' => array(
              'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
              'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            ),
            'borders' => array(
              'left'     => array(
                'style' => PHPExcel_Style_Border::BORDER_DOUBLE
              ),
              'right'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
              'top'     => array(
                'style' => PHPExcel_Style_Border::BORDER_DOUBLE
              ),
              'bottom'     => array(
                'style' => PHPExcel_Style_Border::BORDER_DOUBLE
              ),
            ),
          )
      );
      
      $objPHPExcel->getActiveSheet()->setCellValue('G5', 'DISCOUNT');
      $objPHPExcel->getActiveSheet()->getStyle("G5")->applyFromArray(
          array(
            'alignment' => array(
              'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
              'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            ),
            'borders' => array(
              'left'     => array(
                'style' => PHPExcel_Style_Border::BORDER_DOUBLE
              ),
              'right'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
              'top'     => array(
                'style' => PHPExcel_Style_Border::BORDER_DOUBLE
              ),
              'bottom'     => array(
                'style' => PHPExcel_Style_Border::BORDER_DOUBLE
              ),
            ),
          )
      );
      
      $objPHPExcel->getActiveSheet()->setCellValue('H5', 'PPN');
      $objPHPExcel->getActiveSheet()->getStyle("H5")->applyFromArray(
          array(
            'alignment' => array(
              'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
              'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            ),
            'borders' => array(
              'left'     => array(
                'style' => PHPExcel_Style_Border::BORDER_DOUBLE
              ),
              'right'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
              'top'     => array(
                'style' => PHPExcel_Style_Border::BORDER_DOUBLE
              ),
              'bottom'     => array(
                'style' => PHPExcel_Style_Border::BORDER_DOUBLE
              ),
            ),
          )
      );
      
      $objPHPExcel->getActiveSheet()->setCellValue('I5', 'Total Harga');
      $objPHPExcel->getActiveSheet()->getStyle("I5")->applyFromArray(
          array(
            'alignment' => array(
              'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
              'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            ),
            'borders' => array(
              'left'     => array(
                'style' => PHPExcel_Style_Border::BORDER_DOUBLE
              ),
              'right'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
              'top'     => array(
                'style' => PHPExcel_Style_Border::BORDER_DOUBLE
              ),
              'bottom'     => array(
                'style' => PHPExcel_Style_Border::BORDER_DOUBLE
              ),
            ),
          )
      );
      
      $objPHPExcel->getActiveSheet()->setCellValue('J5', 'Vendor');
      $objPHPExcel->getActiveSheet()->getStyle("J5")->applyFromArray(
          array(
            'alignment' => array(
              'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
              'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            ),
            'borders' => array(
              'left'     => array(
                'style' => PHPExcel_Style_Border::BORDER_DOUBLE
              ),
              'right'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
              'top'     => array(
                'style' => PHPExcel_Style_Border::BORDER_DOUBLE
              ),
              'bottom'     => array(
                'style' => PHPExcel_Style_Border::BORDER_DOUBLE
              ),
            ),
          )
      );
      
      $objPHPExcel->getActiveSheet()->setCellValue('K5', 'Beban');
      $objPHPExcel->getActiveSheet()->getStyle("K5")->applyFromArray(
          array(
            'alignment' => array(
              'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
              'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            ),
            'borders' => array(
              'left'     => array(
                'style' => PHPExcel_Style_Border::BORDER_DOUBLE
              ),
              'right'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
              'top'     => array(
                'style' => PHPExcel_Style_Border::BORDER_DOUBLE
              ),
              'bottom'     => array(
                'style' => PHPExcel_Style_Border::BORDER_DOUBLE
              ),
            ),
          )
      );
      
      $objPHPExcel->getActiveSheet()->setCellValue('L5', 'Surat Jalan');
      $objPHPExcel->getActiveSheet()->getStyle("L5")->applyFromArray(
          array(
            'alignment' => array(
              'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
              'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            ),
            'borders' => array(
              'left'     => array(
                'style' => PHPExcel_Style_Border::BORDER_DOUBLE
              ),
              'right'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
              'top'     => array(
                'style' => PHPExcel_Style_Border::BORDER_DOUBLE
              ),
              'bottom'     => array(
                'style' => PHPExcel_Style_Border::BORDER_DOUBLE
              ),
            ),
          )
      );
      
      $objPHPExcel->getActiveSheet()->setCellValue('M5', 'Lama');
      $objPHPExcel->getActiveSheet()->getStyle("M5")->applyFromArray(
          array(
            'alignment' => array(
              'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
              'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            ),
            'borders' => array(
              'left'     => array(
                'style' => PHPExcel_Style_Border::BORDER_DOUBLE
              ),
              'right'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
              'top'     => array(
                'style' => PHPExcel_Style_Border::BORDER_DOUBLE
              ),
              'bottom'     => array(
                'style' => PHPExcel_Style_Border::BORDER_DOUBLE
              ),
            ),
          )
      );
      
      
      $data = $this->data_report_po();
//     print $this->db->last_query();
//     die;
//        $lvl = array(1 => "Direktorat",2 => "Divisi",3 => "Department",4 => "Section");
        $no = $qty = $jumlah = 0;
        $angka = 6;
        
        foreach ($data as $key => $da) {
//          foreach($value['information'] AS $ky => $info){
            $no = $no + 1;
            
        $dt_beban =  $this->global_models->get_query(" SELECT G.title AS master_organisasi "
                . " FROM mrp_po_asset AS A"
                . " LEFT JOIN mrp_task_orders_request_asset AS B ON A.id_mrp_task_orders_request_asset = B.id_mrp_task_orders_request_asset"
                . " LEFT JOIN mrp_task_orders_request AS C ON B.id_mrp_task_orders = C.id_mrp_task_orders"
                . " LEFT JOIN mrp_request AS D ON C.id_mrp_request = D.id_mrp_request"
                . " LEFT JOIN mrp_request_asset AS E ON (C.id_mrp_request = E.id_mrp_request AND A.id_mrp_inventory_spesifik = E.id_mrp_inventory_spesifik) "
                . " LEFT JOIN hr_pegawai AS F ON D.id_hr_pegawai = F.id_hr_pegawai"
                . " LEFT JOIN hr_master_organisasi AS G ON F.id_hr_master_organisasi = G.id_hr_master_organisasi"
                . " WHERE A.status < 8 AND A.id_mrp_po ='{$da->id_mrp_po}' AND E.id_mrp_request IS NOT NULL AND A.id_mrp_task_orders='{$da->id_mrp_task_orders}'"
                . " GROUP BY F.id_hr_master_organisasi");   
        
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
        
        $ktrng = $this->global_models->get_query("SELECT B.title AS spesifik,C.name AS umum,C.id_mrp_type_inventory AS type,D.title AS brand"
                . " FROM mrp_po_asset AS A"
                . " LEFT JOIN mrp_inventory_spesifik AS B ON A.id_mrp_inventory_spesifik = B.id_mrp_inventory_spesifik"
                . " LEFT JOIN mrp_inventory_umum AS C ON B.id_mrp_inventory_umum = C.id_mrp_inventory_umum"
                . " LEFT JOIN mrp_brand AS D ON B.id_mrp_brand = D.id_mrp_brand"
                . " WHERE A.id_mrp_po ='{$da->id_mrp_po}' AND A.id_mrp_task_orders='{$da->id_mrp_task_orders}' ");
        $mn = 0;
        foreach ($ktrng as $vk) {
            if($vk->brand){
                $brand[$key] = "Brand:".$vk->brand.". \n";
            }else{
                $brand[$key] = "";
            }
            if($vk->type == 2){
                $keterangan[$key] = "ATK ".date("F Y",strtotime($da->tanggal_po));
            }else{
                $keterangan[$key] .= "~".$vk->umum." ".$vk->spesifik."\n ".$brand[$key];
            }
        }
        
        $tgl_po = "";
        if($da->tanggal_po != "0000-00-00" AND $da->tanggal_po != ""){
        $tgl_po = date("d/m/y", strtotime($da->tanggal_po));

        }
        
        $tgl_surat_jln = "";
        if($da->tanggal_diterima != "0000-00-00" AND $da->tanggal_diterima != ""){
        $tgl_surat_jln = date("d/m/y", strtotime($da->tanggal_diterima));

        }
        
        $lama = ((strtotime ($da->tanggal_diterima) - strtotime ($da->tanggal_po))/(60*60*24));
        $nm_supplier = $this->global_models->get_field("mrp_supplier","name",array("id_mrp_supplier" => "{$da->id_mrp_supplier}"));
        
            $objPHPExcel->getActiveSheet()->setCellValue('A'.(6+$key),$no);
            $objPHPExcel->getActiveSheet()->getStyle("A".(6+$key))->applyFromArray(
            array(
                'alignment' => array(
                  'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP,
                ),
                'borders' => array(
                  'top'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                  ),
                  'right'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                  ),
                  'left'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_DOUBLE
                  ),
                ),
              )
          );
            $objPHPExcel->getActiveSheet()->setCellValue('B'.(6+$key),$tgl_po);
             $objPHPExcel->getActiveSheet()->getStyle("B".(6+$key))->applyFromArray(
            array(
                'alignment' => array(
                  'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP,
                ),
                'borders' => array(
                  'top'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                  ),
                  'right'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                  ),
                  'left'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_DOUBLE
                  ),
                ),
              )
          );
             
             $objPHPExcel->getActiveSheet()->setCellValue('C'.(6+$key),$keterangan[$key]);
        $objPHPExcel->getActiveSheet()->getStyle("C".(6+$key))->applyFromArray(
            array(
                'alignment' => array(
                  'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP,
                ),
                'borders' => array(
                  'top'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                  ),
                  'right'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                  ),
                  'left'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_DOUBLE
                  ),
                ),
              )
          );   
        
            $objPHPExcel->getActiveSheet()->setCellValue('D'.(6+$key),$da->no_po);
             $objPHPExcel->getActiveSheet()->getStyle("D".(6+$key))->applyFromArray(
            array(
                'alignment' => array(
                  'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP,
                ),
                'borders' => array(
                  'top'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                  ),
                  'right'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                  ),
                  'left'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_DOUBLE
                  ),
                ),
              )
          );
             
        $objPHPExcel->getActiveSheet()->setCellValue('E'.(6+$key),$da->qty);
        $objPHPExcel->getActiveSheet()->getStyle("E".(6+$key))->applyFromArray(
            array(
                'alignment' => array(
                  'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP,
                ),
                'borders' => array(
                  'top'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                  ),
                  'right'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                  ),
                  'left'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_DOUBLE
                  ),
                ),
              )
          );     
            $sub_total[$key] = $da->price;
            $objPHPExcel->getActiveSheet()->setCellValue('F'.(6+$key),$sub_total[$key]);
            $objPHPExcel->getActiveSheet()->getStyle('F'.(6+$key))->getNumberFormat()->setFormatCode('#,##0');
             $objPHPExcel->getActiveSheet()->getStyle("F".(6+$key))->applyFromArray(
            array(
                'alignment' => array(
                  'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP,
                ),
                'borders' => array(
                  'top'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                  ),
                  'right'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                  ),
                  'left'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_DOUBLE
                  ),
                ),
              )
          );
             if($da->discount){
                 $discount[$key] = $da->discount;
             }else{
                 $discount[$key] = 0;
             }
             
             
         $objPHPExcel->getActiveSheet()->setCellValue('G'.(6+$key),$discount[$key]);
            $objPHPExcel->getActiveSheet()->getStyle('G'.(6+$key))->getNumberFormat()->setFormatCode('#,##0');
             $objPHPExcel->getActiveSheet()->getStyle("G".(6+$key))->applyFromArray(
            array(
                'alignment' => array(
                  'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP,
                ),
                'borders' => array(
                  'top'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                  ),
                  'right'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                  ),
                  'left'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_DOUBLE
                  ),
                ),
              )
          );    
        
             if($da->ppn == 1){
                 $ppn[$key] = ((10/100) * ($sub_total[$key] - $discount[$key]));
             }else{
                 $ppn[$key] = 0;
             }
             
             $objPHPExcel->getActiveSheet()->setCellValue('H'.(6+$key),$ppn[$key]);
            $objPHPExcel->getActiveSheet()->getStyle('H'.(6+$key))->getNumberFormat()->setFormatCode('#,##0');
             $objPHPExcel->getActiveSheet()->getStyle("H".(6+$key))->applyFromArray(
            array(
                'alignment' => array(
                  'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP,
                ),
                'borders' => array(
                  'top'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                  ),
                  'right'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                  ),
                  'left'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_DOUBLE
                  ),
                ),
              )
          );
             
             $all_total[$key] = ($sub_total[$key] - $discount[$key]) - $ppn[$key];
            $objPHPExcel->getActiveSheet()->setCellValue('I'.(6+$key),$all_total[$key]);
            $objPHPExcel->getActiveSheet()->getStyle('I'.(6+$key))->getNumberFormat()->setFormatCode('#,##0');
             $objPHPExcel->getActiveSheet()->getStyle("I".(6+$key))->applyFromArray(
            array(
                'alignment' => array(
                  'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP,
                ),
                'borders' => array(
                  'top'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                  ),
                  'right'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                  ),
                  'left'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_DOUBLE
                  ),
                ),
              )
          );    
             
        $objPHPExcel->getActiveSheet()->setCellValue('J'.(6+$key),$nm_supplier);
        $objPHPExcel->getActiveSheet()->getStyle("J".(6+$key))->applyFromArray(
            array(
                'alignment' => array(
                  'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP,
                ),
                'borders' => array(
                  'top'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                  ),
                  'right'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                  ),
                  'left'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_DOUBLE
                  ),
                ),
              )
          );  
        
        $objPHPExcel->getActiveSheet()->setCellValue('K'.(6+$key),$beban);
        $objPHPExcel->getActiveSheet()->getStyle("K".(6+$key))->applyFromArray(
            array(
                'alignment' => array(
                  'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP,
                ),
                'borders' => array(
                  'top'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                  ),
                  'right'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                  ),
                  'left'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_DOUBLE
                  ),
                ),
              )
          );  
        
        $objPHPExcel->getActiveSheet()->setCellValue('L'.(6+$key),$tgl_surat_jln);
        $objPHPExcel->getActiveSheet()->getStyle("L".(6+$key))->applyFromArray(
            array(
                'alignment' => array(
                  'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP,
                ),
                'borders' => array(
                  'top'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                  ),
                  'right'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                  ),
                  'left'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_DOUBLE
                  ),
                ),
              )
          );  
        
        $objPHPExcel->getActiveSheet()->setCellValue('M'.(6+$key),$lama);
        $objPHPExcel->getActiveSheet()->getStyle("M".(6+$key))->applyFromArray(
            array(
                'alignment' => array(
                  'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP,
                ),
                'borders' => array(
                  'top'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                  ),
                  'right'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                  ),
                  'left'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_DOUBLE
                  ),
                ),
              )
          );
            $jml_sub_ttl = $jml_sub_ttl + $sub_total[$key];
            $jml_total2 = $jml_total2 + $all_total[$key];
            $angka++;
        }
        
        $objPHPExcel->getActiveSheet()->setCellValue('F'.(7+$key), $jml_sub_ttl);
       $objPHPExcel->getActiveSheet()->getStyle('F'.(7+$key))->applyFromArray(
            array(
                'alignment' => array(
                  'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP,
                ),
                'borders' => array(
                  'top'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_DOUBLE
                  ),
                  'right'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_DOUBLE
                  ),
                  'left'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_DOUBLE
                  ),
                  'bottom'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_DOUBLE
                  ),  
                ),
              )
          );
       
       $objPHPExcel->getActiveSheet()->getStyle('F'.(7+$key))->getNumberFormat()->setFormatCode('#,##0');
       
       
        $objPHPExcel->getActiveSheet()->setCellValue('I'.(7+$key), $jml_total2);
       $objPHPExcel->getActiveSheet()->getStyle('I'.(7+$key))->applyFromArray(
            array(
                'alignment' => array(
                  'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP,
                ),
                'borders' => array(
                  'top'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_DOUBLE
                  ),
                  'right'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_DOUBLE
                  ),
                  'left'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_DOUBLE
                  ),
                  'bottom'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_DOUBLE
                  ),  
                ),
              )
          );
       $objPHPExcel->getActiveSheet()->getStyle('I'.(7+$key))->getNumberFormat()->setFormatCode('#,##0');
       
        
        
      $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
      $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
      $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
      $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
      $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
      $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
      $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
      $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
      $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
      $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
      $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
      $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
      $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
      $objPHPExcel->getActiveSheet()->freezePane('A6');
      
     
      header('Content-Type: application/vnd.ms-excel');
      header('Content-Disposition: attachment;filename="'.$filename."-".date("Y-m-d").'.xls"');
      header('Cache-Control: max-age=0');
      $objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel5');
//$objWriter->save(str_replace('.php', '.xls', __FILE__));
      $objWriter->save('php://output');die;
}

  private function olah_history_task_mutasi_code(&$kode){
    $this->load->helper('string');
    $kode_random = random_string('alnum', 6);
    $st_upper = strtoupper($kode_random);
    $kode = "HTM".$st_upper;
    $cek = $this->global_models->get_field("history_to_mutasi", "id_history_to_mutasi", array("kode" => $kode));
    if($cek > 0){
      $this->olah_history_task_mutasi_code($kode);
    }
  }
}
?>
