<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends MY_Controller {
    

    public function index()
    {
        $this->template->goster('homev2', $data);
        //$this->template->goster('home', $data);
    }

    public function filteredDashboardData(){
        $data = json_decode(file_get_contents('php://input'), true);
        $result = [];
        if(!is_null($data['is_passive'])){
            $this->load->model('Ikyp_model');
            $res = $this->Ikyp_model->getDonemList($data);
            if($res){
                $result["data"] = $res; 
                $result["error"] = false; 
            }
            else{
                $result["data"] = []; 
                $result["error"] = false; 
            }
        }
        else{
            $result["message"] = 'Teknik bir aksaklık oluştu'; 
            $result["error"] = true; 
        }

        echo json_encode($result);
    }

    public function changePeriodStatus(){ // dönem açmak kapatmak için
        $data = json_decode(file_get_contents('php://input'), true);
        $result = [];
        if(is_null($data['status']) || is_null($data['id'])) {
            $result["message"] = 'Teknik bir aksaklık oluştu'; 
            $result["error"] = true; 
            echo json_encode($result);
            exit();
        }
        
        $this->load->model('Ikyp_model');
        $is_updated  = $this->Ikyp_model->changeDonemStatus($data);
        if($is_updated){
            $result["message"] = "İşlemi başarıyla gerçekleşmiştir."; 
            $result["error"] = false; 
        }
        else{
            $result["message"] = 'Teknik bir aksaklık oluştu'; 
            $result["error"] = true;
        }

        echo json_encode($result);
       
        
    }
   
    public function sdegistir()
    {
        $this->template->goster('sdegistir', $data);
    }
    
    public function sdegistir_k()
    {
        $pass = $this->input->post("pass");
        $pass_r = $this->input->post("pass_r");
        
        if (empty_f($pass) || empty_f($pass_r) || ($pass != $pass_r))
        {
            $alert_message = alert_message('Girilen bilgiler hatalı.');
            $this->session->set_flashdata('message', $alert_message);
            redirect('/home/sdegistir/');
            exit();
        }
        
        unset($kosul);
        $kosul['pernr'] = $this->session->userdata("pernr");
        $data['pernr'] = $this->session->userdata("pernr");
        $data['password'] = $this->encrypt->hash($pass);
        $data['sd'] = 2;
        $r = $this->genel_model->veri_kaydet("auth", $data, $kosul);
        
        if (!$r)
        {
            $alert_message = alert_message('Teknik bir aksaklık oluştu daha sonra tekrar deneyiniz.');
            $this->session->set_flashdata('message', $alert_message);
            redirect('/login/');
            exit();
        }
        
        $alert_message = alert_message('Şifreniz değiştirildi.', 'success');
        $this->session->set_flashdata('message', $alert_message);
        redirect('/home/');
        exit();
    }
    
    public function sdegistir_ss()
    {
        $this->template->goster('sdegistir_ss', $data);
    }
    
    public function sdegistir_k_ss()
    {
        $pass = $this->input->post("pass");
        $pass_r = $this->input->post("pass_r");
        
        if (empty_f($pass) || empty_f($pass_r) || ($pass != $pass_r))
        {
            $alert_message = alert_message('Girilen bilgiler hatalı.');
            $this->session->set_flashdata('message', $alert_message);
            redirect('/home/sdegistir_ss/');
            exit();
        }
        
        unset($kosul);
        $data['super_sifre'] = $this->encrypt->hash($pass);
        $r = $this->genel_model->veri_kaydet("super_sifre", $data);
        
        if (!$r)
        {
            $alert_message = alert_message('Teknik bir aksaklık oluştu daha sonra tekrar deneyiniz.');
            $this->session->set_flashdata('message', $alert_message);
            redirect('/home/');
            exit();
        }
        
        $alert_message = alert_message('Süper şifreniz değiştirildi.', 'success');
        $this->session->set_flashdata('message', $alert_message);
        redirect('/home/');
        exit();
    }
}
