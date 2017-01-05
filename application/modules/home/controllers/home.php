<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends MX_Controller {
    
  function __construct() {  
      
    //$this->menu = $this->cek();
    
//    $this->debug($this->menu, true);
  }
  
  function tes(){
      $get = $this->global_models->get("m_users",array("id_users IS NOT NULL" => NULL));
      print $this->db->last_query();
      die;
  }
  function aa(){
      $this->load->helper('captcha');
      // Captcha configuration
//		$config = array(
//			'img_path'      => './files/captcha_images/',
//			'img_url'       => base_url().'files/captcha_images/',
//			'img_width'     => '150',
//			'img_height'    => 90,
//			'word_length'   => 8,
//			'font_size'     => 22
//		);
      $config = array(
        'img_path'      => './files/captcha_images/',
        'img_url'       => base_url().'files/captcha_images/',
           'img_width' => '340',
                'font_path' => FCPATH . 'themes/lte/capcha/font/captcha4.ttf',
                'img_height' => 65,
                'expiration' => 7200,
                'font_size'     => 32

);
//    print_r($config);
//    die;
      
		$captcha = create_captcha($config);
		
		// Unset previous captcha and store new captcha word
		$this->session->unset_userdata('captchaCode');
		$this->session->set_userdata('captchaCode',$captcha['word']);
		
		// Send captcha image to view
		$data['captchaImg'] = $captcha['image'];
//		print_r($data);
//                print "<br>";
//                print $this->session->userdata("captchaCode");
//                die;
		// Load the view
//		$this->load->view('captcha/index', $data);
                
      $this->template->build("main-capcha", 
      array(
            'url'         => base_url()."themes/".DEFAULTTHEMES."/",
            'title'       => "Home",
            'title_small' => "",
            'foot'          => $foot,
            'css'           => $css,
            'data'          => $data,
            'breadcrumb'  => array(),
          ));
    $this->template
      ->set_layout('default')
      ->build("main-capcha");
    
  }
  
	public function index(){
//    $this->debug(site_url(), true);
//    set_cookie('test', 'coba');
//    setcookie("ci_session", "testsadsab dsgj sajh sgd agsfgyga");
//    $cookie = array(
//    'name'   => 'km',
//    'value'  => 'hilang',
//    'expire' => '86500'
//);
//delete_cookie("test");
//$this->input->set_cookie($cookie);
//    $this->debug($this->input->cookie('cart'), true);
//    $this->debug(get_cookie('cart', TRUE), true);
//    die;
    
    $css = "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/datatables/dataTables.bootstrap.css' rel='stylesheet' type='text/css' />"
      . "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/jQueryUI/jquery-ui-1.10.3.custom.min.css' rel='stylesheet' type='text/css' />";

    $foot = ""
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/jquery-ui-1.10.3.min.js' type='text/javascript'></script>"
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/datatables/jquery.dataTables.js' type='text/javascript'></script>"
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/datatables/dataTables.bootstrap.js' type='text/javascript'></script>";
    $foot .= "<script>"
        . 'var table = '
        . '$("#tableboxy").DataTable({'
          . '"order": [[ 0, "desc" ]],'
        . '});'
        . "$('#history-log').slimScroll({"
          . "height: '250px'"
        . "});"
      . '</script>';
	  
	  if(!$this->session->userdata("id"))
		  redirect("login");
	  
    $this->template->build("main", 
      array(
            'url'         => base_url()."themes/".DEFAULTTHEMES."/",
            'title'       => "Home",
            'title_small' => "",
            'foot'          => $foot,
            'css'           => $css,
            'breadcrumb'  => array(),
          ));
    $this->template
      ->set_layout('default')
      ->build("main");
	}
	public function contact_us($id_contact, $stat = 0, $pesan = ""){
    if(!$this->input->post(NULL)){
      if($this->session->userdata('id') == 1){
        $detail = $this->global_models->get("d_contact_us", array("id_contact_us" => $id_contact));
      }
      $this->template->build("contact-us", 
        array('message'     => urldecode ($pesan),
              'url'         => base_url()."themes/srabon/",
              'title_table' => "Cotact Us",
              'stat'        => $stat,
              'detail'      => $detail,
              'foot'        => ""
            ));
      $this->template
        ->set_layout('default')
        ->build("contact-us");
    }
    else{
      $pst = $this->input->post(NULL);
      $today = time();
      $kirim = array(
          "title"     =>  $pst['title'],
          "name"      =>  $pst['name'],
          "tanggal"   =>  date("Y-m-d"),
          "email"     =>  $pst['email'],
          "telp"      =>  $pst['telp'],
          "note"      =>  $pst['note']
      );
      if($this->global_models->insert("d_contact_us", $kirim)){
        $this->email->from($pst['email'], $pst['name']);
        $this->email->to('project@nusato.com');

        $this->email->subject('Opportunity New Client');
        $this->email->message("
          title     =>  {$pst['title']}
          name      =>  {$pst['name']}
          tanggal   =>  ".date("Y-m-d")."
          email     =>  {$pst['email']}
          telp      =>  {$pst['telp']}
          note      =>  {$pst['note']}
          ");

        if($this->email->send() === TRUE){
          redirect("home/contact-us/0/1/Data telah disimpan dan mengirim email kepada kami. Kami akan membaca dan merespon keinginan anda. Terima Kasih");
        }
        else{
          redirect("home/contact-us/0/1/Data telah disimpan tapi gagal mengirim email. Kami tetap dapat membaca dan merespon keinginan anda. Terima Kasih");
        }
      }
      else{
        redirect("home/contact-us/0/2/Data gagal disimpan harap coba lagi atau langsung contact kami dengan info contact di bawah. Terima Kasih");
      }
    }
	}
	public function opportunity_list(){
    $data = $this->global_models->get("d_contact_us");
    $this->template->build("opportunity-list",
      array('message'     => $pesan,
            'url'         => base_url()."themes/srabon/",
            'title_table' => "Opportunity",
            'data'        => $data,
            'foot'        => ""
          ));
    $this->template
      ->set_layout('default')
      ->build("opportunity-list");
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */