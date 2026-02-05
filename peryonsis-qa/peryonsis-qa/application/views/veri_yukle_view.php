<?=$header?>
<div class="content-wrapper">

  <div class="container-fluid">

    <!-- Breadcrumbs -->
    <ol class="breadcrumb">
      <li class="breadcrumb-item">
        <a href="<?=base_url()?>">Anasayfa</a>
      </li>
      <li class="breadcrumb-item active">IK Yönetim Paneli - Veri Yükle</li>
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
            Veri Yükle
        </div>
        <div class="card-body">
            <form method="POST" action="<?=base_url()?>veri_yukle/kaydet/" enctype="multipart/form-data">
                <div class="form-group">
                    <label>Dönem *</label>
                    <select class="js-example-theme-single form-control" name="donem_id" style="width: 100%;">
                        <option value="">Seçiniz</option>
                        <?
                        foreach ($donem_listesi as $key=>$arr)
                        {
                            ?>
                            <option value="<?=$arr['id']?>"><?=$arr['yil']?> - <?=$arr['donem']?> <?=$arr['donem_ack']?></option>
                            <?
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Form *</label>
                    <select class="js-example-theme-single form-control" name="form_id" style="width: 100%;">
                        <option value="">Seçiniz</option>
                        <?
                        foreach ($formlar as $key=>$arr)
                        {
                            ?>
                            <option value="<?=$arr['form_id']?>"><?=$arr['form_adi']?></option>
                            <?
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Gösterge *</label>
                    <select class="js-example-theme-single form-control" name="g_id" style="width: 100%;">
                        <option value="">Seçiniz</option>
                        <?
                        foreach ($gostergeler as $key=>$arr)
                        {
                            ?>
                            <option value="<?=$arr['g_id']?>"><?=$arr['g_adi']?></option>
                            <?
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="">Ürün *</label>
                    <div class="radio-inline">
                        <label><input type="radio" name="urun" value="1"/>Var</label>  
                        <label><input type="radio" name="urun" value="2"/>Yok</label>  
                    </div>
                </div>
                <div class='form-group'>
                    <input name="dosya" type="file" id="exampleInputFile">
                </div>
                <button type="submit" class="btn btn-primary pull-right"><span class="glyphicon glyphicon-log-in" aria-hidden="true"></span> Kaydet</button>
            </form>
        </div>
    </div>
    
    <?
    if (!empty_f($mes))
    {
        ?>
        <div class="alert alert-info"><?=$mes?></div>    
        <?
    }
    ?>
    
  </div>
  <!-- /.container-fluid -->

</div>
<!-- /.content-wrapper -->

<script language="javascript" type="text/javascript">  
    $(document).ready(function() {
    $(".js-example-theme-single").select2({
     theme: "classic"
   });
});
</script>

<?=$footer?>