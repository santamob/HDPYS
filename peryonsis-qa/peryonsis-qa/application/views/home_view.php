<?=$header?>
<div class="content-wrapper">

  <div class="container-fluid">

    <!-- Breadcrumbs -->
    <ol class="breadcrumb">
      <li class="breadcrumb-item">
      <?php $veriListeleme =  new Genel_model();
      $tabloAdi = $veriListeleme->perskGetir($this->session->userdata['pernr']);
      if($tabloAdi['0']){$perSk= $tabloAdi['0']['persk'];}else{$perSk=  $tabloAdi['persk'];}?>
        <a href="<?=base_url()?>">Anasayfa</a>
      </li>
    </ol>
    
    <?
    if ($this->session->userdata('message'))
    {
        echo $this->session->userdata('message');
    }
    ?>
    
    <div id="alert_div">

    </div>
    
    <div class="row">
        <?
        $tarih = date("Y-m-d");
        foreach ($donemler_header as $key=>$arr)
        {
        $lokasyon = explode('-',$arr['lokasyon']);
        $lokasyon = array_filter($lokasyon);

        if ($perSk == 1 || $perSk == 4)
    {
        $office = "Merkez";
    }
    else if ($perSk == 2 || $perSk == 5)
    {
        $office = "Fabrika";
    }
    else if ($perSk == 3 || $perSk == 6)
    {
        $office = "Saha";
    }
            if ($arr['bit'] < $tarih || sizeof($lokasyon)==0 || !in_array($office,$lokasyon))
    {
                ?>
                <div class="col-sm-4 mb-3">
                    <div class="card text-white o- hidden h-100" style="background-color: #717073">
                        <div class="card-body">
                            <div class="card-body-icon">
                              <i class="fa fa-fw fa-pencil-square"></i>
                            </div>
                            <div class="mr-5"><?=$arr['yil']?> - <?=$arr['donem_ack']?></div>
                        </div>
                        <a class="card-footer text-white clearfix small z-1" href="<?=base_url()?>d_donemleri/takvim/<?=$arr['yil']?>/<?=$arr['donem']?>">
                            <span class="float-left">Formlar İçin Tıklayınız</span>
                            <span class="float-right">
                              <i class="fa fa-angle-right"></i>
                            </span>
                        </a>
                    </div>
                </div>
                <?
            }
            else
            {
                ?>
                <div class="col-sm-4 mb-3">
                    <div class="card text-white o-hidden h-100" style="background-color: #005596">
                        <div class="card-body">
                            <div class="card-body-icon">
                              <i class="fa fa-fw fa-pencil-square"></i>
                            </div>
                            <div class="mr-5"><?=$arr['yil']?> - <?=$arr['donem_ack']?></div>
                        </div>
                        <a class="card-footer text-white clearfix small z-1" href="<?=base_url()?>d_donemleri/takvim/<?=$arr['yil']?>/<?=$arr['donem']?>">
                            <span class="float-left">Formlar İçin Tıklayınız</span>
                            <span class="float-right">
                              <i class="fa fa-angle-right"></i>
                            </span>
                        </a>
                    </div>
                </div>
                <?
            }
        }
        ?>
    </div>
    
  </div>
  <!-- /.container-fluid -->

</div>
<!-- /.content-wrapper -->

<script language="javascript" type="text/javascript">  
    var table1_Props =  {
                    col_0: "none",
                    col_1: "none",
                    col_2: "none",
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
</script>

<?=$footer?>