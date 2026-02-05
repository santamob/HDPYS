<?=$header?>
<div class="content-wrapper">

  <div class="container-fluid">

    <!-- Breadcrumbs -->
    <ol class="breadcrumb">
      <li class="breadcrumb-item">
        <a href="<?=base_url()?>">Anasayfa</a>
      </li>
      <li class="breadcrumb-item active">IK Yönetim Paneli - Dönem Listesi</li>
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
            <button class="btn btn-primary" data-toggle="modal" data-target="#yeni_donem_modal" title="Yeni"><i class="fa fa-fw fa-plus"></i></button>
        </div>
        <div class="card-body">
            <table id="table1" cellpadding="0" cellspacing="0" class='margin_bot' width="100%">
                <thead>
                    <tr>
                        <th>Yıl</th>
                        <th>Dönem</th>
                        <th>Başlangıç Tarihi</th>
                        <th>Bitiş Tarihi</th>
                        <th>İşlem</th>
                    </tr>
                </thead>
                <tbody>
                    <?
                    if (is_array($donemler))
                    {
                        foreach ($donemler as $key=>$arr)
                        {
                            ?>
                            <tr>
                                <td><?=$arr['yil']?></td>
                                <td><?=$arr['donem']?> (<?=$arr['donem_ack']?>)</td>
                                <td><?=tarih($arr['bat'])?></td>
                                <td><?=tarih($arr['bit'])?></td>
                                <td>
                                    <button class="btn btn-primary" data-toggle="modal" data-target="#donem_guncelle_modal_<?=$arr['id']?>" title="Güncelle"><i class="fa fa-fw fa-refresh"></i></button>
                                    <button class="btn btn-primary" data-toggle="modal" data-target="#org_guncelle_<?=$arr['id']?>" title="SAP Sisteminden Org. Bilgilerini Al"><i class="fa fa-fw fa-cloud-download"></i></button>
                                    <a href="<?=base_url()?>ikyp/organizasyon_semasi/<?=$arr['yil']?>/<?=$arr['donem']?>" class="btn btn-primary" title="Organizasyon Şeması Görüntüle"><i class="fa fa-fw fa-users"></i></a>
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
    
    <!-- Yeni dönem ekleme modal -->
    <div class="modal fade" id="yeni_donem_modal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">
                        Yeni Dönem
                    </h4>
                    <button type="button" class="close" 
                       data-dismiss="modal">
                           <span aria-hidden="true">&times;</span>
                           <span class="sr-only">Kapat</span>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="modal-body">
                    <form method="POST" action="<?=base_url()?>ikyp/donem_kaydet/">
                        <div class="form-group">
                            <label for="">Yıl *</label>
                            <select class="js-example-theme-single form-control" name="yil" id="yil_n" style="width: 100%;">
                                <option value="">Seçiniz</option>
                                <?
                                for ($yil = 2015; $yil<2031; $yil++)
                                {
                                    ?>
                                    <option value="<?=$yil?>"><?=$yil?></option>
                                    <?
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="">Dönem *</label>
                            <?
                            if (isset($donem_av))
                            {
                                ?>
                                <select class="js-example-theme-single form-control" name="donem" id="donem_n" style="width: 100%;">
                                    <option value="">Seçiniz</option>
                                    <?
                                    foreach ($donem_av as $key=>$arr)
                                    {
                                        ?>
                                        <option value="<?=$arr['donem_id']?>"><?=$arr['ack']?></option>
                                        <?
                                    }
                                    ?>
                                </select>    
                                <?
                            }
                            ?>
                        </div>
                        <div class="form-group">
                            <label for="">Form Tipleri *</label>
                            <?
                            foreach ($form_tipleri as $key1=>$arr1)
                            {
                                ?>
                                <div class="checkbox-inline">
                                    <label><input type="checkbox" name="ft[]" value="<?=$arr1['id']?>"/><?=$arr1['ft_text']?></label>  
                                </div>
                                <?
                            }
                            ?>
                        </div>
                        
                        <div class="form-group">
                            <label for="">Kademelendirme *</label>
                            <div class="radio-inline">
                                <label><input type="radio" name="kademelendirme" value="1"/>Var</label>  
                                <label><input type="radio" name="kademelendirme" value="2"/>Yok</label>  
                            </div>
                        </div>
                        
                        <div class="form-group">
                              <label for="">Başlangıç Tarihi *</label>
                              <input type="date" name="bat" id="bat_n" class="form-control" placeholder="Başlangıç Tarihi" value="">
                        </div>
                        <div class="form-group">
                              <label for="">Bitiş Tarihi *</label>
                              <input type="date" name="bit" id="bit_n" class="form-control" placeholder="Bitiş Tarihi" value="">
                        </div>
                        <p class="text-muted">* işaretli alanlar zorunludur.</p>  
                        <button type="submit" class="btn btn-primary btn-block" onclick="">Kaydet</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- /.Yeni dönem ekleme modal -->
    
    <?
    if (is_array($donemler))
    {
        foreach ($donemler as $key=>$arr)
        {
            ?>
            <!-- Veri güncelleme modal -->
            <div class="modal fade" id="donem_guncelle_modal_<?=$arr['id']?>" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title" id="myModalLabel">
                                Dönem Güncelle
                            </h4>
                            <button type="button" class="close" 
                               data-dismiss="modal">
                                   <span aria-hidden="true">&times;</span>
                                   <span class="sr-only">Kapat</span>
                            </button>
                        </div>

                        <!-- Modal Body -->
                        <div class="modal-body">
                            <form method="POST" action="<?=base_url()?>ikyp/donem_kaydet/">
                                <input type="hidden" name="donem_listesi_id" value="<?=$arr['id']?>">
                                <div class="form-group">
                                    <label for="">Yıl *</label>
                                    <div><?=$arr['yil']?></div>
                                </div>
                                <div class="form-group">
                                    <label for="">Dönem *</label>
                                    <div><?=$arr['donem_ack']?></div>
                                </div>
                                <div class="form-group">
                                    <label for="">Form Tipleri *</label>
                                    <?
                                    foreach ($form_tipleri as $key1=>$arr1)
                                    {
                                        $ft = explode(',', $arr['ft']);
                                        if (in_array($arr1['id'], $ft))
                                        {
                                            $checked = "checked";
                                        }
                                        else
                                        {
                                            $checked = "";
                                        }
                                        ?>
                                        <div class="checkbox-inline">
                                            <label><input type="checkbox" name="ft[]" value="<?=$arr1['id']?>" <?=$checked?>/><?=$arr1['ft_text']?></label>  
                                        </div>
                                        <?
                                    }
                                    ?>
                                </div>
                                <div class="form-group">
                                    <label for="">Kademelendirme *</label>
                                    <div class="radio-inline">
                                        <label><input type="radio" name="kademelendirme" value="1" <?=($arr['kademelendirme'] == 1)?'checked':''?>/>Var</label>  
                                        <label><input type="radio" name="kademelendirme" value="2" <?=($arr['kademelendirme'] == 2)?'checked':''?>/>Yok</label>  
                                    </div>
                                </div>
                                <div class="form-group">
                                      <label for="">Başlangıç Tarihi *</label>
                                      <input type="date" name="bat" id="bat_n" class="form-control" placeholder="Başlangıç Tarihi" value="<?=$arr['bat']?>">
                                </div>
                                <div class="form-group">
                                      <label for="">Bitiş Tarihi *</label>
                                      <input type="date" name="bit" id="bit_n" class="form-control" placeholder="Bitiş Tarihi" value="<?=$arr['bit']?>">
                                </div>
                                <div class="form-group">
                                    <?php $lokasyon = explode('-',$arr['lokasyon']);?>
                                    <label for="">Lokasyon *</label>
                                    <div class="radio-inline">
                                        <label><input type="checkbox" name="lokasyon[]" value="Fabrika" <?php if(in_array('Fabrika',$lokasyon)){echo 'checked';}?> />Fabrika</label>  
                                        <label><input type="checkbox" name="lokasyon[]" value="Merkez" <?php if(in_array('Merkez',$lokasyon)){echo 'checked';}?>/>Merkez</label>  
                                        <label><input type="checkbox" name="lokasyon[]" value="Saha" <?php if(in_array('Saha',$lokasyon)){echo 'checked';}?>/>Saha</label>  
                                    </div>
                                </div>
                                <p class="text-muted">* işaretli alanlar zorunludur.</p>  
                                <button type="submit" class="btn btn-primary btn-block" onclick="">Güncelle</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>  
            <!-- /.Veri güncelleme modal -->
    
            <!-- Organizasyon şeması güncelle modal -->
            <div class="modal fade" id="org_guncelle_<?=$arr['id']?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="myModalLabel">Organizasyon Şeması</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <?=$arr['yil']?> - <?=$arr['donem']?> (<?=$arr['donem_ack']?>) değerlendirme dönemi için
                            organizasyon bilgilerini SAP sisteminden alınacaktır. Bu işlem sırasında ilgili döneme ait
                            yapılan tüm işlemler (tanımlamalar, değerlendirmeler, form atamaları vb.) silinecektir. Devam etmek istediğinizden emin misiniz ?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Hayır</button>
                            <form method="POST"  id="modal_form_<?=$arr['id']?>" action="<?=base_url()?>ikyp/organizasyon_guncelle/">
                                <input type="hidden" name="yil" value="<?=$arr['yil']?>">
                                <input type="hidden" name="donem" value="<?=$arr['donem']?>">
                                <button type="button" class="btn btn-primary" data-id="org_guncelle_<?=$arr['id']?>" id="checkAgain">Evet</button>
                            </form>
                        </div>
                      </div>
                </div>
            </div>
            <!-- /.Organizasyon şeması güncelle modal -->
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
                    col_1: "select",
                    col_2: "none",
                    col_3: "none",
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


   
    $(document).on('click','#checkAgain',function(){

        var modal_id = $(this).attr("data-id");
      
        let myArray = modal_id.split("_");
        let form_id = `modal_form_${myArray[2]}`;
       
        $(`#${modal_id}`).modal('hide');

        if (confirm('Devam etmek istediğinizden emin misiniz ?')) {
            $(`#${form_id}`).submit();
        } 
        else {
            alert('İşlemi iptal ettiniz.');
            return;
        }
            
        // return;
        
    });

    

   




});
</script>

<?=$footer?>