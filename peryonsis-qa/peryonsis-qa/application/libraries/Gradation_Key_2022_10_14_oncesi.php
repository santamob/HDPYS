<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Class Gradation_Key
{
    private $ci;
    function __construct() 
    {
        $this->ci =& get_instance();
        $this->ci->load->model("gradation_model");
    }
    
    // public function getGradationData($year,$period,$person_count,$pool_id){
    //     echo $person_count."<br>";
    //     $pool_levels = $this->ci->gradation_model->get_pool_levels($year,$pool_id,$period);
    //     $data = [];
    //     if($person_count == 2 || $person_count == 1){
    //         return [];
    //         exit();
    //     }
    //     else if($person_count > 10 ){
    //         foreach($pool_levels as $value){
    //            $level = $value->percantage == 50 ? $this->expectedForOverTenPerson($person_count,$pool_levels) : $this->calculatePercantage($person_count,$value->percantage);
    //            $data[$value->level][] = $level;
    //         }
    //     }
    //     else if( $person_count > 2 && $person_count <= 10 ){
    //         foreach($pool_levels as $value){
    //             //$level = $value->percantage == 50 ? $this->expectedForOverTenPerson($person_count,$pool_levels) : $this->calculatePercantageFloor($person_count,$value->percantage);
    //             //$data[$value->level][] = $level;
    //             $data[$value->level][] = $this->calculatePercantageFloor($person_count,$value->percantage);
    //         }
    //     }
       
    //     var_dump($data);
    // }


    public function getGradationData($year,$period,$person_count,$pool_id){
        //echo  " kisi sayısı =  ".$person_count." || yil =  ".$year." || donem =  ".$period." || havuz  id =  ".$pool_id."<br>";
        //if($person_count == 0){ return []; exit();}
        $data = [];
        $pool_levels = $this->pool_levels($year,$pool_id,$period);
        if($person_count > 10){
            $data["free"]  = false;
            foreach($pool_levels as $value){
               $val = $value->percantage == 50 ? $this->expectedPercantageForOverTenPerson($person_count,$pool_levels) : $this->calculatePercantage($person_count,$value->percantage);
               $data["data"][]= [
                    "id"=>$value->id,
                    "level"=>$value->level,
                    "percantage"=>$value->percantage,
                    "value"=>$val
                ];
            }
            return $data;
        }
        else{
            
            $pool_levels_with_join = $this->pool_levels_with_join($year,$pool_id,$period,$person_count,"");
            // if ($person_count == 2 || $person_count == 1){ // ???
            if (count($pool_levels_with_join) == 1){ // ???
                $data["free"]  = true; // kişi sayısı 2 veya 1 ise view'da serbest yazması için böyle bir key koyuldu.
            }
            else{
                $data["free"]  = false;
                //$pool_levels = $this->pool_levels_with_join($year,$pool_id,$period,$person_count,"");
                $pool_levels = $pool_levels_with_join;
            }

            foreach ($pool_levels  as $value){
                $data["data"][]= [
                    "id"=>$value->id,
                    "level"=>$value->level,
                    "percantage"=>$value->percantage,
                    "value"=>$value->value
                ];
            }
           
           
            return $data;
        }
    }

    public function pool_levels_with_join($year="",$pool_id="",$period="",$person_count="",$orderable=""){
         return $this->ci->gradation_model->get_pool_levels_with_join($year,"",$period,$person_count,$orderable="");
    }

    public function pool_levels($year,$pool_id,$period){
        return $this->ci->gradation_model->get_pool_levels($year,$pool_id,$period);
    }

    public function pool_levels_by_year($year){
        return $this->ci->gradation_model->get_pool_levels_by_year($year);
    }

    public function getPeriodsDates($year,$pool_id,$period){
        return ["data"=> $this->ci->gradation_model->getPeriodsDates()];
    }

    public function calculatePercantage($person_count,$percantage){
        return intval(round($person_count * ($percantage) / 100));
    }

    // public function pools($person_count,$percantage){
    //     return $this->ci->gradation_model->getPools();
    // }

    public function expectedPercantageForOverTenPerson($person_count,$percentages)
    {
        //$data['kisi_sayisi']-round($data['kisi_sayisi']*0.15)-round($data['kisi_sayisi']*0.25)-round($data['kisi_sayisi']*0.1);
        $calculation = "";
        foreach($percentages as $value){
            if($value->percantage != 50){
                $calculation += -round($person_count * ($value->percantage) / 100);
            }
        }
        return intval($person_count - (- $calculation));
    }


    public function yearAndPeriodisExist($year,$period){
        return $this->ci->gradation_model->yearAndPeriodisExist($year,$period);
    }

    public function createLevelsWithYearAndPeriod($data){
        return $this->ci->gradation_model->createLevelsWithYearAndPeriod($data);
    }

    public function populateLevelValues($columns_and_levels){
        $data = [];
        foreach($columns_and_levels["columns"] as $key => $column){
            $person_count = $column["person_count"];
            $col_count = count($column["value"]);
            foreach($column["value"] as $vkey => $value){
                $exploded = explode("-",$value["th_id"]);
                $merge_flag = array_sum($exploded);
                foreach($exploded as $th){
                    //$col_count 1 ise serbest değilse normal
                    $val = ($col_count == 1)  ? ["val"=>null,"merge_flag"=>99999999999] : ["val"=>$value["value"],"merge_flag"=>$merge_flag];
                    $data[$person_count][$columns_and_levels["levels"][$th]] = $val;
                }
            }
        }
        // echo "<pre>";
        //  // print_r($columns);
        // print_r($data);
        // echo "</pre>";
        // exit();
        return $this->ci->gradation_model->populateLevelValues($data);
    }



    public function getMaxFromArray($array){
        $max = max(array_keys($array));
        return $array[$max];
    }

    public function getMinFromArray($array){
        $min = min(array_keys($array));
        return $array[$min];
    }

   

    public function getSumNthElementsOfArray($array,$i){
        $elements = array_slice($array, -$i, count($array), true);
        // var_dump($elements);
        // exit();
        $sum = 0;
        foreach($elements as $el){
            $sum+= $el["value"];
        }
        return $sum;
    }


    public function getKademeNameById($id){
        $data = $this->ci->gradation_model->getKademeNameById($id);
        return $data[0]->level;
    }


    public function kademelendirme_havuzlari($yil,$donem){
        return  $this->ci->gradation_model->kademelendirme_havuzlari($yil,$donem);
    }


}

?>
