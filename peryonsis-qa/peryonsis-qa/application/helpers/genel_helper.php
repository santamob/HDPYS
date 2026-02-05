<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if ( ! function_exists('turkcele'))
{
    function turkcele($metin)
    {
        $bulunacak = array('ç','Ç','ı','İ','ğ','Ğ','ü','ö','Ş','ş','Ö','Ü',',',' ','(',')','[',']','-');
        $degistir  = array('c','C','i','I','g','G','u','o','S','s','O','U','','_','','','','','_');

        $sonuc=str_replace($bulunacak, $degistir, $metin);

        return $sonuc;
    }
}

if ( ! function_exists('empty_f'))
{
    function empty_f($metin)
    {
        if ($metin == '' || $metin == null)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
}

if ( ! function_exists('tarih_saat'))
{
    function tarih_saat($originalDate)
    {
        if ($originalDate == "" || $originalDate == '0000-00-00 00:00:00')
        {
            return "";
        }
        else
        {
            $newDate = '<i class="fa fa-fw fa-calendar"></i> ';
            $newDate = $newDate.date("d-m-Y", strtotime($originalDate));
            $newDate = $newDate.' <span class="glyphicon glyphicon-time"></span> ';
            $newDate = $newDate.date("H:i", strtotime($originalDate));
            return $newDate;
        }
    }
}

if ( ! function_exists('tarih'))
{
    function tarih($originalDate)
    {
        if ($originalDate == "" || $originalDate == '0000-00-00' || $originalDate == '0000-00-00 00:00:00')
        {
            return "";
        }
        else
        {
            $newDate = '<i class="fa fa-fw fa-calendar"></i> ';
            $newDate = $newDate.date("d-m-Y", strtotime($originalDate));
            return $newDate;
        }
    }
}

if ( ! function_exists('mail_gonder'))
{
    // function mail_gonder($subject, $message, $to,$cc="")
    // {
    //     $ci =& get_instance();
    //     $ci->load->library('email');
    //     $config['charset']      = 'utf-8';
    //     $config['mailtype']     = 'html';

    //     // $config['protocol']     = 'smtp';
    //     // $config['smtp_host']    = 'smtp.office365.com'; //ssl://
    //     // $config['smtp_user']    = 'web@santafarma.net';
    //     // $config['smtp_pass']    = 'zG8p*f!R';
    //     // $config['smtp_port']    = 25;
    //     // $config['smtp_crypto']    = 'tls';
    //     // $config['crlf']    =  "\r\n";

    //     // yeni
    //    // $this->mail->IsSMTP(); // send via SMTP 
	// 	//$this->mail->Host  = "172.20.20.5";
	// 	//$mail->IsSMTP(); // send via SMTP 
	// 	//$mail->Host     = "smtp.office365.com:25"; // SMTP servers 
	// 	//$mail->SMTPSecure = "tls";
	// 	//$mail->port = 25;
	//     /*  $mail->SMTPAuth = true; // turn on SMTP authentication 
	// 	$mail->Username = "yanitlama@santafarma.net"; // SMTP username 
	// 	$mail->Password = "96241119aA"; // SMTP password  */
	
		
	
    //     //$ci->mail->IsSMTP(); // send via SMTP 
	// 	//$ci->mail->Host  = "172.20.20.5";
	// 	//$ci->mail->IsHTML(true);
	// 	//$ci->mail->CharSet = "utf-8";
		
		

    //      // yeni



    //     $config['protocol']     = 'smtp';
    //     $config['smtp_host']    = 'santafarma-net.mail.protection.outlook.com:25'; //ssl://
    //     //$config['smtp_user']    = 'web@santafarma.net';
    //     //$config['smtp_pass']    = 'zG8p*f!R';
    //     $config['smtp_port']    = 25;
    //     //$config['smtp_crypto']    = 'tls';
    //     $config['crlf']    =  "\r\n";




    //     $ci->email->initialize( $config );
    //     $ci->email->set_newline("\r\n");
        
    //     if (ENVIRONMENT == 'production')
    //     {
    //         $ci->email->from('web@santafarma.net', 'Performans Sistemi', 'web@santafarma.net');
    //         $ci->email->to($to);
    //         $ci->email->cc($cc);
    //         $ci->email->bcc($bcc);
            
    //         $ci->email->subject($subject);
    //         $ci->email->message($message);

    //         $ci->email->send();
    //         //show_error($ci->email->print_debugger());
    //     }
    //     else if (ENVIRONMENT == 'qa')
    //     {
    //         if (is_array($to))
    //         {
    //             foreach ($to as $key=>$value)
    //             {
    //                 $message = $message."<br/>".$value;
    //             }
    //         }
    //         else
    //         {
    //             $message = $message."<br/>".$to;
    //         }
            
    //         $ci->email->from('web@santafarma.net', 'Performans Sistemi', 'web@santafarma.net');
    //         $ci->email->to('gaytekin@santafarma.com.tr');
    //         if(isset($cc)){
    //             $ci->email->cc($cc);
    //         }
            
    //         $ci->email->subject($subject);
    //         $ci->email->message($message);

    //         $ci->email->send();
    //         //$ci->email->send();
    //     //            show_error($ci->email->print_debugger());
    //     }
    //     else if (ENVIRONMENT == 'development')
    //     {
    //         if (is_array($to))
    //         {
    //             foreach ($to as $key=>$value)
    //             {
    //                 $message = $message."<br/>".$value;
    //             }
    //         }
    //         else
    //         {
    //             $message = $message."<br/>".$to;
    //         }
            
    //         $ci->email->from('web@santafarma.net', 'Performans Sistemi', 'web@santafarma.net');
    //         $ci->email->to('gaytekin@santafarma.com.tr');
            
    //         $ci->email->subject($subject);
    //         $ci->email->message($message);

    //         $ci->email->send();
    //         //$ci->email->send();
    //         //show_error($ci->email->print_debugger());
    //     }
    // }
    
    function mail_gonder($subject, $message, $to,$cc="")
    {
        $ci =& get_instance();
        $ci->load->library('My_email');
       
        if (ENVIRONMENT == 'production'){
            //$ci->my_email->sendEmail($subject,$message, $to, 'santafarma.net');
            // $ci->My_email->cc($cc); ?? 
            
        }
        else if (ENVIRONMENT == 'qa'){
            $to = ["cisik@santafarma.com.tr","shaddeler@santafarma.com.tr"];
            $cc = "yguler@santafarma.com.tr";
        }
        else if (ENVIRONMENT == 'development'){
            $to ="shaddeler@santafarma.com.tr";
            $cc = "cisik@santafarma.com.tr";

        }

        $ci->my_email->sendEmail($subject, $message, $to, "Performans Sistemi",$cc);
    }
}

if ( ! function_exists('organizasyon_semasi'))
{
    function organizasyon_semasi($pernr, $yil, $donem, $sayac)
    {

        $ci =& get_instance();
        $ci->load->model('genel_model');
        
        $data = "";
        
        if ($sayac == 1)
        {
            $display = "";
        }
        else
        {
            $display = "none";
        }
        
        if ($sayac == 0)
        {
            $display2 = "";
        }
        else
        {
            $display2 = "none";
        }
        
        $sayac++;
        
        if (isset($pernr))
        {
            unset($kosul);
            $select = "*";
            $kosul['pernr'] = $pernr;
            $kosul['yil'] = $yil;
            $kosul['donem'] = $donem;
            $kisi = $ci->genel_model->veri_cek_ts("organizasyon_d", $select, $kosul);
            
            
            if ($kisi)
            {
                if ($kisi['yonetici_kontrol'] == 'X')
                {
                    unset($kosul);
                    $select = "*";
                    $kosul['ust_pernr'] = $pernr;
                    $kosul['yil'] = $yil;
                    $kosul['donem'] = $donem;
                    $ast = $ci->genel_model->veri_cek_cs("organizasyon_d", $select, $kosul);
                }

                // var_dump($ast);
                // exit();
                
                $degerlendiren = $kisi['org_id'];
                $pernr1 = $kisi['pernr'];
                $id = $kisi['id'];
                $guncelle_modal = '<a href="javascript:void(0)" data-toggle="modal" data-target="#kisi_guncelle_modal_'.$id.'" title="Güncelle"><i class="fa fa-fw fa-refresh"></i></a>';
                $sil_modal = '<a style="color:red" href="javascript:void(0)" data-toggle="modal" data-target="#kisi_sil_modal_'.$id.'" title="Sil"><i class="fa fa-fw fa-trash"></i></a>';
                
                $data = $data."<ul style='display:".$display."' class='".$degerlendiren."'><li><a href='javasciript:Void(0)' onclick='ac_kapa(".$degerlendiren.")'><i class='fa fa-fw fa-users'></i><b>".$kisi['org_text']."</b></a></li>";
                $data = $data."<ul style='display:".$display2."' class='".$pernr1."'><li><i class='fa fa-fw fa-diamond'></i><b>".$kisi['poz_text']."</b> - ".$kisi['ad_soyad']." ".$guncelle_modal." ".$sil_modal."</li></ul>";
                if ($ast)
                {
                   foreach ($ast as $key=>$arr)
                    {
                        if ($arr['yonetici_kontrol'] == 'X')
                        {
                            $data = $data.organizasyon_semasi($arr['pernr'], $yil, $donem, $sayac);
                            
                        }
                        else
                        {
                            $pernr1 = $arr['pernr'];
                            $id = $arr['id'];
                            $guncelle_modal = '<a href="javascript:void(0)" data-toggle="modal" data-target="#kisi_guncelle_modal_'.$id.'" title="Güncelle"><i class="fa fa-fw fa-refresh"></i></a>';
                            $sil_modal = '<a style="color:red" href="javascript:void(0)" data-toggle="modal" data-target="#kisi_sil_modal_'.$id.'" title="Sil"><i class="fa fa-fw fa-trash"></i></a>';
                            $data = $data."<ul style='display:".$display2."' class='".$pernr1."'><li><b>".$arr['poz_text']."</b> - ".$arr['ad_soyad']." ".$guncelle_modal." ".$sil_modal."</li></ul>";
                        }
                    }
                }
                $data = $data."</ul>";  
            }
        }
        

        
        return $data;
    }
}


