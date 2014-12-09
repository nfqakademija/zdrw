(function($){
    'use strict';

    function numericInput(points){
        var myPoints = $(points).val();
        myPoints = parseInt(myPoints);
        $('.plus').click(function(){
            $(points).val(myPoints += 1);
        });
        $('.minus').click(function(){
            if(myPoints > 0){
                $(points).val(myPoints -= 1);
            }
        });
    }

    numericInput('#points');
    numericInput('#offer_rewards');

})(jQuery);