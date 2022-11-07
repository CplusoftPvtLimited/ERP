var main_url = document.getElementById('app_url').value;
function filterManufacturer() {
            var input, filter, ul, li, a, i;
            input = document.getElementById("manufacturer_input_search");
            filter = input.value.toUpperCase();
            if (input.value) {
                document.getElementById('manufacturer_more').style.display = "none";
            } else {
                document.getElementById('manufacturer_more').style.display = "block";
            }
            div = document.getElementsByClassName("manufacturer_normal_option");
            a = document.getElementsByClassName("manufacturer_option");
            for (i = 0; i < a.length; i++) {
                txtValue = a[i].textContent || a[i].innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    a[i].style.display = "";
                } else {
                    a[i].style.display = "none";
                }
            }

        }
       
        // get manufacturers // load more script for get manufacturers
        var manufacturer_id_check_array = [];
        $('.dropdown-header.manufacturer').click(function(event) {
            $('.dropdown-content.manufacturer_content').toggle();
            event.stopPropagation();
        })

      

        function selectEngineType() {
            manufacturer_id_check_array = [];
            $('.dropdown-header.manufacturer').html("Select Manufacturer");
            $('.dropdown-header.model').html("Select Model");
            $('.dropdown-header.engine').html("Select Engine");
            $('.manufacturer_normal_option').empty();
            $('.model_normal_option').empty();
            $('.engine_normal_option').empty();
            document.getElementById('model_more').style.display = "none";
            document.getElementById('engine_more').style.display = "none";
            var sub_type = $('input[name="sub_type"]:checked').val();
            var main_type = $('input[name="type"]:checked').val();
            if (sub_type == "V" || sub_type == "L" || sub_type == "B") {
                $('#p_type').prop('checked', true);
                $('#o_type').prop('checked', false);
            } else if (sub_type == "C" || sub_type == "T" || sub_type == "M" || sub_type == "A" || sub_type == "K") {
                $('#o_type').prop('checked', true);
                $('#p_type').prop('checked', false);
            } else {
                if (main_type == "O" && sub_type == "home") {
                    $('#p_type').prop('checked', false);
                    $('#o_type').prop('checked', true);
                } else if (main_type == "P" && sub_type == "home") {
                    $('#p_type').prop('checked', true);
                    $('#o_type').prop('checked', false);
                }

            }
            var type = $('input[name="type"]:checked').val();
            // var url = "{{ url('') }}";
            var url = new URL(main_url +'/get_home_manufacturers');
            console.log(url);
            $.get(url + '?type=' + type + '&sub_type=' + sub_type + '&main=1', function(data) {

                let response = data.data;

                if (data.manu_more_data['value'] > data.total_count) {
                    document.getElementById('manufacturer_more').style.display = "none";
                } else {
                    document.getElementById('manufacturer_more').style.display = "block";
                }
                if (response.length > 0) {
                    $.each(response, function(key, value) {
                        manufacturer_id_check_array.push(value.manuId);
                        $('.manufacturer_normal_option').append($(
                            '<div class="manufacturer_option" id="manu_id" data-manufacturer_id="' +
                            value.manuId + '">').html(value.manuName));
                    });

                } else {
                    console.log(data)
                    $('.manufacturer_normal_option').append(
                        "<span style='color:red;text-align:center;font-size:13px'>No Record Found</span>");
                }




            })
        }
        $('.more.manufacturer_more').click(function(event) {
            document.getElementById('manufacturer_load_icon').style.display = "block";
            var sub_type = $('input[name="sub_type"]:checked').val();
            var main_type = $('input[name="type"]:checked').val();
            if (sub_type == "V" || sub_type == "L" || sub_type == "B") {
                $('#p_type').prop('checked', true);
                $('#o_type').prop('checked', false);
            } else if (sub_type == "C" || sub_type == "T" || sub_type == "M" || sub_type == "A" || sub_type ==
                "K") {
                $('#o_type').prop('checked', true);
                $('#p_type').prop('checked', false);
            } else {
                if (main_type == "O" && sub_type == "home") {
                    $('#p_type').prop('checked', false);
                    $('#o_type').prop('checked', true);
                } else if (main_type == "P" && sub_type == "home") {
                    $('#p_type').prop('checked', true);
                    $('#o_type').prop('checked', false);
                }

            }
            var type = $('input[name="type"]:checked').val();
            var url = new URL(main_url +'/get_home_manufacturers');
            $.get(url + '?type=' + type + '&sub_type=' + sub_type + '&load=1', function(data) {

                let response = data.data;
                console.log(data)
                document.getElementById('manufacturer_load_icon').style.display = "none";
                if (data.manu_more_data['value'] > data.total_count) {
                    document.getElementById('manufacturer_more').style.display = "none";
                } else {
                    document.getElementById('manufacturer_more').style.display = "block";
                }
                $.each(response, function(key, value) {
                    if (!manufacturer_id_check_array.includes(value.manuId)) {
                        manufacturer_id_check_array.push(value.manuId);
                        $('.manufacturer_normal_option').append($(
                            '<div class="manufacturer_option" id="manu_id" data-manufacturer_id="' +
                            value.manuId + '">').html(value.manuName));
                    }

                });
            })
            event.stopPropagation();


        })