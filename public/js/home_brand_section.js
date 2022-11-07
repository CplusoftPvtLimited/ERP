var main_url = document.getElementById('app_url').value;

$('.dropdown-header.brands').click(function(event) {
    $('.dropdown-content.brands_content').toggle();
    event.stopPropagation();
})



$('.option.more').click(function(event) {
    var count = document.getElementById('brand_count').value;
    document.getElementById('brand_load_icon').style.display = "block";
    $.ajax({
        url: main_url+ '/load_more_brand',
        method: "GET",
        success: function(data) {
            let view_html = "";
            document.getElementById('brand_load_icon').style.display = "none";
            $.each(data.brands, function(key, value) {
                $('.normal-option').append($('<div class="option" data-brand_id="' +
                    value.brandId + '">').html(value.brandName));
            });

            if (data.count >= count) {
                $('.option.more').hide();
            }


        }
    });
    event.stopPropagation();


})
/// load more script for brands  end

// load more script for sub section by brands
var section_id_check_array = [];
$('.dropdown-header.product_group').click(function(event) {
    $('.dropdown-content.product_group_content').toggle();
    event.stopPropagation();
})
var brand_id_save = "";
$(document.body).on('click', '.option:not(.more)', function(event) { // click on brand to get sections
    var brand_id = $(this).data('brand_id');
    brand_id_save = $(this).data('brand_id');
    $('.dropdown-header.brands').html($(this).html());
    $('.dropdown-content.brands_content').toggle();
    section_id_check_array = [];
    var url = new URL(main_url+'/get_sub_sections_by_brand');
    $.get(url + '?brand_id=' + brand_id, function(data) {


        let response = data;

        if (response.length <= 0) {
            $('.product_group_normal_option').empty();
            $('.dropdown-header.product_group').html("Select Product Group");
            // $('.more.product_group_more').hide();
        } else {
            // $('.more.product_group_more').hide();
        }
        let view_html = `<option value="">Select One</option>`;
        $.each(response, function(key, value) {

            section_id_check_array.push(value.assemblyGroupNodeId);
            $('.product_group_normal_option').append($(
                '<div class="product_group_option" data-section_id="' +
                value.assemblyGroupNodeId + '">').html(value.assemblyGroupName));
            // $.each(value.sub_section, function(key_2, value_2) {


            //         $('.product_group_normal_option').append($('<div class="option" data-section_id="' +
            //         value_2.assemblyGroupNodeId + '">').html(value_2.assemblyGroupName));
            // });
        });

    })
})

$('.more.product_group_more').click(function(event) {
    var brand_id = brand_id_save
    // $('.dropdown-header.brands').html($(this).html());


    var url = new URL(main_url+'/get_sub_sections_by_brand');
    $.get(url + '?brand_id=' + brand_id, function(data) {


        let response = data;

        let view_html = `<option value="">Select One</option>`;
        $.each(response, function(key, value) {

            if (!section_id_check_array.includes(value.assemblyGroupNodeId)) {
                $('.product_group_normal_option').append($(
                    '<div class="product_group_option" data-section_id="' +
                    value.assemblyGroupNodeId + '">').html(value.assemblyGroupName));
                section_id_check_array.push(value.assemblyGroupNodeId);
            }

            // $.each(value.sub_section, function(key_2, value_2) {


            //         $('.product_group_normal_option').append($('<div class="option" data-section_id="' +
            //         value_2.assemblyGroupNodeId + '">').html(value_2.assemblyGroupName));
            // });
        });

    });
    event.stopPropagation();
})

$(document.body).on('click', '.product_group_option:not(.product_group_more)', function(
    event) { // click on brand to get sections
    var section_id = $(this).data('section_id');
    $('.dropdown-content.product_group_content').toggle();
    $('#sub_section_id').val(section_id);
    $('.dropdown-header.product_group').html($(this).html());
    event.stopPropagation();
})

// load more script for sub section by brands end =========================
