<?=$header?>
<div class="content-wrapper">

  <div class="container-fluid">

    <!-- Breadcrumbs -->
    <ol class="breadcrumb">
      <li class="breadcrumb-item">
        <a href="<?=base_url()?>">Anasayfa</a>
      </li>
      <li class="breadcrumb-item active">IK Yönetim Paneli - Formlar</li>
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
            <button class="btn btn-primary" data-toggle="modal" data-target="#yeni_gosterge_modal" title="Yeni"><i class="fa fa-fw fa-plus"></i></button>
            <a class="btn btn-success" href="<?=base_url()?>download_excel/formlar/" title="Excel"><i class="fa fa-fw fa-file-excel-o"></i></a>
        </div>
        <div class="card-body">
            <table id="table1" cellpadding="0" cellspacing="0" class='margin_bot' width="100%">
                <thead>
                    <tr>
                        <th>Form Tipi</th>
                        <th>Form Adı</th>
                        <th>Göstergeler</th>
                        <th>Durum</th>
                        <th>İşlem</th>
                    </tr>
                </thead>
                <tbody>
                    <?
                    if (is_array($formlar))
                    {
                        foreach ($formlar as $key=>$arr)
                        {
                            ?>
                            <tr>
                                <td><?=$arr['ft_id']?> (<?=$arr['ft_text']?>)</td>
                                <td><?=$arr['form_adi']?></td>
                                <td>
                                    <?
                                    if (is_array($formlar_detay[$arr['form_id']]))
                                    {
                                        foreach ($formlar_detay[$arr['form_id']] as $key1=>$arr1)
                                        {
                                            if ($key1 > 0)
                                            {
                                                echo "<br/>";
                                            }
                                            if ($arr['ft_id'] != 4 && $arr['ft_id'] != 5)
                                            {
                                                echo $gostergeler1[$arr['ft_id']][$arr1['g_id']]." - ".$arr1['agirlik'];
                                            }
                                            else
                                            {
                                                echo $gostergeler1[$arr['ft_id']][$arr1['g_id']];
                                            }
                                        }
                                    }
                                    ?>
                                </td>
                                <td>
                                     <?
                                    if ($arr['durum'] == 1)
                                    {
                                        echo "Aktif";
                                    }
                                    else
                                    {
                                        echo "Pasif";
                                    }
                                    ?>
                                </td>
                                <td>
                                    <button class="btn btn-danger" data-toggle="modal" data-target="#form_pasife_al_<?=$arr['form_id']?>" title="Pasife Al"><i class="fa fa-fw fa-trash"></i></button>
                                </td>
                            </tr>    
                            <?
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
        
    </div>
    
    <!-- Yeni hedef formu ekleme modal -->
    <div class="modal fade" id="yeni_gosterge_modal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">
                        Yeni Form
                    </h4>
                    <button type="button" class="close" 
                       data-dismiss="modal">
                           <span aria-hidden="true">&times;</span>
                           <span class="sr-only">Kapat</span>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="modal-body">
                    <form method="POST" action="<?=base_url()?>ikyp/form_kaydet/" autocomplete="off">
                        <div class="form-group">
                            <label for="">Form Tipi *</label>
                            <select class="form-control" name="ft_id" id="ft_id_n" style="width: 100%;" onchange="ft_change();">
                                <option value="">Seçiniz</option>
                                <?
                                foreach ($form_tipleri as $key=>$arr)
                                {
                                    ?>
                                    <option value="<?=$arr['id']?>"><?=$arr['ft_text']?></option>
                                    <?
                                }
                                ?>
                            </select>  
                        </div>
                        <div class="form-group">
                            <div class="form-row">
                                <div class="col-md-12">
                                    <label for="">Form Adı *</label>
                                    <input type="text" name="form_adi" id="" class="form-control" placeholder="Form Adı" value="">
                                </div>
                            </div>
                        </div>
                        <div class="form-group" id="t_agr" style="display: none">
                            <div class="form-row">
                                <div class="col-md-12 agr">
                                    <label for="">Toplam Ağırlık</label>
                                    <input type="text" name="t_agirlik" class="form-control" placeholder="Toplam Ağırlık (%)" value="">
                                </div>
                            </div>
                        </div>
                        
                        <div id="gostergeler">
                            
                        </div>
                        
                        <input type="hidden" name="gosterge_sayac" id="gosterge_sayac" value="0">
                        <textarea style="display: none" name="gostergeler_hedef" id="gostergeler_hedef"><?=$gostergeler_hedef?></textarea>
                        <textarea style="display: none" name="gostergeler_yetkinlik" id="gostergeler_yetkinlik"><?=$gostergeler_yetkinlik?></textarea>
                        <textarea style="display: none" name="gostergeler_potansiyel" id="gostergeler_potansiyel"><?=$gostergeler_potansiyel?></textarea>
                        <textarea style="display: none" name="gostergeler_gorusme" id="gostergeler_gorusme"><?=$gostergeler_gorusme?></textarea>
                        
                        
                        
                        <a class="btn btn-success btn-block" id="g_ekle" href="javascript:Void(0)" role="button" onclick="yeni_gosterge()">Gösterge Ekle</a>
                        
                        <p class="text-muted">* işaretli alanlar zorunludur.</p>  
                        <button type="submit" class="btn btn-primary btn-block" onclick="">Kaydet</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- /.Yeni hedef formu ekleme modal -->
    
    <?
    if (is_array($formlar))
    {
        foreach ($formlar as $key=>$arr)
        {
            ?>
            <div class="modal fade" id="form_pasife_al_<?=$arr['form_id']?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="myModalLabel">Gösterge Pasife Al</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <b><?=$arr['form_adi']?></b> formu pasife alınacaktır. Pasif olan formlar personellere atanamaz. Devam etmek istediğinizden emin misiniz ?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Hayır</button>
                            <form method="POST" action="<?=base_url()?>ikyp/form_pasife_al/">
                                <input type="hidden" name="form_id" value="<?=$arr['form_id']?>">
                                <button type="submit" class="btn btn-primary" onclick="">Evet</button>
                            </form>
                        </div>
                      </div>
                </div>
            </div>     
            <?
        }
    }
    ?>
    
  </div>
  <!-- /.container-fluid -->

</div>
<!-- /.content-wrapper -->

<script language="javascript" type="text/javascript">  
    var table1_Props =  {
                    col_0: "select",
                    col_3: "select",
                    col_4: "none",
                    display_all_text: " Seçiniz ",  
                    sort_select: true,
                    paging: true,  
                    paging_length: 10,  
                    rows_counter: true,  
                    rows_counter_text: "Rows:",  
                    btn_reset: true,  
                    loader: true,  
                    help_instructions: false,
                    loader_text: "Filtering data...",  
                    results_per_page: ['# sayfa başına satır',[10,20,50]],  
                    btn_next_page_html: '<a href="javascript:;" style="margin:3px;">Sonraki ></a>',  
                    btn_prev_page_html: '<a href="javascript:;" style="margin:3px;">< Önceki</a>',  
                    btn_last_page_html: '<a href="javascript:;" style="margin:3px;"> Son >|</a>',  
                    btn_first_page_html: '<a href="javascript:;" style="margin:3px;"><| İlk</a>',  
                };  
    var tf1 = setFilterGrid("table1", table1_Props);  
    
$(document).ready(function() {
    $(".js-example-theme-single").select2({
     theme: "classic"
   });
});

function yeni_gosterge()
{
    var gosterge_sayac = jQuery("#gosterge_sayac").val();
    gosterge_sayac++;
    
    jQuery("#gosterge_sayac").val(gosterge_sayac);
    
    if (jQuery('#ft_id_n').val() == 1)
    {
        var gostergeler = jQuery("#gostergeler_hedef").val();
    }
    else if (jQuery('#ft_id_n').val() == 2)
    {
        var gostergeler = jQuery("#gostergeler_yetkinlik").val();
    }
    else if (jQuery('#ft_id_n').val() == 3)
    {
        var gostergeler = jQuery("#gostergeler_potansiyel").val();
    }
    else if (jQuery('#ft_id_n').val() == 4)
    {
        var gostergeler = jQuery("#gostergeler_gorusme").val();
    }
    else if (jQuery('#ft_id_n').val() == 5)
    {
        var gostergeler = jQuery("#gostergeler_yonlendirme").val();
    }
    else
    {
        alert("Form tipi seçmelisiniz");
        return;
    }
    
    var obj = jQuery.parseJSON(gostergeler);
    
    var yaz = "";
    yaz = yaz + '<div class="form-group gosterge" id="gosterge_'+gosterge_sayac+'">';
        yaz = yaz + '<div class="form-row">';
            yaz = yaz + '<div class="col-md-6">';
                yaz = yaz + '<label for="">Gösterge *</label>';
                yaz = yaz + '<select class="js-example-theme-single form-control g_id" name="dizi['+gosterge_sayac+'][g_id]" style="width: 100%;">';
                    yaz = yaz + '<option value="">Seçiniz</option>'
                    $.each( obj, function (key1, val1) {
                        if (key1 != '')
                        {
                            yaz = yaz + '<option value="'+key1+'">'+val1+'</option>';
                        }
                    });
                yaz = yaz + '</select>';
            yaz = yaz + '</div>';
            
            if (jQuery('#ft_id_n').val() != 4 && jQuery('#ft_id_n').val() != 5)
            {
                yaz = yaz + '<div class="col-md-4">';
                    yaz = yaz + '<label for="">Ağırlık</label>';
                    yaz = yaz + '<input type="text" name="dizi['+gosterge_sayac+'][agirlik]" id="" class="form-control agr" placeholder="Gösterge Ağırlığı (%)" value="">';
                yaz = yaz + '</div>';
                yaz = yaz + '<div>';
                    yaz = yaz + '<label for="">İşlem</label>';
                    yaz = yaz + '<div>';
                        yaz = yaz + '<a style="color: red" href="javascript:Void(0)" role="button" onclick="gosterge_sil('+gosterge_sayac+')" title="Gösterge Sil"><i class="fa fa-fw fa-trash"></i></a>';
                    yaz = yaz + '</div>';
                yaz = yaz + '</div>';
            }
            
        yaz = yaz + '</div>';
    yaz = yaz + '</div>';
    
    jQuery('#gostergeler').append(yaz);
    
    $(".js-example-theme-single").select2({
        theme: "classic"
    });
    
}

function gosterge_sil(gosterge_sayac)
{
    jQuery("#gosterge_"+gosterge_sayac).remove();
}

function ft_change()
{
    jQuery(".gosterge").remove();
    if (jQuery('#ft_id_n').val() != 4 && jQuery('#ft_id_n').val() != 5)
    {
        jQuery("#t_agr").show();
    }
    else
    {
        jQuery("#t_agr").hide();
    }
    
    if (jQuery('#ft_id_n').val() != 5)
    {
        jQuery("#g_ekle").show();
    }
    else
    {
        jQuery("#g_ekle").hide();
    }
}
</script>

<?=$footer?>