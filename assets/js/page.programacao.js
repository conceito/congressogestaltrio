function log(a, b){
	if(typeof console !== "undefined" || typeof console.log !== "undefined"){
		console.log(a, b);
	}
}
$(document).ready(function () {



	setTimeout(recalcActivitiesHeight, 100);
	function recalcActivitiesHeight() {

		$('.activity').each(function () {
			var a = $(this),
				td = a.parent();

			a.height('auto');

			if (td.innerHeight() > a.innerHeight()) {
				a.outerHeight(td.innerHeight());
			}
		});
	}

	$('.activity').eventDetail();


	$(window).on('resize', function () {
		recalcActivitiesHeight();
	});


});


/**
 * --------------------------------------------------------------------
 * Event navigation
 */

var eventDetailOpened = false;
(function ($, window, undefined) {


	var pluginName = 'eventDetail',
		document = window.document,
		defaults = {
			propertyName: "value"
		},
		winW, winH, smallCardW, smallCardH, smallCardX, smallCardY, bigCardW, bigCardH;

	//window.eventDetailGlobals = {
	//	isOpened: false
	//};


	// The actual plugin constructor
	function Plugin(element, options) {
		this.element = element;
		this.$elem = $(element);


		// jQuery has an extend method which merges the contents of two or
		// more objects, storing the result in the first object. The first object
		// is generally empty as we don't want to alter the default options for
		// future instances of the plugin
		this.options = $.extend({}, defaults, options);

		this._defaults = defaults;
		this._name = pluginName;

		this.init();
	}


	Plugin.prototype.init = function () {
		var self = this;
		this.$elem.on('click', function (e) {
			e.preventDefault();
			self.open();
		});

		$(window).on('resize', self.environmentUpdate);
	};

	/**
	 * open modal
	 */
	Plugin.prototype.open = function () {

		var self = this;

		// remove any opened
		self.removeAll();
		// pop up backdrop
		self.setBackdrop();
		// pop up small card
		if (self.isOpen()) {
			setTimeout(function () {
				self.openCard();
			}, 400);
		} else {
			self.openCard();
		}

	};

	/**
	 * Open the small card, animate to big card
	 */
	Plugin.prototype.openCard = function () {

		var self = this;
		eventDetailOpened = true;

		// copy small card
		var smallCopy = this.$elem.clone().addClass('ed-cloned');
		smallCardW = this.$elem.outerWidth();
		smallCardH = this.$elem.outerHeight();
		smallCardX = this.$elem.offset().left;
		smallCardY = this.$elem.offset().top - $(window).scrollTop();

		var finalWidth = 400,
			maxQuickWidth = 900,
			windowWidth = $(window).width(),
			windowHeight = $(window).height(),
			finalLeft = (windowWidth - finalWidth) / 2,
			finalHeight = finalWidth * smallCardH / smallCardW,
			finalTop = (windowHeight - finalHeight) / 2,
			quickViewWidth = ( windowWidth * .8 < maxQuickWidth ) ? windowWidth * .8 : maxQuickWidth,
			quickViewLeft = (windowWidth - quickViewWidth) / 2,
			finalHeight = 500,
		finalCardTop = (windowHeight - finalHeight) /2 ;

		// wrapper with container
		//var smallCopy2 = smallCopy.wrap('<div class="smallCard-wrapper"></div>');

		var card = $('.ed-card-wrapper');

		//console.log();
		var content = $(this.$elem.attr('href')).html();
		card.find('.content').empty().append(content);

		card.find('.small-card').empty().append(smallCopy);
		card.css({
			width: smallCardW,
			height: smallCardH,
			top: smallCardY,
			left: smallCardX,
			opacity: 1
		})
			.velocity({
				//animate the quick view: animate its width and center it in the viewport
				//during this animation, only the slider image is visible
				'top': finalTop + 'px',
				'left': finalLeft + 'px',
				'width': finalWidth + 'px'
			}, {
				duration: 600,
				easing: [400, 20],

				complete: function(elements) {
					card.find('.activity').velocity({
						opacity: 0
					});

					//animate the quick view: animate its width to the final value
					card.velocity({
						top: finalCardTop,
						'left': quickViewLeft+'px',
						'width': quickViewWidth+'px',
						'height': finalHeight+'px'
					}, 300, 'ease' ,function(){
						//show quick view content
						//$('.cd-quick-view').addClass('add-content');
					}).addClass('is-visible');
				}
			});

		card.find('.close').on('click', function(e){
			e.preventDefault();
			self.removeAll();
		});


		//log('smallCardW', smallCardW);
		//log('smallCardH', smallCardH);
		//log('smallCardX', smallCardX);
		//log('smallCardY', smallCardY);
		//log(smallCopy);
		// animate to big card

		// update class

	};

	Plugin.prototype.isOpen = function(){
		return $('body').hasClass('ed-is-opened');
	};

	/**
	 * Remove the opened instance
	 */
	Plugin.prototype.removeAll = function () {
		var self = this;

		if (self.isOpen()) {
			self.unsetBackdrop();
			//
			var card = $('.ed-card-wrapper').removeClass('is-visible').velocity({
				width: smallCardW,
				height: smallCardH,
				top: smallCardY,
				left: smallCardX
			}, {
				duration: 400,

				complete: function(){
					//card.css({opacity: 0, width:0, height:0});
					card.velocity({opacity: 0}, {
						delay: 100,
						complete: function(){
							card.css({opacity: 0, width:0, height:0});
						}
					});
				}
			});

			card.find('.activity').velocity({
				opacity: 1
			});

			log("removing");
		}
	};

	/**
	 * Show the backdrop
	 */
	Plugin.prototype.setBackdrop = function () {
		var self = this;
		$('body').addClass('ed-is-opened');

		setTimeout(function(){
			$('.ed-backdrop').addClass('open').on('click', function (e) {
				e.preventDefault();
				self.removeAll();
			});
		}, 400);

	};

	/**
	 * Remove the backdrop
	 */
	Plugin.prototype.unsetBackdrop = function () {
		$('body').removeClass('ed-is-opened');
		$('.ed-backdrop').removeClass('open');
	};

	/**
	 * On window resize update the hole variables
	 */
	Plugin.prototype.environmentUpdate = function () {
		var w = $(window);

		winW = w.width();
		winH = w.height();
	};




	setBaseTemplate = function () {

		var cardContainer = '<div class="ed-card-wrapper"><div class="small-card"></div><div class="close">&times;</div><div class="content"></div></div>';
		var backdrop = '<div class="ed-backdrop"></div>';
		$('body').append(cardContainer + backdrop);

	};

	// A really lightweight plugin wrapper around the constructor,
	// preventing against multiple instantiations
	$.fn[pluginName] = function (options) {

		setBaseTemplate();

		return this.each(function () {
			if (!$.data(this, 'plugin_' + pluginName)) {
				$.data(this, 'plugin_' + pluginName, new Plugin(this, options));
			}
		});
	};

	//window[pluginName] = Plugin;


}(jQuery, window));