<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class D_donemleri_new extends MY_Controller {
    
    public function __construct() {
        parent::__construct();
        
        $this->load->model("d_donemleri_model");
        $this->load->library("gradation_key");
        $this->load->library('encryption');
        
//        $this->load->model("d_donemleri_model");
    }

    public function takvim($yil, $donem, $disaridan_veri_al_ok)
    {
        if($donem == 2)
        {
            
            $this->template->goster('takvim_donem_2', $data);
        }
        if($donem == 3)
        {
            $this->template->goster('takvim_js', $data);
        }
        
    }
    


    public function form_goruntule2($form_id, $rilen_pernr, $yil, $donem, $ft_id){


        if (empty_f($form_id) || empty_f($rilen_pernr) || empty_f($yil) || empty_f($donem) || empty_f($ft_id))
        {
            $alert_message = alert_message('Hata.');
            $this->session->set_flashdata('message', $alert_message);
            redirect('./home/');
            exit();
        }
        
        // Kişinin kendisi veya yöneticisi veya iki üst yönetici değilse hata ver..
        unset($kosul);
        $select = '*';
        $kosul['yil'] = $yil;
        $kosul['donem'] = $donem;
        $kosul['pernr'] = $rilen_pernr;
        $yon1 = $this->genel_model->veri_cek_ts("organizasyon_d", $select, $kosul);


        




        
        if (!is_array($yon1))
        {
            $alert_message = alert_message('Hata.');
            $this->session->set_flashdata('message', $alert_message);
            redirect('./home/');
            exit();
        }
        
        unset($kosul);
        $select = '*';
        $kosul['yil'] = $yil;
        $kosul['donem'] = $donem;
        $kosul['pernr'] = $yon1['ust_pernr'];
        $yon2 = $this->genel_model->veri_cek_ts("organizasyon_d", $select, $kosul);
        
        if ($rilen_pernr != $this->session->userdata['pernr'] 
        &&  $yon1['ust_pernr'] != $this->session->userdata['pernr']
        &&  $yon2['ust_pernr'] != $this->session->userdata['pernr']
        )
        {
            $alert_message = alert_message('Bu formu görüntüleme yetkiniz yok.');
            $this->session->set_flashdata('message', $alert_message);
            redirect('/d_donemleri/takvim/'.$yil.'/'.$donem);
            exit();
        }
        
        // Potansiyel ve yönlendirme, görüşme formunu sadece yöneticiler görüntüler
        if ($rilen_pernr == $this->session->userdata['pernr'] && ($ft_id == 3 || $ft_id == 5 || $ft_id == 4))
        {
            $alert_message = alert_message('Bu formu görüntüleme yetkiniz yok.');
            $this->session->set_flashdata('message', $alert_message);
            redirect('/d_donemleri/takvim/'.$yil.'/'.$donem);
            exit();
        }
        
        // Form bilgilerini çek
        unset($kosul);
        $select = '*';
        $kosul['form_id'] = $form_id;
        $form = $this->genel_model->veri_cek_ts("formlar", $select, $kosul);

        if (!is_array($form))
        {
            $alert_message = alert_message('Hata.');
            $this->session->set_flashdata('message', $alert_message);
            redirect('./home/');
            exit();
        }
        
        unset($kosul);
        $select = '*';
        $kosul['yil'] = $yil;
        $kosul['donem'] = $donem;
        $donem_listesi = $this->genel_model->veri_cek_ts("donem_listesi", $select, $kosul);
        
        if (!is_array($donem_listesi))
        {
            $alert_message = alert_message('Hata.');
            $this->session->set_flashdata('message', $alert_message);
            redirect('./home/');
            exit();
        }
        
        $tarih = date('Y-m-d');
        
        if ($tarih < $donem_listesi['bat'])
        {
            $alert_message = alert_message('Hata.');
            $this->session->set_flashdata('message', $alert_message);
            redirect('./home/');
            exit();
        }
        
        if ($tarih > $donem_listesi['bit'])
        {
            $data['degisiklik'] = false;
        }
        else
        {
            $data['degisiklik'] = true;
        }
        
      
        //Hedef formu ise, yarı dönemse ve tüm göstergeler yıllıksa değişikliğe kapat
        if (($ft_id == 1 || $ft_id == 2) && $donem == 2 && $data['degisiklik'])
        {
           
            $form_detay = $this->d_donemleri_model->formlar_detay($form_id);
 
            if (!is_array($form_detay))
            {
                $alert_message = alert_message('Form bulunamadı.');
                $this->session->set_flashdata('message', $alert_message);
                redirect('/d_donemleri/takvim/'.$yil.'/'.$donem);
                exit();
            }
            
            //periyotların hepsi yıllık mı ?
            $kontrol = true;
            foreach ($form_detay as $key=>$arr)
            {
                if (!$kontrol)
                {
                    break;
                }
                
                if ($arr['periyod'] != 1)
                {
                    $kontrol = false;
                }
            }
            
            if ($kontrol)
            {
                $data['degisiklik'] = false;
            }
            
            unset($kontrol);
        }
        
        //Kademelendiren kişi kim ? Eğer yönetici ise kademelendirme başlayınca
        // değişikliğe kapat eğer yöneticinin yöneticisi ise onaya gönderilince..
        // Kademelendirmesi gereken havuzları listele..
        unset($kosul);
        $select = '*';
        $kosul['yil'] = $yil;
        $kosul['donem'] = $donem;
        $kosul['pernr'] = $rilen_pernr;
        $kademelendiren = $this->genel_model->veri_cek_ts("kademelendirme_atamalari", $select, $kosul);
        
        if ($kademelendiren['k_pernr'] == $yon1['ust_pernr'])
        {
            if ($ft_id == 1 || $ft_id == 2)
            {
                // Kademelendirme süreci başlamışmı kontrol et başladı ise değişikliğe kapat
                unset($kosul);
                $select = '*';
                $kosul['yil'] = $yil;
                $kosul['donem'] = $donem;
                $kosul['degerlendiren'] = $yon1['ust_pernr'];
                $kosul['onaylayan'] = $yon1['ust_pernr'];
                $kosul['ft_id'] = $ft_id;
                $onaylar = $this->genel_model->veri_cek_ts("onaylar", $select, $kosul);

                if (is_array($onaylar))
                {
                    if ($onaylar['onay_durum'] == 1 || $onaylar['onay_durum'] == 4)
                    {
                        $data['degisiklik'] = false;
                    }
                }
            }
            else
            {
                // Onay süreci başladı mı kontrol et başladı ise değişikliğe kapat
                unset($kosul);
                $select = '*';
                $kosul['yil'] = $yil;
                $kosul['donem'] = $donem;
                $kosul['degerlendiren'] = $yon1['ust_pernr'];
                $kosul['onaylayan'] = $yon2['ust_pernr'];
                $kosul['ft_id'] = $ft_id;
                $onaylar = $this->genel_model->veri_cek_ts("onaylar", $select, $kosul);

                if (is_array($onaylar))
                {
                    if ($onaylar['onay_durum'] == 1 || $onaylar['onay_durum'] == 4)
                    {
                        $data['degisiklik'] = false;
                    }
                }
            }
        }
        else
        {
            // Onay süreci başladı mı kontrol et başladı ise değişikliğe kapat
            unset($kosul);
            $select = '*';
            $kosul['yil'] = $yil;
            $kosul['donem'] = $donem;
            $kosul['degerlendiren'] = $yon1['ust_pernr'];
            $kosul['onaylayan'] = $yon2['ust_pernr'];
            $kosul['ft_id'] = $ft_id;
            $onaylar = $this->genel_model->veri_cek_ts("onaylar", $select, $kosul);

            if (is_array($onaylar))
            {
                if ($onaylar['onay_durum'] == 1 || $onaylar['onay_durum'] == 4)
                {
                    $data['degisiklik'] = false;
                }
            }
        }
     
        
        $degerlendiren_ack = 'yon1';
        if ($rilen_pernr == $this->session->userdata['pernr'])
        {
            if ($ft_id == 2)
            {
                $degerlendiren_ack = 'rilen';
            }
            $data['who'] = 'rilen';
        }
        else if ($this->session->userdata['pernr'] == $yon1['ust_pernr'])
        {
            $data['who'] = 'yon1';
        }
        else if ($this->session->userdata['pernr'] == $yon2['ust_pernr'])
        {
            $data['who'] = 'yon2';
        }
        
        if ($form['ft_id'] == 2 && ($data['who'] == 'yon1' || $data['who'] == 'yon2')) // Yetkinlik formu ise ast içinde veri çek
        {
            unset($kosul);
            $select = '*';
            $kosul['yil'] = $yil;
            $kosul['donem'] = $donem;
            $kosul['form_id'] = $form_id;
            $kosul['pernr'] = $rilen_pernr;
            $kosul['degerlendiren_ack'] = 'rilen';
            $degerlendirme_ast = $this->genel_model->veri_cek_cs("degerlendirmeler", $select, $kosul);
            
            if (is_array($degerlendirme_ast))
            {
                $ast_puan1 = 0;
                foreach ($degerlendirme_ast as $key=>$arr)
                {
                    $ast_puan1 = $ast_puan1 + $arr['puan'];
                    $data['gd_ast'][$arr['g_id']][$arr['periyod']] = $arr['duzey'];
                }
            }
            
            if ($donem == 3) // Yılsonu ise yarıyılıda çek
            {
                unset($kosul);
                $select = '*';
                $kosul['yil'] = $yil;
                $kosul['donem'] = 2;
                $kosul['form_id'] = $form_id;
                $kosul['pernr'] = $rilen_pernr;
                $kosul['degerlendiren_ack'] = 'rilen';
                $degerlendirme_ast = $this->genel_model->veri_cek_cs("degerlendirmeler", $select, $kosul);

                if (is_array($degerlendirme_ast))
                {
                    $ast_puan2 = 0;
                    foreach ($degerlendirme_ast as $key=>$arr)
                    {
                        $ast_puan2 = $ast_puan2 + $arr['puan'];
                        $data['gd_ast'][$arr['g_id']][$arr['periyod']] = $arr['duzey'];
                    }
                }
            }
            
            if ($donem == 2)
            {
                $ast_puan = $ast_puan1;
            }
            else if ($donem == 3)
            {
                $ast_puan = ($ast_puan1 + $ast_puan2);
            }
        }
        
        if ($ft_id == 5) // Yönlendirme formu ise kişiye atanan görüşme formunu çek.
        {
            // Kendine atanmış formlar
            unset($kosul);
            $select = '*';
            $kosul['yil'] = $yil;
            $kosul['donem'] = $donem;
            $kosul['pernr'] = $rilen_pernr;
            $kosul['ft_id'] = 4;
            $gorusme_formu = $this->genel_model->veri_cek_ts("form_atamalari", $select, $kosul);
            if (!is_array($gorusme_formu))
            {
                $alert_message = alert_message('Hata. Görüşme formu atanmamış.');
                $this->session->set_flashdata('message', $alert_message);
                redirect('./home/');
                exit();
            }

            unset($kosul);
            $select = '*';
            $kosul['yil'] = $yil;
            $kosul['donem'] = $donem;
            $kosul['pernr'] = $rilen_pernr;
            $kosul['ft_id'] = 3;
            $pot_formu = $this->genel_model->veri_cek_ts("form_atamalari", $select, $kosul);

            //            if (!is_array($pot_formu))
            //            {
            //                $alert_message = alert_message('Hata. Pot formu atanmamış.');
            //                $this->session->set_flashdata('message', $alert_message);
            //                redirect('./home/');
            //                exit();
            //            }

                        //$form_id = $gorusme_formu['form_id'];
            

           
            if (is_array($pot_formu))
            {
                $data['pot_varmi'] = 1;
            }
            else
            {
                $data['pot_varmi'] = 2;
            }

            if ($data['pot_varmi'] == 1)
            {
                // Kişinin potansiyel puanını çek..
                unset($kosul);
                $select = '*';
                $kosul['yil'] = $yil;
                $kosul['donem'] = $donem;
                $kosul['form_id'] = $pot_formu['form_id'];
                $kosul['pernr'] = $rilen_pernr;
                $kosul['degerlendiren_ack'] = $degerlendiren_ack;
                $degerlendirme = $this->genel_model->veri_cek_cs("degerlendirmeler", $select, $kosul);


               
                $puan = 0;
                if (is_array($degerlendirme))
                {
                    $puan = 0;
                    foreach ($degerlendirme as $key=>$arr)
                    {
                        $puan = $puan + $arr['puan'];
                    }
                }
                else
                {
                    $data['degisiklik'] = false;
                }

             
                //$puan = 20;
                $data['pot_puan'] = $puan;
                $donem_id = $this->getDonemId($donem,$yil);
                //echo $donem_id;
                //$donem_id=27;

                //tuşları da  ona göre gelecek

                //Çalışanın “Yüksek Potansiyel” kademesinde değerlendirilebilmesi için, 
                //değerlendirildiği potansiyel formundaki maddelerin en az “Beklenen Düzeyin Üstü (3)” kademesinde olması gerekmektedir.
              
                $is_high_potential = true ;
                if($yil > 2020){
                    $is_high_potential = $this->checkPotential($pot_formu,$donem,$yil);
                } 
                $data['pot_degerlendirme_data'] = $this->getPotansiyelDegerlendirme($puan,$donem_id,$is_high_potential);   // degisim var 

                
                //var_dump($data['pot_degerlendirme_data']);
                //exit();
                $data['pot_degerlendirme_data']["buttons"] = $this->getButtons($data['pot_degerlendirme_data']["pot_id"]);


                
                

            }
            
            //Görüşme doldurulmuşmu.
            unset($kosul);
            $select = '*';
            $kosul['yil'] = $yil;
            $kosul['form_id'] = $gorusme_formu['form_id'];
            $kosul['pernr'] = $rilen_pernr;
            $kosul['degerlendiren_ack'] = 'yon1';
            //$kosul['donem'] = $donem;
            $kayit = $this->genel_model->veri_cek_cs("degerlendirmeler", $select, $kosul);

            if (is_array($kayit))
            {
                foreach ($kayit as $key=>$arr)
                {
                    $data['aciklama2'][$arr['g_id']][$arr['periyod']] = $arr['aciklama'];
                }
            }
            else
            {
                $data['degisiklik'] = false;
            }

        }
        
        if ($donem == 3) // Yılsonu ise yarıyılıda çek
        {
            unset($kosul);
            $select = '*';
            $kosul['yil'] = $yil;
            $kosul['donem'] = 2;
            $kosul['form_id'] = $form_id;
            $kosul['pernr'] = $rilen_pernr;
            $kosul['degerlendiren_ack'] = $degerlendiren_ack;
            $degerlendirme = $this->genel_model->veri_cek_cs("degerlendirmeler", $select, $kosul);

            

            if (is_array($degerlendirme))
            {
                $puan2 = 0;
                foreach ($degerlendirme as $key=>$arr)
                {
                    if ($arr['planlanan'] != "")
                    {
                        $arr['planlanan'] = number_format($arr['planlanan'],2,',','');
                    }
                    if ($arr['gerceklesen'] != "")
                    {
                        $arr['gerceklesen'] = number_format($arr['gerceklesen'],2,',','');
                    }
                    if ($arr['sonuc'] != "")
                    {
                        $arr['sonuc'] = number_format($arr['sonuc'],2,',','');
                    }
                    
                    if ($arr['ft_id'] == 1 && $arr['urun'] != "") // Hedef ve ürün varsa
                    {
                        $puan2 = $puan2 + $arr['puan'];
                        $data['per_urun'][$arr['g_id']][$arr['periyod']][] = $arr['urun']; 
                        $data['gp'][$arr['g_id']][$arr['periyod']][$arr['urun']] = $arr['planlanan'];
                        $data['gg'][$arr['g_id']][$arr['periyod']][$arr['urun']] = $arr['gerceklesen'];
                        $data['gs'][$arr['g_id']][$arr['periyod']][$arr['urun']] = $arr['sonuc'];
                        $data['aciklama'][$arr['g_id']] = $arr['aciklama'];
                        $data['aciklama2'][$arr['g_id']][$arr['periyod']][$arr['urun']] = $arr['aciklama'];
                        $data['gd'][$arr['g_id']][$arr['periyod']][$arr['urun']] = $arr['duzey'];
                        $data['hp'] = $arr['hp'];
                    }
                    else
                    {
                        $puan2 = $puan2 + $arr['puan'];
                        $data['gp'][$arr['g_id']][$arr['periyod']] = $arr['planlanan'];
                        $data['gg'][$arr['g_id']][$arr['periyod']] = $arr['gerceklesen'];
                        $data['gs'][$arr['g_id']][$arr['periyod']] = $arr['sonuc'];
                        $data['aciklama'][$arr['g_id']] = $arr['aciklama'];
                        $data['aciklama2'][$arr['g_id']][$arr['periyod']] = $arr['aciklama'];
                        $data['gd'][$arr['g_id']][$arr['periyod']] = $arr['duzey'];
                        $data['hp'] = $arr['hp'];
                    }
                }
            }
        }
        
        unset($kosul);
        $select = '*';
        $kosul['yil'] = $yil;
        $kosul['donem'] = $donem;
        $kosul['form_id'] = $form_id;
        $kosul['pernr'] = $rilen_pernr;
        $kosul['degerlendiren_ack'] = $degerlendiren_ack;
        $degerlendirme = $this->genel_model->veri_cek_cs("degerlendirmeler", $select, $kosul);

        

        if (is_array($degerlendirme))
        {
            $puan1 = 0;
            foreach ($degerlendirme as $key=>$arr)
            {
                if ($arr['planlanan'] != "")
                {
                    $arr['planlanan'] = number_format($arr['planlanan'],2,',','');
                }
                if ($arr['gerceklesen'] != "")
                {
                    $arr['gerceklesen'] = number_format($arr['gerceklesen'],2,',','');
                }
                if ($arr['sonuc'] != "")
                {
                    $arr['sonuc'] = number_format($arr['sonuc'],2,',','');
                }
                
                if ($arr['ft_id'] == 1 && $arr['urun'] != "") // Hedef ve ürün varsa
                {
                    $puan1 = $puan1 + $arr['puan'];
                    $data['per_urun'][$arr['g_id']][$arr['periyod']][] = $arr['urun']; 
                    $data['gp'][$arr['g_id']][$arr['periyod']][$arr['urun']] = $arr['planlanan'];
                    $data['gg'][$arr['g_id']][$arr['periyod']][$arr['urun']] = $arr['gerceklesen'];
                    $data['gs'][$arr['g_id']][$arr['periyod']][$arr['urun']] = $arr['sonuc'];
                    $data['aciklama'][$arr['g_id']] = $arr['aciklama'];
                    $data['aciklama2'][$arr['g_id']][$arr['periyod']][$arr['urun']] = $arr['aciklama'];
                    $data['gd'][$arr['g_id']][$arr['periyod']][$arr['urun']] = $arr['duzey'];
                    $data['hp'] = $arr['hp'];
                }
                else
                {
                    $puan1 = $puan1 + $arr['puan'];
                    $data['gp'][$arr['g_id']][$arr['periyod']] = $arr['planlanan'];
                    $data['gg'][$arr['g_id']][$arr['periyod']] = $arr['gerceklesen'];
                    $data['gs'][$arr['g_id']][$arr['periyod']] = $arr['sonuc'];
                    $data['aciklama'][$arr['g_id']] = $arr['aciklama'];
                    $data['aciklama2'][$arr['g_id']][$arr['periyod']] = $arr['aciklama'];
                    $data['gd'][$arr['g_id']][$arr['periyod']] = $arr['duzey'];
                    $data['hp'] = $arr['hp'];
                }
            }
        }

        if ($donem == 2)
        {
            $puan = $puan1;
        }
        else if ($donem == 3)
        {
            $puan = ($puan1 + $puan2);
        }
        
        $data['form_baslik'] = $form;
        $data['donem_ack'] = $donem_listesi['donem_ack'];
        $data['rilen'] = $yon1;
        $data['ren'] = $yon2;
        $data['yil'] = $yil;
        $data['donem'] = $donem;
        $data['puan'] = $puan;
        $data['ast_puan'] = $ast_puan;
        
        if ($ft_id == 1) // Hedef formu
        {
            // Form detayınnı bul
            $form_detay = $this->d_donemleri_model->formlar_detay($form_id);


            
            
            if (!is_array($form_detay))
            {
                $alert_message = alert_message('Form bulunamadı.');
                $this->session->set_flashdata('message', $alert_message);
                redirect('/d_donemleri/takvim/'.$yil.'/'.$donem);
                exit();
            }
            
            $data['gostergeler'] = $form_detay;


            // echo "<pre>";
            // var_dump($data["gostergeler"]);
            // exit();
            

            
            $this->template->goster('hedef_formu', $data);
            
        }
        else if ($ft_id == 2) // Yetkinlik Formu
        {
            // Form detayınnı bul
            $form_detay = $this->d_donemleri_model->formlar_detay($form_id);
            
            if (!is_array($form_detay))
            {
                $alert_message = alert_message('Form bulunamadı.');
                $this->session->set_flashdata('message', $alert_message);
                redirect('/d_donemleri/takvim/'.$yil.'/'.$donem);
                exit();
            }
            
            foreach ($form_detay as $key=>$arr)
            {
                $data['kategori_toplam'][$arr['kategori']] = $data['kategori_toplam'][$arr['kategori']] + $arr['agirlik']; 
            }
            
            $data['gostergeler'] = $form_detay;
            
            $this->template->goster('yetkinlik_formu', $data);
        }
        else if ($ft_id == 3) // Potansiyel Formu
        {

            
            // Form detayınnı bul
            $form_detay = $this->d_donemleri_model->formlar_detay($form_id);
            
            if (!is_array($form_detay))
            {
                $alert_message = alert_message('Form bulunamadı.');
                $this->session->set_flashdata('message', $alert_message);
                redirect('/d_donemleri/takvim/'.$yil.'/'.$donem);
                exit();
            }
            
            foreach ($form_detay as $key=>$arr)
            {
                $data['kategori_toplam'][$arr['kategori']] = $data['kategori_toplam'][$arr['kategori']] + $arr['agirlik']; 
            }
            
            $data['gostergeler'] = $form_detay;

           
           
           
            $pot_formu=[
                "ft_id"=>$ft_id,
                "donem"=>$donem,
                "pernr"=>$rilen_pernr,
                "yil"=>$yil,
                "form_id" =>$form_id
            ];

            //var_dump( $pot_formu);
            //exit();
           
           // $pot_formu = $this->genel_model->veri_cek_ts("form_atamalari", $select, $kosul);

          
           $is_high_potential = true ;
           if($yil > 2020){
               $is_high_potential = $this->checkPotential($pot_formu,$donem,$yil); // DEGİŞECEK 
           } 
            
            
            $donem_id = $this->getDonemId($donem,$yil);
            $data['pot_degerlendirme_data'] = $this->getPotansiyelDegerlendirme($puan,$donem_id,$is_high_potential); // DEGİŞECEK 

            $metin = $this->d_donemleri_model->getPotansiyelInformationTextByYear($yil); // DEGİŞECEK 

            $data["metin"] ="";
            if(!empty($metin)){
                $data["metin"] = $metin[0]["metin"];
            }

           
            $this->template->goster('potansiyel_formu', $data);
        }
        else if ($ft_id == 4) // Görüşme Formu
        {
            // Form detayınnı bul
            $form_detay = $this->d_donemleri_model->formlar_detay($form_id);
            
            if (!is_array($form_detay))
            {
                $alert_message = alert_message('Form bulunamadı.');
                $this->session->set_flashdata('message', $alert_message);
                redirect('/d_donemleri/takvim/'.$yil.'/'.$donem);
                exit();
            }
            
            $data['gostergeler'] = $form_detay;
            
            $this->template->goster('gorusme_formu', $data);
        }
        else if ($ft_id == 5) // Yönlendirme Formu
        {
            // Form detayınnı bul
            $form_detay = $this->d_donemleri_model->formlar_detay($gorusme_formu['form_id']);
            
            if (!is_array($form_detay))
            {
                $alert_message = alert_message('Form bulunamadı.');
                $this->session->set_flashdata('message', $alert_message);
                redirect('/d_donemleri/takvim/'.$yil.'/'.$donem);
                exit();
            }
            
            $data['gostergeler'] = $form_detay;

            $this->template->goster('yonlendirme_formu', $data);
        }
        
    }
    
    // public function form_kaydet2()
    // {

    //     $form_id = $this->input->post('form_id');
    //     $rilen_pernr = $this->input->post('rilen_pernr');
    //     $yil = $this->input->post('yil');
    //     $donem = $this->input->post('donem');
    //     $ft_id = $this->input->post('ft_id');
        
    //     if (empty_f($form_id) || empty_f($rilen_pernr) || empty_f($yil) || empty_f($donem) || empty_f($ft_id))
    //     {
    //         $alert_message = alert_message('Hata.');
    //         $this->session->set_flashdata('message', $alert_message);
    //         redirect('./home/');
    //         exit();
    //     }
        
    //     // Kişinin kendisi veya yöneticisi veya iki üst yönetici değilse hata ver..
    //     unset($kosul);
    //     $select = '*';
    //     $kosul['yil'] = $yil;
    //     $kosul['donem'] = $donem;
    //     $kosul['pernr'] = $rilen_pernr;
    //     $yon1 = $this->genel_model->veri_cek_ts("organizasyon_d", $select, $kosul);
        
    //     if (!is_array($yon1))
    //     {
    //         $alert_message = alert_message('Hata.');
    //         $this->session->set_flashdata('message', $alert_message);
    //         redirect('./home/');
    //         exit();
    //     }
        
    //     if ($rilen_pernr != $this->session->userdata['pernr'] 
    //     && $this->session->userdata['pernr'] != $yon1['ust_pernr'] 
    //     )
    //     {
    //         $alert_message = alert_message('Hata.');
    //         $this->session->set_flashdata('message', $alert_message);
    //         redirect('./home/');
    //         exit();
    //     }
        
    //     // Potansiyel ve yönlendirme formunu sadece yöneticiler görüntüler
    //     if ($rilen_pernr == $this->session->userdata['pernr'] && ($ft_id == 3 || $ft_id == 5 || $ft_id == 4))
    //     {
    //         $alert_message = alert_message('Hata.');
    //         $this->session->set_flashdata('message', $alert_message);
    //         redirect('./home/');
    //         exit();
    //     }
        
    //     // Form tipini bul
    //     unset($kosul);
    //     $select = '*';
    //     $kosul['form_id'] = $form_id;
    //     $form = $this->genel_model->veri_cek_ts("formlar", $select, $kosul);
        
    //     if (!is_array($form))
    //     {
    //         $alert_message = alert_message('Hata.');
    //         $this->session->set_flashdata('message', $alert_message);
    //         redirect('./home/');
    //         exit();
    //     }
        
    //     unset($kosul);
    //     $select = '*';
    //     $kosul['yil'] = $yil;
    //     $kosul['donem'] = $donem;
    //     $donem_listesi = $this->genel_model->veri_cek_ts("donem_listesi", $select, $kosul);
        
    //     if (!is_array($donem_listesi))
    //     {
    //         $alert_message = alert_message('Hata.');
    //         $this->session->set_flashdata('message', $alert_message);
    //         redirect('./home/');
    //         exit();
    //     }
        
    //     $tarih = date('Y-m-d');
        
    //     if ($tarih < $donem_listesi['bat'])
    //     {
    //         $alert_message = alert_message('Hata.');
    //         $this->session->set_flashdata('message', $alert_message);
    //         redirect('./home/');
    //         exit();
    //     }
        
    //     if ($tarih > $donem_listesi['bit'])
    //     {
    //         $alert_message = alert_message('Hata.');
    //         $this->session->set_flashdata('message', $alert_message);
    //         redirect('./home/');
    //         exit();
    //     }
        
    //     if ($rilen_pernr == $this->session->userdata['pernr'])
    //     {
    //         $who = "rilen";
    //         if ($ft_id == 2)
    //         {
    //             $degerlendiren_ack = 'rilen';
    //             $degerlendiren_pernr = $this->session->userdata['pernr'];
    //         }
    //         else 
    //         {
    //             $degerlendiren_ack = 'yon1';
    //             $degerlendiren_pernr = $yon1['ust_pernr'];
    //         }
    //     }
    //     else if ($this->session->userdata['pernr'] == $yon1['ust_pernr'])
    //     {
    //         $who = "yon1";
    //         $degerlendiren_ack = 'yon1';
    //         $degerlendiren_pernr = $this->session->userdata['pernr'];
    //     }
        
    //     // Değerlendirme sonuçlarını çek onaya gönderilmiş mi kontrol et.. 
    //     unset($kosul);
    //     $select = '*';
    //     $kosul['yil'] = $yil;
    //     $kosul['donem'] = $donem;
    //     $kosul['form_id'] = $form_id;
    //     $kosul['pernr'] = $rilen_pernr;
    //     $kosul['degerlendiren_ack'] = $degerlendiren_ack;
    //     $degerlendirme = $this->genel_model->veri_cek_cs("degerlendirmeler", $select, $kosul);

        
    //     if (is_array($degerlendirme))
    //     {
    //         foreach ($degerlendirme as $key=>$arr)
    //         {
    //             // Bu göstergenin verisi oto geliyor.
    //             unset($kosul);
    //             $select = "*";
    //             $kosul['g_id'] = $arr['g_id'];
    //             //$g_bilgi = $this->genel_model->veri_cek_ts("gostergeler", $select, $kosul);  
    //             $g_bilgi = $this->genel_model->veri_cek_ts("gostergelerv2", $select, $kosul);                         
                
    //             if ($g_bilgi['veri_kaynagi'] == 1)
    //             {
    //                 $silinmeyecek_gosterge[] = $arr['g_id'];
    //                 continue;
    //             }
                
    //             $degerlendirme2[$arr['g_id']][$arr['periyod']]['planlanan'] = $arr['planlanan'];
    //             $degerlendirme2[$arr['g_id']][$arr['periyod']]['gerceklesen'] = $arr['gerceklesen'];
    //             $degerlendirme2[$arr['g_id']][$arr['periyod']]['sonuc'] = $arr['sonuc'];
    //             $degerlendirme2[$arr['g_id']]['aciklama'] = $arr['aciklama'];
    //             $degerlendirme2[$arr['g_id']][$arr['periyod']]['duzey'] = $arr['duzey'];
    //             $degerlendirme2['hp'] = $arr['hp'];
    //         }
    //     }
        
    //     if ($form['ft_id'] == 1) // Hedef formu
    //     {
    //         // Form detayınnı bul
    //         $form_detay = $this->d_donemleri_model->formlar_detay($form_id);
            
    //         $planlanan = $this->input->post('gp');
            
    //         foreach ($planlanan as $key=>$arr)
    //         {
    //             foreach ($arr as $key1=>$val1)
    //             {
    //                 if ($val1 == "")
    //                 {
    //                     continue;
    //                 }
    //                 $val1 = str_replace(",",".",$val1);
    //                 $planlanan[$key][$key1] = number_format($val1,2,'.','');
    //             }
    //         }
            
    //         $gerceklesen = $this->input->post('gg');
            
    //         foreach ($gerceklesen as $key=>$arr)
    //         {
    //             foreach ($arr as $key1=>$val1)
    //             {
    //                 if ($val1 == "")
    //                 {
    //                     continue;
    //                 }
    //                 $val1 = str_replace(",",".",$val1);
    //                 $gerceklesen[$key][$key1] = number_format($val1,2,'.','');
    //             }
    //         }
            
    //         $g_sonuc = $this->input->post('gs');
            
    //         foreach ($g_sonuc as $key=>$arr)
    //         {
    //             foreach ($arr as $key1=>$val1)
    //             {
    //                 if ($val1 == "")
    //                 {
    //                     continue;
    //                 }
    //                 $val1 = str_replace(",",".",$val1);
    //                 $g_sonuc[$key][$key1] = number_format($val1,2,'.','');
    //             }
    //         }
            
    //         // yarıyılda bu form ataması var mı ?
    //         unset($kosul);
    //         $select = '*';
    //         $kosul['yil'] = $yil;
    //         $kosul['donem'] = 2;
    //         $kosul['form_id'] = $form_id;
    //         $kosul['pernr'] = $rilen_pernr;
    //         $form_atamalari = $this->genel_model->veri_cek_ts("form_atamalari", $select, $kosul);

    //         if (is_array($form_atamalari))
    //         {
    //             $yy_form_var = true;
    //         }
    //         else
    //         {
    //             $yy_form_var = false;
    //         }

    //         // Veya ilk açılışa özel 2. dönem verisi var mı oku
    //         if (!$yy_form_var && $yil == 2017)
    //         {
    //             unset($kosul);
    //             $select = '*';
    //             $kosul['yil'] = $yil;
    //             $kosul['donem'] = 2;
    //             $kosul['form_id'] = $form_id;
    //             $kosul['pernr'] = $rilen_pernr;
    //             $kosul['degerlendiren_ack'] = 'yon1';
    //             $form_atamalari2 = $this->genel_model->veri_cek_cs("degerlendirmeler", $select, $kosul);

    //             if (is_array($form_atamalari2))
    //             {
    //                 $yy_form_var = true;
    //             }
    //             else
    //             {
    //                 $yy_form_var = false;
    //             }
    //         }
            
    //         $aciklama = $this->input->post('rilen_ack');
    //         $duzey = $this->input->post('gd');
    //         $time = date("Y-m-d H:i:s");
            
    //         $sayac = 0;
    //         foreach ($form_detay as $key=>$arr)
    //         {
    //             if (in_array($arr['g_id'], $silinmeyecek_gosterge))
    //             {
    //                 //ürün ataması olanların açıklama alnı için
    //                 unset($kosul);
    //                 $kosul['g_id'] = $arr['g_id'];
    //                 $kosul['yil'] = $yil;
    //                 $kosul['donem'] = $donem;
    //                 $kosul['pernr'] = $rilen_pernr;
    //                 $kosul['degerlendiren_ack'] = $degerlendiren_ack;
    //                 $kosul['form_id'] = $form_id;
    //                 $veri_guncelle['aciklama'] = $aciklama[$arr['g_id']];
    //                 $veri_guncelle['guncelleyen'] = $this->session->userdata('pernr');
    //                 $veri_guncelle['g_time'] = $time;
    //                 $r = $this->genel_model->veri_guncelle("degerlendirmeler", $veri_guncelle, $kosul);
    //                 continue;
    //             }
                
    //             $periyod_sayisi[$arr['g_id']] = 0;
    //             $g_periyod_sayisi[$arr['g_id']] = $arr['periyod'];
    //             for ($i = 1; $i <= $arr['periyod']; $i++)
    //             {
    //                 $kayit_kontrol = true;  
    //                 if ($donem == 2)
    //                 {
    //                     $periyod_hesap = 12/$arr['periyod']*$i;
    //                     if ($periyod_hesap > 6) // Yarı yıl
    //                     {
    //                         continue;
    //                     }
    //                 }
    //                 else if ($donem == 3)
    //                 {
    //                     $periyod_hesap = 12/$arr['periyod']*$i;
    //                     if ($periyod_hesap <= 6) // Yılsonu
    //                     {
    //                         continue;
    //                     }
    //                 }
                    
    //                 $data[$sayac]['yil'] = $yil;
    //                 $data[$sayac]['donem'] = $donem;
    //                 $data[$sayac]['pernr'] = $rilen_pernr;
    //                 $data[$sayac]['degerlendiren_ack'] = $degerlendiren_ack;
    //                 $data[$sayac]['degerlendiren_pernr'] = $degerlendiren_pernr;
    //                 $data[$sayac]['form_id'] = $form_id;
    //                 $data[$sayac]['ft_id'] = $ft_id;
    //                 $data[$sayac]['g_id'] = $arr['g_id'];
    //                 $data[$sayac]['periyod'] = $i;
                    
    //                 if ($who == "rilen" && $arr['veri_kaynagi'] == 2 )
    //                 {
    //                     if ($planlanan[$arr['g_id']][$i] != "" && $gerceklesen[$arr['g_id']][$i] != "" && $planlanan[$arr['g_id']][$i] != 0)
    //                     {
    //                         $data[$sayac]['planlanan'] = $planlanan[$arr['g_id']][$i];
    //                         $data[$sayac]['gerceklesen'] = $gerceklesen[$arr['g_id']][$i];

    //                         $sonuc = 0;
    //                         if ($arr['veri_hesaplama'] == 2) //Realizasyon
    //                         {
    //                             $sonuc = 100 - (($data[$sayac]['planlanan'] - $data[$sayac]['gerceklesen'])/$data[$sayac]['planlanan']*100);
    //                         }
    //                         else if ($arr['veri_hesaplama'] == 3) // Sapma
    //                         {
    //                             $sonuc = (($data[$sayac]['gerceklesen'] - $data[$sayac]['planlanan'])/$data[$sayac]['planlanan']*100);
    //                             if ($sonuc < 0)
    //                             {
    //                                 $sonuc = -1*$sonuc;
    //                             }
    //                         }
    //                         else if ($arr['veri_hesaplama'] == 4) // Sapma
    //                         {
    //                             $sonuc = (($data[$sayac]['gerceklesen'] - $data[$sayac]['planlanan'])/$data[$sayac]['planlanan']*100);
    //                         }

    //                         $data[$sayac]['sonuc'] = $sonuc;
    //                     }
    //                 }
    //                 else if ($who == "rilen" && $arr['veri_kaynagi'] == 4)
    //                 {
    //                     if ($g_sonuc[$arr['g_id']][$i] != "")
    //                     {
    //                         $data[$sayac]['sonuc'] = $g_sonuc[$arr['g_id']][$i];
    //                     }
    //                 }
    //                 else
    //                 {
    //                     $data[$sayac]['planlanan'] = $degerlendirme2[$arr['g_id']][$i]['planlanan'];
    //                     $data[$sayac]['gerceklesen'] = $degerlendirme2[$arr['g_id']][$i]['gerceklesen'];
    //                     $data[$sayac]['sonuc'] = $degerlendirme2[$arr['g_id']][$i]['sonuc'];
    //                 }
                    
    //                 if ($who == "rilen")
    //                 {
    //                     $data[$sayac]['aciklama'] = $aciklama[$arr['g_id']];
    //                 }
    //                 else
    //                 {
    //                     $data[$sayac]['aciklama'] = $degerlendirme2[$arr['g_id']]['aciklama'];
    //                 }
                    
    //                 if ($who == 'rilen')
    //                 {
    //                     if ($arr['degerlendirme_turu'] == '2') // Objektif
    //                     {
    //                         if (isset($data[$sayac]['sonuc']))
    //                         {
    //                             if ($data[$sayac]['sonuc'] >= $arr['k1_as'] && $data[$sayac]['sonuc'] <= $arr['k1_us'] && $arr['k1_as'] != '' && $arr['k1_us'] != '')
    //                             {
    //                                 $data[$sayac]['duzey'] = 1;
    //                             }
    //                             else if ($data[$sayac]['sonuc'] >= $arr['k2_as'] && $data[$sayac]['sonuc'] <= $arr['k2_us'] && $arr['k2_as'] != '' && $arr['k2_us'] != '')
    //                             {
    //                                 $data[$sayac]['duzey'] = 2;
    //                             }
    //                             else if ($data[$sayac]['sonuc'] >= $arr['k3_as'] && $data[$sayac]['sonuc'] <= $arr['k3_us'] && $arr['k3_as'] != '' && $arr['k3_us'] != '')
    //                             {
    //                                 $data[$sayac]['duzey'] = 3;
    //                             }
    //                             else if ($data[$sayac]['sonuc'] >= $arr['k4_as'] && $data[$sayac]['sonuc'] <= $arr['k4_us'] && $arr['k4_as'] != '' && $arr['k4_us'] != '')
    //                             {
    //                                 $data[$sayac]['duzey'] = 4;
    //                             }
    //                         }
    //                     }
    //                     else
    //                     {
    //                         $data[$sayac]['duzey'] = $degerlendirme2[$arr['g_id']][$i]['duzey'];
    //                     }
    //                 }
    //                 else if ($who == 'yon1')
    //                 {
    //                     if ($arr['degerlendirme_turu'] == '3') // Subjektif
    //                     {
    //                         $data[$sayac]['duzey'] = $duzey[$arr['g_id']][$i];
    //                     }
    //                     else
    //                     {
    //                         $data[$sayac]['duzey'] = $degerlendirme2[$arr['g_id']][$i]['duzey'];
    //                     }
    //                 }
                    
    //                 if ($data[$sayac]['duzey'] > 0)
    //                 {
    //                     $periyod_sayisi[$arr['g_id']]++;
    //                 }
                    
    //                 $data[$sayac]['puan'] = $arr['agirlik']/$arr['periyod']/4*$data[$sayac]['duzey'];
    //                 $data[$sayac]['guncelleyen'] = $this->session->userdata('pernr');
    //                 $data[$sayac]['g_time'] = $time;
                    
    //                 $sayac++;
    //             }
    //         }
            
    //         foreach ($data as $key=>$arr)
    //         {
    //             if ($donem == 2)
    //             {
    //                 $data[$key]['puan'] = $data[$key]['puan']*$g_periyod_sayisi[$arr['g_id']]/$periyod_sayisi[$arr['g_id']]/2;
    //             }
    //             else
    //             {
    //                 if ($g_periyod_sayisi[$arr['g_id']] == 1)
    //                 {
    //                     $data[$key]['puan'] = $data[$key]['puan']*$g_periyod_sayisi[$arr['g_id']]/$periyod_sayisi[$arr['g_id']];
    //                 }
    //                 else
    //                 {
    //                     // Yarıyılda form varsa ikiye böl
    //                     if ($yy_form_var)
    //                     {
    //                         $data[$key]['puan'] = $data[$key]['puan']*$g_periyod_sayisi[$arr['g_id']]/$periyod_sayisi[$arr['g_id']]/2;
    //                     }
    //                     else
    //                     {
    //                         $data[$key]['puan'] = $data[$key]['puan']*$g_periyod_sayisi[$arr['g_id']]/$periyod_sayisi[$arr['g_id']]; 
    //                     }
    //                 }
                    
    //             }
    //         }
            
    //         if (is_array($data))
    //         {
    //             unset($kosul);
    //             $kosul['yil'] = $yil;
    //             $kosul['donem'] = $donem;
    //             $kosul['pernr'] = $rilen_pernr;
    //             $kosul['degerlendiren_ack'] = $degerlendiren_ack;
    //             $kosul['form_id'] = $form_id;
                
    //             if (is_array($silinmeyecek_gosterge))
    //             {
    //                 $kosul['g_id!='] = $silinmeyecek_gosterge;
    //             }
                
    //             $r = $this->genel_model->veri_kaydet_cs("degerlendirmeler", $data, $kosul);

    //             if (!$r)
    //             {
    //                 $alert_message = alert_message('Teknik bir aksaklık oluştu daha sonra tekrar deneyiniz.');
    //                 $this->session->set_flashdata('message', $alert_message);
    //                 redirect('/d_donemleri/form_goruntule/'.$form_id.'/'.$rilen_pernr.'/'.$yil.'/'.$donem.'/'.$ft_id);
    //                 exit();
    //             }
                
    //             $alert_message = alert_message('Kayıt başarılı.', 'success');
    //             $this->session->set_flashdata('message', $alert_message);
    //             redirect('/d_donemleri/form_goruntule/'.$form_id.'/'.$rilen_pernr.'/'.$yil.'/'.$donem.'/'.$ft_id);
    //             exit();
    //         }

    //         $alert_message = alert_message('Kayıt başarılı.', 'success');
    //         $this->session->set_flashdata('message', $alert_message);
    //         redirect('/d_donemleri/form_goruntule/'.$form_id.'/'.$rilen_pernr.'/'.$yil.'/'.$donem.'/'.$ft_id);
    //         exit();
            
    //     }
    //     else if ($form['ft_id'] == 2 || $form['ft_id'] == 3) // Yetkinlik Formu
    //     {
    //         // Form detayınnı bul
    //         $form_detay = $this->d_donemleri_model->formlar_detay($form_id);

            
            
    //         $duzey = $this->input->post('gd');


            
    //         // yarıyılda bu form ataması var mı ?
    //         unset($kosul);
    //         $select = '*';
    //         $kosul['yil'] = $yil;
    //         $kosul['donem'] = 2;
    //         $kosul['form_id'] = $form_id;
    //         $kosul['pernr'] = $rilen_pernr;
    //         $form_atamalari = $this->genel_model->veri_cek_ts("form_atamalari", $select, $kosul);

    //         if (is_array($form_atamalari))
    //         {
    //             $yy_form_var = true;
    //         }
    //         else
    //         {
    //             $yy_form_var = false;
    //         }
            
    //         $time = date("Y-m-d H:i:s");
    //         $sayac = 0;
    //         foreach ($form_detay as $key=>$arr)
    //         {
    //             $periyod_sayisi[$arr['g_id']] = 0;
    //             $g_periyod_sayisi[$arr['g_id']] = $arr['periyod'];
    //             for ($i = 1; $i <= $arr['periyod']; $i++)
    //             {
    //                 if ($donem == 2)
    //                 {
    //                     $periyod_hesap = 12/$arr['periyod']*$i;
    //                     if ($periyod_hesap > 6) // Yarı yıl
    //                     {
    //                         continue;
    //                     }
    //                 }
    //                 else if ($donem == 3)
    //                 {
    //                     $periyod_hesap = 12/$arr['periyod']*$i;
    //                     if ($periyod_hesap <= 6) // Yılsonu
    //                     {
    //                         continue;
    //                     }
    //                 }
                    
    //                 $data[$sayac]['yil'] = $yil;
    //                 $data[$sayac]['donem'] = $donem;
    //                 $data[$sayac]['pernr'] = $rilen_pernr;
    //                 $data[$sayac]['degerlendiren_ack'] = $degerlendiren_ack;
    //                 $data[$sayac]['degerlendiren_pernr'] = $degerlendiren_pernr;
    //                 $data[$sayac]['form_id'] = $form_id;
    //                 $data[$sayac]['ft_id'] = $ft_id;
    //                 $data[$sayac]['g_id'] = $arr['g_id'];
    //                 $data[$sayac]['periyod'] = $i;
    //                 $data[$sayac]['duzey'] = $duzey[$arr['g_id']][$i]['duzey'];
                    
    //                 if ($data[$sayac]['duzey'] > 0)
    //                 {
    //                     $periyod_sayisi[$arr['g_id']]++;
    //                 }
                    
    //                 $data[$sayac]['guncelleyen'] = $this->session->userdata('pernr');
    //                 $data[$sayac]['g_time'] = $time;
                    
    //                 if ($donem == 2)
    //                 {
    //                     $data[$sayac]['puan'] = 0;
    //                 }
    //                 else if ($donem == 3)
    //                 {
    //                     $data[$sayac]['puan'] = $arr['agirlik']/1/4*$data[$sayac]['duzey'];
    //                 }
                    
                    
    //                 $sayac++;
    //             }
    //         }
            
    //     //            foreach ($data as $key=>$arr)
    //     //            {
    //     //                if ($donem == 2)
    //     //                {
    //     //                    $data[$key]['puan'] = $data[$key]['puan']*$g_periyod_sayisi[$arr['g_id']]/$periyod_sayisi[$arr['g_id']]/2;
    //     //                }
    //     //                else
    //     //                {
    //     //                    if ($g_periyod_sayisi[$arr['g_id']] == 1)
    //     //                    {
    //     //                        $data[$key]['puan'] = $data[$key]['puan']*$g_periyod_sayisi[$arr['g_id']]/$periyod_sayisi[$arr['g_id']];
    //     //                    }
    //     //                    else 
    //     //                    {
    //     //                        // Yarıyılda form varsa ikiye böl
    //     //                        if ($yy_form_var)
    //     //                        {
    //     //                            $data[$key]['puan'] = $data[$key]['puan']*$g_periyod_sayisi[$arr['g_id']]/$periyod_sayisi[$arr['g_id']]/2;
    //     //                        }
    //     //                        else
    //     //                        {
    //     //                            $data[$key]['puan'] = $data[$key]['puan']*$g_periyod_sayisi[$arr['g_id']]/$periyod_sayisi[$arr['g_id']]; 
    //     //                        }
    //     //                    }
    //     //                }
    //     //            }
            
            
    //         if (is_array($data))
    //         {

                
    //             unset($kosul);
    //             $kosul['yil'] = $yil;
    //             $kosul['donem'] = $donem;
    //             $kosul['pernr'] = $rilen_pernr;
    //             $kosul['degerlendiren_ack'] = $degerlendiren_ack;
    //             $kosul['form_id'] = $form_id;
    //             $r = $this->genel_model->veri_kaydet_cs("degerlendirmeler", $data, $kosul);

    //             if (!$r)
    //             {
    //                 $alert_message = alert_message('Teknik bir aksaklık oluştu daha sonra tekrar deneyiniz.');
    //                 $this->session->set_flashdata('message', $alert_message);
    //                 redirect('/d_donemleri/form_goruntule/'.$form_id.'/'.$rilen_pernr.'/'.$yil.'/'.$donem.'/'.$ft_id);
    //                 exit();
    //             }

    //             $alert_message = alert_message('Kayıt başarılı.', 'success');
    //             $this->session->set_flashdata('message', $alert_message);
    //             redirect('/d_donemleri/form_goruntule/'.$form_id.'/'.$rilen_pernr.'/'.$yil.'/'.$donem.'/'.$ft_id);
    //             exit();
    //         }
    //         else
    //         {
    //             $alert_message = alert_message('Kayıt başarılı.', 'success');
    //             $this->session->set_flashdata('message', $alert_message);
    //             redirect('/d_donemleri/form_goruntule/'.$form_id.'/'.$rilen_pernr.'/'.$yil.'/'.$donem.'/'.$ft_id);
    //             exit();
    //         }
    //     }
    //     else if ($form['ft_id'] == 4) // Görüşme Formu
    //     {
    //         $aciklama = $this->input->post('aciklama');
            
    //         // Form detayınnı bul
    //         $form_detay = $this->d_donemleri_model->formlar_detay($form_id);
            
    //         $duzey = $this->input->post('gd');
    //         $time = date("Y-m-d H:i:s");
    //         $sayac = 0;
    //         foreach ($form_detay as $key=>$arr)
    //         {
    //             for ($i = 1; $i <= $arr['periyod']; $i++)
    //             {
    //                 $kayit_kontrol = true;  
    //                 if ($donem == 2)
    //                 {
    //                     $periyod_hesap = 12/$arr['periyod']*$i;
    //                     if ($periyod_hesap > 6) // Yarı yıl
    //                     {
    //                         continue;
    //                     }
    //                 }
    //                 else if ($donem == 3)
    //                 {
    //                     $periyod_hesap = 12/$arr['periyod']*$i;
    //                     if ($periyod_hesap <= 6) // Yılsonu
    //                     {
    //                         continue;
    //                     }
    //                 }
                    
    //                 $data[$sayac]['yil'] = $yil;
    //                 $data[$sayac]['donem'] = $donem;
    //                 $data[$sayac]['pernr'] = $rilen_pernr;
    //                 $data[$sayac]['degerlendiren_ack'] = $degerlendiren_ack;
    //                 $data[$sayac]['degerlendiren_pernr'] = $degerlendiren_pernr;
    //                 $data[$sayac]['form_id'] = $form_id;
    //                 $data[$sayac]['ft_id'] = $ft_id;
    //                 $data[$sayac]['g_id'] = $arr['g_id'];
    //                 $data[$sayac]['periyod'] = $i;
    //                 $data[$sayac]['aciklama'] = $aciklama[$arr['g_id']][$i];
    //                 $data[$sayac]['guncelleyen'] = $this->session->userdata('pernr');
    //                 $data[$sayac]['g_time'] = $time;
                    
    //                 $sayac++;
    //             }
    //         }
            
    //         if (is_array($data))
    //         {
    //             unset($kosul);
    //             $kosul['yil'] = $yil;
    //             $kosul['donem'] = $donem;
    //             $kosul['pernr'] = $rilen_pernr;
    //             $kosul['degerlendiren_ack'] = $degerlendiren_ack;
    //             $kosul['form_id'] = $form_id;
    //             $r = $this->genel_model->veri_kaydet_cs("degerlendirmeler", $data, $kosul);

    //             if (!$r)
    //             {
    //                 $alert_message = alert_message('Teknik bir aksaklık oluştu daha sonra tekrar deneyiniz.');
    //                 $this->session->set_flashdata('message', $alert_message);
    //                 redirect('/d_donemleri/form_goruntule/'.$form_id.'/'.$rilen_pernr.'/'.$yil.'/'.$donem.'/'.$ft_id);
    //                 exit();
    //             }

    //             $alert_message = alert_message('Kayıt başarılı.', 'success');
    //             $this->session->set_flashdata('message', $alert_message);
    //             redirect('/d_donemleri/form_goruntule/'.$form_id.'/'.$rilen_pernr.'/'.$yil.'/'.$donem.'/'.$ft_id);
    //             exit();
    //         }
            
    //         $alert_message = alert_message('Kayıt başarılı.', 'success');
    //         $this->session->set_flashdata('message', $alert_message);
    //         redirect('/d_donemleri/form_goruntule/'.$form_id.'/'.$rilen_pernr.'/'.$yil.'/'.$donem.'/'.$ft_id);
    //         exit();
    //     }
    //     else if ($form['ft_id'] == 5) // Yönlendirme Formu
    //     {
    //         $hp = $this->input->post('hp');
            
    //         $time = date("Y-m-d H:i:s");
    //         $sayac = 0;
            
    //         $data[$sayac]['yil'] = $yil;
    //         $data[$sayac]['donem'] = $donem;
    //         $data[$sayac]['pernr'] = $rilen_pernr;
    //         $data[$sayac]['degerlendiren_ack'] = $degerlendiren_ack;
    //         $data[$sayac]['degerlendiren_pernr'] = $degerlendiren_pernr;
    //         $data[$sayac]['form_id'] = $form_id;
    //         $data[$sayac]['ft_id'] = $ft_id;
    //         $data[$sayac]['hp'] = $hp;
    //         if ($hp == 1)
    //         {
    //             $data[$sayac]['hp_ack'] = "Üst Göreve Atanabilir";
    //         }
    //         else if ($hp == 2)
    //         {
    //             $data[$sayac]['hp_ack'] = "Eş Düzey Göreve Geçebilir";
    //         }
    //         else if ($hp == 3)
    //         {
    //             $data[$sayac]['hp_ack'] = "İşten Ayrılmasında Sakınca Yoktur";
    //         }
    //         else if ($hp == 4)
    //         {
    //             $data[$sayac]['hp_ack'] = "Mevcut Göreve Devam Edebilir";
    //         }
    //         $data[$sayac]['guncelleyen'] = $this->session->userdata('pernr');
    //         $data[$sayac]['g_time'] = $time;
            
    //         if (is_array($data))
    //         {
    //             unset($kosul);
    //             $kosul['yil'] = $yil;
    //             $kosul['donem'] = $donem;
    //             $kosul['pernr'] = $rilen_pernr;
    //             $kosul['degerlendiren_ack'] = $degerlendiren_ack;
    //             $kosul['form_id'] = $form_id;
    //             $r = $this->genel_model->veri_kaydet_cs("degerlendirmeler", $data, $kosul);

    //             if (!$r)
    //             {
    //                 $alert_message = alert_message('Teknik bir aksaklık oluştu daha sonra tekrar deneyiniz.');
    //                 $this->session->set_flashdata('message', $alert_message);
    //                 redirect('/d_donemleri/form_goruntule/'.$form_id.'/'.$rilen_pernr.'/'.$yil.'/'.$donem.'/'.$ft_id);
    //                 exit();
    //             }

    //             $alert_message = alert_message('Kayıt başarılı.', 'success');
    //             $this->session->set_flashdata('message', $alert_message);
    //             redirect('/d_donemleri/form_goruntule/'.$form_id.'/'.$rilen_pernr.'/'.$yil.'/'.$donem.'/'.$ft_id);
    //             exit();
    //         }
            
    //         $alert_message = alert_message('Kayıt başarılı.', 'success');
    //         $this->session->set_flashdata('message', $alert_message);
    //         redirect('/d_donemleri/form_goruntule/'.$form_id.'/'.$rilen_pernr.'/'.$yil.'/'.$donem.'/'.$ft_id);
    //         exit();
    //     }
    // }
    
    public function onay_islemleri()
    {
        $yil = $this->input->post('yil');
        $donem = $this->input->post('donem');
        $ft_id = $this->input->post('ft_id');
        $onay_durum = $this->input->post('onay_durum');
        $onaylayan = $this->my_encrypt_url->decode($this->input->post('onaylayan'));
        $degerlendiren = $this->my_encrypt_url->decode($this->input->post('degerlendiren'));

        
        
        if (empty_f($yil) || empty_f($donem) || empty_f($ft_id) || empty_f($onay_durum) || empty_f($onaylayan) || empty_f($degerlendiren))
        {
            if($degerlendiren == 100000 && $ft_id == 99)   // Erol Bey Onaya Gönderemiyordu üst yöneticisi olmadığı için bu sebeden ötürü kendisine üst yönetici olarak tanımladım
            {
                $onaylayan = $degerlendiren;
                $onay_durum = 1;
            }
            else
            {
                $alert_message = alert_message('Hata.');
                $this->session->set_flashdata('message', $alert_message);
                redirect('./home/');
                exit();
            }
            
        }

        
        
        if ($onay_durum == 4)
        {
            if ($degerlendiren != $this->session->userdata('pernr'))
            {
                $alert_message = alert_message('Hata.'.__LINE__.__FILE__);
                $this->session->set_flashdata('message', $alert_message);
                redirect('./home/');
                exit();
            }
        }
        else if ($onay_durum == 1 || $onay_durum == 2)
        {
            if ($onaylayan != $this->session->userdata('pernr'))
            {
                $alert_message = alert_message('Hata.');
                $this->session->set_flashdata('message', $alert_message);
                redirect('./home/');
                exit();
            }
        }

       

        unset($kosul);
        $select = '*';
        $kosul['yil'] = $yil;
        $kosul['donem'] = $donem;
        $donem_listesi = $this->genel_model->veri_cek_ts("donem_listesi", $select, $kosul);
        
        if (!is_array($donem_listesi))
        {
            $alert_message = alert_message('Hata.');
            $this->session->set_flashdata('message', $alert_message);
            redirect('./home/');
            exit();
        }


        
        
        $tarih = date('Y-m-d');
        
        if ($tarih < $donem_listesi['bat'])
        {
            $alert_message = alert_message('Hata.');
            $this->session->set_flashdata('message', $alert_message);
            redirect('./home/');
            exit();
        }
        
        if ($tarih > $donem_listesi['bit'] || $donem_listesi['donem_status'] == 0)
        {
            $alert_message = alert_message('Değerlendirme dönemi kapandığı için işlem yapamazsınız.');
            $this->session->set_flashdata('message', $alert_message);
            redirect('./home/');
            exit();
        }

        
        
        unset($kosul);
        $select = '*';
        $kosul['id'] = $onay_durum;
        $tablo1 = $this->genel_model->veri_cek_ts("onay_durum_av", $select, $kosul);
        
        
        $data['onay_text'] = $tablo1['text'];
        
        $data['yil'] = $yil;
        $data['donem'] = $donem;
        $data['ft_id'] = $ft_id;
        $data['onay_durum'] = $onay_durum;
        $data['onaylayan'] = $onaylayan;
        $data['degerlendiren'] = $degerlendiren;
        
        $data['guncelleyen'] = $this->session->userdata("pernr");
        $data['g_time'] = date('Y-m-d H:i:s');
        unset($kosul);
        $kosul['yil'] = $yil;
        $kosul['donem'] = $donem;
        $kosul['degerlendiren'] = $degerlendiren;
        $kosul['onaylayan'] = $onaylayan;
        $kosul['ft_id'] = $ft_id;

        
        
        $r = $this->genel_model->veri_kaydet("onaylar", $data, $kosul);
        
        if (!$r)
        {
            $alert_message = alert_message('Teknik bir aksaklık oluştu daha sonra tekrar deneyiniz.');
            $this->session->set_flashdata('message', $alert_message);
            redirect('/d_donemleri/takvim/'.$yil.'/'.$donem);
            exit();
        }


        
        // DIŞ SİSTEM İLE BAĞLANTI OLACAĞIN DÖNEMDE BU KODUN TEKRAR AKTİF HALE GETİRİLMESİ GEREKLİDİR. 20220025
            // $this->load->model("veri_al_model");
            // $this->veri_al_model->kopyala2($yil, $donem, $degerlendiren);
            
            $alert_message = alert_message('Kayıt başarılı.', 'success');
            $this->session->set_flashdata('message', $alert_message);

        /////////////////



        if ($ft_id == 99)
        {
            redirect('/d_donemleri/kademelendirme/'.$yil.'/'.$donem);
        }
        else 
        {
            redirect('/d_donemleri/takvim/'.$yil.'/'.$donem);
        }
        exit();
        
    }
    
    public function kademelendirme($yil, $donem)
    {
        if (empty_f($yil) || empty_f($donem))
        {
            $alert_message = alert_message('Yıl ve dönem zorunludur.');
            $this->session->set_flashdata('message', $alert_message);
            redirect('./home/');
            exit();
        }
        
        $data['yil'] = $yil;
        $data['donem'] = $donem;
        
        unset($kosul);
        $select = '*';
        $kosul['yil'] = $yil;
        $kosul['donem'] = $donem;
        $tablo1 = $this->genel_model->veri_cek_ts("donem_listesi", $select, $kosul);
        
        if ($tablo1['kademelendirme'] == 2) // Kademelendirme var mı kontrol et..
        {
            $alert_message = alert_message('Hata.');
            $this->session->set_flashdata('message', $alert_message);
            redirect('./home/');
            exit();
        }
        
        $data['donem_ack'] = $tablo1['donem_ack'];
        
        $tarih = date('Y-m-d');
        
        if ($tarih < $tablo1['bat'])
        {
            $alert_message = alert_message('Hata.');
            $this->session->set_flashdata('message', $alert_message);
            redirect('./home/');
            exit();
        }
        
        // Kendi bilgileri
        unset($kosul);
        $select = '*';
        $kosul['yil'] = $yil;
        $kosul['donem'] = $donem;
        $kosul['pernr'] = $this->session->userdata('pernr');
        $tablo1 = $this->genel_model->veri_cek_cs("organizasyon_d", $select, $kosul, $order_by);
        if (is_array($tablo1))
        {
            foreach ($tablo1 as $key=>$arr)
            {
                $data['ust_ad_soyad'][$arr['pernr']] = $arr['ust_ad_soyad'];
                $data['ust_pernr'][$arr['pernr']] = $arr['ust_pernr'];
                $data['ad_soyad'][$arr['pernr']] = $arr['ad_soyad'];
                $data['pozisyon'][$arr['pernr']] = $arr['poz_text'];
            }
        }
        
        // Astları..
        unset($kosul);
        $select = '*';
        $kosul['yil'] = $yil;
        $kosul['donem'] = $donem;
        $kosul['ust_pernr'] = $this->session->userdata('pernr');
        $order_by['ad_soyad'] = 'asc';
        $tablo2 = $this->genel_model->veri_cek_cs("organizasyon_d", $select, $kosul, $order_by);
        
        if (is_array($tablo2))
        {
            foreach ($tablo2 as $key=>$arr)
            {
                $kisi_listesi[] = $arr['pernr'];
                $data['ust_ad_soyad'][$arr['pernr']] = $arr['ust_ad_soyad'];
                $data['ust_pernr'][$arr['pernr']] = $arr['ust_pernr'];
                $data['ad_soyad'][$arr['pernr']] = $arr['ad_soyad'];
                $data['pozisyon'][$arr['pernr']] = $arr['poz_text'];
            }
        }
        
        // Kademelendirmesi gereken havuzları listele..
        unset($kosul);
        $select = '*';
        $kosul['yil'] = $yil;
        $kosul['donem'] = $donem;
        $kosul['k_pernr'] = $this->session->userdata('pernr');
        $tablo2 = $this->genel_model->veri_cek_cs("kademelendirme_atamalari", $select, $kosul, null, null, $group_by);
        
        if (is_array($tablo2))
        {
            foreach ($tablo2 as $key=>$arr)
            {
                if (!in_array($arr['havuz_id'], $data['havuz_id']['idler']))
                {
                    $data['havuz_id']['idler'][] = $arr['havuz_id'];
                    $data['havuz_id']['kademelendiren'][$arr['havuz_id']] = $arr['k_pernr'];
                    $data['havuz_id']['kademelendiren_text'][$arr['havuz_id']] = $data['ad_soyad'][$arr['k_pernr']];
                    
                    // Kademelendirme start kontrol
                    unset($kosul);
                    $select = "*";
                    $kosul['havuz_id'] = $arr['havuz_id'];
                    $kosul['yil'] = $yil;
                    $kosul['donem'] = $donem;
                    $ks_kontrol_v = $this->genel_model->veri_cek_cs("kademelendirme_sonuclari", $select, $kosul);
                    if (is_array ($ks_kontrol_v))
                    {
                        $data['ks_kontrol'][$arr['havuz_id']] = 1;
                    }
                    else
                    {
                        $data['ks_kontrol'][$arr['havuz_id']] = 2;
                    }
                    
                    // Kademelendirme start kontrol2
                    unset($kosul);
                    $select = "*";
                    $kosul['havuz_id'] = $arr['havuz_id'];
                    $kosul['yil'] = $yil;
                    $kosul['donem'] = $donem;
                    $kosul['sk'] = 0;
                    $ks_kontrol_v = $this->genel_model->veri_cek_cs("kademelendirme_sonuclari", $select, $kosul);
                    if (is_array ($ks_kontrol_v))
                    {
                        $data['ks_kontrol'][$arr['havuz_id']] = 2;
                    }
                    
                    // Kademelendirme onayı ne durumda
                    unset($kosul);
                    $select = '*';
                    $kosul['yil'] = $yil;
                    $kosul['donem'] = $donem;
                    $kosul['degerlendiren'] = $this->session->userdata('pernr');
                    $kosul['ft_id'] = 99;
                    $onaylar = $this->genel_model->veri_cek_ts("onaylar", $select, $kosul);
                    if (is_array($onaylar))
                    {
                        $data['od_ast'] = $onaylar;
                    }
                    else
                    {
                        // Onaya gönderilmesi bekleniyor..
                        $data['od_ast']['onay_durum'] = 3;
                        $data['od_ast']['onay_text'] = "Onaya Gönderilmesi Bekleniyor";
                    }
                }
            }
        }
        
        if (is_array($data['havuz_id']['idler']))
        {
            // Kademelendirme havuzları master data..
            unset($kosul);
            $select = '*';
            $kosul['havuz_id'] = $data['havuz_id']['idler'];
            $tablo3 = $this->genel_model->veri_cek_cs("kademelendirme_havuzlari", $select, $kosul);
            
            if (is_array($tablo3))
            {
                foreach ($tablo3 as $key=>$arr)
                {
                    $data['havuz_md'][$arr['havuz_id']] = $arr['havuz_text'];
                }
            }
        }
        
        // Onaylaması gereken havuzları listele..
        unset($kosul);
        $select = '*';
        $kosul['yil'] = $yil;
        $kosul['donem'] = $donem;
        $kosul['k_pernr'] = $kisi_listesi;
        $group_by = 'k_pernr, havuz_id';
        $tablo4 = $this->genel_model->veri_cek_cs("kademelendirme_atamalari", $select, $kosul, null, null, $group_by);
        
        
        
        if (is_array($tablo4))
        {
            foreach ($tablo4 as $key=>$arr)
            {
                if (!in_array($arr['havuz_id2'], $data['havuz_id2']['idler']))
                {
                    $data['havuz_id2']['idler'][] = $arr['havuz_id'];
                    $data['havuz_id2']['kademelendiren'][$arr['havuz_id']] = $arr['k_pernr'];
                    $data['havuz_id2']['kademelendiren_text'][$arr['havuz_id']] = $data['ad_soyad'][$arr['k_pernr']];
                    
                    // Kademelendirme start kontrol
                    unset($kosul);
                    $select = "*";
                    $kosul['havuz_id'] = $arr['havuz_id'];
                    $kosul['yil'] = $yil;
                    $kosul['donem'] = $donem;
                    $ks_kontrol_v = $this->genel_model->veri_cek_cs("kademelendirme_sonuclari", $select, $kosul);
                    if (is_array ($ks_kontrol_v))
                    {
                        $data['ks_kontrol'][$arr['havuz_id']] = 1;
                    }
                    else
                    {
                        $data['ks_kontrol']['astlar'] = 2;
                        $data['ks_kontrol'][$arr['havuz_id']] = 2;
                    }
                    
                    // Kademelendirme start kontrol2
                    unset($kosul);
                    $select = "*";
                    $kosul['havuz_id'] = $arr['havuz_id'];
                    $kosul['yil'] = $yil;
                    $kosul['donem'] = $donem;
                    $kosul['sk'] = 0;
                    $ks_kontrol_v = $this->genel_model->veri_cek_cs("kademelendirme_sonuclari", $select, $kosul);
                    if (is_array ($ks_kontrol_v))
                    {
                        $data['ks_kontrol']['astlar'] = 2;
                        $data['ks_kontrol'][$arr['havuz_id']] = 2;
                    }
                    
                    // Kademelendirme onayı ne durumda
                    unset($kosul);
                    $select = '*';
                    $kosul['yil'] = $yil;
                    $kosul['donem'] = $donem;
                    $kosul['degerlendiren'] = $arr['k_pernr'];
                    $kosul['ft_id'] = 99;
                    $onaylar = $this->genel_model->veri_cek_ts("onaylar", $select, $kosul);
                    if (is_array($onaylar))
                    {
                        $data['od_ast2'][$arr['k_pernr']] = $onaylar;
                    }
                    else
                    {
                        // Onaya gönderilmesi bekleniyor..
                        $data['od_ast2'][$arr['k_pernr']]['onay_durum'] = 3;
                        $data['od_ast2'][$arr['k_pernr']]['onay_text'] = "Onaya Gönderilmesi Bekleniyor"; 
                    }
                }
            }
        }
        
        if (is_array($data['havuz_id2']['idler']))
        {
            // Kademelendirme havuzları master data..
            unset($kosul);
            $select = '*';
            $kosul['havuz_id'] = $data['havuz_id2']['idler'];
            $tablo5 = $this->genel_model->veri_cek_cs("kademelendirme_havuzlari", $select, $kosul);
            
            if (is_array($tablo5))
            {
                foreach ($tablo5 as $key=>$arr)
                {
                    $data['havuz_md'][$arr['havuz_id']] = $arr['havuz_text'];
                }
            }
            
        }
        
        
        
        $this->template->goster('kademelendirme', $data);
    }
    
    public function kademelendirme_detay($yil, $donem, $havuz_id)
    {

        if (empty_f($yil) || empty_f($donem) || empty_f($havuz_id))
        {
            $alert_message = alert_message('Hata.');
            $this->session->set_flashdata('message', $alert_message);
            redirect('./home/');
            exit();
        }
        
        $data['yil'] = $yil;
        $data['donem'] = $donem;
        $data['havuz_id'] = $havuz_id;

        unset($kosul);
        $select = '*';
        $kosul['yil'] = $yil;
        $kosul['donem'] = $donem;
        $donem_listesi = $this->genel_model->veri_cek_ts("donem_listesi", $select, $kosul);
        
        if (!is_array($donem_listesi))
        {
            $alert_message = alert_message('Hata.');
            $this->session->set_flashdata('message', $alert_message);
            redirect('./home/');
            exit();
        }
        
        $data['donem_ack'] = $donem_listesi['donem_ack'];
        
        $tarih = date('Y-m-d');
        
        if ($tarih < $donem_listesi['bat'])
        {
            $alert_message = alert_message('Hata.');
            $this->session->set_flashdata('message', $alert_message);
            redirect('./home/');
            exit();
        }
        
        if ($tarih > $donem_listesi['bit'])
        {
            $data['degisiklik'] = false;
        }
        else
        {
            $data['degisiklik'] = true;
        }
        
        // Havuz bilgilerini çek
        unset($kosul);
        $select = '*';
        $kosul['havuz_id'] = $havuz_id;
        $havuz_bilgileri = $this->genel_model->veri_cek_ts("kademelendirme_havuzlari", $select, $kosul);
        if (!is_array($havuz_bilgileri))
        {
            $alert_message = alert_message('Hata.');
            $this->session->set_flashdata('message', $alert_message);
            redirect('./home/');
            exit();
        }
        
        $data['havuz_adi'] = $havuz_bilgileri['havuz_text'];
        
        unset($kosul);
        $select = '*';
        $kosul['yil'] = $yil;
        $kosul['donem'] = $donem;
        $kosul['havuz_id'] = $havuz_id;
        $havuzdakiler = $this->genel_model->veri_cek_cs("kademelendirme_atamalari", $select, $kosul);
        if (!is_array($havuzdakiler))
        {
            $alert_message = alert_message('Hata.');
            $this->session->set_flashdata('message', $alert_message);
            redirect('./home/');
            exit();
        }
        
        $k_pernr = $havuzdakiler['0']['k_pernr'];
        
        unset($kosul);
        $select = 'count(*) as a';
        $kosul['yil'] = $yil;
        $kosul['donem'] = $donem;
        $kosul['havuz_id'] = $havuz_id;
        $kisi_sayisi = $this->genel_model->veri_cek_ts("kademelendirme_atamalari", $select, $kosul);

        $data['kisi_sayisi'] = $kisi_sayisi['a'];



        ////////////////////////////////////////////////////////////////////////////////

        // milestone kontrolu yapılacak
       
       
        $dummy_person_count = $data['kisi_sayisi'];
        $gradationData = $this->gradation_key->getGradationData($yil,$donem,$dummy_person_count,$havuz_id);
        $data["gradationData"] = $gradationData;
        $data["gradationDataForMerged"] = $this->gradation_key->getGradationDatForMerged($yil,$donem,$dummy_person_count,$havuz_id);


        // echo "<pre>";
        // print_r($data["gradationData"]["data"]);
        // exit();


        
     
     
        //$data["gradationData_keys"] = array_keys($gradationData["data"]);
        //$data["gradationData_values"] = array_values($gradationData["data"]);


        ////////////////////////////////////////////////////////////////////////////////
       

        // if ($data['kisi_sayisi'] > 10)
        // {
        //     $data['kisi_sayisi_ba'] = round($data['kisi_sayisi']*0.15);
        //     $data['kisi_sayisi_b'] = $data['kisi_sayisi']-round($data['kisi_sayisi']*0.15)-round($data['kisi_sayisi']*0.25)-round($data['kisi_sayisi']*0.1);
        //     $data['kisi_sayisi_bu']  = round($data['kisi_sayisi']*0.25);
        //     $data['kisi_sayisi_bcu']  = round($data['kisi_sayisi']*0.1);
        // }
        // else if ($data['kisi_sayisi'] == 10)
        // {
        //     $data['kisi_sayisi_ba'] = 1;
        //     $data['kisi_sayisi_b'] = 5;
        //     $data['kisi_sayisi_bu']  = 3;
        //     $data['kisi_sayisi_bcu']  = 1;
        // }
        // else if ($data['kisi_sayisi'] == 9)
        // {
        //     $data['kisi_sayisi_ba'] = 1;
        //     $data['kisi_sayisi_b'] = 5;
        //     $data['kisi_sayisi_bu']  = 2;
        //     $data['kisi_sayisi_bcu']  = 1;
        // }
        // else if ($data['kisi_sayisi'] == 8)
        // {
        //     $data['kisi_sayisi_ba'] = 1;
        //     $data['kisi_sayisi_b'] = 4;
        //     $data['kisi_sayisi_bu']  = 2;
        //     $data['kisi_sayisi_bcu']  = 1;
        // }
        // else if ($data['kisi_sayisi'] == 7)
        // {
        //     $data['kisi_sayisi_ba'] = 1;
        //     $data['kisi_sayisi_b'] = 4;
        //     $data['kisi_sayisi_bu']  = 1;
        //     $data['kisi_sayisi_bcu']  = 1;
        // }
        // else if ($data['kisi_sayisi'] == 6)
        // {
        //     $data['kisi_sayisi_ba'] = 1;
        //     $data['kisi_sayisi_b'] = 3;
        //     $data['kisi_sayisi_bu']  = 1;
        //     $data['kisi_sayisi_bcu']  = 1;
        // }
        // else if ($data['kisi_sayisi'] == 5)
        // {
        //     $data['kisi_sayisi_ba'] = 1;
        //     $data['kisi_sayisi_b'] = 2;
        //     $data['kisi_sayisi_bu']  = 1;
        //     $data['kisi_sayisi_bcu']  = 1;
        // }
        // else if ($data['kisi_sayisi'] == 4)
        // {
        //     $data['kisi_sayisi_b'] = 3;
        //     $data['kisi_sayisi_bu']  = 1;
        // }
        // else if ($data['kisi_sayisi'] == 3)
        // {
        //     $data['kisi_sayisi_b'] = 2;
        //     $data['kisi_sayisi_bu']  = 1;
        // }
        // else if ($data['kisi_sayisi'] == 2 || $data['kisi_sayisi'] == 1)
        // {

        // }
        
        // Kademelendiren Kişinin kendisi veya yöneticisi değilse hata ver..
        unset($kosul);
        $select = '*';
        $kosul['yil'] = $yil;
        $kosul['donem'] = $donem;
        $kosul['pernr'] = $k_pernr;
        $yon1 = $this->genel_model->veri_cek_ts("organizasyon_d", $select, $kosul);
        /*
        var_dump($yon1);
        exit();
        */
        if (!is_array($yon1))
        {
            $alert_message = alert_message('Hata.');
            $this->session->set_flashdata('message', $alert_message);
            redirect('./home/');
            exit();
        }
        
        if ($k_pernr != $this->session->userdata['pernr'] 
        &&  $yon1['ust_pernr'] != $this->session->userdata['pernr']  && $this->session->userdata('pernr') != 100003)
        {
            $alert_message = alert_message('Bu havuzu görüntüleme yetkiniz yok.');
            $this->session->set_flashdata('message', $alert_message);
            redirect('/d_donemleri/takvim/'.$yil.'/'.$donem);
            exit();
        }
        
        if ($k_pernr == $this->session->userdata['pernr'])
        {
            $data['who'] = "kademelendiren";
        }
        else
        {
            $data['who'] = "onaylayan";
        }
        
        // Havuzdaki kişileri çek, değerlendiren kim bak eğer değerlendiren ve kademlendiren aynı ise onay kontrolü
        // değerlendiren ve kademelendiren aynı değilse onay durumuna bak kendisi ise
        // Henüz onaylar verilmemişse kademelendirmeyi başlatma ancak mevcut durumu görüntület
        // Astlarımın kademelendirdiklerini getirme..
        
        foreach ($havuzdakiler as $key=>$arr)
        {
            $kisi_listesi[] = $arr['pernr'];
        }
        
        unset($kosul);
        $select = '*';
        $kosul['yil'] = $yil;
        $kosul['donem'] = $donem;
        $kosul['pernr'] = $kisi_listesi;
        $kademelendirilen_kisi_bilgileri = $this->genel_model->veri_cek_cs("organizasyon_d", $select, $kosul);
        
        foreach ($kademelendirilen_kisi_bilgileri as $key=>$arr)
        {
            $data['ad_soyad'][$arr['pernr']] = $arr['ad_soyad'];
            $data['pozisyon'][$arr['pernr']] = $arr['poz_text'];
            $data['ust_ad_soyad'][$arr['pernr']] = $arr['ust_ad_soyad'];
            $data['ust_pernr'][$arr['pernr']] = $arr['ust_pernr'];
            if ($arr['ust_pernr'] == $k_pernr) // kademelendiren ve onaylayan kendisi
            {
                $kendi_onay_durumu_kontrol[$arr['ust_pernr']] = $arr['ust_pernr'];
            }
            else
            {
                $farkli_onay_durumu_kontrol[$arr['ust_pernr']] = $arr['ust_pernr'];
            }
        }
        
        // Havuzdaki kişilerin hedef formu var mı ?
        unset($kosul);
        $select = '*';
        $kosul['yil'] = $yil;
        $kosul['donem'] = $donem;
        $kosul['ft_id'] = 1;
        $kosul['pernr'] = $kisi_listesi;
        $form_atamalari = $this->genel_model->veri_cek_cs("form_atamalari", $select, $kosul);

        if (is_array($form_atamalari))
        {
            $hedef_formu_var = true;
            foreach ($form_atamalari as $key=>$arr)
            {
                $hedef_formu_olan[] = $data['ust_pernr'][$arr['pernr']];
            }
        }
        else
        {
            $hedef_formu_var = false;
        }
        
        $data['hedef_formu_var'] = $hedef_formu_var;
        
        
        
        
        $data['start'] = true;
        foreach ($farkli_onay_durumu_kontrol as $key=>$val)
        {
            if ($hedef_formu_var)
            {
                // Onay durumunu çek...
                if (in_array($val, $hedef_formu_olan))
                {
                    unset($kosul);
                    $select = '*';
                    $kosul['yil'] = $yil;
                    $kosul['donem'] = $donem;
                    $kosul['ft_id'] = 1;
                    $kosul['degerlendiren'] = $val;
                    $kosul['onaylayan!='] = $val;
                    $od_ast = $this->genel_model->veri_cek_ts("onaylar", $select, $kosul);
                    if (is_array($od_ast))
                    {
                        if ($od_ast['onay_durum'] != 1)
                        {
                            $data['start'] = false;
                        }
                    }
                    else
                    {
                        $data['start'] = false;
                    }
                }
            }
            
            // Onay durumunu çek..
            unset($kosul);
            $select = '*';
            $kosul['yil'] = $yil;
            $kosul['donem'] = $donem;
            $kosul['ft_id'] = 2;
            $kosul['degerlendiren'] = $val;
            $kosul['onaylayan!='] = $val;
            $od_ast = $this->genel_model->veri_cek_ts("onaylar", $select, $kosul);
            if (is_array($od_ast))
            {
                if ($od_ast['onay_durum'] != 1)
                {
                    $data['start'] = false;
                }
            }
            else 
            {
                $data['start'] = false;
            }
        }

        foreach ($kendi_onay_durumu_kontrol as $key=>$val)
        {
            if ($hedef_formu_var)
            {
                if (in_array($val, $hedef_formu_olan))
                {
                    // Onay durumunu çek..
                    unset($kosul);
                    $select = '*';
                    $kosul['yil'] = $yil;
                    $kosul['donem'] = $donem;
                    $kosul['ft_id'] = 1;
                    $kosul['degerlendiren'] = $k_pernr;
                    $kosul['onaylayan'] = $k_pernr;
                    $od_ast = $this->genel_model->veri_cek_ts("onaylar", $select, $kosul);
                    if (is_array($od_ast))
                    {
                        if ($od_ast['onay_durum'] != 1)
                        {
                            $data['start'] = false;
                        }
                    }
                    else
                    {
                        $data['start'] = false;
                    }
                }
            }
            
            // Onay durumunu çek..
            unset($kosul);
            $select = '*';
            $kosul['yil'] = $yil;
            $kosul['donem'] = $donem;
            $kosul['ft_id'] = 2;
            $kosul['degerlendiren'] = $k_pernr;
            $kosul['onaylayan='] = $k_pernr;
            $od_ast = $this->genel_model->veri_cek_ts("onaylar", $select, $kosul);
            if (is_array($od_ast))
            {
                if ($od_ast['onay_durum'] != 1)
                {
                    $data['start'] = false;
                }
            }
            else
            {
                $data['start'] = false;
            }
        }
        
        // Kaydedilmiş kademelendirme var mı ?
        unset($kosul);
        unset($order_by);
        $select = '*';
        $kosul['yil'] = $yil;
        $kosul['donem'] = $donem;
        $kosul['havuz_id'] = $havuz_id;
        $order_by['sk'] = 'desc';
        $order_by['vk'] = 'desc';
        $order_by['tp'] = 'desc';
        $order_by['hp'] = 'desc';
        $order_by['yp'] = 'desc';
        $data['siralama'] = $this->genel_model->veri_cek_cs("kademelendirme_sonuclari", $select, $kosul, $order_by);

       

        $data["hpd"] = array_reverse($this->gradation_key->pool_levels($yil,$havuz_id,$donem));

        // var_dump(  $data["hpd"]);
        // exit();

        // var_dump($data["hpd"]);
        // exit();


        // foreach ($data['siralama'] as $key=>$arr)
        // {
        //     if ($arr['sk'] == 1)
        //     {
        //         $data['md']['kisi_sayisi_ba']++;
        //     }
        //     else if ($arr['sk'] == 2)
        //     {
        //         $data['md']['kisi_sayisi_b']++;
        //     }
        //     else if ($arr['sk'] == 3)
        //     {
        //         $data['md']['kisi_sayisi_bu']++;
        //     }
        //     else if ($arr['sk'] == 4)
        //     {
        //         $data['md']['kisi_sayisi_bcu']++;
        //     }
        // }


        // üstekki kodun yerine robin yazdı

            
       
        //$gradationData_for_mevcut_dagilim = ($gradationData["is_over_ten"]) ? $gradationData["data"] : $gradationData["data_merged"];
        $gradationData_for_mevcut_dagilim = $gradationData["data"];
        //$gradationData_for_mevcut_dagilim = array_reverse($gradationData_for_mevcut_dagilim);


        //var_dump( $gradationData_for_mevcut_dagilim);
        // exit();



        
        foreach ($data['siralama'] as $key=>$arr)
        {
            //$keyx = array_search($arr["sk"], array_column($gradationData["data"], 'id'));
            //$data['md'][$gradationData["data"][$keyx]["level"]]++;
           
            
            $keyx = array_search($arr["sk"], array_column($gradationData_for_mevcut_dagilim, 'id'));
            $data['md'][$gradationData_for_mevcut_dagilim[$keyx]["level"]]++;
           
            //var_dump($arr["sk"],$keyx);
           //var_dump($gradationData_for_mevcut_dagilim[$keyx]);
           
          
            // $data['md'][$keyx] = [
            //     "id"=>$gradationData_for_mevcut_dagilim[$keyx]["id"],
            //     "level"=>$gradationData_for_mevcut_dagilim[$keyx]["level"],
            //     "value" =>$gradationData_for_mevcut_dagilim[$keyx]["value"],
            //     "merged"=>$gradationData_for_mevcut_dagilim[$keyx]["merged"],
            //     "merged_values_count" => count($gradationData_for_mevcut_dagilim[$keyx]["merged_with"])
            // ];


           
        }

        if(count($data['siralama']) > 2 ){
            $data["md"] = array_reverse($data["md"]);
        }else{
            $data["md"] = [];
        }


        //var_dump($data["md"]);
        //exit();

        
        //$file_data_array = null;

        $ft_id[] = 1;
        $ft_id[] = 2;
        if (!is_array($data['siralama']))
        {
            $data['iptal'] = false;
            // Yoksa öneri listesi oluştur. Kademelendirilen kişilerin yetkinlik ve hedef puanlarını çek ve sırala.
            unset($kosul);
            $select = '*, sum(puan) as a';
            $kosul['yil'] = $yil;
            $kosul['ft_id'] = $ft_id;
            $kosul['pernr'] = $kisi_listesi;
            $kosul['degerlendiren_ack'] = 'yon1';
            $group_by = 'pernr, ft_id';
            $hedef_puanlar = $this->genel_model->veri_cek_cs("degerlendirmeler", $select, $kosul, '', '', $group_by);

            foreach ($hedef_puanlar as $key=>$arr)
            {
                $data['puan'][$arr['pernr']][$arr['ft_id']] = $arr['a'];
                $data['form_id'][$arr['pernr']][$arr['ft_id']] = $arr['form_id'];
            }
            
            foreach ($kisi_listesi as $key=>$val)
            {
                $data['siralama'][$key]['havuz_id'] = $havuz_id;
                $data['siralama'][$key]['yil'] = $yil;
                $data['siralama'][$key]['donem'] = $donem;
                $data['siralama'][$key]['pernr'] = $val;
                $data['siralama'][$key]['hp'] = $data['puan'][$val]['1'];
                $data['siralama'][$key]['yp'] = $data['puan'][$val]['2'];
                $data['siralama'][$key]['tp'] = $data['puan'][$val]['1']+$data['puan'][$val]['2'];
                
            }

          

            foreach ($data['siralama'] as $key=>$arr)
            {
                $tp[$key] = $arr['tp'];
                $hp[$key] = $arr['hp'];
                $pn[$key] = $arr['pernr'];
            }
            
            array_multisort($tp, SORT_DESC, $hp, SORT_DESC, $pn, SORT_ASC, $data['siralama']);
            

//            // Toplam puan
//            unset($kosul);
//            unset($order_by);
//            $select = '*, sum(puan) as a';
//            $kosul['yil'] = $yil;
//            $kosul['ft_id'] = $ft_id;
//            $kosul['pernr'] = $kisi_listesi;
//            $kosul['degerlendiren_ack'] = 'yon1';
//            $group_by = 'pernr';
//            $order_by['a'] = 'desc';
//            $toplam_puanlar = $this->genel_model->veri_cek_cs("degerlendirmeler", $select, $kosul, $order_by, '', $group_by);
//
//            foreach ($toplam_puanlar as $key=>$arr)
//            {
//                $data['puan'][$arr['pernr']]['t'] = $arr['a'];
//                $data['siralama'][] = $arr['pernr'];
//            }
        }
        else
        {
            unset($kosul);
            $select = '*';
            $kosul['yil'] = $yil;
            $kosul['donem'] = $donem;
            $kosul['ft_id'] = $ft_id;
            $kosul['pernr'] = $kisi_listesi;
            $form_atamalari = $this->genel_model->veri_cek_cs("form_atamalari", $select, $kosul);

            
            foreach ($form_atamalari as $key=>$arr)
            {
                $data['form_id'][$arr['pernr']][$arr['ft_id']] = $arr['form_id'];
            }
            
            $data['iptal'] = true;
        }
        
        // Kademelendirme onayı ne durumda ?
        unset($kosul);
        $select = '*';
        $kosul['yil'] = $yil;
        $kosul['donem'] = $donem;
        $kosul['degerlendiren'] = $k_pernr;
        $kosul['onaylayan'] = $yon1['ust_pernr'];
        $kosul['ft_id'] = 99;
        $onaylar = $this->genel_model->veri_cek_ts("onaylar", $select, $kosul);

        //var_dump($kosul);
        //exit();

        
        //echo $this->db->last_query();

        //var_dump($onaylar);
        //exit();

        if (is_array($onaylar))
        {
           
            if ($onaylar['onay_durum'] == 1 || $onaylar['onay_durum'] == 4)
            {
                
                $data['degisiklik'] = false;
            }
        }
        
    //    echo '<pre>';
    //    print_r($data);
    //    echo '</pre>';
    //    exit();

        unset($kosul);
        $select = '*';
        $kosul['yil'] = $yil;
        $kosul['donem'] = $donem;
        $kosul['havuz_id'] = $havuz_id;
        //$kosul['onaylayan'] = $yon1['ust_pernr'];
        $kademelendirme_sonuclari_data = $this->genel_model->veri_cek_cs("kademelendirme_sonuclari", $select, $kosul);

        $filtered_results = array_filter($kademelendirme_sonuclari_data, function ($var) {
            return ($var['kyo'] == 0);
        });
       
        $is_saved_before = (count($filtered_results) == 0)  ? true : false;
        $data['is_saved_before'] = $is_saved_before;

        //var_dump($data['is_saved_before']);
        
        // echo "<pre>";
        // print_r($data);
        // exit();
        
        $this->template->goster('kademelendirme_detay', $data);
        
        // Kademelendirmeyi iptal edince kendi değerlendirip kademelendirdikleri için onay durumunu update et.
        
    }
    
    public function kademelendirmeyi_baslat($yil, $donem, $havuz_id){
        if (empty_f($yil) || empty_f($donem) || empty_f($havuz_id))
        {
            $alert_message = alert_message('Hata.');
            $this->session->set_flashdata('message', $alert_message);
            redirect('./home/');
            exit();
        }
        
        unset($kosul);
        $select = '*';
        $kosul['yil'] = $yil;
        $kosul['donem'] = $donem;
        $donem_listesi = $this->genel_model->veri_cek_ts("donem_listesi", $select, $kosul);
        
        if (!is_array($donem_listesi))
        {
            $alert_message = alert_message('Hata.');
            $this->session->set_flashdata('message', $alert_message);
            redirect('./home/');
            exit();
        }
        
        
        $tarih = date('Y-m-d');
        
        if ($tarih < $donem_listesi['bat'])
        {
            $alert_message = alert_message('Hata.');
            $this->session->set_flashdata('message', $alert_message);
            redirect('./home/');
            exit();
        }
        
        if ($tarih > $donem_listesi['bit'])
        {
            $alert_message = alert_message('Hata.');
            $this->session->set_flashdata('message', $alert_message);
            redirect('./home/');
            exit();
        }
        
        unset($kosul);
        $select = '*';
        $kosul['yil'] = $yil;
        $kosul['donem'] = $donem;
        $kosul['havuz_id'] = $havuz_id;
        $havuzdakiler = $this->genel_model->veri_cek_cs("kademelendirme_atamalari", $select, $kosul);
        if (!is_array($havuzdakiler))
        {
            $alert_message = alert_message('Hata.');
            $this->session->set_flashdata('message', $alert_message);
            redirect('./home/');
            exit();
        }
        
        $k_pernr = $havuzdakiler['0']['k_pernr'];
        
        // Kademelendiren Kişinin kendisi değilse hata ver..
        if ($k_pernr != $this->session->userdata['pernr'] && $this->session->userdata('pernr') != 100003)
        {
            $alert_message = alert_message('Bu havuzu görüntüleme yetkiniz yok.');
            $this->session->set_flashdata('message', $alert_message);
            redirect('/d_donemleri/takvim/'.$yil.'/'.$donem);
            exit();
        }
        
        // Kademelendirme onayı ne durumda ?
        unset($kosul);
        $select = '*';
        $kosul['yil'] = $yil;
        $kosul['donem'] = $donem;
        $kosul['degerlendiren'] = $k_pernr;
        $kosul['ft_id'] = 99;
        $onaylar = $this->genel_model->veri_cek_ts("onaylar", $select, $kosul);

        if (is_array($onaylar))
        {
            if ($onaylar['onay_durum'] == 1 || $onaylar['onay_durum'] == 4)
            {
                $alert_message = alert_message('Hata.');
                $this->session->set_flashdata('message', $alert_message);
                redirect('./home/');
                exit();
            }
        }
        
        unset($kosul);
        $select = 'count(*) as a';
        $kosul['yil'] = $yil;
        $kosul['donem'] = $donem;
        $kosul['havuz_id'] = $havuz_id;
        $kisi_sayisi = $this->genel_model->veri_cek_ts("kademelendirme_atamalari", $select, $kosul);
        
        $data['kisi_sayisi'] = $kisi_sayisi['a'];


        ///////////////////////////
        $dummy_year = $yil;
        $dummy_person_count = $data['kisi_sayisi'];
        $gradationData = $this->gradation_key->getGradationData($dummy_year,$donem,$dummy_person_count,$havuz_id);
        $data["gradationData"] = $gradationData;

        //$data["gradationData"] = ($gradationData["is_over_ten"]) ? $gradationData["data"] : $gradationData["data_merged"];

        
        

        ///////////////////////////


        //var_dump( $data["gradationData"]);
        
        //yukarıda robin tarafından dinamik hali yazıldı.

        // if ($data['kisi_sayisi'] > 10)
        // {
        //     $data['kisi_sayisi_ba'] = round($data['kisi_sayisi']*0.15);
        //     $data['kisi_sayisi_b'] = $data['kisi_sayisi']-round($data['kisi_sayisi']*0.15)-round($data['kisi_sayisi']*0.25)-round($data['kisi_sayisi']*0.1);
        //     $data['kisi_sayisi_bu']  = round($data['kisi_sayisi']*0.25);
        //     $data['kisi_sayisi_bcu']  = round($data['kisi_sayisi']*0.1);
        // }
        // else if ($data['kisi_sayisi'] == 10)
        // {
        //     $data['kisi_sayisi_ba'] = 1;
        //     $data['kisi_sayisi_b'] = 5;
        //     $data['kisi_sayisi_bu']  = 3;
        //     $data['kisi_sayisi_bcu']  = 1;
        // }
        // else if ($data['kisi_sayisi'] == 9)
        // {
        //     $data['kisi_sayisi_ba'] = 1;
        //     $data['kisi_sayisi_b'] = 5;
        //     $data['kisi_sayisi_bu']  = 2;
        //     $data['kisi_sayisi_bcu']  = 1;
        // }
        // else if ($data['kisi_sayisi'] == 8)
        // {
        //     $data['kisi_sayisi_ba'] = 1;
        //     $data['kisi_sayisi_b'] = 4;
        //     $data['kisi_sayisi_bu']  = 2;
        //     $data['kisi_sayisi_bcu']  = 1;
        // }
        // else if ($data['kisi_sayisi'] == 7)
        // {
        //     $data['kisi_sayisi_ba'] = 1;
        //     $data['kisi_sayisi_b'] = 4;
        //     $data['kisi_sayisi_bu']  = 1;
        //     $data['kisi_sayisi_bcu']  = 1;
        // }
        // else if ($data['kisi_sayisi'] == 6)
        // {
        //     $data['kisi_sayisi_ba'] = 1;
        //     $data['kisi_sayisi_b'] = 3;
        //     $data['kisi_sayisi_bu']  = 1;
        //     $data['kisi_sayisi_bcu']  = 1;
        // }
        // else if ($data['kisi_sayisi'] == 5)
        // {
        //     $data['kisi_sayisi_ba'] = 1;
        //     $data['kisi_sayisi_b'] = 2;
        //     $data['kisi_sayisi_bu']  = 1;
        //     $data['kisi_sayisi_bcu']  = 1;
        // }



        
        // Havuzdaki kişileri çek, değerlendiren kim bak eğer değerlendiren ve kademlendiren aynı ise onay kontrolü
        // değerlendiren ve kademelendiren aynı değilse onay durumuna bak kendisi ise
        // Henüz onaylar verilmemişse kademelendirmeyi başlatma ancak mevcut durumu görüntület
        // Astlarımın kademelendirdiklerini getirme..
        foreach ($havuzdakiler as $key=>$arr)
        {
            $kisi_listesi[] = $arr['pernr'];
        }
        
        unset($kosul);
        $select = '*';
        $kosul['yil'] = $yil;
        $kosul['donem'] = $donem;
        $kosul['pernr'] = $kisi_listesi;
        $kademelendirilen_kisi_bilgileri = $this->genel_model->veri_cek_cs("organizasyon_d", $select, $kosul);
        
        foreach ($kademelendirilen_kisi_bilgileri as $key=>$arr)
        {
            $data['ust_pernr'][$arr['pernr']] = $arr['ust_pernr'];
            if ($arr['ust_pernr'] == $k_pernr) // kademelendiren ve onaylayan kendisi
            {
                $kendi_onay_durumu_kontrol[$arr['ust_pernr']] = $arr['ust_pernr'];
            }
            else
            {
                $farkli_onay_durumu_kontrol[$arr['ust_pernr']] = $arr['ust_pernr'];
            }
        }
        
        // Havuzdaki kişilerin hedef formu var mı ?
        unset($kosul);
        $select = '*';
        $kosul['yil'] = $yil;
        $kosul['donem'] = $donem;
        $kosul['ft_id'] = 1;
        $kosul['pernr'] = $kisi_listesi;
        $form_atamalari = $this->genel_model->veri_cek_cs("form_atamalari", $select, $kosul);

        if (is_array($form_atamalari))
        {
            $hedef_formu_var = true;
            foreach ($form_atamalari as $key=>$arr)
            {
                $hedef_formu_olan[] = $data['ust_pernr'][$arr['pernr']];
            }
        }
        else
        {
            $hedef_formu_var = false;
        }
        
        $data['hedef_formu_var'] = $hedef_formu_var;
        
        $start = true;
        foreach ($farkli_onay_durumu_kontrol as $key=>$val)
        {
            if ($hedef_formu_var)
            {
                if (in_array($val, $hedef_formu_olan))
                {
                    // Onay durumunu çek...
                    unset($kosul);
                    $select = '*';
                    $kosul['yil'] = $yil;
                    $kosul['donem'] = $donem;
                    $kosul['ft_id'] = 1;
                    $kosul['degerlendiren'] = $val;
                    $kosul['onaylayan!='] = $val;
                    $od_ast = $this->genel_model->veri_cek_ts("onaylar", $select, $kosul);
                    if (is_array($od_ast))
                    {
                        if ($od_ast['onay_durum'] != 1)
                        {
                            $start = false;
                        }
                    }
                    else
                    {
                        $start = false;
                    }
                }
            }
            
            // Onay durumunu çek..
            unset($kosul);
            $select = '*';
            $kosul['yil'] = $yil;
            $kosul['donem'] = $donem;
            $kosul['ft_id'] = 2;
            $kosul['degerlendiren'] = $val;
            $kosul['onaylayan!='] = $val;
            $od_ast = $this->genel_model->veri_cek_ts("onaylar", $select, $kosul);
            if (is_array($od_ast))
            {
                if ($od_ast['onay_durum'] != 1)
                {
                    $start = false;
                }
            }
            else 
            {
                $start = false;
            }
        }
        
        foreach ($kendi_onay_durumu_kontrol as $key=>$val)
        {
            if ($hedef_formu_var)
            {
                if (in_array($val, $hedef_formu_olan))
                {
                    // Onay durumunu çek..
                    unset($kosul);
                    $select = '*';
                    $kosul['yil'] = $yil;
                    $kosul['donem'] = $donem;
                    $kosul['ft_id'] = 1;
                    $kosul['degerlendiren'] = $k_pernr;
                    $kosul['onaylayan'] = $k_pernr;
                    $od_ast = $this->genel_model->veri_cek_ts("onaylar", $select, $kosul);
                    if (is_array($od_ast))
                    {
                        if ($od_ast['onay_durum'] != 1)
                        {
                            $start = false;
                        }
                    }
                    else
                    {
                        $start = false;
                    }
                }
            }
            
            // Onay durumunu çek..
            unset($kosul);
            $select = '*';
            $kosul['yil'] = $yil;
            $kosul['donem'] = $donem;
            $kosul['ft_id'] = 2;
            $kosul['degerlendiren'] = $k_pernr;
            $kosul['onaylayan='] = $k_pernr;
            $od_ast = $this->genel_model->veri_cek_ts("onaylar", $select, $kosul);
            if (is_array($od_ast))
            {
                if ($od_ast['onay_durum'] != 1)
                {
                    $start = false;
                }
            }
            else
            {
                $start = false;
            }
        }
        
        if (!$start)
        {
            $alert_message = alert_message('Hata.');
            $this->session->set_flashdata('message', $alert_message);
            redirect('./home/');
            exit();
        }
        
        $ft_id[] = 1;
        $ft_id[] = 2;
        unset($kosul);
        unset($order_by);
        $select = '*, sum(puan) as a';
        $kosul['yil'] = $yil;
        $kosul['ft_id'] = $ft_id;
        $kosul['pernr'] = $kisi_listesi;
        $kosul['degerlendiren_ack'] = 'yon1';
        $group_by = 'pernr, ft_id';
        $hedef_puanlar = $this->genel_model->veri_cek_cs("degerlendirmeler", $select, $kosul, $order_by, '', $group_by);


        // var_dump($hedef_puanlar);
        // exit();

        foreach ($hedef_puanlar as $key=>$arr)
        {
            $data['puan'][$arr['pernr']][$arr['ft_id']] = $arr['a'];
            $data['form_id'][$arr['pernr']][$arr['ft_id']] = $arr['form_id'];
        }
        
        foreach ($kisi_listesi as $key=>$val)
        {
            $veri_kaydet[$key]['havuz_id'] = $havuz_id;
            $veri_kaydet[$key]['yil'] = $yil;
            $veri_kaydet[$key]['donem'] = $donem;
            $veri_kaydet[$key]['pernr'] = $val;
            $veri_kaydet[$key]['hp'] = $data['puan'][$val]['1'];
            $veri_kaydet[$key]['yp'] = $data['puan'][$val]['2'];
            $veri_kaydet[$key]['tp'] = $data['puan'][$val]['1']+$data['puan'][$val]['2'];
            $veri_kaydet[$key]['guncelleyen'] = $this->session->userdata("pernr");
            $veri_kaydet[$key]['g_time'] = date('Y-m-d H:i:s');
        }
        
        foreach ($veri_kaydet as $key=>$arr)
        {
            $tp[$key] = $arr['tp'];
            $hp[$key] = $arr['hp'];
            $pn[$key] = $arr['pernr'];
        }
        
        array_multisort($tp, SORT_DESC, $hp, SORT_DESC, $pn, SORT_ASC, $veri_kaydet);
        

        //$gradationData = $data["gradationData"]["data"];
        //$gradationData = $data["gradationData"]["data_merged"];
        $gradationData = ($gradationData["is_over_ten"]) ? $gradationData["data"] : $gradationData["data_merged"];

        

        $countGrad = count($gradationData);
  
        $maxGrad = $this->gradation_key->getMaxFromArray($gradationData);
        //$i = $this->gradation_key->getSumNthElementsOfArray($gradationData,3);
        
        
        //var_dump($gradationData);
        // var_dump($i);
         //exit();


       // alttaki kodun yerine robin yazdı SORULACAK
        //if ($data['kisi_sayisi'] = 4){
            $sayac = 0;
            foreach ($veri_kaydet as $key=>$arr)
            {
                $sayac++;
                for($level = 1; $level < $countGrad ; $level ++)
                {
                    if ($sayac >0 && $sayac <= $maxGrad["value"]){
                        
                        $veri_kaydet[$key]['vk'] = $maxGrad["id"];
                        $veri_kaydet[$key]['sk'] = $maxGrad["id"];
                    }
                    else if($sayac > ($this->gradation_key->getSumNthElementsOfArray($gradationData,$level)) && $sayac <= ($this->gradation_key->getSumNthElementsOfArray($gradationData,$level+1)))
                    {
                        $veri_kaydet[$key]['vk']  = $gradationData[$countGrad-$level-1]["id"];
                        $veri_kaydet[$key]['sk']  = $gradationData[$countGrad-$level-1]["id"];
                    }
                } 
            }
        //}
        // else{
        //     foreach ($veri_kaydet as $key2=>$arr){
        //        $veri_kaydet[$key2]['vk']  = null;
        //        $veri_kaydet[$key2]['sk']  = null;
        //     }
        // }
        
        //var_dump($gradationData);
        // var_dump($veri_kaydet);
        // exit();


        
        
       // BURASI SORULACAK
        
        // if ($data['kisi_sayisi'] > 4)
        // {
        //     $sayac = 0;
        //     foreach ($veri_kaydet as $key=>$arr)
        //     {
        //         $sayac++;
        //         if ($sayac >0 && $sayac <= $data['kisi_sayisi_bcu'])
        //         {
        //             $veri_kaydet[$key]['vk'] = 4;
        //             $veri_kaydet[$key]['sk'] = 4;
        //         }
        //         else if ($sayac > $data['kisi_sayisi_bcu'] && $sayac <= ($data['kisi_sayisi_bcu']+$data['kisi_sayisi_bu']))
        //         {
        //             $veri_kaydet[$key]['vk']  = 3;
        //             $veri_kaydet[$key]['sk']  = 3;
        //         }
        //         else if ($sayac > ($data['kisi_sayisi_bcu']+$data['kisi_sayisi_bu']) && $sayac <= ($data['kisi_sayisi_bcu']+$data['kisi_sayisi_bu']+$data['kisi_sayisi_b']))
        //         {
        //             $veri_kaydet[$key]['vk']  = 2;
        //             $veri_kaydet[$key]['sk']  = 2;
        //         }
        //         else if ($sayac > ($data['kisi_sayisi_bcu']+$data['kisi_sayisi_bu']+$data['kisi_sayisi_b']) && $sayac <= ($data['kisi_sayisi_ba']+$data['kisi_sayisi_b']+$data['kisi_sayisi_bu']+$data['kisi_sayisi_bcu']))
        //         {
        //             $veri_kaydet[$key]['vk']  = 1;
        //             $veri_kaydet[$key]['sk']  = 1;
        //         }
        //     }
        // }

        

//        else
//        {
//            $veri_kaydet[$key]['vk'] = 2;
//            $veri_kaydet[$key]['sk'] = 2;
//        }
        

       

        unset($kosul);
        $kosul['yil'] = $yil;
        $kosul['donem'] = $donem;
        $kosul['havuz_id'] = $havuz_id;
        $r = $this->genel_model->veri_kaydet_cs("kademelendirme_sonuclari", $veri_kaydet, $kosul);

        if (!$r)
        {
            $alert_message = alert_message('Teknik bir aksaklık oluştu daha sonra tekrar deneyiniz.');
            $this->session->set_flashdata('message', $alert_message);
            redirect('/d_donemleri/kademelendirme_detay/'.$yil.'/'.$donem.'/'.$havuz_id);
            exit();
        }

        $alert_message = alert_message('Kademelendirme başlatıldı.', 'success');
        $this->session->set_flashdata('message', $alert_message);
        redirect('/d_donemleri/kademelendirme_detay/'.$yil.'/'.$donem.'/'.$havuz_id);
        exit();
    }


    
    public function kademelendirmeyi_iptal_et($yil, $donem, $havuz_id)
    {
        if (empty_f($yil) || empty_f($donem) || empty_f($havuz_id))
        {
            $alert_message = alert_message('Hata.');
            $this->session->set_flashdata('message', $alert_message);
            redirect('./home/');
            exit();
        }
        
        unset($kosul);
        $select = '*';
        $kosul['yil'] = $yil;
        $kosul['donem'] = $donem;
        $donem_listesi = $this->genel_model->veri_cek_ts("donem_listesi", $select, $kosul);
        
        if (!is_array($donem_listesi))
        {
            $alert_message = alert_message('Hata.');
            $this->session->set_flashdata('message', $alert_message);
            redirect('./home/');
            exit();
        }
        
        
        $tarih = date('Y-m-d');
        
        if ($tarih < $donem_listesi['bat'])
        {
            $alert_message = alert_message('Hata.');
            $this->session->set_flashdata('message', $alert_message);
            redirect('./home/');
            exit();
        }
        
        if ($tarih > $donem_listesi['bit'])
        {
            $alert_message = alert_message('Hata.');
            $this->session->set_flashdata('message', $alert_message);
            redirect('./home/');
            exit();
        }
        
        unset($kosul);
        $select = '*';
        $kosul['yil'] = $yil;
        $kosul['donem'] = $donem;
        $kosul['havuz_id'] = $havuz_id;
        $havuzdakiler = $this->genel_model->veri_cek_cs("kademelendirme_atamalari", $select, $kosul);
        if (!is_array($havuzdakiler))
        {
            $alert_message = alert_message('Hata.');
            $this->session->set_flashdata('message', $alert_message);
            redirect('./home/');
            exit();
        }
        
        $k_pernr = $havuzdakiler['0']['k_pernr'];
        
        // Kademelendiren Kişinin kendisi değilse hata ver..
        if ($k_pernr != $this->session->userdata['pernr'] && $this->session->userdata('pernr') != 100003)
        {
            $alert_message = alert_message('Bu havuzu görüntüleme yetkiniz yok.');
            $this->session->set_flashdata('message', $alert_message);
            redirect('/d_donemleri/takvim/'.$yil.'/'.$donem);
            exit();
        }
        
        // Kademelendirme onayı ne durumda ?
        unset($kosul);
        $select = '*';
        $kosul['yil'] = $yil;
        $kosul['donem'] = $donem;
        $kosul['degerlendiren'] = $k_pernr;
        $kosul['ft_id'] = 99;
        $onaylar = $this->genel_model->veri_cek_ts("onaylar", $select, $kosul);

        if (is_array($onaylar))
        {
            if ($onaylar['onay_durum'] == 1 || $onaylar['onay_durum'] == 4)
            {
                $alert_message = alert_message('Hata.');
                $this->session->set_flashdata('message', $alert_message);
                redirect('./home/');
                exit();
            }
        }
        
        
        // Havuzdaki kişileri çek, değerlendiren kim bak eğer değerlendiren ve kademlendiren aynı ise onay kontrolü
        // değerlendiren ve kademelendiren aynı değilse onay durumuna bak kendisi ise
        // Henüz onaylar verilmemişse kademelendirmeyi başlatma ancak mevcut durumu görüntület
        // Astlarımın kademelendirdiklerini getirme..
        
        
        unset($kosul);
        unset($order_by);
        $select = '*';
        $kosul['yil'] = $yil;
        $kosul['donem'] = $donem;
        $kosul['havuz_id'] = $havuz_id;
        $siralama = $this->genel_model->veri_cek_cs("kademelendirme_sonuclari", $select, $kosul, $order_by);
        if (!is_array($siralama))
        {
            $alert_message = alert_message('Hata.');
            $this->session->set_flashdata('message', $alert_message);
            redirect('./home/');
            exit();
        }
        
        unset($kosul);
        $kosul['yil'] = $yil;
        $kosul['donem'] = $donem;
        $kosul['havuz_id'] = $havuz_id;
        $r = $this->genel_model->veri_sil("kademelendirme_sonuclari", $kosul);

        if (!$r)
        {
            $alert_message = alert_message('Teknik bir aksaklık oluştu daha sonra tekrar deneyiniz.');
            $this->session->set_flashdata('message', $alert_message);
            redirect('/d_donemleri/kademelendirme_detay/'.$yil.'/'.$donem.'/'.$havuz_id);
            exit();
        }

        $alert_message = alert_message('Kademelendirme iptal edildi.', 'success');
        $this->session->set_flashdata('message', $alert_message);
        redirect('/d_donemleri/kademelendirme_detay/'.$yil.'/'.$donem.'/'.$havuz_id);
        exit();
    }
    
    public function kademelendirmeyi_kaydet($yil, $donem, $havuz_id)
    {
        
        $yil = $this->input->post('yil');
        $donem = $this->input->post('donem');
        $havuz_id = $this->input->post('havuz_id');
        $sk = $this->input->post('sk');
        $kyo = $this->input->post('kyo');
        // if($this->session->userdata('pernr') == 100003){
        //     $kyo = $this->input->post('kyo');
        // }

        // var_dump($sk);
        // exit();

        if($this->session->userdata('pernr') == 100003){
            $attachment = $_FILES;
            // var_dump($attachment);
            // exit();
        }
       

        
        
        if (empty_f($yil) || empty_f($donem) || empty_f($havuz_id) || !is_array($sk))
        {
            $alert_message = alert_message('Hata.');
            $this->session->set_flashdata('message', $alert_message);
            redirect('./home/');
            exit();
        }
        
        unset($kosul);
        $select = '*';
        $kosul['yil'] = $yil;
        $kosul['donem'] = $donem;
        $donem_listesi = $this->genel_model->veri_cek_ts("donem_listesi", $select, $kosul);
        
        if (!is_array($donem_listesi))
        {
            $alert_message = alert_message('Hata 3619.');
            $this->session->set_flashdata('message', $alert_message);
            redirect('./home/');
            exit();
        }
        
        
        $tarih = date('Y-m-d');
        
        if ($tarih < $donem_listesi['bat'])
        {
            $alert_message = alert_message('Hata 3630.');
            $this->session->set_flashdata('message', $alert_message);
            redirect('./home/');
            exit();
        }
        
        if ($tarih > $donem_listesi['bit'])
        {
            $alert_message = alert_message('Hata 3639.');
            $this->session->set_flashdata('message', $alert_message);
            redirect('./home/');
            exit();
        }
        
        unset($kosul);
        $select = '*';
        $kosul['yil'] = $yil;
        $kosul['donem'] = $donem;
        $kosul['havuz_id'] = $havuz_id;
        $havuzdakiler = $this->genel_model->veri_cek_cs("kademelendirme_atamalari", $select, $kosul);
        if (!is_array($havuzdakiler))
        {
            $alert_message = alert_message('Hata 3652.');
            $this->session->set_flashdata('message', $alert_message);
            redirect('./home/');
            exit();
        }
        
        $k_pernr = $havuzdakiler['0']['k_pernr'];
        
        // Kademelendiren Kişinin kendisi değilse hata ver..
        if ($k_pernr != $this->session->userdata['pernr'] && $this->session->userdata('pernr') != 100003)
        {
            $alert_message = alert_message('Bu havuzu görüntüleme yetkiniz yok.');
            $this->session->set_flashdata('message', $alert_message);
            redirect('/d_donemleri/takvim/'.$yil.'/'.$donem);
            exit();
        }
        
        // Kademelendirme onayı ne durumda ?
        unset($kosul);
        $select = '*';
        $kosul['yil'] = $yil;
        $kosul['donem'] = $donem;
        $kosul['degerlendiren'] = $k_pernr;
        $kosul['ft_id'] = 99;
        $onaylar = $this->genel_model->veri_cek_ts("onaylar", $select, $kosul);

        // var_dump($kosul);
        // exit();

        if (is_array($onaylar))
        {
            if ( ($onaylar['onay_durum'] == 1 || $onaylar['onay_durum'] == 4 ) && is_null($sk))
            {
                $alert_message = alert_message('Hata 3682.');
                $this->session->set_flashdata('message', $alert_message);
                redirect('./home/');
                exit();
            }
        }

        unset($kosul);
        unset($order_by);
        $select = '*';
        $kosul['yil'] = $yil;
        $kosul['donem'] = $donem;
        $kosul['havuz_id'] = $havuz_id;
        $siralama = $this->genel_model->veri_cek_cs("kademelendirme_sonuclari", $select, $kosul, $order_by);
        if (!is_array($siralama))
        {
            $alert_message = alert_message('Hata. 3698');
            $this->session->set_flashdata('message', $alert_message);
            redirect('./home/');
            exit();
        }


        ///////////////////////////////////////
            $this->load->library("gradation_key");
            // $dummy_year = 2021;
            // $dummy_person_count =   $data['kisi_sayisi'];
            $pool_levels = $this->gradation_key->pool_levels($yil,$havuz_id,$donem);
        ///////////////////////////////////////

      
        
        $dummy_person_count = count($sk);
        $gradationData = $this->gradation_key->getGradationData($yil,$donem,$dummy_person_count,$havuz_id);

        //$data["gradationData"] = $gradationData;
        //$data["gradationDataForMerged"] = $this->gradation_key->getGradationDatForMerged($yil,$donem,$dummy_person_count,$havuz_id);


        // var_dump( $gradationData);
        // exit();
        // var_dump($sk);


        // var_dump($siralama,$sk);

        // exit();
        
        $sayac = 0;
        $y = [];
        foreach ($siralama as $key=>$arr)
        {

            $sayac++;
            if ($sk[$arr['pernr']] == '')
            {
                $sk[$arr['pernr']] = $arr['vk'];
            }
            if ($arr['vk'] > 0)
            {
                $fark_kontrol = $sk[$arr['pernr']]-$arr['vk'];
                $fark_kontrol = abs($fark_kontrol);
                
                if ($fark_kontrol > 1)
                {
                    if($gradationData["is_over_ten"]){
                        
                        $alert_message = alert_message('Hata 3769.');
                        $this->session->set_flashdata('message', $alert_message);
                        redirect('./home/');
                        exit();
                    }
                }
            }

            
            // var_dump($sk);
            // exit();
            
         
             // buraya bakılacak
            // if ($sk[$arr['pernr']] == 4)
            // {
                
            //     $y_kisi_sayisi_bcu++;
            // }
            // else if ($sk[$arr['pernr']] == 3)
            // {
               
            //     $y_kisi_sayisi_bu++;
            // }
            // else if ($sk[$arr['pernr']] == 2)
            // {
               
            //     $y_kisi_sayisi_b++;
            // }
            // else if ($sk[$arr['pernr']] == 1)
            // {
                
            //     $y_kisi_sayisi_ba++;
            // }


            
                //echo "<pre>";
                //print_r($sk);
                //print_r($gradationData["data"]);
                // exit();
            

            if(!$gradationData["is_over_ten"]){
                

               // var_dump($sk);
                //exit();


                $sum_of_sk = [];
                foreach($sk as $pernr_key => $k){
                    $sum_of_sk[$k][] = $pernr_key;
                }
                
                $merged_with_values = [];
                foreach($sum_of_sk as $sosk =>$value){
                   
                    $grd_key = array_search($sosk, array_column($gradationData["data"], 'id'));
                    

                    
                    if(!is_null($gradationData["data"][$grd_key])){
                        
                        $merged_with = $gradationData["data"][$grd_key]["merged_with"];
                        foreach($merged_with as $mw){
                            $merged_with_values[$sosk][] = $mw["id"];
                        }
                        
                    }



                   
                    if(!empty($merged_with_values[$sosk])){ // merge var 
                        $sum = 0;
                        foreach($merged_with_values[$sosk] as $value){
                            $sum += count($sum_of_sk[$value]);
                        }



                        // echo "<pre>";
                        // print_r($gradationData["data"]);
                        // echo "<hr />";
                        // exit();
  
                        //var_dump($gradationData["data"][$grd_key]["value"],$sum);
                        if($gradationData["data"][$grd_key]["value"] != $sum){
                            
                            
                            $alert_message = alert_message('Olması gereken dağılım sayılarına kesinlikle uymalısınız.'.$gradationData["data"][$grd_key]["value"]."=?=".$sum);
                            //echo $alert_message;
                            $this->session->set_flashdata('message', $alert_message);
                            redirect('/d_donemleri/kademelendirme_detay/'.$yil.'/'.$donem.'/'.$havuz_id);
                            exit();
                        }


                        
                    }
                    else { // merge yok
                       
                        if($gradationData["data"][$grd_key]["value"] != count($value)){
                            
                            $alert_message = alert_message('Olması gereken dağılım sayılarına kesinlikle uymalısınız .');
                            //echo $alert_message;
                            $this->session->set_flashdata('message', $alert_message);
                            redirect('/d_donemleri/kademelendirme_detay/'.$yil.'/'.$donem.'/'.$havuz_id);
                            exit();
                        }
                    }


                }


                // echo "tamam geçti";
                // exit();

            }
            else if($gradationData["is_over_ten"]){


                // yukardaki kapalı kod  yerine robin tarafından yazıldı
                // foreach($gradationData["data"] as $x=>$gr){
                //     //if(array_search($gr["id"], array_column($sk, 'sk'))){
                //     if(array_search($gr["id"], $sk, 'sk')) {
                //         if($sk[$arr['pernr']] == $gr["id"]){
                //             //echo $sk[$arr['pernr']]."==".$gr["id"]."<br>";
                //             $y['data'][$gr["id"]]++;
                //         }
                //     }
                //     else{
                //         $y['data'][$gr["id"]] = 0;
                //     }
                // }
                // yukardaki kapalı kod  yerine robin tarafından yazıldı

                // robin yazdı
               // krsort($y["data"]);
                // robin yazdı

                

                $countGrad = count($gradationData["data"]);

                // foreach($gradationData["data"] as $grad){
                
                //     if ($sayac > 4){
                        
                //         if($y['data'][$grad["id"]] != 0){ // yeni ekledim
                //             //echo "aa";
                //             //echo "grad_id:".$grad["id"]."----->".$y['data'][$grad["id"]]."==". $grad["value"]."(olması gereken dağılım value)"."<br>";
                //             if($y['data'][$grad["id"]] != $grad["value"]){
                //                 $alert_message = alert_message('Olması gereken dağılım sayılarına uymalısınız 3889.');
                //                 echo $alert_message;
                //                 $this->session->set_flashdata('message', $alert_message);
                //                 redirect('/d_donemleri/kademelendirme_detay/'.$yil.'/'.$donem.'/'.$havuz_id);
                //                 exit();
                //             }
                //         }
                //     }
                // }


                $sum_of_sk = [];
                foreach($sk as $pernr_key => $k){
                    $sum_of_sk[$k][] = $pernr_key;
                }
             
                foreach($sum_of_sk as $sosk =>$value){
                    $grd_key = array_search($sosk, array_column($gradationData["data"], 'id'));

                    if($gradationData["data"][$grd_key]["value"] != count($value)){
                            
                        $alert_message = alert_message('Olması gereken dağılım sayılarına kesinlikle uymalısınız .');
                        //echo $alert_message;
                        $this->session->set_flashdata('message', $alert_message);
                        redirect('/d_donemleri/kademelendirme_detay/'.$yil.'/'.$donem.'/'.$havuz_id);
                        exit();
                    }
                }






                

            }
            
            
             
            //// robinin kodu //// 

            $siralama[$key]['sk'] = $sk[$arr['pernr']];
            
            if($this->session->userdata('pernr') == 100003){
                $siralama[$key]['kyo'] = $kyo[$arr['pernr']];
            }else{
                $siralama[$key]['kyo'] = $sk[$arr['pernr']];
            }

            if($this->session->userdata('pernr') == 100003 && $kyo[$arr['pernr']]){
                $this->load->library('upload');
                $number_of_files_uploaded = count($_FILES['attachment']['name']);
                $files = $_FILES;
                if($files['attachment']['size'][$arr['pernr']] > 0) {
                    $_FILES['test']['name']     = $files['attachment']['name'][$arr['pernr']];
                    $_FILES['test']['type']     = $files['attachment']['type'][$arr['pernr']];
                    $_FILES['test']['tmp_name'] = $files['attachment']['tmp_name'][$arr['pernr']];
                    $_FILES['test']['error']    = $files['attachment']['error'][$arr['pernr']];
                    $_FILES['test']['size']     = $files['attachment']['size'][$arr['pernr']];


                   

                    $unique_file_name = date("YmdHis")."_".$files['attachment']['name'][$arr['pernr']];
                    //echo $unique_file_name;
                    $unique_file_name = str_replace(" ", "_" , $unique_file_name);

                    
                    // Şişli Belediyesindeki Büyük Çöp Yığınları Öğürtüyor


                    $unique_file_name = str_replace("ş", "s" , $unique_file_name);
                    $unique_file_name = str_replace("ı", "i" , $unique_file_name);
                    $unique_file_name = str_replace("ğ", "g" , $unique_file_name);
                    $unique_file_name = str_replace("ö", "o" , $unique_file_name);
                    $unique_file_name = str_replace("ç", "c" , $unique_file_name);
                    $unique_file_name = str_replace("ü", "u" , $unique_file_name);

                    $unique_file_name = str_replace("Ş", "s" , $unique_file_name);
                    $unique_file_name = str_replace("I", "i" , $unique_file_name);
                    $unique_file_name = str_replace("Ğ", "g" , $unique_file_name);
                    $unique_file_name = str_replace("Ö", "o" , $unique_file_name);
                    $unique_file_name = str_replace("Ç", "c" , $unique_file_name);
                    $unique_file_name = str_replace("Ü", "u" , $unique_file_name);

                    $unique_file_name = strtolower($unique_file_name);


                    $config = array(
                        //'file_name'     => $files['attachment']['name'][$arr['pernr']],
                        'file_name'     =>  $unique_file_name,
                        'allowed_types' => '*',
                        'max_size'      => 6000,
                        'overwrite'     => FALSE,
                        'upload_path'=> $_SERVER['DOCUMENT_ROOT'] . '/assets/attachments'
                    );
                    $this->upload->initialize($config);
                    if (!$this->upload->do_upload('test')) {
                        $error = array('error' => $this->upload->display_errors());
                        var_dump($error);
                        exit();
                    }
                    else{
                        $final_files_data = $this->upload->data();
                        $full_path = 'assets/attachments/'.$unique_file_name;
                        $siralama[$key]['attachment_path'] =  $full_path;
                    }
                }
            }
            //// robinin kodu //// 

            $siralama[$key]['guncelleyen'] = $this->session->userdata('pernr');
            $siralama[$key]['g_time'] = date('Y-m-d H:i:s');

            
        }

       // exit();



       
        // robin yazdı
       // krsort($y["data"]);
        // robin yazdı

        // echo "<pre>";
        // print_r($gradationData["data"]);
        // var_dump($y["data"]);
        // exit();
        
        
        //Kişi limitlerini kontrol et
        // if ($sayac > 10)
        // {
        //     $kisi_sayisi_ba = round($sayac*0.15);
        //     $kisi_sayisi_b = $sayac-round($sayac*0.15)-round($sayac*0.25)-round($sayac*0.1);
        //     $kisi_sayisi_bu = round($sayac*0.25);
        //     $kisi_sayisi_bcu = round($sayac*0.1);
        // }
        // else if ($sayac == 10)
        // {
        //     $kisi_sayisi_ba = 1;
        //     $kisi_sayisi_b = 5;
        //     $kisi_sayisi_bu = 3;
        //     $kisi_sayisi_bcu = 1;
        // }
        // else if ($sayac == 9)
        // {
        //     $kisi_sayisi_ba = 1;
        //     $kisi_sayisi_b = 5;
        //     $kisi_sayisi_bu = 2;
        //     $kisi_sayisi_bcu = 1;
        // }
        // else if ($sayac == 8)
        // {
        //     $kisi_sayisi_ba = 1;
        //     $kisi_sayisi_b = 4;
        //     $kisi_sayisi_bu = 2;
        //     $kisi_sayisi_bcu = 1;
        // }
        // else if ($sayac == 7)
        // {
        //     $kisi_sayisi_ba = 1;
        //     $kisi_sayisi_b = 4;
        //     $kisi_sayisi_bu = 1;
        //     $kisi_sayisi_bcu = 1;
        // }
        // else if ($sayac == 6)
        // {
        //     $kisi_sayisi_ba = 1;
        //     $kisi_sayisi_b = 3;
        //     $kisi_sayisi_bu = 1;
        //     $kisi_sayisi_bcu = 1;
        // }
        // else if ($sayac == 5)
        // {
        //     $kisi_sayisi_ba = 1;
        //     $kisi_sayisi_b = 2;
        //     $kisi_sayisi_bu = 1;
        //     $kisi_sayisi_bcu = 1;
        // }
        // else if ($sayac == 4)
        // {
        //     $kisi_sayisi_b = 3;
        //     $kisi_sayisi_bu = 1;
        // }
        // else if ($sayac == 3)
        // {
        //     $kisi_sayisi_b = 2;
        //     $kisi_sayisi_bu = 1;
        // }
        

        // KISI SAYISI ZATEN YOK BOŞA ÇALIŞIYORDU KAPATTIK
        // if ($kisi_sayisi > 4)
        // {
        //     $dag_mes = "<br/> Yapmaya çalıştığınız dağılım/olması gereken<br/>";
        //     $dag_mes = $dag_mes."Beklenen düzey altı : ".$y_kisi_sayisi_ba."/".$kisi_sayisi_ba." <br/>";
        //     $dag_mes = $dag_mes." Beklenen düzey : ".$y_kisi_sayisi_b."/".$kisi_sayisi_b." <br/>";
        //     $dag_mes = $dag_mes." Beklenen düzeyin üstü : ".$y_kisi_sayisi_bu."/".$kisi_sayisi_bu." <br/>";
        //     $dag_mes = $dag_mes." Beklenen düzeyin çok üstü : ".$y_kisi_sayisi_bcu."/".$kisi_sayisi_bcu." <br/>";
        // }

        //var_dump($y);
        //exit();
        //$countGrad = count($gradationData["data"]);

        
        //if($k_pernr != 100000 && $k_pernr != 100429){  yeni kapatıldı erol kiresepi 100000 olduğu için
            // foreach($gradationData["data"] as $grad){
                
            //     if ($sayac > 4){
                    
            //         if($y['data'][$grad["id"]] != 0){ // yeni ekledim
            //            // echo "grad_id:".$grad["id"]."----->".$y['data'][$grad["id"]]."==". $grad["value"]."(olması gereken dağılım value)"."<br>";
            //             if($y['data'][$grad["id"]] != $grad["value"]){
            //                 $alert_message = alert_message('Olması gereken dağılım sayılarına uymalısınız.');
            //                 //echo $alert_message;
            //                 $this->session->set_flashdata('message', $alert_message);
            //                 redirect('/d_donemleri/kademelendirme_detay/'.$yil.'/'.$donem.'/'.$havuz_id);
            //                 exit();
            //             }
            //         }
            //     }
            //     else if ($sayac == 3 || $sayac == 4){

            //         // $maxyid = max(array_keys($y['data']));
            //         // $maxy =  $y['data'][$max];
            //         // $minyid = min(array_keys($y['data']));
            //         // $miny =  $y['data'][$minid];

            //         // $maxgid = max(array_keys($gradationData["data"]));
            //         // $maxgrad =  $gradationData["data"][$max];
            //         // $mingid = min(array_keys($gradationData["data"]));
            //         // $mingrad =  $gradationData["data"][$minid];


            //         $maxyid = max(array_keys($y['data']));
            //         $maxy =  $y['data'][$maxyid];
            //         $minyid = min(array_keys($y['data']));
            //         $miny =  $y['data'][$minyid];

            //         $maxgid = max(array_keys($gradationData["data"]));
            //         $maxgrad =  $gradationData["data"][$max];
            //         $mingid = min(array_keys($gradationData["data"]));
            //         $mingrad =  $gradationData["data"][$minid];

            //         //if($y['data'][$max+1]+$maxy != 0 &&  $y['data'][$min+1]+$miny != 0){ // 2022-1026 eklendi 


            //             // if(($y['data'][$max+1]+$maxy) != $gradationData["data"][$max-1] ){
            //             //     $alert_message = alert_message('Olması gereken dağılım sayılarına uymalısınız.'.$dag_mes);
            //             //     $this->session->set_flashdata('message', $alert_message);
            //             //     redirect('/d_donemleri/kademelendirme_detay/'.$yil.'/'.$donem.'/'.$havuz_id);
            //             //     exit();
            //             // }
            //             // if(($y['data'][$min+1]+$miny) != $gradationData["data"][$min+1]){
            //             //     $alert_message = alert_message('Olması gereken dağılım sayılarına uymalısınız.'.$dag_mes);
            //             //     $this->session->set_flashdata('message', $alert_message);
            //             //     redirect('/d_donemleri/kademelendirme_detay/'.$yil.'/'.$donem.'/'.$havuz_id);
            //             //     exit();
            //             // }

                        
            //             if( ($y['data'][$maxyid-1] + $y['data'][$maxyid]) != $gradationData["data"][$maxgrad-1]){
            //                 $alert_message = alert_message('Olması gereken dağılım sayılarına uymalısınız.'.$dag_mes);

            //                 echo ($y['data'][$maxyid-1] + $y['data'][$maxyid])."!=".$gradationData["data"][$maxgrad-1]."<br>";
                            
            //                 // $this->session->set_flashdata('message', $alert_message);
            //                 // redirect('/d_donemleri/kademelendirme_detay/'.$yil.'/'.$donem.'/'.$havuz_id);
            //                 // exit();
            //             }
            //             if(($y['data'][$minyid+2] + $y['data'][$minyid+1]) != $gradationData["data"][$mingid+2]){
            //                 $alert_message = alert_message('Olması gereken dağılım sayılarına uymalısınız.'.$dag_mes);

            //                 //var_dump($y['data'][$minyid+2]);
            //                 echo ($y['data'][$minyid+2] + $y['data'][$minyid+1])."!=".$gradationData["data"][$mingid+2]."<br>";
            //                 // $this->session->set_flashdata('message', $alert_message);
            //                 // redirect('/d_donemleri/kademelendirme_detay/'.$yil.'/'.$donem.'/'.$havuz_id);
            //                 // exit();
            //             }
                    
                       

            //         //}

            //     }
            // }
        //}

        
            
     



      //exit();
        
        
    //     if ($k_pernr != 100000 && $k_pernr != 100429)
    //     {
    //         if ($sayac > 4)
    //         {
    //             if ($y_kisi_sayisi_bcu != $kisi_sayisi_bcu)
    //             {
    //                 $alert_message = alert_message('Olması gereken dağılım sayılarına uymalısınız.'.$dag_mes);
    //                 $this->session->set_flashdata('message', $alert_message);
    //                 redirect('/d_donemleri/kademelendirme_detay/'.$yil.'/'.$donem.'/'.$havuz_id);
    //                 exit();
    //             }
    //             if ($y_kisi_sayisi_bu != $kisi_sayisi_bu)
    //             {
    //                 $alert_message = alert_message('Olması gereken dağılım sayılarına uymalısınız.'.$dag_mes);
    //                 $this->session->set_flashdata('message', $alert_message);
    //                 redirect('/d_donemleri/kademelendirme_detay/'.$yil.'/'.$donem.'/'.$havuz_id);
    //                 exit();
    //             }
    //             if ($y_kisi_sayisi_b != $kisi_sayisi_b)
    //             {
    //                 $alert_message = alert_message('Olması gereken dağılım sayılarına uymalısınız.'.$dag_mes);
    //                 $this->session->set_flashdata('message', $alert_message);
    //                 redirect('/d_donemleri/kademelendirme_detay/'.$yil.'/'.$donem.'/'.$havuz_id);
    //                 exit();
    //             }
    //             if ($y_kisi_sayisi_ba != $kisi_sayisi_ba)
    //             {
    //                 $alert_message = alert_message('Olması gereken dağılım sayılarına uymalısınız.'.$dag_mes);
    //                 $this->session->set_flashdata('message', $alert_message);
    //                 redirect('/d_donemleri/kademelendirme_detay/'.$yil.'/'.$donem.'/'.$havuz_id);
    //                 exit();
    //             }
    //         }
    // // //        else if ($sayac == 4)
    // // //        {
    // // //            if ($y_kisi_sayisi_bu+$y_kisi_sayisi_bcu != $kisi_sayisi_bu)
    // // //            {
    // // //                $alert_message = alert_message('Olması gereken dağılım sayılarına uymalısınız.'.$dag_mes);
    // // //                $this->session->set_flashdata('message', $alert_message);
    // // //                redirect('/d_donemleri/kademelendirme_detay/'.$yil.'/'.$donem.'/'.$havuz_id);
    // // //                exit();
    // // //            }
    // // //            if ($y_kisi_sayisi_b != $kisi_sayisi_b)
    // // //            {
    // // //                $alert_message = alert_message('Olması gereken dağılım sayılarına uymalısınız.'.$dag_mes);
    // // //                $this->session->set_flashdata('message', $alert_message);
    // // //                redirect('/d_donemleri/kademelendirme_detay/'.$yil.'/'.$donem.'/'.$havuz_id);
    // // //                exit();
    // // //            }
    // // //            if ($y_kisi_sayisi_ba != $kisi_sayisi_ba)
    // // //            {
    // // //                $alert_message = alert_message('Olması gereken dağılım sayılarına uymalısınız.'.$dag_mes);
    // // //                $this->session->set_flashdata('message', $alert_message);
    // // //                redirect('/d_donemleri/kademelendirme_detay/'.$yil.'/'.$donem.'/'.$havuz_id);
    // // //                exit();
    // // //            }
    // // //        }
            
    
    //         else if ($sayac == 3 || $sayac == 4)
    //         {
    //             if ($y_kisi_sayisi_bu+$y_kisi_sayisi_bcu != $kisi_sayisi_bu)
    //             {
    //                 $alert_message = alert_message('Olması gereken dağılım sayılarına uymalısınız.'.$dag_mes);
    //                 $this->session->set_flashdata('message', $alert_message);
    //                 redirect('/d_donemleri/kademelendirme_detay/'.$yil.'/'.$donem.'/'.$havuz_id);
    //                 exit();
    //             }
    //             if ($y_kisi_sayisi_b+$y_kisi_sayisi_ba != $kisi_sayisi_b)
    //             {
    //                 $alert_message = alert_message('Olması gereken dağılım sayılarına uymalısınız.'.$dag_mes);
    //                 $this->session->set_flashdata('message', $alert_message);
    //                 redirect('/d_donemleri/kademelendirme_detay/'.$yil.'/'.$donem.'/'.$havuz_id);
    //                 exit();
    //             }
    //         }

    //     }
        
        
        unset($kosul);
        $kosul['yil'] = $yil;
        $kosul['donem'] = $donem;
        $kosul['havuz_id'] = $havuz_id;

        //exit();

        // var_dump($siralama);
        // exit();
        $r = $this->genel_model->veri_kaydet_cs("kademelendirme_sonuclari", $siralama, $kosul);

        if (!$r)
        {
            $alert_message = alert_message('Teknik bir aksaklık oluştu daha sonra tekrar deneyiniz.');
            $this->session->set_flashdata('message', $alert_message);
            redirect('/d_donemleri/kademelendirme_detay/'.$yil.'/'.$donem.'/'.$havuz_id);
            exit();
        }

        $alert_message = alert_message('Kayıt Başarılı.', 'success');
        $this->session->set_flashdata('message', $alert_message);
        redirect('/d_donemleri/kademelendirme_detay/'.$yil.'/'.$donem.'/'.$havuz_id);
        exit();
    }


   

    public function getPotansiyelDegerlendirme($puan,$donem_id,$is_high_potential){
        $data = $this->d_donemleri_model->potansiyel_degerlendirme($puan,$donem_id,$is_high_potential);
        $data = $data[0];
        return $data;
    }

    public function getSelectData($id){

        
       $data = $this->d_donemleri_model->getKriterNames($id);

       
       $result =[];
      // potaniyel_degerlendirme.id AS pot_id,kriter.id AS kriter_id,MIN,MAX,STATUS,donem_id,NAME,tus_kuralları.*
       foreach($data as $key=>$val){
            
            $result[$val["potansiyel_degerlendirme_id"]]["kriter_id"] =$val["kriter_id"];
            $result[$val["potansiyel_degerlendirme_id"]]["pot_id"] =$val["pot_id"];
            $result[$val["potansiyel_degerlendirme_id"]]["kriter_id"] =$val["kriter_id"];
            $result[$val["potansiyel_degerlendirme_id"]]["min"] =$val["MIN"];
            $result[$val["potansiyel_degerlendirme_id"]]["max"] =$val["MAX"];
            $result[$val["potansiyel_degerlendirme_id"]]["status"] =$val["STATUS"];
            $result[$val["potansiyel_degerlendirme_id"]]["donem_id"] =$val["donem_id"];
            $result[$val["potansiyel_degerlendirme_id"]]["name"] =$val["NAME"];

            $result[$val["potansiyel_degerlendirme_id"]]["buttons"][] =[
                "button_id"=>$val["tus_id"],
                "button_name"=>$val["button_name"],
                "button_state"=>$val["button_state"]
            ];

               
            
       }

       $result = array_values($result);
       
       echo json_encode($result);
        

    }

    public function getDonemId($donem,$yil){
        $data = $this->d_donemleri_model->getIdDonem($donem,$yil);
        $data = $data[0];
        
        return $data["id"];
        
    }


    public function addNewCriteria(){

        $data = $this->input->post("data");
        $result = $this->d_donemleri_model->addCriteria($data);
        $res = [
            "success"=>$result
        ];
        echo json_encode($res);

    }

    public function updateCriteria(){
        $data = $this->input->post("data");

        $result = $this->d_donemleri_model->updateCriteria($data);

        $data = [
            "success"=>$result
        ];

        

        echo json_encode($data);



    }

    public function deleteCriteria($id){
        $result = $this->d_donemleri_model->deleteCriter($id);
        $data = [
            "success"=>$result,
        ];
        echo json_encode($data);
    }


    public function deleteButton($id){
        $result = $this->d_donemleri_model->deleteButtonById($id);
        $data = [
            "success"=>$result,
        ];
        echo json_encode($data);
    }



    


    public function getButtons($id){
        $data = $this->d_donemleri_model->getAllButtons($id);
        return $data;
    }





    public function test(){
        $this->template->goster('test', $data);

    }

    function calculateMedian($a,$count_duzeyler) { 
        //sort($a);
        $c = count($a);
        
        if($c == 3){
            $plus = $count_duzeyler - $c;
            $c+= $plus;
        }
        if($c == 2){
          
            $plus = $count_duzeyler - $c;
            $c+= $plus;
        }
        
        $m = floor(($c-1)/2);
        return floor(($c % 2) ? $a[$m] : (($a[$m]+$a[$m+1])/2)); // aşağı round etsin diye
    }

    
    public function checkPotential($pot_formu,$donem,$yil){

        $count_duzeyler = $this->d_donemleri_model->getHavuzPerformansDuzeyleriCount($donem,$yil);
        
        $data = $this->d_donemleri_model->checkHighPotential($pot_formu);

        $data2 =  array_keys($count_duzeyler);
        $new_data = [];
        foreach($data2 as $key => $val){
            $new_data[] = $val+1; // key arrtırdım 
        }
        $median = $this->calculateMedian($new_data,$count_duzeyler);
        // var_dump($median);
        // exit();
        
        $key = array_search($median, $new_data);
        $output = array_slice($new_data,0,$key+1); 
        // var_dump($output);
        // exit();
        
       
        $boolean = true;
        foreach($data as $value){
            if(array_search($value["duzey"],$output)){
                $boolean = false;
                break;
            }
            // if($value["duzey"] == 2 || $value["duzey"] == 1 ){
            //    $boolean = false;
            //     break;
            // }
        }

       
       return $boolean;

    }


    // public function doneme_kademe_ekle(){
    //     $this->template->goster('kademelendirme_detay', $data);
    // }


    // public function pools(){
    //   echo json_encode($this->gradation_key->pools());
    //   //var_dump($this->gradation_key->pools());
    // }

    public function getPeriodsDates(){
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($this->gradation_key->getPeriodsDates());
    }

    public function gradation_table_by_pool_id($year,$period){
        header('Content-Type: application/json; charset=utf-8');
        $data["data"] =[];
        if(empty($year) && empty($period)){
            $data["error"] = ["status"=>true,"message"=>"Parametre Eksik"];
            echo json_encode($data);
            exit();
        }
        $pool_levels = $this->gradation_key->pool_levels_with_join($year,"",$period,$person_count = "",$orderable=1);
        $data["error"] = ["status"=>false,"message"=>"Data Yok"];
        if(!empty($pool_levels)){
            $data["error"] = false;
            $data["data"]["columns"][1]["value"][] = NULL;
            $data["data"]["columns"][1]["kisi_sayisi"] = 1;
            $data["data"]["columns"][2]["value"][] = NULL;
            $data["data"]["columns"][2]["kisi_sayisi"] = 2;
            foreach ($pool_levels  as $value){
                $data["data"]["titles"][$value->id] = ["id"=>$value->id,"name"=>$value->level];
                $data["data"]["columns"][$value->count_person]["value"][] = $value->value;
                $data["data"]["columns"][$value->count_person]["kisi_sayisi"] = $value->count_person;
            }
            $data["data"]["columns"] = array_values($data["data"]["columns"]);
            $data["data"]["titles"] = array_values($data["data"]["titles"]);
            
        }
        echo json_encode($data,JSON_PRETTY_PRINT);
    }


    public function yeni_kademelendirme(){
        $this->template->goster('yeni_kademelendirme_olusturma');
    }

    public function havuz_kademelendirme_atama(){
        $this->template->goster('havuz_kademelendirme_atama');
    }

    public function getKeysByYeardAndPersonCount(){
        header('Content-Type: application/json; charset=utf-8');
        $data = json_decode(file_get_contents('php://input'), true);
        if(!isset($data["year"]) && !isset($data["is_over"])){
            $message = "Year ve is_over parametreleri eksiktir";
            echo  json_encode(["error"=>true,"message"=>$message]);
            exit();
        }

        $response = $this->d_donemleri_model->getKeysByYeardAndPersonCount($data["year"],$data["is_over"]);
        echo  json_encode(["error"=>false,"data"=>$response]);
        exit();
    }

    public function getPoolsByYeardAndPersonCount(){
        header('Content-Type: application/json; charset=utf-8');
        $data = json_decode(file_get_contents('php://input'), true);
        if(!isset($data["year"]) && !isset($data["is_over"])){
            $message = "Year ve is_over parametreleri eksiktir";
            echo  json_encode(["error"=>true,"message"=>$message]);
            exit();
        }
        
        $pools = $this->d_donemleri_model->getPoolsByYeardAndPersonCount($data["year"],$data["is_over"]);
        $assinged_pools = $this->d_donemleri_model->getAssingedPools($data["year"],$data["is_over"],$data["idkey"]);

        echo  json_encode(["error"=>false,"result"=>["pools"=>$pools,"assinged_pools"=>$assinged_pools]]);
        exit();
    }



    public function assingKeyToPool(){
        header('Content-Type: application/json; charset=utf-8');
        $data = json_decode(file_get_contents('php://input'), true);
        if(!isset($data["havuz_ids"]) && !isset($data["idda"]) && !isset($data["year"])){
            $message = "Parametreleri eksiktir";
            echo  json_encode(["error"=>true,"message"=>$message]);
            exit();
        }
        
        $result = $this->d_donemleri_model->assingKeyToPool($data);
        echo  json_encode(["error"=>false,"result"=>$result]);
        exit();
    }


   

    // public function yeni_kademelendirme_ekle(){
    //     header('Content-Type: application/json; charset=utf-8');
    //     $data = json_decode(file_get_contents('php://input'), true);

    //     if(!isset($data["yil"]) && !isset($data["donem"])){
    //         $message = "Yil ve dönem parametreleri eksiktir";
    //         echo  json_encode(["error"=>true,"message"=>$message]);
    //         exit();
    //     }

    //     $message ="";
    //     $is_exist = $this->gradation_key->yearAndPeriodisExist($data["yil"],$data["donem"]);
    //     if(!$is_exist){
    //         $insertedIdList = $this->gradation_key->createLevelsWithYearAndPeriod($data);
    //         if(!empty($insertedIdList)){
    //             if($data["isForTen"]){
    //                 foreach($data["levels"] as $key => $level){
    //                     $data["levels"][$key] = $insertedIdList[$key];
    //                 }
    //                 $isLevelsCreated = $this->gradation_key->populateLevelValues($data);
    //                 if($isLevelsCreated){
    //                     $error = false;
    //                     $message ="Islem basarılı";
    //                 }
    //                 else{
    //                    $error = true;
    //                    $message ="Bir sorun ile karsılasıldı!";
    //                 }
    //             }
    //             else{
    //                 $error = false;
    //                 $message ="Islem basarılı";
    //             }
    //         }
    //     }
    //     else{
    //         $error = true;
    //         $message ="Secmis oldugunuz tarihlerde zaten kademelendirme bulunmaktadir!";
    //     }
        
    //     echo  json_encode(["error"=>$error,"message"=>$message]);
    // }


    public function yeni_kademelendirme_ekle(){
        header('Content-Type: application/json; charset=utf-8');
        $data = json_decode(file_get_contents('php://input'), true);

        if(!isset($data["yil"]) && !isset($data["donem"])){
            $message = "Yil ve dönem parametreleri eksiktir";
            echo  json_encode(["error"=>true,"message"=>$message]);
            exit();
        }
        $message ="";
        // $is_exist = $this->gradation_key->yearAndPeriodisExist($data["yil"],$data["donem"]);
        $is_exist = $this->gradation_key->anahtarNameisExist($data["kademelendirme_ismi"]);

        if(!$is_exist){
            $insertedIdList = $this->gradation_key->createLevelsWithYearAndPeriod($data);
            //var_dump($insertedIdList);
            if(!empty($insertedIdList)){
                if($data["isForTen"]){
                    foreach($data["levels"] as $key => $level){
                        $data["levels"][$key] = $insertedIdList[$key];
                    }
                    $isLevelsCreated = $this->gradation_key->populateLevelValues($data);
                    if($isLevelsCreated){
                        $error = false;
                        $message ="Islem basarılı";
                    }
                    else{
                       $error = true;
                       $message ="Bir sorun ile karsılasıldı!";
                    }
                }
                else{
                    $error = false;
                    $message ="Islem basarılı";
                }
            }
        }
        else{
            $error = true;
            $message ="Girmiş oldugunuz  kademelendirme ismi seçmiş olduğunuz tarihlerde zaten bulunmaktadir!";
        }
        
        echo  json_encode(["error"=>$error,"message"=>$message]);
    }



    public function kademelendirme_yonetim_onay(){
       $this->template->goster('kademelendirme_yonetim_onay');
    }

    public function kademelendirme_havuzlari_data(){
        header('Content-Type: application/json; charset=utf-8');
        //$data = json_decode(file_get_contents('php://input'), true);
        $data= $_POST;
        if(!isset($data["yil"]) && !isset($data["donem"])){
            $message = "Yil ve dönem parametreleri eksiktir";
            echo  json_encode(["error"=>true,"message"=>$message]);
            exit();
        }
        $data = ["data" => $this->gradation_key->kademelendirme_havuzlari($data["yil"],$data["donem"])];
        echo json_encode($data);
    }



    // test amaçlı excelden dbye data basılmıştır (robin)
    public function excel_to_puanlar(){
        $this->load->library('excel'); 
        $this->load->model("atamalar_model");  

        $reader= PHPExcel_IOFactory::createReader('Excel2007');
        $reader->setReadDataOnly(true);

        $file_path = 'assets/tester/test.xlsx';
        $excel=$reader->load($file_path);

        $objWorksheet = $excel->getActiveSheet();
        $header=true;
        
        $highestRow = $objWorksheet->getHighestRow();
        $highestColumn = $objWorksheet->getHighestColumn();
        $headingsArray = $objWorksheet->rangeToArray('A1:' . $highestColumn . '1', null, true, true, true);
        $headingsArray = $headingsArray[1];
        $r = -1;
        $namedDataArray = array();
        for ($row = 3; $row <= $highestRow; ++$row) {
            $dataRow = $objWorksheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, null, true, true, true);

            $namedDataArray[] = [
                "pernr" => $dataRow[$row]["A"],
                "yil"=> 2021,
                "puan"=>$dataRow[$row]["E"]
            ];
            
        }
        

        // echo '<pre>';
        // print_r($namedDataArray);
        // exit();

        $this->atamalar_model->populatePuanlar($namedDataArray);
 
        
        
    }

    
    public function BirOncekiYilVeDonemiBul($yil,$donem)
    {
        $data = null;
        $diff = 0;

        if($donem == 2)
        {
            $data['onceki_donem'] = 3;
            $diff = 31536000;
        }
        elseif($donem == 3)
        {
            $data['onceki_donem'] = 2;
            $diff = 0;
        }
        else
        {
            $alert_message = alert_message('Donemler Sadece 2 veya 3 olabilir');
            $this->session->set_flashdata('message', $alert_message);
            redirect('/d_donemleri/takvim/'.$yil.'/'.$donem);
            exit();
        }

        $current_date = date("Y-m-d",strtotime("$yil-01-01"));


        $previous_date = strtotime($current_date) - $diff;

        $data["onceki_yil"] = date("Y",$previous_date); 

        //print_r($data);

        //$donem_listesi = $this->genel_model->veri_cek_ts("donem_listesi",$data);

        
        return $data;

    }



                                                //CEM                                          //Gamza                  //Banu
    public function form_goruntule($form_id, $rilen_pernr, $yil, $donem, $ft_id, $hash = '', $rilen_ust_pernr='' , $rilen_ust_ust_pernr='')
    {
        $create_button = false;
        $data = null;
        $data_for = null;
        if (empty_f($form_id) || empty_f($rilen_pernr) || empty_f($yil) || empty_f($donem) || empty_f($ft_id))
        {
            $alert_message = alert_message('Hata.');
            $this->session->set_flashdata('message', $alert_message);
            redirect('./home/');
            exit();
        }

        // Kişinin kendisi veya yöneticisi veya iki üst yönetici değilse hata ver..
        unset($kosul);
        $select = '*';
        $kosul['yil'] = $yil;
        $kosul['donem'] = $donem;
        $kosul['pernr'] = $rilen_pernr;
        $yon1 = $this->genel_model->veri_cek_ts("organizasyon_d", $select, $kosul);

        //echo "<pre>";
        $donem_listesi = $this->BirOncekiYilVeDonemiBul($yil,$donem);
        //print_r($donem_listesi);


        if($donem_listesi)
        {
            $data_for["yil"] = $donem_listesi["onceki_yil"];
            $data_for["donem"] = $donem_listesi["onceki_donem"];
            //$data_for['form_id'] = $form_id;
            $data_for['pernr'] = $rilen_pernr;
            $data_for['ft_id'] = $ft_id;
            //SELECT * FROM form_atamalari WHERE pernr = 102576 AND yil = 2022 AND donem = 3 AND ft_id = 1
            $previous_data_for_resource = $this->db->select("*")->where($data_for)->get("form_atamalari");
            //echo $this->db->last_query();
            //echo "<pre>";
            //print_r($previous_data_for_resource->result_array());
            

            
            if($previous_data_for_resource->num_rows() > 0)
            {
                $create_button = true;  
                $data_of_yil_donem = $previous_data_for_resource->result_array();
                $data_for["form_id"] = $data_of_yil_donem[0]["form_id"];
                $data_for["hash"] = hash('crc32b',$this->session->userdata['pernr'] * 100); 
                $data["button_link_items"] = $data_for;
            }
            //print_r($data);

            //exit();

        }

        $data["create_button"] = $create_button;
        

        
        if (!is_array($yon1))
        {
            $alert_message = alert_message('Hata.');
            $this->session->set_flashdata('message', $alert_message);
            redirect('./home/');
            exit();
        }
        
        unset($kosul);
        
        /*$select = '*';
        $kosul['yil'] = $yil;
        $kosul['donem'] = $donem;
        $kosul['pernr'] = $yon1['ust_pernr'];
        $yon2 = $this->genel_model->veri_cek_ts("organizasyon_d", $select, $kosul);
        
        echo $yon1['ust_pernr'];
        echo "<br />";
        print_r($yon2);
        exit();*/
        
        $select = '*';

        if(!empty($rilen_ust_pernr))
        {
            $kosul['yil'] = $data_for['yil'];
            $kosul['donem'] =  $data_for['donem'];
            $kosul['pernr'] = $rilen_ust_pernr;
        }
        else
        {
            $kosul['yil'] = $yil;
            $kosul['donem'] = $donem;
            $kosul['pernr'] = $yon1['ust_pernr'];
        }


        $yon2 = $this->genel_model->veri_cek_ts("organizasyon_d", $select, $kosul);
        

        if(empty($hash) && $hash != hash('crc32b',$this->session->userdata['pernr'] * 100))
        {
            
        
            if ($rilen_pernr != $this->session->userdata['pernr']
            &&  $yon1['ust_pernr'] != $this->session->userdata['pernr']
            &&  $yon2['ust_pernr'] != $this->session->userdata['pernr']
            )
            {
                
                    $alert_message = alert_message('Bu formu görüntüleme yetkiniz yok.'.__LINE__);
                    $this->session->set_flashdata('message', $alert_message);
                    redirect('/d_donemleri/takvim/'.$yil.'/'.$donem);
                    exit();
                
            }
        
        }
        
        
        // Potansiyel ve yönlendirme, görüşme formunu sadece yöneticiler görüntüler
        if ($rilen_pernr == $this->session->userdata['pernr'] && ($ft_id == 3 || $ft_id == 5 || $ft_id == 4))
        {
            $alert_message = alert_message('Bu formu görüntüleme yetkiniz yok.'.__LINE__);
            $this->session->set_flashdata('message', $alert_message);
            redirect('/d_donemleri/takvim/'.$yil.'/'.$donem);
            exit();
        }
        
        // Form bilgilerini çek
        unset($kosul);
        $select = '*';
        $kosul['form_id'] = $form_id;
        $form = $this->genel_model->veri_cek_ts("formlar", $select, $kosul);

        if (!is_array($form))
        {
            $alert_message = alert_message('Hata.');
            $this->session->set_flashdata('message', $alert_message);
            redirect('./home/');
            exit();
        }
        
        unset($kosul);
        $select = '*';
        $kosul['yil'] = $yil;
        $kosul['donem'] = $donem;
        $donem_listesi = $this->genel_model->veri_cek_ts("donem_listesi", $select, $kosul);
        
        if (!is_array($donem_listesi))
        {
            $alert_message = alert_message('Hata.');
            $this->session->set_flashdata('message', $alert_message);
            redirect('./home/');
            exit();
        }
        
        $tarih = date('Y-m-d');
        
        if ($tarih < $donem_listesi['bat'])
        {
            $alert_message = alert_message('Hata.');
            $this->session->set_flashdata('message', $alert_message);
            redirect('./home/');
            exit();
        }
        
        if ($tarih > $donem_listesi['bit'])
        {
            $data['degisiklik'] = false;
        }
        else
        {
            $data['degisiklik'] = true;
        }
        
      
        //Hedef formu ise, yarı dönemse ve tüm göstergeler yıllıksa değişikliğe kapat
        if (($ft_id == 1 || $ft_id == 2) && $donem == 2 && $data['degisiklik'])
        {
           
            //$form_detay = $this->d_donemleri_model->formlar_detay($form_id);

            $form_detay = $this->d_donemleri_model->formlar_detayv2($form_id);
 
            if (!is_array($form_detay))
            {
                $alert_message = alert_message('Form bulunamadı.');
                $this->session->set_flashdata('message', $alert_message);
                redirect('/d_donemleri/takvim/'.$yil.'/'.$donem);
                exit();
            }
            
            //periyotların hepsi yıllık mı ?
            $kontrol = true;
            foreach ($form_detay as $key=>$arr)
            {
                if (!$kontrol)
                {
                    break;
                }
                
                if ($arr['periyod'] != 1)
                {
                    $kontrol = false;
                }
            }
            
            if ($kontrol)
            {
                $data['degisiklik'] = false;
            }
            
            unset($kontrol);
        }
        
        //Kademelendiren kişi kim ? Eğer yönetici ise kademelendirme başlayınca
        // değişikliğe kapat eğer yöneticinin yöneticisi ise onaya gönderilince..
        // Kademelendirmesi gereken havuzları listele..
        unset($kosul);
        
        $select = '*';
        $kosul['yil'] = $yil;
        $kosul['donem'] = $donem;
        $kosul['pernr'] = $rilen_pernr;
        $kademelendiren = $this->genel_model->veri_cek_ts("kademelendirme_atamalari", $select, $kosul);
        
        /*
        if(empty($rilen_ust_pernr) || empty($rilen_ust_ust_pernr))
        {
            
            $kosul['yil'] = $yil;
            $kosul['donem'] = $donem;
        }
        else
        {
            $kosul['yil'] = $data_for['yil'];
            $kosul['donem'] = $data_for['donem'];   
        }

        $select = '*';
        $kosul['pernr'] = $rilen_pernr;
        $kademelendiren = $this->genel_model->veri_cek_ts("kademelendirme_atamalari", $select, $kosul);
        echo $this->db->last_query();
        echo "<pre>";
        print_r($kademelendiren);
        echo $kademelendiren['k_pernr'] ."==". $yon1['ust_pernr'];

        exit();*/
        

        if ($kademelendiren['k_pernr'] == $yon1['ust_pernr'])
        {
            if ($ft_id == 1 || $ft_id == 2)
            {
                // Kademelendirme süreci başlamışmı kontrol et başladı ise değişikliğe kapat
                unset($kosul);
                $select = '*';
                $kosul['yil'] = $yil;
                $kosul['donem'] = $donem;
                $kosul['degerlendiren'] = $yon1['ust_pernr'];
                $kosul['onaylayan'] = $yon1['ust_pernr'];
                $kosul['ft_id'] = $ft_id;
                $onaylar = $this->genel_model->veri_cek_ts("onaylar", $select, $kosul);

                if (is_array($onaylar))
                {
                    if ($onaylar['onay_durum'] == 1 || $onaylar['onay_durum'] == 4)
                    {
                        $data['degisiklik'] = false;
                    }
                }
            }
            else
            {
                // Onay süreci başladı mı kontrol et başladı ise değişikliğe kapat
                unset($kosul);
                $select = '*';
                $kosul['yil'] = $yil;
                $kosul['donem'] = $donem;
                $kosul['degerlendiren'] = $yon1['ust_pernr'];
                $kosul['onaylayan'] = $yon2['ust_pernr'];
                $kosul['ft_id'] = $ft_id;
                $onaylar = $this->genel_model->veri_cek_ts("onaylar", $select, $kosul);

                if (is_array($onaylar))
                {
                    if ($onaylar['onay_durum'] == 1 || $onaylar['onay_durum'] == 4)
                    {
                        $data['degisiklik'] = false;
                    }
                }
            }
        }
        else
        {
            // Onay süreci başladı mı kontrol et başladı ise değişikliğe kapat
            unset($kosul);
            $select = '*';
            $kosul['yil'] = $yil;
            $kosul['donem'] = $donem;
            $kosul['degerlendiren'] = $yon1['ust_pernr'];
            $kosul['onaylayan'] = $yon2['ust_pernr'];
            $kosul['ft_id'] = $ft_id;
            $onaylar = $this->genel_model->veri_cek_ts("onaylar", $select, $kosul);

            if (is_array($onaylar))
            {
                if ($onaylar['onay_durum'] == 1 || $onaylar['onay_durum'] == 4)
                {
                    $data['degisiklik'] = false;
                }
            }
        }
     
        
        $degerlendiren_ack = 'yon1';
        if ($rilen_pernr == $this->session->userdata['pernr'])
        {
            if ($ft_id == 2)
            {
                $degerlendiren_ack = 'rilen';
            }
            $data['who'] = 'rilen';
        }
        else if ($this->session->userdata['pernr'] == $yon1['ust_pernr'])
        {
            $data['who'] = 'yon1';
        }
        else if ($this->session->userdata['pernr'] == $yon2['ust_pernr'])
        {
            $data['who'] = 'yon2';
        }
        
        if ($form['ft_id'] == 2 && ($data['who'] == 'yon1' || $data['who'] == 'yon2')) // Yetkinlik formu ise ast içinde veri çek
        {
            unset($kosul);
            $select = '*';
            $kosul['yil'] = $yil;
            $kosul['donem'] = $donem;
            $kosul['form_id'] = $form_id;
            $kosul['pernr'] = $rilen_pernr;
            $kosul['degerlendiren_ack'] = 'rilen';
            $degerlendirme_ast = $this->genel_model->veri_cek_cs("degerlendirmeler", $select, $kosul);
            
            if (is_array($degerlendirme_ast))
            {
                $ast_puan1 = 0;
                foreach ($degerlendirme_ast as $key=>$arr)
                {
                    $ast_puan1 = $ast_puan1 + $arr['puan'];
                    $data['gd_ast'][$arr['g_id']][$arr['periyod']] = $arr['duzey'];
                }
            }
            
            if ($donem == 3) // Yılsonu ise yarıyılıda çek
            {
                unset($kosul);
                $select = '*';
                $kosul['yil'] = $yil;
                $kosul['donem'] = 2;
                $kosul['form_id'] = $form_id;
                $kosul['pernr'] = $rilen_pernr;
                $kosul['degerlendiren_ack'] = 'rilen';
                $degerlendirme_ast = $this->genel_model->veri_cek_cs("degerlendirmeler", $select, $kosul);

                if (is_array($degerlendirme_ast))
                {
                    $ast_puan2 = 0;
                    foreach ($degerlendirme_ast as $key=>$arr)
                    {
                        $ast_puan2 = $ast_puan2 + $arr['puan'];
                        $data['gd_ast'][$arr['g_id']][$arr['periyod']] = $arr['duzey'];
                    }
                }
            }
            
            if ($donem == 2)
            {
                $ast_puan = $ast_puan1;
            }
            else if ($donem == 3)
            {
                $ast_puan = ($ast_puan1 + $ast_puan2);
            }
        }
        
        if ($ft_id == 5) // Yönlendirme formu ise kişiye atanan görüşme formunu çek.
        {
            // Kendine atanmış formlar
            unset($kosul);
            $select = '*';
            $kosul['yil'] = $yil;
            $kosul['donem'] = $donem;
            $kosul['pernr'] = $rilen_pernr;
            $kosul['ft_id'] = 4;
            $gorusme_formu = $this->genel_model->veri_cek_ts("form_atamalari", $select, $kosul);
            if (!is_array($gorusme_formu))
            {
                $alert_message = alert_message('Hata. Görüşme formu atanmamış.');
                $this->session->set_flashdata('message', $alert_message);
                redirect('./home/');
                exit();
            }

            unset($kosul);
            $select = '*';
            $kosul['yil'] = $yil;
            $kosul['donem'] = $donem;
            $kosul['pernr'] = $rilen_pernr;
            $kosul['ft_id'] = 3;
            $pot_formu = $this->genel_model->veri_cek_ts("form_atamalari", $select, $kosul);

            

            //            if (!is_array($pot_formu))
            //            {
            //                $alert_message = alert_message('Hata. Pot formu atanmamış.');
            //                $this->session->set_flashdata('message', $alert_message);
            //                redirect('./home/');
            //                exit();
            //            }

                        //$form_id = $gorusme_formu['form_id'];
            

           
            if (is_array($pot_formu))
            {
                $data['pot_varmi'] = 1;
            }
            else
            {
                $data['pot_varmi'] = 2;
            }

            if ($data['pot_varmi'] == 1)
            {

               
                // Kişinin potansiyel puanını çek..
                unset($kosul);
                $select = '*';
                $kosul['yil'] = $yil;
                $kosul['donem'] = $donem;
                $kosul['form_id'] = $pot_formu['form_id'];
                $kosul['pernr'] = $rilen_pernr;
                $kosul['degerlendiren_ack'] = $degerlendiren_ack;
                $degerlendirme = $this->genel_model->veri_cek_cs("degerlendirmeler", $select, $kosul);


               
                $puan = 0;
                if (is_array($degerlendirme))
                {
                    $puan = 0;
                    foreach ($degerlendirme as $key=>$arr)
                    {
                        $puan = $puan + $arr['puan'];
                    }
                }
                else
                {
                    $data['degisiklik'] = false;
                }

             
                //$puan = 20;
                $data['pot_puan'] = $puan;
                $donem_id = $this->getDonemId($donem,$yil);
                //echo $donem_id;
                //$donem_id=27;

                //tuşları da  ona göre gelecek

                //Çalışanın “Yüksek Potansiyel” kademesinde değerlendirilebilmesi için, 
                //değerlendirildiği potansiyel formundaki maddelerin en az “Beklenen Düzeyin Üstü (3)” kademesinde olması gerekmektedir.
              
                $is_high_potential = true ;
                if($yil > 2020){
                    $is_high_potential = $this->checkPotential($pot_formu,$donem,$yil);
                } 
                $data['pot_degerlendirme_data'] = $this->getPotansiyelDegerlendirme($puan,$donem_id,$is_high_potential);   // degisim var 

                // var_dump($data['pot_degerlendirme_data']);
                // exit();
              
                $data['pot_degerlendirme_data']["buttons"] = $this->getButtons($data['pot_degerlendirme_data']["pot_id"]);
                // var_dump($data['pot_degerlendirme_data']);
                // exit();


            }


            //Görüşme doldurulmuşmu.
            unset($kosul);
            $select = '*';
            $kosul['yil'] = $yil;
            $kosul['form_id'] = $gorusme_formu['form_id'];
            $kosul['pernr'] = $rilen_pernr;
            $kosul['degerlendiren_ack'] = 'yon1';
            //$kosul['donem'] = $donem;
            $kayit = $this->genel_model->veri_cek_cs("degerlendirmeler", $select, $kosul);

            if (is_array($kayit))
            {
                foreach ($kayit as $key=>$arr)
                {
                    $data['aciklama2'][$arr['g_id']][$arr['periyod']] = $arr['aciklama'];
                }
            }
            else
            {
                $data['degisiklik'] = false;
            }

        }
        
        if ($donem == 3) // Yılsonu ise yarıyılıda çek
        {
            unset($kosul);
            $select = '*';
            $kosul['yil'] = $yil;
            $kosul['donem'] = 2;
            $kosul['form_id'] = $form_id;
            $kosul['pernr'] = $rilen_pernr;
            $kosul['degerlendiren_ack'] = $degerlendiren_ack;
            $degerlendirme = $this->genel_model->veri_cek_cs("degerlendirmeler", $select, $kosul);

            
     
            if (is_array($degerlendirme))
            {
                $puan2 = 0;
                foreach ($degerlendirme as $key=>$arr)
                {
                    if ($arr['planlanan'] != "")
                    {
                        $arr['planlanan'] = number_format($arr['planlanan'],2,',','');
                    }
                    if ($arr['gerceklesen'] != "")
                    {
                        $arr['gerceklesen'] = number_format($arr['gerceklesen'],2,',','');
                    }
                    if ($arr['sonuc'] != "")
                    {
                        $arr['sonuc'] = number_format($arr['sonuc'],2,',','');
                    }
                    
                    if ($arr['ft_id'] == 1 && $arr['urun'] != "") // Hedef ve ürün varsa
                    {

                        $puan2 = $puan2 + $arr['puan'];
                        $data['per_urun'][$arr['g_id']][$arr['periyod']][] = $arr['urun']; 
                        $data['gp'][$arr['g_id']][$arr['periyod']][$arr['urun']] = $arr['planlanan'];
                        $data['gg'][$arr['g_id']][$arr['periyod']][$arr['urun']] = $arr['gerceklesen'];
                        $data['gs'][$arr['g_id']][$arr['periyod']][$arr['urun']] = $arr['sonuc'];
                        $data['aciklama'][$arr['g_id']] = $arr['aciklama'];
                        $data['aciklama2'][$arr['g_id']][$arr['periyod']][$arr['urun']] = $arr['aciklama'];
                        $data['gd'][$arr['g_id']][$arr['periyod']][$arr['urun']] = $arr['duzey'];
                        $data['hp'] = $arr['hp'];
                    }
                    else
                    {
                        
                        $puan2 = $puan2 + $arr['puan'];
                        $data['gp'][$arr['g_id']][$arr['periyod']] = $arr['planlanan'];
                        $data['gg'][$arr['g_id']][$arr['periyod']] = $arr['gerceklesen'];
                        $data['gs'][$arr['g_id']][$arr['periyod']] = $arr['sonuc'];
                        $data['aciklama'][$arr['g_id']] = $arr['aciklama'];
                        $data['aciklama2'][$arr['g_id']][$arr['periyod']] = $arr['aciklama'];
                        $data['gd'][$arr['g_id']][$arr['periyod']] = $arr['duzey'];
                        $data['hp'] = $arr['hp'];
                    }
                }
            }
        }
        
        unset($kosul);
        $select = '*';
        $kosul['yil'] = $yil;
        $kosul['donem'] = $donem;
        $kosul['form_id'] = $form_id;
        $kosul['pernr'] = $rilen_pernr;
        $kosul['degerlendiren_ack'] = $degerlendiren_ack;
        $degerlendirme = $this->genel_model->veri_cek_cs("degerlendirmeler", $select, $kosul);

        

       
        
        if (is_array($degerlendirme))
        {
            $puan1 = 0;
            foreach ($degerlendirme as $key=>$arr)
            {
                if ($arr['planlanan'] != "")
                {
                    $arr['planlanan'] = number_format($arr['planlanan'],2,',','');
                }
                if ($arr['gerceklesen'] != "")
                {
                    $arr['gerceklesen'] = number_format($arr['gerceklesen'],2,',','');
                }
                if ($arr['sonuc'] != "")
                {
                    $arr['sonuc'] = number_format($arr['sonuc'],2,',','');
                }
                
               
                if ($arr['ft_id'] == 1 && $arr['urun'] != "") // Hedef ve ürün varsa
                {

                    $puan1 = $puan1 + $arr['puan'];
                    $data['per_urun'][$arr['g_id']][$arr['periyod']][] = $arr['urun']; 
                    $data['gp'][$arr['g_id']][$arr['periyod']][$arr['urun']] = $arr['planlanan'];
                    $data['gg'][$arr['g_id']][$arr['periyod']][$arr['urun']] = $arr['gerceklesen'];
                    $data['gs'][$arr['g_id']][$arr['periyod']][$arr['urun']] = $arr['sonuc'];
                    $data['aciklama'][$arr['g_id']] = $arr['aciklama'];
                    $data['aciklama2'][$arr['g_id']][$arr['periyod']][$arr['urun']] = $arr['aciklama'];
                    $data['gd'][$arr['g_id']][$arr['periyod']][$arr['urun']] = $arr['duzey'];
                    $data['hp'] = $arr['hp'];
                }
                else
                {
                    $puan1 = $puan1 + $arr['puan'];
                    $data['gp'][$arr['g_id']][$arr['periyod']] = $arr['planlanan'];
                    $data['gg'][$arr['g_id']][$arr['periyod']] = $arr['gerceklesen'];
                    $data['gs'][$arr['g_id']][$arr['periyod']] = $arr['sonuc'];
                    $data['aciklama'][$arr['g_id']] = $arr['aciklama'];
                    $data['aciklama2'][$arr['g_id']][$arr['periyod']] = $arr['aciklama'];
                    $data['gd'][$arr['g_id']][$arr['periyod']] = $arr['duzey'];
                    $data['hp'] = $arr['hp'];

                    
                }
            }
        }


        // var_dump($data['gd']);
        // exit();

        if ($donem == 2)
        {
            $puan = $puan1;
        }
        else if ($donem == 3)
        {
            $puan = ($puan1 + $puan2);
        }
        
        $data['form_baslik'] = $form;
        $data['donem_ack'] = $donem_listesi['donem_ack'];
        $data['rilen'] = $yon1;
        $data['ren'] = $yon2;
        $data['yil'] = $yil;
        $data['donem'] = $donem;
        $data['puan'] = $puan;
        $data['ast_puan'] = $ast_puan;
        
        if ($ft_id == 1) // Hedef formu
        {
            // Form detayınnı bul
            //$form_detay = $this->d_donemleri_model->formlar_detay($form_id);

            $form_detay = $this->formlar_detay_from_modal($form_id);

            if (!is_array($form_detay))
            {
                $alert_message = alert_message('Form bulunamadı.');
                $this->session->set_flashdata('message', $alert_message);
                redirect('/d_donemleri/takvim/'.$yil.'/'.$donem);
                exit();
            }
            
            $data['gostergeler'] =  $form_detay;
           


            // echo "<pre>";
            // print_r($data["gd"]);
            // exit();
            

            // var_dump($data['per_urun']);
            // exit();

            
            $this->template->goster('hedef_formu', $data);
            
        }
        else if ($ft_id == 2) // Yetkinlik Formu
        {
            // Form detayınnı bul
            //$form_detay = $this->d_donemleri_model->formlar_detay($form_id);

            $form_detay = $this->formlar_detay_from_modal($form_id);

            
            if (!is_array($form_detay))
            {
                $alert_message = alert_message('Form bulunamadı.');
                $this->session->set_flashdata('message', $alert_message);
                redirect('/d_donemleri/takvim/'.$yil.'/'.$donem);
                exit();
            }
            
            foreach ($form_detay as $key=>$arr)
            {
                $data['kategori_toplam'][$arr['kategori']] = $data['kategori_toplam'][$arr['kategori']] + $arr['agirlik']; 
            }
            
            $data['gostergeler'] = $form_detay;

            // echo "<pre>";
            // print_r($data);
            // exit();
            
            $this->template->goster('yetkinlik_formu', $data);
        }
        else if ($ft_id == 3) // Potansiyel Formu
        {

            
            // Form detayınnı bul
           //$form_detay = $this->d_donemleri_model->formlar_detay($form_id);

            $form_detay = $this->formlar_detay_from_modal($form_id);
            // var_dump( $data);
            // exit();
            
            if (!is_array($form_detay))
            {
                $alert_message = alert_message('Form bulunamadı.');
                $this->session->set_flashdata('message', $alert_message);
                redirect('/d_donemleri/takvim/'.$yil.'/'.$donem);
                exit();
            }
            
            foreach ($form_detay as $key=>$arr)
            {
                $data['kategori_toplam'][$arr['kategori']] = $data['kategori_toplam'][$arr['kategori']] + $arr['agirlik']; 
            }
            
            $data['gostergeler'] = $form_detay;

           
           
           
            $pot_formu=[
                "ft_id"=>$ft_id,
                "donem"=>$donem,
                "pernr"=>$rilen_pernr,
                "yil"=>$yil,
                "form_id" =>$form_id
            ];

            // var_dump( $pot_formu);
            // exit();
           
           // $pot_formu = $this->genel_model->veri_cek_ts("form_atamalari", $select, $kosul);

          
           $is_high_potential = true ;
           if($yil > 2020){
               $is_high_potential = $this->checkPotential($pot_formu,$donem,$yil); // DEGİŞECEK 
           } 


            
            $donem_id = $this->getDonemId($donem,$yil);
            $data['pot_degerlendirme_data'] = $this->getPotansiyelDegerlendirme($puan,$donem_id,$is_high_potential); // DEGİŞECEK 

            
            $metin = $this->d_donemleri_model->getPotansiyelInformationTextByYear($yil); // DEGİŞECEK 

            
            $data["metin"] ="";
            if(!empty($metin)){
                $data["metin"] = $metin[0]["metin"];
            }

            // var_dump($data['pot_degerlendirme_data'] );
            // exit();

           
            $this->template->goster('potansiyel_formu', $data);
        }
        else if ($ft_id == 4) // Görüşme Formu
        {
            // Form detayınnı bul
            //$form_detay = $this->d_donemleri_model->formlar_detay($form_id);

            
            $form_detay = $this->formlar_detay_from_modal($form_id);
            // var_dump($form_detay );
            // exit();


            
            if (!is_array($form_detay))
            {
                $alert_message = alert_message('Form bulunamadı.');
                $this->session->set_flashdata('message', $alert_message);
                redirect('/d_donemleri/takvim/'.$yil.'/'.$donem);
                exit();
            }
            
            $data['gostergeler'] = $form_detay;

            
            $this->template->goster('gorusme_formu', $data);
        }
        else if ($ft_id == 5) // Yönlendirme Formu
        {
            // Form detayınnı bul
            //$form_detay = $this->d_donemleri_model->formlar_detay($gorusme_formu['form_id']);

            $form_detay = $this->formlar_detay_from_modal($gorusme_formu['form_id']);

            // var_dump($form_detay );
            // exit();
            
            
            if (!is_array($form_detay))
            {
                $alert_message = alert_message('Form bulunamadı.');
                $this->session->set_flashdata('message', $alert_message);
                redirect('/d_donemleri/takvim/'.$yil.'/'.$donem);
                exit();
            }
            
            $data['gostergeler'] = $form_detay;

            $this->template->goster('yonlendirme_formu', $data);
        }
        
    }




    public function formlar_detay_from_modal($form_id){

        
        $data  =  $this->d_donemleri_model->formlar_detayv2($form_id);

       
        $arr = [];
        
        foreach($data as $modal){
            $arr[$modal['g_id']]['g_id'] = $modal['g_id'];
            $arr[$modal['g_id']]['form_id'] = $modal["ft_id"];
            $arr[$modal['g_id']]['ft_text'] = $modal["ft_text"];
            $arr[$modal['g_id']]['g_adi'] = $modal["g_adi"];
            $arr[$modal['g_id']]['periyod'] = $modal["periyod"];
            $arr[$modal['g_id']]['g_text'] = $modal["g_text"];
            $arr[$modal['g_id']]['g_desc'] = $modal["g_desc"];
            $arr[$modal['g_id']]['kategori'] = $modal["kategori"];
            $arr[$modal['g_id']]['planlanan_text'] = $modal["planlanan_text"];
            $arr[$modal['g_id']]['gerceklesen_text'] = $modal["gerceklesen_text"];
            $arr[$modal['g_id']]['sonuc_text'] = $modal["sonuc_text"];
            $arr[$modal['g_id']]['veri_kaynagi'] = $modal["veri_kaynagi"];
            $arr[$modal['g_id']]['veri_kaynagi_text'] = $modal["veri_kaynagi_text"];
            $arr[$modal['g_id']]['veri_hesaplama'] = $modal["veri_hesaplama"];
            $arr[$modal['g_id']]['veri_hesaplama_text'] = $modal["veri_hesaplama_text"];
            $arr[$modal['g_id']]['degerlendirme_turu'] = $modal["degerlendirme_turu"];
            $arr[$modal['g_id']]['degerlendirme_turu_text'] = $modal["degerlendirme_turu_text"];
            $arr[$modal['g_id']]['kmin_text'] = $modal["kmin_text"];
            $arr[$modal['g_id']]['kmax_text'] = $modal["kmax_text"];
            $arr[$modal['g_id']]['agirlik'] = $modal["agirlik"];

            if($modal["ft_id"] == 1){
                $arr[$modal['g_id']]["kademeler"][] = [
                    "kademe_text"=>$modal['kademe_text'],
                    "kademe_as"=>$modal['kademe_as'],
                    "kademe_us"=>$modal['kademe_us']
                ];
            }
            if($modal["ft_id"] == 2 || $modal["ft_id"] == 3 ){
                $arr[$modal['g_id']]["kademeler"][]= [
                    "kademe_text"=>$modal['kademe_text'],
                ];
            }
           
        }
        $arr = array_values($arr);

        return $arr;
    }





    public function form_kaydet(){

        $form_id = $this->input->post('form_id');
        $rilen_pernr = $this->input->post('rilen_pernr');
        $yil = $this->input->post('yil');
        $donem = $this->input->post('donem');
        $ft_id = $this->input->post('ft_id');
        
        if (empty_f($form_id) || empty_f($rilen_pernr) || empty_f($yil) || empty_f($donem) || empty_f($ft_id))
        {
            $alert_message = alert_message('Hata.');
            $this->session->set_flashdata('message', $alert_message);
            redirect('./home/');
            exit();
        }
        
        // Kişinin kendisi veya yöneticisi veya iki üst yönetici değilse hata ver..
        unset($kosul);
        $select = '*';
        $kosul['yil'] = $yil;
        $kosul['donem'] = $donem;
        $kosul['pernr'] = $rilen_pernr;
        $yon1 = $this->genel_model->veri_cek_ts("organizasyon_d", $select, $kosul);
        
        if (!is_array($yon1))
        {
            $alert_message = alert_message('Hata.');
            $this->session->set_flashdata('message', $alert_message);
            redirect('./home/');
            exit();
        }
        
        if ($rilen_pernr != $this->session->userdata['pernr'] 
        && $this->session->userdata['pernr'] != $yon1['ust_pernr'] 
        )
        {
            $alert_message = alert_message('Hata.');
            $this->session->set_flashdata('message', $alert_message);
            redirect('./home/');
            exit();
        }
        
        // Potansiyel ve yönlendirme formunu sadece yöneticiler görüntüler
        if ($rilen_pernr == $this->session->userdata['pernr'] && ($ft_id == 3 || $ft_id == 5 || $ft_id == 4))
        {
            $alert_message = alert_message('Hata.');
            $this->session->set_flashdata('message', $alert_message);
            redirect('./home/');
            exit();
        }
        
        // Form tipini bul
        unset($kosul);
        $select = '*';
        $kosul['form_id'] = $form_id;
        $form = $this->genel_model->veri_cek_ts("formlar", $select, $kosul);
        
        if (!is_array($form))
        {
            $alert_message = alert_message('Hata.');
            $this->session->set_flashdata('message', $alert_message);
            redirect('./home/');
            exit();
        }
        
        unset($kosul);
        $select = '*';
        $kosul['yil'] = $yil;
        $kosul['donem'] = $donem;
        $donem_listesi = $this->genel_model->veri_cek_ts("donem_listesi", $select, $kosul);
        
        if (!is_array($donem_listesi))
        {
            $alert_message = alert_message('Hata.');
            $this->session->set_flashdata('message', $alert_message);
            redirect('./home/');
            exit();
        }
        
        $tarih = date('Y-m-d');
        
        if ($tarih < $donem_listesi['bat'])
        {
            $alert_message = alert_message('Hata.');
            $this->session->set_flashdata('message', $alert_message);
            redirect('./home/');
            exit();
        }
        
        if ($tarih > $donem_listesi['bit'])
        {
            $alert_message = alert_message('Hata.');
            $this->session->set_flashdata('message', $alert_message);
            redirect('./home/');
            exit();
        }
        
        if ($rilen_pernr == $this->session->userdata['pernr'])
        {
            $who = "rilen";
            if ($ft_id == 2)
            {
                $degerlendiren_ack = 'rilen';
                $degerlendiren_pernr = $this->session->userdata['pernr'];
            }
            else 
            {
                $degerlendiren_ack = 'yon1';
                $degerlendiren_pernr = $yon1['ust_pernr'];
            }
        }
        else if ($this->session->userdata['pernr'] == $yon1['ust_pernr'])
        {
            $who = "yon1";
            $degerlendiren_ack = 'yon1';
            $degerlendiren_pernr = $this->session->userdata['pernr'];
        }
        
        // Değerlendirme sonuçlarını çek onaya gönderilmiş mi kontrol et.. 
        unset($kosul);
        $select = '*';
        $kosul['yil'] = $yil;
        $kosul['donem'] = $donem;
        $kosul['form_id'] = $form_id;
        $kosul['pernr'] = $rilen_pernr;
        $kosul['degerlendiren_ack'] = $degerlendiren_ack;
        $degerlendirme = $this->genel_model->veri_cek_cs("degerlendirmeler", $select, $kosul);

        

        if (is_array($degerlendirme))
        {
            foreach ($degerlendirme as $key=>$arr)
            {
                // Bu göstergenin verisi oto geliyor.
                unset($kosul);
                $select = "*";
                $kosul['g_id'] = $arr['g_id'];
                //$g_bilgi = $this->genel_model->veri_cek_ts("gostergeler", $select, $kosul);  
                $g_bilgi = $this->genel_model->veri_cek_ts("gostergelerv2", $select, $kosul);                         
                
                if ($g_bilgi['veri_kaynagi'] == 1)
                {
                    $silinmeyecek_gosterge[] = $arr['g_id'];
                    continue;
                }
                
                $degerlendirme2[$arr['g_id']][$arr['periyod']]['planlanan'] = $arr['planlanan'];
                $degerlendirme2[$arr['g_id']][$arr['periyod']]['gerceklesen'] = $arr['gerceklesen'];
                $degerlendirme2[$arr['g_id']][$arr['periyod']]['sonuc'] = $arr['sonuc'];
                $degerlendirme2[$arr['g_id']]['aciklama'] = $arr['aciklama'];
                $degerlendirme2[$arr['g_id']][$arr['periyod']]['duzey'] = $arr['duzey'];
                $degerlendirme2['hp'] = $arr['hp'];
            }
        }
        
        if ($form['ft_id'] == 1) // Hedef formu
        {
            // Form detayınnı bul
            //$form_detay = $this->d_donemleri_model->formlar_detay($form_id);

            $form_detay = $this->formlar_detay_from_modal($form_id);

            
            $planlanan = $this->input->post('gp');

            foreach ($planlanan as $key=>$arr)
            {
                foreach ($arr as $key1=>$val1)
                {
                    if ($val1 == "")
                    {
                        continue;
                    }
                    $val1 = str_replace(",",".",$val1);
                    $planlanan[$key][$key1] = number_format($val1,2,'.','');
                }
            }
            
            $gerceklesen = $this->input->post('gg');
            
            foreach ($gerceklesen as $key=>$arr)
            {
                foreach ($arr as $key1=>$val1)
                {
                    if ($val1 == "")
                    {
                        continue;
                    }
                    $val1 = str_replace(",",".",$val1);
                    $gerceklesen[$key][$key1] = number_format($val1,2,'.','');
                }
            }
            
            $g_sonuc = $this->input->post('gs');

            
            foreach ($g_sonuc as $key=>$arr)
            {
                foreach ($arr as $key1=>$val1)
                {
                    if ($val1 == "")
                    {
                        continue;
                    }
                    $val1 = str_replace(",",".",$val1);
                    $g_sonuc[$key][$key1] = number_format($val1,2,'.','');
                }
            }


            
            
            // yarıyılda bu form ataması var mı ?
            unset($kosul);
            $select = '*';
            $kosul['yil'] = $yil;
            $kosul['donem'] = 2;
            $kosul['form_id'] = $form_id;
            $kosul['pernr'] = $rilen_pernr;
            $form_atamalari = $this->genel_model->veri_cek_ts("form_atamalari", $select, $kosul);

            if (is_array($form_atamalari))
            {
                $yy_form_var = true;
            }
            else
            {
                $yy_form_var = false;
            }

            // Veya ilk açılışa özel 2. dönem verisi var mı oku
            if (!$yy_form_var && $yil == 2017)
            {
                unset($kosul);
                $select = '*';
                $kosul['yil'] = $yil;
                $kosul['donem'] = 2;
                $kosul['form_id'] = $form_id;
                $kosul['pernr'] = $rilen_pernr;
                $kosul['degerlendiren_ack'] = 'yon1';
                $form_atamalari2 = $this->genel_model->veri_cek_cs("degerlendirmeler", $select, $kosul);

                if (is_array($form_atamalari2))
                {
                    $yy_form_var = true;
                }
                else
                {
                    $yy_form_var = false;
                }
            }
            
            $aciklama = $this->input->post('rilen_ack');
            $duzey = $this->input->post('gd');
            $time = date("Y-m-d H:i:s");
            
            $sayac = 0;

            
            foreach ($form_detay as $key=>$arr)
            {
                if (in_array($arr['g_id'], $silinmeyecek_gosterge))
                {
                    //ürün ataması olanların açıklama alnı için
                    unset($kosul);
                    $kosul['g_id'] = $arr['g_id'];
                    $kosul['yil'] = $yil;
                    $kosul['donem'] = $donem;
                    $kosul['pernr'] = $rilen_pernr;
                    $kosul['degerlendiren_ack'] = $degerlendiren_ack;
                    $kosul['form_id'] = $form_id;
                    $veri_guncelle['aciklama'] = $aciklama[$arr['g_id']];
                    $veri_guncelle['guncelleyen'] = $this->session->userdata('pernr');
                    $veri_guncelle['g_time'] = $time;
                    $r = $this->genel_model->veri_guncelle("degerlendirmeler", $veri_guncelle, $kosul);
                    continue;
                }
                
                $periyod_sayisi[$arr['g_id']] = 0;
                $g_periyod_sayisi[$arr['g_id']] = $arr['periyod'];
                for ($i = 1; $i <= $arr['periyod']; $i++)
                {
                    $kayit_kontrol = true;  
                    if ($donem == 2)
                    {
                        $periyod_hesap = 12/$arr['periyod']*$i;
                        if ($periyod_hesap > 6) // Yarı yıl
                        {
                            continue;
                        }
                    }
                    else if ($donem == 3)
                    {
                        $periyod_hesap = 12/$arr['periyod']*$i;
                        if ($periyod_hesap <= 6) // Yılsonu
                        {
                            continue;
                        }
                    }
                    
                    $data[$sayac]['yil'] = $yil;
                    $data[$sayac]['donem'] = $donem;
                    $data[$sayac]['pernr'] = $rilen_pernr;
                    $data[$sayac]['degerlendiren_ack'] = $degerlendiren_ack;
                    $data[$sayac]['degerlendiren_pernr'] = $degerlendiren_pernr;
                    $data[$sayac]['form_id'] = $form_id;
                    $data[$sayac]['ft_id'] = $ft_id;
                    $data[$sayac]['g_id'] = $arr['g_id'];
                    $data[$sayac]['periyod'] = $i;

                   //var_dump($who,$arr['veri_kaynagi']);
                  
                    
                    
                    if ($who == "rilen" && $arr['veri_kaynagi'] == 2 )
                    {
                        if ($planlanan[$arr['g_id']][$i] != "" && $gerceklesen[$arr['g_id']][$i] != "" && $planlanan[$arr['g_id']][$i] != 0)
                        {
                            $data[$sayac]['planlanan'] = $planlanan[$arr['g_id']][$i];
                            $data[$sayac]['gerceklesen'] = $gerceklesen[$arr['g_id']][$i];

                            $sonuc = 0;
                            if ($arr['veri_hesaplama'] == 2) //Realizasyon
                            {
                                
                                $sonuc = 100 - (($data[$sayac]['planlanan'] - $data[$sayac]['gerceklesen'])/$data[$sayac]['planlanan']*100);
                            }
                            else if ($arr['veri_hesaplama'] == 3) // Sapma
                            {
                                
                                $sonuc = (($data[$sayac]['gerceklesen'] - $data[$sayac]['planlanan'])/$data[$sayac]['planlanan']*100);
                                if ($sonuc < 0)
                                {
                                    $sonuc = -1*$sonuc;
                                }

                            }
                            else if ($arr['veri_hesaplama'] == 4) // Sapma
                            {
                                $sonuc = (($data[$sayac]['gerceklesen'] - $data[$sayac]['planlanan'])/$data[$sayac]['planlanan']*100);
                            }

                            $data[$sayac]['sonuc'] = $sonuc;
                        }
                    }
                    else if ($who == "rilen" && $arr['veri_kaynagi'] == 4)
                    {
                        if ($g_sonuc[$arr['g_id']][$i] != "")
                        {
                            $data[$sayac]['sonuc'] = $g_sonuc[$arr['g_id']][$i];
                        }
                    }
                    else
                    {
                        $data[$sayac]['planlanan'] = $degerlendirme2[$arr['g_id']][$i]['planlanan'];
                        $data[$sayac]['gerceklesen'] = $degerlendirme2[$arr['g_id']][$i]['gerceklesen'];
                        $data[$sayac]['sonuc'] = $degerlendirme2[$arr['g_id']][$i]['sonuc'];
                    }
                    
                    if ($who == "rilen")
                    {
                        $data[$sayac]['aciklama'] = $aciklama[$arr['g_id']];
                    }
                    else
                    {
                        $data[$sayac]['aciklama'] = $degerlendirme2[$arr['g_id']]['aciklama'];
                    }

                    //var_dump($data[$sayac]['sonuc']);
                   // exit();
                    
                    // exit();

                    if ($who == 'rilen')
                    {
                        if ($arr['degerlendirme_turu'] == '2') // Objektif
                        {
                           if (isset($data[$sayac]['sonuc'])){
                            
                                foreach($arr["kademeler"] as $kademe_key => $kdm){
                                    
                                   //echo ($kademe_key+1)." ".$data[$sayac]['sonuc'] ."  >= ". $kdm['kademe_as']. " && ".$data[$sayac]['sonuc']." <= ".$kdm['kademe_us']."<br>";
                                    if($data[$sayac]['sonuc'] >= $kdm['kademe_as'] && $data[$sayac]['sonuc'] <= $kdm['kademe_us'] && $kdm['kademe_as'] != '' && $kdm['kademe_us'] != ''){
                                        $data[$sayac]['duzey'] = ($kademe_key+1);
                                    }
                                }
                               // echo "<hr>";
                              
                                // if ($data[$sayac]['sonuc'] >= $arr['k1_as'] && $data[$sayac]['sonuc'] <= $arr['k1_us'] && $arr['k1_as'] != '' && $arr['k1_us'] != '')
                                // {
                                //     $data[$sayac]['duzey'] = 1;
                                // }
                                // else if ($data[$sayac]['sonuc'] >= $arr['k2_as'] && $data[$sayac]['sonuc'] <= $arr['k2_us'] && $arr['k2_as'] != '' && $arr['k2_us'] != '')
                                // {
                                //     $data[$sayac]['duzey'] = 2;
                                // }
                                // else if ($data[$sayac]['sonuc'] >= $arr['k3_as'] && $data[$sayac]['sonuc'] <= $arr['k3_us'] && $arr['k3_as'] != '' && $arr['k3_us'] != '')
                                // {
                                //     $data[$sayac]['duzey'] = 3;
                                // }
                                // else if ($data[$sayac]['sonuc'] >= $arr['k4_as'] && $data[$sayac]['sonuc'] <= $arr['k4_us'] && $arr['k4_as'] != '' && $arr['k4_us'] != '')
                                // {
                                //     $data[$sayac]['duzey'] = 4;
                                // }
                            }
                        }
                        else
                        {
                          $data[$sayac]['duzey'] = $degerlendirme2[$arr['g_id']][$i]['duzey'];
                        }
                    }
                    else if ($who == 'yon1')
                    {
                        if ($arr['degerlendirme_turu'] == '3') // Subjektif
                        {
                            $data[$sayac]['duzey'] = $duzey[$arr['g_id']][$i];
                        }
                        else
                        {
                            $data[$sayac]['duzey'] = $degerlendirme2[$arr['g_id']][$i]['duzey'];
                        }
                    }

                    

                    
                    if ($data[$sayac]['duzey'] > 0)
                    {
                        $periyod_sayisi[$arr['g_id']]++;
                    }

                    $count_kadameler = count($arr["kademeler"]);
                    
                    //$data[$sayac]['puan'] = $arr['agirlik']/$arr['periyod']/4*$data[$sayac]['duzey'];
                    $data[$sayac]['puan'] = $arr['agirlik'] / $arr['periyod'] / $count_kadameler * $data[$sayac]['duzey'];
                    $data[$sayac]['guncelleyen'] = $this->session->userdata('pernr');
                    $data[$sayac]['g_time'] = $time;
                    
                    $sayac++;
                }
            }

                //var_dump($data);
                //exit();


            foreach ($data as $key=>$arr)
            {
                if ($donem == 2)
                {
                    $data[$key]['puan'] = $data[$key]['puan']*$g_periyod_sayisi[$arr['g_id']]/$periyod_sayisi[$arr['g_id']]/2;
                }
                else
                {
                    if ($g_periyod_sayisi[$arr['g_id']] == 1)
                    {
                        $data[$key]['puan'] = $data[$key]['puan']*$g_periyod_sayisi[$arr['g_id']]/$periyod_sayisi[$arr['g_id']];
                    }
                    else
                    {
                        // Yarıyılda form varsa ikiye böl
                        if ($yy_form_var)
                        {
                            $data[$key]['puan'] = $data[$key]['puan']*$g_periyod_sayisi[$arr['g_id']]/$periyod_sayisi[$arr['g_id']]/2;
                        }
                        else
                        {
                            $data[$key]['puan'] = $data[$key]['puan']*$g_periyod_sayisi[$arr['g_id']]/$periyod_sayisi[$arr['g_id']]; 
                        }
                    }
                    
                }
            }
            

            // var_dump($data);
            //exit();

            if (is_array($data))
            {
                unset($kosul);
                $kosul['yil'] = $yil;
                $kosul['donem'] = $donem;
                $kosul['pernr'] = $rilen_pernr;
                $kosul['degerlendiren_ack'] = $degerlendiren_ack;
                $kosul['form_id'] = $form_id;
                
                if (is_array($silinmeyecek_gosterge))
                {
                    $kosul['g_id!='] = $silinmeyecek_gosterge;
                }
                
                $r = $this->genel_model->veri_kaydet_cs("degerlendirmeler", $data, $kosul);

                if (!$r)
                {
                    $alert_message = alert_message('Teknik bir aksaklık oluştu daha sonra tekrar deneyiniz.');
                    $this->session->set_flashdata('message', $alert_message);
                    redirect('/d_donemleri/form_goruntule/'.$form_id.'/'.$rilen_pernr.'/'.$yil.'/'.$donem.'/'.$ft_id);
                    exit();
                }
                
                $alert_message = alert_message('Kayıt başarılı.', 'success');
                $this->session->set_flashdata('message', $alert_message);
                redirect('/d_donemleri/form_goruntule/'.$form_id.'/'.$rilen_pernr.'/'.$yil.'/'.$donem.'/'.$ft_id);
                exit();
            }

            $alert_message = alert_message('Kayıt başarılı.', 'success');
            $this->session->set_flashdata('message', $alert_message);
            redirect('/d_donemleri/form_goruntule/'.$form_id.'/'.$rilen_pernr.'/'.$yil.'/'.$donem.'/'.$ft_id);
            exit();
            
        }
        else if ($form['ft_id'] == 2 || $form['ft_id'] == 3) // Yetkinlik Formu
        {
            // Form detayınnı bul
            //$form_detay = $this->d_donemleri_model->formlar_detay($form_id);

            
            $form_detay = $this->formlar_detay_from_modal($form_id);

            

            $duzey = $this->input->post('gd');

            
            // yarıyılda bu form ataması var mı ?
            unset($kosul);
            $select = '*';
            $kosul['yil'] = $yil;
            $kosul['donem'] = 2;
            $kosul['form_id'] = $form_id;
            $kosul['pernr'] = $rilen_pernr;
            $form_atamalari = $this->genel_model->veri_cek_ts("form_atamalari", $select, $kosul);

            if (is_array($form_atamalari))
            {
                $yy_form_var = true;
            }
            else
            {
                $yy_form_var = false;
            }
            
            $time = date("Y-m-d H:i:s");
            $sayac = 0;
            foreach ($form_detay as $key=>$arr)
            {

                $count_kademeler = count($arr["kademeler"]); // puan işlemi dinamik olsun diye kademe sayısı hesaplanıyor.

                $periyod_sayisi[$arr['g_id']] = 0;
                $g_periyod_sayisi[$arr['g_id']] = $arr['periyod'];
                for ($i = 1; $i <= $arr['periyod']; $i++)
                {
                    if ($donem == 2)
                    {
                        $periyod_hesap = 12/$arr['periyod']*$i;
                        if ($periyod_hesap > 6) // Yarı yıl
                        {
                            continue;
                        }
                    }
                    else if ($donem == 3)
                    {
                        $periyod_hesap = 12/$arr['periyod']*$i;
                        if ($periyod_hesap <= 6) // Yılsonu
                        {
                            continue;
                        }
                    }
                    
                    $data[$sayac]['yil'] = $yil;
                    $data[$sayac]['donem'] = $donem;
                    $data[$sayac]['pernr'] = $rilen_pernr;
                    $data[$sayac]['degerlendiren_ack'] = $degerlendiren_ack;
                    $data[$sayac]['degerlendiren_pernr'] = $degerlendiren_pernr;
                    $data[$sayac]['form_id'] = $form_id;
                    $data[$sayac]['ft_id'] = $ft_id;
                    $data[$sayac]['g_id'] = $arr['g_id'];
                    $data[$sayac]['periyod'] = $i;
                    $data[$sayac]['duzey'] = $duzey[$arr['g_id']][$i]['duzey'];
                    
                    if ($data[$sayac]['duzey'] > 0)
                    {
                        $periyod_sayisi[$arr['g_id']]++;
                    }
                    
                    $data[$sayac]['guncelleyen'] = $this->session->userdata('pernr');
                    $data[$sayac]['g_time'] = $time;
                    
                    if ($donem == 2)
                    {
                        $data[$sayac]['puan'] = 0;
                    }
                    else if ($donem == 3)
                    {
                        //$data[$sayac]['puan'] = $arr['agirlik']/1/4*$data[$sayac]['duzey'];
                        $data[$sayac]['puan'] = $arr['agirlik']/1/$count_kademeler*$data[$sayac]['duzey'];
                        
                    }
                    
                    
                    $sayac++;
                }
            }
            
            //            foreach ($data as $key=>$arr)
            //            {
            //                if ($donem == 2)
            //                {
            //                    $data[$key]['puan'] = $data[$key]['puan']*$g_periyod_sayisi[$arr['g_id']]/$periyod_sayisi[$arr['g_id']]/2;
            //                }
            //                else
            //                {
            //                    if ($g_periyod_sayisi[$arr['g_id']] == 1)
            //                    {
            //                        $data[$key]['puan'] = $data[$key]['puan']*$g_periyod_sayisi[$arr['g_id']]/$periyod_sayisi[$arr['g_id']];
            //                    }
            //                    else 
            //                    {
            //                        // Yarıyılda form varsa ikiye böl
            //                        if ($yy_form_var)
            //                        {
            //                            $data[$key]['puan'] = $data[$key]['puan']*$g_periyod_sayisi[$arr['g_id']]/$periyod_sayisi[$arr['g_id']]/2;
            //                        }
            //                        else
            //                        {
            //                            $data[$key]['puan'] = $data[$key]['puan']*$g_periyod_sayisi[$arr['g_id']]/$periyod_sayisi[$arr['g_id']]; 
            //                        }
            //                    }
            //                }
            //            }
            
            
            if (is_array($data))
            {

                
                unset($kosul);
                $kosul['yil'] = $yil;
                $kosul['donem'] = $donem;
                $kosul['pernr'] = $rilen_pernr;
                $kosul['degerlendiren_ack'] = $degerlendiren_ack;
                $kosul['form_id'] = $form_id;
                $r = $this->genel_model->veri_kaydet_cs("degerlendirmeler", $data, $kosul);

                if (!$r)
                {
                    $alert_message = alert_message('Teknik bir aksaklık oluştu daha sonra tekrar deneyiniz.');
                    $this->session->set_flashdata('message', $alert_message);
                    redirect('/d_donemleri/form_goruntule/'.$form_id.'/'.$rilen_pernr.'/'.$yil.'/'.$donem.'/'.$ft_id);
                    exit();
                }

                $alert_message = alert_message('Kayıt başarılı.', 'success');
                $this->session->set_flashdata('message', $alert_message);
                redirect('/d_donemleri/form_goruntule/'.$form_id.'/'.$rilen_pernr.'/'.$yil.'/'.$donem.'/'.$ft_id);
                exit();
            }
            else
            {
                $alert_message = alert_message('Kayıt başarılı.', 'success');
                $this->session->set_flashdata('message', $alert_message);
                redirect('/d_donemleri/form_goruntule/'.$form_id.'/'.$rilen_pernr.'/'.$yil.'/'.$donem.'/'.$ft_id);
                exit();
            }
        }
        else if ($form['ft_id'] == 4) // Görüşme Formu
        {
            $aciklama = $this->input->post('aciklama');
            
            // Form detayınnı bul
            //$form_detay = $this->d_donemleri_model->formlar_detay($form_id);

            $form_detay = $this->formlar_detay_from_modal($form_id);
            
            $duzey = $this->input->post('gd');
            $time = date("Y-m-d H:i:s");
            $sayac = 0;
            foreach ($form_detay as $key=>$arr)
            {
                for ($i = 1; $i <= $arr['periyod']; $i++)
                {
                    $kayit_kontrol = true;  
                    if ($donem == 2)
                    {
                        $periyod_hesap = 12/$arr['periyod']*$i;
                        if ($periyod_hesap > 6) // Yarı yıl
                        {
                            continue;
                        }
                    }
                    else if ($donem == 3)
                    {
                        $periyod_hesap = 12/$arr['periyod']*$i;
                        if ($periyod_hesap <= 6) // Yılsonu
                        {
                            continue;
                        }
                    }
                    
                    $data[$sayac]['yil'] = $yil;
                    $data[$sayac]['donem'] = $donem;
                    $data[$sayac]['pernr'] = $rilen_pernr;
                    $data[$sayac]['degerlendiren_ack'] = $degerlendiren_ack;
                    $data[$sayac]['degerlendiren_pernr'] = $degerlendiren_pernr;
                    $data[$sayac]['form_id'] = $form_id;
                    $data[$sayac]['ft_id'] = $ft_id;
                    $data[$sayac]['g_id'] = $arr['g_id'];
                    $data[$sayac]['periyod'] = $i;
                    $data[$sayac]['aciklama'] = $aciklama[$arr['g_id']][$i];
                    $data[$sayac]['guncelleyen'] = $this->session->userdata('pernr');
                    $data[$sayac]['g_time'] = $time;
                    
                    $sayac++;
                }
            }
            
            if (is_array($data))
            {
                unset($kosul);
                $kosul['yil'] = $yil;
                $kosul['donem'] = $donem;
                $kosul['pernr'] = $rilen_pernr;
                $kosul['degerlendiren_ack'] = $degerlendiren_ack;
                $kosul['form_id'] = $form_id;
                $r = $this->genel_model->veri_kaydet_cs("degerlendirmeler", $data, $kosul);

                if (!$r)
                {
                    $alert_message = alert_message('Teknik bir aksaklık oluştu daha sonra tekrar deneyiniz.');
                    $this->session->set_flashdata('message', $alert_message);
                    redirect('/d_donemleri/form_goruntule/'.$form_id.'/'.$rilen_pernr.'/'.$yil.'/'.$donem.'/'.$ft_id);
                    exit();
                }

                $alert_message = alert_message('Kayıt başarılı.', 'success');
                $this->session->set_flashdata('message', $alert_message);
                redirect('/d_donemleri/form_goruntule/'.$form_id.'/'.$rilen_pernr.'/'.$yil.'/'.$donem.'/'.$ft_id);
                exit();
            }
            
            $alert_message = alert_message('Kayıt başarılı.', 'success');
            $this->session->set_flashdata('message', $alert_message);
            redirect('/d_donemleri/form_goruntule/'.$form_id.'/'.$rilen_pernr.'/'.$yil.'/'.$donem.'/'.$ft_id);
            exit();
        }
        else if ($form['ft_id'] == 5) // Yönlendirme Formu
        {
            $hp = $this->input->post('hp');

            /// YENİ EKLENDİ
            $donem_id = $this->getDonemId($donem,$yil);
            $is_high_potential = true ;
            $pot_degerlendirme_data = $this->getPotansiyelDegerlendirme(0,$donem_id,$is_high_potential);   
            $buttons = $this->getButtons($pot_degerlendirme_data["pot_id"]);
            /// YENİ EKLENDİ
           
            
            $time = date("Y-m-d H:i:s");
            $sayac = 0;
            
            $data[$sayac]['yil'] = $yil;
            $data[$sayac]['donem'] = $donem;
            $data[$sayac]['pernr'] = $rilen_pernr;
            $data[$sayac]['degerlendiren_ack'] = $degerlendiren_ack;
            $data[$sayac]['degerlendiren_pernr'] = $degerlendiren_pernr;
            $data[$sayac]['form_id'] = $form_id;
            $data[$sayac]['ft_id'] = $ft_id;
            $data[$sayac]['hp'] = $hp;


            foreach($buttons as $button_key => $btn){ // altakki kod dinamik hale getirildi.
                if( ($button_key+1) == $hp ){
                    $data[$sayac]['hp_ack'] = $btn["button_name"];
                }
            }
            

            // if ($hp == 1)
            // {
            //     $data[$sayac]['hp_ack'] = "Üst Göreve Atanabilir";
            // }
            // else if ($hp == 2)
            // {
            //     $data[$sayac]['hp_ack'] = "Eş Düzey Göreve Geçebilir";
            // }
            // else if ($hp == 3)
            // {
            //     $data[$sayac]['hp_ack'] = "İşten Ayrılmasında Sakınca Yoktur";
            // }
            // else if ($hp == 4)
            // {
            //     $data[$sayac]['hp_ack'] = "Mevcut Göreve Devam Edebilir";
            // }


            $data[$sayac]['guncelleyen'] = $this->session->userdata('pernr');
            $data[$sayac]['g_time'] = $time;

            
            if (is_array($data))
            {
                unset($kosul);
                $kosul['yil'] = $yil;
                $kosul['donem'] = $donem;
                $kosul['pernr'] = $rilen_pernr;
                $kosul['degerlendiren_ack'] = $degerlendiren_ack;
                $kosul['form_id'] = $form_id;
                $r = $this->genel_model->veri_kaydet_cs("degerlendirmeler", $data, $kosul);

                if (!$r)
                {
                    $alert_message = alert_message('Teknik bir aksaklık oluştu daha sonra tekrar deneyiniz.');
                    $this->session->set_flashdata('message', $alert_message);
                    redirect('/d_donemleri/form_goruntule/'.$form_id.'/'.$rilen_pernr.'/'.$yil.'/'.$donem.'/'.$ft_id);
                    exit();
                }

                $alert_message = alert_message('Kayıt başarılı.', 'success');
                $this->session->set_flashdata('message', $alert_message);
                redirect('/d_donemleri/form_goruntule/'.$form_id.'/'.$rilen_pernr.'/'.$yil.'/'.$donem.'/'.$ft_id);
                exit();
            }
            
            $alert_message = alert_message('Kayıt başarılı.', 'success');
            $this->session->set_flashdata('message', $alert_message);
            redirect('/d_donemleri/form_goruntule/'.$form_id.'/'.$rilen_pernr.'/'.$yil.'/'.$donem.'/'.$ft_id);
            exit();
        }
    }

    // ---- Performans Arttırıcı Olan Kodlar JQuery Tarafındada tüketilen kodlar
    
    public function GetDonemFormTypes($yil,$donem)
    {

        $select = '*';
        $kosul['yil'] = $yil;
        $kosul['donem'] = $donem;
        $tablo1 = $this->genel_model->veri_cek_ts("donem_listesi", $select, $kosul);
        
        $data['donem_ack'] = $tablo1['donem_ack'];
        
        // Değerlendirilmesi gereken formlar..
        $form_tipleri = explode(',', $tablo1['ft']);

        
        
        unset($kosul);
        $select = "*";
        $kosul['id'] = $form_tipleri;
        $tablo1 = $this->genel_model->veri_cek_cs("form_tipleri", $select, $kosul);



        foreach ($tablo1 as $key=>$arr)
        {
            $data['ft_text'][$arr['id']] = $arr['ft_text'];
        }


        if(!is_null($data["ft_text"]))
        {
            $http_code = 200;
        }
        else
        {
            $http = 204;
        }

        http_response_code($http_code);

        header("Content-Type: application/json");

        echo json_encode($data);

    }





    public function GetFormTypes($id='')
    {
 
        $this->load->model("form_model");

        $where_in = null;

        if($id=="kaf")
        {
            $where_in = [ 1, 2 ];
        }
        
        $data["forms"] = $this->form_model->get_form_types($where_in);

        
        if(!is_null($data["forms"]))
        {
            $http_code = 200;
        }
        else
        {
            $http_code = 204;
        }

        http_response_code($http_code);

        header("Content-Type: application/json");

        echo json_encode($data);
    }


    public function GetSessionFormPointEvaluateBySelf()
    {
        $payload_data = json_decode(file_get_contents("php://input") , true);

        $this->load->model("form_model");


        $data = $this->form_model->get_points_hedef_yetkinlik_form_by_session($payload_data["year"],$payload_data["donem"],$payload_data["pernr"]);
        

        if(!is_null($data))
        {
            $http_code = 200;
        }
        else
        {
            $http_code = 204;
        }

        http_response_code($http_code);

        header("Content-Type: application/json");

        
        echo json_encode($data);
    }
    


    public function GetSessionFormOfPernr()
    {
        $payload_data = json_decode(file_get_contents("php://input") , true);

        $this->load->model("form_model");

        $data = $this->form_model->get_session_form_of_pernr($payload_data["year"],$payload_data["donem"],$payload_data["pernr"],$payload_data["ft_id"]);
        

        if(!is_null($data))
        {
            $http_code = 200;
        }
        else
        {
            $http_code = 204;
        }

        http_response_code($http_code);

        header("Content-Type: application/json");

        
        echo json_encode($data);
        

    }



    public function GetTotalHedefYetkinlikPoints()
    {
        $payload_data = json_decode(file_get_contents("php://input") , true);

        $this->load->model("form_model");

        $data = $this->form_model->get_total_hedef_yetkinlik_points($payload_data["year"],$payload_data["donem"],$payload_data["pernr_list"]);
        

        if(!is_null($data))
        {
            $http_code = 200;
        }
        else
        {
            $http_code = 204;
        }

        http_response_code($http_code);

        header("Content-Type: application/json");

        
        echo json_encode($data);
    }
    

    public function GetPernrForms($pernr,$yil,$donem,$pernr_list)
    {
        $select = '*';
        $kosul['yil'] = $yil;
        $kosul['donem'] = $donem;
        $kosul['ft_id'] = $ft_id;
        $kosul['pernr'] = $pernr_list;
        
        $tablo2 = $this->genel_model->veri_cek_cs("organizasyon_d", $select, $kosul, $order_by);
    }


    public function GetOrganizationLevelOneInner($pernr,$yil,$donem)
    {
        // Değerlendirilmesi gereken kişiler astlarım..
                
        $select = '*';
        $kosul['yil'] = $yil;
        $kosul['donem'] = $donem;
        $kosul['ust_pernr'] = $pernr;
        $order_by['ad_soyad'] = 'asc';

        $tablo2 = $this->genel_model->veri_cek_cs("organizasyon_d", $select, $kosul, $order_by);

        $data = null;

        if (is_array($tablo2))
        {
            foreach ($tablo2 as $key=>$arr)
            {
                $data['ast_listesi'][] = $arr['pernr'];
                $data['ad_soyad'][$arr['pernr']] = $arr['ad_soyad'];
                $data['pozisyon'][$arr['pernr']] = $arr['poz_text'];
            }
        }


        return $data;
    }


    public function GetAstAstEmployeeData()
    {
        $this->load->model("form_model");

        $payload_data = json_decode(file_get_contents("php://input") , true);

        /*
        $payload_data = [
            "year"=>"2022",
            "donem"=>"3",
            "pernr_list"=> ["102506","102474","102493"]
        ];*/

        $data_ast_ast_employee = $this->form_model->get_ast_ast_employee_data($payload_data["year"], $payload_data["donem"], $payload_data["pernr_list"]);
        
        $data = null;

        if(!is_null($data_ast_ast_employee))
        {
            
            
            $http_code = 200;

            foreach ($data_ast_ast_employee as $key => $arr) {

                
                $data['ast_listesi'][] = $arr -> pernr;
                
            }

        }
        else
        {
            $http_code = 204;
        }
        
        http_response_code($http_code);

        header("Content-Type: application/json");

        echo json_encode($data);

    }



    public function GetOrganizationLevelOne($pernr,$yil,$donem)
    {

        $data = $this->GetOrganizationLevelOneInner($pernr,$yil,$donem);

        /*
        echo "<pre>";
        print_r($data);
        */
        if(!is_null($data))
        {
            $http_code = 200;
        }
        else
        {
            $http_code = 204;
        }

        http_response_code($http_code);

        header("Content-Type: application/json");

        echo json_encode($data);

        
    }

                    
    public function GetFormOfPernrList()
    {

        $payload_data = json_decode(file_get_contents("php://input") , true);

        $this->load->model("form_model");
    
        $data = $this->form_model->get_forms_of_pernrs($payload_data["year"],$payload_data["donem"],$payload_data["ust_pernr"],$payload_data["ft_id"]);

        if(!is_null($data))
        {
            $http_code = 200;
        }
        else
        {
            $http_code = 204;
        }

        http_response_code($http_code);

        header("Content-Type: application/json");

        
        echo json_encode($data);

    }


    public function GetHedefFormPoints()
    {
        $payload_data = json_decode(file_get_contents("php://input") , true);
        
        $this->load->model("form_model");

        $data = $this->form_model->get_hedef_form_point($payload_data["year"],$payload_data["donem"],$payload_data["pernr_list"]);

        if(!is_null($data))
        {
            $http_code = 200;
        }
        else
        {
            $http_code = 204;
        }

        http_response_code($http_code);

        header("Content-Type: application/json");

        
        echo json_encode($data);
    }

    

    public function GetYetkinlikFormPoints()
    {
        $payload_data = json_decode(file_get_contents("php://input") , true);
        
        $this->load->model("form_model");

        $data = $this->form_model->get_yetkinlik_form_point($payload_data["year"],$payload_data["donem"],$payload_data["pernr_list"]);

        if(!is_null($data))
        {
            $http_code = 200;
        }
        else
        {
            $http_code = 204;
        }

        http_response_code($http_code);

        header("Content-Type: application/json");

        
        echo json_encode($data);
    }
    

    public function GetPointsOfPernrList()
    {
        $payload_data = json_decode(file_get_contents("php://input") , true);
        
        $this->load->model("form_model");

        //get_points_of_potential_form_of_pernr_list
        $data = $this->form_model->get_points_of_potential_form_of_pernr_list($payload_data["year"],$payload_data["donem"],$payload_data["pernr_list"]);

        if(!is_null($data))
        {
            $http_code = 200;
        }
        else
        {
            $http_code = 204;
        }

        http_response_code($http_code);

        header("Content-Type: application/json");

        
        echo json_encode($data);

    }

    public function GetPointsOfPotentialFormOfPernr()
    {
        $payload_data = json_decode(file_get_contents("php://input") , true);

        $this->load->model("form_model");

        $data = $this->form_model->get_points_of_potential_form_of_pernr($payload_data["year"],$payload_data["donem"],$payload_data["form_id"],$payload_data["pernr"]);

        if(!is_null($data))
        {
            $http_code = 200;
        }
        else
        {
            $http_code = 204;
        }

        http_response_code($http_code);

        header("Content-Type: application/json");

        
        echo json_encode($data);


    }

    
    public function CollectPotentialPoints()
    {

        $this->load->model("form_model");

        $payload_data = json_decode(file_get_contents("php://input") , true);


        /*$payload_data = [
            "year" => 2022,
            "donem" => 3,
            "ft_id" => 3,
            "pernr_list" => ["102474,102493,102576"]
        ];*/


        $data = $this->form_model->get_points_of_potential_form_of_pernr_list($payload_data["year"],$payload_data["donem"],$payload_data["ft_id"],$payload_data["pernr_list"]);

        if(!is_null($data))
        {
            $http_code = 200;
        }
        else
        {
            $http_code = 204;
        }

        http_response_code($http_code);

        header("Content-Type: application/json");

        
        echo json_encode($data);

    }


    public function GetAstAstForm()
    {
        // Değerlendirilmesi gereken kişiler astlarımın astları..

        $this->load->model("form_model");
        $payload_data = json_decode(file_get_contents("php://input") , true);

    
        //Example data
        /*$payload_data = [
            "year"=>"2022",
            "donem" => "3",
            "pernr_list" => ["102506","102474","102493"],
            "form_id" => "1"
        ];*/

        $data = $this->form_model->get_forms_of_pernrs_for_ast_ast($payload_data["year"],$payload_data["donem"],$payload_data["pernr_list"],$payload_data["form_id"]);

        
        if(!is_null($data))
        {
            $http_code = 200;
        }
        else
        {
            $http_code = 204;
        }

        http_response_code($http_code);

        header("Content-Type: application/json");

        
        echo json_encode($data);

    }
    

    public function GetSessionIsManager(){

        $this->load->model("form_model");

        $payload_data = json_decode(file_get_contents("php://input") , true);

        
        $data = $this->form_model->get_session_pernr_is_administrator($payload_data["year"],$payload_data["donem"],$payload_data["pernr"]);

        

        if(!is_null($data))
        {
            $http_code = 200;
        }
        else
        {
            $http_code = 204;
        }

        http_response_code($http_code);

        header("Content-Type: application/json");

        
        echo json_encode($data);

    }


    public function GetConfirmationProcessOfAst()
    {

        // Onaya Gönderilmesi Bekleniyor Default
        // Onaya Gönderildi.
        // Onayalndı yada Red Edildi
        // Onaya Gönderilmesi Bekleniyor


        $this->load->model("form_model");

        $payload_data = json_decode(file_get_contents("php://input") , true);

    
        $data = $this->form_model->get_confirmation_process($payload_data["year"],$payload_data["donem"],$payload_data["ft_id"],$payload_data["degerlendiren_list"],$payload_data["onaylayan"]);

        if(!is_null($data))
        {
            $http_code = 200;
        }
        else
        {
            $http_code = 204;
        }

        http_response_code($http_code);

        header("Content-Type: application/json");

        
        echo json_encode($data);

    }


    public function IsKadelendirmeStart()
    {

        // Onaya Gönderilmesi Bekleniyor Default
        // Onaya Gönderildi.
        // Onayalndı yada Red Edildi
        // Onaya Gönderilmesi Bekleniyor


        $this->load->model("form_model");

        $payload_data = json_decode(file_get_contents("php://input") , true);

    
        $data = $this->form_model->is_kademelendirme_start($payload_data["year"],$payload_data["donem"],$payload_data["havuz_id"]);

        if(!is_null($data))
        {
            $http_code = 200;
        }
        else
        {
            $http_code = 204;
        }

        http_response_code($http_code);

        header("Content-Type: application/json");

        
        echo json_encode($data);

    }


    public function GetRoleOfKademelendirme()
    {
        $this->load->model("form_model");

        $payload_data = json_decode(file_get_contents("php://input") , true);

    
        $data = $this->form_model->get_role_of_kademelendirme($payload_data["year"],$payload_data["donem"],$payload_data["k_pernr"]);

        if(!is_null($data))
        {
            $http_code = 200;
        }
        else
        {
            $http_code = 204;
        }

        http_response_code($http_code);

        header("Content-Type: application/json");

        
        echo json_encode($data);
    }

    public function GetRoleOfKademelendirmeBaseOfPernr($year,$donem,$pernr)
    {
        $this->load->model("form_model");

        $payload_data = json_decode(file_get_contents("php://input") , true);

    
        $data = $this->form_model->get_role_of_kademelendirme_base_of_pernr($payload_data["year"],$payload_data["donem"],$payload_data["pernr"]);

        if(!is_null($data))
        {
            $http_code = 200;
        }
        else
        {
            $http_code = 204;
        }

        http_response_code($http_code);

        header("Content-Type: application/json");

        
        echo json_encode($data);
    }


    public function SessionIsManager()
    {
        $this->load->model("form_model");

        $payload_data = json_decode(file_get_contents("php://input") , true);

    
        $data = $this->form_model->session_is_manager_data($payload_data["year"],$payload_data["donem"],$payload_data["pernr"]);

        if(!is_null($data))
        {
            $http_code = 200;
        }
        else
        {
            $http_code = 204;
        }

        http_response_code($http_code);

        header("Content-Type: application/json");

        
        echo json_encode($data);
    }


    public function GetTotalHedefAndYetknlikBySelfSession()
    {
        $this->load->model("form_model");

        $payload_data = json_decode(file_get_contents("php://input") , true);

    
        $data = $this->form_model->get_total_hedef_and_yetknlik_by_self_session($payload_data["year"],$payload_data["donem"],$payload_data["pernr"]);

        if(!is_null($data))
        {
            $http_code = 200;
        }
        else
        {
            $http_code = 204;
        }

        http_response_code($http_code);

        header("Content-Type: application/json");

        
        echo json_encode($data);
    }


    public function CheckDegerlendirmeler()
    {
        $this->load->model("form_model");

        $payload_data = json_decode(file_get_contents("php://input") , true);

    
        $data = $this->form_model->session_user_check_degerlendirmeler($payload_data["year"],$payload_data["donem"],$payload_data["degerlendiren_pernr"]);

        if(!is_null($data))
        {
            $http_code = 200;
            $new_data = null;
            foreach ($data as $item) 
            {
                $new_data[$item->ft_id][$item->pernr] = $item->is_ft_filled;
            }

            $data = $new_data;
        }
        else
        {
            $http_code = 204;
        }

        http_response_code($http_code);

        header("Content-Type: application/json");

        
        echo json_encode($data);
    }

    public function CheckConfirmationToDeep()
    {
        $this->load->model("form_model");

        $payload_data = json_decode(file_get_contents("php://input") , true);

    
        $data = $this->form_model->session_user_check_all_confirmation_to_deep($payload_data["year"],$payload_data["donem"],$payload_data["session_user_pernr"],$payload_data["ast_listesi"]);

        if(!is_null($data))
        {
            
            $http_code = 200;
            $new_data = null;

            
            foreach ($data as $item) 
            {
                $new_data[$item->ft_id][] = $item->onay_durum == 1 ? 1 : 0;
            }

            $data = $new_data;

        }
        else
        {
            $http_code = 204;
        }

        http_response_code($http_code);

        header("Content-Type: application/json");

        
        echo json_encode($data);


    }


    public function IsKademeManagerIsConfirmed()
    {
        $this->load->model("form_model");

        $payload_data = json_decode(file_get_contents("php://input") , true);

    
        $data = $this->form_model->is_kademe_manager_is_confirmed($payload_data["year"],$payload_data["donem"],$payload_data["onaylayan"]);

        if(!is_null($data))
        {
            $http_code = 200;
            $new_data = null;
            foreach ($data as $item) 
            {
                $new_data[$item->ft_id] = $item->onay_durum == 1 ? 1 : 0;
            }

            $data = $new_data;
        }
        else
        {
            $http_code = 204;
        }

        http_response_code($http_code);

        header("Content-Type: application/json");

        
        echo json_encode($data);
    }

    public function GetUstListesi()
    {
        $this->load->model("form_model");

        $payload_data = json_decode(file_get_contents("php://input") , true);

    
        $data = $this->form_model->get_ust_listesi($payload_data["year"],$payload_data["donem"],$payload_data["session_pernr"]);

        if(!is_null($data))
        {
            $http_code = 200;
        }
        else
        {
            $http_code = 204;
        }

        http_response_code($http_code);

        header("Content-Type: application/json");

        
        echo json_encode($data);
    }

    public function GetUstOnay()
    {
        $this->load->model("form_model");

        $payload_data = json_decode(file_get_contents("php://input") , true);

    
        $data = $this->form_model->get_ust_confirmation($payload_data["year"],$payload_data["donem"],$payload_data["onaylayan"],$payload_data["degerlendiren"]);

        if(!is_null($data))
        {
            
            $http_code = 200;
            $new_data = null;
            foreach ($data as $item) 
            {
                $new_data[$item->ft_id] = $item->onay_durum == 1 ? 1 : 0;
            }

            $data = $new_data;
            
        }
        else
        {
            $http_code = 204;
        }

        http_response_code($http_code);

        header("Content-Type: application/json");

        
        echo json_encode($data);
    }


    public function GetProfileData()
    {
        $data["user"] = $this->session->userdata();

        echo json_encode($data);
    }




}
