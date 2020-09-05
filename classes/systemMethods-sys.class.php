<?php

class sys extends db {
        
        function __construct() {
        
        }
        
        public function getAccessParent($menuId) {
                $menuId = intVal($menuId);
                if(getType($menuId)!="integer") {return(array());}
                if($menuId<1)                   {return(array());}

                if(getType($_SESSION["BUFFER_PARENT"])=="NULL") {$_SESSION["BUFFER_PARENT"] = array();}
                $oTester = $this->arrayIn($_SESSION["BUFFER_PARENT"], '$men_idParent=="'.$menuId.'"', true);

                if($_SESSION["SYS_ACCOUNT"]["acc_admin"]==1 || $_SESSION["SYS_ACCOUNT"]["acc_supervisor"]==1)  {
                        if(count($oTester)==0) {
                                $oTester = $this->get("TESTER","generalMenu", null, "men_idParent='".$menuId."' ORDER BY men_index", true, __FILE__, __LINE__, true);
                        }
                        if(count($oTester)>0) {return($oTester);}
                }
                if(count($oTester)>0) {return($oTester);}

                $aTables = array("menuRightsAccess", "generalMenu");
                $where   = "men_id=	rig_idMenu && rig_idUser='".$_SESSION["SYS_ACCOUNT"]["acc_id"]."' && men_idParent='".$menuId."' ORDER BY men_index";
                $oTester = $this->get("TESTER", $aTables, null, $where, true, __FILE__, __LINE__, true);

                $oBuffer = $_SESSION["BUFFER_PARENT"];
                $oBuffer[] = $oTester;
                $this->recordView($oBuffer[0]);
                $_SESSION["BUFFER_PARENT"] = $oBuffer;
                return($oTester);
        }
        
        public function _getRightForMenuItem($path="") {
        
                if(strLen(Trim($path))==0) {$path = $_SERVER["REQUEST_URI"];}
                $aUri = explode("/", subStr($path, 1, strLen($path)));
                
                //$_SESSION["ITEM_RIGHT"] = null;
                if(getType($_SESSION["ITEM_RIGHT"])=="NULL") {$_SESSION["ITEM_RIGHT"]=array();}
                $aReturnId = $this->arrayIn($_SESSION["ITEM_RIGHT"], '$parentPath=="'.$aUri[count($aUri)-2].'" && $childPath=="'.$aUri[count($aUri)-1].'"', true);
                $a = $_SESSION["ITEM_RIGHT"][0];
                if(count($aReturnId)>0)  {
                        return(array($aReturnId["parentId"], $aReturnId["childId"]));
                }
                if($_SESSION["SYS_ACCOUNT"]["acc_admin"] == 1)  {
                        $aParent = $this->arrayIn($_SESSION["GLOBAL_MENU"], '$men_path=="'.$aUri[count($aUri)-2].'"', true);
                        $aChild  = $this->arrayIn($_SESSION["GLOBAL_MENU"], '$men_path=="'.$aUri[count($aUri)-1].'"', true);
                        return(array($aParent["men_id"], $aChild["men_id"]));
                }
                $parentId = $childId = 0;
                $oRightItem = $this->get("TESTER", "generalMenu", "men_id", "men_path='".$aUri[count($aUri)-1]."'", true, __FILE__, __LINE__, true);
                $aExistRight = $this->arrayIn($_SESSION["ACCESSRIGHT_MENU"], '$rig_idMenu=="'.$oRightItem["men_id"].'"', true);
                if(count($aExistRight)>0) {$childId = $aExistRight["men_id"];}
                if(count($aUri)>1) {
                        $oRightParent = $this->get("TESTER", "generalMenu", null, "men_id='".$aExistRight["men_idParent"]."'", true, __FILE__, __LINE__, true);
                        if(count($oRightParent)>0) {$parentId = $oRightParent["men_id"];}
                }
                $aItemRight = $_SESSION["ITEM_RIGHT"];
                $aItemRight[] = array(  "parentId"=>$parentId, 
                                        "childId"=>$childId, 
                                        "parentPath"=>$aUri[count($aUri)-2], 
                                        "childPath"=>$aUri[count($aUri)-1]);
                $_SESSION["ITEM_RIGHT"] = $aItemRight;
                return(array($parentId, $childId));
        }
        
        public function pathFromMenu() {

                if($_SESSION["SYS_ACCOUNT"]["acc_admin"]!=1) {
                        $aMenuItem      = $this->arrayIn($_SESSION["ACCESSRIGHT_MENU"], '$rig_idMenu>0 && $men_idParent>0', true);
                        $aParentItem    = $this->arrayIn($_SESSION["GLOBAL_MENU"], '$men_id=="'.$aMenuItem["men_idParent"].'"', true);
                } else {
                        $aMenuItem      = $this->arrayIn($_SESSION["GLOBAL_MENU"], '$men_id>0 && $men_idParent>0', true);
                        $aParentItem    = $this->arrayIn($_SESSION["GLOBAL_MENU"], '$men_id=="'.$aMenuItem["men_idParent"].'"', true);
                }
                
                if(strLen(trim($aParentItem["men_path"]))==0 || strLen(Trim($aMenuItem["men_path"]))==0) {return(false);}
                return($aParentItem["men_path"]."/".$aMenuItem["men_path"]);
        }
        
        public function recordView($aRecord) {
                if(getType($aRecord)!="array")     {return(array());}
                if(count($aRecord)==0)             {return(array());}

                foreach(array_keys($aRecord) as $key) {
                        d($key.": ".$aRecord[$key]);
                }
                return($aRecord);
        }
        
        public function arrayView($oRecords) {
        
                if(getType($oRecords)!="array") {d("Parameter nie je dátový objekt !"); return(null);}
                foreach($oRecords as $aRecord) {
                        $comma = $record = "";
                        foreach(array_keys($aRecord) as $key) {$record .= $comma.$key." :: ".$aRecord[$key];$comma=", ";}
                        d($record);
                }
                return(true);
        }
        
        //arrayIn(
        public function arrayIn($oRecords, $condition="", $unique=false, $returnFields=false) {
                if(getType($oRecords)!="array")     {return(array());}
                if(count($oRecords)==0)             {return(array());}
                $aReturn = array();
                if($returnFields) {
                        foreach(array_keys($oRecords[0]) as $key) {
                                $aReturn[] = $key;
                        }
                        return($aReturn);
                } 
                if(getType($condition)!="string")   {return(array());}
                if(strLen(Trim($condition))<4)      {return(array());}
                $first = true;
                foreach($oRecords as $aRecord) {
                        foreach(array_keys($aRecord) as $key) {eval('$'.$key." = '".$aRecord[$key]."';");}
                        eval('$meeting = ('.$condition.');');
                        if($meeting) {
                                if($unique) {
                                        $aReturn = $aRecord;
                                        break;
                                } else {
                                        $aReturn[] = $aRecord;
                                }
                        }
                }
                return($aReturn);
        }
        

}
?>