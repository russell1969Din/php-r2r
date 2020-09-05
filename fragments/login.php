<?
        if(getType($_SESSION["SYS_ACCOUNT"]) != "array") {$_SESSION["SYS_ACCOUNT"] = array();}
        if(strLen(trim($_POST["acc_login"])) && count($_SESSION["SYS_ACCOUNT"])==0) {
                $oAccessUser = $db->get("SYS_ACCOUNTX", "sysUserAccounts", null, 'acc_login="'.$_POST["acc_login"].'" && acc_pass="'.$_POST["acc_pass"].'" && acc_isDelete = 0 && acc_block = 0', true, __FILE__, __LINE__, true);
                $_SESSION["SYS_ACCOUNT"] = $_SESSION["SYS_ACCOUNTX"];
                if(count($oAccessUser)>0) {
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
                        $db->get("GLOBAL_MENU", array("generalMenu", "templates"), null, "tmp_id=men_template && men_delete=0 ORDER BY men_index ASC", true, __FILE__, __LINE__);
                        $_SESSION["VISITORS"] = null;
                        
                        $db->get("USER_ALL_ACCOUNTX", "sysUserAccounts", null, "acc_isDelete=0 && acc_admin=0 && acc_block=0 ORDER BY acc_surName, acc_name ASC", true, __FILE__, __LINE__);
                        $_SESSION["USER_ALL_ACCOUNT"] = $_SESSION["USER_ALL_ACCOUNTX"];
                        
                        $db->get("ALL_TEMPLATESX", "templates", null, null, true, __FILE__, __LINE__);
                        $_SESSION["ALL_TEMPLATES"] = $_SESSION["ALL_TEMPLATESX"];

                        $db->get("GLOBAL_ACCESSRIGHTX", array("menuRightsAccess"), null, "1", true, __FILE__, __LINE__);
                        $_SESSION["GLOBAL_ACCESSRIGHT"] = $_SESSION["GLOBAL_ACCESSRIGHTX"];

                }
        }

        if(getType($_SESSION["SYS_ACCOUNT"]) != "array") {$_SESSION["SYS_ACCOUNT"] = array();}
        if(count($_SESSION["SYS_ACCOUNT"]) == 0) {
?>

<script>
function setLoginPass()  {
        $("#acc_pass").val(md5($("#acc_pass").val()));
} 
</script>
        <form id="passForm" method="post">
                <link rel="stylesheet" type="text/css"  href="/css/setPassword.css" />
                <div class="container">
                        <div class="row mt-5 mb-5">
                                <div class="col-sm-3"></div>
                                <div id="desk_passForm" class="col-sm-6">
                                        <div id="pass_desk" class="row">
                                                <div id="pass_head" class="col-sm text-center text-light">
                                                        Pre vstup sa prihláste:
                                                </div>
                                        </div>
                                        <div id="pass_desk" class="row mt-3">
                                                <div id="acc_login_title"  class="col-sm-4 text-center">
                                                        Login:
                                                </div>
                                                <div class="col-xl-8">
                                                        <input type="text" alt="CN~4" class="form-control text-dark" placeholder="Vaše prihlasovacie meno" id="acc_login" name="acc_login" />
                                                        <div id="acc_login_err" style="height:20px;"></div>
                                                </div>
                                        </div>
                                        <div id="pass_desk" class="row">
                                                <div id="acc_pass_title" class="col-sm-4 text-center">
                                                        Heslo:
                                                </div>
                                                <div class="col-xl-8">
                                                        <!---->
                                                        <input type="password" alt="~6" class="form-control text-dark" placeholder="Heslo k prihláseniu" id="acc_pass" name="acc_pass" />
                                                        <input type="hidden" id="acc_pass_md5" name="acc_pass_md5" value="" />
                                                        <div id="acc_pass_err" style="height:20px;"></div>
                                                </div>
                                        </div>
                                        
                                        <div id="pass_desk" class="row mt-2 mb-5" >
                                                <div id="pass_login" class="col-sm text-center text-light">
                                                        <input type="button" alt="captcha" id="save_passForm" disabled onclick="clickButton(this, 'setLoginPass()')" class="btn btn-primary" value="Prihlásiť sa" />
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
                <input type="hidden" id="inspect_passForm" name="inspect_passForm" value="acc~#cc0000~green~~#cc0000~12~arial~bold~i" />
        </form>

        
<?
        die();
        }
        

        if(strPos($_SERVER["REQUEST_URI"], "odhlasenie")) {
                
                //foreach($_SESSION["SESSIONS_BANK"] as $bank) {$_SESSION[$bank] = null;}
                //$_SESSION["SESSIONS_BANK"] = array();
                $db->flushSessionsBank();
                $_SESSION["SYSTEM_PHP_INTERFACE"] = $_SESSION[$_SESSION["SYSTEM_PHP_INTERFACE"]] = 
                $_SESSION["ITEM_RIGHT"] =
                $_SESSION["GLOBAL_MENU"] = 
                $_SESSION["ACCESSRIGHT_MENU"] = 
                $_SESSION["VISIT_RECORD"] = 
                $_SESSION["GENERAL_PARENT"] =  
                $_SESSION["BUFFER_PARENT"] =
                $_SESSION["BUFFER_VISITORS"] =
                $_SESSION["SYS_ACCOUNT"] = null;
?>
                <script>
                        window.location.href = "http://<?=$_SERVER['SERVER_NAME']?>";
                </script>
<?
        }
        
?>

