<?php

if(getType($_SESSION["BUFFER_VISITORS"])=="NULL") {$_SESSION["BUFFER_VISITORS"] = array();}
$oMenuVisit = $sys->arrayIn($_SESSION["GLOBAL_MENU"], '$men_path=="'.$aURI[count($aURI)-1].'"', true);
if(count($oMenuVisit) > 0) {
        $oVisitTest = $sys->arrayIn($_SESSION["BUFFER_VISITORS"], '$sit_idMenu=="'.$oMenuVisit["men_id"].'" && $sit_path=="'.$aURI[count($aURI)-1].'"', true);
        if(count($oVisitTest)==0) {
                $aRecord = array(   "sit_idVisit"=>$_SESSION["VISIT_RECORD"], 
                                    "sit_idMenu"=>$oMenuVisit["men_id"], 
                                    "sit_path"=>$aURI[count($aURI)-1]);
                $aValues = array();
                $aValues[] = $aRecord; 
                $aNew = $db->insert("visitorInSite", $aValues, false, __FILE__, __LINE__, false);
                $bufferVisitors = $_SESSION["BUFFER_VISITORS"]; 
                $bufferVisitors[] = $aRecord;
                $_SESSION["BUFFER_VISITORS"] = $bufferVisitors;
        }
}

?>