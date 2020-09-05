<?
        $aMsg = array("Naozaj chcete zmazať už zaslanú správu  ?");
        $aAddMsg = array("Tento krok môže späť vrátiť len správca db !!!");
        $aYesButton = array("delete_formChat");
        bsModal(array("deleteMessage", "clickDel()"), $aMsg, $aAddMsg, $aYesButton);
?>
        <link rel="stylesheet" href="../css/chat.css">

        <button id="openMyForm" class="btn btn-primary mt-1 open-button" style="">Otvoriť diskusiu</button>
        <div class="chat-popup" id="myForm">
    
        <form id="formChat" method="post" action="" class="form-container">
                <div class="msgHead">Na chate komunikujete ako:</div>
                <div class="msgHead msgUser" >
                        <?if(is_file("persons/person".$_SESSION["SYS_ACCOUNT"]["acc_id"].".png")) {?>
                        <img class="rounded-circle userChat-photo" src="/persons/person<?=$_SESSION["SYS_ACCOUNT"]["acc_id"]?>.png" />
                        <?}?>
                        &nbsp;
                        <?=$_SESSION["SYS_ACCOUNT"]["acc_surName"]."&nbsp;".$_SESSION["SYS_ACCOUNT"]["acc_name"]?>
                </div>
<?
                if($_SESSION["SYS_ACCOUNT"]["acc_chatAdmin"]==1) {       
                        $aFieldNames = array("acc_id", "acc_name", "acc_surName"); 
                        $where="acc_id!='".$_SESSION["SYS_ACCOUNT"]["acc_id"]."' && acc_chatAdmin=0 ORDER BY acc_surName, acc_name ASC";
                        $oOtherUsers = $db->get("OTHERUSERS", "sysUserAccounts", $aFieldNames, $where, false, __FILE__, __LINE__);
                }
                
                if($_SESSION["SYS_ACCOUNT"]["acc_chatAdmin"]==0) {
                        $aFieldNames = array("acc_id", "acc_name", "acc_surName"); 
                        $where="acc_id!='".$_SESSION["SYS_ACCOUNT"]["acc_id"]."' && acc_chatAdmin=1 ORDER BY acc_surName, acc_name ASC";
                        $oOtherUsers = $db->get("CHATADMINS", "sysUserAccounts", $aFieldNames, $where, false, __FILE__, __LINE__);
                }
                
                if(count($oOtherUsers)>1) {
?>
                <div class="d-flex justify-content-center msgHead msgUser msgSel mt-2" style="height:43px;">
                        s:&nbsp;
                        <div class="" style="width:250px;height:42px;">
                        <select id="otherUsers" name="otherUsers" onchange="setOtherUsers()">
                        <option value=""></option>
<?
                        foreach($oOtherUsers as $user) {
                        $selected=''; if($_POST["otherUsers"]==$user["acc_id"]) {$selected='selected="selected"';}
                        ?><option <?=$selected?> value="<?=$user["acc_id"]?>"><?=$user["acc_surName"]."&nbsp;".$user["acc_name"]?></option><?
                        }
?>
                        </select>
                        <input type="hidden" id="existSelectOther" name="existSelectOther" value="" />
                        <input type="hidden" id="setNewOther" name="setNewOther" value="" />
                        </div>
                        <script>$(document).ready(function() {$('#otherUsers').niceSelect();});</script>
                </div>
<?                                                                            
                } else {
                        if(count($oOtherUsers)==1) {
                                $oOtherUsers = $oOtherUsers[0];} else {
                                $oOtherUsers = $sys->arrayIn($_SESSION["USER_ALL_ACCOUNT"],'$acc_admin="'.$chatAdminID.'"', true);
                        }
                        ?><input type="hidden" id="otherUsers" name="otherUsers" value="<?=$oOtherUsers["acc_id"]?>">
                        
                 <div class="d-flex justify-content-center msgHead msgUser one mt-2 " style="height:25px;">
                        s:&nbsp;<?=$oOtherUsers["acc_surName"]."&nbsp;".$oOtherUsers["acc_name"]?>
                 </div>
<?
                }               
?>
                <div id="messages-in"></div>
                <textarea placeholder="Napíšte správu..." style="height:60px;" class="" id="message-set-new" required></textarea>
                <input type="button" id="sendMsg" disabled class="d-none btn btn-success mt-0 btn-action" value="Zaslať správu" />
                <input type="button" id="sendMsgAll" disabled class="d-inline btn btn-danger mt-0 btn-action" value="Zaslať správu všetkým" />
                <button type="button" id="closeMyForm" class="btn btn-primary mt-1 btn-action">Zatvoriť diskusiu</button>
                
                <input type="hidden" id="setDel_formChat" name="setDel_formChat" value="" />
                <input type="hidden" id="chatCount" name="chatCount" value="<?=$_POST["chatCount"]?>" />
        </form>
        </div>
<?

?>
        
<script>

$(document).ready(function() {
 
        $("#closeMyForm").click(function() {resetButtonOpen(); $("#myForm").hide();});
        $("#closeMyForm").keyup(function() {resetButtonOpen(); $("#myForm").hide();});
        
        $("#openMyForm").click(function() {resetButtonOpen(); $("#myForm").show();});
        $("#openMyForm").keyup(function() {resetButtonOpen(); $("#myForm").show();});
        $("#messages-in").scrollTop(10000); 
        
        $("#message-set-new").keyup(function() {
                var newMsg = $("#message-set-new").val().trim();
                if(newMsg.length>0) {
                        $("#sendMsg").removeAttr('disabled');
                        $("#sendMsgAll").removeAttr('disabled');
                } else {
                        $("#sendMsg").attr('disabled','disabled');
                        $("#sendMsgAll").attr('disabled','disabled');
                }
        });               
        
        $("#sendMsg").keyup(function() {sendMess(true);});
        $("#sendMsg").click(function() {sendMess(true);});
        $("#sendMsgAll").keyup(function() {sendMess(true);});
        $("#sendMsgAll").click(function() {sendMess(true);});
       
       $("#sendMsg").removeClass("d-inline");
       $("#sendMsgAll").removeClass("d-inline");
       $("#sendMsg").removeClass("d-none");
       $("#sendMsgAll").removeClass("d-none");

        if(document.getElementById("existSelectOther")) {
                $("#sendMsg").addClass("d-none");
                $("#sendMsgAll").addClass("d-inline");
        } else {
                $("#sendMsg").addClass("d-inline");
                $("#sendMsgAll").addClass("d-none");
        }
        
        sendMess(); refresh('<?=$chatRefresh?>');
        responsiveOpenButton();
        $("#messages-in").scrollTop(10000);
});

function resetButtonOpen() {
        $("#openMyForm").removeClass("btn-primary");
        $("#openMyForm").removeClass("btn-danger");
        $("#openMyForm").addClass("btn-primary");        
        $("#openMyForm").html('Otvoriť diskusiu');
        $("#chatCount").val(""); 
        $("#messages-in").scrollTop(10000);
}

function refresh(delayTime) {
        setTimeout(function(){sendMess(); refresh(delayTime);}, 20000);
}

function sendMess(sendMsg=false) {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {if (this.readyState == 4 && this.status == 200) {
                $("#messages-in").html(this.response);
                $("#messages-in").scrollTop(10000); 
        }}
        if(sendMsg) {
                xmlhttp.open("POST", "../chatContent.php?new="+$("#message-set-new").val()+"&other="+$("#otherUsers").val(), true);
                $("#message-set-new").val("");
                $("#sendMsg").attr('disabled','disabled');
                $("#sendMsgAll").attr('disabled','disabled');
        } else {
                xmlhttp.open("POST", "../chatContent.php?other="+$("#otherUsers").val(), true);
        }
        xmlhttp.send();
}

function setOtherUsers() {
        if($("#otherUsers").val().length>0) {
                $("#sendMsgAll").removeClass("d-inline");
                $("#sendMsgAll").addClass("d-none");        
                $("#sendMsg").removeClass("d-none");
                $("#sendMsg").addClass("d-inline");
                $("#sendMsg").val("Zaslať " + $("#otherUsers option:selected").text());
                
                
        } else {
                $("#sendMsgAll").removeClass("d-none");
                $("#sendMsgAll").addClass("d-inline");        
                $("#sendMsg").removeClass("d-inline");
                $("#sendMsg").addClass("d-none");
        }
        sendMess();
}

function clickDel() {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {if (this.readyState == 4 && this.status == 200) {
                $("#messages-in").html(this.response);
                $("#messages-in").scrollTop(10000);         
        }}
        xmlhttp.open("POST", "../chatContent.php?other="+$("#otherUsers").val()+"&deleteID="+$("#setDel_formChat").val(), true);
        xmlhttp.send();
}

function setChatHidden(deleteID) {
        $("#setDel_formChat").val(deleteID);
}

function responsiveOpenButton() {

        if($(window).height()<641 && $(window).height()>600) {
                $('#message-set-new').css('min-height','80px'); 
                $("#messages-in").height(310);
        }
        if($(window).height()<601 && $(window).height()>568) {
                $('#message-set-new').css('min-height','60px'); 
                $("#messages-in").height(300);
                
        }
        if($(window).height()<569 && $(window).height()>533) {
                $('#message-set-new').css('min-height','50px'); 
                $("#messages-in").height(220);

        }
        if($(window).height()<534 && $(window).height()>480) {
                $('#message-set-new').css('min-height','50px'); 
                $("#messages-in").height(230);
        }
        if($(window).height()<481 && $(window).height()>361) {
                $('#message-set-new').css('min-height','30px'); 
                $("#messages-in").height(200);
                                                    
        }        

        if($(window).height()<361) {
                $('#message-set-new').css('min-height','30px'); 
                $("#messages-in").height(85);
        }
        
        if($(window).width()<415) {
                $('.open-button').css('right','25px');
                $('.open-button').css('width','330px');
                
        }

        
        if($(window).width()<376) {
                
                $("#messages-in").height(290);
                $('.open-button').css('right','23px');
                $('.open-button').css('width','325px');
        
        }
        
        if($(window).width()<361) {
                $('#message-set-new').css('height','50px'); 
                $("#messages-in").height(240);
                $('.open-button').css('right','22px');
                $('.open-button').css('width','310px');
        }
        
        
        if($(window).width()<321) {
                
                $('#message-set-new').css('height','10px'); 
                $("#messages-in").height(170);
                $('.open-button').css('right','25px');
                $('.open-button').css('width','265px');
        }
}
        

</script>

