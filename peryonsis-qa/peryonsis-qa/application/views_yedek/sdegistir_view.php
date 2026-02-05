<?=$header?>
<div class="content-wrapper">

  <div class="container-fluid">

    <!-- Breadcrumbs -->
    <ol class="breadcrumb">
      <li class="breadcrumb-item">
        <a href="<?=base_url()?>">Anasayfa</a>
      </li>
      <li class="breadcrumb-item active">Şifremi Değiştir</li>
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
            Şifremi Değiştir
        </div>
        <div class="card-body">
            <form method="POST" action="<?=base_url()?>home/sdegistir_k/" id="login">
                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                <div class="form-group">
                    <label>Yeni Şifre *</label>
                    <input type="password" class="form-control" name="pass" id="pass" placeholder="">
                </div>
                <div class="form-group">
                    <label>Yeni Şifre (Tekrar) *</label>
                    <input type="password" class="form-control" name="pass_r" id="pass_r" placeholder="">
                </div>
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
        if (jQuery.trim(jQuery("#pass").val()) == "")
        {
            alert("Şifre boş bırakılamaz.");
            return false;
        }
        else if (jQuery.trim(jQuery("#pass_r").val()) == "")
        {
            alert("Şifre tekrarı boş bırakılamaz.");
            return false;
        }
        else if (jQuery.trim(jQuery("#pass").val()).length < 5)
        {
            alert("Şifre en az 5 karakter olmalıdır.");
            return false;
        }
        else if (jQuery.trim(jQuery("#pass").val()) != jQuery.trim(jQuery("#pass_r").val()))
        {
            alert("Şifre tekrarı ile uyuşmuyor.");
            return false;
        }
        return true;
    }
</script>

<?=$footer?>