<?
        if($_POST["change_newPassForm"]>0) {
                
                if(strLen(Trim($_POST["freeUser"]))>16) {
                        $aFields = array("acc_pass", "acc_free", "acc_freeEntry");
                        $aValues = array($_POST["acc_new"], "", "");
                        $db->update("sysUserAccounts", $aFields, $aValues, "acc_id = '".$_POST["freeUserId"]."'", __FILE__, __LINE__, true);
                        $oAccessUser = $db->get("SYS_ACCOUNT", "sysUserAccounts", null, "acc_id= '".$_POST["freeUserId"]."'", true, __FILE__, __LINE__, true, true);
                        
                        $aValues    = array();
                        $aValues[]  = array(    "vis_dateTime"=>date("Y-m-d H:i:s"), 
                                                "vis_ip"=>$_SERVER['REMOTE_ADDR'], 
                                                "vis_addr"=>gethostbyaddr ($_SERVER['REMOTE_ADDR']), 
                                                "vis_idSysUser"=>$oAccessUser["acc_id"]);
                        $aNewId = $db->insert("sysVisitorsIn", $aValues, false, __FILE__, __LINE__, true);
                        if($oAccessUser["acc_admin"]!=1) {
                                $_SESSION["VISIT_RECORD"] = $aNewId[0];
                                $db->get("ACCESSRIGHT_MENUX", array("menuRightsAccess", "generalMenu"), null, "men_id=rig_idMenu && rig_idUser='".$oAccessUser["acc_id"]."' && men_block=0 && men_delete=0", true, __FILE__, __LINE__);
                                $_SESSION["ACCESSRIGHT_MENU"] = $_SESSION["ACCESSRIGHT_MENUX"];
                        }
                        $db->get("GLOBAL_MENU", "generalMenu", null, "men_delete=0 ORDER BY men_index ASC", true, __FILE__, __LINE__);
                        $_SESSION["VISITORS"] = null;
                        
                        $db->get("USER_ALL_ACCOUNTX", "sysUserAccounts", null, "acc_isDelete=0 && acc_admin=0 && acc_block=0 ORDER BY acc_surName, acc_name ASC", true, __FILE__, __LINE__);
                        $_SESSION["USER_ALL_ACCOUNT"] = $_SESSION["USER_ALL_ACCOUNTX"];
                        
                        $db->get("ALL_TEMPLATESX", "templates", null, null, true, __FILE__, __LINE__);
                        $_SESSION["ALL_TEMPLATES"] = $_SESSION["ALL_TEMPLATESX"];

                        $db->get("GLOBAL_ACCESSRIGHTX", array("menuRightsAccess"), null, "1", true, __FILE__, __LINE__);
                        $_SESSION["GLOBAL_ACCESSRIGHT"] = $_SESSION["GLOBAL_ACCESSRIGHTX"];                
                } else {
                        $db->update("sysUserAccounts", "acc_pass", $_POST["acc_new"], "acc_id = '".$_SESSION["SYS_ACCOUNT"]["acc_id"]."'", __FILE__, __LINE__);
                        $oAccessUser = $db->get("SYS_ACCOUNT", "sysUserAccounts", null, "acc_id= '".$_SESSION["SYS_ACCOUNT"]["acc_id"]."'", true, __FILE__, __LINE__, true);
                }
                
                
                bsAlert("Heslo bolo úspešne zmenené !", 1);
        }
        
?>

<script>
function setChangePass()  {
        $("#acc_original").val(md5($("#acc_original").val()));
        $("#acc_new").val(md5($("#acc_new").val()));
        $("#acc_repeat").val(md5($("#acc_repeat").val()));
        //document.getElementById("acc_original").value = md5(document.getElementById("acc_original").value);
        //document.getElementById("acc_new").value = md5(document.getElementById("acc_new").value);
        //document.getElementById("acc_repeat").value = md5(document.getElementById("acc_repeat").value);
} 
</script>

<form id="newPassForm" method="post">
        <link rel="stylesheet" type="text/css"  href="/css/setPassword.css" />
        <div class="container">
                <div class="row mt-1 mb-5">
                        <div class="col-sm-3"></div>
                                <div id="desk_newPassForm" class="col-sm-6">
                                        <div id="pass_desk" class="row">
                                                <div id="pass_head" class="col-sm text-center text-light">
<?
                                                        
                                                        if(strLen(Trim($oFreeUser["acc_login"]))>0) {
                                                                $loginName = "ku login: <b>".$oFreeUser["acc_login"]."</b>";
                                                        } elseif(strLen(Trim($_POST["freeUserLogin"]))>0) {
                                                                $loginName = "ku login: <b>".$_POST["freeUserLogin"]."</b>";
                                                        } else {
                                                                $loginName = "ku login: <b>".$_SESSION["SYS_ACCOUNT"]["acc_login"]."</b>";
                                                        }
?>
                                                        Zmena aktuálneho hesla <?=$loginName?>
                                                </div>
                                        </div>
                                        <div id="pass_desk" class="row mt-3">
                                                <div id="acc_original_title"  class="col-sm-4 text-center" >
                                                        Pôvodné&nbsp;heslo:
                                                </div>
                                                <div class="col-xl-8">
<?
                                                if(strLen(Trim($oFreeUser["acc_pass"]))>0) {
?>
                                                        <input type="password" alt="C~6" class="form-control text-dark" placeholder="Zadajte aktuálne heslo" value="xxxxxx" id="acc_original" name="acc_original" />
                                                        <input type="hidden" id="acc_original_md5" name="acc_original_md5" value="" />
                                                        <input type="hidden" id="acc_buffer" name="acc_buffer" value="<?=$oFreeUser["acc_pass"]?>" />
                                                        <input type="hidden" id="freeUser" name="freeUser" value="<?=$oFreeUser["acc_pass"]?>" />
                                                        <input type="hidden" id="freeUserId" name="freeUserId" value="<?=$oFreeUser["acc_id"]?>" />
                                                        <input type="hidden" id="freeUserLogin" name="freeUserLogin" value="<?=$oFreeUser["acc_login"]?>" />
                                                        
<?
                                                } else {
?>
                                                        <input type="password" alt="MD5~6~~~acc_buffer~Chybné pôvodné heslo !" class="form-control text-dark" placeholder="Zadajte aktuálne heslo" id="acc_original" name="acc_original" />
                                                        <input type="hidden" id="acc_original_md5" name="acc_original_md5" value="" />
                                                        <input type="hidden" id="acc_buffer" name="acc_buffer" value="<?=$_SESSION["SYS_ACCOUNT"]["acc_pass"]?>" />
<?
                                                }
?>
                                                        <div id="acc_original_err" style="height:20px;"></div>
                                                </div>
                                        </div>
                                        <div id="pass_desk" class="row">
                                                <div id="acc_new_title" class="col-sm-4 text-center">
                                                        Nové&nbsp;heslo:
                                                </div>
                                                <div class="col-xl-8">
                                                        <!---->
                                                        <input type="password" alt="X~6~~~acc_repeat~Nové heslá sa musia zhodovať!" class="form-control text-dark" placeholder="Zadajte nové heslo" id="acc_new" name="acc_new" />
                                                        <input type="hidden" id="acc_new_md5" name="acc_new_md5" value="" />
                                                        <div id="acc_new_err" style="line-height:12px;height:20px;"><span style="font-size:12px;">Návrh hesla: <span style="color:#008C14;font-weight:bold;"><?=randomString(8)?></span></span></div>
                                                </div>
                                        </div>
                                        <div id="pass_desk" class="row">
                                                <div id="acc_repeat_title" class="col-sm-4 text-center">
                                                        Nové&nbsp;heslo:
                                                </div>
                                                <div class="col-xl-8">
                                                        <!---->
                                                        <input type="password" alt="X~6~~~acc_new~Nové heslá sa musia zhodovať!" class="form-control text-dark" placeholder="Zopakujte nové heslo" id="acc_repeat" name="acc_repeat" />
                                                        <input type="hidden" id="acc_repeat_md5" name="acc_repeat_md5" value="" />
                                                        <div id="acc_repeat_err" style="height:20px;"></div>
                                                </div>
                                        </div>
                                        
                                        <div class="mt-5 " style="width:100%;">  
                                                <div class="progress ">
                                                        <div id="progress_newPassForm" class="progress-bar text-white text-bold text-center progressNote" style="width:0%;font-size:12px;">
                                                                0%        
                                                        </div>
                                                </div>
                                        </div>
                                        
                                        <div id="approvalDesk_newPassForm" style="display:none;" class="text-center pt-1 " style="width:100% ">
                                                Nové heslo mám aj inde zapísané:
                                                <input type="checkbox" class="form-check-input" style="margin-left:25px;cursor:pointer;" id="approval_newPassForm" />
                                        </div>    

                                        
                                        <div id="pass_desk" class="row mt-2 mb-5" >
                                                <div id="acc_login" class="col-sm text-center text-light">
                                                        <input type="button" alt="xaptcha" id="save_newPassForm" disabled onclick="clickButton(this, 'setChangePass()')" class="btn btn-primary" value="Zmeniť heslo" />
                                                </div>
                                        </div>

                                </div>
                                <div class="col-sm-3"></div>
                        </div>                
                
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
                <input type="hidden" id="inspect_newPassForm" name="inspect_newPassForm" value="acc~#cc0000~green~~#cc0000~12~arial~bold~i" />
                <input type="hidden" id="change_newPassForm" name="change_newPassForm" value="" />
        </form>
