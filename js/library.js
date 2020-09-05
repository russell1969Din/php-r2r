$(document).ready(function() {      
       for(var i=0;i<document.forms.length;++i) {
                var oForm = document.forms[i];
                if(document.getElementById("inspect_" + oForm.id)) {
                        var aInspect = $("#inspect_" + oForm.id).val().split("~");
                        if(oForm.id!="passForm") {
                                for(var j=0;j<oForm.length;++j) {
                                        var oElement = oForm.elements[j];
                                        if(oElement.id.indexOf(aInspect[0])>(-1)) {
                                                eval('$("#"+oElement.id).keyup(function() {formAction(oForm, "' + $("#inspect_" + oForm.id).val() + '", ' + i + ');});');
                                                eval('$("#"+oElement.id).click(function() {formAction(oForm, "' + $("#inspect_" + oForm.id).val() + '", ' + i + ');});');
                                                eval('$("#"+oElement.id).change(function() {formAction(oForm, "' + $("#inspect_" + oForm.id).val() + '", ' + i + ');});');
                                        }
                                }
                        } else {
                                for(var j=0;j<oForm.length;++j) {
                                        var oElement = oForm.elements[j];
                                        if(oElement.id.indexOf(aInspect[0])>(-1)) {
                                                eval('$("#"+oElement.id).keyup(function() {formAction(oForm, "' + $("#inspect_" + oForm.id).val() + '", ' + i + ');});');
                                                eval('$("#"+oElement.id).click(function() {formAction(oForm, "' + $("#inspect_" + oForm.id).val() + '", ' + i + ');});');
                                        }
                                }

                        }
                }
        }
})

function formAction(oForm, inspect, indexForm) {   
        var aInspect = inspect.split("~");
        var aTag = getTagCount(document.forms[indexForm], aInspect[0], indexForm);
        var ok = controller(document.forms[indexForm], aTag, aInspect);
}

function getTagCount(oForm, prx, indexForm) {
        var aTags = [];
        for(var i=0;i<oForm.length;++i) {
                var oElement = oForm.elements[i];
                if(oElement.id.indexOf(prx+"_")>(-1)) {
                        if($("#"+oElement.id).attr("alt")) {var alt = $("#"+oElement.id).attr("alt");} else {var alt = "";}
                        if(oElement.id.indexOf(prx+"_")==0 || oElement.id.indexOf("smart_"+prx)==0) {
                                if($('#'+oElement.id).attr('type')=="radio") {
                                        var f = false;
                                        for(var r=0;r<aTags.length;++r) {
                                                if(aTags[r][0].indexOf(oElement.name)>(-1)) {f=!f;break;}
                                        }             
                                        if(!f) {
                                                aTags.push([oElement.name,alt,$("#"+oElement.id).val()]);
                                        }                                                           
                                } else {
                                        if($('#'+oElement.id).attr('type')!="hidden") {        
                                                if(oElement.id.indexOf("smart_") == 0) {
                                                        aTags.push([oElement.id.substr(6,oElement.id.length),alt,$("#"+oElement.id).val()]);
                                                } else {
                                                        aTags.push([oElement.id,alt,$("#"+oElement.id).val()]);
                                                }
                                        }
                                }
                        }
                }
        }
        return(aTags);
}

function controller(oForm, aTags, aInspect) {
        var correct = 0;
        for(var i=0;i<aTags.length;++i) {
        
                if(document.getElementById(aTags[i][0]+"_title")) {
                        eval('if(typeof(bg_'+aTags[i][0]+')=="undefined") {bg_'+aTags[i][0]+' = $("#'+aTags[i][0]+'_title").css("background-color");}');
                        eval('if(typeof(txt_'+aTags[i][0]+')=="undefined") {txt_'+aTags[i][0]+' = $("#'+aTags[i][0]+'_title").css("color");}');
                }
                var aReg = aTags[i][1].split("~");
                var err = regExpValidation(aReg[0], aTags[i][2], aReg[1]);
                if(err.length == 0 && (aReg[0]=="X" || aReg[0]=="MD5") || aReg[0]=="OR") {
                        if(aReg[0] == "X" && aTags[i][2] != $("#"+aReg[4]).val() && aTags[i][2].length > 0) {
                                err = aReg[5];   
                        }
                        if(aReg[0] == "MD5" && md5(aTags[i][2]) != $("#"+aReg[4]).val() && aTags[i][2].length > 0) {
                                err = aReg[5];   
                        }
                }
                
                if(err.length>0) {
                        if(typeof(aReg[3])!="undefined") {if(aReg[3].length>0) {err = aReg[3];}}
                        if(document.getElementById(aTags[i][0]+"_title")) {
                                eval('$("#'+aTags[i][0]+'_title").css({"background-color":"'+aInspect[3]+'", "color":"'+aInspect[4]+'"});');
                        }
                        if(document.getElementById(aTags[i][0]+"_err")) {
                                $("#"+aTags[i][0]+"_err").css({"color":aInspect[1], "font-size":aInspect[5]+"px", "font-family":aInspect[6], "font-weight":aInspect[7]});
                                $("#"+aTags[i][0]+"_err").html(err);
                        }
                        
                } else {
                        if(document.getElementById(aTags[i][0]+"_title")) {
                                eval('$("#'+aTags[i][0]+'_title").css({"background-color":bg_'+aTags[i][0]+', "color":txt_'+aTags[i][0]+'});');
                        }
                        if(document.getElementById(aTags[i][0]+"_err")) {
                                ok=""; if(typeof(aReg[2])!="undefined") {if(aReg[2].length>0) {ok=aReg[2];}}
                                $("#"+aTags[i][0]+"_err").css({"color":aInspect[2], "font-size":aInspect[5]+"px", "font-family":aInspect[6], "font-weight":aInspect[7]});
                                $("#"+aTags[i][0]+"_err").html(ok);
                        }
                        ++correct;
                }
        }
        
        if(document.getElementById("save_"+oForm.id))   {var buttonIdent = "save_";}
        if(document.getElementById("update_"+oForm.id)) {var buttonIdent = "update_";}
         
        if(buttonIdent.indexOf("_")>1) {
                var approval  = (document.getElementById("approvalDesk_"+oForm.id) && document.getElementById("approval_"+oForm.id));
                
                if(progress = setProgress(oForm, aTags, correct)) {
                        if(approval) {
                                $("#approval_"+oForm.id).click(function() {$("#"+buttonIdent+oForm.id).prop("disabled", !$("#approval_"+oForm.id).is(":checked"))});
                                $("#approvalDesk_"+oForm.id).css("display", "block");
                        } else {
                                $("#"+buttonIdent+oForm.id).prop("disabled", !progress);
                        }
                } else {          
                        $("#"+buttonIdent+oForm.id).prop("disabled", true);
                        if(approval) {
                                $("#approval_"+oForm.id).prop( "checked", false );
                                $("#approvalDesk_"+oForm.id).css("display", "none");
                        }
                }         
         }
}

function setProgress(oForm, aTags, correct) {
        perc = Math.round((100 * correct) / aTags.length);
        if(document.getElementById("progress_" + oForm.id)) {
                $("#progress_" + oForm.id).html(perc+"%");
                $("#progress_" + oForm.id).css("width", perc + "%");
        }
        return(perc==100);
}

function _d(param) {
        if(param != null && document.getElementById("dbgDesk")) {
                $("#dbgDesk").html(param);
                $("#dbgDesk").css("display", "inline-block");
        }
}


function _dx(param) {
        if(param != null && document.getElementById("dbgDesk")) {
                var content = $("#dbgDesk").html();
                var comma = ""; if(content.length > 0 ) {comma = " :: ";}
                $("#dbgDesk").html($("#dbgDesk").html() + comma + param);
                $("#dbgDesk").css("display", "inline-block");
        }   else {
                var content = $("#dbgDesk").html();
                var comma = ""; if(content.length > 0 ) {comma = " :: ";}
                $("#dbgDesk").html($("#dbgDesk").html() + comma + "EMPTY?");
                $("#dbgDesk").css("display", "inline-block");
        
        }
}

function clickButton(o, userFun=null) {
        var solution = false;
        if(typeof($("#"+o.id).attr("alt"))!="undefined") {
                if( $("#"+o.id).attr("alt").toLowerCase() == "captcha" &&
                    document.getElementById("captcha_modal") &&
                    document.getElementById("captcha_info") &&
                    document.getElementById("captcha_confirm") &&
                    document.getElementById("captcha_content") &&
                    document.getElementById("captcha_key")) {
                            hiddenSet(o);
                            callUserFunc = viewCaptchaModal(userFun, o);
                            var solution = !solution;            
                }
        }
            
        if(!solution) {   
                if(userFun!=null) {
                        if(userFun.length > 0) {
                                var aUserFun = userFun.split("|");
                                //var isFunction = (typeof setLoginPass) == 'function';
                                for(var i=0;i<aUserFun.length;++i) {
                                        eval("var isFunction = (typeof " + aUserFun[i].substr(0,aUserFun[i].indexOf("(")) + ") == 'function'");
                                        hiddenSet(o); if (isFunction) {eval(aUserFun[i])};
                                }
                        } else {hiddenSet(o);}
                } else {
                        hiddenSet(o);                        
                }
                formSubmit(o);
        }        
}

//formSubmit(
function formSubmit(o) {

        var oSubmit = null;
        if(typeof(o) == "object") {
                for(var i=0;i<document.forms.length;++i) {
                        var oForm = document.forms[i];
                        if(o.id.substr(o.id.indexOf("_")+1, o.id.length) == oForm.id) {oSubmit = oForm;}
                }
        }
        
        if(typeof(o) == "string") {
                var id = o;
                for(var i=0;i<document.forms.length;++i) {
                        var oForm = document.forms[i]; 
                        if(id.substr(id.indexOf("_")+1, id.length) == oForm.id) {oSubmit = oForm;}
                }
        }
        if(oSubmit != null) {oSubmit.submit();}
}

//hiddenSet(
function hiddenSet(o ,param=1, isSubmit=false) {
        if(typeof(o) == "object") {
                var id = o.id;
                var hidden = "change" + id.substr(id.indexOf("_"),id.length);
        } else {
                if(typeof(o) == "string") {var id = o;}
                var hidden = id;
        }
        if(document.getElementById(hidden)) {$("#"+hidden).val(param);}
        if(isSubmit) {
                oSubmit = null;
                for(var i=0;i<document.forms.length;++i) {
                        var oForm = document.forms[i];
                        if(id.substr(id.indexOf("_")+1, id.length) == oForm.id) {var oSubmit = oForm;}
                }
                if(typeof(oSubmit)!=null) {formSubmit(oSubmit);}
        }
}

function    regExpValidation(type, validValue,  minimalChars)   {

        var regExp  =   "XXXXX";
        var regDate     =   /(^(((0[1-9]|1[0-9]|2[0-8])[-](0[1-9]|1[012]))|((29|30|31)[-](0[13578]|1[02]))|((29|30)[-](0[4,6,9]|11)))[-](19|[2-9][0-9])\d\d$)|(^29[-]02[-](19|[2-9][0-9])(00|04|08|12|16|20|24|28|32|36|40|44|48|52|56|60|64|68|72|76|80|84|88|92|96)$)/;
        var regPhone    =   /^((\+\d{1,3}(-| )?\(?\d\)?(-| )?\d{1,3})|(\(?\d{2,3}\)?))(-| )?(\d{3,4})(-| )?(\d{4})(( x| ext)\d{1,5}){0,1}$/;
        var regMail     =   /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        var regUrl      =   /(http|ftp|https):\/\/[\w-]+(\.[\w-]+)+([\w.,@?^=%&amp;:\/~+#-]*[\w@?^=%&amp;\/~+#-])?/
        switch( type    )   {
                case    "H":    {   
                        regExp  =   /[0-9A-Fa-f]{6}/;
                        var err =   "Chybný hex formát napr. ABC123 !";                                                  
                        break;  }
                case    "P":    {   
                        regExp  =   /\d{3} ?\d{2}/;
                        var err =   "Chybný formát PSČ !";                                                 
                        break;  }
                case    "NDC":  {   
                        regExp  =   /^[0-9.,]+$/;
                        var err =   "Povolené číslo, čiarka, bodka !";                                                   
                        break;  }
                case    "NCO":  {
                        regExp  =   /(\d{1})|((\d+(\,|\.))+\d{2,})/;
                        var err =   "Povolené číslo a čiarka !";                                                   
                        break;  }
                case    "NC":  {   
                        regExp  =   /^[0-9.]+$/;
                        var err =   "Povolené číslo a bodka !";                                                   
                        break;  }
                case    "A":    {   
                        regExp  =   /[0-9]+(\.[0-9]{1,2}){0,1}/;
                        var err =   "Financie napr. 152,50 eur !";
                        break;  }
                case    "NS":   {   
                        regExp  =   /^(?=.*\d)[\d ]+$/;
                        var err =   "Číslo prípadne medzera !";                                             
                        break;  }
                case    "CS":   {   
                        regExp  =   /^[A-Za-z\s]+$/;                
                        validValue=diaConvert(validValue);
                        var err =   "Abeceda prípadne medzera !";   
                        break;  }
                case    "C":    {   
                        regExp  =   /^[a-zA-Z]+$/;                  
                        validValue=diaConvert(validValue);
                        var err =   "Iba abeceda bez medzery !";   
                        break;  }        
                case    "N":    {   
                        regExp  =   /^\d+$/;
                        var err =   "Iba číslo !";                                                        
                        break;  }
                case    "D":    {       
                        if(validValue != "00-00-0000") {regExp  =   regDate;}
                        var err =   "Formát dátumu 10-01-2011 !";                                                        
                        break;  }
                case    "T":    {   
                        regExp  =   regPhone;
                        var err =   " Tel.č. napr. +421 904123456 !";                                                       
                        break;  }
                case    "URL":    {   
                        regExp  =   regUrl;
                        var err =   "Formát napr. https:\\domain.sk";
                        break;  }
                case    "E":    {   
                        regExp  =   regMail;
                        var err =   "Napr. meno@domena.sk !";
                        break;  }
                case    "CN":   {
                        //regExp  =   /^[a-zA-Z0-9\-\_]$/;
                        //regExp  =   /[a-zA-Z0-9\_]$/;
                        regExp  =   /^[0-9a-zA-Z]+$/;   
                        validValue=diaConvert(validValue);
                        var err =   "Abeceda alebo číslo";
                        break;  }
                case    "CNP":    {                        
                        regExp  =   /^[0-9a-zA-Z-]+$/; 
                        validValue=diaConvert(validValue);  
                        var err =   "Abeceda, číslo, pomlčka  !";   
                        break;  }
                case    "CNS":    {                        
                        regExp  =   /^[0-9a-zA-Z\s]+$/;   
                        validValue=diaConvert(validValue);
                        var err =   "Abeceda, číslo, medzera!";   
                        break;  }
                        
                case    "CD":    {
                        //regExp  =   /[a-z A-Z0-9\\_\\"]+$/;     
                        //regExp  =   /^[A-Za-z0-9]+([-_]+[A-Za-z0-9]+)*$/;
                        regExp  =   /^[a-zA-Z-]*$/; //. 
                        validValue=diaConvert(validValue);
                        var err =   "Abeceda prípadne pomlčka !";   
                        break;  }
                case    "ND":    {
                        //regExp  =   /[a-z A-Z0-9\\_\\"]+$/;     
                        //regExp  =   /^[A-Za-z0-9]+([-_]+[A-Za-z0-9]+)*$/;
                        regExp  =   /^(\d+-?)+\d+$/; //. 
                        var err =   "Číslo prípadne pomlčka !";   
                        break;  }

                                    
        }
        
        if(validValue.length==0 &&  minimalChars==0)    {return("");}
        
        if(regExp!="XXXXX") {
                if(!regExp.test(validValue)) {   return(err);  }
                if(minimalChars>0   &&  validValue.length<minimalChars)    {return("Minimálny počet znakov " +   minimalChars    +   " !");}
                    
        }   else    {

                if(type=="M")   {    
                
                        
                        regExp  =   /^\d+$/;
                        if(!regExp.test(validValue))    {return("Pre modulo 11 musí byť číslo !");}
                        if((validValue%11)>0)           {return("Modulo 11 zadania musí byť 0 !");}
                        if(minimalChars>validValue.length)  {return("Počet znakov musí byť " +   minimalChars    +   " !");}
                }   else    {
                
                        if(minimalChars>0   &&  validValue.length<minimalChars)    {return("Minimálny počet znakov " +   minimalChars    +   " !");}
                }
        }
        
        return("");
}

function diaConvert(    text,  safetyEmpty  )   {

        var convertText = "";
        var dia         = "áäčďéíľĺňóôŕšťúýžÁČĎÉÍĽĹŇÓŠŤÚÝŽ";
        var nodia       = "aacdeillnoorstuyzACDEILLNOSTUYZ";                

        for(i=0; i<text.length; i++) {

                if(dia.indexOf(text.charAt(i))!=-1) {
                        
                        convertText += nodia.charAt(dia.indexOf(text.charAt(i)));
                }
                else {
                        convertText += text.charAt(i);
                }
        }

        return convertText;
}        


function hx(color) {
        var isHastag = (color.substr(0,1) == "#");
        if(regExpValidation("H", color,  0).length == 0) {
                if(!isHastag) {color = "#" + color;}                        
        }
        return(color);
}

function  processChangeURL( title, url  )  {
        var obj = { Title: title, Url: url };
        history.pushState(obj, obj.Title, obj.Url);
}