<link rel="stylesheet" type="text/css"  href="<?=$_SESSION["DOMAIN_PATH"]?>/css/table.css" />
<link rel="stylesheet" type="text/css"  href="<?=$_SESSION["DOMAIN_PATH"]?>/css/form.css" />


<?
        $aMsg = "Naozaj chcete vymazať túto prílohu ?";
        $aAddMsg = "Tento krok nie je možné vrátiť späť !!!";
        $aYes = "deleteCont_formUpload";
        bsModal("delContent", $aMsg, $aAddMsg, $aYes);
        
        $aMsg = "Vymazať položku menu ?";
        $aAddMsg = "Tento krok vie vrátiť len správca db !!!";
        $aYes = "delete_contAdmin";
        bsModal("delMenuId", $aMsg, $aAddMsg, $aYes);
        
        $aMsg = "Vymazať prístup užívateľa k položke menu ?";
        $aYes = "delete_formAccess";
        bsModal("delRight", $aMsg, null, $aYes);

        if($_POST["menuOthers"] > 0) {
                $_POST["idEditMenu_contAdmin"] = $_POST["menuOthers"]; 
        }

        function getFreeVideo() {
                include($_SESSION["PROJECT_INFO"]);
                $serverDir = scandir( $_SESSION["SYSTEM_ROOT"]."/".$videoFolder);
                $aFreeVideos = array();
                foreach ($serverDir as $key => $value) {
                        if (!in_array($value,array(".",".."))) {
                                $start = strPos("|".$value, $separatorID ); //"~-~"
                                $stop = strRPos("|".$value, ".");
                                $menId = subStr($value, ($start+2), ($stop-($start+3)));
                                $inReturn = true;
                                if(strLen(Trim($menId))>0) {
                                        if(intVal($menId)>0) {$inReturn = false;}
                                }
                                if($inReturn) {$aFreeVideos[] = $value;}
                        }
                }
                return($aFreeVideos);
        }
        
        function setFreeVideo($aFreeVideo, $db) {

        $oCurrent = $db->get("TESTER", "contentSites", "con_videoName", "con_idMenu='".$_POST["setFTPVideo_formUpload"]."'", true, __FILE__, __LINE__, true);
        
?>
        <form id="formSetVideo" method="post" action="">

        <div class="container text-center ">
        <div class="container mt-2 pt-1 formMenuHead ">
                Voľné videá ku dispozícii 
        </div>


        <div class="container mt-2 ">
        <style>.classFile a:hover {text-decoration:underline;cursor:pointer;}</style>        
<?                                                                                             
        $usingVideo = false;
        if(is_file($_SESSION["SERVER_PATH_VIDEO"]."/".$oCurrent["con_videoName"])) {
?>
                <div class="col-sm-12 pt-1 mt-2 text-left" style="color:#cc0000;">
                        V aktuálnej položke menu sa používa iná príloha, najprv ju treba vymazať !
                </div>
<?        
                $aFreeVideo = array(); $usingVideo = !$usingVideo;
        }

        $line = $subIndex = 1;
        if(count($aFreeVideo)==0 && !$usingVideo) {
?>
                <div class="col-sm-12 pt-1 mt-2 text-left" style="color:#cc0000;">
                        Nepoužitá príloha na serveri nie je k dispozícii!
                </div>
<?       
        }
        foreach($aFreeVideo as $file) {
                $style = ''; if(($line%2)!=0) $style = 'background-color:#A8A8A8;';
?>        
                <div class="col-sm-4 pt-1 mt-2 text-left classFile" style="<?=$style?>">
                        <a onclick="hiddenSet('video_formSetVideo', '<?=$file."|||".$_POST["setFTPVideo_formUpload"]?>', true)">
                        <?=$file?>
                        </a>
                </div>
<?
                ++$subIndex;
                if($subIndex == 4) {
?>
                </div>
                <div class="container mt-2">
<?
                 $subIndex = 1;
                }
                ++$line;
        }
        if($subIndex < 4) {
?>
        </div>
<?                
        }  
?>        
        </div>
        </div>

        <input type="hidden" id="video_formSetVideo" name="video_formSetVideo" value="" />
        </form>
<?
        }
        
        if($_POST["returnOnly_formMenuItem"]>0) {
                $_POST["parentSet_contAdmin"] = $_POST["returnOnly_formMenuItem"];
        }

        $aUri = explode("/", $_SERVER['REQUEST_URI']);
        $personPath = str_replace("/".$aUri[count($aUri)-1], "/access", $_SESSION["URL_FULL"]);
        
        if(strLen(Trim($_POST["video_formSetVideo"]))>0) {
                $aVideo = explode("|||", $_POST["video_formSetVideo"]);
                $_POST["idEditMenu_contAdmin"] = $aVideo[1];
                $dot = strRPos($aVideo[0], ".");
                $start = subStr($aVideo[0], 0, $dot);
                $stop  = subStr($aVideo[0], $dot, strLen($aVideo[0]));
                $video = $start.$separatorID .$aVideo[1].$stop; //"~-~"
                rename( $videoFolder."/".$aVideo[0],
                        $videoFolder."/".$video);
                $oTester = $db->get("TESTER", "contentSites", "con_idMenu", "con_idMenu='".$aVideo[1]."'", true, __FILE__, __LINE__, true);
                if(count($oTester)>0) {
                        $db->update("contentSites", array("con_videoName", "con_path"), array($video, $_SESSION["DOMAIN_PATH_VIDEO"]."/".$video), "con_idMenu='".$aVideo[1]."'", __FILE__, __LINE__);
                } else {
                        $aValues    = array();
                        $aValues[]  = array("con_videoName"=>$video, "con_path"=>$_SESSION["DOMAIN_PATH_VIDEO"]."/".$video, "con_idMenu"=>$aVideo[1]);
                        $db->insert("contentSites", $aValues, false, __FILE__, __LINE__, false);
                }
                

        }
        
        if($_POST["setFTPVideo_formUpload"]>0) {
                $_POST["idEditMenu_contAdmin"] = $_POST["idEditMenu_formUpload"];
                setFreeVideo(getFreeVideo(), $db);
        }

        if($_POST["setNewMenu_formMenuItem"] > 0) {
                $parent = $_POST["setNewMenu_formMenuItem"]; if($parent==999999) {$parent=0;}
                $oTester = $db->get("TESTER", "generalMenu", "men_index", "men_idParent='".$parent."' ORDER BY men_index DESC", true, __FILE__, __LINE__, true);
                $index = $oTester["men_index"]; if(strLen(Trim($index))==0) {$index=0;}
                $block=0; if($_POST["men_block"]=="on") {$block=1;}
                $aValues = array();
                $aValues[] = array( "men_itemName"=>$_POST["men_itemName"],
                                    "men_idParent"=>$_POST["men_idParent"],
                                    "men_index"=>($index+1),
                                    "men_path"=>$_POST["men_path"],
                                    "men_template"=>$_POST["men_template"],
                                    "men_block"=>$block);
                $aNew = $db->insert("generalMenu", $aValues, false, __FILE__, __LINE__, false);
                $_POST["idEditMenu_contAdmin"] = $aNew[0];
                bsAlert("Nová položka menu = ".$_POST["men_itemName"]." = bola zapísaná", 1);
                $db->flushSessionsBank();
                $db->get("GLOBAL_MENU", "generalMenu", null, "men_delete=0 ORDER BY men_index ASC", true, __FILE__, __LINE__);
        }
        //l(isSet($_FILES["fileToUpload"]));
        //d(strLen(Trim($_FILES["fileToUpload"]["name"])));
        //d(strLen(trim($_FILES["fileToUpload"]["tmp_name"])));
        //d($_POST["uploadCurrent"]);
        $errorSystemMoveFile = false;
        $ok = (strLen(Trim($_FILES["fileToUpload"]["name"]))>0 || strLen(trim($_FILES["fileToUpload"]["tmp_name"])) >0);
        if($_POST["uploadCurrent"]>0 && (!isSet($_FILES["fileToUpload"]) && !$ok)) {
                bsAlert("Chyba pri prenose súboru, nahrávanie zopakujte !", 0);
                $_POST["idEditMenu_contAdmin"] = $_POST["idEditMenu_formUpload"];
                $errorSystemMoveFile = true;
        }

        
        // || isSet($_FILES["fileToUpload"])
        if($_POST["saveVideo_formUpload"] > 0 && !$errorSystemMoveFile) {
                $temp = basename($_FILES["fileToUpload"]["name"]);
                $dot = strRPos($temp, ".");
                $targetFile = subStr($temp, 0, ($dot)).$separatorID.$_POST["saveVideo_formUpload"].".".subStr($temp, ($dot+1), strLen($temp)); //"~-~"
                $videoPath = $_POST["con_path"];
                $basePath = getCWD();
                chDir($_SESSION["SYSTEM_ROOT"]);
                if(!is_dir($videoFolder)) {mkDir($videoFolder);}
                chDir($_SESSION["SYSTEM_ROOT"]."/".$videoFolder);
                $tmpFile = $_FILES["fileToUpload"]["tmp_name"];
                if($_POST["uploadCurrent"]>0) {
                        move_uploaded_file($tmpFile, $targetFile);
                        $isUpload = true;
                        if(strLen(Trim($videoPath))==0) {
                                $videoPath = $_SESSION["DOMAIN_PATH"]."/".$videoFolder."/".$targetFile;
                        }
                        if(strToLower($targetFile) != strToLower($oTester["con_videoName"])) {
                                $videoPath = $_SESSION["DOMAIN_PATH"]."/".$videoFolder."/".$targetFile;
                        }
                }
                chDir($basePath);
                
                $oTester = $db->get("TESTER", "contentSites", null, $where="con_idMenu='".$_POST["saveVideo_formUpload"]."'", true, __FILE__, __LINE__, true);
                if($_POST["saveVideo_formUpload"]>0 && $isUpload) {
                        if(is_file($_SESSION["SYSTEM_ROOT"]."/".$videoFolder."/".$oTester["con_videoName"])) {
                                if(strToLower($targetFile) != strToLower($oTester["con_videoName"])) {
                                     unlink($_SESSION["SYSTEM_ROOT"]."/".$videoFolder."/".$oTester["con_videoName"]);
                                     if(strPos(strToLower($oTester["con_path"]), "/".strToLower($oTester["con_videoName"]))) {
                                            $db->update("contentSites", "con_path", "", "con_idMenu='".$_POST["saveVideo_formUpload"]."'", __FILE__, __LINE__);
                                           
                                     }
                                }
                        }
                }
                
                if(strLen(Trim($_POST["con_path"]))>0) {
                        if($isUpload && !strPos(strToLower($_POST["con_path"]), "/".strToLower($targetFile))) {
                                $videoPath = $_POST["con_path"];
                        }
                }  
                if($_POST["saveVideo_formUpload"]>0) {
                        if(strPos("~".$targetFile, $separatorID)==1) {
                                $serverDir = scandir( $_SESSION["SYSTEM_ROOT"]."/".$videoFolder);
                                foreach ($serverDir as $key => $value) {
                                        if(strPos($value, $separatorID.$_POST["saveVideo_formUpload"].".")) {
                                                

                                                $targetFile = $value;
                                                if(strLen(Trim($videoPath))==0) {
                                                        $videoPath = $_SESSION["SERVER_PATH_VIDEO"]."/".$targetFile;
                                                }
                                                break;
                                        }
                                }
                        }
                        
                        if(strRPos($targetFile, ".")==strLen(Trim($targetFile))-1) {
                                if(strRPos($videoPath, ".")<strLen(Trim($videoPath))-1 && strRPos($videoPath, ".")>strLen(Trim($videoPath))-5) {
                                        $aPath = explode("/", $videoPath);
                                        $targetFile = $aPath[count($aPath)-1];
                                }
                        }
                        
                         if(strRPos($videoPath, ".")==strLen(Trim($videoPath))-1  && strRPos($videoPath, ".")>strLen(Trim($videoPath))-5) {
                                $pointer = strRPos($videoPath, "/");
                                $videoPath = subStr($videoPath, 0, $pointer+1).$targetFile;
                         }
                         
                        if($_POST["uploadCurrent"]>0) {
                                if( strRPos($targetFile, ".")==strLen(Trim($targetFile))-1 ||
                                    strRPos($videoPath, ".")==strLen(Trim($videoPath))-1) {
                                        $errorSystemMoveFile = true;
                                        bsAlert("Chyba pri prenose súboru, vymažte cestu a nahrávanie zopakujte !", 0);
                                        $aFields = array("con_path");
                                        $aValues  = array("");
                                }
                        }

                        if(count($oTester) == 0) {
                                $aValues = array();
                                if($isUpload) {
                                        $aValues[] = array("con_videoName"=>$targetFile, "con_path"=>$videoPath, "con_idMenu"=>$_POST["saveVideo_formUpload"]);
                                } else {
                                        $aValues[] = array("con_path"=>$videoPath, "con_idMenu"=>$_POST["saveVideo_formUpload"]);
                                }
                                $db->insert("contentSites", $aValues, false, __FILE__, __LINE__, false);
                        } else {
                                if($isUpload) {
                                        $aFields = array("con_videoName", "con_path");
                                        $aValues  = array($targetFile, "");
                                } else {
                                        $aFields = array("con_path");
                                        $aValues  = array($videoPath);
                                }
                                $db->update("contentSites", $aFields, $aValues, "con_idMenu='".$_POST["saveVideo_formUpload"]."'", __FILE__, __LINE__);
                        }
                        if($_POST["updateEditor"]>0) {
                                $handle = fopen("lc-scripts/scripts_".$_POST["saveVideo_formUpload"].".html", "w+");
                                fwrite($handle, $_POST["con_note"]);
                                fclose($handle);
                        }
                }
                $_POST["idEditMenu_contAdmin"] = $_POST["idEditMenu_formUpload"];
                if(!$errorSystemMoveFile) {
                        if($_POST["uploadCurrent"]>0) {
                                bsAlert("Video bolo úspešne nahraté, zmeny zapísané", 1);    
                        } else {
                                if($_POST["saveVideo_formUpload"] > 0) {
                                        bsAlert("Zmeny okrem nahratia videa sú úspešne zapísané", 1);
                                }
                        }
                }
                $db->flushSessionsBank();
                $db->get("GLOBAL_MENU", "generalMenu", null, "men_delete=0 ORDER BY men_index ASC", true, __FILE__, __LINE__);
                $_FILES["fileToUpload"] = null;
        }                       

        if($_POST["idMenuDelete_contAdmin"] > 0) {
                $allRightDelete = false;
                $oDeleteRec = $sys->arrayIn($_SESSION["GLOBAL_MENU"], '$men_id=="'.$_POST["idMenuDelete_contAdmin"].'"', true);
                if($oDeleteRec["men_idParent"] == 0) {
                        $oDeleteChild = $sys->arrayIn($_SESSION["GLOBAL_MENU"], '$men_idParent=="'.$oDeleteRec["men_id"].'"');
                        if(count($oDeleteChild) > 0) {
                                bsAlert("Určená položka menu obsahuje podradené menu", 0);
                        } else { $allRightDelete = true;}
                
                } else { $allRightDelete = true;}
                
                if($allRightDelete) {
                        $allRightDelete = $_POST["idMenuDelete_contAdmin"];
                        bsAlert("Určená položka menu bola úspešne vymazaná", 2);
                }
                $db->update("generalMenu", "men_delete", 1, "men_id='".$_POST["idMenuDelete_contAdmin"]."'", __FILE__, __LINE__);
                $db->flushSessionsBank();
                $db->get("GLOBAL_MENU", "generalMenu", null, "men_delete=0 ORDER BY men_index ASC", true, __FILE__, __LINE__);
        }

        if($_POST["delete_formUpload"]>0 || $allRightDelete>0)  {
                 $_POST["idEditMenu_contAdmin"] = $_POST["idEditMenu_formUpload"];
                if($allRightDelete>0) {$_POST["delete_formUpload"] = $allRightDelete;}
                $oContent = $db->get("CONTENT", "contentSites", array("con_videoName", "con_path"), "con_idMenu='".$_POST["delete_formUpload"]."'", true, __FILE__, __LINE__, true);
                $videoPath = $_SESSION["DOMAIN_PATH"]."/".$videoFolder."/".$oContent["con_videoName"];
                $isUnlink = false;
                if(is_file($_SESSION["SYSTEM_ROOT"]."/".$videoFolder."/".$oContent["con_videoName"])) {
                        unlink($_SESSION["SYSTEM_ROOT"]."/".$videoFolder."/".$oContent["con_videoName"]);
                        $isUnlink = !$isUnlink;
                }
                if($allRightDelete>0) {
                        $db->delete("contentSites", $allRightDelete, __FILE__, __LINE__);
                } else {
                        $db->update("contentSites", "con_videoName", "", "con_idMenu='".$_POST["delete_formUpload"]."'", __FILE__, __LINE__);
                }
                
                if($_POST["delete_formUpload"]>0) {
                        if(strToLower($oContent["con_path"])==strToLower($videoPath)) {
                                $db->update("contentSites", "con_path", "", "con_idMenu='".$_POST["delete_formUpload"]."'", __FILE__, __LINE__);                        
                        }
                }
                if($isUnlink && strLen(Trim($_POST["idMenuDelete_contAdmin"]))==0) {bsAlert("Video v tejto položke menu je vymazané", 2);}
        }

        if($_POST["setSave_formMenuItem"] > 0) {
                $menId = $_POST["setSave_formMenuItem"]; 
                $_POST["idEditMenu_contAdmin"] = $_POST["idEditMenu_formMenuItem"];
                $block=0; if($_POST["men_block"]=="on") {$block=1;}
                $lockA=0; if($_POST["men_lockArea"]=="on") {$lockA=1;}
                
                $oldMenuRec = $sys->arrayIn($_SESSION["GLOBAL_MENU"], '$men_id=="'.$_POST["setSave_formMenuItem"].'"', true);
                if($oldMenuRec["men_idParent"]!=$_POST["men_idParent"]) {
                        $oldChilds = $sys->arrayIn($_SESSION["GLOBAL_MENU"], '$men_idParent=="'.$_POST["men_idParent"].'"');
                        $index = 1;
                        foreach($oldChilds as $aChild) {
                                $db->update("generalMenu", "men_index", $index, "men_id='".$aChild["men_id"]."'", __FILE__, __LINE__);  ++$index;
                        }
                        $db->update("generalMenu", "men_index", $index, "men_id='".$_POST["setSave_formMenuItem"]."'", __FILE__, __LINE__);
                }
                $aFieldNames = array("men_itemName", "men_idParent", "men_path", "men_block", "men_template", "men_lockArea");
                $aValues     = array($_POST["men_itemName"], $_POST["men_idParent"], $_POST["men_path"], $block, $_POST["men_template"], $lockA);
                $db->update("generalMenu", $aFieldNames, $aValues, "men_id='".$menId."'", __FILE__, __LINE__);
                $db->flushSessionsBank();
                $db->get("GLOBAL_MENU", "generalMenu", null, "men_delete=0 ORDER BY men_index ASC", true, __FILE__, __LINE__);
                bsAlert("Zmeny v tejto položke menu boli zapísané", 2);
        }                

        $currentRight = false;
        if($_POST["noAccess_formAccess"] > 0) {
                $_POST["idEditMenu_contAdmin"] = $_POST["idEditMenu_formAccess"];
                $aValues = array();
                $aValues[] = array("rig_idUser"=>$_POST["noAccess_formAccess"], "rig_idMenu"=>$_POST["idEditMenu_contAdmin"]);
                $db->insert("menuRightsAccess", $aValues, false, __FILE__, __LINE__, true);
                $currentRight = true;
                bsAlert("K tejto položke menu bol priradený nový prístup", 2);
        }

        if($_POST["setDelAcc_formAccess"] > 0) {
                $_POST["idEditMenu_contAdmin"] = $_POST["idEditMenu_formAccess"];
                $db->delete("menuRightsAccess", "rig_idUser='".$_POST["setDelAcc_formAccess"]."' && rig_idMenu='". $_POST["idEditMenu_formAccess"]."'", __FILE__, __LINE__);
                $currentRight = true;
                bsAlert("Pri tejto položke menu bol jeden z prístupov zrušený", 2);
        }
        
        if($currentRight) {
                $db->get("ACCESSRIGHT_MENUX", array("menuRightsAccess", "generalMenu"), null, "men_id=rig_idMenu && rig_idUser='".$_POST["setDelAcc_formAccess"]."' && men_block=0 && men_delete=0", true, __FILE__, __LINE__);
                $_SESSION["ACCESSRIGHT_MENU"] = $_SESSION["ACCESSRIGHT_MENUX"];
                $db->get("GLOBAL_ACCESSRIGHTX", array("menuRightsAccess"), null, "1", true, __FILE__, __LINE__);
                $_SESSION["GLOBAL_ACCESSRIGHT"] = $_SESSION["GLOBAL_ACCESSRIGHTX"];
        }

        
        if($_POST["idMenuSetUp_contAdmin"]>0) {
                $aMenu = $db->get("TESTER", "generalMenu", null, "men_id='".$_POST["idMenuSetUp_contAdmin"]."'", true, __FILE__, __LINE__, true);
                $newIndex = ($aMenu["men_index"]-2);
                if($newIndex<0) {$newIndex=0;}
                $newIndex += 0.6;
                $db->update("generalMenu", array("men_index"), array($newIndex), "men_id='".$aMenu["men_id"]."'", __FILE__, __LINE__);
                $oTempMenu = $db->get("TESTER", "generalMenu", null, "men_idParent='".$aMenu["men_idParent"]."' ORDER BY men_index ASC", true, __FILE__, __LINE__);
                $index = 1;
                foreach($oTempMenu as $aMenu) {
                        $db->update("generalMenu", array("men_index"), array($index), "men_id='".$aMenu["men_id"]."'", __FILE__, __LINE__);
                        ++$index;
                }
                $db->flushSessionsBank();
                $db->get("GLOBAL_MENU", "generalMenu", null, "men_delete = 0", true, __FILE__, __LINE__);
                bsAlert("V aktuálnej skupine menu položiek bolo zmenené poradie", 2);
        }
        
        $oParent = $db->get("PARENT_SET", "generalMenu", null, "men_idParent=0 && men_delete=0 ORDER BY men_index ASC", false, __FILE__, __LINE__, false);
        if(strLen(Trim($_POST["parentSet_contAdmin"])) == 0) {
                $first = true;                
                foreach($oParent as $aParent) {
                        $aFirstParent = $aParent; $first = false;
                        if($aParent["men_delete"] == 0) {
                                $aFirstParent = $aParent;
                                break;
                        } 
                }
                $_POST["parentSet_contAdmin"] = $aFirstParent["men_id"];
        }
        
        
        if(strLen(Trim($_POST["idEditMenu_contAdmin"]))>0 || $_POST["newMenuInParent_contAdmin"] > 0) {
                $oFormMenu = $db->get("FORFORM", "generalMenu", null, "men_id='".$_POST["idEditMenu_contAdmin"]."'", true, __FILE__, __LINE__, true);
                formularMenu($db, $sys, $oFormMenu, $oParent, $personPath);
        }
?>
<form id="contAdmin" method="post">
<?

if(strLen(Trim($_POST["idEditMenu_contAdmin"]))==0 && strLen(Trim($_POST["newMenuInParent_contAdmin"]))==0) {
?>
<div class="container ">
        <div class="d-flex justify-content-center">
                <div style="width:400px;">
                <select id="parentSet_contAdmin" name="parentSet_contAdmin" class="nice-select" onchange="submit()">
<?
                if(count($oParent)==0) {
?>
                <option value=""></option>
<?                
                }
                foreach($oParent as $aParent) {
                        $selected = '';
                        if($_POST["parentSet_contAdmin"] == $aParent["men_id"]) {$selected = 'selected="selected"';}
?>
                        <option <?=$selected?> value="<?=$aParent["men_id"]?>"><?=$aParent["men_itemName"]?></option>
<?
                }
                
                $selected = '';
                if($_POST["parentSet_contAdmin"] == 999999) {$selected = 'selected="selected"';}
?>
                <option <?=$selected?> value="999999">Rodičovské menu</option>
                </select>
                <input type="hidden" id="parent_contAdmin" name="parent_contAdmin" value="" />
                </div>
                <script>$(document).ready(function() {$('#parentSet_contAdmin').niceSelect();});</script>
        </div>
</div>
<?
if($_POST["parentSet_contAdmin"]>0) {
        if($_POST["parentSet_contAdmin"]<999999) {
                $oChild  = $db->get("CHILD_SET_".$_POST["parentSet_contAdmin"], "generalMenu", null, "men_idParent='".$_POST["parentSet_contAdmin"]."' && men_delete=0 ORDER BY men_index ASC", false, __FILE__, __LINE__);
        } else {
                $oChild  = $db->get("PARENT_SET_".$_POST["parentSet_contAdmin"], "generalMenu", null, "men_idParent='0' && men_delete=0 ORDER BY men_index ASC", false, __FILE__, __LINE__);
        }
?>

<div class="container mt-2  ">
        <div class="row tableHead pt-1">
                <div class="col-xs-3 col1 col1Title " data-toggle="tooltip" title="Názov položky menu.">
                        Položka menu
                </div>
                <div class="col-xs-3 tableOnlyMore768 col2 col2Title " data-toggle="tooltip" title="Položka menu spadá pod rodičovské menu.">
                        Položka rodič
                </div>
                <div class="col-xs-3 col3 col3Title " data-toggle="tooltip" title="Časť URL cesty, ktorú má položka menu volať.">
                        URL
                </div>
                <div class="d-flex justify-content-center col-xs-1 col4 col4Title " data-toggle="tooltip" title="Položku menu nezobrazovať." >
                        Zobraz
                </div>
                <div class="d-flex justify-content-center col-xs-1 col5 col5Title " data-toggle="tooltip" title="Položku menu presnúť pred predchádzajúcu.">
                        Presuň
                </div>
                <div class="d-flex justify-content-center col-xs-1 col6 col6Title " data-toggle="tooltip" title="Vymazať položku menu.">
                        Výmaz
                </div>

        </div>
</div>
<div class="container mt-2  ">
<?
        $oParentIs = $db->get("PARENT_IS_".$_POST["parentSet_contAdmin"], "generalMenu", null, "men_id='".$_POST["parentSet_contAdmin"]."' && men_delete=0 ORDER BY men_index ASC", true, __FILE__, __LINE__, true);
?>
        <div class="row mt-1 tableLine tableUnderLine tableColumn pt-1">
                <div class="col-xs-3 col1 pt-2 ">
                        <a data-toggle="tooltip" title="Zobraziť / editovať podrobnosti k tomuto menu"  onclick="hiddenSet('idEditMenu_contAdmin', '<?=$oParentIs["men_id"]?>', true)">
                              <?=$oParentIs["men_itemName"]?>
                        </a>
                </div>
                <div class="col-xs-3 pt-2 tableOnlyMore768 col2 ">
                        *&nbsp;*&nbsp;*&nbsp;*&nbsp;*
                </div>
                <div class="col-xs-3 pt-2 col3 ">
                        /<?=$oParentIs["men_path"]?>
                </div>
                <div class="d-flex justify-content-center pt-2 col-xs-1 col4 ">
<?
                if($oParentIs["men_block"]==0) {
                        echo('<i class="fas fa-eye"></i>'); 
                } else {
                        echo('<span class="tableWarning"><i class="far fa-eye-slash"></i></span>');                        
                }                
?>
                </div>
                <div class="d-flex justify-content-center col-xs-1 pt-2 col5  ">
                        *
                </div>
                <div class="d-flex justify-content-center col-xs-1 pt-2 col6  ">
                        *
                </div>

        </div>
</div>

<div class="container mt-3   ">
<?
        $line = 0;
        foreach($oChild as $aChild) {
        $even = ''; if(($line%2)==0) $even = 'tableEvenLine'; 
        
    
?>
        <div class="row tableLine tableColumn <?=$even?>">
                <div class="col-xs-3 pt-2 col1 ">
                        <a data-toggle="tooltip" title="Zobraziť / editovať podrobnosti k tomuto menu"  onclick="hiddenSet('idEditMenu_contAdmin', '<?=$aChild["men_id"]?>', true)">
                                <?=$aChild["men_itemName"]?>
                        </a>
                </div>
                <div class="col-xs-3 pt-2 tableOnlyMore768 col2 ">
                        <?=$oParentIs["men_itemName"]?>
                </div>
                <div class="col-xs-3 pt-2 col3  ">
                        /<?=$aChild["men_path"]?>
                </div>                   
                <div class="d-flex justify-content-center col-xs-1 pt-2 col4  ">
<?
                if($aChild["men_block"]==0) {
                        echo('<i class="fas fa-eye"></i>'); 
                } else {
                        echo('<span class="tableWarning"><i class="far fa-eye-slash"></i></span>');                       
                }                
?>
                </div>
                <div class="d-flex justify-content-center  col-xs-1 col5 contentSetUp  ">
<?
                        if($line>0) {
?>
                        <span class="setUpItem"   >
                        <a class="set-up" onclick="hiddenSet('idMenuSetUp_contAdmin', '<?=$aChild["men_id"]?>', true)" >
                                <i data-placement="bottom" title="Presunúť položku menu o pozíciu vyššie" data-toggle="tooltip" class="fas fa-angle-up"></i>
                        </a>
                        </span>
                        <span class="setUpItemDis" data-toggle="modal" data-target="#demoversion">
                        <span class="set-up" >
                                <i data-placement="bottom" title="Presunúť položku menu o pozíciu vyššie" data-toggle="tooltip" class="fas fa-angle-up"></i>
                        </span>
                        </span>
<?
                        }
?>
                </div>
                
                <div class=" d-flex justify-content-center  col-xs-1 pt-2  col6 contentDelete ">
                        <span class="trashMenuIcon">
                        <a data-toggle="modal" class="trash-menu" data-target="#delMenuId"  onclick="hiddenSet('idMenuDelete_contAdmin', '<?=$aChild["men_id"]?>')" >
                                <i data-placement="bottom" title="Možnosť vymazať aktuálnu položku menu" data-toggle="tooltip"  class="fas fa-trash"></i>
                        </a>
                        </span>
                       <span class="trashMenuIconDis" data-toggle="modal" class="trash-menu" data-target="#demoversion">
                                <i placement="bottom" title="Možnosť vymazať aktuálnu položku menu" data-toggle="tooltip" class="fas fa-trash"></i>
                       </span>
                </div>

        </div>
<?        
        ++$line;
        }
?>
</div>
<script>
        if($(window).width()<=1920 && $(window).width()>1680) {
                //_dx($(window).width());
        }

        if($(window).width()<=1680 && $(window).width()>1600) {
                //_dx($(window).width());
        }

        if($(window).width()<=1600 && $(window).width()>1440) {
                //_dx($(window).width());
        }

        if($(window).width()<=1440 && $(window).width()>1366) {
                //_dx($(window).width());
        }

        if($(window).width()<=1366 && $(window).width()>1024) {
                //_dx($(window).width());
        }

        if($(window).width()<=1366 && $(window).width()>1024) {
                //_dx($(window).width());
        }

        if($(window).width()<=1024 && $(window).width()>800) {
                //_dx($(window).width());
        }

        if($(window).width()<=800 && $(window).width()>720) {
                //_dx($(window).width());
                $("div.col1").css("font-size", "14px");
                $("div.col3").css("font-size", "14px");
                $("div.col4Title").html('<i class="far fa-eye-slash"></i>');
                $("div.col5Title").html('<i class="fas fa-upload"></i>');
                $("div.col6Title").html('<i class="far fa-trash-alt"></i>');
                $("div.col4").css("font-size", "11px");
                $("div.col5").css("font-size", "11px")

                $(".trash-menu").css("font-size", "13px")
                $(".set-up").css("font-size", "18px")      
        }

        if($(window).width()<=768 && $(window).width()>600) {            
                //_dx($(window).width());
                /*
                $("div.col1").css("font-size", "14px");
                $("div.col3").css("font-size", "14px");
                $("div.col4Title").html('<i class="far fa-eye-slash"></i>');
                $("div.col5Title").html('<i class="fas fa-upload"></i>');
                $("div.col6Title").html('<i class="far fa-trash-alt"></i>');
                $("div.col4").css("font-size", "11px");
                $("div.col5").css("font-size", "11px")

                $(".trash-menu").css("font-size", "13px")
                $(".set-up").css("font-size", "18px")
                */                 
        }

        if($(window).width()<=720 && $(window).width()>414) {
                //_dx($(window).width());
                $("div.col1").css("font-size", "12px");
                $("div.col3").css("font-size", "12px");
                $("div.col4Title").html('<i class="far fa-eye-slash"></i>');
                $("div.col5Title").html('<i class="fas fa-upload"></i>');
                $("div.col6Title").html('<i class="far fa-trash-alt"></i>');
                $("div.col4").css("font-size", "11px");
                $("div.col5").css("font-size", "11px")

                $("div.col1").removeClass("col-xs-3");
                $("div.col1").addClass("col-xs-5");

                $("div.col3").removeClass("col-xs-3");
                $("div.col3").addClass("col-xs-4");                

                $("div.col5").css("font-size", "13px")
                $("div.col6").css("font-size", "11px")
             
                $(".trash-menu").css("font-size", "11px")
                $(".set-up").css("font-size", "14px")
        }

        if($(window).width()<=414) {
                //_dx($(window).width());
                $("div.col1").css("font-size", "12px");
                $("div.col3").css("font-size", "12px");
                $("div.col4Title").html('<i class="far fa-eye-slash"></i>');
                $("div.col5Title").html('<i class="fas fa-upload"></i>');
                $("div.col6Title").html('<i class="far fa-trash-alt"></i>');
                $("div.col4").css("font-size", "11px");
                $("div.col5").css("font-size", "11px")

                $("div.col1").removeClass("col-xs-3");
                $("div.col1").addClass("col-xs-5");
                
                $("div.col3").removeClass("col-xs-4");
                $("div.col3").addClass("col-xs-3");

                $("div.col5").css("font-size", "13px")
                $("div.col6").css("font-size", "11px")
             
                $(".trash-menu").css("font-size", "11px")
                $(".set-up").css("font-size", "14px") 
        }

        if($(window).width()<=375 && $(window).width()>360) {
                //_dx($(window).width());
        }

        if($(window).width()<=360 && $(window).width()>320) {
                //_dx($(window).width());
        }

        if($(window).width()<=320) {
                //_dx($(window).width());
        }

        //      $("div.col1").css("color", "");
        // cyan red green  magenta yellow pink orange black blue


        
        
</script>

<div class="container mt-3 d-flex justify-content-center buttonDesk ">                                            
        <input type="button" title="Možnosť vytvoriť novú položku menu v aktuálnom sektore" data-toggle="tooltip" alt="captcha" id="newMenu_contAdmin" onclick="hiddenSet('newMenuInParent_contAdmin', '<?=$_POST["parentSet_contAdmin"]?>', true)" class="btn btn-primary" value="Nová položka menu" />
        <span data-toggle="modal" data-target="#demoversion"   >
        <input type="button" title="Možnosť vytvoriť novú položku menu v aktuálnom sektore" data-toggle="tooltip"  id="newMenu_contAdminDis" onclick="" class="btn btn-primary" value="Nová položka menu" />
        </span>
        <input type="hidden" id="newMenuInParent_contAdmin" name="newMenuInParent_contAdmin" value="" />
</div>
<?
}
}
?>

<style>
.contentDelete {
        color:#cc0000;
}

.contentDelete a {
        color:#cc0000;
        font-weight:700;
        font-size:14px;
}

.contentDelete a:hover {
        color:#cc0000;
        font-weight:700;
        font-size:14px;
}

.contentSetUp {
        color:#006600;
        font-weight:700;
        font-size:20px;
}

.contentSetUp a {
        color:#006600;
        font-weight:700;
        font-size:20px;
}

.contentSetUp a:hover {
        color:#006600;
        font-weight:700;
        font-size:20px;
}


</style>


<input type="hidden" id="idMenuSetUp_contAdmin" name="idMenuSetUp_contAdmin"    value="" />
<input type="hidden" id="idMenuDelete_contAdmin" name="idMenuDelete_contAdmin"  value="" />
<input type="hidden" id="idEditMenu_contAdmin"  name="idEditMenu_contAdmin"     value="<?=$_POST["idEditMenu_contAdmin"]?>" />
</form>


<?
function formularMenu($db, $sys, $oMenuItem, $oParent, $personPath) {
include($_SESSION["PROJECT_INFO"]);
$border = " ";

$aFields = array("men_id", "men_itemName");
$where = "men_idParent='".$oMenuItem["men_idParent"]."' && men_id <> '".$oMenuItem["men_id"]."'";
$oMenuSelect = $db->get("MENUSELECT", "generalMenu", $aFields, $where, true, __FILE__, __LINE__);
?>   

<form id="otherMenu" method="post" action="">
<div class="container ">
        <div class="row" title="Môžete vybrať inú položku menu z toho stého rodičovského menu" data-toggle="tooltip" >
                <div class="col-sm-5 text-right pt-2">
                        Iná položka menu:&nbsp;        
                </div>
                <div class="col-sm-7 ">
                        <div style="width:250px;">
        
                        <select id="menuOthers" name="menuOthers" onchange="submit()" class="nice-select ">
                                <option value=""></option>
<?
                                foreach($oMenuSelect as $aMenuSel) {
?>
                                <option value="<?=$aMenuSel["men_id"]?>"><?=$aMenuSel["men_itemName"]?></option>
<?
                                }
?>
                        </select>
                        <script>$(document).ready(function() {$('#menuOthers').niceSelect();});</script>
                        </div>
                </div>
        </div>
</div>
</form>


<div class="container text-center ">
        <div class="container mt-2 pt-1 formMenuHead">
                Položka menu:&nbsp;<span class="formBold"><?=$oMenuItem["men_itemName"]?></span> 
        </div>
        <form id="formMenuItem" method="post" action="" >
        <div class="container mt-2">
                <div class="row mt-1 ">
                        <div id="men_itemName_title" class="col-sm-2 pt-1 text-left  ">
                                Názov položky:&nbsp;
                        </div>
                        <div class="col-sm-4 ">
                                <input title="Názov položky menu ako sa má zobrazovať" data-toggle="tooltip" type="text" alt="CNS~5" class="form-control text-dark"  id="men_itemName" name="men_itemName" value="<?=$oMenuItem["men_itemName"]?>" autocomplete="off" />
                                <div id="men_itemName_err" class="form_error"></div>
                        </div>
                        <div id="men_path_title" class="col-sm-2 pt-1 text-left ">
                                Cesta:&nbsp;
                        </div>
                        <div class="col-sm-4 ">
                                <input type="text" title="Cesta položky menu do URL" data-toggle="tooltip"  alt="CNP~5" class="form-control text-dark"  id="men_path" name="men_path" value="<?=$oMenuItem["men_path"]?>" autocomplete="off" />
                                <div id="men_path_err" class="form_error"></div>
                        </div>
                </div>
                <div class="row mt-1 ">
                        <div id="men_idParent_title"  class="col-sm-2 pt-1 text-left">
                                Rodič:&nbsp;
                        </div>
                        <div class="col-sm-6 " title="Rodičovská položka menu pod ktorú bude táto položka spadať" data-toggle="tooltip">
<?
                        if($oMenuItem["men_idParent"]>0 || $_POST["newMenuInParent_contAdmin"]>0 && $_POST["newMenuInParent_contAdmin"] !=999999) {
?>
                        <select alt="CN~0" style="width:100%;" onchange="" id="men_idParent" name="men_idParent" class="nice-select " >
<?
                                foreach($oParent as $aParent) {
                                if($_POST["newMenuInParent_contAdmin"]>0) {
                                        $selected=''; if($aParent["men_id"]==$_POST["newMenuInParent_contAdmin"]) {$selected='selected="selected"';}
                                } else {
                                        $selected=''; if($oMenuItem["men_idParent"]==$aParent["men_id"]) {$selected='selected="selected"';}
                                }
?>
                                <option <?=$selected?> value="<?=$aParent["men_id"]?>"><?=$aParent["men_itemName"]?></option>
<?
                                }
?>
                        </select>
                        <script>$(document).ready(function() {$('#men_idParent').niceSelect();});</script> 
<?
                        }
?>
                        </div>
                        <div id="" class="col-sm-4 pt-2 form_checks">
<?
                                $checked=''; if($oMenuItem["men_block"]==1) {$checked='checked="checked"';}
?>

                                        <div class="text-left" style="float:left;width:170px;">
                                        Nezobraziť:
                                        </div>
                                        <div class="" style="float:left;width:50px;">
                                        <input <?=$checked?> type="checkbox" id="men_block" name="men_block" data-toggle="tooltip"  title="Položka menu sa bežným návštevníkom nezobrazí" />
                                        </div>
                        </div>                
                </div>
                <div class="row mt-1 ">
                        <div id="men_idParent_title"  class="col-sm-2 pt-1 text-left">
                                Šablóna:&nbsp;
                        </div>
                        <div class="col-sm-6 " title="Vybrať šalónu ak je k diispozícii viac možností" data-toggle="tooltip"  >
                        <select alt="N~0" style="width:100%;" onchange="" id="men_template" name="men_template" class="nice-select " >
<?
                                foreach($_SESSION["ALL_TEMPLATES"] as $aTemplate) {
?>
                                <option <?=$selected?> value="<?=$aTemplate["tmp_id"]?>"><?=$aTemplate["tmp_tempName"]?></option>
<?
                                }
?>
                        </select>
                        <script>$(document).ready(function() {$('#men_template').niceSelect();});</script> 
                        </div>
                        <div id="" class="col-sm-4 pt-2 form_checks">
<?
                                $checked=''; if($oMenuItem["men_lockArea"]==1) {$checked='checked="checked"';}
?>
                                        <div class="text-left" style="float:left;width:170px;">
                                        Uzamknúť:
                                        </div>
                                        <div class="" style="float:left;width:50px;">
                                        <input <?=$checked?> type="checkbox" id="men_lockArea" name="men_lockArea" data-toggle="tooltip"  title="Položka menu bude neprístupná, alebo časť jej obsahu platený" />
                                        </div>

                        </div>                
                </div>
        
        <div id="approvalDesk_formMenuItem" class="text-center pt-1 mt-3" style="display:none;width:100%;border-top:dotted 1px #fff;">
                Údaje ku účtu som riadne skontroloval:
                <input type="checkbox" class="form-check-input" style="margin-left:25px;" id="approval_formMenuItem" />
        </div>  
        
        <div class="mt-5 " style="width:100%;">  
                <div class="progress ">
                        <div id="progress_formMenuItem" class="progress-bar text-white text-bold text-center progressNote" style="width:0%;font-size:12px;">
                                0%        
                        </div>
                </div>
        </div>


        
        <div class="container text-center buttonDesk" >
<?
                if($_POST["newMenuInParent_contAdmin"] > 0) {
?>
                <input type="button" alt="captcha" id="save_formMenuItem" disabled onclick="hiddenSet('setNewMenu_formMenuItem', '<?=$_POST["newMenuInParent_contAdmin"]?>', true)" class="btn btn-primary" value="Zapísať novú položku menu" />
<?
                } else {
?>
                <input type="button" alt="captcha" id="save_formMenuItem" disabled onclick="hiddenSet('setSave_formMenuItem', '<?=$_POST["idEditMenu_contAdmin"]?>', true)" class="btn btn-primary" value="Zapísať" />
                <input type="button" id="save_formMenuItemDis" disabled onclick="" class="btn btn-primary" value="Zapísať" />
<?
                }
                
                if($_POST["newMenuInParent_contAdmin"]>0) {
                        $returnVal = $_POST["newMenuInParent_contAdmin"];
                } else {
                        $returnVal = $oMenuItem["men_idParent"];
                }
?>
                <input type="button" title="Návrat k položkám menu bez zápisu zmien." data-toggle="tooltip" alt="captcha" id="return_formMenuItem" onclick="hiddenSet('returnOnly_formMenuItem', '<?=$returnVal?>', true)" class="btn btn-success" value="Návrat bez ďalšieho zápisu" />
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
        <input type="hidden" id="inspect_formMenuItem" name="inspect_formMenuItem" value="men~#cc0000~green~~#cc0000~12~arial~bold~i" />
        <input type="hidden" id="setSave_formMenuItem" name="setSave_formMenuItem" value="" />
        <input type="hidden" id="setNewMenu_formMenuItem" name="setNewMenu_formMenuItem" value="" />
        <input type="hidden" id="returnOnly_formMenuItem" name="returnOnly_formMenuItem" value="" />
        
<?
        if(strLen(Trim($_POST["idEditMenu_contAdmin"])) > 0) {
                $_POST["idEditMenu_formMenuItem"] = $_POST["idEditMenu_contAdmin"];
        }
        
        if(strLen(Trim($_POST["newMenuInParent_contAdmin"])) > 0) {
                $_POST["idEditMenu_formMenuItem"] = $_POST["idEditMenu_contAdmin"];
        }
        
?>
        <input type="hidden" id="idEditMenu_formMenuItem" name="idEditMenu_formMenuItem" value="<?=$_POST["idEditMenu_formMenuItem"]?>" />          
        </div>
</form>
        
        <!----------------------->
<?
        if($oMenuItem["men_idParent"] > 0) {
        $oContent = $db->get("CONTENT", "contentSites", null, "con_idMenu='".$oMenuItem["men_id"]."'", true, __FILE__, __LINE__, true);
        $aAnnexesTemp = explode("|", $annexesVideo);
        $aAnnexesVideo = array();
        $comma = $annexes = "";
        foreach($aAnnexesTemp as $annex) {
                 $annexes .= $comma.$annex;
                 $aAnnexesVideo[] =  $annex;
                 $comma = ", ";
        }
         $aAnnexesImage = explode("|", $annexesImage);
         foreach($aAnnexesImage as $annex) {
                 $annexes .= $comma.$annex;
                 $aAnnexesVideo[] =  $annex;
                 $comma = ", ";
         }

?>
<form id="formUpload" action="" method="post" enctype="multipart/form-data">

        <div id="con_path_title"  class="container mt-4 pt-1 text-center formMenuHead">
                Nahratie prezentačnej prílohy
        </div>
        <div class="container custom-file mb-2 pl-0 " >
                <input type="file" class="custom-file-input"  id="fileToUpload" name="fileToUpload" style="cursor:pointer;" />
                <div id="fileToUpload_err" class="form_error"></div>
                <input type="hidden" id="uploadMax" name="uploadMax" value="<?=$uploadMax?>" />
                <input type="hidden" id="videoPathSize" name="videoPathSize" value="<?=folderSize($videoFolder)?>" />
                <input type="hidden" id="maxContent" name="maxContent" value="<?=$maxContent?>" />
                
                
                <script>
                $('#fileToUpload').bind('change', function() {
                        var pathSize = $("#videoPathSize").val();
                        var maxSize  = $("#maxContent").val();
                        var addCapacity = (parseInt(pathSize)+parseInt(this.files[0].size));
                        if((addCapacity>parseInt(maxSize))) {
                                $("#fileToUpload_err").html('<span style="color:#cc0000;">Nahratie videa nad max.limit servera (' + maxSize + ' b) !</span>');
                                $('#fileToUpload').val(null);
                        } else {
                                var aAnnex = $("#annexesVideo").val().toLowerCase().split("|");
                                var ending = $("#fileToUpload").val().toLowerCase().substr($("#fileToUpload").val().indexOf(".")+1,$("#fileToUpload").val().length);
                                $("#fileToUpload_err").html("");
                                if(this.files[0].size>$("#uploadMax").val()) {
                                        $("#fileToUpload_err").html('<span style="color:#cc0000;">Príliš veľká príloha (max <?=$uploadMax/1000000?>Mb), použi FTP !</span>');
                                        $('#fileToUpload').val(null);
                                } else {$("#uploadCurrent").val(1);}
                                var format=false; for(var i=0;i<(aAnnex.length-1);++i) {if(aAnnex[i]==ending) {format=!format;break;}}
                                if(!format) {
                                        $("#fileToUpload_err").html('<span style="color:#cc0000;">Formát prílohy = ' + ending + ' = nie je povolený !</span>');
                                        $('#fileToUpload').val(null);
                                } else {$("#uploadCurrent").val(1);}
                        }
                });
                $(".custom-file-input").on("change", function() {
                        var fileName = $(this).val().split("\\").pop();
                        $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
                });
                </script> 
                <label class="custom-file-label" id="videoFormat" style="width:99%;text-align:left;font-size:15px;" for="customFile">(<?=$annexes?>)</label>
                <script>
                        if($(window).width()<=800) {$("#videoFormat").html('Len video/obrázok formát !');}
                </script>
                <div class="mt-1 " style="width:100%;">  
                        <div class="progress ">
                                <div id="progress_videoUpload" class="progress-bar text-white text-bold text-center progressNote" style="width:0%;font-size:12px;">
                                        0%        
                                </div>
                        </div>
                </div>
                <div style="float:left;width:97%;">
                        <input type="text" readonly="readonly" style="width:100%;" class="form-control text-dark mt-1 mb-0" id="con_videoName" name="con_videoName" placeholder="Príloha nie je nahratá" value="<?=$oContent["con_videoName"]?>" />
                </div>
<?
                if(strLen(trim($oContent["con_videoName"]))>0) {
?>
                <div id="trashVideo" class="text-center pt-2" style="float:left;width:3%;color:#cc0000;">
                        <a data-toggle="modal" data-target="#delContent"  onclick="hiddenSet('delete_formUpload', '<?=$oMenuItem["men_id"]?>')" style="cursor:pointer;">
                                <i data-placement="bottom" title="Vymazať aktuálne nahratú prílohu." data-toggle="tooltip" class="fas fa-trash-alt"></i>
                        </a>
                </div>
                <div id="trashVideoDis" class="text-center pt-2" style="float:left;width:3%;color:#cc0000;">
                        <a data-toggle="modal" data-target="#demoversion"  onclick="" style="cursor:pointer;">
                                <i data-placement="bottom" title="Vymazať aktuálne nahraté video." data-toggle="tooltip" class="fas fa-trash-alt"></i>
                        </a>
                </div>
<?
                }
?>                 
                <div style="float:left;width:97%;">
                        <input type="text" title="Ak je video uložené mimo priestoru pre tento systém, cestu zadajte sem" data-toggle="tooltip" id="con_path" name="con_path" alt="URL~0" class="form-control text-dark mt-1 mb-0" placeholder="Cesta k existujúcej prílohe" value="<?=$oContent["con_path"]?>" autocomplete="off" />
                        <script>
                        $("#con_path").keyup(function() {
                                var isVideo   = $("#con_videoName").val().length;
                                var isValue = $("#con_path").val().length;
                                if(isVideo>0 && isValue==0) {$("#insertPath").css("display", "inline")} else {$("#insertPath").css("display","none")} 
                        })
                        function videoInsert() {
                                $("#con_path").val($("#pathVideo").val() + "/" + $("#con_videoName").val());
                        }
                        </script>
                        <div id="con_path_err" class="form_error" style=""clear:both;></div>
                </div>
<?                                                                                    
                $display = 'display:none;'; 
                if(strLen(trim($oContent["con_path"]))==0 && strLen(trim($oContent["con_videoName"]))>0) {
                        $display = 'display:block;'; 
                }
?>
                <div id="insertPath" class="text-center pt-2" style="float:left;width:3%;color:#009900;<?=$display?>">
                        <a data-toggle="tooltip" title="Vložiť cestu ku aktuálnemu videu." onclick="videoInsert()" style="cursor:pointer;">
                                <i class="far fa-file-video"></i>
                        </a>
                </div>
                
                <div class="container mt-2" style="clear:both;">
<?
                $lcEditorContent = "";
                $lcEditorName = "con_note";    
                $lcScript = "lc-scripts/scripts_".$oMenuItem["men_id"].".html";
                if(is_File($lcScript)) {
                        $handle = fopen($lcScript, "r+");
                        $lcEditorContent = fread($handle, filesize($lcScript));
                        fclose($handle);
                }
                include("line-control/wysiwyg.php"); 
?>
                </div> 
                
                <div class="mt-2 " style="clear:both;width:100%;">  
                        <div class="progress ">
                                <div id="progress_formUpload" class="progress-bar text-white text-bold text-center progressNote" style="width:0%;font-size:12px;">
                                        0%        
                                </div>
                        </div>
                </div>

                <div class="mt-0 buttonDesk">
                        
                        <button type="submit" data-toggle="tooltip" title="Nahrať prílohu, ostatný obsah, alebo nahrať oboje" id="save_formUpload" class="btn btn-primary" onclick="hiddenSet('saveVideo_formUpload', '<?=$oMenuItem["men_id"]?>', true)" >Nahrať prílohu alebo cestu ku prílohe</button>
                        <button type="submit" data-toggle="tooltip" title="Pripnúť k tomuto obsahu prílohu nahratú k systému cez FTP" id="ftp_formUpload" class="btn btn-warning" onclick="hiddenSet('setFTPVideo_formUpload', '<?=$oMenuItem["men_id"]?>', true)" >Pripnúť FTP prílohu</button>

                        <span id="save_formUploadDis"  data-toggle="modal" data-target="#demoversion" >
                        <button data-placement="bottom" data-toggle="tooltip" title="Nahrať prílohu, ostatný obsah, alebo nahrať oboje"  type="button" class="btn btn-primary" onclick="" >Nahrať prílohu alebo cestu ku prílohe</button>
                        </span>
                        <span id="ftp_formUploadDis" data-toggle="modal" data-target="#demoversion" >
                        <button data-placement="bottom" data-toggle="tooltip" title="Pripnúť k tomuto obsahu prílohu nahratú k systému cez FTP"  type="button" data-toggle="modal" data-target="#demoversion" class="btn btn-warning" onclick="" >Pripnúť FTP prílohu</button>
                        </span>

                        <!--<button type="button" id="refresh_formUpload" class="btn btn-danger" onclick="resetFileTag()" >Reset a refresh</button>-->
                </div> 
                <input type="hidden" id="saveVideo_formUpload" name="saveVideo_formUpload" value="" />
                <input type="hidden" id="setFTPVideo_formUpload" name="setFTPVideo_formUpload" value="" />
                
                        <!--    prefix id
                        
                                err msg color ~ 
                                ok msg color ~ 
                                err title backgroundColor ~ 
                                err title text color ~ 
                                size error msg
                                font error msg
                                weight error msg
                                other tag (italic) -->
                <input type="hidden" id="inspect_formUpload" name="inspect_formUpload" value="con~#cc0000~green~~#cc0000~12~arial~bold~i" />
                <input type="hidden" id="pathVideo" name="pathVideo" value="<?=$_SESSION["DOMAIN_PATH_VIDEO"]?>" />
<?
                if(strLen(Trim($_POST["idEditMenu_contAdmin"])) > 0) {
                        $_POST["idEditMenu_formUpload"] = $_POST["idEditMenu_contAdmin"];
                }
?>
                <input type="hidden" id="idEditMenu_formUpload" name="idEditMenu_formUpload" value="<?=$_POST["idEditMenu_formUpload"]?>" />
                <input type="hidden" id="delete_formUpload" name="delete_formUpload" value="" />
                <input type="hidden" id="uploadCurrent" name="uploadCurrent" value="" />
                <input type="hidden" id="annexesVideo" name="annexesVideo" value="<?=$annexesVideo."|".$annexesImage?>" />
        </div>
        <script>function resetFileTag() {$("#fileToUpload").val(null);$('#formUpload').submit();}</script>
</form>
        
        <form id="formAccess" method="post" action="">
<?
        $oRightUser     = $db->get("TEMPO", "menuRightsAccess", null, "rig_idMenu='".$oMenuItem["men_id"]."'", true, __FILE__, __LINE__);
        
        $function = "alert('Aktuálne len demo verzia !')"; if(strLen(Trim($demoVesion))==0) {$function = "submit()"; }
?>
        <div class="container mt-4 pt-1 formMenuHead">
                Prístupy k položke:&nbsp;<span class="formBold"><?=$oMenuItem["men_itemName"]?></span> 
        </div>
        <div class="container mt-2 d-flex justify-content-center ">
                <div style="width:250px;" data-toggle="tooltip" title="Povoliť prístup k položke menu ďalšiemu užívateľovi" >
                <select id="noAccess_formAccess" name="noAccess_formAccess" style="width:400px;" onchange="<?=$function?>">
                        <option value=""></option>
<?
                        $oActiveRight = array();
                        foreach($_SESSION["USER_ALL_ACCOUNT"] as $aAccount) {
                                $oFind = $sys->arrayIn($_SESSION["GLOBAL_ACCESSRIGHT"], '$rig_idUser=="'.$aAccount["acc_id"].'" && $rig_idMenu=="'.$oMenuItem["men_id"].'"', true);
                                if(count($oFind)==0) {
?>
                                <option value="<?=$aAccount["acc_id"]?>"><?=$aAccount["acc_surName"]." ".$aAccount["acc_name"]?></option>
<?
                                } else {$oActiveRight[] = $oFind;}
                        }
?>
                </select>
                </div>
                <script>$(document).ready(function() {$('#noAccess_formAccess').niceSelect();});</script>
        </div>
        <div class="container mt-2 ">
                <div class="row mt-1">
<?
                $comma = "";
                $where = "0 ";
                foreach($oActiveRight as $aActiveRight) {
                        if(strLen(Trim($comma))==0) {$where=$comma;}
                        $where .= $comma." acc_id='".$aActiveRight["rig_idUser"]."'";
                        $comma = " || ";
                }
                $oUserRight = $db->get("TESTER", "sysUserAccounts", array("acc_id", "acc_name", "acc_surName"), $where, true, __FILE__, __LINE__, false);
                $line = $subIndex = 1;
                foreach($oUserRight as $aUserRight) {
                        $style = ''; if(($line%2)==0) $style = 'background-color:#A8A8A8;';
?>        
                        <div class="col-sm-4 pt-1 mt-2 text-left tableDelete userRight $even" style="<?=$style?>">
                                <a  class="delPrivileg" data-toggle="modal" data-target="#delRight" onclick="hiddenSet('setDelAcc_formAccess', <?=$aUserRight["acc_id"]?>)"  >
                                        <i data-placement="bottom" title="Vymazať prístup užívateľa k tejto položke menu." data-toggle="tooltip"  class="fas fa-user-slash"></i>
                                </a>
                                </span>
                                <span class="delPrivilegDis" data-toggle="modal" data-target="#demoversion">
                                        <i data-placement="bottom" title="Vymazať prístup užívateľa k tejto položke menu." data-toggle="tooltip"  class="fas fa-user-slash"></i>
                                </span>
                                <a href="<?=$personPath."?id=".$aUserRight["acc_id"]?>" style="color:#3276B1;">
                                        <?=$aUserRight["acc_surName"]."&nbsp;".$aUserRight["acc_name"]?>&nbsp;
                                </a>

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
                        ++$line;
                }
                if($subIndex < 4) {
?>
                        </div>
<?                
                }  
?>        
        </div>
        
        <script>
        if($(window).width()<=610 && $(window).width()>480) {
                //_dx($(window).width());
                $("div.userRight").css("font-size", "12px");
        } else {
                $("div.userRight").css("font-size", "15px");        
        }
        </script>
<?
        
        if(strLen(Trim($_POST["idEditMenu_contAdmin"])) > 0) {
                $_POST["idEditMenu_formAccess"] = $_POST["idEditMenu_contAdmin"];
        }
?>

        <input type="hidden" id="setDelAcc_formAccess" name="setDelAcc_formAccess" value />
        <input type="hidden" id="idEditMenu_formAccess"  name="idEditMenu_formAccess" value="<?=$_POST["idEditMenu_contAdmin"]?>" />
        </form>
<?
        }
?>
        
</div>

<?
}
?>




<script>
(function(e){"use strict";if(typeof define==="function"&&define.amd){define(["jquery"],e)}else{e(typeof jQuery!="undefined"?jQuery:window.Zepto)}})(function(e){"use strict";function r(t){var n=t.data;if(!t.isDefaultPrevented()){t.preventDefault();e(t.target).ajaxSubmit(n)}}function i(t){var n=t.target;var r=e(n);if(!r.is("[type=submit],[type=image]")){var i=r.closest("[type=submit]");if(i.length===0){return}n=i[0]}var s=this;s.clk=n;if(n.type=="image"){if(t.offsetX!==undefined){s.clk_x=t.offsetX;s.clk_y=t.offsetY}else if(typeof e.fn.offset=="function"){var o=r.offset();s.clk_x=t.pageX-o.left;s.clk_y=t.pageY-o.top}else{s.clk_x=t.pageX-n.offsetLeft;s.clk_y=t.pageY-n.offsetTop}}setTimeout(function(){s.clk=s.clk_x=s.clk_y=null},100)}function s(){if(!e.fn.ajaxSubmit.debug){return}var t="[jquery.form] "+Array.prototype.join.call(arguments,"");if(window.console&&window.console.log){window.console.log(t)}else if(window.opera&&window.opera.postError){window.opera.postError(t)}}var t={};t.fileapi=e("<input type='file'/>").get(0).files!==undefined;t.formdata=window.FormData!==undefined;var n=!!e.fn.prop;e.fn.attr2=function(){if(!n){return this.attr.apply(this,arguments)}var e=this.prop.apply(this,arguments);if(e&&e.jquery||typeof e==="string"){return e}return this.attr.apply(this,arguments)};e.fn.ajaxSubmit=function(r){function k(t){var n=e.param(t,r.traditional).split("&");var i=n.length;var s=[];var o,u;for(o=0;o<i;o++){n[o]=n[o].replace(/\+/g," ");u=n[o].split("=");s.push([decodeURIComponent(u[0]),decodeURIComponent(u[1])])}return s}function L(t){var n=new FormData;for(var s=0;s<t.length;s++){n.append(t[s].name,t[s].value)}if(r.extraData){var o=k(r.extraData);for(s=0;s<o.length;s++){if(o[s]){n.append(o[s][0],o[s][1])}}}r.data=null;var u=e.extend(true,{},e.ajaxSettings,r,{contentType:false,processData:false,cache:false,type:i||"POST"});if(r.uploadProgress){u.xhr=function(){var t=e.ajaxSettings.xhr();if(t.upload){t.upload.addEventListener("progress",function(e){var t=0;var n=e.loaded||e.position;var i=e.total;if(e.lengthComputable){t=Math.ceil(n/i*100)}r.uploadProgress(e,n,i,t)},false)}return t}}u.data=null;var a=u.beforeSend;u.beforeSend=function(e,t){if(r.formData){t.data=r.formData}else{t.data=n}if(a){a.call(this,e,t)}};return e.ajax(u)}function A(t){function T(e){var t=null;try{if(e.contentWindow){t=e.contentWindow.document}}catch(n){s("cannot get iframe.contentWindow document: "+n)}if(t){return t}try{t=e.contentDocument?e.contentDocument:e.document}catch(n){s("cannot get iframe.contentDocument: "+n);t=e.document}return t}function k(){function f(){try{var e=T(v).readyState;s("state = "+e);if(e&&e.toLowerCase()=="uninitialized"){setTimeout(f,50)}}catch(t){s("Server abort: ",t," (",t.name,")");_(x);if(w){clearTimeout(w)}w=undefined}}var t=a.attr2("target"),n=a.attr2("action"),r="multipart/form-data",u=a.attr("enctype")||a.attr("encoding")||r;o.setAttribute("target",p);if(!i||/post/i.test(i)){o.setAttribute("method","POST")}if(n!=l.url){o.setAttribute("action",l.url)}if(!l.skipEncodingOverride&&(!i||/post/i.test(i))){a.attr({encoding:"multipart/form-data",enctype:"multipart/form-data"})}if(l.timeout){w=setTimeout(function(){b=true;_(S)},l.timeout)}var c=[];try{if(l.extraData){for(var h in l.extraData){if(l.extraData.hasOwnProperty(h)){if(e.isPlainObject(l.extraData[h])&&l.extraData[h].hasOwnProperty("name")&&l.extraData[h].hasOwnProperty("value")){c.push(e('<input type="hidden" name="'+l.extraData[h].name+'">').val(l.extraData[h].value).appendTo(o)[0])}else{c.push(e('<input type="hidden" name="'+h+'">').val(l.extraData[h]).appendTo(o)[0])}}}}if(!l.iframeTarget){d.appendTo("body")}if(v.attachEvent){v.attachEvent("onload",_)}else{v.addEventListener("load",_,false)}setTimeout(f,15);try{o.submit()}catch(m){var g=document.createElement("form").submit;g.apply(o)}}finally{o.setAttribute("action",n);o.setAttribute("enctype",u);if(t){o.setAttribute("target",t)}else{a.removeAttr("target")}e(c).remove()}}function _(t){if(m.aborted||M){return}A=T(v);if(!A){s("cannot access response document");t=x}if(t===S&&m){m.abort("timeout");E.reject(m,"timeout");return}else if(t==x&&m){m.abort("server abort");E.reject(m,"error","server abort");return}if(!A||A.location.href==l.iframeSrc){if(!b){return}}if(v.detachEvent){v.detachEvent("onload",_)}else{v.removeEventListener("load",_,false)}var n="success",r;try{if(b){throw"timeout"}var i=l.dataType=="xml"||A.XMLDocument||e.isXMLDoc(A);s("isXml="+i);if(!i&&window.opera&&(A.body===null||!A.body.innerHTML)){if(--O){s("requeing onLoad callback, DOM not available");setTimeout(_,250);return}}var o=A.body?A.body:A.documentElement;m.responseText=o?o.innerHTML:null;m.responseXML=A.XMLDocument?A.XMLDocument:A;if(i){l.dataType="xml"}m.getResponseHeader=function(e){var t={"content-type":l.dataType};return t[e.toLowerCase()]};if(o){m.status=Number(o.getAttribute("status"))||m.status;m.statusText=o.getAttribute("statusText")||m.statusText}var u=(l.dataType||"").toLowerCase();var a=/(json|script|text)/.test(u);if(a||l.textarea){var f=A.getElementsByTagName("textarea")[0];if(f){m.responseText=f.value;m.status=Number(f.getAttribute("status"))||m.status;m.statusText=f.getAttribute("statusText")||m.statusText}else if(a){var c=A.getElementsByTagName("pre")[0];var p=A.getElementsByTagName("body")[0];if(c){m.responseText=c.textContent?c.textContent:c.innerText}else if(p){m.responseText=p.textContent?p.textContent:p.innerText}}}else if(u=="xml"&&!m.responseXML&&m.responseText){m.responseXML=D(m.responseText)}try{L=H(m,u,l)}catch(g){n="parsererror";m.error=r=g||n}}catch(g){s("error caught: ",g);n="error";m.error=r=g||n}if(m.aborted){s("upload aborted");n=null}if(m.status){n=m.status>=200&&m.status<300||m.status===304?"success":"error"}if(n==="success"){if(l.success){l.success.call(l.context,L,"success",m)}E.resolve(m.responseText,"success",m);if(h){e.event.trigger("ajaxSuccess",[m,l])}}else if(n){if(r===undefined){r=m.statusText}if(l.error){l.error.call(l.context,m,n,r)}E.reject(m,"error",r);if(h){e.event.trigger("ajaxError",[m,l,r])}}if(h){e.event.trigger("ajaxComplete",[m,l])}if(h&&!--e.active){e.event.trigger("ajaxStop")}if(l.complete){l.complete.call(l.context,m,n)}M=true;if(l.timeout){clearTimeout(w)}setTimeout(function(){if(!l.iframeTarget){d.remove()}else{d.attr("src",l.iframeSrc)}m.responseXML=null},100)}var o=a[0],u,f,l,h,p,d,v,m,g,y,b,w;var E=e.Deferred();E.abort=function(e){m.abort(e)};if(t){for(f=0;f<c.length;f++){u=e(c[f]);if(n){u.prop("disabled",false)}else{u.removeAttr("disabled")}}}l=e.extend(true,{},e.ajaxSettings,r);l.context=l.context||l;p="jqFormIO"+(new Date).getTime();if(l.iframeTarget){d=e(l.iframeTarget);y=d.attr2("name");if(!y){d.attr2("name",p)}else{p=y}}else{d=e('<iframe name="'+p+'" src="'+l.iframeSrc+'" />');d.css({position:"absolute",top:"-1000px",left:"-1000px"})}v=d[0];m={aborted:0,responseText:null,responseXML:null,status:0,statusText:"n/a",getAllResponseHeaders:function(){},getResponseHeader:function(){},setRequestHeader:function(){},abort:function(t){var n=t==="timeout"?"timeout":"aborted";s("aborting upload... "+n);this.aborted=1;try{if(v.contentWindow.document.execCommand){v.contentWindow.document.execCommand("Stop")}}catch(r){}d.attr("src",l.iframeSrc);m.error=n;if(l.error){l.error.call(l.context,m,n,t)}if(h){e.event.trigger("ajaxError",[m,l,n])}if(l.complete){l.complete.call(l.context,m,n)}}};h=l.global;if(h&&0===e.active++){e.event.trigger("ajaxStart")}if(h){e.event.trigger("ajaxSend",[m,l])}if(l.beforeSend&&l.beforeSend.call(l.context,m,l)===false){if(l.global){e.active--}E.reject();return E}if(m.aborted){E.reject();return E}g=o.clk;if(g){y=g.name;if(y&&!g.disabled){l.extraData=l.extraData||{};l.extraData[y]=g.value;if(g.type=="image"){l.extraData[y+".x"]=o.clk_x;l.extraData[y+".y"]=o.clk_y}}}var S=1;var x=2;var N=e("meta[name=csrf-token]").attr("content");var C=e("meta[name=csrf-param]").attr("content");if(C&&N){l.extraData=l.extraData||{};l.extraData[C]=N}if(l.forceSync){k()}else{setTimeout(k,10)}var L,A,O=50,M;var D=e.parseXML||function(e,t){if(window.ActiveXObject){t=new ActiveXObject("Microsoft.XMLDOM");t.async="false";t.loadXML(e)}else{t=(new DOMParser).parseFromString(e,"text/xml")}return t&&t.documentElement&&t.documentElement.nodeName!="parsererror"?t:null};var P=e.parseJSON||function(e){return window["eval"]("("+e+")")};var H=function(t,n,r){var i=t.getResponseHeader("content-type")||"",s=n==="xml"||!n&&i.indexOf("xml")>=0,o=s?t.responseXML:t.responseText;if(s&&o.documentElement.nodeName==="parsererror"){if(e.error){e.error("parsererror")}}if(r&&r.dataFilter){o=r.dataFilter(o,n)}if(typeof o==="string"){if(n==="json"||!n&&i.indexOf("json")>=0){o=P(o)}else if(n==="script"||!n&&i.indexOf("javascript")>=0){e.globalEval(o)}}return o};return E}if(!this.length){s("ajaxSubmit: skipping submit process - no element selected");return this}var i,o,u,a=this;if(typeof r=="function"){r={success:r}}else if(r===undefined){r={}}i=r.type||this.attr2("method");o=r.url||this.attr2("action");u=typeof o==="string"?e.trim(o):"";u=u||window.location.href||"";if(u){u=(u.match(/^([^#]+)/)||[])[1]}r=e.extend(true,{url:u,success:e.ajaxSettings.success,type:i||e.ajaxSettings.type,iframeSrc:/^https/i.test(window.location.href||"")?"javascript:false":"about:blank"},r);var f={};this.trigger("form-pre-serialize",[this,r,f]);if(f.veto){s("ajaxSubmit: submit vetoed via form-pre-serialize trigger");return this}if(r.beforeSerialize&&r.beforeSerialize(this,r)===false){s("ajaxSubmit: submit aborted via beforeSerialize callback");return this}var l=r.traditional;if(l===undefined){l=e.ajaxSettings.traditional}var c=[];var h,p=this.formToArray(r.semantic,c);if(r.data){r.extraData=r.data;h=e.param(r.data,l)}if(r.beforeSubmit&&r.beforeSubmit(p,this,r)===false){s("ajaxSubmit: submit aborted via beforeSubmit callback");return this}this.trigger("form-submit-validate",[p,this,r,f]);if(f.veto){s("ajaxSubmit: submit vetoed via form-submit-validate trigger");return this}var d=e.param(p,l);if(h){d=d?d+"&"+h:h}if(r.type.toUpperCase()=="GET"){r.url+=(r.url.indexOf("?")>=0?"&":"?")+d;r.data=null}else{r.data=d}var v=[];if(r.resetForm){v.push(function(){a.resetForm()})}if(r.clearForm){v.push(function(){a.clearForm(r.includeHidden)})}if(!r.dataType&&r.target){var m=r.success||function(){};v.push(function(t){var n=r.replaceTarget?"replaceWith":"html";e(r.target)[n](t).each(m,arguments)})}else if(r.success){v.push(r.success)}r.success=function(e,t,n){var i=r.context||this;for(var s=0,o=v.length;s<o;s++){v[s].apply(i,[e,t,n||a,a])}};if(r.error){var g=r.error;r.error=function(e,t,n){var i=r.context||this;g.apply(i,[e,t,n,a])}}if(r.complete){var y=r.complete;r.complete=function(e,t){var n=r.context||this;y.apply(n,[e,t,a])}}var b=e("input[type=file]:enabled",this).filter(function(){return e(this).val()!==""});var w=b.length>0;var E="multipart/form-data";var S=a.attr("enctype")==E||a.attr("encoding")==E;var x=t.fileapi&&t.formdata;s("fileAPI :"+x);var T=(w||S)&&!x;var N;if(r.iframe!==false&&(r.iframe||T)){if(r.closeKeepAlive){e.get(r.closeKeepAlive,function(){N=A(p)})}else{N=A(p)}}else if((w||S)&&x){N=L(p)}else{N=e.ajax(r)}a.removeData("jqxhr").data("jqxhr",N);for(var C=0;C<c.length;C++){c[C]=null}this.trigger("form-submit-notify",[this,r]);return this};e.fn.ajaxForm=function(t){t=t||{};t.delegation=t.delegation&&e.isFunction(e.fn.on);if(!t.delegation&&this.length===0){var n={s:this.selector,c:this.context};if(!e.isReady&&n.s){s("DOM not ready, queuing ajaxForm");e(function(){e(n.s,n.c).ajaxForm(t)});return this}s("terminating; zero elements found by selector"+(e.isReady?"":" (DOM not ready)"));return this}if(t.delegation){e(document).off("submit.form-plugin",this.selector,r).off("click.form-plugin",this.selector,i).on("submit.form-plugin",this.selector,t,r).on("click.form-plugin",this.selector,t,i);return this}return this.ajaxFormUnbind().bind("submit.form-plugin",t,r).bind("click.form-plugin",t,i)};e.fn.ajaxFormUnbind=function(){return this.unbind("submit.form-plugin click.form-plugin")};e.fn.formToArray=function(n,r){var i=[];if(this.length===0){return i}var s=this[0];var o=this.attr("id");var u=n?s.getElementsByTagName("*"):s.elements;var a;if(u&&!/MSIE [678]/.test(navigator.userAgent)){u=e(u).get()}if(o){a=e(':input[form="'+o+'"]').get();if(a.length){u=(u||[]).concat(a)}}if(!u||!u.length){return i}var f,l,c,h,p,d,v;for(f=0,d=u.length;f<d;f++){p=u[f];c=p.name;if(!c||p.disabled){continue}if(n&&s.clk&&p.type=="image"){if(s.clk==p){i.push({name:c,value:e(p).val(),type:p.type});i.push({name:c+".x",value:s.clk_x},{name:c+".y",value:s.clk_y})}continue}h=e.fieldValue(p,true);if(h&&h.constructor==Array){if(r){r.push(p)}for(l=0,v=h.length;l<v;l++){i.push({name:c,value:h[l]})}}else if(t.fileapi&&p.type=="file"){if(r){r.push(p)}var m=p.files;if(m.length){for(l=0;l<m.length;l++){i.push({name:c,value:m[l],type:p.type})}}else{i.push({name:c,value:"",type:p.type})}}else if(h!==null&&typeof h!="undefined"){if(r){r.push(p)}i.push({name:c,value:h,type:p.type,required:p.required})}}if(!n&&s.clk){var g=e(s.clk),y=g[0];c=y.name;if(c&&!y.disabled&&y.type=="image"){i.push({name:c,value:g.val()});i.push({name:c+".x",value:s.clk_x},{name:c+".y",value:s.clk_y})}}return i};e.fn.formSerialize=function(t){return e.param(this.formToArray(t))};e.fn.fieldSerialize=function(t){var n=[];this.each(function(){var r=this.name;if(!r){return}var i=e.fieldValue(this,t);if(i&&i.constructor==Array){for(var s=0,o=i.length;s<o;s++){n.push({name:r,value:i[s]})}}else if(i!==null&&typeof i!="undefined"){n.push({name:this.name,value:i})}});return e.param(n)};e.fn.fieldValue=function(t){for(var n=[],r=0,i=this.length;r<i;r++){var s=this[r];var o=e.fieldValue(s,t);if(o===null||typeof o=="undefined"||o.constructor==Array&&!o.length){continue}if(o.constructor==Array){e.merge(n,o)}else{n.push(o)}}return n};e.fieldValue=function(t,n){var r=t.name,i=t.type,s=t.tagName.toLowerCase();if(n===undefined){n=true}if(n&&(!r||t.disabled||i=="reset"||i=="button"||(i=="checkbox"||i=="radio")&&!t.checked||(i=="submit"||i=="image")&&t.form&&t.form.clk!=t||s=="select"&&t.selectedIndex==-1)){return null}if(s=="select"){var o=t.selectedIndex;if(o<0){return null}var u=[],a=t.options;var f=i=="select-one";var l=f?o+1:a.length;for(var c=f?o:0;c<l;c++){var h=a[c];if(h.selected){var p=h.value;if(!p){p=h.attributes&&h.attributes.value&&!h.attributes.value.specified?h.text:h.value}if(f){return p}u.push(p)}}return u}return e(t).val()};e.fn.clearForm=function(t){return this.each(function(){e("input,select,textarea",this).clearFields(t)})};e.fn.clearFields=e.fn.clearInputs=function(t){var n=/^(?:color|date|datetime|email|month|number|password|range|search|tel|text|time|url|week)$/i;return this.each(function(){var r=this.type,i=this.tagName.toLowerCase();if(n.test(r)||i=="textarea"){this.value=""}else if(r=="checkbox"||r=="radio"){this.checked=false}else if(i=="select"){this.selectedIndex=-1}else if(r=="file"){if(/MSIE/.test(navigator.userAgent)){e(this).replaceWith(e(this).clone(true))}else{e(this).val("")}}else if(t){if(t===true&&/hidden/.test(r)||typeof t=="string"&&e(this).is(t)){this.value=""}}})};e.fn.resetForm=function(){return this.each(function(){if(typeof this.reset=="function"||typeof this.reset=="object"&&!this.reset.nodeType){this.reset()}})};e.fn.enable=function(e){if(e===undefined){e=true}return this.each(function(){this.disabled=!e})};e.fn.selected=function(t){if(t===undefined){t=true}return this.each(function(){var n=this.type;if(n=="checkbox"||n=="radio"){this.checked=t}else if(this.tagName.toLowerCase()=="option"){var r=e(this).parent("select");if(t&&r[0]&&r[0].type=="select-one"){r.find("option").selected(false)}this.selected=t}})};e.fn.ajaxSubmit.debug=false})
</script>

<script type="text/javascript">
$(document).ready(function() { 
	 $('#formUpload').submit(function(e) {	
		if($('#fileToUpload').val()) {
			e.preventDefault();
			$(this).ajaxSubmit({ 
				//target:   '#targetLayer', 
				beforeSubmit: function() {
				  $("#progress_formMenuItemr").width('0%');
                  if($(window).width()<=800) {
                        $("#fileToUpload_err").html('<span style="color:#428BCA;">Chvíľu to potrvá</span>');
                  } else {
                        $("#fileToUpload_err").html('<span style="color:#428BCA;">Trochu strpenia, môže to chvíľu trvať</span>');
                  }
                  
				},
				uploadProgress: function (event, position, total, percentComplete){	
					$("#progress_videoUpload").width(percentComplete + '%');
					$("#progress_videoUpload").html('<div id="progress-status">' + percentComplete +' %</div>')
                    
				},
				success:function (){
                    $('#formUpload').submit();
                    return(false);
				},
				resetForm: true 
			}); 
			return false; 
		}
	});
}); 
</script>

<script>
        $(document).ready(function() {  
                var demoVersion = '<?=$demoVesion?>';
                if(demoVersion.length>0) {
                        $('#newMenu_contAdminDis').show();
                        $('#newMenu_contAdmin').hide();
                        $('#save_formMenuItemDis').show();
                        $('#save_formMenuItem').hide();
                        $("#save_formUploadDis").show();
                        $("#save_formUpload").hide();
                        $("#ftp_formUploadDis").show();
                        $("#ftp_formUpload").hide();
                        $(".delPrivilegDis").show();
                        $(".delPrivileg").hide();
                        $("#trashVideoDis").show();
                        $("#trashVideo").hide();
                        $(".trashMenuIconDis").show();
                        $(".trashMenuIcon").hide();
                        $(".setUpItemDis").show();
                        $(".setUpItem").hide();
                } else {
                        $('#newMenu_contAdminDis').hide();
                        $('#newMenu_contAdmin').show();
                        $('#save_formMenuItemDis').hide();
                        $('#save_formMenuItem').show();
                        $("#save_formUploadDis").hide();
                        $("#save_formUpload").show();
                        $("#ftp_formUploadDis").hide();
                        $("#ftp_formUpload").show();
                        $(".delPrivilegDis").hide();
                        $(".delPrivileg").show();
                        $("#trashVideoDis").hide();
                        $("#trashVideo").show();
                        $(".trashMenuIconDis").hide();
                        $(".trashMenuIcon").show();
                        $(".setUpItemDis").hide();
                        $(".setUpItem").show();

                }
        });
</script>
