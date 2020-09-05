<?session_start();?>
<!DOCTYPE html>
<html>

<head>
<?
        if(!is_dir("lc-scripts")) {mkDir("lc-scripts");}        
        
        if(is_file("service.php")) {include("service.php");}
        if(is_file("library.php")) {include("library.php");}
        
        $_SESSION["SYSTEM_ROOT"]    = getSystemRoot();
        $_SESSION["PROJECT_INFO"]   = $_SESSION["SYSTEM_ROOT"]."/setsParam.php";
        $_SESSION["DOMAIN_PATH"]    = "http://".$_SERVER['HTTP_HOST'];
        $_SESSION["URL_FULL"]       = "http://".$_SERVER['HTTP_HOST'].$_SERVER["REQUEST_URI"];
        
        if(is_file($_SESSION["PROJECT_INFO"])) {include($_SESSION["PROJECT_INFO"]);}
        
        $_SESSION["DOMAIN_PATH_VIDEO"] = "http://".$_SERVER['HTTP_HOST']."/".$videoFolder;
        
        $_SESSION["SERVER_PATH_VIDEO"] = $_SESSION["SYSTEM_ROOT"]."/".$videoFolder;
        
        if(strLen(Trim($_POST["screenHeight"])) != 0) {
                $_SESSION["SCREEN_HEIGHT"] = $_POST["screenHeight"];
        }   
        
        if(!is_dir($photoPersons)) {mkDir($photoPersons);}
?>

        <!--- meta tag on site->

        <!-- meta facebook -->
        <!--
        <meta property="og:locale" content="sk_SK" />
        <meta property="og:type" content="website" />
        <meta property="og:title" content="FERMATA Art Rock" />
        <meta property="og:description" content="..." />
        <meta property="og:url" content="https://fermata.sk" />
        <meta property="og:site_name" content="fermata" />
        <meta property="og:image" content="https://fermata.abnet.sk/img/logo.jpg" />
        -->
        
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <link rel="shortcut icon" type="image/x-icon" href=""/>

        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Demo project R2R</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous">

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
        
        <!--
        < script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
        < script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
        -->
        
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
        
        <script type="text/javascript">
                $(document).ready(function(){$('#body').fadeIn(2000);});
        </script>
       
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
        

        <link rel="stylesheet" type="text/css"  href="<?=$_SESSION["DOMAIN_PATH"]?>/css/responsiveObjects.css" />
        
        <link rel="stylesheet" type="text/css"  href="<?=$_SESSION["DOMAIN_PATH"]?>/css/global.css" />
        <link rel="stylesheet" type="text/css"  href="<?=$_SESSION["DOMAIN_PATH"]?>/css/scrollBar.css" />
      
        
        <script src="<?=$_SESSION["DOMAIN_PATH"]?>/js/userFunctions.js"></script>   
        
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script src="<?=$_SESSION["DOMAIN_PATH"]?>/js/jquery.nice-select.js"></script>
        <link rel="stylesheet" type="text/css"  href="<?=$_SESSION["DOMAIN_PATH"]?>/css/nice-select.css" />
        <!-- Nice select set DOM objects-->
        <script>
                $(document).ready(function() {$('#pers_age').niceSelect();});
        </script>   
        
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
        <!--< script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>-->
        <script src="<?=$_SESSION["DOMAIN_PATH"]?>/js/bootstrap.min.js"></script> 
        
        <!--< script src="< ?=$_SESSION["DOMAIN_PATH"]?>/js/bootstrap-hover-dropdown.js"></script>-->   
        
        <!--multi select-->
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.23/jquery-ui.min.js"></script>
        <link rel="stylesheet" type="text/css"  href="<?=$_SESSION["DOMAIN_PATH"]?>/msCss/jquery-ui-1.7.1.template_v2.css" />
        


        <link rel="stylesheet" type="text/css"  href="<?=$_SESSION["DOMAIN_PATH"]?>/msCss/jquery.multiselect.css" />
        <link rel="stylesheet" type="text/css"  href="<?=$_SESSION["DOMAIN_PATH"]?>/msCss/jquery.multiselect.filter.css" />

        <link rel="stylesheet" type="text/css"  href="<?=$_SESSION["DOMAIN_PATH"]?>/msCss/style.css" />       
        <script src="<?=$_SESSION["DOMAIN_PATH"]?>/msJs/jquery.multiselect.min.js"></script>
        <script src="<?=$_SESSION["DOMAIN_PATH"]?>/msJs/jquery.multiselect.filter.min.js"></script>
        <script src="<?=$_SESSION["DOMAIN_PATH"]?>/msJs/smartSearch.js"></script>
        
        <script src="<?=$_SESSION["DOMAIN_PATH"]?>/js/captcha.js"></script> 
        <script src="<?=$_SESSION["DOMAIN_PATH"]?>/js/library.js"></script>
        <script src="<?=$_SESSION["DOMAIN_PATH"]?>/js/unex.js"></script>  
        <script src="<?=$_SESSION["DOMAIN_PATH"]?>/md5/md5.js"></script> 
</head>

<body class="body" id="body" style="display:none;">                  


<?
        $db  = _constructClass("db");
        $sys = _constructClass("sys");
        
        
        if(isDemo()) {
                $aMsg = array("Aktuálne len demo verzia !");
                bsModal("demoversion", $aMsg);     
                bsAlert("Demoverzia s obmedzenými možnosťami", 2);
        }

        //$db->dropTables("manab");

        if(is_file("fragments/captcha.php")) {include("fragments/captcha.php");}
        
        
        //http://r2r.abnet.sk/admin/change?id=60&free=3H9AYVItlkLH35vjGGO
        
        if(strLen(Trim($_GET["free"]))>16) {
                $oFreeUser = $db->get("TESTER", "sysUserAccounts", array("acc_pass", "acc_id", "acc_login"), "acc_id='".$_GET["id"]."' && acc_freeEntry='".$_GET["free"]."'", true, __FILE__, __LINE__, true);
                if(count($oFreeUser) == 0) {if(is_file("fragments/login.php"))   {include("fragments/login.php");}}
        } else {
                if(is_file("fragments/login.php"))   {include("fragments/login.php");}
        }
        

        $aURI = explode("/",$_SERVER['REQUEST_URI']);
        if($_SESSION["SYS_ACCOUNT"]["acc_admin"]!=1 && strLen(Trim($aURI[count($aURI)-1]))>0) {
                if(is_file("fragments/mapVisitSite.php"))   {include("fragments/mapVisitSite.php");}
        }
        
        if(true && strLen(Trim($_POST["pers_name"]))>0) {
                // to do test
                echo("== ".$_POST["pers_name"].", ".$_POST["pers_surName"].", ".$_POST["pers_birth"].", ".$_POST["pers_telephone"].", ".$_POST["pers_email"].", ".$_POST["pers_hobby"].", ".$_POST["pers_attitude"].", ".implode("~", $_POST["pers_know"]).", ".implode("~", $_POST["pers_lang"])." ==<br />");
        }
        
        if($_SESSION["SYS_ACCOUNT"]["acc_chatAccess"] || $_SESSION["SYS_ACCOUNT"]["acc_chatAccess"]) {
                if(is_file("fragments/chat.php")) {include("fragments/chat.php");}
        }

        if(is_file("fragments/facebook.php"))   {include("fragments/facebook.php");}
        //if(is_file("fragments/twitter.php"))   {include("fragments/twitter.php");}
        if(is_file("fragments/topMenu.php"))   {include("fragments/topMenu.php");}
        ?><script>var p="";for(var i=0;i<(window.location.href.split("/").length)-3;++i) {p+="../";}</script><?

        
?>
        <div id="afterMenu" style="margin-top:100px;"></div>
<?
        
        if(is_file("fragments/jsDebug.php")) {include("fragments/jsDebug.php");}
        
        $entry = entryAllowed();
        //d($entry);
        if(getType($entry)=="string") {
                if(is_file($entry)) {
                        include($entry);
                }
        } else {
                $script = $aURI[count($aURI)-1];
                if(strPos($script, "?")) {$script = subStr($script, 0, strPos($script, "?"));}
                if($entry) {
                        $script = $script.".php";
                        if(is_file("sites/".$script)) {
                                include("sites/".$script);
                        }
                        
                } else {
                        $path = $aURI[count($aURI)-1];
                        if(strPos($path, "?")) {$path = subStr($path, 0, strPos($path, "?"));}
                        if($path=="visit" || $path=="change") {
                                if(is_file("sites/".$path.".php")) {
                                        include("sites/".$path.".php");
                                }
                        }
                        if($path=="access" || $path=="content" && $_SESSION["SYS_ACCOUNT"]["acc_admin"]==1) {
                                if(is_file("sites/".$path.".php")) {
                                        include("sites/".$path.".php");
                                }
                        }
                }
        }
        
        function entryAllowed($path=null) {
                if(strLen(trim($_SESSION["SYS_ACCOUNT"]["acc_id"]))==0) {return(false);}
                
                $db = _constructClass("db");
                $sys = _constructClass("sys");
                
                $script = true;
                if(strLen(Trim($path))==0) {
                        $aUri = explode("/", $_SERVER["REQUEST_URI"]);
                        $oMenuCurrent = $db->get("MENU", "generalMenu", array("men_id", "men_path"), "men_path='".$aUri[count($aUri)-1]."'", true, __FILE__, __LINE__, true);
                }
                
                
                if(getType($path)=="NULL") {
                        $aURI = explode("/",$_SERVER['REQUEST_URI']);
                        $path = $aURI[count($aURI)-1];
                }
                
                if($_SESSION["SYS_ACCOUNT"]["acc_supervisor"]!=1 && $_SESSION["SYS_ACCOUNT"]["acc_admin"]!=1) {
                        $oUserCurrent = $sys->arrayIn($_SESSION["ACCESSRIGHT_MENU"], '$rig_idMenu=="'.$oMenuCurrent["men_id"].'"',true);
                        if(count($oUserCurrent)==0) {return(false);}
                }
                $oMenuCurrent = $sys->arrayIn($_SESSION["GLOBAL_MENU"], '$men_path=="'.$path.'"', true);
                if($oMenuCurrent["men_idParent"]==0) {return(false);}
                
                if(count($oMenuCurrent)>0 || $_SESSION["SYS_ACCOUNT"]["acc_supervisor"]==1 || $_SESSION["SYS_ACCOUNT"]["acc_admin"]==1) {
                        $oTemplates = $sys->arrayIn($_SESSION["ALL_TEMPLATES"], '$tmp_id=="'.$oMenuCurrent["men_template"].'"', true);
                        if(count($oTemplates)>0) {
                                $path   = "fragments/";
                                $script = $oTemplates["tmp_scriptName"];
                        }
                }          
                
                if(strLen(Trim($script))==0 && strLen(Trim($oMenuCurrent["tmp_scriptName"]))==0) {
                        $aUri = explode("/", $_SERVER["REQUEST_URI"]);
                        $path   = "sites/";
                        $script = $aUri[count($aUri)-1];
                }
                
                return($path.$script.".php");
        }
        
        //if(is_file("fragments/slider.php")) {include("fragments/slider.php");}
        //if(is_file("fragments/album.php"))  {include("fragments/album.php");}
        //if(is_file("forms/test.php"))       {include("forms/test.php");}
     
        include("fragments/downContainer.php");
        if(($_SERVER["REQUEST_URI"]=="/" || $_SERVER["REQUEST_URI"]=="") && getType($_SESSION["SYS_ACCOUNT"]) == "array") {
                $path = $sys->pathFromMenu();
                
                if(!$path) {$path = "admin/visit";}
?>
                <script>window.location.href = "http://<?=$_SERVER['SERVER_NAME']?>/<?=$path?>";</script>                  
<?
        }
?>

<form id="screen" method="POST"> 
        <input type="hidden" id="screenHeight" name="screenHeight" value="<?=$_POST["screenHeight"]?>" />
        <input type="hidden" id="screenWidth" name="screenWidth" value="<?=$_POST["screenWidth"]?>" />
</form> 

<?
        if(strLen(Trim($_SESSION["SCREEN_HEIGHT"])) == 0 || false) {
?>
        <script>
        var height = document.forms["screen"].screenHeight.value;
        if(height.length == 0) {
                document.forms["screen"].screenHeight.value = $(window).height();
                document.forms["screen"].submit();
        }
        </script>        
<? 
        }
        
?>

</body>
<?
        function    getSystemRoot() {
        
                $lastPath=getCWD();
                if( is_file("index.php") )    {return(getCWD());}
        
                $aDir=explode("/",getCWD());
                $index  =   Count($aDir);   
                for($x=0;$x<10;++$x)    {
                
                        if( is_file("index.php") )    {$lastSystemRoot=$systemRoot;}
                        $systemRoot=""; for($i=1;$i<($index-1);++$i)  {$systemRoot.="/".$aDir[$i];    } --$index;
                        if(!@chDir($systemRoot)) {break;} 
                }
                
                chDir($lastPath);
                
                return($lastSystemRoot);
        }
        
?>
</html>  

<script>
function unLogin() {window.location.href = "http://<?=$_SERVER['SERVER_NAME']?>/odhlasenie";}
</script>
