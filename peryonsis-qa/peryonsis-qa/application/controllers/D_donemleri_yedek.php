<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class D_donemleri extends MY_Controller {
    
    public function __construct() {
        parent::__construct();
        
        $this->load->model("d_donemleri_model");
        
//        $this->load->model("d_donemleri_model");
    }

    public function takvim($yil, $donem, $disaridan_veri_al_ok)
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
        
        $data['donem_ack'] = $tablo1['donem_ack'];
        
        $tarih = date('Y-m-d');
        
        if ($tarih < $tablo1['bat'])
        {
            $alert_message = alert_message('Hata.');
            $this->session->set_flashdata('message', $alert_message);
            redirect('./home/');
            exit();
        }
        
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
        
        // Değerlendirilmesi gereken kişiler astlarım..
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
                $data['ad_soyad'][$arr['pernr']] = $arr['ad_soyad'];
                $data['pozisyon'][$arr['pernr']] = $arr['poz_text'];
                
                $samanlik = $arr['poz_text'];
                $igne = "TSM";
                $konum_t = strpos($samanlik, $igne);
                if ($konum_t !== false)
                {
                    $tsm_astlarim[] = $arr['pernr'];
                }
            }
        }
        
        if (!is_array($kisi_listesi))
        {
            $kisi_listesi = "boş";
        }
        
        // Dışardan TSM verisi çek
        $samanlik = $tablo1[0]['poz_text'];
        $igne = "BTM";
        $konum = strpos($samanlik, $igne);
        if (is_array($kisi_listesi) && $konum !== false)
        {
            $data['disardan_veri_al'] = 1;
            
            if ($disaridan_veri_al_ok == "ok")
            {
                $this->load->model("veri_al_model");
                $this->veri_al_model->kopyala($kisi_listesi, $yil, $donem, $tablo1[0]['pernr']);
            }
        }
        
        if ($konum === false)
        {
            if (is_array($tsm_astlarim))
            {
                $data['disardan_veri_al'] = 1;
                
                if ($disaridan_veri_al_ok == "ok")
                {
                    $this->load->model("veri_al_model");
                    $this->veri_al_model->kopyala($tsm_astlarim, $yil, $donem, $tablo1[0]['pernr']);
                }
            }
        }
        
        
        // Değerlendirilmesi gereken kişiler astlarımın astları..
        unset($kosul);
        $select = '*';
        $kosul['yil'] = $yil;
        $kosul['donem'] = $donem;
        $kosul['ust_pernr'] = $kisi_listesi;
        $order_by['ad_soyad'] = 'asc';
        $tablo3 = $this->genel_model->veri_cek_cs("organizasyon_d", $select, $kosul, $order_by);
        
        // Astlarımın kademelendirdiklerini getirme..
        unset($kosul);
        $select = '*';
        $kosul['yil'] = $yil;
        $kosul['donem'] = $donem;
        $kosul['k_pernr'] = $kisi_listesi;
        $astlarimin_kademelendirdikleri = $this->genel_model->veri_cek_cs("kademelendirme_atamalari", $select, $kosul);
        
        foreach ($astlarimin_kademelendirdikleri as $key=>$arr)
        {
            $akk[] = $arr['pernr'];
        }
        
        if (is_array($tablo3))
        {
            foreach ($tablo3 as $key=>$arr)
            {
//                if (in_array($arr['pernr'], $akk))
//                {
//                    continue;
//                }
                $kisi_listesi2[] = $arr['pernr'];
                $data['ust_ad_soyad'][$arr['pernr']] = $arr['ust_ad_soyad'];
                $data['ust_pernr'][$arr['pernr']] = $arr['ust_pernr'];
                $data['ad_soyad'][$arr['pernr']] = $arr['ad_soyad'];
                $data['pozisyon'][$arr['pernr']] = $arr['poz_text'];
            }
        }
        
        if (!is_array($kisi_listesi2))
        {
            $kisi_listesi2 = "boş";
        }
        
        // Kendine atanmış formlar
        unset($kosul);
        $select = '*';
        $kosul['yil'] = $yil;
        $kosul['donem'] = $donem;
        $kosul['pernr'] = $this->session->userdata('pernr');
        $kosul['ft_id'] = $form_tipleri;
        $tablo4 = $this->genel_model->veri_cek_cs("form_atamalari", $select, $kosul);
        
        if (is_array($tablo4))
        {
            foreach ($tablo4 as $key=>$arr)
            {
                //Potansiyel ve yönlendirme formlarını yöneticiye göster..
                if (($arr['ft_id'] == 3 || $arr['ft_id'] == 5 || $arr['ft_id'] == 4))
                {
                    continue;
                }

                $data['formlar'][$arr['ft_id']][$arr['pernr']] = $arr['form_id'];
                
                unset($kosul);
                $select = 'sum(puan) as a';
                $kosul['yil'] = $yil;
                $kosul['form_id'] = $arr['form_id'];
                $kosul['pernr'] = $arr['pernr'];
                
                // Yarıyıl ise sadece yarıyılın puanını al
                if ($donem == 2)
                {
                    $kosul['donem'] = 2;
                }
                
                if ($arr['ft_id'] == 2)
                {
                    $kosul['degerlendiren_ack'] = 'rilen';
                }
                else if ($arr['ft_id'] == 1)
                {
                    $kosul['degerlendiren_ack'] = 'yon1';
                }
                $puan = $this->genel_model->veri_cek_ts("degerlendirmeler", $select, $kosul);
                
                $data['puan'][$arr['ft_id']][$arr['pernr']] = $puan['a'];
                
            }
        }
        
        // Astlarına atanmış formlar
        unset($kosul);
        $select = '*';
        $kosul['yil'] = $yil;
        $kosul['donem'] = $donem;
        $kosul['pernr'] = $kisi_listesi;
        $kosul['ft_id'] = $form_tipleri;
        $tablo5 = $this->genel_model->veri_cek_cs("form_atamalari", $select, $kosul);
        
        // Kademelnedirdiklerimi çek. Eğer hepsini ben kademelendiriyosam onaya gönderme.
        // Kademelendirmeye gönder. Onaylanan formlar otomatik kademelendirmeye gönderilmeli
        // Astlarımın kademelendirdiklerini getirme..
        unset($kosul);
        $select = '*';
        $kosul['yil'] = $yil;
        $kosul['donem'] = $donem;
        $kosul['k_pernr'] = $this->session->userdata('pernr');
        $kademelendirdiklerim = $this->genel_model->veri_cek_cs("kademelendirme_atamalari", $select, $kosul);
        
        foreach ($kademelendirdiklerim as $key=>$arr)
        {
            $bkk[] = $arr['pernr'];
        }
        
        $data['onaya_gonder']['1'] = false;
        $data['onaya_gonder']['2'] = false;
        $data['k_gonder']['1'] = false;
        $data['k_gonder']['2'] = false;
        
        if (is_array($tablo5))
        {
            foreach ($tablo5 as $key=>$arr)
            {
                if ($arr['ft_id'] == 1 || $arr['ft_id'] == 2)
                {
                    if (!in_array($arr['pernr'], $bkk))
                    {
                        $data['onaya_gonder'][$arr['ft_id']] = true;
                    }
                    else
                    {
                        $data['k_gonder'][$arr['ft_id']] = true;
                    }
                }
                else
                {
                    $data['onaya_gonder'][$arr['ft_id']] = true;
                }
                
                if ($data['od_ast_kontrol'][$arr['ft_id']] != 2)
                {
                    $data['od_ast_kontrol'][$arr['ft_id']] = 1;
                }
                
                if ($data['k_ast_kontrol'][$arr['ft_id']] != 2)
                {
                    $data['k_ast_kontrol'][$arr['ft_id']] = 1;
                }
                
                $data['formlar_ast'][$arr['ft_id']][$arr['pernr']] = $arr['form_id'];
                
                // Onay durumunu çek..
                unset($kosul);
                $select = '*';
                $kosul['yil'] = $yil;
                $kosul['donem'] = $donem;
                $kosul['ft_id'] = $arr['ft_id'];
                $kosul['degerlendiren'] = $this->session->userdata('pernr');
                $kosul['onaylayan'] = $data['ust_pernr'][$this->session->userdata('pernr')];
                $od_ast = $this->genel_model->veri_cek_ts("onaylar", $select, $kosul);
                if (is_array($od_ast))
                {
                    $data['od_ast'][$arr['ft_id']] = $od_ast;
                }
                else
                {
                    // Onaya gönderilmesi bekleniyor..
                    $data['od_ast'][$arr['ft_id']]['onay_durum'] = 3;
                    $data['od_ast'][$arr['ft_id']]['onay_text'] = "Onaya Gönderilmesi Bekleniyor";
                }
                
                // Kademelendirme durumunu çek..
                unset($kosul);
                $select = '*';
                $kosul['yil'] = $yil;
                $kosul['donem'] = $donem;
                $kosul['ft_id'] = $arr['ft_id'];
                $kosul['degerlendiren'] = $this->session->userdata('pernr');
                $kosul['onaylayan'] = $this->session->userdata('pernr');
                $od_ast = $this->genel_model->veri_cek_ts("onaylar", $select, $kosul);
                if (is_array($od_ast))
                {
                    $data['k_ast'][$arr['ft_id']] = $od_ast;
                    
                    // Kademelendirme başlamış mı kontrol et.. Geri al durumu..
                    unset($kosul);
                    $select = '*';
                    $kosul['yil'] = $yil;
                    $kosul['donem'] = $donem;
                    $kosul['pernr'] = $bkk;
                    $k_durumu = $this->genel_model->veri_cek_cs("kademelendirme_sonuclari", $select, $kosul);

                    if (is_array($k_durumu))
                    {
                        $data['k_ast'][$arr['ft_id']]['k_kontrol'] = false;
                    }
                    else
                    {
                        $data['k_ast'][$arr['ft_id']]['k_kontrol'] = true;
                    }
                }
                else
                {
                    // Onaya gönderilmesi bekleniyor..
                    $data['k_ast'][$arr['ft_id']]['onay_durum'] = 3;
                    $data['k_ast'][$arr['ft_id']]['onay_text'] = "Kademelendirmeye Gönderilmesi Bekleniyor";
                }
                
                unset($kosul);
                $select = 'sum(puan) as a';
                $kosul['yil'] = $yil;
                $kosul['form_id'] = $arr['form_id'];
                $kosul['pernr'] = $arr['pernr'];
                $kosul['degerlendiren_ack'] = 'yon1';
                // Yarıyıl ise sadece yarıyılın puanını al
                if ($donem == 2)
                {
                    $kosul['donem'] = 2;
                }
                $puan = $this->genel_model->veri_cek_ts("degerlendirmeler", $select, $kosul);
                $data['puan'][$arr['ft_id']][$arr['pernr']] = $puan['a'];
                
                //tüm astlar için veri oluşmuş ise onaya gönderilebilir.
                unset($kosul);
                $select = 'count(*) as a';
                $kosul['yil'] = $yil;
                $kosul['form_id'] = $arr['form_id'];
                $kosul['pernr'] = $arr['pernr'];
                $kosul['degerlendiren_ack'] = 'yon1';
                $kosul['donem'] = $donem;
                $kayit = $this->genel_model->veri_cek_ts("degerlendirmeler", $select, $kosul);

                if ($kayit['a'] == 0)
                {
                    $data['od_ast_kontrol'][$arr['ft_id']] = 2;
                    $data['k_ast_kontrol'][$arr['ft_id']] = 2;
                }
            }
        }
        
        // Astlarımın astlarına atanmış formlar
        unset($kosul);
        $select = '*';
        $kosul['yil'] = $yil;
        $kosul['donem'] = $donem;
        $kosul['pernr'] = $kisi_listesi2;
        $kosul['ft_id'] = $form_tipleri;
        $tablo6 = $this->genel_model->veri_cek_cs("form_atamalari", $select, $kosul);
        
        if (is_array($tablo6))
        {
            foreach ($tablo6 as $key=>$arr)
            {
                if (in_array($arr['pernr'], $akk) && ($arr['ft_id'] == 1 || $arr['ft_id'] == 2))
                {
                    continue;
                }
                
                $data['formlar_ast2'][$arr['ft_id']][$arr['pernr']] = $arr['form_id'];
                
                // Onay durumunu çek..
                unset($kosul);
                $select = '*';
                $kosul['yil'] = $yil;
                $kosul['donem'] = $donem;
                $kosul['ft_id'] = $arr['ft_id'];
                $kosul['degerlendiren'] = $data['ust_pernr'][$arr['pernr']];
                $kosul['onaylayan'] = $this->session->userdata('pernr');
                $od_ast2 = $this->genel_model->veri_cek_ts("onaylar", $select, $kosul);
                if (is_array($od_ast2))
                {
                    $data['od_ast2'][$arr['ft_id']][$data['ust_pernr'][$arr['pernr']]] = $od_ast2;
                    
                    // Astlarının astlarına onay vermemiş ise bir üste onaya göndertme..
                    if ($od_ast2['onay_durum'] != 1)
                    {
                        $data['od_ast_kontrol'][$arr['ft_id']] = 3;
                        $data['k_ast_kontrol'][$arr['ft_id']] = 3;
                    }
                }
                else
                {
                    $data['od_ast_kontrol'][$arr['ft_id']] = 3;
                    $data['k_ast_kontrol'][$arr['ft_id']] = 3;
                        
                    // Onaya gönderilmesi bekleniyor..
                    $data['od_ast2'][$arr['ft_id']][$data['ust_pernr'][$arr['pernr']]]['onay_durum'] = 3;
                    $data['od_ast2'][$arr['ft_id']][$data['ust_pernr'][$arr['pernr']]]['onay_text'] = "Onaya Gönderilmesi Bekleniyor";
                }
                
                unset($kosul);
                $select = 'sum(puan) as a';
                $kosul['yil'] = $yil;
                $kosul['form_id'] = $arr['form_id'];
                $kosul['pernr'] = $arr['pernr'];
                $kosul['degerlendiren_ack'] = 'yon1';
                // Yarıyıl ise sadece yarıyılın puanını al
                if ($donem == 2)
                {
                    $kosul['donem'] = 2;
                }
                $puan = $this->genel_model->veri_cek_ts("degerlendirmeler", $select, $kosul);
                $data['puan'][$arr['ft_id']][$arr['pernr']] = $puan['a'];
                
                //tüm astlar için veri oluşmuş ise onaya gönderilebilir.
                unset($kosul);
                $select = 'count(*) as a';
                $kosul['yil'] = $yil;
                $kosul['form_id'] = $arr['form_id'];
                $kosul['pernr'] = $arr['pernr'];
                $kosul['degerlendiren_ack'] = 'yon1';
                $kosul['donem'] = $donem;
                $kayit = $this->genel_model->veri_cek_ts("degerlendirmeler", $select, $kosul);

                if ($kayit['a'] == 0)
                {
                    $data['od_ast2_kontrol'][$arr['ft_id']][$data['ust_pernr'][$arr['pernr']]] = 2;
                }
            }
        }
        
        // Formları sırala
        ksort($data['formlar']);
        ksort($data['formlar_ast']);
        ksort($data['formlar_ast2']);
        
        $this->template->goster('takvim', $data);
    }
    
    public function form_goruntule($form_id, $rilen_pernr, $yil, $donem, $ft_id)
    {
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

                $data['pot_puan'] = $puan;
            }
            
            //Görüşme doldurulmuşmu.
            unset($kosul);
            $select = '*';
            $kosul['yil'] = $yil;
            $kosul['form_id'] = $gorusme_formu['form_id'];
            $kosul['pernr'] = $rilen_pernr;
            $kosul['degerlendiren_ack'] = 'yon1';
            $kosul['donem'] = $donem;
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
            
//            echo "<pre>";
//            print_r($data);
//            echo "</pre>";
//            exit();
            
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
    
    public function form_kaydet()
    {
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
                $g_bilgi = $this->genel_model->veri_cek_ts("gostergeler", $select, $kosul);              
                
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
            $form_detay = $this->d_donemleri_model->formlar_detay($form_id);
            
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
                    
                    if ($who == 'rilen')
                    {
                        if ($arr['degerlendirme_turu'] == '2') // Objektif
                        {
                            if (isset($data[$sayac]['sonuc']))
                            {
                                if ($data[$sayac]['sonuc'] >= $arr['k1_as'] && $data[$sayac]['sonuc'] <= $arr['k1_us'] && $arr['k1_as'] != '' && $arr['k1_us'] != '')
                                {
                                    $data[$sayac]['duzey'] = 1;
                                }
                                else if ($data[$sayac]['sonuc'] >= $arr['k2_as'] && $data[$sayac]['sonuc'] <= $arr['k2_us'] && $arr['k2_as'] != '' && $arr['k2_us'] != '')
                                {
                                    $data[$sayac]['duzey'] = 2;
                                }
                                else if ($data[$sayac]['sonuc'] >= $arr['k3_as'] && $data[$sayac]['sonuc'] <= $arr['k3_us'] && $arr['k3_as'] != '' && $arr['k3_us'] != '')
                                {
                                    $data[$sayac]['duzey'] = 3;
                                }
                                else if ($data[$sayac]['sonuc'] >= $arr['k4_as'] && $data[$sayac]['sonuc'] <= $arr['k4_us'] && $arr['k4_as'] != '' && $arr['k4_us'] != '')
                                {
                                    $data[$sayac]['duzey'] = 4;
                                }
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
                    
                    $data[$sayac]['puan'] = $arr['agirlik']/$arr['periyod']/4*$data[$sayac]['duzey'];
                    $data[$sayac]['guncelleyen'] = $this->session->userdata('pernr');
                    $data[$sayac]['g_time'] = $time;
                    
                    $sayac++;
                }
            }
            
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
            $form_detay = $this->d_donemleri_model->formlar_detay($form_id);
            
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
                    $data[$sayac]['puan'] = $arr['agirlik']/$arr['periyod']/4*$data[$sayac]['duzey'];
                    
                    $sayac++;
                }
            }
            
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
            $form_detay = $this->d_donemleri_model->formlar_detay($form_id);
            
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
            if ($hp == 1)
            {
                $data[$sayac]['hp_ack'] = "Üst Göreve Atanabilir";
            }
            else if ($hp == 2)
            {
                $data[$sayac]['hp_ack'] = "Eş Düzey Göreve Geçebilir";
            }
            else if ($hp == 3)
            {
                $data[$sayac]['hp_ack'] = "İşten Ayrılmasında Sakınca Yoktur";
            }
            else if ($hp == 4)
            {
                $data[$sayac]['hp_ack'] = "Mevcut Göreve Devam Edebilir";
            }
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
            $alert_message = alert_message('Hata.');
            $this->session->set_flashdata('message', $alert_message);
            redirect('./home/');
            exit();
        }
        
        if ($onay_durum == 4)
        {
            if ($degerlendiren != $this->session->userdata('pernr'))
            {
                $alert_message = alert_message('Hata.');
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
        
        if ($tarih > $donem_listesi['bit'])
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

        $this->load->model("veri_al_model");
        $this->veri_al_model->kopyala2($yil, $donem, $degerlendiren);
        
        $alert_message = alert_message('Kayıt başarılı.', 'success');
        $this->session->set_flashdata('message', $alert_message);
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
        if ($data['kisi_sayisi'] > 10)
        {
            $data['kisi_sayisi_ba'] = round($data['kisi_sayisi']*0.15);
            $data['kisi_sayisi_b'] = $data['kisi_sayisi']-round($data['kisi_sayisi']*0.15)-round($data['kisi_sayisi']*0.25)-round($data['kisi_sayisi']*0.1);
            $data['kisi_sayisi_bu']  = round($data['kisi_sayisi']*0.25);
            $data['kisi_sayisi_bcu']  = round($data['kisi_sayisi']*0.1);
        }
        else if ($data['kisi_sayisi'] == 10)
        {
            $data['kisi_sayisi_ba'] = 1;
            $data['kisi_sayisi_b'] = 5;
            $data['kisi_sayisi_bu']  = 3;
            $data['kisi_sayisi_bcu']  = 1;
        }
        else if ($data['kisi_sayisi'] == 9)
        {
            $data['kisi_sayisi_ba'] = 1;
            $data['kisi_sayisi_b'] = 5;
            $data['kisi_sayisi_bu']  = 2;
            $data['kisi_sayisi_bcu']  = 1;
        }
        else if ($data['kisi_sayisi'] == 8)
        {
            $data['kisi_sayisi_ba'] = 1;
            $data['kisi_sayisi_b'] = 4;
            $data['kisi_sayisi_bu']  = 2;
            $data['kisi_sayisi_bcu']  = 1;
        }
        else if ($data['kisi_sayisi'] == 7)
        {
            $data['kisi_sayisi_ba'] = 1;
            $data['kisi_sayisi_b'] = 4;
            $data['kisi_sayisi_bu']  = 1;
            $data['kisi_sayisi_bcu']  = 1;
        }
        else if ($data['kisi_sayisi'] == 6)
        {
            $data['kisi_sayisi_ba'] = 1;
            $data['kisi_sayisi_b'] = 3;
            $data['kisi_sayisi_bu']  = 1;
            $data['kisi_sayisi_bcu']  = 1;
        }
        else if ($data['kisi_sayisi'] == 5)
        {
            $data['kisi_sayisi_ba'] = 1;
            $data['kisi_sayisi_b'] = 2;
            $data['kisi_sayisi_bu']  = 1;
            $data['kisi_sayisi_bcu']  = 1;
        }
        else if ($data['kisi_sayisi'] == 4)
        {
            $data['kisi_sayisi_b'] = 3;
            $data['kisi_sayisi_bu']  = 1;
        }
        else if ($data['kisi_sayisi'] == 3)
        {
            $data['kisi_sayisi_b'] = 2;
            $data['kisi_sayisi_bu']  = 1;
        }
        else if ($data['kisi_sayisi'] == 2 || $data['kisi_sayisi'] == 1)
        {

        }
        
        // Kademelendiren Kişinin kendisi veya yöneticisi değilse hata ver..
        unset($kosul);
        $select = '*';
        $kosul['yil'] = $yil;
        $kosul['donem'] = $donem;
        $kosul['pernr'] = $k_pernr;
        $yon1 = $this->genel_model->veri_cek_ts("organizasyon_d", $select, $kosul);
        
        if (!is_array($yon1))
        {
            $alert_message = alert_message('Hata.');
            $this->session->set_flashdata('message', $alert_message);
            redirect('./home/');
            exit();
        }
        
        if ($k_pernr != $this->session->userdata['pernr'] 
        &&  $yon1['ust_pernr'] != $this->session->userdata['pernr']
        )
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
        
        foreach ($data['siralama'] as $key=>$arr)
        {
            if ($arr['sk'] == 1)
            {
                $data['md']['kisi_sayisi_ba']++;
            }
            else if ($arr['sk'] == 2)
            {
                $data['md']['kisi_sayisi_b']++;
            }
            else if ($arr['sk'] == 3)
            {
                $data['md']['kisi_sayisi_bu']++;
            }
            else if ($arr['sk'] == 4)
            {
                $data['md']['kisi_sayisi_bcu']++;
            }
        }
        
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

        if (is_array($onaylar))
        {
            if ($onaylar['onay_durum'] == 1 || $onaylar['onay_durum'] == 4)
            {
                $data['degisiklik'] = false;
            }
        }
//        echo '<pre>';
//        print_r($data);
//        echo '</pre>';
//        exit();
        $this->template->goster('kademelendirme_detay', $data);
        
        // Kademelendirmeyi iptal edince kendi değerlendirip kademelendirdikleri için onay durumunu update et.
        
    }
    
    public function kademelendirmeyi_baslat($yil, $donem, $havuz_id)
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
        if ($k_pernr != $this->session->userdata['pernr'])
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
        if ($data['kisi_sayisi'] > 10)
        {
            $data['kisi_sayisi_ba'] = round($data['kisi_sayisi']*0.15);
            $data['kisi_sayisi_b'] = $data['kisi_sayisi']-round($data['kisi_sayisi']*0.15)-round($data['kisi_sayisi']*0.25)-round($data['kisi_sayisi']*0.1);
            $data['kisi_sayisi_bu']  = round($data['kisi_sayisi']*0.25);
            $data['kisi_sayisi_bcu']  = round($data['kisi_sayisi']*0.1);
        }
        else if ($data['kisi_sayisi'] == 10)
        {
            $data['kisi_sayisi_ba'] = 1;
            $data['kisi_sayisi_b'] = 5;
            $data['kisi_sayisi_bu']  = 3;
            $data['kisi_sayisi_bcu']  = 1;
        }
        else if ($data['kisi_sayisi'] == 9)
        {
            $data['kisi_sayisi_ba'] = 1;
            $data['kisi_sayisi_b'] = 5;
            $data['kisi_sayisi_bu']  = 2;
            $data['kisi_sayisi_bcu']  = 1;
        }
        else if ($data['kisi_sayisi'] == 8)
        {
            $data['kisi_sayisi_ba'] = 1;
            $data['kisi_sayisi_b'] = 4;
            $data['kisi_sayisi_bu']  = 2;
            $data['kisi_sayisi_bcu']  = 1;
        }
        else if ($data['kisi_sayisi'] == 7)
        {
            $data['kisi_sayisi_ba'] = 1;
            $data['kisi_sayisi_b'] = 4;
            $data['kisi_sayisi_bu']  = 1;
            $data['kisi_sayisi_bcu']  = 1;
        }
        else if ($data['kisi_sayisi'] == 6)
        {
            $data['kisi_sayisi_ba'] = 1;
            $data['kisi_sayisi_b'] = 3;
            $data['kisi_sayisi_bu']  = 1;
            $data['kisi_sayisi_bcu']  = 1;
        }
        else if ($data['kisi_sayisi'] == 5)
        {
            $data['kisi_sayisi_ba'] = 1;
            $data['kisi_sayisi_b'] = 2;
            $data['kisi_sayisi_bu']  = 1;
            $data['kisi_sayisi_bcu']  = 1;
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
        
        if ($data['kisi_sayisi'] > 4)
        {
            $sayac = 0;
            foreach ($veri_kaydet as $key=>$arr)
            {
                $sayac++;
                if ($sayac >0 && $sayac <= $data['kisi_sayisi_bcu'])
                {
                    $veri_kaydet[$key]['vk'] = 4;
                    $veri_kaydet[$key]['sk'] = 4;
                }
                else if ($sayac > $data['kisi_sayisi_bcu'] && $sayac <= ($data['kisi_sayisi_bcu']+$data['kisi_sayisi_bu']))
                {
                    $veri_kaydet[$key]['vk']  = 3;
                    $veri_kaydet[$key]['sk']  = 3;
                }
                else if ($sayac > ($data['kisi_sayisi_bcu']+$data['kisi_sayisi_bu']) && $sayac <= ($data['kisi_sayisi_bcu']+$data['kisi_sayisi_bu']+$data['kisi_sayisi_b']))
                {
                    $veri_kaydet[$key]['vk']  = 2;
                    $veri_kaydet[$key]['sk']  = 2;
                }
                else if ($sayac > ($data['kisi_sayisi_bcu']+$data['kisi_sayisi_bu']+$data['kisi_sayisi_b']) && $sayac <= ($data['kisi_sayisi_ba']+$data['kisi_sayisi_b']+$data['kisi_sayisi_bu']+$data['kisi_sayisi_bcu']))
                {
                    $veri_kaydet[$key]['vk']  = 1;
                    $veri_kaydet[$key]['sk']  = 1;
                }
            }
        }
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
        if ($k_pernr != $this->session->userdata['pernr'])
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
        if ($k_pernr != $this->session->userdata['pernr'])
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
        
        $sayac = 0;
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
                    $alert_message = alert_message('Hata.');
                    $this->session->set_flashdata('message', $alert_message);
                    redirect('./home/');
                    exit();
                }
            }
            
            if ($sk[$arr['pernr']] == 4)
            {
                $y_kisi_sayisi_bcu++;
            }
            else if ($sk[$arr['pernr']] == 3)
            {
                $y_kisi_sayisi_bu++;
            }
            else if ($sk[$arr['pernr']] == 2)
            {
                $y_kisi_sayisi_b++;
            }
            else if ($sk[$arr['pernr']] == 1)
            {
                $y_kisi_sayisi_ba++;
            }
            
            $siralama[$key]['sk'] = $sk[$arr['pernr']];
            $siralama[$key]['guncelleyen'] = $this->session->userdata('pernr');
            $siralama[$key]['g_time'] = date('Y-m-d H:i:s');
        }
        
        // Kişi limitlerini kontrol et
        if ($sayac > 10)
        {
            $kisi_sayisi_ba = round($sayac*0.15);
            $kisi_sayisi_b = $sayac-round($sayac*0.15)-round($sayac*0.25)-round($sayac*0.1);
            $kisi_sayisi_bu = round($sayac*0.25);
            $kisi_sayisi_bcu = round($sayac*0.1);
        }
        else if ($sayac == 10)
        {
            $kisi_sayisi_ba = 1;
            $kisi_sayisi_b = 5;
            $kisi_sayisi_bu = 3;
            $kisi_sayisi_bcu = 1;
        }
        else if ($sayac == 9)
        {
            $kisi_sayisi_ba = 1;
            $kisi_sayisi_b = 5;
            $kisi_sayisi_bu = 2;
            $kisi_sayisi_bcu = 1;
        }
        else if ($sayac == 8)
        {
            $kisi_sayisi_ba = 1;
            $kisi_sayisi_b = 4;
            $kisi_sayisi_bu = 2;
            $kisi_sayisi_bcu = 1;
        }
        else if ($sayac == 7)
        {
            $kisi_sayisi_ba = 1;
            $kisi_sayisi_b = 4;
            $kisi_sayisi_bu = 1;
            $kisi_sayisi_bcu = 1;
        }
        else if ($sayac == 6)
        {
            $kisi_sayisi_ba = 1;
            $kisi_sayisi_b = 3;
            $kisi_sayisi_bu = 1;
            $kisi_sayisi_bcu = 1;
        }
        else if ($sayac == 5)
        {
            $kisi_sayisi_ba = 1;
            $kisi_sayisi_b = 2;
            $kisi_sayisi_bu = 1;
            $kisi_sayisi_bcu = 1;
        }
        else if ($sayac == 4)
        {
            $kisi_sayisi_b = 3;
            $kisi_sayisi_bu = 1;
        }
        else if ($sayac == 3)
        {
            $kisi_sayisi_b = 2;
            $kisi_sayisi_bu = 1;
        }
        
        if ($kisi_sayisi > 4)
        {
            $dag_mes = "<br/> Yapmaya çalıştığınız dağılım/olması gereken<br/>";
            $dag_mes = $dag_mes." Beklenen düzeyin altı : ".$y_kisi_sayisi_ba."/".$kisi_sayisi_ba." <br/>";
            $dag_mes = $dag_mes." Beklenen düzey : ".$y_kisi_sayisi_b."/".$kisi_sayisi_b." <br/>";
            $dag_mes = $dag_mes." Beklenen düzeyin üstü : ".$y_kisi_sayisi_bu."/".$kisi_sayisi_bu." <br/>";
            $dag_mes = $dag_mes." Beklenen düzeyin çok üstü : ".$y_kisi_sayisi_bcu."/".$kisi_sayisi_bcu." <br/>";
        }
        
        if ($sayac > 4)
        {
            if ($y_kisi_sayisi_bcu != $kisi_sayisi_bcu)
            {
                $alert_message = alert_message('Olması gereken dağılım sayılarına uymalısınız.'.$dag_mes);
                $this->session->set_flashdata('message', $alert_message);
                redirect('/d_donemleri/kademelendirme_detay/'.$yil.'/'.$donem.'/'.$havuz_id);
                exit();
            }
            if ($y_kisi_sayisi_bu != $kisi_sayisi_bu)
            {
                $alert_message = alert_message('Olması gereken dağılım sayılarına uymalısınız.'.$dag_mes);
                $this->session->set_flashdata('message', $alert_message);
                redirect('/d_donemleri/kademelendirme_detay/'.$yil.'/'.$donem.'/'.$havuz_id);
                exit();
            }
            if ($y_kisi_sayisi_b != $kisi_sayisi_b)
            {
                $alert_message = alert_message('Olması gereken dağılım sayılarına uymalısınız.'.$dag_mes);
                $this->session->set_flashdata('message', $alert_message);
                redirect('/d_donemleri/kademelendirme_detay/'.$yil.'/'.$donem.'/'.$havuz_id);
                exit();
            }
            if ($y_kisi_sayisi_ba != $kisi_sayisi_ba)
            {
                $alert_message = alert_message('Olması gereken dağılım sayılarına uymalısınız.'.$dag_mes);
                $this->session->set_flashdata('message', $alert_message);
                redirect('/d_donemleri/kademelendirme_detay/'.$yil.'/'.$donem.'/'.$havuz_id);
                exit();
            }
        }
//        else if ($sayac == 4)
//        {
//            if ($y_kisi_sayisi_bu+$y_kisi_sayisi_bcu != $kisi_sayisi_bu)
//            {
//                $alert_message = alert_message('Olması gereken dağılım sayılarına uymalısınız.'.$dag_mes);
//                $this->session->set_flashdata('message', $alert_message);
//                redirect('/d_donemleri/kademelendirme_detay/'.$yil.'/'.$donem.'/'.$havuz_id);
//                exit();
//            }
//            if ($y_kisi_sayisi_b != $kisi_sayisi_b)
//            {
//                $alert_message = alert_message('Olması gereken dağılım sayılarına uymalısınız.'.$dag_mes);
//                $this->session->set_flashdata('message', $alert_message);
//                redirect('/d_donemleri/kademelendirme_detay/'.$yil.'/'.$donem.'/'.$havuz_id);
//                exit();
//            }
//            if ($y_kisi_sayisi_ba != $kisi_sayisi_ba)
//            {
//                $alert_message = alert_message('Olması gereken dağılım sayılarına uymalısınız.'.$dag_mes);
//                $this->session->set_flashdata('message', $alert_message);
//                redirect('/d_donemleri/kademelendirme_detay/'.$yil.'/'.$donem.'/'.$havuz_id);
//                exit();
//            }
//        }
        else if ($sayac == 3 || $sayac == 4)
        {
            if ($y_kisi_sayisi_bu+$y_kisi_sayisi_bcu != $kisi_sayisi_bu)
            {
                $alert_message = alert_message('Olması gereken dağılım sayılarına uymalısınız.'.$dag_mes);
                $this->session->set_flashdata('message', $alert_message);
                redirect('/d_donemleri/kademelendirme_detay/'.$yil.'/'.$donem.'/'.$havuz_id);
                exit();
            }
            if ($y_kisi_sayisi_b+$y_kisi_sayisi_ba != $kisi_sayisi_b)
            {
                $alert_message = alert_message('Olması gereken dağılım sayılarına uymalısınız.'.$dag_mes);
                $this->session->set_flashdata('message', $alert_message);
                redirect('/d_donemleri/kademelendirme_detay/'.$yil.'/'.$donem.'/'.$havuz_id);
                exit();
            }
        }
        
        
        unset($kosul);
        $kosul['yil'] = $yil;
        $kosul['donem'] = $donem;
        $kosul['havuz_id'] = $havuz_id;
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
    
}
