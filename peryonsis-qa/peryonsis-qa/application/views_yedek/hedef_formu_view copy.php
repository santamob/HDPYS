<?=$header2?>
<style src='text/css'>
    table {
        width: 100%;
    }
    td {
        vertical-align: middle;
        border: solid 1px #e1edf7;
    }
    .bg_sf_k {
        background-color: #ED1C24;
        color: #FFF;
    }
    .bg_baslik {
        background-color: #e1e1e1;
    }
</style>
<div class="">
 <?php // Kullanıcı lokasyonunu bulmak
 $veriListeleme =  new Genel_model();
      $tabloAdi = $veriListeleme->perskGetir($this->session->userdata['pernr']);
      if($tabloAdi['0']){$perSk= $tabloAdi['0']['persk'];}else{$perSk=  $tabloAdi['persk'];}
      unset($kosul);
      $select = '*';
        $kosul['yil'] = $yil;
        $kosul['donem'] = $donem;
        $tabloAdi = 'donem_listesi';
      $lokasyonTablo = $veriListeleme->lokasyonGetir($tabloAdi, $select, $kosul);
      $lokasyon = explode('-',$lokasyonTablo[0]['lokasyon']);
        $lokasyon = array_filter($lokasyon);

        if ($perSk == 1 || $perSk == 4)
    {
        $office = "Merkez";
    }
    else if ($perSk == 2 || $perSk == 5)
    {
        $office = "Fabrika";
    }
    else if ($perSk == 3 || $perSk == 6)
    {
        $office = "Saha";
    }
      ?>
  <div class="container-fluid">
    
    <?
    if ($this->session->userdata('message'))
    {
        echo $this->session->userdata('message');
    }
    ?>
    
    <div id="alert_div">
        
    </div>
    
    <table class="table table-bordered">
        <tr>
            <th>Değerlendirilen</th>
            <td><?=$rilen['ad_soyad']?></td>
        </tr>
        <tr>
            <th>Donem</th>
            <td><?=$yil?> - <?=$donem_ack?></td>
        </tr>
        <?
        if ($donem == 3)
        {
            ?>
            <tr>
                <th>Puan</th>
                <td>
                    <?
                    if ($puan > 0)
                    {
                        echo $puan;
                    }
                    else
                    {
                        echo "Değerlendirme yapılmadı";
                    }
                    ?>
                </td>
            </tr>    
            <?
        }
        ?>
    </table>
      
    <form method="POST" action="<?=base_url()?>d_donemleri/form_kaydet/" >
        <table class='margin_bot'>
            <tr>
                <td colspan="8" class='text-center bg_sf_k'>
                    <h3><?=$form_baslik['ft_text']?></h3>
                </td>
            </tr>
            <? foreach ($gostergeler as $key=>$arr)
            {?>
                <tr class='bg_baslik'>
                    <td><?=$arr['g_text']?></td>
                    <td>Periyot</td>
                    <td><?=$arr['planlanan_text']?></td>
                    <td><?=$arr['gerceklesen_text']?></td>
                    <td><?=$arr['sonuc_text']?></td>
                    <td>Değerlendirilenin<br/> Açıklaması</td>
                    <td>Ağırlık<br> <?=$arr['agirlik']?></td>
                    <td>
                        <table class=''>
                            <tr>
                                <td colspan='4' class='text-center'>Değerlendirme Düzeyleri</td>
                            </tr>
                            <tr>
                                <td style='width: 25%'><?=$arr['k1_text']?></td>
                                <td style='width: 25%'><?=$arr['k2_text']?></td>
                                <td style='width: 25%'><?=$arr['k3_text']?></td>
                                <td style='width: 25%'><?=$arr['k4_text']?></td>
                            </tr>
                            <tr>
                                <?
                                $arr['k1_as'] = number_format($arr['k1_as'],2,',','');
                                $arr['k1_us'] = number_format($arr['k1_us'],2,',','');
                                $arr['k2_as'] = number_format($arr['k2_as'],2,',','');
                                $arr['k2_us'] = number_format($arr['k2_us'],2,',','');
                                $arr['k3_as'] = number_format($arr['k3_as'],2,',','');
                                $arr['k3_us'] = number_format($arr['k3_us'],2,',','');
                                $arr['k4_as'] = number_format($arr['k4_as'],2,',','');
                                $arr['k4_us'] = number_format($arr['k4_us'],2,',','');
                                ?>
                                <td><?=($arr['veri_kaynagi'] == 4)?'':'%'?><?=$arr['k1_as']?> - <?=($arr['veri_kaynagi'] == 4)?'':'%'?><?=$arr['k1_us']?></td>
                                <td><?=($arr['veri_kaynagi'] == 4)?'':'%'?><?=$arr['k2_as']?> - <?=($arr['veri_kaynagi'] == 4)?'':'%'?><?=$arr['k2_us']?></td>
                                <td><?=($arr['veri_kaynagi'] == 4)?'':'%'?><?=$arr['k3_as']?> - <?=($arr['veri_kaynagi'] == 4)?'':'%'?><?=$arr['k3_us']?></td>
                                <td><?=($arr['veri_kaynagi'] == 4)?'':'%'?><?=$arr['k4_as']?> - <?=($arr['veri_kaynagi'] == 4)?'':'%'?><?=$arr['k4_us']?></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td><?=$arr['g_desc']?></td>
                    <td>
                        <?
                        for ($i = 1; $i <= $arr['periyod']; $i++)
                        {
                            if (is_array($per_urun[$arr['g_id']][$i]))
                            {
                                foreach ($per_urun[$arr['g_id']][$i] as $key1=>$val1)
                                {
                                    ?>
                                    <input style="width: 200px;" type='text' class='form-control form-control-sm' value='<?=$i?>. Periyot - <?=$val1?>' disabled/>    
                                    <?
                                }
                            }
                            else
                            {
                                ?>
                                <input style="width: 200px;" type='text' class='form-control form-control-sm' value='<?=$i?>. Periyot' disabled/>    
                                <?
                            }
                        }
                        ?>
                    </td>
                    <td>
                        <?
                        for ($i = 1; $i <= $arr['periyod']; $i++)
                        {
                            
                            $display_per = "";  
                            if ($donem == 2)
                            {
                                $periyod_hesap = 12/$arr['periyod']*$i;
                                if ($periyod_hesap > 6) // Yarı yıl
                                {
                                    $display_per = "disabled";
                                }
                            }
                            else if ($donem == 3)
                            {
                                $periyod_hesap = 12/$arr['periyod']*$i;
                                if ($periyod_hesap <= 6) // Yılsonu
                                {
                                    $display_per = "disabled";
                                }
                            }
                            
                            if (!$degisiklik)
                            {
                                $display_per = "disabled";
                            }
                            if(sizeof($lokasyon)==0 || !in_array($office,$lokasyon))
                            {
                                $display_per = "disabled";  
                            }
                            if ($arr['veri_kaynagi'] == 1) // veri oto gelecekse
                            {
                                for ($i = 1; $i <= $arr['periyod']; $i++)
                                {
                                    if (is_array($per_urun[$arr['g_id']][$i]))
                                    {
                                        foreach ($per_urun[$arr['g_id']][$i] as $key1=>$val1)
                                        {
                                            ?>
                                            <input type='text' class='form-control form-control-sm' value="<?=$gp[$arr['g_id']][$i][$val1]?>" placeholder="Planlanan" disabled/>    
                                            <?
                                        }
                                    }
                                    else
                                    {
                                        ?>
                                        <input type='text' class='form-control form-control-sm' value="<?=$gp[$arr['g_id']][$i]?>" placeholder="Planlanan" disabled/>    
                                        <?
                                    }
                                }
                            }
                            else if ($arr['veri_kaynagi'] == 2) // Manuel
                            {
                                if ($who == 'rilen')
                                {
                                    ?>
                                    <input type='text' class='form-control form-control-sm' name="gp[<?=$arr['g_id']?>][<?=$i?>]" value="<?=$gp[$arr['g_id']][$i]?>" placeholder="Planlanan" <?=$display_per?>/>    
                                    <?
                                }
                                else
                                {
                                    ?>
                                    <input type='text' class='form-control form-control-sm' value="<?=$gp[$arr['g_id']][$i]?>" placeholder="Planlanan" disabled/>    
                                    <?
                                }
                            }
                        }
                        ?>
                    </td>
                    <td>
                        <?
                        for ($i = 1; $i <= $arr['periyod']; $i++)
                        {
                            $display_per = "";  
                            if ($donem == 2)
                            {
                                $periyod_hesap = 12/$arr['periyod']*$i;
                                if ($periyod_hesap > 6) // Yarı yıl
                                {
                                    $display_per = "disabled";
                                }
                            }
                            else if ($donem == 3)
                            {
                                $periyod_hesap = 12/$arr['periyod']*$i;
                                if ($periyod_hesap <= 6) // Yılsonu
                                {
                                    $display_per = "disabled";
                                }
                            }
                            
                            if (!$degisiklik)
                            {
                                $display_per = "disabled";
                            }
                            if(sizeof($lokasyon)==0 || !in_array($office,$lokasyon))
                            {
                                $display_per = "disabled";  
                            }
                            if ($arr['veri_kaynagi'] == 1) // veri oto gelecekse
                            {
                                for ($i = 1; $i <= $arr['periyod']; $i++)
                                {
                                    if (is_array($per_urun[$arr['g_id']][$i]))
                                    {
                                        foreach ($per_urun[$arr['g_id']][$i] as $key1=>$val1)
                                        {
                                            ?>
                                            <input type='text' class='form-control form-control-sm' value="<?=$gg[$arr['g_id']][$i][$val1]?>" placeholder="Gerçekleşen" disabled/>    
                                            <?
                                        }
                                    }
                                    else
                                    {
                                        ?>
                                        <input type='text' class='form-control form-control-sm' value="<?=$gg[$arr['g_id']][$i]?>" placeholder="Gerçekleşen" disabled/>    
                                        <?
                                    }
                                }
                            }
                            else if ($arr['veri_kaynagi'] == 2) // Manuel
                            {
                                if ($who == 'rilen')
                                {
                                    ?>
                                    <input type='text' class='form-control form-control-sm' name="gg[<?=$arr['g_id']?>][<?=$i?>]" value="<?=$gg[$arr['g_id']][$i]?>" placeholder="Gerçekleşen" <?=$display_per?>/>    
                                    <?
                                }
                                else
                                {
                                    ?>
                                    <input type='text' class='form-control form-control-sm' value="<?=$gg[$arr['g_id']][$i]?>" placeholder="Gerçekleşen" disabled/>    
                                    <?
                                }
                            }
                        }
                        ?>
                    </td>
                    <td>
                        <?
                        for ($i = 1; $i <= $arr['periyod']; $i++)
                        {
                            $display_per = "";  
                            if ($donem == 2)
                            {
                                $periyod_hesap = 12/$arr['periyod']*$i;
                                if ($periyod_hesap > 6) // Yarı yıl
                                {
                                    $display_per = "disabled";
                                }
                            }
                            else if ($donem == 3)
                            {
                                $periyod_hesap = 12/$arr['periyod']*$i;
                                if ($periyod_hesap <= 6) // Yılsonu
                                {
                                    $display_per = "disabled";
                                }
                            }

                            if (!$degisiklik)
                            {
                                $display_per = "disabled";
                            }
                            if(sizeof($lokasyon)==0 || !in_array($office,$lokasyon))
                            {
                                $display_per = "disabled";  
                            }
                            if ($arr['veri_kaynagi'] == 4) // Veri kaynağı sadece sonuç ise sonuç girişi yapabilir..
                            {
                                if ($who == 'rilen')
                                {
                                    ?>
                                    <input type='text' class='form-control form-control-sm' name="gs[<?=$arr['g_id']?>][<?=$i?>]" value="<?=$gs[$arr['g_id']][$i]?>" placeholder="Sonuç" <?=$display_per?>/>    
                                    <?
                                }
                                else
                                {
                                    ?>
                                    <input type='text' class='form-control form-control-sm' name="gs[<?=$arr['g_id']?>][<?=$i?>]" value="<?=$gs[$arr['g_id']][$i]?>" placeholder="Sonuç" disabled/>    
                                    <?
                                }
                            }
                            else if ($arr['veri_hesaplama'] != 5)
                            {
                                for ($i = 1; $i <= $arr['periyod']; $i++)
                                {
                                    if (is_array($per_urun[$arr['g_id']][$i]))
                                    {
                                        foreach ($per_urun[$arr['g_id']][$i] as $key1=>$val1)
                                        {
                                            ?>
                                            <input type='text' class='form-control form-control-sm' value="<?=$gs[$arr['g_id']][$i][$val1]?>" placeholder="Sonuç" disabled/>    
                                            <?
                                        }
                                    }
                                    else
                                    {
                                        ?>
                                        <input type='text' class='form-control form-control-sm' value="<?=$gs[$arr['g_id']][$i]?>" placeholder="Sonuç" disabled/>    
                                        <?
                                    }
                                }
                            }
                            else
                            {
                                ?>
                                <input type='text' class='form-control form-control-sm' value="" placeholder="Sonuç" disabled/>    
                                <?
                            }
                        }
                        ?>
                    </td>
                    <td>
                        <?
                        if ($who == 'rilen' && $degisiklik)
                        {
                            ?>
                            <textarea name="rilen_ack[<?=$arr['g_id']?>]" id="" rows="<?=$arr['periyod']?>" class="form-control" placeholder='Açıklama' <?php if(sizeof($lokasyon)==0 || !in_array($office,$lokasyon)){echo 'disabled';}?>><?=$aciklama[$arr['g_id']]?></textarea>
                            <?
                        }
                        else
                        {
                            echo $aciklama[$arr['g_id']];
                        }
                        ?>
                    </td>
                    <td>
                        <?php 
                      

                       // var_dump($arr['periyod']);
                        
                      
                     
                    
                        $agirlikSonuc = 0;
                        for ($i = 1; $i <= $arr['periyod']; $i++){

                           
                            $counter = count($gd[$arr['g_id']][$i]);
                            if(is_array($gd[$arr['g_id']][$i])){

                               foreach($gd[$arr['g_id']][$i] as $value){
                                    $agirlikSonuc+= ($arr['agirlik']/$counter/4) * $value;
                                }

                            }
                            else{
                                $agirlikSonuc = ($arr['agirlik']/4) * $gd[$arr['g_id']][$i];
                             
                            }

                            
                        }

                        echo $agirlikSonuc;
                        $agirlikSonuc=0; 
                        
                     
                        
                        ?>
                    </td>
                    <td>
                        <table>
                            <?
                            for ($i = 1; $i <= $arr['periyod']; $i++)
                            {
                                $display_per = "";  
                                if ($donem == 2)
                                {
                                    $periyod_hesap = 12/$arr['periyod']*$i;
                                    if ($periyod_hesap > 6) // Yarı yıl
                                    {
                                        $display_per = "disabled";
                                    }
                                }
                                else if ($donem == 3)
                                {
                                    $periyod_hesap = 12/$arr['periyod']*$i;
                                    if ($periyod_hesap <= 6) // Yılsonu
                                    {
                                        $display_per = "disabled";
                                    }
                                }
                                
                                if (!$degisiklik)
                                {
                                    $display_per = "disabled";
                                }
                                if(sizeof($lokasyon)==0 || !in_array($office,$lokasyon))
                            {
                                $display_per = "disabled";  
                            }
                                if ($arr['degerlendirme_turu'] == 1) // veri oto gelecekse
                                {
                                    for ($i = 1; $i <= $arr['periyod']; $i++)
                                    {
                                        if (is_array($per_urun[$arr['g_id']][$i]))
                                        {
                                            foreach ($per_urun[$arr['g_id']][$i] as $key1=>$val1)
                                            {
                                                ?>
                                                <tr>
                                                    <td style='width: 25%; padding: 3px 2px;'>
                                                        <?=($gd[$arr['g_id']][$i][$val1] == 1)?'X':'&nbsp;'?>
                                                    </td>
                                                    <td style='width: 25%; padding: 3px 2px;'>
                                                        <?=($gd[$arr['g_id']][$i][$val1] == 2)?'X':'&nbsp;'?>
                                                    </td>
                                                    <td style='width: 25%; padding: 3px 2px;'>
                                                        <?=($gd[$arr['g_id']][$i][$val1] == 3)?'X':'&nbsp;'?>
                                                    </td>
                                                    <td style='width: 25%; padding: 3px 2px;'>
                                                        <?=($gd[$arr['g_id']][$i][$val1] == 4)?'X':'&nbsp;'?>
                                                    </td>
                                                </tr>    
                                                <?
                                            }
                                        }
                                        else
                                        {
                                            ?>
                                            <tr>
                                                <td style='width: 25%; padding: 3px 2px;'>
                                                    <?=($gd[$arr['g_id']][$i] == 1)?'X':'&nbsp;'?>
                                                </td>
                                                <td style='width: 25%; padding: 3px 2px;'>
                                                    <?=($gd[$arr['g_id']][$i] == 2)?'X':'&nbsp;'?>
                                                </td>
                                                <td style='width: 25%; padding: 3px 2px;'>
                                                    <?=($gd[$arr['g_id']][$i] == 3)?'X':'&nbsp;'?>
                                                </td>
                                                <td style='width: 25%; padding: 3px 2px;'>
                                                    <?=($gd[$arr['g_id']][$i] == 4)?'X':'&nbsp;'?>
                                                </td>
                                            </tr>    
                                            <?
                                        }
                                    }
                                }
                                else if ($arr['degerlendirme_turu'] == 2) // Objektif
                                {
                                    if (is_array($per_urun[$arr['g_id']][$i]))
                                    {
                                        foreach ($per_urun[$arr['g_id']][$i] as $key1=>$val1)
                                        {
                                            ?>
                                            <tr>
                                                <td style='width: 25%; padding: 3px 2px;'>
                                                    <?=($gd[$arr['g_id']][$i][$val1] == 1)?'X':'&nbsp;'?>
                                                </td>
                                                <td style='width: 25%; padding: 3px 2px;'>
                                                    <?=($gd[$arr['g_id']][$i][$val1] == 2)?'X':'&nbsp;'?>
                                                </td>
                                                <td style='width: 25%; padding: 3px 2px;'>
                                                    <?=($gd[$arr['g_id']][$i][$val1] == 3)?'X':'&nbsp;'?>
                                                </td>
                                                <td style='width: 25%; padding: 3px 2px;'>
                                                    <?=($gd[$arr['g_id']][$i][$val1] == 4)?'X':'&nbsp;'?>
                                                </td>
                                            </tr>    
                                            <?
                                        }
                                    }
                                    else
                                    {
                                        ?>
                                        <tr>
                                            <td style='width: 25%; padding: 3px 2px;'>
                                                <?=($gd[$arr['g_id']][$i] == 1)?'X':'&nbsp;'?>
                                            </td>
                                            <td style='width: 25%; padding: 3px 2px;'>
                                                <?=($gd[$arr['g_id']][$i] == 2)?'X':'&nbsp;'?>
                                            </td>
                                            <td style='width: 25%; padding: 3px 2px;'>
                                                <?=($gd[$arr['g_id']][$i] == 3)?'X':'&nbsp;'?>
                                            </td>
                                            <td style='width: 25%; padding: 3px 2px;'>
                                                <?=($gd[$arr['g_id']][$i] == 4)?'X':'&nbsp;'?>
                                            </td>
                                        </tr>
                                        <?
                                    }
                                    ?>
                                    <?
                                }
                                else if ($arr['degerlendirme_turu'] == 3) // Subjektif
                                {
                                    if ($who == 'yon1')
                                    {
                                        if ($degisiklik)
                                        {
                                            $yon_kaydet = true;
                                            ?>
                                            <tr>
                                                <td style='width: 25%; padding: 3px 2px;'>
                                                    <input type="radio" name="gd[<?=$arr['g_id']?>][<?=$i?>]" value="1" <?=($gd[$arr['g_id']][$i] == 1)?'checked':''?> <?=$display_per?> required>
                                                </td>
                                                <td style='width: 25%; padding: 3px 2px;'>
                                                    <input type="radio" name="gd[<?=$arr['g_id']?>][<?=$i?>]" value="2" <?=($gd[$arr['g_id']][$i] == 2)?'checked':''?> <?=$display_per?> required>
                                                </td>
                                                <td style='width: 25%; padding: 3px 2px;'>
                                                    <input type="radio" name="gd[<?=$arr['g_id']?>][<?=$i?>]" value="3" <?=($gd[$arr['g_id']][$i] == 3)?'checked':''?> <?=$display_per?> required>
                                                </td>
                                                <td style='width: 25%; padding: 3px 2px;'>
                                                    <input type="radio" name="gd[<?=$arr['g_id']?>][<?=$i?>]" value="4" <?=($gd[$arr['g_id']][$i] == 4)?'checked':''?> <?=$display_per?> required>
                                                </td>
                                            </tr>    
                                            <?
                                        }
                                        else
                                        {
                                            ?>
                                            <tr>
                                                <td style='width: 25%; padding: 3px 2px;'>
                                                    <?=($gd[$arr['g_id']][$i] == 1)?'X':'&nbsp;'?>
                                                </td>
                                                <td style='width: 25%; padding: 3px 2px;'>
                                                    <?=($gd[$arr['g_id']][$i] == 2)?'X':'&nbsp;'?>
                                                </td>
                                                <td style='width: 25%; padding: 3px 2px;'>
                                                    <?=($gd[$arr['g_id']][$i] == 3)?'X':'&nbsp;'?>
                                                </td>
                                                <td style='width: 25%; padding: 3px 2px;'>
                                                    <?=($gd[$arr['g_id']][$i] == 4)?'X':'&nbsp;'?>
                                                </td>
                                            </tr>      
                                            <?
                                        }
                                    }
                                    else
                                    {
                                        ?>
                                        <tr>
                                            <td style='width: 25%; padding: 3px 2px;'>
                                                <?=($gd[$arr['g_id']][$i] == 1)?'X':'&nbsp;'?>
                                            </td>
                                            <td style='width: 25%; padding: 3px 2px;'>
                                                <?=($gd[$arr['g_id']][$i] == 2)?'X':'&nbsp;'?>
                                            </td>
                                            <td style='width: 25%; padding: 3px 2px;'>
                                                <?=($gd[$arr['g_id']][$i] == 3)?'X':'&nbsp;'?>
                                            </td>
                                            <td style='width: 25%; padding: 3px 2px;'>
                                                <?=($gd[$arr['g_id']][$i] == 4)?'X':'&nbsp;'?>
                                            </td>
                                        </tr>      
                                        <?
                                    }
                                }
                            }
                            ?>
                        </table>
                    </td>
                </tr>
                <?
            }
            ?>
        </table>
        <div>
            <?
            if (($who == 'rilen' || $yon_kaydet) && $degisiklik && (sizeof($lokasyon)>0 && in_array($office,$lokasyon)))
            {
                ?>
                <input type="hidden" name="yil" value="<?=$yil?>">
                <input type="hidden" name="donem" value="<?=$donem?>">
                <input type="hidden" name="rilen_pernr" value="<?=$rilen['pernr']?>">
                <input type="hidden" name="form_id" value="<?=$form_baslik['form_id']?>">
                <input type="hidden" name="ft_id" value="<?=$form_baslik['ft_id']?>">
                <button class="btn btn-block btn-primary">Kaydet</button>   
                <?
            }
            ?>
        </div>
    </form>
    
  </div>
  <!-- /.container-fluid -->

</div>
<!-- /.content-wrapper -->

<script language="javascript" type="text/javascript">  
</script>

<?=$footer2?>