(function($){
    'use strict';

    var points = $('#points').val();
    points = parseInt(points);
    console.log(points);
    $('.plus').click(function(){
        $('#points').val(points += 1);
    });
    $('.minus').click(function(){
        if(points > 0){
        $('#points').val(points -= 1);
        }
    });
})(jQuery);