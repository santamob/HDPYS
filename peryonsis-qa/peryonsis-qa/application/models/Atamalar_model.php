<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Atamalar_model extends CI_Model {
    
    public function get_btm_tsm()
    {
        $this->db->where("donem", 3);
        $this->db->group_start();
            $this->db->like('poz_text', "TSM");
            $this->db->or_like('poz_text', "BTM");
        $this->db->group_end();
        $this->db->order_by("yil", "DESC");
        $this->db->order_by("ad_soyad", "ASC");
        $this->db->group_by("pernr");
        $query = $this->db->get("organizasyon_d");
        return $query->result_array();
    }
    
}