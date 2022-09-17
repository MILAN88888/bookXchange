$(document).ready(function () {
    jQuery.validator.addMethod("my_size", function (value, element) {
        var numb = $("input[name='book_image']")[0].files[0].size;
        if (numb > 1048576) {
            return false;
        } else {
            return true;
        }
    }, "too large, Image must be 2mb");

    jQuery.validator.addMethod("my_type", function (value, element) {
        var file_data = $("input[name='book_image']")[0].files;
        var myfile = file_data[0].name;
        var mytype = myfile.substring(myfile.lastIndexOf(".") + 1);
        console.log(mytype);
        if (mytype == 'jpeg' || mytype == 'jpg' || mytype == 'png' || mytype == 'svg') {
            return true;
        } else {
            return false;
        };

    }, "Img type must be jpeg, jpg, svg, png");
    $("#addbook-form").validate({
        rules: {
            book_image: {
                required: true,
                my_size: true,
                my_type: true,
            },
            book_name: {
                required: true,
            },
            book_genre: {
                required: true,
            },
            book_author: {
                required: true,
            },
            book_edition: {
                required: true,
            },
            book_des: {
                required: true,
            },
            book_rating: {
                required: true,
                number: true,
            }
        }, messages: {
            book_image:
            {
                required: true,
            },
            book_name: {
                required: "Enter Book Name",
            },
            book_genre: {
                required: "Enter Book Genre",
            },
            book_author: {
                required: "Enter Book Author",
            },
            book_edition: {
                required: "Enter Book Edition",
            },
            book_des: {
                required: "Enter Book Description",
            },
            book_rating: {
                required: "Enter Book Rating",
                number: "Number Format",
            }

        }
    })
});
