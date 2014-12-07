(function($){
    'use strict';

    $('.cuadro_intro_hover .caption p').hide();

    $('.cuadro_intro_hover').hover(
        function(){
            $(this).find('.caption p').stop().fadeTo('fast', 1);
        },
        function(){
            $(this).find('.caption p').stop().fadeTo('fast', 0);
    });
})(jQuery);