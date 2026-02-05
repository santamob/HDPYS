<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Class Template
{
    private $ci;
    function __construct() 
    {
        $this->ci =& get_instance();
    }
    
    public function goster($filename,$data = NULL)
    {
        $kosul['bat<'] = date('Y-m-d');
        $order_by['yil,donem'] = 'desc';
        $select = '*';
        $tablo = $this->ci->genel_model->veri_cek_cs("donem_listesi", $select, $kosul, $order_by);
        $data['donemler_header'] = $tablo;
        
        $data["header"] = $this->ci->load->view("header_view",$data,TRUE);
        $data["header2"] = $this->ci->load->view("header2_view",$data,TRUE);
        $data["footer"] = $this->ci->load->view("footer_view","",TRUE);
        $data["footer2"] = $this->ci->load->view("footer2_view","",TRUE);
        $this->ci->load->view($filename . "_view", $data);
    }
}

?>
