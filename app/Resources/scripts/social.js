(function($){
    'use strict';
    $('.like').on('click', function()
    {
        var like = $(this);
        $.ajax({
            type: 'POST',
            url: like.data('path'),
            data: {id: 1},
            success: function(response)
                {
                    if (response === 'success')
                    {
                        var text = like.text();
                        like.text(text === 'Like' ? 'Unlike' : 'Like');
                    }
                }
        });
    });
})(jQuery);