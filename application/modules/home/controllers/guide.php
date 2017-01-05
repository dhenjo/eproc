<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Guide extends MX_Controller {
    
  function __construct() {      
    $this->menu = $this->cek();
  }
  
  function master(){
    $css = "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/datatables/dataTables.bootstrap.css' rel='stylesheet' type='text/css' />"
      . "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/jQueryUI/jquery-ui-1.10.3.custom.min.css' rel='stylesheet' type='text/css' />"
      . "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/datepicker/datepicker3.css' rel='stylesheet' type='text/css' />"
      . "";
    
    $foot = ""
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/datepicker/bootstrap-datepicker.js' type='text/javascript'></script>"
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/datatables/jquery.dataTables.js' type='text/javascript'></script>"
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/datatables/dataTables.bootstrap.js' type='text/javascript'></script>"
      . "";
    $foot .= "<script>"
      
      . "$(function() {"
        . "var table = "
        . "$('#tableboxy').DataTable({"
          . "'order': [[ 0, 'asc' ]]"
        . "});"
      
      . "$('.qtyubah').keyup(function(){"
      . "$(this).val();"
      . "$(this).attr('isi');"
      . ""
      . "});"
      
        . 'function ambil_data(table, start){'
          . 'ajax_inventory = $.post("'.site_url("home/guide-ajax/master-guide-get").'", {start: start}, function(data){'
            . 'var hasil = $.parseJSON(data);'
            . '$("#loader-page").show();'
            . 'if(hasil.status == 2){'
              . 'if(hasil.hasil){'
                . 'for(ind = 0; ind < hasil.hasil.length; ++ind){'
                  . "var rowNode = table.row.add(hasil.hasil[ind]).draw().node();"
                  . "$( rowNode ).attr('isi',hasil.banding[ind]);"
                . '}'
              . '}'
              . 'ambil_data(table, hasil.start);'
            . '}'
            . 'else{'
              . '$("#loader-page").hide();'
            . '}'
          . '});'
        . '}'
      
        . 'ambil_data(table, 0);'
      
      . "});"
      
      . "function str_replace(search, replace, subject, count){"
        . "var i = 0, j = 0, temp = '', repl = '', sl = 0, fl = 0, f = [].concat(search), r = [].concat(replace)"
          . ", s = subject, ra = Object.prototype.toString.call(r) === '[object Array]'"
          . ", sa = Object.prototype.toString.call(r) === '[object Array]'"
          . ";"
        . "s = [].concat(s);"
        . "if(count){"
          . "this.window[count] = 0;"
        . "}"
        . "for(i = 0, sl = s.length; i < sl; i++){"
          . "if(s[i] === ''){"
            . "continue;"
          . "}"
          . "for(j = 0, fl = f.length; j < fl; j++){"
            . "temp = s[i] + '';"
            . "repl = ra ? (r[j] !== undefined ? r[j] : '') : r[0];"
            . "s[i] = (temp)"
              . ".split(f[j])"
              . ".join(repl);"
            . "if(count && s[i] !== temp){"
              . "this.window[count] += (temp.length - s[i].length) / f[j].lenght;"
            . "}"
          . "}"
        . "}"
        . "return sa ? s : s[0];"
      . "}"

      . "function number_format(number, decimals, dec_point, thousands_sep){"
        . "number = (number + '').replace(/[^0-9+\-Ee.]/g, '');"
        . "var n = !isFinite(+number) ? 0 : +number"
          . ", prec = !isFinite(+decimals) ? 0 : Math.abs(decimals)"
          . ", sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep"
          . ", dec = (typeof dec_point === 'undefined') ? '.' : dec_point"
          . ", s = ''"
          . ", toFixedFix = function (n, prec){"
              . "var k = Math.pow(10, prec);"
              . "return '' + (Math.round(n * k) / k).toFixed(prec);"
            . "};"
        . "s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');"
        . "if(s[0].length > 3){"
          . "s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);"
        . "}"
        . "if((s[1] || '').length < prec){"
          . "s[1] = s[1] || '';"
          . "s[1] += new Array(prec - s[1].length + 1).join('0');"
        . "}"
        . "return s.join(dec);"
      . "}"
      
      . "</script>";
         
    $this->template->build('master', 
      array(
        'url'           => base_url()."themes/".DEFAULTTHEMES."/",
        'menu'          => "home/guide/master",
        'title'         => lang("Master"),
        'foot'          => $foot,
        'css'           => $css,
      ));
    $this->template
      ->set_layout('default')
      ->build('master');
  }
  
  function index(){
    $data = $this->global_models->get_query("SELECT A.title, A.note, A.tanggal, A.id_guide_master"
      . " FROM guide_master AS A"
      . " WHERE A.id_guide_master IN (SELECT B.id_guide_master FROM guide_master_privilege AS B WHERE B.id_privilege = '{$this->session->userdata("id_privilege")}')"
      . " ORDER BY A.sort ASC");
//    $this->debug($data, true);
    $this->template->build('guide', 
      array(
        'url'           => base_url()."themes/".DEFAULTTHEMES."/",
        'menu'          => "home/guide",
        'title'         => lang("Users Guide"),
        'foot'          => $foot,
        'css'           => $css,
        'data'          => $data,
      ));
    $this->template
      ->set_layout('default')
      ->build('guide');
  }
  
  function master_add($id_guide_master){
    $pst = $this->input->post();
    if($pst){
      
      if($pst['id_detail']){
        $post = array(
          "tanggal"             => date("Y-m-d H:i:s"),
          "title"               => $pst['title'],
          "sort"                => $pst['sort'],
          "note"                => $pst['note'],
          "update_by_users"     => $this->session->userdata("id"),
        );

        $data = $this->global_models->update("guide_master", array("id_guide_master" => $pst['id_detail']), $post);
        $this->global_models->delete("guide_master_privilege", array("id_guide_master" => $pst['id_detail']));
        foreach($pst['id_privilege'] AS $priv){
          if($priv){
            $privilege[] = array(
              "id_guide_master"     => $pst['id_detail'],
              "id_privilege"        => $priv,
              "create_by_users"     => $this->session->userdata("id"),
              "create_date"         => date("Y-m-d H:i:s"),
            );
          }
        }
        if($privilege)
          $this->global_models->insert_batch("guide_master_privilege", $privilege);
      }
      else{
        $post = array(
          "tanggal"             => date("Y-m-d H:i:s"),
          "title"               => $pst['title'],
          "sort"                => $pst['sort'],
          "note"                => $pst['note'],
          "create_by_users"     => $this->session->userdata("id"),
          "create_date"         => date("Y-m-d H:i:s"),
        );

        $data = $this->global_models->insert("guide_master", $post);
        foreach($pst['id_privilege'] AS $priv){
          if($priv){
            $privilege[] = array(
              "id_guide_master"     => $data,
              "id_privilege"        => $priv,
              "create_by_users"     => $this->session->userdata("id"),
              "create_date"         => date("Y-m-d H:i:s"),
            );
          }
        }
        if($privilege)
          $this->global_models->insert_batch("guide_master_privilege", $privilege);
      }
      
      if($data)
        $this->session->set_flashdata('success', 'Data tersimpan');
      else
        $this->session->set_flashdata('notice', 'Data tidak tersimpan');
      
      redirect("home/guide/master");
    }
    
    $css = ""
      . "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/jQueryUI/jquery-ui-1.10.3.custom.min.css' rel='stylesheet' type='text/css' />"
      . "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/datepicker/datepicker3.css' rel='stylesheet' type='text/css' />"
    . "";
    
    $foot = ""
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/datepicker/bootstrap-datepicker.js' type='text/javascript'></script>"
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/jquery.price_format.1.8.min.js' type='text/javascript'></script>"
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/ckeditor/ckeditor.js' type='text/javascript'></script>"
      . "";
    $foot .= "<script>"

      . "$(function() {"
        . "CKEDITOR.replace('editor2');"
        . "$( '.tanggal' ).datepicker({"
          . "showOtherMonths: true,"
          . "format: 'yyyy-mm-dd',"
          . "selectOtherMonths: true,"
          . "selectOtherYears: true"
        . "});"
        . "$('.harga').priceFormat({"
          . "prefix: '',"
          . "centsLimit: 0"
        . "});"
      . "});"

    . "</script>";
    
    $detail = $this->global_models->get("guide_master", array("id_guide_master" => $id_guide_master));
    $privilege = $this->global_models->get("guide_master_privilege", array("id_guide_master" => $id_guide_master));
    
    $this->template->build('master-add', 
      array(
        'url'           => base_url()."themes/".DEFAULTTHEMES."/",
        'menu'          => "home/guide/master",
        'title'         => lang("Guide"),
        'foot'          => $foot,
        'css'           => $css,
        'detail'        => $detail,
        'priv'          => $privilege,
      ));
    $this->template
      ->set_layout('default')
      ->build('master-add');
  }
  
  function master_copy($id_guide_master){
    $load = $this->global_models->get("guide_master", array("id_guide_master" => $id_guide_master));
    $post = array(
      "tanggal"             => date("Y-m-d H:i:s"),
      "title"               => $load[0]->title,
      "sort"                => $load[0]->sort,
      "note"                => $load[0]->note,
      "create_by_users"     => $this->session->userdata("id"),
      "create_date"         => date("Y-m-d H:i:s"),
    );

    $data = $this->global_models->insert("guide_master", $post);
    $priv_load = $this->global_models->get("guide_master_privilege", array("id_guide_master" => $id_guide_master));
    foreach($priv_load AS $priv){
      if($priv){
        $privilege[] = array(
          "id_guide_master"     => $data,
          "id_privilege"        => $priv->id_privilege,
          "create_by_users"     => $this->session->userdata("id"),
          "create_date"         => date("Y-m-d H:i:s"),
        );
      }
    }
    if($privilege)
      $this->global_models->insert_batch("guide_master_privilege", $privilege);
    

    if($data)
      $this->session->set_flashdata('success', 'Data tersimpan');
    else
      $this->session->set_flashdata('notice', 'Data tidak tersimpan');

    redirect("home/guide/master");
  }
  
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */