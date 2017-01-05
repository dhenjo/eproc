<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Discount extends MX_Controller {
  function __construct() {
      if(strtolower($this->uri->segment(2,0)) == "promobankmega"){
          redirect("home");
      }elseif(strtolower($this->uri->segment(2,0)) == "promoonline"){
          redirect("home");
      }
      
  }
  
  public function aa() {
      print "aa";
      die;
  }
  
}