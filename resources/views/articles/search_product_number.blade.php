<div class="container">
    <form action="{{ route('article.index') }}">
        <h4>Product Number</h4>
        <div class="row">
            <div class="col-md-8 mt-3">
                <div class="ui-widget">
                    {{-- <label for="automplete-1">Product Number: </label> --}}
                    <input id="automplete-1" name="article_id" class="form-control" placeholder="e.g. 1000 1000 1000">
                </div>

            </div>
            <div class="col-md-4 mt-3 pull-right" style="text-align: right">
                <button type="button" class="btn btn-primary purchase-save-btn"
                    id="save-btn">{{ trans('file.Search') }}</button>
            </div>
        </div>
    </form>
</div>
<script>
    $(function() {
        var name = $('#automplete-1').val();
        console.log("?article_id=0+001+106+017")
        $.ajax({
            method: "GET",
            url: "{{ url('articlesByReferenceNo') }}",
            data: {
                name: name
            },

            success: function(data) {

                let response = data.data.data;

                var html = "";
                var articleNumbers = [];
                $.each(response, function(key, value) {
                    if (value != null) {
                        articleNumbers.push(value.articleNumber)
                    }

                });

                $("#automplete-1").autocomplete({
                    source: articleNumbers
                });



            },
            error: function(error) {
                console.log(error);
            }
        });

        
    });
</script>
