<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mrp_ajax_request extends MX_Controller {
  function __construct() {
    $this->menu = $this->cek();
  }
  
    function get_mrp_request_pengadaan_cetakan_rutin($start = 0,$id_users = 0){
      
      $dta_id = $this->global_models->get_field("hr_pegawai", "id_hr_master_organisasi", array("id_users" => $id_users));
      $hr_pegawai = $this->global_models->get("hr_master_organisasi", array("parent" => "{$dta_id}"));

        $no = 0;
        $aa = $dta_id;
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
    
        $qry = ""; 

        if($id_users == 1){
            $qry = "";
        }else{
            if($aa){
                $qry .= "AND ((A.id_hr_master_organisasi='$dta_id' OR A.id_hr_master_organisasi IN ($aa)) OR (A.id_create_by_organisasi='$dta_id' OR A.id_create_by_organisasi IN ($aa))) ";
            }elseif($this->nbscache->get_olahan("permission", $this->session->userdata("id_privilege"), "proses_ro", "edit") !== FALSE){
                $qry .= "AND ((A.id_hr_master_organisasi='$dta_id' OR A.status >='3') OR (A.id_create_by_organisasi='$dta_id' OR A.status >='3')) ";
            }else{
                $qry .= "AND (A.id_hr_master_organisasi='$dta_id' OR A.id_create_by_organisasi='$dta_id')";
            }
        }
            
//        }
    
    if($this->nbscache->get_olahan("permission", $this->session->userdata("id_privilege"), "show-request", "edit") !== FALSE){    
    $where = "WHERE A.type_inventory = 11 AND (A.status >= 1 AND A.status < 10)";
    }elseif($this->session->userdata("id") == 1){
      $where = "WHERE A.type_inventory = 11";   
    }else{
    $where = "WHERE A.type_inventory = 11 {$qry} AND ((A.status >= 1 AND A.status < 10) OR A.status =11) ";   
    }
    
     $data = $this->global_models->get_query("SELECT *"
        . " FROM v_mrp_request AS A"
        . " {$where}"
        . " GROUP BY A.id_mrp_request"
        . " ORDER BY A.create_date DESC"
        . " LIMIT {$start}, 10"
        );
        
        
//    print  $this->db->last_query(); die;
//    $data_array = json_decode($data);
//    $this->debug($data, true);
    
//    $jenis = array(1 => "Habis Pakai", 2 => "Asset");
    $status = array( 1=> "<span class='label bg-orange'>Draft</span>", 2 => "<span class='label bg-blue'>Pengajuan</span>",
        3 => "<span class='label bg-green'>Approved</span>",4 => "<span class='label bg-green'>Task Order</span>",
        5 => "<span class='label bg-green'>Proses PO</span>", 6 => "<span class='label bg-green'>Sent PO</span>",
        7 => "<span class='label bg-green'>Partial</span>",8 => "<span class='label bg-green'>RG</span>",
        9 => "<span class='label bg-green'>Closed RO</span>", 10 => "<span class='label bg-red'>CANCEL RO</span>",
        11=> "<span class='label bg-black'>Reject RO</span>");
//    $status = array(1 => "Create", 2 => "Approve");
//    $type_inventory = array(1 => "Pengadaan Cetakan", 2 => "Pengadaan ATK");
   $no = 0;
   $hide = 0;
    foreach ($data AS $ky => $da){
            $dt_name = "";
        if($da->name){
            $dt_name = "<br>Approved:".$da->name;
        }
        $receiver = "";
        if($da->id_pegawai_receiver){
            $receiver = "<br><b>Penerima Barang</b>:".$da->name_receiver."<".$da->nama_department_receiver.">";
        }
        $note_warning = $this->global_models->get_field("mrp_request","note_warning",array("id_mrp_request" => $da->id_mrp_request));
       if($da->status >= 9 AND $da->status <= 10){
           $btn_del = "";
       }else{
            if($da->create_by_users == $id_users OR $id_users == 1 OR $this->nbscache->get_olahan("permission", $this->session->userdata("id_privilege"), "btn-delete", "edit") !== FALSE){
           $btn_del = "<a href='javascript:void(0)' data-toggle='modal' type='button' class='btn btn-danger btn-sm' title='Delete' data-target='#edit-keterangan-cancel' isi='{$da->id_mrp_request}' id='id-customer-cancel' ><i class='fa fa-trash-o'></i></a>";
            }
        }
       if($da->status == 1 OR $da->status == 11){
           if($da->create_by_users == $id_users OR $id_users == 1){
               $hasil[] = array(
                date('d-M-Y H:i:s', strtotime($da->create_date))."<br>Kode Request:<b>".$da->ro_code."</b>",  
                $da->nama_pegawai."<br>".$da->nip,
                $da->perusahaan."<br>Department:".$da->department,
                $da->note."<br>".$note_warning,
                $status[$da->status].$dt_name,
                "<a href='".site_url("mrp/mrp-task-orders/add-task-orders/{$da->id_mrp_task_orders}")."'>{$da->code}</a>",
                $da->create_by.$receiver,        
                "<div class='btn-group'>"
                  . "<a href='".site_url("mrp/mrp-request/add-request-pengadaan-cetakan-rutin/{$da->id_mrp_request}")."' title='Edit Request Orders' type='button' class='btn btn-info btn-sm'><i class='fa fa-edit'></i></a>"
                  . $btn_del
                . "</div>"
              );
                $hide++;  
           }
            
       }else{
           $hasil[] = array(
                date('d-M-Y H:i:s', strtotime($da->create_date))."<br>Kode Request:<b>".$da->ro_code."</b>",  
                $da->nama_pegawai."<br>".$da->nip,
                $da->perusahaan."<br>Department:".$da->department,
                $da->note."<br>".$note_warning,
                $status[$da->status].$dt_name,
                "<a href='".site_url("mrp/mrp-task-orders/add-task-orders/{$da->id_mrp_task_orders}")."'>{$da->code}</a>",
                $da->create_by.$receiver,
                "<div class='btn-group'>"
                  . "<a href='".site_url("mrp/mrp-request/add-request-pengadaan-cetakan-rutin/{$da->id_mrp_request}")."' title='Edit Request Orders' type='button' class='btn btn-info btn-sm'><i class='fa fa-edit'></i></a>"
                . $btn_del
                . "</div>"
              );
                  $no++;
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
     function get_mrp_request_pengadaan_cetakan_invoice($start = 0,$id_users = 0){
      
      $dta_id = $this->global_models->get_field("hr_pegawai", "id_hr_master_organisasi", array("id_users" => $id_users));
      $hr_pegawai = $this->global_models->get("hr_master_organisasi", array("parent" => "{$dta_id}"));

        $no = 0;
        $aa = $dta_id;
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
    
        $qry = ""; 

        if($id_users == 1){
            $qry = "";
        }else{
            if($aa){
                $qry .= "AND ((A.id_hr_master_organisasi='$dta_id' OR A.id_hr_master_organisasi IN ($aa)) OR (A.id_create_by_organisasi='$dta_id' OR A.id_create_by_organisasi IN ($aa))) ";
            }elseif($this->nbscache->get_olahan("permission", $this->session->userdata("id_privilege"), "proses_ro", "edit") !== FALSE){
                $qry .= "AND ((A.id_hr_master_organisasi='$dta_id' OR A.status >='3') OR (A.id_create_by_organisasi='$dta_id' OR A.status >='3')) ";
            }else{
                $qry .= "AND (A.id_hr_master_organisasi='$dta_id' OR A.id_create_by_organisasi='$dta_id')";
            }
        }
            
//        }
    
    if($this->nbscache->get_olahan("permission", $this->session->userdata("id_privilege"), "show-request", "edit") !== FALSE){    
    $where = "WHERE A.type_inventory = 10 AND (A.status >= 1 AND A.status < 10)";
    }elseif($this->session->userdata("id") == 1){
      $where = "WHERE A.type_inventory = 10";   
    }else{
    $where = "WHERE A.type_inventory = 10 {$qry} AND ((A.status >= 1 AND A.status < 10) OR A.status =11) ";   
    }
    
     $data = $this->global_models->get_query("SELECT *"
        . " FROM v_mrp_request AS A"
        . " {$where}"
        . " GROUP BY A.id_mrp_request"
        . " ORDER BY A.create_date DESC"
        . " LIMIT {$start}, 10"
        );
        
        
//    print  $this->db->last_query(); die;
//    $data_array = json_decode($data);
//    $this->debug($data, true);
    
//    $jenis = array(1 => "Habis Pakai", 2 => "Asset");
    $status = array( 1=> "<span class='label bg-orange'>Draft</span>", 2 => "<span class='label bg-blue'>Pengajuan</span>",
        3 => "<span class='label bg-green'>Approved</span>",4 => "<span class='label bg-green'>Task Order</span>",
        5 => "<span class='label bg-green'>Proses PO</span>", 6 => "<span class='label bg-green'>Sent PO</span>",
        7 => "<span class='label bg-green'>Partial</span>",8 => "<span class='label bg-green'>RG</span>",
        9 => "<span class='label bg-green'>Closed RO</span>", 10 => "<span class='label bg-red'>CANCEL RO</span>",
        11=> "<span class='label bg-black'>Reject RO</span>");
//    $status = array(1 => "Create", 2 => "Approve");
//    $type_inventory = array(1 => "Pengadaan Cetakan", 2 => "Pengadaan ATK");
   $no = 0;
   $hide = 0;
    foreach ($data AS $ky => $da){
            $dt_name = "";
        if($da->name){
            $dt_name = "<br>Approved:".$da->name;
        }
        $receiver = "";
        if($da->id_pegawai_receiver){
            $receiver = "<br><b>Penerima Barang</b>:".$da->name_receiver."<".$da->nama_department_receiver.">";
        }
        $note_warning = $this->global_models->get_field("mrp_request","note_warning",array("id_mrp_request" => $da->id_mrp_request));
       if($da->status >= 9 AND $da->status <= 10){
           $btn_del = "";
       }else{
            if($da->create_by_users == $id_users OR $id_users == 1 OR $this->nbscache->get_olahan("permission", $this->session->userdata("id_privilege"), "btn-delete", "edit") !== FALSE){
           $btn_del = "<a href='javascript:void(0)' data-toggle='modal' type='button' class='btn btn-danger btn-sm' title='Delete' data-target='#edit-keterangan-cancel' isi='{$da->id_mrp_request}' id='id-customer-cancel' ><i class='fa fa-trash-o'></i></a>";
            }
        }
       if($da->status == 1 OR $da->status == 11){
           if($da->create_by_users == $id_users OR $id_users == 1){
               $hasil[] = array(
                date('d-M-Y H:i:s', strtotime($da->create_date))."<br>Kode Request:<b>".$da->ro_code."</b>",  
                $da->nama_pegawai."<br>".$da->nip,
                $da->perusahaan."<br>Department:".$da->department,
                $da->note."<br>".$note_warning,
                $status[$da->status].$dt_name,
                "<a href='".site_url("mrp/mrp-task-orders/add-task-orders/{$da->id_mrp_task_orders}")."'>{$da->code}</a>",
                $da->create_by.$receiver,        
                "<div class='btn-group'>"
                  . "<a href='".site_url("mrp/mrp-request/add-request-pengadaan-cetakan-invoice/{$da->id_mrp_request}")."' title='Edit Request Orders' type='button' class='btn btn-info btn-sm'><i class='fa fa-edit'></i></a>"
                  . $btn_del
                . "</div>"
              );
                $hide++;  
           }
            
       }else{
           $hasil[] = array(
                date('d-M-Y H:i:s', strtotime($da->create_date))."<br>Kode Request:<b>".$da->ro_code."</b>",  
                $da->nama_pegawai."<br>".$da->nip,
                $da->perusahaan."<br>Department:".$da->department,
                $da->note."<br>".$note_warning,
                $status[$da->status].$dt_name,
                "<a href='".site_url("mrp/mrp-task-orders/add-task-orders/{$da->id_mrp_task_orders}")."'>{$da->code}</a>",
                $da->create_by.$receiver,
                "<div class='btn-group'>"
                  . "<a href='".site_url("mrp/mrp-request/add-request-pengadaan-cetakan-invoice/{$da->id_mrp_request}")."' title='Edit Request Orders' type='button' class='btn btn-info btn-sm'><i class='fa fa-edit'></i></a>"
                . $btn_del
                . "</div>"
              );
                  $no++;
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
      function get_mrp_request_pengadaan_service($start = 0,$id_users = 0){
      
      $dta_id = $this->global_models->get_field("hr_pegawai", "id_hr_master_organisasi", array("id_users" => $id_users));
      $hr_pegawai = $this->global_models->get("hr_master_organisasi", array("parent" => "{$dta_id}"));

        $no = 0;
        $aa = $dta_id;
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
    
        $qry = ""; 

        if($id_users == 1){
            $qry = "";
        }else{
            if($aa){
                $qry .= "AND ((A.id_hr_master_organisasi='$dta_id' OR A.id_hr_master_organisasi IN ($aa)) OR (A.id_create_by_organisasi='$dta_id' OR A.id_create_by_organisasi IN ($aa))) ";
            }elseif($this->nbscache->get_olahan("permission", $this->session->userdata("id_privilege"), "proses_ro", "edit") !== FALSE){
                $qry .= "AND ((A.id_hr_master_organisasi='$dta_id' OR A.status >='3') OR (A.id_create_by_organisasi='$dta_id' OR A.status >='3')) ";
            }else{
                $qry .= "AND (A.id_hr_master_organisasi='$dta_id' OR A.id_create_by_organisasi='$dta_id')";
            }
        }
            
//        }
    
    if($this->nbscache->get_olahan("permission", $this->session->userdata("id_privilege"), "show-request", "edit") !== FALSE){    
    $where = "WHERE A.type_inventory = 5 AND (A.status >= 1 AND A.status < 10)";
    }elseif($this->session->userdata("id") == 1){
      $where = "WHERE A.type_inventory = 5";   
    }else{
    $where = "WHERE A.type_inventory = 5 {$qry} AND ((A.status >= 1 AND A.status < 10) OR A.status =11) ";   
    }
    
     $data = $this->global_models->get_query("SELECT *"
        . " FROM v_mrp_request AS A"
        . " {$where}"
        . " GROUP BY A.id_mrp_request"
        . " ORDER BY A.create_date DESC"
        . " LIMIT {$start}, 10"
        );
        
        
//    print  $this->db->last_query(); die;
//    $data_array = json_decode($data);
//    $this->debug($data, true);
    
//    $jenis = array(1 => "Habis Pakai", 2 => "Asset");
    $status = array( 1=> "<span class='label bg-orange'>Draft</span>", 2 => "<span class='label bg-blue'>Pengajuan</span>",
        3 => "<span class='label bg-green'>Approved</span>",4 => "<span class='label bg-green'>Task Order</span>",
        5 => "<span class='label bg-green'>Proses PO</span>", 6 => "<span class='label bg-green'>Sent PO</span>",
        7 => "<span class='label bg-green'>Partial</span>",8 => "<span class='label bg-green'>RG</span>",
        9 => "<span class='label bg-green'>Closed RO</span>", 10 => "<span class='label bg-red'>CANCEL RO</span>",
        11=> "<span class='label bg-black'>Reject RO</span>");
//    $status = array(1 => "Create", 2 => "Approve");
//    $type_inventory = array(1 => "Pengadaan Cetakan", 2 => "Pengadaan ATK");
   $no = 0;
   $hide = 0;
    foreach ($data AS $ky => $da){
            $dt_name = "";
        if($da->name){
            $dt_name = "<br>Approved:".$da->name;
        }
        $receiver = "";
        if($da->id_pegawai_receiver){
            $receiver = "<br><b>Penerima Barang</b>:".$da->name_receiver."<".$da->nama_department_receiver.">";
        }
        $note_warning = $this->global_models->get_field("mrp_request","note_warning",array("id_mrp_request" => $da->id_mrp_request));
       if($da->status >= 9 AND $da->status <= 10){
           $btn_del = "";
       }else{
            if($da->create_by_users == $id_users OR $id_users == 1 OR $this->nbscache->get_olahan("permission", $this->session->userdata("id_privilege"), "btn-delete", "edit") !== FALSE){
           $btn_del = "<a href='javascript:void(0)' data-toggle='modal' type='button' class='btn btn-danger btn-sm' title='Delete' data-target='#edit-keterangan-cancel' isi='{$da->id_mrp_request}' id='id-customer-cancel' ><i class='fa fa-trash-o'></i></a>";
            }
        }
       if($da->status == 1 OR $da->status == 11){
           if($da->create_by_users == $id_users OR $id_users == 1){
               $hasil[] = array(
                date('d-M-Y H:i:s', strtotime($da->create_date))."<br>Kode Request:<b>".$da->ro_code."</b>",  
                $da->nama_pegawai."<br>".$da->nip,
                $da->perusahaan."<br>Department:".$da->department,
                $da->note."<br>".$note_warning,
                $status[$da->status].$dt_name,
                "<a href='".site_url("mrp/mrp-task-orders/add-task-orders/{$da->id_mrp_task_orders}")."'>{$da->code}</a>",
                $da->create_by.$receiver,        
                "<div class='btn-group'>"
                  . "<a href='".site_url("mrp/mrp-request/add-request-pengadaan-service/{$da->id_mrp_request}")."' title='Edit Request Orders' type='button' class='btn btn-info btn-sm'><i class='fa fa-edit'></i></a>"
                  . $btn_del
                . "</div>"
              );
                $hide++;  
           }
            
       }else{
           $hasil[] = array(
                date('d-M-Y H:i:s', strtotime($da->create_date))."<br>Kode Request:<b>".$da->ro_code."</b>",  
                $da->nama_pegawai."<br>".$da->nip,
                $da->perusahaan."<br>Department:".$da->department,
                $da->note."<br>".$note_warning,
                $status[$da->status].$dt_name,
                "<a href='".site_url("mrp/mrp-task-orders/add-task-orders/{$da->id_mrp_task_orders}")."'>{$da->code}</a>",
                $da->create_by.$receiver,
                "<div class='btn-group'>"
                  . "<a href='".site_url("mrp/mrp-request/add-request-pengadaan-service/{$da->id_mrp_request}")."' title='Edit Request Orders' type='button' class='btn btn-info btn-sm'><i class='fa fa-edit'></i></a>"
                . $btn_del
                . "</div>"
              );
                  $no++;
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
  
   function get_form_mrp_request_pengadaan_cetakan_invoice($id_mrp_request = 0,$id_users = 0,$start = 0){
      
      $status = $this->global_models->get_field("mrp_request", "status", 
                    array("id_mrp_request" => "{$id_mrp_request}"));
                    
      $dt_user = $this->global_models->get_field("mrp_request", "create_by_users", 
                    array("id_mrp_request" => "{$id_mrp_request}"));              
                    
      $ls = $this->global_models->get_query("SELECT B.id_hr_company,B.id_hr_master_organisasi"
        . " FROM m_users AS A"
        . " LEFT JOIN hr_pegawai AS B ON A.id_users = B.id_users"
        . " WHERE A.id_users = '{$id_users}'");
//       print $this->db->last_query();
//       die;
      $where = "WHERE A.status = 1 AND B.id_mrp_type_inventory = 10";
      $disabled ="";
      
      if($id_mrp_request > 0){
          $where .= " AND jumlah IS NOT NULL";
      }
      
      if($status == 1 AND  $this->session->userdata("id") != $dt_user){
//          $where .= " AND jumlah IS NOT NULL";
          $disabled = "disabled ";  
      }elseif($status > 1){
//          $where .= " AND jumlah IS NOT NULL";
          $disabled = "disabled ";  
      }
//      
   
       if($this->session->userdata("id") == 1 OR ($status == 2 AND $this->nbscache->get_olahan("permission", $this->session->userdata("id_privilege"), "approval-ro", "edit") !== FALSE)){
       $disabled ="";
      }
      
      if($this->session->userdata("id") == 1 OR $status == 11 OR $this->nbscache->get_olahan("permission", $this->session->userdata("id_privilege"), "edit-form-ro", "edit") !== FALSE){
       $disabled ="";
      }
//      if($id_mrp_request > 0){
//          $join = " "
//                  . " ";
//          $field = ",E.jumlah";
//      }else{
//          $where . " AND 1=1 ";
//          $join = "";
//          $field = "";
//      }
       $data = $this->global_models->get_query("SELECT B.name AS inventory_umum,D.title AS satuan,A.title AS title_spesifik,"
        . "A.id_mrp_inventory_spesifik,C.title AS brand,E.jumlah,E.rg,E.id_mrp_request_asset AS id_spesifik,F.create_by_users,F.status"
        . " FROM mrp_inventory_spesifik AS A"
        . " LEFT JOIN mrp_inventory_umum AS B ON A.id_mrp_inventory_umum = B.id_mrp_inventory_umum"
        . " LEFT JOIN mrp_brand AS C ON A.id_mrp_brand = C.id_mrp_brand"
        . " LEFT JOIN mrp_satuan AS D ON A.id_mrp_satuan = D.id_mrp_satuan"
        . " LEFT JOIN mrp_request_asset AS E ON (A.id_mrp_inventory_spesifik = E.id_mrp_inventory_spesifik AND E.id_mrp_request = '{$id_mrp_request}')"
        . " LEFT JOIN mrp_request AS F ON E.id_mrp_request = F.id_mrp_request"
        . " {$where} "
        . " ORDER BY A.id_mrp_inventory_spesifik ASC"
        . " LIMIT {$start}, 10");
        
//       print $this->db->last_query(); die;
//    $data_array = json_decode($data);
//    $this->debug($data, true);
        
    
    if(count($data) > 0){
      $return['status'] = 2;
      $return['start']  = $start + 10;
    }
    else{
      $return['status'] = 3;
    }
    
//    $jenis = array(1 => "Habis Pakai", 2 => "Asset");
    $no = $start;
    $url = base_url()."themes/".DEFAULTTHEMES."/img/ajax-loader.gif";
    foreach ($data AS $da){
        if($da->jumlah){
            $jumlah = $da->jumlah;
        }else{
            $jumlah = 0;
        }
        
        if($da->rg){
            $rg = $da->rg;
        }else{
            $rg = 0;
        }
        
        $data_id = "id=dt_id_mrp_inventory_spesifik_".$da->id_mrp_inventory_spesifik;
        $data_jumlah = "id=jumlah".$da->id_mrp_inventory_spesifik;
        $data_jumlah_rg = "id=jumlah_rg".$da->id_mrp_inventory_spesifik;
        if($da->id_mrp_setting_lock_atk){
            $dt_jumlah = $this->form_eksternal->form_input("jumlah[]", $jumlah, $data_jumlah.'  style="width:100px;display:none" class="form-control jumlah input-sm" placeholder="Jumlah"').
            $this->form_eksternal->form_input("jumlah_rg[]", $rg, $data_jumlah_rg.'  style="width:100px;display:none" class="form-control jumlah_rg input-sm" placeholder="rg"');
        }else{
         
             $dt_jumlah = $this->form_eksternal->form_input("jumlah[]", $jumlah, $disabled.$data_jumlah.'  style="width:100px" class="form-control jumlah input-sm" placeholder="Jumlah"').
            $this->form_eksternal->form_input("jumlah_rg[]", $rg, $data_jumlah_rg.'  style="width:100px;display:none" class="form-control jumlah_rg input-sm" placeholder="rg"');
          
            
        }
        
        
        
        if($da->id_spesifik){
            if($id_users ==1 OR $da->create_by_users == $id_users OR $this->nbscache->get_olahan("permission", $this->session->userdata("id_privilege"), "edit-form-ro", "edit") !== FALSE OR $this->nbscache->get_olahan("permission", $this->session->userdata("id_privilege"), "approval-ro", "edit") !== FALSE){
        $btn_del =  "<div class='btn-group'>"
        . "<a href='javascript:void(0)' onclick='delete_request_pengadaan({$da->id_spesifik})' id='del_{$da->id_spesifik}' class='btn btn-danger btn-flat' style='width: 40px'><i class='fa fa-trash-o'></i></a>"
        ."<span style='display: none; margin-left: 10px;' id='img-page-{$da->id_spesifik}'><img width='35px' src='{$url}' /></span>"
        . "</div>";
            }
        }
        
        if($da->status < 6 OR $da->status == 11){
            $dt_jumlah = $dt_jumlah;
            $btn_del = $btn_del;
        }else{
            $dt_jumlah = $jumlah;
            $btn_del = "";
        }
        
      $hasil[] = array(
          $no =$no + 1,
        $da->inventory_umum." ".$da->title_spesifik,
        $da->satuan,
       $this->form_eksternal->form_input("id_mrp_inventory_spesifik[]", $da->id_mrp_inventory_spesifik, $data_id.' style="width:100px;display:none" class="form-control id_spesifik input-sm" placeholder=""').
       $rg, 
       $dt_jumlah, 
       $btn_del
      );
    }
    $return['hasil'] = $hasil;
//    $this->debug($return, true);
    print json_encode($return);
    die;
  }
  
  function get_form_mrp_request_pengadaan_cetakan_rutin($id_mrp_request = 0,$id_users = 0,$start = 0){
      
      $status = $this->global_models->get_field("mrp_request", "status", 
                    array("id_mrp_request" => "{$id_mrp_request}"));
                    
      $dt_user = $this->global_models->get_field("mrp_request", "create_by_users", 
                    array("id_mrp_request" => "{$id_mrp_request}"));              
                    
      $ls = $this->global_models->get_query("SELECT B.id_hr_company,B.id_hr_master_organisasi"
        . " FROM m_users AS A"
        . " LEFT JOIN hr_pegawai AS B ON A.id_users = B.id_users"
        . " WHERE A.id_users = '{$id_users}'");
//       print $this->db->last_query();
//       die;
      $where = "WHERE A.status = 1 AND B.id_mrp_type_inventory = 11";
      $disabled ="";
      
      if($id_mrp_request > 0){
          $where .= " AND jumlah IS NOT NULL";
      }
      
      if($status == 1 AND  $this->session->userdata("id") != $dt_user){
//          $where .= " AND jumlah IS NOT NULL";
          $disabled = "disabled ";  
      }elseif($status > 1){
//          $where .= " AND jumlah IS NOT NULL";
          $disabled = "disabled ";  
      }
//      
   
       if($this->session->userdata("id") == 1 OR ($status == 2 AND $this->nbscache->get_olahan("permission", $this->session->userdata("id_privilege"), "approval-ro", "edit") !== FALSE)){
       $disabled ="";
      }
      
      if($this->session->userdata("id") == 1 OR $status == 11 OR $this->nbscache->get_olahan("permission", $this->session->userdata("id_privilege"), "edit-form-ro", "edit") !== FALSE){
       $disabled ="";
      }
//      if($id_mrp_request > 0){
//          $join = " "
//                  . " ";
//          $field = ",E.jumlah";
//      }else{
//          $where . " AND 1=1 ";
//          $join = "";
//          $field = "";
//      }
       $data = $this->global_models->get_query("SELECT B.name AS inventory_umum,D.title AS satuan,A.title AS title_spesifik,"
        . "A.id_mrp_inventory_spesifik,C.title AS brand,E.jumlah,E.rg,E.id_mrp_request_asset AS id_spesifik,F.create_by_users,F.status"
        . " FROM mrp_inventory_spesifik AS A"
        . " LEFT JOIN mrp_inventory_umum AS B ON A.id_mrp_inventory_umum = B.id_mrp_inventory_umum"
        . " LEFT JOIN mrp_brand AS C ON A.id_mrp_brand = C.id_mrp_brand"
        . " LEFT JOIN mrp_satuan AS D ON A.id_mrp_satuan = D.id_mrp_satuan"
        . " LEFT JOIN mrp_request_asset AS E ON (A.id_mrp_inventory_spesifik = E.id_mrp_inventory_spesifik AND E.id_mrp_request = '{$id_mrp_request}')"
        . " LEFT JOIN mrp_request AS F ON E.id_mrp_request = F.id_mrp_request"
        . " {$where} "
        . " ORDER BY A.id_mrp_inventory_spesifik ASC"
        . " LIMIT {$start}, 10");
        
//       print $this->db->last_query(); die;
//    $data_array = json_decode($data);
//    $this->debug($data, true);
        
    
    if(count($data) > 0){
      $return['status'] = 2;
      $return['start']  = $start + 10;
    }
    else{
      $return['status'] = 3;
    }
    
//    $jenis = array(1 => "Habis Pakai", 2 => "Asset");
    $no = $start;
    $url = base_url()."themes/".DEFAULTTHEMES."/img/ajax-loader.gif";
    foreach ($data AS $da){
        if($da->jumlah){
            $jumlah = $da->jumlah;
        }else{
            $jumlah = 0;
        }
        
        if($da->rg){
            $rg = $da->rg;
        }else{
            $rg = 0;
        }
        
        $data_id = "id=dt_id_mrp_inventory_spesifik_".$da->id_mrp_inventory_spesifik;
        $data_jumlah = "id=jumlah".$da->id_mrp_inventory_spesifik;
        $data_jumlah_rg = "id=jumlah_rg".$da->id_mrp_inventory_spesifik;
        if($da->id_mrp_setting_lock_atk){
            $dt_jumlah = $this->form_eksternal->form_input("jumlah[]", $jumlah, $data_jumlah.'  style="width:100px;display:none" class="form-control jumlah input-sm" placeholder="Jumlah"').
            $this->form_eksternal->form_input("jumlah_rg[]", $rg, $data_jumlah_rg.'  style="width:100px;display:none" class="form-control jumlah_rg input-sm" placeholder="rg"');
        }else{
         
             $dt_jumlah = $this->form_eksternal->form_input("jumlah[]", $jumlah, $disabled.$data_jumlah.'  style="width:100px" class="form-control jumlah input-sm" placeholder="Jumlah"').
            $this->form_eksternal->form_input("jumlah_rg[]", $rg, $data_jumlah_rg.'  style="width:100px;display:none" class="form-control jumlah_rg input-sm" placeholder="rg"');
          
            
        }
        
        
        
        if($da->id_spesifik){
            if($id_users ==1 OR $da->create_by_users == $id_users OR $this->nbscache->get_olahan("permission", $this->session->userdata("id_privilege"), "edit-form-ro", "edit") !== FALSE OR $this->nbscache->get_olahan("permission", $this->session->userdata("id_privilege"), "approval-ro", "edit") !== FALSE){
        $btn_del =  "<div class='btn-group'>"
        . "<a href='javascript:void(0)' onclick='delete_request_pengadaan({$da->id_spesifik})' id='del_{$da->id_spesifik}' class='btn btn-danger btn-flat' style='width: 40px'><i class='fa fa-trash-o'></i></a>"
        ."<span style='display: none; margin-left: 10px;' id='img-page-{$da->id_spesifik}'><img width='35px' src='{$url}' /></span>"
        . "</div>";
            }
        }
        
        if($da->status < 6 OR $da->status == 11){
            $dt_jumlah = $dt_jumlah;
            $btn_del = $btn_del;
        }else{
            $dt_jumlah = $jumlah;
            $btn_del = "";
        }
        
      $hasil[] = array(
          $no =$no + 1,
        $da->inventory_umum." ".$da->title_spesifik,
        $da->satuan,
       $this->form_eksternal->form_input("id_mrp_inventory_spesifik[]", $da->id_mrp_inventory_spesifik, $data_id.' style="width:100px;display:none" class="form-control id_spesifik input-sm" placeholder=""').
       $rg, 
       $dt_jumlah, 
       $btn_del
      );
    }
    $return['hasil'] = $hasil;
//    $this->debug($return, true);
    print json_encode($return);
    die;
  }
  
  function get_list_form_mrp_request_pengadaan_cetakan_rutin($id_mrp_request = 0,$id_users = 0,$start = 0){
      
      $status = $this->global_models->get_field("mrp_request", "status", 
                    array("id_mrp_request" => "{$id_mrp_request}"));
                    
      $dt_user = $this->global_models->get_field("mrp_request", "create_by_users", 
                    array("id_mrp_request" => "{$id_mrp_request}"));              
                    
      $ls = $this->global_models->get_query("SELECT B.id_hr_company,B.id_hr_master_organisasi"
        . " FROM m_users AS A"
        . " LEFT JOIN hr_pegawai AS B ON A.id_users = B.id_users"
        . " WHERE A.id_users = '{$id_users}'");
//       print $this->db->last_query();
//       die;
      $where = "WHERE A.status = 1 AND B.id_mrp_type_inventory = 11";
      $disabled ="";
      if($status == 1 AND  $this->session->userdata("id") != $dt_user){
//          $where .= " AND jumlah IS NOT NULL";
          $disabled = "disabled ";  
      }elseif($status > 1){
//          $where .= " AND jumlah IS NOT NULL";
          $disabled = "disabled ";  
      }
//      
   
       if($this->session->userdata("id") == 1 OR ($status == 2 AND $this->nbscache->get_olahan("permission", $this->session->userdata("id_privilege"), "approval-ro", "edit") !== FALSE)){
       $disabled ="";
      }
      
      if($this->session->userdata("id") == 1 OR $status == 11 OR $this->nbscache->get_olahan("permission", $this->session->userdata("id_privilege"), "edit-form-ro", "edit") !== FALSE){
       $disabled ="";
      }

      $id_mrp_supplier = $this->global_models->get_field("mrp_setting_harga_atk","id_mrp_supplier",array("id_mrp_setting_harga_atk" => '1'));
      $data = $this->global_models->get_query("SELECT B.name AS inventory_umum,C.title AS brand,D.title AS satuan,"
        . "A.id_mrp_inventory_spesifik,A.title AS title_spesifik,E.jumlah,G.harga,I.id_mrp_setting_lock_atk"
        . " FROM mrp_inventory_spesifik AS A"
        . " LEFT JOIN mrp_inventory_umum AS B ON A.id_mrp_inventory_umum = B.id_mrp_inventory_umum"
        . " LEFT JOIN mrp_brand AS C ON A.id_mrp_brand = C.id_mrp_brand"
        . " LEFT JOIN mrp_satuan AS D ON A.id_mrp_satuan = D.id_mrp_satuan"
        . " LEFT JOIN mrp_request_asset AS E ON (A.id_mrp_inventory_spesifik = E.id_mrp_inventory_spesifik AND E.id_mrp_request = '{$id_mrp_request}')"
        . " LEFT JOIN mrp_request AS F ON E.id_mrp_request = F.id_mrp_request"
        . " LEFT JOIN mrp_supplier_inventory AS G ON (A.id_mrp_inventory_spesifik = G.id_mrp_inventory_spesifik AND G.id_mrp_supplier = '{$id_mrp_supplier}')"
        . " LEFT JOIN mrp_setting_lock_atk_asset AS H ON A.id_mrp_inventory_spesifik = H.id_mrp_inventory_spesifik"
        . " LEFT JOIN mrp_setting_lock_atk AS I ON (H.id_mrp_setting_lock_atk = I.id_mrp_setting_lock_atk AND I.id_hr_company = '{$ls[0]->id_hr_company}' AND I.id_hr_master_organisasi ='{$ls[0]->id_hr_master_organisasi}')"        
        . " {$where} "
        . " GROUP BY A.id_mrp_inventory_spesifik"
        . " ORDER BY A.id_mrp_inventory_spesifik ASC"
        . " LIMIT {$start}, 10");
        
//       print $this->db->last_query(); die;
//    $data_array = json_decode($data);
//    $this->debug($data, true);
        
    
    if(count($data) > 0){
      $return['status'] = 2;
      $return['start']  = $start + 10;
    }
    else{
      $return['status'] = 3;
    }
    
//    $jenis = array(1 => "Habis Pakai", 2 => "Asset");
    $no = $start;
    foreach ($data AS $da){
        if($da->jumlah){
            $jumlah = $da->jumlah;
        }else{
            $jumlah = 0;
        }
        if($da->harga){
            $harga = number_format($da->harga);
        }else{
            $harga = "";
        }
        
        $data_id = "id=dt_id_mrp_inventory_spesifik_".$da->id_mrp_inventory_spesifik;
        $data_jumlah = "id=jumlah".$da->id_mrp_inventory_spesifik;
        $data_jumlah_rg = "id=jumlah_rg".$da->id_mrp_inventory_spesifik;
//        if($da->id_mrp_setting_lock_atk){
//            $dt_jumlah = $this->form_eksternal->form_input("jumlah[]", $jumlah, $disabled.$data_jumlah.'  style="width:100px;display:none" class="form-control jumlah input-sm" placeholder="Jumlah"');
//        }else{
//            $dt_jumlah = $this->form_eksternal->form_input("jumlah[]", $jumlah, $disabled.$data_jumlah.'  style="width:100px" class="form-control jumlah input-sm" placeholder="Jumlah"');
//        }
        
        if($da->id_mrp_setting_lock_atk){
            $dt_jumlah = $this->form_eksternal->form_input("jumlah[]", $jumlah, $data_jumlah.'  style="width:100px;display:none" class="form-control jumlah input-sm" placeholder="Jumlah"').
            $this->form_eksternal->form_input("jumlah_rg[]", $rg, $data_jumlah_rg.'  style="width:100px;display:none" class="form-control jumlah_rg input-sm" placeholder="rg"');
        }else{
            
            $dt_jumlah = $this->form_eksternal->form_input("jumlah[]", $jumlah, $disabled.$data_jumlah.'  style="width:100px" class="form-control jumlah input-sm" placeholder="Jumlah"').
            $this->form_eksternal->form_input("jumlah_rg[]", $rg, $data_jumlah_rg.'  style="width:100px;display:none" class="form-control jumlah_rg input-sm" placeholder="rg"');
        }
        
      $hasil[] = array(
          $no =$no + 1,
        $da->inventory_umum." ".$da->title_spesifik,
        $da->satuan,
        $harga,  
        $this->form_eksternal->form_input("id_mrp_inventory_spesifik[]", $da->id_mrp_inventory_spesifik, $data_id.' style="width:100px;display:none" class="form-control id_spesifik input-sm" placeholder=""').
        $dt_jumlah, 
//        "<div class='btn-group'>"
//          . "<a href='".site_url("mrp/mrp-master/add-inventory-spesifik/{$da->id_mrp_request}")."' type='button' class='btn btn-info btn-flat' style='width: 40px'><i class='fa fa-edit'></i></a>"
//        . "</div>"
      );
    }
    $return['hasil'] = $hasil;
//    $this->debug($return, true);
    print json_encode($return);
    die;
  }
  
   function get_list_form_mrp_request_pengadaan_cetakan_invoice($id_mrp_request = 0,$id_users = 0,$start = 0){
      
      $status = $this->global_models->get_field("mrp_request", "status", 
                    array("id_mrp_request" => "{$id_mrp_request}"));
                    
      $dt_user = $this->global_models->get_field("mrp_request", "create_by_users", 
                    array("id_mrp_request" => "{$id_mrp_request}"));              
                    
      $ls = $this->global_models->get_query("SELECT B.id_hr_company,B.id_hr_master_organisasi"
        . " FROM m_users AS A"
        . " LEFT JOIN hr_pegawai AS B ON A.id_users = B.id_users"
        . " WHERE A.id_users = '{$id_users}'");
//       print $this->db->last_query();
//       die;
      $where = "WHERE A.status = 1 AND B.id_mrp_type_inventory = 10";
      $disabled ="";
      if($status == 1 AND  $this->session->userdata("id") != $dt_user){
//          $where .= " AND jumlah IS NOT NULL";
          $disabled = "disabled ";  
      }elseif($status > 1){
//          $where .= " AND jumlah IS NOT NULL";
          $disabled = "disabled ";  
      }
//      
   
       if($this->session->userdata("id") == 1 OR ($status == 2 AND $this->nbscache->get_olahan("permission", $this->session->userdata("id_privilege"), "approval-ro", "edit") !== FALSE)){
       $disabled ="";
      }
      
      if($this->session->userdata("id") == 1 OR $status == 11 OR $this->nbscache->get_olahan("permission", $this->session->userdata("id_privilege"), "edit-form-ro", "edit") !== FALSE){
       $disabled ="";
      }

      $id_mrp_supplier = $this->global_models->get_field("mrp_setting_harga_atk","id_mrp_supplier",array("id_mrp_setting_harga_atk" => '1'));
      $data = $this->global_models->get_query("SELECT B.name AS inventory_umum,C.title AS brand,D.title AS satuan,"
        . "A.id_mrp_inventory_spesifik,A.title AS title_spesifik,E.jumlah,G.harga,I.id_mrp_setting_lock_atk"
        . " FROM mrp_inventory_spesifik AS A"
        . " LEFT JOIN mrp_inventory_umum AS B ON A.id_mrp_inventory_umum = B.id_mrp_inventory_umum"
        . " LEFT JOIN mrp_brand AS C ON A.id_mrp_brand = C.id_mrp_brand"
        . " LEFT JOIN mrp_satuan AS D ON A.id_mrp_satuan = D.id_mrp_satuan"
        . " LEFT JOIN mrp_request_asset AS E ON (A.id_mrp_inventory_spesifik = E.id_mrp_inventory_spesifik AND E.id_mrp_request = '{$id_mrp_request}')"
        . " LEFT JOIN mrp_request AS F ON E.id_mrp_request = F.id_mrp_request"
        . " LEFT JOIN mrp_supplier_inventory AS G ON (A.id_mrp_inventory_spesifik = G.id_mrp_inventory_spesifik AND G.id_mrp_supplier = '{$id_mrp_supplier}')"
        . " LEFT JOIN mrp_setting_lock_atk_asset AS H ON A.id_mrp_inventory_spesifik = H.id_mrp_inventory_spesifik"
        . " LEFT JOIN mrp_setting_lock_atk AS I ON (H.id_mrp_setting_lock_atk = I.id_mrp_setting_lock_atk AND I.id_hr_company = '{$ls[0]->id_hr_company}' AND I.id_hr_master_organisasi ='{$ls[0]->id_hr_master_organisasi}')"        
        . " {$where} "
        . " GROUP BY A.id_mrp_inventory_spesifik"
        . " ORDER BY A.id_mrp_inventory_spesifik ASC"
        . " LIMIT {$start}, 10");
        
//       print $this->db->last_query(); die;
//    $data_array = json_decode($data);
//    $this->debug($data, true);
        
    
    if(count($data) > 0){
      $return['status'] = 2;
      $return['start']  = $start + 10;
    }
    else{
      $return['status'] = 3;
    }
    
//    $jenis = array(1 => "Habis Pakai", 2 => "Asset");
    $no = $start;
    foreach ($data AS $da){
        if($da->jumlah){
            $jumlah = $da->jumlah;
        }else{
            $jumlah = 0;
        }
        if($da->harga){
            $harga = number_format($da->harga);
        }else{
            $harga = "";
        }
        
        $data_id = "id=dt_id_mrp_inventory_spesifik_".$da->id_mrp_inventory_spesifik;
        $data_jumlah = "id=jumlah".$da->id_mrp_inventory_spesifik;
        $data_jumlah_rg = "id=jumlah_rg".$da->id_mrp_inventory_spesifik;
//        if($da->id_mrp_setting_lock_atk){
//            $dt_jumlah = $this->form_eksternal->form_input("jumlah[]", $jumlah, $disabled.$data_jumlah.'  style="width:100px;display:none" class="form-control jumlah input-sm" placeholder="Jumlah"');
//        }else{
//            $dt_jumlah = $this->form_eksternal->form_input("jumlah[]", $jumlah, $disabled.$data_jumlah.'  style="width:100px" class="form-control jumlah input-sm" placeholder="Jumlah"');
//        }
        
        if($da->id_mrp_setting_lock_atk){
            $dt_jumlah = $this->form_eksternal->form_input("jumlah[]", $jumlah, $data_jumlah.'  style="width:100px;display:none" class="form-control jumlah input-sm" placeholder="Jumlah"').
            $this->form_eksternal->form_input("jumlah_rg[]", $rg, $data_jumlah_rg.'  style="width:100px;display:none" class="form-control jumlah_rg input-sm" placeholder="rg"');
        }else{
            
            $dt_jumlah = $this->form_eksternal->form_input("jumlah[]", $jumlah, $disabled.$data_jumlah.'  style="width:100px" class="form-control jumlah input-sm" placeholder="Jumlah"').
            $this->form_eksternal->form_input("jumlah_rg[]", $rg, $data_jumlah_rg.'  style="width:100px;display:none" class="form-control jumlah_rg input-sm" placeholder="rg"');
        }
        
      $hasil[] = array(
          $no =$no + 1,
        $da->inventory_umum." ".$da->title_spesifik,
        $da->satuan,
        $harga,  
        $this->form_eksternal->form_input("id_mrp_inventory_spesifik[]", $da->id_mrp_inventory_spesifik, $data_id.' style="width:100px;display:none" class="form-control id_spesifik input-sm" placeholder=""').
        $dt_jumlah, 
//        "<div class='btn-group'>"
//          . "<a href='".site_url("mrp/mrp-master/add-inventory-spesifik/{$da->id_mrp_request}")."' type='button' class='btn btn-info btn-flat' style='width: 40px'><i class='fa fa-edit'></i></a>"
//        . "</div>"
      );
    }
    $return['hasil'] = $hasil;
//    $this->debug($return, true);
    print json_encode($return);
    die;
  }
  
      function get_mrp_request_pengadaan_technical($start = 0,$id_users = 0){
      
      $dta_id = $this->global_models->get_field("hr_pegawai", "id_hr_master_organisasi", array("id_users" => $id_users));
      $hr_pegawai = $this->global_models->get("hr_master_organisasi", array("parent" => "{$dta_id}"));

        $no = 0;
        $aa = $dta_id;
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
    
        $qry = ""; 

        if($id_users == 1){
            $qry = "";
        }else{
            if($aa){
                $qry .= "AND ((A.id_hr_master_organisasi='$dta_id' OR A.id_hr_master_organisasi IN ($aa)) OR (A.id_create_by_organisasi='$dta_id' OR A.id_create_by_organisasi IN ($aa))) ";
            }elseif($this->nbscache->get_olahan("permission", $this->session->userdata("id_privilege"), "proses_ro", "edit") !== FALSE){
                $qry .= "AND ((A.id_hr_master_organisasi='$dta_id' OR A.status >='3') OR (A.id_create_by_organisasi='$dta_id' OR A.status >='3')) ";
            }else{
                $qry .= "AND (A.id_hr_master_organisasi='$dta_id' OR A.id_create_by_organisasi='$dta_id')";
            }
        }
            
//        }
    
    if($this->nbscache->get_olahan("permission", $this->session->userdata("id_privilege"), "show-request", "edit") !== FALSE){    
    $where = "WHERE A.type_inventory = 4 AND (A.status >= 1 AND A.status < 10)";
    }elseif($this->session->userdata("id") == 1){
      $where = "WHERE A.type_inventory = 4";   
    }else{
    $where = "WHERE A.type_inventory = 4 {$qry} AND ((A.status >= 1 AND A.status < 10) OR A.status =11) ";   
    }
    
     $data = $this->global_models->get_query("SELECT *"
        . " FROM v_mrp_request AS A"
        . " {$where}"
        . " GROUP BY A.id_mrp_request"
        . " ORDER BY A.create_date DESC"
        . " LIMIT {$start}, 10"
        );
        
        
//    print  $this->db->last_query(); die;
//    $data_array = json_decode($data);
//    $this->debug($data, true);
    
//    $jenis = array(1 => "Habis Pakai", 2 => "Asset");
    $status = array( 1=> "<span class='label bg-orange'>Draft</span>", 2 => "<span class='label bg-blue'>Pengajuan</span>",
        3 => "<span class='label bg-green'>Approved</span>",4 => "<span class='label bg-green'>Task Order</span>",
        5 => "<span class='label bg-green'>Proses PO</span>", 6 => "<span class='label bg-green'>Sent PO</span>",
        7 => "<span class='label bg-green'>Partial</span>",8 => "<span class='label bg-green'>RG</span>",
        9 => "<span class='label bg-green'>Closed RO</span>", 10 => "<span class='label bg-red'>CANCEL RO</span>",
        11=> "<span class='label bg-black'>Reject RO</span>");
//    $status = array(1 => "Create", 2 => "Approve");
//    $type_inventory = array(1 => "Pengadaan Cetakan", 2 => "Pengadaan ATK");
   $no = 0;
   $hide = 0;
    foreach ($data AS $ky => $da){
            $dt_name = "";
        if($da->name){
            $dt_name = "<br>Approved:".$da->name;
        }
        $receiver = "";
        if($da->id_pegawai_receiver){
            $receiver = "<br><b>Penerima Barang</b>:".$da->name_receiver."<".$da->nama_department_receiver.">";
        }
        $note_warning = $this->global_models->get_field("mrp_request","note_warning",array("id_mrp_request" => $da->id_mrp_request));
       if($da->status >= 9 AND $da->status <= 10){
           $btn_del = "";
       }else{
            if($da->create_by_users == $id_users OR $id_users == 1 OR $this->nbscache->get_olahan("permission", $this->session->userdata("id_privilege"), "btn-delete", "edit") !== FALSE){
           $btn_del = "<a href='javascript:void(0)' data-toggle='modal' type='button' class='btn btn-danger btn-sm' title='Delete' data-target='#edit-keterangan-cancel' isi='{$da->id_mrp_request}' id='id-customer-cancel' ><i class='fa fa-trash-o'></i></a>";
            }
        }
       if($da->status == 1 OR $da->status == 11){
           if($da->create_by_users == $id_users OR $id_users == 1){
               $hasil[] = array(
                date('d-M-Y H:i:s', strtotime($da->create_date))."<br>Kode Request:<b>".$da->ro_code."</b>",  
                $da->nama_pegawai."<br>".$da->nip,
                $da->perusahaan."<br>Department:".$da->department,
                $da->note."<br>".$note_warning,
                $status[$da->status].$dt_name,
                "<a href='".site_url("mrp/mrp-task-orders/add-task-orders/{$da->id_mrp_task_orders}")."'>{$da->code}</a>",
                $da->create_by.$receiver,        
                "<div class='btn-group'>"
                  . "<a href='".site_url("mrp/mrp-request/add-request-pengadaan-technical/{$da->id_mrp_request}")."' title='Edit Request Orders' type='button' class='btn btn-info btn-sm'><i class='fa fa-edit'></i></a>"
                  . $btn_del
                . "</div>"
              );
                $hide++;  
           }
            
       }else{
           $hasil[] = array(
                date('d-M-Y H:i:s', strtotime($da->create_date))."<br>Kode Request:<b>".$da->ro_code."</b>",  
                $da->nama_pegawai."<br>".$da->nip,
                $da->perusahaan."<br>Department:".$da->department,
                $da->note."<br>".$note_warning,
                $status[$da->status].$dt_name,
                "<a href='".site_url("mrp/mrp-task-orders/add-task-orders/{$da->id_mrp_task_orders}")."'>{$da->code}</a>",
                $da->create_by.$receiver,
                "<div class='btn-group'>"
                  . "<a href='".site_url("mrp/mrp-request/add-request-pengadaan-technical/{$da->id_mrp_request}")."' title='Edit Request Orders' type='button' class='btn btn-info btn-sm'><i class='fa fa-edit'></i></a>"
                . $btn_del
                . "</div>"
              );
                  $no++;
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
  
   function get_mrp_request_pengadaan_office($start = 0,$id_users = 0){
      
      $dta_id = $this->global_models->get_field("hr_pegawai", "id_hr_master_organisasi", array("id_users" => $id_users));
      $hr_pegawai = $this->global_models->get("hr_master_organisasi", array("parent" => "{$dta_id}"));

        $no = 0;
        $aa = $dta_id;
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
    
        $qry = ""; 

        if($id_users == 1){
            $qry = "";
        }else{
            if($aa){
                $qry .= "AND ((A.id_hr_master_organisasi='$dta_id' OR A.id_hr_master_organisasi IN ($aa)) OR (A.id_create_by_organisasi='$dta_id' OR A.id_create_by_organisasi IN ($aa))) ";
            }elseif($this->nbscache->get_olahan("permission", $this->session->userdata("id_privilege"), "proses_ro", "edit") !== FALSE){
                $qry .= "AND ((A.id_hr_master_organisasi='$dta_id' OR A.status >='3') OR (A.id_create_by_organisasi='$dta_id' OR A.status >='3')) ";
            }else{
                $qry .= "AND (A.id_hr_master_organisasi='$dta_id' OR A.id_create_by_organisasi='$dta_id')";
            }
        }
            
//        }
    
    if($this->nbscache->get_olahan("permission", $this->session->userdata("id_privilege"), "show-request", "edit") !== FALSE){    
    $where = "WHERE A.type_inventory = 7 AND (A.status >= 1 AND A.status < 10)";
    }elseif($this->session->userdata("id") == 1){
      $where = "WHERE A.type_inventory = 7";   
    }else{
    $where = "WHERE A.type_inventory = 7 {$qry} AND ((A.status >= 1 AND A.status < 10) OR A.status =11) ";   
    }
    
     $data = $this->global_models->get_query("SELECT *"
        . " FROM v_mrp_request AS A"
        . " {$where}"
        . " GROUP BY A.id_mrp_request"
        . " ORDER BY A.create_date DESC"
        . " LIMIT {$start}, 10"
        );
        
        
//    print  $this->db->last_query(); die;
//    $data_array = json_decode($data);
//    $this->debug($data, true);
    
//    $jenis = array(1 => "Habis Pakai", 2 => "Asset");
    $status = array( 1=> "<span class='label bg-orange'>Draft</span>", 2 => "<span class='label bg-blue'>Pengajuan</span>",
        3 => "<span class='label bg-green'>Approved</span>",4 => "<span class='label bg-green'>Task Order</span>",
        5 => "<span class='label bg-green'>Proses PO</span>", 6 => "<span class='label bg-green'>Sent PO</span>",
        7 => "<span class='label bg-green'>Partial</span>",8 => "<span class='label bg-green'>RG</span>",
        9 => "<span class='label bg-green'>Closed RO</span>", 10 => "<span class='label bg-red'>CANCEL RO</span>",
        11=> "<span class='label bg-black'>Reject RO</span>");
//    $status = array(1 => "Create", 2 => "Approve");
//    $type_inventory = array(1 => "Pengadaan Cetakan", 2 => "Pengadaan ATK");
   $no = 0;
   $hide = 0;
    foreach ($data AS $ky => $da){
            $dt_name = "";
        if($da->name){
            $dt_name = "<br>Approved:".$da->name;
        }
        $receiver = "";
        if($da->id_pegawai_receiver){
            $receiver = "<br><b>Penerima Barang</b>:".$da->name_receiver."<".$da->nama_department_receiver.">";
        }
        $note_warning = $this->global_models->get_field("mrp_request","note_warning",array("id_mrp_request" => $da->id_mrp_request));
       if($da->status >= 9 AND $da->status <= 10){
           $btn_del = "";
       }else{
            if($da->create_by_users == $id_users OR $id_users == 1 OR $this->nbscache->get_olahan("permission", $this->session->userdata("id_privilege"), "btn-delete", "edit") !== FALSE){
           $btn_del = "<a href='javascript:void(0)' data-toggle='modal' type='button' class='btn btn-danger btn-sm' title='Delete' data-target='#edit-keterangan-cancel' isi='{$da->id_mrp_request}' id='id-customer-cancel' ><i class='fa fa-trash-o'></i></a>";
            }
        }
       if($da->status == 1 OR $da->status == 11){
           if($da->create_by_users == $id_users OR $id_users == 1){
               $hasil[] = array(
                date('d-M-Y H:i:s', strtotime($da->create_date))."<br>Kode Request:<b>".$da->ro_code."</b>",  
                $da->nama_pegawai."<br>".$da->nip,
                $da->perusahaan."<br>Department:".$da->department,
                $da->note."<br>".$note_warning,
                $status[$da->status].$dt_name,
                "<a href='".site_url("mrp/mrp-task-orders/add-task-orders/{$da->id_mrp_task_orders}")."'>{$da->code}</a>",
                $da->create_by.$receiver,        
                "<div class='btn-group'>"
                  . "<a href='".site_url("mrp/mrp-request/add-request-pengadaan-office/{$da->id_mrp_request}")."' title='Edit Request Orders' type='button' class='btn btn-info btn-sm'><i class='fa fa-edit'></i></a>"
                  . $btn_del
                . "</div>"
              );
                $hide++;  
           }
            
       }else{
           $hasil[] = array(
                date('d-M-Y H:i:s', strtotime($da->create_date))."<br>Kode Request:<b>".$da->ro_code."</b>",  
                $da->nama_pegawai."<br>".$da->nip,
                $da->perusahaan."<br>Department:".$da->department,
                $da->note."<br>".$note_warning,
                $status[$da->status].$dt_name,
                "<a href='".site_url("mrp/mrp-task-orders/add-task-orders/{$da->id_mrp_task_orders}")."'>{$da->code}</a>",
                $da->create_by.$receiver,
                "<div class='btn-group'>"
                  . "<a href='".site_url("mrp/mrp-request/add-request-pengadaan-office/{$da->id_mrp_request}")."' title='Edit Request Orders' type='button' class='btn btn-info btn-sm'><i class='fa fa-edit'></i></a>"
                . $btn_del
                . "</div>"
              );
                  $no++;
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
  
   function get_mrp_request_pengadaan_umum($start = 0,$id_users = 0){
      
      $dta_id = $this->global_models->get_field("hr_pegawai", "id_hr_master_organisasi", array("id_users" => $id_users));
      $hr_pegawai = $this->global_models->get("hr_master_organisasi", array("parent" => "{$dta_id}"));

        $no = 0;
        $aa = $dta_id;
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
    
        $qry = ""; 

        if($id_users == 1){
            $qry = "";
        }else{
            if($aa){
                $qry .= "AND ((A.id_hr_master_organisasi='$dta_id' OR A.id_hr_master_organisasi IN ($aa)) OR (A.id_create_by_organisasi='$dta_id' OR A.id_create_by_organisasi IN ($aa))) ";
            }elseif($this->nbscache->get_olahan("permission", $this->session->userdata("id_privilege"), "proses_ro", "edit") !== FALSE){
                $qry .= "AND ((A.id_hr_master_organisasi='$dta_id' OR A.status >='3') OR (A.id_create_by_organisasi='$dta_id' OR A.status >='3')) ";
            }else{
                $qry .= "AND (A.id_hr_master_organisasi='$dta_id' OR A.id_create_by_organisasi='$dta_id')";
            }
        }
            
//        }
    
    if($this->nbscache->get_olahan("permission", $this->session->userdata("id_privilege"), "show-request", "edit") !== FALSE){    
    $where = "WHERE A.type_inventory = 9 AND (A.status >= 1 AND A.status < 10)";
    }elseif($this->session->userdata("id") == 1){
      $where = "WHERE A.type_inventory = 9";   
    }else{
    $where = "WHERE A.type_inventory = 9 {$qry} AND ((A.status >= 1 AND A.status < 10) OR A.status =11) ";   
    }
    
     $data = $this->global_models->get_query("SELECT *"
        . " FROM v_mrp_request AS A"
        . " {$where}"
        . " GROUP BY A.id_mrp_request"
        . " ORDER BY A.create_date DESC"
        . " LIMIT {$start}, 10"
        );
        
        
//    print  $this->db->last_query(); die;
//    $data_array = json_decode($data);
//    $this->debug($data, true);
    
//    $jenis = array(1 => "Habis Pakai", 2 => "Asset");
    $status = array( 1=> "<span class='label bg-orange'>Draft</span>", 2 => "<span class='label bg-blue'>Pengajuan</span>",
        3 => "<span class='label bg-green'>Approved</span>",4 => "<span class='label bg-green'>Task Order</span>",
        5 => "<span class='label bg-green'>Proses PO</span>", 6 => "<span class='label bg-green'>Sent PO</span>",
        7 => "<span class='label bg-green'>Partial</span>",8 => "<span class='label bg-green'>RG</span>",
        9 => "<span class='label bg-green'>Closed RO</span>", 10 => "<span class='label bg-red'>CANCEL RO</span>",
        11=> "<span class='label bg-black'>Reject RO</span>");
//    $status = array(1 => "Create", 2 => "Approve");
//    $type_inventory = array(1 => "Pengadaan Cetakan", 2 => "Pengadaan ATK");
   $no = 0;
   $hide = 0;
    foreach ($data AS $ky => $da){
            $dt_name = "";
        if($da->name){
            $dt_name = "<br>Approved:".$da->name;
        }
        $receiver = "";
        if($da->id_pegawai_receiver){
            $receiver = "<br><b>Penerima Barang</b>:".$da->name_receiver."<".$da->nama_department_receiver.">";
        }
        $note_warning = $this->global_models->get_field("mrp_request","note_warning",array("id_mrp_request" => $da->id_mrp_request));
       if($da->status >= 9 AND $da->status <= 10){
           $btn_del = "";
       }else{
            if($da->create_by_users == $id_users OR $id_users == 1 OR $this->nbscache->get_olahan("permission", $this->session->userdata("id_privilege"), "btn-delete", "edit") !== FALSE){
           $btn_del = "<a href='javascript:void(0)' data-toggle='modal' type='button' class='btn btn-danger btn-sm' title='Delete' data-target='#edit-keterangan-cancel' isi='{$da->id_mrp_request}' id='id-customer-cancel' ><i class='fa fa-trash-o'></i></a>";
            }
        }
       if($da->status == 1 OR $da->status == 11){
           if($da->create_by_users == $id_users OR $id_users == 1){
               $hasil[] = array(
                date('d-M-Y H:i:s', strtotime($da->create_date))."<br>Kode Request:<b>".$da->ro_code."</b>",  
                $da->nama_pegawai."<br>".$da->nip,
                $da->perusahaan."<br>Department:".$da->department,
                $da->note."<br>".$note_warning,
                $status[$da->status].$dt_name,
                "<a href='".site_url("mrp/mrp-task-orders/add-task-orders/{$da->id_mrp_task_orders}")."'>{$da->code}</a>",
                $da->create_by.$receiver,        
                "<div class='btn-group'>"
                  . "<a href='".site_url("mrp/mrp-request/add-request-pengadaan-umum/{$da->id_mrp_request}")."' title='Edit Request Orders' type='button' class='btn btn-info btn-sm'><i class='fa fa-edit'></i></a>"
                  . $btn_del
                . "</div>"
              );
                $hide++;  
           }
            
       }else{
           $hasil[] = array(
                date('d-M-Y H:i:s', strtotime($da->create_date))."<br>Kode Request:<b>".$da->ro_code."</b>",  
                $da->nama_pegawai."<br>".$da->nip,
                $da->perusahaan."<br>Department:".$da->department,
                $da->note."<br>".$note_warning,
                $status[$da->status].$dt_name,
                "<a href='".site_url("mrp/mrp-task-orders/add-task-orders/{$da->id_mrp_task_orders}")."'>{$da->code}</a>",
                $da->create_by.$receiver,
                "<div class='btn-group'>"
                  . "<a href='".site_url("mrp/mrp-request/add-request-pengadaan-umum/{$da->id_mrp_request}")."' title='Edit Request Orders' type='button' class='btn btn-info btn-sm'><i class='fa fa-edit'></i></a>"
                . $btn_del
                . "</div>"
              );
                  $no++;
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
  
  function get_mrp_request_pengadaan_promosi($start = 0,$id_users = 0){
      
      $dta_id = $this->global_models->get_field("hr_pegawai", "id_hr_master_organisasi", array("id_users" => $id_users));
      $hr_pegawai = $this->global_models->get("hr_master_organisasi", array("parent" => "{$dta_id}"));

        $no = 0;
        $aa = $dta_id;
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
    
        $qry = ""; 

        if($id_users == 1){
            $qry = "";
        }else{
            if($aa){
                $qry .= "AND ((A.id_hr_master_organisasi='$dta_id' OR A.id_hr_master_organisasi IN ($aa)) OR (A.id_create_by_organisasi='$dta_id' OR A.id_create_by_organisasi IN ($aa))) ";
            }elseif($this->nbscache->get_olahan("permission", $this->session->userdata("id_privilege"), "proses_ro", "edit") !== FALSE){
                $qry .= "AND ((A.id_hr_master_organisasi='$dta_id' OR A.status >='3') OR (A.id_create_by_organisasi='$dta_id' OR A.status >='3')) ";
            }else{
                $qry .= "AND (A.id_hr_master_organisasi='$dta_id' OR A.id_create_by_organisasi='$dta_id')";
            }
        }
            
//        }
    
    if($this->nbscache->get_olahan("permission", $this->session->userdata("id_privilege"), "show-request", "edit") !== FALSE){    
    $where = "WHERE A.type_inventory = 8 AND (A.status >= 1 AND A.status < 10)";
    }elseif($this->session->userdata("id") == 1){
      $where = "WHERE A.type_inventory = 8";   
    }else{
    $where = "WHERE A.type_inventory = 8 {$qry} AND ((A.status >= 1 AND A.status < 10) OR A.status =11) ";   
    }
    
     $data = $this->global_models->get_query("SELECT *"
        . " FROM v_mrp_request AS A"
        . " {$where}"
        . " GROUP BY A.id_mrp_request"
        . " ORDER BY A.create_date DESC"
        . " LIMIT {$start}, 10"
        );
        
        
//    print  $this->db->last_query(); die;
//    $data_array = json_decode($data);
//    $this->debug($data, true);
    
//    $jenis = array(1 => "Habis Pakai", 2 => "Asset");
    $status = array( 1=> "<span class='label bg-orange'>Draft</span>", 2 => "<span class='label bg-blue'>Pengajuan</span>",
        3 => "<span class='label bg-green'>Approved</span>",4 => "<span class='label bg-green'>Task Order</span>",
        5 => "<span class='label bg-green'>Proses PO</span>", 6 => "<span class='label bg-green'>Sent PO</span>",
        7 => "<span class='label bg-green'>Partial</span>",8 => "<span class='label bg-green'>RG</span>",
        9 => "<span class='label bg-green'>Closed RO</span>", 10 => "<span class='label bg-red'>CANCEL RO</span>",
        11=> "<span class='label bg-black'>Reject RO</span>");
//    $status = array(1 => "Create", 2 => "Approve");
//    $type_inventory = array(1 => "Pengadaan Cetakan", 2 => "Pengadaan ATK");
   $no = 0;
   $hide = 0;
    foreach ($data AS $ky => $da){
            $dt_name = "";
        if($da->name){
            $dt_name = "<br>Approved:".$da->name;
        }
        $receiver = "";
        if($da->id_pegawai_receiver){
            $receiver = "<br><b>Penerima Barang</b>:".$da->name_receiver."<".$da->nama_department_receiver.">";
        }
        $note_warning = $this->global_models->get_field("mrp_request","note_warning",array("id_mrp_request" => $da->id_mrp_request));
       if($da->status >= 9 AND $da->status <= 10){
           $btn_del = "";
       }else{
            if($da->create_by_users == $id_users OR $id_users == 1 OR $this->nbscache->get_olahan("permission", $this->session->userdata("id_privilege"), "btn-delete", "edit") !== FALSE){
           $btn_del = "<a href='javascript:void(0)' data-toggle='modal' type='button' class='btn btn-danger btn-sm' title='Delete' data-target='#edit-keterangan-cancel' isi='{$da->id_mrp_request}' id='id-customer-cancel' ><i class='fa fa-trash-o'></i></a>";
            }
        }
       if($da->status == 1 OR $da->status == 11){
           if($da->create_by_users == $id_users OR $id_users == 1){
               $hasil[] = array(
                date('d-M-Y H:i:s', strtotime($da->create_date))."<br>Kode Request:<b>".$da->ro_code."</b>",  
                $da->nama_pegawai."<br>".$da->nip,
                $da->perusahaan."<br>Department:".$da->department,
                $da->note."<br>".$note_warning,
                $status[$da->status].$dt_name,
                "<a href='".site_url("mrp/mrp-task-orders/add-task-orders/{$da->id_mrp_task_orders}")."'>{$da->code}</a>",
                $da->create_by.$receiver,        
                "<div class='btn-group'>"
                  . "<a href='".site_url("mrp/mrp-request/add-request-pengadaan-promosi/{$da->id_mrp_request}")."' title='Edit Request Orders' type='button' class='btn btn-info btn-sm'><i class='fa fa-edit'></i></a>"
                  . $btn_del
                . "</div>"
              );
                $hide++;  
           }
            
       }else{
           $hasil[] = array(
                date('d-M-Y H:i:s', strtotime($da->create_date))."<br>Kode Request:<b>".$da->ro_code."</b>",  
                $da->nama_pegawai."<br>".$da->nip,
                $da->perusahaan."<br>Department:".$da->department,
                $da->note."<br>".$note_warning,
                $status[$da->status].$dt_name,
                "<a href='".site_url("mrp/mrp-task-orders/add-task-orders/{$da->id_mrp_task_orders}")."'>{$da->code}</a>",
                $da->create_by.$receiver,
                "<div class='btn-group'>"
                  . "<a href='".site_url("mrp/mrp-request/add-request-pengadaan-promosi/{$da->id_mrp_request}")."' title='Edit Request Orders' type='button' class='btn btn-info btn-sm'><i class='fa fa-edit'></i></a>"
                . $btn_del
                . "</div>"
              );
                  $no++;
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
  
    function get_mrp_request_pengadaan_komputer($start = 0,$id_users = 0){
      
      $dta_id = $this->global_models->get_field("hr_pegawai", "id_hr_master_organisasi", array("id_users" => $id_users));
      $hr_pegawai = $this->global_models->get("hr_master_organisasi", array("parent" => "{$dta_id}"));

        $no = 0;
        $aa = $dta_id;
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
    
        $qry = ""; 

        if($id_users == 1){
            $qry = "";
        }else{
            if($aa){
                $qry .= "AND ((A.id_hr_master_organisasi='$dta_id' OR A.id_hr_master_organisasi IN ($aa)) OR (A.id_create_by_organisasi='$dta_id' OR A.id_create_by_organisasi IN ($aa))) ";
            }elseif($this->nbscache->get_olahan("permission", $this->session->userdata("id_privilege"), "proses_ro", "edit") !== FALSE){
                $qry .= "AND ((A.id_hr_master_organisasi='$dta_id' OR A.status >='3') OR (A.id_create_by_organisasi='$dta_id' OR A.status >='3')) ";
            }else{
                $qry .= "AND (A.id_hr_master_organisasi='$dta_id' OR A.id_create_by_organisasi='$dta_id')";
            }
        }
            
//        }
    
    if($this->nbscache->get_olahan("permission", $this->session->userdata("id_privilege"), "show-request", "edit") !== FALSE){    
    $where = "WHERE A.type_inventory = 3 AND (A.status >= 1 AND A.status < 10)";
    }elseif($this->session->userdata("id") == 1){
      $where = "WHERE A.type_inventory = 3";   
    }else{
    $where = "WHERE A.type_inventory = 3 {$qry} AND ((A.status >= 1 AND A.status < 10) OR A.status =11) ";   
    }
    
     $data = $this->global_models->get_query("SELECT *"
        . " FROM v_mrp_request AS A"
        . " {$where}"
        . " GROUP BY A.id_mrp_request"
        . " ORDER BY A.create_date DESC"
        . " LIMIT {$start}, 10"
        );
        
        
//    print  $this->db->last_query(); die;
//    $data_array = json_decode($data);
//    $this->debug($data, true);
    
//    $jenis = array(1 => "Habis Pakai", 2 => "Asset");
    $status = array( 1=> "<span class='label bg-orange'>Draft</span>", 2 => "<span class='label bg-blue'>Pengajuan</span>",
        3 => "<span class='label bg-green'>Approved</span>",4 => "<span class='label bg-green'>Task Order</span>",
        5 => "<span class='label bg-green'>Proses PO</span>", 6 => "<span class='label bg-green'>Sent PO</span>",
        7 => "<span class='label bg-green'>Partial</span>",8 => "<span class='label bg-green'>RG</span>",
        9 => "<span class='label bg-green'>Closed RO</span>", 10 => "<span class='label bg-red'>CANCEL RO</span>",
        11=> "<span class='label bg-black'>Reject RO</span>");
//    $status = array(1 => "Create", 2 => "Approve");
//    $type_inventory = array(1 => "Pengadaan Cetakan", 2 => "Pengadaan ATK");
   $no = 0;
   $hide = 0;
    foreach ($data AS $ky => $da){
            $dt_name = "";
        if($da->name){
            $dt_name = "<br>Approved:".$da->name;
        }
        $receiver = "";
        if($da->id_pegawai_receiver){
            $receiver = "<br><b>Penerima Barang</b>:".$da->name_receiver."<".$da->nama_department_receiver.">";
        }
        $note_warning = $this->global_models->get_field("mrp_request","note_warning",array("id_mrp_request" => $da->id_mrp_request));
       if($da->status >= 9 AND $da->status <= 10){
           $btn_del = "";
       }else{
            if($da->create_by_users == $id_users OR $id_users == 1 OR $this->nbscache->get_olahan("permission", $this->session->userdata("id_privilege"), "btn-delete", "edit") !== FALSE){
           $btn_del = "<a href='javascript:void(0)' data-toggle='modal' type='button' class='btn btn-danger btn-sm' title='Delete' data-target='#edit-keterangan-cancel' isi='{$da->id_mrp_request}' id='id-customer-cancel' ><i class='fa fa-trash-o'></i></a>";
            }
        }
       if($da->status == 1 OR $da->status == 11){
           if($da->create_by_users == $id_users OR $id_users == 1){
               $hasil[] = array(
                date('d-M-Y H:i:s', strtotime($da->create_date))."<br>Kode Request:<b>".$da->ro_code."</b>",  
                $da->nama_pegawai."<br>".$da->nip,
                $da->perusahaan."<br>Department:".$da->department,
                $da->note."<br>".$note_warning,
                $status[$da->status].$dt_name,
                "<a href='".site_url("mrp/mrp-task-orders/add-task-orders/{$da->id_mrp_task_orders}")."'>{$da->code}</a>",
                $da->create_by.$receiver,        
                "<div class='btn-group'>"
                  . "<a href='".site_url("mrp/mrp-request/add-request-pengadaan-komputer/{$da->id_mrp_request}")."' title='Edit Request Orders' type='button' class='btn btn-info btn-sm'><i class='fa fa-edit'></i></a>"
                  . $btn_del
                . "</div>"
              );
                $hide++;  
           }
            
       }else{
           $hasil[] = array(
                date('d-M-Y H:i:s', strtotime($da->create_date))."<br>Kode Request:<b>".$da->ro_code."</b>",  
                $da->nama_pegawai."<br>".$da->nip,
                $da->perusahaan."<br>Department:".$da->department,
                $da->note."<br>".$note_warning,
                $status[$da->status].$dt_name,
                "<a href='".site_url("mrp/mrp-task-orders/add-task-orders/{$da->id_mrp_task_orders}")."'>{$da->code}</a>",
                $da->create_by.$receiver,
                "<div class='btn-group'>"
                  . "<a href='".site_url("mrp/mrp-request/add-request-pengadaan-komputer/{$da->id_mrp_request}")."' title='Edit Request Orders' type='button' class='btn btn-info btn-sm'><i class='fa fa-edit'></i></a>"
                . $btn_del
                . "</div>"
              );
                  $no++;
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
  
   function draft_form_mrp_request_pengadaan($type_inventory = 0){
      // $type_inventory 1 = Cetakan, 2 = ATK,3 = komputer , 4 = tehnikal, 5 = service;
       
     $pst = $_POST;
     $id_spesifik = $pst['id_spesifik'];
     $jumlah =  $pst['jumlah'];
     $note =  $pst['note'];
     $id_pegawai =  $pst['id_hr_pegawai'];
     $id_mrp_request =  $pst['id_mrp_request'];
     $id_receiving = $pst['id_receiver'];
     
    $arr_id = explode(",",$id_spesifik);
    $arr_jumlah = explode(",",$jumlah);
    $id_hr_pegawai = $this->global_models->get_field("hr_pegawai", "id_hr_pegawai", 
                    array("id_users" => $this->session->userdata("id")));
//    $aa = array($id_spesifik,$jumlah,$note,$id_mrp_request);
//      print_r($aa); die;    
      
      $received =$this->global_models->get_field("hr_pegawai", "id_hr_pegawai",
           array("id_users" => "{$this->session->userdata("id")}"));
    if($id_receiving){
        $id_receiver = $id_receiving;
    }else{
        $id_receiver = $received;
    }  
    
    if($id_mrp_request > 0){
        
         $id_hr_pegawai = $this->global_models->get_field("mrp_request", "id_hr_pegawai", 
                    array("id_mrp_request" => $id_mrp_request));
       
        if($id_pegawai != 0){
        $dt_hr_pegawai = $id_pegawai;
        }else{
        $dt_hr_pegawai = $id_hr_pegawai;
        }
        
        $kirim = array(
            "id_hr_pegawai"               => $dt_hr_pegawai,
            "user_pegawai_receiver"       => $id_receiver,
            "note"                        => $note,
            "status"                      => 2,
            "update_by_users"             => $this->session->userdata("id"),
            "update_date"                 => date("Y-m-d H:i:s")
        );
        $this->global_models->update("mrp_request", array("id_mrp_request" => $id_mrp_request),$kirim);
		
		if($type_inventory == 10){
            $kirim1 = array(
            "id_mrp_request"                => $id_mrp_request,
            "type"                          => 1,
            "status"                        => 1,
            "create_by_users"               => $this->session->userdata("id"),
            "create_date"                   => date("Y-m-d H:i:s")
        );
        $this->global_models->insert("temp_alert_email", $kirim1);
       
        }
        
        $this->global_models->delete("mrp_request_asset", array("id_mrp_request" => $id_mrp_request));
        foreach ($arr_jumlah as $key => $val2) {
            if($val2 > 0){
                    $kirim = array(
                    "id_mrp_request"                => $id_mrp_request,
                    "id_mrp_inventory_spesifik"     => $arr_id[$key],
                    "jumlah"                        => $val2,   
                    "status"                        => 2,
                    "create_by_users"               => $this->session->userdata("id"),
                    "create_date"                   => date("Y-m-d H:i:s")
                );
                $this->global_models->insert("mrp_request_asset", $kirim);
            }
        }
    }else{
        if($id_pegawai != 0){
        $dt_hr_pegawai = $id_pegawai;
        }else{
        $dt_hr_pegawai = $id_hr_pegawai;
        }
        $this->olah_request_order_code($kode);
        $kirim = array(
           "id_hr_pegawai"                => $dt_hr_pegawai,
            "note"                        => $note,
            "code"                        => $kode,
            "user_pegawai_receiver"       => $id_receiver,
            "status"                      => 2,
            "status_blast"                => 3,
            "type_inventory"              => $type_inventory,
            "create_by_users"             => $this->session->userdata("id"),
            "create_date"                 => date("Y-m-d H:i:s")
        );
        $id_mrp_request = $this->global_models->insert("mrp_request", $kirim);
        
        if($type_inventory == 10){
            $kirim1 = array(
            "id_mrp_request"                => $id_mrp_request,
            "type"                          => 1,
            "status"                        => 1,
            "create_by_users"               => $this->session->userdata("id"),
            "create_date"                   => date("Y-m-d H:i:s")
        );
        $this->global_models->insert("temp_alert_email", $kirim1);
       
        }
        
         foreach ($arr_jumlah as $key => $val) {
            if($val > 0){
                $kirim = array(
                "id_mrp_request"                => $id_mrp_request,
                "id_mrp_inventory_spesifik"     => $arr_id[$key],
                "jumlah"                        => $val,   
                "status"                        => 2,
                "create_by_users"               => $this->session->userdata("id"),
                "create_date"                   => date("Y-m-d H:i:s")
            );
            $this->global_models->insert("mrp_request_asset", $kirim);
            }
        }
    }
     
   
//    if($id_mrp_request > 0){
        $this->session->set_flashdata('success', 'Data tersimpan');
        
//    }
//     $array =array($cc,$kk,$note);
//     print_r($array);
//         $return['hasil'] = 1;
//    $this->debug($return, true);
//    print json_encode($return);
    die;
  }
  
      function update_form_mrp_request_pengadaan($type_inventory = 0){
     // $type_inventory 1 = Cetakan, 2 = ATK;
        $pst = $_POST;
    $id_spesifik = $pst['id_spesifik'];
    $jumlah =  $pst['jumlah'];
    $jumlah_rg =  $pst['jumlah_rg'];
    $note =  $pst['note'];
    $id_pegawai =  $pst['id_hr_pegawai'];
    $id_mrp_request =  $pst['id_mrp_request'];
    $id_receiving = $pst['id_receiver'];
     
    $arr_id = explode(",",$id_spesifik);
    $arr_jumlah = explode(",",$jumlah);
    $arr_jumlah_rg = explode(",",$jumlah_rg);
    $id_hr_pegawai = $this->global_models->get_field("hr_pegawai", "id_hr_pegawai", 
                    array("id_users" => $this->session->userdata("id")));
    
    $status = $this->global_models->get_field("mrp_request", "status", 
                    array("id_mrp_request" => $id_mrp_request));
    
//    $aa = array($id_spesifik,$jumlah,$note,$id_mrp_request);
//      print_r($aa); die;    
      
      $received =$this->global_models->get_field("hr_pegawai", "id_hr_pegawai",
           array("id_users" => "{$this->session->userdata("id")}"));
    if($id_receiving){
        $id_receiver = $id_receiving;
    }else{
        $id_receiver = $received;
    }  
    
    if($id_mrp_request > 0){
        if($id_pegawai !=0){
             $kirim = array(
            "id_hr_pegawai"              => $id_pegawai,
            "user_pegawai_receiver"              => $id_receiver,     
            "note"                        => $note,
            "status"                       => $status,
            "update_by_users"               => $this->session->userdata("id"),
            "update_date"                   => date("Y-m-d H:i:s")
            );
            $this->global_models->update("mrp_request", array("id_mrp_request" => $id_mrp_request),$kirim);
//            print $this->db->last_query();
//            die('cc');
        }else{
            $kirim = array(
            "note"                        => $note,
            "user_pegawai_receiver"        => $id_receiver,         
            "status"                       => $status,
            "update_by_users"               => $this->session->userdata("id"),
            "update_date"                   => date("Y-m-d H:i:s")
            );
            $this->global_models->update("mrp_request", array("id_mrp_request" => $id_mrp_request),$kirim);
        
//            print $this->db->last_query();
//            die('kk');
        }
       
        $this->global_models->delete("mrp_request_asset", array("id_mrp_request" => $id_mrp_request));
        foreach ($arr_jumlah as $key => $val2) {
            if($val2 > 0){
                    $kirim = array(
                    "id_mrp_request"                => $id_mrp_request,
                    "id_mrp_inventory_spesifik"     => $arr_id[$key],
                    "jumlah"                        => $val2,
                    "rg"                            => $arr_jumlah_rg[$key],    
                    "status"                        => $status,
                    "create_by_users"               => $this->session->userdata("id"),
                    "create_date"                   => date("Y-m-d H:i:s")
                );
                $this->global_models->insert("mrp_request_asset", $kirim);
            }
        }
    }
                  
//    if($id_mrp_request > 0){
        $this->session->set_flashdata('success', 'Data tersimpan');
        
//    }
//     $array =array($cc,$kk,$note);
//     print_r($array);
//         $return['hasil'] = 1;
//    $this->debug($return, true);
//    print json_encode($return);
    die;
  }
  
   function insert_form_mrp_request_pengadaan($type_inventory = 0){
     // $type_inventory 1 = Cetakan, 2 = ATK,3 = komputer , 4 = tehnikal, 5 = service;
      $pst = $_POST;
   $id_spesifik = $pst['id_spesifik'];
     $jumlah =  $pst['jumlah'];
     $note =  $pst['note'];
     $id_pegawai =  $pst['id_hr_pegawai'];
    $id_mrp_request =  $pst['id_mrp_request'];
    $id_receiving = $pst['id_receiver'];
     
    $arr_id = explode(",",$id_spesifik);
    $arr_jumlah = explode(",",$jumlah);
    $arr_id_mrp_request = explode(",",$id_mrp_request);
    $id_hr_pegawai = $this->global_models->get_field("hr_pegawai", "id_hr_pegawai", 
                    array("id_users" => $this->session->userdata("id")));
//    $aa = array($id_spesifik,$jumlah,$note,$id_mrp_request);
//      print_r($aa); die;    
//      
   
   $received =$this->global_models->get_field("hr_pegawai", "id_hr_pegawai",
           array("id_users" => "{$this->session->userdata("id")}"));
    if($id_receiving){
        $id_receiver = $id_receiving;
    }else{
        $id_receiver = $received;
    }  
    
    if($id_mrp_request > 0){
        
       $id_hr_pegawai = $this->global_models->get_field("mrp_request", "id_hr_pegawai", 
                    array("id_mrp_request" => $id_mrp_request));
       
        if($id_pegawai != 0){
        $dt_hr_pegawai = $id_pegawai;
        }else{
        $dt_hr_pegawai = $id_hr_pegawai;
        }
        
        $kirim = array(
           "id_hr_pegawai"                => $dt_hr_pegawai,
            "user_pegawai_receiver"                => $id_receiver,
            "note"                        => $note,
            "update_by_users"               => $this->session->userdata("id"),
            "update_date"                   => date("Y-m-d H:i:s")
        );
        $this->global_models->update("mrp_request", array("id_mrp_request" => $id_mrp_request),$kirim);
//       print $this->db->last_query()."bb";
//       die;
        $this->global_models->delete("mrp_request_asset", array("id_mrp_request" => $id_mrp_request));
        foreach ($arr_jumlah as $key => $val2) {
            if($val2 > 0){
                    $kirim = array(
                    "id_mrp_request"                => $id_mrp_request,
                    "id_mrp_inventory_spesifik"     => $arr_id[$key],
                    "jumlah"                        => $val2,   
                    "status"                        => 1,
                    "create_by_users"               => $this->session->userdata("id"),
                    "create_date"                   => date("Y-m-d H:i:s")
                );
                $this->global_models->insert("mrp_request_asset", $kirim);
            }
        }
    }else{
        
        if($id_pegawai != 0){
        $dt_hr_pegawai = $id_pegawai;
        }else{
        $dt_hr_pegawai = $id_hr_pegawai;
        }
        
        $this->olah_request_order_code($kode);
        $kirim = array(
           "id_hr_pegawai"                => $dt_hr_pegawai,
            "user_pegawai_receiver"       => $id_receiver,
            "note"                        => $note,
            "code"                        => $kode,
            "status"                      => 1,
            "status_blast"                => 3,
            "type_inventory"              => $type_inventory,
            "create_by_users"             => $this->session->userdata("id"),
            "create_date"                 => date("Y-m-d H:i:s")
        );
        $id_mrp_request = $this->global_models->insert("mrp_request", $kirim);
//        print $this->db->last_query()."bb";
//       die;
         foreach ($arr_jumlah as $key => $val) {
            if($val > 0){
                $kirim = array(
                "id_mrp_request"                => $id_mrp_request,
                "id_mrp_inventory_spesifik"     => $arr_id[$key],
                "jumlah"                        => $val,   
                "status"                        => 1,
                "create_by_users"               => $this->session->userdata("id"),
                "create_date"                   => date("Y-m-d H:i:s")
            );
            $this->global_models->insert("mrp_request_asset", $kirim);
            }
        }
    }
     
   
//    if($id_mrp_request > 0){
        $this->session->set_flashdata('success', 'Data tersimpan');
        
//    }
//     $array =array($cc,$kk,$note);
//     print_r($array);
//         $return['hasil'] = 1;
//    $this->debug($return, true);
//    print json_encode($return);
    die;
  }
 
     function get_form_mrp_request_pengadaan_komputer($id_mrp_request = 0,$id_users = 0,$start = 0){
      
      $status = $this->global_models->get_field("mrp_request", "status", 
                    array("id_mrp_request" => "{$id_mrp_request}"));
                    
      $dt_user = $this->global_models->get_field("mrp_request", "create_by_users", 
                    array("id_mrp_request" => "{$id_mrp_request}"));              
                    
      $ls = $this->global_models->get_query("SELECT B.id_hr_company,B.id_hr_master_organisasi"
        . " FROM m_users AS A"
        . " LEFT JOIN hr_pegawai AS B ON A.id_users = B.id_users"
        . " WHERE A.id_users = '{$id_users}'");
//       print $this->db->last_query();
//       die;
      $where = "WHERE A.status = 1 AND B.id_mrp_type_inventory = 3";
      $disabled ="";
      
      if($id_mrp_request > 0){
          $where .= " AND jumlah IS NOT NULL";
      }
      
      if($status == 1 AND  $this->session->userdata("id") != $dt_user){
//          $where .= " AND jumlah IS NOT NULL";
          $disabled = "disabled ";  
      }elseif($status > 1){
//          $where .= " AND jumlah IS NOT NULL";
          $disabled = "disabled ";  
      }
//      
   
       if($this->session->userdata("id") == 1 OR ($status == 2 AND $this->nbscache->get_olahan("permission", $this->session->userdata("id_privilege"), "approval-ro", "edit") !== FALSE)){
       $disabled ="";
      }
      
      if($this->session->userdata("id") == 1 OR $status == 11 OR $this->nbscache->get_olahan("permission", $this->session->userdata("id_privilege"), "edit-form-ro", "edit") !== FALSE){
       $disabled ="";
      }
//      if($id_mrp_request > 0){
//          $join = " "
//                  . " ";
//          $field = ",E.jumlah";
//      }else{
//          $where . " AND 1=1 ";
//          $join = "";
//          $field = "";
//      }
       $data = $this->global_models->get_query("SELECT B.name AS inventory_umum,D.title AS satuan,A.title AS title_spesifik,"
        . "A.id_mrp_inventory_spesifik,C.title AS brand,E.jumlah,E.rg,E.id_mrp_request_asset AS id_spesifik,F.create_by_users,F.status"
        . " FROM mrp_inventory_spesifik AS A"
        . " LEFT JOIN mrp_inventory_umum AS B ON A.id_mrp_inventory_umum = B.id_mrp_inventory_umum"
        . " LEFT JOIN mrp_brand AS C ON A.id_mrp_brand = C.id_mrp_brand"
        . " LEFT JOIN mrp_satuan AS D ON A.id_mrp_satuan = D.id_mrp_satuan"
        . " LEFT JOIN mrp_request_asset AS E ON (A.id_mrp_inventory_spesifik = E.id_mrp_inventory_spesifik AND E.id_mrp_request = '{$id_mrp_request}')"
        . " LEFT JOIN mrp_request AS F ON E.id_mrp_request = F.id_mrp_request"
        . " {$where} "
        . " ORDER BY A.id_mrp_inventory_spesifik ASC"
        . " LIMIT {$start}, 10");
        
//       print $this->db->last_query(); die;
//    $data_array = json_decode($data);
//    $this->debug($data, true);
        
    
    if(count($data) > 0){
      $return['status'] = 2;
      $return['start']  = $start + 10;
    }
    else{
      $return['status'] = 3;
    }
    
//    $jenis = array(1 => "Habis Pakai", 2 => "Asset");
    $no = $start;
    $url = base_url()."themes/".DEFAULTTHEMES."/img/ajax-loader.gif";
    foreach ($data AS $da){
        if($da->jumlah){
            $jumlah = $da->jumlah;
        }else{
            $jumlah = 0;
        }
        
        if($da->rg){
            $rg = $da->rg;
        }else{
            $rg = 0;
        }
        
        $data_id = "id=dt_id_mrp_inventory_spesifik_".$da->id_mrp_inventory_spesifik;
        $data_jumlah = "id=jumlah".$da->id_mrp_inventory_spesifik;
        $data_jumlah_rg = "id=jumlah_rg".$da->id_mrp_inventory_spesifik;
        if($da->id_mrp_setting_lock_atk){
            $dt_jumlah = $this->form_eksternal->form_input("jumlah[]", $jumlah, $data_jumlah.'  style="width:100px;display:none" class="form-control jumlah input-sm" placeholder="Jumlah"').
            $this->form_eksternal->form_input("jumlah_rg[]", $rg, $data_jumlah_rg.'  style="width:100px;display:none" class="form-control jumlah_rg input-sm" placeholder="rg"');
        }else{
         
             $dt_jumlah = $this->form_eksternal->form_input("jumlah[]", $jumlah, $disabled.$data_jumlah.'  style="width:100px" class="form-control jumlah input-sm" placeholder="Jumlah"').
            $this->form_eksternal->form_input("jumlah_rg[]", $rg, $data_jumlah_rg.'  style="width:100px;display:none" class="form-control jumlah_rg input-sm" placeholder="rg"');
          
            
        }
        
        
        
        if($da->id_spesifik){
            if($id_users ==1 OR $da->create_by_users == $id_users OR $this->nbscache->get_olahan("permission", $this->session->userdata("id_privilege"), "edit-form-ro", "edit") !== FALSE OR $this->nbscache->get_olahan("permission", $this->session->userdata("id_privilege"), "approval-ro", "edit") !== FALSE){
        $btn_del =  "<div class='btn-group'>"
        . "<a href='javascript:void(0)' onclick='delete_request_pengadaan({$da->id_spesifik})' id='del_{$da->id_spesifik}' class='btn btn-danger btn-flat' style='width: 40px'><i class='fa fa-trash-o'></i></a>"
        ."<span style='display: none; margin-left: 10px;' id='img-page-{$da->id_spesifik}'><img width='35px' src='{$url}' /></span>"
        . "</div>";
            }
        }
        
        if($da->status < 6 OR $da->status == 11){
            $dt_jumlah = $dt_jumlah;
            $btn_del = $btn_del;
        }else{
            $dt_jumlah = $jumlah;
            $btn_del = "";
        }
        
      $hasil[] = array(
          $no =$no + 1,
        $da->inventory_umum." ".$da->title_spesifik,
        $da->brand,
        $da->satuan,
       $this->form_eksternal->form_input("id_mrp_inventory_spesifik[]", $da->id_mrp_inventory_spesifik, $data_id.' style="width:100px;display:none" class="form-control id_spesifik input-sm" placeholder=""').
       $rg, 
       $dt_jumlah, 
       $btn_del
      );
    }
    $return['hasil'] = $hasil;
//    $this->debug($return, true);
    print json_encode($return);
    die;
  }
  
  function get_form_mrp_request_pengadaan_office($id_mrp_request = 0,$id_users = 0,$start = 0){
      
      $status = $this->global_models->get_field("mrp_request", "status", 
                    array("id_mrp_request" => "{$id_mrp_request}"));
                    
      $dt_user = $this->global_models->get_field("mrp_request", "create_by_users", 
                    array("id_mrp_request" => "{$id_mrp_request}"));              
                    
      $ls = $this->global_models->get_query("SELECT B.id_hr_company,B.id_hr_master_organisasi"
        . " FROM m_users AS A"
        . " LEFT JOIN hr_pegawai AS B ON A.id_users = B.id_users"
        . " WHERE A.id_users = '{$id_users}'");
//       print $this->db->last_query();
//       die;
      $where = "WHERE A.status = 1 AND B.id_mrp_type_inventory = 7";
      $disabled ="";
      
      if($id_mrp_request > 0){
          $where .= " AND jumlah IS NOT NULL";
      }
      
      if($status == 1 AND  $this->session->userdata("id") != $dt_user){
//          $where .= " AND jumlah IS NOT NULL";
          $disabled = "disabled ";  
      }elseif($status > 1){
//          $where .= " AND jumlah IS NOT NULL";
          $disabled = "disabled ";  
      }
//      
   
       if($this->session->userdata("id") == 1 OR ($status == 2 AND $this->nbscache->get_olahan("permission", $this->session->userdata("id_privilege"), "approval-ro", "edit") !== FALSE)){
       $disabled ="";
      }
      
      if($this->session->userdata("id") == 1 OR $status == 11 OR $this->nbscache->get_olahan("permission", $this->session->userdata("id_privilege"), "edit-form-ro", "edit") !== FALSE){
       $disabled ="";
      }
//      if($id_mrp_request > 0){
//          $join = " "
//                  . " ";
//          $field = ",E.jumlah";
//      }else{
//          $where . " AND 1=1 ";
//          $join = "";
//          $field = "";
//      }
       $data = $this->global_models->get_query("SELECT B.name AS inventory_umum,D.title AS satuan,A.title AS title_spesifik,"
        . "A.id_mrp_inventory_spesifik,C.title AS brand,E.jumlah,E.rg,E.id_mrp_request_asset AS id_spesifik,F.create_by_users,F.status"
        . " FROM mrp_inventory_spesifik AS A"
        . " LEFT JOIN mrp_inventory_umum AS B ON A.id_mrp_inventory_umum = B.id_mrp_inventory_umum"
        . " LEFT JOIN mrp_brand AS C ON A.id_mrp_brand = C.id_mrp_brand"
        . " LEFT JOIN mrp_satuan AS D ON A.id_mrp_satuan = D.id_mrp_satuan"
        . " LEFT JOIN mrp_request_asset AS E ON (A.id_mrp_inventory_spesifik = E.id_mrp_inventory_spesifik AND E.id_mrp_request = '{$id_mrp_request}')"
        . " LEFT JOIN mrp_request AS F ON E.id_mrp_request = F.id_mrp_request"
        . " {$where} "
        . " ORDER BY A.id_mrp_inventory_spesifik ASC"
        . " LIMIT {$start}, 10");
        
//       print $this->db->last_query(); die;
//    $data_array = json_decode($data);
//    $this->debug($data, true);
        
    
    if(count($data) > 0){
      $return['status'] = 2;
      $return['start']  = $start + 10;
    }
    else{
      $return['status'] = 3;
    }
    
//    $jenis = array(1 => "Habis Pakai", 2 => "Asset");
    $no = $start;
    $url = base_url()."themes/".DEFAULTTHEMES."/img/ajax-loader.gif";
    foreach ($data AS $da){
        if($da->jumlah){
            $jumlah = $da->jumlah;
        }else{
            $jumlah = 0;
        }
        
        if($da->rg){
            $rg = $da->rg;
        }else{
            $rg = 0;
        }
        
        $data_id = "id=dt_id_mrp_inventory_spesifik_".$da->id_mrp_inventory_spesifik;
        $data_jumlah = "id=jumlah".$da->id_mrp_inventory_spesifik;
        $data_jumlah_rg = "id=jumlah_rg".$da->id_mrp_inventory_spesifik;
        if($da->id_mrp_setting_lock_atk){
            $dt_jumlah = $this->form_eksternal->form_input("jumlah[]", $jumlah, $data_jumlah.'  style="width:100px;display:none" class="form-control jumlah input-sm" placeholder="Jumlah"').
            $this->form_eksternal->form_input("jumlah_rg[]", $rg, $data_jumlah_rg.'  style="width:100px;display:none" class="form-control jumlah_rg input-sm" placeholder="rg"');
        }else{
         
             $dt_jumlah = $this->form_eksternal->form_input("jumlah[]", $jumlah, $disabled.$data_jumlah.'  style="width:100px" class="form-control jumlah input-sm" placeholder="Jumlah"').
            $this->form_eksternal->form_input("jumlah_rg[]", $rg, $data_jumlah_rg.'  style="width:100px;display:none" class="form-control jumlah_rg input-sm" placeholder="rg"');
          
            
        }
        
        
        if($da->id_spesifik){
            if($id_users ==1 OR $da->create_by_users == $id_users OR $this->nbscache->get_olahan("permission", $this->session->userdata("id_privilege"), "edit-form-ro", "edit") !== FALSE OR $this->nbscache->get_olahan("permission", $this->session->userdata("id_privilege"), "approval-ro", "edit") !== FALSE){
        $btn_del =  "<div class='btn-group'>"
        . "<a href='javascript:void(0)' onclick='delete_request_pengadaan({$da->id_spesifik})' id='del_{$da->id_spesifik}' class='btn btn-danger btn-flat' style='width: 40px'><i class='fa fa-trash-o'></i></a>"
        ."<span style='display: none; margin-left: 10px;' id='img-page-{$da->id_spesifik}'><img width='35px' src='{$url}' /></span>"
        . "</div>";
            }
        }
        
        if($da->status < 6 OR $da->status == 11){
            $dt_jumlah = $dt_jumlah;
            $btn_del = $btn_del;
        }else{
            $dt_jumlah = $jumlah;
            $btn_del = "";
        }
        
      $hasil[] = array(
          $no =$no + 1,
        $da->inventory_umum." ".$da->title_spesifik,
        $da->brand,
        $da->satuan,
       $this->form_eksternal->form_input("id_mrp_inventory_spesifik[]", $da->id_mrp_inventory_spesifik, $data_id.' style="width:100px;display:none" class="form-control id_spesifik input-sm" placeholder=""').
       $rg, 
       $dt_jumlah, 
       $btn_del
      );
    }
    $return['hasil'] = $hasil;
//    $this->debug($return, true);
    print json_encode($return);
    die;
  }
  
  function get_form_mrp_request_pengadaan_promosi($id_mrp_request = 0,$id_users = 0,$start = 0){
      
      $status = $this->global_models->get_field("mrp_request", "status", 
                    array("id_mrp_request" => "{$id_mrp_request}"));
                    
      $dt_user = $this->global_models->get_field("mrp_request", "create_by_users", 
                    array("id_mrp_request" => "{$id_mrp_request}"));              
                    
      $ls = $this->global_models->get_query("SELECT B.id_hr_company,B.id_hr_master_organisasi"
        . " FROM m_users AS A"
        . " LEFT JOIN hr_pegawai AS B ON A.id_users = B.id_users"
        . " WHERE A.id_users = '{$id_users}'");
//       print $this->db->last_query();
//       die;
      $where = "WHERE A.status = 1 AND B.id_mrp_type_inventory = 8";
      $disabled ="";
      
      if($id_mrp_request > 0){
          $where .= " AND jumlah IS NOT NULL";
      }
      
      if($status == 1 AND  $this->session->userdata("id") != $dt_user){
//          $where .= " AND jumlah IS NOT NULL";
          $disabled = "disabled ";  
      }elseif($status > 1){
//          $where .= " AND jumlah IS NOT NULL";
          $disabled = "disabled ";  
      }
//      
   
       if($this->session->userdata("id") == 1 OR ($status == 2 AND $this->nbscache->get_olahan("permission", $this->session->userdata("id_privilege"), "approval-ro", "edit") !== FALSE)){
       $disabled ="";
      }
      
      if($this->session->userdata("id") == 1 OR $status == 11 OR $this->nbscache->get_olahan("permission", $this->session->userdata("id_privilege"), "edit-form-ro", "edit") !== FALSE){
       $disabled ="";
      }
//      if($id_mrp_request > 0){
//          $join = " "
//                  . " ";
//          $field = ",E.jumlah";
//      }else{
//          $where . " AND 1=1 ";
//          $join = "";
//          $field = "";
//      }
       $data = $this->global_models->get_query("SELECT B.name AS inventory_umum,D.title AS satuan,A.title AS title_spesifik,"
        . "A.id_mrp_inventory_spesifik,C.title AS brand,E.jumlah,E.rg,E.id_mrp_request_asset AS id_spesifik,F.create_by_users,F.status"
        . " FROM mrp_inventory_spesifik AS A"
        . " LEFT JOIN mrp_inventory_umum AS B ON A.id_mrp_inventory_umum = B.id_mrp_inventory_umum"
        . " LEFT JOIN mrp_brand AS C ON A.id_mrp_brand = C.id_mrp_brand"
        . " LEFT JOIN mrp_satuan AS D ON A.id_mrp_satuan = D.id_mrp_satuan"
        . " LEFT JOIN mrp_request_asset AS E ON (A.id_mrp_inventory_spesifik = E.id_mrp_inventory_spesifik AND E.id_mrp_request = '{$id_mrp_request}')"
        . " LEFT JOIN mrp_request AS F ON E.id_mrp_request = F.id_mrp_request"
        . " {$where} "
        . " ORDER BY A.id_mrp_inventory_spesifik ASC"
        . " LIMIT {$start}, 10");
        
//       print $this->db->last_query(); die;
//    $data_array = json_decode($data);
//    $this->debug($data, true);
        
    
    if(count($data) > 0){
      $return['status'] = 2;
      $return['start']  = $start + 10;
    }
    else{
      $return['status'] = 3;
    }
    
//    $jenis = array(1 => "Habis Pakai", 2 => "Asset");
    $no = $start;
    $url = base_url()."themes/".DEFAULTTHEMES."/img/ajax-loader.gif";
    foreach ($data AS $da){
        if($da->jumlah){
            $jumlah = $da->jumlah;
        }else{
            $jumlah = 0;
        }
        
        if($da->rg){
            $rg = $da->rg;
        }else{
            $rg = 0;
        }
        
        $data_id = "id=dt_id_mrp_inventory_spesifik_".$da->id_mrp_inventory_spesifik;
        $data_jumlah = "id=jumlah".$da->id_mrp_inventory_spesifik;
        $data_jumlah_rg = "id=jumlah_rg".$da->id_mrp_inventory_spesifik;
        if($da->id_mrp_setting_lock_atk){
            $dt_jumlah = $this->form_eksternal->form_input("jumlah[]", $jumlah, $data_jumlah.'  style="width:100px;display:none" class="form-control jumlah input-sm" placeholder="Jumlah"').
            $this->form_eksternal->form_input("jumlah_rg[]", $rg, $data_jumlah_rg.'  style="width:100px;display:none" class="form-control jumlah_rg input-sm" placeholder="rg"');
        }else{
         
             $dt_jumlah = $this->form_eksternal->form_input("jumlah[]", $jumlah, $disabled.$data_jumlah.'  style="width:100px" class="form-control jumlah input-sm" placeholder="Jumlah"').
            $this->form_eksternal->form_input("jumlah_rg[]", $rg, $data_jumlah_rg.'  style="width:100px;display:none" class="form-control jumlah_rg input-sm" placeholder="rg"');
          
            
        }
        
        
        if($da->id_spesifik){
            if($id_users ==1 OR $da->create_by_users == $id_users OR $this->nbscache->get_olahan("permission", $this->session->userdata("id_privilege"), "edit-form-ro", "edit") !== FALSE OR $this->nbscache->get_olahan("permission", $this->session->userdata("id_privilege"), "approval-ro", "edit") !== FALSE){
        $btn_del =  "<div class='btn-group'>"
        . "<a href='javascript:void(0)' onclick='delete_request_pengadaan({$da->id_spesifik})' id='del_{$da->id_spesifik}' class='btn btn-danger btn-flat' style='width: 40px'><i class='fa fa-trash-o'></i></a>"
        ."<span style='display: none; margin-left: 10px;' id='img-page-{$da->id_spesifik}'><img width='35px' src='{$url}' /></span>"
        . "</div>";
            }
        }
        
        if($da->status < 6 OR $da->status == 11){
            $dt_jumlah = $dt_jumlah;
            $btn_del = $btn_del;
        }else{
            $dt_jumlah = $jumlah;
            $btn_del = "";
        }
        
      $hasil[] = array(
          $no =$no + 1,
        $da->inventory_umum." ".$da->title_spesifik,
        $da->brand,
        $da->satuan,
       $this->form_eksternal->form_input("id_mrp_inventory_spesifik[]", $da->id_mrp_inventory_spesifik, $data_id.' style="width:100px;display:none" class="form-control id_spesifik input-sm" placeholder=""').
       $rg, 
       $dt_jumlah, 
       $btn_del
      );
    }
    $return['hasil'] = $hasil;
//    $this->debug($return, true);
    print json_encode($return);
    die;
  }
  
    function get_form_mrp_request_pengadaan_umum($id_mrp_request = 0,$id_users = 0,$start = 0){
      
      $status = $this->global_models->get_field("mrp_request", "status", 
                    array("id_mrp_request" => "{$id_mrp_request}"));
                    
      $dt_user = $this->global_models->get_field("mrp_request", "create_by_users", 
                    array("id_mrp_request" => "{$id_mrp_request}"));              
                    
      $ls = $this->global_models->get_query("SELECT B.id_hr_company,B.id_hr_master_organisasi"
        . " FROM m_users AS A"
        . " LEFT JOIN hr_pegawai AS B ON A.id_users = B.id_users"
        . " WHERE A.id_users = '{$id_users}'");
//       print $this->db->last_query();
//       die;
      $where = "WHERE A.status = 1 AND B.id_mrp_type_inventory = 9";
      $disabled ="";
      
      if($id_mrp_request > 0){
          $where .= " AND jumlah IS NOT NULL";
      }
      
      if($status == 1 AND  $this->session->userdata("id") != $dt_user){
//          $where .= " AND jumlah IS NOT NULL";
          $disabled = "disabled ";  
      }elseif($status > 1){
//          $where .= " AND jumlah IS NOT NULL";
          $disabled = "disabled ";  
      }
//      
   
       if($this->session->userdata("id") == 1 OR ($status == 2 AND $this->nbscache->get_olahan("permission", $this->session->userdata("id_privilege"), "approval-ro", "edit") !== FALSE)){
       $disabled ="";
      }
      
      if($this->session->userdata("id") == 1 OR $status == 11 OR $this->nbscache->get_olahan("permission", $this->session->userdata("id_privilege"), "edit-form-ro", "edit") !== FALSE){
       $disabled ="";
      }
//      if($id_mrp_request > 0){
//          $join = " "
//                  . " ";
//          $field = ",E.jumlah";
//      }else{
//          $where . " AND 1=1 ";
//          $join = "";
//          $field = "";
//      }
       $data = $this->global_models->get_query("SELECT B.name AS inventory_umum,D.title AS satuan,A.title AS title_spesifik,"
        . "A.id_mrp_inventory_spesifik,C.title AS brand,E.jumlah,E.rg,E.id_mrp_request_asset AS id_spesifik,F.create_by_users,F.status"
        . " FROM mrp_inventory_spesifik AS A"
        . " LEFT JOIN mrp_inventory_umum AS B ON A.id_mrp_inventory_umum = B.id_mrp_inventory_umum"
        . " LEFT JOIN mrp_brand AS C ON A.id_mrp_brand = C.id_mrp_brand"
        . " LEFT JOIN mrp_satuan AS D ON A.id_mrp_satuan = D.id_mrp_satuan"
        . " LEFT JOIN mrp_request_asset AS E ON (A.id_mrp_inventory_spesifik = E.id_mrp_inventory_spesifik AND E.id_mrp_request = '{$id_mrp_request}')"
        . " LEFT JOIN mrp_request AS F ON E.id_mrp_request = F.id_mrp_request"
        . " {$where} "
        . " ORDER BY A.id_mrp_inventory_spesifik ASC"
        . " LIMIT {$start}, 10");
        
//       print $this->db->last_query(); die;
//    $data_array = json_decode($data);
//    $this->debug($data, true);
        
    
    if(count($data) > 0){
      $return['status'] = 2;
      $return['start']  = $start + 10;
    }
    else{
      $return['status'] = 3;
    }
    
//    $jenis = array(1 => "Habis Pakai", 2 => "Asset");
    $no = $start;
    $url = base_url()."themes/".DEFAULTTHEMES."/img/ajax-loader.gif";
    foreach ($data AS $da){
        if($da->jumlah){
            $jumlah = $da->jumlah;
        }else{
            $jumlah = 0;
        }
        
        if($da->rg){
            $rg = $da->rg;
        }else{
            $rg = 0;
        }
        
        $data_id = "id=dt_id_mrp_inventory_spesifik_".$da->id_mrp_inventory_spesifik;
        $data_jumlah = "id=jumlah".$da->id_mrp_inventory_spesifik;
        $data_jumlah_rg = "id=jumlah_rg".$da->id_mrp_inventory_spesifik;
        if($da->id_mrp_setting_lock_atk){
            $dt_jumlah = $this->form_eksternal->form_input("jumlah[]", $jumlah, $data_jumlah.'  style="width:100px;display:none" class="form-control jumlah input-sm" placeholder="Jumlah"').
            $this->form_eksternal->form_input("jumlah_rg[]", $rg, $data_jumlah_rg.'  style="width:100px;display:none" class="form-control jumlah_rg input-sm" placeholder="rg"');
        }else{
         
             $dt_jumlah = $this->form_eksternal->form_input("jumlah[]", $jumlah, $disabled.$data_jumlah.'  style="width:100px" class="form-control jumlah input-sm" placeholder="Jumlah"').
            $this->form_eksternal->form_input("jumlah_rg[]", $rg, $data_jumlah_rg.'  style="width:100px;display:none" class="form-control jumlah_rg input-sm" placeholder="rg"');
          
            
        }
        
        
        if($da->id_spesifik){
            if($id_users ==1 OR $da->create_by_users == $id_users OR $this->nbscache->get_olahan("permission", $this->session->userdata("id_privilege"), "edit-form-ro", "edit") !== FALSE OR $this->nbscache->get_olahan("permission", $this->session->userdata("id_privilege"), "approval-ro", "edit") !== FALSE){
        $btn_del =  "<div class='btn-group'>"
        . "<a href='javascript:void(0)' onclick='delete_request_pengadaan({$da->id_spesifik})' id='del_{$da->id_spesifik}' class='btn btn-danger btn-flat' style='width: 40px'><i class='fa fa-trash-o'></i></a>"
        ."<span style='display: none; margin-left: 10px;' id='img-page-{$da->id_spesifik}'><img width='35px' src='{$url}' /></span>"
        . "</div>";
            }
        }
        
        if($da->status < 6 OR $da->status == 11){
            $dt_jumlah = $dt_jumlah;
            $btn_del = $btn_del;
        }else{
            $dt_jumlah = $jumlah;
            $btn_del = "";
        }
        
      $hasil[] = array(
          $no =$no + 1,
        $da->inventory_umum." ".$da->title_spesifik,
        $da->brand,
        $da->satuan,
       $this->form_eksternal->form_input("id_mrp_inventory_spesifik[]", $da->id_mrp_inventory_spesifik, $data_id.' style="width:100px;display:none" class="form-control id_spesifik input-sm" placeholder=""').
       $rg, 
       $dt_jumlah, 
       $btn_del
      );
    }
    $return['hasil'] = $hasil;
//    $this->debug($return, true);
    print json_encode($return);
    die;
  }
  
  function get_form_mrp_request_pengadaan_technical($id_mrp_request = 0,$id_users = 0,$start = 0){
      
      $status = $this->global_models->get_field("mrp_request", "status", 
                    array("id_mrp_request" => "{$id_mrp_request}"));
                    
      $dt_user = $this->global_models->get_field("mrp_request", "create_by_users", 
                    array("id_mrp_request" => "{$id_mrp_request}"));              
                    
      $ls = $this->global_models->get_query("SELECT B.id_hr_company,B.id_hr_master_organisasi"
        . " FROM m_users AS A"
        . " LEFT JOIN hr_pegawai AS B ON A.id_users = B.id_users"
        . " WHERE A.id_users = '{$id_users}'");
//       print $this->db->last_query();
//       die;
      $where = "WHERE A.status = 1 AND B.id_mrp_type_inventory = 4";
      $disabled ="";
      
      if($id_mrp_request > 0){
          $where .= " AND jumlah IS NOT NULL";
      }
      
      if($status == 1 AND  $this->session->userdata("id") != $dt_user){
//          $where .= " AND jumlah IS NOT NULL";
          $disabled = "disabled ";  
      }elseif($status > 1){
//          $where .= " AND jumlah IS NOT NULL";
          $disabled = "disabled ";  
      }
//      
   
       if($this->session->userdata("id") == 1 OR ($status == 2 AND $this->nbscache->get_olahan("permission", $this->session->userdata("id_privilege"), "approval-ro", "edit") !== FALSE)){
       $disabled ="";
      }
      
      if($this->session->userdata("id") == 1 OR $status == 11 OR $this->nbscache->get_olahan("permission", $this->session->userdata("id_privilege"), "edit-form-ro", "edit") !== FALSE){
       $disabled ="";
      }
//      if($id_mrp_request > 0){
//          $join = " "
//                  . " ";
//          $field = ",E.jumlah";
//      }else{
//          $where . " AND 1=1 ";
//          $join = "";
//          $field = "";
//      }
       $data = $this->global_models->get_query("SELECT B.name AS inventory_umum,D.title AS satuan,A.title AS title_spesifik,"
        . "A.id_mrp_inventory_spesifik,C.title AS brand,E.jumlah,E.rg,E.id_mrp_request_asset AS id_spesifik,F.create_by_users,F.status"
        . " FROM mrp_inventory_spesifik AS A"
        . " LEFT JOIN mrp_inventory_umum AS B ON A.id_mrp_inventory_umum = B.id_mrp_inventory_umum"
        . " LEFT JOIN mrp_brand AS C ON A.id_mrp_brand = C.id_mrp_brand"
        . " LEFT JOIN mrp_satuan AS D ON A.id_mrp_satuan = D.id_mrp_satuan"
        . " LEFT JOIN mrp_request_asset AS E ON (A.id_mrp_inventory_spesifik = E.id_mrp_inventory_spesifik AND E.id_mrp_request = '{$id_mrp_request}')"
        . " LEFT JOIN mrp_request AS F ON E.id_mrp_request = F.id_mrp_request"
        . " {$where} "
        . " ORDER BY A.id_mrp_inventory_spesifik ASC"
        . " LIMIT {$start}, 10");
        
//       print $this->db->last_query(); die;
//    $data_array = json_decode($data);
//    $this->debug($data, true);
        
    
    if(count($data) > 0){
      $return['status'] = 2;
      $return['start']  = $start + 10;
    }
    else{
      $return['status'] = 3;
    }
    
//    $jenis = array(1 => "Habis Pakai", 2 => "Asset");
    $no = $start;
    $url = base_url()."themes/".DEFAULTTHEMES."/img/ajax-loader.gif";
    foreach ($data AS $da){
        if($da->jumlah){
            $jumlah = $da->jumlah;
        }else{
            $jumlah = 0;
        }
        
        if($da->rg){
            $rg = $da->rg;
        }else{
            $rg = 0;
        }
        
        $data_id = "id=dt_id_mrp_inventory_spesifik_".$da->id_mrp_inventory_spesifik;
        $data_jumlah = "id=jumlah".$da->id_mrp_inventory_spesifik;
        $data_jumlah_rg = "id=jumlah_rg".$da->id_mrp_inventory_spesifik;
        if($da->id_mrp_setting_lock_atk){
            $dt_jumlah = $this->form_eksternal->form_input("jumlah[]", $jumlah, $data_jumlah.'  style="width:100px;display:none" class="form-control jumlah input-sm" placeholder="Jumlah"').
            $this->form_eksternal->form_input("jumlah_rg[]", $rg, $data_jumlah_rg.'  style="width:100px;display:none" class="form-control jumlah_rg input-sm" placeholder="rg"');
        }else{
         
             $dt_jumlah = $this->form_eksternal->form_input("jumlah[]", $jumlah, $disabled.$data_jumlah.'  style="width:100px" class="form-control jumlah input-sm" placeholder="Jumlah"').
            $this->form_eksternal->form_input("jumlah_rg[]", $rg, $data_jumlah_rg.'  style="width:100px;display:none" class="form-control jumlah_rg input-sm" placeholder="rg"');
          
            
        }
        
        
        
        if($da->id_spesifik){
            if($id_users ==1 OR $da->create_by_users == $id_users OR $this->nbscache->get_olahan("permission", $this->session->userdata("id_privilege"), "edit-form-ro", "edit") !== FALSE OR $this->nbscache->get_olahan("permission", $this->session->userdata("id_privilege"), "approval-ro", "edit") !== FALSE){
        $btn_del =  "<div class='btn-group'>"
        . "<a href='javascript:void(0)' onclick='delete_request_pengadaan({$da->id_spesifik})' id='del_{$da->id_spesifik}' class='btn btn-danger btn-flat' style='width: 40px'><i class='fa fa-trash-o'></i></a>"
        ."<span style='display: none; margin-left: 10px;' id='img-page-{$da->id_spesifik}'><img width='35px' src='{$url}' /></span>"
        . "</div>";
            }
        }
        
        if($da->status < 6 OR $da->status == 11){
            $dt_jumlah = $dt_jumlah;
            $btn_del = $btn_del;
        }else{
            $dt_jumlah = $jumlah;
            $btn_del = "";
        }
        
      $hasil[] = array(
          $no =$no + 1,
        $da->inventory_umum." ".$da->title_spesifik,
        $da->brand,
        $da->satuan,
       $this->form_eksternal->form_input("id_mrp_inventory_spesifik[]", $da->id_mrp_inventory_spesifik, $data_id.' style="width:100px;display:none" class="form-control id_spesifik input-sm" placeholder=""').
       $rg, 
       $dt_jumlah, 
       $btn_del
      );
    }
    $return['hasil'] = $hasil;
//    $this->debug($return, true);
    print json_encode($return);
    die;
  }
  
  function get_form_mrp_request_pengadaan_service($id_mrp_request = 0,$id_users = 0,$start = 0){
      
      $status = $this->global_models->get_field("mrp_request", "status", 
                    array("id_mrp_request" => "{$id_mrp_request}"));
                    
      $dt_user = $this->global_models->get_field("mrp_request", "create_by_users", 
                    array("id_mrp_request" => "{$id_mrp_request}"));              
                    
      $ls = $this->global_models->get_query("SELECT B.id_hr_company,B.id_hr_master_organisasi"
        . " FROM m_users AS A"
        . " LEFT JOIN hr_pegawai AS B ON A.id_users = B.id_users"
        . " WHERE A.id_users = '{$id_users}'");
//       print $this->db->last_query();
//       die;
      $where = "WHERE A.status = 1 AND B.id_mrp_type_inventory = 5";
      $disabled ="";
      
      if($id_mrp_request > 0){
          $where .= " AND jumlah IS NOT NULL";
      }
      
      if($status == 1 AND  $this->session->userdata("id") != $dt_user){
//          $where .= " AND jumlah IS NOT NULL";
          $disabled = "disabled ";  
      }elseif($status > 1){
//          $where .= " AND jumlah IS NOT NULL";
          $disabled = "disabled ";  
      }
//      
   
       if($this->session->userdata("id") == 1 OR ($status == 2 AND $this->nbscache->get_olahan("permission", $this->session->userdata("id_privilege"), "approval-ro", "edit") !== FALSE)){
       $disabled ="";
      }
      
      if($this->session->userdata("id") == 1 OR $status == 11 OR $this->nbscache->get_olahan("permission", $this->session->userdata("id_privilege"), "edit-form-ro", "edit") !== FALSE){
       $disabled ="";
      }
//      if($id_mrp_request > 0){
//          $join = " "
//                  . " ";
//          $field = ",E.jumlah";
//      }else{
//          $where . " AND 1=1 ";
//          $join = "";
//          $field = "";
//      }
       $data = $this->global_models->get_query("SELECT B.name AS inventory_umum,D.title AS satuan,A.title AS title_spesifik,"
        . "A.id_mrp_inventory_spesifik,C.title AS brand,E.jumlah,E.rg,E.id_mrp_request_asset AS id_spesifik,F.create_by_users,F.status"
        . " FROM mrp_inventory_spesifik AS A"
        . " LEFT JOIN mrp_inventory_umum AS B ON A.id_mrp_inventory_umum = B.id_mrp_inventory_umum"
        . " LEFT JOIN mrp_brand AS C ON A.id_mrp_brand = C.id_mrp_brand"
        . " LEFT JOIN mrp_satuan AS D ON A.id_mrp_satuan = D.id_mrp_satuan"
        . " LEFT JOIN mrp_request_asset AS E ON (A.id_mrp_inventory_spesifik = E.id_mrp_inventory_spesifik AND E.id_mrp_request = '{$id_mrp_request}')"
        . " LEFT JOIN mrp_request AS F ON E.id_mrp_request = F.id_mrp_request"
        . " {$where} "
        . " ORDER BY A.id_mrp_inventory_spesifik ASC"
        . " LIMIT {$start}, 10");
        
//       print $this->db->last_query(); die;
//    $data_array = json_decode($data);
//    $this->debug($data, true);
        
    
    if(count($data) > 0){
      $return['status'] = 2;
      $return['start']  = $start + 10;
    }
    else{
      $return['status'] = 3;
    }
    
//    $jenis = array(1 => "Habis Pakai", 2 => "Asset");
    $no = $start;
    $url = base_url()."themes/".DEFAULTTHEMES."/img/ajax-loader.gif";
    foreach ($data AS $da){
        if($da->jumlah){
            $jumlah = $da->jumlah;
        }else{
            $jumlah = 0;
        }
        
        if($da->rg){
            $rg = $da->rg;
        }else{
            $rg = 0;
        }
        
        $data_id = "id=dt_id_mrp_inventory_spesifik_".$da->id_mrp_inventory_spesifik;
        $data_jumlah = "id=jumlah".$da->id_mrp_inventory_spesifik;
        $data_jumlah_rg = "id=jumlah_rg".$da->id_mrp_inventory_spesifik;
        if($da->id_mrp_setting_lock_atk){
            $dt_jumlah = $this->form_eksternal->form_input("jumlah[]", $jumlah, $data_jumlah.'  style="width:100px;display:none" class="form-control jumlah input-sm" placeholder="Jumlah"').
            $this->form_eksternal->form_input("jumlah_rg[]", $rg, $data_jumlah_rg.'  style="width:100px;display:none" class="form-control jumlah_rg input-sm" placeholder="rg"');
        }else{
         
             $dt_jumlah = $this->form_eksternal->form_input("jumlah[]", $jumlah, $disabled.$data_jumlah.'  style="width:100px" class="form-control jumlah input-sm" placeholder="Jumlah"').
            $this->form_eksternal->form_input("jumlah_rg[]", $rg, $data_jumlah_rg.'  style="width:100px;display:none" class="form-control jumlah_rg input-sm" placeholder="rg"');
          
            
        }
        
        
        
        if($da->id_spesifik){
            if($id_users ==1 OR $da->create_by_users == $id_users OR $this->nbscache->get_olahan("permission", $this->session->userdata("id_privilege"), "edit-form-ro", "edit") !== FALSE OR $this->nbscache->get_olahan("permission", $this->session->userdata("id_privilege"), "approval-ro", "edit") !== FALSE){
        $btn_del =  "<div class='btn-group'>"
        . "<a href='javascript:void(0)' onclick='delete_request_pengadaan({$da->id_spesifik})' id='del_{$da->id_spesifik}' class='btn btn-danger btn-flat' style='width: 40px'><i class='fa fa-trash-o'></i></a>"
        ."<span style='display: none; margin-left: 10px;' id='img-page-{$da->id_spesifik}'><img width='35px' src='{$url}' /></span>"
        . "</div>";
            }
        }
        
        if($da->status < 6 OR $da->status == 11){
            $dt_jumlah = $dt_jumlah;
            $btn_del = $btn_del;
        }else{
            $dt_jumlah = $jumlah;
            $btn_del = "";
        }
        
      $hasil[] = array(
          $no =$no + 1,
        $da->inventory_umum." ".$da->title_spesifik,
        $da->brand,
        $da->satuan,
       $this->form_eksternal->form_input("id_mrp_inventory_spesifik[]", $da->id_mrp_inventory_spesifik, $data_id.' style="width:100px;display:none" class="form-control id_spesifik input-sm" placeholder=""').
       $rg, 
       $dt_jumlah, 
       $btn_del
      );
    }
    $return['hasil'] = $hasil;
//    $this->debug($return, true);
    print json_encode($return);
    die;
  }
  
     function get_list_form_mrp_request_pengadaan_komputer($id_mrp_request = 0,$id_users = 0,$start = 0){
      
      $status = $this->global_models->get_field("mrp_request", "status", 
                    array("id_mrp_request" => "{$id_mrp_request}"));
                    
      $dt_user = $this->global_models->get_field("mrp_request", "create_by_users", 
                    array("id_mrp_request" => "{$id_mrp_request}"));              
                    
      $ls = $this->global_models->get_query("SELECT B.id_hr_company,B.id_hr_master_organisasi"
        . " FROM m_users AS A"
        . " LEFT JOIN hr_pegawai AS B ON A.id_users = B.id_users"
        . " WHERE A.id_users = '{$id_users}'");
//       print $this->db->last_query();
//       die;
      $where = "WHERE A.status = 1 AND B.id_mrp_type_inventory = 3";
      $disabled ="";
      if($status == 1 AND  $this->session->userdata("id") != $dt_user){
//          $where .= " AND jumlah IS NOT NULL";
          $disabled = "disabled ";  
      }elseif($status > 1){
//          $where .= " AND jumlah IS NOT NULL";
          $disabled = "disabled ";  
      }
//      
   
       if($this->session->userdata("id") == 1 OR ($status == 2 AND $this->nbscache->get_olahan("permission", $this->session->userdata("id_privilege"), "approval-ro", "edit") !== FALSE)){
       $disabled ="";
      }
      
      if($this->session->userdata("id") == 1 OR $status == 11 OR $this->nbscache->get_olahan("permission", $this->session->userdata("id_privilege"), "edit-form-ro", "edit") !== FALSE){
       $disabled ="";
      }

      $id_mrp_supplier = $this->global_models->get_field("mrp_setting_harga_atk","id_mrp_supplier",array("id_mrp_setting_harga_atk" => '1'));
      $data = $this->global_models->get_query("SELECT B.name AS inventory_umum,C.title AS brand,D.title AS satuan,"
        . "A.id_mrp_inventory_spesifik,A.title AS title_spesifik,E.jumlah,G.harga,I.id_mrp_setting_lock_atk"
        . " FROM mrp_inventory_spesifik AS A"
        . " LEFT JOIN mrp_inventory_umum AS B ON A.id_mrp_inventory_umum = B.id_mrp_inventory_umum"
        . " LEFT JOIN mrp_brand AS C ON A.id_mrp_brand = C.id_mrp_brand"
        . " LEFT JOIN mrp_satuan AS D ON A.id_mrp_satuan = D.id_mrp_satuan"
        . " LEFT JOIN mrp_request_asset AS E ON (A.id_mrp_inventory_spesifik = E.id_mrp_inventory_spesifik AND E.id_mrp_request = '{$id_mrp_request}')"
        . " LEFT JOIN mrp_request AS F ON E.id_mrp_request = F.id_mrp_request"
        . " LEFT JOIN mrp_supplier_inventory AS G ON (A.id_mrp_inventory_spesifik = G.id_mrp_inventory_spesifik AND G.id_mrp_supplier = '{$id_mrp_supplier}')"
        . " LEFT JOIN mrp_setting_lock_atk_asset AS H ON A.id_mrp_inventory_spesifik = H.id_mrp_inventory_spesifik"
        . " LEFT JOIN mrp_setting_lock_atk AS I ON (H.id_mrp_setting_lock_atk = I.id_mrp_setting_lock_atk AND I.id_hr_company = '{$ls[0]->id_hr_company}' AND I.id_hr_master_organisasi ='{$ls[0]->id_hr_master_organisasi}')"        
        . " {$where} "
        . " GROUP BY A.id_mrp_inventory_spesifik"
        . " ORDER BY A.id_mrp_inventory_spesifik ASC"
        . " LIMIT {$start}, 10");
        
//       print $this->db->last_query(); die;
//    $data_array = json_decode($data);
//    $this->debug($data, true);
        
    
    if(count($data) > 0){
      $return['status'] = 2;
      $return['start']  = $start + 10;
    }
    else{
      $return['status'] = 3;
    }
    
//    $jenis = array(1 => "Habis Pakai", 2 => "Asset");
    $no = $start;
    foreach ($data AS $da){
        if($da->jumlah){
            $jumlah = $da->jumlah;
        }else{
            $jumlah = 0;
        }
        if($da->harga){
            $harga = number_format($da->harga);
        }else{
            $harga = "";
        }
        
        $data_id = "id=dt_id_mrp_inventory_spesifik_".$da->id_mrp_inventory_spesifik;
        $data_jumlah = "id=jumlah".$da->id_mrp_inventory_spesifik;
        $data_jumlah_rg = "id=jumlah_rg".$da->id_mrp_inventory_spesifik;
//        if($da->id_mrp_setting_lock_atk){
//            $dt_jumlah = $this->form_eksternal->form_input("jumlah[]", $jumlah, $disabled.$data_jumlah.'  style="width:100px;display:none" class="form-control jumlah input-sm" placeholder="Jumlah"');
//        }else{
//            $dt_jumlah = $this->form_eksternal->form_input("jumlah[]", $jumlah, $disabled.$data_jumlah.'  style="width:100px" class="form-control jumlah input-sm" placeholder="Jumlah"');
//        }
        
        if($da->id_mrp_setting_lock_atk){
            $dt_jumlah = $this->form_eksternal->form_input("jumlah[]", $jumlah, $data_jumlah.'  style="width:100px;display:none" class="form-control jumlah input-sm" placeholder="Jumlah"').
            $this->form_eksternal->form_input("jumlah_rg[]", $rg, $data_jumlah_rg.'  style="width:100px;display:none" class="form-control jumlah_rg input-sm" placeholder="rg"');
        }else{
            
            $dt_jumlah = $this->form_eksternal->form_input("jumlah[]", $jumlah, $disabled.$data_jumlah.'  style="width:100px" class="form-control jumlah input-sm" placeholder="Jumlah"').
            $this->form_eksternal->form_input("jumlah_rg[]", $rg, $data_jumlah_rg.'  style="width:100px;display:none" class="form-control jumlah_rg input-sm" placeholder="rg"');
        }
        
      $hasil[] = array(
          $no =$no + 1,
        $da->inventory_umum." ".$da->title_spesifik,
        $da->brand,
        $da->satuan,
        $harga,  
        $this->form_eksternal->form_input("id_mrp_inventory_spesifik[]", $da->id_mrp_inventory_spesifik, $data_id.' style="width:100px;display:none" class="form-control id_spesifik input-sm" placeholder=""').
        $dt_jumlah, 
//        "<div class='btn-group'>"
//          . "<a href='".site_url("mrp/mrp-master/add-inventory-spesifik/{$da->id_mrp_request}")."' type='button' class='btn btn-info btn-flat' style='width: 40px'><i class='fa fa-edit'></i></a>"
//        . "</div>"
      );
    }
    $return['hasil'] = $hasil;
//    $this->debug($return, true);
    print json_encode($return);
    die;
  }
  
    function get_list_form_mrp_request_pengadaan_office($id_mrp_request = 0,$id_users = 0,$start = 0){
      
      $status = $this->global_models->get_field("mrp_request", "status", 
                    array("id_mrp_request" => "{$id_mrp_request}"));
                    
      $dt_user = $this->global_models->get_field("mrp_request", "create_by_users", 
                    array("id_mrp_request" => "{$id_mrp_request}"));              
                    
      $ls = $this->global_models->get_query("SELECT B.id_hr_company,B.id_hr_master_organisasi"
        . " FROM m_users AS A"
        . " LEFT JOIN hr_pegawai AS B ON A.id_users = B.id_users"
        . " WHERE A.id_users = '{$id_users}'");
//       print $this->db->last_query();
//       die;
      $where = "WHERE A.status = 1 AND B.id_mrp_type_inventory = 7";
      $disabled ="";
      if($status == 1 AND  $this->session->userdata("id") != $dt_user){
//          $where .= " AND jumlah IS NOT NULL";
          $disabled = "disabled ";  
      }elseif($status > 1){
//          $where .= " AND jumlah IS NOT NULL";
          $disabled = "disabled ";  
      }
//      
   
       if($this->session->userdata("id") == 1 OR ($status == 2 AND $this->nbscache->get_olahan("permission", $this->session->userdata("id_privilege"), "approval-ro", "edit") !== FALSE)){
       $disabled ="";
      }
      
      if($this->session->userdata("id") == 1 OR $status == 11 OR $this->nbscache->get_olahan("permission", $this->session->userdata("id_privilege"), "edit-form-ro", "edit") !== FALSE){
       $disabled ="";
      }

      $id_mrp_supplier = $this->global_models->get_field("mrp_setting_harga_atk","id_mrp_supplier",array("id_mrp_setting_harga_atk" => '1'));
      $data = $this->global_models->get_query("SELECT B.name AS inventory_umum,C.title AS brand,D.title AS satuan,"
        . "A.id_mrp_inventory_spesifik,A.title AS title_spesifik,E.jumlah,G.harga,I.id_mrp_setting_lock_atk"
        . " FROM mrp_inventory_spesifik AS A"
        . " LEFT JOIN mrp_inventory_umum AS B ON A.id_mrp_inventory_umum = B.id_mrp_inventory_umum"
        . " LEFT JOIN mrp_brand AS C ON A.id_mrp_brand = C.id_mrp_brand"
        . " LEFT JOIN mrp_satuan AS D ON A.id_mrp_satuan = D.id_mrp_satuan"
        . " LEFT JOIN mrp_request_asset AS E ON (A.id_mrp_inventory_spesifik = E.id_mrp_inventory_spesifik AND E.id_mrp_request = '{$id_mrp_request}')"
        . " LEFT JOIN mrp_request AS F ON E.id_mrp_request = F.id_mrp_request"
        . " LEFT JOIN mrp_supplier_inventory AS G ON (A.id_mrp_inventory_spesifik = G.id_mrp_inventory_spesifik AND G.id_mrp_supplier = '{$id_mrp_supplier}')"
        . " LEFT JOIN mrp_setting_lock_atk_asset AS H ON A.id_mrp_inventory_spesifik = H.id_mrp_inventory_spesifik"
        . " LEFT JOIN mrp_setting_lock_atk AS I ON (H.id_mrp_setting_lock_atk = I.id_mrp_setting_lock_atk AND I.id_hr_company = '{$ls[0]->id_hr_company}' AND I.id_hr_master_organisasi ='{$ls[0]->id_hr_master_organisasi}')"        
        . " {$where} "
        . " GROUP BY A.id_mrp_inventory_spesifik"
        . " ORDER BY A.id_mrp_inventory_spesifik ASC"
        . " LIMIT {$start}, 10");
        
//       print $this->db->last_query(); die;
//    $data_array = json_decode($data);
//    $this->debug($data, true);
        
    
    if(count($data) > 0){
      $return['status'] = 2;
      $return['start']  = $start + 10;
    }
    else{
      $return['status'] = 3;
    }
    
//    $jenis = array(1 => "Habis Pakai", 2 => "Asset");
    $no = $start;
    foreach ($data AS $da){
        if($da->jumlah){
            $jumlah = $da->jumlah;
        }else{
            $jumlah = 0;
        }
        if($da->harga){
            $harga = number_format($da->harga);
        }else{
            $harga = "";
        }
        
        $data_id = "id=dt_id_mrp_inventory_spesifik_".$da->id_mrp_inventory_spesifik;
        $data_jumlah = "id=jumlah".$da->id_mrp_inventory_spesifik;
        $data_jumlah_rg = "id=jumlah_rg".$da->id_mrp_inventory_spesifik;
//        if($da->id_mrp_setting_lock_atk){
//            $dt_jumlah = $this->form_eksternal->form_input("jumlah[]", $jumlah, $disabled.$data_jumlah.'  style="width:100px;display:none" class="form-control jumlah input-sm" placeholder="Jumlah"');
//        }else{
//            $dt_jumlah = $this->form_eksternal->form_input("jumlah[]", $jumlah, $disabled.$data_jumlah.'  style="width:100px" class="form-control jumlah input-sm" placeholder="Jumlah"');
//        }
        
        if($da->id_mrp_setting_lock_atk){
            $dt_jumlah = $this->form_eksternal->form_input("jumlah[]", $jumlah, $data_jumlah.'  style="width:100px;display:none" class="form-control jumlah input-sm" placeholder="Jumlah"').
            $this->form_eksternal->form_input("jumlah_rg[]", $rg, $data_jumlah_rg.'  style="width:100px;display:none" class="form-control jumlah_rg input-sm" placeholder="rg"');
        }else{
            
            $dt_jumlah = $this->form_eksternal->form_input("jumlah[]", $jumlah, $disabled.$data_jumlah.'  style="width:100px" class="form-control jumlah input-sm" placeholder="Jumlah"').
            $this->form_eksternal->form_input("jumlah_rg[]", $rg, $data_jumlah_rg.'  style="width:100px;display:none" class="form-control jumlah_rg input-sm" placeholder="rg"');
        }
        
      $hasil[] = array(
          $no =$no + 1,
        $da->inventory_umum." ".$da->title_spesifik,
        $da->brand,
        $da->satuan,
        $harga,  
        $this->form_eksternal->form_input("id_mrp_inventory_spesifik[]", $da->id_mrp_inventory_spesifik, $data_id.' style="width:100px;display:none" class="form-control id_spesifik input-sm" placeholder=""').
        $dt_jumlah, 
//        "<div class='btn-group'>"
//          . "<a href='".site_url("mrp/mrp-master/add-inventory-spesifik/{$da->id_mrp_request}")."' type='button' class='btn btn-info btn-flat' style='width: 40px'><i class='fa fa-edit'></i></a>"
//        . "</div>"
      );
    }
    $return['hasil'] = $hasil;
//    $this->debug($return, true);
    print json_encode($return);
    die;
  }
  
  function get_list_form_mrp_request_pengadaan_promosi($id_mrp_request = 0,$id_users = 0,$start = 0){
      
      $status = $this->global_models->get_field("mrp_request", "status", 
                    array("id_mrp_request" => "{$id_mrp_request}"));
                    
      $dt_user = $this->global_models->get_field("mrp_request", "create_by_users", 
                    array("id_mrp_request" => "{$id_mrp_request}"));              
                    
      $ls = $this->global_models->get_query("SELECT B.id_hr_company,B.id_hr_master_organisasi"
        . " FROM m_users AS A"
        . " LEFT JOIN hr_pegawai AS B ON A.id_users = B.id_users"
        . " WHERE A.id_users = '{$id_users}'");
//       print $this->db->last_query();
//       die;
      $where = "WHERE A.status = 1 AND B.id_mrp_type_inventory = 8";
      $disabled ="";
      if($status == 1 AND  $this->session->userdata("id") != $dt_user){
//          $where .= " AND jumlah IS NOT NULL";
          $disabled = "disabled ";  
      }elseif($status > 1){
//          $where .= " AND jumlah IS NOT NULL";
          $disabled = "disabled ";  
      }
//      
   
       if($this->session->userdata("id") == 1 OR ($status == 2 AND $this->nbscache->get_olahan("permission", $this->session->userdata("id_privilege"), "approval-ro", "edit") !== FALSE)){
       $disabled ="";
      }
      
      if($this->session->userdata("id") == 1 OR $status == 11 OR $this->nbscache->get_olahan("permission", $this->session->userdata("id_privilege"), "edit-form-ro", "edit") !== FALSE){
       $disabled ="";
      }

      $id_mrp_supplier = $this->global_models->get_field("mrp_setting_harga_atk","id_mrp_supplier",array("id_mrp_setting_harga_atk" => '1'));
      $data = $this->global_models->get_query("SELECT B.name AS inventory_umum,C.title AS brand,D.title AS satuan,"
        . "A.id_mrp_inventory_spesifik,A.title AS title_spesifik,E.jumlah,G.harga,I.id_mrp_setting_lock_atk"
        . " FROM mrp_inventory_spesifik AS A"
        . " LEFT JOIN mrp_inventory_umum AS B ON A.id_mrp_inventory_umum = B.id_mrp_inventory_umum"
        . " LEFT JOIN mrp_brand AS C ON A.id_mrp_brand = C.id_mrp_brand"
        . " LEFT JOIN mrp_satuan AS D ON A.id_mrp_satuan = D.id_mrp_satuan"
        . " LEFT JOIN mrp_request_asset AS E ON (A.id_mrp_inventory_spesifik = E.id_mrp_inventory_spesifik AND E.id_mrp_request = '{$id_mrp_request}')"
        . " LEFT JOIN mrp_request AS F ON E.id_mrp_request = F.id_mrp_request"
        . " LEFT JOIN mrp_supplier_inventory AS G ON (A.id_mrp_inventory_spesifik = G.id_mrp_inventory_spesifik AND G.id_mrp_supplier = '{$id_mrp_supplier}')"
        . " LEFT JOIN mrp_setting_lock_atk_asset AS H ON A.id_mrp_inventory_spesifik = H.id_mrp_inventory_spesifik"
        . " LEFT JOIN mrp_setting_lock_atk AS I ON (H.id_mrp_setting_lock_atk = I.id_mrp_setting_lock_atk AND I.id_hr_company = '{$ls[0]->id_hr_company}' AND I.id_hr_master_organisasi ='{$ls[0]->id_hr_master_organisasi}')"        
        . " {$where} "
        . " GROUP BY A.id_mrp_inventory_spesifik"
        . " ORDER BY A.id_mrp_inventory_spesifik ASC"
        . " LIMIT {$start}, 10");
        
//       print $this->db->last_query(); die;
//    $data_array = json_decode($data);
//    $this->debug($data, true);
        
    
    if(count($data) > 0){
      $return['status'] = 2;
      $return['start']  = $start + 10;
    }
    else{
      $return['status'] = 3;
    }
    
//    $jenis = array(1 => "Habis Pakai", 2 => "Asset");
    $no = $start;
    foreach ($data AS $da){
        if($da->jumlah){
            $jumlah = $da->jumlah;
        }else{
            $jumlah = 0;
        }
        if($da->harga){
            $harga = number_format($da->harga);
        }else{
            $harga = "";
        }
        
        $data_id = "id=dt_id_mrp_inventory_spesifik_".$da->id_mrp_inventory_spesifik;
        $data_jumlah = "id=jumlah".$da->id_mrp_inventory_spesifik;
        $data_jumlah_rg = "id=jumlah_rg".$da->id_mrp_inventory_spesifik;
//        if($da->id_mrp_setting_lock_atk){
//            $dt_jumlah = $this->form_eksternal->form_input("jumlah[]", $jumlah, $disabled.$data_jumlah.'  style="width:100px;display:none" class="form-control jumlah input-sm" placeholder="Jumlah"');
//        }else{
//            $dt_jumlah = $this->form_eksternal->form_input("jumlah[]", $jumlah, $disabled.$data_jumlah.'  style="width:100px" class="form-control jumlah input-sm" placeholder="Jumlah"');
//        }
        
        if($da->id_mrp_setting_lock_atk){
            $dt_jumlah = $this->form_eksternal->form_input("jumlah[]", $jumlah, $data_jumlah.'  style="width:100px;display:none" class="form-control jumlah input-sm" placeholder="Jumlah"').
            $this->form_eksternal->form_input("jumlah_rg[]", $rg, $data_jumlah_rg.'  style="width:100px;display:none" class="form-control jumlah_rg input-sm" placeholder="rg"');
        }else{
            
            $dt_jumlah = $this->form_eksternal->form_input("jumlah[]", $jumlah, $disabled.$data_jumlah.'  style="width:100px" class="form-control jumlah input-sm" placeholder="Jumlah"').
            $this->form_eksternal->form_input("jumlah_rg[]", $rg, $data_jumlah_rg.'  style="width:100px;display:none" class="form-control jumlah_rg input-sm" placeholder="rg"');
        }
        
      $hasil[] = array(
          $no =$no + 1,
        $da->inventory_umum." ".$da->title_spesifik,
        $da->brand,
        $da->satuan,
        $harga,  
        $this->form_eksternal->form_input("id_mrp_inventory_spesifik[]", $da->id_mrp_inventory_spesifik, $data_id.' style="width:100px;display:none" class="form-control id_spesifik input-sm" placeholder=""').
        $dt_jumlah, 
//        "<div class='btn-group'>"
//          . "<a href='".site_url("mrp/mrp-master/add-inventory-spesifik/{$da->id_mrp_request}")."' type='button' class='btn btn-info btn-flat' style='width: 40px'><i class='fa fa-edit'></i></a>"
//        . "</div>"
      );
    }
    $return['hasil'] = $hasil;
//    $this->debug($return, true);
    print json_encode($return);
    die;
  }
  
      function get_list_form_mrp_request_pengadaan_umum($id_mrp_request = 0,$id_users = 0,$start = 0){
      
      $status = $this->global_models->get_field("mrp_request", "status", 
                    array("id_mrp_request" => "{$id_mrp_request}"));
                    
      $dt_user = $this->global_models->get_field("mrp_request", "create_by_users", 
                    array("id_mrp_request" => "{$id_mrp_request}"));              
                    
      $ls = $this->global_models->get_query("SELECT B.id_hr_company,B.id_hr_master_organisasi"
        . " FROM m_users AS A"
        . " LEFT JOIN hr_pegawai AS B ON A.id_users = B.id_users"
        . " WHERE A.id_users = '{$id_users}'");
//       print $this->db->last_query();
//       die;
      $where = "WHERE A.status = 1 AND B.id_mrp_type_inventory = 9";
      $disabled ="";
      if($status == 1 AND  $this->session->userdata("id") != $dt_user){
//          $where .= " AND jumlah IS NOT NULL";
          $disabled = "disabled ";  
      }elseif($status > 1){
//          $where .= " AND jumlah IS NOT NULL";
          $disabled = "disabled ";  
      }
//      
   
       if($this->session->userdata("id") == 1 OR ($status == 2 AND $this->nbscache->get_olahan("permission", $this->session->userdata("id_privilege"), "approval-ro", "edit") !== FALSE)){
       $disabled ="";
      }
      
      if($this->session->userdata("id") == 1 OR $status == 11 OR $this->nbscache->get_olahan("permission", $this->session->userdata("id_privilege"), "edit-form-ro", "edit") !== FALSE){
       $disabled ="";
      }

      $id_mrp_supplier = $this->global_models->get_field("mrp_setting_harga_atk","id_mrp_supplier",array("id_mrp_setting_harga_atk" => '1'));
      $data = $this->global_models->get_query("SELECT B.name AS inventory_umum,C.title AS brand,D.title AS satuan,"
        . "A.id_mrp_inventory_spesifik,A.title AS title_spesifik,E.jumlah,G.harga,I.id_mrp_setting_lock_atk"
        . " FROM mrp_inventory_spesifik AS A"
        . " LEFT JOIN mrp_inventory_umum AS B ON A.id_mrp_inventory_umum = B.id_mrp_inventory_umum"
        . " LEFT JOIN mrp_brand AS C ON A.id_mrp_brand = C.id_mrp_brand"
        . " LEFT JOIN mrp_satuan AS D ON A.id_mrp_satuan = D.id_mrp_satuan"
        . " LEFT JOIN mrp_request_asset AS E ON (A.id_mrp_inventory_spesifik = E.id_mrp_inventory_spesifik AND E.id_mrp_request = '{$id_mrp_request}')"
        . " LEFT JOIN mrp_request AS F ON E.id_mrp_request = F.id_mrp_request"
        . " LEFT JOIN mrp_supplier_inventory AS G ON (A.id_mrp_inventory_spesifik = G.id_mrp_inventory_spesifik AND G.id_mrp_supplier = '{$id_mrp_supplier}')"
        . " LEFT JOIN mrp_setting_lock_atk_asset AS H ON A.id_mrp_inventory_spesifik = H.id_mrp_inventory_spesifik"
        . " LEFT JOIN mrp_setting_lock_atk AS I ON (H.id_mrp_setting_lock_atk = I.id_mrp_setting_lock_atk AND I.id_hr_company = '{$ls[0]->id_hr_company}' AND I.id_hr_master_organisasi ='{$ls[0]->id_hr_master_organisasi}')"        
        . " {$where} "
        . " GROUP BY A.id_mrp_inventory_spesifik"
        . " ORDER BY A.id_mrp_inventory_spesifik ASC"
        . " LIMIT {$start}, 10");
        
//       print $this->db->last_query(); die;
//    $data_array = json_decode($data);
//    $this->debug($data, true);
        
    
    if(count($data) > 0){
      $return['status'] = 2;
      $return['start']  = $start + 10;
    }
    else{
      $return['status'] = 3;
    }
    
//    $jenis = array(1 => "Habis Pakai", 2 => "Asset");
    $no = $start;
    foreach ($data AS $da){
        if($da->jumlah){
            $jumlah = $da->jumlah;
        }else{
            $jumlah = 0;
        }
        if($da->harga){
            $harga = number_format($da->harga);
        }else{
            $harga = "";
        }
        
        $data_id = "id=dt_id_mrp_inventory_spesifik_".$da->id_mrp_inventory_spesifik;
        $data_jumlah = "id=jumlah".$da->id_mrp_inventory_spesifik;
        $data_jumlah_rg = "id=jumlah_rg".$da->id_mrp_inventory_spesifik;
//        if($da->id_mrp_setting_lock_atk){
//            $dt_jumlah = $this->form_eksternal->form_input("jumlah[]", $jumlah, $disabled.$data_jumlah.'  style="width:100px;display:none" class="form-control jumlah input-sm" placeholder="Jumlah"');
//        }else{
//            $dt_jumlah = $this->form_eksternal->form_input("jumlah[]", $jumlah, $disabled.$data_jumlah.'  style="width:100px" class="form-control jumlah input-sm" placeholder="Jumlah"');
//        }
        
        if($da->id_mrp_setting_lock_atk){
            $dt_jumlah = $this->form_eksternal->form_input("jumlah[]", $jumlah, $data_jumlah.'  style="width:100px;display:none" class="form-control jumlah input-sm" placeholder="Jumlah"').
            $this->form_eksternal->form_input("jumlah_rg[]", $rg, $data_jumlah_rg.'  style="width:100px;display:none" class="form-control jumlah_rg input-sm" placeholder="rg"');
        }else{
            
            $dt_jumlah = $this->form_eksternal->form_input("jumlah[]", $jumlah, $disabled.$data_jumlah.'  style="width:100px" class="form-control jumlah input-sm" placeholder="Jumlah"').
            $this->form_eksternal->form_input("jumlah_rg[]", $rg, $data_jumlah_rg.'  style="width:100px;display:none" class="form-control jumlah_rg input-sm" placeholder="rg"');
        }
        
      $hasil[] = array(
          $no =$no + 1,
        $da->inventory_umum." ".$da->title_spesifik,
        $da->brand,
        $da->satuan,
        $harga,  
        $this->form_eksternal->form_input("id_mrp_inventory_spesifik[]", $da->id_mrp_inventory_spesifik, $data_id.' style="width:100px;display:none" class="form-control id_spesifik input-sm" placeholder=""').
        $dt_jumlah, 
//        "<div class='btn-group'>"
//          . "<a href='".site_url("mrp/mrp-master/add-inventory-spesifik/{$da->id_mrp_request}")."' type='button' class='btn btn-info btn-flat' style='width: 40px'><i class='fa fa-edit'></i></a>"
//        . "</div>"
      );
    }
    $return['hasil'] = $hasil;
//    $this->debug($return, true);
    print json_encode($return);
    die;
  }
  
    function get_list_form_mrp_request_pengadaan_service($id_mrp_request = 0,$id_users = 0,$start = 0){
      
      $status = $this->global_models->get_field("mrp_request", "status", 
                    array("id_mrp_request" => "{$id_mrp_request}"));
                    
      $dt_user = $this->global_models->get_field("mrp_request", "create_by_users", 
                    array("id_mrp_request" => "{$id_mrp_request}"));              
                    
      $ls = $this->global_models->get_query("SELECT B.id_hr_company,B.id_hr_master_organisasi"
        . " FROM m_users AS A"
        . " LEFT JOIN hr_pegawai AS B ON A.id_users = B.id_users"
        . " WHERE A.id_users = '{$id_users}'");
//       print $this->db->last_query();
//       die;
      $where = "WHERE A.status = 1 AND B.id_mrp_type_inventory = 5";
      $disabled ="";
      if($status == 1 AND  $this->session->userdata("id") != $dt_user){
//          $where .= " AND jumlah IS NOT NULL";
          $disabled = "disabled ";  
      }elseif($status > 1){
//          $where .= " AND jumlah IS NOT NULL";
          $disabled = "disabled ";  
      }
//      
   
       if($this->session->userdata("id") == 1 OR ($status == 2 AND $this->nbscache->get_olahan("permission", $this->session->userdata("id_privilege"), "approval-ro", "edit") !== FALSE)){
       $disabled ="";
      }
      
      if($this->session->userdata("id") == 1 OR $status == 11 OR $this->nbscache->get_olahan("permission", $this->session->userdata("id_privilege"), "edit-form-ro", "edit") !== FALSE){
       $disabled ="";
      }

      $id_mrp_supplier = $this->global_models->get_field("mrp_setting_harga_atk","id_mrp_supplier",array("id_mrp_setting_harga_atk" => '1'));
      $data = $this->global_models->get_query("SELECT B.name AS inventory_umum,C.title AS brand,D.title AS satuan,"
        . "A.id_mrp_inventory_spesifik,A.title AS title_spesifik,E.jumlah,G.harga,I.id_mrp_setting_lock_atk"
        . " FROM mrp_inventory_spesifik AS A"
        . " LEFT JOIN mrp_inventory_umum AS B ON A.id_mrp_inventory_umum = B.id_mrp_inventory_umum"
        . " LEFT JOIN mrp_brand AS C ON A.id_mrp_brand = C.id_mrp_brand"
        . " LEFT JOIN mrp_satuan AS D ON A.id_mrp_satuan = D.id_mrp_satuan"
        . " LEFT JOIN mrp_request_asset AS E ON (A.id_mrp_inventory_spesifik = E.id_mrp_inventory_spesifik AND E.id_mrp_request = '{$id_mrp_request}')"
        . " LEFT JOIN mrp_request AS F ON E.id_mrp_request = F.id_mrp_request"
        . " LEFT JOIN mrp_supplier_inventory AS G ON (A.id_mrp_inventory_spesifik = G.id_mrp_inventory_spesifik AND G.id_mrp_supplier = '{$id_mrp_supplier}')"
        . " LEFT JOIN mrp_setting_lock_atk_asset AS H ON A.id_mrp_inventory_spesifik = H.id_mrp_inventory_spesifik"
        . " LEFT JOIN mrp_setting_lock_atk AS I ON (H.id_mrp_setting_lock_atk = I.id_mrp_setting_lock_atk AND I.id_hr_company = '{$ls[0]->id_hr_company}' AND I.id_hr_master_organisasi ='{$ls[0]->id_hr_master_organisasi}')"        
        . " {$where} "
        . " GROUP BY A.id_mrp_inventory_spesifik"
        . " ORDER BY A.id_mrp_inventory_spesifik ASC"
        . " LIMIT {$start}, 10");
        
//       print $this->db->last_query(); die;
//    $data_array = json_decode($data);
//    $this->debug($data, true);
        
    
    if(count($data) > 0){
      $return['status'] = 2;
      $return['start']  = $start + 10;
    }
    else{
      $return['status'] = 3;
    }
    
//    $jenis = array(1 => "Habis Pakai", 2 => "Asset");
    $no = $start;
    foreach ($data AS $da){
        if($da->jumlah){
            $jumlah = $da->jumlah;
        }else{
            $jumlah = 0;
        }
        if($da->harga){
            $harga = number_format($da->harga);
        }else{
            $harga = "";
        }
        
        $data_id = "id=dt_id_mrp_inventory_spesifik_".$da->id_mrp_inventory_spesifik;
        $data_jumlah = "id=jumlah".$da->id_mrp_inventory_spesifik;
        $data_jumlah_rg = "id=jumlah_rg".$da->id_mrp_inventory_spesifik;
//        if($da->id_mrp_setting_lock_atk){
//            $dt_jumlah = $this->form_eksternal->form_input("jumlah[]", $jumlah, $disabled.$data_jumlah.'  style="width:100px;display:none" class="form-control jumlah input-sm" placeholder="Jumlah"');
//        }else{
//            $dt_jumlah = $this->form_eksternal->form_input("jumlah[]", $jumlah, $disabled.$data_jumlah.'  style="width:100px" class="form-control jumlah input-sm" placeholder="Jumlah"');
//        }
        
        if($da->id_mrp_setting_lock_atk){
            $dt_jumlah = $this->form_eksternal->form_input("jumlah[]", $jumlah, $data_jumlah.'  style="width:100px;display:none" class="form-control jumlah input-sm" placeholder="Jumlah"').
            $this->form_eksternal->form_input("jumlah_rg[]", $rg, $data_jumlah_rg.'  style="width:100px;display:none" class="form-control jumlah_rg input-sm" placeholder="rg"');
        }else{
            
            $dt_jumlah = $this->form_eksternal->form_input("jumlah[]", $jumlah, $disabled.$data_jumlah.'  style="width:100px" class="form-control jumlah input-sm" placeholder="Jumlah"').
            $this->form_eksternal->form_input("jumlah_rg[]", $rg, $data_jumlah_rg.'  style="width:100px;display:none" class="form-control jumlah_rg input-sm" placeholder="rg"');
        }
        
      $hasil[] = array(
          $no =$no + 1,
        $da->inventory_umum." ".$da->title_spesifik,
        $da->brand,
        $da->satuan,
        $harga,  
        $this->form_eksternal->form_input("id_mrp_inventory_spesifik[]", $da->id_mrp_inventory_spesifik, $data_id.' style="width:100px;display:none" class="form-control id_spesifik input-sm" placeholder=""').
        $dt_jumlah, 
//        "<div class='btn-group'>"
//          . "<a href='".site_url("mrp/mrp-master/add-inventory-spesifik/{$da->id_mrp_request}")."' type='button' class='btn btn-info btn-flat' style='width: 40px'><i class='fa fa-edit'></i></a>"
//        . "</div>"
      );
    }
    $return['hasil'] = $hasil;
//    $this->debug($return, true);
    print json_encode($return);
    die;
  }
  
  function get_list_form_mrp_request_pengadaan_technical($id_mrp_request = 0,$id_users = 0,$start = 0){
      
      $status = $this->global_models->get_field("mrp_request", "status", 
                    array("id_mrp_request" => "{$id_mrp_request}"));
                    
      $dt_user = $this->global_models->get_field("mrp_request", "create_by_users", 
                    array("id_mrp_request" => "{$id_mrp_request}"));              
                    
      $ls = $this->global_models->get_query("SELECT B.id_hr_company,B.id_hr_master_organisasi"
        . " FROM m_users AS A"
        . " LEFT JOIN hr_pegawai AS B ON A.id_users = B.id_users"
        . " WHERE A.id_users = '{$id_users}'");
//       print $this->db->last_query();
//       die;
      $where = "WHERE A.status = 1 AND B.id_mrp_type_inventory = 4";
      $disabled ="";
      if($status == 1 AND  $this->session->userdata("id") != $dt_user){
//          $where .= " AND jumlah IS NOT NULL";
          $disabled = "disabled ";  
      }elseif($status > 1){
//          $where .= " AND jumlah IS NOT NULL";
          $disabled = "disabled ";  
      }
//      
   
       if($this->session->userdata("id") == 1 OR ($status == 2 AND $this->nbscache->get_olahan("permission", $this->session->userdata("id_privilege"), "approval-ro", "edit") !== FALSE)){
       $disabled ="";
      }
      
      if($this->session->userdata("id") == 1 OR $status == 11 OR $this->nbscache->get_olahan("permission", $this->session->userdata("id_privilege"), "edit-form-ro", "edit") !== FALSE){
       $disabled ="";
      }

      $id_mrp_supplier = $this->global_models->get_field("mrp_setting_harga_atk","id_mrp_supplier",array("id_mrp_setting_harga_atk" => '1'));
      $data = $this->global_models->get_query("SELECT B.name AS inventory_umum,C.title AS brand,D.title AS satuan,"
        . "A.id_mrp_inventory_spesifik,A.title AS title_spesifik,E.jumlah,G.harga,I.id_mrp_setting_lock_atk"
        . " FROM mrp_inventory_spesifik AS A"
        . " LEFT JOIN mrp_inventory_umum AS B ON A.id_mrp_inventory_umum = B.id_mrp_inventory_umum"
        . " LEFT JOIN mrp_brand AS C ON A.id_mrp_brand = C.id_mrp_brand"
        . " LEFT JOIN mrp_satuan AS D ON A.id_mrp_satuan = D.id_mrp_satuan"
        . " LEFT JOIN mrp_request_asset AS E ON (A.id_mrp_inventory_spesifik = E.id_mrp_inventory_spesifik AND E.id_mrp_request = '{$id_mrp_request}')"
        . " LEFT JOIN mrp_request AS F ON E.id_mrp_request = F.id_mrp_request"
        . " LEFT JOIN mrp_supplier_inventory AS G ON (A.id_mrp_inventory_spesifik = G.id_mrp_inventory_spesifik AND G.id_mrp_supplier = '{$id_mrp_supplier}')"
        . " LEFT JOIN mrp_setting_lock_atk_asset AS H ON A.id_mrp_inventory_spesifik = H.id_mrp_inventory_spesifik"
        . " LEFT JOIN mrp_setting_lock_atk AS I ON (H.id_mrp_setting_lock_atk = I.id_mrp_setting_lock_atk AND I.id_hr_company = '{$ls[0]->id_hr_company}' AND I.id_hr_master_organisasi ='{$ls[0]->id_hr_master_organisasi}')"        
        . " {$where} "
        . " GROUP BY A.id_mrp_inventory_spesifik"
        . " ORDER BY A.id_mrp_inventory_spesifik ASC"
        . " LIMIT {$start}, 10");
        
//       print $this->db->last_query(); die;
//    $data_array = json_decode($data);
//    $this->debug($data, true);
        
    
    if(count($data) > 0){
      $return['status'] = 2;
      $return['start']  = $start + 10;
    }
    else{
      $return['status'] = 3;
    }
    
//    $jenis = array(1 => "Habis Pakai", 2 => "Asset");
    $no = $start;
    foreach ($data AS $da){
        if($da->jumlah){
            $jumlah = $da->jumlah;
        }else{
            $jumlah = 0;
        }
        if($da->harga){
            $harga = number_format($da->harga);
        }else{
            $harga = "";
        }
        
        $data_id = "id=dt_id_mrp_inventory_spesifik_".$da->id_mrp_inventory_spesifik;
        $data_jumlah = "id=jumlah".$da->id_mrp_inventory_spesifik;
        $data_jumlah_rg = "id=jumlah_rg".$da->id_mrp_inventory_spesifik;
//        if($da->id_mrp_setting_lock_atk){
//            $dt_jumlah = $this->form_eksternal->form_input("jumlah[]", $jumlah, $disabled.$data_jumlah.'  style="width:100px;display:none" class="form-control jumlah input-sm" placeholder="Jumlah"');
//        }else{
//            $dt_jumlah = $this->form_eksternal->form_input("jumlah[]", $jumlah, $disabled.$data_jumlah.'  style="width:100px" class="form-control jumlah input-sm" placeholder="Jumlah"');
//        }
        
        if($da->id_mrp_setting_lock_atk){
            $dt_jumlah = $this->form_eksternal->form_input("jumlah[]", $jumlah, $data_jumlah.'  style="width:100px;display:none" class="form-control jumlah input-sm" placeholder="Jumlah"').
            $this->form_eksternal->form_input("jumlah_rg[]", $rg, $data_jumlah_rg.'  style="width:100px;display:none" class="form-control jumlah_rg input-sm" placeholder="rg"');
        }else{
            
            $dt_jumlah = $this->form_eksternal->form_input("jumlah[]", $jumlah, $disabled.$data_jumlah.'  style="width:100px" class="form-control jumlah input-sm" placeholder="Jumlah"').
            $this->form_eksternal->form_input("jumlah_rg[]", $rg, $data_jumlah_rg.'  style="width:100px;display:none" class="form-control jumlah_rg input-sm" placeholder="rg"');
        }
        
      $hasil[] = array(
          $no =$no + 1,
        $da->inventory_umum." ".$da->title_spesifik,
        $da->brand,
        $da->satuan,
        $harga,  
        $this->form_eksternal->form_input("id_mrp_inventory_spesifik[]", $da->id_mrp_inventory_spesifik, $data_id.' style="width:100px;display:none" class="form-control id_spesifik input-sm" placeholder=""').
        $dt_jumlah, 
//        "<div class='btn-group'>"
//          . "<a href='".site_url("mrp/mrp-master/add-inventory-spesifik/{$da->id_mrp_request}")."' type='button' class='btn btn-info btn-flat' style='width: 40px'><i class='fa fa-edit'></i></a>"
//        . "</div>"
      );
    }
    $return['hasil'] = $hasil;
//    $this->debug($return, true);
    print json_encode($return);
    die;
  }
  
  private function olah_request_order_code(&$kode){
    $this->load->helper('string');
    $kode_random = random_string('alnum', 6);
    $st_upper = strtoupper($kode_random);
    $kode = "RO".$st_upper;
    $cek = $this->global_models->get_field("mrp_request", "id_mrp_request", array("code" => $kode));
    if($cek > 0){
      $this->olah_request_order_code($kode);
    }
  }
}