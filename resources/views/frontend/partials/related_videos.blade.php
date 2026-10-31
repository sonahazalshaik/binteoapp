@foreach($relatedVideos as $video)
    @include('frontend.partials.video_card_list', ['related' => $video])
@endforeach
