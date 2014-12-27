$(document).ready(function() {

    $('.toggle-active').click(function(e) {
        e.preventDefault();
        var parentBlock = $(this).parent().parent().parent().parent().prev();
        var that = $(this);
        var url = $(this).attr('data-url');

        $.post(url, function(response) {
            if (response.success == 1) {
                if (response.params.active == 0) {
                    parentBlock.find('h3').after($('<span></span>').addClass('off').text('Turned off'));
                    that.text('Turn on');
                } else {
                    parentBlock.find('.off').remove();
                    that.text('Turn off');
                }
                $('.info-block-success').fadeIn().delay(2000).fadeOut().text(response.message);
            } else {
                $('.info-block-error').fadeIn().delay(2000).fadeOut().text(response.message);
            }
        });
    });

    $('.dlt-btn').click(function (e) {
        var decision = confirm('Do you really want to delete?');

        if (!decision) {
            e.preventDefault();
            return false;
        }
    });

});