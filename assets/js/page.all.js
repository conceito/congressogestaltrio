(function($, window, document, undefined){

    $(document).ready(function(){

        $('.flexslider').flexslider({
            animation: "slide",
            animationLoop: true,
            start: function(slider){
                $('body').removeClass('loading');
            }
        });

        // initialise plugin
        var example = $('#main-menu').superfish({
            delay: 200,
            speed: 300
        });

        // buttons to demonstrate Superfish's public methods
        $('.destroy').on('click', function(){
            example.superfish('destroy');
        });

        $('.init').on('click', function(){
            example.superfish();
        });

        $('.open').on('click', function(){
            example.children('li:first').superfish('show');
        });

        $('.close').on('click', function(){
            example.children('li:first').superfish('hide');
        });

//        $(document)
//            .on('click.bs.dropdown.data-api', clearMenus)
//            .on('click.bs.dropdown.data-api', '.dropdown form', function (e) { e.stopPropagation() })
//            .on('click.bs.dropdown.data-api'  , toggle, Dropdown.prototype.toggle)
//            .on('keydown.bs.dropdown.data-api', toggle + ', [role=menu]' , Dropdown.prototype.keydown)
//            .on('click', clearAllMenus)

    });

})(jQuery, window, document);


