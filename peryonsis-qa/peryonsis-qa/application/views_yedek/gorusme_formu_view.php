<?=$header2?>
<style src = "text/css">
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
            <th>Değerlendiren</th>
            <td><?=$ren['ad_soyad']?></td>
        </tr>
        <tr>
            <th>Değerlendirilen</th>
            <td><?=$rilen['ad_soyad']?></td>
        </tr>
        <tr>
            <th>Donem</th>
            <td><?=$yil?> - <?=$donem_ack?></td>
        </tr>
    </table>
    
    <form method="POST" action="<?=base_url()?>d_donemleri/form_kaydet/" >
        <div>
            <h2 class="bg_sf_k text-center"><?=$form_baslik['ft_text']?></h2>
        </div>
        <?
        foreach ($gostergeler as $key=>$arr)
        {
            ?>
            <div class="bg_baslik">
                <b><?=$arr['g_text']?></b>
            </div>
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
                if ($who == 'yon1')
                {
                    ?>
                    <div>
                        <b><?=$i?>. Periyot</b><br/>
                        <textarea name="aciklama[<?=$arr['g_id']?>][<?=$i?>]" rows="4" class="form-control" placeholder='<?=$i?>. Periyot' <?=$display_per?> required><?=$aciklama2[$arr['g_id']][$i]?></textarea>
                    </div>
                    <?
                }
                else
                {
                    ?>
                    <b><?=$i?>. Periyot</b><br/>
                    <div style="min-height: 100px; border: 1px solid; padding: 5px 5px;">
                        <?=$aciklama2[$arr['g_id']][$i];?> 
                    </div>    
                    <?
                }
            }
        }
        ?>
        <div style='margin-top: 10px;'>
            <?
            //if (($who == 'yon1') && $degisiklik && (sizeof($lokasyon)>0 && in_array($office,$lokasyon)))
            if (($who == 'yon1') && $degisiklik && ($donem_status == 1))
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