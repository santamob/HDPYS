
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

