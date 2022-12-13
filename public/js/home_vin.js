var model_id_set = -1;

$(document).ready(function() {
    console.log('sadsdasd');
    $("#search-btn").click(function() {
        var plate_number = $('#plate_number').val();
        // alert(plate_number)
        document.getElementById('plate_load_icon').style.display = "inline-block";

        $.ajax({
            method: "GET",
            url: "/get_chasis_number",
            data: {
                plate_number: plate_number,
            },
            success: function(data) {
                document.getElementById('plate_engine_sub_type').value = data.sub_type;
                document.getElementById('plate_engine_type').value = data.type;
                if(data.data == "exceed"){
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: data.message,
                    });
                    document.getElementById('plate_load_icon').style.display = "none";
                    exit();
                }
                if (data.data == 1) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: "plate no is not correct!",
                    });
                    document.getElementById('plate_load_icon').style.display = "none";
                    exit();
                } else if (data.data == 2) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: "chassis no not found!",
                    });
                    document.getElementById('plate_load_icon').style.display = "none";
                    exit();
                } else {
                    var model_id = data.data.modelId;
                    var model_name = data.data.modelname;
                    var manu_id = data.data.manuId;
                    model_id_set = model_id;
                    let url = "{{ route('get_purchase_plate_engine_by_model') }}";

                    $('.plate_engine_normal_option').empty();

                    $.get(url + '?model_id=' + model_id + "&main=1",
                        function(data) {
                            if (data.data == "no") {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: "Data not found",
                                });
                                document.getElementById('plate_engine_load_icon')
                                    .style.display = "none";
                                exit();
                            }
                            let response = data.data;
                            document.getElementById('plate_load_icon').style
                                .display = "none";
                            document.getElementById('model_name').value =
                            model_name;
                            document.getElementById('model_id').value = model_id;
                            document.getElementById('manufacturer_id').value =
                                manu_id;
                            document.getElementById('plate_engine_load_icon').style
                                .display = "none";
                            if (data.load_more_plate_engine_value > data
                                .total_count) {
                                document.getElementById('plate_engine_more').style
                                    .display = "none";
                            } else {
                                document.getElementById('plate_engine_more').style
                                    .display = "block";
                            }
                            if (response.length > 0) {
                                $.each(response, function(key, value) {
                                    engine_id_check_array.push(value
                                        .linkageTargetId);
                                    $('.plate_engine_normal_option').append(
                                        $(
                                            '<div class="plate_engine_option" data-engine_id="' +
                                            value.linkageTargetId + '">'
                                        ).html(value.description +
                                            "(" +
                                            value
                                            .beginYearMonth + " - " +
                                            value.endYearMonth));
                                });
                            } else {
                                $('.engine_normal_option').append(
                                    "<span style='color:red;text-align:center;font-size:13px'>No Record Found</span>"
                                );
                            }

                        })

                }
            },
        });
    });
});

// Filter Functions
function filterPlateEngine() {
    var input, filter, ul, li, a, i;
    input = document.getElementById("plate_engine_input_search");
    filter = input.value.toUpperCase();
    if (input.value) {
        document.getElementById('plate_engine_more').style.display = "none";
    } else {
        document.getElementById('plate_engine_more').style.display = "block";
    }
    div = document.getElementsByClassName("plate_engine_normal_option");
    a = document.getElementsByClassName("plate_engine_option");
    for (i = 0; i < a.length; i++) {
        txtValue = a[i].textContent || a[i].innerText;
        if (txtValue.toUpperCase().indexOf(filter) > -1) {
            a[i].style.display = "";
        } else {
            a[i].style.display = "none";
        }
    }

}

function filterPlateSection() {
    var input, filter, ul, li, a, i;
    input = document.getElementById("plate_section_input_search");
    filter = input.value.toUpperCase();
    if (input.value) {
        document.getElementById('plate_section_more').style.display = "none";
    } else {
        document.getElementById('plate_section_more').style.display = "block";
    }
    div = document.getElementsByClassName("plate_section_normal_option");
    a = document.getElementsByClassName("plate_section_option");
    for (i = 0; i < a.length; i++) {
        txtValue = a[i].textContent || a[i].innerText;
        if (txtValue.toUpperCase().indexOf(filter) > -1) {
            a[i].style.display = "";
        } else {
            a[i].style.display = "none";
        }
    }

}

function filterPlateSectionPart() {
    var input, filter, ul, li, a, i;
    input = document.getElementById("plate_section_part_input_search");
    filter = input.value.toUpperCase();
    if (input.value) {
        document.getElementById('plate_section_part_more').style.display = "none";
    } else {
        document.getElementById('plate_section_part_more').style.display = "block";
    }
    div = document.getElementsByClassName("plate_section_part_normal_option");
    a = document.getElementsByClassName("plate_section_part_option");
    for (i = 0; i < a.length; i++) {
        txtValue = a[i].textContent || a[i].innerText;
        if (txtValue.toUpperCase().indexOf(filter) > -1) {
            a[i].style.display = "";
        } else {
            a[i].style.display = "none";
        }
    }

}

////// get engines==================

$('.dropdown-header.plate_engine').click(function(event) {
    $('.dropdown-content.plate_engine_content').toggle();
    event.stopPropagation();
})
$('.more.plate_engine_more').click(function(event) {
    document.getElementById('plate_engine_load_icon').style.display = "block";
    var model_id = model_id_set;

    let url = "{{ route('get_purchase_plate_engine_by_model') }}";

    $.get(url + '?model_id=' + model_id + "&load=1",
        function(data) {
            if (data.data == "no") {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "Data not found",
                });
                document.getElementById('plate_engine_load_icon').style.display = "none";
                exit();
            }
            let response = data.data;

            document.getElementById('plate_engine_load_icon').style.display = "none";
            if (data.load_more_plate_engine_value > data.total_count) {
                document.getElementById('plate_engine_more').style.display = "none";
            } else {
                document.getElementById('plate_engine_more').style.display = "block";
            }
            $.each(response, function(key, value) {
                $('.plate_engine_normal_option').append($(
                    '<div class="plate_engine_option" data-engine_id="' +
                    value.linkageTargetId + '">').html(value.description + "(" +
                    value
                    .beginYearMonth + " - " + value.endYearMonth));

            });


        })
});


////// get sections==================
var engine_id_set = -1;
$('.dropdown-header.plate_section').click(function(event) {
    $('.dropdown-content.plate_section_content').toggle();
    event.stopPropagation();
})
$(document.body).on('click', '.plate_engine_option:not(.plate_engine_more)', function(
    event) { // click on brand to get sections
    $('.dropdown-header.plate_engine').html($(this).html());

    var engine_id = $(this).data('engine_id');
    document.getElementById('engine_id').value = engine_id;
    engine_id_set = engine_id;
    let url = "{{ route('get_purchase_plate_section_by_engine') }}";
    $('.dropdown-content.plate_engine_content').toggle();
    $('.plate_section_normal_option').empty();
    $.get(url + '?engine_id=' + engine_id + "&main=1",
        function(data) {
            let response = data.data;
            var engine = data.engine;
            document.getElementById('plate_section_load_icon').style.display = "none";
            if (data.load_more_plate_section_value > data.total_count) {
                document.getElementById('plate_section_more').style.display = "none";
            } else {
                document.getElementById('plate_section_more').style.display = "block";
            }
            $('#plate_model_year').val(engine.beginYearMonth != null ? engine.beginYearMonth : 'N/A');
            $('#plate_fuel').val(engine.fuelType != null ? engine.fuelType : 'N/A');
            $('#plate_cc').val(engine.capacityCC != null ? engine.capacityCC : 'N/A');
            if (response.length > 0) {
                $.each(response, function(key, value) {
                    $('.plate_section_normal_option').append($(
                        '<div class="plate_section_option" data-section_id="' +
                        value.assemblyGroupNodeId + '">').html(value.assemblyGroupName));
                });
            } else {
                $('.section_normal_option').append(
                    "<span style='color:red;text-align:center;font-size:13px'>No Record Found</span>"
                );
            }

        })

})
$('.more.plate_section_more').click(function(event) {
    document.getElementById('plate_section_load_icon').style.display = "block";
    var engine_id = engine_id_set;

    let url = "{{ route('get_purchase_plate_section_by_engine') }}";

    $.get(url + '?engine_id=' + engine_id + "&load=1",
        function(data) {
            if (data.data == "no") {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "Data not found",
                });
                document.getElementById('plate_section_load_icon').style.display = "none";
                exit();
            }
            let response = data.data;

            document.getElementById('plate_section_load_icon').style.display = "none";
            if (data.load_more_plate_section_value > data.total_count) {
                document.getElementById('plate_section_more').style.display = "none";
            } else {
                document.getElementById('plate_section_more').style.display = "block";
            }
            $.each(response, function(key, value) {
                $('.plate_section_normal_option').append($(
                    '<div class="plate_section_option" data-section_id="' +
                    value.assemblyGroupNodeId + '">').html(value.assemblyGroupName));

            });


        })
});


////// get section parts==================
var section_id_set = -1;
$('.dropdown-header.plate_section_part').click(function(event) {
    $('.dropdown-content.plate_section_part_content').toggle();
    event.stopPropagation();
})
$(document.body).on('click', '.plate_section_option:not(.plate_section_more)', function(
    event) { // click on brand to get sections
    $('.dropdown-header.plate_section').html($(this).html());

    var section_id = $(this).data('section_id');
    document.getElementById('section_id').value = section_id;
    section_id_set = section_id;
    let url = "{{ route('get_purchase_plate_section_part_by_section') }}";
    $('.dropdown-content.plate_section_content').toggle();
    $('.plate_section_part_normal_option').empty();
    $.get(url + '?section_id=' + section_id + "&main=1",
        function(data) {
            let response = data.data;

            if (data.load_more_plate_section_part_value > data.total_count) {
                document.getElementById('plate_section_part_more').style.display = "none";
            } else {
                document.getElementById('plate_section_part_more').style.display = "block";
            }
            if (response.length > 0) {
                $.each(response, function(key, value) {
                    $('.plate_section_part_normal_option').append($(
                        '<div class="plate_section_part_option" data-section_part_id="' +
                        value.dataSupplierId + "-" + value.legacyArticleId +
                        '">').html(value.genericArticleDescription + "-" +
                        value.articleNumber));
                });
            } else {
                $('.section_normal_option').append(
                    "<span style='color:red;text-align:center;font-size:13px'>No Record Found</span>"
                );
            }

        })

})
$('.more.plate_section_part_more').click(function(event) {
    document.getElementById('plate_section_part_load_icon').style.display = "block";
    var section_id = section_id_set;

    let url = "{{ route('get_purchase_plate_section_part_by_section') }}";

    $.get(url + '?section_id=' + section_id + "&load=1",
        function(data) {
            if (data.data == "no") {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "Data not found",
                });
                document.getElementById('plate_section_part_load_icon').style.display = "none";
                exit();
            }
            let response = data.data;

            document.getElementById('plate_section_part_load_icon').style.display = "none";
            if (data.load_more_plate_section_part_value > data.total_count) {
                document.getElementById('plate_section_part_more').style.display = "none";
            } else {
                document.getElementById('plate_section_part_more').style.display = "block";
            }
            $.each(response, function(key, value) {
                $('.plate_section_part_normal_option').append($(
                    '<div class="plate_section_part_option" data-section_part_id="' +
                    value.dataSupplierId + "-" + value.legacyArticleId +
                    '">').html(value.genericArticleDescription + "-" +
                    value.articleNumber));
            });



        })
});




