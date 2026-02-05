<?=$header2?>
<div class="">

  <div class="container-fluid">

    <!-- Breadcrumbs -->
    <ol class="breadcrumb">
      <li class="breadcrumb-item">
        <a href="<?=base_url()?>">Anasayfa</a>
      </li>
      <li class="breadcrumb-item">
        <a href="<?=base_url()?>d_donemleri/kademelendirme/<?=$yil?>/<?=$donem?>">Kademelendirme İşlemleri</a>
      </li>
      <li class="breadcrumb-item active">Kademelendirme Havuzu</li>
    </ol>
    
    <?
    
    if ($this->session->userdata('message'))
    {
        echo $this->session->userdata('message');
    }
    ?>
    
    <div id="alert_div">

    </div>
    
    <div id="tabs_k">
        <ul>
          <li><a href="#tabs_k-1"><?=$havuz_adi?></a></li>
        </ul>
        <div id='tabs_k-1'>
            <table class="table table-bordered">
                <tr>
                    <th colspan="4">Toplam Kişi Sayısı : <?=$kisi_sayisi?></th>
                </tr>
                <tr>
                    <th colspan="4" class="text-center">Olması Gereken Dağılım</th>
                </tr>
                <tr>
                    <td class="bg-danger">Beklenen Düzeyin Altı</td>
                    <td class="bg-warning">Beklenen Düzey</td>
                    <td class="bg-info">Beklenen Düzeyin Üstü</td>
                    <td class="bg-success">Beklenen Düzeyin Çok Üstü</td>
                </tr>
                <tr>
                    <?
                    if ($kisi_sayisi == 3 || $kisi_sayisi == 4)
                    {
                        ?>
                        <td colspan="2" style="text-align: center;"><?php echo $kisi_sayisi_b; ?></td>
                        <td colspan="2" style="text-align: center;"><?php echo $kisi_sayisi_bu; ?></td>    
                        <?
                    }
                    else if ($kisi_sayisi == 2 || $kisi_sayisi == 1)
                    {
                        ?>
                        <td colspan="4" style="text-align: center;"><?php echo 'Serbest'; ?></td>    
                        <?
                    }
                    else
                    {
                        ?>
                        <td style="text-align: center;"><?php echo $kisi_sayisi_ba; ?></td>
                        <td style="text-align: center;"><?php echo $kisi_sayisi_b; ?></td>
                        <td style="text-align: center;"><?php echo $kisi_sayisi_bu; ?></td>
                        <td style="text-align: center;"><?php echo $kisi_sayisi_bcu; ?></td>      
                        <?
                    }
                    ?>
                </tr>
                <tr>
                    <th colspan="4" class="text-center">Mevcut Dağılım</th>
                </tr>
                <tr>
                    <?
                    if ($iptal) // İptal edilebiliyorsa kademelendirme başlamış demektir.
                    {
                        ?>
                        <td class="text-center"><?=$md['kisi_sayisi_ba']?></td>    
                        <td class="text-center"><?=$md['kisi_sayisi_b']?></td>
                        <td class="text-center"><?=$md['kisi_sayisi_bu']?></td>
                        <td class="text-center"><?=$md['kisi_sayisi_bcu']?></td>
                        <?
                    }
                    else
                    {
                        ?>
                        <td class="text-center">-</td>    
                        <td class="text-center">-</td>    
                        <td class="text-center">-</td>    
                        <td class="text-center">-</td>    
                        <?
                    }
                    ?>
                </tr>
                <?
                if ($who == 'kademelendiren')
                {
                    ?>
                    <tr>
                        <td colspan="4">
                            <?
                            if ($degisiklik)
                            {
                                if ($start && !$iptal)
                                {
                                    ?>
                                    <a href='<?=base_url()?>d_donemleri/kademelendirmeyi_baslat/<?=$yil?>/<?=$donem?>/<?=$havuz_id?>' class='btn btn-primary float-right' style='color: #FFF'>Kademelendirmeyi başlatmak için tıklayınız</a>
                                    <?
                                }
                                else if (!$start)
                                {
                                    ?>
                                    <div class="text-danger float-right">Kademelendirmeyi başlatabilmeniz için tüm astların değerlendirme ve onay işlemleri tamamlanmalıdır.</div>    
                                    <?
                                }
                                else if ($iptal)
                                {
                                    ?>
                                    <a href='<?=base_url()?>d_donemleri/kademelendirmeyi_iptal_et/<?=$yil?>/<?=$donem?>/<?=$havuz_id?>' class='btn btn-danger float-right' style='color: #FFF'>Kademelendirmeyi iptal etmek için tıklayınız</a>
                                    <?
                                }
                            }
                            else
                            {
                                ?>
                                <div class="text-danger float-right">Kademelendirme üzerinde değişiklik yapamazsınız.</div>    
                                <?
                            }
                            ?>
                        </td>
                    </tr>    
                    <?
                }
                ?>
            </table>
            <form method="POST" action="<?=base_url()?>d_donemleri/kademelendirmeyi_kaydet/" >
                <table class="table">
                    <tr>
                        <th>Ad Soyad</th>
                        <th>Pozisyon</th>
                        <?
                        if ($hedef_formu_var)
                        {
                            ?>
                            <th>Hedef Puanı</th>    
                            <?
                        }
                        ?>
                        <th>Yetkinlik Puanı</th>
                        <th>Toplam Puan</th>  
                        <th>Sistemin Önerdiği Kademe</th>
                        <th>Yeni Kademe</th>
                        
                    </tr>
                    <?
                    foreach ($siralama as $key=>$arr)
                    {
                        $color = "";
                        if ($arr['sk'] != $arr['vk'])
                        {
                            $color = "gray";
                        }
                        
                        $cls = "";
                        if ($arr['sk'] == 1)
                        {
                            $cls = "bg-danger";
                        }
                        else if ($arr['sk'] == 2)
                        {
                            $cls = "bg-warning";
                        }
                        else if ($arr['sk'] == 3)
                        {
                            $cls = "bg-info";
                        }
                        else if ($arr['sk'] == 4)
                        {
                            $cls = "bg-success";
                        }
                        else
                        {
                            if ($arr['vk'] == 1)
                            {
                                $cls = "bg-danger";
                            }
                            else if ($arr['vk'] == 2)
                            {
                                $cls = "bg-warning";
                            }
                            else if ($arr['vk'] == 3)
                            {
                                $cls = "bg-info";
                            }
                            else if ($arr['vk'] == 4)
                            {
                                $cls = "bg-success";
                            }
                        }
                        
                        ?>
                        <tr class="<?=$cls?>">
                            <td><?=$ad_soyad[$arr['pernr']]?></td>
                            <td><?=$pozisyon[$arr['pernr']]?></td>
                            <?
                            if ($hedef_formu_var)
                            {
                                ?>
                                <td>
                                    <?=$arr['hp']?><br/>
                                    <?
                                    if ($arr['hp'] > 0)
                                    {
                                        ?>
                                        <a href='<?=base_url()?>d_donemleri/form_goruntule/<?=$form_id[$arr['pernr']]['1']?>/<?=$arr['pernr']?>/<?=$yil?>/<?=$donem?>/1' target="_blank" class='btn btn-primary' style='color: #FFF'>Form İçin Tıklayınız</a>    
                                        <?
                                    }
                                    ?>
                                </td>
                                <?
                            }
                            ?>
                            <td>
                                <?=$arr['yp']?><br/>
                                <?
                                if ($arr['yp'] > 0)
                                {
                                    ?>
                                    <a href='<?=base_url()?>d_donemleri/form_goruntule/<?=$form_id[$arr['pernr']]['2']?>/<?=$arr['pernr']?>/<?=$yil?>/<?=$donem?>/2' target="_blank" class='btn btn-primary' style='color: #FFF'>Form İçin Tıklayınız</a>
                                    <?
                                }
                                ?>
                            </td>
                            <td><?=$arr['tp']?></td>
                            <td style="background-color: <?=$color?>">
                                <?
                                if ($arr['vk'] == 1)
                                {
                                    echo "Beklenen Düzeyin Altı";
                                }
                                else if ($arr['vk'] == 2)
                                {
                                    echo "Beklenen Düzey";
                                }
                                else if ($arr['vk'] == 3)
                                {
                                    echo "Beklenen Düzeyin Üstü";
                                }
                                else if ($arr['vk'] == 4)
                                {
                                    echo "Beklenen Düzeyin Çok Üstü";
                                }
                                else if ($iptal)
                                {
                                    echo "";
                                }
                                else
                                {
                                    echo "Kademelendirme başlatılmadı";
                                }
                                ?>
                            </td>
                            <td>
                                <?
                                if ($arr['vk'] == 1)
                                {
                                    ?>
                                    <select name="sk[<?=$arr['pernr']?>]" >
                                        <option value="2" <?=($arr['sk'] == 2)?'selected':''?>>Beklenen Düzey</option>
                                        <option value="1" <?=($arr['sk'] == 1)?'selected':''?>>Beklenen Düzeyin Altı</option>
                                    </select>    
                                    <?
                                }
                                else if ($arr['vk'] == 2)
                                {
                                    ?>
                                    <select name="sk[<?=$arr['pernr']?>]" >
                                        <option value="3" <?=($arr['sk'] == 3)?'selected':''?>>Beklenen Düzeyin Üstü</option>
                                        <option value="2" <?=($arr['sk'] == 2)?'selected':''?>>Beklenen Düzey</option>
                                        <option value="1" <?=($arr['sk'] == 1)?'selected':''?>>Beklenen Düzeyin Altı</option>
                                    </select>    
                                    <?
                                }
                                else if ($arr['vk'] == 3)
                                {
                                    ?>
                                    <select name="sk[<?=$arr['pernr']?>]" >
                                        <option value="4" <?=($arr['sk'] == 4)?'selected':''?>>Beklenen Düzeyin Çok Üstü</option>
                                        <option value="3" <?=($arr['sk'] == 3)?'selected':''?>>Beklenen Düzeyin Üstü</option>
                                        <option value="2" <?=($arr['sk'] == 2)?'selected':''?>>Beklenen Düzey</option>
                                    </select>    
                                    <?
                                }
                                else if ($arr['vk'] == 4)
                                {
                                    ?>
                                    <select name="sk[<?=$arr['pernr']?>]" >
                                        <option value="4" <?=($arr['sk'] == 4)?'selected':''?>>Beklenen Düzeyin Çok Üstü</option>
                                        <option value="3" <?=($arr['sk'] == 3)?'selected':''?>>Beklenen Düzeyin Üstü</option>
                                    </select>    
                                    <?
                                }
                                else if ($iptal)
                                {
                                    ?>
                                    <select name="sk[<?=$arr['pernr']?>]" >
                                        <option value="4" <?=($arr['sk'] == 4)?'selected':''?>>Beklenen Düzeyin Çok Üstü</option>
                                        <option value="3" <?=($arr['sk'] == 3)?'selected':''?>>Beklenen Düzeyin Üstü</option>
                                        <option value="2" <?=($arr['sk'] == 2)?'selected':''?>>Beklenen Düzey</option>
                                        <option value="1" <?=($arr['sk'] == 1)?'selected':''?>>Beklenen Düzeyin Altı</option>
                                    </select>       
                                    <?
                                }
                                ?>
                            </td>
                        </tr>
                        <?
                    }
                    ?>
                </table>
                <?
                if ($who == 'kademelendiren')
                {
                    if ($degisiklik && $iptal)
                    {
                        ?>
                        <input type="hidden" name="yil" value="<?=$yil?>"/>
                        <input type="hidden" name="donem" value="<?=$donem?>"/>
                        <input type="hidden" name="havuz_id" value="<?=$havuz_id?>"/>
                        <button class="btn btn-block btn-primary">Kaydet</button>  
                        <?
                    }
                }
                ?>
            </form>
            
            
        </div>
    </div>
    
    

<script language="javascript" type="text/javascript">  
    $( function() {
        $( "#tabs_k" ).tabs();
    });
    
    jQuery(function($) {
        var index = 'qpsstats-active-tab';
        //  Define friendly data store name
        var dataStore = window.sessionStorage;
        var oldIndex = 0;
        //  Start magic!
        try {
            // getter: Fetch previous value
            oldIndex = dataStore.getItem(index);
        } catch(e) {}

        $( "#tabs_k" ).tabs({
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

<?=$footer2?>