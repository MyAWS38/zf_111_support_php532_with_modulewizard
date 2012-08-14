// Removes leading whitespaces
function LTrim( value ) { var re = /\s*((\S+\s*)*)/; return value.replace(re, "$1"); }    
// Removes ending whitespaces
function RTrim( value ) { var re = /((\s*\S+)*)\s*/; return value.replace(re, "$1"); }    
// Removes leading and ending whitespaces
function trim( value ) { if (browser!='MSIE') return LTrim(RTrim(value));  else{ str = value.replace(/^\s+/, ''); for (var i = str.length - 1; i >= 0; i--) {
		if (/\S/.test(str.charAt(i))) {
			str = str.substring(0, i + 1);
			break;
		}
	    }
	return str;
    }
}
function htmlEntities(texto){
    //by Micox - elmicoxcodes.blogspot.com - www.ievolutionweb.com
        var i,carac,letra,novo='';
        for(i=0;i<texto.length;i++){
            carac = texto[i].charCodeAt(0);
            if( (carac > 47 && carac < 58) || (carac > 62 && carac < 127) ){
                //se for numero ou letra normal
                novo += texto[i];
            }else{
                novo += "&#" + carac + ";";
            }
        }
        return novo;
}

var numb = '0123456789';
var lwr = 'abcdefghijklmnopqrstuvwxyz';
var upr = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';

function isValid(parm,val) {
    if (parm == "") return true;
    for (i=0; i<parm.length; i++) {
        if (val.indexOf(parm.charAt(i),0) == -1) return false;
    }
    return true;
}

function isNumber(parm) {return isValid(parm,numb);}
function isLower(parm) {return isValid(parm,lwr);}
function isUpper(parm) {return isValid(parm,upr);}
function isAlpha(parm) {return isValid(parm,lwr+upr);}
function isAlphanum(parm) {return isValid(parm,lwr+upr+numb);}