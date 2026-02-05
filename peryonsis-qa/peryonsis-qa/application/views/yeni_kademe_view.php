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

                     <!-- <table class="table  table-bordered" id="degerlendirme_list">
                        <thead>
                            <tr>
                            <th scope="col">Min</th>
                            <th scope="col">Max</th>
                            <th scope="col">Sil</th>
                            <th scope="col">Handle</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                           
                        </tbody>
                        </table>  -->
                    
                    <button type="button" id="saveData" class="btn btn-success btn-sm mt-3">Kaydet</button>
                    
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
        $("#kademe_area").show();
        $("#saveForm").show();
       
      
        //set_select2(id)
        

    });


    $("#saveData").click(function() {

        degerlendirme_data = [];
        var optionTexts = [];
        jQuery( "ul#degerlendirme_list li" ).each(function( index ) {
            
            optionTexts = [];
            //$("ul.mybuttons_list li").each(function() {
            
            $(this).find('ul.mybuttons_list').each(function() {

                $(this).find('li').each(function() {

                    var button_obj = {
                        "button_name":$(this).find('.button_name').val(),
                        "check_val":$(this).find('.check_button_status').is(":checked")
                    };
               
                    optionTexts.push(button_obj)

                });

            });

            
            var  obj = {
                "donem_id":donem_id,
                "name" :$(this).attr("id"),
                "min": $(this).find('.new_criteria_names_min').val(),
                "max": $(this).find('.new_criteria_names_max').val(),
                "buttons": optionTexts,
            };


          

           

            if($(this).find('.new_criteria_names_min').val()){
                degerlendirme_data.push(obj);
            }

          
            
        });

       
        var mydata = {"data":degerlendirme_data};
        
        jQuery.ajax({
                type: "post",
                url: '/d_donemleri/addNewCriteria',
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
        
       
        
        $("ul#degerlendirme_list").empty();
        criteriaData = $('.js-example-tags').select2('val');
       
        if(criteriaData == null){alert("Ekleme yapmadınız"); return;}
        
        $("#degerelendirme_listesi").show();
        var items = populateList(criteriaData);
        $("ul#degerlendirme_list").append(items);

        //var items = populateTable(criteriaData);
        //$("#degerlendirme_list tbody").append(items);

        

        
    });



    // $(document.body).on('select2:select', ".js-example-tags", function () {

    //     console.log($(".js-example-tags").val())
    //     return;
    //     if ($(".js-example-tags").val()) {
    //         if (isNaN(this.value)) {
    //             var userinfo = confirm("Yeni bir kriter eklemek istediğinize emin misiniz?.");
    //             if (!userinfo) {
    //                 clear_select2();
    //             }

    //         }
    //     }

    // });

    function clear_select2() {
        $(".js-example-tags").empty().trigger('change');
    }

  
    function set_select2(id) {

        $("#degerelendirme_listesi").show();
        $("#kademe_area").show();
        $("#saveForm").show();


        var items = populateList(data);
        $("ul#degerlendirme_list").append(items);
      

        //         jQuery.ajax({
        //         type: "post",
        //         url: '/d_donemleri/getSelectData/'+id,
        //         dataType: 'json',
        //         success: function (data) {
        //             console.log(data)
        //             criteriaData = data;

        //             jQuery.each(data, function (i, d) {
        //                 var data = {
        //                     id: d.id,
        //                     text: d.name
        //                 };
        //                 var newOption = new Option(data.text, data.id, false, false);
        //                 jQuery('.js-example-tags').append(newOption).trigger('change');


        //             });

                   
                   
        //         },
        // });

        
    
    }


   

    function  populateList(data){

        var items = [];

        $(data).each(function(index, Element) {

            var li = 
                `<li  id ="${Element}" class="list-group-item d-flex justify-content-between align-items-center">
                    <div class="row">
                        
                        <div class="col-md-2 form-group">
                            <label for="exampleInputEmail1">(${Element}) Min </label>
                           
                            <input type="text" class="form-control new_criteria_names_min" data-min="${Element}" value="" >
                        </div>

                        <div class="col-md-2 form-group">
                            <label for="exampleInputEmail1">(${Element}) Max </label>
                           
                            <input type="text" class="form-control new_criteria_names_max" data-max="${Element}"  value="">
                        </div> 

                        <div class="col-md-5 form-group">
                            <label for="exampleInputEmail1" class="h-1">Tuşlar<button type="button" data-from = "${Element.replace(" ", "_")}_1" class="btn btn-sm btn-primary add_button_rule">+</button></label>
                            
                            <ul id="${Element.replace(" ", "_")}_1" class="list-group mybuttons_list">
                                
                                <li  id="${Element.replace(" ", "_")}_id" class="list-group-item d-flex justify-content-between align-items-center">
                                    <input type="text" class="form-control button_name"  value="">
                                    <input class="form-check-input check_button_status"  type="checkbox" value="" >
                                    <button type="button" data-buttonstatusid="${Element.replace(" ", "_")}"  class="btn btn-danger btn-sm remove_from_button_list">Sil</button>
                                </li>
                               
                            </ul>

                        </div> 

                        

                        <div class="col-md-3 form-group">
                            <br>
                            <button type="button" data-id="${Element}"  class="btn btn-danger btn-sm remove_from_list">Sil</button>
                        </div>  

                            
                      
                    </div>   
                </li>`;
               

            

            items.push(li);
        });

        return items;

       

    }

    
    function  populateTable(data){

        var items = [];

        $(data).each(function(index, Element) {

            
                var tr = `
                        <tr>
                            <td>
                                
                                <label for="exampleInputEmail1">(${Element}) Min </label>
                                <input type="text" class="form-control new_criteria_names_min" data-min="${Element}" value="" >


                            </td>
                            <td>

                                <label for="exampleInputEmail1">(${Element}) Max </label>
                                <input type="text" class="form-control new_criteria_names_max" data-max="${Element}"  value="">
                            
                            </td>

                            

                            <td>
                                <button type="button" data-id="${Element}" class="btn btn-danger btn-sm remove_from_list mt-4">Sil</button>
                            </td>
                        
                        </tr>`;
            

            
        
        
        
            items.push(tr);
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
     
    });



    

    var count = 0;
    function createLi(li_id){
       count++;
      
       var button_status_id = li_id.split("_");
       button_status_id= button_status_id[0]+"_"+count;

       console.log(button_status_id);
       
      
        var li = `<li id="${button_status_id}_id" class="list-group-item d-flex justify-content-between align-items-center">
                            <input type="text" class="form-control button_name"  value="">
                            <input class="form-check-input check_button_status"   type="checkbox" value="" >
                            <button type="button" data-buttonstatusid="${button_status_id}"  class="btn btn-danger btn-sm remove_from_button_list">Sil</button>
            </li>`;
  
        $(`ul#${li_id}`).append(li);
        
      

    }

   

    $(document).on("click",".remove_from_list",function() {
        $("ul#degerlendirme_list").empty();
        
        var li = $(this).attr("data-id");
        criteriaData.splice(criteriaData.indexOf(li),1);
        var items = populateList(criteriaData);
        console.log(items)
       if(items.length ==0 ){ $("#degerelendirme_listesi").hide();}

        $("ul#degerlendirme_list").append(items);

        $(".js-example-tags").find(`option[value='${li}']`).prop("selected",false);
        $(".js-example-tags").trigger("change");
       
    });




//     function  AddNewToList(name){

      
//             var li = 
//                 `<li class="list-group-item d-flex justify-content-between align-items-center">
//                     <div class="row">
//                         <div class="col-md-5 form-group">
//                             <label for="exampleInputEmail1">Min(${name})</label>
//                         <input type="text" class="form-control"  value="" >
//                         </div>
//                         <div class="col-md-5 form-group">
//                             <label for="exampleInputEmail1">Max(${name})</label>
//                             <input type="text" class="form-control" value="" >
//                         </div> 
//                     <div class="col-md-2 form-group">
//                         <br>
//                         <button type="button"   class="btn btn-success btn-sm">Ekle</button>
//                         </div>      
//                     </div>   
//                 </li>`;
                
//             return li;

            

// }


});
</script>

<?=$footer?>