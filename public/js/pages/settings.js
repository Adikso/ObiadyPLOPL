$( ".removeToken" ).click(function() {
    $('#tokenId').val($(this).attr('value'));
    $('#removeTokenForm').submit();
});