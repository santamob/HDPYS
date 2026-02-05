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
    
    <?
    if ($this->session->userdata('message'))
    {
        echo $this->session->userdata('message');
    }
    ?>
    
    <div id="alert_div">

    </div>
    
    <div class="container">

        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="dropdown show  mr-4">
                            <a class="btn btn-primary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Değerlendirme Tipi Seçiniz
                            </a>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                <a class="dropdown-item" id="1" >Aktif Değerlendirmeler</a>
                                <a class="dropdown-item" id="0" >Pasif Değerlendirmeler</a>
                                <a class="dropdown-item" id="2" >Tüm Değerlendirmeler</a>
                            </div>
                    </div>
                    
                   
                    <a href="http://performans-v1.santafarma.com.tr/login" role="button" class="btn btn-danger">2021 Yılı Öncesi Verilere Göz At </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row"></div>
            </div>
        </div>
     
    </div>
    
  </div>
  <!-- /.container-fluid -->

</div>
<!-- /.content-wrapper -->

<script language="javascript" type="text/javascript">  
   
    $(document).ready(function() {

        const card_body = document.querySelector('.card-body .row');
        const  perSk = <?php echo $perSk;?>;
        const today = getDate();
       
        var is_passive = 1; // hepsi 2 // 1 aktif // 0 pasif 
        getData(is_passive);


        $('.dropdown-menu a').on('click', function(){    
            $('.dropdown-toggle').html($(this).html());
            is_passive = $(this).attr('id');
            getData(is_passive); 
        })

        function getData(is_passive){
            if(is_passive){
                var mydata = JSON.stringify({is_passive,perSk});
                jQuery.ajax({
                    type: "post",
                    url: '/home/filteredDashboardData',
                    data:mydata ,
                    success: function (data) {
                        var result = JSON.parse(data);
                        if(result.error == false){
                            showCards(result.data);
                        }else{
                            alert("hata var");
                        }
                    },
                });
            }
        }

        function showCards(data){
            
            if(data.length === 0){
                card_body.innerHTML = "<span >Açık dönem bulunmamaktadır.</span>";
                return;
            }

            var cards = [];
            data.forEach(element =>{
                if(element){
                    cards.push(createCard(element));
                }
            });

            card_body.innerHTML = cards.join(" ");
            
        }

        function passive(element){
            return  obj = {
                "background_color" : "background-color:#717073",
                "button":`<button type='button' class='btn btn-success btn-sm mt-1'  id="donem_ac" data-id='${element.id}'>Dönemi Aç</button>`
            };
        }

        function active(element){
            return  obj = {
                "background_color" : "background-color:#005596",
                "button":`<button type='button' class='btn btn-danger btn-sm mt-1' id='donem_kapat' data-id='${element.id}'>Dönemi Kapat</button>`
            };
        }



        $(document).on("click","#donem_ac, #donem_kapat",function() {
            var x = ($(this).attr('id') == "donem_ac") ? 1 : 0;
            var id = $(this).attr('data-id');
            if (confirm('İşleme devam etmek istediğinizden emin misiniz?')) {
                donemAcKapa(x,id);
            } 
        });


        function donemAcKapa(status,id){
          
            var mydata = JSON.stringify({status,id});
                jQuery.ajax({
                    type: "post",
                    url: '/home/changePeriodStatus',
                    data:mydata ,
                    success: function (data) {
                        var result = JSON.parse(data);
                        if(result.error == false){
                            alert(result.message);
                            getData(2);

                        }else{
                            alert(result.message);
                        }
                    },
            });
        }

       

        function getDate(){
            const newDate = new Date();
            const year = newDate.getFullYear();
            const month = newDate.getMonth() + 1;
            const d = newDate.getDate();
            return `${year}-${month.toString().padStart(2, '0')}-${d.toString().padStart(2, '0')}`
        }
 
        
        function checkLocations(location){
            if(location === null){ return false;}

            locations = location.split("-");

            var office = "";
            if (perSk == 1 || perSk == 4){
                office = "Merkez";
            }
            else if (perSk == 2 || perSk == 5){
                office = "Fabrika";
            }
            else if (perSk == 3 || perSk == 6){
                office = "Saha";
            }

            return locations.includes(office);
        }

       
        function is_active_or_passive(element){

           return  (element.donem_status == 1) ? active(element) : passive(element);// donem_status ile yapıldı.

           //return (element.bit < today || !checkLocations(element.lokasyon)) ? passive(element) : active(element)
        }

        function createCard(element){
            
            var id = <?php echo $this->session->userdata('pernr')?>;
            var card_info = is_active_or_passive(element);
            

            if(id != 100003){
                card_info.button = ""
            }
                
           
            var card = 
                `<div class="col-4 mb-3 shadow">
                            <div class="card text-white o-hidden h-100" style="${card_info.background_color}" >
                                <div class="card-body">
                                    <div class="card-body-icon">
                                        <i class="fa fa-fw fa-pencil-square"></i>
                                    </div>
                                    <div class="small mr-5">${element.yil} - ${element.donem_ack}</div>
                                    ${card_info.button}
                                </div>
                                <a class="card-footer text-white clearfix small z-1" href="<?=base_url()?>d_donemleri/takvim/${element.yil}/${element.donem}">
                                    <span class="float-left">Formlar İçin Tıklayınız</span>
                                    <span class="float-right">
                                    <i class="fa fa-angle-right"></i>
                                    </span>
                                </a>
                            </div>
                    </div>`;

           return card;
               
        }

       

       
    });

</script>

<?=$footer?>