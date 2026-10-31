<x-app-layout>
    @if($sections != null)
        @foreach(json_decode($sections) as $sec)
            @include($activeTemplate . 'sections.' . $sec)
        @endforeach
    @endif
</x-app-layout>
