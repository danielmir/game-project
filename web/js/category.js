$(document).ready(function () {

    $('.category-subdomain').click(function () {
        $('.page-content').find('.active').removeClass('active');
        $(this).addClass('active');

        var url = $(this).attr('data-url');

        $('.alert-success').fadeIn().text('Loading...');

        $.post(url, function (response) {
            $('.alert-success').fadeOut();
            $('.category-form').html(response);
        });
    });

    $('.category-form').on('click', '.update-category', function (e) {
        e.preventDefault();

        var url = $(this).attr('data-url');
        var formData = $(this).parent().serializeArray();

        $.post(url, formData, function (response) {
            if (response.success) {
                $('.alert-success').fadeIn().delay(2000).fadeOut().text(response.message);
            }
        });
    });

});