var main_url = document.getElementById('app_url').value;

var model_id_check_array = [];
        $('.dropdown-header.model').click(function(event) {
            $('.dropdown-content.model_content').toggle();
            event.stopPropagation();
        })
        var manufacturer_id_set = 0;
        $(document.body).on('click', '.manufacturer_option:not(.manufacturer_more)', function(
            event) { // click on brand to get sections
            model_id_check_array = [];
            $('.dropdown-header.model').html("Select Model");
            $('.dropdown-header.engine').html("Select Engine");
            $('.model_normal_option').empty();
            $('.engine_normal_option').empty();
            document.getElementById('engine_more').style.display = "none";
            $('.dropdown-content.manufacturer_content').toggle();
            var manufacturer_id = $(this).data('manufacturer_id');
            manufacturer_id_set = manufacturer_id;
            let engine_sub_type = $('input[name="sub_type"]:checked').val();
            let engine_type = $('input[name="type"]:checked').val();
            let url = '/get_models_by_manufacturer_home_search';
            $('.dropdown-header.manufacturer').html($(this).html());
            $.get(url + '?manufacturer_id=' + manufacturer_id + '&engine_sub_type=' + engine_sub_type +
                '&engine_type=' + engine_type + '&main=1',
                function(data) {

                    let response = data.data;
                    if (data.load_more_model['value'] > data.total_count) {
                        document.getElementById('model_more').style.display = "none";
                    } else {
                        document.getElementById('model_more').style.display = "block";
                    }

                    if (response.length > 0) {
                        $.each(response, function(key, value) {

                            model_id_check_array.push(value.modelId);
                            $('.model_normal_option').append($(
                                '<div class="model_option" data-model_id="' +
                                value.modelId + '">').html(value.modelname));
                        });
                    } else {
                        $('.model_normal_option').append(
                            "<span style='color:red;text-align:center;font-size:13px;'>No Record Found</span>"
                        );
                    }




                })
        })

        $('.more.model_more').click(function(event) {
            document.getElementById('model_load_icon').style.display = "block";
            var manufacturer_id = manufacturer_id_set;
            let engine_sub_type = $('input[name="sub_type"]:checked').val();
            let engine_type = $('input[name="type"]:checked').val();
            let url = '/get_models_by_manufacturer_home_search';

            $.get(url + '?manufacturer_id=' + manufacturer_id + '&engine_sub_type=' + engine_sub_type +
                '&engine_type=' +
                engine_type + '&load=1',
                function(data) {

                    let response = data.data;
                    document.getElementById('model_load_icon').style.display = "none";
                    if (data.load_more_model['value'] > data.total_count) {
                        document.getElementById('model_more').style.display = "none";
                    } else {
                        document.getElementById('model_more').style.display = "block";
                    }
                    var error = [];
                    $.each(response, function(key, value) {
                        if (!model_id_check_array.includes(value.modelId)) {
                            $('.model_normal_option').append($(
                                '<div class="model_option" data-model_id="' +
                                value.modelId + '">').html(value.modelname));
                            error.push('data');
                        }
                    });
                })
            event.stopPropagation();
        })