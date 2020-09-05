        function dec(param, takeAsc=3) {
                var setIn = 1;
                var aReverse = [];
                var reverse = "";
                var chr = "";
                param = param.trim();
                for(var i=0;i<param.length;++i) {
                        chr = chr + param.substr(i, 1);
                        ++setIn;
                        go  = false;
                        if(setIn==4) {go=true;setIn=1;item=reverseWord(chr); aReverse.push(item);chr="";}
                        
                }
                if(setIn<4) {item=reverseWord(chr); aReverse.push(item);}

                for(i=0;i<(aReverse.length);++i) {
                        var revItem = "";
                        item = aReverse[i];
                        
                        for(j=0;j<3;++j) {
                                chr = item[j];
                                if(typeof(chr) != "undefined") {
                                        revItem = revItem + String.fromCharCode(chr.charCodeAt(0)-takeAsc);
                                }
                        }
                        reverse = reverse + revItem;
                        
                }    
                return(reverse)
        }
        
        function reverseWord(param) {
                var aTempo = [];
                for(i=0;i<param.length;++i) {aTempo.push(param.substr(i,1));}
                var reverse = "";
                for(i=(aTempo.length-1);i>=0;--i) {reverse = reverse + aTempo[i];}
                return(reverse);
        }

