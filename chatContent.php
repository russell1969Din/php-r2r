<?session_start();?>
<?
        if(strLen(Trim($_SESSION["SYS_ACCOUNT"]["acc_id"]))==0) {die();}
        include("service.php");
        include("library.php");

        include($_SESSION["PROJECT_INFO"]);
        $db  = _constructClass("db");
        $sys = _constructClass("sys");
        
        $info = date("Y-m-d").".info";
        if($chatDeleteDays>0 && !is_file($info)) {
                $serverDir = scandir( $_SESSION["SYSTEM_ROOT"]);
                foreach ($serverDir as $key => $value) {
                        if(strPos($value,".info")) {unlink($value);}
                } 
                $handle = fopen($info, "w+a+"); fclose($handle);
                $oTest = $db->get("TEST", "chatTable", "chat_id", "TO_DAYS(CURDATE())-TO_DAYS(chat_timeSend)>".$chatDeleteDays, true, __FILE__, __LINE__);
                foreach($oTest as $test) {
                        $db->delete("chatTable", intVal($test["chat_id"]), __FILE__, __LINE__);
                }
        }

        if(strLen(Trim($_GET["deleteID"]))>0) {
                $db->update("chatTable", "chat_delete", 1, "chat_id='".$_GET["deleteID"]."'", __FILE__, __LINE__);
        }

        $loginId    = $_SESSION["SYS_ACCOUNT"]["acc_id"];
        $chatAdmin  = $_SESSION["SYS_ACCOUNT"]["acc_chatAdmin"];
        $db->update("sysUserAccounts", "acc_chatLogin", date("Y/m/d H:i:s"), "acc_id='".$loginId."'", __FILE__, __LINE__);

        $aSendMailUsers  = array();
        if(strLen(Trim($_GET["new"]))>0) {
                $aValues = array();
                if(strLen(Trim($_GET["other"]))==0) {
                        if($chatAdmin) {
                                $oOther = $sys->arrayIn($_SESSION["USER_ALL_ACCOUNT"], '$acc_id!="'.$loginId.'"');
                        } else {
                                $oOther = $_SESSION["CHATADMINS"];
                        }
                        foreach($oOther as $other) {
                                $aSendMailUsers[]  = $other["acc_id"];
                                $aValues[] = array("chat_userFrom"=>$loginId, "chat_userTo"=>$other["acc_id"], "chat_message"=>$_GET["new"], "chat_timeSend"=>date("Y/m/d H:i:s"));
                        }        
                } else {
                        $aSendMailUsers[]  = $_GET["other"];
                        $aValues[] = array("chat_userFrom"=>$loginId, "chat_userTo"=>$_GET["other"], "chat_message"=>$_GET["new"], "chat_timeSend"=>date("Y/m/d H:i:s"));
                }
                $aId = $db->insert("chatTable", $aValues, false, __FILE__, __LINE__, false);
        }

        if(strLen(Trim($_GET["other"]))==0) {
                $where= "(chat_userFrom='".$loginId."' or chat_userTo='".$loginId."') ".
                        "ORDER BY chat_timeSend ASC";
        } else {
                $where= "(chat_userFrom='".$loginId."' and chat_userTo='".$_GET["other"]."') or ".
                        "(chat_userFrom='".$_GET["other"]."' and chat_userTo='".$loginId."') ".
                        "ORDER BY chat_timeSend ASC";
        }
        $oChatMessage = $db->get("CHATMSG", "chatTable", null, $where, true, __FILE__, __LINE__);
        
        for($i=0;$i<count($oChatMessage);++$i) {
                $aChatLine = $oChatMessage[$i];
                if($_SESSION["SYS_ACCOUNT"]["acc_chatAdmin"]) {
                        if($_SESSION["SYS_ACCOUNT"]["acc_id"] == $aChatLine["chat_userTo"]) {
                                $aAccount   = $sys->arrayIn($_SESSION["USER_ALL_ACCOUNT"], '$acc_id=="'.$aChatLine["chat_userFrom"].'"', true);
                        } else {
                                $aAccount   = $sys->arrayIn($_SESSION["USER_ALL_ACCOUNT"], '$acc_id=="'.$aChatLine["chat_userTo"].'"', true);
                        }
                } else {
                        $aAccount = $db->get("USERFROM", "sysUserAccounts", null, "acc_id='".$aChatLine["chat_userFrom"]."'", true, __FILE__, __LINE__, true);
                }
                $aDT = dateTimeToArray($aChatLine["chat_timeSend"]);
                $otherUser  = "Zaslal ".$aAccount["acc_surName"]."&nbsp;".$aAccount["acc_name"]." dňa ".$aDT["dd"]."-".$aDT["mm"]."-".$aDT["rr"]." v čase ".$aDT["hh"].":".$aDT["mn"].":".$aDT["ss"];
                
                $photoName = "img/person0.png";
                if(is_file($photoPersons."/person".$aAccount["acc_id"].".png")) {
                        $photoName = $photoPersons."/person".$aAccount["acc_id"].".png";
                }
                
                $photo = true;
                $timeStamp = "";
                if(count($oChatMessage)>($i-1)) {
                        $tempLine = $oChatMessage[$i+1];
                        if(($aChatLine["chat_userFrom"]==$tempLine["chat_userFrom"]) && ($aChatLine["chat_userTo"]==$tempLine["chat_userTo"])) {
                                $photo = !$photo;
                        } 
                }

                if($i==0) {
                        $timeStamp = $aDT["dd"].". ".$aDT["mm"].". ".$aDT["rr"].", ".$aDT["hh"].":".$aDT["mn"];
                } 
                
                if($i>0) {
                        $tempLine = $oChatMessage[$i-1];
                        if(($aChatLine["chat_userFrom"]!=$tempLine["chat_userFrom"]) || ($aChatLine["chat_userTo"]!=$tempLine["chat_userTo"])) {
                                $timeStamp = $aDT["dd"].". ".$aDT["mm"].". ".$aDT["rr"].", ".$aDT["hh"].":".$aDT["mn"];
                        }
                }
                
                $toUser = "";
                if(strLen(Trim($timeStamp))>0 && strLen(Trim($_GET["other"]))==0 && $_SESSION["SYS_ACCOUNT"]["acc_id"] != $aChatLine["chat_userTo"]) {
                        if($chatAdmin) {
                                $aDelivery = $sys->arrayIn($_SESSION["USER_ALL_ACCOUNT"], '$acc_id=="'.$aChatLine["chat_userTo"].'"', true);
                        } else {
                                $aDelivery = $sys->arrayIn($_SESSION["CHATADMINS"], '$acc_id=="'.$aChatLine["chat_userTo"].'"', true);
                        }
                        
                        $toUser = "pre ".$aDelivery["acc_surName"]." ".$aDelivery["acc_name"];
                } 
                $isCurrentLogin = false;
                if($_SESSION["SYS_ACCOUNT"]["acc_id"] != $aChatLine["chat_userFrom"]) {
                        $oOnline = $db->get("GETLOGIN", "sysUserAccounts", "acc_chatLogin", "acc_id='".$aChatLine["chat_userFrom"]."'", true, __FILE__, __LINE__, true);
                        $datetime = date("Y-m-d H:i:s");
                        $timestamp = strtotime($datetime);
                        $time = $timestamp - ($chatRefresh/1000);
                        $datetime = date("Y-m-d H:i:s", $time);                
                        if((strtotime($datetime)-strtotime($oOnline["acc_chatLogin"]))<(($chatRefresh/1000)-1)) {
                                $isCurrentLogin = true;
                        } else {
                                $isCurrentLogin = false;
                        }
                }
                messageLine($aChatLine, $otherUser, $photo, $timeStamp, $toUser, $photoName, $isCurrentLogin);
        
        } 

        if(count($oChatMessage)==0 && !$chatAdmin) {
        $photoName = $photoPersons."/person".$chatAdminID.".png";
        if(!is_file($photoName))  {$photoName = $photoPersons."/person0.png";}
        $isAdminLogin = false;
        $oAdmin = $db->get("GETADMIN", "sysUserAccounts", "acc_chatLogin", "acc_id='".$chatAdminID."'", true, __FILE__, __LINE__, true);
        $dtAdmin = date("Y-m-d H:i:s");
        $timestamp = strtotime($dtAdmin);
        $time = $timestamp - ($chatRefresh/1000);
        $dtAdmin = date("Y-m-d H:i:s", $time);                
        if((strtotime($dtAdmin)-strtotime($oAdmin["acc_chatLogin"]))<(($chatRefresh/1000)-1)) {
                $isAdminLogin = true;
        }
        
?>
        <div class="userMsg-newLine">
                <div class="mt-2 d-flex justify-content-center userPerson-in ">
                        <img style="position: static;z-index:0;" class="rounded-circle userPerson-photo ml-2" src="/<?=$photoName?>" data-toggle="tooltip" title="<?=$otherUser?>" />
                        <?if($isAdminLogin) {?>
                        <i style="" class="fas fa-circle userPerson-online" data-toggle="tooltip" title="Užívateľ je na chate"></i>                        
                        <?}?>
                </div>         
                <div class="ml-2 mt-2 p-1 userMsg-start ">
                        Ako správca systému som pripravený odpovedať na všetky Vaše otázky !
                </div>
        </div>
<?    
    
        }
          
        foreach($aSendMailUsers as $userID)  {
                $aFields = array("acc_chatLogin", "acc_chatMail", "acc_email");
                $aUser = $db->get("MAILUSER", "sysUserAccounts", $aFields, "acc_id='".$userID."'", true, __FILE__, __LINE__, true);
                $userFrom = $_SESSION["SYS_ACCOUNT"]["acc_surName"]." ".$_SESSION["SYS_ACCOUNT"]["acc_name"];
                if($aUser["acc_chatMail"]) {
                        $dt = date("Y-m-d H:i:s");
                        $timestamp = strtotime($dt);
                        $time = $timestamp - ($chatRefresh/1000);
                        $dt = date("Y-m-d H:i:s", $time); 
                        if((strtotime($dt)-strtotime($oOnline["acc_chatLogin"]))<(($chatRefresh/1000)-1)) {} else {
                                $subject = "V diskusii projektu ".$alterBaseURL." máte neprečítanú správu od ".$userFrom.".";
                                $body = $_GET["new"]."<br /><br />Na tento mail prosím neodpovedajte !";
                                sendMailFromServer($aUser["acc_email"], $subject, $body);
                        }
                }
        }

        function messageLine($aChatLine, $otherUser, $photo, $timeStamp, $toUser, $photoName, $isCurrentLogin) {

        if($aChatLine["chat_userFrom"]!=$_SESSION["SYS_ACCOUNT"]["acc_id"]) {
?>
       <div class="userMsg-newLine">
                <?if(strLen(Trim($timeStamp))>0) {?>
                <div class="msg-timeStamp text-center pt-1">
                        <?=$timeStamp?>
                </div>
                <?}?>
                <?if(strLen(Trim($toUser))>0) {?>
                <div class="msg-timeStamp text-center">
                        <?=$toUser?>
                </div>
                <?}?>
                <?$personOut="userPerson-in"; if(!$photo) {$personOut="userPerson-noPhoto";}?>
                <div class="mt-2 d-flex justify-content-center <?=$personOut?> ">
                        <?if($photo) {?>
                        <img onclick="selectedPerson(<?=$aChatLine["chat_userFrom"]?>)" style="cursor:pointer;position: static;z-index:0;" class="rounded-circle userPerson-photo ml-2" src="/<?=$photoName?>" data-toggle="tooltip" title="<?=$otherUser?>" />
                        <?if($isCurrentLogin) {?>
                        <i style="" class="fas fa-circle userPerson-online" data-toggle="tooltip" title="Užívateľ je na chate"></i>                        
                        <?}?>
                        <?}?>
                </div>                    
                <?if($aChatLine["chat_delete"]) {?>
                <div class="mr-3 mt-2 p-1 userMsg-in-del" >
                        Správa vymazaná !
                </div>
                <?} else {?>
                <div class="ml-2 mt-2 p-1 userMsg-in ">
                        <?=$aChatLine["chat_message"]?>
                </div>
                <?}?>
        </div>
<?                        
        }  else {
?>
        <div class="userMsg-newLine">
                <?if(strLen(Trim($timeStamp))>0) {?>
                <div class="msg-timeStamp text-center pt-1">
                        <?=$timeStamp?>
                </div>
                <?}?>  
                <?if(strLen(Trim($toUser))>0) {?>
                <div class="msg-timeStamp text-center">
                        <?=$toUser?>
                </div>
                <?}?>             
                <div class="mt-2 d-flex justify-content-center userPerson-out"></div>
                <?if($aChatLine["chat_delete"]) {?>
                <div class="mr-3 mt-2 p-1 userMsg-out-del" >
                        Správa vymazaná !
                </div>
                <?} else {?>
                <div class="mr-3 mt-2 p-1 userMsg-out" >
                        <span data-toggle="modal" data-target="#deleteMessage">
                        <i onclick="setChatHidden('<?=$aChatLine["chat_id"]?>')" class="fas fa-eye-slash msgDelete" data-placement="bottom" title="Vymazať už zaslaný príspevok" data-toggle="tooltip"></i>
                        </span>&nbsp;
                        <?=$aChatLine["chat_message"]?>  
                </div>
                <?}?>
        </div>
<?
        }
        
        }
?>
<script>
$(document).ready(function() {   
        if($('#myForm').css('display') == "none") {
                var chatCount = '<?=count($oChatMessage)?>';
                if($("#chatCount").val().length==0) {
                        $("#chatCount").val(chatCount);
                } else {
                        if($("#chatCount").val().length>0) {
                                if(chatCount>$("#chatCount").val()) {
                                        $("#openMyForm").removeClass("btn-primary");
                                        $("#openMyForm").addClass("btn-danger");        
                                        $("#openMyForm").html('<i class="far fa-envelope"></i>&nbsp;&nbsp;&nbsp;Máte novú správu');
                                }
                        }
                }
        } 
});

function selectedPerson(id) {
        if(document.getElementById("existSelectOther")) {
                $('[name=otherUsers]').val(id);
                $("#otherUsers").niceSelect("update");
                $("#sendMsgAll").removeClass("d-inline");
                $("#sendMsgAll").addClass("d-none");        
                $("#sendMsg").removeClass("d-none");
                $("#sendMsg").addClass("d-inline");
                $("#sendMsg").val("Zaslať " + $("#otherUsers option:selected").text());
        }
}
</script>


