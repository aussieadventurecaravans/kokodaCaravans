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

$("button.send-email-customer-btn").click(function(e)
{
   if($(this).attr('quote_id') !== undefined)
    {
        var data = {
            'quote_id': $(this).attr('quote_id'),
            'custom_email' : $('#send-email-customer-modal input#recipient-name').val()
        };

        var url = "/custom-orders-portal/quote_request/send_email_customer";

        e.preventDefault();
        $.ajax({
            type: "POST",
            url: url,
            data: data,
            dataType: 'json',
            success: function(data)
            {
                console.log('passed');
                swal({
                    title: "Thank You",
                    text: "Your email is sent successfully.",
                    icon: "success",
                    type: "success",
                    button: "Yes"
                });
            }
        });
    }
});

$("button.email-dealer").click(function(e)
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
            dataType: 'json',
            success: function(data)
            {
                swal({
                    title: "Thank You",
                    text: "Your email is sent successfully.",
                    icon: "success",
                    type: "success",
                    button: "Yes"
                });
            }
        });
    }
});