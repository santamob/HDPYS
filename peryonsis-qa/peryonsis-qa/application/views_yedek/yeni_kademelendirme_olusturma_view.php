<?=$header?>
<style>
    .highlited{background-color:#ffd800;color:#ff0000;border:1px solid}
</style>

<div class="content-wrapper">

  <div class="container-fluid">

    <div class="container mt-3">
            <div class="card" id="kademelendirme_main">
                <div class="card-body">
                    <form id="createElements">
                        <div class="form-group">
                            <label for="yil_selectbox">Yil ve Tarih</label>
                            <select class="form-control" name="yil" id="yil_selectbox">
                                <option value="0" selected="selected"></option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="yes_or_no">10  Kişi ve Altı İçin</label>
                            <select class="form-control" name="yes_or_no" id="yes_or_no">
                                <option value="1" selected="selected">Evet</option>
                                <option value="0">Hayır</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="kademelendirme_ismi">Kademelendirme İsmi</label>
                            <input id="kademelendirme_ismi" class="form-control" name="kademelendirme_ismi" placeholder="Kademelendirme ismi giriniz" type="text" />

                        </div>
                        <div class="row" id="yeni_kademe_ekleme_alanı">
                            <div class="col-5">
                                <div class="form-group">
                                    <input id="countElements" class="form-control" name="countElements" placeholder="Kademe adeti giriniz" type="number" min="1" />
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <button class="btn btn-warning btn-sm border border-dark" type="button" id="btncreate">Yeni Kademeler Oluştur</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="card" id="yeni_kademeler_list">
                <div class="card-body text-center">
                    <h5>Yeni Eklenecek Kademeler</h5>
                    <p style="color:red">Kademelendirmeleri düşük kademeden yüksek kademeye göre sıralayınız. <p>
                    <hr>
                    <div class="inputList mt-3"></div>
                </div>
            </div>
            
       

    </div>


 
    <div class="container mt-3" id="kademenelendirme_kaydet_div">
        <div class="mb-2 mt-2">
            <button class="btn btn-success border border-dark btn-sm btn-block" id="Save">Kademelendirmeyi Kaydet</button>
        </div>
    </div>
   
    <div class="container mt-3 mb-5" id="table_container">
        <hr>
        <button  class="btn btn-primary border border-dark btn-sm btn-block" id="merge_button"  onclick="mergeCell()">Seçili Alanları  Birleştir</button>
        <div class="row">
            <div class="col-md-12 order-md-1 table-responsive"> 
                <table class="table table-striped table-bordered text-center" id="tablo">
                    <thead id="table_head"></thead>
                    <tbody id="KademeDagılımListesi"></tbody>
                </table>
            </div>
        </div>
    </div>

    </div>
  <!-- /.container-fluid -->

</div>

<script>
    const table_container = document.getElementById("table_container");
    const yeni_kademeler_list = document.getElementById("yeni_kademeler_list");
    const yeni_kademe_ekleme_alanı = document.getElementById("yeni_kademe_ekleme_alanı");
    //const createElementForm = document.getElementById("createElements");
    const inputList = document.querySelector(".inputList");
    const yil_selectbox = document.getElementById("yil_selectbox");
    const saveButton =  document.getElementById("Save");
    const app = document.getElementById("app");
    const btncreate = document.getElementById("btncreate");
    const kademenelendirme_kaydet_div = document.getElementById("kademenelendirme_kaydet_div")
    //const dagilim_anahtalari_div = $("#dagilim_anahtalari_div");
    var deleted_tr = {};
    var log = {};
    
    //dagilim_anahtalari_div.hide("true");


    $("#merge_button").hide()
    table_container.hidden = true;
    yeni_kademeler_list.hidden = true
    kademenelendirme_kaydet_div.hidden = true
    //yeni_kademe_ekleme_alanı.hidden = true;

    fetch("<?php echo base_url()?>d_donemleri/getPeriodsDates")
        .then(function (response) {
            return response.json();
        })
        .then(function (data) {
            var yearData = data.data;
            document.getElementById("yil_selectbox").innerHTML = populateDropDown(yearData);
        });
    
    function populateDropDown(yearData){
        var out = `<option value="">Yil ve dönem seçiniz</option>`;
        //var out = `<option value="">Yil seçiniz</option>`;
        for (let yil of yearData) {
            out += `<option value="${yil.donem}-${yil.yil}">${yil.donem}-${yil.yil}</option> `;
        }
        return out;
    }

    btncreate.addEventListener("click", () => {
        //e.preventDefault();
        
        const countElements = parseInt(document.getElementById("countElements").value, 10);
        var year_and_period = $("#yil_selectbox option:selected").val();
        if (isNaN(countElements)) { alert("Lütfen geçerli adet giriniz."); return; }
        if ($("#kademelendirme_ismi").val().length == 0) { alert("Lütfen kademelendirme ismi giriniz."); return; }
        if(year_and_period == ""){
            alert("Yil ve dönem boş bırakılmaz.");
            return;
        }

        $("#kademelendirme_main").hide();
        yeni_kademeler_list.hidden = false
        inputList.innerHTML = "";
        for (let i = 1; i <= countElements; i++) {
            inputList.appendChild(createKademeInputsWithPercantages(i));
        }
        inputList.appendChild(create_geri_al_button());
        inputList.appendChild(create_olustur_button());
    });

    
    saveButton.addEventListener("click", (e) => {
        getTableData();
    });



    function mergeCell() {
        $('#tablo tr').each(function (e) {
            var oldTDLength = $(this).find('td').length;
            
            for(xTD=0; xTD<oldTDLength;xTD++)
            {
                var str="";
                crrTD = $(this).find('td:eq(' + xTD + ')');
                crrTDNext = $(this).find('td:eq(' + xTD + ')').next();
                var colSpan = 1;
                
                str+= crrTD.attr("data-id")
                while (((crrTD.hasClass('highlited')) && (crrTD.hasClass('highlited') == crrTDNext.hasClass('highlited'))) ){
                    str+= "-"+crrTDNext.attr("data-id");
                    var tempCell = crrTDNext;///store old cell in temp
                    crrTDNext = crrTDNext.next();///get next 
                    tempCell.remove();///remove next highlited cell
                    colSpan++;
                    xTD++;//to skip merged cell
                }
                
                if (colSpan > 1) {
                    crrTD.attr('colSpan', colSpan)
                    crrTD.addClass('merged');
                    crrTD.attr("merge_data",str);
                }
                

                crrTD.removeClass('highlited');
                crrTD.closest('td').find('[type=checkbox]').prop('checked',false);
                $("#merge_button").hide()
            }//td loop
            
        });//tr loop
    }


    // $( "#yes_or_no" ).change(function() {
    //     yeni_kademe_ekleme_alanı.hidden = !$("#yeni_kademe_ekleme_alanı").attr("hidden");
    // });

    function getTableData(){

        var tableData = {};
        var obj = {}
        var titles=[];
        var data = [];
        var count = 0;
        // $("#KademeListesi th").each(function(){
        //     var currentTh=$(this);
        //     var id = currentTh.attr("id");
        //     if(id){
        //         var name = currentTh.text().replace("\n","").replace("%","").trim()
        //         var percantage = currentTh.find("input:text").val();
        //         count+=parseInt(percantage);
        //         titles.push({id:id,level:name,percantage:percantage});
        //     }
        // })

        var isForTen =  ($('#yes_or_no option:selected').val() == 1 ) ? true : false;
        
        $('.inputList .new_kademe_rows').each(function() {
            var currentTh=$(this);
            var id = currentTh.find(".kademe_adi").attr("id");
            var name = currentTh.find(".kademe_adi").val();
            var percantage = currentTh.find(".kademe_yuzdesi").val();
            if(id && name && percantage){
                id= id.split('#')[1];
                count+=parseInt(percantage);
                titles.push({id:id,level:name,percantage:percantage});
            }
        });

        if(count != 100){ alert("Kademelendirme yüzdelerinin toplamın 100 olması gerekmetedir."); return;}

        var td_list = $("#KademeListesi").find(".Level");
        var count_td_list = td_list.length 

        $("#KademeDagılımListesi tr").each(function(){
            var count_td = 0;
            var currentRow=$(this);
            var count_currentRow = currentRow.find('.edittable_td').length; // th leri topluyorum
            var id = currentRow.attr("id").split("person_count#")[1];
            var arr = []
            var th_id = 0;

            currentRow.find('td').each(function() {
                var merged = false;
                if($(this).hasClass("merged")){
                    // var colspan = $(this).attr('colspan'),
                    // index = $(this).index(),
                    // prevCells = $(this).prevAll(),
                    // headerTxt = '';
                    // $.each(prevCells, function() {
                    //     if( $(this).attr('colspan') ) {
                    //     index += ( $(this).attr('colspan') - 1 );
                    //     }
                    // });
                    // for(var i=0; i<colspan; i++ ) {
                    //     headerTxt += "--"+$('#tablo thead tr th:eq('+(index+i)+')').attr("id");
                    // }
                    // th_id = headerTxt;
                   
                    merged = true;
                    th_id = $(this).attr("merge_data");
                    
                }
                else{
                    th_id = $(this).attr("data-id");
                    
                }

                if($(this).find("input:text").val()){
                    arr.push({value:$(this).find("input:text").val(),th_id:th_id,merged:merged});
                }

                
            });
            obj = {person_count:id,value:arr};
            data.push(obj)
        });


       

        //console.log(checkSumValues(data));
        if(checkSumValues(data) == false){
            alert("Kademe alanlarının toplamı kişi sayısına eşit olmalıdır!");
            return;
        }


        var year_and_period = $("#yil_selectbox option:selected").val();
        if(year_and_period == ""){
            alert("Yil ve dönem boş bırakılmaz");
            return;
        }

        
        if(isForTen && data.length == 0){
            alert("Kademelendirme yapmadınız");
            return;
        }
        
        
        year_and_period = year_and_period.split('-');
        var donem = year_and_period[0];
        var year = year_and_period[1];

        tableData["levels"] = titles;
        tableData["columns"] = data;
        tableData["yil"] = year;
        tableData["donem"] = donem;
        tableData["isForTen"] = isForTen;
        tableData["count_level"] = $("#countElements").val();
        tableData["kademelendirme_ismi"] = $("#kademelendirme_ismi").val();


        // console.log( tableData);
        // return;
        

        var res = JSON.stringify(tableData);
        jQuery.ajax({
                type: "post",
                url: '/d_donemleri/yeni_kademelendirme_ekle',
                data:res,
               // dataType: 'json',
                success: function (data) {
                    //console.log(data)
                    var result = data;
                //    var result = JSON.parse(data);
                //    console.log(result);
                    if(result.error == false){
                        alert(result.message);
                    }
                    else if(result.error == true){
                        alert(result.message);
                    }
                },
        });
    }


    function checkSumValues(data){
        var boolean = true;
        data.forEach(item => {
            console.log(item)
            var person_count = parseInt(item.person_count);
            var values = item.value;
            var count = 0;
            values.forEach(val => {
                // if(val.merged){
                //     //return true;
                //     count = parseInt(item.person_count)
                // }
                // else{
                //     count+= parseInt(val.value)
                // }

                count+= parseInt(val.value)
            })
            console.log(person_count,count);
            if(parseInt(person_count) != parseInt(count)){
                boolean = false;
            }
        })

        return boolean;
    }

    

    function create_geri_al_button(){
        var button = document.createElement("button");
        button.className = `btn btn-info btn-sm btn-block mt-2 border border-dark`;
        button.id = `go_back_button`;
        var t = document.createTextNode("İşlemi Geri Al");
        button.appendChild(t);
        return button;
    }

    function create_olustur_button(){
        var button = document.createElement("button");
        button.className = `btn btn-danger btn-sm btn-block mt-2 border border-dark`;
        button.id = `send_kademe_to_table`;
        var t = document.createTextNode("Oluştur");
        button.appendChild(t);
        return button;
    }

    function createKademeInputsWithPercantages(i) {
        let parentDiv = document.createElement("div");
        parentDiv.className = `row mt-2 new_kademe_rows`;

        let col1 = document.createElement("div");
        col1.className = `col-9`;
        var textInput = document.createElement("input");
        textInput.type = `text`;
        textInput.id = `inputElement#${i}`;
        //textInput.value = `Test Kademe İsim${i}`;
        textInput.className = `form-control kademe_adi`;
        textInput.placeholder = `Kademe İsmi Giriniz`;
        col1.appendChild(textInput);

        let col2 = document.createElement("div");
        col2.className = `col-3`;
        var parcantageInput = document.createElement("input");
        parcantageInput.type = `number`;
        parcantageInput.id = `parcantageInput#${i}`;
        //parcantageInput.value = parseInt(20);
        parcantageInput.className = `form-control kademe_yuzdesi`;
        parcantageInput.placeholder = `%`;
        col2.appendChild(parcantageInput);
        
        parentDiv.appendChild(col1);
        parentDiv.appendChild(col2);


        return parentDiv;
    }


    $(document).on("click","#send_kademe_to_table",function() {

        var count = 0;
        var isNameEmpty = false;
        $('.inputList .new_kademe_rows').each(function() {
            var currentTh=$(this);
            var id = currentTh.find(".kademe_adi").attr("id");
            var name = currentTh.find(".kademe_adi").val();
            var percantage = currentTh.find(".kademe_yuzdesi").val();
            
            //if(id && name && percantage){
            if(percantage){
               count+=parseInt(percantage);
            }

            if(name == ""){
                isNameEmpty = true;
            }
        });

        if(isNameEmpty){ alert("Kademelenirme isimleri boş bırakılamaz."); return;}
        if(count != 100){ alert("Kademelenirme yüzdelerininin toplamın 100 olması gerekmetedir."); return;}

        
        if($('#yes_or_no option:selected').val() == 1){
            append_new_levels_to_table();
        }
        else if($('#yes_or_no option:selected').val() == 0){
            kademenelendirme_kaydet_div.hidden = false
        }
    });

    $(document).on("click","#go_back_button",function() {
        go_back();
    });


  
    

    $(document).on("click",".input_checkbox_for_merge",function() {
        var tr = $(this).closest("tr");
        
        if (!(tr.attr("id") in deleted_tr)){
            deleted_tr[tr.attr("id")] = tr.html();   // satır bilgisi obje içinde tutuluyor.
        }

        // var arr = [];
        // tr.find('td').each(function() {
        //     if($(this).hasClass('edittable_td')){
        //         if($(this).hasClass('merged')){
        //             arr.push({col:$(this).attr("data-id"),val:$(this).find('.edittable').val(),merged:true})
        //         }
        //         else{
        //            arr.push({col:$(this).attr("data-id"),val:$(this).find('.edittable').val(),merged:false})
        //         }
        //     }
        // });

        // log[tr.attr("id")] = arr ;
        // console.log(log);
        
        tr.children('td:first').find(".geri_al").css('display','block'); // geri al tuşu açılıyor
        var isChecked = $(this).prop('checked'); // true
        
        if(isChecked){
            var selected = $(this).closest('td').hasClass('highlited');
            $(this).closest('td').addClass('highlited');
            $("#merge_button").show();
        }else{
            $(this).closest('td').removeClass('highlited');
            tr.children('td:first').find(".geri_al").css('display','none'); 
            $("#merge_button").hide();
        }

        

    });



    $(document).on("click",".geri_al",function() {
        var tr = $(this).closest("tr");
        var id = tr.attr("id")
        var data =  deleted_tr[tr.attr("id")];
        tr.html(data)

        
        
        tr.find('td').removeClass("highlited");
        tr.children('td:first').find(".geri_al").css('display','none'); 
        if ($("#tablo tr").has("td.highlited").length == 0) {
            $("#merge_button").hide();
        }
    });

 


    function go_back(){
        window.location.reload();
        // table_container.hidden = true;
        // $("#kademelendirme_main").show();
        // yeni_kademeler_list.hidden = true;
    }


    

    function append_new_levels_to_table(){
        const parentDiv = document.getElementsByClassName("new_kademe_rows");
        var titles = [];
        var columns = [];
        var count = 0;
        for(var i = 0; i < parentDiv.length; i++){
            const obj = {id:i,name: parentDiv[i].getElementsByClassName("kademe_adi")[0].value,percantage: parentDiv[i].getElementsByClassName("kademe_yuzdesi")[0].value};
            count+= parseInt(obj.percantage);
            titles.push(obj);
        }
        // if(count != 100){ alert("Yüzdelerin toplamın 100 olması gerekmetedir."); return;}
        
        var values = [];
        for(var i = 1;i <=10;i++){
            for(var y = 0;y<=parentDiv.length-1;y++){
                values[y] = 0 ;
            }
            columns.push({kisi_sayisi:i,value:values});
        }
        //document.getElementById("tablo").focus();
        
        table_container.hidden = false;
        kademenelendirme_kaydet_div.hidden = false
        document.getElementById("table_head").innerHTML = createTableTitles(titles);
        document.getElementById("KademeDagılımListesi").innerHTML = createTableRow(columns)
        $(".geri_al").hide();
    }


    // function append_new_levels_to_table(){
    //     const parentDiv = document.getElementsByClassName("new_kademe_rows");
    //     var titles = [];
    //     var columns = [];
    //     var values = [];
    //     var count = 0;
    //     var obj2={};
    //     for(var i = 0; i < parentDiv.length; i++){
    //         var obj = {id:`new_${i+1}`,name: parentDiv[i].getElementsByClassName("kademe_adi")[0].value,percantage: parentDiv[i].getElementsByClassName("kademe_yuzdesi")[0].value};
    //         count+= parseInt(obj.percantage);
    //         titles.push(obj);

    //         for(var row = 1;row <=10;row++){
    //             for(var c = 0;c<=parentDiv.length-1;c++){
    //                 values[c] = 0 ;
    //             }

    //             columns.push({th:obj.id,kisi_sayisi:row,value:values});
    //             // if(!(row in obj2) ){
    //             //     obj2[row] = obj.id
    //             //     //obj2["title"] = 
    //             //     //obj2["kisi_sayisi"] = row
    //             //     //obj2["value"] = values
    //             // }
    //             // columns.push(obj2);
    //         }
          
    //     }

    //     console.log(columns);
    //     return;
       
        
    //     //document.getElementById("tablo").focus();
        
    //     table_container.hidden = false;
    //     kademenelendirme_kaydet_div.hidden = false
    //     document.getElementById("table_head").innerHTML = createTableTitles(titles);
    //     document.getElementById("KademeDagılımListesi").innerHTML = createTableRow(columns)
    //     $(".geri_al").hide();
    // }


    yil_selectbox.addEventListener('change', function () {

        
        // $.getJSON("data.json", function (data) {
        //     table_container.hidden = false;
        //     var result = data.data
        //     if(!result){alert("hata"); return false;};
        //     var titles = result.titles
        //     var columns = result.columns
        //     document.getElementById("table_head").innerHTML = createTableTitles(titles);
        //     document.getElementById("KademeDagılımListesi").innerHTML = createTableRow(columns)
        // });

    });

   
    function createTableRow(columns) {
        //<tr class="DataSet" id=" dinamik kisi sayisi">
        //     <td>1</td>
        //     <td><input type="number" class="form-control edittable" value="0"></td>
        //     <td><input type="number" class="form-control edittable" value="1"></td>
        //     <td><input type="number" class="form-control edittable" value="0"></td>
        //     <td><input type="number" class="form-control edittable" value="0"></td>
        // </tr>
        var table_row = "";
        var count = 0;
        $.each(columns, function (key, row) {
            table_row += `<tr class="DataSet"  id="person_count#${row.kisi_sayisi}">`;
            table_row += `<td>
                        <b>${row.kisi_sayisi}</b>
                        <button type="button" class="btn btn-primary btn-sm geri_al">Geri Al</button>
                </td>`;
            var year = $("#yil_selectbox").val();
            count = 0;
            $.each(row.value, function (key2, val) {
                // if(year){
                //     if (val !== null) {
                //         if(row.kisi_sayisi == 3 || row.kisi_sayisi == 4){
                //             table_row += `<td class="edittable_td" colspan="${row.value.length}"><input type="text" class="form-control text-center edittable"  value="${val}"></td>`;
                //         }
                //         else{
                //             table_row += `<td class="edittable_td"><input type="text" class="form-control text-center edittable" value="${val}">
                //                 </td>`;
                //         }
                    
                //         //table_row += `<td><input type="text" class="form-control text-center edittable" value="${val}"></td>`;
                //     }
                //     else{
                //         if (row.kisi_sayisi == 1 || row.kisi_sayisi == 2){
                //             table_row += `<td class="edittable_td" colspan = "100%"><input type="text" class="form-control text-center edittable"  disabled value="Serbest"></td>`;
                //         }
                //     }
                // }
                //else{
                    // <input type="text" class="form-control text-center edittable" value="${val}">
                    table_row += `<td class="edittable_td" data-id="${count}">
                                <div class="form-row">
                                    <div class="col-10">
                                        <input type="text" class="form-control text-center edittable" value="${val}">
                                    </div>
                                    <div class="col-2">
                                    <div class="form-check mb-2 chkbx">
                                        <input class="form-check-input input_checkbox_for_merge ml-auto" type="checkbox"  class="autoSizingCheck">
                                    </div>
                                    </div>
                                </div>
                        </td>`;
                //}
                
                count++;
            });
           
            table_row += `</tr>`;
        });
            return table_row;
    }

    function createTableTitles(titles) {
        var table_head = ""
        table_head = '<tr id="KademeListesi">';
        table_head += `<th>Kişi Sayısı</th>`;
        $.each(titles, function (key, row) {
            var id = (row.id ? row.id : 0);
            table_head +=
                `<th class="Level" id="${id}">
                ${row.name}<br>
                %<input type="text" class="form-control text-center"  aria-describedby="basic-addon3" value="${row.percantage}">
            </th>`;
        });
        table_head += `</tr>`;
        return table_head;
    }


    //ekle butonu
    var ekle = document.getElementsByClassName("btnEkle");
    ekle.onclick = function () {

    }
</script>

<?=$footer?>