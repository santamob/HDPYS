<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ikyp extends MY_Controller {
    
    public function __construct() {
        parent::__construct();
        
        $this->load->model("ikyp_model");
        
        // Yetki kontrolü sadee ik anahtar kullanıcıları erişebilir..

        // Ik anahtar kullanıcıları veritabanında tablosu var fakat bu kısımda bağlı değil. 2024 Geliştirmelerinde mutlaka yer almalı. 07.06.2023

        if ($this->session->userdata('pernr') != 101993 && $this->session->userdata('pernr') != 101081 && $this->session->userdata('pernr') != 100003 && $this->session->userdata('pernr') != 102576)
        {
            $alert_message = alert_message('Hata.');
            $this->session->set_flashdata('message', $alert_message);
            redirect('/ikyp/donem_listesi/');
            exit();
        }
    }

    public function donem_listesi()
    {
        $select = '*';
        $tablo = $this->genel_model->veri_cek_cs("donem_listesi", $select);
        
        $data['donemler'] = $tablo;
        
        $select = '*';
        $tablo1 = $this->genel_model->veri_cek_cs("donem_av", $select);
        
        $data['donem_av'] = $tablo1;
        
        $select = '*';
        $tablo2 = $this->genel_model->veri_cek_cs("form_tipleri", $select);
        
        $data['form_tipleri'] = $tablo2;

        
        
        $this->template->goster('donem_listesi', $data);
    }
    
    public function donem_kaydet()
    {
        $yil = $this->input->post('yil');
        $donem = $this->input->post('donem');
        $bat = $this->input->post('bat');
        $bit = $this->input->post('bit');
        $ft = $this->input->post('ft');
        $kademelendirme = $this->input->post('kademelendirme');
        $donem_listesi_id = $this->input->post('donem_listesi_id');
        $lokasyon = $this->input->post('lokasyon');
        // Değerler boşsa hata ver..
        if (empty_f($donem_listesi_id))
        {
            //if (empty_f($yil) || empty_f($donem) || empty_f($bat) || empty_f($bit) || empty_f($kademelendirme) || empty_f($lokasyon) || !is_array($ft))
            if (empty_f($yil) || empty_f($donem) || empty_f($bat) || empty_f($bit) || empty_f($kademelendirme) || !is_array($ft))

            {
                $alert_message = alert_message('Tüm alanları doldurunuz.');
                $this->session->set_flashdata('message', $alert_message);
                redirect('/ikyp/donem_listesi/');
                exit();
            }
        }
        else
        {
            if (empty_f($bat) || empty_f($bit) || empty_f($kademelendirme) || !is_array($ft))
            {
                $alert_message = alert_message('Tüm alanları doldurunuz.');
                $this->session->set_flashdata('message', $alert_message);
                redirect('/ikyp/donem_listesi/');
                exit();
            }
        }
        
        // Başlangıç ve bitiş tarihlerini kontrol et..
        if ($bit < $bat)
        {
            $alert_message = alert_message('Bitiş tarihi başlangıç tarihinden küçük olamaz.');
            $this->session->set_flashdata('message', $alert_message);
            redirect('/ikyp/donem_listesi/');
            exit();
        }
        
        unset($kosul);
        $select = '*';
        $kosul['donem_id'] = $donem;
        $tablo1 = $this->genel_model->veri_cek_ts("donem_av", $select, $kosul);
        $donem_ack = $tablo1['ack'];
        
        $data['bat'] = $bat;
        $data['bit'] = $bit;
        $data['ft'] = implode(',', $ft);
        $data['lokasyon'] = implode('-', $lokasyon);
        $data['kademelendirme'] = $kademelendirme;
        $data['created_by'] = $this->session->userdata('pernr');
        $data['created_date'] = date('y-m-d H:i:s');
        
        // Dönem listesi id doluysa güncellemedir veriyi güncelle, değilse kaydet..
        if (!empty_f($donem_listesi_id))
        {
            unset($kosul);
            $kosul['id'] = $donem_listesi_id;
            $r = $this->genel_model->veri_guncelle("donem_listesi", $data, $kosul);
            
        }
        else
        {
            $data['yil'] = $yil;
            $data['donem'] = $donem;
            $data['donem_ack'] = $donem_ack;
            $r = $this->genel_model->veri_kaydet("donem_listesi", $data);
        }
        
        if (!$r)
        {
            $alert_message = alert_message('Teknik bir aksaklık oluştu daha sonra tekrar deneyiniz.');
            $this->session->set_flashdata('message', $alert_message);
            redirect('/ikyp/donem_listesi/');
            exit();
        }
        
        $alert_message = alert_message('Kayıt başarılı.', 'success');
        $this->session->set_flashdata('message', $alert_message);
        redirect('/ikyp/donem_listesi/');
        exit();
    }
    
    public function organizasyon_guncelle()
    {
        $yil = $this->input->post('yil');
        $donem = $this->input->post('donem');

        //echo "Donem :".$donem."<br />";
        
        if (empty_f($yil) || empty_f($donem))
        {
            $alert_message = alert_message('Yıl ve dönem zorunludur.');
            $this->session->set_flashdata('message', $alert_message);
            redirect('/ikyp/donem_listesi/');
            exit();
        }
        
        if ($_SERVER['CI_ENV'] == "qa")
        {
            // Veri güncelle
            $uri = 'http://192.168.1.54/peryonsis/organizasyon.php?yil='.$yil.'&donem='.$donem.'&sys=seq';
        }
        else if ($_SERVER['CI_ENV'] == "production")
        {
            // Veri güncelle
            $uri = 'http://192.168.1.54/peryonsis/organizasyon.php?yil='.$yil.'&donem='.$donem.'&sys=sep';
        }
        else if ($_SERVER['CI_ENV'] == "development")
        {
            // Veri güncelle
            $uri = 'http://192.168.1.54/peryonsis/organizasyon.php?yil='.$yil.'&donem='.$donem.'&sys=sed';
        }
        
        $ch = curl_init(); 
        curl_setopt($ch, CURLOPT_URL, $uri); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
        $output = curl_exec($ch); 
        curl_close($ch); 
        


        if (trim($output) != 'OK')
        {
            $alert_message = alert_message('SAP sisteminden veri alınamadı');
            $this->session->set_flashdata('message', $alert_message);
            redirect('/ikyp/donem_listesi/');
            exit();
        }
        
        // Bu dönem ile ilgili her şeyi sil..
        $r = $this->ikyp_model->donem_sil($yil,$donem);
        
        if (!$r)
        {
            $alert_message = alert_message('Veriler silinirken hata oluştu.');
            $this->session->set_flashdata('message', $alert_message);
            redirect('/ikyp/donem_listesi/');
            exit();
        }
        
        //Organizasyon şemasını performans için uyarla..
        //Aktif personeli çek
        unset($kosul);
        $select = "*";
        $tablo = $this->genel_model->veri_cek_cs("aktif_pernr", $select);
        
        $sayac = 0;
        //echo "<pre>";
        foreach ($tablo as $key=>$arr)
        {

            /*if($arr['pernr'] != 102304)
            {
                continue;
            }*/


            // Çalışanın en uzun pozisyonunu bul..
            unset($kosul);
            unset($order_by);
            $select = "*";
            $kosul['pernr'] = $arr['pernr'];
            $kosul['yil'] = $yil;
            $kosul['donem'] = $donem;
            $kosul['otype'] = 'P';
            $kosul['prozt'] = 100;
            $order_by['endda'] = 'ASC';
            $tablo2 = $this->genel_model->veri_cek_cs("organizasyon", $select, $kosul, $order_by);
            
            if (!$tablo2)
            {
                //Çalışan 1 ekim sonrasında işe girmiş şemada yer almaz.
                continue;
            }


            //TEYİT : Yelda Hanımdan Alınan bilgiye göre Ara Dönemde -> Son Pozisyon , Yıl Sonunda -> En Uzun Kaldığı Pozisyon. Tarih 10-05-2023

            $tarih1 = $yil.'-01-01';
            unset($pks_a);
            foreach ($tablo2 as $key2=>$arr2)
            {
                if ($arr2['endda'] == '9999-12-31')
                {
                    $tarih2 = $yil.'-12-31';
                }
                else
                {
                    $tarih2 = $arr2['endda'];
                }
                // pozisyonda kaldığı süre..
                $pks = strtotime($tarih2)-strtotime($tarih1);
                //echo "PKS: ".$pks."<br />";
                $pks_a[$arr2['sobid']] = $pks_a[$arr2['sobid']]+$pks;
                
                $tarih1 = $arr2['endda'];
            }

            //print_r($pks_a);

            //echo "<br />";

            $en_buyuk = 0;
            $en_uzun_poz = "";


            

            if($donem == 2)
            {

                // Ara Dönemde en güncel halini aladık Yelda Hanımdan teyit alınmıştır. 17.05.2023
                foreach ($pks_a as $key3=>$val3)
                {
                        $en_buyuk = $val3;
                        $en_uzun_poz = $key3;
                }
                //echo "En Güncel : ".$en_uzun_poz."<br />";
            }
            elseif($donem == 3)
            {
                

                //pks_a arrayi tüm pozisyonlardaki çalıştığı zamanları tutyor.
                // Birden fazla data gelirse Array Length -1 bana son pozisyonu varir
                // Eğer tek data geliyorsa güncel durumdur.

                
                foreach ($pks_a as $key3=>$val3)
                {
                    if ($val3 >= $en_buyuk)
                    {
                        $en_buyuk = $val3;
                        $en_uzun_poz = $key3;
                    }
                }
            }

            


            //echo $arr["stext"]."==>".$arr['pernr']."===>". $en_uzun_poz."<br />";
            
            
            unset($kosul);
            $select = "stext, sobid, yntci";
            $kosul['otype'] = 'S';
            $kosul['objid'] = $en_uzun_poz;
            $kosul['yil'] = $yil;
            $kosul['donem'] = $donem;
            $tablo3 = $this->genel_model->veri_cek_ts("organizasyon", $select, $kosul);


            
            /*echo "<br />".$this->db->last_query()."<br />";

            echo "S Kısmı Bakıldı Sonucu"."<br />";
            */

            //echo "Tablo-3"."<br />";
            //print_r($tablo3);


            


            if ($tablo3)
            {
                $poz_text = $tablo3['stext'];
                $org_id = $tablo3['sobid'];
                $yonetici_kontrol = $tablo3['yntci'];
            }
            else
            {
                continue;
                
            }

            unset($kosul);
            unset($order_by);
            $select = "stext,sobid";
            $kosul['otype'] = 'O';
            $kosul['objid'] = $org_id;
            $kosul['yil'] = $yil;
            $kosul['donem'] = $donem;
            $order_by['endda'] = 'DESC';
            $limit = 1;
            $tablo4 = $this->genel_model->veri_cek_ts("organizasyon", $select, $kosul, $order_by, $limit);


            //echo "Tablo-4"."<br />";
            //print_r($tablo4);

            

            if ($tablo4)
            {
                $org_sobid = $tablo4['sobid'];
                $org_text = $tablo4['stext'];
            }
            else
            {
                continue;
            }
            
            // Yöneticiyi bul
            if ($yonetici_kontrol == 'X')
            {
                // Ust organizasyonu bul
                unset($kosul);
                unset($order_by);
                $select = '*';
                $kosul['yil'] = $yil;
                $kosul['donem'] = $donem;
                $kosul['otype'] = 'O';
                $kosul['objid'] = $org_sobid;
                $order_by['endda'] = 'DESC';
                $limit = 1;
                $tablo5 = $this->genel_model->veri_cek_ts("organizasyon", $select, $kosul, $order_by, $limit);
                
                unset($kosul);
                unset($order_by);
                $select = '*';
                $kosul['yil'] = $yil;
                $kosul['donem'] = $donem;
                $kosul['otype'] = 'S';
                $kosul['sobid'] = $tablo5['objid'];
                $kosul['yntci'] = 'X';
                $order_by['endda'] = 'DESC';
                $limit = 1;
                $tablo6 = $this->genel_model->veri_cek_ts("organizasyon", $select, $kosul, $order_by, $limit);
            }
            else
            {
                unset($kosul);
                $select = '*';
                $kosul['yil'] = $yil;
                $kosul['donem'] = $donem;
                $kosul['otype'] = 'S';
                $kosul['sobid'] = $org_id;
                $kosul['yntci'] = 'X';
                $tablo6 = $this->genel_model->veri_cek_ts("organizasyon", $select, $kosul);

                //echo "Tablo-6"."<br />";
                //print_r($tablo6);


                //echo "<hr />";
            }
            
            $org_yeni[$sayac]['yil'] = $tablo2[0]['yil'];
            $org_yeni[$sayac]['donem'] = $tablo2[0]['donem'];
            $org_yeni[$sayac]['pernr'] = $tablo2[0]['pernr'];
            $org_yeni[$sayac]['ad_soyad'] = $tablo2[0]['stext'];
            $org_yeni[$sayac]['poz_id'] = $en_uzun_poz;
            $org_yeni[$sayac]['endda'] = $en_buyuk;
            $org_yeni[$sayac]['poz_text'] = $poz_text;
            $org_yeni[$sayac]['org_id'] = $org_id;
            $org_yeni[$sayac]['yonetici_kontrol'] = $yonetici_kontrol;
            $org_yeni[$sayac]['org_text'] = $org_text;
            $org_yeni[$sayac]['ust_pozid'] = $tablo6['objid'];
            $org_yeni[$sayac]['ust_sobid'] = $tablo6['sobid'];
            
            $sayac++;
            
        }

        //echo "Organizasyon D Tablo kayıdı<br />";
        //print_r($org_yeni);

        // veri kayıdı işlemleri
        $r = $this->genel_model->veri_kaydet_cs("organizasyon_d_temp", $org_yeni);
        
        if (!$r)
        {
            $alert_message = alert_message('Organizasyon Teknik bir aksaklık oluştu daha sonra tekrar deneyiniz.');
            $this->session->set_flashdata('message', $alert_message);
            redirect('/ikyp/donem_listesi/');
            exit();
        }
        
        foreach ($org_yeni as $key=>$arr)
        {
            unset($kosul);
            unset($order_by);
            $select = "*";
            $kosul['yil'] = $yil;
            $kosul['donem'] = $donem;
            $kosul['poz_id'] = $arr['ust_pozid'];
            $order_by['endda'] = 'DESC';
            $tablo7= $this->genel_model->veri_cek_cs("organizasyon_d_temp", $select, $kosul, $order_by);
            
            if ($tablo7)
            {
                $org_yeni[$key]['ust_pernr'] = $tablo7[0]['pernr'];
                $org_yeni[$key]['ust_ad_soyad'] = $tablo7[0]['ad_soyad'];
            }
            else
            {
                unset($kosul);
                unset($order_by);
                $select = "stext,sobid";
                $kosul['otype'] = 'O';
                $kosul['objid'] = $arr['ust_sobid'];
                $kosul['yil'] = $yil;
                $kosul['donem'] = $donem;
                $order_by['endda'] = 'DESC';
                $limit = 1;
                $tablo8 = $this->genel_model->veri_cek_ts("organizasyon", $select, $kosul, $order_by, $limit);

                $org_sobid = $tablo8['sobid'];
                $org_text = $tablo8['stext'];

                unset($kosul);
                $select = '*';
                $kosul['yil'] = $yil;
                $kosul['donem'] = $donem;
                $kosul['otype'] = 'S';
                $kosul['sobid'] = $org_sobid;
                $kosul['yntci'] = 'X';
                $tablo10 = $this->genel_model->veri_cek_ts("organizasyon", $select, $kosul);
                
                unset($kosul);
                unset($order_by);
                $select = "*";
                $kosul['yil'] = $yil;
                $kosul['donem'] = $donem;
                $kosul['poz_id'] = $tablo10['objid'];
                $order_by['endda'] = 'DESC';
                $tablo11 = $this->genel_model->veri_cek_cs("organizasyon_d_temp", $select, $kosul, $order_by);
                
                if ($tablo11)
                {
                    $org_yeni[$key]['ust_pernr'] = $tablo11[0]['pernr'];
                    $org_yeni[$key]['ust_ad_soyad'] = $tablo11[0]['ad_soyad'];
                }
            }

            //echo "<hr />";
            
        }
        
        $r = $this->genel_model->veri_kaydet_cs("organizasyon_d", $org_yeni);
        
        if (!$r)
        {
            $alert_message = alert_message('Teknik bir aksaklık oluştu daha sonra tekrar deneyiniz.');
            $this->session->set_flashdata('message', $alert_message);
            redirect('/ikyp/donem_listesi/');
            exit();
        }
        
        $alert_message = alert_message('Organizasyon bilgileri SAP sisteminden alınmıştır.', 'success');
        $this->session->set_flashdata('message', $alert_message);
        redirect('/ikyp/donem_listesi/');
        //exit();
        
    }
    
    public function organizasyon_semasi($yil, $donem)
    {
        if (empty_f($yil) || empty_f($donem))
        {
            $alert_message = alert_message('Yıl ve dönem zorunludur.');
            $this->session->set_flashdata('message', $alert_message);
            redirect('/ikyp/donem_listesi/');
            exit();
        }
        
        unset($kosul);
        $select = '*';
        $kosul['yil'] = $yil;
        $kosul['donem'] = $donem;
        $tablo = $this->genel_model->veri_cek_cs("organizasyon_d", $select, $kosul);
        
        if (!$tablo)
        {
            $alert_message = alert_message($yil.' - '.$donem.' dönemi için organizasyon şeması bulunamadı. Organizasyon bilgilerini SAP sisteminden almalısınız.');
            $this->session->set_flashdata('message', $alert_message);
            redirect('/ikyp/donem_listesi/');
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
        
        $data['sema'] = organizasyon_semasi('100000', $yil, $donem, '1');

        
        unset($kosul);
        $select = '*';
        $kosul['yil'] = $yil;
        $kosul['donem'] = $donem;
        $tablo2 = $this->genel_model->veri_cek_cs("organizasyon_d", $select, $kosul);
        $data['sema_tablo'] = $tablo2;
        
        unset($kosul);
        $select = '*';
        $kosul['id'] = explode(',', $tablo1['ft']);
        $tablo3 = $this->genel_model->veri_cek_cs("form_tipleri", $select, $kosul);
        $data['form_tipleri'] = $tablo3;
        
        unset($kosul);
        $kosul['durum'] = 1;
        $select = '*';
        $tablo4 = $this->genel_model->veri_cek_cs("formlar", $select, $kosul);
        $data['formlar'] = $tablo4;
        
        unset($kosul);
        $select = '*';
        $kosul['yil'] = $yil;
        $kosul['donem'] = $donem;
        $tablo5 = $this->ikyp_model->form_atamalari($yil, $donem);
        
        if (is_array($tablo5))
        {
            foreach ($tablo5 as $key=>$arr)
            {
                if ($arr['form_adi'] == "")
                {
                    $data['form_atamalari'][$arr['pernr']][$arr['ft_id']] = "Statik Form";
                }
                else
                {
                    $data['form_atamalari'][$arr['pernr']][$arr['ft_id']] = $arr['form_adi'];
                }
            }
        }
        
        unset($kosul);
        $select = "*";
        $kosul['yil'] = $yil;
        $kosul['donem'] = $donem;
        $kosul['yonetici_kontrol'] = 'X';
        $tablo8 = $this->genel_model->veri_cek_cs("organizasyon_d", $select, $kosul);
        
        if (is_array($tablo8))
        {
            foreach ($tablo8 as $key=>$arr)
            {
                $data['kisiler'][$arr['pernr']] = $arr['ad_soyad'];
            }
        }
        
        unset($kosul);
        $select = '*';
        $tablo6 = $this->genel_model->veri_cek_cs("kademelendirme_havuzlari", $select);
        $data['havuzlar'] = $tablo6;
        
        $tablo7 = $this->ikyp_model->kademelendirme_atamalari($yil, $donem);
        
        if (is_array($tablo7))
        {
            foreach ($tablo7 as $key=>$arr)
            {
                $data['kademelendirme_atamalari'][$arr['pernr']] = $arr['havuz_text'];
                $data['kademelendirme_atamalari2'][$arr['pernr']] = $data['kisiler'][$arr['k_pernr']];
            }
        }


        
        $this->template->goster('organizasyon_semasi', $data);
    }
    
    public function per_kaydet()
    {
        $yil = $this->input->post('yil');
        $donem = $this->input->post('donem');
        $pernr = $this->input->post('pernr');
        $ad_soyad = $this->input->post('ad_soyad');
        $poz_id = $this->input->post('poz_id');
        $poz_text = $this->input->post('poz_text');
        $org_id = $this->input->post('org_id');
        $org_text = $this->input->post('org_text');
        $ust_pernr = $this->input->post('ust_pernr');
        $ust_ad_soyad = $this->input->post('ust_ad_soyad');
        $yonetici_kontrol = $this->input->post('yonetici_kontrol');
        
        $guncellenecek_id = $this->input->post('guncellenecek_id');
        
        // Değerler boşsa hata ver..
        if (empty_f($guncellenecek_id))
        {
            if (empty_f($yil) || empty_f($donem) || empty_f($pernr) || empty_f($ad_soyad)
                || empty_f($poz_id) || empty_f($poz_text) || empty_f($org_id) || empty_f($org_text)
                || empty_f($ust_pernr) || empty_f($ust_ad_soyad)
                )
            {
                $alert_message = alert_message('Tüm alanları doldurunuz.');
                $this->session->set_flashdata('message', $alert_message);
                redirect('/ikyp/organizasyon_semasi/'.$yil.'/'.$donem);
                exit();
            }
        }
        else
        {
            if (empty_f($yil) || empty_f($donem)
                || empty_f($poz_id) || empty_f($poz_text) || empty_f($org_id) || empty_f($org_text)
                || empty_f($ust_pernr) || empty_f($ust_ad_soyad)
                )
            {
                $alert_message = alert_message('Tüm alanları doldurunuz.');
                $this->session->set_flashdata('message', $alert_message);
                redirect('/ikyp/organizasyon_semasi/'.$yil.'/'.$donem);
                exit();
            }
        }
        
        // Değerlendirme dönemi başlamış mı kontrol et
        unset($kosul);
        $select = "*";
        $kosul['yil'] = $yil;
        $kosul['donem'] = $donem;
        $tablo1 = $this->genel_model->veri_cek_ts("donem_listesi", $select, $kosul);
        
//        if ($tablo1['bat'] < date('Y-m-d'))
//        {
//            $alert_message = alert_message('Değerlendirme dönemi başladıktan sonra organizasyon şeması üzerinde değişiklik yapamazsınız.');
//            $this->session->set_flashdata('message', $alert_message);
//            redirect('/ikyp/organizasyon_semasi/'.$yil.'/'.$donem);
//            exit();
//        }
        
        $data['poz_id'] = $poz_id;
        $data['poz_text'] = $poz_text;
        $data['org_id'] = $org_id;
        $data['org_text'] = $org_text;
        $data['ust_pernr'] = $ust_pernr;
        $data['ust_ad_soyad'] = $ust_ad_soyad;
        $data['yonetici_kontrol'] = $yonetici_kontrol;
        $data['endda'] = "9999-12-31";
        
        // Güncellenecek id doluysa güncellemedir veriyi güncelle, değilse kaydet..
        if (!empty_f($guncellenecek_id))
        {
            unset($kosul);
            $kosul['id'] = $guncellenecek_id;
            $r = $this->genel_model->veri_guncelle("organizasyon_d", $data, $kosul);
            
        }
        else
        {
            $data['yil'] = $yil;
            $data['donem'] = $donem;
            $data['pernr'] = $pernr;
            $data['ad_soyad'] = $ad_soyad;
            
            $r = $this->genel_model->veri_kaydet("organizasyon_d", $data);
        }
        
        if (!$r)
        {
            $alert_message = alert_message('Teknik bir aksaklık oluştu daha sonra tekrar deneyiniz.');
            $this->session->set_flashdata('message', $alert_message);
            redirect('/ikyp/organizasyon_semasi/'.$yil.'/'.$donem);
            exit();
        }
        
        $alert_message = alert_message('Kayıt başarılı.', 'success');
        $this->session->set_flashdata('message', $alert_message);
        redirect('/ikyp/organizasyon_semasi/'.$yil.'/'.$donem);
        exit();
    }
    
    public function per_sil()
    {
        $yil = $this->input->post('yil');
        $donem = $this->input->post('donem');
        
        $silinecek_id = $this->input->post('silinecek_id');
        
        // Değerler boşsa hata ver..
        if (empty_f($yil) || empty_f($donem) || empty_f($silinecek_id))
        {
            $alert_message = alert_message('Tüm alanları doldurunuz.');
            $this->session->set_flashdata('message', $alert_message);
            redirect('/ikyp/organizasyon_semasi/'.$yil.'/'.$donem);
            exit();
        }
        
        // Değerlendirme dönemi başlamış mı kontrol et
        unset($kosul);
        $select = "*";
        $kosul['yil'] = $yil;
        $kosul['donem'] = $donem;
        $tablo1 = $this->genel_model->veri_cek_ts("donem_listesi", $select, $kosul);
        
//        if ($tablo1['bat'] < date('Y-m-d'))
//        {
//            $alert_message = alert_message('Değerlendirme dönemi başladıktan sonra organizasyon şeması üzerinde değişiklik yapamazsınız.');
//            $this->session->set_flashdata('message', $alert_message);
//            redirect('/ikyp/organizasyon_semasi/'.$yil.'/'.$donem);
//            exit();
//        }
        
        unset($kosul);
        $kosul['id'] = $silinecek_id;
        $r1 = $this->genel_model->veri_cek_ts("organizasyon_d", "*", $kosul);
        
        $pernr = $r1['pernr'];
        
        $r = "";
        if(!empty($pernr) && strlen($pernr) > 0 && $pernr > 0){
            $r = $this->ikyp_model->per_sil($yil, $donem, $pernr, $silinecek_id);
        }
        else{

            $alert_message = alert_message('Kullanıcı silme aşamasında problem meydana geldi.');
            $this->session->set_flashdata('message', $alert_message);
            redirect('/ikyp/organizasyon_semasi/'.$yil.'/'.$donem);
            exit();

        }
       
        
        if (!$r)
        {
            $alert_message = alert_message('Teknik bir aksaklık oluştu daha sonra tekrar deneyiniz.');
            $this->session->set_flashdata('message', $alert_message);
            redirect('/ikyp/organizasyon_semasi/'.$yil.'/'.$donem);
            exit();
        }
        
        $alert_message = alert_message('Kayıt silindi.', 'success');
        $this->session->set_flashdata('message', $alert_message);
        redirect('/ikyp/organizasyon_semasi/'.$yil.'/'.$donem);
        exit();
    }
    
    public function form_tipleri()
    {
        $select = '*';
        $tablo = $this->genel_model->veri_cek_cs("form_tipleri", $select);
        
        $data['form_tipleri'] = $tablo;
        
        $this->template->goster('form_tipleri', $data);
    }
    
    public function ft_kaydet()
    {
        $ft_text = $this->input->post('ft_text');
        
        $guncellenecek_id = $this->input->post('guncellenecek_id');
        
        // Değerler boşsa hata ver..
        if (empty_f($ft_text))
        {
            $alert_message = alert_message('Tüm alanları doldurunuz.');
            $this->session->set_flashdata('message', $alert_message);
            redirect('/ikyp/form_tipleri/');
            exit();
        }
        
        
        $data['ft_text'] = $ft_text;
        
        // Güncellenecek id doluysa güncellemedir veriyi güncelle, değilse kaydet..
        if (!empty_f($guncellenecek_id))
        {
            unset($kosul);
            $kosul['id'] = $guncellenecek_id;
            $r = $this->genel_model->veri_guncelle("form_tipleri", $data, $kosul);
            
        }
        else
        {
            $data['ft'] = $ft;
            
            $r = $this->genel_model->veri_kaydet("form_tipleri", $data);
        }
        
        if (!$r)
        {
            $alert_message = alert_message('Teknik bir aksaklık oluştu daha sonra tekrar deneyiniz.');
            $this->session->set_flashdata('message', $alert_message);
            redirect('/ikyp/form_tipleri/');
            exit();
        }
        
        $alert_message = alert_message('Kayıt başarılı.', 'success');
        $this->session->set_flashdata('message', $alert_message);
        redirect('/ikyp/form_tipleri/');
        exit();
    }
    
    // public function gostergeler()
    // {
    //     $select = '*';
    //     $order_by['g_id'] = 'desc';
    //     $tablo = $this->genel_model->veri_cek_cs("gostergeler", $select, '', $order_by);
    //     $data['gostergeler'] = $tablo;
        
    //     $select = '*';
    //     $tablo1 = $this->genel_model->veri_cek_cs("veri_kaynagi_av", $select);
    //     $data['veri_kaynagi'] = $tablo1;
        
    //     $select = '*';
    //     $tablo2 = $this->genel_model->veri_cek_cs("veri_hesaplama_av", $select);
    //     $data['veri_hesaplama'] = $tablo2;
        
    //     $select = '*';
    //     $tablo3 = $this->genel_model->veri_cek_cs("degerlendirme_turu_av", $select);
    //     $data['degerlendirme_turu'] = $tablo3;
        
    //     $select = '*';
    //     $tablo4 = $this->genel_model->veri_cek_cs("form_tipleri", $select);
    //     $data['form_tipleri'] = $tablo4;
        
    //     $this->template->goster('gostergeler', $data);
    // }

    public function gostergelerv2()
    {
        
        // $select = '*';
        // $order_by['g_id'] = 'desc';
        // $tablo = $this->genel_model->veri_cek_cs("gostergeler", $select, '', $order_by);
        // $data['gostergeler'] = $tablo;
        
        $select = '*';
        $tablo1 = $this->genel_model->veri_cek_cs("veri_kaynagi_av", $select);
        $data['veri_kaynagi'] = $tablo1;
        
        $select = '*';
        $tablo2 = $this->genel_model->veri_cek_cs("veri_hesaplama_av", $select);
        $data['veri_hesaplama'] = $tablo2;
        
        $select = '*';
        $tablo3 = $this->genel_model->veri_cek_cs("degerlendirme_turu_av", $select);
        $data['degerlendirme_turu'] = $tablo3;
        
        $select = '*';
        $tablo4 = $this->genel_model->veri_cek_cs("form_tipleri", $select);
        $data['form_tipleri'] = $tablo4;
        

        // Mükerreriyet için ikyp Modelda query ile sorgula
        $data["DuplicatedGostergeler"] = $this->ikyp_model->IsThereAnyDuplicatedGostergeExist();

        $this->template->goster('gostergeler2', $data);
    }



    public function get_modal_data_genarator($id){
        
        $modal_data = $this->genel_model->get_modal_data($id);
        $arr = [];
        foreach($modal_data as $modal){
            $arr['g_id'] = $modal['g_id'];
            $arr['ft_id'] = $modal["ft_id"];
            $arr['ft_text'] = $modal["ft_text"];
            $arr['g_adi'] = $modal["g_adi"];
            $arr['periyod'] = $modal["periyod"];
            $arr['g_text'] = $modal["g_text"];
            $arr['g_desc'] = $modal["g_desc"];
            $arr['kategori'] = $modal["kategori"];
            $arr['planlanan_text'] = $modal["planlanan_text"];
            $arr['gerceklesen_text'] = $modal["gerceklesen_text"];
            $arr['sonuc_text'] = $modal["sonuc_text"];
            $arr['veri_kaynagi'] = $modal["veri_kaynagi"];
            $arr['veri_kaynagi_text'] = $modal["veri_kaynagi_text"];
            $arr['veri_hesaplama'] = $modal["veri_hesaplama"];
            $arr['veri_hesaplama_text'] = $modal["veri_hesaplama_text"];
            $arr['degerlendirme_turu'] = $modal["degerlendirme_turu"];
            $arr['degerlendirme_turu_text'] = $modal["degerlendirme_turu_text"];
            $arr['kmin_text'] = $modal["kmin_text"];
            $arr['kmax_text'] = $modal["kmax_text"];

            if($modal["ft_id"] == 1){
                $arr["kademeler"][] = [
                    "kademe_text"=>$modal['kademe_text'],
                    "kademe_as"=>$modal['kademe_as'],
                    "kademe_us"=>$modal['kademe_us']
                ];
            }
            if($modal["ft_id"] == 2 || $modal["ft_id"] == 3 ){
                $arr["kademeler"][] = [
                    "kademe_text"=>$modal['kademe_text'],
                ];
            }
           
        }
        return $arr;
    }

    public function get_detay_model_html($id){

        $select = '*';
        $tablo1 = $this->genel_model->veri_cek_cs("veri_kaynagi_av", $select);
        $data['veri_kaynagi'] = $tablo1;
        
        $select = '*';
        $tablo2 = $this->genel_model->veri_cek_cs("veri_hesaplama_av", $select);
        $data['veri_hesaplama'] = $tablo2;
        
        $select = '*';
        $tablo3 = $this->genel_model->veri_cek_cs("degerlendirme_turu_av", $select);
        $data['degerlendirme_turu'] = $tablo3;
       
        //$data["arr"] = $this->genel_model->get_modal_data($id);
        $data["arr"] = $this->get_modal_data_genarator($id);

        

        $this->template->goster('gosterge_detay_model',$data);
    }

    public function get_kopyala_model_html($id){


        //// yeni eklendi //// 
        $select = '*';
        $tablo1 = $this->genel_model->veri_cek_cs("veri_kaynagi_av", $select);
        $data['veri_kaynagi'] = $tablo1;
        
        $select = '*';
        $tablo2 = $this->genel_model->veri_cek_cs("veri_hesaplama_av", $select);
        $data['veri_hesaplama'] = $tablo2;
        
        $select = '*';
        $tablo3 = $this->genel_model->veri_cek_cs("degerlendirme_turu_av", $select);
        $data['degerlendirme_turu'] = $tablo3;
        
        //// yeni eklendi //// 
       
        //$data["arr"] = $this->genel_model->get_modal_data($id);
        $data["arr"] = $this->get_modal_data_genarator($id);
        $this->template->goster('gosterge_kopyala_model',$data);
 
    }

    public function get_pasif_model_html($id){
       
        //$data["arr"] = $this->genel_model->get_modal_data($id);
        $data["arr"] = $this->get_modal_data_genarator($id);
        $this->template->goster('gosterge_pasif_model',$data);
 
    }

    public function gosterge_table(){

        
        $table_data = [
            "draw" => $_POST["draw"],
            "start" =>  $_POST["start"],
            "length"=>$_POST["length"],
            "order"=>$_POST["order"],
            "dir"=>$_POST["order"][0]["dir"],
            "column"=>$_POST["order"][0]["column"],
            "order_by"=>$_POST["columns"][$column]["data"],
            "mysearch_list" => $_POST["mysearch_list"],
        ];

       

        $select = '*';
        $order_by['g_id'] = 'desc';
        //$tablo = $this->genel_model->veri_cek_cs2("gostergeler", $select, '', $order_by);
        $tablo = $this->genel_model->veri_cek_cs2("gostergelerv2",$table_data);
        $data['gostergeler'] = $tablo;

        

        echo json_encode ($data['gostergeler']);

    }



    public function gosterge_kaydet2(){
       
        $data = [];
        $k_texts = $this->input->post("k_text");
        $k_as_list = $this->input->post("k_as");
        $k_us_list = $this->input->post("k_us");
        

        $kademeler = [];
        foreach($k_texts as $key => $text){
            if($this->input->post('ft_id') == 1){
                $kademeler[] = [
                    "text" => $text,
                    "k_as" =>  $k_as_list[$key],
                    "k_us" =>  $k_us_list[$key]
                ];
            }
            else if($this->input->post('ft_id') == 2 || $this->input->post('ft_id') == 3){
                $kademeler[] = [
                    "text" => $text,
                ];
                
                $data["main"]['kmin_text'] = $this->input->post("kmin_desc");
                $data["main"]['kmax_text'] = $this->input->post("kmax_desc");
            }
        }
        
        
        $ft_id = $this->input->post('ft_id');
        $g_adi = $this->input->post('g_adi');
        $periyod = $this->input->post('periyod');
        $g_text = $this->input->post('g_text');
        $kategori = $this->input->post('kategori');
        $g_desc = $this->input->post('g_desc');
        $planlanan_text = $this->input->post('planlanan_text');
        $gerceklesen_text = $this->input->post('gerceklesen_text');
        $sonuc_text = $this->input->post('sonuc_text');
        $veri_kaynagi = $this->input->post('veri_kaynagi');
        $veri_hesaplama = $this->input->post('veri_hesaplama');
        $degerlendirme_turu = $this->input->post('degerlendirme_turu');
        $g_id = $this->input->post('g_id');

       
        if (empty_f($ft_id))
        {
            $alert_message = alert_message('Tüm alanları doldurunuz.');
            $this->session->set_flashdata('message', $alert_message);
            redirect('/ikyp/gostergelerv2/');
            exit();
        }
        
        if ($ft_id == 1) // Hedef formu
        {
            // Değerler boşsa hata ver..
            if (empty_f($g_adi) || empty_f($periyod) || empty_f($g_text)
                || empty_f($g_desc) || empty_f($planlanan_text) || empty_f($gerceklesen_text)
                || empty_f($sonuc_text) || empty_f($veri_kaynagi) || empty_f($veri_hesaplama)
                || empty_f($degerlendirme_turu)
                )
            {
                $alert_message = alert_message('Tüm alanları doldurunuz.');
                $this->session->set_flashdata('message', $alert_message);
                redirect('/ikyp/gostergelerv2/');
                exit();
            }
        }
        else if ($ft_id == 2) // Yetkinlik formu
        {
            // Değerler boşsa hata ver..
            if (empty_f($g_adi) || empty_f($periyod) || empty_f($g_text) || empty_f($kategori)
                || empty_f($g_desc) 
                // || empty_f($k1_text) || empty_f($k2_text)
                // || empty_f($k3_text) || empty_f($k4_text)
                )
            {
                $alert_message = alert_message('Tüm alanları doldurunuz.');
                $this->session->set_flashdata('message', $alert_message);
                redirect('/ikyp/gostergelerv2/');
                exit();
            }
        }
        else if ($ft_id == 3) // Potansiyel formu
        {
            // Değerler boşsa hata ver..
            if (empty_f($g_adi) || empty_f($periyod) || empty_f($g_text) || empty_f($kategori)
                || empty_f($g_desc) 
                // || empty_f($k1_text) || empty_f($k2_text)
                // || empty_f($k3_text) || empty_f($k4_text)
                )
            {
                $alert_message = alert_message('Tüm alanları doldurunuz.');
                $this->session->set_flashdata('message', $alert_message);
                redirect('/ikyp/gostergelerv2/');
                exit();
            }
        }
        else if ($ft_id == 4) // Görüşme formu
        {
            // Değerler boşsa hata ver..
            if (empty_f($g_adi) || empty_f($periyod) || empty_f($g_text))
            {
                $alert_message = alert_message('Tüm alanları doldurunuz.');
                $this->session->set_flashdata('message', $alert_message);
                redirect('/ikyp/gostergelerv2/');
                exit();
            }
        }
        else if ($ft_id == 5) // Görüşme formu
        {
            // Değerler boşsa hata ver..
            if (empty_f($g_adi) || empty_f($periyod) || empty_f($g_text))
            {
                $alert_message = alert_message('Tüm alanları doldurunuz.');
                $this->session->set_flashdata('message', $alert_message);
                redirect('/ikyp/gostergelerv2/');
                exit();
            }
        }
        


       


        unset($kosul);
        $select = '*';
        $kosul['id'] = $veri_kaynagi;
        $tablo1 = $this->genel_model->veri_cek_ts("veri_kaynagi_av", $select, $kosul);
        $data['main']['veri_kaynagi_text'] = $tablo1['text'];
        
        unset($kosul);
        $select = '*';
        $kosul['id'] = $veri_hesaplama;
        $tablo2 = $this->genel_model->veri_cek_ts("veri_hesaplama_av", $select, $kosul);
        $data['main']['veri_hesaplama_text'] = $tablo2['text'];
        
        unset($kosul);
        $select = '*';
        $kosul['id'] = $degerlendirme_turu;
        $tablo3 = $this->genel_model->veri_cek_ts("degerlendirme_turu_av", $select, $kosul);
        $data['main']['degerlendirme_turu_text'] = $tablo3['text'];
        
        unset($kosul);
        $select = '*';
        $kosul['id'] = $ft_id;
        $tablo3 = $this->genel_model->veri_cek_ts("form_tipleri", $select, $kosul);
        $data['main']['ft_text'] = $tablo3['ft_text'];
        
        $data['main']['ft_id'] = $ft_id;
        $data['main']['g_adi'] = $g_adi;
        $data['main']['periyod'] = $periyod;
        $data['main']['g_text'] = $g_text;
        $data['main']['g_desc'] = $g_desc;
        $data['main']['kategori'] = $kategori;
        $data['main']['planlanan_text'] = $planlanan_text;
        $data['main']['gerceklesen_text'] = $gerceklesen_text;
        $data['main']['sonuc_text'] = $sonuc_text;
        $data['main']['veri_kaynagi'] = $veri_kaynagi;
        $data['main']['veri_hesaplama'] = $veri_hesaplama;
        $data['main']['degerlendirme_turu'] = $degerlendirme_turu;
        $data['main']['durum'] = 1;
        $data['main']['guncelleyen'] = $this->session->userdata('pernr');
        $data['main']['g_time'] = date('Y-m-d H:i:s');
        $data['main']['g_id'] = $g_id;


        $data["kademeler"] = $kademeler;


        // echo "<pre>";
        // print_r($data);
        // exit();
        // unset($kosul);

        if ($g_id > 0){
            $r = $this->ikyp_model->updateGosterv2($data);
        }
        else{
            $r = $this->ikyp_model->addGostergev2($data);
        }
        
       // $r = $this->ikyp_model->addGostergev2($data);
        
        if (!$r)
        {
            $alert_message = alert_message('Teknik bir aksaklık oluştu daha sonra tekrar deneyiniz.');
            $this->session->set_flashdata('message', $alert_message);
            redirect('/ikyp/gostergelerv2/');
            exit();
        }
        
        $alert_message = alert_message('Kayıt başarılı.', 'success');
        $this->session->set_flashdata('message', $alert_message);
        redirect('/ikyp/gostergelerv2/');
        exit();

    }
    
    
    // public function gosterge_kaydet()
    // {
    //     $ft_id = $this->input->post('ft_id');
    //     $g_adi = $this->input->post('g_adi');
    //     $periyod = $this->input->post('periyod');
    //     $g_text = $this->input->post('g_text');
    //     $kategori = $this->input->post('kategori');
    //     $g_desc = $this->input->post('g_desc');
    //     $planlanan_text = $this->input->post('planlanan_text');
    //     $gerceklesen_text = $this->input->post('gerceklesen_text');
    //     $sonuc_text = $this->input->post('sonuc_text');
    //     $veri_kaynagi = $this->input->post('veri_kaynagi');
    //     $veri_hesaplama = $this->input->post('veri_hesaplama');
    //     $degerlendirme_turu = $this->input->post('degerlendirme_turu');
    //     $k1_text = $this->input->post('k1_text');
    //     $k1_as = $this->input->post('k1_as');
    //     $k1_us = $this->input->post('k1_us');
    //     $k2_text = $this->input->post('k2_text');
    //     $k2_as = $this->input->post('k2_as');
    //     $k2_us = $this->input->post('k2_us');
    //     $k3_text = $this->input->post('k3_text');
    //     $k3_as = $this->input->post('k3_as');
    //     $k3_us = $this->input->post('k3_us');
    //     $k4_text = $this->input->post('k4_text');
    //     $k4_as = $this->input->post('k4_as');
    //     $k4_us = $this->input->post('k4_us');
    //     $g_id = $this->input->post('g_id');
    //     $k1_desc = $this->input->post('k1_desc');
    //     $k4_desc = $this->input->post('k4_desc');
        
    //     if (empty_f($ft_id))
    //     {
    //         $alert_message = alert_message('Tüm alanları doldurunuz.');
    //         $this->session->set_flashdata('message', $alert_message);
    //         redirect('/ikyp/gostergelerv2/');
    //         exit();
    //     }
        
    //     if ($ft_id == 1) // Hedef formu
    //     {
    //         // Değerler boşsa hata ver..
    //         if (empty_f($g_adi) || empty_f($periyod) || empty_f($g_text)
    //             || empty_f($g_desc) || empty_f($planlanan_text) || empty_f($gerceklesen_text)
    //             || empty_f($sonuc_text) || empty_f($veri_kaynagi) || empty_f($veri_hesaplama)
    //             || empty_f($degerlendirme_turu) || empty_f($k1_text) || empty_f($k2_text) 
    //             || empty_f($k3_text) || empty_f($k4_text)
    //             )
    //         {
    //             $alert_message = alert_message('Tüm alanları doldurunuz.');
    //             $this->session->set_flashdata('message', $alert_message);
    //             redirect('/ikyp/gostergelerv2/');
    //             exit();
    //         }
    //     }
    //     else if ($ft_id == 2) // Yetkinlik formu
    //     {
    //         // Değerler boşsa hata ver..
    //         if (empty_f($g_adi) || empty_f($periyod) || empty_f($g_text) || empty_f($kategori)
    //             || empty_f($g_desc) || empty_f($k1_text) || empty_f($k2_text)
    //             || empty_f($k3_text) || empty_f($k4_text)
    //             )
    //         {
    //             $alert_message = alert_message('Tüm alanları doldurunuz.');
    //             $this->session->set_flashdata('message', $alert_message);
    //             redirect('/ikyp/gostergelerv2/');
    //             exit();
    //         }
    //     }
    //     else if ($ft_id == 3) // Potansiyel formu
    //     {
    //         // Değerler boşsa hata ver..
    //         if (empty_f($g_adi) || empty_f($periyod) || empty_f($g_text) || empty_f($kategori)
    //             || empty_f($g_desc) || empty_f($k1_text) || empty_f($k2_text)
    //             || empty_f($k3_text) || empty_f($k4_text)
    //             )
    //         {
    //             $alert_message = alert_message('Tüm alanları doldurunuz.');
    //             $this->session->set_flashdata('message', $alert_message);
    //             redirect('/ikyp/gostergelerv2/');
    //             exit();
    //         }
    //     }
    //     else if ($ft_id == 4) // Görüşme formu
    //     {
    //         // Değerler boşsa hata ver..
    //         if (empty_f($g_adi) || empty_f($periyod) || empty_f($g_text))
    //         {
    //             $alert_message = alert_message('Tüm alanları doldurunuz.');
    //             $this->session->set_flashdata('message', $alert_message);
    //             redirect('/ikyp/gostergelerv2/');
    //             exit();
    //         }
    //     }
    //     else if ($ft_id == 5) // Görüşme formu
    //     {
    //         // Değerler boşsa hata ver..
    //         if (empty_f($g_adi) || empty_f($periyod) || empty_f($g_text))
    //         {
    //             $alert_message = alert_message('Tüm alanları doldurunuz.');
    //             $this->session->set_flashdata('message', $alert_message);
    //             redirect('/ikyp/gostergelerv2/');
    //             exit();
    //         }
    //     }
        
    //     unset($kosul);
    //     $select = '*';
    //     $kosul['id'] = $veri_kaynagi;
    //     $tablo1 = $this->genel_model->veri_cek_ts("veri_kaynagi_av", $select, $kosul);
    //     $data['veri_kaynagi_text'] = $tablo1['text'];
        
    //     unset($kosul);
    //     $select = '*';
    //     $kosul['id'] = $veri_hesaplama;
    //     $tablo2 = $this->genel_model->veri_cek_ts("veri_hesaplama_av", $select, $kosul);
    //     $data['veri_hesaplama_text'] = $tablo2['text'];
        
    //     unset($kosul);
    //     $select = '*';
    //     $kosul['id'] = $degerlendirme_turu;
    //     $tablo3 = $this->genel_model->veri_cek_ts("degerlendirme_turu_av", $select, $kosul);
    //     $data['degerlendirme_turu_text'] = $tablo3['text'];
        
    //     unset($kosul);
    //     $select = '*';
    //     $kosul['id'] = $ft_id;
    //     $tablo3 = $this->genel_model->veri_cek_ts("form_tipleri", $select, $kosul);
    //     $data['ft_text'] = $tablo3['ft_text'];
        
    //     $data['ft_id'] = $ft_id;
    //     $data['g_adi'] = $g_adi;
    //     $data['periyod'] = $periyod;
    //     $data['g_text'] = $g_text;
    //     $data['g_desc'] = $g_desc;
    //     $data['kategori'] = $kategori;
    //     $data['planlanan_text'] = $planlanan_text;
    //     $data['gerceklesen_text'] = $gerceklesen_text;
    //     $data['sonuc_text'] = $sonuc_text;
    //     $data['veri_kaynagi'] = $veri_kaynagi;
    //     $data['veri_hesaplama'] = $veri_hesaplama;
    //     $data['degerlendirme_turu'] = $degerlendirme_turu;
    //     $data['k1_text'] = $k1_text;
    //     if ($k1_as)
    //     {
    //         $data['k1_as'] = $k1_as;
    //     }
    //     if ($k1_us)
    //     {
    //         $data['k1_us'] = $k1_us;
    //     }
    //     if ($k2_as)
    //     {
    //         $data['k2_as'] = $k2_as;
    //     }
    //     if ($k2_us)
    //     {
    //         $data['k2_us'] = $k2_us;
    //     }
    //     if ($k3_as)
    //     {
    //         $data['k3_as'] = $k3_as;
    //     }
    //     if ($k3_us)
    //     {
    //         $data['k3_us'] = $k3_us;
    //     }
    //     if ($k4_as)
    //     {
    //         $data['k4_as'] = $k4_as;
    //     }
    //     if ($k4_us)
    //     {
    //         $data['k4_us'] = $k4_us;
    //     }
    //     if ($k1_desc)
    //     {
    //         $data['k1_desc'] = $k1_desc;
    //     }
    //     if ($k4_desc)
    //     {
    //         $data['k4_desc'] = $k4_desc;
    //     }
        
    //     $data['k1_text'] = $k1_text;
    //     $data['k2_text'] = $k2_text;
    //     $data['k3_text'] = $k3_text;
    //     $data['k4_text'] = $k4_text;
    //     $data['durum'] = 1;
    //     $data['guncelleyen'] = $this->session->userdata('pernr');
    //     $data['g_time'] = date('Y-m-d H:i:s');
        
    //     unset($kosul);
    //     if ($g_id > 0)
    //     {
    //         $kosul['g_id'] = $g_id;
    //         $data['g_id'] = $g_id;
    //     }
        
    //     $r = $this->genel_model->veri_kaydet("gostergeler", $data, $kosul);
    //     if (!$r)
    //     {
    //         $alert_message = alert_message('Teknik bir aksaklık oluştu daha sonra tekrar deneyiniz.');
    //         $this->session->set_flashdata('message', $alert_message);
    //         redirect('/ikyp/gostergelerv2/');
    //         exit();
    //     }
        
    //     $alert_message = alert_message('Kayıt başarılı.', 'success');
    //     $this->session->set_flashdata('message', $alert_message);
    //     redirect('/ikyp/gostergelerv2/');
    //     exit();
    // }
    
    public function gosterge_pasife_al()
    {
        $g_id = $this->input->post('g_id');
        if (empty_f($g_id))
        {
            $alert_message = alert_message('Tüm alanları doldurunuz.');
            $this->session->set_flashdata('message', $alert_message);
            redirect('/ikyp/gostergelerv2/');
            exit();
        }
        
        $data['guncelleyen'] = $this->session->userdata('pernr');
        $data['g_time'] = date('Y-m-d H:i:s');
        $data['durum'] = 2;
        
        unset($kosul);
        $kosul['g_id'] = $g_id;
        //$r = $this->genel_model->veri_guncelle("gostergeler", $data, $kosul);
        $r = $this->genel_model->veri_guncelle("gostergelerv2", $data, $kosul);
        if (!$r)
        {
            $alert_message = alert_message('Teknik bir aksaklık oluştu daha sonra tekrar deneyiniz.');
            $this->session->set_flashdata('message', $alert_message);
            redirect('/ikyp/gostergelerv2/');
            exit();
        }
        
        $alert_message = alert_message('Kayıt başarılı.', 'success');
        $this->session->set_flashdata('message', $alert_message);
        //redirect('/ikyp/gostergeler/');
        redirect('/ikyp/gostergelerV2/');
        exit();
    }
    
    public function form_pasife_al()
    {
        $form_id = $this->input->post('form_id');
        if (empty_f($form_id))
        {
            $alert_message = alert_message('Tüm alanları doldurunuz.');
            $this->session->set_flashdata('message', $alert_message);
            redirect('/ikyp/gostergelerv2/');
            exit();
        }
        
        $data['guncelleyen'] = $this->session->userdata('pernr');
        $data['g_time'] = date('Y-m-d H:i:s');
        $data['durum'] = 2;
        
        unset($kosul);
        $kosul['form_id'] = $form_id;
        $r = $this->genel_model->veri_guncelle("formlar", $data, $kosul);
        if (!$r)
        {
            $alert_message = alert_message('Teknik bir aksaklık oluştu daha sonra tekrar deneyiniz.');
            $this->session->set_flashdata('message', $alert_message);
            redirect('/ikyp/formlar/');
            exit();
        }
        
        $alert_message = alert_message('Kayıt başarılı.', 'success');
        $this->session->set_flashdata('message', $alert_message);
        redirect('/ikyp/formlar/');
        exit();
    }
    
    public function formlar()
    {
        $select = '*';
        $tablo = $this->genel_model->veri_cek_cs("formlar", $select);
        $data['formlar'] = $tablo;
        
        if (is_array($tablo))
        {
            foreach ($tablo as $key=>$arr)
            {
                unset($kosul);
                $select = "*";
                $kosul['form_id'] = $arr['form_id'];
                $tablo2 = $this->genel_model->veri_cek_cs("formlar_detay", $select, $kosul);
                $data['formlar_detay'][$arr['form_id']] = $tablo2;
            }
        }
        
        unset($kosul);
        $select = '*';
        $kosul['durum'] = 1; 
        //$tablo3 = $this->genel_model->veri_cek_cs("gostergeler", $select, $kosul);
        $tablo3 = $this->genel_model->veri_cek_cs("gostergelerv2", $select, $kosul);

        $data['gostergeler'] = $tablo3;
        
        if (is_array($tablo3))
        {
            foreach ($tablo3 as $key1 => $arr1)
            {
                $gostergeler2[$arr1['ft_id']][$arr1['g_id']] = $arr1['g_adi'];
            }
        }

        
        $data['gostergeler1'] = $gostergeler2;
        $data['gostergeler_hedef'] = json_encode($gostergeler2['1']);
        $data['gostergeler_yetkinlik'] = json_encode($gostergeler2['2']);
        $data['gostergeler_potansiyel'] = json_encode($gostergeler2['3']);
        $data['gostergeler_gorusme'] = json_encode($gostergeler2['4']);
        $data['gostergeler_yonlendirme'] = json_encode($gostergeler2['5']);

        // var_dump( $data);
        // exit();
        
        
        $select = '*';
        $tablo4 = $this->genel_model->veri_cek_cs("form_tipleri", $select);
        $data['form_tipleri'] = $tablo4;
        
        $this->template->goster('formlar', $data);
    }
    
    public function form_kaydet()
    {
        $form_adi = $this->input->post('form_adi');
        $ft_id = $this->input->post('ft_id');
        $dizi = $this->input->post('dizi');
        $t_agirlik = $this->input->post('t_agirlik');
        
        if ($ft_id == 5)
        {
            if (empty_f($form_adi) || empty_f($ft_id))
            {
                $alert_message = alert_message('Tüm alanları doldurunuz.');
                $this->session->set_flashdata('message', $alert_message);
                redirect('/ikyp/formlar/');
                exit();
            }
        }
        else 
        {
            if (empty_f($form_adi) || empty_f($ft_id) || !is_array($dizi))
            {
                $alert_message = alert_message('Tüm alanları doldurunuz.');
                $this->session->set_flashdata('message', $alert_message);
                redirect('/ikyp/formlar/');
                exit();
            }
        }
        
        if (($ft_id == 1 || $ft_id == 2 || $ft_id == 3) && empty_f($t_agirlik))
        {
            $alert_message = alert_message('Tüm alanları doldurunuz.');
            $this->session->set_flashdata('message', $alert_message);
            redirect('/ikyp/formlar/');
            exit();
        }


        
        $toplam_agirlik = 0;
        foreach ($dizi as $key => $arr)
        {
            if (!empty_f($arr['g_id']))
            {
                if (($ft_id == 1 || $ft_id == 2 || $ft_id == 3) && !empty_f($arr['agirlik']))
                {
                    $toplam_agirlik = $toplam_agirlik + $arr['agirlik'];
                    $data2[$key]['g_id'] = $arr['g_id'];
                    $data2[$key]['agirlik'] = $arr['agirlik'];
                }
                else if ($ft_id == 4 || $ft_id == 5)
                {
                    $toplam_agirlik = $toplam_agirlik + $arr['agirlik'];
                    $data2[$key]['g_id'] = $arr['g_id'];
                    $data2[$key]['agirlik'] = $arr['agirlik'];
                }
            }
        }
        
        $toplam_agirlik = number_format($toplam_agirlik,0,'.','');
        // $check = ($toplam_agirlik  == $t_agirlik) ? "yes" : "no";
        // var_dump($check);
        // exit();


        if (($toplam_agirlik != $t_agirlik) && ($ft_id != 4))
        {
            $alert_message = alert_message('Gösterge ağırlıklarının toplamı toplam ağırlığa eşit olmalıdır.');
            $this->session->set_flashdata('message', $alert_message);
            redirect('/ikyp/formlar/');
            exit();
        }
        
        $data1['ft_id'] = $ft_id;
        
        unset($kosul);
        $select = '*';
        $kosul['id'] = $ft_id;
        $tablo = $this->genel_model->veri_cek_ts("form_tipleri", $select, $kosul);
        $data1['ft_text'] = $tablo['ft_text'];
        
        $data1['form_adi'] = $form_adi;
        $data1['t_agirlik'] = $t_agirlik;
        $data1['guncelleyen'] = $this->session->userdata('pernr');
        $data1['g_time'] = date('Y-m-d H:i:s');
        
        $r = $this->ikyp_model->form_kaydet($data1, $data2);
        
        if (!$r)
        {
            $alert_message = alert_message('Teknik bir aksaklık oluştu daha sonra tekrar deneyiniz.');
            $this->session->set_flashdata('message', $alert_message);
            redirect('/ikyp/formlar/');
            exit();
        }
        
        $alert_message = alert_message('Kayıt başarılı.', 'success');
        $this->session->set_flashdata('message', $alert_message);
        redirect('/ikyp/formlar/');
        exit();
        
    }
    
    public function form_ata()
    {
        $yil = $this->input->post('yil');
        $donem = $this->input->post('donem');
        $form_id_dizi = $this->input->post('form_id');
        $pernr = $this->input->post('kayitlar');
        
        if (empty_f($pernr))
        {
            $alert_message = alert_message('Hiç bir personel seçmediniz.');
            $this->session->set_flashdata('message', $alert_message);
            redirect('/ikyp/organizasyon_semasi/'.$yil.'/'.$donem);
            exit();
        }
        
        $pernr_dizi = explode(',', $pernr);
        
        if (empty_f($yil) || empty_f($donem) || !is_array($form_id_dizi))
        {
            $alert_message = alert_message('Tüm alanları doldurunuz.');
            $this->session->set_flashdata('message', $alert_message);
            redirect('/ikyp/organizasyon_semasi/'.$yil.'/'.$donem);
            exit();
        }
        
        // Değerlendirme dönemi başlamış mı kontrol et
        unset($kosul);
        $select = "*";
        $kosul['yil'] = $yil;
        $kosul['donem'] = $donem;
        $tablo1 = $this->genel_model->veri_cek_ts("donem_listesi", $select, $kosul);
        
//        if ($tablo1['bat'] < date('Y-m-d'))
//        {
//            $alert_message = alert_message('Değerlendirme dönemi başladıktan sonra atamaları değiştiremezsiniz.');
//            $this->session->set_flashdata('message', $alert_message);
//            redirect('/ikyp/organizasyon_semasi/'.$yil.'/'.$donem);
//            exit();
//        }
        
        $g_time = date('Y-m-d H:i:s');
        $sayac = 0;
        foreach ($pernr_dizi as $key=>$val)
        {
            foreach ($form_id_dizi as $key1=>$val1)
            {
                if (!empty_f($val1))
                {
                    $form_id_dizi2[] = $val1;
                    
                    $data[$sayac]['yil'] = $yil;
                    $data[$sayac]['donem'] = $donem;
                    $data[$sayac]['pernr'] = $val;
                    $data[$sayac]['ft_id'] = $key1;
                    $data[$sayac]['form_id'] = $val1;
                    $data[$sayac]['guncelleyen'] = $this->session->userdata('pernr');
                    $data[$sayac]['g_time'] = $g_time;
                    $sayac++;
                }
            }
        }
        
        $r = $this->ikyp_model->form_atamasi_kaydet($data, $yil, $donem, $pernr_dizi, $form_id_dizi2);
        if (!$r)
        {
            $alert_message = alert_message('Teknik bir aksaklık oluştu daha sonra tekrar deneyiniz.');
            $this->session->set_flashdata('message', $alert_message);
            redirect('/ikyp/organizasyon_semasi/'.$yil.'/'.$donem);
            exit();
        }
        
        $alert_message = alert_message('Kayıt başarılı.', 'success');
        $this->session->set_flashdata('message', $alert_message);
        redirect('/ikyp/organizasyon_semasi/'.$yil.'/'.$donem);
        exit();
    }
    
    public function kademelendirme_havuzlari()
    {
        $select = '*';
        $tablo = $this->genel_model->veri_cek_cs("kademelendirme_havuzlari", $select);
        
        $data['havuzlar'] = $tablo;
        
        $this->template->goster('kademelendirme_havuzlari', $data);
    }
    
    public function havuz_kaydet()
    {
        $havuz_text = $this->input->post('havuz_text');
        
        $guncellenecek_id = $this->input->post('guncellenecek_id');
        
        // Değerler boşsa hata ver..
        if (empty_f($havuz_text))
        {
            $alert_message = alert_message('Tüm alanları doldurunuz.');
            $this->session->set_flashdata('message', $alert_message);
            redirect('/ikyp/kademelendirme_havuzlari/');
            exit();
        }
        
        
        $data['havuz_text'] = $havuz_text;
        $data['guncelleyen'] = $this->session->userdata('pernr');
        $data['g_time'] = date('Y-m-d H:i:s');
        
        // Güncellenecek id doluysa güncellemedir veriyi güncelle, değilse kaydet..
        if (!empty_f($guncellenecek_id))
        {
            unset($kosul);
            $kosul['havuz_id'] = $guncellenecek_id;
            $r = $this->genel_model->veri_guncelle("kademelendirme_havuzlari", $data, $kosul);
            
        }
        else
        {
            $r = $this->genel_model->veri_kaydet("kademelendirme_havuzlari", $data);
        }
        
        
        if (!$r)
        {
            $alert_message = alert_message('Teknik bir aksaklık oluştu daha sonra tekrar deneyiniz.');
            $this->session->set_flashdata('message', $alert_message);
            redirect('/ikyp/kademelendirme_havuzlari/');
            exit();
        }
        
        $alert_message = alert_message('Kayıt başarılı.', 'success');
        $this->session->set_flashdata('message', $alert_message);
        redirect('/ikyp/kademelendirme_havuzlari/');
        exit();
    }
    
    public function havuz_ata()
    {
        $yil = $this->input->post('yil');
        $donem = $this->input->post('donem');
        $havuz_id = $this->input->post('havuz_id');
        $pernr = $this->input->post('kayitlar');
        $k_pernr = $this->input->post('k_pernr');
        
        if (empty_f($pernr))
        {
            $alert_message = alert_message('Hiç bir personel seçmediniz.');
            $this->session->set_flashdata('message', $alert_message);
            redirect('/ikyp/organizasyon_semasi/'.$yil.'/'.$donem);
            exit();
        }
        
        $pernr_dizi = explode(',', $pernr);
        
        if (empty_f($yil) || empty_f($donem) || empty_f($havuz_id) || empty_f($k_pernr))
        {
            $alert_message = alert_message('Tüm alanları doldurunuz.');
            $this->session->set_flashdata('message', $alert_message);
            redirect('/ikyp/organizasyon_semasi/'.$yil.'/'.$donem);
            exit();
        }
        
        // Değerlendirme dönemi başlamış mı kontrol et
        unset($kosul);
        $select = "*";
        $kosul['yil'] = $yil;
        $kosul['donem'] = $donem;
        $tablo1 = $this->genel_model->veri_cek_ts("donem_listesi", $select, $kosul);
        
//        if ($tablo1['bat'] < date('Y-m-d'))
//        {
//            $alert_message = alert_message('Değerlendirme dönemi başladıktan sonra atamaları değiştiremezsiniz.');
//            $this->session->set_flashdata('message', $alert_message);
//            redirect('/ikyp/organizasyon_semasi/'.$yil.'/'.$donem);
//            exit();
//        }
        
        $g_time = date('Y-m-d H:i:s');
        $sayac = 0;
        foreach ($pernr_dizi as $key=>$val)
        {
            $data[$sayac]['yil'] = $yil;
            $data[$sayac]['donem'] = $donem;
            $data[$sayac]['pernr'] = $val;
            $data[$sayac]['havuz_id'] = $havuz_id;
            $data[$sayac]['k_pernr'] = $k_pernr;
            $data[$sayac]['guncelleyen'] = $this->session->userdata('pernr');
            $data[$sayac]['g_time'] = $g_time;
            $sayac++;
        }

        // var_dump($data, $yil, $donem, $pernr_dizi, $havuz_id);
        
        // exit();
        $r = $this->ikyp_model->havuz_atamasi_kaydet($data, $yil, $donem, $pernr_dizi, $havuz_id);
        
       
        if (!$r)
        {
            $alert_message = alert_message('Teknik bir aksaklık oluştu daha sonra tekrar deneyiniz.');
            $this->session->set_flashdata('message', $alert_message);
            redirect('/ikyp/organizasyon_semasi/'.$yil.'/'.$donem);
            exit();
        }
        
        $alert_message = alert_message('Kayıt başarılı.', 'success');
        $this->session->set_flashdata('message', $alert_message);
        redirect('/ikyp/organizasyon_semasi/'.$yil.'/'.$donem);
        exit();
    }


    public function kademe_ekle(){
        
        $this->load->model("d_donemleri_model");
        $donem_listesi = $this->d_donemleri_model->getDonemList();
        $data["donem_listesi"] = $donem_listesi;
        $this->template->goster('yeni_kademe', $data); 
    }
    
    public function kademe_guncelle(){
        
        $this->load->model("d_donemleri_model");
        $donem_listesi = $this->d_donemleri_model->getDonemList();
        $data["donem_listesi"] = $donem_listesi;
        $this->template->goster('kademe_guncelle', $data); 
    }




    public function getFTCol($row){
        $colA = explode ("(", $row); 
        $ft_id = ""; 
        $ft_text = "";
        if(is_array($colA)){
            $ft_id  =  $colA[0];
            $ft_text  =  trim(str_replace(")"," ",$colA[1]));
        }
        return [$ft_id, $ft_text];
    }
    

    public function findInList($str,$list){
        $key = array_search($str, array_column($list, 'text'));
        return ($list[$key]) ? $list[$key]['id'] : "";
    }


    public function getPeriodNamesWithIdFromExcel($text){
        $periods = [
            "Yıllık"=>"1",
            "6 Aylık"=>"2",
            "4 Aylık"=>"3",
            "3 Aylık"=>"4",
            "2 Aylık"=>"6",
            "1 Aylık"=>"12"
        ];

        return $periods[$text];
        
    }


    public function get_kademe_isimleri($id){
        $kademeler = [
            5 => "Beklenen Düzeyin Çok Üstü",
            4 =>"Beklenen Düzeyin Üstü",
            3 =>"Beklenen Düzey" ,
            2 => "Beklenen Düzeyin Altı",
            1 => "Beklenen Düzeyin Çok Altı"
        ];
            return  $kademeler[$id];
    }


    public function saveGostergelerByExcel(){
        $this->load->library('excel');
        if(isset($_FILES['gosterge_excel'])){
            if($_FILES['gosterge_excel']['tmp_name']){
                if(!$_FILES['gosterge_excel']['error']){
                    $inputFile = $_FILES['gosterge_excel']['tmp_name'];
                    $extension = strtoupper(pathinfo($inputFile, PATHINFO_EXTENSION));
                    try {
                        $inputFileType = PHPExcel_IOFactory::identify($inputFile);
                        $objReader = PHPExcel_IOFactory::createReader($inputFileType);
                        $objPHPExcel = $objReader->load($inputFile);
                    } catch(Exception $e) {
                        die($e->getMessage());
                    }

                    $veri_kaynagi_list = $this->ikyp_model->getVeriKaynagiAv(); // veri_kaynagi_av sorgu atıyor
                    $veri_hesaplama_list = $this->ikyp_model->getVeriHesapamaAv(); // veri_hesaplama_av sorgu atıyor
                    $degerlendirme_turu_list = $this->ikyp_model->getDegerlendirmeTuruAv(); // degerlendirme_turu_av sorgu atıyor
                        
                    $sheetnum = $objPHPExcel->getSheetCount();
                    for($i = 0; $i < $sheetnum ;$i++) {
                        $sheet =  $objPHPExcel->setActiveSheetIndex($i);

                        if(count($sheet->getMergeCells()) > 0){ exit();}
                        
                        $highestRow = $sheet->getHighestRow(); 
                        $highestColumn = $sheet->getHighestColumn();
                        $sheet_name =  $sheet->getTitle();
                        //echo  $sheet_name." => highestRow => ".$highestRow."<br>";
                        
                        if($sheet_name == "Hedef"){
                            $start_to_range_array = 'L';
                            $columns_must_remove = false;
                            $category_check = true;
                        }
                        else if($sheet_name = "Potansiyel" || $sheet_name == "Yetkinlik"){
                            $start_to_range_array = 'G';
                            $columns_must_remove = true;
                            $category_check = false;
                        }

                        $headings = $sheet->rangeToArray($start_to_range_array."1:" . $highestColumn . 1, NULL, TRUE, FALSE);
                        for ($row = 2; $row <= $highestRow; $row++){ 
                            $rowData = $sheet->rangeToArray($start_to_range_array . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
                            //$combined_gostergeler = array_combine(array_filter($headings[0],'strlen'), array_filter($rowData[0],'strlen'));
                            $combined_gostergeler = array_combine($headings[0],$rowData[0]);

                            //var_dump($headings[0],$rowData[0]);
                            
                            $gostergeler = [];
                            $aciklamalar = [];
                            
                            $ft = $this->getFTCol($sheet->getCell("A".$row)->getValue());
                            foreach($combined_gostergeler as $key => $gosterge){
                                if(!is_null($gosterge)){
                                    $kademe_ismi = explode ("-", $key);
                                    $k = $kademe_ismi[0];

                                    if($ft[0] == 1){
                                        $key = trim(str_replace( "(%)", "", $kademe_ismi[1]));
                                    }
                                    // else if($ft[0] == 2){
                                    //     $key = trim(str_replace( "*", "", $kademe_ismi[1]));
                                    // }

                                    if(strpos($key, 'Açıklama')){
                                        $aciklamalar[$k] = $gosterge;
                                    }
                                    else{
                                        $kademe_ismi = $this->get_kademe_isimleri($k[1]);
                                        $gostergeler[$kademe_ismi][$key] = $gosterge;
                                    }

                                }
                            }

                            $excelData["main"] = [
                                "ft_id" => $ft[0],
                                "ft_text"=> $ft[1], //texte göre dinamik id gelicek
                                "g_adi"=>$sheet->getCell("B".$row)->getValue(),
                                "periyod"=>$this->getPeriodNamesWithIdFromExcel($sheet->getCell("C".$row)->getValue()), // SORULACAK
                                "g_text"=>$sheet->getCell("D".$row)->getValue(),
                                "g_desc"=>$sheet->getCell("E".$row)->getValue(),
                                "planlanan_text"=>$sheet->getCell("F".$row)->getValue(),
                                "kategori"=>($category_check) ? $sheet->getCell("F".$row)->getValue() : '',
                                "durum"=>1,
                                "guncelleyen"=>$this->session->userdata('pernr')
                            ];

                            
                            if(!$columns_must_remove){
                                $excelData["main"]["gerceklesen_text"] = $sheet->getCell("G".$row)->getValue();
                                $excelData["main"]["sonuc_text"] = $sheet->getCell("H".$row)->getValue();
                                $excelData["main"]["veri_kaynagi"] = $this->findInList($sheet->getCell("I".$row)->getValue(),$veri_kaynagi_list); // texte göre dinamik id gelicek
                                $excelData["main"]["veri_kaynagi_text"] =$sheet->getCell("I".$row)->getValue();
                                $excelData["main"]["veri_hesaplama"] =$this->findInList($sheet->getCell("J".$row)->getValue(),$veri_hesaplama_list); // texte göre dinamik id gelicek
                                $excelData["main"]["veri_hesaplama_text"] =$sheet->getCell("J".$row)->getValue();
                                $excelData["main"]["degerlendirme_turu"] = $this->findInList($sheet->getCell("K".$row)->getValue(),$degerlendirme_turu_list); // texte göre dinamik id gelicek
                                $excelData["main"]["degerlendirme_turu_text"] = $sheet->getCell("K".$row)->getValue();
                            }
                            
                            $excelData["info"]["gostergeler"] = $gostergeler;

                            
                            $aciklamalar = array_values($aciklamalar);
                            $excelData["main"]["kmin_text"] = $aciklamalar[0];
                            $excelData["main"]["kmax_text"] = $aciklamalar[1];
                            
                            $result = $this->ikyp_model->exportFormlarExcelToDb($excelData);

                            if(!$result){
                                $alert_message = alert_message('Excel yüklenirken bir hata ile karşılaşıldı.');
                                $this->session->set_flashdata('message', $alert_message);
                                redirect('/ikyp/gostergelerv2/');
                                exit();
                            }

                            //echo "<pre>";
                            //print_r($excelData);
                            //exit();
                            
                        }
                    }

                    if($result){
                        $alert_message = alert_message('Excel Başarıyla yüklendi.','success');
                        $this->session->set_flashdata('message', $alert_message);
                        redirect('/ikyp/gostergelerv2/');
                        exit();
                    }

                }
            }
        }
}












}
