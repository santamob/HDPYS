<?=$header2?>
<style src='text/css'>
    table {
        width: 100%;
    }
    /* td {
        vertical-align: middle;
        border: solid 1px #e1edf7;
    } */
    .bg_sf_k {
        background-color: #ED1C24;
        color: #FFF;
    }
    .bg_baslik {
        background-color: #e1e1e1;
    }
    .btn_baslik
    {
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
    //   $lokasyon = explode('-',$lokasyonTablo[0]['lokasyon']);
    //     $lokasyon = array_filter($lokasyon);

        $donem_status = $lokasyonTablo[0]["donem_status"];

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
            if ($who == 'rilen')
            {
                ?>
                <tr>
                    <th>Yetkinlik Puanı</th>
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
            else
            {
                ?>
                <tr>
                    <th>Astın Verdiği Yetkinlik Puanı</th>
                    <td>
                        <?
                        if ($ast_puan > 0)
                        {
                            echo $ast_puan;
                        }
                        else
                        {
                            echo "Değerlendirme yapılmadı";
                        }
                        ?>
                    </td>
                </tr>  
                <tr>
                    <th>Yöneticinin Verdiği Yetkinlik Puanı</th>
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
        }
        ?>
    </table>
      
    <form method="POST" action="<?=base_url()?>d_donemleri/form_kaydet/" >
        <div class="alert alert-info">
            <?php
                foreach($gostergeler as $key=>$g){
                    $text = " " ;
                    foreach($g["kademeler"] as $kademe){
                        $text.= $kademe["kademe_text"]."&nbsp;&nbsp;&nbsp;&nbsp;";
                    }
                }
                echo $text;

            ?>
            <!-- 1 - Beklenen Düzeyin Altı, 2 - Beklenen Düzey, 3 - Beklenen Düzeyin Üstü, 4 - Beklenen Düzeyin Çok Üstü -->
        </div>
        <div class="table-responsive">
        <table class='table table-bordered margin_bot'>
            <tr>
                <?
                if ($who == 'rilen')
                {
                    ?>
                    <td colspan="3" class='text-center bg_sf_k'>
                        <h3><?=$form_baslik['ft_text']?></h3>
                    </td>
                    <?
                }
                else
                {
                    ?>
                    <td colspan="4" class='text-center bg_sf_k'>
                        <h3><?=$form_baslik['ft_text']?></h3>
                    </td>
                    <?
                }
                ?>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <?
                if ($who == 'rilen')
                {
                    ?>
                    <td class="text-center">Değerlendirme Düzeyleri</td>
                    <?
                }
                else
                {
                    ?>
                    <td colspan="2" class="text-center">Değerlendirme Düzeyleri</td>
                    <?
                }
                ?>
            </tr>
            <?
            $sayac = 0;
            $eski_kategori = "";
            foreach ($gostergeler as $key=>$arr)
            {

                $count_kategoriler = count($gostergeler[$key]["kademeler"]);
                
                if ($eski_kategori != $arr['kategori'])
                {
                    $eski_kategori = $arr['kategori'];
                    ?>
                    <tr class='bg_baslik'>
                        <td><?=$arr['kategori']?></td>
                        <td>Ağırlık<br> <?=$kategori_toplam[$arr['kategori']]?></td>
                        <?
                        if ($sayac == 0)
                        {
                            if ($donem == 3)
                            {
                                if ($who == 'rilen')
                                {
                                    ?>
                                    <td class='text-center'>Puan (<?=$puan?>)</td>   
                                    <?
                                }
                                else
                                {
                                    ?>
                                    <td>Astın Verdiği Puan (<?=$ast_puan?>)</td>
                                    <td>Yöneticinin Verdiği Puan (<?=$puan?>)</td>
                                    <?
                                }
                            }
                            else
                            {
                                if ($who == 'rilen')
                                {
                                    ?>
                                    <td class='text-center'></td>   
                                    <?
                                }
                                else
                                {
                                    ?>
                                    <td>Astın Değerlendirmesi</td>
                                    <td>Yöneticinin Değerlendirmesi</td>
                                    <?
                                }
                            }
                        }
                        else
                        {
                            if ($who == 'rilen')
                            {
                                ?>
                                <td></td>   
                                <?
                            }
                            else
                            {
                                ?>
                                <td colspan="2"></td>
                                <?
                            }
                        }
                        $sayac++;
                        ?>
                    </tr>    
                    <?
                }
                ?>
                <tr>
                   <td class="col-lg-6">
                        <?=$arr['g_text']?>: <?=$arr['g_desc']?> 
                        <br/>
                        <a href="JavaScript:Void(0)" onclick="jQuery('#detay_<?=$arr['g_id']?>').toggle()">Detay için tıklayınız.</a>
                        <span class="text-danger" style="display: none;" id="detay_<?=$arr['g_id']?>">
                        <br/>
                        <?=$arr['kmin_text']?><br/><?=$arr['kmax_text']?></span>
                    </td>
                    <td>
                        <?php  if ($who == 'yon1'){
                            for ($i = 1; $i <= $arr['periyod']; $i++)
                            {

                                for($k_i = 0;$k_i <= $count_kategoriler-1;$k_i++){
                                    if($gd[$arr['g_id']][$i] == ($k_i+1)){
                                        $agirlikSonuc = (($kategori_toplam[$arr['kategori']]/count($arr['periyod'])) / $count_kategoriler) * ($k_i+1); 
                                    }
                                }
                                       
                                // if($gd[$arr['g_id']][$i] == 1){
                                //     $agirlikSonuc = (($kategori_toplam[$arr['kategori']]/count($arr['periyod']))/4);
                                // }else if($gd[$arr['g_id']][$i] == 2){
                                //     $agirlikSonuc = (($kategori_toplam[$arr['kategori']]/count($arr['periyod']))/4)*2;
                                // }else if($gd[$arr['g_id']][$i] == 3){
                                //     $agirlikSonuc = (($kategori_toplam[$arr['kategori']]/count($arr['periyod']))/4)*3;
                                // }else if($gd[$arr['g_id']][$i] == 4){
                                //     $agirlikSonuc = (($kategori_toplam[$arr['kategori']]/count($arr['periyod']))/4)*4;
                                // }
                            }
                            echo $agirlikSonuc;
                            $agirlikSonuc='';
                        }?>
                        
                    </td>
                    <?

                   
                    if ($who == "rilen")
                    {
                        ?>
                        <td>
                            <table>
                                <tr>
                                    <td></td>
                                    <?php
                                        foreach($arr["kademeler"] as $kademe_key => $a){
                                    ?>
                                        <td><a class="btn btn_baslik" title="<?=$a['kademe_text']?>"><?php echo  ($kademe_key+1) ?></a></td>
                                    <?php
                                        }
                                    ?>
                                    <!-- <td><a class="btn btn_baslik" title="<?=$arr['k1_text']?>">1</a></td>
                                    <td><a class="btn btn_baslik" title="<?=$arr['k2_text']?>">2</a></td>
                                    <td><a class="btn btn_baslik" title="<?=$arr['k3_text']?>">3</a></td>
                                    <td><a class="btn btn_baslik" title="<?=$arr['k4_text']?>">4</a></td> -->
                                </tr>
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
                                    //if(sizeof($lokasyon)==0 || !in_array($office,$lokasyon))
                                    if($donem_status == 0)
                                    {
                                        $display_per = "disabled";  
                                    }
                                    if ($degisiklik)
                                    {

                                       
                                        ?>
                                        <tr>
                                           
                                            <td class="text-center"><?=$i?>. Periyot</td>

                                            <?php
                                              for($k_i = 0;$k_i <= $count_kategoriler-1;$k_i++){
                                            ?>
                                            <td class="text-center">
                                                <input type="radio" name="gd[<?=$arr['g_id']?>][<?=$i?>]" value="<?php echo ($k_i+1) ?>" <?=($gd[$arr['g_id']][$i] == ($k_i+1)) ? 'checked':''?> <?=$display_per?> required>
                                            </td>

                                            <?php
                                                }
                                            ?>
                                          
                                            <!-- <td style='width: 25%; padding: 3px 2px;'>
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
                                            </td> -->

                                        </tr>
                                        <?
                                    }
                                    else
                                    {
                                       ?>
                                        <tr>
                                            <td><?=$i?>. Periyot</td>

                                            <?php
                                              for($k_i = 0;$k_i <= $count_kategoriler-1;$k_i++){
                                            ?>
                                            
                                            <td class="text-center">
                                                <?=($gd[$arr['g_id']][$i] == ($k_i+1))?'X':'&nbsp;'?>
                                            </td>

                                            <?php
                                                }
                                            ?>

                                            <!-- <td style='width: 25%; padding: 3px 2px;'>
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
                                            </td> -->


                                        </tr>       
                                        <?
                                    }
                                }
                                ?>
                            </table>
                        </td>
                        <?
                    }

                    else if ($who == 'yon1')
                    {
                        ?>
                        <td>
                            <table class="table">
                                <tr">
                                    <td></td>
                                    <?php
                                        foreach($arr["kademeler"] as $kademe_key => $a){
                                    ?>
                                        <td><a class="btn btn_baslik" title="<?=$a['kademe_text']?>"><?php echo  ($kademe_key+1) ?></a></td>
                                    <?php
                                        }
                                    ?>
                                    <!-- <td><a class="btn btn_baslik" title="<?=$arr['k1_text']?>">1</a></td>
                                    <td><a class="btn btn_baslik" title="<?=$arr['k2_text']?>">2</a></td>
                                    <td><a class="btn btn_baslik" title="<?=$arr['k3_text']?>">3</a></td>
                                    <td><a class="btn btn_baslik" title="<?=$arr['k4_text']?>">4</a></td> -->
                                </tr>
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
                                    //if(sizeof($lokasyon)==0 || !in_array($office,$lokasyon))
                                    if($donem_status == 0)
                                    {
                                        $display_per = "disabled";  
                                    }
                                    ?>
                                    <tr>
                                        <td><?=$i?>. Periyot</td>
                                        
                                            <?php
                                               for($k_i = 0;$k_i <= $count_kategoriler-1;$k_i++){
                                              
                                            ?>
                                            
                                            <td class="text-center">
                                                <?=($gd_ast[$arr['g_id']][$i] == ($k_i+1))?'X':'&nbsp;'?>
                                            </td>

                                            <?php
                                                }
                                            ?>

                                            <!-- <td style='width: 25%; padding: 3px 2px;'>
                                                <?=($gd_ast[$arr['g_id']][$i] == 1)?'X':'&nbsp;'?>
                                            </td>
                                            <td style='width: 25%; padding: 3px 2px;'>
                                                <?=($gd_ast[$arr['g_id']][$i] == 2)?'X':'&nbsp;'?>
                                            </td>
                                            <td style='width: 25%; padding: 3px 2px;'>
                                                <?=($gd_ast[$arr['g_id']][$i] == 3)?'X':'&nbsp;'?>
                                            </td>
                                            <td style='width: 25%; padding: 3px 2px;'>
                                                <?=($gd_ast[$arr['g_id']][$i] == 4)?'X':'&nbsp;'?>
                                            </td> -->
                                    </tr>    
                                    <?
                                }
                                ?>
                            </table>
                        </td>
                        <td>
                            <table>
                                <tr>
                                    <td></td>

                                    <?php
                                        foreach($arr["kademeler"] as $kademe_key => $a){
                                    ?>
                                        <td><a class="btn btn_baslik" title="<?=$a['kademe_text']?>"><?php echo  ($kademe_key+1) ?></a></td>
                                    <?php
                                        }
                                    ?>
                                    <!-- <td><a class="btn btn_baslik" title="<?=$arr['k1_text']?>">1</a></td>
                                    <td><a class="btn btn_baslik" title="<?=$arr['k2_text']?>">2</a></td>
                                    <td><a class="btn btn_baslik" title="<?=$arr['k3_text']?>">3</a></td>
                                    <td><a class="btn btn_baslik" title="<?=$arr['k4_text']?>">4</a></td> -->
                                </tr>
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
                                    //if(sizeof($lokasyon)==0 || !in_array($office,$lokasyon))
                                    if($donem_status == 0)
                                    {
                                        $display_per = "disabled";  
                                    }
                                    if ($degisiklik)
                                    {
                                        ?>
                                        <tr>
                                            <td><?=$i?>. Periyot</td>

                                            <?php
                                              for($k_i = 0;$k_i <= $count_kategoriler-1;$k_i++){
                                            ?>
                                            <td class="text-center">
                                                <input type="radio" name="gd[<?=$arr['g_id']?>][<?=$i?>]" value="<?php echo ($k_i+1) ?>" <?=($gd[$arr['g_id']][$i] == ($k_i+1)) ? 'checked':''?> <?=$display_per?> required>
                                            </td>

                                            <?php
                                                }
                                            ?>
                                            <!-- <td style='width: 25%; padding: 3px 2px;'>
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
                                            </td> -->
                                        </tr>
                                        <?
                                    }
                                    else
                                    {
                                        ?>
                                        <tr>
                                            <td><?=$i?>. Periyot</td>

                                            <?php
                                              for($k_i = 0;$k_i <= $count_kategoriler-1;$k_i++){
                                            ?>
                                            
                                            <td class="text-center">
                                                <?=($gd[$arr['g_id']][$i] == ($k_i+1))?'X':'&nbsp;'?>
                                            </td>

                                            <?php
                                                }
                                            ?>

                                            <!-- <td style='width: 25%; padding: 3px 2px;'>
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
                                            </td> -->
                                        </tr>       
                                        <?
                                    }
                                }
                                ?>
                            </table>
                        </td>
                        <?
                    }
                    else if ($who == 'yon2')
                    {
                        ?>
                        <td>
                            <table>
                                <tr>
                                    <td></td>
                                    <?php
                                        foreach($arr["kademeler"] as $kademe_key => $a){
                                    ?>
                                        <td><a class="btn btn_baslik" title="<?=$a['kademe_text']?>"><?php echo  ($kademe_key+1) ?></a></td>
                                    <?php
                                        }
                                    ?>
                                    <!-- <td><a class="btn btn_baslik" title="<?=$arr['k1_text']?>">1</a></td>
                                    <td><a class="btn btn_baslik" title="<?=$arr['k2_text']?>">2</a></td>
                                    <td><a class="btn btn_baslik" title="<?=$arr['k3_text']?>">3</a></td>
                                    <td><a class="btn btn_baslik" title="<?=$arr['k4_text']?>">4</a></td> -->
                                </tr>
                                <?
                                for ($i = 1; $i <= $arr['periyod']; $i++)
                                {
                                    ?>
                                    <tr>
                                        <td><?=$i?>. Periyot</td>

                                        <?php
                                              for($k_i = 0;$k_i <= $count_kategoriler-1;$k_i++){
                                            ?>
                                            
                                            <td class="text-center">
                                                <?=($gd[$arr['g_id']][$i] == ($k_i+1))?'X':'&nbsp;'?>
                                            </td>

                                            <?php
                                                }
                                            ?>
                                        
                                        <!-- <td style='width: 25%; padding: 3px 2px;'>
                                            <?=($gd_ast[$arr['g_id']][$i] == 1)?'X':'&nbsp;'?>
                                        </td>
                                        <td style='width: 25%; padding: 3px 2px;'>
                                            <?=($gd_ast[$arr['g_id']][$i] == 2)?'X':'&nbsp;'?>
                                        </td>
                                        <td style='width: 25%; padding: 3px 2px;'>
                                            <?=($gd_ast[$arr['g_id']][$i] == 3)?'X':'&nbsp;'?>
                                        </td>
                                        <td style='width: 25%; padding: 3px 2px;'>
                                            <?=($gd_ast[$arr['g_id']][$i] == 4)?'X':'&nbsp;'?>
                                        </td> -->
                                    </tr>    
                                    <?
                                }
                                ?>
                            </table>
                        </td>
                        <td>
                            <table>
                                <tr>
                                    <td></td>
                                    <?php
                                        foreach($arr["kademeler"] as $kademe_key => $a){
                                    ?>
                                        <td><a class="btn btn_baslik" title="<?=$a['kademe_text']?>"><?php echo  ($kademe_key+1) ?></a></td>
                                    <?php
                                        }
                                    ?>
                                    <!-- <td><a class="btn btn_baslik" title="<?=$arr['k1_text']?>">1</a></td>
                                    <td><a class="btn btn_baslik" title="<?=$arr['k2_text']?>">2</a></td>
                                    <td><a class="btn btn_baslik" title="<?=$arr['k3_text']?>">3</a></td>
                                    <td><a class="btn btn_baslik" title="<?=$arr['k4_text']?>">4</a></td> -->
                                </tr>
                                <?
                                for ($i = 1; $i <= $arr['periyod']; $i++)
                                {
                                    ?>
                                    <tr>
                                        <td><?=$i?>. Periyot</td>
                                        <?php
                                              for($k_i = 0;$k_i <= $count_kategoriler-1;$k_i++){
                                            ?>
                                            
                                            <td class="text-center">
                                                <?=($gd[$arr['g_id']][$i] == ($k_i+1))?'X':'&nbsp;'?>
                                            </td>

                                            <?php
                                                }
                                            ?>
                                        <!-- <td style='width: 25%; padding: 3px 2px;'>
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
                                        </td> -->
                                    </tr>        
                                    <?
                                }
                                ?>
                            </table>
                        </td>
                        <?
                    }
                    ?>
                </tr>
                <?
            }
            ?>
        </table>
        </div>
        
        <div>
            <?
            //if (($who == 'rilen' || $who == 'yon1') && $degisiklik && (sizeof($lokasyon)>0 && in_array($office,$lokasyon)))
            if (($who == 'rilen' || $who == 'yon1') && $degisiklik && ($donem_status == 1))
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