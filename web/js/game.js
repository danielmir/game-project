$(document).ready(function () {

    $('.game-subdomain').click(function () {
        $('.page-content').find('.active').removeClass('active');
        $(this).addClass('active');

        var url = $(this).attr('data-url');

        $('.alert-success').fadeIn().text('Loading...');

        $.post(url, function (response) {
            $('.alert-success').fadeOut();
            $('.game-form').html(response);
        });
    });

    $('.game-form').on('click', '.update-game', function (e) {
        e.preventDefault();

        var url = $(this).attr('data-url');
        var formData = $(this).parent().serializeArray();

        $.post(url, formData, function (response) {
            if (response.success) {
                $('.alert-success').fadeIn().delay(2000).fadeOut().text(response.message);
            } else {
                $('.alert-danger').fadeIn().delay(2000).fadeOut().text(response.message);
            }
        });
    });

});