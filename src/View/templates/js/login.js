$(document).ready(function () {
    $('#forget-pass').click(function (e) { 
        e.preventDefault();
        $('#forget-div').show();
        $('#login-div').hide();
        $('#register-div').hide();
    });;
});