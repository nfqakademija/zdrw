(function($){
    'use strict';

    $('.notification').on('click', function()
    {
        var notification = $(this);
        var id = $(this).data('id');
        var url = $(this).data('url');
        $.ajax({
            type: 'POST',
            url: url,
            data: {'id': id},
            success: function()
            {
                notification.css({ backgroundColor: '#CDCAB9' });

                notification.mouseenter(function() {
                    $(this).css({ backgroundColor: '#CDCAB9' });
                }).mouseleave(function() {
                    $(this).css({ backgroundColor: '#E0DDD4' });
                });
            }
        });
    });
})(jQuery);