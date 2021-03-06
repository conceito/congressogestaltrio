/**
 * Flash (http://jquery.lukelutman.com/plugins/flash)
 * A jQuery plugin for embedding Flash movies.
 * 
 * Version 1.0
 * November 9th, 2006
 *
 * Copyright (c) 2006 Luke Lutman (http://www.lukelutman.com)
 * Dual licensed under the MIT and GPL licenses.
 * http://www.opensource.org/licenses/mit-license.php
 * http://www.opensource.org/licenses/gpl-license.php
 * 
 * Inspired by:
 * SWFObject (http://blog.deconcept.com/swfobject/)
 * UFO (http://www.bobbyvandersluis.com/ufo/)
 * sIFR (http://www.mikeindustries.com/sifr/)
 * 
 * IMPORTANT: 
 * The packed version of jQuery breaks ActiveX control
 * activation in Internet Explorer. Use JSMin to minifiy
 * jQuery (see: http://jquery.lukelutman.com/plugins/flash#activex).
 *
 **/ 
;(function(){var $$;$$=jQuery.fn.flash=function(htmlOptions,pluginOptions,replace,update){var block=replace||$$.replace;pluginOptions=$$.copy($$.pluginOptions,pluginOptions);if(!$$.hasFlash(pluginOptions.version)){if(pluginOptions.expressInstall&&$$.hasFlash(6,0,65)){var expressInstallOptions={flashvars:{MMredirectURL:location,MMplayerType:'PlugIn',MMdoctitle:jQuery('title').text()}}}else if(pluginOptions.update){block=update||$$.update}else{return this}}htmlOptions=$$.copy($$.htmlOptions,expressInstallOptions,htmlOptions);return this.each(function(){block.call(this,$$.copy(htmlOptions))})};$$.copy=function(){var options={},flashvars={};for(var i=0;i<arguments.length;i++){var arg=arguments[i];if(arg==undefined)continue;jQuery.extend(options,arg);if(arg.flashvars==undefined)continue;jQuery.extend(flashvars,arg.flashvars)}options.flashvars=flashvars;return options};$$.hasFlash=function(){if(/hasFlash\=true/.test(location))return true;if(/hasFlash\=false/.test(location))return false;var pv=$$.hasFlash.playerVersion().match(/\d+/g);var rv=String([arguments[0],arguments[1],arguments[2]]).match(/\d+/g)||String($$.pluginOptions.version).match(/\d+/g);for(var i=0;i<3;i++){pv[i]=parseInt(pv[i]||0);rv[i]=parseInt(rv[i]||0);if(pv[i]<rv[i])return false;if(pv[i]>rv[i])return true}return true};$$.hasFlash.playerVersion=function(){try{try{var axo=new ActiveXObject('ShockwaveFlash.ShockwaveFlash.6');try{axo.AllowScriptAccess='always'}catch(e){return'6,0,0'}}catch(e){}return new ActiveXObject('ShockwaveFlash.ShockwaveFlash').GetVariable('$version').replace(/\D+/g,',').match(/^,?(.+),?$/)[1]}catch(e){try{if(navigator.mimeTypes["application/x-shockwave-flash"].enabledPlugin){return(navigator.plugins["Shockwave Flash 2.0"]||navigator.plugins["Shockwave Flash"]).description.replace(/\D+/g,",").match(/^,?(.+),?$/)[1]}}catch(e){}}return'0,0,0'};$$.htmlOptions={height:240,flashvars:{},pluginspage:'http://www.adobe.com/go/getflashplayer',src:'#',type:'application/x-shockwave-flash',width:320};$$.pluginOptions={expressInstall:false,update:true,version:'6.0.65'};$$.replace=function(htmlOptions){this.innerHTML='<div class="alt">'+this.innerHTML+'</div>';jQuery(this).addClass('flash-replaced').prepend($$.transform(htmlOptions))};$$.update=function(htmlOptions){var url=String(location).split('?');url.splice(1,0,'?hasFlash=true&');url=url.join('');var msg='<p>This content requires the Flash Player. <a href="http://www.adobe.com/go/getflashplayer">Download Flash Player</a>. Already have Flash Player? <a href="'+url+'">Click here.</a></p>';this.innerHTML='<span class="alt">'+this.innerHTML+'</span>';jQuery(this).addClass('flash-update').prepend(msg)};function toAttributeString(){var s='';for(var key in this)if(typeof this[key]!='function')s+=key+'="'+this[key]+'" ';return s};function toFlashvarsString(){var s='';for(var key in this)if(typeof this[key]!='function')s+=key+'='+encodeURIComponent(this[key])+'&';return s.replace(/&$/,'')};$$.transform=function(htmlOptions){htmlOptions.toString=toAttributeString;if(htmlOptions.flashvars)htmlOptions.flashvars.toString=toFlashvarsString;return'<embed '+String(htmlOptions)+'/>'};if(window.attachEvent){window.attachEvent("onbeforeunload",function(){__flash_unloadHandler=function(){};__flash_savedUnloadHandler=function(){}})}})();

$(document).ready(function(e) {
    $('li.flash-banner', 'ul.banners-list').flash(null, { version: 9 }, function(htmlOptions) {
        var $this = $(this),
			params = $this.attr('rel').split('|');		
		
        htmlOptions.src = params[0];
        htmlOptions.width = params[1];
        htmlOptions.height = params[2];
		htmlOptions.flashvars = {url: params[3]};
        this.innerHTML = '<div class="alt">'+this.innerHTML+'</div>';
        $this.addClass('flash-replaced').prepend($.fn.flash.transform(htmlOptions));
    });
});