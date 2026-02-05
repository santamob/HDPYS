<?php


class Checkpot
{
    public $ci 

    public $point;

    public $level_text;


    public function __construct()
    {
        $this->ci =& get_instance();
    }

    public function GetLevels($yil,$donem)
    {

        $sql = "SELECT d.*,k.*,p.* FROM `potaniyel_degerlendirme` p 
                JOIN donem_listesi d ON p.donem_id = d.id 
                JOIN kriter k ON p.criteria_id = k.id
                WHERE d.yil = $yil AND d.donem = $donem AND d.donem_status = 1 AND p.status=1  ORDER BY p.id DESC";

        $query = $this->ci->db->query($sql);

        return $this->ci->db-num_rows() > 0 ? $query->result_array() : null;
        
    }

    public function GetSecondHighestLevel($GetLevelsData)
    {
        return $GetLevelsData[1];
    }

    public function GetLevelsByPuan($yil,$donem,$puan)
    {
        
        $sql = "SELECT * FROM `potaniyel_degerlendirme` p 
        JOIN donem_listesi d ON p.donem_id = d.id 
        WHERE d.yil = $yil AND d.donem = $donem AND d.donem_status = 1 AND p.status=1 and p.min <= $puan and p.max >= $puan ";
                
        $query = $this->ci->db->query($sql);

        return $this->ci->db-num_rows() > 0 ? $query->result_array() : null;
        
    }

    public function GetDataFromPotentialForm($yil,$donem,$pernr)
    {

        $sql = "select * from degerlendirmeler where ft_id = 3 and yil = $yil and donem = $donem and pernr=$pernr";
        $query = $this->ci->db->query($sql);

        return $this->ci->db-num_rows() > 0 ? $query->result_array() : null;
    }

    

    public function HowManyLevelWeDetect($data)
    {
        $counters_array = null;
        $points = 0;
        foreach($data as $item)
        {
            $counters_array[$item["duzey"]] = $item["duzey"];
            $points += $item["puan"];
        }

        $this->point = $puan;

        return [
            "counters_keys" => $counters_array,
            "point" => $points
        ];

    }

    public function DecreasedLevel($LevelAndPointData)
    {
        $boolean = false;
        foreach($LevelAndPointData["counters_keys"] as $level)
        {
            if($level == 3 || $level == 2 || $level == 1)
            {
                $boolean = true;
                break
            }
        }

        return $boolean;
    }

    public function Run($yil,$donem,$pernr)
    {   
        $data_degerlendirme = $this->GetDataFromPotentialForm($yil,$donem,$pernr);

        $data_get_levels = $this->GetLevels($yil,$donem);

        $will_be_decrease = $this->DecreasedLevel($this->HowManyLevelWeDetect($data_degerlendirme));

        if($will_be_decrease)
        {
            $second_higest_level = $this->GetSecondHighestLevel($data_get_levels);

            $this->level_text = $second_higest_level["name"];
        }


        return $this;
    }


}