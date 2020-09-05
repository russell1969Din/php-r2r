<style>
video::-internal-media-controls-download-button {
    display:none;
}

video::-webkit-media-controls-enclosure {
    overflow:hidden;
}

video::-webkit-media-controls-panel {
    width: calc(100% + 30px); /* Adjust as needed */
}
</style>


<?
        $aURI = explode("/",$_SERVER['REQUEST_URI']);
        $aTables = array("generalMenu", "contentSites");
        $aFields = array("con_path", "con_videoName", "men_id");
        $where   = "men_id=con_idMenu && men_path='".$aURI[count($aURI)-1]."'";
        $oVideo = $db->get("VIDEO", $aTables, $aFields, $where, true, __FILE__, __LINE__, true);
        $error = false;
        if(strLen(Trim($oVideo["con_path"]))==0 && strLen(Trim($oVideo["con_videoName"]))==0) {
?>
        <div class="container mt-5">
<?
        bsAlert("V danej časti nie je nastavené, aké video má systém zobraziť", 0);
        $error = true;
?>
        </div>
<?
        } else {
                if(strLen(Trim($oVideo["con_videoName"])) > 0) {$videoName = $oVideo["con_videoName"];} else {$videoName = "asasasaasas";}
                if(strLen(Trim($oVideo["con_videoName"]))>0 && is_file($_SESSION["SERVER_PATH_VIDEO"]."/".$oVideo["con_videoName"])) {
                        $sourceVideo = $_SESSION["DOMAIN_PATH_VIDEO"]."/".$oVideo["con_videoName"];
                } elseif (strLen(Trim($oVideo["con_path"]))>0 && !strPos("~".$oVideo["con_path"], $videoName)) {
                        $sourceVideo = $oVideo["con_path"];
                } else {
                
                        if(!$error) {bsAlert("Video napriek nastaveniu fyzicky na serveri neexistuje", 0);}
                        $error = true;
                }
                $unc = unc($sourceVideo);
        }
        $aAnnexesVideo = explode("|", $annexesVideo);
        $iFrame = 1;
        foreach($aAnnexesVideo as $ext) {
                if(strPos(strToLower($sourceVideo), ".".strToLower($ext))) {
                        $iFrame = 0;
                        break;
                }
        }
        
        $aAnnexesImage = explode("|", $annexesImage);
        foreach($aAnnexesImage as $ext) {
                if(strPos(strToLower($sourceVideo), ".".strToLower($ext))) {
                        $iFrame = 2;
                        break;
                }
        }


        
        if(strLen(Trim($sourceVideo))>0)  {
?>
        
        <div id="mediaDesk" class="container d-flex justify-content-center ">

        </div>
<?      
          
        } else {
                if(!$error) {bsAlert("V nastaveniach videa je neznáma chyba", 0);}
        }       
        
        $lcEditorContent = "";
        $lcScript = "lc-scripts/scripts_". $oVideo["men_id"].".html";
        if(is_file($lcScript)) {
                $handle = fopen($lcScript, "r+");
                $lcEditorContent = fread($handle, filesize($lcScript));
                fclose($handle);
        }
        if(strLen(Trim($lcEditorContent))>0) {
?>
        <div id="lcEditorContent" class="container mt-5">
<?
        echo($lcEditorContent);
?>        
        </div>
<?
        }
        
        $_SESSION["REQUEST_URI"] = $_SERVER["REQUEST_URI"];
        //if(getType($_SESSION[$_SESSION["SYSTEM_PHP_INTERFACE"]]) == "NULL") {
                $random = randomString(20);
                $_SESSION["SYSTEM_PHP_INTERFACE"] = $random;
                $_SESSION[$random] = $random;
       // }
?>
<script>  
var iFrame = '<?=$iFrame?>';

if(iFrame==1) {
        $('<iframe />', {name:"exh",id:"exh",frameborder:"0",allowtransparency:"true",style:"width:100%;height:400px;"}).appendTo('#mediaDesk');
        var xmlhttp = new XMLHttpRequest();xmlhttp.onreadystatechange = function() {if (this.readyState == 4 && this.status == 200) {dec(this.response), $('#exh').attr('src', dec(this.response) );}};xmlhttp.open("POST", p+".php", true);xmlhttp.send();        
} 

if(iFrame==0) {
        var video = $('<video />', {
            id: 'video',oncontextmenu:"return false;",controls:true,controlsList:"nodownload",style: "width:90%;height:500px;"
        }); video.appendTo($('#mediaDesk'));
        var xmlhttp = new XMLHttpRequest();xmlhttp.onreadystatechange = function() {if (this.readyState == 4 && this.status == 200) {$('#video').attr('src', dec(this.response) );}};xmlhttp.open("POST", p+".php", true);xmlhttp.send();
}

if(iFrame==2) {
        $('#mediaDesk').prepend('<img style="width:50%;min-height:100px;" id="inImage" src="" />');
        var xmlhttp = new XMLHttpRequest();xmlhttp.onreadystatechange = function() {if (this.readyState == 4 && this.status == 200) {$('#inImage').attr('src', dec(this.response) );}};xmlhttp.open("POST", p+".php", true);xmlhttp.send();        
        /*
        var video = $('<video />', {
            id: 'video',oncontextmenu:"return false;",controls:true,controlsList:"nodownload",style: "width:100%;height:700px;"
        }); video.appendTo($('#mediaDesk'));
        var xmlhttp = new XMLHttpRequest();xmlhttp.onreadystatechange = function() {if (this.readyState == 4 && this.status == 200) {$('#video').attr('src', dec(this.response) );}};xmlhttp.open("POST", p+".php", true);xmlhttp.send();
        */
}
</script>
