(function($){
    'use strict';

    var notification = $('.notification');
    var notificationId;
    var notificationUrl;

    if (notification.length > 0) {
        notification.each(function(){
            notificationId = $(this).data('id');
            notificationUrl = $(this).data('url');
            if ($(this).data('seen') === 0) {
                $.ajax({
                    type: 'POST',
                    url: notificationUrl,
                    data: {'id': notificationId},
                    success: function()
                    {
                    }
                });
            }
        });
    }
})(jQuery);