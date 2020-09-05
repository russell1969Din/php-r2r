<?session_start();?>

<?php
        include("service.php");
        include("library.php");
        

        if(strLen(Trim($_SESSION["SYSTEM_PHP_INTERFACE"]))==0 || ($_SESSION["SYSTEM_PHP_INTERFACE"]!=$_SESSION[$_SESSION["SYSTEM_PHP_INTERFACE"]])) {
                die();
        }
        

        include($_SESSION["PROJECT_INFO"]);
        $db  = _constructClass("db");
        $aTables = array("generalMenu", "contentSites");
        $aFields = array("con_path", "con_videoName");
        $aURL = explode("/",$_SESSION["REQUEST_URI"]);
        $where = "men_id=con_idMenu && men_path='".$aURL[count($aURL)-1]."'";
        //$where = "men_id=con_idMenu && men_path='videoin'";
        $oVideo = $db->get("GETVIDEO", $aTables, $aFields, $where, true, __FILE__, __LINE__, true);
        //d($oVideo["con_videoName"]);
        //d($oVideo["con_path"]);
        
        $video  = $oVideo["con_videoName"];
        $path   = $oVideo["con_path"];
        $output = "";
        if(strLen(trim($video))>0 && is_file($_SESSION["SYSTEM_ROOT"]."/".$videoFolder."/").$video) {
                $output = unc("/".$videoFolder."/".$video);
        } elseif(strLen(trim($path))>0)  {
                $output = unc($path);
        }
        if($_SESSION["SYSTEM_PHP_INTERFACE"]==$_SESSION[$_SESSION["SYSTEM_PHP_INTERFACE"]] && strLen(Trim($_SESSION["SYSTEM_PHP_INTERFACE"]))>0) {
                echo($output);
                //$_SESSION["SYSTEM_PHP_INTERFACE"] = null;
                $_SESSION[$_SESSION["SYSTEM_PHP_INTERFACE"]] = $_SESSION["SYSTEM_PHP_INTERFACE"] = null;
        }
        
        //
                                                      
        /*                                                      
        if(strLen(Trim($output))>0 && getType($_SESSION["SYS_ACCOUNT"])!="NULL") {
                if(count($_SESSION["SYS_ACCOUNT"]) > 0 && strPos($_SERVER["REQUEST_URI"],"video")) {
                        echo(unc($output));
                }
        }
        */
?>