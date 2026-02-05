<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
﻿<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta name="robots" content="noindex">
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta content="IE=edge" http-equiv=X-UA-Compatible>
    <title>Performans Değerlendirme Sistemi</title>
    <link href="<?=base_url()?>assets/img/favicon.ico" rel="shortcut icon" />
	<!-- BOOTSTRAP STYLES-->
    <link href="<?=base_url()?>assets/bootstrap/css/bootstrap.css" rel="stylesheet" />
        <!-- CUSTOM STYLES-->
    <!-- GOOGLE FONTS-->
   <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
</head>
<body style="background-color: #343a40; font-family: 'Open Sans', sans-serif;">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-4 col-lg-offset-4 col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
                
                <div style="margin-top: 10%;">
                    <div class="panel panel-default">
                        
                        <div class="panel-heading">
                            <a href="<?=base_url()?>"><img src="<?=base_url()?>assets/img/logo_y.png" class="img-responsive" style="margin: auto"></a>
                            <div style="background-color: #343a40; color: #FFF; text-align: center; font-weight: bold">PERFORMANS DEĞERLENDİRME SİSTEMİ</div>
                        </div>
                        
                        <div class="panel-body">
                            
                            <?php
                            if ($this->session->userdata('message'))
                            {
                                echo $this->session->userdata('message');
                            }
                            ?>
                            <form method="POST" action="<?=base_url()?>login/auth/" id="login">
                                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                                <div class="form-group">
                                    <label>Eposta Adresi veya Personel Numarası</label>
                                    <input type="text" class="form-control" name="username" id="username" placeholder="Eposta Adresi veya Personel Numarası">
                                </div>
                                <div class="form-group">
                                    <label>Şifre</label>
                                    <input type="password" class="form-control" name="pass" id="pass" placeholder="Şifre">
                                </div>
                                <button type="submit" class="btn btn-primary pull-right"><span class="glyphicon glyphicon-log-in" aria-hidden="true"></span> Giriş</button>
                                <a href="javascript:Void(0)" class="btn btn-danger pull-right" style="margin-right: 8px;" onclick="jQuery('#login').hide(); jQuery('#new_password').show()"><span class="glyphicon" aria-hidden="true"></span> Yeni Şifre Al / Şifremi Unuttum</a>
                            </form>
                            
                            <form method="POST" action="<?=base_url()?>login/new_password/" id="new_password" style="display: none">
                                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                                <div class="form-group">
                                    <label>Eposta Adresi</label>
                                    <input type="text" class="form-control" name="username" id="username" placeholder="Eposta Adresi">
                                </div>
                                <button type="submit" class="btn btn-primary pull-right"><span class="glyphicon" aria-hidden="true"></span> Gönder</button>
                                <a href="javascript:Void(0)" class="btn btn-danger pull-right" style="margin-right: 8px;" onclick="jQuery('#new_password').hide(); jQuery('#perno').show(); "><span class="glyphicon" aria-hidden="true"></span> Eposta Adresim Yok</a>
                            </form>
                            
                            <form method="POST" action="<?=base_url()?>login/new_password_pernr/" id="perno" style="display: none">
                                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                                <div class="form-group">
                                    <label>Personel Numarası</label>
                                    <input type="text" class="form-control" name="pernr" id="pernr" placeholder="Personel Numarası">
                                </div>
                                <button type="submit" class="btn btn-primary pull-right"><span class="glyphicon" aria-hidden="true"></span> Gönder</button>
                            </form>
                            
                        </div>
                        
                        <div class='panel-footer' style="text-align: center">
                                <img src="<?=base_url()?>assets/img/devices.png" width="75">
                                ©2017 Santa Farma İlaç Sanayii A.Ş. - TİM 
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    
    <!-- SCRIPTS -AT THE BOTOM TO REDUCE THE LOAD TIME-->
    <!-- JQUERY SCRIPTS -->
    <script src="<?=base_url()?>assets/jquery/jquery-1.10.2.js"></script>
      <!-- BOOTSTRAP SCRIPTS -->
    <script src="<?=base_url()?>assets/bootstrap/js/bootstrap.min.js"></script>
    
</body>
</html>
