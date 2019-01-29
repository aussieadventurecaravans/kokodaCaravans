jQuery(function($) {
    jQuery(document).ready(function ($) {
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
        /**
         * submit and send Email function
         * to customer and dealer at ed
         */

        $("form#send_email_customer_modal_form").submit(function (e) {
            e.preventDefault();
            if ($(this).attr('request_id') !== undefined) {
                var data = {
                    'request_id': $(this).attr('request_id'),
                    'custom_email': $('#send-email-customer-modal input#recipient-name').val()
                };
                var url = "/custom-orders-portal/quote_request/send_email_customer";

                $.ajax({
                    type: "POST",
                    url: url,
                    data: data,
                    dataType: 'json'
                })
                    .done(function (data) {
                        swal({
                            title: "Thank You",
                            text: "Your email is sent successfully.",
                            icon: "success",
                            type: "success",
                            button: "Yes"
                        });
                        $('#send-email-customer-modal').modal('toggle');
                    })
                    .fail(function () {
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

        $("form#send_email_dealer_modal_form").submit(function (e)
        {
            if ($(this).attr('request_id') !== undefined) {
                var data = {
                    'request_id': $(this).attr('request_id')
                };

                var url = "/custom-orders-portal/quote_request/send_email_dealer";

                e.preventDefault();

                $.ajax({
                    type: "POST",
                    url: url,
                    data: data,
                    dataType: 'json'
                })
                .done(function (data) {
                    swal({
                        title: "Thank You",
                        text: "Your email is sent successfully.",
                        icon: "success",
                        type: "success",
                        button: "Yes"
                    });

                    $('#send-email-dealer-modal').modal('toggle');

                })
                .fail(function () {
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
        /*** END ***/

        /**
         * update, edit, create the exterior options for
         * new quote form
         */

        if($("form#new_quote_form").length > 0)
        {
            //initialize the the exterior option when load first product
            var exteriors = $_global_exterior[$("form#new_quote_form select#product_name").val()];

            $('div.exterior-custom-options div.options').html('');
            for(var i = 0; i < exteriors.length; i++ )
            {
                var option_name = exteriors[i]['custom_option'];
                var dropdown = '<label class="font-italic text-capitalize">' +  option_name  + '</label>';

                dropdown += '<div class="form-row row"><div class="col-12"><select name="custom_exterior_ ' + option_name.trim() +'" type="text" id="custom_exterior" value="" class="form-control text-capitalize" required="1">';

                var options =  exteriors[i]['option_value'];
                for(var e = 0;  e < options.length; e++)
                {
                    dropdown += '<option value="'+ options[e]['value'] + '">' +  options[e]['value']  + ' - Price $' +  options[e]['price']  +'</option>';
                }
                dropdown += '</select></div></di>';

                $('div.exterior-custom-options div.options').append(dropdown)
            }
        }

        //change the exterior value when they select the product
        $("form#new_quote_form select#product_name").change(function()
        {
            var exteriors = $_global_exterior[$(this).val()];

            $('div.exterior-custom-options div.options').html('');
            for(var i = 0; i < exteriors.length; i++ )
            {
                var option_name = exteriors[i]['custom_option'];
                var dropdown = '<label class="font-italic text-capitalize">' +  option_name  + '</label>';

                dropdown += '<div class="form-row row"><div class="col-12"><select name="custom_exterior_ ' + option_name.trim() +'" type="text" id="custom_exterior" value="" class="form-control text-capitalize" required="1">';

                var options =  exteriors[i]['option_value'];
                for(var e = 0;  e < options.length; e++)
                {
                    dropdown += '<option value="'+ options[e]['value'] + '">' +  options[e]['value']  + ' - Price $' +  options[e]['price']  +'</option>';
                }
                dropdown += '</select></div></di>';

                $('div.exterior-custom-options div.options').append(dropdown)
            }

        });
        /**** END *****/




        /**
         * update, edit, create the interior options for
         * new quote form
         */

        //initialize the the exterior option when load first product
        if($("form#new_quote_form").length > 0)
        {
            var interiors = $_global_interior[$("form#new_quote_form select#product_name").val()];

            $('div.interior-custom-options div.options').html('');

            if (interiors !== null && interiors.length > 0) {
                for (var i = 0; i < interiors.length; i++) {
                    var option_name = interiors[i]['custom_option'];
                    var in_dropdown = '<label class="font-italic text-capitalize">' + option_name + '</label>';

                    in_dropdown += '<div class="form-row row"><div class="col-12"><select name="custom_interior_ ' + option_name.trim() + '" type="text" id="custom_einterior" value="" class="form-control text-capitalize" required="1">';

                    var options = interiors[i]['option_value'];
                    for (var e = 0; e < options.length; e++) {
                        in_dropdown += '<option value="' + options[e]['value'] + '">' + options[e]['value'] + ' - Price $' + options[e]['price'] + '</option>';
                    }
                    in_dropdown += '</select></div></di>';

                    $('div.interior-custom-options div.options').append(in_dropdown)
                }
            }
            else {
                $('div.interior-custom-options div.options').html('<p>No Interior Options</p>');
            }
        }
        //change the exterior value when they select the product
        $("form#new_quote_form select#product_name").change(function()
        {
            var interiors = $_global_interior[$(this).val()];

            $('div.interior-custom-options div.options').html('');

            if(interiors !== null && interiors.length > 0)
            {
                for(var i = 0; i < interiors.length; i++ )
                {
                    var option_name = interiors[i]['custom_option'];
                    var in_dropdown = '<label class="font-italic text-capitalize">' +  option_name  + '</label>';

                    in_dropdown += '<div class="form-row row"><div class="col-12"><select name="custom_interior_ ' + option_name.trim() +'" type="text" id="custom_interior" value="" class="form-control text-capitalize" required="1">';

                    var options =  interiors[i]['option_value'];
                    for(var e = 0;  e < options.length; e++)
                    {
                        in_dropdown += '<option value="'+ options[e]['value'] + '">' +  options[e]['value']  + ' - Price $' +  options[e]['price']  +'</option>';
                    }
                    in_dropdown += '</select></div></di>';

                    $('div.interior-custom-options div.options').append(in_dropdown)
                }
            }
            else
            {
                $('div.interior-custom-options div.options').html('<p>No Interior Options</p>');
            }
        });
    });
});