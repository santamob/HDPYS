<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {


    public function __construct() {
        parent::__construct();
        
        if ($this->session->userdata('pernr'))
        {
            redirect("/home/");
        }
    }

    public function index()
    {
        
        $this->load->view('login_view');
    }

    // public function pass_test(){
    //     $string = "f5c4ed3b59cd63dbf2ba0015b0e0a2f4df813bfc";
    //    var_dump($this->encrypt->decode($string));
    // }

    // public function test_login()
    // {
        
    //     $this->load->view('login_CD_view');
    // }
    
    // Eposta adresi ile yeni şifre alır..
    public function new_password()
    {
      
        $username = $this->input->post('username');
        
        // Kullanıcı adı boşsa hata ver
        if (empty_f($username))
        {
            $alert_message = alert_message('Tüm alanları doldurunuz.');
            $this->session->set_flashdata('message', $alert_message);
            redirect('/login/');
            exit();
        }
        
        // Personel numarasını çek
        $kosul['email'] = $username;
        $select = 'pernr';
        $tablo = $this->genel_model->veri_cek_ts("aktif_pernr", $select, $kosul);
        
        if (!$tablo)
        {
            $alert_message = alert_message('Sistemde kaydınız bulunamadı. Lütfen insan kaynakları ile iletişime geçiniz.');
            $this->session->set_flashdata('message', $alert_message);
            redirect('/login/');
            exit();
        }
        
        $this->load->helper('string');
        $password = random_string('alnum', 6);
        $hash_pass = $this->encrypt->hash($password);

        unset($kosul);
        $data['pernr'] = $tablo['pernr'];
        $data['password'] = $hash_pass;
        $kosul['pernr'] = $tablo['pernr'];

        $r = $this->genel_model->veri_kaydet("auth", $data, $kosul);
        
        if (!$r)
        {
            $alert_message = alert_message('Teknik bir aksaklık oluştu daha sonra tekrar deneyiniz.');
            $this->session->set_flashdata('message', $alert_message);
            redirect('/login/');
            exit();
        }

        $subject = "Performans Değerlendirme Sistemi Giriş Şifresi";
        $message = "Şifreniz: ".$password."<br/><br/>";
        $message = $message."Giriş yapmak için <a href='".base_url()."'>tıklayınız</a>";
        $to = $username;
        
        //$to = "ckasapoglu@santafarma.com.tr";
        //($subject, $message,$to,$FromName,$cc,$with_pern)
       // mail_gonder($subject, $message, $to,"","",false);
        //mail_gonder($subject, $message, $username,"",$to,false); // Can bey cc'de gerçek kişi cc'de

        mail_gonder($subject, $message, $to);


        $alert_message = alert_message('Şifreniz mail adresinize gönderildi.', 'success');
        $this->session->set_flashdata('message', $alert_message);
        redirect('/login/');
        exit();
    }
    
    // Pernr ile şifre alır
    public function new_password_pernr()  //102668 -> Cem Işık
    {
        $pernr = $this->input->post('pernr');
        
        if (empty_f($pernr))
        {
            $alert_message = alert_message('Tüm alanları doldurunuz.');
            $this->session->set_flashdata('message', $alert_message);
            redirect('/login/');
            exit();
        }
        
        // Personel numarasını çek
        $kosul['pernr'] = $pernr;
        $select = 'pernr, email, stext';
        $tablo = $this->genel_model->veri_cek_ts("aktif_pernr", $select, $kosul);
        

        //print_r($tablo);
        //echo "<hr />";


        if (!$tablo)
        {
            $alert_message = alert_message('Sistemde kaydınız bulunamadı. Lütfen insan kaynakları ile iletişime geçiniz.');
            $this->session->set_flashdata('message', $alert_message);
            redirect('/login/');
            exit();
        }
        
        $this->load->helper('string');
        $password = random_string('alnum', 6);
        $hash_pass = $this->encrypt->hash($password);


        //echo $hash_pass;
        //echo "<hr />";

        unset($kosul);
        $data['pernr'] = $tablo['pernr'];
        $data['password'] = $hash_pass;
        $kosul['pernr'] = $tablo['pernr'];

        $r = $this->genel_model->veri_kaydet("auth", $data, $kosul);
        
        if (!$r)
        {
            $alert_message = alert_message('Teknik bir aksaklık oluştu daha sonra tekrar deneyiniz.');
            $this->session->set_flashdata('message', $alert_message);
            redirect('/login/');
            exit();
        }
                    
        $subject = "Performans Değerlendirme Sistemi Giriş Şifresi";
        $message = "Personel Numarası: ".$tablo['pernr']."<br/>";
        $message = $message."Personel Adı: ".$tablo['stext']."<br/>";
        $message = $message."Şifre: ".$password."<br/><br/>";
        $message = $message.date("Y-m-d H:i:s")."<br/><br/>";


        //echo $message;
        //echo "<hr />";
        
        /*
        $select = "pernr";
        $ak = $this->genel_model->veri_cek_cs("anahtar_kullanicilar", $select);
        if (!$ak)
        {
            $alert_message = alert_message('Anahtar kullanıcı bulunamadı.');
            $this->session->set_flashdata('message', $alert_message);
            redirect('/login/');
            exit();
        }
        
        unset($kosul);
        $select = "email";
        foreach ($ak as $key=>$arr)
        {
            $kosul['pernr'][] = $arr['pernr'];
        }
        $email = $this->genel_model->veri_cek_cs("aktif_pernr", $select, $kosul);

        //print_r($email);
        
        
        if (!$email)
        {
            $alert_message = alert_message('Email bulunamadı.');
            $this->session->set_flashdata('message', $alert_message);
            redirect('/login/');
            exit();
        }
        
        foreach ($email as $key=>$arr)
        {
            $to[] = $arr['email'];
        }
        */
        
                

        $to = ["yguler@santafarma.com.tr","dozcan@santafarma.com.tr","ckasapoglu@santafarma.com.tr"];
        //mail_gonder($subject, $message, $to,$cc);
        //mail_gonder($subject, $message, $to,"",$cc,true);

        //mail_gonder($subject, $message, $cc,"",$to,false);


        mail_gonder($subject, $message, $to);


        $alert_message = alert_message('Şifreniz ik yetkililerine gönderilmiştir. Şifrenizi ik yetkililerinden öğrenebilirsiniz.', 'success');
        $this->session->set_flashdata('message', $alert_message);

        redirect('/login/');
        exit();
        
    }

    public function test_email() {

        //$this->load->library("My_email_test");
        $this->load->library("My_email");
          
        $password = "test password";
      
        $subject = "Performans Değerlendirme Sistemi Giriş Şifresi";
        $message = "Şifreniz: ".$password."<br/><br/>";
        
        $to = ["shaddeler@santafarma.com.tr", "cisik@santafarma.com.tr"];
        $cc = "ddeniz@santafarma.com.tr";



        $result = $this->my_email->sendEmail($subject, $message, $to, "santafarma.net",$cc);

        if($result)
        {
            echo "OK";
        }

    }

    public function auth()
    {
        $username = $this->input->post('username');
        $pass = $this->input->post('pass');
        $order_by['id'] = "desc";
        $limit = 1;
        $superSifre = $this->genel_model->veri_cek_ts("super_sifre", "*", "", $order_by, $limit);
        $hashPass = $this->encrypt->hash($pass);
        if (empty_f($username) || empty_f($pass))
        {
            $alert_message = alert_message('Tüm alanları doldurunuz.');
            $this->session->set_flashdata('message', $alert_message);
            redirect('/login/');
            exit();
        }
        
        $username_explode = explode('@', $username);
        
        // Maille giriş..
        if (count($username_explode) == 2)
        {
            unset($kosul);
            $kosul['email'] = $username;
            $select = '*';
            $tablo = $this->genel_model->veri_cek_ts("aktif_pernr", $select, $kosul);

            if (!$tablo)
            {
                $alert_message = alert_message('performans.santafarma.com.tr\'e giriş izniniz yoktur. Lütfen insan kaynakları ile iletişime geçiniz.');
                $this->session->set_flashdata('message', $alert_message);
                redirect('/login/');
                exit();
            }
            
            // var_dump($username_explode['1']);
            // exit();
            if ($username_explode['1'] == 'santafarma.net' && $hashPass != $superSifre['super_sifre'])
            {
                $alert_message = alert_message('Sistemde kaydınız bulunamadı. Lütfen insan kaynakları ile iletişime geçiniz.');
                $this->session->set_flashdata('message', $alert_message);
                redirect('/login/');
                exit();
            }
        }
        else // pernr veya ad ile giriş
        {
            if (is_numeric($username)) // pernr ile giriş
            {
                unset($kosul);
                $kosul['pernr'] = $username;
                $select = '*';
                $tablo = $this->genel_model->veri_cek_ts("aktif_pernr", $select, $kosul);

                if (!$tablo)
                {
                    $alert_message = alert_message('Sistemde kaydınız bulunamadı. Lütfen insan kaynakları ile iletişime geçiniz.');
                    $this->session->set_flashdata('message', $alert_message);
                    redirect('/login/');
                    exit();
                }
                
               $username_explode2 = explode('@', $tablo['email']);
                if ($username_explode2['1'] == 'santafarma.net'  && $hashPass != $superSifre['super_sifre'])
                {
                    $alert_message = alert_message('performans.santafarma.com.tr\'e giriş izniniz yoktur. Lütfen insan kaynakları ile iletişime geçiniz.');
                    $this->session->set_flashdata('message', $alert_message);
                   redirect('/login/');
                    exit();
                }
            }
//            else // Ad ile giriş
//            {
//                $ad = true;
//                unset($kosul);
//                $kosul['usrid'] = $username;
//                $select = '*';
//                $tablo = $this->genel_model->veri_cek_ts("aktif_pernr", $select, $kosul);
//
//                if (!$tablo)
//                {
//                    $alert_message = alert_message('Sistemde kaydınız bulunamadı. Lütfen insan kaynakları ile iletişime geçiniz.');
//                    $this->session->set_flashdata('message', $alert_message);
//                    redirect('/login/');
//                    exit();
//                }
//            }
        }
        
        if ($ad) // AD ile giriş
        {
            $ldap = ldap_connect("santafarma.com.tr");
            ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION, 3);
            ldap_set_option($ldap, LDAP_OPT_REFERRALS, 0);
            $bind_rdn = 'santafarma\\'.$username;
            $bind = ldap_bind($ldap, $bind_rdn, $pass);
        }
        else
        {
            $hash_pass = $this->encrypt->hash($pass);
            unset($kosul);
            $kosul['pernr'] = $tablo['pernr'];
            $kosul['password'] = $hash_pass;
            $bind = $this->genel_model->veri_cek_ts("auth", "*", $kosul);
        }
        
        //Süper şifreyi çek
        unset($kosul);
        $order_by['id'] = "desc";
        $limit = 1;
        $super_sifre = $this->genel_model->veri_cek_ts("super_sifre", "*", "", $order_by, $limit);
        if (is_array($super_sifre))
        {
            $hash_pass = $this->encrypt->hash($pass);
            // Herkes adına giriş
            if ($hash_pass == $super_sifre['super_sifre'])
            {
                $bind = true;
            }
        }
        
        
        
        if (!$bind)
        {
            $alert_message = alert_message('Girilen bilgiler hatalı.');
            $this->session->set_flashdata('message', $alert_message);
            redirect('/login/');
            exit();
        }
        
        $this->session->set_userdata($tablo);
        if ($bind['sd'] == 1)
        {
            redirect("/home/sdegistir/");
        }
        else
        {
 
            redirect("/home/");
           
        }
    }

    
}
