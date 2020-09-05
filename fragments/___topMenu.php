       <link rel="stylesheet" type="text/css"  href="/css/menu.css" />
        
        <!-- Modal-->
        <div class="modal fade" id="myModal" role="dialog">
                <div class="modal-dialog">
                        <!-- Modal content-->
                        <div class="modal-content">
                                <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>
                                <div class="modal-body">
                                        <p>Skutočne sa chcete odhlásiť ?</p>
                                </div>
                                <div class="modal-footer">
                                        <button type="button" class="btn btn-default" onclick="unLogin()">Áno</button>
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Nie</button>
                                </div>
                        </div>
                </div>
        </div>
  
         
        <div id="fluidTopNav" class="container-fluid">
                <div class="container">
                        <div class="topnav" id="myTopnav">
<?
                                $aURI = explode("/",$_SERVER['REQUEST_URI']);
                                $currentClass = "";
                                if(strPos("~".$_SERVER['REQUEST_URI'],"/video"))  {$currentClass = "active";}
?>                               
                                <a href="/video/polyfun" class="<?=$currentClass?>">Video ponuky</a>
<?
                                $currentClass = "";
                                if(strPos("~".$_SERVER['REQUEST_URI'], "/admin")) {$currentClass = "active";}
                                
                                if($_SESSION["SYS_ACCOUNT"]["acc_admin"] == 1) {
?>                               
                                <a href="/admin/visit" class="<?=$currentClass?>" >Administrátor</a>
<?                                
                                } else {
?>                               
                                <a href="/admin/change" class="<?=$currentClass?>" >Administrátor</a>
<?                                
                                }
?>
                                <a data-toggle="modal" data-target="#myModal">Odhlásiť sa</a>
                                <a href="javascript:void(0);" class="icon" onclick="myFunction()">
                                        <i class="fa fa-bars"></i>
                                </a>
                        </div>

                </div>
        </div>
        
<?
        if(strPos("~".$_SERVER['REQUEST_URI'], "/polyfun") || strPos("~".$_SERVER['REQUEST_URI'], "/lermont")) {
?>        

        <div class="container-fluid" style="background-color:#aaaaaa;border-bottom:1px dotted #7F7F7F;">
        <div class="container" >
        <div class="row ml-2" style="">
<?
                $currentClass = "subMenuText";    
                if(strPos("~".$_SERVER['REQUEST_URI'], "/polyfun")) {$currentClass = "subMenuTextOn";}
?>              
                <div class="col-sm-2 subMenuItem <?=$currentClass?>" >
                        <a href="/video/polyfun" >Polyfunkcia</a>
                </div>       
<?
                $currentClass = "subMenuText";    
                if(strPos("~".$_SERVER['REQUEST_URI'], "/lermont")) {$currentClass = "subMenuTextOn";}
?>              
                <div class="col-sm-2 subMenuItem <?=$currentClass?>">
                        <a href="/video/lermont" >Staré mesto</a>
                </div>       
        </div>
        </div>
        </div>
<?
        }
?>

        
        
<?
        if(strPos("~".$_SERVER['REQUEST_URI'], "/change") || strPos("~".$_SERVER['REQUEST_URI'], "/access") || strPos("~".$_SERVER['REQUEST_URI'], "/visit")) {
?>        

        <div class="container-fluid" style="background-color:#aaaaaa;border-bottom:1px dotted #7F7F7F;">
        <div class="container" >
        <div class="row ml-2" style="">
<?
                if($_SESSION["SYS_ACCOUNT"]["acc_admin"] == 1) {
                $currentClass = "subMenuText";    
                if(strPos("~".$_SERVER['REQUEST_URI'], "/visit")) {$currentClass = "subMenuTextOn";}
?>              

                <div class="col-sm-2 subMenuItem <?=$currentClass?>" >
                        <a href="/admin/visit" >Návštevy</a>
                </div>       
<?                
                }

                if($_SESSION["SYS_ACCOUNT"]["acc_admin"] == 1) {
                $currentClass = "subMenuText";    
                if(strPos("~".$_SERVER['REQUEST_URI'], "/access")) {$currentClass = "subMenuTextOn";}
?>              
                <div class="col-sm-2 subMenuItem <?=$currentClass?>">
                        <a href="/admin/access" >Prístupy</a>
                </div>       
<?                
                } 



                $currentClass = "subMenuText";    
                if(strPos("~".$_SERVER['REQUEST_URI'], "/change")) {$currentClass = "subMenuTextOn";}
?>              
                <div class="col-sm-2 subMenuItem <?=$currentClass?>" >
                        <a href="/admin/change" >Heslo</a>
                </div>       
        </div>
        </div>
        </div>
<?
        }
?>
       
        <script>
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