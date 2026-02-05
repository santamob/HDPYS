<?=$header?>
<div class="content-wrapper">

  <div class="container-fluid">

    <!-- Breadcrumbs -->
    <ol class="breadcrumb">
      <li class="breadcrumb-item">
        <a href="<?=base_url()?>">Anasayfa</a>
      </li>
      <li class="breadcrumb-item active">IK Yönetim Paneli - Kademelendirme Havuzları</li>
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
            <button class="btn btn-primary" data-toggle="modal" data-target="#yeni_havuz_modal" title="Yeni"><i class="fa fa-fw fa-plus"></i></button>
        </div>
        <div class="card-body">
            <table id="table1" cellpadding="0" cellspacing="0" class='margin_bot' width="100%">
                <thead>
                    <tr>
                        <th>Havuz Id</th>
                        <th>Havuz Adı</th>
                        <th>İşlem</th>
                    </tr>
                </thead>
                <tbody>
                    <?
                    if (is_array($havuzlar))
                    {
                        foreach ($havuzlar as $key=>$arr)
                        {
                            ?>
                            <tr>
                                <td><?=$arr['havuz_id']?></td>
                                <td><?=$arr['havuz_text']?></td>
                                <td>
                                    <button class="btn btn-primary" data-toggle="modal" data-target="#havuz_guncelle_modal_<?=$arr['havuz_id']?>" title="Güncelle"><i class="fa fa-fw fa-refresh"></i></button>
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
    <div class="modal fade" id="yeni_havuz_modal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">
                        Kademelendirme Havuzu
                    </h4>
                    <button type="button" class="close" 
                       data-dismiss="modal">
                           <span aria-hidden="true">&times;</span>
                           <span class="sr-only">Kapat</span>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="modal-body">
                    <form method="POST" action="<?=base_url()?>ikyp/havuz_kaydet/" autocomplete="off">
                        <div class="form-group">
                            <label for="">Havuz Tanımı *</label>
                            <input type="text" name="havuz_text" id="" class="form-control" placeholder="Havuz Tanımı">
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
    if (is_array($havuzlar))
    {
        foreach ($havuzlar as $key=>$arr)
        {
            ?>
            <!-- Veri güncelleme modal -->
            <div class="modal fade" id="havuz_guncelle_modal_<?=$arr['havuz_id']?>" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title" id="myModalLabel">
                                Kademelendirme Havuzu Güncelle
                            </h4>
                            <button type="button" class="close" 
                               data-dismiss="modal">
                                   <span aria-hidden="true">&times;</span>
                                   <span class="sr-only">Kapat</span>
                            </button>
                        </div>

                        <!-- Modal Body -->
                        <div class="modal-body">
                            <form method="POST" action="<?=base_url()?>ikyp/havuz_kaydet/" autocomplete="off">
                                <input type="hidden" name="guncellenecek_id" value="<?=$arr['havuz_id']?>">
                                <div class="form-group">
                                    <label for="">Havuz Tanımı *</label>
                                    <input type="text" name="havuz_text" id="" class="form-control" placeholder="Havuz Tanımı" value="<?=$arr['havuz_text']?>">
                                </div>
                                <p class="text-muted">* işaretli alanlar zorunludur.</p>  
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