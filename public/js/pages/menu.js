$('.delete').click(function () {
    var toDelete = $(this).val();
    $('#removeDayInfo').text(toDelete);
    $('#confirm-delete').val(toDelete);

    $('#removeAlert').show();
    $("html, body").animate({ scrollTop: 0 }, "slow");
});

$('#confirm-hide').click(function () {
    $('#removeAlert').hide();
});