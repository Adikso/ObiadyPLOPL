$('.datepicker').datepicker({
    format: 'yyyy-mm-dd'
});

function isBreakpoint(alias) {
    return $('.device-' + alias).is(':visible');
}

$('.mobile').css('display', 'none');

if (isBreakpoint('xs') || isBreakpoint('sm')) {
    $('.hide-mobile').remove();
    $('.mobile').css('display', 'block');

    $('.select-buttons-group').css('width', '100%');
    $('.select-buttons').css('width', '33%');
}

$("input[name='pizza']").change(function () {
    if ($(this).is(':checked')) {
        $("select.ingredient-selector").prop('disabled', true);
    } else {
        $("select.ingredient-selector").prop('disabled', false);
    }
});

$("#resetselection").click(function () {
    $("input[name='pizza']").prop('checked', false);
    $("select.ingredient-selector").prop('disabled', false);
});

// For fun

$(".fa-calendar-o").click(function () {
    $(this).before('<i class="fa fa-calendar" aria-hidden="true"></i>');
    $(this).remove();
});

$(".fa-lock").click(function () {
    if (document.cookie.indexOf('haskey=true') !== -1) {
        $(this).before('<i class="fa fa-unlock-alt" aria-hidden="true"></i>');
        $(this).remove();
    }
});

$(".fa-unlock").click(function () {
    $(this).before('<i class="fa fa-lock" aria-hidden="true"></i>');
    $(this).remove();
});

$(".fa-key").click(function () {
    document.cookie = "haskey=true; expires=0; path=/";
    $(this).remove();
});

if (document.cookie.indexOf('haskey=true') !== -1) {
    $(".fa-key").remove();
}