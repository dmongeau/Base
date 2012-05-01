(function($){$.toJSON=function(o)
{if(typeof(JSON)=='object'&&JSON.stringify)
return JSON.stringify(o);var type=typeof(o);if(o===null)
return"null";if(type=="undefined")
return undefined;if(type=="number"||type=="boolean")
return o+"";if(type=="string")
return $.quoteString(o);if(type=='object')
{if(typeof o.toJSON=="function")
return $.toJSON(o.toJSON());if(o.constructor===Date)
{var month=o.getUTCMonth()+1;if(month<10)month='0'+month;var day=o.getUTCDate();if(day<10)day='0'+day;var year=o.getUTCFullYear();var hours=o.getUTCHours();if(hours<10)hours='0'+hours;var minutes=o.getUTCMinutes();if(minutes<10)minutes='0'+minutes;var seconds=o.getUTCSeconds();if(seconds<10)seconds='0'+seconds;var milli=o.getUTCMilliseconds();if(milli<100)milli='0'+milli;if(milli<10)milli='0'+milli;return'"'+year+'-'+month+'-'+day+'T'+
hours+':'+minutes+':'+seconds+'.'+milli+'Z"';}
if(o.constructor===Array)
{var ret=[];for(var i=0;i<o.length;i++)
ret.push($.toJSON(o[i])||"null");return"["+ret.join(",")+"]";}
var pairs=[];for(var k in o){var name;var type=typeof k;if(type=="number")
name='"'+k+'"';else if(type=="string")
name=$.quoteString(k);else
continue;if(typeof o[k]=="function")
continue;var val=$.toJSON(o[k]);pairs.push(name+":"+val);}
return"{"+pairs.join(", ")+"}";}};$.evalJSON=function(src)
{if(typeof(JSON)=='object'&&JSON.parse)
return JSON.parse(src);return eval("("+src+")");};$.secureEvalJSON=function(src)
{if(typeof(JSON)=='object'&&JSON.parse)
return JSON.parse(src);var filtered=src;filtered=filtered.replace(/\\["\\\/bfnrtu]/g,'@');filtered=filtered.replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g,']');filtered=filtered.replace(/(?:^|:|,)(?:\s*\[)+/g,'');if(/^[\],:{}\s]*$/.test(filtered))
return eval("("+src+")");else
throw new SyntaxError("Error parsing JSON, source is not valid.");};$.quoteString=function(string)
{if(string.match(_escapeable))
{return'"'+string.replace(_escapeable,function(a)
{var c=_meta[a];if(typeof c==='string')return c;c=a.charCodeAt();return'\\u00'+Math.floor(c/16).toString(16)+(c%16).toString(16);})+'"';}
return'"'+string+'"';};var _escapeable=/["\\\x00-\x1f\x7f-\x9f]/g;var _meta={'\b':'\\b','\t':'\\t','\n':'\\n','\f':'\\f','\r':'\\r','"':'\\"','\\':'\\\\'};})(jQuery);

(function($){jQuery.fn.pngFix=function(settings){settings=jQuery.extend({blankgif:"blank.gif"},settings);var ie55=navigator.appName=="Microsoft Internet Explorer"&&parseInt(navigator.appVersion)==4&&navigator.appVersion.indexOf("MSIE 5.5")!=-1;var ie6=navigator.appName=="Microsoft Internet Explorer"&&parseInt(navigator.appVersion)==4&&navigator.appVersion.indexOf("MSIE 6.0")!=-1;if(jQuery.browser.msie&&(ie55||ie6)){jQuery(this).find("img[src$=.png]").each(function(){jQuery(this).attr("width",jQuery(this).width());
jQuery(this).attr("height",jQuery(this).height());var prevStyle="";var strNewHTML="";var imgId=jQuery(this).attr("id")?'id="'+jQuery(this).attr("id")+'" ':"";var imgClass=jQuery(this).attr("class")?'class="'+jQuery(this).attr("class")+'" ':"";var imgTitle=jQuery(this).attr("title")?'title="'+jQuery(this).attr("title")+'" ':"";var imgAlt=jQuery(this).attr("alt")?'alt="'+jQuery(this).attr("alt")+'" ':"";var imgAlign=jQuery(this).attr("align")?"float:"+jQuery(this).attr("align")+";":"";var imgHand=jQuery(this).parent().attr("href")?
"cursor:hand;":"";if(this.style.border){prevStyle+="border:"+this.style.border+";";this.style.border=""}if(this.style.padding){prevStyle+="padding:"+this.style.padding+";";this.style.padding=""}if(this.style.margin){prevStyle+="margin:"+this.style.margin+";";this.style.margin=""}var imgStyle=this.style.cssText;strNewHTML+="<span "+imgId+imgClass+imgTitle+imgAlt;strNewHTML+='style="position:relative;white-space:pre-line;display:inline-block;background:transparent;'+imgAlign+imgHand;strNewHTML+="width:"+
jQuery(this).width()+"px;"+"height:"+jQuery(this).height()+"px;";strNewHTML+="filter:progid:DXImageTransform.Microsoft.AlphaImageLoader"+"(src='"+jQuery(this).attr("src")+"', sizingMethod='scale');";strNewHTML+=imgStyle+'"></span>';if(prevStyle!="")strNewHTML='<span style="position:relative;display:inline-block;'+prevStyle+imgHand+"width:"+jQuery(this).width()+"px;"+"height:"+jQuery(this).height()+"px;"+'">'+strNewHTML+"</span>";jQuery(this).hide();jQuery(this).after(strNewHTML)});jQuery(this).find("*").each(function(){var bgIMG=
jQuery(this).css("background-image");if(bgIMG.indexOf(".png")!=-1){var iebg=bgIMG.split('url("')[1].split('")')[0];jQuery(this).css("background-image","none");jQuery(this).get(0).runtimeStyle.filter="progid:DXImageTransform.Microsoft.AlphaImageLoader(src='"+iebg+"',sizingMethod='scale')"}});jQuery(this).find("input[src$=.png]").each(function(){var bgIMG=jQuery(this).attr("src");jQuery(this).get(0).runtimeStyle.filter="progid:DXImageTransform.Microsoft.AlphaImageLoader"+"(src='"+bgIMG+"', sizingMethod='scale');";
jQuery(this).attr("src",settings.blankgif)})}return jQuery}})(jQuery);


// minmax.js: make IE5+/Win support CSS min/max-width/height
// version 1.0, 08-Aug-2003
// written by Andrew Clover <and@doxdesk.com>, use freely

/*@cc_on
@if (@_win32 && @_jscript_version>4)

var minmax_elements;

minmax_props= new Array(
  new Array('min-width', 'minWidth'),
  new Array('max-width', 'maxWidth'),
  new Array('min-height','minHeight'),
  new Array('max-height','maxHeight')
);

// Binding. Called on all new elements. If <body>, initialise; check all
// elements for minmax properties

function minmax_bind(el) {
  var i, em, ms;
  var st= el.style, cs= el.currentStyle;

  if (minmax_elements==window.undefined) {
    // initialise when body element has turned up, but only on IE
    if (!document.body || !document.body.currentStyle) return;
    minmax_elements= new Array();
    window.attachEvent('onresize', minmax_delayout);
    // make font size listener
    em= document.createElement('div');
    em.setAttribute('id', 'minmax_em');
    em.style.position= 'absolute'; em.style.visibility= 'hidden';
    em.style.fontSize= 'xx-large'; em.style.height= '5em';
    em.style.top='-5em'; em.style.left= '0';
    if (em.style.setExpression) {
      em.style.setExpression('width', 'minmax_checkFont()');
      document.body.insertBefore(em, document.body.firstChild);
    }
  }

  // transform hyphenated properties the browser has not caught to camelCase
  for (i= minmax_props.length; i-->0;)
    if (cs[minmax_props[i][0]])
      st[minmax_props[i][1]]= cs[minmax_props[i][0]];
  // add element with properties to list, store optimal size values
  for (i= minmax_props.length; i-->0;) {
    ms= cs[minmax_props[i][1]];
    if (ms && ms!='auto' && ms!='none' && ms!='0' && ms!='') {
      st.minmaxWidth= cs.width; st.minmaxHeight= cs.height;
      minmax_elements[minmax_elements.length]= el;
      // will need a layout later
      minmax_delayout();
      break;
  } }
}

// check for font size changes

var minmax_fontsize= 0;
function minmax_checkFont() {
  var fs= document.getElementById('minmax_em').offsetHeight;
  if (minmax_fontsize!=fs && minmax_fontsize!=0)
    minmax_delayout();
  minmax_fontsize= fs;
  return '5em';
}

// Layout. Called after window and font size-change. Go through elements we
// picked out earlier and set their size to the minimum, maximum and optimum,
// choosing whichever is appropriate

// Request re-layout at next available moment
var minmax_delaying= false;
function minmax_delayout() {
  if (minmax_delaying) return;
  minmax_delaying= true;
  window.setTimeout(minmax_layout, 0);
}

function minmax_stopdelaying() {
  minmax_delaying= false;
}

function minmax_layout() {
  window.setTimeout(minmax_stopdelaying, 100);
  var i, el, st, cs, optimal, inrange;
  for (i= minmax_elements.length; i-->0;) {
    el= minmax_elements[i]; st= el.style; cs= el.currentStyle;

    // horizontal size bounding
    st.width= st.minmaxWidth; optimal= el.offsetWidth;
    inrange= true;
    if (inrange && cs.minWidth && cs.minWidth!='0' && cs.minWidth!='auto' && cs.minWidth!='') {
      st.width= cs.minWidth;
      inrange= (el.offsetWidth<optimal);
    }
    if (inrange && cs.maxWidth && cs.maxWidth!='none' && cs.maxWidth!='auto' && cs.maxWidth!='') {
      st.width= cs.maxWidth;
      inrange= (el.offsetWidth>optimal);
    }
    if (inrange) st.width= st.minmaxWidth;

    // vertical size bounding
    st.height= st.minmaxHeight; optimal= el.offsetHeight;
    inrange= true;
    if (inrange && cs.minHeight && cs.minHeight!='0' && cs.minHeight!='auto' && cs.minHeight!='') {
      st.height= cs.minHeight;
      inrange= (el.offsetHeight<optimal);
    }
    if (inrange && cs.maxHeight && cs.maxHeight!='none' && cs.maxHeight!='auto' && cs.maxHeight!='') {
      st.height= cs.maxHeight;
      inrange= (el.offsetHeight>optimal);
    }
    if (inrange) st.height= st.minmaxHeight;
  }
}

// Scanning. Check document every so often until it has finished loading. Do
// nothing until <body> arrives, then call main init. Pass any new elements
// found on each scan to be bound   

var minmax_SCANDELAY= 500;

function minmax_scan() {
  var el;
  for (var i= 0; i<document.all.length; i++) {
    el= document.all[i];
    if (!el.minmax_bound) {
      el.minmax_bound= true;
      minmax_bind(el);
  } }
}

var minmax_scanner;
function minmax_stop() {
  window.clearInterval(minmax_scanner);
  minmax_scan();
}

minmax_scan();
minmax_scanner= window.setInterval(minmax_scan, minmax_SCANDELAY);
window.attachEvent('onload', minmax_stop);

@end @*/


/*****************************************************************************************

		Sparko.ca - jQuery Plugins and Functions - 1.0
		Author : David Mongeau-Petitpas

******************************************************************************************/
jQuery.fn.extend({
	
	//////////////////////////////////////////////////
	//////Text Hints in input
	//////////////////////////////////////////////////
	hint: function(action) {
		if(!$(this).length) { return false; }
		else {
			
			function init(el) {
				el.each(function() {
					var input = $(this);
					input.focus(function() {
						if($(this).val() == $(this).attr("title")) {
							hide($(this));
						}
					}).blur(function() {
						if(jQuery.trim($(this).val()) == "") {
							show($(this));
						}
					});
					
					input.parents("form").submit(function() {
						if(input.val() == input.attr("title")) { hide(input); }
					});
					if(!$.trim($(this).val()).length) {
						show($(this));
					}
				});
			}
			
			function hide(el) {
				$(el).each(function() {
					$(this).removeClass("hasHint");
					if($(this).val() == $(this).attr("title")) { $(this).val(""); }
				});
			}
			
			function show(el) {
				$(el).each(function() {
					$(this).val($(this).attr("title")).addClass("hasHint");
				});
			}
			
			if(!action) {
				init($(this));
			} else if(action == "hide") {
				hide($(this));
			} else if(action == "show") {
				show($(this));
			} else {
				init($(this));
			}
		}
		return this;
	},
	
	collapsable : function() {
		
		if(!$(this).length) { return; }
		var $el = $(this);
		
		$el.hint();
		
		$el.focus(function() {
			if($(this).val() == $(this).attr("title") || !$.trim($(this).val()).length) {
				$(this).animate({height:'200px'},500);
			}
		});
		
		$el.blur(function() {
			if(!$.trim($(this).val()).length || $(this).val() == $(this).attr("title")) {
				$(this).animate({height:'30px'},300);
			}
		});
		
		$el.each(function() {
			if(!$.trim($(this).val()).length || $(this).val() == $(this).attr("title")) {
				$(this).height(30);
			}
		});
		
		$el.parents("form").one('submit',function() {
			$el.each(function() {
				if(!$.trim($(this).val()).length || $(this).val() == $(this).attr("title")) {
					$(this).val("");
				}
			});
		});
		
		return $(this);
	},
	
	//////////////////////////////////////////////////
	//////Text Hints in input
	//////////////////////////////////////////////////
	isValid: function(type) {
		var val = $(this).val();
		
		if(type == "email") {
			if(/^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/.test(val) == false) { return false; }
		}
		else if(type == "postalcode") {
			if (val.length == 6 && val.search(/^[a-zA-Z]\d[a-zA-Z]\d[a-zA-Z]\d$/) != -1) return true;
			else if (val.length == 7 && val.search(/^[a-zA-Z]\d[a-zA-Z](-|\s)\d[a-zA-Z]\d$/) != -1) return true;
			else return false;
		}
		
		return true;
	},
	
	//////////////////////////////////////////////////
	//////Form Validation
	//////////////////////////////////////////////////
	"validate" : function(opts) {
	
		var form = $(this);
		var isValid = true;
		
		var options = $.extend({}, {
			'validIcon' : '/statics/img/btn/bon.gif',
			'invalidIcon' : '/statics/img/btn/mal.gif',
			'validClass' : 'valid',
			'invalidClass' : 'invalid'
		}, opts);
		
		function showError(el) {
			el.addClass("redbg").after("<img src='"+options.invalidIcon+"' class='validIcons' />");
		}

		form.find("img.validIcons").remove();
		form.find("input, select, textarea").removeClass("redbg");
		
		form.find("input.required, select.required, textarea.required").each(function() {
		
			if($(this).hasClass("email") && !$(this).isValid("email")) {
				showError($(this));
				isValid = false;
			} else if($(this).attr("type") == "checkbox" && !$(this).is(':checked')) {
				showError($(this));
				isValid = false;
			} else if (!$.trim($(this).val()).length) {
				showError($(this));
				isValid = false;
			}
			
		});
		
		return isValid;
	
	}
	
});

function encode_base36(number) {
	number = parseInt(number);
	var a = 'a'; a = a.charCodeAt(0);
	var first =  number % 36;
	var str = (first < 10)? first+"":(String.fromCharCode(first-10+a))+"";
	do {
		number = number/36;
		var second = Math.floor(number) % 36;
		str += (second < 10)? second+"":(String.fromCharCode(second-10+a))+"";
	} while(number > 36);

	return str;

}

function decode_base36 (str) {
	var a = 'a'; a = a.charCodeAt(0);
	var val = 0;
	for(var i = 0; i < str.length; i++){
		val += ((str.charCodeAt(i) < a)? parseInt(str.substr(i,1)):(str.charCodeAt(i) - a+10)) * Math.pow(36,i);
	}
	return val;


}


String.prototype.noaccent = function() {
  return this.replace(/[àâä]/gi,"a").replace(/[éèêë]/gi,"e").replace(/[îï]/gi,"i").replace(/[ôö]/gi,"o").replace(/[ùûü]/gi,"u");
};

String.prototype.ext = function(ext) {
  if(this.indexOf("?") >= 0) return this.replace(/\.([a-zA-z]{1,4})\?/gi, ((ext.substr(0,1) == ".") ? ext:"."+ext) + "?");
  else if(this.indexOf("&") >= 0) return this.replace(/\.([a-zA-z]{1,4})\&/gi, ((ext.substr(0,1) == ".") ? ext:"."+ext) + "&");
  else return this.replace(/\.([a-zA-z]{1,4})$/gi, (ext.substr(0,1) == ".") ? ext:"."+ext); 
};