function validateEmail(email) { 
    var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
} 

$(document).ready(function() {
    
    $('.confirm').click(function() {
        var answer = confirm('Na pewno chesz wynkonać tę operację?');
        if (!answer) {
            return false;
        }
    });

    $('#show-closed').click(function() {
        $('.closed-rows').show();
        $('#show-closed').hide();
        $('#hide-closed').show();
        return false;
    });
    
    $('#hide-closed').click(function() {
        $('.closed-rows').hide();
        $('#show-closed').show();
        $('#hide-closed').hide();
        return false;
    });
    
});
