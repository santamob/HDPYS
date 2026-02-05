<?php




if ($arr['ft_id'] == 1)
{
    ?>
    <!-- Veri güncelleme modal -->
    <div class="modal fade" id="gosterge_detay_modal_<?=$arr['g_id']?>" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">
                        Gösterge Güncelle
                    </h4>
                    <button type="button" class="close" 
                        data-dismiss="modal">
                            <span aria-hidden="true">&times;</span>
                            <span class="sr-only">Kapat</span>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="modal-body">
                    <form method="POST" action="<?=base_url()?>ikyp/gosterge_kaydet2/" autocomplete="off">
                        <input type="hidden" name="g_id" value="<?=$arr['g_id']?>">
                        <input type="hidden" name="ft_id" value="1">
                        <div class="form-group">
                            <div class="form-row">
                                <div class="col-md-6">
                                    <label for="">Gösterge Adı *</label>
                                    <input type="text" name="g_adi" id="g_adi_n" class="form-control" placeholder="Gösterge Adı" value="<?=$arr['g_adi']?>">
                                </div>
                                <div class="col-md-6">
                                    <label for="">Yıllık Periyod *</label>
                                    <select class=" form-control" name="periyod" id="periyod_n" style="width: 100%;">
                                        <option value="" <?=($arr['periyod'] == "")?'selected':''?>>Seçiniz</option>
                                        <option value="1" <?=($arr['periyod'] == 1)?'selected':''?>>Yıllık</option>
                                        <option value="2" <?=($arr['periyod'] == 2)?'selected':''?>>6 Aylık</option>
                                        <option value="3" <?=($arr['periyod'] == 3)?'selected':''?>>4 Aylık</option>
                                        <option value="4" <?=($arr['periyod'] == 4)?'selected':''?>>3 Aylık</option>
                                        <option value="6" <?=($arr['periyod'] == 6)?'selected':''?>>2 Aylık</option>
                                        <option value="12" <?=($arr['periyod'] == 12)?'selected':''?>>1 Aylık</option>
                                    </select>  
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="form-row">
                                <div class="col-md-6">
                                    <label for="">Gösterge Tanımı *</label>
                                    <textarea name="g_text" id="g_text_n" rows="2" class="form-control"><?=$arr['g_text']?></textarea>
                                </div>
                                <div class="col-md-6">
                                    <label for="">Gösterge Açıklaması *</label>
                                    <textarea name="g_desc" id="g_desc_n" rows="2" class="form-control"><?=$arr['g_desc']?></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="form-row">
                                <div class="col-md-4">
                                    <label for="">Planlanan Tanımı *</label>
                                    <input type="text" name="planlanan_text" id="planlanan_text_n" class="form-control" placeholder="Planlanan Tanımı" value="<?=$arr['planlanan_text']?>">
                                </div>
                                <div class="col-md-4">
                                    <label for="">Gerçekleşen Tanımı *</label>
                                    <input type="text" name="gerceklesen_text" id="gerceklesen_text_n" class="form-control" placeholder="Gerçekleşen Tanımı" value="<?=$arr['gerceklesen_text']?>">
                                </div>
                                <div class="col-md-4">
                                    <label for="">Sonuç Tanımı *</label>
                                    <input type="text" name="sonuc_text" id="sonuc_text_n" class="form-control" placeholder="Sonuç Tanımı" value="<?=$arr['sonuc_text']?>">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="form-row">
                                <div class="col-md-4">
                                    <label for="">Veri Kaynağı *</label>
                                    <select class=" form-control" name="veri_kaynagi" id="veri_kaynagi_n" style="width: 100%;">
                                        <option value="">Seçiniz</option>
                                        <?
                                        foreach ($veri_kaynagi as $key2=>$arr2)
                                        {
                                            ?>
                                            <option value="<?=$arr2['id']?>" <?=($arr2['id'] == $arr['veri_kaynagi'])?'selected':''?>><?=$arr2['text']?></option>
                                            <?
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="">Veri Hesaplama *</label>
                                    <select class=" form-control" name="veri_hesaplama" id="veri_hesaplama_n" style="width: 100%;">
                                        <option value="">Seçiniz</option>
                                        <?
                                        foreach ($veri_hesaplama as $key2=>$arr2)
                                        {
                                            ?>
                                            <option value="<?=$arr2['id']?>" <?=($arr2['id'] == $arr['veri_hesaplama'])?'selected':''?>><?=$arr2['text']?></option>
                                            <?
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="">Değerlendirme Türü *</label>
                                    <select class=" form-control" name="degerlendirme_turu" id="degerlendirme_turu_n" style="width: 100%;">
                                        <option value="">Seçiniz</option>
                                        <?
                                        foreach ($degerlendirme_turu as $key2=>$arr2)
                                        {
                                            ?>
                                            <option value="<?=$arr2['id']?>" <?=($arr2['id'] == $arr['degerlendirme_turu'])?'selected':''?>><?=$arr2['text']?></option>
                                            <?
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>


                        <?php foreach($arr['kademeler'] as $key => $kademe){ ?>
                            <div class="form-group">
                                <div class="form-row kademler_n">
                                    <div class="col-md-6">
                                        <label for="">K<?php echo ($key+1);?> - Tanım *</label>
                                        <!-- <input type="text" name="k<?php echo ($key+1);?>_text" id="k<?php echo ($key+1);?>_text_n" class="form-control" placeholder="Kademe Tanımı" value="<?=$kademe['kademe_text']?>"> -->
                                        <input type="text" name="k_text[]" id="k<?php echo ($key+1);?>_text_n" class="form-control" placeholder="Kademe Tanımı" value="<?=$kademe['kademe_text']?>">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="">K<?php echo ($key+1);?> - AS (%) *</label>
                                        <!-- <input type="text" name="k<?php echo ($key+1);?>_as" id="k<?php echo ($key+1);?>_as_n" class="form-control" placeholder="AS" value="<?=$kademe['kademe_as']?>"> -->
                                        <input type="text" name="k_as[]" id="k<?php echo ($key+1);?>_as_n" class="form-control" placeholder="AS" value="<?=$kademe['kademe_as']?>">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="">K<?php echo ($key+1);?> - ÜS (%) *</label>
                                        <!-- <input type="text" name="k<?php echo ($key+1);?>_us" id="k<?php echo ($key+1);?>_us_n" class="form-control" placeholder="ÜS" value="<?=$kademe['kademe_us']?>"> -->
                                        <input type="text" name="k_us[]" id="k<?php echo ($key+1);?>_us_n" class="form-control" placeholder="ÜS" value="<?=$kademe['kademe_us']?>">
                                    </div>
                                </div>
                            </div> 

                        <?php  }?>
                        



                        <!-- <div class="form-group">
                            <div class="form-row kademler_n">
                                <div class="col-md-6">
                                    <label for="">K2 - Tanım *</label>
                                    <input type="text" name="k2_text" id="k2_text_n" class="form-control" placeholder="Kademe Tanımı" value="<?=$arr['k2_text']?>">
                                </div>
                                <div class="col-md-3">
                                    <label for="">K2 - AS (%)</label>
                                    <input type="text" name="k2_as" id="k2_as_n" class="form-control" placeholder="AS" value="<?=$arr['k2_as']?>">
                                </div>
                                <div class="col-md-3">
                                    <label for="">K2 - ÜS (%)</label>
                                    <input type="text" name="k2_us" id="k2_us_n" class="form-control" placeholder="ÜS" value="<?=$arr['k2_us']?>">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="form-row kademler_n">
                                <div class="col-md-6">
                                    <label for="">K3 - Tanım *</label>
                                    <input type="text" name="k3_text" id="k3_text_n" class="form-control" placeholder="Kademe Tanımı" value="<?=$arr['k3_text']?>">
                                </div>
                                <div class="col-md-3">
                                    <label for="">K3 - AS (%)</label>
                                    <input type="text" name="k3_as" id="k3_as_n" class="form-control" placeholder="AS" value="<?=$arr['k3_as']?>">
                                </div>
                                <div class="col-md-3">
                                    <label for="">K3 - ÜS (%)</label>
                                    <input type="text" name="k3_us" id="k3_us_n" class="form-control" placeholder="ÜS" value="<?=$arr['k3_us']?>">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="form-row kademler_n">
                                <div class="col-md-6">
                                    <label for="">K4 - Tanım *</label>
                                    <input type="text" name="k4_text" id="k4_text_n" class="form-control" placeholder="Kademe Tanımı" value="<?=$arr['k4_text']?>">
                                </div>
                                <div class="col-md-3">
                                    <label for="">K4 - AS (%)</label>
                                    <input type="text" name="k4_as" id="k4_as_n" class="form-control" placeholder="AS" value="<?=$arr['k4_as']?>">
                                </div>
                                <div class="col-md-3">
                                    <label for="">K4 - ÜS (%)</label>
                                    <input type="text" name="k4_us" id="k4_us_n" class="form-control" placeholder="ÜS" value="<?=$arr['k4_us']?>">
                                </div>
                            </div>
                        </div>
                         -->
                        <p class="text-muted">* işaretli alanlar zorunludur.</p>  
                        <button type="submit" class="btn btn-primary btn-block" onclick="">Kaydet</button>
                    </form>
                </div>
            </div>
        </div>
    </div>  
    <!-- /.Veri güncelleme modal -->
    <?
}
else if ($arr['ft_id'] == 2)
{
    ?>
    <!-- Veri güncelleme modal -->
    <div class="modal fade" id="gosterge_detay_modal_<?=$arr['g_id']?>" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">
                        Gösterge Güncelle
                    </h4>
                    <button type="button" class="close" 
                        data-dismiss="modal">
                            <span aria-hidden="true">&times;</span>
                            <span class="sr-only">Kapat</span>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="modal-body">
                    <form method="POST" action="<?=base_url()?>ikyp/gosterge_kaydet2/" autocomplete="off">
                        <input type="hidden" name="g_id" value="<?=$arr['g_id']?>">
                        <input type="hidden" name="ft_id" value="2">
                        <div class="form-group">
                            <div class="form-row">
                                <div class="col-md-6">
                                    <label for="">Gösterge Adı *</label>
                                    <input type="text" name="g_adi" id="g_adi_n" class="form-control" placeholder="Gösterge Adı" value="<?=$arr['g_adi']?>">
                                </div>
                                <div class="col-md-6">
                                    <label for="">Yıllık Periyod *</label>
                                    <select class=" form-control" name="periyod" id="periyod_n" style="width: 100%;">
                                        <option value="" <?=($arr['periyod'] == "")?'selected':''?>>Seçiniz</option>
                                        <option value="1" <?=($arr['periyod'] == 1)?'selected':''?>>Yıllık</option>
                                        <option value="2" <?=($arr['periyod'] == 2)?'selected':''?>>6 Aylık</option>
                                        <option value="3" <?=($arr['periyod'] == 3)?'selected':''?>>4 Aylık</option>
                                        <option value="4" <?=($arr['periyod'] == 4)?'selected':''?>>3 Aylık</option>
                                        <option value="6" <?=($arr['periyod'] == 6)?'selected':''?>>2 Aylık</option>
                                        <option value="12" <?=($arr['periyod'] == 12)?'selected':''?>>1 Aylık</option>
                                    </select>     
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="form-row">
                                <div class="col-md-6">
                                    <label for="">Gösterge Tanımı *</label>
                                    <textarea name="g_text" id="g_text_n" rows="2" class="form-control"><?=$arr['g_text']?></textarea>
                                </div>
                                <div class="col-md-6">
                                    <label for="">Gösterge Açıklaması *</label>
                                    <textarea name="g_desc" id="g_desc_n" rows="2" class="form-control"><?=$arr['g_desc']?></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="form-row">
                                <div class="col-md-12">
                                    <label for="">Gösterge Kategorisi *</label>
                                    <textarea name="kategori" id="kategori_n" rows="1" class="form-control"><?=$arr['kategori']?></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- <div class="form-group">
                            <div class="form-row kademler_n">
                                <div class="col-md-6">
                                    <label for="">K1 - Tanım *</label>
                                    <input type="text" name="k1_text" id="k1_text_n" class="form-control" placeholder="Kademe Tanımı" value="<?=$arr['k1_text']?>">
                                </div>
                                <div class="col-md-6">
                                    <label for="">K2 - Tanım *</label>
                                    <input type="text" name="k2_text" id="k2_text_n" class="form-control" placeholder="Kademe Tanımı" value="<?=$arr['k2_text']?>">
                                </div>
                            </div>
                        </div> -->

                        <?php foreach($arr['kademeler'] as $key => $kademe){ ?>
                            <div class="form-group">
                                <div class="form-row kademler_n">
                                    <div class="col-md-12">
                                        <label for="">K<?php echo ($key+1);?> - Tanım *</label>
                                        <input type="text" name="k_text[]" id="k1_text_n" class="form-control" placeholder="Kademe Tanımı" value="<?=$kademe['kademe_text']?>">
                                    </div>
                                </div>
                            </div> 

                        <?php  }?>

                        <!-- <div class="form-group">
                            <div class="form-row kademler_n">
                                <div class="col-md-6">
                                    <label for="">K3 - Tanım *</label>
                                    <input type="text" name="k3_text" id="k3_text_n" class="form-control" placeholder="Kademe Tanımı" value="<?=$arr['k3_text']?>">
                                </div>
                                <div class="col-md-6">
                                    <label for="">K4 - Tanım *</label>
                                    <input type="text" name="k4_text" id="k4_text_n" class="form-control" placeholder="Kademe Tanımı" value="<?=$arr['k4_text']?>">
                                </div>
                            </div>
                        </div> -->



                        
                        <div class="form-group">
                            <div class="form-row">
                                <div class="col-md-6">
                                    <label for="">Kmin Açıklama</label>
                                    <textarea name="kmin_desc" id="kmin_desc_n" rows="2" class="form-control"><?=$arr['kmin_text']?></textarea>
                                </div>
                                <div class="col-md-6">
                                    <label for="">Kmax Açıklama</label>
                                    <textarea name="kmax_desc" id="kmax_desc_n" rows="2" class="form-control"><?=$arr['kmax_text']?></textarea>
                                </div>
                            </div>
                        </div>
                        
                        <p class="text-muted">* işaretli alanlar zorunludur.</p>  
                        <button type="submit" class="btn btn-primary btn-block" onclick="">Kaydet</button>
                    </form>
                </div>
            </div>
        </div>
    </div> 
    <!-- /.Veri güncel leme modal -->    
    <?
}
else if ($arr['ft_id'] == 3)
{
    ?>
    <!-- Veri güncelleme modal -->
    <div class="modal fade" id="gosterge_detay_modal_<?=$arr['g_id']?>" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">
                        Gösterge Güncelle
                    </h4>
                    <button type="button" class="close" 
                        data-dismiss="modal">
                            <span aria-hidden="true">&times;</span>
                            <span class="sr-only">Kapat</span>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="modal-body">
                    <form method="POST" action="<?=base_url()?>ikyp/gosterge_kaydet2/" autocomplete="off">
                        <input type="hidden" name="g_id" value="<?=$arr['g_id']?>">
                        <input type="hidden" name="ft_id" value="3">
                        <div class="form-group">
                            <div class="form-row">
                                <div class="col-md-6">
                                    <label for="">Gösterge Adı *</label>
                                    <input type="text" name="g_adi" id="g_adi_n" class="form-control" placeholder="Gösterge Adı" value="<?=$arr['g_adi']?>">
                                </div>
                                <div class="col-md-6">
                                    <label for="">Yıllık Periyod *</label>
                                    <select class=" form-control" name="periyod" id="periyod_n" style="width: 100%;">
                                        <option value="" <?=($arr['periyod'] == "")?'selected':''?>>Seçiniz</option>
                                        <option value="1" <?=($arr['periyod'] == 1)?'selected':''?>>Yıllık</option>
                                        <option value="2" <?=($arr['periyod'] == 2)?'selected':''?>>6 Aylık</option>
                                        <option value="3" <?=($arr['periyod'] == 3)?'selected':''?>>4 Aylık</option>
                                        <option value="4" <?=($arr['periyod'] == 4)?'selected':''?>>3 Aylık</option>
                                        <option value="6" <?=($arr['periyod'] == 6)?'selected':''?>>2 Aylık</option>
                                        <option value="12" <?=($arr['periyod'] == 12)?'selected':''?>>1 Aylık</option>
                                    </select>     
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="form-row">
                                <div class="col-md-6">
                                    <label for="">Gösterge Tanımı *</label>
                                    <textarea name="g_text" id="g_text_n" rows="2" class="form-control"><?=$arr['g_text']?></textarea>
                                </div>
                                <div class="col-md-6">
                                    <label for="">Gösterge Açıklaması *</label>
                                    <textarea name="g_desc" id="g_desc_n" rows="2" class="form-control"><?=$arr['g_desc']?></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="form-row">
                                <div class="col-md-12">
                                    <label for="">Gösterge Kategorisi *</label>
                                    <textarea name="kategori" id="kategori_n" rows="1" class="form-control"><?=$arr['kategori']?></textarea>
                                </div>
                            </div>
                        </div>

                        <?php foreach($arr['kademeler'] as $key => $kademe){ ?>
                            <div class="form-group">
                                <div class="form-row kademler_n">
                                    <div class="col-md-12">
                                        <label for="">K<?php echo ($key+1);?> - Tanım *</label>
                                        <input type="text" name="k_text[]" id="k1_text_n" class="form-control" placeholder="Kademe Tanımı" value="<?=$kademe['kademe_text']?>">
                                    </div>
                                </div>
                            </div> 

                        <?php  }?>

                        <!-- <div class="form-group">
                            <div class="form-row kademler_n">
                                <div class="col-md-6">
                                    <label for="">K3 - Tanım *</label>
                                    <input type="text" name="k3_text" id="k3_text_n" class="form-control" placeholder="Kademe Tanımı" value="<?=$arr['k3_text']?>">
                                </div>
                                <div class="col-md-6">
                                    <label for="">K4 - Tanım *</label>
                                    <input type="text" name="k4_text" id="k4_text_n" class="form-control" placeholder="Kademe Tanımı" value="<?=$arr['k4_text']?>">
                                </div>
                            </div>
                        </div> -->



                        
                        <div class="form-group">
                            <div class="form-row">
                                <div class="col-md-6">
                                    <label for="">Kmin Açıklama</label>
                                    <textarea name="kmin_desc" id="kmin_desc_n" rows="2" class="form-control"><?=$arr['kmin_text']?></textarea>
                                </div>
                                <div class="col-md-6">
                                    <label for="">Kmax Açıklama</label>
                                    <textarea name="kmax_desc" id="kmax_desc_n" rows="2" class="form-control"><?=$arr['kmax_text']?></textarea>
                                </div>
                            </div>
                        </div>

                        <p class="text-muted">* işaretli alanlar zorunludur.</p>  
                        <button type="submit" class="btn btn-primary btn-block" onclick="">Kaydet</button>
                    </form>
                </div>
            </div>
        </div>
    </div>  
    <!-- /.Veri güncelleme modal -->    
    <?
}
else if ($arr['ft_id'] == 4)
{
    ?>
    <!-- Veri güncelleme modal -->
    <div class="modal fade" id="gosterge_detay_modal_<?=$arr['g_id']?>" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">
                        Gösterge Güncelle
                    </h4>
                    <button type="button" class="close" 
                        data-dismiss="modal">
                            <span aria-hidden="true">&times;</span>
                            <span class="sr-only">Kapat</span>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="modal-body">
                    <form method="POST" action="<?=base_url()?>ikyp/gosterge_kaydet2/" autocomplete="off">
                        <input type="hidden" name="g_id" value="<?=$arr['g_id']?>">
                        <input type="hidden" name="ft_id" value="4">
                        <div class="form-group">
                            <div class="form-row">
                                <div class="col-md-6">
                                    <label for="">Gösterge Adı *</label>
                                    <input type="text" name="g_adi" id="g_adi_n" class="form-control" placeholder="Gösterge Adı" value="<?=$arr['g_adi']?>">
                                </div>
                                <div class="col-md-6">
                                    <label for="">Yıllık Periyod *</label>
                                    <select class=" form-control" name="periyod" id="periyod_n" style="width: 100%;">
                                        <option value="" <?=($arr['periyod'] == "")?'selected':''?>>Seçiniz</option>
                                        <option value="1" <?=($arr['periyod'] == 1)?'selected':''?>>Yıllık</option>
                                        <option value="2" <?=($arr['periyod'] == 2)?'selected':''?>>6 Aylık</option>
                                        <option value="3" <?=($arr['periyod'] == 3)?'selected':''?>>4 Aylık</option>
                                        <option value="4" <?=($arr['periyod'] == 4)?'selected':''?>>3 Aylık</option>
                                        <option value="6" <?=($arr['periyod'] == 6)?'selected':''?>>2 Aylık</option>
                                        <option value="12" <?=($arr['periyod'] == 12)?'selected':''?>>1 Aylık</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="form-row">
                                <div class="col-md-12">
                                    <label for="">Gösterge Tanımı *</label>
                                    <textarea name="g_text" id="g_text_n" rows="2" class="form-control"><?=$arr['g_text']?></textarea>
                                </div>
                            </div>
                        </div>
                        
                        <p class="text-muted">* işaretli alanlar zorunludur.</p>  
                        <button type="submit" class="btn btn-primary btn-block" onclick="">Kaydet</button>
                    </form>
                </div>
            </div>
        </div>
    </div>  
    <!-- /.Veri güncelleme modal -->    
    <?
}
else if ($arr['ft_id'] == 5)
{
    ?>
    <!-- Veri güncelleme modal -->
    <div class="modal fade" id="gosterge_detay_modal_<?=$arr['g_id']?>" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">
                        Gösterge Güncelle
                    </h4>
                    <button type="button" class="close" 
                        data-dismiss="modal">
                            <span aria-hidden="true">&times;</span>
                            <span class="sr-only">Kapat</span>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="modal-body">
                    <form method="POST" action="<?=base_url()?>ikyp/gosterge_kaydet2/" autocomplete="off">
                        <input type="hidden" name="g_id" value="<?=$arr['g_id']?>">
                        <input type="hidden" name="ft_id" value="5">
                        <div class="form-group">
                            <div class="form-row">
                                <div class="col-md-6">
                                    <label for="">Gösterge Adı *</label>
                                    <input type="text" name="g_adi" id="g_adi_n" class="form-control" placeholder="Gösterge Adı" value="<?=$arr['g_adi']?>">
                                </div>
                                <div class="col-md-6">
                                    <label for="">Yıllık Periyod *</label>
                                    <select class=" form-control" name="periyod" id="periyod_n" style="width: 100%;">
                                        <option value="" <?=($arr['periyod'] == "")?'selected':''?>>Seçiniz</option>
                                        <option value="1" <?=($arr['periyod'] == 1)?'selected':''?>>Yıllık</option>
                                        <option value="2" <?=($arr['periyod'] == 2)?'selected':''?>>6 Aylık</option>
                                        <option value="3" <?=($arr['periyod'] == 3)?'selected':''?>>4 Aylık</option>
                                        <option value="4" <?=($arr['periyod'] == 4)?'selected':''?>>3 Aylık</option>
                                        <option value="6" <?=($arr['periyod'] == 6)?'selected':''?>>2 Aylık</option>
                                        <option value="12" <?=($arr['periyod'] == 12)?'selected':''?>>1 Aylık</option>
                                    </select>    
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="form-row">
                                <div class="col-md-12">
                                    <label for="">Gösterge Tanımı *</label>
                                    <textarea name="g_text" id="g_text_n" rows="2" class="form-control"><?=$arr['g_text']?></textarea>
                                </div>
                            </div>
                        </div>
                        
                        <p class="text-muted">* işaretli alanlar zorunludur.</p>  
                        <button type="submit" class="btn btn-primary btn-block" onclick="">Kaydet</button>
                    </form>
                </div>
            </div>
        </div>
    </div>  
    <!-- /.Veri güncelleme modal -->    
    <?
}
      











?>