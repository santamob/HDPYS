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
                            <label for="yil_selectbox">Dönem Yillari</label>
                            <select class="form-control" name="yil" id="yil_selectbox">
                                <option value="0" selected="selected"></option>
                            </select>
                        </div>
                    </form>
                </div>
            </div>
            
       

    </div>



    <div class="container mt-3 mb-5" id="list_container">
        <h3 class="text-center mb-2">Havuz Listesi</h3>
        <table id="pool_table" class="table table-bordered"></table>
    </div>

    </div>
  <!-- /.container-fluid -->

</div>

<script>
    
    const inputList = document.querySelector(".inputList");
    const yil_selectbox = document.getElementById("yil_selectbox");
    const list_container = document.getElementById("list_container");
    
    list_container.hidden = true;
    

    
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
        for (let yil of yearData) {
            out += `<option value="${yil.donem}-${yil.yil}">${yil.donem} - ${yil.yil}</option> `;
            //out += `<option value="${yil.donem}-${yil.yil}">${yil.yil}</option> `;
        }
        return out;
    }

    

    $(document.body).on('change',"#yil_selectbox",function (e) {
        var optVal = $("#yil_selectbox option:selected").val();
        getPools(optVal);
    });



    function getPools(optVal){
        var year_and_period = optVal.split('-');
        var donem = year_and_period[0];
        var year = year_and_period[1];
        var res = {"yil":year,"donem":donem};
        //res = JSON.stringify(res);
        list_container.hidden = false
        if(year && donem){
            $("#pool_table").DataTable({
                "bDestroy": true,
                ajax: {
                    url: "/d_donemleri/kademelendirme_havuzlari_data",
                    type: "POST",
                    data:res,
                },
                "columns": [{
                    "title": "Havuz İsmi",
                    "data": "havuz_text",
                    "render": function(data, type, row) {
                        var link = `/d_donemleri/kademelendirme_detay/${year}/${donem}/${row.havuz_id}`;
                        return `<a href="${link}">${row.havuz_text}</a>`;
                    },
                   
                }]

            });
        }
        else{
            alert("Yil ve dönem seçmelisiniz");
            list_container.hidden = true;
        }
        
    }


    // function getPools2(optVal){
    //     var year_and_period = optVal.split('-');
    //     var donem = year_and_period[0];
    //     var year = year_and_period[1];
    //     var res = {"yil":year,"donem":donem};
    //     res = JSON.stringify(res);
    //     if(year && donem){
    //         jQuery.ajax({
    //             type: "post",
    //             url: '/d_donemleri/kademelendirme_havuzlari_data',
    //             data:res,
    //             success: function (data) {
    //                 //var result = JSON.parse(data);
    //                 var result = data;
    //                 list_container.hidden = false
    //                 document.querySelector(".list-group").innerHTML = (result.length === 0) ? "Havuz bulunamamıştır." :  populateList(result,year,donem);
    //             }
    //         });
    //     }
    //     else{
    //         alert("yil ve dönem seçmelisiniz");
    //     }
        
    // }

    // function populateList(data,year,donem){
    //     var li = "";
    //     data.forEach((item)=>{
    //         var link = `/d_donemleri/kademelendirme_detay/${year}/${donem}/${item.havuz_id}`;
    //         li += `<li class="list-group-item"><a href="${link}">${item.havuz_text}</a></li>`;
    //     })
    //     return li;
    // }

  





   
  
</script>

<?=$footer?>