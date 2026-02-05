<?=$header?>
<style>
    .displayNone {
   display: none;
 }
    </style>
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

    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-header">
                    Yeni Kademe Ekle
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="exampleFormControlSelect1">Dönem Seçimi</label>
                        <select class="form-control" id="selected_donem">
                            <option value ="0">Lütfen Dönem Seçiniz</option>
                            <?php
                            foreach($donem_listesi as $value){

                            ?>
                            <option value ="<?php echo $value["id"];?>"><?php echo $value["yil"]."-".$value["donem"]." (".$value["donem_ack"].")" ; ?> </option>
                            <?php
                                }
                            ?>
                        
                        </select>
                    </div>
                
                    <div class="form-group" id="kademe_area">
                        <label for="exampleFormControlSelect1">Kademe Ekle</label>
                        <!-- <select class="form-control js-example-tags" multiple   data-select2-tag="true"  name="criters[]">

                        </select> -->

                        <select class="form-control js-example-tags" multiple>
                            
                        </select>
                    </div>
                    <button type="button" id="saveForm" class="btn btn-primary">Ekle</button>
                </div>
            
            </div>
        </div>
        <div class="col-md-9">
            <div class="card">
                <div class="card-header">
                  Değerlendirme Listesi
                </div>
                <div class="card-body" id="degerelendirme_listesi">
                    <ul class="list-group" id="degerlendirme_list">
                        
                    </ul>
                    
                    <button type="button" id="saveData" class="btn btn-success btn-sm mt-3">Güncelle</button>
                    
                </div>
            
            </div>
        </div>
        
    </div>

  </div>
  <!-- /.container-fluid -->

</div>

<div class="modal" id="degerlendirmeler_modal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <ul class="list-group" id="degerlendirme_list_modal">
                            
        </ul>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary">Save changes</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<!-- /.content-wrapper -->

<script language="javascript" type="text/javascript">  
    

    
$(document).ready(function() {
    

   $(".js-example-tags").select2({
            tags: true
    });

    $("#kademe_area").hide();
    $("#saveForm").hide();
    $("#degerelendirme_listesi").hide();
    
    

    let  criteriaData;

    let donem_id;

    let degerlendirme_data = [];

   $('#selected_donem').change(function() {
        
        donem_id = $(this,':selected').val();
        if(donem_id == 0){ return;}

        getData(donem_id);
        
        $("#kademe_area").show();
        $("#saveForm").show();
       
      
      
        

    });


    $("#saveData").click(function() {

        degerlendirme_data = [];
        var optionTexts = [];
        jQuery( "ul#degerlendirme_list li" ).each(function( index ) {
            optionTexts = [];
            
            $(this).find('ul.mybuttons_list').each(function() {

                $(this).find('li').each(function() {

                    var button_obj = {
                        "button_id":$(this).attr("id"),
                        "button_name":$(this).find('.button_name').val(),
                        "check_val":$(this).find('.check_button_status').is(":checked"),
                        "button_state":$(this).attr("data-buttonstate")

                    };

                    optionTexts.push(button_obj)

                });

            });

            
            var  obj = {
                "donem_id":donem_id,
                //"name" :$(this).attr("data-name"),
                "name" :$(this).find('.criteria_name').val(),
                "min": $(this).find('.new_criteria_names_min').val(),
                "max": $(this).find('.new_criteria_names_max').val(),
                "state":$(this).attr("data-state"),
                "pot_id":$(this).attr("data-id"),
                "kriter_id":$(this).attr("data-criter"),
                "buttons":optionTexts

            };

            if($(this).find('.new_criteria_names_min').val()){
                degerlendirme_data.push(obj);
            }
           
            
        });

    

        //console.log(degerlendirme_data);
        //return;
        
        var mydata = {"data":degerlendirme_data};

        jQuery.ajax({
                type: "post",
                url: '/d_donemleri/updateCriteria',
                data:mydata ,
                //dataType: 'json',
                success: function (data) {
                   var result = JSON.parse(data);
                   console.log(result);
                    if(result.success == true){
                        alert("işlem başarılı");
                        location.reload();
                    }

                },
        });


       
    });


    $( "#saveForm" ).click(function() {
        
        
        //$("ul#degerlendirme_list").empty();
         var new_data = $('.js-example-tags').select2('val');
       
        if(new_data == null){alert("Ekleme yapmadınız"); return;}
        
        //$("#degerelendirme_listesi").show();
        $(".js-example-tags").empty().trigger('change');
        var item = AddNewToList(new_data);
        $("ul#degerlendirme_list").append(item);


        
    });




    function clear_select2() {
        $(".js-example-tags").empty().trigger('change');
    }

  
    function getData(id) {

        
        $("ul#degerlendirme_list").empty();

        $("#kademe_area").hide();
        $("#saveForm").hide();
        $("#degerelendirme_listesi").hide();

        jQuery.ajax({
            type: "post",
            url: '/d_donemleri/getSelectData/'+id,
            dataType: 'json',
            success: function (data) {
               //console.log(data);
                criteriaData = data;
                $("#degerelendirme_listesi").show();
                var items = populateList(data);
                
                $("ul#degerlendirme_list").append(items);
     
            },
        });

        
    
    }


    function  populateList(data){

        var items = [];

        $(data).each(function(index, Element) {

            var li = 
                `<li  id ="${Element.pot_id}"   data-criter="${Element.kriter_id}" data-id="${Element.pot_id}" data-name="${Element.name}" data-state="0" class="list-group-item d-flex justify-content-between align-items-center">
                    <div class="row">
                        <input type="text" class="form-control criteria_name mb-2"   value="${Element.name}">
                        
                        <div class="col-md-2 form-group">
                            <label for="exampleInputEmail1">Min </label>
                            <input type="text" class="form-control new_criteria_names_min" data-min="${Element.min}" value="${Element.min}" >
                        </div>
                        <div class="col-md-2 form-group">
                            <label for="exampleInputEmail1">Max </label>
                            <input type="text" class="form-control new_criteria_names_max" data-max="${Element.max}"  value="${Element.max}">
                        </div>

                        <div class="col-md-5 form-group">
                            <label for="exampleInputEmail1" class="h-1">Tuşlar<button type="button" data-from = "${Element.name.replace(" ", "_")}_1" class="btn btn-sm btn-primary add_button_rule">+</button></label>
                            
                            <ul id="${Element.name.replace(" ", "_")}_1" class="list-group mybuttons_list">`;

                            $(Element.buttons).each(function(index2, button) {

                                li += `<li  data-buttonstate="0" id="${button.button_id}"  class="list-group-item d-flex justify-content-between align-items-center">
                                            <input type="text" class="form-control button_name"  value="${button.button_name}">
                                            <input class="form-check-input check_button_status"  type="checkbox" ${(button.button_state == 1) ? "checked":""} value="" >
                                            <button type="button" data-buttonstatusid="${button.button_id}"  class="btn btn-danger btn-sm remove_from_button_list_old">Sil</button>
                                        </li>`;

                            });
                                
            
                               
                li+=`</ul>

                        </div>  

                        <div class="col-md-3 form-group">
                            <br>
                            <button type="button" data-id="${Element.pot_id}"  class="btn btn-danger btn-sm remove_from_list">Sil</button>
                        </div>      
                    </div>   
                </li>`;
               

            

            items.push(li);
        });

        return items;

       

    }


    $(document).on("click",".add_button_rule",function() {
      
    var li_id = $(this).attr("data-from");
    createLi(li_id);
   
  });


  $(document).on("click",".remove_from_button_list",function() {
      
      var li_id = $(this).attr("data-buttonstatusid");
      $(`.mybuttons_list #${li_id}_id`).remove();
      alert("Silme işlemi başarılı");
   
  });

  $(document).on("click",".remove_from_button_list_old",function() {
      
      var id = $(this).attr("data-buttonstatusid");
     
        if(id){
            jQuery.ajax({
                type: "get",
                url: '/d_donemleri/deleteButton/'+id,
                dataType: 'json',
                success: function (data) {
                if(data.success == true ){
                  
                    $(`.mybuttons_list #${id}`).remove();
                    alert("Silme işlemi başarılı");
                }
                else{
                    alert("silme işlemi başarısız");
                }
                
              
                },
            });

        }
      //$(`.mybuttons_list #${li_id}_id`).remove();
   
  });


  

    
   

    $(document).on("click",".remove_from_list",function() {
        //$("ul#degerlendirme_list").empty();
        
        var id = $(this).attr("data-id");
        if(id){
            jQuery.ajax({
                type: "get",
                url: '/d_donemleri/deleteCriteria/'+id,
                dataType: 'json',
                success: function (data) {
                if(data.success == true ){
                    $(`ul#degerlendirme_list #${id}`).remove(); 
                }
                else{
                    alert("silme işlemi başarısız");
                }
                
              
                },
            });

        }
        
      
       
    
    });


    var count = 0;
    function createLi(li_id){
       
       count++;
      
       var button_status_id = li_id.split("_");
       button_status_id= button_status_id[0]+"_"+count;

       //console.log(button_status_id);
       
      
        var li = `<li data-buttonstate="1" id="${button_status_id}_id" class="list-group-item d-flex justify-content-between align-items-center">
                            <input type="text" class="form-control button_name"  value="">
                            <input class="form-check-input check_button_status"   type="checkbox" value="" >
                            <button type="button" data-buttonstatusid="${button_status_id}"  class="btn btn-danger btn-sm remove_from_button_list">Sil</button>
            </li>`;
  
        console.log(li_id);
        $(`ul#${li_id}`).append(li);
        
      

    }




    function  AddNewToList(name){

      

            var li = `<li  id ="${name}" data-criter=""  data-id="" data-name="${name}" data-state="1" class="list-group-item d-flex justify-content-between align-items-center">
                    <div class="row">
                        <input type="text" class="form-control criteria_name"  value="${name}">
                        <div class="col-md-5 form-group">
                            <label for="exampleInputEmail1"> Min </label>
                         <input type="text" class="form-control new_criteria_names_min" data-min="${Element.min}" value="" >
                        </div>
                        <div class="col-md-5 form-group">
                            <label for="exampleInputEmail1"> Max </label>
                            <input type="text" class="form-control new_criteria_names_max" data-max="${Element.max}"  value="">
                        </div> 
                        <div class="col-md-2 form-group">
                        <br>
                        <button type="button" data-id="${name}"  class="btn btn-danger btn-sm remove_from_list">Sil</button>
                        </div>      
                    </div>   
                    </div>   
                </li>`;
                
            return li;
          

}


});
</script>

<?=$footer?>