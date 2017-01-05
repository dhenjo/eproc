<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Guide_ajax extends MX_Controller {
    
  function __construct() {      
    $this->menu = $this->cek();
  }
  
  function master_guide_get(){
    $pst = $this->input->post();
    $data = $this->global_models->get_query("SELECT A.id_guide_master, A.title, A.tanggal, A.sort"
      . " FROM guide_master AS A"
      . " ORDER BY A.tanggal DESC LIMIT {$pst['start']}, 20");
    foreach ($data AS $da){
      $privilege = $this->global_models->get_query("SELECT A.id_privilege"
        . " ,(SELECT B.name FROM m_privilege AS B WHERE B.id_privilege = A.id_privilege) AS name"
        . " FROM guide_master_privilege AS A"
        . " WHERE A.id_guide_master = '{$da->id_guide_master}'");
      $tulis = "";
      foreach($privilege AS $prv){
        $tulis .= "{$prv->name}<br />";
      }
      $hasil[] = array(
        $da->sort,
        $da->tanggal,
        $da->title,
        $tulis,
        "<a href='".site_url("home/guide/master-add/{$da->id_guide_master}")."' type='button' class='btn btn-primary'><i class='fa fa-edit'></i></a>"
        . "<a href='".site_url("home/guide/master-copy/{$da->id_guide_master}")."' type='button' class='btn btn-success'><i class='fa fa-copy'></i></a>",
      );
      $banding[] = $da->id_guide_master;
    }
    if(!$hasil){
      $return['status'] = 3;
    }
    else{
      $return['status'] = 2;
      $return['start']  = $start + 20;
    }
    $return['hasil'] = $hasil;
    $return['banding'] = $banding;
    print json_encode($return);
    die;
  }
  
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */