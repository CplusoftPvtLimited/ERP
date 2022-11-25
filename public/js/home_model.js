var main_url = document.getElementById('app_url').value;
function filterModel() {
    var input, filter, ul, li, a, i;
    input = document.getElementById("model_input_search");
    filter = input.value.toUpperCase();
    if (input.value) {
        document.getElementById('model_more').style.display = "none";
    } else {
        document.getElementById('model_more').style.display = "block";
    }
    div = document.getElementsByClassName("model_normal_option");
    a = document.getElementsByClassName("model_option");
    for (i = 0; i < a.length; i++) {
        txtValue = a[i].textContent || a[i].innerText;
        if (txtValue.toUpperCase().indexOf(filter) > -1) {
            a[i].style.display = "";
        } else {
            a[i].style.display = "none";
        }
    }

}
var model_id_check_array = [];
        $('.dropdown-header.model').click(function(event) {
            $('.dropdown-content.model_content').toggle();
            if(document.getElementById('model_caret').style.transform == "rotate(180deg)"){
                document.getElementById('model_caret').style.transform = "rotate(0deg)";
            }else{
                document.getElementById('model_caret').style.transform = "rotate(180deg)";
            }
            event.stopPropagation();
        })
        var manufacturer_id_set = 0;
        $(document.body).on('click', '.manufacturer_option:not(.manufacturer_more)', function(
            event) { // click on brand to get sections
            model_id_check_array = [];
            $('.dropdown-header.model').html('Select Model <i id="model_caret" class="fa fa-solid fa-caret-down"></i>');
            $('.dropdown-header.engine').html('Select Engine <i id="engine_caret" class="fa fa-solid fa-caret-down"></i>');
            $('.model_normal_option').empty();
            $('.engine_normal_option').empty();
            document.getElementById('engine_more').style.display = "none";
            $('.dropdown-content.manufacturer_content').toggle();
            var manufacturer_id = $(this).data('manufacturer_id');
            manufacturer_id_set = manufacturer_id;
            let engine_sub_type = $('input[name="sub_type"]:checked').val();
            let engine_type = $('input[name="type"]:checked').val();
            $('.dropdown-header.manufacturer').html($(this).html() + ' <i id="manufacturer_caret" class="fa fa-solid fa-caret-down"></i>');
            let url = '/get_models_by_manufacturer_home_search';
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