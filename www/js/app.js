$(function () {
    $('.quick-view').click(function () {
        $.get("/quickview/" + $(this).data('id'), function (data) {
            $("#fragment").html(data);
            $("#quickViewContainer").fadeIn();
            $("#shade").fadeIn();
        });
    });

    $("#shade").click(function () {
        $("#quickViewContainer").fadeOut();
        $("#shade").fadeOut();
    });
});