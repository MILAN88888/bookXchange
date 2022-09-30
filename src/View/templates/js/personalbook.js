
function myedit(str) {
    $.ajax({
        url: 'action.php?type=bookedit',
        type: 'post',
        data: 'id=' + str,
        dataType: 'json',
        success: function (res) {
            img = '../Upload/Books/' + res[0].image;
            $('#edit-img').attr('src', img);
            $('#book_id').val(res[0].id);
            $('#old_image').val(res[0].image);
            $('#book_name').val(res[0].book_name);
            $('#book_genre').val(res[0].genre);
            $('#book_author').val(res[0].author);
            $('#book_edition').val(res[0].edition);
            $('#book_des').val(res[0].description);
            $('#book_rating').val(res[0].rating);
            // $('#book' + res[0].id).after($('#editbook-div').detach());
            $(document).find('#editbook-div').show();
            $('#editbook-form').submit(function (e) {
                e.preventDefault();
                let form_data = $('#editbook-form').serializeArray();

                const formData = new FormData();
                $.each(form_data, function (key, input) {
                    formData.append(input.name, input.value);
                })
                var img = $('#book_image').val();
                if (img != "") {
                    var file_data = $("input[name='book_image']")[0].files;
                    for (var i = 0; i < file_data.length; i++) {
                        formData.append("image[]", file_data[i]);
                    }
                }
                if ($('#editbook-form').valid()) {
                    $.ajax({
                        url: 'action.php?type=bookupdate',
                        type: 'post',
                        data: formData,
                        dataType: 'json',
                        enctype: 'multipart/form-data',
                        processData: false,
                        contentType: false,
                        success: function (res) {
                            $(document).find('#editbook-form')[0].reset();
                            $(document).find('#editbook-div').hide();
                            $(document).find('#edit-msg').html(res.edit);
                            $(document).find('#personalbook-div').html(res.html);
                            
                            
                        }
                    })
                }
            })
        }
    });
}
$(document).ready(function () {

    jQuery.validator.addMethod("mysize", function (value, element) {
        var img = $('#book_image').val();
        if (img != '') {
            var numb = $("input[name='book_image']")[0].files[0].size;
            if (numb > 1048576) {
                return false;
            } else {
                return true;
            }

        } else {
            return true
        }
    }, "too large, Image must be 2mb");

    jQuery.validator.addMethod("mytype", function (value, element) {

        var img = $('#book_image').val();
        if (img != '') {
            var file_data = $("input[name='book_image']")[0].files;
            var myfile = file_data[0].name;
            var mytype = myfile.substring(myfile.lastIndexOf(".") + 1);
            console.log(mytype);
            if (mytype == 'jpeg' || mytype == 'jpg' || mytype == 'png' || mytype == 'svg') {
                return true;
            } else {
                return false;
            };
        }
        else {
            return true
        }
    }, "Img type must be jpeg, jpg, svg, png");
    $("#editbook-form").validate({
        rules: {
            book_image: {
                mysize: true,
                mytype: true,
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

function bookdelete(str) {
    var conf = confirm('Are you sure want to delete this book!!');
    if (conf == true) {
        $.ajax({
            url: 'action.php?type=bookdelete',
            type: 'post',
            dataType: 'json',
            data: { 'book_id': str },
            success: function (res) {
                $(document).find('#edit-msg').html(res.delete);
                $(document).find('#personalbook-div').html(res.html);
            }
        })
    }
}

