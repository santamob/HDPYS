<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Raporlar extends MY_Controller {
    
    public function __construct() {
        parent::__construct();
        
        // Yetki kontrolü sadee ik anahtar kullanıcıları erişebilir..
        if ($this->session->userdata('pernr') != 101993 && $this->session->userdata('pernr') != 101081 && $this->session->userdata('pernr') != 100003 && $this->session->userdata('pernr') != 102576)
        {
            $alert_message = alert_message('Hata.');
            $this->session->set_flashdata('message', $alert_message);
            redirect('/ikyp/donem_listesi/');
            exit();
        }
    }
    
    public function genel_rapor()
    {
        unset($kosul);
        $select = '*';
        $data['donem_listesi'] = $this->genel_model->veri_cek_cs("donem_listesi", $select, $kosul);
        
        $this->template->goster('genel_rapor', $data);
    }
    
}
