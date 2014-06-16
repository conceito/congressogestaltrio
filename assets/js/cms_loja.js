/************************************************************

*	Scripts para manupulação de produtos do módulo 'loja'  	*

*************************************************************
*/
$(document).ready(function(e) {
    
	/*******************************************************
	*	Adicionando produtos via AJAX
	*/
	$('button', '#product-options').on('click', function(e){
		e.preventDefault();
		
		// serializa dados do form
		var data = $('#product-options').serialize();
		
		// envia produto
		$.ajax({
			type: "POST",
			url: CMS.site_url + 'loja/adicionar/' + $('input[name=product_id]', '#product-options').val(),
			data: data,
			dataType: ($.browser.msie) ? "text" : "html",
			beforeSend: function() {
				// loading				
			},
			success: function(html) {				
				console.log(html);
				$('#cart').html(html);
				
			},
			complete: function(){
				
				var ctx = '#page',
					image = $('img', ctx).first(),
					img_offset = image.offset(),
					cart  = $('#cart').offset();
				
				if(image.length == 0){
					// não existe imagem
					// pega o título
					var h1 = $('h1', ctx), 
					h1_offset = h1.offset();
					
					h1.before('<h1 id="temp" style="position: absolute; top: ' + 
					h1_offset.top + 'px; left: ' + h1_offset.left + 'px;">'+h1.text()+'</h1>');
					
				} else {
					// ok, é uma imagem	
					image.before('<img src="' + image.attr('src') + '" id="temp" style="position: absolute; top: ' + 
					img_offset.top + 'px; left: ' + img_offset.left + 'px;" />');
				}
		
				params = {
					top : cart.top + 'px',
					left : cart.left + 'px',
					opacity : 0.0,
					width : $('#cart').width(),  
					height : $('#cart').height()
				};		
		
				$('#temp').animate(params, 'slow', false, function () {
					$('#temp').remove();
				});		
				
			}
		});// ajax
		
	});//on
});