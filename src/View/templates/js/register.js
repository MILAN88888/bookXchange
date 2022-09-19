$(document).ready(function () {
    jQuery.validator.addMethod("my_size", function (value, element) {
        var img = $('#user_img').val();
        if (img != '') {
            var numb = $("input[name='user_img']")[0].files[0].size;
            if (numb > 1048576) {
                return false;
            } else {
                return true;
            }
        } else {
            return true
        }
    }, "too large, Image must be 2mb");

    jQuery.validator.addMethod("my_type", function (value, element) {
        var img = $('#user_img').val();
        if (img != '') {
            var file_data = $("input[name='user_img']")[0].files;
            var myfile = file_data[0].name;
            var mytype = myfile.substring(myfile.lastIndexOf(".") + 1);
            console.log(mytype);
            if (mytype == 'jpeg' || mytype == 'jpg' || mytype == 'png' || mytype == 'svg') {
                return true;
            } else {
                return false;
            }
        } else {
            return true
        }

    }, "Img type must be jpeg, jpg, svg, png");
    $('#register-form').validate({
        rules: {
            user_img: {
                my_size: true,
                my_type: true,
            },
            user_name: {
                required: true,
                minlength: 5,
            },
            user_mobile_no: {
                required: true,
                number: true,
                maxlength: 10,
                minlength: 10,
            },
            address: {
                required: true,
            },
            user_email: {
                required: true,
                email: true,
            },
            user_pass: {
                required: true,
                minlength: 5,
            }
        }, messages: {

            user_name: {
                required: "Enter Name.",
                minlength: "at least 5 character",
            },
            user_mobile_no: {
                required: "Enter Mobile No.",
                number: "Number format",
                maxlength: "10 digit number",
                minlength: "10 digit number",
            },
            address: {
                required: "Enter address",
            },
            user_email: {
                required: "Enter Email",
                email: "Email format",
            },
            user_pass: {
                required: "Enter password",
                minlength: "atleast 5 Character",
            }

        }
    })
});