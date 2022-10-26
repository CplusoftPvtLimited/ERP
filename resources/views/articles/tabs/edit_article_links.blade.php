<div class="card-header">
    <div class="row">
        <div class="col-md-6">
            <h4>{{ trans('file.Edit Product Links') }}</h4>
        </div>
        <div class="col-md-6">
            <a href="{{ route('article.index') }}" class="btn btn-primary float-right">Back</a>
        </div>
    </div>
</div>
<div class="card-body">
    <p class="italic"><small>{{ trans('file.The field labels marked with * are required input fields') }}.</small></p>
    @if (Session::has('error'))
        <p class="bg-danger text-white p-2 rounded">{{ Session::get('error') }}</p>
    @endif
    @if (Session::has('success'))
        <p class="bg-success text-white p-2 rounded">{{ Session::get('success') }}</p>
    @endif
    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <strong>Whoops!</strong> There were some problems with your input.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form
        action="{{ isset($article->articleLink->id) ? route('articleLinks.update', $article->articleLink->id) : route('articleLinks.store') }}"
        method="post" id="linksForm" enctype="multipart/form-data">
        @csrf
        @if (isset($article->articleLink->id))
            @method('PUT')
        @else
            @method('POST')
        @endif

        <div class="row">
            <div class="col-12">
                <div class="other_data"></div>
                <div class="row">
                    <div class="col-4">
                        <div class="form-group">
                            <h6>Url *</h6>
                            <input type="url" name="url" id="linkUrl" class="form-control"
                                value="{{ isset($article->articleLink->url) ? $article->articleLink->url : '' }}" required>
                        </div>
                    </div>
                    <input type="hidden" name="legacyArticleId" id="links_articleId" class="form-control"
                        value="{{ isset($article->id) ? $article->legacyArticleId : '' }}" readonly required>
                    <div class="col-4">
                        <div class="form-group">
                            <h6>Language</h6>
                            <select name="lang" id="linkLang" class="form-control">
                                <option value="">--Select One--</option>
                                @foreach ($languages as $language)
                                    <option value="{{ $language->lang }}"
                                        {{ isset($article->articleLink->lang) ? ($language->lang == $article->articleLink->lang ? 'selected' : '') : '' }}>
                                        {{ $language->lang }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-4">
                        <h6>Description</h6>
                        <textarea name="description" id="linkDescription" cols="10" rows="5 " class="form-control">{{ isset($article->articleLink->description) ? $article->articleLink->description : '' }}</textarea>
                    </div>
                </div>
                <div class="d-flex flex-row-reverse">
                    <button type="submit" id="saveLink" class="btn btn-success" style="width:100px">Update</button>

                </div>
            </div>
        </div>
    </form>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"
    integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
