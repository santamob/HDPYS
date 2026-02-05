<?=$header?>
<style>
    .highlited{background-color:#ffd800;color:#ff0000;border:1px solid}
</style>

<div class="content-wrapper">

  <div class="container-fluid">
  <!-- <button type="button" class="btn btn-warning" id="getExcel">Excel Çıktısı Al</button> -->
  <a href="/download_excel/dagilim_anahtarlari_excel" class="btn btn-sm btn-warning border border-dark">Excel Çıktısı Al</a>

    <div class="container mt-3">
        <div class="card">
            <div class="card-body">
                <div class="container">
                    <div class="row">
                        <div class="col-sm">
                            <div class="form-group">
                                <label for="yil_selectbox"><b>Yil</b></label>
                                <select class="form-control" name="yil" id="yil_selectbox">
                                    <option value="0" selected="selected"></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm">
                            <div class="form-group">
                                <label for="count_person"><b>Etki Edeceği Grup</b></label>
                                <select class="form-control" name="count_person" id="choose_count_person">
                                    <option value="" selected="selected">Lütfen seçiniz</option>
                                    <option value="0" >10 Kişinin altı</option>
                                    <option value="1">10 Kişinin Üstü</option>
                                </select>
                            </div>
                        </div>
                        
                    </div>

                    <!-- <button type="button" class="btn btn-primary btn-md btn-block" id="getAllData">Getir</button> -->


                    <div class="card mt-5" id="show_data">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm">
                                    <div class="card">
                                        <div class="card-body">
                                            <h5 class="text-center mb-4">Dağılım Anahtarları</h5><hr>
                                            <div class="form-group">
                                                <ul class="list-group"  id="keys"></ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="col-sm">
                                    <div class="card">
                                        <div class="card-body">
                                            <h5 class="text-center mb-4">Havuzlar</h5><hr>
                                            <div class="form-group">
                                               <ul class="list-group"  id="pools"></ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                            </div>
                            <button type="button" class="btn btn-success btn-md btn-block mt-3" id="saveData">Kaydet</button>
                        </div>
                       
                    </div>




                    
                </div>
            </div>
        </div>
    </div>



    </div>
  <!-- /.container-fluid -->

</div>

<script>
    const choose_count_person = document.querySelector("#choose_count_person");
    const pools_div = document.querySelector("#pools");
    const keys_div = document.querySelector("#keys");
    //const getAllDataButton =  document.querySelector("#getAllData");
    const saveButton =  document.querySelector("#saveData");
    const getExcelButton = document.querySelector("#getExcel");
   
    var assinged_pools = [];
   
    $( document ).ready(function() {
        $("#show_data").hide();
        
        fetch("<?php echo base_url()?>d_donemleri/getPeriodsDates")
        .then(function (response) {
            return response.json();
        })
        .then(function (data) {
            var yearData = data.data;
         
            const newMap = new Map();
            yearData.forEach((item) => newMap.set(item.yil, item));
            var years = [...newMap.values()];

            document.getElementById("yil_selectbox").innerHTML = populateDropDown(years);
        });
    });




    function createCheckBox(item,idkey){
      
        //console.log(assinged_pools,item)

        var checked = "";
        var disabled = "";
        var owner = "";
       
        var checked_ones = assinged_pools.filter(obj => obj.idhavuz == item.havuz_id && obj.id_da == idkey); // atanmışlar içinde benim idhvzuuma ve idkeyme eşit var mı;
        if(checked_ones.length > 0){
            checked = "checked";
        }

        var not_mines = assinged_pools.filter(obj2 => obj2.idhavuz == item.havuz_id && obj2.id_da != idkey);
        if(not_mines.length > 0){
            disabled = "disabled";
            owner = `(${item.name})`;
        }

        console.log(item)

        var checkbox = `
            <li class="list-group-item"
                <div class="form-check">
                    <label class="form-check-label" for="defaultCheck1">
                    <input class="form-check-input checkbox_pools" type="checkbox" ${disabled} ${checked} value="${item.havuz_id}">
                    (${item.havuz_id}) - ${item.havuz_text}  <b>${owner}</b>
                    </label>
                </div>
            </li>
            `;
        return checkbox;
    }


    function createRadioButton(item){
        var k = assinged_pools.find(x => x.id_da == item.id);
        var checked = (!k) ? "": "checked";
     
        var radio_box = `
            <li class="list-group-item"
                <div class="form-check">
                    <label class="form-check-label">
                        <input type="radio" class="form-check-input"  ${checked} value="${item.id}" name="optradio">
                        (${item.id}) -  ${item.name}
                    </label>
                </div>
            </li>
        `;

        return radio_box;
    }
    
    function populateDropDown(yearData){
        var out = `<option value="">Yil seçiniz</option>`;
        //var out = `<option value="">Yil seçiniz</option>`;
        for (let yil of yearData) {
            //out += `<option value="${yil.donem}-${yil.yil}">${yil.donem}-${yil.yil}</option> `;
            out += `<option value="${yil.yil}">${yil.yil}</option> `;
        }
        return out;
    }



    function getKeys(year,is_over) {
        var data = {year,is_over};
        fetch('/d_donemleri/getKeysByYeardAndPersonCount/', {
            method: 'post',
            body: JSON.stringify(data)
        }).then(function(response) {
            return response.json();
        }).then(function(data) {
            if(data.error == false){
                var result = data.data;
                var keys = [];
                
                if(result.length > 0){
                    result.forEach(function (item) {
                        keys.push(createRadioButton(item));
                    });
                    keys_div.innerHTML = keys.join("");
                }
                else{
                    keys_div.innerHTML = `<span class="text-center"><b>${year} yılına ait anahtar bulunmamaktadır.</span></b>`;
                }
                
            }
           
        });
    }

    function getPools(year,is_over,idkey) {
        // console.log("getPools");
        // return;
        var data = {year,is_over,idkey};
        fetch('/d_donemleri/getPoolsByYeardAndPersonCount/', {
            method: 'post',
            body: JSON.stringify(data)
        }).then(function(response) {
            return response.json();
        }).then(function(data) {
            if(data.error == false){
                var keys = [];
                var pools = data.result.pools;
                assinged_pools = data.result.assinged_pools;
                //getKeys(year,is_over);

                //console.log(assinged_pools)
              
                var is_include = assinged_pools.find(ap => ap.id_da == idkey);
                (assinged_pools.length > 0 && is_include != undefined) ? updateButton() : remakeSaveButton(); // for change state of save button


                pools.forEach(function (item) {
                    keys.push(createCheckBox(item,idkey));
                });
                pools_div.innerHTML = keys.join("");
            }
        });
    }


    function updateButton(){
        $("#saveData").addClass("update");
        $("#saveData").removeClass("btn-success");
        $("#saveData").addClass("btn-danger");
        $("#saveData").text("Güncelle");
    }

    function remakeSaveButton(){
        $("#saveData").removeClass("update");
        $("#saveData").removeClass("btn-danger");
        $("#saveData").addClass("btn-success");
        $("#saveData").text("Kaydet");
    }


    $('#choose_count_person').click(function(){
        let year = $("#yil_selectbox").val();
        if(year == ""){alert("Yil seçimi yapmadınız.");return false};
    });

    $('#yil_selectbox').change(function () {
       $("#choose_count_person").val("");
    });


    $('#choose_count_person').change(function () {
        pools_div.innerHTML = "";
        const is_over = $("#choose_count_person").val();
        let year = $("#yil_selectbox").val();
        
        if(year == ""){alert("Yil seçimi yapmadınız.");return false};
        if(is_over == ""){alert("Kişi sayısı seçimi yapmadınız");return false};
        
        $("#show_data").show();
        //year =  year.split('-')[1];
        remakeSaveButton();
        getKeys(year,is_over);
    });


    // getAllDataButton.addEventListener('click', function () {

    //     pools_div.innerHTML = "";
    //     const is_over = $("#choose_count_person").val();
    //     let year = $("#yil_selectbox").val();
        
    //     if(year == ""){alert("Yil seçimi yapmadınız.");return false};
    //     if(is_over == ""){alert("Kişi sayısı seçimi yapmadınız");return false};
        
    //     $("#show_data").show();
    //     //year =  year.split('-')[1];
        
    //     getKeys(year,is_over);
    //     //getPools(year,is_over)
    // });


    $(document).on('change','.checkbox_pools',function(){
        var is_checked = $(this).is(':checked');

        if(is_checked) {
            $(this).addClass("checked");
            //$(this).removeClass("updated");
            $(this).addClass("updated");
        }else{
            $(this).addClass("updated");
            $(this).removeClass("checked");
        }
    });



    $(document).on('change','input:radio[name=optradio]:checked',function(){
        var idkey = $(this).val();
        var is_over = $("#choose_count_person").val();
        var year = $("#yil_selectbox").val();
        //var key_data = {idkey,is_over,year};
        //console.log(key_data)
        getPools(year,is_over,idkey)
       
    });

    

    

    saveButton.addEventListener('click',saveData );

    //getExcelButton.addEventListener('click',saveData );


    function saveData(){

        const is_update = ($("#saveData").hasClass("update")) ? true : false;
        const radio_button = $("input[name='optradio']:checked").val();
        
        var arr = [];
        //$('input.checkbox_pools:checkbox:checked').each(function () {
        $('input.checkbox_pools').each(function () {
           //arr.push({"id":$(this).val()});
           if($(this).prop('disabled') == false){
            var checked = ($(this).hasClass("updated")) ? true : false;
            arr.push({"id":$(this).val(),"update":checked,"state":$(this).is(":checked")});
           }
        });

        

        if(arr.length == 0){alert("Havuz seçimi yapmadınız");return false};
        if(radio_button == ""){alert("Dağılım anahtarı seçmediniz.");return false};
        
        let year = $("#yil_selectbox").val();
        //year = year.split('-')[1];

        var data = {"havuz_ids":arr,"idda":radio_button,"year":year,"is_update":is_update};


        // console.log(data);
        // return;

        fetch('/d_donemleri/assingKeyToPool/', {
            method: 'post',
            body: JSON.stringify(data)
        }).then(function(response) {
            return response.json();
        }).then(function(data) {
            if(data.error == false && data.result == true){
                alert("İşlem Başarıyla Gerçekleşti!");
                var idkey = $('input[name="optradio"]:checked').val();
                var is_over = $("#choose_count_person").val();
                getPools(year,is_over,idkey)
            }
        });

    }


  
  

   

  
    
</script>

<?=$footer?>