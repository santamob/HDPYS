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


    public function getGradationDatForMerged($year,$period,$person_count,$pool_id){
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

                $is_here = array_search($value->merge_flag, array_column($data["data"], 'merge_flag'));
                $data["data"][] = [
                    //"id"=>$value->id,
                    "id"=> (!is_null($is_here) && $is_here !== false) ? $data["data"][$is_here]["id"] : $value->id, 
                    "level"=>$value->level,
                    "percantage"=>$value->percantage,
                    "value"=> (!is_null($is_here) && $is_here !== false) ? $data["data"][$is_here]["value"] : $value->value, 
                    "merge_flag"=>$value->merge_flag
                ];
            }
        return $data;
    }


    public function getGradationData($year,$period,$person_count,$pool_id){
        //echo  " kisi sayısı =  ".$person_count." || yil =  ".$year." || donem =  ".$period." || havuz  id =  ".$pool_id."<br>";
        //if($person_count == 0){ return []; exit();}
        $data = [];
        $data["is_over_ten"]= ($person_count >= 10) ? true:false;
        $pool_levels = $this->pool_levels($year,$pool_id,$period);
        if($person_count > 10){
            $data["free"]  = false;
            foreach($pool_levels as $value){
               $val = $value->percantage == 50 ? $this->expectedPercantageForOverTenPerson($person_count,$pool_levels) : $this->calculatePercantage($person_count,$value->percantage);
               $data["data"][]= [
                    "id"=>$value->id,
                    "level"=>$value->level,
                    "color"=>$value->color,
                    "percantage"=>$value->percantage,
                    "value"=>$val
                ];
            }


            
            return $data;
        }
        else{
            //echo "<pre>";
            
            $pool_levels_with_join = $this->pool_levels_with_join(false,$year,$pool_id,$period,$person_count,"");
            //$pool_levels_with_join_for_merged = $this->pool_levels_with_join(true,$year,$pool_id,$period,$person_count,"");
            // if ($person_count == 2 || $person_count == 1){ // ???

                //print_r($pool_levels_with_join);
            
            /*if (count($pool_levels_with_join) == 1){ // ???
                $data["free"]  = true; // kişi sayısı 2 veya 1 ise view'da serbest yazması için böyle bir key koyuldu.
            }*/
            if($person_count <= 2 && $person_count != 0)
            {
                $data["free"] = true;
                $pool_levels = $pool_levels_with_join;
            }
            else{
                $data["free"]  = false;
                //$pool_levels = $this->pool_levels_with_join($year,$pool_id,$period,$person_count,"");
                $pool_levels = $pool_levels_with_join;
            }

        //    var_dump($pool_levels);
        //    exit();
            foreach ($pool_levels  as $k => $value){
                $is_here = null;
                $prev = 0;
                $current = 0;
                $next = 0;
               
                
                //$data["data"][] = $new_data;

                
                if($k != 0){ // ilk indexe bakmasın diye
                    $is_here = array_search($value->merge_flag, array_column($data["data_merged"], 'merge_flag'));
                    //$is_here = array_search($value->merge_flag, array_column(json_decode(json_encode($pool_levels),TRUE), 'merge_flag'));
                }

               // var_dump($is_here);
               
               
               // if(!is_null($is_here) && $is_here !== false && $pool_levels[$is_here]->id !== $value->id ){
                if(!is_null($is_here) && $is_here !== false){

                    //echo "I am here<br />";
                   
                    //$data["data_merged"][$is_here]["id"] = $value->id;
                    $data["data_merged"][$is_here]["level"].= " / ".$value->level ;
                    $data["data_merged"][$is_here]["percantage"] = $this->addTwoPercentages($data["data"][$is_here]["percantage"],$value->percantage) ;
                    $data["data_merged"][$is_here]["merged"] = true;
                    $data["data_merged"][$is_here]["merged_values_count"]++;
                    $data["data_merged"][$is_here]["value"] = $value->value;
                }
                else{
                    //echo "I am not here<br />";
                   
                    $new_data_merged = [
                        "id"=>$value->id,
                        "level"=>$value->level,
                        "percantage"=>$value->percantage,
                        "value"=>$value->value,
                        "color"=>$value->color,
                        "merge_flag"=>$value->merge_flag,
                        "merged"=>false,
                        "merged_values_count"=>1
                    ];
                    $data["data_merged"][] = $new_data_merged;
                }



                $prev = $k-1;
                $current = $k;
                $next = $k+1;

                // burası sorulacak
                if(($pool_levels[$current]->merge_flag == $pool_levels[$prev]->merge_flag) || ($pool_levels[$current]->merge_flag == $pool_levels[$next]->merge_flag))
                {
                    $data["data"][] = [
                        "id"=>$value->id,
                        "level"=>$value->level,
                        "percantage"=>$value->percantage,
                        "value"=>$value->value,
                        "color"=>$value->color,
                        "merge_flag"=>$value->merge_flag,
                        "merged"=>true,
                        "merged_with"=>$this->getIdsWithSameFlag(json_decode(json_encode($pool_levels),TRUE),$value->merge_flag,$value->id),
                        "for_select_merged_with"=>$this->getIdsWithSameFlag2(json_decode(json_encode($pool_levels),TRUE),$value->merge_flag,$value->id)
                    ];
                }
                else{
                    $data["data"][] = [
                        "id"=>$value->id,
                        "level"=>$value->level,
                        "percantage"=>$value->percantage,
                        "value"=>$value->value,
                        "color"=>$value->color,
                        "merge_flag"=>$value->merge_flag,
                        "merged"=>false,
                        //"merged_with"=>$pool_levels[$prev]->id
                    ];

                }


                // echo "DATA<BR />";
                // print_r($data);
                // echo "<hr />";
                
            }
            
            //exit();
            
           
            return $data;
        }
    }

    public function getIdsWithSameFlag($pools,$flag,$id){
        $ids = [];
        foreach($pools as $pool){
            //if($pool["merge_flag"] == $flag && $pool["id"] != $id ){
            if($pool["merge_flag"] == $flag){
                $ids[] = ["level"=>$pool["level"],"id"=>$pool["id"]];
            }
        }

        return $ids;
    }

    public function getIdsWithSameFlag2($pools,$flag,$id){
        
        $ids = $this->getIdsWithSameFlag($pools,$flag,$id);

        $max = max(array_column($ids, 'id'));
        $key = array_search($max+1, array_column($pools, 'id'));

      
        if(!is_null($pools[$key]) &&  $key!= false){
           $ids[] = ["level"=>$pools[$key]["level"],"id"=>$pools[$key]["id"]];
        }
        else{ // yeni eklendi 2022-10-27

            $count = count($ids);
            $max = max(array_column($ids, 'id'));
            $key = array_search($max-$count, array_column($pools, 'id'));
            
            //$ids[] = ["level"=>$pools[$key]["level"],"id"=>$pools[$key]["id"]];
            $item = ["level"=>$pools[$key]["level"],"id"=>$pools[$key]["id"]];
            
            array_unshift($ids,$item);

        }

        

       
  
        return $ids;
    }

    public function addTwoPercentages($num1,$num2){
        $result = $num1 + $num2;
        return number_format((float)$result, 2, '.', '');
    }


    // public function getGradationData($year,$period,$person_count,$pool_id){
    //     //echo  " kisi sayısı =  ".$person_count." || yil =  ".$year." || donem =  ".$period." || havuz  id =  ".$pool_id."<br>";
    //     //if($person_count == 0){ return []; exit();}
    //     $data = [];
    //     $pool_levels = $this->pool_levels($year,$pool_id,$period);
    //     if($person_count > 10){
    //         $data["free"]  = false;
    //         foreach($pool_levels as $value){
    //            $val = $value->percantage == 50 ? $this->expectedPercantageForOverTenPerson($person_count,$pool_levels) : $this->calculatePercantage($person_count,$value->percantage);
    //            $data["data"][]= [
    //                 "id"=>$value->id,
    //                 "level"=>$value->level,
    //                 "percantage"=>$value->percantage,
    //                 "value"=>$val
    //             ];
    //         }
    //         return $data;
    //     }
    //     else{
            
    //         $pool_levels_with_join = $this->pool_levels_with_join($year,$pool_id,$period,$person_count,"");
    //         // if ($person_count == 2 || $person_count == 1){ // ???
    //         if (count($pool_levels_with_join) == 1){ // ???
    //             $data["free"]  = true; // kişi sayısı 2 veya 1 ise view'da serbest yazması için böyle bir key koyuldu.
    //         }
    //         else{
    //             $data["free"]  = false;
    //             //$pool_levels = $this->pool_levels_with_join($year,$pool_id,$period,$person_count,"");
    //             $pool_levels = $pool_levels_with_join;
    //         }

    //         foreach ($pool_levels  as $value){
    //             $data["data"][]= [
    //                 "id"=>$value->id,
    //                 "level"=>$value->level,
    //                 "percantage"=>$value->percantage,
    //                 "value"=>$value->value
    //             ];
    //         }
           
           
    //         return $data;
    //     }
    // }

    public function pool_levels_with_join($for_merge="",$year="",$pool_id="",$period="",$person_count="",$orderable=""){
         return $this->ci->gradation_model->get_pool_levels_with_join($for_merge,$year,$pool_id,$period,$person_count,$orderable="");
    }

    // public function pool_levels_with_join($year="",$pool_id="",$period="",$person_count="",$orderable=""){
    //     return $this->ci->gradation_model->get_pool_levels_with_join($year,"",$period,$person_count,$orderable="");
    // }

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

    public function anahtarNameisExist($year,$period,$name){
        return $this->ci->gradation_model->anahtarNameisExist($name);
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
