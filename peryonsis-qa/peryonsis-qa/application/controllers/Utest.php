<?php

class Utest extends CI_Controller {

    public function __construct() {
        parent::__construct();
        
        
    }

    public function index()
    {
        $this->test_email();
    }


    public function test_email() {

        //$this->load->library("My_email_test");
        $this->load->library("My_email");
        
        $password = "test password";
    
        $subject = "Performans Değerlendirme Sistemi Giriş Şifresi";
        $message = "Şifreniz: ".$password."<br/><br/>";
        
        $to = ["shaddeler@santafarma.com.tr", "cisik@santafarma.com.tr"];
        $cc = "ddeniz@santafarma.com.tr";



        mail_gonder($subject, $message, $to,$cc="");
    }

}