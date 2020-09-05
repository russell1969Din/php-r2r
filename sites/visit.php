<style>

.visitSites {
        font-family:verdana;
        font-size:15px;
        font-weight:700;
        height:30px;
        /*background-color:#7D7D7D;*/
        color:#006600;
        border-bottom:dotted 1px #006600;
}

.visitLine {
        font-family:verdana;
        font-size:15px;
        font-weight:700;
}

.evenVisitLine {
        background-color:#A8A8A8;
}

.visitColumn {
        height:40px;
        color:#1A5180;
}

.visitColumn a {
        color:#1A5180;
        text-decoration:underline;
}

.visitColumn a:hover {
        color:#1A5180;
        text-decoration:underline;
        cursor:pointer;
}

.visitOnlyMore .visitOnlyMoreIP {
        display:inline;
}

@media screen and (max-width: 600px) {
        .visitOnlyMoreIP {display:none;}
}
                   

@media screen and (max-width: 1024px) {
        .visitOnlyMore {display:none;}
}


.scrollList {
        float:left;
        width:60px;
        height:40px;
        padding-top:8px;
        text-align:center;
        font-size:16px;
        
}

.scrollList a:hover {
        cursor:pointer;
        text-decoration:underline;
}

.scrollCurrentList {
        padding-top:2px;
        font-size:22px;
        font-weight:700;
        color:#5080A8;
}

.scrollClear {
        clear:both;
}

.scrollDeskUp {
        border-bottom:dotted 2px #1A5180;
}

.scrollDeskDown {
        border-top:dotted 2px #1A5180;
}
</style>
<?
        $oFilterPers = $db->get("FILTER_PERSON", "sysUserAccounts", null, "1 ", false, __FILE__, __LINE__);

        $aTables    = array("sysVisitorsIn", "sysUserAccounts");
        $aFields    = array("vis_dateTime", "vis_ip", "vis_addr", "acc_id", "acc_name", "acc_surName", "vis_id");
        $addWhere = "";
        if($_SESSION["SYS_ACCOUNT"]["acc_admin"]!=1) {
                $addWhere = "acc_id = ".$_SESSION["SYS_ACCOUNT"]["acc_id"]." && ";
        }
        
        if($_POST["persons"] > 0) {
                $where      = $addWhere." acc_id = vis_idSysUser && acc_id=".$_POST["persons"]." ORDER BY vis_dateTime DESC ";
        } else {
                $where      = $addWhere." acc_id = vis_idSysUser ORDER BY vis_dateTime DESC ";
        }
        
        $oVisit     = $db->get("VISITORS", $aTables, $aFields, $where, true, __FILE__, __LINE__);
    
        $aUri = explode("/", $_SERVER['REQUEST_URI']);
        
        $personPath = str_replace("/".$aUri[count($aUri)-1], "/access", $_SESSION["URL_FULL"]);

        $screenHeight = $_SESSION["SCREEN_HEIGHT"];
        //$screenHeight = 1200;
        //d($screenHeight);
        $aParams = getParamsListing($screenHeight);

        
?>
<form id="visitForm" method="post">
<?
        if($_SESSION["SYS_ACCOUNT"]["acc_admin"]==1) {
?>
        <div class="container d-flex justify-content-center" style="width:300px;border-radius:5px;">
        <select id="persons" name="persons" class="nice-select "  onchange="hiddenSet('setPers_visitForm', 1, true)" style="">
                <option value=""></option>
<?
                foreach($oFilterPers as $pers) {
                $selected=''; if($_POST["persons"] == $pers["acc_id"]) {$selected=' selected="selected" '; }
?>
                <option <?=$selected?> value="<?=$pers["acc_id"]?>"><?=$pers["acc_surName"]." ".$pers["acc_name"]?></option>
<?
                }
?>
        </select>
        <input type="hidden" id="setPers_visitForm" name="setPers_visitForm" value="" /> 
        </div>
<?
        }
?>
        
        <script>$(document).ready(function() {$('#persons').niceSelect();});</script> 
<?
        if(count($oVisit) > 0) {
        $sitesNo = scrollTable($_POST["siteNo_visitForm"], $aParams["sitesNumber"], $aParams["linesOnSite"], count($oVisit));
?>
<input type="hidden" id="siteNo_visitForm" name="siteNo_visitForm" value="<?=$_POST["siteNo_visitForm"]?>" />

<div class="container mt-1">
<?
        $line=1; 
        $before = ($aParams["linesOnSite"]*($sitesNo-1));
        $firstCycle = true;
        $visitorSitesCount = "";
        for($index=0;$index<count($oVisit);++$index) {
                if($firstCycle && ($before>0)) {
                        $firstCycle = !$firstCycle;
                        $index += $before;
                }
                if($index>=count($oVisit)) {break;}
                $aVisit = $oVisit[$index];
                $even = ""; if($line%2==0)  {$even = "evenVisitLine";}
                $oVisitSites = $db->get("VISITSITES_".$aVisit["vis_id"], array("visitorInSite", "generalMenu"), null, "men_id=sit_idMenu && sit_idVisit=".$aVisit["vis_id"], false, __FILE__, __LINE__);
?>
                <div class="row visitLine visitColumn <?=$even?> ">
                        <div class="col1 col-xs-2 pt-3">
<?
                                if(count($oVisitSites) > 0) {
?>
                                <a onclick="openVisitSites(<?=$aVisit["vis_id"]?>)" title="Zobraziť navštívené časti stránky v uvedenom čase" data-toggle="tooltip">
<?
                                }
                                $aDate = dateTimeToArray($aVisit["vis_dateTime"], "SK");
                                echo($aDate["dd"]."-".$aDate["mm"]."-".$aDate["rr"]." ".$aDate["hh"].":".$aDate["mn"]);
                                if(count($oVisitSites) > 0) {
?>
                                </a>
<?
                                }
?>
                        </div>
                        <div class="col2 col-xs-3 pt-3" title="Otvoriť podrobnosti k prístupovému účtu" data-toggle="tooltip" >
<?
                        if($_SESSION["SYS_ACCOUNT"]["acc_admin"]==1) {
?>
                        <a href="<?=$personPath."?id=".$aVisit["acc_id"]?>">
<?                        
                        }
?>
                                <?echo($aVisit["acc_surName"]." ".$aVisit["acc_name"])?>
 <?
                        if($_SESSION["SYS_ACCOUNT"]["acc_admin"]==1) {
?>
                        </a>
<?                        
                        }
?>
                        </div>
                        <div class="col3 col-xs-2 pt-3 visitOnlyMoreIP">
                                <?echo($aVisit["vis_ip"])?>
                        </div>
                        <div class="col4 col-xs-5 pt-3 visitOnlyMore">
                                <?echo($aVisit["vis_addr"])?>
                        </div>
                </div>
<?
                if(count($oVisitSites)>0) {
                $visitorSitesCount .= $comma.$aVisit["vis_id"];
                $comma = "|";
?>
                <div class="row " id="siteVisit_<?=$aVisit["vis_id"]?>" style="display:none;">
<?
                displayVisitSites($oVisitSites);
?>
                </div>

<?
                }
                if($line == $aParams["linesOnSite"]) {break;}
                ++$line;
        }
        
?>
</div>
<?
        $sitesNo = scrollTable($_POST["siteNo_visitForm"], $aParams["sitesNumber"], $aParams["linesOnSite"], count($oVisit), false);
        }
?>
<input type="hidden" id="visitorSitesCount" name="visitorSitesCount" value="<?=$visitorSitesCount?>" />
</form>

<script>
        if($(window).width()<=1024 && $(window).width()>600) {
                $("div.col1").removeClass("col-xs-2");
                $("div.col1").addClass("col-xs-4");
                
                $("div.col2").removeClass("col-xs-3");
                $("div.col2").addClass("col-xs-5");
                

                $("div.col3").removeClass("col-xs-2");
                $("div.col3").addClass("col-xs-3");
        }

        if($(window).width()<=600 && $(window).width()>480) {
        
                $("div.col1").removeClass("col-xs-2");
                $("div.col1").addClass("col-xs-5");
                
                $("div.col2").removeClass("col-xs-3");
                $("div.col2").addClass("col-xs-7");
        
        }        
        if($(window).width()<=480 && $(window).width()>414) {
                $("div.col1").css("font-size", "15px");
                $("div.col2").css("font-size", "15px");  
                
                $("div.col1").removeClass("col-xs-2");
                $("div.col1").addClass("col-xs-5");
                
                $("div.col2").removeClass("col-xs-3");
                $("div.col2").addClass("col-xs-7");
        }

        if($(window).width()<=414) {
                $("div.col1").css("font-size", "11px");
                $("div.col2").css("font-size", "11px");  
                
                $("div.col1").removeClass("col-xs-2");
                $("div.col1").addClass("col-xs-5");
                
                $("div.col2").removeClass("col-xs-3");
                $("div.col2").addClass("col-xs-7");
        }
        
        if($(window).width()<=320) {
                $("div.col1").css("font-size", "9px");
                $("div.col2").css("font-size", "9px");
        }

        /*
        var x = y = 0;
        if($(window).width()<=414) { 
                $("div.col1").css("font-size", "11px");
                $("div.col2").css("font-size", "11px");
                if($(window).width()<=320) { 
                        $("div.col1").css("font-size", "9px");
                        $("div.col2").css("font-size", "9px");
                }
        }

        if($(window).width()>600) { 
                 $("div.col1").removeClass("col-xs-2");
                 $("div.col1").addClass("col-xs-4");
                 $("div.col2").removeClass("col-xs-3");
                 $("div.col2").addClass("col-xs-5");
                 $("div.col3").removeClass("col-xs-2");
                 $("div.col3").addClass("col-xs-3");
        }        

        if($(window).width()<=1023) {
                 $("div.col1").removeClass("col-xs-2");
                 $("div.col1").addClass("col-xs-5");
                 $("div.col2").removeClass("col-xs-3");
                 $("div.col2").addClass("col-xs-7");
                 y = 1;

        }
                
        if($(window).width()>=1366) {
                $("div.col1").addClass("col-xs-2");
                $("div.col2").addClass("col-xs-3");
                $("div.col3").addClass("col-xs-2");
                $("div.col4").addClass("col-xs-5");
        }
        */
        
        function openVisitSites(visId) {
                var aVisitorSitesCount = $("#visitorSitesCount").val().split("|");
                for(var i=0;i<aVisitorSitesCount.length;++i) {
                        $("#siteVisit_" + aVisitorSitesCount[i]).css("display","none");      
                }
                $("#siteVisit_" + visId).css("display","block");
        }   
</script>

<?
function getParamsListing($screenHeight) {
        //d($screenHeight);
        //if($screenHeight<=1280) {$aReturn = array("linesOnSite"=>1,  "sitesNumber"=>5 );}
        if($screenHeight<=1280) {$aReturn = array("linesOnSite"=>20, "sitesNumber"=>9 );}
        if($screenHeight<=1200) {$aReturn = array("linesOnSite"=>20, "sitesNumber"=>8 );}
        if($screenHeight<=1080) {$aReturn = array("linesOnSite"=>18, "sitesNumber"=>8 );}
        if($screenHeight<=1050) {$aReturn = array("linesOnSite"=>17, "sitesNumber"=>8 );}
        if($screenHeight<=970)  {$aReturn = array("linesOnSite"=>16, "sitesNumber"=>8 );}
        if($screenHeight<=960)  {$aReturn = array("linesOnSite"=>15, "sitesNumber"=>8 );}
        if($screenHeight<=900)  {$aReturn = array("linesOnSite"=>14,  "sitesNumber"=>5 );}
        if($screenHeight<=800)  {$aReturn = array("linesOnSite"=>10,  "sitesNumber"=>5 );}
        if($screenHeight<=736)  {$aReturn = array("linesOnSite"=>8,  "sitesNumber"=>4 );}
        if($screenHeight<=731)  {$aReturn = array("linesOnSite"=>7,  "sitesNumber"=>4 );}
        if($screenHeight<=640)  {$aReturn = array("linesOnSite"=>7,  "sitesNumber"=>4 );}
        if($screenHeight<=568)  {$aReturn = array("linesOnSite"=>4,  "sitesNumber"=>4 );}
        return($aReturn);
}

function scrollTable($siteNo, $viewSitesCount, $viewLinesCount, $dataCount, $upView=true) {

$sitesCount = floor($dataCount/$viewLinesCount);
if(($dataCount%$viewLinesCount)>0) {++$sitesCount;}
$startSites = 1;
if($siteNo > $viewSitesCount) {
        
        $startSites = ($sitesCount - ($viewSitesCount-1));
        if(($siteNo)<$startSites+(floor($viewSitesCount/2))) {
                $startSites = $siteNo - floor($viewSitesCount/2);
        }
}
if(strLen(Trim($siteNo))==0) {$siteNo = 1;}
$sitesNo = $siteNo;

if($upView) {$scrollDesk = " scrollDeskUp "; $mt = " mt-3 "; } else {$scrollDesk = " scrollDeskDown "; $mt = " mt-1 ";}
?>

<div class="container  d-flex justify-content-center  <?=$mt." ".$scrollDesk?>" >
        <div class="scrollClear scrollList ">
<?
                if($siteNo>1) {
?>
                <a onclick="hiddenSet('siteNo_visitForm', 1, true)">
                        <i class="fas fa-angle-double-left"></i>
                </a>
<?
                } else {
?>
                        <i class="fas fa-angle-double-left"></i>
<?                        
                }
?>
        </div>
        <div class="scrollList ">
<?
                if($siteNo>1) {
?>
                <a onclick="hiddenSet('siteNo_visitForm', <?=($siteNo-1)?>, true)">
                        <i class="fas fa-angle-left"></i>
                </a>
<?
                } else {
?>
                        <i class="fas fa-angle-left"></i>
<?                        
                }
?> 
        </div>
<?
        for($i=$startSites;($i<=($startSites+$viewSitesCount)-1) && $i<=$sitesCount;++$i) {
                $current = ""; if($i==$siteNo) {$current = " scrollCurrentList ";}
?>
                <div class="scrollList <?=$current?> ">
<?
                        if($i!=$siteNo) {
?>
                        <a onclick="hiddenSet('siteNo_visitForm', <?=$i?>, true)">
                                <?=$i?>
                        </a>
<?
                        } else {
?>
                                <?=$i?>
<?                        
                        }
?>
                </div>
<?        
                //if($i==5) {break;}
        }
?>
        <div class="scrollList ">
<?
                if($siteNo<$sitesCount) {
?>
                <a onclick="hiddenSet('siteNo_visitForm', <?=($siteNo+1)?>, true)">
                        <i class="fas fa-angle-right"></i>
                </a>
<?
                } else {
?>
                        <i class="fas fa-angle-right"></i>
<?                        
                }
?> 
        </div>
        <div class="scrollList ">
<?
                if($siteNo<$sitesCount) {
?>
                <a onclick="hiddenSet('siteNo_visitForm', <?=$sitesCount?>, true)">
                        <i class="fas fa-angle-double-right"></i>
                </a>
<?
                } else {
?>
                        <i class="fas fa-angle-double-right"></i>
<?                        
                }
?> 
        </div>
</div>
<?
return($sitesNo);
}

function displayVisitSites($oVisitSites) {
        //c($oVisitSites);
?>        
        <div class="row  ">
<?
        $index = 1;
        foreach($oVisitSites as $aSites) {
?>
                <div class="col-sm-4 rightDesk ">
                        <div class="text-left mt-1 pt-2 pl-3 visitSites" style="float:left;width:99%;">
                                <?=$aSites["men_itemName"]?>
                        </div>
                </div>
<?        
                ++$index;
                if($index == 4) {
?>
                        </div>
                        <div class="row  ">
<?
                        $index = 1;
                }
        }
        if($index < 4) {?></div><?                
    } 
}

?>