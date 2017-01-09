<?php
class Mmrp_master extends CI_Model {

    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library('PHPExcel');
    }
    
    function get_supplier_inventory($id_mrp_supplier = 0){
    
        $where = "WHERE 1=1 AND A.status=1 AND A.id_mrp_supplier = '{$id_mrp_supplier}'";
        
      $data = $this->global_models->get_query("SELECT  A.id_mrp_supplier_inventory,A.id_mrp_supplier,A.harga,A.tanggal,A.status"
        . ",D.name AS inventory_umum,F.code AS type,E.title AS brand,G.title AS satuan,C.jenis,C.title AS title_spesifik"
        . " FROM mrp_supplier_inventory AS A"
//        . " LEFT JOIN mrp_supplier AS B ON A.id_mrp_supplier = B.id_mrp_supplier"
        . " LEFT JOIN mrp_inventory_spesifik AS C ON A.id_mrp_inventory_spesifik = C.id_mrp_inventory_spesifik"
        . " LEFT JOIN mrp_inventory_umum AS D ON C.id_mrp_inventory_umum = D.id_mrp_inventory_umum"    
        . " LEFT JOIN mrp_brand AS E ON C.id_mrp_brand = E.id_mrp_brand"
        . " LEFT JOIN mrp_type_inventory AS F ON D.id_mrp_type_inventory = F.id_mrp_type_inventory"
        . " LEFT JOIN mrp_satuan AS G ON C.id_mrp_satuan = G.id_mrp_satuan"    
        . " {$where}"
        . " ORDER BY D.name ASC");
      
      return $data;
    }
    

    function export_xls($filename,$id_supplier){
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
      $nama_supplier = $this->global_models->get_field("mrp_supplier", "name", 
                    array("id_mrp_supplier" => $id_supplier));
      $objPHPExcel->getActiveSheet()->mergeCells('A1:G2');
      $objPHPExcel->getActiveSheet()->setCellValue('A1', "Supplier Inventory {$nama_supplier}");
      $objPHPExcel->getActiveSheet()->getStyle('A1:G2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
  
//      $objPHPExcel->getActiveSheet()->getStyle('A1:V2')->getFill()->getStartColor()->setARGB('FF808080');
      $objPHPExcel->getActiveSheet()->getStyle('A1:G2')->applyFromArray(
          array(
            'font'    => array(
              'bold'      => true,
               'size'  => 18,
              'name'  => 'Verdana'
              
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
      $objPHPExcel->getActiveSheet()->setCellValue('A3', 'Nama Umum');
      $objPHPExcel->getActiveSheet()->setCellValue('B3', 'Nama Spesifik');
      $objPHPExcel->getActiveSheet()->setCellValue('C3', 'Type');
      $objPHPExcel->getActiveSheet()->setCellValue('D3', 'Jenis');
      $objPHPExcel->getActiveSheet()->setCellValue('E3', 'Brand');
      $objPHPExcel->getActiveSheet()->setCellValue('F3', 'Satuan');
      $objPHPExcel->getActiveSheet()->setCellValue('G3', 'Harga');
      
      $objPHPExcel->getActiveSheet()->getStyle('A3:G3')->applyFromArray(
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
      
      $data = $this->get_supplier_inventory($id_supplier);

        $no = 0;
        $jenis = array("1" => "Habis Pakai", "2" => "Asset");
        foreach ($data as $key => $da) {
//          foreach($value['information'] AS $ky => $info){
            $no = $no + 1;
           
        if($da->title_spesifik){
            $title_spesifik2 = $da->title_spesifik;
        }else{
            $title_spesifik2 = "";
        }
        
        if($da->jenis){
            $jenis2 = $jenis[$da->jenis];
        }else{
            $jenis2 = "";
        }
      
        if($da->type){
            $type2 = $da->type;
        }else{
            $type2 ="";
        }
        
        if($da->brand){
           $brand = $da->brand;
        }else{
            $brand = "";
        }
        
        if($da->satuan){
           $satuan = $da->satuan;
        }else{
            $satuan = "";
        }
        
//        $tgl = date("d F Y", strtotime($da->tanggal));
       
             
            $objPHPExcel->getActiveSheet()->setCellValue('A'.(4+$key),$da->inventory_umum);
            $objPHPExcel->getActiveSheet()->setCellValue('B'.(4+$key),$da->title_spesifik);
            $objPHPExcel->getActiveSheet()->setCellValue('C'.(4+$key),$type2);
            $objPHPExcel->getActiveSheet()->setCellValue('D'.(4+$key),$jenis2);
            $objPHPExcel->getActiveSheet()->setCellValue('E'.(4+$key),$brand);
            $objPHPExcel->getActiveSheet()->setCellValue('F'.(4+$key),$satuan);
            $objPHPExcel->getActiveSheet()->setCellValue('G'.(4+$key),$da->harga);
            $objPHPExcel->getActiveSheet()->getStyle('G'.(4+$key))->getNumberFormat()->setFormatCode('#,##0');
            
           }
      
      $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
      $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
      $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
      $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
      $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
      $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
      $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
      
      
      
      $objPHPExcel->setActiveSheetIndex(0);
      header('Content-Type: application/vnd.ms-excel');
      header('Content-Disposition: attachment;filename="'.$filename."-".date("Y-m-d").'.xls"');
      header('Cache-Control: max-age=0');
      $objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel5');
//$objWriter->save(str_replace('.php', '.xls', __FILE__));
      $objWriter->save('php://output');die;
    }
}
?>
