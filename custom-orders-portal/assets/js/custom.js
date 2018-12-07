/*
var ctx = document.getElementById("myChart");
var myChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"],
        datasets: [{
            data: [15339, 21345, 18483, 24003, 23489, 24092, 12034],
            lineTension: 0,
            backgroundColor: 'transparent',
            borderColor: '#007bff',
            borderWidth: 4,
            pointBackgroundColor: '#007bff'
        }]
    },
    options: {
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero: false
                }
            }]
        },
        legend: {
            display: false,
        }
    }
});
*/

$("form#send_email_customer_modal_form").submit(function(e)
{
   e.preventDefault();
   if($(this).attr('quote_id') !== undefined)
    {
        var data = {
            'quote_id': $(this).attr('quote_id'),
            'custom_email' : $('#send-email-customer-modal input#recipient-name').val()
        };
        var url = "/custom-orders-portal/quote_request/send_email_customer";

        $.ajax({
            type: "POST",
            url: url,
            data: data,
            dataType: 'json'
        })
        .done(function(data)
        {
            swal({
                title: "Thank You",
                text: "Your email is sent successfully.",
                icon: "success",
                type: "success",
                button: "Yes"
            });
            $('#send-email-customer-modal').modal('toggle');
        })
        .fail(function(){
            swal({
                title: "Oops !!!",
                text: "Your email is failed to send.",
                icon: "error",
                type: "error",
                button: "Yes"
            });

        });

    }
    return false;
});

$("form#send_email_dealer_modal_form").submit(function(e)
{
    if($(this).attr('quote_id') !== undefined)
    {
        var data = {
            'quote_id': $(this).attr('quote_id')
        };

        var url = "/custom-orders-portal/quote_request/send_email_dealer";

        e.preventDefault();

        $.ajax({
            type: "POST",
            url: url,
            data: data,
            dataType: 'json'
        })
        .done(function(data)
        {
            swal({
                title: "Thank You",
                text: "Your email is sent successfully.",
                icon: "success",
                type: "success",
                button: "Yes"
            });

            $('#send-email-dealer-modal').modal('toggle');

        })
        .fail(function(){
            swal({
                title: "Oops !!!",
                text: "Your email is failed to send.",
                icon: "error",
                type: "error",
                button: "Yes"
            });

        });
    }
    return false;
});