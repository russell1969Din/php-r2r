        <link rel="stylesheet" type="text/css"  href="<?=$_SESSION["DOMAIN_PATH"]?>/css/form.css" />

        <form id="form" data-selectedlist = "2" data-selectedtext="Potvrdené: # x" data-uncheckalltext="Zrušiť všetko" data-checkalltext="Označiť všetko" action="" method="post" class="smartsearch">
        <div class="container mt-5">

                <div class="row ">
                        <div id="pers_name_title" style="" class="col-sm-2 pt-2 text-left ">Krstné meno:</div>
                        <div class="col-sm-4 ">
                                <input type="text" alt="C~2" class="form-control text-dark" placeholder="Zadajte svoje krstné meno" id="pers_name" name="pers_name" />
                                <div id="pers_name_err" class="" style="height:20px;"></div>
                        </div>
                        <div id="pers_surName_title" class="col-sm-2 pt-2 text-left ">Priezvisko:</div>
                        <div class="col-sm-4 ">
                                <input type="text" alt="C~2" class="form-control text-dark" placeholder="Zadajte svoje priezvisko" id="pers_surName" name="pers_surName"  />
                                <div id="pers_surName_err" class="" style="height:20px;"></div>                 
                        </div>
                </div>
                
                <div class="row ">
                        <div id="pers_birth_title"  class="col-sm-2 pt-2 text-left ">Narodený/á:</div>
                        <div class="col-sm-4 ">
                                <input type="text" alt="D~2" class="form-control" placeholder="99-99-9999" id="pers_birth" name="pers_birth" />
                                <div id="pers_birth_err" class="" style="height:20px;"></div>
                        </div>
                        <div id="pers_age_title" class="col-sm-2 pt-2 text-left ">Vek:</div>
                        <div class="col-sm-4 ">
                                <!-- class select-->
                                <div id="pers_age_nice" class="clearfix border" style="background-color:#fff;border-radius:5px;">
                                        <select alt="ND~2~~Vek je povinný údaj" class="nice-select " id="pers_age"  name="pers_age">
                                                <option value="">Zadajte vek</option>
                                                <option value="18-25">18-25</option>
                                                <option value="18-25">26-40</option>
                                                <option value="18-25">41-50</option>
                                                <option value="18-25">51-60</option>
                                                <option value="18-25" disabled>61-viac</option>
                                        </select>
                                </div>
                                <div id="pers_age_err" class="" style="height:20px;"></div>
                                <script>
                                        $(document).ready(function() {$('#pers_age').niceSelect();});
                                </script> 
                        </div>
                </div>
                
                <div class="row ">
                        <div id="pers_telephone_title" class="col-sm-2 pt-2 text-left ">Telefón:</div>
                        <div class="col-sm-4 ">
                                <input type="text" alt="T~2" class="form-control" placeholder="Telefónne číslo" id="pers_telephone" name="pers_telephone" />
                                <div id="pers_telephone_err" class="" style="height:20px;"></div>
                        </div>
                        <div id="pers_email_title" class="col-sm-2 pt-2 text-left ">E-mail:</div>
                        <div class="col-sm-4 ">
                                <input type="text" alt="E~2" class="form-control" placeholder="E-mailová adresa" id="pers_email" name="pers_email" />
                                <div id="pers_email_err" class="" style="height:20px;"></div>
                        </div>
                </div>
                

                <div class="row ">
                        <div id="pers_hobby_title" id="pers_hobby_tile" class="col-sm-2 pt-2 text-left ">Záujmy a záľuby:</div>
                        <div class="col-sm-4 ">
                                <textarea alt="~5" class="form-control" rows="5" id="pers_hobby" name="pers_hobby"></textarea>
                                <div id="pers_hobby_err" class="" style="height:20px;"></div>
                        </div>
                        <div id="pers_attitude_title" class="col-sm-2 pt-2 text-left ">Postoj k náboženstvu:</div>
                        <div class="col-sm-4 ">
                                <div class="radio">
                                        <label><input type="radio" id="pers_attitude_1" name="pers_attitude" value="1" checked>Veriaci</label>
                                </div>
                                <div class="radio">
                                        <label><input type="radio" id="pers_attitude_2" name="pers_attitude" value="2">Neveriaci</label>
                                </div>
                                <div class="radio disabled">
                                        <label><input type="radio" id="pers_attitude_3" name="pers_attitude" value="3">Neutrálny postoj</label>
                                </div>                        
                        </div>
                </div>

                <div class="row ">
                        <div id="pers_know_title" class="col-sm-2 pt-2 text-left ">IT znalosti:</div>
                        <div class="col-sm-4 ">
                                <div class="element" data-label="Aspoň jedna znalosť">
                                        <div class="smartElement" data-type="select" data-name="pers_know">
                                                <input class="smart_pers_knowCallback callbackValues" name="pers_knowAjax" type="hidden" value=""/>
                                                <!--alt="NCO~1~~~Aspoň jedna znalosť" -->
                                                <select alt="NCO~1~~Aspoň jeden typ kódu" name="pers_know[]" multiple="multiple" id="smart_pers_know" class="multiselect" >
                                                        <option  value="1" >&nbsp;HTML</option>
                                                        <option  value="2" >&nbsp;Bootstrap</option>
                                                        <option  value="3" >&nbsp;.NET C#</option>
                                                        <option  value="4" >&nbsp;.NET VB</option>
                                                        <option  value="5" >&nbsp;PHP</option>
                                                        <option  value="6" >&nbsp;Javascript</option>
                                                        <option  value="7" >&nbsp;jQuery</option>
                                                        <option  value="8" >&nbsp;AJAX</option>
                                                </select>
                                                <?$form .= "|pers_know~";?>
                                                <div id="pers_know_err" class="" style="height:20px;"></div>
                                        </div>
                                </div>
                         </div>
                        
                        <div id="pers_lang_title" class="col-sm-2 pt-2 text-left ">Iné jazyky:</div>
                        <div class="col-sm-4 ">
                        <div class="element" data-label="Aspoň jeden jazyk">
                                <div class="smartElement" data-type="select" data-name="pers_lang">
                                <input class="smart_pers_langCallback callbackValues" name="pers_langAjax" type="hidden" value=""/>
                                        <!--alt="NCO~1~~~Aspoň jedna znalosť"--> 
                                        <select alt="NCO~1~~Aspoň jeden jazyk" name="pers_lang[]" multiple="multiple" id="smart_pers_lang" class="multiselect" >
                                                <option  value="1" >&nbsp;Anglicky</option>
                                                <option  value="2" >&nbsp;Nemecky</option>
                                                <option  value="3" >&nbsp;Francúzky</option>
                                                <option  value="4" >&nbsp;Španielsky</option>
                                                <option  value="5" >&nbsp;Maďarsky</option>
                                                <option  value="6" >&nbsp;Rusky</option>
                                                <option  value="7" >&nbsp;Čínsky</option>
                                                <option  value="8" >&nbsp;Perfektne česky</option>
                                        </select>
                                                <div id="pers_lang_err" class="" style="height:20px;"></div>
                                        </div>
                                </div>
                        </div>
                </div>
                
                <!----->
      
                <div class="mt-5 " style="width:100%;">  
                        <div class="progress ">
                                <div id="progress_form" class="progress-bar text-white text-bold text-center progressNote" style="width:0%;font-size:12px;">
                                        0%        
                                </div>
                        </div>
                </div>

                <div id="approvalDesk_form" style="display:none;" class="text-center pt-1 " style="width:100% ">
                        Vyhlasujem že všetky údaje som vyplnil správne:&nbsp; 
                        <input type="checkbox" class="form-check-input" id="approval_form" />
                </div>    
        
                <div class="container text-center buttonDesk pt-3" >
                        <input type="button" alt="captcha" id="save_form" disabled onclick="clickButton(this, 'fSubmit()')" class="btn btn-primary" value="Vložiť" />
                        
                </div>
                
        
                        <!--    prefix id
                        
                                err msg color ~ 
                                ok msg color ~ 
                                err title backgroundColor ~ 
                                err title text color ~ 
                                size error msg
                                font error msg
                                weight error msg
                                other tag (italic) -->
                <input type="hidden" id="inspect_form" name="inspect_form" value="pers~#cc0000~green~~#cc0000~12~arial~bold~i" />
        </div>
        </form>
        