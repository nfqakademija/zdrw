(function($){
    'use strict';

    $('.login-link').click(function(){
        var link = $(this).data('link');
        if($(window).width() < 768) {
            window.location.replace(link);
        }
    });
})(jQuery);