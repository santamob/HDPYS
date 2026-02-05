<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Gradation_model extends CI_Model {
    
    public function get_pool_levels($year,$pool_id,$period)
    {
        //$this->db->where("havuz_id", $pool_id);
        $this->db->where("year", $year);
        $this->db->where("period", $period);
        $this->db->where("status", 1);
        $this->db->order_by("id");
        $query = $this->db->get("havuz_performans_duzeyleri");
        return $query->result();
    }


    public function get_pool_levels_with_join($year="",$pool_id="",$period="",$person_count = "",$orderable="")
    {

        
        // SELECT havuz_id,count_person,LEVEL,VALUE,percantage,YEAR,PERIOD
        // FROM `havuz_performans_duzeyleri`
        // INNER JOIN `test_kademe_table` ON havuz_performans_duzeyleri.id = test_kademe_table.hpd_id
        // WHERE STATUS = 1 AND YEAR = 2021 AND PERIOD = 3 and havuz_id = 61
        // ORDER BY test_kademe_table.id
        $this->db->select('havuz_performans_duzeyleri.id AS id,count_person,level,value,percantage,year,period,merge_flag');    
        $this->db->from('havuz_performans_duzeyleri');
        $this->db->join('test_kademe_table', 'havuz_performans_duzeyleri.id = test_kademe_table.hpd_id','JOIN Type');
        if(!empty($year)){
            $this->db->where("year", $year);
        }
        if(!empty($period)){
            $this->db->where("period", $period);
        }
        if(!empty($person_count)){
            $this->db->where("count_person", $person_count);
        }
        //$this->db->where("havuz_id", $pool_id);
        $this->db->where("status", 1);
        
        if(!empty($orderable)){
             $this->db->order_by("count_person", 1);
        }
      
        $this->db->order_by("havuz_performans_duzeyleri.id");
        $this->db->group_by("merge_flag");
      
        $query = $this->db->get();

        //echo $this->db->last_query();
        return $query->result();
    }


    public function getPools(){
        $this->db->select('havuz_id,havuz_text');    
        $query = $this->db->get("kademelendirme_havuzlari");
        return $query->result();
    }

    public function getKademeNameById($id){
        $this->db->select('*');    
        $this->db->where("id", $id);
        $query = $this->db->get("havuz_performans_duzeyleri");
        return $query->result();
    }

    public function get_pool_levels_by_year($year)
    {
        $this->db->where("year", $year);
        $this->db->where("status", 1);
        $this->db->order_by("id");
        $query = $this->db->get("havuz_performans_duzeyleri");
        return $query->result();
    }


    public function getPeriodsDates(){
        $this->db->select('id,donem,yil');    
        $query = $this->db->get("donem_listesi");
        return $query->result();
    }


    public function yearAndPeriodisExist($year,$period)
    {
        $this->db->where("year", $year);
        $this->db->where("period", $period); 
        $query = $this->db->get("havuz_performans_duzeyleri");
        //return $query->result();
        return  ($query->num_rows() > 0 ) ? TRUE : FALSE;
    }


    public function createLevelsWithYearAndPeriod($data)
    {
            $inserted_ids=[];
            foreach ($data["levels"] as $key=>$arr)
            {
                $table = [
                    "level" =>$arr["level"],
                    "percantage" =>$arr["percantage"],
                    "period"=>$data["donem"],
                    "year"=>$data["yil"]
                ];
                $insert =$this->db->insert("havuz_performans_duzeyleri", $table);
                $inserted_ids[] = $this->db->insert_id();
            }
            return  (!$insert) ? [] : $inserted_ids;
    }


    public function populateLevelValues($data)
    {
        $errors=[];
        foreach($data as $count_person=>$column){
            foreach($column as $hpd_id=>$val){
                $data=[
                    "hpd_id"=>$hpd_id,
                    "value"=>$val["val"],
                    "merge_flag"=>$val["merge_flag"],
                    "count_person"=>$count_person
                ];
                $insert = $this->db->insert("test_kademe_table", $data);
                if(!($insert >0 )){ $errors[$hpd_id] = $hpd_id;}
            } 
        }
        if(!empty($errors)){$this->deleteLevels($errors);}
        return (!empty($errors)) ? FALSE : TRUE;
    }


    public function deleteLevels($errors){
        foreach($errors as $err){
            $this -> db -> where('id', $err);
            $this -> db -> delete('havuz_performans_duzeyleri');
            $this -> db -> delete('test_kademe_table');
        }
    }


    public function kademelendirme_havuzlari($yil,$donem){

        // SELECT * 
        // FROM `kademelendirme_havuzlari` 
        // INNER JOIN `kademelendirme_sonuclari` ON kademelendirme_havuzlari.havuz_id = kademelendirme_sonuclari.havuz_id
        // WHERE yil= 2021 AND donem = 3 
        // GROUP BY kademelendirme_havuzlari.havuz_id


        // $this->db->select('*');    
        // $query = $this->db->get("kademelendirme_sonucları");

        
        $this->db->select('*'); 
        $this->db->where('yil', $yil);  
        $this->db->where('donem', $donem);
        $this->db->group_by("kademelendirme_havuzlari.havuz_id");
        $this->db->from('kademelendirme_havuzlari');
        $this->db->join('kademelendirme_sonuclari', 'kademelendirme_havuzlari.havuz_id = kademelendirme_sonuclari.havuz_id','inner');
        $query = $this->db->get();
        //echo $this->db->last_query();
        return $query->result();
    }

    

    






    
}