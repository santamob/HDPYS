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
        $kosul['pernr'] = $id;
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
        $this->db->trans_start(); // Query will be rolled back
        
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
        }
        
//        echo $this->db->last_query();
//        exit();
        
        $sql = $this->db->last_query();
        
        foreach ($data as $key=>$arr)
        {
            $this->db->insert($tablo_adi, $arr);
            $sql = $this->db->last_query();
            
            $sql = $this->db->last_query();
//            echo $sql;
//            echo "<br/>";
        }
        
//        exit();
        
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
        
        if ($query->num_rows() == 1)
        {
            return $query->row_array();
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
        
        $query = $this->db->get($tablo_adi);
        
        $sql = $this->db->last_query();
        
        if ($query->num_rows() > 0)
        {
            return $query->result_array();
        }
        else
        {
            return false;
        }
    }
    
}