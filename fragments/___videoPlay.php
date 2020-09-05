<?
        $aURI = explode("/",$_SERVER['REQUEST_URI']);
        $aTables = array("generalMenu", "contentSites");
        $aFields = array("con_path", "con_videoName");
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
                if(strLen(Trim($oVideo["con_videoName"]))>0 && is_file($videoFolder."/".$oVideo["con_videoName"])) {
                        $sourceVideo = $_SESSION["DOMAIN_PATH_VIDEO"]."/".$oVideo["con_videoName"];
                        $source1 = substr($sourceVideo, 0, 5);
                        $source2 = substr($sourceVideo, 6, 5);
                        $source3 = substr($sourceVideo, 11, 5);
                        $source4 = substr($sourceVideo, 16,strLen($sourceVideo));
                        
                        
                } elseif (strLen(Trim($oVideo["con_path"]))>0) {
                        $sourceVideo = $oVideo["con_path"];
                        $source1 = substr($sourceVideo, strLen($sourceVideo)-4, strLen($sourceVideo));
                        d($source1);
                        $source2 = substr($sourceVideo, strLen($sourceVideo) - 9, 5);
                        d($source2);
                        $source3 = substr($sourceVideo, strLen($sourceVideo) - 14, 5);
                        d($source3);
                        $source4 = substr($sourceVideo, strLen($sourceVideo) - 19, 5);
                        d($source4);
                        $source5 = substr($sourceVideo, 0,strLen($sourceVideo)-19);
                        d($source5);
                }
                //$unc  = unc($sourceVideo);
                $unc1 = unc($source1);
                d(dec($unc1)." :: ".$source1);
                $unc2 = unc($source2);
                d($unc2." :: ".$source2);
                $unc3 = unc($source3);
                d($unc3." :: ".$source3);
                $unc4 = unc($source4);
                d($unc4." :: ".$source4);
                $unc5 = unc($source5);
                d($unc5." :: ".$source5);
        }
        
        if(strLen(Trim($sourceVideo))>0)  {
?>
        <div id="videoDesk" class="container text-center mt-5"></div>
<?        
        } else {
                if(!$error) {bsAlert("V nastaveniach videa je neznáma chyba", 0);}
        }
?>
<script>
var loc =  window.location.href;
var x = loc.substr(loc.indexOf(loc.length)-8,1)+loc.substr(loc.indexOf(loc.length)-4,1)+loc.substr(loc.indexOf(loc.length)-5,1)+loc.substr(loc.indexOf(loc.length)-1,1)+loc.substr(loc.indexOf(loc.length)-2,1)+loc.substr(loc.indexOf(loc.length)-9,1); 
alert(x)

$('<iframe />', {id:"exh",name:"<?=$sourceVideo?>",src:"",frameborder:"0",allowtransparency:"true",style:"width:100%;height:400px;"}).appendTo('#videoDesk');

   

//alert(String.fromCharCode(65) + String.fromCharCode(65) + String.fromCharCode(65));
//
//alert($('#exh').attr('name').indexOf(String.fromCharCode(33) + String.fromCharCode(36) + String.fromCharCode(37)));
//alert($('#exh').attr('name').substr(0,$('#exh').attr('name').indexOf(String.fromCharCode(1) + String.fromCharCode(1) + String.fromCharCode(1))));

//$('#exh').attr('src', $('#exh').attr('name'))

//$('$exh').


//dec("<?=$unc?>")
</script>