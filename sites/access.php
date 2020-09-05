<link rel="stylesheet" type="text/css"  href="/css/personCard.css" />
<link rel="stylesheet" type="text/css"  href="/css/form.css" />

<?
        if($_SESSION["SYS_ACCOUNT"]["acc_admin"] < 1) {?><script>window.location.href="http://<?=$_SERVER['SERVER_NAME']?>"</script><?}

        $aMsg = array("Naozaj vymazať označený prístupový účet ?");
        $aAddMsg = array("Tento krok môže späť vrátiť len správca db !!!");
        $aYesButton = array("delete_formAcc");
        $aNoButton  = array("Ee", "info");
        bsModal("delModal", $aMsg, $aAddMsg, $aYesButton);
        
        if($_POST["setPath_formAcc"] > 0) {
?>
                <div class="container text-center pt-2 pb-2 ">
<?
                $aUser = $db->get("sysUserAccounts", "sysUserAccounts", "acc_freeEntry", "acc_id='".$_POST["setPath_formAcc"]."'", true, __FILE__, __LINE__, true);
                $path = $_SESSION["DOMAIN_PATH"]."/admin/change?id=".$_POST["setPath_formAcc"]."&free=".$aUser["acc_freeEntry"];
?>
                <input data-toggle="tooltip" title="Potvrdením myšky skopírujete." type="text" id="path" value="<?=$path?>" style="text-align:center;background-color:#B5B5B5;cursor:pointer;border:0px;" onclick="textCopy()" readonly="readonly" />
                </div>

                <script>
                function textCopy() {
                        
                        var copyText = document.getElementById("path");
                        copyText.select();
                        copyText.setSelectionRange(0, 99999);
                        document.execCommand("copy");
                        copyText.setSelectionRange(0, 0);
                        $("#path").prop('title', 'Cesta bola skopírovaná');
                        $("#path").blur(); 
                        document.getElementById("path").style.color="#CCCCCC";
                        
                        
                }
                </script>
<?
        }
        
        if($_POST["setDel_formAcc"]>0) {
                 $db->update("sysUserAccounts", "acc_isDelete", 1, "acc_id = '".$_POST["setDel_formAcc"]."'", __FILE__, __LINE__);
                 $_SESSION["INITIALS"] = null;
                 $db->flushSessionsBank();
                 bsAlert("Označený užívateľ bol úspešne vymazaný", 2);
        }
                          
        if(strLen(Trim($_POST["saveRight_formAcc"]))>0) {
                $db->delete("menuRightsAccess", "rig_idUser = '".$_POST["saveRight_formAcc"]."'", __FILE__, __LINE__);
                $oMenuId = $db->get("RIGHT_MENUID", "generalMenu", null, "men_idParent > 0", true, __FILE__, __LINE__);
                $aValues = array();
                foreach($oMenuId as $aId) {
                        eval('$isChecked = $_POST["right_'.$aId["men_id"].'"];');
                        if($isChecked == "on") {
                                $aValues[] = array("rig_idUser"=>$_POST["saveRight_formAcc"], "rig_idMenu"=>$aId["men_id"]);
                        }
                }
                $db->insert("menuRightsAccess", $aValues, false, __FILE__, __LINE__);
                $db->flushSessionsBank();
                bsAlert("Práva pri editovanom užívateľovi boli úspešne zmenené", 1);
        }

        if($_POST["change_formAcc"] == 1) {
                if($_POST["acc_admin"] == "on")         {$admin=1;} else {$admin=0;}
                if($_POST["acc_block"] == "on")         {$block=1;} else {$block=0;}
                if($_POST["acc_supervisor"] == "on")    {$super=1;} else {$super=0;}
                
                if($_POST["acc_chatMail"] == "on")      {$chMail=1;} else {$chMail=0;}
                if($_POST["acc_chatAccess"] == "on")    {$chAccess=1;} else {$chAccess=0;}
                if($_POST["acc_chatAdmin"] == "on")     {$chAdmin=1;} else {$chAdmin=0;}
                if($_POST["acc_free"] == "on")          {$free=1;} else {$free=0;}
                if($_POST["acc_demo"] == "on")          {$demo=1;} else {$demo=0;}
                
                $randString = "";
                if($free==1) {$randString = randomString(20);}
                

                $aValues    = array();
                $aValues[]  = array("acc_login"=>$_POST["acc_login"], 
                                    "acc_pass"=>$_POST["acc_pass"], 
                                    "acc_name"=>$_POST["acc_name"], 
                                    "acc_surName"=>$_POST["acc_surName"], 
                                    "acc_email"=>$_POST["acc_email"], 
                                    "acc_mobil"=>$_POST["acc_mobil"] , 
                                    "acc_demo"=>$demo,
                                    "acc_admin"=>$admin,
                                    "acc_supervisor"=>$super,
                                    "acc_chatMail"=>$chMail, 
                                    "acc_chatAccess"=>$chAccess, 
                                    "acc_chatAdmin"=>$chAdmin,
                                    "acc_free"=>$free,    
                                    "acc_freeEntry"=>$randString,    
                                    "acc_block"=>$block);                
                $db->insert("sysUserAccounts", $aValues, false, __FILE__, __LINE__);
                $_SESSION["INITIALS"] = null;
                $db->flushSessionsBank();
                bsAlert("Nový uživateľ bol úspešne pridaný", 1);
                //$db->get("GLOBAL_MENU", "generalMenu", null, "men_delete=0 ORDER BY men_index ASC", true, __FILE__, __LINE__);
        }
        
        if($_POST["change_formAcc"] == 2) {
                if($_POST["acc_admin"] == "on") {$admin=1;} else {$admin=0;}
                if($_POST["acc_block"] == "on") {$block=1;} else {$block=0;}
                if($_POST["acc_supervisor"] == "on")    {$super=1;} else {$super=0;}
                if($_POST["acc_chatMail"] == "on")      {$chMail=1;} else {$chMail=0;}
                if($_POST["acc_chatAccess"] == "on")    {$chAccess=1;} else {$chAccess=0;}
                if($_POST["acc_chatAdmin"] == "on")     {$chAdmin=1;} else {$chAdmin=0;}
                 if($_POST["acc_demo"] == "on")          {$demo=1;} else {$demo=0;}
                
                $aPayment = dateTimeToArray($_POST["acc_payment"],"AM");
                $payment = $aPayment["rr"]."-".$aPayment["mm"]."-".$aPayment["dd"];
                
                $aFields = array("acc_name", "acc_surName", "acc_email", "acc_mobil", "acc_login", "acc_admin", "acc_demo", "acc_block", "acc_supervisor", "acc_chatAccess", "acc_chatMail", "acc_chatAdmin", "acc_payment");
                $aValues = array($_POST["acc_name"], $_POST["acc_surName"], $_POST["acc_email"], $_POST["acc_mobil"], $_POST["acc_login"], $admin, $demo, $block, $super, $chAccess, $chMail, $chAdmin, $payment);
                $db->update("sysUserAccounts", $aFields, $aValues, "acc_id = '".$_POST["acc_id"]."'", __FILE__, __LINE__);
                $_SESSION["INITIALS"] = null;
                $db->flushSessionsBank();
                bsAlert("Pri editovanom užívateľovi boli zmenené údaje", 2);
        }
        
        if($_POST["setType_formAcc"] > 0) {
                $oEdit = $db->get("EDIT", "sysUserAccounts", null, "acc_id = '".$_POST["setType_formAcc"]."'", true, __FILE__, __LINE__, true);
        }

        if(strLen(Trim($_GET["id"])) == 0) {
                $where = " acc_isDelete = 0";
                if(strLen(Trim($_POST["initials"])) == 1) {$where = " acc_isDelete = 0 && INSTR(acc_surName, '".$_POST["initials"]."') = 1 ";}
        } else {
                $where = " acc_id = ".$_GET["id"];
        }
        
                                                      
        $oUsers = $db->get("USERS", "sysUserAccounts", null, $where." ORDER BY acc_surName", true, __FILE__, __LINE__, false);
        if($_GET["id"] > 0) {
                $aUsers = $oUsers[0];
                $_POST["initials"] = strToUpper(subStr($aUsers["acc_surName"], 0, 1));
        }
        
        
        $_SESSION["INITIALS"] = null;
        if(getType($_SESSION["INITIALS"]) == "NULL") {
                if($_GET["id"] > 0) {$whereInit = " acc_isDelete = 0 ";} else {$whereInit = " acc_isDelete = 0 ";} 
                $oUsers = $db->get("USERS", "sysUserAccounts", null, $whereInit." ORDER BY acc_surName", true, __FILE__, __LINE__, false);
                $aInitials = array();
                foreach($oUsers as $user) {
                        $chr = strToUpper(subStr($user["acc_surName"], 0, 1));
                        if(ord($chr)==197) {$chr = strToUpper(subStr($user["acc_surName"], 0, 2));}
                        $found = false;
                        foreach($aInitials as $init) {if($init == $chr) {$found = !$found; break;}}
                        if(!$found) {$aInitials[] = $chr;}
                }
                $_SESSION["INITIALS"] = $aInitials; 
        }
        
        $oUsers = $db->get("USERS", "sysUserAccounts", null, $where." ORDER BY acc_surName", true, __FILE__, __LINE__, false);
        if(count($oUsers) == 0) {
                $oUsers = $db->get("USERS", "sysUserAccounts", null, " 1 ORDER BY acc_surName", true, __FILE__, __LINE__, false);
        }

?>

<script>
function setLoginPass()  {
        $("#acc_pass").val(md5($("#acc_pass").val()));
} 
</script>

<form id="formAcc" method="post">
<?
        if($_POST["setRight_formAcc"] > 0) {
        $oUserSetRight = $sys->arrayIn($_SESSION["USER_ALL_ACCOUNT"], '$acc_id=="'.$_POST["setRight_formAcc"].'"', true);
?>
        <div class="container mb-5">
                <div id="pass_desk" class="row">
                        <div id="form_head" class="col-sm text-center text-light">
                                Nastavte prístupové práva ku menu
                        </div>
                </div>
<?
                $indexMenu   = 1; $reset = true;
                $oRightParents  = $db->get("PARENTRIGHT", "generalMenu", null, "men_idParent=0 && men_delete=0 ORDER BY men_index", $reset, __FILE__, __LINE__);
                foreach($oRightParents as $aParent) {
                        //men_rights=1 && 
                        $oRightChild  = $db->get("CHILDRIGHT", "generalMenu", null, "men_idParent='".$aParent["men_id"]."' && men_delete=0 ORDER BY men_index", $reset, __FILE__, __LINE__, false);
                        $subIndex = 1;
                        if(count($oRightChild)>0) {
?>
                        <div id="pass_desk" class="row">
                                <div id="form_parentHead" class="col-sm text-center text-light">
                                        <?=$aParent["men_itemName"]?>
                                </div>
                        </div>
<?
                        }
?>
                        <div class="row  ">
<?
                        foreach($oRightChild as $aChild) { 

?>
                                <div class="col-sm-4 rightDesk ">
                                        <div class="text-right mt-2" style="float:left;width:60%;">
                                                <?=$aChild["men_itemName"]?>:&nbsp;&nbsp;
                                        </div>
                                        <div class="mt-2 " style="float:left;width:38%;">
<?
                                                $checked = '';
                                                $oChecked  = $db->get("CHECKED", "menuRightsAccess", null, "rig_idUser='".$_POST["setRight_formAcc"]."' && 	rig_idMenu='".$aChild["men_id"]."'", true, __FILE__, __LINE__);
                                                if(count($oChecked)>0) {$checked = 'checked="checked"';}
?>
                                                <input class="isCheck" id="right_<?=$aChild["men_id"]?>" name="right_<?=$aChild["men_id"]?>" data-toggle="tooltip" <?=$checked?> title="Má, alebo nemá mať držiteľ účtu prístup ku tejto položke menu ?" type="checkbox" style="margin-right:150px;" />
                                        </div>
                                </div>
<?
                                ++$subIndex;
                                if($subIndex == 4) {
?>
                                        </div>
                                        <div class="row  ">
<?
                                        $subIndex = 1;
                                }
                                
                        }
                        if($subIndex < 4) {
?>
                        </div>
<?                
                        }              
                }

?>
                <div class="d-flex justify-content-center buttonDesk">
                        <span id="saveRights" >
                        <input type="button" data-placement="bottom" title="Možnosť zapísať aktuálne nastavenie prístupových práv" data-toggle="tooltip"  id="right_formAcc" onclick="hiddenSet('saveRight_formAcc', '<?=$_POST["setRight_formAcc"]?>', true)" class="btn btn-primary " value="Zapísať prístupové práva"  />
                        </span>
                        
                        <span id="saveRightsDis" data-toggle="modal" data-target="#demoversion">
                        <input type="button" data-placement="bottom" title="Možnosť zapísať aktuálne nastavenie prístupových práv" data-toggle="tooltip"   id="right_formAccX" class="btn btn-primary " value="Zapísať prístupové práva"  />
                        </span>

                        
                        <input type="hidden" id="saveRight_formAcc" name="saveRight_formAcc" value="" />
                </div>
                <div id="attention" class ="container d-flex justify-content-center" style="color:#cc0000;font-size:14px;font-weight:bold;"></div>
        </div>
        <script>
        $(".isCheck").click(function() {
                if($("#supervisor").val()==1) {
                        $("#attention").html("Užívateľ je supervízor !");
                } 
        })
        </script>
        <input type="hidden" id="supervisor" name="supervisor" value="<?=$oUserSetRight["acc_supervisor"]?>">
<?      
        //d($_SESSION["SYS_ACCOUNT"]["acc_supervisor"]);  
        }
        
        if($_POST["setType_formAcc"]>0 || $_POST["setType_formAcc"]==(-1)) {
?>
        <div class="container ">
                <div id="pass_desk" class="row">
                        <div id="form_head" class="col-sm text-center text-light">
<?
                        if($oEdit["acc_id"] > 0) {
                                echo("Editovať dáta prístupového účtu:");
                        } else {
                                echo("Vytvoriť nový prístupový účet:");
                        }
?>
                                
                        </div>
                </div>

                <div class="row ">
                        <div id="acc_name_title" class="col-sm-2 pt-2 text-left form_note">Krstné meno:</div>
                        <div class="col-sm-4 pt-2 form_input_desk ">
                                <input type="text" alt="C~2" class="form_text" placeholder="Zadajte krstné meno" id="acc_name" name="acc_name" value="<?=$oEdit["acc_name"]?>" autocomplete="off" autocomplete=new-password" />
                                
                                <div id="acc_name_err" class="form_error"></div>
                        </div>
                        <div id="acc_surName_title" class="col-sm-2 pt-2 text-left form_note">Priezvisko:</div>
                        <div class="col-sm-4 pt-2 form_input_desk ">
                                <input type="text" alt="C~2" class="form_text" placeholder="Zadajte priezvisko" id="acc_surName" name="acc_surName" value="<?=$oEdit["acc_surName"]?>" autocomplete="off" autocomplete=new-password" />
                                <div id="acc_surName_err" class="form_error"></div>                 
                        </div>
                </div>
                <div class="row ">
                        <div id="acc_email_title" class="col-sm-2 pt-2 text-left form_note">E-mail:</div>
                        <div class="col-sm-4 pt-2 form_input_desk ">
                                <input type="text" alt="E~2" class=" form_text" placeholder="Zadajte e-mailovú adresu" id="acc_email" name="acc_email" value="<?=$oEdit["acc_email"]?>" autocomplete="off" autocomplete=new-password" />
                                <div id="acc_email_err" class="form_error"></div>
                        </div>
                        <div id="acc_mobil_title" class="col-sm-2 pt-2 text-left form_note">Telefón:</div>
                        <div class="col-sm-4 pt-2 form_input_desk ">
                                <input type="text" alt="T~0" class="form_text" placeholder="Zadajte telefónne číslo" id="acc_mobil" name="acc_mobil" value="<?=$oEdit["acc_mobil"]?>" autocomplete="off" />
                                <!--autocomplete=new-password" autocomplete="off"-->
                                <div id="acc_mobil_err" class="form_error"></div>
                        </div>
                </div>
                <div id="pass_desk" class="row">
                        <div id="acc_login_title" class="col-sm-2 pt-2 text-left form_note">Login:</div>
                        <div id="" class="col-sm-4 pt-2 form_input_desk ">
                                <input type="text" alt="CN~4" class="form_text" placeholder="Zadajte prihlasovacie meno" id="acc_login" name="acc_login"  value="<?=$oEdit["acc_login"]?>" autocomplete="off" />
                                <div id="acc_login_err" class="form_error"></div>
                        </div>
                        <div id="" class="col-sm-6 pt-2 form_checks ">
<?
                                $checked = ''; if($oEdit["acc_chatAccess"]==1) {$checked = ' checked="checked" ';}
?>
                                <div class="row">
                                <div class="col-sm-6" >
                                        <div class="" style="float:left;width:170px;">
                                        Prístup na chat:
                                        </div>
                                        <div class="" style="float:left;width:50px;">
                                        <input data-toggle="tooltip" <?=$checked?> title="Má, alebo nemá mať držiteľ účtu prístup na chat ?" type="checkbox" style="margin-left:5px;" id="acc_chatAccess" name="acc_chatAccess" />
                                        </div>
                                </div>

<?
                                if($_POST["setType_formAcc"]==(-1)) {

                                $checked = ''; if($oEdit["acc_free"]==1) {$checked = ' checked="checked" ';}
?>
                                <div class="col-sm-6" >
                                        <div class="" style="float:left;width:170px;">
                                        Voľný vstup:
                                        </div>
                                        <div class="" style="float:left;width:50px;">
                                        <input type="checkbox" <?=$checked?> style="margin-left:5px;" data-toggle="tooltip"  title="Voľný vstup pre držiteľa účtu ?" type="checkbox" id="acc_free" name="acc_free" />
                                        </div>
                                </div>
<?
                                } else {
                                $checked = ''; if($oEdit["acc_block"]==1) {$checked = ' checked="checked" ';}
?>
                                <div class="col-sm-6" >
                                        <div class="" style="float:left;width:170px;">
                                        Blokovať:
                                        </div>
                                        <div class="" style="float:left;width:50px;">
                                        <input type="checkbox" <?=$checked?> style="margin-left:5px;" data-toggle="tooltip"  title="Blokovať prístup tommuto držiteľovi účtu ?" type="checkbox" id="acc_block" name="acc_block" />
                                        </div>
                                </div>
<?                                
                                }
?>
                                </div>
                        </div>
                </div>
<?
                if($_POST["setType_formAcc"]==(-1)) {
?>
                <div class="row ">
                        <div id="acc_pass_title" class="col-sm-2 pt-2 text-left form_note">Heslo:</div>
                        <div class="col-sm-4 pt-2 form_input_desk ">
                                <input type="password" alt="X~6~~~acc_repeat~Nové heslá sa musia zhodovať!" class=" form_text" placeholder="Zadajte nové heslo" id="acc_pass" name="acc_pass" autocomplete=new-password" />
                                <div id="acc_pass_err" class="" class="form_error"><span style="font-size:12px;">Návrh hesla: <span style="color:#008C14;font-weight:bold;"><?=randomString(8)?></span></span></div>
                        </div>
                        <div id="acc_repeat_title" class="col-sm-2 pt-2 text-left form_note">Zopakovať:</div>
                        <div class="col-sm-4 pt-2 form_input_desk ">
                                <input type="password" alt="X~6~~~acc_pass~Nové heslá sa musia zhodovať!" class="form_text" placeholder="Zopakujte nové heslo" id="acc_repeat" name="acc_repeat" autocomplete=new-password" />
                                <div id="acc_repeat_err" class="form_error"></div>
                        </div>
                </div>
<?
                }
?>

<?
                if($_POST["setType_formAcc"]>(-1)) {
?>
                <div class="row ">
                        <div id="acc_payment_title" class="col-sm-2 pt-2 text-left form_note">Kredit ukončenie:</div>
                        <div class="col-sm-4 pt-2 form_input_desk ">
<?
                                $aDate = dateTimeToArray($oEdit["acc_payment"]);
                                $payment = $aDate["dd"]."-".$aDate["mm"]."-".$aDate["rr"];
?>
                                <input type="text" alt="D~10" class=" form_text" placeholder="Deň ukončenia kreditu" id="acc_payment" name="acc_payment" value="<?=$payment?>" autocomplete="off" />
                                <div id="acc_payment_err" class="" class="form_error"></div>
                        </div>
                        <div id="" class="col-sm-6 pt-2 form_checks ">
<?
                                $checked = ''; if($oEdit["acc_supervisor"]==1) {$checked = ' checked="checked" ';}
?>
                                <div class="row">
                                <div class="col-sm-6" >
                                        <div class="" style="float:left;width:170px;">
                                        Supervízor:
                                        </div>
                                        <div class="" style="float:left;width:50px;">
                                        <input type="checkbox" <?=$checked?> style="margin-left:5px;" data-toggle="tooltip"  title="Sprístupniť užívateľovi všetky položky menu okrem administrácie ?" type="checkbox" id="acc_supervisor" name="acc_supervisor" />
                                        </div>
                                </div>
<?
                                $checked = ''; if($oEdit["acc_chatAccess"]==1) {$checked = ' checked="checked" ';}
?>
                                <div class="col-sm-6" >
                                        <div class="" style="float:left;width:170px;">
                                        Prístup na chat:
                                        </div>
                                        <div class="" style="float:left;width:50px;">
                                        <input type="checkbox" <?=$checked?> style="margin-left:5px;" data-toggle="tooltip"  title="Blokovať prístup tommuto držiteľovi účtu ?" type="checkbox" id="acc_chatAccess" name="acc_chatAccess" />
                                        </div>

                                </div>
                                </div>
                        </div>
                </div>
                
                <div class="row ">
                        <div id="" class="col-sm-2 pt-2 text-left form_note"></div>
                        <div class="col-sm-4 pt-2 form_input_desk ">
                        </div>
                        <div id="" class="col-sm-6 pt-2 form_checks ">
<?
                                $checked = ''; if($oEdit["acc_chatAdmin"]==1) {$checked = ' checked="checked" ';}
?>
                                <div class="row">
                                <div class="col-sm-6" >
                                        <div class="" style="float:left;width:170px;">
                                        Chat admin:
                                        </div>
                                        <div class="" style="float:left;width:50px;">
                                        <input type="checkbox" <?=$checked?> style="margin-left:5px;" data-toggle="tooltip"  title="Má byť užívateľ administrátorom chatu ?" type="checkbox" id="acc_chatAdmin" name="acc_chatAdmin" />
                                        </div>
                                </div>
<?
                                $checked = ''; if($oEdit["acc_chatMail"]==1) {$checked = ' checked="checked" ';}
?>
                                <div class="col-sm-6" >
                                        <div class="" style="float:left;width:170px;">
                                        Mail z chatu:
                                        </div>
                                        <div class="" style="float:left;width:50px;">
                                        <input type="checkbox" <?=$checked?> style="margin-left:5px;" data-toggle="tooltip"  title="Ak nie je užívateľ prihlásený posielať správu aj na mail  ?" type="checkbox" id="acc_chatMail" name="acc_chatMail" />
                                        </div>

                                </div>
                                </div>
                        </div>
                </div>
                
                <div class="row ">
                        <div id="" class="col-sm-2 pt-2 text-left form_note"></div>
                        <div class="col-sm-4 pt-2 form_input_desk ">
                        </div>
                        <div id="" class="col-sm-6 pt-2 form_checks ">
<?
                                $checked = ''; if($oEdit["acc_admin"]==1) {$checked = ' checked="checked" ';}
?>
                                <div class="row">
                                <div class="col-sm-6" >
                                        <div class="" style="float:left;width:170px;">
                                        Administrátor:
                                        </div>
                                        <div class="" style="float:left;width:50px;">
                                        <input type="checkbox" <?=$checked?> style="margin-left:5px;" data-toggle="tooltip"  title="Má byť užívateľ administrátorom projektu ?" type="checkbox" id="acc_admin" name="acc_admin" />
                                        </div>
                                </div>
<?
                                $checked = ''; if($oEdit["acc_demo"]==1) {$checked = ' checked="checked" ';}
?>
                                <div class="col-sm-6" >
                                        <div class="" style="float:left;width:170px;">
                                        Demo verzia:
                                        </div>
                                        <div class="" style="float:left;width:50px;">
                                        <input type="checkbox" <?=$checked?> style="margin-left:5px;" data-toggle="tooltip"  title="Užívateľovi spúšťať len demo veziu ?" type="checkbox" id="acc_demo" name="acc_demo" />
                                        </div>

                                </div>
                                </div>
                        </div>
                </div>


<?
                }
?>
                <div class="mt-5 " style="width:100%;">  
                        <div class="progress ">
                                <div id="progress_formAcc" class="progress-bar text-white text-bold text-center progressNote" style="width:0%;font-size:12px;">
                                        0%        
                                </div>
                        </div>
                </div>
<?
                //if($_POST["setType_formAcc"]==(-1)) {
?>
                <div id="approvalDesk_formAcc" style="display:none;" class="text-center pt-1 " style="width:100% ">
                        Údaje ku účtu som riadne skontroloval:
                        <input type="checkbox" class="form-check-input" style="margin-left:25px;display:pointer;" id="approval_formAcc" />
                </div>    
<?
                //}
?>
                <div class="container text-center buttonDesk pt-3" >
<?
                if($_POST["setType_formAcc"]==(-1)) {
?>
                        <span id="newUserAccount" >
                        <input type="button" data-placement="bottom" title="Ak sú všetky údaje korektné, možete vytvoriť prístupový účet" data-toggle="tooltip"   alt="captcha" id="save_formAcc" disabled onclick="clickButton(this, 'setLoginPass()|hiddenSet(o,1)')" class="btn btn-primary" value="Založiť nový účet" />
                        </span>
                        <span id="newUserAccountDis" data-toggle="modal" data-target="#demoversion">
                        <input type="button" data-placement="bottom" title="Ak sú všetky údaje korektné, možete vytvoriť prístupový účet" data-toggle="tooltip"  id="save_formAccX" class="btn btn-primary" value="Založiť nový účet" />
                        </span>

<?
                } elseif($_POST["setType_formAcc"]>0) {
?>
                        <span id="rewriteAccount" >
                        <input type="button" data-placement="bottom" title="Ak sú všetky údaje korektné, možete aktualizovať prístupový účet" data-toggle="tooltip"  alt="captcha" id="update_formAcc" disabled onclick="viewCaptchaModal('change_formAcc(o)',this)" class="btn btn-primary" value="Zapísať zmeny v účte" />
                        <script>function change_formAcc(o) {$("#change_formAcc").val(2);}</script>
                        </span>
                        <span id="rewriteAccountDis" data-toggle="modal" data-target="#demoversion">
                        <input type="button" data-placement="bottom" title="Ak sú všetky údaje korektné, možete aktualizovať prístupový účet" data-toggle="tooltip" id="update_formAccX" class="btn btn-primary" value="Zapísať zmeny v účte" />
                        </span>

                        
<?
                }
?>                          
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
                <input type="hidden" id="inspect_formAcc" name="inspect_formAcc" value="acc~#cc0000~green~~#cc0000~12~arial~bold~i" />
                <input type="hidden" id="change_formAcc" name="change_formAcc" value="" />
                <input type="hidden" id="acc_id" name="acc_id" value="<?=$oEdit["acc_id"]?>" />

        </div>
<?
        } else {

                if(strLen(Trim($_POST["setRight_formAcc"]))==0 || $_POST["setRight_formAcc"]==0) {
?>
                <div id="newUserAccount" class="container ">
                        <input type="button" data-placement="bottom" title="Vytvoriť nový prístupový účet" data-toggle="tooltip"  id="new_formAcc" onclick="hiddenSet('setType_formAcc', (-1), true)" class="btn btn-primary " value="Nový prístupový účet"  />
                        
                </div>        

<?
                }
        }
?>
        
        <input type="hidden" id="setRight_formAcc" name="setRight_formAcc" value=""  />
        <input type="hidden" id="setType_formAcc" name="setType_formAcc" value="<?=$_POST["typeForm"]?>"  />
        <input type="hidden" id="setDel_formAcc" name="setDel_formAcc" value=""  />
        <input type="hidden" id="setPath_formAcc" name="setPath_formAcc" value=""  />
        


<div class="container-fluid mt-3">
        <div class="container">
<?
                $aInitials = $_SESSION["INITIALS"]; 
?>
                <div id="initials_nice" class="clearfix border" style="background-color:#fff;border-radius:5px;width:100px;margin-left:auto;margin-right:auto;">
                <select class="nice-select " id="initials"  name="initials" onchange="hiddenSet('initials_formAcc', 1, true)">
                        <option value=""></option>
<?
                        foreach($aInitials as $init) {
                        $selected = "";
                        if($init == $_POST["initials"]) {$selected = ' selected="selected" ';}
?>
                        <option <?=$selected?> value="<?=$init?>"><?=$init?></option>
<?
                        }
?>
                        
                </select>
                </div>
                <script>$(document).ready(function() {$('#initials').niceSelect();});</script> 
                <input type="hidden" id="initials_formAcc" name="initials_formAcc" value="" />
        </div>
        
        <div class="container">
                <div class="row">
<?
                        $in = 1;
                        foreach($_SESSION["USERS"] as $aRecord) {
                                $addClass = "";
                                if($in%2 == 0)  {$addClass = "cardPersonEven";}
                                card($aRecord, $addClass);
                                ++$in;
                                
                        }
                        emptyCard("");
                        emptyCard("cardPersonEven");
?>                        
                </div>
        </div>
</div>

</form>

<script>

eval('var currentPath = "<?=$_SESSION["URL_FULL"]?>";');
if(currentPath.indexOf("?")>(-1)) {
        var newPath = currentPath.substring(0, currentPath.indexOf("?"));
        processChangeURL( '', newPath);
}
</script>

<?
function emptyCard() {  ?><div class="oneCard col-sm-3 ml-0 mt-2 pl-0" ></div><? }

function card($aRecord, $addClass) {

        include($_SESSION["PROJECT_INFO"] );
?>

        <div class="oneCard col-sm-3 mt-3 ml-0 pl-0 cardPerson <?=$addClass?> " style="">  
                <div style="width:100%;height:23px;" class="">
                        
<?
                        if($aRecord["acc_id"] > 2) {
?>
                        <div id="rightEdit<?=$aRecord["acc_id"]?>_formAcc" onclick="hiddenSet('setRight_formAcc', <?=$aRecord["acc_id"]?>, true)" data-toggle="tooltip"  title="Možnosť nastaviť prístupové práva tohto užívateľa ku menu" class="rightButton  cursor-pointer"><i class="far fa-address-card"></i></div>
                        <div id="clickEdit<?=$aRecord["acc_id"]?>_formAcc" onclick="hiddenSet('setType_formAcc', <?=$aRecord["acc_id"]?>, true)" data-toggle="tooltip"  title="Možnosť vykonať zmeny v tomto prístupovom účte" class="editButton  cursor-pointer"><i class="fas fa-user-edit"></i></div>
                        
                        
                        <span class="delAcount">
                        <div data-toggle="modal" data-target="#delModal"  id="clickDel<?=$aRecord["acc_id"]?>_formAcc" onclick="hiddenSet('setDel_formAcc', <?=$aRecord["acc_id"]?>)" class="delButton cursor-pointer">
                                <i data-placement="bottom" title="Vymazať aktuálny prístupový účet" data-toggle="tooltip" class="fas fa-user-times"></i>
                        </div>
                        </span>
                        <span class="delAcountDis">
                        <div data-toggle="modal" data-target="#demoversion"  class="delButton cursor-pointer">
                                <i data-placement="bottom" title="Vymazať aktuálny prístupový účet" data-toggle="tooltip" class="fas fa-user-times"></i>
                        </div>
                        </span>
                        
                        
<?
                        } else {
?>
                        <div id="rightEdit<?=$aRecord["acc_id"]?>_formAcc" onclick="" data-toggle="tooltip"  title="Nastavenie prístupových práv vlastníkov systému alebo licencie k užívaniu systému nie je možné ani mazať ani editovať" class="rightButton rightOFF"><i class="far fa-address-card"></i></div>
                        <div id="clickEdit<?=$aRecord["acc_id"]?>_formAcc" onclick="" data-toggle="tooltip"  title="Prístupové účty vlastníkov systému alebo licencie k užívaniu systému nie je možné ani mazať ani editovať - len priamo cez databázu !" class="editButton editOFF"><i class="fas fa-user-edit"></i></div>
                        <div id="clickDel<?=$aRecord["acc_id"]?>_formAcc" onclick="" class="delButton delOFF" data-toggle="tooltip"  title="Prístupové účty vlastníkov systému alebo licencie k užívaniu systému nie je možné ani mazať ani editovať - len priamo cez databázu !"><i class="fas fa-user-times"></i></div>
<?
                        }
                        
                        if($aRecord["acc_free"] == 1) {
?>                                                                                                                                                                                                 
                        <div id="clickPath<?=$aRecord["acc_id"]?>_formAcc" onclick="hiddenSet('setPath_formAcc', <?=$aRecord["acc_id"]?>, true)"  class="pathButton cursor-pointer" data-toggle="tooltip"  title="Zobraziť cestu pre voľný vstup"><i class="fas fa-equals"></i></div>
<?                                                                                                                                                                                                                                                                           
                        }

?>                        
                        
                </div>
                
                <div class="" style="width:100%;"> 

                <div class="ml-0 photoDesk " style="float:left;width:20%;">
<?
                $photoName = $photoPersons."/person0.png";
                if(is_file($photoPersons."/person".$aRecord["acc_id"].".png")) {
                        $photoName = $photoPersons."/person".$aRecord["acc_id"].".png";
                }       
                $cursor = "";
                if($aRecord["acc_id"] > $adminID ) {
                        $func = "hiddenSet('setType_formAcc', '".$aRecord["acc_id"]."', true)";
                        $cursor = "cursor:pointer;";
                }  
?>
                        <img src="/<?=$photoName?>" class="rounded-circle  photo" style="<?=$cursor?>" onclick="<?=$func?>" />
                </div>
                
                <div class="ml-0 personIdent " style="width:75%;float:left;">
                        <div class="persNote"></div>
                        <div class="persNote">Priezvisko:&nbsp;</div>
                        <div class="persData"><?=$aRecord["acc_surName"]?></div>
                        <div class="persNote">Meno:&nbsp;</div>
                        <div class="persData"><?=$aRecord["acc_name"]?></div>
                        <div class="persNote persDataPause">Login:&nbsp;</div>
                        <div class="persData persDataPause"><?=$aRecord["acc_login"]?></div>
                        <div class="persNote">Administrátor:&nbsp;</div>
                        <div class="persData">
<?
                        if($aRecord["acc_admin"]) {echo('<span class="warning">Áno</span>');} else {echo("Nie");}
?>
                        </div>
                        <div class="persNote">Len demo:&nbsp;</div>
                        <div class="persData">
<?
                        if($aRecord["acc_demo"]) {echo('<span class="credit">Áno</span>');} else {echo('<span class="warning">Nie</span>');;}
?>
                        </div>
                        <div class="persNote">Supervízia:&nbsp;</div>
                        <div class="persData">
<?
                        if($aRecord["acc_supervisor"]) {echo('<span class="warning">Áno</span>');} else {echo("Nie");}
?>
                        </div>

                        <div class="persNote">Blokovanie:&nbsp;</div>
                        <div class="persData">
<?
                        if($aRecord["acc_block"]) {echo('<span class="warning">Áno</span>');} else {echo("Nie");}
?>
                        </div>
                        <div class="persNote">Kredit do:&nbsp;</div>
                        <div class="persData">
<?
                        $aCreditDate = DateTimeToArray($aRecord["acc_payment"]);
                        $creditDate  = $aCreditDate["dd"].".".$aCreditDate["mm"].".".$aCreditDate["rr"];
                        if(subStr($creditDate,4,1)>0) {
                                if($creditDate<date("d.m.Y")) {$class = "warning";} else {$class = "credit";}
                                echo('<span class="'.$class.'">'.$creditDate.'</span>');
                        } else {
                                echo("==");
                        }
?>
                        </div>
                </div>                
                </div> 
                
                <div class="mt-2 otherPerson">
                         <div class="persNoteFull">E-mail:&nbsp;</div>
                        <div class="persDataFull"><?=$aRecord["acc_email"]?></div>  
                </div>
                <div class="mt-2 otherPerson">
                         <div class="persNoteFull">Telefón:&nbsp;</div>
                        <div class="persDataFull"><?=$aRecord["acc_mobil"]?></div>  
                </div>
        </div>
<?        
}
?>

<script>
        if($(window).width()>=1024 && $(window).width()<1280) {
                $("div.oneCard").removeClass("col-sm-3");
                $("div.oneCard").addClass("col-sm-4");
        
        }

        if($(window).width()>599 && $(window).width()<1024) {
                $("div.oneCard").removeClass("col-sm-3");
                $("div.oneCard").addClass("col-sm-6");
        }
        
        $(document).ready(function() {  
                var demoVersion = '<?=$demoVesion?>';
                if(demoVersion.length>0) {
                        $('.delAcountDis').show();
                        $('.delAcount').hide();
                        $('#newUserAccountDis').show();   
                        $('#newUserAccount').hide();   
                        $('#rewriteAccountDis').show();   
                        $('#rewriteAccount').hide();   
                        $('#saveRightsDis').show();   
                        $('#saveRights').hide();   

                } else {
                        $('.delAcountDis').hide();
                        $('.delAcount').show();     
                        $('#newUserAccountDis').hide();   
                        $('#newUserAccount').show();   
                        $('#rewriteAccountDis').hide();   
                        $('#rewriteAccount').show();   
                        $('#saveRightsDis').hide();   
                        $('#saveRights').show();   

                }
        });
</script>