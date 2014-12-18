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
                    var likesNumHolder =  $('.pull-right').find('#likes-num');
                    var likesNum = parseInt(likesNumHolder.text());
                    likesNumHolder.text(' ' + (text === 'Like' ? likesNum + 1 : likesNum - 1));
                }
            }
        });
    });
})(jQuery);
/*jshint unused:false*/
function commentDare(ajaxlink){
    'use strict';
    var form = $('#commentForm');
    var text = form.find('textarea[name="commentText"]').val();
    if (text && text !== '') {
        var id = form.find('input[name="dare-id"]').val();
        form.hide();
        $('#loading-bar').show();
        $.ajax({
            type: 'POST',
            url: ajaxlink,
            data: {text: text, id: id},
            success: function (response) {
                if (response === 'success') {
                    $('textarea[name="commentText"]').val('');
                    $('#successBox').show();
                    $('#comment-template').clone().removeAttr('id').show().prependTo('#into').find('p').text(text);
                    $('#loading-bar').hide();
                    form.show();
                    var commentsNumHolder =  $('.pull-right').find('#comments-num');
                    var commentsNum = parseInt(commentsNumHolder.text());
                    commentsNumHolder.text(' ' + (commentsNum + 1));
                }
            }
        });
    }
}