       <link rel="stylesheet" type="text/css"  href="/css/menu.css" />
       
<?
        $aMsg = array("Skutočne sa chcete odhlásiť ?");
        $aYesButton = array("setYes");
        $aId = array("unloginModal", "unLogin()");
        bsModal($aId, $aMsg, null, $aYesButton);        

        $oParent = $db->get("GENERAL_PARENT", "generalMenu", null, "men_idParent=0 && men_block=0 && men_delete = 0", false, __FILE__, __LINE__);
        
        $length = 0;
        foreach($oParent as $aParent) {
                $length += strLen(Trim($aParent["men_itemName"]));       
        }
?>         
        <input type="hidden" id="menuLength" name="menuLength" value="<?=$length?>">
        
        <!---->
        <div id="globalMenu" class="fixed-top " style="">
        <div id="fluidTopNav" class="container-fluid " >
                <div class="container" >
                        <div id="selectorMenu" class="container" style="display:none;">
                                
                                <div class="row">
                                <div class="col-sm-8" style="" >
                                        <select id="selectMenu" class="nice-select" name="selectMenu" onchange="callParent()">
<?
                                        $oParent = $db->get("GENERAL_PARENT", "generalMenu", null, "men_idParent=0 && men_block=0 && men_delete = 0", false, __FILE__, __LINE__);
                                        foreach($oParent as $aParent) {
                                        $oChild = $sys->getAccessParent($aParent["men_id"]);
                                        if(count($oChild)>0) {
                                                $currentClass = "";
                                                if(strPos("~".$_SERVER['REQUEST_URI'],"/".$aParent["men_path"]))  {
                                                        $currentClass       = "active";
                                                        $currentParentId    = $oChild["men_idParent"];
                                                        $parentPath         = $aParent["men_path"];
                                                        $itemName           = $aParent["men_itemName"];
                                                }
                                                $aURI = explode("/", $_SERVER["REQUEST_URI"]);
                                                $selected = '';
                                                if($aParent["men_path"]==$aURI[count($aURI)-2]) {$selected = 'selected="selected"';}

?>                                          
                                                <option <?=$selected?> value="/<?=$aParent["men_path"]?>/<?=$oChild["men_path"]?>"><?=$aParent["men_itemName"]?></option>
<?
                                        }
                                        }
?>
                                                <option value="/admin/visit">Administrátor</option>    
                                        </select>
                                        <script>$(document).ready(function() {$('#selectMenu').niceSelect();});</script>
                                </div>
                                <div class="col-sm-4 pt-1" style="color:#B5B5B5;">
                                <a class="menu"  data-toggle="modal" data-target="#unloginModal">Odhlásiť sa</a>
                                </div>
                                </div>
                        </div>
                
                        <div class="topnav" id="myTopnav" >
<?
                                $oParent = $db->get("GENERAL_PARENT", "generalMenu", null, "men_idParent=0 && men_block=0 && men_delete = 0", false, __FILE__, __LINE__);
                                foreach($oParent as $aParent) {
                                        $oChild = $sys->getAccessParent($aParent["men_id"]);
                                        if(count($oChild)>0) {
                                                $currentClass = "";
                                                if(strPos("~".$_SERVER['REQUEST_URI'],"/".$aParent["men_path"]))  {
                                                        $currentClass       = "active";
                                                        $currentParentId    = $oChild["men_idParent"];
                                                        $parentPath         = $aParent["men_path"];
                                                        $itemName           = $aParent["men_itemName"];
                                                }
?>
                                                <a class="menu <?=$currentClass?>"  href="/<?=$aParent["men_path"]?>/<?=$oChild["men_path"]?>" ><?=$aParent["men_itemName"]?></a>
<?
                                        }
                                }

                                $currentClass = ""; 
                                if(strPos("~".$_SERVER['REQUEST_URI'],"/admin"))  {$currentClass = "active";}
?>
                                <a href="/admin/visit" class="menu <?=$currentClass?>" >Administrátor</a>

                                <a class="menu"  data-toggle="modal" data-target="#unloginModal">Odhlásiť sa</a>
                                <a href="javascript:void(0);" class="icon" onclick="myFunction()">
                                        <i class="fa fa-bars"></i>
                                </a>
                        </div>

                </div>
        </div>

        <script>
        var menuLength = $("#menuLength").val()
        
        if(menuLength>27) {
                $(".menu").css("font-size", "18px");
        }
        
        if(menuLength>40) {
                $(".menu").css("font-size", "14px");
        }
        
        
        if($(window).width()<=1024 && $(window).width()>800) {
                if(menuLength>35) {
                        $("#myTopnav").css("display", "none");
                        $("#selectorMenu").css("display", "block");
                } else {
                        $(".menu").css("font-size", "18px");
                }
        
        }
        
        if($(window).width()<=800 && $(window).width()>768) {
                if(menuLength>28) {
                        $("#myTopnav").css("display", "none");
                        $("#selectorMenu").css("display", "block");
                } else {
                        $(".menu").css("font-size", "18px");
                }
        
        }
        
        if($(window).width()<=768 && $(window).width()>610) {
                _dx($(window).width());
                if(menuLength>28) {
                        $("#myTopnav").css("display", "none");
                        $("#selectorMenu").css("display", "block");
                } else {
                        $(".menu").css("font-size", "15px");
                }
                
        }
        
        function callParent() {
                window.location.href=$("#selectMenu").val();
        }
        
        </script>
<?
        if(strPos("~".$_SERVER['REQUEST_URI'], "/".$parentPath."/")) {
        
?>        

        <div class="container-fluid "  style="background-color:#aaaaaa;border-bottom:1px dotted #7F7F7F;">
        
        
<?                                                                 
        if($_SESSION["SYS_ACCOUNT"]["acc_admin"]==1 || $_SESSION["SYS_ACCOUNT"]["acc_supervisor"]==1)  {
                $aTables = "generalMenu"; 
                $where   = "men_idParent='".$currentParentId."' && men_block=0 && men_delete=0 ORDER BY men_index ASC"; 
        } else {
                $aTables = array("generalMenu", "menuRightsAccess"); 
                $where   = "men_id=rig_idMenu && rig_idUser = '".$_SESSION["SYS_ACCOUNT"]["acc_id"]."' && men_idParent='".$currentParentId."' && men_block=0 && men_delete=0 ORDER BY men_index ASC"; 
        }
        $oChild = $db->get($itemName, $aTables, null, $where, false, __FILE__, __LINE__);
        
        if(count($oChild)<4) {
?>
                <div class="container " >
                <div class="row ml-2" style="">   
<?        
                
                $sm = 12/Count($oChild);
                foreach($oChild as $aChild) {
                        $currentClass = "subMenuText";    
                        if(strPos("~".$_SERVER['REQUEST_URI'], "/".$aChild["men_path"])) {$currentClass = "subMenuTextOn";}
?>      
                        <div class="col-sm-<?=$sm?> subMenuItem <?=$currentClass?> " >
                            <a href="/<?=$parentPath?>/<?=$aChild["men_path"]?>"><?=$aChild["men_itemName"]?></a>
                        </div>       
<?
                }
?>
                </div>
                </div>
<?
        } else {
            
?>
                <div class="container d-flex justify-content-center" style="clear:both;">
                        <div style="width:300px;">
                        <select id="setMenuItem" class="nice-select "  name="setMenuItem" onchange="hrefMenu()">
<?
                                
                                foreach($oChild as $aChild) {
                                        $selected='';
                                        if(strPos("~".$_SERVER['REQUEST_URI'], "/".$aChild["men_path"])) {$selected='selected="selected"';}
?>
                                        <option <?=$selected?> value="<?=$parentPath?>/<?=$aChild["men_path"]?>"><?=$aChild["men_itemName"]?></option>
<?
                                }
?>
                        </select>
                        </div>
                        <script>
                                $(document).ready(function() {$('#setMenuItem').niceSelect();});
                                function hrefMenu() {window.location.href='<?=$_SESSION["DOMAIN_PATH"]?>/' + $("#setMenuItem").val();}
                        </script> 
                </div>
<?
        }
?>
        </div>
<?
        }
        
        $aSubMenuItems = array("change", "content", "access", "visit");
        $viewAdminSub = false;
        foreach($aSubMenuItems as $sub) {
                if(strPos("~".$_SERVER['REQUEST_URI'], "/".$sub)) {$viewAdminSub = !$viewAdminSub; break;}
        }

        if($viewAdminSub) {
?>        

        <div class="container-fluid" style="background-color:#aaaaaa;border-bottom:1px dotted #7F7F7F;">
        <div class="container" >
        <div class="row ml-2" style="">
<?
                $currentClass = "subMenuText";    
                if(strPos("~".$_SERVER['REQUEST_URI'], "/visit")) {$currentClass = "subMenuTextOn";}
?>              

                <div class="col-sm-3 subMenuItem <?=$currentClass?>" >
                        <a href="/admin/visit" >Návštevy</a>
                </div>       
<?          
                if($_SESSION["SYS_ACCOUNT"]["acc_admin"] == 1) {
                $currentClass = "subMenuText";    
                if(strPos("~".$_SERVER['REQUEST_URI'], "/content")) {$currentClass = "subMenuTextOn";}
?>              
                <div class="col-sm-3 subMenuItem <?=$currentClass?>">
                        <a href="/admin/content" >Obsah</a>
                </div>       
<?                
                } 
      

                if($_SESSION["SYS_ACCOUNT"]["acc_admin"] == 1) {
                $currentClass = "subMenuText";    
                if(strPos("~".$_SERVER['REQUEST_URI'], "/access")) {$currentClass = "subMenuTextOn";}
?>              
                <div class="col-sm-3 subMenuItem <?=$currentClass?>">
                        <a href="/admin/access" >Prístupy</a>
                </div>       
<?                
                } 



                $currentClass = "subMenuText";    
                if(strPos("~".$_SERVER['REQUEST_URI'], "/change")) {$currentClass = "subMenuTextOn";}
?>              
                <div class="col-sm-3 subMenuItem <?=$currentClass?>" >
                        <a href="/admin/change" >Heslo</a>
                </div>       
        </div>
        </div>
        </div>
<?
        }
?>
</div>      
       
<script>
$(document).ready(function() {if($(window).width()<481) {$("#globalMenu").removeClass("fixed-top");}});


        function myFunction() {
                var x = document.getElementById("myTopnav");
                if (x.className === "topnav") {
                        x.className += " responsive";
                } else {
                        x.className = "topnav";
                }
                
                var x = document.getElementById("_myTopnav");
                if (x.className === "topnav") {
                        x.className += " responsive";
                } else {
                        x.className = "topnav";
                }
        
        }
                
</script>