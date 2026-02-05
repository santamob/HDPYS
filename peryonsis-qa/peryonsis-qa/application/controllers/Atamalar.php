<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Atamalar extends MY_Controller {
    
    public function __construct() {
        parent::__construct();
        
        $this->load->model("atamalar_model");
        $this->load->library("gradation_key");
        
        // Yetki kontrolü sadee ik anahtar kullanıcıları erişebilir..
        if ($this->session->userdata('pernr') != 101993 && $this->session->userdata('pernr') != 101081 && $this->session->userdata('pernr') != 100003 && $this->session->userdata('pernr') != 102576)
        {
            $alert_message = alert_message('Hata.');
            $this->session->set_flashdata('message', $alert_message);
            redirect('/ikyp/donem_listesi/');
            exit();
        }
    }


    public function atama_ekrani()
    {
        unset($kosul);
        unset($order_by);
        $select = 'max(yil) as a';
        $kosul['donem'] = 3;
        $order_by['atama_tarihi'];
        $donem_listesi = $this->genel_model->veri_cek_ts("donem_listesi", $select, $kosul);

        $data['max_donem'] = $donem_listesi['a'];
        $data['personeller'] = $this->atamalar_model->get_btm_tsm();
        
        unset($kosul);
        unset($order_by);
        $select = '*';
        $order_by['atama_tarihi'];
        $atamalar = $this->genel_model->veri_cek_cs("atamalar", $select, '', $order_by);

  
        $yil_max = 0;
        $yil_min = date('Y');
        foreach ($atamalar as $key=>$arr)
        {
            $yil = substr($arr['atama_tarihi'], 0, 4);
            if ($yil >= $yil_max)
            {
                $yil_max = $yil;
            }
            if ($yil <= $yil_min)
            {
                $yil_min = $yil;
            }
            $data['kademe'][$arr['pernr']][$yil] = $arr['kademe'];
            $data['kademe_t'][$arr['pernr']][$yil] = $arr['atama_tarihi'];
        }
        
        $yil_min = $yil_max-6;
        $data['yil_min'] = $yil_min;
        $data['yil_max'] = $yil_max;


        
        unset($kosul);
        unset($order_by);
        $select = '*';
        $order_by['yil'];
        $puanlar = $this->genel_model->veri_cek_cs("puanlar", $select, '', $order_by);


        foreach ( $puanlar as $key=>$arr)
        {
            $data['puan'][$arr['pernr']][$arr['yil']] = $arr['puan']; // puan aslında kademe
        }


        // Gereksiz kod tekrar gözlemlendi 2022-03-02 Suitso
        
        // unset($kosul);
        // unset($order_by);
        // $select = '*';
        // $order_by['yil'];
        // $puanlar = $this->genel_model->veri_cek_cs("puanlar", $select, '', $order_by);
        
        // foreach ( $puanlar as $key=>$arr)
        // {
        //     $data['puan'][$arr['pernr']][$arr['yil']] = $arr['puan'];
        // }
        
        unset($kosul);
        unset($order_by);
        $select = '*';
        //$yil_max = 2021; // robin ekledi
        $kosul['yil'] = $yil_max;
        $ks = $this->genel_model->veri_cek_cs("kademelendirme_sonuclari", $select, $kosul, $order_by);
        
        foreach ($ks as $key=>$arr){
            $data['puan'][$arr['pernr']][$arr['yil']] = $arr['sk'];
            //$data['puan'][$arr['pernr']][$arr['yil']] = $arr['kyo'];
        }

        // echo "<pre>";
        // print_r($data["puan"]);
        // echo "</pre>";
        // exit();

        $this->template->goster('atama_ekrani', $data);
    }

    public function guncelle()
    {
        $pernr = $this->input->post("pernr");
        $kademe = $this->input->post("kademe");
        $atama_tarihi = $this->input->post("atama_tarihi");
        
        if (empty_f($pernr) || empty_f($kademe) || empty_f($atama_tarihi))
        {
            $alert_message = alert_message('Hata.');
            $this->session->set_flashdata('message', $alert_message);
            redirect('./atamalar/atama_ekrani');
            exit();
        }
        
        $yil = substr($atama_tarihi, 0, 4);
        
        $kosul['pernr'] = $pernr;
        $kosul['atama_tarihi>='] = $yil."-01-01";
        $kosul['atama_tarihi<='] = $yil."-12-31";
        $veri_guncelle['pernr'] = $pernr;
        $veri_guncelle['atama_tarihi'] = $atama_tarihi;
        $veri_guncelle['kademe'] = $kademe;
        $veri_guncelle['guncelleyen'] = $this->session->userdata('pernr');
        $veri_guncelle['g_time'] = date("Y-m-d H:i:s");
        $r = $this->genel_model->veri_kaydet("atamalar", $veri_guncelle, $kosul);
        
        if (!$r)
        {
            $alert_message = alert_message('Teknik bir aksaklık oluştu daha sonra tekrar deneyiniz.');
            $this->session->set_flashdata('message', $alert_message);
            redirect('./atamalar/atama_ekrani');
            exit();
        }

        $alert_message = alert_message('Kayıt başarılı.', 'success');
        $this->session->set_flashdata('message', $alert_message);
        redirect('./atamalar/atama_ekrani');
        exit();
    }
    
    public function oneri()
    {
        unset($kosul);
        unset($order_by);
        $select = 'max(yil) as a';
        $kosul['donem'] = 3;
        $order_by['atama_tarihi'];
        $donem_listesi = $this->genel_model->veri_cek_ts("donem_listesi", $select, $kosul);
        
        $donem_yil = $donem_listesi['a'];
        $oneri_yil = $donem_listesi['a']+1;
        
        unset($kosul);
        unset($order_by);
        $select = '*';
        $order_by['atama_tarihi'];
        $atamalar = $this->genel_model->veri_cek_cs("atamalar", $select, '', $order_by);
        
        foreach ($atamalar as $key=>$arr)
        {
            $yil = substr($arr['atama_tarihi'], 0, 4);
            $kademe[$arr['pernr']][$yil] = $arr['kademe'];
            $kademe_t[$arr['pernr']][$yil] = $arr['atama_tarihi'];
            if ($arr['atama_tarihi'] < $yil."-07-01" && $yil != $oneri_yil)
            {
                $kademe_k[$arr['pernr']][$arr['kademe']]++;
            }
            
            if ($yil != $oneri_yil)
            {
                $kademe_tk[$arr['pernr']]++;
            }
        }


    //    echo "<pre>";
    //    print_r($atamalar);
    //    echo "</pre>";
    //    exit();
       
        
        unset($kosul);
        unset($order_by);
        $select = '*';
        $order_by['yil'];
        $puanlar = $this->genel_model->veri_cek_cs("puanlar", $select, '', $order_by);
        
        foreach ( $puanlar as $key=>$arr)
        {
            $puan[$arr['pernr']][$arr['yil']] = $arr['puan'];
        }


        
        
        unset($kosul);
        unset($order_by);
        $select = '*';
        $kosul['yil'] = $donem_yil;
        $ks = $this->genel_model->veri_cek_cs("kademelendirme_sonuclari", $select, $kosul, $order_by);

        // echo "<pre>";
        // print_r($puanlar);
        // echo "</pre>";
        // exit();

        // var_dump($puanlar);
        // var_dump($ks);
        // exit();
        
        $sayac2 = 0;
        foreach ( $ks as $key=>$arr)
        {
            //$puan[$arr['pernr']][$arr['yil']] = $arr['sk'];
            $puan[$arr['pernr']][$arr['yil']] = $arr['kyo']; // robin ekledi
            $veri_guncelle2[$sayac2]['pernr'] = $arr['pernr'];
            $veri_guncelle2[$sayac2]['yil'] = $arr['yil'];
            //$veri_guncelle2[$sayac2]['puan'] = $arr['sk'];
            $veri_guncelle2[$sayac2]['puan'] = $arr['kyo']; // robin ekledi
            $sayac2++;
        }
        
        $personeller = $this->atamalar_model->get_btm_tsm();
        
        $sayac = 0;
        $g_time = date("Y-m-d H:i:s");
        $yil1 = $donem_yil;
        $yil2 = $donem_yil-1;
        $yil3 = $donem_yil-2;


        // Robin ekledi  YILLARA GÖRE KADEMELENDİRME LİSTESİ
        $gradationData_yil1 = $this->gradation_key->pool_levels_by_year($yil1);
        $gradationData_yil2 = $this->gradation_key->pool_levels_by_year($yil2);
        $gradationData_yil3 = $this->gradation_key->pool_levels_by_year($yil3);

        $maxGrad_yil1 =  max(array_keys($gradationData_yil1));
        $maxGrad_yil2 =  max(array_keys($gradationData_yil2));
        $maxGrad_yil3 =  max(array_keys($gradationData_yil3));

        $minGrad_yil1 =  min(array_keys($gradationData_yil1));
        $minGrad_yil2 = min(array_keys($gradationData_yil2));
        $minGrad_yil3 = min(array_keys($gradationData_yil3));
        // Robin ekledi



        foreach ($personeller as $key=>$arr)
        {
            $son_kademe = $kademe[$arr['pernr']][$donem_yil];
            $puan1 = $puan[$arr['pernr']][$yil1];
            $puan2 = $puan[$arr['pernr']][$yil2];
            $puan3 = $puan[$arr['pernr']][$yil3];

            $veri_guncelle[$sayac]['pernr'] = $arr['pernr'];
            $veri_guncelle[$sayac]['atama_tarihi'] = $oneri_yil."-01-01";
            $veri_guncelle[$sayac]['guncelleyen'] = $this->session->userdata('pernr');
            $veri_guncelle[$sayac]['g_time'] = $g_time;
            
            // Yeni girmişse dikkate alma
            if ($kademe_tk[$arr['pernr']] == 1)
            {
                // Atama tarihine bak 1 temmuz sonrası ise dikkate alma
                if ($kademe_t[$arr['pernr']][$donem_yil] >= $donem_yil."-07-01")
                {
                    $veri_guncelle[$sayac]['kademe'] = $son_kademe;
                    $sayac++;
                    continue;
                }
            }

            
            
            
            $veri_guncelle[$sayac]['kademe'] = $son_kademe;
            
            if ($son_kademe == "TSM-C")
            {
                if ($kademe_k[$arr['pernr']][$son_kademe] == 1 || $kademe_k[$arr['pernr']][$son_kademe] == 2)
                {
                    //if ($puan1 == 4)
                    if ($puan1 == $gradationData_yil1[$maxGrad_yil1]->id)
                    {
                        $veri_guncelle[$sayac]['kademe'] = "TSM-B";
                        
                    }
                }
                else if ($kademe_k[$arr['pernr']][$son_kademe] == 3 || $kademe_k[$arr['pernr']][$son_kademe] == 4)
                {
                    //if (($puan1 == 4 || $puan1 == 3) && ($puan2 == 4 || $puan2 == 3))
                    if ( 
                            ($puan1 == $gradationData_yil1[$maxGrad_yil1]->id || $puan1 == $gradationData_yil1[$maxGrad_yil1-1]->id) && 
                            ($puan2 == $gradationData_yil2[$maxGrad_yil2]->id || $puan2 == $gradationData_yil2[$maxGrad_yil2-1]->id)
                       )
                    {
                        $veri_guncelle[$sayac]['kademe'] = "TSM-B";
                       
                    }
                }
                else if ($kademe_k[$arr['pernr']][$son_kademe] >= 5)
                {
                    //if (($puan1 == 4 || $puan1 == 3 || $puan1 == 2) && ($puan2 == 4 || $puan2 == 3 || $puan2 == 2) && ($puan3 == 4 || $puan3 == 3 || $puan3 == 2))
                    if (
                            ($puan1 == $gradationData_yil1[$maxGrad_yil1]->id || $puan1 ==  $gradationData_yil1[$maxGrad_yil1-1]->id || $puan1 == $gradationData_yil1[$maxGrad_yil1-2]->id) && 
                            ($puan2 == $gradationData_yil2[$maxGrad_yil2]->id || $puan2 == $gradationData_yil2[$maxGrad_yil2-1]->id || $puan2 == $gradationData_yil2[$maxGrad_yil2-2]->id) &&
                            ($puan3 == $gradationData_yil3[$maxGrad_yil3]->id || $puan3 ==  $gradationData_yil3[$maxGrad_yil3-1]->id || $puan3 == $gradationData_yil3[$maxGrad_yil3-2]->id)
                        )
                    {
                        $veri_guncelle[$sayac]['kademe'] = "TSM-B";
                    }
                }
            }
            else if ($son_kademe == "TSM-B")
            {
                if ($kademe_k[$arr['pernr']][$son_kademe] >= 3)
                {
                    //if (($puan1 == 4) && ($puan2 == 4 || $puan2 == 3) && ($puan3 == 4 || $puan3 == 3))
                    if (
                            ($puan1 == $gradationData_yil1[$maxGrad_yil1]->id) && 
                            ($puan2 == $gradationData_yil2[$maxGrad_yil2]->id || $puan2 == $gradationData_yil2[$maxGrad_yil2-1]->id) && 
                            ($puan3 == $gradationData_yil3[$maxGrad_yil3]->id || $puan3 == $gradationData_yil3[$maxGrad_yil3-1]->id)
                        )
                    {
                        $veri_guncelle[$sayac]['kademe'] = "TSM-A";
                    }
                }
            }
            else if ($son_kademe == "BTM-B")
            {
                if ($kademe_k[$arr['pernr']][$son_kademe] >= 3)
                {
                    //if (($puan1 == 4) && ($puan2 == 4 || $puan2 == 3) && ($puan3 == 4 || $puan3 == 3))
                    if (
                            ($puan1 == $gradationData_yil1[$maxGrad_yil1]->id) && 
                            ($puan2 == $gradationData_yil2[$maxGrad_yil2]->id || $puan2 == $gradationData_yil2[$maxGrad_yil2-1]->id) &&
                            ($puan3 == $gradationData_yil3[$maxGrad_yil3]->id || $puan3 == $gradationData_yil3[$maxGrad_yil3-1]->id)
                        )
                    {
                        $veri_guncelle[$sayac]['kademe'] = "BTM-A";
                    }
                }
            }

            

           
            // if ($son_kademe == "TSM-C")
            // {
            //     if ($kademe_k[$arr['pernr']][$son_kademe] == 1 || $kademe_k[$arr['pernr']][$son_kademe] == 2)
            //     {
            //         if ($puan1 == 4)
            //         {
            //             $veri_guncelle[$sayac]['kademe'] = "TSM-B";
            //         }
            //     }
            //     else if ($kademe_k[$arr['pernr']][$son_kademe] == 3 || $kademe_k[$arr['pernr']][$son_kademe] == 4)
            //     {
            //         if (($puan1 == 4 || $puan1 == 3) && ($puan2 == 4 || $puan2 == 3))
            //         {
            //             $veri_guncelle[$sayac]['kademe'] = "TSM-B";
            //         }
            //     }
            //     else if ($kademe_k[$arr['pernr']][$son_kademe] >= 5)
            //     {
            //         if (($puan1 == 4 || $puan1 == 3 || $puan1 == 2) && ($puan2 == 4 || $puan2 == 3 || $puan2 == 2) && ($puan3 == 4 || $puan3 == 3 || $puan3 == 2))
            //         {
            //             $veri_guncelle[$sayac]['kademe'] = "TSM-B";
            //         }
            //     }
            // }
            // else if ($son_kademe == "TSM-B")
            // {
            //     if ($kademe_k[$arr['pernr']][$son_kademe] >= 3)
            //     {
            //         if (($puan1 == 4) && ($puan2 == 4 || $puan2 == 3) && ($puan3 == 4 || $puan3 == 3))
            //         {
            //             $veri_guncelle[$sayac]['kademe'] = "TSM-A";
            //         }
            //     }
            // }
            // else if ($son_kademe == "BTM-B")
            // {
            //     if ($kademe_k[$arr['pernr']][$son_kademe] >= 3)
            //     {
            //         if (($puan1 == 4) && ($puan2 == 4 || $puan2 == 3) && ($puan3 == 4 || $puan3 == 3))
            //         {
            //             $veri_guncelle[$sayac]['kademe'] = "BTM-A";
            //         }
            //     }
            // }


            $sayac++;
            
        }



        // echo "<pre>";
        // print_r($veri_guncelle);
        // echo "</pre>";
        // exit();
        
        unset($kosul);
        $kosul['atama_tarihi'] = $oneri_yil."-01-01";
        $r = $this->genel_model->veri_kaydet_cs("atamalar", $veri_guncelle, $kosul);

        if (!$r)
        {
            $alert_message = alert_message('Teknik bir aksaklık oluştu daha sonra tekrar deneyiniz.');
            $this->session->set_flashdata('message', $alert_message);
            redirect('./atamalar/atama_ekrani');
            exit();
        }
        
        unset($kosul);
        $kosul['yil'] = $donem_yil;
        $r = $this->genel_model->veri_kaydet_cs("puanlar", $veri_guncelle2, $kosul);

        if (!$r)
        {
            $alert_message = alert_message('Teknik bir aksaklık oluştu daha sonra tekrar deneyiniz.');
            $this->session->set_flashdata('message', $alert_message);
            redirect('./atamalar/atama_ekrani');
            exit();
        }
        
        $alert_message = alert_message('Kayıt başarılı.', 'success');
        $this->session->set_flashdata('message', $alert_message);
        redirect('./atamalar/atama_ekrani');
        exit();
        
    }


    
    
}
