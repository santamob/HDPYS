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
                    <th colspan="<?php echo count($gradationData["data"]);?>">Toplam Kişi Sayısı : <?=$kisi_sayisi?></th>
                </tr>
                <tr>
                    <th colspan="<?php echo count($gradationData["data"]);?>" class="text-center">Olması Gereken Dağılım</th>
                </tr>

                <tr>
                <?php
                    foreach($gradationData["data"] as $grade){
                        $lev =$grade["level"]; 
                        echo   "<td class='bg-info' style='text-align:center;'>$lev</td>";
                    }
                ?>
                </tr>

                <tr>
                    <?php
                        $count= count($gradationData["data"]);
                        if($gradationData["free"]){
                            $count = count($gradationData["data"]);
                            echo "<td colspan='$count' style='text-align:center;'>Serbest</td>";
                        }
                        else if($gradationData["free"]  == false) {
                            foreach($gradationData["data"] as $grade){
                                // $is_here = array_search($grade->merge_flag, array_column($gradationData["data"], 'merge_flag'));
                                // if($is_here)
                                //var_dump($grade);
                                $val = $grade["value"];
                                echo "<td colspan='2' style='text-align:center;'>$val</td>";
                            }
                        }
                   
                    ?>
                </tr>

                <tr>
                    <?php
                        $count = count($md);
                        $count_grade = count($gradationData["data"]);
                        $colspan = ($count !=0 ) ? $count : $count_grade;
                    ?>
                    <th colspan="<?php echo $count_grade;?>" class="text-center">Mevcut Dağılım</th>
                </tr>
                <tr>
                    <?
                    if ($iptal) // İptal edilebiliyorsa kademelendirme başlamış demektir.
                    {
                        foreach($md as $key=>$value){
                        ?>
                        <td class="text-center"><?=$value?></td>
                        
                        <!-- <td class="text-center"><?=$md['kisi_sayisi_ba']?></td>    
                        <td class="text-center"><?=$md['kisi_sayisi_b']?></td>
                        <td class="text-center"><?=$md['kisi_sayisi_bu']?></td>
                        <td class="text-center"><?=$md['kisi_sayisi_bcu']?></td> -->
                        <?
                        }
                    }
                    else
                    {
                        foreach($md as $key=>$value){
                            ?>
                            <td class="text-center">-</td>
                            
                            <!-- <td class="text-center"><?=$md['kisi_sayisi_ba']?></td>    
                            <td class="text-center"><?=$md['kisi_sayisi_b']?></td>
                            <td class="text-center"><?=$md['kisi_sayisi_bu']?></td>
                            <td class="text-center"><?=$md['kisi_sayisi_bcu']?></td> -->
                            <?
                            }
                    }
                    ?>
                </tr>
                <?
                if ($who == 'kademelendiren')
                {
                    ?>
                    <tr>
                        <?php
                        //$count = count($gradationData);
                        $count_grade = count($gradationData["data"]);
                        //echo $count_grade;
                        ?>
                        <td colspan="<?php echo $count_grade;?>">
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
            <form method="POST" enctype="multipart/form-data" action="<?=base_url()?>d_donemleri/kademelendirmeyi_kaydet/" >
                <table class="table table-responsive">
                    <tr>
                        <th>Ad Soyad</th>
                        <th >Pozisyon</th>
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
                        <!-- $start && !$iptal -->
                        <!--($degisiklik && $iptal)-->
                        <?php if ($degisiklik && $iptal && $this->session->userdata('pernr') == 100003){ ?>
                            <th>Kademelendirme Yönetim Onayı</th>
                        <?php }?>
                        
                    </tr>
                    <?
                    foreach ($siralama as $key=>$arr)
                    {
                        $color = "";
                        if ($arr['sk'] != $arr['vk'])
                        {
                            if(!is_null($arr['vk'])){
                                $color = "gray";
                            }
                           
                        }
                        
                        // $cls = "";
                        // if ($arr['sk'] == 1)
                        // {
                        //     $cls = "bg-danger";
                        // }
                        // else if ($arr['sk'] == 2)
                        // {
                        //     $cls = "bg-warning";
                        // }
                        // else if ($arr['sk'] == 3)
                        // {
                        //     $cls = "bg-info";
                        // }
                        // else if ($arr['sk'] == 4)
                        // {
                        //     $cls = "bg-success";
                        // }
                        // else
                        // {
                        //     if ($arr['vk'] == 1)
                        //     {
                        //         $cls = "bg-danger";
                        //     }
                        //     else if ($arr['vk'] == 2)
                        //     {
                        //         $cls = "bg-warning";
                        //     }
                        //     else if ($arr['vk'] == 3)
                        //     {
                        //         $cls = "bg-info";
                        //     }
                        //     else if ($arr['vk'] == 4)
                        //     {
                        //         $cls = "bg-success";
                        //     }
                        // }
                        
                        ?>
                        <tr class="<?=$cls?> small">
                            <td ><?=$ad_soyad[$arr['pernr']]?></td>
                            <td ><?=$pozisyon[$arr['pernr']]?></td>
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
                                        <a href='<?=base_url()?>d_donemleri/form_goruntule/<?=$form_id[$arr['pernr']]['1']?>/<?=$arr['pernr']?>/<?=$yil?>/<?=$donem?>/1' target="_blank" class='btn btn-primary btn-sm' style='color: #FFF'>Formu Gör</a>    
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
                                    <a href='<?=base_url()?>d_donemleri/form_goruntule/<?=$form_id[$arr['pernr']]['2']?>/<?=$arr['pernr']?>/<?=$yil?>/<?=$donem?>/2' target="_blank" class='btn btn-primary btn-sm' style='color: #FFF'>Formu Gör</a>
                                    <?
                                }
                                ?>
                            </td>
                            <td><?=$arr['tp']?></td>
                            <td style="background-color: <?=$color?>;">
                            <!-- Sistemin önerdiği kademe -->
                                <?
                                // if ($arr['vk'] == 1)
                                // {
                                //     echo "Beklenen Düzeyin Altı";
                                // }
                                // else if ($arr['vk'] == 2)
                                // {
                                //     echo "Beklenen Düzey";
                                // }
                                // else if ($arr['vk'] == 3)
                                // {
                                //     echo "Beklenen Düzeyin Üstü";
                                // }
                                // else if ($arr['vk'] == 4)
                                // {
                                //     echo "Beklenen Düzeyin Çok Üstü";
                                // }

                                
                               
                                $keyx = array_search($arr["vk"], array_column($gradationData["data"], 'id'));
                                if(!is_null($arr["vk"])){
                                    echo $gradationData["data"][$keyx]["level"];
                                }
                                else if($iptal && is_null($arr["vk"])){
                                    echo "Serbest";
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
                                <!-- yeni kademe -->
                                

                                <?php
                                    $prev = 0;
                                    $next = 0;
                                    $current = 0;
                                    $select = "";
                                    $max_kademe = max(array_keys($gradationData["data"]));
                                    $min_kademe = min(array_keys($gradationData["data"]));
                                    if(!is_null($arr["vk"])){
                                        $pernr = $arr['pernr'];
                                        $select .= "<select  name='sk[$pernr]'>";
                                        $value = array_search($arr["sk"], array_column($gradationData["data"], 'id'));
                                        
                                       
                                        if($gradationData["data"][$value]["merged"] === true){
                                            $value = array_search($arr["sk"], array_column($gradationDataForMerged["data"], 'id'));
                                            //var_dump($value);
                                            foreach($gradationDataForMerged["data"] as $gd){
                                                
                                                //$is_selected = ($value == $gd["value"]) ? "selected" : "";
                                                //$select .= "<option value={$gd['id']}  $is_selected>".$gd['level']."</option>";
                                                $select .= "<option value={$gd['id']}>".$gd['level']."</option>";
                                            }
                                            $select .= "</select>";
                                            echo $select;
                                           
                                        } 
                                        else{

                                            if($value == $max_kademe)
                                            {
                                                $value_minus = $value - 1;
                                                $is_selected = ($value == $max_kademe) ? "selected" : "";
                                                $value_id = $gradationData["data"][$value]["id"];
                                                $value_minus_id = $gradationData["data"][$value_minus]["id"];

                                                $select .= "<option value='$value_id'  $is_selected>".$gradationData["data"][$value]["level"]."</option>";
                                                $select .= "<option value='$value_minus_id' >".$gradationData["data"][$value_minus]["level"]."</option>";
                                            }
                                            else if($value == $min_kademe)
                                            {
                                                $value_plus = $value + 1;
                                                $value_id = $gradationData["data"][$value]["id"];
                                                $value_plus_id = $gradationData["data"][$value_plus]["id"];
                                                
                                                $is_selected = ($value == $max_kademe) ? "selected" : "";
                                                $select .= "<option value='$value_id' $is_selected>".$gradationData["data"][$value]["level"]."</option>";
                                                $select .= "<option value='$value_plus_id'>".$gradationData["data"][$value_plus]["level"]."</option>";
                                            }
                                            else
                                            {
                                                $prev = $value - 1;
                                                $current = $value;
                                                $next = $value + 1;
                                                $prev_id = $gradationData["data"][$prev]["id"];
                                                $current_id = $gradationData["data"][$current]["id"];
                                                $next_id = $gradationData["data"][$next]["id"];
                                                
                                                $is_selected = ($value == $current) ? "selected" : "";
                                                $select .= "<option value='$prev_id' >".$gradationData["data"][$prev]["level"]."</option>";
                                                $select .= "<option value='$current_id'  $is_selected >".$gradationData["data"][$current]["level"]."</option>";
                                                $select .= "<option value='$next_id' >".$gradationData["data"][$next]["level"]."</option>";
                                            }
                                        
                                            $select .= "</select>";
                                            echo $select;

                                        }

                                        
                                
                                    } 
                                    else if($iptal && is_null($arr["sk"])){
                                        ?>
                                    
                                            <select name="sk[<?=$arr['pernr']?>]">
                                            <?php
                                                foreach($gradationData["data"] as $gr){
                                            ?>
                                                <option value="<?php echo $gr["id"]?>"<?=($arr['sk'] == $gr["id"])?'selected':''?>><?php echo $gr["level"];?> </option>
                                            <?php
                                                }
                                            ?>
                                            </select> 
                                        <?
                                       
                                    } 
                                    else if ($iptal){
                                        ?>
                                    
                                            <select name="sk[<?=$arr['pernr']?>]">
                                            <?php
                                                foreach($gradationData["data"] as $gr){
                                            ?>
                                                <option value="<?php echo $gr["id"]?>"<?=($arr['sk'] == $gr["id"])?'selected':''?>><?php echo $gr["level"];?> </option>
                                            <?php
                                                }
                                            ?>
                                            </select> 
                                        <?
                                    }
                                    
                                
                                
                                ?>



                               
                                
                               
                            </td>
                            <?php
                                //if ($start && !$iptal){
                                  
                                if ($degisiklik && $iptal && $this->session->userdata('pernr') == 100003){
                            ?>
                                <td>
                                   
                                       
                                    <select name="kyo[<?=$arr['pernr']?>]">
                                        <option value="" selected>Kademe seçmek için tıklayınız</option>
                                        <?php
                                            foreach($gradationData["data"] as $level){
                                                if(!is_null($arr["kyo"])){
                                                    $arr["sk"] = $arr["kyo"];
                                                }
                                        ?>  
                                            <option value="<?php echo $level["id"];?>" <?=($arr['sk'] == $level["id"])?'selected':''?>><?php echo $level["level"];?> </option>
                                        <?php
                                            }
                                        ?>
                                    </select> 
                                      
                                        
                                    <div class="input-group mt-3">
                                        <!-- <div class="custom-file">
                                            <input type="file" name="attachment[<?=$arr['pernr']?>]" class="custom-file-input input-group">
                                            <label class="custom-file-label" for="inputGroupFile01">Açıklama Ekleyiniz</label>
                                        </div> -->

                                        <div class="form-group">
                                            <!-- <label for="exampleFormControlFile1">Açıklama Ekleyiniz</label> -->
                                            <input type="file" name="attachment[<?=$arr['pernr']?>]" class="form-control-file">
                                        </div>
                                    </div>
                                        

                                       
                                </td>
                            <?php
                                }
                            ?>



                        </tr>
                        <?
                    }
                    ?>
                </table>
                <?
                if (($who == 'kademelendiren') ||  ($this->session->userdata('pernr') == 100003))
                {
                    if (($degisiklik && $iptal) || ($this->session->userdata('pernr') == 100003))
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

    $(document).ready(function () {
        $('.custom-file-input').on('change', function (e) {
           e.target.nextElementSibling.innerHTML = e.target.files[0].name;
        });
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