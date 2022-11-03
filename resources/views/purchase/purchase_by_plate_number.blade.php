<link href="https://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-1.10.2.js"></script>
<script src="https://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
<div class="row">
    <div class="col-md-12">
        <div id="other_data"></div>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <div class="row">
                        {{-- <div class="col-md-4 mt-3">
                                <label for="">Enter Number Plate </label>
                                <input id="plate_number" class="form-control">
                                <input type="text" name="number plate" pattern="^([A-Za-z]{2}-?[0-9]{3}-?[A-Za-z]{2})?([0-9]{4}-?[A-Za-z]{2}-?[0-9]{2})?([0-9]{3}-?[A-Za-z]{3}-?[0-9]{2})?$" title="French Number Plate">
                        </div> --}}
                        <div class="col-md-4 mt-3">
                                <label for="">Plate Number</label>
                                <input id="plate_number" class="form-control" placeholder="156-TU-2999">
                        </div>
                        {{-- <div class="col-md-4 mt-3">
                                <label for="">DROIT MIL: </label>
                                <input id="droit_mil" class="form-control">
                        </div> --}}
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <button type="button" class="btn btn-info search-btn"
                        id="search-btn">{{ trans('file.Search') }}</button>
                    </div>
                </div>
                
             </div>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-1.12.4.min.js"
    integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ=" crossorigin="anonymous"></script>

<script>
    $(document).ready(function() {
        console.log('sadsdasd');
        $(".search-btn").click(function() {
            var plate_number = $('#plate_number').val();
            $.ajax({
                method: "GET",
                url: "{{ url('get_chasis_number') }}",
                data:{
                    plate_number: plate_number,
                },
                success: function (data) {
                    if (data.data == 1) {
                        console.log(data.data)
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: "plate no is not correct!",
                        });
                        exit();
                    } else if (data.data == 2) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: "chassis no not found!",
                        });
                        exit();
                    } else {
                        Swal.fire({
                            icon: 'success',
                            title: 'Chassis Number :',
                            text: data.data.CHASSIS,
                        });
                        exit();
                    } 
                },
            });
        });
    });
</script>
