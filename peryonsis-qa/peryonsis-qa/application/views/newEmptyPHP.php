<?=$header?>

<div class="content-wrapper">

  <div class="container-fluid">

    <!-- Breadcrumbs -->
    <ol class="breadcrumb">
      <li class="breadcrumb-item">
        <a href="<?=base_url()?>">Anasayfa</a>
      </li>
      <li class="breadcrumb-item active">IK Yönetim Paneli - Göstergeler</li>
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
            <a class="btn btn-success" href="<?=base_url()?>download_excel/gostergeler/" title="Excel"><i class="fa fa-fw fa-file-excel-o"></i></a>
        </div>
        <div class="card-body">
            <table id="table1" cellpadding="0" cellspacing="0" class='margin_bot' width="100%">
                <thead>
                    <tr>
                        <th>Form Tipi</th>
                        <th>Gös. Adı</th>
                        <th>Yıllık Periyod</th>
                        <th>Gös. Tn.</th>
                        <th>Gös. Açk.</th>
                        <th>Veri Kaynağı</th>
                        <th>Veri Hesaplama</th>
                        <th>Değerlendirme Türü</th>
                        <th>Durum</th>
                        <th>İşlem</th>
                    </tr>
                </thead>
                <tbody>
                    <?
                    if (is_array($gostergeler))
                    {
                        foreach ($gostergeler as $key=>$arr)
                        {
                            ?>
                            <tr>
                                <td><?=$arr['ft_id']?> (<?=$arr['ft_text']?>)</td>
                                <td><?=$arr['g_adi']?></td>
                                <td><?=$arr['periyod']?></td>
                                <td><?=$arr['g_text']?></td>
                                <td><?=$arr['g_desc']?></td>
                                <td><?=$arr['veri_kaynagi_text']?></td>
                                <td><?=$arr['veri_hesaplama_text']?></td>
                                <td><?=$arr['degerlendirme_turu_text']?></td>
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
                                    <button class="btn btn-primary" data-toggle="modal" data-target="#gosterge_detay_modal_<?=$arr['g_id']?>" title="Güncelle"><i class="fa fa-fw fa-refresh"></i></button>
                                    <button class="btn btn-warning" data-toggle="modal" data-target="#gosterge_kopyala_modal_<?=$arr['g_id']?>" title="Kopyala"><i class="fa fa-fw fa-copy"></i></button>
                                    <button class="btn btn-danger" data-toggle="modal" data-target="#gosterge_pasife_al_<?=$arr['g_id']?>" title="Pasife Al"><i class="fa fa-fw fa-trash"></i></button>
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
    
    <!-- Yeni gösterge ekleme modal -->
    <div class="modal fade" id="yeni_gosterge_modal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">
                        Gösterge
                    </h4>
                    <button type="button" class="close" 
                       data-dismiss="modal">
                           <span aria-hidden="true">&times;</span>
                           <span class="sr-only">Kapat</span>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="modal-body">
                    <div class="form-group">
                        <label for="">Form Tipi *</label>
                        <select class="form-control" name="ft_id" id="ft_id_n" style="width: 100%;" onchange="ft_change();">
                            <option value="">Seçiniz</option>
                            <?
                            foreach ($form_tipleri as $key=>$arr)
                            {
                                if ($arr['id'] > 4)
                                {
                                    continue;
                                }
                                ?>
                                <option value="<?=$arr['id']?>"><?=$arr['ft_text']?></option>
                                <?
                            }
                            ?>
                        </select>  
                    </div>
                    
                    <!--Hedef göstergeleri -->
                    <form method="POST" action="<?=base_url()?>ikyp/gosterge_kaydet/" autocomplete="off" id="ft_1" style="display: none">
                        <input type="hidden" name="ft_id" value="1"/>
                        <div class="form-group">
                            <div class="form-row">
                                <div class="col-md-6">
                                    <label for="">Gösterge Adı *</label>
                                    <input type="text" name="g_adi" id="g_adi_n" class="form-control" placeholder="Gösterge Adı" value="">
                                </div>
                                <div class="col-md-6">
                                    <label for="">Yıllık Periyod *</label>
                                    <select class=" form-control" name="periyod" id="periyod_n" style="width: 100%;">
                                        <option value="">Seçiniz</option>
                                        <option value="1">Yıllık</option>
                                        <option value="2">6 Aylık</option>
                                        <option value="3">4 Aylık</option>
                                        <option value="4">3 Aylık</option>
                                        <option value="6">2 Aylık</option>
                                        <option value="12">1 Aylık</option>
                                    </select>    
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="form-row">
                                <div class="col-md-6">
                                    <label for="">Gösterge Tanımı *</label>
                                    <textarea name="g_text" id="g_text_n" rows="2" class="form-control"></textarea>
                                </div>
                                <div class="col-md-6">
                                    <label for="">Gösterge Açıklaması *</label>
                                    <textarea name="g_desc" id="g_desc_n" rows="2" class="form-control"></textarea>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="form-row">
                                <div class="col-md-4">
                                    <label for="">Planlanan Tanımı *</label>
                                    <input type="text" name="planlanan_text" id="planlanan_text_n" class="form-control" placeholder="Planlanan Tanımı" value="">
                                </div>
                                <div class="col-md-4">
                                    <label for="">Gerçekleşen Tanımı *</label>
                                    <input type="text" name="gerceklesen_text" id="gerceklesen_text_n" class="form-control" placeholder="Gerçekleşen Tanımı" value="">
                                </div>
                                <div class="col-md-4">
                                    <label for="">Sonuç Tanımı *</label>
                                    <input type="text" name="sonuc_text" id="sonuc_text_n" class="form-control" placeholder="Sonuç Tanımı" value="">
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
                                        foreach ($veri_kaynagi as $key=>$arr)
                                        {
                                            ?>
                                            <option value="<?=$arr['id']?>"><?=$arr['text']?></option>
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
                                        foreach ($veri_hesaplama as $key=>$arr)
                                        {
                                            ?>
                                            <option value="<?=$arr['id']?>"><?=$arr['text']?></option>
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
                                        foreach ($degerlendirme_turu as $key=>$arr)
                                        {
                                            ?>
                                            <option value="<?=$arr['id']?>"><?=$arr['text']?></option>
                                            <?
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="form-row kademler_n">
                                <div class="col-md-6">
                                    <label for="">K1 - Tanım *</label>
                                    <input type="text" name="k1_text" id="k1_text_n" class="form-control" placeholder="Kademe Tanımı" value="1-Beklenen Düzeyin Altı">
                                </div>
                                <div class="col-md-3">
                                    <label for="">K1 - AS (%)</label>
                                    <input type="text" name="k1_as" id="k1_as_n" class="form-control" placeholder="AS" value="">
                                </div>
                                <div class="col-md-3">
                                    <label for="">K1 - ÜS (%)</label>
                                    <input type="text" name="k1_us" id="k1_us_n" class="form-control" placeholder="ÜS" value="">
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="form-row kademler_n">
                                <div class="col-md-6">
                                    <label for="">K2 - Tanım *</label>
                                    <input type="text" name="k2_text" id="k2_text_n" class="form-control" placeholder="Kademe Tanımı" value="2-Beklenen Düzey">
                                </div>
                                <div class="col-md-3">
                                    <label for="">K2 - AS (%)</label>
                                    <input type="text" name="k2_as" id="k2_as_n" class="form-control" placeholder="AS" value="">
                                </div>
                                <div class="col-md-3">
                                    <label for="">K2 - ÜS (%)</label>
                                    <input type="text" name="k2_us" id="k2_us_n" class="form-control" placeholder="ÜS" value="">
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="form-row kademler_n">
                                <div class="col-md-6">
                                    <label for="">K3 - Tanım *</label>
                                    <input type="text" name="k3_text" id="k3_text_n" class="form-control" placeholder="Kademe Tanımı" value="3-Beklenen Düzeyin Üstü">
                                </div>
                                <div class="col-md-3">
                                    <label for="">K3 - AS (%)</label>
                                    <input type="text" name="k3_as" id="k3_as_n" class="form-control" placeholder="AS" value="">
                                </div>
                                <div class="col-md-3">
                                    <label for="">K3 - ÜS (%)</label>
                                    <input type="text" name="k3_us" id="k3_us_n" class="form-control" placeholder="ÜS" value="">
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="form-row kademler_n">
                                <div class="col-md-6">
                                    <label for="">K4 - Tanım *</label>
                                    <input type="text" name="k4_text" id="k4_text_n" class="form-control" placeholder="Kademe Tanımı" value="4-Beklenen Düzeyin Çok Üstü">
                                </div>
                                <div class="col-md-3">
                                    <label for="">K4 - AS (%)</label>
                                    <input type="text" name="k4_as" id="k4_as_n" class="form-control" placeholder="AS" value="">
                                </div>
                                <div class="col-md-3">
                                    <label for="">K4 - ÜS (%)</label>
                                    <input type="text" name="k4_us" id="k4_us_n" class="form-control" placeholder="ÜS" value="">
                                </div>
                            </div>
                        </div>
                        
                        <p class="text-muted">* işaretli alanlar zorunludur.</p>  
                        <button type="submit" class="btn btn-primary btn-block" onclick="">Kaydet</button>
                    </form>
                    
                    <!-- Yetkinlik göstergeleri -->
                    <form method="POST" id="ft_2" action="<?=base_url()?>ikyp/gosterge_kaydet/" style="display: none">
                        <input type="hidden" name="ft_id" value="2" />
                        <div class="form-group">
                            <div class="form-row">
                                <div class="col-md-6">
                                    <label for="">Gösterge Adı *</label>
                                    <input type="text" name="g_adi" id="g_adi_n" class="form-control" placeholder="Gösterge Adı" value="">
                                </div>
                                <div class="col-md-6">
                                    <label for="">Yıllık Periyod *</label>
                                    <select class=" form-control" name="periyod" id="periyod_n" style="width: 100%;">
                                        <option value="">Seçiniz</option>
                                        <option value="1">Yıllık</option>
                                        <option value="2">6 Aylık</option>
                                    </select>    
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="form-row">
                                <div class="col-md-6">
                                    <label for="">Gösterge Tanımı *</label>
                                    <textarea name="g_text" id="g_text_n" rows="2" class="form-control"></textarea>
                                </div>
                                <div class="col-md-6">
                                    <label for="">Gösterge Açıklaması *</label>
                                    <textarea name="g_desc" id="g_desc_n" rows="2" class="form-control"></textarea>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="form-row">
                                <div class="col-md-12">
                                    <label for="">Gösterge Kategorisi *</label>
                                    <textarea name="kategori" id="kategori_n" rows="1" class="form-control"></textarea>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="form-row kademler_n">
                                <div class="col-md-6">
                                    <label for="">K1 - Tanım *</label>
                                    <input type="text" name="k1_text" id="k1_text_n" class="form-control" placeholder="Kademe Tanımı" value="">
                                </div>
                                <div class="col-md-6">
                                    <label for="">K2 - Tanım *</label>
                                    <input type="text" name="k2_text" id="k2_text_n" class="form-control" placeholder="Kademe Tanımı" value="">
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="form-row kademler_n">
                                <div class="col-md-6">
                                    <label for="">K3 - Tanım *</label>
                                    <input type="text" name="k3_text" id="k3_text_n" class="form-control" placeholder="Kademe Tanımı" value="">
                                </div>
                                <div class="col-md-6">
                                    <label for="">K4 - Tanım *</label>
                                    <input type="text" name="k4_text" id="k4_text_n" class="form-control" placeholder="Kademe Tanımı" value="">
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="form-row">
                                <div class="col-md-6">
                                    <label for="">K1 Açıklama</label>
                                    <textarea name="k1_desc" id="k1_desc_n" rows="2" class="form-control"></textarea>
                                </div>
                                <div class="col-md-6">
                                    <label for="">K4 Açıklama</label>
                                    <textarea name="k4_desc" id="k4_desc_n" rows="2" class="form-control"></textarea>
                                </div>
                            </div>
                        </div>
                        
                        <p class="text-muted">* işaretli alanlar zorunludur.</p>  
                        <button type="submit" class="btn btn-primary btn-block" onclick="">Kaydet</button>
                    </form>
                    
                    <!-- Potansiyel göstergeleri -->
                    <form method="POST" id="ft_3" action="<?=base_url()?>ikyp/gosterge_kaydet/" autocomplete="off" style="display: none">
                        <input type="hidden" name="ft_id" value="3" />
                        <div class="form-group">
                            <div class="form-row">
                                <div class="col-md-6">
                                    <label for="">Gösterge Adı *</label>
                                    <input type="text" name="g_adi" id="g_adi_n" class="form-control" placeholder="Gösterge Adı" value="">
                                </div>
                                <div class="col-md-6">
                                    <label for="">Yıllık Periyod *</label>
                                    <select class=" form-control" name="periyod" id="periyod_n" style="width: 100%;">
                                        <option value="">Seçiniz</option>
                                        <option value="1">Yıllık</option>
                                        <option value="2">6 Aylık</option>
                                    </select>    
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="form-row">
                                <div class="col-md-6">
                                    <label for="">Gösterge Tanımı *</label>
                                    <textarea name="g_text" id="g_text_n" rows="2" class="form-control"></textarea>
                                </div>
                                <div class="col-md-6">
                                    <label for="">Gösterge Açıklaması *</label>
                                    <textarea name="g_desc" id="g_desc_n" rows="2" class="form-control"></textarea>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="form-row">
                                <div class="col-md-12">
                                    <label for="">Gösterge Kategorisi *</label>
                                    <textarea name="kategori" id="kategori_n" rows="1" class="form-control"></textarea>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="form-row kademler_n">
                                <div class="col-md-6">
                                    <label for="">K1 - Tanım *</label>
                                    <input type="text" name="k1_text" id="k1_text_n" class="form-control" placeholder="Kademe Tanımı" value="">
                                </div>
                                <div class="col-md-6">
                                    <label for="">K2 - Tanım *</label>
                                    <input type="text" name="k2_text" id="k2_text_n" class="form-control" placeholder="Kademe Tanımı" value="">
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="form-row kademler_n">
                                <div class="col-md-6">
                                    <label for="">K3 - Tanım *</label>
                                    <input type="text" name="k3_text" id="k3_text_n" class="form-control" placeholder="Kademe Tanımı" value="">
                                </div>
                                <div class="col-md-6">
                                    <label for="">K4 - Tanım *</label>
                                    <input type="text" name="k4_text" id="k4_text_n" class="form-control" placeholder="Kademe Tanımı" value="">
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="form-row">
                                <div class="col-md-6">
                                    <label for="">K1 Açıklama</label>
                                    <textarea name="k1_desc" id="k1_desc_n" rows="2" class="form-control"></textarea>
                                </div>
                                <div class="col-md-6">
                                    <label for="">K4 Açıklama</label>
                                    <textarea name="k4_desc" id="k4_desc_n" rows="2" class="form-control"></textarea>
                                </div>
                            </div>
                        </div>
                        
                        <p class="text-muted">* işaretli alanlar zorunludur.</p>  
                        <button type="submit" class="btn btn-primary btn-block" onclick="">Kaydet</button>
                    </form>
                    
                    <!-- Görüşme göstergeleri -->
                    <form method="POST" id="ft_4" action="<?=base_url()?>ikyp/gosterge_kaydet/" autocomplete="off" style="display: none">
                        <input type="hidden" name="ft_id" value="4" />
                        <div class="form-group">
                            <div class="form-row">
                                <div class="col-md-6">
                                    <label for="">Gösterge Adı *</label>
                                    <input type="text" name="g_adi" id="g_adi_n" class="form-control" placeholder="Gösterge Adı" value="">
                                </div>
                                <div class="col-md-6">
                                    <label for="">Yıllık Periyod *</label>
                                    <select class=" form-control" name="periyod" id="periyod_n" style="width: 100%;">
                                        <option value="">Seçiniz</option>
                                        <option value="1">Yıllık</option>
                                        <option value="2">6 Aylık</option>
                                    </select>    
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="form-row">
                                <div class="col-md-12">
                                    <label for="">Gösterge Tanımı *</label>
                                    <textarea name="g_text" id="g_text_n" rows="2" class="form-control"></textarea>
                                </div>
                            </div>
                        </div>
                        
                        <p class="text-muted">* işaretli alanlar zorunludur.</p>  
                        <button type="submit" class="btn btn-primary btn-block" onclick="">Kaydet</button>
                    </form>
                    
                    <!-- Yönlendirme göstergeleri -->
                    <form method="POST" id="ft_5" action="<?=base_url()?>ikyp/gosterge_kaydet/" autocomplete="off" style="display: none">
                        <input type="hidden" name="ft_id" value="5" />
                        <div class="form-group">
                            <div class="form-row">
                                <div class="col-md-6">
                                    <label for="">Gösterge Adı *</label>
                                    <input type="text" name="g_adi" id="g_adi_n" class="form-control" placeholder="Gösterge Adı" value="">
                                </div>
                                <div class="col-md-6">
                                    <label for="">Yıllık Periyod *</label>
                                    <select class=" form-control" name="periyod" id="periyod_n" style="width: 100%;">
                                        <option value="">Seçiniz</option>
                                        <option value="1">Yıllık</option>
                                        <option value="2">6 Aylık</option>
                                    </select>    
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="form-row">
                                <div class="col-md-12">
                                    <label for="">Gösterge Tanımı *</label>
                                    <textarea name="g_text" id="g_text_n" rows="2" class="form-control"></textarea>
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
    <!-- /.Yeni dönem ekleme modal -->
    
    <?
    if (is_array($gostergeler))
    {
        foreach ($gostergeler as $key=>$arr)
        {
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
                                <form method="POST" action="<?=base_url()?>ikyp/gosterge_kaydet/" autocomplete="off">
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

                                    <div class="form-group">
                                        <div class="form-row kademler_n">
                                            <div class="col-md-6">
                                                <label for="">K1 - Tanım *</label>
                                                <input type="text" name="k1_text" id="k1_text_n" class="form-control" placeholder="Kademe Tanımı" value="<?=$arr['k1_text']?>">
                                            </div>
                                            <div class="col-md-3">
                                                <label for="">K1 - AS (%) *</label>
                                                <input type="text" name="k1_as" id="k1_as_n" class="form-control" placeholder="AS" value="<?=$arr['k1_as']?>">
                                            </div>
                                            <div class="col-md-3">
                                                <label for="">K1 - ÜS (%) *</label>
                                                <input type="text" name="k1_us" id="k1_us_n" class="form-control" placeholder="ÜS" value="<?=$arr['k1_us']?>">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
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
                                <form method="POST" action="<?=base_url()?>ikyp/gosterge_kaydet/" autocomplete="off">
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

                                    <div class="form-group">
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
                                    </div>

                                    <div class="form-group">
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
                                    </div>
                                    
                                    <div class="form-group">
                                        <div class="form-row">
                                            <div class="col-md-6">
                                                <label for="">K1 Açıklama</label>
                                                <textarea name="k1_desc" id="k1_desc_n" rows="2" class="form-control"><?=$arr['k1_desc']?></textarea>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="">K4 Açıklama</label>
                                                <textarea name="k4_desc" id="k4_desc_n" rows="2" class="form-control"><?=$arr['k4_desc']?></textarea>
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
                                <form method="POST" action="<?=base_url()?>ikyp/gosterge_kaydet/" autocomplete="off">
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

                                    <div class="form-group">
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
                                    </div>

                                    <div class="form-group">
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
                                    </div>
                                    
                                    <div class="form-group">
                                        <div class="form-row">
                                            <div class="col-md-6">
                                                <label for="">K1 Açıklama</label>
                                                <textarea name="k1_desc" id="k1_desc_n" rows="2" class="form-control"><?=$arr['k1_desc']?></textarea>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="">K4 Açıklama</label>
                                                <textarea name="k4_desc" id="k4_desc_n" rows="2" class="form-control"><?=$arr['k4_desc']?></textarea>
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
                                <form method="POST" action="<?=base_url()?>ikyp/gosterge_kaydet/" autocomplete="off">
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
                                <form method="POST" action="<?=base_url()?>ikyp/gosterge_kaydet/" autocomplete="off">
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
        }
    }
    ?>
                
    <?
    if (is_array($gostergeler))
    {
        foreach ($gostergeler as $key=>$arr)
        {
            if ($arr['ft_id'] == 1)
            {
                ?>
                <!-- Veri güncelleme modal -->
                <div class="modal fade" id="gosterge_kopyala_modal_<?=$arr['g_id']?>" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
                                <form method="POST" action="<?=base_url()?>ikyp/gosterge_kaydet/" autocomplete="off">
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

                                    <div class="form-group">
                                        <div class="form-row kademler_n">
                                            <div class="col-md-6">
                                                <label for="">K1 - Tanım *</label>
                                                <input type="text" name="k1_text" id="k1_text_n" class="form-control" placeholder="Kademe Tanımı" value="<?=$arr['k1_text']?>">
                                            </div>
                                            <div class="col-md-3">
                                                <label for="">K1 - AS (%) *</label>
                                                <input type="text" name="k1_as" id="k1_as_n" class="form-control" placeholder="AS" value="<?=$arr['k1_as']?>">
                                            </div>
                                            <div class="col-md-3">
                                                <label for="">K1 - ÜS (%) *</label>
                                                <input type="text" name="k1_us" id="k1_us_n" class="form-control" placeholder="ÜS" value="<?=$arr['k1_us']?>">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
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
                                    
                                    <p class="text-muted">* işaretli alanlar zorunludur.</p>  
                                    <button type="submit" class="btn btn-primary btn-block" onclick="">Kopyala</button>
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
                <div class="modal fade" id="gosterge_kopyala_modal_<?=$arr['g_id']?>" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
                                <form method="POST" action="<?=base_url()?>ikyp/gosterge_kaydet/" autocomplete="off">
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

                                    <div class="form-group">
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
                                    </div>

                                    <div class="form-group">
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
                                    </div>
                                    
                                    <div class="form-group">
                                        <div class="form-row">
                                            <div class="col-md-6">
                                                <label for="">K1 Açıklama</label>
                                                <textarea name="k1_desc" id="k1_desc_n" rows="2" class="form-control"><?=$arr['k1_desc']?></textarea>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="">K4 Açıklama</label>
                                                <textarea name="k4_desc" id="k4_desc_n" rows="2" class="form-control"><?=$arr['k4_desc']?></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <p class="text-muted">* işaretli alanlar zorunludur.</p>  
                                    <button type="submit" class="btn btn-primary btn-block" onclick="">Kopyala</button>
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
                <div class="modal fade" id="gosterge_kopyala_modal_<?=$arr['g_id']?>" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
                                <form method="POST" action="<?=base_url()?>ikyp/gosterge_kaydet/" autocomplete="off">
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

                                    <div class="form-group">
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
                                    </div>

                                    <div class="form-group">
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
                                    </div>
                                    
                                    <div class="form-group">
                                        <div class="form-row">
                                            <div class="col-md-6">
                                                <label for="">K1 Açıklama</label>
                                                <textarea name="k1_desc" id="k1_desc_n" rows="2" class="form-control"><?=$arr['k1_desc']?></textarea>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="">K4 Açıklama</label>
                                                <textarea name="k4_desc" id="k4_desc_n" rows="2" class="form-control"><?=$arr['k4_desc']?></textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <p class="text-muted">* işaretli alanlar zorunludur.</p>  
                                    <button type="submit" class="btn btn-primary btn-block" onclick="">Kopyala</button>
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
                <div class="modal fade" id="gosterge_kopyala_modal_<?=$arr['g_id']?>" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
                                <form method="POST" action="<?=base_url()?>ikyp/gosterge_kaydet/" autocomplete="off">
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
                                    <button type="submit" class="btn btn-primary btn-block" onclick="">Kopyala</button>
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
                <div class="modal fade" id="gosterge_kopyala_modal_<?=$arr['g_id']?>" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
                                <form method="POST" action="<?=base_url()?>ikyp/gosterge_kaydet/" autocomplete="off">
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
                                    <button type="submit" class="btn btn-primary btn-block" onclick="">Kopyala</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>  
                <!-- /.Veri güncelleme modal -->    
                <?
            }
        }
    }
    
    if (is_array($gostergeler))
    {
        foreach ($gostergeler as $key=>$arr)
        {
            ?>
            <div class="modal fade" id="gosterge_pasife_al_<?=$arr['g_id']?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="myModalLabel">Gösterge Pasife Al</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <b><?=$arr['g_adi']?></b> göstergesi pasife alınacaktır. Pasif olan göstergeler form'lara atanamaz. Devam etmek istediğinizden emin misiniz ?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Hayır</button>
                            <form method="POST" action="<?=base_url()?>ikyp/gosterge_pasife_al/">
                                <input type="hidden" name="g_id" value="<?=$arr['g_id']?>">
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
                    col_5: "select",
                    col_6: "select",
                    col_7: "select",
                    col_8: "select",
                    col_9: "none",
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

function ft_change()
{
    ft_id = jQuery("#ft_id_n").val();
    if (ft_id == 1)
    {
        jQuery("#ft_1").show();
        jQuery("#ft_2").hide();
        jQuery("#ft_3").hide();
        jQuery("#ft_4").hide();
        jQuery("#ft_5").hide();
    }
    
    if (ft_id == 2)
    {
        jQuery("#ft_2").show();
        jQuery("#ft_1").hide();
        jQuery("#ft_3").hide();
        jQuery("#ft_4").hide();
        jQuery("#ft_5").hide();
    }
    
    if (ft_id == 3)
    {
        jQuery("#ft_3").show();
        jQuery("#ft_1").hide();
        jQuery("#ft_2").hide();
        jQuery("#ft_4").hide();
        jQuery("#ft_5").hide();
    }
    
    if (ft_id == 4)
    {
        jQuery("#ft_4").show();
        jQuery("#ft_1").hide();
        jQuery("#ft_2").hide();
        jQuery("#ft_3").hide();
        jQuery("#ft_5").hide();
    }
    
    if (ft_id == 5)
    {
        jQuery("#ft_5").show();
        jQuery("#ft_1").hide();
        jQuery("#ft_2").hide();
        jQuery("#ft_3").hide();
        jQuery("#ft_4").hide();
    }
}
</script>

<?=$footer?>