<?=$header?>
<div class="content-wrapper">

  <div class="container-fluid">

    <!-- Breadcrumbs -->
    <ol class="breadcrumb">
      <li class="breadcrumb-item">
        <a href="<?=base_url()?>">Anasayfa</a>
      </li>
      <li class="breadcrumb-item active">Kademelendirme İşlemleri - <?=$yil?> - <?=$donem_ack?></li>
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
          <li><a href="#tabs_k-1">Kademelendirmem Gerekenler</a></li>
          <li><a href="#tabs_k-2">Astlarımın Yaptığı Kademelendirmeler</a></li>
        </ul>
        <div id='tabs_k-1'>
            <?
            if (is_array($havuz_id['idler']))
            {
                ?>
                <div class="table-responsive">
                    <table class="table table-bordered display" id="" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Kademelendirme Havuzu</th>
                                <th>Kademelendiren</th>
                                <th>İşlem</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?
                            foreach ($havuz_id['idler'] as $key=>$val)
                            {
                                ?>
                                <tr>
                                    <td><?=$havuz_md[$val]?></td>
                                    <td><?=$havuz_id['kademelendiren_text'][$val]?></td>
                                    <td><a href='<?=base_url()?>d_donemleri/kademelendirme_detay/<?=$yil?>/<?=$donem?>/<?=$val?>' target="" class='btn btn-primary' style='color: #FFF'>Görüntülemek İçin Tıklayınız</a></td>
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
                                <th>Kademelendiren</th>
                                <th>Onaylayan</th>
                                <th>Durum</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><?=$ad_soyad[$this->session->userdata('pernr')]?></td>
                                <td><?=$ust_ad_soyad[$this->session->userdata('pernr')]?></td>
                                <td>
                                    <?
                                    if ($ks_kontrol[$val] == 2)
                                    {
                                        echo "Onaya gönderebilmeniz için kademelendirme işlemlerini tamamlamalısınız.";
                                    }
                                    else if ($ks_kontrol['astlar'] == 2)
                                    {
                                        echo "Onaya gönderebilmeniz için önce astlarınızın yaptığı kademelendirmeleri onaylamalısınız.";
                                    }
                                    else
                                    {
                                        echo $od_ast['onay_text'];
                                        if ($od_ast['onay_durum'] == 2 || $od_ast['onay_durum'] == 3)
                                        {
                                            ?>
                                            <form method="POST" action="<?=base_url()?>d_donemleri/onay_islemleri">
                                                <input type="hidden" name="yil" value="<?=$yil?>">
                                                <input type="hidden" name="donem" value="<?=$donem?>">
                                                <input type="hidden" name="ft_id" value="99">
                                                <input type="hidden" name="onay_durum" value="4">
                                                <input type="hidden" name="degerlendiren" value="<?=$this->my_encrypt_url->encode($this->session->userdata('pernr'))?>" />
                                                <input type="hidden" name="onaylayan" value="<?=$this->my_encrypt_url->encode($ust_pernr[$this->session->userdata('pernr')])?>" />
                                                <input type="submit" class="btn btn-primary" value="Onaya Gönder" />
                                            </form>    
                                            <?
                                        }
                                        else if ($od_ast['onay_durum'] == 4)
                                        {
                                            ?>
                                            <form method="POST" action="<?=base_url()?>d_donemleri/onay_islemleri">
                                                <input type="hidden" name="yil" value="<?=$yil?>">
                                                <input type="hidden" name="donem" value="<?=$donem?>">
                                                <input type="hidden" name="ft_id" value="99">
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
                        </tbody>
                    </table>
                </div>
                <?
            }
            else
            {
                ?>
                <span>Kademelendirmeniz gereken havuz bulunmuyor.</span>    
                <?
            }
            ?>
        </div>
        <div id='tabs_k-2'>
            <?
            if (is_array($havuz_id2['idler']))
            {
                ?>
                <div class="table-responsive">
                    <table class="table table-bordered display" id="" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Kademelendirme Havuzu</th>
                                <th>Kademelendiren</th>
                                <th>İşlem</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?
                            foreach ($havuz_id2['idler'] as $key=>$val)
                            {
                                ?>
                                <tr>
                                    <td><?=$havuz_md[$val]?></td>
                                    <td><?=$havuz_id2['kademelendiren_text'][$val]?></td>
                                    <td><a href='<?=base_url()?>d_donemleri/kademelendirme_detay/<?=$yil?>/<?=$donem?>/<?=$val?>' target="_blank" class='btn btn-primary' style='color: #FFF'>Görüntülemek İçin Tıklayınız</a></td>
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
                                <th>Kademelendiren</th>
                                <th>Onaylayan</th>
                                <th>Durum</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?
                            foreach ($havuz_id2['idler'] as $key=>$val)
                            {  
                                if (in_array($havuz_id2['kademelendiren'][$val], $kademelendiren_dizi))
                                {
                                    continue;
                                }
                                $kademelendiren_dizi[] = $havuz_id2['kademelendiren'][$val];
                                ?>
                                <tr>
                                    <td><?=$havuz_id2['kademelendiren_text'][$val]?></td>
                                    <td><?=$ust_ad_soyad[$havuz_id2['kademelendiren'][$val]]?></td>
                                    <td>
                                        <div>
                                            <?=$od_ast2[$havuz_id2['kademelendiren'][$val]]['onay_text']?>
                                        </div>
                                        <?
                                        if ($od_ast2[$havuz_id2['kademelendiren'][$val]]['onay_durum'] == 4)
                                        {
                                            ?>
                                            <form method="POST" action="<?=base_url()?>d_donemleri/onay_islemleri" style="float: left">
                                                <input type="hidden" name="yil" value="<?=$yil?>">
                                                <input type="hidden" name="donem" value="<?=$donem?>">
                                                <input type="hidden" name="ft_id" value="99">
                                                <input type="hidden" name="onay_durum" value="1">
                                                <input type="hidden" name="degerlendiren" value="<?=$this->my_encrypt_url->encode($havuz_id2['kademelendiren'][$val])?>" />
                                                <input type="hidden" name="onaylayan" value="<?=$this->my_encrypt_url->encode($ust_pernr[$havuz_id2['kademelendiren'][$val]])?>" />
                                                <input type="submit" class="btn btn-primary" value="Onayla" />
                                            </form>   
                                            <form method="POST" action="<?=base_url()?>d_donemleri/onay_islemleri" style="float: left">
                                                <input type="hidden" name="yil" value="<?=$yil?>">
                                                <input type="hidden" name="donem" value="<?=$donem?>">
                                                <input type="hidden" name="ft_id" value="99">
                                                <input type="hidden" name="onay_durum" value="2">
                                                <input type="hidden" name="degerlendiren" value="<?=$this->my_encrypt_url->encode($havuz_id2['kademelendiren'][$val])?>" />
                                                <input type="hidden" name="onaylayan" value="<?=$this->my_encrypt_url->encode($ust_pernr[$havuz_id2['kademelendiren'][$val]])?>" />
                                                <input type="submit" class="btn btn-danger" value="Red Et" />
                                            </form> 
                                            <?
                                        }
                                        else if ($od_ast2[$havuz_id2['kademelendiren'][$val]]['onay_durum'] == 1)
                                        {
                                            if ($od_ast['onay_durum'] == 3)
                                            {
                                                ?>
                                                <form method="POST" action="<?=base_url()?>d_donemleri/onay_islemleri" style="float: left">
                                                    <input type="hidden" name="yil" value="<?=$yil?>">
                                                    <input type="hidden" name="donem" value="<?=$donem?>">
                                                    <input type="hidden" name="ft_id" value="99">
                                                    <input type="hidden" name="onay_durum" value="3">
                                                    <input type="hidden" name="degerlendiren" value="<?=$this->my_encrypt_url->encode($havuz_id2['kademelendiren'][$val])?>" />
                                                    <input type="hidden" name="onaylayan" value="<?=$this->my_encrypt_url->encode($ust_pernr[$havuz_id2['kademelendiren'][$val]])?>" />
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
                            ?>
                        </tbody>
                    </table>
                </div>
                <?
            }
            else
            {
                ?>
                <span>Kademelendirmeniz gereken havuz bulunmuyor.</span>    
                <?
            }
            ?>
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

<?=$footer?>