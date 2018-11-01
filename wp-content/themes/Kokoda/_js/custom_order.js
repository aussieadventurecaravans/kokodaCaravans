jQuery(function($) {
    var select_model_id = '';
    var current_tab = '';
    var caravan_title = $caravan_title;
    var custom_exterior = $custom_exterior;
    var custom_floorplan = $custom_floorplan;
    var dealers = $dealers;
    var primary_prices = $primary_prices;
    var acs_files =  $acs_files;
    var caravan_image = '';
    var accessories = '';
    var custom_order = {
        customer: {},
        dealer: {},
        finance: {},
        caravan: '',
        caravan_options: {},
        accessories: [],
        floorplan: '',
        caravan_image:''
    };

    jQuery(document).ready(function ($) {

        actionsListener();

        document.getElementById($('li.current a.tablinks').attr('tab-content')).style.display = "block";


        $('a.tablinks').click(function (event) {

            event.preventDefault();

            if ($(this).parent('li').hasClass('next')) {
                $(this).parent('li').removeClass('next');
            }
            else if ($(this).parent('li').hasClass('complete')) {
                $(this).parent('li').removeClass('complete');
            }
            else {
                return;
            }

            $('a.tablinks').parent('li').removeClass('current');
            $(this).parent('li').nextAll().removeClass('next');
            $(this).parent('li').nextAll().removeClass('complete');

            $(this).parent('li').addClass('current');
            $('div.tabcontent').hide();

            if (current_tab !== $(this).attr('tab-content')) {

                $(this).parent('li').prevAll().addClass('complete');
                $(this).parent('li').next().addClass('next');


                current_tab = $(this).attr('tab-content');
                $('#' + current_tab + '.tabcontent').show();
            }


            if($(this).attr('tab-content') != 'model')
            {
                $('.option-select-value-section .option-select-value-section-content').addClass('col-md-9');
                $('.option-select-value-section .option-select-value-section-content').removeClass('col-md-12');
                $('fieldset.finance-section').show();
                $('fieldset.loan-detail-section').show();
            }
            else
            {
                $('.option-select-value-section .option-select-value-section-content').addClass('col-md-12');
                $('.option-select-value-section .option-select-value-section-content').removeClass('col-md-9');
                $('fieldset.finance-section').hide();
                $('fieldset.loan-detail-section').hide();
            }

            renderCustomOptions($(this).attr('tab-content'));
            renderDisplayImageWrapper($(this).attr('tab-content'));


            if($(this).attr('tab-content') != 'model' && $(this).attr('tab-content') != 'enquiry')
            {
                $('.custom-quote-section  .display-model-section.row .model-header').html(caravan_title[select_model_id])
                $('.custom-quote-section  .display-model-section.row .model-header').show();
            }
            else
            {
                $('.custom-quote-section  .display-model-section.row .model-header').hide();
            }
        });


        function renderCustomOptions(tab) {
            switch (tab) {
                case 'exterior' :

                    var options = custom_order.caravan_options;
                    $("ul.ui-choose").remove();
                    for (var i = 0; i < custom_exterior[select_model_id].length; i++)
                    {
                        if (custom_exterior[select_model_id][i]['custom_option'] == 'composite panel') {
                            $('select#composite_panel').html('');
                            var custom_options_value = custom_exterior[select_model_id][i]['option_value'];

                            for (var e = 0; e < custom_options_value.length; e++) {
                                var el = '<option value="' + custom_options_value[e].value + '"></option>';

                                $('select#composite_panel').append(el);
                            }
                            if (typeof options.panel != 'undefined') {
                                $('select#composite_panel').val(options.panel);
                            }
                            custom_order.caravan_options.panel = $('select#composite_panel').val();


                            //convert these select into the horizonal select menu
                            $('select#composite_panel').ui_choose({id : 'panel'});

                        }
                        if (custom_exterior[select_model_id][i]['custom_option'] == 'checker plate') {
                            $('select#checker_plate').html('');

                            var custom_options_value = custom_exterior[select_model_id][i]['option_value'];
                            for (var e = 0; e < custom_options_value.length; e++) {
                                var el = '  <option value="' + custom_options_value[e].value + '"></option>';
                                $('select#checker_plate').append(el);
                            }
                            if (typeof options.checker_plate != 'undefined') {
                                $('select#checker_plate').val(options.checker_plate);
                            }
                            custom_order.caravan_options.checker_plate = $('select#checker_plate').val();

                            //convert these select into the horizonal select menu
                            $('select#checker_plate').ui_choose({id : 'checker-plate'});
                        }
                    }

                   $("ul.ui-choose").owlCarousel({

                        navigation: false, // Show next and prev buttons
                        slideSpeed: 300,
                        pagination: true,
                        paginationSpeed: 400,
                        items: 10,
                        itemsMobile: [379, 3],
                        itemsTabletSmall: [425,4],
                        itemsTablet: [768, 7],
                        itemsDesktopSmall: [1079, 8],
                        itemsDesktop: [1199, 9],
                        responsiveBaseWidth: '.ui-choose'
                    });
                    break;
                default:
                //do nothing is gold
            }
        }

        function renderDisplayImageWrapper(tab) {
            switch (tab) {
                case 'exterior' :

                    exteriorRenderImageWrapper();

                    break;
                case 'floorplan' :
                    if (!Array.isArray(custom_floorplan[select_model_id])) {
                        var el = '<div class="item col-md-12 text-center selected" floorplan="default"><img src="' + custom_floorplan[select_model_id] + '" style="width:80%" />';
                        el += '<div class="item-details"><div class="details"><h3> Default Floor Plan </h3></div></div></div>';
                        $('#floorplan .option-display-image-wrapper').html(el);
                        custom_order.floorplan = 'default';
                    }
                    else {
                        $('#floorplan .option-display-image-wrapper').html('');
                    }

                    break;
                case "accessories":
                    //render the accessories
                    accessories_section_update();
                    break;
                case 'summary':
                    //render the model specs at summary page
                    summary_section_update();
                    break;
                default:
                //do nothing is gold
            }
        }

        function actionsListener() {
            $('#model .model-list .item').click(function (e)
            {

                //reset the custom options of caravan
                custom_order.caravan_options = {};

                $('#model .model-list .item').removeClass('selected');
                $(this).addClass('selected');
                select_model_id = $(this).attr('select-model');
                custom_order.caravan = select_model_id;

                //we are allowed to go to next tab when we complete this tab
                $('a.tablinks[tab-content="model"]').parent('li').next().addClass('next');

                //go to the next tab
                $('a.tablinks[tab-content="exterior"]').click();

                //render total price detail after we select the model
                finance_section_update();


            });

            $(".custom-quote-section .option-select-value-section #exterior").on('click', 'ul.ui-choose li', function (e) {
                $(this).parent().parent().children().children('li').removeClass('selected');
                $(this).addClass('selected');
                $(this).parent().parent().parent().parent().prev('select').val($(this).attr('data-value'));
                $('#exterior select').change();

            });

            $('#exterior select').change(function (e) {

                var composite_panel_select = $('select#composite_panel').val();
                var checker_plate_select = $('select#checker_plate').val();
                custom_order.caravan_options = {panel: composite_panel_select, checker_plate: checker_plate_select};

                exteriorRenderImageWrapper();

            });


            $('#floorplan .floorplan-list').on('click', '.item', function (e) {

                $('#floorplan .floorplan-list .item').removeClass('selected');
                $(this).addClass('selected');
                custom_order.floorplan = $(this).attr('floorplan');

                //we can go to next tab when we complete this tab
                $('a.tablinks[tab-content="floorplan"]').parent('li').next().addClass('next');
                $('a.tablinks[tab-content="accessories"]').click();

            });

            $(window).resize(function () {
                exteriorRenderImageWrapper();
                summary_section_update();

                var current_tab = $('li.current a.tablinks').attr('tab-content');
                renderCustomOptions(current_tab);

            });

            $('form#customer_details_form').submit(function (event) {
                if ($('select#payment_method').val() == 'cash')
                {
                    custom_order.finance.apply_loan_option = 'none';
                }
                submitCustomOrder();
                return false;
            });

            $('select#payment_method').change(function(event){

                if ($('select#payment_method').val() == 'cash')
                {
                    custom_order.finance.apply_loan_option = 'none';
                    $('#customer_details_form input[name=loan_options]').removeAttr('required');
                    $('.apply-finance-form').hide();
                    $('.form-group.apply-finance-form .finance-options-detail span').hide();
                }
                else
                {
                    custom_order.finance.apply_loan_option = $('#customer_details_form input[name=loan_options]:checked').val();
                    $('#customer_details_form input[name=loan_options]').attr('required','required');
                    $('.apply-finance-form').show();

                    var id =  $('#customer_details_form input[name=loan_options]:checked').attr('id');
                    $('.form-group.apply-finance-form  .finance-options-detail span').hide();
                    $('.form-group.apply-finance-form  .finance-options-detail span.' + id).show();
                }

            });

            $('#customer_details_form input[name=loan_options][type=radio]').change(function()
            {

                var id =  $('#customer_details_form input[name=loan_options]:checked').attr('id');

                $('.form-group.apply-finance-form  .finance-options-detail span').hide();
                $('.form-group.apply-finance-form  .finance-options-detail span.' + id).show();
            });


            $('form#customer_details_form select#customer_state').change(function (e)
            {
                var el = '<option selected value="">Choose Dealer</option>';
                var count = 0;
                for (var i = 0; i < dealers.length; i++) {
                    var state = dealers[i]['sl_state'];
                    if (state.toLowerCase() == $(this).val()) {
                        el += '<option value="' + dealers[i]['sl_id'] + '" dealers_name=" ' + dealers[i]['sl_store'] + '"  >' + dealers[i]['sl_store'] + ' </option>';

                        count++;
                    }

                }

                if (count != 0)
                {
                    $('form#customer_details_form select#dealer_name').removeAttr("disabled");
                }
                else
                {
                    $('form#customer_details_form select#dealer_name').attr("disabled", true);
                }

                $('form#customer_details_form select#dealer_name').html(el);

            });

            $('.tabcontent button.btn-next').click(function (e) {
                event.preventDefault();

                var next_tabcontent = $(this).parent().parent().parent('div.tabcontent').next().attr('id');

                if (typeof next_tabcontent != 'undefined') {
                    $("a.tablinks[tab-content='" + next_tabcontent + "']").click();
                }
                $('html, body').animate({
                    scrollTop: $(".custom-quote-section").offset().top - 250
                }, 1000);
            });

            $('.tabcontent button.btn-pre').click(function (e) {
                event.preventDefault();
                var prev_tabcontent = $(this).parent().parent().parent('div.tabcontent').prev().attr('id');

                if (typeof prev_tabcontent != 'undefined') {
                    $("a.tablinks[tab-content='" + prev_tabcontent + "']").click();
                }
                $('html, body').animate({
                    scrollTop: $(".custom-quote-section").offset().top - 250
                }, 1000);
            });
            $('#customer_details_form button.btn-back').click(function (e) {
                event.preventDefault();
                var prev_tabcontent = $(this).parent().parent().parent().parent().parent().parent().parent('div.tabcontent').prev().attr('id');

                if (typeof prev_tabcontent != 'undefined') {
                    $("a.tablinks[tab-content='" + prev_tabcontent + "']").click();
                }
                $('html, body').animate({
                    scrollTop: $(".custom-quote-section").offset().top - 250
                }, 1000);
            });

            $(".finance-section-details.loan-summary input[type=text]").click(function (e) {
                $(this).select();
            });
            $(".finance-section-details.loan-summary input[type=text]").on('keyup', function (e) {
                //update finance section everytime, enter new amount
                finance_section_update(false);

                //add the input value , comma
                var $this = $(this);
                var raw_input = $this.val();

                var input = raw_input.replace(/[\D\s\._\-]+/g, "");

                input = input ? parseInt(input, 10) : 0;
                if (!$this.hasClass('interest-rate')) {
                    $this.val(function () {
                        return (input === 0) ? "" : input.toLocaleString("en-US");
                    });
                }
            });
            $("button.btn-download").click(function (e) {

                downloadPDF();

            });
            $("button.btn-print").click(function (e) {

                printPDF();

            });

        }

        function exteriorRenderImageWrapper()
        {
            var options = custom_order.caravan_options;
            var image_name = 'default';


            if (typeof options.panel != 'undefined' && typeof options.checker_plate != 'undefined') {
                image_name = options.panel + '_' + options.checker_plate;
            }

            var containerWidth = $('#exterior .option-display-image-wrapper').width();

            var exteriorImageWrapper = new Konva.Stage({
                container: 'exterior-display-image-wrapper',
                width: containerWidth
            });


            //add checkerplate
            var chekerPlateImg = new Konva.Image();
            var chekerPlateObj = new Image();
            chekerPlateObj.src = $base_url + '/custom_order/' + select_model_id + '/checkerplate/' + options.checker_plate + '.png';
            chekerPlateObj.onload = function () {
                chekerPlateImg.setImage(chekerPlateObj);
                var layer = new Konva.Layer();
                layer.add(chekerPlateImg);

                var models_panel = [];
                models_panel[5417] = {x : 232 , y : 62 };
                models_panel[5195] = {x : 232 , y : 62 };
                models_panel[4032] = {x : 186 , y : 24 };


                var panelImg = new Konva.Image();
                var panelObj = new Image();
                panelObj.src = $base_url + '/custom_order/' + select_model_id + '/panel/' + options.panel + '.png';
                panelObj.onload = function () {
                    panelImg.setImage(panelObj);

                    if(typeof models_panel[select_model_id] != 'undefined')
                    {

                        panelImg.setX(models_panel[select_model_id].x);
                        panelImg.setY(models_panel[select_model_id].y);
                    }
                    else
                    {
                        panelImg.setX(232);
                        panelImg.setY(63);
                    }


                    layer.add(panelImg);

                    // add the layer to the stage
                    exteriorImageWrapper.add(layer);

                    //save the images to export pdf function
                    var scale = 650 / chekerPlateImg.getWidth();
                    exteriorImageWrapper.width(chekerPlateImg.getWidth() * scale);
                    exteriorImageWrapper.height(chekerPlateImg.getHeight() * scale);
                    exteriorImageWrapper.scale({x: scale, y: scale});
                    exteriorImageWrapper.draw();
                    caravan_image = exteriorImageWrapper.toDataURL('image/jpeg', 1);

                    //resize the canvas to fit the browser size
                    var scale = containerWidth / chekerPlateImg.getWidth();

                    $("#exterior-display-image-wrapper").height(chekerPlateImg.getHeight() * scale);

                    exteriorImageWrapper.width(chekerPlateImg.getWidth() * scale);
                    exteriorImageWrapper.height(chekerPlateImg.getHeight() * scale);
                    exteriorImageWrapper.scale({x: scale, y: scale});
                    exteriorImageWrapper.draw();

                }

            };

        }


        function submitCustomOrder() {

            custom_order.customer.customer_first_name = $('input#customer_first_name').val();
            custom_order.customer.customer_last_name = $('input#customer_last_name').val();
            custom_order.customer.customer_address = $('input#customer_address').val();
            custom_order.customer.customer_postcode = $('input#customer_postcode').val();
            custom_order.customer.customer_state = $('select#customer_state').val();
            custom_order.customer.customer_phone = $('input#customer_phone').val();
            custom_order.customer.customer_email = $('input#customer_email').val();
            custom_order.customer.payment_method = $('select#payment_method').val();
            if ($('select#payment_method').val() != 'cash')
            {
                custom_order.finance.apply_loan_option = $('#customer_details_form input[name=loan_options]:checked').val();
            }


            custom_order.dealer.dealer_id = $('select#dealer_name').val();
            custom_order.dealer.dealer_name = $('select#dealer_name option:selected').attr('dealers_name');
            var selected_dealer_id = custom_order.dealer.dealer_id;
            for (var i = 0; i < dealers.length; i++) {
                if (dealers[i]['sl_id'] == selected_dealer_id) {
                    custom_order.dealer.dealer_phone = dealers[i]['sl_phone'];
                    custom_order.dealer.dealer_email = dealers[i]['sl_email'];
                    custom_order.dealer.dealer_address = dealers[i]['sl_address'];
                    custom_order.dealer.dealer_city = dealers[i]['sl_city'];
                    custom_order.dealer.dealer_state = dealers[i]['sl_state'];
                    custom_order.dealer.dealer_postcode = dealers[i]['sl_zip'];
                }
            }
            custom_order.product_price = Number(primary_prices[select_model_id]);
            custom_order.orc_price = 0;

            custom_order.caravan_image = caravan_image;



            var data = {
                'action': 'submit_customorder',
                'custom_order': custom_order,
                'kokoda_wpnonce' : $('input[name="kokoda_wpnonce"]').val(),
                '_wp_http_referer' :  $('input[name="_wp_http_referer"]').val()
            };
            var url = $site_url + "/wp-admin/admin-ajax.php";
            //loading the caravan detail before open panel
            $.ajax({
                url: url,
                data: data,
                type: "POST",
                beforeSend: function () {
                    $('.custom-quote-section .option-select-value-section  #enquiry .feedback-notice-messages .alert').hide();
                    $('#enquiry input#reset_order').attr("disabled", true);
                    $('#enquiry input#submit_order').attr("disabled", true);
                    $('#enquiry input#submit_order').attr('value', 'Loading....');
                    $('#loading-icon-panel').show();

                },
                success: function (data)
                {
                    $('#loading-icon-panel').hide();
                    if (data == true)
                    {
                        $('.custom-quote-section .option-select-value-section  #enquiry .feedback-notice-messages .alert.alert-success').show();
                        $('#enquiry input#submit_order').attr('value', 'Complete');

                        //refresh page after successfully submit quote to system
                        setTimeout(function () {
                            location = ''
                        }, 3000);
                    }
                    else
                    {
                        $('.custom-quote-section .option-select-value-section  #enquiry .feedback-notice-messages .alert.alert-danger').show();
                        $('#enquiry input#submit_order').attr('value', 'Submit');
                        $('#enquiry input#submit_order').removeAttr("disabled");
                    }
                }
            });
        }


        function finance_section_update(refresh_amount) {
            var caravan_price = primary_prices;

            var accessories_prices = 0;
            for( var i = 0 ; i < custom_order.accessories.length; i++)
            {

                accessories_prices += Number(custom_order.accessories[i]['retail_price']);
            }

            custom_order.accessories_price = accessories_prices;
            custom_order.product_price = Number(primary_prices[select_model_id]);

            var total_price = Number(caravan_price[select_model_id]) + accessories_prices;


            $(".finance-section-details.cash-summary h2.primary-price").html("$" + Number(caravan_price[select_model_id]).toLocaleString("en-US") + " + ORC");
            $(".finance-section-details.cash-summary h2.total-price").html('$' + total_price.toLocaleString("en-US") + " + ORC");
            $(".finance-section-details.cash-summary h2.add-on-price").html('$' + accessories_prices.toLocaleString("en-US"));


            var loan = Number($(".finance-section-details.loan-summary input.loan-amount").val().replace(/[\D\s\._\-]+/g, ""));
            if (refresh_amount === true) {
                $(".finance-section-details.loan-summary input.loan-amount").val(total_price);
                loan = total_price;
            }

            var rate = Number($(".finance-section-details.loan-summary input.interest-rate").val());
            var terms = Number($(".finance-section-details.loan-summary input.loan-terms").val().replace(/[\D\s\._\-]+/g, ""));
            var balloon = Number($(".finance-section-details.loan-summary input.balloon-amount").val().replace(/[\D\s\._\-]+/g, ""));

            var monthly_payment = 0;
            var factor = rate / 1200;

            if (balloon === 0 && !isNaN(terms) && !isNaN(loan) && !isNaN(rate)) {
                if (terms !== 0 && loan !== 0 && rate !== 0) {
                    monthly_payment = loan * factor / (1 - (Math.pow(1 / (1 + factor), terms)));
                }
            }
            else {
                if (terms !== 0 && loan !== 0 && rate !== 0) {
                    var step_1 = loan * (factor * Math.pow(1 + factor, terms) / (Math.pow(1 + factor, terms) - 1));
                    var step_2 = balloon * (factor / (Math.pow(1 + factor, terms) - 1));

                    monthly_payment = step_1 - step_2;

                }
            }
            $(".finance-section-details.loan-summary .monthly-payment-sec span.mp-amount").html(monthly_payment.toFixed(2));


        }


        function summary_section_update() {

            //render the the feature spec for the model
            var data = {
                'action': 'get_caravan',
                'caravan_id': custom_order.caravan
            };
            var url = $site_url + "/wp-admin/admin-ajax.php";
            //loading the caravan detail before open panel
            $.ajax({
                url: url,
                data: data,
                type: "POST",
                beforeSend: function () {
                    $('#loading-icon-panel').show();

                },
                success: function (data) {
                    $(".tabcontent#summary .display-features-wrapper").html(data);
                    $('#loading-icon-panel').hide();
                }
            });

            //render the list of selected accessories from order
            var el = '';
            var acs = custom_order.accessories;
            if (acs.length > 0)
            {
                el += '<h2 style="text-align: center;font-size: 40px">+</h2>';
                el += '<div class="header-wrapper">';
                el += '<h2>Add-on Accessories</h2>';
                el += '</div>';

                el += '<div class="col-md-12 text-center">';
                for (var i = 0; i < acs.length; i++) {
                    el += '<div class="item" access-id="' + i + '" ><div class="item-detail">';
                    el += '<img src="' + $base_url + '/custom_order/Accessories/' + acs[i]['label'] + '.png" />';
                    el += '<h3>' + acs[i]['label'] + '</h3>';
                    el += '</div></div>';
                }
                el += '</div>';
            }

            $(".tabcontent#summary .display-accessories-wrapper").html(el);

            //render the caravan image with custom options
            var options = custom_order.caravan_options;
            var image_name = 'default';
            if (typeof options.panel != 'undefined' && typeof options.checker_plate != 'undefined') {
                image_name = options.panel + '_' + options.checker_plate;
            }
            else {
                return;
            }

            var containerWidth = $('#summary .display-image-wrapper').width();

            var exteriorImageWrapper = new Konva.Stage({
                container: 'summary-display-image-wrapper',
                width: containerWidth
            });

            //add checkerplate
            var chekerPlateImg = new Konva.Image();
            var chekerPlateObj = new Image();
            chekerPlateObj.src = $base_url + '/custom_order/' + select_model_id + '/checkerplate/' + options.checker_plate + '.png';
            chekerPlateObj.onload = function () {
                chekerPlateImg.setImage(chekerPlateObj);
                var layer = new Konva.Layer();
                layer.add(chekerPlateImg);

                var models_panel = [];
                models_panel[5417] = {x : 232 , y : 62 };
                models_panel[5195] = {x : 232 , y : 62 };
                models_panel[4032] = {x : 186 , y : 24 };

                var panelImg = new Konva.Image();
                var panelObj = new Image();
                panelObj.src = $base_url + '/custom_order/' + select_model_id + '/panel/' + options.panel + '.png';
                panelObj.onload = function () {
                    panelImg.setImage(panelObj);

                    if(typeof models_panel[select_model_id] != 'undefined')
                    {

                        panelImg.setX(models_panel[select_model_id].x);
                        panelImg.setY(models_panel[select_model_id].y);
                    }
                    else
                    {
                        panelImg.setX(232);
                        panelImg.setY(63);
                    }


                    layer.add(panelImg);

                    // add the layer to the stage
                    exteriorImageWrapper.add(layer);

                    //save the images to export pdf function
                    var scale = 650 / chekerPlateImg.getWidth();
                    exteriorImageWrapper.width(chekerPlateImg.getWidth() * scale);
                    exteriorImageWrapper.height(chekerPlateImg.getHeight() * scale);
                    exteriorImageWrapper.scale({x: scale, y: scale});
                    exteriorImageWrapper.draw();
                    caravan_image = exteriorImageWrapper.toDataURL('image/jpeg', 1);

                    //resize the canvas to fit the browser size
                    var scale = containerWidth / chekerPlateImg.getWidth();

                    exteriorImageWrapper.width(chekerPlateImg.getWidth() * scale);
                    exteriorImageWrapper.height(chekerPlateImg.getHeight() * scale);
                    exteriorImageWrapper.scale({x: scale, y: scale});
                    exteriorImageWrapper.draw();
                }

            };


        }


        function accessories_section_update()
        {
            var accessories_wrapper = $(".tabcontent#accessories .accessories-list");

            if( acs_files[select_model_id] == '')
            {
                accessories_wrapper.html('<h3 class="text-center">There are no accessories for ' +  caravan_title[select_model_id]  + '</h3>');
                return ;
            }


            var data = {
                'action': 'list_accessories',
                'accessories_file': acs_files[select_model_id]
            };

            var url = $site_url + "/wp-admin/admin-ajax.php";
            //loading the caravan detail before open panel
            $.ajax({
                url: url,
                data: data,
                type: "POST",
                beforeSend: function() {
                    $('#loading-icon-panel').show();
                },
                success: function (data) {

                    $('#loading-icon-panel').hide();

                    accessories = JSON.parse(data);

                    //load accessories to template
                    var el = "";

                    for (var i = 0; i < accessories.length; i++)
                    {

                        //check if any accessories are added to current order
                        var sel = '';
                        if (custom_order.accessories.length > 0)
                        {
                            for (var a = 0; a < custom_order.accessories.length; a++)
                            {
                                if (accessories[i]['label'] == custom_order.accessories[a]['label'])
                                {
                                    sel = "selected";
                                }
                            }
                        }

                        //render the accessories list at template
                        el += '<div class="item ' + sel + ' col-md-4 col-sm-5 col-xs-6" access-id="' + i + '" ><div class="item-detail">';
                        el += '<span class="icon-moon"></span>';
                        el += '<img src="' + $base_url + '/custom_order/Accessories/' + accessories[i]['label'] + '.png" />';
                        el += '<h3>' + accessories[i]['label'] + '</h3>';
                        el += '</div></div>';

                    }
                    accessories_wrapper.html(el);

                    //add event listener to the accessories link

                    $(".tabcontent#accessories .accessories-list .item").click(function (e) {

                        if ($(this).hasClass('selected')) {
                            //remove accessories from the order
                            $(this).removeClass('selected');
                        }
                        else {
                            //add accessories to the order
                            $(this).addClass('selected');
                        }

                        var acc_list = [];
                        $(".tabcontent#accessories .accessories-list .item.selected").each(function (index) {
                            var accessId = $(this).attr('access-id');
                            acc_list.push(accessories[accessId]);

                        });
                        custom_order.accessories = acc_list;
                        finance_section_update(true);
                    });
                }
            });


        }

        function printPDF() {
            //render the the feature spec for the model
            var data = {
                'action': 'export_pdf',
                'custom_order': custom_order,
                'caravan_id': custom_order.caravan,
                'caravan_image': caravan_image
            };
            var url = $site_url + "/wp-admin/admin-ajax.php";
            //loading the caravan detail before open panel
            $.ajax({
                url: url,
                data: data,
                type: "POST",
                beforeSend: function() {
                    $('#loading-icon-panel').show();
                },
                success: function (data) {
                    var base64string = data;
                    var d = new Date();
                    printPdfFile(base64ToArrayBuffer(base64string), caravan_title[select_model_id] + ' ' + d.getDate() + '-' + d.getMonth() + '-' + d.getFullYear()  + '.pdf', 'application/pdf');
                    $('#loading-icon-panel').hide();
                }
            });
        }


        function downloadPDF() {
            //render the the feature spec for the model
            var data = {
                'action': 'export_pdf',
                'custom_order': custom_order,
                'caravan_id': custom_order.caravan,
                'caravan_image': caravan_image
            };
            var url = $site_url + "/wp-admin/admin-ajax.php";
            //loading the caravan detail before open panel
            $.ajax({
                url: url,
                data: data,
                type: "POST",
                beforeSend: function () {
                    $('#loading-icon-panel').show();

                },
                success: function (data) {
                    var base64string = data;
                    var d = new Date();
                    exportPdfFile(base64ToArrayBuffer(base64string),  caravan_title[select_model_id] + ' ' + d.getDate() + '-' + d.getMonth() + '-' + d.getFullYear() +'.pdf', 'application/pdf');
                    $('#loading-icon-panel').hide();
                }
            });
        }


        function printPdfFile(data, filename, type) {
            var file = new Blob([data], {type: type});

            var url = URL.createObjectURL(file);
            var iframe = this._printIframe;
            if (!this._printIframe) {
                iframe = this._printIframe = document.createElement('iframe');
                document.body.appendChild(iframe);

                iframe.style.display = 'none';
                iframe.onload = function () {
                    setTimeout(function () {
                        iframe.focus();
                        iframe.contentWindow.print();
                    }, 1);
                };
            }

            iframe.src = url;
        }


        function exportPdfFile(data, filename, type) {
            var file = new Blob([data], {type: type});
            if (window.navigator.msSaveOrOpenBlob) {
                window.navigator.msSaveOrOpenBlob(file, filename);
            }
            else { // Others
                var a = document.createElement("a"),
                    url = URL.createObjectURL(file);
                a.href = url;
                a.download = filename;
                document.body.appendChild(a);
                a.click();
                setTimeout(function () {
                    document.body.removeChild(a);
                    window.URL.revokeObjectURL(url);

                }, 0);
            }
        }

        function base64ToArrayBuffer(data) {
            var binaryString = window.atob(data);
            var binaryLen = binaryString.length;
            var bytes = new Uint8Array(binaryLen);
            for (var i = 0; i < binaryLen; i++) {
                var ascii = binaryString.charCodeAt(i);
                bytes[i] = ascii;
            }
            return bytes;
        }


    });
});