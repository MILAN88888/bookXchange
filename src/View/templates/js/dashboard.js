function bookdetails(str)
{
    $('#bookfeedback-div').show(1000);
    
    $.ajax({
        url:'action.php?type=bookfeedback',
        type:'post',
        dataType:'json',
        data:{'book_id':str},
        success:function(res) {
            $(document).find('#bookfeedback-div').html(res.html1);
            $(document).find('#allfeedback-div').html(res.html2);
            $('#feedback-form').submit(function(e){
                e.preventDefault();
                var feedback = $('#feedback').val();
                $.ajax({
                    url:'action.php?type=insertfeedback',
                    type:'post',
                    dataType:'json',
                    data:{'feedback':feedback, 'bookid':str},
                    success:function(res)
                    {
                        $(document).find('#allfeedback-div').html(res.feedbackhtml);
                        $(document).find('#feedbackmsg').html(res.feedbackmsg);
                    }
                })
            });
        }
    });
}