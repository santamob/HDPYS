<?=$header?>

<div class="content-wrapper">

  <div class="container-fluid">

    <!-- Breadcrumbs -->
    <ol class="breadcrumb">
      <li class="breadcrumb-item">
        <a href="<?=base_url()?>">Anasayfa</a>
      </li>
      <li class="breadcrumb-item">
        <a href="<?=base_url()?>ikyp/donem_listesi/">Dönem Listesi</a>
      </li>
      <li class="breadcrumb-item active">IK Yönetim Paneli - Organizasyon Şeması <?=$yil?> - <?=$donem?> (<?=$donem_ack?>)</li>
    </ol>
    
    <?
    
    if ($this->session->userdata('message'))
    {
        echo $this->session->userdata('message');
    }
    ?>
    
    <div id="alert_div">

    </div>
    
    <div class="card">
        <div class="card-header">
            <button class="btn btn-primary" data-toggle="modal" data-target="#yeni_kisi_modal" title="Yeni"><i class="fa fa-fw fa-plus"></i></button>
            <a class="btn btn-success" href="<?=base_url()?>download_excel/org_semasi/<?=$yil?>/<?=$donem?>" title="Excel"><i class="fa fa-fw fa-file-excel-o"></i></a>
        </div>
        <div class="card-body">
            <div id="tabs">
                <ul>
                  <li><a href="#tabs-1">Tablo Görünümü</a></li>
                  <li><a href="#tabs-2">Ağaç Görünümü</a></li>
                </ul>
                <div id="tabs-1">
                    <table id="table1" cellpadding="0" cellspacing="0" class='margin_bot' width="100%">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Yıl</th>
                                <th>Dönem</th>
                                <th>Per. Nr.</th>
                                <th>Ad Soyad</th>
                                <th>Poz. Id</th>
                                <th>Poz. Tnm.</th>
                                <th>Org. Id</th>
                                <th>Org. Tnm.</th>
                                <th>Yon. Per. Nr.</th>
                                <th>Yon. Ad Soyad</th>
                                <?
                                foreach ($form_tipleri as $key=>$arr)
                                {
                                    ?>
                                    <th><?=$arr['ft_text']?></th>    
                                    <?
                                }
                                ?>
                                <th>Kademelendirme Havuzu</th>
                                <th>Kademelendirecek Kişi</th>
                                <th>İşlem</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?
                            if (is_array($sema_tablo))
                            {
                                foreach ($sema_tablo as $key=>$arr)
                                {
                                    ?>
                                    <tr>
                                        <td>
                                            <input type='checkbox' name='per_list[]' class='per_list' value='<?=$arr['pernr']?>'>
                                        </td>
                                        <td><?=$arr['yil']?></td>
                                        <td><?=$arr['donem']?> (<?=$donem_ack?>)</td>
                                        <td><?=$arr['pernr']?></td>
                                        <td><?=$arr['ad_soyad']?></td>
                                        <td><?=$arr['poz_id']?></td>
                                        <td><?=$arr['poz_text']?></td>
                                        <td><?=$arr['org_id']?></td>
                                        <td><?=$arr['org_text']?></td>
                                        <td><?=$arr['ust_pernr']?></td>
                                        <td><?=$arr['ust_ad_soyad']?></td>
                                        <?
                                        foreach ($form_tipleri as $key1=>$arr1)
                                        {
                                            ?>
                                            <td><?=$form_atamalari[$arr['pernr']][$arr1['id']]?></td>    
                                            <?
                                        }
                                        ?>
                                        <td><?=$kademelendirme_atamalari[$arr['pernr']]?></td>
                                        <td><?=$kademelendirme_atamalari2[$arr['pernr']]?></td>
                                        <td>
                                            <button class="btn btn-primary" data-toggle="modal" data-target="#kisi_guncelle_modal_<?=$arr['id']?>" title="Güncelle"><i class="fa fa-fw fa-refresh"></i></button>
                                            <button class="btn btn-danger" data-toggle="modal" data-target="#kisi_sil_modal_<?=$arr['id']?>" title="Sil"><i class="fa fa-fw fa-trash"></i></button>
                                        </td>
                                    </tr>    
                                    <?
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                    
                    <button class="btn btn-default" onclick="tumunu_sec();" title="Tümünü Seç"><i class="fa fa-fw fa-check-square-o"></i></button>
                    <button class="btn btn-default" onclick="temizle();" title="Temizle" ><i class="fa fa-fw fa-square-o"></i></button>
                    <button class="btn btn-default" data-toggle="modal" data-target="#form_ata_modal" title="Seçililere Form Ata"><i class="fa fa-fw fa-file"></i></button>
                    <button class="btn btn-default" data-toggle="modal" data-target="#kademelendirme_modal" title="Kademelendirme Havuzu Seç"><i class="fa fa-fw fa-sort-numeric-desc"></i></button>
                    
                </div>
                <div id="tabs-2">
                    <?=$sema?>
                </div>
            </div>
        </div>
        
    </div>
    
    <!-- Form atama modal -->
    <div class="modal fade" id="form_ata_modal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">
                        Seçililere Form Ata
                    </h4>
                    <button type="button" class="close" 
                       data-dismiss="modal">
                           <span aria-hidden="true">&times;</span>
                           <span class="sr-only">Kapat</span>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="modal-body">
                    <form method="POST" action="<?=base_url()?>ikyp/form_ata/"  autocomplete="off">
                        <input type='hidden' name='kayitlar' class='kayitlar' />
                        <input type='hidden' name='yil' value="<?=$yil?>" />
                        <input type='hidden' name='donem' value="<?=$donem?>" />
                        <?
                        foreach ($form_tipleri as $key=>$arr)
                        {
                            ?>
                            <div class="form-group">
                                <div class="">
                                    <label for=""><?=$arr['ft_text']?></label>
                                    <select class="js-example-theme-single form-control" name="form_id[<?=$arr['id']?>]" id="" style="width: 100%;">
                                        <option value="">Form Yok</option>
                                        <?
                                        foreach ($formlar as $key1=>$arr1)
                                        {
                                            if ($arr1['ft_id'] == $arr['id'])
                                            {
                                                ?>
                                                <option value="<?=$arr1['form_id']?>"><?=$arr1['form_adi']?></option>    
                                                <?
                                            }
                                        }
                                        ?>
                                    </select>        
                                </div> 
                            </div>    
                            <?
                        }
                        ?>
                        <button type="submit" class="btn btn-primary btn-block" onclick="form_ata_kontrol()">Kaydet</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- /.Form atama modal -->
    
    <!-- Kademelendirme modal -->
    <div class="modal fade" id="kademelendirme_modal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">
                        Kademelendirme Havuzu Seç
                    </h4>
                    <button type="button" class="close" 
                       data-dismiss="modal">
                           <span aria-hidden="true">&times;</span>
                           <span class="sr-only">Kapat</span>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="modal-body">
                    <form method="POST" action="<?=base_url()?>ikyp/havuz_ata/"  autocomplete="off">
                        <input type='hidden' name='kayitlar' class='kayitlar' />
                        <input type='hidden' name='yil' value="<?=$yil?>" />
                        <input type='hidden' name='donem' value="<?=$donem?>" />
                        <div class="form-group">
                            <label for="">Havuz Adı *</label>
                            <select class="js-example-theme-single form-control" name="havuz_id" style="width: 100%;">
                                <option value="">Form Yok</option>
                                <?
                                foreach ($havuzlar as $key1=>$arr1)
                                {
                                    ?>
                                    <option value="<?=$arr1['havuz_id']?>"><?=$arr1['havuz_text']?></option>      
                                    <?
                                }
                                ?>
                            </select>  
                        </div>   
                        <div class="form-group">
                            <label for="">Kademelendirecek Kişi *</label>
                            <select class="js-example-theme-single form-control" name="k_pernr" style="width: 100%;">
                                <option value="">Form Yok</option>
                                <?
                                foreach ($kisiler as $key1=>$val1)
                                {
                                    ?>
                                    <option value="<?=$key1?>"><?=$val1?></option>      
                                    <?
                                }
                                ?>
                            </select>  
                        </div>
                        <button type="submit" class="btn btn-primary btn-block" onclick="havuz_ata_kontrol()">Kaydet</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- /.Kademelendirme modal -->
    
    
    <!-- Yeni kisi ekleme modal -->
    <div class="modal fade" id="yeni_kisi_modal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">
                        Yeni Personel
                    </h4>
                    <button type="button" class="close" 
                       data-dismiss="modal">
                           <span aria-hidden="true">&times;</span>
                           <span class="sr-only">Kapat</span>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="modal-body">
                    <form method="POST" action="<?=base_url()?>ikyp/per_kaydet/"  autocomplete="off">
                        <input type="hidden" name="yil" value="<?=$yil?>">
                        <input type="hidden" name="donem" value="<?=$donem?>">
                        <div class="form-group">
                            <div class="form-row">
                                <div class="col-md-6">
                                    <label for="">Pernr *</label>
                                    <input type="text" name="pernr" id="pernr_n" class="form-control" placeholder="Personel Numarası" value="">
                                </div>
                                <div class="col-md-6">
                                    <label for="">Ad Soyad *</label>
                                    <input type="text" name="ad_soyad" id="ad_soyad_n" class="form-control" placeholder="Ad Soyad" value="">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="form-row">
                                <div class="col-md-6">
                                    <label for="">Poz. Id *</label>
                                    <input type="text" name="poz_id" id="poz_id_n" class="form-control" placeholder="Sap Pozisyon Id" value="">
                                </div>
                                <div class="col-md-6">
                                    <label for="">Poz. Tnm. *</label>
                                    <input type="text" name="poz_text" id="poz_text_n" class="form-control" placeholder="Sap Pozisyon Tanımı" value="">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="form-row">
                                <div class="col-md-6">
                                    <label for="">Org. Id *</label>
                                    <input type="text" name="org_id" id="org_id_n" class="form-control" placeholder="Sap Organizasyon Id" value="">
                                </div>
                                <div class="col-md-6">
                                    <label for="">Org. Tnm. *</label>
                                    <input type="text" name="org_text" id="org_text_n" class="form-control" placeholder="Sap Organizasyon Tanımı" value="">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="form-row">
                                <div class="col-md-6">
                                    <label for="">Yon. Per. Nr. *</label>
                                    <input type="text" name="ust_pernr" id="ust_pernr_n" class="form-control" placeholder="Yönetici Personel Numarası" value="">
                                </div>
                                <div class="col-md-6">
                                    <label for="">Yon. Ad Soyad *</label>
                                    <input type="text" name="ust_ad_soyad" id="ust_ad_soyad_n" class="form-control" placeholder="Yönetici Ad Soyad" value="">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Yönetici Mi ?</label>
                            <input type="radio" name="yonetici_kontrol" value="X" />Evet
                            <input type="radio" name="yonetici_kontrol" value="" />Hayır
                        </div>
                        <p class="text-muted">* işaretli alanlar zorunludur.</p>  
                        <button type="submit" class="btn btn-primary btn-block" onclick="">Kaydet</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- /.Yeni kişi ekleme modal -->
    
    <?
    if (is_array($sema_tablo))
    {
        foreach ($sema_tablo as $key=>$arr)
        {
            ?>
            <!-- kisi güncelleme modal -->
            <div class="modal fade" id="kisi_guncelle_modal_<?=$arr['id']?>" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title" id="myModalLabel">
                                Personel Güncelle
                            </h4>
                            <button type="button" class="close" 
                               data-dismiss="modal">
                                   <span aria-hidden="true">&times;</span>
                                   <span class="sr-only">Kapat</span>
                            </button>
                        </div>

                        <!-- Modal Body -->
                        <div class="modal-body">
                            <form method="POST" action="<?=base_url()?>ikyp/per_kaydet/"  autocomplete="off">
                                <input type="hidden" name="yil" value="<?=$yil?>">
                                <input type="hidden" name="donem" value="<?=$donem?>">
                                <input type="hidden" name="guncellenecek_id" value="<?=$arr['id']?>">
                                <div class="form-group">
                                    <div class="form-row">
                                        <div class="col-md-6">
                                            <label for="">Pernr *</label>
                                            <div><?=$arr['pernr']?></div>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="">Ad Soyad *</label>
                                            <div><?=$arr['ad_soyad']?></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="form-row">
                                        <div class="col-md-6">
                                            <label for="">Poz. Id *</label>
                                            <input type="text" name="poz_id" id="poz_id_n" class="form-control" placeholder="Sap Pozisyon Id" value="<?=$arr['poz_id']?>">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="">Poz. Tnm. *</label>
                                            <input type="text" name="poz_text" id="poz_text_n" class="form-control" placeholder="Sap Pozisyon Tanımı" value="<?=$arr['poz_text']?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="form-row">
                                        <div class="col-md-6">
                                            <label for="">Org. Id *</label>
                                            <input type="text" name="org_id" id="org_id_n" class="form-control" placeholder="Sap Organizasyon Id" value="<?=$arr['org_id']?>">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="">Org. Tnm. *</label>
                                            <input type="text" name="org_text" id="org_text_n" class="form-control" placeholder="Sap Organizasyon Tanımı" value="<?=$arr['org_text']?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="form-row">
                                        <div class="col-md-6">
                                            <label for="">Yon. Per. Nr. *</label>
                                            <input type="text" name="ust_pernr" id="ust_pernr_n" class="form-control" placeholder="Yönetici Personel Numarası" value="<?=$arr['ust_pernr']?>">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="">Yon. Ad Soyad *</label>
                                            <input type="text" name="ust_ad_soyad" id="ust_ad_soyad_n" class="form-control" placeholder="Yönetici Ad Soyad" value="<?=$arr['ust_ad_soyad']?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Yönetici Mi ?</label>
                                    <input type="radio" name="yonetici_kontrol" value="X" <?=($arr['yonetici_kontrol'] == 'X')?'checked':''?> />Evet
                                    <input type="radio" name="yonetici_kontrol" value=""  <?=($arr['yonetici_kontrol'] != 'X')?'checked':''?> />Hayır
                                </div>
                                <p class="text-muted">* işaretli alanlar zorunludur.</p>  
                                <button type="submit" class="btn btn-primary btn-block" onclick="">Kaydet</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.kişi güncelleme modal -->
            
            <!-- Kişi sil modal -->
            <div class="modal fade" id="kisi_sil_modal_<?=$arr['id']?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="myModalLabel">Personel Sil</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <?=$yil?> - <?=$donem?> (<?=$donem_ack?>) döneminden <?=$arr['ad_soyad']?> personelini siliyorsunuz. Bu işlemi
                            yapmadan önce personele bağlı astların yönetici bilgisini güncellemelisiniz. Devam etmek istediğinizden emin misiniz ?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Hayır</button>
                            <form method="POST" action="<?=base_url()?>ikyp/per_sil/"  autocomplete="off">
                                <input type="hidden" name="yil" value="<?=$arr['yil']?>">
                                <input type="hidden" name="donem" value="<?=$arr['donem']?>">
                                <input type="hidden" name="silinecek_id" value="<?=$arr['id']?>">
                                <input type="hidden" name="pernr" value="<?=$arr['pernr']?>">
                                <button type="submit" class="btn btn-primary" onclick="">Evet</button>
                            </form>
                        </div>
                      </div>
                </div>
            </div>
            <!-- /.Kişi sil modal -->
            
            <?
        }
    }
    ?>
    

<script language="javascript" type="text/javascript">  
    $( function() {
        $( "#tabs" ).tabs();
    });
    
    var table1_Props =  {
                    col_0: "none",
                    col_1: "select",
                    col_2: "select",
                    col_18: "none",
                    display_all_text: " Seçiniz ",  
                    sort_select: true,
                    paging: true,  
                    paging_length: 10,  
                    rows_counter: true,  
                    rows_counter_text: "Satır Sayısı:",  
                    btn_reset: true,  
                    loader: true,  
                    help_instructions: false,
                    loader_text: "Filtering data...",  
                    results_per_page: ['',[10,20,50,100,200,1000]],  
                    btn_next_page_html: '<a href="javascript:;" style="margin:3px;">Sonraki ></a>',  
                    btn_prev_page_html: '<a href="javascript:;" style="margin:3px;">< Önceki</a>',  
                    btn_last_page_html: '<a href="javascript:;" style="margin:3px;"> Son >|</a>',  
                    btn_first_page_html: '<a href="javascript:;" style="margin:3px;"><| İlk</a>',
                    /*** Extensions manager ***/
                    extensions: { 
                                /*** Columns Visibility Manager extension load ***/	
                                name:['ColsVisibility'], 
                                src:['TableFilter/TFExt_ColsVisibility/TFExt_ColsVisibility.js'], 
                                description:[''], 
                                initialize:[function(o){o.SetColsVisibility();}] 
                        },
					
                    /*** Columns Visibility Manager extension properties ***/
                    showHide_cols_at_start: [3,5,7,8,9],
                    showHide_cols_text: 'Hücreler: ',
                    btn_showHide_cols_text: "Göster&#9660",
                    showHide_enable_tick_all: true
                };  
    var tf1 = setFilterGrid("table1", table1_Props); 

    
$(document).ready(function() {
    $(".js-example-theme-single").select2({
     theme: "classic"
   });
});

function ac_kapa(org)
{
    jQuery("."+org+" > ul").toggle();
}

function temizle()
{
    $('.per_list').prop('checked', false);
}

function tumunu_sec()
{
    $('.per_list:visible').prop('checked', true);
}

function form_ata_kontrol()
{
    if (jQuery('.per_list').is(':checked'))
    {
        var kayitlar = "" ;
        var i = 0;
        jQuery(".per_list:checked").each(function(){
            if (i == 0)
            {
                kayitlar = $(this).val();
            }
            else
            {
                kayitlar = kayitlar + ',' + $(this).val();
            }
            i++;
        });
        jQuery('.kayitlar').val(kayitlar);
    }
}

function havuz_ata_kontrol()
{
    if (jQuery('.per_list').is(':checked'))
    {
        var kayitlar = "" ;
        var i = 0;
        jQuery(".per_list:checked").each(function(){
            if (i == 0)
            {
                kayitlar = $(this).val();
            }
            else
            {
                kayitlar = kayitlar + ',' + $(this).val();
            }
            i++;
        });
        jQuery('.kayitlar').val(kayitlar);
    }
}
</script>

<?=$footer?>