<?=$header2?>


<style>
    table.table-bordered{
    border:1px solid black;
    margin-top:20px;
  }
table.table-bordered > thead > tr > th{
    border:1px solid black;
}
table.table-bordered > tbody > tr > td{
    border:1px solid black;
}

.none_match{
    opacity: 0.25;
}

@media print {
  table{
    font-size: 10px;
  }
}
</style>
<div class="">

  <div class="container-fluid">

    <!-- Breadcrumbs -->
    <ol class="breadcrumb mt-3">
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
            <table class="table table-bordered border border-dark">
                <tr>
                    <th  class="border border-dark" colspan="<?php echo count($gradationData["data"]);?>">Toplam Kişi Sayısı : <?=$kisi_sayisi?></th>
                </tr>
                <tr>
                    <th colspan="<?php echo count($gradationData["data"]);?>" class="text-center border border-dark">Olması Gereken Dağılım</th>
                </tr>

                <tr>
                <?php
                    foreach($gradationData["data"] as $grade){
                        $lev = $grade["level"]; 
                        $color = $grade["color"];
                        echo   "<td class='bg-$color' style='text-align:center;'><b>$lev</b></td>";
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
                            

                            $data = ($gradationData["is_over_ten"]) ? $gradationData["data"] : $gradationData["data_merged"];
                            //$data = $gradationData["data"];
                            //var_dump($data);
                            foreach($data as $key => $grade){
                                $colspan = "";
                                if($grade["merged"]){
                                    $merged_values_count = $grade["merged_values_count"];
                                    $colspan = "colspan='$merged_values_count'";
                                }
                               
                                $val = $grade["value"];
                                echo "<td  $colspan style='text-align:center;'><b>$val</b></td>";
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
                    <!--<th colspan="<?php echo $count_grade;?>" class="text-center">Mevcut Dağılım</th>-->
                    <th  colspan="<?php echo $count_grade;?>" class="text-center border border-dark">Mevcut Dağılım</th>
                </tr>
                <tr>
                    <?
 
                    if ($iptal) // İptal edilebiliyorsa kademelendirme başlamış demektir.
                    {
                        
                        $count_md = count($gradationData["data"]);
                        
                       
                        // foreach($md as $key=>$value){

                        //     $colspan = "";
                           
                        //     $keyx = array_search($key, array_column($gradationData["data"], 'level'));
                        //     $color = "bg-".$gradationData["data"][$keyx]["color"];

                            

                        //     //echo $count_md."<br>";
                        //     echo $count_md. "-".$value."<br>";
                        //     $count_md = $count_md - $value;

                        //     $merged_values_count = $count_md;
                          
                        //     $colspan = "colspan='$merged_values_count'";
                            
                        //     $result =  "<b>".$key. " => ".  $value."</b>";
                        //     echo "<td  $colspan style='text-align:center' class='$color'>$result</td>";

                         
                        // }

                        $keys = array_keys($md);
                      

                        foreach($gradationData["data"] as $key=>$value){
                            $color = "bg-".$value["color"];
                            $keyx = array_search($value["level"],$keys);
                            $class2="";
                            if($keyx !== false){
                                $result =  "<b>".$value["level"]. " => ".  $md[$value["level"]]."</b>";
                            }else{
                                $result =  "<b>".$value["level"]. " =>0 </b>";
                                $class2="none_match";
                            }
                            echo "<td  $colspan style='text-align:center' class='$class2 $color'>$result</td>";

                        }



                      


                    }
                    else
                    {
                        foreach($md as $key=>$value){
                            ?>
                                <td class="text-center">-</td>
                            
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
                                    <a href='<?=base_url()?>d_donemleri/kademelendirmeyi_baslat/<?=$yil?>/<?=$donem?>/<?=$havuz_id?>' class='btn btn-primary border border-dark float-right' style='color: #FFF'>Kademelendirmeyi başlatmak için tıklayınız</a>
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
                                    <a href='<?=base_url()?>d_donemleri/kademelendirmeyi_iptal_et/<?=$yil?>/<?=$donem?>/<?=$havuz_id?>' class='btn btn-danger border border-dark float-right' style='color: #FFF'>Kademelendirmeyi iptal etmek için tıklayınız</a>
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


            <?php
             
                $is_disable = ($is_saved_before &&  $this->session->userdata('pernr') != 100003 ) ? "disabled":"";

                if (($degisiklik && $iptal) || ($this->session->userdata('pernr') == 100003)){
            ?>

                <!-- <button <?php echo  $is_disable ?>  class="btn btn-primary" onclick="myApp.printTable()">Tabloyu Pdf  Olarak Kaydet</button> -->
                <button <?php echo  $is_disable ?>  class="btn btn-primary border border-dark" onclick="generate()">PDF  Olarak Kaydet</button>

                

            <?php
                }
            ?>


            <form id="myForm" method="POST" enctype="multipart/form-data" action="<?=base_url()?>d_donemleri/kademelendirmeyi_kaydet/" >
                <table id="myTable" class="table table-bordered border border-dark mt-5">
                    <tr class="border border-dark">
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
                        
                        <?php if (!$degisiklik && $iptal && $this->session->userdata('pernr') == 100003){ ?>
                            <th>Kademelendirme Yönetim Onayı</th>
                        <?php }?>
                        
                    </tr>
                    <?
                    foreach ($siralama as $key=>$arr)
                    {
                        $cls = "";

                        if(!is_null($arr["vk"])){
                            $keyx = array_search($arr["sk"], array_column($gradationData["data"], 'id')); // 20221017 silindi
                           
                            $cls = "bg-".$gradationData["data"][$keyx]["color"];
                        }
                        
                        if ($arr['sk'] != $arr['vk'])
                        {
                            //var_dump($gradationData["data"][$keyx]);
                            //if(!$gradationData["data"][$keyx]["merged"]) {
                                if(!is_null($arr['vk'])){
                                    $color = "gray";
                                }
                            //}
                            
                           
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
                            <td  style=" <?php echo (!$degisiklik && $iptal && $this->session->userdata('pernr') == 100003)  ? 'max-width:110px;' : ''  ?>"  ><b><?=$ad_soyad[$arr['pernr']]?></b></td>
                            <td style=" <?php echo (!$degisiklik && $iptal && $this->session->userdata('pernr') == 100003)  ? 'max-width:150px;' : ''  ?>"  ><b><?=$pozisyon[$arr['pernr']]?></b></td>
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
                                        <a href='<?=base_url()?>d_donemleri/form_goruntule/<?=$form_id[$arr['pernr']]['1']?>/<?=$arr['pernr']?>/<?=$yil?>/<?=$donem?>/1' target="_blank" class='btn btn-primary btn-sm border border-dark' style='color: #FFF'>Formu Gör</a>    
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
                                    <a href='<?=base_url()?>d_donemleri/form_goruntule/<?=$form_id[$arr['pernr']]['2']?>/<?=$arr['pernr']?>/<?=$yil?>/<?=$donem?>/2' target="_blank" class='btn btn-primary btn-sm border border-dark ' style='color: #FFF'>Formu Gör</a>
                                    <?
                                }
                                ?>
                            </td>
                            <td><b><?=$arr['tp']?><b/></td>
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

                                
                               
                                $keyx = array_search($arr["vk"], array_column($gradationData["data"], 'id')); // 20221017 silindi
                                //$keyx = array_search($arr["sk"], array_column($gradationData["data"], 'id'));
                                //var_dump($gradationData["data"][$keyx]);
                                if(!is_null($arr["vk"])){
                                   
                                    $color = isset($gradationData["data"][$keyx]["color"]) ?  "bg-".$gradationData["data"][$keyx]["color"] :  "";
                                    if($gradationData["data"][$keyx]["merged"]){
                                        //$values = $gradationData["data"][$keyx]["merged_with"];
                                        $values = array_reverse($gradationData["data"][$keyx]["merged_with"]);

                                        $concat_leves = "";
                                        foreach($values as $vl){
                                            $level = array_values($vl)[0]; // level
                                            $concat_leves.= " /<br> ". $level;
                                        }
                                        $concat_leves = substr($concat_leves, 6);
                                        echo '<h6><span class="badge badge-secondary border border-dark shadow">'.$concat_leves.'</span></h6>';
                                    }
                                    else{
                                        echo '<h6><span class="badge badge-secondary border border-dark shadow">'.$gradationData["data"][$keyx]["level"].'</span></h6>';
                                    }
                                   
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
                                        $is_disable = ($is_saved_before &&  $this->session->userdata('pernr') != 100003 ) ? "disabled":"";

                                        $pernr = $arr['pernr'];
                                        $select .= "<select  $is_disable class='form-control border border-dark' name='sk[$pernr]'>";
                                        $value = array_search($arr["sk"], array_column($gradationData["data"], 'id'));
                                        
                                        // if($arr["vk"] != $arr["sk"]){
                                            
                                           
                                        //     $value = array_search($arr["vk"], array_column($gradationData["data"], 'id')); 
                                        // }
                                        // else{
                                           
                                            
                                        //     $value = array_search($arr["sk"], array_column($gradationData["data"], 'id'));
                                        // }
                                       
                                        if($gradationData["data"][$value]["merged"] === true){ // ROW MERGE İSE 
                                           
                                            //var_dump($gradationData["data"]);
                                            $value = array_search($arr["sk"], array_column($gradationData["data"], 'id'));
                                           // $value = array_search($arr["vk"], array_column($gradationData["data"], 'id')); // yeni eklendi 2022-11-01
                                           
                                            $prev = $value - 1;
                                            $current = $value;
                                            $next = $value + 1;
                                            $prev_id = $gradationData["data"][$prev]["id"];
                                            $current_id = $gradationData["data"][$current]["id"];
                                            $next_id = $gradationData["data"][$next]["id"];


                                            //var_dump($gradationData["data"][$current]["merged_with"]);
                                            foreach($gradationData["data"][$current]["for_select_merged_with"] as $grad){
                                                    $grad_id = $grad["id"];
                                                    $level = $grad["level"];
                                                    //echo  $grad_id."==". $gradationData["data"][$current]["id"]."<br>";
                                                    $is_selected = ($grad_id == $gradationData["data"][$current]["id"]) ? "selected" : "";
                                                    $select .= "<option value='$grad_id' $is_selected>".$level."</option>";
                                            }

                                            

                                            $select .= "</select>";
                                            echo $select;

                                           
                                           
                                        } 
                                       else{
                                            
                                             //$value = array_search($arr["sk"], array_column($gradationData["data"], 'id')); // yeni eklendi 2022-11-01

                                          
                                            if($value == $max_kademe)
                                            {
                                                $value_minus = $value - 1;
                                                $is_selected = ($value == $max_kademe) ? "selected" : "";
                                                $value_id = $gradationData["data"][$value]["id"];
                                                $value_minus_id = $gradationData["data"][$value_minus]["id"];

                                                $select .= "<option value='$value_id'  $is_selected>".$gradationData["data"][$value]["level"]."</option>";
                                                $select .= "<option value='$value_minus_id'>".$gradationData["data"][$value_minus]["level"]."</option>";
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

                                                // echo $value."<br>";
                                                // echo "prev-id ".$prev_id."<br>";
                                                // echo "current_id ".$current_id."<br>";
                                                // echo "next_id ".$next_id;

                                                 // sıralama vk ya göre gelmeli ama seçimler sk ile yapılacak ? 

                                                
                                                $is_selected = ($value == $current) ? "selected" : ""; //eski hali

                                               
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
                                    
                                            <select class='form-control' name="sk[<?=$arr['pernr']?>]">
                                            <?php
                                             var_dump("aaa");
                                                foreach($gradationData["data"] as $gr){
                                            ?>
                                                <option value="<?php echo $gr["id"]?>"<?=($arr['sk'] == $gr["id"])?'selected':''?>>a<?php echo $gr["level"];?> </option>
                                            <?php
                                                }
                                            ?>
                                            </select> 
                                        <?
                                       
                                    } 
                                    else if ($iptal){
                                       
                                        ?>
                                    
                                            <select class='form-control' name="sk[<?=$arr['pernr']?>]">
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
                                  
                                if (!$degisiklik && $iptal && $this->session->userdata('pernr') == 100003){
                            ?>
                                <td>
                                   
                                       
                                    <select class='form-control' name="kyo[<?=$arr['pernr']?>]">
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
                                        <?php if(!is_null($arr['attachment_path'])): ?>
                                        <div class="form-group">
                                            <!-- <label for="exampleFormControlFile1">Açıklama Ekleyiniz</label> -->
                                            <a class="btn btn-default" role="button" href="/<?php echo $arr['attachment_path'];?>" target="_blank" download> INDIR </a>
                                        </div>
                                        <?php endif; ?>
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
                        $is_disable = ($is_saved_before &&  $this->session->userdata('pernr') != 100003 ) ? "disabled":"";

                        ?>
                        
                        <input type="hidden" name="yil" value="<?=$yil?>"/>
                        <input type="hidden" name="donem" value="<?=$donem?>"/>
                        <input type="hidden" name="havuz_id" value="<?=$havuz_id?>"/>
                        <button  <?php echo $is_disable ;?> class="btn btn-block btn-primary border border-dark">Kaydet</button>  
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


    // let myApp = new function () {
    //     this.printTable = function () {
    //         //let tab = $('#myTable').html();
    //         let  tab = document.getElementById('myTable');
    //         let win = window.open('', '', 'height=700,width=700');
           
    //         //win.document.write(tab.outerHTML);


    //        // console.log(html)
    //         win.document.write( tab.outerHTML);
    //         //console.log( $('#myTable').contents())
    //         win.document.close();

            
            
    //       // win.print();
           
    //     }
    // }


    function takeScreenshot() {
        var screenshot = document.documentElement.cloneNode(true);
        //var screenshot =  document.getElementById('myTable');
        
        screenshot.style.pointerEvents = 'none';
        screenshot.style.overflow = 'hidden';
        screenshot.style.webkitUserSelect = 'none';
        screenshot.style.mozUserSelect = 'none';
        screenshot.style.msUserSelect = 'none';
        screenshot.style.oUserSelect = 'none';
        screenshot.style.userSelect = 'none';
        screenshot.dataset.scrollX = window.scrollX;
        screenshot.dataset.scrollY = window.scrollY;
        var blob = new Blob([screenshot.outerHTML], {
            //type: 'application/pdf',
           type: 'image/png'
        });

        return blob;
    }

   

    function generate() {
        window.URL = window.URL || window.webkitURL;

        window.document.title ="<?php echo $havuz_adi?>";
        window.print(window.URL.createObjectURL(takeScreenshot()));
        
    }

    

    $(document).ready(function () {
        $('.custom-file-input').on('change', function (e) {
           e.target.nextElementSibling.innerHTML = e.target.files[0].name;
        });


        $("#myForm").submit(function() {
            $message = `
                Kademelendirme işlemleri sadece bir kez gerçekleştirebilir. Kademelendirme işlemi tamamlanıp onaya gönderilmeden önce güncelleme yapmak isterseniz “ Kademelendirmeyi iptal etmek için tıklayınız” butonuna basarak kademelendirmeyi tekrar başlatabilirsiniz.
                “Kaydet” butonuna basmadan önce “PDF olarak Kaydet” butonu ile yapılan değerlendirmeleri PDF olarak kayıt edebilirsiniz.`;
            if (confirm($message)) {         
                return true;         
            } else {
                return false;
            }
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