var main_url = document.getElementById('app_url').value;

var engine_id_check_array = [];
        var model_id_set = 0;
        $('.dropdown-header.engine').click(function(event) {
            $('.dropdown-content.engine_content').toggle();
            event.stopPropagation();
        })
        $(document.body).on('click', '.model_option:not(.model_more)', function(
            event) { // click on brand to get sections
            $('.dropdown-header.model').html($(this).html());
            var model_id = $(this).data('model_id');
            model_id_set = model_id;
            let url = '/get_engines_by_model_home_search';
            let engine_sub_type = $('input[name="sub_type"]:checked').val();
            let engine_type = $('input[name="type"]:checked').val();
            $('.dropdown-content.model_content').toggle();
            $('.engine_normal_option').empty();
            $.get(url + '?model_id=' + model_id + '&engine_sub_type=' + engine_sub_type + '&engine_type=' +
                engine_type + "&main=1",
                function(data) {
                    let response = data.data;
                    console.log(response)

                    document.getElementById('engine_load_icon').style.display = "none";
                    if (data.load_more_engine['value'] > data.total_count) {
                        document.getElementById('engine_more').style.display = "none";
                    } else {
                        document.getElementById('engine_more').style.display = "block";
                    }
                    if (response.length > 0) {
                        $.each(response, function(key, value) {
                            engine_id_check_array.push(value.linkageTargetId);
                            $('.engine_normal_option').append($(
                                '<div class="engine_option" data-engine_id="' +
                                value.linkageTargetId + '">').html(value.description + "(" +
                                value
                                .beginYearMonth + " - " + value.endYearMonth));
                        });
                    } else {
                        $('.engine_normal_option').append(
                            "<span style='color:red;text-align:center;font-size:13px'>No Record Found</span>"
                        );
                    }

                })

        })

        $('.more.engine_more').click(function(event) {
            document.getElementById('engine_load_icon').style.display = "block";
            var model_id = model_id_set;
            let url = '/get_engines_by_model_home_search';
            let engine_sub_type = $('input[name="sub_type"]:checked').val();
            let engine_type = $('input[name="type"]:checked').val();
            $.get(url + '?model_id=' + model_id + '&engine_sub_type=' + engine_sub_type + '&engine_type=' +
                engine_type + "&load=1",
                function(data) {
                    let response = data.data;


                    document.getElementById('engine_load_icon').style.display = "none";
                    if (data.load_more_engine['value'] > data.total_count) {
                        document.getElementById('engine_more').style.display = "none";
                    } else {
                        document.getElementById('engine_more').style.display = "block";
                    }

                    $.each(response, function(key, value) {
                        if (!engine_id_check_array.includes(value.linkageTargetId)) {
                            engine_id_check_array.push(value.linkageTargetId);
                            $('.engine_normal_option').append($(
                                '<div class="engine_option" data-engine_id="' +
                                value.linkageTargetId + '">').html(value.description + "(" +
                                value.beginYearMonth + " - " + value.endYearMonth));
                        }

                    });
                })
        })

        $(document.body).on('click', '.engine_option:not(.engine_more)', function(
            event) { // click on brand to get sections
            $('.dropdown-header.engine').html($(this).html());
            $('#engine_id').val($(this).data('engine_id'))
            var url ='/get_data_of_engine_home_search';
            var engine_id = $(this).data('engine_id');
            $('.dropdown-content.engine_content').toggle();
            $.get(url + '?engine_id=' + engine_id, function(data) {

                // $('#engine_id').html('<option value="">Select One</option>');
                // $('#engine_id').selectpicker("refresh");

                let response = data.data;

                $('#model_year').val(response.beginYearMonth != null ? response.beginYearMonth : 'N/A');
                $('#fuel').val(response.fuelType != null ? response.fuelType : 'N/A');
                $('#cc').val(response.capacityCC != null ? response.capacityCC : 'N/A');
            })
        })