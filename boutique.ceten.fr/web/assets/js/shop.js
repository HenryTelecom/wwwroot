$(function () {

    $(window).scroll(function () {
        var top = $('body').scrollTop() || $(window).scrollTop();
        top = 30 - top;
        if (top < 0) {
            top = 0;
        }

        $('.main-nav').css({ top: top });
    });
});