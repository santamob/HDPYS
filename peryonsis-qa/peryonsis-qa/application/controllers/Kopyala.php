<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kopyala extends MY_Controller {
    
    public function __construct() {
        parent::__construct();
        
    }

    public function org($yil, $donem)
    {
        unset($kosul);
        $select = '*';
        $kosul['yil'] = 2017;
        $kosul['donem'] = 3;
        $tablo1 = $this->genel_model->veri_cek_cs("organizasyon_d", $select, $kosul);
        
        foreach ($tablo1 as $key=>$arr)
        {
            $tablo1[$key]['id'] = "";
            $tablo1[$key]['donem'] = 2;
        }
        
       
        unset($kosul);
        $select = '*';
        $kosul['yil'] = 2017;
        $kosul['donem'] = 2;
        $r = $this->genel_model->veri_kaydet_cs("organizasyon_d", $tablo1, $kosul);
        
        if ($r)
        {
            echo "ok";
        }
        else
        {
            echo "sıkıntı";
        }
    }
    
    public function deg()
    {
        unset($kosul);
        $select = '*';
        $tablo1 = $this->genel_model->veri_cek_cs("degerlendirmeler_yedek", $select);
        
        $sayac = 400;
        foreach ($tablo1 as $key=>$arr)
        {
            $tablo1[$key]['id'] = $sayac;
            $tablo1[$key]['puan'] = $arr['puan']/2;
            $sayac++;
        }
        
       
        unset($kosul);
        $r = $this->genel_model->veri_kaydet_cs("degerlendirmeler_yedek", $tablo1);
        
        if ($r)
        {
            echo "ok";
        }
        else
        {
            echo "sıkıntı";
        }
    }
    
    
}
