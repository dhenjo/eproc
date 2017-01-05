<?php
class Mmrp_po extends CI_Model {

    function __construct()
    {
        parent::__construct();
//        $this->load->database();
        $this->load->library('PHPExcel');
    }
    

    
    function data_po($id_mrp_task_orders = 0,$id_mrp_po = 0){
    
     $where = "WHERE A.id_mrp_task_orders = '{$id_mrp_task_orders}' AND A.id_mrp_po = '{$id_mrp_po}'  ";
      $list = $this->global_models->get_query("SELECT A.id_mrp_task_orders_request_asset,A.jumlah,A.catatan,A.note,C.name AS nama_barang,E.title AS satuan"
            . ",B.title AS title_spesifik,F.harga,A.id_mrp_task_orders_request_asset,E.group_satuan,A.harga AS harga_task_order_request"
              . ",E.nilai"
        . " FROM mrp_po_asset AS A"
        . " LEFT JOIN mrp_inventory_spesifik AS B ON A.id_mrp_inventory_spesifik = B.id_mrp_inventory_spesifik"
        . " LEFT JOIN mrp_inventory_umum AS C ON B.id_mrp_inventory_umum = C.id_mrp_inventory_umum"
        . " LEFT JOIN mrp_brand AS D ON B.id_mrp_brand = D.id_mrp_brand"
        . " LEFT JOIN mrp_satuan AS E ON A.id_mrp_satuan = E.id_mrp_satuan"
        . " LEFT JOIN mrp_supplier_inventory AS F ON B.id_mrp_inventory_spesifik = (F.id_mrp_inventory_spesifik AND F.id_mrp_supplier = '{$id_mrp_supplier}')"
        . " LEFT JOIN mrp_supplier G ON F.id_mrp_supplier = G.id_mrp_supplier "
        . " {$where}"
        . " ORDER BY C.name ASC");
    
    return $list;
}

    function po_export_xls($id_mrp_task_orders = 0,$id_mrp_po = 0,$filename){
        
      $objPHPExcel = $this->phpexcel;
      $objPHPExcel->getProperties()->setCreator("AntaVaya")
							 ->setLastModifiedBy("AntaVaya")
							 ->setTitle("Data PO")
							 ->setSubject("Data PO")
							 ->setDescription("DATA PO")
							 ->setKeywords("DATA PO")
							 ->setCategory("DATA PO");

      $objPHPExcel->setActiveSheetIndex(0);
      $objPHPExcel->getActiveSheet()->mergeCells('A1:F1');
      $objPHPExcel->getActiveSheet()->setCellValue('B2', 'DATA PO Antavaya');
      $objPHPExcel->getActiveSheet()->getStyle('A1:F2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
      $objPHPExcel->getActiveSheet()->getStyle('A1:F2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
      $objPHPExcel->getActiveSheet()->getStyle('A1:F2')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
 
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
      $objPHPExcel->getActiveSheet()->setCellValue('A3', 'NO');
      $objPHPExcel->getActiveSheet()->getStyle("A3")->applyFromArray(
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
      $objPHPExcel->getActiveSheet()->setCellValue('B3', 'Jenis Barang');
      $objPHPExcel->getActiveSheet()->getStyle("B3")->applyFromArray(
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
      $objPHPExcel->getActiveSheet()->setCellValue('C3', 'Jumlah Barang');
      $objPHPExcel->getActiveSheet()->getStyle("C3")->applyFromArray(
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
      $objPHPExcel->getActiveSheet()->setCellValue('D3', 'Satuan');
      $objPHPExcel->getActiveSheet()->getStyle("D3")->applyFromArray(
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
      
      $objPHPExcel->getActiveSheet()->setCellValue('E3', 'Harga Satuan');
      $objPHPExcel->getActiveSheet()->getStyle("E3")->applyFromArray(
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
      
      $objPHPExcel->getActiveSheet()->setCellValue('F3', 'Total Harga');
      $objPHPExcel->getActiveSheet()->getStyle("F3")->applyFromArray(
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
      
      $data = $this->data_po($id_mrp_task_orders,$id_mrp_po);
     $detail = $this->global_models->get("mrp_po", array("id_mrp_po" =>"{$id_mrp_po}"));
//        $lvl = array(1 => "Direktorat",2 => "Divisi",3 => "Department",4 => "Section");
        $no  = 0;
       
        $angka = 4;
        foreach ($data as $key => $da) {
//          foreach($value['information'] AS $ky => $info){
            $no = $no + 1;
           if($da->title_spesifik){
            $title_spesifik = " ".$da->title_spesifik;
            }else{
            $title_spesifik = "";
            }
            
            if($da->catatan){
            $catatan = "<br>".nl2br($da->catatan);
        }else{
            $catatan = "";
        }
    
      $total = ($da->jumlah * $da->nilai ) * $da->harga_task_order_request;
      $total2 += (($da->jumlah * $da->nilai) * $da->harga_task_order_request);
     
      $nama_barang = $da->nama_barang.$title_spesifik.$catatan;
      
            $objPHPExcel->getActiveSheet()->setCellValue('A'.(4+$key),$no);
            $objPHPExcel->getActiveSheet()->getStyle("A".(4+$key))->applyFromArray(
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
            $objPHPExcel->getActiveSheet()->setCellValue('B'.(4+$key),$nama_barang);
             $objPHPExcel->getActiveSheet()->getStyle("B".(4+$key))->applyFromArray(
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
            $objPHPExcel->getActiveSheet()->setCellValue('C'.(4+$key),$da->jumlah);
             $objPHPExcel->getActiveSheet()->getStyle("C".(4+$key))->applyFromArray(
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
             
        $objPHPExcel->getActiveSheet()->setCellValue('D'.(4+$key),$da->satuan);
        $objPHPExcel->getActiveSheet()->getStyle("D".(4+$key))->applyFromArray(
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
             
            $objPHPExcel->getActiveSheet()->setCellValue('E'.(4+$key),$da->harga_task_order_request);
            $objPHPExcel->getActiveSheet()->getStyle('E'.(4+$key))->getNumberFormat()->setFormatCode('#,##0');
             $objPHPExcel->getActiveSheet()->getStyle("E".(4+$key))->applyFromArray(
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
        
        $objPHPExcel->getActiveSheet()->setCellValue('F'.(4+$key),$total);
        $objPHPExcel->getActiveSheet()->getStyle('F'.(4+$key))->getNumberFormat()->setFormatCode('#,##0');
        $objPHPExcel->getActiveSheet()->getStyle("F".(4+$key))->applyFromArray(
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
      
    $total_sementara = ($total2 - $detail[0]->discount);
    $ppn = (10*$total_sementara)/100;
    if($detail[0]->ppn == 1){
        $dt_ppn = $ppn;
    }else{
        $dt_ppn = 0;
    }
    
    $total_akhir = $total_sementara + $dt_ppn;
    
      $objPHPExcel->getActiveSheet()->setCellValue('B'.($angka),"SUB TOTAL");
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
      
      $objPHPExcel->getActiveSheet()->setCellValue('F'.($angka), $total2);
      $objPHPExcel->getActiveSheet()->getStyle('F'.($angka))->getNumberFormat()->setFormatCode('#,##0');
      $objPHPExcel->getActiveSheet()->getStyle("F".($angka))->applyFromArray(
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
      
      $objPHPExcel->getActiveSheet()->setCellValue('B'.($angka+1),"DISCOUNT");
      $objPHPExcel->getActiveSheet()->getStyle("B".($angka+1))->applyFromArray(
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
      
      $objPHPExcel->getActiveSheet()->setCellValue('F'.($angka+1), $detail[0]->discount);
      $objPHPExcel->getActiveSheet()->getStyle('F'.($angka+1))->getNumberFormat()->setFormatCode('#,##0');
      $objPHPExcel->getActiveSheet()->getStyle("F".($angka+1))->applyFromArray(
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
      
      $objPHPExcel->getActiveSheet()->setCellValue('B'.($angka+2),"PPN");
      $objPHPExcel->getActiveSheet()->getStyle("B".($angka+2))->applyFromArray(
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
      
      $objPHPExcel->getActiveSheet()->setCellValue('F'.($angka+2), $dt_ppn);
      $objPHPExcel->getActiveSheet()->getStyle('F'.($angka+2))->getNumberFormat()->setFormatCode('#,##0');
      $objPHPExcel->getActiveSheet()->getStyle("F".($angka+2))->applyFromArray(
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
      
      $objPHPExcel->getActiveSheet()->setCellValue('B'.($angka+3),"TOTAL");
      $objPHPExcel->getActiveSheet()->getStyle("B".($angka+3))->applyFromArray(
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
      
      $objPHPExcel->getActiveSheet()->setCellValue('F'.($angka+3), $total_akhir);
      $objPHPExcel->getActiveSheet()->getStyle('F'.($angka+3))->getNumberFormat()->setFormatCode('#,##0');
      $objPHPExcel->getActiveSheet()->getStyle("F".($angka+3))->applyFromArray(
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
      
        $bod = "A".$angka.":E".$angka;
//        $objPHPExcel->getActiveSheet()->setCellValue($bod, "TOTAL");
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
      
      $objPHPExcel->getActiveSheet()->mergeCells('A1:D1');
      $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
      $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
      $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
      $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
      $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
      $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
//      $objPHPExcel->getActiveSheet()->freezePane('A3');
    
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
