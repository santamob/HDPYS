<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
﻿<!DOCTYPE html>
<html lang="en">

  <head>
    
    <meta name="robots" content="noindex">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <meta name="description" content="">
    <meta name="author" content="">
    <title>Santa Farma İlaç Sanayii A.Ş.</title>

    <link href="<?=base_url()?>assets/img/favicon.ico" rel="shortcut icon" />
    <!-- Bootstrap core CSS-->
    <link href="<?=base_url()?>assets/sb-admin/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- select2 -->    
    <link href="<?=base_url()?>assets/select2/dist/css/select2.min.css" rel="stylesheet" />
    <!-- Custom fonts for this template-->
    <link href="<?=base_url()?>assets/sb-admin/vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <!-- Page level plugin CSS-->
    <link href="<?=base_url()?>assets/sb-admin/vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">
    <!-- Custom styles for this template-->
    <link href="<?=base_url()?>assets/sb-admin/css/sb-admin.css" rel="stylesheet">
    <!-- Custom styles for this template-->
    <link href="<?=base_url()?>assets/sb-admin/css/custom.css" rel="stylesheet">
    <!-- table filter -->    
    <link href="<?=base_url()?>assets/TableFilter/filtergrid.css" rel="stylesheet" />
    <link href="<?=base_url()?>assets/TableFilter/TFExt_ColsVisibility/TFExt_ColsVisibility.css" rel="stylesheet" />
    <!-- Jquery -->  
    <script src="<?=base_url()?>assets/jquery/jquery-1.10.2.js"></script>
    <!-- select2 -->
    <script src="<?=base_url()?>assets/select2/dist/js/select2.min.js"></script>
    <!-- table filter -->
    <script src="<?=base_url()?>assets/TableFilter/tablefilter_all_min.js"></script>
    <script src="<?=base_url()?>assets/TableFilter/TFExt_ColsVisibility/TFExt_ColsVisibility.js"></script>
    <!-- jquery ui -->
    <link rel="stylesheet" href="<?=base_url()?>assets/jquery/jquery-ui-1.12.1/jquery-ui.css">
    <script src="<?=base_url()?>assets/jquery/jquery-ui-1.12.1/jquery-ui.js"></script>
    
  </head>

  <body class="fixed-nav sticky-footer bg-dark" id="page-top">

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top" id="mainNav">
      <a class="navbar-brand" href="<?=base_url()?>">Performans Sistemi</a>
      <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarResponsive">
        <ul class="navbar-nav navbar-sidenav" id="exampleAccordion">
          <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Anasayfa">
            <a class="nav-link" href="<?=base_url()?>">
              <i class="fa fa-fw fa-home"></i>
              <span class="nav-link-text">
                Anasayfa</span>
            </a>
          </li>
          <?
          if ($this->session->userdata('pernr') == 101993 || $this->session->userdata('pernr') == 101081 || $this->session->userdata('pernr') == 100003)
          {
            ?>
            <li class="nav-item" data-toggle="tooltip" data-placement="right" title="IK Yönetim Paneli">
                <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#collapseExamplePages1" data-parent="#exampleAccordion">
                  <i class="fa fa-fw fa-cogs"></i>
                  <span class="nav-link-text">
                    IK Yönetim Paneli</span>
                </a>
                <ul class="sidenav-second-level collapse" id="collapseExamplePages1">
                  <li>
                    <a href="<?=base_url()?>ikyp/form_tipleri/">Form Tipleri</a>
                    <a href="<?=base_url()?>ikyp/gostergelerv2/">Göstergeler</a>
                    <a href="<?=base_url()?>ikyp/formlar/">Formlar</a>
                    <a href="<?=base_url()?>ikyp/kademelendirme_havuzlari/">Kademelendirme Havuzları</a>
                    <a href="<?=base_url()?>ikyp/donem_listesi/">Dönem Listesi ve Organizasyon Şeması</a>
                    <a href="<?=base_url()?>veri_yukle/">Veri Yükle</a>
                    <a href="<?=base_url()?>home/sdegistir_ss">Süper Şifre</a>
                   
                  </li>
                </ul>
              </li>  
            <?
          }
          ?>
          <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Değerlendirme Formları">
            <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#collapseExamplePages2" data-parent="#exampleAccordion">
              <i class="fa fa-fw fa-pencil"></i>
              <span class="nav-link-text">
                Değerlendirme Formları</span>
            </a>
            <ul class="sidenav-second-level collapse" id="collapseExamplePages2">
                <li>
                    <?
                    foreach ($donemler_header as $key=>$arr)
                    {
                        ?>
                        <a href="<?=base_url()?>d_donemleri/takvim/<?=$arr['yil']?>/<?=$arr['donem']?>"><?=$arr['yil']?> - <?=$arr['donem_ack']?></a>    
                        <?
                    }
                    ?>
                </li>
            </ul>
          </li>
          <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Kademelendirme İşlemleri">
            <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#collapseExamplePages3" data-parent="#exampleAccordion">
              <i class="fa fa-fw fa-sort-numeric-asc"></i>
              <span class="nav-link-text">
                Kademelendirme İşlemleri</span>
            </a>
            <ul class="sidenav-second-level collapse" id="collapseExamplePages3">
                <li>
                    <?
                    foreach ($donemler_header as $key=>$arr)
                    {
                        if ($arr['kademelendirme'] == 1)
                        {
                            ?>
                            <a href="<?=base_url()?>d_donemleri/kademelendirme/<?=$arr['yil']?>/<?=$arr['donem']?>"><?=$arr['yil']?> - <?=$arr['donem_ack']?></a>    
                            <?
                        }
                    }
                    ?>
                </li>
            </ul>
          </li>
        </ul>
        <ul class="navbar-nav sidenav-toggler">
          <li class="nav-item">
            <a class="nav-link text-center" id="sidenavToggler">
              <i class="fa fa-fw fa-angle-left"></i>
            </a>
          </li>
        </ul>
        <ul class="navbar-nav ml-auto">
<!--          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle mr-lg-2" href="#" id="messagesDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="fa fa-fw fa-envelope"></i>
              <span class="d-lg-none">Messages
                <span class="badge badge-pill badge-primary">12 New</span>
              </span>
              <span class="new-indicator text-primary d-none d-lg-block">
                <i class="fa fa-fw fa-circle"></i>
                <span class="number">12</span>
              </span>
            </a>
            <div class="dropdown-menu" aria-labelledby="messagesDropdown">
              <h6 class="dropdown-header">New Messages:</h6>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="#">
                <strong>David Miller</strong>
                <span class="small float-right text-muted">11:21 AM</span>
                <div class="dropdown-message small">Hey there! This new version of SB Admin is pretty awesome! These messages clip off when they reach the end of the box so they don't overflow over to the sides!</div>
              </a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="#">
                <strong>Jane Smith</strong>
                <span class="small float-right text-muted">11:21 AM</span>
                <div class="dropdown-message small">I was wondering if you could meet for an appointment at 3:00 instead of 4:00. Thanks!</div>
              </a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="#">
                <strong>John Doe</strong>
                <span class="small float-right text-muted">11:21 AM</span>
                <div class="dropdown-message small">I've sent the final files over to you for review. When you're able to sign off of them let me know and we can discuss distribution.</div>
              </a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item small" href="#">
                View all messages
              </a>
            </div>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle mr-lg-2" href="#" id="alertsDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="fa fa-fw fa-bell"></i>
              <span class="d-lg-none">Alerts
                <span class="badge badge-pill badge-warning">6 New</span>
              </span>
              <span class="new-indicator text-warning d-none d-lg-block">
                <i class="fa fa-fw fa-circle"></i>
                <span class="number">6</span>
              </span>
            </a>
            <div class="dropdown-menu" aria-labelledby="alertsDropdown">
              <h6 class="dropdown-header">New Alerts:</h6>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="#">
                <span class="text-success">
                  <strong>
                    <i class="fa fa-long-arrow-up"></i>
                    Status Update</strong>
                </span>
                <span class="small float-right text-muted">11:21 AM</span>
                <div class="dropdown-message small">This is an automated server response message. All systems are online.</div>
              </a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="#">
                <span class="text-danger">
                  <strong>
                    <i class="fa fa-long-arrow-down"></i>
                    Status Update</strong>
                </span>
                <span class="small float-right text-muted">11:21 AM</span>
                <div class="dropdown-message small">This is an automated server response message. All systems are online.</div>
              </a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="#">
                <span class="text-success">
                  <strong>
                    <i class="fa fa-long-arrow-up"></i>
                    Status Update</strong>
                </span>
                <span class="small float-right text-muted">11:21 AM</span>
                <div class="dropdown-message small">This is an automated server response message. All systems are online.</div>
              </a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item small" href="#">
                View all alerts
              </a>
            </div>
          </li>
          <li class="nav-item">
            <form class="form-inline my-2 my-lg-0 mr-lg-2">
              <div class="input-group">
                <input type="text" class="form-control" placeholder="Search for...">
                <span class="input-group-btn">
                  <button class="btn btn-primary" type="button">
                    <i class="fa fa-search"></i>
                  </button>
                </span>
              </div>
            </form>
          </li>-->
          <li class="nav-item">
             <a class="nav-link">
              <i class="fa fa-fw fa-user-circle-o"></i>
              <?=$this->session->userdata('stext')?></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" data-toggle="modal" data-target="#exampleModal">
              <i class="fa fa-fw fa-sign-out"></i>
              Çıkış Yap</a>
          </li>
        </ul>
      </div>
    </nav>
    
    <!-- Logout Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Çıkış yapıyorsunuz</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            Oturumunuzu sonlandırmak istediğinizden emin misiniz ?
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Hayır</button>
            <a class="btn btn-primary" href="<?=base_url()?>logout/">Evet</a>
          </div>
        </div>
      </div>
    </div>