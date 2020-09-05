$(document).ready(function() {});

function viewCaptchaModal(userFun, o=null) {
        
        $("#captcha_key").removeClass( "spinner-border text-success" );
        $("#captcha_key").html('<i class="fas fa-sign-in-alt"></i>');
        $("#captcha_key").css("color","#00B51E");
        $("#captcha_modal").css("display", "block");
        
        var oCaptcha = document.getElementById("captcha_modal");
        $(window).click(function(e) {
                if (e.target == oCaptcha) {
                        $("#captcha_modal").css("display", "none");
                }
        });

        $("#captcha_key").click(function() {    
                $("#captcha_key").addClass( "spinner-border text-success" );
                setTimeout(function(){
                        $("#captcha_key").removeClass( "spinner-border text-success" );
                        $("#captcha_key").html('<i class="fas fa-check"></i>');
                        $("#captcha_key").css("color","#CCCCCC");
                        setTimeout(function(){
                                $("#captcha_key").css("color","#B2B2B2");
                                setTimeout(function(){
                                        $("#captcha_key").css("color","#959494");
                                        setTimeout(function(){
                                                $("#captcha_key").css("color","#7D7C7C");
                                                setTimeout(function(){
                                                        $("#captcha_key").css("color","#676565");
                                                        setTimeout(function() {
                                                                $("#captcha_key").css("color","#484747");
                                                                setTimeout(function() {
                                                                        $("#captcha_key").css("color","#2A2A2A");
                                                                        setTimeout(function() {
                                                                                $("#captcha_modal").css("display", "none");
                                                                                if(userFun!=null) {
                                                                                        if(userFun.length > 0) {
                                                                                                var aUserFun = userFun.split("|");
                                                                                                for(var i=0;i<aUserFun.length;++i) {
                                                                                                        eval("var isFunction = (typeof " + aUserFun[i].substr(0,aUserFun[i].indexOf("(")) + ") == 'function'");
                                                                                                        if (isFunction) {eval(aUserFun[i])};
                                                                                                }
                                                                                        }
                                                                                } 
                                                                                formSubmit(o);
                                                                        }, 200);
                                                                }, 100);
                                                        }, 100);
                                                }, 100);
                                        }, 100);
                                }, 100);
                        }, 100);
                }, 1000);        
        });
}