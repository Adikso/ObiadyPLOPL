$("#print-button").click(function () {
    $('#menu-left').css("display", "none");
    $('#export-options').css("display", "none");
    $('#display-settings').css("display", "none");
    window.print();
    $('#menu-left').css("display", "block");
    $('#export-options').css("display", "block");
    $('#display-settings').css("display", "block");
});