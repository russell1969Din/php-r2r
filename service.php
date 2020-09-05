<?php
        function _constructClass($objectClassName, $aParamConstruct=null)    {
                eval("global \$".$objectClassName.";");
                /*
                ///home/nr019500/_sub/space         fermata.sk
                $publicABNetObject = "home/nr019500/public/calendar-calen.class.php";
                
                if($objectClassName == "core") {
                        d($publicABNetObject);
                        l(is_file($publicABNetObject));
                        include($publicABNetObject);
                }
                */
                $existClass = class_exists($objectClassName);
                if($existClass) {
                        eval('$getType = getType($'.$objectClassName.');');                                                     
                        //if($getType == "NULL")    {eval('$'.$objectClassName.' = new '.$objectClassName.'();');}
                        eval('$objectReturn = $'.$objectClassName.';');
                        if($objectClassName == "acc") {true;}
                        return($objectReturn);
                }
                $originalPath = getCWD();   
                $classDir = "classes";   
                chDir($_SESSION["SYSTEM_ROOT"]);
                $classSourceName="";
                if($dh = opendir($classDir)) {
                        while(($file = readdir($dh)) !== false) {if(strPos($file,"-".$objectClassName.".") > 0) {$classSourceName = $file; break;}}
                }
                if(strlen(trim($classSourceName)) == 0)  {d("B");return(null);}
                include($classDir."/".$classSourceName);
                chDir($originalPath);
                if(getType($aParamConstruct) == "array"  )    {
                        $comma = $params = ""; $first=true; 
                        foreach($aParamConstruct as $param) {
                                if(!$first) {$comma=", ";}  
                                $params .= $comma.'"'.$param.'"'; 
                                $first = false;    
                        }
                }
                if(getType($aParamConstruct) == "string"  )    {
                        $params = '"'.$aParamConstruct.'"';   
                }
                if(!class_exists($objectClassName)) {d("A");return(null);}
                eval('$'.$objectClassName.' = new '.$objectClassName.'('.$params.');');
                eval('$objectReturn =   $'.$objectClassName.';');
                return($objectReturn);
        }
        
 ?>