<?=$header?>
<div class="content-wrapper">

  <div class="container-fluid">

    <!-- Breadcrumbs -->
    <ol class="breadcrumb">
      <li class="breadcrumb-item">
        <a href="<?=base_url()?>">Anasayfa</a>
      </li>
      <li class="breadcrumb-item active">Genel Rapor</li>
    </ol>
    
    <?
    if ($this->session->userdata('message'))
    {
        echo $this->session->userdata('message');
    }
    ?>
    
    <div class="card">
        <div class="card-header">
            Genel Rapor
        </div>
        <div class="card-body">
            <form method="POST" action="<?=base_url()?>download_excel/genel_rapor_g/" id="login">
                <label for="">Yıl *</label>
                <select class="js-example-theme-single form-control margin_bot" name="donem_id" id="donem_id" style="width: 100%;">
                    <option value="">Seçiniz</option>
                    <?
                    foreach ($donem_listesi as $key=>$arr)
                    {
                        ?>
                        <option value="<?=$arr['id']?>"><?=$arr['yil']?> <?=$arr['donem_ack']?></option>
                        <?
                    }
                    ?>
                </select>
                <button type="submit" class="btn btn-primary pull-right" onclick="return form_control();"><span class="glyphicon glyphicon-log-in" aria-hidden="true"></span> Kaydet</button>
            </form>
        </div>
    </div>
    
  </div>
  <!-- /.container-fluid -->

</div>
<!-- /.content-wrapper -->

<script language="javascript" type="text/javascript">  
    function form_control()
    {
        if (jQuery.trim(jQuery("#donem_id").val()) == "")
        {
            alert("Dönem seçmelisiniz.");
            return false;
        }
        return true;
    }
</script>

<?=$footer?>