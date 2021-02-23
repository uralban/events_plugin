jQuery(document).ready(function($) {

    $('#events-add-new-form').submit(function(event) {

        var message = '';
        $('.events-add-result-message').html(message);

        $.ajax({
            type: 'POST',
            url: ajaxurl,
            data: $(this).serialize()+'&action=events_add_envent',
            success: function(res){
                if (res == 'ok'){                    
                    message = '<p style=\"color:green;\">Success</p>';
                    $('.events-add-result-message').html(message);
                    // console.log($("#events-add-new-form input[name='event_location']").val());
                    var content = '<tr><td>'+$("#events-add-new-form input[name='event_location']").val()
                                +'</td><td>'+$("#events-add-new-form input[name='event_startDate']").val()
                                +'</td><td>'+$("#events-add-new-form input[name='event_endDate']").val()
                                +'</td></tr>'
                    $('.events-col-right-wraper tbody').append(content);
                    $('#events-add-new-form').trigger('reset');
                } else {
                    message = '<p style=\"color:red;\">' + res + '</p>';
                    $('.events-add-result-message').html(message);
                }                
            },
            error: function() {          
                message = '<p style=\"color:red;\">Error ajax</p>';
                $('.events-add-result-message').html(message);
            }
        });
        event.preventDefault();
    });
});