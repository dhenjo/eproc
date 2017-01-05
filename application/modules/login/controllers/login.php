<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends MX_Controller {
  function __construct() {
    $this->load->model('login/mlogin');
    $this->load->library('encrypt');
  }
  
	public function index(){
//          print  $this->encrypt->decode("5+xEvGXpQvEgVKUhLBRo4RteXsaNW+tmI7RFhJbn7L5SLLSRtA/OKf4TpmBXymjzfyH+6JeonDKBuuFFaW3njQ==");
//          die;
//    $this->debug(uri_string(), true);
    $config = array(
      array(
            'field'   => 'memuname', 
            'label'   => 'Username', 
            'rules'   => 'required'
         ),
      array(
            'field'   => 'mempass', 
            'label'   => 'Password', 
            'rules'   => 'required'
         ),
    );
    
    $this->form_validation->set_rules($config);
    $this->template->title('Login', "Sistem");
    if ($this->form_validation->run() == FALSE){
      $this->session->sess_destroy();
//      print "gagal";
			$this->template->build('main', 
        array(
              'url'     => base_url()."themes/".DEFAULTTHEMES."/",
              'field'   => array('memuname' => $this->input->post('memuname'))
            ));
      $this->template
        ->set_layout('login')
        ->build('main');
		}
		else{
      $cek_login = $this->mlogin->cek_login($this->input->post('memuname'), $this->input->post('mempass'));
      if($cek_login === true){
        redirect($this->session->userdata('dashbord'));
      }
      else{
        $this->session->sess_destroy();
        $this->template->build('main', 
        array(
              'url'     => base_url()."themes/".DEFAULTTHEMES."/",
              'field'   => array('memuname' => $this->input->post('memuname')),
			   'salah' => 1,
            ));
        $this->template
          ->set_layout('login')
          ->build('main');
      }
		}
	}
  function forgot_password(){
    if($this->input->post()){
      $new_password = random_string('alnum',8);
      $enpass = $this->encrypt->encode($new_password);
      $email_user = $this->global_models->get("m_users", array("email" => $this->input->post("email")));
      if($email_user){
        $this->global_models->update('m_users', array("id_users" => $email_user[0]->id_users),array('pass' => $enpass));

        //kirim email
        $this->load->library('email');
        $this->email->initialize($this->global_models->email_conf());
        $this->email->from('no-reply@antavaya.com', 'Administrator');
        $this->email->to($email_user[0]->email);
        $this->email->bcc('hendri.prasetyo@antavaya.com');

        $this->email->subject('Notifikasi Perubahan Password');
//         $this->email->message("tes");
        $this->email->message("
          Dear {$email_user[0]->name}<br />
          Berikut adalah cara akses untuk menggunakan aplikasi procurement.<br />
          Untuk dapat menggunakan aplikasi ini, user dapat menggunakan browser (chrome atau mozilla firefox)<br />
          Setelah halaman tampil, masukan alamat web :<br />
		  <br />
          <b>".site_url("login")." </b> (live)<br />
          <b> http://10.63.9.10:88/login </b> (trial)<br />
		  <br />
          Hasil dari halaman tersebut akan meminta user memasukan email dan password untuk login.
          Berikut akses user : <br />
		  <br />
          <b>user</b> => {$email_user[0]->email} <br />
          <b>pass</b> => {$new_password}<br />
          <br />
          Saat menggunakan password, diharapkan untuk mengetik manual password di atas.<br />
          Menghindari tambahan spasi jika di lakukan copy paste.<br />
          Jika sudah masuk, user dapat menggunakan aplikasi dan mengganti password sesuai keinginan user sendiri.<br />
          Tanggungjawab terhadap user ini adalah pemegang email.
          <br />
          <br />
		  Jika terdapat pertanyaan teknis, kritik, saran dan bug program dapat menghubungi IT di itdev@antavaya.com<br />
		  Jika terdapat pertanyaan mengenai inventory, item atau apapun yang berhubungan dan pengadaan dapat menghubungj procurement@antavaya.com
		  <br />
		  <br />
          Terima Kasih<br />
          System Internal AntaVaya
          ");
		  
/*
		  $this->email->send();
          
          print $this->email->print_debugger();

          die;
*/
          
        if($this->email->send() === TRUE){
          $pesan = "New Password has been send to your mail";
        }
        else{
          $pesan = "Password has been changed, but the email delivery fails. Please contact your admin";
        }
      }
      else{
        $pesan = "Your e-mail has not been registered";
      }
    }
    
    $this->template->build('forgot-password', 
    array(
          'url'         => base_url()."themes/".DEFAULTTHEMES."/",
          'pesan'       => $pesan
        ));
    $this->template
      ->set_layout('login')
      ->build('forgot-password');
  }
}