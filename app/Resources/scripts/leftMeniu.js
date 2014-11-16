(function($){
    'use strict';

    var myToggle = 0;
    $('.left-sidebar-button').click(function(){

        var contentOffset = $('.page-content').offset().left;
        var pixelsLeft = 200 - contentOffset;

        function slideBar(marginLeft, buttonMarginLeft, pixelsLeft){
            $('.left-sidebar').animate({
                left: marginLeft
            }, 300, function() {
                myToggle += 1;
                $('.left-sidebar-button').animate({
                    marginLeft: buttonMarginLeft
                }, 300, function() {
                });
            });
            if (contentOffset < 200) {
                $('.page-content').animate({
                    paddingLeft: pixelsLeft
                }, 300, function() {
                });
            }
        }

        if(myToggle%2 === 0){
            slideBar('0', '-32', pixelsLeft);
        }
        else{
            slideBar('-200','0', '15');
        }
    });
    $( window ).resize(function() {
        if($(window).width() < 755){
            $('.left-sidebar').hide();
        }
        else{
            $('.left-sidebar').show();
        }
    });
})(window.jQuery);