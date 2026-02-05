<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Genel_model extends CI_Model {
    
    // Tablodan veri siler
    public function veri_sil($tablo_adi, $kosul = NULL)
    {
        $this->db->trans_start(); // Query will be rolled back
        
        foreach ($kosul as $key=>$val)
        {
            $this->db->where($key, $val);
        }
        $this->db->delete($tablo_adi);
        
        $sql = $this->db->last_query();
        
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
    // Organizasyon tablosu
    public function  perskGetir($id)
    {
   $select = '*';
        $kosul['pernr'] = '101081';
        $tabloAdi = 'organizasyon';
        $orgTablo = $this->veri_cek_cs($tabloAdi,$select,$kosul);
        return $orgTablo;
    }
    // Organizasyon tablosu
    public function  lokasyonGetir($tabloAdi,$select,$kosul)
    {
    $orgTablo = $this->veri_cek_cs($tabloAdi,$select,$kosul);
        return $orgTablo;
    }
    // Tabloya yeni veri ekler
    public function veri_kaydet($tablo_adi, $data, $kosul = NULL)
    {
        $this->db->trans_start(); // Query will be rolled back
        
        // Koşul dizisi varsa önce sil
        if (isset($kosul))
        {
            foreach ($kosul as $key=>$val)
            {
                $this->db->where($key, $val);
            }
            $this->db->delete($tablo_adi);
            
            $sql = $this->db->last_query();
        }
        
        //echo $this->db->last_query();
        
        $this->db->insert($tablo_adi, $data);
        
        $sql = $this->db->last_query();
        
        
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
    
    // Tabloya yeni veri ekler
    public function veri_kaydet_cs($tablo_adi, $data, $kosul = NULL, $where_in = NULL)
    {
        //$this->db->trans_start(); // Query will be rolled back
        
        // Koşul dizisi varsa önce sil
        if (isset($kosul))
        {
            foreach ($kosul as $key=>$val)
            {
                if (is_array($val))
                {
                    foreach ($val as $key1=>$val1)
                    {
                        $this->db->where($key, $val1);
                    }
                }
                else
                {
                    $this->db->where($key, $val);
                }
            }
        }
        
        if (is_array($where_in))
        {
            foreach ($where_in as $key2=>$arr2)
            {
                $this->db->where_in($key2, $arr2);
            }
        }
        
        if (isset($kosul) || is_array($where_in))
        {
            $this->db->delete($tablo_adi);
 //           echo $this->db->last_query();
 //           echo "<br />";  
        }
        
//        echo $this->db->last_query();
//        exit();
        
        
        
        foreach ($data as $key=>$arr)
        {
            $id = $this->db->insert($tablo_adi, $arr);
            
            //echo $this->db->insert_id()."<br />";
            //echo $this->db->last_query();
            //echo "<br/>";
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
    
    // Tabloyu günceller
    public function veri_guncelle($tablo_adi, $data, $kosul = NULL)
    {
        $this->db->trans_start();
        
        if (isset($kosul))
        {
            foreach ($kosul as $key=>$val)
            {
                $this->db->where($key, $val);
            }
        }
        
        $this->db->update($tablo_adi, $data);
                
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
    
    // Tabloyu günceller
    public function veri_guncelle_cs($tablo_adi, $data, $kosul = NULL)
    {
        $this->db->trans_start();
        
        if (isset($kosul))
        {
            foreach ($kosul as $key=>$val)
            {
                $this->db->where($key, $val);
            }
        }
        
        $this->db->update_batch($tablo_adi, $data);
                
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
    
    public function veri_cek_ts($tablo_adi, $select = NULL, $kosul = NULL, $order_by = NULL, $limit = NULL, $group_by = NULL)
    {
        if (isset($select))
        {
            $this->db->select($select);
        }
        
        if (isset($kosul))
        {
            foreach ($kosul as $key=>$val)
            {
                if (is_array($val))
                {
                    $this->db->group_start();
                    foreach ($val as $key1=>$val1)
                    {
                        $this->db->or_where($key, $val1);
                    }
                    $this->db->group_end();
                }
                else
                {
                    $this->db->where($key,$val);
                }
            }
        }
        
        if (isset($order_by))
        {
            foreach ($order_by as $key=>$val)
            {
                $this->db->order_by($key,$val);
            }
        }
        
        if (isset($group_by))
        {
            $this->db->group_by($group_by);
        }
        
        if (isset($limit))
        {
            $this->db->limit($limit);
        }
      
        
        $query = $this->db->get($tablo_adi);
        
        $sql = $this->db->last_query();
        //echo $sql."<br/>";
       
        /*
        if ($query->num_rows() == 1)
        {

            return $query->row_array();
        }
        else
        {
            return false;
        }
        */

        if ($query->num_rows() > 0)
        {

            $data = $query->result_array(); 
            return $data[0]; // ilk datayı almak doğrumu yoksa son datayı mı almak lazım hangisi güncelldir. //Örnek Sevilay Arslan örneği
        }
        else
        {
            return false;
        }
    }
    
    public function veri_cek_cs($tablo_adi, $select = NULL, $kosul = NULL, $order_by = NULL, $limit = NULL, $group_by = NULL, $where_in = NULL)
    {
        if (isset($select))
        {
            $this->db->select($select);
        }
        
        if (isset($kosul))
        {
            foreach ($kosul as $key=>$val)
            {
                if (is_array($val))
                {
                    $this->db->group_start();
                    foreach ($val as $key1=>$val1)
                    {
                        $this->db->or_where($key, $val1);
                    }
                    $this->db->group_end();
                }
                else
                {
                    $this->db->where($key,$val);
                }
            }
        }
        
        if (is_array($where_in))
        {
            foreach ($where_in as $key2=>$arr2)
            {
                $this->db->where_in($key2, $arr2);
            }
        }
        
        if (isset($order_by))
        {
            foreach ($order_by as $key=>$val)
            {
                $this->db->order_by($key,$val);
            }
        }
        
        if (isset($group_by))
        {
            $this->db->group_by($group_by);
        }
        
        if (isset($limit))
        {
            $this->db->limit($limit); 
        }

       //$this->db->limit(10); // ben ekledim.
      
        
        $query = $this->db->get($tablo_adi);
        
        $sql = $this->db->last_query();

        //echo $sql."<br>";
        //exit();

        
        
        if ($query->num_rows() > 0)
        {
            return $query->result_array();
        }
        else
        {
            return false;
        }
    }

    public function veri_cek_cs2($tablo_adi,$table_data)
    {

        
       

        $this->db->select("*");
        $this->db->where("durum", 1);
        $query = $this->db->get($tablo_adi);
       
        $totalData = $query->num_rows();
        $recordsFiltered =  $totalData;
        
    
        if($table_data["mysearch_list"] == null){
           
            $result =[];
            $this->db->select("*");
            $this->db->where("durum", 1);
            $this->db->order_by("g_id","DESC");
            $this->db->limit($table_data["length"], $table_data["start"]);
            $query= $this->db->get($tablo_adi);
            $result = $query->result_array();
        }
        elseif ($table_data["mysearch_list"] != null)
        {
            $result =[];

            $this->db->select("*");
           
            $this->db->where("durum", 1);
            $this->db->order_by("g_id","DESC");
            foreach($table_data["mysearch_list"] as $key=>$search_items){

                //$this->db->where($key, $search_items);
                if($key =='g_adi'){
                    $this->db->like($key, $search_items);
                }
                else{

                    $this->db->where($key, $search_items);
                }
              
            }
            $query = $this->db->get($tablo_adi);
            $totalData = $query->num_rows();
           

            $this->db->select("*");
            $this->db->order_by("g_id","DESC");
            foreach($table_data["mysearch_list"] as $key=>$search_items){
                //$this->db->where($key, $search_items);
                
                if($key =='g_adi'){
                    $this->db->like($key, $search_items);
                }
                else{

                    $this->db->where($key, $search_items);
                }
              
            }

            $this->db->limit($table_data["length"], $table_data["start"]);
            $query_limit = $this->db->get($tablo_adi);
            $sql = $this->db->last_query();
            //$recordsFiltered = $query_limit->num_rows();
           // $totalData = $recordsFiltered;
            //$totalData = $query_limit->num_rows();
            $recordsFiltered =  $totalData;
           
            
            if ($query_limit->num_rows() > 0)
            {
                $result = $query_limit->result_array();
            }
           
                
      
        }
       
        
      

        $draw = intval($table_data["draw"]);
        $json_data = array(
            "draw" => $draw,
            "recordsTotal" => intval( $totalData),
            "recordsFiltered" => intval($recordsFiltered),
            "data"=>$result
            //"query"=>$sql
        );

        
        return $json_data;
    }


    public function get_modal_data($id){


        // $this->db->select('*');
        // $this->db->from('gostergelerv2');
        // $this->db->join('gostergelerv2_kademeler', 'gostergelerv2.g_id = gostergelerv2_kademeler.g_id', 'left outer');
        // $this->db->where("gostergelerv2.g_id",$id);
        // $this->db->where("gostergelerv2.durum",1);
        // $this->db->where("gostergelerv2_kademeler.durum",1);
        // $query = $this->db->get();

        $sql = 
        "
            SELECT  *,gostergelerv2.g_id  as g_id
                FROM gostergelerv2
                LEFT OUTER JOIN gostergelerv2_kademeler  ON gostergelerv2.g_id = gostergelerv2_kademeler.g_id  AND gostergelerv2_kademeler.durum = 1
                WHERE `gostergelerv2`.`g_id` = $id  AND gostergelerv2.durum = 1 
        ";
        $query = $this->db->query($sql);

        // $tablo_adi ="gostergeler";
        // $this->db->select($select);
        // $this->db->where("g_id",$id);
        // $query = $this->db->get($tablo_adi);
        
         //$sql = $this->db->last_query();
        //echo $sql;
        // echo "<br>";

        $result = [];
        
        if ($query->num_rows() > 0)
        {
           $result = $query->result_array();
        }
        
        return $result;

    }


    public function gostergeler_for_excel(){

        $this->db->select('*');
        $this->db->from('gostergelerv2');
        $this->db->join('gostergelerv2_kademeler', 'gostergelerv2.g_id = gostergelerv2_kademeler.g_id', 'inner');
        $this->db->where('gostergelerv2_kademeler.durum', 1);
        $this->db->where('gostergelerv2.durum', 1);
        $this->db->order_by('gostergelerv2.g_id', 1);

        $query = $this->db->get();
        return $query->result_array();

        
      
        //$this->db->order_by('pot_id,id', 'ASC');

    }


    public function get_g_list($ft_id,$search_val){
        //SELECT * FROM `gostergelerv2` WHERE ft_id  = 4 AND g_adi LIKE '%hedef%'
        
        $this->db->select('g_id');
        $this->db->from('gostergelerv2');
        $this->db->where('gostergelerv2.durum', 1);
        $this->db->like("g_adi",$search_val);
        $this->db->where('ft_id', $ft_id);

        $query = $this->db->get();

        // $sql = $this->db->last_query();
        // echo $sql;
        return $query->result_array();

    }
    
}