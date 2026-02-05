<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Download_excel extends MY_Controller {
    
    public function __construct() {
        parent::__construct();
        
        // Yetki kontrolü sadee ik anahtar kullanıcıları erişebilir..
        if ($this->session->userdata('pernr') != 101993 && $this->session->userdata('pernr') != 101081 && $this->session->userdata('pernr') != 100003)
        {
            $alert_message = alert_message('Hata.');
            $this->session->set_flashdata('message', $alert_message);
            redirect('/home/');
            exit();
        }
    }
    
    public function genel_rapor_g()
    {
        $donem_id = $this->input->post("donem_id");
        if (empty_f($donem_id))
        {
            $alert_message = alert_message('Dönem seçiniz.');
            $this->session->set_flashdata('message', $alert_message);
            redirect('/raporlar/genel_rapor/');
            exit();
        }
        
        unset($kosul);
        $select = '*';
        $kosul['id'] = $donem_id;
        $donem_listesi = $this->genel_model->veri_cek_ts("donem_listesi", $select, $kosul);
        
        if (!is_array($donem_listesi))
        {
            $alert_message = alert_message('Hata.');
            $this->session->set_flashdata('message', $alert_message);
            redirect('/raporlar/genel_rapor/');
            exit();
        }
        
        $yil = $donem_listesi['yil'];
        $donem = $donem_listesi['donem'];
        
        $this->load->library('excel');
        //activate worksheet number 1
        $this->excel->setActiveSheetIndex(0);
        
        $this->excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
        //$this->excel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
        
        $this->excel->getActiveSheet()->getPageSetup()->setFitToWidth(1);
        $this->excel->getActiveSheet()->getPageSetup()->setFitToHeight(0);
//        $this->excel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
//        $this->excel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
//        $this->excel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
//        $this->excel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
//        $this->excel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
//        $this->excel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
//        $this->excel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
//        $this->excel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
//        $this->excel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
//        $this->excel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
//        $this->excel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
//        $this->excel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
//        $this->excel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
//        $this->excel->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
//        $this->excel->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
//        $this->excel->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);
//        $this->excel->getActiveSheet()->getColumnDimension('Q')->setAutoSize(true);
//        $this->excel->getActiveSheet()->getColumnDimension('R')->setAutoSize(true);
//        $this->excel->getActiveSheet()->getColumnDimension('S')->setAutoSize(true);
//        $this->excel->getActiveSheet()->getColumnDimension('T')->setAutoSize(true);
//        $this->excel->getActiveSheet()->getColumnDimension('U')->setAutoSize(true);
//        $this->excel->getActiveSheet()->getColumnDimension('V')->setAutoSize(true);
//        $this->excel->getActiveSheet()->getColumnDimension('W')->setAutoSize(true);
        
        $this->excel->getActiveSheet()->setTitle('Genel Rapor');
        
        $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('A1', 'Yıl');
        $this->excel->getActiveSheet()->getStyle('B1')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('B1', 'Dönem');
        $this->excel->getActiveSheet()->getStyle('C1')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('C1', 'Değerlendirilen Pernr');
        $this->excel->getActiveSheet()->getStyle('D1')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('D1', 'Değerlendirilen Ad Soyad');
        $this->excel->getActiveSheet()->getStyle('E1')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('E1', 'Değerlendirilen Pozisyon');
        $this->excel->getActiveSheet()->getStyle('F1')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('F1', 'Değerlendiren Pernr');
        $this->excel->getActiveSheet()->getStyle('G1')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('G1', 'Değerlendiren Ad Soyad');
        $this->excel->getActiveSheet()->getStyle('H1')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('H1', 'Değerlendiren Pozisyon');
        $this->excel->getActiveSheet()->getStyle('I1')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('I1', 'Yetkinlik Formu');
        $this->excel->getActiveSheet()->getStyle('J1')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('J1', 'Hedef Formu');
        $this->excel->getActiveSheet()->getStyle('K1')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('K1', 'Potansiyel Formu');
        $this->excel->getActiveSheet()->getStyle('L1')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('L1', 'Görüşme Formu');
        $this->excel->getActiveSheet()->getStyle('M1')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('M1', 'Yönlendirme Formu');
        $this->excel->getActiveSheet()->getStyle('N1')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('N1', 'Kademelendirme Havuzu');
        $this->excel->getActiveSheet()->getStyle('O1')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('O1', 'Kademelendiren Pernr');
        $this->excel->getActiveSheet()->getStyle('P1')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('P1', 'Kademelendiren Ad Soyad');
        $this->excel->getActiveSheet()->getStyle('Q1')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('Q1', 'Kademelendiren Pozisyon');
        $this->excel->getActiveSheet()->getStyle('R1')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('R1', 'Astın Yetkinlik Puanı');
        $this->excel->getActiveSheet()->getStyle('S1')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('S1', 'Yetkinlik Puanı');
        $this->excel->getActiveSheet()->getStyle('T1')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('T1', 'Yetkinlik Onay Durumu');
        $this->excel->getActiveSheet()->getStyle('U1')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('U1', 'Hedef Puanı');
        $this->excel->getActiveSheet()->getStyle('V1')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('V1', 'Hedef Onay Durumu');
        $this->excel->getActiveSheet()->getStyle('W1')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('W1', 'Potansiyel Puanı');
        $this->excel->getActiveSheet()->getStyle('X1')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('X1', 'Potansiyel Durumu');
        $this->excel->getActiveSheet()->getStyle('Y1')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('Y1', 'Potansiyel Onay Durumu');
        $this->excel->getActiveSheet()->getStyle('Z1')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('Z1', 'Üstün Nitelikler / Hedefler İle İlgili Değerlendiren Yöneticinin Görüşleri');
        $this->excel->getActiveSheet()->getStyle('AA1')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('AA1', 'Gelişmesi Gereken Yönler / Yetkinlikler İle İlgili Değerlendiren Yöneticinin Görüşleri');
        $this->excel->getActiveSheet()->getStyle('AB1')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('AB1', 'Çalışan Gelişme Planı');
        $this->excel->getActiveSheet()->getStyle('AC1')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('AC1', 'Görüşme Onay Durumu');
        $this->excel->getActiveSheet()->getStyle('AD1')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('AD1', 'Çalışan Hareket Planı');
        $this->excel->getActiveSheet()->getStyle('AE1')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('AE1', 'Yönlendirme Onay Durumu');
        $this->excel->getActiveSheet()->getStyle('AF1')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('AF1', 'Toplam Puan');
        $this->excel->getActiveSheet()->getStyle('AG1')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('AG1', 'Sistemin Önerdiği Kademe');
        $this->excel->getActiveSheet()->getStyle('AH1')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('AH1', 'Yöneticinin Belirlediği Kademe');
        $this->excel->getActiveSheet()->getStyle('AI1')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('AI1', 'Kademelendirme Onay Durumu');
        
        unset($kosul);
        $select = '*';
        $kosul['yil'] = $yil;
        $kosul['donem'] = $donem;
        $order_by['pernr'] = "ASC";
        $org = $this->genel_model->veri_cek_cs("organizasyon_d", $select, $kosul, $order_by);
        
        foreach ($org as $key=>$arr)
        {
            $ad_soyad[$arr['pernr']] = $arr['ad_soyad'];
            $poz[$arr['pernr']] = $arr['poz_text'];
            $ust[$arr['pernr']] = $arr['ust_pernr'];
        }
        
        unset($kosul);
        $select = '*';
        $formlar = $this->genel_model->veri_cek_cs("formlar", $select, $kosul);
        
        foreach ($formlar as $key=>$arr)
        {
            $form_adi[$arr['form_id']] = $arr['form_adi'];
        }
        
        unset($kosul);
        $select = '*';
        $kosul['yil'] = $yil;
        $kosul['donem'] = $donem;
        $form_atamalari = $this->genel_model->veri_cek_cs("form_atamalari", $select, $kosul);
        
        foreach ($form_atamalari as $key=>$arr)
        {
            $fa[$arr['pernr']][$arr['ft_id']] = $form_adi[$arr['form_id']];
        }
        
        unset($kosul);
        $select = '*';
        $havuzlar = $this->genel_model->veri_cek_cs("kademelendirme_havuzlari", $select, $kosul);
        
        foreach ($havuzlar as $key=>$arr)
        {
            $havuz_adi[$arr['havuz_id']] = $arr['havuz_text'];
        }
        
        unset($kosul);
        $select = '*';
        $kosul['yil'] = $yil;
        $kosul['donem'] = $donem;
        $kademelendirme_atamalari = $this->genel_model->veri_cek_cs("kademelendirme_atamalari", $select, $kosul);
        
        foreach ($kademelendirme_atamalari as $key=>$arr)
        {
            $ka[$arr['pernr']] = $havuz_adi[$arr['havuz_id']];
            $kademelendiren[$arr['pernr']] = $arr['k_pernr'];
        }
        
        unset($kosul);
        $select = '*';
        $kosul['yil'] = $yil;
        $kosul['donem'] = $donem;
        $kademelendirme_sonuclari = $this->genel_model->veri_cek_cs("kademelendirme_sonuclari", $select, $kosul);
        
        foreach ($kademelendirme_sonuclari as $key=>$arr)
        {
            $ks[$arr['pernr']]['vk'] = $arr['vk'];
            $ks[$arr['pernr']]['sk'] = $arr['sk'];
        }
        
        unset($kosul);
        $select = '*, sum(puan) as a';
        $kosul['yil'] = $yil;
        $kosul['degerlendiren_ack'] = 'yon1';
        // Yarıyıl ise sadece yarıyılın puanını al
        if ($donem == 2)
        {
            $kosul['donem'] = 2;
        }
        $group_by = "pernr, ft_id";
        $puan = $this->genel_model->veri_cek_cs("degerlendirmeler", $select, $kosul, '', '', $group_by);
        
        foreach ($puan as $key=>$arr) 
        {
            $puan_y[$arr['ft_id']][$arr['pernr']] = $arr['a'];
        }
        
        unset($kosul);
        $select = '*, sum(puan) as a';
        $kosul['yil'] = $yil;
        $kosul['degerlendiren_ack'] = 'rilen';
        $kosul['ft_id'] = 2;
        // Yarıyıl ise sadece yarıyılın puanını al
        if ($donem == 2)
        {
            $kosul['donem'] = 2;
        }
        $group_by = "pernr, ft_id";
        $puan = $this->genel_model->veri_cek_cs("degerlendirmeler", $select, $kosul, '', '', $group_by);
        
        foreach ($puan as $key=>$arr) 
        {
            $puan_a[$arr['ft_id']][$arr['pernr']] = $arr['a'];
        }
        
        unset($kosul);
        unset($where_in);
        unset($g_list);
        $g_list = array(7001, 7004, 7007, 10511);
        $select = '*';
        $kosul['yil'] = $yil;
        $kosul['donem'] = $donem;
        $kosul['degerlendiren_ack'] = 'yon1';
        $kosul['ft_id'] = 4;
        $where_in['g_id'] = $g_list;
        $group_by = "pernr";
        $gorusme = $this->genel_model->veri_cek_cs("degerlendirmeler", $select, $kosul, '', '', $group_by, $where_in);
        
        foreach ($gorusme as $key=>$arr) 
        {
            $go1[$arr['pernr']] = $arr['aciklama'];
        }
        
        unset($kosul);
        unset($where_in);
        unset($g_list);
        $g_list = array(7002, 7005, 7008, 10512);
        $select = '*';
        $kosul['yil'] = $yil;
        $kosul['donem'] = $donem;
        $kosul['degerlendiren_ack'] = 'yon1';
        $kosul['ft_id'] = 4;
        $where_in['g_id'] = $g_list;
        $group_by = "pernr";
        $gorusme = $this->genel_model->veri_cek_cs("degerlendirmeler", $select, $kosul, '', '', $group_by, $where_in);
        
        foreach ($gorusme as $key=>$arr) 
        {
            $go2[$arr['pernr']] = $arr['aciklama'];
        }
        
        unset($kosul);
        unset($where_in);
        unset($g_list);
        $g_list = array(7003, 7006, 7009, 10513);
        $select = '*';
        $kosul['yil'] = $yil;
        $kosul['donem'] = $donem;
        $kosul['degerlendiren_ack'] = 'yon1';
        $kosul['ft_id'] = 4;
        $where_in['g_id'] = $g_list;
        $group_by = "pernr";
        $gorusme = $this->genel_model->veri_cek_cs("degerlendirmeler", $select, $kosul, '', '', $group_by, $where_in);
        
        foreach ($gorusme as $key=>$arr) 
        {
            $go3[$arr['pernr']] = $arr['aciklama'];
        }
        
        unset($kosul);
        $select = '*';
        $kosul['yil'] = $yil;
        $kosul['donem'] = $donem;
        $kosul['degerlendiren_ack'] = 'yon1';
        $kosul['ft_id'] = 5;
        $group_by = "pernr";
        $gorusme = $this->genel_model->veri_cek_cs("degerlendirmeler", $select, $kosul, '', '', $group_by);
        
        foreach ($gorusme as $key=>$arr) 
        {
            $yo1[$arr['pernr']] = $arr['hp_ack'];
        }
        
        unset($kosul);
        $select = "*";
        $kosul['yil'] = $yil;
        $kosul['donem'] = $donem;
        $group_by = "degerlendiren, ft_id";
        $onaylar = $this->genel_model->veri_cek_cs("onaylar", $select, $kosul, '', '', $group_by);
        
        foreach ($onaylar as $key=>$arr) 
        {
            $onay[$arr['ft_id']][$arr['degerlendiren']] = $arr['onay_text'];
        }
        
        $i = 1;
        foreach ($org as $key=>$arr)
        {
            $i++;
            $a = $arr['yil'];
            $this->excel->getActiveSheet()->setCellValue('A'.$i, $a);
            
            $a = $arr['donem'];
            $this->excel->getActiveSheet()->setCellValue('B'.$i, $a);
            
            $a = $arr['pernr'];
            $this->excel->getActiveSheet()->setCellValue('C'.$i, $a);
            
            $a = $arr['ad_soyad'];
            $this->excel->getActiveSheet()->setCellValue('D'.$i, $a);
            
            $a = $arr['poz_text'];
            $this->excel->getActiveSheet()->setCellValue('E'.$i, $a);
            
            $a = $arr['ust_pernr'];
            $this->excel->getActiveSheet()->setCellValue('F'.$i, $a);
            
            $a = $arr['ust_ad_soyad'];
            $this->excel->getActiveSheet()->setCellValue('G'.$i, $a);
            
            $a = $poz[$arr['pernr']];
            $this->excel->getActiveSheet()->setCellValue('H'.$i, $a);
            
            $a = $fa[$arr['pernr']]['2']; // Yetkinlik Formu
            $this->excel->getActiveSheet()->setCellValue('I'.$i, $a);
            
            $a = $fa[$arr['pernr']]['1']; // Hedef Formu
            $this->excel->getActiveSheet()->setCellValue('J'.$i, $a);
            
            $a = $fa[$arr['pernr']]['3']; // Potansiyel Formu
            $this->excel->getActiveSheet()->setCellValue('K'.$i, $a);
            
            $a = $fa[$arr['pernr']]['4']; // Görüşme Formu
            $this->excel->getActiveSheet()->setCellValue('L'.$i, $a);
            
            $a = $fa[$arr['pernr']]['5']; // Yönlendirme Formu
            $this->excel->getActiveSheet()->setCellValue('M'.$i, $a);
            
            $a = $ka[$arr['pernr']]; // Kademelendirme Havuzu
            $this->excel->getActiveSheet()->setCellValue('N'.$i, $a);
            
            $a = $kademelendiren[$arr['pernr']]; // Kademelendiren pernr
            $this->excel->getActiveSheet()->setCellValue('O'.$i, $a);
            
            $a = $ad_soyad[$kademelendiren[$arr['pernr']]]; // Kademelendiren ad soyad
            $this->excel->getActiveSheet()->setCellValue('P'.$i, $a);
            
            $a = $poz[$kademelendiren[$arr['pernr']]]; // Kademelendiren pozisyon
            $this->excel->getActiveSheet()->setCellValue('Q'.$i, $a);
            
            if ($fa[$arr['pernr']]['2'])
            {
                $a = $puan_a[2][$arr['pernr']];
                $this->excel->getActiveSheet()->setCellValue('R'.$i, $a);

                $a = $puan_y[2][$arr['pernr']];
                $this->excel->getActiveSheet()->setCellValue('S'.$i, $a);

                $a = $onay[2][$ust[$arr['pernr']]];
                $this->excel->getActiveSheet()->setCellValue('T'.$i, $a);
            }
            else
            {
                $a = "Form Yok";
                $this->excel->getActiveSheet()->setCellValue('R'.$i, $a);

                $a = "Form Yok";
                $this->excel->getActiveSheet()->setCellValue('S'.$i, $a);

                $a = "Form Yok";
                $this->excel->getActiveSheet()->setCellValue('T'.$i, $a);
            }
            
            if ($fa[$arr['pernr']]['1'])
            {
                $a = $puan_y[1][$arr['pernr']];
                $this->excel->getActiveSheet()->setCellValue('U'.$i, $a);

                $a = $onay[1][$ust[$arr['pernr']]];
                $this->excel->getActiveSheet()->setCellValue('V'.$i, $a);
            }
            else
            {
                $a = "Form Yok";
                $this->excel->getActiveSheet()->setCellValue('U'.$i, $a);

                $a = "Form Yok";
                $this->excel->getActiveSheet()->setCellValue('V'.$i, $a);
            }
            
            if ($fa[$arr['pernr']]['3'])
            {
                $a = $puan_y[3][$arr['pernr']];
                $this->excel->getActiveSheet()->setCellValue('W'.$i, $a);

                if ($puan_y[3][$arr['pernr']] > 70)
                {
                    $a = "Yeterli";
                }
                else if ($puan_y[3][$arr['pernr']] > 0)
                {
                    $a = "Yetersiz";
                }
                $this->excel->getActiveSheet()->setCellValue('X'.$i, $a);

                $a = $onay[3][$ust[$arr['pernr']]];
                $this->excel->getActiveSheet()->setCellValue('Y'.$i, $a);
            }
            else
            {
                $a = "Form Yok";
                $this->excel->getActiveSheet()->setCellValue('W'.$i, $a);

                $a = "Form Yok";
                $this->excel->getActiveSheet()->setCellValue('X'.$i, $a);

                $a = "Form Yok";
                $this->excel->getActiveSheet()->setCellValue('Y'.$i, $a);
            }
            
            if ($fa[$arr['pernr']]['4'])
            {
                $a = $go1[$arr['pernr']];
                $this->excel->getActiveSheet()->setCellValue('Z'.$i, $a);

                $a = $go2[$arr['pernr']];
                $this->excel->getActiveSheet()->setCellValue('AA'.$i, $a);

                $a = $go3[$arr['pernr']];
                $this->excel->getActiveSheet()->setCellValue('AB'.$i, $a);
                
                $a = $onay[4][$ust[$arr['pernr']]];
                $this->excel->getActiveSheet()->setCellValue('AC'.$i, $a);
            }
            else
            {
                $a = "Form Yok";
                $this->excel->getActiveSheet()->setCellValue('Z'.$i, $a);

                $a = "Form Yok";
                $this->excel->getActiveSheet()->setCellValue('AA'.$i, $a);

                $a = "Form Yok";
                $this->excel->getActiveSheet()->setCellValue('AB'.$i, $a);
                
                $a = "Form Yok";
                $this->excel->getActiveSheet()->setCellValue('AC'.$i, $a);
            }
            
            if ($fa[$arr['pernr']]['5'])
            {
                $a = $yo1[$arr['pernr']];
                $this->excel->getActiveSheet()->setCellValue('AD'.$i, $a);
                
                $a = $onay[5][$ust[$arr['pernr']]];
                $this->excel->getActiveSheet()->setCellValue('AE'.$i, $a);
            }
            else
            {
                $a = "Form Yok";
                $this->excel->getActiveSheet()->setCellValue('AD'.$i, $a);
                
                $a = "Form Yok";
                $this->excel->getActiveSheet()->setCellValue('AE'.$i, $a);
            }
            
            if ($fa[$arr['pernr']]['1'] || $fa[$arr['pernr']]['2'])
            {
                $a = $puan_y[1][$arr['pernr']]+$puan_y[2][$arr['pernr']];
                $this->excel->getActiveSheet()->setCellValue('AF'.$i, $a);
            }
            else
            {
                $a = "Form Yok";
                $this->excel->getActiveSheet()->setCellValue('AF'.$i, $a);
            }
            
            if (is_array($ks[$arr['pernr']]))
            {
                $a = $ks[$arr['pernr']]['vk'];
                $this->excel->getActiveSheet()->setCellValue('AG'.$i, $a);
                
                $a = $ks[$arr['pernr']]['sk'];
                $this->excel->getActiveSheet()->setCellValue('AH'.$i, $a);

                $a = $onay[99][$ust[$arr['pernr']]];
                $this->excel->getActiveSheet()->setCellValue('AI'.$i, $a);
            }
            else
            {
                $a = "Kademelendirme Henüz Başlamadı";
                $this->excel->getActiveSheet()->setCellValue('AG'.$i, $a);
                
                $a = "Kademelendirme Henüz Başlamadı";
                $this->excel->getActiveSheet()->setCellValue('AH'.$i, $a);
                
                $a = "Kademelendirme Henüz Başlamadı";
                $this->excel->getActiveSheet()->setCellValue('AI'.$i, $a);
            }
        }
        
        $tarih = date("Y-m-d");
        $filename='PerformansGenelRapor_'.$tarih.'.xlsx'; //save our workbook as this file name
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache

        //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
        //if you want to save it as .XLSX Excel 2007 format
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');  
        //force user to download the Excel file without writing it to server's HD
        $objWriter->save('php://output');

    }
    
    public function gostergeler()
    {
        $select = '*';
        $order_by['g_id'] = 'desc';
        $tablo = $this->genel_model->veri_cek_cs("gostergeler", $select, '', $order_by);
        
        $this->load->library('excel');
        //activate worksheet number 1
        $this->excel->setActiveSheetIndex(0);
        
        $this->excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
        //$this->excel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
        
        $this->excel->getActiveSheet()->getPageSetup()->setFitToWidth(1);
        $this->excel->getActiveSheet()->getPageSetup()->setFitToHeight(0);
//        $this->excel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
//        $this->excel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
//        $this->excel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
//        $this->excel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
//        $this->excel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
//        $this->excel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
//        $this->excel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
//        $this->excel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
//        $this->excel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
//        $this->excel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
//        $this->excel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
//        $this->excel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
//        $this->excel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
//        $this->excel->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
//        $this->excel->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
//        $this->excel->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);
//        $this->excel->getActiveSheet()->getColumnDimension('Q')->setAutoSize(true);
//        $this->excel->getActiveSheet()->getColumnDimension('R')->setAutoSize(true);
//        $this->excel->getActiveSheet()->getColumnDimension('S')->setAutoSize(true);
//        $this->excel->getActiveSheet()->getColumnDimension('T')->setAutoSize(true);
        
        $this->excel->getActiveSheet()->setTitle('Göstergeler');
        
        $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('A1', 'Form Tipi');
        $this->excel->getActiveSheet()->getStyle('B1')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('B1', 'Gös. Adı');
        $this->excel->getActiveSheet()->getStyle('C1')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('C1', 'Yıllık Periyod');
        $this->excel->getActiveSheet()->getStyle('D1')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('D1', 'Gös. Tn.');
        $this->excel->getActiveSheet()->getStyle('E1')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('E1', 'Gös. Açk.');
        $this->excel->getActiveSheet()->getStyle('F1')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('F1', 'Veri Kaynağı');
        $this->excel->getActiveSheet()->getStyle('G1')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('G1', 'Veri Hesaplama');
        $this->excel->getActiveSheet()->getStyle('H1')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('H1', 'Değerlendirme Türü');
        $this->excel->getActiveSheet()->getStyle('I1')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('I1', 'Planlanan Tanımı');
        $this->excel->getActiveSheet()->getStyle('J1')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('J1', 'Gerçekleşen Tanımı');
        $this->excel->getActiveSheet()->getStyle('K1')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('K1', 'Sonuç Tanımı');
        $this->excel->getActiveSheet()->getStyle('L1')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('L1', 'K1 AS (%)');
        $this->excel->getActiveSheet()->getStyle('M1')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('M1', 'K1 ÜS (%)');
        $this->excel->getActiveSheet()->getStyle('N1')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('N1', 'K2 AS (%)');
        $this->excel->getActiveSheet()->getStyle('O1')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('O1', 'K2 ÜS (%)');
        $this->excel->getActiveSheet()->getStyle('P1')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('P1', 'K3 AS (%)');
        $this->excel->getActiveSheet()->getStyle('Q1')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('Q1', 'K3 ÜS (%)');
        $this->excel->getActiveSheet()->getStyle('R1')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('R1', 'K4 AS (%)');
        $this->excel->getActiveSheet()->getStyle('S1')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('S1', 'K4 ÜS (%)');
        $this->excel->getActiveSheet()->getStyle('T1')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('T1', 'Durum');
        $this->excel->getActiveSheet()->getStyle('U1')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('U1', 'K1 Açıklama');
        $this->excel->getActiveSheet()->getStyle('V1')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('V1', 'K4 Açıklam');
        
        $i = 1;
        foreach ($tablo as $key=>$arr)
        {
            $i++;
            $a = $arr['ft_id']. "(".$arr['ft_text'].")";
            $this->excel->getActiveSheet()->setCellValue('A'.$i, $a);
            
            $a = $arr['g_adi'];
            $this->excel->getActiveSheet()->setCellValue('B'.$i, $a);
            
            $a = $arr['periyod'];
            $this->excel->getActiveSheet()->setCellValue('C'.$i, $a);
            
            $a = $arr['g_text'];
            $this->excel->getActiveSheet()->setCellValue('D'.$i, $a);
            
            $a = $arr['g_desc'];
            $this->excel->getActiveSheet()->setCellValue('E'.$i, $a);
            
            $a = $arr['veri_kaynagi_text'];
            $this->excel->getActiveSheet()->setCellValue('F'.$i, $a);
            
            $a = $arr['veri_hesaplama_text'];
            $this->excel->getActiveSheet()->setCellValue('G'.$i, $a);
            
            $a = $arr['degerlendirme_turu_text'];
            $this->excel->getActiveSheet()->setCellValue('H'.$i, $a);
            
            $a = $arr['planlanan_text'];
            $this->excel->getActiveSheet()->setCellValue('I'.$i, $a);
            
            $a = $arr['gerceklesen_text'];
            $this->excel->getActiveSheet()->setCellValue('J'.$i, $a);
            
            $a = $arr['sonuc_text'];
            $this->excel->getActiveSheet()->setCellValue('K'.$i, $a);
            
            $a = $arr['k1_as'];
            $this->excel->getActiveSheet()->setCellValue('L'.$i, $a);
            
            $a = $arr['k1_us'];
            $this->excel->getActiveSheet()->setCellValue('M'.$i, $a);
            
            $a = $arr['k2_as'];
            $this->excel->getActiveSheet()->setCellValue('N'.$i, $a);
            
            $a = $arr['k2_us'];
            $this->excel->getActiveSheet()->setCellValue('O'.$i, $a);
            
            $a = $arr['k3_as'];
            $this->excel->getActiveSheet()->setCellValue('P'.$i, $a);
            
            $a = $arr['k3_us'];
            $this->excel->getActiveSheet()->setCellValue('Q'.$i, $a);
            
            $a = $arr['k4_as'];
            $this->excel->getActiveSheet()->setCellValue('R'.$i, $a);

            $a = $arr['k4_us'];
            $this->excel->getActiveSheet()->setCellValue('S'.$i, $a);

            
            if ($arr['durum'] == 1)
            {
                $a = "Aktif";
            }
            else
            {
                $a = "Pasif";
            }
            
            $this->excel->getActiveSheet()->setCellValue('T'.$i, $a);
            
            $a = $arr['k1_desc'];
            $this->excel->getActiveSheet()->setCellValue('U'.$i, $a);
            
            $a = $arr['k4_desc'];
            $this->excel->getActiveSheet()->setCellValue('V'.$i, $a);
        }
        
        $tarih = date("Y-m-d");
        $filename='Göstergeler_'.$tarih.'.xlsx'; //save our workbook as this file name
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache

        //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
        //if you want to save it as .XLSX Excel 2007 format
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');  
        //force user to download the Excel file without writing it to server's HD
        $objWriter->save('php://output');
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
                
                $toplam = 0;
                foreach ($tablo2 as $key1=>$arr1)
                {
                    $toplam = $toplam+$arr1['agirlik'];
                }
                
                $top_agirlik[$arr['form_id']] = $toplam;
                $data['formlar_detay'][$arr['form_id']] = $tablo2;
            }
        }
        
        unset($kosul);
        $select = '*';
        $kosul['durum'] = 1; 
        $tablo3 = $this->genel_model->veri_cek_cs("gostergeler", $select, $kosul);
        
        if (is_array($tablo3))
        {
            foreach ($tablo3 as $key1 => $arr1)
            {
                $gostergeler2[$arr1['ft_id']][$arr1['g_id']] = $arr1['g_adi'];
            }
        }
        
        $data['gostergeler1'] = $gostergeler2;
        
        $this->load->library('excel');
        //activate worksheet number 1
        $this->excel->setActiveSheetIndex(0);
        
        $this->excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
        //$this->excel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
        
        $this->excel->getActiveSheet()->getPageSetup()->setFitToWidth(1);
        $this->excel->getActiveSheet()->getPageSetup()->setFitToHeight(0);
        $this->excel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        $this->excel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $this->excel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $this->excel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
        $this->excel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
        $this->excel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
        
        $this->excel->getActiveSheet()->setTitle('Formlar');
        
        $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('A1', 'Form Tipi');
        $this->excel->getActiveSheet()->getStyle('B1')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('B1', 'Form Adı');
        $this->excel->getActiveSheet()->getStyle('C1')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('C1', 'Gös. Adı');
        $this->excel->getActiveSheet()->getStyle('D1')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('D1', 'Gös. Ağr.');
        $this->excel->getActiveSheet()->getStyle('E1')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('E1', 'Top. Ağr.');
        $this->excel->getActiveSheet()->getStyle('F1')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('F1', 'Durum');
        
        $i = 1;
        foreach ($tablo as $key=>$arr)
        {
            foreach ($data['formlar_detay'][$arr['form_id']] as $key1=>$arr1)
            {
                $i++;
                $a = $arr['ft_id']. "(".$arr['ft_text'].")";
                $this->excel->getActiveSheet()->setCellValue('A'.$i, $a);

                $a = $arr['form_adi'];
                $this->excel->getActiveSheet()->setCellValue('B'.$i, $a);

                $a = $gostergeler2[$arr['ft_id']][$arr1['g_id']];
                $this->excel->getActiveSheet()->setCellValue('C'.$i, $a);

                if ($arr['ft_id'] != 4 && $arr['ft_id'] != 5)
                {
                    $a = $arr1['agirlik'];
                }
                else
                {
                    $a = "";
                }
                $this->excel->getActiveSheet()->setCellValue('D'.$i, $a);

                if ($arr['ft_id'] != 4 && $arr['ft_id'] != 5)
                {
                    $a = $top_agirlik[$arr['form_id']];
                }
                else
                {
                    $a = "";
                }
                $this->excel->getActiveSheet()->setCellValue('E'.$i, $a);
                
                if ($arr['durum'] == 1)
                {
                    $a = "Aktif";
                }
                else
                {
                    $a = "Pasif";
                }

                $this->excel->getActiveSheet()->setCellValue('F'.$i, $a);
                
            }
        }
        
        $tarih = date("Y-m-d");
        $filename='Formlar_'.$tarih.'.xlsx'; //save our workbook as this file name
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache

        //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
        //if you want to save it as .XLSX Excel 2007 format
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');  
        //force user to download the Excel file without writing it to server's HD
        $objWriter->save('php://output');
        
    }
    
    public function org_semasi($yil, $donem)
    {
        $this->load->model("ikyp_model");
        
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
        $tablo2 = $this->genel_model->veri_cek_cs("organizasyon_d", $select, $kosul);
        $data['sema_tablo'] = $tablo2;
        
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
        
        $this->load->library('excel');
        //activate worksheet number 1
        $this->excel->setActiveSheetIndex(0);
        
        $this->excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
        //$this->excel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
        
        $this->excel->getActiveSheet()->getPageSetup()->setFitToWidth(1);
        $this->excel->getActiveSheet()->getPageSetup()->setFitToHeight(0);
        $this->excel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        $this->excel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $this->excel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $this->excel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
        $this->excel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
        $this->excel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
        $this->excel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
        $this->excel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
        $this->excel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
        $this->excel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
        $this->excel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
        $this->excel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
        $this->excel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
        $this->excel->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
        $this->excel->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
        $this->excel->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);
        $this->excel->getActiveSheet()->getColumnDimension('Q')->setAutoSize(true);
        
        $this->excel->getActiveSheet()->setTitle('org_semasi');
        
        $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('A1', 'Yıl');
        $this->excel->getActiveSheet()->getStyle('B1')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('B1', 'Dönem');
        $this->excel->getActiveSheet()->getStyle('C1')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('C1', 'Per. Nr.');
        $this->excel->getActiveSheet()->getStyle('D1')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('D1', 'Ad Soyad');
        $this->excel->getActiveSheet()->getStyle('E1')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('E1', 'Poz. Id.');
        $this->excel->getActiveSheet()->getStyle('F1')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('F1', 'Poz. Tnm.');
        $this->excel->getActiveSheet()->getStyle('G1')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('G1', 'Org. Id');
        $this->excel->getActiveSheet()->getStyle('H1')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('H1', 'Org. Tnm.');
        $this->excel->getActiveSheet()->getStyle('I1')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('I1', 'Yon. Per. Nr.');
        $this->excel->getActiveSheet()->getStyle('J1')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('J1', 'Yon. Ad Soyad');
        $this->excel->getActiveSheet()->getStyle('K1')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('K1', 'Hedef Formları');
        $this->excel->getActiveSheet()->getStyle('L1')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('L1', 'Yetkinlik Formları');
        $this->excel->getActiveSheet()->getStyle('M1')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('M1', 'Potansiyel Formları');
        $this->excel->getActiveSheet()->getStyle('N1')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('N1', 'Görüşme Formları');
        $this->excel->getActiveSheet()->getStyle('O1')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('O1', 'Yönlendirme Formları');
        $this->excel->getActiveSheet()->getStyle('P1')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('P1', 'Kademelendirme Havuzu');
        $this->excel->getActiveSheet()->getStyle('Q1')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('Q1', 'Kademelendirecek Kişi');
        
        $i = 1;
        foreach ($data['sema_tablo'] as $key=>$arr)
        {
            $i++;
            $a = $arr['yil'];
            $this->excel->getActiveSheet()->setCellValue('A'.$i, $a);
            
            $a = $arr['donem'];
            $this->excel->getActiveSheet()->setCellValue('B'.$i, $a);
            
            $a = $arr['pernr'];
            $this->excel->getActiveSheet()->setCellValue('C'.$i, $a);
            
            $a = $arr['ad_soyad'];
            $this->excel->getActiveSheet()->setCellValue('D'.$i, $a);
            
            $a = $arr['poz_id'];
            $this->excel->getActiveSheet()->setCellValue('E'.$i, $a);
            
            $a = $arr['poz_text'];
            $this->excel->getActiveSheet()->setCellValue('F'.$i, $a);
            
            $a = $arr['org_id'];
            $this->excel->getActiveSheet()->setCellValue('G'.$i, $a);
            
            $a = $arr['org_text'];
            $this->excel->getActiveSheet()->setCellValue('H'.$i, $a);
            
            $a = $arr['ust_pernr'];
            $this->excel->getActiveSheet()->setCellValue('I'.$i, $a);
            
            $a = $arr['ust_ad_soyad'];
            $this->excel->getActiveSheet()->setCellValue('J'.$i, $a);
            
            $a = $data['form_atamalari'][$arr['pernr']][1]; 
            $this->excel->getActiveSheet()->setCellValue('K'.$i, $a);
            
            $a = $data['form_atamalari'][$arr['pernr']][2];
            $this->excel->getActiveSheet()->setCellValue('L'.$i, $a);
            
            $a = $data['form_atamalari'][$arr['pernr']][3];
            $this->excel->getActiveSheet()->setCellValue('M'.$i, $a);
            
            $a = $data['form_atamalari'][$arr['pernr']][4];
            $this->excel->getActiveSheet()->setCellValue('N'.$i, $a);
            
            $a = $data['form_atamalari'][$arr['pernr']][5];
            $this->excel->getActiveSheet()->setCellValue('O'.$i, $a);
            
            $a = $data['kademelendirme_atamalari'][$arr['pernr']];
            $this->excel->getActiveSheet()->setCellValue('P'.$i, $a);
            
            $a = $data['kademelendirme_atamalari2'][$arr['pernr']];
            $this->excel->getActiveSheet()->setCellValue('Q'.$i, $a);
        }
        
        $tarih = date("Y-m-d");
        $filename='Org_semasi_'.$tarih.'.xlsx'; //save our workbook as this file name
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache

        //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
        //if you want to save it as .XLSX Excel 2007 format
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');  
        //force user to download the Excel file without writing it to server's HD
        $objWriter->save('php://output');
        
    }
    
    public function atama_ekrani()
    {
        $this->load->model("atamalar_model");

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
        
        $yil_max1 = $yil_max-1;
        $yil_max2 = $yil_max-2;
        $yil_max3 = $yil_max-3;
        $yil_max4 = $yil_max-4;
        $yil_max5 = $yil_max-5;
        $yil_max6 = $yil_max-6;
        
        $data['yil_min'] = $yil_min;
        $data['yil_max'] = $yil_max;
        
        unset($kosul);
        unset($order_by);
        $select = '*';
        $order_by['yil'];
        $puanlar = $this->genel_model->veri_cek_cs("puanlar", $select, '', $order_by);
        
        foreach ( $puanlar as $key=>$arr)
        {
            $data['puan'][$arr['pernr']][$arr['yil']] = $arr['puan'];
        }
        
        unset($kosul);
        unset($order_by);
        $select = '*';
        $order_by['yil'];
        $puanlar = $this->genel_model->veri_cek_cs("puanlar", $select, '', $order_by);
        
        foreach ( $puanlar as $key=>$arr)
        {
            $data['puan'][$arr['pernr']][$arr['yil']] = $arr['puan'];
        }
        
        unset($kosul);
        unset($order_by);
        $select = '*';
        $kosul['yil'] = $yil_max;
        $ks = $this->genel_model->veri_cek_cs("kademelendirme_sonuclari", $select, $kosul, $order_by);
        
        foreach ( $ks as $key=>$arr)
        {
            $data['puan'][$arr['pernr']][$arr['yil']] = $arr['sk'];
        }
        
        $this->load->library('excel');
        //activate worksheet number 1
        $this->excel->setActiveSheetIndex(0);
        
        $this->excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
        //$this->excel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
        
        $this->excel->getActiveSheet()->getPageSetup()->setFitToWidth(1);
        $this->excel->getActiveSheet()->getPageSetup()->setFitToHeight(0);
        $this->excel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        $this->excel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $this->excel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $this->excel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
        $this->excel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
        $this->excel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
        $this->excel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
        $this->excel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
        $this->excel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
        $this->excel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
        $this->excel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
        $this->excel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
        $this->excel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
        $this->excel->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
        $this->excel->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
        $this->excel->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);
        $this->excel->getActiveSheet()->getColumnDimension('Q')->setAutoSize(true);
        
        $this->excel->getActiveSheet()->setTitle('atama_ekrani');
        
        $this->excel->getActiveSheet()->mergeCells('A1:D1');
        $this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('A1', 'Personel Bilgileri');
        
        $this->excel->getActiveSheet()->mergeCells('E1:K1');
        $this->excel->getActiveSheet()->getStyle('E1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('E1')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('E1', 'Kademeler');
        
        $this->excel->getActiveSheet()->mergeCells('L1:R1');
        $this->excel->getActiveSheet()->getStyle('L1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('L1')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('L1', 'Kademeler');
        
        $this->excel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('A2', 'Pernr');
        $this->excel->getActiveSheet()->getStyle('B2')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('B2', 'Ad Soyad');
        $this->excel->getActiveSheet()->getStyle('C2')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('C2', 'Poz. Id.');
        $this->excel->getActiveSheet()->getStyle('D2')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('D2', 'Pozisyon');
        $this->excel->getActiveSheet()->getStyle('E2')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('E2', $yil_max);
        $this->excel->getActiveSheet()->getStyle('F2')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('F2', $yil_max1);
        $this->excel->getActiveSheet()->getStyle('G2')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('G2', $yil_max2);
        $this->excel->getActiveSheet()->getStyle('H2')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('H2', $yil_max3);
        $this->excel->getActiveSheet()->getStyle('I2')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('I2', $yil_max4);
        $this->excel->getActiveSheet()->getStyle('J2')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('J2', $yil_max5);
        $this->excel->getActiveSheet()->getStyle('K2')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('K2', $yil_max6);
        $this->excel->getActiveSheet()->getStyle('L2')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('L2', $yil_max);
        $this->excel->getActiveSheet()->getStyle('M2')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('M2', $yil_max1);
        $this->excel->getActiveSheet()->getStyle('N2')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('N2', $yil_max2);
        $this->excel->getActiveSheet()->getStyle('O2')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('O2', $yil_max3);
        $this->excel->getActiveSheet()->getStyle('P2')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('P2', $yil_max4);
        $this->excel->getActiveSheet()->getStyle('Q2')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('Q2', $yil_max5);
        $this->excel->getActiveSheet()->getStyle('R2')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('R2', $yil_max6);
        
        $i = 2;
        foreach ($data['personeller'] as $key=>$arr)
        {
            $i++;
            $a = $arr['pernr'];
            $this->excel->getActiveSheet()->setCellValue('A'.$i, $a);
            
            $a = $arr['ad_soyad'];
            $this->excel->getActiveSheet()->setCellValue('B'.$i, $a);
            
            $a = $arr['poz_id'];
            $this->excel->getActiveSheet()->setCellValue('C'.$i, $a);
            
            $a = $arr['poz_text'];
            $this->excel->getActiveSheet()->setCellValue('D'.$i, $a);
            
            $a = $data['kademe'][$arr['pernr']][$yil_max];
            $this->excel->getActiveSheet()->setCellValue('E'.$i, $a);
            
            $a = $data['kademe'][$arr['pernr']][$yil_max1];
            $this->excel->getActiveSheet()->setCellValue('F'.$i, $a);
            
            $a = $data['kademe'][$arr['pernr']][$yil_max2];
            $this->excel->getActiveSheet()->setCellValue('G'.$i, $a);
            
            $a = $data['kademe'][$arr['pernr']][$yil_max3];
            $this->excel->getActiveSheet()->setCellValue('H'.$i, $a);
            
            $a = $data['kademe'][$arr['pernr']][$yil_max4];
            $this->excel->getActiveSheet()->setCellValue('I'.$i, $a);
            
            $a = $data['kademe'][$arr['pernr']][$yil_max5];
            $this->excel->getActiveSheet()->setCellValue('J'.$i, $a);
            
            $a = $data['kademe'][$arr['pernr']][$yil_max6];
            $this->excel->getActiveSheet()->setCellValue('K'.$i, $a);
            
            $a = $data['puan'][$arr['pernr']][$yil_max];
            $this->excel->getActiveSheet()->setCellValue('L'.$i, $a);
            
            $a = $data['puan'][$arr['pernr']][$yil_max1];
            $this->excel->getActiveSheet()->setCellValue('M'.$i, $a);
            
            $a = $data['puan'][$arr['pernr']][$yil_max2];
            $this->excel->getActiveSheet()->setCellValue('N'.$i, $a);
            
            $a = $data['puan'][$arr['pernr']][$yil_max3];
            $this->excel->getActiveSheet()->setCellValue('O'.$i, $a);
            
            $a = $data['puan'][$arr['pernr']][$yil_max4];
            $this->excel->getActiveSheet()->setCellValue('P'.$i, $a);
            
            $a = $data['puan'][$arr['pernr']][$yil_max5];
            $this->excel->getActiveSheet()->setCellValue('Q'.$i, $a);
            
            $a = $data['puan'][$arr['pernr']][$yil_max6];
            $this->excel->getActiveSheet()->setCellValue('R'.$i, $a);
        }
        
        $tarih = date("Y-m-d");
        $filename='Atama_ekrani_'.$tarih.'.xlsx'; //save our workbook as this file name
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache

        //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
        //if you want to save it as .XLSX Excel 2007 format
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');  
        //force user to download the Excel file without writing it to server's HD
        $objWriter->save('php://output');
        
    }
    
}
