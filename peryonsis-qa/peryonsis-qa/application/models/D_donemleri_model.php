<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class D_donemleri_model extends CI_Model {
    
    public function formlar_detay($form_id)  // eski
    {
        $this->db->select('formlar_detay.*, gostergeler.*');
        $this->db->from('formlar_detay');
        $this->db->join('gostergeler', 'gostergeler.g_id = formlar_detay.g_id', 'inner');
        $this->db->where('formlar_detay.form_id', $form_id);
        $this->db->order_by('gostergeler.kategori', 'asc');
        $this->db->order_by('formlar_detay.agirlik', 'desc');
        $this->db->order_by('formlar_detay.id', 'asc');
        $query = $this->db->get();

        // echo $this->db->last_query();
        // exit();
        
        return $query->result_array();
    }


    public function formlar_detayv2($form_id){

        // SELECT 
        //     * 
        // FROM 
        //     gostergelerv2
        // INNER JOIN `gostergelerv2_kademeler` ON `gostergelerv2`.`g_id` = `gostergelerv2_kademeler`.`g_id` 
        // INNER JOIN `formlar_detay` ON `gostergelerv2`.`g_id` = `formlar_detay`.`g_id`
        // WHERE  
        //     formlar_detay.form_id = 1001
        // ORDER BY  
        //     gostergelerv2.kategori ASC, 
        //     formlar_detay.agirlik DESC,
        //     formlar_detay.id ASC


        // SELECT 
        //     * 
        // FROM `gostergelerv2` 
        // LEFT JOIN `gostergelerv2_kademeler` ON `gostergelerv2`.`g_id` = `gostergelerv2_kademeler`.`g_id` 
        // LEFT JOIN `formlar_detay` ON `gostergelerv2`.`g_id` = `formlar_detay`.`g_id` 
        // WHERE `formlar_detay`.`form_id` = '4529' 
        // ORDER BY 
        // `gostergelerv2`.`kategori` ASC, 
        // `formlar_detay`.`agirlik` DESC, 
        // `formlar_detay`.`id` ASC, 
        // `gostergelerv2_kademeler`.`id` ASC

        // $sql = "
        // SELECT 
            
        //     gostergelerv2.g_id,
        //     formlar_detay.ft_id,
        //     ft_text,
        //     g_adi,
        //     periyod,
        //     g_text,g_desc,
        //     kategori,
        //     planlanan_text,
        //     gerceklesen_text,
        //     sonuc_text,
        //     var_kaynagi,
        //     veri_kaynagi_text,
        //     veri_hesaplama,
        //     veri_hesaplama_text,
        //     degerledirme_turu,
        //     degerledirme_turu_text,
        //     kmin_text,
        //     kmax_text,
        //     guncelleyen,
        //     g_time,
        //     gostergelerv2_kademeler.id  AS gk_id
        //     kademe_text,
        //     kademe_as,
        //     kademe_us,
        //     kmin_desc,
        //     kmax_desc,
        //     formlar_detay.id AS fd_id,agirlik



        //     FROM `gostergelerv2` 
        //     LEFT OUTER JOIN `gostergelerv2_kademeler` ON `gostergelerv2`.`g_id` = `gostergelerv2_kademeler`.`g_id` AND gostergelerv2_kademeler.durum = 1
        //     LEFT OUTER JOIN `formlar_detay` ON `gostergelerv2`.`g_id` = `formlar_detay`.`g_id`  
        //     WHERE `formlar_detay`.`form_id` = '4529'
        //     ORDER BY  `formlar_detay`.`agirlik` DESC, `formlar_detay`.`id` ASC
        // ";

        //$query = $this->db->query($sql);


        $this->db->select(
            'gostergelerv2.g_id,
            ft_id,
            kademe_text,
            formlar_detay.id  as fd_id,
            ft_text,
            g_adi,
            periyod,
            g_text,g_desc,
            kategori,
            planlanan_text,
            gerceklesen_text,
            sonuc_text,
            veri_kaynagi,
            veri_kaynagi_text,
            veri_hesaplama,
            veri_hesaplama_text,
            degerlendirme_turu,
            degerlendirme_turu_text,
            kmin_text,
            kmax_text,
            gostergelerv2.guncelleyen,
            gostergelerv2.g_time,
            gostergelerv2_kademeler.id as  gk_id,
            kademe_as,
            kademe_us,
            kmin_desc,
            kmax_desc,
            agirlik'
        );

     

        $this->db->from('gostergelerv2');
        //$this->db->join('gostergelerv2_kademeler', 'gostergelerv2.g_id = gostergelerv2_kademeler.g_id', 'left');
        $this->db->join('gostergelerv2_kademeler', 'gostergelerv2.g_id = gostergelerv2_kademeler.g_id and gostergelerv2_kademeler.durum = 1 ', 'left');
        $this->db->join('formlar_detay', 'gostergelerv2.g_id = formlar_detay.g_id', 'left');
        $this->db->where('formlar_detay.form_id', $form_id);
        //$this->db->where('gostergelerv2_kademeler.durum',1); // yeni güncellendi
        $this->db->order_by('gostergelerv2.kategori', 'asc');
        $this->db->order_by('formlar_detay.agirlik', 'desc');
        $this->db->order_by('formlar_detay.id', 'asc');
        $this->db->order_by('gostergelerv2_kademeler.id', 'asc');

       

        $query = $this->db->get();

        //echo $this->db->last_query();
        //exit();
        
        return $query->result_array();
    }




    
    public function potansiyel_degerlendirme($puan,$donem_id,$is_high_potential){


        // puan 83.33
        //donem 27
        // high pot false
        
        $this->db->select('*,kriter.id AS kriter_id,potaniyel_degerlendirme.id AS pot_id');
        $this->db->from('potaniyel_degerlendirme');
        $this->db->join('kriter', 'potaniyel_degerlendirme.criteria_id = kriter.id', 'inner');
        $this->db->where('min <=', $puan);
        $this->db->where('max >', $puan);
        $this->db->where('status', 1);

        $this->db->where('donem_id', $donem_id);
        $query = $this->db->get();
        //echo $this->db->last_query();
        //exit();
        $data = $query->result_array();
        

       
        //SELECT MAX(potaniyel_degerlendirme.id) FROM potaniyel_degerlendirme 
        //WHERE  `status` = 1 AND `donem_id` = 27

        $this->db->select('MAX(potaniyel_degerlendirme.id) as max_pot_id');
        $this->db->from('potaniyel_degerlendirme');
        $this->db->where('status', 1);
        $this->db->where('donem_id', $donem_id);
        $query = $this->db->get();
        $donem_kriter_max_id = $query->result_array();

        
        // MAX İD KONTROLU YAP

        
        
        if($data[0]["pot_id"] == $donem_kriter_max_id[0]["max_pot_id"]){
            
            if($is_high_potential == false){

                // SELECT *,MAX( potaniyel_degerlendirme.id ) FROM potaniyel_degerlendirme
                // INNER JOIN `kriter` ON `potaniyel_degerlendirme`.`criteria_id` = `kriter`.`id` 
                // WHERE  donem_id=27 AND potaniyel_degerlendirme.id < ( SELECT MAX( potaniyel_degerlendirme.id ) FROM potaniyel_degerlendirme);


                // $query = "SELECT *,kriter.id AS kriter_id,potaniyel_degerlendirme.id AS pot_id,  MAX( potaniyel_degerlendirme.id ) 
                // FROM potaniyel_degerlendirme
                //  INNER JOIN kriter ON potaniyel_degerlendirme.criteria_id = kriter.id 
                //  WHERE  status = 1 and  donem_id=$donem_id AND potaniyel_degerlendirme.id < ( SELECT MAX( potaniyel_degerlendirme.id ) FROM potaniyel_degerlendirme)";
                
                // $this->db->select('*,kriter.id AS kriter_id,potaniyel_degerlendirme.id AS pot_id,  MAX( potaniyel_degerlendirme.id )');
                // $this->db->from('potaniyel_degerlendirme');
                // $this->db->join('kriter', 'potaniyel_degerlendirme.criteria_id = kriter.id', 'inner');
                // $this->db->where('potaniyel_degerlendirme.id < ( SELECT MAX( potaniyel_degerlendirme.id ) FROM potaniyel_degerlendirme)');
                // $this->db->where('status', 1);
                // $this->db->where('donem_id', $donem_id);
               
                
                $this->db->select('*,MAX(potaniyel_degerlendirme.id) as pot_id');
                $this->db->from('potaniyel_degerlendirme');
                $this->db->join('kriter', 'potaniyel_degerlendirme.criteria_id = kriter.id', 'inner');
                $this->db->where('potaniyel_degerlendirme.id < ( SELECT MAX( potaniyel_degerlendirme.id ) FROM potaniyel_degerlendirme)');
                $this->db->where('status', 1);
                $this->db->where('donem_id', $donem_id);
             
                $query2 = $this->db->get();
                $data2 = $query2->result_array();

               // echo "<pre>";
                //print_r($data2);
                //exit();

              
                $this->db->select('*,potaniyel_degerlendirme.id as pot_id');
                $this->db->from('potaniyel_degerlendirme');
                $this->db->join('kriter', 'potaniyel_degerlendirme.criteria_id = kriter.id', 'inner');
                $this->db->where('potaniyel_degerlendirme.id', $data2[0]["pot_id"]);
                $this->db->where('status', 1);
                $this->db->where('donem_id', $donem_id);
                $query3 = $this->db->get();
                $data = $query3->result_array();


                //echo "<pre>";
                //print_r($data);
                //exit();

                

                
            
            }

        }

       

        return $data;


    }

    public function getIdDonem($donem,$yil){

        $this->db->select('id');
        $this->db->from('donem_listesi');
        $this->db->where('yil', $yil);
        $this->db->where('donem', $donem);
        $query = $this->db->get();
        
        return $query->result_array();

    }

    public function getKriterNames($donem_id){

     
    //     SELECT *
	// FROM potaniyel_degerlendirme 
	// JOIN kriter ON potaniyel_degerlendirme.criteria_id = kriter.id
	// JOIN tus_kuralları ON potaniyel_degerlendirme.id = tus_kuralları.potansiyel_degerlendirme_id
    // WHERE  `status` = 1 AND `donem_id` = 27 AND tus_kuralları.button_status = 1

        //$this->db->select('potaniyel_degerlendirme.id AS pot_id,kriter.id AS kriter_id,min,max,status,donem_id,name');
        $this->db->select('potaniyel_degerlendirme.id AS pot_id,kriter.id AS kriter_id,MIN,MAX,STATUS,donem_id,NAME,tus_kuralları.*,tus_kuralları.id as tus_id');
        //$this->db->select('*');
        $this->db->from('potaniyel_degerlendirme');
        $this->db->join('kriter', 'potaniyel_degerlendirme.criteria_id = kriter.id', 'inner');
        $this->db->join('tus_kuralları', 'potaniyel_degerlendirme.id = tus_kuralları.potansiyel_degerlendirme_id', 'inner');
        $this->db->where('status', 1);
        $this->db->where('tus_kuralları.button_status', 1);
        $this->db->where('donem_id', $donem_id);
        $this->db->order_by('pot_id,id', 'ASC');
        
        $query = $this->db->get();

       

        $data = $query->result_array();

        
        return $data;

        
   
    }

    public function addCriteria($data){

       
        $ids = 0;
        foreach($data as $val){

            $kriter= [
                "name"=>$val["name"]
            ];

            $this->db->insert('kriter', $kriter);
            $criteria_id = $this->db->insert_id();

            if($criteria_id > 0 ){

                $potaniyel_degerlendirme=[
                    "criteria_id"=>$criteria_id,
                    "min"=>$val["min"],
                    "max"=>$val["max"],
                    "donem_id"=>$val["donem_id"],
    
                ];
    
                $this->db->insert('potaniyel_degerlendirme', $potaniyel_degerlendirme);
                $id = $this->db->insert_id();

                if($id > 0 ){

                    foreach($val["buttons"] as $button){

                        $tus_kuralları=[
                            "button_name"=>$button["button_name"],
                            "button_state"=>($button["check_val"] == 'true') ? 1:0,
                            "potansiyel_degerlendirme_id"=>$id
                        ];

                        $this->db->insert('tus_kuralları', $tus_kuralları);
                        $ids = $this->db->insert_id();
     
                    }

                }

                

            }

        }
        

        if($ids > 0){
            return true;
        }

        return false;

    }


    public function getDonemList(){

        $this->db->select('id,yil,donem,donem_ack');
        $this->db->from('donem_listesi');
        
        $query = $this->db->get();
        return  $query->result_array();

    }


    public function updateCriteria($data){
        
        $success = 0;
        foreach($data as $val){
            
            if($val["state"] == '0'){
                
                $this->db->set('name', $val["name"]);
                $this->db->where('id', $val["kriter_id"]);
                $this->db->update('kriter');

                $this->db->set('min', $val["min"]);
                $this->db->set('max', $val["max"]);
                $this->db->where('id', $val["pot_id"]);
                $this->db->where('donem_id', $val["donem_id"]);
                $this->db->update('potaniyel_degerlendirme');


                foreach($val["buttons"] as $button){

                    $check_val = ($button["check_val"] == 'true') ? true : false;
                   
                    if($button["button_state"] == '0'){

                      
                        $this->db->set('button_name', $button["button_name"]);
                        $this->db->set('button_state', $check_val);
                        $this->db->where('id',$button["button_id"]);
                        $this->db->update('tus_kuralları');

                    }
                    if($button["button_state"] == '1'){
                        
                      
                        $tus_kuralları=[
                            "button_name"=>$button["button_name"],
                            "button_state"=>$check_val,
                            "potansiyel_degerlendirme_id"=>$val["pot_id"]
                            
                        ];


                        $this->db->insert('tus_kuralları', $tus_kuralları);
                        $button_id = $this->db->insert_id();
                        
                        
                    }

                }



                $success = 1;
            }
            if($val["state"] == '1'){

              
                $kriter= [
                    "name"=>$val["name"]
                ];
    
                $this->db->insert('kriter', $kriter);
                $criteria_id = $this->db->insert_id();
    
                if($criteria_id > 0 ){
    
                    $potaniyel_degerlendirme=[
                        "criteria_id"=>$criteria_id,
                        "min"=>$val["min"],
                        "max"=>$val["max"],
                        "donem_id"=>$val["donem_id"],
        
                    ];
        
                    $this->db->insert('potaniyel_degerlendirme', $potaniyel_degerlendirme);
                    $id = $this->db->insert_id();
                    if($id > 0){
                        $success = 1;
                    }


                    }
            }

          
        }

        return $success;

        


    }

    public function deleteCriter($id){

        $this->db->set('status', 0);
        $this->db->where('id', $id);
        $a = $this->db->update('potaniyel_degerlendirme'); 
        $boolean = false;
        if($a){
            $boolean = true;
        }

        return $boolean;
    }

    public function deleteButtonById($id){

        $this->db->set('button_status', 0);
        $this->db->where('id', $id);
        $a = $this->db->update('tus_kuralları'); 
        $boolean = false;
        if($a){
            $boolean = true;
        }

        return $boolean;
    }

    


    public function getAllButtons($id){

        $this->db->select('*');
        $this->db->from('tus_kuralları');
        $this->db->where('potansiyel_degerlendirme_id', $id);
        $this->db->where('button_status', 1);
        $this->db->order_by('id', 'asc');
        $query = $this->db->get();

        // echo $this->db->last_query();
        // exit();

        return $query->result_array();

    }


    public function checkHighPotential($pot_formu){


        //SELECT * FROM degerlendirmeler WHERE  ft_id =3 AND form_id = 3005 AND pernr = 100001 AND donem=3 AND  yil=2019
        
        $this->db->select('*');
        $this->db->from('degerlendirmeler');
        $this->db->where('ft_id', $pot_formu["ft_id"]);
        $this->db->where('donem', $pot_formu["donem"]);
        $this->db->where('form_id', $pot_formu["form_id"]);
        $this->db->where('pernr', $pot_formu["pernr"]);
        $this->db->where('yil', $pot_formu["yil"]);
        
        $query = $this->db->get();

        // echo $this->db->last_query();
        // exit();

        

        return $query->result_array();

    }


    public function getPotansiyelInformationTextByYear($yil){

        //SELECT * FROM `potansiyel_form_metin` WHERE `min_date` <= 2019 AND `max_date` >= 2019

        $this->db->select('*');
        $this->db->from('potansiyel_form_metin');
        $this->db->where('min_date <=', $yil);
        $this->db->where('max_date >=', $yil);
        
        $query = $this->db->get();
        return $query->result_array();

    }



    public function getGosgelerByGostergeIdForExcel($g_id){
        
        $this->db->select('*');
        $this->db->from('gostergelerv2_kademeler');
        $this->db->where('g_id =', $g_id);
        $this->db->where('durum =', 1);
      
        $query = $this->db->get();
        return $query->result_array();

    }




    //public function getHavuzPerformansDuzeyleriCount($donem,$yil,$id_ida){
    public function getHavuzPerformansDuzeyleriCount($donem,$yil,$id_ida){
      
        $this->db->select('*');
        $this->db->from('havuz_performans_duzeyleri');
        $this->db->where('year =', $yil);
        $this->db->where('period =', $donem);
        $this->db->where('status =',1);
        //$this->db->where('id_da =',$id_ida);

        $query = $this->db->get();
        // echo $this->db->last_query();
        //return  $query->num_rows();

        return $query->result_array();

    }

    
    public function getId_da($is_over_ten,$donem,$yil){
        //SELECT * FROM `dagilim_anahtarlari` WHERE `year` = '2022' AND `status` = 1 AND is_over_ten = BOOLEAN

        $is_over_ten = ($is_over_ten == 1 ) ? 1 : 0;

        $this->db->select('id');
        $this->db->from('dagilim_anahtarlari');
        $this->db->where('year =', $yil);
        $this->db->where('status =',1);
        $this->db->where('is_over_ten =',$is_over_ten);
        $query = $this->db->get();
        // echo $this->db->last_query();
        //return  $query->num_rows();

        return $query->result_array();

    }






    public function getKeysByYeardAndPersonCount($year,$is_over){

        // SELECT 
        //     * 
        // FROM `dagilim_anahtarlari`
        // WHERE YEAR = 2021 AND is_over_ten = 0 AND STATUS = 1

        $this->db->select('*');
        $this->db->from('dagilim_anahtarlari');
        $this->db->where('year = ', $year);
        $this->db->where('is_over_ten = ', $is_over);
        $this->db->where('status = ', 1);
        
        $query = $this->db->get();
        return $query->result_array();

    }



    public function getAssingedPools($year,$is_over,$idkey){


        // SELECT * FROM `pools_has_keys`
        // INNER JOIN `kademelendirme_atamalari` ON kademelendirme_atamalari.havuz_id = pools_has_keys.idhavuz
        // WHERE yil = 2021 
        // GROUP BY kademelendirme_atamalari.havuz_id HAVING COUNT(pernr) < 10
      

        $this->db->select('*');
        $this->db->from('pools_has_keys');
        $this->db->join('kademelendirme_atamalari', 'kademelendirme_atamalari.havuz_id = pools_has_keys.idhavuz', 'inner');
        $this->db->where('yil = ', $year);
        //$this->db->where('id_da = ', $idkey);
        $this->db->where('phk_status = ', 1);
        $this->db->group_by('kademelendirme_atamalari.havuz_id');
        ($is_over == 1) ?  $this->db->having('COUNT(pernr) > 10') :  $this->db->having('COUNT(pernr) <= 10'); 
        
        $query = $this->db->get();
        // echo $this->db->last_query();
        // exit();
        return $query->result_array();

    }



    public function get_dagilim_for_excel(){


        // SELECT  idhavuz,pools_has_keys.year,havuz_text,NAME,is_over_ten
        // FROM `pools_has_keys` 
        // INNER JOIN `kademelendirme_havuzlari` ON `pools_has_keys`.`idhavuz` = `kademelendirme_havuzlari`.`havuz_id`
        // INNER JOIN `dagilim_anahtarlari` ON `dagilim_anahtarlari`.`id` = `pools_has_keys`.id_da
        // WHERE phk_status = 1 AND STATUS = 1
      

        $this->db->select('idhavuz,pools_has_keys.year,havuz_text,name,is_over_ten');
        $this->db->from('pools_has_keys');
        $this->db->join('kademelendirme_havuzlari', 'pools_has_keys.idhavuz = kademelendirme_havuzlari.havuz_id', 'inner');
        $this->db->join('dagilim_anahtarlari', 'dagilim_anahtarlari.id = pools_has_keys.id_da', 'inner');
        $this->db->where('phk_status', 1);
        $this->db->where('status', 1);
        $query = $this->db->get();
       
        return $query->result_array();

    }
    




    public function assingKeyToPool($data){
        $this->db->trans_start(); 

        if($data["is_update"]){
            foreach($data["havuz_ids"] as $havuz){

                // update olmayanların da dağılım anahtarı güncellensin diye
                $this->db->set('id_da', $data["idda"]);
                $this->db->where('idhavuz', $havuz["id"]);
                $this->db->where('year', $data["year"]);
                $this->db->where('phk_status',1);
                $this->db->update('pools_has_keys');

                if($havuz["update"]){ // update var 
                    if($havuz["state"]){
                        $res=[
                            "year"=>$data["year"],
                            "id_da"=>$data["idda"],
                            "idhavuz"=>$havuz["id"]
                        ];
                        $this->db->insert('pools_has_keys', $res);
                    }
                    else if(!$havuz["state"]){
                        // update yerine delete atmalı mıyız?
                        $this->db->set('phk_status', 0); // deleted
                        $this->db->where('idhavuz', $havuz["id"]);
                        $this->db->where('year', $data["year"]);
                        $this->db->update('pools_has_keys');
                    }
                }
            }
        }
        else{
            foreach($data["havuz_ids"] as $havuz){
                if($havuz["state"]){
                    $res=[
                        "year"=>$data["year"],
                        "id_da"=>$data["idda"],
                        "idhavuz"=>$havuz["id"]
                    ];
                    $this->db->insert('pools_has_keys', $res);
                }
            }
        } 
        

        $this->db->trans_complete();
        return ($this->db->trans_status() === FALSE) ? false : true;
    
    }


    public function getPoolsByYeardAndPersonCount($year,$is_over){

        
        // SELECT kademelendirme_havuzlari.havuz_id,COUNT(pernr),havuz_text
        // FROM `kademelendirme_atamalari`
        // INNER JOIN `kademelendirme_havuzlari` ON kademelendirme_atamalari.havuz_id = kademelendirme_havuzlari.havuz_id
                     ////// LEFT JOIN pools_has_keys  ON pools_has_keys.idhavuz = kademelendirme_havuzlari.havuz_id

        // WHERE yil = 2021 
        // GROUP BY kademelendirme_havuzlari.havuz_id HAVING COUNT(pernr) < 10


        // SELECT `kademelendirme_havuzlari`.`havuz_id`, COUNT(pernr), `havuz_text`,id_da
        // FROM `kademelendirme_atamalari`
        // INNER JOIN `kademelendirme_havuzlari` ON `kademelendirme_atamalari`.`havuz_id` = `kademelendirme_havuzlari`.`havuz_id`
        // LEFT JOIN `pools_has_keys` ON `kademelendirme_atamalari`.`havuz_id` = `pools_has_keys`.`idhavuz`  AND  phk_status = 1  
        //LEFT JOIN `dagilim_anahtarlari` ON `dagilim_anahtarlari`.`id` = `pools_has_keys`.`id_da` AND `phk_status` = 1

        // WHERE `yil` = '2022' 
        // GROUP BY `kademelendirme_havuzlari`.`havuz_id`
        // HAVING COUNT(pernr) > 10



        $this->db->select('kademelendirme_havuzlari.havuz_id,COUNT(pernr),havuz_text,id_da,name');
        $this->db->from('kademelendirme_atamalari');
        $this->db->join('kademelendirme_havuzlari', 'kademelendirme_atamalari.havuz_id = kademelendirme_havuzlari.havuz_id', 'inner');
        $this->db->join('pools_has_keys', 'kademelendirme_atamalari.havuz_id = pools_has_keys.idhavuz and phk_status = 1', 'left');
        $this->db->join('dagilim_anahtarlari', 'dagilim_anahtarlari.id = pools_has_keys.id_da', 'left');

        $this->db->where('yil = ', $year);
        //$this->db->where('phk_status = ',1);
        $this->db->group_by('kademelendirme_havuzlari.havuz_id');
        ($is_over == 1) ?  $this->db->having('COUNT(pernr) > 10') : $this->db->having('COUNT(pernr) <= 10'); 
       

        
        $query = $this->db->get();

        //echo $this->db->last_query();
        // exit();
        return $query->result_array();

    }






    
    
}