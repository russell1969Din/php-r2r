<?php

        function dec($param, $takeAsc=3) {
                $in = 1;
                $aReverse = array();
                $reverse  = "";
                for($i=0;$i<strLen(Trim($param));++$i) {
                        $chr .= substr($param,$i,1);
                        ++$in;
                        if($in==($takeAsc)) {$in=1;$item = reverseWord($chr); $aReverse[] = $item;$chr="";}
                }

                for($i=0;$i<=count($aReverse);++$i) {
                        $revItem = "";
                        for($j=0;$j<strLen($aReverse[$i]);++$j) {
                                $chr = subStr($aReverse[$i], $j, 1);
                                $revItem .= chr(ord($chr)-$takeAsc);
                                
                                //$revItem .= $chr;
                                 
                        }
                        
                        $reverse .= $revItem;
                }
                return($reverse);
        }

        function unc($param, $addAsc=3) {
                $in = 1;
                $aReverse = array();
                $reverse  = "";
                $len = "";
                for($i=0;$i<=strLen(Trim($param));++$i) {
                        $chr .= substr($param,$i,1);
                        ++$in;
                        $go = false;
                        if($in==($addAsc+1)) {$in=1; $go = true; $item = reverseWord($chr); $aReverse[] = $item; $chr="";}
                                                
                }
                if(!$go) {$item = reverseWord($chr); $aReverse[] = $item; }
                
                for($i=0;$i<=count($aReverse);++$i) {
                        $revItem = "";
                        for($j=0;$j<strLen($aReverse[$i]);++$j) {
                                $chr = subStr($aReverse[$i], $j, 1);
                                $revItem .= chr(ord($chr)+$addAsc);
                                //d($revItem." :: ".$chr);
                        }
                        $reverse .= $revItem;
                }
                return($reverse);
        }
        
        function reverseWord($param) {
                $aTempo = array();
                for($i=0;$i<strLen($param);++$i) {$aTempo[] = substr($param,$i,1);}
                $reverse = "";           
                for($i=(count($aTempo)-1);$i>=0;--$i) {$reverse .= $aTempo[$i];}
                return($reverse);
        }
     
        function bsModal($aUniqueID, $aMsg, $aAddMsg="", $aYes=null, $aNo="Nie") {
                             
        if(getType($aNo)=="NULL") {$aNo="Nie";}
        $msg = "";
        $color = "danger"; $addColor = "default";
        
        if(getType($aUniqueID)!="string" && getType($aUniqueID)!="array") {return(1);}
        if(getType($aUniqueID)=="string") {$uniqueID=$aUniqueID;}
        if(getType($aUniqueID)=="array") {
                if(count($aUniqueID)==0) {return(13);}
                if(count($aUniqueID)>0) {$uniqueID=$aUniqueID[0];}
                if(count($aUniqueID)>1) {$function=$aUniqueID[1];}
        }
        
        if(getType($aMsg)!="string" && getType($aMsg)!="array") {return(2);}
        if(getType($aMsg)=="array") {
                if(count($aMsg)==0) {return(3);}
                if(count($aMsg)==1 && getType($aMsg[0])!="string") {return(4);} else {
                        $msg = $aMsg[0];
                }
        }
        if(getType($aMsg)=="string") {$msg=$aMsg;}
        if(getType($aMsg)=="array") {
                $msg=$aMsg[0]; 
                if(count($aMsg)>1)  {$color=$aMsg[1];}
        }

        if(getType($aAddMsg)!="string" && getType($aAddMsg)!="array" && getType($aAddMsg)!="NULL") {return(5);}
        if(getType($aAddMsg)=="array") {
                if(count($aAddMsg)==0) {return(6);}
                if(count($aAddMsg)==1 && getType($aAddMsg[0])!="string") {return(7);} 
        }
        if(getType($aAddMsg)=="string") {$addMsg=$aAddMsg;}
        if(getType($aAddMsg)=="array") {
                $addMsg = $aAddMsg[0]; 
                if(count($aAddMsg)>1) {$addColor=$aAddMsg[1];}
        }
        
        $aBsColors = array("primary", "secondary", "success", "danger", "warning", "info", "light", "dark",  "muted", "white");
        $_bsColor = $_bsAddcolor = $_color = $_addColor = "";
        foreach($aBsColors as $bsColor) {
                if(strToLower(Trim($color))==$bsColor)      {$_bsColor="text-".$bsColor;}
                if(strToLower(Trim($addColor))==$bsColor)   {$_bsAddColor="text-".$bsColor;}
        }
        if(strLen(Trim($_bsColor))==0) {$_color="color:".$color.";";}
        if(strLen(Trim($_bsAddColor))==0) {$_addColor="color:".$addColor.";";}
        
        if(getType($aNo)!="string" && getType($aNo)!="array") {return(8);}
        if(getType($aNo)=="array") {
                if(count($aNo)==0) {return(9);}
                if(count($aNo)==1 && getType($aNo[0])!="string") {return(9);} else {
                        $no = $aNo[0];
                }
        }
        $buttonNo="primary";
        if(getType($aNo)=="string") {$no=$aNo;}
        if(getType($aNo)=="array") {$buttonNo=$aNo[1]; $no=$aNo[0];}
                   
                   
        if(getType($aYes)!="array" && getType($aYes)!="string" && getType($aYes)!="NULL") {return(10);}
        $buttonYes="default"; $yes="Áno";
        if(getType($aYes)=="string") {$yesID  = $aYes;}
        if(getType($aYes)=="array") {
                if(count($aYes)<1) {return(11);}
                if(count($aYes)==1) {$yesID  = $aYes[0];}
                if(count($aYes)>1 && (getType($aYes[0])!="string" || getType($aYes[1])!="string")) {return(12);} else {
                        if(count($aYes)>1) {
                                $yesID  = $aYes[0];
                                $yes    = $aYes[1];
                                if(count($aYes)==3) {$buttonYes=$aYes[2];}
                        }
                }
        }
?>
        <div class="modal fade" id="<?=$uniqueID?>" role="dialog" >
                <div class="modal-dialog">
                        <div class="modal-content">
                                <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>
                                <div class="modal-body">
                                        <p>
                                        <span class="<?=$_bsColor?>" style="<?=$_color?>font-weight:bold;"><?=$msg?></span>
                                        <br />
<?
                                        if(getType($aAddMsg)!="NULL") {
?>
                                        <span class="<?=$_bsAddColor?>"style="<?=$_addColor?>"><?=$addMsg?></span>
<?                                        
                                        }
?>
                                        </p>
                                </div>
                                <div class="modal-footer">
<?
                                        if(getType($aYes)!="NULL") {
                                        if(strLen(Trim($function))==0) {$function="viewCaptchaModal(null,this)";}
?>
                                        <button type="button" id="<?=$yesID?>" class="btn btn-<?=$buttonYes?>" data-dismiss="modal" onclick="<?=$function?>"><?=$yes?></button>
<?
                                        }
?>                                
                                        <button type="button" class="btn btn-<?=$buttonNo?>" data-dismiss="modal"><?=$no?></button>
                                </div>
                        </div>
                </div>
        </div>
<?
        return(0);
        }
        
        function bsAlert($msg, $type=9, $color=null) {
        
        $_color="primary"; $_type="OZNAM";
        if(getType($type)=="integer") {
                switch($type) {
                        case 0:     {$_color="danger";  $_type="CHYBA"; break;}
                        case 1:     {$_color="success"; $_type="OK";    break;}
                        case 2:     {$_color="warning"; $_type="INFO";  break;}
                } 
        }
        $aBsAlertColors = array("success", "info", "warning", "danger");
        $isBootstrapColor = false;
        foreach($aBsAlertColors as $bsColor) {
                if(strToLower(Trim($bsColor))==strToLower(Trim($color))) {
                        $isBootstrapColor = !$isBootstrapColor;
                        break;
                }
        }
        if(getType($type)   != "string") {$type   = $_type;}
        if(getType($color)  != "string") {$color  = $_color;}
        if(!$isBootstrapColor) {$color  = $_color;}
?>
        <div class="container alert alert-<?=$color?> alert-dismissible text-center">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong><?=$type?> !&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</strong> <?=$msg?>.
        </div>
<?        
        }
        
        function g() {echo("== ".getCWD()." ==<br />");}
        
        function _g() {return("== ".getCWD()." ==");}
        
        function l($param) {if($param) {echo("== TRUE ==<br />");} else {echo("== FALSE ==<br />");} }
        
        function _l($param) {if($param) {return("== TRUE ==<br />");} else {return("== FALSE ==<br />");} }        

        function d($param) {echo("== ".$param." ==<br />");}
        
        function _d($param) {return("== ".$param." ==");}
        
        function t($param) {echo("== ".getType($param)." ==<br />");}
        
        function _t($param) {return("== ".getType($param)." ==");}

        function c($param) {

                if(getType($param) == "array") {
                        echo("== ".count($param)." ==<br />");
                } else {
                        echo('<span style="color:#cc0000;">== '.$param.' NIE JE ARRAY ! ==</span>');
                }
        }
        
        function _c($param) {
                if(getType($param) == "array") {
                        return("== ".count($param)." ==");
                } else {
                        return('<span style="color:#cc0000;">== '.$param.' NIE JE ARRAY ! ==</span>');
                }
        }
        
        function alert($msg) {?><SCRIPT>alert(<?="\"$msg\""?>)</SCRIPT><?}
        
        function  dateTimeToArray($dateTime, $format="SK") {
    
                $aDateTime  = Explode(  " ",  $dateTime);
                $aDate      = Explode(  "-",  $aDateTime[ 0 ]  );
                $aTime      = Explode(  ":",  $aDateTime[ 1 ]  );
        
                if($format == "SK") {
                        $aReturnValue = array(  "dd"=>$aDate[2],  "mm"=>$aDate[1],  "rr"=>$aDate[0],  "hh"=>$aTime[0],  "mn"=>$aTime[1],  "ss"=>$aTime[2]  );
                }
                
                if($format == "AM") {
                        $aReturnValue = array(  "dd"=>$aDate[0],  "mm"=>$aDate[1],  "rr"=>$aDate[2],  "hh"=>$aTime[0],  "mn"=>$aTime[1],  "ss"=>$aTime[2]  );
                }
                
        
                return( $aReturnValue );
        }
        
        function randomString($size=10) {
                $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $randString = '';
                for ($i = 0; $i <= ($size-1); $i++) {   $randString .= $characters[rand(0, strlen($characters))];    }
                return( $randString );
        }
        
        function folderSize($path) {
                $total_size = 0;
                $files = scandir($path);
                foreach($files as $t) {
                        if (is_dir(rtrim($path, '/') . '/' . $t)) {
                                    if ($t<>"." && $t<>"..") {
                                            $size = $this->foldersize(rtrim($path, '/') . '/' . $t);
                                            $total_size += $size;
                                    }
                        } else {
                                $size = filesize(rtrim($path, '/') . '/' . $t);
                                $total_size += $size;
                        }
                }   
                
                return $total_size;
        }
        
        function sendMailFromServer($to, $subject, $body) {
                include($_SESSION["PROJECT_INFO"]);
                $headers  =   "From: ".strip_tags($mailFrom)."\r\n";
                $headers .=  "Reply-To: ".strip_tags($mailFrom)."\r\n";
                $headers .=  "MIME-Version: 1.0\r\n";
                $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
                $headers .= "Content-Transfer-Encoding: 7bit\r\n";
                $headers .=  "MIME-Version: 1.0\r\n";
                mail($to, $subject, $body, $headers);
        }
        
        function isDemo() {
                include($_SESSION["PROJECT_INFO"]);
                if($demoVesion) {return(true);}
                return($_SESSION["SYS_ACCOUNT"]["acc_demo"]);
        }

        
?>