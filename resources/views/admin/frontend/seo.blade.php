@extends('admin.layouts.app')

@section('panel')
    <div class="row">
        <div class="col-lg-12 col-md-12 mb-30">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.frontend.sections.content', 'seo') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="type" value="data">
                        <div class="col-xl-12">
                                <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.css">
                                <style>
                                    .tagify { display:block; width:100%; padding:8px; border:1px solid #ced4da; border-radius:4px; background:#fff; font-family:inherit; font-size:14px; min-height:120px; max-height:400px; overflow:visible; transition:height .2s; }
                                    .tagify__input { display:inline-block; width:100%; padding:5px; background:transparent; border:none; outline:none; font-size:14px; line-height:normal; }
                                    .tagify__tag { padding:2px 6px; margin:3px 5px; white-space:nowrap; }
                                </style>
                                <div class="form-group">
                                    <label>@lang('Meta Keywords')</label>
                                    <small class="ms-2 mt-2">@lang('Type a keyword and press') <code>@lang('enter')</code> @lang('or') <code>,</code> @lang('to add it')</small>
                                    <input id="keywords-input" class="form-control" type="text"
                                           value="{{ isset($seo->data_values->keywords) && is_array($seo->data_values->keywords) ? implode(',', $seo->data_values->keywords) : '' }}">
                                </div>
                                <div class="form-group">
                                    <label>@lang('Meta Robots') <small>(@lang('optional'))</small></label>
                                    <input type="text" class="form-control" name="meta_robots" value="{{ isset($seo->data_values->meta_robots) ? $seo->data_values->meta_robots : '' }}" placeholder="e.g. noindex, follow">
                                </div>
                                <div class="form-group">
                                    <label>@lang('Meta Description')</label>
                                    <textarea name="description" rows="3" class="form-control" required>{{ isset($seo->data_values->description) ? $seo->data_values->description : '' }}</textarea>
                                </div>
                                <div class="form-group">
                                    <label>@lang('Social Title')</label>
                                    <input type="text" class="form-control" name="social_title" value="{{ isset($seo->data_values->social_title) ? $seo->data_values->social_title : '' }}" required>
                                </div>
                                <div class="form-group">
                                    <label>@lang('Social Description')</label>
                                    <textarea name="social_description" rows="3" class="form-control" required>{{ isset($seo->data_values->social_description) ? $seo->data_values->social_description : '' }}</textarea>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn--primary w-100 h-45">@lang('Submit')</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
<script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify"></script>
<script>
(function() {
    var inputEl = document.getElementById('keywords-input');
    if (!inputEl) return;
    var form = inputEl.closest('form');
    var tagify = new Tagify(inputEl, { delimiters: ",", dropdown: { enabled: 0 } });

    tagify.on('input', function() {
        tagify.DOM.scope.style.height = 'auto';
    });

    form.addEventListener('submit', function() {
        form.querySelectorAll('input[name="keywords[]"]').forEach(function(el) { el.remove(); });
        tagify.value.forEach(function(tag) {
            var h = document.createElement('input');
            h.type = 'hidden';
            h.name = 'keywords[]';
            h.value = tag.value;
            form.appendChild(h);
        });
    });
})();
</script>
@endpush
