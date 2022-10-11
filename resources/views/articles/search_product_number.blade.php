<div class="container">
    <form action="{{ route('article.index') }}">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <div class="col-md-12 mt-3">
                        <div class="ui-widget">
                            <label for="automplete-1">Product Number: </label>
                            <input id="automplete-1" name="article_id" class="form-control">
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="row mr-1">
            <div class="col-md-12 text-right">
                <div class="form-group">
                    <button type="button" class="btn btn-info purchase-save-btn"
                        id="save-btn">{{ trans('file.Search') }}</button>
                </div>
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
