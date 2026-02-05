<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Veri_yukle extends MY_Controller {
    
    public function __construct() {
        parent::__construct();
        
        $this->load->model("d_donemleri_model");
    }
    
    public function index()
    {
        unset($kosul);
        $select = '*';
        $order_by['yil'] = 'desc';
        $order_by['donem'] = 'desc';
        $tablo1 = $this->genel_model->veri_cek_cs("donem_listesi", $select, '', $order_by);
        $data['donem_listesi'] = $tablo1;
        
        unset($kosul);
        unset($order_by);
        $select = '*';
        $kosul['ft_id'] = 1;
        $kosul['durum'] = 1;
        $order_by['g_id'] = 'desc';
        //$gostergeler = $this->genel_model->veri_cek_cs("gostergeler", $select, $kosul, $order_by);
        $gostergeler = $this->genel_model->veri_cek_cs("gostergelerv2", $select, $kosul, $order_by);
        $data['gostergeler'] = $gostergeler;
        
        unset($kosul);
        unset($order_by);
        $select = '*';
        $kosul['ft_id'] = 1;
        $kosul['durum'] = 1;
        $order_by['form_id'] = 'desc';
        $formlar = $this->genel_model->veri_cek_cs("formlar", $select, $kosul, $order_by);
        $data['formlar'] = $formlar;
        
        $this->template->goster("veri_yukle", $data);
    }
    
    public function kaydet()
    {
        $donem_id = $this->input->post('donem_id');
        $g_id = $this->input->post('g_id');
        $urun = $this->input->post('urun');
        $form_id = $this->input->post('form_id');
        
        
        if (empty_f($donem_id) || empty_f($g_id) || empty_f($urun) || empty_f($form_id) || !file_exists($_FILES['dosya']['tmp_name']))
        {
            $alert_message = alert_message('Hata.');
            $this->session->set_flashdata('message', $alert_message);
            redirect('/veri_yukle/');
            exit();
        }
        
        // Döenm bilgisi
        unset($kosul);
        $select = '*';
        $kosul['id'] = $donem_id;
        $tablo1 = $this->genel_model->veri_cek_ts("donem_listesi", $select, $kosul);
        
        if (!is_array($tablo1))
        {
            $alert_message = alert_message('Hata.');
            $this->session->set_flashdata('message', $alert_message);
            redirect('/veri_yukle/');
            exit();
        }
        
        $yil = $tablo1['yil'];
        $donem = $tablo1['donem'];
        $time = date("Y-m-d H:i:s");
        
        // Gösterge bilgisi
        unset($kosul);
        $select = '*';
        $kosul['g_id'] = $g_id;
        //$tablo2 = $this->genel_model->veri_cek_ts("gostergeler", $select, $kosul);
        $tablo2 = $this->genel_model->veri_cek_ts("gostergelerv2", $select, $kosul);


        
        if (!is_array($tablo2))
        {
            $alert_message = alert_message('Hata.');
            $this->session->set_flashdata('message', $alert_message);
            redirect('/veri_yukle/');
            exit();
        }
        
        // Form bilgisi
        unset($kosul);
        $select = '*';
        $kosul['form_id'] = $form_id;
        $kosul['g_id'] = $g_id;
        $tablo3 = $this->genel_model->veri_cek_ts("formlar_detay", $select, $kosul);
        
        if (!is_array($tablo3))
        {
            $alert_message = alert_message('Hata.');
            $this->session->set_flashdata('message', $alert_message);
            redirect('/veri_yukle/');
            exit();
        }
        
        
        if (!is_dir('./documents/veri/'.$yil.'_'.$donem.'_'.$form_id.'_'.$g_id))
        {
            mkdir('./documents/veri/'.$yil.'_'.$donem.'_'.$form_id.'_'.$g_id, 0777, TRUE);

        }
        $config['upload_path']   = './documents/veri/'.$yil.'_'.$donem.'_'.$form_id.'_'.$g_id; 
        $config['allowed_types'] = '*'; 
        $config['max_size']      = 0; //KB cinsinden izin verilen boyut 

        $this->load->library('upload', $config);

        if ($this->upload->do_upload('dosya')) 
        {
            $up_data = $this->upload->data();
        }
        else
        {
            $alert_message = alert_message('Hata.');
            $this->session->set_flashdata('message', $alert_message);
            redirect('/veri_yukle/');
            exit();
        }
        
        $filename = $up_data['file_name'];
        
        $filename = "./documents/veri/".$yil.'_'.$donem.'_'.$form_id.'_'.$g_id.'/'.$filename;
        $file = fopen($filename,"r");
        
        $row = 0;
        $sayac = 0;
        unset($per_list);
        if (($handle = fopen($filename, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 100000, ";")) !== FALSE) {
                $row++;
                if ($row == 1)
                {
                    continue;
                }
                
                $pernr = trim($data[0]);
                
                // Personel var mı ?
                unset($kosul);
                $select = '*';
                $kosul['yil'] = $yil;
                $kosul['donem'] = $donem;
                $kosul['pernr'] = $pernr;
                $yon1 = $this->genel_model->veri_cek_ts("organizasyon_d", $select, $kosul);

                if (!is_array($yon1))
                {
                    $mes = $mes." ".$row.". satır; Personel bulunamadı. Pernr: ".$data[0]."<br/>";
                    continue;
                }
                
                if ($yon1['ust_pernr'] == 0 || $yon1['ust_pernr'] == "")
                {
                    $mes = $mes." ".$row.". satır; Personelin yöneticisi bulunamadı. Pernr: ".$data[0]."<br/>";
                    continue;
                }
                
                // Personele bu form atanmış mı ?
                unset($kosul);
                $select = "*";
                $kosul['yil'] = $yil;
                $kosul['donem'] = $donem;
                $kosul['form_id'] = $form_id;
                $kosul['pernr'] = $pernr;
                $r = $this->genel_model->veri_cek_ts("form_atamalari", $select, $kosul);
                
                if (!is_array($r))
                {
                    $mes = $mes." ".$row.". satır; Form ataması yok. Pernr: ".$data[0]."<br/>";
                    continue;
                }
                
                $periyod = trim($data[2]);
                
                if ($periyod != 1 && $periyod != 2 && $periyod != 3 && $periyod != 4 && $periyod != 5 && $periyod != 6
                    && $periyod != 7 && $periyod != 8 && $periyod != 9 && $periyod != 10 && $periyod != 11 && $periyod != 12)
                {
                    $mes = $mes." ".$row.". satır; periyod hatalı. Periyod: ".$data[3]."<br/>";
                    continue;
                }
                
                if ($tablo2['periyod'] < $periyod)
                {
                    $mes = $mes." ".$row.". satır; periyod hatalı. Periyod: ".$data[3].". Max periyod sayısı: ".$tablo2['periyod']."<br/>";
                    continue;
                }
                
                if ($urun == 1) // Ürün ataması varsa
                {
                    $a_urun = trim($data[3]);
                    if ($a_urun == "" || is_numeric($a_urun))
                    {
                        $mes = $mes." ".$row.". satır; ürün hatalı. Ürün: ".$data[4]."<br/>";
                        continue;
                    }

                    $planlanan = trim($data[4]);
                    $gerceklesen = trim($data[5]);
                    $sonuc = trim($data[6]);
                    
                    if (empty_f($planlanan) || empty_f($gerceklesen) || empty_f($sonuc))
                    {
                        $mes = $mes." ".$row.". satır; planlanan, gerçekleşen veya sonuç hatalı<br/>";
                        continue;
                    }
                    
                    $planlanan = str_replace(",",".",$planlanan);
                    $planlanan = number_format($planlanan,2,'.','');
                    
                    $gerceklesen = str_replace(",",".",$gerceklesen);
                    $gerceklesen = number_format($gerceklesen,2,'.','');
                    
                    $sonuc = str_replace(",",".",$sonuc);
                    $sonuc = number_format($sonuc,2,'.','');
                }
                else
                {
                    $planlanan = trim($data[3]);
                    $gerceklesen = trim($data[4]);
                    $sonuc = trim($data[5]);
                    
                    if (empty_f($planlanan) || empty_f($gerceklesen) || empty_f($sonuc))
                    {
                        $mes = $mes." ".$row.". satır; planlanan, gerçekleşen veya sonuç hatalı<br/>";
                        continue;
                    }
                    
                    
                    $planlanan = str_replace(",",".",$planlanan);
                    $planlanan = number_format($planlanan,2,'.','');
                    
                    $gerceklesen = str_replace(",",".",$gerceklesen);
                    $gerceklesen = number_format($gerceklesen,2,'.','');
                    
                    $sonuc = str_replace(",",".",$sonuc);
                    $sonuc = number_format($sonuc,2,'.','');
                }
                
                $sayac++;
                
               
                $kademeler = $this->d_donemleri_model->getGosgelerByGostergeIdForExcel($g_id);
                
                //var_dump($kademeler);
                if (!empty_f($sonuc))
                {
                    
                    foreach($kademeler as $kademe_key => $kademe){
                        if ($sonuc >= $kademe['kademe_as'] && $sonuc <= $kademe['kademe_us']){
                            //echo ($kademe_key + 1);
                            $veri_yukle[$sayac]['duzey'] = ($kademe_key + 1);
                        }
                    }

                    // if ($sonuc >= $tablo2['k1_as'] && $sonuc <= $tablo2['k1_us'])
                    // {
                    //     $veri_yukle[$sayac]['duzey'] = 1;
                    // }
                    // else if ($sonuc >= $tablo2['k2_as'] && $sonuc <= $tablo2['k2_us'])
                    // {
                    //     $veri_yukle[$sayac]['duzey'] = 2;
                    // }
                    // else if ($sonuc >= $tablo2['k3_as'] && $sonuc <= $tablo2['k3_us'])
                    // {
                    //     $veri_yukle[$sayac]['duzey'] = 3;
                    // }
                    // else if ($sonuc >= $tablo2['k4_as'] && $sonuc <= $tablo2['k4_us'])
                    // {
                    //     $veri_yukle[$sayac]['duzey'] = 4;
                    // }
                }


                // var_dump($veri_yukle);
                // exit();
                
                $periyod_hesap = 12/ $tablo2['periyod']*$periyod;
                
                if ($periyod_hesap > 6) // Yarı yıl
                {
                    $donem_y = 3;
                }
                if ($periyod_hesap <= 6) // Yılsonu
                {
                    $donem_y = 2;
                }
                
                $veri_yukle[$sayac]['yil'] = $yil;
                $veri_yukle[$sayac]['donem'] = $donem_y;
                $veri_yukle[$sayac]['pernr'] = $pernr;
                $veri_yukle[$sayac]['degerlendiren_ack'] = "yon1";
                $veri_yukle[$sayac]['degerlendiren_pernr'] = $yon1['ust_pernr'];
                $veri_yukle[$sayac]['form_id'] = $form_id;
                $veri_yukle[$sayac]['ft_id'] = 1;
                $veri_yukle[$sayac]['g_id'] = $g_id;
                $veri_yukle[$sayac]['periyod'] = $periyod;
                $veri_yukle[$sayac]['urun'] = $a_urun;
                $veri_yukle[$sayac]['planlanan'] = $planlanan;
                $veri_yukle[$sayac]['gerceklesen'] = $gerceklesen;
                $veri_yukle[$sayac]['sonuc'] = $sonuc;
                $veri_yukle[$sayac]['guncelleyen'] = $this->session->userdata('pernr');
                $veri_yukle[$sayac]['g_time'] = $time;
                
                if (!in_array($pernr, $per_list))
                {
                    $per_list[] = $pernr;
                }
            }
            fclose($handle);
        }
        
        unset($kosul);
        unset($where_in);
        $kosul['yil'] = $yil;
        $kosul['donem'] = $donem;
        $kosul['form_id'] = $form_id;
        $kosul['g_id'] = $g_id;
        $where_in['pernr'] = $per_list;
        $r = $this->genel_model->veri_kaydet_cs("degerlendirmeler", $veri_yukle, $kosul, $where_in);
        
        // Kayıt başarılı ise puan hesapla..
        if ($r)
        {
            unset($kosul);
            unset($order_by);
            $select = "*";
            $kosul['yil'] = $yil;
            $kosul['donem'] = $donem;
            $kosul['form_id'] = $form_id;
            $kosul['g_id'] = $g_id;
            $where_in['pernr'] = $per_list;
            $order_by['donem'] = 'ASC';
            $t = $this->genel_model->veri_cek_cs("degerlendirmeler", $select, $kosul, $order_by, '', '', $where_in);

            if ($t)
            {
                unset($veri_yukle);

                $count_kademeler = count($kademeler); // robin ekledi
                foreach ($t as $sayac=>$arr)
                {
                    if ($urun == 1) // Ürün ataması varsa
                    {
                        unset($kosul);
                        $select = "pernr, periyod, count(*) as a";
                        $kosul['yil'] = $yil;
                        $kosul['donem'] = $arr['donem'];
                        $kosul['form_id'] = $form_id;
                        $kosul['g_id'] = $g_id;
                        $kosul['pernr'] = $arr['pernr'];
                        $kosul['periyod'] = $arr['periyod'];
                            //                        $kosul['duzey>'] = 0;
                        $group_by = 'urun';
                        $s = $this->genel_model->veri_cek_cs("degerlendirmeler", $select, $kosul, '', '', $group_by);
                        
                        $urun_sayisi[$arr['pernr']][$arr['periyod']] = 0;
                        if ($s)
                        {
                            $sayac_s = 0;
                            foreach ($s as $key_s=>$arr_s)
                            {
                                $sayac_s++;
                            }
                            $urun_sayisi[$arr['pernr']][$arr['periyod']] =  $sayac_s;
                        }
                    }


                    unset($kosul);
                    unset($group_by);
                    $select = "*";
                    $kosul['yil'] = $yil;
                    $kosul['donem'] = $arr['donem'];
                    $kosul['form_id'] = $form_id;
                    $kosul['g_id'] = $g_id;
                    $kosul['duzey>'] = 0;
                    $kosul['pernr'] = $arr['pernr'];
                    $group_by = "periyod";
                    $ss = $this->genel_model->veri_cek_cs("degerlendirmeler", $select, $kosul, '', '', $group_by);

                    $periyod_sayisi[$arr['pernr']] = 0;
                    if ($ss)
                    {
                        $sayac_ss = 0;
                        foreach ($ss as $key_ss=>$arr_ss)
                        {
                            $sayac_ss++;
                        }
                        $periyod_sayisi[$arr['pernr']] =  $sayac_ss;
                    }
                    
                    $veri_yukle[$sayac]['duzey'] = $arr['duzey'];
                    $veri_yukle[$sayac]['yil'] = $arr['yil'];
                    $veri_yukle[$sayac]['donem'] = $arr['donem'];
                    $veri_yukle[$sayac]['pernr'] = $arr['pernr'];
                    $veri_yukle[$sayac]['degerlendiren_ack'] = $arr['degerlendiren_ack'];
                    $veri_yukle[$sayac]['degerlendiren_pernr'] = $arr['degerlendiren_pernr'];
                    $veri_yukle[$sayac]['form_id'] = $arr['form_id'];
                    $veri_yukle[$sayac]['ft_id'] = $arr['ft_id'];
                    $veri_yukle[$sayac]['g_id'] = $arr['g_id'];
                    $veri_yukle[$sayac]['periyod'] = $arr['periyod'];
                    $veri_yukle[$sayac]['urun'] = $arr['urun'];
                    $veri_yukle[$sayac]['planlanan'] = $arr['planlanan'];
                    $veri_yukle[$sayac]['gerceklesen'] = $arr['gerceklesen'];
                    $veri_yukle[$sayac]['sonuc'] = $arr['sonuc'];
                    $veri_yukle[$sayac]['guncelleyen'] = $this->session->userdata('pernr');
                    $veri_yukle[$sayac]['g_time'] = $time;
                    
                    if ($arr['donem'] == 2)
                    {
                        //$veri_yukle[$sayac]['puan'] = $tablo3['agirlik']/$periyod_sayisi[$arr['pernr']]/4*$arr['duzey']/2;
                        $veri_yukle[$sayac]['puan'] = $tablo3['agirlik']/$periyod_sayisi[$arr['pernr']]/$count_kademeler*$arr['duzey']/2; // robin ekledi

                        
                    }
                    else 
                    {
                        //                        if ($periyod_sayisi[$arr['pernr']] == 1)
                        //                        {
                        //                            $veri_yukle[$sayac]['puan'] = $tablo3['agirlik']/$periyod_sayisi[$arr['pernr']]/4*$arr['duzey'];
                        //                        }
                        //                        else
                        //                        {
                        //                            $veri_yukle[$sayac]['puan'] = $tablo3['agirlik']/$periyod_sayisi[$arr['pernr']]/4*$arr['duzey']/2;
                        //                        }
                        
                        unset($kosul);
                        unset($group_by);
                        $select = "*";
                        $kosul['yil'] = $yil;
                        $kosul['donem'] = 2;
                        $kosul['form_id'] = $form_id;
                        $kosul['g_id'] = $g_id;
                        $kosul['duzey>'] = 0;
                        $kosul['pernr'] = $arr['pernr'];
                        $group_by = "periyod";
                        $ss2 = $this->genel_model->veri_cek_cs("degerlendirmeler", $select, $kosul, '', '', $group_by);

                        $sayac_ss2 = 0;
                        if ($ss2)
                        {
                            foreach ($ss2 as $key_ss=>$arr_ss)
                            {
                                $sayac_ss2++;
                            }
                        }
                        
                        if ($sayac_ss2 > 0)
                        {
                            //$veri_yukle[$sayac]['puan'] = $tablo3['agirlik']/$periyod_sayisi[$arr['pernr']]/4*$arr['duzey']/2;
                            $veri_yukle[$sayac]['puan'] = $tablo3['agirlik']/$periyod_sayisi[$arr['pernr']]/$count_kademeler*$arr['duzey']/2;

                            
                        }
                        else
                        {
                           // $veri_yukle[$sayac]['puan'] = $tablo3['agirlik']/$periyod_sayisi[$arr['pernr']]/4*$arr['duzey'];
                            $veri_yukle[$sayac]['puan'] = $tablo3['agirlik']/$periyod_sayisi[$arr['pernr']]/$count_kademeler*$arr['duzey'];
                        }
                        
                    }
                    
                    if ($urun == 1)
                    {
                        $veri_yukle[$sayac]['puan'] = $veri_yukle[$sayac]['puan']/$urun_sayisi[$arr['pernr']][$arr['periyod']];
                    }
                }
                
                
                unset($kosul);
                unset($where_in);
                $kosul['yil'] = $yil;
                $kosul['donem'] = $donem;
                $kosul['form_id'] = $form_id;
                $kosul['g_id'] = $g_id;
                $where_in['pernr'] = $per_list;
                $son = $this->genel_model->veri_kaydet_cs("degerlendirmeler", $veri_yukle, $kosul, $where_in);

                if ($son)
                {
                    $alert = 1;
                }
            }
            
        }
        
        if ($alert == 1)
        {
            $alert_message = alert_message('Veri yüklendi.', "success");
            $this->session->set_flashdata('message', $alert_message.$mes);
            redirect('/veri_yukle/');
            exit();
        }
        else
        {
            $alert_message = alert_message('Hata Oluştu.', "danger");
            $this->session->set_flashdata('message', $alert_message.$mes);
            redirect('/veri_yukle/');
            exit();
        }
    }



    

    

}
