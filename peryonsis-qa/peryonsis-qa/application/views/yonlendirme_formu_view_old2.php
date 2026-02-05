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
      
    <?
    if ($who == "yon1")
    {
        ?>
        <div class="alert alert-info">
            Yönlendirme formunu doldurulmadan önce potansiyel ve görüşme formu doldurulmalıdır.
        </div>     
        <?
    }
    ?>
    
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
                ?>
                <b><?=$i?>. Periyot</b><br/>
                <div style="min-height: 100px; border: 1px solid; padding: 5px 5px;">
                    <?=$aciklama2[$arr['g_id']][$i];?>
                </div>      
                <?
            }
        }
        ?>
        <div class="bg_baslik">
            <b>Çalışan Hareket Planı</b>
        </div>
        <div> 
            <table class="table table-bordered">
                <tr>
                    <th>Potansiyel Değerlendirme</th>
                    <?
                    if ($pot_varmi == 1)
                    {
                        ?>
                        <th>Üst Göreve Atanabilir</th>
                        <?
                    }
                    ?>
                    <th>Eş Düzey Göreve Geçebilir</th>
                    <th>İşten Ayrılmasında Sakınca Yoktur</th>
                    <th>Mevcut Göreve Devam Edebilir</th>
                </tr>
                <tr>
                    <td>
                        <?
                        if ($pot_varmi == 1)
                        {
                            if ($pot_puan > 0)
                            {
                                if ($pot_puan >= 70)
                                {
                                    $disabled = "disabled";
                                    echo 'Potansiyeli Yeterli - '.$pot_puan;
                                }
                                else
                                {
                                    $disabled2 = "disabled";
                                    echo 'Potansiyeli Yetersiz - '.$pot_puan;
                                }
                            }
                        }
                        else 
                        {
                            echo "Form Yok";
                        }
                        ?>
                    </td>
                    <?
                    if (($who == 'yon1') && $degisiklik)
                    {
                        if ($pot_varmi == 1)
                        {
                            ?>
                            <td>
                                <input type="radio" name="hp" value="1" <?=($hp == 1)?'checked':''?> <?=$disabled2?> required/>
                            </td>
                            <?
                        }
                        ?>
                        <td>
                            <input type="radio" name="hp" value="2" <?=($hp == 2)?'checked':''?> <?=$disabled?> required/>
                        </td>
                        <td>
                            <input type="radio" name="hp" value="3" <?=($hp == 3)?'checked':''?> <?=$disabled?> required/>
                        </td>
                        <td>
                            <input type="radio" name="hp" value="4" <?=($hp == 4)?'checked':''?> <?=$disabled?> required/>
                        </td>   
                        <?
                    }
                    else
                    {
                        if ($pot_varmi == 1)
                        {
                            ?>
                            <td>
                                <?
                                if ($hp == 1)
                                {
                                    echo 'X';
                                }
                                ?>
                            </td>
                            <?
                        }
                        ?>
                        <td>
                            <?
                            if ($hp == 2)
                            {
                                echo 'X';
                            }
                            ?>
                        </td> 
                        <td>
                            <?
                            if ($hp == 3)
                            {
                                echo 'X';
                            }
                            ?>
                        </td> 
                        <td>
                            <?
                            if ($hp == 4)
                            {
                                echo 'X';
                            }
                            ?>
                        </td> 
                        <?
                    }
                    ?>
                </tr>
            </table>
        </div>
        <div>
            <?
            if (($who == 'yon1') && $degisiklik)
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