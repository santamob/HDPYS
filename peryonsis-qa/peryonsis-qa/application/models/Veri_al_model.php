<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Veri_al_model extends CI_Model {
    
    public function kopyala($kisi_listesi, $yil, $donem, $degerlendiren)
    {
        $DB2 = $this->load->database('santanet', TRUE);
        $DB2->select("*");
        $DB2->from('degerlendirmeler');
        $DB2->where('yil', $yil);
        $DB2->where('donem', $donem);
        $DB2->where_in('pernr', $kisi_listesi);
        $DB2->group_start();
            $DB2->where('ft_id', 1);
            $DB2->or_where('ft_id', 2);
        $DB2->group_end();
        $query = $DB2->get();
        $sql = $DB2->last_query();
        $arr = $query->result_array();
        $DB2->close();
        
        $this->db->trans_start();
        
        foreach ($arr as $key=>$arr1)
        {
            
            $this->db->where("yil", $yil);
            $this->db->where("donem", $donem);
            $this->db->where("pernr", $arr1['pernr']);
            $this->db->where("degerlendiren_ack", $arr1['degerlendiren_ack']);
            $this->db->where("ft_id", $arr1['ft_id']);
            $this->db->where("form_id", $arr1['form_id']);
            $this->db->where("g_id", $arr1['g_id']);
            $this->db->where("periyod", $arr1['periyod']);
            $this->db->where("urun", $arr1['urun']);
            $this->db->limit(1);
            $query2 = $this->db->get("degerlendirmeler");
            $sql = $this->db->last_query();
                
            if ($query2->num_rows() == 1)
            {
                $row = $query2->row_array();
//                if ($row['g_time'] == $arr1['g_time'])
//                {
//                    continue;
//                }
//                else 
//                {
                    // Update
                    unset($arr1['id']);
                    $this->db->where("yil", $yil);
                    $this->db->where("donem", $donem);
                    $this->db->where("pernr", $arr1['pernr']);
                    $this->db->where("degerlendiren_ack", $arr1['degerlendiren_ack']);
                    $this->db->where("ft_id", $arr1['ft_id']);
                    $this->db->where("form_id", $arr1['form_id']);
                    $this->db->where("g_id", $arr1['g_id']);
                    $this->db->where("periyod", $arr1['periyod']);
                    $this->db->where("urun", $arr1['urun']);
                    $this->db->update("degerlendirmeler", $arr1);
                    $sql = $this->db->last_query();
//                }
            }
            else
            {
                // İnsert
                unset($arr1['id']);
                $this->db->insert("degerlendirmeler", $arr1);
                $sql = $this->db->last_query();
            }
            
        }
        
        $this->db->where("yil", $yil);
        $this->db->where("donem", $donem);
        $this->db->where("degerlendiren", $degerlendiren);
        $this->db->group_start();
            $this->db->where("ft_id", 1);
            $this->db->or_where("ft_id", 2);
            $this->db->or_where("ft_id", 99);
        $this->db->group_end();
        $query3 = $this->db->get("onaylar");
        
        if ($query3->num_rows() > 0)
        {
            $row2 = $query3->result_array();

            $DB2 = $this->load->database('santanet', TRUE);
            foreach ($row2 as $key2=>$arr2)
            {
                $DB2->select("*");
                $DB2->from('onaylar');
                $DB2->where("yil", $yil);
                $DB2->where("donem", $donem);
                $DB2->where("degerlendiren", $degerlendiren);
                $DB2->where("ft_id", $arr2['ft_id']);
                $query4 = $DB2->get("");

                if ($query4->num_rows() > 0)
                {
                    $row3 = $query4->row_array();
                    
                    if ($arr2['g_time'] == $row3['g_time'])
                    {
                        continue;
                    }
                    else
                    {
                        unset($arr2['id']);
                        $DB2->where("yil", $yil);
                        $DB2->where("donem", $donem);
                        $DB2->where("degerlendiren", $degerlendiren);
                        $DB2->where("ft_id", $arr2['ft_id']);
                        $DB2->update("onaylar", $arr2);
                    }
                }
                else
                {
                    unset($arr2['id']);
                    $DB2->insert("onaylar", $arr2);
                }
            }
            $DB2->close();

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
    
    public function kopyala2($yil, $donem, $degerlendiren)
    {
        $this->db->trans_start();
        
        $this->db->where("yil", $yil);
        $this->db->where("donem", $donem);
        $this->db->where("degerlendiren", $degerlendiren);
        $this->db->group_start();
            $this->db->where("ft_id", 1);
            $this->db->or_where("ft_id", 2);
            $this->db->or_where("ft_id", 99);
        $this->db->group_end();
        $query3 = $this->db->get("onaylar");
        
        if ($query3->num_rows() > 0)
        {
            $row2 = $query3->result_array();

            $DB2 = $this->load->database('santanet', TRUE);

            foreach ($row2 as $key2=>$arr2)
            {
                $DB2->select("*");
                $DB2->from('onaylar');
                $DB2->where("yil", $yil);
                $DB2->where("donem", $donem);
                $DB2->where("degerlendiren", $degerlendiren);
                $DB2->where("ft_id", $arr2['ft_id']);
                $query4 = $DB2->get("");

                if ($query4->num_rows() > 0)
                {
                    $row3 = $query4->row_array();
                    
                    if ($arr2['g_time'] == $row3['g_time'])
                    {
                        continue;
                    }
                    else
                    {
                        unset($arr2['id']);
                        $DB2->where("yil", $yil);
                        $DB2->where("donem", $donem);
                        $DB2->where("degerlendiren", $degerlendiren);
                        $DB2->where("ft_id", $arr2['ft_id']);
                        $DB2->update("onaylar", $arr2);
                    }
                }
                else
                {
                    unset($arr2['id']);
                    $DB2->insert("onaylar", $arr2);
                }
            }
            $DB2->close();

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
    
}