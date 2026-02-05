<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ikyp_model extends CI_Model {
    
    public function form_kaydet($data1, $data2)
    {
        $this->db->trans_start(); // Query will be rolled back
        
        $this->db->insert("formlar", $data1);
        $form_id = $this->db->insert_id();
        
        foreach ($data2 as $key=>$arr)
        {
            $data2[$key]['form_id'] = $form_id;
        }
        
        $this->db->insert_batch("formlar_detay", $data2);
        
        $this->db->trans_complete();
        
        if ($this->db->trans_status() === FALSE)
        {
            return false;
        }
        else
        {
            return true;
        }
    }
    
    public function form_atamasi_kaydet($data, $yil, $donem, $pernr_dizi, $form_id_dizi)
    {
        $this->db->trans_start(); // Query will be rolled back
        
        $this->db->where("yil", $yil);
        $this->db->where("donem", $donem);
        $this->db->where_in("pernr", $pernr_dizi);
        $this->db->delete("form_atamalari");
        
        $this->db->insert_batch("form_atamalari", $data);
        
        $this->db->where("yil", $yil);
        $this->db->where("donem", $donem);
        $this->db->where_in("pernr", $pernr_dizi);
        $this->db->where_not_in("form_id", $form_id_dizi);
        $this->db->delete("degerlendirmeler");
        
        $this->db->trans_complete();
        
        if ($this->db->trans_status() === FALSE)
        {
            return false;
        }
        else
        {
            return true;
        }
    }
    
    public function havuz_atamasi_kaydet($data, $yil, $donem, $pernr_dizi, $havuz_id)
    {
        $this->db->trans_start(); // Query will be rolled back
        
        $this->db->where("yil", $yil);
        $this->db->where("donem", $donem);
        $this->db->where("havuz_id", $havuz_id);
        $this->db->delete("kademelendirme_atamalari");
        
        $this->db->insert_batch("kademelendirme_atamalari", $data);
        
        $this->db->trans_complete();

        // var_dump($this->db->error());
        // exit();
        
        if ($this->db->trans_status() === FALSE)
        {
            return false;
        }
        else
        {
            return true;
        }
    }
    
    public function form_atamalari($yil, $donem)
    {
        $this->db->select('form_atamalari.*, formlar.form_adi');
        $this->db->from('form_atamalari');
        $this->db->join('formlar', 'form_atamalari.form_id = formlar.form_id', 'left');
        $this->db->where('form_atamalari.yil', $yil);
        $this->db->where('form_atamalari.donem', $donem);
        $query = $this->db->get();
        return $query->result_array();
    }
    
    public function kademelendirme_atamalari($yil, $donem)
    {
        $this->db->select('kademelendirme_atamalari.*, kademelendirme_havuzlari.havuz_text');
        $this->db->from('kademelendirme_atamalari');
        $this->db->join('kademelendirme_havuzlari', 'kademelendirme_atamalari.havuz_id = kademelendirme_havuzlari.havuz_id', 'left');
        $this->db->where('kademelendirme_atamalari.yil', $yil);
        $this->db->where('kademelendirme_atamalari.donem', $donem);
        $query = $this->db->get();
        return $query->result_array();
    }
    
    public function donem_sil($yil, $donem)
    {
        $this->db->trans_start(); // Query will be rolled back
        
        $this->db->where("yil", $yil);
        $this->db->where("donem", $donem);
        $this->db->delete("degerlendirmeler");
        
        $this->db->where("yil", $yil);
        $this->db->where("donem", $donem);
        $this->db->delete("kademelendirme_sonuclari");
        
        $this->db->where("yil", $yil);
        $this->db->where("donem", $donem);
        $this->db->delete("onaylar");
        
        $this->db->where("yil", $yil);
        $this->db->where("donem", $donem);
        $this->db->delete("form_atamalari");
        
        $this->db->where("yil", $yil);
        $this->db->where("donem", $donem);
        $this->db->delete("kademelendirme_atamalari");
        
        $this->db->where("yil", $yil);
        $this->db->where("donem", $donem);
        $this->db->delete("organizasyon_d");
        
        $this->db->where("yil", $yil);
        $this->db->where("donem", $donem);
        $this->db->delete("organizasyon_d_temp");
        
        $this->db->trans_complete();
        
        if ($this->db->trans_status() === FALSE)
        {
            return false;
        }
        else
        {
            return true;
        }
    }
    
    public function per_sil($yil, $donem, $pernr, $silinecek_id)
    {
        $this->db->trans_start(); // Query will be rolled back
        
        $this->db->where("yil", $yil);
        $this->db->where("donem", $donem);
        $this->db->where_in("pernr", $pernr);
        $this->db->delete("degerlendirmeler");
        
        $this->db->where("yil", $yil);
        $this->db->where("donem", $donem);
        $this->db->where_in("pernr", $pernr);
        $this->db->delete("form_atamalari");
        
        $this->db->where("yil", $yil);
        $this->db->where("donem", $donem);
        $this->db->where_in("pernr", $pernr);
        $this->db->delete("kademelendirme_atamalari");
        
        $this->db->where("yil", $yil);
        $this->db->where("donem", $donem);
        $this->db->where_in("pernr", $pernr);
        $this->db->delete("kademelendirme_sonuclari");
        
        $this->db->where("yil", $yil);
        $this->db->where("donem", $donem);
        $this->db->where_in("id", $silinecek_id);
        $this->db->delete("organizasyon_d");
        
        $this->db->trans_complete();
        
        if ($this->db->trans_status() === FALSE)
        {
            return false;
        }
        else
        {
            return true;
        }
    }

    public function getDonemList($data){
        //SELECT * FROM `donem_listesi` WHERE BIT < NOW()  OR lokasyon IS NULL
        
        $now = date("Y-m-d");
      
        $this->db->select('*'); 

        if($data['is_passive'] != 2){
            $this->db->where('donem_status',$data['is_passive']);  
        }
        
        $this->db->order_by('id',DESC);
        $query = $this->db->get("donem_listesi");

        
        return $query->result();
        
       
    }


    public function changeDonemStatus($data){
        
        $donem_id = $data['id'];
        $this->db->trans_start(); 
        
        $this->db->where('id', $donem_id);
        $update = [
            "donem_status"=>$data["status"]
        ];
        $is_updated = $this->db->update("donem_listesi",$update);
        
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE)
        {
            return false;
        }
        else
        {
            return true;
        }


    }

    public function getDonemList_yedek($data){
        //SELECT * FROM `donem_listesi` WHERE BIT < NOW()  OR lokasyon IS NULL
        
        $now = date("Y-m-d");
      
        $this->db->select('*'); 
        
        if($data['is_passive'] == 0){  // pasif 
            $this->db->where('bit <',$now);   
            $this->db->or_where('lokasyon',NULL); 
        }
        else if($data['is_passive'] == 1){ // aktif

            $this->db->where('bit >',$now);  
            $this->db->where('lokasyon IS NOT',NULL);  
            // $this->db->like('lokasyon', 'Fabrika');
            // $this->db->like('lokasyon', 'Merkez');
            // $this->db->like('lokasyon', 'Saha');
        } 
        
        $this->db->order_by('id',DESC);
        $query = $this->db->get("donem_listesi");

        // echo $this->db->last_query();
        // exit();
        return $query->result();
        
       
    }


    public function getVeriKaynagiAv(){
        $query = $this->db->get("veri_kaynagi_av");
        return $query->result_array();
    }

    public function getVeriHesapamaAv(){
        $query = $this->db->get("veri_hesaplama_av");
        return $query->result_array();
    }

    public function getDegerlendirmeTuruAv(){
        $query = $this->db->get("degerlendirme_turu_av");
        return $query->result_array();
    }


    public function exportFormlarExcelToDb($data){

        $this->db->trans_start(); 
        $this->db->insert("gostergelerv2", $data["main"]);
        // if($this->db->error()){
        //     var_dump($this->db->error());
        //     exit();
        // }
        $g_id =  $this->db->insert_id();
        $ft_id = $data["main"]["ft_id"];

        //$g_id = 1;
        if($g_id > 0){
            $result = [
                "g_id"=>$g_id,
                "guncelleyen"=>$this->session->userdata('pernr'),
                "durum"=>1
            ];
            if(!empty($data["info"]["gostergeler"])){
                foreach($data["info"]["gostergeler"] as $key => $gosterge){
                    if( $ft_id == 1){
                        $result["kademe_text"] = $key;
                        if($gosterge["AS"] === "-"|| $gosterge["ÜS"] === "-"){
                            $result["kademe_as"] = NULL;
                            $result["kademe_us"] = NULL;
                        }
                        else{
                            $result["kademe_as"] = $gosterge["AS"];
                            $result["kademe_us"] = $gosterge["ÜS"];
                        }
                        
                        $this->db->insert("gostergelerv2_kademeler", $result);
                    }
                    else if($ft_id == 2 || $ft_id == 3){
                        $value = array_values($gosterge);
                        $result["kademe_text"] = $value[0];
                        $this->db->insert("gostergelerv2_kademeler", $result);
                    }
                }   
            }
        }

        $this->db->trans_complete();
        
        if ($this->db->trans_status() === FALSE)
        {
            return false;
        }
        else
        {
            return true;
        }

    }



    public function addGostergev2($data){

        $this->db->trans_start(); 
      
        $this->db->insert("gostergelerv2", $data["main"]);
      
        $g_id =  $this->db->insert_id();

        echo $g_id;
        $ft_id = $data["main"]["ft_id"];
        // // var_dump($this->db->error());
        // // exit();
        if($g_id > 0){
            $result = [
                "g_id"=>$g_id,
                "guncelleyen"=>$this->session->userdata('pernr'),
                "durum"=>1,
                "g_time"=> date('Y-m-d H:i:s')
            ];
            if(!empty($data["kademeler"])){
                foreach($data["kademeler"] as $key => $gosterge){
                    if( $ft_id == 1){
                        $result["kademe_text"] =$gosterge["text"];
                        $result["kademe_as"] = $gosterge["k_as"];
                        $result["kademe_us"] = $gosterge["k_us"];
                        $this->db->insert("gostergelerv2_kademeler", $result);
                    }
                    else if($ft_id == 2 || $ft_id == 3){
                        $result["kademe_text"] = $gosterge["text"];
                        $this->db->insert("gostergelerv2_kademeler", $result);
                    }
                }   
            }
        }

        $this->db->trans_complete();
        
        if ($this->db->trans_status() === FALSE)
        {
            return false;
        }
        else
        {
            return true;
        }

       

    }


    public function updateGosterv2($data){
        
        $g_id = $data["main"]['g_id'];
        
        $this->db->trans_start(); 
        
        $this->db->where('g_id', $g_id);
        $is_updated = $this->db->update("gostergelerv2", $data["main"]);
        $ft_id = $data["main"]["ft_id"];
        // // var_dump($this->db->error());
        // // exit();

        if($is_updated){
            $deleted = array(
                "durum"=>0
            );
            $this->db->where('g_id', $g_id);
            $this->db->update("gostergelerv2_kademeler", $deleted);

            $result = [
                "g_id"=>$g_id,
                "guncelleyen"=>$this->session->userdata('pernr'),
                "durum"=>1,
                "g_time"=> date('Y-m-d H:i:s')
            ];
            if(!empty($data["kademeler"])){
                foreach($data["kademeler"] as $key => $gosterge){
                    if( $ft_id == 1){
                        $result["kademe_text"] = $gosterge["text"];
                        $result["kademe_as"] = $gosterge["k_as"];
                        $result["kademe_us"] = $gosterge["k_us"];
                        
                        $this->db->insert("gostergelerv2_kademeler", $result);
                    }
                    else if($ft_id == 2 || $ft_id == 3){
                        $result["kademe_text"] = $gosterge["text"];
                        $this->db->insert("gostergelerv2_kademeler", $result);
                    }
                }   
            }
        }

        $this->db->trans_complete();
        
        if ($this->db->trans_status() === FALSE)
        {
            return false;
        }
        else
        {
            return true;
        }
           
        

    }

    public function IsThereAnyDuplicatedGostergeExist()
    {
        $result = $this->db->query("SELECT g_adi,COUNT(g_adi) as duplicated_number FROM `gostergelerv2` WHERE durum = 1 GROUP BY g_adi HAVING COUNT(g_adi) > 1 ");

        return $result->num_rows() > 0 ? $result->result() : null;

    }
        

    
    
}

