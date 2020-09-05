<?php
class struct extends db {

        function __construct() {
        
        } 
        
        public function menuRightCreateStructureTable() {
                $aStruct    = array(); $tableName = "menuRightsAccess";
                $aStruct[]  = array(  "table"=>$tableName, "name"=>"rig_id",        "type"=>"bigint"        );
                $aStruct[]  = array(  "table"=>$tableName, "name"=>"rig_idUser",    "type"=>"int"           );
                $aStruct[]  = array(  "table"=>$tableName, "name"=>"rig_idMenu",    "type"=>"int"           );
                $this->drop($aStruct, false, __FILE__); $this->create($tableName, $aStruct, __FILE__);
        }
        
        public function templatesCreateStructureTable() {
                $aStruct    = array(); $tableName = "templates";
                $aStruct[]  = array(  "table"=>$tableName, "name"=>"tmp_id",            "type"=>"bigint"        );
                $aStruct[]  = array(  "table"=>$tableName, "name"=>"tmp_tempName",      "type"=>"varchar(50)"   );
                $aStruct[]  = array(  "table"=>$tableName, "name"=>"tmp_scriptName",    "type"=>"varchar(50)"   );
                $this->drop($aStruct, false, __FILE__); $this->create($tableName, $aStruct, __FILE__);
                $aValues    = array();
                $aValues[]  = array("tmp_tempName"=>"Video prezentácia",    "tmp_scriptName"=>"videoPlay");
                $aValues[]  = array("tmp_tempName"=>"Volanie podľa cesty",      "tmp_scriptName"=>"");
                $this->insert($aStruct, $aValues, true, __FILE__, __LINE__, true);                
        }
        
        public function generalMenuCreateStructureTable() {
                
                $aStruct    = array(); $tableName = "generalMenu";
                $aStruct[]  = array(  "table"=>$tableName, "name"=>"men_id",        "type"=>"bigint"        );
                $aStruct[]  = array(  "table"=>$tableName, "name"=>"men_index",     "type"=>"double"        );
                $aStruct[]  = array(  "table"=>$tableName, "name"=>"men_itemName",  "type"=>"varchar(50)"   );
                $aStruct[]  = array(  "table"=>$tableName, "name"=>"men_idParent",  "type"=>"int"           );
                $aStruct[]  = array(  "table"=>$tableName, "name"=>"men_path",      "type"=>"varchar(15)"   );
                $aStruct[]  = array(  "table"=>$tableName, "name"=>"men_template",  "type"=>"int"           );
                $aStruct[]  = array(  "table"=>$tableName, "name"=>"men_rights",    "type"=>"bool"          );
                $aStruct[]  = array(  "table"=>$tableName, "name"=>"men_block",     "type"=>"bool"          );
                $aStruct[]  = array(  "table"=>$tableName, "name"=>"men_lockArea",  "type"=>"bool"          );
                $aStruct[]  = array(  "table"=>$tableName, "name"=>"men_delete",    "type"=>"bool"          );
                $this->drop($aStruct, false, __FILE__); $this->create($tableName, $aStruct, __FILE__);
        }
        
        public function visitorInSiteCreateStructureTable() {
        
                $aStruct    = array(); $tableName = "visitorInSite";
                $aStruct[]  = array(  "table"=>$tableName, "name"=>"sit_id",        "type"=>"bigint"        );
                $aStruct[]  = array(  "table"=>$tableName, "name"=>"sit_idVisit",   "type"=>"bigint"        );
                $aStruct[]  = array(  "table"=>$tableName, "name"=>"sit_idMenu",    "type"=>"bigint"        );
                $aStruct[]  = array(  "table"=>$tableName, "name"=>"sit_path",      "type"=>"varchar(50)"   );
                $this->drop($aStruct, false, __FILE__); $this->create($tableName, $aStruct, __FILE__);
        }

        public function sysVisitorsInCreateStructureTable() {
        
                $aStruct    = array(); $tableName = "sysVisitorsIn";
                $aStruct[]  = array(  "table"=>$tableName, "name"=>"vis_id",        "type"=>"bigint"        );
                $aStruct[]  = array(  "table"=>$tableName, "name"=>"vis_dateTime",  "type"=>"dateTime"      );
                $aStruct[]  = array(  "table"=>$tableName, "name"=>"vis_ip",        "type"=>"varchar(20)"   );
                $aStruct[]  = array(  "table"=>$tableName, "name"=>"vis_addr",      "type"=>"varchar(100)"  );
                $aStruct[]  = array(  "table"=>$tableName, "name"=>"vis_idSysUser", "type"=>"int"           );
                $this->drop($aStruct, false, __FILE__); $this->create($tableName, $aStruct, __FILE__);
               
                /*
                $aValues    =   array();
                $aValues[]  = array("vis_dateTime"=>'2020-03-13 10:42:40', "vis_ip"=>'85.216.201.228', "vis_addr"=>'chello085216201228.chello.sk', "vis_idSysUser"=>'2');
                $aValues[]  = array("vis_dateTime"=>'2020-03-13 10:42:45', "vis_ip"=>'11.222.333.444', "vis_addr"=>'realsoft12346578.realsoft.sk', "vis_idSysUser"=>'1');
                $aValues[]  = array("vis_dateTime"=>'2020-03-13 10:42:50', "vis_ip"=>'85.216.201.228', "vis_addr"=>'chello085216201228.chello.sk', "vis_idSysUser"=>'2');
                $aValues[]  = array("vis_dateTime"=>'2020-03-13 10:42:55', "vis_ip"=>'11.222.333.444', "vis_addr"=>'realsoft12346578.realsoft.sk', "vis_idSysUser"=>'1');
                $aValues[]  = array("vis_dateTime"=>'2020-03-13 10:43:00', "vis_ip"=>'85.216.201.228', "vis_addr"=>'chello085216201228.chello.sk', "vis_idSysUser"=>'2');
                $aValues[]  = array("vis_dateTime"=>'2020-03-13 10:43:05', "vis_ip"=>'11.222.333.444', "vis_addr"=>'realsoft12346578.realsoft.sk', "vis_idSysUser"=>'1');
                $aValues[]  = array("vis_dateTime"=>'2020-03-13 10:43:10', "vis_ip"=>'85.216.201.228', "vis_addr"=>'chello085216201228.chello.sk', "vis_idSysUser"=>'2');
                $aValues[]  = array("vis_dateTime"=>'2020-03-13 10:43:15', "vis_ip"=>'11.222.333.444', "vis_addr"=>'realsoft12346578.realsoft.sk', "vis_idSysUser"=>'1');
                $aValues[]  = array("vis_dateTime"=>'2020-03-13 10:43:20', "vis_ip"=>'85.216.201.228', "vis_addr"=>'chello085216201228.chello.sk', "vis_idSysUser"=>'2');
                $aValues[]  = array("vis_dateTime"=>'2020-03-13 10:43:25', "vis_ip"=>'11.222.333.444', "vis_addr"=>'realsoft12346578.realsoft.sk', "vis_idSysUser"=>'1');
                $aValues[]  = array("vis_dateTime"=>'2020-03-13 10:43:30', "vis_ip"=>'85.216.201.228', "vis_addr"=>'chello085216201228.chello.sk', "vis_idSysUser"=>'2');
                $aValues[]  = array("vis_dateTime"=>'2020-03-13 10:43:35', "vis_ip"=>'11.222.333.444', "vis_addr"=>'realsoft12346578.realsoft.sk', "vis_idSysUser"=>'1');
                $aValues[]  = array("vis_dateTime"=>'2020-03-13 10:43:40', "vis_ip"=>'85.216.201.228', "vis_addr"=>'chello085216201228.chello.sk', "vis_idSysUser"=>'2');
                $aValues[]  = array("vis_dateTime"=>'2020-03-13 10:43:45', "vis_ip"=>'11.222.333.444', "vis_addr"=>'realsoft12346578.realsoft.sk', "vis_idSysUser"=>'1');
                $aValues[]  = array("vis_dateTime"=>'2020-03-13 10:43:50', "vis_ip"=>'85.216.201.228', "vis_addr"=>'chello085216201228.chello.sk', "vis_idSysUser"=>'2');
                $aValues[]  = array("vis_dateTime"=>'2020-03-13 10:43:55', "vis_ip"=>'11.222.333.444', "vis_addr"=>'realsoft12346578.realsoft.sk', "vis_idSysUser"=>'1');
                $aValues[]  = array("vis_dateTime"=>'2020-03-13 10:44:00', "vis_ip"=>'11.222.333.444', "vis_addr"=>'realsoft12346578.realsoft.sk', "vis_idSysUser"=>'1');
                $aValues[]  = array("vis_dateTime"=>'2020-03-13 10:44:05', "vis_ip"=>'85.216.201.228', "vis_addr"=>'chello085216201228.chello.sk', "vis_idSysUser"=>'2');
                $aValues[]  = array("vis_dateTime"=>'2020-03-13 10:44:10', "vis_ip"=>'11.222.333.444', "vis_addr"=>'realsoft12346578.realsoft.sk', "vis_idSysUser"=>'1');
                $aValues[]  = array("vis_dateTime"=>'2020-03-13 10:44:15', "vis_ip"=>'11.222.333.444', "vis_addr"=>'realsoft12346578.realsoft.sk', "vis_idSysUser"=>'1');
                $aValues[]  = array("vis_dateTime"=>'2020-03-13 10:44:20', "vis_ip"=>'85.216.201.228', "vis_addr"=>'chello085216201228.chello.sk', "vis_idSysUser"=>'2');
                $aValues[]  = array("vis_dateTime"=>'2020-03-13 10:44:25', "vis_ip"=>'11.222.333.444', "vis_addr"=>'realsoft12346578.realsoft.sk', "vis_idSysUser"=>'1');
                $aValues[]  = array("vis_dateTime"=>'2020-03-13 10:44:30', "vis_ip"=>'85.216.201.228', "vis_addr"=>'chello085216201228.chello.sk', "vis_idSysUser"=>'2');
                $aValues[]  = array("vis_dateTime"=>'2020-03-13 10:44:35', "vis_ip"=>'11.222.333.444', "vis_addr"=>'realsoft12346578.realsoft.sk', "vis_idSysUser"=>'1');
                $aValues[]  = array("vis_dateTime"=>'2020-03-13 10:44:40', "vis_ip"=>'85.216.201.228', "vis_addr"=>'chello085216201228.chello.sk', "vis_idSysUser"=>'2');
                $aValues[]  = array("vis_dateTime"=>'2020-03-13 10:44:45', "vis_ip"=>'11.222.333.444', "vis_addr"=>'realsoft12346578.realsoft.sk', "vis_idSysUser"=>'1');
                $aValues[]  = array("vis_dateTime"=>'2020-03-13 10:44:50', "vis_ip"=>'85.216.201.228', "vis_addr"=>'chello085216201228.chello.sk', "vis_idSysUser"=>'2');
                $aValues[]  = array("vis_dateTime"=>'2020-03-13 10:44:55', "vis_ip"=>'11.222.333.444', "vis_addr"=>'realsoft12346578.realsoft.sk', "vis_idSysUser"=>'1');
                $aValues[]  = array("vis_dateTime"=>'2020-03-13 10:45:00', "vis_ip"=>'85.216.201.228', "vis_addr"=>'chello085216201228.chello.sk', "vis_idSysUser"=>'2');
                $aValues[]  = array("vis_dateTime"=>'2020-03-13 10:45:05', "vis_ip"=>'11.222.333.444', "vis_addr"=>'realsoft12346578.realsoft.sk', "vis_idSysUser"=>'1');
                $aValues[]  = array("vis_dateTime"=>'2020-03-13 10:45:10', "vis_ip"=>'85.216.201.228', "vis_addr"=>'chello085216201228.chello.sk', "vis_idSysUser"=>'2');
                $aValues[]  = array("vis_dateTime"=>'2020-03-13 10:45:15', "vis_ip"=>'11.222.333.444', "vis_addr"=>'realsoft12346578.realsoft.sk', "vis_idSysUser"=>'1');
                $aValues[]  = array("vis_dateTime"=>'2020-03-13 10:45:20', "vis_ip"=>'85.216.201.228', "vis_addr"=>'chello085216201228.chello.sk', "vis_idSysUser"=>'2');
                $aValues[]  = array("vis_dateTime"=>'2020-03-13 10:45:25', "vis_ip"=>'11.222.333.444', "vis_addr"=>'realsoft12346578.realsoft.sk', "vis_idSysUser"=>'1');
                $aValues[]  = array("vis_dateTime"=>'2020-03-13 10:45:30', "vis_ip"=>'85.216.201.228', "vis_addr"=>'chello085216201228.chello.sk', "vis_idSysUser"=>'2');
                $aValues[]  = array("vis_dateTime"=>'2020-03-13 10:45:35', "vis_ip"=>'11.222.333.444', "vis_addr"=>'realsoft12346578.realsoft.sk', "vis_idSysUser"=>'1');
                $aValues[]  = array("vis_dateTime"=>'2020-03-13 10:45:40', "vis_ip"=>'85.216.201.228', "vis_addr"=>'chello085216201228.chello.sk', "vis_idSysUser"=>'2');
                $aValues[]  = array("vis_dateTime"=>'2020-03-13 10:45:45', "vis_ip"=>'11.222.333.444', "vis_addr"=>'realsoft12346578.realsoft.sk', "vis_idSysUser"=>'1');
                $aValues[]  = array("vis_dateTime"=>'2020-03-13 10:45:50', "vis_ip"=>'85.216.201.228', "vis_addr"=>'chello085216201228.chello.sk', "vis_idSysUser"=>'2');
                $aValues[]  = array("vis_dateTime"=>'2020-03-13 10:45:55', "vis_ip"=>'11.222.333.444', "vis_addr"=>'realsoft12346578.realsoft.sk', "vis_idSysUser"=>'1');


                $aValues[]  = array("vis_dateTime"=>'2020-03-14 10:42:40', "vis_ip"=>'85.216.201.228', "vis_addr"=>'chello085216201228.chello.sk', "vis_idSysUser"=>'2');
                $aValues[]  = array("vis_dateTime"=>'2020-03-14 10:42:45', "vis_ip"=>'11.222.333.444', "vis_addr"=>'realsoft12346578.realsoft.sk', "vis_idSysUser"=>'1');
                $aValues[]  = array("vis_dateTime"=>'2020-03-14 10:42:50', "vis_ip"=>'85.216.201.228', "vis_addr"=>'chello085216201228.chello.sk', "vis_idSysUser"=>'2');
                $aValues[]  = array("vis_dateTime"=>'2020-03-14 10:42:55', "vis_ip"=>'11.222.333.444', "vis_addr"=>'realsoft12346578.realsoft.sk', "vis_idSysUser"=>'1');
                $aValues[]  = array("vis_dateTime"=>'2020-03-14 10:43:00', "vis_ip"=>'85.216.201.228', "vis_addr"=>'chello085216201228.chello.sk', "vis_idSysUser"=>'2');
                $aValues[]  = array("vis_dateTime"=>'2020-03-14 10:43:05', "vis_ip"=>'11.222.333.444', "vis_addr"=>'realsoft12346578.realsoft.sk', "vis_idSysUser"=>'1');
                $aValues[]  = array("vis_dateTime"=>'2020-03-14 10:43:10', "vis_ip"=>'85.216.201.228', "vis_addr"=>'chello085216201228.chello.sk', "vis_idSysUser"=>'2');
                $aValues[]  = array("vis_dateTime"=>'2020-03-14 10:43:15', "vis_ip"=>'11.222.333.444', "vis_addr"=>'realsoft12346578.realsoft.sk', "vis_idSysUser"=>'1');
                $aValues[]  = array("vis_dateTime"=>'2020-03-14 10:43:20', "vis_ip"=>'85.216.201.228', "vis_addr"=>'chello085216201228.chello.sk', "vis_idSysUser"=>'2');
                $aValues[]  = array("vis_dateTime"=>'2020-03-14 10:43:25', "vis_ip"=>'11.222.333.444', "vis_addr"=>'realsoft12346578.realsoft.sk', "vis_idSysUser"=>'1');
                $aValues[]  = array("vis_dateTime"=>'2020-03-14 10:43:30', "vis_ip"=>'85.216.201.228', "vis_addr"=>'chello085216201228.chello.sk', "vis_idSysUser"=>'2');
                $aValues[]  = array("vis_dateTime"=>'2020-03-14 10:43:35', "vis_ip"=>'11.222.333.444', "vis_addr"=>'realsoft12346578.realsoft.sk', "vis_idSysUser"=>'1');
                $aValues[]  = array("vis_dateTime"=>'2020-03-14 10:43:40', "vis_ip"=>'85.216.201.228', "vis_addr"=>'chello085216201228.chello.sk', "vis_idSysUser"=>'2');
                $aValues[]  = array("vis_dateTime"=>'2020-03-14 10:43:45', "vis_ip"=>'11.222.333.444', "vis_addr"=>'realsoft12346578.realsoft.sk', "vis_idSysUser"=>'1');
                $aValues[]  = array("vis_dateTime"=>'2020-03-14 10:43:50', "vis_ip"=>'85.216.201.228', "vis_addr"=>'chello085216201228.chello.sk', "vis_idSysUser"=>'2');
                $aValues[]  = array("vis_dateTime"=>'2020-03-14 10:43:55', "vis_ip"=>'11.222.333.444', "vis_addr"=>'realsoft12346578.realsoft.sk', "vis_idSysUser"=>'1');
                $aValues[]  = array("vis_dateTime"=>'2020-03-14 10:44:00', "vis_ip"=>'11.222.333.444', "vis_addr"=>'realsoft12346578.realsoft.sk', "vis_idSysUser"=>'1');
                $aValues[]  = array("vis_dateTime"=>'2020-03-14 10:44:05', "vis_ip"=>'85.216.201.228', "vis_addr"=>'chello085216201228.chello.sk', "vis_idSysUser"=>'2');
                $aValues[]  = array("vis_dateTime"=>'2020-03-14 10:44:10', "vis_ip"=>'11.222.333.444', "vis_addr"=>'realsoft12346578.realsoft.sk', "vis_idSysUser"=>'1');
                $aValues[]  = array("vis_dateTime"=>'2020-03-14 10:44:15', "vis_ip"=>'11.222.333.444', "vis_addr"=>'realsoft12346578.realsoft.sk', "vis_idSysUser"=>'1');
                $aValues[]  = array("vis_dateTime"=>'2020-03-14 10:44:20', "vis_ip"=>'85.216.201.228', "vis_addr"=>'chello085216201228.chello.sk', "vis_idSysUser"=>'2');
                $aValues[]  = array("vis_dateTime"=>'2020-03-14 10:44:25', "vis_ip"=>'11.222.333.444', "vis_addr"=>'realsoft12346578.realsoft.sk', "vis_idSysUser"=>'1');
                $aValues[]  = array("vis_dateTime"=>'2020-03-14 10:44:30', "vis_ip"=>'85.216.201.228', "vis_addr"=>'chello085216201228.chello.sk', "vis_idSysUser"=>'2');
                $aValues[]  = array("vis_dateTime"=>'2020-03-14 10:44:35', "vis_ip"=>'11.222.333.444', "vis_addr"=>'realsoft12346578.realsoft.sk', "vis_idSysUser"=>'1');
                $aValues[]  = array("vis_dateTime"=>'2020-03-14 10:44:40', "vis_ip"=>'85.216.201.228', "vis_addr"=>'chello085216201228.chello.sk', "vis_idSysUser"=>'2');
                $aValues[]  = array("vis_dateTime"=>'2020-03-14 10:44:45', "vis_ip"=>'11.222.333.444', "vis_addr"=>'realsoft12346578.realsoft.sk', "vis_idSysUser"=>'1');
                $aValues[]  = array("vis_dateTime"=>'2020-03-14 10:44:50', "vis_ip"=>'85.216.201.228', "vis_addr"=>'chello085216201228.chello.sk', "vis_idSysUser"=>'2');
                $aValues[]  = array("vis_dateTime"=>'2020-03-14 10:44:55', "vis_ip"=>'11.222.333.444', "vis_addr"=>'realsoft12346578.realsoft.sk', "vis_idSysUser"=>'1');
                $aValues[]  = array("vis_dateTime"=>'2020-03-14 10:45:00', "vis_ip"=>'85.216.201.228', "vis_addr"=>'chello085216201228.chello.sk', "vis_idSysUser"=>'2');
                $aValues[]  = array("vis_dateTime"=>'2020-03-14 10:45:05', "vis_ip"=>'11.222.333.444', "vis_addr"=>'realsoft12346578.realsoft.sk', "vis_idSysUser"=>'1');
                $aValues[]  = array("vis_dateTime"=>'2020-03-14 10:45:10', "vis_ip"=>'85.216.201.228', "vis_addr"=>'chello085216201228.chello.sk', "vis_idSysUser"=>'2');
                $aValues[]  = array("vis_dateTime"=>'2020-03-14 10:45:15', "vis_ip"=>'11.222.333.444', "vis_addr"=>'realsoft12346578.realsoft.sk', "vis_idSysUser"=>'1');
                $aValues[]  = array("vis_dateTime"=>'2020-03-14 10:45:20', "vis_ip"=>'85.216.201.228', "vis_addr"=>'chello085216201228.chello.sk', "vis_idSysUser"=>'2');
                $aValues[]  = array("vis_dateTime"=>'2020-03-14 10:45:25', "vis_ip"=>'11.222.333.444', "vis_addr"=>'realsoft12346578.realsoft.sk', "vis_idSysUser"=>'1');
                $aValues[]  = array("vis_dateTime"=>'2020-03-14 10:45:30', "vis_ip"=>'85.216.201.228', "vis_addr"=>'chello085216201228.chello.sk', "vis_idSysUser"=>'2');
                $aValues[]  = array("vis_dateTime"=>'2020-03-14 10:45:35', "vis_ip"=>'11.222.333.444', "vis_addr"=>'realsoft12346578.realsoft.sk', "vis_idSysUser"=>'1');
                $aValues[]  = array("vis_dateTime"=>'2020-03-14 10:45:40', "vis_ip"=>'85.216.201.228', "vis_addr"=>'chello085216201228.chello.sk', "vis_idSysUser"=>'2');
                $aValues[]  = array("vis_dateTime"=>'2020-03-14 10:45:45', "vis_ip"=>'11.222.333.444', "vis_addr"=>'realsoft12346578.realsoft.sk', "vis_idSysUser"=>'1');
                $aValues[]  = array("vis_dateTime"=>'2020-03-14 10:45:50', "vis_ip"=>'85.216.201.228', "vis_addr"=>'chello085216201228.chello.sk', "vis_idSysUser"=>'2');
                $aValues[]  = array("vis_dateTime"=>'2020-03-14 10:45:55', "vis_ip"=>'11.222.333.444', "vis_addr"=>'realsoft12346578.realsoft.sk', "vis_idSysUser"=>'1');

                $aValues[]  = array("vis_dateTime"=>'2020-03-15 10:42:40', "vis_ip"=>'85.216.201.228', "vis_addr"=>'chello085216201228.chello.sk', "vis_idSysUser"=>'2');
                $aValues[]  = array("vis_dateTime"=>'2020-03-15 10:42:45', "vis_ip"=>'11.222.333.444', "vis_addr"=>'realsoft12346578.realsoft.sk', "vis_idSysUser"=>'1');
                $aValues[]  = array("vis_dateTime"=>'2020-03-15 10:42:50', "vis_ip"=>'85.216.201.228', "vis_addr"=>'chello085216201228.chello.sk', "vis_idSysUser"=>'2');
                $aValues[]  = array("vis_dateTime"=>'2020-03-15 10:42:55', "vis_ip"=>'11.222.333.444', "vis_addr"=>'realsoft12346578.realsoft.sk', "vis_idSysUser"=>'1');
                $aValues[]  = array("vis_dateTime"=>'2020-03-15 10:43:00', "vis_ip"=>'85.216.201.228', "vis_addr"=>'chello085216201228.chello.sk', "vis_idSysUser"=>'2');
                $aValues[]  = array("vis_dateTime"=>'2020-03-15 10:43:05', "vis_ip"=>'11.222.333.444', "vis_addr"=>'realsoft12346578.realsoft.sk', "vis_idSysUser"=>'1');
                $aValues[]  = array("vis_dateTime"=>'2020-03-15 10:43:10', "vis_ip"=>'85.216.201.228', "vis_addr"=>'chello085216201228.chello.sk', "vis_idSysUser"=>'2');
                $aValues[]  = array("vis_dateTime"=>'2020-03-15 10:43:15', "vis_ip"=>'11.222.333.444', "vis_addr"=>'realsoft12346578.realsoft.sk', "vis_idSysUser"=>'1');
                $aValues[]  = array("vis_dateTime"=>'2020-03-15 10:43:20', "vis_ip"=>'85.216.201.228', "vis_addr"=>'chello085216201228.chello.sk', "vis_idSysUser"=>'2');
                $aValues[]  = array("vis_dateTime"=>'2020-03-15 10:43:25', "vis_ip"=>'11.222.333.444', "vis_addr"=>'realsoft12346578.realsoft.sk', "vis_idSysUser"=>'1');
                $aValues[]  = array("vis_dateTime"=>'2020-03-15 10:43:30', "vis_ip"=>'85.216.201.228', "vis_addr"=>'chello085216201228.chello.sk', "vis_idSysUser"=>'2');
                $aValues[]  = array("vis_dateTime"=>'2020-03-15 10:43:35', "vis_ip"=>'11.222.333.444', "vis_addr"=>'realsoft12346578.realsoft.sk', "vis_idSysUser"=>'1');
                $aValues[]  = array("vis_dateTime"=>'2020-03-15 10:43:40', "vis_ip"=>'85.216.201.228', "vis_addr"=>'chello085216201228.chello.sk', "vis_idSysUser"=>'2');
                $aValues[]  = array("vis_dateTime"=>'2020-03-15 10:43:45', "vis_ip"=>'11.222.333.444', "vis_addr"=>'realsoft12346578.realsoft.sk', "vis_idSysUser"=>'1');
                $aValues[]  = array("vis_dateTime"=>'2020-03-15 10:43:50', "vis_ip"=>'85.216.201.228', "vis_addr"=>'chello085216201228.chello.sk', "vis_idSysUser"=>'2');
                $aValues[]  = array("vis_dateTime"=>'2020-03-15 10:43:55', "vis_ip"=>'11.222.333.444', "vis_addr"=>'realsoft12346578.realsoft.sk', "vis_idSysUser"=>'1');
                $aValues[]  = array("vis_dateTime"=>'2020-03-15 10:44:00', "vis_ip"=>'11.222.333.444', "vis_addr"=>'realsoft12346578.realsoft.sk', "vis_idSysUser"=>'1');
                $aValues[]  = array("vis_dateTime"=>'2020-03-15 10:44:05', "vis_ip"=>'85.216.201.228', "vis_addr"=>'chello085216201228.chello.sk', "vis_idSysUser"=>'2');
                $aValues[]  = array("vis_dateTime"=>'2020-03-15 10:44:10', "vis_ip"=>'11.222.333.444', "vis_addr"=>'realsoft12346578.realsoft.sk', "vis_idSysUser"=>'1');
                $aValues[]  = array("vis_dateTime"=>'2020-03-15 10:44:15', "vis_ip"=>'11.222.333.444', "vis_addr"=>'realsoft12346578.realsoft.sk', "vis_idSysUser"=>'1');
                $aValues[]  = array("vis_dateTime"=>'2020-03-15 10:44:20', "vis_ip"=>'85.216.201.228', "vis_addr"=>'chello085216201228.chello.sk', "vis_idSysUser"=>'2');
                $aValues[]  = array("vis_dateTime"=>'2020-03-15 10:44:25', "vis_ip"=>'11.222.333.444', "vis_addr"=>'realsoft12346578.realsoft.sk', "vis_idSysUser"=>'1');
                $aValues[]  = array("vis_dateTime"=>'2020-03-15 10:44:30', "vis_ip"=>'85.216.201.228', "vis_addr"=>'chello085216201228.chello.sk', "vis_idSysUser"=>'2');
                $aValues[]  = array("vis_dateTime"=>'2020-03-15 10:44:35', "vis_ip"=>'11.222.333.444', "vis_addr"=>'realsoft12346578.realsoft.sk', "vis_idSysUser"=>'1');
                $aValues[]  = array("vis_dateTime"=>'2020-03-15 10:44:40', "vis_ip"=>'85.216.201.228', "vis_addr"=>'chello085216201228.chello.sk', "vis_idSysUser"=>'2');
                $aValues[]  = array("vis_dateTime"=>'2020-03-15 10:44:45', "vis_ip"=>'11.222.333.444', "vis_addr"=>'realsoft12346578.realsoft.sk', "vis_idSysUser"=>'1');
                $aValues[]  = array("vis_dateTime"=>'2020-03-15 10:44:50', "vis_ip"=>'85.216.201.228', "vis_addr"=>'chello085216201228.chello.sk', "vis_idSysUser"=>'2');
                $aValues[]  = array("vis_dateTime"=>'2020-03-15 10:44:55', "vis_ip"=>'11.222.333.444', "vis_addr"=>'realsoft12346578.realsoft.sk', "vis_idSysUser"=>'1');
                $aValues[]  = array("vis_dateTime"=>'2020-03-15 10:45:00', "vis_ip"=>'85.216.201.228', "vis_addr"=>'chello085216201228.chello.sk', "vis_idSysUser"=>'2');
                $aValues[]  = array("vis_dateTime"=>'2020-03-15 10:45:05', "vis_ip"=>'11.222.333.444', "vis_addr"=>'realsoft12346578.realsoft.sk', "vis_idSysUser"=>'1');
                $aValues[]  = array("vis_dateTime"=>'2020-03-15 10:45:10', "vis_ip"=>'85.216.201.228', "vis_addr"=>'chello085216201228.chello.sk', "vis_idSysUser"=>'2');
                $aValues[]  = array("vis_dateTime"=>'2020-03-15 10:45:15', "vis_ip"=>'11.222.333.444', "vis_addr"=>'realsoft12346578.realsoft.sk', "vis_idSysUser"=>'1');
                $aValues[]  = array("vis_dateTime"=>'2020-03-15 10:45:20', "vis_ip"=>'85.216.201.228', "vis_addr"=>'chello085216201228.chello.sk', "vis_idSysUser"=>'2');
                $aValues[]  = array("vis_dateTime"=>'2020-03-15 10:45:25', "vis_ip"=>'11.222.333.444', "vis_addr"=>'realsoft12346578.realsoft.sk', "vis_idSysUser"=>'1');
                $aValues[]  = array("vis_dateTime"=>'2020-03-15 10:45:30', "vis_ip"=>'85.216.201.228', "vis_addr"=>'chello085216201228.chello.sk', "vis_idSysUser"=>'2');
                $aValues[]  = array("vis_dateTime"=>'2020-03-15 10:45:35', "vis_ip"=>'11.222.333.444', "vis_addr"=>'realsoft12346578.realsoft.sk', "vis_idSysUser"=>'1');
                $aValues[]  = array("vis_dateTime"=>'2020-03-15 10:45:40', "vis_ip"=>'85.216.201.228', "vis_addr"=>'chello085216201228.chello.sk', "vis_idSysUser"=>'2');
                $aValues[]  = array("vis_dateTime"=>'2020-03-15 10:45:45', "vis_ip"=>'11.222.333.444', "vis_addr"=>'realsoft12346578.realsoft.sk', "vis_idSysUser"=>'1');
                $aValues[]  = array("vis_dateTime"=>'2020-03-15 10:45:50', "vis_ip"=>'85.216.201.228', "vis_addr"=>'chello085216201228.chello.sk', "vis_idSysUser"=>'2');
                $aValues[]  = array("vis_dateTime"=>'2020-03-15 10:45:55', "vis_ip"=>'11.222.333.444', "vis_addr"=>'realsoft12346578.realsoft.sk', "vis_idSysUser"=>'1');
                $this->insert($aStruct, $aValues, true, __FILE__, __LINE__, true);
                */
        }

        public function sysUserAccountsCreateStructureTable() {

                $aStruct    = array(); $tableName = "sysUserAccounts";
                $aStruct[]  = array(  "table"=>$tableName, "name"=>"acc_id",        "type"=>"bigint"        );
                $aStruct[]  = array(  "table"=>$tableName, "name"=>"acc_login",     "type"=>"varchar(50)"   );
                $aStruct[]  = array(  "table"=>$tableName, "name"=>"acc_pass",      "type"=>"varchar(50)"   );
                $aStruct[]  = array(  "table"=>$tableName, "name"=>"acc_name",      "type"=>"varchar(50)"   );
                $aStruct[]  = array(  "table"=>$tableName, "name"=>"acc_surName",   "type"=>"varchar(50)"   );
                $aStruct[]  = array(  "table"=>$tableName, "name"=>"acc_alias",     "type"=>"varchar(50)"   );
                $aStruct[]  = array(  "table"=>$tableName, "name"=>"acc_email",     "type"=>"varchar(50)"   );
                $aStruct[]  = array(  "table"=>$tableName, "name"=>"acc_mobil",     "type"=>"varchar(20)"   );
                $aStruct[]  = array(  "table"=>$tableName, "name"=>"acc_admin",     "type"=>"bool"          );
                $aStruct[]  = array(  "table"=>$tableName, "name"=>"acc_demo",     "type"=>"bool"           );
                $aStruct[]  = array(  "table"=>$tableName, "name"=>"acc_block",     "type"=>"bool"          );
                $aStruct[]  = array(  "table"=>$tableName, "name"=>"acc_payment",   "type"=>"date"          );
                $aStruct[]  = array(  "table"=>$tableName, "name"=>"acc_supervisor","type"=>"bool"          );
                $aStruct[]  = array(  "table"=>$tableName, "name"=>"acc_chatAccess","type"=>"bool"          );
                $aStruct[]  = array(  "table"=>$tableName, "name"=>"acc_chatAdmin", "type"=>"bool"          );
                $aStruct[]  = array(  "table"=>$tableName, "name"=>"acc_chatMail",  "type"=>"bool"          );
                $aStruct[]  = array(  "table"=>$tableName, "name"=>"acc_free",      "type"=>"bool"          );
                $aStruct[]  = array(  "table"=>$tableName, "name"=>"acc_chatLogin", "type"=>"datetime"      );
                $aStruct[]  = array(  "table"=>$tableName, "name"=>"acc_isDelete",  "type"=>"bool"          );
                $aStruct[]  = array(  "table"=>$tableName, "name"=>"acc_freeEntry",  "type"=>"varchar(20)"  );
                $this->drop($aStruct, false, __FILE__); $this->create($tableName, $aStruct, __FILE__);
                
                
                $this->alterTableAdd($tableName, "acc_alias", "varchar", 50, "acc_surName", __FILE__, __LINE__);

                $aValues    =   array();
                $aValues[]  = array("acc_login"=>'zolo', "acc_pass"=>md5('PROPERTY123!'), "acc_name"=>'Zoltán', "acc_surName"=>'Tatay', "acc_email"=>'zolo71@hotmail.com', "acc_mobil"=>'+421 948101818' , "acc_admin"=>1,  "acc_block"=>0);
                $aValues[]  = array("acc_login"=>'rasto', "acc_pass"=>md5('celula'), "acc_name"=>'Rastislav', "acc_surName"=>'Rehák', "acc_email"=>'rasto@abnet.sk', "acc_mobil"=>'+421 904 478738' , "acc_admin"=>1,  "acc_block"=>0);
                $this->insert($aStruct, $aValues, true, __FILE__, __LINE__, true);
        }

        public function contentSitesCreateStructureTable() {
                $aStruct    = array(); $tableName = "contentSites";
                $aStruct[]  = array(  "table"=>$tableName, "name"=>"con_id",        "type"=>"bigint"        );
                $aStruct[]  = array(  "table"=>$tableName, "name"=>"con_idMenu",    "type"=>"bigint"        );
                $aStruct[]  = array(  "table"=>$tableName, "name"=>"con_path",      "type"=>"varchar(500)"  );
                $aStruct[]  = array(  "table"=>$tableName, "name"=>"con_videoName", "type"=>"varchar(100)"  );
                $this->drop($aStruct, false, __FILE__); $this->create($tableName, $aStruct, __FILE__);
        }
        
        public function chatTableCreateStructureTable() {
                $aStruct    = array(); $tableName = "chatTable";
                $aStruct[]  = array(  "table"=>$tableName, "name"=>"chat_id",       "type"=>"bigint"        );
                $aStruct[]  = array(  "table"=>$tableName, "name"=>"chat_userFrom", "type"=>"bigint"        );
                $aStruct[]  = array(  "table"=>$tableName, "name"=>"chat_userTo",   "type"=>"bigint"        );
                $aStruct[]  = array(  "table"=>$tableName, "name"=>"chat_message",  "type"=>"varchar(5000)" );
                $aStruct[]  = array(  "table"=>$tableName, "name"=>"chat_timeSend", "type"=>"datetime"      );
                $aStruct[]  = array(  "table"=>$tableName, "name"=>"chat_delete",   "type"=>"bool"          );
                $this->drop($aStruct, false, __FILE__); $this->create($tableName, $aStruct, __FILE__);
                
       }


}


?>