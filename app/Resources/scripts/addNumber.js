(function($){
    'use strict';

    var points = $('#points').val();
    points = parseInt(points);
    $('.plus').click(function(){
        $('#points').val(points += 1);
    });
    $('.minus').click(function(){
        if(points > 0){
        $('#points').val(points -= 1);
        }
    });

    var points2 = $('#offer_rewards').val();
    points2 = parseInt(points2);
    $('.plus').click(function(){
        $('#offer_rewards').val(points2 += 1);
        console.log(points2);
    });
    $('.minus').click(function(){
        if(points2 > 0){
            $('#offer_rewards').val(points2 -= 1);
        }
        console.log(points2);
    });
})(jQuery);