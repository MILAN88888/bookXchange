$(document).ready(function(){
    jQuery.validator.addMethod("my_size", function (value, element) {
        var img = $('#new_user_image').val();
        if (img != '') {
            var numb = $("input[name='newimage']")[0].files[0].size;
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
        var img = $('#new_user_image').val();
        if (img != '') {
            var file_data = $("input[name='newimage']")[0].files;
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
$('#profile-form').validate({
    rules:{
        newimage:{
            my_type:true,
            my_size:true,
        },
        user_name:{
            required:true,
            minlength:5,
        },
        user_phone:{
            required:true,
            minlength:10,
            maxlength:10,
            number:true,

        },
        user_address:{
            required:true,
            minlength:5,
        },
        user_email:{
            required:true,
            email:true,
        }

    }, messages:{

        user_name:{
            required:"Enter Name",
            minlength:"At least 5 character",
        },
        user_phone:{
            required:"Enter Phone",
            number:"Number Format",
            minlength:"10 digit required",
            maxlength:"10 digit required",

        },
        user_address:{
            required:"Enter Address",
            minlength:"At least 10 character",
        },
        user_email:{
            required:"Enter Email",
            email:"Enter valid email",
        }
    }
})
});