<?=$header?>
<div class="content-wrapper">

  <div class="container-fluid">

    <!-- Breadcrumbs -->
    <ol class="breadcrumb">
      <li class="breadcrumb-item">
        <a href="http://peryonsis-dev.santafarma.com.tr/">Anasayfa</a>
      </li>
      <li class="breadcrumb-item active" id="degerlendirme_bread" ></li>
    </ol>
    
        
    <div id="alert_div">

    </div>
    
        <!--<button type="button"  id="openMyModal" class="btn btn-danger">Yeni Kriter Ekle</button>-->
   
    <div id="tabs" class="mt-3">


        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link first-segment active" id="kaf" data-toggle="tab" href="#tab_kaf" role="tab" aria-controls="home" aria-selected="true">Kendime Ait Formlar</a>
            </li>
            <li class="nav-item ">
                <a class="nav-link first-segment" id="aaf" data-toggle="tab" href="#tab_aaf" role="tab" aria-controls="profile" aria-selected="false">Astlarıma Ait Formlar</a>
            </li>
            <li class="nav-item">
                <a class="nav-link first-segment" id="aaaf" data-toggle="tab" href="#tab_aaaf" role="tab" aria-controls="contact" aria-selected="false">Astlarımın Astlarına Ait Formlar</a>
            </li>
        </ul>
        <div class="tab-content" id="tabs">
            <div class="tab-pane fade show active" id="tab_kaf" role="tabpanel" aria-labelledby="home-tab">
                
            </div>
            <div class="tab-pane fade" id="tab_aaf" role="tabpanel" aria-labelledby="profile-tab">
                
            </div>
            <div class="tab-pane fade" id="tab_aaaf" role="tabpanel" aria-labelledby="contact-tab">
                
            </div>
        </div>

        
        <div id='tabs_kaf'>

        </div>
        <div id='tabs_aaf'>
            
        </div>
        <div id='tabs_aaaf'>

        </div>
    </div>

    <div class="modal fade" id="kriter_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Yeni Kriter Ekle</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
                <div class="form-group">
                    <label for="recipient-name" class="col-form-label">Recipient:</label>
                    <input type="text" class="form-control" id="recipient-name">
                </div>
                <div class="form-group">
                    <!--<select class="js-example-basic-single"  name="test" style="width:250px">-->
                    <select class="js-example-tags" multiple   data-select2-tag="true"  name="criters[]" style="width:450px">

                    </select>
                </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" id="addNewCrit" class="btn btn-primary">Kaydet</button>
            </div>
            </div>
        </div>
    </div>

<script language="javascript" type="text/javascript">  
    


       

    // set_select2_liste(2021);
    // function set_select2_liste(donem) {
    
    //     $(".js-example-basic-single").empty().trigger('change')


    //     var query2 = {"donem": donem};

    //         jQuery.ajax({
    //             type: "post",
    //             url: '/d_donemleri/getSelectData',
    //             data: query2,
    //             dataType: 'json',
    //             success: function (data) {

                
    //                 jQuery.each(data, function (i, d) {
    //                     var data = {
    //                         id: d.id,
    //                         text: d.name
    //                     };

    //                     var newOption = new Option(data.text, data.id, false, false);
    //                     jQuery('.js-example-basic-single').append(newOption).trigger('change');


    //                 });
    //             },
    //     });





    // }

   
    var Rule_map = null;

  
    
    $(document).ready(function() {
       
        

        let profile_data = GetUserProfileData();

        let uri_segment = GetPageParameters();
        let year = uri_segment[3];
        let donem = 3; // donem ÜÇ olarak sabit tutlması gerekir. 3. ve 2. dönem çalışmaları bir birinden ayrı yapılması gerekir.


        let donem_ack = "";

        if(donem == 3)
        {
            donem_ack = "Yılsonu Değerlendirme";
        }

        //Değerlendirme Dönemleri - 2022 - Yılsonu Değerlendirme
        $("#degerlendirme_bread").text("Değerlendirme Dönemleri - " + year + " - " + donem_ack);

        let kademelendirme_data = GetRoleOfKademelendirme(year,donem,profile_data.pernr); 

        if(kademelendirme_data == null)
        {
            kademelendirme_data = GetRoleOfKademelendirmeBaseOfPernr(year,donem,profile_data.pernr); 
        }


        let session_ust_pernr_data = GetUstListesi(year,donem,profile_data.pernr); 

        let session_ust_onay_data = GetUstOnay(year,donem,session_ust_pernr_data[0].ust_pernr,profile_data.pernr);

        let manager_data = SessionIsManager(year,donem,profile_data.pernr);

        let havuz_data = [];
        
        let is_kademelendirme_start_data = null;

        let havuz_counter = 0;

        let havuz_id_prev = 0;

        let is_havuz_kademe_started = null;

        let is_kademe_manager_is_confirm_hedef = false;
        let is_kademe_manager_is_confirm_yetkinlik = false;

        let is_kademe_manager_is_confirm_data = null;

        
        if(kademelendirme_data != null)
        {
            for(let index = 0; index < kademelendirme_data.length; index++)
            {
                if(havuz_id_prev != kademelendirme_data[index].havuz_id)
                {
                    
                    havuz_data.push(kademelendirme_data[index]);

                    havuz_id_prev = kademelendirme_data[index].havuz_id;

                }
            }

            console.log(havuz_data);

            is_havuz_kademe_started = JSON.parse(JSON.stringify(havuz_data));
            
            
            for(let havuz_index = 0; havuz_index < is_havuz_kademe_started.length; havuz_index++)
            {

                is_havuz_kademe_started[havuz_index] = {"havuz_id": havuz_data[havuz_index].havuz_id, "is_pool_kademe_started": false};

                is_kademelendirme_start_data = IsKademelendirmeStart(year,donem,havuz_data[havuz_index].havuz_id);

                if(is_kademelendirme_start_data)
                {
                    if(kademelendirme_data.length > 0)
                    {
                        is_havuz_kademe_started[havuz_index] = {"havuz_id": havuz_data[havuz_index].havuz_id, "is_pool_kademe_started": true};
                    }
                }
                
            }
        }

        let confirmation_data =  null;

        let is_kademelendirme_manager = false;

        let is_manager = false;


         
        
        if(kademelendirme_data)
        {

            if(kademelendirme_data[0].k_pernr == profile_data.pernr)
            {
                is_kademelendirme_manager = true;

                
                is_kademe_manager_is_confirm_data = IsKademeManagerIsConfirmed(year,donem,profile_data.pernr);

                if(is_kademe_manager_is_confirm_data)
                {
                    is_kademe_manager_is_confirm_hedef = is_kademe_manager_is_confirm_data[1];
                    is_kademe_manager_is_confirm_yetkinlik = is_kademe_manager_is_confirm_data[2];
                }
                
            }
            else
            {
                is_kademelendirme_manager = false;
            }
        }
        else{
            is_kademelendirme_manager = false;
        }

        if(manager_data)
        {
            if(manager_data.length > 0)
            {
                is_manager = true;
            }
            else
            {
                is_manager = false;
            }
        }
        else{
            is_manager = false;
        }
        


        let degerlendirmeler_check_list = CheckDegerlendirmeler(year,donem,profile_data.pernr);
        
        

        //console.log(confirmation_rule);

        Rule_map = {
            "session_ust_pernr_data":session_ust_pernr_data,
            "session_ust_onay_data": session_ust_onay_data,
            "kademelendirme_data": kademelendirme_data,
            "confirmation_data": confirmation_data,
            "manager_data": manager_data,
            "havuz_data": havuz_data,
            "is_kademelendirme_manager": is_kademelendirme_manager,
            "is_manager": is_manager,
            "is_kademelendirme_process_start": is_havuz_kademe_started,
            "degerlendirmeler_check_list": degerlendirmeler_check_list,
            "is_kademe_manager_is_confirm_hedef": is_kademe_manager_is_confirm_hedef,
            "is_kademe_manager_is_confirm_yetkinlik": is_kademe_manager_is_confirm_yetkinlik,
            "is_kademe_manager_is_confirm_data": is_kademe_manager_is_confirm_data,
            "pys_period_finish_rule": true
        };

        console.log("Kademelendirme Yöneticisi");


        console.log(kademelendirme_data);
        
        console.log("Rule_map");


        console.log(Rule_map);

        
        ApplicationRun(year,donem,profile_data,Rule_map);

        $("#kaf").trigger('click');
        $("#kaf_Hedef").trigger('click');
        
    });

        function GetOneLevelDownManager(year,donem,pernr)
        {
            let data = null;

            let payload = {
                "yaer": year,
                "donem" : donem,
                "ust_pernr": pernr
            };

            $.ajax({
                method: "POST",  
                url:"/d_donemleri_new/GetDownManager",
                async: false,
                dataType: 'JSON',
                cache: false,
                contentType: false,
                processData: false,
            })
            .done(function(response){
                
                data = response.user;

                
            });




            return data; 
        }

        function GetSessionIsManager(year,donem,pernr)
        {
            let data = null;

            let payload = {
                "year": year,
                "donem" : donem,
                "pernr": pernr
            };

            $.ajax({
                method: "POST",  
                url:"/d_donemleri_new/GetSessionIsManager",
                data: JSON.stringify(payload),
                async: false,
                dataType: 'JSON',
                cache: false,
                contentType: false,
                processData: false,
            })
            .done(function(response){
                
                data = response;

                
            });




            return data; 
        }

        function GetUserProfileData()
        {
            let data = null;

            

            $.ajax({
                method: "POST",  
                url:"/d_donemleri_new/getprofiledata",
                async: false,
                dataType: 'JSON',
                cache: false,
                contentType: false,
                processData: false,
            })
            .done(function(response){
                
                data = response.user;

                
            });




            return data;
        }

        function GetPageParameters()
        {
            let uri_segment = window.location.pathname.split('/');

            
            
            return uri_segment;
        }


        function GetFormTypes(category)
        {
            let data = null;

            

            $.ajax({
                method: "POST",  
                url:"/d_donemleri_new/getformtypes/"+category,
                async: false,
                dataType: 'JSON',
                cache: false,
                contentType: false,
                processData: false,
            })
            .done(function(response){
                
                data = response.forms;


            });


            return data;
        }


        function GenerateTabsForCategory(category,data)
        {

            let html = "";
            let html_contenter = "";

            

            html += '<div id="tabs_'+category+'_inner">';
            
                html += '<ul class="nav nav-tabs" id="tabs_'+ category +'_forms" role="tablist">';
                html_contenter += '<div class="tab-content" id="myTabContent">';
                for(let i= 0 ; i<data.length; i++)
                {

                    //console.log("data -> " + data[i].ft_text);

                    let do_active = '';
                    if(i == 0)
                    {
                        do_active='active';
                    }

                    let ex = data[i].ft_text.split(' ');
                    let ex_tag = ex[0].replace("ü","u");
                    ex_tag = ex_tag.replace("ö","o");
                    ex_tag = ex_tag.replace("ş","s");

                    html += '<li class="nav-item">';
                    html += '<a class="nav-link second-segment ' + do_active + '" id="' + category + '_' + ex_tag +'" data-toggle="tab" href="#tabs_'+ category + '_' + ex_tag +'" role="tab" aria-controls="home" aria-selected="false">' + data[i].ft_text + '</a>';
                    html += '</li>';


                    html_contenter += '<div class="tab-pane fade show  ' + do_active + '" id="tabs_'+ category +'_' + ex_tag +'" role="tabpanel" aria-labelledby="home-tab"></div>';
                }


                // Her Kategoryde Sonuc Sekmesini yaratmak gerekir
                html += '<li class="nav-item">';
                html += '<a class="nav-link second-segment" id="' + category + '_Sonuc" data-toggle="tab" href="#tabs_'+ category + '_Sonuc" role="tab" aria-controls="home" aria-selected="false"> Sonuç </a>';
                html += '</li>';

                html_contenter += '<div class="tab-pane fade show" id="tabs_'+ category +'_Sonuc" role="tabpanel" aria-labelledby="home-tab"></div>';



                html +="</ul>";
                html_contenter += '</div>';

            $("#tab_"+category).html(html + html_contenter);

        }

        

        function ApplicationRun(year,donem,user_data,Rule_map)
        {
            let full_check_out = false;

            let session_ust_pernr_onay = null;

            let points_of_hedef_and_yetkinlik = null;

             
            let points_of_hedef = null;

            let points_of_yetkinlik = null;

            let ast_pernr_list = null;

            let ast_ast_pernr_list = null;

            let ast_pernr_list_forms = null;

            let ast_ast_pernr_list_forms = null;

            let session_pernr_forms = null;

            let points_of_yetkinlik_evulated_by_self = null;

            let onay_table_data = null;

            let form_data = GetFormTypes("kaf");

            let html = '';

            let payload = "";



            let onay_durumu = "";

            let onay_sureci_kontrol_ast = false;

            let onay_sureci_kontrol_ast_ast = false;

            let ft_id = "";

            

            let session_user_is_administrator = GetSessionIsManager(year,donem,user_data.pernr);

            let check_confirmation_to_deep = null;

            ast_pernr_list = Get_AST_EmployeeData(year,donem,user_data.pernr);

            if(!ast_pernr_list)
            {
                alert("Astınız bulunmamaktadır");
            }
            else
            {
                debugger;
                check_confirmation_to_deep = CheckConfirmationToDeep(year,donem,user_data.pernr,ast_pernr_list.ast_listesi);

                console.log("Check Confirmation Deep");
                console.log(check_confirmation_to_deep);

                if(check_confirmation_to_deep != null)
                {
                    if(check_confirmation_to_deep[1] && check_confirmation_to_deep[2] && check_confirmation_to_deep[3] && check_confirmation_to_deep[4] && check_confirmation_to_deep[5])
                    {
                        full_check_out = jQuery.inArray(0,check_confirmation_to_deep[1]) == -1 
                        && jQuery.inArray(0,check_confirmation_to_deep[2]) == -1 
                        && jQuery.inArray(0,check_confirmation_to_deep[3]) == -1 
                        && jQuery.inArray(0,check_confirmation_to_deep[4]) == -1 
                        && jQuery.inArray(0,check_confirmation_to_deep[5]) == -1 
                    }
                    else
                    {
                        full_check_out = false;
                    }
                }

                



                onay_sureci_kontrol_ast = true;

                ast_ast_pernr_list = Get_AST_AST_EmployeeData(year,donem,ast_pernr_list.ast_listesi);


                console.log(ast_pernr_list);
                console.log(ast_ast_pernr_list);


                if(!ast_ast_pernr_list)
                {
                    alert("Astınızın Astı bulunmamaktadır");
                }
                else
                {
                    onay_sureci_kontrol_ast_ast = true;
                }

            }


            

            /*
            if(onay_sureci_kontrol_ast)
            {
                console.log("Onay süreci AST Kontrolleri");
                console.log(ast_pernr_list);





            }

            if(onay_sureci_kontrol_ast)
            {
                console.log("Onay süreci AST AST Kontrolleri");
                console.log(ast_ast_pernr_list);
            }*/



            GenerateTabsForCategory("kaf",form_data);
            

            // Default State içinde KAF (Kendisine Ait Formlar) içerisinde Gelen Formlardan Kendi Ast kişi listesi geelcektir.


            // Button Clicks For Confirmation States

            /*
            $(".send-confirmation-status").on('click',function(){

                let onaylayan = $(this).data("onaylayan");
                let degerlendiren = $(this).data("degerlendiren");
                let ft_id = $(this).data("ft_id");
                let onay_durum = $(this).data("onay_durum");

                console.log(onaylayan,degerlendiren,onay_durum,ft_id);

                SendConfirmationStatus(year,donem,onaylayan,degerlendiren,ft_id,onay_durum);



            });
            */




            $(".first-segment").one('click',function(){

                
                
                form_data = GetFormTypes(this.id);

                //console.log(data);

                GenerateTabsForCategory(this.id,form_data);




                let category = this.id;
                
                // KAF Default Hedef 

                // AST Default Hedef

                // AST Default Hedef


                $(".second-segment").one('click',function(){
                   


                    if(category == "kaf" && user_data.pernr != 100429 && user_data.pernr != 102659 && user_data.pernr != 100000)
                    {
                        
                        points_of_hedef = GetHedefFormPoints(year,donem,[ user_data.pernr ]);
                        //points_of_hedef_and_yetkinlik = GetHedefAndYetkinlikPoints(year,donem,[ user_data.pernr ]);
                        console.log("kaf_hedef puanı");
                        console.log(points_of_hedef);

                        points_of_yetkinlik = GetSessionFormPointEvaluateBySelf(year,donem,user_data.pernr);

                        // Yöneticinin Verdiği Puandan ayrıştırdığımız için iki farklı puan mevcut

                        if(this.id == "kaf_Hedef")
                        {
                            session_pernr_forms = Get_KAF_HedefAndYetkinlik(year,donem,1,user_data.pernr);  

                            html = CreateTableAstlarForm(year,donem,1,user_data.pernr,session_pernr_forms,true,"sess");

                            $("#tabs_kaf_Hedef").html(html);

                            SetAstPernrHedefAndYetkinlikPoints(1,points_of_hedef,"hp","sess");
                        }

                        if(this.id == "kaf_Yetkinlik")
                        {

                            session_pernr_forms = Get_KAF_HedefAndYetkinlik(year,donem,2,user_data.pernr);  

                            html = CreateTableAstlarForm(year,donem,2,user_data.pernr,session_pernr_forms,true,"sess");

                            $("#tabs_kaf_Yetkinlik").html(html);

                            SetAstPernrHedefAndYetkinlikPoints(2,points_of_yetkinlik,"yp","sess");
                            
                        }

                        if(this.id == "kaf_Sonuc")
                        {
                            

                            points_of_hedef_and_yetkinlik = GetTotalHedefAndYetknlikBySelfSession(year,donem,user_data.pernr);
                            
                            console.log("TOTAL HP YP");
                            console.log(points_of_hedef_and_yetkinlik);

                            html = CreateResultTable(session_pernr_forms,"result_sess");

                            $("#tabs_kaf_Sonuc").html(html);

                            SetAstPernrHedefAndYetkinlikPoints(1,points_of_hedef,"hp","result_sess");

                            console.log(points_of_yetkinlik);

                            SetAstPernrHedefAndYetkinlikPoints(2,points_of_yetkinlik,"yp","result_sess");

                            SetPernrListResultTotalPoint(points_of_hedef_and_yetkinlik,"result_sess");


                        }
                    }
                    if(category == "aaf" && ast_pernr_list)
                    {
                       

                        //ast_pernr_list = Get_AST_EmployeeData(year,donem,user_data.pernr);

                        points_of_hedef = GetHedefFormPoints(year,donem, ast_pernr_list.ast_listesi);


                        points_of_yetkinlik = GetYetkinlikFormPoints(year,donem, ast_pernr_list.ast_listesi);

                        //points_of_hedef_and_yetkinlik = GetHedefAndYetkinlikPoints(year,donem,ast_pernr_list.ast_listesi);
                        
                        
                        
                        if(this.id == "aaf_Hedef")
                        {
                            ast_pernr_list_forms = GetFormPernrList(1,year,donem,user_data.pernr);

                            html = CreateTableAstlarForm(year,donem,1,user_data.pernr,ast_pernr_list_forms,true,"ast");

                            //onay_table_data = GetConfirmationProcessOfSelf(year,donem,1,user_data.pernr);

                            console.log(user_data.pernr);

                            

                            onay_table_data = GetConfirmationProcessOfAst(year,donem,1,Rule_map.manager_data[0].ust_pernr,[ user_data.pernr ]);

                            let button_form_html = "";

                            console.log(onay_table_data);
                        
                            console.log("check_confirmation");
                            console.log(check_confirmation_to_deep);
                            let check_out = false;

                            
                            if( check_confirmation_to_deep != null)
                            {



                                if(check_confirmation_to_deep[1])
                                {
                                    check_out = jQuery.inArray(0,check_confirmation_to_deep[1]) == -1;
                                }
                                else
                                {
                                    check_out = false;
                                }
                                
                            }
                            

                            console.log(check_out);
                            // Kademelendirme Başladımı
                            if( Rule_map.is_kademelendirme_process_start[0].is_pool_kademe_started == false)
                            {
                                // Onay Süreci Başladı mı
                                if(onay_table_data)
                                {
                                    // Başladı işe işlemleri yürüt
                                    html += CreateConfirmationTableSessionDegerlendirenAst(onay_table_data,1,"ast",Rule_map,user_data);
                                }
                                else // Onay Süreci Başlamadı ise
                                {
                                    debugger;
                                    if((Object.keys(Rule_map.degerlendirmeler_check_list[1]).length == ast_pernr_list.ast_listesi.length)) // değerlendirmeleri bitirenler benim aslarım mı      
                                    {
                                        if(check_out) // Tüm ast ve astlarımın astları ilgili formu doldurdumu ?
                                        {
                                            if(Rule_map.is_kademelendirme_manager) // Kademelendirme Yöneticisi ise
                                            {
                                                
                                                if(full_check_out) // Kademe Yöneticisi ilgili hedef formunu Onaylamış mı
                                                {
                                                    button_form_html += '<button class="btn btn-primary" id="aaf_Hedef_first_kademe_push" data-yil ="'+ year + '" data-donem="'+donem +'" data-ft_id = "1" data-onay_durum = "4" data-onaylayan="'+ Rule_map.manager_data[0].ust_pernr +'" data-degerlendiren="'+ user_data.pernr +'" >Kademelendirmeye Gönder</button>';
                                                    
                                                }
                                                else
                                                {
                                                    button_form_html += "Kademelendirmeye gönderebilmeniz için öncesinde astlarınızın formlarına onay vermelisiniz.";
                                                    //button_form_html += '<button class="btn btn-primary" id="aaf_Hedef_first_kademe_push" data-yil ="'+ year + '" data-donem="'+donem +'" data-ft_id = "1" data-onay_durum = "4" data-onaylayan="'+ Rule_map.manager_data[0].ust_pernr +'" data-degerlendiren="'+ user_data.pernr +'" >Kademelendirmeye Gönder</button>';
                                                }
                                            }
                                            else // Kademelendirme Yöneticisi değil ise Onaya Gönder
                                            {
                                                button_form_html += '<button class="btn btn-primary" id="aaf_Hedef_first_move" data-yil ="'+ year + '" data-donem="'+donem +'" data-ft_id = "1" data-onay_durum = "4" data-onaylayan="'+ user_data.pernr +'" data-degerlendiren="'+ user_data.pernr +'" >Onaya Gönder</button>';
                                            }
                                        }
                                        else // Tüm ast ve astlarım değerlendirmeyi doldurmadı
                                        {
                                            if(ast_ast_pernr_list) // Astlarımın astları var mı
                                            {
                                                if(Rule_map.is_kademelendirme_manager) // Kademelendirme Yöneticisi ise
                                                {
                                                    button_form_html += "Kademelendirmeye gönderebilmeniz için öncesinde astlarınızın formlarına onay vermelisiniz.";
                                                }
                                                else
                                                {
                                                    button_form_html += "Onaya gönderebilmeniz için öncesinde astlarınızın formlarına onay vermelisiniz.";
                                                }
                                                
                                            }
                                            else // Astlarımn Astları yoksa
                                            {
                                                button_form_html += "<p>Onaya gönderilmesi bekleniyor</p>";
                                                button_form_html += '<button class="btn btn-primary" id="aaf_Hedef_first_move" data-yil ="'+ year + '" data-donem="'+donem +'" data-ft_id = "1" data-onay_durum = "4" data-onaylayan="'+ user_data.pernr +'" data-degerlendiren="'+ user_data.pernr +'" >Onaya Gönder</button>';
                                            }
                                        }
                                    }
                                    else
                                    {
                                        button_form_html += "Degerlendirilmeler henüz yapılmadı";
                                    }
                                }

                                html += button_form_html;
                            }
                            else
                            {
                                html += "Kademelendirme Başladı";
                            }
                            
                            
                            
                            $("#tabs_aaf_Hedef").html(html);

                            SetAstPernrHedefAndYetkinlikPoints(1,points_of_hedef,"hp","ast");

                        }

                        if(this.id == "aaf_Yetkinlik")
                        {
                            
                            ast_pernr_list_forms = GetFormPernrList(2,year,donem,user_data.pernr);

                            html = CreateTableAstlarForm(year,donem,2,user_data.pernr,ast_pernr_list_forms,true,"ast");

                            
                            onay_table_data = GetConfirmationProcessOfAst(year,donem,2,Rule_map.manager_data[0].ust_pernr,[ user_data.pernr ]);

                            let button_form_html = "";

                            console.log(onay_table_data);
                        
                            console.log("check_confirmation");
                            console.log(check_confirmation_to_deep);
                            let check_out = false;

                            
                            if( check_confirmation_to_deep != null)
                            {
                                check_out = jQuery.inArray(0,check_confirmation_to_deep[2]) == -1;
                            }
                            

                            console.log(check_out);

                            if( Rule_map.is_kademelendirme_process_start[0].is_pool_kademe_started == false)
                            {

                                if(onay_table_data)
                                {
                                    html += CreateConfirmationTableSessionDegerlendirenAst(onay_table_data,2,"ast",Rule_map,user_data);
                                }
                                else
                                {
                                    if((Object.keys(Rule_map.degerlendirmeler_check_list[2]).length == ast_pernr_list.ast_listesi.length))
                                    {
                                        if(check_out)
                                        {
                                            if(Rule_map.is_kademelendirme_manager)
                                            {

                                                if(Rule_map.is_kademe_manager_is_confirm_hedef)
                                                {

                                                    button_form_html += '<button class="btn btn-warning" id="aaf_Hedef_first_kademe_undo" data-yil ="'+ year + '" data-donem="'+donem +'" data-ft_id = "2" data-onay_durum = "3" data-onaylayan="'+ Rule_map.manager_data[0].ust_pernr +'" data-degerlendiren="'+ user_data.pernr +'" >Kademelendirmeyi Geri Al</button>';
                                                }
                                                else
                                                {
                                                    button_form_html += '<button class="btn btn-primary" id="aaf_Hedef_first_kademe_push" data-yil ="'+ year + '" data-donem="'+donem +'" data-ft_id = "2" data-onay_durum = "4" data-onaylayan="'+ Rule_map.manager_data[0].ust_pernr +'" data-degerlendiren="'+ user_data.pernr +'" >Kademelendirmeye Gönder</button>';
                                                }
                                            }
                                            else
                                            {
                                                button_form_html += '<button class="btn btn-primary" id="aaf_Hedef_first_move" data-yil ="'+ year + '" data-donem="'+donem +'" data-ft_id = "2" data-onay_durum = "4" data-onaylayan="'+ user_data.pernr +'" data-degerlendiren="'+ user_data.pernr +'" >Onaya Gönder</button>';
                                            }
                                        }
                                        else
                                        {
                                            button_form_html += "Onaya gönderebilmeniz için öncesinde astlarınızın formlarına onay vermelisiniz.";
                                        }
                                        
                                        
                                    }
                                    else
                                    {
                                        button_form_html += "Degerlendirilmeler henüz yapılmadı";
                                    }
                                    
                                        /*
                                        button_form_html += '<form method="POST" action="/d_donemleri/onay_islemleri/1">';
                                        button_form_html += '<input type="hidden" name="yil" value="' + year + '" />';
                                        button_form_html += '<input type="hidden" name="donem" value="' + donem + '" />';
                                        button_form_html += '<input type="hidden" name="ft_id" value="1" />';
                                        button_form_html += '<input type="hidden" name="onay_durum" value="4" />';
                                        button_form_html += '<input type="hidden" name="onaylayan" value="'+ Rule_map.manager_data[0].ust_pernr +'" />';
                                        button_form_html += '<input type="hidden" name="degerlendiren" value="' + user_data.pernr + '" />';
                                        button_form_html += '<input type="submit" class="btn btn-primary" value="Onaya Gönder Buradayım" />';
                                        button_form_html += '</form>';*/
                                }

                                html += button_form_html;
                            }
                            else
                            {
                                html += "Kademelendirme Başladı";
                            }

                            $("#tabs_aaf_Yetkinlik").html(html);

                            
                            SetAstPernrHedefAndYetkinlikPoints(2,points_of_yetkinlik,"yp","ast");


                        }

                        if(this.id == "aaf_Potansiyel" && user_data.pernr != 100429 && user_data.pernr != 102659 && user_data.pernr != 100000)
                        {

                            ast_pernr_list_forms = GetFormPernrList(3,year,donem,user_data.pernr);

                            let potential_points = CollectPotentialPoint(year,donem,3,ast_pernr_list.ast_listesi);

                            
                            html = CreateTableAstlarForm(year,donem,3,user_data.pernr,ast_pernr_list_forms,true,"ast");

                            onay_table_data = GetConfirmationProcessOfAst(year,donem,3,Rule_map.manager_data[0].ust_pernr,[ user_data.pernr ]);

                            let button_form_html = "";

                            console.log(onay_table_data);

                            console.log("check_confirmation");
                            console.log(check_confirmation_to_deep);
                            let check_out = false;


                            if( check_confirmation_to_deep != null)
                            {
                                check_out = jQuery.inArray(0,check_confirmation_to_deep[3]) == -1;
                            }


                            console.log(check_out);

                            /*if( Rule_map.is_kademelendirme_process_start[0].is_pool_kademe_started == false)
                            {*/

                                if(onay_table_data)
                                {
                                    html += CreateConfirmationTableSessionDegerlendirenAst(onay_table_data,3,"ast",Rule_map,user_data);
                                }
                                else
                                {
                                    if((Object.keys(Rule_map.degerlendirmeler_check_list[3]).length == ast_pernr_list.ast_listesi.length))
                                    {
                                        if(check_out)
                                        {
                                           
                                            html += '<button class="btn btn-primary" id="aaf_Hedef_first_move" data-yil ="'+ year + '" data-donem="'+donem +'" data-ft_id = "3" data-onay_durum = "4" data-onaylayan="'+ user_data.pernr +'" data-degerlendiren="'+ user_data.pernr +'" >Onaya Gönder</button>';
                                        }
                                        else
                                        {
                                            html += "Onaya gönderebilmeniz için öncesinde astlarınızın formlarına onay vermelisiniz.";
                                        }
                                        
                                        
                                    }
                                    else
                                    {
                                        html += "Degerlendirilmeler henüz yapılmadı";
                                    }
                                    
                                        /*
                                        button_form_html += '<form method="POST" action="/d_donemleri/onay_islemleri/1">';
                                        button_form_html += '<input type="hidden" name="yil" value="' + year + '" />';
                                        button_form_html += '<input type="hidden" name="donem" value="' + donem + '" />';
                                        button_form_html += '<input type="hidden" name="ft_id" value="1" />';
                                        button_form_html += '<input type="hidden" name="onay_durum" value="4" />';
                                        button_form_html += '<input type="hidden" name="onaylayan" value="'+ Rule_map.manager_data[0].ust_pernr +'" />';
                                        button_form_html += '<input type="hidden" name="degerlendiren" value="' + user_data.pernr + '" />';
                                        button_form_html += '<input type="submit" class="btn btn-primary" value="Onaya Gönder Buradayım" />';
                                        button_form_html += '</form>';*/
                                }

                                /*html += button_form_html;
                            }
                            else
                            {
                                html += "Kademelendirme Başladı";
                            }*/
                            
                            $("#tabs_aaf_Potansiyel").html(html);

                            SetPernrListPotentialPoint(3,potential_points,"potential_point","ast");


                        }

                        if(this.id == "aaf_Gorusme")
                        {
                            ast_pernr_list_forms = GetFormPernrList(4,year,donem,user_data.pernr);

                            html = CreateTableAstlarForm(year,donem,4,user_data.pernr,ast_pernr_list_forms,false,"ast");

                            onay_table_data = GetConfirmationProcessOfAst(year,donem,4,Rule_map.manager_data[0].ust_pernr,[ user_data.pernr ]);

                            let button_form_html = "";

                            console.log(onay_table_data);
                        
                            console.log("check_confirmation");
                            console.log(check_confirmation_to_deep);
                            let check_out = false;

                            
                            if( check_confirmation_to_deep != null)
                            {
                                check_out = jQuery.inArray(0,check_confirmation_to_deep[4]) == -1;
                            }
                            

                            console.log(check_out);
                                if(onay_table_data)
                                {
                                    html += CreateConfirmationTableSessionDegerlendirenAst(onay_table_data,4,"ast",Rule_map,user_data);
                                }
                                else
                                {
                                    if((Object.keys(Rule_map.degerlendirmeler_check_list[4]).length == ast_pernr_list.ast_listesi.length))
                                    {
                                        if(check_out)
                                        {
                                            html += '<button class="btn btn-primary" id="aaf_Hedef_first_move" data-yil ="'+ year + '" data-donem="'+donem +'" data-ft_id = "3" data-onay_durum = "4" data-onaylayan="'+ user_data.pernr +'" data-degerlendiren="'+ user_data.pernr +'" >Onaya Gönder</button>';
                                        }
                                        else
                                        {
                                            html += "Onaya gönderebilmeniz için öncesinde astlarınızın formlarına onay vermelisiniz.";
                                        }
                                    }
                                    else
                                    {
                                        html += "Degerlendirilmeler henüz yapılmadı";
                                    }
                                }

                            $("#tabs_aaf_Gorusme").html(html);

                        }

                        if(this.id == "aaf_Yonlendirme")
                        {
                            ast_pernr_list_forms = GetFormPernrList(5,year,donem,user_data.pernr);

                            html = CreateTableAstlarForm(year,donem,5,user_data.pernr,ast_pernr_list_forms,false,"ast");

                            onay_table_data = GetConfirmationProcessOfAst(year,donem,5,Rule_map.manager_data[0].ust_pernr,[ user_data.pernr ]);

                            let button_form_html = "";

                            console.log(onay_table_data);
                        
                            console.log("check_confirmation");
                            console.log(check_confirmation_to_deep);
                            let check_out = false;

                            
                            if( check_confirmation_to_deep != null)
                            {
                                check_out = jQuery.inArray(0,check_confirmation_to_deep[5]) == -1;
                            }
                            

                            console.log(check_out);

                            if(onay_table_data)
                                {
                                    html += CreateConfirmationTableSessionDegerlendirenAst(onay_table_data,5,"ast",Rule_map,user_data);
                                }
                                else
                                {
                                    if((Object.keys(Rule_map.degerlendirmeler_check_list[5]).length == ast_pernr_list.ast_listesi.length))
                                    {
                                        if(check_out)
                                        {
                                            html += '<button class="btn btn-primary" id="aaf_Hedef_first_move" data-yil ="'+ year + '" data-donem="'+donem +'" data-ft_id = "3" data-onay_durum = "4" data-onaylayan="'+ user_data.pernr +'" data-degerlendiren="'+ user_data.pernr +'" >Onaya Gönder</button>';
                                        }
                                        else
                                        {
                                            html += "Onaya gönderebilmeniz için öncesinde astlarınızın formlarına onay vermelisiniz.";
                                        }
                                    }
                                    else
                                    {
                                        html += "Degerlendirilmeler henüz yapılmadı";
                                    }
                                }
                            $("#tabs_aaf_Yonlendirme").html(html);

                        }

                        if(this.id == "aaf_Sonuc")
                        {

                            points_of_hedef_and_yetkinlik = GetTotalHedefYetkinlikPoints(year,donem,ast_pernr_list.ast_listesi);
                            
                            console.log("TOTAL HP YP");
                            console.log(points_of_hedef_and_yetkinlik);

                            html = CreateResultTable(ast_pernr_list_forms,"result_ast");


                            $("#tabs_aaf_Sonuc").html(html);

                            SetAstPernrHedefAndYetkinlikPoints(1,points_of_hedef,"hp","result_ast");

                            SetAstPernrHedefAndYetkinlikPoints(2,points_of_yetkinlik,"yp","result_ast");

                            SetPernrListResultTotalPoint(points_of_hedef_and_yetkinlik,"result_ast");

                        }




                    }
                    if(category == "aaaf" && ast_pernr_list && ast_ast_pernr_list)
                    {
                        
                        html = "";




                        points_of_hedef = GetHedefFormPoints(year,donem, ast_ast_pernr_list.ast_listesi);


                        points_of_yetkinlik = GetYetkinlikFormPoints(year,donem, ast_ast_pernr_list.ast_listesi);
                        
                        
                        if(this.id == "aaaf_Hedef")
                        {
                            console.log(this.id);

                            ast_ast_pernr_list_forms = GetAstAstForm(year,donem,ast_pernr_list.ast_listesi,1);

                            if(!ast_ast_pernr_list_forms)
                            {
                                alert("Astlarınıza Ast Form Tanımlanmamış");
                            }
                            
                            //points_of_hedef_and_yetkinlik = GetHedefAndYetkinlikPoints(year,donem,ast_ast_pernr_list.ast_listesi);

                            
                            html = CreateTableAstlarForm(year,donem,1,user_data.pernr,ast_ast_pernr_list_forms,true,"ast_ast");

                            
                            onay_table_data = GetConfirmationProcessOfAst(year,donem,1,user_data.pernr,ast_pernr_list.ast_listesi);

                            let button_form_html = "";

                            console.log(onay_table_data);
                        
                            console.log("check_confirmation");
                            console.log(check_confirmation_to_deep);
                            let check_out = false;

                            
                            if( check_confirmation_to_deep != null)
                            {
                                check_out = jQuery.inArray(0,check_confirmation_to_deep[5]) == -1;
                            }
                            

                            console.log(check_out);
                            if( Rule_map.is_kademelendirme_process_start[0].is_pool_kademe_started == false)
                            {
                                if(onay_table_data != null)
                                {
                                    html += CreateConfirmationTableSessionDegerlendirenAst_Ast(onay_table_data,1,"ast_ast",Rule_map,user_data);
                                }
                                else
                                {
                                    if((Object.keys(Rule_map.degerlendirmeler_check_list[1]).length == ast_pernr_list.ast_listesi.length))
                                    {
                                        if(check_out)
                                        {
                                            button_form_html = "Bu durum ne olmalı";
                                        }
                                        else
                                        {
                                            button_form_html += "Onaya gönderilmesi Bekleniyor";
                                        }
                                    }
                                    else
                                    {
                                        button_form_html += "Degerlendirilmeler henüz yapılmadı";
                                    }
                                }

                                html += button_form_html;
                            }
                            else
                            {
                                html += "Kademelendirme Başladı";
                            }

                            $("#tabs_aaaf_Hedef").html(html);

                            SetAstPernrHedefAndYetkinlikPoints(1,points_of_hedef,"hp","ast_ast");
                        }

                        if(this.id == "aaaf_Yetkinlik")
                        {
                            $("#tabs_aaaf_Hedef").removeClass("active show");

                            ast_ast_pernr_list_forms = GetAstAstForm(year,donem,ast_pernr_list.ast_listesi,2);

                            if(!ast_ast_pernr_list_forms)
                            {
                                alert("Astlarınıza Ast Form Tanımlanmamış");
                            }

                            //points_of_hedef_and_yetkinlik = GetHedefAndYetkinlikPoints(year,donem,ast_ast_pernr_list.ast_listesi);

                            html = CreateTableAstlarForm(year,donem,2,user_data.pernr,ast_ast_pernr_list_forms,true,"ast_ast");

                            onay_table_data = GetConfirmationProcessOfAst(year,donem,2,user_data.pernr,ast_pernr_list.ast_listesi);

                            console.log(onay_table_data);

                            if(onay_table_data != null)
                            {
                                html += CreateConfirmationTableSessionDegerlendirenAst_Ast(onay_table_data,2,"ast_ast",Rule_map,user_data);
                            }
                            
                            $("#tabs_aaaf_Yetkinlik").html(html);

                            SetAstPernrHedefAndYetkinlikPoints(2,points_of_yetkinlik,"yp","ast_ast");
                        }

                        if(this.id == "aaaf_Potansiyel")
                        {
                            console.log(this.id);

                            $("#tabs_aaaf_Hedef").removeClass("active show");

                            ast_ast_pernr_list_forms = GetAstAstForm(year,donem,ast_pernr_list.ast_listesi,3);

                            if(!ast_ast_pernr_list_forms)
                            {
                                alert("Astlarınıza Ast Form Tanımlanmamış");
                            }

                            let potential_points = CollectPotentialPoint(year,donem,3,ast_ast_pernr_list.ast_listesi);

                            html = CreateTableAstlarForm(year,donem,3,user_data.pernr,ast_ast_pernr_list_forms,true,"ast_ast");
                            
                            onay_table_data = GetConfirmationProcessOfAst(year,donem,3,user_data.pernr,ast_pernr_list.ast_listesi);

                            if(onay_table_data != null)
                            {
                                html += CreateConfirmationTableSessionDegerlendirenAst_Ast(onay_table_data,3,"ast_ast",Rule_map,user_data);
                            }
                            
                            $("#tabs_aaaf_Potansiyel").html(html);

                            SetPernrListPotentialPoint(3,potential_points,"potential_point","ast_ast");
                            
                        }


                        if(this.id == "aaaf_Gorusme")
                        {
                            $("#tabs_aaaf_Hedef").removeClass("active show");

                            ast_ast_pernr_list_forms = GetAstAstForm(year,donem,ast_pernr_list.ast_listesi,4);

                            if(!ast_ast_pernr_list_forms)
                            {
                                alert("Astlarınıza Ast Form Tanımlanmamış");
                            }

                            html = CreateTableAstlarForm(year,donem,4,user_data.pernr,ast_ast_pernr_list_forms,false,"ast_ast");
                            
                            onay_table_data = GetConfirmationProcessOfAst(year,donem,4,user_data.pernr,ast_pernr_list.ast_listesi);

                            if(onay_table_data != null)
                            {
                                
                                html += CreateConfirmationTableSessionDegerlendirenAst_Ast(onay_table_data,4,"ast_ast",Rule_map,user_data);
                            }
                            
                            $("#tabs_aaaf_Gorusme").html(html);
                        }

                        if(this.id == "aaaf_Yonlendirme")
                        {
                            html ="";

                            ast_ast_pernr_list_forms = GetAstAstForm(year,donem,ast_pernr_list.ast_listesi,5);

                            if(!ast_ast_pernr_list_forms)
                            {
                                alert("Astlarınıza Ast Form Tanımlanmamış");
                            }

                            html = CreateTableAstlarForm(year,donem,5,user_data.pernr,ast_ast_pernr_list_forms,false,"ast_ast");

                            
                            onay_table_data = GetConfirmationProcessOfAst(year,donem,5,user_data.pernr,ast_pernr_list.ast_listesi);

                            if(onay_table_data != null)
                            {
                                html += CreateConfirmationTableSessionDegerlendirenAst_Ast(onay_table_data,5,"ast_ast",Rule_map,user_data);
                            }

                            $("#tabs_aaaf_Yonlendirme").html(html);
                        }

                        if(this.id == "aaaf_Sonuc")
                        {

                            html ="";

                            ast_ast_pernr_list_forms = GetAstAstForm(year,donem,ast_pernr_list.ast_listesi,1);

                            if(!ast_ast_pernr_list_forms)
                            {
                                alert("Astlarınıza Ast Form Tanımlanmamış");
                            }

                            console.log(ast_pernr_list.ast_listesi);

                            //points_of_hedef_and_yetkinlik = GetHedefAndYetkinlikPoints(year,donem,ast_ast_pernr_list.ast_listesi);

                            points_of_hedef_and_yetkinlik = GetTotalHedefYetkinlikPoints(year,donem,ast_ast_pernr_list.ast_listesi);
                            
                            console.log("TOTAL HP YP");
                            console.log(points_of_hedef_and_yetkinlik);

                            html = CreateResultTable(ast_ast_pernr_list_forms,"result_ast_ast");

                            $("#tabs_aaaf_Sonuc").html(html);

                            SetAstPernrHedefAndYetkinlikPoints(1,points_of_hedef,"hp","result_ast_ast");

                            SetAstPernrHedefAndYetkinlikPoints(2,points_of_yetkinlik,"yp","result_ast_ast");

                            SetPernrListResultTotalPoint(points_of_hedef_and_yetkinlik,"result_ast_ast");

                        }

                        $("#aaaf_Hedef").trigger('click');
                    }
                    
                });
                

                $("#aaf").one('click',function(){
                    
                    $('#aaf_Hedef').trigger('click')
                });
                $("#aaaf").one('click',function(){
                    
                    $('#aaaf_Hedef').trigger('click')
                });

            });

        }


        function ConfirmationActions(year,donem,ft_id,pernr,ust_pernr,confirmation_status)
        {
            let data = null;

            let payload = {
                "yil" : year,
                "donem": donem,
                "ft_id" : ft_id,
                "onaylayan": ust_pernr,
                "degelendiren": pernr,
                "onay_durumu": confirmation_status
            };

            $.ajax({
                method: "POST",  
                url:"/d_donemleri/onay_islemleri/1/",
                data: payload,
                async: false,
                dataType: 'JSON',
                cache: false,
                contentType: false,
                processData: false,
            })
            .done(function(response){
                
            });
        }


        function GetSessionFormPointEvaluateBySelf(year,donem,pernr)
        {
            let data = null;

            let payload = {
                "year" : year,
                "donem" : donem,
                "pernr": pernr,
            };

            $.ajax({
                method: "POST",  
                url:"/d_donemleri_new/GetSessionFormPointEvaluateBySelf",
                data: JSON.stringify(payload),
                async: false,
                dataType: 'JSON',
                cache: false,
                contentType: false,
                processData: false,
            })
            .done(function(response){
                
                data = response;

                //console.log(data);
            });

            return data;
        }


        function Get_KAF_HedefAndYetkinlik(year,donem,ft_id,pernr)
        {

            let data = null;

            let payload = {
                "year" : year,
                "donem" : donem,
                "ft_id" : ft_id,
                "pernr": pernr,
            };

            $.ajax({
                method: "POST",  
                url:"/d_donemleri_new/GetSessionFormOfPernr",
                data: JSON.stringify(payload),
                async: false,
                dataType: 'JSON',
                cache: false,
                contentType: false,
                processData: false,
            })
            .done(function(response){
                
                data = response;

                //console.log(data);
            });

            return data;

        }


        function CollectPotentialPoint(year,donem,ft_id,pernr_list)
        {
            let data = null;

            let payload = {
                "year" : year,
                "donem" : donem,
                "ft_id" : ft_id,
                "pernr_list": pernr_list,
            };

            $.ajax({
                method: "POST",  
                url:"/d_donemleri_new/CollectPotentialPoints",
                data: JSON.stringify(payload),
                async: false,
                dataType: 'JSON',
                cache: false,
                contentType: false,
                processData: false,
            })
            .done(function(response){
                
                data = response;

                //console.log(data);
            });

            return data;
        }


        function Get_AST_EmployeeData(year,donem,pernr)
        {
            let data = null;

            $.ajax({
                method: "POST",  
                url:"/d_donemleri_new/GetOrganizationLevelOne/"+pernr+"/"+year+"/"+ donem,
                async: false,
                dataType: 'JSON',
                cache: false,
                contentType: false,
                processData: false,
            })
            .done(function(response){
                
                data = response;

                //console.log(data);
            });

            return data;
        }

        


        function Get_AST_AST_EmployeeData(year,donem,pernr_list)
        {
            let data = null;

            let payload = {
                "year" : year,
                "donem" : donem,
                "pernr_list": pernr_list,
            };

            $.ajax({
                method: "POST",  
                url:"/d_donemleri_new/GetAstAstEmployeeData/",
                data: JSON.stringify(payload),
                async: false,
                dataType: 'JSON',
                cache: false,
                contentType: false,
                processData: false,
            })
            .done(function(response){
                
                data = response;

                //console.log(data);
            });

            return data;
        }


        function Get_AST_AST_EmployeeDataForm(year,donem,pernr_list,ft_id)
        {
            let data = null;

            let payload = {
                "year" : year,
                "donem" : donem,
                "pernr_list": pernr_list,
                "form_id": ft_id
            };

            $.ajax({
                method: "POST",  
                url:"/d_donemleri_new/GetAST_AST_EmployeeDataForm/",
                data: JSON.stringify(payload),
                async: false,
                dataType: 'JSON',
                cache: false,
                contentType: false,
                processData: false,
            })
            .done(function(response){
                
                data = response;

                //console.log(data);
            });

            return data;
        }

        function GetHedefAndYetkinlikPoints(year,donem,pernr_list)
        {

            
            let data = null;

            let payload = {
                "year" : year,
                "donem" : donem,
                "pernr_list": pernr_list
            };

            $.ajax({
                method: "POST",  
                url:"/d_donemleri_new/GetPointsOfPernrList/",
                data: JSON.stringify(payload),
                async: false,
                dataType: 'JSON',
                cache: false,
                contentType: false,
                processData: false,
            })
            .done(function(response){
                
                data = response;

            });

            return data;
        }

        function GetHedefFormPoints(year,donem,pernr_list)
        {

            
            let data = null;

            let payload = {
                "year" : year,
                "donem" : donem,
                "pernr_list": pernr_list
            };

            $.ajax({
                method: "POST",  
                url:"/d_donemleri_new/GetHedefFormPoints/",
                data: JSON.stringify(payload),
                async: false,
                dataType: 'JSON',
                cache: false,
                contentType: false,
                processData: false,
            })
            .done(function(response){
                
                data = response;

            });

            return data;
        }

        function GetYetkinlikFormPoints(year,donem,pernr_list)
        {

            
            let data = null;

            let payload = {
                "year" : year,
                "donem" : donem,
                "pernr_list": pernr_list
            };

            $.ajax({
                method: "POST",  
                url:"/d_donemleri_new/GetYetkinlikFormPoints/",
                data: JSON.stringify(payload),
                async: false,
                dataType: 'JSON',
                cache: false,
                contentType: false,
                processData: false,
            })
            .done(function(response){
                
                data = response;

            });

            return data;
        }




        function SetAstPernrHedefAndYetkinlikPoints(ft_id,data_of_points,point_field,prefix_for)
        {
            console.log("Setting Points of Table");
            
            console.log(point_field);

            for (let index = 0; index < data_of_points.length; index++) {

                //console.log("Before Setting point " + $("#"+ prefix_for +"_"+ ft_id + "_pernr_"+ data_of_points[index].pernr + "_point").html());

                //console.log(data_of_points[index].pernr + "----" +  data_of_points[index][point_field] );

                console.log("#"+ prefix_for +"_"+ ft_id + "_pernr_"+ data_of_points[index].pernr + "_point");

                $("#"+ prefix_for +"_"+ ft_id + "_pernr_"+ data_of_points[index].pernr + "_point").html(data_of_points[index][point_field]);
            }
        }



        function GetFormPernrList(ft_id,year,donem,pernr_list)
        {
            let data = null;

            let payload = {
                "year" : year,
                "donem" : donem,
                "ft_id": ft_id,
                "ust_pernr": pernr_list
            };

            $.ajax({
                method: "POST",  
                url:"/d_donemleri_new/GetFormOfPernrList/",
                data: JSON.stringify(payload),
                async: false,
                dataType: 'JSON',
                cache: false,
                contentType: false,
                processData: false,
            })
            .done(function(response){
                
                data = response;

            });

            return data;

        }


        function GetPointsOfPotentialFormOfPernr(year,donem,form_id,pernr)
        {
            let data = null;

            let payload = {
                "year" : year,
                "donem" : donem,
                "form_id": form_id,
                "pernr": pernr
            };

            $.ajax({
                method: "POST",  
                url:"/d_donemleri_new/GetPointsOfPotentialFormOfPernr/",
                data: JSON.stringify(payload),
                async: false,
                dataType: 'JSON',
                cache: false,
                contentType: false,
                processData: false,
            })
            .done(function(response){
                
                data = response;

            });

            return data;   
        }



        function GetAstAstForm(year,donem,pernr_list,ft_id)
        {
            let data = null;

            let payload = {
                "year" : year,
                "donem" : donem,
                "pernr_list": pernr_list,
                "form_id" : ft_id
            };

            $.ajax({
                method: "POST",  
                url:"/d_donemleri_new/GetAstAstForm/",
                data: JSON.stringify(payload),
                async: false,
                dataType: 'JSON',
                cache: false,
                contentType: false,
                processData: false,
            })
            .done(function(response){
                
                data = response;

            });

            return data;   
        }


        function GetConfirmationProcessOfAst(year,donem,ft_id,pernr,ast_listesi)
        {
            let data = null;

            let payload = {
                "year": year,
                "donem": donem,
                "ft_id": ft_id,
                "onaylayan": pernr,
                "degerlendiren_list": ast_listesi
            };

            console.log(payload);

            $.ajax({
                method: "POST",  
                url:"/d_donemleri_new/GetConfirmationProcessOfAst/",
                data: JSON.stringify(payload),
                async: false,
                dataType: 'JSON',
                cache: false,
                contentType: false,
                processData: false,
            })
            .done(function(response){
                
                data = response;

            });

            return data;

        }


        function CreateButton(form_id, rilen_pernr, year, donem, ft_id)
        {
            let html = "";

            html += '<a class="btn btn-primary" target="_blank" href="/d_donemleri/form_goruntule/' + form_id + '/'+ rilen_pernr +'/'+ year +'/' + donem + '/'+ ft_id + '"> Formu Tıklayınız </a>'

            return html;
        }


        function CreateTableAstlarForm(year,donem,ft_id,user_data,table_data,has_result_column,prefix_for)
        {

            console.log("Generating Table");
            console.log(table_data);
            let html = '';
            html += '<br />';

            html += '<h3>Değerlendirme İşlemleri</h3>';
            html += '<table class="table table-stripped">';
                html += "<thead>";
                    html += "<tr>";
                        html += "<th> Yöneticisi </th>";
                        html += "<th> Ad Soyad </th>";
                        html += "<th> Pozisyon </th>";

                        html += "<th> </th>";
                        if(has_result_column)
                        {
                            html += "<th> Puan </th>";
                        }
                        
                    html += "</tr>";
                html += "</thead>";
                html += '<tbody>';

                for(let i = 0; i< table_data.length; i++ )
                {
                    html += '<tr>';
                        html += "<td>";
                            html += table_data[i].ust_ad_soyad;
                        html += "</td>";
                        html += '<td>';
                            html += table_data[i].ad_soyad;
                        html += '</td>';
                        html += '<td>';
                            html += table_data[i].poz_text;
                        html += '</td>';
                        html += '<td>';
                            html += CreateButton(table_data[i].form_id,table_data[i].pernr,table_data[i].yil,table_data[i].donem,table_data[i].ft_id);
                        html += '</td>';

                        if(has_result_column)
                        {
                            //ast_"+ ft_id + "_pernr_"+ data_of_points[index].pernr + "_point"
                            html += '<td id="' + prefix_for + '_' +  ft_id + '_pernr_' + table_data[i].pernr +  '_point" >';
                                html += "Degerlendirme Yapılmadı";
                            html += '</td>';
                        }


                    html += '</tr>';
                }

                html += '</tbody>';
            html += '<table>';
            
            return html;
        }

        function SetPernrListPotentialPoint(ft_id,data_of_points,point_field,prefix_for)
        {
            for (let index = 0; index < data_of_points.length; index++) {

            //console.log("Before Setting point " + $("#"+ prefix_for +"_"+ ft_id + "_pernr_"+ data_of_points[index].pernr + "_point").html());

            //console.log(data_of_points[index].pernr + "----" +  data_of_points[index][point_field] );

                $("#"+ prefix_for +"_"+ ft_id + "_pernr_"+ data_of_points[index].pernr + "_point").html(data_of_points[index][point_field]);
            }
        }


        function SetPernrListResultTotalPoint(data_of_points,prefix_for)
        {
            
            let total = 0;
            for (let index = 0; index < data_of_points.length; index++) {

                total = parseFloat(data_of_points[index].total);
                
                //console.log("#"+ prefix_for +"_total_pernr_"+ data_of_points[index].pernr + "_point");
                $("#"+ prefix_for +"_total_pernr_"+ data_of_points[index].pernr + "_point").html(total);
                
            }

            
        }


        function CreateResultTable(table_data,prefix_for)
        {
            let html = '';
            html += '<br />';

            html += '<h3>Değerlendirme İşlemleri</h3>';
            html += '<table class="table table-stripped">';
                html += '<thead>';
                    html += '<th>Ad Soyad</th>';
                    html += '<th>Pozisyon</th>';
                    html += '<th>Hedef Puanı</th>';
                    html += '<th>Yetkinlik Puanı</th>';
                    html += '<th>Toplam Puan</th>';
                html += '</thead>';

                html += '<tbody>';
                for(let i= 0; i < table_data.length; i++)
                {
                    html += '<tr>';
                    html += '<td>' + table_data[i].ad_soyad + '</td>';
                    html += '<td>' + table_data[i].poz_text + '</td>';
                    html += '<td id="' + prefix_for + '_1_pernr_' + table_data[i].pernr + '_point"> Degerlendirme Yapılmadı </td>';
                    html += '<td id="' + prefix_for + '_2_pernr_' + table_data[i].pernr + '_point"> Degerlendirme Yapılmadı </td>';
                    html += '<td id="' + prefix_for + '_total_pernr_' + table_data[i].pernr + '_point"> Degerlendirme Yapılmadı </td>';
                    html += '</tr>';
                }
                html += '</tbody>';

            html += '</table>';

            return html;
        }


        function CreateConfirmationTableSessionDegerlendirenAst(table_data,ft_id,prefix_for,Rule_map,user_data)
        {
            let html = '';
            html += '<br />';
            
            html += "<h3>Onaylama İşlemleri</h3>";
            html += '<table class="table table-stripped">';
                html += '<thead>';
                    html += '<th>Değerlendiren</th>';
                    html += '<th>Onaylayan</th>';
                    html += '<th>Onay Durumu</th>';
                html += '</thead>';

                html += '<tbody>';
                for(let i= 0; i < table_data.length; i++)
                {
                    html += '<tr>';
                        html += '<td>' + table_data[i].ad_soyad + '</td>';
                        html += '<td>' + table_data[i].ust_ad_soyad + '</td>';
                    
                        html += '<td>' + CreateButton_AST_AST_CRU(table_data[i],Rule_map,user_data,user_data) + '</td>';
                    html += '</tr>';
                }
                html += '</tbody>';

            html += '</table>';

            return html;
        }

        function CreateConfirmationTableSessionDegerlendirenAst_Ast(table_data,ft_id,prefix_for,Rule_map,user_data)
        {
            let html = '';
            html += '<br />';
            
            html += "<h3>Onaylama İşlemleri</h3>";
            html += '<table class="table table-stripped">';
                html += '<thead>';
                    html += '<th>Değerlendiren</th>';
                    html += '<th>Onaylayan</th>';
                    html += '<th>Onay Durumu</th>';
                html += '</thead>';

                html += '<tbody>';
                for(let i= 0; i < table_data.length; i++)
                {
                    html += '<tr>';
                        html += '<td>' + table_data[i].ad_soyad + '</td>';
                        html += '<td>' + table_data[i].ust_ad_soyad + '</td>';
                        html += '<td>' + CreateButton_AST_AST_CRU(table_data[i],Rule_map,user_data) + '<td>';
                    html += '</tr>';
                }
                html += '</tbody>';

            html += '</table>';

            return html;
        }


        function CreateButton_AST_CRU(table_data_row,Rule_map,session_user)
        {
            let html = "";
            
            if(table_data_row.onaylayan == session_user.pernr)
            {
                if( table_data_row.onay_durum == 4 ) // //Level 1
                {
                    html += '<form method="POST" action="http://peryonsis-qa.santafarma.com.tr/d_donemleri/onay_islemleri/1">';
                    html += '<input type="hidden" name="yil" value="' + table_data_row.yil + '">';
                    html += '<input type="hidden" name="donem" value="' + table_data_row.donem + '">';
                    html += '<input type="hidden" name="ft_id" value="' + table_data_row.ft_id + '">';
                    html += '<input type="hidden" name="onay_durum" value="1">';
                    html += '<input type="hidden" name="onaylayan" value="' + table_data_row.onaylayan + '">';
                    html += '<input type="hidden" name="degerlendiren" value="' + table_data_row.degerlendiren + '">';
                    html += '<input type="submit" class="btn btn-primary" value="Onayla">';
                    html += '</form>';

                    html += '<form method="POST" action="http://peryonsis-qa.santafarma.com.tr/d_donemleri/onay_islemleri/1">';
                    html += '<input type="hidden" name="yil" value="' + table_data_row.yil + '">';
                    html += '<input type="hidden" name="donem" value="' + table_data_row.donem + '">';
                    html += '<input type="hidden" name="ft_id" value="' + table_data_row.ft_id + '">';
                    html += '<input type="hidden" name="onay_durum" value="2">';
                    html += '<input type="hidden" name="onaylayan" value="' + table_data_row.onaylayan + '">';
                    html += '<input type="hidden" name="degerlendiren" value="' + table_data_row.degerlendiren + '">';
                    html += '<input type="submit" class="btn btn-danger" value="Red Et">';
                    html += '</form>';
                }

                else if( table_data_row.onay_durum == 3 )
                {
                    html += '<p>AST_CRU-Onaylayan-Onay Gönderilmesi Bekliyor </p>';
                }

                else if( table_data_row.onay_durum == 2 )
                {
//                    html += '<p>'+ table_data_row.onay_text +'</p>';
                        html += '<p>AST_CRU-Onaylayan-Red edildi. </p>';
                }

                else if( table_data_row.onay_durum == 1 )
                {
                    html += '<p>'+ table_data_row.onay_text +'</p>';
                    
                    html += '<form method="POST" action="http://peryonsis-qa.santafarma.com.tr/d_donemleri/onay_islemleri/1">';
                    html += '<input type="hidden" name="yil" value="' + table_data_row.yil + '">';
                    html += '<input type="hidden" name="donem" value="' + table_data_row.donem + '">';
                    html += '<input type="hidden" name="ft_id" value="' + table_data_row.ft_id + '">';
                    html += '<input type="hidden" name="onay_durum" value="3">';
                    html += '<input type="hidden" name="onaylayan" value="' + table_data_row.onaylayan + '">';
                    html += '<input type="hidden" name="degerlendiren" value="' + table_data_row.degerlendiren + '">';
                    html += '<input type="submit" class="btn btn-warning" value="Geri Al">';
                    html += '</form>';
                    
                }


            }

            if(table_data_row.degerlendiren == session_user.pernr) 
            {
                if( table_data_row.onay_durum == 4 )
                {

                }

                else if( table_data_row.onay_durum == 3 )
                {

                }
                
                else if( table_data_row.onay_durum == 2 )
                {

                }

                else if( table_data_row.onay_durum == 1 )
                {
                    
                }
            }

            
            return html;
        }


        function CreateButton_AST_AST_CRU(table_data_row,Rule_map,session_user)
        {

            let html = "";
            
            if(table_data_row.onaylayan == session_user.pernr)   
            {
                if( table_data_row.onay_durum == 4 )
                {
                    html += '<p>'+ table_data_row.onay_text +'</p>';
                    html += '<form method="POST" action="http://peryonsis-qa.santafarma.com.tr/d_donemleri/onay_islemleri/1">';
                    html += '<input type="hidden" name="yil" value="' + table_data_row.yil + '">';
                    html += '<input type="hidden" name="donem" value="' + table_data_row.donem + '">';
                    html += '<input type="hidden" name="ft_id" value="' + table_data_row.ft_id + '">';
                    html += '<input type="hidden" name="onay_durum" value="1">';
                    html += '<input type="hidden" name="onaylayan" value="' + table_data_row.onaylayan + '">';
                    html += '<input type="hidden" name="degerlendiren" value="' + table_data_row.degerlendiren + '">';
                    html += '<input type="submit" class="btn btn-primary" value="Onayla">';
                    html += '</form>';
                    
                    html += '<form method="POST" action="http://peryonsis-qa.santafarma.com.tr/d_donemleri/onay_islemleri/1">';
                    html += '<input type="hidden" name="yil" value="' + table_data_row.yil + '">';
                    html += '<input type="hidden" name="donem" value="' + table_data_row.donem + '">';
                    html += '<input type="hidden" name="ft_id" value="' + table_data_row.ft_id + '">';
                    html += '<input type="hidden" name="onay_durum" value="2">';
                    html += '<input type="hidden" name="onaylayan" value="' + table_data_row.onaylayan + '">';
                    html += '<input type="hidden" name="degerlendiren" value="' + table_data_row.degerlendiren + '">';
                    html += '<input type="submit" class="btn btn-danger" value="Red Et">';
                    html += '</form>';

                }

                else if( table_data_row.onay_durum == 3 )
                {
                    html += '<p>'+ table_data_row.onay_text +'</p>';
                }

                else if( table_data_row.onay_durum == 2 )
                {
                    html += '<p>' + table_data_row.onay_text +'</p>';  
                }

                else if( table_data_row.onay_durum == 1 )
                {
                    html += '<p>'+ table_data_row.onay_text +'</p>';
                    if(Rule_map.session_ust_onay_data[1]) // Yelda Hanımın isteğine göre şekillenecek
                    {
                        html += '<form method="POST" action="http://peryonsis-qa.santafarma.com.tr/d_donemleri/onay_islemleri/1">';
                        html += '<input type="hidden" name="yil" value="' + table_data_row.yil + '">';
                        html += '<input type="hidden" name="donem" value="' + table_data_row.donem + '">';
                        html += '<input type="hidden" name="ft_id" value="' + table_data_row.ft_id + '">';
                        html += '<input type="hidden" name="onay_durum" value="3">';
                        html += '<input type="hidden" name="onaylayan" value="' + table_data_row.onaylayan + '">';
                        html += '<input type="hidden" name="degerlendiren" value="' + table_data_row.degerlendiren + '">';
                        html += '<input type="submit" class="btn btn-warning" value="Y Onayı Geri Al">';
                        html += '</form>';
                        
                    }
                    

                }
            }

            if(table_data_row.degerlendiren == session_user.pernr) //Level 2
            {
                if( table_data_row.onay_durum == 4)
                {
                    html += '<p>'+ table_data_row.onay_text +'</p>';
                    html += '<form method="POST" action="http://peryonsis-qa.santafarma.com.tr/d_donemleri/onay_islemleri/1">';
                    html += '<input type="hidden" name="yil" value="' + table_data_row.yil + '">';
                    html += '<input type="hidden" name="donem" value="' + table_data_row.donem + '">';
                    html += '<input type="hidden" name="ft_id" value="' + table_data_row.ft_id + '">';
                    html += '<input type="hidden" name="onay_durum" value="3">';
                    html += '<input type="hidden" name="onaylayan" value="' + table_data_row.onaylayan + '">';
                    html += '<input type="hidden" name="degerlendiren" value="' + table_data_row.degerlendiren + '">';
                    html += '<input type="submit" class="btn btn-warning" value="Geri Al">';
                    html += '</form>';
                }

                else if( table_data_row.onay_durum == 3 )
                {
                    html += '<p>'+ table_data_row.onay_text +'</p>';
                    html += '<form method="POST" action="http://peryonsis-qa.santafarma.com.tr/d_donemleri/onay_islemleri/1">';
                    html += '<input type="hidden" name="yil" value="' + table_data_row.yil + '">';
                    html += '<input type="hidden" name="donem" value="' + table_data_row.donem + '">';
                    html += '<input type="hidden" name="ft_id" value="' + table_data_row.ft_id + '">';
                    html += '<input type="hidden" name="onay_durum" value="4">';
                    html += '<input type="hidden" name="onaylayan" value="' + table_data_row.onaylayan + '">';
                    html += '<input type="hidden" name="degerlendiren" value="' + table_data_row.degerlendiren + '">';
                    html += '<input type="submit" class="btn btn-primary" value="Onaya Gönder">';
                    html += '</form>';
                }
                
                else if( table_data_row.onay_durum == 2 )
                {
                    html += '<p>'+ table_data_row.onay_text +'</p>';
                    html += '<form method="POST" action="http://peryonsis-qa.santafarma.com.tr/d_donemleri/onay_islemleri/1">';
                    html += '<input type="hidden" name="yil" value="' + table_data_row.yil + '">';
                    html += '<input type="hidden" name="donem" value="' + table_data_row.donem + '">';
                    html += '<input type="hidden" name="ft_id" value="' + table_data_row.ft_id + '">';
                    html += '<input type="hidden" name="onay_durum" value="4">';
                    html += '<input type="hidden" name="onaylayan" value="' + table_data_row.onaylayan + '">';
                    html += '<input type="hidden" name="degerlendiren" value="' + table_data_row.degerlendiren + '">';
                    html += '<input type="submit" class="btn btn-primary" value="Onaya Gönder">';
                    html += '</form>';
                }

                else if( table_data_row.onay_durum == 1 )
                {
                    html += '<p>'+ table_data_row.onay_text +' </p>';
                    
                }
            }

            
            
            return html;
        }


        function GetTotalHedefYetkinlikPoints(year,donem,pernr_list)
        {
            let data = null;

            let payload = {
                "year": year,
                "donem": donem,
                "pernr_list": pernr_list
            };

            $.ajax({
                method: "POST",  
                url:"/d_donemleri_new/GetTotalHedefYetkinlikPoints/",
                data: JSON.stringify(payload),
                async: false,
                dataType: 'JSON',
                cache: false,
                contentType: false,
                processData: false,
            })
            .done(function(response){
                
                data = response;

            });

            return data;
        }


        function GetRoleOfKademelendirme(year,donem,k_pernr)
        {
            
            let data = null;

            let payload = {
                "year": year,
                "donem": donem,
                "k_pernr": k_pernr
            };

            $.ajax({
                method: "POST",  
                url:"/d_donemleri_new/GetRoleOfKademelendirme/",
                data: JSON.stringify(payload),
                async: false,
                dataType: 'JSON',
                cache: false,
                contentType: false,
                processData: false,
            })
            .done(function(response){
                
                data = response;

            });

            return data;
        }


        function IsKademelendirmeStart(year,donem,havuz_id)
        {
            let data = null;

            let payload = {
                "year": year,
                "donem": donem,
                "havuz_id": havuz_id
            };

            $.ajax({
                method: "POST",  
                url:"/d_donemleri_new/IsKadelendirmeStart/",
                data: JSON.stringify(payload),
                async: false,
                dataType: 'JSON',
                cache: false,
                contentType: false,
                processData: false,
            })
            .done(function(response){
                
                data = response;

            });

            return data;
        }

        function SessionIsManager(year,donem,pernr)
        {
            let data = null;

            let payload = {
                "year": year,
                "donem": donem,
                "pernr": pernr
            };

            $.ajax({
                method: "POST",  
                url:"/d_donemleri_new/SessionIsManager/",
                data: JSON.stringify(payload),
                async: false,
                dataType: 'JSON',
                cache: false,
                contentType: false,
                processData: false,
            })
            .done(function(response){
                
                data = response;

            });

            return data;
        }
        
        function GetRoleOfKademelendirmeBaseOfPernr(year,donem,pernr)
        {
            let data = null;

            let payload = {
                "year": year,
                "donem": donem,
                "pernr": pernr
            };

            $.ajax({
                method: "POST",  
                url:"/d_donemleri_new/GetRoleOfKademelendirmeBaseOfPernr/",
                data: JSON.stringify(payload),
                async: false,
                dataType: 'JSON',
                cache: false,
                contentType: false,
                processData: false,
            })
            .done(function(response){
                
                data = response;

            });

            return data;
        }


        

        function GetTotalHedefAndYetknlikBySelfSession(year,donem,pernr)
        {
            let data = null;

            let payload = {
                "year": year,
                "donem": donem,
                "pernr": pernr
            };

            $.ajax({
                method: "POST",  
                url:"/d_donemleri_new/GetTotalHedefAndYetknlikBySelfSession/",
                data: JSON.stringify(payload),
                async: false,
                dataType: 'JSON',
                cache: false,
                contentType: false,
                processData: false,
            })
            .done(function(response){

                data = response;

            });

            return data;
        }
        
        function CheckDegerlendirmeler(year,donem,degerlendiren_pernr)
        {
            let data = null;

            let payload = {
                "year": year,
                "donem": donem,
                "degerlendiren_pernr": degerlendiren_pernr
            };

            $.ajax({
                method: "POST",  
                url:"/d_donemleri_new/CheckDegerlendirmeler/",
                data: JSON.stringify(payload),
                async: false,
                dataType: 'JSON',
                cache: false,
                contentType: false,
                processData: false,
            })
            .done(function(response){

                data = response;

            });

            return data;
        }

        function CheckConfirmationToDeep(year,donem,session_pernr,ast_pernr_list)
        {
            let data = null;

            let payload = {
                "year": year,
                "donem": donem,
                "session_user_pernr": session_pernr,
                "ast_listesi": ast_pernr_list
            };

            $.ajax({
                method: "POST",  
                url:"/d_donemleri_new/CheckConfirmationToDeep/",
                data: JSON.stringify(payload),
                async: false,
                dataType: 'JSON',
                cache: false,
                contentType: false,
                processData: false,
            })
            .done(function(response){

                data = response;

            });

            return data;
        }

        function IsKademeManagerIsConfirmed(year,donem,onaylayan)
        {
            let data = null;

            let payload = {
                "year": year,
                "donem": donem,
                "onaylayan": onaylayan
            };

            $.ajax({
                method: "POST",  
                url:"/d_donemleri_new/IsKademeManagerIsConfirmed/",
                data: JSON.stringify(payload),
                async: false,
                dataType: 'JSON',
                cache: false,
                contentType: false,
                processData: false,
            })
            .done(function(response){

                data = response;

            });

            return data;
        }
        
        function GetUstListesi(year,donem,session_pernr)
        {
            let data = null;
            let payload = {
                "year": year,
                "donem": donem,
                "session_pernr": session_pernr
            };

            $.ajax({
                method: "POST",  
                url:"/d_donemleri_new/GetUstListesi/",
                data: JSON.stringify(payload),
                async: false,
                dataType: 'JSON',
                cache: false,
                contentType: false,
                processData: false,
            })
            .done(function(response){

                data = response;

            });

            return data;
        }

        function GetUstOnay(year,donem,onaylayan,degerlendiren)
        {
            let data = null;
            debugger;
            let payload = {
                "year": year,
                "donem": donem,
                "onaylayan": onaylayan,
                "degerlendiren": degerlendiren
            };

            $.ajax({
                method: "POST",  
                url:"/d_donemleri_new/GetUstOnay/",
                data: JSON.stringify(payload),
                async: false,
                dataType: 'JSON',
                cache: false,
                contentType: false,
                processData: false,
            })
            .done(function(response){

                data = response;

            });

            return data;
        }

</script>

<?=$footer?>