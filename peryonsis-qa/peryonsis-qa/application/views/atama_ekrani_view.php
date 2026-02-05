<?=$header?>
<div class="content-wrapper">

  <div class="container-fluid">

    <!-- Breadcrumbs -->
    <ol class="breadcrumb">
      <li class="breadcrumb-item">
        <a href="<?=base_url()?>">Anasayfa </a>
      </li>
      <li class="breadcrumb-item active">IK Yönetim Paneli - Atama Ekranı</li>
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
            <button class="btn btn-primary" data-toggle="modal" data-target="#oneri" title="<?=$max_donem+1?> İçin Kademe Öner"><i class="fa fa-fw fa-sort-numeric-asc"></i><?=$max_donem+1?> İçin Kademe Öner</button>
            <a class="btn btn-success" href="<?=base_url()?>download_excel/atama_ekrani/" title="Excel"><i class="fa fa-fw fa-file-excel-o"></i></a>
        </div>
        <div class="card-body">
            <table id="table1" cellpadding="0" cellspacing="0" class='margin_bot' width="100%">
                <thead>
                    <tr>
                        <th colspan="4" class="text-center">
                            Personel Bilgileri
                        </th>
                        <th colspan="7" class="text-center">
                            Kademeler
                        </th>
                        <th colspan="7" class="text-center">
                            Performans Puanları
                        </th>
                        <th></th>
                    </tr>
                    <tr>
                        <th>Pernr</th>
                        <th>Ad Soyad</th>
                        <th>Poz Id</th>
                        <th>Pozisyon</th>
                        <?
                        for ($i = $yil_max; $i>=$yil_min; $i--)
                        {
                            ?>
                            <th><?=$i?></th>
                            <?
                        }
                        for ($i = $yil_max; $i>=$yil_min; $i--)
                        {
                            ?>
                            <th><?=$i?></th>
                            <?
                        }
                        ?>
                        <th>İşlem</th>
                    </tr>
                </thead>
                <tbody>
                    <?
                    if (is_array($personeller))
                    {
                        foreach ($personeller as $key=>$arr)
                        {
                            ?>
                            <tr>
                                <td><?=$arr['pernr']?></td>
                                <td><?=$arr['ad_soyad']?></td>
                                <td><?=$arr['poz_id']?></td>
                                <td><?=$arr['poz_text']?></td>
                                <?
                                for ($i = $yil_max; $i>=$yil_min; $i--)
                                {
                                    ?>
                                    <td><?=$kademe[$arr['pernr']][$i]?></td>
                                    <?
                                }
                                for ($i = $yil_max; $i>=$yil_min; $i--)
                                {
                                    ?>
                                    <td><?=$puan[$arr['pernr']][$i]?></td>
                                    <?
                                }
                                ?>
                                <td>
                                    <button class="btn btn-primary" data-toggle="modal" data-target="#per_guncelle_modal_<?=$arr['pernr']?>" title="Güncelle"><i class="fa fa-fw fa-refresh"></i></button>
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
    
    
  </div>
  <!-- /.container-fluid -->

</div>
<!-- /.content-wrapper -->

<?
if (is_array($personeller))
{
    foreach ($personeller as $key=>$arr)
    {
        ?>
        <!-- Veri güncelleme modal -->
        <div class="modal fade" id="per_guncelle_modal_<?=$arr['pernr']?>" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
                        <form method="POST" action="<?=base_url()?>atamalar/guncelle/">
                            <input type="hidden" name="pernr" value="<?=$arr['pernr']?>">
                            <input type="hidden" name="yil_max" value="<?=$yil_max?>">
                            <div class="form-group col-md-6">
                                <label for="">Pernr</label>
                                <div><?=$arr['pernr']?></div>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="">Ad Soyad</label>
                                <div><?=$arr['ad_soyad']?></div>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="">Pozisyon</label>
                                <div><?=$arr['poz_text']?></div>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="">Kademe <?=$yil_max?></label>
                                <div>
                                    <select class="form-control" name="kademe">
                                        <option value="">Seçiniz</option>
                                        <option value="TSM-C" <?=($kademe[$arr['pernr']][$yil_max] == "TSM-C")?'selected':''?>>TSM-C</option>
                                        <option value="TSM-B" <?=($kademe[$arr['pernr']][$yil_max] == "TSM-B")?'selected':''?>>TSM-B</option>
                                        <option value="TSM-A" <?=($kademe[$arr['pernr']][$yil_max] == "TSM-A")?'selected':''?>>TSM-A</option>
                                        <option value="BTM-B" <?=($kademe[$arr['pernr']][$yil_max] == "BTM-B")?'selected':''?>>BTM-B</option>
                                        <option value="BTM-A" <?=($kademe[$arr['pernr']][$yil_max] == "BTM-A")?'selected':''?>>BTM-A</option>
                                    </select>
                                </div>
                            </div>    
                            <div class="form-group col-md-6">
                                <label for="">Atama Tarihi</label>
                                <div>
                                    <input class="form-control" type="date" name="atama_tarihi" value="<?=$kademe_t[$arr['pernr']][$max_donem]?>" />
                                </div>
                            </div>    
                            <?
                            /*
                            ?>
                            <div class="col-md-12">
                                <h3>Puanlar</h3>
                            </div>
                            <?
                            for ($i = $yil_max-1; $i>=$yil_min; $i--)
                            {
                                ?>
                                <div class="form-group col-md-6">
                                    <label for=""><?=$i?></label>
                                    <div>
                                        <select class="form-control" name="puan[<?=$i?>]">
                                            <option value="">Seçiniz</option>
                                            <option value="1" <?=($puan[$arr['pernr']][$i] == "1")?'selected':''?>>1</option>
                                            <option value="2" <?=($puan[$arr['pernr']][$i] == "2")?'selected':''?>>2</option>
                                            <option value="3" <?=($puan[$arr['pernr']][$i] == "3")?'selected':''?>>3</option>
                                            <option value="4" <?=($puan[$arr['pernr']][$i] == "4")?'selected':''?>>4</option>
                                        </select>
                                    </div>
                                </div>    
                                <?
                            }*/
                            ?>
                            <button type="submit" class="btn btn-primary btn-block" onclick="">Güncelle</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>  
        <!-- /.Veri güncelleme modal -->

        <?
    }
}
?>

<!-- Öneri modal -->
<div class="modal fade" id="oneri" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel"><?=$max_donem+1?> için kademe öner</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?=$max_donem?> değerlendirme sonuçlarına göre <?=$max_donem+1?> için kademeler oluşturulacaktır. Bu işlem sırasında ilgili yıla ait kademe varsa silinecektir. 
                Devam etmek istediğinizden emin misiniz ?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Hayır</button>
                <form method="POST" action="<?=base_url()?>atamalar/oneri/">
                    <input type="hidden" name="yil" value="<?=$max_donem+1?>">
                    <button type="submit" class="btn btn-primary" onclick="">Evet</button>
                </form>
            </div>
          </div>
    </div>
</div>
<!-- /.Öneri modal -->

<script language="javascript" type="text/javascript">  
    var table1_Props =  {
                    display_all_text: " Seçiniz ",  
                    sort_select: true,
                    paging: false,  
                    paging_length: 10,  
                    rows_counter: true,  
                    rows_counter_text: "Rows:",  
                    btn_reset: true,  
                    loader: true,  
                    help_instructions: false,
                    loader_text: "Filtering data...",  
                    results_per_page: ['# sayfa başına satır',[10,20,50,100,500,1000]],  
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
</script>

<?=$footer?>