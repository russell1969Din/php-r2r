<?php

class db {
        
        function __construct() {
        
                $iCon = $this->dbCon();

                $struct = _constructClass("struct");
                foreach(get_class_methods("struct") as $method) {if(strPos($method, "CreateStructureTable"))  {$eCode = "\$struct->".$method."();"; eval($eCode);}}

        }
        
        public function dbCon() {
                
                include($_SESSION["PROJECT_INFO"]);
                $this->iCon = mysqli_connect($dbLocal, $dbLogin, $dbPass,  $dbName);
                $result     = MySQLi_Select_DB($this->iCon, $dbName);
                if( !$result )  {
                        echo "DB chyba, Neviem sa pripojiť na databázu<br />";
                        echo 'MySQL chyba: '.mysqli_error()."<br />";
                        die();    
                }
                return($this->iCon);        
        }
        
        //insert(
        public function insert($aStruct, $aValues, $onlyEmpty=false, $__FILE__="", $__LINE__ ="", $reset=false, $showSQL=false)  {
        
                $aException = array();
                if(getType($reset)=="string")   {$aException[]  = trim($reset); $reset=false;}
                if(getType($reset)=="array")    {$aException    = $reset;       $reset=false;}

                include($_SESSION["PROJECT_INFO"]);
                if(getType($aStruct)=="string") {
                        $tableName = $aStruct;
                } elseif(getType($aStruct)=="array") {
                        if(count($aStruct)==0) {return(array());}
                        $aFirst = $aStruct[0];
                        $tableName = $aFirst["table"];
                }
                if(getType($tableName)!="string") {return(array());}
                if(getType($aValues)!="array") {return(array());}
                if(count($aValues)==0) {return(array());}
                
                if($reset) {$_SESSION[$__FILE__."_".$__LINE__] = null;}
                $aRecord = $aValues[0];
                $comma = $sessionValue = "";
                foreach(array_keys($aRecord) as $key) {
                        $isExcept == false;
                        foreach($aException as $except) {if($key==$except) {$isExcept=!$isExcept;break;}}
                        if(!$isExcept) {
                                $sessionValue .= $comma.$key."|".$aRecord[$key];
                                $comma = "|";        
                        }
                }
                if($_SESSION[$__FILE__."_".$__LINE__] == $sessionValue) {return(null);}
                
                $aFields = $this->structureFromTable($tableName, __LINE__, __FILE__);
                $aFirst = $aFields[0];
                $id = $aFirst["name"];
 
                if(!strPos($tableName,"_")) {$tableName = $prx."_".$tableName;}

                if($onlyEmpty)  {
                        $oTest = $this->get("TEST", $tableName, array($id), null, true, __FILE__, __LINE__);
                        if(count($oTest)>0) {return(array());}
                }
                
                $aNewId=array();
                $_SESSION[$__FILE__."_".$__LINE__] = $sessionValue;
                foreach($aValues as $value) {
                        $comma=""; 
                        $strFields = $strValues = "";
                        foreach($aFields as $field) {
                                $strFields.=$comma.$field["name"];
                                $strValues.=$comma."'".$value[$field["name"]]."'";
                                $comma=",";
                        }
                        $SQL="INSERT INTO ".$tableName." ( $strFields ) VALUES( $strValues )";
                        if($showSQL) {d($__FILE__." / ".$__LINE__); d($SQL);}
                        if($this->len($__FILE__) == 0)   {$__FILE__ = __FILE__;}
                        if($this->len($__LINE__) == 0)   {$__LINE__ = __LINE__;}
                        $this->call( $SQL, $__FILE__, $__LINE__  );
                        $fieldId = $this->getPrefixFields($tableName)."_id";
                        $oNewRecord = $this->get("NEW", $tableName, $fieldId, " 1 ORDER BY ".$fieldId." DESC", true, $__FILE__, $__LINE__, true);
                        $aNewId[]=$oNewRecord[$fieldId];
                }
                return($aNewId);
        }
        
        //delete(
        public function delete($tableName, $id, $__FILE__="", $__LINE__="", $showSQL=false) {
                include($_SESSION["PROJECT_INFO"]);
                if(getType($id)!="integer" && getType($id)!="string") {$where = " 0 ";}
                if(getType($id)=="integer") {$where = " ".$this->getPrefixFields($tableName)."_id='".$id."'";}
                if(getType($id)=="string")  {$where = $id;}

                $SQL    =   "DELETE FROM ".$prx."_".$tableName." WHERE ".$where;
                if($showSQL) {d($__FILE__." / ".$__LINE__); d($SQL);}
                if($this->len($__FILE__)==0)   {$__FILE__=__FILE__;}
                if($this->len($__LINE__)==0)   {$__LINE__=__LINE__;}
                $this->call($SQL, $__FILE__, $__LINE__);
        }

        
        //create(
        public function create($tableName, $aStruct, $__FILE__="", $__LINE__="", $showSQL=false)    {
                
                include($_SESSION["PROJECT_INFO"]);
                $lExistStru = false; 
                if(getType($aStruct) == "array") {   
                        if( Count($aStruct) > 0) {$lExistStru = !$lExistStru;}    
                }  
                if(!$lExistStru) {$aStruct = $_SESSION[$tableName."Structure"];} else {$_SESSION[$tableName."Structure"] = $aStruct;}
                //if( $__FILE__   ==   __FILE__   )   {   $prefix =   $this->newPrefix;   $lExec  =   false;  }   else    {   $prefix =   $prx;   $lExec  =   true;   }    

                $index  =   1;
                foreach($aStruct as $structLine ) {
                        if($index ==  1) {
                                $SQL     = "";
                                $SQL    .= "CREATE TABLE IF NOT EXISTS   ".$prx."_".$structLine["table"]."\r\n";
                                $SQL    .= "(\r\n";
                                $SQL    .= " ".$structLine["name"]." ".$structLine["type"]." AUTO_INCREMENT NOT NULL,\r\n";
                                $indexKey = $structLine["name"];
                        } else {
                                $SQL    .= " ".$structLine["name"]." ".$structLine["type"]." COLLATE utf8_slovak_ci NOT NULL,\r\n";
                        }
                       ++$index;
                }
                $SQL .= " PRIMARY KEY (".$indexKey.")\r\n";
                $SQL = $SQL.") ENGINE=MyISAM  DEFAULT CHARSET=utf8   COLLATE=utf8_slovak_ci  AUTO_INCREMENT=1;   \r\n\r\n";
                if($showSQL) {d($__FILE__." / ".$__LINE__); d($SQL);}
                if($this->len($__FILE__) == 0) {$__FILE__ = __FILE__;}
                if($this->len($__LINE__) == 0) {$__LINE__ = __LINE__;}
                //if($lExec)   {   $this->call( $SQL, __FILE__, $__LINE__  );  }
                $this->call( $SQL, $__FILE__, $__LINE__);
                return(true);
        }

        
        //drop(
        public function drop($aStruct, $execDrop, $__FILE__="", $__LINE__="", $showSQL=false) {
                if($execDrop)   {
                        include($_SESSION["PROJECT_INFO"]);
                        $aStruct = $aStruct[0];
                        $SQL = "DROP TABLE IF EXISTS  ".$prx."_".$aStruct["table"];
                        if($showSQL) {d($__FILE__." / ".$__LINE__); d($SQL);}
                        if($this->len($__FILE__) == 0) {$__FILE__ = __FILE__;}
                        if($this->len($__LINE__) == 0) {$__LINE__ = __LINE__;}
                        $this->call( $SQL, $__FILE__, $__LINE__);   
                }
                return(true);
        }
        
        //update(
        public function update($tableName, $aFieldNames, $aValues, $conditions=null, $__FILE__="", $__LINE__="", $showSQL=false)   {
                include($_SESSION["PROJECT_INFO"]);

                if(getType($conditions) != "NULL") {$conditions = " WHERE ".$conditions;} else {$conditions = "";}
                
                if(getType($aFieldNames) != "array") {$aFieldNames = array($aFieldNames);}
                if(getType($aValues) != "array") {$aValues = array($aValues);}
  
                if(getType($aFieldNames) != "array" || getType($aValues) != "array") {return(null);}
                if(Count($aFieldNames) != Count($aValues)) {return(null);}
                if(Count($aFieldNames) == 0 || Count($aValues) == 0) {return(null);}
                $comma = $setString = "";
                for($i=0; $i < Count($aFieldNames); ++$i) {
                        $setString .= $comma.$aFieldNames[$i]." = '".$aValues[ $i]."'";
                        $comma = ", ";
                }
                $SQL = "UPDATE ".$prx."_".$tableName." SET $setString  $conditions";
                if($showSQL) {d($__FILE__." / ".$__LINE__); d($SQL);}
                if($this->len($__FILE__)==0)   {$__FILE__=__FILE__;}
                if($this->len($__LINE__)==0)   {$__LINE__=__LINE__;}
                $this->call( $SQL, $__FILE__, $__LINE__);        
                return(true);
        
        
        
                //if(getType($aFieldName)   !=  "array" &&  getType(    $aValues    )   !=  "array"   ) {
                        //$SQL    =   "UPDATE ".$prx."_".$tableName." SET $aFieldNames    =   '$aValues'    $conditions";
                        //if($showSQL) {d($__FILE__." / ".$__LINE__); d($SQL);}        
                        //if($this->len($__FILE__)==0)   {$__FILE__=__FILE__;}
                        //if($this->len($__LINE__)==0)   {$__LINE__=__LINE__;}
                        //$this->call( $SQL, $__FILE__,   $__LINE__  );
                //}   else    {

                        
                        
                        
                        
                        
                        
                        
                //}
                
        }

        
        //call( 
        public function call( $SQL, $__FILE__, $__LINE__, $aExceptField = null, $aExceptValue = null) {
        
                include($_SESSION["PROJECT_INFO"]);
                $lMySQLQuery = false;
                /*
                if($aExceptField != null && $aExceptValue != null)  {
                        if(Count($aExceptField) == Count($aExceptValue)) {
                                $_SQL  = Trim(strToUpper($SQL));
                                $insert = $update = false;
                                if(strPos($_SQL, "NSERT") == 1) {$insert = true;}
                                if(strPos($_SQL, "PDATE") == 1) {$update = true;}
                                if( $insert ||  $update ) { 
                                        $SQLCaseString  = "";
                                        for($index=0;$index<Count($aExceptField);++$index ) {
                                                if( strLen( Trim( $SQLCaseString  ) ) ==  0 ) { 
                                                        $SQLCaseString  .= $aExceptField[$index]." = '".$aExceptValue[$index]."'";  
                                                } else  {
                        
                                                        $SQLCaseString  .= " AND ".$aExceptField[$index]." = '".$aExceptValue[$index]."'";
                                                }
                                        }
                    
                                        if( strLen( Trim( $SQLCaseString  ) ) > 0 ) {
                                                $strPosTable  = strPos( $SQL, $prx  );
                                                $tableName    = "";
                                                for($i=$strPosTable;$ch!=chr(32);++$i)  {   $ch = subStr( $SQL, $i, 1 );    $tableName  .=  $ch;    }
                                                $_SQL = "SELECT * FROM  ".$tableName."  WHERE ".$SQLCaseString;
                                                $result = mysqli_query($this->getCon(), $_SQL); $lMySQLQuery = true;
                                                if( MySQLi_Num_Rows($result) > 0) {return(false);}                
                                        }
                                }            
                        }
                }
                */
                
                $iCon = $this->dbCon();
                if(!$lMySQLQuery) {$result = mysqli_query($iCon, $SQL);}
                if (!$result) {
                        echo  'Chybný SQL príkaz: ' . mysqli_error($this->dbCon())."<br />";
                        echo  'SQL: '.$SQL.'<br />';
                        echo  'Script: '.$__FILE__.'<br />';
                        echo  'Line: '.$__LINE__.'<br />';
                        die();
                } else  {
                        return($result);
                }       
        }

        //get(
        public function get($sessionName, $aTables, $aFieldNames=null, $where="", $reset=false, $__FILE__="", $__LINE__="", $onlyfirstRecord=false, $showSQL=false)   {
        
                $paramFieldNames = $aFieldNames;
        
                include($_SESSION["PROJECT_INFO"]);
                if(getType($aTables)=="string")     {$aTables = array($aTables);}
                if(getType($aFieldNames)=="string") {$aFieldNames = array($aFieldNames);}
    
                if(Count($aTables) == 0 || getType($aTables) != "array")   {return(array());}

                if($reset) {$_SESSION[$sessionName] = "NULL";}
                $this->administrativeSessionsBank($sessionName);

                if( getType($_SESSION[$sessionName]) != "array") {
                        if(getType($aFieldNames) !=  "array" || Count($aFieldNames) == 0) {
                                $aFieldNames = array();
                                foreach($aTables as $strTable  )   {
                                        if(!strPos($strTable,"_"))  {$strTable = $prx."_".$strTable;}
                                        $SQL = "SHOW COLUMNS FROM ".$strTable;
                                        $result = $this->call( $SQL, $__FILE__, $__LINE__);
                                        while($line = MySQLi_Fetch_Array($result, MYSQLI_ASSOC )) {
                                                $aFieldNames[]  =   $line[  "Field"  ]; 
                                        }                            
                                }
                        }
                        $aReturnValue   =   array();
                        $strSetTable    =   "";
                        $nIndex         =   0;   
                        foreach($aTables as $strTable) {
                                if(!strPos($strTable,"_")) {$strTable = $prx."_".$strTable;}
                                if( $nIndex > 0) {$strSetTable .= ", ";}
                                if(!$this->existTable($strTable)) {   
                                        d("Neviem spracovať dátový objekt = chýba požadovaná tabuľka <b>$strTable</b> ! (skript: $__FILE__, na riadku: $__LINE__)"); return( array()); 
                                }                             
                                $strSetTable .= $strTable;
                                ++$nIndex;  
                        }
                        if( $this->len(Trim($where)) > 0)   {$where = " WHERE ".$where;}
                        $limitRecords=""; if($onlyfirstRecord) {$limitRecords = " LIMIT 1 ";}   
                        
                        $fieldName = "*";
                        if(getType($paramFieldNames)=="array") {
                                $comma = $fieldName = "";
                                foreach($aFieldNames as $field) {
                                        $fieldName .= $comma.$field;
                                        $comma = ", ";
                                }                    
                        }

                        $SQL = "SELECT ".$fieldName." FROM ".$strSetTable." ".$where." ".$limitRecords;
                        if($showSQL) {d($__FILE__." / ".$__LINE__); d($SQL);}

                        if($this->len($__LINE__) == 0) {$__LINE__ = __LINE__;}
                        if($this->len($__FILE__) == 0) {$__FILE__ = __FILE__;}                        
                        $result =   $this->call( $SQL, $__FILE__, $__LINE__);
                        
                        
                        while($line = MySQLi_Fetch_Array($result, MYSQLI_ASSOC)) {
                                $eCode  = "\$aReturnValue[] = array( ";
                                $nIndex = 0;
                                foreach($aFieldNames as $field) {
                                        if($nIndex > 0) {$eCode .= ", ";}
                                        $eCode .= '"'.$field.'"=>"'.$line[$field].'"';
                                        ++$nIndex;        
                                }         
                                $eCode .= ");";
                                eval($eCode);
                        }
                        
                    
                }

                if($onlyfirstRecord) {$aReturnValue = $aReturnValue[0];}
                
                if(getType($aReturnValue) == "NULL") {$aReturnValue = array();}
                
                if(getType($_SESSION[$sessionName]) != "array") {
                        $_SESSION[$sessionName] = $aReturnValue;
                } else {
                        $aReturnValue = $_SESSION[$sessionName];
                }
                return($aReturnValue);
        }
        
        public function flushSessionsBank($aFlush=array()) {
        
                if(getType($aFlush) != "array") {$aFlush=array();}
                $tempUserId = $_SESSION["SYS_ACCOUNT"]["acc_id"];
                
                if(count($aFlush)>0) {
                        foreach($aFlush as $flush) {$_SESSION[$flush] = null;}
                } else {
                        foreach($_SESSION["SESSIONS_BANK"] as $bank) {
                                if($bank!="SYS_ACCOUNT") {$_SESSION[$bank] = null;}
                        }
                        $_SESSION["SESSIONS_BANK"] = array();        
                }

                $this->get("ACCESSRIGHT_MENUX", array("menuRightsAccess", "generalMenu"), null, "men_id=rig_idMenu && rig_idUser='".$tempUserId."' && men_block=0 && men_delete=0", true, __FILE__, __LINE__);
                $_SESSION["ACCESSRIGHT_MENU"] = $_SESSION["ACCESSRIGHT_MENUX"];
                $this->get("GLOBAL_ACCESSRIGHTX", array("menuRightsAccess"), null, "1", true, __FILE__, __LINE__);
                $_SESSION["GLOBAL_ACCESSRIGHT"] = $_SESSION["GLOBAL_ACCESSRIGHTX"];
                $this->get("USER_ALL_ACCOUNTX", "sysUserAccounts", null, "acc_isDelete=0 && acc_admin=0 && acc_block=0", true, __FILE__, __LINE__);
                $_SESSION["USER_ALL_ACCOUNT"] = $_SESSION["USER_ALL_ACCOUNTX"];
                $this->get("ALL_TEMPLATESX", "templates", null, null, true, __FILE__, __LINE__);
                $_SESSION["ALL_TEMPLATES"] = $_SESSION["ALL_TEMPLATESX"];
                $this->get("GLOBAL_MENU", "generalMenu", null, "men_delete=0 ORDER BY men_index ASC", true, __FILE__, __LINE__);
                
                return(true);
        }
        
        
        private function administrativeSessionsBank($sessionName) {

                if(getType($_SESSION["SESSIONS_BANK"]) == "NULL")  {$_SESSION["SESSIONS_BANK"] = array();}
                $found = false;
                $aNewSessions = array();
                foreach($_SESSION["SESSIONS_BANK"] as $bank) {
                        if($bank == $sessionName) {
                                $found = true;
                                $aNewSessions[] = $bank;
                        } else {
                                $aNewSessions[] = $bank;
                        }
                }
                
                if(!$found) {$aNewSessions[] = $sessionName;}
                $_SESSION["SESSIONS_BANK"] = $aNewSessions;
                return($found);
        }

        //existTable(
        public function existTable($tableName, $__FILE__="", $__LINE__="")   {
                include($_SESSION["PROJECT_INFO"]);
                $SQL = "SHOW TABLES FROM ".$dbName;
                if($this->len($__LINE__) == 0) {$__LINE__ = __LINE__;}
                if($this->len($__FILE__) == 0) {$__FILE__ = __FILE__;}                        
                $resultTable =   $this->call( $SQL, $__FILE__, $__LINE__  );
                if(!strPos($tableName,"_")) {$tableName=$prx."_".$tableName;}
                while($lineTable = MySQLi_Fetch_Row($resultTable))   {if($lineTable[0] == $tableName) {return(true);}}
                return( false    );      
        }
            
        private function len($param) {return(strLen(Trim($param)));}

        //structureFromTable(
        public function structureFromTable($table, $__FILE__="", $__LINE__="", $onlyFields=false)    {
                include($_SESSION["PROJECT_INFO"]);
                if(!strPos($table,"_")) {$table=$prx."_".$table;}
                if(!isSet($_SESSION[$table."Structure"])) {    
                        $SQL    = "SELECT * FROM ".$table;
                        if($this->len($__LINE__) == 0) {$__LINE__ = __LINE__;}
                        if($this->len($__FILE__) == 0) {$__FILE__ = __FILE__;}                        
                        $resStruct = $this->call( $SQL,  $__FILE__,  $__LINE__);
                        $struct = $resStruct->fetch_fields();

                        $aStruct = array();
                        foreach($struct as $lineStruct) {
                                if($onlyFields) {
                                        $aStruct[] = $lineStruct->name;   
                                } else {

                                        $_table = subStr($lineStruct->table, strPos($lineStruct->table, "_") + 1, $this->len($lineStruct->table));
                                        $type   = $this->getFieldType($lineStruct->type);
                                        $length = "(".$lineStruct->length.")";   
                                        if($type == "boolean")   {$length = "";}
                                        $aStruct[] = array("table"=>$_table, "name"=>$lineStruct->name, "type"=>$type.$length);                                
                                }                        
                        }                
               
                } else {$aStruct = $_SESSION[$table."Structure"];}        
                return($aStruct);
        }
        
        //getFieldType(
        private function getFieldType($type)   {
        
                $fieldTypes[] = array("type"=>"boolean",    "value"=>1);
                $fieldTypes[] = array("type"=>"smallint",   "value"=>2);
                $fieldTypes[] = array("type"=>"int",        "value"=>3);
                $fieldTypes[] = array("type"=>"float",      "value"=>4);
                $fieldTypes[] = array("type"=>"double",     "value"=>5);
                $fieldTypes[] = array("type"=>"real",       "value"=>5);
                $fieldTypes[] = array("type"=>"timestamp",  "value"=>7);
                $fieldTypes[] = array("type"=>"bigint",     "value"=>8);
                $fieldTypes[] = array("type"=>"serial",     "value"=>8);
                $fieldTypes[] = array("type"=>"mediumint",  "value"=>9);
                $fieldTypes[] = array("type"=>"date",       "value"=>10);
                $fieldTypes[] = array("type"=>"time",       "value"=>11);
                $fieldTypes[] = array("type"=>"datetime",   "value"=>12);
                $fieldTypes[] = array("type"=>"year",       "value"=>13);
                $fieldTypes[] = array("type"=>"bit",        "value"=>16);
                $fieldTypes[] = array("type"=>"decimal",    "value"=>246);
                $fieldTypes[] = array("type"=>"text",       "value"=>252);
                $fieldTypes[] = array("type"=>"tinytext",   "value"=>252);
                $fieldTypes[] = array("type"=>"mediumtext", "value"=>252);
                $fieldTypes[] = array("type"=>"longtext",   "value"=>252);
                $fieldTypes[] = array("type"=>"tinyblob",   "value"=>252);
                $fieldTypes[] = array("type"=>"mediumblob", "value"=>252);
                $fieldTypes[] = array("type"=>"blob",       "value"=>252);
                $fieldTypes[] = array("type"=>"longblob",   "value"=>252);
                $fieldTypes[] = array("type"=>"varchar",    "value"=>253);
                $fieldTypes[] = array("type"=>"varbinary",  "value"=>253);
                $fieldTypes[] = array("type"=>"char",       "value"=>254);
                $fieldTypes[] = array("type"=>"binary",     "value"=>254);
                foreach($fieldTypes as  $field) {if( $type == $field["value"]) {$fieldTypeStruct = $field["type"]; break;}}
                return($fieldTypeStruct);        
        }

          //getPrefixFields(
        public function getPrefixFields($tableName, $__FILE__="", $__LINE__="")   {

                include($_SESSION["PROJECT_INFO"]);
                if(!strPos($tableName,"_")) {$tableName = $prx."_".$tableName;}
        
                $SQL = "SHOW COLUMNS FROM ".$tableName;
                if($this->len($__FILE__)==0)   {$__FILE__=__FILE__;}
                if($this->len($__LINE__)==0)   {$__LINE__=__LINE__;}
                $result = $this->call( $SQL, $__FILE__, $__LINE__);
                $line = MySQLi_Fetch_Array($result, MYSQLI_ASSOC );
                return(subStr($line["Field"], 0, strPos($line["Field"], "_" )));
        }
        
        //dropTables(
        public function dropTables($prefix, $__FILE__="", $__LINE__="")   {
                if(strLen(Trim($__FILE__))==0)   {$__FILE__=__FILE__;}
                if(strLen(Trim($__LINE__))==0)   {$__LINE__=__LINE__;}
                $aTables=$this->listAllTablesFromDb( $prefix );
                foreach($aTables as $table)   {
                        $SQL = "DROP TABLE IF EXISTS ".$table;
                        $this->call( $SQL, $__FILE__, $__LINE__  );
                }
                return($aTables);
        }
        
        //listAllTablesFromDb(
        public function listAllTablesFromDb($findString="", $prx="")  {
                $aReturnTables = array();
                $resTables = MySQLi_query($this->iCon, "SHOW TABLES");        
                while($table = MySQLi_fetch_array($resTables))  {
                        if(strLen(Trim($findString))==0) {
                                if(len($prx)==0)    {
                                        $aReturnTables[]=$table[0];
                                }   else    {
                                        if(strPos("_".$table[0], $prx)) {
                                                $aReturnTables[]=$table[0];
                                        }
                                }
                        }   else    {
                        
                                if(strLen(Trim($prx))==0)    {
                                        if(strPos("_".$table[0],$findString))   {
                                                $aReturnTables[]=$table[0];
                                        }                                
                                }   else    {
                                        if(strPos("_".$table[0], $prx)) {
                                                if(strPos("_".$table[0],$findString))   {
                                                        $aReturnTables[]=$table[0];
                                                }
                                        }
                                }
                        }
                }
                return($aReturnTables);
        }
        
        //alterTableAdd(
        public function alterTableAdd($tableName, $fieldName, $fieldType, $fieldLength=0, $after, $__FILE__, $__LINE__, $showSQL=false)    {
        
                if(strLen(Trim($__LINE__)) == 0) {$__LINE__ = __LINE__;}
                if(strLen(Trim($__FILE__)) == 0) {$__FILE__ = __FILE__;}
                
                if(strLen(Trim($this->findInStructure($tableName, $fieldName, $__FILE__, $__LINE__)))>0) {return(null);}
        
                include($_SESSION["PROJECT_INFO"]);
                $length="";
                switch($fieldType)  {
                        case "varchar":  {$length="(".$fieldLength.")"; break;}
                }
        
                $SQL = "ALTER TABLE ".$prx."_".$tableName." ADD ".$fieldName." ".strToUpper($fieldType).$length." NOT NULL AFTER ".$after;
                if($showSQL) {d($__FILE__." / ".$__LINE__); d($SQL);}
                $this->call( $SQL, $__FILE__, $__LINE__);
                
                return(true);
        }

        //findInStructure(
        public function findInStructure($tableName, $fieldName, $__FILE__="", $__LINE__ ="")   {
        
                if(strLen(Trim($__FILE__))==0) {$__FILE__ = __FILE__;}
                if(strLen(Trim($__LINE__))==0) {$__LINE__ = __LINE__;}
                
                $aStuct =   $this->structureFromTable(  $tableName, $__LINE__,   $__FILE__  );
                
                $existType="";
                foreach($aStuct as  $field) {   if($field["name"]==$fieldName)  {$existType=$field["type"];  break;} }
                return($existType);                                                                         
        }


} 

?>