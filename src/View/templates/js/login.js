$(document).ready(function () {
    $('#forget-pass').click(function (e) { 
        e.preventDefault();
        $('#forget-div').show();
        $('#login-div').hide();
        $('#register-div').hide();
    });
    $('#login-form').validate({
        rules:{
            phone_no:{
                required:true,
            },
            pass:{
                required:true,
            }
        },messages:{
            phone_no:{
                required:"Enter Mobile number",
            },
            pass:{
                required:"Enter password",
            }
        }
    });
    $('#forget-form').validate({
        rules:{
            mobile_no:{
                required:true,
            }
        },
            messages:{
                mobile_no:{
                    required:"Phone number is required",
                }
            }
        
    });
});