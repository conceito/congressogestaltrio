/**************************************************************************** 

*	Scripts para carregamento assíncrono de banners e contagem de exibição	*

*****************************************************************************
*/

$(document).ready(function(e) {
	
	// ? Como usar ?
	$('#content-top').cmsbanner({
		group_id: 106,
		limit: 1, // false
		
		// instanciando plugin
		onComplete: function(){
			
			$(this).find('.slider').bxSlider({
				mode: 'horizontal',
				speed: 300,
				pause: 9000,
				auto: true,
				autoHover: true,
				controls: false,
				pager: false,
				randomStart: true
			});
				
		}
	});
	

	
});


/**************************************** 
*	Plugin para manipulação dos banners	*
*   @dependencies: jQuery 7+ 
*/

;(function ( $, window, undefined ) {

  // Create the defaults once
  var pluginName = 'cmsbanner',
      document = window.document,
      defaults = {
        site_url: CMS.site_url,   // URL absoluta do framework
		load_ctlr: 'banner/load', // controller responsável por carregar banner
		group_id: null,           // ID do grupo de banners
		limit: false,             // limita a quantidade de banners, false = infinite
		onComplete: function() {},
		onError: function(){}
      };

  // The actual plugin constructor
  function Plugin( element, options ) {
    this.element = element;

    // jQuery has an extend method which merges the contents of two or 
    // more objects, storing the result in the first object. The first object
    // is generally empty as we don't want to alter the default options for
    // future instances of the plugin
    this.options = $.extend( {}, defaults, options) ;

    this._defaults = defaults;
    this._name = pluginName;

    this.init();
  }

  Plugin.prototype.init = function () {
    // Place initialization logic here
    // You already have access to the DOM element and the options via the instance, 
    // e.g., this.element and this.options
	
	this.get_banners_from_group(this.options.group_id, this.options.limit);
	
	// injeta o resultado no elemento
	//console.log(ret);
	//$(this.element).html(ret);
	
  };
  
  //
  // Carrega HTML completo dos banners de um grupo
  // 
  Plugin.prototype.get_banners_from_group = function (group_id, limit) {
	  
	  var opts = this.options,
	  	  su   = opts.site_url,
	  	  ctrl = opts.load_ctlr,
		  self = this.element,
		  html = '';
		  
	  limit = limit || 0;
	  
	  $.ajax({
			type: "POST",
			url: su + ctrl + '/'+group_id+'/'+limit,
			//data: "group_id="+group_id+"&limit="+limit,
			dataType: ($.browser.msie) ? "text" : "html",
			beforeSend: function() {
				// loading				
			},
			success: function(message) {				
				
				if (message.length > 10){
					
					$(self).html(message);					
					opts.onComplete.call(self);					
					
				} else {
					
					$(self).html('erro ao carregar...');
					opts.onError.call(self);
					
				}
			}
		});
	  
  };

  // A really lightweight plugin wrapper around the constructor, 
  // preventing against multiple instantiations
  $.fn[pluginName] = function ( options ) {
    return this.each(function () {
      if (!$.data(this, 'plugin_' + pluginName)) {
        $.data(this, 'plugin_' + pluginName, new Plugin( this, options ));
      }
    });
  }

}(jQuery, window));
