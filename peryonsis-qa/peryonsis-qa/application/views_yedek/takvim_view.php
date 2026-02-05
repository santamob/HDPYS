<?=$header?>
<div class="content-wrapper">

  <div class="container-fluid">

    <!-- Breadcrumbs -->
    <ol class="breadcrumb">
      <li class="breadcrumb-item">
        <a href="<?=base_url()?>">Anasayfa</a>
      </li>
      <li class="breadcrumb-item active">Değerlendirme Dönemleri - <?=$yil?> - <?=$donem_ack?></li>
    </ol>
    
    <?
    
    if ($this->session->userdata('message'))
    {
        echo $this->session->userdata('message');
    }
    ?>
    
    <div id="alert_div">

    </div>
    
    <?
    if ($disardan_veri_al == 1)
    {
        ?>
        <!--<a href="<?=base_url()?>d_donemleri/takvim/<?=$yil?>/<?=$donem?>/ok/" class="btn btn-danger" onclick="return confirm('Bu işlem yaklaşık 1 dk. sürecektir. Devam etmek istiyor musunuz ?')" style="margin-bottom: 5px;">Astlarıma ait verileri kopyalamak için tıklayınız</a>-->
        <?
    }
    ?>
    <!--<button type="button"  id="openMyModal" class="btn btn-danger">Yeni Kriter Ekle</button>-->
   
    <div id="tabs" class="mt-3">
        <ul>
          <li><a href="#tabs-1">Kendime Ait Formlar</a></li>
          <li><a href="#tabs-2">Astlarıma Ait Formlar</a></li>
          <li><a href="#tabs-3">Astlarımın Astlarına Ait Formlar</a></li>
        </ul>
        <div id='tabs-1'>
            <div id='tabs1'>
                <?
                if (is_array($formlar))
                {
                    ?>
                    <ul>    
                    <?
                    foreach ($formlar as $key=>$arr)
                    {
                        ?>
                        <li><a href="#tabs1-<?=$key?>"><?=$ft_text[$key]?></a></li>
                        <?
                    }
                    if ($donem == 3)
                    {
                        ?>
                        <li><a href="#tabs1-s">Sonuç</a></li>    
                        <?
                    }
                    ?>
                    </ul>
                    <?
                }
                if (is_array($formlar))
                {
                    foreach ($formlar as $key=>$arr)
                    {
                        ?>
                        <div id="tabs1-<?=$key?>">
                            <div class="table-responsive">
                                <table class="table table-bordered display" id="" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Ad Soyad</th>
                                            <th>Pozisyon</th>
                                            <th>Form</th>
                                            <?
                                            if ($key == 1 || $key == 2 || $key == 3)
                                            {
                                                if ($donem == 3)
                                                {
                                                    ?>
                                                    <th>Puan</th>    
                                                    <?
                                                }
                                            }
                                            ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?
                                        unset($kisi_listesi);
                                        foreach ($arr as $key2=>$val2)
                                        {
                                            $kisi_listesi[] = $key2;
                                            ?>
                                            <tr>
                                                <td><?=$ad_soyad[$key2]?></td>
                                                <td><?=$pozisyon[$key2]?></td>
                                                <td><a href='<?=base_url()?>d_donemleri/form_goruntule/<?=$val2?>/<?=$key2?>/<?=$yil?>/<?=$donem?>/<?=$key?>' target="_blank" class='btn btn-primary' style='color: #FFF'>Form İçin Tıklayınız</a></td>
                                                <?
                                                if ($key == 1 || $key == 2 || $key == 3)
                                                {
                                                    if ($donem == 3)
                                                    {
                                                        ?>
                                                        <td>
                                                            <?
                                                            if ($puan[$key][$key2] > 0)
                                                            {
                                                                echo $puan[$key][$key2];
                                                            }
                                                            else
                                                            {
                                                                echo "Değerlendirme yapılmadı.";
                                                            }
                                                            ?>
                                                        </td>  
                                                        <?
                                                    }
                                                }
                                                ?>
                                            </tr>    
                                            <?
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <?
                    }
                    if ($donem == 3)
                    {
                        ?>
                        <div id="tabs1-s">
                            <div class="table-responsive">
                                <table class="table table-bordered display" id="" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Ad Soyad</th>
                                            <th>Pozisyon</th>
                                            <th>Hedef Puanı</th>
                                            <th>Yetkinlik Puanı</th>
                                            <th>Toplam Puan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?
                                        foreach ($kisi_listesi as $key=>$val)
                                        {
                                            ?>
                                            <tr>
                                                <td><?=$ad_soyad[$val]?></td>
                                                <td><?=$pozisyon[$val]?></td>
                                                <td><?=$puan[1][$val]?></td>
                                                <td><?=$puan[2][$val]?></td>
                                                <td>
                                                    <?
                                                    echo $puan[1][$val]+$puan[2][$val];
                                                    ?>
                                                </td>
                                            </tr>    
                                            <?
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>    
                        <?
                    }
                }
                else
                {
                    ?>
                    <span>Değerlendirmeniz gereken form bulunmuyor.</span>    
                    <?
                }
                ?>
            </div>
        </div>
        <div id='tabs-2'>
            <div id='tabs2'>
                <?
                $row_span = 0;
                if (is_array($formlar_ast))
                {
                    $row_span++;
                    ?>
                    <ul>    
                    <?
                    foreach ($formlar_ast as $key=>$arr)
                    {
                        ?>
                        <li><a href="#tabs2-<?=$key?>"><?=$ft_text[$key]?></a></li>
                        <?
                    }
                    if ($donem == 3)
                    {
                        ?>
                        <li><a href="#tabs2-s">Sonuç</a></li>
                        <?
                    }
                    ?>
                    </ul>
                    <?
                }
                if (is_array($formlar_ast))
                {
                    foreach ($formlar_ast as $key=>$arr)
                    {
                        ?>
                        <div id='tabs2-<?=$key?>'>
                            <h3>
                                Değerlendirme İşlemleri
                            </h3>
                            <div class="table-responsive">
                                <table class="table table-bordered display" id="dataTable_<?=$key?>" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Ad Soyad</th>
                                            <th>Pozisyon</th>
                                            <th>Form</th>
                                            <?
                                            if ($key == 1 || $key == 2 || $key == 3)
                                            {
                                                if ($donem == 3)
                                                {
                                                    ?>
                                                    <th>Puan</th>    
                                                    <?
                                                }
                                            }
                                            ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?
                                        $sayac = 0;
                                        unset($kisi_listesi);
                                        foreach ($arr as $key2=>$val2)
                                        {
                                            $kisi_listesi[] = $key2;
                                            $sayac++;
                                            ?>
                                            <tr>
                                                <td><?=$ad_soyad[$key2]?></td>
                                                <td><?=$pozisyon[$key2]?></td>
                                                <td><a href='<?=base_url()?>d_donemleri/form_goruntule/<?=$val2?>/<?=$key2?>/<?=$yil?>/<?=$donem?>/<?=$key?>' target="_blank" class='btn btn-primary' style='color: #FFF'>Form İçin Tıklayınız</a></td>
                                                <?
                                                if ($key == 1 || $key == 2 || $key == 3)
                                                {
                                                    if ($donem == 3)
                                                    {
                                                        ?>
                                                        <td>
                                                            <?
                                                            if ($puan[$key][$key2] > 0)
                                                            {
                                                                echo $puan[$key][$key2];
                                                            }
                                                            else
                                                            {
                                                                echo "Değerlendirme yapılmadı.";
                                                            }
                                                            ?>
                                                        </td>  
                                                        <?
                                                    }
                                                }
                                                ?>
                                            </tr>    
                                            <?
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                            <div>
                                <h3>
                                    Onay İşlemleri
                                </h3>
                                <table class="table table-bordered display" id="dataTable_<?=$key?>" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Değerlendiren</th>
                                            <th>Onaylayan</th>
                                            <th>Durum</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?
                                        if ($onaya_gonder[$key])
                                        {
                                            ?>
                                            <tr>
                                                <td><?=$ad_soyad[$this->session->userdata('pernr')]?></td>
                                                <td><?=$ust_ad_soyad[$this->session->userdata('pernr')]?></td>
                                                <td>
                                                    <?
                                                    if ($od_ast_kontrol[$key] == 2)
                                                    {
                                                        echo "Onaya gönderebilmeniz için tüm değerlendirmeleri tamamlamalısınız.";
                                                    }
                                                    else if ($od_ast_kontrol[$key] == 3)
                                                    {
                                                        echo "Onaya gönderebilmeniz için öncesinde astlarınızın formlarına onay vermelisiniz.";
                                                    }
                                                    else
                                                    {
                                                        echo $od_ast[$key]['onay_text'];
                                                        if ($od_ast[$key]['onay_durum'] == 2 || $od_ast[$key]['onay_durum'] == 3)
                                                        {
                                                            ?>
                                                            <form method="POST" action="<?=base_url()?>d_donemleri/onay_islemleri">
                                                                <input type="hidden" name="yil" value="<?=$yil?>">
                                                                <input type="hidden" name="donem" value="<?=$donem?>">
                                                                <input type="hidden" name="ft_id" value="<?=$key?>">
                                                                <input type="hidden" name="onay_durum" value="4">
                                                                <input type="hidden" name="degerlendiren" value="<?=$this->my_encrypt_url->encode($this->session->userdata('pernr'))?>" />
                                                                <input type="hidden" name="onaylayan" value="<?=$this->my_encrypt_url->encode($ust_pernr[$this->session->userdata('pernr')])?>" />
                                                                <input type="submit" class="btn btn-primary" value="Onaya Gönder" />
                                                            </form>    
                                                            <?
                                                        }
                                                        else if ($od_ast[$key]['onay_durum'] == 4)
                                                        {
                                                            ?>
                                                            <form method="POST" action="<?=base_url()?>d_donemleri/onay_islemleri">
                                                                <input type="hidden" name="yil" value="<?=$yil?>">
                                                                <input type="hidden" name="donem" value="<?=$donem?>">
                                                                <input type="hidden" name="ft_id" value="<?=$key?>">
                                                                <input type="hidden" name="onay_durum" value="3">
                                                                <input type="hidden" name="degerlendiren" value="<?=$this->my_encrypt_url->encode($this->session->userdata('pernr'))?>" />
                                                                <input type="hidden" name="onaylayan" value="<?=$this->my_encrypt_url->encode($ust_pernr[$this->session->userdata('pernr')])?>" />
                                                                <input type="submit" class="btn btn-warning" value="Geri Al" />
                                                            </form>    
                                                            <?
                                                        }
                                                    }
                                                    ?>
                                                </td>
                                            </tr>    
                                            <?
                                        }
                                        if ($k_gonder[$key])
                                        {
                                            ?>
                                            <tr>
                                                <td><?=$ad_soyad[$this->session->userdata('pernr')]?></td>
                                                <td><?=$ad_soyad[$this->session->userdata('pernr')]?></td>
                                                <td>
                                                    <?
                                                    if ($k_ast_kontrol[$key] == 2)
                                                    {
                                                        echo "Kademelendirmeye gönderebilmeniz için tüm değerlendirmeleri tamamlamalısınız.";
                                                    }
                                                    else if ($k_ast_kontrol[$key] == 3)
                                                    {
                                                        echo "Kademelendirmeye gönderebilmeniz için öncesinde astlarınızın formlarına onay vermelisiniz.";
                                                    }
                                                    else
                                                    {
                                                        if ($k_ast[$key]['onay_durum'] == 1)
                                                        {
                                                            echo "Kademelendirmeye gönderildi";
                                                            //Kademelendirme başlamış mı ?
                                                            if ($k_ast[$key]['k_kontrol'])
                                                            {
                                                                ?>
                                                                <form method="POST" action="<?=base_url()?>d_donemleri/onay_islemleri">
                                                                    <input type="hidden" name="yil" value="<?=$yil?>">
                                                                    <input type="hidden" name="donem" value="<?=$donem?>">
                                                                    <input type="hidden" name="ft_id" value="<?=$key?>">
                                                                    <input type="hidden" name="onay_durum" value="3">
                                                                    <input type="hidden" name="degerlendiren" value="<?=$this->my_encrypt_url->encode($this->session->userdata('pernr'))?>" />
                                                                    <input type="hidden" name="onaylayan" value="<?=$this->my_encrypt_url->encode($this->session->userdata('pernr'))?>" />
                                                                    <input type="submit" class="btn btn-primary" value="Geri Al" />
                                                                </form> 
                                                                <?
                                                            }
                                                        }
                                                        else if ($k_ast[$key]['onay_durum'] == 3)
                                                        {
                                                            echo "Kademelendirmeye gönderilmesi bekleniyor";
                                                            ?>
                                                            <form method="POST" action="<?=base_url()?>d_donemleri/onay_islemleri">
                                                                <input type="hidden" name="yil" value="<?=$yil?>">
                                                                <input type="hidden" name="donem" value="<?=$donem?>">
                                                                <input type="hidden" name="ft_id" value="<?=$key?>">
                                                                <input type="hidden" name="onay_durum" value="1">
                                                                <input type="hidden" name="degerlendiren" value="<?=$this->my_encrypt_url->encode($this->session->userdata('pernr'))?>" />
                                                                <input type="hidden" name="onaylayan" value="<?=$this->my_encrypt_url->encode($this->session->userdata('pernr'))?>" />
                                                                <input type="submit" class="btn btn-primary" value="Kademelendirmeye Gönder" />
                                                            </form>    
                                                            <?
                                                        }
                                                        else
                                                        {
                                                            echo "Kademelendirmeye gönderilmesi bekleniyor";
                                                        }
                                                    }
                                                    ?>
                                                </td>
                                            </tr>    
                                            <?
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <?
                    }
                    if ($donem == 3)
                    {
                        ?>
                        <div id="tabs2-s">
                            <div class="table-responsive">
                                <table class="table table-bordered display" id="" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Ad Soyad</th>
                                            <th>Pozisyon</th>
                                            <th>Hedef Puanı</th>
                                            <th>Yetkinlik Puanı</th>
                                            <th>Toplam Puan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?
                                        foreach ($kisi_listesi as $key=>$val)
                                        {
                                            ?>
                                            <tr>
                                                <td><?=$ad_soyad[$val]?></td>
                                                <td><?=$pozisyon[$val]?></td>
                                                <td><?=$puan[1][$val]?></td>
                                                <td><?=$puan[2][$val]?></td>
                                                <td>
                                                    <?
                                                    echo $puan[1][$val]+$puan[2][$val];
                                                    ?>
                                                </td>
                                            </tr>    
                                            <?
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>     
                        <?
                    }
                }
                else
                {
                    ?>
                    <span>Değerlendirmeniz gereken form bulunmuyor.</span>    
                    <?
                }
                ?>
            </div>
        </div>
        <div id='tabs-3'>
            <div id='tabs3'>
                <?
                if (is_array($formlar_ast2))
                {
                    ?>
                    <ul>    
                    <?
                    foreach ($formlar_ast2 as $key=>$arr)
                    {
                        ?>
                        <li><a href="#tabs3-<?=$key?>"><?=$ft_text[$key]?></a></li>
                        <?
                    }
                    if ($donem == 3)
                    {
                        ?>
                        <li><a href="#tabs3-s">Sonuç</a></li>
                        <?
                    }
                    ?>
                    </ul>
                    <?
                }
                if (is_array($formlar_ast2))
                {
                    foreach ($formlar_ast2 as $key=>$arr)
                    {
                        ?>
                        <div id='tabs3-<?=$key?>'>
                            <h3>
                                Değerlendirme İşlemleri
                            </h3>
                            <div class="table-responsive">
                                <table class="table table-bordered display" id="dataTable_<?=$key?>" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Yönetici</th>
                                            <th>Ad Soyad</th>
                                            <th>Pozisyon</th>
                                            <th>Form</th>
                                            <?
                                            if ($key == 1 || $key == 2 || $key == 3)
                                            {
                                                if ($donem == 3)
                                                {
                                                    ?>
                                                    <th>Puan</th>    
                                                    <?
                                                }
                                            }
                                            ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?
                                        unset($kisi_listesi);
                                        foreach ($arr as $key2=>$val2)
                                        {
                                            $kisi_listesi[] = $key2;
                                            ?>
                                            <tr>
                                                <td><?=$ust_ad_soyad[$key2]?></td>
                                                <td><?=$ad_soyad[$key2]?></td>
                                                <td><?=$pozisyon[$key2]?></td>
                                                <td><a href='<?=base_url()?>d_donemleri/form_goruntule/<?=$val2?>/<?=$key2?>/<?=$yil?>/<?=$donem?>/<?=$key?>' target="_blank" class='btn btn-primary' style='color: #FFF'>Form İçin Tıklayınız</a></td>
                                                <?
                                                if ($key == 1 || $key == 2 || $key == 3)
                                                {
                                                    if ($donem == 3)
                                                    {
                                                        ?>
                                                        <td>
                                                            <?
                                                            if ($puan[$key][$key2] > 0)
                                                            {
                                                                echo $puan[$key][$key2];
                                                            }
                                                            else
                                                            {
                                                                echo "Değerlendirme yapılmadı.";
                                                            }
                                                            ?>
                                                        </td>  
                                                        <?
                                                    }
                                                }
                                                ?>
                                            </tr>    
                                            <?
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                            <div>
                                <h3>
                                    Onay İşlemleri
                                </h3>
                                <table class="table table-bordered display" id="dataTable_<?=$key?>" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Değerlendiren</th>
                                            <th>Onaylayan</th>
                                            <th>Durum</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?
                                        foreach ($arr as $key2=>$val2)
                                        {
                                            if (!in_array($ust_pernr[$key2], $key_dizisi[$key]))
                                            {
                                                ?>
                                                <tr>
                                                    <td><?=$ust_ad_soyad[$key2]?></td>
                                                    <td><?=$ad_soyad[$this->session->userdata('pernr')]?></td>
                                                    <td>
                                                        <div>
                                                            <?=$od_ast2[$key][$ust_pernr[$key2]]['onay_text']?>
                                                        </div>
                                                        <?
                                                        if ($od_ast2[$key][$ust_pernr[$key2]]['onay_durum'] == 4)
                                                        {
                                                            ?>
                                                            <form method="POST" action="<?=base_url()?>d_donemleri/onay_islemleri" style="float: left">
                                                                <input type="hidden" name="yil" value="<?=$yil?>">
                                                                <input type="hidden" name="donem" value="<?=$donem?>">
                                                                <input type="hidden" name="ft_id" value="<?=$key?>">
                                                                <input type="hidden" name="onay_durum" value="1">
                                                                <input type="hidden" name="degerlendiren" value="<?=$this->my_encrypt_url->encode($ust_pernr[$key2])?>" />
                                                                <input type="hidden" name="onaylayan" value="<?=$this->my_encrypt_url->encode($this->session->userdata('pernr'))?>" />
                                                                <input type="submit" class="btn btn-primary" value="Onayla" />
                                                            </form>   
                                                            <form method="POST" action="<?=base_url()?>d_donemleri/onay_islemleri" style="float: left">
                                                                <input type="hidden" name="yil" value="<?=$yil?>">
                                                                <input type="hidden" name="donem" value="<?=$donem?>">
                                                                <input type="hidden" name="ft_id" value="<?=$key?>">
                                                                <input type="hidden" name="onay_durum" value="2">
                                                                <input type="hidden" name="degerlendiren" value="<?=$this->my_encrypt_url->encode($ust_pernr[$key2])?>" />
                                                                <input type="hidden" name="onaylayan" value="<?=$this->my_encrypt_url->encode($this->session->userdata('pernr'))?>" />
                                                                <input type="submit" class="btn btn-danger" value="Red Et" />
                                                            </form> 
                                                            <?
                                                        }
                                                        else if ($od_ast2[$key][$ust_pernr[$key2]]['onay_durum'] == 1)
                                                        {
                                                            // Kendi astlarını geri alabiliyorsa
                                                            if (
                                                                ($onaya_gonder[$key] && $od_ast[$key]['onay_durum'] == 3) 
                                                                || ($k_gonder[$key] && $k_ast[$key]['onay_durum'] == 3))
                                                            {
                                                                ?>
                                                                <form method="POST" action="<?=base_url()?>d_donemleri/onay_islemleri" style="float: left">
                                                                    <input type="hidden" name="yil" value="<?=$yil?>">
                                                                    <input type="hidden" name="donem" value="<?=$donem?>">
                                                                    <input type="hidden" name="ft_id" value="<?=$key?>">
                                                                    <input type="hidden" name="onay_durum" value="3">
                                                                    <input type="hidden" name="degerlendiren" value="<?=$this->my_encrypt_url->encode($ust_pernr[$key2])?>" />
                                                                    <input type="hidden" name="onaylayan" value="<?=$this->my_encrypt_url->encode($this->session->userdata('pernr'))?>" />
                                                                    <input type="submit" class="btn btn-warning" value="Onayı Geri Al" />
                                                                </form>  
                                                                <?
                                                            }
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>    
                                                <?
                                            }
                                            $key_dizisi[$key][] = $ust_pernr[$key2];
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <?
                    }
                    if ($donem == 3)
                    {
                        ?>
                        <div id="tabs3-s">
                            <div class="table-responsive">
                                <table class="table table-bordered display" id="" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Yönetici</th>
                                            <th>Ad Soyad</th>
                                            <th>Pozisyon</th>
                                            <th>Hedef Puanı</th>
                                            <th>Yetkinlik Puanı</th>
                                            <th>Toplam Puan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?
                                        foreach ($kisi_listesi as $key=>$val)
                                        {
                                            ?>
                                            <tr>
                                                <td><?=$ust_ad_soyad[$val]?></td>
                                                <td><?=$ad_soyad[$val]?></td>
                                                <td><?=$pozisyon[$val]?></td>
                                                <td><?=number_format($puan[1][$val],2,',','')?></td>
                                                <td><?=number_format($puan[2][$val],2,',','')?></td>
                                                <td>
                                                    <?
                                                    $tp = $puan[1][$val]+$puan[2][$val];
                                                    echo number_format($tp,2,',','');
                                                    ?>
                                                </td>
                                            </tr>    
                                            <?
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>     
                        <?
                    }
                }
                else
                {
                    ?>
                    <span>Değerlendirmeniz gereken form bulunmuyor.</span>    
                    <?
                }
                ?>
            </div>
        </div>
    </div>

    <div class="modal fade" id="kriter_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Yeni Kriter Ekle</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
                <div class="form-group">
                    <label for="recipient-name" class="col-form-label">Recipient:</label>
                    <input type="text" class="form-control" id="recipient-name">
                </div>
                <div class="form-group">
                    <!--<select class="js-example-basic-single"  name="test" style="width:250px">-->
                    <select class="js-example-tags" multiple   data-select2-tag="true"  name="criters[]" style="width:450px">

                    </select>
                </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" id="addNewCrit" class="btn btn-primary">Kaydet</button>
            </div>
            </div>
        </div>
    </div>

<script language="javascript" type="text/javascript">  
    setTimeout(function(){location.href="<?=base_url()?>d_donemleri/takvim/<?=$yil?>/<?=$donem?>"} , 120000);
    jQuery(function($) {
        var index = 'qpsstats-active-tab0';
        //  Define friendly data store name
        var dataStore = window.sessionStorage;
        var oldIndex = 0;
        //  Start magic!
        try {
            // getter: Fetch previous value
            oldIndex = dataStore.getItem(index);
        } catch(e) {}
        $( "#tabs" ).tabs({
            active: oldIndex,
            activate: function(event, ui) {
                //  Get future value
                var newIndex = ui.newTab.parent().children().index(ui.newTab);
                //  Set future value
                try {
                    dataStore.setItem( index, newIndex );
                } catch(e) {}
            }
        });
        
    });
    
    jQuery(function($) {
        var index = 'qpsstats-active-tab1';
        //  Define friendly data store name
        var dataStore = window.sessionStorage;
        var oldIndex = 0;
        //  Start magic!
        try {
            // getter: Fetch previous value
            oldIndex = dataStore.getItem(index);
        } catch(e) {}
        $( "#tabs1" ).tabs({
            active: oldIndex,
            activate: function(event, ui) {
                //  Get future value
                var newIndex = ui.newTab.parent().children().index(ui.newTab);
                //  Set future value
                try {
                    dataStore.setItem( index, newIndex );
                } catch(e) {}
            }
        });
        
    });
    
    jQuery(function($) {
        var index = 'qpsstats-active-tab2';
        //  Define friendly data store name
        var dataStore = window.sessionStorage;
        var oldIndex = 0;
        //  Start magic!
        try {
            // getter: Fetch previous value
            oldIndex = dataStore.getItem(index);
        } catch(e) {}
        $( "#tabs2" ).tabs({
            active: oldIndex,
            activate: function(event, ui) {
                //  Get future value
                var newIndex = ui.newTab.parent().children().index(ui.newTab);
                //  Set future value
                try {
                    dataStore.setItem( index, newIndex );
                } catch(e) {}
            }
        });
        
    });
    
    jQuery(function($) {
        var index = 'qpsstats-active-tab3';
        //  Define friendly data store name
        var dataStore = window.sessionStorage;
        var oldIndex = 0;
        //  Start magic!
        try {
            // getter: Fetch previous value
            oldIndex = dataStore.getItem(index);
        } catch(e) {}
        $( "#tabs3" ).tabs({
            active: oldIndex,
            activate: function(event, ui) {
                //  Get future value
                var newIndex = ui.newTab.parent().children().index(ui.newTab);
                //  Set future value
                try {
                    dataStore.setItem( index, newIndex );
                } catch(e) {}
            }
        });
        
    });


       

    // set_select2_liste(2021);
    // function set_select2_liste(donem) {
    
    //     $(".js-example-basic-single").empty().trigger('change')


    //     var query2 = {"donem": donem};

    //         jQuery.ajax({
    //             type: "post",
    //             url: '/d_donemleri/getSelectData',
    //             data: query2,
    //             dataType: 'json',
    //             success: function (data) {

                
    //                 jQuery.each(data, function (i, d) {
    //                     var data = {
    //                         id: d.id,
    //                         text: d.name
    //                     };

    //                     var newOption = new Option(data.text, data.id, false, false);
    //                     jQuery('.js-example-basic-single').append(newOption).trigger('change');


    //                 });
    //             },
    //     });





    // }

   
    

  
    
    $(document).ready(function() {
       
       
        $('table.display').DataTable({
            "paging": false, 
            "searching": false,
            "bInfo" : false,
            "oLanguage": {
                "sDecimal":        ",",
                "sEmptyTable":     "Tabloda herhangi bir veri mevcut değil",
                "sInfo":           "_TOTAL_ kayıt gösteriliyor",
                "sInfoEmpty":      "Kayıt yok",
                "sInfoFiltered":   "",
                "sInfoPostFix":    "",
                "sInfoThousands":  ".",
                "sLengthMenu":     "Sayfada _MENU_ kayıt göster",
                "sLoadingRecords": "Yükleniyor...",
                "sProcessing":     "İşleniyor...",
                "sSearch":         "Ara:",
                "sZeroRecords":    "Eşleşen kayıt bulunamadı",
                "oPaginate": {
                    "sFirst":    "İlk",
                    "sLast":     "Son",
                    "sNext":     "Sonraki",
                    "sPrevious": "Önceki"
                },
                "oAria": {
                    "sSortAscending":  ": artan sütun sıralamasını aktifleştir",
                    "sSortDescending": ": azalan sütun sıralamasını aktifleştir"
                }
            }
        });
    } );
</script>

<?=$footer?>